CREATE DATABASE bootcamp_sistemi;
USE bootcamp_sistemi;


CREATE TABLE ogrenciler (
    ogrenci_id VARCHAR(64) PRIMARY KEY,
    ogrenci_ad VARCHAR(50) NOT NULL,
    ogrenci_soyad VARCHAR(50) NOT NULL,
    telefon VARCHAR(20),
    ogrenci_mail VARCHAR(100),
    kayit_tarihi DATE
);


CREATE TABLE IF NOT EXISTS egitmenler (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ad VARCHAR(50) NOT NULL,
    soyad VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefon VARCHAR(20) NOT NULL,
    uzmanlik VARCHAR(100) NOT NULL,
    deneyim INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;


CREATE TABLE bootcampler (
    program_id VARCHAR(64) PRIMARY KEY,
    bootcamp_ad VARCHAR(100),
    baslangic_tarihi DATE,
    bitis_tarihi DATE,
    egitmen_id VARCHAR(64),
    FOREIGN KEY (egitmen_id) REFERENCES egitmenler(egitmen_id)
        ON DELETE SET NULL ON UPDATE CASCADE
);


CREATE TABLE dersler (
    ders_id VARCHAR(64) PRIMARY KEY,
    ders_ad VARCHAR(100),
    program_id VARCHAR(64),
    FOREIGN KEY (program_id) REFERENCES bootcampler(program_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE ogrenci_bootcamp (
    ogrenci_id VARCHAR(64),
    program_id VARCHAR(64),
    PRIMARY KEY (ogrenci_id, program_id),
    FOREIGN KEY (ogrenci_id) REFERENCES ogrenciler(ogrenci_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (program_id) REFERENCES bootcampler(program_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE katilim (
    yoklama_id VARCHAR(64) PRIMARY KEY,
    ogrenci_id VARCHAR(64),
    ders_id VARCHAR(64),
    tarih DATE,
    katilim_durumu ENUM('Var', 'Yok') NOT NULL,
    FOREIGN KEY (ogrenci_id) REFERENCES ogrenciler(ogrenci_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (ders_id) REFERENCES dersler(ders_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE notlar (
    not_id VARCHAR(64) PRIMARY KEY,
    ogrenci_id VARCHAR(64),
    ders_id VARCHAR(64),
    not_tipi ENUM('Sınav', 'Ödev', 'Proje'),
    puan FLOAT,
    FOREIGN KEY (ogrenci_id) REFERENCES ogrenciler(ogrenci_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (ders_id) REFERENCES dersler(ders_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);


DELIMITER $$
CREATE PROCEDURE sp_OgrencilerHepsi()
BEGIN
    SELECT 
        ogrenci_id AS ID,
        ogrenci_ad AS Ad,
        ogrenci_soyad AS Soyad,
        telefon AS Telefon,
        ogrenci_mail AS Mail,
        kayit_tarihi AS KayitTarihi
    FROM ogrenciler;
END $$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE sp_OgrenciEkle(
    id VARCHAR(64), ad VARCHAR(50), soyad VARCHAR(50),
    tel VARCHAR(20), mail VARCHAR(100), tarih DATE
)
BEGIN
    INSERT INTO ogrenciler VALUES (id, ad, soyad, tel, mail, tarih);
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE sp_OgrenciGuncelle(
    id VARCHAR(64), ad VARCHAR(50), soyad VARCHAR(50),
    tel VARCHAR(20), mail VARCHAR(100), tarih DATE
)
BEGIN
    UPDATE ogrenciler
    SET ogrenci_ad = ad,
        ogrenci_soyad = soyad,
        telefon = tel,
        ogrenci_mail = mail,
        kayit_tarihi = tarih
    WHERE ogrenci_id = id;
END $$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE sp_OgrenciSil(
    id VARCHAR(64)
)
BEGIN
    DELETE FROM ogrenciler WHERE ogrenci_id = id;
END $$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE sp_OgrenciBul(
    filtre VARCHAR(64)
)
BEGIN
    SELECT * FROM ogrenciler
    WHERE ogrenci_id LIKE CONCAT('%', filtre, '%')
       OR ogrenci_ad LIKE CONCAT('%', filtre, '%')
       OR ogrenci_soyad LIKE CONCAT('%', filtre, '%')
       OR ogrenci_mail LIKE CONCAT('%', filtre, '%')
       OR telefon LIKE CONCAT('%', filtre, '%');
END $$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE sp_OgrenciNotlari(
    id VARCHAR(64)
)
BEGIN
    SELECT d.ders_ad, n.not_tipi, n.puan
    FROM notlar n
    INNER JOIN dersler d ON d.ders_id = n.ders_id
    WHERE n.ogrenci_id = id;
END $$
DELIMITER ;


DELIMITER $$
CREATE FUNCTION fn_OrtalamaNot(ogr_id VARCHAR(64))
RETURNS FLOAT DETERMINISTIC
BEGIN
    DECLARE ortalama FLOAT;
    SELECT AVG(puan) INTO ortalama FROM notlar WHERE ogrenci_id = ogr_id;
    RETURN ortalama;
END $$
DELIMITER ;
 
DELIMITER $$
CREATE TRIGGER tg_NotKontrol
BEFORE INSERT ON notlar
FOR EACH ROW
BEGIN
    IF NEW.puan < 0 OR NEW.puan > 100 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Puan 0-100 arasında olmalıdır.';
    END IF;
END $$
DELIMITER ;


DELIMITER $$
CREATE TRIGGER tg_NotGuncelle_Kontrol
BEFORE UPDATE ON notlar
FOR EACH ROW
BEGIN
    IF NEW.puan < 0 OR NEW.puan > 100 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Güncellenen puan 0-100 arasında olmalıdır.';
    END IF;
END $$
DELIMITER ;


DELIMITER $$
CREATE TRIGGER tg_YoklamaTekil
BEFORE INSERT ON katilim
FOR EACH ROW
BEGIN
    IF EXISTS (
        SELECT 1 FROM katilim
        WHERE ogrenci_id = NEW.ogrenci_id
        AND ders_id = NEW.ders_id
        AND tarih = NEW.tarih
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Aynı derse aynı gün birden fazla yoklama girilemez.';
    END IF;
END $$
DELIMITER ;


DELIMITER $$
CREATE TRIGGER tg_Katilim_Tarih_Kontrol
BEFORE INSERT ON katilim
FOR EACH ROW
BEGIN
    IF NEW.tarih > CURDATE() THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Yoklama tarihi bugünden ileri olamaz.';
    END IF;
END $$
DELIMITER ;
