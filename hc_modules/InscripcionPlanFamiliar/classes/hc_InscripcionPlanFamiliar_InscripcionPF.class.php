<?php
	/********************************************************************************* 
 	* $Id: hc_InscripcionPlanFamiliar_InscripcionPF.class.php,v 1.3 2007/02/01 20:54:43 luis Exp $ 
 	*
 	* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 	* @package IPSOFT-SIIS
 	* 
	* @author    Luis Alejandro vargas. 
	* @package   hc_InscripcionPlanFamiliar_InscripcionPF
	* 
 	**********************************************************************************/
	
	class InscripcionPF
	{
		function InscripcionPF()
		{
			return true;
		}

		function InscribirPF($datos)
		{
			$pfj=SessionGetVar("Prefijo");
			$datosPaciente=SessionGetVar("DatosPaciente");
			$programa=SessionGetVar("Programa");
			$evolucion=SessionGetVar("Evolucion");
			
			list($dbconn) = GetDBconn();
			$query="SELECT nextval('pyp_inscripciones_pacientes_inscripcion_id_seq'::regclass)";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo InscripcionPlanFliar - InscribirPF - SQL 1";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				$inscripcion=$result->fields[0];
				
				$query="INSERT INTO pyp_inscripciones_pacientes 
								(
									inscripcion_id,
									programa_id,
									tipo_id_paciente,
									paciente_id,
									usuario_id,
									fecha_inscripcion,
									estado
								)
								VALUES
								(
									$inscripcion,
									$programa,
									'".$datosPaciente['tipo_id_paciente']."',
									'".$datosPaciente['paciente_id']."',
									".UserGetUID().",
									now(),
									'1'
								)";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el SubModulo InscripcionPlanFliar - InscribirPF - SQL 2";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else
				{
				
					$query="INSERT INTO pyp_inscripcion_planificacion_fliar
								(
									inscripcion_id,
									num_hijos_vivos
								)
								VALUES
								(
									$inscripcion,
									".$datos['num_hijos_vivos'.$pfj]."
								)";
					$result = $dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el SubModulo InscripcionPlanFliar - InscribirPF - SQL 3 $query";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
					else
					{
						if($datos['bandera'.$pfj])
						{
							$query="SELECT nextval('hc_signos_vitales_consultas_signos_vitales_consulta_id_seq'::regclass)";
							$result = $dbconn->Execute($query);
							if($dbconn->ErrorNo() != 0)
							{
								$this->error = "Error al Cargar el SubModulo InscripcionPlanFliar - InscribirPF - SQL 4";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
							}
							
							$hc_signo_id=$result->fields[0];
							
							$query="INSERT INTO hc_signos_vitales_consultas
									(
										signos_vitales_consulta_id,
										peso,
										taalta,
										tabaja,
										evolucion_id,
										fecha_registro
									)
									VALUES
									(
										$hc_signo_id,
										".$datos['peso'.$pfj].",
										".$datos['ta_alta'.$pfj].",
										".$datos['ta_baja'.$pfj].",
										$evolucion,
										now()
									)";
							$result = $dbconn->Execute($query);
							if($dbconn->ErrorNo() != 0)
							{
								$this->error = "Error al Cargar el SubModulo InscripcionPlanFliar - InscribirPF - SQL 5";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
							}
						}
						
						$query="INSERT INTO pyp_evoluciones_procesos
								(
									inscripcion_id,
									evolucion_id
								)
								VALUES
								(
									$inscripcion,
									$evolucion
								)";
						$result = $dbconn->Execute($query);
						if($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el SubModulo InscripcionPlanFliar - InscribirPF - SQL 6";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}
					}
				}
			}
			
			return true;
		}

		function ValidaInscripcionPaciente($tipo_id_paciente,$paciente_id,$programa)
		{
		
			list($dbconn) = GetDBconn();
			
			$query="SELECT a.inscripcion_id
							FROM 	pyp_inscripciones_pacientes as a
							WHERE a.tipo_id_paciente='$tipo_id_paciente'
							AND a.paciente_id='$paciente_id'
							AND a.programa_id=$programa
							AND a.estado='1'";
			
			$result = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo InscripcionPlanFliar - ValidaInscripcionPaciente - SQL";
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
			
			if(empty($vars))
				return false;
			
			return $vars;	
		}
		
		function GetDatosSignos()
		{
			$evolucion=SessionGetVar("Evolucion");
			
			list($dbconn) = GetDBconn();
			$query="SELECT peso,tabaja,taalta
							FROM hc_signos_vitales_consultas
							WHERE evolucion_id=$evolucion";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo InscripcionPlanFliar - GetDatosSignos - SQL";
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
			
			return $vars;
		}
		
		function GetMetodosPF()
		{
			
			list($dbconn) = GetDBconn();
			
			$datosPaciente=SessionGetVar("DatosPaciente");
			
			if($datosPaciente['sexo_id']=='M') $sexo=1;
			if($datosPaciente['sexo_id']=='F') $sexo=2;
			
			$query="SELECT *
							FROM pyp_plan_fliar_metodos_planificacion
							WHERE sexo=$sexo OR sexo=3
							ORDER BY metodo_id";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo InscripcionPlanFliar - GetMetodosPF - SQL";
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
			
			return $vars;
		}
		
		function GetMotivosSuspencionPF()
		{
			
			list($dbconn) = GetDBconn();
			$query="SELECT *
							FROM pyp_plan_fliar_motivos_suspencion
							ORDER BY motivo_suspencion_id";
							
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo InscripcionPlanFliar - GetMotivosSuspencionPF - SQL";
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
			
			return $vars;
		}
		
		function GetDatosHistorialMetodosPF($inscripcion)
		{
			$datosPaciente=SessionGetVar("DatosPaciente");
			
			list($dbconn) = GetDBconn();
			$query="SELECT 	a.inscripcion_id,
											b.metodo_id,
											b.descripcion as desc_metodo,
											TO_CHAR(a.fecha_inicio,'YYYY-MM-DD') as fecha_ini,
											TO_CHAR(a.fecha_fin,'YYYY-MM-DD') as fecha_fin,
											c.motivo_suspencion_id,
											c.descripcion as desc_motivo,
											a.otro_metodo,
											a.otro_motivo_suspencion,
											b.sw_otro as sw_otro_met,
											c.sw_otro as sw_otro_mot
							FROM pyp_plan_fliar_historial_metodos_planificacion as a
							LEFT JOIN pyp_plan_fliar_metodos_planificacion as b
							ON
							(
								a.metodo_id=b.metodo_id
							)
							LEFT JOIN pyp_plan_fliar_motivos_suspencion as c
							ON
							(
								a.motivo_suspencion_id=c.motivo_suspencion_id
							)
							JOIN pyp_inscripciones_pacientes as d
							ON
							(
								a.inscripcion_id=d.inscripcion_id
							)
							WHERE a.inscripcion_id<=$inscripcion
							AND d.tipo_id_paciente='".$datosPaciente['tipo_id_paciente']."'
							AND d.paciente_id='".$datosPaciente['paciente_id']."'
							ORDER BY fecha_ini DESC";
							
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo InscripcionPlanFliar - GetDatosHistorialMetodosPF - SQL";
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
			
			return $vars;
		}
		
		
		function GuardarHistorialMPF($inscripcion,$param)
		{
		
			if(!$param[2])
				$param[2]='NULL';
			else
				$param[2]="'".$this->FechaStamp($param[2])."'";
				
			if(!$param[3])
				$param[3]='NULL';
			else
				$param[3]="'".$this->FechaStamp($param[3])."'";
				
			if(!$param[4])
				$param[4]='NULL';
				
			if(!$param[6])
				$param[6]='NULL';
			else
				$param[6]="'".$param[6]."'";
			
			if(!$param[7])
				$param[7]='NULL';
			else
				$param[7]="'".$param[7]."'";
				
			list($dbconn) = GetDBconn();
			
			$query="SELECT nextval('pyp_plan_fliar_historial_metodos_planificacion_historial_id_seq'::regclass);";
			
			$result = $dbconn->Execute($query);
			
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo InscripcionPlanFliar - GetDatosHistorialMetodosPF - SQL";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			
			$historial_id=$result->fields[0];
				
			$query="INSERT INTO pyp_plan_fliar_historial_metodos_planificacion
							(
								historial_id,
								inscripcion_id,
								metodo_id,
								fecha_inicio,
								fecha_fin,
								motivo_suspencion_id,
								otro_metodo,
								otro_motivo_suspencion
							)
							VALUES
							(
								$historial_id,
								$inscripcion,
								".$param[0].",
								".$param[2].",
								".$param[3].",
								".$param[4].",
								".$param[6].",
								".$param[7]."
							);";

			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo InscripcionPlanFliar - GetDatosHistorialMetodosPF - SQL";
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
			
			return $vars;
		}
		
		function ConsultaSolicitudes($evolucion,$inscripcion)
		{
			list($dbconn) = GetDBconn();

			$query="SELECT 	a.hc_os_solicitud_id,
											b.cargo,
											b.evolucion_id
							FROM 		pyp_solicitudes_inscripciones as a
							JOIN 		hc_os_solicitudes AS b ON 
							(
								a.hc_os_solicitud_id=b.hc_os_solicitud_id
							)
							WHERE a.evolucion_id<=".$evolucion."
							AND a.inscripcion_id=$inscripcion";
			
			$result = $dbconn->Execute($query);
	
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo InscripcionPlaFliar - ConsultaSolicitudes - SQL";
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
			return $vars;
		}
		
		function GetApoyosInicales($programa)
		{
			list($dbconn) = GetDBconn();
			
			$query="SELECT	b.cargo,
											b.descripcion,
											a.alias
							FROM		pyp_cargos a
							JOIN		cups as b 
											ON
											(
												a.cargo_cups=b.cargo
											)
							WHERE 	a.programa_id=$programa
							AND 		a.sw_inscripcion=1";
			
			$result = $dbconn->Execute($query);
	
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo InscripcionPlanFamiliar - GetApoyosInicales - SQL";
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
			return $vars;	
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
	
		function ErrorDB()
		{
			$this->frmErrorBD=$this->error."<br>".$this->mensajeDeError;
			return $this->frmErrorBD;
		}
		
	}
?>