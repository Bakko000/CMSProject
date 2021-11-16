<?php
//Codice relativo alla ricerca istantanea fatta con l'ausilio di Ajax
include '../config/database.php';

trim(htmlspecialchars($_GET["q"]));

if (isset($_GET["q"])) {
  $inizio = $_GET["q"] . "%";   // Devo assegnare tramite concatenazione le wildcards
  $meta = "%" . $_GET["q"] . "%";
  $fine = "%" . $_GET["q"];


  //La query si occupa di recuperare le informazioni sui post basandosi sulla similarità dei parametri immessi

  try {

    $query = "SELECT nome, pid FROM post WHERE nome LIKE :inizio OR nome LIKE :meta OR nome LIKE :fine";
    $result = $pdo->prepare($query);
    $result->bindParam(":inizio", $inizio);
    $result->bindParam(":meta", $meta);
    $result->bindParam(":fine", $fine);
    $result->execute();
  } catch (PDOException $e) {

    echo "" . $e->getMessage() . "";
    exit();
  }

  echo "<a class='dropdown-item border-bottom badge badge-info text-white font-weight-bold'>Post</a>";

  while ($q = $result->fetch()) {

    if ($q["nome"] == "") {
      $response = "";
    } else {
      $response = "<a class='dropdown-item border-bottom' href='post.php?action=viewpost&id=" . $q["pid"] . "'>" . $q["nome"] . "</a>";
    }
    echo $response;
  }



  // La query si occupa di recuperare le informazioni sui Blog  basandosi sulla similarità dei parametri immessi

  try {

    $query = "SELECT nome, id FROM blog WHERE nome LIKE :inizio OR nome LIKE :meta OR nome LIKE :fine";
    $result = $pdo->prepare($query);
    $result->bindParam(":inizio", $inizio);
    $result->bindParam(":meta", $meta);
    $result->bindParam(":fine", $fine);
    $result->execute();
  } catch (PDOException $e) {

    echo "" . $e->getMessage() . "";
    exit();
  }

  echo "<a class='dropdown-item border-bottom badge badge-info text-white font-weight-bold'>Blog</a>";

  while ($qu = $result->fetch()) {

    if ($qu["nome"] == "") {
      $response = "";
    } else {
      $response = "<a class='dropdown-item border-bottom' href='blog.php?action=viewblog&id=" . $qu["id"] . "'>" . $qu["nome"] . "</a>";
    }
    echo $response;
  }
}