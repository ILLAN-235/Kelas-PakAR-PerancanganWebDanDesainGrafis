<?php
$servername = "localhost";
$username = "u747399580_MyVeridla";
$password = "nurendra886";
$dbname = "u747399580_toko_veridla";

// Koneksi database utama
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Fungsi setup database jika diperlukan
function setupDatabase($sqlFile) {
    global $servername, $username, $password, $dbname;

    $connSetup = new mysqli($servername, $username, $password);
    if ($connSetup->connect_error) {
        die("Koneksi server gagal: " . $connSetup->connect_error);
    }

    // Buat database jika belum ada
    $connSetup->query("CREATE DATABASE IF NOT EXISTS $dbname");
    $connSetup->select_db($dbname);

    $sql = file_get_contents($sqlFile);
    if ($sql === false) {
        die("Gagal membaca file SQL.");
    }

    $queries = explode(";", $sql);
    foreach ($queries as $query) {
        $query = trim($query);
        if (!empty($query)) {
            if ($connSetup->query($query) === false) {
                echo "Error saat eksekusi SQL: " . $connSetup->error . "<br>";
            }
        }
    }

    echo "Database berhasil di-setup dari file SQL";
    $connSetup->close();
}

// Kalau mau setup lagi, hapus komentar ini:
// setupDatabase('u747399580_toko_veridla.sql');
?>
