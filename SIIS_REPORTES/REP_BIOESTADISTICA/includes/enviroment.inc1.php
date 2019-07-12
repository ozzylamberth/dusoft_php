<?php

/**
 * $Id: enviroment.inc.php,v 1.7 2005/07/28 16:28:03 alex Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: Cargar el Entorno de la aplicacion
 */

// Reportar errores para depuracion.
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

if (phpversion() < "4.2.0") {
    die('Se requiere PHP 4.2.0 o superior');
}

$SIIS_VERSION=array(
'version'=>'1',
'subversion'=>'0',
'revision'=>'0',
'fecha'=>'01/27/2005',
'descripcion_cambio' => '',
'requiere_sql' => false,
'requerimientos_adicionales' => '',
);


//Inicializo Variables de Configuracion de la aplicacion
$ConfigAplication = array();
include $_ROOT . 'ConfigAplication.php';

// Incluir las Definiciones de Version
include $_ROOT . 'Version.php';

// Incluir mas Definiciones
include $_ROOT . 'includes/defines.inc.php';

// Incluir el API de la aplicacion
include $_ROOT . 'includes/api.inc.php';

//DEFINIR EL DOMINIO
$ConfigAplication['DOMINIO_SIIS'] = GetBaseURL();


// Incluir Funciones de retorno de la vista.
if (!IncludeFile("includes/vistas/$VISTA.php")) {
  die(MsgOut("Archivo de la capa de 'vista' no Disponible","Nombre del Archivo : includes/VISTA/$VISTA.php"));
}

// Cargar funciones para el manejo de Session
include $_ROOT . 'includes/session.inc.php';

SessionConfig();
SesionStart();

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


//Inicializo la session en PHP.

SessionInit();

if(!GetHostAccess()){
  die(MsgOut("PERMISO DENEGADO","La IP " . GetIPAddress() . " no tiene permiso de acceso."));
}

// Cargar funciones para el manejo de Usuarios
include $_ROOT . 'includes/users.inc.php';

// Cargar funciones miscelaneas
IncludeFile('includes/funciones.inc.php');


if (!IncludeFile("themes/$VISTA/".GetTheme()."/theme.php")) {
  die(MsgOut("Archivo del 'Theme' no Disponible","Nombre del Archivo : ".GetThemePath()."/theme.php"));
}

if (!IncludeFile("themes/$VISTA/".GetTheme()."/gui_theme.php")) {
  die(MsgOut("Archivo del 'Theme' no Disponible","Nombre del Archivo : ".GetThemePath()."/gui_theme.php"));
}

if (!function_exists("PrintIndexFrames")) {
  die(MsgOut("Archivo del 'Theme' incorrecto","La funcion ThemeReturnIndexFrames() no existe en el archivo ".GetThemePath()."/theme.php"));
}

if (!function_exists("PrintIndexNoFrames")) {
  die(MsgOut("Archivo del 'Theme' incorrecto","La funcion ThemeReturnIndexNoFrames() no existe en el archivo ".GetThemePath()."/theme.php"));
}

// Incluir el API de la aplicacion
IncludeFile('includes/javas.inc.php');

if(function_exists("ThemeVars"))
{
  $_ENV['THEME_VARS']=ThemeVars();
}

// Cargar funciones modulos
if (!IncludeFile('includes/modules.inc.php')) {
    die(MsgOut("No se pudo Incluir el archivo","includes/modules.inc.php"));
}

// Incluir la clase de reportes
if (!IncludeFile('classes/reports/GetReports.class.php')) {
    die(MsgOut("No se pudo Incluir el archivo","classes/reports/GetReports.class.php"));
}

// Incluir el API de la aplicacion
IncludeFile('includes/garbage.inc.php');


//Monitor de sqls
if(!ModuloGetVar('system', 'BDperf', 'ActivarDepuracionSQL'))
{
    $ConfigAplication['ActivarDepuracionSQL']=false;
}
else
{
    $ConfigAplication['ActivarDepuracionSQL']=true;
}

//depurador de SQL
if($ConfigAplication['ActivarDepuracionSQL'])
{
    include $_ROOT . 'classes/adodb/adodb-perf.inc.php';
    $dbconn->LogSQL(true);
}

//EJECUTO EL GARBAGE DE LA APLICACION EN EL 10% DE LAS OCACIONES
if(is_numeric($ConfigAplication['FrecuenciaGarbage']))
{
    $gb=5;
}
else
{
    $gb=$ConfigAplication['FrecuenciaGarbage'];
}
if(rand(1,100)<=$gb)
{
   Garbage_day();
}
unset($gb);


//Interface BX4 para la Clinica de Occidente de Cali TEMPORAL
//IncludeFile('interfaceBX4.php');


?>
