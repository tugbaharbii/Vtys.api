<?php
// Veritabanı bağlantısını sağlamak için config dosyasını dahil ediyoruz
require_once 'config.php';

function authenticateUser() {
    global $conn; // config dosyasından gelen $conn değişkenine erişim sağlıyoruz

    // Token'ı çerezden al
    if (!isset($_COOKIE['auth_token']) || empty($_COOKIE['auth_token'])) {
        return false;
    }
    $token = $_COOKIE['auth_token'];
    
    // Token'ın geçerliliğini kontrol eden sorgu
    $stmt = $conn->prepare("SELECT k.* FROM `Kayıt_Tablosu` k JOIN auth_tokens a ON k.id = a.user_id WHERE a.token = ? AND a.expires_at > NOW()");
    if (!$stmt) {
        die("Sorgu hazırlanamadı: " . $conn->error);
    }
    
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    
    return $user ? $user : false;
}
?>

