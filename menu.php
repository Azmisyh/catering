<?php
require_once 'config.php';

// Filter
$kategori_filter = isset($_GET['kategori']) ? ($_GET['kategori']) : '';
$search = isset($_GET['search']) ? ($_GET['search']) : '';
$sort = isset($_GET['sort']) ? ($_GET['sort']) : 'terbaru';

// Query menu
$query = "SELECT m.*, k.nama_kategori FROM menu m 
          LEFT JOIN kategori k ON m.kategori_id = k.id 
          WHERE m.status = 'tersedia'";

if ($kategori_filter) {
    $query .= " AND m.kategori_id = '$kategori_filter'";
}

if ($search) {
    $query .= " AND (m.nama_menu LIKE '%$search%' OR m.deskripsi LIKE '%$search%')";
}

switch ($sort) {
    case 'termurah':
        $query .= " ORDER BY m.harga ASC";
        break;
    case 'termahal':
        $query .= " ORDER BY m.harga DESC";
        break;
    case 'nama':
        $query .= " ORDER BY m.nama_menu ASC";
        break;
    default:
        $query .= " ORDER BY m.created_at DESC";
}

$result_menu = mysqli_query($conn, $query);

// Ambil kategori untuk filter
$query_kategori = "SELECT * FROM kategori ORDER BY nama_kategori";
$result_kategori = mysqli_query($conn, $query_kategori);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - Catering Delicious</title>
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
        .filter-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        .menu-card {
            transition: transform 0.3s;
            height: 100%;
        }
        .menu-card:hover {
            transform: translateY(-10px);
        }
        .price-tag {
            font-size: 1.3rem;
            color: var(--primary-color);
            font-weight: bold;
        }
        .btn-cart {
            background: var(--primary-color);
            border: none;
            color: white;
        }
        .btn-cart:hover {
            background: var(--secondary-color);
            color: white;
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
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link active" href="menu.php">Menu</a></li>
                    <li class="nav-item"><a class="nav-link" href="paket.php">Paket</a></li>
                    <?php if (is_logged_in()): ?>
                        <li class="nav-item"><a class="nav-link" href="cart.php">
                            <i class="fas fa-shopping-cart"></i> Keranjang
                            <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                                <span class="badge bg-danger"><?php echo count($_SESSION['cart']); ?></span>
                            <?php endif; ?>
                        </a></li>
                        <li class="nav-item"><a class="nav-link" href="pesanan.php">Pesanan Saya</a></li>
                        <li class="nav-item"><a class="nav-link" href="chat.php"><i class="fas fa-comments"></i> Chat</a></li>
                        <?php if (is_admin()): ?>
                            <li class="nav-item"><a class="nav-link" href="admin/">Dashboard</a></li>
                        <?php endif; ?>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <h2 class="text-center mb-4">Menu Catering Kami</h2>
        
        <!-- Filter Section -->
        <div class="filter-section">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Cari Menu</label>
                    <input type="text" class="form-control" name="search" placeholder="Cari nama menu..." value="<?php echo $search; ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kategori</label>
                    <select class="form-select" name="kategori">
                        <option value="">Semua Kategori</option>
                        <?php while($kat = mysqli_fetch_assoc($result_kategori)): ?>
                            <option value="<?php echo $kat['id']; ?>" <?php echo $kategori_filter == $kat['id'] ? 'selected' : ''; ?>>
                                <?php echo $kat['nama_kategori']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Urutkan</label>
                    <select class="form-select" name="sort">
                        <option value="terbaru" <?php echo $sort == 'terbaru' ? 'selected' : ''; ?>>Terbaru</option>
                        <option value="termurah" <?php echo $sort == 'termurah' ? 'selected' : ''; ?>>Termurah</option>
                        <option value="termahal" <?php echo $sort == 'termahal' ? 'selected' : ''; ?>>Termahal</option>
                        <option value="nama" <?php echo $sort == 'nama' ? 'selected' : ''; ?>>Nama A-Z</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Menu Grid -->
        <div class="row">
            <?php if (mysqli_num_rows($result_menu) > 0): ?>
                <?php while($menu = mysqli_fetch_assoc($result_menu)): ?>
                <div class="col-md-4 col-lg-3 mb-4">
                    <div class="card menu-card shadow-sm">
                        <img src="<?php echo $menu['gambar'] ? $menu['gambar'] : 'https://via.placeholder.com/300x200?text=Menu'; ?>" 
                             class="card-img-top" alt="<?php echo $menu['nama_menu']; ?>" 
                             style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <span class="badge bg-secondary mb-2"><?php echo $menu['nama_kategori']; ?></span>
                            <h5 class="card-title"><?php echo $menu['nama_menu']; ?></h5>
                            <p class="card-text text-muted small"><?php echo substr($menu['deskripsi'], 0, 60); ?>...</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="price-tag"><?php echo format_rupiah($menu['harga']); ?></span>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0">
                            <?php if (is_logged_in()): ?>
                            <form method="POST" action="cart_action.php">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="type" value="menu">
                                <input type="hidden" name="id" value="<?php echo $menu['id']; ?>">
                                <div class="input-group mb-2">
                                    <input type="number" class="form-control" name="quantity" value="1" min="1" required>
                                    <button type="submit" class="btn btn-cart">
                                        <i class="fas fa-cart-plus"></i> Tambah
                                    </button>
                                </div>
                            </form>
                            <?php else: ?>
                            <a href="login.php" class="btn btn-primary w-100">Login untuk Pesan</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle"></i> Tidak ada menu yang ditemukan.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>