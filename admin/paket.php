<?php
require_once '../config.php';

if (!is_admin()) {
    redirect('../login.php');
}

$query = mysqli_query($conn, "SELECT * FROM paket ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Paket Catering</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h3 class="mb-4">Data Paket Catering</h3>

    <a href="paket_tambah.php" class="btn btn-primary mb-3">
        + Tambah Paket
    </a>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Paket</th>
                <th>Deskripsi</th>
                <th>Harga</th>
                <th>Jumlah Porsi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php $no = 1; while ($p = mysqli_fetch_assoc($query)) : ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $p['nama_paket']; ?></td>
                <td><?= $p['deskripsi']; ?></td>
                <td><?= format_rupiah($p['harga']); ?></td>
                <td><?= $p['min_porsi']; ?> orang</td>
                <td>
                    <a href="paket_edit.php?id=<?= $p['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="paket_hapus.php?id=<?= $p['id']; ?>" 
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Hapus paket ini?')">
                       Hapus
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
