<?php
session_start();
require 'function.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['movie_id']) || !is_numeric($_GET['movie_id'])) {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$movie_id = (int)$_GET['movie_id'];

addToWishlist($conn, $user_id, $movie_id);

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;

?>
