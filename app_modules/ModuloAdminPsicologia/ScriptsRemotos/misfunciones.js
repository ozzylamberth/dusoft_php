/*
* Funcion llamada desde el HTML como evento, ejecuta el remoteScripting jsrsExecute()
*/


function GuardarFactorConversion( cadena ) 
{
    jsrsExecute("app_modules/EE_AdministracionMedicamentos/ScriptsRemotos/procesos.php", retorno_funcion_principal, "EstablecerFactorConversion", cadena);
}


function ValidacionPremisos( cadena ) 
{
    jsrsExecute("app_modules/EE_AdministracionMedicamentos/ScriptsRemotos/procesos.php", retorno_validacion, "ValidarPermisosFactor", cadena);
}


function retorno_funcion_principal( cadena )
{
     CapaFac = xGetElementById('ContenedorCambioFactor');        
     CapaFac.style.display = 'none';

     load_page();
}


function retorno_validacion( cadena )
{
     if (cadena == '1')
     {
     	alert('El usuario no tiene permiso para : Establecer Factor de Conversion para Medicamentos [59]');
          
          CapaFac = xGetElementById('ContenedorCambioFactor');        
          CapaFac.style.display = 'none';
          
          CapaFac = xGetElementById('ContenedorSuministrosParciales');        
          CapaFac.style.display = 'none';
     }else
     {
     	vector = xGetElementById('vectorFactor');
     	Temp = jsrsArrayFromString(vector.value, ',');
          valor = xGetElementById('valorFactor');
          Temp[4] = valor.value;
          
          GuardarFactorConversion(Temp);
     }
}