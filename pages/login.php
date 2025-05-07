<?php
// Bağlantı dosyasını dahil et
include('config.php');

// Hata mesajları için değişken
$error_message = "";

// Verilerin POST ile gelip gelmediğini kontrol et
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verilerin gelip gelmediğini kontrol et
    if (isset($_POST["kullanici_adi"]) && isset($_POST["parola"])) {
        $kullanici_adi = mysqli_real_escape_string($conn, $_POST["kullanici_adi"]);
        $sifre = mysqli_real_escape_string($conn, $_POST["parola"]);

        // Kullanıcıyı veritabanında ara
        $sql = "SELECT * FROM kullanicilar WHERE kullanici_adi = '$kullanici_adi'";
        $sonuc = mysqli_query($conn, $sql);

        if (mysqli_num_rows($sonuc) > 0) {
            $row = mysqli_fetch_assoc($sonuc);

            // Şifreyi düz metin olarak karşılaştır (hashing kullanılmıyor)
            if ($sifre == $row["parola"]) {
                session_start();
                $_SESSION["kullanici_adi"] = $kullanici_adi;
                header("Location: ../index.php"); // Başarılı giriş sonrası ana sayfaya yönlendir
                exit();
            } else {
                // Şifre hatalı mesajı
                $error_message = 'Şifre hatalı';
            }
        } else {
            // Kullanıcı adı bulunamadı
            $error_message = 'Kullanıcı adı bulunamadı';
        }
    } else {
        $error_message = 'Lütfen tüm alanları doldurun.';
    }
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container p-5">
    <div class="card p-5">
        <h2 class="text-center mb-4">Giriş Yap</h2>

        <?php
        // Hata mesajını göster
        if ($error_message != "") {
            echo '<div class="alert alert-danger" role="alert">' . $error_message . '</div>';
        }
        ?>

        <form action="login.php" method="POST">
            <div class="mb-3">
                <label for="kullanici_adi" class="form-label">Kullanıcı Adı</label>
                <input type="text" class="form-control" id="kullanici_adi" name="kullanici_adi" required>
            </div>
            <div class="mb-3">
                <label for="parola" class="form-label">Şifre</label>
                <input type="password" class="form-control" id="parola" name="parola" required>
            </div>
            <button type="submit" name="giris" class="btn btn-primary">Giriş Yap</button>
        </form>

        <hr>
        <p class="text-center">Hesabınız yok mu? <a href="register.php">Kayıt Olun</a></p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
