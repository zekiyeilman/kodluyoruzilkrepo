<?php
require_once '../config.php';

try {
    $stmt = $conn->prepare("
        SELECT d.*, b.bootcamp_ad
        FROM dersler d
        LEFT JOIN bootcampler b ON d.program_id = b.program_id
        ORDER BY b.bootcamp_ad, d.ders_ad
    ");
    $stmt->execute();
    $dersler = $stmt->fetchAll();
} catch(PDOException $e) {
    die("Hata: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ders Listesi - Bootcamp Yönetim Sistemi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Ders Listesi</h2>
            <a href="yeni.php" class="btn btn-primary">Yeni Ders</a>
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ders Adı</th>
                    <th>Bootcamp</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dersler as $ders): ?>
                <tr>
                    <td><?php echo htmlspecialchars($ders['ders_id']); ?></td>
                    <td><?php echo htmlspecialchars($ders['ders_ad']); ?></td>
                    <td><?php echo htmlspecialchars($ders['bootcamp_ad']); ?></td>
                    <td>
                        <a href="duzenle.php?id=<?php echo $ders['ders_id']; ?>" class="btn btn-sm btn-warning">Düzenle</a>
                        <a href="sil.php?id=<?php echo $ders['ders_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bu dersi silmek istediğinizden emin misiniz?')">Sil</a>
                        <a href="yoklama.php?id=<?php echo $ders['ders_id']; ?>" class="btn btn-sm btn-info">Yoklama</a>
                        <a href="notlar.php?id=<?php echo $ders['ders_id']; ?>" class="btn btn-sm btn-success">Notlar</a>
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