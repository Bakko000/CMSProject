<!-- In questo file si trova la parte che si trova premendo "Il tuo profilo" nel pannello di amministrazione. -->
<html>

<!-- @controller acp.php  -->

<head>
</head>

<body>
    <br>
    <h3 id="pannello">Qui di seguito puoi vedere e modificare i dati del tuo profilo</h3>
    <p id="pannello">Ricordati di cliccare sul bottone "Salva" per salvare le modifiche.</p>
    <?php if (isset($msg)) : ?>
    <p><?php echo $msg ?></p>
    <?php endif; ?>
    <div class="wrapper" id="formprofilo">
        <div class="row">
            <div class="col-md-6 ml-auto mr-auto">
                <form class="settings-form" action="?savemod" method="post">
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>Nome <span class="icon-danger">*</span></label>
                                <input type="text" class="textbox form-control border-input" placeholder="Nome"
                                    name="nome" value="<?php echo $user['nome'] ?>" maxlength="24"
                                    pattern="[A-Za-z]{1,24}">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>Cognome <span class="icon-danger">*</span></label>
                                <input type="text" class="textbox form-control border-input" placeholder="Cognome"
                                    name="cognome" value="<?php echo $user['cognome'] ?>" maxlength="18"
                                    pattern="[A-Za-z]{1,18}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Username <span class="icon-danger">*</span></label>
                        <input type="text" class="textbox form-control border-input" placeholder="Username"
                            name="username" value="<?php echo $user['username'] ?>" maxlength="25"
                            pattern="[A-Za-z0-9_.-]{3,25}$">
                        <h5><small><span id="textarea-limited-message" class="pull-right">Massimo 25 caratteri
                                </span></small></h5>
                    </div>
                    <div class="form-group">
                        <label>Email <span class="icon-danger">*</span></label>
                        <input type="text" class="textbox form-control border-input" placeholder="Email" name="email"
                            value="<?php echo $user['email'] ?>" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                            maxlength="40">
                    </div>
                    <div class="form-group">
                        <label>Biografia <span class="icon-danger">*</span></label>
                        <textarea name="note" class="textbox form-control textarea-limited"
                            placeholder="Inserisci una biografia nel tuo account." rows="3"
                            maxlength="150"><?php echo $user['bio'] ?></textarea>
                        <h5><small><span id="textarea-limited-message" class="pull-right">Massimo 150 caratteri
                                </span></small></h5>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="textbox submit btn btn-wd btn-info btn-round" class="submit"
                            name="save" value="Salva">Salva</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

<!-- Includo il footer comune a tutto il sito -->
<?php include_once './particomuni/footer.php'; ?>


</html>