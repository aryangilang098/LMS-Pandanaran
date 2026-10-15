@extends('layouts.app')

@section('title', 'Pembayaran - ' . $bill->title)
@section('page_title', 'Pembayaran Tagihan')
@section('breadcrumb', 'Midtrans Gateway (VA & QRIS)')

@section('content')
<div style="max-width: 720px; margin: 0 auto;">

    {{-- Bill Detail Card --}}
    <div style="background: linear-gradient(135deg, #064e3b, #047857); border-radius: 20px; padding: 2rem; margin-bottom: 1.75rem; color: white; position: relative; overflow: hidden; box-shadow: 0 10px 25px rgba(4, 120, 87, 0.25);">
        <div style="position: absolute; right: -40px; top: -40px; width: 180px; height: 180px; border-radius: 50%; background: rgba(255,255,255,0.06);"></div>
        <div style="position: relative; z-index: 1;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                <div>
                    <span style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); padding: 0.25rem 0.75rem; border-radius: 99px; font-size: 0.75rem; font-weight: 600;">
                        🏷️ {{ $bill->feeType->name }}
                    </span>
                    <h3 style="font-size: 1.35rem; font-weight: 800; margin-top: 0.6rem; color: white;">{{ $bill->title }}</h3>
                </div>
                <div style="text-align: right;">
                    <span class="badge badge-warning" style="font-size: 0.8rem; padding: 0.4rem 0.8rem; text-transform: uppercase;">
                        Status: {{ $bill->status }}
                    </span>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 1rem; border-top: 1px solid rgba(255,255,255,0.15); padding-top: 1rem; margin-top: 1rem;">
                <div>
                    <div style="font-size: 0.75rem; opacity: 0.8;">KODE TAGIHAN</div>
                    <div style="font-size: 0.95rem; font-weight: 700;">{{ $bill->bill_code }}</div>
                </div>
                <div>
                    <div style="font-size: 0.75rem; opacity: 0.8;">JATUH TEMPO</div>
                    <div style="font-size: 0.95rem; font-weight: 700;">{{ $bill->due_date->isoFormat('D MMMM YYYY') }}</div>
                </div>
                <div>
                    <div style="font-size: 0.75rem; opacity: 0.8;">SISWA</div>
                    <div style="font-size: 0.95rem; font-weight: 700;">{{ $bill->student->name }}</div>
                </div>
            </div>

            <div style="margin-top: 1.5rem; background: rgba(0,0,0,0.18); border-radius: 12px; padding: 1.25rem; display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <div style="font-size: 0.75rem; color: rgba(255,255,255,0.8); text-transform: uppercase; font-weight: 600;">Total Pembayaran</div>
                    <div style="font-size: 2.2rem; font-weight: 800; color: #fbbf24; line-height: 1.1; margin-top: 0.25rem;">
                        Rp {{ number_format($bill->amount, 0, ',', '.') }}
                    </div>
                </div>
                <div style="font-size: 2.5rem;">💳</div>
            </div>
        </div>
    </div>

    {{-- Midtrans Snap Payment Action Card --}}
    <div class="card" style="border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.05);">
        <div class="card-header" style="border-bottom: 1px solid #f1f5f9; padding: 1.25rem 1.5rem;">
            <h3 style="font-size: 1.1rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 0.5rem;">
                <span>🔒</span> Pembayaran Midtrans Gateway (VA + QRIS)
            </h3>
        </div>
        <div class="card-body" style="padding: 1.5rem;">
            
            <p style="font-size: 0.9rem; color: #475569; line-height: 1.6; margin-bottom: 1.25rem;">
                Silakan pilih opsi metode pembayaran di bawah ini, lalu klik <strong>"Bayar Sekarang"</strong> untuk memproses transaksi.
            </p>

            {{-- Interactive Payment Method Selectors --}}
            <div style="margin-bottom: 1.5rem;">
                <div style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.75rem;">
                    Pilih Metode Pembayaran:
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.75rem;" id="method-options">
                    
                    <div class="method-card active" data-method="qris" onclick="selectPaymentMethod('qris', '📱 QRIS (GoPay, OVO, ShopeePay, mBanking)')" style="background: #ecfdf5; border: 2px solid #059669; border-radius: 12px; padding: 0.85rem 1rem; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 0.6rem;">
                        <input type="radio" name="payment_channel" value="qris" checked style="accent-color: #059669; width: 18px; height: 18px;">
                        <div>
                            <div style="font-size: 0.85rem; font-weight: 700; color: #065f46;">📱 QRIS (Semua E-Wallet)</div>
                            <div style="font-size: 0.72rem; color: #047857;">GoPay, OVO, ShopeePay, Dana, mBanking</div>
                        </div>
                    </div>

                    <div class="method-card" data-method="bca_va" onclick="selectPaymentMethod('bca_va', '🏦 BCA Virtual Account')" style="background: #ffffff; border: 2px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 0.6rem;">
                        <input type="radio" name="payment_channel" value="bca_va" style="accent-color: #0056a4; width: 18px; height: 18px;">
                        <div>
                            <div style="font-size: 0.85rem; font-weight: 700; color: #1e293b;">🏦 BCA Virtual Account</div>
                            <div style="font-size: 0.72rem; color: #64748b;">Transfer VA BCA Otomatis</div>
                        </div>
                    </div>

                    <div class="method-card" data-method="bri_va" onclick="selectPaymentMethod('bri_va', '🏦 BRI Virtual Account')" style="background: #ffffff; border: 2px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 0.6rem;">
                        <input type="radio" name="payment_channel" value="bri_va" style="accent-color: #1da0dc; width: 18px; height: 18px;">
                        <div>
                            <div style="font-size: 0.85rem; font-weight: 700; color: #1e293b;">🏦 BRI Virtual Account</div>
                            <div style="font-size: 0.72rem; color: #64748b;">Transfer BRIVA Otomatis</div>
                        </div>
                    </div>

                    <div class="method-card" data-method="mandiri_va" onclick="selectPaymentMethod('mandiri_va', '🏦 Mandiri Virtual Account')" style="background: #ffffff; border: 2px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 0.6rem;">
                        <input type="radio" name="payment_channel" value="mandiri_va" style="accent-color: #003088; width: 18px; height: 18px;">
                        <div>
                            <div style="font-size: 0.85rem; font-weight: 700; color: #1e293b;">🏦 Mandiri Virtual Account</div>
                            <div style="font-size: 0.72rem; color: #64748b;">Livin / Bill Payment Mandiri</div>
                        </div>
                    </div>

                    <div class="method-card" data-method="bni_va" onclick="selectPaymentMethod('bni_va', '🏦 BNI & Permata VA')" style="background: #ffffff; border: 2px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 0.6rem;">
                        <input type="radio" name="payment_channel" value="bni_va" style="accent-color: #047857; width: 18px; height: 18px;">
                        <div>
                            <div style="font-size: 0.85rem; font-weight: 700; color: #1e293b;">🏦 BNI & Permata VA</div>
                            <div style="font-size: 0.72rem; color: #64748b;">Virtual Account BNI / Permata</div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Form for Midtrans Submission Callback --}}
            <form id="paymentCallbackForm" action="{{ route('payment.student.process', $bill->id) }}" method="POST">
                @csrf
                <input type="hidden" name="result_data" id="resultDataInput">
            </form>

            {{-- Action Buttons --}}
            <button id="pay-button" class="btn btn-primary" style="width: 100%; padding: 1rem; font-size: 1.05rem; font-weight: 700; border-radius: 12px; background: linear-gradient(135deg, #059669, #047857); border: none; box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3); cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                <i class="bi bi-shield-lock-fill"></i> Bayar Sekarang (<span id="selected-method-label">📱 QRIS</span>)
            </button>

            <div style="text-align: center; margin-top: 1rem;">
                <a href="{{ route('payment.student.bills') }}" style="color: #64748b; font-size: 0.85rem; text-decoration: none; font-weight: 600;">
                    ← Kembali ke Daftar Tagihan
                </a>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
{{-- Midtrans Snap Script --}}
<script src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ $clientKey }}"></script>
<script type="text/javascript">
    let selectedChannel = 'qris';
    let selectedChannelLabel = '📱 QRIS';

    function selectPaymentMethod(channel, label) {
        selectedChannel = channel;
        selectedChannelLabel = label;
        
        document.querySelectorAll('.method-card').forEach(card => {
            card.style.background = '#ffffff';
            card.style.borderColor = '#e2e8f0';
            card.querySelector('input[type="radio"]').checked = false;
        });

        const activeCard = document.querySelector(`.method-card[data-method="${channel}"]`);
        if (activeCard) {
            activeCard.style.background = '#ecfdf5';
            activeCard.style.borderColor = '#059669';
            activeCard.querySelector('input[type="radio"]').checked = true;
        }

        document.getElementById('selected-method-label').innerText = label;
    }

    document.getElementById('pay-button').onclick = function(e){
        e.preventDefault();
        
        const snapToken = "{{ $snapToken }}";

        if (!snapToken || snapToken.startsWith('DEMO-SNAP-TOKEN')) {
            alert('Pengajuan pembayaran [' + selectedChannelLabel + '] berhasil diajukan!\n\nStatus transaksi kini PENDING dan menunggu verifikasi dari Bendahara.');
            const mockResult = {
                payment_type: selectedChannel,
                transaction_id: 'TRX-MIDTRANS-' + Date.now(),
                transaction_status: 'pending'
            };
            document.getElementById('resultDataInput').value = JSON.stringify(mockResult);
            document.getElementById('paymentCallbackForm').submit();
            return;
        }

        snap.pay(snapToken, {
            onSuccess: function(result){
                console.log("Success result:", result);
                document.getElementById('resultDataInput').value = JSON.stringify(result);
                document.getElementById('paymentCallbackForm').submit();
            },
            onPending: function(result){
                console.log("Pending result:", result);
                document.getElementById('resultDataInput').value = JSON.stringify(result);
                document.getElementById('paymentCallbackForm').submit();
            },
            onError: function(result){
                console.log("Error result:", result);
                alert("Pembayaran gagal atau dibatalkan oleh sistem.");
            },
            onClose: function(){
                alert('Anda menutup jendela pembayaran tanpa menyelesaikan transaksi.');
            }
        });
    };
</script>
@endsection
