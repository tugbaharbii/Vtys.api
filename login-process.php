<?php
session_start();

// CSRF token doğrulaması
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("Geçersiz CSRF token.");
}

// Veritabanı bağlantısını dahil et
require_once 'config.php';

// Form gönderildi mi kontrolü
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Form verilerini al
    $e_posta = trim($_POST['e_posta']);
    $sifre = $_POST['sifre'];
    $errors = [];

    // Giriş alanlarını doğrula
    if (empty($e_posta)) {
        $errors[] = "E-posta alanı gereklidir.";
    }

    if (empty($sifre)) {
        $errors[] = "Şifre alanı gereklidir.";
    }

    // Hata yoksa, kullanıcıyı doğrula
    if (empty($errors)) {
        try {
            // E-posta adresine göre kullanıcıyı bul
            $stmt = $conn->prepare("SELECT * FROM `Kayıt_Tablosu` WHERE e_posta = ?");
            $stmt->bind_param("s", $e_posta);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();

            // Kullanıcı bulundu ve şifre doğru mu?
            if ($user && password_verify($sifre, $user['sifre'])) {
                // Token oluştur
                $token = generateToken();
                $user_id = $user['id'];
                $expires_at = date('Y-m-d H:i:s', time() + TOKEN_EXPIRY);

                // Eski token'ları temizle
                $stmt = $conn->prepare("DELETE FROM auth_tokens WHERE user_id = ?");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $stmt->close();

                // Yeni token'ı kaydet
                $stmt = $conn->prepare("INSERT INTO auth_tokens (user_id, token, expires_at) VALUES (?, ?, ?)");
                $stmt->bind_param("iss", $user_id, $token, $expires_at);
                $stmt->execute();
                $stmt->close();

                // Token'ı çerezde sakla
                setcookie('auth_token', $token, time() + TOKEN_EXPIRY, '/', '', false, true);

                // Kullanıcıyı dashboard'a yönlendir
                header("Location: dashboard.php");
                exit;
            } else {
                $errors[] = "Geçersiz e-posta veya şifre.";
            }
        } catch (Exception $e) {
            $errors[] = "Veritabanı hatası: " . $e->getMessage();
        }
    }

    // Hata varsa, hataları göster
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p class='error-message'>" . $error . "</p>";
        }
    }
}

// Güvenli token oluşturma fonksiyonu
function generateToken() {
    return bin2hex(random_bytes(32));
}
?>