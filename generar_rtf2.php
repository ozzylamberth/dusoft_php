<?php
	
	$VISTA = "HTML";
	$_ROOT = "";
	
	include	"includes/enviroment.inc.php";
	list($dbconn)=GetDBConn();
	//$dbconn->debug=true;
	
	$sql= "SELECT	FF.prefijo,
								FF.factura_fiscal,
								FF.total_factura,
								FF.saldo,
								FF.estado,
								CASE 	WHEN TO_CHAR(FF.fecha_registro,'YY') = '06' THEN ROUND(FF.total_factura*0.04,0)
											WHEN TO_CHAR(FF.fecha_registro,'YY') = '07' THEN ROUND(FF.total_factura*0.02,0)
								ELSE 0 END AS valor_rtf
				FROM		fac_facturas AS FF,
								tipo_id_terceros TI
				WHERE		TI.tipo_id_tercero = FF.tipo_id_tercero
				AND			TI.sw_personas_naturales = '0'
				AND			FF.saldo > 0
				AND 		FF.retencion_fuente = 0 
				AND 		FF.estado = '0' 
			--	LIMIT 2000 OFFSET 0 ";
	
	$rst = $dbconn->Execute($sql);
				
	if ($dbconn->ErrorNo() != 0)
	{
		$frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
		echo "<b class=\"label\">".$frmError['MensajeError']."</b>";
		return false;
	}

	$facturas = array();
	while (!$rst->EOF)
	{
		$facturas[] = $rst->GetRowAssoc($ToUpper = false);
		$rst->MoveNext();
	}
	$rst->Close();
	
	list($dbconn)=GetDBConn();
	//$dbconn->debug=true;
	ECHO "<b style=\"font-size:10px\">";

	$rst = $dbconn->Execute($sql);
	$i = 0;
	foreach($facturas as $key => $detalle)
	{
		if($detalle['saldo'] > $detalle['valor_rtf'] && $detalle['valor_rtf'] > 0)
		{
			$sql  = "ALTER TABLE fac_facturas DISABLE TRIGGER actualizar_saldo_factura; ";
			$rst = $dbconn->Execute($sql);

			$sql  = "UPDATE fac_facturas ";
			$sql .= "SET 		retencion_fuente = ".$detalle['valor_rtf'].", ";
			$sql .= "				saldo = ".($detalle['saldo'] - $detalle['valor_rtf'])." ";
			$sql .= "WHERE	prefijo= '".$detalle['prefijo']."' ";
			$sql .= "AND 		factura_fiscal= ".$detalle['factura_fiscal']."; ";
			$rst = $dbconn->Execute($sql);
			
			$sql = "ALTER TABLE fac_facturas ENABLE TRIGGER actualizar_saldo_factura; ";
			$rst = $dbconn->Execute($sql);
			
			echo ($i++)." ".$detalle['prefijo']." ".$detalle['factura_fiscal']." --> Actualizada <br>";
		}
	}
	echo "</b>FIN";
?>
