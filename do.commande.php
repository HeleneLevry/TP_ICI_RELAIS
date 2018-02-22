<!-- do.commande.php -->

<?php

include "session.php";

// -------------------- PROGRAMME --------------------

// ----- Treatment -----
$redirect = false;
if (parameterControl()){
	// Enregistrer le fichier
	recordInFile();
	// Envoyer la requete au serveur
	$xml = getData();
	// Utiliser le flux XML pour creation d'un tableau
	$table = createTable($xml);
	// Rediriger
	$redirect = true;
}
else{
	echo '<p>Some fields are missing</p>';
	exit();
}

// ----- Redirection ----
if ($redirect) {
	
	header("Location: resultat.php?table=".json_encode($table));
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
}
// getData();
function getData(){
	// URL
	$URL = "http://exapaq.pickup-services.com/Exapaq/mypudofull.asmx/GetPudoList?address=".$_POST['address']."&ZIPCode=".$_POST['ZIPCode']."&city=".$_POST['city']."&request_id=".time()."&date_from=".$_POST['date_from'];
	$URL = str_replace(" ", '%20', $URL);
	// init curl connexion
	$connexion = curl_init();
	// curl opt
	curl_setopt($connexion, CURLOPT_HTTPGET, true);
	curl_setopt($connexion, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	curl_setopt($connexion, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($connexion, CURLOPT_URL, $URL);
	// Execute curl 
	$data = curl_exec($connexion);
	// Close curl connexion
	curl_close($connexion);
	// To string
	$xml = simplexml_load_string($data);
	//return 
	return $xml;
}
// createTable
function createTable($xml){
	$i=0;
	$result= array();
	foreach ($xml->PUDO_ITEMS->PUDO_ITEM as $pudo_item) {
		$distance = $pudo_item->DISTANCE;
		$name = $pudo_item->NAME;
		$address1 = $pudo_item->ADDRESS1;
		$address2 = $pudo_item->ADDRESS2;
		$address3 = $pudo_item->ADDRESS3;
		$zipcode = $pudo_item->ZIPCODE;
		$city = $pudo_item->CITY;
		$longitude = $pudo_item->LONGITUDE;
		$latitude = $pudo_item->LATITUDE;
		$opening_hours_items = $pudo_item->OPENING_HOURS_ITEMS;
		$j=0;
		$opening_hours=array();
		foreach ($opening_hours_items->OPENING_HOURS_ITEM as $opening_hours_item) {
			$day_id = $opening_hours_item->DAY_ID;
			$start_tm = $opening_hours_item->START_TM;
			$end_tm = $opening_hours_item->END_TM;
			${'opening_hours'.$j} = array($day_id, $start_tm, $end_tm);
			array_push($opening_hours, ${'opening_hours'.$j});
			$j++;
		}
		
		${'table'.$i} = array($distance, $name, $address1, $address2, $address3, $zipcode, $city, $longitude, $latitude, $opening_hours);
		array_push($result, ${'table'.$i});
		$i++;
	}
	return $result;
}

// ----- Redirection -----

// --------------------  --------------------

?>