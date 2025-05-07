<?php
include 'config.php';

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
