<?php

/**
* clase atencion de atencion gestantes.
* $Id: hc_CierredeCaso_Cierre.class.php,v 1.2 2007/02/01 20:44:26 luis Exp $
* @author luis alejandro vargas
* @version 1.0
* @package SIIS
* 
*/

class Cierre
{

	function Cierre()
	{
		return true;
	}

	function GuardarCierreCaso($inscripcion,$evolucion,$datos,$opcion)
	{
		$pfj=SessionGetVar("Prefijo");
		
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$query="SELECT max(cierre_id)
						FROM pyp_cpn_cierre_caso";
		
		$result = $dbconn->Execute($query);
		
		$cierre_id=$result->fields[0]+1;
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo CierredeCaso - GuardarCierreCaso - SQL 1";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		
		switch($opcion)
		{
			
			case 1:
				$query="INSERT INTO pyp_cpn_cierre_caso
								(
									evolucion_id,
									inscripcion_id,
									cierre_id,
									fecha_terminacion,
									tipo_terminacion,
									semanas_gestacion,
									nivel_atencion,
									sw_episiotomia,
									sw_desgarros,
									sw_muerte_fetal,
									sw_tipo_muerte_fetal,
									atendio_parto,
									atendio_neonato,
									num_hijos_vivos,
									num_hijos_muertos,
									sw_cierre
								)
								VALUES
								(
									$evolucion,
									$inscripcion,
									$cierre_id,
									'".$datos[0]."',
									".$datos[1].",
									".$datos[2].",
									".$datos[3].",
									'".$datos[4]."',
									'".$datos[5]."',
									'".$datos[6]."',
									'".$datos[7]."',
									".$datos[8].",
									".$datos[9].",
									".$datos[10].",
									".$datos[11].",
									".$opcion."
								)";
				break;
				case 2:
					$query="INSERT INTO pyp_cpn_cierre_caso
								(
									evolucion_id,
									inscripcion_id,
									cierre_id,
									fecha_terminacion,
									sw_cierre
								)
								VALUES
								(
									$evolucion,
									$inscripcion,
									$cierre_id,
									'".$datos[0]."',
									".$opcion."
								)";
				
				break;
				case 3:
					$query="INSERT INTO pyp_cpn_cierre_caso
								(
									evolucion_id,
									inscripcion_id,
									cierre_id,
									fecha_terminacion,
									feto_vivo,
									causa_muerte_materna,
									sw_cierre
								)
								VALUES
								(
									$evolucion,
									$inscripcion,
									$cierre_id,
									'".$datos[0]."',
									".$datos[1].",
									'".$datos[2]."',
									".$opcion."
								)";
				
				break;
		}
		
		$result = $dbconn->Execute($query);
	
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo CierredeCaso - GuardarCierreCaso - SQL 2";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$programa=SessionGetVar("Programa");
		SessionSetVar("cierre_caso_$programa",1);
		$dbconn->CommitTrans();
		return true;
	}
	
	function ConsultaInfo($inscripcion)
	{
		$pfj=SessionGetVar("Prefijo");
		
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$query="SELECT	cierre_id,
										evolucion_id,
										inscripcion_id,
										TO_CHAR(fecha_terminacion,'YYYY-MM-DD') as fecha_terminacion,
										semanas_gestacion,
										CASE tipo_terminacion
										WHEN 1 THEN 'ESPONTANEA'
										WHEN 2 THEN 'CESARIA'
										WHEN 3 THEN 'FORCEPS'
										WHEN 4 THEN 'OTRA'
										END AS tipo_terminacion,
										CASE nivel_atencion
										WHEN 1 THEN '1'
										WHEN 2 THEN '2'
										WHEN 3 THEN '3'
										WHEN 4 THEN 'DOMICILIARIA'
										WHEN 5 THEN 'OTRO'
										END AS nivel_atencion,
										CASE sw_episiotomia
										WHEN '0' THEN 'NO'
										WHEN '1' THEN 'SI'
										END AS sw_episiotomia,
										CASE sw_desgarros
										WHEN '0' THEN 'NO'
										WHEN '1' THEN 'SI'
										END AS sw_desgarros,
										CASE sw_muerte_fetal
										WHEN '0' THEN 'NO'
										WHEN '1' THEN 'SI'
										END AS sw_muerte_fetal,
										CASE sw_tipo_muerte_fetal
										WHEN 1 THEN 'PARTO'
										WHEN 2 THEN 'MOMENTO DESCONOCIDO'
										END AS sw_tipo_muerte_fetal,
										CASE atendio_parto
										WHEN 1 THEN 'MEDICO'
										WHEN 2 THEN 'ENFERMERA'
										WHEN 3 THEN 'AUXILIAR'
										WHEN 4 THEN 'PARTERA'
										WHEN 5 THEN 'PROMOTOR'
										WHEN 6 THEN 'OTRO'
										END AS atendio_parto,
										CASE atendio_neonato
										WHEN 1 THEN 'MEDICO'
										WHEN 2 THEN 'ENFERMERA'
										WHEN 3 THEN 'AUXILIAR'
										WHEN 4 THEN 'PARTERA'
										WHEN 5 THEN 'PROMOTOR'
										WHEN 6 THEN 'OTRO'
										END AS atendio_neonato,
										num_hijos_vivos,
										causa_muerte_materna,
										CASE feto_vivo
										WHEN true THEN 'SI'
										WHEN false THEN 'NO'
										END AS feto_vivo,
										CASE sw_cierre
										WHEN 1 THEN 'PARTO'
										WHEN 2 THEN 'ABORTO'
										WHEN 3 THEN 'MUERTE MATERNA'
										END AS tipo_cierre,
										sw_cierre
						FROM pyp_cpn_cierre_caso
						WHERE inscripcion_id=$inscripcion";
						
		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo CierredeCaso - ConsultaInfo - SQL";
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