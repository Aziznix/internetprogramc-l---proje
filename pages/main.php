<?php
include 'config.php';?>
<?php
$stmt = $conn->prepare("SELECT * FROM park_yerleri WHERE user_id = ? AND durum = 'rezerve'");
$stmt->bind_param("s", $_SESSION['id']);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $saat = $row['saat'];
        ?>

        <div style='margin-top:50px;margin-left:20%;width:60%;background-color:skyblue;justify-items:left;padding:20px;border:1px solid #ccc;border-radius:10px;'>
            <div style="display: flex;flex-direction:row; gap:30px;">
                <div class='uyariyazisi'>
                    <h4>Rezervasyonunuz bulunmaktadır!</h4>
                    <h6>Farklı bir kiralama işlemi yapmadan önce lütfen rezervasyonunuzu iptal edin ya da aracınızı belirlediğiniz yere park edin!</h6>
                    <h6>Rezervasyonun sona ermesine kalan süre:</h6>
                    <h3 id="countdown"></h3>
                </div>
                <div style="display: flex; flex-direction:column; gap:10px;margin-top:20px;">
                    <button style="padding: 10px; background-color:lightsalmon; border-radius: 10px; border:1px solid #ccc;font-weight:bold"><a style="text-decoration: none;color:aliceblue" href="pages/rezervasyon_iptal.php">Rezervasyon İptal</a></button>
                    <button style="padding: 10px; background-color:lightgreen; border-radius: 10px; border:1px solid #ccc;font-weight:bold"><a style="text-decoration: none; color:aliceblue" href="pages/parket.php">Park Et</a></button>
                </div>
            </div>
        </div>

    <script>
    const phpSaat = "<?= $saat ?>"; // örn: "01:37:13"
    const now = new Date();

    // YEREL TARİHİ DOĞRU FORMATTA AL
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const todayStr = `${year}-${month}-${day}`; // Örn: 2025-05-13

    let start = new Date(`${todayStr}T${phpSaat}`);
    let bitis = new Date(start.getTime() + 15 * 60 * 1000);

    // Eğer bitiş çoktan geçtiyse, tarih aslında düne aitti
    if (bitis.getTime() < now.getTime() - (23 * 60 * 60 * 1000)) {
        const yesterday = new Date(now);
        yesterday.setDate(yesterday.getDate() - 1);
        const yYear = yesterday.getFullYear();
        const yMonth = String(yesterday.getMonth() + 1).padStart(2, '0');
        const yDay = String(yesterday.getDate()).padStart(2, '0');
        const yesterdayStr = `${yYear}-${yMonth}-${yDay}`;
        start = new Date(`${yesterdayStr}T${phpSaat}`);
        bitis = new Date(start.getTime() + 15 * 60 * 1000);
    }

    console.log("PHP'den gelen saat:", phpSaat);
    console.log("Başlangıç zamanı (JS):", start);
    console.log("Bitiş zamanı:", bitis);
    console.log("Şu an:", now);

    const countdown = setInterval(() => {
        const simdi = new Date().getTime();
        const fark = bitis.getTime() - simdi;

        console.log("Kalan süre (ms):", fark);

        if (fark <= 0) {
            clearInterval(countdown);
            document.getElementById("countdown").innerHTML = "Rezervasyon süresi doldu!";

            fetch("http://localhost/internetprogramcılığıproje/pages/rezervasyon_iptal.php", {
                method: "POST"
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    alert("Rezervasyon süresi dolduğu için iptal edildi.");
                    location.reload();
                }
            });

            return;
        }

        const dakika = Math.floor((fark % (1000 * 60 * 60)) / (1000 * 60));
        const saniye = Math.floor((fark % (1000 * 60)) / 1000);
        document.getElementById("countdown").innerHTML = `${dakika} dakika ${saniye} saniye`;
    }, 1000);
</script>







        <?php
    }
}
$stmt->close();
?>
<?php
$sql = "
SELECT *
FROM otopark
INNER JOIN otoparkdoluluk ON otopark.otopark_id = otoparkdoluluk.otopark_id
";
$result = $conn->query($sql);

?>

<main>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            ?>
            <button class="accordion">
                <div class="otopark-head">
                    <h4>
                        <?php 
                        echo $row['otopark-adi']; 
                        ?>
                    </h4>
                    <div class="skill">
                        <span class="material-symbols-outlined" style="color: rgba(255, 255, 255, <?php echo $row['otopark-lpg']+0.4;?>);">propane_tank</span>
                        <span class="material-symbols-outlined" style="color: rgba(255, 255, 255, <?php echo $row['otopark-alan']+0.4;?>);">garage_home</span>
                        <h5>
                            <?php
                            echo $row['dolu-alan']."/".$row['otopark-limit'];
                            ?>
                        </h5>
                    </div>
                </div>
            </button>
            <div class="panel">
                <?php include 'content.php'; ?>
            </div>
            <?php
        }
    } else {
        echo "Veri bulunamadı.";
    }
    ?>
</main>
