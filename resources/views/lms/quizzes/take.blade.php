@extends('layouts.app')

@section('title', 'Kerjakan Kuis: ' . $quiz->title)
@section('page_title', 'Kuis Online')

@section('content')
@php
    $endTime = $existing->started_at->addMinutes($quiz->duration_minutes);
    $secondsLeft = max(0, now()->diffInSeconds($endTime, false));
@endphp

<div style="max-width: 860px; margin: 0 auto;">
    {{-- Timer Bar --}}
    <div class="quiz-timer-bar" id="timerBar">
        <div>
            <div style="font-size: 0.82rem; color: rgba(255,255,255,0.7); margin-bottom: 0.2rem;">⏱ Waktu Tersisa</div>
            <div class="timer-display" id="timerDisplay">--:--</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 1.1rem; font-weight: 800; color: white;">{{ $quiz->title }}</div>
            <div style="font-size: 0.78rem; color: rgba(255,255,255,0.65); margin-top: 2px;">{{ $quiz->questions->count() }} soal · KKM {{ $quiz->passing_score }}</div>
        </div>
        <div style="text-align: right;">
            <div style="font-size: 0.82rem; color: rgba(255,255,255,0.7); margin-bottom: 0.2rem;">Progres</div>
            <div style="font-size: 1.4rem; font-weight: 800; color: #fbbf24;" id="progressCount">0 / {{ $quiz->questions->count() }}</div>
        </div>
    </div>

    {{-- Quiz Form --}}
    <form action="{{ route('lms.quiz.submit', $quiz->id) }}" method="POST" id="quizForm">
        @csrf
        @foreach($quiz->questions as $idx => $q)
        <div style="background: white; border-radius: 16px; padding: 1.75rem; margin-bottom: 1.25rem; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.04);" id="qblock-{{ $q->id }}">
            <div style="display: flex; align-items: flex-start; gap: 1rem;">
                <div style="width: 36px; height: 36px; background: linear-gradient(135deg, #047857, #059669); border-radius: 9px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 0.9rem;">{{ $idx + 1 }}</div>
                <div style="flex: 1;">
                    <p style="font-size: 1rem; font-weight: 600; color: #1e293b; line-height: 1.6; margin-bottom: 1.25rem;">{{ $q->question }}</p>
                    <div style="display: flex; flex-direction: column; gap: 0.6rem;">
                        @foreach(['A' => $q->option_a, 'B' => $q->option_b, 'C' => $q->option_c, 'D' => $q->option_d] as $opt => $text)
                        <label style="display: flex; align-items: center; gap: 0.75rem; padding: 0.875rem 1.25rem; border: 2px solid #e2e8f0; border-radius: 12px; cursor: pointer; transition: all 0.2s; font-size: 0.9rem;" class="opt-label" data-qid="{{ $q->id }}">
                            <input type="radio" name="answers[{{ $q->id }}]" value="{{ $opt }}"
                                style="accent-color: #047857; width: 18px; height: 18px;"
                                onchange="updateOption({{ $q->id }}, this.parentElement)">
                            <span style="background: #f1f5f9; border-radius: 6px; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.8rem; flex-shrink: 0;">{{ $opt }}</span>
                            {{ $text }}
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <div style="background: white; border-radius: 16px; padding: 1.5rem; border: 1px solid #e2e8f0; text-align: center;">
            <p style="color: #64748b; font-size: 0.875rem; margin-bottom: 1.25rem;">Pastikan semua soal telah dijawab sebelum mengumpulkan.</p>
            <button type="submit" class="btn btn-primary" style="padding: 0.875rem 2.5rem; font-size: 1rem;" onclick="return confirm('Yakin ingin mengumpulkan jawaban?')">
                <i class="bi bi-send-fill"></i> Kumpulkan Jawaban
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
let secondsLeft = {{ $secondsLeft }};
let answeredCount = 0;
const totalQ = {{ $quiz->questions->count() }};

function pad(n) { return String(n).padStart(2, '0'); }

const timer = setInterval(function() {
    if (secondsLeft <= 0) {
        clearInterval(timer);
        document.getElementById('quizForm').submit();
        return;
    }
    secondsLeft--;
    const m = Math.floor(secondsLeft / 60);
    const s = secondsLeft % 60;
    const display = document.getElementById('timerDisplay');
    display.textContent = pad(m) + ':' + pad(s);
    if (secondsLeft < 120) {
        display.classList.add('warning');
        document.getElementById('timerBar').style.background = 'linear-gradient(135deg, #7f1d1d, #991b1b)';
    }
}, 1000);

function updateOption(qid, selectedLabel) {
    document.querySelectorAll(`[data-qid="${qid}"]`).forEach(l => {
        l.style.borderColor = '#e2e8f0';
        l.style.background = 'white';
    });
    selectedLabel.style.borderColor = '#047857';
    selectedLabel.style.background = '#ecfdf5';

    answeredCount = document.querySelectorAll('input[type="radio"]:checked').length;
    document.getElementById('progressCount').textContent = answeredCount + ' / ' + totalQ;
}
</script>
@endsection
