/*
* Funcion llamada desde el HTML como evento, ejecuta el remoteScripting jsrsExecute()
*/


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




/*Maestro combos*/
function LlenarCombo(cadena ) 
{
    // esta funcion recibe una cadena ... que transformaremos en un array ...
    // con la funcion jsrsArrayFromString ... es un split ...
    miArray  = jsrsArrayFromString( cadena  , "~" ) ;
    
     for( indice = 0; indice < miArray.length ; indice ++) 
     {
          miArray2 = jsrsArrayFromString( miArray[indice]  , "-" ) ;
          document.formulario1.nombre.value=miArray2[0]+" "+miArray2[1];
          document.formulario1.APELLIDOS.value=miArray2[2]+" "+miArray2[3];
          document.formulario1.fech_nac.value=miArray2[4];
          document.formulario1.Dir.value=miArray2[5];
          document.formulario1.sex.value=miArray2[6];
     }
}


function valores_exa() {
     alert("ddd");
     function vec() 
     { 
     	datos = new Array();
          
          datos[0]=forma1.sistema1.value;
          datos[1]=forma1.sistema2.value;
          datos[2]=forma1.sistema3.value;
          datos[3]=forma1.sistema4.value;
          datos[4]=forma1.sistema5.value;
          datos[5]=forma1.sistema6.value;
          datos[6]=forma1.sistema7.value;
          datos[7]=forma1.sistema8.value;
          datos[8]=forma1.sistema9.value; 
          datos[9]=forma1.sistema10.value;
          datos[10]=forma1.sistema11.value;
          datos[11]=forma1.string.value;
          datos[12]=forma1.usuario.value;
          datos[13]=forma1.ingreso.value;
          datos[14]=forma1.evolucion.value;
          
          return datos  
      }
    
     vector= new Array(); 
     vector=vec();
     alert("sss" + vector[12]);
     jsrsExecute("hc_modules/ExamenFisico/RemoteScripting/procesos.php", valores_resultado_exa, "get_valores_exa", vector);
}


function valores_resultado_exa( cadena ) {
    //document.formulario1.Sex.value=cadena;
    //objeto = document.formulario1.nombre;
}

/*Maestro combos*/
function LlenarCombo(cadena ) 
{
    // esta funcion recibe una cadena ... que transformaremos en un array ...
    // con la funcion jsrsArrayFromString ... es un split ...
    miArray  = jsrsArrayFromString( cadena  , "~" ) ;
    
     for( indice = 0; indice < miArray.length ; indice ++) 
	{
	     miArray2 = jsrsArrayFromString( miArray[indice]  , "-" ) ;
          document.formulario1.nombre.value=miArray2[0]+" "+miArray2[1];
          document.formulario1.APELLIDOS.value=miArray2[2]+" "+miArray2[3];
		document.formulario1.fech_nac.value=miArray2[4];
		document.formulario1.Dir.value=miArray2[5];
		document.formulario1.sex.value=miArray2[6];
	}
}

