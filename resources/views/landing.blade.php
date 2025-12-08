<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klinik Nusantara - Layanan Kesehatan Terpercaya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #0d6efd;
            --secondary: #6c757d;
            --success: #198754;
            --info: #0dcaf0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        /* Navbar */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary) !important;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary), var(--info));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .nav-link {
            color: #333 !important;
            font-weight: 500;
            transition: all 0.3s ease;
            margin: 0 10px;
        }

        .nav-link:hover {
            color: var(--primary) !important;
            transform: translateY(-2px);
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -150px;
            right: -100px;
            animation: float 6s ease-in-out infinite;
        }

        .hero::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            bottom: -50px;
            left: -50px;
            animation: float 8s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(20px); }
        }

        .hero-content {
            text-align: center;
            z-index: 1;
            animation: slideInUp 0.8s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .clinic-logo {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, var(--primary), var(--info));
            border-radius: 20px;
            margin: 0 auto 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
            color: white;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
            animation: scaleIn 0.6s ease-out;
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .hero h1 {
            color: white;
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            line-height: 1.2;
        }

        .hero p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.25rem;
            margin-bottom: 30px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-group-custom {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 40px;
        }

        .btn-login {
            padding: 12px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            background: white;
            color: var(--primary);
            border: none;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            text-decoration: none;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
            color: var(--primary);
        }

        .btn-info-page {
            padding: 12px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            background: transparent;
            color: white;
            border: 2px solid white;
            border-radius: 50px;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-info-page:hover {
            background: white;
            color: var(--primary);
            transform: translateY(-3px);
        }

        /* Features Section */
        .features {
            padding: 80px 0;
            background: white;
        }

        .feature-card {
            padding: 30px;
            border-radius: 15px;
            background: #f8f9fa;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
            background: white;
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary), var(--info));
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            margin: 0 auto 20px;
        }

        .feature-card h3 {
            color: #333;
            margin-bottom: 15px;
            font-weight: 600;
            font-size: 1.3rem;
        }

        .feature-card p {
            color: #666;
            line-height: 1.6;
        }

        /* Services Section */
        .services {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .services h2 {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 50px;
            color: #333;
        }

        .service-item {
            padding: 20px;
            background: white;
            border-radius: 10px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
            transition: all 0.3s ease;
            border-left: 4px solid var(--primary);
        }

        .service-item:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transform: translateX(5px);
        }

        .service-icon {
            font-size: 2rem;
            color: var(--primary);
            min-width: 50px;
            text-align: center;
        }

        .service-text h4 {
            color: #333;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .service-text p {
            color: #666;
            font-size: 0.95rem;
            margin: 0;
        }

        /* Footer */
        footer {
            background: #222;
            color: white;
            padding: 30px 0;
            text-align: center;
        }

        footer p {
            margin: 0;
            color: rgba(255, 255, 255, 0.7);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }

            .hero p {
                font-size: 1rem;
            }

            .btn-group-custom {
                flex-direction: column;
                align-items: center;
            }

            .btn-login, .btn-info-page {
                width: 100%;
                max-width: 300px;
            }

            .navbar-brand {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="#home">
                <div class="logo-icon">
                    <i class="bi bi-hospital"></i>
                </div>
                <span>Klinik Nusantara</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Fitur</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#services">Layanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-content">
            <div class="clinic-logo">
                <i class="bi bi-hospital"></i>
            </div>
            <h1>Klinik Nusantara</h1>
            <p>Layanan kesehatan terpercaya dengan teknologi terkini dan tenaga profesional yang berpengalaman</p>
            <div class="btn-group-custom">
                <a href="{{ route('login') }}" class="btn-login">
                    <i class="bi bi-box-arrow-in-right"></i> Masuk Sistem
                </a>
                <a href="#features" class="btn-info-page">
                    <i class="bi bi-info-circle"></i> Pelajari Selengkapnya
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <h2 style="text-align: center; font-size: 2.5rem; font-weight: 700; margin-bottom: 50px; color: #333;">
                Fitur Unggulan
            </h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                        <h3>Pendaftaran Online</h3>
                        <p>Daftar pasien dengan mudah dan cepat melalui sistem online kami yang user-friendly</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-file-earmark-medical"></i>
                        </div>
                        <h3>Rekam Medis Digital</h3>
                        <p>Simpan dan akses rekam medis pasien dengan aman dan tersentralisasi</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-capsule"></i>
                        </div>
                        <h3>Manajemen Resep</h3>
                        <p>Kelola resep obat dan distribusi farmasi dengan sistem terintegrasi</p>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <h3>Laporan & Analitik</h3>
                        <p>Dapatkan insight mendalam tentang kinerja klinik dengan dashboard analitik real-time</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h3>Keamanan Data</h3>
                        <p>Perlindungan data pasien dengan enkripsi tingkat enterprise</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-people"></i>
                        </div>
                        <h3>Multi-Role Access</h3>
                        <p>Sistem manajemen akses berbasis peran untuk keamanan dan efisiensi operasional</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services" id="services">
        <div class="container">
            <h2>Layanan Kami</h2>
            <div class="row">
                <div class="col-lg-6">
                    <div class="service-item">
                        <div class="service-icon">
                            <i class="bi bi-stethoscope"></i>
                        </div>
                        <div class="service-text">
                            <h4>Pemeriksaan Umum</h4>
                            <p>Konsultasi dan pemeriksaan kesehatan umum dengan dokter berpengalaman</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="service-item">
                        <div class="service-icon">
                            <i class="bi bi-heart-pulse"></i>
                        </div>
                        <div class="service-text">
                            <h4>Poliklinik Gigi</h4>
                            <p>Layanan kesehatan gigi profesional dengan peralatan modern</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mt-3">
                    <div class="service-item">
                        <div class="service-icon">
                            <i class="bi bi-bandaid"></i>
                        </div>
                        <div class="service-text">
                            <h4>Poliklinik Kandungan</h4>
                            <p>Layanan kesehatan ibu dan anak dengan tenaga medis khusus</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mt-3">
                    <div class="service-item">
                        <div class="service-icon">
                            <i class="bi bi-pill"></i>
                        </div>
                        <div class="service-text">
                            <h4>Apotek & Farmasi</h4>
                            <p>Penyediaan obat-obatan berkualitas dengan harga terjangkau</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2025 Klinik Nusantara. Semua hak dilindungi. | Sistem Manajemen Klinik Terpadu</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Smooth scroll untuk links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href !== '#' && document.querySelector(href)) {
                    e.preventDefault();
                    document.querySelector(href).scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>
</html>
