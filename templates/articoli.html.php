<!-- @controller: index.php -->



<? if (isset($_SESSION) && isset($_SESSION["uid"])) : ?>  <!-- se l'utente corrente è loggato -->
    <a id="newpost" class="btn btn-info btn-round" href="createblog.php" title="Crea nuovo blog"><i
            class="fa fa-plus"></i>Nuovo Blog</a>
    <? endif; ?>


    <!-- SEZIONE CHE MOSTRA GLI ULTIMI POST NEI BLOG -->
    <div class="col-md-8 mt-4 blog-main">
        <h3 class="pb-3 mb-4 font-italic border-bottom">
            Ultime attività dai blog
        </h3>

        <div class="row">
            <?php foreach ($posts as $post) :  ?>  <!-- GENERA I POST CON UN LIMITE DI 0,6 (all'inizio cioè vedo i primi 6 post del DB) -->
            <div class="col-md-6">
                <div class="card-body"><a href="post.php?action=viewpost&id=<?php echo $post['pid'] ?>">
                        <div class="card" data-background="image"
                            style='background-image: url(<?php echo "data:image/jpeg;base64," . base64_encode($post['foto1']); ?>'
                            )>
                            <div class="card-body">
                                <h4>
                                    <? echo $post['titolo'] ?>
                                </h4>
                                <h6 class="card-category">
                                    <i class="fa fa-calendar"></i>
                                    <?php echo date("d/m/Y H:i", strtotime($post['data'])); ?> <!-- Echa correttamente la data -->
                                </h6>
                                <div class="author">
                                    <a href="utenti.php?id=<? echo $post['authorid'] ?>">
                                        <?php if ($post['a_foto'] == "") echo '<img class="avatar img-raised" src="./img/user-symbol.png" />'; ##-- Controllo sulla foto dell'autore
                                            else echo '<img class="avatar img-raised" src="data:image/jpeg;base64,' . base64_encode($post['a_foto']) . '"/>'; ?>
                                        <span>
                                            <? echo $post["nomeauth"] . " " . $post["cognauth"] . " " . "(" . $post["authnick"] . ")" ?>
                                    </a></span>
                                </div>
                                <h6 class="bold"><a
                                        href="blog.php?action=viewblog&id=<?php echo $post["idblog"] ?>"><?php echo $post["blog"] ?></a>
                                </h6>
                                <p class="card-description" style="font-family: <? echo $post["font"] ?>">
                                    <?php echo substr_replace($post["testo"], "...", 180) ?> <!-- Testo limitato -->
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
            <?php endforeach; ?>
        </div>


    </div>




    <!-- (QUI*) mostro gli ultimi 5 articoli pubblicati da qualsiasi utente (dal più recente) -->
    <div id="post" class="col-md-8 mt-4 blog-main row">

    </div>

    <script type="text/javascript" src="../js/articoli.js"></script>  <!-- Script con la chiamata ajax per mostrare gli articoli QUI* -->

    <a class="btn btn-lg btn-info btn-block mt-4 mb-4" id="carica" href="#">Vedi altri post</a>


    <!-- Includo il footer comune a tutto il sito -->
    <?php include_once './particomuni/footer.php'; ?>