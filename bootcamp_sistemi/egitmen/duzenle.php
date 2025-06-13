<?php
require_once '../config/db.php';

if (!isset($_GET['id'])) {
    header("Location: liste.php");
    exit();
}

$id = $_GET['id'];
 
$sql = "SELECT * FROM egitmenler WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$egitmen = $result->fetch_assoc();

if (!$egitmen) {
    header("Location: liste.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ad = $_POST['ad'];
    $soyad = $_POST['soyad'];
    $email = $_POST['email'];
    $telefon = $_POST['telefon'];
    $uzmanlik = $_POST['uzmanlik'];
    $deneyim = $_POST['deneyim'];

    $sql = "UPDATE egitmenler SET ad=?, soyad=?, email=?, telefon=?, uzmanlik=?, deneyim=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssii", $ad, $soyad, $email, $telefon, $uzmanlik, $deneyim, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Eğitmen bilgileri başarıyla güncellendi!'); window.location.href='liste.php';</script>";
    } else {
        echo "<script>alert('Hata oluştu: " . $stmt->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eğitmen Düzenle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Eğitmen Düzenle</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="ad" class="form-label">Ad</label>
                <input type="text" class="form-control" id="ad" name="ad" value="<?php echo $egitmen['ad']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="soyad" class="form-label">Soyad</label>
                <input type="text" class="form-control" id="soyad" name="soyad" value="<?php echo $egitmen['soyad']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">E-posta</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $egitmen['email']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="telefon" class="form-label">Telefon</label>
                <input type="tel" class="form-control" id="telefon" name="telefon" value="<?php echo $egitmen['telefon']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="uzmanlik" class="form-label">Uzmanlık Alanı</label>
                <input type="text" class="form-control" id="uzmanlik" name="uzmanlik" value="<?php echo $egitmen['uzmanlik']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="deneyim" class="form-label">Deneyim (Yıl)</label>
                <input type="number" class="form-control" id="deneyim" name="deneyim" value="<?php echo $egitmen['deneyim']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Güncelle</button>
            <a href="liste.php" class="btn btn-secondary">Listeye Dön</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 