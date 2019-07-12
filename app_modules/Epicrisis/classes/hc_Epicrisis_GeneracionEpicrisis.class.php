<?php
/**
* Submodulo de Epicrisis
*
* @author Luis Alejandro Vargas
* @version 1.0
* @package SIIS
* $Id: hc_Epicrisis_GeneracionEpicrisis.class.php,v 1.9 2007/02/23 14:21:25 luis Exp $
*/

class GeneracionEpicrisis
{

	function GeneracionEpicrisis()
	{
		return true;
	}
	
	function GetDatosMotivosConsulta($ingreso)
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$query=	"	SELECT 	a.descripcion,
											a.enfermedadactual,
											b.motivo_consulta as descripcion1,
											b.enfermedad_actual as enfermedadactual1
							FROM hc_motivo_consulta as a 
							LEFT JOIN hc_epicrisis_motivo_enfermedad as b
							ON
							(
								a.ingreso=b.ingreso
							)
							WHERE a.ingreso=$ingreso;
						";
							
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo Epicrisis - GetDatosMotivosConsulta - SQL 1";
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

		if($vars)
		{
			
			$l=0;
			foreach($vars as $valor)
			{
				$c=",  ";
				if(sizeof($vars)==$l+1)
					$c="";
				
				$desc_mov=$valor['descripcion'];
				if(!strcmp($valor['descripcion'],$valor['descripcion1']))
					$desc_mov=$valor['descripcion1'];
					
				$enf=$valor['enfermedadactual'];
				if(!strcmp($valor['enfermedadactual'],$valor['enfermedadactual1']))
					$enf=$valor['enfermedadactual1'];
				
				if(!empty($desc_mov))
					$descripcion.=strtoupper($desc_mov)."$c";
				if(!empty($enf))
					$enfermedad.=strtoupper($enf)."$c";
			
				$l++;
			}
			
			$query="DELETE FROM hc_epicrisis_motivo_enfermedad
							WHERE ingreso=$ingreso";
			
			$result = $dbconn->Execute($query);
			
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo Epicrisis - GetDatosMotivosConsulta - SQL 2";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
			
			$descripcion=str_replace("'","\'",$descripcion);
			$enfermedad=str_replace("'","\'",$enfermedad);
			
			$query="
							INSERT INTO hc_epicrisis_motivo_enfermedad
							(
								ingreso,
								motivo_consulta,
								enfermedad_actual,
								fecha_registro
							)
							VALUES
							(
								$ingreso,
								'$descripcion',
								'$enfermedad',
								now()
							);
							";
								
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo Epicrisis - GetDatosMotivosConsulta - SQL 3";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
		}
		
		$dbconn->CommitTrans();
		return $vars;
	}
	
	function GetDatosEnfermedad($ingreso)
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$query="SELECT enfermedad_actual as enfermedadactual
						FROM hc_epicrisis_motivo_enfermedad
						WHERE ingreso=$ingreso;
						";
		
		$result = $dbconn->Execute($query);
	
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo Epicrisis - GetDatosEnfermedad - SQL 1";
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
	
	
	function GetDatosExamenFisico($ingreso,$sw=null)
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$query="SELECT 	a.ingreso,
										a.tipo_sistema_id,
										a.sw_normal,
										b.nombre
						FROM hc_epicrisis_examen_fisico as a
						JOIN hc_tipos_sistemas as b
						ON
						(
							a.tipo_sistema_id=b.tipo_sistema_id
						)
						WHERE a.ingreso=$ingreso;";
		
		$result = $dbconn->Execute($query);
	
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo Epicrisis - GetDatosExamenFisico - SQL 1";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		else
		{
			$con=$result->fields[0];
			if($result->RecordCount() > 0)
			{
				while(!$result->EOF)
				{
					$vars[$result->fields[3]][]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		
		if(!$sw && !$con)
			$vars=null;
		elseif($sw)
		{
			$query="SELECT 	a.ingreso,
											a.tipo_sistema_id,
											a.sw_normal,
											b.nombre
							FROM hc_revision_por_sistemas as a
							JOIN hc_tipos_sistemas as b
							ON
							(
								a.tipo_sistema_id=b.tipo_sistema_id
							)
							WHERE a.ingreso=$ingreso;";
		
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo Epicrisis - GetDatosExamenFisico - SQL 2";
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
						$vars[$result->fields[3]][]=$result->GetRowAssoc($toUpper=false);
						$result->MoveNext();
					}
				}
			}
			
			if($vars)
			{
				$query="INSERT INTO hc_epicrisis_examen_fisico
								(
									ingreso,
									tipo_sistema_id,
									sw_normal
								)
								(
									SELECT 	a.ingreso,
													a.tipo_sistema_id,
													a.sw_normal
									FROM hc_revision_por_sistemas as a
									JOIN hc_tipos_sistemas as b
									ON
									(
										a.tipo_sistema_id=b.tipo_sistema_id
									)
									WHERE a.ingreso=$ingreso
									
								);";
			
				$result = $dbconn->Execute($query);
			
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el SubModulo Epicrisis - GetDatosExamenFisico - SQL 3";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
			}
			
		}
		$dbconn->CommitTrans();
		
		return $vars;
	}
	
	function GetDatosExamenFisicoHallazgos($ingreso,$sw=null)
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$query="SELECT 	ingreso,
										hallazgo,
										fecha_registro
						FROM hc_epicrisis_examen_fisico_hallazgo
						WHERE ingreso=$ingreso;";
		
		$result = $dbconn->Execute($query);
	
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo Epicrisis - GetDatosExamenFisicoHallazgos - SQL 1";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		else
		{
			$con=$result->fields[0];
			if($result->RecordCount() > 0)
			{
				while(!$result->EOF)
				{
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		
		if(!$sw && !$con)
			$vars=null;
		elseif($sw)
		{
			$query="SELECT 	ingreso,
											hallazgo,
											fecha_registro
							FROM hc_revision_por_sistemas_hallazgos
							WHERE ingreso=$ingreso;";
		
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo Epicrisis - GetDatosExamenFisico - SQL 2";
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
			
			if($vars)
			{
				$query="INSERT INTO hc_epicrisis_examen_fisico_hallazgo
								(
									ingreso,
									hallazgo,
									fecha_registro
								)
								(
									SELECT 	ingreso,
													hallazgo,
													fecha_registro
									FROM hc_revision_por_sistemas_hallazgos
									WHERE ingreso=$ingreso
								)
								";
			
				$result = $dbconn->Execute($query);
			
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el SubModulo Epicrisis - GetDatosExamenFisico - SQL 3";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
			}
			
		}
		$dbconn->CommitTrans();
		
		return $vars;
	}
	
	function GetDatosApoyosD($ingreso,$tipoidpaciente,$pacienteid)
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
	
		$query="SELECT DISTINCT	
							a.cargo,
							b.descripcion,
							hea.apoyo_diagnostico as descripcion1
							FROM hc_os_solicitudes as a
							JOIN cups as b
							ON
							(
								a.cargo=b.cargo
							)
							JOIN hc_evoluciones he
							ON
							(
								a.evolucion_id=he.evolucion_id
							)
							JOIN ingresos as c
							ON
							(
								he.ingreso=c.ingreso
							)
							LEFT JOIN hc_epicrisis_apoyod as hea
							ON
							(
								c.ingreso=hea.ingreso
							)
							WHERE c.ingreso=$ingreso";
							
		$result = $dbconn->Execute($query);
	
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo Epicrisis - GetDatosApoyosD - SQL 1";
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

		if($vars)
		{
			$query="DELETE FROM hc_epicrisis_apoyod
							WHERE ingreso=$ingreso";
			
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo Epicrisis - GetDatosApoyosD - SQL 3";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
			
			$l=0;
			$apoyosd="";
			foreach($vars as $valor1)
			{
				$c=",  ";
				if(sizeof($vars)==$l+1)
					$c="";
					
				$apoyosd.=$valor1['descripcion']."$c";	
			
				$l++;
			}
			
			$apoyosd=str_replace("'","\'",$apoyosd);
			
			$query="
							INSERT INTO hc_epicrisis_apoyod
							(
								ingreso,
								apoyo_diagnostico,
								fecha_registro
							)
							VALUES
							(
								$ingreso,
								'$apoyosd',
								now()
							)";
			
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo Epicrisis - GetDatosApoyosD - SQL 3";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
		}
		
		$dbconn->CommitTrans();
		
		return $vars;

	}
	
	
	function InsertDatosApoyosD($datos,$ingreso)
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$query="DELETE FROM hc_epicrisis_apoyod
						WHERE ingreso=$ingreso;";
		
		$result = $dbconn->Execute($query);
	
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo Epicrisis - InsertDatosApoyosD - SQL 1";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		
		$datos=str_replace("'","\'",$datos);
		
		$query="INSERT INTO hc_epicrisis_apoyod
						(
							ingreso,
							apoyo_diagnostico,
							fecha_registro
						)
						VALUES
						(
							$ingreso,
							'$datos',
							now()
						);";
		
		$result = $dbconn->Execute($query);
	
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo Epicrisis - InsertDatosApoyosD - SQL 2";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		
		$dbconn->CommitTrans();
		
		return true;
	}
	
	function InsertDatosExamenFisico($datos,$ingreso)
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		foreach($datos as $examen)
		{
			$query="INSERT INTO hc_epicrisis_examen_fisico
							(
								ingreso,
								tipo_sistema_id,
								sw_normal
							)
							VALUES
							(
								$ingreso,
								".$examen['tipo_sistema_id'].",
								'".$examen['sw_normal']."'
							);";
			
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo Epicrisis - InsertDatosExamenFisico - SQL";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
		}

		$dbconn->CommitTrans();
		
		return true;
	}
	
	function InsertDatosExamenFisicoHallazgos($datos,$ingreso)
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$query="DELETE FROM hc_epicrisis_examen_fisico_hallazgo
						WHERE ingreso=$ingreso;";
		
		$result = $dbconn->Execute($query);
	
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo Epicrisis - InsertDatosExamenFisicoHallazgo - SQL 1";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		
		$datos=str_replace("'","\'",$datos);
		
		$query="INSERT INTO hc_epicrisis_examen_fisico_hallazgo
						(
							ingreso,
							hallazgo,
							fecha_registro
						)
						VALUES
						(
							$ingreso,
							'$datos',
							now()
						);";
		
		$result = $dbconn->Execute($query);
	
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo Epicrisis - InsertDatosExamenFisicoHallazgo - SQL 2";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		
		
		$dbconn->CommitTrans();
		
		return true;
	}
	
	function GetDatosAntecedentesPersonales($ingreso,$evolucion,$pacienteid,$tipoidpaciente,$sw=null)
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		if($sw)
		{
			$sql = "SELECT	HT.nombre_tipo,
											HD.descripcion,
											HA.descripcion as detalle,
											HT.hc_tipo_antecedente_personal_id AS hctap,
											HT.hc_tipo_antecedente_detalle_personal_id AS hctad,
											HA.hc_epicrisis_antecedente_personal_id AS hac,
											HA.fecha_registro,
											HA.sw_riesgo
							FROM		hc_epicrisis_antecedentes_personales HA 
							JOIN hc_tipos_antecedentes_detalle_personales HT 
							ON
							(
								HA.hc_tipo_antecedente_detalle_personal_id = HT.hc_tipo_antecedente_detalle_personal_id 
								AND 
								HA.hc_tipo_antecedente_personal_id = HT.hc_tipo_antecedente_personal_id
							)
							JOIN hc_tipos_antecedentes_personales HD 
							ON
							(
								HA.hc_tipo_antecedente_personal_id = HD.hc_tipo_antecedente_personal_id
							) 
			WHERE 	HA.ingreso = $ingreso
			ORDER BY HT.hc_tipo_antecedente_detalle_personal_id, HA.fecha_registro ASC;";
			
			$result = $dbconn->Execute($sql);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo Epicrisis - GetDatosAntecedentesPersonales - SQL 1";
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
						$vars[$result->fields[1]][$result->fields[0]][]=$result->GetRowAssoc($toUpper=false);
						$result->MoveNext();
					}
				}
			}
		}
		else
		{
		
			$sql .= "SELECT	HT.nombre_tipo,";
			$sql .= "				HD.descripcion,";
			$sql .= "				HT.riesgo,";
			$sql .= "				HA.detalle,";
			$sql .= "				HA.destacar,";
			$sql .= "				HE.evolucion_id,";
			$sql .= "				HT.hc_tipo_antecedente_personal_id AS hctap,";
			$sql .= "				HT.hc_tipo_antecedente_detalle_personal_id AS hctad,";
			$sql .= "				HA.ocultar,";
			$sql .= "				HA.hc_antecedente_personal_id AS hac,";
			$sql .= "				HA.sw_riesgo,";
			$sql .= "				HA.fecha_registro ";
			$sql .= "FROM		hc_evoluciones HE,";
			$sql .= "				ingresos IG, ";
			$sql .= "				hc_antecedentes_personales HA RIGHT JOIN hc_tipos_antecedentes_detalle_personales HT ";
			$sql .= "	 			ON(	HA.hc_tipo_antecedente_detalle_personal_id = HT.hc_tipo_antecedente_detalle_personal_id AND ";
			$sql .= "	 					HA.hc_tipo_antecedente_personal_id = HT.hc_tipo_antecedente_personal_id) ";
			$sql .= "	 			RIGHT JOIN hc_tipos_antecedentes_personales HD ";
			$sql .= "	 			ON(HT.hc_tipo_antecedente_personal_id = HD.hc_tipo_antecedente_personal_id) ";
			$sql .= "WHERE 	HE.evolucion_id<=".$evolucion." ";
			$sql .= "AND		HE.ingreso = IG.ingreso ";
			$sql .= "AND		IG.paciente_id='".$pacienteid."' ";
			$sql .= "AND		IG.tipo_id_paciente='".$tipoidpaciente."' ";
			$sql .= "AND		HE.evolucion_id = HA.evolucion_id ";
			$sql .= "ORDER BY HT.hc_tipo_antecedente_detalle_personal_id, HA.fecha_registro ASC;";
			
			$result = $dbconn->Execute($sql);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo Epicrisis - GetDatosAntecedentesPersonales - SQL 2";
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
						$vars[$result->fields[1]][$result->fields[0]][]=$result->GetRowAssoc($toUpper=false);
						$result->MoveNext();
					}
				}
			}
			
			if($vars)
			{
				$query="INSERT INTO  hc_epicrisis_antecedentes_personales
								(
									ingreso, 
									hc_tipo_antecedente_personal_id,
									hc_tipo_antecedente_detalle_personal_id,
									descripcion,
									fecha_registro,
									sw_riesgo
								)
								(
									SELECT
													$ingreso,
													HT.hc_tipo_antecedente_personal_id AS hctap,
													HT.hc_tipo_antecedente_detalle_personal_id AS hctad,
													HA.detalle,
													HA.fecha_registro,
													sw_riesgo
									FROM		hc_evoluciones HE,
									ingresos IG,
									hc_antecedentes_personales HA 
									RIGHT JOIN hc_tipos_antecedentes_detalle_personales HT 
									ON(	HA.hc_tipo_antecedente_detalle_personal_id = HT.hc_tipo_antecedente_detalle_personal_id AND 
									HA.hc_tipo_antecedente_personal_id = HT.hc_tipo_antecedente_personal_id) 
									RIGHT JOIN hc_tipos_antecedentes_personales HD 
									ON(HT.hc_tipo_antecedente_personal_id = HD.hc_tipo_antecedente_personal_id) 
									WHERE 	HE.evolucion_id<=".$evolucion." 
									AND		HE.ingreso = IG.ingreso
									AND		IG.paciente_id='".$pacienteid."' 
									AND		IG.tipo_id_paciente='".$tipoidpaciente."' 
									AND		HE.evolucion_id = HA.evolucion_id 
									ORDER BY HT.hc_tipo_antecedente_detalle_personal_id, HA.fecha_registro ASC
								)
								";
				$result = $dbconn->Execute($query);
		
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el SubModulo Epicrisis - GetDatosAntecedentesPersonales - SQL 3";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
			}
		}

		$dbconn->CommitTrans();
		return $vars;
	}

	function GetDiagnosticos($ingreso,$nombre,$q1=null,$q2=null)
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		if(!$q2)
			$etdo="AND estado='1'";
		
		if($q1)
		{
			$query=	"	SELECT hed.ingreso,
												hed.diagnostico_id,
												hed.tipo_diagnostico_id,
												hed.sw_principal,
												d.diagnostico_nombre,
												CASE hed.tipo_diagnostico_id
												WHEN '1' THEN 'IMPRESION DIAGNOSTICA'
												WHEN '2' THEN 'CONFIRMADO NUEVO'
												WHEN '3' THEN 'CONFIRMADO REPETIDO'
												END as tipo_diag,
												hed.estado
									FROM hc_epicrisis_diagnosticos_$nombre hed
									JOIN diagnosticos as d
									ON
									(
										hed.diagnostico_id=d.diagnostico_id
									)
									WHERE hed.ingreso=$ingreso
									$etdo
									ORDER BY hed.sw_principal DESC
								";
		}
		else
		{
			$query="
							SELECT he.ingreso,
										di.tipo_diagnostico_id as diagnostico_id,
										di.tipo_diagnostico as tipo_diagnostico_id,
										di.sw_principal,
										d.diagnostico_nombre,
										CASE di.tipo_diagnostico
										WHEN '1' THEN 'IMPRESION DIAGNOSTICA'
										WHEN '2' THEN 'CONFIRMADO NUEVO'
										WHEN '3' THEN 'CONFIRMADO REPETIDO'
										END as tipo_diag,
										'1' as estado
							FROM hc_diagnosticos_$nombre as di
							JOIN diagnosticos as d
							ON
							(
								di.tipo_diagnostico_id=d.diagnostico_id
							)
							JOIN hc_evoluciones as he
							ON
							(
								di.evolucion_id=he.evolucion_id
								AND he.ingreso=$ingreso
							)
							ORDER BY di.sw_principal DESC
					";
		}
			
		$result = $dbconn->Execute($query);
	
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo Epicrisis - GetDiagnosticos_$nombre - SQL 1";
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
		
		if(!$q1)
		{
			$query="DELETE FROM hc_epicrisis_diagnosticos_$nombre
							WHERE ingreso=$ingreso;";
			
			$result = $dbconn->Execute($query);
							
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo Epicrisis - GetDiagnosticos_$nombre - SQL 3";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}

			foreach($vars as $diag)
			{

				$query="INSERT INTO  hc_epicrisis_diagnosticos_$nombre
								(
									ingreso,
									diagnostico_id,
									tipo_diagnostico_id,
									sw_principal,
									estado,
									fecha_registro
								)
								VALUES
								(
									$ingreso,
									'".$diag['diagnostico_id']."',
									'".$diag['tipo_diagnostico_id']."',
									'".$diag['sw_principal']."',
									".$diag['estado'].",
									now()
								);";
				
				$result = $dbconn->Execute($query);
								
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el SubModulo Epicrisis - GetDiagnosticos_$nombre - SQL 3";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
			}
		}

		$dbconn->CommitTrans();
		return $vars;
	}
	
	function UpdateDiagnoticoPrimario($ingreso,$nombre,$diag_id,$diag_id_primario,$estado)
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$query="UPDATE hc_epicrisis_diagnosticos_$nombre
						SET sw_principal='0'
						WHERE ingreso=$ingreso
						AND diagnostico_id='$diag_id_primario'";
		
		$result = $dbconn->Execute($query);
	
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo Epicrisis - UpdateDiagnoticoPrimario - SQL 1";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		
		$query="UPDATE hc_epicrisis_diagnosticos_$nombre
						SET sw_principal='1',estado='1'
						WHERE ingreso=$ingreso
						AND diagnostico_id='$diag_id'";
		
		$result = $dbconn->Execute($query);
	
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo Epicrisis - UpdateDiagnoticoPrimario - SQL 2";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		
		$dbconn->CommitTrans();
		return true;
	}
	
	function UpdateIncluirDiagnostico($ingreso,$nombre,$diag_id,$estado)
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$est=0;
		if(!$estado)
			$est=1;
			
		$query="UPDATE hc_epicrisis_diagnosticos_$nombre
						SET estado='$est'
						WHERE ingreso=$ingreso
						AND diagnostico_id='$diag_id'";
		
		$result = $dbconn->Execute($query);
	
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo Epicrisis - UpdateIncluirDiagnostico - SQL";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		
		$dbconn->CommitTrans();
		return true;
	}
	
	function GetDatosEvolucion($ingreso)
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$query="SELECT 	ingreso,
										descripcion_evolucion,
										fecha_registro
						FROM hc_epicrisis_evolucion
						WHERE ingreso=$ingreso
						";

		$result = $dbconn->Execute($query);
	
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo Epicrisis - GetDatosEvolucion - SQL";
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
	
	function InsertDatosEvolucion($datosEvolucion,$ingreso)
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();

		$evo=$this->GetDatosEvolucion($ingreso);
		
		$datosEvolucion=str_replace("'","\'",$datosEvolucion);
		
		if(!$evo)
		{
			$query="INSERT INTO hc_epicrisis_evolucion
							(
								ingreso,
								descripcion_evolucion,
								fecha_registro
							)
							VALUES
							(
								$ingreso,
								'$datosEvolucion',
								now()
							)
							";
		}
		else
		{
			$query="UPDATE hc_epicrisis_evolucion
							SET descripcion_evolucion='$datosEvolucion',
									fecha_registro=now()
							WHERE ingreso=$ingreso;
							";
		}
		
		$result = $dbconn->Execute($query);
	
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo Epicrisis - InsertDatosEvolucion - SQL";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		
		$dbconn->CommitTrans();
		return true;
	}
	
	function GetMedicamentosPacientes($ingreso,$sw=null)
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();

		$query="(
								SELECT a.codigo_producto,
												b.descripcion_abreviada,
												b.descripcion,
												c.descripcion_medicamento as descripcion1
								FROM  hc_formulacion_medicamentos as a
								JOIN inventarios_productos as b
								ON
								(
									a.codigo_producto=b.codigo_producto
								)
								LEFT JOIN hc_epicrisis_medicamentos as c
								ON
								(
									a.ingreso=c.ingreso
								)
								WHERE a.ingreso=$ingreso
							)
							UNION DISTINCT
							(
								SELECT 	b.codigo_producto,
										 		c.descripcion_abreviada,
										 		c.descripcion,
												d.descripcion_medicamento as descripcion1
								FROM  hc_formulacion_mezclas as a
								JOIN hc_formulacion_mezclas_detalle as b
								ON
								(
									a.num_mezcla=b.num_mezcla
								)
								JOIN inventarios_productos as c
								ON
								(
									b.codigo_producto=c.codigo_producto
								)
								LEFT JOIN hc_epicrisis_medicamentos as d
								ON
								(
									a.ingreso=d.ingreso
								)
								WHERE a.ingreso=$ingreso
							);
							";
							
		$result = $dbconn->Execute($query);
	
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo Epicrisis - GetMedicamentosPacientes - SQL 2";
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
			
		if($vars)
		{
			$query="DELETE FROM hc_epicrisis_medicamentos
							WHERE ingreso=$ingreso";
			
			$result = $dbconn->Execute($query);
	
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo Epicrisis - GetMedicamentosPacientes - SQL 2";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
			
			$l=0;
			foreach($vars as $valor)
			{
				$c=",  ";
				if(sizeof($vars)==$l+1)
					$c="";
			
				$medicamentos.=$valor['descripcion']."$c";
				
				$l++;
			}
			
			$medicamentos=str_replace("'","\'",$medicamentos);
			
			$query="INSERT INTO hc_epicrisis_medicamentos
							(
								ingreso,
								descripcion_medicamento,
								fecha_registro
							)
							VALUES
							(
								$ingreso,
								'$medicamentos',
								now()
							)
							";
			$result = $dbconn->Execute($query);
	
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo Epicrisis - GetMedicamentosPacientes - SQL 3";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
		}
		
		$dbconn->CommitTrans();
		
		return $vars;
	}
	
	
	function InsertMedicamentosPacientes($datos,$ingreso)
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$datos=str_replace("'","\'",$datos);
		
		$query="UPDATE hc_epicrisis_medicamentos
						SET descripcion_medicamento='$datos',
								fecha_registro=now()
						WHERE ingreso=$ingreso;	
						";
						
		$result = $dbconn->Execute($query);
	
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo Epicrisis - InsertMedicamentosPacientes - SQL";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		
		$dbconn->CommitTrans();
		return true;
	}
	
	
	function GetDatosPlanSeguimiento($ingreso)
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$query="SELECT 	ingreso,
										plan_seguimiento,
										fecha_registro
						FROM hc_epicrisis_plan_seguimiento
						WHERE ingreso=$ingreso
						";

		$result = $dbconn->Execute($query);
	
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo Epicrisis - GetDatosPlanSegumiento - SQL";
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
	
	function InsertDatosPlanSeguimiento($datosPlan,$ingreso)
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();

		$evo=$this->GetDatosPlanSeguimiento($ingreso);
		
		$datosPlan=str_replace("'","\'",$datosPlan);
		
		if(!$evo)
		{
			$query="INSERT INTO hc_epicrisis_plan_seguimiento
							(
								ingreso,
								plan_seguimiento,
								fecha_registro
							)
							VALUES
							(
								$ingreso,
								'$datosPlan',
								now()
							)
							";
		}
		else
		{
			$query="UPDATE hc_epicrisis_plan_seguimiento
							SET plan_seguimiento='$datosPlan',
									fecha_registro=now()
							WHERE ingreso=$ingreso;
							";
		}

		$result = $dbconn->Execute($query);
	
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo Epicrisis - InsertDatosPlanSegumiento - SQL";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		
		$dbconn->CommitTrans();
		return true;
	}
	
	function GetTiposCausaSalida()
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();

		$query="SELECT *
						FROM hc_epicrisis_tipos_causa_salida
						";

		$result = $dbconn->Execute($query);
	
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo Epicrisis - GetTiposCausaSalida - SQL";
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
	
	function GetDatosCausaSalida($ingreso)
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();

		$query="SELECT 	a.ingreso,
										a.hc_epicrisis_tipo_causa_salida_id as tipo_causa_id,
										b.descripcion as causa,
										a.descripcion_remision
						FROM hc_epicrisis_datos_salida AS a
						JOIN hc_epicrisis_tipos_causa_salida as b
						ON
						(
							a.hc_epicrisis_tipo_causa_salida_id=b.hc_epicrisis_tipo_causa_salida_id
						)
						WHERE a.ingreso=$ingreso";

		$result = $dbconn->Execute($query);
	
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo Epicrisis - GetDatosCausaSalida - SQL";
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
	
	function InsertDatosSalida($datos,$ingreso)
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();

		$query="DELETE FROM hc_epicrisis_datos_salida
						WHERE ingreso=$ingreso;";

		$result = $dbconn->Execute($query);
	
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo Epicrisis - InsertDatosSalida - SQL 1";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		
		$datos[1]=str_replace("'","\'",$datos[1]);
		
		$query="INSERT INTO  hc_epicrisis_datos_salida
						(
							ingreso,
							hc_epicrisis_tipo_causa_salida_id,
							descripcion_remision,
							fecha_registro
						)
						VALUES
						(
							$ingreso,
							".$datos[0].",
							".$datos[1].",
							now()
						)
						";
						
		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo Epicrisis - InsertDatosSalida - SQL 2";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		
		$dbconn->CommitTrans();
		return true;
	}
	
	function GetDatosEpicrisis($ingreso)
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();

		$query="
						SELECT *
						FROM hc_epicrisis
						WHERE ingreso=$ingreso
						";
						
		$result = $dbconn->Execute($query);
	
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo Epicrisis - GetDatosEpicrisis - SQL";
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
	
	function InsertEpicrisis($ingreso)
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();

		$query="INSERT INTO  hc_epicrisis
						(
							ingreso,
							usuario_id,
							fecha_registro
						)
						VALUES
						(
							$ingreso,
							".UserGetUID().",
							now()
						)
						";

		$result = $dbconn->Execute($query);
	
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo Epicrisis - InsertEpicrisis - SQL";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}

		$dbconn->CommitTrans();
		
		
		return true;
	}
	
	function InsertDatosMotivoConsulta($dato,$ingreso,$sw_insert,$sw_dato)
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$dato=str_replace("'","\'",$dato);
		
		if(!$sw_dato)
		{
			$query="UPDATE hc_epicrisis_motivo_enfermedad
							SET motivo_consulta='$dato',
									fecha_registro=now()
							WHERE ingreso=$ingreso";
		}
		else
		{
			$query="UPDATE hc_epicrisis_motivo_enfermedad
							SET enfermedad_actual='$dato',
									fecha_registro=now()
							WHERE ingreso=$ingreso";
		}
		
		$result = $dbconn->Execute($query);
	
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo Epicrisis - InsertDatosMotivoConsulta - SQL";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		
		$dbconn->CommitTrans();
		return true;
	}
	
	function InsertDatosAntecedentesPersonales($datos,$ingreso)
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$query="DELETE FROM hc_epicrisis_antecedentes_personales
						WHERE ingreso=$ingreso";
		
		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo Epicrisis - InsertDatosAntecedentesPersonales - SQL 1";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		
		for($i=0;$i<sizeof($datos);$i++)
		{
			$arreglo=explode("__",$datos[$i]);
			$detalle=$arreglo[0];
			$hctap=$arreglo[1];
			$hctad=$arreglo[2];
			$sw_riesgo=$arreglo[3];
			
			if(!$sw)
			{
				$query="INSERT INTO  hc_epicrisis_antecedentes_personales
								(
									ingreso, 
									hc_tipo_antecedente_personal_id,
									hc_tipo_antecedente_detalle_personal_id,
									descripcion,
									fecha_registro,
									sw_riesgo
								)
								VALUES
								(
									$ingreso,
									$hctap,
									$hctad,
									'$detalle',
									now(),
									'$sw_riesgo'
								)
								";
			}
			else
			{
				$query="UPDATE hc_epicrisis_antecedentes_personales
								SET descripcion='$detalle',
										fecha_registro=now()
								WHERE ingreso=$ingreso
								AND hc_tipo_antecedente_personal_id=$hctap
								AND hc_tipo_antecedente_detalle_personal_id=$hctad;
								";
			}
	
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo Epicrisis - InsertDatosAntecedentesPersonales - SQL 2";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
		}

		$dbconn->CommitTrans();
		return true;
	}
	
	function ErrorDB()
	{
		$this->frmErrorBD=$this->error."<br>".$this->mensajeDeError;
		return $this->frmErrorBD;
	}
}
?>