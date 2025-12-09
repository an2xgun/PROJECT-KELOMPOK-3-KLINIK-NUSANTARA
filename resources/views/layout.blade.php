<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title ?? 'Klinik NUSANTARA' }}</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
  :root{
    --primary-start: #617dfbff; /* default blue gradient start */
    --primary-end: #764ba2;   /* default blue gradient end */
    --accent-color: #f75093ff; /* pink accent (used for day) */
    --text-on-primary: #ffffff;
    --sidebar-bg: #263e56ff;
    --bg-main: #e6effdff; /* page background */
    --text-main: #203241; /* main text color for readability */
    --navbar-height: 60px; /* fallback, will be updated via JS to match actual navbar */
  }
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: var(--bg-main);
    color: var(--text-main);
  }

  /* ===== NAVBAR ===== */
  .navbar-custom {
    background: linear-gradient(135deg, var(--primary-start) 0%, var(--primary-end) 100%);
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    padding: 0.6rem 1.2rem;
    position: sticky;
    top: 0;
    z-index: 1000;
  }

  .navbar-brand {
    font-weight: 700;
    font-size: 18px;
    color: white !important;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .navbar-brand img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
  }

  .navbar-custom .dropdown-menu {
    border: none;
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    border-radius: 8px;
  }

  .navbar-custom .dropdown-item {
    padding: 10px 20px;
  }

  .navbar-custom .dropdown-item:hover {
    background-color: #f5f7fa;
    color: #667eea;
  }

  .user-profile {
    display: flex;
    align-items: center;
    gap: 12px;
    color: white;
  }

  .user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255,255,255,0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
  }

  /* ===== SIDEBAR ===== */
  .sidebar {
    width: 260px;
    height: calc(100vh - var(--navbar-height));
    background: var(--sidebar-bg);
    color: #ecf0f1;
    padding: 20px;
    position: fixed;
    top: var(--navbar-height);
    left: 0;
    overflow-y: auto;
    transition: all 0.3s ease;
    box-shadow: 2px 0 10px rgba(0,0,0,0.1);
  }

  .sidebar.collapsed {
    width: 80px;
    padding: 20px 10px;
  }

  .sidebar-title {
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    color: rgba(255,255,255,0.75);
    margin-top: 20px;
    margin-bottom: 10px;
    transition: opacity 0.3s ease;
  }

  .sidebar.collapsed .sidebar-title {
    opacity: 0;
    height: 0;
    margin: 0;
  }

  .sidebar a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 15px;
    color: #ecf0f1;
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.3s ease;
    font-size: 14px;
    margin-bottom: 5px;
    white-space: nowrap;
  }

  .sidebar a:hover {
    /* subtle overlay to indicate hover without breaking solid background */
    background: rgba(255,255,255,0.03);
    padding-left: 18px;
  }

  .sidebar a.active {
    background: var(--primary-start);
    color: var(--text-on-primary);
    box-shadow: 0 2px 8px rgba(0,0,0,0.12);
  }

  .sidebar a i {
    width: 20px;
    text-align: center;
    font-size: 18px;
  }

  .sidebar a span {
    transition: opacity 0.3s ease;
  }

  .sidebar.collapsed a span {
    opacity: 0;
    display: none;
  }

  .sidebar .collapse {
    padding-left: 20px;
  }

  .sidebar .collapse a {
    font-size: 13px;
    padding: 8px 12px;
  }

  /* small badges / chevrons */
  .sidebar .chev {
    margin-left: auto;
    opacity: 0.85;
  }

  /* ===== TOGGLE BUTTON ===== */
  .toggle-btn {
    position: fixed;
    top: calc(var(--navbar-height) + 10px);
    left: 270px;
    z-index: 999;
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 8px 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  }

  .sidebar.collapsed ~ .toggle-btn {
    left: 90px;
  }

  /* ===== CONTENT ===== */
  .main-content {
    margin-left: 260px;
    margin-top: var(--navbar-height);
    padding: 30px;
    transition: all 0.3s ease;
    min-height: calc(100vh - 60px);
  }

  .sidebar.collapsed ~ .main-content {
    margin-left: 80px;
  }

  /* Hidden section links by default; JS toggles this class */
  .section-hidden {
    display: none !important;
  }

  /* ===== CARD STYLES ===== */
  .card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
  }

  .card:hover {
    box-shadow: 0 5px 20px rgba(0,0,0,0.12);
  }

  .card-header {
    background: linear-gradient(135deg, var(--primary-start) 0%, var(--primary-end) 100%);
    border: none;
    border-radius: 12px 12px 0 0;
    color: white;
    padding: 1.5rem;
  }

  /* ===== BUTTON STYLES ===== */
  .btn-primary {
    background: var(--primary-start);
    border: none;
  }

  .btn-primary:hover {
    background: #5568d3;
  }

  .btn-success {
    background: #27ae60;
  }

  .btn-success:hover {
    background: #229954;
  }

  /* ===== TABLE STYLES ===== */
  .table {
    border-radius: 8px;
    overflow: hidden;
  }

  .table thead {
    background: #34495e;
    color: white;
  }

  .table tbody tr:hover {
    background: #f0f0f0;
  }

  /* ===== ALERT STYLES ===== */
  .alert {
    border: none;
    border-radius: 8px;
  }

  /* ===== SCROLLBAR ===== */
  ::-webkit-scrollbar {
    width: 8px;
  }

  ::-webkit-scrollbar-track {
    background: #f1f1f1;
  }

  ::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
  }

  ::-webkit-scrollbar-thumb:hover {
    background: #555;
  }

  /* ===== RESPONSIVE ===== */
  @media (max-width: 768px) {
    .sidebar {
      width: 260px;
      position: absolute;
      background: var(--sidebar-bg);

    .main-content {
      margin-left: 0;
      padding: 20px;
    }

    .toggle-btn {
      left: 20px;
    }

    .navbar-custom .navbar-brand {
      font-size: 18px;
    }
  }

  /* Floating action button for doctors to quickly start pemeriksaan */
  .float-exam-btn {
    position: fixed;
    right: 28px;
    bottom: 28px;
    z-index: 1200;
    border-radius: 999px;
    padding: 12px 16px;
    display: flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.12);
    font-weight: 600;
  }

  @media (max-width: 576px) {
    .float-exam-btn { right: 12px; bottom: 12px; padding: 10px 12px; }
  }
</style>

</head>

<body>

<!-- ===== NAVBAR ===== -->
<nav class="navbar navbar-custom">
  <div class="container-fluid">
    <a class="navbar-brand" href="{{ route('dashboard') }}">
      <i class="bi bi-hospital"></i>
      <span>Klinik Nusantara</span>
    </a>

    <div class="ms-auto d-flex align-items-center">
      <div class="dropdown">
        <button class="btn btn-link text-white text-decoration-none dropdown-toggle" type="button" data-bs-toggle="dropdown">
          <div class="user-profile">
            <div class="user-avatar">
              {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div>
              <div style="font-size: 14px;">{{ Auth::user()->name }}</div>
              <div style="font-size: 12px; opacity: 0.8;">{{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}</div>
            </div>
          </div>
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="bi bi-person-circle"></i> Profil</a></li>
          <li><a class="dropdown-item" href="{{ route('settings.index') }}"><i class="bi bi-gear"></i> Pengaturan</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item text-danger" href="{{ route('logout') }}"><i class="bi bi-box-arrow-right"></i> Keluar</a></li>
        </ul>
      </div>
    </div>
  </div>
</nav>

<!-- ===== SIDEBAR ===== -->
<div id="sidebar" class="sidebar">

  <!-- Dashboard -->
  <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">
    <i class="bi bi-speedometer2"></i>
    <span>Dashboard</span>
  </a>

  <hr style="border-color: rgba(255,255,255,0.1); margin: 15px 0;">

  {{-- ALUR UTAMA: PENDAFTARAN (visible untuk: admin, petugas pendaftaran) --}}
  @if(Auth::user()->role === 'admin' || Auth::user()->role === 'petugas_pendaftaran')
  <div class="sidebar-title"><i class="bi bi-door-left"></i> Pendaftaran Pasien</div>
  <a href="{{ route('pendaftaran.choice') }}" class="{{ request()->is('pendaftaran/choice*') ? 'active' : '' }}">
    <i class="bi bi-plus-circle"></i>
    <span>Pendaftaran Baru</span>
  </a>
  <a href="{{ route('pendaftaran.list') }}" class="{{ request()->is('pendaftaran/list*') ? 'active' : '' }}">
    <i class="bi bi-list-check"></i> 
    <span>Daftar Antrian</span>
  </a>
  @endif

  {{-- ALUR UTAMA: PEMERIKSAAN (visible untuk: admin, dokter) --}}
  @if(Auth::user()->role === 'admin' || Auth::user()->role === 'dokter')
  @if(Auth::user()->role !== 'petugas_pendaftaran')
  <hr style="border-color: rgba(255,255,255,0.1); margin: 15px 0;">
  <div class="sidebar-title"><i class="bi bi-stethoscope"></i> Pemeriksaan Medis</div>
  @endif
  <!-- Tampilkan antrian pemeriksaan langsung ke route dokter untuk admin dan dokter -->
  <a href="{{ route('examination.queue') }}" class="{{ request()->is('examination/queue*') ? 'active' : '' }}" style="display: flex; align-items: center; justify-content: space-between;">
    <div style="display: flex; align-items: center; gap: 12px;">
      <i class="bi bi-hourglass-split"></i>
      <span>Antrian Pemeriksaan</span>
    </div>
    <span id="pending-count-badge-sidebar" class="badge bg-danger" style="display: none; font-size: 11px; padding: 4px 8px;">0</span>
  </a>
  <a href="{{ route('rekam.index') }}" class="{{ request()->is('rekam*') ? 'active' : '' }}">
    <i class="bi bi-file-text"></i>
    <span>Rekam Medis</span>
  </a>
  @endif

  {{-- ALUR UTAMA: RESEP (visible untuk: admin, dokter, apoteker) --}}
  @if(Auth::user()->role === 'admin' || Auth::user()->role === 'dokter' || Auth::user()->role === 'apoteker')
  <hr style="border-color: rgba(255,255,255,0.1); margin: 15px 0;">
  <div class="sidebar-title"><i class="bi bi-prescription2"></i> Obat & Resep</div>
  <a href="{{ route('prescription.index') }}" class="{{ request()->is('prescription*') ? 'active' : '' }}">
    <i class="bi bi-file-earmark"></i>
    <span>Daftar Resep Dokter</span>
  </a>
  @endif

  {{-- ADMINISTRASI (visible untuk: admin only) --}}
  @if(Auth::user()->role === 'admin')
  <hr style="border-color: rgba(255,255,255,0.1); margin: 15px 0;">
  <div class="sidebar-title"><i class="bi bi-gear"></i> Administrasi</div>
  <a href="{{ route('pasien.index') }}" class="{{ request()->is('pasien*') ? 'active' : '' }}">
    <i class="bi bi-people"></i>
    <span>Data Pasien</span>
  </a>
  <a href="{{ route('master.jadwal_dokter') }}" class="{{ request()->is('master/dokter*') ? 'active' : '' }}">
    <i class="bi bi-calendar-event"></i>
    <span>Jadwal Dokter</span>
  </a>
  <a href="{{ route('master.data_tindakan') }}" class="">
    <i class="bi bi-tools"></i>
    <span>Data Tindakan Medis</span>
  </a>
  <a href="{{ route('master.data_diagnosa') }}" class="">
    <i class="bi bi-journal-text"></i>
    <span>Data Diagnosa</span>
  </a>

  <hr style="border-color: rgba(255,255,255,0.1); margin: 15px 0;">
  <div class="sidebar-title"><i class="bi bi-box-seam"></i> Apotek</div>
  <a href="{{ route('gudang_obat.index') }}" class="{{ request()->is('gudang_obat*') ? 'active' : '' }}">
    <i class="bi bi-capsule"></i>
    <span>Daftar Obat</span>
  </a>

  <hr style="border-color: rgba(255,255,255,0.1); margin: 15px 0;">
  <div class="sidebar-title"><i class="bi bi-receipt"></i> Keuangan</div>
  <a href="{{ route('pendaftaran.antrian') }}" class="{{ request()->is('pendaftaran/antrian*') ? 'active' : '' }}">
    <i class="bi bi-hourglass-split"></i>
    <span>Antrian Pasien</span>
  </a>
  <a href="{{ route('invoice.index') }}" class="{{ request()->is('invoice*') ? 'active' : '' }}">
    <i class="bi bi-file-earmark-pdf"></i>
    <span>Kasir</span>
  </a>
  @endif

  {{-- APOTEK (visible untuk: apoteker) --}}
  @if(Auth::user()->role === 'apoteker' && Auth::user()->role !== 'admin')
  <hr style="border-color: rgba(255,255,255,0.1); margin: 15px 0;">
  <div class="sidebar-title"><i class="bi bi-box-seam"></i> Manajemen Obat</div>
  <a href="{{ route('gudang_obat.index') }}" class="{{ request()->is('gudang_obat*') ? 'active' : '' }}">
    <i class="bi bi-capsule"></i>
    <span>Daftar Obat</span>
  </a>

  <hr style="border-color: rgba(255,255,255,0.1); margin: 15px 0;">
  <div class="sidebar-title"><i class="bi bi-gift"></i> Penyerahan Obat</div>
  <a href="{{ route('dispensing.queue') }}" class="{{ request()->is('dispensing*') ? 'active' : '' }}">
    <i class="bi bi-cart-check"></i>
    <span>Antrian Dispensing</span>
  </a>
  @endif

  {{-- KASIR (visible untuk: kasir) --}}
  @if(Auth::user()->role === 'kasir' && Auth::user()->role !== 'admin')
  <hr style="border-color: rgba(255,255,255,0.1); margin: 15px 0;">
  <div class="sidebar-title"><i class="bi bi-cash-coin"></i> Pembayaran</div>
  <a href="{{ route('pendaftaran.antrian') }}" class="{{ request()->is('pendaftaran/antrian*') ? 'active' : '' }}">
    <i class="bi bi-hourglass-split"></i>
    <span>Antrian Pasien</span>
  </a>
  <a href="{{ route('invoice.index') }}" class="{{ request()->is('invoice*') ? 'active' : '' }}">
    <i class="bi bi-file-earmark-pdf"></i>
    <span>Daftar Invoice</span>
  </a>
  @endif

</div>

<!-- Toggle Button -->
<button id="toggle" class="toggle-btn" title="Collapse/Expand">
  <i class="bi bi-chevron-left"></i>
</button>

@if(Auth::check() && (Auth::user()->role === 'dokter' || Auth::user()->role === 'admin'))
  <a href="{{ route('examination.queue') }}" class="btn btn-primary float-exam-btn" title="Mulai Pemeriksaan" id="floatExamBtn">
    <i class="bi bi-stethoscope" style="font-size:18px;"></i>
    <span style="color:white;">Mulai Pemeriksaan</span>
  </a>
@endif

<!-- ===== MAIN CONTENT ===== -->
<div class="main-content">
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="bi bi-check-circle"></i> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
  // Ensure navbar height is reflected in CSS so sidebar and content don't overlap
  (function syncNavbarHeight(){
    const nav = document.querySelector('.navbar-custom');
    function apply() {
      if (!nav) return;
      const h = nav.offsetHeight + 'px';
      document.documentElement.style.setProperty('--navbar-height', h);
    }
    // apply now and on resize (debounced)
    apply();
    let t;
    window.addEventListener('resize', () => { clearTimeout(t); t = setTimeout(apply, 120); });
  })();

  // Apply persisted user settings (provided by AppServiceProvider)
  const USER_SETTINGS = @json($user_settings ?? []);
  const sidebar = document.getElementById('sidebar');
  const toggle = document.getElementById('toggle');

  toggle.addEventListener('click', () => {
    sidebar.classList.toggle('collapsed');
    if(sidebar.classList.contains('collapsed')) {
      toggle.innerHTML = '<i class="bi bi-chevron-right"></i>';
    } else {
      toggle.innerHTML = '<i class="bi bi-chevron-left"></i>';
    }
  });

  // Apply initial collapsed state from settings
  if (USER_SETTINGS && USER_SETTINGS.sidebar_collapsed) {
    if (!sidebar.classList.contains('collapsed')) {
      sidebar.classList.add('collapsed');
      toggle.innerHTML = '<i class="bi bi-chevron-right"></i>';
    }
  }

  // Apply theme from settings (user_settings.theme: 'day'|'night')
  (function(){
    const theme = (USER_SETTINGS && USER_SETTINGS.theme) ? USER_SETTINGS.theme : 'night';
    const root = document.documentElement;
    if (theme === 'day') {
      // Day: pink theme
      root.style.setProperty('--primary-start', '#cd6c94ff');
      root.style.setProperty('--primary-end', '#ff86c1');
      root.style.setProperty('--sidebar-bg', '#6b3346');
      root.style.setProperty('--bg-main', '#fff8fb');
      root.style.setProperty('--text-main', '#233c4d');
    } else {
      // Night (default blue)
      root.style.setProperty('--primary-start', '#2563eb');
      root.style.setProperty('--primary-end', '#1e3a8a');
      root.style.setProperty('--sidebar-bg', '#234866');
      root.style.setProperty('--bg-main', '#071229');
      root.style.setProperty('--text-main', '#e6f3ff');
    }
  })();

  // Mark active menu
  document.querySelectorAll('.sidebar a').forEach(link => {
    if (link.href === window.location.href) {
      link.classList.add('active');
    }
  });

  // Sidebar sections: hide links under each `.sidebar-title` and toggle on click
  (function() {
    const titles = Array.from(document.querySelectorAll('.sidebar .sidebar-title'));
    titles.forEach(title => {
      let nodes = [];
      let el = title.nextElementSibling;
      while (el && !el.classList.contains('sidebar-title') && el.tagName.toLowerCase() !== 'hr') {
        if (el.matches('a')) nodes.push(el);
        el = el.nextElementSibling;
      }
      if (nodes.length) {
        // hide them initially
        nodes.forEach(n => n.classList.add('section-hidden'));
        title.style.cursor = 'pointer';
        title.addEventListener('click', () => {
          const closed = title.classList.toggle('section-closed');
          nodes.forEach(n => n.classList.toggle('section-hidden'));
        });
      }
    });
  })();

  // Poll pending pendaftaran count untuk sidebar badge
  (function() {
    const badge = document.getElementById('pending-count-badge-sidebar');
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    async function fetchCount() {
      try {
        const res = await fetch('/api/pending-count', {
          credentials: 'same-origin',
          headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
          }
        });
        if (res.ok) {
          const data = await res.json();
          if (data.count > 0) {
            badge.textContent = data.count;
            badge.style.display = 'inline-block';
          } else {
            badge.style.display = 'none';
          }
        }
      } catch (e) {
        console.warn('Could not fetch pending count:', e);
      }
    }

    if (badge) {
      fetchCount();
      setInterval(fetchCount, 20000); // Poll every 20 seconds
    }
  })();
</script>

</body>
</html>
