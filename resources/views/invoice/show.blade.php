@extends('layout')
@section('content')

<div class="container-fluid mt-4">
    <div class="row mb-3">
        <div class="col">
            <h3>Detail Invoice #{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</h3>
        </div>
        <div class="col-auto">
            @if($invoice->status === 'unpaid')
                <form action="{{ route('invoice.markAsPaid', $invoice->id) }}" method="POST" style="display:inline;">
                    @csrf @method('PUT')
                    <button type="submit" class="btn btn-success">Tandai Dibayar</button>
                </form>
            @endif
            <a href="{{ route('invoice.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Informasi Pasien</h5>
                </div>
                <div class="card-body">
                    <p><strong>Nama:</strong> {{ optional($invoice->pasien)->nama ?? '-' }}</p>
                    <p><strong>No RM:</strong> {{ optional($invoice->pasien)->no_rm ?? optional($invoice->pasien)->kodepasien ?? '-' }}</p>
                    <p><strong>Telepon:</strong> {{ optional($invoice->pasien)->telepon ?? '-' }}</p>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5>Detail Invoice</h5>
                </div>
                <div class="card-body">
                    <p><strong>Nomor Invoice:</strong> INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</p>
                    <p><strong>Layanan:</strong> {{ $invoice->layanan }}</p>
                    <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($invoice->created_at)->format('d/m/Y H:i') }}</p>
                    <p><strong>Jenis Pembayaran:</strong> {{ $invoice->jenis_pembayaran ?? '-' }}</p>
                    @if($invoice->no_bpjs)
                        <p><strong>No. BPJS / Asuransi:</strong> {{ $invoice->no_bpjs }}</p>
                    @endif
                    @if($invoice->keterangan_pembayaran)
                        <p><strong>Keterangan:</strong> {{ $invoice->keterangan_pembayaran }}</p>
                    @endif
                    <p><strong>Status:</strong>
                        @php
                            $statusLabel = $invoice->status;
                            if(str_starts_with($invoice->status, 'paid_by_')){
                                $statusLabel = 'Dibayar oleh ' . strtoupper(str_after($invoice->status, 'paid_by_'));
                            }
                        @endphp
                        <span class="badge {{ $invoice->status === 'paid' || str_starts_with($invoice->status, 'paid_by_') ? 'bg-success' : 'bg-warning' }}">
                            {{ $statusLabel }}
                        </span>
                    </p>
                          @if($invoice->paid_at)
                              {{ \Carbon\Carbon::parse($invoice->paid_at)->format('d/m/Y H:i') }}
                          @endif
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5>Rincian Biaya Layanan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr style="background: #f8f9fa;">
                                    <th>Item Layanan</th>
                                    <th class="text-center">Tipe</th>
                                    <th class="text-end">Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($invoice->items && count($invoice->items) > 0)
                                    @php
                                        $pemeriksaan = $invoice->items->filter(fn($i) => $i->type === 'pemeriksaan');
                                        $tindakan = $invoice->items->filter(fn($i) => $i->type === 'layanan' || $i->type === 'tindakan');
                                        $obat = $invoice->items->filter(fn($i) => $i->type === 'obat');
                                    @endphp
                                    
                                    @if($pemeriksaan->count() > 0)
                                        <tr style="background: #e7f3ff; font-weight: 600;">
                                            <td colspan="3"><i class="bi bi-stethoscope"></i> Pemeriksaan</td>
                                        </tr>
                                        @foreach($pemeriksaan as $item)
                                            <tr>
                                                <td>{{ $item->name }}</td>
                                                <td class="text-center"><span class="badge bg-primary">Pemeriksaan</span></td>
                                                <td class="text-end">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    @endif

                                    @if($tindakan->count() > 0)
                                        <tr style="background: #fff3cd; font-weight: 600;">
                                            <td colspan="3"><i class="bi bi-tools"></i> Tindakan Medis</td>
                                        </tr>
                                        @foreach($tindakan as $item)
                                            <tr>
                                                <td>{{ $item->name }}</td>
                                                <td class="text-center"><span class="badge bg-warning text-dark">Tindakan</span></td>
                                                <td class="text-end">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    @endif

                                    @if($obat->count() > 0)
                                        <tr style="background: #f0f9e8; font-weight: 600;">
                                            <td colspan="3"><i class="bi bi-capsule"></i> Obat & Resep</td>
                                        </tr>
                                        @foreach($obat as $item)
                                            <tr>
                                                <td>{{ $item->name }}</td>
                                                <td class="text-center"><span class="badge bg-success">Obat</span></td>
                                                <td class="text-end">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    @endif

                                    @php
                                        $lainnya = $invoice->items->filter(fn($i) => !in_array($i->type, ['pemeriksaan', 'layanan', 'tindakan', 'obat']));
                                    @endphp
                                    @if($lainnya->count() > 0)
                                        <tr style="background: #f5f5f5; font-weight: 600;">
                                            <td colspan="3"><i class="bi bi-info-circle"></i> Lainnya</td>
                                        </tr>
                                        @foreach($lainnya as $item)
                                            <tr>
                                                <td>{{ $item->name }}</td>
                                                <td class="text-center"><span class="badge bg-secondary">{{ ucfirst($item->type) }}</span></td>
                                                <td class="text-end">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @else
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">
                                            <i class="bi bi-inbox"></i> Tidak ada detail item
                                        </td>
                                    </tr>
                                @endif
                                <tr style="border-top: 2px solid #ddd;">
                                    <td colspan="2" class="text-end"><strong>Subtotal:</strong></td>
                                    <td class="text-end"><strong>Rp {{ number_format($invoice->subtotal ?? 0, 0, ',', '.') }}</strong></td>
                                </tr>
                                <tr style="background: #e3f2fd;">
                                    <td colspan="2" class="text-end"><strong style="font-size: 16px;">Total Biaya:</strong></td>
                                    <td class="text-end"><strong style="font-size: 16px; color: #667eea;">Rp {{ number_format($invoice->total ?? 0, 0, ',', '.') }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Ringkasan</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p><strong>Subtotal</strong></p>
                        <h4>Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</h4>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <p><strong>Total</strong></p>
                        <h3>Rp {{ number_format($invoice->total, 0, ',', '.') }}</h3>
                    </div>
                    <div class="mb-3">
                        <p><strong>Status Pembayaran</strong></p>
                        @php
                            $paidOrCovered = $invoice->status === 'paid' || str_starts_with($invoice->status, 'paid_by_');
                            $statusText = 'BELUM DIBAYAR';
                            if($invoice->status === 'paid') $statusText = 'SUDAH DIBAYAR';
                            if(str_starts_with($invoice->status, 'paid_by_')) $statusText = 'DIBAYAR OLEH ' . strtoupper(str_after($invoice->status, 'paid_by_'));
                        @endphp
                        <p class="badge {{ $paidOrCovered ? 'bg-success' : 'bg-danger' }} p-2">
                            {{ $statusText }}
                        </p>
                    </div>
                    @if($invoice->status === 'unpaid')
                    <div class="mb-3">
                        <form action="{{ route('invoice.markAsPaid', $invoice->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-2">
                                <label class="form-label">Pilih Cara Pembayaran</label>
                                <select name="payment_method" id="payment_method" class="form-select" required>
                                    <option value="Tunai">Tunai</option>
                                    <option value="BPJS">BPJS</option>
                                    <option value="Asuransi">Asuransi</option>
                                    <option value="Transfer">Transfer</option>
                                </select>
                            </div>
                            <div class="mb-2" id="pay-no-bpjs" style="display:none;">
                                <label class="form-label">No. BPJS / Polis Asuransi</label>
                                <input type="text" name="no_bpjs" id="pay_no_bpjs" class="form-control">
                                <small class="text-muted" id="pay-bpjs-hint">BPJS: 13 digit | Asuransi: minimal 6 karakter</small>
                                <div id="pay-bpjs-error" class="text-danger" style="display:none; font-size:12px; margin-top:4px;"></div>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Keterangan (opsional)</label>
                                <input type="text" name="keterangan" class="form-control" placeholder="Catatan pembayaran atau bukti transfer">
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success">Proses Pembayaran</button>
                                <a href="{{ route('invoice.print', $invoice->id) }}" target="_blank" class="btn btn-outline-primary">Cetak Struk</a>
                            </div>
                        </form>
                    </div>
                    <script>
                        (function(){
                            const sel = document.getElementById('payment_method');
                            const grp = document.getElementById('pay-no-bpjs');
                            const inp = document.getElementById('pay_no_bpjs');
                            const errDiv = document.getElementById('pay-bpjs-error');
                            const form = grp.closest('form');
                            
                            function validatePaymentNumber() {
                                const val = inp.value.trim();
                                const method = sel.value;
                                errDiv.style.display = 'none';
                                errDiv.textContent = '';
                                
                                if (method === 'BPJS') {
                                    if (!/^\d{13}$/.test(val)) {
                                        errDiv.textContent = 'Nomor BPJS harus tepat 13 digit angka';
                                        errDiv.style.display = 'block';
                                        return false;
                                    }
                                } else if (method === 'Asuransi') {
                                    if (!/^[a-zA-Z0-9]{6,}$/.test(val)) {
                                        errDiv.textContent = 'Nomor Asuransi minimal 6 karakter (huruf/angka)';
                                        errDiv.style.display = 'block';
                                        return false;
                                    }
                                }
                                return true;
                            }
                            
                            function toggle(){
                                if(!sel) return;
                                if(sel.value === 'BPJS' || sel.value === 'Asuransi'){
                                    grp.style.display = 'block'; inp.required = true;
                                } else { 
                                    grp.style.display = 'none'; 
                                    inp.required = false; 
                                    errDiv.style.display = 'none';
                                }
                            }
                            sel && sel.addEventListener('change', toggle);
                            inp && inp.addEventListener('blur', validatePaymentNumber);
                            inp && inp.addEventListener('keyup', validatePaymentNumber);
                            
                            form && form.addEventListener('submit', (e) => {
                                if ((sel.value === 'BPJS' || sel.value === 'Asuransi') && !validatePaymentNumber()) {
                                    e.preventDefault();
                                }
                            });
                            
                            toggle();
                        })();
                    </script>
                    @else
                        <div class="mb-3 text-center">
                            <a href="{{ route('invoice.print', $invoice->id) }}" target="_blank" class="btn btn-outline-primary">Cetak Struk</a>
                            <a href="{{ route('invoice.printThermal', $invoice->id) }}" target="_blank" class="btn btn-outline-secondary ms-2">Cetak (Thermal)</a>
                        </div>
                    @endif

                    @if($invoice->payments && $invoice->payments->count() > 0)
                        <hr>
                        <h6>Riwayat Pembayaran</h6>
                        <ul class="list-group mt-2">
                            @foreach($invoice->payments as $p)
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>{{ strtoupper($p->method) }}</strong>
                                        @if($p->no_bpjs)
                                            <div><small>No: {{ $p->no_bpjs }}</small></div>
                                        @endif
                                        @if($p->note)
                                            <div><small>{{ $p->note }}</small></div>
                                        @endif
                                    </div>
                                    <div class="text-end">
                                        <div>Rp {{ number_format($p->amount,0,',','.') }}</div>
                                        <div><small>{{ $p->paid_at ? \Carbon\Carbon::parse($p->paid_at)->format('d/m/Y H:i') : \Carbon\Carbon::parse($p->created_at)->format('d/m/Y H:i') }}</small></div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
