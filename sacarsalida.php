<?
			$VISTA='HTML';
			include 'includes/enviroment.inc.php';
			
			list($dbconn) = GetDBconn();
	
			$query = "SELECT a.ingreso
								FROM pacientes_urgencias as a
								WHERE a.sw_estado in('4','5','6')";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error INSERT INTO egresos_no_atencion";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
			}

			while(!$result->EOF)
			{
					$query = "UPDATE cuentas SET estado='0'
										WHERE ingreso=".$result->fields[0]."";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
							$resultado['mensaje']='ERROR EN ACTUALIZAR EL INGRESO'. $dbconn->ErrorMsg();
					}
					
					$query = "UPDATE ingresos SET estado='2',fecha_cierre='now()'
										WHERE ingreso=".$result->fields[0]."";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
							$resultado['mensaje']='ERROR EN ACTUALIZAR EL INGRESO'. $dbconn->ErrorMsg();
					}
		
					$query = "INSERT INTO ingresos_salidas (ingreso,fecha_registro,usuario_id,observacion_salida)
										VALUES(".$result->fields[0].",'now()',2,'SALIDA POR USUARIO SIIS - PACIENTES PENDIENTES DE SALIDA')";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
							$resultado['mensaje']='ERROR EN ACTUALIZAR EL INGRESO'. $dbconn->ErrorMsg();
					}
															
					$result->MoveNext();
			}

			echo "TERMINADO, SALIO BIEN";

			return true;


	?>

