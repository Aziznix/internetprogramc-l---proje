<?php 
include('config.php');
header("Content-Type: application/json"); // fetch() için şart
date_default_timezone_set("Europe/Istanbul");
$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (isset($data['veriler']) && is_array($data['veriler'])) {
    foreach ($data['veriler'] as $odeme) {
        $musteri_id = $odeme['id'];
        $tutar = $odeme['tutar'];
        $parkyeri = $odeme['parkyeri'];
        $plaka = $odeme['aracplaka'];
        $tarih = $odeme['tarih'];
        $sure = $odeme['sure'];
    }

    // saniyeyi saate çevir
    $sure = $sure / 60 / 60;

    $stmt = $conn->prepare("INSERT INTO otopark_pay 
        (`musteri_id`, `tutar`, `park_yeri`, `arac_plaka`, `sure`, `tarih`) 
        VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("idssss", $musteri_id, $tutar, $parkyeri, $plaka, $sure, $tarih);

    if ($stmt->execute()) {
        $stmt->close();

        // Park yeri tablosunu boşalt
        $stmt = $conn->prepare("UPDATE park_yerleri SET 
            durum = 'bos', 
            user_id = NULL, 
            arac_plaka = NULL, 
            tarih = NULL, 
            saat = NULL 
            WHERE arac_plaka = ?");
        $stmt->bind_param("s", $plaka);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "fail", "error" => $stmt->error]);
        }
    } else {
        echo json_encode(["status" => "fail", "error" => $stmt->error]);
    }
} else {
    echo json_encode(["status" => "fail", "error" => "Geçersiz veri"]);
}
