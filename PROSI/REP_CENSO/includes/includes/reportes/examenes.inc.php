<?php

/**
 * $Id: examenes.inc.php,v 1.2 2005/06/07 18:40:57 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Archivo que imprime el reporte de la ambulancia, según un evento del soat
 */

function GenerarExamen($datos)
{
	//	print_r($datos);exit;
	IncludeLib("tarifario");
	$Dir="cache/Examenes.pdf";
	require("classes/fpdf/html_class.php");
	define('FPDF_FONTPATH','font/');
	$pdf=new PDF();
	$pdf->AddPage();


	//inicio de copiado igual que el reporte de lista_trabajo_apoyod
	$html .="<TABLE BORDER='0' WIDTH='1000' ALIGN='LEFT'>";
	if(is_file('images/logocliente.png'))
	{
			$html.="".$pdf->image('images/logocliente.png',10,6,18)."";//$pdf->image('classes/fpdf/clinicaoccidente.gif'
	}
	$html.="<TR>";
	$html.="<TD ALIGN='LEFT' WIDTH='760'><br><br>";
	$html.="<font size='24'><b>".strtoupper($datos[laboratorio])."</b></font>";
	$html.="</TD>";
	$html.="</TR>";
	$html.="<TR>";
	$html.="<TD ALIGN='LEFT' WIDTH='760'>";
	$html.="<font size='24'><b>".strtoupper($datos[tipo_id_tercero]).":".$datos[id]."</b></font>";
	$html.="</TD>";
	$html.="</TR>";

	$html.="<TR>";
	$html.="<TD WIDTH='560' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
	$html.="</TR>";
	$html.="</TABLE>";

	$html .="<TABLE BORDER='0' WIDTH='1000' ALIGN='CENTER'>";
	$html.="<TR>";
	$html.="<TD WIDTH='600' HEIGHT='22' ALIGN='CENTER'></TD>";
	$html.="</TR>";

	$html.="<TR>";
	$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
	$html.="</TR>";
	$html.="</TABLE>";

	$html .="<TABLE BORDER='0' WIDTH='1000' ALIGN='CENTER'>";
	$html.="<TR>";
	$html.="<TD WIDTH='760' HEIGHT='22' ALIGN='CENTER'><b>".strtoupper($datos[titulo])."</b></TD>";
	$html.="</TR>";

	$html.="<TR>";
	$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
	$html.="</TR>";
	$html.="</TABLE>";


//DATOS DEL PACIENTE
	$a=" ";
	$html .="<TABLE BORDER='0'  ALIGN='LEFT'>";

	$html.="<TR>";
	$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='LEFT'><b>DATOS DEL PACIENTE</b></TD>";
	$html.="</TR>";
	$html.="<TR>";
	$html.="<TD WIDTH='760' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
	$html.="</TR>";
	$html.="<TR><font color='#000000'><TD HEIGHT=22 WIDTH='150'><b>No. RESULTADO</b></TD><TD HEIGHT=22 WIDTH='15'><b> :</b></TD><TD WIDTH='300' HEIGHT=22>".$datos[resultado_id]."</TD></TR>";
	$html.="<TR><font color='#000000'><TD HEIGHT=22 WIDTH='150'><b>FECHA</b></TD><TD HEIGHT=22 WIDTH='15'><b> :</b></TD><TD WIDTH='300' HEIGHT=22>".$datos[fecha_realizado]."</TD></TR>";
	$html.="<TR><font color='#000000'><TD HEIGHT=22 WIDTH='150'><b>NOMBRE</b></TD><TD HEIGHT=22 WIDTH='15'><b> :</b></TD><TD WIDTH='300' HEIGHT=22>".strtoupper($datos[nombre])."</TD></TR>";
  $html.="<TR>";
	$html.="<TD WIDTH='760' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
	$html.="</TR>";

	//LO NUEVO QUES E METIO

	//1. obtener la edad del paciente
	$edad_paciente = CalcularEdad($datos[fecha_nacimiento],date("Y-m-d"));

	$html.="<TR><font color='#000000'><TD HEIGHT=22 WIDTH='150'><b>EDAD PACIENTE</b></TD><TD HEIGHT=22 WIDTH='15'><b> :</b></TD><TD WIDTH='300' HEIGHT=22>".$edad_paciente[años]."</TD></TR>";
  //2. datos adicionales
	foreach($datos[datos_adicionales] as $index=>$codigo)
	{
		if ($codigo!='')
		{
			$html.="<TR><font color='#000000'><TD HEIGHT=22 WIDTH='150'><b>".strtoupper($index)."</b></TD><TD HEIGHT=22 WIDTH='15'><b> :</b></TD><TD WIDTH='300' HEIGHT=22>".$codigo."</TD></TR>";
		}
	}
	//FIN DELO NUEVO

	$html.="</TABLE>";

	$html .="<TABLE BORDER='0' WIDTH='1000' ALIGN='CENTER'>";
	$html.="<TR>";
	$html.="<TD WIDTH='600' HEIGHT='22' ALIGN='CENTER'></TD>";
	$html.="</TR>";
	$html.="<TR>";
	$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
	$html.="</TR>";
	$html.="</TABLE>";


	$html .="<TABLE BORDER='0' WIDTH='1000' ALIGN='CENTER'>";
	if($datos[detalle])
	 {
		$p = 1;
		for($i=0;$i<sizeof($datos[detalle]);$i++)
		 {
		 //$datos[detalle][$i][lab_plantilla_id] = 0;
		  switch ($datos[detalle][$i][lab_plantilla_id])
	     {
        case "1": {
										if(empty($datos[detalle][$i][sexo_id]) || $datos[detalle][$i][sexo_id] == '0' || $datos[detalle][$i][sexo_id] == strtoupper($datos[sexo_paciente]))
										{
											$html.="<TR>";
											$html.="<TD WIDTH='140' HEIGHT='22'><b>NOMBRE DEL EXAMEN</b></TD>";
											$html.="<TD WIDTH='240' HEIGHT='22' ALIGN='RIGHT'><b>RESULTADO</b></TD>";
											$html.="<TD WIDTH='340' HEIGHT='22' ALIGN='RIGHT'><b>VALOR NORMAL</b></TD>";
											$html.="</TR>";
										}

									  if(is_null($datos[detalle][$i][rango_min]) || is_null($datos[detalle][$i][rango_max]))
										{
										  $val='';
										  if(is_null($datos[detalle][$i][rango_min]) || $datos[detalle][$i][rango_min] == '0')
											{
											  $datos[detalle][$i][rango_min] = 0;
											  $val="Rango :".$datos[detalle][$i][rango_min]."-".$datos[detalle][$i][rango_max]."$a".$datos[detalle][$i][unidades]."";
											}
										}
									  else
										{
										  if(empty($datos[detalle][$i][sexo_id]) || $datos[detalle][$i][sexo_id] == '0')
											{
												$val="Rango :".$datos[detalle][$i][rango_min]."-".$datos[detalle][$i][rango_max]."$a".$datos[detalle][$i][unidades]."";
											}
										  else
											{
											  $p=0;
												if ($datos[detalle][$i][sexo_id] == $datos[sexo_paciente])
												{
												  if(strtoupper($datos[detalle][$i][sexo_id])=='F')
											 		{
														$val="Mujeres : ".$datos[detalle][$i][rango_min]."-".$datos[detalle][$i][rango_max]."$a".$datos[detalle][$i][unidades]."";
                            $html.="<TR>";
														$html.="<TD WIDTH='140' HEIGHT='22'>".$datos[detalle][0][nombre_examen]."</TD>";
  													if ($datos[detalle][$i][sw_alerta] == '1')
														{
                              //ojo colocar el label error
															$html.="<TD WIDTH='240' HEIGHT='22' ALIGN='RIGHT'>".$datos[detalle][0][resultado]." ".$datos[detalle][0][unidades]."</TD>";
														}
														else
														{
															$html.="<TD WIDTH='240' HEIGHT='22' ALIGN='RIGHT'>".$datos[detalle][0][resultado]." ".$datos[detalle][0][unidades]."</TD>";
														}
														$html.="<TD WIDTH='340' HEIGHT='22' ALIGN='RIGHT'>".$val."</TD>";
                            $html.="</TR>";
											    }
													elseif(strtoupper($datos[detalle][$i][sexo_id])=='M')
													{
														$val="Hombres : ".$datos[detalle][$i][rango_min]."-".$datos[detalle][$i][rango_max]."$a".$datos[detalle][$i][unidades]."";
														$html.="<TR>";
														$html.="<TD WIDTH='140' HEIGHT='22'>".$datos[detalle][0][nombre_examen]."</TD>";
														if ($datos[detalle][$i][sw_alerta] == '1')
														{
															//label error
															$html.="<TD WIDTH='240' HEIGHT='22' ALIGN='RIGHT'>".$datos[detalle][0][resultado]." ".$datos[detalle][0][unidades]."</TD>";
														}
														else
														{
															$html.="<TD WIDTH='240' HEIGHT='22' ALIGN='RIGHT'>".$datos[detalle][0][resultado]." ".$datos[detalle][0][unidades]."</TD>";
														}
														$html.="<TD WIDTH='340' HEIGHT='22' ALIGN='RIGHT'>".$val."</TD>";
													  $html.="</TR>";
													}
												}
											}
										}

									  if ($p == 1)
										{
			  						   $html.="<TR>";
											 $html.="<TD WIDTH='140' HEIGHT='22'>".$datos[detalle][0][nombre_examen]."</TD>";
  										 if ($datos[detalle][$i][sw_alerta] == '1')
											 {
												  //label error
													$html.="<TD WIDTH='240' HEIGHT='22' ALIGN='RIGHT'>".$datos[detalle][0][resultado]." ".$datos[detalle][0][unidades]."</TD>";
											 }
											 else
											 {
													$html.="<TD WIDTH='240' HEIGHT='22' ALIGN='RIGHT'>".$datos[detalle][$i][resultado]." ".$datos[detalle][0][unidades]."</TD>";
											 }
										   $html.="<TD WIDTH='340' HEIGHT='22' ALIGN='RIGHT'>".$val."</TD>";
                       $html.="</TR>";
										}
								   break;
  						    }



			  case "2": {
										$html.="<TR>";
											$html.="<TD WIDTH='140' HEIGHT='22'><b>NOMBRE DEL EXAMEN</b></TD>";
											$html.="<TD WIDTH='240' HEIGHT='22' ALIGN='RIGHT'><b>RESULTADO</b></TD>";
											$html.="<TD WIDTH='340' HEIGHT='22' ALIGN='RIGHT'><b>VALOR NORMAL</b></TD>";
											$html.="</TR>";
										$val = '';
										$html.="<TR>";
										$html.="<TD WIDTH='140' HEIGHT='22'>".$datos[detalle][0][nombre_examen]."</TD>";

										$opcion = $this->ConversionOpcion($datos[detalle][$i][resultado], $datos[detalle][$i][lab_examen_id]);
										$html.="<TD WIDTH='240' HEIGHT='22' ALIGN='RIGHT'>".$opcion[0][opcion]."</TD>";
										$html.="<TD WIDTH='340' HEIGHT='22' ALIGN='RIGHT'>".$val."</TD>";
										$html.="</TR>";
										break;
			            }

        case "3": {//utilizado para los examenes de imagenes
											$html.="<TR>";
											$html.="<TD WIDTH='170' HEIGHT='22'><b>NOMBRE DEL EXAMEN:</b></TD>";
											$html.="<TD WIDTH='520' HEIGHT='22'>".$datos[detalle][0][nombre_examen]."</TD>";
											$html.="</TR>";
											for ($c=0; $c<2;$c++)
											{
												$html.="<TR>";
												$html.="<TD WIDTH='560' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
												$html.="</TR>";
											}
											$html.="<TR>";
											$html.="<TD WIDTH='180' HEIGHT='22' ALIGN='LEFT'><b>RESULTADO</b></TD>";
											$html.="</TR>";
											for ($c=0; $c<2;$c++)
											{
												$html.="<TR>";
												$html.="<TD WIDTH='560' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
												$html.="</TR>";
											}



											$largo_cadena = strlen($datos[detalle][$i][resultado]);
											$ancho_papel = 110;
											if ($largo_cadena > $ancho_papel)
											{
												for($k=0; $k< $largo_cadena; ($k=$k+$ancho_papel))
												{
													$cadena = substr($datos[detalle][$i][resultado], $k,($ancho_papel));
													//$cadena=Justifica($datos[detalle][$i][resultado],10);
													$html.="<TR>";
													$html.="<TD WIDTH='580' HEIGHT='22' ALIGN='LEFT'>".$cadena."</TD>";unset($cadena);
													$html.="</TR>";
												}
											}
											else
											{
												$html.="<TR>";
												$html.="<TD WIDTH='580' HEIGHT='22' ALIGN='LEFT'>".$datos[detalle][$i][resultado]."</TD>";
												$html.="</TR>";
											}

										/*	$cad=Justifica($datos[detalle][$i][resultado],10);
											$html.="<TR>";
											$html.="<TD WIDTH='580' HEIGHT='22' ALIGN='LEFT'>".$cad."</TD>";
											$html.="</TR>";*/

											$e++;
											break;
									}

          case "0": {
																							$html.="<TR>";
											$html.="<TD WIDTH='170' HEIGHT='22'><b>NOMBRE DEL EXAMEN:</b></TD>";
											$html.="<TD WIDTH='520' HEIGHT='22'>".$datos[detalle][0][nombre_examen]."</TD>";
											$html.="</TR>";
											for ($c=0; $c<2;$c++)
											{
												$html.="<TR>";
												$html.="<TD WIDTH='560' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
												$html.="</TR>";
											}
											$html.="<TR>";
											$html.="<TD WIDTH='180' HEIGHT='22' ALIGN='LEFT'><b>RESULTADO</b></TD>";
											$html.="</TR>";
											for ($c=0; $c<2;$c++)
											{
												$html.="<TR>";
												$html.="<TD WIDTH='560' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
												$html.="</TR>";
											}

											$largo_cadena = strlen($datos[detalle][$i][resultado]);
											$ancho_papel = 110;
											if ($largo_cadena > $ancho_papel)
											{
												for($k=0; $k< $largo_cadena; ($k=$k+$ancho_papel))
												{
													$cadena = substr($datos[detalle][$i][resultado], $k,($ancho_papel));
													$html.="<TR>";
													$html.="<TD WIDTH='580' HEIGHT='22' ALIGN='LEFT'>".$cadena."</TD>";
													$html.="</TR>";
												}
											}
											else
											{
												$html.="<TR>";
												$html.="<TD WIDTH='580' HEIGHT='22' ALIGN='LEFT'>".$datos[detalle][$i][resultado]."</TD>";
												$html.="</TR>";
											}

											$e++;
											break;
										}

				}//cierra el switche
		 }//cierra el for
		 for ($c=0; $c<3;$c++)
			{
				$html.="<TR>";
				$html.="<TD WIDTH='560' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
				$html.="</TR>";
      }
			$html.="<TR><font color='#000000'>";
			$html.="<TD HEIGHT=22 WIDTH='50'><b>INFORMACION :</b></TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='60' HEIGHT=22>".$datos[informacion]."</TD>";
			$html.="</TR>";

			for ($c=0; $c<2;$c++)
			{
				$html.="<TR>";
				$html.="<TD WIDTH='560' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
				$html.="</TR>";
      }

      $html.="<TR><font color='#000000'>";
			$html.="<TD HEIGHT=22 WIDTH='50'><b>OBSERVACION DEL PRESTADOR DEL SERVICIO :</b></TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='60' HEIGHT=22>".$datos[observacion_prestacion_servicio]."</TD>";
			$html.="</TR>";

			for ($c=0; $c<2;$c++)
			{
				$html.="<TR>";
				$html.="<TD WIDTH='560' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
				$html.="</TR>";
      }

			$html.="<TR><font color='#000000'>";
			$html.="<TD HEIGHT=22 WIDTH='50'><b>PROFESIONAL</b></TD>";
      			$html.="</TR>";

			for ($c=0; $c<3;$c++)
			{
				$html.="<TR>";
				$html.="<TD WIDTH='560' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
				$html.="</TR>";
      }


			$html.="<TR><font color='#000000'>";
			$html.="<TD HEIGHT=22 WIDTH='50'><b>_________________________________________</b></TD>";
			$html.="</TR>";
			$html.="<TR><font color='#000000'>";
			$html.="<TD WIDTH='70' HEIGHT=22>".$a.strtoupper($datos[nombre_tercero])."</TD>";
			$html.="</TR>";


/*
      if(sizeof($examenes[observaciones_adicionales])>=1)
      {
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="  <td align=\"left\" colspan=\"4\">OBSERVACIONES ADICIONALES REALIZADAS AL RESULTADO:</td>";
				$this->salida.="</tr>";

				$this->salida.="<tr>";
				$this->salida.="<td align='left' class=\"hc_table_submodulo_list_title\" width='5%'>No.</td>";
				$this->salida.="<td align='left' class=\"hc_table_submodulo_list_title\" width='10%'>FECHA DE REGISTRO</td>";
				$this->salida.="<td align='left' class=\"hc_table_submodulo_list_title\" width='20%'>USUARIO QUE REALIZA LA OBSERVACION</td>";
				$this->salida.="<td align='left'  class=\"hc_table_submodulo_list_title\" width='45%'>OBSERVACION ADICIONAL AL RESULTADO</td>";
				$this->salida.="</tr>";

				for($i=0;$i<sizeof($examenes[observaciones_adicionales]);$i++)
				{
					$this->salida.="<tr>";
					$this->salida.="<td align=\"center\" class=\"modulo_list_claro\" >".($i+1)."</td>";
          $this->salida.="<td align=\"center\" class=\"modulo_list_claro\" >".$this->FechaStamp($examenes[observaciones_adicionales][$i][fecha_registro_observacion])." - ".$this->HoraStamp($examenes[observaciones_adicionales][$i][fecha_registro_observacion])."</td>";
					$this->salida.="<td align=\"center\" class=\"modulo_list_claro\" >".$examenes[observaciones_adicionales][$i][usuario_observacion]."</td>";
					$this->salida.="<td align=\"left\" class=\"modulo_list_claro\" >".$examenes[observaciones_adicionales][$i][observacion_adicional]."</td>";
					$this->salida.="</tr>";
				}
				$this->salida.="</table><br>";
			}*/
    }
	  $html.="</TABLE>";
	  $html.="<TABLE WIDTH='1520' ALIGN='CENTER' border='0'>";
		$html.="<TR>";
		$html.="<TD ALIGN='RIGHT' WIDTH='760'>";
		$html.="<FONT SIZE='6'>"._SIIS_APLICATION_TITLE."</FONT>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="</TABLE>";
		//fin de copiado igual que el reporte del modulo os_listas-trabajo_apoyod


		$pdf->SetFont('Arial','B',18);
		//$pdf->SetTextColor(203,203,203);
		//$pdf->RotatedText(60,80,GetVarConfigAplication('Cliente'),35);
		$pdf->SetFont('Arial','',8);
		$pdf->WriteHTML($html);
		//$pdf->SetLineWidth(0.7);
		//$pdf->RoundedRect(7, 5, 198, 280, 3.5, '');
		$pdf->Output($Dir,'F');
		return true;
			}


	//convierte la opcion para tipo plantilla dos
	function ConversionOpcion($resultado, $id)
 {
		list($dbconnect) = GetDBconn();
		$query= "SELECT opcion FROM lab_plantilla2
 						WHERE lab_examen_id= ".$id." AND lab_examen_opcion_id= '".$resultado."'";
		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al realizar la convercion";
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

?>
