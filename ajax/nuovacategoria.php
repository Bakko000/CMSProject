<?php

include_once("../config/database.php");

session_start();
//Se chi sta eseguendo l'azione è un utente
if (isset($_SESSION) && isset($_SESSION["uid"])) {

    if (!isset($_GET["suggest"])) {  // Controlli di sicurezza 

            $nome = trim(htmlspecialchars($_POST["t_nome"]));
            $descrizione = trim(htmlspecialchars($_POST["descrizione"]));
            $topicpadre = trim(htmlspecialchars($_POST["topicpadre"]));

            if(mb_strlen($nome, "UTF-8")<=60 && mb_strlen($descrizione, "UTF-8")<=510) { 
                 // E' necessario controllare la lunghezza degli input inviati (già fatto in HTML) e la loro correttezza
                 // +10 di tolleranza in quanto è possibile ci siano caratteri speciali che valgono più di 1 byte

             // Inserisco la categoria se non esiste già
                try {
                    
                    $sql = "SELECT COUNT(*) AS tot FROM topic WHERE t_nome=:nome OR descr=:desc;";
                    $s = $pdo->prepare($sql);
                    $s->bindParam(":nome", $nome);
                    $s->bindParam(":desc", $descrizione);
                    $s->execute();
                } catch (PDOException $e) {
                    echo $e->getMessage();
                    exit();
                }

                $row =$s->fetch();

                if($row["tot"]<1) {   //Non esiste..allora inserisco 

            try {
                // Inserisco la categoria
                $sql = "INSERT INTO topic (t_nome, descr) VALUES(:nome, :desc)";
                $s = $pdo->prepare($sql);
                $s->bindParam(":nome", $nome);
                $s->bindParam(":desc", $descrizione);
                $s->execute();
            } catch (PDOException $e) {
                echo $e->getMessage();
                exit();
            }
            
            $last = $pdo->lastInsertId();

            if ($_POST["topicpadre"] != "" && $_POST["topicpadre"] != null) { // aggiorno se il topic ha padre
          
            try {
                // Aggiorno il topic appena creato con il suo padre
                $sql = "UPDATE topic
                SET topicpadre = :padre
                WHERE tid=:tid
                ";
                $s = $pdo->prepare($sql);
                $s->bindParam(":padre", $topicpadre);
                $s->bindParam(":tid", $last);
                $s->execute();
            } catch (PDOException $e) {
                echo $e->getMessage();
                exit();
            }
          
        }
    }


        if ($last > 0) {
            //Dopo aver creato la nuova categoria la mostro in un menù a tendina e lo passo attraverso un JSON
            try {
                $sql = "SELECT t_nome, tid FROM topic WHERE tid=:id ";
                $select = $pdo->prepare($sql);
                $select->bindValue(":id", $last);
                $select->execute();
            } catch (PDOException $e) {
                echo $e->getMessage();
                exit();
            }

            $row = $select->fetch();

            echo json_encode(array("nome" => $row["t_nome"], "id" => $row["tid"]));
        }
      } else { //Se qualche campo risulta troppo lungo, lo comunico ad AJAX che mostrerà l'alert all'utente.
        /* echo $nome;
        echo $descrizione;
        echo mb_strlen($nome, "UTF-8");
        echo strlen($nome);
        echo mb_strlen ($descrizione, "UTF-8"); */
        echo 'errore';
        exit();
      }

 } elseif (isset($_GET["suggest"])) {

        //Query per i suggerimenti
        if (isset($_POST["t_nome"]) && $_POST["t_nome"] != "") {

            $inizio = trim(htmlspecialchars($_POST["t_nome"])) . "%";

            try {
                $sql = "SELECT t_nome, tid FROM topic WHERE t_nome LIKE :inizio";
                $result = $pdo->prepare($sql);
                $result->bindParam(":inizio", $inizio);
                $result->execute();
            } catch (PDOException $e) {
                echo $e->getMessage();
                exit();
            }


            while ($t = $result->fetch()) {

                if ($t["t_nome"] == "") {
                    $response = "";
                } else {
                    $response = "<h6 id='sugg'>Forse cercavi:  <a href='javascript:void();' data-value='" . $t['tid'] . "' onclick=suggerisci(this.getAttribute('data-value'))>" . $t["t_nome"] . "</a></h6>";
                }
                echo $response;
            }
        }
    }
} else echo ("Non sei loggato.");