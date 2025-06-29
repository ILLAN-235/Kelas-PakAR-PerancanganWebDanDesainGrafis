<?php
session_start();

// Konfigurasi dasar
$site_title = "My Veridla";
$current_page = basename($_SERVER['PHP_SELF']);

// Koneksi database
include 'includes/config.php';

// Ambil ID produk dari URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data produk dari database
$product = null;
if ($product_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM produk WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();
}

// Format harga
function formatPrice($price) {
    return 'Rp ' . number_format($price, 0, ',', '.');
}

// Proses tambah ke keranjang
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id']) && isset($_POST['quantity'])) {
    $product_id = intval($_POST['id']);
    $quantity = intval($_POST['quantity']);
    
    if ($quantity > 0) {
        // Pastikan produk ada di database
        $stmt = $conn->prepare("SELECT id, name, price, image_url FROM produk WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $product_data = $result->fetch_assoc();
            
            // Inisialisasi keranjang jika belum ada
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            
            // Tambahkan ke keranjang
            if (isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$product_id] = [
                    'id' => $product_data['id'],
                    'name' => $product_data['name'],
                    'price' => $product_data['price'],
                    'image' => $product_data['image_url'],
                    'quantity' => $quantity
                ];
            }
            
            // Redirect ke keranjang setelah menambah
            header("Location: cart.php");
            exit();
        }
        $stmt->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($site_title) ?> - <?= $product ? htmlspecialchars($product['name']) : 'Produk Tidak Ditemukan' ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
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

        .product-container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
        }

        .product-image {
            flex: 1;
            min-width: 300px;
            max-width: 500px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .product-image img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .product-details {
            flex: 1;
            min-width: 300px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .product-title {
            font-size: 28px;
            color: #333;
            margin-bottom: 15px;
        }

        .product-price {
            font-size: 24px;
            color: #2c7873;
            font-weight: bold;
            margin: 15px 0;
        }

        .product-stock {
            color: #666;
            margin-bottom: 20px;
        }

        .product-description {
            line-height: 1.6;
            margin-bottom: 30px;
            color: #555;
        }

        .quantity-selector {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .quantity-selector label {
            margin-right: 10px;
            font-weight: bold;
        }

        .quantity-selector input {
            width: 60px;
            padding: 8px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .add-to-cart {
            display: inline-block;
            background: #2c7873;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
            font-size: 16px;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }

        .add-to-cart:hover {
            background: #6fb98f;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #2c7873;
            text-decoration: none;
        }

        .not-found {
            text-align: center;
            padding: 50px;
            max-width: 800px;
            margin: 0 auto;
        }

        footer {
            background: #2c7873;
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-top: 50px;
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
    <main>
        <?php if ($product): ?>
            <div class="product-container">
                <div class="product-image">
                    <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                </div>
                <div class="product-details">
                    <h1 class="product-title"><?= htmlspecialchars($product['name']) ?></h1>
                    <div class="product-price"><?= formatPrice($product['price']) ?>/<?= htmlspecialchars(isset($product['unit']) ? $product['unit'] : 'kg') ?></div>

                    <div class="product-stock">Stok: <?= htmlspecialchars($product['stock']) ?></div>
                    <div class="product-description">
                        <?= nl2br(htmlspecialchars($product['description'])) ?>
                    </div>
                    
                    <form action="product.php?id=<?= $product['id'] ?>" method="post">
                        <input type="hidden" name="id" value="<?= $product['id'] ?>">
                        
                        <div class="quantity-selector">
                            <label for="quantity">Jumlah:</label>
                            <input type="number" id="quantity" name="quantity" 
                                   value="1" min="1" max="<?= $product['stock'] ?>">
                        </div>
                        
                        <button type="submit" class="add-to-cart">
                            <i class="fas fa-cart-plus"></i> Tambah ke Keranjang
                        </button>
                    </form>
                    
                    <a href="index.php" class="back-link">&larr; Kembali ke Beranda</a>
                </div>
            </div>
        <?php else: ?>
            <div class="not-found">
                <h1>Produk Tidak Ditemukan</h1>
                <p>Maaf, produk yang Anda cari tidak tersedia.</p>
                <a href="index.php" class="add-to-cart">Kembali ke Beranda</a>
            </div>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <footer>

    </footer>

    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>
