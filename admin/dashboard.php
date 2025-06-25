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

$sql = "SELECT product_id, name, description, price, image_url FROM products";
$result = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard - Litera Book Store</title>

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
            padding: 16px 32px 12px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .header-left img {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
        }

        .header-left .title {
            font-size: 24px;
            font-weight: 600;
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
        }

        .button-group {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 6px;
            padding-left: 64px;
            position: relative;
        }

        .button-group a {
            color: white;
            font-weight: 400;
            font-size: 14px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: color 0.2s ease;
        }

        .button-group a:hover {
            color: #cbd5ff;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #1e3a8a;
            min-width: 180px;
            box-shadow: 0px 2px 8px rgba(0,0,0,0.2);
            z-index: 1;
            flex-direction: column;
            padding: 8px 0;
            border-radius: 6px;
            top: 28px;
        }

        .dropdown-content.show {
            display: flex;
        }

        .dropdown-content a {
            color: white;
            padding: 10px 16px;
            text-decoration: none;
            display: block;
            font-size: 14px;
        }

        .dropdown-content a:hover {
            background-color: #374bb7;
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

        .action-buttons {
            margin-top: 12px;
            display: flex;
            gap: 10px;
        }

        .btn-edit, .btn-delete {
            padding: 6px 12px;
            font-size: 14px;
            border-radius: 5px;
            font-weight: 500;
            color: white;
            text-decoration: none;
        }

        .btn-edit {
            background-color: #2980b9;
        }

        .btn-delete {
            background-color: #e74c3c;
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

        @media (max-width: 768px) {
            .produk-list {
                grid-template-columns: repeat(2, 1fr);
            }

            .button-group {
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .produk-list {
                grid-template-columns: 1fr;
            }

            .header-top {
                flex-direction: column;
                align-items: center;
            }

            .button-group {
                flex-direction: column;
                align-items: center;
                padding-left: 0;
            }

            .dropdown-content {
                left: 0;
                right: auto;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const laporanButton = document.getElementById('laporan-toggle');
            const laporanDropdown = document.getElementById('laporan-dropdown');

            laporanButton.addEventListener('click', function (e) {
                e.preventDefault();
                laporanDropdown.classList.toggle('show');
            });

            document.addEventListener('click', function (e) {
                if (!laporanButton.contains(e.target) && !laporanDropdown.contains(e.target)) {
                    laporanDropdown.classList.remove('show');
                }
            });
        });
    </script>
</head>
<body>

<header>
    <div class="header-top">
        <div class="header-left">
            <img src="../assets/images/60.JPG" alt="Logo LiteraNusa" />
            <div class="title">Litera Book Store</div>
        </div>
        <div class="user-info">
            Selamat datang, <?= htmlspecialchars($_SESSION['username']) ?> |
            <a href="../logout.php" class="btn-logout">Logout</a>
        </div>
    </div>

    <div class="button-group">
        <a href="produk.php"><i class="fas fa-book"></i> Produk</a>
        <a href="tambah_produk.php"><i class="fas fa-circle-plus"></i> Tambah Produk</a>
        <a href="Pesanan.php"><i class="fas fa-shopping-cart"></i> Pesanan</a>
        <a href="update.php"><i class="fas fa-sync-alt"></i> Update</a>

        <div class="dropdown">
            <a href="#" id="laporan-toggle">
                <i class="fas fa-file-alt"></i> Laporan <i class="fas fa-caret-down"></i>
            </a>
            <div class="dropdown-content" id="laporan-dropdown">
                <a href="laporan_harian.php">Laporan Harian</a>
                <a href="laporan_stok.php">Laporan Stok</a>
                <a href="laporan_bulanan.php">Laporan Bulanan</a>
                <a href="laporan_tahunan.php">Laporan Tahunan</a>
                <a href="user.php">User</a>
            </div>
        </div>
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

                        <div class="action-buttons">
                            <a href="edit_produk.php?id=<?= $row['product_id'] ?>" class="btn-edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="hapus_produk.php?id=<?= $row['product_id'] ?>" class="btn-delete" onclick="return confirm('Yakin ingin menghapus produk ini?');">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align:center;">Tidak ada produk ditemukan.</p>
        <?php endif; ?>
    </div>
</main>

<!-- Tombol Chat Mengambang -->
<a href="../chat.php" class="floating-chat" title="Buka Live Chat">
    💬
</a>

</body>
</html>
