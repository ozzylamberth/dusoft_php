<?php

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	

	
    $VISTA = "HTML";
	$_ROOT = "";
	
	include	"includes/enviroment.inc.php";
	require_once ("./classes/AutoCarga/AutoCarga.class.php");
	require_once ("./ConexionBD.class.php");
	
	
	$dusoft_fi = AutoCarga::factory("SincronizacionDusoftFI", "SincronizacionDusoftFI", "", "");
	$resultado_sincronizacion_ws = $dusoft_fi->facturas_venta_fi($empresa_id, $prefijo, $numero_factura);
	
	echo "hola mundo". print_r($resultado_sincronizacion_ws);
	die();


	
	$VISTA = "HTML";
	$_ROOT = "";
	
	include	"includes/enviroment.inc.php";
	list($dbconn)=GetDBConn();
	//$dbconn->debug=true;
	$documento = ModuloGetVar('app','FacturacionNotaCreditoAjuste','documento');
	if(!$documento)
	{
		echo "Falta definir la variable de modulo 'documento' para el modulo 'FacturacionNotaCreditoAjuste' ";
		return false;
	}
	$sql= "	SELECT	SUM(FF.saldo) AS saldo,
			COUNT(*) AS cantidad,
			FF.tipo_id_tercero,
			FF.tercero_id,
			FF.empresa_id,
			FF.sw_ptr
		FROM	tmp_facturas FF
		GROUP BY FF.tipo_id_tercero,FF.tercero_id,FF.empresa_id,FF.sw_ptr
		ORDER BY cantidad";
	
	$rst = $dbconn->Execute($sql);
				
	if ($dbconn->ErrorNo() != 0)
	{
		$frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
		echo "<b class=\"label\">".$frmError['MensajeError']."</b>";
		return false;
	}

	$terceros = array();
	while (!$rst->EOF)
	{
		$terceros[] = $rst->GetRowAssoc($ToUpper = false);
		$rst->MoveNext();
	}
	$rst->Close();
	
	list($dbconn) = GetDBConn();
	foreach($terceros as $key => $facturas)
	{
		echo "<b>".date("H:i");
		$sql = "SELECT cerrar_nota(".$documento.",'".$facturas['tipo_id_tercero']."','".$facturas['tercero_id']."','".$facturas['empresa_id']."',".$facturas['saldo'].",'".$facturas['sw_ptr']."') AS retorno ";
		$rst = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0) 
		{
			echo "Error...... Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		
		echo " ".$facturas['cantidad']." FACTURAS DEL TERCERO ".$facturas['tipo_id_tercero']." ".$facturas['tercero_id']." ACTUALIZADAS CORRECTAMENTE ".date("H:i")."</b><br>";
	}
	echo "FIN";
?>
