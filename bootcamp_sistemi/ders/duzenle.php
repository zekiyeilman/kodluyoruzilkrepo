<?php
require_once '../config.php';

$id = $_GET['id'] ?? '';

if (empty($id)) {
    header('Location: liste.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ad = $_POST['ad'] ?? '';
    $program_id = $_POST['program_id'] ?? '';

    try {
        $stmt = $conn->prepare("UPDATE dersler SET ders_ad = ?, program_id = ? WHERE ders_id = ?");
        $stmt->execute([$ad, $program_id, $id]);
        $message = "Ders bilgileri başarıyla güncellendi.";
        $messageType = "success";
    } catch(PDOException $e) {
        $message = "Hata: " . $e->getMessage();
        $messageType = "danger";
    }
}

try {
    
    $stmt = $conn->prepare("SELECT * FROM dersler WHERE ders_id = ?");
    $stmt->execute([$id]);
    $ders = $stmt->fetch();

    if (!$ders) {
        header('Location: liste.php');
        exit;
    }


    $stmt = $conn->prepare("SELECT * FROM bootcampler ORDER BY bootcamp_ad");
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
    <title>Ders Düzenle - Bootcamp Yönetim Sistemi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Ders Düzenle</h2>
        
        <?php if (isset($message)): ?>
            <div class="alert alert-<?php echo $messageType; ?>" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="mt-4">
            <div class="mb-3">
                <label for="ad" class="form-label">Ders Adı</label>
                <input type="text" class="form-control" id="ad" name="ad" value="<?php echo htmlspecialchars($ders['ders_ad']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="program_id" class="form-label">Bootcamp</label>
                <select class="form-control" id="program_id" name="program_id" required>
                    <option value="">Bootcamp Seçin</option>
                    <?php foreach ($bootcampler as $bootcamp): ?>
                        <option value="<?php echo $bootcamp['program_id']; ?>" <?php echo ($ders['program_id'] == $bootcamp['program_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($bootcamp['bootcamp_ad']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Güncelle</button>
            <a href="liste.php" class="btn btn-secondary">Geri Dön</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 