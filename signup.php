<?php
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Hesap Oluştur</h2>
            <form id="signup-form" action="signup-process.php" method="POST">
                <div class="form-group">
                    <label for="ad_soyad">Ad Soyad</label>
                    <input type="text" id="ad_soyad" name="ad_soyad" required>
                </div>
                <div class="form-group">
                    <label for="e_posta">E-posta</label>
                    <input type="email" id="e_posta" name="e_posta" required>
                </div>
                <div class="form-group">
                    <label for="sifre">Şifre</label>
                    <input type="password" id="sifre" name="sifre" required>
                </div>
                <div class="form-group">
                    <label for="confirm_sifre">Şifreyi Onayla</label>
                    <input type="password" id="confirm_sifre" name="confirm_sifre" required>
                </div>
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <div class="form-group">
                    <button type="submit">Kayıt Ol</button>
                </div>
                <div class="form-footer">
                    <p>Zaten hesabınız var mı? <a href="login.php">Giriş Yapın</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
