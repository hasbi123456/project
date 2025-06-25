<?php
session_start();
require '../koneksi.php';

// Cek jika admin sudah login
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Ambil data pesanan dari database
$query = "
    SELECT o.order_id, u.username, o.order_date, o.status, o.total_amount, o.shipping_address, o.phone
    FROM orders o
    JOIN users u ON o.user_id = u.user_id
    ORDER BY o.order_date DESC
";
$result = $koneksi->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pesanan</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        h2 { text-align: center; color: #333; margin-bottom: 10px; }
        .top-bar {
            width: 95%;
            max-width: 1100px;
            margin: 0 auto 10px auto;
            display: flex;
            justify-content: flex-end;
        }
        .btn-dashboard {
            background-color: #27ae60;
            color: white;
            padding: 7px 15px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn-dashboard:hover {
            background-color: #1e8449;
        }
        table {
            width: 95%;
            max-width: 1100px;
            border-collapse: collapse;
            background-color: #fff;
            margin: 0 auto 20px auto;
            font-size: 14px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px 6px;
            text-align: center;
            vertical-align: middle;
        }
        th {
            background-color: #f0f0f0;
        }
        a.btn, button.btn {
            display: inline-block;
            background-color: #2980b9;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 13px;
            margin: 2px;
            border: none;
            cursor: pointer;
        }
        a.btn:hover, button.btn:hover {
            background-color: #1c5980;
        }
        button.btn-danger {
            background-color: #c0392b;
        }
        button.btn-danger:hover {
            background-color: #7a2115;
        }
        form { display: inline; }
    </style>
</head>
<body>
    <h2>Daftar Pesanan</h2>

    <div class="top-bar">
        <a href="dashboard.php" class="btn-dashboard">Kembali ke Dashboard</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID Pesanan</th>
                <th>Username</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Total</th>
                <th>Alamat</th>
                <th>No Telepon</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['order_id']) ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['order_date']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td>Rp <?= number_format($row['total_amount'], 0, ',', '.') ?></td>
                <td><?= htmlspecialchars($row['shipping_address']) ?></td>
                <td><?= htmlspecialchars($row['phone']) ?></td>
                <td>
                    <a class="btn" href="detail_pesanan.php?order_id=<?= urlencode($row['order_id']) ?>">Lihat Detail</a>
                    <?php if (strtolower($row['status']) != 'dibatalkan' && strtolower($row['status']) != 'selesai'): ?>
                        <form method="post" action="batalkan_pesanan.php" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?');">
                            <input type="hidden" name="order_id" value="<?= htmlspecialchars($row['order_id']) ?>">
                            <button type="submit" class="btn btn-danger">Batalkan</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="8">Belum ada pesanan.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
