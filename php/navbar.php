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
            <img src="../Image/logo web horizontal.png" alt="SceneSide Logo" style="height: 65px">
        </a>
        <div class="d-flex align-items-center ms-auto">
            <ul class="navbar-nav flex-row gap-3 align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="search.php"><i class="bi bi-search fs-5"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="wishlist.php"><i class="bi bi-bookmarks fs-5"></i></a>
                </li>

                <?php if (isset($_SESSION['login']) && isset($_SESSION['username'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link d-flex align-items-center p-0" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php 
                            if (!empty($_SESSION['foto'])): 
                                $pp_path = '../Image/profiles/' . htmlspecialchars($_SESSION['foto']);
                            ?>
                                <img src="<?php echo $pp_path; ?>" alt="Foto Profil" class="navbar-profile-pic">
                            <?php else: ?>
                                <i class="bi bi-person-circle fs-4"></i>
                            <?php endif; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end" aria-labelledby="profileDropdown">
                            <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person-fill me-2"></i>Profil Saya</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php"><i class="bi bi-person-circle fs-4"></i></a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>


