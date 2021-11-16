<?php

/* In questo file sono presenti delle funzioni che eseguono controlli fondamentali per il funzionamento del sistema.
Esse sono chiamate poi più volte in blog.php, nuovopost.php, topics.php e relativi templates a ciascun file associati. */


// Controlla se l'utente corrente è stato escluso dai commenti nel blog corrente
function is_banned($user, $blog)
{

   require(dirname(dirname(__FILE__)) . '/config/database.php');  ## Include il file per caricare PDO

   try {

      $query = "SELECT e.idutente, e.idblog FROM utenti as u, blog as b, escluso as e WHERE e.idutente=:uid AND e.idblog=:bid";
      $result = $pdo->prepare($query);
      $result->bindParam(':uid', $user);
      $result->bindParam(':bid', $blog);
      $result->execute();
   } catch (PDOException $e) {

      echo "" . $e->getMessage() . "";
      exit();
   }

   $row = $result->fetchAll();

   if (count($row) > 0) {

      return true;

   } else {

   return false;
   }
}


// Controlla se l'utente corrente ha votato il post corrente
function has_voted($user, $post)
{

   require(dirname(dirname(__FILE__)) . '/config/database.php');  ## Include il file per caricare PDO

   try {

      $query = "SELECT uid, pid FROM vota WHERE uid=:uid AND pid=:pid";
      $result = $pdo->prepare($query);
      $result->bindParam(':uid', $user);
      $result->bindParam(':pid', $post);
      $result->execute();
   } catch (PDOException $e) {

      echo "" . $e->getMessage() . "";
      exit();
   }

   $row = $result->fetchAll();

   if (count($row) > 0) {

      return true;

   } 

   return false;
}



// Controlla se l'utente corrente è autore del blog corrente
function is_auth($user, $blog)
{

   require(dirname(dirname(__FILE__)) . '/config/database.php');  ## Include il file per caricare PDO

   try {

      $query = "SELECT id FROM blog WHERE idautore=:uid AND id=:bid";
      $result = $pdo->prepare($query);
      $result->bindParam(':uid', $user);
      $result->bindParam(':bid', $blog);
      $result->execute();
   } catch (PDOException $e) {

      echo "" . $e->getMessage() . "";
      exit();
   }

   $row = $result->fetch();

   if($row["id"] < 1) {
         
         return false;

   }

   else {
       
       return true;

   }

}





// Controlla se l'utente corrente è stato promosso a coautore nel blog corrente
function is_coauth($user, $blog)
{

   require(dirname(dirname(__FILE__)) . '/config/database.php');  ## Include il file per caricare PDO

   try {

      $query = "SELECT coautid, idblog FROM cogestisce WHERE coautid=:uid AND idblog=:bid";
      $result = $pdo->prepare($query);
      $result->bindParam(':uid', $user);
      $result->bindParam(':bid', $blog);
      $result->execute();
   } catch (PDOException $e) {

      echo "" . $e->getMessage() . "";
      exit();
   }

   $row = $result->fetchAll();

   if (count($row) > 0) {

      return true;
   }

   return false;
}



// Mostra i coautori di un Blog
function show_coauths($blog)
{

   require(dirname(dirname(__FILE__)) . '/config/database.php');  ## Include il file per caricare PDO

   try {

      $query = "SELECT coautid, username as nomecoauth FROM cogestisce, utenti WHERE coautid=uid AND idblog=:bid";
      $result = $pdo->prepare($query);
      $result->bindParam(':bid', $blog);
      $result->execute();
   } catch (PDOException $e) {

      echo "" . $e->getMessage() . "";
      exit();
   }

   $rows = $result->fetchAll();

   if (count($rows) > 0) {

      foreach ($rows as $row) {

         $coauths[] = array("id" => $row["coautid"], "nome" => $row["nomecoauth"]);
      }

      foreach ($coauths as $coauth) {
         echo '<a href="utenti.php?id=' . $coauth['id'] . '">'.$coauth["nome"].'</a>&nbsp;&nbsp;';
      }

   } else {
        echo "nessuno";
   }
}





// Controlla se l'utente corrente è autore del commento corrente
function is_auth_comm($user, $comm)
{

   require(dirname(dirname(__FILE__)) . '/config/database.php');  ## Include il file per caricare PDO

   try {

      $query = "SELECT comid FROM commenti WHERE idauth=:uid AND comid=:cid";
      $result = $pdo->prepare($query);
      $result->bindParam(':uid', $user);
      $result->bindParam(':cid', $comm);
      $result->execute();
   } catch (PDOException $e) {

      echo "" . $e->getMessage() . "";
      exit();
   }

   $row = $result->fetchAll();

   if (count($row) > 0) {

      return true;
   }

   return false;
}






// Controlla se il topic corrente ha figli o se è una categoria unica
function has_sons($tid)
{


   require(dirname(dirname(__FILE__)) . '/config/database.php');  ## Include il file per caricare PDO

   try {

      $query = "SELECT t.t_nome as padre, t.tid as idpadre, t1.tid as idfiglio, t1.t_nome as nomefiglio
         FROM topic as t, topic as t1 where t.tid = t1.topicpadre and t.tid=:tid";
      $result = $pdo->prepare($query);
      $result->bindParam(':tid', $tid);
      $result->execute();
   } catch (PDOException $e) {

      echo "" . $e->getMessage() . "";
      exit();
   }

   $row = $result->fetchAll();

   if (count($row) > 0) {

      return true;
   }

   return false;
}



// Mostra i figli del topic corrente
function show_sons($tid)
{


   require(dirname(dirname(__FILE__)) . '/config/database.php');  ## Include il file per caricare PDO

   try {

      $query = "SELECT t.t_nome as padre, t.tid as idpadre, t1.tid as idfiglio, t1.t_nome as nomefiglio
      FROM topic as t, topic as t1 where t.tid = t1.topicpadre and t.tid=:tid";
      $result = $pdo->prepare($query);
      $result->bindParam(':tid', $tid);
      $result->execute();
   } catch (PDOException $e) {

      echo "" . $e->getMessage() . "";
      exit();
   }

   $rows = $result->fetchAll();

   if (count($rows) > 0) {

      foreach ($rows as $row) {

         $topics[] = array("id" => $row["idfiglio"], "nome" => $row["nomefiglio"]);
      }

      foreach ($topics as $figlio) {
         echo '<a href="topics.php?action=viewtopic&tid=' . $figlio['id'] . '"><button type="button" class="btn btn-danger btn-round m-1">' . $figlio["nome"] . "</button></a>";
      }
   }
}


// Controlla se il topic corrente ha un padre = è una sottocategoria
function has_father($tid)
{

   require(dirname(dirname(__FILE__)) . '/config/database.php');  ## Include il file per caricare PDO

   try {

      $query = "SELECT t.t_nome as padre, t.tid as idpadre, t1.tid as idfiglio, t1.t_nome as nomefiglio
      FROM topic as t, topic as t1 where t.tid = t1.topicpadre and t1.tid=:tid";
      $result = $pdo->prepare($query);
      $result->bindParam(':tid', $tid);
      $result->execute();
   } catch (PDOException $e) {

      echo "" . $e->getMessage() . "";
      exit();
   }

   $row = $result->fetchAll();

   if (count($row) > 0) {

      return true;
   }

   return false;
}


// Mostra il padre del topic corrente
function show_father($tid)
{


   require(dirname(dirname(__FILE__)) . '/config/database.php');  ## Include il file per caricare PDO

   try {

      $query = "SELECT t.t_nome as padre, t.tid as idpadre, t1.tid as idfiglio, t1.t_nome as nomefiglio
      FROM topic as t, topic as t1 where t.tid = t1.topicpadre and t1.tid=:tid";
      $result = $pdo->prepare($query);
      $result->bindParam(':tid', $tid);
      $result->execute();
   } catch (PDOException $e) {

      echo "" . $e->getMessage() . "";
      exit();
   }

   $padre = $result->fetch();

   echo '<a href="topics.php?action=viewtopic&tid=' . $padre['idpadre'] . '"><button type="button" class="btn btn-danger btn-round m-1">' . $padre["padre"] . " " . "</button></a>";
}



// Controlla se l'utente corrente può effettivamente escludere l'utente scelto
function canban($user, $blog, $banned, $post)
{

   if (is_auth($user, $blog) || is_coauth($user, $blog)) {

      if (!is_banned($banned, $blog) && !is_coauth($banned, $blog) && !is_auth($banned, $blog)) {

         require(dirname(dirname(__FILE__)) . '/config/database.php');  ## Include il file per caricare PDO

         try {
            $query = "SELECT c.comid, c.testo FROM commenti as c, post as p, blog as b WHERE c.idauth=:banned AND c.idpost = p.pid AND p.idblog = b.id AND b.id = :blog AND p.pid=:post";
            $check = $pdo->prepare($query);
            $check->bindParam(":banned", $banned);
            $check->bindParam(":blog", $blog);
            $check->bindParam(":post", $post);
            $check->execute();
         } catch (PDOException $e) {

            echo "" . $e->getMessage() . "";
            exit();
         }

         $row = $check->fetchAll();

         if(count($row)>0) {

             return true;
         } 
         
         else {
             return false;
         }

      } else {

            return false;
            }
            
   } else {

      return false;

   }

   return false;
}
