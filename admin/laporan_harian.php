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

$tanggal = $_GET['tanggal'] ?? date('Y-m-d');

// Query ambil transaksi sesuai tanggal yang dipilih
$sql = "
SELECT 
    o.order_id,
    o.order_date,
    p.name AS nama_produk,
    od.quantity,
    od.price,
    od.subtotal
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
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Harian - Litera Book Store</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 24px; background: #f4f4f4; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; background: #fff; margin-top: 16px; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        th { background-color: #1e3a8a; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .form-filter { margin-bottom: 16px; display: flex; gap: 12px; align-items: center; flex-wrap: wrap; }
        .form-filter input[type="date"] { padding: 6px; font-size: 14px; }
        .form-filter button, .btn-cetak, .kembali {
            background-color: #1e3a8a;
            color: white;
            border: none;
            padding: 8px 16px;
            font-size: 14px;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
        }
        .form-filter button:hover, .btn-cetak:hover, .kembali:hover {
            background-color: #2f4cb5;
        }
        .total { text-align: right; font-weight: bold; padding: 12px; }
    </style>
</head>
<body>

    <h2>Laporan Penjualan Harian</h2>

    <form method="GET" class="form-filter">
        <label for="tanggal">Pilih Tanggal:</label>
        <input type="date" name="tanggal" id="tanggal" value="<?= htmlspecialchars($tanggal) ?>" required>
        <button type="submit">Tampilkan</button>
        <a class="btn-cetak" target="_blank" href="cetak_laporan_harian.php?tanggal=<?= $tanggal ?>">🖨 Cetak</a>
        <a href="dashboard.php" class="kembali">←</a>
    </form>

    <?php if ($result && $result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID Order</th>
                    <th>Waktu</th>
                    <th>Nama Produk</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
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
                    <td colspan="6" class="total">Total Pendapatan:</td>
                    <td class="total">Rp <?= number_format($total, 0, ',', '.') ?></td>
                </tr>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align:center;">Tidak ada transaksi pada tanggal ini.</p>
    <?php endif; ?>

</body>
</html>
