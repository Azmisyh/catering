<?php
require_once 'config.php';

if (!is_logged_in()) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];

// Ambil pesanan user
$query = "SELECT * FROM pesanan 
          WHERE user_id = '$user_id' 
          ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesanan Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .status-pending { background:#ffc107;color:#000;padding:5px 12px;border-radius:20px; }
        .status-diproses { background:#0d6efd;color:#fff;padding:5px 12px;border-radius:20px; }
        .status-selesai { background:#198754;color:#fff;padding:5px 12px;border-radius:20px; }
        .status-dibatalkan { background:#dc3545;color:#fff;padding:5px 12px;border-radius:20px; }

        .badge-pembayaran {
            background:#6f42c1;
            color:#fff;
            padding:6px 12px;
            border-radius:20px;
            font-size: 13px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">🍽 Catering Delicious</a>
        <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
</nav>

<div class="container my-5">
    <h3 class="mb-4"><i class="fas fa-list"></i> Pesanan Saya</h3>

    <?php if (mysqli_num_rows($result) == 0): ?>
        <div class="alert alert-info text-center">
            <h5>Belum ada pesanan</h5>
            <a href="menu.php" class="btn btn-primary mt-2">Pesan Sekarang</a>
        </div>
    <?php endif; ?>

    <?php while ($pesanan = mysqli_fetch_assoc($result)): ?>
        <div class="card mb-3 shadow-sm">
            <div class="card-body">

                <div class="row">
                    <div class="col-md-4">
                        <strong>Tanggal Pesan</strong><br>
                        <?= date('d M Y H:i', strtotime($pesanan['created_at'])) ?>
                    </div>
                    <div class="col-md-4">
                        <strong>Total</strong><br>
                        <?= format_rupiah($pesanan['total_harga']) ?>
                    </div>
                    <div class="col-md-4 text-end">
                        <span class="status-<?= $pesanan['status'] ?>">
                            <?= strtoupper($pesanan['status']) ?>
                        </span>
                    </div>
                </div>

                <hr>

                <!-- METODE PEMBAYARAN -->
                <p>
                    <strong>Metode Pembayaran:</strong><br>
                    <span class="badge-pembayaran">
                        <?= htmlspecialchars($pesanan['metode_pembayaran']) ?>
                    </span>
                </p>

                <!-- BUKTI PEMBAYARAN -->
                <?php if (!empty($pesanan['bukti_pembayaran'])): ?>
                    <p>
                        <strong>Bukti Pembayaran:</strong><br>
                        <a href="uploads/bukti/<?= $pesanan['bukti_pembayaran'] ?>" 
                           target="_blank" 
                           class="btn btn-outline-success btn-sm mt-1">
                            <i class="fas fa-image"></i> Lihat Bukti
                        </a>
                    </p>
                <?php else: ?>
                    <p>
                        <strong>Bukti Pembayaran:</strong><br>
                        <span class="text-muted">Belum ada bukti</span>
                    </p>
                <?php endif; ?>

                <p>
                    <strong>Alamat Pengiriman:</strong><br>
                    <?= nl2br(htmlspecialchars($pesanan['alamat_pengiriman'])) ?>
                </p>

                <?php if ($pesanan['catatan']): ?>
                    <p>
                        <strong>Catatan:</strong><br>
                        <?= nl2br(htmlspecialchars($pesanan['catatan'])) ?>
                    </p>
                <?php endif; ?>

                <h6 class="mt-3">Detail Pesanan</h6>
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Menu</th>
                            <th class="text-end">Harga</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $pesanan_id = $pesanan['id'];
                        $q_detail = "SELECT d.*, m.nama_menu 
                                     FROM pesanan_detail d 
                                     JOIN menu m ON d.menu_id = m.id 
                                     WHERE d.pesanan_id = '$pesanan_id'";
                        $r_detail = mysqli_query($conn, $q_detail);

                        while ($d = mysqli_fetch_assoc($r_detail)):
                            $subtotal = $d['harga'] * $d['jumlah'];
                        ?>
                        <tr>
                            <td><?= $d['nama_menu'] ?></td>
                            <td class="text-end"><?= format_rupiah($d['harga']) ?></td>
                            <td class="text-center"><?= $d['jumlah'] ?></td>
                            <td class="text-end"><?= format_rupiah($subtotal) ?></td>
                        </tr>
                        <?php endwhile; ?>
                        <tr class="table-light">
                            <td colspan="3" class="text-end"><strong>Total</strong></td>
                            <td class="text-end"><strong><?= format_rupiah($pesanan['total_harga']) ?></strong></td>
                        </tr>
                    </tbody>
                </table>

                <a href="chat.php?pesanan=<?= $pesanan['kode_pesanan'] ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-comments"></i> Chat Admin
                </a>

            </div>
        </div>
    <?php endwhile; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
