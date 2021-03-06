<?php
//$Id: ApoyosDiagnosticos_HTML.class.php,v 1.4 2005/10/06 21:46:37 mauricio Exp $

class ApoyosDiagnosticos_HTML
{

  var $salida='';



  function ApoyosDiagnosticos_HTML()
	{
    return true;
	}

	function GetPlantillaApoyoDiagnostico($resultado_id, $sw_modo_resultado, $evolucion_id, $accion_observacion)
	{
    $this->salida.= '';
    $this->Plantilla_Apoyos($resultado_id, $sw_modo_resultado, $evolucion_id, $accion_observacion);
    return $this->salida;
	}

	function Plantilla_Apoyos($resultado_id, $sw_modo_resultado, $evolucion_id, $accion_observacion)
	{
			$pfj=$this->frmPrefijo;
			$examenes = $this->ConsultaExamenesPaciente($resultado_id, $sw_modo_resultado);
			//verificacion de lecturas
			$registro = $this->RegistroLecturas($resultado_id);
			$prof = 0;
			for($k=0;$k<sizeof($registro);$k++)
			{
					if ($registro[$k][sw_prof] == '1'){	$prof = 1;}
			}
			//fin de verificacion

			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="<td align=\"center\" colspan=\"1\" width=\"84%\">".$examenes[descripcion]."</td>";

			//opciones de observacion por examen, e informacion del examen
			$this->salida.="<td align=\"center\" colspan=\"1\" width=\"8%\">INFO.<input type='image' name='submit' src='".GetThemePath()."/images/EstacionEnfermeria/info.png' border='0' title='Laboratorio: $examenes[laboratorio]\nRealizado: $examenes[fecha_realizado]\nProfesional: $examenes[profesional]'></td>";
			if ($evolucion_id != '')
			{
				if ($prof == 1)
				{
						$this->salida.="<td align=\"center\" colspan=\"1\" width=\"8%\"><a href='$accion_observacion'>OBS.<img src=\"".GetThemePath()."/images/asignacion_citas.png\" border='0' ></a></td>";
				}
				else
				{
						$this->salida.="<td align=\"center\" colspan=\"1\" width=\"8%\"><a href='$accion_observacion'>OBS.<img src=\"".GetThemePath()."/images/EstacionEnfermeria/edita.png\" border='0' ></a></td>";
				}
			}
			else
			{
					$this->salida.="<td align=\"center\" colspan=\"1\" width=\"8%\">OBS.<img src=\"".GetThemePath()."/images/EstacionEnfermeria/edita.png\" border='0' ></td>";
			}
			//fin de observaciones.
			$this->salida.="</tr>";
			$this->salida.="</table>";

			$vector = $this->ConsultaDetalle($resultado_id);

			if($vector)
			{
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
					for($i=0;$i<sizeof($vector);$i++)
					{
							if( $i % 2)
							{$estilo='modulo_list_claro';}
							else
							{$estilo='modulo_list_oscuro';}

							switch ($vector[$i][lab_plantilla_id])
							{
									case "1": {
																$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
																$this->salida.="<td width=\"25%\">EXAMEN</td>";
																$this->salida.="<td width=\"55%\">RESULTADO</td>";
																$this->salida.="<td width=\"20%\">RANGO NORMAL</td>";
																$this->salida.="</tr>";

																$this->salida.="<tr class=\"modulo_list_claro\">";
																$this->salida.="<td align=\"center\" >".strtoupper($vector[$i][nombre_examen])."</td>";
																if ($vector[$i][sw_alerta] == '1')
																{
																		$this->salida.="<td class=label_error align=\"center\">".$vector[$i][resultado]." ".$vector[$i][unidades]."</td>";
																}
																else
																{
																		$this->salida.="<td align=\"center\">".$vector[$i][resultado]." ".$vector[$i][unidades]."</td>";
																}
																$this->salida.="<td align=\"center\" >".$vector[$i][rango_min]." - ".$vector[$i][rango_max]." ".$vector[$i][unidades]."</td>";
																$this->salida.="</tr>";
																break;
														}

									case "2": {
																$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
																$this->salida.="<td width=\"25%\">EXAMEN</td>";
																$this->salida.="<td width=\"55%\">RESULTADO</td>";
																$this->salida.="<td width=\"20%\">RANGO NORMAL</td>";
																$this->salida.="</tr>";
																$this->salida.="<tr class=\"modulo_list_claro\">";
																$this->salida.="<td align=\"center\" >".strtoupper($vector[$i][nombre_examen])."</td>";
																$this->salida.="<td align=\"center\" >".$vector[$i][resultado]."</td>";
																$this->salida.="<td align=\"center\">&nbsp;</td>";
																$this->salida.="</tr>";
																break;
														}

									case "3": {
																$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
																$this->salida.="  <td colspan=\"1\" width=\"25%\">EXAMEN</td>";
																$this->salida.="  <td colspan=\"2\" align=\"center\" width=\"75%\">".strtoupper($vector[$i][nombre_examen])."</td>";
																$this->salida.="</tr>";
																$this->salida.="<tr class=\"$estilo\">";
																$vector[$i][resultado]=str_replace("\x0a","<p></p>",$vector[$i][resultado]);
																$this->salida.="  <td colspan=\"3\" align=\"justify\" width=\"100%\">".$vector[$i][resultado]."</td>";
																$this->salida.="</tr>";
																break;
														}

									case "0": {
																$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
																$this->salida.="<td width=\"25%\" colspan=\"1\">EXAMEN</td>";
																$this->salida.="<td width=\"75%\" colspan=\"2\">RESULTADO</td>";
																$this->salida.="</tr>";
																$this->salida.="<tr class=\"$estilo\">";
																$this->salida.="<td align=\"center\" >".strtoupper($vector[$i][nombre_examen])."</td>";
																$this->salida.="<td align=\"center\" colspan=\"2\">".$vector[$i][resultado]."</td>";
																$this->salida.="</tr>";
																break;
														}

									case "5": {//caso exclusivo para datalab
																IncludeLib('funciones_datalab');
																$datos = ConsultaExamenesMaquinas($vector[$i][resultado], $examenes[numero_orden_id]);
																if($datos)
																{
																		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
																		$this->salida.="<td width=\"25%\">EXAMEN</td>";
																		$this->salida.="<td width=\"55%\">RESULTADO</td>";
																		$this->salida.="<td width=\"20%\">RANGO NORMAL</td>";
																		$this->salida.="</tr>";
																		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
																		for($j=0;$j<sizeof($datos);$j++)
																		{
																				if($j % 2) {$estilo='modulo_list_claro';}
																				else {$estilo='modulo_list_oscuro';}
																				$this->salida.="<tr class=\"$estilo\">";
																				$this->salida.="  <td align=\"left\" >".$datos[$j][nombre_examen]."</td>";
																				if ($datos[$j][patologico] == '1')
																				{
																						$this->salida.="  <td  class=label_error align=\"left\">".$datos[$j][resultado]." ".$datos[$j][unidades]."</td>";
																				}
																				else
																				{
																						$this->salida.="  <td align=\"left\">".$datos[$j][resultado]."&nbsp;".$datos[$j][unidades]."</td>";
																				}
																				$this->salida.="  <td  align=\"left\" width=\"25%\">".$datos[$j][normal_minima]." - ".$datos[$j][normal_maxima]." ".$datos[$j][unidades]."</td>";
																				$this->salida.="</tr>";
                                        if($datos[$j][comentario]!='')
																				{
																						$this->salida.="<tr class=\"$estilo\">";
																						$this->salida.="  <td colspan=\"3\" align=\"left\" >COMENTARIO: ".$datos[$j][comentario]."</td>";
																						$this->salida.="</tr>";
																				}
																		}
																}
																break;
														}
							}//cierra el switche
					}//cierra el for

					$observaciones = $this->ConsultaObservaciones($resultado_id);
					if ($examenes[informacion]!= '' OR $examenes[observacion_prestacion_servicio]!= ''
					OR (!empty($observaciones)) OR (sizeof($examenes[observaciones_adicionales])>=1))
					{
							$this->salida.="<tr class=\"$estilo\">";
							$this->salida.="<td colspan=\"3\">";
							$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
							if ($examenes[informacion])
							{
								$this->salida.="<tr class=\"modulo_list_claro\" >";
								$this->salida.="<td colspan=\"1\" width=\"25%\" align=\"left\">INFORMACION: </td>";
								$this->salida.="<td colspan=\"2\" width=\"75%\" align=\"left\"><font size='1'>".$examenes[informacion]."</font></td>";
								$this->salida.="</tr>";
							}

							if ($examenes[observacion_prestacion_servicio])
							{
								$this->salida.="<tr class=\"modulo_list_claro\" >";
								$this->salida.="<td colspan=\"1\" width=\"25%\" align=\"left\">OBSERVACION PREST. SERVICIO</td>";
								$this->salida.="<td colspan=\"2\" width=\"75%\" align=\"left\">".$examenes[observacion_prestacion_servicio]."</td>";
								$this->salida.="</tr>";
							}

							//listado de las observaciones adicionales al resultado
							if(sizeof($examenes[observaciones_adicionales])>=1)
							{
									$this->salida.="<tr class=\"modulo_list_claro\" >";
									$this->salida.="<td align=\"left\" colspan=\"1\" width=\"25%\" >OBSERVACIONES ADICIONALES REALIZADAS AL RESULTADO</td>";
									$this->salida.="<td align=\"left\" colspan=\"2\" width=\"75%\" class=\"modulo_list_oscuro\">";
									$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
									$this->salida.="<tr>";
									$this->salida.="<td align=\"left\" class=\"hc_table_submodulo_list_title\" width=\"5%\">No.</td>";
									$this->salida.="<td align=\"left\" class=\"hc_table_submodulo_list_title\" width=\"10%\">REGISTRO</td>";
									$this->salida.="<td align=\"left\" class=\"hc_table_submodulo_list_title\" width=\"30%\">PROFESIONAL</td>";
									$this->salida.="<td align=\"left\" class=\"hc_table_submodulo_list_title\" width=\"55%\">OBSERVACION ADICIONAL AL RESULTADO</td>";
									$this->salida.="</tr>";
									for($i=0;$i<sizeof($examenes[observaciones_adicionales]);$i++)
									{
											if( $i % 2)    {$estilo='modulo_list_claro';}
											else{$estilo='modulo_list_oscuro';}
											$this->salida.="<tr>";
											$this->salida.="<td align=\"center\" class=\"$estilo\" >".($i+1)."</td>";
											$this->salida.="<td align=\"center\" class=\"$estilo\" >".$this->FechaStampMostrar($examenes[observaciones_adicionales][$i][fecha_registro_observacion])." - ".$this->HoraStamp($examenes[observaciones_adicionales][$i][fecha_registro_observacion])."</td>";
											$this->salida.="<td align=\"center\" class=\"$estilo\" >".$examenes[observaciones_adicionales][$i][usuario_observacion]."</td>";
											$this->salida.="<td align=\"left\" class=\"$estilo\" >".$examenes[observaciones_adicionales][$i][observacion_adicional]."</td>";
											$this->salida.="</tr>";
									}
									$this->salida.="</table>";
									$this->salida.="</td>";
									$this->salida.="</tr>";
							}
							//fin de las observaciones adicionales

							if ($observaciones)
							{
								$this->salida.="<tr class=\"modulo_list_claro\" >";
								//$this->salida.="<td colspan=\"1\" align=\"left\" class=\"hc_table_submodulo_list_title\" width=\"25%\">OBSERVACIONES MEDICAS</td>";
								$this->salida.="<td colspan=\"1\" width=\"25%\" align=\"left\">OBSERVACIONES MEDICAS</td>";
								$this->salida.="<td colspan=\"2\" align=\"left\" width=\"75%\">";
								$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
								for($i=0;$i<sizeof($observaciones);$i++)
								{
										$this->salida.="<tr>";
										$this->salida.="<td align=\"left\" class=\"hc_table_submodulo_list_title\" >".$observaciones[$i][descripcion]." - ".$observaciones[$i][nombre]."</td>";
										$this->salida.="</tr>";

										$this->salida.="<tr>";
										$this->salida.="<td align=\"left\"class=\"$estilo\" >".$observaciones[$i][observacion_prof]."</td>";
										$this->salida.="</tr>";
								}
								$this->salida.="</table>";
								$this->salida.="</td>";
								$this->salida.="</tr>";
							}


							$this->salida.="</table>";
							$this->salida.="</td>";
							$this->salida.="</tr>";
					}
					$this->salida.="</table>";
			}


	}//fin de la funcion Plantilla_Apoyos


	//funciones de busquedas

	function ConsultaExamenesPaciente($resultado_id, $sw_modo_resultado)
{
		list($dbconnect) = GetDBconn();

		//esta consulta la referencia a los examens en resultado manual, en resultados
		//no solicitados y en resultados sistema.
    $query = '';
		if ($sw_modo_resultado == '1')
		{
				$query="  SELECT b.numero_orden_id, a.resultado_id, a.fecha_realizado,
				          a.observacion_prestacion_servicio,
									i.nombre_tercero as profesional, case when f.razon_social is not null then
									f.razon_social else k.nombre_tercero end as laboratorio

									,l.descripcion, m.informacion

									FROM hc_resultados as a, hc_resultados_sistema as b, profesionales_usuarios as g,
									profesionales as h, terceros as i, os_maestro as c left join os_internas as d on
									(c.numero_orden_id=d.numero_orden_id) left join departamentos as e on
									(d.departamento=e.departamento) left join empresas as f on(e.empresa_id=f.empresa_id)
									left join os_externas as j on(c.numero_orden_id=j.numero_orden_id) left join
									terceros as k on(j.tipo_id_tercero=k.tipo_id_tercero and j.tercero_id=k.tercero_id)

									, cups l,	apoyod_cargos m

									WHERE a.resultado_id = b.resultado_id and a.resultado_id = ".$resultado_id." and
									b.numero_orden_id=c.numero_orden_id and b.usuario_id_profesional=g.usuario_id and
									g.tipo_tercero_id=h.tipo_id_tercero and g.tercero_id=h.tercero_id and
									h.tipo_id_tercero=i.tipo_id_tercero and h.tercero_id=i.tercero_id

									and c.cargo_cups = l.cargo and l.cargo = m.cargo
									;";

		}
		elseif ($sw_modo_resultado == '2')
		{
				$query="  SELECT b.numero_orden_id, a.resultado_id, a.fecha_realizado,
				          a.observacion_prestacion_servicio,
									b.profesional, case when f.razon_social is not null then f.razon_social else
									k.nombre_tercero end as laboratorio

									,l.descripcion, m.informacion

									FROM hc_resultados as a, hc_resultados_manuales as b,    os_maestro as c
									left join os_internas as d on(c.numero_orden_id=d.numero_orden_id) left join
									departamentos as e on(d.departamento=e.departamento) left join empresas as f
									on(e.empresa_id=f.empresa_id) left join os_externas as j on
									(c.numero_orden_id=j.numero_orden_id)    left join terceros as k on
									(j.tipo_id_tercero=k.tipo_id_tercero and j.tercero_id=k.tercero_id)

									, cups l,	apoyod_cargos m

									WHERE a.resultado_id = b.resultado_id and a.resultado_id = ".$resultado_id."
									and b.numero_orden_id=c.numero_orden_id

                  and c.cargo_cups = l.cargo and l.cargo = m.cargo
									;";
		}
		elseif ($sw_modo_resultado == '3')
		{
				$query="  SELECT a.resultado_id, a.fecha_realizado,
									a.observacion_prestacion_servicio, b.profesional, b.laboratorio

									,l.descripcion, m.informacion

									FROM hc_resultados as a, hc_resultados_nosolicitados as b

									, cups l,	apoyod_cargos m

									WHERE a.resultado_id = b.resultado_id and a.resultado_id = ".$resultado_id."

									and a.cargo = l.cargo and l.cargo = m.cargo

									;";
		}

		if ($query !='')
		{
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
				$result->Close();
				return $a;
		}
		else
		{
        return false;
		}
}

//ad*
//esta funcion busca en la tabla hc_lecturas_profesionales el registro de las lecturas
// realizadas para cada resultado_id
function RegistroLecturas($resultado_id)
{
		list($dbconnect) = GetDBconn();
		$query = "select resultado_id, sw_prof, sw_prof_dpto, sw_prof_todos, evolucion_id
		from hc_apoyod_lecturas_profesionales where resultado_id = ".$resultado_id."
		order by resultado_id";

		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error en la consulta de lecturas profesionales";
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
		$result->Close();
		return $fact;
}

//ad*
function ConsultaDetalle($resultado_id)
{
//OJO CLAUDIA COMENTE LOS CAMPOS NUEVOS PARA QUE EL QUERY FUNCIONE MIENTRAS TANTO.
// ya los descomente para seguir con las pruebas. a.rango_max, a.rango_min, a.unidades,
		list($dbconnect) = GetDBconn();
		$query=   "SELECT 
								a.lab_examen_id, a.resultado_id, a.resultado,	a.sw_alerta,
								a.rango_max, a.rango_min, a.unidades,
								b.lab_plantilla_id, b.nombre_examen
							FROM hc_apoyod_resultados_detalles a, lab_examenes b
							WHERE  	a.resultado_id = ".$resultado_id." AND 
											a.lab_examen_id=b.lab_examen_id AND
											a.cargo = b.cargo  AND
											a.tecnica_id = b.tecnica_id";

// 								$query=   "SELECT DISTINCT
// 								a.lab_examen_id, a.resultado_id, a.resultado,	a.sw_alerta,
// 								a.rango_max, a.rango_min, a.unidades,
// 								b.lab_plantilla_id, b.nombre_examen
// 								FROM hc_apoyod_resultados_detalles a, lab_examenes b
// 								WHERE  a.resultado_id = ".$resultado_id." AND a.lab_examen_id=b.lab_examen_id";
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
		$result->Close();
	return $fact;
}

//pendiente borrarlo en el origen
function ConsultaObservaciones($resultado_id)
{
		list($dbconnect) = GetDBconn();
		$query =" SELECT a.resultado_id, a.evolucion_id, a.observacion_prof, d.nombre, e.descripcion
							FROM hc_apoyod_lecturas_profesionales as a, hc_evoluciones as b,
							profesionales_usuarios as c, profesionales d, tipos_profesionales e
							WHERE a.resultado_id = ".$resultado_id." AND a.evolucion_id = b.evolucion_id
							AND b.usuario_id = c.usuario_id AND c.tipo_tercero_id = d.tipo_id_tercero
							AND    c.tercero_id = d.tercero_id AND d.tipo_profesional = e.tipo_profesional";
		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
				$this->error = "Error al consultar las observaciones realizadas al Examen";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
		}
		else
		{
				while (!$result->EOF)
				{
					$vector[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
		}
		$result->Close();
		return $vector;
}

function FechaStampMostrar($fecha)
{
    if($fecha){
            $fech = strtok ($fecha,"-");
            for($l=0;$l<3;$l++)
            {
                $date[$l]=$fech;
                $fech = strtok ("-");
            }
            $mes = str_pad(ceil($date[1]), 2, 0, STR_PAD_LEFT);
            $dia = str_pad(ceil($date[2]), 2, 0, STR_PAD_LEFT);
            return  ceil($date[0])."-".$mes."-".$dia;
    }
}

/**
* Separa la hora del formato timestamp
* @access private
* @return string
* @param date hora
*/
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

}
 ?>
