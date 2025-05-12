<?php
session_start();
include('config.php');

$userId = $_SESSION['id'];

$stmt = $conn->prepare("UPDATE park_yerleri SET durum = 'bos', user_id = NULL, saat = NULL  ,arac_plaka = NULL WHERE user_id = ? AND durum = 'rezerve'");
$stmt->bind_param("i", $userId);
if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
    header("Location: ../index.php");
    exit();
} else {
    echo json_encode(["status" => "error"]);
}
$stmt->close();
?>
