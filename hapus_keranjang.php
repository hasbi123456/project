<?php
session_start();

if (isset($_GET['index'])) {
    $index = (int)$_GET['index'];

    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
        // Reindex agar tidak ada lubang di array
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}

// Redirect ke keranjang.php
header("Location: keranjang.php");
exit;
?>
