@extends('layouts.app')

@section('title', 'Pesan Masuk')
@section('page_title', 'Pesan Masuk (Kotak Kontak)')

@section('content')

<div class="card">
    <div class="card-header">
        <h3>📩 Daftar Pesan Masuk ({{ $messages->total() }})</h3>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Pengirim</th>
                        <th>Kontak</th>
                        <th>Subjek / Pesan</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $i => $msg)
                    <tr>
                        <td style="font-size: 0.8rem; color: #94a3b8; vertical-align: top;">{{ $messages->firstItem() + $i }}</td>
                        <td style="vertical-align: top;">
                            <div style="display: flex; align-items: center; gap: 0.6rem;">
                                <div style="width: 34px; height: 34px; border-radius: 8px; background: linear-gradient(135deg, #3b82f6, #2563eb); display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 0.85rem; flex-shrink: 0;">
                                    {{ strtoupper(substr($msg->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight: 700; font-size: 0.875rem;">{{ $msg->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="vertical-align: top;">
                            <div style="font-size: 0.8rem; color: #64748b;"><i class="bi bi-envelope"></i> {{ $msg->email }}</div>
                            @if($msg->phone)
                                <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 2px;"><i class="bi bi-whatsapp"></i> {{ $msg->phone }}</div>
                            @endif
                        </td>
                        <td style="vertical-align: top;">
                            <div style="font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.25rem;">{{ $msg->subject }}</div>
                            <div style="font-size: 0.8rem; color: #64748b; line-height: 1.5; background: #f8fafc; padding: 0.75rem; border-radius: 8px; border: 1px solid #e2e8f0;">
                                {{ $msg->message }}
                            </div>
                        </td>
                        <td style="font-size: 0.78rem; color: #64748b; vertical-align: top;">
                            {{ $msg->created_at->isoFormat('D MMM YYYY, HH:mm') }}
                        </td>
                        <td style="vertical-align: top;">
                            <form action="{{ route('admin.messages.delete', $msg->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline btn-sm" onclick="return confirm('Hapus pesan dari {{ addslashes($msg->name) }}?')" title="Hapus Pesan" style="color: #dc2626;">
                                    <i class="bi bi-trash3-fill"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 3rem; color: #94a3b8;">
                            <i class="bi bi-envelope-paper" style="font-size: 2rem; display: block; margin-bottom: 0.5rem;"></i>
                            Belum ada pesan masuk.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($messages->hasPages())
        <div style="padding: 1rem 1.5rem; display: flex; justify-content: center;">
            {{ $messages->appends(request()->all())->links() }}
        </div>
        @endif
    </div>
</div>

@endsection
