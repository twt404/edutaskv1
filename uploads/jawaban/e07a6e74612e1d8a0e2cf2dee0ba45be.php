<?php
session_start();
require 'koneksi.php';

if (isset($_SESSION['id_user'])) {
    header("Location: index.php");
    exit;
}

$error = "";
$username_val = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $username_val = $username;

    if ($username === "" || $password === "") {
        $error = "Username dan password wajib diisi.";
    } else {
        $stmt = mysqli_prepare(
            $koneksi,
            "SELECT id_user, nama_lengkap, username, password FROM tb_user WHERE username = ?"
        );
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['id_user'] = (int) $user['id_user'];
            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
            $_SESSION['username'] = $user['username'];
            mysqli_stmt_close($stmt);
            header("Location: index.php");
            exit;
        } else {
            $error = "Username atau password salah.";
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Aplikasi Perpustakaan</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container auth-box">
        <h2>LOGIN APLIKASI PERPUSTAKAAN SEKOLAH</h2>
        <p class="subtitle">Masuk untuk mengelola data koleksi buku perpustakaan.</p>

        <?php if ($error !== ""): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" maxlength="50"
                    value="<?= htmlspecialchars($username_val) ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Masuk / Login</button>
        </form>

        <div class="auth-footer">
            Belum punya akun? <a href="register.php">Daftar di sini</a>
        </div>
    </div>
</body>

</html>