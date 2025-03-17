<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // CSRF token kontrolü
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Geçersiz CSRF token.");
    }
    
    // Form verilerini al
    $ad_soyad     = trim($_POST['ad_soyad']);
    $e_posta      = trim($_POST['e_posta']);
    $sifre        = $_POST['sifre'];
    $confirm_sifre = $_POST['confirm_sifre'];
    
    $errors = [];
    
    // Alanları doğrula
    if (empty($ad_soyad)) {
        $errors[] = "Ad Soyad alanı boş bırakılamaz.";
    }
    
    if (empty($e_posta)) {
        $errors[] = "E-posta alanı boş bırakılamaz.";
    } elseif (!filter_var($e_posta, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Geçerli bir e-posta adresi giriniz.";
    }
    
    if (empty($sifre)) {
        $errors[] = "Şifre alanı boş bırakılamaz.";
    }
    
    if ($sifre !== $confirm_sifre) {
        $errors[] = "Şifreler eşleşmiyor.";
    }
    
    // Hata varsa, ekrana yazdır ve çık
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p>" . htmlspecialchars($error) . "</p>";
        }
        exit;
    }
    
    // E-posta adresinin benzersizliğini kontrol et
    $stmt = $conn->prepare("SELECT id FROM `Kayıt_Tablosu` WHERE e_posta = ?");
    if (!$stmt) {
        die("Prepare hatası: " . $conn->error);
    }
    $stmt->bind_param("s", $e_posta);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        die("Bu e-posta zaten kayıtlı.");
    }
    $stmt->close();
    
    // Şifreyi hash'le
    $hashed_password = password_hash($sifre, PASSWORD_DEFAULT);
    
    // Yeni kullanıcıyı veritabanına ekle
    $stmt = $conn->prepare("INSERT INTO `Kayıt_Tablosu` (ad_soyad, e_posta, sifre) VALUES (?, ?, ?)");
    if (!$stmt) {
        die("Prepare hatası: " . $conn->error);
    }
    $stmt->bind_param("sss", $ad_soyad, $e_posta, $hashed_password);
    if ($stmt->execute()) {
        echo "Kayıt başarılı!";
        // İsterseniz aşağıdaki kodu kullanarak giriş sayfasına yönlendirebilirsiniz:
         header("Location: login.php");
         exit;
    } else {
        die("Kayıt hatası: " . $stmt->error);
    }
    $stmt->close();
    
} else {
    echo "Geçersiz istek.";
}
?>