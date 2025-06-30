<?php
session_start();

// Konfigurasi dasar
$site_title = "My Veridla";
$current_page = basename($_SERVER['PHP_SELF']);

// Koneksi database
include 'includes/config.php';

// Inisialisasi keranjang jika belum ada
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Proses tambah produk ke keranjang
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $product_id = intval($_POST['id']);
    
    // Ambil data produk dari database
    $stmt = $conn->prepare("SELECT id, name, price, image_url FROM produk WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        
        // Tambahkan ke keranjang
        if (!isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image_url'],
                'quantity' => 1
            ];
        } else {
            $_SESSION['cart'][$product_id]['quantity']++;
        }
    }
    $stmt->close();
}

// Proses hapus item dari keranjang
if (isset($_GET['remove'])) {
    $remove_id = intval($_GET['remove']);
    if (isset($_SESSION['cart'][$remove_id])) {
        unset($_SESSION['cart'][$remove_id]);
    }
}

// Hitung total
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Format harga
function formatPrice($price) {
    return 'Rp ' . number_format($price, 0, ',', '.');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($site_title) ?> - Keranjang Belanja</title>
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

        .cart-container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .cart-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
        }

        .cart-items {
            padding: 20px;
        }

        .cart-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .cart-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 20px;
        }

        .cart-item-info {
            flex-grow: 1;
        }

        .cart-item-title {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .cart-item-price {
            color: #2c7873;
            font-weight: bold;
        }

        .cart-item-quantity {
            display: flex;
            align-items: center;
            margin: 10px 0;
        }

        .cart-item-quantity input {
            width: 50px;
            text-align: center;
            margin: 0 10px;
        }

        .remove-item {
            color: #ff5722;
            text-decoration: none;
            margin-left: 20px;
        }

        .cart-summary {
            padding: 20px;
            text-align: right;
            border-top: 1px solid #eee;
        }

        .total-price {
            font-size: 20px;
            font-weight: bold;
            color: #2c7873;
        }

        .checkout-btn {
            display: inline-block;
            background: #2c7873;
            color: white;
            padding: 12px 30px;
            margin-top: 20px;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background 0.3s;
        }

        .checkout-btn:hover {
            background: #6fb98f;
        }

        .empty-cart {
            text-align: center;
            padding: 40px;
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

        /* Pop-up Styles */
        .notification {
            position: fixed;
            top: -100px; /* Mulai di luar layar */
            left: 50%;
            transform: translateX(-50%);
            background: white;
            padding: 15px 30px;
            border-radius: 12px; /* Sudut tumpul */
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 1000;
            transition: top 0.5s ease; /* Animasi saat muncul */
            max-width: 80%;
            text-align: center;
            border: 1px solid #e0e0e0;
        }

        .notification.show {
            top: 20px; /* Posisi saat muncul */
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
        <div class="cart-container">
            <div class="cart-header">
                <h2>Keranjang Belanja</h2>
            </div>

            <div class="cart-items">
                <?php if (!empty($_SESSION['cart'])): ?>
                    <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                        <div class="cart-item">
                            <img src="/myveridla/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                            <div class="cart-item-info">
                                <div class="cart-item-title"><?= htmlspecialchars($item['name']) ?></div>
                                <div class="cart-item-price"><?= formatPrice($item['price']) ?></div>
                                <div class="cart-item-quantity">
                                    <span>Jumlah: <?= $item['quantity'] ?></span>
                                </div>
                            </div>
                            <a href="cart.php?remove=<?= $id ?>" class="remove-item">Hapus</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-cart">
                        <h3>Keranjang Anda kosong</h3>
                        <p>Silakan tambahkan produk dari halaman utama</p>
                        <a href="index.php" class="checkout-btn">Kembali ke Beranda</a>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (!empty($_SESSION['cart'])): ?>
                <div class="cart-summary">
                    <div class="total-price">Total: <?= formatPrice($total) ?></div>
                    <button class="checkout-btn" id="checkoutBtn">Lanjut ke Pembayaran</button>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pop-up Notifikasi -->
        <div class="notification" id="notification">
            Proses pembelian sedang diperbarui.<br>
            Silakan hubungi <strong>088291790570</strong> untuk informasi lebih lanjut.
        </div>
    </main>

    <!-- Footer -->
    <footer>
        
    </footer>

    <script>
        // Fungsi untuk menampilkan pop-up
        document.getElementById("checkoutBtn").onclick = function() {
            const notif = document.getElementById('notification');
            notif.classList.add('show'); // Tambahkan kelas untuk menampilkan pop-up
            
            // Mengatur waktu untuk menyembunyikan pop-up setelah 3 detik
            setTimeout(() => {
                notif.classList.remove('show'); // Hapus kelas untuk menyembunyikan pop-up
            }, 3000);
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
