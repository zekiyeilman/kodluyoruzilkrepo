<?php
require_once '../config.php';

try {
    
    $stmt = $conn->prepare("SELECT * FROM bootcampler ORDER BY bootcamp_ad");
    $stmt->execute();
    $bootcampler = $stmt->fetchAll();

    
    $stmt = $conn->prepare("SELECT * FROM dersler ORDER BY ders_ad");
    $stmt->execute();
    $dersler = $stmt->fetchAll();

    
    $stmt = $conn->prepare("SELECT * FROM ogrenciler ORDER BY ogrenci_ad, ogrenci_soyad");
    $stmt->execute();
    $ogrenciler = $stmt->fetchAll();
} catch(PDOException $e) {
    die("Hata: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raporlar - Bootcamp Yönetim Sistemi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Raporlar</h2>

        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Öğrenci Başarı Raporu</h5>
                        <p class="card-text">Öğrencilerin bootcamp ve derslerdeki başarı durumlarını görüntüleyin.</p>
                        <form action="ogrenci_basari.php" method="GET">
                            <div class="mb-3">
                                <label for="ogrenci_id" class="form-label">Öğrenci Seçin</label>
                                <select class="form-select" id="ogrenci_id" name="id" required>
                                    <option value="">Öğrenci Seçin</option>
                                    <?php foreach ($ogrenciler as $ogrenci): ?>
                                        <option value="<?php echo $ogrenci['ogrenci_id']; ?>">
                                            <?php echo htmlspecialchars($ogrenci['ogrenci_ad'] . ' ' . $ogrenci['ogrenci_soyad']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Raporu Görüntüle</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Bootcamp Katılım Raporu</h5>
                        <p class="card-text">Bootcamp'lerdeki derslere katılım oranlarını görüntüleyin.</p>
                        <form action="bootcamp_katilim.php" method="GET">
                            <div class="mb-3">
                                <label for="program_id" class="form-label">Bootcamp Seçin</label>
                                <select class="form-select" id="program_id" name="id" required>
                                    <option value="">Bootcamp Seçin</option>
                                    <?php foreach ($bootcampler as $bootcamp): ?>
                                        <option value="<?php echo $bootcamp['program_id']; ?>">
                                            <?php echo htmlspecialchars($bootcamp['bootcamp_ad']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Raporu Görüntüle</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Ders Başarı Raporu</h5>
                        <p class="card-text">Derslerdeki öğrenci başarı durumlarını görüntüleyin.</p>
                        <form action="ders_basari.php" method="GET">
                            <div class="mb-3">
                                <label for="ders_id" class="form-label">Ders Seçin</label>
                                <select class="form-select" id="ders_id" name="id" required>
                                    <option value="">Ders Seçin</option>
                                    <?php foreach ($dersler as $ders): ?>
                                        <option value="<?php echo $ders['ders_id']; ?>">
                                            <?php echo htmlspecialchars($ders['ders_ad']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Raporu Görüntüle</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <a href="../index.php" class="btn btn-secondary mt-3">Ana Sayfa</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 