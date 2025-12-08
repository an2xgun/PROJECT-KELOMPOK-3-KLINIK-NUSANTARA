@extends('layout')
@section('content')

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
                    <th>Subtotal</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $invoice)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ optional($invoice->pasien)->nama ?? '-' }}</td>
                        <td>Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</td>
                        <td><strong>Rp {{ number_format($invoice->total, 0, ',', '.') }}</strong></td>
                        <td>
                            <span class="badge {{ $invoice->status === 'paid' ? 'bg-success' : 'bg-warning' }}">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </td>
                        <td>{{ $invoice->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('invoice.show', $invoice->id) }}" class="btn btn-sm btn-info">Lihat</a>
                            @if($invoice->status === 'unpaid')
                                <form action="{{ route('invoice.markAsPaid', $invoice->id) }}" method="POST" style="display:inline;">
                                    @csrf @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-success">Bayar</button>
                                </form>
                            @endif
                            @if(Auth::user()->role === 'admin')
                                <form action="{{ route('invoice.destroy', $invoice->id) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin?')">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Belum ada invoice</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $invoices->links() }}
</div>

@endsection
