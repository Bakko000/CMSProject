valido = false;  // Inizialmente, è tutto settato su false

function check_fileupload(input, upload) // Il parametro indica lo "Scegli file", upload indica l'intero input

{   

    var el = document.getElementById("err");  // Div dove apparirà il messaggio di errore
    var fileName = input.value; // Contenuto, la stringa del file
    var allowed_extensions = new Array("jpg","png","raw","tiff", "jpeg", ""); // Estensioni consentite
    var file_extension = fileName.split('.').pop();  // Split per dividere la stringa da ".", pop per levare il resto della stringa che non ci interessa

    for(var i = 0; i < allowed_extensions.length; i++)  // Itero controllando se vale per qualche estensione possibile
    {
        if(allowed_extensions[i]==file_extension)   // Se l'elemento ha un'estensione valida
        {   
            
            if(input.files[0].size < 3145728) {  // Controllo se il file ha una dimensione corretta, minore di 3MB

            valido = true; // Se sì, allora estensione e formato valido, il file è validato
            el.innerHTML = "";   // Nessun messaggio
            return;   // Termino la funzione

            }
        }
    }

    el.innerHTML="Formato o dimensione non corrette";  // Messaggio di errore
    valido = false;  // Se c'è errore, la validità torna su false
    document.getElementById(upload).value = ""; // Azzera perciò il file input
}
    