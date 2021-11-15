<? /* @controller: ./createblog.php */ ?>

<!DOCTYPE html>
<html lang="it">

<head>
    <title>My Powerful Blog - Nuovo blog</title>
    <!-- Script relativo alla funzionalità del tasto cancella -->
    <script type="text/javascript" src="../js/cancella.js"></script>
    <!-- Codice con la parte Ajax per la creazione di una nuova categoria -->
    <script type="text/javascript" src="../js/nuovacategoria.js"></script>
    <!-- Codice per il suggerimento della nuova categoria (per evitare di creare categorie duplicate) -->
    <script tye="text/javascript" src="../js/resetfoto.js"></script>
    <!-- Includo piccolo script di cancellazione per le foto -->
    <script>
    function suggerisci(param) {
        /* Questo piccolo script permette di cambiare la selectbox quando si clicca
                                              sulla categoria suggerita o la si crea*/
        cat = parseInt(param);
        $("#topic").val(cat);
    }
    </script>

</head>

<body>
    <!-- Form per la creazione di un nuovo blog -->
    <form action="?createblog" method="post" enctype="multipart/form-data">
        <!-- Enctype necessario all'upload di foto -->
        <div class="main" id="mainnuovopost">
            <div class="section" id="nuovopostdiv">
                <div class="container">
                    <h3 class="text-center">Crea un nuovo blog</h3>
                    <div class="row">
                        <div class="col-md-5 col-sm-5 ml-auto mr-auto mt-1">
                            <!-- Input foto 1-->
                            <h6 class="text-center">Puoi aggiungere una foto iniziale del blog</h6>
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
                        </div>
                        <div class="col-lg-7 col-md-7 col-sm-5 col-12 ml-auto">
                            <div class="">
                                <h6 class="categoriamodifica">Categoria <span class="icon-danger">*</span></h6>
                                <h6 id="tooltip"></h6>
                                <div id="tags-21">
                                    <div class="bootstrap-tagsinput">
                                        <select class="form-control border-input" name="topic" id="topic" required>
                                            <option value="notset">Seleziona una categoria</option>
                                            <!-- Mostro tutti i topic già presenti nella piattaforma. -->
                                            <?php foreach ($topics as $topic) : ?>
                                            <?php echo "<option id=" . $topic['id'] . " value=" . $topic['id'] . ">" . $topic['nome'] . "</option>"; ?>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <br />
                            <div class="">
                                <!-- Parte per la creazione di una nuova categoria gestito da Ajax (nuovacategoria.js) -->
                                <h6 id="creanuova">Oppure crea nuova:</h6>
                                <div id="tags-2">
                                    <div class="form-group">
                                        <input class="form-control border-input" name="nuovotopic" maxlength="50"
                                            id="nuovotopic" placeholder="Scrivi nome categoria">
                                        <button class="btn btn-info btn-neutral form-control border-input"
                                            id="crea">Crea</button>
                                    </div>
                                </div>
                            </div>
                            <br />
                            <div class="">
                                <div class="form-group">
                                    <h6>Titolo del blog <span class="icon-danger">*</span></h6>
                                    <input type="text" id="text" name="titolo" class="form-control border-input"
                                        placeholder="Titolo del blog" maxlength="50" required>
                                </div>

                                <div class="">
                                    <h6>CoAutore (opzionale)</h6>
                                    <div id="tags-2">
                                        <div class="bootstrap-tagsinput">
                                            <select class="form-control border-input" name="coauth" id="coauth"
                                                required>
                                                <option value="notset">Seleziona Utente</option>
                                                <?php foreach ($users as $user) : ?>
                                                <?php echo "<option id=" . $user['uid'] . " value=" . $user['uid'] . ">" . $user['username'] . "</option>"; ?>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <br />
                                <div class="form-group">
                                    <h6>Descrizione del blog <span class="icon-danger">*</span></h6>
                                    <textarea id="text" name="descrizione" class="form-control border-input"
                                        placeholder="Descrizione del blog" maxlength="300" rows="3" required></textarea>
                                    <br />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row buttons-row">
                        <div class="col-md-4 col-sm-4">
                            <button class="btn btn-outline-danger btn-block btn-round" type="reset">Cancella</button>
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