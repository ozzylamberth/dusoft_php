<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: InformacionBaseDatosOci8.class.php,v 1.2 2009/02/10 16:02:02 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : InformacionBaseDatos
  * Clase encargada de obtener informacion de los afiliados, en las bases de datos 
  * o esquemas configurados para cada plan
  *  
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class InformacionBaseDatosOci8
  {
    /**
    * Objeto para hacer una conexion a una base de datos externa
    *
    * @var object
    * @access public
    */
    var $dbconn;
    /**
    * Codigo de error
    *
    * @var string
    * @access public
    */
    var $error = "";
    /**
    * Mensaje de error
    *
    * @var string
    * @access public
    */
    var $mensajeDeError = "";
    /**
    * Constructor de la clase
    */
    function InformacionBaseDatosOci8(){}
    /**
    * Funcion donde se obtiene la informacion solicitada del afiliado 
    * de la base de datos indicada en el archivo de configuracion para 
    * el plan seleccionado
    *
    * @param array $cnf Arreglo con los datos de configuracion de la 
    *              base de datos contenida en el archivo de configuracion
    * @param array $filtros Vector con los datos de identificacion de la 
    *              persona a buscar y el numero del plan
    *
    * @return array
    */
    function ObtenerInformacion($cnf,$filtros)
    {
      $sql = $this->ConstruirConsulta($cnf['base_datos']['tablaname'],$cnf['esquema']['esquema'],$cnf['campos_tabla'],$filtros);
      $rst = "";
      
      if(!is_null($cnf['base_datos']['user']) && trim($cnf['base_datos']['user']) != "")
      {
        if(is_null($cnf['base_datos']['host']))
        {
          global $ConfigDB;
          $cnf['base_datos']['host'] = $ConfigDB['dbhost'];
        }
        
        if(!$rst =$this->CrearConexionBD($cnf['base_datos'])) return false;
        $rst = $this->ConexionBaseDatosExterna($sql);
      }
      else
      {
        $rst = $this->ConexionBaseDatosInterna($sql);
      }
      
      if($rst === false || $rst == "") return false;
      
      $datos = array();
			while (!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
	 		
      if(!empty($filtros['paciente_id']))
        return $datos[0];
      
	 		return $datos;
    }
    /**
    * Funcion donde de acuerdo a los datos contenidos en el archivo de
    * configuracion se contruye una consulta para obtener los datos solicitados
    *
    * @param Strimg $tabla Nombre de la tabla
    * @param String $esquema Nombre del esquema
    * @param array $campos Vector con los nombres de los campos
    * @param array $datos vector con los datos de los filtros
    *
    * @return String
    */
    function ConstruirConsulta($tabla,$esquema,$campos,$datos)
    {
      $select = "";
      foreach($campos as $key => $campo)
      {
        if($campo != "")
          ($select == "")? $select .= " ".$campo." AS \"".$key."\" ": $select .= " ,".$campo." AS \"".$key."\" "; 
      }
           
      if($select == "") return false;
      
      if($esquema) $esquema = $esquema.".";
      
      $sql  = "SELECT ".$select." FROM ".$esquema.$tabla." ";
      if(!empty($datos['paciente_id']))
      {
        $sql .= "WHERE  ".$campos['paciente_id']." = '".$datos['paciente_id']."' ";
        $sql .= "AND    ".$campos['tipo_id_paciente']." = '".$datos['tipo_id_paciente']."' ";
      }
      else
      {
        $ctl = AutoCarga::factory('ClaseUtil');
        $sql .= "WHERE ".$ctl->FiltrarNombresOci8($datos['nombres'],$datos['apellidos'],"",$campos['primer_nombre'],$campos['segundo_nombre'],$campos['primer_apellido'],$campos['segundo_apellido']);
        $sql .= " ORDER BY ".$campos['primer_apellido'];
      }      
      return $sql;
    }
    /**
    * Funcion que permite realizar la conexion a la base de datos externa y ejecutar la
    * consulta sql
    *
    * @param string $sql sentencia sql a ejecutar
    * @param boolean $asoc Indica el modo en el cual se hara la ejecucion del query,
    *                      por defecto es false
    * @return object $rst
    */
    function ConexionBaseDatosExterna($sql,$asoc = false)
    {
      GLOBAL $ADODB_FETCH_MODE;
      //$this->dbconn->debug=true;

      if($asoc) $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

      $rst = $this->dbconn->Execute($sql);

      if($asoc) $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
      
      if ($this->dbconn->ErrorNo() != 0)
      {
        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
        $this->mensajeDeError = $this->dbconn->ErrorMsg();
        return false;
      }
      return $rst;
    } 
    /**
    * Funcion que permite realizar la conexion a la base de datos y ejecutar la
    * consulta sql
    *
    * @param string $sql sentencia sql a ejecutar
    * @param boolean $asoc Indica el modo en el cual se hara la ejecucion del query,
    *                      por defecto es false
    * @return object $rst
    */
    function ConexionBaseDatosInterna($sql,$asoc = false)
    {
      GLOBAL $ADODB_FETCH_MODE;
      list($dbconn)=GetDBConn();
      //$dbconn->debug=true;

      if($asoc) $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

      $rst = $dbconn->Execute($sql);

      if($asoc) $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
      
      if ($dbconn->ErrorNo() != 0)
      {
        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
        $this->mensajeDeError = $dbconn->ErrorMsg();
        return false;
      }
      return $rst;
    }
    /**
    * Funcion en la que se crea una conexion a la base de datos,
    * diferente a la contenida en el ConfigDB.php, para hecer consultas 
    * sobre esta misma
    *
    * @param array $datos Vector con la configuracion de la base de datos 
    */
    function CrearConexionBD($datos)
		{
      $this->dbconn = NewADOConnection($datos['dbtype']);
      //$ctr = "(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.1.35)(PORT = 1521)) (CONNECT_DATA = (SERVER = DEDICATED) (SERVICE_NAME = XE) ))";
      $ctr = "(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)(HOST = ".$datos['host'].")(PORT = 1521)) (CONNECT_DATA = (SERVER = DEDICATED) (SERVICE_NAME = ".$datos['tns']." ) ))";
    
      if ($this->dbconn->Connect($ctr, $datos['user'], $datos['pass']) === false)
      {
				$this->error = "Error en la Conexion a la Base de Datos";
        $this->mensajeDeError = $this->dbconn->ErrorMsg();
        return false;
			}
			return true;
		}
  }
?>