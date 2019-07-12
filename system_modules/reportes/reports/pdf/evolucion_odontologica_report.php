<?php
//Reporte de prueba formato HTML
//
//Un reporte es una clase con el nombre de reporte y el sufijo '_report'
class evolucion_odontologica_html_report
{
	//VECTOR DE DATOS O PARAMETROS PARA GENERAR EL REPORTE
	var $datos;

	//PARAMETROS PARA LA CONFIGURACION DEL REPORTE
	//NO MODIFICAR POR EL MOMENTO - DELEN UN TIEMPITO PARA TERMINAR EL DESARROLLO
	var $title       = '';
	var $author      = '';
	var $sizepage    = 'leter';
	var $Orientation = '';
	var $grayScale   = false;
	var $headers     = array();
	var $footers     = array();

    //CONSTUCTOR DE LA CLASE - RECIBE EL VECTOR DE DATOS - METODO PRIVADO NO MODIFICAR
    function evolucion_odontologica_html_report($datos=array())
    {
		    $this->datos=$datos;
        return true;
    }


	function GetMembrete()
	{
		$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>'',
																'subtitulo'=>'',
																'logo'=>'logocliente.png',
																'align'=>'left'));
		return $Membrete;
	}

//FUNCION CrearReporte()
//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
function CrearReporte()
{
		$pfj=$this->frmPrefijo;

		IncludeLib("funciones_facturacion");
		IncludeLib("tarifario_cargos");

		print_r ($this->datos);
// 		$infoEvolucion = $this->Get_Evoluciones_Odontologicas();
// 		$Cuentas = $this->BuscarCuentas($this->cuenta);
// 		$usuario = $this->NombreUs($this->usuario_id);

		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar', 'inscripcion'.$pfj=>$programas));
		$this->salida.="<form name=\"evoluciones_odontologicas$pfj\" action=\"$accion\" method=\"post\">";
		$this->salida.="<table border=\"0\" width=\"100%\" align=\"center\" class=\"hc_table_list\">";
		$this->salida.="<tr class=\"hc_table_list_title\">";
		$this->salida.="<td width=\"100%\" colspan=\"2\">EVOLUCION HISTORIA CLINICA ODONTOLOGICA</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td width=\"10%\" align=\"center\">FECHA</td>";
		$this->salida.="<td width=\"90%\" align=\"center\">DATOS EVOLUCION</td>";
		$this->salida.="</tr>";
		foreach($infoEvolucion as $k => $v)
		{
			list($fecha,$hora) = explode(" ",$v[2]);
			$fecha = $fecha;


			$this->salida.="<tr>";
			$this->salida.="<td class=\"hc_table_submodulo_list_title\" width=\"10%\" align=\"center\">$fecha</td>";
			$this->salida.="<td width=\"90%\">";
			$this->salida.="<table border=\"0\" class=\"hc_table_list\" width=\"100%\">";

			$this->salida.="<tr class=\"hc_table_list_title\" align=\"center\">";
			$this->salida.="<td width=\"8%\" colspan=\"3\">EVOLUCION</td>";
			$this->salida.="<td width=\"5%\" colspan=\"3\">DIENTE</td>";
			$this->salida.="<td width=\"10%\" colspan=\"3\">SUPERFICIE</td>";
			$this->salida.="<td width=\"40%\" colspan=\"3\">DESCRIPCION DEL PROCEDIMIENTO EJECUTADO</td>";
			$this->salida.="<td width=\"10%\" colspan=\"3\">AUTORIZACION</td>";
			$this->salida.="<td width=\"10%\" colspan=\"3\">FACTURA</td>";
			$this->salida.="<td width=\"10%\" colspan=\"3\">CM / CP</td>";
			$this->salida.="<td width=\"10%\" colspan=\"3\">ODONTOLOGO</td>";
			$this->salida.="</tr>";

			for($j=0; $j<sizeof($v[evo]); $j++)
			{
				/*OBTENER EQUIVALENCIAS*/
				$validados=ValdiarEquivalencias($this->plan,$v[evo][$j][5]);
				/*FIN OBTENER EQUIVALENCIAS*/

				if(!empty($validados))
				{
					$cargo_liq[0]=array('tarifario_id'=>$validados[0]['tarifario_id'],'cargo'=>$validados[0]['cargo'],'cantidad'=>1,'autorizacion_int'=>'','autorizacion_ext'=>'');

					/*LIQUIDAR CUENTA*/
					$resul=LiquidarCargosCuentaVirtual($cargo_liq,array(),array(),array(),$this->plan,$Cuentas[0],$Cuentas[1],$Cuentas[2],$this->servicio,$this->tipoidpaciente,$this->paciente,'','');
					/*FIN LIQUIDAR CUENTA*/

					$this->salida.="<tr class=\"modulo_list_claro\">";
					$this->salida.="<td width=\"8%\" colspan=\"3\" align=\"center\">".$v[1]."</td>";
					$this->salida.="<td width=\"5%\" colspan=\"3\" align=\"center\">".$v[evo][$j][0]."</td>";
					$this->salida.="<td width=\"10%\" colspan=\"3\" align=\"center\">".$v[evo][$j][2]."</td>";
					$this->salida.="<td width=\"60%\" colspan=\"3\" align=\"justify\">".$v[evo][$j][6]." - ".$v[evo][$j][3]." -";
					$this->salida.="<label class=\"label_error\">"."  (".$v[evo][$j][4].").</label></td>";
					$this->salida.="<td width=\"10%\" colspan=\"3\" align=\"center\">&nbsp;</td>";
					$this->salida.="<td width=\"10%\" colspan=\"3\" align=\"center\">".$this->cuenta."</td>";
					$this->salida.="<td width=\"10%\" colspan=\"3\" align=\"center\">".$resul['valor_total_paciente']."</td>";
					$this->salida.="<td width=\"10%\" colspan=\"3\" align=\"center\">".substr($usuario,0,15)."</td>";
					$this->salida.="</tr>";
				}
				else
				{
					$this->salida.="<tr class=\"modulo_list_claro\">";
					$this->salida.="<td width=\"8%\" colspan=\"3\" align=\"center\">".$v[1]."</td>";
					$this->salida.="<td width=\"5%\" colspan=\"3\" align=\"center\">".$v[evo][$j][0]."</td>";
					$this->salida.="<td width=\"10%\" colspan=\"3\" align=\"center\">".$v[evo][$j][2]."</td>";
					$this->salida.="<td width=\"60%\" colspan=\"3\" align=\"justify\">".$v[evo][$j][6]." - ".$v[evo][$j][3]." -";
					$this->salida.="<label class=\"label_error\">"."  (".$v[evo][$j][4].").</label></td>";
					$this->salida.="<td width=\"10%\" colspan=\"3\" align=\"center\">&nbsp;</td>";
					$this->salida.="<td width=\"10%\" colspan=\"3\" align=\"center\">".$this->cuenta."</td>";
					$this->salida.="<td width=\"10%\" colspan=\"3\" align=\"center\">Sin Equivalencia.</td>";
					$this->salida.="<td width=\"10%\" colspan=\"3\" align=\"center\">".substr($usuario,0,15)."</td>";
					$this->salida.="</tr>";
				}

			}
			$this->salida.="</table>";
			$this->salida.="</td>";
			$this->salida.="</tr>";
		}

		$this->salida.="</table>";
		$this->salida.="</form>";
  	return $Salida;
 }

/*

//AQUI TODOS LOS METODOS QUE USTED QUIERA


function Reporte_Justificacion_nopos_ambulatorio()
{
    $pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
	  $query= "select	c.descripcion as principio_activo, b.contenido_unidad_venta,
		b.descripcion as producto, p.unidad_dosificacion, q.nombre as via, p.cantidad, p.observacion,
		p.dosis, a.hc_justificaciones_no_pos_amb, a.evolucion_id, n.descripcion as forma,
		k.concentracion_forma_farmacologica, k.unidad_medida_medicamento_id, a.codigo_producto,
		a.usuario_id_autoriza, a.duracion, a.dosis_dia, a.justificacion, a.ventajas_medicamento,
		a.ventajas_tratamiento, a.precauciones, a.controles_evaluacion_efectividad,
		a.tiempo_respuesta_esperado, a.riesgo_inminente, a.sw_riesgo_inminente,
		a.sw_agotadas_posibilidades_existentes, a.sw_comercializacion_pais, a.sw_homologo_pos,
		a.descripcion_caso_clinico, a.sw_existe_alternativa_pos

		from hc_justificaciones_no_pos_amb as a,	medicamentos as k,
		inv_med_cod_forma_farmacologica as n, hc_medicamentos_recetados_amb p
		left join hc_vias_administracion q on (p.via_administracion_id = q.via_administracion_id),
		inventarios_productos	as b, inv_med_cod_principios_activos as c

		where	a.codigo_producto = '".$this->datos[codigo_producto]."' and
		a.evolucion_id = ".$this->datos[evolucion]." and
		a.codigo_producto = k.codigo_medicamento and k.cod_forma_farmacologica =
		n.cod_forma_farmacologica and a.codigo_producto = p.codigo_producto and
		a.evolucion_id = p.evolucion_id
		and a.codigo_producto = b.codigo_producto and k.cod_principio_activo = c.cod_principio_activo
		and b.codigo_producto = k.codigo_medicamento ";
		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al consultar la justificacion";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
			  $vector_justificacion[]=$result->GetRowAssoc($ToUpper = false);
			  $result->MoveNext();
			}
		}
		$result->Close();

//consulta de los diagnosticos de la justificacion
		$query= "select a.hc_justificaciones_no_pos_amb, a.diagnostico_id,
		b.diagnostico_nombre from hc_justificaciones_no_pos_amb_diagnostico as a,
		diagnosticos as b where a.diagnostico_id = b.diagnostico_id and
		a.hc_justificaciones_no_pos_amb = ".$vector_justificacion[0][hc_justificaciones_no_pos_amb]."";

		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al consultar hc_justificaciones_no_pos_amb_diagnostico";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
			  $vector_diagnostico[]=$result->GetRowAssoc($ToUpper = false);
			  $result->MoveNext();
			}
		}
  $vector_justificacion[diagnosticos]= $vector_diagnostico;
	$result->Close();
//fin de los diagnosticos

//CONSULTA DE LAS ALTERNATIVAS
  $query= "select a.alternativa_pos_id, a.medicamento_pos,
		a.principio_activo, a.dosis_dia_pos, a.duracion_pos,
		a.sw_no_mejoria, a.sw_reaccion_secundaria, a.reaccion_secundaria,
		a.sw_contraindicacion, a.contraindicacion,
		a.otras, a.hc_justificaciones_no_pos_amb
		from hc_justificaciones_no_pos_respuestas_pos as a
 		where (a.hc_justificaciones_no_pos_amb = ".$vector_justificacion[0][hc_justificaciones_no_pos_amb].")";

		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al consultar hc_justificaciones_no_pos_respuestas_pos";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
			$vector_alternativas[]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			}
		}
		$vector_justificacion[alternativas]= $vector_alternativas;
		$result->Close();
		//FIN DE LAS ALTERNATIVAS

//OBTENER DATOS DEL PACIENTE
    $query= "
				select g.nombre_tercero, g.tipo_id_tercero as tipo_id_medico,
				g.tercero_id as medico_id, h.tarjeta_profesional,
				j.descripcion as tipo_profesional,
				e.tipo_id_tercero, e.id, e.razon_social,
			  a.fecha, b.tipo_id_paciente, b.paciente_id,
			  btrim(c.primer_nombre||' '||c.segundo_nombre||' '||
				c.primer_apellido||' '||c.segundo_apellido,'') as nombre
				from hc_evoluciones a, ingresos b, pacientes c,
				departamentos d, empresas e,
        profesionales_usuarios f, terceros g, profesionales h,
				tipos_profesionales j
				where a.evolucion_id = ".$vector_justificacion[0][evolucion_id]." and
				a.ingreso = b.ingreso and
				b.tipo_id_paciente = c.tipo_id_paciente  and b.paciente_id = c.paciente_id
				and b.departamento = d.departamento and d.empresa_id = e.empresa_id
        and a.usuario_id = f.usuario_id and  f.tipo_tercero_id = g.tipo_id_tercero
				AND f.tercero_id = g.tercero_id and f.tipo_tercero_id = h.tipo_id_tercero
				AND f.tercero_id = h.tercero_id and h.tipo_profesional = j.tipo_profesional";

		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al consultar hc_justificaciones_no_pos_respuestas_pos";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
			$paciente[]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			}
		}
		$vector_justificacion[paciente]= $paciente;
		$result->Close();
		//FIN DE DATOS DEL PACIENTE
    return $vector_justificacion;

}


function Reporte_Justificacion_nopos_hospitalario()
{
    $pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
	  $query= "select	c.descripcion as principio_activo, b.contenido_unidad_venta,
		b.descripcion as producto, p.unidad_dosificacion, q.nombre as via, p.cantidad, p.observacion,
		p.dosis, a.hc_justificaciones_no_pos_hosp, a.evolucion_id, n.descripcion as forma,
		k.concentracion_forma_farmacologica, k.unidad_medida_medicamento_id, a.codigo_producto,
		a.usuario_id_autoriza, a.duracion, a.dosis_dia, a.justificacion, a.ventajas_medicamento,
		a.ventajas_tratamiento, a.precauciones, a.controles_evaluacion_efectividad,
		a.tiempo_respuesta_esperado, a.riesgo_inminente, a.sw_riesgo_inminente,
		a.sw_agotadas_posibilidades_existentes, a.sw_comercializacion_pais, a.sw_homologo_pos,
		a.descripcion_caso_clinico, a.sw_existe_alternativa_pos

		from hc_justificaciones_no_pos_hosp as a,	medicamentos as k,
		inv_med_cod_forma_farmacologica as n, hc_medicamentos_recetados_hosp p
		left join hc_vias_administracion q on (p.via_administracion_id = q.via_administracion_id),
		inventarios_productos	as b, inv_med_cod_principios_activos as c

		where	a.codigo_producto = '".$this->datos[codigo_producto]."' and
		a.evolucion_id = ".$this->datos[evolucion]." and
		a.codigo_producto = k.codigo_medicamento and k.cod_forma_farmacologica =
		n.cod_forma_farmacologica and a.codigo_producto = p.codigo_producto and
		a.evolucion_id = p.evolucion_id
		and a.codigo_producto = b.codigo_producto and k.cod_principio_activo = c.cod_principio_activo
		and b.codigo_producto = k.codigo_medicamento ";
		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al consultar la justificacion";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
			  $vector_justificacion[]=$result->GetRowAssoc($ToUpper = false);
			  $result->MoveNext();
			}
		}
		$result->Close();

//consulta de los diagnosticos de la justificacion
		$query= "select a.hc_justificaciones_no_pos_hosp, a.diagnostico_id,
		b.diagnostico_nombre from hc_justificaciones_no_pos_hosp_diagnostico as a,
		diagnosticos as b where a.diagnostico_id = b.diagnostico_id and
		a.hc_justificaciones_no_pos_hosp = ".$vector_justificacion[0][hc_justificaciones_no_pos_hosp]."";

		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al consultar hc_justificaciones_no_pos_hosp_diagnostico";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
			  $vector_diagnostico[]=$result->GetRowAssoc($ToUpper = false);
			  $result->MoveNext();
			}
		}
  $vector_justificacion[diagnosticos]= $vector_diagnostico;
	$result->Close();
//fin de los diagnosticos

//CONSULTA DE LAS ALTERNATIVAS
  $query= "select a.alternativa_pos_id, a.medicamento_pos,
		a.principio_activo, a.dosis_dia_pos, a.duracion_pos,
		a.sw_no_mejoria, a.sw_reaccion_secundaria, a.reaccion_secundaria,
		a.sw_contraindicacion, a.contraindicacion,
		a.otras, a.hc_justificaciones_no_pos_hosp
		from hc_justificaciones_no_pos_hosp_respuestas_pos as a
 		where (a.hc_justificaciones_no_pos_hosp = ".$vector_justificacion[0][hc_justificaciones_no_pos_hosp].")";

		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al consultar hc_justificaciones_no_pos_hosp_respuestas_pos";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
			$vector_alternativas[]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			}
		}
		$vector_justificacion[alternativas]= $vector_alternativas;
		$result->Close();
		//FIN DE LAS ALTERNATIVAS

//OBTENER DATOS DEL PACIENTE
    $query= "
				select g.nombre_tercero, g.tipo_id_tercero as tipo_id_medico,
				g.tercero_id as medico_id, h.tarjeta_profesional,
				j.descripcion as tipo_profesional,
				e.tipo_id_tercero, e.id, e.razon_social,
			  a.fecha, b.tipo_id_paciente, b.paciente_id,
			  btrim(c.primer_nombre||' '||c.segundo_nombre||' '||
				c.primer_apellido||' '||c.segundo_apellido,'') as nombre
				from hc_evoluciones a, ingresos b, pacientes c,
				departamentos d, empresas e,
        profesionales_usuarios f, terceros g, profesionales h,
				tipos_profesionales j
				where a.evolucion_id = ".$vector_justificacion[0][evolucion_id]." and
				a.ingreso = b.ingreso and
				b.tipo_id_paciente = c.tipo_id_paciente  and b.paciente_id = c.paciente_id
				and b.departamento = d.departamento and d.empresa_id = e.empresa_id
        and a.usuario_id = f.usuario_id and  f.tipo_tercero_id = g.tipo_id_tercero
				AND f.tercero_id = g.tercero_id and f.tipo_tercero_id = h.tipo_id_tercero
				AND f.tercero_id = h.tercero_id and h.tipo_profesional = j.tipo_profesional";

		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al consultar hc_justificaciones_no_pos_hosp_respuestas_pos";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
			$paciente[]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			}
		}
		$vector_justificacion[paciente]= $paciente;
		$result->Close();
		//FIN DE DATOS DEL PACIENTE
    return $vector_justificacion;

}*/

function FechaStamp($fecha)
	{
			if($fecha){
					$fech = strtok ($fecha,"-");
					for($l=0;$l<3;$l++)
					{
						$date[$l]=$fech;
						$fech = strtok ("-");
					}
					return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
			}
	}

	function HoraStamp($hora)
		{
						$hor = strtok ($hora," ");
						for($l=0;$l<4;$l++)
						{
								$time[$l]=$hor;
								$hor = strtok (":");
						}

						$x = explode (".",$time[3]);
						return  $time[1].":".$time[2].":".$x[0];
		}
    //---------------------------------------
}

?>
