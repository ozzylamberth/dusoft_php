<?php
	/********************************************************************************* 
 	* $Id: hc_RegistroEvolucionReno_RegistroER.class.php,v 1.2 2007/02/01 20:51:01 luis Exp $ 
 	*
 	* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 	* @package IPSOFT-SIIS
 	* 
	* @author    Luis Alejandro vargas. 
	* @package   hc_RegistroEvolucionReno
	* 
 	**********************************************************************************/

	class RegistroER
	{
		function RegistroER()
		{
			return true;
		}
		
		function GetDatosEvolucion()
		{
			list($dbconn) = GetDBconn();
			
			$query ="SELECT *
							FROM pyp_renoproteccion_codigos_evolucion
							ORDER BY indice_orden";
			
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo RegistroEvolucionReno - GetDatosEvolucion - SQL";
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

		function GetDatosEvolucionRegistros($evolucion,$inscripcion)
		{
			
			list($dbconn) = GetDBconn();
			$query ="SELECT *
							FROM pyp_renoproteccion_codigos_evolucion_valores
							WHERE evolucion_id<=$evolucion
							AND inscripcion_id=$inscripcion";
			
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo RegistroEvolucionReno - GetDatosEvolucionRegistros - SQL";
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
		
		function GetDatosEvolucionConductas($evolucion,$inscripcion)
		{
			list($dbconn) = GetDBconn();
			
			
			$query="SELECT a.*,d.nombre,e.descripcion,TO_CHAR(b.fecha_ideal_proxima_cita,'YYYY-MM-DD') as fecha_ideal_proxima_cita,f.peso,f.taalta,f.talla,f.tabaja
							FROM pyp_renoproteccion_conducta as a
							JOIN pyp_evoluciones_procesos as b 
							ON
							(
								a.evolucion_id=b.evolucion_id
								AND a.inscripcion_id=b.inscripcion_id
							)
							LEFT JOIN profesionales_usuarios AS c
							ON
							(
								a.usuario_id=c.usuario_id
							)
							LEFT JOIN profesionales AS d 
							ON
							(
								c.tipo_tercero_id=d.tipo_id_tercero 
								AND c.tercero_id=d.tercero_id
							)
							LEFT JOIN tipos_profesionales AS e 
							ON
							(
								d.tipo_profesional=e.tipo_profesional
							)
							LEFT JOIN hc_signos_vitales_consultas AS f
							ON
							(
								a.evolucion_id=f.evolucion_id 
							)
							WHERE a.evolucion_id<=$evolucion
							AND a.inscripcion_id=$inscripcion
							ORDER BY a.evolucion_id 
							";
							
			
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo RegistroEvolucionReno - GetDatosEvolucionConductas - SQL";
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
		
		
		function GuardarRegistros($datos,$inscripcion,$evolucion)
		{
			$pfj=SessionGetVar("Prefijo");
			
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			
			$signos=$this->GetDatosSignosConsultas($evolucion);
			
			if(!$signos)
			{
				$query="SELECT nextval('hc_signos_vitales_consultas_signos_vitales_consulta_id_seq'::regclass);";
				
				$result = $dbconn->Execute($query);
				
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el SubModulo RegistroEvolucionReno - GuardarRegistros - SQL 1";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
				else
				{
					$sitio_id=$result->fields[0];
					
					$query ="INSERT INTO hc_signos_vitales_consultas
								(signos_vitales_consulta_id,taalta,tabaja,peso,talla,fecha_registro,evolucion_id)
								VALUES($sitio_id,".$datos['ta_alta'.$pfj].",".$datos['ta_baja'.$pfj].",".$datos['peso'.$pfj].",".$datos['talla'.$pfj].",now(),$evolucion);";
					
					$result = $dbconn->Execute($query);
					
					if($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el SubModulo RegistroEvolucionReno - GuardarRegistros - SQL 2";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				}
			}
			else
			{
				$query ="	UPDATE hc_signos_vitales_consultas 
									SET taalta=".$datos['ta_alta'.$pfj].",
											tabaja=".$datos['ta_baja'.$pfj].",
											peso=".$datos['peso'.$pfj].",
											talla=".$datos['talla'.$pfj]."
									WHERE evolucion_id=$evolucion";
							
				$result = $dbconn->Execute($query);
				
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el SubModulo RegistroEvolucionReno - GuardarRegistros - SQL 3";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
			}
			
			$query ="SELECT nextval('pyp_renoproteccion_conducta_pyp_renoproteccion_conducta_id_seq'::regclass);";
			
			$result = $dbconn->Execute($query);
			
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo RegistroEvolucionReno - GuardarRegistros - SQL 4";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
			else
			{
				$registro=$result->fields[0];
				
				if(!$datos['causa_cierre_caso'.$pfj])
					$datos['causa_cierre_caso'.$pfj]=0;
					
				$query ="INSERT INTO pyp_renoproteccion_conducta
									(
										pyp_renoproteccion_conducta_id,
										evolucion_id,
										inscripcion_id,
										estado_nutricional,
										estadio_kdoqi,
										riesgo_deterioro_acelerado,
										deterioro_acelerado,
										retinopatia,
										presencia_ulcera_pies,
										riesgo_ulcera_pies,
										adherencia_farmacologica,
										cambio_habitos_alimenticios,
										habito_actividad_fisica,
										asistencia_grupo_apoyo,
										cierre_caso,
										causa_cierre_caso,
										riesgo_psicosocial,
										lesion_organo_blanco,
										fecha_registro,
										usuario_id
									)
									VALUES
									(
										$registro,
										$evolucion,
										$inscripcion,
										'".$datos['estado_nutricional'.$pfj]."',
										".$datos['kdoqi'.$pfj].",
										".$datos['riesgo_deterioro_acelerado'.$pfj].",
										".$datos['deterioro_acelerado'.$pfj].",
										".$datos['retinopatia'.$pfj].",
										".$datos['presencia_ulcera_pies'.$pfj].",
										".$datos['riesgo_ulcera_pies'.$pfj].",
										".$datos['af'.$pfj].",
										".$datos['cambio_habitos_alimenticios'.$pfj].",
										".$datos['habito_actividad_fisica'.$pfj].",
										".$datos['asistencia_grupo_apoyo'.$pfj].",
										".$datos['cierre_caso'.$pfj].",
										".$datos['causa_cierre_caso'.$pfj].",
										".$datos['riesgo_psicosocial'.$pfj].",
										".$datos['lesion_organo_blanco'.$pfj].",
										now(),
										".UserGetUID()."
									);";
				
					$result = $dbconn->Execute($query);
					
					if($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el SubModulo RegistroEvolucionReno - GuardarRegistros - SQL 5";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				}
			
			
			for($i=0;$i<sizeof($datos['nombre'.$pfj]);$i++)
			{
				$query ="SELECT nextval('pyp_renoproteccion_codigos_evo_registro_codigo_evolucion_id_seq'::regclass);";
			
				$result = $dbconn->Execute($query);
				
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el SubModulo RegistroEvolucionReno - GuardarRegistros - SQL 6";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
				else
				{
					$codigo_evolucion_id=$result->fields[0];
					
					$codigos=explode("ç",$datos['nombre'.$pfj][$i]);
					$valor=$codigos[0];
					$codigo_id=$codigos[1];
					
					$query ="INSERT INTO pyp_renoproteccion_codigos_evolucion_valores 
									(
										registro_codigo_evolucion_id,
										valor,
										evolucion_id,
										inscripcion_id,
										codigo_evolucion_id,
										fecha_registro,
										usuario_id
									)
									VALUES
									(
										$codigo_evolucion_id,
										$valor,
										$evolucion,
										$inscripcion,
										$codigo_id,
										now(),
										".UserGetUID()."
									);";
							
					$result = $dbconn->Execute($query);
				
					if($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el SubModulo RegistroEvolucionReno- GuardarRegistros - SQL 7";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				}
			}

			$query="UPDATE pyp_evoluciones_procesos
										SET fecha_ideal_proxima_cita='".$this->FechaStamp($datos['proxima_cita'.$pfj])."'
										WHERE evolucion_id=$evolucion
										AND inscripcion_id=$inscripcion";
					
			$result = $dbconn->Execute($query);
			
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo RegistroEvolucionReno - GuardarRegistros - SQL 8";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
			
			$dbconn->CommitTrans();
			return true;
		}

		function GetDatosProfesional($usuario_id)
		{
			$obj=$this->obj;
			$pfj=$obj->frmPrefijo;
			
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			
			$query="SELECT b.nombre,c.descripcion
							FROM profesionales_usuarios as a
							LEFT JOIN profesionales as b on(a.tipo_tercero_id=b.tipo_id_tercero and a.tercero_id=b.tercero_id)
							LEFT JOIN tipos_profesionales as c on(b.tipo_profesional=c.tipo_profesional)
							WHERE a.usuario_id=$usuario_id";
							
			$result = $dbconn->Execute($query);		

			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo RegistroEvolucionReno - GetDatosProfesional - SQL";
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
		
		
		function GetDatosSignosConsultas($evolucion)
		{
			$obj=$this->obj;
			$pfj=$obj->frmPrefijo;
			
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			
			$query="SELECT peso,tabaja as ta_baja,taalta as ta_alta,talla
							FROM hc_signos_vitales_consultas
							WHERE evolucion_id=$evolucion;";
							
			$result = $dbconn->Execute($query);
			
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo RegistroEvolucionReno - GetDatosSignosConsultas - SQL";
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
		
		function GetDatosSignos($evolucion,$inscripcion,$usuario_id=null,$ingreso=null)
		{
			$obj=$this->obj;
			$pfj=$obj->frmPrefijo;
			
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			
			$query="SELECT a.peso,a.tabaja as ta_baja,a.taalta as ta_alta,b.*
							FROM hc_signos_vitales_consultas AS a,
							pyp_cpn_conducta AS b
							WHERE a.evolucion_id=b.evolucion_id 
							AND b.evolucion_id<=$evolucion
							AND b.inscripcion_id=$inscripcion;";
							
			$result = $dbconn->Execute($query);
			
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo RegistroEvolucionReno - GetDatosSignos - SQL";
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
		
		function ConsultaDatosEvolucion()
		{
			list($dbconn) = GetDBconn();
			
			$query ="SELECT *
							FROM pyp_renoproteccion_codigos_evolucion
							ORDER BY indice_orden";
			
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo RegistroEvolucionReno - ConsultaDatosEvolucion - SQL";
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

		function ConsultaDatosEvolucionRegistros($evolucion,$inscripcion)
		{
			
			list($dbconn) = GetDBconn();
			$query ="SELECT *
							FROM pyp_renoproteccion_codigos_evolucion_valores
							WHERE evolucion_id=$evolucion
							AND inscripcion_id=$inscripcion";
			
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo RegistroEvolucionReno - ConsultaDatosEvolucionRegistros - SQL";
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
		
		function ConsultaDatosEvolucionConductas($evolucion,$inscripcion)
		{
			list($dbconn) = GetDBconn();
			
			
			$query="SELECT a.*,d.nombre,e.descripcion,TO_CHAR(b.fecha_ideal_proxima_cita,'YYYY-MM-DD') as fecha_ideal_proxima_cita,f.peso,f.taalta,f.talla,f.tabaja
							FROM pyp_renoproteccion_conducta as a
							JOIN pyp_evoluciones_procesos as b 
							ON
							(
								a.evolucion_id=b.evolucion_id
								AND a.inscripcion_id=b.inscripcion_id
							)
							LEFT JOIN profesionales_usuarios AS c
							ON
							(
								a.usuario_id=c.usuario_id
							)
							LEFT JOIN profesionales AS d 
							ON
							(
								c.tipo_tercero_id=d.tipo_id_tercero 
								AND c.tercero_id=d.tercero_id
							)
							LEFT JOIN tipos_profesionales AS e 
							ON
							(
								d.tipo_profesional=e.tipo_profesional
							)
							LEFT JOIN hc_signos_vitales_consultas AS f
							ON
							(
								a.evolucion_id=f.evolucion_id 
							)
							WHERE a.evolucion_id=$evolucion
							AND a.inscripcion_id=$inscripcion
							";
							
			
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo RegistroEvolucionReno - ConsultaDatosEvolucionConductas - SQL";
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
		
		function GetCargosPruebas($programa)
		{
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			$query="
							SELECT a.cargo_cups,b.descripcion,a.alias
							FROM pyp_cargos a
							JOIN cups as b on(a.cargo_cups=b.cargo)
							JOIN apoyod_cargos as c on(c.cargo=b.cargo)
							WHERE a.programa_id=$programa
							ORDER BY a.indice_orden; 
						";
			
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo RegistroEvolucionReno - GetCargosPruebas - SQL";
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

		function GetSolicitudesCargos($evolucion,$inscripcion,$programa)
		{
			
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();

			$query="
							SELECT DISTINCT a.hc_os_solicitud_id,
											a.evolucion_id,
											a.inscripcion_id,
											b.cargo,
											c.descripcion
							FROM 		pyp_solicitudes_inscripciones AS a
							LEFT JOIN hc_os_solicitudes AS b
											ON
											(
												a.hc_os_solicitud_id=b.hc_os_solicitud_id
											)
							LEFT JOIN cups AS c
											ON
											(
												b.cargo=c.cargo
											)
							WHERE a.evolucion_id<=$evolucion
							AND a.inscripcion_id=$inscripcion
							AND a.programa_id=$programa
							";
						
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo RegistroEvolucionreno - GetSolicitudesCargos - SQL";
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

		function CalcularProximaCita($semana_gestante,$semana_proxima_sugerida,$indice_semana)
		{
			$dif=intval($semana_proxima_sugerida[($indice_semana+1)][rango_media]) - intval($semana_gestante);
			$dif=$dif*7;
			$tiempo=time()+(($dif-1)*24*60*60);
			$fecha=date("Y-m-d",$tiempo);
			return $fecha;
		}

		function GetEspecialidadesTotal()
		{
			list($dbconn) = GetDBconn();
			
			$query="SELECT a.*,b.cargo
							FROM especialidades as a
							JOIN especialidades_cargos as b
									ON
									(
										a.especialidad=b.especialidad
									)
							ORDER BY a.descripcion";
			
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo RegistroEvolucionReno - GetEspecialidadesTotal - SQL";
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
		
		
		function ActualizarEstadoProcesos($estado,$evolucion,$inscripcion)
		{
			$obj=$this->obj;
			$pfj=$obj->frmPrefijo;
			
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			
			$query="UPDATE pyp_evoluciones_procesos SET sw_estado='$estado'
							WHERE evolucion_id=$evolucion AND inscripcion_id=$inscripcion";
			
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo RegistroEvolucionGestacion- ActualizarEstadoProcesos - SQL";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
			
			$dbconn->CommitTrans();
			return true;
		}
		
		function ActualizarEstadoInscripcion($inscripcion,$estado)
		{
			$obj=$this->obj;
			$pfj=$obj->frmPrefijo;
			
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			
			$query="UPDATE pyp_inscripciones_pacientes SET estado='$estado'
							WHERE inscripcion_id=$inscripcion";
			
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo RegistroEvolucionGestacion- ActualizarEstadoProcesos - SQL";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
			
			$dbconn->CommitTrans();
			return true;
		}
		
		function GetExamenFisico($evolucion)
		{
			$obj=$this->obj;
			$pfj=$obj->frmPrefijo;
			
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			
			$query="SELECT CASE b.normal 
										WHEN 0 THEN 1 
										WHEN 1 THEN 2 
										END as mamas,
										evolucion_id
							FROM hc_tipos_sistemas as a
							LEFT JOIN  hc_sistemas as b
							ON
							(
								a.tipo_sistema_id=b.tipo_sistema_id
							)
							WHERE b.tipo_sistema_id=17
							AND b.evolucion_id=$evolucion
						";
			
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo RegistroEvolucionGestacion- GetEspecialidades - SQL";
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