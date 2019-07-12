<?php

class ResumenAPD
{
		var $salida;
		var $error;
		var $mensajeDeError;
		var $paciente;
		var $tipoidpaciente;
		var $evolucion;
		var $modulo;

    function ResumenAPD()
    {
        $this->salida='';
        $this->error='';
        $this->mensajeDeError='';
        return true;
    }

    function Error()
    {
        return $this->error;
    }

    function ErrorMsg()
    {
        return $this->mensajeDeError;
    }

    function GetSalida()
    {
        return $this->salida;
    }

	function Iniciar()
	{
		
		if(!IncludeFile('classes/modules/hc_classmodules.class.php',true)){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "El archivo 'includes/historia_clinica.inc.php' no existe.";
				return false;
		}
		if(empty($_REQUEST['accion']))
		{
			if(!empty($_REQUEST['BUSCAR']) or ($_REQUEST['grupotipo']!=-1 and !empty($_REQUEST['grupotipo'])))
			{
				$datos=$this->Consulta_Apoyod_delMedico();
				$datos1=$this->ConsultaResultadosNoSolicitados();
			}

			if(empty($datos) and empty($datos1))
			{
					$this->salida.= themeAbrirTabla("APOYOS DIAGNOSTICOS");
					$this->salida.=$this->FormasConsultas();
					$this->salida .= themeCerrarTabla();
			}
			else
			{
				$this->salida.= themeAbrirTabla("APOYOS DIAGNOSTICOS");
				$this->salida.=$this->FormasConsultas();
				$this->salida.='<br>';
				$this->salida.=$this->frmForma_Apoyod_leyenda(&$datos,&$datos1);
				$this->salida .= themeCerrarTabla();
			}
		}
		else
		{
			$this->salida.= themeAbrirTabla("APOYOS DIAGNOSTICOS");
			$this->salida.=$this->FormasConsultas();
			$this->salida.='<br>';
			$this->salida.=$this->Consulta_Resultados();
			$this->salida .= themeCerrarTabla();
		}
		return true;
	}
	
	function FechaStamp($fecha)
	 {
			if($fecha){
					$fech = strtok ($fecha,"-");
					for($l=0;$l<3;$l++)
					{
						$date[$l]=$fech;
						$fech = strtok ("-");
					}
					return  ceil($date[0])."-".ceil($date[1])."-".ceil($date[2]);
			}
	}
	
	function GrupoTipoCargo()
	{
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$sql="select * from apoyod_tipos;";
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($sql);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error en la consulta de Apoyos Diagnosticos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			while ($result_paso = $result->FetchRow()) 
			{
				$datos[$result_paso['apoyod_tipo_id']]=$result_paso;
			}
		}
		return $datos;
	}
	
	function TipoCargo()
	{
		if($_REQUEST['grupotipo']==-1)
		{
			return false;
		}
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$sql="select * from tipos_cargos where grupo_tipo_cargo='".$_REQUEST['grupotipo']."';";
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($sql);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error en la consulta de Apoyos Diagnosticos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			while ($result_paso = $result->FetchRow()) 
			{
				$datos[$result_paso['tipo_cargo']]=$result_paso;
			}
		}
		return $datos;
	}
    
//OK CLZC
	function Consulta_Apoyod_delMedico()
	{
		list($dbconnect) = GetDBconn();
		$tipoid   = $this->tipoidpaciente;
		$paciente = $this->paciente;
		if(empty($_REQUEST['cargob']))
		{
			if($_REQUEST['grupotipo']!=-1 and !empty($_REQUEST['grupotipo']))
			{
				$grupotipo=" and l.grupo_tipo_cargo='".$_REQUEST['grupotipo']."'";
			}
			if($_REQUEST['tipocargo']!=-1 and !empty($_REQUEST['tipocargo']))
			{
				$tipocargo=" and l.tipo_cargo='".$_REQUEST['tipocargo']."'";
			}
			if(!empty($_REQUEST['descripcion']))
			{
				$descripcion=" AND l.descripcion LIKE '%".strtoupper($_REQUEST['descripcion'])."%'";
			}
		}
		else
		{
			$cargo=" and l.cargo='".$_REQUEST['cargob']."'";
		}
		//(h.tipo_id_paciente='".$tipoid."' and h.paciente_id='".$paciente."') and
		$query = "select d.autorizacion_int, d.autorizacion_ext, a.hc_os_solicitud_id,
		a.cargo,

		z.especialidad, x.descripcion as especialidad_nombre,

		a.os_tipo_solicitud_id, b.usuario_id, b.departamento, b.fecha,
		h.resultado_id, k.informacion, case when j.sw_estado is null then '0' when
		j.sw_estado='' then '0' when j.sw_estado='1' then '2' when j.sw_estado='0'
		then '1' end as autorizado, case when e.sw_estado is null then '0' else
		e.sw_estado end as realizacion, case when f.resultado_id is null then '0'
		else f.resultado_id end as resultados_sistema, case when g.resultado_id is null
		then '0' else g.resultado_id end as resultado_manual, e.numero_orden_id,
		h.fecha_realizado,

		case when (k.titulo_examen = '' OR k.titulo_examen ISNULL) then l.descripcion
		else k.titulo_examen end as titulo_examenes,

		f.usuario_id_profesional
		FROM hc_os_solicitudes as a left join
		apoyod_cargos as k on (a.cargo = k.cargo) left join cups as l on
		(a.cargo=l.cargo) left join hc_os_autorizaciones as d on
		(a.hc_os_solicitud_id=d.hc_os_solicitud_id) left join autorizaciones as j
		on(d.autorizacion_int=j.autorizacion) left join	os_maestro as e on
		(a.hc_os_solicitud_id=e.hc_os_solicitud_id) left join	hc_resultados_sistema
		as f on(e.numero_orden_id=f.numero_orden_id) left join hc_resultados_manuales
		as g on(e.numero_orden_id=g.numero_orden_id) left join
		hc_resultados as h on ((h.tipo_id_paciente='".$tipoid."' and h.paciente_id='".$paciente."') and (f.resultado_id=h.resultado_id or g.resultado_id=h.resultado_id)) left join hc_apoyod_resultados_detalles as i on
		(h.resultado_id=i.resultado_id)

    left join hc_os_solicitudes_interconsultas as z on
		(a.hc_os_solicitud_id = z.hc_os_solicitud_id) left join especialidades as x
		on (z.especialidad = x.especialidad),

		hc_evoluciones as b, ingresos as c
		WHERE a.evolucion_id=b.evolucion_id and b.ingreso=c.ingreso and
		c.tipo_id_paciente='$tipoid' and c.paciente_id='$paciente' and
		(k.validez is null or h.fecha_realizado+k.validez>now() or
		h.fecha_realizado is null) and (e.sw_estado is null or e.sw_estado<8)

		AND (a.os_tipo_solicitud_id = 'APD' OR a.os_tipo_solicitud_id = 'INT'
		OR a.os_tipo_solicitud_id = 'PNQ')


		$grupotipo $tipocargo $descripcion $cargo
		order by a.os_tipo_solicitud_id, a.hc_os_solicitud_id";
		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error en la consulta de Apoyos Diagnosticos";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{ 
			$i=0;
			while (!$result->EOF)
			{
				$fact[$i]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
				$i++;
			}
		}
		return $fact;
	}

	//OK CLZC
	function ConsultaResultadosNoSolicitados()
	{
		list($dbconnect) = GetDBconn();
		$tipoid   		= $this->tipoidpaciente;
		$paciente 		= $this->paciente;
		if(empty($_REQUEST['cargo']))
		{
			if($_REQUEST['grupotipo']!=-1 and !empty($_REQUEST['grupotipo']))
			{
				$grupotipo=" and f.grupo_tipo_cargo='".$_REQUEST['grupotipo']."'";
			}
			if($_REQUEST['tipocargo']!=-1 and !empty($_REQUEST['tipocargo']))
			{
				$tipocargo=" and f.tipo_cargo='".$_REQUEST['tipocargo']."'";
			}
			if(!empty($_REQUEST['descripcion']))
			{
				$descripcion=" AND l.descripcion LIKE '%".strtoupper($_REQUEST['descripcion'])."%'";
			}
		}
		else
		{
			$cargo=" and f.cargo='".$_REQUEST['cargo']."'";
		}
		$query = "SELECT b.cargo, b.fecha_realizado, b.resultado_id, c.titulo_examen,
		c.informacion, d.sw_prof, d.evolucion_id, e.fecha FROM hc_resultados_nosolicitados
		as a left join hc_resultados as b on (a.resultado_id = b.resultado_id)
		left join	apoyod_cargos as c on (b.cargo = c.cargo) left join
		hc_apoyod_lecturas_profesionales as d on	(b.resultado_id = d.resultado_id)
		left join hc_evoluciones as e on (d.evolucion_id = e.evolucion_id)
		left join cups as f on(c.cargo=f.cargo)
		WHERE b.tipo_id_paciente = '$tipoid'	AND	b.paciente_id = '$paciente'
		$grupotipo $tipocargo $cargo $descripcion ";
		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la tabla hc_lab_examenes_detalles";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{ 
			$i=0;
			while (!$result->EOF)
			{
				$vector[$i]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
				$i++;
			}
		}
	  return $vector;
	}

//OK CLZC
	function RegistroLecturas($resultado_id)
	{
	 $pfj=$this->frmPrefijo;
	 list($dbconnect) = GetDBconn();

	 $tipoid   = $this->tipoidpaciente;
   $paciente = $this->paciente;
   $query = "select resultado_id, sw_prof, sw_prof_dpto, sw_prof_todos,
	 evolucion_id from hc_apoyod_lecturas_profesionales
	 where resultado_id = $resultado_id
	 order by resultado_id";

	 $result = $dbconnect->Execute($query);
	 if ($dbconnect->ErrorNo() != 0)
	   {
	     $this->error = "Error en la consulta de lecturas profesionales";
		   $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
		   return false;
		 }
	 else
		 { $i=0;
		   while (!$result->EOF)
			   {
			    $fact[$i]=$result->GetRowAssoc($ToUpper = false);
			    $result->MoveNext();
			    $i++;
			   }
		 }
   return $fact;
}


//OK CLZC
function ConsultaNombreMedico($usuario_id_evolucion)
{
			$pfj=$this->frmPrefijo;
	 		list($dbconnect) = GetDBconn();

      $query= "SELECT d.nombre_tercero, c.descripcion
                 FROM profesionales_usuarios a, profesionales b,
								 tipos_profesionales c, terceros d
								 WHERE a.tipo_tercero_id = b.tipo_id_tercero AND
								 a.tercero_id = b.tercero_id AND
								 a.tipo_tercero_id = d.tipo_id_tercero AND
								 a.tercero_id = d.tercero_id AND
								 a.usuario_id = ".$usuario_id_evolucion." AND
								 b.tipo_profesional = c.tipo_profesional";

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar el nombre del profesional";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		return $result->GetRowAssoc($ToUpper = false);
}

//OK CLZC
	function ConsultaExamenesPaciente($resultado_id)
	{
		list($dbconnect) = GetDBconn();

		//esta consulta la referencia a los examens en resultado manual, en resultados
		//no solicitados y en resultados sistema.

		$query="(SELECT a.resultado_id, a.fecha_realizado, a.observacion_prestacion_servicio,
					b.profesional, case when f.razon_social is not null then f.razon_social else
					k.nombre_tercero end as laboratorio
					FROM hc_resultados as a, hc_resultados_manuales as b,	os_maestro as c
					left join os_internas as d on(c.numero_orden_id=d.numero_orden_id) left join
					departamentos as e on(d.departamento=e.departamento) left join empresas as f
					on(e.empresa_id=f.empresa_id) left join os_externas as j on
					(c.numero_orden_id=j.numero_orden_id)	left join terceros as k on
					(j.tipo_id_tercero=k.tipo_id_tercero and j.tercero_id=k.tercero_id)
					WHERE a.resultado_id = b.resultado_id and a.resultado_id = ".$resultado_id."
					and	b.numero_orden_id=c.numero_orden_id)

					UNION	(SELECT a.resultado_id, a.fecha_realizado,
					a.observacion_prestacion_servicio, b.profesional, b.laboratorio
					FROM hc_resultados as a, hc_resultados_nosolicitados as b
					WHERE a.resultado_id = b.resultado_id and a.resultado_id = ".$resultado_id.")

					UNION (SELECT a.resultado_id, a.fecha_realizado, a.observacion_prestacion_servicio,
					i.nombre_tercero as profesional, case when f.razon_social is not null then
					f.razon_social else k.nombre_tercero end as laboratorio
					FROM hc_resultados as a, hc_resultados_sistema as b, profesionales_usuarios as g,
					profesionales as h, terceros as i, os_maestro as c left join os_internas as d on
					(c.numero_orden_id=d.numero_orden_id) left join departamentos as e on
					(d.departamento=e.departamento) left join empresas as f on(e.empresa_id=f.empresa_id)
					left join os_externas as j on(c.numero_orden_id=j.numero_orden_id) left join
					terceros as k on(j.tipo_id_tercero=k.tipo_id_tercero and j.tercero_id=k.tercero_id)
					WHERE a.resultado_id = b.resultado_id and a.resultado_id = ".$resultado_id." and
					b.numero_orden_id=c.numero_orden_id and b.usuario_id_profesional=g.usuario_id and
					g.tipo_tercero_id=h.tipo_id_tercero and g.tercero_id=h.tercero_id and
					h.tipo_id_tercero=i.tipo_id_tercero and h.tercero_id=i.tercero_id);";

				$result = $dbconnect->Execute($query);
				if ($dbconnect->ErrorNo() != 0)
				{
					$this->error = "Error al Consultar los datos del examen";
					$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
					return false;
				}
				$a=$result->GetRowAssoc($ToUpper = false);

				//cargando las observaciones adicionales
			$query="SELECT a.resultado_id, a.observacion_adicional,
					a.fecha_registro_observacion, c.nombre_tercero as usuario_observacion
					FROM hc_resultados_observaciones_adicionales as a,
					profesionales_usuarios as b, terceros as c
					WHERE resultado_id = ".$resultado_id." AND
					a.usuario_id = b.usuario_id
					and b.tipo_tercero_id = c.tipo_id_tercero and b.tercero_id = c.tercero_id
					order by a.observacion_resultado_id";

			$result = $dbconnect->Execute($query);
			if ($dbconnect->ErrorNo() != 0)
			{
				$this->error = "Error al consultar las observaciones adicionales al resultado del apoyo";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
			}
			else
			{ while (!$result->EOF)
				{
					$vector[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
			$a[observaciones_adicionales]=$vector;
		//fin de las observaciones adicionales
			return $a;

		//QUERY QUE HACE LO MISMO PERO CONTRA NOSOLICITADOS
				/*$query="			select a.resultado_id, a.fecha_realizado, a.observacion_prestacion_servicio, b.profesional
		from hc_resultados as a, hc_resultados_nosolicitados as b
		where a.resultado_id = b.resultado_id and a.resultado_id = $resultado_id";*/
	}

//OK CLZC
 	function ConsultaDetalle($resultado_id)
 	{
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		//si ejecuto este llama los cargos pero me esta repitiendo dos veces el mismo resultado en la prueba de embarazo
		//este paciente se tomo dos examenes de embarazo pero solo quiero el resultado de uno.

		$query=   "SELECT DISTINCT
							a.lab_examen_id, a.resultado_id, a.resultado,
							a.sw_alerta, b.lab_plantilla_id, b.nombre_examen,
							b.unidades, c.rango_max,
							c.rango_min, c.sexo_id
							FROM lab_examenes b, hc_apoyod_resultados_detalles a
							left join lab_plantilla1 c on (a.lab_examen_id = c.lab_examen_id)
							left join lab_plantilla2 as d on	(a.lab_examen_id = d.lab_examen_id)
              left join lab_plantilla3 as e on (a.lab_examen_id = e.lab_examen_id)
							WHERE  a.resultado_id = ".$resultado_id." AND a.lab_examen_id=b.lab_examen_id";

		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al consultar los resultados de los examenes";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{ $i=0;
			while (!$result->EOF)
			{
			$fact[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
			}
		}
		return $fact;
	}

//OK CLZC
	function ConsultaObservaciones($resultado_id)
	{
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query ="SELECT a.resultado_id, a.evolucion_id, a.observacion_prof, d.nombre,
				e.descripcion FROM hc_apoyod_lecturas_profesionales as a, hc_evoluciones as b,
				profesionales_usuarios as c, profesionales d, tipos_profesionales e
				WHERE a.resultado_id = $resultado_id AND a.evolucion_id = b.evolucion_id
				AND b.usuario_id = c.usuario_id AND c.tipo_tercero_id = d.tipo_id_tercero
				AND	c.tercero_id = d.tercero_id AND d.tipo_profesional = e.tipo_profesional";
		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al consultar las observaciones realizadas al Examen";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{ $i=0;
			while (!$result->EOF)
			{
			$vector[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
			}
		}
	  return $vector;
	}

}//fin clase

?>
