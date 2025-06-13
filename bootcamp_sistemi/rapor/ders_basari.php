<?php
require_once '../config.php';

$id = $_GET['id'] ?? '';

if (empty($id)) {
    header('Location: index.php');
    exit;
}

try {
  
    $stmt = $conn->prepare("
        SELECT d.*, b.bootcamp_ad
        FROM dersler d
        LEFT JOIN bootcampler b ON d.program_id = b.program_id
        WHERE d.ders_id = ?
    ");
    $stmt->execute([$id]);
    $ders = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$ders) {
        header('Location: index.php');
        exit;
    }

  
    $stmt = $conn->prepare("
        SELECT o.ogrenci_ad, o.ogrenci_soyad,
               GROUP_CONCAT(CONCAT(n.not_tipi, ': ', n.puan) SEPARATOR ', ') as notlar,
               fn_OrtalamaNot(o.ogrenci_id) as ortalama,
               COUNT(CASE WHEN k.katilim_durumu = 'Var' THEN 1 END) as katilim_sayisi,
               COUNT(k.yoklama_id) as toplam_ders
        FROM ogrenciler o
        INNER JOIN ogrenci_bootcamp ob ON o.ogrenci_id = ob.ogrenci_id
        LEFT JOIN notlar n ON o.ogrenci_id = n.ogrenci_id AND n.ders_id = ?
        LEFT JOIN katilim k ON o.ogrenci_id = k.ogrenci_id AND k.ders_id = ?
        WHERE ob.program_id = ?
        GROUP BY o.ogrenci_id
        ORDER BY o.ogrenci_ad, o.ogrenci_soyad
    ");
    $stmt->execute([$id, $id, $ders['program_id']]);
    $ogrenciler = $stmt->fetchAll(PDO::FETCH_ASSOC);


    $not_dagilimi = [
        '90-100' => 0,
        '80-89' => 0,
        '70-79' => 0,
        '60-69' => 0,
        '0-59' => 0
    ];

    foreach ($ogrenciler as $ogrenci) {
        $ortalama = $ogrenci['ortalama'];
        if ($ortalama >= 90) $not_dagilimi['90-100']++;
        elseif ($ortalama >= 80) $not_dagilimi['80-89']++;
        elseif ($ortalama >= 70) $not_dagilimi['70-79']++;
        elseif ($ortalama >= 60) $not_dagilimi['60-69']++;
        else $not_dagilimi['0-59']++;
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
    <title>Ders Başarı Raporu - Bootcamp Yönetim Sistemi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Ders Başarı Raporu</h2>
        
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($ders['ders_ad']); ?></h5>
                <p class="card-text">
                    <strong>Bootcamp:</strong> <?php echo htmlspecialchars($ders['bootcamp_ad']); ?>
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <h4>Öğrenci Başarı Durumları</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Öğrenci</th>
                            <th>Notlar</th>
                            <th>Ortalama</th>
                            <th>Katılım Oranı</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ogrenciler as $ogrenci): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($ogrenci['ogrenci_ad'] . ' ' . $ogrenci['ogrenci_soyad']); ?></td>
                            <td><?php echo htmlspecialchars($ogrenci['notlar'] ?? 'Not girilmemiş'); ?></td>
                            <td><?php echo number_format($ogrenci['ortalama'] ?? 0, 2); ?></td>
                            <td>
                                <?php 
                                $katilim_orani = ($ogrenci['toplam_ders'] > 0) ? 
                                    ($ogrenci['katilim_sayisi'] / $ogrenci['toplam_ders'] * 100) : 0;
                                echo number_format($katilim_orani, 1) . '%';
                                ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="col-md-4">
                <h4>Not Dağılımı</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Not Aralığı</th>
                            <th>Öğrenci Sayısı</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($not_dagilimi as $aralik => $sayi): ?>
                        <tr>
                            <td><?php echo $aralik; ?></td>
                            <td><?php echo $sayi; ?></td>
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