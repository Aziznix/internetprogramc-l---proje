<?php

session_start();
if (!isset($_SESSION['kullanici_adi'])) {
    header("Location: ../index.php");
    exit();
}
$host = 'localhost';
$dbname = 'iguotopark';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}

// Kullanıcı bilgilerini çekiyor.
$userId = 1;
$query = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$query->execute(['id' => $userId]);
$user = $query->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $user = [
        'name' => '',
        'surname' => '',
        'email' => '',
        'birthday' => '',
        'phone' => '',
        'numberplate' => ''
    ];
}

// Form gönderimi kontrolü sağlıyor.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $surname = $_POST['surname'] ?? '';
    $email = $_POST['email'] ?? '';
    $birthday = $_POST['birthday'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $numberplate = $_POST['numberplate'] ?? '';

    // Veritabanında güncelleme yapmasını sağlar.
    $updateQuery = $pdo->prepare("
        UPDATE users 
        SET name = :name, surname = :surname, email = :email, birthday = :birthday, phone = :phone, numberplate = :numberplate
        WHERE id = :id
    ");
    $updateQuery->execute([
        'name' => $name,
        'surname' => $surname,
        'email' => $email,
        'birthday' => $birthday,
        'phone' => $phone,
        'numberplate' => $numberplate,
        'id' => $userId
    ]);

    echo json_encode(["success" => true, "message" => "Değişiklikler başarıyla kaydedildi."]);
    exit; // Sayfa yenilenmesini önler.
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assest/style.css" rel="stylesheet">
    <style>
        body {
            background-color: #eef2f3;
            color: #333;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);
            background-color: #ffffff;
        }

        .card-header {
            background-color: #007bff;
            color: #fff;
        }

        .btn-primary {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-primary:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #ced4da;
        }

        .alert-success {
            display: none;
            background-color: #d4edda;
            color: #155724;
        }
    </style>
</head>
<nav>
    <div class="logo">
        <a href="../index.php"><img src="../images/logo.png" alt="Logo" /></a>
    </div>
    <div class="menu">
        <ul>
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

<body>
    <div style="display: flex; flex-direction:row;">

        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header text-center">
                            <h4>Profile Panel</h4>
                        </div>
                        <div class="card-body">
                            <div id="successMessage" class="alert alert-success">
                                Değişiklikler başarıyla kaydedildi.
                            </div>

                            <form id="profileForm">
                                <div class="form-group">
                                    <label>Ad</label>
                                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Soyad</label>
                                    <input type="text" name="surname" class="form-control" value="<?= htmlspecialchars($user['surname']); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Doğum Günü</label>
                                    <input type="date" name="birthday" class="form-control" value="<?= htmlspecialchars($user['birthday']); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Telefon Numarası</label>
                                    <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone']); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Araç Plakası</label>
                                    <input type="text" name="numberplate" class="form-control" value="<?= htmlspecialchars($user['numberplate']); ?>">
                                </div>
                                <button type="submit" class="btn btn-primary btn-block">Değişiklikleri Kaydet</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <h1>profildetay</h1>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#profileForm').on('submit', function(e) {
                e.preventDefault(); // Sayfa yenilemesini engeller


                $.ajax({
                    url: '', // Aynı dosya üzerinden işlem yapmaya yarıyor.
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        const result = JSON.parse(response);
                        if (result.success) {
                            $('#successMessage').text(result.message).fadeIn();

                            // 3 saniye sonra mesaj kayboluyor.
                            setTimeout(function() {
                                $('#successMessage').fadeOut();
                            }, 3000);
                        }
                    },
                    error: function() {
                        alert('Bir hata oluştu.');
                    }
                });
            });
        });
    </script>
</body>

</html>