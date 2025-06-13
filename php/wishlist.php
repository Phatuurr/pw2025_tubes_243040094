<?php
session_start();
require 'function.php';

// Keamanan: Cek jika user belum login, tendang ke halaman login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Pastikan koneksi berhasil
if (!$conn) {
    die("Koneksi ke database gagal");
}

$user_id = $_SESSION['user_id'];

$sort_by = $_GET['sort'] ?? 'nama_asc';

$results_per_page = 12;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) $current_page = 1;
$offset = ($current_page - 1) * $results_per_page;

$total_results = countWishlistByUserId($conn, $user_id);
$results = getWishlistByUserId($conn, $user_id, $results_per_page, $offset, $sort_by);

$total_pages = ceil($total_results / $results_per_page);

$pagination_query_param = '&sort=' . urlencode($sort_by);

$page_title = "Wishlist Saya";
$header_title = "Wishlist Saya";
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../Image/favicon.png">
    <title>SceneSide</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/search.css">
</head>

<body>

    <?php include 'navbar.php' ?>

    <main class="container-fluid px-lg-5" style="padding-bottom: 60px;">
        <header class="search-header">
            <h1><?php echo $header_title; ?></h1>
            <div class="search-controls-container">
                <form action="wishlist.php" method="GET">
                    <div class="sort-wrapper d-flex justify-content-center">
                        <select name="sort" class="form-select w-auto" onchange="this.form.submit()" title="Urutkan hasil">
                            <option value="nama_asc" <?php if ($sort_by == 'nama_asc') echo 'selected'; ?>>Abjad (A-Z)</option>
                            <option value="rating_desc" <?php if ($sort_by == 'rating_desc') echo 'selected'; ?>>Rating Tertinggi</option>
                            <option value="tahun_desc" <?php if ($sort_by == 'tahun_desc') echo 'selected'; ?>>Tahun Terbaru</option>
                        </select>
                    </div>
                </form>
            </div>
        </header>

        <section class="results-grid">
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 g-4">
                <?php if (!empty($results)): ?>
                    <?php foreach ($results as $movie): ?>
                        <div class="col">
                            <div class="movie-card">
                                <div class="poster-wrapper">
                                    <!-- Link utama ke detail film -->
                                    <a href="detail.php?id=<?php echo $movie['id']; ?>">
                                        <img src="../Image/<?php echo htmlspecialchars($movie['poster'] ?? 'default_poster.jpg'); ?>" class="card-img-top" alt="Poster <?php echo htmlspecialchars($movie['nama']); ?>">
                                    </a>

                                    <!-- Tombol Hapus (ceklis hijau) yang fungsional -->
                                    <a href="hapuswishlist.php?movie_id=<?php echo $movie['id']; ?>" class="btn-watchlist-overlay in-wishlist" title="Hapus dari Wishlist">
                                        <i class="bi bi-check-lg"></i>
                                    </a>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($movie['nama']); ?></h5>
                                    <p class="card-rating mb-1"><i class="bi bi-star-fill"></i> <?php echo htmlspecialchars($movie['rating']); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="text-center py-5">
                            <h4 class="text-light">Wishlist Anda kosong</h4>
                            <p class="text-secondary">Tambahkan film ke wishlist untuk melihatnya di sini.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <?php if ($total_pages > 1): ?>
            <section class="d-flex justify-content-center py-5">
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <li class="page-item <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $current_page - 1 . $pagination_query_param; ?>">&laquo;</a>
                        </li>
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i . $pagination_query_param; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $current_page + 1 . $pagination_query_param; ?>">&raquo;</a>
                        </li>
                    </ul>
                </nav>
            </section>
        <?php endif; ?>
    </main>

    <?php include "footer.php" ?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>