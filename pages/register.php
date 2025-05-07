<?php
// Bağlantı dosyasını dahil et
include('config.php');

// Hata mesajları için değişken
$error_message = "";

// Form gönderildiğinde işlemi başlat
if (isset($_POST["kaydet"])) {
    // Form verilerini al
    $name = mysqli_real_escape_string($conn, $_POST["kullaniciadi"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = mysqli_real_escape_string($conn, $_POST["parola"]);

    // Boş alan kontrolü
    if (empty($name) || empty($email) || empty($password)) {
        $error_message = "Lütfen tüm alanları doldurunuz.";
    } else {
        // Kullanıcı adının mevcut olup olmadığını kontrol et
        $kontrol = "SELECT * FROM kullanicilar WHERE kullanici_adi = '$name'";
        $sonuc = mysqli_query($conn, $kontrol);

        if (mysqli_num_rows($sonuc) > 0) {
            $error_message = "Bu kullanıcı adı zaten alınmış.";
        } else {
            // Şifreyi düz metin olarak veritabanına ekle (gerçek dünyada hash kullanmalısınız)
            $ekle = "INSERT INTO kullanicilar (kullanici_adi, email, parola) VALUES ('$name', '$email', '$password')";
            $calistirekle = mysqli_query($conn, $ekle);

            if ($calistirekle) {
                // Kayıt başarılıysa giriş sayfasına yönlendir
                header("Location: login.php");
                exit();
            } else {
                $error_message = "Kayıt eklenirken bir hata oluştu.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container p-5">
    <div class="card p-5">
        <h2 class="text-center mb-4">Kayıt Ol</h2>

        <?php
        // Hata mesajını göster
        if ($error_message != "") {
            echo '<div class="alert alert-danger" role="alert">' . $error_message . '</div>';
        }
        ?>

        <form action="register.php" method="POST">
            <div class="mb-3">
                <label for="kullaniciadi" class="form-label">Kullanıcı Adı</label>
                <input type="text" class="form-control" id="kullaniciadi" name="kullaniciadi" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="parola" class="form-label">Şifre</label>
                <input type="password" class="form-control" id="parola" name="parola" required>
            </div>
            <button type="submit" name="kaydet" class="btn btn-primary">Kayıt Ol</button>
        </form>

        <hr>
        <p class="text-center">Zaten hesabınız var mı? <a href="login.php">Giriş Yapın</a></p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
