<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'auth-functions.php';

// Kullanıcı girişi gerektirir
$user = authenticateUser();
if (!$user) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
  <style>
    /* Temel stil sıfırlaması */
    body, h1, h2, h3, p, a {
      margin: 0;
      padding: 0;
    }
    body {
      font-family: 'Roboto', sans-serif;
      background: #f4f7f6;
      color: #333;
      line-height: 1.6;
    }
    .container {
      max-width: 1100px;
      margin: 0 auto;
      padding: 20px;
    }
    .header {
      background: #007bff;
      color: #fff;
      padding: 20px;
      text-align: center;
      border-radius: 8px;
      margin-bottom: 20px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .header h1 {
      font-size: 2.5em;
      font-weight: 700;
    }
    .user-info {
      text-align: right;
      margin-bottom: 20px;
    }
    .logout-btn {
      display: inline-block;
      background: #dc3545;
      color: #fff;
      padding: 10px 20px;
      border-radius: 5px;
      text-decoration: none;
      transition: background 0.3s ease;
    }
    .logout-btn:hover {
      background: #c82333;
    }
    .welcome-box {
      background: #fff;
      border-left: 5px solid #007bff;
      padding: 20px;
      border-radius: 8px;
      margin-bottom: 20px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .welcome-box h2 {
      font-size: 1.8em;
      margin-bottom: 10px;
    }
    .welcome-box p {
      font-size: 1.1em;
    }
    .content {
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .content h3 {
      margin-bottom: 10px;
      font-size: 1.6em;
    }
    .content p {
      font-size: 1.1em;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>Dashboard</h1>
    </div>
    <div class="user-info">
      <a href="logout.php" class="logout-btn">Çıkış Yap</a>
    </div>
    <div class="welcome-box">
      <h2>Hoş Geldiniz, <?php echo htmlspecialchars($user['ad_soyad']); ?>!</h2>
      <p>E-posta: <?php echo htmlspecialchars($user['e_posta']); ?></p>
    </div>
    <div class="content">
      <h3>Uygulama İçeriği</h3>
      <p>
        Burada uygulamanızın detaylarını, istatistiklerini veya duyuruları görüntüleyebilirsiniz. 
        Token tabanlı kimlik doğrulama sistemi sayesinde güvenli bir şekilde giriş yapmış durumdasınız.
      </p>
    </div>
  </div>
</body>
</html>