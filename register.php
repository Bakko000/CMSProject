<? /* @controller: ./register.php
      @view: ./register.php 
      Unico file, insieme a login.php, che non rispetta il MVC, questo avviene poichè abbiamo deciso di inserire eventuali errori
      nella stessa pagina e per poter rimandare in caso di login già eseguito, alla pagina di index */ ?>



<?php include_once './config/database.php'; ?>
<?php include_once './inc/functions.php'; ?>
<!-- Includo il file per connettersi al database -->
<!DOCTYPE html>
<html lang="it">

<head>
    <?php include_once './elementicomuni/headcomune.php'; ?>
    <title>My Powerful Blog - Registrati</title>
    <script src="js/filecheck.js" type="text/javascript">

    </script> <!-- Script di controllo del file (estensione e dimensione) -->
</head>

<body>
    <div class="container">
        <!-- Includo lo header comune a tutto il sito -->
        <?php include_once './particomuni/header.php'; ?>
    </div>
    <main role="main" class="container">
        <div class="row">

            <div class="col-md-8 blog-main mb-5">
                <h3 class="pb-3 mb-4 mt-4 font-italic border-bottom">
                    Iscriviti al sito!
                </h3>

                <form class="form-signin" method="post" action="?action=reg" enctype="multipart/form-data"
                    id="iscriviti_form">

                    <input type="hidden" name="azione" value="iscrizione" />

                    <h5 class="h5 mb-3 font-weight-italic">Compila tutti i campi per proseguire.</h5>

                    <label for="username" class="sr-only">Username *</label>
                    <input type="text" id="inputUsername" name="username" class="form-control marginbottom"
                        placeholder="Username" maxlength="25" pattern="[A-Za-z0-9_.-]{3,25}$" required>


                    <label for="inputNome" class="sr-only">Nome *</label>
                    <input type="text" id="inputNome" name="nome" class="form-control" placeholder="Nome" maxlength=30"
                        pattern="[A-Za-z]{1,24}" required>

                    <label for="inputCognome" class="sr-only">Cognome *</label>
                    <input type="text" id="inputCognome" name="cognome" class="form-control marginbottom"
                        placeholder="Cognome" maxlength="20" pattern="[A-Za-z]{1,18}" required>

                    <label for="inputEmail" class="sr-only">Email *</label>
                    <input id="inputEmail" name="email" class="form-control" placeholder="Indirizzo Email"
                        pattern="[^@]+@[^@]+\.[a-zA-Z]{2,}" maxlength="60" required="" type="email">

                    <label for="inputPassword" class="sr-only">Password *</label>
                    <input type="password" id="inputPassword" name="pswd" class="form-control" placeholder="Password"
                        minlength="6" maxlength="25" required>

                    <label for="confirmPassword" class="sr-only">Conferma Password *</label>
                    <input type="password" id="confirmPassword" name="confermapswd" class="form-control marginbottom"
                        placeholder="Conferma Password" minlength="6" maxlength="25" required>

                    <label for="inputTelefono" class="sr-only">Telefono</label>
                    <input type="text" id="inputTelefono" name="telefono" class="form-control marginbottom"
                        placeholder="Telefono" minlength="7" maxlength="13" pattern="[0-9]{7,13}">
                    <br>

                    <div class="form-group">
                        <label for="inputBiografia" class="sr-only">Biografia</label>
                        <textarea class="form-control marginbottom" id="inputBiografia" name="about_me"
                            placeholder="La tua Biografia" rows="3" maxlength="150"></textarea>
                        <h5><small><span id="textarea-limited-message" class="pull-right">Massimo 150 caratteri
                                </span></small></h5>
                    </div>

                    <div class="form-group">
                        <label for="inputFoto">Immagine profilo</label>
                        <input type="file" id="file_id" class="form-control-file" name="imgprofilo" accept="image/*"
                            onchange="check_fileupload(this, 'file_id');" max-file-size="4096">
                            <i>max 3MB <b>solo: .raw,.jpeg,.jpg,.png,.tiff</b></i>
                    </div>
                    <div id="err"></div>

                    <button class="btn btn-lg btn-info btn-block" name="action" type="submit">Invia</button>
                </form>

            </div>

            <?php

      /* Modulo di Registrazione Utente */

      session_start();

      if (isset($_SESSION['uid'])) {  // Se sei già loggato, ti rimanda all'index
        echo "<script> window.location.replace('index.php') </script>";
      }

      if (isset($_GET['action']) && $_GET['action'] == 'reg' && !isset($_POST['action'])) {

        echo '<div class="alert alert-danger" role="alert">Non puoi raggiungere questo link direttamente!</div>';
        exit();

        exit();
      } elseif (isset($_GET['action']) && $_GET['action'] == 'reg') {  /* Vuoi registrarti e hai cliccato sul bottone */

        require_once('config/database.php');  /* Ottengo la connessione al db */

        if (isset($_POST['action'])) {
          $nome = $_POST['nome'] ?? '';
          $cognome = $_POST['cognome'] ?? '';
          $username = $_POST['username'] ?? '';
          $password = $_POST['pswd'] ?? '';
          $p_foto = $_POST["foto"] ?? '';
          $email = $_POST["email"] ?? '';
          $bio = $_POST["about_me"] ?? '';
          $tel = $_POST["telefono"] ?? '';

          $pwdLenght = strlen($password); /* Conto i caratteri della password */

          if (upcheckfiles("imgprofilo", 3145728)) { // Controllo formato e dimensioni max 3MB

            $imgprofilo = file_get_contents($_FILES['imgprofilo']['tmp_name']);  // Ottiene l'immagine 

            if (!isset($username) || !isset($password)) {
              $msg = 'Compila tutti i campi %s';
            } elseif ($pwdLenght < 6 || $pwdLenght > 25) {
              $msg = 'Lunghezza minima password 6 caratteri.
                Lunghezza massima 25 caratteri';
            } elseif ($password != $_POST['confermapswd']) {
              $msg = 'Le password non coincidono';
            } else {
              $password_hash = password_hash($password, PASSWORD_BCRYPT);  /* Hash della password */

              $query = "        /* Controllo se l'utente esiste già */
            SELECT *
            FROM utenti
            WHERE username = :username OR email = :email
        ";

              $check = $pdo->prepare($query);
              $check->bindParam(':username', $username);
              $check->bindParam(':email', $email);
              $check->execute();

              $user = $check->fetchAll(PDO::FETCH_ASSOC);

              if (count($user) > 0) {
                $msg = 'Username o email già in uso';
              } else {  /* Non esiste, allora lo inserisco */
                $query = "INSERT INTO utenti SET nome=:nome, cognome=:cognome, username=:username, password=:password, email=:email,
            tel=:tel, bio=:bio, p_foto=:p_foto;";

                $check = $pdo->prepare($query);
                $check->bindParam(':nome', $nome);
                $check->bindParam(':cognome', $cognome);
                $check->bindParam(':username', $username);
                $check->bindParam(':p_foto', $p_foto);
                $check->bindParam(':password', $password_hash);
                $check->bindParam(':email', $email);
                $check->bindParam(':tel', $tel);
                $check->bindParam(':bio', $bio);
                $check->bindParam(':p_foto', $imgprofilo);
                $check->execute();

                if ($check->rowCount() > 0) {  // Se la query va a buon fine, rimando alla pagina di login
                  echo "<script> window.location.replace('login.php') </script>";
                } else {
                  $msg = 'Problemi con l\'inserimento dei dati %s';
                }
              }
            }
          } else {
            echo '<div class="alert alert-danger" role="alert">Dimensione o formato errati. 3MB consentiti e solo formati immagini/fotografici.</div>';
            exit();
          }
          echo '<div class="alert alert-danger" role="alert">' . $msg . '</div>'; /* Messaggio di errore */
        }
      }


      ?>




        </div>
    </main>
    <!-- includo il footer generale del sito -->
    <?php include_once './particomuni/footer.php'; ?>

</body>

</html>
