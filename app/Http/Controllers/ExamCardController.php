<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExamCard;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ExamCardController extends Controller
{
    /**
     * Tampilan Kartu Ujian untuk Siswa (UTS & UAS)
     */
    public function studentCard(Request $request)
    {
        $user = Auth::user();
        $user->load('class');

        $examType     = in_array($request->get('type'), ['uts', 'uas']) ? $request->get('type') : 'uts';
        $academicYear = $request->get('academic_year', '2026/2027');
        $semester     = $request->get('semester', 'ganjil');

        // Hitung nomor urut absen berdasarkan abjad nama di kelas siswa
        $absentNumber = 1;
        if ($user->class_id) {
            $classStudentIds = User::where('role', 'siswa')
                ->where('class_id', $user->class_id)
                ->orderBy('name', 'asc')
                ->pluck('id')
                ->toArray();
            
            $pos = array_search($user->id, $classStudentIds);
            if ($pos !== false) {
                $absentNumber = $pos + 1;
            }
        }

        $defaultExamNumber = strtoupper($examType) . '-' . date('Y') . '-' . str_pad($absentNumber, 2, '0', STR_PAD_LEFT);

        // Cari atau buat kartu ujian otomatis jika siswa aktif
        $card = ExamCard::firstOrCreate(
            [
                'user_id'       => $user->id,
                'exam_type'     => $examType,
                'academic_year' => $academicYear,
                'semester'      => $semester,
            ],
            [
                'exam_number' => $defaultExamNumber,
                'room_number' => 'Ruang 04 - R102',
                'status'      => 'pending',
                'notes'       => 'Menunggu verifikasi panitia/admin.',
            ]
        );

        // Ambil daftar mata pelajaran untuk jadwal ujian
        $courses = Course::where('class_id', $user->class_id)
            ->with('teacher')
            ->get();

        return view('exam_cards.student', compact('user', 'card', 'examType', 'academicYear', 'semester', 'courses'));
    }

    /**
     * Dashboard Manajemen Kartu Ujian untuk Admin
     */
    public function adminIndex(Request $request)
    {
        $classes = SchoolClass::withCount('students')->get();

        $selectedClassId = $request->get('class_id', $classes->first()?->id);
        $examType        = in_array($request->get('type'), ['uts', 'uas']) ? $request->get('type') : 'uts';
        $search          = $request->get('search');

        // Otomatis pastikan semua siswa di kelas yang dipilih memiliki Kartu Ujian (status pending/menunggu verifikasi)
        if ($selectedClassId) {
            $classStudents = User::where('role', 'siswa')
                ->where('class_id', $selectedClassId)
                ->orderBy('name', 'asc')
                ->get();
            
            $absentNo = 1;
            foreach ($classStudents as $st) {
                ExamCard::firstOrCreate(
                    [
                        'user_id'       => $st->id,
                        'exam_type'     => $examType,
                        'academic_year' => '2026/2027',
                        'semester'      => 'ganjil',
                    ],
                    [
                        'exam_number' => strtoupper($examType) . '-' . date('Y') . '-' . str_pad($absentNo, 2, '0', STR_PAD_LEFT),
                        'room_number' => 'Ruang 04 - R102',
                        'status'      => 'pending',
                        'notes'       => 'Menunggu verifikasi admin.',
                    ]
                );
                $absentNo++;
            }
        }

        $studentsQuery = User::where('role', 'siswa')->orderBy('name', 'asc');

        if ($selectedClassId) {
            $studentsQuery->where('class_id', $selectedClassId);
        }

        if ($search) {
            $studentsQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('identity_number', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $students = $studentsQuery->with(['class', 'examCards' => function ($q) use ($examType) {
            $q->where('exam_type', $examType);
        }])->paginate(15);

        return view('admin.exam_cards.index', compact('classes', 'students', 'selectedClassId', 'examType', 'search'));
    }

    /**
     * Admin: Simpan atau Update data Kartu Ujian Siswa
     */
    public function adminStoreOrUpdate(Request $request)
    {
        $request->validate([
            'user_id'       => 'required|exists:users,id',
            'exam_type'     => 'required|in:uts,uas',
            'academic_year' => 'required|string',
            'semester'      => 'required|in:ganjil,genap',
            'exam_number'   => 'required|string|max:50',
            'room_number'   => 'required|string|max:50',
            'status'        => 'required|in:approved,pending,suspended',
            'file'          => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
        ], [
            'exam_number.required' => 'Nomor Peserta Ujian wajib diisi.',
            'room_number.required' => 'Ruang Ujian wajib diisi.',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('exam_cards', 'public');
        }

        $data = [
            'exam_number' => $request->exam_number,
            'room_number' => $request->room_number,
            'status'      => $request->status,
            'notes'       => $request->notes,
        ];

        if ($filePath) {
            $data['file_path'] = $filePath;
        }

        ExamCard::updateOrCreate(
            [
                'user_id'       => $request->user_id,
                'exam_type'     => $request->exam_type,
                'academic_year' => $request->academic_year,
                'semester'      => $request->semester,
            ],
            $data
        );

        return back()->with('success', 'Data Kartu Ujian berhasil diperbarui!');
    }

    /**
     * Admin: Generasi Massal Kartu Ujian per Kelas (Urut Abjad)
     */
    public function adminGenerate(Request $request)
    {
        $request->validate([
            'class_id'      => 'required|exists:classes,id',
            'exam_type'     => 'required|in:uts,uas',
            'academic_year' => 'required|string',
            'semester'      => 'required|in:ganjil,genap',
            'room_prefix'   => 'required|string',
        ]);

        // Urutkan siswa berdasarkan abjad nama (A-Z)
        $students = User::where('role', 'siswa')
            ->where('class_id', $request->class_id)
            ->orderBy('name', 'asc')
            ->get();

        if ($students->isEmpty()) {
            return back()->with('error', 'Tidak ada siswa ditemukan di kelas yang dipilih.');
        }

        $absentNumber = 1;
        foreach ($students as $student) {
            // Format 2 angka dibelakang berdasarkan nomor urut absen abjad (01, 02, 03, dst)
            $examNumber = strtoupper($request->exam_type) . '-' . date('Y') . '-' . str_pad($absentNumber, 2, '0', STR_PAD_LEFT);
            $roomNumber = $request->room_prefix;

            ExamCard::updateOrCreate(
                [
                    'user_id'       => $student->id,
                    'exam_type'     => $request->exam_type,
                    'academic_year' => $request->academic_year,
                    'semester'      => $request->semester,
                ],
                [
                    'exam_number' => $examNumber,
                    'room_number' => $roomNumber,
                    'status'      => 'approved',
                    'notes'       => 'Generated secara otomatis berdasarkan urutan absen abjad.',
                ]
            );
            $absentNumber++;
        }

        return back()->with('success', "Berhasil men-generate {$students->count()} Kartu Ujian dengan nomor urut absen abjad (01-{$students->count()})!");
    }

    /**
     * Admin: Hapus Kartu Ujian
     */
    public function adminDelete($id)
    {
        ExamCard::findOrFail($id)->delete();
        return back()->with('success', 'Kartu Ujian berhasil dihapus.');
    }
}
