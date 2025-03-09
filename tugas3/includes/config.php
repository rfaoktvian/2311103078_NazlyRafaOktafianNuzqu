<?php
// Konfigurasi database
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', ''); // Biasanya password default kosong di Laragon
define('DB_NAME', 'website_db');

// Menghubungkan ke database
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

// Periksa koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Buat database jika belum ada
$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
if (mysqli_query($conn, $sql)) {
    // Pilih database
    mysqli_select_db($conn, DB_NAME);
    
    // Buat tabel jika belum ada
    $sql = "CREATE TABLE IF NOT EXISTS messages (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL,
        email VARCHAR(50) NOT NULL,
        message TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if (!mysqli_query($conn, $sql)) {
        echo "Error membuat tabel: " . mysqli_error($conn);
    }
} else {
    echo "Error membuat database: " . mysqli_error($conn);
}
?>