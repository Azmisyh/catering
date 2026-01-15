<?php
require_once '../config.php';

// Cek login admin
if (!is_logged_in()) {
    redirect('../login.php');
}

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "ID pesanan tidak ditemukan";
    exit;
}

// =========================
// Ambil data pesanan + user
// =========================
$q_pesanan = mysqli_query($conn, "
    SELECT p.*, u.nama, u.email
    FROM pesanan p
    JOIN users u ON p.user_id = u.id
    WHERE p.id = '$id'
");

$pesanan = mysqli_fetch_assoc($q_pesanan);
if (!$pesanan) {
    echo "Pesanan tidak ditemukan";
    exit;
}

// =========================
// Ambil detail pesanan
// =========================
$q_detail = mysqli_query($conn, "
    SELECT d.*, m.nama_menu
    FROM pesanan_detail d
    JOIN menu m ON d.menu_id = m.id
    WHERE d.pesanan_id = '$id'
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .badge-status {
            padding:6px 12px;
            border-radius:20px;
            font-size:13px;
            color:#fff;
        }
        .pending { background:#ffc107;color:#000; }
        .diproses { background:#0d6efd; }
        .selesai { background:#198754; }
        .dibatalkan { background:#dc3545; }
    </style>
</head>
<body>

<div class="container my-4">

    <a href="pesanan.php" class="btn btn-secondary btn-sm mb-3">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>

    <h4 class="mb-3">Detail Pesanan</h4>

    <!-- INFO PESANAN -->
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <strong>Kode Pesanan</strong><br>
                    <?= $pesanan['kode_pesanan'] ?>
                </div>
                <div class="col-md-4">
                    <strong>Tanggal Pesan</strong><br>
                    <?= date('d M Y H:i', strtotime($pesanan['created_at'])) ?>
                </div>
                <div class="col-md-4">
                    <strong>Status</strong><br>
                    <span class="badge-status <?= $pesanan['status'] ?>">
                        <?= strtoupper($pesanan['status']) ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- CUSTOMER -->
    <div class="card mb-3">
        <div class="card-header bg-light">
            <strong>Customer</strong>
        </div>
        <div class="card-body">
            <p class="mb-1"><strong>Nama:</strong> <?= htmlspecialchars($pesanan['nama']) ?></p>
            <p class="mb-0"><strong>Email:</strong> <?= htmlspecialchars($pesanan['email']) ?></p>
        </div>
    </div>

    <!-- PEMBAYARAN -->
    <div class="card mb-3">
        <div class="card-header bg-light">
            <strong>Pembayaran</strong>
        </div>
        <div class="card-body">

            <p>
                <strong>Metode Pembayaran:</strong><br>
                <span class="badge bg-primary"><?= $pesanan['metode_pembayaran'] ?></span>
            </p>

            <div class="alert alert-secondary">
                <?php if ($pesanan['metode_pembayaran'] == 'Transfer Bank'): ?>
                    <strong>Transfer Bank</strong><br>
                    Bank BCA<br>
                    No Rekening: <strong>1234567890</strong><br>
                    A/N: Catering Delicious

                <?php elseif ($pesanan['metode_pembayaran'] == 'QRIS'): ?>
                    <strong>QRIS</strong><br>
                    Pembayaran via QRIS resmi Catering Delicious

                <?php else: ?>
                    <strong>COD</strong><br>
                    Bayar saat pesanan diterima
                <?php endif; ?>
            </div>

            <p><strong>Bukti Pembayaran:</strong></p>
            <?php if (!empty($pesanan['bukti_pembayaran'])): ?>
                <a href="../uploads/bukti/<?= $pesanan['bukti_pembayaran'] ?>" 
                   target="_blank" 
                   class="btn btn-success btn-sm">
                    <i class="fas fa-image"></i> Lihat Bukti
                </a>
            <?php else: ?>
                <span class="text-muted">Belum ada bukti pembayaran</span>
            <?php endif; ?>

        </div>
    </div>

    <!-- DETAIL ITEM -->
    <div class="card mb-3">
        <div class="card-header bg-light">
            <strong>Detail Pesanan</strong>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Menu</th>
                        <th class="text-end">Harga</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($d = mysqli_fetch_assoc($q_detail)): ?>
                    <tr>
                        <td><?= $d['nama_menu'] ?></td>
                        <td class="text-end"><?= format_rupiah($d['harga']) ?></td>
                        <td class="text-center"><?= $d['jumlah'] ?></td>
                        <td class="text-end"><?= format_rupiah($d['subtotal']) ?></td>
                    </tr>
                    <?php endwhile; ?>
                    <tr class="table-light">
                        <td colspan="3" class="text-end"><strong>Total</strong></td>
                        <td class="text-end"><strong><?= format_rupiah($pesanan['total_harga']) ?></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

</body>
</html>
