<?php session_start();

require "utils.php";

if (isLogged()) {
	header("location: ../php/personale.php");
	exit();
}

function login() {
	try {
		if (empty($_POST) || !isset($_POST["username"]) || !isset($_POST["password"]))
			return false;

		if (strlen($_POST["username"]) > 256 || strlen($_POST["password"]) > 32)
			return false;

		$user = filterInput($_POST["username"]);
		$pass = hash("sha256", $_POST["password"]);

		$conn = connectDB();
		$query = mysqli_prepare($conn, "SELECT * FROM USER WHERE email = ?");
		$query->bind_param("s", $user);
		$query->execute();
		$result = $query->get_result();
		$valid = false;
		if ($result->num_rows > 0) {
			if ($row = $result->fetch_assoc()) {
				if ($pass === $row["password"]) {
					$valid = true;
					$_SESSION["user"] = $user;
				}
			}
			$result->free();
		}
		$query->close();
		disconnectDB($conn);

		return $valid;

	} catch (Throwable $e) {
		erroreServer();
	}
}

$err = "";
if (!empty($_POST) && isset($_POST["submit"])) {
	$succ = login();
	if (!$succ) {
		$err = 	"<span role=\"alert\" class=\"error\">Credenziali non valide</span>";
	} else {
		if (isset($_GET["prod"])) {
			header("location: ../php/prodotto.php?prod=".$_GET["prod"]);
		} else {
			header("location: ../php/personale.php");
		}
		exit();
	}
}

$cont = file_get_contents("../html/login.html");
$cont = str_replace("###PLACE_HOLDER###", $err, $cont);

echo $cont;

?> 