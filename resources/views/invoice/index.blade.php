@extends('layout')
@section('content')

{{-- Using fully-qualified Str calls to avoid duplicate imports --}}
<div class="container-fluid mt-4">
    <div class="row mb-3">
        <div class="col">
            <h3>Daftar Invoice</h3>
        </div>
        <div class="col-auto">
            <form method="GET" action="{{ route('invoice.index') }}" style="display:inline;">
                <select name="status" class="form-select" onchange="this.form.submit();">
                    <option value="">Semua Status</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Dibayar</option>
                    <option value="unpaid" {{ request('status') === 'unpaid' ? 'selected' : '' }}>Belum Dibayar</option>
                </select>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>No Invoice</th>
                    <th>Pasien</th>
                    <th class="text-end">Subtotal</th>
                    <th class="text-end">Total</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $invoice)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</strong></td>
                        <td>{{ optional($invoice->pasien)->nama ?? '-' }}</td>
                        <td class="text-end">Rp {{ number_format($invoice->subtotal ?? 0, 0, ',', '.') }}</td>
                        <td class="text-end"><strong style="color: #667eea;">Rp {{ number_format($invoice->total ?? 0, 0, ',', '.') }}</strong></td>
                        <td>
                            <span class="badge {{ $invoice->status === 'paid' || str_starts_with($invoice->status, 'paid_by_') ? 'bg-success' : 'bg-warning' }}">
                                @if($invoice->status === 'paid')
                                    Dibayar
                                @elseif(str_starts_with($invoice->status, 'paid_by_'))
                                    Dibayar ({{ strtoupper(\Illuminate\Support\Str::after($invoice->status, 'paid_by_')) }})
                                @else
                                    Belum Dibayar
                                @endif
                            </span>
                        </td>
                        <td>{{ $invoice->created_at ? \Carbon\Carbon::parse($invoice->created_at)->format('d/m/Y') : '-' }}</td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('invoice.show', $invoice->id) }}" class="btn btn-info" title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($invoice->status === 'unpaid' || !str_starts_with($invoice->status, 'paid_by_'))
                                    <form action="{{ route('invoice.markAsPaid', $invoice->id) }}" method="POST" style="display:inline;">
                                        @csrf @method('PUT')
                                        <button type="submit" class="btn btn-success" title="Proses Pembayaran">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                    </form>
                                @endif
                                @if(Auth::user()->role === 'admin')
                                    <form action="{{ route('invoice.destroy', $invoice->id) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Hapus Invoice" onclick="return confirm('Yakin ingin menghapus invoice ini?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-inbox"></i> Belum ada invoice
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $invoices->links() }}
</div>

@endsection
