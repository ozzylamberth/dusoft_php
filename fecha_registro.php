<?php
	
	$VISTA = "HTML";
	$_ROOT = "";
	
	include	"includes/enviroment.inc.php";
	list($dbconn)=GetDBConn();
	//$dbconn->debug=true;

	$sql= 'select a.fecha_registro, a.transaccion
	from cuentas_detalle a, cuentas_detalle_profesionales b
	where a.transaccion = b.transaccion';
	
	$rst = $dbconn->Execute($sql);
				
	if ($dbconn->ErrorNo() != 0)
	{
		$frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
		echo "<b class=\"label\">".$frmError['MensajeError']."</b>";
		return false;
	}
	
	$mezclas = array();
	while (!$rst->EOF)
	{
		$mezclas[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
		$rst->MoveNext();
	}
	$rst->Close();
	print_r($mezclas)."<br>";


	list($dbconn)=GetDBConn();
	
	foreach($mezclas as $key=> $transaccion)
	{
		$sql = "update voucher_honorarios
		set fecha_registro = '".$transaccion['fecha_registro']."'
		where transaccion = ".$key." ";
		
		$rst = $dbconn->Execute($sql);
	}
	echo "<b>todo sali� bien</b>";
	$rst->Close();
?>