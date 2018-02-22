<!-- do.commande.php -->

<?php

include "session.php";

// -------------------- PROGRAMME --------------------

// ----- Treatment -----
// init redirection to false
$redirect = false;
// parameterControl
if (parameterControl()){
	// Enregistrer le fichier
	$xml = recordInFile();
	// Recuperer les coordoonees pour centrer la carte
	$coord = getCoord(); 
	echo("coord: " . $coord[0] . " ". $coord[1]);
	// Rediriger
	$redirect = true;
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
		global $Error, $Redirect;
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
	// Close
	curl_close($connexionFile);
	// To string
	$xml = simplexml_load_file($outputFile);
	// return
	return $xml;
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
	// Close curl connexion
	curl_close($connexion);
	// To string
	$xml = simplexml_load_string($data);
	// latitude
	$lat = $xml->result->geometry->location->lat;
	// longitude
	$lng = $xml->result->geometry->location->lng;
	// table to return
	$coord = array($lat, $lng);
	return $coord;
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
		// default
		default:
		header("Location: commande.php?error=unknow");
		exit();
	}
}

// --------------------  --------------------

?>