<?php
session_start();

// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Periksa apakah data dikirim via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    // Validasi jumlah
    if ($quantity < 1) {
        $quantity = 1;
    }

    // Inisialisasi keranjang jika belum ada
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Cek apakah produk sudah ada dalam keranjang
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] += $quantity;
            $found = true;
            break;
        }
    }
    unset($item); // keluar dari referensi loop

    // Jika belum ada, tambahkan item baru
    if (!$found) {
        $_SESSION['cart'][] = [
            'product_id' => $product_id,
            'quantity' => $quantity
        ];
    }

    // Redirect kembali ke dashboard
    header("Location: keranjang.php?pesan=keranjang-ditambahkan");
    exit;
} else {
    // Jika tidak melalui POST
    header("Location: keranjang.php");
    exit;
}
?>
