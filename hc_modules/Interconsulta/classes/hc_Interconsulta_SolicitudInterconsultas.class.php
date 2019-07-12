<?php
/**
* Submodulo de Interconsultas.
* $Id: hc_Interconsulta_SolicitudInterconsultas.class.php,v 1.1 2006/12/07 21:18:37 luis Exp $
* @author luis alejandro vargas
* @version 1.0
* @package SIIS
*/

class SolicitudInterconsultas
{

	function SolicitudInterconsultas()
	{
		return true;
	}
	
	
	function Busqueda_Avanzada_especialidad($especialidad_id="",$desc_especialidad="")
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$ban=0;
		$id="";
		$desc="";
	
		if(!empty($especialidad_id))
		{
			$id="WHERE a.especialidad like '%$especialidad_id%'";
			$ban=1;
		}
		
		if(!empty($desc_especialidad))
		{
			if($ban==0)
				$desc="WHERE a.descripcion like '%$desc_especialidad%'";
			else
				$desc="or a.descripcion like '%$desc_especialidad%'";
		}

		$query="SELECT a.especialidad, a.descripcion, b.cargo, c.tipo_consulta_id,d.sw_cantidad 
						FROM especialidades as a 
						LEFT JOIN especialidades_cargos as b on (a.especialidad = b.especialidad)
						LEFT JOIN tipos_consulta as c on (a.especialidad = c.especialidad)
						LEFT JOIN cups as d on (b.cargo = d.cargo)
						$id $desc";
		
		$result=$dbconn->Execute($query);
						
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error en hc_Interconsultas_SolicitudInterconsultas.class.php -  Busqueda_Especialidad - sql";
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
	
	function Insertar_Varias_Especialidades($datosEspecialidad,$evolucion)
	{
		
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$plan=SessionGetvar("Plan");
		
		for($i=0;$i<sizeof($datosEspecialidad);$i++)
		{
			$datos=$datosEspecialidad[$i];
			
			for($j=0;$j<sizeof($datos);$j++)
			{
				$query1="SELECT nextval('hc_os_solicitudes_hc_os_solicitud_id_seq')";
				$result=$dbconn->Execute($query1);
				$hc_os_solicitud_id=$result->fields[0];
				
				
				$query2="INSERT INTO hc_os_solicitudes
									(hc_os_solicitud_id, evolucion_id, cargo, os_tipo_solicitud_id, plan_id, cantidad,sw_ambulatorio)
									VALUES
									($hc_os_solicitud_id,".$evolucion.",
									'".$datos[$j][cargo]."','INT',
									".$plan.",".$datos[$j][sw_cantidad].",$ambulatorio)";
									
				$result=$dbconn->Execute($query2);
				if ($dbconn->ErrorNo() != 0)
				{
						$this->error = "Error al insertar en hc_os_solicitudes";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
				}
				else
				{
					$query3="INSERT INTO  hc_os_solicitudes_interconsultas
					(hc_os_solicitud_id, especialidad,observacion)
					VALUES  ($hc_os_solicitud_id, '".$datos[$j][especialidad]."',$observacion);";
					$result=$dbconn->Execute($query3);
						
					if($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al insertar en hc_os_solicitudes_interconsultas";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
					else
					{
						if($datos[$j][tipo_consulta_id]!=NULL )
						{
							$query4="INSERT INTO hc_os_solicitudes_citas
										(hc_os_solicitud_id, tipo_consulta_id)
										VALUES  ($hc_os_solicitud_id, '".$datos[$j][tipo_consulta_id]."');";
		
							$result=$dbconn->Execute($query4);
							if ($dbconn->ErrorNo() != 0)
							{
								$this->error = "Error al insertar en hc_os_solicitudes_citas";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
							}
						}
					}
				}
			}
		}
		
		$dbconn->CommitTrans();
		return true;
	}
	
	
	function Insertar_Especialidad($datosEspecialidad,$evolucion)
	{
	
		$plan=SessionGetVar("Plan");
		
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		
		$query1="SELECT nextval('hc_os_solicitudes_hc_os_solicitud_id_seq')";
		$result=$dbconn->Execute($query1);
		$hc_os_solicitud_id=$result->fields[0];	
				
		$query2="INSERT INTO hc_os_solicitudes
							(hc_os_solicitud_id, evolucion_id, cargo, os_tipo_solicitud_id, plan_id, cantidad)
							VALUES
							($hc_os_solicitud_id,".$evolucion.",
							'".$datosEspecialidad[2]."','INT',
							".$plan.",".$datosEspecialidad[7].")";
							
		$result=$dbconn->Execute($query2);
		if ($dbconn->ErrorNo() != 0)
		{
				$this->error = "Error al insertar en hc_os_solicitudes";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
		}
		else
		{
			$query3="INSERT INTO  hc_os_solicitudes_interconsultas
			(hc_os_solicitud_id, especialidad,observacion)
			VALUES  ($hc_os_solicitud_id, '".$datosEspecialidad[0]."','".$datosEspecialidad[8]."');";
			$result=$dbconn->Execute($query3);
				
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al insertar en hc_os_solicitudes_interconsultas";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
			else
			{
				if($datos[$j][tipo_consulta_id]!=NULL )
				{
					$query4="INSERT INTO hc_os_solicitudes_citas
								(hc_os_solicitud_id, tipo_consulta_id)
								VALUES  ($hc_os_solicitud_id, '".$datosEspecialidad[3]."');";

					$result=$dbconn->Execute($query4);
					if ($dbconn->ErrorNo() != 0)
					{
								$this->error = "Error al insertar en hc_os_solicitudes_citas";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
					}
				}
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