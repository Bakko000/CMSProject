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

    <!--- SEZIONE PRINCIPALE E PANNELLI -->
    <h2 class="benvenutopannello">Benvenuto nel pannello di Moderazione !</h2>
    <h3 id="pannello"><i>Da qui puoi decidere se eliminare o aggiungere CoAutori</i></h3>
    <div class="justify-content-center" id="bottonipcp">
    <button class="acpopt btn btn-outline-info btn-round"><a href="?bid=<? echo $_GET['add'] ?>"><i class="fa fa-home"></i>
                Home</a></button>
   <!-- FINE SEZ -->
        <br>
        <h5 class="text-center mt-4">Seleziona l'utente che diventerà coautore.</h5>
        <h4 class="text-center card-title">Utenti</h4>
        <form class="text-center" action="modcp.php?add=<? echo $_GET["add"] ?>&action=addcoauth" method="post">
            
            <select class="form-control border-input w-25 ml-auto mr-auto" name="coauth" id="coauth" required>
                <option value="notset">Seleziona un utente</option>
                <?php foreach ($users as $user) : ?> <!-- Genera le opzioni nella select box -->
                <?php echo "<option id=" . $user['id'] . " value=" . $user['id'] . ">" . $user['nick'] . "</option>"; ?>
                <?php endforeach; ?>
            </select>
            <input class="text-center btn btn-info border mt-2" type="submit" name="submit" value="Aggiungi"
                class="submit">
        </form>
        <br>
        <br>
        <br>
    </div>



</body>


</html>