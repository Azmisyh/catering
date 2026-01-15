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
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h3>Checkout</h3>

    <form method="POST" action="checkout_action.php" enctype="multipart/form-data">

        <div class="mb-3">
            <label>Alamat Pengiriman</label>
            <textarea name="alamat" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label>Catatan</label>
            <textarea name="catatan" class="form-control"></textarea>
        </div>

        <!-- INFORMASI PEMBAYARAN -->
        <div class="alert alert-info">
            <h6><i class="fas fa-info-circle"></i> Informasi Pembayaran</h6>
            <ul class="mb-0">
                <li>
                    <strong>Transfer Bank</strong><br>
                    Bank BCA<br>
                    No Rekening: <strong>1234567890</strong><br>
                    A/N: <strong>Catering Delicious</strong>
                </li>
                <li class="mt-2">
                    <strong>QRIS</strong><br>
                    Scan QRIS resmi Catering Delicious
                </li>
                <li class="mt-2">
                    <strong>COD</strong><br>
                    Bayar langsung saat pesanan diterima
                </li>
            </ul>
        </div>

        <div class="mb-3">
            <label>Metode Pembayaran</label>
            <select name="metode_pembayaran" class="form-control" required>
                <option value="">-- Pilih Metode --</option>
                <option value="Transfer Bank">Transfer Bank</option>
                <option value="QRIS">QRIS</option>
                <option value="COD">COD</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Bukti Pembayaran</label>
            <input type="file" name="bukti_pembayaran" class="form-control" accept="image/*">
            <small class="text-muted">Wajib upload jika Transfer / QRIS</small>
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

<a href="pesanan_detail.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm">
    <i class="fas fa-eye"></i>
</a>

</body>
</html>
