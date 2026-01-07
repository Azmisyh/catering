<?php
require_once 'config.php';

if (!is_logged_in()) {
    redirect('login.php');
}

if (!isset($_GET['kode'])) {
    redirect('index.php');
}

$kode = mysqli_real_escape_string($conn, $_GET['kode']);

$query = mysqli_query($conn, "
    SELECT p.*, u.nama 
    FROM pesanan p
    JOIN users u ON p.user_id = u.id
    WHERE p.kode_pesanan = '$kode'
");

$pesanan = mysqli_fetch_assoc($query);

if (!$pesanan) {
    redirect('index.php');
}

// ambil detail pesanan (menu / paket)
$detail = mysqli_query($conn, "
    SELECT d.*, m.nama_menu, pk.nama_paket
    FROM pesanan_detail d
    LEFT JOIN menu m ON d.menu_id = m.id
    LEFT JOIN paket pk ON d.paket_id = pk.id
    WHERE d.pesanan_id = {$pesanan['id']}
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesanan Berhasil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-body text-center">
            <h2 class="text-success">🎉 Pesanan Berhasil Dibuat</h2>
            <p class="mt-3">
                Terima kasih <strong><?= $pesanan['nama']; ?></strong>, pesanan kamu berhasil dibuat.
            </p>

            <hr>

            <p>
                <strong>Kode Pesanan:</strong><br>
                <span class="fs-4 text-primary"><?= $pesanan['kode_pesanan']; ?></span>
            </p>

            <p>
                <strong>Status Pesanan:</strong><br>
                <span class="badge bg-warning text-dark">
                    <?= ucfirst($pesanan['status']); ?>
                </span>
            </p>

            <p>
                <strong>Tanggal Acara:</strong>
                <?= date('d M Y', strtotime($pesanan['tanggal_acara'])); ?><br>
                <strong>Waktu:</strong>
                <?= date('H:i', strtotime($pesanan['waktu_acara'])); ?>
            </p>

            <p>
                <strong>Alamat Pengiriman:</strong><br>
                <?= nl2br($pesanan['alamat_pengiriman']); ?>
            </p>

            <?php if ($pesanan['catatan']) : ?>
                <p>
                    <strong>Catatan:</strong><br>
                    <?= nl2br($pesanan['catatan']); ?>
                </p>
            <?php endif; ?>
        </div>
    </div>

    <!-- DETAIL PESANAN -->
    <div class="card mt-4">
        <div class="card-body">
            <h5>Detail Pesanan</h5>

            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($d = mysqli_fetch_assoc($detail)) : ?>
                    <tr>
                        <td>
                            <?= $d['nama_menu'] ?? $d['nama_paket']; ?>
                        </td>
                        <td><?= format_rupiah($d['harga']); ?></td>
                        <td><?= $d['jumlah']; ?></td>
                        <td><?= format_rupiah($d['subtotal']); ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3">Total</th>
                        <th><?= format_rupiah($pesanan['total_harga']); ?></th>
                    </tr>
                </tfoot>
            </table>

            <div class="text-center mt-4">
                <a href="index.php" class="btn btn-primary">Kembali ke Beranda</a>
                <a href="riwayat_pesanan.php" class="btn btn-outline-secondary">
                    Riwayat Pesanan
                </a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
