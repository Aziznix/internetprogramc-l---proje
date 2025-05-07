<?php
session_start();
$_SESSION = []; // Oturum değişkenlerini temizler
session_destroy(); // Oturumu sonlandırır
session_unset(); // Oturumu tamamen sıfırlar

// Oturum kapatıldı mesajını konsolda görmek için kontrol amaçlı atadım
error_log("Oturum kapatıldı.");

// Yönlendirme için
header('Location: ../index.php');
exit();
?>
