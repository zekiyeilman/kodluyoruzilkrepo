<?php
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ad = $_POST['ad'];
    $soyad = $_POST['soyad'];
    $email = $_POST['email'];
    $telefon = $_POST['telefon'];
    $uzmanlik = $_POST['uzmanlik'];
    $deneyim = $_POST['deneyim'];

    $sql = "INSERT INTO egitmenler (ad, soyad, email, telefon, uzmanlik, deneyim) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);

    try {
        if ($stmt->execute([$ad, $soyad, $email, $telefon, $uzmanlik, $deneyim])) {
            echo "<script>alert('Eğitmen başarıyla eklendi!'); window.location.href='liste.php';</script>";
        } else {
            echo "<script>alert('Hata oluştu!');</script>";
        }
    } catch(PDOException $e) {
        echo "<script>alert('Hata oluştu: " . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeni Eğitmen Ekle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Yeni Eğitmen Ekle</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="ad" class="form-label">Ad</label>
                <input type="text" class="form-control" id="ad" name="ad" required>
            </div>
            <div class="mb-3">
                <label for="soyad" class="form-label">Soyad</label>
                <input type="text" class="form-control" id="soyad" name="soyad" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">E-posta</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="telefon" class="form-label">Telefon</label>
                <input type="tel" class="form-control" id="telefon" name="telefon" required>
            </div>
            <div class="mb-3">
                <label for="uzmanlik" class="form-label">Uzmanlık Alanı</label>
                <input type="text" class="form-control" id="uzmanlik" name="uzmanlik" required>
            </div>
            <div class="mb-3">
                <label for="deneyim" class="form-label">Deneyim (Yıl)</label>
                <input type="number" class="form-control" id="deneyim" name="deneyim" required>
            </div>
            <button type="submit" class="btn btn-primary">Eğitmen Ekle</button>
            <a href="liste.php" class="btn btn-secondary">Listeye Dön</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 