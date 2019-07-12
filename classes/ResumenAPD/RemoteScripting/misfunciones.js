/*
* Funcion llamada desde el HTML como evento, ejecuta el remoteScripting jsrsExecute()
*/

function valores_grupotipocargo( cadena ) {
		
    jsrsExecute("classes/ResumenAPD/RemoteScripting/procesos.php", valores_resultado_grupotipocargo, "get_valores_grupotipocargo",  cadena );
}

function valores_resultado_grupotipocargo( cadena ) {
    
    objeto = document.apoyos.tipocargo ;
    LlenarCombo(objeto,cadena);
}


/*Maestro combos*/
function LlenarCombo(objeto, cadena ) {
    // esta funcion recibe una cadena ... que transformaremos en un array ...
    // con la funcion jsrsArrayFromString ... es un split ...
    miArray  = jsrsArrayFromString( cadena  , "~" ) ;
    //objeto = document.formulario.depto ;
    objeto.options.length = 0 ;
    var defaultSelected = true;
    var selected = false;
    var length = 0;
    for( indice = 0; indice < miArray.length ; indice ++) {
       if ( indice == 0 ) {
          defaultSelected = true;
          selected = true;
       } else {
          defaultSelected = false;
          selected = false;

       }
			 miArray2 = jsrsArrayFromString( miArray[indice]  , "-" ) ;
     //  optionName = new Option(miArray[indice], miArray[indice], defaultSelected, selected) ;
			 optionName = new Option(miArray2[1], miArray2[0], defaultSelected, selected) ;
       length = objeto.options.length;
       objeto.options[length] = optionName ;
    }
}
