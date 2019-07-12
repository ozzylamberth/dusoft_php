<?php
/*
* Clase complementaria del manejador de modulos
*
* Esta clase contiene algunos metodos de acceso publico generales del manejador de modulos
*
* @access public
*/

class classModulo extends classModules
{
  /**
  * Metodo Constructor de la Clase classModulo
  * @return boolean
  * @access private
  */

  function classModulo()
  {
    $this->classModules();
    return true;
  }


  /**
  * Funcion para llamar metodos de otros modulos de la aplicacion
  * @return boolean
  * @param string Nombre del contenedor del modulo a llamar
  * @param string Nombre del modulo
  * @param string Tipo de metodo a llamar (user,admin)
  * @param string Metodo a llamar
  * @param array  Arreglo con los parametros para el metodo
  */

	function ReturnMetodoExterno($contenedor, $modulo, $tipo='', $metodo='', $argumentos=array())
	{
    if(empty($contenedor) || empty($modulo)){
      die(MsgOut('ERROR AL LLAMAR UN METODO EXTERNO','Los parametros Contenedor y Modulo son obligatorios'));
    }

    if($contenedor == 'hc'){
      die(MsgOut("METODO NO APLICABLE","No se permite invocar modulos de Historias Clinicas desde otro tipos de modulos, por privacidad de la informacion."));
		}

		$Modulo = new ManejadorDeModulos($contenedor, $modulo, $tipo, $metodo, $argumentos);

		if(!$Modulo->Inicializar()){
			die(MsgOut($Modulo->Err(),$Modulo->ErrMsg()));
		}

		$this->salida .= $Modulo->GetSalida();
    	$this->javaScripts .= $Modulo->GetJavaScripts();
		return true;
	}

 	function CallMetodoExterno($contenedor, $modulo, $tipo, $metodo, $argumentos=array())
	{
    if(empty($contenedor) || empty($modulo) || empty($tipo) || empty($metodo)){
      die(MsgOut('ERROR EN CallMetodoExterno()','Los parametros Contenedor, Modulo, Tipo, Metodo son obligatorios'));
    }

    if(!($contenedor == 'app' || $contenedor == 'system')){
      die(MsgOut('ERROR EN CallMetodoExterno()',"El contenedor $contenedor no permite este metodo."));
		}

		$fileName  = $contenedor . "_modules/" . $modulo . "/" . $contenedor  . "_" . $modulo . "_" . $tipo . ".php";

		if(!IncludeFile($fileName)){
      die(MsgOut('ERROR EN CallMetodoExterno()',"El archivo '$fileName' no existe."));
			return true;
		}

		$className = $contenedor  . "_" . $modulo  . "_" . $tipo  ;

		if(!class_exists($className)){
      die(MsgOut('ERROR EN CallMetodoExterno()',"La clase '$className' no existe."));
			return true;
		}

		$MODULO_EXTERNO = new $className;

		if(!method_exists($MODULO_EXTERNO,$metodo)){
      unset($MODULO_EXTERNO);
      die(MsgOut('ERROR EN CallMetodoExterno()',"El metodo '$metodo' no existe en la clase '$className'."));
			return true;
		}

    return call_user_method_array ($metodo, $MODULO_EXTERNO, $argumentos);

	}


 	function ReturnModuloExterno($contenedor, $modulo, $tipo)
	{
    if(empty($contenedor) || empty($modulo) || empty($tipo)){
      die(MsgOut('ERROR EN ReturnModuloExterno()','Los parametros Contenedor, Modulo, Tipo, Metodo son obligatorios'));
    }

    if(!($contenedor == 'app' || $contenedor == 'system')){
      die(MsgOut('ERROR EN ReturnModuloExterno()',"El contenedor $contenedor no permite este metodo."));
		}

		$fileName  = $contenedor . "_modules/" . $modulo . "/" . $contenedor  . "_" . $modulo . "_" . $tipo . ".php";

		if(!IncludeFile($fileName))
    {
      die(MsgOut('ERROR EN ReturnModuloExterno()',"El archivo '$fileName' no existe."));
			return true;
		}

		$className = $contenedor  . "_" . $modulo  . "_" . $tipo  ;

		if(!class_exists($className))
    {
      die(MsgOut('ERROR EN ReturnModuloExterno()',"La clase '$className' no existe."));
			return true;
		}
    
    if($tipo != "controller")
    {
      global $VISTA;
  		$fileName  = $contenedor . "_modules/" . $modulo . "/$tipo"."classes/" . $contenedor  . "_" . $modulo . "_" . $tipo . "classes_$VISTA.php";
  		if(!IncludeFile($fileName)){
        die(MsgOut('ERROR EN ReturnModuloExterno()',"El archivo '$fileName' no existe."));
  			return true;
  		}

  		$className = $contenedor  . "_" . $modulo  . "_" . $tipo. "classes_$VISTA"  ;

  		if(!class_exists($className)){
        die(MsgOut('ERROR EN ReturnModuloExterno()',"La clase '$className' no existe."));
  			return true;
  		}
    }

		$MODULO_EXTERNO = new $className;
		$ModuloRetorno=&$MODULO_EXTERNO;
		return $ModuloRetorno;
	}
}//fin de classModulo

?>