<?php
// 1. Mulai sesi untuk mengakses dan memanipulasinya.
session_start();

// 2. Kosongkan semua data dalam array $_SESSION.
$_SESSION = [];

// 3. Hapus semua variabel sesi yang terdaftar.
session_unset();

// 4. Hancurkan sesi secara permanen di server.
session_destroy();

// 5. Arahkan pengguna kembali ke halaman login setelah sesi dihancurkan.
header("Location: login.php");
// 6. Hentikan eksekusi skrip untuk memastikan redirect berjalan.
exit;
?>