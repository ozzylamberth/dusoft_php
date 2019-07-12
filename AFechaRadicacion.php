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
							SELECT DISTINCT g.fecha_radicacion,c.numerodecuenta
							FROM voucher_honorarios_cuentas_x_pagar as a
							JOIN voucher_honorarios_facturas_profesionales as b
							ON
							(
								a.empresa_id=b.empresa_id
								AND a.prefijo=b.prefijo_cxp
								AND a.numero=b.numero_cxp
							)
							JOIN voucher_honorarios as c
							ON
							(
								b.empresa_id=c.empresa_id
								AND b.prefijo=c.prefijo
								AND b.numero=c.numero
							)
							JOIN cuentas as d
							ON
							(
								c.numerodecuenta=d.numerodecuenta
							)
							JOIN fac_facturas_cuentas as e
							ON
							(
								d.numerodecuenta=e.numerodecuenta
							)
							JOIN envios_detalle as f
							ON
							(
								e.empresa_id=f.empresa_id
								AND e.prefijo=f.prefijo
								AND e.factura_fiscal=f.factura_fiscal
							)
							JOIN envios as g
							ON
							(
								f.envio_id=g.envio_id
							)
							WHERE (a.prefijo_orden,a.numero_orden) IS NULL
							AND a.estado='1'
							AND g.sw_estado='1'
						";
	
	$result=$dbconn->Execute($query);
	
	if($dbconn->ErrorNo() != 0)
	{
		$this->error = "Error SELECT fecha_radicacion";
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
							SET fecha_radicacion='".$dat['fecha_radicacion']."'
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
