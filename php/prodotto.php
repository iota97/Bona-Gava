<?php session_start();

require "utils.php";

if (!isset($_GET["prod"])) {
	header("location: ../html/prodotti.html");
	exit();
}

$cat = "";
$name = "";
$desc = "";
$banner = "";
$banner_alt = "";
$img_list = "";
$wish = false;
$id = filterInput($_GET["prod"]);

$item = "<li><img src=\"###P###\" alt=\"###A###\" class=\"prod_smallimg\" width=\"301\" height=\"172\" /></li>";
try {
	$conn = connectDB();
	$query = mysqli_prepare($conn, "SELECT * FROM PRODUCT WHERE id = ?");
	$query->bind_param("s", $id);
	$query->execute();
	$result = $query->get_result();
	if ($result->num_rows > 0) {
		if ($row = $result->fetch_assoc()) {
			$cat = $row["cat"];
			$name = $row["nome"];
			$desc = $row["descrizione"];
			$banner = $row["banner"];
			$banner_alt = $row["banner_alt"];
		}
		$result->free();
	} else {
		$query->close();
		disconnectDB($conn);
		header("location: ../html/prodotti.html");
		exit();
	}
	$query->close();

	$query = mysqli_prepare($conn, "SELECT * FROM IMAGE WHERE prod = ?");
	$query->bind_param("s", $id);
	$query->execute();
	$result = $query->get_result();
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$i = $item;
			$i = str_replace("###A###", $row["alt"], $i);
			$i = str_replace("###P###", $row["path"], $i);
			$img_list = $img_list . $i;
		}
		$result->free();
	}
	$query->close();

	$query = mysqli_prepare($conn, "SELECT * FROM WISHLIST WHERE prod = ? AND user=?");
	$tmp = getUserName();
	$query->bind_param("ss", $id, $tmp);
	$query->execute();
	$result = $query->get_result();
	if ($result->num_rows > 0) {
		$wish = true;
		$result->free();
	}
	$query->close();

	disconnectDB($conn);
} catch (Throwable $e) {
	erroreServer();
}

function ToggleWish() {
	$id = filterInput($_GET["prod"]);
	if (!isLogged()) {
		header("location: ../php/login.php?prod=".$id);
		exit();
	}
	try {
		global $wish;

		$conn = connectDB();
		$query_str = "INSERT INTO WISHLIST (user, prod) VALUES (?, ?)";
		if ($wish) {
			$query_str = "DELETE FROM WISHLIST WHERE user=? AND prod=?";
		}
		$query = mysqli_prepare($conn, $query_str);
		$tmp = getUserName();
		$query->bind_param("ss", $tmp, $id);
		$query->execute();
		$query->close();
		disconnectDB($conn);

		$_SESSION["status"] = $wish ? 1 : 2;
		header("location: ../php/prodotto.php?prod=". $id);
		exit();
	} catch (Throwable $e) {
		erroreServer();
	}
}

$catname = "Complementi d'arredo";
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
}

if (!empty($_POST) && isset($_POST["wish"])) {
	ToggleWish();
}

$stat = "";
if (isset($_SESSION["status"])) {
	if ($_SESSION["status"] == 1)
		$stat = "<div role=\"alert\" class=\"success\"><p>Prodotto rimosso dalla <span lang=\"en\">wishlist</span></p></div>";
	else if ($_SESSION["status"] == 2)
		$stat = "<div role=\"alert\" class=\"success\"><p>Prodotto aggiunto alla <span lang=\"en\">wishlist!</span></p></div>";
	unset($_SESSION["status"]);
}

$bread = "<p>Ti trovi in: <a href=\"../index.html\" lang=\"en\">Home</a> &gt; &gt; <a href=\"../html/prodotti.html\">Prodotti</a> &gt; &gt; <a href=\"../php/categoria.php?cat=###CAT###\">###CATNAME###</a> &gt; &gt; ###NAME_ENG###</p>";
if (isset($_GET["w"]) && $_GET["w"] == "1")
	$bread = "<p>Ti trovi in: <a href=\"../index.html\" lang=\"en\">Home</a> &gt; &gt; <a href=\"../php/personale.php\">Area personale</a> &gt; &gt; <a href=\"../php/wish.php\" lang=\"en\">Wishlist</a> &gt; &gt; ###NAME_ENG###</p>";

$cont = file_get_contents("../html/prodotto.html");
$cont = str_replace("###BREAD###", $bread, $cont);
$cont = str_replace("###CATNAME###", $catname, $cont);
$cont = str_replace("###CAT###", $cat, $cont);
$cont = str_replace("###NAME###", strip_tags($name), $cont);
$cont = str_replace("###NAME_ENG###", $name, $cont);
$cont = str_replace("###DESCRIZIONE###", $desc, $cont);
$cont = str_replace("###BANNER###", $banner, $cont);
$cont = str_replace("###BANNER_ALT###", $banner_alt, $cont);
$cont = str_replace("###LISTA###", $img_list, $cont);
$cont = str_replace("###WISH_STATUS###", $stat, $cont);
$cont = str_replace("###WISH###", $wish ? "Rimuovi dalla wishlist" : "Salva nella wishlist", $cont);
$cont = str_replace("###WISH_LABEL###", $wish ? "Premi qui per rimuovere dalla wishlist" : "Premi qui per salvare nella wishlist", $cont);

echo $cont;
?>