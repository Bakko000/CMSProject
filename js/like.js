$(document).ready(function() {
var clicked = false;
var punteggio = parseInt(($("#likeinput").val()));
    // Carica il pulsante di like 
    
    $("#like").one("click", function (e) {      // .one Cioè solamente una volta (certamente se si ricarica la pagina si può ricliccare..ma ci pensa il controller php in /ajax)
        e.preventDefault();
        if (!clicked) {
        clicked = true;
        pid = $("#hidpost").val();
        $.ajax({
            method: "POST",
            url: "../ajax/likes.php",
            data: {
                pid: pid,
                punteggio: punteggio+1
            }
        }).done(function(response) {
            if(response!='0'){
            punteggio = punteggio+1;
            $("#punteggio").html(punteggio);
            $("#like").toggleClass('fa fa-thumbs-o-up fa-3x mr-4 fa fa-thumbs-up fa-3x mr-4');
            } 
    });
} 
    });

    $("#unlike").one("click", function (e) {
        e.preventDefault();
        if (!clicked) {
        clicked = true;
        pid = $("#hidpost").val();
        $.ajax({
            method: "POST",
            url: "../ajax/likes.php",
            data: {
                pid: pid,
                punteggio: punteggio-1
            }
        }).done(function(response) {
            if(response!='0'){
                punteggio = punteggio-1;
            $("#punteggio").html(punteggio);
            $("#unlike").toggleClass('fa fa-thumbs-o-down fa-3x ml-1 mr-5 fa fa-thumbs-down fa-3x ml-1 mr-5');
        } 
    });
} 
    });

});
