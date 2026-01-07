<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function format_rupiah($angka){
    return "Rp " . number_format($angka, 0, ',', '.');
}

function redirect($url){
    header("Location: $url");
    exit;
}

function is_admin(){
    if(!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin'){
        redirect('../login.php');
    }
}
