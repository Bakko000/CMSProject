//Codice JQuery per la modifica dei commenti con Ajax
$(document).ready(function() {

    $(".editcomm").each(function() { //Sul click del bottone "modifica commento"

        $(this).on("click", function(e) {

            e.preventDefault();

            pid = $("#hidpost").val();
            cid = this.getAttribute('data-value');
            testo = ($("p[id=" + cid + "]").text()).trim();

            $("p[id=" + cid + "]").html('<textarea class="form-control textarea-limited w-75" placeholder="Qui puoi scrivere il commento." rows="1" maxlength="500" name="text" required id="testocomm">' + testo + '</textarea>');
            $('#testocomm').keyup(function() {
                $('#testocomm').text($('#testocomm').val());
            });

            $(this).html('<a href="javascript:void(0);" id="savecomm"><i class="fa fa-check"></i>Salva</a>');

        });
    });
});

// Dopo averlo inserito dinamicamente, richiama il DOM con il nuovo pulsante per salvare le modifiche
$(document).on('click', '#savecomm', function(e) {
    e.preventDefault();

    if (testo != "") {
        $.ajax({ //Chiamata Ajax
            method: "POST",
            url: "../ajax/commenti.php?edit",
            data: {
                post_id: pid,
                com_id: cid,
                testo: testo
            }

        }).done(function(response) {
            if(response != "0"){
            
            $("#savecomm").remove();
            $("p[id=" + cid + "]").html('<p>' + testo + '</p>');

            } else {
                alert("Non puoi modificare il commento di un altro utente");
            }
        });
    } else {
        alert("Non puoi inserire un commento senza testo.");
    }
});