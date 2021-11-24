<?php

/* @view ./templates/nuovopost.html.php */

require_once('./inc/functions.php');
include './elementicomuni/headcomune.php';
include './particomuni/header.php';


session_start(); //Avvio la sessione.

if (isset($_SESSION) && isset($_SESSION["uid"]) && isset($_SESSION["user"])) { // Se si tratta di un utente, può postare

  include './config/database.php';

  // Seleziona i Blog possibili dove l'utente può postare fra quelli di cui è autore o coautore

  try {

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

    $blogs[] = array("nome" => $row["nome"], "id" => $row['id']);  // Array multidimensionale con i risultati
  }

  include './templates/nuovopost.html.php'; // Include il template

  if (isset($_GET["publish"]) && isset($_POST["submit"]) && $_POST["submit"] == "Salva") { //Invia le modifiche al server che le aggiunge al database.

    // Controlli di sicurezza 
    $titolo = trim(htmlspecialchars($_POST["titolo"]));
    $testo = trim(htmlspecialchars($_POST["text"]));
    $blog = trim(htmlspecialchars($_POST["blog"]));
    $topic = trim(htmlspecialchars($_POST["topic"]));
    $font = htmlspecialchars($_POST["font"]);
   $img1 = file_get_contents($_FILES['img1']['tmp_name']);  // Ottiene il contenuto da mettere nel db
    $img2 = file_get_contents($_FILES['img2']['tmp_name']);
    $extension1 = pathinfo($_FILES['img1']['name'], PATHINFO_EXTENSION);  // Ottiene l'estensione
    $extension2 = pathinfo($_FILES['img2']['name'], PATHINFO_EXTENSION);
    $dimension1 = $_FILES['img1']['size'];  // Ottiene la grandezza 
    $dimension2 = $_FILES['img2']['size'];

    $validi = array("", "jpg", "tiff", "raw", "png", "jpeg");  // "" -> Nessuna foto in quel campo

    if(in_array($extension1, $validi)) {
      if(in_array($extension2, $validi)) { // Se i formati dei file sono davvero foto (possono non esserci foto nel post) e non superano la dimensione di 3 MB
       if ($dimension1 < 3145728 && $dimension2 < 3145728) {

      if (
        isset($titolo) && $titolo != ""
        && isset($testo) && $testo != ""             // Se tutti i campi sono compilati
        && isset($blog) && $blog != "notset"
      ) {

        if (mb_strlen($titolo, "UTF-8") <= 110 && mb_strlen($testo, "UTF-8") <= 2510) {   // Controllo la lunghezza, +10 di tolleranza per eventuali caratteri speciali

          if (is_auth($_SESSION['uid'], $blog) || is_coauth($_SESSION['uid'], $blog)) { // Se non ci sono tentativi fraudolenti, inserisci nel DB 


            try {

              $sql = 'INSERT INTO post  (foto1, foto2, nome, testo, authid, punteggio, data, idblog) VALUES (:img1, :img2, :titolo, :texxt, :id, :points, :data, :bid)';
              $s = $pdo->prepare($sql);
              $s->bindValue(":img1", $img1);
              $s->bindValue(":img2", $img2);
              $s->bindValue(":titolo", $titolo);
              $s->bindValue(":texxt", $testo);
              $s->bindValue(":points", "0");
              $s->bindValue(":id", $_SESSION['uid']);
              $s->bindValue("data", date("Y/m/d H:i"));
              $s->bindValue(":bid", $blog);
              $s->execute();
            } catch (PDOException $e) {

              echo "" . $e->getMessage() . "";
              exit();
            }

            $pid = $pdo->lastInsertId(); // Ottieni l'id del post inserito

            if ($font != "notset" && isset($font)) {  // Nel qual caso l'utente abbia selezionato un font, lo inserisco nel DB aggiornando il campo nella riga del post appena creato

              try {

                $sql = 'UPDATE post SET font=:font WHERE pid=:pid';
                $s = $pdo->prepare($sql);
                $s->bindValue(":font", $font);
                $s->bindValue(":pid", $pid);
                $s->execute();
              } catch (PDOException $e) {

                echo "" . $e->getMessage() . "";
                exit();
              }
            }

            echo "<script> window.location.replace('post.php?action=viewpost&id=" . $pid . "') </script>"; // Rimanda alla pagina del post
            exit();
          } else {   // Errore, non sei nè autore, nè coautore
            echo '<div class="alert alert-danger" role="alert">Puoi creare Post solo in Blog che hai creato o che CoAutori. <a href="javascript:history.back(-1)">Torna indietro.</a></div>';
            exit();
          }
        } else {   // Errore, troppo lungo
          echo '<div class="alert alert-danger" role="alert">Sei oltre il limite caratteri consentito.</div>';
          exit();
        }
      } else {  // Errore, non hai compilato correttamente
        echo '<div class="alert alert-danger" role="alert">Compila tutti i campi!</div>';
        exit();
       }
      } else {  // Errore di dimensione
        echo '<div class="alert alert-danger" role="alert">Sono consentiti massimo 3 MB a foto.</div>';
        exit();
      }
     } else {  // Errore di formato 
      echo '<div class="alert alert-danger" role="alert">Sono consentiti solo formati immagini/fotografici.</div>';
      exit();
    }
   } else {  // Errore di formato
      echo '<div class="alert alert-danger" role="alert">Sono consentiti solo formati immagini/fotografici.</div>';
      exit();
    }
  }
} else {  // Errore, sei un semplice visitatore o un utente sloggato che sta forzando il link
  echo "<div class='alert alert-danger' role='alert'>Per creare un nuovo post, entra o registrati.</div>";
  include 'login.php'; // Includi il template di login ed esci
  exit();
}
