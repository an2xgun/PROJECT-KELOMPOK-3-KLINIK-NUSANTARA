@extends('layout')

@section('title','Detail Diagnosa')

@section('content')
<h1>{{ $diagnosis->name }}</h1>

<ul class="list-group mb-3">
  <li class="list-group-item"><strong>Kode:</strong> {{ $diagnosis->code }}</li>
  <li class="list-group-item"><strong>ICD-10:</strong> {{ $diagnosis->icd10 ?? '-' }}</li>
  <li class="list-group-item"><strong>Deskripsi:</strong> <p class="mb-0">{!! nl2br(e($diagnosis->description ?? '-')) !!}</p></li>
</ul>

<a href="{{ route('master.data_diagnosa.edit', $diagnosis) }}" class="btn btn-warning">Edit</a>
<a href="{{ route('master.data_diagnosa.index') }}" class="btn btn-secondary">Kembali</a>

@endsection
