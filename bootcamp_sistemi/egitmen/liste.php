<?php
require_once '../config/db.php';

if (isset($_GET['sil'])) {
    $id = $_GET['sil'];
    $sql = "DELETE FROM egitmenler WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt->execute([$id])) {
        echo "<script>alert('Eğitmen başarıyla silindi!');</script>";
    } else {
        echo "<script>alert('Hata oluştu!');</script>";
    }
}

$sql = "SELECT * FROM egitmenler ORDER BY ad, soyad";
$stmt = $conn->query($sql);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eğitmen Listesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Eğitmen Listesi</h2>
            <a href="yeni.php" class="btn btn-primary">Yeni Eğitmen Ekle</a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ad</th>
                        <th>Soyad</th>
                        <th>E-posta</th>
                        <th>Telefon</th>
                        <th>Uzmanlık</th>
                        <th>Deneyim (Yıl)</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (count($result) > 0) {
                        foreach($result as $row) {
                            echo "<tr>";
                            echo "<td>" . $row["id"] . "</td>";
                            echo "<td>" . $row["ad"] . "</td>";
                            echo "<td>" . $row["soyad"] . "</td>";
                            echo "<td>" . $row["email"] . "</td>";
                            echo "<td>" . $row["telefon"] . "</td>";
                            echo "<td>" . $row["uzmanlik"] . "</td>";
                            echo "<td>" . $row["deneyim"] . "</td>";
                            echo "<td>
                                    <a href='duzenle.php?id=" . $row["id"] . "' class='btn btn-sm btn-warning'>Düzenle</a>
                                    <a href='liste.php?sil=" . $row["id"] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Bu eğitmeni silmek istediğinizden emin misiniz?\")'>Sil</a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8' class='text-center'>Henüz eğitmen bulunmamaktadır.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 