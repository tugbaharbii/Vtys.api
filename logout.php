<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

ob_start();             // Çıktı tamponlaması
session_start();        // Oturum başlat

// Oturum verilerini temizle
$_SESSION = [];
session_destroy();      // Oturumu yok et

// Test amaçlı bir mesaj yazalım (bakalım ekrana gelecek mi?):
echo "Logout işlemi tamam, yönlendiriliyor...";

// Yönlendirme
header('Location: login.php');
exit;

ob_end_flush();
?>