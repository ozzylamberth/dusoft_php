<?
			$VISTA='HTML';
			include 'includes/enviroment.inc.php';
			
			list($dbconn) = GetDBconn();
	
			$query = "SELECT ingreso,
										numerodecuenta ,
										estado_cuenta,
										estado_ingreso 
								FROM tmp_sacarsalida
								WHERE numerodecuenta NOT IN 
																	(
																		SELECT numerodecuenta
																		FROM fac_facturas_cuentas
																	);";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error SELECT tmp_sacarsalida";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							echo $this->mensajeDeError;
							return false;
			}
			$i = 0;

			while(!$result->EOF)
			{
				if($result->fields[2]=='1')
				{
					$query = "UPDATE cuentas SET estado='1'
										WHERE ingreso=".$result->fields[0]."";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
							$resultado['mensaje']='ERROR EN ACTUALIZAR cuentas'. $dbconn->ErrorMsg();
							echo $resultado['mensaje'];
					}
					//**********************************************************
					//**********************************************************
					$query = "UPDATE ingresos SET estado=".$result->fields[3].",fecha_cierre=null
										WHERE ingreso=".$result->fields[0]."";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
							$resultado['mensaje']='ERROR EN ACTUALIZAR EL INGRESO'. $dbconn->ErrorMsg();
							echo $resultado['mensaje'];
					}
					//**********************************************************
					//**********************************************************
					$query = "DELETE FROM ingresos_salidas
										WHERE ingreso=".$result->fields[0]."";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
							$resultado['mensaje']='ERROR EN DELETE ingresos_salidas'. $dbconn->ErrorMsg();
							echo $resultado['mensaje'];
					}
					//**********************************************************
					//**********************************************************
				}
				elseif($result->fields[2]=='2')
				{
					$query = "UPDATE cuentas SET estado='2'
										WHERE ingreso=".$result->fields[0]."";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
							$resultado['mensaje']='ERROR EN ACTUALIZAR cuentas'. $dbconn->ErrorMsg();
							echo $resultado['mensaje'];
					}
					//**********************************************************
					//**********************************************************
					$query = "UPDATE ingresos SET estado=".$result->fields[3].",fecha_cierre=null
										WHERE ingreso=".$result->fields[0]."";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
							$resultado['mensaje']='ERROR EN ACTUALIZAR EL INGRESO'. $dbconn->ErrorMsg();
							echo $resultado['mensaje'];
					}
					//**********************************************************
					//**********************************************************
					$query = "DELETE FROM ingresos_salidas
										WHERE ingreso=".$result->fields[0]."";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
							$resultado['mensaje']='ERROR EN DELETE ingresos_salidas'. $dbconn->ErrorMsg();
							echo $resultado['mensaje'];
					}
					//**********************************************************
					//**********************************************************
				}
				elseif($result->fields[2]=='3')
				{
					$query = "UPDATE cuentas SET estado='3'
										WHERE ingreso=".$result->fields[0]."";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
							$resultado['mensaje']='ERROR EN ACTUALIZAR cuentas'. $dbconn->ErrorMsg();
							echo $resultado['mensaje'];
					}
					//**********************************************************
					//**********************************************************
					$query = "UPDATE ingresos SET estado=".$result->fields[3].",fecha_cierre=null
										WHERE ingreso=".$result->fields[0]."";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
							$resultado['mensaje']='ERROR EN ACTUALIZAR EL INGRESO'. $dbconn->ErrorMsg();
							echo $resultado['mensaje'];
					}
					//**********************************************************
					//**********************************************************
					$query = "DELETE FROM ingresos_salidas
										WHERE ingreso=".$result->fields[0]."";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
							$resultado['mensaje']='ERROR EN DELETE ingresos_salidas'. $dbconn->ErrorMsg();
							echo $resultado['mensaje'];
					}
					//**********************************************************
					//**********************************************************
				}
/*				elseif($result->fields[2]=='5')
				{
					$query = "UPDATE cuentas SET estado='5'
										WHERE ingreso=".$result->fields[0]."";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
							$resultado['mensaje']='ERROR EN ACTUALIZAR cuentas'. $dbconn->ErrorMsg();
							echo $resultado['mensaje'];
					}
					//**********************************************************
					//**********************************************************
					$query = "UPDATE ingresos SET estado=".$result->fields[3].",fecha_cierre=''
										WHERE ingreso=".$result->fields[0]."";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
							$resultado['mensaje']='ERROR EN ACTUALIZAR EL INGRESO'. $dbconn->ErrorMsg();
							echo $resultado['mensaje'];
					}
					//**********************************************************
					//**********************************************************
				}*/
					$result->MoveNext();
				
					$i = $i+1;
			}

			echo "<center>TERMINADO: $i</center>";

			return true;

/*
count 39742
			SELECT a.ingreso, b.numerodecuenta, b.estado as estado_cuenta,
						c.estado as estado_ingreso, d.ingreso as ingreso_sal
			FROM pacientes_urgencias as a, cuentas as b, ingresos c,
					ingresos_salidas d
			WHERE a.sw_estado in('4','5','6')
			AND a.ingreso = b.ingreso
			AND a.ingreso = c.ingreso
			AND c.ingreso = d.ingreso
			AND b.numerodecuenta IN (select numerodecuenta 
															from fac_facturas_cuentas a, fac_facturas b
															where a.prefijo = b.prefijo 
															AND a.factura_fiscal = b.factura_fiscal
															AND b.estado NOT IN ('2','3'));
***************************************************
			CREATE TABLE tmp_sacarsalida(
			ingreso integer NOT NULL,
			numerodecuenta integer NOT NULL,
			estado_cuenta character(1) NOT NULL,
			estado_ingreso character(1) NOT NULL
			);

			INSERT INTO tmp_sacarsalida(
			ingreso ,
			numerodecuenta ,
			estado_cuenta,
			estado_ingreso 
			)
			SELECT a.ingreso, b.numerodecuenta, b.estado as estado_cuenta,
						c.estado as estado_ingreso, d.ingreso as ingreso_sal
			FROM pacientes_urgencias as a, cuentas as b, ingresos c,
					ingresos_salidas d
			WHERE a.sw_estado in('4','5','6')
			AND a.ingreso = b.ingreso
			AND a.ingreso = c.ingreso
			AND c.ingreso = d.ingreso
*/
	?>
