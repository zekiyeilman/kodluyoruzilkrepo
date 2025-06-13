<?php
require_once '../config.php';

$bootcamp_id = $_GET['bootcamp_id'] ?? '';
$ogrenci_id = $_GET['ogrenci_id'] ?? '';

if (empty($bootcamp_id) || empty($ogrenci_id)) {
    header('Location: liste.php');
    exit;
}

try {
    $stmt = $conn->prepare("DELETE FROM ogrenci_bootcamp WHERE ogrenci_id = ? AND program_id = ?");
    $stmt->execute([$ogrenci_id, $bootcamp_id]);
    header('Location: ogrenciler.php?id=' . $bootcamp_id . '&message=success&text=' . urlencode('Öğrenci bootcamp\'ten başarıyla çıkarıldı.'));
} catch(PDOException $e) {
    header('Location: ogrenciler.php?id=' . $bootcamp_id . '&message=error&text=' . urlencode('Hata: ' . $e->getMessage()));
}
exit;
?> 