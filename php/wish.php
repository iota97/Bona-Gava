<?php session_start();

require "utils.php";

if (!isLogged()) {
	header("location: ../php/login.php");
	exit();
}

$item = "<li>
         <img width=\"215\" height=\"123\" src=\"###T###\" alt=\"###A###\" />
         <h3>###N###</h3>
         <a href=\"../php/prodotto.php?prod=###I###&w=1\" aria-label=\"Scopri ###P###\">Scopri</a>
         </li>";

$item_list = "";
try {
	$conn = connectDB();
	$query = mysqli_prepare($conn, "SELECT * FROM PRODUCT, WISHLIST WHERE prod=id AND user=?");
	$query->bind_param("s", getUserName());
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

$void = "";
if ($item_list == "") {
	$void = "<p>Nessun prodotto nella <span en=\"en\">wishlist</span></p>";
}

$cont = file_get_contents("../html/wish.html");
$cont = str_replace("###VUOTO###", $void, $cont);
$cont = str_replace("###LISTA###", $item_list, $cont);

echo $cont;
?>