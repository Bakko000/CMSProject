<? /* @controller: ./utenti.php */ ?>

<!DOCTYPE html>
<html lang="it">

<head>
    <?php include_once './elementicomuni/headcomune.php'; ?>
    <title>My Powerful Blog - Profilo di
        <? echo $user['nome']; ?>
        <? echo $user['cognome'] ?>
    </title>
</head>

<body>

    <?php include_once './particomuni/header.php'; ?>

    <!-- PARTE DI CONTROLLO E PROFILO UTENTE -->

    <?php if ($user['nome'] != "") : ?>  <!-- Se l'utente esiste -->
    <h2 class="card-category"> Profilo Utente </h2>
    <div class="card card-profile card-plain">
        <div class="card-avatar border-white">
            <?php if ($user['p_foto'] == "") echo '<img src="./img/user-symbol.png">';   // Se l'utente ha una foto, la mostro, altrimenti mostro quella di default
                else echo  '<img src="data:image/jpeg;base64,' . base64_encode($user['p_foto']) . '"/>'; ?>
        </div>
        <div class="card-body">
            <!---->
            <h4 class="card-title">
                <? echo $user['nome']; ?>
                <? echo $user['cognome'] ?>
            </h4>
            <h6 class="card-category">Username:
                <? echo $user['username'] ?>
            </h6>
            <? if($punteggio>0): ?>
            <h6 class="card-category">Punteggio:
                <!-- Rimando alla pagina del punteggio -->
                <? echo "<a title='Visualizza i post più votati' href='punteggio.php?action=viewpost&id=".$_GET['id']."'>".$punteggio."</a>" ?>
            </h6>
            <? endif; ?>
            <p class="card-description">
                Scrivi un' email a questo utente:
                <? echo "<a href='mailto:" . $user["email"] . "'>" . $user["email"] . "</a>" ?>
        </div>
        <div class="card" data-background="color" data-color="orange">
            <div class="card-body">
                <span class="category-social pull-right">
                    <i class="fa fa-quote-right"></i>
                </span>
                <div class="clearfix"></div>
                <p class="card-description">
                    <? if ($user['bio'] != "") {  // Se c'è la bio, la mostro, sennò mostro il default
                            echo $user['bio'];
                        } else echo "Nessuna nota per questo utente.."; ?>
                </p>
            </div>
        </div>
        </ul>

        <br>

        <!-- PARTE DI CONTROLLO UTENTE CORRENTE E MODIFICA PROFILO -->

        <? session_start();  // Inizializzo la sessione
            if ($_SESSION["uid"] == $_GET["id"]) : ?> <!-- Se l'utente corrente è titolare del suo profilo, mostro il pulsante di modifica profilo -->
        <a id="modprof" class="btn btn-info btn-round" href="acp.php?settings" title="Modifica il tuo profilo"><i
                class="fa fa-cog fa-spin"></i>Modifica
            il profilo</a>
        <? endif; ?>
        <br>
        <br>

       <!--- PARTE LISTA DEI BLOG -->

        <h6 class="justify-content-center">Lista dei blog creati dall'utente</h6>



        <nav class="nav justify-content-center">
            <?php if (count($blogs) == 0) {   // Se ci sono blogs, li mostro per ognuno, altrimenti stampo il messaggio
                    echo "Nessun blog trovato";
                } else {
                    foreach ($blogs as $blog) {  // Per ogni elemento dell'array multidimensionale blogs, associo a un nuovo vettore $blog
                        echo "<h5><a class='nav-link active' href='blog.php?action=viewblog&id=" . $blog['id'] . "''>" . $blog["nome"] . "</a></h5>";
                    }
                }
                ?>
        </nav>

    </div> <!-- Se l'utente non esiste, o c'è un tentativo fraudolento, mostra l'errore -->
    <?php else : ?><div class="alert alert-danger" role="alert">L'utente da te cercato non risulta nei nostri database, 
        <a href="javascript:history.back(-1)" class="alert-link">Torna indietro</a>
    </div>
    <?php endif; ?>
    <br>
    <?php include_once './particomuni/footer.php';  ?> <!-- Include il footer -->
</body>

</html>