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

$bulan_ini = date('Y-m');

$sql = "
SELECT o.order_id, o.order_date, p.name AS nama_produk, od.quantity, od.price, od.subtotal
FROM orders o
JOIN order_details od ON o.order_id = od.order_id
JOIN products p ON od.product_id = p.product_id
WHERE DATE_FORMAT(o.order_date, '%Y-%m') = ?
ORDER BY o.order_date DESC
";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("s", $bulan_ini);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Bulanan - Litera Book Store</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 24px; background: #f4f4f4; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td { padding: 12px; border: 1px solid #ccc; }
        th { background-color: #1e3a8a; color: white; }
        tr:nth-child(even) { background: #f9f9f9; }
        .total { text-align: right; font-weight: bold; }
        .kembali { margin-top: 20px; display: inline-block; background: #1e3a8a; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; }
    </style>
</head>
<body>
    <h2>Laporan Penjualan Bulan <?= date('F Y') ?></h2>
    <table>
        <tr>
            <th>No</th>
            <th>ID Order</th>
            <th>Tanggal</th>
            <th>Nama Produk</th>
            <th>Jumlah</th>
            <th>Harga</th>
            <th>Subtotal</th>
        </tr>
        <?php 
        $no = 1; $total = 0;
        while ($row = $result->fetch_assoc()):
            $total += $row['subtotal'];
        ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $row['order_id'] ?></td>
            <td><?= date('d M Y H:i', strtotime($row['order_date'])) ?></td>
            <td><?= htmlspecialchars($row['nama_produk']) ?></td>
            <td><?= $row['quantity'] ?></td>
            <td>Rp <?= number_format($row['price'], 0, ',', '.') ?></td>
            <td>Rp <?= number_format($row['subtotal'], 0, ',', '.') ?></td>
        </tr>
        <?php endwhile; ?>
        <tr>
            <td colspan="6" class="total">Total Bulan Ini:</td>
            <td class="total">Rp <?= number_format($total, 0, ',', '.') ?></td>
        </tr>
    </table>
    <a href="dashboard.php" class="kembali">←</a>
</body>
</html>
