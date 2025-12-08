@extends('layout')
@section('content')
<h3>Cari Pasien Lama</h3>

<div class="input-group mb-3">
  <input id="searchBox" class="form-control" placeholder="Ketik NIK / Nama / No RM">
  <button id="btnSearch" class="btn btn-outline-secondary">Cari</button>
</div>

<div id="searchResult"></div>

<script>
document.getElementById('btnSearch').addEventListener('click', function(){
    const q = document.getElementById('searchBox').value.trim();
    if(!q) return;
    fetch('/ajax/cari-pasien?keyword=' + encodeURIComponent(q))
    .then(r=>r.json())
    .then(data=>{
        const box = document.getElementById('searchResult');
        if(!data || data.length===0){
            box.innerHTML = '<div class="alert alert-warning">Pasien tidak ditemukan. <a href="{{ route("pasien.create") }}">Daftar pasien baru</a></div>';
            return;
        }
        let html = '<div class="list-group">';
        data.forEach(p=>{
            html += `<a class="list-group-item list-group-item-action" href="/pendaftaran/form/${p.id}">${p.no_rm} — ${p.nama} (${p.nik || "-"})</a>`;
        });
        html += '</div>';
        box.innerHTML = html;
    });
});
</script>
@endsection
