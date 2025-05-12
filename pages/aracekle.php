<?php 
session_start();
include('config.php');

$kullanici_id = $_SESSION['id'];
$plaka = $_POST['plaka'];

$stmt = $conn->prepare("SELECT numberplate FROM users WHERE id = ?");
$stmt->bind_param("i", $kullanici_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$eskiplaka = $row['numberplate'] ?? '';

$stmt->close();


if (!empty($eskiplaka)) {
    $yeniplaka = $eskiplaka . ',' . $plaka;
} else {
    $yeniplaka = $plaka;
}

$stmt = $conn->prepare("UPDATE users SET numberplate = ? WHERE id = ?");
$stmt->bind_param("si", $yeniplaka, $kullanici_id);
$stmt->execute();
$stmt->close();
header("Location: ../index.php"); // Başarılı giriş sonrası ana sayfaya yönlendir
exit();
?>
