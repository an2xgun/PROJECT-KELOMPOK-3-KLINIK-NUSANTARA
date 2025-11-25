@extends('layout')

@section('title','Daftar Diagnosa')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h1>Diagnosa</h1>
    <a href="{{ route('master.data_diagnosa.create') }}" class="btn btn-primary">+ Tambah Diagnosa</a>
</div>

<form method="get" class="mb-3">
    <div class="input-group">
        <input type="text" name="q" value="{{ $q ?? '' }}" class="form-control" placeholder="Cari kode / nama / ICD-10...">
        <button class="btn btn-outline-secondary">Cari</button>
    </div>
</form>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Kode</th>
            <th>Nama</th>
            <th>ICD-10</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($diagnoses as $d)
        <tr>
            <td>{{ $loop->iteration + ($diagnoses->currentPage()-1)*$diagnoses->perPage() }}</td>
            <td>{{ $d->code }}</td>
            <td><a href="{{ route('master.data_diagnosa.show', $d) }}">{{ $d->name }}</a></td>
            <td>{{ $d->icd10 }}</td>
    
            <td style="min-width: 180px;">
                <a href="{{ route('master.data_diagnosa.edit', $d) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('master.data_diagnosa.destroy', $d) }}" 
                      method="post" style="display:inline" 
                      onsubmit="return confirm('Hapus diagnosa ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center">Tidak ada data.</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{ $diagnoses->links() }}

@endsection
