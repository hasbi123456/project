<?php
session_start();
require '../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['order_id']) || !is_numeric($_GET['order_id'])) {
    echo "ID pesanan tidak valid.";
    exit;
}

$order_id = intval($_GET['order_id']);

// Ambil detail pesanan
$query = "
    SELECT p.name, d.quantity, d.price, d.subtotal
    FROM order_details d
    JOIN products p ON d.product_id = p.product_id
    WHERE d.order_id = ?
";

$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Pesanan</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background-color: #f9f9f9; }
        h2 { text-align: center; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background-color: #fff; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background-color: #f0f0f0; }
        a { display: inline-block; margin-top: 20px; text-decoration: none; background: #2980b9; color: #fff; padding: 10px 15px; border-radius: 5px; }
        a:hover { background: #1c5980; }
    </style>
</head>
<body>
    <h2>Detail Pesanan #<?= htmlspecialchars($order_id) ?></h2>
    <table>
        <tr>
            <th>Nama Produk</th>
            <th>Jumlah</th>
            <th>Harga</th>
            <th>Subtotal</th>
        </tr>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= $row['quantity'] ?></td>
                <td>Rp <?= number_format($row['price'], 0, ',', '.') ?></td>
                <td>Rp <?= number_format($row['subtotal'], 0, ',', '.') ?></td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4">Tidak ada detail pesanan ditemukan.</td></tr>
        <?php endif; ?>
    </table>
    <a href="pesanan.php">&#8592; Kembali ke Daftar Pesanan</a>
</body>
</html>