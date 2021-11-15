$(document).ready(function()  {

$("#font").change(function() // Al cambio degli option nella select box

 {  
 	$("#text").css("font-family", $("#font").val()); // Cambia il font family del testo nella textarea di "testo post"

 	if($("#font").val()=="notset") {    // Se non si sceglie font, o si cambia idea..
      
      $("#text").css("font-family", "");  // ..leva i font 

 	}

  });

});