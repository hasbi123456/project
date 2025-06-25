<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Koneksi database
$host = "localhost";
$user = "root";
$password = "";
$database = "toko_buku";
$koneksi = new mysqli($host, $user, $password, $database);

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Ambil data keranjang dari session
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Keranjang Belanja</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f7f7f7; padding: 20px; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: center; }
        th { background-color: #3498db; color: white; }
        h2 { text-align: center; margin-bottom: 20px; }
        .btn { padding: 8px 14px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; }
        .btn-delete { background-color: #e74c3c; color: white; }
        .btn-proses { background-color: #2ecc71; color: white; float: right; margin-top: 20px; }
        .total { font-weight: bold; font-size: 18px; text-align: right; padding-top: 10px; }
        .back { margin-bottom: 20px; display: inline-block; background: #777; color: white; padding: 8px 12px; border-radius: 5px; text-decoration: none; }
    </style>
</head>
<body>

<a href="beranda.php" class="back">← Kembali</a>
<h2>Keranjang Belanja Anda</h2>

<?php if (empty($cart)): ?>
    <p>Keranjang masih kosong.</p>
<?php else: ?>
    <table>
        <tr>
            <th>Nama Produk</th>
            <th>Harga Satuan</th>
            <th>Jumlah</th>
            <th>Subtotal</th>
            <th>Aksi</th>
        </tr>

        <?php
        $total = 0;
        foreach ($cart as $index => $item):
            $product_id = (int)$item['product_id'];
            $quantity = (int)$item['quantity'];

            // Ambil data produk dari DB
            $stmt = $koneksi->prepare("SELECT name, price FROM products WHERE product_id = ?");
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0):
                $product = $result->fetch_assoc();
                $name = htmlspecialchars($product['name']);
                $price = (float)$product['price'];
                $subtotal = $price * $quantity;
                $total += $subtotal;
        ?>
            <tr>
                <td><?= $name ?></td>
                <td>Rp <?= number_format($price, 0, ',', '.') ?></td>
                <td><?= $quantity ?></td>
                <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                <td>
                    <a href="hapus_keranjang.php?index=<?= $index ?>" class="btn btn-delete" onclick="return confirm('Yakin ingin menghapus item ini?')">Hapus</a>
                </td>
            </tr>
        <?php
            endif;
            $stmt->close();
        endforeach;
        ?>
    </table>

    <div class="total">Total: Rp <?= number_format($total, 0, ',', '.') ?></div>
    

    <form action="checkout.php" method="POST">
        <button type="submit" class="btn btn-proses"> Beli</button>
    </form>
<?php endif; ?>

</body>
</html>
