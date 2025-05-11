<?php 
include('config.php');
session_start();

// Form verileri gönderildiğinde veritabanına ekleme işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Kullanıcı girişi kontrolü
    if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
        echo "Kullanıcı ID'si bulunamadı. Lütfen giriş yapın.";
        exit();
    }

    // Formdan alınan veriler
    $kullanici_id = $_SESSION['id'];
    $plaka = $_POST['plaka'];
    $parkYeri = $_POST['yer'];
    $otoparkId = $_POST['otopark'];

    $insertArac = "INSERT INTO park_yerleri (kullanici_id, plaka, park_id, otopark_id) VALUES (?, ?, ?, ?)";
    $stmtArac = $conn->prepare($insertArac);
    
    $stmtArac->bind_param("sssi", $kullanici_id, $plaka, $park_id, $otoparkId);
    
  
    if ($stmtArac->execute()) {
        header("refresh:2;url=../index.php");
         echo "Rezervasyon başarılı!";

    
    
    exit(); 
    } else {
        echo "Hata: " . $stmtArac->error;
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gelişim Otopark</title>
    <link rel="stylesheet" href="../assest/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
</head>
<body>
<nav>
    <!-- Menü ve diğer içerikler buraya gelir -->
</nav>

<div class="rezervasyon">
    <div style="margin-bottom:20px;">
        <h2>Rezervasyon</h2>
        <h3>Otopark Seçiniz</h3>
    </div>
    <div>
        <form method="POST" action="rezerv.php" class="secim" style="justify-items: center; align-items: center;">
            <select name="otopark" id="otopark" style="border:none; margin-right:100px; margin-bottom:10px; margin-left: 20%">
                <?php 
                // Otoparkları çekiyoruz
                $sql = 'SELECT * FROM otopark';
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['otopark_id'] . "'>" . $row['otopark-adi'] . "</option>";
                    }
                } else {
                    echo "<option value=''>Hiç otopark bulunmamaktadır.</option>";
                }
                ?>
            </select>

            <select name="yer" id="yer" style="border:none; margin-right:100px; margin-bottom:20px; margin-left: 20%">
                <option value="">Önce otopark seçin</option>
            </select>

            <input type="text" name="plaka" placeholder="Plaka Giriniz" required 
                   style="margin-bottom: 20px; margin-left: 20%; border: none; border-radius: 5px;"><br>

            <input type="time" name="saat" required 
                   style="margin-bottom: 50px; margin-left: 20%; border: none; border-radius: 5px;"><br>

            <button type="submit" class="btn btn-primary" style="padding:20px; background-color: lightskyblue; border:none; border-radius:10px; margin-left:30%">Rezervasyon Yap</button>
        </form>
    </div>
</div>

<script>
// Otopark seçildiğinde park yerlerini dinamik olarak yüklemek için JavaScript
document.getElementById('otopark').addEventListener('change', function() {
    var otoparkAdi = this.value;
    
    if (otoparkAdi) {
        // AJAX ile park yerlerini getiriyoruz
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '', true);  
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var yerler = JSON.parse(xhr.responseText);
                
                // Yerler select kutusunu temizle
                var yerSelect = document.getElementById('yer');
                yerSelect.innerHTML = '<option value="">Yer seçin</option>';
                
                // Yeni seçenekleri ekle
                yerler.forEach(function(yer) {
                    var option = document.createElement('option');
                    option.value = yer.park_id;
                    option.textContent = yer.park_id;  
                    yerSelect.appendChild(option);
                });
            }
        };
        xhr.send('otopark_adi=' + otoparkAdi);
    }
});
</script>

</body>
</html>