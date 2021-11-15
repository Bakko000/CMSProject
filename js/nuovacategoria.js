//Script relativo alla creazione di una nuova categoria
$(document).ready(function() {
    $("#nuovotopic").one("click", function(e) {   // .one Vogliamo che si possa creare solo una categoria per volta
        e.preventDefault();
        $("#nuovotopic").after('<h5 id="suggerimento"></h5><textarea class="form-control border-input" name="descrizione" id="descrizione" placeholder="Scrivi la descrizione della categoria" maxlength="500"></textarea>');
        $("#tooltip").html('<h6 id="tooltip1">Se la categoria che vuoi creare è una sottocategoria seleziona qui la categoria padre, altrimenti lascia vuoto.</h6>');
        $(".categoriamodifica").html('<h6 id="categoriamodifica">Categoria padre (opzionale)</h6>');
        //Aggiorno i campi con il testo inserito
        $('#nuovotopic').keyup(function() {
            $('#nuovotopic').text($('#nuovotopic').val());
        });
        $('#descrizione').keyup(function() {
            $('#descrizione').text($('#descrizione').val());
        });
    });

    $("#crea").on("click", function(e) {
        e.preventDefault();
        t_nome = $("#nuovotopic").text();
        descrizione = $("#descrizione").text();
        if (t_nome != "" && descrizione != "") {
            if ($('#topic').val() == "notset") { //Se l'utente seleziona il topic padre lo inserisco, altrimenti sarà una categoria indipendente
                topicpadre = null;
            } else {
                topicpadre = $("#topic").children(":selected").attr("id");
            }
            $.ajax({ //Chiamata Ajax
                method: "POST",
                url: "../ajax/nuovacategoria.php",
                data: {
                    t_nome: t_nome,
                    descrizione: descrizione,
                    topicpadre: topicpadre
                }
            }).done(function(response) {
                
                if(response=="errore") {   // Da nuovacategoria.php, "errore" se i caratteri sono troppi
                    
                    alert("Sei andato oltre il limite caratteri consentito");

                } else {

                if(response!="" && response!=null) {  // se è null o "" significa che php non ha dato risposta=categoria o desc esistono già
                var json = response;
                var obj = JSON.parse(json);
                if(obj["nome"] != null && obj["nome"] != "") { // se è null o "" significa che php non ha dato risposta=categoria o desc esistono già
                $('#topic').append("<option id='"+obj.id+"' value='"+obj.id+"'>"+obj.nome+"</option>"); // Appendo la nuova categoria
                suggerisci(obj.id); // Utilizzando la stessa funzione dei suggerimenti, cambio la value nella select box con la categoria appena creata
                $('#tooltip1').html('<h6>Ora puoi selezionare la categoria che hai creato in questo elenco.</h6>'); // Inserisco un aiuto per l'utente
                $('#categoriamodifica').html('<h6>Categoria <span class="icon-danger">*</span></h6>'); 
                $("#crea").off();
                $("#crea").remove();
                $('#descrizione').remove();
                $('#nuovotopic').off();
                $('#nuovotopic').remove();
                $('#creanuova').remove();
                $('#sugg').remove();
                }
                else {
                    alert("Categoria o descrizione inserite esistono già");
                 }
               } else {
                    alert("Categoria o descrizione inserite esistono già");
                 }
               }
            });
        } else {
            alert("Riempi tutti i campi!");

        }
    });

    $("#nuovotopic").keyup(function(e) {
        e.preventDefault();
        t_nome = $("#nuovotopic").text();
        if (t_nome != "") {
        $.ajax({ //Chiamata Ajax
                method: "POST",
                url: "../ajax/nuovacategoria.php?suggest",
                data: {
                    t_nome: t_nome
                } }).done(function(response) {
        var risposta = response;
        $("#suggerimento").html(risposta); //Mostro il suggerimento
    });
    }
    });
});