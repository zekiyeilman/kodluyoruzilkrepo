<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "bootcamp_sistemi";

try {
    $conn = new PDO("mysql:host=$servername;port=3307;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}
?> 