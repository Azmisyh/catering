<?php
// admin/laporan.php
require_once 'config.php';



$bulan = isset($_GET['bulan']) ? clean_input($_GET['bulan']) : date('Y-m');

// Laporan pendapatan bulan ini
$query_pendapatan = "SELECT 
                     COUNT(*) as total_pesanan,
                     SUM(total_harga) as total_pendapatan,
                     SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                     SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) as selesai
                     FROM pesanan 
                     WHERE DATE_FORMAT(created_at, '%Y-%m') = '$bulan'";
$laporan = mysqli_fetch_assoc(mysqli_query($conn, $query_pendapatan));

// Top menu
$query_top = "SELECT pd.nama_item, SUM(pd.jumlah) as total_terjual, SUM(pd.subtotal) as pendapatan
              FROM pesanan_detail pd
              LEFT JOIN pesanan p ON pd.pesanan_id = p.id
              WHERE DATE_FORMAT(p.created_at, '%Y-%m') = '$bulan'
              GROUP BY pd.nama_item
              ORDER BY total_terjual DESC
              LIMIT 5";
$result_top = mysqli_query($conn, $query_top);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #FF6B35;
            --secondary-color: #F7931E;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background: linear-gradient(180deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 20px;
            overflow-y: auto;
        }
        .sidebar .nav-link {
            color: white;
            padding: 12px 15px;
            margin-bottom: 5px;
            border-radius: 8px;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255,255,255,0.2);
        }
        .main-content {
            margin-left: 250px;
            padding: 30px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4 class="mb-4"><i class="fas fa-utensils"></i> Admin Panel</h4>
        <nav class="nav flex-column">
            <a class="nav-link" href="index.php"><i class="fas fa-home"></i> Dashboard</a>
            <a class="nav-link" href="pesanan.php"><i class="fas fa-shopping-cart"></i> Kelola Pesanan</a>
            <a class="nav-link" href="menu.php"><i class="fas fa-utensils"></i> Kelola Menu</a>
            <a class="nav-link" href="paket.php"><i class="fas fa-box"></i> Kelola Paket</a>
            <a class="nav-link" href="kategori.php"><i class="fas fa-tags"></i> Kategori</a>
            <a class="nav-link" href="customer.php"><i class="fas fa-users"></i> Customer</a>
            <a class="nav-link" href="../chat.php"><i class="fas fa-comments"></i> Chat</a>
            <a class="nav-link active" href="laporan.php"><i class="fas fa-chart-bar"></i> Laporan</a>
            <hr class="bg-white">
            <a class="nav-link" href="../index.php"><i class="fas fa-globe"></i> Lihat Website</a>
            <a class="nav-link" href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
    </div>

    <div class="main-content">
        <h2 class="mb-4">Laporan Penjualan</h2>

        <!-- Filter Bulan -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Pilih Bulan</label>
                        <input type="month" class="form-control" name="bulan" value="<?php echo $bulan; ?>">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistik -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-primary"><?php echo $laporan['total_pesanan']; ?></h3>
                        <p class="mb-0">Total Pesanan</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-success"><?php echo format_rupiah($laporan['total_pendapatan'] ?? 0); ?></h3>
                        <p class="mb-0">Total Pendapatan</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-warning"><?php echo $laporan['pending']; ?></h3>
                        <p class="mb-0">Pending</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-info"><?php echo $laporan['selesai']; ?></h3>
                        <p class="mb-0">Selesai</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Menu -->
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Menu Terlaris Bulan Ini</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Menu</th>
                            <th>Total Terjual</th>
                            <th>Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        while($top = mysqli_fetch_assoc($result_top)): 
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><strong><?php echo $top['nama_item']; ?></strong></td>
                            <td><?php echo $top['total_terjual']; ?> porsi</td>
                            <td><strong><?php echo format_rupiah($top['pendapatan']); ?></strong></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>