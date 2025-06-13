<?php
require_once '../config.php';

$id = $_GET['id'] ?? '';

if (empty($id)) {
    header('Location: liste.php');
    exit;
}

// Not kaydetme işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        foreach ($_POST['not'] as $ogrenci_id => $not_bilgisi) {
            if (!empty($not_bilgisi['puan'])) {
                $not_id = uniqid();
                $stmt = $conn->prepare("INSERT INTO notlar (not_id, ogrenci_id, ders_id, not_tipi, puan) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$not_id, $ogrenci_id, $id, $not_bilgisi['tip'], $not_bilgisi['puan']]);
            }
        }
        $message = "Notlar başarıyla kaydedildi.";
        $messageType = "success";
    } catch(PDOException $e) {
        $message = "Hata: " . $e->getMessage();
        $messageType = "danger";
    }
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
        header('Location: liste.php');
        exit;
    }

   
    $stmt = $conn->prepare("
        SELECT o.*, 
               GROUP_CONCAT(CONCAT(n.not_tipi, ': ', n.puan) SEPARATOR ', ') as notlar
        FROM ogrenciler o
        INNER JOIN ogrenci_bootcamp ob ON o.ogrenci_id = ob.ogrenci_id
        LEFT JOIN notlar n ON o.ogrenci_id = n.ogrenci_id AND n.ders_id = ?
        WHERE ob.program_id = ?
        GROUP BY o.ogrenci_id
        ORDER BY o.ogrenci_ad, o.ogrenci_soyad
    ");
    $stmt->execute([$id, $ders['program_id']]);
    $ogrenciler = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <title>Notlar - Bootcamp Yönetim Sistemi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2><?php echo htmlspecialchars($ders['ders_ad']); ?> - Notlar</h2>
        <p class="text-muted">Bootcamp: <?php echo htmlspecialchars($ders['bootcamp_ad']); ?></p>
        
        <?php if (isset($message)): ?>
            <div class="alert alert-<?php echo $messageType; ?>" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="row mt-4">
            <div class="col-md-8">
                <h4>Mevcut Notlar</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Ad Soyad</th>
                            <th>Notlar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ogrenciler as $ogrenci): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($ogrenci['ogrenci_ad'] . ' ' . $ogrenci['ogrenci_soyad']); ?></td>
                            <td><?php echo htmlspecialchars($ogrenci['notlar'] ?? 'Not girilmemiş'); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="col-md-4">
                <h4>Not Ekle</h4>
                <form method="POST" class="mt-3">
                    <?php foreach ($ogrenciler as $ogrenci): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h6 class="card-title"><?php echo htmlspecialchars($ogrenci['ogrenci_ad'] . ' ' . $ogrenci['ogrenci_soyad']); ?></h6>
                            <div class="mb-2">
                                <select class="form-select" name="not[<?php echo $ogrenci['ogrenci_id']; ?>][tip]">
                                    <option value="Sınav">Sınav</option>
                                    <option value="Ödev">Ödev</option>
                                    <option value="Proje">Proje</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <input type="number" class="form-control" name="not[<?php echo $ogrenci['ogrenci_id']; ?>][puan]" 
                                       placeholder="Puan (0-100)" min="0" max="100" step="0.01">
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <button type="submit" class="btn btn-primary">Notları Kaydet</button>
                </form>
            </div>
        </div>

        <a href="liste.php" class="btn btn-secondary mt-3">Geri Dön</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 