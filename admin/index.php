<?php
require_once '../config.php';

if (!is_admin()) {
    redirect('../index.php');
}


// Statistik
$total_pesanan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pesanan"))['total'];
$total_pending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pesanan WHERE status = 'pending'"))['total'];
$total_customer = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role = 'customer'"))['total'];
$total_menu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM menu"))['total'];

// Total pendapatan
$pendapatan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_harga) as total FROM pesanan WHERE status IN ('dikonfirmasi', 'diproses', 'selesai')"));
$total_pendapatan = $pendapatan['total'] ? $pendapatan['total'] : 0;

// Pesanan terbaru
$query_pesanan = "SELECT p.*, u.nama, u.email FROM pesanan p 
                  LEFT JOIN users u ON p.user_id = u.id 
                  ORDER BY p.created_at DESC LIMIT 10";
$result_pesanan = mysqli_query($conn, $query_pesanan);

// Pesan chat belum dibaca
$unread_chat = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM chat WHERE pengirim = 'user' AND is_read = 0"))['total'];

// Data chart - Pendapatan per bulan
$query_chart = "SELECT DATE_FORMAT(created_at, '%Y-%m') as bulan, SUM(total_harga) as total
                FROM pesanan 
                WHERE status IN ('dikonfirmasi', 'diproses', 'selesai')
                AND created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                ORDER BY bulan ASC";
$result_chart = mysqli_query($conn, $query_chart);

$chart_labels = [];
$chart_data = [];
while($row = mysqli_fetch_assoc($result_chart)) {
    $chart_labels[] = date('M Y', strtotime($row['bulan']));
    $chart_data[] = $row['total'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Catering Delicious</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255,255,255,0.2);
        }
        .main-content {
            margin-left: 250px;
            padding: 30px;
        }
        .stat-card {
            border-left: 4px solid;
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-card.primary { border-color: var(--primary-color); }
        .stat-card.success { border-color: #28a745; }
        .stat-card.warning { border-color: #ffc107; }
        .stat-card.info { border-color: #17a2b8; }
        
        .status-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="mb-4"><i class="fas fa-utensils"></i> Admin Panel</h4>
        <nav class="nav flex-column">
            <a class="nav-link active" href="index.php">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a class="nav-link" href="pesanan.php">
                <i class="fas fa-shopping-cart"></i> Kelola Pesanan
                <?php if ($total_pending > 0): ?>
                <span class="badge bg-danger"><?php echo $total_pending; ?></span>
                <?php endif; ?>
            </a>
            <a class="nav-link" href="menu.php">
                <i class="fas fa-utensils"></i> Kelola Menu
            </a>
            <a class="nav-link" href="paket.php">
                <i class="fas fa-box"></i> Kelola Paket
            </a>
            <a class="nav-link" href="kategori.php">
                <i class="fas fa-tags"></i> Kategori
            </a>
            <a class="nav-link" href="customer.php">
                <i class="fas fa-users"></i> Customer
            </a>
            <a class="nav-link" href="../chat.php">
                <i class="fas fa-comments"></i> Chat
                <?php if ($unread_chat > 0): ?>
                <span class="badge bg-danger"><?php echo $unread_chat; ?></span>
                <?php endif; ?>
            </a>
            <a class="nav-link" href="laporan.php">
                <i class="fas fa-chart-bar"></i> Laporan
            </a>
            <hr class="bg-white">
            <a class="nav-link" href="../index.php">
                <i class="fas fa-globe"></i> Lihat Website
            </a>
            <a class="nav-link" href="../logout.php">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2 class="mb-4">Dashboard Admin</h2>
        
        <!-- Statistik Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card stat-card primary shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-2">Total Pesanan</h6>
                                <h3 class="mb-0"><?php echo $total_pesanan; ?></h3>
                            </div>
                            <div class="text-primary">
                                <i class="fas fa-shopping-cart fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-3">
                <div class="card stat-card warning shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-2">Pesanan Pending</h6>
                                <h3 class="mb-0"><?php echo $total_pending; ?></h3>
                            </div>
                            <div class="text-warning">
                                <i class="fas fa-clock fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-3">
                <div class="card stat-card success shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-2">Total Customer</h6>
                                <h3 class="mb-0"><?php echo $total_customer; ?></h3>
                            </div>
                            <div class="text-success">
                                <i class="fas fa-users fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-3">
                <div class="card stat-card info shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-2">Total Menu</h6>
                                <h3 class="mb-0"><?php echo $total_menu; ?></h3>
                            </div>
                            <div class="text-info">
                                <i class="fas fa-utensils fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pendapatan -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0"><i class="fas fa-dollar-sign"></i> Total Pendapatan</h5>
                        </div>
                        <h2 class="text-success"><?php echo format_rupiah($total_pendapatan); ?></h2>
                        <p class="text-muted mb-0">Dari pesanan yang dikonfirmasi & selesai</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Pendapatan -->
        <?php if (count($chart_data) > 0): ?>
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-chart-line"></i> Grafik Pendapatan 6 Bulan Terakhir</h5>
                        <canvas id="chartPendapatan" height="80"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Pesanan Terbaru -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-list"></i> Pesanan Terbaru</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Kode Pesanan</th>
                                <th>Customer</th>
                                <th>Tanggal Acara</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($pesanan = mysqli_fetch_assoc($result_pesanan)): ?>
                            <tr>
                                <td><strong><?php echo $pesanan['kode_pesanan']; ?></strong></td>
                                <td>
                                    <?php echo $pesanan['nama']; ?><br>
                                    <small class="text-muted"><?php echo $pesanan['email']; ?></small>
                                </td>
                                <td><?php echo date('d M Y', strtotime($pesanan['tanggal_acara'])); ?></td>
                                <td><strong><?php echo format_rupiah($pesanan['total_harga']); ?></strong></td>
                                <td>
                                    <?php
                                    $badge_class = [
                                        'pending' => 'bg-warning',
                                        'dikonfirmasi' => 'bg-info',
                                        'diproses' => 'bg-primary',
                                        'selesai' => 'bg-success',
                                        'dibatalkan' => 'bg-danger'
                                    ];
                                    ?>
                                    <span class="badge <?php echo $badge_class[$pesanan['status']]; ?>">
                                        <?php echo strtoupper($pesanan['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="pesanan_detail.php?id=<?php echo $pesanan['id']; ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white text-center">
                <a href="pesanan.php" class="btn btn-primary">Lihat Semua Pesanan</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php if (count($chart_data) > 0): ?>
    <script>
        const ctx = document.getElementById('chartPendapatan').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($chart_labels); ?>,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: <?php echo json_encode($chart_data); ?>,
                    borderColor: '#FF6B35',
                    backgroundColor: 'rgba(255, 107, 53, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    </script>
    <?php endif; ?>
</body>
</html>