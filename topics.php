<?php

 /* @view: ./templates/topic.html.php */ 


// E' evidente che ci sia un tentativo di forzatura del link, o un tentativo di attacco, includo la pagina di errore
if (!isset($_GET["action"]) || $_GET["action"] != "viewtopic" || !isset($_GET["tid"]) || $_GET["tid"] == "" || !is_numeric($_GET["tid"])) {

    include 'errore404.php';
    exit();

} elseif (isset($_GET['action']) && ($_GET['action'] == 'viewtopic')) { // Se invece è tutto okay e sto visualizzando un topic

    $tid = trim(htmlspecialchars($_GET['tid']));  // Sanifico l'id del topic


    include_once './config/database.php';
    include_once './inc/functions.php'; 

    //Query relativa alla visualizzazione dei topics
    try {

        $query = "SELECT * FROM topic WHERE tid = :tid";
        $result = $pdo->prepare($query);
        $result->bindParam(':tid', $tid);
        $result->execute();
    } catch (PDOException $e) {

        echo "" . $e->getMessage() . "";
        exit();
    }

    foreach ($result as $row) {  // Creo vettore multidimensionale

        $topics[] = array("id" => $row["tid"], "nome" => $row["t_nome"], "descr" => $row["descr"], "idblog" => $row["idblog"], "padre" => $row["topicpadre"]);
    }



    try {

// Ottengo tutti i dati dei blogs eseguendo svariate join 
        $query = "SELECT
         u.nome as nomeauth, u.cognome as cognauth, u.uid, u.p_foto, t.topicpadre, t.tid, b.id as idblog, b.nome as nomeblog, b.descrizione, b.idautore, b.data 
         FROM blog as b, topic as t, utenti as u
         WHERE  b.idautore = u.uid AND 
         b.idtopic = t.tid AND 
         t.tid = :tid";
        $result = $pdo->prepare($query);
        $result->bindParam(':tid', $tid);
        $result->execute();
    } catch (PDOException $e) {

        echo "" . $e->getMessage() . "";
        exit();
    }

    foreach ($result as $row) {  // Creo un vettore multidimensionale

        $blogs[] = array(
            "auth" => $row["nomeauth"], "cognome" => $row["cognauth"], "authid" => $row["idautore"], "idblog" => $row["idblog"], "p_foto" => $row["p_foto"],
            "nome" => $row["nomeblog"], "desc" => $row["descrizione"], "data" => $row["data"], "id" => $row["tid"], "padre" => $row["topicpadre"]
        );
    }


    if ($blogs[0]["nome"] == "") { // Se non è stato trovato nessun blog, avverto che non è presente, includo l'errore e esco

        echo '<div class="alert alert-danger" role="alert">In questa categoria non è presente ancora nessun blog! <a href="createblog.php" class="alert-link">Creane tu uno!</a></div>';
        include 'errore404.php';
        exit();

    } else {

        include './templates/topic.html.php';  // Se invece è stato trovato almeno uno, includo il template dei topic
        exit(); // ..infine esco dallo script
    }
}