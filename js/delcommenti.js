//Codice JQuery per l'eliminazione dei commenti con Ajax
$(document).ready(function() {

  $(".deletecomm").each(function() { //Sul click del bottone "elimina commento"

        $(this).on("click", function(e) {

                e.preventDefault();

                procedi = confirm("Sei sicuro di cancellare il tuo commento?");
                if (procedi) {

                    pid = $("#hidpost").val();
                    cid = this.getAttribute('data-value');

                    $.ajax({    //Chiamata Ajax
                        method: "POST",
                        url: "../ajax/commenti.php?delete",
                        data: {
                            post_id: pid,
                            com_id: cid
                        }

                    }).done(function(response) {

                        if(response != "0") {   //Se la chiamata va a buon fine rimuovo il relativo commento

                        eliminato = document.getElementById(cid);

                        eliminato.classList.add("none");

                        eliminati = $(".none");

                        for (var i = 0; i < eliminati.length; i++) {

                            daeliminare = eliminati[i];

                            daeliminare.remove();

                            }
                        }

                        else {

                            alert("Non puoi eseguire l'azione. Il commento non esiste o non hai i permessi necessari.");

                        }


                    });
                } 

            });
        });
});