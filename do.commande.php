<!-- do.commande.php -->

<?php

// -------------------- PROGRAMME --------------------

// ----- Treatment -----

if (parameterControl()){
	// Enregistrer le fichier
	recordInFile();
	// Envoyer la requete au serveur
	$data = getData();
	echo $data;
}
else{
	echo '<p>Some fields are missing</p>';
	exit();
}


// Utiliser le flux XML pour creation d'un tableau

// ----- Redirection -----

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
	//return 
	return $data;
}

// ----- Redirection -----

// --------------------  --------------------

?>