<?php
require_once '../config.php';


$search = isset($_GET['search']) ? $_GET['search'] : '';

try {
    if ($search) {
        $stmt = $conn->prepare("CALL sp_OgrenciBul(?)");
        $stmt->execute([$search]);
    } else {
        $stmt = $conn->prepare("CALL sp_OgrencilerHepsi()");
        $stmt->execute();
    }
    $ogrenciler = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Hata: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Öğrenci Listesi - Bootcamp Yönetim Sistemi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Öğrenci Listesi</h2>
            <a href="kayit.php" class="btn btn-primary">Yeni Öğrenci Ekle</a>
        </div>

        <div class="mb-3">
            <form method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Öğrenci ara..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-outline-primary">Ara</button>
            </form>
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ad</th>
                    <th>Soyad</th>
                    <th>Telefon</th>
                    <th>E-posta</th>
                    <th>Kayıt Tarihi</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ogrenciler as $ogrenci): ?>
                <tr>
                    <td><?php echo htmlspecialchars($ogrenci['ID']); ?></td>
                    <td><?php echo htmlspecialchars($ogrenci['Ad']); ?></td>
                    <td><?php echo htmlspecialchars($ogrenci['Soyad']); ?></td>
                    <td><?php echo htmlspecialchars($ogrenci['Telefon']); ?></td>
                    <td><?php echo htmlspecialchars($ogrenci['Mail']); ?></td>
                    <td><?php echo htmlspecialchars($ogrenci['KayitTarihi']); ?></td>
                    <td>
                        <a href="duzenle.php?id=<?php echo $ogrenci['ID']; ?>" class="btn btn-sm btn-warning">Düzenle</a>
                        <a href="sil.php?id=<?php echo $ogrenci['ID']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bu öğrenciyi silmek istediğinizden emin misiniz?')">Sil</a>
                        <a href="notlar.php?id=<?php echo $ogrenci['ID']; ?>" class="btn btn-sm btn-info">Notlar</a>
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