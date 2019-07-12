<?php
	/********************************************************************************* 
 	* $Id: hc_InscripcionCPN_Inscripcion.class.php,v 1.3 2007/02/01 20:55:52 luis Exp $ 
 	*
 	* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 	* @package IPSOFT-SIIS
 	* 
	* @author    Luis Alejandro vargas. 
	* @package   hc_InscripcionCPN_Inscripcion
	* 
 	**********************************************************************************/
	
	class Inscripcion
	{
		function Inscripcion()
		{
			return true;
		}

		function InscribirCPN($fum,$fup,$fpp,$fcp,$num_previos,$programa,$fecha_proxima_cita)
		{
			$pfj=SessionGetVar("Prefijo");
			
			$evolucion=SessionGetVar("Evolucion");
			$datosPaciente=SessionGetVar("DatosPaciente");
			
			$estado_gestacion=1;
			
			list($dbconn) = GetDBconn();
			
			$query="SELECT max(inscripcion_id) 
							FROM pyp_inscripciones_pacientes";
			
			$result = $dbconn->Execute($query);
			
			$inscripcion=$result->fields[0]+1;
			
			$query="SELECT setval('pyp_inscripciones_pacientes_inscripcion_id_seq',$inscripcion)";
			
			$result = $dbconn->Execute($query);
			
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
				$this->error = "Error al Cargar el SubModulo InscripcionCPN - InscribirCPN - SQL 1";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			$query="INSERT INTO pyp_evoluciones_procesos 
							(
								inscripcion_id,
								evolucion_id,
								fecha_ideal_proxima_cita,
								sw_estado
							)
							VALUES
							(
								$inscripcion,
								".$evolucion.",
								'$fecha_proxima_cita',
								'1'
							)";
			
			$result = $dbconn->Execute($query);
			
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo InscripcionCPN - InscribirCPN - SQL 2";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
	
			$ffp='NULL';
			
			if($fpp != 'NULL')
					$fpp="'$fpp'";
			
			if($fup != 'NULL')
					$fup="'$fup'";
						
		
			$query="INSERT INTO pyp_inscripcion_cpn 
							(
								inscripcion_id,
								fecha_ultimo_parto,
								fecha_ultimo_periodo,
								estado_gestacion,
								fecha_primer_parto,
								numero_embarazos_previos,
								fecha_calulada_parto,
								fecha_final_parto
							) 
							VALUES
							(
								$inscripcion,
								$fup,
								'$fum',
								'$estado_gestacion',
								$fpp,
								$num_previos,
								'$fcp',
								$ffp
							)";

			
			$result = $dbconn->Execute($query);
					
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo InscripcionCPN - InscribirCPN - SQL 3";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
		
			return true;
		}
		
		
		function GetApoyosInicales()
		{
			$evolucion=SessionGetVar("Evolucion");
			$programa=SessionGetVar("Programa");
			
			list($dbconn) = GetDBconn();
			
			$query="SELECT 	b.cargo,
											b.descripcion,
											c.apoyod_tipo_id,
											a.alias
							FROM 		pyp_cargos a
							JOIN 		cups AS b 
							ON
							(
								a.cargo_cups=b.cargo
							)
							JOIN apoyod_cargos AS c 
							ON
							(
								c.cargo=b.cargo
							)
							WHERE a.programa_id=$programa
							AND a.sw_inscripcion=1";
			
			$result = $dbconn->Execute($query);
	
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo InscripcionCPN - GetApoyosIniciales - SQL";
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
		
		function ValidaInscripcionPaciente($tipo_id,$paciente_id,$programa)
		{
			
			$evolucion=SessionGetVar("Evolucion");
		
			list($dbconn) = GetDBconn();
			
			$query="SELECT a.inscripcion_id
							FROM pyp_inscripciones_pacientes as a
							JOIN pyp_evoluciones_procesos as b
							ON
							(
								a.inscripcion_id=b.inscripcion_id
							) 
							WHERE 
							(
								a.tipo_id_paciente='$tipo_id'
								AND a.paciente_id='$paciente_id'
								AND a.programa_id=$programa
								AND a.estado='1'
							)
							OR
							(
								a.estado='0' AND b.evolucion_id=".$evolucion."
								AND a.programa_id=$programa
							)
							";
							
			$result = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo InscripcionCPN - ValidaInscripcionPaciente - SQL";
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
											b.cargo,b.evolucion_id
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
				$this->error = "Error al Cargar el SubModulo InscripcionCPN - ConsultaSolicitudes - SQL";
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
		
	}
?>