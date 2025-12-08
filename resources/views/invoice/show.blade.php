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
                    <p><strong>Tanggal:</strong> {{ $invoice->created_at->format('d/m/Y H:i') }}</p>
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
                    <h5>Detail Pembayaran</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Deskripsi</th>
                                <th>Tipe</th>
                                <th class="text-end">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoice->items as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->type }}</td>
                                <td class="text-end">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                            <tr class="table-light">
                                <td colspan="2" class="text-end"><strong>Subtotal:</strong></td>
                                <td class="text-end"><strong>Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</strong></td>
                            </tr>
                            <tr class="table-light">
                                <td colspan="2" class="text-end"><strong>Total:</strong></td>
                                <td class="text-end"><strong>Rp {{ number_format($invoice->total, 0, ',', '.') }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
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
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
