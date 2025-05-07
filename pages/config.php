<?php

define('DB_HOST', 'localhost');  
define('DB_USER', 'root');      
define('DB_PASS', '');          
define('DB_NAME', 'iguotopark'); 

error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Veritabanına bağlanılamadı: " . $conn->connect_error);
}


$conn->set_charset("utf8");

define('BASE_URL', 'http://localhost/internetprogramcılığıproje'); 
define('SITE_NAME', 'Gelişim Otopark');

?>