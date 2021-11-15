<?php

 /* @view: ./templates/u_profile.html.php */ 


// Nel profilo utente vedrò ?id=x, la x viene chiamata da $_GET["id"], il valore viene opportunatamente sanificato 
$uid = trim(htmlspecialchars($_GET['id']));

if (isset($uid) && !is_nan($uid)) {  // Se effettivamente sono in un profilo utente, vincolo di cui sopra..

	include_once "./config/database.php";  // ..includo PDO 
	try {

		$query = "SELECT * FROM utenti WHERE uid=:uid";    // ..ottengo quel determinato utente

		$check = $pdo->prepare($query);
		$check->bindParam(":uid", $uid);
		$check->execute();
	} catch (PDOException $e) {

		echo "" . $e->getMessage() . "";
		exit();
	}

	$row = $check->fetch();  // ..restituisco i risultati


	$user = array(     // ..inizializzo un vettore con i risultati della query
		"nome" => $row['nome'], "cognome" => $row['cognome'], "username" => $row['username'], "tel" => $row['tel'],
		"bio" => $row['bio'], "email" => $row["email"], "p_foto" => $row["p_foto"]
	); 


	try {

		$query = "SELECT b.nome, b.id FROM blog as b, utenti as u WHERE b.idautore=u.uid AND u.uid=:id";  // ..ottengo i blog dell'utente
		$result = $pdo->prepare($query);
		$result->bindParam(":id", $uid);
		$result->execute();
	} catch (PDOException $e) {

		echo "" . $e->getMessage() . "";
		exit();
	}


	foreach ($result as $row) {   // Per ogni risultato creo un vettore multidimensionale
		$blogs[] = array("id" => $row["id"], "nome" => $row["nome"]);
	}

	//Query per generare il punteggio degli utenti (somma il punteggio di tutti i post.)
	try {

		$query = "SELECT SUM(punteggio) as tot FROM post WHERE authid=:id";
		$result = $pdo->prepare($query);
		$result->bindParam(":id", $uid);
		$result->execute();
	} catch (PDOException $e) {

		echo "" . $e->getMessage() . "";
		exit();
	}
	$row = $result->fetch();  
	if ($row['tot'] == '') {   //Se l'utente non ha punteggio, è 0
		$punteggio = 0;
	} else {
		$punteggio = $row['tot'];  // Altrimenti glielo assegno
	}


	include 'templates/u_profile.html.php';   // ..infine include il template del profilo utente
	exit();  // Dopo l'inclusione, esco quindi dallo script in corso
}