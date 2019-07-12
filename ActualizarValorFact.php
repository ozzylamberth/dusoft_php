<?
	$VISTA='HTML';
	include 'includes/enviroment.inc.php';
		
	$dbconn = ADONewConnection('postgres');
		
	if (!($dbconn->Connect('127.0.0.1', 'siis', 'siis2006','SIIS'))) 
	{
		die(MsgOut("PERMISOS DB : Error en la Conexión a la Base de Datos",$dbconn->ErrorMsg()));
	}
	var_dump($dbconn->debug = true);
	
	$query = "  SELECT	numero_factura_id,
											profesional_id,
											tipo_id_profesional,
											empresa_id,
											prefijo,
											numero,
											estado,
											valor
							FROM voucher_honorarios_cuentas_x_pagar
							ORDER BY numero_factura_id,profesional_id,tipo_id_profesional
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
				$datos[$result->fields[0]."-".$result->fields[1]."-".$result->fields[2]][]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
	}
	//echo "<pre>";
	//print_r($datos);

	foreach($datos as $key=>$valor)
	{
		$num_max=0;
		foreach($valor as $key1=>$valor1)
		{
			if(sizeof($valor) > 1)
			{
				if(!$num_max)
				{
					$query = "SELECT max(numero) as num_max
										FROM voucher_honorarios_cuentas_x_pagar
										WHERE numero_factura_id='".$valor1['numero_factura_id']."'
										AND profesional_id='".$valor1['profesional_id']."'
										AND tipo_id_profesional='".$valor1['tipo_id_profesional']."'
										AND estado='1'
									";

					$result=$dbconn->Execute($query);
					
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						echo "ERROR - SQL 3";
						exit;
						return false;
					}
					
					$num_max=$result->fields[0];	
				}
				
				if($valor1['numero'] < $num_max)
				{
					$query = "UPDATE voucher_honorarios_facturas_profesionales 
										SET numero_cxp='$num_max'
										WHERE empresa_id='".$valor1['empresa_id']."'
										AND prefijo_cxp='".$valor1['prefijo']."'
										AND numero_cxp=".$valor1['numero']."
								";
								
					$result=$dbconn->Execute($query);
					
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						echo "ERROR - SQL 4";
						exit;
						return false;
					}
					
					$query = "UPDATE voucher_honorarios_cuentas_x_pagar SET estado='0'
										WHERE empresa_id='".$valor1['empresa_id']."'
										AND prefijo='".$valor1['prefijo']."'
										AND numero=".$valor1['numero']."
										";
									
					$result=$dbconn->Execute($query);
					
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						echo "ERROR - SQL 5";
						exit;
						return false;
					}
					
				}
			}
		}
		
		if(!$num_max)
			$num_max=$valor[$key1]['numero'];
			
		$query = "SELECT sum(b.valor_real) as valor_fact
							FROM voucher_honorarios_facturas_profesionales as a,
							voucher_honorarios as b
							WHERE a.empresa_id=b.empresa_id 
							AND a.prefijo=b.prefijo
							AND a.numero=b.numero
							AND b.estado='1'
							AND b.valor_real > 0
							AND a.empresa_id='".$valor[$key1]['empresa_id']."'
							AND a.prefijo_cxp='".$valor[$key1]['prefijo']."'
							AND a.numero_cxp=".$num_max."
						";
						
		$result=$dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
			exit;
		}
		
		$valor_fact=$result->fields[0];
		
		if($valor_fact>0)
		{
			$query = "UPDATE voucher_honorarios_cuentas_x_pagar SET
								valor=".$valor_fact."
								WHERE numero_factura_id='".$valor[$key1]['numero_factura_id']."'
								AND profesional_id='".$valor[$key1]['profesional_id']."'
								AND tipo_id_profesional='".$valor[$key1]['tipo_id_profesional']."'
								AND estado='1'
								";
			
			$result=$dbconn->Execute($query);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				echo "ERROR - SQL 6";
				exit;
				return false;
			}
			
			$query = "UPDATE voucher_honorarios_cuentas_x_pagar SET
								valor_cruzado=".$valor_fact."
								WHERE numero_factura_id='".$valor[$key1]['numero_factura_id']."'
								AND profesional_id='".$valor[$key1]['profesional_id']."'
								AND tipo_id_profesional='".$valor[$key1]['tipo_id_profesional']."'
								AND (prefijo_orden,numero_orden) IS NOT NULL
								AND estado='1'
								";
								
			$result=$dbconn->Execute($query);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				echo "ERROR - SQL 7";
				exit;
				return false;
			}
		}
	}
?>
