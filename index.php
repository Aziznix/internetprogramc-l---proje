<?php

session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

include 'pages/header.php';
include 'pages/main.php';
include 'pages/footer.php';
?>
