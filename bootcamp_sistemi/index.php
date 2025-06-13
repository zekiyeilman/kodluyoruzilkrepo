<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootcamp Yönetim Sistemi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Bootcamp Yönetim Sistemi</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            Öğrenci İşlemleri
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="ogrenci/kayit.php">Yeni Kayıt</a></li>
                            <li><a class="dropdown-item" href="ogrenci/liste.php">Öğrenci Listesi</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            Bootcamp İşlemleri
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="bootcamp/yeni.php">Yeni Bootcamp</a></li>
                            <li><a class="dropdown-item" href="bootcamp/liste.php">Bootcamp Listesi</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            Ders İşlemleri
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="ders/yeni.php">Yeni Ders</a></li>
                            <li><a class="dropdown-item" href="ders/liste.php">Ders Listesi</a></li>
                            <li><a class="dropdown-item" href="ders/yoklama.php">Yoklama Girişi</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            Eğitmen İşlemleri
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="egitmen/yeni.php">Yeni Eğitmen</a></li>
                            <li><a class="dropdown-item" href="egitmen/liste.php">Eğitmen Listesi</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="rapor/index.php">Raporlar</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-center">Bootcamp Yönetim Sistemine Hoş Geldiniz</h1>
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Öğrenci İşlemleri</h5>
                                <p class="card-text">Öğrenci kayıt, listeleme ve güncelleme işlemleri</p>
                                <a href="ogrenci/liste.php" class="btn btn-primary">İşlem Yap</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Bootcamp İşlemleri</h5>
                                <p class="card-text">Bootcamp oluşturma ve yönetim işlemleri</p>
                                <a href="bootcamp/liste.php" class="btn btn-primary">İşlem Yap</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Ders İşlemleri</h5>
                                <p class="card-text">Ders ve yoklama yönetimi işlemleri</p>
                                <a href="ders/liste.php" class="btn btn-primary">İşlem Yap</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Eğitmen İşlemleri</h5>
                                <p class="card-text">Eğitmen kayıt ve yönetim işlemleri</p>
                                <a href="egitmen/liste.php" class="btn btn-primary">İşlem Yap</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 