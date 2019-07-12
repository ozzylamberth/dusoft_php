<?php

/**
* Submodulo de InscripcionPYP_HTML.
* $Id: hc_InscripcionPYP_InscripcionesPYP.class.php,v 1.7 2007/02/01 20:50:05 luis Exp $
* @author luis alejandro vargas
* @version 1.0
* @package SIIS
*/

class InscripcionesPYP
{
	function InscripcionesPYP()
	{
		$this->limit=GetLimitBrowser();
		return true;
	}
	
	function getProgramasPacienteInscrito()
	{
		$pfj=SessionGetVar("Prefijo");
		$datosPaciente=SessionGetVar("DatosPaciente");
		
		list($dbconn) = GetDBconn();

		$query="SELECT DISTINCT b.programa_id,b.descripcion
						FROM pyp_inscripciones_pacientes a
						LEFT JOIN pyp_programas as b on(b.programa_id=a.programa_id)
						WHERE a.tipo_id_paciente='".$datosPaciente['tipo_id_paciente']."'
						AND a.paciente_id='".$datosPaciente['paciente_id']."'
						AND a.estado='1'
						AND b.sw_estado='1'
						ORDER BY b.programa_id";

		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo InscripcionPYP - getProgramasPacienteInscrito - SQL";
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
	
	function getProgramasPacienteCandidato()
	{
		$pfj=SessionGetVar("Prefijo");
		$datosPaciente=SessionGetVar("DatosPaciente");
		$plan=SessionGetVar("Plan");
		
		list($dbconn) = GetDBconn();

		$query="SELECT DISTINCT a.programa_id,
												a.descripcion,
												a.hc_modulo,
												c.submodulo,
												c.sexo_id,
												c.edad_min
						FROM pyp_programas AS a
						LEFT JOIN pyp_programas_planes AS b
						ON
						(
							a.programa_id=b.programa_id
						)
						LEFT JOIN system_hc_submodulos AS c
						ON
						(
							a.hc_modulo=c.submodulo
						)
						WHERE c.edad_min<=".$datosPaciente['edad_paciente']['anos']."
						AND c.edad_max>=".$datosPaciente['edad_paciente']['anos']."
						AND 
						(
							c.sexo_id='".$datosPaciente['sexo_id']."'
							OR	c.sexo_id is null
						)
						AND b.plan_id=".$plan." 
						AND a.sw_estado='1'
						AND a.programa_id NOT IN
							(
								SELECT DISTINCT b.programa_id
								FROM pyp_inscripciones_pacientes a
								LEFT JOIN pyp_programas as b on(b.programa_id=a.programa_id)
								WHERE a.tipo_id_paciente='".$datosPaciente['tipo_id_paciente']."'
								AND a.paciente_id='".$datosPaciente['paciente_id']."'
								AND a.estado='1'
								AND b.sw_estado='1'
							)
						ORDER BY a.programa_id";
						/*
						
						*/
		$result = $dbconn->Execute($query);
			
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo InscripcionPYP - getProgramasPacienteCandidato - SQL";
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
	
	function InsertarEvoProcesos($estado,$evolucion,$inscripcion,$fecha='NULL')
	{
		$pfj=SessionGetVar("Prefijo");
		$evolucion=SessionGetVar("Evolucion");
		$programa=SessionGetVar("Programa");
		$inscripcion=SessionGetVar("Inscripcion_$programa");
		$datosPaciente=SessionGetVar("DatosPaciente");
		$plan=SessionGetVar("Plan");
		
		list($dbconn) = GetDBconn();
		
		if($fecha!='NULL')
			$fecha="'$fecha'";
			
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
							$evolucion,
							$fecha,
							'$estado'
						);";
		
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo InscripcionPYP - InsertarEvoProcesos - SQL";
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
	
	function UpdateEvoProcesos($estado,$evolucion,$inscripcion,$fecha='NULL')
	{
		
		if($fecha!='NULL')
			$fecha="'$fecha'";
		
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$query="UPDATE pyp_evoluciones_procesos 
						SET sw_estado='$estado',
						fecha_ideal_proxima_cita=$fecha
						WHERE evolucion_id=$evolucion 
						AND inscripcion_id=$inscripcion";
		
		$result = $dbconn->Execute($query);
	
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo InscripcionPYP - UpdateEvoProcesos - SQL";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		
		$dbconn->CommitTrans();
		return true;
	}
	
	function GetCierre($inscripcion)
	{
		list($dbconn) = GetDBconn();
		
		$query="SELECT cierre_id 
						FROM pyp_cpn_cierre_caso
						WHERE inscripcion_id=$inscripcion";
		
		$result = $dbconn->Execute($query);
	
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo InscripcionPYP - GetCierre - SQL";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}

		if($result->fields[0])
			return true;
		
		return false;
	}
	
	function GetInscripcionEvolucion()
	{
		$evolucion=SessionGetVar("Evolucion");
		$programa=SessionGetVar("Programa");
		$inscripcion=SessionGetVar("Inscripcion_$programa");
		
		list($dbconn) = GetDBconn();
		
		$query="SELECT 	max(evolucion_id)
						FROM 		pyp_evoluciones_procesos
						WHERE 	inscripcion_id=$inscripcion";
		
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo InscripcionPYP - GetInscripcionEvolucion - SQL 1";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$evolucion_max=$result->fields[0];
			
			$query="
							SELECT 	inscripcion_id,
											evolucion_id,
											sw_estado
							FROM 		pyp_evoluciones_procesos
							WHERE 	evolucion_id=$evolucion_max
							AND 		inscripcion_id=$inscripcion
							";
			$result = $dbconn->Execute($query);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo InscripcionPYP - GetInscripcionEvolucion - SQL 2";
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
	}
	
	function GetDatos()
	{
		$evolucion=SessionGetVar("Evolucion");
		$programa=SessionGetVar("Programa");
		$inscripcion=SessionGetVar("Inscripcion_$programa");
		$datosPaciente=SessionGetvar("DatosPaciente");
		
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
		
		$evolucion_max=$result->fields[0];
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo InscripcionPYP - GetInscripcionEvolucion - SQL1";
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
						WHERE evolucion_id=$evolucion_max
						AND b.tipo_id_paciente='".$datosPaciente['tipo_id_paciente']."' 
						AND b.paciente_id='".$datosPaciente['paciente_id']."'
						AND b.programa_id=$programa
						";
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo InscripcionPYP - GetInscripcionEvolucion - SQL2";
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

	function GetPaso($submodulo)
	{
		list($dbconn) = GetDBconn();
		
		$query="SELECT paso
						FROM historias_clinicas_templates
						WHERE submodulo='$submodulo'
						AND hc_modulo='ConsultaExterna'";

		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo InscripcionPYP - GetPaso - SQL";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		$paso=$result->fields[0];
		
		return $paso;
	}
	
	function ObtenerInscripcion($evolucion,$programa)
	{
		list($dbconn) = GetDBconn();

		$query="SELECT a.inscripcion_id
						FROM pyp_inscripciones_pacientes as a
						JOIN pyp_evoluciones_procesos as b
						ON
						(
							a.inscripcion_id=b.inscripcion_id
						)
						WHERE b.evolucion_id=$evolucion
						AND a.programa_id=$programa";
							
		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo InscripcionPYP - ObtenerInscripcion - SQL";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
			
		$inscripcion=$result->fields[0];
		
		return $inscripcion;
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