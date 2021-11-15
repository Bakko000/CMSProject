<? // @controller acp.php ?>
<!DOCTYPE html>

<!-- PAGINA DI INDEX E PRINCIPALE DELL'ACP -->

<html lang="it">

<head>
    <?php include_once './elementicomuni/headcomune.php'; ?>
    <title>My Powerful Blog - Pannello di Amministrazione
    </title>
</head>

<body>
    <?php include_once './particomuni/header.php'; ?>
    <h2 class="benvenutopannello">Benvenuto nel pannello di Amministrazione !</h2>
    <h3 id="pannello"><i>Da qui puoi decidere se eliminare tuoi blogs, modificare i tuoi dati oppure escludere degli
            utenti dalla
            visione di un tuo determinato blog</i></h3>

            <!-- Sezione dei bottoncini per navigare il pannello -->
    <div class="justify-content-center" id="bottonipcp">
        <button class="acpopt btn btn-outline-info btn-round"><a href="?blogs"><i class="fa fa-wrench"></i>Gestione
                Blogs</a></button>
        <button class="acpopt btn btn-outline-info btn-round"><a href="?settings"><i class="fa fa-user"></i>Il Tuo
                Profilo</a></button>
        <button class="acpopt btn btn-outline-info btn-round"><a href="?deleteacc"><i class="fa fa-trash"></i>Cancella
                Account</a></button>
                <? if($totblogs>0): ?>  <!-- Non ha senso mostrare i pulsanti di ban e sban se non si hanno blog -->
        <button class="acpopt btn btn-outline-info btn-round"><a href="?ban"><i class="fa fa-ban"></i>Ban</a></button>
        <button class="acpopt btn btn-outline-info btn-round"><a href="?sban"><i class="fa fa-users"></i>Rimuovi
                Ban</a></button>
                <? endif; ?>
    </div>

</body>


</html>