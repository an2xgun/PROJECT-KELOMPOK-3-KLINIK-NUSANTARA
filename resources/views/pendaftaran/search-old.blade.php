@extends('layout')
@section('content')

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <h2 class="mb-4">
                <a href="{{ route('pendaftaran.choice') }}" class="btn btn-secondary btn-sm me-2">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                Cari Pasien Lama
            </h2>

            <div class="card mb-4">
                <div class="card-body">
                    <div class="input-group input-group-lg mb-3">
                        <input type="text" id="searchInput" class="form-control" 
                               placeholder="Masukkan No RM..." 
                               autocomplete="off" list="noRmSuggestions">
                        <datalist id="noRmSuggestions"></datalist>
                        <button class="btn btn-primary" type="button" id="searchBtn">
                            <i class="bi bi-search"></i> Cari
                        </button>
                    </div>
                </div>
            </div>

            <!-- Result -->
            <div id="searchResult"></div>
        </div>

        <script>
        // Combined JS: suggestions + search by exact No RM
        const input = document.getElementById('searchInput');
        const btn = document.getElementById('searchBtn');
        const resultWrap = document.getElementById('searchResult');

        input.addEventListener('input', function() {
            const val = this.value.trim();
            if(val.length < 2) return;
            fetch(`/api/pasien/suggest-no-rm?q=${encodeURIComponent(val)}`)
                .then(res => res.json())
                .then(data => {
                    const datalist = document.getElementById('noRmSuggestions');
                    datalist.innerHTML = '';
                    data.forEach(no_rm => {
                        const option = document.createElement('option');
                        option.value = no_rm;
                        datalist.appendChild(option);
                    });
                })
                .catch(() => {});
        });

        function renderNotFound() {
            resultWrap.innerHTML = `\n        <div class="alert alert-info">\n            Pasien tidak ditemukan. <a href="{{ route('pendaftaran.create-new-patient') }}">Daftar pasien baru?</a>\n        </div>\n    `;
        }

        function renderError(err) {
            resultWrap.innerHTML = '<div class="alert alert-danger">Error: ' + err + '</div>';
        }

        function renderPatientCard(p) {
            let html = `<div class="card"><div class="card-body">\n                <h6 class="card-title">${p.nama}</h6>\n                <p class="card-text text-muted mb-2">\n                    <small>\n                        <strong>No RM:</strong> ${p.no_rm}<br>\n                        <strong>NIK:</strong> ${p.nik || '-'}<br>\n                        <strong>DOB:</strong> ${p.tanggal_lahir || '-'}\n                    </small>\n                </p>\n                <button class="btn btn-success" onclick="selectPasien(${p.id})">Pilih Pasien</button>\n            </div></div>`;
            resultWrap.innerHTML = html;
        }

        function searchPasienByList() {
            const query = input.value.trim();
            if(!query) {
                resultWrap.innerHTML = '<div class="alert alert-warning">Masukkan No RM</div>';
                return;
            }
            // exact lookup endpoint
            fetch(`/api/patient/${encodeURIComponent(query)}`)
                .then(res => {
                    if(res.status === 404) return null;
                    return res.json();
                })
                .then(p => {
                    if(!p || !p.no_rm) {
                        renderNotFound();
                        return;
                    }
                    renderPatientCard(p);
                })
                .catch(renderError);
        }

        btn.addEventListener('click', searchPasienByList);
        input.addEventListener('keypress', function(e) {
            if(e.key === 'Enter') {
                e.preventDefault();
                searchPasienByList();
            }
        });

        function selectPasien(pasienId) {
            window.location.href = `/pendaftaran/select-poli/${pasienId}`;
        }
        </script>

<style>
.card {
    border-radius: 8px;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.card:hover {
    border-color: #667eea;
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
    transform: translateY(-2px);
}
</style>

@endsection
