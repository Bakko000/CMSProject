//Codice JQuery relativo al clic del bottone ban
$(document).ready(function() {

   $(".banusr").each(function() { // each: come un for, per ogni elemento di questa classe, esegui

            $(this).on("click", function(e) {   // esegui sul click

                e.preventDefault();

                procedi = confirm("Sei sicuro di bannare l'utente? Egli non potrà più commentare questo blog.");
                if (procedi) {

                    bid = $("#hidbid").val();
                    uid = this.getAttribute('data-value');
                    pid = $("#hidpost").val();

                    $.ajax({ //Chiamata Ajax
                        method: "POST",
                        url: "./ajax/escluso.php?post",
                        data: {
                            blog_id: bid,
                            usr_uid: uid,
                            p_id: pid
                        }

                    }).done(function(response) {

                        if(response == "0") {   //Se dalla risposta ottengo 0 significa che non è possibile bannare l'utente

                        alert("Non puoi eseguire l'azione, l'utente è un autore o coautore del blog oppure è già stato escluso.");

                        } else {

                            alert("Utente escluso con successo!");

                        }


                    });
                } 

            });
        });
});