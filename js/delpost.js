$(document).ready(function() {

    $(".deletepost").each(function() {  // Per ogni tastino di cancellazione 

      $(this).on("click", function(e) { // al click, esegui:

                e.preventDefault();

                procedi = confirm("Sei sicuro di cancellare il tuo post?");
                if (procedi) {

                    pid = this.getAttribute('data-value');
                    bid = $("#hidblog").val();

                    $.ajax({    //Chiamata Ajax
                        method: "POST",
                        url: "../ajax/delpost.php",
                        data: {
                            post_id: pid,
                            blog_id: bid
                        }

                    }).done(function(response) {

                        if(response != "0") {  // Se ci sono dei risultati dal codice php

                        eliminato = document.getElementById(pid);  // Ottiene il post da eliminare

                        eliminato.classList.add("none"); // Aggiunge una classe 

                        eliminati = $(".none"); 

                        for (var i = 0; i < eliminati.length; i++) {

                            daeliminare = eliminati[i];  // Con un ciclo tutti gli elementi con la classe none, vengono rimossi dal DOM

                            daeliminare.remove();
                        }

                      } else {

                            alert("Non puoi eseguire l'azione. Il post probabilmente non è tuo o non esiste.");

                      }

                    });
                } 

            });
        });
});