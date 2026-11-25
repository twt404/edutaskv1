<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?v=<?= filemtime(__DIR__ . '/style.css') ?>">
</head>
<body>

<div class="tlog">
    <h2 class="jenengelog">Login inventaris LAB</h2>

    <br>

    <form method="POST">
        <label for="username">Masukkan username Anda</label>
        <br>
        <input type="text" name="username" id="username" placeholder="Masukkan username" required>

        <br><br>

        <label for="password" class="spasi">Masukkan password Anda</label>
        <br>
        <input type="password" name="password" id="password" placeholder="Masukkan password" required>

        <br><br>

        <button type="submit" name="login" class="tombol">Login</button>

        <p>Tidak punya akun? <a href="register.php">Daftar</a></p>
    </form>
</div>
</body>
</html>


<?php
require 'konek.php';

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = $kon->prepare(
        "SELECT * FROM tb_user WHERE username = ?"
    );

    $query->bind_param("s", $username); 
    $query->execute();

    $result = $query->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {

        echo "<script>
        alert('Login berhasil!');
        window.location='index.php';
        </script>";

    } else {

        echo "<script>
        alert('Login gagal!');
        </script>";

    }
}
?>