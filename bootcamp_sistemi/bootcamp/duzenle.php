<?php
require_once '../config.php';

$id = $_GET['id'] ?? '';

if (empty($id)) {
    header('Location: liste.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ad = $_POST['ad'] ?? '';
    $baslangic = $_POST['baslangic'] ?? '';
    $bitis = $_POST['bitis'] ?? '';

    try {
        $stmt = $conn->prepare("UPDATE bootcampler SET bootcamp_ad = ?, baslangic_tarihi = ?, bitis_tarihi = ? WHERE program_id = ?");
        $stmt->execute([$ad, $baslangic, $bitis, $id]);
        $message = "Bootcamp bilgileri başarıyla güncellendi.";
        $messageType = "success";
    } catch(PDOException $e) {
        $message = "Hata: " . $e->getMessage();
        $messageType = "danger";
    }
}

try {
    $stmt = $conn->prepare("SELECT * FROM bootcampler WHERE program_id = ?");
    $stmt->execute([$id]);
    $bootcamp = $stmt->fetch();

    if (!$bootcamp) {
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
    <title>Bootcamp Düzenle - Bootcamp Yönetim Sistemi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Bootcamp Düzenle</h2>
        
        <?php if (isset($message)): ?>
            <div class="alert alert-<?php echo $messageType; ?>" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="mt-4">
            <div class="mb-3">
                <label for="ad" class="form-label">Bootcamp Adı</label>
                <input type="text" class="form-control" id="ad" name="ad" value="<?php echo htmlspecialchars($bootcamp['bootcamp_ad']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="baslangic" class="form-label">Başlangıç Tarihi</label>
                <input type="date" class="form-control" id="baslangic" name="baslangic" value="<?php echo htmlspecialchars($bootcamp['baslangic_tarihi']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="bitis" class="form-label">Bitiş Tarihi</label>
                <input type="date" class="form-control" id="bitis" name="bitis" value="<?php echo htmlspecialchars($bootcamp['bitis_tarihi']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Güncelle</button>
            <a href="liste.php" class="btn btn-secondary">Geri Dön</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 