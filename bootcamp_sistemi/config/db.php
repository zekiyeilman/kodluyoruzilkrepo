<?php
$servername = "localhost";
$username = "root";
$password = "root"; 

try {
    
    $conn = new PDO("mysql:host=$servername;charset=utf8", $username, $password);
    

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "CREATE DATABASE IF NOT EXISTS bootcamp_sistemi CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    $conn->exec($sql);
    
    
    $conn->exec("USE bootcamp_sistemi");
    
   
    $sql = "CREATE TABLE IF NOT EXISTS egitmenler (
        id INT AUTO_INCREMENT PRIMARY KEY,
        ad VARCHAR(50) NOT NULL,
        soyad VARCHAR(50) NOT NULL,
        email VARCHAR(100) NOT NULL,
        telefon VARCHAR(20),
        uzmanlik VARCHAR(100),
        deneyim INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    $conn->exec($sql);
    
   
    $conn->exec("SET NAMES utf8");
    
} catch(PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}
?> 