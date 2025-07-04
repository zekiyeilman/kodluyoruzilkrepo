<?php
require_once '../config.php';

$id = $_GET['id'] ?? '';

if (empty($id)) {
    header('Location: liste.php');
    exit;
}

try {
    $stmt = $conn->prepare("DELETE FROM ogrenciler WHERE ogrenci_id = ?");
    $stmt->execute([$id]);
    header('Location: liste.php?message=success&text=' . urlencode('Öğrenci başarıyla silindi.'));
} catch(PDOException $e) {
    header('Location: liste.php?message=error&text=' . urlencode('Hata: ' . $e->getMessage()));
}
exit;
?> 