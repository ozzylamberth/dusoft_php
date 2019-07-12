<?php

// mantenimiento.class.php  27/01/2005
// ----------------------------------------------------------------------
// Copyright (C) 2004 IPSOFT SA
// www.ipsoft-sa.com
// ----------------------------------------------------------------------
// Autor: Alexander Giraldo
// Proposito del Archivo: Clase para ver la información y realizar el
// mantenimiento de la estructura.
// ----------------------------------------------------------------------
Include_once("var/www/html/phpPgAdmin/classes/database/ADODB_base.php");
Include_once("var/www/html/phpPgAdmin/classes/database/Connection.php");
Include_once("var/www/html/phpPgAdmin/classes/database/ADODB_base.php");
Include_once("var/www/html/phpPgAdmin/libraries/errorhandler.inc.php");
Include_once("var/www/html/phpPgAdmin/libraries/adodb/adodb.inc.php");
Include_once("var/www/html/phpPgAdmin/classes/database/Connection.php");

class mantenimiento
{
	var $conn;
  //variable que recibe la base de datos
  var $db;
	// The backend platform.  Set to UNKNOWN by default.
	var $platform = 'UNKNOWN';

	/**
	 * Base constructor
	 * @param &$conn The connection object
	 */

//  function mantenimiento(&$conn)
  function mantenimiento($op,$schema,$tabla,$op2)
  {
		//$this->conn = $conn;
    //echo $op;
		$versionmotor=$this->GetDriver('version');
    $this->IncluirDriver($versionmotor);
    switch ($op)
    {
			case 'tablas':
        $tables=$this->InstanciarTablas($versionmotor);
        return $tables;
        break;
			case 'campos':
        $campos=$this->Campos($versionmotor,$schema,$tabla);
        return $campos;
        break;
			case 'pk':
        $llavesprimarias=$this->Llavesforaneas('pk',$versionmotor,$schema,$tabla);
        return $llavesprimarias;
        break;
			case 'fk':
        $llavesforaneas=$this->Llavesforaneas('fk',$versionmotor,$schema,$tabla);
        return $llavesforaneas;
        break;
			case 'referencias':
        $referencias=$this->Referencias($versionmotor,$schema,$tabla);
        return $referencias;
        break;
			case 'select':
				$sql=$schema;
        $seleccion=$this->Seleccion($versionmotor,$sql);
        return $seleccion;
        break;
			case 'atributos':
        $campos=$this->SelCampos($versionmotor,$schema,$tabla);
        return $campos;
        break;
			case 'ref':
        $campos=$this->Ref($versionmotor,$schema,$tabla);
        return $campos;
        break;
			case 'tablasref':
        $campos=$this->TablasRef($versionmotor,$schema,$tabla,$op2);
        return $campos;
        break;
			case 'buscartabla':
      	$tabla=$schema;
        $campos=$this->BuscartablaBD($tabla,$versionmotor);
        return $campos;
        break;
			default:
				return false;
    }//fin switch
 }

  function Conectar($db)
  {
   switch($db)
   {
    case 'postgres':
    {
      $conn=ADONewConnection($db);
			if (empty($_REQUEST['HOST']) || empty($_REQUEST['UserBD']) || empty($_REQUEST['Passwd']) || empty($_REQUEST['BD']))
			{
        $_REQUEST['HOST']=$_SESSION['mantenimiento']['host'];
				$_REQUEST['UserBD']=$_SESSION['mantenimiento']['user'];
        $_REQUEST['Passwd']=$_SESSION['mantenimiento']['pwd'];
				$_REQUEST['BD']=$_SESSION['mantenimiento']['db'];
			}
      if (!($conn->Connect($_REQUEST['HOST'], $_REQUEST['UserBD'], $_REQUEST['Passwd'],$_REQUEST['BD'])))
      {
        return false;
      }else{
            if (is_object($conn))
              {
									$_SESSION['mantenimiento']['motor']=$db;
                	$_SESSION['mantenimiento']['version']=$this->GetDriver($db);
                  //$file='/var/www/html/'.$_REQUEST['BD'].'/classes/MantenimientoBD/Postgres/'.$version.'.class.php';
										$file=$ConfigAplication['DIR_SIIS'].'classes/MantenimientoBD/Postgres/'.$_SESSION['mantenimiento']['version'].'.class.php';
                if(file_exists($file))
                {
                  $this->ConexionDifusa();
                  include_once($file);
									return $conn;
                }else{
                  echo 'NO EXISTE DRIVER PARA PGSQL: '.$version;
                  return false;
                }
              }
           }
      break;
    }

    case 'oci':
      $conn=ConnOCI($db);
      if (!($conn->Connect($_POST['HOST2'], $_POST['UserBD2'], $_POST['Paswd2'],$_POST['BD2'])))
      {
          return false;
      }else{
          return $conn;
      }
      break;

    case 'mysql':
      $conn=ConnMYSQL($db);
      if (!($conn->Connect($_POST['HOST2'], $_POST['UserBD2'], $_POST['Paswd2'],$_POST['BD2'])))
      {
          return false;
      }else{
          return $conn;
      }
      break;

    case 'sqlserver':
      $conn=ConnSQLserver($db);
      if (!($conn->Connect($_POST['HOST2'], $_POST['UserBD2'], $_POST['Paswd2'],$_POST['BD2'])))
      {
          return false;
      }else{
          return $conn;
      }
      break;

    default :
      return false;

   }//fin switch
  }//fin metodo conectar

//***************************************************************************
/***
	 * Load the code for a specific database driver
  ***/

	function ADOLoadCode($dbType)
	{
    GLOBAL $ADODB_Database;

		if (!$dbType) return false;
		$ADODB_Database = strtolower($dbType);
		switch ($ADODB_Database) {
			case 'maxsql': $ADODB_Database = 'mysqlt'; break;
			case 'postgres':
			case 'pgsql': $ADODB_Database = 'postgres7'; break;
		}
		// Karsten Kraus <Karsten.Kraus@web.de>
		//return @include_once(ADODB_DIR."/drivers/adodb-".$ADODB_Database.".inc.php");
    return @include_once("classes/MantenimientoBD/".$ADODB_Database.".inc.php");
	}

	/**
	 * Gets the name of the correct database driver to use.  As a side effect,
	 * sets the platform.
	 * @param (return-by-ref) $description A description of the database and version
	 * @return The class name of the driver eg. Postgres73
	 * @return null if version is < 7.0
	 * @return -3 Database-specific failure
	 */
  //Extrae la version del driver sobre el cual se esta trabajando

	function GetDriver($description) {
		//$adodb =$this->ADODB_base($this->conn);
		$sql = "SELECT VERSION() AS version";
		$field = $this->selectField($sql, 'version');
  	//$field=$connect->Execute($sql);
		// Check the platform, if it's mingw, set it
		if (eregi(' mingw ', $field))
			$this->platform = 'MINGW';

		$params = explode(' ', $field);
		if (!isset($params[1])) return -3;

		$version = $params[1]; // eg. 7.3.2

    $_SESSION['mantenimiento']['version1']=$version;
		$description = "PostgreSQL {$params[1]}";
		// Detect version and choose appropriate database driver
		// If unknown version, then default to latest driver
		// All 6.x versions default to oldest driver, even though
		// it won't work with those versions.
		if ((int)substr($version, 0, 1) < 7)
			return null;
		elseif (strpos($version, '7.4') === 0)
			return 'Postgres74';
		elseif (strpos($version, '7.3') === 0)
			return 'Postgres73';
		elseif (strpos($version, '7.2') === 0)
			return 'Postgres72';
		elseif (strpos($version, '7.1') === 0)
			return 'Postgres71';
		elseif (strpos($version, '7.0') === 0)
			return 'Postgres';
		else
			return 'Postgres80';
	}

 	function selectField($sql, $field) {
		// Execute the statement
		//$rs = $this->conn->Execute($sql);
    list($con) = GetDBconn();
    $rs = $con->Execute($sql);
		// If failure, or no rows returned, return error value
		if (!$rs) return $conn->ErrorNo();
		elseif ($rs->RecordCount() == 0) return -1;

		return $rs->fields[0];
	}

//**************************************************************************

	function Connection($host, $port, $user, $password, $database, $fetchMode = ADODB_FETCH_ASSOC) {
		$this->conn = &ADONewConnection('postgres7');
		$this->conn->setFetchMode($fetchMode);

		// Ignore host if null
		if ($host === null || $host == '')
			$pghost = '';
		else
			$pghost = "{$host}:{$port}";

		$this->conn->connect($pghost, $user, $password, $database);
	}

 /***
	 * Get the last error in the connection
	 * @return Error string
	***/

	function getLastError() {
		if (function_exists('pg_errormessage'))
			return pg_errormessage($this->conn->_connectionID);
		else
			return pg_last_error($this->conn->_connectionID);
	}

//**********************************

	function ADODB_base(&$conn) {
		$this->conn = $conn;
	}

 /***
	 * Turns on or off query debugging
	 * @param $debug True to turn on debugging, false otherwise
	***/

	function setDebug($debug) {
		$this->conn->debug = $debug;
	}

	/**
	 * Cleans (escapes) a string
	 * @param $str The string to clean, by reference
	 * @return The cleaned string
	 */
	function clean(&$str) {
		$str = addslashes($str);
		return $str;
	}

	/**
	 * Cleans (escapes) an object name (eg. table, field)
	 * @param $str The string to clean, by reference
	 * @return The cleaned string
	 */
	function fieldClean(&$str) {
		$str = str_replace('"', '""', $str);
		return $str;
	}

	/**
	 * Cleans (escapes) an array
	 * @param $arr The array to clean, by reference
	 * @return The cleaned array
	 */
	function arrayClean(&$arr) {
		reset($arr);
		while(list($k, $v) = each($arr))
			$arr[$k] = addslashes($v);
		return $arr;
	}

	/**
	 * Executes a query on the underlying connection
	 * @param $sql The SQL query to execute
	 * @return A recordset
	 */
	function execute($sql) {
		// Execute the statement

		$rs = $this->conn->Execute($sql);

		// If failure, return error value
		return $this->conn->ErrorNo();
	}

	/**
	 * Closes the connection the database class
	 * relies on.
	 */
	function close() {
		$this->conn->close();
	}

	/**
	 * Retrieves a ResultSet from a query
	 * @param $sql The SQL statement to be executed
	 * @return A recordset
	 */
	function selectSet($sql) {
		// Execute the statement
		$rs = $this->conn->Execute($sql);

		if (!$rs) return $this->conn->ErrorNo();

 		return $rs;
 	}

	function ConexionDifusa()
	{
		$_SESSION['mantenimiento']['host']=$_REQUEST['HOST'];
		$_SESSION['mantenimiento']['user']=$_REQUEST['UserBD'];
		$_SESSION['mantenimiento']['pwd']=$_REQUEST['Passwd'];
		$_SESSION['mantenimiento']['db']=$_REQUEST['BD'];
    return true;
	}

  function IncluirDriver($driver)
  {
    //$file='/var/www/html/SIIS/classes/MantenimientoBD/Postgres/'.$driver.'.class.php';
    $file=$ConfigAplication['DIR_SIIS'].'classes/MantenimientoBD/Postgres/'.$driver.'.class.php';
    if(file_exists($file))
      include_once($file);
		else
    {
      $file=$ConfigAplication['DIR_SIIS'].'classes/MantenimientoBD/Postgres/'.$driver.'.class.php';
      if(file_exists($file))
        include_once($file);   
      else  
        return false;      
    }
    return true;
  }

  function InstanciarTablas($versionmotor)
   {
    $clase = New $versionmotor;
    $tablas=$clase->TablasBD($connect);
    return $tablas;
   }

  function Campos($versionmotor,$schema,$tabla)
   {
    $clase = New $versionmotor;
    $campos=$clase->CamposTablaBD($schema,$tabla);
    return $campos;
   }

  function Llavesforaneas($op,$versionmotor,$schema,$tabla)
   {
    $clase = New $versionmotor;
    $foraneas=$clase->LlavesForaneasTablasBD($op,$schema,$tabla);
    return $foraneas;
   }

  function Referencias($versionmotor,$schema,$tabla)
   {
    $clase = New $versionmotor;
    $referencias=$clase->TablasReferenciadasBD($schema,$tabla);
    return $referencias;
   }

  function Seleccion($versionmotor,$sql)
   {
    $clase = New $versionmotor;
    $sel=$clase->Seleccion($sql);
    return $sel;
   }

  function SelCampos($versionmotor,$schema,$tabla)
   {
      $clase = New $versionmotor;
      $campos=$clase->SelCampos($schema,$tabla);
      return $campos;
   }

	function Ref($versionmotor,$schema,$tabla)
   {
    $clase = New $versionmotor;
    $campos=$clase->GetCamposRef($schema,$tabla);
    return $campos;
   }

   	//TablasRef($versionmotor,$schema,$tabla)
	function TablasRef($versionmotor,$schema,$tabla,$op)
   {
    $clase = New $versionmotor;
    $tablas=$clase->GetTablasRef($schema,$tabla,$op);
    return $tablas;
   }

   function BuscartablaBD($tabla,$versionmotor)
   {
    $clase = New $versionmotor;
    $tablas=$clase->BuscarTablaBD($tabla);
    return $tablas;
   }

}//End class.

?>
