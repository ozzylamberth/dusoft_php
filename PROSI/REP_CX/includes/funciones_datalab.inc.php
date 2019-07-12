<?php

/**
 * $Id: funciones_datalab.inc.php,v 1.3 2005/06/07 19:03:54 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

//funciones que invoca datalab

//funciones de equivalencia para datalab
function Get_Tarifa($numero_orden_id)
{
		list($dbconn) = GetDBconn();
		$query="select tarifario_id
		from os_maestro_cargos where numero_orden_id = ".$numero_orden_id."";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
				$this->error = "Error en la consulta del Tarifario";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		if (!$result->EOF)
		{
			list($tarifario_id)=$result->FetchRow();
			$result->Close();
		}else{
        return "ERROR";
		}

    $query="SELECT equivalencia FROM
		interface_datalab_tarifario WHERE tarifario_id = '".$tarifario_id."'";
    $result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
				$this->error = "Error en la consulta de la equivalencia del Tarifario";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		if (!$result->EOF)
		{
			list($equivalencia)=$result->FetchRow();
			$result->Close();
		}
    return $equivalencia;
}

function Get_Pagador($numero_orden_id)
{//esta funcion cuadrarla para que devuelva
//elm plan y la descripcion del plan y e inserta orden1, y orden1char.
		list($dbconn) = GetDBconn();
		$query="select c.plan_id, c.plan_descripcion, c.num_contrato
		from os_maestro a, os_ordenes_servicios b, planes c
		where a.numero_orden_id = ".$numero_orden_id." and
		a.orden_servicio_id = b.orden_servicio_id
		and b.plan_id = c.plan_id";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
				$this->error = "Error en la consulta del Pagador";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		/*if (!$result->EOF)
		{
			list($plan_id, $num_contrato)=$result->FetchRow();
			$result->Close();
		}else{
        return "ERROR";
		}

    $query="SELECT equivalencia FROM
		interface_datalab_pagador  WHERE
		plan_id = ".$plan_id."
		and num_contrato = '".$num_contrato."'";
    $result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
				$this->error = "Error en la consulta de la equivalencia del Pagador";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		if (!$result->EOF)
		{
			list($equivalencia)=$result->FetchRow();
			$result->Close();
		}
    return $equivalencia;*/
		if (!$result->EOF)
		{
					$datos=$result->GetRowAssoc($ToUpper = false);
					$result->Close();
		}
		return $datos;
}

function Get_Servicio($numero_orden_id)
{
		list($dbconn) = GetDBconn();
		$query="select c.servicio, c.descripcion
		from os_maestro a, os_ordenes_servicios b, servicios c
		where
		a.numero_orden_id = ".$numero_orden_id." and
		a.orden_servicio_id = b.orden_servicio_id
		and b.servicio = c.servicio";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
				$this->error = "Error en la consulta del servicio";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}/*
		if (!$result->EOF)
		{
			list($servicio)=$result->FetchRow();
			$result->Close();
		}else{
        return "ERROR";
		}
    $query="SELECT equivalencia FROM
		interface_datalab_servicio  WHERE
		servicio = ".$servicio."";
    $result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
				$this->error = "Error en la consulta de la equivalencia del servicio";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		if (!$result->EOF)
		{
			list($equivalencia)=$result->FetchRow();
			$result->Close();
		}
    return $equivalencia;*/
		if (!$result->EOF)
		{
					$datos=$result->GetRowAssoc($ToUpper = false);
					$result->Close();
		}
		return $datos;
}

/*
function Get_Medico($usuario_id)
{
		list($dbconn) = GetDBconn();
    $query="SELECT equivalencia FROM
		interface_datalab_medico  WHERE
		usuario_id = ".$usuario_id."";
    $result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
				$this->error = "Error en la consulta de la equivalencia del medico";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		if (!$result->EOF)
		{
			list($equivalencia)=$result->FetchRow();
			$result->Close();
		}
    return $equivalencia;
}*/

function Get_Datos_Paciente($tipo_id_paciente, $paciente_id)
{
		list($dbconn) = GetDBconn();
    $query="SELECT b.historia_numero, b.historia_prefijo,
		a.paciente_id, a.tipo_id_paciente,
		btrim(a.primer_nombre||' '||a.segundo_nombre, '') as nombres,
		btrim(a.primer_apellido||' '||a.segundo_apellido, '') as apellidos,
		a.fecha_nacimiento, a.residencia_telefono, a.sexo_id
		FROM pacientes a left join historias_clinicas b on
		(a.paciente_id = b.paciente_id and a.tipo_id_paciente = b.tipo_id_paciente)
		WHERE a.tipo_id_paciente = '".$tipo_id_paciente."'
		and a.paciente_id = '".$paciente_id."'";

    $result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
				{
						$this->error = "Error en la consulta de lecturas profesionales";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
		if (!$result->EOF)
		{
					$datos=$result->GetRowAssoc($ToUpper = false);
					$result->Close();
		}
		return $datos;
}

function Get_Sexo($sexo_id)
{
		list($dbconn) = GetDBconn();
    $query="SELECT equivalencia FROM
		interface_datalab_sexo  WHERE
		sexo_id = '".$sexo_id."'";
    $result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
				$this->error = "Error en la consulta de la equivalencia del medico";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		if (!$result->EOF)
		{
			list($equivalencia)=$result->FetchRow();
			$result->Close();
		}
    return $equivalencia;
}

function Get_Cama($numero_orden_id)
{
		list($dbconn) = GetDBconn();
		$query="select c.cama from os_maestro a, ingresos_departamento b,
		movimientos_habitacion c where a.numero_orden_id = ".$numero_orden_id." and
		a.numerodecuenta = b.numerodecuenta and
		b.ingreso_dpto_id = c.ingreso_dpto_id and c.fecha_egreso is NULL ";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
				$this->error = "Error en la consulta del Pagador";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		if (!$result->EOF)
		{
			list($cama)=$result->FetchRow();
			$result->Close();
		}
    return $equivalencia;
}

function Get_Datos_Solicitud($numero_orden_id)
{

//g.usuario_id es el medico de la evolucion que hizo la solicitud
//i.nombre es el nombre del medico de la evolucion que hizo la solicitud
//e.profesional es el nombre del medico que hizo la solicitud manualmente
		list($dbconn) = GetDBconn();
		$query="select i.nombre_tercero as nombre, g.usuario_id, f.*, e.profesional, b.cargo, c.observacion
		FROM os_maestro a left join hc_os_solicitudes_manuales_datos_adicionales f on
		(a.orden_servicio_id = f.orden_servicio_id),
		hc_os_solicitudes b left join hc_os_solicitudes_manuales e on
		(b.hc_os_solicitud_id = e.hc_os_solicitud_id) left join hc_evoluciones g on
		(b.evolucion_id = g.evolucion_id) left join profesionales_usuarios h on
		(g.usuario_id = h.usuario_id) left join terceros i on
		(h.tipo_tercero_id = i.tipo_id_tercero and h.tercero_id = i.tercero_id),
		hc_os_solicitudes_apoyod c
		where a.numero_orden_id = ".$numero_orden_id."
		and a.hc_os_solicitud_id = b.hc_os_solicitud_id
		and b.hc_os_solicitud_id = c.hc_os_solicitud_id";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
				{
						$this->error = "Error en la consulta de lecturas profesionales";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
		if (!$result->EOF)
		{
					$datos=$result->GetRowAssoc($ToUpper = false);
					$result->Close();
		}
		return $datos;
}



//fin de datalab


//FUNCIONES DATALAB DESDE HISTORIA CLINICA Y LISTAS DE TRABAJO
//*****************CASOS PARA DTAALAB**************************
//detalle de los examnes de datalab
function ConsultaExamenesMaquinas($control_id, $numero_orden_id,$sw_origen)
{
	list($dbconnect) = GetDBconn();

	$query="SELECT codigo_datalab, sw_perfil FROM
	interface_datalab_control_detalle WHERE interface_datalab_control_id = ".$control_id."
	and numero_orden_id = ".$numero_orden_id."";
	$result = $dbconnect->Execute($query);
	if ($dbconnect->ErrorNo() != 0)
	{
			$this->error = "Error al consultar los resultados de los examenes";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
	}
	if (!$result->EOF)
	{
		list($codigo_datalab, $sw_perfil)=$result->FetchRow();
	}

	if ($codigo_datalab)
	{
        if ($sw_perfil== '1')
				{
					$query= "SELECT nombre_examen, resultado, unidades, normal_minima, normal_maxima,
					patologico, comentario FROM interface_datalab_resultados
					WHERE numero_orden_id = ".$control_id." and codigo_perfil = ".$codigo_datalab."";
				}
				elseif ($sw_perfil== '2')
				{
	        $query= "SELECT nombre_examen, resultado, unidades, normal_minima, normal_maxima,
					patologico, comentario FROM interface_datalab_resultados
					WHERE numero_orden_id = ".$control_id." and codigo_datalab = ".$codigo_datalab."";
				}
				$result = $dbconnect->Execute($query);
				if ($dbconnect->ErrorNo() != 0)
				{
						$this->error = "Error al consultar los resultados de los examenes";
						$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
						return false;
				}
				else
				{
						while (!$result->EOF)
						{
							$fact[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
						}
				}
				return $fact;
	}
	else
	{
    return false;
	}

}


//------------------------------------------------------------------------------------

?>
