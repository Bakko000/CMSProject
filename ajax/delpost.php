<?php

include '../config/database.php';
include '../inc/functions.php';


// E' necessario data la scarsa(nessuna) sicurezza degli input hidden, controllare nuovamente se l'utente corrente è davvero autore del post che intende eliminare o se è un tentativo di XSS

session_start();

if(is_auth($_SESSION['uid'], $_POST["blog_id"])) { // Se sei autore del blog corrente

if(isset($_POST["post_id"]) && isset($_POST["blog_id"])) { // E i parametri sono stati compilati

       $pid =  trim(htmlspecialchars($_POST["post_id"]));
       $bid = trim(htmlspecialchars($_POST["blog_id"]));
       //Query per l'eliminazione del post
       try {

            $sql = 'DELETE FROM post WHERE pid=:pid AND idblog=:bid';
            $s = $pdo->prepare($sql);
            $s->bindValue(":pid", $pid);
            $s->bindParam(':bid', $bid);
            $s->execute();
        } catch (PDOException $e) {

            echo "" . $e->getMessage() . "";
            exit();
        } 

        echo "Cancellazione effettuata con successo!";
        exit();
        
    }
} else {
  
    echo "0";  // Comunico ad AJAX di fermarsi e mostrare errore
    exit();

}