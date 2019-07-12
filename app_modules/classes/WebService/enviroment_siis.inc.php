<?php

/**
 * $Id: enviroment_siis.inc.php,v 1.3 2005/11/22 13:45:47 ehudes Exp $
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
IncludeLib("modules");


error_reporting(0);
// funcion de gestion de errores definida por el usuario
function gestorDeErrores($num_err, $mens_err, $nombre_archivo,$num_linea, $vars)
{
	$err = "<errorentry>\n";
//	$err .= "\t<datetime>" . $dt . "</datetime>\n";
	$err .= "\t<errornum>" . $num_err . "</errornum>\n";
	$err .= "\t<errortype>" . $tipo_error[$num_err] . "</errortype>\n";
	$err .= "\t<errormsg>" . $mens_err . "</errormsg>\n";
	$err .= "\t<scriptname>" . $nombre_archivo . "</scriptname>\n";
	$err .= "\t<scriptlinenum>" . $num_linea . "</scriptlinenum>\n";
	$err .= "</errorentry>\n\n";
	error_log($err, 3, "/tmp/error.log");
}
$gestor_de_errores_anterior = set_error_handler("gestorDeErrores");
?>