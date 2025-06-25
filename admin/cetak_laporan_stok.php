<?php
$koneksi = new mysqli("localhost", "root", "", "toko_buku");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$sql = "SELECT product_id, name, stock, price FROM products ORDER BY name ASC";
$result = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Stok</title>
    <style>
        body { font-family: Arial; font-size: 14px; padding: 20px; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #333; padding: 8px; }
        th { background: #eee; }
    </style>
</head>
<body onload="window.print()">
    <h2>Laporan Stok Buku - <?= date('d M Y') ?></h2>
    <table>
        <tr>
            <th>ID Produk</th>
            <th>Nama Produk</th>
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
