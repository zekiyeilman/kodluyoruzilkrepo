<?php
require_once '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = uniqid();
    $ad = $_POST['ad'] ?? '';
    $baslangic = $_POST['baslangic'] ?? '';
    $bitis = $_POST['bitis'] ?? '';

    try {
        $stmt = $conn->prepare("INSERT INTO bootcampler (program_id, bootcamp_ad, baslangic_tarihi, bitis_tarihi) VALUES (?, ?, ?, ?)");
        $stmt->execute([$id, $ad, $baslangic, $bitis]);
        $message = "Bootcamp başarıyla oluşturuldu.";
        $messageType = "success";
    } catch(PDOException $e) {
        $message = "Hata: " . $e->getMessage();
        $messageType = "danger";
    }
}

// Eğitmenleri getir
try {
    $stmt = $conn->prepare("SELECT * FROM egitmenler");
    $stmt->execute();
    $egitmenler = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Hata: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeni Bootcamp - Bootcamp Yönetim Sistemi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Yeni Bootcamp Oluştur</h2>
        
        <?php if (isset($message)): ?>
            <div class="alert alert-<?php echo $messageType; ?>" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="mt-4">
            <div class="mb-3">
                <label for="ad" class="form-label">Bootcamp Adı</label>
                <input type="text" class="form-control" id="ad" name="ad" required>
            </div>
            <div class="mb-3">
                <label for="baslangic" class="form-label">Başlangıç Tarihi</label>
                <input type="date" class="form-control" id="baslangic" name="baslangic" required>
            </div>
            <div class="mb-3">
                <label for="bitis" class="form-label">Bitiş Tarihi</label>
                <input type="date" class="form-control" id="bitis" name="bitis" required>
            </div>
            <button type="submit" class="btn btn-primary">Oluştur</button>
            <a href="liste.php" class="btn btn-secondary">Geri Dön</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 