<?php
session_start();
include('config.php');

$userId = $_SESSION['id'];

$stmt = $conn->prepare("UPDATE park_yerleri SET durum = 'dolu' WHERE user_id = ? AND durum = 'rezerve'");
$stmt->bind_param("i", $userId);
if ($stmt->execute()) {
    
    header("Location: ../index.php");
    exit();
} else {
    echo "hata";
}
$stmt->close();
?>
