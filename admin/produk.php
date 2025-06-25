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

$sql = "SELECT * FROM products";
$result = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Produk - Toko Buku Digital</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #2c3e50;
            color: white;
            padding: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .title {
            font-size: 22px;
            font-weight: bold;
        }
        .header-actions {
            display: flex;
            gap: 10px;
        }
        .btn {
            padding: 8px 16px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-weight: bold;
            background-color: #3498db;
        }
        .btn:hover {
            background-color: #2980b9;
        }
        main {
            padding: 24px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            table-layout: fixed;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
            word-wrap: break-word;
            font-size: 14px;
        }
        th {
            background-color: #ecf0f1;
        }
        th:nth-child(1), td:nth-child(1) { width: 5%; }     /* ID */
        th:nth-child(2), td:nth-child(2) { width: 15%; }    /* Nama */
        th:nth-child(3), td:nth-child(3) { width: 25%; }    /* Deskripsi */
        th:nth-child(4), td:nth-child(4) { width: 10%; }    /* Harga */
        th:nth-child(5), td:nth-child(5) { width: 10%; }    /* Stok */
        th:nth-child(6), td:nth-child(6) { width: 15%; }    /* Gambar */
        th:nth-child(7), td:nth-child(7) { width: 20%; }    /* Aksi */
        img {
            width: 100px;
            height: auto;
            object-fit: cover;
        }
        .btn-edit {
            background-color: #f39c12;
            padding: 6px 12px;
            border-radius: 4px;
            color: white;
            text-decoration: none;
        }
        .btn-hapus {
            background-color: #e74c3c;
            padding: 6px 12px;
            border-radius: 4px;
            color: white;
            text-decoration: none;
        }
        .btn-edit:hover {
            background-color: #d68910;
        }
        .btn-hapus:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

<header>
    <div class="title">Daftar Produk</div>
    <div class="header-actions">
        <a href="dashboard.php" class="btn">← Kembali</a>
    </div>
</header>

<main>
    <h2 style="text-align:center;">Tabel Produk</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['product_id'] ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td>Rp <?= number_format($row['price'], 0, ',', '.') ?></td>
                        <td><?= $row['stock'] ?></td>
                        <td>
                            <img src="../assets/images/<?= htmlspecialchars($row['image_url']) ?>" alt="Gambar Buku">
                        </td>
                        <td>
                            <a href="edit_produk.php?id=<?= $row['product_id'] ?>" class="btn-edit">Edit</a>
                            <a href="hapus_produk.php?id=<?= $row['product_id'] ?>" class="btn-hapus" onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="7">Tidak ada produk tersedia.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

</body>
</html>
