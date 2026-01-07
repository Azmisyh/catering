<?php
require_once 'config.php';

if (!is_logged_in()) {
    redirect('login.php');
}

$paket_id = (int) $_POST['paket_id'];

$query = mysqli_query($conn, "SELECT * FROM paket WHERE id = $paket_id");
$paket = mysqli_fetch_assoc($query);

if (!$paket) {
    redirect('paket.php');
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout Paket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h3>Checkout Paket</h3>

    <form method="POST" action="checkout_paket_action.php">

        <input type="hidden" name="paket_id" value="<?= $paket['id']; ?>">

        <div class="mb-3">
            <label>Tanggal Acara</label>
            <input type="date" name="tanggal_acara" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Waktu Acara</label>
            <input type="time" name="waktu_acara" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Alamat Pengiriman</label>
            <textarea name="alamat_pengiriman" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label>Catatan</label>
            <textarea name="catatan" class="form-control"></textarea>
        </div>

        <table class="table table-bordered">
            <tr>
                <th>Paket</th>
                <th>Harga</th>
                <th>Jumlah Porsi</th>
            </tr>
            <tr>
                <td><?= $paket['nama_paket']; ?></td>
                <td><?= format_rupiah($paket['harga']); ?></td>
                <td><?= $paket['min_porsi']; ?> orang</td>
            </tr>
        </table>

        <button class="btn btn-success">Buat Pesanan</button>
        <a href="paket.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

</body>
</html>
