-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 26 Şub 2026, 13:02:38
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `evde_bakim`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `cihaz_tanimlari`
--

CREATE TABLE `cihaz_tanimlari` (
  `id` int(11) NOT NULL,
  `cihaz_adi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Tablo döküm verisi `cihaz_tanimlari`
--

INSERT INTO `cihaz_tanimlari` (`id`, `cihaz_adi`) VALUES
(1, 'Enteral beslenme infüzyonu pompası'),
(2, 'Ev tipi aspiratör'),
(3, 'Ev tipi invaziv mekanik ventilatör (bipap)'),
(4, 'Fonksiyonlu hasta karyolası'),
(5, 'Hasta Karyolası-Metal'),
(6, 'Havalı yatak'),
(7, 'Isıtıcılı nemlendirici'),
(8, 'Kanedyen Baston');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `hastalik_tanimlari`
--

CREATE TABLE `hastalik_tanimlari` (
  `id` int(11) NOT NULL,
  `hastalik_adi` varchar(255) NOT NULL,
  `hastalik_grubu` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Tablo döküm verisi `hastalik_tanimlari`
--

INSERT INTO `hastalik_tanimlari` (`id`, `hastalik_adi`, `hastalik_grubu`) VALUES
(11, 'Akciğer transplantasyonu', 'Akciğer ve solunum sistemi'),
(12, 'BOOP (Bronchiolitis obliterans)', 'Akciğer ve solunum sistemi'),
(13, 'Bronşial astma', 'Akciğer ve solunum sistemi'),
(14, 'Bronşiektazi', 'Akciğer ve solunum sistemi'),
(15, 'Diğer', 'Akciğer ve solunum sistemi'),
(16, 'Diğer hastalıklar nedeniye gelişen solunum yetmezliği', 'Akciğer ve solunum sistemi'),
(17, 'Göğüs duvarı deformiteleri', 'Akciğer ve solunum sistemi'),
(18, 'İAH (İatrojenik akciğer hastalığı)', 'Akciğer ve solunum sistemi'),
(19, 'Kistik fibrozis', 'Akciğer ve solunum sistemi'),
(20, 'KOAH', 'Akciğer ve solunum sistemi'),
(21, 'Obezite hipoventilasyon', 'Akciğer ve solunum sistemi'),
(22, 'Plörezi', 'Akciğer ve solunum sistemi'),
(23, 'Pmömokonyoz', 'Akciğer ve solunum sistemi'),
(24, 'Posttorakotomi', 'Akciğer ve solunum sistemi'),
(25, 'Pulmoner hipertansiyon', 'Akciğer ve solunum sistemi'),
(26, 'Santral hipoventilasyon', 'Akciğer ve solunum sistemi'),
(27, 'TBC sekelleri', 'Akciğer ve solunum sistemi'),
(28, 'Akciğer kanseri', 'Hematolojik ve Onkolojik'),
(29, 'Anemi', 'Hematolojik ve Onkolojik'),
(30, 'Beyin tümörü', 'Hematolojik ve Onkolojik'),
(31, 'Böbrek kanseri', 'Hematolojik ve Onkolojik'),
(32, 'Cilt kanseri', 'Hematolojik ve Onkolojik'),
(33, 'Diğer', 'Hematolojik ve Onkolojik'),
(34, 'Farinks kanseri', 'Hematolojik ve Onkolojik'),
(35, 'Gliablastoma', 'Hematolojik ve Onkolojik'),
(36, 'Hematolojik kanserler (lökozlar)', 'Hematolojik ve Onkolojik'),
(37, 'Hipofiz kanseri', 'Hematolojik ve Onkolojik'),
(38, 'ITP', 'Hematolojik ve Onkolojik'),
(39, 'İnce barsak kanseri', 'Hematolojik ve Onkolojik'),
(40, 'Karaciğer kanseri', 'Hematolojik ve Onkolojik'),
(41, 'Kemik kanseri', 'Hematolojik ve Onkolojik'),
(42, 'Kolon kanseri', 'Hematolojik ve Onkolojik'),
(43, 'Meme kanseri', 'Hematolojik ve Onkolojik'),
(44, 'Mide kanseri', 'Hematolojik ve Onkolojik'),
(45, 'Multipl myeloma', 'Hematolojik ve Onkolojik'),
(46, 'Over kanseri', 'Hematolojik ve Onkolojik'),
(47, 'Özefagus kanseri', 'Hematolojik ve Onkolojik'),
(48, 'Pankreas kanseri', 'Hematolojik ve Onkolojik'),
(49, 'Prostat kanseri', 'Hematolojik ve Onkolojik'),
(50, 'Safra yolu/ kesesi kanseri', 'Hematolojik ve Onkolojik'),
(51, 'Surrenal bez kanseri', 'Hematolojik ve Onkolojik'),
(52, 'Tiroid kanseri', 'Hematolojik ve Onkolojik'),
(53, 'Trakea kanseri', 'Hematolojik ve Onkolojik'),
(54, 'Uterus kanseri', 'Hematolojik ve Onkolojik'),
(55, 'Aritmi', 'Kardiyo vasküler'),
(56, 'ASKH', 'Kardiyo vasküler'),
(57, 'Diğer', 'Kardiyo vasküler'),
(58, 'Hipertansiyon', 'Kardiyo vasküler'),
(59, 'Kalp kapak hastalığı', 'Kardiyo vasküler'),
(60, 'Kalp yetmezliği', 'Kardiyo vasküler'),
(61, 'Koroner arter hastalığı', 'Kardiyo vasküler'),
(62, 'MI', 'Kardiyo vasküler'),
(63, 'Venöz dolaşım bozukluğu', 'Kardiyo vasküler'),
(64, 'Venöz ve lenfatik drenaj bozukluğuna bağlı ileri derece ödem', 'Kardiyo vasküler'),
(65, 'Ataksi', 'Kas (yataga bağımlı)'),
(66, 'Diğer', 'Kas (yataga bağımlı)'),
(67, 'Kas atrofisi', 'Kas (yataga bağımlı)'),
(68, 'Müsküler distrofi', 'Kas (yataga bağımlı)'),
(69, 'Spastik ve flask tip özürlüler', 'Kas (yataga bağımlı)'),
(70, 'Diğer', 'Kronik ve Endokrin'),
(71, 'Diyabet', 'Kronik ve Endokrin'),
(72, 'Hiperlipidemi', 'Kronik ve Endokrin'),
(73, 'Hipertroidi', 'Kronik ve Endokrin'),
(74, 'Obezite', 'Kronik ve Endokrin'),
(75, 'ALS', 'Nörolojik ve Psikiyatrik'),
(76, 'Alzheimer hastalığı', 'Nörolojik ve Psikiyatrik'),
(77, 'Atipik psikoz', 'Nörolojik ve Psikiyatrik'),
(78, 'Bipolar bozukluk', 'Nörolojik ve Psikiyatrik'),
(79, 'Demans', 'Nörolojik ve Psikiyatrik'),
(80, 'Demiyelizan MSS', 'Nörolojik ve Psikiyatrik'),
(81, 'Depresyon', 'Nörolojik ve Psikiyatrik'),
(82, 'Diğer', 'Nörolojik ve Psikiyatrik'),
(83, 'Ensefalopati', 'Nörolojik ve Psikiyatrik'),
(84, 'Epilepsi', 'Nörolojik ve Psikiyatrik'),
(85, 'Hemipleji', 'Nörolojik ve Psikiyatrik'),
(86, 'Hidrosefali', 'Nörolojik ve Psikiyatrik'),
(87, 'Kuadripleji', 'Nörolojik ve Psikiyatrik'),
(88, 'Mental retardasyon', 'Nörolojik ve Psikiyatrik'),
(89, 'Miyelit', 'Nörolojik ve Psikiyatrik'),
(90, 'Motor nöron hastalığı', 'Nörolojik ve Psikiyatrik'),
(91, 'Multipl skleroz', 'Nörolojik ve Psikiyatrik'),
(92, 'Nöropatik ağrı', 'Nörolojik ve Psikiyatrik'),
(93, 'Parapleji', 'Nörolojik ve Psikiyatrik'),
(94, 'Parkinson', 'Nörolojik ve Psikiyatrik'),
(95, 'Polio sekeli', 'Nörolojik ve Psikiyatrik'),
(96, 'Psikoz', 'Nörolojik ve Psikiyatrik'),
(97, 'Senil demans/Serilite', 'Nörolojik ve Psikiyatrik'),
(98, 'SSPE', 'Nörolojik ve Psikiyatrik'),
(99, 'SVO', 'Nörolojik ve Psikiyatrik'),
(100, 'Şizofreni', 'Nörolojik ve Psikiyatrik'),
(101, 'Tetrapleji', 'Nörolojik ve Psikiyatrik'),
(102, 'Dekübitus ülseri', 'Ortopedi ve Travmatoloji'),
(103, 'Diğer', 'Ortopedi ve Travmatoloji'),
(104, 'Diskopati', 'Ortopedi ve Travmatoloji'),
(105, 'Extremitelerde amputasyon', 'Ortopedi ve Travmatoloji'),
(106, 'Extremitelerde gonatroz', 'Ortopedi ve Travmatoloji'),
(107, 'Kalça fraktürü', 'Ortopedi ve Travmatoloji'),
(108, 'Koks artroz', 'Ortopedi ve Travmatoloji'),
(109, 'Omurga fraktürü', 'Ortopedi ve Travmatoloji'),
(110, 'Opere edilmiş veya mobilizasyonu kısıtlayan kırıklar', 'Ortopedi ve Travmatoloji'),
(111, 'Osteomiyelit', 'Ortopedi ve Travmatoloji'),
(112, 'Ostreoporoz', 'Ortopedi ve Travmatoloji');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `hasta_ciihazlar`
--

CREATE TABLE `hasta_ciihazlar` (
  `id` int(11) NOT NULL,
  `hasta_id` int(11) NOT NULL,
  `cihaz_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Tablo döküm verisi `hasta_ciihazlar`
--

INSERT INTO `hasta_ciihazlar` (`id`, `hasta_id`, `cihaz_id`, `created_at`) VALUES
(4, 9041, 1, '2026-02-06 10:41:36'),
(5, 9041, 2, '2026-02-06 10:41:37'),
(6, 9041, 3, '2026-02-06 10:41:38'),
(7, 9041, 4, '2026-02-06 10:41:38'),
(10, 9043, 3, '2026-02-16 11:01:05');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `hasta_hastaliklar`
--

CREATE TABLE `hasta_hastaliklar` (
  `id` int(11) NOT NULL,
  `hasta_id` int(11) NOT NULL,
  `hastalik_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Tablo döküm verisi `hasta_hastaliklar`
--

INSERT INTO `hasta_hastaliklar` (`id`, `hasta_id`, `hastalik_id`, `created_at`) VALUES
(6, 9041, 11, '2026-02-06 10:41:29'),
(7, 9041, 12, '2026-02-06 10:41:30'),
(8, 9041, 13, '2026-02-06 10:41:30'),
(9, 9041, 14, '2026-02-06 10:41:31'),
(10, 9041, 15, '2026-02-06 10:41:31'),
(11, 9041, 16, '2026-02-06 10:41:31'),
(12, 9041, 95, '2026-02-06 10:47:25'),
(13, 9041, 97, '2026-02-06 10:47:26'),
(14, 9041, 112, '2026-02-06 10:47:27'),
(15, 9041, 80, '2026-02-06 10:47:29'),
(18, 9043, 13, '2026-02-16 11:01:00'),
(19, 9043, 14, '2026-02-16 11:01:00');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `hasta_kayit`
--

CREATE TABLE `hasta_kayit` (
  `id` int(11) NOT NULL,
  `ad_soyad` varchar(150) NOT NULL,
  `tc` varchar(11) NOT NULL,
  `hizmet_no` varchar(20) DEFAULT NULL,
  `cinsiyet` enum('E','K') DEFAULT NULL,
  `dogum_tarihi` date DEFAULT NULL,
  `cep_tel` varchar(20) DEFAULT NULL,
  `yatalak` tinyint(1) DEFAULT 0,
  `beyan_ilce` varchar(100) DEFAULT NULL,
  `adres` text DEFAULT NULL,
  `hizmet_durum` enum('Hasta Kayıt','Hizmet Başlatıldı','Hizmet Sonlandırıldı','Hizmet Verilmedi','Hizmete Uygun Değil','Aile Hekimine Sevk') DEFAULT 'Hasta Kayıt',
  `sonuc_durum` varchar(255) NOT NULL,
  `olum_tarihi` date DEFAULT NULL,
  `hiz_son_tarihi` date DEFAULT NULL,
  `hiz_baslama_tarihi` date DEFAULT NULL,
  `basvuru_tarihi` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `hasta_ziyaretleri`
--

CREATE TABLE `hasta_ziyaretleri` (
  `id` int(11) NOT NULL,
  `hasta_id` int(11) NOT NULL,
  `ziyaret_tarihi` date NOT NULL,
  `ziyaret_tipi` enum('Randevu','Ziyaret') NOT NULL,
  `aciklama` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Tablo döküm verisi `hasta_ziyaretleri`
--

INSERT INTO `hasta_ziyaretleri` (`id`, `hasta_id`, `ziyaret_tarihi`, `ziyaret_tipi`, `aciklama`, `created_at`) VALUES
(2, 9041, '2026-01-06', 'Ziyaret', 'haaaga ', '2026-02-06 10:43:07'),
(4, 9041, '2026-01-15', 'Ziyaret', 'ewewew', '2026-02-06 10:43:23'),
(5, 9041, '2026-02-20', 'Randevu', '14444', '2026-02-06 11:19:50'),
(6, 9041, '2026-03-26', 'Randevu', '4242545', '2026-02-06 11:20:22'),
(7, 9043, '2026-02-04', 'Randevu', 'ff', '2026-02-16 11:00:45');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `attempts` int(11) DEFAULT 0,
  `banned_until` datetime DEFAULT NULL,
  `last_attempt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`) VALUES
(3, 'deneme', '$2y$10$/eB6fArKJGoL66A8XY2r0et3LhQP5D7YpwnmWN6xZO5j1RDct7Ely', '2026-02-02 12:14:12');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `cihaz_tanimlari`
--
ALTER TABLE `cihaz_tanimlari`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `hastalik_tanimlari`
--
ALTER TABLE `hastalik_tanimlari`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `hasta_ciihazlar`
--
ALTER TABLE `hasta_ciihazlar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hasta_id` (`hasta_id`,`cihaz_id`),
  ADD KEY `cihaz_id` (`cihaz_id`);

--
-- Tablo için indeksler `hasta_hastaliklar`
--
ALTER TABLE `hasta_hastaliklar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hasta_id` (`hasta_id`,`hastalik_id`),
  ADD KEY `hastalik_id` (`hastalik_id`);

--
-- Tablo için indeksler `hasta_kayit`
--
ALTER TABLE `hasta_kayit`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `hasta_ziyaretleri`
--
ALTER TABLE `hasta_ziyaretleri`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hasta_id` (`hasta_id`);

--
-- Tablo için indeksler `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `cihaz_tanimlari`
--
ALTER TABLE `cihaz_tanimlari`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Tablo için AUTO_INCREMENT değeri `hastalik_tanimlari`
--
ALTER TABLE `hastalik_tanimlari`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- Tablo için AUTO_INCREMENT değeri `hasta_ciihazlar`
--
ALTER TABLE `hasta_ciihazlar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Tablo için AUTO_INCREMENT değeri `hasta_hastaliklar`
--
ALTER TABLE `hasta_hastaliklar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Tablo için AUTO_INCREMENT değeri `hasta_kayit`
--
ALTER TABLE `hasta_kayit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `hasta_ziyaretleri`
--
ALTER TABLE `hasta_ziyaretleri`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Tablo için AUTO_INCREMENT değeri `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `hasta_ciihazlar`
--
ALTER TABLE `hasta_ciihazlar`
  ADD CONSTRAINT `hasta_ciihazlar_ibfk_1` FOREIGN KEY (`hasta_id`) REFERENCES `hasta_kayit` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hasta_ciihazlar_ibfk_2` FOREIGN KEY (`cihaz_id`) REFERENCES `cihaz_tanimlari` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `hasta_hastaliklar`
--
ALTER TABLE `hasta_hastaliklar`
  ADD CONSTRAINT `hasta_hastaliklar_ibfk_1` FOREIGN KEY (`hasta_id`) REFERENCES `hasta_kayit` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hasta_hastaliklar_ibfk_2` FOREIGN KEY (`hastalik_id`) REFERENCES `hastalik_tanimlari` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `hasta_ziyaretleri`
--
ALTER TABLE `hasta_ziyaretleri`
  ADD CONSTRAINT `hasta_ziyaretleri_ibfk_1` FOREIGN KEY (`hasta_id`) REFERENCES `hasta_kayit` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
