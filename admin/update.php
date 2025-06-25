<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

require '../koneksi.php';

// Ambil data pesanan dari database
$sql = "
    SELECT o.order_id, u.username, o.order_date, o.status, o.total_amount, o.shipping_address, o.phone
    FROM orders o
    JOIN users u ON o.user_id = u.user_id
    ORDER BY o.order_date DESC
";
$result = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Update Status Pesanan</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        h2 { text-align: center; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; background-color: white; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        th { background-color: #f0f0f0; }
        form { margin: 0; }
        select { padding: 5px; }
        button[type=submit] {
            padding: 6px 12px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 4px;
            background-color: #27ae60;
            color: white;
            border: none;
        }
        button[type=submit]:hover {
            background-color: #1e8449;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            background: #3498db;
            color: white;
            padding: 8px 14px;
            text-decoration: none;
            border-radius: 4px;
        }
        .back-link:hover {
            background: #2c80b4;
        }
    </style>
</head>
<body>

<a href="dashboard.php" class="back-link">← Kembali ke Dashboard</a>
<h2>Update Status Pesanan</h2>

<?php if ($result && $result->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Username</th>
                <th>Tanggal</th>
                <th>Alamat</th>
                <th>Telepon</th>
                <th>Total</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['order_id']) ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['order_date']) ?></td>
                    <td><?= htmlspecialchars($row['shipping_address']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                    <td>Rp <?= number_format($row['total_amount'], 0, ',', '.') ?></td>
                    <td>
                        <form action="update_status_pesanan.php" method="POST">
                            <input type="hidden" name="order_id" value="<?= $row['order_id'] ?>">
                            <select name="status" required>
                                <option value="pending" <?= $row['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="processing" <?= $row['status'] == 'processing' ? 'selected' : '' ?>>Processing</option>
                                <option value="shipped" <?= $row['status'] == 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                <option value="completed" <?= $row['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                                <option value="cancelled" <?= $row['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                    </td>
                    <td>
                            <button type="submit">Update</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p style="text-align:center;">Tidak ada pesanan ditemukan.</p>
<?php endif; ?>

</body>
</html>
