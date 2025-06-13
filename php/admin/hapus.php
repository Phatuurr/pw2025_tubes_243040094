<?php
session_start();

// Keamanan: Pastikan hanya admin yang bisa mengakses
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require '../function.php';

// Ambil ID dari URL
$id = $_GET["id"];

if (hapusFilm($id) > 0) {
    echo "
        <script>
            alert('Data film berhasil dihapus!');
            document.location.href = 'index.php';
        </script>
    ";
} else {
    echo "
        <script>
            alert('Data film GAGAL dihapus!');
            document.location.href = 'index.php';
        </script>
    ";
}
?>