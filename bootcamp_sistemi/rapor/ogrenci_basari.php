<?php
require_once '../config.php';

$id = $_GET['id'] ?? '';

if (empty($id)) {
    header('Location: index.php');
    exit;
}

try {
  
    $stmt = $conn->prepare("SELECT * FROM ogrenciler WHERE ogrenci_id = ?");
    $stmt->execute([$id]);
    $ogrenci = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$ogrenci) {
        header('Location: index.php');
        exit;
    }

  
    $stmt = $conn->prepare("
        SELECT b.*, CONCAT(e.egitmen_ad, ' ', e.egitmen_soyad) as egitmen_adsoyad
        FROM bootcampler b
        INNER JOIN ogrenci_bootcamp ob ON b.program_id = ob.program_id
        LEFT JOIN egitmenler e ON b.egitmen_id = e.egitmen_id
        WHERE ob.ogrenci_id = ?
    ");
    $stmt->execute([$id]);
    $bootcampler = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("
        SELECT d.ders_ad,
               GROUP_CONCAT(CONCAT(n.not_tipi, ': ', n.puan) SEPARATOR ', ') as notlar,
               fn_OrtalamaNot(?) as ortalama,
               (SELECT COUNT(*) FROM katilim k WHERE k.ogrenci_id = ? AND k.ders_id = d.ders_id AND k.katilim_durumu = 'Var') as katilim_sayisi,
               (SELECT COUNT(*) FROM katilim k WHERE k.ogrenci_id = ? AND k.ders_id = d.ders_id) as toplam_ders
        FROM dersler d
        LEFT JOIN notlar n ON d.ders_id = n.ders_id AND n.ogrenci_id = ?
        INNER JOIN bootcampler b ON d.program_id = b.program_id
        INNER JOIN ogrenci_bootcamp ob ON b.program_id = ob.program_id
        WHERE ob.ogrenci_id = ?
        GROUP BY d.ders_id
    ");
    $stmt->execute([$id, $id, $id, $id, $id]);
    $dersler = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <title>Öğrenci Başarı Raporu - Bootcamp Yönetim Sistemi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Öğrenci Başarı Raporu</h2>
        
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Öğrenci Bilgileri</h5>
                <p class="card-text">
                    <strong>Ad Soyad:</strong> <?php echo htmlspecialchars($ogrenci['ogrenci_ad'] . ' ' . $ogrenci['ogrenci_soyad']); ?><br>
                    <strong>E-posta:</strong> <?php echo htmlspecialchars($ogrenci['ogrenci_mail']); ?><br>
                    <strong>Kayıt Tarihi:</strong> <?php echo htmlspecialchars($ogrenci['kayit_tarihi']); ?>
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <h4>Katıldığı Bootcampler</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Bootcamp</th>
                            <th>Eğitmen</th>
                            <th>Tarih</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bootcampler as $bootcamp): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($bootcamp['bootcamp_ad']); ?></td>
                            <td><?php echo htmlspecialchars($bootcamp['egitmen_adsoyad']); ?></td>
                            <td>
                                <?php 
                                echo htmlspecialchars($bootcamp['baslangic_tarihi']) . ' - ' . 
                                     htmlspecialchars($bootcamp['bitis_tarihi']); 
                                ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="col-md-6">
                <h4>Ders Başarı Durumu</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Ders</th>
                            <th>Notlar</th>
                            <th>Ortalama</th>
                            <th>Katılım</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dersler as $ders): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($ders['ders_ad']); ?></td>
                            <td><?php echo htmlspecialchars($ders['notlar'] ?? 'Not girilmemiş'); ?></td>
                            <td><?php echo number_format($ders['ortalama'] ?? 0, 2); ?></td>
                            <td>
                                <?php 
                                $katilim_orani = ($ders['toplam_ders'] > 0) ? 
                                    ($ders['katilim_sayisi'] / $ders['toplam_ders'] * 100) : 0;
                                echo number_format($katilim_orani, 1) . '%';
                                ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <a href="index.php" class="btn btn-secondary mt-3">Geri Dön</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 