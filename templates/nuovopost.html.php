<? /* @controller nuovopost.php */ ?>

<!DOCTYPE html>
<html lang="it">

<head>
    <title>My Powerful Blog - Nuovo post</title>

    <script tye="text/javascript" src="../js/cancella.js"></script> <!-- Includo piccolo script di cancellazione -->

    <script tye="text/javascript" src="../js/resetfoto.js"></script>
    <!-- Includo piccolo script di cancellazione per le foto -->

    <script type="text/javascript" src="../js/font.js"></script>
    <!-- Questo script si occupa di cambiare il testo aggiungendo il font scelto sull'evento change della select box -->
</head>

<body>

    <form action="?publish" method="post" enctype="multipart/form-data">
        <!-- Enctype necessario all'upload di foto -->
        <div class="main" id="mainnuovopost">
            <div class="section" id="nuovopostdiv">
                <div class="container">
                    <h3 class="text-center">Crea un nuovo post</h3>
                    <div class="row">
                        <div class="col-md-5 col-sm-5">
                            <!-- Input foto 1-->
                            <h6>Puoi aggiungere un massimo di due foto</h6>
                            <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                                <div class="fileinput-new thumbnail img-no-padding"
                                    style="max-width: 370px; max-height: 250px;">
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail img-no-padding"
                                    style="max-width: 370px; max-height: 250px;"></div>
                                <div>
                                    <span class="btn btn-outline-default btn-round btn-file"><span
                                            class="fileinput">Seleziona immagine</span><input type="file" id="img1"
                                            name="img1" accept="image/*" max-file-size="8192"></span>
                                    <a id="rf1" class="btn btn-link btn-danger fileinput-exists"
                                        data-dismiss="fileinput"><i class="fa fa-times"></i> Rimuovi foto</a>
                                </div>
                            </div>
                            <!-- Input foto 2-->
                            <div class="fileinput fileinput-new text-center" data-provides="fileinput" id="inputfoto2">
                                <div class="fileinput-new thumbnail img-no-padding"
                                    style="max-width: 370px; max-height: 250px;">
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail img-no-padding"
                                    style="max-width: 370px; max-height: 250px;"></div>
                                <div>
                                    <span class="btn btn-outline-default btn-round btn-file"><span
                                            class="fileinput">Seleziona immagine</span><input type="file" id="img2"
                                            name="img2" accept="image/*" max-file-size="8192"></span>
                                    <a id="rf2" class="btn btn-link btn-danger fileinput-exists"
                                        data-dismiss="fileinput"><i class="fa fa-times"></i> Rimuovi foto</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7 col-sm-7">
                            <div class="form-group">
                                <h6>Titolo <span class="icon-danger">*</span></h6>
                                <input type="text" name="titolo" maxlength="45" class="form-control border-input"
                                    placeholder="Titolo del post" required>
                            </div>
                            <div class="form-group">
                                <h6>Blog <span class="icon-danger">*</span></h6>
                                <div class="input-group border-input">

                                    <!-- PARTE RELATIVA AI BLOG DOVE POSTARE -->
                                    <select name="blog" id="blog">
                                        <option value="notset">Seleziona un blog</option>
                                        <?php foreach ($blogs as $blog) : ?>
                                        <!-- Itero sugli elementi -->
                                        <?php echo "<option id=" . $blog['id'] . " value=" . $blog['id'] . ">" . $blog['nome'] . "</option>"; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <br />

                                <div class="form-group">
                                    <h6>Font <span>(opzionale)</span></h6>
                                    <div class="input-group border-input">
                                        <!-- PARTE RELATIVA AI FONT COME POSTARE -->
                                        <select name="font" id="font" required>
                                            <option value="notset">Seleziona un font</option>
                                            <option id="Georgia" value="Georgia, serif">Georgia</option>
                                            <option id="Verdana" value="Verdana, sans-serif">Verdana</option>
                                            <option id="Times New Roman" value="Times New Roman, serif">Times New Roman
                                            </option>
                                            <option id="Tahoma" value="Tahoma, sans-serif">Tahoma</option>
                                            <option id="Arial" value="Arial, sans-serif">Arial</option>
                                            <option id="Impact" value="Impact,Charcoal, sans-serif">Impact</option>
                                            <option id="Brush Script MT" value="Brush Script MT, cursive">Brush Script
                                                MT</option>
                                            <option id="Segoe UI" value="Segoe UI, sans-serif">Segoe UI</option>
                                            <option id="Consolas" value="Consolas, monaco, monospace">Consolas</option>
                                            <option id="Helvetica" value="Helvetica Neue, Helvetica, Arial, sans-serif">
                                                Helvetica</option>
                                            <option id="Copperplate Gothic"
                                                value="Copperplate, Copperplate Gothic Light, fantasia">
                                                Copperplate Gothic</option>
                                        </select>
                                    </div>

                                    <br />
                                    <div class="form-group">
                                        <h6>Testo del post <span class="icon-danger">*</span></h6>
                                        <textarea class="form-control textarea-limited"
                                            placeholder="Qui puoi scrivere il contenuto del post." rows="12"
                                            maxlength="2500" name="text" required id="text"></textarea>
                                        <h5><small><span id="textarea-limited-message" class="pull-right">Massimo 2500
                                                    caratteri</span></small></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row buttons-row">
                        <div class="col-md-4 col-sm-4">
                            <button class="btn btn-outline-danger btn-block btn-round" id="reset"
                                type="reset">Cancella</button>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <input class="btn btn-outline-primary btn-block btn-round" name="submit" value="Salva"
                                type="submit" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </form>
</body>

</html>