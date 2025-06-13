<?php
require_once '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = uniqid();
    $ad = $_POST['ad'];
    $program_id = $_POST['program_id'];

    try {
        $stmt = $conn->prepare("INSERT INTO dersler (ders_id, ders_ad, program_id) VALUES (?, ?, ?)");
        $stmt->execute([$id, $ad, $program_id]);
        $message = "Ders başarıyla oluşturuldu.";
        $messageType = "success";
    } catch(PDOException $e) {
        $message = "Hata: " . $e->getMessage();
        $messageType = "danger";
    }
}

try {

    $stmt = $conn->prepare("SELECT * FROM bootcampler");
    $stmt->execute();
    $bootcampler = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Yeni Ders - Bootcamp Yönetim Sistemi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Yeni Ders Oluştur</h2>
        
        <?php if (isset($message)): ?>
            <div class="alert alert-<?php echo $messageType; ?>" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="mt-4">
            <div class="mb-3">
                <label for="ad" class="form-label">Ders Adı</label>
                <input type="text" class="form-control" id="ad" name="ad" required>
            </div>
            <div class="mb-3">
                <label for="program_id" class="form-label">Bootcamp</label>
                <select class="form-control" id="program_id" name="program_id" required>
                    <option value="">Bootcamp Seçin</option>
                    <?php foreach ($bootcampler as $bootcamp): ?>
                        <option value="<?php echo $bootcamp['program_id']; ?>">
                            <?php echo htmlspecialchars($bootcamp['bootcamp_ad']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Oluştur</button>
            <a href="liste.php" class="btn btn-secondary">Geri Dön</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 