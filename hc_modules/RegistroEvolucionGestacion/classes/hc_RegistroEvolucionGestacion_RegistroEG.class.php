<?php
/********************************************************************************* 
* $Id: hc_RegistroEvolucionGestacion_RegistroEG.class.php,v 1.3 2007/02/01 20:51:01 luis Exp $ 
*
* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
* @package IPSOFT-SIIS
* 
* @author    Luis Alejandro vargas. 
* @package   hc_RegistroEvolucionGestacion
* 
**********************************************************************************/
	class RegistroEG
	{
		function RegistroEG()
		{
			return true;
		}
		
		function GetDatosEvolucion()
		{
			$pfj=SessionGetVar("Prefijo");
			
			list($dbconn) = GetDBconn();
			 
			
			$query ="SELECT *
							FROM pyp_cpn_codigos_evolucion_gestacion
							ORDER BY indice_orden";
			
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo RegistroEvolucionGestacion- GetDatos - SQL";
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
							FROM pyp_cpn_codigos_evolucion_gestacion_valores
							WHERE evolucion_id<=$evolucion
							AND inscripcion_id=$inscripcion";
			
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo RegistroEvolucionGestacion- GetDatos - SQL";
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
			 
			
			$query="SELECT 	a.*,
											d.nombre,
											e.descripcion,
											TO_CHAR(b.fecha_ideal_proxima_cita,'YYYY-MM-DD') as fecha_ideal_proxima_cita,
											f.peso,
											f.taalta,
											f.tabaja
							FROM pyp_cpn_conducta as a
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
							";
			
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo RegistroEvolucionGestacion - GetDatosEvolucionConductas - SQL";
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
		
		
		function GuardarRegistros($datos,$semana,$semana_gestante,$inscripcion,$evolucion)
		{
			$ingreso=SessionGetVar("Ingreso");
			$pfj=SessiongetVar("Prefijo");
			
			list($dbconn) = GetDBconn();
			 
			
			$signos=$this->GetDatosSignosConsultas($evolucion);
			
			if(!$signos)
			{
				$query="SELECT nextval('hc_signos_vitales_consultas_signos_vitales_consulta_id_seq'::regclass);";
				
				$result = $dbconn->Execute($query);
				
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el SubModulo RegistroEvolucionGestacion - GuardarRegistros - SQL 1";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					  
					return false;
				}
				else
				{
					$sitio_id=$result->fields[0];
					
					$query="INSERT INTO hc_signos_vitales_consultas
									(
										signos_vitales_consulta_id,
										taalta,
										tabaja,
										peso,
										fecha_registro,
										evolucion_id
									)
									VALUES
									(
										$sitio_id,
										".$datos['ta_alta'.$pfj].",
										".$datos['ta_baja'.$pfj].",
										".$datos['peso'.$pfj].",
										now(),
										$evolucion
									);";
					
					$result = $dbconn->Execute($query);
					
					if($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el SubModulo RegistroEvolucionGestacion - GuardarRegistros - SQL 2";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						  
						return false;
					}
				}
			}
			
			$mamas=$this->GetExamenFisico();
			
			if(!$mamas)
			{
				$query="SELECT nextval(('hc_sistema_id_seq'::text)::regclass);";
				
				$result = $dbconn->Execute($query);
				
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el SubModulo RegistroEvolucionGestacion - GuardarRegistros - SQL 3";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					  
					return false;
				}
				else
				{
					$sis_id=$result->fields[0];
					
					$query="INSERT INTO hc_sistemas
									(
										hc_sistema_id,
										normal,
										anormal,
										tipo_sistema_id,
										evolucion_id,
										ingreso
									)
									VALUES
									(
										$sis_id,
										".$datos['mamas'.$pfj].",
										' ',
										17,
										$evolucion,
										".$ingreso."
									);";
					
					$result = $dbconn->Execute($query);
					
					if($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el SubModulo RegistroEvolucionGestacion - GuardarRegistros - SQL 4";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						  
						return false;
					}
				}
			}

			
			/*$query ="SELECT max(pyp_cpn_conducta_id)
							 FROM pyp_cpn_conducta;";
			
			$result = $dbconn->Execute($query);
			
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo RegistroEvolucionGestacion - GuardarRegistros - SQL 5";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				  
				return false;
			}
			
			$query ="SELECT setval('pyp_cpn_conducta_pyp_cpn_conducta_id_seq',".$result->fields[0].");";
			
			$result = $dbconn->Execute($query);
			
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo RegistroEvolucionGestacion - GuardarRegistros - SQL 6";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				  
				return false;
			}*/
			
			$query ="SELECT nextval('pyp_cpn_conducta_pyp_cpn_conducta_id_seq'::regclass);";
			
			$result = $dbconn->Execute($query);
			
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo RegistroEvolucionGestacion - GuardarRegistros - SQL 7";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				  
				return false;
			}
			else
			{
				$registro=$result->fields[0];

				if(!$datos['riesgos_especifico'.$pfj][0])
					$datos['riesgos_especifico'.$pfj][0]="NULL";
					
				if(!$datos['riesgos_especifico'.$pfj][1])
				$datos['riesgos_especifico'.$pfj][1]="NULL";
				
				if(!$datos['riesgos_especifico'.$pfj][2])
					$datos['riesgos_especifico'.$pfj][2]="NULL";
				
				$query ="INSERT INTO pyp_cpn_conducta
									(
										pyp_cpn_conducta_id,
										evolucion_id,
										inscripcion_id,
										altura_uterina,
										mamas,
										especuloscopia,
										estado_nutricional,
										presentacion_fetal,
										fcf,
										actividad_uterina,
										clasificacion_riesgo,
										riesgo_biologico,
										riesgo_psicosocial,
										hospitalizacion_antes_cpn,
										asesoria_pretest,
										asesoria_postest,
										vacunacion_tt,
										cierre_caso,
										riesgo_especifico1,
										riesgo_especifico2,
										riesgo_especifico3,
										semana_sugerida,
										semana_actual,
										fecha_registro,
										usuario_id
									)
									VALUES
									(
										$registro,
										$evolucion,
										$inscripcion,
										".$datos['altura_uterina'.$pfj].",
										".$datos['mamas'.$pfj].",
										".$datos['especu'.$pfj].",
										".$datos['estado_nutricional'.$pfj].",
										".$datos['presentacion_fetal'.$pfj].",
										".$datos['fcf'.$pfj].",
										".$datos['actividad_uterina'.$pfj].",
										".$datos['clasifi_riesgo'.$pfj].",
										".$datos['riesgo_bio'.$pfj].",
										".$datos['riesgo_psico'.$pfj].",
										".$datos['hospt_cpn'.$pfj].",
										".$datos['pretest'.$pfj].",
										".$datos['postest'.$pfj].",
										".$datos['vacunacion_tt'.$pfj].",
										".$datos['cierre_caso'.$pfj].",
										".$datos['riesgos_especifico'.$pfj][0].",
										".$datos['riesgos_especifico'.$pfj][1].",
										".$datos['riesgos_especifico'.$pfj][2].",
										$semana,
										$semana_gestante,
										now(),
										".UserGetUID()."
									);";
				
					$result = $dbconn->Execute($query);
					
					if($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el SubModulo RegistroEvolucionGestacion - GuardarRegistros - SQL 8";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						  
						return false;
					}
				}
			
			/*$query ="SELECT max(registro_codigo_evolucion_id)
							FROM pyp_cpn_codigos_evolucion_gestacion_valores;";
			
			$result = $dbconn->Execute($query);
			
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo RegistroEvolucionGestacion - GuardarRegistros - SQL 9";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				
				return false;
			}
			
			$query ="SELECT setval('pyp_cpn_codigos_evolucion_gest_registro_codigo_evolucion_id_seq',".$result->fields[0].");";
		
			$result = $dbconn->Execute($query);
			
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo RegistroEvolucionGestacion - GuardarRegistros - SQL 10";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					
				return false;
			}
			*/
			for($i=0;$i<sizeof($datos['nombre'.$pfj]);$i++)
			{
				$query ="SELECT nextval('pyp_cpn_codigos_evolucion_gest_registro_codigo_evolucion_id_seq');";
			
				$result = $dbconn->Execute($query);
				
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el SubModulo RegistroEvolucionGestacion - GuardarRegistros - SQL 11";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					  
					return false;
				}
				else
				{
					$codigo_evolucion_id=$result->fields[0];
					
					$codigos=explode("ç",$datos['nombre'.$pfj][$i]);
					$valor=$codigos[0];
					$codigo_id=$codigos[1];
					
					$query ="INSERT INTO pyp_cpn_codigos_evolucion_gestacion_valores 
									(
										registro_codigo_evolucion_id,
										valor,
										evolucion_id,
										inscripcion_id,
										codigo_evolucion_id,
										semana_sugerida,
										semana_actual,
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
										$semana,
										$semana_gestante,
										now(),
										".UserGetUID()."
									);";
							
					$result = $dbconn->Execute($query);
				
					if($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el SubModulo RegistroEvolucionGestacion- GuardarRegistros - SQL 12";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						  
						return false;
					}
				}
			}
			
			
			$query="UPDATE pyp_evoluciones_procesos
										SET fecha_ideal_proxima_cita='".$datos['fecha_ideal_cita'.$pfj]."'
										WHERE evolucion_id=$evolucion
										AND inscripcion_id=$inscripcion";
					
			$result = $dbconn->Execute($query);
			
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo RegistroEvolucionGestacion - GuardarRegistros - SQL 13";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				  
				return false;
			}
			return true;
		}

		/**********************************************************************************************************/
		
		function GetDatosProfesional($usuario_id)
		{
			
			list($dbconn) = GetDBconn();
			 
			
			$query="SELECT b.nombre,c.descripcion
							FROM profesionales_usuarios as a
							LEFT JOIN profesionales as b on(a.tipo_tercero_id=b.tipo_id_tercero and a.tercero_id=b.tercero_id)
							LEFT JOIN tipos_profesionales as c on(b.tipo_profesional=c.tipo_profesional)
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
			
			  
			return $vars;
		}
		
		
		function GetDatosSignosConsultas($evolucion)
		{
			
			list($dbconn) = GetDBconn();
			 
			
			$query="SELECT peso,tabaja as ta_baja,taalta as ta_alta
							FROM hc_signos_vitales_consultas
							WHERE evolucion_id=$evolucion;";
							
			$result = $dbconn->Execute($query);
			
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo RegistroEvolucionGestacion- GetDatosSignos - SQL";
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
		
		function GetDatosSignos($evolucion,$inscripcion,$usuario_id=null,$ingreso=null)
		{
			
			list($dbconn) = GetDBconn();
			 
			
			$query="SELECT a.peso,a.tabaja as ta_baja,a.taalta as ta_alta,b.*
							FROM hc_signos_vitales_consultas AS a,
							pyp_cpn_conducta AS b
							WHERE a.evolucion_id=b.evolucion_id 
							AND b.evolucion_id<=$evolucion
							AND b.inscripcion_id=$inscripcion;";
							
			$result = $dbconn->Execute($query);
			
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo RegistroEvolucionGestacion- GetDatosSignos - SQL";
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
		
		function GetRegistrosEvoluciones($evolucion,$inscripcion)
		{
			list($dbconn) = GetDBconn();
			 
			
			$query ="SELECT a.*,b.*,d.nombre,e.descripcion
							FROM pyp_cpn_datos_evolucion_gestacion_valores AS a 
							JOIN pyp_evoluciones_procesos AS b
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
							WHERE a.evolucion_id<=$evolucion
							AND a.inscripcion_id=$inscripcion
							";
			
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo RegistroEvolucionGestacion- GetRegistrosEvoluciones - SQL";
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

		function GuardarRegistroEvolucion($datos,$datosEvolucionID,$semana,$semana_gestante,$inscripcion,$evolucion)
		{
			
			list($dbconn) = GetDBconn();
			 

			$registro=$this->GetMaxRegistroID();
			
			for($i=0;$i<sizeof($datos);$i++)
			{
				if($datos[$i]==-1)
					$datos[$i]=0;

				if($i!=sizeof($datos)-3 AND $i!=sizeof($datos)-1)
				{
					$query ="INSERT INTO pyp_cpn_datos_evolucion_gestacion_valores 
										(registro_datos_evolucion_id,valor,evolucion_id,inscripcion_id,dato_evolucion_id,semana_sugerida,semana_actual,fecha_registro,usuario_id)
										VALUES(".$registro.",'".$datos[$i]."',".$evolucion.",$inscripcion,".$datosEvolucionID[$i].",$semana,$semana_gestante,now(),".UserGetUID().");";
					
					$result = $dbconn->Execute($query);
					
					if($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el Modulo RegistroEvolucionGestacion- GuardarRegistroEvolucion - SQL 1";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						  
						return false;
					}
					$registro++;
				}
			}
			$query="UPDATE pyp_evoluciones_procesos
										SET fecha_ideal_proxima_cita='".$datos[sizeof($datos)-3]."'
										WHERE evolucion_id=$evolucion
										AND inscripcion_id=$inscripcion";
					
			$result = $dbconn->Execute($query);
			
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo RegistroEvolucionGestacion- GuardarRegistroEvolucion - SQL 2";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				  
				return false;
			}
			return true;
		}
		
		function GuardarRegistroEvolucionConductas($datos,$semana,$semana_gestante,$inscripcion,$evolucion)
		{
		
			$ingreso=SessionGetVar("Ingreso");
			
			list($dbconn) = GetDBconn();
			 
			
			$a=0;
			$b=0;
			
			if($this->GetDatosSignos($evolucion,$inscripcion))
				$a=1;
			
			if($this->GetExamenFisico($evolucion))
				$b=1;
			
			if($a==0)
			{
				$sitio_id=$this->GetMaxsitioID();
				
				$query ="INSERT INTO hc_signos_vitales_consultas
								(
									signos_vitales_consulta_id,
									taalta,
									tabaja,
									peso,
									fecha_registro,
									evolucion_id
								)
								VALUES
								(
									$sitio_id,
									".$datos[1].",
									".$datos[2].",
									".$datos[0].",
									now(),
									$evolucion
								);";
				
				$result = $dbconn->Execute($query);
				
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo RegistroEvolucionGestacion- GuardarRegistroEvolucionConductas - SQL 1";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					  
					return false;
				}
			}
			
			if($b==0)
			{
				$sis_id=$this->GetSistemaID();
				
				$query ="INSERT INTO hc_sistemas
								(
									hc_sistema_id,
									normal,
									anormal,
									tipo_sistema_id,
									evolucion_id,
									ingreso
								)
								VALUES
								(
									$sis_id,
									".($datos[3]-1).",
									' ',
									17,
									$evolucion,
									".$ingreso."
								);";
				
				$result = $dbconn->Execute($query);
				
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo RegistroEvolucionGestacion- GuardarRegistroEvolucionConductas - SQL 1";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					  
					return false;
				}
			}
			
			$conducta=$this->GetMaxConductaID();
			
			for($i=sizeof($datos[9]);$i<3;$i++)
					$datos[9][$i]="NULL";
			
			$query="INSERT INTO pyp_cpn_conducta
							(
								pyp_cpn_conducta_id,
								evolucion_id,
								inscripcion_id,
								altura_uterina,
								mamas,
								especuloscopia,
								fcf,
								estado_nutricional,
								presentacion_fetal,
								semana_sugerida,
								semana_actual,
								fecha_registro,
								riesgo_especifico1,
								riesgo_especifico2,
								riesgo_especifico3
							)
							VALUES
							(
								$conducta,
								$evolucion,
								$inscripcion,
								".$datos[5].",
								".$datos[3].",
								".$datos[4].",
								'".$datos[6]."',
								".$datos[8].",
								".$datos[7].",
								$semana,
								$semana_gestante,
								now(),
								".$datos[9][0].",
								".$datos[9][1].",
								".$datos[9][2]."
							);";
			
			$result = $dbconn->Execute($query);
			
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo RegistroEvolucionGestacion - GuardarRegistroEvolucionConductas - SQL 2";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			
			return true;
		}
		
		function GetMaxConductaID()
		{
			list($dbconn) = GetDBconn();
			
			$query="SELECT max(pyp_cpn_conducta_id)
							FROM pyp_cpn_conducta";
							
			$result = $dbconn->Execute($query);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo hc_RegistroEvolucionGestacion - GetMaxConductaID";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			
			while($res=$result->FetchRow())
			{
				$conducta=$res[0]+1;
			}
			
			return $conducta;
		}
		
		function GetMaxsitioID()
		{
			list($dbconn) = GetDBconn();
			
			$query="SELECT nextval('hc_signos_vitales_consultas_signos_vitales_consulta_id_seq'::regclass)";
							
			$result = $dbconn->Execute($query);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo hc_RegistroEvolucionGestacion - GetMaxConductaID";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			
			$sitio_id=$result->fields[0];
			
			return $sitio_id;
		}
		
		function GetSistemaID()
		{
			list($dbconn) = GetDBconn();
			
			$query="SELECT nextval(('hc_sistema_id_seq'::text)::regclass)";
							
			$result = $dbconn->Execute($query);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo hc_RegistroEvolucionGestacion - GetMaxConductaID";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			
			$sis_id=$result->fields[0];
			
			return $sis_id;
		}
		
		function GetMaxRegistroID()
		{
			
			list($dbconn) = GetDBconn();

			$query="SELECT max(registro_datos_evolucion_id)
							FROM pyp_cpn_datos_evolucion_gestacion_valores";
		
			$ADODB_FETCH_MODE = ADODB_FETCH_ROW;
			$result = $dbconn->Execute($query);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo RegistroEvolucion - GetMaxRegistroID";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			
			while($res=$result->FetchRow())
			{
				$registro=$res[0]+1;
			}

			return $registro;
		}

		function ConsultaRegistroEvolucion($inscripcion,$evolucion)
		{
			list($dbconn) = GetDBconn();
			 
			$query="
							SELECT 	a.*,
											d.nombre,
											e.descripcion,
											TO_CHAR(b.fecha_ideal_proxima_cita,'YYYY-MM-DD') as fecha_ideal_proxima_cita,
											f.peso,
											f.taalta,
											f.tabaja,
											CASE g.normal
											WHEN 0 THEN 'NORMAL'
											WHEN 1 THEN 'ANORMAL'
											END as mamas_n
							FROM pyp_cpn_conducta as a
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
							LEFT JOIN hc_sistemas as g
							ON
							(
								a.evolucion_id=g.evolucion_id
							)
							WHERE a.evolucion_id<=$evolucion
							AND a.inscripcion_id=$inscripcion
			";
			
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo RegistroEvolucionGestacion - VerificarSolicitues - SQL";
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
			 
			$query="
							SELECT a.cargo_cups,b.descripcion,a.alias,sw_post
							FROM pyp_cargos a
							JOIN cups as b on(a.cargo_cups=b.cargo)
							JOIN apoyod_cargos as c on(c.cargo=b.cargo)
							WHERE a.programa_id=$programa
							ORDER BY a.indice_orden; 
						";
			
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo RegistroEvolucionGestacion- VerificarSolicitues - SQL";
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

		function VerificarSolicitues($evolucion,$inscripcion,$programa)
		{
			list($dbconn) = GetDBconn();
			 

			$query="
				SELECT DISTINCT a.hc_os_solicitud_id,
								a.evolucion_id,
								a.inscripcion_id,
								c.cargo_cups as cargo,
								e.descripcion,
								c.periodo_sugerido,
								c.periodo_solicitud
				FROM 		pyp_solicitudes_inscripciones AS a
				JOIN 		pyp_evoluciones_procesos AS b 
								ON
								(
									a.evolucion_id=b.evolucion_id 
									AND 
									a.inscripcion_id=b.inscripcion_id
								)
				LEFT JOIN pyp_procedimientos_solicitados AS c 
								ON
								(
									a.hc_os_solicitud_id=c.hc_os_solicitud_id
									AND 
									b.evolucion_id=c.evolucion_id 
									AND 
									b.inscripcion_id=c.inscripcion_id
								)
				LEFT JOIN hc_os_solicitudes AS d
								ON
								(
									a.hc_os_solicitud_id=d.hc_os_solicitud_id
									AND
									c.cargo_cups=d.cargo
								)
				LEFT JOIN cups AS e
								ON
								(
									d.cargo=e.cargo
								)
				WHERE a.evolucion_id<=$evolucion
				AND a.inscripcion_id=$inscripcion
				AND c.programa_id=$programa
			";
						
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo RegistroEvolucionGestacion- VerificarSolicitues - SQL";
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
		
		
		function GetSemanasCronograma($programa=1)
		{
			list($dbconn) = GetDBconn();
			
			$query="SELECT periodo_id,rango_inicio,rango_fin,rango_media
							FROM pyp_periodos_programa
							WHERE programa_id=$programa";
			
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo RegistroEvolucionGestacion- GetSemanasCronograma - SQL";
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
		
		function CalcularProximaCita($semana_gestante,$semana_proxima_sugerida,$indice_semana)
		{
			$dif=intval($semana_proxima_sugerida[($indice_semana+1)][rango_media]) - intval($semana_gestante);
			$dif=$dif*7;
			$tiempo=time()+(($dif-1)*24*60*60);
			$fecha=date("Y-m-d",$tiempo);
			return $fecha;
		}
		
		function GetEstadoProcesosCPN($evolucion,$inscripcion)
		{
			list($dbconn) = GetDBconn();
			
			$query="SELECT sw_estado
							FROM pyp_evoluciones_procesos
							WHERE inscripcion_id=$inscripcion
							AND evolucion_id=$evolucion";
			
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo RegistroEvolucionGestacion - GetEstadoProcesosCPN - SQL";
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
			
			list($dbconn) = GetDBconn();
			 
			
			$query="UPDATE pyp_evoluciones_procesos SET sw_estado='$estado'
							WHERE evolucion_id=$evolucion AND inscripcion_id=$inscripcion";
			
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo RegistroEvolucionGestacion- ActualizarEstadoProcesos - SQL";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				  
				return false;
			}
			
			  
			return true;
		}
		
		function ActualizarEstadoInscripcion($inscripcion,$estado)
		{
			
			list($dbconn) = GetDBconn();
			 
			
			$query="UPDATE pyp_inscripciones_pacientes SET estado='$estado'
							WHERE inscripcion_id=$inscripcion";
			
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo RegistroEvolucionGestacion- ActualizarEstadoProcesos - SQL";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				  
				return false;
			}

			return true;
		}
		
		function GetExamenFisico($evolucion)
		{
			
			list($dbconn) = GetDBconn();
			 
			
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
				$this->error = "Error al Cargar el Modulo RegistroEvolucionGestacion - GetExamenFisico - SQL";
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
		
		function GetEspecialidades()
		{
			
			list($dbconn) = GetDBconn();
			 
			
			$query="SELECT especialidad_id
							FROM pyp_cpn_especialidades
							ORDER BY indice_orden";
			
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo RegistroEvolucionGestacion - GetEspecialidades - SQL";
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