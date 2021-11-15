<? /* @controller: ./post.php */ ?>
<!DOCTYPE html>
<html lang="it">

<head>
    <?php include_once './elementicomuni/headcomune.php'; ?>
    <title>My Powerful Blog -
        <? echo $post['nomepost']; ?>
    </title>

    <!--  Script relativo all'eliminazione dei commenti -->
    <script src="../js/delcommenti.js"></script>
    <!--  Script relativo ai like dei post -->
    <script src="../js/like.js"></script>
    <!--  Script relativo alla modifica del post -->
    <script type="text/javascript" src="./js/modificapost.js"></script>
    <!--  Script relativo al ban degli utenti  -->
    <script type="text/javascript" src="./js/ban.js"></script>
    <!--  Script relativo alla modifica del commento  -->
    <script type="text/javascript" src="./js/modificacommento.js"></script>

</head>

<body>
    <?php include_once './particomuni/header.php'; ?>
    <div class="wrapper" id="articolodiv">
        <div class="main" id="articolodiv">
            <div class="section section-white">
                <div class="container">
                    <div class="row">
                        <!--  Intestazione del post  -->
                        <div class="col-md-12 ml-auto mr-auto text-center title">
                            <input type="hidden" class="hidden" id="hidpost" name="pid"
                                value="<?php echo trim(htmlspecialchars($_GET["id"])) ?>" />
                            <h1 id="titolopost">
                                <? echo $post["nomepost"] ?>

                            </h1>
                            <br />
                            <h6>Autore:
                                <a href="utenti.php?id=<? echo $post['uid'] ?>">
                                    <?php echo " " . $post['nomeautore'] . " " . $post['cognomeautore']; ?></a>
                            </h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10 ml-auto mr-auto">
                            <div class="text-center">
                                <h6 class="title-uppercase">
                                    <i class="fa fa-calendar"></i>
                                    <? echo date("d/m/Y H:i", strtotime($post['data'])) . ' - ' . "<a href='blog.php?action=viewblog&id=" . $post['bid'] . "'>" .
                                        $post['nomeblog'] . "</a>"; ?>
                                </h6>
                                <span class="badge badge-warning main-tag mb-1">
                                    <!--<? echo $post['nomeautore']; ?>-->
                                    <?php echo "<a style='color:#fff' href='topics.php?action=viewtopic&tid=" . $post['tid'] . "'><b>" . $post['categoria'] . "</b></a>" ?>
                                </span>
                            </div>
                        </div>
                        <!--  Corpo del post  -->
                        <div class="article-content">
                            <p id="testoart" class="text-justify" style="font-family: <?php echo $post["font"] ?>">
                                <? echo htmlspecialchars($post["testo"]) ?>
                            </p>
                            <!-- Questa parte di codice PHP serve a controllare se c'è una foto singola che normalmente verrebbe messa a metà pagina con l'altra foto,
                            essendocene una singola viene creato un div "col-md-12" che occupa la pagina intera -->
                            <div class="row" id="fotopost">
                                <?php if ($post['foto2'] != "") echo '<div class="col-md-6">';
                                else echo '<div class="col-md-12">'; ?>
                                <?php if ($post['foto1'] != "") echo '<img src="data:image/jpeg;base64,' . base64_encode($post['foto1']) . '"/>'; ?>
                            </div>
                            <div class="col-md-6">
                                <?php if ($post['foto2'] != "") echo '<img src="data:image/jpeg;base64,' . base64_encode($post['foto2']) . '"/>'; ?>
                            </div>
                        </div>
                        <!-- Bottone per modificare i post -->
                        <?php if (
                            isset($_SESSION) && isset($_SESSION["uid"]) && $_SESSION["uid"] == $post['uid']
                            || is_coauth($_SESSION["uid"], $post["bid"]) || is_auth($_SESSION["uid"], $post["bid"])
                        ) : ?>
                        <div id="mod">
                            <a class="btn btn-lg btn-info btn-block mt-4 mb-4" id="modifica" href="#">Modifica post</a>
                        </div>
                        <? endif ?>
                    </div>
                    <br>
                    <div class="article-footer">
                        <div class="container">

                            <div class="row">
                                <!-- Like e dislike (Ajax) -->
                                <?php if (isset($_SESSION) && isset($_SESSION["uid"])) : ?>
                                <input type="hidden" id="likeinput" value="<? echo $post['punteggio']; ?>"
                                    name="<?php echo $post["pid"]; ?>" class="like" />
                                <i id="like" class="fa fa-thumbs-o-up fa-3x mr-4">
                                </i>

                                <input type="hidden" id="unlikeinput" value="<? echo $post['punteggio']; ?>"
                                    name="<?php echo $post["pid"]; ?>" class="unlike" /><span
                                    id="<?php echo $post["pid"]; ?>"><i id="unlike"
                                        class="fa fa-thumbs-o-down fa-3x ml-1 mr-5">
                                    </i></span></span></input></input>
                                <h2 id="punteggio" class="m-0">
                                    <? echo $post['punteggio']; ?>
                                </h2>
                                <? endif; ?>
                                <!--  Link per la condivisione tramite social  -->
                                <div class="col-md-3 ml-auto">
                                    <div class="sharing">
                                        <h5>Condividi questo post</h5>
                                        <a
                                            href="https://www.facebook.com/sharer/sharer.php?u=http://webcreativedesign.altervista.org/post.php?action=viewpost&id=<?php echo $post["pid"]; ?>">
                                            <button class="btn btn-just-icon btn-facebook">
                                                <i class="fa fa-facebook"> </i>
                                            </button></a>
                                        <a href="https://twitter.com/intent/tweet?url=http://webcreativedesign.altervista.org/post.php?action=viewpost&id=<?php echo $post["pid"]; ?>&text="
                                            <button class="btn btn-just-icon btn-twitter">
                                            <i class="fa fa-twitter"></i>
                                            </button>
                                        </a>
                                        <a href="https://www.linkedin.com/shareArticle?mini=true&url=http://webcreativedesign.altervista.org/post.php?action=viewpost&id=<?php echo $post["pid"]; ?>"
                                            <button class="btn btn-just-icon btn-linkedin">
                                            <i class="fa fa-linkedin"> </i>
                                            </button>
                                        </a>
                                        <a
                                            href="https://api.whatsapp.com/send?text=%0ahttp://webcreativedesign.altervista.org/post.php?action=viewpost&id=<?php echo $post["pid"]; ?>">
                                            <button class="btn btn-just-icon btn-linkedin">
                                                <i class="fa fa-whatsapp"> </i>
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <!--  Parte relativa all'autore del post con relativa descrizione  -->
                    <div class="container">
                        <div class="row" id="utentepost">
                            <div class="media">
                                <a class="pull-left" href="utenti.php?id=<? echo $post['uid'] ?>">
                                    <div class="avatar big-avatar">
                                        <?php if ($post['p_foto'] == "") echo '<img src="./img/user-symbol.png">';
                                        else echo '<img class="media-object" alt="64x64" src="data:image/jpeg;base64,' . base64_encode($post['p_foto']) . '"/>'; ?>
                                    </div>
                                    </href=>
                                    <div class="media-body">
                                        <h4 class="media-heading">
                                            <a href="utenti.php?id=<? echo $post['uid'] ?>">
                                                <? echo $post['nomeautore'] . " " . $post['cognomeautore']; ?>
                                            </a>
                                        </h4>
                                        <div class="pull-right">
                                            <a href="utenti.php?id=<? echo $post['uid'] ?>" class="btn btn-default
                                                    btn-round "> <i class="fa fa-reply"></i>
                                                Profilo</a>
                                        </div>
                                        <p>
                                            <? if ($post['bio'] != "") {
                                                echo $post['bio'];
                                            } else echo "Nessuna nota per questo utente.."; ?>
                                        </p>
                                    </div>
                            </div>
                        </div>
                        <div class="row" id="utentecomm">
                            <div class="col-md-12 ml-auto mr-auto">
                                <h3 class="text-center">Commenti</h3>
                                <div class="media media-post">
                                    <a class="pull-left author" href="javascript:;">
                                        <div class="avatar">

                                        </div>
                                    </a>
                                    <div class="media-body">
                                        <? if (isset($_SESSION) && isset($_SESSION["uid"]) && !is_banned($_SESSION["uid"], $post["bid"])) : ?>
                                        <!-- Area per inserire i commenti -->
                                        <form action="commenti.php?nuovocommento" method="post">
                                            <input class="form-control" placeholder="Scrivi qui..." rows="4" type="text"
                                                name="testo" class="form-control border-input"
                                                placeholder="Descrizione del blog" maxlength="300" required>
                                            <input type="hidden" class="hidden" id="hidpost" name="pid"
                                                value="<?php echo trim(htmlspecialchars($_GET["id"])) ?>" />
                                            <div class="media-footer">
                                                <input name="submit" value="Commenta" type="submit"
                                                    class="btn btn-info pull-right"></input>
                                            </div>
                                        </form>
                                        <? endif ?>
                                        <? if (is_banned($_SESSION["uid"], $post["bid"])) : ?>
                                        <br />
                                        <p><b>Non puoi commentare questo blog. <a
                                                    href="mailto:<?php echo $post['authmail'] ?>"> Contatta l'autore per
                                                    maggiori informazioni</a></b></p>
                                        <br />
                                        <? endif; ?>
                                    </div>
                                </div>
                                </a>
                                <? if ($commenti != null) { ?>
                                <!--  Costrutto foreach che mostra tutti gli eventuali commenti  -->
                                <?php foreach ($commenti as $commento) : ?>
                                <div class="media-body" id="<?php echo $commento['comid'] ?>">
                                    <a class="pull-left author" href="javascript:;">
                                    </a>
                                    <h5 class="media-heading">
                                        <a href="utenti.php?id=<? echo $commento['idauth'] ?>">
                                            <? echo $commento['nomeauth'] . " " . $commento['cognauth']; ?>
                                        </a>
                                    </h5>
                                    <div class="pull-right">
                                        <h6 class="text-muted text-right">
                                            <?php echo date("d/m/Y H:i", strtotime($commento['data'])); ?></h6>
                                        <?php if ($_SESSION["uid"] == $commento["idauth"]) : ?>
                                        <a href="javascript:void(0);" class="deletecomm"
                                            data-value="<? echo trim(htmlspecialchars($commento['comid'])) ?>"
                                            class="btn btn-info btn-link pull-right "> <i class="fa fa-trash"></i>
                                            Elimina</a>
                                        <a href="javascript:void(0);" class="editcomm"
                                            data-value="<? echo trim(htmlspecialchars($commento['comid'])) ?>"
                                            class="btn btn-info btn-link pull-right " id="modcomm"> <i
                                                class="fa fa-pencil"></i>
                                            Modifica</a>
                                        <?php endif; ?>
                                        <?php if (is_auth($_SESSION['uid'], $post["bid"]) || is_coauth($_SESSION['uid'], $post["bid"]) && $commento['idauth'] != $post['uid']) : ?>
                                        <?php if ($_SESSION['uid'] != $commento['idauth']) : ?>
                                        <a href="javascript:void(0);" class="banusr"
                                            data-value="<? echo trim(htmlspecialchars($commento['idauth'])) ?>"
                                            class="btn btn-info btn-link pull-right "> <i class="fa fa-ban"></i>
                                            Banna</a>
                                        <input type="hidden" class="hidden" id="hidbid" name="bid"
                                            value="<?php
                                                                                                                            echo trim(htmlspecialchars($commento['bid'])) ?>" />
                                        <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                    <p id="<?php echo $commento['comid'] ?>">
                                        <?php echo htmlspecialchars($commento['testo']); ?>
                                    </p>
                                </div>
                                <?php endforeach; ?>
                                <? } else { ?>
                                <p>Nessun Commento</p>
                                <? } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>

</html>