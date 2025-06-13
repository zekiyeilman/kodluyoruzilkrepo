<?php
require_once '../config.php';

$id = $_GET['id'] ?? '';

if (empty($id)) {
    header('Location: liste.php');
    exit;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ogrenci_id = $_POST['ogrenci_id'];

    try {
        $stmt = $conn->prepare("INSERT INTO ogrenci_bootcamp (ogrenci_id, program_id) VALUES (?, ?)");
        $stmt->execute([$ogrenci_id, $id]);
        $message = "Öğrenci bootcamp'e başarıyla eklendi.";
        $messageType = "success";
    } catch(PDOException $e) {
        $message = "Hata: " . $e->getMessage();
        $messageType = "danger";
    }
}

try {
   
    $stmt = $conn->prepare("SELECT * FROM bootcampler WHERE program_id = ?");
    $stmt->execute([$id]);
    $bootcamp = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$bootcamp) {
        header('Location: liste.php');
        exit;
    }

    $stmt = $conn->prepare("
        SELECT o.*, ob.program_id
        FROM ogrenciler o
        INNER JOIN ogrenci_bootcamp ob ON o.ogrenci_id = ob.ogrenci_id
        WHERE ob.program_id = ?
    ");
    $stmt->execute([$id]);
    $bootcamp_ogrencileri = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("
        SELECT o.*
        FROM ogrenciler o
        WHERE o.ogrenci_id NOT IN (
            SELECT ob.ogrenci_id
            FROM ogrenci_bootcamp ob
            WHERE ob.program_id = ?
        )
    ");
    $stmt->execute([$id]);
    $diger_ogrenciler = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <title>Bootcamp Öğrencileri - Bootcamp Yönetim Sistemi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2><?php echo htmlspecialchars($bootcamp['bootcamp_ad']); ?> - Öğrenciler</h2>
        
        <?php if (isset($message)): ?>
            <div class="alert alert-<?php echo $messageType; ?>" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="row mt-4">
            <div class="col-md-8">
                <h4>Kayıtlı Öğrenciler</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Ad</th>
                            <th>Soyad</th>
                            <th>E-posta</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bootcamp_ogrencileri as $ogrenci): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($ogrenci['ogrenci_ad']); ?></td>
                            <td><?php echo htmlspecialchars($ogrenci['ogrenci_soyad']); ?></td>
                            <td><?php echo htmlspecialchars($ogrenci['ogrenci_mail']); ?></td>
                            <td>
                                <a href="ogrenci_cikar.php?bootcamp_id=<?php echo $id; ?>&ogrenci_id=<?php echo $ogrenci['ogrenci_id']; ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Bu öğrenciyi bootcamp\'ten çıkarmak istediğinizden emin misiniz?')">
                                    Çıkar
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="col-md-4">
                <h4>Öğrenci Ekle</h4>
                <form method="POST" class="mt-3">
                    <div class="mb-3">
                        <label for="ogrenci_id" class="form-label">Öğrenci Seçin</label>
                        <select class="form-control" id="ogrenci_id" name="ogrenci_id" required>
                            <option value="">Öğrenci Seçin</option>
                            <?php foreach ($diger_ogrenciler as $ogrenci): ?>
                                <option value="<?php echo $ogrenci['ogrenci_id']; ?>">
                                    <?php echo htmlspecialchars($ogrenci['ogrenci_ad'] . ' ' . $ogrenci['ogrenci_soyad']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Öğrenci Ekle</button>
                </form>
            </div>
        </div>

        <a href="liste.php" class="btn btn-secondary mt-3">Geri Dön</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 