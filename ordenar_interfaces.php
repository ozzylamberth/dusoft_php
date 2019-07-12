<?php
	
	$VISTA = "HTML";
	$_ROOT = "";
	
	include	"includes/enviroment.inc.php";
	list($dbconn)=GetDBConn();
	//$dbconn->debug=true;

	$sql= 'select a.fecha_registro, a.transaccion
	from cuentas_detalle a, cuentas_detalle_profesionales b
	where a.transaccion = b.transaccion
	order by a.fecha_registro';
	
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
		$sql = "update cuentas_detalle_profesionales
		set transaccion = '".$transaccion['transaccion']."'
		where transaccion = ".$key." ";
		
		$rst = $dbconn->Execute($sql);
	}
	echo "<b>todo salió bien</b>";
	$rst->Close();

?>