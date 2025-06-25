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

// Ambil data pembelian user
$sql = "
SELECT 
    u.username,
    u.email,
    o.order_id,
    o.order_date,
    p.name AS nama_produk,
    od.quantity,
    od.price,
    od.subtotal
FROM users u
JOIN orders o ON u.user_id = o.user_id
JOIN order_details od ON o.order_id = od.order_id
JOIN products p ON od.product_id = p.product_id
ORDER BY o.order_date DESC
";

$result = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan User - Litera Book Store</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 24px; background: #f0f0f5; }
        h2 { text-align: center; margin-bottom: 24px; }
        table { width: 100%; border-collapse: collapse; background-color: #fff; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        th { background-color: #1e3a8a; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .kembali {
            margin-top: 20px;
            display: inline-block;
            padding: 10px 16px;
            background: #1e3a8a;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }
        .kembali:hover {
            background: #2d4cb5;
        }
    </style>
</head>
<body>

<h2>Laporan User Pembeli Produk</h2>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Username</th>
            <th>Email</th>
            <th>ID Order</th>
            <th>Tanggal Order</th>
            <th>Nama Produk</th>
            <th>Jumlah</th>
            <th>Harga</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $no = 1;
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= $row['order_id'] ?></td>
            <td><?= date('d M Y H:i', strtotime($row['order_date'])) ?></td>
            <td><?= htmlspecialchars($row['nama_produk']) ?></td>
            <td><?= $row['quantity'] ?></td>
            <td>Rp <?= number_format($row['price'], 0, ',', '.') ?></td>
            <td>Rp <?= number_format($row['subtotal'], 0, ',', '.') ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<a href="dashboard.php" class="kembali">Dashboard</a>

</body>
</html>
