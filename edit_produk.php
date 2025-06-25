<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location:../login.php");
    exit;
}

$host = "localhost";
$user = "root";
$password = "";
$database = "toko_buku";
$koneksi = new mysqli($host, $user, $password, $database);
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$id = $_GET['id'] ?? 0;

// Proses simpan jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image_url = $_POST['image_url'];

    $stmt = $koneksi->prepare("UPDATE products SET name=?, description=?, price=?, image_url=? WHERE product_id=?");
    $stmt->bind_param("ssdsi", $name, $description, $price, $image_url, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: dashboard.php");
    exit;
}

// Ambil data produk
$stmt = $koneksi->prepare("SELECT * FROM products WHERE product_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$produk = $result->fetch_assoc();
$stmt->close();

if (!$produk) {
    echo "Produk tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Produk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 24px;
        }

        form {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 24px;
            border-radius: 8px;
        }

        input, textarea {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
        }

        button {
            background-color: #2ecc71;
            color: white;
            padding: 10px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #27ae60;
        }

        a {
            display: inline-block;
            margin-top: 10px;
            color: #3498db;
        }
    </style>
</head>
<body>

<h2>Edit Produk</h2>
<form method="POST">
    <label>Nama Produk</label>
    <input type="text" name="name" value="<?= htmlspecialchars($produk['name']) ?>" required>

    <label>Deskripsi</label>
    <textarea name="description" required><?= htmlspecialchars($produk['description']) ?></textarea>

    <label>Harga</label>
    <input type="number" name="price" value="<?= $produk['price'] ?>" required>

    <label>URL Gambar (nama file di folder images)</label>
    <input type="text" name="image_url" value="<?= htmlspecialchars($produk['image_url']) ?>" required>

    <button type="submit">Simpan Perubahan</button>
    <br>
    <a href="./dashboard.php">← Kembali ke Daftar Produk</a>
</form>

</body>
</html>
