<?php 
// config.php'yi dahil ediyoruz
include('config.php');
session_start();

// Veritabanı bağlantısını başlatıyoruz
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    die("Veritabanı bağlantısı başarısız: " . mysqli_connect_error());
}

// Kullanıcı bilgilerini al
$query_users = "SELECT * FROM users"; // Kullanıcı tablosu
$result_users = mysqli_query($conn, $query_users);

// Otopark pay bilgilerini al
$query_pay = "SELECT * FROM otopark_pay";
$result_pay = mysqli_query($conn, $query_pay);

// Verileri bir dizi olarak alıyoruz
$users_data = mysqli_fetch_all($result_users, MYSQLI_ASSOC);
$pay_data = mysqli_fetch_all($result_pay, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Paneli</title>
    <link rel="stylesheet" href="../assest/style.css">
    <style>
        /* Admin paneline özel stiller */
        .admin-container {
            display: flex;
            height: 80vh;
        }
        .left-panel {
            width: 25%;
            background-color: #f4f4f4;
            padding: 20px;
            border-right: 1px solid #ddd;
        }
        .right-panel {
            width: 75%;
            padding: 20px;
        }
        .left-panel a {
            display: block;
            padding: 10px;
            margin: 10px 0;
            background-color: lightskyblue;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .left-panel a:hover {
            background-color: lightgoldenrodyellow;
            color: lightskyblue;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: lightskyblue;
            color: white;
        }
    </style>
</head>
<body>

<?php include('header.php'); ?> <!-- Üst header'ı dahil ediyoruz -->

<div class="admin-container">
    <!-- Sol Panel -->
    <div class="left-panel">
        <a href="#" id="user-info">Kullanıcı Bilgileri</a>
        <a href="#" id="otopark-pay">Otopark Pay</a>
    </div>

    <!-- Sağ Panel -->
    <div class="right-panel" id="right-panel">
        <!-- Burada kullanıcı bilgileri veya otopark pay verisi gösterilecek -->
    </div>
</div>

<script>
    // PHP'den gelen verileri JavaScript'e aktarıyoruz
    const usersData = <?php echo json_encode($users_data); ?>;
    const payData = <?php echo json_encode($pay_data); ?>;

    // Kullanıcı Bilgileri butonuna tıklanınca
    document.getElementById('user-info').addEventListener('click', function() {
        let tableContent = `<table>
            <tr>
                <th>ID</th>
                <th>Ad Soyad</th>
                <th>Email</th>
                <th>Telefon</th>
                <th>Doğum Tarihi</th>
                <th>Araç Plakası</th>
            </tr>`;
        
        // PHP verisini HTML olarak işleyip tabloya ekliyoruz
        usersData.forEach(user => {
            tableContent += `<tr>
                <td>${user.id}</td>
                <td>${user.name}</td>
                <td>${user.email}</td>
                <td>${user.phone}</td>
                <td>${user.birthday}</td>
                <td>${user.numberplate}</td>
            </tr>`;
        });

        tableContent += `</table>`;
        document.getElementById('right-panel').innerHTML = tableContent;
    });

    // Otopark Pay butonuna tıklanınca
    document.getElementById('otopark-pay').addEventListener('click', function() {
        let tableContent = `<table>
            <tr>
                <th>Fatura ID</th>
                <th>Müşteri ID</th>
                <th>Tutar</th>
                <th>Park Yeri</th>
                <th>Arac Plaka</th>
                <th>Süre</th>
                <th>Saat</th>
                <th>Tarih</th>
            </tr>`;
        
        // PHP verisini HTML olarak işleyip tabloya ekliyoruz
        payData.forEach(pay => {
            tableContent += `<tr>
                <td>${pay.fatura_id}</td>
                <td>${pay.musteri_id}</td>
                <td>${pay.tutar}</td>
                <td>${pay.park_yeri}</td>
                <td>${pay.arac_plaka}</td>
                <td>${pay.sure}</td>
                <td>${pay.saat}</td>
                <td>${pay.tarih}</td>
            </tr>`;
        });

        tableContent += `</table>`;
        document.getElementById('right-panel').innerHTML = tableContent;
    });
</script>

</body>
</html>
