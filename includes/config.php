<?php
$servername = "localhost";
$username = "MyVeridla";  
$password = "nurendra886";
$dbname = "toko_veridla"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}
?>
