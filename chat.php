<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: ../login.php");
    exit;
}

$koneksi = new mysqli("localhost", "root", "", "toko_buku");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['message'])) {
    $user = $_SESSION['username'];
    $msg = $koneksi->real_escape_string($_POST['message']);
    $koneksi->query("INSERT INTO chat (username, message) VALUES ('$user', '$msg')");
}

// Ambil semua pesan
$pesan = $koneksi->query("SELECT * FROM chat ORDER BY timestamp ASC");

// Tentukan URL kembali berdasarkan role
$backURL = ($_SESSION['role'] === 'admin') ? 'admin/dashboard.php' : 'users/beranda.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Live Chat - Litera Book Store</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            margin: 0;
            padding: 0;
        }

        .chat-container {
            max-width: 700px;
            margin: 0 auto;
            padding: 24px;
            background: white;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .chat-header {
            font-size: 24px;
            font-weight: bold;
            color: #1e3a8a;
            margin-bottom: 16px;
            text-align: center;
        }

        .chat-box {
            flex: 1;
            overflow-y: auto;
            border: 1px solid #ccc;
            padding: 16px;
            margin-bottom: 12px;
            background: #f9f9f9;
        }

        .chat-message {
            margin-bottom: 10px;
            max-width: 70%;
            padding: 10px 14px;
            border-radius: 12px;
            background-color: #e0e0e0;
            word-wrap: break-word;
        }

        .chat-message.you {
            margin-left: auto;
            background-color: #1e3a8a;
            color: white;
        }

        .chat-form {
            display: flex;
            gap: 8px;
        }

        .chat-form input {
            flex: 1;
            padding: 10px;
            font-size: 14px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .chat-form button {
            padding: 10px 16px;
            background-color: #1e3a8a;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .chat-form button:hover {
            background-color: #324cb3;
        }

        .back-button {
            background-color: #1e3a8a;
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 12px;
        }

        .back-button:hover {
            background-color: #324cb3;
        }

        .timestamp {
            font-size: 11px;
            color: #666;
            margin-top: 2px;
        }
    </style>
</head>
<body>

<div class="chat-container">
    <div class="chat-header">Live Chat</div>

    <div class="chat-box" id="chat-box">
        <?php while ($row = $pesan->fetch_assoc()): ?>
            <div class="chat-message <?= $row['username'] === $_SESSION['username'] ? 'you' : '' ?>">
                <strong><?= htmlspecialchars($row['username']) ?>:</strong> <?= htmlspecialchars($row['message']) ?>
                <div class="timestamp"><?= date('H:i', strtotime($row['timestamp'])) ?></div>
            </div>
        <?php endwhile; ?>
    </div>

    <form class="chat-form" method="POST">
        <input type="text" name="message" placeholder="Tulis pesan..." required autocomplete="off" />
        <button type="submit">Kirim</button>
    </form>

    <form action="<?= $backURL ?>" method="get">
        <button type="submit" class="back-button">Kembali</button>
    </form>
</div>

<script>
    const chatBox = document.getElementById('chat-box');
    chatBox.scrollTop = chatBox.scrollHeight;
</script>

</body>
</html>
