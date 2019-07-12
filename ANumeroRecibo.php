<?
	$VISTA='HTML';
	include 'includes/enviroment.inc.php';
		
	$dbconn = ADONewConnection('postgres');
		
	if (!($dbconn->Connect('192.1.1.30', 'siis', 'siis2006','SIIS'))) 
	{
		die(MsgOut("PERMISOS DB : Error en la Conexión a la Base de Datos",$dbconn->ErrorMsg()));
	}
	var_dump($dbconn->debug = true);
	
	$query = 	"  
							SELECT A.numero_recibo,B.numerodecuenta
							FROM
							(
								(
								SELECT
								a.empresa_id, a.prefijo_factura as prefijo, a.factura_fiscal , a.prefijo::character varying || a.recibo_caja::character varying as numero_recibo
								FROM rc_detalle_tesoreria_facturas a,fac_facturas_cuentas b
								WHERE a.empresa_id=b.empresa_id
								AND a.prefijo_factura=b.prefijo
								AND a.factura_fiscal=b.factura_fiscal
								)
								UNION
								(
									SELECT 	empresa_id,prefijo,factura_fiscal,numero_recibo
									FROM rc_detalle_tesoreria_facturas_externas
								)
							) AS A, 
							fac_facturas_cuentas as B
							WHERE A.empresa_id=B.empresa_id
							AND A.prefijo=B.prefijo
							AND A.factura_fiscal=B.factura_fiscal;
						";
	
	$result=$dbconn->Execute($query);
	
	if($dbconn->ErrorNo() != 0)
	{
		$this->error = "Error SELECT numero_recibo";
    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
		echo "ERROR - SQL 1";
		exit;
		return false;
	}
	else
	{
		if($result->RecordCount()>0)
		{
			while(!$result->EOF)
			{
				$datos[]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
	}
	
	
	foreach($datos as $dat)
	{
		$query = 	"UPDATE voucher_honorarios
							SET numero_recibo='".$dat['numero_recibo']."'
							WHERE 	numerodecuenta=".$dat['numerodecuenta']."";
		
		$result=$dbconn->Execute($query);
	
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error UPDATE voucher_honorarios";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			echo "ERROR - SQL 2";
			exit;
			return false;
		}
	}
?>
