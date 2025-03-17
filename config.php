<?php
// Veritabanı bağlantı bilgileri
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "VTYSProject"; // MySQL ekran görüntüsünde kullanılan veritabanı adı

date_default_timezone_set('Europe/Istanbul');

// MySQL bağlantısını oluştur
$conn = new mysqli($servername, $username, $password, $database);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

// Türkçe karakterler ve özel karakterler için charset ayarını yap
$conn->set_charset("utf8");
echo "Bağlantı başarılı!";

// Token güvenlik anahtarı
define('SECRET_KEY', 'buraya_guclu_bir_guvenlik_anahtari_girin');

// Token geçerlilik süresi (saniye cinsinden) - 24 saat
define('TOKEN_EXPIRY', 60);
?>