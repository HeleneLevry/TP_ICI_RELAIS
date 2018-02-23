<!-- do.commande.php -->

<?php

// -------------------- PROGRAMME --------------------

// ----- Treatment -----
// init redirection to false
$redirect = false;
// parameterControl
if (parameterControl()){
	// recordInFile
	if (recordInFile()){
		// Enregistrer le fichier
		$xml = recordInFile();
		// Recuperer les coordoonees pour centrer la carte
		// getCoord
		if (getCoord()){
			$coord = getCoord(); 
			echo("coord: " . $coord[0] . " ". $coord[1]);
			// Rediriger
			$redirect = true;
		// getCoord
		}
	// recordInFile
	}
// parameterControl
}

// ----- Redirection ----
if ($redirect) {
	header("Location: resultat.php?lat=".$coord[0]."&lng=".$coord[1]);
	exit();
}
elseif (isset($Error)) {
	redirectError();
}
else{
	echo('Issue to redirect');
	exit();
}

// --------------------  --------------------

// -------------------- FUNCTIONS --------------------

// ----- Treatment -----
// parameterControl
function parameterControl(){
	if ( isset($_POST["address"]) AND isset($_POST["ZIPCode"])  AND isset($_POST["city"])  AND isset($_POST["date_from"]) ) {
		global $dateFrom;
		$_POST['date_from'] =date("d/m/Y", strtotime($_POST['date_from']));
		return true;
	}
	else {
		global $Error, $redirect;
		$Redirect = false;
		$Error = 'missingArg';
		return false;
	}	
}
// recordInFile()
function recordInFile(){
	// URL
	$URL = "http://exapaq.pickup-services.com/Exapaq/mypudofull.asmx/GetPudoList?address=".$_POST['address']."&ZIPCode=".$_POST['ZIPCode']."&city=".$_POST['city']."&request_id=".time()."&date_from=".$_POST['date_from'];
	$URL = str_replace(" ", '%20', $URL);
	// output declaration
	$outputFile = "./output.xml";
	// open file
	$fp = fopen($outputFile, "w");
	// init connection
	$connexionFile = curl_init();
	// setOPT
	curl_setopt($connexionFile, CURLOPT_FILE, $fp);
	curl_setopt($connexionFile, CURLOPT_HTTPGET, true);
	curl_setopt($connexionFile, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	curl_setopt($connexionFile, CURLOPT_URL, $URL);
	// Exec
	curl_exec($connexionFile);
	// If curl error
	if (curl_errno($connexionFile) != 0){
		global $Error, $redirect;
		$redirect = false;
		$Error = 'errRecordInFile1';
		return false;
	}
	// Close
	curl_close($connexionFile);
	if (simplexml_load_file($outputFile)){
		// To string
		$xml = simplexml_load_file($outputFile);
		// return
		return $xml;
	}
	// if issue with simplexml_load_string
	else{
		global $Error, $redirect;
		$redirect = false;
		$Error = 'errRecordInFile2';
		return false;
	}
}
// getCoord();
function getCoord(){
	// URL parameters
	$param=$_POST['address']." ".$_POST['ZIPCode']." ".$_POST['city'];
	$param = str_replace (" ", "+", urlencode($param));
	// URL
	$URL = "https://maps.googleapis.com/maps/api/geocode/xml?address=".$param."&key=AIzaSyD8LZnDRxHPwBpGU3oiCaBekDgxjYNyCbw";//
	// init curl connexion
	$connexion = curl_init();
	// curl opt
	curl_setopt($connexion, CURLOPT_HTTPGET, true);
	curl_setopt($connexion, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	curl_setopt($connexion, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($connexion, CURLOPT_URL, $URL);
	curl_setopt($connexion, CURLOPT_SSL_VERIFYPEER, false);
	// Execute curl 
	$data = curl_exec($connexion);
	// If curl error
	if (curl_errno($connexion) != 0){
		global $Error, $redirect;
		//echo $e->getMessage();
		$redirect = false;
		$Error = 'errGetCoord1';
		return false;
	}
	// Close curl connexion
	curl_close($connexion);
	// To string
	if (simplexml_load_string($data)){
		$xml = simplexml_load_string($data);
		// latitude
		$lat = $xml->result->geometry->location->lat;
		// longitude
		$lng = $xml->result->geometry->location->lng;
		// table to return
		$coord = array($lat, $lng);
		return $coord;
	}
	// if issue with simplexml_load_string
	else{
		global $Error, $redirect;
		$redirect = false;
		$Error = 'errGetCoord2';
		return false;
	}
}

// ----- Redirection -----
// redirectError
function redirectError(){
	global $Error;
	switch($Error) {
    	// parameterControl
		case 'missingArg':
		header("Location: commande.php?error=missingArg");
		exit();
		break;
		// getCoord
		case 'errGetCoord1':
		header("Location: commande.php?error=errGetCoord1");
		exit();
		break;
		// getCoord
		case 'errGetCoord2':
		header("Location: commande.php?error=errGetCoord2");
		exit();
		break;
		// recordInFile
		case 'errRecordInFile1':
		header("Location: commande.php?error=errRecordInFile1");
		exit();
		break;
		// recordInFile
		case 'errRecordInFile2':
		header("Location: commande.php?error=errRecordInFile2");
		exit();
		break;
		// default
		default:
		header("Location: commande.php?error=unknow");
		exit();
	}
}

// --------------------  --------------------

?>