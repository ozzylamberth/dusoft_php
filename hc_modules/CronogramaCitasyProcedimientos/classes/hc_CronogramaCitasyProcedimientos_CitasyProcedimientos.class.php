<?php
	/********************************************************************************* 
 	* $Id: hc_CronogramaCitasyProcedimientos_CitasyProcedimientos.class.php,v 1.2 2007/02/01 20:44:37 luis Exp $ 
 	*
 	* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 	* @package IPSOFT-SIIS
 	* 
	* @author    Luis Alejandro vargas. 
	* @package   hc_CronogramaCitasyProcedimientos
	* 
 	**********************************************************************************/

	class CitasyProcedimientos
	{
		
		function CitasyProcedimientos()
		{
			return true;
		}
		
		function GetListaProcedimientos($programa)
		{
			
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			
			$query ="SELECT b.cargo,
											b.descripcion,
											a.observacion,
											a.alias
							FROM 		pyp_cargos AS a
							JOIN 		cups AS b 
											ON
											(
												a.cargo_cups=b.cargo
											)
							WHERE a.programa_id=$programa
							ORDER BY b.descripcion";
			
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo RegistroEvolucionGestacion- GetListaProcedimientos - SQL";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				if($result->RecordCount() > 0)
				{
					while(!$result->EOF)
					{
						$vars[]=$result->GetRowAssoc($toUpper=false);
						$result->MoveNext();
					}
				}
			}
			
			$dbconn->CommitTrans();
			return $vars;
		}
		
		function GetDatosProcedimientos($programa)
		{
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			
			$query="SELECT 	a.cargo_cups,
											b.periodo_id,
											b.periodo_metrica
							FROM 		pyp_programas_cargos_periodo a
							JOIN 		pyp_periodos_programa as b
											ON
											(
												a.periodo_id=b.periodo_id 
												AND 
												a.programa_id=b.programa_id
											)
											JOIN pyp_programas as c ON
											(
												b.programa_id=c.programa_id
											)
							LEFT JOIN pyp_cargos as d 
											ON
											(
												c.programa_id=d.programa_id 
												AND 
												a.cargo_cups=d.cargo_cups
											)
							WHERE a.programa_id=$programa
							ORDER BY b.periodo_metrica";
			
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo RegistroEvolucionGestacion- GetDatosProcedimientos - SQL";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				if($result->RecordCount() > 0)
				{
					while(!$result->EOF)
					{
						$vars[]=$result->GetRowAssoc($toUpper=false);
						$result->MoveNext();
					}
				}
			}
			
			$dbconn->CommitTrans();
			return $vars;
		}

		function GetDatosProfesional($usuario_id)
		{
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			
			$query="SELECT 	b.nombre,
											c.descripcion
							FROM 	profesionales_usuarios AS a
										LEFT JOIN profesionales AS b 
												ON
												(
													a.tipo_tercero_id=b.tipo_id_tercero 
													AND 
													a.tercero_id=b.tercero_id
												)
										LEFT JOIN tipos_profesionales AS c 
												ON
												(
													b.tipo_profesional=c.tipo_profesional
												)
							WHERE a.usuario_id=$usuario_id";
			
			$result = $dbconn->Execute($query);		

			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo RegistroEvolucionGestacion- GetDatosProfesional - SQL";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				if($result->RecordCount() > 0)
				{
					while(!$result->EOF)
					{
						$vars[]=$result->GetRowAssoc($toUpper=false);
						$result->MoveNext();
					}
				}
			}
			
			$dbconn->CommitTrans();
			return $vars;
		}

		function GetTipoProfesionalSemana($programa)
		{
			
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			
			$query="SELECT periodo_id,tipo_profesional
							FROM pyp_profesional_periodos_programa
							WHERE programa_id=$programa";
			
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo RegistroEvolucionGestacion- GetRegistrosEvoluciones - SQL";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
			else
			{
				if($result->RecordCount() > 0)
				{
					while(!$result->EOF)
					{
						$vars[]=$result->GetRowAssoc($toUpper=false);
						$result->MoveNext();
					}
				}
			}
			
			$dbconn->CommitTrans();
			return $vars;
		}
		
		function GetDatosProcedimientosSolicitados($evolucion,$inscripcion,$programa)
		{
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			
			$query="SELECT a.*,d.sw_estado 
							FROM pyp_procedimientos_solicitados AS a 
							JOIN pyp_evoluciones_procesos AS b
									ON
									(
										a.inscripcion_id=b.inscripcion_id 
										AND 
										a.evolucion_id=b.evolucion_id
									)
							JOIN pyp_solicitudes_inscripciones AS c
									ON
									(
										b.inscripcion_id=c.inscripcion_id 
										AND 
										b.evolucion_id=c.evolucion_id
									)
							JOIN hc_os_solicitudes AS d 
									ON
									(
										c.hc_os_solicitud_id=d.hc_os_solicitud_id 
										AND 
										a.cargo_cups=d.cargo 
										AND 
										c.evolucion_id=d.evolucion_id
									)
							WHERE a.evolucion_id<=$evolucion
							AND a.inscripcion_id=$inscripcion
							AND a.programa_id=$programa";
			
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo hc_CronogramaCitasyProcedimientos - GetDatosProcedimientosSolicitados - SQL";
				$this->mensajeDeError = "Error DB : ". $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				if($result->RecordCount() > 0)
				{
					while(!$result->EOF)
					{
						$vars[]=$result->GetRowAssoc($toUpper=false);
						$result->MoveNext();
					}
				}
			}
			
			$dbconn->CommitTrans();
			return $vars;
		}
		
		function GuardarProcedimientosSolicitados($evolucion,$inscripcion,$programa,$proc,$periodo_sugerido,$periodo_solicitado)
		{
			$pfj=SessionGetVar("Prefijo");
			$plan=SessionGetVar("Plan");
			
			
			list($dbconn) = GetDBconn();
			
			for($i=0;$i<sizeof($proc);$i++)
			{
				$query1="SELECT nextval('hc_os_solicitudes_hc_os_solicitud_id_seq')";
				$result=$dbconn->Execute($query1);
				$hc_os_solicitud_id=$result->fields[0];

				$query="INSERT INTO hc_os_solicitudes
				(hc_os_solicitud_id, evolucion_id, cargo, os_tipo_solicitud_id, plan_id)
				VALUES
				($hc_os_solicitud_id,".$evolucion.",
				'".$proc[$i]."', '".ModuloGetVar('','','TipoSolicitudApoyod')."',
				".$plan.")";
				
				$result=$dbconn->Execute($query);
				
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo hc_CronogramaCitasyProcedimientos- GuardarProcedimientosSolicitados - SQL 1";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					
					return false;
				}
				else
				{
					$query="INSERT INTO hc_os_solicitudes_apoyod
								(hc_os_solicitud_id, apoyod_tipo_id)
								VALUES ($hc_os_solicitud_id, '".ModuloGetVar('','','TipoApoyod')."');";
	
					$result=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al insertar en hc_CronogramaCitasyProcedimientos - hc_os_solicitudes_apoyod - SQL 2";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();

						return false;
					}
				}
				
				$query ="INSERT INTO pyp_solicitudes_inscripciones (evolucion_id,inscripcion_id,hc_os_solicitud_id,programa_id) VALUES
								($evolucion,$inscripcion,$hc_os_solicitud_id,$programa)";
				
				$result=$dbconn->Execute($query);
				
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo hc_CronogramaCitasyProcedimientos- GuardarProcedimientosSolicitados - SQL 3";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
						
				$query ="INSERT INTO pyp_procedimientos_solicitados(hc_os_solicitud_id,evolucion_id,inscripcion_id,periodo_sugerido,programa_id,cargo_cups,periodo_solicitud) VALUES 
									($hc_os_solicitud_id,$evolucion,$inscripcion,$periodo_sugerido,$programa,'".$proc[$i]."',$periodo_solicitado);";

				$result = $dbconn->Execute($query);
		
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo hc_CronogramaCitasyProcedimientos- GuardarProcedimientosSolicitados - SQL 4";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
			}
			
			return true;
		}
		
		function ErrorDB()
		{
			$this->frmErrorBD=$this->error."<br>".$this->mensajeDeError;
			return $this->frmErrorBD;
		}

		function FechaStamp($fecha)
		{
			if($fecha)
			{
				$fech = strtok ($fecha,"-");
				for($l=0;$l<3;$l++)
				{
					$date[$l]=$fech;
					$fech = strtok ("-");
				}
	
				return  ceil($date[2])."-".str_pad(ceil($date[1]),2,0,STR_PAD_LEFT)."-".str_pad(ceil($date[0]),2,0,STR_PAD_LEFT);
			}
		}
	}
?>