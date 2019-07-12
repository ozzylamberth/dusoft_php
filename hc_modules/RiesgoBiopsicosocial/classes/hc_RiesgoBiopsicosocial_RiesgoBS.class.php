<?php
	/********************************************************************************* 
 	* $Id: hc_RiesgoBiopsicosocial_RiesgoBS.class.php,v 1.3 2007/02/01 20:51:09 luis Exp $ 
 	*
 	* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 	* @package IPSOFT-SIIS
 	* 
	* @author    Luis Alejandro vargas. 
	* @package   hc_RiesgoBiopsicosocial
	* 
 	**********************************************************************************/
	class RiesgoBS
	{
		function RiesgoBS()
		{
			return true;
		}
		
		function GetDatosRiesgoBiopsicosocial($programa)
		{
			list($dbconn) = GetDBconn();
			
			$query="SELECT riesgo_id,
										 descripcion,
										 puntaje,
										 grupo_id
							FROM 	pyp_riesgos_biopsicosocial
							WHERE programa_id=$programa
							ORDER BY riesgo_id";
			
			$result = $dbconn->Execute($query);
		
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo RiesgoBiopsicosocial - GetDatosRiesgoBiopsicosocial - SQL";
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
		
		function GetDatosGruposRiesgos()
		{
			
			list($dbconn) = GetDBconn();
			
			$evolucion=SessionGetVar("Evolucion");
			$programa=SessionGetVar("Programa");
			
			$query="SELECT	grupo_id,
											puntaje,
											descripcion_valor
							FROM 	pyp_riesgos_grupos
							WHERE programa_id=$programa
							ORDER BY grupo_id";
			
			$result = $dbconn->Execute($query);
		
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo RiesgoBiopsicosocial - GetDatosGruposRiesgos - SQL";
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
		
		function ObtenerPuntaje_Riesgos($inscripcion,$evolucion,$semana)
		{
			list($dbconn) = GetDBconn();
			global $ADODB_FETCH_MODE;

			$query1="SELECT sum(a.valor)
								FROM pyp_cpn_registro_riesgo_evolucion a
								JOIN pyp_riesgos_biopsicosocial AS b 
								ON
								(
									a.riesgo_id=b.riesgo_id 
									AND a.valor=b.puntaje 
									AND b.grupo_id is null
								)
								WHERE a.evolucion_id<=".$evolucion."
								AND a.inscripcion_id=".$inscripcion."
								AND a.semana='$semana'";
			
			$ADODB_FETCH_MODE = ADODB_FETCH_ROW;
			$result = $dbconn->Execute($query1);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo RiesgoBiopsicosocial - ObtenerPuntaje_Riesgos - SQL 1";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			
			while($res=$result->FetchRow())
			{
				$puntaje[0]=$res[0];
			}
			$result->Close();
			$query2="	SELECT count(*) 
								FROM pyp_cpn_registro_riesgo_evolucion a 
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
									AND c.grupo_id=1 
									AND c.puntaje=1
								)
								WHERE a.evolucion_id<=".$evolucion."
								AND a.inscripcion_id=".$inscripcion."
								AND a.semana='$semana'";
			
			$ADODB_FETCH_MODE = ADODB_FETCH_ROW;
			$result = $dbconn->Execute($query2);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo RiesgoBiopsicosocial - ObtenerPuntaje_Riesgos - SQL 2";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			
			while($res=$result->FetchRow())
			{
				$puntaje[1]=$res[0];
			}
			
			$result->Close();
			$query3 ="SELECT count(*) FROM pyp_cpn_registro_riesgo_evolucion a 
								JOIN pyp_riesgos_biopsicosocial AS b
								ON
								(
									a.riesgo_id=b.riesgo_id 
									AND a.valor=2 
									AND b.grupo_id is not null
								) 
								JOIN pyp_riesgos_grupos AS c 
								ON
								(
									b.grupo_id=c.grupo_id 
									AND c.grupo_id=2 
									AND c.puntaje=2
								) 
								WHERE a.evolucion_id<=".$evolucion."
								AND a.inscripcion_id=".$inscripcion."
								AND a.semana='$semana'";
			
			$ADODB_FETCH_MODE = ADODB_FETCH_ROW;
			$result = $dbconn->Execute($query3);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo RiesgoBiopsicosocial - ObtenerPuntaje_Riesgos - SQL 3";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			
			while($res=$result->FetchRow())
			{
				$puntaje[2]=$res[0];
			}
			$result->Close();
			
			return $puntaje;
		}
		
		function GetDatosRegistrosRiegos($evolucion,$inscripcion)
		{
			list($dbconn) = GetDBconn();
			
			$query="SELECT c.registro_id,
										c.valor,
										c.semana,
										c.riesgo_id,
										c.evolucion_id,
										c.inscripcion_id
							FROM pyp_inscripciones_pacientes as a 
							JOIN pyp_evoluciones_procesos as b 
							ON
							(
								a.inscripcion_id=b.inscripcion_id 
							)
							JOIN pyp_cpn_registro_riesgo_evolucion as c 
							ON
							(
								b.inscripcion_id=c.inscripcion_id 
								AND b.evolucion_id=c.evolucion_id
							)
							WHERE c.evolucion_id<=".$evolucion." 
							AND c.inscripcion_id=$inscripcion";
			
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo - RiesgoBiopsicosocial - GetDatosRegistrosRiegos - SQL";
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
		
		function ConteoSemana($inscripcion,$evolucion)
		{
			
			list($dbconn) = GetDBconn();
			
			$query = "SELECT DISTINCT semana
								FROM pyp_cpn_registro_riesgo_evolucion
								WHERE evolucion_id<=".$evolucion."
								AND inscripcion_id=".$inscripcion;
			
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo - RiesgoBiopsicosocial - ConteoSemana - SQL";
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
		
		function guardarRegistros_Biopsicosocial($inscripcion_id,$riesgo_id,$valores,$semana,$cont_semanas,$evolucion,$r_ini,$r_fin)
		{
			
			list($dbconn) = GetDBconn();
			
			for($i=0;$i<=$cont_semanas;$i++)
			{
				if($semana>=$r_ini[$i] and $semana<=$r_fin[$i])
				{
					$query="DELETE 
									FROM pyp_cpn_registro_riesgo_evolucion
									WHERE evolucion_id=$evolucion
									AND 	inscripcion_id=$inscripcion_id
									AND 	semana='$semana'";
	
					$result = $dbconn->Execute($query);
					
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el SubModulo RiesgoBiopsicosocial - guardarRegistros_Biopsicosocial - SQL 1";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
				}
			}	
			
			$registro_id=$this->GetMaxRegistroID();

			for($i=0;$i<sizeof($riesgo_id);$i++)
			{
				$query ="INSERT INTO pyp_cpn_registro_riesgo_evolucion
								(
									registro_id,
									valor,
									semana,
									riesgo_id,
									evolucion_id,
									inscripcion_id
								) 
								VALUES
								(
									$registro_id,
									".$valores[$i].",
									'$semana',
									".$riesgo_id[$i].",
									".$evolucion.",
									".$inscripcion_id."
								)";		
				
				$result = $dbconn->Execute($query);
				
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el SubModulo RiesgoBiopsicosocial - guardarRegistros_Biopsicosocial - SQL 2";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				
				$registro_id++;
			}
			return true;
		}
		
		function ConsultaRiesgoBiopsicosocial($inscripcion,$evolucion,$programa)
		{
			list($dbconn) = GetDBconn();
	
			$query="SELECT 	a.riesgo_id,
											b.evolucion_id,
											b.registro_id,
											b.valor,
											b.semana,
											a.descripcion,
											b.inscripcion_id,
											a.puntaje,
											a.grupo_id
							FROM pyp_riesgos_biopsicosocial as a
							LEFT JOIN pyp_cpn_registro_riesgo_evolucion as b
							ON
							(
								a.riesgo_id=b.riesgo_id
							)
							LEFT JOIN  pyp_evoluciones_procesos as c
							ON
							(
								b.evolucion_id=c.evolucion_id
								AND b.inscripcion_id=c.inscripcion_id
							)
							LEFT JOIN  pyp_inscripciones_pacientes as d
							ON
							(
								c.inscripcion_id=d.inscripcion_id
							)
							WHERE b.evolucion_id<=$evolucion
							AND b.inscripcion_id=$inscripcion
							AND a.programa_id=$programa
							ORDER BY a.riesgo_id";
			
			$result = $dbconn->Execute($query);
		
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo RiesgoBiopsicosocial - ConsultaRiesgoBiopsicosocial - SQL";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				if($result->RecordCount() > 0)
				{
					while(!$result->EOF)
					{
						$vars[$result->fields[5]][$result->fields[4]]=$result->GetRowAssoc($toUpper=false);
						$result->MoveNext();
					}
				}
			}
			
			return $vars;
		}

		function GetInscripcionEvolucion()
		{
			$evolucion=SessionGetVar("Evolucion");
			$programa=SessionGetVar("Programa");
			$inscripcion=SessionGetVar("Inscripcion_$programa");
			$datosPaciente=SessionGetVar("DatosPaciente");
			
			list($dbconn) = GetDBconn();
			
			$query="SELECT max(evolucion_id)
							FROM pyp_evoluciones_procesos AS a
							JOIN pyp_inscripciones_pacientes AS b 
							ON
							(
								a.inscripcion_id=b.inscripcion_id 
								AND b.tipo_id_paciente='".$datosPaciente['tipo_id_paciente']."' 
								AND b.paciente_id='".$datosPaciente['paciente_id']."' 
								AND b.programa_id=$programa
							)";
			
			$result = $dbconn->Execute($query);
			
			$evolucion=$result->fields[0];
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo RiesgoBiopsicosocial - GetInscripcionEvolucion - SQL 1";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			
			$query="SELECT a.inscripcion_id,a.evolucion_id,a.sw_estado
							FROM pyp_evoluciones_procesos AS a
							JOIN pyp_inscripciones_pacientes AS b 
							ON
							(
								a.inscripcion_id=b.inscripcion_id 
							)
							WHERE evolucion_id=$evolucion
							AND b.tipo_id_paciente='".$datosPaciente['tipo_id_paciente']."' 
							AND b.paciente_id='".$datosPaciente['paciente_id']."'
							AND b.programa_id=$programa
							";
			$result = $dbconn->Execute($query);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo RiesgoBiopsicosocial - GetInscripcionEvolucion - SQL 2";
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
		
		function GetMaxRegistroID()
		{
			list($dbconn) = GetDBconn();
			
			$query="SELECT max(registro_id)
							FROM pyp_cpn_registro_riesgo_evolucion";
		
			$result = $dbconn->Execute($query);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo RiesgoBiopsicosocial - GetMaxRegistroID - SQL";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			
			$registro_id=$result->fields[0]+1;
			
			return $registro_id;
		}
		
		function CalculoPuntajeTotal($puntaje_gineco,$puntaje_riesgo)
		{
			$valor1=0;
			$valor2=0;
			
			if($puntaje_riesgo[1]>=2)
			{
				$valor1=1;
			}
			if($puntaje_riesgo[2]>=2)
			{
				$valor2=1;
			}

			return ($puntaje_gineco+$puntaje_riesgo[0]+$valor1+$valor2);
		}
		
		/**
		* Funcion que calcula y  retorna las semanas de gestacion de una paciente, sacando la fecha
		* inicial desde la tabla gestacion, mediante el campo (FUM -->fecha ultima mestruacion), la cual llega
		* a la variable $FechaIni y con la fecha actual, la cual es $FechaFin, se sacan las semanas de la paciente.
		* @return boolean
		* @param date fecha sacada de la tabla gestacion(campo --> fum)
		*/
		
		function CalcularSemanasGestante($FechaIni,$FechaFin='')
		{
			if(empty($FechaFin))
				$FechaFin=date("Y-m-d");
			
			$fech=strtok($FechaIni,"-");
			for($i=0;$i<3;$i++)
			{
					$date[$i]=$fech;
					$fech=strtok("-");
			}
			$fech=strtok($FechaFin,"-");
			for($i=0;$i<3;$i++)
			{
					$date1[$i]=$fech;
				$fech=strtok("-");
			}
			$edad=(ceil($date1[0])-$date[0]);
			$meses=$date1[1]-$date[1];
			$dias=$date1[2]-$date[2];
			$total=($edad*378)+($meses*31.5)+$dias;
			$meses1=(($total%378)/30);
			$meses1=$meses1*4.5;
			return $meses1;
		}
		
		function GetDatofum($inscripcion)
		{
			
			list($dbconn) = GetDBconn();

			$query="SELECT 	b.fecha_ultimo_periodo,
											b.fecha_calulada_parto
							FROM 		pyp_inscripciones_pacientes as a
							LEFT JOIN pyp_inscripcion_cpn AS b 
							ON
							(
								a.inscripcion_id=b.inscripcion_id
							)
							WHERE a.inscripcion_id=$inscripcion";
							
			$result = $dbconn->Execute($query);

			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo RiesgoBiopsicosocial - GetDatofum - SQL";
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