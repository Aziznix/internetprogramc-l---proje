<?php 
include('./config.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['plaka'])) {
    $plaka = $_POST['plaka'];
    $response = [];

    // park_yerleri tablosundan veri çek
    $stmt = $conn->prepare("SELECT * FROM park_yerleri WHERE arac_plaka = ?");
    $stmt->bind_param("s", $plaka);
    $stmt->execute();
    $result = $stmt->get_result();
    $park_data = $result->fetch_assoc();
    $stmt->close();

    if ($park_data) {
        $response['park'] = $park_data;

        // kullanıcı bilgisi
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $park_data['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $user_data = $result->fetch_assoc();
        $stmt->close();

        if ($user_data) {
            $response['user'] = $user_data;
        }

        // sabit ücret
        $response['ucret'] = 100;
    }

    echo json_encode($response);
}
?>
