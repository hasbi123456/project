<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "toko_buku";

$koneksi = new mysqli($host, $user, $password, $database);
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$id = $_GET['product_id'] ?? null;
if (!$id) {
    echo "ID tidak ditemukan.";
    exit;
}

$sql = "DELETE FROM products WHERE id = ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location:produk.php");
exit;
