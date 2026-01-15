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
$metode_pembayaran = mysqli_real_escape_string($conn, $_POST['metode_pembayaran']);

// default acara
$tanggal_acara = date('Y-m-d');
$waktu_acara   = date('H:i:s');

// kode pesanan
$kode_pesanan = 'ORD-' . date('YmdHis') . rand(100,999);

// =========================
// Upload Bukti Pembayaran
// =========================
$bukti_pembayaran = null;

if (!empty($_FILES['bukti_pembayaran']['name'])) {

    $folder = "uploads/bukti/";
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $ext = pathinfo($_FILES['bukti_pembayaran']['name'], PATHINFO_EXTENSION);
    $nama_file = 'bukti_' . time() . '_' . rand(100,999) . '.' . $ext;
    $path = $folder . $nama_file;

    if (move_uploaded_file($_FILES['bukti_pembayaran']['tmp_name'], $path)) {
        $bukti_pembayaran = $nama_file;
    } else {
        echo "Gagal upload bukti pembayaran";
        exit;
    }
}

mysqli_begin_transaction($conn);

try {

    // =========================
    // Ambil menu dari cart
    // =========================
    $ids = implode(',', array_keys($_SESSION['cart']));
    $query = mysqli_query($conn, "SELECT * FROM menu WHERE id IN ($ids)");

    $total_harga = 0;
    $jumlah_porsi = 0;
    $items = [];

    while ($row = mysqli_fetch_assoc($query)) {
        $qty = $_SESSION['cart'][$row['id']];
        $subtotal = $row['harga'] * $qty;

        $total_harga += $subtotal;
        $jumlah_porsi += $qty;

        $items[] = [
            'menu_id' => $row['id'],
            'nama'    => $row['nama_menu'],
            'harga'   => $row['harga'],
            'qty'     => $qty,
            'subtotal'=> $subtotal
        ];
    }

    // =========================
    // Insert Pesanan
    // =========================
    $sql_pesanan = "
        INSERT INTO pesanan 
        (user_id, kode_pesanan, tanggal_acara, waktu_acara, alamat_pengiriman, jumlah_porsi, total_harga, catatan, metode_pembayaran, bukti_pembayaran, status_pembayaran)
        VALUES
        ('$user_id', '$kode_pesanan', '$tanggal_acara', '$waktu_acara', '$alamat', '$jumlah_porsi', '$total_harga', '$catatan', '$metode_pembayaran', '$bukti_pembayaran', 'menunggu_verifikasi')
    ";

    if (!mysqli_query($conn, $sql_pesanan)) {
        throw new Exception("Gagal insert pesanan");
    }

    $pesanan_id = mysqli_insert_id($conn);

    // =========================
    // Insert Detail Pesanan
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

    mysqli_commit($conn);
    unset($_SESSION['cart']);

    redirect("pesanan_sukses.php?kode=$kode_pesanan");

} catch (Exception $e) {
    mysqli_rollback($conn);
    echo "Terjadi kesalahan: " . $e->getMessage();
}
