@extends('layout')
@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Pengaturan</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('settings.update') }}">
                        @csrf

                        {{-- Live preview --}}
                        <div class="mb-4">
                            <label class="form-label">Preview Tema</label>
                            <div id="themePreview" class="theme-preview p-0" style="max-width:420px; border-radius:8px; overflow:hidden; border:1px solid #e9e9e9;">
                                <div id="previewNavbar" class="preview-navbar d-flex align-items-center" style="gap:8px; padding:8px 12px; color:var(--text-on-primary)">
                                    <div class="preview-avatar"></div>
                                    <div style="font-weight:700;">Klinik Nusantara</div>
                                </div>
                                <div class="d-flex">
                                    <div id="previewSidebar" class="preview-sidebar p-2">
                                        <div class="preview-line short"></div>
                                        <div class="preview-line"></div>
                                    </div>
                                    <div class="preview-content p-3">
                                        <div class="preview-line short mb-2"></div>
                                        <div class="preview-line"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <style>
                            /* Preview styles use the same variables as layout; transitions for smooth change */
                            #themePreview { --primary-start: #667eea; --primary-end: #764ba2; --sidebar-bg: #2c3e50; --text-on-primary: #fff; --bg-main: #f5f7fa; --text-main: #203241; }
                            .preview-navbar {
                                background: linear-gradient(135deg, var(--primary-start), var(--primary-end));
                                transition: background 400ms ease, color 300ms ease;
                            }
                            .preview-avatar{ width:28px; height:28px; border-radius:50%; background: rgba(255,255,255,0.18); }
                            .preview-sidebar{
                                width:90px; min-height:80px; background: var(--sidebar-bg); color:#fff;
                                transition: width 300ms ease, background 400ms ease;
                            }
                            .preview-content{ background: #fff; color:#222; flex:1; transition: background 300ms ease, color 300ms ease; }
                            .preview-line{ height:10px; background:#f1f1f1; border-radius:4px; margin-bottom:8px; }
                            .preview-line.short{ width:40%; }
                            .preview-line.mb-2{ margin-bottom:12px; }
                        </style>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="sidebar_collapsed" id="sidebar_collapsed"
                                   {{ (isset($settings['sidebar_collapsed']) && $settings['sidebar_collapsed']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="sidebar_collapsed">Sidebar collapse by default</label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="notifications" id="notifications"
                                   {{ (isset($settings['notifications']) && $settings['notifications']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="notifications">Enable notifications (demo)</label>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tema</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="theme" id="theme_day" value="day"
                                           {{ (isset($settings['theme']) && $settings['theme'] === 'day') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="theme_day">Mode Siang (Pink)</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="theme" id="theme_night" value="night"
                                           {{ (!isset($settings['theme']) || $settings['theme'] === 'night') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="theme_night">Mode Malam (Biru)</label>
                                </div>
                            </div>
                        </div>

                        <script>
                            (function(){
                                const previewRoot = document.getElementById('themePreview');
                                const previewNavbar = document.getElementById('previewNavbar');
                                const previewSidebar = document.getElementById('previewSidebar');


                                function applyPreview(theme, collapsed){
                                    // update CSS variables on the preview root so children animate via CSS transitions
                                    if(theme === 'day'){
                                        previewRoot.style.setProperty('--primary-start', '#ff66a3');
                                        previewRoot.style.setProperty('--primary-end', '#ff85b8');
                                        previewRoot.style.setProperty('--sidebar-bg', '#4a2230');
                                        previewRoot.style.setProperty('--text-on-primary', '#fff');
                                    } else {
                                        previewRoot.style.setProperty('--primary-start', '#667eea');
                                        previewRoot.style.setProperty('--primary-end', '#764ba2');
                                        previewRoot.style.setProperty('--sidebar-bg', '#2c3e50');
                                        previewRoot.style.setProperty('--text-on-primary', '#fff');
                                    }

                                    // collapsed width animated via CSS transition on .preview-sidebar
                                    previewSidebar.style.width = collapsed ? '40px' : '90px';
                                }

                                // init preview with current selection
                                document.addEventListener('DOMContentLoaded', function(){
                                    const selTheme = document.querySelector('input[name="theme"]:checked');
                                    const sidebarCollapsed = document.getElementById('sidebar_collapsed');
                                    applyPreview(selTheme ? selTheme.value : 'night', sidebarCollapsed && sidebarCollapsed.checked);

                                    // watch theme radios
                                    document.querySelectorAll('input[name="theme"]').forEach(r => {
                                        r.addEventListener('change', function(e){
                                            applyPreview(e.target.value, document.getElementById('sidebar_collapsed').checked);
                                        });
                                    });

                                    // watch sidebar checkbox
                                    const cb = document.getElementById('sidebar_collapsed');
                                    if(cb){
                                        cb.addEventListener('change', function(e){
                                            const sel = document.querySelector('input[name="theme"]:checked');
                                            applyPreview(sel ? sel.value : 'night', e.target.checked);
                                        });
                                    }
                                });
                            })();
                        </script>

                        <button class="btn btn-primary">Simpan Pengaturan</button>
                    </form>

                    <hr>
                    <p class="text-muted">Catatan: Untuk sekarang pengaturan disimpan di session (tidak permanen). Jika ingin disimpan ke database, saya bisa tambahkan migrasi/kolom `settings` pada tabel `users`.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
