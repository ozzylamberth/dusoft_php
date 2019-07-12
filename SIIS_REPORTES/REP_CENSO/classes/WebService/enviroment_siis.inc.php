<?php

/**
 * $Id: enviroment_siis.inc.php,v 1.1 2005/07/13 18:40:27 ehudes Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: Cargar el Entorno de la aplicacion exclusivo para los webservices
 */
$VISTA='HTML';
$_ROOT = '../../';

//Inicializo Variables de Configuracion de la aplicacion
$ConfigAplication = array();
include $_ROOT . 'ConfigAplication.php';
setlocale (LC_TIME, "es_ES");
// Incluir el API de la aplicacion
include $_ROOT . 'includes/api.inc.php';

//DEFINIR EL DOMINIO
$ConfigAplication['DOMINIO_SIIS'] = GetBaseURL();

// Configuracion de ADODB
define('ADODB_DIR', $_ROOT . 'classes/adodb');
include $_ROOT . 'classes/adodb/adodb.inc.php';

// Inicializar y Cargar la Configuracion de la BD.
$ConfigDB = array();
include $_ROOT . 'ConfigDB.php';

// Decodificar parametros de la BD
if ($ConfigDB['encoded']) {
    $ConfigDB['dbuser'] = base64_decode($ConfigDB['dbuser']);
    $ConfigDB['dbpass'] = base64_decode($ConfigDB['dbpass']);
    $ConfigDB['encoded'] = 0;
}

// Inicializar la Conexion a la BD.
$dbconn = ADONewConnection($ConfigDB['dbtype']);

if (!($dbconn->Connect($ConfigDB['dbhost'], $ConfigDB['dbuser'], $ConfigDB['dbpass'], $ConfigDB['dbname']))) {
    die(MsgOut("Error en la Conexión a la Base de Datos",$dbconn->ErrorMsg()));
}

$ADODB_FETCH_MODE = ADODB_FETCH_NUM;


?>
