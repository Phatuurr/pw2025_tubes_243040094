<?php
session_start();
require 'function.php';

// Keamanan: Cek jika pengguna belum login.
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Keamanan: Pastikan ada movie_id yang dikirim.
if (!isset($_GET['movie_id']) || !is_numeric($_GET['movie_id'])) {
    header("Location: home.php");
    exit;
}

// Ambil ID pengguna dan ID film.
$user_id = $_SESSION['user_id'];
$movie_id = (int)$_GET['movie_id'];

// Panggil fungsi removeFromWishlist dari function.php.
removeFromWishlist($conn, $user_id, $movie_id);

// Arahkan kembali ke halaman sebelumnya.
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;

?>
