<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
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

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $koneksi->real_escape_string($_POST['name']);
    $description = $koneksi->real_escape_string($_POST['description']);
    $price = (int) $_POST['price'];
    $stock = (int) $_POST['stock'];

    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image_file']['tmp_name'];
        $fileName = $_FILES['image_file']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedfileExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileExtension, $allowedfileExtensions)) {
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $uploadFileDir = '../assets/images/';
            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $sql = "INSERT INTO products (name, description, price, stock, image_url) VALUES ('$name', '$description', $price, $stock, '$newFileName')";
                if ($koneksi->query($sql)) {
                    header("Location: dashboard.php");
                    exit;
                } else {
                    $error = "Gagal menambahkan produk: " . $koneksi->error;
                }
            } else {
                $error = 'Gagal memindahkan file.';
            }
        } else {
            $error = 'Tipe file tidak diizinkan.';
        }
    } else {
        $error = 'Silakan unggah gambar.';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Tambah Produk</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #d3d6db;
            margin: 0;
            padding: 0;
        }

        .header-title {
            background-color: #000;
            color: white;
            padding: 24px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
        }

        main {
            max-width: 600px;
            margin: 40px auto;
            background-color: #fff;
            padding: 24px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-top: 12px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"] {
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        button {
            margin-top: 24px;
            padding: 14px;
            background-color: #000;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }

        .error {
            margin-top: 16px;
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="header-title">Tambah Produk</div>

<main>
    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="tambah_produk.php" method="post" enctype="multipart/form-data">
        <label>Nama Produk:</label>
        <input type="text" name="name" required>

        <label>Deskripsi:</label>
        <input type="text" name="description" required>

        <label>Harga:</label>
        <input type="number" name="price" required>

        <label>Stok:</label>
        <input type="number" name="stock" required>

        <label>Gambar Produk:</label>
        <input type="file" name="image_file" accept="image/*" required>

        <button type="submit">Simpan Produk</button>
        <a href="dashboard.php" class="btn-back">Cancel</a>
    </form>
</main>

</body>
</html>
