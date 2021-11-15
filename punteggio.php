<?php

include_once './config/database.php';

// @view: ./templates/punteggio.html.php

// E' evidente che ci sia un tentativo di forzare il link o di un attacco, includi la pagina di errore ed esci 
if (!isset($_GET["action"]) || $_GET["action"] != "viewpost" || !isset($_GET["id"]) || $_GET["id"] == "" || !is_numeric($_GET["id"])) {

   include 'errore404.php';
   exit();
} elseif (isset($_GET['action']) && ($_GET['action'] == 'viewpost')) { // Se tutto è regolare 

   $uid = htmlspecialchars($_GET['id']); // Sanifico l'id dell'utente dalla barra di ricerca


   // Ottengo tutti i posts dell'utente con un punteggio almeno maggiore di 0 e li ordino 

   try {

      $query = "SELECT u.nome as nomeauth, u.cognome as cognauth, u.p_foto, p.punteggio, u.uid, p.nome as titolopost, p.testo, p.data, p.pid FROM post as p, utenti as u
      WHERE p.authid=u.uid AND u.uid=:uid AND p.punteggio>0
      GROUP BY p.pid
      ORDER BY p.punteggio DESC";
      $result = $pdo->prepare($query);
      $result->bindParam(':uid', $uid);
      $result->execute();
   } catch (PDOException $e) {

      echo "" . $e->getMessage() . "";
      exit();
   }

   foreach ($result as $row) {  // Creo il vettore multidimensionale con i risultati

      $posts[] = array(
         "id" => $row["pid"], "titolo" => $row["titolopost"], "autore" => $row["nomeauth"], "cognauth" => $row["cognauth"], "fotoauth" => $row["p_foto"],
         "data" => $row["data"], "testo" => $row["testo"], "authid" => $row["uid"], "punti" => $row["punteggio"]
      );
   }



   if ($posts[0]["id"] == "") {  // Se il post non è stato trovato, non esiste, includi l'errore ed esci 

      include 'errore404.php';
      exit();
   } else {

      include './templates/punteggio.html.php';  // Altrimenti includi il template del punteggio ed esci
      exit();
   }
}