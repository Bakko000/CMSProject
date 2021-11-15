//Codice JQuery relativo al corretto funzionamento del bottone "rimuovi foto"
$(document).ready(function() {
    $("#rf1").on("click", function(){  // Primo input 

        if($("#img1").val()!="") {
            $("#img1").val(''); //Toglie la foto inserita
        }

    });

    $("#rf2").on("click", function(){  // Secondo input

        if($("#img2").val()!="") {
            $("#img2").val(''); //Toglie la foto inserita
        }
    });

});