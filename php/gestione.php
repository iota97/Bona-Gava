<?php session_start();

require "utils.php";

if (!isAdmin()) {
	header("location: ../html/404.html");
	exit();
}

function prodotto($add) {
	try {
		// Categoria
		if (!isset($_POST["cat"]))
			return "Errore: inserire la categoria";
		$cat = filterInput($_POST["cat"]);

		if ($cat != "1" && $cat != "2" && $cat != "3" && $cat != "4" && $cat != "5" && $cat != "6")
			return "Errore: categoria non valida";

		// Titolo
		if (!isset($_POST["nome"]) || trim($_POST["nome"]) == "")
			return "Errore: inserire il nome prodotto";
		if (strlen(adminTag(filterInput($_POST["nome"]))) > 64)
			return "Errore: il nome prodotto può essere al massimo 64 caratteri";

		$nome = adminTag(filterInput($_POST["nome"]));

		// Descrizione
		if (!isset($_POST["descrizione"]) || trim($_POST["descrizione"]) == "")
			return "Errore: inserire  la descrizione prodotto";
		if (isset($_POST["descrizione"]) && strlen(adminTag(filterInput($_POST["descrizione"]))) > 5000)
			return "Errore: la descrizione può essere al massimo 5000 caratteri";

		$descrizione = adminTag(filterInput($_POST["descrizione"]));
		
		// Banner
		$banner_alt = isset($_POST["banner_alt"]) ? filterInput($_POST["banner_alt"]) : "";
		if (strlen(filterInput($banner_alt)) > 32) {
			return "Errore: la lunghezza massima per l'alt del <span lang=en>banner</span> è 32 caratteri";
		}

		$imageFileType = strtolower(pathinfo(basename($_FILES["banner"]["name"]), PATHINFO_EXTENSION));
		$skip_banner = false;
		if (!file_exists($_FILES["banner"]["tmp_name"][0])) {
			if ($add) {
				return "Errore: il <span lang=en>banner</span> è necessario";
			} else {
				$skip_banner = true;
			}
		}

		if ($banner_alt == "")
				return "Errore: il <span lang=en>banner</span> deve avere un alt";
		if (!$skip_banner) {
			if (getimagesize($_FILES["banner"]["tmp_name"]) === false)
				return "Errore: il <span lang=en>banner</span> non è un'immagine";

			if (file_exists($_FILES["banner"]["tmp_name"][0]) && $_FILES["banner"]["size"] > 2000000)
				return "Errore: il <span lang=en>banner</span> è troppo grande";

			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" )
				return "Errore: il <span lang=en>banner</span> non è di tipo <abbr lang=\"en\" title=\"Portable Network Graphics\">png</abbr>, <abbr lang=\"en\" title=\"Joint Photographic Experts Group\">jpg</abbr> o <abbr lang=\"en\" title=\"Graphics Interchange Format\">gif</abbr>";

			$banner = "";
			$thumb = "";
			$target_dir = "../uploads/";
			$target_file = $target_dir . basename($_FILES["banner"]["name"]);
			$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

			do {
				$target_file = $target_dir . generateRandomString() . "." . $imageFileType;
			} while (file_exists($target_file));

			do {
				$thumb = $target_dir . generateRandomString() . "." . $imageFileType;
			} while (file_exists($thumb));

			if (move_uploaded_file($_FILES["banner"]["tmp_name"], $target_file)) {
				$banner = $target_file;
			} else {
				return "Errore: <span lang=en>banner</span> non caricato";
			}
		}

		// Inserimento database
		$conn = connectDB();
		if ($add) {
			$query = mysqli_prepare($conn,
				"INSERT INTO PRODUCT (nome, cat, descrizione, banner, banner_alt, thumb) VALUES (?, ?, ?, ?, ?, ?)"
			);
			$query->bind_param("ssssss", $nome, $cat, $descrizione, $banner, $banner_alt, $thumb);
			$query->execute();
			$id = $conn->insert_id;
			$query->close();
		} else if ($skip_banner) {
			$id = filterInput($_GET["prod"]);
			$query = mysqli_prepare($conn,
				"UPDATE PRODUCT SET nome=?, cat=?, descrizione=?, banner_alt=? WHERE id=?"
			);
			$query->bind_param("sssss", $nome, $cat, $descrizione, $banner_alt, $id);
			$query->execute();
			$query->close();
		} else  {
			$id = filterInput($_GET["prod"]);
			$query = mysqli_prepare($conn,
				"UPDATE PRODUCT SET nome=?, cat=?, descrizione=?, banner=?, banner_alt=?, thumb=? WHERE id=?"
			);
			$query->bind_param("sssssss", $nome, $cat, $descrizione, $banner, $banner_alt, $thumb, $id);
			$query->execute();
			$query->close();
		}
		disconnectDB($conn);

		// Resize immagini
		if (!$skip_banner) {
			resizeImage($banner, 1850, 700);
			copy($banner, $thumb);
			$thumb = resizeImage($thumb, 215, 123);
		}

		if ($add) {
			$_SESSION["status"] = 1;
			header("location: ../php/immagini.php?prod=". $id);
			exit();
		} else {
			$_SESSION["status"] = 1;
			header("location: ../php/gestione.php?prod=". $id);
			exit();
		}

	} catch (Throwable $e) {
		erroreServer();
	}
}

function elimina() {
	try {
		$conn = connectDB();
		$id = isset($_GET["prod"]) ? filterInput($_GET["prod"]) : "";

		// Wishlist
		$query = mysqli_prepare($conn, "DELETE FROM WISHLIST WHERE prod=?");
		$query->bind_param("s", $id);
		if ($query->execute() !== true) {
			$query->close();
			disconnectDB($conn);
			return "Errore nell'eliminazione del prodotto";
		}
		$query->close();

		// Immagini
		$query = mysqli_prepare($conn, "SELECT * FROM IMAGE WHERE prod = ?");
		$query->bind_param("s", $id);
		$query->execute();
		$result = $query->get_result();
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				unlink($row["path"]);
			}
			$result->free();
		}
		$query->close();

		$query = mysqli_prepare($conn, "DELETE FROM IMAGE WHERE prod = ?");
		$query->bind_param("s", $id);
		if ($query->execute() !== true) {
			$query->close();
			disconnectDB($conn);
			return "Errore nell'eliminazione del prodotto";
		}
		$query->close();
		
		// Prodotto
		$query = mysqli_prepare($conn, "SELECT * FROM PRODUCT WHERE id = ?");
		$query->bind_param("s", $id);
		$query->execute();
		$result = $query->get_result();
		if ($result->num_rows > 0) {
			if ($row = $result->fetch_assoc()) {
				unlink($row["banner"]);
				unlink($row["thumb"]);
			}
			$result->free();
		}
		$query->close();

		$query = mysqli_prepare($conn, "DELETE FROM PRODUCT WHERE id = ?");
		$query->bind_param("s", $id);
		if ($query->execute() !== true) {
			$query->close();
			disconnectDB($conn);
			return "Errore nell'eliminazione del prodotto";
		}
		$query->close();
		disconnectDB($conn);

		header("location: ../php/admin.php");
		exit();
	} catch (Throwable $e) {
		erroreServer();
	}
}

$act = "Aggiungi";
$nome = "";
$cat = "";
$descrizione = "";
$banner = "";
$alt = "";
$id = isset($_GET["prod"]) ? filterInput($_GET["prod"]) : "";
try {
	$conn = connectDB();
	$query = mysqli_prepare($conn, "SELECT * FROM PRODUCT WHERE id = ?");
	$query->bind_param("s", $id);
	$query->execute();
	$result = $query->get_result();
	if ($result->num_rows > 0) {
		if ($row = $result->fetch_assoc()) {
			$act = "Modifica";
			$nome = "value=\"". $row["nome"] ."\"";
			$descrizione = $row["descrizione"];
			$banner = "data-not-required=\"true\"";
			$cat = $row["cat"];
			$alt = "value=\"". $row["banner_alt"] ."\"";
		}
		$result->free();
	}
	$query->close();
	disconnectDB($conn);
} catch (Throwable $e) {
	erroreServer();
}

$nome = adminTagReverse($nome);
$descrizione = adminTagReverse($descrizione);

$del = "";
if ($act == "Modifica") {
	$del = "<li><input type=\"button\" id=\"elimina\" value=\"Elimina prodotto\" onclick=\"showConfirm()\"/></li>";
}

$mod = "";
if ($act == "Modifica") {
	$mod = "<li><a href=\"../php/immagini.php?prod=". $id ."\">Modifica&nbsp;immagini</a></li>";
}

$err = "";
if (!empty($_POST) && isset($_POST["elimina"])) {
	$err = elimina();
}

if ($err == "" && !empty($_POST) && isset($_POST["submit"])) {
	$err = prodotto($act == "Aggiungi");
}

$stat = "";
if (isset($_SESSION["status"])) {
	if ($_SESSION["status"] == 1)
		$stat = "Prodotto modificato con successo";

	unset($_SESSION["status"]);
}

$cont = file_get_contents("../html/gestione.html");
$cont = str_replace("###NUOVO###", $act == "Aggiungi" ? "" : "nuovo ", $cont);
$cont = str_replace("###ERRORE###", $err == "" ? "" : "<p role=\"alert\" class=\"error\">".$err."</p>", $cont);
$cont = str_replace("###STAT###", $stat == "" ? "" : "<p role=\"alert\" class=\"success\">".$stat."</p>", $cont);
$cont = str_replace("###ACT###", $act, $cont);
$cont = str_replace("###NOME###", $nome, $cont);
$cont = str_replace("###DESCRIZIONE###", $descrizione, $cont);
$cont = str_replace("###BANNER###", $banner, $cont);
$cont = str_replace("###ALT###", $alt, $cont);
$cont = str_replace("###ELIMINA###", $del, $cont);
$cont = str_replace("###MOD###", $mod, $cont);
$cont = str_replace("###SUB###", $act == "Aggiungi" ? "Aggiungi prodotto" : "Applica modifiche", $cont);
$cont = str_replace("###CAT1###", ($cat == "1" ? "selected" : ""), $cont);
$cont = str_replace("###CAT2###", ($cat == "2" ? "selected" : ""), $cont);
$cont = str_replace("###CAT3###", ($cat == "3" ? "selected" : ""), $cont);
$cont = str_replace("###CAT4###", ($cat == "4" ? "selected" : ""), $cont);
$cont = str_replace("###CAT5###", ($cat == "5" ? "selected" : ""), $cont);
$cont = str_replace("###CAT6###", ($cat == "6" ? "selected" : ""), $cont);

echo $cont;

?>