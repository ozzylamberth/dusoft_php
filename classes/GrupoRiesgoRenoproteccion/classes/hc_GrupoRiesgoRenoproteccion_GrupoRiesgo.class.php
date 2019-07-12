<?php
	/********************************************************************************* 
 	* $Id: hc_GrupoRiesgoRenoproteccion_GrupoRiesgo.class.php,v 1.2 2009/11/06 18:18:37 hugo Exp $ 
 	*
 	* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 	* @package IPSOFT-SIIS
 	* 
	* @author    Luis Alejandro vargas. 
	* @package   hc_GrupoRiesgoRenoproteccion
	* 
 	**********************************************************************************/

	class GrupoRiesgo
	{
		function GrupoRiesgo()
		{
			return true;
		}

		function GetDatosAdicionalesPaciente()
		{
			$datosPaciente=SessionGetVar("DatosPaciente");
			
			list($dbconn) = GetDBconn();
			
			$query ="SELECT a.ocupacion_id,
											b.ocupacion_descripcion,
											c.tipo_raza_id,
											d.descripcion
							FROM 		pacientes AS a
							LEFT JOIN ocupaciones AS b 
											ON
											(
												a.ocupacion_id=b.ocupacion_id
											)
							LEFT JOIN pacientes_datos_adicionales as c
											ON
											(
												a.tipo_id_paciente=c.tipo_id_paciente
												AND a.paciente_id=c.paciente_id
											)
							LEFT JOIN tipos_razas as d
											ON
											(
												c.tipo_raza_id=d.tipo_raza_id
											)
							WHERE a.tipo_id_paciente='".$datosPaciente['tipo_id_paciente']."'
							AND a.paciente_id='".$datosPaciente['paciente_id']."'";

			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo GrupoRiesgoRenoproteccion - GetDatosAdicionalesPaciente - SQL";
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
		
		function GetTiposRaza()
		{
			list($dbconn) = GetDBconn();
			
			$query="SELECT 	tipo_raza_id,
											descripcion
							FROM		tipos_razas";

			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo GrupoRiesgoRenoproteccion - GetTiposRaza - SQL";
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
		
		function GetDatoOcupacion()
		{
			$programa=SessionGetVar("Programa");
			$inscripcion=SessionGetVar("Inscripcion_$programa");
			
			list($dbconn) = GetDBconn();
			
			$query="SELECT 	ocupacion
							FROM 		pyp_inscripcion_renoproteccion
							WHERE 	inscripcion_id=$inscripcion";

			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo GrupoRiesgoRenoproteccion - GetTiposRaza - SQL";
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
		
		
		function GetRiesgoBiopsicosocial()
		{
			$programa=SessionGetVar("Programa");
			
			list($dbconn) = GetDBconn();
			
			$query="SELECT 	riesgo_id,
											descripcion,
											puntaje,
											grupo_id
							FROM pyp_riesgos_biopsicosocial
							WHERE programa_id=$programa
							ORDER BY riesgo_id";
			
			$result = $dbconn->Execute($query);
		
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo GrupoRiesgoRenoproteccion - GetRiesgoBiopsicosocial - SQL";
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
		
		function GetDatosRiesgoBiopsicosocial()
		{
			$programa=SessionGetVar("Programa");
			$inscripcion=SessionGetVar("Inscripcion_$programa");
			$evolucion=SessionGetVar("Evolucion");
			
			list($dbconn) = GetDBconn();
			
			$query="SELECT 	valor,
											evolucion_id,
											registro_id,
											riesgo_id,
											TO_CHAR(fecha_registro,'YYYY-MM-DD') as fecha
							FROM pyp_renoproteccion_registro_riesgo_evolucion
							WHERE evolucion_id<=$evolucion
							AND inscripcion_id=$inscripcion
							ORDER BY evolucion_id";
			
			$result = $dbconn->Execute($query);
		
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo GrupoRiesgoRenoproteccion - GetDatosRiesgoBiopsicosocial - SQL";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				if($result->RecordCount() > 0)
				{
					while(!$result->EOF)
					{
						$vars[$result->fields[1]][]=$result->GetRowAssoc($toUpper=false);
						$result->MoveNext();
					}
				}
			}
			
			return $vars;
		}
		
		function GetGruposRiesgos()
		{
			$programa=SessionGetVar("Programa");
			
			list($dbconn) = GetDBconn();
			
			$query="
							SELECT 	grupo_id,
											descripcion_valor as descripcion_grupo,
											puntaje as puntaje_grupo	
							FROM 	pyp_riesgos_grupos
							WHERE programa_id=$programa
							";
			
			$result = $dbconn->Execute($query);
		
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo GrupoRiesgoRenoproteccion - GetDatosRiesgoBiopsicosocial - SQL";
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
		
		function GetConteoEvolucion()
		{
			$programa=SessionGetVar("Programa");
			$inscripcion=SessionGetVar("Inscripcion_$programa");
			$evolucion=SessionGetVar("Evolucion");
			
			list($dbconn) = GetDBconn();
			
			$query="SELECT count(A.*)
							FROM (
										SELECT DISTINCT evolucion_id 
										FROM pyp_renoproteccion_registro_riesgo_evolucion
										WHERE evolucion_id<=$evolucion
										AND inscripcion_id=$inscripcion
										) AS A";
			
			$result = $dbconn->Execute($query);
		
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo GrupoRiesgoRenoproteccion - GetConteoEvolucion - SQL";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
				$conteo=$result->fields[0];
			
			return $conteo;
		
		}
		
		function GuardarRiesgosBiopsicosocial($datos)
		{
			$pfj=SessionGetVar("Prefijo");
			$programa=SessionGetVar("Programa");
			$inscripcion=SessionGetVar("Inscripcion_$programa");
			$evolucion=SessionGetVar("Evolucion");
			
			list($dbconn) = GetDBconn();
			
			$query="DELETE FROM pyp_renoproteccion_registro_riesgo_evolucion
							WHERE evolucion_id=$evolucion
							AND 	inscripcion_id=$inscripcion";
			
			$result = $dbconn->Execute($query);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar en el SubModulo GrupoRiesgoRenoproteccion - GuardarRiesgosBiopsicosocial - SQL1";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				for($i=0;$i<sizeof($datos['riesgos'.$pfj]);$i++)
				{
					$query="SELECT nextval('pyp_renoproteccion_registro_riesgo_evolucion_registro_id_seq'::regclass)";
					
					$result = $dbconn->Execute($query);
					
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar en el SubModulo GrupoRiesgoRenoproteccion - GuardarRiesgosBiopsicosocial - SQL2";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
					else
					{
						$valorR=explode("ç",$datos['riesgos'.$pfj][$i]);
						$puntaje=$valorR[0];
						$riesgo_id=$valorR[1];
						
						$registro_id=$result->fields[0];
					
						$query="INSERT INTO pyp_renoproteccion_registro_riesgo_evolucion
																(
																	registro_id,
																	valor,
																	riesgo_id,
																	evolucion_id,
																	inscripcion_id,
																	fecha_registro
																)
																VALUES
																(
																	$registro_id,
																	$puntaje,
																	$riesgo_id,
																	$evolucion,
																	$inscripcion,
																	now()
																)";
						
						$result = $dbconn->Execute($query);
					
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar en el SubModulo GrupoRiesgoRenoproteccion - GuardarRiesgosBiopsicosocial - SQL3";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}
					}
				}
			}
			return true;
		}
		
		function GuardarDatosAdicionalesPaciente($datos)
		{
			$pfj=SessionGetVar("Prefijo");
			$datosPaciente=SessionGetVar("DatosPaciente");
			$programa=SessionGetVar("Programa");
			$inscripcion=SessionGetVar("Inscripcion_$programa");
			
			list($dbconn) = GetDBconn();
			
			if(!$datos['raza'.$pfj])
				$datos['raza'.$pfj]="NULL";
					
			$query="UPDATE pacientes_datos_adicionales 
							SET tipo_raza_id=".$datos['raza'.$pfj]."
							WHERE tipo_id_paciente='".$datosPaciente['tipo_id_paciente']."'
							AND paciente_id='".$datosPaciente['paciente_id']."'";
			
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo GrupoRiesgoRenoproteccion - GuardarDatosAdicionalesPaciente - SQL1";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				if(!$datos['ocupacion'.$pfj])
					$datos['ocupacion'.$pfj]="NULL";
					
				$query="UPDATE pyp_inscripcion_renoproteccion 
								SET ocupacion='".$datos['ocupacion'.$pfj]."'
								WHERE inscripcion_id=$inscripcion";
			
				$result = $dbconn->Execute($query);
			
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el SubModulo GrupoRiesgoRenoproteccion - GuardarDatosAdicionalesPaciente - SQL2";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
			}
			return true;
		}
		
		function CalcularPuntajeRiesgoEvolucion()
		{
			$evolucion=SessionGetVar("Evolucion");
			$programa=SessionGetVar("Programa");
			$inscripcion=SessionGetVar("Inscripcion_$programa");
			
			list($dbconn) = GetDBconn();
			$query="
								SELECT sum(A.puntaje) as puntaje,A.evolucion_id
								FROM
								(
									(
										SELECT sum(a.valor) as puntaje,
													a.evolucion_id,
													1
										FROM  pyp_renoproteccion_registro_riesgo_evolucion as a
										JOIN pyp_riesgos_biopsicosocial AS b 
										ON
										(
											a.riesgo_id=b.riesgo_id 
											--AND a.valor=b.puntaje 
											--AND b.grupo_id is null
										)
										WHERE evolucion_id<=$evolucion
										AND 	inscripcion_id=$inscripcion
										GROUP BY evolucion_id
									) 
									UNION
									(
										SELECT CASE count(*)
													WHEN 0 THEN 0
													WHEN 1 THEN 0
													WHEN 2 THEN 1
													WHEN 3 THEN 1
													END as puntaje, 
													a.evolucion_id,
													2
										FROM pyp_renoproteccion_registro_riesgo_evolucion a 
										JOIN pyp_riesgos_biopsicosocial AS b 
										ON
										(
											a.riesgo_id=b.riesgo_id 
											AND a.valor=1 
											AND b.grupo_id is not null
										)
										JOIN pyp_riesgos_grupos as c 
										ON
										(
											b.grupo_id=c.grupo_id 
											AND c.grupo_id=3
											AND c.puntaje=1
										)
										WHERE a.evolucion_id<=$evolucion
										AND a.inscripcion_id=$inscripcion
										GROUP BY a.evolucion_id
									)
									UNION
									(
										SELECT CASE count(*)
													WHEN 0 THEN 0
													WHEN 1 THEN 0
													WHEN 2 THEN 1
													WHEN 3 THEN 1
													END as puntaje, 
													a.evolucion_id,
													3
										FROM pyp_renoproteccion_registro_riesgo_evolucion a 
										JOIN pyp_riesgos_biopsicosocial AS b 
										ON
										(
											a.riesgo_id=b.riesgo_id 
											AND a.valor=2
											AND b.grupo_id is not null
										)
										JOIN pyp_riesgos_grupos as c 
										ON
										(
											b.grupo_id=c.grupo_id 
											AND c.grupo_id=4
											AND c.puntaje=2
										)
										WHERE a.evolucion_id<=$evolucion
										AND a.inscripcion_id=$inscripcion
										GROUP BY a.evolucion_id
									)
									
								)
								AS A
								GROUP BY evolucion_id
								ORDER BY evolucion_id
							";
							
							/*
								UNION
									(
										SELECT c.puntaje as puntaje, 
													a.evolucion_id,
													4
										FROM pyp_renoproteccion_registro_riesgo_evolucion a 
										JOIN pyp_riesgos_biopsicosocial AS b 
										ON
										(
											a.riesgo_id=b.riesgo_id 
											AND b.grupo_id is not null
										)
										JOIN pyp_riesgos_grupos as c 
										ON
										(
											b.grupo_id=c.grupo_id 
											AND a.valor=c.puntaje 
											AND c.grupo_id=5
										)
										WHERE a.evolucion_id<=$evolucion
										AND a.inscripcion_id=$inscripcion
										GROUP BY a.evolucion_id,c.puntaje
									)
							
							*/
							
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo GrupoRiesgoRenoproteccion - CalcularPuntajeRiesgoEvolucion - SQL";
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
		
		function ConsultaRiesgoBiopsicosocial($evolucion,$inscripcion,$programa)
		{
			list($dbconn) = GetDBconn();

			$query="SELECT a.riesgo_id,
													a.descripcion,
													a.grupo_id,
													a.puntaje,
													b.puntaje as puntaje_grupo,
													b.descripcion_valor as descripcion_grupo,
													c.valor,
													c.evolucion_id,
													c.inscripcion_id,
													TO_CHAR(c.fecha_registro,'YYYY-MM-DD') as fecha
							FROM  pyp_riesgos_biopsicosocial as a
							LEFT JOIN pyp_riesgos_grupos as b
							ON
							(
								a.grupo_id=b.grupo_id
								AND a.programa_id=b.programa_id
							)
							LEFT JOIN pyp_renoproteccion_registro_riesgo_evolucion as c
							ON
							(
								a.riesgo_id=c.riesgo_id
								AND 
								(
									a.puntaje=c.valor 
									OR b.puntaje=c.valor
								)
							)
							WHERE c.evolucion_id=$evolucion
							AND 	c.inscripcion_id=$inscripcion
							AND 	a.programa_id=$programa
							ORDER BY a.riesgo_id";
							
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo GrupoRiesgoRenoproteccion - ConsultaRiesgoBiopsicosocial - SQL";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				if($result->RecordCount() > 0)
				{
					while(!$result->EOF)
					{
						$vars[$result->fields[0]]=$result->GetRowAssoc($toUpper=false);
						$result->MoveNext();
					}
				}
			}
			
			return $vars;
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