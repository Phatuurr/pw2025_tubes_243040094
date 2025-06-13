<?php
require 'function.php';
session_start();

if (isset($_SESSION['login'])) {
    header("Location: home.php");
    exit;
}

$error = false;

if (isset($_POST["login"])) {
    if (login($_POST) === false) {
        $error = true;
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="icon" href="../Image/favicon.png">
    <title>SceneSide</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/logwin.css">
    <style>
        .error-message {
            color: #ff6b6b;
            background-color: rgba(255, 107, 107, 0.2);
            border: 1px solid #ff6b6b;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin-top: 1rem;
        }
    </style>
</head>

<body>
    <div class="container d-flex">
        <div class="bg-login">
            <div class="login">
                <h2>WELCOME TO</h2>
                <h1>SCENE<span class="color">SIDE</span></h1>

                <form action="" method="post">
                    <h3>Email</h3>
                    <input class="email" type="email" name="email" id="email" placeholder="Email" required autocomplete="off">
                    <br>
                    <h3 class="label">Password</h3>
                    <input class="pass" type="password" name="password" id="password" placeholder="Password" required>
                    <br>
                    <button class="submit" type="submit" name="login">LOGIN</button>
                </form>
                <div class="garis"></div>
                <div class="register">
                    <a href="register.php">- Belum Punya Akun? -</a>
                </div>

                <?php if ($error) : 
                ?>
                    <div class="error-message">
                        Email atau password salah!
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>