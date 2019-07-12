<?php

/**
 * $Id: soat_ambulancia.inc.php,v 1.2 2005/06/07 18:40:58 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Archivo que imprime el reporte de la ambulancia, según un evento del soat
 */

	function GenerarSoatAmbulancia($datos)
	{
		$Dir="cache/ambulancia_anexo.pdf";
		require("classes/fpdf/html_class.php");
		define('FPDF_FONTPATH','font/');
		$pdf=new PDF('P','mm','soat');
		$pdf->AddPage();
		$pdf->SetFont('Arial','',8);
		$html ="<TABLE>";
		$html.="<TR><TD>";
		if(is_file('images/logocliente.png'))
		{
			$html.="".$pdf->image('images/logocliente.png',10,6,18)."";//$pdf->image('classes/fpdf/clinicaoccidente.gif'
		}
		$html.="</TD></TR>";
		$html.="</TABLE>";
		$html.="<TABLE BORDER='0' WIDTH='1520'>";
		$html.="<TR>";
		$html.="<TD ALIGN='CENTER' WIDTH='760'><br>";
		$html.="<FONT SIZE='26'><b>ANEXO AL FORMULARIO DE INSTITUCIONES</b></FONT>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD ALIGN='CENTER' WIDTH='760'>";
			$html.="<TABLE BORDER='0' WIDTH='1520'>";
			$html.="<TR>";
			$html.="<TD WIDTH='90' HEIGHT=22 ALIGN='LEFT'>EMPRESA:</TD>";
			$html.="<TD WIDTH='310' HEIGHT=22>".$datos['empresa']."</TD>";
			$html.="<TD WIDTH='220' HEIGHT=22 ALIGN='LEFT'>FECHA DEL ACCIDENTE:</TD>";
			$html.="<TD WIDTH='140' HEIGHT=22>".$datos['fecha_accidente']."</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='90' HEIGHT=22 ALIGN='LEFT'>No. DE POLIZA:</TD>";
			$html.="<TD WIDTH='310' HEIGHT=22>".$datos['poliza']."</TD>";
			$html.="<TD WIDTH='220' HEIGHT=22 ALIGN='LEFT'>PLACA DEL VEHÍCULO ACCIDENTADO:</TD>";
			$html.="<TD WIDTH='140' HEIGHT=22>".$datos['placa_vehiculo']."</TD>";
			$html.="</TR>";
			$html.="</TABLE>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD ALIGN='LEFT' WIDTH='760'>";
			$html.="<TABLE BORDER='0' WIDTH='1520'>";
			$html.="GASTOS DE TRANSPORTE Y MOVILIZACIÓN DE VICTIMAS";
			$html.="</TABLE>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD ALIGN='CENTER' WIDTH='760'>";
			$html.="<TABLE BORDER='1' WIDTH='1520'>";
			$html.="<TR>";
			$html.="<TD WIDTH='300' HEIGHT=22 ALIGN='LEFT'>NOMBRE DEL CONDUCTOR (QUE TRANSPORTA):</TD>";
			$html.="<TD WIDTH='460' HEIGHT=22>".$datos['nombre_conductor']." CON ".$datos['tipo_id_paciente']."".' - '."".$datos['conductor_id']."</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='300' HEIGHT=22 ALIGN='LEFT'>DE:</TD>";
			$html.="<TD WIDTH='460' HEIGHT=22>".$datos['exmunicipio']."</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='300' HEIGHT=22 ALIGN='LEFT'>DIRECCIÓN:</TD>";
			$html.="<TD WIDTH='460' HEIGHT=22>".$datos['direccion']."</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='300' HEIGHT=22 ALIGN='LEFT'>CIUDAD:</TD>";
			$html.="<TD WIDTH='460' HEIGHT=22>".$datos['municipio']."</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='300' HEIGHT=22 ALIGN='LEFT'>TELÉFONO:</TD>";
			$html.="<TD WIDTH='460' HEIGHT=22>".$datos['telefono']."</TD>";
			$html.="</TR>";
			$html.="</TABLE>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD ALIGN='LEFT' WIDTH='760'>";
			$html.="<TABLE BORDER='0' WIDTH='1520'>";
			$html.="TRANSPORTÓ LA VICTIMA";
			$html.="</TABLE>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD ALIGN='CENTER' WIDTH='760'>";
			$html.="<TABLE BORDER='1' WIDTH='1520'>";
			$html.="<TR>";
			$html.="<TD WIDTH='50' HEIGHT=22 ALIGN='LEFT'>DESDE:</TD>";
			$html.="<TD WIDTH='330' HEIGHT=22>".$datos['lugar_desde']."</TD>";
			$html.="<TD WIDTH='50' HEIGHT=22 ALIGN='LEFT'>HASTA:</TD>";
			$html.="<TD WIDTH='330' HEIGHT=22>".$datos['lugar_hasta']."</TD>";
			$html.="</TR>";
			$html.="</TABLE>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD ALIGN='CENTER' WIDTH='760'>";
			$html.="<TABLE BORDER='0' WIDTH='1520'>";
			$html.="<TR>";
			$html.="<TD WIDTH='300' HEIGHT=22 ALIGN='LEFT'>PLACA No. (QUE TRANSPORTA): ".$datos['placa_ambulancia']."";
			$html.="</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='760' HEIGHT=22 ALIGN='LEFT'>LA PRESENTE RECLAMACIÓN POR GASTOS DE TRANSPORTE Y MOVILIZACIÓN DEL SEÑOR(A):";
			$html.="</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='760' HEIGHT=22 ALIGN='LEFT'>(NOMBRE DEL PACIENTE) ".$datos['nombrpa']." CON ".$datos['pacient']."";
			$html.="</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='760' HEIGHT=22 ALIGN='LEFT'>CORRESPONDE A LA ENTIDAD CON RAZÓN SOCIAL:";
			$html.="</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='760' HEIGHT=22 ALIGN='LEFT'>".$datos['empresa']." CON ".$datos['tipo_id_tercero']."".' - '."".$datos['id']."";
			$html.="</TD>";
			$html.="</TR>";
			$html.="</TABLE>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD ALIGN='CENTER' WIDTH='760'>";
		$html.="<br>__________________________________________________________<br>";
		$html.="FIRMA Y SELLO DE LA ENTIDAD";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD ALIGN='RIGHT' WIDTH='760'>";
		$html.="<FONT SIZE='6'>"._SIIS_APLICATION_TITLE."</FONT>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="</TABLE>";
		$pdf->WriteHTML($html);
		$pdf->SetLineWidth(0.7);
		//$pdf->SetFillColor(192);
		$pdf->RoundedRect(7, 5, 202, 120, 3.5, '');
		$pdf->Output($Dir,'F');
		return True;
	}

?>
