<?php
session_start();
require 'function.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$user = getUserById($conn, $userId); 

if (isset($_POST['update_profile'])) {
    $_POST['user_id'] = $userId;
    
    if (updateProfile($conn, $_POST) !== false) {
        echo "<script>
                alert('Profil berhasil diperbarui!');
                document.location.href = 'profile.php';
              </script>";
    } else {
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="../Image/favicon.png">
    <title>SceneSide</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/profile.css">
    
   
</head>
<body class="bg-dark text-white">

<?php include "navbar.php"?>


    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="profile-card">
                    <h2 class="text-center mb-4 fw-bold">Profil Saya</h2>
                    
                    <form action="" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="old_picture" value="<?php echo htmlspecialchars($user['foto'] ?? ''); ?>">
                        
                        <div class="text-center mb-4">
                            <?php 
                            $pp_path = (!empty($user['foto'])) 
                                       ? '../Image/profiles/' . htmlspecialchars($user['foto'])
                                       : '../Image/default_user.png';
                            ?>
                            <img src="<?php echo $pp_path; ?>" class="rounded-circle profile-picture">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email (tidak dapat diubah)</label>
                            <input type="email" id="email" class="form-control form-control-dark" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" name="username" id="username" class="form-control form-control-dark" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                        </div>
                         <div class="mb-4">
                            <label for="profile_picture" class="form-label">Ganti Foto Profil</label>
                            <input type="file" name="profile_picture" id="profile_picture" class="form-control form-control-dark">
                            <div class="form-text text-secondary">Biarkan kosong jika tidak ingin mengganti foto.</div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <h4 class="mb-3 fw-semibold">Ubah Password</h4>
                        <p class="text-secondary mb-3">Biarkan kosong jika tidak ingin mengubah password.</p>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Password Baru</label>
                            <input type="password" name="new_password" id="new_password" class="form-control form-control-dark" placeholder="Masukkan password baru">
                        </div>
                         <div class="mb-4">
                            <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control form-control-dark" placeholder="Ketik ulang password baru">
                        </div>

                        <button type="submit" name="update_profile" class="btn btn-primary w-100 mt-3">SIMPAN PERUBAHAN</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    

<?php include "footer.php"?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
