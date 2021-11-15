//Codice JQuery relativo alla ricerca istantanea
function showResult(str) {
    if (str.length == 0) { // All'inizio non essendoci nulla di scritto, di default non c'è nulla nel livesearch
        document.getElementById("livesearch").innerHTML = "";
        document.getElementById("livesearch").style.border = "0px";
        return;
    }
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          if(this.responseText!="") {
            document.getElementById("livesearch").innerHTML = this.responseText; //Se ottengo dei risultati li mostro sotto la casella
          } else {
            document.getElementById("livesearch").innerHTML = "Nessun Risultato"; //Se non ottengo risultati mostro la rispettiva scritta
          }
            document.getElementById("livesearch").style.border = "1px solid #A5ACB2";
        }
    }
    xmlhttp.open("GET", "./ajax/livesearch.php?q=" + str, true); //Prendo la stringa "q" che è il parametro della ricerca
    xmlhttp.send();
}