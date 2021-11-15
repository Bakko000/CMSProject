//Codice JQuery relativo al corretto funzionamento del bottone "cancella" nei form (es. in nuovo blog)
$(document).ready(function() {
    
    $textarea = $("#text");

    $("#reset").on("click", function(){

        if($textarea.val()!="") {

            $textarea.val(''); //Resetta le textarea

        }


    });

});