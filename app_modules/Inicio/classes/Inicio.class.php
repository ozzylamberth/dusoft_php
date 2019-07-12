<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: Inicio.class.php,v 1.2 2008/03/28 18:23:48 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase: Inicio
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class Inicio extends ConexionBD
  {
    /**
    * Codigo de error
    *
    * @var string
    * @access public
    */
    var $error;
    /**
    * Mensaje de error
    *
    * @var string
    * @access public
    */
    var $mensajeDeError;
    /**
    * Variable que indica el offset de la consulta
    *
    * @var int
    * @access public
    */
    var $offset;
    /**
    * Variable que indica el numero de la pagina a mostrar
    *
    * @var int
    * @access public
    */
    var $pagina;
    /**
    * Variable que indica la cantidad total de registros de la consulta
    *
    * @var int
    * @access public
    */
    function Inicio(){}
    /**
    * Metodo para recuperar el titulo del error
    *
    * @return string
    * @access public
    */
    function Err()
    {
      return $this->error;
    }
    /**
    * Metodo para recuperar el detalle del error
    *
    * @return string
    * @access public
    */
    function ErrMsg()
    {
      return $this->mensajeDeError;
    }
    /**
    * Funcion donde se obtiene el listado de tablas registradas
    *
    * @return array
    */
    function ListadoDeTablas()
    {
      $directorio = opendir("system_modules/Tablas/classes");
      $archivos = array();
      while ($file = readdir($directorio)) 
      {
        if(!is_dir($directorio))
        {
          if ($file != "." && $file != "..") 
          {
            if($file != "CVS" && $file != "Modelo.class.php")
            {
              $tabla = explode(".",$file);
              $nombre = ucfirst(str_replace("_"," ",$tabla[0]));
              $archivos[$nombre]['nombre_tabla'] = $tabla[0];
            }
          }
        }
      }
      closedir($directorio);
      return $archivos;
    }
    /**
    * Funcion donde se verifica el permiso del usuario para el ingreso
    * al modulo
    *
    * @return array
    */
    function ObtenerPermisos()
    {
      $sql  = "SELECT	* ";
			$sql .= "FROM	  userpermisos_tablas ";
			$sql .= "WHERE	usuario_id = ".UserGetUID()." ";
			$sql .= "AND    sw_activo = '1' ";
			
      if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
      $datos = array();
			if(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			
			$rst->Close();
			return $datos;
		}
    /**
    * Funcion donde se verifica el permiso del usuario para el ingreso
    * al modulo
    *
    * @return array
    */
    function ObtenerTablas()
    {
      $sql  = "SELECT	tabla_descripcion ";
			$sql .= "FROM	  system_usuarios_tablas ";
			$sql .= "WHERE	usuario_id = ".UserGetUID()." ";
			$sql .= "AND    sw_activo = '1' ";
			
      if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
      $archivos = array();
			while(!$rst->EOF)
			{
        $nombre = ucfirst(str_replace("_"," ",$rst->fields[0]));
        $archivos[$nombre]['nombre_tabla'] = $rst->fields[0];
				$rst->MoveNext();
			}
			
			
			$rst->Close();
			return $archivos;
		}
  }
?>