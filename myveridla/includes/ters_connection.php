<?php
include 'config.php'; // Menyertakan file konfigurasi

// Uji koneksi
if ($conn) {
    echo "Koneksi ke database berhasil!";
} else {
    echo "Koneksi ke database gagal: " . $conn->connect_error;
}

$conn->close(); // Menutup koneksi
?>
