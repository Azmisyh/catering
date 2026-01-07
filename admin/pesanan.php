<?php
require_once 'config.php';



// Update status pesanan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $pesanan_id = ($_POST['pesanan_id']);
    $status = ($_POST['status']);
    
    $query = "UPDATE pesanan SET status = '$status' WHERE id = '$pesanan_id'";
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = 'Status pesanan berhasil diupdate!';
    }
}

// Filter
$status_filter = isset($_GET['status']) ? ($_GET['status']) : '';
$search = isset($_GET['search']) ? ($_GET['search']) : '';

// Query pesanan
$query = "SELECT p.*, u.nama, u.email, u.telepon FROM pesanan p 
          LEFT JOIN users u ON p.user_id = u.id 
          WHERE 1=1";

if ($status_filter) {
    $query .= " AND p.status = '$status_filter'";
}

if ($search) {
    $query .= " AND (p.kode_pesanan LIKE '%$search%' OR u.nama LIKE '%$search%' OR u.email LIKE '%$search%')";
}

$query .= " ORDER BY p.created_at DESC";
$result = mysqli_query($conn, $query);

// Hitung total per status
$query_stats = "SELECT status, COUNT(*) as total FROM pesanan GROUP BY status";
$result_stats = mysqli_query($conn, $query_stats);
$stats = [];
while ($row = mysqli_fetch_assoc($result_stats)) {
    $stats[$row['status']] = $row['total'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesanan - Admin</title>
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
    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="mb-4"><i class="fas fa-utensils"></i> Admin Panel</h4>
        <nav class="nav flex-column">
            <a class="nav-link" href="index.php"><i class="fas fa-home"></i> Dashboard</a>
            <a class="nav-link active" href="pesanan.php"><i class="fas fa-shopping-cart"></i> Kelola Pesanan</a>
            <a class="nav-link" href="menu.php"><i class="fas fa-utensils"></i> Kelola Menu</a>
            <a class="nav-link" href="paket.php"><i class="fas fa-box"></i> Kelola Paket</a>
            <a class="nav-link" href="kategori.php"><i class="fas fa-tags"></i> Kategori</a>
            <a class="nav-link" href="customer.php"><i class="fas fa-users"></i> Customer</a>
            <a class="nav-link" href="../chat.php"><i class="fas fa-comments"></i> Chat</a>
            <a class="nav-link" href="laporan.php"><i class="fas fa-chart-bar"></i> Laporan</a>
            <hr class="bg-white">
            <a class="nav-link" href="../index.php"><i class="fas fa-globe"></i> Lihat Website</a>
            <a class="nav-link" href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2 class="mb-4">Kelola Pesanan</h2>

        <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Status Summary -->
        <div class="row mb-4">
            <div class="col-md-2">
                <div class="card text-center">
                    <div class="card-body">
                        <h4 class="text-warning"><?php echo $stats['pending'] ?? 0; ?></h4>
                        <small>Pending</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center">
                    <div class="card-body">
                        <h4 class="text-info"><?php echo $stats['dikonfirmasi'] ?? 0; ?></h4>
                        <small>Dikonfirmasi</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center">
                    <div class="card-body">
                        <h4 class="text-primary"><?php echo $stats['diproses'] ?? 0; ?></h4>
                        <small>Diproses</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center">
                    <div class="card-body">
                        <h4 class="text-success"><?php echo $stats['selesai'] ?? 0; ?></h4>
                        <small>Selesai</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center">
                    <div class="card-body">
                        <h4 class="text-danger"><?php echo $stats['dibatalkan'] ?? 0; ?></h4>
                        <small>Dibatalkan</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="search" 
                               placeholder="Cari kode pesanan, nama, atau email..." value="<?php echo $search; ?>">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="status">
                            <option value="">Semua Status</option>
                            <option value="pending" <?php echo $status_filter == 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="dikonfirmasi" <?php echo $status_filter == 'dikonfirmasi' ? 'selected' : ''; ?>>Dikonfirmasi</option>
                            <option value="diproses" <?php echo $status_filter == 'diproses' ? 'selected' : ''; ?>>Diproses</option>
                            <option value="selesai" <?php echo $status_filter == 'selesai' ? 'selected' : ''; ?>>Selesai</option>
                            <option value="dibatalkan" <?php echo $status_filter == 'dibatalkan' ? 'selected' : ''; ?>>Dibatalkan</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="pesanan.php" class="btn btn-secondary w-100">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel Pesanan -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Kode Pesanan</th>
                                <th>Customer</th>
                                <th>Tanggal Acara</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Tanggal Pesan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result) > 0): ?>
                                <?php while($pesanan = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><strong><?php echo $pesanan['kode_pesanan']; ?></strong></td>
                                    <td>
                                        <?php echo $pesanan['nama']; ?><br>
                                        <small class="text-muted"><?php echo $pesanan['telepon']; ?></small>
                                    </td>
                                    <td>
                                        <?php echo date('d M Y', strtotime($pesanan['tanggal_acara'])); ?><br>
                                        <small class="text-muted"><?php echo date('H:i', strtotime($pesanan['waktu_acara'])); ?> WIB</small>
                                    </td>
                                    <td><strong><?php echo format_rupiah($pesanan['total_harga']); ?></strong></td>
                                    <td>
                                        <?php
                                        $badge_class = [
                                            'pending' => 'bg-warning text-dark',
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
                                    <td><?php echo date('d M Y', strtotime($pesanan['created_at'])); ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" 
                                                data-bs-toggle="modal" data-bs-target="#modal<?php echo $pesanan['id']; ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="pesanan_detail.php?id=<?php echo $pesanan['id']; ?>" 
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>

                                <!-- Modal Update Status -->
                                <div class="modal fade" id="modal<?php echo $pesanan['id']; ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Update Status Pesanan</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST">
                                                <div class="modal-body">
                                                    <input type="hidden" name="pesanan_id" value="<?php echo $pesanan['id']; ?>">
                                                    <p><strong>Kode:</strong> <?php echo $pesanan['kode_pesanan']; ?></p>
                                                    <p><strong>Customer:</strong> <?php echo $pesanan['nama']; ?></p>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label">Status Pesanan</label>
                                                        <select class="form-select" name="status" required>
                                                            <option value="pending" <?php echo $pesanan['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                            <option value="dikonfirmasi" <?php echo $pesanan['status'] == 'dikonfirmasi' ? 'selected' : ''; ?>>Dikonfirmasi</option>
                                                            <option value="diproses" <?php echo $pesanan['status'] == 'diproses' ? 'selected' : ''; ?>>Diproses</option>
                                                            <option value="selesai" <?php echo $pesanan['status'] == 'selesai' ? 'selected' : ''; ?>>Selesai</option>
                                                            <option value="dibatalkan" <?php echo $pesanan['status'] == 'dibatalkan' ? 'selected' : ''; ?>>Dibatalkan</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" name="update_status" class="btn btn-primary">Update Status</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data pesanan</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>