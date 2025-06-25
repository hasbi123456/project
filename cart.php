<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Koneksi database
$host = "localhost";
$user = "root";
$password = "";
$database = "toko_buku";

$koneksi = new mysqli($host, $user, $password, $database);
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Ambil user_id dari session username
$username = $_SESSION['username'];
$user_query = $koneksi->query("SELECT user_id FROM users WHERE username = '$username'");
if ($user_query->num_rows == 0) {
    die("Pengguna tidak ditemukan.");
}
$user = $user_query->fetch_assoc();
$user_id = $user['user_id'];

// Ambil product_id dari URL
if (!isset($_GET['product_id'])) {
    echo "<script>alert('ID produk tidak ditemukan.'); window.location.href = 'beranda.php';</script>";
    exit;
}
$product_id = intval($_GET['product_id']);

// Ambil data produk
$product_query = $koneksi->query("SELECT * FROM products WHERE product_id = $product_id");
if ($product_query->num_rows == 0) {
    echo "<script>alert('Produk tidak ditemukan.'); window.location.href = 'beranda.php';</script>";
    exit;
}
$product = $product_query->fetch_assoc();

// Data order
$price = $product['price'];
$quantity = 1;
$subtotal = $price * $quantity;
$total_amount = $subtotal;
$shipping_address = "Alamat default user"; // Ubah jika perlu ambil dari profil
$phone = "080000000000"; // Ubah jika perlu ambil dari profil

// Simpan ke tabel orders
$insert_order = $koneksi->prepare("INSERT INTO orders (user_id, total_amount, shipping_address, phone) VALUES (?, ?, ?, ?)");
$insert_order->bind_param("idss", $user_id, $total_amount, $shipping_address, $phone);
if (!$insert_order->execute()) {
    die("Gagal menyimpan data order: " . $insert_order->error);
}
$order_id = $koneksi->insert_id;

// Simpan ke tabel order_details
$insert_detail = $koneksi->prepare("INSERT INTO order_details (order_id, product_id, quantity, price, subtotal) VALUES (?, ?, ?, ?, ?)");
$insert_detail->bind_param("iiidd", $order_id, $product_id, $quantity, $price, $subtotal);
if (!$insert_detail->execute()) {
    die("Gagal menyimpan data detail order: " . $insert_detail->error);
}

// Konfirmasi dan redirect
echo "<script>alert('Produk berhasil dipesan!'); window.location.href = 'orders.php';</script>";
?>
