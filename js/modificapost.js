//Script relativo alla modifica del post
$(document).ready(function() {

    // Carica il pulsante di modifica 
    $("#modifica").on("click", function (e) {
        e.preventDefault();
        $testop = ($("#testoart").text()).trim();
        $titolop = ($("#titolopost").text()).trim();
        $("#titolopost").html('<textarea class="form-control textarea-limited" placeholder="Qui puoi scrivere il titolo del post." rows="1" maxlength="120" name="text" required id="titolop">' + $titolop + '</textarea>');
        $("#testoart").html('<textarea class="form-control textarea-limited" placeholder="Qui puoi scrivere il contenuto del post." rows="12" maxlength="2500" name="text" required id="testop">' + $testop + '</textarea>');

        $("#modifica").html('<a class="font-weight-bold text-white mt-4 mt-4 pr-5 pl-5" id="salva" href="#">Salva modifiche</a>');

        $('#titolop').keyup(function() {
            $('#titolop').text($('#titolop').val());
        });
        $('#testop').keyup(function() {
            $('#testop').text($('#testop').val());
        });
    });
});   


// Dopo averlo inserito dinamicamente, richiama il DOM con il nuovo pulsante per salvare le modifiche

$(document).on('click', '#salva', function(e) {
        e.preventDefault();
        pid = $("#hidpost").val();
        titolo = ($("#titolop").text()).trim();
        testo = ($("#testop").text()).trim();
        if(pid != "" && titolo != "" && testo != ""){
        $.ajax({
            method: "POST",
            url: "../ajax/modificapost.php",
            data: {
                pid: pid,
                titolo: titolo,
                testo: testo
            }
        }).done(function(response) {
            $("#titolop").off();
            $("#titolop").remove();
            $("#titolopost").html(titolo);

            $("#testop").off();
            $("#testop").remove();
            $("#testoart").html(testo);

            $("#modifica").html('<a class="font-weight-bold text-white mt-4 mb-4 pr-5 pl-5" id="modifica" href="#">Modifica post</a>');
            
        });
    } else {alert("Riempi tutti i campi!")}   
});