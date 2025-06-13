<?php
session_start();
require '../function.php'; // Sesuaikan path ke function.php

// Ambil parameter dari request AJAX
$keyword = $_GET['keyword'] ?? '';
$sort_by = $_GET['sort'] ?? 'nama_asc';
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

// Pengaturan Pagination
$results_per_page = 12;
$offset = ($current_page - 1) * $results_per_page;

// Dapatkan "checklist" wishlist jika user login
$wishlistMovieIds = [];
if (isset($_SESSION['login'])) {
    $wishlistMovieIds = getWishlistMovieIds($conn, $_SESSION['user_id']);
}

// Ambil data film berdasarkan keyword atau semua film jika keyword kosong
if (!empty(trim($keyword))) {
    $total_results = countSearchResults($conn, $keyword);
    $results = searchMovies($conn, $keyword, $results_per_page, $offset, $sort_by);
} else {
    $total_results = countAllMovies($conn);
    $results = getAllMovies($conn, $results_per_page, $offset, $sort_by);
}

// Hitung total halaman
$total_pages = ceil($total_results / $results_per_page);

// Atur parameter untuk link pagination
$pagination_query_param = '';
if (!empty($keyword)) {
    $pagination_query_param .= '&query=' . urlencode($keyword);
}
if (!empty($sort_by)) {
    $pagination_query_param .= '&sort=' . urlencode($sort_by);
}
?>

<!-- Bagian ini adalah HTML yang akan dikirim kembali ke JavaScript -->
<section class="results-grid">
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 g-4">
        <?php if (!empty($results)): ?>
            <?php foreach ($results as $movie): ?>
                <?php $isInWishlist = in_array($movie['id'], $wishlistMovieIds); ?>
                <div class="col">
                    <div class="movie-card">
                        <div class="poster-wrapper">
                            <a href="detail.php?id=<?php echo $movie['id']; ?>">
                                <img src="../Image/<?php echo htmlspecialchars($movie['poster'] ?? 'default_poster.jpg'); ?>" class="card-img-top" alt="Poster <?php echo htmlspecialchars($movie['nama']); ?>">
                            </a>
                            <?php if (isset($_SESSION['login'])): ?>
                                <?php if ($isInWishlist): ?>
                                    <a href="hapuswishlist.php?movie_id=<?php echo $movie['id']; ?>" class="btn-watchlist-overlay in-wishlist" title="Hapus dari Wishlist"><i class="bi bi-check-lg"></i></a>
                                <?php else: ?>
                                    <a href="tambahwishlist.php?movie_id=<?php echo $movie['id']; ?>" class="btn-watchlist-overlay" title="Tambah ke Wishlist"><i class="bi bi-plus-lg"></i></a>
                                <?php endif; ?>
                            <?php endif; ?>
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
                    <h4 class="text-light">Tidak ada film yang ditemukan</h4>
                    <p class="text-secondary">Silakan coba dengan kata kunci lain.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php if ($total_pages > 1): ?>
<section class="d-flex justify-content-center py-5 pagination-container">
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
