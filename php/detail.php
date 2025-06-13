<?php
session_start();
require 'function.php';

if (!isset($_GET['id'])) {
    header("Location: home.php");
    exit;
}

$id = $_GET['id'];
$movie = getMovieById($id); 

if (!$movie) {
    die("Film tidak ditemukan!");
}

$trailer_url = $movie['trailer'] ?? '';
if (strpos($trailer_url, 'watch?v=') !== false) {
    $trailer_url = str_replace('watch?v=', 'embed/', $trailer_url);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($movie['nama']); ?> - SceneSide</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/detail.css">

</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="home.php">
                <img src="../Image/logo web type white.png" alt="SceneSide Logo" style="height: 40px;">
            </a>
            <div class="d-flex align-items-center ms-auto">
                <ul class="navbar-nav flex-row gap-3 align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="search.php"><i class="bi bi-search"></i></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="wishlist.php"><i class="bi bi-bookmarks"></i></a>
                    </li>

                    <?php if (isset($_SESSION['login']) && isset($_SESSION['username'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link " href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle me-1"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end" aria-labelledby="profileDropdown">
                                <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person-fill me-2"></i>Profil Saya</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="loginDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end" aria-labelledby="loginDropdown">
                                <li><a class="dropdown-item" href="login.php"><i class="bi bi-box-arrow-in-right me-2"></i>Login</a></li>
                                <li><a class="dropdown-item" href="register.php"><i class="bi bi-person-plus-fill me-2"></i>Daftar</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Kontainer Utama Halaman Detail -->
    <div class="container my-5">
        <div class="main-container">
            <!-- Bagian Header -->
            <div class="row align-items-center mb-4">
                <div class="col-md-8">
                    <h1 class="movie-title"><?php echo htmlspecialchars($movie['nama']); ?></h1>
                    <div class="d-flex align-items-center movie-year">
                        <span><?php echo htmlspecialchars($movie['tahun']); ?></span>
                        <?php
                        $genres = explode(',', $movie['genre'] ?? '');
                        $first_genre = trim($genres[0]);
                        if (!empty($first_genre)) :
                        ?>
                            <span class="mx-2">|</span>
                            <span><?php echo htmlspecialchars($first_genre); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <div class="rating-box d-inline-flex align-items-center gap-2">
                        <i class="bi bi-star-fill"></i>
                        <span><?php echo htmlspecialchars($movie['rating']); ?></span>
                    </div>
                </div>
            </div>

            <!-- Bagian Grid Gambar -->
            <div class="row g-3 mb-4 image-grid">
                <div class="col-md-3">
                    <img src="../Image/<?php echo htmlspecialchars($movie['poster']); ?>" class="poster-img" alt="Poster Film">
                </div>
                <div class="col-md-9">
                    <img src="../Image/<?php echo htmlspecialchars($movie['background']); ?>" class="background-img" alt="Scene Film">
                </div>
            </div>

            <!-- Bagian Trailer dan Sinopsis -->
            <div class="row g-4">
                <div class="col-lg-7">
                    <?php if (!empty($trailer_url)): ?>
                        <iframe class="trailer-placeholder" src="<?php echo htmlspecialchars($trailer_url); ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    <?php else: ?>
                        <div class="trailer-placeholder d-flex align-items-center justify-content-center"><span>Trailer Tidak Tersedia</span></div>
                    <?php endif; ?>
                </div>
                <div class="col-lg-5">
                    <h3 class="synopsis-title">Sinopsis</h3>
                    <p class="synopsis-text">
                        <?php echo nl2br(htmlspecialchars($movie['sinopsis'] ?? 'Sinopsis tidak tersedia.')); ?>
                    </p>
                </div>
            </div>

            <!-- Bagian Info KRew Film -->
            <div class="credits-section">
                <div class="credit-item">
                    <span class="credit-item-label">Studio:</span>
                    <span class="credit-item-value"><?php echo htmlspecialchars($movie['studio'] ?? 'Tidak diketahui'); ?></span>
                </div>
                <div class="credit-item">
                    <span class="credit-item-label">Director:</span>
                    <span class="credit-item-value"><?php echo htmlspecialchars($movie['director'] ?? 'Tidak diketahui'); ?></span>
                </div>
                <div class="credit-item">
                    <span class="credit-item-label">Stars:</span>
                    <span class="credit-item-value"><?php echo htmlspecialchars($movie['stars'] ?? 'Tidak diketahui'); ?></span>
                </div>
            </div>

        </div>
    </div>


    <?php include "footer.php"?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
