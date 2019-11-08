	/*
	* Funcion llamada desde el HTML como evento, ejecuta el remoteScripting jsrsExecute()
	*/
	function valores( cadena )    
	{
    jsrsExecute("app_modules/RecibosCaja/RemoteScripting/procesos.php", valores_resultado, "Bancos", cadena);
	}
  /***************************************************************************************
    * esta funcion recibe una cadena ... que transformaremos en un array ...
    * con la funcion jsrsArrayFromString ... es un split ...
    ****************************************************************************************/
	function valores_resultado( cadena ) 
	{
   	objeto = document.formaP.numero_cuenta;
		LlenarComboOrg(objeto,cadena);
	}		
	/***************************************************************************************
    * esta funcion recibe una cadena ... que transformaremos en un array ...
    * con la funcion jsrsArrayFromString ... es un split ...
    ****************************************************************************************/	
	function LlenarComboOrg(objeto, cadena ) 
	{
    	miArray  = jsrsArrayFromString( cadena  , "~" ) ;

    	objeto.options.length = 0 ;
    	var defaultSelected = true;
    	var selected = false;
    	var length = 0;
       		
    	for( indice = 0; indice < miArray.length ; indice ++) 
    	{
				if ( indice == 0 ) 
				{
	        defaultSelected = true;
	        selected = true;
	      }
	      else 
	      {
	      	defaultSelected = false;
	      	selected = false;
	      }
				
				miArray2 = jsrsArrayFromString(miArray[indice]  , "*" ) ;
				if(indice == 0)
				{
	       	optionName = new Option(miArray2[1], miArray2[0], defaultSelected, selected) ;
				}
				else
				{
					optionName = new Option(miArray2[1], miArray2[1], defaultSelected, selected) ;
				}
       	length = objeto.options.length;
       	objeto.options[length] = optionName ;
    	}
	}