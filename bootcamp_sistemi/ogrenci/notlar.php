<?php
require_once '../config.php';

$id = $_GET['id'] ?? '';

if (empty($id)) {
    header('Location: liste.php');
    exit;
}

try {

    $stmt = $conn->prepare("SELECT * FROM ogrenciler WHERE ogrenci_id = ?");
    $stmt->execute([$id]);
    $ogrenci = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$ogrenci) {
        header('Location: liste.php');
        exit;
    }

  
    $stmt = $conn->prepare("CALL sp_OgrenciNotlari(?)");
    $stmt->execute([$id]);
    $notlar = $stmt->fetchAll(PDO::FETCH_ASSOC);

  
    $stmt = $conn->prepare("SELECT fn_OrtalamaNot(?) as ortalama");
    $stmt->execute([$id]);
    $ortalama = $stmt->fetch(PDO::FETCH_ASSOC);

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
    <title>Öğrenci Notları - Bootcamp Yönetim Sistemi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Öğrenci Notları</h2>
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Öğrenci Bilgileri</h5>
                <p class="card-text">
                    <strong>Ad Soyad:</strong> <?php echo htmlspecialchars($ogrenci['ogrenci_ad'] . ' ' . $ogrenci['ogrenci_soyad']); ?><br>
                    <strong>Ortalama:</strong> <?php echo number_format($ortalama['ortalama'] ?? 0, 2); ?>
                </p>
            </div>
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Ders</th>
                    <th>Not Tipi</th>
                    <th>Puan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notlar as $not): ?>
                <tr>
                    <td><?php echo htmlspecialchars($not['ders_ad']); ?></td>
                    <td><?php echo htmlspecialchars($not['not_tipi']); ?></td>
                    <td><?php echo htmlspecialchars($not['puan']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="liste.php" class="btn btn-secondary">Geri Dön</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 