<?php session_start();

require "utils.php";

function edit() {
	try {
		if (empty($_POST) || !isset($_POST["password_old"]) || !isset($_POST["password"]) || !isset($_POST["password_rep"]))
			return "Errore: compilare tutti i campi";

		if (strlen($_POST["password"]) < 8)
			return "Errore: la <span lang=\"en\">password</span> deve essere lunga al meno 8 caratteri";

		if (strlen($_POST["password"]) > 32  || strlen($_POST["password_rep"]) > 32)
			return "Errore: la <span lang=\"en\">password</span> può essere lunga al massimo 32 caratteri";

		if ($_POST["password"] != $_POST["password_rep"])
			return "Errore: le <span lang=\"en\">password</span> non sono uguali";

		$user = getUserName();
		$pass_old = hash("sha256", $_POST["password_old"]);
		$pass = hash("sha256", $_POST["password"]);

		$conn = connectDB();
		$query = mysqli_prepare($conn, "SELECT * FROM USER WHERE email = ?");
		$query->bind_param("s", $user);
		$query->execute();
		$result = $query->get_result();
		if ($result->num_rows > 0) {
			if ($row = $result->fetch_assoc()) {
				if ($pass_old === $row["password"]) {
					$query2 = mysqli_prepare($conn, "UPDATE USER SET password=? WHERE email=?");
					$query2->bind_param("ss", $pass, $user);
					$query2->execute();
					$query2->close();
				} else {
					$result->free();
					$query->close();
					disconnectDB($conn);
					return "<span lang=\"en\">Password</span> errata";
				}
			}
			$result->free();	
		}
		$query->close();
		disconnectDB($conn);

		return "";
	} catch (Throwable $e) {
		echo $e->getMessage(); die();
		erroreServer();
	}
}

$err = "";
if (!empty($_POST) && isset($_POST["submit"])) {
	$err = edit();
	if ($err == "") {
		$err = "<p role=\"alert\" class=\"success\"><span lang=\"en\">Password</span> modificata con successo</p>";
	} else {
		$err = "<p role=\"alert\" class=\"error\">". $err . "</p>";
	}
}

$cont = str_replace("###PLACE_HOLDER###", $err, file_get_contents("../html/edit.html"));
$cont = str_replace("###USER###", getUserName(), $cont);

echo $cont;

?> 