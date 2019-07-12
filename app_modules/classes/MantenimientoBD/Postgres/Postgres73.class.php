<?php
// mantenimiento.class.php  27/01/2005
// ----------------------------------------------------------------------
// Copyright (C) 2004 IPSOFT SA
// www.ipsoft-sa.com
// ----------------------------------------------------------------------
// Autor: Alexander Giraldo
// Proposito del Archivo: Clase para extraer datos del motor y realizar el
// mantenimiento de la estructura.
// ----------------------------------------------------------------------


class postgres73
{

    function postgres73()
    {
        return true;
    }

    function GetKernelVersion()
    {
        global     $SIIS_VERSION;
        return $SIIS_VERSION;
    }

//*******************************************************************
	function ADOLoadDB($dbType)
	{
		return ADOLoadCode($dbType);
	}

	/**
	 * Load the code for a specific database driver
	 */
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
	 * synonym for ADONewConnection for people like me who cannot remember the correct name
	 */
	function &NewConexion($db='')
	{
		return ADOConexion($db);
	}

	/**
	 * Instantiate a new Connection class for a specific database driver.
	 *
	 * @param [db]  is the database Connection object to create. If undefined,
	 * 	use the last database driver that was loaded by ADOLoadCode().
	 *
	 * @return the freshly created instance of the Connection class.
	 */
	function &ADOConexion($db='')
	{

	GLOBAL $ADODB_Database

		$rez = true;
		if ($db) {
			if ($ADODB_Database != $db) ADOLoadCode($db);
		} else {
			if (!empty($ADODB_Database)) {
				ADOLoadCode($ADODB_Database);
			} else {
				 $rez = false;
			}
		}

		$errorfn = (defined('ADODB_ERROR_HANDLER')) ? ADODB_ERROR_HANDLER : false;
		if (!$rez) {
			 if ($errorfn) {
				// raise an error
				$errorfn('ADONewConnection', 'ADONewConnection', -998,
						 "could not load the database driver for '$db",
						 $dbtype);
			} else
				 ADOConnection::outp( "<p>ADONewConnection: Unable to load database driver '$db'</p>",false);

			return false;
		}

		$cls = 'ADODB_'.$ADODB_Database;
		$obj = new $cls();
		if ($errorfn) {
			$obj->raiseErrorFn = $errorfn;
		}
		return $obj;
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
	function getDriver(&$description) {
		$adodb = new ADODB_base($this->conn);

	echo	$sql = "SELECT VERSION() AS version";
		$field = $adodb->selectField($sql, 'version');

		// Check the platform, if it's mingw, set it
		if (eregi(' mingw ', $field))
			$this->platform = 'MINGW';

		$params = explode(' ', $field);
		if (!isset($params[1])) return -3;

		$version = $params[1]; // eg. 7.3.2
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

 function PutDriver($driver)
 {
    switch ($driver)
    {
        case 'postgres':
            $version=GetDriver($driver);
            $file='classes/MantenimientoBD/Postgres/posgres'.$version.'.class.php';
            if(file_exists($file))
            {
              include_once($file);
              return true;
            }else{
              echo 'NO EXISTE DRIVER'; exit;
              return false;
            }
        break;

        case 'oci':
            $version=getdriver($driver);
            return $version;
        break;

        case 'sqlserver':
            $version=getdriver($driver);
            return $version;
        break;

        case 'mysql':
            $version=getdriver($driver);
            return $version;
        break;

        default:
        return false;
    }
 }
//*********************************************************

}//End class.

?>
 
