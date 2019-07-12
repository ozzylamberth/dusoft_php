<?
	$VISTA='HTML';
	include 'includes/enviroment.inc.php';
		
	$dbconn = ADONewConnection('postgres');
		
	if (!($dbconn->Connect('127.0.0.1', 'siis', 'siis2006','SIIS'))) 
	{
		die(MsgOut("PERMISOS DB : Error en la Conexión a la Base de Datos",$dbconn->ErrorMsg()));
	}
	var_dump($dbconn->debug = true);
	
	$query = "
										SELECT	a.empresa_id,
														a.prefijo,
														a.numero,
														a.factura_profesional_id,
														b.profesional_id,
														b.tipo_id_profesional,
														a.fecha_registro,
														a.usuario_id,
														sum(valor_real) as valor_svh
										FROM 		voucher_honorarios_facturas_profesionales as a
										JOIN voucher_honorarios as b
										ON
										(
											a.empresa_id=b.empresa_id
											AND a.prefijo=b.prefijo
											AND a.numero=b.numero
										)
										GROUP BY 1,2,3,4,5,6,7,8
									";
	
	$result=$dbconn->Execute($query);
	
	if($dbconn->ErrorNo() != 0)
	{
		$this->error = "Error SELECT voucher_honorarios_facturas_profesionales";
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

	foreach($datos as $key=>$valor)
	{
		$query= "	
								SELECT a.documento_id_cxp,b.prefijo,b.numeracion
								FROM 	voucher_honorarios_parametros a,
											documentos b
								WHERE a.empresa_id='".$valor['empresa_id']."'
								AND a.documento_id_cxp=b.documento_id;";
		
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo OrdenesdePagos - GuardarOrdenPago SQL1";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			exit;
			return false;
		}
		else
		{  
			if($result->RecordCount()>0)
			{
				while (!$result->EOF) 
				{
					list($documento_id,$prefijo,$numero)=$result->FetchRow();
					$result->MoveNext();
				}
			}
		}
		
		$query ="
									INSERT INTO voucher_honorarios_cuentas_x_pagar
									(
										empresa_id,
										prefijo,
										numero,
										numero_factura_id,
										profesional_id,
										tipo_id_profesional,
										documento_id,
										valor,
										usuario_id,
										fecha_registro
									)
									VALUES
									(
										'".$valor['empresa_id']."',
										'$prefijo',
										$numero,
										'".$valor['factura_profesional_id']."',
										'".$valor['profesional_id']."',
										'".$valor['tipo_id_profesional']."',
										$documento_id,
										".$valor['valor_svh'].",
										".$valor['usuario_id'].",
										'".$valor['fecha_registro']."'
									);
								";
		
		$result=$dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error INSERT voucher_honorarios_cuentas_x_pagar";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			echo "ERROR - SQL 2";
			exit;
			return false;
		}
		else
		{
			$query="	UPDATE voucher_honorarios_facturas_profesionales SET
							prefijo_cxp='$prefijo',
							numero_cxp=$numero
							WHERE empresa_id= '".$valor['empresa_id']."'
							AND prefijo='".$valor['prefijo']."'
							AND numero=".$valor['numero'].";";
	
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) 
			{
				$this->error = "Error al UPDATE documentos";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				echo "Error al UPDATE voucher_honorarios_facturas_profesionales";
				exit;
				return false;
			}
			else
			{
			
				$query="UPDATE documentos SET
								numeracion=numeracion+1
								WHERE documento_id=".$documento_id."
								AND empresa_id='".$valor['empresa_id']."';";
				
				$result = $dbconn->Execute($query);
				
				if ($dbconn->ErrorNo() != 0) 
				{
					$this->error = "Error al UPDATE documentos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					echo "Error al UPDATE documentos";
					exit;
					return false;
				}
			}
		}
	}
?>