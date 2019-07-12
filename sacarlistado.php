<?
			$VISTA='HTML';
			include 'includes/enviroment.inc.php';

			$dbconn = ADONewConnection('postgres');

			if (!($dbconn->Connect('192.1.1.30', 'siis', 'siis','SIIS_DESARROLLO'))) {
				die(MsgOut("PERMISOS DB : Error en la Conexión a la Base de Datos",$dbconn->ErrorMsg()));
			}

			$query = "SELECT a.ingreso, c.tipo_id_paciente, c.paciente_id, a.triage_id
								FROM pacientes_urgencias as a, ingresos as c
								WHERE a.sw_estado=1 and a.ingreso=c.ingreso";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error INSERT INTO egresos_no_atencion";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
			}

			while(!$result->EOF)
			{
					if(empty($result->fields[3]))
					{  $result->fields[3]='NULL';  }
					$query = "INSERT INTO egresos_no_atencion (
																					tipo_id_paciente,
																					paciente_id,
																					ingreso,
																					triage_id,
																					observacion,
																					fecha_registro,
																					usuario_id)
											VALUES('".$result->fields[1]."','".$result->fields[2]."',
											".$result->fields[0].",".$result->fields[3].",
											'USUARIO DE PRUEBA - SIIS','now()',2)";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error INSERT INTO egresos_no_atencion";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$dbconn->RollbackTrans();
									return false;
					}

					if(!empty($result->fields[3]))
					{
									$query = "UPDATE triages SET sw_estado=9
														WHERE triage_id=".$result->fields[3]."";
									$dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
											$this->error = "Error update PACIENTES_URGENCIAS";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											$dbconn->RollbackTrans();
											return false;
									}
					}

					$query = "UPDATE pacientes_urgencias SET sw_estado=9
										WHERE ingreso=".$result->fields[0]."";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error update PACIENTES_URGENCIAS";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
					}

					$query = "UPDATE ingresos SET estado=0
										WHERE ingreso=".$result->fields[0]."";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error update ingresos";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
					}

					$query = "UPDATE cuentas SET estado=0
										WHERE ingreso=".$result->fields[0]."";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error update ingresos";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
					}

					$result->MoveNext();
			}

			echo "SALIO BIEN";

			return true;


	?>

