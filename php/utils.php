 <?php
error_reporting(0);

function erroreServer() {
	http_response_code(500);
	echo file_get_contents("../html/500.html");
	die();
}

function connectDB() {
	try {
		$servername = "localhost";
		$username = "gcocco";
		$password = "aiQueeTh2Ahn7eob";
		$dbname = "gcocco";

		// Per semplificare i login diversi tra i membri del gruppo, non usare in produzione
		if (file_exists("db.json")) {
			$db = json_decode(file_get_contents("db.json"));
			$servername = $db->servername;
			$username = $db->username;
			$password = $db->password;
			$dbname = $db->dbname;
		}

		$conn = new mysqli($servername, $username, $password, $dbname);

		if ($conn->connect_error) {
			erroreServer();
		} else {
 			mysqli_set_charset($conn, "utf8");
			return $conn;
		}
	} catch (Throwable $e) {
		erroreServer();
	}
}

function disconnectDB($conn) {
	mysqli_close($conn);
}

function filterInput($str) {
	$str = trim($str);
	$str = strip_tags($str);
 	$str = htmlspecialchars($str, ENT_QUOTES);

	return $str;
}

function getUserName() {
	return isset($_SESSION["user"]) ? $_SESSION["user"] : "utente sconosciuto";
}

function isLogged() {
	return isset($_SESSION["user"]);
}

function isAdmin() {
	return isset($_SESSION["user"]) && $_SESSION["user"] == "admin";
}

function generateRandomString($length = 10) {
    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}

function loadImage($filename) {
	if (!file_exists($filename)) {
		throw new InvalidArgumentException('File "'.$filename.'" not found.');
	}
	switch ( strtolower( pathinfo( $filename, PATHINFO_EXTENSION ))) {
	case 'jpeg':
	case 'jpg':
		return imagecreatefromjpeg($filename);
		break;

	case 'png':
		return imagecreatefrompng($filename);
		break;

	case 'gif':
		return imagecreatefromgif($filename);
		break;
	default:
		throw new InvalidArgumentException('File "'.$filename.'" is not valid jpg, png or gif image.');
		break;
	}
}

function saveImage($img, $filename) {
	switch (strtolower(pathinfo($filename, PATHINFO_EXTENSION ))) {
	case 'jpeg':
	case 'jpg':
		return imagejpeg($img, $filename);
		break;

	case 'png':
		return imagepng($img, $filename);
		break;

	case 'gif':
		return imagegif($img, $filename);
		break;
	default:
		throw new InvalidArgumentException('File "'.$filename.'" is not valid jpg, png or gif image.');
		break;
	}
}

function resizeImage($filename, $w, $h) {
	$thumb = imagecreatetruecolor($w, $h);
	$source = loadImage($filename);

	list($width, $height) = getimagesize($filename);
	if(($width/$w) > ($height/$h)) {
		$y = 0;
		$x = $width - ($height * $w/$h);
	} else {
		$x = 0;
		$y = $height - ($width * $h/$w);
	}
	imagecopyresampled($thumb, $source, 0, 0, $x/2, $y/2, $w, $h, $width - $x, $height - $y);
	saveImage($thumb, $filename);
}

function adminTag($cont) {
	$cont = str_replace("[en]", "<span lang=\"en\">", $cont);
	$cont = str_replace("[/en]", "</span>", $cont);

	return $cont;
}

function adminTagReverse($cont) {
	$cont = str_replace("</span>", "[/en]", $cont);
	$cont = str_replace("<span lang=\"en\">", "[en]", $cont);

	return $cont;
}

?> 