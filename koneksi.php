<?php
$host = "localhost";        
$user = "root";          
$password = "";
$database = "toko_buku"; 

// Membuat koneksi
$koneksi = new mysqli($host, $user, $password, $database);

// Mengecek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
?>
