<?php

class app_HistoriaClinicaPapel_user extends classModulo
{

    function app_HistoriaClinicaPapel_user()
    {
        return true;
    }

    function main()
    {
		$this->PantallaInicial();
        return true;
    }

function LlamaInformehis()
{
 	list($dbconn) = GetDBconn();
	$query="SELECT H.historia_numero, H.historia_prefijo FROM historias_clinicas AS H
		WHERE paciente_id = '".$_SESSION['paciente_id']."' AND tipo_id_paciente = '".$_SESSION['tipo_id_paciente']."';";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		while(!$resulta->EOF)
		{
			$infohistoria[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}
	$this->Informehis();
	return true;
}

function LlamaEditarhistoria()
{
	list($dbconn) = GetDBconn();
	$query="UPDATE historias_clinicas
		SET historia_numero='".$_POST['historia_numero']."',
		historia_prefijo='".$_POST['historia_prefijo']."'
		WHERE paciente_id ='".$_REQUEST['paciente_id']."'
		AND tipo_id_paciente ='".$_REQUEST['tipo_id_paciente']."';";
	$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
 	$this->Informehis();
	return true;
}

		function BusquedaHistoriaParaHoyUser()
		{
		 if($this->BusquedaHistoriaParaHoy()==false)
			{
     		  return false;
			}
			return true;
		}

		function BusquedaHistoriasCitas()
		{
			list($dbconn) = GetDBconn();
			if(empty($_REQUEST['DiaEspe']))
			{
			  $_REQUEST['DiaEspe']=date("Y-m-d");
			}
			$sql="select c.hora, a.paciente_id, a.tipo_id_paciente, b.primer_nombre || ' ' || b.segundo_nombre || ' ' || b.primer_apellido || ' ' || b.segundo_apellido as nombre, d.consultorio_id, e.nombre_tercero from agenda_citas_asignadas as a, pacientes as b, agenda_citas as c, agenda_turnos as d, terceros as e where a.paciente_id=b.paciente_id and a.tipo_id_paciente=b.tipo_id_paciente and a.sw_historia='1' and a.agenda_cita_id=c.agenda_cita_id and c.agenda_turno_id=d.agenda_turno_id and date(d.fecha_turno)=date('".$_REQUEST['DiaEspe']."') and d.profesional_id=e.tercero_id and d.tipo_id_profesional=e.tipo_id_tercero and a.sw_atencion='0' order by e.nombre_tercero, c.hora;";
			$result = $dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			while(!$result->EOF)
			{
			  $datos[$result->fields[1]][$result->fields[2]]=$result->GetRowAssoc(false);
				$result->MoveNext();
			}
		  return $datos;
		}
}//end of class

?>
