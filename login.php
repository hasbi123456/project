<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$database = "toko_buku";

$koneksi = new mysqli($host, $user, $password, $database);
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $koneksi->real_escape_string($_POST["username"]);
    $password_input = md5($_POST["password"]);

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $koneksi->query($query);

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if ($password_input === $user['password']) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: admin/dashboard.php");
                exit;
            } else {
                header("Location: users/beranda.php");
                exit;
            }
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Toko Buku</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            margin: 0;
            height: 100vh;
            background: linear-gradient(to right, #a0d8ff, #e0f7fa);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            display: flex;
            background: #fff;
            border-radius: 40px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 90%;
            max-width: 1100px;
            height: 640px;
        }

        .left-side {
            flex: 1;
            background: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 40px 0 0 40px;
        }

        .left-side img {
            max-width: 90%;
            height: auto;
            border-radius: 20px;
        }

        .right-side {
            flex: 1;
            padding: 50px 40px;
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            border-radius: 0 40px 40px 0;
        }

        .right-side h2 {
            font-size: 28px;
            margin-bottom: 24px;
            text-align: center;
        }

        form {
            width: 100%;
            max-width: 380px;
            margin: 0 auto;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 14px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 12px;
            font-size: 15px;
        }

        .forgot {
            text-align: right;
            margin-bottom: 16px;
        }

        .forgot a {
            text-decoration: none;
            color: #1a73e8;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 14px;
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            margin-bottom: 16px;
            transition: 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        .social-login {
            display: flex;
            flex-direction: column;
            gap: 12px;
            width: 100%;
            max-width: 380px;
            margin: 0 auto;
        }

        .social-login button {
            background-color: #fff;
            border: 1px solid #ccc;
            color: #333;
            padding: 12px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 14px;
            cursor: pointer;
        }

        .social-login img {
            height: 20px;
        }

        .error {
            color: red;
            margin-bottom: 10px;
            text-align: center;
        }

        .register {
            text-align: center;
            margin-top: 18px;
        }

        .register a {
            color: #1a73e8;
            text-decoration: none;
            font-weight: bold;
        }

        @media screen and (max-width: 768px) {
            .container {
                flex-direction: column;
                border-radius: 30px;
                height: auto;
            }

            .left-side, .right-side {
                flex: unset;
                width: 100%;
                border-radius: 0;
            }

            .left-side {
                padding: 20px;
            }

            .right-side {
                padding: 30px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="left-side">
        <img src="assets/images/34.PNG" alt="Ilustrasi Login">
    </div>

    <div class="right-side">
        <h2>Masuk Akun Toko Buku</h2>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Kata Sandi" required>
            <div class="forgot"><a href="#">Lupa Kata Sandi</a></div>
            <button type="submit">Masuk</button>
        </form>

        <div class="social-login">
            <button><img src="assets/images/20.JPG" alt="Google"> Masuk dengan Google</button>
            <button><img src="assets/images/30.JPG" alt="MyValue"> Masuk dengan MyValue</button>
        </div>

        <div class="register">
            Belum punya akun? <a href="register.php">Daftar</a>
        </div>
    </div>
</div>

</body>
</html>
