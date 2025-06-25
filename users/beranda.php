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

$sql = "SELECT product_id, name, description, price, image_url, stock FROM products";
$result = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Litera Book Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #d3d6db;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #1e3a8a;
            color: white;
            padding: 16px 32px;
        }

        .header-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .header-top > div:first-child {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .header-top img {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
        }

        .title {
            font-size: 24px;
            font-weight: 600;
        }

        .nav-links {
            margin-top: 12px;
            display: flex;
            gap: 18px;
            flex-wrap: wrap;
            padding-left: 64px;
        }

        .nav-links a {
            color: white;
            font-weight: 300;
            font-size: 14px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: color 0.2s ease;
        }

        .nav-links a:hover {
            color: #cbd5ff;
        }

        .user-info {
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-logout {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            font-weight: 500;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
        }

        main {
            padding: 24px;
            max-width: 1200px;
            margin: 0 auto;
        }

        h2 {
            text-align: center;
            margin-bottom: 32px;
            color: #000;
            font-weight: 400;
        }

        .produk-list {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
        }

        .produk-item {
            background: #e4e4e4;
            overflow: hidden;
            height: 520px;
            display: flex;
            flex-direction: column;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .produk-item img {
            width: 100%;
            height: 60%;
            object-fit: cover;
        }

        .produk-content {
            padding: 16px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .produk-item h3 {
            margin: 0;
            font-size: 18px;
            color: #000;
            font-weight: 400;
        }

        .produk-item p {
            margin: 8px 0;
            color: #333;
            font-size: 14px;
            font-weight: 300;
            flex-grow: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }

        .produk-item .harga {
            font-weight: 500;
            color: #000;
            font-size: 16px;
        }

        .aksi-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }

        .btn-beli {
            background: none;
            color: #1E40AF;
            padding: 8px 14px;
            border-radius: 5px;
            font-weight: 400;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-beli:hover {
            text-decoration: underline;
        }

        .btn-icon {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #1E40AF;
            padding: 4px;
        }

        .btn-icon:hover {
            transform: scale(1.2);
            color: #374ac6;
        }

        .floating-chat {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background-color: #1e3a8a;
            color: white;
            font-size: 22px;
            padding: 14px;
            border-radius: 50%;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
            text-decoration: none;
            z-index: 999;
            transition: background-color 0.3s;
        }

        .floating-chat:hover {
            background-color: #324cb3;
        }

        @media (max-width: 992px) {
            .produk-list {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .produk-list {
                grid-template-columns: 1fr;
            }

            .nav-links {
                padding-left: 0;
                justify-content: flex-start;
            }
        }
    </style>
</head>
<body>

<header>
    <div class="header-top">
        <div>
            <img src="../assets/images/60.JPG" alt="Logo LiteraNusa" />
            <div class="title">Litera Book Store</div>
        </div>

        <div class="user-info">
            <div>Selamat datang, <?= htmlspecialchars($_SESSION['username']) ?></div>
            <a href="../logout.php" class="btn-logout">Logout</a>
        </div>
    </div>

    <div class="nav-links">
        <a href="keranjang.php"><i class="fas fa-shopping-cart"></i> Keranjang</a>
        <a href="orders.php"><i class="fas fa-box"></i> Order</a>
    </div>
</header>

<main>
    <h2>Daftar Buku</h2>

    <div class="produk-list">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="produk-item">
                    <img src="<?= '../assets/images/' . htmlspecialchars($row['image_url']) ?>" alt="Gambar Buku" />
                    <div class="produk-content">
                        <h3><?= htmlspecialchars($row['name']) ?></h3>
                        <p><?= htmlspecialchars($row['description']) ?></p>
                        <div class="harga">Rp <?= number_format($row['price'], 0, ',', '.') ?></div>
                        <div>Stok: <?= htmlspecialchars($row['stock']) ?></div>
                        <div class="aksi-wrapper">
                            <form action="checkout.php" method="POST" style="margin:0;">
                                <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn-beli">
                                    <i class="fas fa-credit-card"></i> Beli
                                </button>
                            </form>
                            <form action="tambah_keranjang.php" method="POST" style="margin:0;">
                                <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn-icon" title="Tambah ke Keranjang">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align:center;">Tidak ada produk ditemukan.</p>
        <?php endif; ?>
    </div>
</main>

<!-- Tombol Chat -->
<a href="../chat.php" class="floating-chat" title="Hubungi Admin">
    💬
</a>

</body>
</html>
