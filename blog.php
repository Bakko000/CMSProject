<?php

// @view: ./templates/blogs.html.php

require_once('./inc/functions.php');  // Richiede funzioni utili per checkare se si è autori o coautori


// E' evidente ci sia un tentativo di forzare il link, mostro l'errore ed esco
if (!isset($_GET["action"]) || $_GET["action"] != "viewblog" || !isset($_GET["id"]) || $_GET["id"] == "" || !is_numeric($_GET["id"])) {

   include 'errore404.php';
   exit();
} elseif (isset($_GET['action']) && ($_GET['action'] == 'viewblog')) {

   $bid = htmlspecialchars($_GET['id']); // Sanifico l'id dalla barra di ricerca


   include_once './config/database.php';

   try {   // Ottengo i dati sul blog

      $query = "SELECT nome, descrizione, data, id, fotob FROM blog WHERE id = :bid";
      $result = $pdo->prepare($query);
      $result->bindParam(':bid', $bid);
      $result->execute();
   } catch (PDOException $e) {

      echo "" . $e->getMessage() . "";
      exit();
   }

   $blog = $result->fetch();


   // Ottengo i dati sull'autore del blog corrente

   try {

      $query = "SELECT u.nome, u.cognome, u.uid, u.p_foto FROM utenti as u, blog as b WHERE b.id = :bid AND u.uid = b.idautore";
      $result = $pdo->prepare($query);
      $result->bindParam(':bid', $bid);
      $result->execute();
   } catch (PDOException $e) {

      echo "" . $e->getMessage() . "";
      exit();
   }

   $author = $result->fetch();



   // Ottengo i dati per la categoria del blog

   try {

      $query = "SELECT * FROM topic as t, blog as b WHERE b.id = :bid AND b.idtopic=t.tid";
      $result = $pdo->prepare($query);
      $result->bindParam(':bid', $bid);
      $result->execute();
   } catch (PDOException $e) {

      echo "" . $e->getMessage() . "";
      exit();
   }

   foreach ($result as $row) {

      $topics[] = array("id" => $row["tid"], "nome" => $row["t_nome"], "descr" => $row["desc"], "idblog" => $row["idblog"], "padre" => $row["topicpadre"]);
   }

   // Ottengo molti dati per mostrare i post nel blog visualizzato

   try {
      $query = "SELECT p.font, p.pid, p.testo, p.foto1, p.foto2, p.nome as titolopost, p.data, p.authid FROM 
      post as p, blog as b WHERE
       p.idblog = b.id AND  
       b.id = :bid ORDER BY p.data DESC";
      $check = $pdo->prepare($query);
      $check->bindParam(":bid", $bid);
      $check->execute();
   } catch (PDOException $e) {

      echo "" . $e->getMessage() . "";
      exit();
   }

   foreach ($check as $row) {

      $posts[] = array(
         "pid" => $row['pid'], "testo" => $row['testo'], "foto1" => $row['foto1'], "foto2" => $row['foto2'],
         "data" => $row['data'],  "titolo" => $row["titolopost"], "font" => $row["font"]
      );
   }


   if ($blog["nome"] == "") {  // Il blog non è stato trovato 

      include 'errore404.php';
      exit();
   } else {

      include './templates/blogs.html.php';   // Se è stato trovato, visualizza il template con i dati sul blog e i dati sui post che contiene
      exit();
   }
}