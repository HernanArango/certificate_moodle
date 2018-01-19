<?php 
require_once("../../config.php");
require_once("$CFG->dirroot/mod/certificateuv/lib/phpqrcode/qrlib.php");
$texto = $_GET['text'];
$prueba= QRcode::png($texto);
echo "<img src='$texto' />";
?>