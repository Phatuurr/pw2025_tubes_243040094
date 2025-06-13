<?php
// ===================================================================
// KONEKSI DATABASE
// ===================================================================
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'sceneside';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}


// ===================================================================
// funsi fungsi otentiikasi untuk register
// ===================================================================


//Fungsi Registrasi Pengguna yang Aman.

function registrasi($data) {
    global $conn;

    $username = strtolower(stripslashes($data["username"]));
    $email = strtolower(stripslashes($data["email"]));
    $password = $data["password"];
    $password2 = $data["password2"];

    $stmt = $conn->prepare("SELECT username FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo "<script> alert('Username sudah terdaftar!'); </script>";
        return false;
    }
    $stmt->close();

    if ($password !== $password2) {
        echo "<script> alert('Konfirmasi password tidak sesuai!');</script>";
        return false;
    }

    // Enkripsi password
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    // Insert data user baru dengan menyertakan role
    $default_role = 'user';
    $stmt_insert = $conn->prepare("INSERT INTO user (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt_insert->bind_param("ssss", $username, $email, $password_hashed, $default_role);
    $stmt_insert->execute();

    return $stmt_insert->affected_rows;
}

//
// Fungsi Login Pengguna yang Aman (Bisa menggunakan username atau email).
//
function login($data) {
    global $conn;
    $login_identifier = $data["username"] ?? $data["email"]; 
    $password = $data["password"];

    // Tambahkan 'profile_picture' ke dalam query SELECT
    $stmt = $conn->prepare("SELECT id, username, password, role, foto FROM user WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $login_identifier, $login_identifier);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['login'] = true;
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role']; 
            $_SESSION['foto'] = $row['foto'];

            // Jika yang login adalah admin, arahkan ke dashboard admin
            if ($row['role'] === 'admin') {
                header("Location: admin/admin.php"); // Pastikan path ini benar
            } else {
                // Jika user biasa, arahkan ke home
                header("Location: home.php");
            }
            exit;
        }
    }
    
    return false;
}


// ===================================================================
// FUNGSI-FUNGSI UNTUK FILM
// ===================================================================


// Fungsi untuk mengambil film berdasarkan penempatan (hero, upcoming).

function getMoviesBySection($conn, $section, $limit = 5) {
    $sql = "SELECT * FROM movies WHERE seleksi = ? ORDER BY id ASC LIMIT ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $section, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}


// Fungsi untuk mengambil film berdasarkan genre, dengan pengecualian.

function getMoviesByGenre($conn, $genre, $limit = 10) {
    $sql = "SELECT * FROM movies 
            WHERE genre LIKE ? 
            AND seleksi = 'slider' 
            ORDER BY rating DESC
            LIMIT ?";

    $searchTerm = '%' . $genre . '%';
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $searchTerm, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}


// Fungsi untuk menghitung total SEMUA film.

function countAllMovies($conn) {
    $sql = "SELECT COUNT(*) as total FROM movies";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'] ?? 0;
}



// Fungsi untuk mengambil SEMUA film dengan pagination dan sorting.

function getAllMovies($conn, $limit, $offset, $sort_by = 'nama_asc') {
    // Whitelist untuk keamanan, mencegah SQL Injection di ORDER BY
    $allowed_sort = [
        'nama_asc' => 'nama ASC',
        'nama_desc' => 'nama DESC',
        'rating_desc' => 'rating DESC',
        'tahun_desc' => 'tahun DESC'
    ];
    $order_by_clause = $allowed_sort[$sort_by] ?? 'nama ASC'; 

    // Tambahkan 'seleksi' dan 'genre' ke dalam daftar kolom SELECT
    $sql = "SELECT id, nama, poster, rating, tahun, seleksi, genre, sinopsis, studio, director, stars FROM movies ORDER BY $order_by_clause LIMIT ? OFFSET ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}


// Fungsi untuk menghitung hasil PENCARIAN.

function countSearchResults($conn, $query) {
    $searchTerm = '%' . $query . '%';
    $sql = "SELECT COUNT(*) as total FROM movies WHERE nama LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['total'] ?? 0;
}


// 
//  Fungsi untuk mengambil hasil PENCARIAN dengan pagination dan sorting.
//
function searchMovies($conn, $query, $limit, $offset, $sort_by = 'nama_asc') {
    // Whitelist untuk keamanan
    $allowed_sort = [
        'nama_asc' => 'nama ASC',
        'nama_desc' => 'nama DESC',
        'rating_desc' => 'rating DESC',
        'tahun_desc' => 'tahun DESC'
    ];
    $order_by_clause = $allowed_sort[$sort_by] ?? 'nama ASC';

    $searchTerm = '%' . $query . '%';
    $sql = "SELECT id, nama, poster, rating, tahun FROM movies WHERE nama LIKE ? ORDER BY $order_by_clause LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $searchTerm, $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}


// 
//  Fungsi untuk mengambil film di wishlist berdasarkan ID user dengan pagination dan sorting.
//  Menggunakan JOIN untuk menggabungkan tabel wishlist dan movies.
// 
function getWishlistByUserId($conn, $userId, $limit, $offset, $sort_by = 'nama_asc') {
    global $conn;

    // Whitelist untuk keamanan sorting
    $allowed_sort = [
        'nama_asc'    => 'm.nama ASC',
        'rating_desc' => 'm.rating DESC',
        'tahun_desc'  => 'm.tahun DESC'
    ];
    $order_by_clause = $allowed_sort[$sort_by] ?? 'm.nama ASC';

    // Query JOIN untuk mengambil detail film dari wishlist user
    $sql = "SELECT
                m.id,
                m.nama,
                m.poster,
                m.rating,
                m.tahun
            FROM
                wishlist w
            JOIN
                movies m ON w.movie_id = m.id
            WHERE
                w.user_id = ?
            ORDER BY $order_by_clause
            LIMIT ? OFFSET ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $userId, $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}


//  Fungsi untuk menghitung total film di wishlist berdasarkan ID user.
  
function countWishlistByUserId($conn, $userId) {
    global $conn;
    $sql = "SELECT COUNT(*) as total FROM wishlist WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['total'] ?? 0;
}




// Fungsi untuk menambahkan film ke wishlist seorang user.


function addToWishlist($conn, $userId, $movieId) {
    $stmt_check = $conn->prepare("SELECT id FROM wishlist WHERE user_id = ? AND movie_id = ?");
    $stmt_check->bind_param("ii", $userId, $movieId);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $stmt_check->close();
        return 0;
    }
    $stmt_check->close();

    $stmt_insert = $conn->prepare("INSERT INTO wishlist (user_id, movie_id) VALUES (?, ?)");
    $stmt_insert->bind_param("ii", $userId, $movieId);
    $stmt_insert->execute();
    
    $affected_rows = $stmt_insert->affected_rows;
    $stmt_insert->close();

    return $affected_rows;
}


function getWishlistMovieIds($conn, $userId) {
    $stmt = $conn->prepare("SELECT movie_id FROM wishlist WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Ambil semua baris dan ubah menjadi array satu dimensi
    $movie_ids = array_column($result->fetch_all(MYSQLI_ASSOC), 'movie_id');
    
    $stmt->close();
    return $movie_ids;
}


//  Fungsi untuk menghapus film dari wishlist seorang user.

function removeFromWishlist($conn, $userId, $movieId) {
    $stmt = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND movie_id = ?");
    $stmt->bind_param("ii", $userId, $movieId);
    $stmt->execute();
    
    $affected_rows = $stmt->affected_rows;
    $stmt->close();

    return $affected_rows;
}


// ==============================
// Funsgi edit profile
// ==============================


//  Fungsi untuk mengambil data satu user berdasarkan ID.

function getUserById($conn, $userId) {
    $stmt = $conn->prepare("SELECT id, username, email, foto FROM user WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

 

// Fungsi untuk meng-handle upload foto profil dengan validasi ukuran dan nama file unik.

function uploadProfilePicture() {
    // Pastikan Anda menggunakan 'profile_picture' sesuai dengan nama input di form HTML
    if (!isset($_FILES['foto']) || $_FILES['foto']['error'] === 4) {
        return null; 
    }

    $namaFile = $_FILES['foto']['name'];
    $ukuranFile = $_FILES['foto']['size']; 
    $error = $_FILES['foto']['error'];
    $tmpName = $_FILES['foto']['tmp_name'];

    if ($error !== 0) {
        echo "<script>alert('Terjadi kesalahan saat mengupload file. Silakan coba lagi.');</script>";
        return false;
    }
    
    $maxFileSize = 2621440; // 2.5 MB
    if ($ukuranFile > $maxFileSize) {
        echo "<script>alert('Ukuran foto terlalu besar! Maksimal 2.5 MB.');</script>";
        return false;
    }
    
    $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
    $ekstensiGambarArray = explode('.', $namaFile);
    $ekstensiGambar = strtolower(end($ekstensiGambarArray));
    if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
        echo "<script>alert('File yang diupload harus berupa gambar (jpg, jpeg, png)!');</script>";
        return false;
    }
    
    $namaFileBaru = 'user_' . uniqid() . '.' . $ekstensiGambar;


    $tujuanUpload = '../Image/profiles/' . $namaFileBaru;
    
    // Pastikan folder tujuan ada
    if (!is_dir('../Image/profiles/')) {
        mkdir('../Image/profiles/', 0777, true);
    }
    
    if (move_uploaded_file($tmpName, $tujuanUpload)) {
        return $namaFileBaru;
    } else {
        echo "<script>alert('Gagal memindahkan file yang diupload.');</script>";
        return false;
    }
}




//  Fungsi utama untuk memperbarui profil pengguna.

 function updateProfile($conn, $data) {
    $userId = $data['user_id'];
    $username = htmlspecialchars($data['username']);
    $password_baru = $data['new_password'];
    $konfirmasi_password = $data['confirm_password'];
    $gambarLama = $data['old_picture'];

    $stmt_check = $conn->prepare("SELECT id FROM user WHERE username = ? AND id != ?");
    $stmt_check->bind_param("si", $username, $userId);
    $stmt_check->execute();
    if ($stmt_check->get_result()->num_rows > 0) {
        echo "<script>alert('Username sudah digunakan oleh akun lain!');</script>";
        return false;
    }
    $stmt_check->close();

    $query_parts = [];
    $params = [];
    $types = '';

    $query_parts[] = "username = ?";
    $params[] = $username;
    $types .= 's';

    if (!empty($password_baru)) {
        if ($password_baru !== $konfirmasi_password) {
            echo "<script>alert('Konfirmasi password baru tidak cocok!');</script>";
            return false;
        }
        $password_hashed = password_hash($password_baru, PASSWORD_DEFAULT);
        $query_parts[] = "password = ?";
        $params[] = $password_hashed;
        $types .= 's';
    }

    $gambar_baru = uploadProfilePicture();
    if ($gambar_baru) {
        if ($gambarLama) { 
            @unlink('../Image/profiles/' . $gambarLama);
        }
        $query_parts[] = "foto = ?";
        $params[] = $gambar_baru;
        $types .= 's';
    }

    $sql = "UPDATE user SET " . implode(', ', $query_parts) . " WHERE id = ?";
    $params[] = $userId;
    $types .= 'i';

    $stmt_update = $conn->prepare($sql);
    $stmt_update->bind_param($types, ...$params);
    $stmt_update->execute();

    $_SESSION['username'] = $username;
    if ($gambar_baru) {
        $_SESSION['foto'] = $gambar_baru;
    }

    return $stmt_update->affected_rows;
}






// ===============================================
// FUNGSI FUNGSI ADMIN
//===============================================

// Fungsi tambah film.

function uploadGambar($fileInputName) {
    if (!isset($_FILES[$fileInputName]) || $_FILES[$fileInputName]['error'] === 4) {
        return null; 
    }

    $namaFile = $_FILES[$fileInputName]['name'];
    $ukuranFile = $_FILES[$fileInputName]['size'];
    $error = $_FILES[$fileInputName]['error'];
    $tmpName = $_FILES[$fileInputName]['tmp_name'];

    $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
    $ekstensiGambar = strtolower(end(explode('.', $namaFile)));
    if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
        echo "<script>alert('File yang diupload untuk " . $fileInputName . " bukan gambar!');</script>";
        return false;
    }
    
    $namaFileBaru = uniqid() . '.' . $ekstensiGambar;
    if (move_uploaded_file($tmpName, '../../Image/' . $namaFileBaru)) {
        return $namaFileBaru;
    } else {
        return false;
    }
}



function tambahFilm($data) {
    global $conn;

    $nama = htmlspecialchars($data["nama"]);
    $tahun = htmlspecialchars($data["tahun"]);
    $rating = htmlspecialchars($data["rating"]);
    $genre = htmlspecialchars($data["genre"]);
    $trailer = htmlspecialchars($data["trailer"]);
    $seleksi = htmlspecialchars($data["seleksi"]);
    $sinopsis = htmlspecialchars($data["sinopsis"]);
    $studio = htmlspecialchars($data["studio"]);
    $director = htmlspecialchars($data["director"]);
    $stars = htmlspecialchars($data["stars"]);

    // Upload gambar
    $poster = uploadGambar('poster');
    $background = uploadGambar('background');
    if ($poster === false || $background === false) {
        return false; 
    }

    $stmt = $conn->prepare("INSERT INTO movies (nama, tahun, rating, genre, trailer, seleksi, poster, background, sinopsis, studio, director, stars) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdsssssssss", $nama, $tahun, $rating, $genre, $trailer, $seleksi, $poster, $background, $sinopsis, $studio, $director, $stars);
    $stmt->execute();

    return $stmt->affected_rows;
}


function editFilm($data) {
    global $conn;

    // Ambil semua data dari form
    $id = $data["id"];
    $nama = htmlspecialchars($data["nama"]);
    $tahun = htmlspecialchars($data["tahun"]);
    $rating = htmlspecialchars($data["rating"]);
    $genre = htmlspecialchars($data["genre"]);
    $trailer = htmlspecialchars($data["trailer"]);
    $seleksi = htmlspecialchars($data["seleksi"]);
    $posterLama = htmlspecialchars($data["posterLama"]);
    $backgroundLama = htmlspecialchars($data["backgroundLama"]);
    $sinopsis = htmlspecialchars($data["sinopsis"]);
    $studio = htmlspecialchars($data["studio"]);
    $director = htmlspecialchars($data["director"]);
    $stars = htmlspecialchars($data["stars"]);

    if (isset($data['delete_poster'])) {
        if ($posterLama) @unlink('../../Image/' . $posterLama); 
        $poster = null; 
    } else {
        $poster_baru = uploadGambar('poster');
        if ($poster_baru) {
            if ($posterLama) @unlink('../../Image/' . $posterLama); 
            $poster = $poster_baru;
        } else {
            $poster = $posterLama;
        }
    }
    
    if (isset($data['delete_background'])) {
        if ($backgroundLama) @unlink('../../Image/' . $backgroundLama);
        $background = null;
    } else {
        $background_baru = uploadGambar('background');
        if ($background_baru) {
            if ($backgroundLama) @unlink('../../Image/' . $backgroundLama);
            $background = $background_baru;
        } else {
            $background = $backgroundLama;
        }
    }

    $stmt = $conn->prepare("UPDATE movies SET 
                                nama = ?, tahun = ?, rating = ?, genre = ?, 
                                trailer = ?, seleksi = ?, poster = ?, background = ?,
                                sinopsis = ?, studio = ?, director = ?, stars = ?
                            WHERE id = ?");
    $stmt->bind_param("ssdsssssssssi", $nama, $tahun, $rating, $genre, $trailer, $seleksi, $poster, $background, $sinopsis, $studio, $director, $stars, $id);
    $stmt->execute();

    return $stmt->affected_rows;
}



function hapusFilm($id) {
    global $conn;

    $stmt_get = $conn->prepare("SELECT poster, background FROM movies WHERE id = ?");
    $stmt_get->bind_param("i", $id);
    $stmt_get->execute();
    $result = $stmt_get->get_result()->fetch_assoc();
    $posterLama = $result['poster'];
    $backgroundLama = $result['background'];
    $stmt_get->close();

    $stmt = $conn->prepare("DELETE FROM movies WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        if ($posterLama) @unlink('../../Image/' . $posterLama);
        if ($backgroundLama) @unlink('../../Image/' . $backgroundLama);
    }

    return $stmt->affected_rows;
}


// Fungsi untuk mengambil data satu film berdasarkan ID.

function getMovieById($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM movies WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}


function searchMoviesForAdmin($conn, $keyword) {
    $searchTerm = '%' . $keyword . '%';
    $sql = "SELECT * FROM movies WHERE nama LIKE ? ORDER BY nama ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}





?>