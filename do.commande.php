<!-- do.commande.php -->

<?php

// -------------------- PROGRAMME --------------------

// ----- Treatment -----
getInFile();

// Envoyer la requete au serveur

// Enregistrer le fichier

// Utiliser le flux XML pour creation d'un tableau

// ----- Redirection -----

// -------------------- FUNCTIONS --------------------

// ----- Treatment -----
// ----- Treatment -----
// getInFile
function getInFile(){
	// output declaration
	$outputFile = "./output.html";
	// open file
	$fp = fopen($outputFile, "w");
	// init connection
	$connexionFile = curl_init();
	// setOPT
	curl_setopt($connexionFile, CURLOPT_FILE, $fp);
	curl_setopt($connexionFile, CURLOPT_URL, "http://exapaq.pickup-services.com/Exapaq/mypudofull.asmx?op=GetPudoList");
	// Exec
	curl_exec($connexionFile);
	// Close
	curl_close($connexionFile);
	echo("File created: <a href='.\output.html'> 
					file </a>");
}

// ----- Redirection -----


?>