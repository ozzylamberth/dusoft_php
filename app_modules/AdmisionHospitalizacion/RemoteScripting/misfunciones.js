	/*
	* Funcion llamada desde el HTML como evento, ejecuta el remoteScripting jsrsExecute()
	*/
	function valores( cadena ) 
	{
    	jsrsExecute("app_modules/AdmisionHospitalizacion/RemoteScripting/procesos.php", valores_resultado, "Tipo_Afiliado",  cadena);
		jsrsExecute("app_modules/AdmisionHospitalizacion/RemoteScripting/procesos.php", valores_resultado1,"Niveles",cadena);
	}
    /***************************************************************************************
    * esta funcion recibe una cadena ... que transformaremos en un array ...
    * con la funcion jsrsArrayFromString ... es un split ...
    ****************************************************************************************/
	function valores_resultado( cadena ) 
	{
    	objeto = document.forma.tipoafiliado ;
		LlenarComboOrg(objeto,cadena);
	}
    /***************************************************************************************
    * esta funcion recibe una cadena ... que transformaremos en un array ...
    * con la funcion jsrsArrayFromString ... es un split ...
    ****************************************************************************************/
	function valores_resultado1( cadena ) 
	{
    	objeto = document.forma.nivel ;
		LlenarComboOrg1(objeto,cadena);
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
			miArray2 = jsrsArrayFromString(miArray[indice]  , "-" ) ;
       		optionName = new Option(miArray2[0], miArray2[1], defaultSelected, selected) ;
       		length = objeto.options.length;
       		objeto.options[length] = optionName ;
    	}
	}
	/***************************************************************************************
    * esta funcion recibe una cadena ... que transformaremos en un array ...
    * con la funcion jsrsArrayFromString ... es un split ...
    ****************************************************************************************/	
	function LlenarComboOrg1(objeto, cadena ) 
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
       		optionName = new Option(miArray[indice], miArray[indice], defaultSelected, selected) ;
       		length = objeto.options.length;
       		objeto.options[length] = optionName ;
    	}
	}			