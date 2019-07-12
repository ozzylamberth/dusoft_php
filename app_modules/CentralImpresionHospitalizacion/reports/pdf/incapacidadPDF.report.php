<?php

/**
 * $Id: incapacidadPDF.report.php,v 1.1.1.1 2009/09/11 20:36:19 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de formulamedica para impresora pos
 */

class incapacidadPDF_report extends pos_reports_class
{
    //constructor por default
    function incapacidadPDF_report($orientacion,$unidad,$formato,$html)
    {
        	$this->pdf_reports_class($orientacion,$unidad,$formato,$html);
		return true;
    }

    function CrearReporte($datos)
    {
				$pdf=&$this->driver; //obtener el driver
				$datos=&$this->datos; //obtener los datos enviados al reporte.
				$pdf->AddPage();
				//$html="".$pdf->image('images/logocliente.png',10,6,22)."";//$pdf->image('classes/fpdf/clinicaoccidente.gif'
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
				$html.="<font size='24'><b>".$datos[0][tipo_id_tercero].": ".$datos[0][id]."</b></font>";
				$html.="</TD>";
				$html.="</TR>";

				$html.="<TR>";
				$html.="<TD ALIGN='LEFT' WIDTH='760'>";
				$html.="<font size='24'><b>".$datos[0][direccion].' '.$datos[0][municipio].' '.$datos[0][departamento]."</b></font>";
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
				$html.="<TD WIDTH='760' HEIGHT='22' ALIGN='CENTER'><b>INCAPACIDAD MEDICA</b></TD>";
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
        $html.="<TR><TD HEIGHT=22 WIDTH='120'>IDENTIFICACION:</TD><TD WIDTH='60' HEIGHT=22>".$datos[0][tipo_id].' '.$datos[0][paciente_id]."</TD></TR>";
				$html.="<TR><TD HEIGHT=22 WIDTH='120'>PACIENTE:</TD><TD WIDTH='60' HEIGHT=22>".strtoupper($datos[0][paciente])."</TD></TR>";
				$html.="<TR><TD HEIGHT=22 WIDTH='120'>CLIENTE :</TD><TD WIDTH='60' HEIGHT=30>".$datos[0][cliente]."</TD></TR>";
				$html.="<TR><TD HEIGHT=22 WIDTH='120'>PLAN :</TD><TD WIDTH='60' HEIGHT=30>".$datos[0][plan_descripcion]."</TD></TR>";
				$html.="<TR><TD HEIGHT=22 WIDTH='120'>TIPO AFILIADO :</TD><TD WIDTH='60' HEIGHT=30>".$datos[0][tipo_afiliado_nombre]."</TD><TD HEIGHT=22 WIDTH='120'>RANGO :</TD><TD WIDTH='60' HEIGHT=30>".$datos[0][rango]."</TD></TR>";
				$html.="<TR><TD HEIGHT=22 WIDTH='120'>No. EVOLUCION :</TD><TD WIDTH='60' HEIGHT=30>".$datos[0][evolucion_id]."</TD></TR>";
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
				$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='LEFT'><b>".strtoupper($datos[0][tipo_incapacidad_descripcion])."</b></TD>";
        $html.="</TR>";

        $html.="<TR>";
  			$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='LEFT'>".""."</TD>";
				$html.="</TR>";

        $html.="<TR><TD HEIGHT=22 WIDTH='120'>SERVICIO:</TD><TD WIDTH='60' HEIGHT=22>".$datos[0][servicio]."</TD></TR>";
				$html.="<TR><TD HEIGHT=22 WIDTH='120'>FECHA DE EMISIÓN:</TD><TD WIDTH='60' HEIGHT=22>".$datos[0][fecha]."</TD></TR>";
				$html.="<TR><TD HEIGHT=22 WIDTH='120'>FECHA DE TERMINACIÓN :</TD><TD WIDTH='60' HEIGHT=30>".$datos[0][fecha_terminacion]."</TD></TR>";
				$html.="<TR><TD HEIGHT=22 WIDTH='120'>DURACION :</TD><TD WIDTH='60' HEIGHT=30>".$datos[0][dias_de_incapacidad].' dias'."</TD></TR>";

				$html.="<TR>";
  			$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='LEFT'>".""."</TD>";
				$html.="</TR>";

				if($datos[0][observacion_incapacidad]!='')
				{
          $html.="<TR>";
					$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='LEFT'>".'Observación : '.$datos[0][observacion_incapacidad]."</TD>";
					$html.="</TR>";
				}
				$html.="<TR>";
  			$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='LEFT'>".""."</TD>";
				$html.="</TR>";

				$html.="<TR>";
				$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='LEFT'>Diagnostico(s)  :</TD>";
				$html.="</TR>";


				foreach ($datos[0][diagnostico_ingreso] as $k => $v)
				{
						$html.="<TR>";
						$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='LEFT'>".$v[diagnostico_id].' - '.$v[diagnostico_nombre]."</TD>";
						$html.="</TR>";
				}
        foreach ($datos[0][diagnostico_egreso] as $k => $v)
				{
				    $html.="<TR>";
						$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='LEFT'>".$v[diagnostico_id].' - '.$v[diagnostico_nombre]."</TD>";
						$html.="</TR>";
				}
				$html.="<TR>";
				$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='LEFT'>".""."</TD>";
				$html.="</TR>";
				$html.="<TR>";
				$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='LEFT'>".""."</TD>";
				$html.="</TR>";

        $html.="<TR><TD WIDTH='660' HEIGHT='22' ALIGN='LEFT'>MEDICO TRATANTE:</TD></TR>";

				for ($c=0; $c<4;$c++)
				{
					$html.="<TR>";
					$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
					$html.="</TR>";
				}

				$html.="<TR><font color='#000000'>";
	      $html.="<TD HEIGHT=22 WIDTH='50'><b>_________________________________________</b></TD>";
	      $html.="</TR>";
				$html.="<TR><font color='#000000'>";
	      $html.="<TD WIDTH='70' HEIGHT=22>".$datos[0][nombre_tercero]."</TD>";
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
}
?>

