<?php
require_once '../config.php';

$id = $_GET['id'] ?? '';

if (empty($id)) {
    header('Location: liste.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ad = $_POST['ad'] ?? '';
    $soyad = $_POST['soyad'] ?? '';
    $telefon = $_POST['telefon'] ?? '';
    $email = $_POST['email'] ?? '';
    $kayit_tarihi = $_POST['kayit_tarihi'] ?? date('Y-m-d');

    try {
        $stmt = $conn->prepare("UPDATE ogrenciler SET ogrenci_ad = ?, ogrenci_soyad = ?, telefon = ?, ogrenci_mail = ?, kayit_tarihi = ? WHERE ogrenci_id = ?");
        $stmt->execute([$ad, $soyad, $telefon, $email, $kayit_tarihi, $id]);
        $message = "Öğrenci bilgileri başarıyla güncellendi.";
        $messageType = "success";
    } catch(PDOException $e) {
        $message = "Hata: " . $e->getMessage();
        $messageType = "danger";
    }
}

try {
    $stmt = $conn->prepare("SELECT * FROM ogrenciler WHERE ogrenci_id = ?");
    $stmt->execute([$id]);
    $ogrenci = $stmt->fetch();

    if (!$ogrenci) {
        header('Location: liste.php');
        exit;
    }
} catch(PDOException $e) {
    die("Hata: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Öğrenci Düzenle - Bootcamp Yönetim Sistemi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Öğrenci Bilgilerini Düzenle</h2>
        
        <?php if (isset($message)): ?>
            <div class="alert alert-<?php echo $messageType; ?>" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="mt-4">
            <div class="mb-3">
                <label for="ad" class="form-label">Ad</label>
                <input type="text" class="form-control" id="ad" name="ad" value="<?php echo htmlspecialchars($ogrenci['ogrenci_ad']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="soyad" class="form-label">Soyad</label>
                <input type="text" class="form-control" id="soyad" name="soyad" value="<?php echo htmlspecialchars($ogrenci['ogrenci_soyad']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="telefon" class="form-label">Telefon</label>
                <input type="tel" class="form-control" id="telefon" name="telefon" value="<?php echo htmlspecialchars($ogrenci['telefon']); ?>">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">E-posta</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($ogrenci['ogrenci_mail']); ?>">
            </div>
            <div class="mb-3">
                <label for="kayit_tarihi" class="form-label">Kayıt Tarihi</label>
                <input type="date" class="form-control" id="kayit_tarihi" name="kayit_tarihi" value="<?php echo htmlspecialchars($ogrenci['kayit_tarihi']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Güncelle</button>
            <a href="liste.php" class="btn btn-secondary">Geri Dön</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 