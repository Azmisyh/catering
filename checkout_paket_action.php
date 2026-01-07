<?php
require_once 'config.php';

if (!is_logged_in()) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];

$paket_id = (int) $_POST['paket_id'];
$tanggal  = $_POST['tanggal_acara'];
$waktu    = $_POST['waktu_acara'];
$alamat   = mysqli_real_escape_string($conn, $_POST['alamat_pengiriman']);
$catatan  = mysqli_real_escape_string($conn, $_POST['catatan'] ?? '');

$paket = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM paket WHERE id = $paket_id")
);

$kode = 'PKT-' . date('YmdHis');

mysqli_begin_transaction($conn);

try {

    // insert pesanan
    mysqli_query($conn, "
        INSERT INTO pesanan
        (user_id, kode_pesanan, tanggal_acara, waktu_acara, alamat_pengiriman, min_porsi, total_harga, catatan)
        VALUES
        ('$user_id', '$kode', '$tanggal', '$waktu', '$alamat', '{$paket['min_porsi']}', '{$paket['harga']}', '$catatan')
    ");

    $pesanan_id = mysqli_insert_id($conn);

    // insert detail paket
    mysqli_query($conn, "
        INSERT INTO pesanan_detail
        (pesanan_id, paket_id, nama_item, harga, jumlah, subtotal)
        VALUES
        ('$pesanan_id', '$paket_id', '{$paket['nama_paket']}', '{$paket['harga']}', 1, '{$paket['harga']}')
    ");

    mysqli_commit($conn);

    redirect("pesanan_sukses.php?kode=$kode");

} catch (Exception $e) {
    mysqli_rollback($conn);
    echo "Gagal menyimpan pesanan paket";
}
