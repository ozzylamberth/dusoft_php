<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ConexionBD.class.php,v 1.2 2009/09/23 21:45:59 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase: ConexionBD
  * Clase encargada del manejo de base de datos para hacer las consultas y las
  * actualizaciones que sean necesarias
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class ConexionBD
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
    * Variable global para el manejo de l aconexion
    * 
    * @var object
    * @access public
    */
    var $dbconn;
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
    var $conteo;
    /**
    * Variable que indica el total de registros a mostrar por pagina
    *
    * @var int
    * @access public
    */
    var $limit;
    /**
    * Variable para hacer debug en la ejecucion de los querys
    *
    * @var int
    * @access public
    */
    var $debug = false;
    /**
    * Constructor de la clase
    */
    function ConexionBD(){}
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
		* Funcion, donde se inicializan las variables de paginaActual, offset y conteo,
		* importantes a la hora de referenciar al paginador
		*
		* @param String $sql Cadena que contiene la consulta sql del conteo
    * @param int $pg_siguiente Indica el numero de la pagina que se desea ver
		* @param int $num_reg numero que define el limite de datos,cuando no se desa el del
		* 			 usuario,si no se pasa se tomara por defecto el del usuario
    * @param int $limite Indica el limite que se desea ver, si no esta se pondra el 
    *        definido para el usuario en la base de datos    
		* @return boolean
		*/
		function ProcesarSqlConteo($sql,$pg_siguiente = 0,$num_reg = 0,$limite = 0)
		{
			$this->offset = 0;
			$this->pagina = 1;
			if($limite === 0)
			{
				$this->limit = GetLimitBrowser();
				if(!$this->limit) $this->limit = 20;
			}
			else
			{
				$this->limit = $limite;
			}

			if($pg_siguiente)
			{
				$this->pagina = intval($pg_siguiente);
				if($this->pagina > 1)
					$this->offset = ($this->pagina - 1) * ($this->limit);
			}

			if(!$num_reg)
			{
				if(!$rst = $this->ConexionBaseDatos($sql)) return false;

				if(!$rst->EOF)
				{
					$this->conteo = $rst->fields[0];
					$rst->MoveNext();
				}
				$rst->Close();
			}
			else
			{
				$this->conteo = $num_reg;
			}
			return true;
		}
    /**
    * Funcion que hace commit de la transaccion
    */
    function Commit()
    {
      $this->dbconn->CommitTrans();
    }
    /**
		* Funcion que permite crear una transaccion 
		* @param string $sql Sql a ejecutar- para dar inicio a la transaccion se pasa vacio
    * @param boolean $asoc Indica el modo en el cual se hara la ejecucion del query,
    *                      por defecto es false
		* @return object Objeto de la transaccion - Al momento de iniciar la transaccion no 
		*								 se devuelve nada
		*/
		function ConexionTransaccion($sql,$asoc = false)
		{
			GLOBAL $ADODB_FETCH_MODE;
      
      if(!$sql)
			{
				list($this->dbconn) = GetDBconn();
				$this->dbconn->debug = $this->debug;
				$this->dbconn->BeginTrans();
			}
			else
			{
        if($asoc) $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

        $rst = $this->dbconn->Execute($sql);

        if($asoc) $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

				if ($this->dbconn->ErrorNo() != 0)
				{
          $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
          $this->mensajeDeError = $this->dbconn->ErrorMsg()."<br>".$sql;
         	$this->dbconn->RollbackTrans();
					
          return false;
				}
				return $rst;
			}
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
    function ConexionBaseDatos($sql,$asoc = false)
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn)=GetDBConn();
        $dbconn->debug = $this->debug;

        if($asoc) $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

        $rst = $dbconn->Execute($sql);

        if($asoc) $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        
        if ($dbconn->ErrorNo() != 0)
        {
          $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
          $this->mensajeDeError = $dbconn->ErrorMsg()." ".$sql;
          return false;
        }
        return $rst;
    }
    /**
    * Funcion donde se parte la fecha y se devuelve la fecha en formato yyyy-MM-DD
    *
    * @param strinmg $fecha Fecha pasada por parametro 
    *
    * @return string
    */
    function DividirFecha($fecha)
    {
      $f = explode("/",$fecha);
      if(sizeof($f) == 3 )
        $fecha = $f[2]."-".$f[1]."-".$f[0];
      
      return $fecha;
    }
  }
?>