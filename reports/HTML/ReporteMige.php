<?php
/**
 * $Id: ReporteMige.php,v 1.1 2005/07/25 20:30:48 ehudes Exp $
 *
 * @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 * @package IPSOFT-SIIS
 * 
 * Redirecciona la peticion de consultar un reporte al MigeRas
 */
$_ROOT='../../';
$VISTA='HTML';
include $_ROOT.'includes/enviroment.inc.php';
IncludeClass('Ras');
if(!isset($_REQUEST['reporte']))
{
	$salida='Error';
	echo $salida;
	exit();
}
else
	$reporte=$_REQUEST['reporte'];

if(isset($_REQUEST['params']))
	$params=$_REQUEST['params'];
else
	$params=array();
$ras=new Ras(GetVarConfigAplication('ServerMige'),GetVarConfigAplication('PuertoMige'));
$solicitud = $ras->SolicitarReporte($reporte,$params,UserGetUID(),session_id());
?>