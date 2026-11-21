<?php
// Koneksi dan pembuatan database otomatis untuk Laragon.
$host = 'localhost';
$database = 'klinikzakwan_paket1';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `$database`");
    $pdo->exec("CREATE TABLE IF NOT EXISTS pasien (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nama VARCHAR(100) NOT NULL,
        username VARCHAR(50) NOT NULL,
        umur INT NOT NULL,
        jenis_kelamin ENUM('Laki-laki','Perempuan') NOT NULL,
        no_hp VARCHAR(20) NOT NULL,
        foto VARCHAR(255) DEFAULT NULL,
        keluhan TEXT DEFAULT NULL,
        tanggal_masuk DATE DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // Menambahkan kolom jika database dari versi sebelumnya sudah ada.
    $columns = $pdo->query("SHOW COLUMNS FROM pasien")->fetchAll(PDO::FETCH_COLUMN);
    $updates = [
        'username' => "ALTER TABLE pasien ADD username VARCHAR(50) NOT NULL DEFAULT '' AFTER nama",
        'no_hp' => "ALTER TABLE pasien ADD no_hp VARCHAR(20) NOT NULL DEFAULT '' AFTER jenis_kelamin",
        'foto' => "ALTER TABLE pasien ADD foto VARCHAR(255) DEFAULT NULL AFTER no_hp",
        'keluhan' => "ALTER TABLE pasien ADD keluhan TEXT DEFAULT NULL",
        'tanggal_masuk' => "ALTER TABLE pasien ADD tanggal_masuk DATE DEFAULT NULL"
    ];
    foreach ($updates as $column => $sql) {
        if (!in_array($column, $columns, true)) $pdo->exec($sql);
    }
} catch (PDOException $e) {
    die('Koneksi database gagal. Pastikan MySQL Laragon aktif. ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}
