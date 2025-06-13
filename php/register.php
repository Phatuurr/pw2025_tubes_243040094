<?php

require 'function.php';

if(isset($_POST["register"])) {

    // Fungsi registrasi akan mengembalikan nilai berdasarkan jumlah baris yang terpengaruh
    // > 0 berarti berhasil, <= 0 berarti gagal
    if(registrasi($_POST) > 0) {
        echo "<script>
                document.location.href = 'berhasil.php'; // Arahkan ke halaman login setelah sukses
              </script>";
    } else {
        // INI BAGIAN YANG DIPERBAIKI
        // Jangan panggil mysqli_error() di sini.
        // Cukup tampilkan pesan gagal umum, karena pesan spesifik
        // seperti "username sudah ada" sudah di-handle di dalam fungsi registrasi().
        echo "<script>
                alert('Gagal menambahkan user baru! Silakan periksa kembali data Anda.');
              </script>";
    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../Image/favicon.png">
    <title>SceneSide</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/regwister.css">
</head>

<body>
    <div class="container d-flex">
        <div class="register">
            <img src="../Image/logo web white.png" width="100px" alt="">
            <h1>CREATE ACCOUNT</h1>
            <form action="" method="post">
                <h3>Email</h3>
                <input class="email" type="email" name="email" id="email" placeholder="Email" required autocomplete="off">
                <br>
                <h3>Username</h3>
                <input class="username" type="username" name="username" id="username" placeholder="Username" required autocomplete="off">
                <br>
                <h3 class="label">Password</h3>
                <input class="pass" type="password" name="password" id="password" placeholder="Password" required autocomplete="off">
                <br>
                <h3 class="label">Confirm Password</h3>
                <input class="pass" type="password" name="password2" id="password2" placeholder="Password" required autocomplete="off">
                <br>
                <button class="submit" type="submit" name="register" id="register">REGISTER</button>
            </form>
        </div>




    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>

</body>

</html>