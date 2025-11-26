@extends('layout')

@section('title','Tambah Diagnosa')

@section('content')
<h1>Tambah Diagnosa</h1>

<form action="{{ route('master.data_diagnosa.store') }}" method="post">
  @csrf
  <div class="mb-3">
    <label class="form-label">Kode</label>
    <input type="text" name="code" class="form-control" value="{{ old('code') }}" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Nama</label>
    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
  </div>
  <div class="mb-3">
    <label class="form-label">ICD-10</label>
    <input type="text" name="icd10" class="form-control" value="{{ old('icd10') }}">
  </div>
  <div class="mb-3">
    <label class="form-label">Deskripsi</label>
    <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
  </div>
  <button class="btn btn-primary">Simpan</button>
  <a href="{{ route('master.data_diagnosa.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
