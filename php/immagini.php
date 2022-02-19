<?php session_start();

require "utils.php";

if (!isAdmin()) {
	header("location: ../html/404.html");
	exit();
}

function inserisciImmagine() {	
	try {
		// ID
		if (!isset($_GET["prod"]))
			return "Errore: prodotto inesistente";

		$conn = connectDB();
		$id = filterInput($_GET["prod"]);
		$query = mysqli_prepare($conn, "SELECT * FROM PRODUCT WHERE id = ?");
		$query->bind_param("s", $id);
		$query->execute();
		$result = $query->get_result();
		if ($result->num_rows != 1) {
			$query->close();
			disconnectDB($conn);
			return "Errore: prodotto inesistente";
		}
		$result->free();
		$query->close();
		disconnectDB($conn);

		// Immagine
		$alt = isset($_POST["alt"]) ? filterInput($_POST["alt"]) : "";
		if ($alt == "") {
			return "Errore: l'immagine deve avere un alt";
		}
		if (strlen(filterInput($banner_alt)) > 32) {
			return "Errore: lunghezza massima per l'alt è 32 caratteri";
		}

		$imageFileType = strtolower(pathinfo(basename($_FILES["img"]["name"]), PATHINFO_EXTENSION));

		if (!file_exists($_FILES["img"]["tmp_name"][0])) 
			return "Errore: selezionare un'immagine";

		if (getimagesize($_FILES["img"]["tmp_name"]) === false)
			return "Errore, il <span lang=en>file</span> non è un'immagine";

		if ($_FILES["img"]["size"] > 2000000)
			return "Errore: l'immagine è troppo grande";

		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" )
			return "Errore: l'immagine non è di tipo <abbr lang=\"en\" title=\"Portable Network Graphics\">png</abbr>, <abbr lang=\"en\" title=\"Joint Photographic Experts Group\">jpg</abbr> o <abbr lang=\"en\" title=\"Graphics Interchange Format\">gif</abbr>";

		$img = "";
		$target_dir = "../uploads/";
		$target_file = $target_dir . basename($_FILES["img"]["name"]);
		$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

		do {
			$target_file = $target_dir . generateRandomString() . "." . $imageFileType;
		} while (file_exists($target_file));

		if (move_uploaded_file($_FILES["img"]["tmp_name"], $target_file)) {
			$img = $target_file;
		} else {
			return "Errore: immagine non caricata";
		}

		// Inserimento database
		$conn = connectDB();
		$query = mysqli_prepare($conn,
			"INSERT INTO IMAGE (path, alt, prod) VALUES (?, ?, ?)"
		);
		$query->bind_param("sss", $img, $alt, $id);
		$query->execute();
		$query->close();
		disconnectDB($conn);

		// Resize immagini
		resizeImage($img, 301, 172);

		$_SESSION["status"] = 2;
		header("location: ../php/immagini.php?prod=". $id);
		exit();
	} catch (Throwable $e) {
		erroreServer();
	}
}

$err = "";
if (!empty($_POST) && isset($_POST["inserisci"])) {
	$err = inserisciImmagine();
}

// Rimozione immagini
try {
	$id = isset($_GET["prod"]) ? filterInput($_GET["prod"]) : "";
	$rimossa = false;

	$conn = connectDB();
	$query = mysqli_prepare($conn, "SELECT * FROM IMAGE WHERE prod = ?");
	$query->bind_param("s", $id);
	$query->execute();
	$result = $query->get_result();
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			if (isset($_POST["rem".$row["id"]])) {

				// Eliminazione immagine
				$query2 = mysqli_prepare($conn,
					"SELECT * FROM IMAGE WHERE id = ?"
				);
				$query2->bind_param("s", $row["id"]);
				$query2->execute();
				$result2 = $query2->get_result();
				if ($result2->num_rows > 0) {
					if ($row2 = $result2->fetch_assoc()) {
						unlink($row2["path"]);
					}
				}
				$query2->close();

				// Eliminazione Database
				$query2 = mysqli_prepare($conn,
					"DELETE FROM IMAGE WHERE id = ?"
				);
				$query2->bind_param("s", $row["id"]);
				$query2->execute();
				$query2->close();

				$rimossa = true;
			}
		}
		$result->free();
	}
	$query->close();
	disconnectDB($conn);

	if ($rimossa) {
		$_SESSION["status"] = 3;
		header("location: ../php/immagini.php?prod=". $id);
		exit();
	}
} catch (Throwable $e) {
	erroreServer();
}

$rem = "<ul class=\"imgCard\">\n###IMG###\n</ul>\n";
$img = "<li>
		<form action=\"#\" method=\"post\">
			<img id=\"IMG###I###\" src=\"###P###\" width=\"215\" height=\"123\" alt=\"Rimuovi immagine con alt: ###A###\" />
			<input aria-labelledby=\"IMG###I###\" type=\"submit\" name=\"rem###I###\" value=\"Rimuovi\" />
		</form>\n
	</li>";
$img_list = "";

try {
	$conn = connectDB();
	$query = mysqli_prepare($conn, "SELECT * FROM IMAGE WHERE prod = ?");
	$id = filterInput(isset($_GET["prod"]) ? filterInput($_GET["prod"]) : "");
	$query->bind_param("s", $id);
	$query->execute();
	$result = $query->get_result();
	if ($result->num_rows > 0) {
		$i = 0;
		while ($row = $result->fetch_assoc()) {
			$i++;
			$tmp = str_replace("###N###", $i, $img);
			$tmp = str_replace("###I###", $row["id"], $tmp);
			$tmp = str_replace("###P###", $row["path"], $tmp);
			$tmp = str_replace("###A###", $row["alt"], $tmp);
			$img_list = $img_list . $tmp;
		}
		$result->free();
	}
	$query->close();
	disconnectDB($conn);
} catch (Throwable $e) {
	erroreServer();
}

$stat = "";
if (isset($_SESSION["status"])) {
	if ($_SESSION["status"] == 1)
		$stat = "Prodotto inserito, se vuoi puoi aggiungere altre immagini";
	else if ($_SESSION["status"] == 2)
		$stat = "Immagine aggiunta con successo";
	else if ($_SESSION["status"] == 3)
		$stat = "Immagine eliminata con successo";

	unset($_SESSION["status"]);
}
if ($err != "")
	$stat = "";

$cont = file_get_contents("../html/immagini.html");
$cont = str_replace("###REMOVE###", $img_list != "" ? $rem : "", $cont);
$cont = str_replace("###IMG###", $img_list, $cont);
$cont = str_replace("###ERR###", $err == "" ? "" : "<p role=\"alert\" class=\"error\">".$err."</p>", $cont);
$cont = str_replace("###STAT###", $stat == "" ? "" : "<p role=\"alert\" class=\"success\">".$stat."</p>", $cont);
$cont = str_replace("###ID###", isset($_GET["prod"]) ? filterInput($_GET["prod"]) : "", $cont);

echo $cont;

?>