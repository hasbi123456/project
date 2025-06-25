<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location:../login.php");
    exit;
}

if (!isset($_GET['order_id'])) {
    header("Location:orders.php");
    exit;
}

$order_id = intval($_GET['order_id']);

$koneksi = new mysqli("localhost", "root", "", "toko_buku");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Hapus detail pesanan terlebih dahulu
$koneksi->query("DELETE FROM order_details WHERE order_id = $order_id");

// Hapus pesanan utama
$koneksi->query("DELETE FROM orders WHERE order_id = $order_id");

header("Location:orders.php");
exit;
?>
