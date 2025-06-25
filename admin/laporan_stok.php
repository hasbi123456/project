<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}

$koneksi = new mysqli("localhost", "root", "", "toko_buku");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$sql = "SELECT product_id, name, stock, price FROM products ORDER BY name ASC";
$result = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Stok - Litera Book Store</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 24px; background: #f4f4f4; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; background: #fff; margin-top: 16px; }
        th, td { padding: 12px; border: 1px solid #ccc; }
        th { background-color: #1e3a8a; color: white; }
        tr:nth-child(even) { background: #f9f9f9; }
        .btn-group {
            margin-bottom: 16px;
            display: flex;
            justify-content: flex-start;
            gap: 12px;
        }
        .btn-cetak, .kembali {
            background: #1e3a8a;
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
        }
        .btn-cetak:hover, .kembali:hover {
            background: #2e4cb5;
        }
    </style>
</head>
<body>
    <h2>Laporan Stok Buku</h2>

    <div class="btn-group">
        <a href="cetak_laporan_stok.php" target="_blank" class="btn-cetak">🖨 Cetak</a>
        <a href="dashboard.php" class="kembali">←</a>
    </div>

    <table>
        <tr>
            <th>ID Produk</th>
            <th>Nama Buku</th>
            <th>Stok</th>
            <th>Harga</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['product_id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= $row['stock'] ?></td>
                <td>Rp <?= number_format($row['price'], 0, ',', '.') ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
