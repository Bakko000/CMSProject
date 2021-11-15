<?php //@controller acp.php ?>

<!DOCTYPE html>
<html lang="it">

<head>
    <title>Elimina Account</title>
</head>

<body>
    <h4 class="text-center">Sei sicuro di voler eliminare il tuo account?</h4>
    <form class="text-center" action="?deleteacc" method="post">
        <input class="btn btn-info border" type="submit" name="submit" value="Elimina" class="submit">
    </form>
    <br>
    <br>
    <br>

    <!-- Includo il footer comune a tutto il sito -->
    <?php include_once './particomuni/footer.php'; ?>

</body>

</html>