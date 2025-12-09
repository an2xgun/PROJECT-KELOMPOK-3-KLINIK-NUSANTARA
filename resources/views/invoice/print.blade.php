@extends('layout')
@section('content')

<div class="container-fluid mt-4">
    <div class="card p-4">
        <div class="text-center mb-3">
            <h4>Klinik Nusantara</h4>
            <small>Struk Pembayaran</small>
        </div>

        <div>
            <p><strong>Nomor Invoice:</strong> INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</p>
            <p><strong>Nama Pasien:</strong> {{ optional($invoice->pasien)->nama ?? '-' }}</p>
            <p><strong>No. RM:</strong> {{ optional($invoice->pasien)->no_rm ?? '-' }}</p>
            <p><strong>Tanggal:</strong> {{ $invoice->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Jenis Pembayaran:</strong> {{ $invoice->jenis_pembayaran ?? ($invoice->status ?? '-') }}</p>
            @if($invoice->no_bpjs)
                <p><strong>No. BPJS/Asuransi:</strong> {{ $invoice->no_bpjs }}</p>
            @endif
        </div>

        <hr>

        <table class="table table-borderless">
            <tbody>
                @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td class="text-end">Rp {{ number_format($item->amount,0,',','.') }}</td>
                </tr>
                @endforeach
                <tr>
                    <td><strong>Total</strong></td>
                    <td class="text-end"><strong>Rp {{ number_format($invoice->total,0,',','.') }}</strong></td>
                </tr>
            </tbody>
        </table>

        <div class="text-center mt-4">
            <small>Terima kasih telah berobat di Klinik Nusantara</small>
        </div>
    </div>
</div>

<script>
    window.onload = function(){
        setTimeout(() => { window.print(); }, 500);
    }
</script>

@endsection
