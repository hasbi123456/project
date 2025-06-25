<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "toko_buku";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Tangkap data dari form
$username = trim($_POST['username']);
$email = trim($_POST['email']);
$password = $_POST['password'];
$confirm = $_POST['confirm'];

// Validasi dasar
if ($password !== $confirm) {
    echo "<script>alert('Konfirmasi password tidak cocok.'); window.history.back();</script>";
    exit;
}

// Cek apakah username/email sudah ada
$cek = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
$cek->bind_param("ss", $username, $email);
$cek->execute();
$result = $cek->get_result();

if ($result->num_rows > 0) {
    echo "<script>alert('Username atau email sudah terdaftar.'); window.history.back();</script>";
    exit;
}

// Enkripsi password dengan MD5
$password_md5 = md5($password);

// Simpan ke database (default role = 'user')
$stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
$stmt->bind_param("sss", $username, $email, $password_md5);

if ($stmt->execute()) {
    echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location.href='login.php';</script>";
} else {
    echo "<script>alert('Terjadi kesalahan saat mendaftar.'); window.history.back();</script>";
}
?>
