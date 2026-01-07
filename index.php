<?php
// tentang.php - Halaman Tentang Kami
require_once 'config.php';

if (!is_logged_in()) {
    redirect('login.php');
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - Catering Delicious</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #FF6B35;
            --secondary-color: #F7931E;
        }
        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }
        .hero-about {
            background: linear-gradient(135deg, rgba(255,107,53,0.9), rgba(247,147,30,0.9));
            color: white;
            padding: 80px 0;
            text-align: center;
        }
        .team-card {
            transition: transform 0.3s;
        }
        .team-card:hover {
            transform: translateY(-10px);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="fas fa-utensils"></i> Catering Delicious
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="menu.php">Menu</a></li>
                    <li class="nav-item"><a class="nav-link" href="paket.php">Paket</a></li>
                    <?php if (is_logged_in()): ?>
                        <li class="nav-item"><a class="nav-link" href="cart.php">
                            <i class="fas fa-shopping-cart"></i> Keranjang
                        </a></li>
                        <li class="nav-item"><a class="nav-link" href="pesanan.php">Pesanan Saya</a></li>
                        <li class="nav-item"><a class="nav-link" href="chat.php"><i class="fas fa-comments"></i> Chat</a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="hero-about">
        <div class="container">
            <h1 class="display-4 mb-3">Tentang Catering Delicious</h1>
            <p class="lead">Melayani dengan sepenuh hati sejak 2010</p>
        </div>
    </section>

    <!-- Profil -->
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 mb-4">
                    <h2 class="mb-4">Profil Perusahaan</h2>
                    <p>Catering Delicious adalah penyedia layanan catering profesional yang telah berpengalaman lebih dari 10 tahun dalam industri kuliner. Kami berkomitmen untuk memberikan pengalaman kuliner terbaik untuk setiap acara Anda.</p>
                    <p>Dengan tim chef profesional dan bahan baku berkualitas premium, kami siap memenuhi kebutuhan catering untuk berbagai acara seperti pernikahan, seminar, gathering perusahaan, ulang tahun, dan acara spesial lainnya.</p>
                </div>
                <div class="col-md-6 mb-4">
                    <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=600" class="img-fluid rounded shadow" alt="About Us">
                </div>
            </div>
        </div>
    </section>

    <!-- Visi Misi -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body p-4">
                            <h3 class="text-primary mb-3"><i class="fas fa-eye"></i> Visi</h3>
                            <p>Menjadi penyedia layanan catering terpercaya dan terbaik di Indonesia dengan standar kualitas internasional.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body p-4">
                            <h3 class="text-primary mb-3"><i class="fas fa-bullseye"></i> Misi</h3>
                            <ul>
                                <li>Memberikan pelayanan terbaik kepada setiap customer</li>
                                <li>Menggunakan bahan baku berkualitas tinggi</li>
                                <li>Inovasi menu yang variatif dan lezat</li>
                                <li>Tepat waktu dalam setiap pengiriman</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Nilai -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Nilai-Nilai Kami</h2>
            <div class="row text-center">
                <div class="col-md-3 mb-4">
                    <div class="p-4">
                        <i class="fas fa-star fa-3x text-warning mb-3"></i>
                        <h5>Kualitas</h5>
                        <p>Standar kualitas tinggi dalam setiap hidangan</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="p-4">
                        <i class="fas fa-heart fa-3x text-danger mb-3"></i>
                        <h5>Kepuasan</h5>
                        <p>Kepuasan pelanggan adalah prioritas utama</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="p-4">
                        <i class="fas fa-shield-alt fa-3x text-success mb-3"></i>
                        <h5>Kepercayaan</h5>
                        <p>Membangun kepercayaan dengan transparansi</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="p-4">
                        <i class="fas fa-lightbulb fa-3x text-primary mb-3"></i>
                        <h5>Inovasi</h5>
                        <p>Terus berinovasi dalam menu dan pelayanan</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Kontak -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Hubungi Kami</h2>
            <div class="row">
                <div class="col-md-4 mb-4 text-center">
                    <i class="fas fa-map-marker-alt fa-3x text-primary mb-3"></i>
                    <h5>Alamat</h5>
                    <p>Jl. Raya Kuliner No. 123<br>Jakarta Selatan, 12345</p>
                </div>
                <div class="col-md-4 mb-4 text-center">
                    <i class="fas fa-phone fa-3x text-primary mb-3"></i>
                    <h5>Telepon</h5>
                    <p>0812-3456-7890<br>021-1234-5678</p>
                </div>
                <div class="col-md-4 mb-4 text-center">
                    <i class="fas fa-envelope fa-3x text-primary mb-3"></i>
                    <h5>Email</h5>
                    <p>info@cateringdelicious.com<br>order@cateringdelicious.com</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer style="background: #2C3E50; color: white; padding: 40px 0;">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5><i class="fas fa-utensils"></i> Catering Delicious</h5>
                    <p>Layanan catering terpercaya untuk berbagai acara Anda.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Link Cepat</h5>
                    <ul class="list-unstyled">
                        <li><a href="menu.php" class="text-white">Menu</a></li>
                        <li><a href="paket.php" class="text-white">Paket</a></li>
                        <li><a href="tentang.php" class="text-white">Tentang Kami</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Jam Operasional</h5>
                    <p>Senin - Sabtu: 08.00 - 20.00<br>Minggu: 09.00 - 17.00</p>
                </div>
            </div>
            <hr class="bg-white">
            <p class="text-center mb-0">&copy; 2024 Catering Delicious. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>