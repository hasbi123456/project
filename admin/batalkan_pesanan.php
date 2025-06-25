<?php
session_start();
require '../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

if (isset($_POST['order_id'])) {
    $order_id = intval($_POST['order_id']);

    $stmt = $koneksi->prepare("UPDATE orders SET status = 'cancelled' WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);

    if ($stmt->execute()) {
        header("Location: pesanan.php?msg=dibatalkan");
        exit;
    } else {
        echo "Gagal membatalkan pesanan.";
    }
} else {
    echo "ID pesanan tidak ditemukan.";
}
?>
