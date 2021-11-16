<header class="blog-header py-3">
    <div class="row flex-nowrap justify-content-between align-items-center">
        <div class="col-4 pt-1">
            <?php
            session_start();
            //Mostro il bottone del Profilo in caso di login effettuato correttamente
            if (isset($_SESSION["id"]) && isset($_SESSION["user"]) && isset($_SESSION["email"])) { ?>
            <a class="btn btn-info btn-round" href='utenti.php?id=<? echo $_SESSION['uid']; ?>' title="Vai al tuo
                Profilo"><i class="fa fa-user-o"></i>
                <? echo $_SESSION["user"]; ?>
            </a>
            <a class="btn btn-info btn-round" href="acp.php" title="Amministra Blog"><i
                    class="fa fa-cog"></i>Amministrazione
            </a>
            <?php } else { ?>
            <!--Altrimenti mostro il pulsante iscriviti -->
            <a class="btn btn-sm btn-secondary" href="register.php" title="Iscriviti al sito"><i
                    class="fa fa-pencil-square-o"></i>Iscriviti </a>
            <?php } ?>
        </div>
        <div class="col-4 text-center">
            <a href="index.php" title="Torna alla homepage">
                <img src="logogrande.png" alt="Logo MPB" class="logoMPBheader">
            </a>
        </div>
        <div class="col-4 d-flex justify-content-end align-items-center">
            <?php
            if (isset($_SESSION["id"]) && isset($_SESSION["user"]) && isset($_SESSION["email"])) { ?>
            <a class="btn btn-sm btn-secondary" href="..?action=logout" title="Esci"><i
                    class="fa fa-sign-out"></i>Logout
            </a>
            <?php } else { ?>
            <a class="btn btn-sm btn-secondary" href="login.php" title="Sei già iscritto? Effettua l'accesso"><i
                    class="fa fa-sign-in"></i>Accedi </a>
            <?php } ?>
        </div>
    </div>
</header>
