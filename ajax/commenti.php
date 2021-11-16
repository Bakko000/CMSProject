<?php
include_once('../config/database.php');
include_once('../inc/functions.php');
session_start();


$uid = $_SESSION['uid'];

// Parte relativa all'eliminazione del commento con AJAX

if (isset($_GET["delete"]) && isset($_POST["post_id"]) && isset($_POST["com_id"])) {

if (is_auth_comm($uid, $_POST["com_id"])) {

    $pid =  trim(htmlspecialchars($_POST["post_id"]));
    $cid = trim(htmlspecialchars($_POST["com_id"]));

    try {
        //Query per l'eliminazione del commento
        $sql = 'DELETE FROM commenti WHERE comid=:cid AND idpost=:pid';
        $s = $pdo->prepare($sql);
        $s->bindValue(":cid", $cid);
        $s->bindParam(':pid', $pid);
        $s->execute();
    } catch (PDOException $e) {

        echo "" . $e->getMessage() . "";
        exit();
    }

    echo "Cancellazione effettuata con successo!";
    exit();
} else {

    echo "0";
    exit();
}


// Parte relativa alla modifica del commento con AJAX

} elseif (isset($_GET["edit"]) && isset($_POST["post_id"]) && isset($_POST["com_id"]) && isset($_POST["testo"]) 
      && is_auth_comm($uid, $_POST["com_id"])) {


    $pid =  trim(htmlspecialchars($_POST["post_id"]));
    $cid = trim(htmlspecialchars($_POST["com_id"]));     // Controlli di sicurezza, sanificazione parametri
    $testo = trim(htmlspecialchars($_POST["testo"]));

    try {
        //Query per la modifica del commento
        $sql = 'UPDATE commenti SET testo=:testo WHERE comid=:cid AND idpost=:pid';
        $s = $pdo->prepare($sql);
        $s->bindValue(":cid", $cid);
        $s->bindParam(':pid', $pid);
        $s->bindParam(':testo', $testo);
        $s->execute();
    } catch (PDOException $e) {

        echo "" . $e->getMessage() . "";
        exit();
    }

    echo "Modifica effettuata con successo!";
    exit();
} elseif(!is_auth_comm($uid, $pid) && isset($uid)) {  // Se non è autore del commento, però è loggato mostra l'alert 
echo "0";
exit();
} else {
    include 'errore404.php';
    exit();
}