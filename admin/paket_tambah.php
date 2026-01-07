<?php
require_once '../config.php';

if (!is_admin()) {
    redirect('../login.php');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Paket Catering</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h3 class="mb-4">Tambah Paket Catering</h3>

    <form action="paket_tambah_action.php" method="POST">

        <div class="mb-3">
            <label>Nama Paket</label>
            <input type="text" name="nama_paket" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Deskripsi Paket</label>
            <textarea name="deskripsi" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label>Harga Paket</label>
            <input type="number" name="harga" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Jumlah Porsi</label>
            <input type="number" name="jumlah_porsi" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Simpan Paket</button>
        <a href="paket.php" class="btn btn-secondary">Kembali</a>

    </form>
</div>

</body>
</html>
