<?php session_start();

require "utils.php";

if (!isLogged()) {
	header("location: ../php/login.php");
	exit();
}

$gest = isAdmin() ? "<li><a href=\"admin.php\" id=\"manage\">Gestione&nbsp;prodotti</a></li>" : "";
$cont = file_get_contents("../html/personale.html");
$cont = str_replace("###PLACE_HOLDER###", getUserName(), $cont);
$cont = str_replace("###GESTIONE###", $gest, $cont);

echo $cont;

?>