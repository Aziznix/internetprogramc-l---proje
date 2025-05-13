<?php 
include('config.php');
session_start();

// AJAX isteği: Otopark yerlerini getir
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

        $yer_sql = "SELECT * FROM park_yerleri WHERE otopark_id = ? AND durum = 'bos'";
        $stmt = $conn->prepare($yer_sql);
        $stmt->bind_param("i", $otopark_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $yerler = [];
        while ($row = $result->fetch_assoc()) {
            $yerler[] = $row;
        }

        echo json_encode([
            "yerler" => $yerler,
            "otopark_id" => $otopark_id
        ]);
    } else {
        echo json_encode(["yerler" => [], "otopark_id" => null]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Gelişim Otopark</title>
    <link rel="stylesheet" href="../assest/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
</head>
<body>

<nav>
    <div class="logo">
        <a href="../index.php"><img src="../images/logo.png" alt="Logo" /></a>
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

<?php 
if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];

    // Kullanıcının rezerve veya dolu kaydı var mı?
    $stmt = $conn->prepare("SELECT durum FROM park_yerleri WHERE user_id = ? AND (durum = 'rezerve' OR durum = 'dolu')");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if ($row['durum'] == 'rezerve') {
            echo "<div class='rezervasyon'>
                    <h2>Rezervasyon</h2>
                    <div style='margin:30px; padding:20px; background-color:#ffcccc; border:1px solid #cc0000; border-radius:10px; width:400px;'>
                        <h3>Zaten aktif bir rezervasyonunuz bulunmaktadır.</h3>
                        <p>Yeni rezervasyon yapabilmek için önce mevcut rezervasyonunuzu iptal etmelisiniz.</p>
                    </div>
                  </div>";
        } elseif ($row['durum'] == 'dolu') {
            echo "<div class='rezervasyon'>
                    <h2>Park Halindesiniz</h2>
                    <div style='margin:30px; padding:20px; background-color:#ffcccc; border:1px solid #cc0000; border-radius:10px; width:400px;'>
                        <h3>Zaten aktif olarak park halindesiniz.</h3>
                        <p>Yeni rezervasyon yapabilmek için önce ödeme yapıp park yerinden ayrılmanız gerekmektedir.</p>
                    </div>
                  </div>";
        }
    } else {
        // Rezerve veya dolu kaydı yoksa formu göster
?>

<div class="rezervasyon">
    <div style="margin-bottom:50px;">
        <h2>Rezervasyon</h2>
        <h3>Otopark Seçiniz</h3>
    </div>
    <div>
        <form method="POST" action="rezerv.php" class="secim" style="justify-items: center; align-items: center;">
            <select name="otopark" id="otopark" style="margin-bottom:20px; margin-left: 20%">
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

            <select name="yer" id="yer" style="margin-bottom:20px; margin-left: 20%">
                <option value="">Önce otopark seçin</option>
            </select>

            <select name="plaka" style="margin-bottom:20px; margin-left: 20%">
               <?php 
                $stmt = $conn->prepare("SELECT numberplate FROM users WHERE id = ?");
                $stmt->bind_param("i", $_SESSION['id']);
                $stmt->execute();
                $result2 = $stmt->get_result();

                if ($row = $result2->fetch_assoc()) {
                    $numberplates = explode(',', $row['numberplate']);
                    foreach ($numberplates as $plaka) {
                        $plaka = trim($plaka);
                        if (!empty($plaka)) {
                            echo "<option value='" . htmlspecialchars($plaka) . "'>" . htmlspecialchars($plaka) . "</option>";
                        }
                    }
                }
                ?>
            </select>

            <input type="hidden" name="otopark_id" id="otopark_id">

            <button type="submit" style="padding:20px; background-color: lightskyblue; border:none; border-radius:10px; margin-left:30%">Rezervasyon Yap</button>
        </form>
    </div>
</div>

<script>
document.getElementById('otopark').addEventListener('change', function () {
    var otoparkAdi = this.value;

    if (otoparkAdi) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var data = JSON.parse(xhr.responseText);

                var yerSelect = document.getElementById('yer');
                yerSelect.innerHTML = '<option value="">Yer seçin</option>';

                data.yerler.forEach(function (yer) {
                    var option = document.createElement('option');
                    option.value = yer.park_id;
                    option.textContent = yer.park_id;
                    yerSelect.appendChild(option);
                });

                document.getElementById('otopark_id').value = data.otopark_id;
            }
        };
        xhr.send('otopark_adi=' + encodeURIComponent(otoparkAdi));
    }
});
</script>

<?php 
        } // aktif durum yoksa form göster
    }
else {
    echo "<h3>Rezervasyon yapabilmek için giriş yapmalısınız.</h3>";
}
?>

</body>
</html>
