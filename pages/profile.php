<?php
session_start(); // Oturum başlat

// Eğer kullanıcı giriş yapmadıysa, giriş sayfasına yönlendir
if (!isset($_SESSION['kullanici_adi'])) {
    header("Location: login.php");
    exit();
}

// Giriş yapan kullanıcının adını al
$kullanici_adi = $_SESSION['kullanici_adi'];

echo "<h1>Hoşgeldiniz, $kullanici_adi!</h1>";
echo '<a href="exit.php" class="btn btn-danger">Çıkış Yap</a>';
?>
