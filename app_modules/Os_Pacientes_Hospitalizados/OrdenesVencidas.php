<?php

/**
 * $Id: OrdenesVencidas.php,v 1.1 2005/07/05 20:06:31 duvan Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: Mostrar ordenes de servicios vencidas.
 */

echo "<html>";
echo "<head>";
echo "<title>CLASIFICACION</title>";
echo "</head>";

  $VISTA='HTML';
	$_ROOT='../../';
	include_once $_ROOT.'includes/enviroment.inc.php';
	include_once $_ROOT.'includes/api.inc.php';
	$filename="themes/$VISTA/" . GetTheme() . "/module_theme.php";
  IncludeFile($filename);
	print (ReturnHeader('BUSCADOR'));
	print(ReturnBody());
	list($dbconn) = GetDBconn();

			$salida.= ThemeAbrirTabla('ORDEN DE SERVICIOS MEDICOS VENCIDAS');
			unset($conteo);
			$vector2=TraerOrdenesServicio_estado2($_REQUEST['tipo_id'],$_REQUEST['paciente']);
			if(is_array($vector2))
			{
					$salida.="<BR><table  align=\"center\" border=\"0\" width=\"70%\">";
					$salida.="<tr class=\"modulo_table_title\">";
					if($_SESSION['LABORATORIO']['SW_HONORARIO']==1)
					{
						$salida.="  <td align=\"left\" colspan=\"7\">ORDENES PAGADAS</td>";
					}
					else
					{
						$salida.="  <td align=\"left\" colspan=\"6\">ORDENES PAGADAS</td>";
					}
					$salida.="</tr>";
					$salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$salida.="  <td width=\"8%\">ITEM</td>";
					$salida.="  <td width=\"10%\">CANTIDAD</td>";
					$salida.="  <td width=\"10%\">CARGO</td>";
					$salida.="  <td width=\"40%\">DESCRIPCION</td>";
					$salida.="  <td width=\"20%\">VENCIMIENTO</td>";
					if($_SESSION['LABORATORIO']['SW_HONORARIO']==1)
					{$salida.="  <td width=\"20%\">MEDICO</td>";}
					$salida.="  <td width=\"8%	\"></td>";

					$conteo=sizeof($vector2); //para saber si es uno solo y si viene vencido entonces no creamos el
					//boton cumplir...
					for($i=0;$i<sizeof($vector2);$i++)
					{
							if( $i % 2){ $estilo='modulo_list_claro';}
							else {$estilo='modulo_list_oscuro';}
							$vecimiento=$vector2[$i][fecha_vencimiento];
							$arr_fecha=explode(" ",$vecimiento);
							$salida.="<tr class=$estilo>";
							$salida.="  <td align=\"center\" >".$vector2[$i][numero_orden_id]."</td>";
							$salida.="  <td align=\"center\" >".$vector2[$i][cantidad]."</td>";
							$salida.="  <td align=\"center\" >".$vector2[$i][cargoi]."</td>";
							$salida.="  <td align=\"center\" >".$vector2[$i][des1]."</td>";
							if(strtotime($arr_fecha[0]) >= strtotime(date("Y-m-d")))
							{
								$salida.="  <td  align=\"center\" >$arr_fecha[0]</td>";
//echo "---->".$_SESSION['LABORATORIO']['SW_HONORARIO'];

							/*	if($_SESSION['LABORATORIO']['SW_HONORARIO']==1)
								{
											$datos=ComboProfesionales();
											if(is_array($datos))
											{
														$salida.="<td align=\"center\" ><select name=profe[$i] class='select'>";
														$salida.="<option value=-1>----Seleccione----</option>";
														for($m=0;$m<sizeof($datos);$m++)
														{
			
																$salida.="<option value=".$datos[$m][usuario_id].">".$datos[$m][nombre]."</option>";
														}
														$salida.="</select></td>";
											}
											else
											{
												$salida.="  <td  align=\"center\" ></td>";
											}
								}*/

								$salida.="  <td  align=\"center\" ></td>";
								if($vector2[$i][os_tipo_solicitud_id]=='CIT')//quiere decir q es una cita..
								{
									$salida.="  <td><label class='label_mark'>PAGADO</label></td>";
								}
								else
								{
									$salida.="  <td  align=\"center\"></td>";
								}
								$sw_conteo=true;//esto se activa si no esta vencido y lo comparamos a ver si es un solo
								//registro para determinar que solo salga como informacion y no con boton de cumplir.
							}
							else
							{
								$salida.="  <td  align=\"center\" ><label class='label_mark'>VENCIDO</label></td>";
								$salida.="  <td align='center'><img src=\"".GetThemePath()."/images/delete.gif\"></td>";
								if(empty($sw_conteo))
								{$sw_conteo=false;}
								if($_SESSION['LABORATORIO']['SW_HONORARIO']==1)
								{
									$salida.="  <td  align=\"center\" ></td>";
								}

							}
							$salida.="</tr>";
					}
						$salida.="</table>";


			}else{$conteo++;}
			
			
			unset($vector3);
			$vector3=TraerOrdenesServicio_estado3($_REQUEST['tipo_id'],$_REQUEST['paciente']);
			if(is_array($vector3))
			{



					//$salida .= "           <form name=\"formo\" action=\"".ModuloGetURL('app','Os_Atencion','user','BuscarCuentaActiva',array('id_tipo'=>$tipo,'nom'=>urlencode($nom),'id'=>$id,'plan_id'=>$vector[$k][plan_id]))."\" method=\"post\">";
					$salida.="<BR><table  align=\"center\" border=\"0\" width=\"70%\">";
					$salida.="<tr class=\"modulo_table_title\">";
					if($_SESSION['LABORATORIO']['SW_HONORARIO']==1)
					{	$salida.="  <td align=\"left\" colspan=\"8\">PARA ATENCION</td>";}
					else
					{$salida.="  <td align=\"left\" colspan=\"7\">PARA ATENCION</td>";}
					$salida.="</tr>";
					$salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$salida.="  <td width=\"8%\">ITEM</td>";
					$salida.="  <td width=\"5%\">CANT</td>";
					$salida.="  <td width=\"10%\">CARGO</td>";
					$salida.="  <td width=\"40%\">DESCRIPCION</td>";
					if($_SESSION['LABORATORIO']['SW_HONORARIO']==1)
					{$salida.="  <td width=\"20%\">ASIGNADO A</td>";}
					$salida.="  <td width=\"20%\">VENCIMIENTO</td>";
					$salida.="  <td width=\"8%\"></td>";
					$salida.="  <td width=\"5%\">Sel</td>";
					unset($_SESSION['OS_ATENCION']['NUMERO_C']);
					for($i=0;$i<sizeof($vector3);$i++)
					{
							if( $i % 2){ $estilo='modulo_list_claro';$color='#DDDDDD';}
							else {$estilo='modulo_list_oscuro';$color='#CCCCCC';}

							$numero=TraerNumeroCumplimiento($vector3[$i][numero_orden_id]);
						//	echo "<br>".print_r($numero);
							//si este $numero llega vacio es por q a la tabla os_cumplimiento_detalle
							//le han borrado el numero_orden_id..

							if(!$_SESSION['OS_ATENCION']['NUMERO_C'])
							{
								$_SESSION['OS_ATENCION']['NUMERO_C']=$numero['numero_cumplimiento'];
								$sw_imprimir=0;
								//aqui va el cambio <duvan>
							}
							else
							{
								//echo 'session: '.$_SESSION['OS_ATENCION']['NUMERO_C'];
								//echo 'var: '.$numero['numero_cumplimiento'];
								if($_SESSION['OS_ATENCION']['NUMERO_C']==$numero['numero_cumplimiento'])
								{
										$_SESSION['OS_ATENCION']['NUMERO_C']=$numero['numero_cumplimiento'];
										$sw_imprimir=0;
								}
								else
								{//echo "->".$_SESSION['OS_ATENCION']['NUMERO_C'];
											if($_SESSION['LABORATORIO']['SW_HONORARIO']==1)
											{$nu=8;}else{$nu=7;}
											$sw_imprimir=0;
											$accion1=ModuloGetURL('app','Os_Atencion','user','ReporteFichaLaboratorio',array('numero'=>$numero['numero_cumplimiento'],'fecha_cumplimiento'=>$numero['fecha_cumplimiento'],'tipoid'=>$tipo,'id'=>$id,'nom'=>$nom));
											//$salida.="  <tr class=$estilo><td  colspan='7' align=\"center\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'><a href='$accion1'>&nbsp;IMPRIMIR</a></td></tr>";

											if($vector3[$i][os_tipo_solicitud_id]=='CIT')//quiere decir q es una cita..
											{
												$salida.="  <tr class='$estilo'><td  colspan='$nu' align=\"center\"><label class='label_mark'>CITA</label></td></tr>";
											}
											else
											{
												$salida.="  <tr class='$estilo'><td  colspan='$nu' align=\"center\">&nbsp;</td></tr>";
											}
											$_SESSION['OS_ATENCION']['NUMERO_C']=$numero['numero_cumplimiento'];
											$salida.="</form>";
											$salida .= "<form name=\"formaimp\"  action=".$accion1." method=\"post\">";
								}
							}

							$vecimiento=$vector3[$i][fecha_vencimiento];
							$arr_fecha=explode(" ",$vecimiento);
							$salida.="<tr id=$i class='$estilo'>";
							$salida.="  <td  align=\"center\" >".$vector3[$i][numero_orden_id]."</td>";
							$salida.="  <td  align=\"center\" >".$vector3[$i][cantidad]."</td>";
							$salida.="  <td  align=\"center\" >".$vector3[$i][cargoi]."</td>";
							$salida.="  <td  align=\"center\" >".$vector3[$i][des1]."</td>";

							if(strtotime($arr_fecha[0]) >= strtotime(date("Y-m-d")))
							{
								$salida.="  <td   align=\"center\" >$arr_fecha[0]</td>";
								//$salida.="  <td  align=\"center\"><input type=checkbox name=op[$i] value=".$vector[$m][numero_orden_id].",".$vector[$m][cargo].",".$vector[$m][tarifario_id].",".$vector[$m][autorizacion_ext].",".$vector[$m][autorizacion_int].",".$vector[$m][cantidad].",".urlencode($vector[$m][descargo]).",".$vector[$m][servicio].",".$vector[$m][serv_des].",".$vector[$k][orden_servicio_id]."></td>";
								//$salida.="  <td  align=\"center\"><label class='label_mark'>CUMPLUUIDA</label></td>";
								if($_SESSION['LABORATORIO']['SW_HONORARIO']==1)
								{$salida.="  <td  align=\"center\" >".TraerEspecialista($vector3[$i][numero_orden_id])."</td>";}
                 $salida.="  <td  align=\"center\"><label class='label_mark'>CUMPLIDA</label></td>";
							}
							else
							{
								if($_SESSION['LABORATORIO']['SW_HONORARIO']==1)
								{$salida.="  <td  align=\"center\" >".TraerEspecialista($vector3[$i][numero_orden_id])."</td>";}
								$salida.="  <td   align=\"center\"><label class='label_mark'><DIV TITLE='FECHA DE VENCIMIENTO :  $arr_fecha[0]'>VENCIDA</DIV></label></td>";
								$salida.="  <td align='center'><img src=\"".GetThemePath()."/images/delete.gif\"></td>";
							}
							//<duvan>aqui se sigue
							if($vector3[$i][os_tipo_solicitud_id]=='CIT')//quiere decir q es una cita..
							{
								$salida.="  <td><label class='label_mark'><label class='label_mark'>CITA</label></label></td>";
							}
							else
							{
								$salida.="<td class='$estilo' align=\"center\"></td>";
							}
              $salida.="</tr>";


							if($sw_imprimir==1)
							{
								$accion1=ModuloGetURL('app','Os_Atencion','user','ReporteFichaLaboratorio',array('numero'=>$numero['numero_cumplimiento'],'fecha_cumplimiento'=>$numero['fecha_cumplimiento'],'numero_orden_id'=>$vector3[$i][numero_orden_id],'tipoid'=>$tipo,'id'=>$id,'nom'=>$nom));
								if($_SESSION['LABORATORIO']['SW_HONORARIO']==1)
								{$numero=8;}else{$numero=7;}

								if($vector3[$i][os_tipo_solicitud_id]=='CIT')//quiere decir q es una cita..
								{
										$salida.="  <tr class='$estilo'><td  colspan='$numero' align=\"center\"><label class='label_mark'>CITA</label></td></tr>";
								}
								else
								{
									$salida.="  <tr class='$estilo'><td  colspan='$numero' align=\"center\">&nbsp;</td></tr>";
								}
								//	$salida.="  <tr class=$estilo><td  colspan='7' align=\"center\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'><a href='$accion1'>&nbsp;IMPRIMIR</a></td></tr>";
							}

							if($i==sizeof($vector3)-1)
							{
								//$accion1=ModuloGetURL('app','Os_Atencion','user','ReporteFichaLaboratorio',array('numero'=>$numero['numero_cumplimiento'],'fecha_cumplimiento'=>$numero['fecha_cumplimiento'],'numero_orden_id'=>$vector3[$i][numero_orden_id],'tipoid'=>$tipo,'id'=>$id,'nom'=>$nom));
								//$salida.="  <tr class=$estilo><td  colspan='7' align=\"center\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'><a href='$accion1'>&nbsp;IMPRIMIR</a></td></tr>";
								if($_SESSION['LABORATORIO']['SW_HONORARIO']==1)
								{$numero=8;}else{$numero=7;}
								if($vector3[$i][os_tipo_solicitud_id]=='CIT')//quiere decir q es una cita..
								{
									$salida.="  <tr class='$estilo'><td  colspan='$numero' align=\"center\"><label class='label_mark'>CITA</label></td></tr>";
								}
								else
								{
									$salida.="  <tr class='$estilo'><td  colspan='$numero' align=\"center\">&nbsp;</td></tr>";
								}
								$salida.="</form>";

							}

					}
					$salida.="</table>";
					unset($_SESSION['OS_ATENCION']['NUMERO_C']);
			}else{$conteo++;}

			



      		$vector=TraerOrdenesServicio($_REQUEST['tipo_id'],$_REQUEST['paciente']); //sacamos las ordenes de sevicio que desea pagar.
					if(is_array($vector))
					{
						for($i=0;$i<sizeof($vector);)
						{
								$k=$i;
								if($vector[$i][plan_id]==$vector[$k][plan_id]
								AND $vector[$i][tipo_afiliado_id]==$vector[$k][tipo_afiliado_id]
								AND $vector[$i][rango]==$vector[$k][rango]
								AND $vector[$i][orden_servicio_id]==$vector[$k][orden_servicio_id])
								{
								$salida.="<BR><table  align=\"center\" border=\"0\" width=\"70%\">";
								$salida.="<tr class=\"modulo_table_list_title\">";
								$salida.="  <td align=\"left\" colspan=\"7\">PLAN&nbsp;&nbsp;".$vector[$i][descripcion]."&nbsp;&nbsp;".
								$vector[$i][plan_descripcion]."</td>";
								$salida.="</tr>";
								$salida.="<tr class=\"hc_table_submodulo_list_title\">";
								$salida.="  <td width=\"7%\">ORDEN</td>";
								$salida.="  <td width=\"8%\">ITEM</td>";
								$salida.="  <td width=\"10%\">CANTIDAD</td>";
								$salida.="  <td width=\"10%\">CARGO</td>";
								$salida.="  <td width=\"40%\">DESCRIPCION</td>";
								$salida.="  <td width=\"20%\">VENCIMIENTO</td>";
								$salida.="  <td width=\"8%	\"></td>";
								//$salida.="  <td width=\"10%\">Todos&nbsp;<input type=checkbox name=sel onclick=chequeoTotal(this.form,this.checked)></td>";
							//form
								$salida .= "           <form name=\"formita\" action=\"".ModuloGetURL('app','Os_Atencion','user','BuscarCuentaActiva',array('id_tipo'=>$tipo,'nom'=>urlencode($nom),'id'=>$id,'plan_id'=>$vector[$k][plan_id]))."\" method=\"post\">";
								$salida.="</tr>";
								}
								while($vector[$i][plan_id]==$vector[$k][plan_id]
								AND $vector[$i][tipo_afiliado_id]==$vector[$k][tipo_afiliado_id]
								AND $vector[$i][rango]==$vector[$k][rango]
								AND $vector[$i][servicio]==$vector[$k][servicio])
								{
										$salida.="<tr class='modulo_list_claro'>";
										$salida.="  <td  class=\"hc_table_submodulo_list_title\" width=\"7%\">".$vector[$k][orden_servicio_id]."</td>";
										$salida.="  <td colspan=\"6\">";
										$salida.="  <table align=\"center\" border=\"1\" width=\"100%\">";
										$l=$k;
										while($vector[$k][orden_servicio_id]==$vector[$l][orden_servicio_id]
										AND $vector[$k][plan_id]==$vector[$l][plan_id]
										AND $vector[$k][tipo_afiliado_id]==$vector[$l][tipo_afiliado_id]
										AND $vector[$k][rango]==$vector[$l][rango]
										AND $vector[$k][servicio]==$vector[$l][servicio])
												{
												$vecimiento=$vector[$l][fecha_vencimiento];
												$arr_fecha=explode(" ",$vecimiento);
												if( $l % 2){ $estilo='modulo_list_claro';}
												else {$estilo='modulo_list_oscuro';}
												$salida.="<tr align='center'>";
												$salida.="  <td align='center' class=$estilo width=\"8%\"<label class='label_mark'>".$vector[$l][numero_orden_id]."</label></td>";
												$salida.="  <td colspan=5>";
												$salida.="  <table align=\"center\" border=\"0\" width=\"100%\">";
												$m=$l;
												while($vector[$l][numero_orden_id]==$vector[$m][numero_orden_id]
												AND $vector[$l][orden_servicio_id]==$vector[$m][orden_servicio_id]
												AND $vector[$l][plan_id]==$vector[$m][plan_id]
												AND $vector[$l][tipo_afiliado_id]==$vector[$m][tipo_afiliado_id]
												AND $vector[$l][rango]==$vector[$m][rango]
												AND $vector[$l][servicio]==$vector[$m][servicio])
												{
														$salida.="<tr class=$estilo>";
														$salida.="  <td width=\"10%\" align=\"center\" >".$vector[$m][cantidad]."</td>";
														$salida.="  <td width=\"14%\" align=\"center\" >".$vector[$m][cargoi]."</td>";
														$salida.="  <td width=\"42%\">".$vector[$m][des1]."</td>";

														if(strtotime($arr_fecha[0]) >= strtotime(date("Y-m-d")))
														{
                              $salida.="<td width=\"26%\" align=\"center\" >$arr_fecha[0]</td>";

															$salida.="<td width=\"15%\" align=\"center\" >";
                              $salida.="<table align=\"center\" border=\"0\" width=\"100%\">";
															$salida.="<tr class=$estilo>";
															$citas = Revision_Cita($vector[$m][numero_orden_id],$vector[$m][cargoi]);
                              if($citas[sw_cita]=='1')
                              {
                                if(!empty($citas[existe_cita][numero_orden_id]))
                                {
                                  $accion=ModuloGetURL('app','Os_Atencion','user','AsignacionProfCitasImagen',array('numero_orden_id'=>$vector[$m][numero_orden_id],'tipo_id_paciente'=>$tipo,'paciente_id'=>$id,'nombre'=>$nom));
																	$salida.="<td width=\"15%\" align=\"center\" ><a href=".$accion."><img src=\"". GetThemePath() ."/images/cumplimientos_citas.png\" border=0></a></td>";
                                }
																else
																{
																	$accion=ModuloGetURL('app','Os_Atencion','user','LlamaProgramCitasImagen',array('numero_orden_id'=>$vector[$m][numero_orden_id],'tipo_id_paciente'=>$tipo,'paciente_id'=>$id,'nombre'=>$nom,'tipo_equipo'=>$citas[tipo_equipo_imagen_id]));
																	$salida.="<td width=\"15%\" align=\"center\" ><a href=".$accion."><img src=\"". GetThemePath() ."/images/atencion_citas.png\" border=0 ></a></td>";
																}
																$salida.="<td width=\"15%\" align=\"center\"></td>";
                              }
															else
															{
															  $salida.="<td width=\"15%\" align=\"center\">&nbsp;&nbsp;</td>";
                                $salida.="<td width=\"15%\" align=\"center\"></td>";
															}
                              $salida.="</tr>";
															$salida.="</table>";
															$salida.="</td>";
														}
														else
														{
															$salida.="  <td width=\"26%\" align=\"center\" ><label class='label_mark'>VENCIDO</label></td>";
															$salida.="  <td><img src=\"".GetThemePath()."/images/delete.gif\"></td>";
														}
														$salida.="</tr>";
														$m++;
												}
												$salida.="</table>";
												$salida.="</td>";
												$salida.="</tr>";
												$l=$m;
									}
									//parte de alex.
									$salida.="<tr><td colspan='8' align=\"center\">";
									$salida.="<table width='100%' border='0' cellpadding='2' align=\"center\">";
									$salida.="<tr><td width='20%' class=\"hc_table_submodulo_list_title\" >OBSERVACION</td><td class='modulo_list_claro'>".$vector[$k][observacion]."</td></tr>";
									$salida.="<tr><td width='20%' class=\"hc_table_submodulo_list_title\" >SERVICIO</td><td width='80%' class='modulo_list_oscuro'>".$vector[$k][serv_des]."</td></tr>";
									$salida.="<tr><td width='20%' class=\"hc_table_submodulo_list_title\" >AUT. INT.</td><td width='80%' class='modulo_list_claro'>".$vector[$k][autorizacion_int]."</td></tr>";
									$salida.="<tr><td width='20%' class=\"hc_table_submodulo_list_title\" >AUT. EXT.</td><td width='80%' class='modulo_list_oscuro'>".$vector[$k][autorizacion_ext]."</td></tr>";
									$salida.="<tr><td width='20%' class=\"hc_table_submodulo_list_title\" >AFILIACION</td><td width='80%' class='modulo_list_oscuro'>".$vector[$k][tipo_afiliado_nombre]."</td></tr>";
									$salida.="</table>";
									$salida.="</td></tr>";
									//parte de alex.

									$salida.="</table>";
									$salida.="</td>";
									$salida.="</tr>";
									$k=$l;
							}
							$salida.="</table>";

							$i=$k;
						}
			}else{$conteo++;}


			if($conteo ==3)
			{
				$salida.="<DIV ALIGN='CENTER'><LABEL class='label_mark'>NO EXISTEN ORDENES DE SERVICIOS VENCIDAS PARA ESTE PACIENTE</LABEL></DIV>";
			}
			
			$salida .= ThemeCerrarTabla();

		echo $salida;
		echo "</html>";

			//si no va vacia y se realiza el query comun y corriente.
//trae las ordenes de servicio de estado 2 osea pagado.
function TraerOrdenesServicio_estado2($TipoId,$PacienteId)
{
	list($dbconn) = GetDBconn();
	$query = "SELECT distinct c.plan_id,c.plan_descripcion,a.servicio,h.descripcion as serv_des, 
						sw_cargo_multidpto as switche, 
						CASE c.sw_tipo_plan WHEN '0' THEN d.nombre_tercero 
						WHEN '1' THEN 'SOAT' 
						WHEN '2' THEN 'PARTICULAR' 
						WHEN '3' 
						THEN 'CAPITACION - '||d.nombre_tercero ELSE e.descripcion END, 
						a.tipo_afiliado_id,a.rango,a.orden_servicio_id,f.numero_orden_id,a.fecha_registro, i.fecha_vencimiento, 
						f.cargo as cargoi,g.descripcion as des1,i.cantidad, a.autorizacion_int,a.autorizacion_ext,a.observacion, 
						k.tipo_afiliado_nombre,l.os_tipo_solicitud_id
						FROM os_ordenes_servicios as a,os_maestro i, pacientes as b, tipos_afiliado k,
						servicios h, os_internas as f, cups g, hc_os_solicitudes l, planes c, tipos_planes as e, terceros d
						WHERE a.tipo_id_paciente='$TipoId' AND a.paciente_id='$PacienteId'
						AND a.orden_servicio_id=i.orden_servicio_id 
						AND i.sw_estado=2 
						AND i.hc_os_solicitud_id=l.hc_os_solicitud_id
						AND DATE(i.fecha_activacion) <= NOW()
						AND DATE(i.fecha_vencimiento) < NOW()
						AND i.numero_orden_id=f.numero_orden_id
						AND f.departamento='".$_REQUEST['dpto']."'
						AND g.cargo=f.cargo 
						AND a.tipo_id_paciente=b.tipo_id_paciente AND a.paciente_id=b.paciente_id 
						AND a.tipo_afiliado_id=k.tipo_afiliado_id 
						AND a.servicio=h.servicio
						AND c.plan_id=a.plan_id 
						AND c.tercero_id=d.tercero_id AND c.tipo_tercero_id=d.tipo_id_tercero 
						ORDER BY c.plan_id,i.fecha_vencimiento,a.orden_servicio_id";
 
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
				$this->error = "ERROR AL TRAER LAS ORDENES DE SERVICIOS.";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			if($spia==true)
			{
				return $result->RecordCount();
			}
			while (!$result->EOF) {
							$var[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}
					$result->Close();
					return $var;
}



//si no va vacia y se realiza el query comun y corriente.
//trae las ordenes de servicio de estado 3 osea atender.
function TraerOrdenesServicio_estado3($TipoId,$PacienteId)
{
	list($dbconn) = GetDBconn();

   $query="SELECT
					c.plan_id,c.plan_descripcion,a.servicio,h.descripcion as serv_des,
					sw_cargo_multidpto as switche,
					CASE c.sw_tipo_plan
					WHEN '0' THEN d.nombre_tercero
					WHEN '1' THEN 'SOAT'
					WHEN '2' THEN 'PARTICULAR'
					WHEN '3' THEN 'CAPITACION - '||d.nombre_tercero
					ELSE e.descripcion END,
					a.tipo_afiliado_id,a.rango,a.orden_servicio_id,f.numero_orden_id,a.fecha_registro,
					i.fecha_vencimiento, f.cargo as cargoi,g.descripcion as des1,i.cantidad,
					a.autorizacion_int,a.autorizacion_ext,a.observacion,
					k.tipo_afiliado_nombre,l.os_tipo_solicitud_id
					FROM os_ordenes_servicios as a, pacientes as b, planes c,
					terceros d, tipos_planes as e, os_internas as f, cups g, hc_os_solicitudes l,
					servicios h,os_maestro i,tipos_afiliado k
					WHERE
					a.orden_servicio_id=i.orden_servicio_id
					AND i.numero_orden_id=f.numero_orden_id
					AND a.tipo_id_paciente=b.tipo_id_paciente
					AND a.paciente_id=b.paciente_id
					AND a.tipo_id_paciente='$TipoId'
					AND a.paciente_id='$PacienteId'
					AND a.servicio=h.servicio
					AND g.cargo=f.cargo
					AND c.plan_id=a.plan_id
					AND e.sw_tipo_plan=c.sw_tipo_plan
					AND c.tercero_id=d.tercero_id
					AND c.tipo_tercero_id=d.tipo_id_tercero
 					AND f.departamento='".$_REQUEST['dpto']."'
					AND i.sw_estado=3
					AND a.tipo_afiliado_id=k.tipo_afiliado_id
					AND i.hc_os_solicitud_id=l.hc_os_solicitud_id
					AND DATE(i.fecha_activacion) <= NOW()
					AND DATE(i.fecha_vencimiento) < NOW()
					ORDER BY f.numero_orden_id";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
				$this->error = "ERROR AL TRAER LAS ORDENES DE SERVICIOS.";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			if($spia==true)
			{
				return $result->RecordCount();
			}
			while (!$result->EOF) {
							$var[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}
					$result->Close();
					return $var;
}


	/*
		Funcion que trae el numero de cumplimiento generado
		al darse la atencion
	*/
	function TraerNumeroCumplimiento($orden_id)
	{
				list($dbconn) = GetDBconn();

				$query = "SELECT numero_cumplimiento,fecha_cumplimiento
				FROM os_cumplimientos_detalle
				WHERE numero_orden_id = ".$orden_id." AND
				departamento = '".$_SESSION['LABORATORIO']['DPTO']."' order by numero_cumplimiento asc";

				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "ERROR AL TRAER EL NÚMERO DE CUMPLIMIENTO.";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				return $var[]=$result->GetRowAssoc($ToUpper = false);
	}


/*
* funcion q trae lo nombres de los medicos especialistas amarrados a los departamentos
*/
	function ComboProfesionales()
{
  	list($dbconn) = GetDBconn();


   $query="SELECT DISTINCT x.usuario_id,c.nombre,c.tipo_id_tercero,c.tercero_id

					FROM profesionales_departamentos a, tipos_profesionales b,profesionales c
					,profesionales_usuarios x

					WHERE a.departamento='".$_SESSION['LABORATORIO']['DPTO']."'
					--AND b.tipo_profesional=6
					AND a.tipo_id_tercero=c.tipo_id_tercero
					AND a.tercero_id=c.tercero_id
					AND x.tercero_id=c.tercero_id
					AND x.tipo_tercero_id=c.tipo_id_tercero";


		$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al listar las empresas";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;

		while (!$resulta->EOF)
		{
			$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}
	return $var;
}

	/*
		* funcion que trae el nombre de la especialista ya sea radiologa,o bacteriologa
		* solo para el caso de que sea imagenologia.
		*/
		function TraerEspecialista($Norden)
		{
				list($dbconn) = GetDBconn();

			$query = "SELECT nombre
				FROM os_cumplimientos_detalle a,profesionales b,profesionales_usuarios x
				WHERE a.numero_orden_id = ".$Norden."
				AND	x.usuario_id=a.usuario_id
				AND x.tipo_tercero_id=b.tipo_id_tercero
				AND x.tercero_id=b.tercero_id
				AND departamento = '".$_SESSION['LABORATORIO']['DPTO']."'";

				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "ERROR AL TRAER EL NÚMERO DE CUMPLIMIENTO.";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				return $result->fields[0];
		}



		function Revision_Cita($numero_orden_id, $cargo)
		{
				list($dbconnect) = GetDBconn();
			$query= " SELECT a.cargo, a.departamento, b.sw_cita, a.tipo_equipo_imagen_id
				FROM departamentos_cargos_citas a, os_imagenes_tipo_equipos b
				WHERE a.tipo_equipo_imagen_id = b.tipo_equipo_imagen_id
				AND a.cargo = '".$cargo."' and a.departamento = '".$_SESSION['LABORATORIO']['DPTO']."' ";
				$result = $dbconnect->Execute($query);
				if ($dbconnect->ErrorNo() != 0)
				{
					$this->error = "Error al crear subexamen generico";
					$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
					return false;
				}
				$a=$result->GetRowAssoc($ToUpper = false);
				if($a[sw_cita]=='1')
				{
					$query= " SELECT numero_orden_id FROM os_imagenes_citas WHERE numero_orden_id = ".$numero_orden_id."";
					$result = $dbconnect->Execute($query);
					if ($dbconnect->ErrorNo() != 0)
					{
						$this->error = "Error al crear subexamen generico";
						$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
						return false;
					}
					$b=$result->GetRowAssoc($ToUpper = false);
					$a[existe_cita]=$b;
				}
				return $a;
		}



// $spia es una variable q si esta activa  va a realizar un record count del query
//si no va vacia y se realiza el query comun y corriente.
function TraerOrdenesServicio($TipoId,$PacienteId,$spia='')
{
	list($dbconn) = GetDBconn();

//	if($_SESSION['LABORATORIO']['SW_ESTADO']==1)
//	{
			$filtro_cuenta=", os_cuenta_activa('$TipoId','$PacienteId',c.plan_id) as sw_cuenta";

	//}
	//else
	//{
		//$filtro_cuenta=', 0 as sw_cuenta';
	//}

     $query="SELECT
					c.plan_id,c.plan_descripcion,a.servicio,h.descripcion as serv_des,
					sw_cargo_multidpto as switche,
					CASE c.sw_tipo_plan
					WHEN '0' THEN d.nombre_tercero
					WHEN '1' THEN 'SOAT'
					WHEN '2' THEN 'PARTICULAR'
					WHEN '3' THEN 'CAPITACION - '||d.nombre_tercero
					ELSE e.descripcion END,

					a.tipo_afiliado_id,a.rango,a.orden_servicio_id,f.numero_orden_id,a.fecha_registro,
					i.fecha_vencimiento, f.cargo as cargoi,g.descripcion as des1,i.cantidad,
					a.autorizacion_int,a.autorizacion_ext,a.observacion,
					k.tipo_afiliado_nombre,h.sw_cargo_multidpto$filtro_cuenta

					FROM os_ordenes_servicios as a, pacientes as b, planes c,
					terceros d, tipos_planes as e, os_internas as f, cups g,
					servicios h,os_maestro i, tipos_afiliado k

					WHERE
					a.orden_servicio_id=i.orden_servicio_id
					AND i.numero_orden_id=f.numero_orden_id
					AND a.tipo_id_paciente=b.tipo_id_paciente
					AND a.paciente_id=b.paciente_id
					AND a.tipo_id_paciente='$TipoId'
					AND a.paciente_id='$PacienteId'
					AND a.servicio=h.servicio
					AND g.cargo=f.cargo
					AND c.plan_id=a.plan_id
					AND e.sw_tipo_plan=c.sw_tipo_plan
					AND c.tercero_id=d.tercero_id
					AND c.tipo_tercero_id=d.tipo_id_tercero
 					AND f.departamento='".$_REQUEST['dpto']."'
					AND i.sw_estado=1
					AND a.tipo_afiliado_id=k.tipo_afiliado_id
					AND DATE(i.fecha_activacion) <= NOW()
					AND DATE(i.fecha_vencimiento) < NOW()
					ORDER BY c.plan_id,i.fecha_vencimiento,a.orden_servicio_id";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
				$this->error = "ERROR AL TRAER LAS ORDENES DE SERVICIO.";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			if($spia==true)
			{
				return $result->RecordCount();
			}
			while (!$result->EOF) {
							$var[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}
					$result->Close();
					return $var;
}




?>

