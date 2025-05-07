<?php 

$otopark_id = $row['otopark_id']; 
$park_sql = "SELECT * FROM park_yerleri WHERE otopark_id = $otopark_id";
$park_result = $conn->query($park_sql);

$a_parks = []; // A ile başlayan park yerleri
$b_parks = []; 
$c_parks = [];
$d_parks= [];
$e_parks= [];
$f_parks= [];


if ($park_result->num_rows > 0) {
    while ($park = $park_result->fetch_assoc()) {
        // park_id'nin ilk harfine göre gruplama
        if (strtoupper(substr($park['park_id'], 0, 1)) === 'A') {
            $a_parks[] = $park;
        } elseif (strtoupper(substr($park['park_id'], 0, 1)) === 'B') {
            $b_parks[] = $park;
        }
         elseif (strtoupper(substr($park['park_id'], 0, 1)) === 'C') {
            $c_parks[] = $park;
        }
         elseif (strtoupper(substr($park['park_id'], 0, 1)) === 'D') {
            $d_parks[] = $park;
        }
         elseif (strtoupper(substr($park['park_id'], 0, 1)) === 'E') {
            $e_parks[] = $park;
        }
         elseif (strtoupper(substr($park['park_id'], 0, 1)) === 'F') {
            $f_parks[] = $park;
        }
    }
}
?>

<div class="otopark">
    <?php if (!empty($a_parks)) { ?>
    <div class="row">
        <?php
        foreach ($a_parks as $park) {
            echo "<div class='park-yeri " . htmlspecialchars($park['durum']) . " " . htmlspecialchars($park['pozisyon']) . "' id='" . htmlspecialchars($park['park_id']) . "'>" . htmlspecialchars($park['park_id']) . "</div>";
        }
        ?>
    </div>
    <?php } ?>

    <?php if (!empty($b_parks)) { ?>
    <div class="row">
        <?php
        foreach ($b_parks as $park) {
            echo "<div class='park-yeri " . htmlspecialchars($park['durum']) . " " . htmlspecialchars($park['pozisyon']) . "' id='" . htmlspecialchars($park['park_id']) . "'>" . htmlspecialchars($park['park_id']) . "</div>";
        }
        ?>
    </div>
    <?php } ?>

    <?php if (!empty($c_parks)) { ?>
    <div class="row">
        <?php
        foreach ($c_parks as $park) {
            echo "<div class='park-yeri " . htmlspecialchars($park['durum']) . " " . htmlspecialchars($park['pozisyon']) . "' id='" . htmlspecialchars($park['park_id']) . "'>" . htmlspecialchars($park['park_id']) . "</div>";
        }
        ?>
    </div>
    <?php } ?>
    <?php if (!empty($d_parks)) { ?>
    <div class="row">
        <?php
        foreach ($d_parks as $park) {
            echo "<div class='park-yeri " . htmlspecialchars($park['durum']) . " " . htmlspecialchars($park['pozisyon']) . "' id='" . htmlspecialchars($park['park_id']) . "'>" . htmlspecialchars($park['park_id']) . "</div>";
        }
        ?>
    </div>
    <?php } ?>
    <?php if (!empty($e_parks)) { ?>
    <div class="row">
        <?php
        foreach ($e_parks as $park) {
            echo "<div class='park-yeri " . htmlspecialchars($park['durum']) . " " . htmlspecialchars($park['pozisyon']) . "' id='" . htmlspecialchars($park['park_id']) . "'>" . htmlspecialchars($park['park_id']) . "</div>";
        }
        ?>
    </div>
    <?php } ?>
    <?php if (!empty($f_parks)) { ?>
    <div class="row">
        <?php
        foreach ($f_parks as $park) {
            echo "<div class='park-yeri " . htmlspecialchars($park['durum']) . " " . htmlspecialchars($park['pozisyon']) . "' id='" . htmlspecialchars($park['park_id']) . "'>" . htmlspecialchars($park['park_id']) . "</div>";
        }
        ?>
    </div>
    <?php } ?>
</div>
