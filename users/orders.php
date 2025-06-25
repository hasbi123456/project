<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
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

$username = $_SESSION['username'];

// Ambil user_id berdasarkan username
$stmt = $koneksi->prepare("SELECT user_id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['user_id'];

// Ambil pesanan milik user
$sql = "
    SELECT o.order_id, o.order_date, o.status, o.total_amount, 
           p.name AS product_name, od.quantity, od.subtotal
    FROM orders o
    JOIN order_details od ON o.order_id = od.order_id
    JOIN products p ON od.product_id = p.product_id
    WHERE o.user_id = ?
    ORDER BY o.order_date DESC
";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesanan Saya</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        h2 { text-align: center; }

        .btn-kembali {
            display: inline-block;
            margin-bottom: 20px;
            background-color: #000;
            color: white;
            padding: 10px 16px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .btn-kembali:hover {
            background-color: #222;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-top: 10px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }

        th {
            background-color: #333;
            color: white;
        }

        .btn-hapus {
            background-color: #d9534f;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-hapus:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>

<h2>Riwayat Pesanan Anda</h2>

<div style="text-align: center;">
    <a href="beranda.php" class="btn-kembali">← Kembali ke Beranda</a>
</div>

<table>
    <thead>
        <tr>
            <th>ID Pesanan</th>
            <th>Nama Produk</th>
            <th>Jumlah</th>
            <th>Subtotal</th>
            <th>Total Pesanan</th>
            <th>Status</th>
            <th>Tanggal</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($orders->num_rows > 0): ?>
            <?php while($row = $orders->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['order_id'] ?></td>
                    <td><?= htmlspecialchars($row['product_name']) ?></td>
                    <td><?= $row['quantity'] ?></td>
                    <td>Rp <?= number_format($row['subtotal'], 0, ',', '.') ?></td>
                    <td>Rp <?= number_format($row['total_amount'], 0, ',', '.') ?></td>
                    <td><?= ucfirst($row['status']) ?></td>
                    <td><?= $row['order_date'] ?></td>
                    <td>
                        <a class="btn-hapus" href="batalkan_pesanan.php?order_id=<?= $row['order_id'] ?>" onclick="return confirm('Yakin ingin menghapus pesanan ini?');">Batalkan Pesanan</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="8">Anda belum memiliki pesanan.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
