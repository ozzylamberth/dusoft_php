<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: InformacionPacientes.class.php,v 1.3 2009/02/10 16:02:02 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : InformacionPacientes
  * Clase encargada de cargar la configuracion establecida para los planes
  *  
  * @package IPSOFT-SIIS
  * @version $Revision: 1.3 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class InformacionPacientes extends ConexionBD
  {
    /**
    * Constructor de la clase
    */
    function InformacionPacientes(){}
    /**
    * Funcion donde se obtiene el mensaje de error establecido 
    * para los errores
    * 
    * @param int $error Codigo del error
    * 
    * @return string
    */
    function ObtenerClasificacionErrores($error)
    {
      switch($error)
      {
        case 0: return $this->error; break;
        case 2: return "El archivo de configuracion para el plan seleccionado no existe"; break;
      }
    }
    /**
    * Funcion donde se valida que un plan dado, posea un correspondiente
    * archivo de configuracion
    * 
    * @param array $datos Vector con los datos del plan y la identificacion 
    *              de los pacientes
    *
    * @return mixed
    */
    function ValidarInformacion($datos,$root)
    { 
      $clase = $this->ObtenerClase($datos['plan_id']);
      
      if($clase == "") return 3;
      
      $config = array();
      $archivo = $root."classes/InformacionPacientes/planes_config/plan_".$datos['plan_id'].".ini";

      //echo file_exists($archivo);
      if(file_exists($archivo))
        $config = parse_ini_file($archivo,true);
      else
        return 2;
      
      $file = $root."classes/InformacionPacientes/classes/".$clase.".class.php";
      include $file;
      
      $cls = new $clase;
      
      $datos = $cls->ObtenerInformacion($config,$datos);
      $this->error = $cls->mensajeDeError;
      
      return $datos;
    }
    /**
    * Funcion donde se obtiene la clase a la cual se instanciara para el
    * plan dado
    *
    * @param int $plan Identificador del plan
    *
    * @return String
    */
    function ObtenerClase($plan)
    {
      $sql  = "SELECT nombre_clase ";
      $sql .= "FROM   eps_planes_parametros ";
      $sql .= "WHERE  plan_id = ".$plan." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
            
      $datos = array();
			if (!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
	 		
	 		return $datos['nombre_clase'];
    }
  }
?>