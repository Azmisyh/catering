<?php
// config.php

// ================== SESSION ==================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ================== DATABASE =================
$conn = mysqli_connect("localhost", "root", "", "catering_db");

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// ================== HELPER ===================
function clean_input($data) {
    global $conn;
    return mysqli_real_escape_string($conn, trim($data));
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function format_rupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}

// ================== AUTH =====================
function is_admin() {
    if (
        !isset($_SESSION['login']) ||
        !isset($_SESSION['role']) ||
        $_SESSION['role'] !== 'admin'
    ) {
        redirect('../index.php');
    }
    return true;
}
