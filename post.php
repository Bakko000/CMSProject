<?php

// @view: ./templates/post.html.php

//Includo il file con le funzioni appositamente realizzate.
require_once('./inc/functions.php');


if (!isset($_GET["action"]) || $_GET["action"] != "viewpost" || !isset($_GET["id"]) || $_GET["id"] == "" || !is_numeric($_GET["id"])) {

    include 'errore404.php';
    exit();
} elseif (isset($_GET['action']) && ($_GET['action'] == 'viewpost')) {

    $pid = trim(htmlspecialchars($_GET['id']));
    $uid = htmlspecialchars($_SESSION['uid']);

    include_once './config/database.php';
    //Query che recupera dal database tutti i paramentri che servono a mostrare correttamente i commenti.
    try {

        $query = "SELECT b.id as bid, u.nome, u.cognome, u.uid, u.username, u.p_foto, c.testo, c.idauth, c.comid, c.data FROM commenti as c, utenti as u, post as p, blog as b
        WHERE c.idauth = u.uid 
        AND c.idpost = p.pid
        AND p.idblog = b.id
        AND c.idpost=:pid ORDER BY c.data DESC";
        $result = $pdo->prepare($query);
        $result->bindParam(':pid', $pid);
        $result->execute();
    } catch (PDOException $e) {

        echo "" . $e->getMessage() . "";
        exit();
    }
    //Recupero tutti i risultati dei commenti
    foreach ($result as $row) {
        $commenti[] = array(
            "comid" => $row["comid"], "nomeauth" => $row["nome"], "cognauth" => $row["cognome"], "nickauth" => $row["username"], "fotoauth" => $row["p_foto"], "bid" => $row["bid"],
            "testo" => $row["testo"],
            "idauth" => $row["idauth"], "data" => $row["data"]
        );
    }


    try {
        //Query che recupera dal database tutti i paramentri che servono a mostrare correttamente il post.
        $query = "SELECT p.nome as nomepost, p.pid, p.testo, p.font, p.foto1, p.foto2, p.authid as autorepost, p.punteggio, p.data, p.idblog as blogpadre,
        b.id as bid, b.nome as nomeblog, b.descrizione, b.idautore, u.uid, u.nome as nomeautore, u.cognome as cognomeautore, u.username, u.p_foto, u.email as authmail, u.bio, t.t_nome as categoria, t.tid, t.topicpadre as padrecategoria
        FROM utenti as u, blog as b, post as p, topic as t
        WHERE u.uid = p.authid AND  
        p.idblog = b.id AND
        t.tid = b.idtopic AND 
        p.pid = :pid";
        $result = $pdo->prepare($query);
        $result->bindParam(':pid', $pid);
        $result->execute();
    } catch (PDOException $e) {

        echo "" . $e->getMessage() . "";
        exit();
    }

    $post = $result->fetch();
    //Controllo che l'id del post (pid) sia valido, altrimenti redirigo alla pagina 404.
    if ($post["pid"] < 1) {
        include 'errore404.php';
        exit();
    } else {

        include './templates/post.html.php';
        exit();
    }
}