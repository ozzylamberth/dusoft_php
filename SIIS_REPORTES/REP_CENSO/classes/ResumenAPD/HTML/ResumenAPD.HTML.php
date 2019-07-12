<?php
class ResumenAPD_HTML extends ResumenAPD
{

	function ResumenAPD_HTML($paciente,$tipoidpaciente,$evolucion,$modulo)
	{
		$this->ResumenAPD();
		$this->paciente=$paciente;
		$this->tipoidpaciente=$tipoidpaciente;
		$this->evolucion=$evolucion;
		$this->modulo=$modulo;
		return true;
	}

	function SetStyle($campo)
	{
		if ($this->frmError[$campo] || $campo=="MensajeError")
		{
			if ($campo=="MensajeError")
			{
				return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
			}
			return ("label_error");
		}
		return ("label");
	}
    
	function MostrarApoyosVacios()
	{
		$salida = themeAbrirTabla("RESUMEN DE APOYOS DIAGNOSTICOS");
		$salida .= "<table align=\"center\" width=\"100%\" border=\"0\">\n";
		$salida .= "    <tr align='center'>\n";
		$salida .= "        <td>\n";
		$salida.="<label class=\"label_error\">NO EXISTE NINGÚN APOYO DIAGNOSTICO</label>";
		$salida .= "        </td>\n";
		$salida .= "    </tr>\n";
		$salida .= "    </table>\n";
		$salida .= themeCerrarTabla();
		return $salida;
	}
	
	function FormasConsultas()
	{
		$datos=$this->GrupoTipoCargo();
		$tipocargo=$this->TipoCargo();
		$salida='<script>'."\n";
		$salida.='function GrupoCargo(t)'."\n";
		$salida.='{'."\n";
		$salida.='t.submit();'."\n";
		$salida.='}'."\n";
		$salida.='</script>'."\n";
		$accion=ModuloHCGetURL($this->evolucion,'apoyod',0,$this->modulo);
		$salida.="<form action=\"$accion\" name=\"apoyos\" method=\"post\">\n";
		$salida .= "<table align=\"center\" width=\"60%\" border=\"1\">\n";
		$salida .= "<tr align='center' class='hc_table_submodulo_list_title'>\n";
		$salida .= "<td colspan='2'>\n";
		$salida .= "OPCIONES DE BUSQUEDA\n";
		$salida .= "</td>\n";
		$salida .= "</tr>\n";
		$salida .= "<tr align='center'>\n";
		$salida .= "<td width=\"50%\">\n";
		$salida .= "<label class=\"label\">GRUPO: </label>\n";
		$salida .= "</td>\n";
		$salida .= "<td width=\"50%\">\n";
		$salida.="<select name=\"grupotipo\" class=\"select\" onchange=\"GrupoCargo(this.form)\">\n";
		$salida.="<option value=\"-1\">Todos</option>\n";
		foreach($datos as $k=>$v)
		{
			if($_REQUEST['grupotipo']==$k)
			{
				if(strlen($v['descripcion'])>50)
				{
					$salida.="<option value=\"".$k."\" selected>".substr($v['descripcion'],0,50)."</option>\n";
				}
				else
				{
					$salida.="<option value=\"".$k."\" selected>".$v['descripcion']."</option>\n";
				}
			}
			else
			{
				$salida.="<option value=\"".$k."\">".$v['descripcion']."</option>\n";
			}
		}
		$salida.="</select>\n";
		$salida .= "</td>\n";
		$salida .= "</tr>\n";
		$salida .= "<tr align='center'>\n";
		$salida .= "<td>\n";
		$salida .= "<label class=\"label\">TIPO CARGO: </label>\n";
		$salida .= "</td>\n";
		$salida .= "<td>\n";
		$salida.="<select name=\"tipocargo\" class=\"select\" onchange=\"GrupoCargo(this.form)\">\n";
		$salida.="<option value=\"-1\">Todos</option>\n";
		foreach($tipocargo as $k=>$v)
		{
			if($_REQUEST['tipocargo']==$k)
			{
				if(strlen($v['descripcion'])>50)
				{
					$salida.="<option value=\"".$k."\" selected>".substr($v['descripcion'],0,50)."</option>\n";
				}
				else
				{
					$salida.="<option value=\"".$k."\" selected>".$v['descripcion']."</option>\n";
				}
			}
			else
			{
				if(strlen($v['descripcion'])>50)
				{
					$salida.="<option value=\"".$k."\">".substr($v['descripcion'],0,50)."</option>\n";
				}
				else
				{
					$salida.="<option value=\"".$k."\">".$v['descripcion']."</option>\n";
				}
			}
		}
		$salida.="</select>\n";
		$salida .= "        </td>\n";
		$salida .= "    </tr>\n";
		$salida .= "    <tr align='center'>\n";
		$salida .= "        <td>\n";
		$salida .= "<label class=\"label\">CARGO: </label>\n";
		$salida .= "        </td>\n";
		$salida .= "        <td>\n";
		$salida.="<input type=\"text\" name=\"cargob\" value=\"".$_REQUEST['cargob']."\" class=\"input-text\" maxlength=\"10\">";
		$salida .= "        </td>\n";
		$salida .= "    </tr>\n";
		$salida .= "    <tr align='center'>\n";
		$salida .= "        <td>\n";
		$salida .= "<label class=\"label\">DESCRIPCION: </label>\n";
		$salida .= "        </td>\n";
		$salida .= "        <td>\n";
		$salida.="<input type=\"text\" name=\"descripcion\" value=\"".$_REQUEST['descripcion']."\" class=\"input-text\" maxlength=\"200\">";
		$salida .= "        </td>\n";
		$salida .= "    </tr>\n";
		$salida .= "    <tr align='center' class=\"hc_table_submodulo_list_title\">\n";
		$salida .= "        <td colspan=\"2\">\n";
		$salida.="<input type=\"submit\" value=\"BUSCAR\" name=\"BUSCAR\" class=\"input-submit\">";
		$salida .= "        </td>\n";
		$salida .= "    </tr>\n";
		$salida .= "    </table>\n";
		$salida.="</form>";
		return $salida;
	}

	function Consulta_Resultados()
	{
		$resultado_id=$_REQUEST['resultado_id'];
		$cargo=$_REQUEST['cargo'];
		$titulo=$_REQUEST['titulo'];
		$informacion=$_REQUEST['informacion'];

		$examenes = $this->ConsultaExamenesPaciente($resultado_id);
		$salida.="<br>";
		$salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$salida.="<tr class=\"modulo_table_title\">";
		$salida.="  <td align=\"center\" colspan=\"4\">$titulo</td>";
		$salida.="</tr>";
		$salida.="</table>";

		$salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$salida.="  <td align=\"left\" width=\"20%\">FECHA DE REALIZACION1: </td>";
		$salida.="  <td align=\"center\" width=\"10%\">$examenes[fecha_realizado]</td>";
		$salida.="  <td align=\"left\" width=\"25%\">LABORATORIO</td>";
		$salida.="  <td align=\"left\" width=\"25%\">$examenes[laboratorio]</td>";
		$salida.="</tr>";

		$vector = $this->ConsultaDetalle($resultado_id);
		if($vector)
		{
			$p = 1;
			for($i=0;$i<sizeof($vector);$i++)
			{
				$spia = $vector[$i][lab_plantilla_id];
				switch ($spia)
	     {
        case "1": {
										$nombre=$vector[$i][nombre_examen];
										$rangoMin=$vector[$i][rango_min];
										$rangoMax=$vector[$i][rango_max];
										$unidad=$vector[$i][unidades];
										$resultado = $vector[$i][resultado];
										$sw_alerta = $vector[$i][sw_alerta];
										$sexo = $vector[$i][sexo_id];

                    if(empty($sexo) || $sexo == '0' || $sexo == strtoupper($this->datosPaciente[sexo_id]))
										{
											$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
											$this->salida.="  <td colspan = 2>NOMBRE DEL EXAMEN</td>";
											$this->salida.="  <td>RESULTADO</td>";
											$this->salida.="  <td>RANGO NORMAL</td>";
											$this->salida.="</tr>";
										}


										if(is_null($rangoMin) || is_null($rangoMax))
											{
											$val='';
											if(is_null($rangoMin) || $rangoMin == '0')
												{
												$rangoMin = 0;
												$val="Rango :".$rangoMin."-".$rangoMax."&nbsp;".$unidad."";
												}
											}
										else
										{
											if(empty($sexo) || $sexo == '0')
												{
													$val="Rango :".$rangoMin."-".$rangoMax."&nbsp;".$unidad."";
												}
											else
												{ $p=0;
													if ($sexo == $this->datosPaciente[sexo_id])
														{   if(strtoupper($sexo)=='F')
																{
																	$val="Mujeres : ".$rangoMin."-".$rangoMax."&nbsp;".$unidad."";
																	if( $i % 2)
																		{
																			$estilo='modulo_list_claro';
																		}
																	else
																		{
																			$estilo='modulo_list_oscuro';
																		}
																	$salida.="<tr class=\"$estilo\">";
																	$salida.="  <td align=\"center\" colspan = 2>$nombre</td>";

																	if ($sw_alerta == '1')
																	{
																		$salida.="  <td  class=label_error align=\"center\" width=\"25%\">$resultado&nbsp;$unidad</td>";
																	}
																	else
																	{
																		$salida.="  <td align=\"center\" width=\"25%\">$resultado&nbsp;$unidad</td>";
																	}
																	$salida.="  <td align=\"center\" width=\"25%\">$val</td>";
																	$salida.="</tr>";
																}
																elseif(strtoupper($sexo)=='M')
																	{
																		$val="Hombres : ".$rangoMin."-".$rangoMax."&nbsp;".$unidad."";
																		if( $i % 2)
																			{
																				$estilo='modulo_list_claro';
																			}
																		else
																			{
																				$estilo='modulo_list_oscuro';
																			}
																		$salida.="<tr class=\"$estilo\">";
																		$salida.="  <td align=\"center\" colspan = 2>$nombre</td>";

																		if ($sw_alerta == '1')
																		{
																			$salida.="  <td  class=label_error align=\"center\" width=\"25%\">$resultado&nbsp;$unidad</td>";
																		}
																		else
																		{
																			$salida.="  <td align=\"center\" width=\"25%\">$resultado&nbsp;$unidad</td>";
																		}
																			$salida.="  <td  align=\"center\" width=\"25%\">$val</td>";
																		$salida.="</tr>";
																	}
														}
												}
											}
										if( $i % 2)
											{
												$estilo='modulo_list_claro';
											}
										else
											{
												$estilo='modulo_list_oscuro';
											}
										if ($p == 1)
											{
													$salida.="<tr class=\"$estilo\">";
													$salida.="  <td align=\"center\" colspan = 2>$nombre</td>";

													if ($sw_alerta == '1')
													{ 
														$salida.="  <td  class=label_error align=\"center\" width=\"25%\">$resultado&nbsp;$unidad</td>";
													}
													else
													{
														$salida.="  <td align=\"center\" width=\"25%\">$resultado&nbsp;$unidad</td>";
													}

													$salida.="  <td align=\"center\" width=\"25%\">$val</td>";
													$salida.="</tr>";
											}
											break;
										}

					case "2": {
										  $salida.="<tr class=\"hc_table_submodulo_list_title\">";
											$salida.="  <td colspan = 2>NOMBRE DEL EXAMEN</td>";
											$salida.="  <td>RESULTADO</td>";
											$salida.="  <td>RANGO NORMAL</td>";
											$salida.="</tr>";

											$val = '';
											$nombre=$vector[$i][nombre_examen];
											$opc = $vector[$i][opcion];
											$cod_opc = $vector[$i][lab_examen_opcion_id];
											$id = $vector[$i][lab_examen_id];
											$resultado = $vector[$i][resultado];
											$opcion = $this->ConversionOpcion($resultado, $id);
											$opcion = $opcion[0][opcion];
											if( $i % 2)
													{
														$estilo='modulo_list_claro';
													}
												else
													{
														$estilo='modulo_list_oscuro';
													}

												$salida.="<tr class=\"$estilo\">";
												$salida.="<td align=\"center\" colspan = 2>$nombre</td>";
												$salida.="<td align=\"center\" width=\"25%\">$opcion</td>";
												$salida.="  <td align=\"center\" width=\"25%\">$val</td>";
												$salida.="</tr>";
												break;
											}

				case "3":   {
											$salida.="<tr class=\"hc_table_submodulo_list_title\">";
											$salida.="  <td colspan = 2>NOMBRE DEL EXAMEN</td>";
											$salida.="  <td colspan = 2>RESULTADO</td>";
											$salida.="</tr>";
											$nombre=$vector[$i][nombre_examen];
											$cod=$vector[$i][lab_examen_id];
											$resultado=$vector[$i][resultado];
											if( $i % 2)
													{$estilo='modulo_list_claro';}
											else
													{$estilo='modulo_list_oscuro';}

											$salida.="<tr class=\"$estilo\">";
											$salida.="  <td colspan = 2 align=\"center\" width=\"40%\"class=".$this->SetStyle("res").">$nombre</td>";
											$salida.="  <input type='hidden' name = 'nom$e'  value='$cod'>";
											$salida.="  <td colspan = 2 align=\"left\" width=\"30%\">$resultado</td>";
											$salida.="</tr>";
											$e++;
											break;
										}

        case "0":   {
										    $salida.="<tr class=\"hc_table_submodulo_list_title\">";
												$salida.="  <td colspan = 2>NOMBRE DEL EXAMEN</td>";
												$salida.="  <td colspan = 2>RESULTADO</td>";
												$salida.="</tr>";
												$nombre=$vector[$i][nombre_examen];
												$cod=$vector[$i][lab_examen_id];
												$resultado=$vector[$i][resultado];
												if( $i % 2)
														{$estilo='modulo_list_claro';}
												else
														{$estilo='modulo_list_oscuro';}

												$salida.="<tr class=\"$estilo\">";
												$salida.="  <td colspan = 2 align=\"center\" width=\"40%\"class=".$this->SetStyle("res").">$nombre</td>";
												$salida.="  <input type='hidden' name = 'nom$e'  value='$cod'>";
												$salida.="  <td colspan = 2 align=\"center\" width=\"30%\">$resultado</td>";
												$salida.="</tr>";
												$e++;
												break;
										  }
				}//cierra el switche
      //res$i;
		 }//cierra el for
			$salida.="<tr class=\"$estilo\">";
			$salida.="<td align=\"center\">INFORMACION: </td><td colspan = 3 align=\"left\"><font size='1'>$informacion</font></td>";
			$salida.="</tr>";

			$salida.="<tr>";
			$salida.="<td align='left' class=\"hc_table_submodulo_list_title\"width='20%'>Observacion Bacteriologo</td>";
			$salida.="<td colspan = 3 align=\"center\"class=\"$estilo\" width='60%'>$examenes[observacion_prestacion_servicio]</td>";
			$salida.="</tr>";

			$salida.="<tr>";
			$salida.="<td align='left' class=\"hc_table_submodulo_list_title\"width='20%'>Profesional</td>";
			$salida.="<td colspan = 3 align=\"left\"class=\"$estilo\" width='60%'>$examenes[profesional]</td>";
			$salida.="</tr>";

//listado de las observaciones adicionales al resultado
		if(sizeof($examenes[observaciones_adicionales])>=1)
      {
        $this->salida.="<tr>";
				$this->salida.="<td align='left' class=\"hc_table_submodulo_list_title\"width='20%'>OBSERVACIONES ADICIONALES REALIZADAS AL RESULTADO</td>";
				$this->salida.="<td colspan = 3 align=\"left\" class=\"modulo_list_oscuro\" width='60%'>";

				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";

				$this->salida.="<tr>";
				$this->salida.="<td align='left' class=\"hc_table_submodulo_list_title\" width='5%'>No.</td>";
				$this->salida.="<td align='left' class=\"hc_table_submodulo_list_title\" width='10%'>REGISTRO</td>";
				$this->salida.="<td align='left' class=\"hc_table_submodulo_list_title\" width='30%'>PROFESIONAL</td>";
				$this->salida.="<td align='left'  class=\"hc_table_submodulo_list_title\" width='55%'>OBSERVACION ADICIONAL AL RESULTADO</td>";
				$this->salida.="</tr>";

				for($i=0;$i<sizeof($examenes[observaciones_adicionales]);$i++)
				{
					if( $i % 2)	{$estilo='modulo_list_claro';}
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


		$observaciones = $this->ConsultaObservaciones($resultado_id);
		for($i=0;$i<sizeof($observaciones);$i++)
			{
				$salida.="<tr>";
				$salida.="<td rowspan = 2 align='left' class=\"hc_table_submodulo_list_title\"width='20%'>Observacion del Medico</td>";
				$salida.="<td colspan = 3 align=\"left\" class=\"hc_table_submodulo_list_title\" width='60%'>".$observaciones[$i][descripcion]." - ".$observaciones[$i][nombre]."</td>";
				$salida.="</tr>";

				$salida.="<tr>";
				$salida.="<td colspan = 3 align=\"left\"class=\"$estilo\" width='60%'>".$observaciones[$i][observacion_prof]."</td>";
				$salida.="</tr>";
			}
			$salida.="</table>";

			$salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$salida.="<tr class=\"$estilo\">";
			//BOTON DE VOLVER
				$accionV=ModuloHCGetURL($this->evolucion,'apoyod',0,'',false,array('grupotipo'=>$_REQUEST['grupotipo'],'tipocargo'=>$_REQUEST['tipocargo'],'cargob'=>$_REQUEST['cargob']));
				$salida .= "<form name=\"forma\" action=\"$accionV\" method=\"post\">";
				$salida .= "<td  colspan = 2 align=\"center\"><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"VOLVER\"></form></td>";
			$salida.="</form>";
			$salida.="</tr>";
			$salida.="</table>";
			return $salida;
	}
}

	function frmForma_Apoyod_leyenda($medicos,$NoSolicitados)
	{
		$paso  = 1;
		$paso1 = 1;
		$paso2 = 1;
		$cad = '';
		GLOBAL $VISTA;
		$salida .="<script>\n";
		$salida .="function DatosAutorizacion1(aint,aext)\n";
		$salida .="{\n";
		$salida .="var url='reports/$VISTA/datosautorizacion.php?autorizacion_int='+aint+'&autorizacion_ext='+aext;\n";
		$salida .="window.open(url,'','width=600,height=450,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no');\n";
		$salida .="}\n";
		$salida .="</script>\n";
		$salida .= "<form name=\"formades\" action=\"$accion\" method=\"post\">";
		$salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
		$salida.="<tr class=\"modulo_table_title\">";
		$salida.="  <td align=\"center\" colspan=\"5\">EXAMENES SOLICITADOS AL PACIENTE POR:</td>";
		$salida.="</tr>";
					for($i=0;$i<sizeof($medicos);$i++)
					{
								$var = 0;
								if( $i % 2){ $estilo='modulo_list_claro';}
								else {$estilo='modulo_list_oscuro';}
								$cargo					    	= $medicos[$i][cargo];
								$fecha_evolucion = $this->FechaStamp($medicos[$i][fecha]);
								$fecha_realizado    	= $medicos[$i][fecha_realizado];
								$titulo       				= $medicos[$i][titulo_examenes];
								$usuario_id_evolucion	= $medicos[$i][usuario_id];
								$departamento   			= $medicos[$i][departamento];
								$autorizado						= $medicos[$i][autorizado];
								$realizacion					= $medicos[$i][realizacion];
								$resultado_id   			= $medicos[$i][resultado_id];
								$numero_orden_id 			= $medicos[$i][numero_orden_id];
								$resultado_manual			=	$medicos[$i][resultado_manual];
								$resultados_sistema		=	$medicos[$i][resultados_sistema];
								$informacion          =	$medicos[$i][informacion];

//carga las lecturas que hay en la tabla profesionales de ese resultado_id

//variables que se traen de la siguinte consulta
//resultado_id	sw_prof	sw_prof_dpto	sw_prof_todos	evolucion_id
								$registro = $this->RegistroLecturas($resultado_id);

// se esta sacando $usuario_id_evolucion de la tabla hc_evoluciones
								if ($usuario_id_evolucion == UserGetUID())
								{
									if ($paso==1)
									{
										$nombre  = $this->ConsultaNombreMedico($usuario_id_evolucion);
										$salida.="<tr class=\"modulo_table_title\">";
										$salida.="  <td align=\"left\" colspan=\"5\">".$nombre[descripcion]." - ".$nombre[nombre_tercero]."</td>";
										$salida.="</tr>";
										$salida.="<tr class=\"hc_table_submodulo_list_title\">";
										$salida.="<td width=\"10%\">Fecha Evolucion </td>";
										$salida.=" <td width=\"40%\">Examen</td>";
										$salida.=" <td width=\"10%\">Estado</td>";
										$salida.="<td width=\"10%\">Revision </td>";
										$salida.="<td width=\"10%\">Fecha de Realizacion </td>";
										$salida.="</tr>";
										$paso++;
									}
										$salida.="<tr class=\"$estilo\">";
										$salida.="  <td align=\"center\" width=\"10%\">$fecha_evolucion</td>";
										//$salida.="  <td align=\"left\" width=\"40%\">$titulo</td>";
										if($medicos[$i][especialidad]!=NULL)
										{
											$salida.="  <td align=\"left\" width=\"40%\">".$medicos[$i][especialidad_nombre]."</td>";
										}
										else
										{
											$salida.="  <td align=\"left\" width=\"40%\">".$titulo."</td>";
										}
										if($autorizado == '0' OR $autorizado == '2')
										{
											if ($autorizado == '0')
												{
													$salida.="  <td align=\"center\" width=\"10%\">Sin Autorizar</td>";
												}
											if ($autorizado == '2')
												{
													$a=RetornarWinOpenDatosAutorizacion($medicos[$i][autorizacion_int],$medicos[$i][autorizacion_ext],'No Fue Autorizado');
													$salida.="  <td align=\"center\" width=\"10%\">$a</td>";
												}
												$salida.="  <td align=\"center\" width=\"10%\"></td>";
										}
										else
										{
										  	if($autorizado == '1')
													{
														if($realizacion == '0')
															{
																$salida.="  <td align=\"center\" width=\"10%\">Sin Realizar</td>";
																$salida.="  <td align=\"center\" width=\"10%\"></td>";
															}
														if($realizacion == '1')
															{
																$salida.="  <td align=\"center\" width=\"10%\">Sin Pagar</td>";
																$salida.="  <td align=\"center\" width=\"10%\"></td>";
											 				}
														if($realizacion == '2')
															{
																if ($resultado_manual != 0)
																		{
																			$salida.="  <td align=\"center\" width=\"10%\">Resultado Manual</td>";
																			$var = 1;
																		}
																else
																		{
																			if ($resultados_sistema != 0)
																				{
																						$salida.="  <td align=\"center\" width=\"10%\">Resultado Sistema</td>";
                                            if (!empty($medicos[$i][usuario_id_profesional]))
																						{
																						  $var = 1;
																						}
																						else
																						{
                                              $salida.="  <td align=\"center\" width=\"10%\">En Proceso</td>";
																						}
																				}
																			else
																				{
																					$salida.="  <td align=\"center\" width=\"10%\">Tomado sin Resultados</td>";
																					$salida.="  <td align=\"center\" width=\"10%\"></td>";
																				}
																		}
																$cad = '';

																for($j=0;$j<sizeof($registro);$j++)
																		{
																			$sw_prof       =	$registro[$j][sw_prof];
																			$sw_prof_dpto	 =	$registro[$j][sw_prof_dpto];
																			$sw_prof_todos =	$registro[$j][sw_prof_todos];
																			if (($sw_prof == '1') OR ($sw_prof_dpto == '1') OR ($sw_prof_todos == '1'))
																				{
																					$cad = 'Leido por un Profesional';
																					break;
																				}
																		}

																if ($var == 1)
																{
																	if ($cad == '')
																	{
																			$accion=ModuloHCGetURL($this->evolucion,'apoyod',0,'',false,array('accion'=>'lectura_resultados','resultado_id' => $resultado_id, 'cargo' => $cargo, 'sw_prof' => '1', 'sw_prof_dpto' => '0','sw_prof_todos' => '0', 'titulo' => $titulo, 'informacion'=>$informacion,'grupotipo'=>$_REQUEST['grupotipo'],'tipocargo'=>$_REQUEST['tipocargo'],'cargob'=>$_REQUEST['cargob']));
																			$salida.="  <td align=\"center\" width=\"10%\"><a href='$accion'>Sin Lectura</a></td>";
																	}
																	else
																	{
																			$accion=ModuloHCGetURL($this->evolucion,'apoyod',0,'',false,array('accion'=>'lectura_resultados','resultado_id' => $resultado_id, 'cargo' => $cargo, 'sw_prof' => '1', 'sw_prof_dpto' => '0','sw_prof_todos' => '0', 'titulo' => $titulo, 'informacion'=>$informacion,'grupotipo'=>$_REQUEST['grupotipo'],'tipocargo'=>$_REQUEST['tipocargo'],'cargob'=>$_REQUEST['cargob']));
																			$salida.="  <td align=\"center\" width=\"10%\"><a href='$accion'>".$cad."</a></td>";
																	}
																}
															}

														if($realizacion != '0' AND $realizacion != '1' AND $realizacion != '2')
															{
																if ($resultado_manual != 0)
																		{
																			$salida.="  <td align=\"center\" width=\"10%\">Resultado Manual</td>";
																			$var = 1;
																		}
																else
																		{
																			if ($resultados_sistema != 0)
																				{
																						$salida.="  <td align=\"center\" width=\"10%\">Resultado Sistema</td>";
                                            if (!empty($medicos[$i][usuario_id_profesional]))
																						{
																							$var = 1;
																						}
																						else
																						{
																							$salida.="  <td align=\"center\" width=\"10%\">En Proceso</td>";
																						}
																				}
																			else
																				{
																					$salida.="  <td align=\"center\" width=\"10%\">Resultado</td>";
																					$salida.="  <td align=\"center\" width=\"10%\"></td>";
																				}
																		}
																$cad = '';
																for($j=0;$j<sizeof($registro);$j++)
																		{
																			$sw_prof       =	$registro[$j][sw_prof];
																			$sw_prof_dpto	 =	$registro[$j][sw_prof_dpto];
																			$sw_prof_todos =	$registro[$j][sw_prof_todos];
																			if (($sw_prof == '1') OR ($sw_prof_dpto == '1') OR ($sw_prof_todos == '1'))
																				{
																					$cad = 'Leido por un Profesional';
																					break;
																				}
																		}

																if ($var == 1)
																{
																	if ($cad == '')
																	{
																			$accion=ModuloHCGetURL($this->evolucion,'apoyod',0,'',false,array('accion'=>'lectura_resultados','resultado_id' => $resultado_id, 'cargo' => $cargo, 'sw_prof' => '1', 'sw_prof_dpto' => '0','sw_prof_todos' => '0', 'titulo' => $titulo, 'informacion'=>$informacion,'grupotipo'=>$_REQUEST['grupotipo'],'tipocargo'=>$_REQUEST['tipocargo'],'cargob'=>$_REQUEST['cargob']));
																			$salida.="  <td align=\"center\" width=\"10%\"><a href='$accion'>Sin Lectura</a></td>";
																	}
																	else
																	{
																			$accion=ModuloHCGetURL($this->evolucion,'apoyod',0,'',false,array('accion'=>'lectura_resultados','resultado_id' => $resultado_id, 'cargo' => $cargo, 'sw_prof' => '1', 'sw_prof_dpto' => '0','sw_prof_todos' => '0', 'titulo' => $titulo, 'informacion'=>$informacion,'grupotipo'=>$_REQUEST['grupotipo'],'tipocargo'=>$_REQUEST['tipocargo'],'cargob'=>$_REQUEST['cargob']));
																			$salida.="  <td align=\"center\" width=\"10%\"><a href='$accion'>".$cad."</a></td>";
																	}
																}
															}
													}
											}
											$salida.="  <td align=\"center\" width=\"10%\">$fecha_realizado</td>";
											$salida.="</tr>";

								}

						}

//segundo ciclo de busqueda
						 for($i=0;$i<sizeof($medicos);$i++)
						{
				       $var = 0;
								if( $i % 2){ $estilo='modulo_list_claro';}
								else {$estilo='modulo_list_oscuro';}
              $cargo					    	= $medicos[$i][cargo];
								$fecha_evolucion = $this->FechaStamp($medicos[$i][fecha]);
								$fecha_realizado    	= $medicos[$i][fecha_realizado];
								$titulo       				= $medicos[$i][titulo_examenes];
								$usuario_id_evolucion	= $medicos[$i][usuario_id];
              $departamento   			= $medicos[$i][departamento];
								$autorizado						= $medicos[$i][autorizado];
								$realizacion					= $medicos[$i][realizacion];
              $resultado_id   			= $medicos[$i][resultado_id];
								$numero_orden_id 			= $medicos[$i][numero_orden_id];
								$resultado_manual			=	$medicos[$i][resultado_manual];
								$resultados_sistema		=	$medicos[$i][resultados_sistema];
              $informacion         =	$medicos[$i][informacion];

//carga las lecturas que hay en la tabla profesionales de ese resultado_id

//variables que se traen de la siguinte consulta
//resultado_id	sw_prof	sw_prof_dpto	sw_prof_todos	evolucion_id
                $registro = $this->RegistroLecturas($resultado_id);

								// se esta sacando $usuario_id_evolucion de la tabla hc_evoluciones
								if (($usuario_id_evolucion != UserGetUID()) AND ($departamento == $this->departamento))
                  {
									 if ($paso1 ==1)
                     {
											$salida.="<tr class=\"modulo_table_title\">";
					            $salida.="  <td align=\"left\" colspan=\"5\">OTROS PROFESIONAL DEL MISMO DEPARTAMENTO</td>";
					            $salida.="</tr>";

                    $salida.="<tr class=\"hc_table_submodulo_list_title\">";
											$salida.="<td width=\"10%\">Fecha Evolucion </td>";
											$salida.=" <td width=\"40%\">Examen</td>";
					            $salida.=" <td  width=\"10%\">Estado</td>";
											$salida.="<td width=\"10%\">Revision </td>";
											$salida.="<td width=\"10%\">Fecha de Realizacion </td>";
					            $salida.="</tr>";
											 $paso1++;
											 }

											$salida.="<tr class=\"$estilo\">";
								      $salida.="  <td align=\"center\" width=\"10%\">$fecha_evolucion</td>";
											//$salida.="  <td align=\"left\" width=\"40%\">$titulo</td>";
                        if($medicos[$i][especialidad]!=NULL)
												{
													$salida.="  <td align=\"left\" width=\"40%\">".$medicos[$i][especialidad_nombre]."</td>";
												}
												else
												{
													$salida.="  <td align=\"left\" width=\"40%\">".$titulo."</td>";
												}

                      if($autorizado == '0' OR $autorizado == '2')
											{
											  if ($autorizado == '0')
												{
												  $salida.="  <td align=\"center\" width=\"10%\">Sin Autorizar</td>";
												}
											  if ($autorizado == '2')
												{
													$a=RetornarWinOpenDatosAutorizacion($medicos[$i][autorizacion_int],$medicos[$i][autorizacion_ext],'No Fue Autorizado');
													$salida.="  <td align=\"center\" width=\"10%\">$a</td>";
												}
												$salida.="  <td align=\"center\" width=\"10%\"></td>";
											}
											 else
											 {
												if($autorizado == '1')
											 		{
														if($realizacion == '0')
											 				{
                          			$salida.="  <td align=\"center\" width=\"10%\">Sin Realizar</td>";
																$salida.="  <td align=\"center\" width=\"10%\"></td>";
											 				}

														if($realizacion == '1')
											 				{
																	$salida.="  <td align=\"center\" width=\"10%\">Sin Pagar</td>";
																	$salida.="  <td align=\"center\" width=\"10%\"></td>";
											 				}

														if($realizacion == '2')
											 				{
                                 if ($resultado_manual != 0)
																		{
																			$salida.="  <td align=\"center\" width=\"10%\">Resultado Manual</td>";
																			$var = 1;
																		}
																else
																		{
																			if ($resultados_sistema != 0)
																				{
																						$salida.="  <td align=\"center\" width=\"10%\">Resultado Sistema</td>";
                                            if (!empty($medicos[$i][usuario_id_profesional]))
																						{
																							$var = 1;
																						}
																						else
																						{
																							$salida.="  <td align=\"center\" width=\"10%\">En Proceso</td>";
																						}
																				}
																			else
																				{
																					$salida.="  <td align=\"center\" width=\"10%\">Tomado sin Resultados</td>";
																					$salida.="  <td align=\"center\" width=\"10%\"></td>";
																				}
																		}
																$cad = '';
                                for($j=0;$j<sizeof($registro);$j++)
																		{
																			$sw_prof       =	$registro[$j][sw_prof];
																			$sw_prof_dpto	 =	$registro[$j][sw_prof_dpto];
																			$sw_prof_todos =	$registro[$j][sw_prof_todos];
																			if (($sw_prof == '1') OR ($sw_prof_dpto == '1') OR ($sw_prof_todos == '1'))
																				{
																					$cad = 'Leido por un Profesional';
																					break;
																				}
																		}

																if ($var == 1)
																{
                                   if ($cad == '')
																	 {
																		  $accion=ModuloHCGetURL($this->evolucion,'apoyod',0,'',false,array('accion'=>'lectura_resultados','resultado_id' => $resultado_id, 'cargo' => $cargo, 'sw_prof' => '1', 'sw_prof_dpto' => '0','sw_prof_todos' => '0', 'titulo' => $titulo, 'informacion'=>$informacion,'grupotipo'=>$_REQUEST['grupotipo'],'tipocargo'=>$_REQUEST['tipocargo'],'cargob'=>$_REQUEST['cargob']));
																      $salida.="  <td align=\"center\" width=\"10%\"><a href='$accion'>Sin Lectura</a></td>";
																	 }
																	 else
																	 {
																			$accion=ModuloHCGetURL($this->evolucion,'apoyod',0,'',false,array('accion'=>'lectura_resultados','resultado_id' => $resultado_id, 'cargo' => $cargo, 'sw_prof' => '1', 'sw_prof_dpto' => '0','sw_prof_todos' => '0', 'titulo' => $titulo, 'informacion'=>$informacion,'grupotipo'=>$_REQUEST['grupotipo'],'tipocargo'=>$_REQUEST['tipocargo'],'cargob'=>$_REQUEST['cargob']));
																			$salida.="  <td align=\"center\" width=\"10%\"><a href='$accion'>".$cad."</a></td>";
																	 }
																}
											 				}

														if($realizacion != '0' AND $realizacion != '1' AND $realizacion != '2')
											 				{
                                if ($resultado_manual != 0)
																		{
																			$salida.="  <td align=\"center\" width=\"10%\">Resultado Manual</td>";
																			$var = 1;
																		}
																else
																		{
																			if ($resultados_sistema != 0)
																				{
																						$salida.="  <td align=\"center\" width=\"10%\">Resultado Sistema</td>";
																						if (!empty($medicos[$i][usuario_id_profesional]))
																						{
																							$var = 1;
																						}
																						else
																						{
																							$salida.="  <td align=\"center\" width=\"10%\">En Proceso</td>";
																						}
																				}
																			else
																				{
																					$salida.="  <td align=\"center\" width=\"10%\">Resultado</td>";
																					$salida.="  <td align=\"center\" width=\"10%\"></td>";
																				}
																		}
																$cad = '';
                                for($j=0;$j<sizeof($registro);$j++)
																		{
																			$sw_prof       =	$registro[$j][sw_prof];
																			$sw_prof_dpto	 =	$registro[$j][sw_prof_dpto];
																			$sw_prof_todos =	$registro[$j][sw_prof_todos];
																			if (($sw_prof == '1') OR ($sw_prof_dpto == '1') OR ($sw_prof_todos == '1'))
																				{
																					$cad = 'Leido por un Profesional';
																					break;
																				}
																		}

																if ($var == 1)
																{
                                   if ($cad == '')
																	 {
																		  $accion=ModuloHCGetURL($this->evolucion,'apoyod',0,'',false,array('accion'=>'lectura_resultados','resultado_id' => $resultado_id, 'cargo' => $cargo, 'sw_prof' => '1', 'sw_prof_dpto' => '0','sw_prof_todos' => '0', 'titulo' => $titulo, 'informacion'=>$informacion,'grupotipo'=>$_REQUEST['grupotipo'],'tipocargo'=>$_REQUEST['tipocargo'],'cargob'=>$_REQUEST['cargob']));
																      $salida.="  <td align=\"center\" width=\"10%\"><a href='$accion'>Sin Lectura</a></td>";
																	 }
																	 else
																	 {
																			$accion=ModuloHCGetURL($this->evolucion,'apoyod',0,'',false,array('accion'=>'lectura_resultados','resultado_id' => $resultado_id, 'cargo' => $cargo, 'sw_prof' => '1', 'sw_prof_dpto' => '0','sw_prof_todos' => '0', 'titulo' => $titulo, 'informacion'=>$informacion,'grupotipo'=>$_REQUEST['grupotipo'],'tipocargo'=>$_REQUEST['tipocargo'],'cargob'=>$_REQUEST['cargob']));
																			$salida.="  <td align=\"center\" width=\"10%\"><a href='$accion'>".$cad."</a></td>";
																	 }
																}
											 				}
													}
											 }
											 $salida.="  <td align=\"center\" width=\"10%\">$fecha_realizado</td>";
								       $salida.="</tr>";
                 }
            }

//tercer ciclo de busqueda
						for($i=0;$i<sizeof($medicos);$i++)
						   {
              $var = 0;
								if( $i % 2){ $estilo='modulo_list_claro';}
								else {$estilo='modulo_list_oscuro';}
              $cargo					    	= $medicos[$i][cargo];
								$fecha_evolucion = $this->FechaStamp($medicos[$i][fecha]);
								$fecha_realizado    	= $medicos[$i][fecha_realizado];
								$titulo       				= $medicos[$i][titulo_examenes];
								$usuario_id_evolucion	= $medicos[$i][usuario_id];
              $departamento   			= $medicos[$i][departamento];
								$autorizado						= $medicos[$i][autorizado];
								$realizacion					= $medicos[$i][realizacion];
              $resultado_id   			= $medicos[$i][resultado_id];
								$numero_orden_id 			= $medicos[$i][numero_orden_id];
								$resultado_manual			=	$medicos[$i][resultado_manual];
								$resultados_sistema		=	$medicos[$i][resultados_sistema];
              $informacion         =	$medicos[$i][informacion];

//carga las lecturas que hay en la tabla profesionales de ese resultado_id

//variables que se traen de la siguinte consulta
//resultado_id	sw_prof	sw_prof_dpto	sw_prof_todos	evolucion_id
                $registro = $this->RegistroLecturas($resultado_id);

// se esta sacando $usuario_id_evolucion de la tabla hc_evoluciones
								if (($usuario_id_evolucion != UserGetUID()) AND ($departamento != $this->departamento))
					       {
									if ($paso2 == 1)
									{
           						$salida.="<tr class=\"modulo_table_title\">";
					            $salida.="  <td align=\"left\" colspan=\"5\">OTROS PROFESIONALES DE OTROS DEPARTAMENTOS</td>";
					            $salida.="</tr>";

											$salida.="<tr class=\"hc_table_submodulo_list_title\">";
											$salida.="<td width=\"10%\">Fecha Evolucion </td>";
											$salida.=" <td width=\"40%\">Examen</td>";
					            $salida.=" <td  width=\"10%\">Estado</td>";
											$salida.="<td width=\"10%\">Revision </td>";
											$salida.="<td width=\"10%\">Fecha de Realizacion </td>";
					            $salida.="</tr>";
											 $paso2++;
									}		 

											$salida.="<tr class=\"$estilo\">";
								      $salida.="  <td align=\"center\" width=\"10%\">$fecha_evolucion</td>";
											//$salida.="  <td align=\"left\" width=\"40%\">$titulo</td>";

											if($medicos[$i][especialidad]!=NULL)
												{
													$salida.="  <td align=\"left\" width=\"40%\">".$medicos[$i][especialidad_nombre]."</td>";
												}
												else
												{
													$salida.="  <td align=\"left\" width=\"40%\">".$titulo."</td>";
												}

                       if($autorizado == '0' OR $autorizado == '2')
											{
											if ($autorizado == '0')
												{
												  $salida.="  <td align=\"center\" width=\"10%\">Sin Autorizar</td>";
												}
											if ($autorizado == '2')
												{
													$a=RetornarWinOpenDatosAutorizacion($medicos[$i][autorizacion_int],$medicos[$i][autorizacion_ext],'No Fue Autorizado');
													$salida.="  <td align=\"center\" width=\"10%\">$a</td>";
												}
												$salida.="  <td align=\"center\" width=\"10%\"></td>";
											}
											 else
											 {
												if($autorizado == '1')
											 		{
														if($realizacion == '0')
											 				{
                          			$salida.="  <td align=\"center\" width=\"10%\">Sin Realizar</td>";
																$salida.="  <td align=\"center\" width=\"10%\"></td>";
											 				}

														if($realizacion == '1')
											 				{
																	$salida.="  <td align=\"center\" width=\"10%\">Sin Pagar</td>";
																	$salida.="  <td align=\"center\" width=\"10%\"></td>";
											 				}

														if($realizacion == '2')
											 				{
                                 if ($resultado_manual != 0)
																		{
																			$salida.="  <td align=\"center\" width=\"10%\">Resultado Manual</td>";
																			$var = 1;
																		}
																else
																		{
																			if ($resultados_sistema != 0)
																				{
																						$salida.="  <td align=\"center\" width=\"10%\">Resultado Sistema</td>";
																						if (!empty($medicos[$i][usuario_id_profesional]))
																						{
																							$var = 1;
																						}
																						else
																						{
																							$salida.="  <td align=\"center\" width=\"10%\">En Proceso</td>";
																						}
																				}
																			else
																				{
																					$salida.="  <td align=\"center\" width=\"10%\">Tomado sin Resultados</td>";
																					$salida.="  <td align=\"center\" width=\"10%\"></td>";
																				}
																		}
																$cad = '';
                                for($j=0;$j<sizeof($registro);$j++)
																		{
																			$sw_prof       =	$registro[$j][sw_prof];
																			$sw_prof_dpto	 =	$registro[$j][sw_prof_dpto];
																			$sw_prof_todos =	$registro[$j][sw_prof_todos];
																			if (($sw_prof == '1') OR ($sw_prof_dpto == '1') OR ($sw_prof_todos == '1'))
																				{
																					$cad = 'Leido por un Profesional';
																					break;
																				}
																		}

																if ($var == 1)
																{
                                   if ($cad == '')
																	 {
																		  $accion=ModuloHCGetURL($this->evolucion,'apoyod',0,'',false,array('accion'=>'lectura_resultados','resultado_id' => $resultado_id, 'cargo' => $cargo, 'sw_prof' => '1', 'sw_prof_dpto' => '0','sw_prof_todos' => '0', 'titulo' => $titulo, 'informacion'=>$informacion,'grupotipo'=>$_REQUEST['grupotipo'],'tipocargo'=>$_REQUEST['tipocargo'],'cargob'=>$_REQUEST['cargob']));
																      $salida.="  <td align=\"center\" width=\"10%\"><a href='$accion'>Sin Lectura</a></td>";
																	 }
																	 else
																	 {
																	    $accion=ModuloHCGetURL($this->evolucion,'apoyod',0,'',false,array('accion'=>'lectura_resultados','resultado_id' => $resultado_id, 'cargo' => $cargo, 'sw_prof' => '1', 'sw_prof_dpto' => '0','sw_prof_todos' => '0', 'titulo' => $titulo, 'informacion'=>$informacion,'grupotipo'=>$_REQUEST['grupotipo'],'tipocargo'=>$_REQUEST['tipocargo'],'cargob'=>$_REQUEST['cargob']));
																			$salida.="  <td align=\"center\" width=\"10%\"><a href='$accion'>".$cad."</a></td>";
																	 }
																}
											 				}

														if($realizacion != '0' AND $realizacion != '1' AND $realizacion != '2')
											 				{
                                if ($resultado_manual != 0)
																		{
																			$salida.="  <td align=\"center\" width=\"10%\">Resultado Manual</td>";
																			$var = 1;
																		}
																else
																		{
																			if ($resultados_sistema != 0)
																				{
																						$salida.="  <td align=\"center\" width=\"10%\">Resultado Sistema</td>";
																						if (!empty($medicos[$i][usuario_id_profesional]))
																						{
																							$var = 1;
																						}
																						else
																						{
																							$salida.="  <td align=\"center\" width=\"10%\">En Proceso</td>";
																						}
																				}
																			else
																				{
																					$salida.="  <td align=\"center\" width=\"10%\">Resultado</td>";
																					$salida.="  <td align=\"center\" width=\"10%\"></td>";
																				}
																		}
																$cad = '';
                                for($j=0;$j<sizeof($registro);$j++)
																		{
																			$sw_prof       =	$registro[$j][sw_prof];
																			$sw_prof_dpto	 =	$registro[$j][sw_prof_dpto];
																			$sw_prof_todos =	$registro[$j][sw_prof_todos];
																			if (($sw_prof == '1') OR ($sw_prof_dpto == '1') OR ($sw_prof_todos == '1'))
																				{
																					$cad = 'Leido por un Profesional';
																					break;
																				}
																		}

																if ($var == 1)
																{
                                   if ($cad == '')
																	 {
																		  $accion=ModuloHCGetURL($this->evolucion,'apoyod',0,'',false,array('accion'=>'lectura_resultados','resultado_id' => $resultado_id, 'cargo' => $cargo, 'sw_prof' => '1', 'sw_prof_dpto' => '0','sw_prof_todos' => '0', 'titulo' => $titulo, 'informacion'=>$informacion,'grupotipo'=>$_REQUEST['grupotipo'],'tipocargo'=>$_REQUEST['tipocargo'],'cargob'=>$_REQUEST['cargob']));
																      $salida.="  <td align=\"center\" width=\"10%\"><a href='$accion'>Sin Lectura</a></td>";
																	 }
																	 else
																	 {
                                      $accion=ModuloHCGetURL($this->evolucion,'apoyod',0,'',false,array('accion'=>'lectura_resultados','resultado_id' => $resultado_id, 'cargo' => $cargo, 'sw_prof' => '1', 'sw_prof_dpto' => '0','sw_prof_todos' => '0', 'titulo' => $titulo, 'informacion'=>$informacion,'grupotipo'=>$_REQUEST['grupotipo'],'tipocargo'=>$_REQUEST['tipocargo'],'cargob'=>$_REQUEST['cargob']));
																			$salida.="  <td align=\"center\" width=\"10%\"><a href='$accion'>".$cad."</a></td>";
																	 }
																}
											 				}
													}
											 }
											 $salida.="  <td align=\"center\" width=\"10%\">$fecha_realizado</td>";
								       $salida.="</tr>";
                 }
				} //cierre del for - finaliza el tercer ciclo de busqueda
			if($NoSolicitados)
				{
					$salida.="<tr class=\"hc_table_submodulo_list_title\">";
									$salida.="<td width=\"10%\" colspan=\"5\">APOYOS DIGITADOS POR EL MEDICO TRATANTE</td>";
// 									$salida.=" <td width=\"40%\">Examen</td>";
// 									$salida.=" <td width=\"10%\">Estado</td>";
// 									$salida.="<td width=\"10%\">Revision </td>";
// 									$salida.="<td width=\"10%\">Fecha de Realizacion </td>";
					$salida.="</tr>";

					for($i=0;$i<sizeof($NoSolicitados);$i++)
						{
	              if( $i % 2){ $estilo='modulo_list_claro';}
								else {$estilo='modulo_list_oscuro';}
								$cargo					    	= $NoSolicitados[$i][cargo];
								$fecha_evolucion = $this->FechaStamp($NoSolicitados[$i][fecha]);
								$fecha_realizado    	= $NoSolicitados[$i][fecha_realizado];
								$titulo       				= $NoSolicitados[$i][titulo_examen];
				        $resultado_id   			= $NoSolicitados[$i][resultado_id];
				        $informacion         =	$NoSolicitados[$i][informacion];
                $sw_prof         =	$NoSolicitados[$i][sw_prof];

								$salida.="<tr class=\"$estilo\">";
						    $salida.="  <td align=\"center\" width=\"10%\">$fecha_evolucion</td>";
								$salida.="  <td align=\"left\" width=\"40%\">$titulo</td>";
                $salida.="  <td align=\"center\" width=\"10%\">Resultado</td>";
								if ($sw_prof == '1')
								{
								  $accion=ModuloHCGetURL($this->evolucion,'apoyod',0,'',false,array('accion'=>'consulta_resultados','resultado_id' => $resultado_id, 'cargo' => $cargo, 'titulo' => $titulo, 'informacion'=>$informacion,'grupotipo'=>$_REQUEST['grupotipo'],'tipocargo'=>$_REQUEST['tipocargo'],'cargob'=>$_REQUEST['cargob']));
								  $salida.="  <td align=\"center\" width=\"10%\"><a href='$accion'>Leido</a></td>";
								}
  							$salida.="  <td align=\"center\" width=\"10%\">$fecha_realizado</td>";
								$salida.="</tr>";
						}
					}
  					$salida.="</table><br>";
				$salida .= "</form>";
		return $salida;
}

}//fin clase
?>
