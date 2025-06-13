<?php
require_once '../config.php';

$id = $_GET['id'] ?? '';
$tarih = $_GET['tarih'] ?? date('Y-m-d');

if (empty($id)) {
    header('Location: liste.php');
    exit;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        
        $stmt = $conn->prepare("DELETE FROM katilim WHERE ders_id = ? AND tarih = ?");
        $stmt->execute([$id, $tarih]);

        foreach ($_POST['katilim'] as $ogrenci_id => $durum) {
            $yoklama_id = uniqid();
            $stmt = $conn->prepare("INSERT INTO katilim (yoklama_id, ogrenci_id, ders_id, tarih, katilim_durumu) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$yoklama_id, $ogrenci_id, $id, $tarih, $durum]);
        }
        $message = "Yoklama başarıyla kaydedildi.";
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
        SELECT o.*, k.katilim_durumu
        FROM ogrenciler o
        INNER JOIN ogrenci_bootcamp ob ON o.ogrenci_id = ob.ogrenci_id
        LEFT JOIN katilim k ON o.ogrenci_id = k.ogrenci_id AND k.ders_id = ? AND k.tarih = ?
        WHERE ob.program_id = ?
        ORDER BY o.ogrenci_ad, o.ogrenci_soyad
    ");
    $stmt->execute([$id, $tarih, $ders['program_id']]);
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
    <title>Yoklama - Bootcamp Yönetim Sistemi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2><?php echo htmlspecialchars($ders['ders_ad']); ?> - Yoklama</h2>
        <p class="text-muted">Bootcamp: <?php echo htmlspecialchars($ders['bootcamp_ad']); ?></p>
        
        <?php if (isset($message)): ?>
            <div class="alert alert-<?php echo $messageType; ?>" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="GET" class="mb-4">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="row">
                <div class="col-md-4">
                    <label for="tarih" class="form-label">Tarih</label>
                    <input type="date" class="form-control" id="tarih" name="tarih" value="<?php echo $tarih; ?>" max="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary d-block">Tarihi Değiştir</button>
                </div>
            </div>
        </form>

        <form method="POST">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Ad Soyad</th>
                        <th>Katılım Durumu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ogrenciler as $ogrenci): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($ogrenci['ogrenci_ad'] . ' ' . $ogrenci['ogrenci_soyad']); ?></td>
                        <td>
                            <div class="btn-group" role="group">
                                <input type="radio" class="btn-check" name="katilim[<?php echo $ogrenci['ogrenci_id']; ?>]" 
                                       id="var_<?php echo $ogrenci['ogrenci_id']; ?>" value="Var" 
                                       <?php echo ($ogrenci['katilim_durumu'] == 'Var') ? 'checked' : ''; ?>>
                                <label class="btn btn-outline-success" for="var_<?php echo $ogrenci['ogrenci_id']; ?>">Var</label>

                                <input type="radio" class="btn-check" name="katilim[<?php echo $ogrenci['ogrenci_id']; ?>]" 
                                       id="yok_<?php echo $ogrenci['ogrenci_id']; ?>" value="Yok" 
                                       <?php echo ($ogrenci['katilim_durumu'] == 'Yok') ? 'checked' : ''; ?>>
                                <label class="btn btn-outline-danger" for="yok_<?php echo $ogrenci['ogrenci_id']; ?>">Yok</label>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <button type="submit" class="btn btn-primary">Yoklamayı Kaydet</button>
            <a href="liste.php" class="btn btn-secondary">Geri Dön</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 