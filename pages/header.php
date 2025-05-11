<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gelişim Otopark</title>
    <link rel="stylesheet" href="./assest/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
</head>
<body>
<nav>
    <div class="logo">
        <a href="index.php"><img src="images/logo.png" alt="Logo" /></a>
    </div>
    <div class="menu">
        <ul>
            <?php if (isset($_SESSION['yetki']) && $_SESSION['yetki'] == 1): ?>
                <li><a href="pages/admin.php">Admin Panel</a></li>
            <?php endif; ?>
                <li><a href="index.php">Otoparklar</a></li>
                <li><a href="pages/fastpay.php">Hızlı Ödeme</a></li>
            <?php if (isset($_SESSION['kullanici_adi'])): ?>
                <li><a href="pages/paymaount.php">Rezervasyon</a></li>
                <li><a href="pages/profil.php">Profil</a></li>
                <li><a href="pages/exit.php">Çıkış Yap</a></li>
            <?php else: ?>
                <li><a href="pages/login.php">Giriş Yap</a></li>
            <?php endif; ?>
     

        </ul>
    </div>
</nav>

