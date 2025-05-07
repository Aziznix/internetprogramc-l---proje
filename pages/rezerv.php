<?php
// Veritabanı bağlantınızı buraya ekleyin
include('config.php');

// Formdan gelen veriyi kontrol et
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Seçilen otopark ve yer bilgisini al
    $otopark = $_POST['otopark'];
    $yer = $_POST['yer'];

    // Otopark ID'yi alın
    $sql = "SELECT otopark_id FROM otopark WHERE `otopark-adi` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $otopark);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $otoparkId = $row['otopark_id'];

        // Park yerini güncelle
        $updateSql = "UPDATE park_yerleri SET durum = 'rezerve' WHERE park_id = ? AND otopark_id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("si", $yer, $otoparkId);

        if ($updateStmt->execute()) {
            echo "Record updated successfully"; // Kontrol amaçlı
        } else {
            echo "Error updating record: " . $conn->error;
        }
        header("Location: ../index.php");
    } else {
        echo "No matching otopark found.";
    }
}
?>
