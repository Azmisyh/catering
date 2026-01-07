<?php
require_once 'config.php';

if (!is_logged_in()) {
    redirect('login.php');
}

$cart = $_SESSION['cart'] ?? [];
$total = 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang - Catering Delicious</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="menu.php">
            <i class="fas fa-utensils"></i> Catering Delicious
        </a>
        <a href="menu.php" class="btn btn-outline-light">← Kembali</a>
    </div>
</nav>

<div class="container my-5">
    <h3 class="mb-4">
        <i class="fas fa-shopping-cart"></i> Keranjang Belanja
    </h3>

<?php if (empty($cart)): ?>
    <div class="alert alert-info text-center">
        Keranjang masih kosong
    </div>
<?php else: ?>


<form method="POST" action="cart_action.php">
<input type="hidden" name="action" value="update">

<table class="table table-bordered align-middle">
<thead>
<tr>
    <th>Menu</th>
    <th>Harga</th>
    <th width="120">Qty</th>
    <th>Subtotal</th>
    <th>Aksi</th>
</tr>
</thead>
<tbody>
</div>
</form>


<?php foreach ($cart as $id => $qty): 
$query = mysqli_query($conn, "SELECT nama_menu, harga FROM menu WHERE id='$id'");

if (!$query || mysqli_num_rows($query) == 0) {
    continue; // skip kalau menu tidak ditemukan
}

$menu = mysqli_fetch_assoc($query);

$harga = (int)$menu['harga'];
$qty   = (int)$qty;

$subtotal = $harga * $qty;
$total += $subtotal;

?>
<tr>
    <td><?= $menu['nama_menu']; ?></td>
    <td><?= format_rupiah($menu['harga']); ?></td>
    <td>
        <input type="number" name="qty[<?= $id ?>]" value="<?= $qty ?>" min="1" class="form-control">
    </td>
    <td><?= format_rupiah($subtotal); ?></td>
    <td>
        <form method="POST" action="cart_action.php" class="d-inline">
            <input type="hidden" name="action" value="remove">
            <input type="hidden" name="id" value="<?= $id ?>">
            <button class="btn btn-danger btn-sm">
                <i class="fas fa-trash"></i>
            </button>
        </form>
    </td>
</tr>
<?php endforeach; ?>

    </tbody>
</table>

<div class="d-flex justify-content-between align-items-center">
    <h4>Total: <span class="text-success"><?= format_rupiah($total); ?></span></h4>
    <div>
        <button class="btn btn-warning">
            <i class="fas fa-sync"></i> Update
        </button>
        <a href="cart_action.php" onclick="event.preventDefault(); document.getElementById('clear').submit();" class="btn btn-danger">
            Kosongkan
        </a>
        <a href="checkout.php" class="btn btn-success">
            Checkout
        </a>
    </div>
</div>
</form>

<form method="POST" action="cart_action.php" id="clear">
    <input type="hidden" name="action" value="clear">
</form>

<?php endif; ?>
</div>

</body>
</html>
