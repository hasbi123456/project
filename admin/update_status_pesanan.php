<?php
session_start();
require '../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

if (isset($_POST['order_id'], $_POST['status'])) {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];

    $allowed_status = ['pending', 'processing', 'shipped', 'completed', 'cancelled'];
    if (!in_array($status, $allowed_status)) {
        // Redirect ke halaman sebelumnya dengan pesan error
        header("Location: update.php?msg=Status tidak valid");
        exit;
    }

    $stmt = $koneksi->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
    $stmt->bind_param("si", $status, $order_id);

    if ($stmt->execute()) {
        // Redirect ke halaman update.php dengan pesan sukses
        header("Location: update.php?msg=Status berhasil diupdate");
        exit;
    } else {
        // Redirect dengan pesan gagal
        header("Location: update.php?msg=Gagal mengupdate status");
        exit;
    }
} else {
    header("Location: update.php?msg=Data tidak lengkap");
    exit;
}
