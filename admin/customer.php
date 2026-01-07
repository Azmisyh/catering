<?php
// admin/customer.php
require_once '../config.php';



$query = "SELECT u.*, 
          (SELECT COUNT(*) FROM pesanan WHERE user_id = u.id) as total_pesanan,
          (SELECT SUM(total_harga) FROM pesanan WHERE user_id = u.id AND status IN ('dikonfirmasi', 'diproses', 'selesai')) as total_belanja
          FROM users u 
          WHERE role = 'customer' 
          ORDER BY u.created_at DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Customer - Admin</title>
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
            <a class="nav-link active" href="customer.php"><i class="fas fa-users"></i> Customer</a>
            <a class="nav-link" href="../chat.php"><i class="fas fa-comments"></i> Chat</a>
            <a class="nav-link" href="laporan.php"><i class="fas fa-chart-bar"></i> Laporan</a>
            <hr class="bg-white">
            <a class="nav-link" href="../index.php"><i class="fas fa-globe"></i> Lihat Website</a>
            <a class="nav-link" href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
    </div>

    <div class="main-content">
        <h2 class="mb-4">Data Customer</h2>

        <div class="card">
            <div class="card-body">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Total Pesanan</th>
                            <th>Total Belanja</th>
                            <th>Terdaftar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        while($cust = mysqli_fetch_assoc($result)): 
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><strong><?php echo $cust['nama']; ?></strong></td>
                            <td><?php echo $cust['email']; ?></td>
                            <td><?php echo $cust['telepon']; ?></td>
                            <td><span class="badge bg-primary"><?php echo $cust['total_pesanan']; ?> pesanan</span></td>
                            <td><strong><?php echo format_rupiah($cust['total_belanja'] ?? 0); ?></strong></td>
                            <td><?php echo date('d M Y', strtotime($cust['created_at'])); ?></td>
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
