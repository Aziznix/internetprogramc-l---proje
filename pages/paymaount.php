<?php 
include('config.php');
session_start();


if (isset($_POST['otopark_adi'])) {
    $otopark_adi = $_POST['otopark_adi'];

  
    $sql = "SELECT otopark_id FROM otopark WHERE `otopark-adi` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $otopark_adi);
    $stmt->execute();
    $result = $stmt->get_result();
    $otopark = $result->fetch_assoc();

    if ($otopark) {
        
        $otopark_id = $otopark['otopark_id'];
        $yer_sql = "SELECT * FROM park_yerleri WHERE otopark_id = ?";
        $stmt = $conn->prepare($yer_sql);
        $stmt->bind_param("i", $otopark_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $yerler = [];
        while ($row = $result->fetch_assoc()) {
            $yerler[] = $row;
        }
        echo json_encode($yerler);
    } else {
        echo json_encode([]);
    }
    exit; 
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
    <div class="logo">
        <a href="index.php"><img src="../images/logo.png" alt="Logo" /></a>
    </div>
    <div class="menu">
        <ul>
            <li><a href="../index.php">Otoparklar</a></li>
            <li><a href="../pages/fastpay.php">Hızlı Ödeme</a></li>
            <?php if (isset($_SESSION['kullanici_adi'])): ?>
                <li><a href="../pages/paymaount.php">Rezervasyon</a></li>
                <li><a href="../pages/profil.php">Profil</a></li>
                <li><a href="../pages/exit.php">Çıkış Yap</a></li>
            <?php else: ?>
                <li><a href="../pages/login.php">Giriş Yap</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<div class="rezervasyon">
    <div style="margin-bottom:50px;">
        <h2>Rezervasyon</h2>
        <h3>Otopark Seçiniz</h3>
    </div>
    <div>
        <form method="POST" action="rezerv.php" class="secim" style="justify-items: center; align-items: center;">
            <select name="otopark" id="otopark" style="border:none; margin-right:100px; margin-bottom:20px; margin-left: 20%">
                <?php 
                
                $sql = 'SELECT * FROM otopark';
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['otopark-adi'] . "'>" . $row['otopark-adi'] . "</option>";
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
           style=" margin-bottom: 20px; margin-left: 20%; border: none; border-radius: 5px;"><br>

    <input type="time" name="saat" required 
           style=" margin-bottom: 50px; margin-left: 20%; border: none; border-radius: 5px;"><br>


            <button type="submit" class="btn btn-primary" style="padding:20px; background-color: lightskyblue; border:none; border-radius:10px; margin-left:30%">Rezervasyon Yap</button>
        </form>
    </div>
</div>

<script>

document.getElementById('otopark').addEventListener('change', function() {
    var otoparkAdi = this.value;
    
    if (otoparkAdi) {
       
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '', true);  
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var yerler = JSON.parse(xhr.responseText);
                
               
                var yerSelect = document.getElementById('yer');
                yerSelect.innerHTML = '<option value="">Yer seçin</option>';
                
                // Yeni seçenekleri ekle
                yerler.forEach(function(yer) {
                    var option = document.createElement('option');
                    option.value =yer.park_id;  // park_yerleri tablosundaki 'pozisyon' ve 'park_id' alanlarını kullanıyoruz
                    option.textContent =yer.park_id;  // park_yerleri tablosundaki 'pozisyon' ve 'park_id' alanlarını kullanıyoruz
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