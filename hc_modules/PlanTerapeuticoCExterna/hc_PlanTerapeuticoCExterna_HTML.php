<?php
/**
* Submodulo para la Solicitud de Medicamentos.
*
* @author Claudia Liliana Zuñiga Cañon <claudia_zc@hotmail.com
* @version 1.0
* @package SIIS
* $Id: hc_PlanTerapeuticoCExterna_HTML.php,v 1.7 2006/12/19 21:00:14 jgomez Exp $
*/

class PlanTerapeuticoCExterna_HTML extends PlanTerapeuticoCExterna
{
//clzc - ptce
		function PlanTerapeuticoCExterna_HTML()
		{
			$this->PlanTerapeuticoCExterna();//constructor del padre
			return true;
		}

	  /**
* Esta función retorna los datos de concernientes a la version del submodulo
* @access private
*/

  function GetVersion()
  {
    $informacion=array(
    'version'=>'1',
    'subversion'=>'0',
    'revision'=>'0',
    'fecha'=>'01/27/2005',
    'autor'=>'CLAUDIA LILIANA ZUÑIGA CAÑON',
    'descripcion_cambio' => '',
    'requiere_sql' => false,
    'requerimientos_adicionales' => '',
    'version_kernel' => '1.0'
    );
    return $informacion;
  }

  
  /**
	*		function SetStyle => Muestra mensajes
	* 	crea una fila para poner el mensaje de "Faltan campos por llenar" cambiando a color rojo
	*		el label del campo "obligatorio" sin llenar
	*/
	//clzc - ptce
	function SetStyle($campo)
	{
				if ($this->frmError[$campo] || $campo=="MensajeError")
				{
					if ($campo=="MensajeError"){
						return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
					}
					return ("label_error");
				}
			return ("label");
	}

//clzc - jea - ptce - *
	function CalcularNumeroPasos($conteo)
	{
		$numpaso=ceil($conteo/$this->limit);
		return $numpaso;
	}

	//clzc - jea - ptce - *
	function CalcularBarra($paso)
	{
		$barra=floor($paso/10)*10;
		if(($paso%10)==0)
		{
			$barra=$barra-10;
		}
		return $barra;
	}

	//clzc - jea - ptce - *
	function CalcularOffset($paso)
	{
		$offset=($paso*$this->limit)-$this->limit;
		return $offset;
	}

	//clzc - jea - ptce - *
	function RetornarBarraMedicamentos_Avanzada()//Barra paginadora de los planes clientes
	{
		$pfj=$this->frmPrefijo;
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso1'.$pfj];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Medicamentos',
		'conteo'.$pfj=>$this->conteo,'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
		'criterio1'.$pfj=>$_REQUEST['criterio1'.$pfj],
		'producto'.$pfj=>$_REQUEST['producto'.$pfj],
		'principio_activo'.$pfj=>$_REQUEST['principio_activo'.$pfj]));

		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";

    if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset(1)."&paso1$pfj=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso-1)."&paso1$pfj=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($i)."&paso1$pfj=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso+1)."&paso1$pfj=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($numpasos)."&paso1$pfj=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($i)."&paso1$pfj=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso+1)."&paso1$pfj=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($numpasos)."&paso1$pfj=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
		if(($_REQUEST['Of'.$pfj])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		else
		{
			if($numpasos>10)
			{
				$valor=10+5;
			}
			else
			{
				$valor=$numpasos+5;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		return $salida;
	}


//clzc - si
function frmForma()
{
		$pfj=$this->frmPrefijo;
		unset ($_SESSION['DATOS_M'.$pfj]['PLAN_TERAPEUTICO']);
		unset ($_SESSION['MEDICAMENTOS'.$pfj]);
		unset ($_SESSION['POSOLOGIA4'.$pfj]);
		unset ($_SESSION['DIAGNOSTICOS'.$pfj]);
		unset ($_SESSION['JUSTIFICACION'.$pfj]);
		unset ($_SESSION['MODIFICANDO'.$pfj]);
		unset ($_SESSION['DIAGNOSTICOSM'.$pfj]);
		unset ($_SESSION['MEDICAMENTOSM'.$pfj]);
		if(empty($this->titulo))
		{
			$this->salida = ThemeAbrirTablaSubModulo('SOLICITUD DE MEDICAMENTOS');
		}
		else
		{
			$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
		}

		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
		$this->salida .= "<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
		$vector1=$this->Consulta_Solicitud_Medicamentos();
		if($vector1)
		{
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida.="</table>";

			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"5\">MEDICAMENTOS AMBULATORIOS SOLICITADOS</td>";
			$this->salida.="</tr>";

			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td width=\"7%\">CODIGO</td>";
			$this->salida.="  <td width=\"30%\">PRODUCTO</td>";
			$this->salida.="  <td width=\"29%\">PRINCIPIO ACTIVO</td>";
			$this->salida.="  <td colspan= 2 width=\"14%\">OPCION</td>";
			$this->salida.="</tr>";
			for($i=0;$i<sizeof($vector1);$i++)
				{
								if( $i % 2){ $estilo='modulo_list_claro';}
								else {$estilo='modulo_list_oscuro';}
								$this->salida.="<tr class=\"$estilo\">";
								if($vector1[$i][item] == 'NO POS')
									{
											$this->salida.="  <td ROWSPAN = 4 align=\"center\" width=\"7%\">".$vector1[$i][codigo_producto]."<BR>NO_POS</td>";
									}
								else
									{
											$this->salida.="  <td ROWSPAN = 3 align=\"center\" width=\"7%\">".$vector1[$i][codigo_producto]."</td>";
									}
								$this->salida.="  <td align=\"center\" width=\"30%\">".$vector1[$i][producto]."</td>";
								$this->salida.="  <td align=\"left\" width=\"29%\">".$vector1[$i][principio_activo]."</td>";

								if($vector1[$i][evolucion_id] == $this->evolucion)
								{
									$accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'forma_modificar_medicamento', 'codigo_producto'.$pfj => $vector1[$i][codigo_producto]));
									$this->salida.="  <td align=\"center\" width=\"7%\"><a href='$accion1'><img title=\"Modificar\" src=\"".GetThemePath()."/images/modificar.png\" border='0'></a></td>";
									$accion2=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'eliminar', 'codigo_producto'.$pfj => $vector1[$i][codigo_producto], 'opcion_posologia'.$pfj => $vector1[$i][tipo_opcion_posologia_id]));
									$this->salida.="  <td align=\"center\" width=\"7%\"><a href='$accion2'><img title=\"Eliminar\" src=\"".GetThemePath()."/images/elimina.png\"  border='0'></a></td>";
								}
								else
								{
										$this->salida.="  <td colspan=\"2\" align=\"center\" width=\"14%\">&nbsp;</td>";
								}
										$this->salida.="</tr>";

										$this->salida.="<tr class=\"$estilo\">";
										$this->salida.="<td colspan = 4>";
										$this->salida.="<table>";

										$this->salida.="<tr class=\"$estilo\">";
										$this->salida.="  <td colspan = 3 align=\"left\" width=\"9%\">Via de Administracion: ".$vector1[$i][via]."</td>";
										$this->salida.="</tr>";

										$this->salida.="<tr class=\"$estilo\">";
										$this->salida.="  <td align=\"left\" width=\"9%\">Dosis:</td>";
										$e=$vector1[$i][dosis]/floor($vector1[$i][dosis]);
										if($e==1)
										{
											$this->salida.="  <td align=\"left\" width=\"14%\">".floor($vector1[$i][dosis])."  ".$vector1[$i][unidad_dosificacion]."</td>";
										}
										else
										{
											$this->salida.="  <td align=\"left\" width=\"14%\">".$vector1[$i][dosis]."  ".$vector1[$i][unidad_dosificacion]."</td>";
										}


										//ALERTA-------->ojo porque esta llendo a consultar despues de  modificar
										//a la misma opcion y no cinsulta la que es



										$vector_posologia= $this->Consulta_Solicitud_Medicamentos_Posologia($vector1[$i][codigo_producto], $vector1[$i][tipo_opcion_posologia_id], $vector1[$i][evolucion_id]);

//pintar formula para opcion 1
								if($vector1[$i][tipo_opcion_posologia_id]== 1)
								{
									$this->salida.="  <td align=\"left\" width=\"50%\">cada ".$vector_posologia[0][periocidad_id]." ".$vector_posologia[0][tiempo]."</td>";
								}

//pintar formula para opcion 2
								if($vector1[$i][tipo_opcion_posologia_id]== 2)
								{
									$this->salida.="  <td align=\"left\" width=\"50%\">".$vector_posologia[0][descripcion]."</td>";
								}

//pintar formula para opcion 3
								if($vector1[$i][tipo_opcion_posologia_id]== 3)
								{
										$momento = '';
										if($vector_posologia[0][sw_estado_momento]== '1')
										{
											$momento = 'antes de ';
										}
										else
										{
											if($vector_posologia[0][sw_estado_momento]== '2')
											{
												$momento = 'durante ';
											}
											else
											{
												if($vector_posologia[0][sw_estado_momento]== '3')
													{
														$momento = 'despues de ';
													}
											}
										}
										$Cen = $Alm = $Des= '';
										$cont= 0;
										$conector = '  ';
										$conector1 = '  ';
										if($vector_posologia[0][sw_estado_desayuno]== '1')
										{
											$Des = $momento.'el Desayuno';
											$cont++;
										}
										if($vector_posologia[0][sw_estado_almuerzo]== '1')
										{
											$Alm = $momento.'el Almuerzo';
											$cont++;
										}
										if($vector_posologia[0][sw_estado_cena]== '1')
										{
											$Cen = $momento.'la Cena';
											$cont++;
										}
										if ($cont== 2)
										{
											$conector = ' y ';
											$conector1 = '  ';
										}
										if ($cont== 1)
										{
											$conector = '  ';
											$conector1 = '  ';
										}
										if ($cont== 3)
										{
											$conector = ' , ';
											$conector1 = ' y ';
										}
										$this->salida.="  <td align=\"left\" width=\"50%\">".$Des."".$conector."".$Alm."".$conector1."".$Cen."</td>";
								}

//pintar formula para opcion 4
								if($vector1[$i][tipo_opcion_posologia_id]== 4)
								{
									$conector = '  ';
									$frecuencia='';
									$j=0;
									foreach ($vector_posologia as $k => $v)
									{
										if ($j+1 ==sizeof($vector_posologia))
										{
											$conector = '  ';
										}
										else
										{
												if ($j+2 ==sizeof($vector_posologia))
													{
														$conector = ' y ';
													}
												else
													{
														$conector = ' - ';
													}
										}
										$frecuencia = $frecuencia.$k.$conector;
										$j++;
									}
									$this->salida.="  <td align=\"left\" width=\"50%\">a la(s): $frecuencia</td>";
								}

//pintar formula para opcion 5
								if($vector1[$i][tipo_opcion_posologia_id]== 5)
								{
									$this->salida.="  <td align=\"left\" width=\"50%\">".$vector_posologia[0][frecuencia_suministro]."</td>";
								}
								$this->salida.="</tr>";

								$this->salida.="<tr class=\"$estilo\">";
								$this->salida.="  <td align=\"left\" width=\"9%\">Cantidad:</td>";
								$e=$vector1[$i][cantidad]/floor($vector1[$i][cantidad]);
								if ($vector1[$i][contenido_unidad_venta])
								{
									if($e==1)
									{
										$this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".floor($vector1[$i][cantidad])." ".$vector1[$i][descripcion]." por ".$vector1[$i][contenido_unidad_venta]."</td>";
									}
									else
									{
										$this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".$vector1[$i][cantidad]." ".$vector1[$i][descripcion]." por ".$vector1[$i][contenido_unidad_venta]."</td>";
									}
								}
								else
								{
									if($e==1)
									{
										$this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".floor($vector1[$i][cantidad])." ".$vector1[$i][descripcion]."</td>";
									}
									else
									{
										$this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".$vector1[$i][cantidad]." ".$vector1[$i][descripcion]."</td>";
									}
								}
								$this->salida.="</tr>";

								$this->salida.="</table>";
								$this->salida.="</td>";
								$this->salida.="</tr>";

								$this->salida.="<tr class=\"$estilo\">";
								$this->salida.="<td colspan = 4 class=\"$estilo\">";
								$this->salida.="<table>";
								$this->salida.="<tr class=\"$estilo\">";
								$this->salida.="  <td align=\"left\" width=\"4%\">Observacion:</td>";
								$this->salida.="  <td align=\"left\" width=\"69%\">".$vector1[$i][observacion]."</td>";
								$this->salida.="<tr class=\"$estilo\">";

                if($vector1[$i][sw_uso_controlado]==1)
								{
									$this->salida.="<tr class=\"$estilo\">";
									$this->salida.="  <td align=\"left\" colspan = 2 width=\"73%\">MEDICAMENTO DE USO CONTROLADO</td>";
									$this->salida.="<tr class=\"$estilo\">";
                }
                $this->salida.="</table>";

								$this->salida.="</td>";
								$this->salida.="</tr>";

								if($vector1[$i][item] == 'NO POS')
								{
									$this->salida.="<tr class=\"$estilo\">";
									if($vector1[$i][sw_paciente_no_pos] != '1')
									{
										if($vector1[$i][evolucion_id] == $this->evolucion)
										{
												$accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Consultar_Justificacion', 'codigo_p'.$pfj => $vector1[$i][codigo_producto], 'product'.$pfj => $vector1[$i][producto], 'principio_a'.$pfj => $vector1[$i][principio_activo], 'via'.$pfj => $vector1[$i][via],'dosis'.$pfj => $vector1[$i][dosis], 'unidad'.$pfj => $vector1[$i][unidad_dosificacion], 'canti'.$pfj => $vector1[$i][cantidad],'desc'.$pfj => $vector1[$i][descripcion],'contenido_u_v'.$pfj => $vector1[$i][contenido_unidad_venta], 'obs'.$pfj => $vector1[$i][observacion], 'evolucion'.$pfj => $vector1[$i][evolucion_id]));
												$this->salida.="  <td colspan = 4 align=\"center\" width=\"63%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/auditoria.png\" border='0'> VER JUSTIFICACION</a></td>";
										}
										else
										{
												$accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Consultar_Justificacion', 'codigo_p'.$pfj => $vector1[$i][codigo_producto], 'product'.$pfj => $vector1[$i][producto], 'principio_a'.$pfj => $vector1[$i][principio_activo], 'via'.$pfj => $vector1[$i][via],'dosis'.$pfj => $vector1[$i][dosis], 'unidad'.$pfj => $vector1[$i][unidad_dosificacion], 'canti'.$pfj => $vector1[$i][cantidad],'desc'.$pfj => $vector1[$i][descripcion],'contenido_u_v'.$pfj => $vector1[$i][contenido_unidad_venta], 'obs'.$pfj => $vector1[$i][observacion],'evolucion'.$pfj => $vector1[$i][evolucion_id],'consultar_just'.$pfj => 1));
												$this->salida.="  <td colspan = 4 align=\"center\" width=\"63%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/auditoria.png\" border='0'> CONSULTAR JUSTIFICACION</a></td>";
										}
									}
									else
									{
										$this->salida.="  <td class = label_error colspan = 4 align=\"center\" width=\"63%\">MEDICAMENTO NO POS FORMULADO A PETICION DEL PACIENTE</td>";
									}
									$this->salida.="</tr>";
								}

						}
						$this->salida.="</table><br>";
				}
				$this->salida .= "</form>";

//los medicamentos frecuentes por diagnostico
				$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'llenar_solicitud_medicamento'));
				$this->salida .= "<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
				$vectorMF = $this->Medicamentos_Frecuentes_Diagnostico();
				if ($vectorMF)
					{
						$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
							$this->salida.="<tr class=\"modulo_table_title\">";
							$this->salida.="  <td align=\"center\" colspan=\"7\">MEDICAMENTOS EMPLEADOS PARA LOS DIAGNOSTICOS DE ESTA HISTORIA CLINICA</td>";
							$this->salida.="</tr>";

							$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
							$this->salida.="  <td width=\"5%\"></td>";
							$this->salida.="  <td width=\"5%\">CODIGO</td>";
							$this->salida.="  <td width=\"23%\">PRODUCTO</td>";
							$this->salida.="  <td width=\"23%\">PRINCIPIO ACTIVO</td>";
							if ($this->bodega==='')
							{
								$this->salida.="  <td colspan = 2 width=\"15%\">FORMA</td>";
							}
							else
							{
								$this->salida.="  <td width=\"15%\">FORMA</td>";
								$this->salida.="  <td width=\"5%\">EXISTENCIA</td>";
							}
							$this->salida.="  <td width=\"4%\">OPCION</td>";
							$this->salida.="</tr>";
							for($i=0;$i<sizeof($vectorMF);$i++)
							{
									if( $i % 2){ $estilo='modulo_list_claro';}
									else {$estilo='modulo_list_oscuro';}
									$this->salida.="<tr class=\"$estilo\">";
									$this->salida.="  <td align=\"center\" width=\"5%\">".$vectorMF[$i][item]."</td>";
									$this->salida.="  <td align=\"center\" width=\"5%\">".$vectorMF[$i][codigo_producto]."</td>";
									$this->salida.="  <td align=\"left\" width=\"20%\">".$vectorMF[$i][producto]."</td>";
									$this->salida.="  <td align=\"left\" width=\"20%\">".$vectorMF[$i][principio_activo]."</td>";

							if ($this->bodega==='')
								{
									$this->salida.="  <td colspan = 2 align=\"center\" width=\"15%\">".$vectorMF[$i][forma]."</td>";
								}
							else
								{
									$this->salida.="  <td align=\"center\" width=\"15%\">".$vectorMF[$i][forma]."</td>";
									if(!empty($vectorMF[$i][existencia]))
									{
											$this->salida.="  <td align=\"center\" width=\"5%\">".$vectorMF[$i][existencia]."</td>";
									}
									else
									{
											$this->salida.="  <td align=\"center\" width=\"5%\">--</td>";
									}
									//$this->salida.="  <td align=\"center\" width=\"5%\"><input type = checkbox name= 'opE".$pfj."[$i]' value = ".$cargo.",".$vectorE[$i][especialidad]."></td>";
								}
								$this->salida.="  <td align=\"center\" width=\"5%\"><input type = radio name= 'opE$pfj' value = '".$vectorMF[$i][item].",".$vectorMF[$i][codigo_producto].",".$vectorMF[$i][producto].",".$vectorMF[$i][principio_activo].",".$vectorE[$i][concentracion_forma_farmacologica].",".$vectorE[$i][unidad_medida_medicamento_id].",".$vectorE[$i][forma].",".$vectorE[$i][cod_forma_farmacologica]."'></td>";
									$this->salida.="</tr>";
								}

							$this->salida.="<tr class=\"$estilo\">";
							$this->salida .= "<td align=\"right\" colspan=\"7\"><input class=\"input-submit\" name=\"guardar$pfj\" type=\"submit\" value=\"FORMULAR\"></td>";
							$this->salida.="</tr>";
						$this->salida.="</table><br>";
					}
				$this->salida .= "</form>";
				//fin de medicamentos MAS FRECUENTES POR DIAGNMOSTICO
				//lo que inserte
						$accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Medicamentos',
					'Of'.$pfj=>$_REQUEST['Of'.$pfj],'paso1'=>$_REQUEST['paso1'.$pfj],
					'criterio1'.$pfj=>$_REQUEST['criterio1'.$pfj],
					'producto'.$pfj=>$_REQUEST['producto'.$pfj],
					'principio_activo'.$pfj=>$_REQUEST['principio_activo'.$pfj]));

					$this->salida .= "<form name=\"formades$pfj\" action=\"$accion1\" method=\"post\">";
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
					$this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="  <td align=\"center\" colspan=\"7\">ADICION DE MEDICAMENTOS - BUSQUEDA AVANZADA </td>";
					$this->salida.="</tr>";

					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="<td width=\"5%\">TIPO</td>";

					$this->salida.="<td width=\"10%\" align = left >";
					$this->salida.="<select size = 1 name = 'criterio1$pfj'  class =\"select\">";
					$this->salida.="<option value = '001' selected>Todos</option>";
					if (($_REQUEST['criterio1'.$pfj])  == '002')
						{
							$this->salida.="<option value = '002' selected>Frecuentes</option>";
						}
					else
						{
							$this->salida.="<option value = '002' >Frecuentes</option>";
						}
					$this->salida.="</select>";
					$this->salida.="</td>";

					$this->salida.="<td width=\"7%\">PRODUCTO:</td>";
					$this->salida .="<td width=\"23%\" align='center'><input type='text' class='input-text'  size = 22 name = 'producto$pfj'  value =\"".$_REQUEST['producto'.$pfj]."\"    ></td>" ;

					$this->salida.="<td width=\"8%\">PRINCIPIO ACTIVO:</td>";
					$this->salida .="<td width=\"22%\" align='center' ><input type='text' class='input-text' size = 22 name = 'principio_activo$pfj'   value =\"".$_REQUEST['principio_activo'.$pfj]."\"        ></td>" ;

					$this->salida .= "<td  width=\"5%\" align=\"center\"><input class=\"input-submit\" name=\"buscar$pfj\" type=\"submit\" value=\"BUSQUEDA\"></td>";
					$this->salida.="</tr>";
					$this->salida.="</table><br>";

					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
					$this->salida .= $this->SetStyle("MensajeError");
					$this->salida.="</table>";
					$this->salida.="</form>";
//hasta aqui lo que inserte
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
}


//clzc - si - *
 function frmForma_Seleccion_Medicamentos($vectorE)
 {
	 		$pfj=$this->frmPrefijo;
   		$this->salida= ThemeAbrirTablaSubModulo('SOLICITUD DE MEDICAMENTOS');
			$accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Medicamentos',
			'Of'.$pfj=>$_REQUEST['Of'.$pfj],'paso1'=>$_REQUEST['paso1'.$pfj],
			'criterio1'.$pfj=>$_REQUEST['criterio1'.$pfj],
			'producto'.$pfj=>$_REQUEST['producto'.$pfj],
			'principio_activo'.$pfj=>$_REQUEST['principio_activo'.$pfj]));

			$this->salida .= "<form name=\"formades$pfj\" action=\"$accion1\" method=\"post\">";
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"7\">BUSQUEDA AVANZADA </td>";
			$this->salida.="</tr>";

			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td width=\"5%\">TIPO</td>";

			$this->salida.="<td width=\"10%\" align = left >";
			$this->salida.="<select size = 1 name = 'criterio1$pfj'  class =\"select\">";
					$this->salida.="<option value = '001' selected>Todos</option>";
					if (($_REQUEST['criterio1'.$pfj])  == '002')
						{
							$this->salida.="<option value = '002' selected>Frecuentes</option>";
						}
					else
						{
							$this->salida.="<option value = '002' >Frecuentes</option>";
						}
			$this->salida.="</select>";
			$this->salida.="</td>";

			$this->salida.="<td width=\"7%\">PRODUCTO:</td>";
			$this->salida .="<td width=\"23%\" align='center'><input type='text' class='input-text'  size = 22 name = 'producto$pfj'  value =\"".$_REQUEST['producto'.$pfj]."\" ></td>" ;

			$this->salida.="<td width=\"8%\">PRINCIPIO ACTIVO:</td>";
			$this->salida .="<td width=\"22%\" align='center' ><input type='text' class='input-text' size = 22 name = 'principio_activo$pfj'   value =\"".$_REQUEST['principio_activo'.$pfj]."\"></td>" ;

			$this->salida .= "<td  width=\"5%\" align=\"center\"><input class=\"input-submit\" name=\"buscar$pfj\" type=\"submit\" value=\"BUSCAR\"></td>";
			$this->salida.="</tr>";
			$this->salida.="</table><br>";

			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida.="</table>";
			$this->salida.="</form>";

	    $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'llenar_solicitud_medicamento'));
			$this->salida .= "<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
			if ($vectorE)
				{
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
					$this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="  <td align=\"center\" colspan=\"7\">RESULTADO DE LA BUSQUEDA</td>";
					$this->salida.="</tr>";

					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="  <td align=\"center\" width=\"5%\"></td>";
					$this->salida.="  <td align=\"center\" width=\"5%\">CODIGO</td>";
					$this->salida.="  <td align=\"center\" width=\"23%\">PRODUCTO</td>";
					$this->salida.="  <td align=\"center\" width=\"23%\">PRINCIPIO ACTIVO</td>";
					if ($this->bodega==='')
					{
						$this->salida.="  <td colspan = 2 width=\"15%\">FORMA</td>";
					}
					else
					{
						$this->salida.="  <td width=\"15%\">FORMA</td>";
						$this->salida.="  <td width=\"5%\">EXISTENCIA</td>";
					}
					$this->salida.="  <td align=\"center\" width=\"4%\">OPCION</td>";
					$this->salida.="</tr>";
					for($i=0;$i<sizeof($vectorE);$i++)
					{
							if( $i % 2){ $estilo='modulo_list_claro';}
							else {$estilo='modulo_list_oscuro';}
							$this->salida.="<tr class=\"$estilo\">";
							$this->salida.="  <td align=\"left\" width=\"5%\">".$vectorE[$i][item]."</td>";
							$this->salida.="  <td align=\"left\" width=\"5%\">".$vectorE[$i][codigo_producto]."</td>";
							$this->salida.="  <td align=\"left\" width=\"20%\">".$vectorE[$i][producto]."</td>";
							$this->salida.="  <td align=\"left\" width=\"20%\">".$vectorE[$i][principio_activo]."</td>";

							if ($this->bodega==='')
							{
								$this->salida.="  <td colspan = 2align=\"left\" width=\"15%\">".$vectorE[$i][forma]."</td>";
							}
							else
							{
								$this->salida.="  <td align=\"left\" width=\"15%\">".$vectorE[$i][forma]."</td>";
								if(!empty($vectorE[$i][existencia]))
								{
									$this->salida.="  <td align=\"center\" width=\"5%\">".$vectorE[$i][existencia]."</td>";
								}
								else
								{
									$this->salida.="  <td align=\"center\" width=\"5%\">--</td>";
								}
							}
							$valor=urlencode($vectorE[$i][item].'|/'.$vectorE[$i][codigo_producto].'|/'.$vectorE[$i][producto].'|/'.$vectorE[$i][principio_activo].'|/'.$vectorE[$i][concentracion_forma_farmacologica].'|/'.$vectorE[$i][unidad_medida_medicamento_id].'|/'.$vectorE[$i][forma].'|/'.$vectorE[$i][cod_forma_farmacologica].'|/'.$vectorE[$i][unidad_dosificacion]);
							$this->salida.="  <td align=\"center\" width=\"5%\"><input type = radio name= 'opE$pfj' value = $valor></td>";
							$this->salida.="</tr>";
					}
					$this->salida.="<tr class=\"$estilo\">";
					$this->salida .= "<td align=\"right\" colspan=\"7\"><input class=\"input-submit\" name=\"guardar$pfj\" type=\"submit\" value=\"ACEPTAR\"></td>";
					$this->salida.="</tr>";
					$this->salida.="</table><br>";

					$var=$this->RetornarBarraMedicamentos_Avanzada();
					if(!empty($var))
						{
							$this->salida .= "  <br><table border=\"0\" width=\"60%\" align=\"center\">";// class=\"modulo_table\"
							$this->salida .= "  <tr>";
							$this->salida .= "  <td width=\"100%\" align=\"center\">";
							$this->salida .=$var;
							$this->salida .= "  </td>";
							$this->salida .= "  </tr>";
							$this->salida .= "  </table><br>";
						}
					}
      $this->salida .= "</form>";

  //BOTON DEVOLVER
		$accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
		$this->salida .= "<form name=\"forma$pfj\" action=\"$accionV\" method=\"post\">";
		$this->salida .= "<tr><td  colspan = 6 align=\"center\"><br><input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\"></form></td></tr>";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
 }

//*
function frmForma_Llenar_Solicitud_Medicamento($datos_m)
{
		$pfj=$this->frmPrefijo;
		if(empty($datos_m))
		{
			$datos_m=$_SESSION['DATOS_M'.$pfj]['PLAN_TERAPEUTICO'];
		}
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'justificacion_no_pos', 'datos_m'.$pfj=>$_REQUEST['opE'.$pfj]));
		$this->salida .= "<form name=\"forma_med$pfj\" action=\"$accion\" method=\"post\">";

		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";

		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"6\">FORMULACION DEL MEDICAMENTO</td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class=\"modulo_table_title\">";

		$this->salida.="  <td align=\"center\" width=\"5%\"></td>";
		$this->salida.="  <td align=\"center\" width=\"5%\">CODIGO</td>";
		$this->salida.="  <td align=\"center\" width=\"23%\">PRODUCTO</td>";
		$this->salida.="  <td align=\"center\" width=\"23%\">PRINCIPIO ACTIVO</td>";
		$this->salida.="  <td align=\"center\" width=\"23%\">CONCENTRACION</td>";
		$this->salida.="  <td align=\"center\" width=\"15%\">FORMA</td>";
		//$this->salida.="  <td width=\"5%\">EXISTENCIA</td>";
		$this->salida.="</tr>";

		if( $i % 2){ $estilo='modulo_list_claro';}
		else {$estilo='modulo_list_oscuro';}


		$arreglo=explode("|/",$datos_m);

		$this->salida.="  <input type='hidden' name = 'item$pfj'  value = '".$arreglo[0]."'>";
		$this->salida.="  <input type='hidden' name = 'codigo_producto$pfj'  value = '".$arreglo[1]."'>";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";

		$this->salida.="<td align=\"center\" width=\"5%\">".$arreglo[0]."</td>";
		$this->salida.="<td align=\"center\" width=\"5%\">".$arreglo[1]."</td>";
		$this->salida.="<td align=\"center\" width=\"23%\" >".$arreglo[2]."</td>";
		$this->salida.="<td align=\"center\" width=\"23%\" >".$arreglo[3]."</td>";
		$this->salida.="<td align=\"center\" width=\"15%\" >".$arreglo[4]." ".$arreglo[5]."</td>";
		$this->salida.="<td align=\"center\" width=\"15%\" >".$arreglo[6]."</td>";

		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";

//via de administracion
		$this->salida.="<tr class=\"$estilo\">";
		$this->salida.="<td class=".$this->SetStyle("via_administracion")." width=\"20%\"align=\"left\" >VIA DE ADMINISTRACION</td>";
		$via_admon = $this->tipo_via_administracion($arreglo[1]);

//es la unidad de dosificacion que viene $arreglo[8]

if ((sizeof($via_admon)>1))
		{
				$this->salida.="<td width=\"60%\" align = left >";				
				if	(empty($arreglo[8]))
				{
					$EventoOnclick="OnChange='UnidadPorVia(this)'";
				}
				else
				{
					$EventoOnclick="";
				}

				$this->salida.="\n\n<select name = 'via_administracion$pfj'  class =\"select\" $EventoOnclick>";
				$this->salida.="<option value = '-1' selected>-Seleccione-</option>";

				$javita.="<script>\n";
				$javita.="function UnidadPorVia(forma) {\n";
				$javita.="if (forma.value=='-1') {\n";
				$javita.="  document.forma_med$pfj.unidad_dosis$pfj.length=0;\n";
				$javita.="}\n\n";
				for($i=0;$i<sizeof($via_admon);$i++)
				{
						if ((($_REQUEST['via_administracion'.$pfj])  != $via_admon[$i][via_administracion_id]) )
							{
								$this->salida.="<option value = ".$via_admon[$i][via_administracion_id].">".$via_admon[$i][nombre]."</option>";
							}
						else
							{
								$this->salida.="<option value = ".$via_admon[$i][via_administracion_id]." selected >".$via_admon[$i][nombre]."</option>";
							}

							//generar java para el combo de unidades de dosificacion
							if	(empty($arreglo[8]))
									{
											$javita.="if (forma.value=='".$via_admon[$i][via_administracion_id]."') {\n";

											$unidadesViaAdministracion = $this->GetunidadesViaAdministracion($via_admon[$i][via_administracion_id]);

											$javita.="document.forma_med$pfj.unidad_dosis$pfj.length=".count($unidadesViaAdministracion)."\n";

											for($cont=0;$cont<count($unidadesViaAdministracion);$cont++){
													$javita.="document.forma_med$pfj.unidad_dosis$pfj.options[".$cont."]= new Option('".$unidadesViaAdministracion[$cont][unidad_dosificacion]."','".$unidadesViaAdministracion[$cont][unidad_dosificacion]."');\n";
															}
											$javita.="}\n\n";
									}
							//fin javita
				}
				$javita.="}\n\n";
				$javita.="</script>\n";
				$this->salida.="</select>\n\n";
				$this->salida.="</td>";
		}
		else
		{
			if ((sizeof($via_admon)==1))
			{
					$this->salida.="<td width=\"60%\" align = left >";
					$this->salida.="\n\n<select name = 'via_administracion$pfj'  class =\"select\">";
					$this->salida.="<option value = ".$via_admon[0][via_administracion_id]." selected >".$via_admon[0][nombre]."</option>";
					$this->salida.="</select>\n\n";
					$this->salida.="</td>";
			}
			else
			{
					$this->salida.="<td width=\"60%\" align = left >&nbsp;</td>";
			}
		}
		$this->salida.="</tr>";

//-----------------

//Generar Combo de unidades de dosificacion
		$ComboUnidadDosis ="<select size = 1 name = 'unidad_dosis$pfj'  class =\"select\">";
		if	(!empty($arreglo[8]))
		{
					$ComboUnidadDosis.="<option value = '".$arreglo[8]."' selected >".$arreglo[8]."</option>";
		}
		else
		{
			if ((sizeof($via_admon)==1))
			{
				$unidadesViaAdministracion = $this->GetunidadesViaAdministracion($via_admon[0][via_administracion_id]);
				$ComboUnidadDosis.="<option value = '-1' selected >--Seleccione--</option>";
				for($i=0;$i<sizeof($unidadesViaAdministracion);$i++)
				{
					//aqui agreggue este if para que se seleccione la unidad seleccionada y guardada en el request
									if($_REQUEST['unidad_dosis'.$pfj]==$unidadesViaAdministracion[$i][unidad_dosificacion])
									{
										$ComboUnidadDosis.="<option selected value = '".$unidadesViaAdministracion[$i][unidad_dosificacion]."'>".$unidadesViaAdministracion[$i][unidad_dosificacion]."</option>";
									}
									else
									{
										$ComboUnidadDosis.="<option value = '".$unidadesViaAdministracion[$i][unidad_dosificacion]."'>".$unidadesViaAdministracion[$i][unidad_dosificacion]."</option>";
									}
					//fin del if y sigue comentado lo que estaba antes de que se creara el if
									//$ComboUnidadDosis.="<option value = '".$unidadesViaAdministracion[$i][unidad_dosificacion]."'>".$unidadesViaAdministracion[$i][unidad_dosificacion]."</option>";
				}
			}
			if (empty($via_admon))
			{
				$unidadesViaAdministracion = $this->Unidades_Dosificacion();
				$ComboUnidadDosis.="<option value = '-1' selected >--Seleccione--</option>";
				for($i=0;$i<sizeof($unidadesViaAdministracion);$i++)
				{
					//aqui agreggue este if para que se seleccione la unidad guardadad en la bd
							if($_REQUEST['unidad_dosis'.$pfj]==$unidadesViaAdministracion[$i][unidad_dosificacion])
							{
								$ComboUnidadDosis.="<option selected value = '".$unidadesViaAdministracion[$i][unidad_dosificacion]."'>".$unidadesViaAdministracion[$i][unidad_dosificacion]."</option>";
							}
							else
							{
								$ComboUnidadDosis.="<option value = '".$unidadesViaAdministracion[$i][unidad_dosificacion]."'>".$unidadesViaAdministracion[$i][unidad_dosificacion]."</option>";
							}
							//fin del if
				}
			}
		}
		$ComboUnidadDosis.="</select>";
//--------------

//posologia neonatos
/*
		$FechaInicio = $this->datosPaciente[fecha_nacimiento];
		$FechaFin = date("Y-m-d");
		$edad_paciente = CalcularEdad($FechaInicio,$FechaFin);
		if ( $edad_paciente[anos] < ModuloGetVar('','','max_edad_pediatrica'))
			{
				$peso_pac = $this->Peso_Paciente();
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td width=\"20%\"align=\"left\" >POSOLOGIA NEONATOS</td>";
				$this->salida.="<td width=\"60%\" align = left >";
				$this->salida.="<table>";
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td  class=".$this->SetStyle("peso")." width=\"20%\" align = left >PESO</td>";
				$this->salida.="<td colspan = 2 width=\"40%\" align='left' ><input type='text' class='input-text' size = 10 name = 'peso$pfj'   value = \"".$peso_pac[peso]."\">  Kg</td>" ;
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td  width=\"20%\" align=\"left\" >DOSIS ORDENADA</td>";
				$this->salida.="<td width=\"15%\" align=\"left\" ><input type='text' class='input-text' size = 10 name = 'dosis_ordenada$pfj'   value =\"".$_REQUEST['dosis_ordenada'.$pfj]."\">  mg/Kg por: </td>" ;
				$this->salida.="<td width=\"25%\" align=\"left\" >";
				$this->salida.="<select size = 1 name = 'criterio_dosis$pfj'  class =\"select\">";
				$this->salida.="<option value = 'dosis' selected>Dosis</option>";
				if (($_REQUEST['criterio_dosis'.$pfj])  == 'Dia')
					{
						$this->salida.="<option value = '002' selected>Dia</option>";
					}
				else
					{
						$this->salida.="<option value = '002' >Dia</option>";
					}
				$this->salida.="</select>";
				$this->salida.="</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida .= "<td width=\"20%\"  align=\"left\"><input type='button' name='calcular_dosis$pfj' value='Calcular Dosis' onclick='Calcular_Dosis(this.form)'></td>";
				$this->salida.="<td colspan=2 width=\"40%\" align=\"left\" ><input type='text' class='input-text' readonly size = 10 name = 'dosis_total$pfj'>  mg</td>" ;
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td class=".$this->SetStyle("cantidad")." width=\"50%\" align = left >CANTIDAD</td>";
				$this->salida.="<td  width=\"65%\" align='left' ><input type='text' class='input-text' size = 15 name = 'cantidad$pfj'   value =\"".$_REQUEST['cantidad'.$pfj]."\"></td>" ;
				$this->salida.="<td  width=\"50%\" align = left >UNIDAD</td>";
				$this->salida.="<td width=\"65%\" align='left' ><input type='text' class='input-text' readonly size = 15 name = 'unidad$pfj'   value =\"".$_REQUEST['unidad'.$pfj]."\"></td>" ;
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";

				//funcion que calcula la dosis
				$this->salida .= "<script>\n";
				$this->salida .= "function Calcular_Dosis(formulario){\n";
				$this->salida .= "var a;\n";
				$this->salida .= "var b;\n";
				$this->salida .= "a=formulario.peso$pfj.value;\n";
				$this->salida .= "b=formulario.dosis_ordenada$pfj.value;\n";
				$this->salida .= "c=a*b;\n";
				$this->salida .= "if(isNaN(c)){\n";
				$this->salida .= "alert('valores no validos');\n";
				$this->salida .= "formulario.dosis_total$pfj.value='';\n";
				$this->salida .= "if(isNaN(b)){\n";
				$this->salida .= "formulario.dosis_ordenada$pfj.value='';\n";
				$this->salida .= "formulario.dosis_ordenada$pfj.focus();\n";
				$this->salida .= "}\n";

				$this->salida .= "if(isNaN(a)){\n";
				$this->salida .= "formulario.peso$pfj.value='';\n";
				$this->salida .= "formulario.peso$pfj.focus();\n";
				$this->salida .= "}\n";

				$this->salida .= "} else {\n";
				$this->salida .= "formulario.dosis_total$pfj.value=c;\n";
				$this->salida .= "}\n";
				$this->salida .= "}\n";
				$this->salida .= "</script>\n";
				//fin de la funcion
					}
*/
//posologia-dosis
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td width=\"20%\"align=\"left\" >DOSIS</td>";
				$this->salida.="<td width=\"60%\" align = left >";
				$this->salida.="<table>";
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td  width=\"10%\" class=".$this->SetStyle("dosis")." align = left >DOSIS</td>";
				$this->salida.="<td width=\"15%\" align='left' ><input type='text' class='input-text' size = 15 name = 'dosis$pfj'   value =\"".$_REQUEST['dosis'.$pfj]."\"></td>" ;

//unidades de dosificacion
				$this->salida.="<td width=\"35%\" class=".$this->SetStyle("unidad_dosis")." align = left >";
				//si no trae unidad de dosificacion segun la forma del producto pinta combo de vias interactivo
				if	(empty($arreglo[8]))
				{
					$this->salida.=$javita;
					//este es el if nuevo que coloque para cargar unidades
								if ((sizeof($via_admon)>1))
								{
									$ComboUnidadDosis ="<select size = 1 name = 'unidad_dosis$pfj'  class =\"select\">";
									$unidadesViaAdministracion = $this->GetunidadesViaAdministracion($_REQUEST['via_administracion'.$pfj]);
									$ComboUnidadDosis.="<option value = '-1' selected >--Seleccione--</option>";
									for($i=0;$i<sizeof($unidadesViaAdministracion);$i++)
									{
										if($_REQUEST['unidad_dosis'.$pfj]==$unidadesViaAdministracion[$i][unidad_dosificacion])
										{
											$ComboUnidadDosis.="<option selected value = '".$unidadesViaAdministracion[$i][unidad_dosificacion]."'>".$unidadesViaAdministracion[$i][unidad_dosificacion]."</option>";
										}
										else
										{
											$ComboUnidadDosis.="<option value = '".$unidadesViaAdministracion[$i][unidad_dosificacion]."'>".$unidadesViaAdministracion[$i][unidad_dosificacion]."</option>";
										}
									}
									$ComboUnidadDosis.="</select>";
								}
							//fin del evento nuevo
				}
				$this->salida.="$ComboUnidadDosis";
				$this->salida.="</td>";
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";

//horario
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td width=\"20%\" class=".$this->SetStyle("frecuencia")." align=\"left\" >FRECUENCIA</td>";
				$this->salida.="<td width=\"60%\" align = left >";
				$this->salida.="<table border = 0 >";

//opcion 1
				$this->salida.="<tr class=\"modulo_list_claro\">";

				if ($_REQUEST['opcion'.$pfj] != '1')
						{
							$this->salida.="<td width=\"10%\"  class=".$this->SetStyle("opcion1")." align=\"left\" ><input type = radio name= 'opcion$pfj' value = 1>OPCION 1</td>";
						}
				else
						{
							$this->salida.="<td width=\"10%\"  class=".$this->SetStyle("opcion1")." align=\"left\" ><input type = radio checked name= 'opcion$pfj' value = 1>OPCION 1</td>";
						}

				$this->salida.="<td width=\"50%\"align=\"left\" >";
				$this->salida.="<table>";
				$this->salida.="<tr class=\"modulo_list_claro\">";
				$this->salida.="<td width=\"10%\" align = left >CADA</td>";
				$cada_periocidad = $this->Cargar_Periocidad();
				$this->salida.="<td width=\"10%\" align = left >";
				$this->salida.="<select size = 1 name = 'periocidad$pfj'  class =\"select\">";
				$this->salida.="<option value = '-1' selected>-Seleccione-</option>";
				for($i=0;$i<sizeof($cada_periocidad);$i++)
				{
						if ((($_REQUEST['periocidad'.$pfj])  != $cada_periocidad[$i][periocidad_id]) )
							{
								$this->salida.="<option value = ".$cada_periocidad[$i][periocidad_id].">".$cada_periocidad[$i][periocidad_id]."</option>";
							}
						else
							{
								$this->salida.="<option value = ".$cada_periocidad[$i][periocidad_id]." selected >".$cada_periocidad[$i][periocidad_id]."</option>";
							}
				}
				$this->salida.="</select>";
				$this->salida.="</td>";
				$this->salida.="<td width=\"30%\" align = 'left' >";
				$this->salida.="<select size = 1 name = 'tiempo$pfj'  class =\"select\">";
				$this->salida.="<option value = '-1' selected>-Seleccione-</option>";
				//opcion de minutos
				if (($_REQUEST['tiempo'.$pfj])  == 'Min')
					{
						$this->salida.="<option value = 'Min' selected>Min</option>";
					}
				else
					{
						$this->salida.="<option value = 'Min' >Min</option>";
					}
				//opcion de horas
				if (($_REQUEST['tiempo'.$pfj])  == 'Hora(s)')
					{
						$this->salida.="<option value = 'Hora(s)' selected>Hora(s)</option>";
					}
				else
					{
						$this->salida.="<option value = 'Hora(s)' >Hora(s)</option>";
					}
				//opcion de dias
				if (($_REQUEST['tiempo'.$pfj])  == 'Dia(s)')
					{
						$this->salida.="<option value = 'Dia(s)' selected>Dia(s)</option>";
					}
				else
					{
							$this->salida.="<option value = 'Dia(s)' >Dia(s)</option>";
					}
						//opcion de semanas
				if (($_REQUEST['tiempo'.$pfj])  == 'Semana(s)')
					{
							$this->salida.="<option value = 'Semana(s)' selected>Semana(s)</option>";
					}
				else
					{
							$this->salida.="<option value = 'Semana(s)' >Semana(s)</option>";
					}
				$this->salida.="</select>";
				$this->salida.="</td>";
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";

//OPCION 2
				$this->salida.="<tr class=\"modulo_list_claro\">";
				if ($_REQUEST['opcion'.$pfj] != '2')
						{
							$this->salida.="<td width=\"10%\" class=".$this->SetStyle("opcion2")." align=\"left\" ><input type = radio name= 'opcion$pfj' value = 2>OPCION 2</td>";
						}
				else
						{
							$this->salida.="<td width=\"10%\" class=".$this->SetStyle("opcion2")." align=\"left\" ><input type = radio checked name= 'opcion$pfj' value = 2>OPCION 2</td>";
						}
				$this->salida.="<td width=\"50%\"align=\"left\" >";
				$this->salida.="<table>";
				$this->salida.="<tr class=\"modulo_list_claro\">";
				$horario = $this->horario();
				$this->salida.="<td class=".$this->SetStyle("durante")." width=\"20%\"align=\"left\" >&nbsp;</td>";
				$this->salida.="<td width=\"60%\" align = left >";
				$this->salida.="<select size = 1 name = 'duracion$pfj'  class =\"select\">";
				$this->salida.="<option value = -1 selected>-Seleccione-</option>";


				for($i=0;$i<sizeof($horario);$i++)
				{
					if ($_REQUEST['duracion'.$pfj]==trim($horario[$i][duracion_id]))
						{
							$this->salida.="<option value = ".$horario[$i][duracion_id]." selected >".$horario[$i][descripcion]."</option>";
						}
					else
						{
							$this->salida.="<option value = ".$horario[$i][duracion_id].">".$horario[$i][descripcion]."</option>";
						}
				}
				$this->salida.="</select>";
				$this->salida.="</td>";
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";

//opcion 3
				$this->salida.="<tr class=\"modulo_list_claro\">";
				if ($_REQUEST['opcion'.$pfj] != '3')
						{
							$this->salida.="<td width=\"10%\" class=".$this->SetStyle("opcion3")." align=\"left\" ><input type = radio name= 'opcion$pfj' value = 3>OPCION 3</td>";
						}
				else
						{
							$this->salida.="<td width=\"10%\" class=".$this->SetStyle("opcion3")." align=\"left\" ><input type = radio checked name= 'opcion$pfj' value = 3>OPCION 3</td>";
						}
				$this->salida.="<td width=\"50%\"align=\"left\" >";
				$this->salida.="<table>";
				$this->salida.="<tr class=\"modulo_list_claro\">";
				if ($_REQUEST['momento'.$pfj] != '1')
						{
							$this->salida.="<td width=\"15%\" align = left ><input type = radio name= 'momento$pfj' value = '1'>ANTES</td>";
						}
				else
						{
							$this->salida.="<td width=\"15%\" align = left ><input type = radio name= 'momento$pfj' checked value = '1'>ANTES</td>";
						}
				if ($_REQUEST['momento'.$pfj] != '2')
						{
							$this->salida.="<td width=\"15%\" align = left ><input type = radio name= 'momento$pfj' value = '2'>DURANTE</td>";
						}
				else
						{
							$this->salida.="<td width=\"15%\" align = left ><input type = radio name= 'momento$pfj' checked value = '2'>DURANTE</td>";
						}
				if ($_REQUEST['momento'.$pfj] != '3')
						{
							$this->salida.="<td width=\"20%\" align = left ><input type = radio name= 'momento$pfj' value = '3'>DESPUES</td>";
						}
				else
						{
							$this->salida.="<td width=\"20%\" align = left ><input type = radio name= 'momento$pfj' checked value = '3'>DESPUES</td>";
						}
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"modulo_list_claro\">";
				if ($_REQUEST['desayuno'.$pfj] != '1')
					{
						$this->salida.="<td  colspan = 3 width=\"50%\" align = left ><input type = checkbox name= 'desayuno$pfj' value = '1'>DESAYUNO</td>";
					}
				else
					{
						$this->salida.="<td  colspan = 3 width=\"50%\" align = left ><input type = checkbox name= 'desayuno$pfj' checked value = '1'>DESAYUNO</td>";
					}
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"modulo_list_claro\">";
				if ($_REQUEST['almuerzo'.$pfj] != '1')
					{
						$this->salida.="<td  colspan = 3 width=\"50%\" align = left ><input type = checkbox name= 'almuerzo$pfj' value = '1'>ALMUERZO</td>";
					}
				else
					{
						$this->salida.="<td  colspan = 3 width=\"50%\" align = left ><input type = checkbox name= 'almuerzo$pfj' checked value = '1'>ALMUERZO</td>";
					}
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"modulo_list_claro\">";
				if ($_REQUEST['cena'.$pfj] != '1')
					{
						$this->salida.="<td  colspan = 3 width=\"50%\" align = left ><input type = checkbox name= 'cena$pfj' value = '1'>CENA</td>";
					}
				else
					{
						$this->salida.="<td  colspan = 3 width=\"50%\" align = left ><input type = checkbox name= 'cena$pfj' checked value = '1'>CENA</td>";
					}
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";

//OPCION 4
				$this->salida.="<tr class=\"modulo_list_claro\">";
				if ($_REQUEST['opcion'.$pfj] != '4')
					{
						$this->salida.="<td width=\"10%\" class=".$this->SetStyle("opcion4")." align=\"left\" ><input type = radio name= 'opcion$pfj' value = 4>OPCION 4</td>";
					}
				else
					{
						$this->salida.="<td width=\"10%\" class=".$this->SetStyle("opcion4")." align=\"left\" ><input type = radio checked name= 'opcion$pfj' value = 4>OPCION 4</td>";
					}
				$this->salida.="<td width=\"50%\"align=\"left\" >";
				$this->salida.="<table>";
				$this->salida.="<tr class=\"modulo_list_claro\">";
				$this->salida.="<td colspan = 8 width=\"50%\" align = left >HORA ESPECIFICA</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"modulo_list_claro\">";

				$hora_especifica = $_REQUEST['opH'.$pfj];
				if (($hora_especifica[6])  != '06 am')
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[6]' value = '06 am'>06</td>";
						}
				else
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox checked name= 'opH".$pfj."[6]' value = '06 am'>06</td>";
						}

				if ((($hora_especifica[9])  != '09 am'))
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[9]' value = '09 am'>09</td>";
						}
				else
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox checked name= 'opH".$pfj."[9]' value = '09 am'>09</td>";
						}

				if ((($hora_especifica[12])  != '12 pm'))
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[12]' value = '12 pm'>12</td>";
						}
				else
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox checked name= 'opH".$pfj."[12]' value = '12 pm'>12</td>";
						}

				if ((($hora_especifica[15])  != '03 pm'))
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[15]' value = '03 pm'>15</td>";
						}
				else
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox checked name= 'opH".$pfj."[15]' value = '03 pm'>15</td>";
						}

				if ((($hora_especifica[18])  != '06 pm'))
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[18]' value = '06 pm'>18</td>";
						}
				else
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox checked name= 'opH".$pfj."[18]' value = '06 pm'>18</td>";
						}

				if ((($hora_especifica[21])  != '09 pm'))
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[21]' value = '09 pm'>21</td>";
						}
				else
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox checked name= 'opH".$pfj."[21]' value = '09 pm'>21</td>";
						}

				if ((($hora_especifica[24])  != '00 am'))
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[24]' value = '00 am'>24</td>";
						}
				else
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[24]' value = '00 am'>24</td>";
						}

				if ((($hora_especifica[3])  != '03 am'))
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"15%\" align = left ><input type = checkbox name= 'opH".$pfj."[3]' value = '03 am'>03</td>";
						}
				else
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"15%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[3]' value = '03 am'>03</td>";
						}

				$this->salida.="</tr>";

				$this->salida.="<tr class=\"modulo_list_claro\">";
				if ((($hora_especifica[7])  != '07 am'))
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[7]' value = '07 am'>07</td>";
						}
				else
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[7]' value = '07 am'>07</td>";
						}

				if ((($hora_especifica[10])  != '10 am'))
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[10]' value = '10 am'>10</td>";
						}
				else
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[10]' value = '10 am'>10</td>";
						}

				if ((($hora_especifica[13])  != '01 pm'))
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[13]' value = '01 pm'>13</td>";
						}
				else
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[13]' value = '01 pm'>13</td>";
						}

				if ((($hora_especifica[16])  != '04 pm'))
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[16]' value = '04 pm'>16</td>";
						}
				else
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[16]' value = '04 pm'>16</td>";
						}

				if ((($hora_especifica[19])  != '07 pm'))
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[19]' value = '07 pm'>19</td>";
						}
				else
						{
						$this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[19]' value = '07 pm'>19</td>";
						}

				if ((($hora_especifica[22])  != '10 pm'))
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[22]' value = '10 pm'>22</td>";
						}
				else
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[22]' value = '10 pm'>22</td>";
						}

				if ((($hora_especifica[1])  != '01 am'))
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[1]' value = '01 am'>01</td>";
						}
				else
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[1]' value = '01 am'>01</td>";
						}

				if ((($hora_especifica[4])  != '04 am'))
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"15%\" align = left ><input type = checkbox name= 'opH".$pfj."[4]' value = '04 am'>04</td>";
						}
				else
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"15%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[4]' value = '04 am'>04</td>";
						}

				$this->salida.="</tr>";
				$this->salida.="<tr class=\"modulo_list_claro\">";
				if ((($hora_especifica[8])  != '08 am'))
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[8]' value = '08 am'>08</td>";
						}
				else
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[8]' value = '08 am'>08</td>";
						}

				if ((($hora_especifica[11])  != '11 am'))
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[11]' value = '11 am'>11</td>";
						}
				else
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[11]' value = '11 am'>11</td>";
						}

				if ((($hora_especifica[14])  != '02 pm'))
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[14]' value = '02 pm'>14</td>";
						}
				else
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[14]' value = '02 pm'>14</td>";
						}

				if ((($hora_especifica[17])  != '05 pm'))
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[17]' value = '05 pm'>17</td>";
						}
				else
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[17]' value = '05 pm'>17</td>";
						}

				if ((($hora_especifica[20])  != '08 pm'))
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[20]' value = '08 pm'>20</td>";
						}
				else
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[20]' value = '08 pm'>20</td>";
						}

				if ((($hora_especifica[23])  != '11 pm'))
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[23]' value = '11 pm'>23</td>";
						}
				else
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[23]' value = '11 pm'>23</td>";
						}

				if ((($hora_especifica[2])  != '02 am'))
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[2]' value = '02 am'>02</td>";
						}
				else
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[2]' value = '02 am'>02</td>";
						}

				if ((($hora_especifica[5])  != '05 am'))
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"15%\" align = left ><input type = checkbox name= 'opH".$pfj."[5]' value = '05 am'>05</td>";
						}
				else
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"15%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[5]' value = '05 am'>05</td>";
						}
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";

//OPCION 5
				$this->salida.="<tr class=\"modulo_list_claro\">";
				if ($_REQUEST['opcion'.$pfj] != '5')
				{
					$this->salida.="<td width=\"10%\" class=".$this->SetStyle("opcion5")." align=\"left\" ><input type = radio name= 'opcion$pfj' value = 5>OPCION 5</td>";
				}
				else
				{
					$this->salida.="<td width=\"10%\" class=".$this->SetStyle("opcion5")." align=\"left\" ><input type = radio checked name= 'opcion$pfj' value = 5>OPCION 5</td>";
				}
				$this->salida.="<td width=\"50%\"align=\"left\" >";
				$this->salida.="<table>";
				$this->salida.="<tr class=\"modulo_list_claro\">";
				$this->salida.="<td  colspan = 3 width=\"50%\" align = left >DESCRIBA LA FRECUENCIA PARA EL SUMINISTRO DEL MEDICAMENTO</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"modulo_list_claro\">";
				if (($_REQUEST['frecuencia_suministro'.$pfj])  == '')
					{
						$this->salida.="<td colspan = 3 width=\"50%\" align='left' ><textarea style = \"width:80%\" class='textarea' name = 'frecuencia_suministro$pfj' cols = 60 rows = 5></textarea></td>" ;

					}
				else
					{
						$this->salida.="<td colspan = 3 width=\"50%\" align='left' ><textarea style = \"width:80%\" class='textarea' name = 'frecuencia_suministro$pfj' cols = 60 rows = 5>".$_REQUEST['frecuencia_suministro'.$pfj]."</textarea></td>" ;
					}
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";

				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";

//cantidad
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td width=\"20%\"align=\"left\" >CANTIDAD</td>";
				$this->salida.="<td width=\"60%\" align = left >";
				$this->salida.="<table>";
				$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="<td class=".$this->SetStyle("cantidad")." width=\"5%\" align = left >CANTIDAD</td>";
					$this->salida.="<td  width=\"5%\" align='left' ><input type='text' class='input-text' size = 5 name = 'cantidad$pfj'   value =\"".$_REQUEST['cantidad'.$pfj]."\"></td>" ;
					$unidad_venta = $this->Unidad_Venta($arreglo[1]);
					$frase = ' ';
					if ($unidad_venta[contenido_unidad_venta]!='')
					{
							$frase = ' por ';
					}
					$this->salida.="<td width=\"30%\" align='left' ><input type='text' class='input-text' readonly size = 30 name = 'unidad$pfj'   value = '".$unidad_venta[descripcion]."".$frase."".$unidad_venta[contenido_unidad_venta]."'></td>" ;
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";
//fin de cantidad

				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td width=\"15%\" align=\"left\" >OBSERVACIONES E INDICACION DE SUMINISTRO</td>";

				if (($_REQUEST['observacion'.$pfj])  == '')
				{
					$this->salida.="<td width=\"65%\"align='center'><textarea style = \"width:80%\" class='textarea' name = 'observacion$pfj' cols = 60 rows = 5>$observacion</textarea></td>" ;
				}
				else
				{
					$this->salida.="<td width=\"50%\"align='center'><textarea style = \"width:80%\" class='textarea' name = 'observacion$pfj' cols = 60 rows = 5>".$_REQUEST['observacion'.$pfj]."</textarea></td>" ;
				}

				$this->salida.="</tr>";
				if($arreglo[0] == 'NO POS')
				{
					$this->salida.="<tr class=\"$estilo\">";
					if ($_REQUEST['no_pos_paciente'.$pfj]  == '1')
					{
						$this->salida.="  <td class = label_error colspan = 2 align=\"center\" width=\"5%\"><input type = \"checkbox\" name= 'no_pos_paciente$pfj' checked value = 1>FORMULACION NO POS A PETICION DEL PACIENTE</td>";
					}
					else
					{
						$this->salida.="  <td class = label_error colspan = 2 align=\"center\" width=\"5%\"><input type = \"checkbox\" name= 'no_pos_paciente$pfj' value = 1 >FORMULACION NO POS A PETICION DEL PACIENTE</td>";
					}
					$this->salida.="</tr>";
				}


				$this->salida.="</table><br>";
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"0\"><tr>";
				$this->salida .= "<td   align=\"center\"><input class=\"input-submit\" name= 'guardar_formula$pfj' type=\"submit\" value=\"GUARDAR FORMULA\"></td>";

				$this->salida .= "</form>";
				$accion3=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false, array('accion'.$pfj=>''));
				$this->salida .= "<form name=\"forma\" action=\"$accion3\" method=\"post\">";
				$this->salida .= "<td   align=\"center\"><input class=\"input-submit\" name= 'cancelar$pfj' type=\"submit\" value=\"CANCELAR\"></form></td>";
				$this->salida.="</tr></table>";
 return true;
}
//*
function frmFormaDiagnosticos($vectorD)
	{
		$pfj=$this->frmPrefijo;
		$this->salida= ThemeAbrirTablaSubModulo('DIAGNOSTICOS PARA LA JUSTIFICACION DEL MEDICAMENTO');
		$accionD=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Diagnosticos', 'Of'.$pfj=>$_REQUEST['Of'.$pfj],'paso1'=>$_REQUEST['paso1'.$pfj], 'codigo'.$pfj=>$_REQUEST['codigo'.$pfj], 'diagnostico'.$pfj=>$_REQUEST['diagnostico'.$pfj]));
		$this->salida .= "<form name=\"formades$pfj\" action=\"$accionD\" method=\"post\">";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"75%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"5\">BUSQUEDA AVANZADA DE DIAGNOSTICOS </td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td width=\"4%\">CODIGO:</td>";
		$this->salida .="<td width=\"5%\" align='center'><input type='text' class='input-text' size = 6 maxlength = 6	name = 'codigo$pfj'></td>" ;
		$this->salida.="<td width=\"9%\">DIAGNOSTICO:</td>";
		$this->salida .="<td width=\"55%\" align='center'><input type='text' size =50 class='input-text' 	name = 'diagnostico$pfj'   value =\"".$_REQUEST['diagnostico'.$pfj]."\"        ></td>" ;
		$this->salida .= "<td  width=\"7%\" align=\"center\"><input class=\"input-submit\" name=\"buscar$pfj\" type=\"submit\" value=\"BUSCAR\"></td>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";
		$this->salida.="</form>";
		$accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar_varios_diagnosticos'));
		$this->salida .= "<form name=\"formades$pfj\" action=\"$accionI\" method=\"post\">";
		if ($vectorD)
		{
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"3\">RESULTADO DE LA BUSQUEDA</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td width=\"10%\">CODIGO</td>";
			$this->salida.="  <td width=\"65%\">DIAGNOSTICO</td>";
			$this->salida.="  <td width=\"5%\">OPCION</td>";
			$this->salida.="</tr>";
			for($i=0;$i<sizeof($vectorD);$i++)
			{
				$codigo          = $vectorD[$i][diagnostico_id];
				$diagnostico    = $vectorD[$i][diagnostico_nombre];
				if( $i % 2){$estilo='modulo_list_claro';}
				else {$estilo='modulo_list_oscuro';}
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="  <td align=\"center\" width=\"10%\">$codigo</td>";
				$this->salida.="  <td align=\"left\" width=\"65%\">$diagnostico</td>";
				$this->salida.="  <td align=\"center\" width=\"5%\"><input type = checkbox name= 'opD".$pfj."[$i]' value = '".$codigo.",".$diagnostico."'></td>";
				$this->salida.="</tr>";
			}
			$this->salida.="<tr class=\"$estilo\">";
			$this->salida .= "<td align=\"right\" colspan=\"3\"><input class=\"input-submit\" name=\"guardardiagnostico$pfj\" type=\"submit\" value=\"GUARDAR\"></td>";
			$this->salida.="</tr>";
			$this->salida.="</table><br>";
			$var=$this->RetornarBarraDiagnosticos_Avanzada();
			if(!empty($var))
			{
				$this->salida .= "  <br><table border=\"0\" width=\"60%\" align=\"center\">";// class=\"modulo_table\"
				$this->salida .= "  <tr>";
				$this->salida .= "  <td width=\"100%\" align=\"center\">";
				$this->salida .=$var;
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$this->salida .= "  </table><br>";
			}
		}
		$this->salida .= "</form>";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}

	//cor - jea - ads - *
	function RetornarBarraDiagnosticos_Avanzada()//Barra paginadora
	{
		$pfj=$this->frmPrefijo;
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso1'.$pfj];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Diagnosticos',
		'conteo'.$pfj=>$this->conteo,'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
		'codigo'.$pfj=>$_REQUEST['codigo'.$pfj],
		'diagnostico'.$pfj=>$_REQUEST['diagnostico'.$pfj]));

		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset(1)."&paso1$pfj=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso-1)."&paso1$pfj=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($i)."&paso1$pfj=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso+1)."&paso1$pfj=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($numpasos)."&paso1$pfj=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($i)."&paso1$pfj=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso+1)."&paso1$pfj=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($numpasos)."&paso1$pfj=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
		if(($_REQUEST['Of'.$pfj])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		else
		{
			if($numpasos>10)
			{
				$valor=10+5;
			}
			else
			{
				$valor=$numpasos+5;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		return $salida;

	}

	//*
function Consultar_Justificacion_Medicamentos_No_Pos()
{        
				//if comprobar_especialista == 1 {}

				$pfj=$this->frmPrefijo;

				if ($_REQUEST['consultar_just'.$pfj]==1)
				{
					$this->salida= ThemeAbrirTablaSubModulo('CONSULTA DE LA JUSTIFICACION');
				}
				else
				{
					$this->salida= ThemeAbrirTablaSubModulo('MODIFICACION DE LA JUSTIFICACION');
				}

				$vector_justificacion = $this->Consulta_Datos_Justificacion($_SESSION['MEDICAMENTOSM'.$pfj][codigo_producto], $_SESSION['MEDICAMENTOSM'.$pfj][evolucion]);
				if(empty($_SESSION['DIAGNOSTICOSM'.$pfj]))
				{
					$vector_diagnosticos = $this->Consulta_Diagnosticos_Justificacion($vector_justificacion[0][hc_justificaciones_no_pos_amb]);
				}
				if($vector_justificacion[0][sw_existe_alternativa_pos]==1)
				{
					$vector_alternartiva = $this->Consulta_Alternativas_Pos($vector_justificacion[0][hc_justificaciones_no_pos_amb]);
				}

				$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'modificar_justificacion_no_pos', 'hc_justificaciones_no_pos_amb'.$pfj =>$vector_justificacion[0][hc_justificaciones_no_pos_amb]));
				$this->salida .= "<form name=\"formamodjus$pfj\" action=\"$accion\" method=\"post\">";

    		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida.="</table>";

				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
    		$this->salida.="<tr class=\"modulo_table_list_title\">";
				if ($_REQUEST['consultar_just'.$pfj]==1)
				{
					$this->salida.="  <td align=\"center\" colspan=\"5\">CONSULTA DE LA JUSTIFICACION DE MEDICAMENTOS NO POS</td>";
				}
				else
				{
					$this->salida.="  <td align=\"center\" colspan=\"5\">MODIFICACION DE LA JUSTIFICACION DE MEDICAMENTOS NO POS</td>";
				}
				$this->salida.="</tr>";

    if( $i % 2){ $estilo='modulo_list_claro';}
				else {$estilo='modulo_list_oscuro';}

//datos del medicamento
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >DATOS DEL MEDICAMENTO</td>";
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="  <td align=\"center\" width=\"5%\">CODIGO</td>";
				$this->salida.="  <td align=\"center\" width=\"20%\">PRODUCTO</td>";
				$this->salida.="  <td align=\"center\" width=\"20%\">PRINCIPIO ACTIVO</td>";
				$this->salida.="  <td align=\"center\" width=\"20%\">CONCENTRACION</td>";
				$this->salida.="  <td align=\"center\" width=\"15%\">FORMA</td>";
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td align=\"center\" width=\"5%\">".$vector_justificacion[0][codigo_producto]."</td>";
				$this->salida.="<td align=\"center\" width=\"20%\" >".$_SESSION['MEDICAMENTOSM'.$pfj][producto]."</td>";
				$this->salida.="<td align=\"center\" width=\"20%\" >".$_SESSION['MEDICAMENTOSM'.$pfj][principio_activo]."</td>";
				$this->salida.="<td align=\"center\" width=\"20%\" >".$vector_justificacion[0][concentracion_forma_farmacologica]." ".$vector_justificacion[0][unidad_medida_medicamento_id]."</td>";
				$this->salida.="<td align=\"center\" width=\"15%\" >".$vector_justificacion[0][forma]."</td>";
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan = 5>";
				$this->salida.="<table>";
				$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="  <td colspan = 2 align=\"left\" width=\"80%\">VIA DE ADMINISTRACION: ".$_SESSION['MEDICAMENTOSM'.$pfj][via]."</td>";
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="  <td align=\"left\" width=\"20%\">DOSIS:</td>";
					$e=$_SESSION['MEDICAMENTOSM'.$pfj][dosis]/(floor($_SESSION['MEDICAMENTOSM'.$pfj][dosis]));
					if($e==1)
					{
						$this->salida.="  <td align=\"left\" width=\"60%\">".floor($_SESSION['MEDICAMENTOSM'.$pfj][dosis])."  ".$_SESSION['MEDICAMENTOSM'.$pfj][unidad_dosificacion]."</td>";
					}
					else
					{
						$this->salida.="  <td align=\"left\" width=\"60%\">".$_SESSION['MEDICAMENTOSM'.$pfj][dosis]."  ".$_SESSION['MEDICAMENTOSM'.$pfj][unidad_dosificacion]."</td>";
					}
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="  <td align=\"left\" width=\"20%\">CANTIDAD:</td>";
					$e=($_SESSION['MEDICAMENTOSM'.$pfj][cantidad])/(floor($_SESSION['MEDICAMENTOSM'.$pfj][cantidad]));
						if ($vector1[$i][contenido_unidad_venta])
					{
						if($e==1)
						{
							$this->salida.="  <td align=\"left\" width=\"60%\">".floor($_SESSION['MEDICAMENTOSM'.$pfj][cantidad])." ".$_SESSION['MEDICAMENTOSM'.$pfj][descripcion]." por ".$_SESSION['MEDICAMENTOSM'.$pfj][contenido_unidad_venta]."</td>";
						}
						else
						{
							$this->salida.="  <td align=\"left\" width=\"60%\">".$_SESSION['MEDICAMENTOSM'.$pfj][cantidad]." ".$_SESSION['MEDICAMENTOSM'.$pfj][descripcion]." por ".$_SESSION['MEDICAMENTOSM'.$pfj][contenido_unidad_venta]."</td>";
						}
					}
					else
					{
						if($e==1)
						{
							$this->salida.="  <td align=\"left\" width=\"60%\">".floor($_SESSION['MEDICAMENTOSM'.$pfj][cantidad])." ".$_SESSION['MEDICAMENTOSM'.$pfj][descripcion]."</td>";
						}
						else
						{
							$this->salida.="  <td align=\"left\" width=\"60%\">".$_SESSION['MEDICAMENTOSM'.$pfj][cantidad]." ".$_SESSION['MEDICAMENTOSM'.$pfj][descripcion]."</td>";
						}
					}
				$this->salida.="</tr>";
				$this->salida.="</td>";
				$this->salida.="</table>";

				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan = 5>";
				$this->salida.="<table>";
    		$this->salida.="<tr class=\"$estilo\">";
								$this->salida.="  <td align=\"left\" width=\"20%\">OBSERVACION:</td>";
								$this->salida.="  <td align=\"left\" width=\"60%\">".$_SESSION['MEDICAMENTOSM'.$pfj][observacion]."</td>";
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\">";
				$this->salida.="<table>";
				$this->salida.="<tr class=\"$estilo\">";
						$this->salida.="<td colspan=\"1\" class=".$this->SetStyle("dosis_dia")." width=\"20%\"align=\"left\" >DOSIS POR DIA</td>";
						if ($_REQUEST['consultar_just'.$pfj]==1)
						{
							$this->salida.="<td colspan=\"1\" width=\"60\" align=\"left\" ><input type='text' readonly class='input-text' size = 40 name = 'dosis_dia$pfj'   value =\"".$vector_justificacion[0][dosis_dia]."\"></td>" ;
						}
						else
						{
							if($_REQUEST['dosis_dia'.$pfj] != '')
							{
								$this->salida.="<td colspan=\"1\" width=\"60\" align=\"left\" ><input type='text' class='input-text' size = 40 name = 'dosis_dia$pfj'   value =\"".$_REQUEST['dosis_dia'.$pfj]."\"></td>" ;
							}
							else
							{
								$this->salida.="<td colspan=\"1\" width=\"60\" align=\"left\" ><input type='text' class='input-text' size = 40 name = 'dosis_dia$pfj'   value =\"".$vector_justificacion[0][dosis_dia]."\"></td>" ;
							}
						}
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"$estilo\">";
						$this->salida.="<td colspan=\"1\" class=".$this->SetStyle("duracion_tratamiento")." width=\"20%\"align=\"left\" >DIAS DE TRATAMIENTO</td>";

						if ($_REQUEST['consultar_just'.$pfj]==1)
						{
							  $this->salida.="<td colspan=\"1\"  width=\"60\" align=\"left\" ><input readonly type='text' class='input-text' size = 60 name = 'duracion_tratamiento$pfj'   value =\"".$vector_justificacion[0][duracion]."\"></td>" ;
						}
						else
						{
							if ($_REQUEST['duracion_tratamiento'.$pfj] != '')
							{
								$this->salida.="<td colspan=\"1\" width=\"60\" align=\"left\" ><input type='text' class='input-text' size = 60 name = 'duracion_tratamiento$pfj'   value =\"".$_REQUEST['duracion_tratamiento'.$pfj]."\"></td>" ;
							}
							else
							{
								$this->salida.="<td colspan=\"1\" width=\"60\" align=\"left\" ><input type='text' class='input-text' size = 60 name = 'duracion_tratamiento$pfj'   value =\"".$vector_justificacion[0][duracion]."\"></td>" ;
							}
						}
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";

//Diagnosticos
				$this->salida.="<script>";
				$this->salida.="function diagnostico1(url){\n";
				$this->salida.="document.formamodjus$pfj.action=url;\n";
				$this->salida.="document.formamodjus$pfj.submit();}";
				$this->salida.="</script>";
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >DIAGNOSTICO</td>";
				$this->salida.="</tr>";

				if ($_REQUEST['consultar_just'.$pfj]==1)
				{
						if ($_SESSION['DIAGNOSTICOSM'.$pfj])
						{
							foreach ($_SESSION['DIAGNOSTICOSM'.$pfj] as $k=>$v)
							{
								$this->salida.="<tr class=\"modulo_list_claro\">";
								$this->salida.="<td colspan = 5>".$k." - ".$v."</td>";
								$this->salida.="</tr>";
							}
						}
				}
				else
				{
						if ($_SESSION['DIAGNOSTICOSM'.$pfj])
						{
							foreach ($_SESSION['DIAGNOSTICOSM'.$pfj] as $k=>$v)
							{
								$accion5=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'eliminardiagnosticom', 'diagnostico'.$pfj=>$k));
								$this->salida.="<tr class=\"modulo_list_claro\">";
								$this->salida.="  <td class=\"$estilo\" align=\"center\" width=\"5%\"><a href='javascript:diagnostico1(\"$accion5\")'><img title=\"Eliminar\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
								$this->salida.="<td colspan = 4>".$k." - ".$v."</td>";
								$this->salida.="  <input type='hidden' name = id$k$pfj' value = ".$k.">";
								$this->salida.="</tr>";
							}
						}
						$this->salida.="<tr class=\"modulo_list_oscuro\">";
						$accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'agregar_diagnosticos'));
						$this->salida.="  <td colspan = 5 align=\"center\" width=\"63%\"><a href='javascript:diagnostico1(\"$accion1\")'><font color='#190CA2'><b><u>AGREGAR MAS DIAGNOSTICOS</u></b></font></a></td>";
						$this->salida.="</tr>";
				}



//descripcion del caso clinico
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >DESCRIPCION DEL CASO CLINICO</td>";
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"$estilo\">";
				if ($_REQUEST['consultar_just'.$pfj]==1)
				{
					$this->salida.="<td colspan = 5 width=\"80%\" align='center' ><textarea readonly style = \"width:80%\" class='textarea' name = 'descripcion_caso_clinico$pfj' cols = 60 rows = 3>".$vector_justificacion[0][descripcion_caso_clinico]."</textarea></td>" ;
				}
				else
				{
					if (($_REQUEST['descripcion_caso_clinico'.$pfj])  == '')
						{
							$this->salida.="<td colspan = 5 width=\"80%\" align='center' ><textarea style = \"width:80%\" class='textarea' name = 'descripcion_caso_clinico$pfj' cols = 60 rows = 3>".$vector_justificacion[0][descripcion_caso_clinico]."</textarea></td>" ;
						}
					else
						{
							$this->salida.="<td colspan = 5 width=\"80%\" align='center' ><textarea style = \"width:80%\" class='textarea' name = 'descripcion_caso_clinico$pfj' cols = 60 rows = 3>".$_REQUEST['descripcion_caso_clinico'.$pfj]."</textarea></td>" ;
						}
				}
				$this->salida.="</tr>";


//alternativas pos previamente utilizadas pendiente
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >ALTERNATIVAS POS PREVIAMENTE UTILIZADAS</td>";
				$this->salida.="</tr>";
for ($j=1;$j<3;$j++)
{
			if ($j==1)
			{
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >PRIMERA POSIBILIDAD TERAPEUTICA POS</td>";
				$this->salida.="</tr>";
			}
			else
			{
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >SEGUNDA POSIBILIDAD TERAPEUTICA POS</td>";
				$this->salida.="</tr>";
			}

				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\">";
				$this->salida.="<table>";
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"1\" width=\"15%\"align=\"left\" >MEDICAMENTO</td>";
				if ($_REQUEST['consultar_just'.$pfj]==1)
				{
						$this->salida.="<td colspan=\"1\" width=\"28\" align=\"left\" ><input type='text' readonly class='input-text' size = 30 name = 'medicamento_pos$j$pfj'   value =\"".$vector_alternartiva[$j-1][medicamento_pos]."\"></td>" ;
				}
				else
				{
					if($_REQUEST['medicamento_pos'.$j.$pfj]=='')
					{
						$this->salida.="<td colspan=\"1\" width=\"28\" align=\"left\" ><input type='text' class='input-text' size = 30 name = 'medicamento_pos$j$pfj'   value =\"".$vector_alternartiva[$j-1][medicamento_pos]."\"></td>" ;
					}
					else
					{
							$this->salida.="<td colspan=\"1\" width=\"28\" align=\"left\" ><input type='text' class='input-text' size = 30 name = 'medicamento_pos$j$pfj'   value =\"".$_REQUEST['medicamento_pos'.$j.$pfj]."\"></td>" ;
					}
				}

				$this->salida.="<td colspan=\"1\" width=\"18%\"align=\"left\" >PRINCIPIO ACTIVO</td>";
				if ($_REQUEST['consultar_just'.$pfj]==1)
				{
					$this->salida.="<td colspan=\"1\" width=\"20\" align=\"left\" ><input readonly type='text' class='input-text' size = 30 name = 'principio_activo_pos$j$pfj'   value =\"".$vector_alternartiva[$j-1][principio_activo]."\"></td>" ;
				}
				else
				{
					if($_REQUEST['principio_activo_pos'.$j.$pfj]=='')
					{
							$this->salida.="<td colspan=\"1\" width=\"20\" align=\"left\" ><input type='text' class='input-text' size = 30 name = 'principio_activo_pos$j$pfj'   value =\"".$vector_alternartiva[$j-1][principio_activo]."\"></td>" ;
					}
					else
					{
							$this->salida.="<td colspan=\"1\" width=\"20\" align=\"left\" ><input type='text' class='input-text' size = 30 name = 'principio_activo_pos$j$pfj'   value =\"".$_REQUEST['principio_activo_pos'.$j.$pfj]."\"></td>" ;
					}
				}
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\">";
				$this->salida.="<table>";
				$this->salida.="<tr class=\"$estilo\">";

				$this->salida.="<td colspan=\"1\" width=\"15%\"align=\"left\" >DOSIS POR DIA</td>";
				if ($_REQUEST['consultar_just'.$pfj]==1)
				{
					$this->salida.="<td colspan=\"1\" width=\"13\" align=\"left\" ><input readonly type='text' class='input-text' size = 20 name = 'dosis_dia_pos$j$pfj'   value =\"".$vector_alternartiva[$j-1][dosis_dia_pos]."\"></td>" ;
				}
				else
				{
						if($_REQUEST['dosis_dia_pos'.$j.$pfj]=='')
						{
							$this->salida.="<td colspan=\"1\" width=\"13\" align=\"left\" ><input type='text' class='input-text' size = 20 name = 'dosis_dia_pos$j$pfj'   value =\"".$vector_alternartiva[$j-1][dosis_dia_pos]."\"></td>" ;
						}
						else
						{
							$this->salida.="<td colspan=\"1\" width=\"13\" align=\"left\" ><input type='text' class='input-text' size = 20 name = 'dosis_dia_pos$j$pfj'   value =\"".$_REQUEST['dosis_dia_pos'.$j.$pfj]."\"></td>" ;
						}
				}
				$this->salida.="<td colspan=\"1\" width=\"25%\"align=\"left\" >DURACION DEL TRATAMIENTO</td>";
        if ($_REQUEST['consultar_just'.$pfj]==1)
				{
					  $this->salida.="<td colspan=\"1\" width=\"13\" align=\"left\" ><input readonly type='text' class='input-text' size = 20 name = 'duracion_tratamiento_pos$j$pfj'   value =\"".$vector_alternartiva[$j-1][duracion_pos]."\"></td>" ;
				}
				else
				{
						if($_REQUEST['duracion_tratamiento_pos'.$j.$pfj]=='')
						{
								$this->salida.="<td colspan=\"1\" width=\"13\" align=\"left\" ><input type='text' class='input-text' size = 20 name = 'duracion_tratamiento_pos$j$pfj'   value =\"".$vector_alternartiva[$j-1][duracion_pos]."\"></td>" ;
						}
						else
						{
								$this->salida.="<td colspan=\"1\" width=\"13\" align=\"left\" ><input type='text' class='input-text' size = 20 name = 'duracion_tratamiento_pos$j$pfj'   value =\"".$_REQUEST['duracion_tratamiento_pos'.$j.$pfj]."\"></td>" ;
						}
				}
				if ($_REQUEST['consultar_just'.$pfj]==1)
				{
				    if ($vector_alternartiva[$j-1][sw_no_mejoria]!= '1')
						{
							$this->salida.="<td width=\"14%\"align=\"left\" ><input disabled type = checkbox name= 'sw_no_mejoria$j$pfj' value = 1>NO MEJORIA</td>";
						}
						else
						{
							$this->salida.="<td width=\"14%\"align=\"left\" ><input disabled type = checkbox checked name= 'sw_no_mejoria$j$pfj' value = 1>NO MEJORIA</td>";
						}
				}
				else
				{
				    if (($_REQUEST['sw_no_mejoria'.$j.$pfj] != '1') AND ($vector_alternartiva[$j-1][sw_no_mejoria]!= '1'))
						{
							$this->salida.="<td width=\"14%\"align=\"left\" ><input type = checkbox name= 'sw_no_mejoria$j$pfj' value = 1>NO MEJORIA</td>";
						}
						else
						{
							$this->salida.="<td width=\"14%\"align=\"left\" ><input type = checkbox checked name= 'sw_no_mejoria$j$pfj' value = 1>NO MEJORIA</td>";
						}
				}
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\">";
				$this->salida.="<table>";
				$this->salida.="<tr class=\"$estilo\">";
        if ($_REQUEST['consultar_just'.$pfj]==1)
				{
						if ($vector_alternartiva[$j-1][sw_reaccion_secundaria]!= '1')
						{
							$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input disabled type = checkbox name= 'sw_reaccion_secundaria$j$pfj' value = 1>&nbsp; REACCION SECUNDARIA</td>";
						}
						else
						{
							$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input disabled type = checkbox checked name= 'sw_reaccion_secundaria$j$pfj' value = 1>&nbsp; REACCION SECUNDARIA</td>";
						}
				}
				else
				{
            if (($_REQUEST['sw_reaccion_secundaria'.$j.$pfj] != '1') AND ($vector_alternartiva[$j-1][sw_reaccion_secundaria]!= '1'))
						{
							$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = checkbox name= 'sw_reaccion_secundaria$j$pfj' value = 1>&nbsp; REACCION SECUNDARIA</td>";
						}
						else
						{
							$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = checkbox checked name= 'sw_reaccion_secundaria$j$pfj' value = 1>&nbsp; REACCION SECUNDARIA</td>";
						}
				}
        if ($_REQUEST['consultar_just'.$pfj]==1)
				{
            $this->salida.="<td colspan=\"1\" width=\"50%\" align='center' ><textarea readonly style = \"width:80%\" class='textarea' name = 'reaccion_secundaria$j$pfj' cols = 60 rows = 3>".$vector_alternartiva[$j-1][reaccion_secundaria]."</textarea></td>" ;
				}
				else
				{
		        if (($_REQUEST['reaccion_secundaria'.$j.$pfj])  == '')
						{
							$this->salida.="<td colspan=\"1\" width=\"50%\" align='center' ><textarea style = \"width:80%\" class='textarea' name = 'reaccion_secundaria$j$pfj' cols = 60 rows = 3>".$vector_alternartiva[$j-1][reaccion_secundaria]."</textarea></td>" ;
						}
						else
						{
							$this->salida.="<td colspan=\"1\" width=\"50%\" align='center' ><textarea style = \"width:80%\" class='textarea' name = 'reaccion_secundaria$j$pfj' cols = 60 rows = 3>".$_REQUEST['reaccion_secundaria'.$j.$pfj]."</textarea></td>" ;
						}
				}
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"$estilo\">";
        if ($_REQUEST['consultar_just'.$pfj]==1)
				{
		        if ($vector_alternartiva[$j-1][sw_contraindicacion]!= '1')
						{
							$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input disabled type = checkbox name= 'sw_contraindicacion$j$pfj' value = 1>&nbsp; CONTRAINDICACION EXPRESA</td>";
						}
						else
						{
							$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input disabled type = checkbox checked name= 'sw_contraindicacion$j$pfj' value = 1>&nbsp; CONTRAINDICACION EXPRESA</td>";
						}
				}
				else
        {
						if (($_REQUEST['sw_contraindicacion'.$j.$pfj] != '1') AND ($vector_alternartiva[$j-1][sw_contraindicacion]!= '1'))
						{
							$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = checkbox name= 'sw_contraindicacion$j$pfj' value = 1>&nbsp; CONTRAINDICACION EXPRESA</td>";
						}
						else
						{
							$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = checkbox checked name= 'sw_contraindicacion$j$pfj' value = 1>&nbsp; CONTRAINDICACION EXPRESA</td>";
						}
				}
        if ($_REQUEST['consultar_just'.$pfj]==1)
				{
            $this->salida.="<td colspan=\"1\" width=\"50%\" align='center' ><textarea readonly style = \"width:80%\" class='textarea' name = 'contraindicacion$j$pfj' cols = 60 rows = 3>".$vector_alternartiva[$j-1][contraindicacion]."</textarea></td>" ;
				}
				else
				{
		        if (($_REQUEST['contraindicacion'.$j.$pfj])  == '')
						{
							$this->salida.="<td colspan=\"1\" width=\"50%\" align='center' ><textarea style = \"width:80%\" class='textarea' name = 'contraindicacion$j$pfj' cols = 60 rows = 3>".$vector_alternartiva[$j-1][contraindicacion]."</textarea></td>" ;
						}
						else
						{
							$this->salida.="<td colspan=\"1\" width=\"50%\" align='center' ><textarea style = \"width:80%\" class='textarea' name = 'contraindicacion$j$pfj' cols = 60 rows = 3>".$_REQUEST['contraindicacion'.$j.$pfj]."</textarea></td>" ;
						}
				}

				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\">";
				$this->salida.="<table>";

    		$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"2\" width=\"19%\"align=\"center\" >OTRAS</td>";
        if ($_REQUEST['consultar_just'.$pfj]==1)
				{
            $this->salida.="<td colspan = 3 width=\"61%\" align='center' ><textarea readonly style = \"width:80%\" class='textarea' name = 'otras$j$pfj' cols = 60 rows = 3>".$vector_alternartiva[$j-1][otras]."</textarea></td>" ;
        }
				else
				{
					if (($_REQUEST['otras'.$j.$pfj])  == '')
						{
							$this->salida.="<td colspan = 3 width=\"61%\" align='center' ><textarea style = \"width:80%\" class='textarea' name = 'otras$j$pfj' cols = 60 rows = 3>".$vector_alternartiva[$j-1][otras]."</textarea></td>" ;
						}
					else
						{
							$this->salida.="<td colspan = 3 width=\"61%\" align='center' ><textarea style = \"width:80%\" class='textarea' name = 'otras$j$pfj' cols = 60 rows = 3>".$_REQUEST['otras'.$j.$pfj]."</textarea></td>" ;
						}
				}
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";

}
//fin de alternativas pos previamente utilizadas

//criterios que justifican la solicitud
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >CRITERIOS DE JUSTIFICACION</td>";
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\">";
				//$this->salida.="<table>";
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";

				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >JUSTIFICACION DE LA SOLICITUD:</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"$estilo\">";
        if ($_REQUEST['consultar_just'.$pfj]==1)
				{
           $this->salida.="<td colspan=\"5\" width=\"80%\" align='left' ><textarea readonly style = \"width:100%\" class='textarea' name = 'justificacion_solicitud$pfj' cols = 60 rows = 3>".$vector_justificacion[0][justificacion]."</textarea></td>" ;
				}
				else
				{
	        if (($_REQUEST['justificacion_solicitud'.$pfj])  == '')
						{
							$this->salida.="<td colspan=\"5\" width=\"80%\" align='left' ><textarea style = \"width:100%\" class='textarea' name = 'justificacion_solicitud$pfj' cols = 60 rows = 3>".$vector_justificacion[0][justificacion]."</textarea></td>" ;
						}
					else
						{
							$this->salida.="<td colspan=\"5\" width=\"80%\" align='left' ><textarea style = \"width:100%\" class='textarea' name = 'justificacion_solicitud$pfj' cols = 60 rows = 3>".$_REQUEST['justificacion_solicitud'.$pfj]."</textarea></td>" ;
						}
				}

				$this->salida.="</tr>";


				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >VENTAJAS DE ESTE MEDICAMENTO:</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"$estilo\">";
				if ($_REQUEST['consultar_just'.$pfj]==1)
				{
           $this->salida.="<td colspan=\"5\" width=\"80%\" align='left' ><textarea readonly style = \"width:100%\" class='textarea' name = 'ventajas_medicamento$pfj' cols = 60 rows = 3>".$vector_justificacion[0][ventajas_medicamento]."</textarea></td>" ;
				}
				else
				{
          if (($_REQUEST['ventajas_medicamento'.$pfj])  == '')
						{
							$this->salida.="<td colspan=\"5\" width=\"80%\" align='left' ><textarea style = \"width:100%\" class='textarea' name = 'ventajas_medicamento$pfj' cols = 60 rows = 3>".$vector_justificacion[0][ventajas_medicamento]."</textarea></td>" ;
						}
					else
						{
							$this->salida.="<td colspan=\"5\" width=\"80%\" align='left' ><textarea style = \"width:100%\" class='textarea' name = 'ventajas_medicamento$pfj' cols = 60 rows = 3>".$_REQUEST['ventajas_medicamento'.$pfj]."</textarea></td>" ;
						}
				}

				$this->salida.="</tr>";

				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >VENTAJAS DEL TRATAMIENTO:</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"$estilo\">";
				if ($_REQUEST['consultar_just'.$pfj]==1)
				{
          $this->salida.="<td colspan=\"5\" width=\"80%\" align='left' ><textarea readonly style = \"width:100%\" class='textarea' name = 'ventajas_tratamiento$pfj' cols = 60 rows = 3>".$vector_justificacion[0][ventajas_tratamiento]."</textarea></td>" ;
				}
				else
				{
          if (($_REQUEST['ventajas_tratamiento'.$pfj])  == '')
						{
							$this->salida.="<td colspan=\"5\" width=\"80%\" align='left' ><textarea style = \"width:100%\" class='textarea' name = 'ventajas_tratamiento$pfj' cols = 60 rows = 3>".$vector_justificacion[0][ventajas_tratamiento]."</textarea></td>" ;
						}
					else
						{
							$this->salida.="<td colspan=\"5\" width=\"80%\" align='left' ><textarea style = \"width:100%\" class='textarea' name = 'ventajas_tratamiento$pfj' cols = 60 rows = 3>".$_REQUEST['ventajas_tratamiento'.$pfj]."</textarea></td>" ;
						}
				}

				$this->salida.="</tr>";

				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >PRECAUCIONES:</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"$estilo\">";
				if ($_REQUEST['consultar_just'.$pfj]==1)
				{
          $this->salida.="<td colspan=\"5\" width=\"80%\" align='left' ><textarea readonly style = \"width:100%\" class='textarea' name = 'precauciones$pfj' cols = 60 rows = 3>".$vector_justificacion[0][precauciones]."</textarea></td>" ;
				}
				else
				{
	        if (($_REQUEST['precauciones'.$pfj])  == '')
						{
							$this->salida.="<td colspan=\"5\" width=\"80%\" align='left' ><textarea style = \"width:100%\" class='textarea' name = 'precauciones$pfj' cols = 60 rows = 3>".$vector_justificacion[0][precauciones]."</textarea></td>" ;
						}
					else
						{
							$this->salida.="<td colspan=\"5\" width=\"80%\" align='left' ><textarea style = \"width:100%\" class='textarea' name = 'precauciones$pfj' cols = 60 rows = 3>".$_REQUEST['precauciones'.$pfj]."</textarea></td>" ;
						}
				}

				$this->salida.="</tr>";

				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >CONTROLES PARA EVALUAR LA EFECTIVIDAD DEL MEDICAMENTO:</td>";
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"$estilo\">";
				if ($_REQUEST['consultar_just'.$pfj]==1)
				{
          $this->salida.="<td colspan=\"5\" width=\"80%\" align='left' ><textarea readonly style = \"width:80%\" class='textarea' name = 'controles_evaluacion_efectividad$pfj' cols = 60 rows = 3>".$vector_justificacion[0][controles_evaluacion_efectividad]."</textarea></td>" ;
				}
				else
				{
          if (($_REQUEST['controles_evaluacion_efectividad'.$pfj])  == '')
						{
							$this->salida.="<td colspan=\"5\" width=\"80%\" align='left' ><textarea style = \"width:80%\" class='textarea' name = 'controles_evaluacion_efectividad$pfj' cols = 60 rows = 3>".$vector_justificacion[0][controles_evaluacion_efectividad]."</textarea></td>" ;
						}
					else
						{
							$this->salida.="<td colspan=\"5\" width=\"80%\" align='left' ><textarea style = \"width:80%\" class='textarea' name = 'controles_evaluacion_efectividad$pfj' cols = 60 rows = 3>".$_REQUEST['controles_evaluacion_efectividad'.$pfj]."</textarea></td>" ;
						}
				}
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\">";
				$this->salida.="<table>";
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"1\" width=\"40%\"align=\"left\" >TIEMPO DE RESPUESTA ESPERADO</td>";
        if ($_REQUEST['consultar_just'.$pfj]==1)
				{
          $this->salida.="<td colspan=\"4\" width=\"30\" align=\"left\" ><input readonly type='text' class='input-text' size = 20 name = 'tiempo_respuesta_esperado$pfj'   value =\"".$vector_justificacion[0][tiempo_respuesta_esperado]."\"></td>" ;
				}
				else
				{
					if ($_REQUEST['tiempo_respuesta_esperado'.$pfj]!='')
					{
						$this->salida.="<td colspan=\"4\" width=\"30\" align=\"left\" ><input type='text' class='input-text' size = 20 name = 'tiempo_respuesta_esperado$pfj'   value =\"".$_REQUEST['tiempo_respuesta_esperado'.$pfj]."\"></td>" ;
					}
					else
					{
						$this->salida.="<td colspan=\"4\" width=\"30\" align=\"left\" ><input type='text' class='input-text' size = 20 name = 'tiempo_respuesta_esperado$pfj'   value =\"".$vector_justificacion[0][tiempo_respuesta_esperado]."\"></td>" ;
					}
				}
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";


				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\">";
				$this->salida.="<table>";
				$this->salida.="<tr class=\"$estilo\">";
				if ($_REQUEST['consultar_just'.$pfj]==1)
				{
						if ($vector_justificacion[0][sw_riesgo_inminente] != '1')
						{
							$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input disabled type = checkbox name= 'sw_riesgo_inminente$pfj' value = 1>&nbsp; RIESGO INMINENTE</td>";
						}
						else
						{
							$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input disabled type = checkbox checked name= 'sw_riesgo_inminente$pfj' value = 1>&nbsp; RIESGO INMINENTE</td>";
						}
				}
				else
				{
		        if (($_REQUEST['sw_riesgo_inminente'.$pfj] != '1') AND ($vector_justificacion[0][sw_riesgo_inminente] != '1'))
						{
							$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = checkbox name= 'sw_riesgo_inminente$pfj' value = 1>&nbsp; RIESGO INMINENTE</td>";
						}
						else
						{
							$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = checkbox checked name= 'sw_riesgo_inminente$pfj' value = 1>&nbsp; RIESGO INMINENTE</td>";
						}

				}
				if ($_REQUEST['consultar_just'.$pfj]==1)
				{
            $this->salida.="<td colspan=\"1\" width=\"60%\" align='center' ><textarea readonly style = \"width:80%\" class='textarea' name = 'riesgo_inminente$pfj' cols = 60 rows = 3>".$vector_justificacion[0][riesgo_inminente]."</textarea></td>" ;
				}
				else
				{
            if (($_REQUEST['riesgo_inminente'.$pfj])  == '')
						{
							$this->salida.="<td colspan=\"1\" width=\"60%\" align='center' ><textarea style = \"width:80%\" class='textarea' name = 'riesgo_inminente$pfj' cols = 60 rows = 3>".$vector_justificacion[0][riesgo_inminente]."</textarea></td>" ;
						}
						else
						{
							$this->salida.="<td colspan=\"1\" width=\"60%\" align='center' ><textarea style = \"width:80%\" class='textarea' name = 'riesgo_inminente$pfj' cols = 60 rows = 3>".$_REQUEST['riesgo_inminente'.$pfj]."</textarea></td>" ;
						}
				}
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\">";
				$this->salida.="<table>";
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" >SE HAN AGOTADO LAS POSIBILIDADES EXISTENTES:</td>";
        if ($_REQUEST['consultar_just'.$pfj]==1)
				{
					if ($vector_justificacion[0][sw_agotadas_posibilidades_existentes]!= '1')
					{
						$this->salida.="<td colspan=\"1\" width=\"5%\"align=\"left\" ><input disabled type = radio name= 'sw_agotadas_posibilidades_existentes$pfj' value = '1'>&nbsp; SI</td>";
						$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input disabled type = radio checked name= 'sw_agotadas_posibilidades_existentes$pfj' value = '0'>&nbsp; NO</td>";
					}
					else
					{
						$this->salida.="<td colspan=\"1\" width=\"5%\"align=\"left\" ><input disabled type = radio checked name= 'sw_agotadas_posibilidades_existentes$pfj' value = '1'>&nbsp; SI</td>";
						$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input disabled type = radio name= 'sw_agotadas_posibilidades_existentes$pfj' value = '0'>&nbsp; NO</td>";
					}
				}
				else
				{
	        if (($_REQUEST['sw_agotadas_posibilidades_existentes'.$pfj] != '1') AND ($vector_justificacion[0][sw_agotadas_posibilidades_existentes]!= '1'))
					{
						$this->salida.="<td colspan=\"1\" width=\"5%\"align=\"left\" ><input type = radio name= 'sw_agotadas_posibilidades_existentes$pfj' value = '1'>&nbsp; SI</td>";
						$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = radio checked name= 'sw_agotadas_posibilidades_existentes$pfj' value = '0'>&nbsp; NO</td>";
					}
					else
					{
						$this->salida.="<td colspan=\"1\" width=\"5%\"align=\"left\" ><input type = radio checked name= 'sw_agotadas_posibilidades_existentes$pfj' value = '1'>&nbsp; SI</td>";
						$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = radio name= 'sw_agotadas_posibilidades_existentes$pfj' value = '0'>&nbsp; NO</td>";
					}
				}

				$this->salida.="</tr>";
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" >TIENE HOMOLOGO EN EL POS:</td>";
        if ($_REQUEST['consultar_just'.$pfj]==1)
				{
          if ($vector_justificacion[0][sw_homologo_pos]!= '1')
					{
						$this->salida.="<td colspan=\"1\" width=\"5%\"align=\"left\" ><input disabled type = radio name= 'sw_homologo_pos$pfj' value = '1'>&nbsp; SI</td>";
						$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input disabled type = radio checked name= 'sw_homologo_pos$pfj' value = '0'>&nbsp; NO</td>";
					}
					else
					{
						$this->salida.="<td colspan=\"1\" width=\"5%\"align=\"left\" ><input disabled type = radio checked name= 'sw_homologo_pos$pfj' value = '1'>&nbsp; SI</td>";
						$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input disabled type = radio name= 'sw_homologo_pos$pfj' value = '0'>&nbsp; NO</td>";
					}
				}
				else
				{
          if (($_REQUEST['sw_homologo_pos'.$pfj] != '1') AND ($vector_justificacion[0][sw_homologo_pos]!= '1'))
					{
						$this->salida.="<td colspan=\"1\" width=\"5%\"align=\"left\" ><input type = radio name= 'sw_homologo_pos$pfj' value = '1'>&nbsp; SI</td>";
						$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = radio checked name= 'sw_homologo_pos$pfj' value = '0'>&nbsp; NO</td>";
					}
					else
					{
						$this->salida.="<td colspan=\"1\" width=\"5%\"align=\"left\" ><input type = radio checked name= 'sw_homologo_pos$pfj' value = '1'>&nbsp; SI</td>";
						$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = radio name= 'sw_homologo_pos$pfj' value = '0'>&nbsp; NO</td>";
					}

				}
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" >ES COMERCIALIZADO EN EL PAIS:</td>";
				if ($_REQUEST['consultar_just'.$pfj]==1)
				{
					if ($vector_justificacion[0][sw_comercializacion_pais]!= '1')
					{
						$this->salida.="<td colspan=\"1\" width=\"5%\"align=\"left\" ><input disabled type = radio name= 'sw_comercializacion_pais$pfj' value = '1'>&nbsp; SI</td>";
						$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input disabled type = radio checked name= 'sw_comercializacion_pais$pfj' value = '0'>&nbsp; NO</td>";
					}
					else
					{
						$this->salida.="<td colspan=\"1\" width=\"5%\"align=\"left\" ><input disabled type = radio checked name= 'sw_comercializacion_pais$pfj' value = '1'>&nbsp; SI</td>";
						$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input disabled type = radio name= 'sw_comercializacion_pais$pfj' value = '0'>&nbsp; NO</td>";
					}
				}
				else
				{
	        if (($_REQUEST['sw_comercializacion_pais'.$pfj] != '1') AND ($vector_justificacion[0][sw_comercializacion_pais]!= '1'))
					{
						$this->salida.="<td colspan=\"1\" width=\"5%\"align=\"left\" ><input type = radio name= 'sw_comercializacion_pais$pfj' value = '1'>&nbsp; SI</td>";
						$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = radio checked name= 'sw_comercializacion_pais$pfj' value = '0'>&nbsp; NO</td>";
					}
					else
					{
						$this->salida.="<td colspan=\"1\" width=\"5%\"align=\"left\" ><input type = radio checked name= 'sw_comercializacion_pais$pfj' value = '1'>&nbsp; SI</td>";
						$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = radio name= 'sw_comercializacion_pais$pfj' value = '0'>&nbsp; NO</td>";
					}
				}
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";
//FIN OK
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >NOTA</td>";
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >Para el trámite de esta solicitud es obligatorio el diligenciamiento completo, anexando el original de la formula médica y el resumen de la historia clinica.<br>La entrega del medicamento está sujeta
																a la aprobación del comité técnico-cientifico, de acuerdo a lo establecido en la resolución 5061 del 23 de diciembre de 1997.</td>";
				$this->salida.="</tr>";

				$this->salida.="</table><br>";

				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\"><tr>";

        if ($_REQUEST['consultar_just'.$pfj]==1)
				{
					$this->salida .= "</form>";
					$accion3=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false, array('accion'.$pfj=>''));
					$this->salida .= "<form name=\"forma\" action=\"$accion3\" method=\"post\">";
					$this->salida .= "<td align=\"center\"><input class=\"input-submit\" name= 'cancelar$pfj' type=\"submit\" value=\"VOLVER\"></form></td>";
        }
				else
				{
          $this->salida .= "<td   align=\"center\"><input class=\"input-submit\" name= 'guardar_justificacion$pfj' type=\"submit\" value=\"GUARDAR JUSTIFICACION MODIFICADA\"></td>";
					$this->salida .= "</form>";
					$accion3=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false, array('accion'.$pfj=>''));
					$this->salida .= "<form name=\"forma\" action=\"$accion3\" method=\"post\">";
					$this->salida .= "<td   align=\"center\"><input class=\"input-submit\" name= 'cancelar$pfj' type=\"submit\" value=\"CANCELAR\"></form></td>";
				}
				$this->salida.="</tr></table>";
				$this->salida .= ThemeCerrarTablaSubModulo();
}

//*
function Justificacion_Medicamentos_No_Pos()
{
				$pfj=$this->frmPrefijo;
				$this->salida= ThemeAbrirTablaSubModulo('JUSTIFICACION DEL MEDICAMENTO');
				$datos_m = $_SESSION['DATOS_M'.$pfj]['PLAN_TERAPEUTICO'];
				if(!empty($_SESSION['JUSTIFICACION'.$pfj]) AND ($_SESSION['JUSTIFICACION'.$pfj]['pare']==0))
				{
						//***********cargando los datos en la justificacion de la variable de sesion a los request
						$_REQUEST['dosis_dia'.$pfj] 							= $_SESSION['JUSTIFICACION'.$pfj]['dosis_dia'];
						$_REQUEST['duracion_tratamiento'.$pfj] 		= $_SESSION['JUSTIFICACION'.$pfj]['duracion_tratamiento'];
						$_REQUEST['descripcion_caso_clinico'.$pfj]= $_SESSION['JUSTIFICACION'.$pfj]['descripcion_caso_clinico'];
						for ($j=1;$j<3;$j++)
							{
									$_REQUEST['medicamento_pos'.$j.$pfj] 					= $_SESSION['JUSTIFICACION'.$pfj]['medicamento_pos'.$j];
									$_REQUEST['principio_activo_pos'.$j.$pfj] 		= $_SESSION['JUSTIFICACION'.$pfj]['principio_activo_pos'.$j];
									$_REQUEST['dosis_dia_pos'.$j.$pfj] 						= $_SESSION['JUSTIFICACION'.$pfj]['dosis_dia_pos'.$j];
									$_REQUEST['duracion_tratamiento_pos'.$j.$pfj]	= $_SESSION['JUSTIFICACION'.$pfj]['duracion_tratamiento_pos'.$j];
									$_REQUEST['sw_no_mejoria'.$j.$pfj]						= $_SESSION['JUSTIFICACION'.$pfj]['sw_no_mejoria'.$j];
									$_REQUEST['sw_reaccion_secundaria'.$j.$pfj]		= $_SESSION['JUSTIFICACION'.$pfj]['sw_reaccion_secundaria'.$j];
									$_REQUEST['reaccion_secundaria'.$j.$pfj]			= $_SESSION['JUSTIFICACION'.$pfj]['reaccion_secundaria'.$j];
									$_REQUEST['sw_contraindicacion'.$j.$pfj]			= $_SESSION['JUSTIFICACION'.$pfj]['sw_contraindicacion'.$j];
									$_REQUEST['contraindicacion'.$j.$pfj]					= $_SESSION['JUSTIFICACION'.$pfj]['contraindicacion'.$j];
									$_REQUEST['otras'.$j.$pfj]										=	$_SESSION['JUSTIFICACION'.$pfj]['otras'.$j];
							}
						$_REQUEST['justificacion_solicitud'.$pfj]						= $_SESSION['JUSTIFICACION'.$pfj]['justificacion_solicitud'];
						$_REQUEST['ventajas_medicamento'.$pfj]							= $_SESSION['JUSTIFICACION'.$pfj]['ventajas_medicamento'];
						$_REQUEST['ventajas_tratamiento'.$pfj]							= $_SESSION['JUSTIFICACION'.$pfj]['ventajas_tratamiento'];
						$_REQUEST['precauciones'.$pfj]											= $_SESSION['JUSTIFICACION'.$pfj]['precauciones'];
						$_REQUEST['controles_evaluacion_efectividad'.$pfj]	= $_SESSION['JUSTIFICACION'.$pfj]['controles_evaluacion_efectividad'];
						$_REQUEST['tiempo_respuesta_esperado'.$pfj]					= $_SESSION['JUSTIFICACION'.$pfj]['tiempo_respuesta_esperado'];
						$_REQUEST['sw_riesgo_inminente'.$pfj]								= $_SESSION['JUSTIFICACION'.$pfj]['sw_riesgo_inminente'];
						$_REQUEST['riesgo_inminente'.$pfj]									= $_SESSION['JUSTIFICACION'.$pfj]['riesgo_inminente'];
						$_REQUEST['sw_agotadas_posibilidades_existentes'.$pfj]= $_SESSION['JUSTIFICACION'.$pfj]['sw_agotadas_posibilidades_existentes'];
						$_REQUEST['sw_homologo_pos'.$pfj]										= $_SESSION['JUSTIFICACION'.$pfj]['sw_homologo_pos'];
						$_REQUEST['sw_comercializacion_pais'.$pfj]					= $_SESSION['JUSTIFICACION'.$pfj]['sw_comercializacion_pais'];

						//***********fin******************************************************************************
				}

				//if comprobar_especialista == 1 {}

				$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar_justificacion_no_pos'));
				$this->salida .= "<form name=\"formajus$pfj\" action=\"$accion\" method=\"post\">";

    		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida.="</table>";

				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
    		$this->salida.="<tr class=\"modulo_table_list_title\">";
				$this->salida.="  <td align=\"center\" colspan=\"5\">JUSTIFICACION DE MEDICAMENTOS NO POS</td>";
				$this->salida.="</tr>";

    if( $i % 2){ $estilo='modulo_list_claro';}
				else {$estilo='modulo_list_oscuro';}

//datos del medicamento
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >DATOS DEL MEDICAMENTO</td>";
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="  <td align=\"center\" width=\"5%\">CODIGO</td>";
				$this->salida.="  <td align=\"center\" width=\"20%\">PRODUCTO</td>";
				$this->salida.="  <td align=\"center\" width=\"20%\">PRINCIPIO ACTIVO</td>";
				$this->salida.="  <td align=\"center\" width=\"20%\">CONCENTRACION</td>";
				$this->salida.="  <td align=\"center\" width=\"15%\">FORMA</td>";
				$this->salida.="</tr>";


				if ($_SESSION['SPIA'.$pfj]==1)
				{
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="<td align=\"center\" width=\"5%\">".$_SESSION['MEDICAMENTOS'.$pfj]['codigo_producto']."</td>";
					$this->salida.="<td align=\"center\" width=\"20%\" >".$_SESSION['MEDICAMENTOS'.$pfj]['producto']."</td>";
					$this->salida.="<td align=\"center\" width=\"20%\" >".$_SESSION['MEDICAMENTOS'.$pfj]['principio_activo']."</td>";
					$this->salida.="<td align=\"center\" width=\"20%\" >".$_SESSION['MEDICAMENTOS'.$pfj]['concentracion_forma_farmacologica']." ".$_SESSION['MEDICAMENTOS'.$pfj]['unidad_medida_medicamento_id']."</td>";
					$this->salida.="<td align=\"center\" width=\"15%\" >".$_SESSION['MEDICAMENTOS'.$pfj]['forma']."</td>";
					$this->salida.="</tr>";
				}
				else
				{
					$arreglo=explode("|/",$datos_m);
					$this->salida.="  <input type='hidden' name = 'item$pfj'  value = '".$arreglo[0]."'>";
					$this->salida.="  <input type='hidden' name = 'codigo_producto$pfj'  value = '".$arreglo[1]."'>";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="<td align=\"center\" width=\"5%\">".$arreglo[1]."</td>";
					$this->salida.="<td align=\"center\" width=\"20%\" >".$arreglo[2]."</td>";
					$this->salida.="<td align=\"center\" width=\"20%\" >".$arreglo[3]."</td>";
					$this->salida.="<td align=\"center\" width=\"20%\" >".$arreglo[4]." ".$arreglo[5]."</td>";
					$this->salida.="<td align=\"center\" width=\"15%\" >".$arreglo[6]."</td>";
					$this->salida.="</tr>";
				}
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan = 5>";
				$this->salida.="<table>";
				/*
				$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="  <td colspan = 2 align=\"left\" width=\"80%\">VIA DE ADMINISTRACION: ".$_SESSION['MEDICAMENTOS'.$pfj]['via_administracion_id']."</td>";
				$this->salida.="</tr>";
				*/
				$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="  <td align=\"left\" width=\"20%\">DOSIS:</td>";
					$e=($_SESSION['MEDICAMENTOS'.$pfj]['dosis'])/(floor($_SESSION['MEDICAMENTOS'.$pfj]['dosis']));
					if($e==1)
					{
						$this->salida.="  <td align=\"left\" width=\"60%\">".floor($_SESSION['MEDICAMENTOS'.$pfj]['dosis'])."  ".$_SESSION['MEDICAMENTOS'.$pfj]['unidad_dosificacion']."</td>";
					}
					else
					{
						$this->salida.="  <td align=\"left\" width=\"60%\">".$_SESSION['MEDICAMENTOS'.$pfj]['dosis']."  ".$_SESSION['MEDICAMENTOS'.$pfj]['unidad_dosificacion']."</td>";
					}
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="  <td align=\"left\" width=\"20%\">CANTIDAD:</td>";
					$e=($_SESSION['MEDICAMENTOS'.$pfj]['cantidad'])/(floor($_SESSION['MEDICAMENTOS'.$pfj]['cantidad']));
	//ojo este contenido_unidad_venta aca no esta llegando
					if ($vector1[$i][contenido_unidad_venta])
					{
						if($e==1)
						{
							$this->salida.="  <td align=\"left\" width=\"60%\">".floor($_SESSION['MEDICAMENTOS'.$pfj]['cantidad'])." ".$vector1[$i][descripcion]." por ".$vector1[$i][contenido_unidad_venta]."</td>";
						}
						else
						{
							$this->salida.="  <td align=\"left\" width=\"60%\">".$_SESSION['MEDICAMENTOS'.$pfj]['cantidad']." ".$vector1[$i][descripcion]." por ".$vector1[$i][contenido_unidad_venta]."</td>";
						}
					}
					else
					{
						if($e==1)
						{
							$this->salida.="  <td align=\"left\" width=\"60%\">".floor($_SESSION['MEDICAMENTOS'.$pfj]['cantidad'])." ".$vector1[$i][descripcion]."</td>";
						}
						else
						{
							$this->salida.="  <td align=\"left\" width=\"60%\">".$_SESSION['MEDICAMENTOS'.$pfj]['cantidad']." ".$vector1[$i][descripcion]."</td>";
						}
					}
				$this->salida.="</tr>";
				$this->salida.="</td>";
				$this->salida.="</table>";

				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan = 5>";
				$this->salida.="<table>";
    		$this->salida.="<tr class=\"$estilo\">";
								$this->salida.="  <td align=\"left\" width=\"20%\">OBSERVACION:</td>";
								$this->salida.="  <td align=\"left\" width=\"60%\">".$_SESSION['MEDICAMENTOS'.$pfj]['observacion']."</td>";
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";



				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\">";
				$this->salida.="<table>";
				$this->salida.="<tr class=\"$estilo\">";
						$this->salida.="<td colspan=\"1\" class=".$this->SetStyle("dosis_dia")." width=\"20%\"align=\"left\" >DOSIS POR DIA</td>";
						$this->salida.="<td colspan=\"1\" width=\"60\" align=\"left\" ><input type='text' class='input-text' size = 40 name = 'dosis_dia$pfj'   value =\"".$_REQUEST['dosis_dia'.$pfj]."\"></td>" ;
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"$estilo\">";
						$this->salida.="<td colspan=\"1\" class=".$this->SetStyle("duracion_tratamiento")." width=\"20%\"align=\"left\" >DIAS DE TRATAMIENTO</td>";
						$this->salida.="<td colspan=\"1\" width=\"60\" align=\"left\" ><input type='text' class='input-text' size = 60 name = 'duracion_tratamiento$pfj'   value =\"".$_REQUEST['duracion_tratamiento'.$pfj]."\"></td>" ;
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";

//Diagnosticos
				$this->salida.="<script>";
				$this->salida.="function diagnostico(url){\n";
				$this->salida.="document.formajus$pfj.action=url;\n";
				$this->salida.="document.formajus$pfj.submit();}";
				$this->salida.="</script>";
                    
                    $flag = $this->SetStyle("diagnostico_id");
                    if ($flag == 'label_error')
                    {
                         $this->salida.="<tr class=".$this->SetStyle("diagnostico_id").">";
                    }
                    else
                    {
                         $this->salida.="<tr class=\"modulo_table_title\">";
                    }

				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >DIAGNOSTICO</td>";
				$this->salida.="</tr>";
							if ($_SESSION['DIAGNOSTICOS'.$pfj])
							{
								foreach ($_SESSION['DIAGNOSTICOS'.$pfj] as $k=>$v)
								{
									$accion5=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'eliminardiagnostico', 'diagnostico'.$pfj=>$k));
									$this->salida.="<tr class=\"modulo_list_claro\">";
									$this->salida.="  <td class=\"$estilo\" align=\"center\" width=\"5%\"><a href='javascript:diagnostico(\"$accion5\")'><img title=\"Eliminar\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
									$this->salida.="<td colspan = 4>".$k." - ".$v."</td>";
									$this->salida.="  <input type='hidden' name = id$k$pfj' value = ".$k.">";
									$this->salida.="</tr>";
								}
							}
				$this->salida.="<tr class=\"modulo_list_oscuro\">";
					$accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'agregar_diagnosticos'));
					$this->salida.="  <td colspan = 5 align=\"center\" width=\"63%\"><a href='javascript:diagnostico(\"$accion1\")'><font color='#190CA2'><b><u>AGREGAR MAS DIAGNOSTICOS</u></b></font></a></td>";
				$this->salida.="</tr>";



//descripcion del caso clinico
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >DESCRIPCION DEL CASO CLINICO</td>";
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"$estilo\">";
				if (($_REQUEST['descripcion_caso_clinico'.$pfj])  == '')
					{
						$this->salida.="<td colspan = 5 width=\"80%\" align='center' ><textarea style = \"width:80%\" class='textarea' name = 'descripcion_caso_clinico$pfj' cols = 60 rows = 3></textarea></td>" ;
					}
				else
					{
						$this->salida.="<td colspan = 5 width=\"80%\" align='center' ><textarea style = \"width:80%\" class='textarea' name = 'descripcion_caso_clinico$pfj' cols = 60 rows = 3>".$_REQUEST['descripcion_caso_clinico'.$pfj]."</textarea></td>" ;
					}
				$this->salida.="</tr>";


//alternativas pos previamente utilizadas
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >ALTERNATIVAS POS PREVIAMENTE UTILIZADAS</td>";
				$this->salida.="</tr>";
for ($j=1;$j<3;$j++)
{
			if ($j==1)
			{
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >PRIMERA POSIBILIDAD TERAPEUTICA POS</td>";
				$this->salida.="</tr>";
			}
			else
			{
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >SEGUNDA POSIBILIDAD TERAPEUTICA POS</td>";
				$this->salida.="</tr>";
			}

				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\">";
				$this->salida.="<table>";
				$this->salida.="<tr class=\"$estilo\">";
						$this->salida.="<td colspan=\"1\" width=\"15%\"align=\"left\" >MEDICAMENTO</td>";
						$this->salida.="<td colspan=\"1\" width=\"28\" align=\"left\" ><input type='text' class='input-text' size = 30 name = 'medicamento_pos$j$pfj'   value =\"".$_REQUEST['medicamento_pos'.$j.$pfj]."\"></td>" ;
						$this->salida.="<td colspan=\"1\" width=\"18%\"align=\"left\" >PRINCIPIO ACTIVO</td>";
						$this->salida.="<td colspan=\"1\" width=\"20\" align=\"left\" ><input type='text' class='input-text' size = 30 name = 'principio_activo_pos$j$pfj'   value =\"".$_REQUEST['principio_activo_pos'.$j.$pfj]."\"></td>" ;
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";



				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\">";
				$this->salida.="<table>";
				$this->salida.="<tr class=\"$estilo\">";
						$this->salida.="<td colspan=\"1\" width=\"15%\"align=\"left\" >DOSIS POR DIA</td>";
						$this->salida.="<td colspan=\"1\" width=\"13\" align=\"left\" ><input type='text' class='input-text' size = 20 name = 'dosis_dia_pos$j$pfj'   value =\"".$_REQUEST['dosis_dia_pos'.$j.$pfj]."\"></td>" ;
						$this->salida.="<td colspan=\"1\" width=\"25%\"align=\"left\" >DURACION DEL TRATAMIENTO</td>";
						$this->salida.="<td colspan=\"1\" width=\"13\" align=\"left\" ><input type='text' class='input-text' size = 20 name = 'duracion_tratamiento_pos$j$pfj'   value =\"".$_REQUEST['duracion_tratamiento_pos'.$j.$pfj]."\"></td>" ;
						if (($_REQUEST['sw_no_mejoria'.$j.$pfj] != '1') AND ($datos[0][tipo_opcion_posologia_id]!= '1'))
						{
							$this->salida.="<td width=\"14%\"align=\"left\" ><input type = checkbox name= 'sw_no_mejoria$j$pfj' value = 1>NO MEJORIA</td>";
						}
						else
						{
							$this->salida.="<td width=\"14%\"align=\"left\" ><input type = checkbox checked name= 'sw_no_mejoria$j$pfj' value = 1>NO MEJORIA</td>";
						}
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\">";
				$this->salida.="<table>";
				$this->salida.="<tr class=\"$estilo\">";
						if (($_REQUEST['sw_reaccion_secundaria'.$j.$pfj] != '1') AND ($datos[0][	tipo_opcion_posologia_id]!= '1'))
						{
							$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = checkbox name= 'sw_reaccion_secundaria$j$pfj' value = 1>&nbsp; REACCION SECUNDARIA</td>";
						}
						else
						{
							$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = checkbox checked name= 'sw_reaccion_secundaria$j$pfj' value = 1>&nbsp; REACCION SECUNDARIA</td>";
						}
						if (($_REQUEST['reaccion_secundaria'.$j.$pfj])  == '')
						{
							$this->salida.="<td colspan=\"1\" width=\"50%\" align='center' ><textarea style = \"width:80%\" class='textarea' name = 'reaccion_secundaria$j$pfj' cols = 60 rows = 3></textarea></td>" ;
						}
						else
						{
							$this->salida.="<td colspan=\"1\" width=\"50%\" align='center' ><textarea style = \"width:80%\" class='textarea' name = 'reaccion_secundaria$j$pfj' cols = 60 rows = 3>".$_REQUEST['reaccion_secundaria'.$j.$pfj]."</textarea></td>" ;
						}
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"$estilo\">";
						if (($_REQUEST['sw_contraindicacion'.$j.$pfj] != '1') AND ($datos[0][	tipo_opcion_posologia_id]!= '1'))
						{
							$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = checkbox name= 'sw_contraindicacion$j$pfj' value = 1>&nbsp; CONTRAINDICACION EXPRESA</td>";
						}
						else
						{
							$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = checkbox checked name= 'sw_contraindicacion$j$pfj' value = 1>&nbsp; CONTRAINDICACION EXPRESA</td>";
						}

						if (($_REQUEST['contraindicacion'.$j.$pfj])  == '')
						{
							$this->salida.="<td colspan=\"1\" width=\"50%\" align='center' ><textarea style = \"width:80%\" class='textarea' name = 'contraindicacion$j$pfj' cols = 60 rows = 3></textarea></td>" ;
						}
						else
						{
							$this->salida.="<td colspan=\"1\" width=\"50%\" align='center' ><textarea style = \"width:80%\" class='textarea' name = 'contraindicacion$j$pfj' cols = 60 rows = 3>".$_REQUEST['contraindicacion'.$j.$pfj]."</textarea></td>" ;
						}
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\">";
				$this->salida.="<table>";

    		$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"2\" width=\"19%\"align=\"center\" >OTRAS</td>";

				if (($_REQUEST['otras'.$j.$pfj])  == '')
					{
						$this->salida.="<td colspan = 3 width=\"61%\" align='center' ><textarea style = \"width:80%\" class='textarea' name = 'otras$j$pfj' cols = 60 rows = 3></textarea></td>" ;
					}
				else
					{
						$this->salida.="<td colspan = 3 width=\"61%\" align='center' ><textarea style = \"width:80%\" class='textarea' name = 'otras$j$pfj' cols = 60 rows = 3>".$_REQUEST['otras'.$j.$pfj]."</textarea></td>" ;
					}
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";

}
//fin de alternativas pos previamente utilizadas

//criterios que justifican la solicitud

				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >CRITERIOS DE JUSTIFICACION</td>";
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"center\">";

				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";

				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >JUSTIFICACION DE LA SOLICITUD:</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"$estilo\">";
				if (($_REQUEST['justificacion_solicitud'.$pfj])  == '')
					{
						$this->salida.="<td colspan=\"5\" width=\"80%\" align=\"center\" ><textarea style = \"width:100%\" class='textarea' name = 'justificacion_solicitud$pfj' cols = 60 rows = 3></textarea></td>" ;
					}
				else
					{
						$this->salida.="<td colspan=\"5\" width=\"80%\" align=\"center\" ><textarea style = \"width:100%\" class='textarea' name = 'justificacion_solicitud$pfj' cols = 60 rows = 3>".$_REQUEST['justificacion_solicitud'.$pfj]."</textarea></td>" ;
					}

				$this->salida.="</tr>";


				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >VENTAJAS DE ESTE MEDICAMENTO:</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"$estilo\">";
				if (($_REQUEST['ventajas_medicamento'.$pfj])  == '')
					{
						$this->salida.="<td colspan=\"5\" width=\"80%\" align=\"center\" ><textarea style = \"width:100%\" class='textarea' name = 'ventajas_medicamento$pfj' cols = 60 rows = 3></textarea></td>" ;
					}
				else
					{
						$this->salida.="<td colspan=\"5\" width=\"80%\" align=\"center\" ><textarea style = \"width:100%\" class='textarea' name = 'ventajas_medicamento$pfj' cols = 60 rows = 3>".$_REQUEST['ventajas_medicamento'.$pfj]."</textarea></td>" ;
					}

				$this->salida.="</tr>";

				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >VENTAJAS DEL TRATAMIENTO:</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"$estilo\">";
				if (($_REQUEST['ventajas_tratamiento'.$pfj])  == '')
					{
						$this->salida.="<td colspan=\"5\" width=\"80%\" align=\"center\" ><textarea style = \"width:100%\" class='textarea' name = 'ventajas_tratamiento$pfj' cols = 60 rows = 3></textarea></td>" ;
					}
				else
					{
						$this->salida.="<td colspan=\"5\" width=\"80%\" align=\"center\" ><textarea style = \"width:100%\" class='textarea' name = 'ventajas_tratamiento$pfj' cols = 60 rows = 3>".$_REQUEST['ventajas_tratamiento'.$pfj]."</textarea></td>" ;
					}

				$this->salida.="</tr>";

				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >PRECAUCIONES:</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"$estilo\">";
				if (($_REQUEST['precauciones'.$pfj])  == '')
					{
						$this->salida.="<td colspan=\"5\" width=\"80%\" align=\"center\" ><textarea style = \"width:100%\" class='textarea' name = 'precauciones$pfj' cols = 60 rows = 3></textarea></td>" ;
					}
				else
					{
						$this->salida.="<td colspan=\"5\" width=\"80%\" align=\"center\" ><textarea style = \"width:100%\" class='textarea' name = 'precauciones$pfj' cols = 60 rows = 3>".$_REQUEST['precauciones'.$pfj]."</textarea></td>" ;
					}

				$this->salida.="</tr>";


				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >CONTROLES PARA EVALUAR LA EFECTIVIDAD DEL MEDICAMENTO:</td>";
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"$estilo\">";
				if (($_REQUEST['controles_evaluacion_efectividad'.$pfj])  == '')
					{
						$this->salida.="<td colspan=\"5\" width=\"80%\" align=\"center\" ><textarea style = \"width:100%\" class='textarea' name = 'controles_evaluacion_efectividad$pfj' cols = 60 rows = 3></textarea></td>" ;
					}
				else
					{
						$this->salida.="<td colspan=\"5\" width=\"80%\" align=\"center\" ><textarea style = \"width:100%\" class='textarea' name = 'controles_evaluacion_efectividad$pfj' cols = 60 rows = 3>".$_REQUEST['controles_evaluacion_efectividad'.$pfj]."</textarea></td>" ;
					}

				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";


				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\">";
				$this->salida.="<table>";
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"1\" width=\"40%\"align=\"left\" >TIEMPO DE RESPUESTA ESPERADO</td>";
				$this->salida.="<td colspan=\"4\" width=\"30\" align=\"left\" ><input type='text' class='input-text' size = 20 name = 'tiempo_respuesta_esperado$pfj'   value =\"".$_REQUEST['tiempo_respuesta_esperado'.$pfj]."\"></td>" ;
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";


				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\">";
				$this->salida.="<table>";
				$this->salida.="<tr class=\"$estilo\">";
						if (($_REQUEST['sw_riesgo_inminente'.$pfj] != '1') AND ($datos[0][	tipo_opcion_posologia_id	]!= '1'))
						{
							$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = checkbox name= 'sw_riesgo_inminente$pfj' value = 1>&nbsp; RIESGO INMINENTE</td>";
						}
						else
						{
							$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = checkbox checked name= 'sw_riesgo_inminente$pfj' value = 1>&nbsp; RIESGO INMINENTE</td>";
						}
						if (($_REQUEST['riesgo_inminente'.$pfj])  == '')
						{
							$this->salida.="<td colspan=\"1\" width=\"60%\" align='center' ><textarea style = \"width:80%\" class='textarea' name = 'riesgo_inminente$pfj' cols = 60 rows = 3></textarea></td>" ;
						}
						else
						{
							$this->salida.="<td colspan=\"1\" width=\"60%\" align='center' ><textarea style = \"width:80%\" class='textarea' name = 'riesgo_inminente$pfj' cols = 60 rows = 3>".$_REQUEST['riesgo_inminente'.$pfj]."</textarea></td>" ;
						}
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";




				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\">";
				$this->salida.="<table>";
				$this->salida.="<tr class=\"$estilo\">";
						$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" >SE HAN AGOTADO LAS POSIBILIDADES EXISTENTES:</td>";
						if (($_REQUEST['sw_agotadas_posibilidades_existentes'.$pfj] != '1') AND ($datos[0][	tipo_opcion_posologia_id	]!= '1'))
						{
							$this->salida.="<td colspan=\"1\" width=\"5%\"align=\"left\" ><input type = radio name= 'sw_agotadas_posibilidades_existentes$pfj' value = '1'>&nbsp; SI</td>";
							$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = radio checked name= 'sw_agotadas_posibilidades_existentes$pfj' value = '0'>&nbsp; NO</td>";
						}
						else
						{
							$this->salida.="<td colspan=\"1\" width=\"5%\"align=\"left\" ><input type = radio checked name= 'sw_agotadas_posibilidades_existentes$pfj' value = '1'>&nbsp; SI</td>";
							$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = radio name= 'sw_agotadas_posibilidades_existentes$pfj' value = '0'>&nbsp; NO</td>";
						}
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"$estilo\">";
						$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" >TIENE HOMOLOGO EN EL POS:</td>";
						if (($_REQUEST['sw_homologo_pos'.$pfj] != '1') AND ($datos[0][	tipo_opcion_posologia_id	]!= '1'))
						{
							$this->salida.="<td colspan=\"1\" width=\"5%\"align=\"left\" ><input type = radio name= 'sw_homologo_pos$pfj' value = '1'>&nbsp; SI</td>";
							$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = radio checked name= 'sw_homologo_pos$pfj' value = '0'>&nbsp; NO</td>";
						}
						else
						{
							$this->salida.="<td colspan=\"1\" width=\"5%\"align=\"left\" ><input type = radio checked name= 'sw_homologo_pos$pfj' value = '1'>&nbsp; SI</td>";
							$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = radio name= 'sw_homologo_pos$pfj' value = '0'>&nbsp; NO</td>";
						}
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"$estilo\">";
						$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" >ES COMERCIALIZADO EN EL PAIS:</td>";
						if (($_REQUEST['sw_comercializacion_pais'.$pfj] != '1') AND ($datos[0][	tipo_opcion_posologia_id	]!= '1'))
						{
							$this->salida.="<td colspan=\"1\" width=\"5%\"align=\"left\" ><input type = radio name= 'sw_comercializacion_pais$pfj' value = '1'>&nbsp; SI</td>";
							$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = radio checked name= 'sw_comercializacion_pais$pfj' value = '0'>&nbsp; NO</td>";
						}
						else
						{
							$this->salida.="<td colspan=\"1\" width=\"5%\"align=\"left\" ><input type = radio checked name= 'sw_comercializacion_pais$pfj' value = '1'>&nbsp; SI</td>";
							$this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = radio name= 'sw_comercializacion_pais$pfj' value = '0'>&nbsp; NO</td>";
						}
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";
//FIN OK


				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >NOTA</td>";
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >Para el trámite de esta solicitud es obligatorio el diligenciamiento completo, anexando el original de la formula médica y el resumen de la historia clinica.<br>La entrega del medicamento está sujeta
																a la aprobación del comité técnico-cientifico, de acuerdo a lo establecido en la resolución 5061 del 23 de diciembre de 1997.</td>";
				$this->salida.="</tr>";

				$this->salida.="</table><br>";

				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"0\"><tr>";
				$this->salida .= "<td   align=\"center\"><input class=\"input-submit\" name= 'guardar_justificacion$pfj' type=\"submit\" value=\"GUARDAR MEDICAMENTO JUSTIFICADO\"></td>";

				$this->salida .= "</form>";
				if ($_SESSION['SPIA'.$pfj]!=1)
				{
				$accion3=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false, array('accion'.$pfj=>'volver'));
				$this->salida .= "<form name=\"forma\" action=\"$accion3\" method=\"post\">";
				$this->salida .= "<td   align=\"center\"><input class=\"input-submit\" name= 'volver$pfj' type=\"submit\" value=\"VOLVER A LA SOLICITUD DEL MEDICAMENTO\"></form></td>";
				}
				$accion3=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false, array('accion'.$pfj=>''));
				$this->salida .= "<form name=\"forma\" action=\"$accion3\" method=\"post\">";
				$this->salida .= "<td   align=\"center\"><input class=\"input-submit\" name= 'cancelar$pfj' type=\"submit\" value=\"CANCELAR\"></form></td>";
				$this->salida.="</tr></table>";
				$this->salida .= ThemeCerrarTablaSubModulo();
}

//clzc - si - *
function frmForma_Modificar_Solicitud_Medicamento($codigo_producto)
{
		$pfj=$this->frmPrefijo;
		$datos = $this->ConsultaGeneralModificacionMedicamento($codigo_producto);
    $this->salida= ThemeAbrirTablaSubModulo('MODIFICACION DE LA SOLICITUD DE MEDICAMENTOS');
    $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'modificar_datos','codigo_producto'.$pfj=>$codigo_producto, 'opcion_posol'.$pfj=>$datos[0][tipo_opcion_posologia_id], 'item'.$pfj=>$datos[0][item], 'producto'.$pfj=>$datos[0][producto], 'principio_activo'.$pfj=>$datos[0][principio_activo], 'concentracion_forma_farmacologica'.$pfj=>$datos[0][concentracion_forma_farmacologica], 'unidad_medida_medicamento_id'.$pfj=>$datos[0][unidad_medida_medicamento_id], 'forma'.$pfj=>$datos[0][forma]));
    $this->salida .= "<form name=\"forma_med1$pfj\" action=\"$accion\" method=\"post\">";
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida.="</table>";
//...............modificacion de la captura de medicamentos
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="  <td align=\"center\" colspan=\"6\">MODIFICACION DE LA FORMULACION DEL MEDICAMENTO</td>";
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"modulo_table_title\">";

				$this->salida.="  <td align=\"center\" width=\"5%\"></td>";
				$this->salida.="  <td align=\"center\" width=\"5%\">CODIGO</td>";
				$this->salida.="  <td align=\"center\" width=\"23%\">PRODUCTO</td>";
				$this->salida.="  <td align=\"center\" width=\"23%\">PRINCIPIO ACTIVO</td>";
				$this->salida.="  <td align=\"center\" width=\"23%\">CONCENTRACION</td>";
				$this->salida.="  <td align=\"center\" width=\"15%\">FORMA</td>";
				//$this->salida.="  <td width=\"5%\">EXISTENCIA</td>";
				$this->salida.="</tr>";

				if( $i % 2){ $estilo='modulo_list_claro';}
				else {$estilo='modulo_list_oscuro';}

				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";

				$this->salida.="<td align=\"center\" width=\"5%\">".$datos[0][item]."</td>";
				$this->salida.="<td align=\"center\" width=\"5%\">".$datos[0][codigo_producto]."</td>";
				$this->salida.="<td align=\"center\" width=\"23%\" >".$datos[0][producto]."</td>";
				$this->salida.="<td align=\"center\" width=\"23%\" >".$datos[0][principio_activo]."</td>";
				$this->salida.="<td align=\"center\" width=\"15%\" >".$datos[0][concentracion_forma_farmacologica]." ".$datos[0][unidad_medida_medicamento_id]."</td>";
				$this->salida.="<td align=\"center\" width=\"15%\" >".$datos[0][forma]."</td>";

				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";

//via de administracion
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td class=".$this->SetStyle("via_administracion")." width=\"20%\"align=\"left\" >VIA DE ADMINISTRACION</td>";
				$via_admon = $this->tipo_via_administracion($datos[0][codigo_producto]);

				if ((sizeof($via_admon)>1))
				{
					$this->salida.="<td width=\"60%\" align = left >";
					if	(empty($datos[0][unidad_dosificacion_forma]))
					{
						$EventoOnclick="OnChange='UnidadPorVia(this)'";
					}
					else
					{
						$EventoOnclick="";
					}

					$this->salida.="\n\n<select name = 'via_administracion$pfj'  class =\"select\" $EventoOnclick>";
					$this->salida.="<option value = '-1' selected>-Seleccione-</option>";

					$javita.="<script>\n";
					$javita.="function UnidadPorVia(forma) {\n";
					$javita.="if (forma.value=='-1') {\n";
					$javita.="  document.forma_med1$pfj.unidad_dosis$pfj.length=0;\n";
					$javita.="}\n\n";
					for($i=0;$i<sizeof($via_admon);$i++)
					{			if ($datos[0][via_administracion_id]  != $via_admon[$i][via_administracion_id])
							//if ((($_REQUEST['via_administracion'.$pfj])  != $via_admon[$i][via_administracion_id]))//esta comparacion no la hace porque esta retornando a frmforma y no vuelve aqui por ello el request siempre estara vacio
								{
									$this->salida.="<option value = ".$via_admon[$i][via_administracion_id].">".$via_admon[$i][nombre]."</option>";
								}
							else
								{
									$this->salida.="<option value = ".$via_admon[$i][via_administracion_id]." selected >".$via_admon[$i][nombre]."</option>";
								}

								//generar java para el combo de unidades de dosificacion
								if	(empty($datos[0][unidad_dosificacion_forma]))
										{
												$javita.="if (forma.value=='".$via_admon[$i][via_administracion_id]."') {\n";

												$unidadesViaAdministracion = $this->GetunidadesViaAdministracion($via_admon[$i][via_administracion_id]);

												$javita.="document.forma_med1$pfj.unidad_dosis$pfj.length=".count($unidadesViaAdministracion)."\n";

												for($cont=0;$cont<count($unidadesViaAdministracion);$cont++)
												{
														$javita.="document.forma_med1$pfj.unidad_dosis$pfj.options[".$cont."]= new Option('".$unidadesViaAdministracion[$cont][unidad_dosificacion]."','".$unidadesViaAdministracion[$cont][unidad_dosificacion]."');\n";
												}
												$javita.="}\n\n";
										}
								//fin javita
					}
					$javita.="}\n\n";
					$javita.="</script>\n";
					$this->salida.="</select>\n\n";
					$this->salida.="</td>";
				}
				else
				{
					if ((sizeof($via_admon)==1))
					{
							$this->salida.="<td width=\"60%\" align = left >";
							$this->salida.="\n\n<select name = 'via_administracion$pfj'  class =\"select\">";
							$this->salida.="<option value = ".$via_admon[0][via_administracion_id]." selected >".$via_admon[0][nombre]."</option>";
							$this->salida.="</select>\n\n";
							$this->salida.="</td>";
					}
					else
					{
							$this->salida.="<td width=\"60%\" align = left >&nbsp;</td>";
					}
				}
				$this->salida.="</tr>";

//-----------------

//Generar Combo de unidades de dosificacion
				$ComboUnidadDosis ="<select size = 1 name = 'unidad_dosis$pfj'  class =\"select\">";
				if	(!empty($datos[0][unidad_dosificacion_forma]))
				{
							$ComboUnidadDosis.="<option value = '".$datos[0][unidad_dosificacion_forma]."' selected >".$datos[0][unidad_dosificacion_forma]."</option>";
				}
				else
				{
					if ((sizeof($via_admon)==1))
					{
						$unidadesViaAdministracion = $this->GetunidadesViaAdministracion($via_admon[0][via_administracion_id]);
						$ComboUnidadDosis.="<option value = '-1' selected >--Seleccione--</option>";
						for($i=0;$i<sizeof($unidadesViaAdministracion);$i++)
						{
//aqui agreggue este if para que se seleccione la unidad guardadad en la bd
							if($datos[0][unidad_dosificacion]==$unidadesViaAdministracion[$i][unidad_dosificacion])
							{
								$ComboUnidadDosis.="<option selected value = '".$unidadesViaAdministracion[$i][unidad_dosificacion]."'>".$unidadesViaAdministracion[$i][unidad_dosificacion]."</option>";
							}
							else
							{
								$ComboUnidadDosis.="<option value = '".$unidadesViaAdministracion[$i][unidad_dosificacion]."'>".$unidadesViaAdministracion[$i][unidad_dosificacion]."</option>";
							}
//fin del if y sigue comentado lo que estaba antes de que se creara el if
							//$ComboUnidadDosis.="<option value = '".$unidadesViaAdministracion[$i][unidad_dosificacion]."'>".$unidadesViaAdministracion[$i][unidad_dosificacion]."</option>";
						}
					}
					if (empty($via_admon))
					{
						$unidadesViaAdministracion = $this->Unidades_Dosificacion();
						$ComboUnidadDosis.="<option value = '-1' selected >--Seleccione--</option>";
						for($i=0;$i<sizeof($unidadesViaAdministracion);$i++)
						{
//aqui agreggue este if para que se seleccione la unidad guardadad en la bd
							if($datos[0][unidad_dosificacion]==$unidadesViaAdministracion[$i][unidad_dosificacion])
							{
								$ComboUnidadDosis.="<option selected value = '".$unidadesViaAdministracion[$i][unidad_dosificacion]."'>".$unidadesViaAdministracion[$i][unidad_dosificacion]."</option>";
							}
							else
							{
								$ComboUnidadDosis.="<option value = '".$unidadesViaAdministracion[$i][unidad_dosificacion]."'>".$unidadesViaAdministracion[$i][unidad_dosificacion]."</option>";
							}
						}
					}
				}
				$ComboUnidadDosis.="</select>";
//--------------

//posologia neonatos
/*
				$FechaInicio = $this->datosPaciente[fecha_nacimiento];
				$FechaFin = date("Y-m-d");
				$edad_paciente = CalcularEdad($FechaInicio,$FechaFin);
				if ( $edad_paciente[anos] < ModuloGetVar('','','max_edad_pediatrica'))
					{
						$peso_pac = $this->Peso_Paciente();
						$this->salida.="<tr class=\"$estilo\">";
						$this->salida.="<td width=\"20%\"align=\"left\" >POSOLOGIA NEONATOS</td>";
						$this->salida.="<td width=\"60%\" align = left >";
						$this->salida.="<table>";
						$this->salida.="<tr class=\"$estilo\">";
						$this->salida.="<td  class=".$this->SetStyle("peso")." width=\"20%\" align = left >PESO</td>";
						$this->salida.="<td colspan = 2 width=\"40%\" align='left' ><input type='text' class='input-text' size = 10 name = 'peso$pfj'   value = \"".$peso_pac[peso]."\">  Kg</td>" ;
						$this->salida.="</tr>";
						$this->salida.="<tr class=\"$estilo\">";
						$this->salida.="<td  width=\"20%\" align=\"left\" >DOSIS ORDENADA</td>";
						$this->salida.="<td width=\"15%\" align=\"left\" ><input type='text' class='input-text' size = 10 name = 'dosis_ordenada$pfj'   value =\"".$_REQUEST['dosis_ordenada'.$pfj]."\">  mg/Kg por: </td>" ;
						$this->salida.="<td width=\"25%\" align=\"left\" >";
						$this->salida.="<select size = 1 name = 'criterio_dosis$pfj'  class =\"select\">";
						$this->salida.="<option value = 'dosis' selected>Dosis</option>";
						if (($_REQUEST['criterio_dosis'.$pfj])  == 'Dia')
							{
								$this->salida.="<option value = '002' selected>Dia</option>";
							}
						else
							{
								$this->salida.="<option value = '002' >Dia</option>";
							}
						$this->salida.="</select>";
						$this->salida.="</td>";
						$this->salida.="</tr>";
						$this->salida.="<tr class=\"$estilo\">";
						$this->salida .= "<td width=\"20%\"  align=\"left\"><input type='button' name='calcular_dosis$pfj' value='Calcular Dosis' onclick='Calcular_Dosis(this.form)'></td>";
						$this->salida.="<td colspan=2 width=\"40%\" align=\"left\" ><input type='text' class='input-text' readonly size = 10 name = 'dosis_total$pfj'>  mg</td>" ;
						$this->salida.="</tr>";
						$this->salida.="<tr class=\"$estilo\">";
						$this->salida.="<td class=".$this->SetStyle("cantidad")." width=\"50%\" align = left >CANTIDAD</td>";
						$this->salida.="<td  width=\"65%\" align='left' ><input type='text' class='input-text' size = 15 name = 'cantidad$pfj'   value =\"".$_REQUEST['cantidad'.$pfj]."\"></td>" ;
						$this->salida.="<td  width=\"50%\" align = left >UNIDAD</td>";
						$this->salida.="<td width=\"65%\" align='left' ><input type='text' class='input-text' readonly size = 15 name = 'unidad$pfj'   value =\"".$_REQUEST['unidad'.$pfj]."\"></td>" ;
						$this->salida.="</tr>";
						$this->salida.="</table>";
						$this->salida.="</td>";
						$this->salida.="</tr>";

						//funcion que calcula la dosis
						$this->salida .= "<script>\n";
						$this->salida .= "function Calcular_Dosis(formulario){\n";
						$this->salida .= "var a;\n";
						$this->salida .= "var b;\n";
						$this->salida .= "a=formulario.peso$pfj.value;\n";
						$this->salida .= "b=formulario.dosis_ordenada$pfj.value;\n";
						$this->salida .= "c=a*b;\n";
						$this->salida .= "if(isNaN(c)){\n";
						$this->salida .= "alert('valores no validos');\n";
						$this->salida .= "formulario.dosis_total$pfj.value='';\n";
						$this->salida .= "if(isNaN(b)){\n";
						$this->salida .= "formulario.dosis_ordenada$pfj.value='';\n";
						$this->salida .= "formulario.dosis_ordenada$pfj.focus();\n";
						$this->salida .= "}\n";

						$this->salida .= "if(isNaN(a)){\n";
						$this->salida .= "formulario.peso$pfj.value='';\n";
						$this->salida .= "formulario.peso$pfj.focus();\n";
						$this->salida .= "}\n";

						$this->salida .= "} else {\n";
						$this->salida .= "formulario.dosis_total$pfj.value=c;\n";
						$this->salida .= "}\n";
						$this->salida .= "}\n";
						$this->salida .= "</script>\n";
				//fin de la funcion
          }
*/
//posologia-dosis
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td width=\"20%\"align=\"left\" >DOSIS</td>";
				$this->salida.="<td width=\"60%\" align = left >";
				$this->salida.="<table>";
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td  width=\"10%\" class=".$this->SetStyle("dosis")." align = left >DOSIS</td>";
				if (($_REQUEST['dosis'.$pfj])  == '')
        	{
						$this->salida.="<td width=\"15%\" align='left' ><input type='text' class='input-text' size = 15 name = 'dosis$pfj'   value =\"".$datos[0][dosis]."\"></td>" ;
					}
				else
					{
  					$this->salida.="<td width=\"15%\" align='left' ><input type='text' class='input-text' size = 15 name = 'dosis$pfj'   value =\"".$_REQUEST['dosis'.$pfj]."\"></td>" ;
					}

//unidades de dosificacion
				$this->salida.="<td width=\"35%\" class=".$this->SetStyle("unidad_dosis")." align = left >";
				//si no trae unidad de dosificacion segun la forma del producto pinta combo de vias interactivo

				if	(empty($datos[0][unidad_dosificacion_forma]))
				{
					$this->salida.=$javita;
							//este es el if nuevo que coloque para cargar unidades
								if ((sizeof($via_admon)>1))
								{
									$ComboUnidadDosis ="<select size = 1 name = 'unidad_dosis$pfj'  class =\"select\">";
									$unidadesViaAdministracion = $this->GetunidadesViaAdministracion($datos[0][via_administracion_id]);
									$ComboUnidadDosis.="<option value = '-1' selected >--Seleccione--</option>";
									for($i=0;$i<sizeof($unidadesViaAdministracion);$i++)
									{
										if($datos[0][unidad_dosificacion]==$unidadesViaAdministracion[$i][unidad_dosificacion])
										{
											$ComboUnidadDosis.="<option selected value = '".$unidadesViaAdministracion[$i][unidad_dosificacion]."'>".$unidadesViaAdministracion[$i][unidad_dosificacion]."</option>";
										}
										else
										{
											$ComboUnidadDosis.="<option value = '".$unidadesViaAdministracion[$i][unidad_dosificacion]."'>".$unidadesViaAdministracion[$i][unidad_dosificacion]."</option>";
										}
									}
									$ComboUnidadDosis.="</select>";
								}
							//fin del evento nuevo
				}
				$this->salida.="$ComboUnidadDosis";
				$this->salida.="</td>";
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";

//horario
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td width=\"20%\"align=\"left\" >FRECUENCIA</td>";
				$this->salida.="<td width=\"60%\" align = left >";
				$this->salida.="<table border = 0 >";

				$vector_posologia= $this->Consulta_Solicitud_Medicamentos_Posologia($datos[0][codigo_producto], $datos[0][tipo_opcion_posologia_id], $datos[0][evolucion_id]);
//opcion 1
				$this->salida.="<tr class=\"modulo_list_claro\">";
				if (($_REQUEST['opcion'.$pfj] != '1') AND ($datos[0][	tipo_opcion_posologia_id	]!= '1'))
					{
						$this->salida.="<td width=\"10%\" class=".$this->SetStyle("opcion1")." align=\"left\" ><input type = radio name= 'opcion$pfj' value = 1>OPCION 1</td>";
					}
				else
					{
						$this->salida.="<td width=\"10%\" class=".$this->SetStyle("opcion1")." align=\"left\" ><input type = radio checked name= 'opcion$pfj' value = 1>OPCION 1</td>";
					}

				$this->salida.="<td width=\"50%\"align=\"left\" >";
				$this->salida.="<table>";
				$this->salida.="<tr class=\"modulo_list_claro\">";
				$this->salida.="<td width=\"10%\" align = left >CADA</td>";
				$cada_periocidad = $this->Cargar_Periocidad();
				$this->salida.="<td width=\"10%\" align = left >";
				$this->salida.="<select size = 1 name = 'periocidad$pfj'  class =\"select\">";
				$this->salida.="<option value = '-1' selected>-Seleccione-</option>";
				for($i=0;$i<sizeof($cada_periocidad);$i++)
				{
					if ((($_REQUEST['periocidad'.$pfj])  != $cada_periocidad[$i][periocidad_id]) AND ($cada_periocidad[$i][periocidad_id]!= $vector_posologia[0][periocidad_id]))
						{
							$this->salida.="<option value = ".$cada_periocidad[$i][periocidad_id].">".$cada_periocidad[$i][periocidad_id]."</option>";
						}
					else
						{
							$this->salida.="<option value = ".$cada_periocidad[$i][periocidad_id]." selected >".$cada_periocidad[$i][periocidad_id]."</option>";
						}
				}
				$this->salida.="</select>";
				$this->salida.="</td>";
				$this->salida.="<td width=\"30%\" align = 'left' >";
				$this->salida.="<select size = 1 name = 'tiempo$pfj'  class =\"select\">";
				$this->salida.="<option value = '-1' selected>-Seleccione-</option>";
				//opcion de minutos
				if ((($_REQUEST['tiempo'.$pfj])  != 'Min') AND ($vector_posologia[0][tiempo] != 'Min'))
					{
						$this->salida.="<option value = 'Min'>Min</option>";
					}
				else
					{
						$this->salida.="<option value = 'Min' selected >Min</option>";
					}
				//opcion de horas
				if ((($_REQUEST['tiempo'.$pfj])  != 'Hora(s)') AND ($vector_posologia[0][tiempo] != 'Hora(s)'))
					{
						$this->salida.="<option value = 'Hora(s)' >Hora(s)</option>";
					}
				else
					{
						$this->salida.="<option value = 'Hora(s)' selected >Hora(s)</option>";
					}
				//opcion de dias
				if ((($_REQUEST['tiempo'.$pfj])  != 'Dia(s)') AND ($vector_posologia[0][tiempo] != 'Dia(s)'))
					{
						$this->salida.="<option value = 'Dia(s)' >Dia(s)</option>";
					}
				else
					{
						$this->salida.="<option value = 'Dia(s)' selected>Dia(s)</option>";
					}
				//opcion de semanas
				if ((($_REQUEST['tiempo'.$pfj])  != 'Semana(s)') AND ($vector_posologia[0][tiempo] != 'Semana(s)'))
					{
						$this->salida.="<option value = 'Semana(s)' >Semana(s)</option>";
					}
				else
					{
						$this->salida.="<option value = 'Semana(s)' selected >Semana(s)</option>";
					}
				$this->salida.="</select>";
				$this->salida.="</td>";
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";

//OPCION 2
				$this->salida.="<tr class=\"modulo_list_claro\">";
				if (($_REQUEST['opcion'.$pfj] != '2') AND ($datos[0][	tipo_opcion_posologia_id	]!= '2'))
					{
						$this->salida.="<td width=\"10%\" class=".$this->SetStyle("opcion2")." align=\"left\" ><input type = radio name= 'opcion$pfj' value = 2>OPCION 2</td>";
					}
				else
					{
						$this->salida.="<td width=\"10%\" class=".$this->SetStyle("opcion2")." align=\"left\" ><input type = radio checked name= 'opcion$pfj' value = 2>OPCION 2</td>";
					}
				$this->salida.="<td width=\"50%\"align=\"left\" >";
				$this->salida.="<table>";
				$this->salida.="<tr class=\"modulo_list_claro\">";
				$horario = $this->horario();
				$this->salida.="<td class=".$this->SetStyle("durante")." width=\"20%\"align=\"left\" >&nbsp;</td>";
				$this->salida.="<td width=\"60%\" align = left >";
				$this->salida.="<select size = 1 name = 'duracion$pfj'  class =\"select\">";
				$this->salida.="<option value = -1 selected>-Seleccione-</option>";
				for($i=0;$i<sizeof($horario);$i++)
				{
						if ((($_REQUEST['duracion'.$pfj])  != $horario[$i][duracion_id]) AND ($vector_posologia[0][duracion_id] != $horario[$i][duracion_id]))
							{
								$this->salida.="<option value = ".$horario[$i][duracion_id].">".$horario[$i][descripcion]."</option>";
							}
						else
							{
								$this->salida.="<option value = ".$horario[$i][duracion_id]." selected >".$horario[$i][descripcion]."</option>";
							}
				}
				$this->salida.="</select>";
				$this->salida.="</td>";
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";

//opcion 3
				$this->salida.="<tr class=\"modulo_list_claro\">";
				if (($_REQUEST['opcion'.$pfj] != '3') AND ($datos[0][	tipo_opcion_posologia_id	]!= '3'))
					{
							$this->salida.="<td width=\"10%\" class=".$this->SetStyle("opcion3")." align=\"left\" ><input type = radio name= 'opcion$pfj' value = 3>OPCION 3</td>";
					}
				else
					{
							$this->salida.="<td width=\"10%\" class=".$this->SetStyle("opcion3")." align=\"left\" ><input type = radio checked name= 'opcion$pfj' value = 3>OPCION 3</td>";
					}
				$this->salida.="<td width=\"50%\"align=\"left\" >";
				$this->salida.="<table>";
				$this->salida.="<tr class=\"modulo_list_claro\">";
				if (($_REQUEST['momento'.$pfj] != '1') AND  ($vector_posologia[0][sw_estado_momento]!= '1'))
					{
						$this->salida.="<td width=\"15%\" align = left ><input type = radio name= 'momento$pfj' value = '1'>ANTES</td>";
					}
				else
					{
						$this->salida.="<td width=\"15%\" align = left ><input type = radio name= 'momento$pfj' checked value = '1'>ANTES</td>";
					}
				if (($_REQUEST['momento'.$pfj] != '2') AND  ($vector_posologia[0][sw_estado_momento]!= '2'))
					{
						$this->salida.="<td width=\"15%\" align = left ><input type = radio name= 'momento$pfj' value = '2'>DURANTE</td>";
					}
				else
					{
						$this->salida.="<td width=\"15%\" align = left ><input type = radio name= 'momento$pfj' checked value = '2'>DURANTE</td>";
					}
				if (($_REQUEST['momento'.$pfj] != '3') AND  ($vector_posologia[0][sw_estado_momento]!= '3'))
					{
						$this->salida.="<td width=\"20%\" align = left ><input type = radio name= 'momento$pfj' value = '3'>DESPUES</td>";
					}
				else
					{
						$this->salida.="<td width=\"20%\" align = left ><input type = radio name= 'momento$pfj' checked value = '3'>DESPUES</td>";
					}
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"modulo_list_claro\">";

				if (($_REQUEST['desayuno'.$pfj] != '1') AND ($vector_posologia[0][sw_estado_desayuno]!= '1'))
					{
						$this->salida.="<td  colspan = 3 width=\"50%\" align = left ><input type = checkbox name= 'desayuno$pfj' value = '1'>DESAYUNO</td>";
					}
				else
					{
						$this->salida.="<td  colspan = 3 width=\"50%\" align = left ><input type = checkbox name= 'desayuno$pfj' checked value = '1'>DESAYUNO</td>";
					}
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"modulo_list_claro\">";
				if (($_REQUEST['almuerzo'.$pfj] != '1') AND ($vector_posologia[0][sw_estado_almuerzo]!= '1'))
					{
						$this->salida.="<td  colspan = 3 width=\"50%\" align = left ><input type = checkbox name= 'almuerzo$pfj' value = '1'>ALMUERZO</td>";
					}
				else
					{
						$this->salida.="<td  colspan = 3 width=\"50%\" align = left ><input type = checkbox name= 'almuerzo$pfj' checked value = '1'>ALMUERZO</td>";
					}
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"modulo_list_claro\">";
				if (($_REQUEST['cena'.$pfj] != '1') AND ($vector_posologia[0][sw_estado_cena]!= '1'))
					{
						$this->salida.="<td  colspan = 3 width=\"50%\" align = left ><input type = checkbox name= 'cena$pfj' value = '1'>CENA</td>";
					}
				else
					{
						$this->salida.="<td  colspan = 3 width=\"50%\" align = left ><input type = checkbox name= 'cena$pfj' checked value = '1'>CENA</td>";
					}
				$this->salida.="</tr>";

				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";


//OPCION 4
				$this->salida.="<tr class=\"modulo_list_claro\">";
				if (($_REQUEST['opcion'.$pfj] != '4') AND ($datos[0][	tipo_opcion_posologia_id	]!= '4'))
						{
							$this->salida.="<td width=\"10%\" class=".$this->SetStyle("opcion4")." align=\"left\" ><input type = radio name= 'opcion$pfj' value = 4>OPCION 4</td>";
						}
				else
						{
							$this->salida.="<td width=\"10%\" class=".$this->SetStyle("opcion4")." align=\"left\" ><input type = radio checked name= 'opcion$pfj' value = 4>OPCION 4</td>";
						}
				$this->salida.="<td width=\"50%\"align=\"left\" >";
				$this->salida.="<table>";
				$this->salida.="<tr class=\"modulo_list_claro\">";
				$this->salida.="<td colspan = 8 width=\"50%\" align = left >HORA ESPECIFICA</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"modulo_list_claro\">";
				$hora_especifica = $_REQUEST['opH'.$pfj];

				if ((($hora_especifica[6])  != '06 am') AND empty($vector_posologia['06 am']))
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[6]' value = '06 am'>06</td>";
						}
				else
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox checked name= 'opH".$pfj."[6]' value = '06 am'>06</td>";
						}

				if ((($hora_especifica[9])  != '09 am') AND empty($vector_posologia['09 am']))
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[9]' value = '09 am'>09</td>";
						}
				else
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox checked name= 'opH".$pfj."[9]' value = '09 am'>09</td>";
						}

				if ((($hora_especifica[12])  != '12 pm') AND empty($vector_posologia['12 pm']))
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[12]' value = '12 pm'>12</td>";
						}
				else
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox checked name= 'opH".$pfj."[12]' value = '12 pm'>12</td>";
						}

				if ((($hora_especifica[15])  != '03 pm') AND empty($vector_posologia['03 pm']))
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[15]' value = '03 pm'>15</td>";
						}
				else
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox checked name= 'opH".$pfj."[15]' value = '03 pm'>15</td>";
						}

				if ((($hora_especifica[18])  != '06 pm') AND empty($vector_posologia['06 pm']))
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[18]' value = '06 pm'>18</td>";
						}
				else
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox checked name= 'opH".$pfj."[18]' value = '06 pm'>18</td>";
						}

				if ((($hora_especifica[21])  != '09 pm') AND empty($vector_posologia['09 pm']))
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[21]' value = '09 pm'>21</td>";
						}
				else
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox checked name= 'opH".$pfj."[21]' value = '09 pm'>21</td>";
						}

				if ((($hora_especifica[24])  != '00 am') AND empty($vector_posologia['00 am']))
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[24]' value = '00 am'>24</td>";
						}
				else
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[24]' value = '00 am'>24</td>";
						}

				if ((($hora_especifica[3])  != '03 am') AND empty($vector_posologia['03 am']))
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"15%\" align = left ><input type = checkbox name= 'opH".$pfj."[3]' value = '03 am'>03</td>";
						}
				else
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"15%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[3]' value = '03 am'>03</td>";
						}
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"modulo_list_claro\">";

				if ((($hora_especifica[7])  != '07 am') AND empty($vector_posologia['07 am']))
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[7]' value = '07 am'>07</td>";
						}
				else
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[7]' value = '07 am'>07</td>";
						}

				if ((($hora_especifica[10])  != '10 am') AND empty($vector_posologia['10 am']))
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[10]' value = '10 am'>10</td>";
						}
				else
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[10]' value = '10 am'>10</td>";
						}

				if ((($hora_especifica[13])  != '01 pm') AND empty($vector_posologia['01 pm']))
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[13]' value = '01 pm'>13</td>";
						}
				else
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[13]' value = '01 pm'>13</td>";
						}

				if ((($hora_especifica[16])  != '04 pm') AND empty($vector_posologia['04 pm']))
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[16]' value = '04 pm'>16</td>";
						}
				else
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[16]' value = '04 pm'>16</td>";
						}

				if ((($hora_especifica[19])  != '07 pm') AND empty($vector_posologia['07 pm']))
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[19]' value = '07 pm'>19</td>";
						}
				else
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[19]' value = '07 pm'>19</td>";
						}

				if ((($hora_especifica[22])  != '10 pm') AND empty($vector_posologia['10 pm']))
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[22]' value = '10 pm'>22</td>";
						}
				else
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[22]' value = '10 pm'>22</td>";
						}

				if ((($hora_especifica[1])  != '01 am') AND empty($vector_posologia['01 am']))
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[1]' value = '01 am'>01</td>";
						}
				else
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[1]' value = '01 am'>01</td>";
						}

				if ((($hora_especifica[4])  != '04 am') AND empty($vector_posologia['04 am']))
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"15%\" align = left ><input type = checkbox name= 'opH".$pfj."[4]' value = '04 am'>04</td>";
						}
				else
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"15%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[4]' value = '04 am'>04</td>";
						}
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"modulo_list_claro\">";

				if ((($hora_especifica[8])  != '08 am') AND empty($vector_posologia['08 am']))
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[8]' value = '08 am'>08</td>";
						}
				else
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[8]' value = '08 am'>08</td>";
						}
				if ((($hora_especifica[11])  != '11 am') AND empty($vector_posologia['11 am']))
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[11]' value = '11 am'>11</td>";
						}
				else
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[11]' value = '11 am'>11</td>";
						}

				if ((($hora_especifica[14])  != '02 pm') AND empty($vector_posologia['02 pm']))
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[14]' value = '02 pm'>14</td>";
						}
				else
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[14]' value = '02 pm'>14</td>";
						}
				if ((($hora_especifica[17])  != '05 pm') AND empty($vector_posologia['05 pm']))
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[17]' value = '05 pm'>17</td>";
						}
				else
						{
							$this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[17]' value = '05 pm'>17</td>";
						}
				if ((($hora_especifica[20])  != '08 pm') AND empty($vector_posologia['08 pm']))
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[20]' value = '08 pm'>20</td>";
						}
				else
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[20]' value = '08 pm'>20</td>";
						}
				if ((($hora_especifica[23])  != '11 pm') AND empty($vector_posologia['11 pm']))
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[23]' value = '11 pm'>23</td>";
						}
				else
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[23]' value = '11 pm'>23</td>";
						}
				if ((($hora_especifica[2])  != '02 am') AND empty($vector_posologia['02 am']))
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[2]' value = '02 am'>02</td>";
						}
				else
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[2]' value = '02 am'>02</td>";
						}
				if ((($hora_especifica[5])  != '05 am') AND empty($vector_posologia['05 am']))
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"15%\" align = left ><input type = checkbox name= 'opH".$pfj."[5]' value = '05 am'>05</td>";
						}
				else
						{
							$this->salida.="<td class =label_error colspan = 1 width=\"15%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[5]' value = '05 am'>05</td>";
						}
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";

//OPCION 5
				$this->salida.="<tr class=\"modulo_list_claro\">";
				if (($_REQUEST['opcion'.$pfj] != '5') AND ($datos[0][	tipo_opcion_posologia_id]!= '5'))
				{
					$this->salida.="<td width=\"10%\" class=".$this->SetStyle("opcion5")." align=\"left\" ><input type = radio name= 'opcion$pfj' value = 5>OPCION 5</td>";
				}
				else
				{
					$this->salida.="<td width=\"10%\" class=".$this->SetStyle("opcion5")." align=\"left\" ><input type = radio checked name= 'opcion$pfj' value = 5>OPCION 5</td>";
				}
				$this->salida.="<td width=\"50%\"align=\"left\" >";
				$this->salida.="<table>";
				$this->salida.="<tr class=\"modulo_list_claro\">";
				$this->salida.="<td  colspan = 3 width=\"50%\" align = left >DESCRIBA LA FRECUENCIA PARA EL SUMINISTRO DEL MEDICAMENTO</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"modulo_list_claro\">";

				if (($_REQUEST['frecuencia_suministro'.$pfj])  == '')
					{
						$this->salida.="<td colspan = 3 width=\"50%\" align='left' ><textarea style = \"width:80%\" class='textarea' name = 'frecuencia_suministro$pfj' cols = 60 rows = 3>".$vector_posologia[0][frecuencia_suministro]."</textarea></td>" ;

					}
				else
					{
						$this->salida.="<td colspan = 3 width=\"50%\" align='left' ><textarea style = \"width:80%\" class='textarea' name = 'frecuencia_suministro$pfj' cols = 60 rows = 3>".$_REQUEST['frecuencia_suministro'.$pfj]."</textarea></td>" ;
					}
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";

				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";

//cantidad
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td width=\"20%\"align=\"left\" >CANTIDAD</td>";
				$this->salida.="<td width=\"60%\" align = left >";
				$this->salida.="<table>";
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td class=".$this->SetStyle("cantidad")." width=\"5%\" align = left >CANTIDAD</td>";
				if (($_REQUEST['cantidad'.$pfj])  == '')
				{
						$this->salida.="<td  width=\"5%\" align='left' ><input type='text' class='input-text' size = 5 name = 'cantidad$pfj'   value =\"".$datos[0][cantidad]."\"></td>" ;
				}
				else
				{
						$this->salida.="<td  width=\"5%\" align='left' ><input type='text' class='input-text' size = 5 name = 'cantidad$pfj'   value =\"".$_REQUEST['cantidad'.$pfj]."\"></td>" ;
				}

				$frase = ' ';
				if ($datos[0][contenido_unidad_venta]!='')
				{
							$frase = ' por ';
				}
				$this->salida.="<td width=\"30%\" align='left' ><input type='text' class='input-text' readonly size = 30 name = 'unidad$pfj'   value = '".$datos[0][descripcion]."".$frase."".$datos[0][contenido_unidad_venta]."'></td>" ;
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";
//fin de cantidad

				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td width=\"15%\" align=\"left\" >OBSERVACIONES E INDICACION DE SUMINISTRO</td>";

				if (($_REQUEST['observacion'.$pfj])  == '')
				{
					$this->salida.="<td width=\"65%\"align='center'><textarea style = \"width:80%\" class='textarea' name = 'observacion$pfj' cols = 60 rows = 3>".$datos[0][observacion]."</textarea></td>" ;
				}
				else
				{
					$this->salida.="<td width=\"50%\"align='center'><textarea style = \"width:80%\" class='textarea' name = 'observacion$pfj' cols = 60 rows = 3>".$_REQUEST['observacion'.$pfj]."</textarea></td>" ;
				}
				$this->salida.="</tr>";

				if($datos[0][item] == 'NO POS')
				{
					$this->salida.="<tr class=\"$estilo\">";
					if (($_REQUEST['no_pos_paciente'.$pfj] != '1') AND  ($datos[0][sw_paciente_no_pos]!= '1'))
					{
							$this->salida.="  <td class=label_error colspan = 2 align=\"center\" width=\"5%\"><input type = \"checkbox\" name= 'no_pos_paciente$pfj' value = 1 >FORMULACION NO POS A PETICION DEL PACIENTE</td>";
					}
					else
					{
							$this->salida.="  <td class=label_error colspan = 2 align=\"center\" width=\"5%\"><input type = \"checkbox\" name= 'no_pos_paciente$pfj' checked value = 1 class=label_error>FORMULACION NO POS A PETICION DEL PACIENTE</td>";
					}
					$this->salida.="</tr>";
				}

				$this->salida.="</table><br>";
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"0\"><tr>";
				$this->salida .= "<td   align=\"center\"><input class=\"input-submit\" name= 'guardar_formula$pfj' type=\"submit\" value=\"MODIFICAR FORMULA\"></td>";

				$this->salida .= "</form>";
				$accion3=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false, array('accion'.$pfj=>''));
				$this->salida .= "<form name=\"forma\" action=\"$accion3\" method=\"post\">";
				$this->salida .= "<td   align=\"center\"><input class=\"input-submit\" name= 'cancelar$pfj' type=\"submit\" value=\"VOLVER\"></form></td>";
				$this->salida.="</tr></table>";
//................fin de la modificacion
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
}


//clzc - si
function frmConsulta()
{
		$pfj=$this->frmPrefijo;
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
		$this->salida .= "<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
		$vector1=$this->Consulta_Solicitud_Medicamentos();
		if($vector1)
		{
			$this->salida.="<table  align=\"center\" border=\"0\" width=\"100%\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida.="</table>";

			$this->salida.="<table  align=\"center\" border=\"1\" width=\"100%\" class=\"hc_table_submodulo_list\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="<td align=\"center\" colspan=\"5\">MEDICAMENTOS AMBULATORIOS SOLICITADOS</td>";
			$this->salida.="</tr>";

			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td width=\"7%\">CODIGO</td>";
			$this->salida.="  <td width=\"30%\">PRODUCTO</td>";
			$this->salida.="  <td colspan=\"3\" width=\"43%\">PRINCIPIO ACTIVO</td>";
			$this->salida.="</tr>";
			for($i=0;$i<sizeof($vector1);$i++)
				{
								if( $i % 2){ $estilo='modulo_list_claro';}
								else {$estilo='modulo_list_oscuro';}
								$this->salida.="<tr class=\"$estilo\">";
								if($vector1[$i][item] == 'NO POS')
									{
											$this->salida.="  <td ROWSPAN = 4 align=\"center\" width=\"7%\">".$vector1[$i][codigo_producto]."<BR>NO_POS</td>";
									}
								else
									{
											$this->salida.="  <td ROWSPAN = 3 align=\"center\" width=\"7%\">".$vector1[$i][codigo_producto]."</td>";
									}
								$this->salida.="  <td align=\"center\" width=\"30%\">".$vector1[$i][producto]."</td>";
								$this->salida.="  <td colspan=\"3\" align=\"center\" width=\"43%\">".$vector1[$i][principio_activo]."</td>";
								$this->salida.="</tr>";


								$this->salida.="<tr class=\"$estilo\">";
								$this->salida.="<td colspan = 4>";
								$this->salida.="<table>";

								$this->salida.="<tr class=\"$estilo\">";
										$this->salida.="  <td colspan = 3 align=\"left\" width=\"9%\">Via de Administracion: ".$vector1[$i][via]."</td>";
								$this->salida.="</tr>";

								$this->salida.="<tr class=\"$estilo\">";
								$this->salida.="  <td align=\"left\" width=\"9%\">Dosis:</td>";
								$e=$vector1[$i][dosis]/floor($vector1[$i][dosis]);
								if($e==1)
								{
									$this->salida.="  <td align=\"left\" width=\"14%\">".floor($vector1[$i][dosis])."  ".$vector1[$i][unidad_dosificacion]."</td>";
								}
								else
								{
									$this->salida.="  <td align=\"left\" width=\"14%\">".$vector1[$i][dosis]."  ".$vector1[$i][unidad_dosificacion]."</td>";
								}

								$vector_posologia= $this->Consulta_Solicitud_Medicamentos_Posologia($vector1[$i][codigo_producto], $vector1[$i][tipo_opcion_posologia_id], $vector1[$i][evolucion_id]);

//pintar formula para opcion 1
								if($vector1[$i][tipo_opcion_posologia_id]== 1)
								{
									$this->salida.="  <td align=\"left\" width=\"50%\">cada ".$vector_posologia[0][periocidad_id]." ".$vector_posologia[0][tiempo]."</td>";
								}

//pintar formula para opcion 2
								if($vector1[$i][tipo_opcion_posologia_id]== 2)
								{
									$this->salida.="  <td align=\"left\" width=\"50%\">".$vector_posologia[0][descripcion]."</td>";
								}

//pintar formula para opcion 3
								if($vector1[$i][tipo_opcion_posologia_id]== 3)
								{
										$momento = '';
										if($vector_posologia[0][sw_estado_momento]== '1')
										{
											$momento = 'antes de ';
										}
										else
										{
											if($vector_posologia[0][sw_estado_momento]== '2')
											{
												$momento = 'durante ';
											}
											else
											{
												if($vector_posologia[0][sw_estado_momento]== '3')
													{
														$momento = 'despues de ';
													}
											}
										}
										$Cen = $Alm = $Des= '';
										$cont= 0;
										$conector = '  ';
										$conector1 = '  ';
										if($vector_posologia[0][sw_estado_desayuno]== '1')
										{
											$Des = $momento.'el Desayuno';
											$cont++;
										}
										if($vector_posologia[0][sw_estado_almuerzo]== '1')
										{
											$Alm = $momento.'el Almuerzo';
											$cont++;
										}
										if($vector_posologia[0][sw_estado_cena]== '1')
										{
											$Cen = $momento.'la Cena';
											$cont++;
										}
										if ($cont== 2)
										{
											$conector = ' y ';
											$conector1 = '  ';
										}
										if ($cont== 1)
										{
											$conector = '  ';
											$conector1 = '  ';
										}
										if ($cont== 3)
										{
											$conector = ' , ';
											$conector1 = ' y ';
										}
										$this->salida.="  <td align=\"left\" width=\"50%\">".$Des."".$conector."".$Alm."".$conector1."".$Cen."</td>";
								}

//pintar formula para opcion 4
								if($vector1[$i][tipo_opcion_posologia_id]== 4)
								{
									$conector = '  ';
									$frecuencia='';
									$j=0;
									foreach ($vector_posologia as $k => $v)
									{
										if ($j+1 ==sizeof($vector_posologia))
										{
											$conector = '  ';
										}
										else
										{
												if ($j+2 ==sizeof($vector_posologia))
													{
														$conector = ' y ';
													}
												else
													{
														$conector = ' - ';
													}
										}
										$frecuencia = $frecuencia.$k.$conector;
										$j++;
									}
									$this->salida.="  <td align=\"left\" width=\"50%\">a la(s): $frecuencia</td>";
								}

//pintar formula para opcion 5
								if($vector1[$i][tipo_opcion_posologia_id]== 5)
								{
									$this->salida.="  <td align=\"left\" width=\"50%\">".$vector_posologia[0][frecuencia_suministro]."</td>";
								}
								$this->salida.="</tr>";

								$this->salida.="<tr class=\"$estilo\">";
								$this->salida.="  <td align=\"left\" width=\"9%\">Cantidad:</td>";
								$e=$vector1[$i][cantidad]/floor($vector1[$i][cantidad]);
								if ($vector1[$i][contenido_unidad_venta])
								{
									if($e==1)
									{
										$this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".floor($vector1[$i][cantidad])." ".$vector1[$i][descripcion]." por ".$vector1[$i][contenido_unidad_venta]."</td>";
									}
									else
									{
										$this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".$vector1[$i][cantidad]." ".$vector1[$i][descripcion]." por ".$vector1[$i][contenido_unidad_venta]."</td>";
									}
								}
								else
								{
									if($e==1)
									{
										$this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".$vector1[$i][cantidad]." ".$vector1[$i][descripcion]."</td>";
									}
									else
									{
										$this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".$vector1[$i][cantidad]." ".$vector1[$i][descripcion]."</td>";
									}
								}
								$this->salida.="</tr>";

								$this->salida.="</table>";
								$this->salida.="</td>";
								$this->salida.="</tr>";

								$this->salida.="<tr class=\"$estilo\">";
								$this->salida.="<td colspan = 4 class=\"$estilo\">";
								$this->salida.="<table>";
								$this->salida.="<tr class=\"$estilo\">";
								$this->salida.="  <td align=\"left\" width=\"4%\">Observacion:</td>";
								$this->salida.="  <td align=\"left\" width=\"69%\">".$vector1[$i][observacion]."</td>";
								$this->salida.="<tr class=\"$estilo\">";
								$this->salida.="</table>";
								$this->salida.="</td>";
								$this->salida.="</tr>";

								if($vector1[$i][item] == 'NO POS')
									{
										$this->salida.="<tr class=\"$estilo\">";

										$accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Consultar_Justificacion', 'codigo_p'.$pfj => $vector1[$i][codigo_producto], 'product'.$pfj => $vector1[$i][producto], 'principio_a'.$pfj => $vector1[$i][principio_activo], 'via'.$pfj => $vector1[$i][via],'dosis'.$pfj => $vector1[$i][dosis], 'unidad'.$pfj => $vector1[$i][unidad_dosificacion], 'canti'.$pfj => $vector1[$i][cantidad],'desc'.$pfj => $vector1[$i][descripcion],'contenido_u_v'.$pfj => $vector1[$i][contenido_unidad_venta], 'obs'.$pfj => $vector1[$i][observacion]));
										if($vector1[$i][sw_paciente_no_pos] != '1')
										{
											$this->salida.="  <td colspan = 4 align=\"center\" width=\"63%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/auditoria.png\" border='0'> VER JUSTIFICACION</a></td>";
										}
										else
										{
											$this->salida.="  <td class = label_error colspan = 4 align=\"center\" width=\"63%\">MEDICAMENTO NO POS FORMULADO A PETICION DEL PACIENTE</td>";
										}
										$this->salida.="</tr>";
									}

						}
						$this->salida.="</table><br>";
				}
				$this->salida .= "</form>";

}


	function frmHistoria()
	{
		$pfj=$this->frmPrefijo;
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
		$salida .= "<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
		$vector1=$this->Consulta_Solicitud_Medicamentos();
		if($vector1)
		{
			/*$salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";
			$salida .= $this->SetStyle("MensajeError");
			$salida.="</table>";*/

			$salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";
			$salida.="<tr class=\"modulo_table_title\">";
			$salida.="  <td align=\"center\" colspan=\"5\">MEDICAMENTOS AMBULATORIOS SOLICITADOS</td>";
			$salida.="</tr>";

			$salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$salida.="  <td width=\"7%\">CODIGO</td>";
			$salida.="  <td width=\"30%\">PRODUCTO</td>";
			$salida.="  <td colspan=\"3\" width=\"43%\">PRINCIPIO ACTIVO</td>";
			$salida.="</tr>";
			for($i=0;$i<sizeof($vector1);$i++)
				{
						if( $i % 2){ $estilo='modulo_list_claro';}
						else {$estilo='modulo_list_oscuro';}
						$salida.="<tr class=\"$estilo\">";
						if($vector1[$i][item] == 'NO POS')
							{
									$salida.="  <td ROWSPAN = 4 align=\"center\" width=\"7%\">".$vector1[$i][codigo_producto]."<BR>NO_POS</td>";
							}
						else
							{
									$salida.="  <td ROWSPAN = 3 align=\"center\" width=\"7%\">".$vector1[$i][codigo_producto]."</td>";
							}
								$salida.="  <td align=\"center\" width=\"30%\">".$vector1[$i][producto]."</td>";
								$salida.="  <td colspan=\"3\" align=\"center\" width=\"43%\">".$vector1[$i][principio_activo]."</td>";
								$salida.="</tr>";


								$salida.="<tr class=\"$estilo\">";
								$salida.="<td colspan = 4>";
								$salida.="<table>";

								$salida.="<tr class=\"$estilo\">";
										$salida.="  <td colspan = 3 align=\"left\" width=\"9%\">Via de Administracion: ".$vector1[$i][via]."</td>";
								$salida.="</tr>";

								$salida.="<tr class=\"$estilo\">";
								$salida.="  <td align=\"left\" width=\"9%\">Dosis:</td>";
								$e=$vector1[$i][dosis]/floor($vector1[$i][dosis]);
								if($e==1)
								{
									$salida.="  <td align=\"left\" width=\"14%\">".floor($vector1[$i][dosis])."  ".$vector1[$i][unidad_dosificacion]."</td>";
								}
								else
								{
									$salida.="  <td align=\"left\" width=\"14%\">".$vector1[$i][dosis]."  ".$vector1[$i][unidad_dosificacion]."</td>";
								}

								$vector_posologia= $this->Consulta_Solicitud_Medicamentos_Posologia($vector1[$i][codigo_producto], $vector1[$i][tipo_opcion_posologia_id], $vector1[$i][evolucion_id]);

//pintar formula para opcion 1
								if($vector1[$i][tipo_opcion_posologia_id]== 1)
								{
									$salida.="  <td align=\"left\" width=\"50%\">cada ".$vector_posologia[0][periocidad_id]." ".$vector_posologia[0][tiempo]."</td>";
								}

//pintar formula para opcion 2
								if($vector1[$i][tipo_opcion_posologia_id]== 2)
								{
									$salida.="  <td align=\"left\" width=\"50%\">".$vector_posologia[0][descripcion]."</td>";
								}

//pintar formula para opcion 3
								if($vector1[$i][tipo_opcion_posologia_id]== 3)
								{
										$momento = '';
										if($vector_posologia[0][sw_estado_momento]== '1')
										{
											$momento = 'antes de ';
										}
										else
										{
											if($vector_posologia[0][sw_estado_momento]== '2')
											{
												$momento = 'durante ';
											}
											else
											{
												if($vector_posologia[0][sw_estado_momento]== '3')
													{
														$momento = 'despues de ';
													}
											}
										}
										$Cen = $Alm = $Des= '';
										$cont= 0;
										$conector = '  ';
										$conector1 = '  ';
										if($vector_posologia[0][sw_estado_desayuno]== '1')
										{
											$Des = $momento.'el Desayuno';
											$cont++;
										}
										if($vector_posologia[0][sw_estado_almuerzo]== '1')
										{
											$Alm = $momento.'el Almuerzo';
											$cont++;
										}
										if($vector_posologia[0][sw_estado_cena]== '1')
										{
											$Cen = $momento.'la Cena';
											$cont++;
										}
										if ($cont== 2)
										{
											$conector = ' y ';
											$conector1 = '  ';
										}
										if ($cont== 1)
										{
											$conector = '  ';
											$conector1 = '  ';
										}
										if ($cont== 3)
										{
											$conector = ' , ';
											$conector1 = ' y ';
										}
										$salida.="  <td align=\"left\" width=\"50%\">".$Des."".$conector."".$Alm."".$conector1."".$Cen."</td>";
								}

//pintar formula para opcion 4
								if($vector1[$i][tipo_opcion_posologia_id]== 4)
								{
									$conector = '  ';
									$frecuencia='';
									$j=0;
									foreach ($vector_posologia as $k => $v)
									{
										if ($j+1 ==sizeof($vector_posologia))
										{
											$conector = '  ';
										}
										else
										{
												if ($j+2 ==sizeof($vector_posologia))
													{
														$conector = ' y ';
													}
												else
													{
														$conector = ' - ';
													}
										}
										$frecuencia = $frecuencia.$k.$conector;
										$j++;
									}
									$salida.="  <td align=\"left\" width=\"50%\">a la(s): $frecuencia</td>";
								}

//pintar formula para opcion 5
								if($vector1[$i][tipo_opcion_posologia_id]== 5)
								{
									$salida.="  <td align=\"left\" width=\"50%\">".$vector_posologia[0][frecuencia_suministro]."</td>";
								}
								$salida.="</tr>";

								$salida.="<tr class=\"$estilo\">";
								$salida.="  <td align=\"left\" width=\"9%\">Cantidad:</td>";
								$e=$vector1[$i][cantidad]/floor($vector1[$i][cantidad]);
								if ($vector1[$i][contenido_unidad_venta])
								{
									if($e==1)
									{
										$salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".floor($vector1[$i][cantidad])." ".$vector1[$i][descripcion]." por ".$vector1[$i][contenido_unidad_venta]."</td>";
									}
									else
									{
										$salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".$vector1[$i][cantidad]." ".$vector1[$i][descripcion]." por ".$vector1[$i][contenido_unidad_venta]."</td>";
									}
								}
								else
								{
									if($e==1)
									{
										$salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".$vector1[$i][cantidad]." ".$vector1[$i][descripcion]."</td>";
									}
									else
									{
										$salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".$vector1[$i][cantidad]." ".$vector1[$i][descripcion]."</td>";
									}
								}
								$salida.="</tr>";

								$salida.="</table>";
								$salida.="</td>";
								$salida.="</tr>";

								$salida.="<tr class=\"$estilo\">";
								$salida.="<td colspan = 4 class=\"$estilo\">";
								$salida.="<table>";
								$salida.="<tr class=\"$estilo\">";
								$salida.="  <td align=\"left\" width=\"4%\">Observacion:</td>";
								$salida.="  <td align=\"left\" width=\"69%\">".$vector1[$i][observacion]."</td>";
								$salida.="<tr class=\"$estilo\">";
								$salida.="</table>";
								$salida.="</td>";
								$salida.="</tr>";

								if($vector1[$i][item] == 'NO POS')
									{
										$salida.="<tr class=\"$estilo\">";

										$accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Consultar_Justificacion', 'codigo_p'.$pfj => $vector1[$i][codigo_producto], 'product'.$pfj => $vector1[$i][producto], 'principio_a'.$pfj => $vector1[$i][principio_activo], 'via'.$pfj => $vector1[$i][via],'dosis'.$pfj => $vector1[$i][dosis], 'unidad'.$pfj => $vector1[$i][unidad_dosificacion], 'canti'.$pfj => $vector1[$i][cantidad],'desc'.$pfj => $vector1[$i][descripcion],'contenido_u_v'.$pfj => $vector1[$i][contenido_unidad_venta], 'obs'.$pfj => $vector1[$i][observacion]));
										if($vector1[$i][sw_paciente_no_pos] != '1')
										{
											$salida.="  <td colspan = 4 align=\"center\" width=\"63%\"><img src=\"".GetThemePath()."/images/auditoria.png\" border='0'> VER JUSTIFICACION</td>";//<a href='$accion1'></a>
										}
										else
										{
											$salida.="  <td class = label_error colspan = 4 align=\"center\" width=\"63%\">MEDICAMENTO NO POS FORMULADO A PETICION DEL PACIENTE</td>";
										}
										$salida.="</tr>";
									}

						}
						$salida.="</table><br>";
				}
				$salida .= "</form>";
				return $salida;
	}

}
?>
