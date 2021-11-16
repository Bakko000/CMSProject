<?php

include_once("../config/database.php");

session_start();

if (isset($_SESSION) && isset($_SESSION["uid"])) { // Se si è utenti
	//La query va ad aggiornare il database con le modifiche su testo e titolo fatte dall'utente
    try {
        $sql = "UPDATE post SET nome=:titolo, testo=:testo WHERE pid=:pid";
        $s = $pdo->prepare($sql);
        $s->bindValue(":titolo", htmlspecialchars($_POST["titolo"]));
        $s->bindValue(":testo", htmlspecialchars($_POST["testo"]));
        $s->bindValue(":pid", htmlspecialchars($_POST["pid"]));
        $s->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }
} else {
    echo "Tentativo fraudolento!";
    exit();
}