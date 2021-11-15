<? // @controller: acp.php ?>

<!DOCTYPE html>
<html lang="it">

<!-- SEZIONE DI REVOCA DELL'ESCLUSIONE (SBAN) DI UTENTI -->

<head>
    <title>Gestisci Permessi Utenza</title>
</head>

<body>
    <br>
    <h5 class="text-center">Seleziona il blog e l'utente a cui vuoi restituire il privilegio di commentare</h5>
    <h4 class="text-center">Blog</h4>
    <form class="text-center" action="acp.php?sban&action=sbanuser" method="post">
        <select class="form-control border-input w-25 ml-auto mr-auto mt-1" name="blog" id="blog" required>
            <option value="notset">Seleziona un blog</option>
            <?php foreach ($blogs as $blog) : ?> <!-- Genera i blog -->
            <?php echo "<option id=" . $blog['id'] . " value=" . $blog['id'] . ">" . $blog['nome'] . "</option>"; ?>
            <?php endforeach; ?>
        </select>
        <div class="text-center" style="width: 24rem;">
            <h4 class="text-center card-title">Utente da includere</h4>
            <select class="form-control border-input w-100 ml-auto mr-auto" name="banuser" id="banuser" required>
                <option value="notset">Seleziona un utente</option>
                <?php foreach ($users as $user) : ?>  <!-- Per ogni valore nell'array users, associalo a quello nuovo user -->
                <?php echo "<option id=" . $user['id'] . " value=" . $user['id'] . ">" . $user['nick'] . "</option>"; ?>
                <?php endforeach; ?>
            </select>
        </div>
        <input class="text-center btn btn-info border" type="submit" name="submit" value="Sbanna" class="submit">
        </div>
    </form>
    <br>
    <br>
    <br>
</body>

<!-- Includo il footer comune a tutto il sito -->
<?php include_once './particomuni/footer.php'; ?>


</html>