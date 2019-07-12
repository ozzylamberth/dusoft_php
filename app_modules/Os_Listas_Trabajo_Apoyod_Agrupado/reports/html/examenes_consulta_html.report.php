<?php

/**
 * $Id: 
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de prueba formato HTML
 */
include_once "./classes/modules/classmodules.class.php";
include_once "./classes/modules/classmodulo.class.php";
include_once "./app_modules/Os_Listas_Trabajo_Apoyod_Agrupado/app_Os_Listas_Trabajo_Apoyod_Agrupado_user.php";
class examenes_consulta_html_report extends app_Os_Listas_Trabajo_Apoyod_Agrupado_user
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
    function examenes_consulta_html_report($datos=array())
    {
		    $salida  = "";
				$this->datosReq=$datos;
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
			$paciente_id = $this->datosReq['paciente_id'];
			$tipo_id_paciente = $this->datosReq['tipo_id_paciente'];
			$servicio = $this->datosReq['servicio'];
			$numero_cumplimiento = $this->datosReq['numero_cumplimiento'];
			$fecha_cumplimiento = $this->datosReq['fecha_cumplimiento'];
			$nombre = $this->datosReq['nombre'];
			
			$salida .="<table  align=\"left\" border=\"0\"  width=\"80%\">\n";
			$salida .="<tr>\n";
			$salida .="  <td class=\"Normal_10N\" align=\"left\" width=\"25%\" >ENTIDAD :</td>\n";
			$salida .="  <td class=\"Normal_10\" align=\"left\" width=\"75%\" >".$_SESSION[LTRABAJOAPOYOD][NOM_DPTO]."</td>\n";
			$salida .="</tr>\n";
			$salida .="<tr>\n";
			$salida .="  <td class=\"Normal_10N\" align=\"left\" width=\"25%\" >ID DEL PACIENTE :</td>\n";
			$salida .="  <td class=\"Normal_10\" align=\"left\" width=\"75%\" >".$tipo_id_paciente.": ".$paciente_id."</td>\n";
			$salida .="</tr>\n";
			$salida .="<tr>\n";
			$salida .="  <td class=\"Normal_10N\" align=\"left\" width=\"25%\" >NOMBRE DEL PACIENTE :</td>\n";
			$salida .="  <td class=\"Normal_10\" align=\"left\" width=\"75%\" >".$nombre."</td>\n";
			$salida .="</tr>\n";
		
			$salida .="<tr>\n";
			$salida .="  <td class=\"Normal_10N\" align=\"left\" width=\"25%\" >FECHA IMPRESION :</td>\n";
			$salida .="  <td class=\"Normal_10\" align=\"left\" width=\"75%\" >".date('Y-m-d')." ".date('h:i')."</td>\n";
			$salida .="</tr>\n";
			$salida .="</table>\n";
			$salida .="<p>&nbsp;</p>\n";
	
			//OBTENIENDO TODOS LOS APOYOS QUE LE FUERON ENVIADOS AL PACIENTE SEGUN EL FILTRO ESCOGIDO
			//Y ADEMAS ESTAN FIRMADOS
			unset($datos);
			$datos = $this->ConsultaResultadosPaciente($paciente_id, $tipo_id_paciente, $servicio, $numero_cumplimiento, $fecha_cumplimiento, $sexo_paciente);
			//echo "<pre>";print_r($datos);
			if ($datos)
			{
					for($k=0;$k<sizeof($datos);$k++)
					{
							if($datos[$k][servicio]!= $datos[$k-1][servicio])
							{
									$salida  .="<table  align=\"center\" border=\"0\"  width=\"80%\">\n";
									$salida  .="<tr class=\"Normal_10N\">\n";
									$salida  .="  <td align=\"left\" width=\"100%\">SERVICIO: ".$datos[$k][servicio_descripcion]."</td>\n";
									$salida  .="</tr>\n";
									$salida  .="</table>\n";
							}
							if(($datos[$k][fecha_cumplimiento]!= $datos[$k-1][fecha_cumplimiento]) AND ($datos[$k][numero_cumplimiento]!= $datos[$k-1][numero_cumplimiento]))
							{
									$salida  .="<table  align=\"center\" border=\"1\"  width=\"80%\">\n";
									$salida  .="<tr >\n";
									$salida  .="  <td class=\"Normal_10N\" align=\"left\" width=\"30%\">FECHA DE CUMPLIMIENTO: </td>\n";
									$salida  .="  <td class=\"Normal_10\" align=\"center\" width=\"20%\">".$datos[$k][fecha_cumplimiento]."</td>\n";
									$salida  .="  <td class=\"Normal_10N\" align=\"left\" width=\"30%\">NUMERO DE CUMPLIMIENTO: </td>\n";
									$cumplimiento=$this->ConvierteCumplimiento($datos[$k][fecha_cumplimiento],$datos[$k][numero_cumplimiento],$_SESSION['LTRABAJOAPOYOD']['DPTO']);
									$salida  .="  <td class=\"Normal_10\" align=\"center\" width=\"20%\">".$cumplimiento."</td>\n";
									$salida  .="</tr>\n";
									$salida  .="</table>\n";
							}
							if($datos[$k][nombre_lista]!= $datos[$k-1][nombre_lista])
							{
									$salida  .="<table  align=\"center\" border=\"1\"  width=\"80%\">\n";
									$salida  .="<tr>\n";
									$salida  .="  <td class=\"Normal_10\" align=\"left\" width=\"100%\">".$datos[$k][nombre_lista]."</td>\n";
									$salida  .="</tr>\n";
									$salida  .="</table>\n";
							}

							$salida  .="<table  align=\"center\" border=\"1\"  width=\"80%\">\n";
							$salida  .="<tr class=\"Normal_10N\">\n";
							$salida  .=" <td align=\"center\" width=\"5%\">CARGO</td>\n";
							$salida  .=" <td align=\"center\" width=\"35%\">EXAMEN</td>\n";
							$salida  .=" <td align=\"center\" width=\"30%\">TECNICA</td>\n";
							$salida  .=" <td align=\"center\" width=\"25%\" >FECHA</td>\n";
							$salida  .="</tr>\n";

							$salida  .="<tr class=\"Normal_10\">";
							$salida  .=" <td align=\"center\" width=\"5%\">".$datos[$k]['cargo']."</td>";
							$salida  .=" <td align=\"center\" width=\"35%\">".strtoupper($datos[$k]['titulo'])."</td>";

							$salida  .=" <td align=\"center\" width=\"12%\">".substr($datos[$k]['nombre_tecnica'],0,30)."</td>";

							
							if ($_SESSION['CONSTRUCTOR_REQUEST']==1)
							{
									$_REQUEST['fecha_realizado'.$k] = $_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['fecha_realizado'];
							}
							else
							{
									if (empty($_REQUEST['fecha_realizado'.$k]))
									{
											$_REQUEST['fecha_realizado'.$k] = date('d-m-Y');
									}
							}
							$salida  .=" <td align=\"center\" width=\"26%\">".$_REQUEST['fecha_realizado'.$k]."</td>";

							$salida  .="</tr>";
							$salida  .="</table>";

							//llama a la funcion que consulta los subexamens de cada apoyo solicitado al paciente
							unset($vector);
							$vector=$this->ConsultaResultados($datos[$k]['resultado_id']);
							//echo "<pre>";print_r($vector);
							if($vector)
							{
									$salida  .="<table  align=\"center\" border=\"1\"  width=\"80%\">";
									$sw_titulo=1;
									for($i=0;$i<sizeof($vector);$i++)
									{
											switch ($vector[$i]['lab_plantilla_id'])
											{
													case "1": { //echo "<br> caso1";
																				if($sw_titulo==1)
																				{
																					$salida  .="<tr  class=\"Normal_10N\">";
																					$salida  .="<td width=\"35%\" align=\"center\">SUBEXAMEN</td>";
																					$salida  .="<td width=\"30%\" align=\"center\">RESULTADO</td>";
																					$salida  .="<td width=\"10%\" align=\"center\">V.MIN</td>";
																					$salida  .="<td width=\"10%\" align=\"center\">V.MAX</td>";
																					$salida  .="<td width=\"10%\" align=\"center\">UND</td>";
																					$salida  .="<td width=\"5%\"  align=\"center\">PAT.</td>";
																					$salida  .="</tr>";
																					$sw_titulo=0;
																				}
																				
																				$salida  .="<tr  class=\"Normal_10\">";
																				$salida  .="<td width=\"35%\" align=\"left\">".$vector[$i]['nombre_examen']."</td>";
																				$salida  .="<td width=\"30%\" align=\"center\">".$vector[$i]['resultado']."</td>";
																				$salida  .="<td width=\"10%\" align=\"center\">".$vector[$i]['rango_min']."&nbsp;</td>";
																				$salida  .="<td width=\"10%\" align=\"center\">".$vector[$i]['rango_max']."&nbsp;</td>";
																				$salida  .="<td width=\"10%\" align=\"center\">".$vector[$i]['unidades']."&nbsp;</td>";

																				if ($vector[$i]['sw_alerta'] == '1')
																				{
																						$salida  .="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" checked name=\"sw_patologico\" value=\"1\" disabled ></td>";
																				}
																				else
																				{
																						$salida  .="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" name=\"sw_patologico\" value=\"1\" disabled></td>";
																				}
																				$salida  .="</tr>";
																				break;
																		}

													case "2": {//echo "<br> caso2";
																					if($sw_titulo==1)
																					{
																						$salida  .="<tr  class=\"Normal_10N\">";
																						$salida  .="<td width=\"35%\" align=\"center\">SUBEXAMEN</td>";
																						$salida  .="<td width=\"40%\" align=\"center\" colspan = \"2\">RESULTADO</td>";
																						$salida  .="<td width=\"20%\" align=\"center\" colspan = \"2\">UND</td>";
																						$salida  .="<td width=\"5%\" align=\"center\">PAT.</td>";
																						$salida  .="</tr>";
																						$sw_titulo=0;
																					}
																					$salida  .="<tr  class=\"Normal_10\">";
																					$salida  .="<td align=\"left\" width=\"40%\" >".strtoupper($vector[$i]['nombre_examen'])."</td>";
																					$salida  .="<td align=\"center\" width=\"45%\" colspan = \"2\">".strtoupper($vector[$i]['resultado'])."</td>";
																					$salida  .="<td align=\"center\" width=\"45%\" colspan = \"2\">".strtoupper($vector[$i]['unidades'])."&nbsp;</td>";

																					if ($vector[$i]['sw_alerta'] == '1')
																					{
																							$salida  .="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" checked name=\"sw_patologico\" value=\"1\" disabled ></td>";
																					}
																					else
																					{
																							$salida  .="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" name=\"sw_patologico\" value=\"1\" disabled></td>";
																					}
																					$salida  .="</tr>";
																				break;
																		}

													case "3": {
																				if($sw_titulo==1)
																				{
																					$salida  .="<tr  class=\"Normal_10N\">";
																					$salida  .="<td width=\"35%\" align=\"center\">SUBEXAMEN</td>";
																					$salida  .="<td width=\"60%\" align=\"center\" colspan = \"4\">RESULTADO</td>";
																					$salida  .="<td width=\"5%\" align=\"center\">PAT.</td>";
																					$salida  .="</tr>";
																					$sw_titulo=0;
																				}

																				$salida  .="<tr  class=\"Normal_10\">";
																				$salida  .="  <td  align=\"center\" width=\"35%\" >".strtoupper($vector[$i]['nombre_examen'])."</td>";
																				$salida   .= "<td colspan = \"4\" align=\"center\" width=\"60%\">".$vector[$i]['resultado']."</td>";
																				if ($vector[$i]['sw_alerta'] == '1')
																				{
																						$salida  .="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" checked name=\"sw_patologico\" value=\"1\" disabled ></td>";
																				}
																				else
																				{
																						$salida  .="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" name=\"sw_patologico\" value=\"1\" disabled></td>";
																				}
																				$salida  .="</tr>";
																				break;
																		}

													case "0": {//echo "<br> caso0";
																				if($sw_titulo==1)
																				{
																					$salida  .="<tr  class=\"Normal_10N\">";
																					$salida  .="<td width=\"35%\" align=\"center\">SUBEXAMEN</td>";
																					$salida  .="<td width=\"60%\" align=\"center\" colspan = \"4\">RESULTADO</td>";
																					$salida  .="<td width=\"5%\" align=\"center\">PAT.</td>";
																					$salida  .="</tr>";
																					$sw_titulo=0;
																				}

																				$salida  .="<tr class=\"Normal_10\" >";
																				$salida  .="  <td  align=\"center\" width=\"35%\" >".strtoupper($vector[$i]['nombre_examen'])."</td>";
																				$salida   .= "<td colspan = \"4\" align=\"center\" width=\"60%\">".$vector[$i]['resultado']."</td>";
																				if ($vector[$i]['sw_alerta'] == '1')
																				{
																						$salida  .="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" checked name=\"sw_patologico\" value=\"1\" disabled ></td>";
																				}
																				else
																				{
																						$salida  .="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" name=\"sw_patologico\" value=\"1\" disabled></td>";
																				}
																				$salida  .="</tr>";
																				break;
																		}
											}//cierra el switche
											
										
									}//cierra el for de los subexamenes
									$salida  .="<tr >";
									$salida  .="	<td class=\"Normal_10N\" colspan = 1 align='left' width='30%'>OBSERVACION PRESTADOR DEL SERVICIO</td>";
									$salida  .="	<td class=\"Normal_10\" colspan = 5  align=\"left\">".$datos[$k]['observacion_prestacion_servicio']."&nbsp;</td>";
									$salida  .="</tr>";
									$salida  .="<tr >";
									$salida  .="	<td class=\"Normal_10N\" colspan = 1 align='left' width='30%'>RESPONSABLE DIAGNOSTICO</td>";
									$salida  .="	<td class=\"Normal_10\" colspan = 5  align=\"left\">".$datos[$k]['prof_diagnostico']."&nbsp;</td>";
									$salida  .="</tr>";
									$salida  .="<tr >";
									$salida  .="	<td class=\"Normal_10N\" colspan = 1 align='left' width='30%'>RESPONSABLE DE LA FIRMA</td>";
									$salida  .="	<td class=\"Normal_10\" colspan = 5  align=\"left\">".$datos[$k]['prof_firma']."&nbsp;</td>";
									$salida  .="</tr>";
									
									$salida  .="</table>";
									
									if(!empty($datos[$k][observaciones_adicionales]))
									{
										$salida  .="<table  align=\"center\" border=\"1\"  width=\"80%\">";
										$salida  .="<tr  class=\"Normal_10N\">";
										$salida  .="  <td align=\"center\" colspan=\"2\" >OBSERVACION ADICIONAL AL RESULTADO</td>";
										$salida  .="</tr>";
	
										foreach($datos[$k][observaciones_adicionales] AS $observaciones => $campos_obs)
										{
											$salida  .="<tr class=\"Normal_10\">";
											$salida  .="<td align=\"left\" width=\"30%\" >OBSERVACION ADICONAL DEL ".$campos_obs[fecha_registro_observacion]." POR : ".$campos_obs[usuario_observacion]."</td>";
											$salida  .="<td align=\"center\" width=\"70%\" class=\"modulo_list_claro\" >".$campos_obs[observacion_adicional]."</td>";
											$salida  .="</tr>";
										}
										$salida  .="</table>";
									}
									
							}//fin del if que verifica si el examen tiene componentes.
					}//fin del for de los apoyos
			}
	
			return $salida ;
	
	}


}

?>
