<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title ?? 'Klnik NUSANTARA' }}</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
  body {
    font-family: Poppins, system-ui, Arial;
    background: #e9f2ff; /* biru lembut */
    overflow-x: hidden;
  }

  /* SIDEBAR */
  .sidebar{
    width: 240px;
    position: fixed;
    left: 0; top: 0; bottom: 0;

    /* GRADIENT BIRU */
    background: linear-gradient(180deg, #1e4f9c, #0b2f63);

    color: #fff;
    padding: 22px 18px;
    transition: all 0.3s ease;
    overflow-y: auto;
    box-shadow: 2px 0 10px rgba(0,0,0,0.2);
  }

  .sidebar.collapsed{
    width: 70px;
    padding: 20px 10px;
  }

  /* LOGO SIMETRIS */
  .logo-circle{
    width: 70px;
    height: 70px;
    border-radius: 50%;
    object-fit: cover;

    border: 3px solid rgba(255,255,255,0.4);
    box-shadow: 0 4px 12px rgba(0,0,0,0.25);

    display: block;
    margin-left: auto;
    margin-right: auto;
    transition: all .3s ease;
  }

  .sidebar.collapsed .logo-circle{
    width: 45px;
    height: 45px;
  }

  /* TEXT LOGO */
  .sidebar-title {
    text-align: center;
    font-size: 18px;
    font-weight: 600;
    margin-top: 8px;
    margin-bottom: 20px;
    transition: 0.3s;
  }

  .sidebar.collapsed .sidebar-title{
    opacity: 0;
    height: 0;
  }

  /* LINK */
  .sidebar a{
    color: rgba(255,255,255,0.9);
    text-decoration: none;
    display: flex;
    gap: 10px;
    align-items: center;
    padding: 10px 12px;
    border-radius: 10px;
    white-space: nowrap;
    transition: all .25s ease;
    font-size: 15px;
  }

  /* HOVER BIRU */
  .sidebar a:hover{
    background: rgba(255,255,255,0.18);
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
  }

  .sidebar a.active{
    background: rgba(255,255,255,0.28);
    backdrop-filter: blur(2px);
  }

  /* HIDE TEXT SAAT COLLAPSE */
  .sidebar a span{
    transition: opacity .2s ease;
  }
  .sidebar.collapsed a span{
    opacity: 0;
    width: 0;
  }

  /* CONTENT */
  .content{
    margin-left: 260px;
    padding: 28px;
    transition: all 0.3s ease;
  }

  .collapsed + .content{
    margin-left: 90px;
  }

  /* TOGGLE BTN */
  .toggle-btn{
    position: fixed;
    top: 16px;
    left: 250px;
    z-index: 999;
    background: white;
    border-radius: 8px;
    padding: 6px 10px;
    border: 1px solid #cfd6e4;
    cursor: pointer;
    transition: all .3s;
    box-shadow: 0 2px 5px rgba(0,0,0,0.15);
  }

  .sidebar.collapsed + .toggle-btn{
    left: 80px;
  }

  /* SUBMENU */
  .collapse a{
    padding-left: 25px;
    font-size: 14px;
  }
</style>


</head>

<body>

<div id="sidebar" class="sidebar">

  <!-- LOGO -->
  <div class="text-center mb-3">
    <img src="https://i.pinimg.com/736x/cd/0a/f4/cd0af474c82e8359413ce2d038a045ea.jpg" class="logo-circle mb-2">
    <div class="sidebar-title text-center">Klinik Nusantara</div>
  </div>

  <!-- DASHBOARD -->
  <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">
    <i class="bi bi-speedometer2"></i><span>Dashboard</span>
  </a>

  <hr style="border-color: rgba(255,255,255,0.1)">


  <!-- ====================== PENDAFTARAN ====================== -->
  <a data-bs-toggle="collapse" href="#menuPendaftaran" role="button">
    <i class="bi bi-journal-medical"></i><span>Pendaftaran</span>
  </a>

  <div class="collapse ms-4" id="menuPendaftaran">
    <a href="{{ route('pendaftaran.create') }}" class="d-block mb-1">
      <i class="bi bi-search"></i> <span>Cari Pasien Lama</span>
    </a>
    <a href="{{ route('pasien.create') }}" class="d-block mb-1">
      <i class="bi bi-person-plus"></i> <span>Pasien Baru</span>
    </a>
  </div>

  <hr style="border-color: rgba(255,255,255,0.1)">


  <!-- ====================== POLIKLINIK ====================== -->
  <a data-bs-toggle="collapse" href="#menuPoliklinik" role="button">
    <i class="bi bi-hospital"></i><span>Poliklinik</span>
  </a>

  <div class="collapse ms-4" id="menuPoliklinik">
    <a href="{{ route('poliklinik.poli_umum') }}" class="d-block mb-1">
      <i class="bi bi-clipboard2-pulse"></i> <span>Poli Umum</span>
    </a>
    <a href="{{ route('poliklinik.poli_gigi') }}" class="d-block mb-1">
      <i class="bi bi-emoji-smile"></i> <span>Poli Gigi</span>
    </a>
    <a href="{{ route('poliklinik.poli_kandungan') }}" class="d-block mb-1">
      <i class="bi bi-gender-female"></i> <span>Poli Kandungan</span>
    </a>
  </div>

  <hr style="border-color: rgba(255,255,255,0.1)">


  <!-- ====================== MASTER DATA ====================== -->
  <a data-bs-toggle="collapse" href="#menuMaster" role="button">
    <i class="bi bi-grid"></i><span>Master Data</span>
  </a>

  <div class="collapse ms-4" id="menuMaster">
    <a href="{{ route('master.jadwal_dokter') }}" class="d-block mb-1">
      <i class="bi bi-person-vcard"></i> <span>Dokter</span>
    </a>

    <a href="{{ route('master.jadwal_poli') }}" class="d-block mb-1">
      <i class="bi bi-calendar2-week"></i> <span>Jadwal Dokter</span>
    </a>

    <a href="{{ route('master.data_tindakan') }}" class="d-block mb-1">
      <i class="bi bi-tools"></i> <span>Tindakan</span>
    </a>

    <a href="{{ route('master.data_diagnosa') }}" class="d-block mb-1">
      <i class="bi bi-journal-text"></i> <span>Diagnosa</span>
    </a>

    <a href="{{ route('pasien.index') }}" class="d-block mb-1">
      <i class="bi bi-people"></i> <span>Data Pasien</span>
    </a>
  </div>

  <hr style="border-color: rgba(255,255,255,0.1)">


  <!-- ====================== GUDANG OBAT ====================== -->
  <a data-bs-toggle="collapse" href="#menuGudangObat" role="button">
    <i class="bi bi-box-seam"></i><span>Gudang Obat</span>
  </a>

  <div class="collapse ms-4" id="menuGudangObat">
    <a href="{{ route('gudang_obat.apotik') }}" class="d-block mb-1">
      <i class="bi bi-capsule"></i> <span>Apotik</span>
    </a>

    <a href="{{ route('gudang_obat.apotik_retail') }}" class="d-block mb-1">
      <i class="bi bi-bag"></i> <span>Apotik Retail</span>
    </a>

    <a href="{{ route('gudang_obat.farmasi') }}" class="d-block mb-1">
      <i class="bi bi-prescription2"></i> <span>Farmasi</span>
    </a>

    <a href="{{ route('gudang_obat.master_obat') }}" class="d-block mb-1">
      <i class="bi bi-archive"></i> <span>Master Obat</span>
    </a>
  </div>

</div>

<!-- Toggle Button -->
<button id="toggle" class="toggle-btn">
  <i class="bi bi-list"></i>
</button>


<!-- ======================= CONTENT ======================= -->
<div class="content">
  @if(session('success'))
    <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
  @endif

  @yield('content')
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
  const sidebar = document.getElementById('sidebar');
  const toggle = document.getElementById('toggle');

  toggle.addEventListener('click', () => {
    sidebar.classList.toggle('collapsed');
    toggle.classList.toggle('collapsed');
  });
</script>

</body>
</html>
