<?php session_start();

require "utils.php";

if (!isAdmin()) {
	header("location: ../html/404.html");
	exit();
}

$cont = "<a href=\"gestione.php\">Aggiungi prodotto</a>";
$item = "<li>
         <img width=\"215\" height=\"123\" src=\"###T###\" alt=\"###A###\" />
         <h3>###N###</h3>
         <a href=\"../php/gestione.php?prod=###I###\" aria-label=\"Modifica ###P###\">Modifica</a>
         </li>";

$item_list = "";
try {
	$conn = connectDB();
	$query = mysqli_prepare($conn, "SELECT * FROM PRODUCT");
	$query->execute();
	$result = $query->get_result();
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$i = $item;
			$i = str_replace("###I###", $row["id"], $i);
			$i = str_replace("###T###", $row["thumb"], $i);
			$i = str_replace("###A###", $row["banner_alt"], $i);
			$i = str_replace("###N###", $row["nome"], $i);
			$i = str_replace("###P###", strip_tags($row["nome"]), $i);
			$item_list = $item_list . $i;
		}
		$result->free();
	}
	$query->close();
	disconnectDB($conn);
} catch (Throwable $e) {
	erroreServer();
}

$cont = file_get_contents("../html/admin.html");
$cont = str_replace("###LISTA###", $item_list, $cont);

echo $cont;

?>