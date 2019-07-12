<?php
/*
* Clase maestra del manejador de modulos
*
* Esta clase contiene los metodos de acceso publico que retornan la informacion
*
* @access public
*/

class classModules
{

    var $salida='';
    var $error='';
    var $mensajeDeError='';
    var $fileError='';
    var $lineError='';
    var $moduloError='';
    var $errorPropiedadesModulo=array();
    var $envError='';
    var $javaScripts='';
    var $themeVars=array();
    var $javas=array();
    var $javasFiles=array();

    function classModules()
    {
        IncludeClass('AutoCarga');
        IncludeClass('ConexionBD');
        $this->IncludeJS("Calendario");
				return true;
    }

    function Err()
    {
        return $this->error;
    }

    function ErrMsg()
    {
        return $this->mensajeDeError;
    }

    function ErrFile()
    {
        return $this->fileError;
    }

    function ErrLine()
    {
        return $this->lineError;
    }

    function ErrModulo()
    {
        return $this->moduloError;
    }

    function ErrEnv()
    {
        return $this->envError;
    }

    function ErrPropiedadesModulo()
    {
        return $this->errorPropiedadesModulo;
    }

    function GetSalida()
    {
        return $this->salida;
    }

    function GetJavaScripts()
    {
        foreach($this->javasFiles as $k=>$v)
        {
		$this->javaScripts .= "<script lenguage=\"text/javascript\" src=\"$k\"></script>\n";
        }
				
        foreach($this->javas as $k=>$v)
        {
		$this->javaScripts .= ReturnJava($k);
        }
	 //list($xajax) = getXajax();
        global $xajax;
    	if(is_object($xajax)) 
    	{
    	/*$xajax->setFlag("debug", true);*/
    		//$xajax->processRequest();
    		$this->javaScripts .= $xajax->printJavascript('classes/xajax/'); 	
    	}
      return $this->javaScripts;
    }
		/**
		* Metodo para incorporar la libreria de xajax al sistema
		*
		* @params array $func Nombres de las funciones a registrar por xajax
		* @params string $file Ruta del archivo a incluir
		* @return boolean
		**/
		function SetXajax($func,$file=null,$encode = "UTF-8")
		{
      global $xajax;
			list($xajax) = getXajax();
      
			$xajax->setCharEncoding($encode);
			
      foreach($func as $key => $xfunc)
				$xajax->registerFunction($xfunc,$file);
      
			$xajax->processRequest();
			return true;
		}
    
		function SetJavaScripts($Java)
    {
				$this->javas[$Java]=1;
        return true;
    }

    /**
    * Metodo para incluir librerias de JavaScript
    *
    * @param string $file Nombre del archivo a incluir, o ruta del mismo.
    * @param string $contenedor opcional nombre del contenedor si la libreria esta en un modulo
    * @param string $modulo opcional nombre del modulo si la libreria esta en un modulo
    * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
    * @access public
    */
    function IncludeJS($file, $contenedor='', $modulo='')
    {
        if(!empty($contenedor) && !empty($modulo))
        {
            $file = $contenedor."_modules/".$modulo."/".$file;
        }
        else
        {
            global $_JavaFiles;
            if (array_key_exists($file, $_JavaFiles))
            {
                $file = $_JavaFiles[$file];
            }
        }

        if(file_exists($file))
        {
            $this->javasFiles[$file]=1;
        }
        return true;
    }

    function ThemeGetVar($var)
    {
        if(isset($this->themeVars[$var]))
        {
            return $this->themeVars[$var];
        }else{
            return false;
        }
    }
}

?>