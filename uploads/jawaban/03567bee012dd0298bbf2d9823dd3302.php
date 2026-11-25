<?php
require 'konek.php';

if (isset($_POST['daf'])) {
    $nama_lengkap = $_POST['namalengkap'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $konfirmasi_password = $_POST['konfirmasi_password'];

    if ($password != $konfirmasi_password) {

        echo "<script>alert('Password tidak cocok!');</script>";

    } else {

        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $simpan = $kon->prepare("INSERT INTO tb_user (nama_lengkap, username, `password`) VALUES (?, ?, ?)");

        $simpan->bind_param("sss", $nama_lengkap, $username, $password_hash);

        if ($simpan->execute()) {

            echo "<script>
            alert('Daftar berhasil!');
            window.location = 'login.php';
            </script>";

            exit;

        } else {

            if ($simpan->errno == 1062) {
                echo "<script>alert('Username sudah digunakan!');</script>";
            } else {
                echo "<script>alert('Daftar gagal!');</script>";
            }

        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Siswa</title>
    <link rel="stylesheet" href="style.css?v=<?= filemtime(__DIR__ . '/style.css') ?>">
</head>
<body>
    <div class="reg">
        <h2>Ayo daftar!</h2>

        <form method="POST">

            <label for="namalengkap">Nama lengkap:</label><br>
            <input type="text" name="namalengkap" id="namalengkap" required><br><br>

            <label for="username">Username:</label><br>
            <input type="text" name="username" id="username" required><br><br>

            <label for="password">Password:</label><br>
            <input type="password" name="password" id="password" required><br><br>

            <label for="konfirmasi_password">Konfirmasi Password:</label><br>
            <input type="password" name="konfirmasi_password" id="konfirmasi_password" required><br><br>

            <button type="submit" name="daf" class="tombol">Daftar</button>

        </form>

        <p>punya akun? <a href="login.php">Masuk</a></p>
    </div>
</body>
</html>