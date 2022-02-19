<?php session_start();

require "utils.php";

function register() {
	try {
		if (empty($_POST) || !isset($_POST["username"]) || !isset($_POST["password"]) || !isset($_POST["password_rep"]))
			return "Compilare tutti i campi";

		if (strlen(filterInput($_POST["username"])) > 256)
			return "L'<span lang=\"en\">email</span> può essere lunga al massimo 256 caratteri";

		if (strlen($_POST["password"]) < 8)
			return "La <span lang=\"en\">password</span> deve essere lunga almeno 8 caratteri";

		if (strlen($_POST["password"]) > 32  || strlen($_POST["password_rep"]) > 32)
			return "La <span lang=\"en\">password</span> può essere lunga al massimo 32 caratteri";
		
		if (!filter_var(trim($_POST["username"]), FILTER_VALIDATE_EMAIL))
			return "<span lang=\"en\">Email</span> non valida";

		if ($_POST["password"] != $_POST["password_rep"])
			return "Le <span lang=\"en\">password</span> non sono uguali";

		$user = filterInput($_POST["username"]);
		$pass = hash("sha256", $_POST["password"]);

		$conn = connectDB();
		$query = mysqli_prepare($conn, "SELECT * FROM USER WHERE email = ?");
		$query->bind_param("s", $user);
		$query->execute();
		$result = $query->get_result();
		if ($result->num_rows > 0) {
			$result->free();
			$query->close();
			disconnectDB($conn);
			return "Esiste già un utente registrato con questa <span lang=\"en\">email</span>";		
		}
		$query->close();

		$query = mysqli_prepare($conn, "INSERT INTO USER (email, password) VALUES (?, ?)");
		$query->bind_param("ss", $user, $pass);
		$query->execute();
		$query->close();
		disconnectDB($conn);

	} catch (Throwable $e) {
		erroreServer();
	}
}

$err = "";
if (!empty($_POST) && isset($_POST["submit"])) {
	$err = register();
	if ($err == "") {
		$err = "<p role=\"alert\" class=\"success\">Registrazione avvenuta con successo</p>";
	} else {
		$err = "<p role=\"alert\" class=\"error\">". $err . "</p>";
	}
}

$cont = str_replace("###PLACE_HOLDER###", $err, file_get_contents("../html/register.html"));

echo $cont;

?> 