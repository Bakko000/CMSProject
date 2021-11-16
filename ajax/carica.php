<?php

include_once("../config/database.php");

// $paginazione viene inviato da AJAX con POST


    if(isset($_POST["paginazione"])) {

$paginazione = $_POST["paginazione"];
$posts = 2;

$offset = ($paginazione * $posts) + 6; //L'offset serve a mostrare correttamente i post successivi (aggiungo 4 che sono i primi post mostrati di default)

//Eseguo la query per mostrare i post al clic del bottone "mostra altro"
try {
    $query = "SELECT b.nome as nomeblog, b.id as blogid, p.pid, p.testo, p.font, p.nome as titolopost, p.foto1, p.foto2, p.data, p.authid, u.nome as unome,
        u.cognome as ucogn, u.username as nick, u.p_foto as authorfoto,  u.uid 
        FROM utenti as u, post as p, blog as b
        WHERE u.uid = p.authid AND
        p.idblog = b.id 
        ORDER BY p.data DESC
        LIMIT " . $offset . "," . $posts . "";
    $check = $pdo->prepare($query);
    $check->execute();
} catch (PDOException $e) {

    echo "" . $e->getMessage() . "";
    exit();
}

$row = $check->fetchAll();

if ($row[0]["pid"] > 0) {  // Se ci sono post

    foreach ($row as $post) { ?>

<!-- HTML relativo ai post che vengono mostrati dopo il clic "mostra altro" -->
<div class="col-md-6">
    <div class="card-body"><a href="post.php?action=viewpost&id=<?php echo $post['pid'] ?>">
            <div class="card" data-background="image"
                style='background-image: url(<?php echo "data:image/jpeg;base64," . base64_encode($post['foto1']); ?>'
                )>
                <div class="card-body">
                    <h4>
                        <? echo $post['titolopost'] ?>
                    </h4>
                    <h6 class="card-category">
                        <i class="fa fa-calendar"></i>
                        <?php echo date("d/m/Y H:i", strtotime($post['data'])); ?>
                    </h6>
                    <div class="author">
                        <a href="utenti.php?id=<? echo $post['authid'] ?>">
                            <? echo '<img class="avatar img-raised" src="data:image/jpeg;base64,' . base64_encode($post['authorfoto']) . '"/>'; ?>
                            <span>
                                <? echo $post["unome"] . " " . $post["ucogn"] . " " . "(" . $post["nick"] . ")" ?>
                        </a></span>
                    </div>
                    <h6 class="bold"><a
                            href="blog.php?action=viewblog&id=<?php echo $post["blogid"] ?>"><?php echo $post["nomeblog"] ?></a>
                    </h6>
                    <p class="card-description" style="font-family: <? echo $post["font"] ?>">
                        <?php echo substr_replace($post["testo"], "...", 180) ?>
                    </p>
                    <a href="post.php?action=viewpost&id=<?php echo $post['pid'] ?>"
                        class="btn btn-danger btn-round card-link">
                        <i class="fa fa-book" aria-hidden="true"></i> Leggi
                    </a>
                </div>
            </div>
        </a>
    </div>
</div>




<?

    }
} else {

    echo "0";  // E qui la chiamata comunica che non c'è altro
}


} else {
    echo "Non puoi raggiungere questo link direttamente!";

}



?>