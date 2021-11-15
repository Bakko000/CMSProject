<? /* My Powerful Blog - 2022 (c)

 @authors Corrado Baccheschi & Vincenzo Sammartino
 @version PHP 7

 Il software è un CMS che consente di poter creare blog, post e commenti 
 Il CMS segue il MVC, abbiamo separato i templates che contengono stampe a video HTML, dai files PHP che si occupano di controllare il funzionamento del sistema
 Il CMS dispone perciò della cartella principale (l'attuale directory) con i file principali del programma scritti puramente in PHP, i quali richiamano spesso file locati in diverse cartelle

 /config -- e poi db.php -> File più importante dove risiede la connesione al DB e la conseguente creazione dell'oggetto PDO

 /inc E' la cartella dove sono presenti funzioni utili per i vari controlli 

 /templates E' la cartella dove sono presenti le stampe a video (echoing) dei codici HTML (templates, appunto) contenenti però talvolta piccoli frammenti e condizioni PHP (per questo motivo i files hanno un'estensione bivalente .html.php)

 /js E' la cartella con i codici JavaScript e JQuery, viene richiamata dai files in templates. Qui si trovano le chiamate AJAX.

 /ajax E' la cartella con le query SQL che rispondono alle chiamate AJAX (iniziate in /js)

/css In questa cartella è presente tutto il codice CSS dell'intero sistema

/img Cartella di immagini principali del sistema, alcune di default (per esempio la foto del profilo di un Utente)

/particomuni Contiene le parti che più volte vengono incluse nei file e che fanno parte della struttura visibile del DOM(esempio footer.php)

/elementicomuni Contiene gli elementi che più volte concorrono a costituire il DOM, nei termini tuttavia di inclusioni da link esterni (es jquery), css interno (da /css)


In ogni template abbiamo citato il rispettivo controller (@controller), in ogni controller il rispettivo template (@view).

********************************************************************************************************************************

@view ./templates/articoli.html.php

  */  ?>


<?php include_once './config/database.php'; ?>
<!-- Includo il file per connettersi al database -->

<html lang="it">

<head>
    <?php include_once './elementicomuni/headcomune.php'; ?>
    <title>My Powerful Blog</title>
</head>
<!-- Script relativo alla ricerca istantanea (Ajax) -->
<script src="../js/cerca.js">

</script>

<body>
    <?php include_once './particomuni/header.php'; ?>


    <!-- Includo i blog e la barra di ricerca -->
    <?php include_once './particomuni/listato.php';

	session_start();

	$g_action = trim(htmlspecialchars($_GET["action"]));

	if (!isset($_SESSION) || !isset($_SESSION['id']) && $g_action == "logout") {

		echo "<div class='alert alert-danger' role='alert'>Non puoi raggiungere questo link direttamente.</div>";
		exit();
	} elseif (isset($g_action) && $g_action == "logout") {

		unset($_SESSION['id']);
		unset($_SESSION['uid']);
		unset($_SESSION['user']);
		unset($_SESSION['email']);
		echo "<script> window.location.replace('login.php') </script>";
		exit();
	}

	//Qui recupero i post dal database in ordine di data dal più recente


	try {
		$query = "SELECT b.nome as nomeblog, b.id as blogid, p.pid, p.testo, p.nome as titolopost, p.foto1, p.font, p.foto2, p.data, p.authid, u.nome as unome, u.cognome as ucogn, u.username as nick, u.p_foto as authorfoto,  u.uid 
		FROM utenti as u, post as p, blog as b
		WHERE u.uid = p.authid AND
		p.idblog = b.id 
		ORDER BY p.data DESC
		LIMIT 0,6";
		$check = $pdo->prepare($query);
		$check->execute();
	} catch (PDOException $e) {

		echo "" . $e->getMessage() . "";
		exit();
	}

	foreach ($check as $row) {


		$posts[] = array(
			"pid" => $row['pid'], "testo" => $row['testo'], "foto1" => $row['foto1'], "foto2" => $row['foto2'],
			"data" => $row['data'], "authid" => $row["authid"], "authorid" => $row["uid"], "nomeauth" => $row["unome"], "cognauth" => $row["ucogn"],
			"authnick" => $row["nick"], "blog" => $row["nomeblog"], "idblog" => $row["blogid"], "titolo" => $row["titolopost"], "a_foto" => $row["authorfoto"], "font" => $row["font"]
		);
	}

	include './templates/articoli.html.php';