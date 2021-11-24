<?php

// @view: ./templates/createblog.html.php

include './elementicomuni/headcomune.php';
include './particomuni/header.php';
include './inc/functions.php';


session_start(); //Avvio la sessione.

if (isset($_SESSION) && isset($_SESSION["uid"]) && isset($_SESSION["user"])) {

  include './config/database.php';

  try {
    //Query che recupera uid e username degli utenti diversi da quello attuale
    $query = "SELECT uid, username FROM utenti WHERE uid<>:uid";
    $result = $pdo->prepare($query);
    $result->bindParam(":uid", $_SESSION['uid']);
    $result->execute();
  } catch (PDOException $e) {

    echo "" . $e->getMessage() . "";
    exit();
  }

  while ($row = $result->fetch()) {

    $users[] = array("username" => $row["username"], "uid" => $row['uid']);
  }



  try {
    // Query che recupera l'id del topic e il nome del topic
    $query = "SELECT tid, t_nome FROM topic";
    $result = $pdo->prepare($query);
    $result->execute();
  } catch (PDOException $e) {

    echo "" . $e->getMessage() . "";
    exit();
  }

  while ($row = $result->fetch()) {

    $topics[] = array("nome" => $row["t_nome"], "id" => $row['tid']);
  }

  include './templates/createblog.html.php';



  if (isset($_GET["createblog"]) && isset($_POST["submit"]) && $_POST["submit"] == "Salva") { //Invia le nuove righe al server che le aggiunge al database.

    // Controlli di sicurezza 
    $titolo = trim(htmlspecialchars($_POST["titolo"]));
    $descrizione = trim(htmlspecialchars($_POST["descrizione"]));
    $topic = trim(htmlspecialchars($_POST["topic"]));
    $coauth = trim(htmlspecialchars($_POST["coauth"]));
    $img1 = file_get_contents($_FILES['img1']['tmp_name']);

   if(upcheckfiles("img1", 3145728)) {  // Controllo che la dimensione sia corretta, minore di 3MB e il formato accettato

    //Controllo che i parametri non siano vuoti (lo faccio anche in HTML)
    if (
      isset($titolo) && $titolo != ""
      && isset($descrizione) && $descrizione != ""
      && isset($topic) && $topic != "notset"
    ) {

      //Controllo che i parametri non siano troppo lunghi (lo faccio anche in HTML), +10 di tolleranza per via della possibilità di inserimento dei caratteri speciali
      if (mb_strlen($titolo, "UTF-8") <= 60 && mb_strlen($descrizione, "UTF-8") <= 310) {

        try {
          //Inserisce la nuova riga nel database con i parametri del blog passati.
          $sql = 'INSERT INTO blog (nome, descrizione, idautore, data, idtopic, fotob) VALUES (:titolo, :descr, :id, :data, :topic, :img1)';
          $s = $pdo->prepare($sql);
          $s->bindValue(":img1", $img1);
          $s->bindValue(":titolo", $titolo);
          $s->bindValue(":descr", $descrizione);
          $s->bindValue(":id", $_SESSION['uid']);
          $s->bindValue(":topic", $topic);
          $s->bindValue(":data", date("Y-m-d"));
          $s->execute();
        } catch (PDOException $e) {

          echo "" . $e->getMessage() . "";
          exit();
        }

        $bid = $pdo->lastInsertId();

        if ($coauth != "notset" && isset($coauth)) {
          //Se l'utente decide di aggiungere un coautore al suo blog inserisco i suoi dati nel database (la coppia uid e bid)
          try {

            $sql = 'INSERT INTO cogestisce (coautid, idblog) VALUES (:uid, :bid)';
            $s = $pdo->prepare($sql);
            $s->bindValue(":uid", $coauth);
            $s->bindValue(":bid", $bid);
            $s->execute();
          } catch (PDOException $e) {

            echo "" . $e->getMessage() . "";
            exit();
          }
        }


        //Se l'inserimento va a buon fine si viene diretti al blog appena creato
        echo "<script> window.location.replace('blog.php?action=viewblog&id=" . $bid . "') </script>";
        exit();
      } else {  // Limite caratteri superato
        echo '<div class="alert alert-danger" role="alert">Sei andato oltre il limite caratteri consentito.</div>';
        exit();
      }
      
     } else {  // Campi non compilati
      echo '<div class="alert alert-danger" role="alert">Compila tutti i campi!</div>';
      exit();
     }
    
    } else {  // Formato o dimensione errati
      echo '<div class="alert alert-danger" role="alert">Dimensione o formato errati. 3MB consentiti e solo formati immagini/fotografici.</div>';
      exit();
    }

  }
} else {  // Invito a registrarsi per creare un nuovo blog
  echo "<div class='alert alert-danger' role='alert'>Per creare un nuovo blog, entra o registrati.</div>";
  include 'login.php';
  exit();
}
