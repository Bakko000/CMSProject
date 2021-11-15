<!-- In questo file si trova il codice PHP relativo al pannello "moderatore" -->
<!-- Questo pannello, più semplice dell'ACP, permette di gestire i coautori del proprio Blog, aggiungere ed eliminarli -->
<!-- @view ./templates/modcp.html.php (per la pagina di index) -->
<!-- @view ./templates/modcp-add.html.php (per la pagina di agggiunta) -->

<?php

include_once './config/database.php';
include_once './inc/functions.php';

session_start();

// E' evidente ci sia un tentativo di forzare il link, tentativo fraudolento od attacco, includi la pagina di errore ed esci
if(!isset($_GET["bid"]) && !isset($_GET["action"]) && !isset($_GET["delete"]) && !isset($_GET["add"])) {
    include 'errore404.php';
    exit();
}

else {   // Altrimenti se sei un utente registrato

if (isset($_SESSION) && isset($_SESSION["uid"]) && isset($_SESSION["user"])) {

    $uid = $_SESSION['uid'];

    if (isset($_GET["bid"])) {  // E sei nella pagina di moderazione di un blog 

        $bid = trim(htmlspecialchars($_GET["bid"]));

        if ($bid > 0 && is_auth($uid, $bid)) {   // Se l'id del blog è valido e sei autore

            try {    // Ti mostro i coautori del tuo blog

                $query = "SELECT coautid, username as nomecoauth FROM cogestisce, utenti WHERE coautid=uid AND idblog=:bid";
                $result = $pdo->prepare($query);
                $result->bindParam(':bid', $bid);
                $result->execute();
            } catch (PDOException $e) {

                echo "" . $e->getMessage() . "";
                exit();
            }

            $rows = $result->fetchAll();

            foreach ($rows as $row) {

                $users[] = array("id" => $row["coautid"], "nick" => $row["nomecoauth"]);
            }
        } else {  // Se non sei l'autore, stai forzando l'accesso quindi errore ed uscita
            echo '<div class="alert alert-danger" role="alert">Devi essere Autore del Blog per gestirne i CoAutori. <a href= "javascript:history.back(-1)" class="alert-link">Torna indietro</a></div>';
            include 'errore404.php';
            exit();
        }

        if (    // Se stai revocando il coautorato
            isset($_GET["delete"]) && is_numeric($_GET["delete"]) &&
            isset($_POST["submit"]) && $_POST["submit"] == "Revoca"
        ) {
            $uid = trim(htmlspecialchars($_GET["delete"]));
            $bid = trim(htmlspecialchars($_GET["bid"]));

            try {  // Invia le modifiche al db

                $sql = "DELETE FROM cogestisce WHERE coautid=:uid AND idblog=:bid";
                $s = $pdo->prepare($sql);
                $s->bindValue(":uid", $uid);
                $s->bindValue(":bid", $bid);
                $s->execute();
            } catch (PDOException $e) {

                echo $e->getMessage();
                exit();
            } //Mostro l'alert di corretto salvataggio delle modifiche.
            include './templates/modcp-success.html.php';   
            exit();
        } elseif (isset($_GET["delete"]) && !isset($_POST["submit"])) {  // Se stai forzando il link, errore ed esci
            include 'errore404.php';
            exit();
        }


        /// Aggiunta coautori
    } elseif (isset($_GET["add"])) {   // Se stai aggiungendo un coautore

        $bid = trim(htmlspecialchars($_GET["add"]));

        if(is_auth($_SESSION["uid"], $bid)) {  // Se sei autore

            try {

                $query = "SELECT uid, username FROM utenti WHERE uid!=:uid";
                $result = $pdo->prepare($query);
                $result->bindParam(':uid', $_SESSION['uid']);
                $result->execute();                          // Mostro gli utenti
            } catch (PDOException $e) {

                echo "" . $e->getMessage() . "";
                exit();
            }

            $rows = $result->fetchAll();   

            foreach ($rows as $row) {
 
                $users[] = array("id" => $row["uid"], "nick" => $row["username"]);
            }

            if (      // Se il link di aggiunta è valido 
                isset($_GET["action"]) && $_GET["action"]!="" && $_GET["action"]=="addcoauth" &&
                isset($_POST["submit"]) && $_POST["submit"] == "Aggiungi" 
            ) {
                $uid = trim(htmlspecialchars($_POST["coauth"]));
                $bid = trim(htmlspecialchars($_GET["add"]));

                if(isset($uid) && $uid!="notset") {
    
                try {
                        
                             //Inserisci utente come coautore
                    $sql = "INSERT INTO cogestisce (coautid, idblog)
                     SELECT " . $uid . "," . $bid . "
               WHERE NOT EXISTS (Select coautid, idblog From cogestisce WHERE coautid=" . $uid . " AND idblog=" . $bid . ") LIMIT 1;";
                    $s = $pdo->prepare($sql);
                    $s->execute();
                } catch (PDOException $e) {
    
                    echo $e->getMessage();
                    exit();
                }
                 
                  
                //Mostro l'alert di corretto salvataggio delle modifiche.
                include './templates/modcp-success.html.php';
                exit();

                } else {   // Non hai compilato tutti i campi 
                    echo '<div class="alert alert-danger" role="alert">Compila tutti i campi!<a href= "javascript:history.back(-1)" class="alert-link">Torna indietro</a></div>';
                    exit();
                } 
                       // Stai forzando il link accedendoci direttamente
            } elseif (isset($_GET["action"]) && !isset($_POST["submit"])) {
                include 'errore404.php';
                exit();
            }

        include './templates/modcp-add.html.php';   // Inclusione del template di aggiunta ed uscita dallo script
        exit();

        } else {    // Non sei autore del blog
            echo '<div class="alert alert-danger" role="alert">Devi essere Autore del Blog per gestirne i CoAutori. <a href= "javascript:history.back(-1)" class="alert-link">Torna indietro</a></div>';
            include 'errore404.php';
            exit();
        }
    }

    include './templates/modcp.html.php';    // Inclusione in ogni caso del template di index del modcp
    exit();
} else {
    include 'errore404.php';   //Infine se non sei loggato, errore e uscita 
    exit();
  }
}
