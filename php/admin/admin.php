<?php
session_start();

// Keamanan: Cek apakah pengguna sudah login dan memiliki peran 'admin'
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require '../function.php';

// --- LOGIKA PENCARIAN ---
// Cek apakah ada keyword pencarian yang dikirim
if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
    $keyword = $_GET['keyword'];
    // Jika ada, panggil fungsi pencarian admin
    $movies = searchMoviesForAdmin($conn, $keyword);
} else {
    // Jika tidak, tampilkan semua film seperti biasa
    $movies = getAllMovies($conn, 100, 0); 
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - SceneSide</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../css/admin.css">
    
    
</head>
<body class="bg-dark text-white">

<div class="container my-5">
    <div class="header-admin d-flex justify-content-between align-items-center mb-4">
        <h1>Admin Dashboard</h1>
        <div>
            <span class="me-3">Selamat datang, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!</span>
            <!-- TOMBOL BARU DITAMBAHKAN DI SINI -->
            <a href="../home.php" class="btn btn-outline-light me-2" target="_blank">Home Page</a>
            <a href="../logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <div class="row mb-4 align-items-center">
        <!-- Kolom Tombol Tambah -->
        <div class="col-md-6">
            <a href="tambah.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Tambah Film Baru</a>
        </div>
        <!-- Kolom Form Pencarian -->
        <div class="col-md-6">
            <form action="" method="get">
                <div class="input-group">
                    <input type="text" name="keyword" class="form-control"   value="<?php echo htmlspecialchars($_GET['keyword'] ?? ''); ?>" autofocus>
                    <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-dark">
                <tr >
                    <th scope="col" class="text-center" style="color: #99f912">No</th>
                    <th scope="col" style="color: #99f912">Poster</th>
                    <th scope="col" style="color: #99f912">Nama Film</th>
                    <th scope="col" style="color: #99f912">Seleksi</th>
                    <th scope="col" style="color: #99f912">Genre</th>
                    <th scope="col" class="text-center" style="color: #99f912">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($movies)): ?>
                    <?php $i = 1; ?>
                    <?php foreach ($movies as $movie) : ?>
                    <tr>
                        <td class="text-center"><?php echo $i++; ?></td>
                        <td><img src="../../Image/<?php echo htmlspecialchars($movie['poster'] ?? 'default_poster.jpg'); ?>" width="60" alt="Poster" class="rounded"></td>
                        <td><?php echo htmlspecialchars($movie['nama'] ?? 'N/A'); ?></td>
                        <td><span class="badge bg-secondary"><?php echo htmlspecialchars($movie['seleksi'] ?? '-'); ?></span></td>
                        <td><?php echo htmlspecialchars($movie['genre'] ?? '-'); ?></td>
                        <td class="text-center">
                            <a href="edit.php?id=<?php echo $movie['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="hapus.php?id=<?php echo $movie['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin?');">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center p-4">
                            <?php if (isset($_GET['keyword'])): ?>
                                Film dengan kata kunci "<?php echo htmlspecialchars($_GET['keyword']); ?>" tidak ditemukan.
                            <?php else: ?>
                                Belum ada data film di database.
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
