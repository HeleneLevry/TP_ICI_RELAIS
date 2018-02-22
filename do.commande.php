<!-- do.commande.php -->

<?php

// -------------------- PROGRAMME --------------------

// ----- Treatment -----

$dateFrom =date("d/m/Y", strtotime($_POST['date_from']));
// output declaration
	$outputFile = "./output.xml";
	// open file
	$fp = fopen($outputFile, "w");
	// init connection
	$connexionFile = curl_init();
	// setOPT
	curl_setopt($connexionFile, CURLOPT_FILE, $fp);
	curl_setopt($connexionFile, CURLOPT_HTTPGET, true);
	curl_setopt($connexionFile, CURLOPT_URL, "http://exapaq.pickup-services.com/Exapaq/mypudofull.asmx/GetPudoList?address=".$_POST['address']."&zipCode=".$_POST['ZIPCode']."&city=".$_POST['city']."&request_id=".time()."&date_from=".$dateFrom);
	// Exec
	curl_exec($connexionFile);
	// Close
	curl_close($connexionFile);
	echo("File created: <a href='.\output.html'> 
					file </a>");


	// init()
	$connexion = curl_init();
	//opt
	curl_setopt($connexion, CURLOPT_URL, "/Exapaq/mypudofull.asmx/GetPudoList?address=".$_POST['address']."&zipCode=".$_POST['ZIPCode']."&city=".$_POST['city']."&request_id=".time()."&date_from=".$_POST['date_from']);
	curl_setopt($connexion, CURLOPT_HTTPGET, true);
	curl_setopt($connexion, CURLOPT_RETURNTRANSFER, true);
	// get in var
	$data = curl_exec($connexion);
	// close connexion
	curl_close($connexion);
	echo("data: ". $data);
	echo(time());
	echo($_POST['address']." ".$_POST['ZIPCode']." ".$_POST['city']." ".time()." ".$dateFrom);
// Envoyer la requete au serveur

// Enregistrer le fichier

// Utiliser le flux XML pour creation d'un tableau

// ----- Redirection -----

// -------------------- FUNCTIONS --------------------

// ----- Treatment -----

// ----- Redirection -----


?>