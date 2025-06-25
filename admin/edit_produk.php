<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
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
if (!$id) {
    echo "ID produk tidak ditemukan.";
    exit;
}

// Proses simpan jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $image_url = $_POST['image_url'];

    $stmt = $koneksi->prepare("UPDATE products SET name=?, description=?, price=?, stock=?, image_url=? WHERE product_id=?");
    $stmt->bind_param("ssdisi", $name, $description, $price, $stock, $image_url, $id);
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
    <meta charset="UTF-8" />
    <title>Edit Produk</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #d3d6db;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #000;
            color: white;
            padding: 16px 24px;
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: auto;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
        }

        main {
            max-width: 600px;
            background: #e4e4e4;
            margin: 24px auto;
            padding: 24px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        h2 {
            margin-bottom: 24px;
            color: #000;
            text-align: center;
        }

        form label {
            display: block;
            margin: 12px 0 6px;
            font-weight: 600;
            color: #333;
        }

        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 15px;
            box-sizing: border-box;
            resize: vertical;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        textarea {
            min-height: 100px;
        }

        button.submit-btn {
            margin-top: 20px;
            background-color: #2ecc71;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s;
        }

        button.submit-btn:hover {
            background-color: #27ae60;
        }

        .btn-back {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 18px;
            background-color: #3498db;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s;
            user-select: none;
        }

        .btn-back:hover {
            background-color: #2c80b4;
        }
    </style>
</head>
<body>

<header>
    <div class="header-top">
        <div class="title">Edit Produk</div>
    </div>
</header>

<main>
    <form method="POST">
        <label for="name">Nama Produk</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($produk['name']) ?>" required>

        <label for="description">Deskripsi</label>
        <textarea id="description" name="description" required><?= htmlspecialchars($produk['description']) ?></textarea>

        <label for="price">Harga</label>
        <input type="number" id="price" name="price" value="<?= $produk['price'] ?>" required>

        <label for="stock">Stok</label>
        <input type="number" id="stock" name="stock" value="<?= $produk['stock'] ?>" required>

        <label for="image_url">URL Gambar (nama file di folder images)</label>
        <input type="text" id="image_url" name="image_url" value="<?= htmlspecialchars($produk['image_url']) ?>" required>

        <button type="submit" class="submit-btn">Simpan Perubahan</button>
    </form>

    <a href="dashboard.php" class="btn-back">Cancel</a>
</main>

</body>
</html>
