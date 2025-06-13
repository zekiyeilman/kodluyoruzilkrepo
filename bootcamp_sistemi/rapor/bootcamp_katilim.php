<?php
require_once '../config.php';

$id = $_GET['id'] ?? '';

if (empty($id)) {
    header('Location: index.php');
    exit;
}

try {
   
    $stmt = $conn->prepare("
        SELECT b.*, CONCAT(e.egitmen_ad, ' ', e.egitmen_soyad) as egitmen_adsoyad
        FROM bootcampler b
        LEFT JOIN egitmenler e ON b.egitmen_id = e.egitmen_id
        WHERE b.program_id = ?
    ");
    $stmt->execute([$id]);
    $bootcamp = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$bootcamp) {
        header('Location: index.php');
        exit;
    }

   
    $stmt = $conn->prepare("SELECT * FROM dersler WHERE program_id = ?");
    $stmt->execute([$id]);
    $dersler = $stmt->fetchAll(PDO::FETCH_ASSOC);

 
    $ders_katilim = [];
    foreach ($dersler as $ders) {
        $stmt = $conn->prepare("
            SELECT o.ogrenci_ad, o.ogrenci_soyad,
                   COUNT(CASE WHEN k.katilim_durumu = 'Var' THEN 1 END) as katilim_sayisi,
                   COUNT(k.yoklama_id) as toplam_ders
            FROM ogrenciler o
            INNER JOIN ogrenci_bootcamp ob ON o.ogrenci_id = ob.ogrenci_id
            LEFT JOIN katilim k ON o.ogrenci_id = k.ogrenci_id AND k.ders_id = ?
            WHERE ob.program_id = ?
            GROUP BY o.ogrenci_id
            ORDER BY o.ogrenci_ad, o.ogrenci_soyad
        ");
        $stmt->execute([$ders['ders_id'], $id]);
        $ders_katilim[$ders['ders_id']] = [
            'ders' => $ders,
            'katilimlar' => $stmt->fetchAll(PDO::FETCH_ASSOC)
        ];
    }

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
    <title>Bootcamp Katılım Raporu - Bootcamp Yönetim Sistemi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Bootcamp Katılım Raporu</h2>
        
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($bootcamp['bootcamp_ad']); ?></h5>
                <p class="card-text">
                    <strong>Eğitmen:</strong> <?php echo htmlspecialchars($bootcamp['egitmen_adsoyad']); ?><br>
                    <strong>Tarih:</strong> <?php echo htmlspecialchars($bootcamp['baslangic_tarihi']) . ' - ' . htmlspecialchars($bootcamp['bitis_tarihi']); ?>
                </p>
            </div>
        </div>

        <?php foreach ($ders_katilim as $ders_id => $data): ?>
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><?php echo htmlspecialchars($data['ders']['ders_ad']); ?></h5>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Öğrenci</th>
                            <th>Katılım Sayısı</th>
                            <th>Toplam Ders</th>
                            <th>Katılım Oranı</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['katilimlar'] as $katilim): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($katilim['ogrenci_ad'] . ' ' . $katilim['ogrenci_soyad']); ?></td>
                            <td><?php echo $katilim['katilim_sayisi']; ?></td>
                            <td><?php echo $katilim['toplam_ders']; ?></td>
                            <td>
                                <?php 
                                $oran = ($katilim['toplam_ders'] > 0) ? 
                                    ($katilim['katilim_sayisi'] / $katilim['toplam_ders'] * 100) : 0;
                                echo number_format($oran, 1) . '%';
                                ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endforeach; ?>

        <a href="index.php" class="btn btn-secondary mt-3">Geri Dön</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 