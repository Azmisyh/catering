<?php
require_once 'config.php';

$query = mysqli_query($conn, "SELECT * FROM paket ORDER BY harga ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Paket Catering</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h3 class="mb-4 text-center">Paket Catering</h3>

    <div class="row">
        <?php while ($p = mysqli_fetch_assoc($query)) : ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><?= $p['nama_paket']; ?></h5>
                        <p class="card-text"><?= $p['deskripsi']; ?></p>

                        <ul class="list-group list-group-flush mb-3">
                            <li class="list-group-item">
                                Harga: <strong><?= format_rupiah($p['harga']); ?></strong>
                            </li>
                            <li class="list-group-item">
                                Jumlah Porsi: <?= $p['min_porsi']; ?> orang
                            </li>
                        </ul>

                        <a href="paket_detail.php?id=<?= $p['id']; ?>" class="btn btn-primary w-100">
                            Pilih Paket
                        </a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>
