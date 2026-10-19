<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Course;
use App\Models\Assignment;
use App\Models\Quiz;
use App\Models\StudentBill;
use App\Models\Payment;
use App\Models\Post;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\QuizAttempt;
use App\Models\AssignmentSubmission;
use App\Models\ExamGrade;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $data = match($user->role) {
            'admin'     => $this->adminData(),
            'guru'      => $this->guruData($user),
            'siswa'     => $this->siswaData($user),
            'bendahara' => $this->bendaharaData(),
            default     => [],
        };

        return view('dashboard.index', array_merge(['user' => $user], $data));
    }

    private function adminData(): array
    {
        $avgScore = AssignmentSubmission::where('status', 'graded')->avg('score');

        return [
            'total_siswa'     => User::where('role', 'siswa')->count(),
            'total_guru'      => User::where('role', 'guru')->count(),
            'total_kelas'     => SchoolClass::count(),
            'total_mapel'     => Subject::count(),
            'total_courses'   => Course::count(),
            'total_peminatan' => Subject::where('is_elective', true)->count(),
            'total_berita'    => Post::count(),
            'avg_score'       => round($avgScore ?? 0, 1),
            'recent_posts'    => Post::with('author')->latest('published_at')->take(5)->get(),
            'recent_users'    => User::latest()->take(5)->get(),
        ];
    }

    private function guruData(User $user): array
    {
        $courses = Course::with(['subject', 'schoolClass'])
            ->where('teacher_id', $user->id)
            ->get();

        $courseIds = $courses->pluck('id');

        $avgScore = AssignmentSubmission::whereHas('assignment', function($q) use ($courseIds) {
            $q->whereIn('course_id', $courseIds);
        })->where('status', 'graded')->avg('score');

        return [
            'courses'              => $courses,
            'total_courses'        => $courses->count(),
            'total_assignments'    => Assignment::whereIn('course_id', $courseIds)->count(),
            'total_quizzes'        => Quiz::whereIn('course_id', $courseIds)->count(),
            'avg_score'            => round($avgScore ?? 0, 1),
            'pending_submissions'  => AssignmentSubmission::whereHas('assignment', function($q) use ($courseIds) {
                $q->whereIn('course_id', $courseIds);
            })->where('status', 'submitted')->count(),
            'recent_submissions'   => AssignmentSubmission::with(['student', 'assignment.course.subject'])
                ->whereHas('assignment', function($q) use ($courseIds) {
                    $q->whereIn('course_id', $courseIds);
                })->latest('submitted_at')->take(5)->get(),
        ];
    }

    private function siswaData(User $user): array
    {
        $courseIds = [];
        if ($user->class_id) {
            $courseIds = Course::where('class_id', $user->class_id)->pluck('id')->toArray();
        }

        $unpaidBills = StudentBill::where('student_id', $user->id)
            ->where('status', '!=', 'paid')
            ->with('feeType')->get();

        $recentAttempts = QuizAttempt::where('student_id', $user->id)
            ->with('quiz.course.subject')
            ->where('status', 'completed')
            ->latest()->take(3)->get();

        // Hitung Rata-Rata Nilai Tugas
        $assignmentAvg = AssignmentSubmission::where('student_id', $user->id)
            ->where('status', 'graded')
            ->whereNotNull('score')
            ->avg('score');

        // Hitung Rata-Rata Nilai Kuis
        $quizAvg = QuizAttempt::where('student_id', $user->id)
            ->where('status', 'completed')
            ->whereNotNull('score')
            ->avg('score');

        // Hitung Rata-Rata Nilai UTS & UAS
        $utsAvg = ExamGrade::where('student_id', $user->id)
            ->whereNotNull('score_uts')
            ->avg('score_uts');

        $uasAvg = ExamGrade::where('student_id', $user->id)
            ->whereNotNull('score_uas')
            ->avg('score_uas');

        $assignmentAvg = $assignmentAvg ? round($assignmentAvg, 1) : null;
        $quizAvg       = $quizAvg ? round($quizAvg, 1) : null;
        $utsAvg        = $utsAvg ? round($utsAvg, 1) : null;
        $uasAvg        = $uasAvg ? round($uasAvg, 1) : null;

        $validScores = array_filter([$assignmentAvg, $quizAvg, $utsAvg, $uasAvg], fn($val) => $val !== null);

        if (count($validScores) > 0) {
            $overallAvg = round(array_sum($validScores) / count($validScores), 1);
            $predicate = match(true) {
                $overallAvg >= 90 => 'Sangat Baik (A)',
                $overallAvg >= 80 => 'Baik (B)',
                $overallAvg >= 70 => 'Cukup (C)',
                default           => 'Perlu Perbaikan',
            };
        } else {
            $overallAvg = null;
            $predicate = 'Belum Ada Nilai';
        }

        return [
            'courses'             => Course::with(['subject', 'teacher'])
                ->whereIn('id', $courseIds)->get(),
            'total_courses'       => count($courseIds),
            'unpaid_bills'        => $unpaidBills,
            'total_unpaid'        => $unpaidBills->sum('amount'),
            'recent_attempts'     => $recentAttempts,
            'pending_submissions' => AssignmentSubmission::where('student_id', $user->id)
                ->where('status', 'submitted')->count(),
            'assignment_avg'      => $assignmentAvg !== null ? $assignmentAvg : '-',
            'quiz_avg'            => $quizAvg !== null ? $quizAvg : '-',
            'uts_avg'             => $utsAvg !== null ? $utsAvg : '-',
            'uas_avg'             => $uasAvg !== null ? $uasAvg : '-',
            'overall_avg'         => $overallAvg !== null ? $overallAvg : '-',
            'grade_predicate'     => $predicate,
        ];
    }

    private function bendaharaData(): array
    {
        $pendingPayments = Payment::with(['bill.student', 'bill.feeType'])
            ->where('status', 'pending')
            ->latest()->get();

        $thisMonth = now()->month;
        $thisYear  = now()->year;

        return [
            'pending_payments'   => $pendingPayments,
            'total_pending'      => $pendingPayments->count(),
            'total_income_month' => Payment::where('status', 'success')
                ->whereMonth('verified_at', $thisMonth)
                ->whereYear('verified_at', $thisYear)
                ->sum('amount_paid'),
            'total_paid_bills'   => StudentBill::where('status', 'paid')->count(),
            'total_unpaid_bills' => StudentBill::where('status', 'unpaid')->count(),
            'recent_payments'    => Payment::with(['bill.student', 'bill.feeType'])
                ->where('status', 'success')
                ->latest('verified_at')->take(5)->get(),
        ];
    }
}
