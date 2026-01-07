<?php
require_once 'config.php';

if (!is_logged_in()) {
    redirect('login.php');
}

if (empty($_SESSION['cart'])) {
    redirect('menu.php');
}

$ids = implode(',', array_keys($_SESSION['cart']));
$query = mysqli_query($conn, "SELECT * FROM menu WHERE id IN ($ids)");

$items = [];
$total = 0;

while ($row = mysqli_fetch_assoc($query)) {
    $qty = $_SESSION['cart'][$row['id']];
    $row['qty'] = $qty;
    $row['subtotal'] = $row['harga'] * $qty;
    $total += $row['subtotal'];
    $items[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h3>Checkout</h3>

    <form method="POST" action="checkout_action.php">
        <div class="mb-3">
            <label>Alamat Pengiriman</label>
            <textarea name="alamat" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label>Catatan</label>
            <textarea name="catatan" class="form-control"></textarea>
        </div>

        <table class="table table-bordered">
            <tr>
                <th>Menu</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?= $item['nama_menu'] ?></td>
                <td><?= format_rupiah($item['harga']) ?></td>
                <td><?= $item['qty'] ?></td>
                <td><?= format_rupiah($item['subtotal']) ?></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <th colspan="3">Total</th>
                <th><?= format_rupiah($total) ?></th>
            </tr>
        </table>

        <button class="btn btn-success">Buat Pesanan</button>
    </form>
</div>

</body>
</html>
