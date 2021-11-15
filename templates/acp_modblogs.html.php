<? // @controller acp.php ?>

<!DOCTYPE html>

<!--- PAGINA DI GESTIONE DEI BLOGS -->

<html lang="it">


<head>
    <title>Gestisci i tuoi blog</title>
</head>

<body>
    <div class="btn btn-info border justify-content-center" role="alert" id="bottonipcp">Attualmente hai
        <?php
        if ($totblogs > 1) {   // Controlla che ci siano blogs e quanti
            echo $totblogs . " blogs creati";
        } elseif ($totblogs == 0) {
            echo " nessun blog creato";
        } else {
            echo " solo un blog";
        }
        ?>
        <button class="btn btn-outline-info"><a href="createblog.php">Aggiungi nuovo blog</a></button>
    </div>
    <? if($totblogs>0): ?>  <!-- Solo se ci sono 1 o più blogs visualizzo altro html -->
    <p align="center">Cliccando su "<b>CoAutori</b>" si viene rimandati alla pagina per aggiungerli/rimuoverli</p>
    <div class="card text-center" style="width: 24rem;">
        <h4 class="card-title">Lista dei blog</h4>
        <?php foreach ($blogs as $blog) : ?>  <!-- Genera iterando sull'array multidimensionale -->
        <div class="card-body">
            <a class="btn btn-primary btn-neutral" href="blog.php?action=viewblog&id=<?php echo $blog["id"] ?>"
                title="Visualizza Blog"><?php echo $blog["nome"] ?></a><a href="modcp.php?bid=<? echo $blog["id"] ?>"><b>CoAutori</b></a>: <? show_coauths($blog['id']) ?>
            <form action="?blogs&deleteblog=<?php echo $blog["id"] ?>" method="post">
                <input class="btn btn-info border" type="submit" name="submit" value="Elimina Blog" class="submit">
            </form>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>


    <!-- SEZIONCINA RELATIVA AI BLOG COAUTORATI -->

<? if($totcoblogs>0): ?>  <!-- Innazitutto, vedo l'html solo se ho 1 o più blog coautorati -->
    <div class="card text-center" style="width: 24rem;">
    
        <h4 class="card-title">Blog di cui sei coautore: <?php echo $totcoblogs; ?></h4>
        <?php foreach ($coblogs as $coblog) : ?>  <!-- Qui li genero in una lista -->
        <div class="card-body">
            <a class="btn btn-primary btn-neutral" href="blog.php?action=viewblog&id=<?php echo $coblog["id"] ?>"
                title="Visualizza Blog"><?php echo $coblog["nome"] ?></a>
        </div>
        <?php endforeach; ?>
    </div>
    <? endif; ?>

</body>

<!-- Includo il footer comune a tutto il sito -->
<?php include_once './particomuni/footer.php'; ?>


</html>