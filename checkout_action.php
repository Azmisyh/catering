<?php
require_once 'config.php';

if (!is_logged_in()) {
    redirect('login.php');
}

if (empty($_SESSION['cart'])) {
    redirect('menu.php');
}

$user_id = $_SESSION['user_id'];
$alamat  = mysqli_real_escape_string($conn, $_POST['alamat']);
$catatan = mysqli_real_escape_string($conn, $_POST['catatan'] ?? '');

// tambahan data acara (kalau belum ada di form, bisa default)
$tanggal_acara = date('Y-m-d');
$waktu_acara   = date('H:i:s');

// buat kode pesanan unik
$kode_pesanan = 'ORD-' . date('YmdHis') . rand(100,999);

mysqli_begin_transaction($conn);

try {

    // =========================
    // Ambil data menu dari cart
    // =========================
    $ids = implode(',', array_keys($_SESSION['cart']));
    $query = mysqli_query($conn, "SELECT * FROM menu WHERE id IN ($ids)");

    $total_harga = 0;
    $min_porsi = 0;
    $items = [];

    while ($row = mysqli_fetch_assoc($query)) {
        $qty = $_SESSION['cart'][$row['id']];
        $subtotal = $row['harga'] * $qty;

        $total_harga += $subtotal;
        $min_porsi += $qty;

        $items[] = [
            'menu_id' => $row['id'],
            'nama'    => $row['nama_menu'],
            'harga'   => $row['harga'],
            'qty'     => $qty,
            'subtotal'=> $subtotal
        ];
    }

    // =========================
    // Insert ke tabel pesanan
    // =========================
    $sql_pesanan = "
        INSERT INTO pesanan 
        (user_id, kode_pesanan, tanggal_acara, waktu_acara, alamat_pengiriman, min_porsi, total_harga, catatan)
        VALUES
        ('$user_id', '$kode_pesanan', '$tanggal_acara', '$waktu_acara', '$alamat', '$min_porsi', '$total_harga', '$catatan')
    ";

    if (!mysqli_query($conn, $sql_pesanan)) {
        throw new Exception("Gagal insert pesanan");
    }

    $pesanan_id = mysqli_insert_id($conn);

    // =========================
    // Insert ke pesanan_detail
    // =========================
    foreach ($items as $item) {
        $sql_detail = "
            INSERT INTO pesanan_detail
            (pesanan_id, menu_id, nama_item, harga, jumlah, subtotal)
            VALUES
            ('$pesanan_id', '{$item['menu_id']}', '{$item['nama']}', '{$item['harga']}', '{$item['qty']}', '{$item['subtotal']}')
        ";

        if (!mysqli_query($conn, $sql_detail)) {
            throw new Exception("Gagal insert detail pesanan");
        }
    }

    // =========================
    // Commit & clear cart
    // =========================
    mysqli_commit($conn);
    unset($_SESSION['cart']);

    redirect("pesanan_sukses.php?kode=$kode_pesanan");

} catch (Exception $e) {
    mysqli_rollback($conn);
    echo "Terjadi kesalahan: " . $e->getMessage();
}
