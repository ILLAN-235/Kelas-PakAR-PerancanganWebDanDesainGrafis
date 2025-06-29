<?php
// Konfigurasi dasar
$site_title = "My Veridla";
$user = null; // Atur sesuai login
$current_page = basename($_SERVER['PHP_SELF']);

// Data produk
$products = [
    [
        'id' => 1,
        'name' => 'Gula Jawa Asli',
        'price' => 24000,
        'image' => 'assets/img/gula_jawa.jpg',
        'url' => 'product.php?id=1',
        'unit' => 'kg'
    ],
    [
        'id' => 2,
        'name' => 'Nila Bumbu',
        'price' => 34000,
        'image' => 'assets/img/nila.jpg',
        'url' => 'product.php?id=2',
        'unit' => 'kg'
    ],
    [
        'id' => 3,
        'name' => 'Gurame Bumbu',
        'price' => 70000,
        'image' => 'assets/img/gurame.jpg',
        'url' => 'product.php?id=3',
        'unit' => 'kg'
    ],
    [
        'id' => 4,
        'name' => 'Jambu Citra',
        'price' => 32000,
        'image' => 'assets/img/jambu.jpg',
        'url' => 'product.php?id=4',
        'unit' => 'kg'
    ]
];

// Format harga
function formatPrice($price) {
    return 'Rp ' . number_format($price, 0, ',', '.');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= htmlspecialchars($site_title) ?> - Beranda</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        header {
            background: #2c7873;
            color: white;
            padding: 10px 0;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: auto;
            padding: 0 20px;
        }

        .main-nav ul {
            list-style: none;
            padding: 0;
            display: flex;
            gap: 20px;
        }

        .main-nav a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .main-nav a:hover {
            background: #6fb98f;
        }

        .produk-section {
            margin: 20px 0;
        }

        .produk-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .produk-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.2s;
        }

        .produk-card:hover {
            transform: scale(1.05);
        }

        .produk-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }

        .produk-details {
            padding: 15px;
        }

        .harga {
            font-weight: bold;
            color: #2c7873;
        }

        .btn-detail {
            display: inline-block;
            background: #2c7873;
            color: white;
            padding: 10px;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .btn-detail:hover {
            background: #6fb98f;
        }

        footer {
            background: #2c7873;
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-top: 20px;
        }

        .footer-links a {
            color: white;
            margin: 0 10px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-container">
            <div class="logo">
                <h1><?= htmlspecialchars($site_title) ?></h1>
            </div>
            <nav class="main-nav">
                <ul>
                    <li><a href="index.php" class="<?= $current_page === 'index.php' ? 'active' : '' ?>">Beranda</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Konten Utama -->
    <main class="main-content">
        <section class="produk-section">
            <h2 class="section-title">Produk Kami</h2>
            <div class="produk-grid">
                <?php foreach ($products as $product): ?>
                    <div class="produk-card">
                        <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="produk-image" />
                        <div class="produk-details">
                            <h3><?= htmlspecialchars($product['name'])?></h3>
                            <p class="harga"><?= formatPrice($product['price']) ?>/<?= htmlspecialchars($product['unit']) ?></p>
                            <a href="<?= htmlspecialchars($product['url']) ?>" class="btn-detail">Lihat Detail</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer>
    </footer>
</body>
</html>
