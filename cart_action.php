<?php
session_start();
require_once 'config.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* ======================
   UPDATE QTY CART
====================== */
if ($action == 'update') {
    if (isset($_POST['qty'])) {
        foreach ($_POST['qty'] as $id => $qty) {
            $qty = (int)$qty;

            if ($qty <= 0) {
                unset($_SESSION['cart'][$id]); // hapus jika 0
            } else {
                $_SESSION['cart'][$id] = $qty; // update qty
            }
        }
    }
    header("Location: cart.php");
    exit;
}

/* ======================
   REMOVE ITEM
====================== */
if ($action == 'remove') {
    $id = $_POST['id'];
    unset($_SESSION['cart'][$id]);

    header("Location: cart.php");
    exit;
}

/* ======================
   CLEAR CART
====================== */
if ($action == 'clear') {
    unset($_SESSION['cart']);

    header("Location: cart.php");
    exit;
}

/* ======================
   ADD TO CART (opsional)
====================== */
if ($action == 'add') {
    $id  = $_POST['id'];
    $qty = $_POST['qty'] ?? 1;

    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] += $qty;
    } else {
        $_SESSION['cart'][$id] = $qty;
    }

    header("Location: cart.php");
    exit;
}
