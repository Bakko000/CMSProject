<!-- In questo file si trova il codice PHP relativo al pannello "amministratore" -->
<?php

include_once './config/database.php';
include_once './inc/functions.php';

session_start();
//Se l'utente è correttamente loggato
if (isset($_SESSION) && isset($_SESSION["uid"]) && isset($_SESSION["user"])) {

   $uid = htmlspecialchars($_SESSION['uid']);

  // Conta i blogs dell'utente corrente
  try {

    $query = "SELECT COUNT(*) as tot FROM blog as b WHERE idautore=:uid";
    $result = $pdo->prepare($query);
    $result->bindParam(":uid", $uid);
    $result->execute();
  } catch (PDOException $e) {

    echo "" . $e->getMessage() . "";
    exit();
  }

  $row = $result->fetch();
  $totblogs = $row["tot"];

  

  include './templates/acp_index.html.php';

  //In questa sezione vi è la parte relativa alla cancellazione dell'account
  if (isset($_GET["deleteacc"])) {

    trim(htmlspecialchars($_GET["deleteacc"]));

    include './templates/acp_cancellaaccount.html.php';

    if (isset($_POST["submit"]) && $_POST["submit"] == "Elimina") {
      try {
        //Query per l'eliminazione dell'utente (nel database è selezionato CASCADE dunque tutto ciò che è collegato all'utente [post, commenti, likes, blog ecc.] viene eliminato)
        $query = "DELETE FROM utenti WHERE uid=:uid";
        $result = $pdo->prepare($query);
        $result->bindParam(":uid", $uid);
        $result->execute();
      } catch (PDOException $e) {

        echo "" . $e->getMessage() . "";
        exit();
      }
      // Rimuovo tutte le variabili di sessione
      session_unset();

      // Distruggo la sessione
      session_destroy();

      //Dopo aver cancellato l'utente lo riporto alla pagina index
      echo "<script> window.location.replace('index.php') </script>";
      exit();
    } elseif (isset($_GET["deleteacc"]) && !isset($_POST["submit"])) {
      include 'errore404.php';
      exit();
    }
  }



  // Gestisco la pagina dei blog
  if (isset($_GET["blogs"])) {

    trim(htmlspecialchars($_GET["blogs"]));

    try {
      //Query per la selezione dei blog dell'utente per mostrarli nella pagina di amministrazione dei blog
      $query = "SELECT b.nome, b.id FROM blog as b, utenti as u WHERE b.idautore=u.uid AND u.uid=:id GROUP BY b.id";
      $result = $pdo->prepare($query);
      $result->bindParam(":id", $uid);
      $result->execute();
    } catch (PDOException $e) {

      echo "" . $e->getMessage() . "";
      exit();
    }

    while ($row = $result->fetch()) {

      $blogs[] = array("nome" => $row["nome"], "id" => $row['id']);
    }


    try {

      $query = "SELECT COUNT(*) as tot FROM blog as b, cogestisce as c WHERE b.id=c.idblog AND c.coautid = :uid";
      $result = $pdo->prepare($query);
      $result->bindParam(":uid", $_SESSION["uid"]);
      $result->execute();
    } catch (PDOException $e) {

      echo "" . $e->getMessage() . "";
      exit();
    }

    $row = $result->fetch();
    $totcoblogs = $row["tot"];


    try {
      //Query per mostrare i blog nella relativa sezione
      $query = "SELECT b.nome, b.id FROM blog as b, cogestisce as c WHERE b.id=c.idblog AND c.coautid = :uid";
      $result = $pdo->prepare($query);
      $result->bindParam(":uid", $_SESSION["uid"]);
      $result->execute();
    } catch (PDOException $e) {

      echo "" . $e->getMessage() . "";
      exit();
    }

    while ($row = $result->fetch()) {

      $coblogs[] = array("nome" => $row["nome"], "id" => $row['id']);
    }


    try {
      //Query per il conteggio dei blog (se non ce ne sono avverto l'utente con la possibilità di creazione di un nuovo blog)
      $query = "SELECT COUNT(*) AS totblogs, b.nome, b.id FROM blog as b, utenti as u WHERE b.idautore=u.uid AND u.uid=:id";
      $result = $pdo->prepare($query);
      $result->bindParam(":id", $uid);
      $result->execute();
    } catch (PDOException $e) {

      echo "" . $e->getMessage() . "";
      exit();
    }

    $row = $result->fetch();

    $totblogs = $row["totblogs"];




    if (isset($_GET["deleteblog"]) && is_numeric($_GET["deleteblog"]) && isset($_POST["submit"]) && $_POST["submit"] == "Elimina Blog") {

      $bid = trim(htmlspecialchars($_GET["deleteblog"]));

      if (is_auth($uid, $bid)) {  // Se l'utente corrente è autore di quel determinato blog, può cancellarlo, altrimenti no.

        try {
          //Query per l'eliminazione del blog
          $query = "DELETE FROM blog WHERE id=:bid";
          $result = $pdo->prepare($query);
          $result->bindParam(":bid", $bid);
          $result->execute();
        } catch (PDOException $e) {

          echo "" . $e->getMessage() . "";
          exit();
        }
      }
      //Mostro l'alert di corretto salvataggio delle modifiche.
      echo '<div class="alert alert-success" role="alert">Modifiche salvate! <a href= "javascript:history.back(-1)" class="alert-link">Torna indietro</a></div>';
      exit();
    } elseif (isset($_GET["deleteblog"]) && !is_numeric($_GET['deleteblog']) && !isset($_POST["submit"])) {
      include 'errore404.php';
      exit();
    }

    include 'templates/acp_modblogs.html.php';
    exit();

   
  } elseif (isset($_GET["settings"])) {

    trim(htmlspecialchars($_GET["settings"]));

    try {
      //Query per recuperare i parametri dell'utente corrente
      $sql = "SELECT * FROM utenti WHERE uid=:uid";
      $s = $pdo->prepare($sql);
      $s->bindValue(":uid", $uid);
      $s->execute();
    } catch (PDOException $e) {
      echo $e->getmessage();
      exit();
    }

    while ($row = $s->fetch()) {

      $user = array(
        "nome" => $row['nome'], "cognome" => $row['cognome'], "username" => $row['username'], "tel" => $row['tel'],
        "bio" => $row['bio'], "email" => $row["email"]
      );
    }

    include "./templates/acp_settings.html.php";
    exit();

    
  } elseif (isset($_GET["savemod"]) && isset($_POST["save"]) && $_POST["save"] == "Salva") {

    trim(htmlspecialchars($_GET["savemod"]));

    session_start(); //Avvio la sessione.

    // Controlli di sicurezza per SQL injection
    $username = trim(htmlspecialchars($_POST["username"]));
    $nome =  trim(htmlspecialchars($_POST["nome"]));
    $cognome = trim(htmlspecialchars($_POST["cognome"]));
    $email = trim(htmlspecialchars($_POST["email"]));
    $note = trim(htmlspecialchars($_POST["note"]));

    //Controllo che tutti i campi siano riempiti
    if (
      isset($username) && $username != ""
      && isset($nome) && $nome != ""
      && isset($cognome) && $cognome != ""
      && isset($email) && $email != ""
    ) {
         
      try {

        $query = "SELECT COUNT(*) as tot FROM utenti WHERE username=:user AND uid!=:uid";
        $result = $pdo->prepare($query);
        $result->bindParam(":user", $username);
        $result->bindParam(":uid", $uid);
        $result->execute();
      } catch (PDOException $e) {
    
        echo "" . $e->getMessage() . "";
        exit();
      }
    
      $row = $result->fetch();
      $exists = $row["tot"];

      if($exists < 1) {

      //Eseguo la query di aggiornamento dei parametri passati tramite il form dall'utente.
      try {
        $sql = "UPDATE utenti SET username=:name, email=:mail, bio=:note, nome=:nome, cognome=:cognome WHERE uid=:uid";
        $s = $pdo->prepare($sql);
        $s->bindValue(":name", $_POST["username"]);
        $s->bindValue(":nome", $_POST["nome"]);
        $s->bindValue(":cognome", $_POST["cognome"]);
        $s->bindValue(":mail", $_POST["email"]);
        $s->bindValue(":uid", $_SESSION["uid"]);
        $s->bindValue(":note", $_POST["note"]);
        $s->execute();
      } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
      } //Mostro l'alert di corretto salvataggio delle modifiche.
      echo '<div class="alert alert-success" role="alert">Modifiche salvate! <a href= "javascript:history.back(-1)" class="alert-link">Torna indietro</a></div>';
      exit();

     } else {
         //Se il nuovo username esiste già, avverto l'utente.
      echo '<div class="alert alert-danger" role="alert">Username già in uso <a href= "javascript:history.back(-1)" class="alert-link">Torna indietro</a></div>';
      exit();
     }
    } else { //Se qualche campo risulta vuoto avverto l'utente che dovrà riempire tutti i campi per proseguire.
      echo '<div class="alert alert-danger" role="alert">Compila tutti i campi! <a href= "javascript:history.back(-1)" class="alert-link">Torna indietro</a></div>';
      exit();
    }
  } elseif (isset($_GET["savemod"]) && !isset($_POST["save"])) { //Se l'utente prova a forzare il link lo avverto dell'errore e lo invito a tornare indietro.
    echo '<div class="alert alert-danger" role="alert">Non puoi raggiungere questo link direttamente! <a href= "javascript:history.back(-1)" class="alert-link">Torna indietro</a></div>';
    exit();
  }


  // Codice per bannare utenti 

  elseif (isset($_GET["ban"])) {

    trim(htmlspecialchars($_GET["ban"]));
    //Query per mostrare lo username (e l'user id) degli utenti (che saranno scelti per essere bannati)
    try {
      $sql = "SELECT username, uid FROM utenti WHERE uid!=:uid";
      $s = $pdo->prepare($sql);
      $s->bindValue(":uid", $_SESSION["uid"]);
      $s->execute();
    } catch (PDOException $e) {
      echo $e->getMessage();
      exit();
    }

    while ($row = $s->fetch()) {

      $users[] = array(
        "nick" => $row['username'], "id" => $row['uid']
      );
    }

    try {
      //Query per mostrare il nome e l'id del blog dove l'utente sarà bannato
      $query = "SELECT nome, id FROM blog WHERE idautore=:uid OR 1<=(SELECT COUNT(*) FROM cogestisce WHERE coautid=:uuid AND idblog=id)";
      $result = $pdo->prepare($query);
      $result->bindParam(":uid", $_SESSION["uid"]);
      $result->bindParam(":uuid", $_SESSION["uid"]);
      $result->execute();
    } catch (PDOException $e) {

      echo "" . $e->getMessage() . "";
      exit();
    }

    while ($row = $result->fetch()) {

      $blogs[] = array("nome" => $row["nome"], "id" => $row['id']);
    }
    //Se tutti i parametri sono correttamente impostati
    if (
      isset($_GET["ban"])
      && isset($_GET["action"]) && $_GET["action"] == "banuser"
      && isset($_POST["submit"])
      && $_POST["submit"] == "Banna"

    ) {

      // Controlli di sicurezza per SQL injection
      $blog = trim(htmlspecialchars($_POST["blog"]));
      $uid = $_SESSION['uid'];
      $banned = trim(htmlspecialchars($_POST["banuser"]));

      if (
        isset($banned) && $banned != "notset"
        && isset($blog) && $blog != "notset"
      ) {


        if (is_auth($uid, $blog) || is_coauth($uid, $blog)) {

          if (!is_banned($banned, $blog) && !is_coauth($banned, $blog) && !is_auth($banned, $blog)) {

            try {
              //Query per inserire l'utente e il relativo blog da cui è stato bannato nella tabella escluso del database
              $sql = "INSERT INTO escluso (idutente, idblog) 
               SELECT " . $banned . "," . $blog . "
               WHERE NOT EXISTS (Select idutente, idblog From escluso WHERE idutente=" . $banned . " AND idblog=" . $blog . ") LIMIT 1;";
              $s = $pdo->prepare($sql);
              //$s->bindValue(":uid", $uid);
              //$s->bindValue(":bid", $blog);
              $s->execute();
            } catch (PDOException $e) {

              echo $e->getMessage();
              exit();
            } //Mostro l'alert di corretto salvataggio delle modifiche.
            echo '<div class="alert alert-success" role="alert">Modifiche salvate! <a href= "javascript:history.back(-1)" class="alert-link">Torna indietro</a></div>';
            exit();
          } else {

            echo '<div class="alert alert-danger" role="alert">L\'utente che vuoi escludere è un autore o un coautore del blog, oppure ne è già stato escluso. <a href= "javascript:history.back(-1)" class="alert-link">Torna indietro</a></div>';
            exit();
          }
        } else {

          echo '<div class="alert alert-danger" role="alert">Devi essere Autore o Coautore del Blog per poterne bannare utenti. <a href= "javascript:history.back(-1)" class="alert-link">Torna indietro</a></div>';
          exit();
        }
      } else { //Se qualche campo risulta vuoto avverto l'utente che dovrà riempire tutti i campi per proseguire.
        echo '<div class="alert alert-danger" role="alert">Compila tutti i campi! <a href= "javascript:history.back(-1)" class="alert-link">Torna indietro</a></div>';
        exit();
      }
    } elseif (isset($_GET["action"]) || $_GET["action"] == "banuser" && !isset($_POST["submit"])) {
      //Se l'utente prova a forzare il link lo avverto dell'errore e lo invito a tornare indietro.
      echo '<div class="alert alert-danger" role="alert">Non puoi raggiungere questo link direttamente! <a href= "javascript:history.back(-1)" class="alert-link">Torna indietro</a></div>';
      exit();
    }

    include "./templates/acp_ban.html.php";
    exit();


    /// Codice di sban utenti
  } elseif (isset($_GET["sban"])) {

    trim(htmlspecialchars($_GET["sban"]));

    try {
      //Query per mostrare gli utenti nella select nella pagina "sban"
      $sql = "SELECT DISTINCT username, uid FROM utenti, escluso WHERE uid!=:uid AND idutente=uid";
      $s = $pdo->prepare($sql);
      $s->bindValue(":uid", $_SESSION["uid"]);
      $s->execute();
    } catch (PDOException $e) {
      echo $e->getMessage();
      exit();
    }

    while ($row = $s->fetch()) {

      $users[] = array(
        "nick" => $row['username'], "id" => $row['uid']
      );
    }

    try {
      //Query per mostrare i blog nella select nella pagina "ban"
      $query = "SELECT nome, id FROM blog WHERE idautore=:uid OR 1<=(SELECT COUNT(*) FROM cogestisce WHERE coautid=:uuid AND idblog=id)";
      $result = $pdo->prepare($query);
      $result->bindParam(":uid", $_SESSION["uid"]);
      $result->bindParam(":uuid", $_SESSION["uid"]);
      $result->execute();
    } catch (PDOException $e) {

      echo "" . $e->getMessage() . "";
      exit();
    }

    while ($row = $result->fetch()) {

      $blogs[] = array("nome" => $row["nome"], "id" => $row['id']);
    }

    if (
      isset($_GET["sban"])
      && isset($_GET["action"]) && $_GET["action"] == "sbanuser"
      && isset($_POST["submit"])
      && $_POST["submit"] == "Sbanna"
    ) {

      // Controlli di sicurezza 
      $blog = trim(htmlspecialchars($_POST["blog"]));
      $banned = trim(htmlspecialchars($_POST["banuser"]));
      $uid = $_SESSION['uid'];



      if (
        isset($banned) && $banned != "notset"
        && isset($blog) && $blog != "notset"
      ) {
        //Controlli di sicurezza per essere sicuri di non essere oggetto di XSS
        if (is_auth($uid, $blog) || is_coauth($uid, $blog)) {

          if (is_banned($banned, $blog) && $banned!=$_SESSION['uid']) { // L'utente deve essere escluso dal blog scelto e soprattutto non si deve trattare di quello corrente

            try {
              //Query di eliminazione dell'utente e il relativo blog dalla tabella escluso (rimozione ban)
              $sql = "DELETE FROM escluso WHERE idutente=:id AND idblog=:bid";
              $s = $pdo->prepare($sql);
              $s->bindValue(":id", $banned);
              $s->bindValue(":bid", $blog);
              $s->execute();
            } catch (PDOException $e) {

              echo $e->getMessage();
              exit();
            } //Mostro l'alert di corretto salvataggio delle modifiche.
            echo '<div class="alert alert-success" role="alert">Modifiche salvate! <a href= "javascript:history.back(-1)" class="alert-link">Torna indietro</a></div>';
            exit();
          } else {

            echo '<div class="alert alert-danger" role="alert">L\'utente selezionato non risulta al momento escluso dal Blog. Oppure si tratta di te stesso. <a href= "javascript:history.back(-1)" class="alert-link">Torna indietro</a></div>';
            exit();
          }
        } else {

          echo '<div class="alert alert-danger" role="alert">Devi essere Autore o Coautore del Blog per poterne sbannare utenti. <a href= "javascript:history.back(-1)" class="alert-link">Torna indietro</a></div>';
          exit();
        }
      } else { //Se qualche campo risulta vuoto avverto l'utente che dovrà riempire tutti i campi per proseguire.
        echo '<div class="alert alert-danger" role="alert">Compila tutti i campi! <a href= "javascript:history.back(-1)" class="alert-link">Torna indietro</a></div>';
        exit();
      }
    } elseif (isset($_GET["action"]) || $_GET["action"] == "sbanuser" && !isset($_POST["submit"])) {
      //Se l'utente prova a forzare il link lo avverto dell'errore e lo invito a tornare indietro.
      echo '<div class="alert alert-danger" role="alert">Non puoi raggiungere questo link direttamente! <a href= "javascript:history.back(-1)" class="alert-link">Torna indietro</a></div>';
      exit();
    }

    include "./templates/acp_sban.html.php";
    exit();
  }
} else { //Se la pagina non viene trovata dirigo l'utente nell'apposita pagina che lo informa dell'errore 404 e lì troverà dei link utili.
  include 'errore404.php';
  exit();
}