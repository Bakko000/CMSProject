<? /* @controller: ./topics.php */ ?>


<!DOCTYPE html>
<html lang="it">

<head>
    <?php include_once './elementicomuni/headcomune.php'; ?>
    <title>My Powerful Blog -
        <? echo $topics[0]["nome"]; ?>
    </title>

</head>

<body>
    <?php include_once './particomuni/header.php'; ?>

    <div class="section section-project cd-section" id="projects">
        <div class="projects-1">
            <div class="container">
                <div class="row" id="categorie">
                    <div class="col-md-10 ml-auto mr-auto text-center">
                        <div class="project-pills row">
                            <?php foreach ($topics as $topic) : ?> <!-- Per ogni elemento in topics, lo associo al nuovo vettore $topic -->
                            <? if (has_sons($topic["id"]) || has_father($topic["id"])) { // Se ha figli o ha padri
                                    echo '<ul class="nav nav-pills nav-pills-danger justify-content-center col-6 d-block">';
                                } else {
                                    echo '<ul class="nav nav-pills nav-pills-danger justify-content-center col-12 d-block">';
                                }
                                ?>
                              
                              <!-- PARTE RELATIVA ALLA CATEGORIA -->

                            <h6 id="titolih6">Tutti i blog della categoria</h6>
                            <button type="button" class="btn btn-danger btn-round"><?php echo $topic["nome"] ?></button>
                            </ul>
                            <? if (has_sons($topic["id"])) : ?> <!-- Se ha figli... -->
                            <ul class="nav nav-pills nav-pills-danger justify-content-center col-6 d-block">
                                <h6 id="titolih6">Vedi anche le sottocategorie</h6>
                                <? show_sons($topic["id"]) . '</ul>'; ?>  <!-- Mostrali -->
                                <? endif; ?>
                                <? if (has_father($topic["id"])) : ?> <!-- Se invece ha un padre, è una sottocategoria.. -->
                                <ul class="nav nav-pills nav-pills-danger justify-content-center col-6 d-block">
                                    <h6 id="titolih6">Visita anche la categoria padre</h6>
                                    <? show_father($topic["id"]) . '</ul>'; ?> <!-- Mostralo -->
                                    <? endif; ?>
                                    <?php endforeach; ?>
                                    <?php echo $topic["topicpadre"] ?>
                                    <?php if (count($topics) == 0) : ?> <!-- Se il vettore originario non aveva elementi, significa che non ci sono blog per la categoria, mostra il messaggio -->
                                    <li class="nav-item"><a class="btn btn-round btn-danger">Nessun argomento trovato da
                                            questo
                                            blog.</a></li>
                                    <?php endif; ?>
                                </ul>
                                <h4 class="text-align-center"><?php echo $topic["descr"] ?></h4>
                                <br>
                        </div>
                    </div>
                    <div class="space-top"></div>
                    <div class="row">

                    <!-- PARTE RELATIVA ALLA LISTA DEI BLOGS -->

                        <?php foreach ($blogs as $blog) : ?>
                        <div class="card card-blog m-4">
                            <div class="card-body text-center">
                                <h4 class="card-title">
                                    <a href='blog.php?action=viewblog&id=<?php echo $blog['idblog'] ?>'><?php echo $blog['nome']; ?>
                                </h4>

                                <div class=" card-description">
                                    <?php echo $blog['desc']; ?>
                                </div>
                                <hr>
                                <div class=" card-footer">
                                    <div class="author">
                                        <?php if ($blog['p_foto'] == "") echo '<img class="avatar img-raised" src="./img/user-symbol.png">'; // se l'utente autore del blog ha una foto, mostrala, altrimenti default
                                            else echo  '<a href="utenti.php?id=' . $blog['authid'] . '"><img class="avatar img-raised" src="data:image/jpeg;base64,' . base64_encode($blog['p_foto']) . '"/></a>'; ?>
                                        <span><a href='utenti.php?id=<?php echo $blog['authid'] ?>'>
                                                <? echo $blog['auth'] . " " . $blog['cognome']; ?>
                                            </a>
                                        </span>
                                        </a>
                                    </div>
                                    <div class=" stats">
                                        <i class="fa fa-clock-o" aria-hidden="true"></i>
                                        <?php echo date("d/m/Y", strtotime($blog['data'])); ?> <!-- Stampa la data come giorno/mese/anno e porta l'orario -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
</body>

</html>
