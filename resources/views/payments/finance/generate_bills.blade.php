@extends('layouts.app')

@section('title', 'Generate Tagihan Siswa')
@section('page_title', 'Generate Tagihan Siswa')

@section('content')
<div style="max-width: 680px; margin: 0 auto;">
    <div class="card">
        <div class="card-header">
            <h3>📋 Generate Tagihan untuk Kelas</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('payment.finance.storeBills') }}" method="POST">
                @csrf
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Kelas / Rombel</label>
                        <select name="class_id" class="form-input" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($classes as $c)
                            <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->students->count() }} siswa)</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jenis Biaya</label>
                        <select name="fee_type_id" class="form-input" required onchange="updateAmount(this)">
                            <option value="">-- Pilih Jenis Biaya --</option>
                            @foreach($feeTypes as $ft)
                            <option value="{{ $ft->id }}" data-amount="{{ $ft->default_amount }}" data-monthly="{{ $ft->is_monthly ? '1' : '0' }}">
                                {{ $ft->name }} (Rp {{ number_format($ft->default_amount, 0, ',', '.') }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-grid-2">
                    <div class="form-group" id="month-field">
                        <label class="form-label">Bulan (untuk SPP bulanan)</label>
                        <select name="month" class="form-input">
                            <option value="">-- Pilih Bulan --</option>
                            @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $bln)
                            <option value="{{ $i + 1 }}" {{ (now()->month === $i + 1) ? 'selected' : '' }}>{{ $bln }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tahun</label>
                        <select name="year" class="form-input" required>
                            @for($y = now()->year; $y <= now()->year + 2; $y++)
                            <option value="{{ $y }}" {{ $y === now()->year ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Nominal (Rp)</label>
                        <input type="number" name="amount" class="form-input" required min="1000" id="amount-input" placeholder="250000">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jatuh Tempo</label>
                        <input type="date" name="due_date" class="form-input" required value="{{ now()->addDays(14)->format('Y-m-d') }}">
                    </div>
                </div>
                <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 10px; padding: 1rem; margin-bottom: 1.25rem; font-size: 0.82rem; color: #92400e;">
                    ⚠️ <strong>Perhatian:</strong> Sistem hanya akan membuat tagihan bagi siswa yang belum memiliki tagihan dengan jenis dan periode yang sama. Tagihan yang sudah ada tidak akan di-duplikat.
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.875rem;" onclick="return confirm('Yakin generate tagihan untuk semua siswa di kelas yang dipilih?')">
                    <i class="bi bi-file-earmark-plus-fill"></i> Generate Tagihan Sekarang
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function updateAmount(sel) {
    const opt = sel.options[sel.selectedIndex];
    if (opt.dataset.amount) {
        document.getElementById('amount-input').value = opt.dataset.amount;
    }
    document.getElementById('month-field').style.opacity = opt.dataset.monthly === '1' ? '1' : '0.4';
}
</script>
@endsection
