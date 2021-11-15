<?php // @controller modcp.php ?>
<!DOCTYPE html>
<html lang="it">

<head>
    <?php include_once './elementicomuni/headcomune.php'; ?>
    <title>My Powerful Blog - Pannello di Moderazione
    </title>
</head>

<body>
    <?php include_once './particomuni/header.php'; ?>

    
    <h2 class="benvenutopannello">Benvenuto nel pannello di Moderazione !</h2>
    <h3 id="pannello"><i>Da qui puoi decidere se eliminare o aggiungere CoAutori</i></h3>
    <div class="justify-content-center" id="bottonipcp">
        <? if(isset($_GET["add"])): ?>
        <button class="acpopt btn btn-outline-info btn-round"><a href="?add=<? echo $_GET['add'] ?>"><i class="fa fa-users"></i>Aggiungi Coautori</a></button>
        <button class="acpopt btn btn-outline-info btn-round"><a href="?bid=<? echo $_GET['add'] ?>"><i class="fa fa-home"></i>
                Home</a></button>
                <? else: ?>
                    <button class="acpopt btn btn-outline-info btn-round"><a href="?add=<? echo $_GET['bid'] ?>"><i class="fa fa-users"></i>Aggiungi Coautori</a></button>
                    <button class="acpopt btn btn-outline-info btn-round"><a href="?bid=<? echo $_GET['bid'] ?>"><i class="fa fa-home"></i>
                Home</a></button>
            <? endif; ?>
</div>
<!-- Mostra il salvataggio delle modifiche con successo -->
<div class="alert alert-success" role="alert">Modifiche salvate! <a href="javascript:history.back(-1)" class="alert-link">Torna indietro</a></div>

</body>


</html>