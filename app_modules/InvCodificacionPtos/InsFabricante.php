<?php

/**
 * $Id: InsFabricante.php,v 1.3 2005/12/29 16:52:01 lorena Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: realizar la Insercion de los fabricantes.
 */

$VISTA='HTML';
$_ROOT='../../';
include_once $_ROOT.'includes/enviroment.inc.php';
include_once $_ROOT.'includes/modules.inc.php';

	$descripcion=$_REQUEST['descripcion'];
	$Aceptar=$_REQUEST['Aceptar'];
  if($Aceptar && !empty($descripcion)){
    list($dbconn) = GetDBconn();
		$descripcion= strtoupper($descripcion);
		$sql="INSERT INTO inv_fabricantes(descripcion) VALUES('$descripcion')";
		$res=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
	}

header("Location:AdicionarFabricante.php");
exit;		
	
?>
