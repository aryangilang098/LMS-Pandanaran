@extends('layouts.app')

@section('title', 'Hasil Kuis: ' . $attempt->quiz->title)
@section('page_title', 'Hasil Kuis')

@section('content')
@php
    $pct = $attempt->total_questions > 0 ? round(($attempt->score / ($attempt->total_questions * 20)) * 100) : 0;
    $passed = $attempt->score >= $attempt->quiz->passing_score;
    $answers = $attempt->answers_json ?? [];
@endphp

<div style="max-width: 800px; margin: 0 auto;">
    {{-- Score Card --}}
    <div style="background: linear-gradient(135deg, {{ $passed ? '#064e3b, #047857' : '#7f1d1d, #b91c1c' }}); border-radius: 24px; padding: 2.5rem; text-align: center; margin-bottom: 2rem; color: white;">
        <div style="font-size: 4rem; margin-bottom: 0.75rem;">{{ $passed ? '🎉' : '📚' }}</div>
        <h2 style="font-size: 1.5rem; font-weight: 800; margin-bottom: 0.5rem;">
            {{ $passed ? 'Selamat! Anda Lulus!' : 'Perlu Belajar Lebih Giat' }}
        </h2>
        <p style="color: rgba(255,255,255,0.8); margin-bottom: 2rem;">{{ $attempt->quiz->title }}</p>
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; max-width: 500px; margin: 0 auto;">
            <div style="background: rgba(255,255,255,0.12); border-radius: 14px; padding: 1.25rem;">
                <div style="font-size: 2.2rem; font-weight: 800; color: #fbbf24;">{{ $attempt->score }}</div>
                <div style="font-size: 0.75rem; opacity: 0.8; margin-top: 2px;">Skor Total</div>
            </div>
            <div style="background: rgba(255,255,255,0.12); border-radius: 14px; padding: 1.25rem;">
                <div style="font-size: 2.2rem; font-weight: 800; color: #6ee7b7;">{{ $attempt->total_correct }}</div>
                <div style="font-size: 0.75rem; opacity: 0.8; margin-top: 2px;">Jawaban Benar</div>
            </div>
            <div style="background: rgba(255,255,255,0.12); border-radius: 14px; padding: 1.25rem;">
                <div style="font-size: 2.2rem; font-weight: 800; color: #fca5a5;">{{ $attempt->total_questions - $attempt->total_correct }}</div>
                <div style="font-size: 0.75rem; opacity: 0.8; margin-top: 2px;">Jawaban Salah</div>
            </div>
        </div>
        <div style="margin-top: 1.5rem; font-size: 0.82rem; color: rgba(255,255,255,0.65);">
            Dikerjakan: {{ $attempt->started_at->isoFormat('D MMMM YYYY, HH:mm') }} |
            Selesai: {{ $attempt->completed_at?->isoFormat('HH:mm') }}
        </div>
    </div>

    {{-- Progress Bar --}}
    <div style="background: white; border-radius: 16px; padding: 1.5rem; margin-bottom: 1.5rem; border: 1px solid #e2e8f0;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.82rem; font-weight: 700;">
            <span>KKM: {{ $attempt->quiz->passing_score }}</span>
            <span style="color: {{ $passed ? '#047857' : '#dc2626' }};">Skor: {{ $attempt->score }} — {{ $passed ? 'LULUS ✅' : 'TIDAK LULUS ❌' }}</span>
        </div>
        <div style="height: 12px; background: #f1f5f9; border-radius: 50px; overflow: hidden;">
            <div style="height: 100%; width: {{ min($attempt->score, 100) }}%; background: {{ $passed ? 'linear-gradient(90deg, #047857, #10b981)' : 'linear-gradient(90deg, #dc2626, #f87171)' }}; border-radius: 50px; transition: width 1s ease;"></div>
        </div>
    </div>

    {{-- Answer Review --}}
    <h3 style="font-size: 1rem; font-weight: 800; margin-bottom: 1rem; color: #1e293b;">📋 Pembahasan Soal</h3>
    @foreach($attempt->quiz->questions as $idx => $q)
    @php
        $given = $answers[$q->id] ?? null;
        $isCorrect = $given && strtoupper($given) === strtoupper($q->correct_option);
    @endphp
    <div style="background: white; border-radius: 14px; padding: 1.5rem; margin-bottom: 1rem; border: 2px solid {{ $isCorrect ? '#a7f3d0' : '#fecaca' }}; border-left-width: 5px;">
        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
            <div style="width: 30px; height: 30px; background: {{ $isCorrect ? '#047857' : '#dc2626' }}; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 0.85rem; flex-shrink: 0;">{{ $idx + 1 }}</div>
            <span style="font-size: 0.75rem; font-weight: 700; {{ $isCorrect ? 'color: #047857; background: #d1fae5;' : 'color: #dc2626; background: #fee2e2;' }} padding: 2px 10px; border-radius: 50px;">{{ $isCorrect ? '✅ Benar' : '❌ Salah' }}</span>
        </div>
        <p style="font-size: 0.9rem; font-weight: 600; color: #1e293b; margin-bottom: 0.875rem; line-height: 1.6;">{{ $q->question }}</p>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.4rem; margin-bottom: 0.75rem;">
            @foreach(['A' => $q->option_a, 'B' => $q->option_b, 'C' => $q->option_c, 'D' => $q->option_d] as $opt => $text)
            <div style="padding: 0.6rem 0.875rem; border-radius: 8px; font-size: 0.82rem; display: flex; align-items: center; gap: 0.5rem;
                background: {{ $opt === strtoupper($q->correct_option) ? '#d1fae5' : ($given === $opt ? '#fee2e2' : '#f8fafc') }};
                border: 1px solid {{ $opt === strtoupper($q->correct_option) ? '#a7f3d0' : ($given === $opt ? '#fecaca' : '#e2e8f0') }};
                color: {{ $opt === strtoupper($q->correct_option) ? '#065f46' : ($given === $opt ? '#991b1b' : '#64748b') }};">
                <span style="font-weight: 700;">{{ $opt }}.</span> {{ $text }}
                @if($opt === strtoupper($q->correct_option)) <i class="bi bi-check-circle-fill" style="margin-left: auto; color: #047857;"></i> @endif
                @if($given === $opt && !$isCorrect && $opt !== strtoupper($q->correct_option)) <i class="bi bi-x-circle-fill" style="margin-left: auto;"></i> @endif
            </div>
            @endforeach
        </div>
        @if($q->explanation)
        <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 0.75rem 1rem; font-size: 0.8rem; color: #92400e;">
            <strong>💡 Penjelasan:</strong> {{ $q->explanation }}
        </div>
        @endif
    </div>
    @endforeach

    <div style="text-align: center; padding: 1.5rem;">
        <a href="{{ route('lms.courses') }}" class="btn btn-primary"><i class="bi bi-arrow-left"></i> Kembali ke Kelas</a>
    </div>
</div>
@endsection
