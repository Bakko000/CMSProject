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
        <button class="acpopt btn btn-outline-info btn-round"><a href="?add=<? echo $_GET['bid'] ?>"><i class="fa fa-users"></i>Aggiungi
                Coautori</a></button>
                
    </div>
    <br />
    <br />
    <? if ($users[0]["id"] != "") : ?>  <!-- Se ci sono coautori, mostrali -->
    <div class="card text-center" style="width: 24rem;">
        <h4 class="card-title">Lista dei CoAutori</h4>
        <?php foreach ($users as $user) : ?>  <!-- Itera su ogni risultato -->
        <div class="card-body">
            <a class="btn btn-primary btn-neutral" href="utenti.php?id=<?php echo $user["id"] ?>"
                title="Visualizza Utente"><?php echo $user["nick"] ?></a>
            <form action="modcp.php?bid=<? echo $_GET['bid'] ?>&delete=<?php echo $user['id'] ?>" method="post">
                <input class="btn btn-info border" type="submit" name="submit" value="Revoca" class="submit">
            </form>
        </div>
        <?php endforeach; ?>
        <? else : ?>
       <!-- Non c'è alcun coautore, segnalalo, invita l'utente ad aggiungerlo -->
        <p class="text-center"><i>Nessun coautore al momento per questo Blog</i>&nbsp;&nbsp;&nbsp;<a
                href="modcp.php?add=<? echo $_GET['bid'] ?>">Aggiungine uno</a></p>
        <? endif; ?>
    </div>

</body>


</html>