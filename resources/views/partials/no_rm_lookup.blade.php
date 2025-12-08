<div class="mb-3 no-rm-lookup">
    <label class="form-label">Cari No RM</label>
    <div class="input-group">
        <input id="noRmInput" class="form-control" placeholder="Masukkan No RM atau RM-0001" />
        <button id="noRmBtn" class="btn btn-outline-primary">Cari</button>
        <button id="noRmClear" class="btn btn-outline-secondary" type="button">Bersihkan</button>
    </div>
    <div id="noRmLookupMsg" class="mt-2"></div>
</div>

<script>
    (function(){
        const input = document.getElementById('noRmInput');
        const btn = document.getElementById('noRmBtn');
        const clearBtn = document.getElementById('noRmClear');
        const msg = document.getElementById('noRmLookupMsg');

        function normalize(q){
            if(!q) return '';
            const digits = q.replace(/\D/g, '');
            if(!digits) return '';
            return digits.padStart(4, '0');
        }

        function dispatchFound(payload){
            const ev = new CustomEvent('no-rm-found', { detail: payload });
            window.dispatchEvent(ev);
        }

        function showMessage(html, type='info'){
            const cls = type === 'error' ? 'alert alert-danger' : 'alert alert-info';
            msg.innerHTML = `<div class="${cls}">${html}</div>`;
        }

        btn.addEventListener('click', function(e){
            e.preventDefault();
            const val = input.value.trim();
            const norm = normalize(val);
            if(!norm){ showMessage('Masukkan No RM yang valid (mis. 0001 atau RM0001)', 'error'); return; }

            fetch('/api/patient/' + encodeURIComponent(norm))
                .then(r => r.json())
                .then(json => {
                    if(json && json.found){
                        showMessage(`Pasien ditemukan: <strong>${json.pasien.no_rm} - ${json.pasien.nama}</strong>`);
                        dispatchFound(json);
                    } else {
                        showMessage('Pasien tidak ditemukan. Anda dapat mendaftar pasien baru.', 'error');
                        dispatchFound({ found: false });
                    }
                })
                .catch(err => {
                    console.error(err);
                    showMessage('Terjadi kesalahan saat mencari pasien.', 'error');
                    dispatchFound({ found: false });
                });
        });

        clearBtn.addEventListener('click', function(){
            input.value = '';
            msg.innerHTML = '';
            window.dispatchEvent(new CustomEvent('no-rm-cleared'));
        });

        // allow Enter key on input
        input.addEventListener('keydown', function(e){ if(e.key === 'Enter'){ e.preventDefault(); btn.click(); } });
    })();
</script>
