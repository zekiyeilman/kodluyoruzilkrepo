<?php
require_once '../config.php';

try {
    $stmt = $conn->prepare("SELECT * FROM bootcampler ORDER BY baslangic_tarihi DESC");
    $stmt->execute();
    $bootcampler = $stmt->fetchAll();
} catch(PDOException $e) {
    die("Hata: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootcamp Listesi - Bootcamp Yönetim Sistemi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Bootcamp Listesi</h2>
            <a href="yeni.php" class="btn btn-primary">Yeni Bootcamp</a>
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Bootcamp Adı</th>
                    <th>Başlangıç Tarihi</th>
                    <th>Bitiş Tarihi</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bootcampler as $bootcamp): ?>
                <tr>
                    <td><?php echo htmlspecialchars($bootcamp['program_id']); ?></td>
                    <td><?php echo htmlspecialchars($bootcamp['bootcamp_ad']); ?></td>
                    <td><?php echo htmlspecialchars($bootcamp['baslangic_tarihi']); ?></td>
                    <td><?php echo htmlspecialchars($bootcamp['bitis_tarihi']); ?></td>
                    <td>
                        <a href="duzenle.php?id=<?php echo $bootcamp['program_id']; ?>" class="btn btn-sm btn-warning">Düzenle</a>
                        <a href="sil.php?id=<?php echo $bootcamp['program_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bu bootcamp\'i silmek istediğinizden emin misiniz?')">Sil</a>
                        <a href="ogrenciler.php?id=<?php echo $bootcamp['program_id']; ?>" class="btn btn-sm btn-info">Öğrenciler</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <a href="../index.php" class="btn btn-secondary">Ana Sayfa</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 