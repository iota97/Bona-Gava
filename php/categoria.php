<?php session_start();

require "utils.php";

if (!isset($_GET["cat"])) {
	header("location: ../html/prodotti.html");
	exit();
}

$catname = "";
$cat = filterInput($_GET["cat"]);
switch ($cat) {
	case "1":
		$catname = "Cucine";
 	       break;
	case "2":
		$catname = "Zona Giorno";
		break;
	case "3":
		$catname = "Zone Notte";
 	       break;
	case "4":
		$catname = "Arredo bagno";
		break;
	case "5":
		$catname = "Salotti";
		break;
	case "6":
		$catname = "Complementi d'arredo";
		break;
	default:
		header("location: ../html/prodotti.html");
		exit();
		break;
}

$item = "<li>
         <img width=\"215\" height=\"123\" src=\"###T###\" alt=\"###A###\" />
         <h3>###N###</h3>
         <a href=\"../php/prodotto.php?prod=###I###\" aria-label=\"Scopri ###P###\">Scopri</a>
       </li>";

$item_list = "";
try {
	$conn = connectDB();
	$query = mysqli_prepare($conn, "SELECT * FROM PRODUCT WHERE cat = ?");
	$query->bind_param("s", $cat);
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

$cont = file_get_contents("../html/categoria.html");
$cont = str_replace("###CATNAME###", $catname, $cont);
$cont = str_replace("###VOID###", $item_list == "" ? "<p>Nessun prodotto corrisponde alla categoria selezionata</p>" : "", $cont);
$cont = str_replace("###LISTA###", $item_list, $cont);

echo $cont;
?>