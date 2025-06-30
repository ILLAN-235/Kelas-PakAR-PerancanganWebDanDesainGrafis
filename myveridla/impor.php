<?php
// impor.php - Skrip untuk mengimpor database dari file SQL

// Konfigurasi Database (sesuaikan dengan milikmu)
$host = "localhost";
$db_user = "MyVeridla";
$db_pass = "nurendra886";
$db_name = "toko_veridla";
$sql_file = "toko_veridla.sql";

echo "<h2>Proses Import Database</h2>";

// 1. Buat Koneksi ke MySQL Server
$conn = new mysqli($host, $db_user, $db_pass);
if ($conn->connect_error) {
    die("<p style='color:red'>Gagal koneksi ke server MySQL: " . $conn->connect_error . "</p>");
}
echo "<p>✓ Terhubung ke server MySQL</p>";

// 2. Buat Database jika belum ada
if ($conn->query("CREATE DATABASE IF NOT EXISTS $db_name") === TRUE) {
    echo "<p>✓ Database '$db_name' siap digunakan</p>";
} else {
    die("<p style='color:red'>Gagal membuat database: " . $conn->error . "</p>");
}

// 3. Pilih Database
$conn->select_db($db_name);

// 4. Baca dan Eksekusi File SQL
if (!file_exists($sql_file)) {
    die("<p style='color:red'>File SQL tidak ditemukan: $sql_file</p>");
}

$sql_content = file_get_contents($sql_file);
if (empty($sql_content)) {
    die("<p style='color:red'>File SQL kosong atau tidak terbaca</p>");
}

// Pisahkan per query
$queries = explode(';', $sql_content);
$success_count = 0;
$error_count = 0;

echo "<p>Memulai proses import...</p>";
echo "<pre>";

foreach ($queries as $query) {
    $query = trim($query);
    if (!empty($query)) {
        if ($conn->query($query) === TRUE) {
            $success_count++;
        } else {
            $error_count++;
            echo "Error: " . $conn->error . "\n";
        }
    }
}

echo "</pre>";
echo "<h3>Hasil Import:</h3>";
echo "<p>✔ Query berhasil: $success_count</p>";
echo "<p>✖ Query gagal: $error_count</p>";

// 5. Verifikasi Tabel
$tables = $conn->query("SHOW TABLES");
if ($tables->num_rows > 0) {
    echo "<h3>Tabel yang terimport:</h3>";
    while ($table = $tables->fetch_array()) {
        echo "<p>• " . $table[0] . "</p>";
    }
} else {
    echo "<p style='color:orange'>⚠ Tidak ada tabel yang terimport</p>";
}

$conn->close();

echo "<h3 style='color:green'>Proses selesai!</h3>";
echo "<p>Jangan lupa hapus file ini setelah import berhasil!</p>";
?>
