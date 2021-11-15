<?php

// @view: ./templates/post.html.php

include_once('./config/database.php');
include_once('./inc/functions.php');
session_start();

$pid = trim(htmlspecialchars($_POST["pid"]));
$uid = $_SESSION['uid'];

if(isset($uid)) {  // Controllo se l'utente è loggato 

//Controllo che tutti i parametri siano correttamente impostati per inviare il nuovo commento
if (isset($_GET["nuovocommento"]) && isset($_POST["submit"]) && $_POST["submit"] == "Commenta") { //Invia le nuove righe al server che le aggiunge al database.

    // Controlli di sicurezza per SQL injection
    $testo = trim(htmlspecialchars($_POST["testo"]));
    try {
        //Query relativa all'inserimento del commento nel database
        $sql = 'INSERT INTO commenti (testo, idauth, idpost) VALUES (:testo, :id, :pid)';
        $s = $pdo->prepare($sql);
        $s->bindValue(":testo", $testo);
        $s->bindValue(":id", $uid);
        $s->bindParam(':pid', $pid);
        $s->execute();
    } catch (PDOException $e) {

        echo "" . $e->getMessage() . "";
        exit();
    }
    //Se l'inserimento va a buon fine si viene avvisati del corretto inserimento
    echo "<script> window.location.replace('post.php?action=viewpost&id=" . $pid . "') </script>";
    exit();



    // Parte relativa alla cancellazione del commento con AJAX

} /* elseif (isset($_GET["delete"]) && isset($_POST["post_id"]) && isset($_POST["com_id"])) {

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
} */ else { //Se l'utente prova a forzare i parametri lo avverto
echo "<div class='alert alert-danger mt-0 ml-3'>Non puoi accedere a questo link direttamente!</div>";
include 'errore404.php';
exit(); }

} else {
    include "errore404.php";
    exit();
}