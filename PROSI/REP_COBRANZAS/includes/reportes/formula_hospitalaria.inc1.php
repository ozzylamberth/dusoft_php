<?php

/**
 * $Id: formula_hospitalaria.inc.php,v 1.12 2005/06/07 18:40:58 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

	function GenerarFormula($datos2)
	{
		$Dir="cache/formula_medica_hos.pdf";
		require("classes/fpdf/html_class.php");
		define('FPDF_FONTPATH','font/');
		$_SESSION['REPORTES']['VARIABLE']='formula_hosp';
		$pdf=new PDF('P','mm','soat');
		$total=sizeof($datos2);
		if($total<=2)
		{
			$pdf->AliasNbPages();
			$pdf->AddPage();
			$pdf->SetFont('Arial','',8);
			$reporte.=cabecera($datos2);
			$reporte.=cuerpo($datos2,0,$total);
			$reporte.=final($datos2);
			$pdf->WriteHTML($reporte);
		}
		else
		{
			$totalenter=intval($total/3);
			$totalresid=$total%3;
			for($j=0;$j<$totalenter;$j++)
			{
				$pdf->AliasNbPages();
				$pdf->AddPage();
				$pdf->SetFont('Arial','',8);
				$reporte =cabecera($datos2);
				$inicial=$j*3;
				$reporte.=cuerpo($datos2,$inicial,3);//datos,numerodemedicamentos,dondeinicia
				$pdf->WriteHTML($reporte);
			}
			if($totalresid==1 OR $totalresid==2)
			{
				$pdf->AliasNbPages();
				$pdf->AddPage();
				$pdf->SetFont('Arial','',8);
				$reporte =cabecera($datos2);
				$inicial=$j*3;
				$reporte.=cuerpo($datos2,$inicial,$totalresid);
				$reporte.=final($datos2);
				$pdf->WriteHTML($reporte);
			}
			else if($totalresid==0)
			{
				$pdf->AliasNbPages();
				$pdf->AddPage();
				$pdf->SetFont('Arial','',8);
				$reporte =final($datos2);
				$pdf->WriteHTML($reporte);
			}
		}
		$pdf->Output($Dir,'F');
		return True;
	}

	function cabecera($datos)
	{
		$fechaI=FechaStampJT($datos[0][fecha_nacimiento]);
		$fechaF=FechaStampJT($datos[0][fecha_cierre]);
		$fechaIngreso=FechaStampJ($datos[0][fecha_ingreso]);
		$fechaEvolucion=FechaStampJ($datos[0][fecha_cierre]);
		$edad=CalcularEdad($fechaI,$fechaF);

		$html ="<br><br><br><TABLE BORDER='0' WIDTH='1520'>";
		$html.="<TR>";
		$html.="<TD WIDTH='110' HEIGHT=25>IDENTIFICACION:</TD>";
		$html.="<TD WIDTH='270' HEIGHT=25>".$datos[0]['tipo_id_paciente']." ".$datos[0]['paciente_id']."</TD>";
		$html.="<TD WIDTH='110' HEIGHT=25>HC:</TD>";
		$html.="<TD WIDTH='270' HEIGHT=25>";
		if($datos[0]['historia_numero']!="")
		{
			if($datos[0]['historia_prefijo']!="")
			{
				$html.= $datos[0]['historia_numero']." - ". $datos[0]['historia_prefijo'];
			}
			else
			{
				$html.= $datos[0]['paciente_id']." - ".$datos[0]['historia_prefijo'];
			}
		}
		else
		{
			$html.= $datos[0]['paciente_id']." - ".$datos[0]['tipo_id_paciente'];
		}
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='110' HEIGHT=25>PACIENTE:</TD>";
		$nombre = $datos[0]['paciente'];
		$nombre = substr("$nombre", 0, 38);
		$html.="<TD WIDTH='270' HEIGHT=25><b>".strtoupper($nombre).""."</b></TD>";
		$html.="<TD WIDTH='110' HEIGHT=25>No. DE INGRESO:</TD>";
		$html.="<TD WIDTH='270' HEIGHT=25>".$datos[0]['ingreso']."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='110' HEIGHT=25>EDAD:</TD>";
		$html.="<TD WIDTH='270' HEIGHT=25>".$edad['anos'].' AÑOS'."</TD>";
		$html.="<TD WIDTH='110' HEIGHT=25>SEXO:</TD>";
		$html.="<TD WIDTH='270' HEIGHT=25>".$datos[0]['sexo_id']."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='110' HEIGHT=25>FECHA INGRESO:</TD>";
		$html.="<TD WIDTH='270' HEIGHT=25>".$fechaIngreso."</TD>";
		$html.="<TD WIDTH='110' HEIGHT=25>FECHA SOLICITUD:</TD>";
		$html.="<TD WIDTH='270' HEIGHT=25>".$fechaEvolucion."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='110' HEIGHT=25>RANGO:</TD>";
		$html.="<TD WIDTH='270' HEIGHT=25>".strtoupper($datos[0]['rango'])."</TD>";
		$html.="<TD WIDTH='110' HEIGHT=25>TIPO DE AFILIADO:</TD>";
		$html.="<TD WIDTH='270' HEIGHT=25>".strtoupper($datos[0]['tipo_afiliado_nombre'])."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='110' HEIGHT=25>CLIENTE:</TD>";
		$cliente = $datos[0]['cliente'];
		$cliente = substr("$cliente", 0, 38);
		$html.="<TD WIDTH='270' HEIGHT=25>".strtoupper($cliente)."."."</TD>";
		$html.="<TD WIDTH='110' HEIGHT=25>PLAN:</TD>";
		$plan = $datos[0]['plan_descripcion'];
		$plan = substr("$plan", 0, 38);
		$html.="<TD WIDTH='270' HEIGHT=25>".strtoupper($plan)."."."</TD>";
		$html.="</TR>";
		if ($datos[0][uso_controlado]==1)
		{
			$html.="<TR>";
			$html.="<TD WIDTH='110' HEIGHT=25>DIRECCION.:</TD>";
			$dir = $datos[0]['residencia_direccion'];
			$dir = substr("$dir", 0, 38);
			$html.="<TD WIDTH='270' HEIGHT=25>".strtoupper($dir)."."."</TD>";
			$html.="<TD WIDTH='110' HEIGHT=25>TELEFONO.:</TD>";
			$html.="<TD WIDTH='270' HEIGHT=25>".strtoupper($datos[0]['residencia_telefono'])."</TD>";
			$html.="</TR>";
		}
		return $html;
	}

	function cuerpo($datos,$inicio,$cuantos)
	{
		$titulo = 'FORMULA MEDICA';
		if ($datos[0]['uso_controlado']==1)
		{
			$subtitulo = 'MEDICAMENTO(S) DE USO CONTROLADO.';
		}
		else
		{
			if($datos[0]['item']=='POS')
			{
				$subtitulo = 'MEDICAMENTO(S) POS FORMULADO(S).';
			}
			if($datos[0]['item']=='NO POS' AND $datos[0]['sw_paciente_no_pos']=='1')
			{
				$subtitulo = 'MEDICAMENTO(S) NO POS SOLICITADO(S) A PETICI?N DEL PACIENTE.';
			}
			elseif($datos[0]['item']=='NO POS' AND $datos[0]['sw_paciente_no_pos']=='0')
			{
				$subtitulo = 'MEDICAMENTO(S) NO POS JUSTIFICADO(S).';
			}
		}
		$html ="<TR>";
		$html.="<TD ALIGN='CENTER' WIDTH='760' HEIGHT=25><br>";
		$html.="<b>".$titulo."".' : '."".$subtitulo."</b>";
		$html.="</TD>";
		$html.="</TR>";
		$limite=$inicio+$cuantos;
		for($i=$inicio;($i<$limite);$i++)
		{
			$html.="<TR>";
			$html.="<TD WIDTH='380' HEIGHT=25><br>".($i+1).'. '.strtoupper($datos[$i]['producto'])."</TD>";
			$e=$datos[$i]['cantidad']/floor($datos[$i]['cantidad']);
			if ($datos[$i]['contenido_unidad_venta'])
			{
				if($e==1)
				{
					$html.="<TD WIDTH='380' HEIGHT=25>".'Cantidad : '.floor($datos[$i]['cantidad']).' '.$datos[$i]['descripcion'].' por '.$datos[$i]['contenido_unidad_venta']."</TD>";
					$html.="</TR>";
				}
				else
				{
					$html.="<TD WIDTH='380' HEIGHT=25>".'Cantidad : '.$datos[$i]['cantidad'].' '.$datos[$i]['descripcion'].' por '.$datos[$i]['contenido_unidad_venta']."</TD>";
					$html.="</TR>";
				}
			}
			else
			{
				if($e==1)
				{
					$html.="<TD WIDTH='380' HEIGHT=25>".'Cantidad : '.floor($datos[$i]['cantidad']).' '.$datos[$i]['descripcion']."</TD>";
					$html.="</TR>";
				}
				else
				{
					$html.="<TD WIDTH='380' HEIGHT=25>".'Cantidad : '.$datos[$i]['cantidad'].' '.$datos[$i]['descripcion']."</TD>";
					$html.="</TR>";
				}
			}
			if($datos[$i]['via']!='')
			{
				$html.="<TR>";
				$html.="<TD WIDTH='760' HEIGHT=25>".'Via de Administracion : '.$datos[$i]['via']."</TD>";
				$html.="</TR>";
			}
			$posologia = '';
			if($datos[$i][tipo_opcion_posologia_id]== 1)
			{
				$posologia = 'cada '.$datos[$i][posologia][0][periocidad_id].' '.$datos[$i][posologia][0][tiempo];
			}
			if($datos[$i][tipo_opcion_posologia_id]== 2)
			{
				$posologia = $datos[$i][posologia][0][descripcion];
			}
			if($datos[$i][tipo_opcion_posologia_id]== 3)
			{
				$momento = '';
				if($datos[$i][posologia][0][sw_estado_momento]== '1')
				{
					$momento = 'antes de ';
				}
				else
				{
					if($datos[$i][posologia][0][sw_estado_momento]== '2')
					{
						$momento = 'durante ';
					}
					else
					{
						if($datos[$i][posologia][0][sw_estado_momento]== '3')
							{
								$momento = 'despues de ';
							}
					}
				}
				$Cen = $Alm = $Des= '';
				$cont= 0;
				$conector = '  ';
				$conector1 = '  ';
				if($datos[$i][posologia][0][sw_estado_desayuno]== '1')
				{
					$Des = $momento.'el Desayuno';
					$cont++;
				}
				if($datos[$i][posologia][0][sw_estado_almuerzo]== '1')
				{
					$Alm = $momento.'el Almuerzo';
					$cont++;
				}
				if($datos[$i][posologia][0][sw_estado_cena]== '1')
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
				$posologia = $Des.$conector.$Alm.$conector1.$Cen;
			}
			if($datos[$i][tipo_opcion_posologia_id]== 4)
			{
				$conector = '  ';
				$frecuencia='';
				$j=0;
				foreach ($datos[$i][posologia] as $k => $v)
				{
					if ($j+1 ==sizeof($datos[$i][posologia]))
					{
						$conector = '  ';
					}
					else
					{
						if ($j+2 ==sizeof($datos[$i][posologia]))
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
				$posologia = 'a la(s): '.$frecuencia;
			}
			if($datos[$i][tipo_opcion_posologia_id]== 5)
			{
				$posologia = ' '.$datos[$i][posologia][0][frecuencia_suministro];
			}
			$e=$datos[$i][dosis]/floor($datos[$i][dosis]);
			if($e==1)
			{
				$html.="<TR>";
				$html.="<TD WIDTH='760' HEIGHT=25>".'Dosis : '.floor($datos[$i]['dosis']).' '.$datos[$i]['unidad_dosificacion'].' '.$posologia."</TD>";
				$html.="</TR>";
			}
			else
			{
				$html.="<TR>";
				$html.="<TD WIDTH='760' HEIGHT=25>".'Dosis : '.$datos[$i]['dosis'].' '.$datos[$i]['unidad_dosificacion'].' '.$posologia."</TD>";
				$html.="</TR>";
			}
			if ($datos[$i]['observacion']!='')
			{
				$html.="<TR>";
				$html.="<TD WIDTH='760' HEIGHT=25>".'Observacion : '.$datos[$i]['observacion']."</TD>";
				$html.="</TR>";
			}
		}
		return $html;
	}

	function final($datos)
	{
		$html ="<TR>";
		$html.="<TD WIDTH='380' HEIGHT=25><br>MEDICO TRATANTE:</TD>";
		if(!empty($datos[0][cuota_moderadora][cuota_moderadora]))
		{
			$html.="<TD WIDTH='380' HEIGHT=25>".'CUOTA MODERADORA:'.$datos[0]['cuota_moderadora']['cuota_moderadora']."</TD>";
		}
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD ALIGN='LEFT' WIDTH='760'>";
		$html.="<br>________________________________________________";
		$html.="</TD>";
		$html.="</TR>";
		if($datos[0][medico_evol_max][tarjeta_profesional] != '')
		{
			$html.="<TR>";
			$html.="<TD WIDTH='760' HEIGHT=25>".strtoupper($datos[0][medico_evol_max][nombre_tercero])."<br>".$datos[0][medico_evol_max][tipo_id_medico].': '.$datos[0][medico_evol_max][medico_id].' - T.P.: '.$datos[0][medico_evol_max][tarjeta_profesional].' - '.$datos[0][medico_evol_max][tipo_profesional]."</TD>";
			$html.="</TR>";
		}
		else
		{
			$html.="<TR>";
			$html.="<TD WIDTH='760' HEIGHT=25>".strtoupper($datos[0][medico_evol_max][nombre_tercero])."<br>".$datos[0][medico_evol_max][tipo_id_medico].': '.$datos[0][medico_evol_max][medico_id].' - '.$datos[0][medico_evol_max][tipo_profesional]."</TD>";
			$html.="</TR>";
		}
		$html.="</TABLE>";
		return $html;
	}

	function FechaStampJ($fecha)
	{
		if($fecha)
		{
			$fech = strtok ($fecha,"-");
			for($l=0;$l<3;$l++)
			{
				$date[$l]=$fech;
				$fech = strtok ("-");
			}
			return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
		}
	}

	function FechaStampJT($fecha)
	{
		if($fecha)
		{
			$fech = strtok ($fecha,"-");
			for($l=0;$l<3;$l++)
			{
				$date[$l]=$fech;
				$fech = strtok ("-");
			}
			return  ceil($date[0])."/".ceil($date[1])."/".ceil($date[2]);
		}
	}

?>
