<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "toko_buku";

$koneksi = new mysqli($host, $user, $password, $database);
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$tanggal = $_GET['tanggal'] ?? date('Y-m-d');

$sql = "
SELECT o.order_id, o.order_date, p.name AS nama_produk, od.quantity, od.price, od.subtotal
FROM orders o
JOIN order_details od ON o.order_id = od.order_id
JOIN products p ON od.product_id = p.product_id
WHERE DATE(o.order_date) = ?
ORDER BY o.order_date ASC
";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("s", $tanggal);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Harian</title>
    <style>
        body { font-family: Arial; font-size: 14px; padding: 20px; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #333; padding: 8px; }
        th { background: #eee; }
        .total { font-weight: bold; text-align: right; }
    </style>
</head>
<body onload="window.print()">
    <h2>Laporan Harian Tanggal <?= date('d M Y', strtotime($tanggal)) ?></h2>
    <table>
        <tr>
            <th>No</th>
            <th>ID Order</th>
            <th>Jam</th>
            <th>Produk</th>
            <th>Qty</th>
            <th>Harga</th>
            <th>Subtotal</th>
        </tr>
        <?php 
        $no = 1;
        $total = 0;
        while ($row = $result->fetch_assoc()):
            $total += $row['subtotal'];
        ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $row['order_id'] ?></td>
            <td><?= date('H:i:s', strtotime($row['order_date'])) ?></td>
            <td><?= htmlspecialchars($row['nama_produk']) ?></td>
            <td><?= $row['quantity'] ?></td>
            <td>Rp <?= number_format($row['price'], 0, ',', '.') ?></td>
            <td>Rp <?= number_format($row['subtotal'], 0, ',', '.') ?></td>
        </tr>
        <?php endwhile; ?>
        <tr>
            <td colspan="6" class="total">Total:</td>
            <td class="total">Rp <?= number_format($total, 0, ',', '.') ?></td>
        </tr>
    </table>
</body>
</html>
