<?php
session_start();
require 'function.php'; // atau sesuaikan path-nya

$hero_movies = getMoviesBySection($conn, 'hero', 5);
$upcoming_movies = getMoviesBySection($conn, 'upcoming', 4);


// Dapatkan "checklist" ID film di wishlist HANYA JIKA user sudah login
$wishlistMovieIds = [];
if (isset($_SESSION['login'])) {
    $wishlistMovieIds = getWishlistMovieIds($conn, $_SESSION['user_id']);
}

$genres_to_display = [
    'Action',
    'Sci-Fi',
    'Fantasy',
    'Animation',
    'Horror',
    'Drama'

];

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
    <link rel="stylesheet" href="../css/homme.css">
</head>


<?php include "navbar.php"?>

<main class="container-fluid p-lg-5 mt-4">
    <div class="row g-4" style="min-height: 85vh;">
        <div class="col-lg-9">

            <!-- HERO -->

            <section class="hero-section">
                <div id="movieCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php foreach ($hero_movies as $index => $movie): ?>
                            <div class="carousel-item <?php echo ($index == 0) ? 'active' : ''; ?>">
                                <img src="../Image/<?php echo htmlspecialchars($movie['background'] ?? 'default_background.jpg'); ?>" class="carousel-background-img" alt="Background <?php echo htmlspecialchars($movie['nama'] ?? ''); ?>">
                                <div class="carousel-caption">
                                    <h1 class="hero-title"><?php echo htmlspecialchars($movie['nama'] ?? 'Judul Tidak Tersedia'); ?></h1>
                                    <p class="hero-rating mb-3">
                                        <i class="bi bi-star-fill"></i> <?php echo htmlspecialchars($movie['rating'] ?? 'N/A'); ?> | <?php echo htmlspecialchars($movie['tahun'] ?? '-'); ?>
                                    </p>
                                    <a href="detail.php?id=<?php echo $movie['id']; ?>" class="btn btn-trailer"><i class="bi bi-play-circle-fill me-2"></i>Tonton Trailer</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#movieCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#movieCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </section>
        </div>

        <div class="col-lg-3">

            <!-- upcoming -->
            <aside class="upcoming-section">
                <h4 class="upcoming-header">FILM SEGERA TAYANG</h4>
                <span class="garis"></span>
                <div class="upcoming-list">
                    <?php foreach ($upcoming_movies as $movie): ?>
                        <!-- ======================================================= -->
                        <!-- PERUBAHAN DI SINI -->
                        <!-- ======================================================= -->
                        <a href="detail.php?id=<?php echo $movie['id']; ?>" class="upcoming-item">
                            <img src="../Image/<?php echo htmlspecialchars($movie['poster'] ?? 'default_poster.jpg'); ?>" alt="Poster <?php echo htmlspecialchars($movie['nama'] ?? ''); ?>" class="upcoming-item-poster">
                            <span class="upcoming-item-title"><?php echo htmlspecialchars($movie['nama'] ?? 'Segera Tayang'); ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </aside>

        </div>
    </div>

    <?php foreach ($genres_to_display as $genre): ?>
        <?php
        $movies_in_genre = getMoviesByGenre($conn, $genre, 6);
        if (!empty($movies_in_genre)):
        ?>
            <div class="row">
                <div class="col-12">
                    <section class="mt-5">
                        <h3 class="mb-4 text-white fw-bold"><?php echo htmlspecialchars($genre); ?> Movies</h3>
                        <div class="slider-wrapper">
                            <?php foreach ($movies_in_genre as $movie): ?>
                                <?php
                                $isInWishlist = in_array($movie['id'], $wishlistMovieIds);
                                ?>
                                <div class="movie-card-link">
                                    <div class="movie-card">
                                        <div class="poster-wrapper">
                                            <a href="detail.php?id=<?php echo $movie['id']; ?>">
                                                <img src="../Image/<?php echo htmlspecialchars($movie['poster'] ?? 'default_poster.jpg'); ?>" class="card-img-top" alt="Poster <?php echo htmlspecialchars($movie['nama']); ?>">
                                            </a>

                                            <?php if ($isInWishlist): ?>
                                                <a href="hapuswishlist.php?movie_id=<?php echo $movie['id']; ?>" class="btn-watchlist-overlay in-wishlist" title="Hapus dari Wishlist">
                                                    <i class="bi bi-check-lg"></i>
                                                </a>
                                            <?php else: ?>
                                                <a href="tambahwishlist.php?movie_id=<?php echo $movie['id']; ?>" class="btn-watchlist-overlay" title="Tambah ke Wishlist">
                                                    <i class="bi bi-plus-lg"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo htmlspecialchars($movie['nama']); ?></h5>
                                            <p class="card-rating mb-1"><i class="bi bi-star-fill"></i> <?php echo htmlspecialchars($movie['rating']); ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

</main>

<?php include "footer.php"?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
