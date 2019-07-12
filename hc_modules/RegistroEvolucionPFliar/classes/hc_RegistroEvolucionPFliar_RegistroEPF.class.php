<?php
	/********************************************************************************* 
 	* $Id: hc_RegistroEvolucionPFliar_RegistroEPF.class.php,v 1.3 2007/02/01 20:51:01 luis Exp $ 
 	*
 	* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 	* @package IPSOFT-SIIS
 	* 
	* @author    Luis Alejandro vargas. 
	* @package   hc_RegistroEvolucionPFliar
	* 
 	**********************************************************************************/

	class RegistroEPF
	{
		function RegistroEPF()
		{
			return true;
		}

		function GetDatosEvolucionPF($evolucion,$inscripcion,$sexo)
		{
			list($dbconn) = GetDBconn();
			
			if($sexo=='M')
			{
				$query="SELECT 	a.evolucion_id,
												a.inscripcion_id,
												a.metodo_id,
												g.descripcion as desc_metodo,
												g.sw_otro,
												a.otro_metodo,
												TO_CHAR(a.fecha_inicio,'YYYY-MM-DD') as fecha_ini,
												TO_CHAR(a.fecha_fin,'YYYY-MM-DD') as fecha_fin,
												a.autoexamen_de_mamas,
												a.autoexamen_de_testiculos,
												d.nombre,
												e.descripcion,
												a.fecha_registro
								FROM pyp_plan_fliar_datos_evolucion as a
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
								JOIN pyp_plan_fliar_metodos_planificacion AS g
								ON
								(
									a.metodo_id=g.metodo_id 
								)
								WHERE a.evolucion_id<=$evolucion
								AND a.inscripcion_id=$inscripcion
								ORDER BY a.evolucion_id 
								";
			}
			elseif($sexo=='F')
			{
				$query="SELECT 	a.evolucion_id,
												a.inscripcion_id,
												a.metodo_id,
												g.descripcion as desc_metodo,
												g.sw_otro,
												a.otro_metodo,
												TO_CHAR(a.fecha_inicio,'YYYY-MM-DD') as fecha_ini,
												TO_CHAR(a.fecha_fin,'YYYY-MM-DD') as fecha_fin,
												TO_CHAR(a.fecha_ultima_mestruacion,'YYYY-MM-DD') as fecha_ultima_mestruacion,
												a.autoexamen_de_mamas,
												a.mareos,
												a.cefalea,
												a.manchas_piel,
												a.acne,
												a.nauzeas,
												a.dolor_mamas,
												a.dolor_pelvico,
												a.expulsion_dispositivo,
												a.tratamiento_propio_leuconea,
												a.tratamiento_pareja_leuconea,
												a.sintomas_urinarios,
												a.hemorragia,
												a.varices,
												a.edema,
												a.cambios_comportamiento,
												a.satisfaccion_metodo,
												a.cambiar_metodo,
												a.cual_metodo,
												g1.descripcion as desc_cual,
												g1.sw_otro as sw_cual_otro,
												a.cual_otro_metodo,
												a.mama_izq_cuadrante_superior_externo,
												a.mama_izq_cuadrante_superior_interno,
												a.mama_izq_cuadrante_inferior_externo,
												a.mama_izq_cuadrante_inferior_interno,
												a.mama_izq_pezon,
												a.mama_izq_axila,
												a.mama_izq_piel,
												a.mama_der_cuadrante_superior_externo,
												a.mama_der_cuadrante_superior_interno,
												a.mama_der_cuadrante_inferior_externo,
												a.mama_der_cuadrante_inferior_interno,
												a.mama_der_pezon,
												a.mama_der_axila,
												a.mama_der_piel,
												a.cierre_caso,
												a.motivo_cierre_caso,
												d.nombre,
												e.descripcion,
												f.taalta,
												f.tabaja,
												a.fecha_registro
								FROM pyp_plan_fliar_datos_evolucion as a
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
								JOIN pyp_plan_fliar_metodos_planificacion AS g
								ON
								(
									a.metodo_id=g.metodo_id 
								)
								LEFT JOIN pyp_plan_fliar_metodos_planificacion AS g1
								ON
								(
									a.cual_metodo=g1.metodo_id 
								)
								WHERE a.evolucion_id<=$evolucion
								AND a.inscripcion_id=$inscripcion
								ORDER BY a.evolucion_id 
								";
			}
							
			
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo RegistroEvolucionPFliar - GetDatosEvolucionPF - SQL";
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
					$vars[0]['cont']=$result->RecordCount();
				}
			}
			
			return $vars;
		}
		
		
		function GuardarRegistros($datos,$inscripcion,$evolucion,$sexo)
		{
			$pfj=SessionGetVar("Prefijo");
			
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			

			$query ="SELECT 	nextval('pyp_plan_fliar_datos_evolucion_registro_id_seq'::regclass);";
			
			$result = $dbconn->Execute($query);
			
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo RegistroEvolucionPFliar - GuardarRegistros - SQL 1";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
			else
			{
				$registro=$result->fields[0];
				
				if($sexo=='M')
				{
					$datos['fecha_ini'.$pfj]=$this->FechaStamp($datos['fecha_ini'.$pfj]);
					
					
					if($datos['fecha_fin'.$pfj])
						$datos['fecha_fin'.$pfj]="'".$this->FechaStamp($datos['fecha_fin'.$pfj])."'";
					else
						$datos['fecha_fin'.$pfj]="NULL";
					
					$query="	INSERT INTO pyp_plan_fliar_datos_evolucion
										(
											registro_id,
											evolucion_id,
											inscripcion_id,
											metodo_id,
											otro_metodo,
											fecha_inicio,
											fecha_fin,
											autoexamen_de_mamas,
											autoexamen_de_testiculos,
											fecha_registro,
											usuario_id
										)
										VALUES
										(
											$registro,
											$evolucion,
											$inscripcion,
											".$datos['metodo'.$pfj].",
											'".$datos['otro_metodo'.$pfj]."',
											'".$datos['fecha_ini'.$pfj]."',
											".$datos['fecha_fin'.$pfj].",
											'".$datos['mamas'.$pfj]."',
											'".$datos['testiculos'.$pfj]."',
											now(),
											".UserGetUID()."
										);";
				}
				elseif($sexo=='F')
				{
					
					$signos=$this->GetDatosSignosConsultas($evolucion);
			
					if(!$signos)
					{
						$query="SELECT nextval('hc_signos_vitales_consultas_signos_vitales_consulta_id_seq'::regclass);";
						
						$result = $dbconn->Execute($query);
						
						if($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el SubModulo RegistroEvolucionPFliar - GuardarRegistros - SQL 2";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
						}
						else
						{
							$sitio_id=$result->fields[0];
							
							$query ="INSERT INTO hc_signos_vitales_consultas
										(signos_vitales_consulta_id,taalta,tabaja,fecha_registro,evolucion_id)
										VALUES($sitio_id,".$datos['ta_alta'.$pfj].",".$datos['ta_baja'.$pfj].",now(),$evolucion);";
							
							$result = $dbconn->Execute($query);
							
							if($dbconn->ErrorNo() != 0)
							{
								$this->error = "Error al Cargar el SubModulo RegistroEvolucionPFliar - GuardarRegistros - SQL 3";
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
													tabaja=".$datos['ta_baja'.$pfj]."
											WHERE evolucion_id=$evolucion";
									
						$result = $dbconn->Execute($query);
						
						if($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el SubModulo RegistroEvolucionPFliar - GuardarRegistros - SQL 4";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
						}
					}
					
					$datos['fecha_ultima_mestruacion'.$pfj]=$this->FechaStamp($datos['fecha_ultima_mestruacion'.$pfj]);
					$datos['fecha_inicio_del_metodo'.$pfj]=$this->FechaStamp($datos['fecha_inicio_del_metodo'.$pfj]);
					
					if($datos['fecha_terminacion_del_metodo'.$pfj])
						$datos['fecha_terminacion_del_metodo'.$pfj]="'".$this->FechaStamp($datos['fecha_terminacion_del_metodo'.$pfj])."'";
					else
						$datos['fecha_terminacion_del_metodo'.$pfj]="NULL";
					
					
					if(!$datos['otro_metodo'.$pfj])
						$datos['otro_metodo'.$pfj]="NULL";
					else
						$datos['otro_metodo'.$pfj]="'".$datos['otro_metodo'.$pfj]."'";
					
					if(!$datos['cual_otro_metodo'.$pfj])
						$datos['cual_otro_metodo'.$pfj]="NULL";
					else
						$datos['cual_otro_metodo'.$pfj]="'".$datos['cual_otro_metodo'.$pfj]."'";
						
					if($datos['cambiar_metodo'.$pfj]=='2')
						$datos['cual'.$pfj]="NULL";
					else
						$datos['cual'.$pfj]="'".$datos['cual'.$pfj]."'";
					
					if(!$datos['cierre_de_caso'.$pfj])
						$datos['motivo_cierre_de_caso'.$pfj]="NULL";
					else
						$datos['motivo_cierre_de_caso'.$pfj]="'".$datos['motivo_cierre_de_caso'.$pfj]."'";
					
					$query="	INSERT INTO pyp_plan_fliar_datos_evolucion
										(
											registro_id,
											evolucion_id,
											inscripcion_id,
											metodo_id,
											otro_metodo,
											fecha_ultima_mestruacion,
											fecha_inicio,
											fecha_fin,
											autoexamen_de_mamas,
											mareos,
											cefalea,
											manchas_piel,
											acne,
											nauzeas,
											dolor_mamas,
											dolor_pelvico,
											expulsion_dispositivo,
											tratamiento_propio_leuconea,
											tratamiento_pareja_leuconea,
											sintomas_urinarios,
											hemorragia,
											varices,
											edema,
											cambios_comportamiento,
											satisfaccion_metodo,
											cambiar_metodo,
											cual_metodo,
											cual_otro_metodo,
											mama_izq_cuadrante_superior_externo,
											mama_izq_cuadrante_superior_interno,
											mama_izq_cuadrante_inferior_externo,
											mama_izq_cuadrante_inferior_interno,
											mama_izq_pezon,
											mama_izq_axila,
											mama_izq_piel,
											mama_der_cuadrante_superior_externo,
											mama_der_cuadrante_superior_interno,
											mama_der_cuadrante_inferior_externo,
											mama_der_cuadrante_inferior_interno,
											mama_der_pezon,
											mama_der_axila,
											mama_der_piel,
											cierre_caso,
											motivo_cierre_caso,
											fecha_registro,
											usuario_id
										)
										VALUES
										(
											$registro,
											$evolucion,
											$inscripcion,
											".$datos['metodo'.$pfj].",
											".$datos['otro_metodo'.$pfj].",
											'".$datos['fecha_ultima_mestruacion'.$pfj]."',
											'".$datos['fecha_inicio_del_metodo'.$pfj]."',
											".$datos['fecha_terminacion_del_metodo'.$pfj].",
											'".$datos['realiza_periodicamente_autoexamen_de_mamas'.$pfj]."',
											'".$datos['mareos'.$pfj]."',
											'".$datos['cefalea'.$pfj]."',
											'".$datos['manchas_en_la_piel'.$pfj]."',
											'".$datos['acne'.$pfj]."',
											'".$datos['nauceas'.$pfj]."',
											'".$datos['dolor_mamas'.$pfj]."',
											'".$datos['dolor_pelvico'.$pfj]."',
											'".$datos['expulsion_del_dispositivo'.$pfj]."',
											'".$datos['tratamiento_propio_de_leucorrea'.$pfj]."',
											'".$datos['tratamiento_pareja_de_leucorrea'.$pfj]."',
											'".$datos['sintomas_urinarios'.$pfj]."',
											'".$datos['hemorragia'.$pfj]."',
											'".$datos['varices'.$pfj]."',
											'".$datos['edema'.$pfj]."',
											'".$datos['cambios_de_comportamiento'.$pfj]."',
											'".$datos['satisfaccion_del_metodo'.$pfj]."',
											'".$datos['cambiar_metodo'.$pfj]."',
											".$datos['cual'.$pfj].",
											".$datos['cual_otro_metodo'.$pfj].",
											'".$datos['cuadrante_superior_externo_(izq)'.$pfj]."',
											'".$datos['cuadrante_superior_interno_(izq)'.$pfj]."',
											'".$datos['cuadrante_inferior_externo_(izq)'.$pfj]."',
											'".$datos['cuadrante_inferior_interno_(izq)'.$pfj]."',
											'".$datos['pezon_(izq)'.$pfj]."',
											'".$datos['axila_(izq)'.$pfj]."',
											'".$datos['piel_(izq)'.$pfj]."',
											'".$datos['cuadrante_superior_externo_(der)'.$pfj]."',
											'".$datos['cuadrante_superior_interno_(der)'.$pfj]."',
											'".$datos['cuadrante_inferior_externo_(der)'.$pfj]."',
											'".$datos['cuadrante_inferior_interno_(der)'.$pfj]."',
											'".$datos['pezon_(der)'.$pfj]."',
											'".$datos['axila_(der)'.$pfj]."',
											'".$datos['piel_(der)'.$pfj]."',
											".$datos['cierre_de_caso'.$pfj].",
											".$datos['motivo_cierre_de_caso'.$pfj].",
											now(),
											".UserGetUID()."
										);
										";
				}
				
				$result = $dbconn->Execute($query);
				
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el SubModulo RegistroEvolucionPFliar - GuardarRegistros - SQL 5";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
			}
			
			/*for($i=0;$i<sizeof($datos['nombre'.$pfj]);$i++)
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
			}*/
			
			$dbconn->CommitTrans();
			return true;
		}

		function GetDatosProfesional($usuario_id)
		{
			
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
				$this->error = "Error al Cargar el SubModulo RegistroEvolucionPFliar - GetDatosProfesional - SQL";
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
				$this->error = "Error al Cargar el SubModulo RegistroEvolucionReno- GetDatosSignos - SQL";
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
				$this->error = "Error al Cargar el SubModulo RegistroEvolucionPFliar - GetCargosPruebas - SQL";
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
				$this->error = "Error al Cargar el Modulo RegistroEvolucionPFliar - GetSolicitudesCargos - SQL";
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
				$this->error = "Error al Cargar el Modulo RegistroEvolucionPFliar- ActualizarEstadoProcesos - SQL";
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
				$this->error = "Error al Cargar el Modulo RegistroEvolucionPFliar- ActualizarEstadoProcesos - SQL";
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
				$this->error = "Error al Cargar el Modulo RegistroEvolucionPFliar- GetExamenFisico - SQL";
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