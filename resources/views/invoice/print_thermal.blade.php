<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Struk Thermal</title>
  <style>
    body { font-family: Arial, Helvetica, sans-serif; font-size:12px; }
    .receipt { width: 58mm; max-width: 58mm; padding: 6px; }
    .center { text-align:center; }
    .items td { padding:4px 0; }
    .bold { font-weight:700; }
    hr { border:none; border-top:1px dashed #000; margin:8px 0; }
  </style>
</head>
<body>
  <div class="receipt">
    <div class="center">
      <div class="bold">Klinik Nusantara</div>
      <div>Struk Pembayaran</div>
      <div style="margin-top:6px;">-----------------------------</div>
    </div>

    <div style="margin-top:8px;">
      <div>INV: INV-{{ str_pad($invoice->id,5,'0',STR_PAD_LEFT) }}</div>
      <div>Pasien: {{ optional($invoice->pasien)->nama ?? '-' }}</div>
      <div>NoRM: {{ optional($invoice->pasien)->no_rm ?? '-' }}</div>
      <div>Tgl: {{ $invoice->created_at ? \Carbon\Carbon::parse($invoice->created_at)->format('d/m/Y H:i') : '-' }}</div>
    </div>

    <hr>

    <table class="items" width="100%">
      @foreach($invoice->items as $item)
      <tr>
        <td style="width:70%;">{{ $item->name }}</td>
        <td style="text-align:right;">Rp {{ number_format($item->amount,0,',','.') }}</td>
      </tr>
      @endforeach
      <tr>
        <td class="bold">TOTAL</td>
        <td class="bold" style="text-align:right;">Rp {{ number_format($invoice->total,0,',','.') }}</td>
      </tr>
    </table>

    <hr>

    @if($invoice->no_bpjs)
      <div>No. BPJS/Asuransi: {{ $invoice->no_bpjs }}</div>
    @endif

    <div class="center" style="margin-top:10px;">Terima kasih, semoga lekas sembuh</div>
  </div>

<script>window.onload = function(){ setTimeout(()=>{ window.print(); },300); }</script>
</body>
</html>
