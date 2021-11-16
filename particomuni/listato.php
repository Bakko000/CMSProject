<?php

include_once './config/database.php';

//Query per mostrare la lista dei blog
try {

  $query = "SELECT * FROM blog";
  $result = $pdo->prepare($query);
  $result->execute();
} catch (PDOException $e) {

  echo "" . $e->getMessage() . "";
  exit();
}


foreach ($result as $row) {
  $blogs[] = array("id" => $row["id"], "nome" => $row["nome"], "descrizione" => $row["descrizione"]);
}

//Query per mostrare la lista degli argomenti (topic)
try {

  $query = "SELECT tid, t_nome FROM topic WHERE topicpadre IS NULL ORDER BY t_nome ASC";
  $result = $pdo->prepare($query);
  $result->execute();
} catch (PDOException $e) {

  echo "" . $e->getMessage() . "";
  exit();
}


foreach ($result as $row) {
  $topics[] = array("id" => $row["tid"], "nome" => $row["t_nome"]);
}

?>

<!-- Colonna dei blog -->
<div class="col-1">
    <!-- Barra di ricerca instantanea (Ajax) -->
    <h5>Cerca</h5>
    <form>
        <input class="nav-item dropdown form-control border-input" type="text" size="30"
            onkeyup="showResult(this.value)" placeholder="Effettua una ricerca" pattern="[A-Za-z0-9]{1,15}">
        <div id="livesearch" class="rounded ">
        </div>
    </form>

    <!-- Colonna dei blog  -->

    <h4 class="font-italic">Blog
    </h4>
    <ul id="listaBlog" class="list-group list-group-flush">
        <?php foreach ($blogs as $blog) { ?>
        <li style="cursor: pointer" class="list-group-item">
            <a href="<? echo " blog.php?action=viewblog&id=" . $blog['id'] . "" ?>">
                <? echo ucwords(htmlspecialchars($blog["nome"])); ?>
            </a>
        </li>
        <?php } ?>
    </ul>

    <!-- Colonna delle categorie -->

    <br>
    <h4 class="font-italic">Categorie
    </h4>
    <ul id="listaCategorie" class="list-group list-group-flush">
        <?php foreach ($topics as $topic) { ?>
        <li style="cursor: pointer" class="list-group-item">
            <a href="<? echo " topics.php?action=viewtopic&tid=" . $topic['id'] . "" ?>">
                <? echo ucwords(htmlspecialchars($topic["nome"])); ?>
            </a>
        </li>
        <?php } ?>
    </ul>
</div>