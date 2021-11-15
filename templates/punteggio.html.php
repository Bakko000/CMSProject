<? /* @controller: ./punteggio.php */ ?>

<!DOCTYPE html>
<html lang="it">

<head>
    <?php include_once './elementicomuni/headcomune.php'; ?>
    <title>My Powerful Blog - Post più votati dell'Utente
    </title>

</head>

<body>
    <?php include_once './particomuni/header.php'; ?>


    <div class="section section-project cd-section" id="projects">
        <div class="projects-1">
            <div class="container">
                <div class="row justify-content-center" id="categorie">
                <h4 class="text-align-center">Di seguito, in ordine, i post più votati dell'utente</h4>
    <div class="space-top"></div>
                    <div class="row justify-content-center">

                    <!-- PARTE RELATIVA ALLE CARD CON I POST PIU' VOTATI -->
                        <?php foreach ($posts as $post) : ?>   <!-- Per ogni elemento del vettore posts, associalo ad un nuovo vettore $post -->
                        <div class="card card-blog m-2">
                            <div class="card-body text-center">
                                <h4 class="card-title">
                                    <a href='post.php?action=viewpost&id=<?php echo $post['id'] ?>'><?php echo $post['titolo']; ?>
                                </h4>
                                <div class=" card-description">
                                Punteggio: <?php echo $post['punti']; ?>
                                </div>
                                <div class=" card-footer">
                                    <div class="author">
                                        <?php if ($post['fotoauth'] == "") echo '<img class="avatar img-raised" src="./img/user-symbol.png">'; /* Controllo sulla foto dell'autore */
                                            else echo  '<a href="utenti.php?id=' . $post['authid'] . '"><img class="avatar img-raised" src="data:image/jpeg;base64,' . base64_encode($post['fotoauth']) . '"/></a>'; ?>
                                        <span><a href='utenti.php?id=<?php echo $post['authid'] ?>'>
                                                <? echo $post['autore'] . " " . $post['cognauth']; ?>
                                            </a>
                                        </span>
                                        </a>
                                    </div>
                                    <div class=" stats">
                                        <i class="fa fa-clock-o" aria-hidden="true"></i>
                                        <?php echo date("d/m/Y", strtotime($post['data'])); ?> <!-- Stampo data in formato corretto e orario -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>

</html>