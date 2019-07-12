<?php

class app_Agenda_user extends classModulo
{

	function app_Agenda_user()
	{
		return true;
	}

	function main()
	{
		$this->CalendarioInsercion();
		return true;
	}

	function Calendario()
	{
    if($this->CalendarioConsulta())
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function Dia()
	{
		if($this->CalendarioConsultaDia())
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function BusquedaDiaFestivo($fecha)
	{
		list($dbconn) = GetDBconn();
		$sql="select count(dia) from dias_festivos where date(dia)=date('".$fecha."');";
		$result = $dbconn->Execute($sql);
		$i=0;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return 0;
		}
		else
		{
			$s=$result->fields[0];
			return $s;
		}
	}

	function PartirFecha($fecha)
	{
		$fechas[0]=explode("-",$fecha);
		$fechas[1]=explode(" ",$fechas[0][2]);
		$fechas[2]=explode(":",$fechas[1][1]);
		return $fechas;
	}

        /**
	*@method Despliegue de la oportunidad
	*@param $fecha Fecha de la oportunidad
	*@param $departamento Departamento
	*@param $especialidad Especialidad
	*@param $profesional Médico especialista
	*/
	function DesplegarOportunidad($fecha, $departamento, $especialidad, $profesional = null, $tipo_id_profesional = null)
	{
                $oportunidad = 0;
                $sql  = "SELECT SUM(oportunidad) AS disponibles ";
                $sql .= " FROM vw_reporte_oportunidad vw ";
                $sql .= " INNER JOIN tipos_registro tr ON tr.tipo_registro = vw.tipo_registro ";
                $sql .= " WHERE CAST (fecha_turno AS DATE ) = '".$fecha."' ";
                $sql .= " AND especialidad_id = '".$especialidad."' ";
                $sql .= " AND departamento_id like '%".$departamento."%' ";
                if (!empty($profesional))
                {
                    $sql .= " AND profesional_id like '%".$profesional."%' ";
                }

                list($dbconn) = GetDBconn();
		$result = $dbconn->Execute($sql);
		$i=0;

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return 0;
		}
		else
		{
			$oportunidad=$result->fields[0];
		}
                return $oportunidad;
	}

	/**
	*@method Consulta la Especialidad por Tipo de Consulta
	*@param $tipo_consulta Tipo de la consulta
	*/
	function EspecialidadTipoConsulta($tipo_consulta)
	{
		$especialidad = 0;
		$sql  = "SELECT especialidad FROM tipos_consulta ";
                $sql .= " WHERE tipo_consulta_id ='".$tipo_consulta."';";
		list($dbconn) = GetDBconn();
		$result = $dbconn->Execute($sql);
		$i=0;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return 0;
		}
		else
		{
			$especialidad=$result->fields[0];
		}

		return $especialidad;
	}
}
?>