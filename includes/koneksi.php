<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = "localhost";
$port = 3306;
$dbname = "db_playlist";
$username = "root";
$password = "";
$charset = "utf8mb4";

$dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

$conn = null;

try {
    $conn = new PDO($dsn, $username, $password, $options);
} catch (\PDOException $e) {
    echo "<p style='color:red; font-weight:bold'>Koneksi database gagal:</p>";
    echo "<pre>" . $e->getMessage() . "</pre>";
    echo "<p>Pastikan database '$dbname' sudah dibuat dan server MySQL berjalan di port $port</p>";
    die();
}
