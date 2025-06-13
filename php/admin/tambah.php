<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}
require '../function.php';

if (isset($_POST["submit"])) {
    if (tambahFilm($_POST) > 0) {
        echo "<script>
                alert('Data film baru berhasil ditambahkan!');
                document.location.href = 'admin.php';
              </script>";
    } else {
        echo "<script>
                alert('Data film baru GAGAL ditambahkan!');
              </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Film Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5 mb-5">
    <h1>Tambah Film Baru</h1>
    <a href="index.php" class="btn btn-secondary mb-3">Kembali ke Dashboard</a>
    
    <form action="" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Film:</label>
            <input type="text" name="nama" id="nama" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="tahun" class="form-label">Tahun:</label>
            <input type="number" name="tahun" id="tahun" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="rating" class="form-label">Rating:</label>
            <input type="text" name="rating" id="rating" class="form-control" required placeholder="0.0">
        </div>
        <div class="mb-3">
            <label for="genre" class="form-label">Genre</label>
            <input type="text" name="genre" id="genre" class="form-control" required >
        </div>
        <div class="mb-3">
            <label for="trailer" class="form-label">Link Trailer (YouTube):</label>
            <input type="url" name="trailer" id="trailer" class="form-control" >
        </div>
        <div class="mb-3">
            <label for="seleksi" class="form-label">Seleksi Tampilan:</label>
            <select name="seleksi" id="seleksi" class="form-select">
                <option value="">Tidak Ada</option>
                <option value="hero">Hero (Tampil di Carousel Besar)</option>
                <option value="upcoming">Upcoming (Segera Tayang)</option>
                <option value="slider">Slider (Tampil di slider genre)</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="poster" class="form-label">Poster (Gambar Vertikal):</label>
            <input type="file" name="poster" id="poster" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="background" class="form-label">Background (Gambar Horizontal):</label>
            <input type="file" name="background" id="background" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="sinopsis" class="form-label">Sinopsis:</label>
            <textarea name="sinopsis" id="sinopsis" class="form-control" rows="5"></textarea>
        </div>
        <div class="mb-3">
            <label for="studio" class="form-label">Studio:</label>
            <input type="text" name="studio" id="studio" class="form-control">
        </div>
        <div class="mb-3">
            <label for="director" class="form-label">Director:</label>
            <input type="text" name="director" id="director" class="form-control">
        </div>
        <div class="mb-3">
            <label for="stars" class="form-label">Stars</label>
            <input type="text" name="stars" id="stars" class="form-control">
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Tambah Data</button>
    </form>
</div>

</body>
</html>
