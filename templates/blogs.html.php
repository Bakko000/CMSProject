<!-- @controller blog.php -->

<!DOCTYPE html>
<html lang="it">

<head>
    <?php include_once './elementicomuni/headcomune.php'; ?>
    <title>My Powerful Blog -
        <? echo $blog["nome"]; ?>
    </title>

    <script src="../js/delpost.js"></script> <!-- Lo script permette di cancellare con AJAX post dal proprio blog -->

</head>

<body>


    <?php include_once './particomuni/header.php'; ?>

    <div class="section section-project cd-section" id="projects">
        <div class="projects-1">
            <div class="container">
                <div class="row" id="categorie">
                    <div class="col-md-8 ml-auto mr-auto text-center rounded-lg <?php if ($blog['fotob'] != "") echo "text-body font-weight-bold opacity-100";
                                                                                else echo "text-body";  ?>  p-4"
                        data-background="image"
                        style='background-image: linear-gradient(rgba(255,255,255,.7), rgba(255,255,255,.7)), url(<?php echo "data:image/jpeg;base64," . base64_encode($blog['fotob']); ?>'
                        )>
                        <ul class="nav nav-pills nav-pills-danger justify-content-center">
                            <?php foreach ($topics as $topic) : ?>
                            <!-- Qui mostro la categoria del blog -->
                            <a href="topics.php?action=viewtopic&tid=<?php echo $topic["id"] ?>">
                                <button type="button"
                                    class="btn btn-danger btn-round"><?php echo $topic["nome"] ?></button>
                            </a>
                            <?php endforeach; ?>
                            <?php if (count($topics) == 0) : ?>
                            <li class="nav-item"><a class="btn btn-round btn-danger">Nessun argomento trovato da questo
                                    blog.</a></li>
                            <?php endif; ?>

                        </ul>

                        <!-- STAMPO INFORMAZIONI SUI BLOG -->
                        <h2 class="mt-4 mb-4"> <?php echo $blog["nome"] ?> </h2>
                        <h6 href='utenti.php?id=<? echo $author[' uid']; ?>
                            '><a href="utenti.php?id=<?php echo $author['uid'] ?>">
                                <?php if ($author['p_foto'] == "") echo '<img src="./img/user-symbol.png">'; # Controllo sulla foto dell'utente
                                else echo  '<img src="data:image/jpeg;base64,' . base64_encode($author['p_foto']) . '"/>'; ?><?php echo " " . $author["nome"] . " " . $author["cognome"] ?></a>
                        </h6>
                        <h6>Creato in data: <?php echo date("d/m/Y", strtotime($blog['data'])); ?></h6>
                        <!-- Stampo la data -->
                        <h5><?php echo $blog["descrizione"] ?></h5>
                        <br>
                        <? if ($_SESSION["uid"] == $author["uid"] || is_coauth($_SESSION['uid'], $blog["id"])) : ?>
                        <!-- Se l'utente corrente è autore del blog o coautore -->
                        <a id="newpost" class="btn btn-info btn-round" href="createblog.php" title="Crea nuovo blog"><i
                                class="fa fa-plus"></i>Nuovo Blog</a>
                        <a id="newpost" class="btn btn-info btn-round" href="nuovopost.php" title="Crea nuovo post"><i
                                class="fa fa-pencil"></i>Nuovo Post</a>
                        <? endif; ?>
                        <? if (is_auth($_SESSION['uid'], $_GET['id'])) : ?>
                        <!-- Se l'utente corrente è autore -->
                        <a id="newpost" class="btn btn-info btn-round" href="modcp.php?bid=<? echo $_GET['id']; ?>"
                            title="Gestisci Blog"><i class="fa fa-cog"></i>Moderazione
                        </a>
                        <? endif; ?>
                        <br>
                    </div>
                    <!--<?php if ($blog['fotob'] != "") echo  '<div class="col-sm-4 ml-auto mr-auto mb-auto mt-auto"><img class="w-100 rounded" src="data:image/jpeg;base64,' . base64_encode($blog['fotob']) . '"/></div>'; ?> -->
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <?php foreach ($posts as $post) : ?>
            <!-- Ottengo tutte le info sui post  -->
            <div class="col-md-5" id="<? echo $post['pid'] ?>">
                <div class="card card-blog">
                    <div class="card-image">
                        <a href="post.php?action=viewpost&id=<?php echo $post['pid'] ?>">
                            <?php if ($post['foto1'] == "") echo '<img src="./img/newpost.jpg">'; // Controllo foto del post
                                else echo  '<img src="data:image/jpeg;base64,' . base64_encode($post['foto1']) . '"/>'; ?>
                        </a>
                    </div>
                    <div class="card-body text-center">
                        <form action="?salvamodifiche" method="post">
                            <h4 class="card-title">
                                <? echo $post['titolo'] ?>
                            </h4>
                            <!-- Questo input mi serve per conservare l'id del blog -->
                            <input type="hidden" class="hidden" id="hidblog" name="bid"
                                value="<?php echo trim(htmlspecialchars($_GET["id"])) ?>" />
                            <h6 class="card-category bold">
                                <i class="fa fa-calendar"></i>
                                <? echo date("d/m/Y", strtotime($post['data'])); ?>
                            </h6>
                            <div class="card-description" style="font-family: <? echo $post[" font"] ?>">
                                <?php echo substr_replace($post["testo"], "...", 220) ?>
                                <!-- Stampo il testo limitato -->
                            </div>
                            <div class="card-footer">
                                <a class="btn btn-danger btn-round"
                                    href="post.php?action=viewpost&id=<?php echo $post['pid'] ?>"><i
                                        class="fa fa-newspaper-o"></i>Leggi</a>
                                <? if ($_SESSION["uid"] == $author["uid"]) : ?>
                                <!-- Collegamento allo script di deletepost, se sono autore posso vedere il pulsantino -->
                                <a class="deletepost" data-value="<?php echo trim(htmlspecialchars($post['pid'])) ?>"
                                    class="btn btn-danger btn-round" href="javascript:void(0);"><i
                                        class="fa fa-trash"></i>Elimina</a>
                                <?php endif; ?>
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