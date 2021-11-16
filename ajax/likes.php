<?php
include_once("../config/database.php");
include_once("../inc/functions.php"); // Utilizziamo la funzioncina has_voted

session_start();

if (isset($_SESSION) && isset($_SESSION["uid"])) { // Sempre se si tratta di un utente

    if(isset($_POST["punteggio"]) && isset($_POST["pid"])) {

    if(!has_voted($_SESSION['uid'], $_POST["pid"])) {
			//Se non è stato già votato il post vado ad aggiornare il campo "punteggio" nella tabella post
            try {
                $sql = "UPDATE post SET punteggio=:punteggio WHERE pid=:pid";
                $s = $pdo->prepare($sql);
                $s->bindValue(":punteggio", $_POST["punteggio"]);
                $s->bindValue(":pid", $_POST["pid"]);
                $s->execute();
            } catch (PDOException $e) {
                echo $e->getMessage();
                exit();
            }
			//La query inserisce la coppia utente-post in modo da non permettergli più di votare
            try {
                $uid = trim(htmlspecialchars($_SESSION["uid"]));
                $pid = trim(htmlspecialchars($_POST["pid"]));
                $sql = "INSERT INTO vota (uid, pid) 
                SELECT " . $uid . "," . $pid . "
                WHERE NOT EXISTS (Select uid, pid From vota WHERE uid=" . $uid . " AND pid=" . $pid . ") LIMIT 1;";
                $s = $pdo->prepare($sql);
                //$s->bindValue(":uid", $uid);
                //$s->bindValue(":bid", $blog);
                $s->execute();
            } catch (PDOException $e) {

                echo $e->getMessage();
                exit();
            }

    } else {

        echo "0"; // Hai votato, comunico ad ajax di fermarsi e mostrare errore
        exit();
    }

 } else {
    echo "Non puoi raggiungere questo link direttamente!";
    exit();
}

} else {
    echo "Non puoi raggiungere questo link direttamente e non sei registrato!";
    exit();
}