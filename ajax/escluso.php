<?php

require('../config/database.php');
require('../inc/functions.php');  // Qui è presente la funzione canban che utilizza altre funzioni 

// Questo file contiene le funzione necessarie all'esclusione (ban)) di un utente dal commentare un determinato blog

session_start();

// Ban dal post, i parametri arrivano da degli input hidden e da data-value

$bid = trim(htmlspecialchars($_POST["blog_id"]));
$uid = trim(htmlspecialchars($_POST["usr_uid"]));
$post = trim(htmlspecialchars($_POST["p_id"]));


if(isset($_GET["post"]) && isset($bid) && isset($uid) && isset($post)) {

    if(canban($_SESSION['uid'],$bid,$uid,$post)) { // Se puoi bannare --> rimando a functions.php
		//La query va ad inserire nella tabella l'utente escluso e il relativo blog da cui è stato escluso
        try {

            $sql = "INSERT INTO escluso (idutente, idblog) 
              SELECT " . $uid . "," . $bid . "
              WHERE NOT EXISTS (Select idutente, idblog From escluso WHERE idutente=" . $uid . " AND idblog=" . $bid . ") LIMIT 1;";
            $s = $pdo->prepare($sql);
            //$s->bindValue(":uid", $uid);
            //$s->bindValue(":bid", $blog);
            $s->execute();
          } catch (PDOException $e) {

            echo $e->getMessage();
            exit();
          }
          
          /* echo "<p>Puoi perchè:</p>";
          echo "<p>Utente corrente è autore: ".is_auth($_SESSION['uid'], $bid)."</p>";
          echo "<p>Utente corrente è coautore: ".is_coauth($_SESSION['uid'], $bid)."</p>";
          echo "<p>Utente da bannare è bannato: ".is_banned($uid, $bid)."</p>";
          echo "<p>Utente da bannare è coautore blog corrente: ".is_coauth($uid, $bid)."</p>";
          echo "<p>Utente da bannare è autore blog corrente: ".is_auth($uid, $bid)."</p>";
          echo "<p>Utente può bannare: ".(int)canban($_SESSION['uid'],$bid,$uid,$post)."</p>"; */

    } else {

       echo "0";  // L'azione non può essere eseguita
      /* echo "<p>Non puoi perchè:</p>";
      echo "<p>Utente corrente è autore: ".(int)is_auth($_SESSION['uid'], $bid)."</p>";
      echo "<p>Utente corrente è coautore: ".(int)is_coauth($_SESSION['uid'], $bid)."</p>";
      echo "<p>Utente da bannare è bannato: ".(int)is_banned($uid, $bid)."</p>";
      echo "<p>Utente da bannare è coautore blog corrente: ".(int)is_coauth($uid, $bid)."</p>";
      echo "<p>Utente da bannare è autore blog corrente: ".(int)is_auth($uid, $bid)."</p>";
      echo "<p>Utente può bannare: ".(int)canban($_SESSION['uid'],$bid,$uid,$post)."</p>"; */
 
                  
    }
     
} else {  // Tentativo fraudolento 
  echo "Non puoi raggiungere questo link direttamente!";
  exit();
}

