
    <style>
        body {
            font-family: 'Inter', sans-serif;
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
                    <img src="../image/logo web horizontal.png" alt="Logo" class="footer-logo mb-3" style="height: 60px;">
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

