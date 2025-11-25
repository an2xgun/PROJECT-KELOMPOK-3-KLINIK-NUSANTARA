<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🏥 Sistem Pendaftaran Pasien</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: linear-gradient(135deg, #3b8cf681, #3382ea25);
            font-family: 'Poppins', sans-serif;
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 240px;
            background-color: #3b8cf694;
            color: #995685ff;
            display: flex;
            flex-direction: column;
            padding: 1.5rem 1rem;
            position: fixed;
            height: 100vh;
        }
        .sidebar h2 {
            font-weight: 600;
            font-size: 1.4rem;
            text-align: center;
            margin-bottom: 2rem;
        }
        .sidebar a {
            color: #e1cbd5ff;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
            border-radius: 10px;
            transition: all 0.2s;
            margin-bottom: 0.5rem;
        }
        .sidebar a:hover,
        .sidebar a.active {
            background-color: #3382ea59;
            color: #fff;
        }
        .content {
            margin-left: 260px;
            padding: 2rem;
            width: 100%;
        }
        .btn-primary {
            background-color: #3382ea59;
            border: none;
        }
        .btn-primary:hover {
            background-color: #3382ea59;
        }
    </style>
</head>

<body>
    <div class="sidebar">
         <div class="sidebar-header text-center mb-4">
            <img src="https://i.pinimg.com/736x/58/c2/53/58c253f9dbde6c2ed7f74eedc4ddc7a2.jpg" alt="Logo Klinik" width="80" class="rounded-circle mb-2">
        <h2>🩺 Pendaftaran Klinik </h2>
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">🏠 Dashboard</a>
        <a href="{{ route('master') }}" class="{{ request()->routeIs('master') ? 'active' : '' }}">📋 Data Master</a>
        <a href="{{ route('pasien.baru') }}" class="{{ request()->routeIs('pasien.baru') ? 'active' : '' }}">➕ Pasien Baru</a>
        <a href="{{ route('pasien.lama') }}" class="{{ request()->routeIs('pasien.lama') ? 'active' : '' }}">🔍 Pasien Lama</a>
        <hr style="border-color: #694758ff;">
        <a href="{{ route('logout') }}">🚪 Logout</a>
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
