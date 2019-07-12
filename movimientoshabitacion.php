<?php


$VISTA='HTML';

include 'includes/enviroment.inc.php';

			list($dbconn) = GetDBconn();		
			
	/*echo		$query = "ALTER TABLE movimientos_habitacion ADD COLUMN departamento character varying(6);
								ALTER TABLE movimientos_habitacion ADD COLUMN estacion_id 	character varying(4);";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error INSERT INTO egresos_no_atencion";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg(); echo "==>".$dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
			}
			*/
			
			$query = "SELECT a.movimiento_id, b.departamento, b.estacion_id
			          FROM movimientos_habitacion as a, ingresos_departamento as b
								WHERE a.ingreso_dpto_id=b.ingreso_dpto_id;";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error INSERT INTO egresos_no_atencion";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
			}			
			while(!$result->EOF)
			{
					$query = "UPDATE movimientos_habitacion SET departamento='".$result->fields[1]."',
																											estacion_id='".$result->fields[2]."'
										WHERE movimiento_id=".$result->fields[0].";";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error INSERT INTO egresos_no_atencion"; echo "==>".$dbconn->ErrorMsg();
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$dbconn->RollbackTrans();
									return false;
					}				
					$result->MoveNext();
			}		
			
			echo "terminado";
			
?>