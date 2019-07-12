<?
/**
 * $Id: index.php,v 1.3 2007/11/20 14:23:31 hugo Exp $
 *
 * @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 * @package IPSOFT-SIIS
 * 
 * Componente de bajo nivel para los privilegios de la base de datos
 * de la aplicacion SIIS
 */

$VISTA='HTML';
$_ROOT='../';
include 'enviroment_siis.inc.php';
include 'Security.class.php';

header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: post-check=0, pre-check=0",false);
header("Pragma: no-cache");
//Autentificacion HTTP CON PHP
if(isset($_SERVER['PHP_AUTH_USER']) and $_SESSION['security'])
{
	$Security = new Security($ConfigDB['dbtype'],$_SERVER['PHP_AUTH_USER'],$_SERVER['PHP_AUTH_PW'],$ConfigDB['dbhost'],$ConfigDB['dbname'],$ConfigDB['dbuser'],$ConfigDB['dbpass']);
	$Security->EjecutarSecurity();
  unset($_SERVER);
	logout();
  exit;
}
else
{
	unset($_SERVER['PHP_AUTH_USER']);
	unset($_SERVER['PHP_AUTH_PW']);
}
if (!isset($_SERVER['PHP_AUTH_USER']))
{
	login();
	header('WWW-Authenticate: Basic realm="Security"');
	header('HTTP/1_0 401 Unauthorized');
	echo '<b>Recurso no autorizado</b>';
	exit;
}
/**
 * Login
 */
function login()
{
	$_SESSION['security']='si';
}
/**
 * Logout
 */
function logout()
{
	unset($_SESSION['security']);
}
?>