/*
* Funcion llamada desde el HTML como evento, ejecuta el remoteScripting jsrsExecute()
*/
function valores( cadena ) {
    /*
        Esta funcion solo ejecuta jsrsExecute con los siguientes parametros:
        1: Fichero [url] del .php que ofrece el servicio
        2: nombre de la funcion que recibirá el resultado ... recibe siempre un parámetro
        3: nombre de la funcion a ejecutar en el servidor
        4: parametros a enviar al servidor ... en este una cadena ...

    */
    jsrsExecute("app_modules/EJEMPLO/RemoteScripting/procesos.php", valores_resultado_org, "get_valores",  cadena);
}

function valores_resultado_org( cadena ) {
    // esta funcion recibe una cadena ... que transformaremos en un array ...
    // con la funcion jsrsArrayFromString ... es un split ...
    objeto = document.formulario.combo2 ;
		LlenarComboOrg(objeto,cadena);
}
function LlenarComboOrg(objeto, cadena ) {
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
       optionName = new Option(miArray[indice], miArray[indice], defaultSelected, selected) ;
       length = objeto.options.length;
       objeto.options[length] = optionName ;
    }
}



function valores_depto( cadena ) {
		
    jsrsExecute("app_modules/EJEMPLO/RemoteScripting/procesos.php", valores_resultado_depto, "get_valores_depto",  cadena );
}

function valores_resultado_depto( cadena ) {
    objeto = document.formulario.depto ;
		LlenarCombo(objeto,cadena);
}

/*Municipos*/
function valores_mpio( pais, depto ) {
    jsrsExecute("app_modules/EJEMPLO/RemoteScripting/procesos.php", valores_resultado_mpio, "get_valores_mpio", Array(pais,depto));
}

function valores_resultado_mpio( cadena ) {
    objeto = document.formulario.mpio ;
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
       //optionName = new Option(miArray[indice], miArray[indice], defaultSelected, selected) ;
			 optionName = new Option(miArray2[1], miArray2[0], defaultSelected, selected) ;
       length = objeto.options.length;
       objeto.options[length] = optionName ;
    }
}
