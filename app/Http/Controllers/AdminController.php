<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Course;
use App\Models\Post;
use App\Models\FeeType;
use App\Models\Registration;
use App\Models\ContactMessage;
use App\Models\StudentElectiveSubject;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    // ─── USERS ───────────────────────────────────────────────────────────────────

    public function users(Request $request)
    {
        $query = User::with('schoolClass');

        if ($request->role) {
            $query->where('role', $request->role);
        }
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('identity_number', 'like', "%{$request->search}%");
            });
        }

        $users   = $query->latest()->paginate(20);
        $classes = SchoolClass::all();
        return view('admin.users.index', compact('users', 'classes'));
    }

    public function createUser()
    {
        $classes = SchoolClass::all();
        return view('admin.users.create', compact('classes'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email',
            'role'            => 'required|in:admin,guru,siswa,bendahara',
            'password'        => 'required|string|min:8',
            'identity_number' => 'nullable|string|max:20',
            'phone'           => 'nullable|string|max:20',
            'gender'          => 'required|in:L,P',
            'class_id'        => 'nullable|exists:classes,id',
            'address'         => 'nullable|string',
        ]);

        User::create([
            'name'            => $request->name,
            'email'           => $request->email,
            'role'            => $request->role,
            'password'        => Hash::make($request->password),
            'identity_number' => $request->identity_number,
            'phone'           => $request->phone,
            'gender'          => $request->gender,
            'class_id'        => $request->class_id,
            'address'         => $request->address,
        ]);

        return redirect()->route('admin.users')->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function editUser($id)
    {
        $user    = User::findOrFail($id);
        $classes = SchoolClass::all();
        return view('admin.users.edit', compact('user', 'classes'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role'  => 'required|in:admin,guru,siswa,bendahara',
        ]);

        $user->update($request->except(['password', '_token', '_method']) + [
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        return redirect()->route('admin.users')->with('success', 'Data pengguna berhasil diperbarui!');
    }

    public function deleteUser($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'Pengguna berhasil dihapus!');
    }

    // ─── POSTS ───────────────────────────────────────────────────────────────────

    public function posts(Request $request)
    {
        $posts = Post::with('author')
            ->when($request->category, fn($q) => $q->where('category', $request->category))
            ->latest('published_at')->paginate(20);
        return view('admin.posts.index', compact('posts'));
    }

    public function createPost(Request $request)
    {
        $selectedCategory = $request->category ?? 'berita';
        return view('admin.posts.create', compact('selectedCategory'));
    }

    public function storePost(Request $request)
    {
        $request->validate([
            'title'    => 'required|string|max:255',
            'category' => 'required|in:berita,agenda,prestasi,pengumuman',
            'content'  => 'required|string',
            'excerpt'  => 'nullable|string|max:500',
        ]);

        Post::create([
            'title'        => $request->title,
            'slug'         => Str::slug($request->title) . '-' . Str::random(6),
            'category'     => $request->category,
            'content'      => $request->content,
            'excerpt'      => $request->excerpt,
            'is_featured'  => $request->boolean('is_featured'),
            'event_date'   => $request->event_date,
            'author_id'    => auth()->id(),
            'published_at' => now(),
        ]);

        return redirect()->route('admin.posts')->with('success', 'Artikel/Berita berhasil dipublikasikan!');
    }

    public function deletePost($id)
    {
        Post::findOrFail($id)->delete();
        return back()->with('success', 'Artikel berhasil dihapus!');
    }

    // ─── CLASSES ─────────────────────────────────────────────────────────────────

    public function classes()
    {
        $classes = SchoolClass::withCount('students')->get();
        return view('admin.classes.index', compact('classes'));
    }

    public function storeClass(Request $request)
    {
        $request->validate(['name' => 'required|string|max:50', 'academic_year' => 'required|string']);
        SchoolClass::create($request->only('name', 'academic_year', 'homeroom_teacher_name'));
        return back()->with('success', 'Kelas berhasil ditambahkan!');
    }

    // ─── SUBJECTS (MATA PELAJARAN) ──────────────────────────────────────────────

    public function subjects(Request $request)
    {
        $query = Subject::withCount('courses');

        if ($request->category) {
            $query->where('category', $request->category);
        }
        if ($request->grade_level) {
            $query->forGrade($request->grade_level);
        }
        if ($request->type === 'elective') {
            $query->elective();
        } elseif ($request->type === 'required') {
            $query->required();
        }

        $subjects = $query->orderBy('name')->get();
        return view('admin.subjects.index', compact('subjects'));
    }

    public function storeSubject(Request $request)
    {
        $request->validate([
            'code'        => 'required|string|max:20|unique:subjects,code',
            'name'        => 'required|string|max:255',
            'category'    => 'required|in:keagamaan,umum,peminatan',
            'grade_level' => 'required|in:all,10,11,12,11-12',
            'is_elective' => 'required|boolean',
        ]);

        Subject::create($request->only('code', 'name', 'category', 'grade_level', 'is_elective'));

        return back()->with('success', 'Mata pelajaran berhasil ditambahkan!');
    }

    public function updateSubject(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);
        $request->validate([
            'code'        => 'required|string|max:20|unique:subjects,code,' . $id,
            'name'        => 'required|string|max:255',
            'category'    => 'required|in:keagamaan,umum,peminatan',
            'grade_level' => 'required|in:all,10,11,12,11-12',
            'is_elective' => 'required|boolean',
        ]);

        $subject->update($request->only('code', 'name', 'category', 'grade_level', 'is_elective'));

        return back()->with('success', 'Mata pelajaran berhasil diperbarui!');
    }

    public function deleteSubject($id)
    {
        Subject::findOrFail($id)->delete();
        return back()->with('success', 'Mata pelajaran berhasil dihapus!');
    }

    // ─── COURSE PLOTTING (Assign Subject + Teacher to Class) ────────────────────

    public function coursePlotting(Request $request)
    {
        $query = Course::with(['subject', 'schoolClass', 'teacher']);

        if ($request->class_id) {
            $query->where('class_id', $request->class_id);
        }
        if ($request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }

        $courses  = $query->orderBy('class_id')->orderBy('day')->get();
        $classes  = SchoolClass::withCount('students')->get();
        $subjects = Subject::orderBy('name')->get();
        $teachers = User::where('role', 'guru')->orderBy('name')->get();

        return view('admin.subjects.plotting', compact('courses', 'classes', 'subjects', 'teachers'));
    }

    public function storeCourse(Request $request)
    {
        $request->validate([
            'class_id'   => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
            'day'        => 'nullable|string',
            'time_start' => 'nullable|string',
            'time_end'   => 'nullable|string',
            'room'       => 'nullable|string',
        ]);

        Course::create($request->only('class_id', 'subject_id', 'teacher_id', 'day', 'time_start', 'time_end', 'room'));

        return back()->with('success', 'Plotting mata pelajaran berhasil ditambahkan!');
    }

    public function updateCourse(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        $request->validate([
            'class_id'   => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
            'day'        => 'nullable|string',
            'time_start' => 'nullable|string',
            'time_end'   => 'nullable|string',
            'room'       => 'nullable|string',
        ]);

        $course->update($request->only('class_id', 'subject_id', 'teacher_id', 'day', 'time_start', 'time_end', 'room'));

        return back()->with('success', 'Plotting berhasil diperbarui!');
    }

    public function deleteCourse($id)
    {
        Course::findOrFail($id)->delete();
        return back()->with('success', 'Plotting mata pelajaran berhasil dihapus!');
    }

    // ─── ELECTIVE SUBJECT MANAGEMENT ────────────────────────────────────────────

    public function electiveManagement(Request $request)
    {
        $electiveSubjects = Subject::elective()->withCount('electiveStudents')->orderBy('name')->get();
        $classes = SchoolClass::withCount('students')
            ->get()
            ->filter(function ($class) {
                // Only show classes 11 and 12
                return preg_match('/^(XI|XII)/i', $class->name);
            });

        $selectedClassId = $request->class_id;
        $students = collect();
        if ($selectedClassId) {
            $students = User::where('role', 'siswa')
                ->where('class_id', $selectedClassId)
                ->with('electiveSubjects')
                ->orderBy('name')
                ->get();
        }

        return view('admin.subjects.electives', compact('electiveSubjects', 'classes', 'students', 'selectedClassId'));
    }

    public function toggleElectiveForStudent(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'action'     => 'required|in:enroll,drop',
        ]);

        $student = User::findOrFail($request->student_id);
        $subject = Subject::findOrFail($request->subject_id);

        if ($request->action === 'enroll') {
            StudentElectiveSubject::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'subject_id' => $subject->id,
                    'academic_year' => '2025/2026',
                ],
                ['status' => 'active']
            );
            return back()->with('success', "{$student->name} berhasil didaftarkan ke mata pelajaran {$subject->name}!");
        } else {
            StudentElectiveSubject::where('student_id', $student->id)
                ->where('subject_id', $subject->id)
                ->delete();
            return back()->with('success', "{$student->name} berhasil dihapus dari mata pelajaran {$subject->name}!");
        }
    }

    // ─── MONITOR ALL COURSES (Admin melihat semua mapel) ────────────────────────

    public function monitorCourses(Request $request)
    {
        $query = Course::with(['subject', 'schoolClass', 'teacher', 'materials', 'assignments', 'quizzes']);

        if ($request->class_id) {
            $query->where('class_id', $request->class_id);
        }
        if ($request->search) {
            $query->whereHas('subject', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%");
            });
        }

        $courses = $query->orderBy('class_id')->get();
        $classes = SchoolClass::withCount('students')->get();

        return view('admin.subjects.monitor', compact('courses', 'classes'));
    }

    // ─── PPDB REGISTRATIONS ─────────────────────────────────────────────────────

    public function registrations(Request $request)
    {
        $query = Registration::query();

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('nisn', 'like', "%{$request->search}%")
                  ->orWhere('school', 'like', "%{$request->search}%");
            });
        }

        $registrations = $query->latest()->paginate(20);
        $stats = [
            'total'    => Registration::count(),
            'pending'  => Registration::where('status', 'pending')->count(),
            'approved' => Registration::where('status', 'approved')->count(),
            'rejected' => Registration::where('status', 'rejected')->count(),
        ];
        $classes = SchoolClass::all();

        return view('admin.registrations.index', compact('registrations', 'stats', 'classes'));
    }

    public function approveRegistration(Request $request, $id)
    {
        $reg = Registration::findOrFail($id);

        if ($reg->status !== 'pending') {
            return back()->with('error', 'Pendaftaran ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'class_id' => 'required|exists:classes,id',
        ], [
            'class_id.required' => 'Silakan pilih kelas untuk calon siswa ini.',
        ]);

        // Create user account from registration data
        User::create([
            'name'            => $reg->name,
            'email'           => $reg->email,
            'password'        => $reg->password, // already hashed
            'role'            => 'siswa',
            'identity_number' => $reg->nisn,
            'phone'           => $reg->phone,
            'gender'          => 'L', // default, admin can edit later
            'class_id'        => $request->class_id,
        ]);

        $reg->update([
            'status'      => 'approved',
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('success', "Pendaftaran {$reg->name} telah DITERIMA dan akun siswa berhasil dibuat!");
    }

    public function rejectRegistration(Request $request, $id)
    {
        $reg = Registration::findOrFail($id);

        if ($reg->status !== 'pending') {
            return back()->with('error', 'Pendaftaran ini sudah diproses sebelumnya.');
        }

        $reg->update([
            'status'      => 'rejected',
            'admin_notes' => $request->admin_notes ?? 'Ditolak oleh panitia.',
        ]);

        return back()->with('success', "Pendaftaran {$reg->name} telah DITOLAK.");
    }

    public function deleteRegistration($id)
    {
        Registration::findOrFail($id)->delete();
        return back()->with('success', 'Data pendaftaran berhasil dihapus.');
    }

    // ─── CONTACT MESSAGES ──────────────────────────────────────────────────────

    public function messages(Request $request)
    {
        $messages = ContactMessage::latest()->paginate(20);
        
        // Mark all as read when viewed (optional, but a simple way is just list them)
        // Let's just list them
        
        return view('admin.messages.index', compact('messages'));
    }

    public function deleteMessage($id)
    {
        ContactMessage::findOrFail($id)->delete();
        return back()->with('success', 'Pesan berhasil dihapus.');
    }
}
