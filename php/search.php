<?php
session_start();
require 'function.php'; 

$search_query = $_GET['query'] ?? '';
$sort_by = $_GET['sort'] ?? 'nama_asc';
$header_title = !empty($search_query) ? 'Hasil untuk "' . htmlspecialchars($search_query) . '"' : "Telusuri Semua Film";
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
    <link rel="stylesheet" href="../css/search2.css">
</head>

<body>

    <?php include "navbar.php" ?>

    <main class="container-fluid px-lg-5">
        <header class="search-header">
            <h1 id="header-title"><?php echo $header_title; ?></h1>
            <div class="search-controls-container">
                <div>
                    <div class="search-form-wrapper mb-3">
                        <input type="search" autofocus name="query" id="keyword" class="form-control form-control-search" placeholder="Cari film atau serial TV..." value="<?php echo htmlspecialchars($search_query); ?>">
                    </div>
                    <div class="sort-wrapper d-flex justify-content-center">
                        <select name="sort" id="sort_by" class="form-select w-auto" title="Urutkan hasil">
                            <option value="nama_asc" <?php if ($sort_by == 'nama_asc') echo 'selected'; ?>>(A-Z)</option>
                            <option value="nama_desc" <?php if ($sort_by == 'nama_desc') echo 'selected'; ?>>(Z-A)</option>
                            <option value="rating_desc" <?php if ($sort_by == 'rating_desc') echo 'selected'; ?>>Rating Tertinggi</option>
                            <option value="tahun_desc" <?php if ($sort_by == 'tahun_desc') echo 'selected'; ?>>Tahun Terbaru</option>
                        </select>
                    </div>
                </div>
            </div>
        </header>

        <div id="results-container">

        </div>
    </main>

    <?php include "footer.php" ?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const keywordInput = document.getElementById('keyword');
            const sortBySelect = document.getElementById('sort_by');
            const resultsContainer = document.getElementById('results-container');
            const headerTitle = document.getElementById('header-title');
            let searchTimeout;

            function loadContent(page = 1) {
                const keyword = keywordInput.value;
                const sortBy = sortBySelect.value;
                const url = `ajax/livesearch.php?keyword=${encodeURIComponent(keyword)}&sort=${sortBy}&page=${page}`;

                if (keyword.trim() !== '') {
                    headerTitle.textContent = `Hasil untuk "${keyword}"`;
                } else {
                    headerTitle.textContent = 'Telusuri Semua Film';
                }

                resultsContainer.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-light" role="status"><span class="visually-hidden">Loading...</span></div></div>';

                fetch(url)
                    .then(response => response.text())
                    .then(data => {
                        resultsContainer.innerHTML = data;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        resultsContainer.innerHTML = '<div class="text-center py-5 text-danger">Gagal memuat data. Silakan coba lagi.</div>';
                    });
            }

            keywordInput.addEventListener('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    loadContent(1);
                }, 500); 
            });

            sortBySelect.addEventListener('change', function() {
                loadContent(1);
            });

            resultsContainer.addEventListener('click', function(event) {
                if (event.target.matches('.page-link')) {
                    event.preventDefault(); // Mencegah reload halaman
                    const href = event.target.getAttribute('href');
                    if (href && href !== '#') {
                        const urlParams = new URLSearchParams(href.split('?')[1]);
                        const page = urlParams.get('page');
                        if (page) {
                            loadContent(page);
                        }
                    }
                }
            });

            loadContent();
        });
    </script>
</body>

</html>