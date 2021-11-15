<? /* @controller: ./login.php
      @view: ./login.php 
      Unico file, insieme a register.php, che non rispetta il MVC, questo avviene poichè abbiamo deciso di inserire eventuali errori
      nella stessa pagina e per poter rimandare in caso di login già eseguito, alla pagina di index */ 
      
      ?>

<!DOCTYPE html>
<html lang="it">

<head>
    <?php include_once './elementicomuni/headcomune.php'; ?>
    <title>My Powerful Blog - Accedi</title>
</head>

<body>
    <div class="container">
        <!-- E' il blocco iniziale comune a tutto il sito -->
        <?php include_once './particomuni/header.php'; ?>

    </div>
    <main role="main" class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-5 col-12 ml-auto">
                <h3 class="pb-3 mb-4 mt-4 font-italic border-bottom">
                    Accedi al sito
                </h3>
                <form class="form-signin" method="post" id="iscriviti_form" enctype="multipart/form-data"
                    action="?action=login">
                    <input type="hidden" name="azione" value="accesso" />
                    <h1 class="h3 mb-3 font-weight-normal">Effettua il login
                    </h1>

                    <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Indirizzo Email"
                        pattern="[^@]+@[^@]+\.[a-zA-Z]{2,}" required="" type="email" required autofocus maxlength="60">
                    <input type="password" id="inputPassword" name="pswd" class="form-control marginbottom"
                        placeholder="Password" minlength="6" required maxlength="25">
                    <br>
                    <button class="btn btn-lg btn-info btn-block" name="action" type="submit">Accedi
                    </button>
                </form>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-7 col-12 mr-auto">
                <div class="info info-horizontal">
                    <div class="icon">
                        <i class="fa fa-pencil-square-o"></i>
                    </div>
                    <div class="description">
                        <h3> Perchè effettuare l'accesso? </h3>
                        <p>Registrandoti e, conseguentemente, facendo l'accesso puoi usufruire di tutti i servizi
                            disponibili per l'utente registrato, come ad esempio creare blog,
                            scrivere post, commentare, votare... e per fare tutto ciò basta entrare in "My Powerful
                            Blog".</p>
                    </div>
                </div>
                <div class="info info-horizontal">
                    <div class="icon">
                        <i class="fa fa-picture-o"></i>
                    </div>
                    <div class="description">
                        <h3> Multimedialità al primo posto </h3>
                        <p>Puoi caricare fino a due foto all'interno dei tuoi post, e una foto come profilo!</p>
                    </div>
                </div>
                <div class="info info-horizontal">
                    <div class="icon">
                        <i class="fa fa-user-secret"></i>
                    </div>
                    <div class="description">
                        <h3> Ci teniamo alla tua sicurezza </h3>
                        <p>Cerchiamo di effettuare quanti più controlli possibili affinchè la piattaforma sia sicura.
                        </p>
                    </div>
                </div>
            </div>

            <?php

            /* Modulo di Accesso Utente */

            $g_action = trim(htmlspecialchars($_GET["action"]));
            $p_action = trim(htmlspecialchars($_POST["action"]));

            if (isset($g_action) && $_GET['action'] == 'login' && !isset($p_action)) {
                //Se l'utente prova a forzare il link lo avverto dell'errore e lo invito a tornare indietro.
                echo '<div class="alert alert-danger" role="alert">Non puoi raggiungere questo link direttamente! <a href= "javascript:history.back(-1)" class="alert-link">Torna indietro</a></div>';

                exit();
            } elseif (isset($g_action) && $g_action == 'login') {  /* Vuoi accedere e hai cliccato sul bottone */

                require_once('config/database.php');

                session_start();

                if (isset($_SESSION['uid'])) {  // Utente già loggato, rimanda alla pagina di login
                    echo "<script> window.location.replace('index.php') </script>";
                    exit;
                }

                if (isset($p_action)) {
                    $email = trim(htmlspecialchars($_POST['email'])) ?? '';
                    $password = trim(htmlspecialchars($_POST['pswd'])) ?? '';

                    if (!isset($email) || !isset($password)) {
                        $msg = 'Inserisci username e password';
                    } else {
                        $query = "
            SELECT *
            FROM utenti
            WHERE email = :email
        ";

                        $check = $pdo->prepare($query);
                        $check->bindParam(':email', $email);
                        $check->execute();

                        $user = $check->fetch(PDO::FETCH_ASSOC);

                        if (!$user || password_verify($password, $user['password']) === false) {
                            $msg = 'Credenziali utente errate';
                        } else {
                            session_regenerate_id();
                            $_SESSION['id'] = session_id();
                            $_SESSION['uid'] = $user['uid'];
                            $_SESSION['user'] = $user['username'];
                            $_SESSION['email'] = $user['email'];

                            echo "<script> window.location.replace('index.php') </script>";
                            exit;
                        }
                    }
                    //Avverto l'utente dell'errore che sta commettendo mostrandogli un messaggio di alert
                    echo '<div class="alert alert-danger mt-0 ml-3" role="alert">' . $msg . '</div>';
                }
            }
            ?>

        </div>
    </main>

    <!-- Includo il footer comune a tutto il sito -->
    <?php include_once './particomuni/footer.php'; ?>

</body>

</html>