<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}
require '../function.php';

$id = $_GET['id'];

$movie = getMovieById($id);

if (isset($_POST["submit"])) {
    if (editFilm($_POST) > 0) {
        echo "<script>alert('Data film berhasil diubah!'); document.location.href = 'admin.php';</script>";
    } else {
        echo "<script>alert('Data film GAGAL diubah atau tidak ada perubahan!'); document.location.href = 'admin.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Film - <?php echo htmlspecialchars($movie['nama']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5 mb-5">
    <h1>Edit Film</h1>
    <a href="index.php" class="btn btn-secondary mb-3">Kembali ke Dashboard</a>

    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $movie['id']; ?>">
        <input type="hidden" name="posterLama" value="<?php echo htmlspecialchars($movie['poster'] ?? ''); ?>">
        <input type="hidden" name="backgroundLama" value="<?php echo htmlspecialchars($movie['background'] ?? ''); ?>">

        <div class="mb-3">
            <label for="nama" class="form-label">Nama Film:</label>
            <input type="text" name="nama" id="nama" class="form-control" required value="<?php echo htmlspecialchars($movie['nama']); ?>">
        </div>
        <div class="mb-3">
            <label for="tahun" class="form-label">Tahun:</label>
            <input type="number" name="tahun" id="tahun" class="form-control" required value="<?php echo htmlspecialchars($movie['tahun']); ?>">
        </div>
        <div class="mb-3">
            <label for="rating" class="form-label">Rating:</label>
            <input type="text" name="rating" id="rating" class="form-control" required value="<?php echo htmlspecialchars($movie['rating']); ?>">
        </div>
        <div class="mb-3">
            <label for="genre" class="form-label">Genre (pisahkan dengan koma):</label>
            <input type="text" name="genre" id="genre" class="form-control" required value="<?php echo htmlspecialchars($movie['genre']); ?>">
        </div>
        <div class="mb-3">
            <label for="trailer" class="form-label">Link Trailer (YouTube):</label>
            <input type="url" name="trailer" id="trailer" class="form-control"  value="<?php echo htmlspecialchars($movie['trailer']); ?>">
        </div>
        <div class="mb-3">
            <label for="seleksi" class="form-label">Seleksi Tampilan:</label>
            <select name="seleksi" id="seleksi" class="form-select">
                <option value="" <?php if ($movie['seleksi'] == '') echo 'selected'; ?>>Biasa / Tidak Ada</option>
                <option value="hero" <?php if ($movie['seleksi'] == 'hero') echo 'selected'; ?>>Hero (Tampil di Carousel Besar)</option>
                <option value="upcoming" <?php if ($movie['seleksi'] == 'upcoming') echo 'selected'; ?>>Upcoming (Segera Tayang)</option>
                <option value="slider" <?php if ($movie['seleksi'] == 'slider') echo 'selected'; ?>>Slider (Tampil di slider genre)</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="poster" class="form-label">Poster (Gambar Vertikal):</label><br>
            <?php if (!empty($movie['poster'])): ?>
                <img src="../../Image/<?php echo htmlspecialchars($movie['poster']); ?>" width="100" class="mb-2 img-thumbnail"><br>
            <?php endif; ?>
            <input type="file" name="poster" id="poster" class="form-control">
            <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah poster.</small>
            <?php if (!empty($movie['poster'])): ?>
            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" name="delete_poster" id="delete_poster">
                <label class="form-check-label" for="delete_poster">
                    Hapus poster saat ini
                </label>
            </div>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label for="background" class="form-label">Background (Gambar Horizontal):</label><br>
            <?php if (!empty($movie['background'])): ?>
                <img src="../../Image/<?php echo htmlspecialchars($movie['background']); ?>" width="200" class="mb-2 img-thumbnail"><br>
            <?php endif; ?>
            <input type="file" name="background" id="background" class="form-control">
            <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah background.</small>
            <?php if (!empty($movie['background'])): ?>
            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" name="delete_background" id="delete_background">
                <label class="form-check-label" for="delete_background">
                    Hapus background saat ini
                </label>
            </div>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label for="sinopsis" class="form-label">Sinopsis:</label>
            <textarea name="sinopsis" id="sinopsis" class="form-control" rows="5"><?php echo htmlspecialchars($movie['sinopsis'] ?? ''); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="studio" class="form-label">Studio:</label>
            <input type="text" name="studio" id="studio" class="form-control" value="<?php echo htmlspecialchars($movie['studio'] ?? ''); ?>">
        </div>
        <div class="mb-3">
            <label for="director" class="form-label">Director:</label>
            <input type="text" name="director" id="director" class="form-control" value="<?php echo htmlspecialchars($movie['director'] ?? ''); ?>">
        </div>
        <div class="mb-3">
            <label for="stars" class="form-label">Stars (pisahkan dengan koma):</label>
            <input type="text" name="stars" id="stars" class="form-control" value="<?php echo htmlspecialchars($movie['stars'] ?? ''); ?>">
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Ubah Data</button>
    </form>
</div>

</body>
</html>
