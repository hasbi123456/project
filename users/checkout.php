<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
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

// Kalau ada product_id di POST, berarti beli 1 produk dari beranda
if (isset($_POST['product_id'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8" />
        <title>Checkout Produk</title>
        <style>
            body {
                font-family: 'Segoe UI', sans-serif;
                background-color: #d3d6db;
                margin: 0;
                padding: 40px;
            }
            h2 {
                text-align: center;
                margin-bottom: 30px;
                color: #000;
            }
            form {
                background-color: #fff;
                padding: 24px;
                border-radius: 8px;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
                width: 400px;
                margin: 0 auto;
            }
            label {
                display: block;
                margin-bottom: 8px;
                font-weight: 600;
            }
            input[type="number"],
            textarea,
            input[type="text"] {
                width: 100%;
                padding: 8px;
                border-radius: 5px;
                border: 1px solid #aaa;
                font-size: 16px;
                margin-bottom: 16px;
                box-sizing: border-box;
            }
            button {
                background-color: #000;
                color: white;
                padding: 10px 18px;
                border-radius: 5px;
                font-size: 16px;
                border: none;
                cursor: pointer;
                width: 100%;
            }
            button:hover {
                background-color: #222;
            }
        </style>
    </head>
    <body>
    <h2>Form Checkout</h2>
    <form action="proses_order.php" method="POST">
        <input type="hidden" name="product_id" value="<?= htmlspecialchars($product_id) ?>">
        <label for="quantity">Jumlah:</label>
        <input type="number" name="quantity" id="quantity" value="<?= $quantity ?>" min="1" required>
        <label for="shipping_address">Alamat Pengiriman:</label>
        <textarea name="shipping_address" id="shipping_address" rows="4" required></textarea>
        <label for="phone">No. Telepon:</label>
        <input type="text" name="phone" id="phone" required>
        <button type="submit">Pesan Sekarang</button>
    </form>
    </body>
    </html>
    <?php
    exit;
}

// Kalau tidak ada product_id, berarti checkout dari keranjang
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
if (empty($cart)) {
    echo "<p>Keranjang kosong. Silakan tambahkan produk dulu.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Checkout Keranjang</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #d3d6db;
            margin: 0;
            padding: 40px;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #000;
        }
        form {
            background-color: #fff;
            padding: 24px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
            width: 400px;
            margin: 0 auto;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }
        textarea,
        input[type="text"] {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #aaa;
            font-size: 16px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }
        button {
            background-color: #000;
            color: white;
            padding: 10px 18px;
            border-radius: 5px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #222;
        }
    </style>
</head>
<body>
<h2>Form Checkout Keranjang</h2>
<form action="proses_order.php" method="POST">
    <label for="shipping_address">Alamat Pengiriman:</label>
    <textarea name="shipping_address" id="shipping_address" rows="4" required></textarea>
    <label for="phone">No. Telepon:</label>
    <input type="text" name="phone" id="phone" required>
    <button type="submit">Pesan Sekarang</button>
</form>
</body>
</html>
