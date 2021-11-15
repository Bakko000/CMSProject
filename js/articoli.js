$(document).ready(function() {

    var conta = 0; // Contatore

    function carica() {
        $.ajax({ //Chiamata Ajax
            method: "POST",
            url: "ajax/carica.php",
            data: {
                paginazione: conta
            }
        }).done(function(response) {

            if (response == "0") { // Se non ci sono altri post, rimuovo il pulsante

                $("#carica").css("display", "none");
            } else {

                $("#post").append(response); // Appendo i risultati come post
                conta++; // Incremento la variabile di paginazione
            }
        });
    }

    $("#carica").on("click", function(e) { // Al click del pulsante, chiamo la funzione carica

        e.preventDefault();
        carica();
    });

});