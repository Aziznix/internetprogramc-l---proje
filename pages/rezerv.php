<?php 
include('config.php');
session_start();
date_default_timezone_set("Europe/Istanbul");
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
        echo "Kullanıcı ID'si bulunamadı. Lütfen giriş yapın.";
        exit();
    }

    $kullanici_id = $_SESSION['id'];
    $plaka = $_POST['plaka'];
    $parkYeri = $_POST['yer'];
    $otoparkId = $_POST['otopark_id'];
    $tarih = date("y-m-d");
    $time = date("H:i:s");
    $pozisyon = "rezerve"; // bu durumu gösteriyor

   $insertArac = "UPDATE park_yerleri 
               SET user_id = ?, arac_plaka = ?, saat = ?, durum = ?, tarih = ? 
               WHERE park_id = ? AND otopark_id = ?";

    $stmtArac = $conn->prepare($insertArac);
  $stmtArac->bind_param('ssssssi', $kullanici_id, $plaka, $time, $pozisyon, $tarih, $parkYeri, $otoparkId);



    if ($stmtArac->execute()) {
        if ($stmtArac->affected_rows > 0) {
            echo "Rezervasyon başarılı!";
            header("refresh:2;url=../index.php");
        } else {
            echo "Hiçbir satır güncellenmedi. Belki park_id ve otopark_id eşleşmiyor.";
        }
        exit();
    } else {
        echo "Hata: " . $stmtArac->error;
    }
}
?>
