<?php
require_once 'config.php';

if (!isset($_GET['id'])) {
    redirect('paket.php');
}

$id = (int) $_GET['id'];

$query = mysqli_query($conn, "SELECT * FROM paket WHERE id = $id");
$paket = mysqli_fetch_assoc($query);

if (!$paket) {
    redirect('paket.php');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $paket['nama_paket']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h3><?= $paket['nama_paket']; ?></h3>

    <div class="card mt-3">
        <div class="card-body">
            <p><?= $paket['deskripsi']; ?></p>

            <ul class="list-group mb-3">
                <li class="list-group-item">
                    <strong>Harga Paket:</strong>
                    <?= format_rupiah($paket['harga']); ?>
                </li>
                <li class="list-group-item">
                    <strong>Jumlah Porsi:</strong>
                    <?= $paket['min_porsi']; ?> orang
                </li>
            </ul>

            <form method="POST" action="checkout_paket.php">
                <input type="hidden" name="paket_id" value="<?= $paket['id']; ?>">
                <button type="submit" class="btn btn-success">
                    Pesan Paket Ini
                </button>
                <a href="paket.php" class="btn btn-secondary">
                    Kembali
                </a>
            </form>
        </div>
    </div>
</div>

</body>
</html>
