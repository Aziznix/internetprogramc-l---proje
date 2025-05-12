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

$userId = $_SESSION["id"]; // Kullanıcı ID'si burada sabit bir değer olarak alınmıştır, bu değeri dinamik hale getirebilirsin.
$query = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$query->execute(['id' => $userId]);
$user = $query->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $stmt = $pdo->prepare("INSERT INTO users (id) VALUES (:id)");
    if ($stmt->execute(['id' => $userId])) {
        echo "<script>console.log('Profil oluşturuldu'); location.reload();</script>";
    }


    
}

// Profil güncelleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $numberplate = $_POST['numberplate'];
    $birthday = $_POST['birthday'];

    $updateQuery = $pdo->prepare("UPDATE users SET name = :name, surname = :surname, email = :email, phone = :phone, numberplate = :numberplate, birthday = :birthday WHERE id = :id");
    $updateQuery->execute([
        'name' => $name,
        'surname' => $surname,
        'email' => $email,
        'phone' => $phone,
        'numberplate' => $numberplate,
        'birthday' => $birthday,
        'id' => $userId
    ]);

    // Sayfayı yeniden yükle
    header("Location: profil.php");
    exit();
}

// İşlem geçmişini çekme
$payQuery = $pdo->prepare("SELECT * FROM otopark_pay WHERE musteri_id = :musteri_id");
$payQuery->execute(['musteri_id' => $userId]);
$pays = $payQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Profil Sayfası</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }
        .profile-container {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background-color: lightblue;
            color: white;
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .sidebar img {
            width: 100px;
            border-radius: 50%;
            margin-bottom: 15px;
        }
        .sidebar h4 {
            margin-bottom: 30px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            margin: 10px 0;
            font-weight: 500;
            padding-bottom: 5px;
            position: relative;
        }
        .sidebar a.active::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            height: 2px;
            width: 100%;
            background-color: white;
        }
        .sidebar a:hover {
            text-decoration: none;
        }

        .content {
            flex: 1;
            padding: 40px;
            background-color: #f0f0f0;
        }

        .about-section {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.05);
        }

        .about-section h3 {
            margin-bottom: 25px;
            color: #34495e;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #e1e8f0;
            padding: 10px 0;
        }

        .info-row strong {
            width: 30%;
            color: #2c3e50;
        }

        .info-row span {
            width: 65%;
            text-align: right;
            color: #555;
        }

        .form-group label {
            font-weight: bold;
        }

        #araclarim .about-section {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.05);
        }

        #araclarim h4 {
            color: #2c3e50;
        }

        #araclarim p {
            color: #555;
            font-size: 16px;
        }
    </style>
</head>
<body>

<!-- Buraya header.php gelecek. -->

<div class="profile-container">
    <div class="sidebar">
        <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" alt="Avatar">
        <h4><?= htmlspecialchars($user['name']) . " " . htmlspecialchars($user['surname']) ?></h4>
        <a href="#" class="active" onclick="showSection('bilgilerim', event)">Bilgilerim</a>
        <a href="#" onclick="showSection('araclarim', event)">Araçlarım</a>
        <a href="#" onclick="showSection('gecmis', event)">İşlem Geçmişi</a>
        <a href="#" onclick="showSection('guncelle', event)">Bilgilerimi Güncelle</a>
        <a href="../index.php">Anasayfa</a>
    </div>

    <div class="content">
        <div id="bilgilerim" class="about-section">
    <h3>Bilgilerim</h3>
    <div class="info-row">
        <strong>Ad</strong>
        <span><?= htmlspecialchars($user['name']) ?></span>
    </div>
    <div class="info-row">
        <strong>Soyad</strong>
        <span><?= htmlspecialchars($user['surname']) ?></span>
    </div>
    <div class="info-row">
        <strong>Email</strong>
        <span><?= htmlspecialchars($user['email']) ?></span>
    </div>
    <div class="info-row">
        <strong>Telefon</strong>
        <span><?= htmlspecialchars($user['phone']) ?></span>
    </div>
    <!-- Plaka bilgisini kaldırdık -->
    <div class="info-row">
        <strong>Doğum Tarihi</strong>
        <span><?= htmlspecialchars($user['birthday']) ?></span>
    </div>
    </div>
        <div class="modal fade" id="aracEkleModal" tabindex="-1" aria-labelledby="aracEkleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                
                <div class="modal-header">
                    <h5 class="modal-title" id="aracEkleModalLabel">Araç Ekle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                </div>

                <div class="modal-body">
                    <form action="aracekle.php" method="POST">
                    <div class="form-group mb-3">
                        <label for="name">Araç Plakası</label>
                        <input type="text" class="form-control" id="plaka" name="plaka" required>
                    </div>
                    <div class="modal-footer px-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-primary">Kaydet</button>
                    </div>
                    </form>
                </div>

                </div>
            </div>
        </div>

        <div id="araclarim" class="about-section" style="display: none;">
            <div style="display: flex;flex-direction:row;justify-content:space-between">
            <h3>Araçlarım</h3>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#aracEkleModal" style="background-color: orange; border:1px solid #ccc; width:150px;">Araç Ekle</button>
            </div>
            <div id="carInfo" class="about-section">
                <table class="table table-bordered"> 
                    <thead>
                        <tr>
                            <th>Araç No</th>
                            <th>Plaka</th>
                        </tr>
                    </thead>
                    <tbody>
                       <?php
                        $numberplates = explode(',', $user['numberplate']); 
                        $count = 1;

                        foreach ($numberplates as $plaka) {
                            $plaka = trim($plaka); 
                            if (!empty($plaka)) {
                                echo "<tr>";
                                echo "<td>" . $count++ . "</td>";
                                echo "<td>" . htmlspecialchars($plaka) . "</td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="gecmis" class="about-section" style="display: none;">
            <h3>İşlem Geçmişi</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Fatura ID</th>
                        <th>Araç Plakası</th>
                        <th>Park Yeri</th>
                        <th>Süre</th>
                        <th>Tutar</th>
                        <th>Tarih</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pays as $pay): ?>
                        <tr>
                            <td><?= htmlspecialchars($pay['fatura_id']) ?></td>
                            <td><?= htmlspecialchars($pay['arac_plaka']) ?></td>
                            <td><?= htmlspecialchars($pay['park_yeri']) ?></td>
                            <td><?= number_format((float)$pay['sure'], 2) ?> Saat</td>
                            <td><?= htmlspecialchars($pay['tutar']) ?> TL</td>
                            <td><?= htmlspecialchars($pay['tarih']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Bilgilerimi Güncelle Formu -->
        <div id="guncelle" class="about-section" style="display: none;">
            <h3>Bilgilerimi Güncelle</h3>
            <form action="profil.php" method="POST">
                <div class="form-group">
                    <label for="name">Ad</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="surname">Soyad</label>
                    <input type="text" class="form-control" id="surname" name="surname" value="<?= htmlspecialchars($user['surname']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="phone">Telefon</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="numberplate">Plaka</label>
                    <input type="text" class="form-control" id="numberplate" name="numberplate" value="<?= htmlspecialchars($user['numberplate']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="birthday">Doğum Tarihi</label>
                    <input type="date" class="form-control" id="birthday" name="birthday" value="<?= htmlspecialchars($user['birthday']) ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Güncelle</button>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    
    function showSection(id, event) {
        event.preventDefault();
        document.getElementById('bilgilerim').style.display = 'none';
        document.getElementById('araclarim').style.display = 'none';
        document.getElementById('gecmis').style.display = 'none';
        document.getElementById('guncelle').style.display = 'none';

        document.getElementById(id).style.display = 'block';

        document.querySelectorAll('.sidebar a').forEach(a => a.classList.remove('active'));
        event.target.classList.add('active');
    }
</script>

</body>
</html>
