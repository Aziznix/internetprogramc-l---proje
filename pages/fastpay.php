<?php 
include('config.php');
session_start();

$time = date("H:i:s");
$day = date("Y-m-d");
$veriler = [];
$toplam = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['veriler_json'])) {
    $json = json_decode($_POST['veriler_json'], true);

    if (
        isset($json['park']) &&
        isset($json['user']) &&
        isset($json['ucret']) &&
        is_array($json['park']) &&
        is_array($json['user'])
    ) {
        $veriler = $json;
        $toplam = $json['ucret'];
    } else {
        echo "<script>console.error('JSON eksik veya hatalı.')</script>";
    }
}


else{
    echo "<script>console.error('Json Gelmiyor')</script>";
}

#şimdilik ad soyadı böyle yapıyoruz ama ay soyad ayrılıcak ozaman düzelticez!!!!!


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
<style>
        .HizliOdemeContainer {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            height: 50vh;
            
        }
        .HizliOdeme{
            display: flex;
            flex-direction: column;
        }
        .HizliOdemeSorgu {
            margin: 10px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #dcdcdc;
            height: 11vh;
            width: 93vh;
        }
        .HizliOdemeSorgu h1{
            margin-top: -50px;
            margin-bottom: 25px;
        }
        .HizliOdemeSonuc {
            background-color: #dcdcdc;
            margin: 10px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            height: 25vh;
        }
        .HizliOdemeOdeme{
            background-color: #dcdcdc;
            padding: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            height: 38vh;
        }
        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }

        th, td {
            padding: 12px 16px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }
</style>
<body>
<nav>
    <div class="logo">
        <a href="index.php"><img src="../images/logo.png" alt="Logo" /></a>
    </div>
    <div class="menu">
        <ul>
            <li><a href="../index.php">Otoparklar</a></li>
            <li><a href="../fastpay.php">Hızlı Ödeme</a></li>
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

<div class="HizliOdemeContainer">
    <div class="HizliOdeme"><!------------------------------Plaka alınan yer ve alınan plakaya göre çıkarılan sorgu-->
        <div class="HizliOdemeSorgu"><!---------------------Plakanın alındığı yer-->
            <h1>Hızlı <span style="color: skyblue; font-weight:bold">Ödeme<span></h1>

            <form id="fastpay-form" method="POST" action="" style="display:flex;gap: 20px;">
                <label for="plaka" style="font-size:24px ;font-weight:bold">Plaka:</label>
                <input type="text" id="plaka" name="plaka" required style="border-radius: 10px; border: 1px solid #ccc">
                <button type="submit" style="margin-left: -10px;border-radius: 10px; border: 1px solid #ccc ;padding:5px;color:white;background-color:lightgreen">Sorgula</button>
            </form>

            <div id="message"></div>
        </div>
        <form id="hidden-form" method="POST" action="fastpay.php" style="display:none;">
            <input type="hidden" name="veriler_json" id="veriler_json">
        </form>

        
        <div class="HizliOdemeSonuc"><!---------------------Alınan plakanın sonuçlarını çıktığı yer-->
            <h1>İşlem Geçmişi</h1>
            <div class="table-wrapper">
                <table>
                    <thead>
                    <tr>
                        <th>Ad</th>
                        <th>Soyad</th>
                        <th>Plaka</th>
                        <th>Giriş Saati</th>
                        <th>Çıkış Saati</th>
                        <th>Giriş Tarih</th>
                        <th>Çıkış Tarih</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php if($toplam != 0){?>
                    <tr>
                        <td><?php echo $veriler['user']['nameandsurname']; ?></td>
                        <td><?php echo $veriler['user']['nameandsurname']; ?></td>
                        <td><?php echo $veriler['park']['arac_plaka']; ?></td>
                        <td><?php echo $veriler['park']['saat']; ?></td>
                        <td><?php echo $time; ?></td>
                        <td><?php echo $veriler['park']['tarih']; ?></td>
                        <td><?php echo $day; ?></td>
                    </tr>
                    <?php 
                    $arac_plaka = $veriler['park']['arac_plaka'];
                    $sure = strtotime($time) - strtotime($veriler['park']['saat']);
                    $park_yeri = $veriler['park']['park_id'];
                    $musteri_id = $veriler['user']['id'];
                    $odemetarih = $day;
                }?>
                    </tbody>
                </table>
                </div>

                <h2 style="margin-top:10px">Toplam Tutar: <?php echo $toplam?>TL<h2>
        </div>
    </div>
    <script>
  const musteri_id = <?= json_encode($musteri_id ?? "") ?>;
  const toplam = <?= json_encode($toplam ?? "") ?>;
  const park_yeri = <?= json_encode($park_yeri ?? "") ?>;
  const arac_plaka = <?= json_encode($arac_plaka ?? "") ?>;
  const sure = <?= json_encode($sure ?? "") ?>;
  const odemetarih = <?= json_encode($odemetarih ?? "") ?>;
</script>


    <div class="HizliOdemeOdeme">
    <form style="max-width: 300px; max-height:280px" id="odemeform">
  <label>Kredi Kart Numarası</label><br>
  <input type="text" maxlength="4" style="width: 60px; border: 2px solid skyblue;"required> 
  <input type="text" maxlength="4" style="width: 60px; border: 2px solid skyblue;"required> 
  <input type="text" maxlength="4" style="width: 60px; border: 2px solid skyblue;"required> 
  <input type="text" maxlength="4" style="width: 60px; border: 2px solid skyblue;"required><br>


  <br><br>
  <label>Son Kullanım Tarihi</label><br>
  <select required>
    <option>Ay</option>
    <option>01</option>
    <option>02</option>
    <option>03</option>
    <option>04</option>
    <option>05</option>
    <option>06</option>
    <option>07</option>
    <option>08</option>
    <option>09</option>
    <option>10</option>
    <option>11</option>
    <option>12</option>
  </select>
  <select>
    <option>Yıl</option>
    <option>2025</option>
    <option>2026</option>
    <option>2027</option>
    <option>2028</option>
    <option>2029</option>
    <option>2030</option>
    <option>2031</option>
    <option>2032</option>
    <option>2033</option>
  </select>

  <br><br>
  <label>Kart Güvenlik Numarası</label><br>
  <input type="text" maxlength="3" style="width: 100px;" required><br><br>

  <button type="submit">Ödeme Yap</button>
</form>


    </div>
    
</div>

<script>
   document.getElementById('fastpay-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const plaka = document.getElementById('plaka').value;

    fetch('plaka_sorgula.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'plaka=' + encodeURIComponent(plaka)
    })
    .then(res => res.json())
    .then(data => {
    if (!data.park || !data.user || !data.ucret) {
        alert("Plaka bulunamadı veya veri eksik!");
        return;
    }
    document.getElementById('veriler_json').value = JSON.stringify(data);
    document.getElementById('hidden-form').submit();
});
});
document.getElementById("odemeform").addEventListener("submit", function(e) {
  e.preventDefault();
  const veriler = [
  {
    id: musteri_id,
    tutar: toplam,
    parkyeri: park_yeri,
    aracplaka: arac_plaka,
    sure: sure,
    tarih: odemetarih
  }
];

  fetch("odeme.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({ veriler: veriler })
  })
  .then(res => res.json())
  .then(data => {
    if(data.status === "success"){
        window.location.href = "../pages/thankyou.php";
    }
    else{
        alert("Hata" + (data.error || "İşlem başarisiz"));
    }
  });
});

</script>

</body>
</html>
