<?
/**
* Archivo que imprime el reporte de la ambulancia, según un evento del soat
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
**/

class certificado_defuncion_report extends pdf_reports_class
{
	//constructor por default
function certificado_defuncion_report()//$orientacion,$unidad,$formato,$html
{
		$this->pdf_reports_class();//$orientacion,$unidad,$formato,$html
		return true;
}

function CrearReporte($datos)
{
	$pdf=&$this->driver; //obtener el driver
	$datos=&$this->datos; //obtener los datos enviados al reporte.
	$pdf->AddPage();
	//$html="".$pdf->image('images/logocliente.png',10,6,22)."";//$pdf->image('classes/fpdf/clinicaoccidente.gif'
	$pdf->SetFont('Arial','B',9);


	/*//ENCABEZADO DE LA PAGINA
	$html .="<TABLE BORDER='0' WIDTH='1000' ALIGN='LEFT'>";
	if(is_file('images/logocliente.png'))
	{
		$html.="".$pdf->image('images/logocliente.png',10,6,18)."";
	}
	$html.="<TR>";
	$html.="<TD ALIGN='LEFT' WIDTH='760'><br><br>";
	$html.="<font size='24'><b>".strtoupper($datos[paciente][0][razon_social])."</b></font>";
	$html.="</TD>";
	$html.="</TR>";

	$html.="<TR>";
	$html.="<TD ALIGN='LEFT' WIDTH='760'>";
	$html.="<font size='24'><b>".$datos[paciente][0][tipo_id_tercero].": ".$datos[paciente][0][id]."</b></font>";
	$html.="</TD>";
	$html.="</TR>";

	$html.="</TABLE>";

	$html .="<TABLE BORDER='0' WIDTH='1000' ALIGN='CENTER'>";
	$html.="<TR>";
	$html.="<TD WIDTH='760' HEIGHT='22' ALIGN='CENTER'></TD>";
	$html.="</TR>";

	$html.="<TR>";
	$html.="<TD WIDTH='760' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
	$html.="</TR>";
	$html.="</TABLE>";
	//FIN DEL ENCABEZADO

	$html .="<TABLE BORDER='0' WIDTH='1000' ALIGN='CENTER'>";
	$html.="<TR>";
	$html.="<TD WIDTH='760' HEIGHT='22' ALIGN='CENTER'><b>JUSTIFICACION DEL MEDICAMENTO NO POS</b></TD>";
	$html.="</TR>";

	$html.="<TR>";
	$html.="<TD WIDTH='760' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
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
	$html.="<TR><TD HEIGHT=22 WIDTH='120'>NOMBRE:</TD><TD WIDTH='60' HEIGHT=22>".strtoupper($datos[paciente][0][nombre])."</TD></TR>";
	$html.="<TR><TD HEIGHT=22 WIDTH='120'>IDENTIFICACION:</TD><TD WIDTH='60' HEIGHT=22>".$datos[paciente][0][tipo_id_paciente]." : ".$datos[paciente][0][paciente_id]."</TD></TR>";
	$html.="<TR><TD HEIGHT=22 WIDTH='120'>No. EVOLUCION :</TD><TD WIDTH='60' HEIGHT=30>".$datos[0][evolucion_id]."</TD></TR>";
	$html.="<TR><TD HEIGHT=22 WIDTH='120'>FECHA :</TD><TD WIDTH='60' HEIGHT=22>".$datos[paciente][0][fecha]."</TD></TR>";
	$html.="</TABLE>";


	$html .="<TABLE BORDER='0' WIDTH='1000' ALIGN='CENTER'>";
	for ($c=0; $c<2;$c++)
	{
		$html.="<TR>";
		$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
		$html.="</TR>";
  }
	$html.="</TABLE>";

//DATOS DEL MEDICAMENTO
	$html .="<TABLE BORDER='0' WIDTH='1000' ALIGN='CENTER'>";
	$html.="<TR>";
	$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='LEFT'><b>DATOS DEL MEDICAMENTO</b></TD>";
	$html.="</TR>";
	$html.="<TR>";
	$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='LEFT'>".""."</TD>";
	$html.="</TR>";
	$html.="<TR>";
	$html.="<TD WIDTH='150' HEIGHT='22' ALIGN='LEFT'>CODIGO: </TD>";
	$html.="<TD WIDTH='510' HEIGHT='22' ALIGN='LEFT'>".$datos[0][codigo_producto]."</TD>";
	$html.="</TR>";
	$html.="<TR>";
	$html.="<TD WIDTH='150' HEIGHT='22' ALIGN='LEFT'>PRODUCTO: </TD>";
	$html.="<TD WIDTH='510' HEIGHT='22' ALIGN='LEFT'>".$datos[0][producto]."</TD>";
	$html.="</TR>";
	$html.="<TR>";
	$html.="<TD WIDTH='150' HEIGHT='22' ALIGN='LEFT'>PRINCIPIO ACTIVO: </TD>";
	$html.="<TD WIDTH='510' HEIGHT='22' ALIGN='LEFT'>".$datos[0][principio_activo]."</TD>";
	$html.="</TR>";
	$html.="<TR>";
	$html.="<TD WIDTH='150' HEIGHT='22' ALIGN='LEFT'>CONCENTRACION: </TD>";
	$html.="<TD WIDTH='510' HEIGHT='22' ALIGN='LEFT'>".$datos[0][concentracion_forma_farmacologica]." ".$datos[0][unidad_medida_medicamento_id]."</TD>";
	$html.="</TR>";
	$html.="<TR>";
	$html.="<TD WIDTH='150' HEIGHT='22' ALIGN='LEFT'>FORMA: </TD>";
	$html.="<TD WIDTH='510' HEIGHT='22' ALIGN='LEFT'>".$datos[0][forma]."</TD>";
	$html.="</TR>";

	$html.="<TR>";
	$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
	$html.="</TR>";

  $html.="<TR>";
	$html.="<TD WIDTH='170 HEIGHT='22' ALIGN='LEFT'>VIA DE ADMINISTRACION: </TD>";
	$html.="<TD WIDTH='490' HEIGHT='22' ALIGN='LEFT'>".$datos[0][via]."</TD>";
	$html.="</TR>";

	$html.="<TR>";
	$html.="<TD WIDTH='170' HEIGHT='22' ALIGN='LEFT'>DOSIS: </TD>";

	$e=$datos[0][dosis]/(floor($datos[0][dosis]));
		if($e==1)
		{
		  $html.="<TD WIDTH='490' HEIGHT='22' ALIGN='LEFT'>".floor($datos[0][dosis])." ".$datos[0][unidad_dosificacion]."</TD>";
		}
		else
		{
			$html.="<TD WIDTH='490' HEIGHT='22' ALIGN='LEFT'>".$datos[0][dosis]." ".$datos[0][unidad_dosificacion]."</TD>";
		}
	$html.="</TR>";
	$html.="<TR>";
	$html.="<TD WIDTH='170' HEIGHT='22' ALIGN='LEFT'>CANTIDAD: </TD>";
	$e=($datos[0][cantidad])/(floor($datos[0][cantidad]));
	if ($datos[0][contenido_unidad_venta])
	{
		if($e==1)
		{
		  $html.="<TD WIDTH='490' HEIGHT='22' ALIGN='LEFT'>".floor($datos[0][cantidad])." ".$datos[0][descripcion]." por ".$datos[0][contenido_unidad_venta]."</TD>";
		}
		else
		{
			$html.="<TD WIDTH='490' HEIGHT='22' ALIGN='LEFT'>".$datos[0][cantidad]." ".$datos[0][descripcion]." por ".$datos[0][contenido_unidad_venta]."</TD>";
		}
	}
	else
	{
		if($e==1)
		{
		  $html.="<TD WIDTH='490' HEIGHT='22' ALIGN='LEFT'>".floor($datos[0][cantidad])." ".$_SESSION['MEDICAMENTOSM'.$pfj][descripcion]."</TD>";
		}
		else
		{
			$html.="<TD WIDTH='490' HEIGHT='22' ALIGN='LEFT'>".$datos[0][cantidad]." ".$_SESSION['MEDICAMENTOSM'.$pfj][descripcion]."</TD>";
		}
	}
	$html.="</TR>";
	$html.="<TR>";
	$html.="<TD WIDTH='170' HEIGHT='22' ALIGN='LEFT'>OBSERVACION: </TD>";
	$html.="<TD WIDTH='490' HEIGHT='22' ALIGN='LEFT'>".$datos[0][observacion]."</TD>";
	$html.="</TR>";
	$html.="<TR>";
	$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
	$html.="</TR>";
	$html.="<TR>";
	$html.="<TD WIDTH='170' HEIGHT='22' ALIGN='LEFT'>DOSIS POR DIA: </TD>";
	$html.="<TD WIDTH='490' HEIGHT='22' ALIGN='LEFT'>".$datos[0][dosis_dia]."</TD>";
	$html.="</TR>";
	$html.="<TR>";
	$html.="<TD WIDTH='170' HEIGHT='22' ALIGN='LEFT'>DIAS DE TRATAMIENTO: </TD>";
	$html.="<TD WIDTH='490' HEIGHT='22' ALIGN='LEFT'>".$datos[0][duracion]."</TD>";
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

	//DDIAGNOSTICOS
	$html .="<TABLE BORDER='0' WIDTH='1000' ALIGN='CENTER'>";
	$html.="<TR>";
	$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='LEFT'><b>DIAGNOSTICOS</b></TD>";
	$html.="</TR>";
	$html.="<TR>";
	$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='CENTER'>".""."</TD>";
	$html.="</TR>";
	if ($datos[diagnosticos])
	{
		for($j=0;$j<sizeof($datos[diagnosticos]);$j++)
		{
			$html.="<TR>";
			$html.="<TD WIDTH='90' HEIGHT='22' ALIGN='LEFT'>".$datos[diagnosticos][$j][diagnostico_id]."</TD>";
			$html.="<TD WIDTH='10' HEIGHT='22' ALIGN='CENTER'> - </TD>";
			$html.="<TD WIDTH='570' HEIGHT='22' ALIGN='LEFT'>".$datos[diagnosticos][$j][diagnostico_nombre]."</TD>";
			$html.="</TR>";
		}
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

//descripcion del caso clinico
	$html .="<TABLE BORDER='0' WIDTH='1000' ALIGN='CENTER'>";
	$html.="<TR>";
	$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='LEFT'><b>DESCRIPCION DEL CASO CLINICO</b></TD>";
	$html.="</TR>";
	$html.="<TR>";
	$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
	$html.="</TR>";
	$html.="<TR>";
	$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='LEFT'>".$datos[0][descripcion_caso_clinico]."</TD>";
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

	//alternativas pos previamente utilizadas.
	$html .="<TABLE BORDER='0' WIDTH='1000' ALIGN='CENTER'>";
	$html.="<TR>";
	$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='LEFT'><b>ALTERNATIVAS POS PREVIAMENTE UTILIZADAS</b></TD>";
	$html.="</TR>";
	$html.="<TR>";
	$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
	$html.="</TR>";
	for ($j=1;$j<3;$j++)
	{
		if ($j==1)
		{
  		$html.="<TR>";
	    $html.="<TD WIDTH='660' HEIGHT='22' ALIGN='LEFT'><b>PRIMERA POSIBILIDAD TERAPEUTICA POS</b></TD>";
	    $html.="</TR>";
			$html.="<TR>";
	    $html.="<TD WIDTH='660' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
	    $html.="</TR>";
		}
		else
		{
      if ($datos[alternativas][$j-1][medicamento_pos]!='' OR $datos[alternativas][$j-1][principio_activo] != '')
			{
				$html.="<TR>";
				$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='LEFT'><b>SEGUNDA POSIBILIDAD TERAPEUTICA POS</b></TD>";
				$html.="</TR>";
				$html.="<TR>";
				$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
				$html.="</TR>";
			}
			else
			{
        break;
			}
		}

      $html.="<TR>";
	    $html.="<TD WIDTH='120' HEIGHT='22' ALIGN='LEFT'>MEDICAMENTO: </TD>";
			if ($datos[alternativas][$j-1][medicamento_pos]!='')
			{
        $html.="<TD WIDTH='190' HEIGHT='22' ALIGN='LEFT'>".$a." ".$datos[alternativas][$j-1][medicamento_pos]."</TD>";
			}
			else
			{
        $html.="<TD WIDTH='190' HEIGHT='22' ALIGN='LEFT'>".""."</TD>";
			}

	    $html.="<TD WIDTH='130' HEIGHT='22' ALIGN='LEFT'>PRINCIPIO ACTIVO: </TD>";
			if ($datos[alternativas][$j-1][principio_activo] != '')
			{
        $html.="<TD WIDTH='220' HEIGHT='22' ALIGN='LEFT'>".$a." ".$datos[alternativas][$j-1][principio_activo]."</TD>";
			}
			else
			{
        $html.="<TD WIDTH='220' HEIGHT='22' ALIGN='LEFT'>".""."</TD>";
			}
	    $html.="</TR>";

      $html.="<TR>";

	    $html.="<TD WIDTH='120' HEIGHT='22' ALIGN='LEFT'>DOSIS POR DIA: </TD>";
			if($datos[alternativas][$j-1][dosis_dia_pos]!='')
			{
			  $html.="<TD WIDTH='190' HEIGHT='22' ALIGN='LEFT'>".$datos[alternativas][$j-1][dosis_dia_pos]."</TD>";
			}
			else
			{
        $html.="<TD WIDTH='190' HEIGHT='22' ALIGN='LEFT'>".""."</TD>";
			}

	    $html.="<TD WIDTH='200' HEIGHT='22' ALIGN='LEFT'>DURACION DEL TRATAMIENTO: </TD>";
			if($datos[alternativas][$j-1][duracion_pos]!='')
			{
			  $html.="<TD WIDTH='150' HEIGHT='22' ALIGN='LEFT'>".$datos[alternativas][$j-1][duracion_pos]."</TD>";
			}
			else
			{
        $html.="<TD WIDTH='150' HEIGHT='22' ALIGN='LEFT'>".""."</TD>";
			}


			if ($datos[alternativas][$j-1][sw_no_mejoria]!= '1')
			{
			  $html.="<TD WIDTH='80' HEIGHT='22' ALIGN='LEFT'>MEJORIA: SI</TD>";
			}
			else
			{
			  $html.="<TD WIDTH='80' HEIGHT='22' ALIGN='LEFT'>MEJORIA: NO</TD>";
			}
			$html.="</TR>";

			$html.="<TR>";
			if ($datos[alternativas][$j-1][sw_reaccion_secundaria]!= '1')
			{
				$html.="<TD WIDTH='250' HEIGHT='22' ALIGN='LEFT'>REACCION SECUNDARIA: NO</TD>";
			}
			else
			{
			  $html.="<TD WIDTH='250' HEIGHT='22' ALIGN='LEFT'>REACCION SECUNDARIA: SI</TD>";
			}
			if($datos[alternativas][$j-1][reaccion_secundaria]!='')
			{
		    $html.="<TD WIDTH='400' HEIGHT='22' ALIGN='LEFT'>".$a." ".$datos[alternativas][$j-1][reaccion_secundaria]."</TD>";
			}
			else
			{
        $html.="<TD WIDTH='400' HEIGHT='22' ALIGN='LEFT'>".""."</TD>";
			}
		  $html.="</TR>";

		$html.="<TR>";
		if ($datos[alternativas][$j-1][sw_contraindicacion]!= '1')
		{
		  $html.="<TD WIDTH='250' HEIGHT='22' ALIGN='LEFT'>CONTRAINDICACION EXPRESA: NO</TD>";
		}
		else
		{
			$html.="<TD WIDTH='250' HEIGHT='22' ALIGN='LEFT'>CONTRAINDICACION EXPRESA: SI</TD>";
		}
		if($datos[alternativas][$j-1][contraindicacion]!='')
		{
		  $html.="<TD WIDTH='400' HEIGHT='22' ALIGN='LEFT'>".$a." ".$datos[alternativas][$j-1][contraindicacion]."</TD>";
		}
		else
		{
      $html.="<TD WIDTH='400' HEIGHT='22' ALIGN='LEFT'>".""."</TD>";
		}
    $html.="</TR>";

		$html.="<TR>";
		$html.="<TD WIDTH='250' HEIGHT='22' ALIGN='LEFT'>OTRAS</TD>";
    $html.="<TD WIDTH='660' HEIGHT='22' ALIGN='LEFT'>".$a." ".$datos[alternativas][$j-1][otras]."</TD>";
		$html.="</TR>";
		$html.="<TR>";
	  $html.="<TD WIDTH='660' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
	  $html.="</TR>";
  }
	$html.="</TABLE>";

	$html .="<TABLE BORDER='0' WIDTH='1000' ALIGN='CENTER'>";
		$html.="<TR>";
		$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
		$html.="</TR>";
	$html.="</TABLE>";

	//criterios que justifican la solicitud
  $html .="<TABLE BORDER='0' WIDTH='1000' ALIGN='CENTER'>";
	$html.="<TR>";
	$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='LEFT'><b>CRITERIOS DE JUSTIFICACION</b></TD>";
	$html.="</TR>";
	$html.="<TR>";
	$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
	$html.="</TR>";

	$html.="<TR>";
	$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='LEFT'>JUSTIFICACION DE LA SOLICITUD:</TD>";
	$html.="</TR>";
	$html.="<TR>";
	$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='LEFT'>".$datos[0][justificacion]."</TD>";
	$html.="</TR>";
	$html.="<TR>";
	$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
	$html.="</TR>";

	$html.="<TR>";
	$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='LEFT'>VENTAJAS DE ESTE MEDICAMENTO:</TD>";
	$html.="</TR>";
	$html.="<TR>";
	$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='LEFT'>".$datos[0][ventajas_medicamento]."</TD>";
	$html.="</TR>";
	$html.="<TR>";
	$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
	$html.="</TR>";

	$html.="<TR>";
	$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='LEFT'>VENTAJAS DEL TRATAMIENTO:</TD>";
	$html.="</TR>";
	$html.="<TR>";
	$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='LEFT'>".$datos[0][ventajas_tratamiento]."</TD>";
	$html.="</TR>";

	$html.="<TR>";
	$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
	$html.="</TR>";

	$html.="<TR>";
	$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='LEFT'>PRECAUCIONES:</TD>";
	$html.="</TR>";


	$html.="<TR>";
	$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='LEFT'>".$datos[0][precauciones]."</TD>";
	$html.="</TR>";

	$html.="<TR>";
	$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
	$html.="</TR>";

	$html.="<TR>";
	$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='LEFT'>CONTROLES PARA EVALUAR LA EFECTIVIDAD DEL MEDICAMENTO:</TD>";
	$html.="</TR>";
	$html.="<TR>";
	$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='LEFT'>".$datos[0][controles_evaluacion_efectividad]."</TD>";
	$html.="</TR>";

	$html.="<TR>";
	$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
	$html.="</TR>";

	$html.="<TR>";
	$html.="<TD WIDTH='350' HEIGHT='22' ALIGN='LEFT'>TIEMPO DE RESPUESTA ESPERADO: </TD>";
	$html.="<TD WIDTH='310' HEIGHT='22' ALIGN='LEFT'>".$datos[0][tiempo_respuesta_esperado]."</TD>";
	$html.="</TR>";
	$html.="<TR>";
	$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
	$html.="</TR>";

	$html.="<TR>";
  if ($datos[0][sw_riesgo_inminente]!= '1')
	{
		$html.="<TD WIDTH='250' HEIGHT='22' ALIGN='LEFT'>RIESGO INMINENTE: NO</TD>";
	}
	else
	{
		$html.="<TD WIDTH='250' HEIGHT='22' ALIGN='LEFT'>RIESGO INMINENTE: SI</TD>";
	}
	$html.="<TD WIDTH='400' HEIGHT='22' ALIGN='LEFT'>".$a." ".$datos[0][riesgo_inminente]."</TD>";
	$html.="</TR>";
	$html.="<TR>";
	$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
	$html.="</TR>";

  $html.="<TR>";
	$html.="<TD WIDTH='350' HEIGHT='22' ALIGN='LEFT'>SE HAN AGOTADO LAS POSIBILIDADES EXISTENTES: </TD>";
	if ($datos[0][sw_agotadas_posibilidades_existentes]!= '1')
	{
		$html.="<TD WIDTH='310' HEIGHT='22' ALIGN='LEFT'>NO</TD>";
	}
	else
	{
		$html.="<TD WIDTH='310' HEIGHT='22' ALIGN='LEFT'>SI</TD>";
	}
	$html.="</TR>";


	$html.="<TR>";
	$html.="<TD WIDTH='350' HEIGHT='22' ALIGN='LEFT'>TIENE HOMOLOGO EN EL POS:</TD>";
	if ($datos[0][sw_homologo_pos]!= '1')
	{
	  $html.="<TD WIDTH='310' HEIGHT='22' ALIGN='LEFT'>NO</TD>";
	}
	else
	{
    $html.="<TD WIDTH='310' HEIGHT='22' ALIGN='LEFT'>SI</TD>";
	}
  $html.="</TR>";

	$html.="<TR>";
	$html.="<TD WIDTH='350' HEIGHT='22' ALIGN='LEFT'>ES COMERCIALIZADO EN EL PAIS:</TD>";
	if ($datos[0][sw_comercializacion_pais]!= '1')
	{
	  $html.="<TD WIDTH='310' HEIGHT='22' ALIGN='LEFT'>NO</TD>";
	}
	else
	{
    $html.="<TD WIDTH='310' HEIGHT='22' ALIGN='LEFT'>SI</TD>";
	}
  $html.="</TR>";


	for ($c=0; $c<3;$c++)
	{
		$html.="<TR>";
		$html.="<TD WIDTH='560' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
		$html.="</TR>";
  }

  $html.="<TR>";
	$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='LEFT'>Para el trámite de esta solicitud es obligatorio el diligenciamiento completo, anexando el original de la formula médica y el resumen de <br>la historia clinica.  La entrega del medicamento está sujeta a la aprobación del comité técnico-cientifico, de acuerdo a lo establecido en la <br>resolución 5061 del 23 de diciembre de 1997.</TD>";
	$html.="</TR>";


	for ($c=0; $c<3;$c++)
	{
		$html.="<TR>";
		$html.="<TD WIDTH='560' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
		$html.="</TR>";
  }

	//PROFESIONAL
	$html.="<TR><font color='#000000'>";
	$html.="<TD HEIGHT=22 WIDTH='50'><b>PROFESIONAL</b></TD>";
	$html.="</TR>";
	for ($c=0; $c<5;$c++)
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
  //$pdf->RoundedRect(7, 5, 198, 280, 3.5, '');*/
  return true;
}

 /*
 insert into system_modulos_variables values ('Os_Atencion', 'app', 'RangoTurnosEquiposImagen', 15);

insert into system_modulos_variables values ('Os_Atencion', 'app', 'InicioTurnoSalaImagen', 15);

insert into system_modulos_variables values ('Os_Atencion', 'app', 'DuracionTurnoSalaImagen', 15);
 */
}
?>
