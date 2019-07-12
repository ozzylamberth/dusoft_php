<?php



$VISTA='HTML';
include 'includes/enviroment.inc.php';

			list($dbconn) = GetDBconn();					
		/*	$query = '	
								DROP INDEX paciente;
								CREATE INDEX idx_ingresos_paciente_id ON ingresos USING btree (paciente_id);
								
								DROP INDEX idx_os_ordenes_servicios_paciente_id;
								CREATE INDEX idx_os_ordenes_servicios_paciente_id ON os_ordenes_servicios USING btree (paciente_id);
								ALTER TABLE os_ordenes_servicios DROP CONSTRAINT "$5";		
								ALTER TABLE os_ordenes_servicios ADD FOREIGN KEY (paciente_id, tipo_id_paciente) REFERENCES pacientes(paciente_id, tipo_id_paciente) ON UPDATE CASCADE ON DELETE RESTRICT;
						
								CREATE INDEX idx_hc_os_solicitudes_manuales_paciente_id ON hc_os_solicitudes_manuales USING btree (paciente_id);
								ALTER TABLE hc_os_solicitudes_manuales DROP CONSTRAINT "$3";
								ALTER TABLE hc_os_solicitudes_manuales ADD FOREIGN KEY (paciente_id, tipo_id_paciente) REFERENCES pacientes(paciente_id, tipo_id_paciente) ON UPDATE CASCADE ON DELETE RESTRICT;';
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error INSERT INTO egresos_no_atencion";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
			}			*/					
								
			$query = "
								CREATE INDEX idx_tarifarios_equivalencias_cargo_base ON tarifarios_equivalencias USING btree (cargo_base);
								CREATE INDEX idx_tarifarios_equivalencias_cargo ON tarifarios_equivalencias USING btree (tarifario_id, cargo);
			
								ALTER TABLE hc_os_solicitudes ADD COLUMN sw_no_autorizado character varying(6);
								COMMENT ON COLUMN hc_os_solicitudes.sw_no_autorizado IS '1=>no esta autorizado 0=>fue autorizado';
								ALTER TABLE hc_os_solicitudes ALTER COLUMN sw_no_autorizado SET DEFAULT 0;
								UPDATE hc_os_solicitudes SET sw_no_autorizado=0;
								ALTER TABLE hc_os_solicitudes ALTER COLUMN sw_no_autorizado SET NOT NULL;";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error INSERT INTO egresos_no_atencion";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
			}
						
			$query = "SELECT e.hc_os_solicitud_id
			          FROM hc_os_autorizaciones as e, autorizaciones as h
								WHERE h.sw_estado='1'
								and e.autorizacion_int=h.autorizacion;";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error INSERT INTO egresos_no_atencion";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
			}			
			while(!$result->EOF)
			{
					$query = "UPDATE hc_os_solicitudes SET sw_no_autorizado='1'
										WHERE hc_os_solicitud_id=".$result->fields[0].";";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error INSERT INTO egresos_no_atencion";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$dbconn->RollbackTrans();
									return false;
					}				
					$result->MoveNext();
			}		
			
			
			echo "terminado";
?>