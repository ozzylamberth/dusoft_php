<?php

/**
 * $Id: formula_medica_hosp.report.php,v 1.1.1.1 2009/09/11 20:36:19 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de formulamedica para impresora en pdf desde la central de impresion hospitalizacion
 */

class formula_medica_hosp_report extends pdf_reports_class
{
	//constructor por default
	function formula_medica_hosp_report($orientacion,$unidad,$formato,$html)
	{
			$this->pdf_reports_class($orientacion,$unidad,$formato,$html);
			return true;
	}


	function CrearReporte($datos)
	{

		$pdf=&$this->driver; //obtener el driver
		$datos=&$this->datos; //obtener los datos enviados al reporte.
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',9);

		//ENCABEZADO DE LA PAGINA
		$html .="<TABLE BORDER='0' WIDTH='1000' ALIGN='LEFT'>";
		if(is_file('images/logocliente.png'))
		{
			$html.="".$pdf->image('images/logocliente.png',10,6,18)."";
		}

		$html.="<TR>";
		$html.="<TD ALIGN='LEFT' WIDTH='760'><br><br>";
		$html.="<font size='24'><b>".strtoupper($datos[0][razon_social])."</b></font>";
		$html.="</TD>";
		$html.="</TR>";


		$html.="<TR>";
	  $html.="<TD ALIGN='LEFT' WIDTH='760'>";
	  $html.="<font size='24'><b>".$datos[0][tipo_id_tercero].': '.$datos[0][id]."</b></font>";
	  //$reporte->PrintFTexto($datos[0][direccion].' '.$datos[0][municipio].' '.$datos[0][departamento],false,'center',false,false);
	  $html.="</TD>";
	  $html.="</TR>";
	  $html.="</TABLE>";

		$html .="<TABLE BORDER='0' WIDTH='1000' ALIGN='CENTER'>";
		for ($c=0; $c<2;$c++)
		{
			$html.="<TR>";
			$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
			$html.="</TR>";
		}
		$html.="</TABLE>";
		//FIN DEL ENCABEZADO

    $html .="<TABLE BORDER='0' WIDTH='1000' ALIGN='CENTER'>";
	  $html.="<TR>";


		if ($datos[0][uso_controlado]==1)
		{
			$html.="<TD WIDTH='760' HEIGHT='22' ALIGN='CENTER'><b>FORMULA MEDICA PARA DESPACHO DE</b></TD>";
			$html.="<TD WIDTH='760' HEIGHT='22' ALIGN='CENTER'><b>MEDICAMENTOS DE USO CONTROLADO</b></TD>";
		}
		else
		{
			$html.="<TD WIDTH='760' HEIGHT='22' ALIGN='CENTER'><b>FORMULA MEDICA</b></TD>";
		}
		$html.="</TR>";
    $html.="</TABLE>";

    $html .="<TABLE BORDER='0' WIDTH='1000' ALIGN='CENTER'>";
		for ($c=0; $c<2;$c++)
		{
			$html.="<TR>";
			$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
			$html.="</TR>";
		}
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

		$html.="<TR><TD HEIGHT=22 WIDTH='120'>FECHA :</TD><TD WIDTH='60' HEIGHT=22>".date('d/m/Y h:i')."</TD></TR>";
		$html.="<TR><TD HEIGHT=22 WIDTH='120'>No. INGRESO :</TD><TD WIDTH='60' HEIGHT=30>".$datos[0][ingreso]."</TD></TR>";

		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
		$html.="</TR>";

		$html.="<TR><TD HEIGHT=22 WIDTH='120'>IDENTIFICACION:</TD><TD WIDTH='60' HEIGHT=22>".$datos[0][tipo_id]." : ".$datos[0][paciente_id]."</TD></TR>";
		$html.="<TR><TD HEIGHT=22 WIDTH='120'>PACIENTE:</TD><TD WIDTH='60' HEIGHT=22>".strtoupper($datos[0][paciente])."</TD></TR>";
		$html.="<TR><TD HEIGHT=22 WIDTH='120'>CLIENTE:</TD><TD WIDTH='60' HEIGHT=22>".strtoupper($datos[0][cliente])."</TD></TR>";
		$html.="<TR><TD HEIGHT=22 WIDTH='120'>PLAN:</TD><TD WIDTH='60' HEIGHT=22>".strtoupper($datos[0][plan_descripcion])."</TD></TR>";
		$html.="<TR><TD HEIGHT=22 WIDTH='120'>TIPO AFILIADO:</TD><TD WIDTH='60' HEIGHT=22>".strtoupper($datos[0][tipo_afiliado_nombre])." RANGO ".$datos[0][rango]."</TD></TR>";

		if ($datos[0][uso_controlado]==1)
		{
			$html.="<TR><TD HEIGHT=22 WIDTH='120'>DIRECCION RES.:</TD><TD WIDTH='60' HEIGHT=22>".strtoupper($datos[0][residencia_direccion])."</TD></TR>";
			$html.="<TR><TD HEIGHT=22 WIDTH='120'>TELEFONO RES.:</TD><TD WIDTH='60' HEIGHT=22>".strtoupper($datos[0][residencia_telefono])."</TD></TR>";
		}

  	$html.="</TABLE>";


		$html .="<TABLE BORDER='0' WIDTH='1000' ALIGN='CENTER'>";
		for ($c=0; $c<2;$c++)
		{
			$html.="<TR>";
			$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
			$html.="</TR>";
		}
		$html.="</TABLE>";

		$html .="<TABLE BORDER='0' WIDTH='1000' ALIGN='CENTER'>";
		$html.="<TR>";
		if ($datos[0][uso_controlado]==1)
		{
			$subtitulo = 'MEDICAMENTO(S) DE USO CONTROLADO.';
		}
		else
		{
			if($datos[0][item]=='POS')
			{
				$subtitulo = 'MEDICAMENTO(S) POS FORMULADO(S).';
			}

			if($datos[0][item]=='NO POS' AND $datos[0][sw_paciente_no_pos]=='1')
			{
				$subtitulo = 'MEDICAMENTO(S) NO POS SOLICITADO(S) A PETICION DEL PACIENTE.';
			}
			elseif($datos[0][item]=='NO POS' AND $datos[0][sw_paciente_no_pos]=='0')
			{
				$subtitulo = 'MEDICAMENTO(S) NO POS JUSTIFICADO(S).';
			}
		}
		$html.="<TD WIDTH='760' HEIGHT='22' ALIGN='CENTER'><b>".$subtitulo."</b></TD>";
		$html.="</TR>";
    $html.="<TR>";
		$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
		$html.="</TR>";


		for($i=0; $i<sizeof($datos);$i++)
		{
			$html.="<TR><TD HEIGHT=22 WIDTH='760'><b>".($i+1).'. '.strtoupper($datos[$i][producto])."</b></TD></TR>";
			$html.="<TR><TD HEIGHT=22 WIDTH='760'>".'Via de Administracion : '.$datos[$i][via]."</TD></TR>";


			$e=$datos[$i][dosis]/floor($datos[$i][dosis]);
			if($e==1)
			{
				$html.="<TR><TD HEIGHT=22 WIDTH='760'>".'Dosis : '.floor($datos[$i][dosis]).' '.$datos[$i][unidad_dosificacion]."</TD></TR>";
			}
			else
			{
				$html.="<TR><TD HEIGHT=22 WIDTH='760'>".'Dosis : '.$datos[$i][dosis].' '.$datos[$i][unidad_dosificacion]."</TD></TR>";
			}

      //pintar formula para opcion 1 //caso ok
			if($datos[$i][tipo_opcion_posologia_id]== 1)
			{
				$html.="<TR><TD HEIGHT=22 WIDTH='180'>cada ".$datos[$i][posologia][0][periocidad_id]." ".$datos[$i][posologia][0][tiempo]."</TD></TR>";
			}

      //pintar formula para opcion 2 //caso ok
			if($datos[$i][tipo_opcion_posologia_id]== 2)
			{
				$html.="<TR><TD HEIGHT=22 WIDTH='180'>".$datos[$i][posologia][0][descripcion]."</TD></TR>";
			}

      //pintar formula para opcion 3  //caso ok
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
				$html.="<TR><TD HEIGHT=22 WIDTH='180'>".$Des.$conector.$Alm.$conector1.$Cen."</TD></TR>";
			}

			//pintar formula para opcion 4 ok
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
				$html.="<TR><TD HEIGHT=22 WIDTH='180'>".'a la(s): '.$frecuencia."</TD></TR>";
			}

			//pintar formula para opcion 5 //ok
			if($datos[$i][tipo_opcion_posologia_id]== 5)
			{
				$html.="<TR><TD HEIGHT=22 WIDTH='180'>".' '.$datos[$i][posologia][0][frecuencia_suministro]."</TD></TR>";
			}
	    //pintar cantidad
			$e=$datos[$i][cantidad]/floor($datos[$i][cantidad]);
			if ($datos[$i][contenido_unidad_venta])
			{
				if($e==1)
				{
					$html.="<TR><TD HEIGHT=22 WIDTH='180'>".'Cantidad : '.floor($datos[$i][cantidad]).' '.$datos[$i][descripcion].' por '.$datos[$i][contenido_unidad_venta]."</TD></TR>";
				}
				else
				{
					$html.="<TR><TD HEIGHT=22 WIDTH='180'>".'Cantidad : '.$datos[$i][cantidad].' '.$datos[$i][descripcion].' por '.$datos[$i][contenido_unidad_venta]."</TD></TR>";
				}
			}
			else
			{
				if($e==1)
				{
					$html.="<TR><TD HEIGHT=22 WIDTH='180'>".'Cantidad : '.floor($datos[$i][cantidad]).' '.$datos[$i][descripcion]."</TD></TR>";
				}
				else
				{
					$html.="<TR><TD HEIGHT=22 WIDTH='180'>".'Cantidad : '.$datos[$i][cantidad].' '.$datos[$i][descripcion]."</TD></TR>";
				}
			}
			$html.="<TR><TD HEIGHT=22 WIDTH='180'>".'Observacion : '.$datos[$i][observacion]."</TD></TR>";

			$html.="<TR>";
			$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
			$html.="</TR>";
		}
		if(!empty($datos[0][cuota_moderadora][cuota_moderadora]))
		{
			$html.="<TR><TD HEIGHT=22 WIDTH='180'>".'CUOTA MODERADORA:'.$datos[0][cuota_moderadora][cuota_moderadora]."</TD></TR>";
		}

		$dias_vencimiento = ModuloGetVar('app', 'Central_de_Autorizaciones','vencimiento_formula_medica');
		$x=explode(' ',$datos[0][fecha]);
	  $fecha_vencimiento=date("Y-m-d",strtotime("+".($dias_vencimiento-1)." days",strtotime(date($x[0]))));

	  $html.="<TR><TD HEIGHT=22 WIDTH='180'>".'VALIDEZ : '.$dias_vencimiento.' Dias'."</TD></TR>";
	  $html.="<TR><TD HEIGHT=22 WIDTH='180'>".'FECHA DE VENCIMIENTO : '.$this->FechaStamp($fecha_vencimiento)."</TD></TR>";

		$html .="<TABLE BORDER='0' WIDTH='1000' ALIGN='CENTER'>";
		for ($c=0; $c<2;$c++)
		{
			$html.="<TR>";
			$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
			$html.="</TR>";
		}
		$html.="</TABLE>";
		$html.="<TR><TD HEIGHT=22 WIDTH='180'>MEDICO TRATANTE:</TD></TR>";
		$html .="<TABLE BORDER='0' WIDTH='1000' ALIGN='CENTER'>";
		for ($c=0; $c<2;$c++)
		{
			$html.="<TR>";
			$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
	  	$html.="</TR>";
	  }
	  $html.="</TABLE>";

    $largo = strlen($datos[0][nombre_tercero]);
		$cad = '___';
		for ($l=0; $l<$largo; $l++)
		{
      $cad = $cad.'_';
		}

		$html.="<TR><font color='#000000'>";
	  $html.="<TD HEIGHT=22 WIDTH='50'><b>".$cad."</b></TD>";
		$html.="</TR>";
		$html.="<TR><font color='#000000'>";



		$html.="<TD WIDTH='70' HEIGHT=22>".strtoupper($datos[0][nombre_tercero])."</TD>";
		$html.="</TR>";

		$html.="<TR><font color='#000000'>";
		$html.="<TD WIDTH='70' HEIGHT=22>".$datos[0][tipo_id_medico].': '.$datos[0][medico_id].' T.P.: '.$datos[0][tarjeta_profesional]."</TD>";
		$html.="</TR>";

    $html.="<TR><font color='#000000'>";
		$html.="<TD WIDTH='70' HEIGHT=22>".$datos[0][tipo_profesional]."</TD>";
		$html.="</TR>";
		$html.="</TABLE>";

		$html.="<TABLE WIDTH='1520' ALIGN='CENTER' border='0'>";
		$html.="<TR>";
		$html.="<TD ALIGN='RIGHT' WIDTH='760'>";
		$html.="<FONT SIZE='6'>"._SIIS_APLICATION_TITLE."</FONT>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="</TABLE>";
		$pdf->WriteHTML($html);
		//$pdf->SetLineWidth(0.7);
		//$pdf->RoundedRect(7, 5, 198, 280, 3.5, '');
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
					return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
			}
	}
}
?>

