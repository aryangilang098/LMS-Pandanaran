<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Material;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Attendance;
use App\Models\AttendanceRecord;
use App\Models\ExamGrade;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\StudentElectiveSubject;
use Illuminate\Support\Facades\Storage;

class LmsController extends Controller
{
    // ─── COURSES ─────────────────────────────────────────────────────────────────

    public function courses(Request $request)
    {
        $user = Auth::user();

        // 1. ADMIN: Bisa masuk dan memantau SEMUA mapel di seluruh kelas
        if ($user->isAdmin()) {
            $query = Course::with(['subject', 'schoolClass', 'teacher'])
                ->withCount(['materials', 'assignments', 'quizzes']);

            if ($request->class_id) {
                $query->where('class_id', $request->class_id);
            }
            if ($request->search) {
                $query->whereHas('subject', function($q) use ($request) {
                    $q->where('name', 'like', "%{$request->search}%")
                      ->orWhere('code', 'like', "%{$request->search}%");
                });
            }

            $courses = $query->orderBy('class_id')->get();
            $classes = SchoolClass::withCount('students')->get();

            return view('lms.courses.index', compact('courses', 'classes'));
        }

        // 2. GURU: Hanya mapel yang diampu oleh guru tersebut
        if ($user->isGuru()) {
            $courses = Course::with(['subject', 'schoolClass'])
                ->withCount(['materials', 'assignments', 'quizzes'])
                ->where('teacher_id', $user->id)->get();
            return view('lms.courses.index', compact('courses'));
        }

        // 3. SISWA:
        $studentClass = $user->schoolClass;
        $isGrade10 = $studentClass ? $studentClass->isGrade10() : true;

        $query = Course::with(['subject', 'teacher', 'schoolClass'])
            ->withCount(['materials', 'assignments', 'quizzes'])
            ->where('class_id', $user->class_id);

        if ($isGrade10) {
            // Siswa kelas 10 diberikan mata pelajaran default / wajib
            $courses = $query->whereHas('subject', function($q) {
                $q->where('is_elective', false);
            })->get();
        } else {
            // Siswa kelas 11-12: Mapel wajib + Mapel peminatan yang dipilih
            $chosenElectiveSubjectIds = StudentElectiveSubject::where('student_id', $user->id)
                ->where('status', 'active')
                ->pluck('subject_id')
                ->toArray();

            $courses = $query->whereHas('subject', function($q) use ($chosenElectiveSubjectIds) {
                $q->where('is_elective', false)
                  ->orWhereIn('id', $chosenElectiveSubjectIds);
            })->get();
        }

        return view('lms.courses.index', compact('courses', 'isGrade10', 'studentClass'));
    }

    public function showCourse($id)
    {
        $user = Auth::user();
        $course = Course::with(['subject', 'teacher', 'schoolClass', 'materials', 'assignments', 'quizzes', 'attendances.records'])
            ->findOrFail($id);

        return view('lms.courses.show', compact('course', 'user'));
    }

    // ─── SISWA ELECTIVE (Peminatan Kelas 11-12) ──────────────────────────────────

    public function electives()
    {
        $user = Auth::user();
        if (!$user->isSiswa()) {
            abort(403);
        }

        $studentClass = $user->schoolClass;
        $gradeLevel = $studentClass ? $studentClass->getGradeLevel() : '10';
        $isSenior = in_array($gradeLevel, ['11', '12']);

        // Mapel peminatan yang disediakan admin untuk tingkat kelas ini
        $availableElectives = Subject::elective()
            ->where(function($q) use ($gradeLevel) {
                $q->where('grade_level', $gradeLevel)
                  ->orWhere('grade_level', '11-12')
                  ->orWhere('grade_level', 'all');
            })
            ->withCount('electiveStudents')
            ->get();

        $chosenSubjectIds = StudentElectiveSubject::where('student_id', $user->id)
            ->where('status', 'active')
            ->pluck('subject_id')
            ->toArray();

        return view('lms.electives.index', compact('user', 'studentClass', 'gradeLevel', 'isSenior', 'availableElectives', 'chosenSubjectIds'));
    }

    public function toggleElective(Request $request)
    {
        $user = Auth::user();
        if (!$user->isSiswa()) {
            abort(403);
        }

        $studentClass = $user->schoolClass;
        if (!$studentClass || !$studentClass->isSeniorGrade()) {
            return back()->with('error', 'Pemilihan mata pelajaran peminatan hanya untuk siswa kelas 11 dan 12. Siswa kelas 10 mengikuti kurikulum paket default.');
        }

        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'action'     => 'required|in:enroll,drop',
        ]);

        $subject = Subject::where('id', $request->subject_id)->where('is_elective', true)->firstOrFail();

        if ($request->action === 'enroll') {
            StudentElectiveSubject::updateOrCreate(
                [
                    'student_id'    => $user->id,
                    'subject_id'    => $subject->id,
                    'academic_year' => $studentClass->academic_year ?? '2025/2026',
                ],
                [
                    'status' => 'active',
                ]
            );
            return back()->with('success', "Berhasil memilih mata pelajaran peminatan: {$subject->name}!");
        } else {
            StudentElectiveSubject::where('student_id', $user->id)
                ->where('subject_id', $subject->id)
                ->delete();
            return back()->with('success', "Mata pelajaran peminatan {$subject->name} berhasil dibatalkan.");
        }
    }

    // ─── MATERIALS ───────────────────────────────────────────────────────────────

    public function createMaterial($courseId)
    {
        $course = Course::findOrFail($courseId);
        $this->authorizeTeacher($course);
        return view('lms.materials.create', compact('course'));
    }

    public function storeMaterial(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
        $this->authorizeTeacher($course);

        $request->validate([
            'title'         => 'required|string|max:255',
            'content'       => 'nullable|string',
            'file_type'     => 'required|in:pdf,docx,video,link',
            'external_link' => 'nullable|url',
            'file'          => 'nullable|file|max:20480',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('materials', 'public');
        }

        Material::create([
            'course_id'     => $course->id,
            'title'         => $request->title,
            'content'       => $request->content,
            'file_path'     => $filePath,
            'file_type'     => $request->file_type,
            'external_link' => $request->external_link,
            'created_by'    => Auth::id(),
        ]);

        return redirect()->route('lms.course.show', $course->id)
            ->with('success', 'Materi berhasil ditambahkan!');
    }

    // ─── ASSIGNMENTS ─────────────────────────────────────────────────────────────

    public function createAssignment($courseId)
    {
        $course = Course::findOrFail($courseId);
        $this->authorizeTeacher($course);
        return view('lms.assignments.create', compact('course'));
    }

    public function storeAssignment(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
        $this->authorizeTeacher($course);

        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date'    => 'required|date|after:now',
            'max_score'   => 'required|integer|min:1|max:100',
        ]);

        Assignment::create([
            'course_id'   => $course->id,
            'title'       => $request->title,
            'description' => $request->description,
            'due_date'    => $request->due_date,
            'max_score'   => $request->max_score,
            'created_by'  => Auth::id(),
        ]);

        return redirect()->route('lms.course.show', $course->id)
            ->with('success', 'Tugas berhasil dibuat!');
    }

    public function showAssignment($id)
    {
        $user = Auth::user();
        $assignment = Assignment::with(['course.subject', 'submissions.student'])->findOrFail($id);
        $mySubmission = $assignment->submissionForStudent($user->id);
        return view('lms.assignments.show', compact('assignment', 'mySubmission', 'user'));
    }

    public function submitAssignment(Request $request, $id)
    {
        $request->validate([
            'student_note' => 'nullable|string',
            'file'         => 'nullable|file|max:20480',
        ]);

        $assignment = Assignment::findOrFail($id);
        $user = Auth::user();

        $existing = AssignmentSubmission::where('assignment_id', $id)
            ->where('student_id', $user->id)->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah mengumpulkan tugas ini sebelumnya.');
        }

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('submissions', 'public');
        }

        AssignmentSubmission::create([
            'assignment_id' => $id,
            'student_id'    => $user->id,
            'file_path'     => $filePath,
            'student_note'  => $request->student_note,
            'status'        => 'submitted',
            'submitted_at'  => now(),
        ]);

        return back()->with('success', 'Tugas berhasil dikumpulkan!');
    }

    public function gradeSubmission(Request $request, $submissionId)
    {
        $request->validate([
            'score'            => 'required|integer|min:0|max:100',
            'teacher_feedback' => 'nullable|string',
        ]);

        $submission = AssignmentSubmission::findOrFail($submissionId);
        $submission->update([
            'score'            => $request->score,
            'teacher_feedback' => $request->teacher_feedback,
            'status'           => 'graded',
        ]);

        return back()->with('success', 'Nilai berhasil disimpan!');
    }

    // ─── QUIZZES ─────────────────────────────────────────────────────────────────

    public function showQuiz($id)
    {
        $user = Auth::user();
        $quiz = Quiz::with(['course.subject', 'questions'])->findOrFail($id);
        $attempt = $quiz->attemptForStudent($user->id);
        return view('lms.quizzes.show', compact('quiz', 'attempt', 'user'));
    }

    public function startQuiz($id)
    {
        $user = Auth::user();
        $quiz = Quiz::with('questions')->findOrFail($id);

        $existing = QuizAttempt::where('quiz_id', $id)
            ->where('student_id', $user->id)->first();

        if ($existing) {
            if ($existing->status === 'completed') {
                return redirect()->route('lms.quiz.result', $existing->id);
            }
            return view('lms.quizzes.take', compact('quiz', 'existing', 'user'));
        }

        $attempt = QuizAttempt::create([
            'quiz_id'         => $id,
            'student_id'      => $user->id,
            'status'          => 'in_progress',
            'started_at'      => now(),
            'total_questions' => $quiz->questions->count(),
        ]);

        return view('lms.quizzes.take', compact('quiz', 'attempt', 'user'));
    }

    public function submitQuiz(Request $request, $id)
    {
        $user = Auth::user();
        $quiz = Quiz::with('questions')->findOrFail($id);
        $attempt = QuizAttempt::where('quiz_id', $id)
            ->where('student_id', $user->id)
            ->where('status', 'in_progress')
            ->firstOrFail();

        $answers = $request->input('answers', []);
        $totalCorrect = 0;
        $totalScore = 0;

        foreach ($quiz->questions as $question) {
            $given = $answers[$question->id] ?? null;
            if ($given && strtoupper($given) === strtoupper($question->correct_option)) {
                $totalCorrect++;
                $totalScore += $question->score_weight;
            }
        }

        $attempt->update([
            'answers_json'   => $answers,
            'score'          => $totalScore,
            'total_correct'  => $totalCorrect,
            'total_questions'=> $quiz->questions->count(),
            'status'         => 'completed',
            'completed_at'   => now(),
        ]);

        return redirect()->route('lms.quiz.result', $attempt->id);
    }

    public function quizResult($attemptId)
    {
        $user = Auth::user();
        $attempt = QuizAttempt::with(['quiz.questions', 'quiz.course.subject'])->findOrFail($attemptId);

        if ($attempt->student_id !== $user->id && !$user->isGuru() && !$user->isAdmin()) {
            abort(403);
        }

        return view('lms.quizzes.result', compact('attempt', 'user'));
    }

    // ─── ATTENDANCE ──────────────────────────────────────────────────────────────

    public function attendance($courseId)
    {
        $user = Auth::user();
        $course = Course::with(['schoolClass', 'subject', 'teacher'])->findOrFail($courseId);
        $attendances = Attendance::with('records.student')->where('course_id', $courseId)->latest('date')->get();
        $students = User::where('role', 'siswa')
            ->where('class_id', $course->class_id)
            ->orderBy('name')
            ->get();
        return view('lms.attendances.index', compact('course', 'attendances', 'students', 'user'));
    }

    public function storeAttendance(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
        $this->authorizeTeacher($course);

        $request->validate([
            'title'    => 'required|string',
            'date'     => 'required|date',
            'statuses' => 'required|array',
        ]);

        $attendance = Attendance::create([
            'course_id' => $courseId,
            'title'     => $request->title,
            'date'      => $request->date,
        ]);

        foreach ($request->statuses as $studentId => $status) {
            AttendanceRecord::create([
                'attendance_id' => $attendance->id,
                'student_id'    => $studentId,
                'status'        => $status,
                'note'          => $request->notes[$studentId] ?? null,
            ]);
        }

        return back()->with('success', 'Presensi berhasil disimpan!');
    }

    // ─── EXAM GRADES (UTS & UAS) ──────────────────────────────────────────────────

    public function showExamGrades($courseId)
    {
        $user = Auth::user();
        $course = Course::with(['subject', 'schoolClass', 'teacher'])->findOrFail($courseId);

        $students = User::where('role', 'siswa')
            ->where('class_id', $course->class_id)
            ->orderBy('name')
            ->get();

        $examGrades = ExamGrade::where('course_id', $courseId)
            ->get()
            ->keyBy('student_id');

        return view('lms.exam_grades.index', compact('course', 'students', 'examGrades', 'user'));
    }

    public function storeExamGrades(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
        $this->authorizeTeacher($course);

        $request->validate([
            'grades'               => 'required|array',
            'grades.*.score_uts'   => 'nullable|numeric|min:0|max:100',
            'grades.*.score_uas'   => 'nullable|numeric|min:0|max:100',
            'grades.*.notes'       => 'nullable|string',
        ]);

        foreach ($request->grades as $studentId => $data) {
            $scoreUts = is_numeric($data['score_uts'] ?? null) ? $data['score_uts'] : null;
            $scoreUas = is_numeric($data['score_uas'] ?? null) ? $data['score_uas'] : null;

            if ($scoreUts !== null || $scoreUas !== null) {
                ExamGrade::updateOrCreate(
                    [
                        'course_id'  => $courseId,
                        'student_id' => $studentId,
                    ],
                    [
                        'score_uts'  => $scoreUts,
                        'score_uas'  => $scoreUas,
                        'notes'      => $data['notes'] ?? null,
                        'created_by' => Auth::id(),
                    ]
                );
            }
        }

        return back()->with('success', 'Nilai UTS & UAS berhasil disimpan!');
    }

    // ─── HELPERS ─────────────────────────────────────────────────────────────────

    private function authorizeTeacher(Course $course)
    {
        $user = Auth::user();
        if ($user->isGuru() && $course->teacher_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke kelas ini.');
        }
        if ($user->isSiswa()) {
            abort(403, 'Siswa tidak dapat melakukan tindakan ini.');
        }
    }
}
