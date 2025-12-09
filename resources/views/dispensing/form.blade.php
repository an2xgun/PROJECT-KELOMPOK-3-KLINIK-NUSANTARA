@extends('layout')
@section('content')

<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h3><i class="bi bi-box-arrow-in-right"></i> Form Penyerahan Obat</h3>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('dispensing.queue') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Patient Information -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-person-vcard"></i> Informasi Pasien</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <small class="text-muted">Nama Pasien</small>
                            <p><strong>{{ $prescription->rekam->pasien->nama ?? '-' }}</strong></p>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">No RM</small>
                            <p><strong>{{ data_get($prescription, 'rekam.pasien.no_rm') ?? data_get($prescription, 'rekam.pasien.kodepasien') ?? '-' }}</strong></p>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">&nbsp;</small>
                            <p>&nbsp;</p>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">Tanggal Resep</small>
                            <p><strong>{{ $prescription->created_at ? \Carbon\Carbon::parse($prescription->created_at)->format('d/m/Y H:i') : '-' }}</strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Prescription Items -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-pill"></i> Detail Obat-obatan</h5>
                </div>
                <div class="card-body">
                    <p><strong><span id="disp_nama">{{ data_get($prescription, 'rekam.pasien.nama', '-') }}</span></strong></p>
                    <p><strong><span id="disp_kodepasien">{{ data_get($prescription, 'rekam.pasien.no_rm') ?? data_get($prescription, 'rekam.pasien.kodepasien') ?? '-' }}</span></strong></p>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Obat</th>
                                    <th>Dosis</th>
                                    <th>Jumlah</th>
                                    <th>Stok</th>
                                    <th>Status Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($prescription->items as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td><strong>{{ $item->obat->nama ?? '-' }}</strong></td>
                                    <td>{{ $item->dosis ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $item->jumlah }} unit</span>
                                    </td>
                                    <td>
                                        <strong>{{ $item->obat->stok ?? 0 }}</strong> unit
                                    </td>
                                    <td>
                                        @if($item->obat->stok >= $item->jumlah)
                                            <span class="badge bg-success"><i class="bi bi-check-circle"></i> Tersedia</span>
                                        @else
                                            <span class="badge bg-danger"><i class="bi bi-exclamation-circle"></i> Stok Kurang</span>
                                            <small class="d-block text-danger">Kurang: {{ $item->jumlah - $item->obat->stok }} unit</small>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Form -->
    <div class="row mb-4">
        <div class="col-md-12">
            <form action="{{ route('dispensing.confirm', $prescription->id) }}" method="POST">
                @csrf
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-clipboard-check"></i> Konfirmasi Penyerahan</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Catatan Penyerahan (opsional)</label>
                            <textarea name="catatan" class="form-control" rows="3" placeholder="Misalnya: Obat diserahkan pada jam 14:00, dll"></textarea>
                        </div>

                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                            <strong>Peringatan:</strong> Pastikan semua obat tersedia sebelum mengkonfirmasi penyerahan.
                            Stok obat akan berkurang sesuai dengan jumlah yang diberikan.
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-check-circle"></i> Konfirmasi & Berikan Obat
                        </button>
                        <a href="{{ route('dispensing.queue') }}" class="btn btn-secondary btn-lg">
                            <i class="bi bi-x-circle"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- No RM lookup for dispensing --}}
@include('partials.no_rm_lookup')

<script>
    // Update displayed patient info when lookup dispatches result
    window.addEventListener('no-rm-found', function(ev){
        const data = ev.detail || {};
        if(data.found && data.pasien){
            document.getElementById('disp_nama').textContent = data.pasien.nama || '-';
            // prefer no_rm for display
            document.getElementById('disp_kodepasien').textContent = data.pasien.no_rm || data.pasien.kodepasien || '-';
            document.getElementById('disp_no_rm').textContent = data.pasien.no_rm || data.pasien.kodepasien || '-';
        } else if(!data.found){
            // clear
            document.getElementById('disp_nama').textContent = '-';
            document.getElementById('disp_kodepasien').textContent = '-';
            document.getElementById('disp_no_rm').textContent = '-';
        }
    });

    // Clear handler
    window.addEventListener('no-rm-cleared', function(){
        document.getElementById('disp_nama').textContent = '{{ data_get($prescription, "rekam.pasien.nama", "-") }}';
        document.getElementById('disp_kodepasien').textContent = '{{ data_get($prescription, "rekam.pasien.no_rm") ?? data_get($prescription, "rekam.pasien.kodepasien") ?? "-" }}';
        document.getElementById('disp_no_rm').textContent = '{{ data_get($prescription, "rekam.pasien.no_rm") ?? data_get($prescription, "rekam.pasien.kodepasien") ?? "-" }}';
    });
</script>

@endsection
