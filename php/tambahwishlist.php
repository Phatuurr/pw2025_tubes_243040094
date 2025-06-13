<?php
session_start();
require 'function.php';

// Keamanan: Cek jika pengguna belum login, arahkan ke halaman login.
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Keamanan: Pastikan ada movie_id yang dikirim dan itu adalah angka.
if (!isset($_GET['movie_id']) || !is_numeric($_GET['movie_id'])) {
    // Jika tidak ada, kembalikan ke halaman utama untuk mencegah error.
    header("Location: home.php");
    exit;
}

// Ambil ID pengguna dari session dan ID film dari URL.
$user_id = $_SESSION['user_id'];
$movie_id = (int)$_GET['movie_id'];

// Panggil fungsi addToWishlist dari function.php untuk memproses ke database.
addToWishlist($conn, $user_id, $movie_id);

// Arahkan pengguna kembali ke halaman tempat mereka mengklik tombol.
// Ini membuat tampilan halaman ter-refresh dan tombol akan berubah.
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;

?>
