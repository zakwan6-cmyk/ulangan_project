<?php
// Koneksi database KlinikZakwan Paket 3
$host = "localhost";
$user = "root";
$password = "";
$db = "klinikzakwan_paket3";

$conn = new mysqli($host, $user, $password, $db);
if ($conn->connect_error) {
    // Database dibuat otomatis jika belum ada
    $temp = new mysqli($host, $user, $password);
    if ($temp->connect_error) die("Koneksi MySQL gagal: " . $temp->connect_error);
    $temp->query("CREATE DATABASE IF NOT EXISTS `$db`");
    $temp->close();
    $conn = new mysqli($host, $user, $password, $db);
}
$conn->set_charset("utf8mb4");

// Tabel dibuat otomatis jika belum ada
$conn->query("CREATE TABLE IF NOT EXISTS pasien (
    id INT AUTO_INCREMENT PRIMARY KEY,
    foto VARCHAR(255) DEFAULT NULL,
    nama_pasien VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL,
    usia INT NOT NULL,
    alergi_obat VARCHAR(255) DEFAULT '-',
    jenis_kelamin VARCHAR(20) NOT NULL,
    alamat TEXT NOT NULL,
    no_hp VARCHAR(30) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");
$conn->query("CREATE TABLE IF NOT EXISTS kunjungan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_pasien INT NOT NULL,
    tanggal DATE NOT NULL,
    keluhan TEXT,
    FOREIGN KEY (id_pasien) REFERENCES pasien(id) ON DELETE CASCADE
)");
?>
