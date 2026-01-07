<?php
// config.php
session_start();

/* ===== DATABASE ===== */
$conn = mysqli_connect("localhost", "root", "", "catering_db");
if (!$conn) {
    die("Koneksi database gagal");
}

/* ===== HELPER FUNCTIONS ===== */
function redirect($url) {
    header("Location: $url");
    exit;
}
function format_rupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}



function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}
function clean_input($data) {
    global $conn;
    return mysqli_real_escape_string($conn, trim($data));
}