<?php
// koneksi ke database
$host = "localhost";
$user = "root";
$password = "";
$database = "toko_buku";
$koneksi = new mysqli($host, $user, $password, $database);

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$sql = "SELECT name, description, price, image_url, stock FROM products";
$result = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>LiteraNusa - Toko Buku Digital</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: #d3d6db;
      margin: 0;
      padding: 0;
      color: #333;
    }

    header {
      background-color: #2541b2; /* warna biru diperkecil jadi lebih kalem */
      color: white;
      padding: 12px 32px; /* padding diperkecil dari 20px ke 12px agar header lebih pendek */
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      flex-wrap: wrap;
    }

    .logo-area {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      gap: 8px; /* jarak dikurangi supaya lebih compact */
    }

    .logo-container {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .logo-img {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      object-fit: cover;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .title {
      font-size: 24px;
      font-weight: 600;
      line-height: 1; /* rapatkan line-height */
    }

    nav.nav-links {
      display: flex;
      gap: 18px;
      flex-wrap: wrap;
      margin-left: 60px; /* tetap geser kanan */
      margin-top: -8px; /* geser ke atas 8px */
    }

    .nav-links a {
      color: white;
      text-decoration: none;
      font-weight: 500;
      transition: color 0.3s;
      padding: 6px 8px; /* kasih padding supaya tombol terasa clickable */
      border-radius: 6px;
      font-size: 15px; /* sedikit diperkecil font */
    }

    .nav-links a:hover {
      color: #93c5fd;
      background-color: rgba(255, 255, 255, 0.15); /* hover ada latar supaya jelas */
    }

    .btn-login {
      background-color: white;
      color: #2541b2;
      border: none;
      padding: 8px 16px; /* padding dikurangi */
      border-radius: 8px;
      font-weight: 600;
      text-decoration: none;
      transition: background 0.3s;
      font-size: 15px;
      margin-top: 4px; /* naikkan sedikit supaya sejajar */
    }

    .btn-login:hover {
      background-color: #dbeafe;
    }

    main {
      padding: 24px;
      max-width: 1200px;
      margin: 0 auto;
    }

    h1 {
      text-align: center;
      font-size: 32px;
      color: #000;
      margin-bottom: 32px;
    }

    .produk-list {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 24px;
    }

    .produk-item {
      background: #e4e4e4;
      overflow: hidden;
      height: 520px;
      display: flex;
      flex-direction: column;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .produk-item img {
      width: 100%;
      height: 60%;
      object-fit: cover;
      display: block;
    }

    .produk-content {
      padding: 16px;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      color: #000;
    }

    .produk-item h3 {
      margin: 0;
      font-size: 18px;
      color: #000;
    }

    .produk-item p {
      margin: 8px 0;
      color: #333;
      font-size: 14px;
      flex-grow: 1;
      overflow: hidden;
      text-overflow: ellipsis;
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
    }

    .harga {
      font-weight: 600;
      color: #000;
      font-size: 16px;
      margin-top: 8px;
    }

    .stok {
      font-size: 13px;
      color: #64748b;
      margin-top: 4px;
    }

    @media (max-width: 1024px) {
      .produk-list {
        grid-template-columns: repeat(3, 1fr);
      }
    }

    @media (max-width: 768px) {
      .produk-list {
        grid-template-columns: repeat(2, 1fr);
      }

      header {
        flex-direction: column;
        align-items: stretch;
        gap: 12px;
        padding: 12px 20px;
      }

      .btn-login {
        align-self: flex-end;
        margin-top: 0;
      }

      .logo-area {
        align-items: flex-start;
      }

      nav.nav-links {
        margin-left: 0; /* reset agar tidak bergeser di mobile */
        margin-top: 0;  /* reset margin-top juga */
      }
    }

    @media (max-width: 480px) {
      .produk-list {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>

<header>
  <div class="logo-area">
    <div class="logo-container">
      <img src="assets/images/60.JPG" alt="Logo LiteraNusa" class="logo-img" />
      <div class="title">Litera Book Store</div>
    </div>
    <nav class="nav-links">
      <a href="#">Beranda</a>
      <a href="#tentang">Tentang Kami</a>
      <a href="#kontak">Kontak</a>
    </nav>
  </div>
  <a href="login.php" class="btn-login">Login</a>
</header>

<main>
  <h1>Temukan Buku Favoritmu</h1>
  <div class="produk-list">
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while($row = $result->fetch_assoc()): ?>
        <div class="produk-item">
          <img src="<?= 'assets/images/' . htmlspecialchars($row['image_url']) ?>" alt="gambar buku" />
          <div class="produk-content">
            <h3><?= htmlspecialchars($row['name']) ?></h3>
            <p><?= htmlspecialchars($row['description']) ?></p>
            <div class="harga">Rp <?= number_format($row['price'], 0, ',', '.') ?></div>
            <div class="stok">Stok: <?= (int)$row['stock'] ?></div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p style="text-align:center;">Tidak ada produk ditemukan.</p>
    <?php endif; ?>
  </div>
</main>

</body>
</html>
