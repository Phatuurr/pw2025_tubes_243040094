<?php
session_start();
require 'php/function.php'; // atau sesuaikan path-nya

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
    <link rel="stylesheet" href="css/homme.css">
</head>

<style>
    .navbar-profile-pic {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;       
    border: 2px solid #99f912; 
}
</style>

<nav class="navbar navbar-expand-lg sticky-top navbar-dark">
    <div class="container-fluid px-lg-5">
        <a class="navbar-brand" href="index.php">
            <img src="Image/logo web horizontal.png" alt="SceneSide Logo" style="height: 65px">
        </a>
        <div class="d-flex align-items-center ms-auto">
            <ul class="navbar-nav flex-row gap-3 align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="php/search.php"><i class="bi bi-search fs-5"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="php/wishlist.php"><i class="bi bi-bookmarks fs-5"></i></a>
                </li>

                <?php if (isset($_SESSION['login']) && isset($_SESSION['username'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link d-flex align-items-center p-0" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php 
                            if (!empty($_SESSION['foto'])): 
                                $pp_path = 'Image/profiles/' . htmlspecialchars($_SESSION['foto']);
                            ?>
                                <img src="<?php echo $pp_path; ?>" alt="Foto Profil" class="navbar-profile-pic">
                            <?php else: ?>
                                <i class="bi bi-person-circle fs-4"></i>
                            <?php endif; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end" aria-labelledby="profileDropdown">
                            <li><a class="dropdown-item" href="php/profile.php"><i class="bi bi-person-fill me-2"></i>Profil Saya</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="php/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="php/login.php"><i class="bi bi-person-circle fs-4"></i></a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>




<main class="container-fluid p-lg-5 mt-4">
    <div class="row g-4" style="min-height: 85vh;">
        <div class="col-lg-9">

            <!-- HERO -->

            <section class="hero-section">
                <div id="movieCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php foreach ($hero_movies as $index => $movie): ?>
                            <div class="carousel-item <?php echo ($index == 0) ? 'active' : ''; ?>">
                                <img src="Image/<?php echo htmlspecialchars($movie['background'] ?? 'default_background.jpg'); ?>" class="carousel-background-img" alt="Background <?php echo htmlspecialchars($movie['nama'] ?? ''); ?>">
                                <div class="carousel-caption">
                                    <h1 class="hero-title"><?php echo htmlspecialchars($movie['nama'] ?? 'Judul Tidak Tersedia'); ?></h1>
                                    <p class="hero-rating mb-3">
                                        <i class="bi bi-star-fill"></i> <?php echo htmlspecialchars($movie['rating'] ?? 'N/A'); ?> | <?php echo htmlspecialchars($movie['tahun'] ?? '-'); ?>
                                    </p>
                                    <a href="php/detail.php?id=<?php echo $movie['id']; ?>" class="btn btn-trailer"><i class="bi bi-play-circle-fill me-2"></i>Tonton Trailer</a>
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
                       
                        <a href="php/detail.php?id=<?php echo $movie['id']; ?>" class="upcoming-item">
                            <img src="Image/<?php echo htmlspecialchars($movie['poster'] ?? 'default_poster.jpg'); ?>" alt="Poster <?php echo htmlspecialchars($movie['nama'] ?? ''); ?>" class="upcoming-item-poster">
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
                                            <a href="php/detail.php?id=<?php echo $movie['id']; ?>">
                                                <img src="Image/<?php echo htmlspecialchars($movie['poster'] ?? 'default_poster.jpg'); ?>" class="card-img-top" alt="Poster <?php echo htmlspecialchars($movie['nama']); ?>">
                                            </a>

                                            <?php if ($isInWishlist): ?>
                                                <a href="php/hapuswishlist.php?movie_id=<?php echo $movie['id']; ?>" class="btn-watchlist-overlay in-wishlist" title="Hapus dari Wishlist">
                                                    <i class="bi bi-check-lg"></i>
                                                </a>
                                            <?php else: ?>
                                                <a href="php/tambahwishlist.php?movie_id=<?php echo $movie['id']; ?>" class="btn-watchlist-overlay" title="Tambah ke Wishlist">
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



<style>
        body {
            font-family: 'Oxanium Extrabold';
            background-color: #121212;
        }

        .site-footer-v2 {
            background-color: #1a1a1a;
            color: #a9a9a9;
            padding: 4rem 0;
            border-top: 1px solid #333;
        }
        
        .footer-logo {
            height: 40px;
            margin-bottom: 1rem;
        }

        .footer-heading-v2 {
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 1.5rem;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .footer-links-v2 {
            list-style: none;
            padding-left: 0;
        }

        .footer-links-v2 li {
            margin-bottom: 0.8rem;
        }

        .footer-links-v2 a {
            color: #a9a9a9;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .footer-links-v2 a:hover {
            color: #ffffff;
            padding-left: 5px;
        }
        
        .footer-bottom-v2 {
            border-top: 1px solid #333;
            padding-top: 2rem;
            margin-top: 3rem;
        }

        .social-icons-v2 a {
            color: #a9a9a9;
            font-size: 1.2rem;
            transition: color 0.2s ease;
        }
        .social-icons-v2 a:hover {
            color: #ffffff;
        }
    </style>



    <footer class="site-footer-v2">
        <div class="container">
            <div class="row">
                <!-- Kolom Logo dan Tentang -->
                <div class="col-lg-8 col-md-12 mb-4 mb-lg-0">
                    <img src="image/logo web horizontal.png" alt="Logo" class="footer-logo mb-3" style="height: 60px;">
                    <p>Website ini dibuat oleh mahasiswa teknik informatik Universitas Pasundan Muhammad Fatur Rahman yang diharapkan dapat untuk menemukan film-film yang kalian cari :D</p>
                </div>

                <!-- Kolom Kontak -->
                <div class="col-lg-4 col-md-6">
                    <h5 class="footer-heading-v2">Kontak</h5>
                    <ul class="footer-links-v2">
                        <li>muhamad.fatur.rahaman@gmail.com</li>
                    </ul>
                </div>
            </div>

            <div class="row footer-bottom-v2 align-items-center">
                <!-- Copyright -->
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0 small">&copy; <?php echo date("Y"); ?> SceneSide, Inc. All rights reserved.</p>
                </div>
                <!-- Ikon Sosial Media -->
                <div class="col-md-6 text-center text-md-end social-icons-v2">
                    <a href="https://www.instagram.com/phaturr__/" target="_blank" class="me-3"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="me-3"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-youtube"></i></a>
                </div>
            </div>
        </div>
    </footer>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
