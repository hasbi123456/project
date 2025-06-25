<?php
session_start();
if (!isset($_SESSION['username'])) {
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_SESSION['username'];
    $shipping_address = $koneksi->real_escape_string($_POST['shipping_address'] ?? '');
    $phone = $koneksi->real_escape_string($_POST['phone'] ?? '');

    if (empty($shipping_address) || empty($phone)) {
        die("Alamat pengiriman dan nomor telepon harus diisi.");
    }

    // Dapatkan user_id
    $stmtUser = $koneksi->prepare("SELECT user_id FROM users WHERE username = ?");
    $stmtUser->bind_param("s", $username);
    $stmtUser->execute();
    $resultUser = $stmtUser->get_result();
    if ($resultUser->num_rows === 0) {
        die("User tidak ditemukan.");
    }
    $user = $resultUser->fetch_assoc();
    $user_id = $user['user_id'];

    // Mulai transaksi supaya data konsisten
    $koneksi->begin_transaction();

    try {
        // Hitung total_amount dari keranjang atau dari produk tunggal
        $total_amount = 0;
        $items = [];

        if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
            // Pembelian produk tunggal langsung
            $product_id = intval($_POST['product_id']);
            $quantity = intval($_POST['quantity']);

            $stmtProduct = $koneksi->prepare("SELECT price FROM products WHERE product_id = ?");
            $stmtProduct->bind_param("i", $product_id);
            $stmtProduct->execute();
            $resultProduct = $stmtProduct->get_result();
            if ($resultProduct->num_rows === 0) {
                throw new Exception("Produk tidak ditemukan.");
            }
            $product = $resultProduct->fetch_assoc();
            $price = $product['price'];
            $subtotal = $price * $quantity;

            $items[] = [
                'product_id' => $product_id,
                'quantity' => $quantity,
                'price' => $price,
                'subtotal' => $subtotal
            ];

            $total_amount += $subtotal;

        } else {
            // Pembelian dari keranjang session
            if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                throw new Exception("Keranjang kosong.");
            }

            foreach ($_SESSION['cart'] as $item) {
                $product_id = intval($item['product_id']);
                $quantity = intval($item['quantity']);

                $stmtProduct = $koneksi->prepare("SELECT price FROM products WHERE product_id = ?");
                $stmtProduct->bind_param("i", $product_id);
                $stmtProduct->execute();
                $resultProduct = $stmtProduct->get_result();
                if ($resultProduct->num_rows === 0) {
                    continue; // skip produk tidak ditemukan
                }
                $product = $resultProduct->fetch_assoc();
                $price = $product['price'];
                $subtotal = $price * $quantity;

                $items[] = [
                    'product_id' => $product_id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $subtotal
                ];

                $total_amount += $subtotal;
            }
        }

        // Insert ke tabel orders
        $stmtOrder = $koneksi->prepare("INSERT INTO orders (user_id, total_amount, shipping_address, phone) VALUES (?, ?, ?, ?)");
        $stmtOrder->bind_param("idss", $user_id, $total_amount, $shipping_address, $phone);
        $stmtOrder->execute();
        $order_id = $stmtOrder->insert_id;

        // Insert detail order
        $stmtDetail = $koneksi->prepare("INSERT INTO order_details (order_id, product_id, quantity, price, subtotal) VALUES (?, ?, ?, ?, ?)");
        foreach ($items as $item) {
            $stmtDetail->bind_param(
                "iiidd",
                $order_id,
                $item['product_id'],
                $item['quantity'],
                $item['price'],
                $item['subtotal']
            );
            $stmtDetail->execute();
        }

        // Commit transaksi
        $koneksi->commit();

        // Jika pembelian dari keranjang, kosongkan keranjang
        if (!isset($_POST['product_id'])) {
            $_SESSION['cart'] = [];
        }

        header("Location: orders.php");
        exit;

    } catch (Exception $e) {
        $koneksi->rollback();
        die("Gagal memproses pesanan: " . $e->getMessage());
    }
}

echo "Gagal memproses pesanan.";
?>
