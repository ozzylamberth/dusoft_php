<?php

/**
 * $Id: soat_constancia.inc.php,v 1.2 2005/06/07 18:40:58 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Archivo que imprime la constancia de atención al paciente
 */

	function GenerarSoatConstancia($datos)
	{
		$Dir="cache/constancia_atencion.pdf";
		require("classes/fpdf/html_class.php");
		define('FPDF_FONTPATH','font/');
		$pdf=new PDF('P','mm','letter');
		$pdf->AddPage();
		$pdf->SetFont('Arial','',10);
		$html ="<TABLE>";
		$html.="<TR><TD>";
		if(is_file('images/logocliente.png'))
		{
			$html.="".$pdf->image('images/logocliente.png',14,16,28)."";//$pdf->image('classes/fpdf/clinicaoccidente.gif'
		}
		$html.="</TD></TR>";
		$html.="</TABLE>";
		$html.="<TABLE BORDER='0' WIDTH='1520'>";
		$html.="<TR>";
		$html.="<TD ALIGN='CENTER' WIDTH='760'><br><br>";
		$html.="<FONT SIZE='30'><b>".$datos['razon_social']."</b></FONT>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD ALIGN='CENTER' WIDTH='760'>";
		$html.="<FONT SIZE='30'><b>".$datos['tipo_id_tercero']."".' '."".$datos['id']."</b></FONT>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD ALIGN='CENTER' WIDTH='760'><br><br>";
		$html.="<FONT SIZE='30'><b>EL SUSCRITO JEFE DE FACTURACIÓN</b></FONT>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD ALIGN='CENTER' WIDTH='760'>";
		$html.="<FONT SIZE='30'><b>DE LA ".$datos['razon_social']."</b></FONT>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD ALIGN='CENTER' WIDTH='760'><br><br>";
		$html.="<FONT SIZE='30'><b>CERTIFICA</b></FONT>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD ALIGN='LEFT' WIDTH='760'><br><br>";
		$html.="<FONT SIZE='26'>Que el paciente ".$datos['nombrepaci']." identificado con ".$datos['descripcion']." No. ".$datos['paciente_id']."</FONT>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD ALIGN='LEFT' WIDTH='760'>";
		if($datos['estado']==1)
		{
			$html.="<FONT SIZE='26'>está siendo atendido en nuestra institución, víctima de accidente de tránsito.</FONT>";
		}
		else
		{
			$html.="<FONT SIZE='26'>fue atendido en nuestra institución, víctima de accidente de tránsito.</FONT>";
		}
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD ALIGN='LEFT' WIDTH='760'><br>";
		$html.="<FONT SIZE='26'>Ingresó el día ".$datos['dia']." de ".$datos['mes']." de ".$datos['ano']." presentado póliza de seguro obligatorio de la</FONT>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD ALIGN='LEFT' WIDTH='760'>";
		$html.="<FONT SIZE='26'>compañía ".$datos['nombre_tercero']." No. ".$datos['poliza']."</FONT>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD ALIGN='CENTER' WIDTH='760'><br><br>";
			$html.="<TABLE BORDER='0' WIDTH='1520'>";
			$html.="<TR>";
			$html.="<TD WIDTH='360' HEIGHT=22 ALIGN='LEFT'>".'     '."Facturado a la COMPAÑÍA $</TD>";
			$html.="<TD WIDTH='250' HEIGHT=22 ALIGN='RIGHT'>".$datos['compania']."</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='360' HEIGHT=22 ALIGN='LEFT'>".'     '."Facturado al CONSORCIO FISALUD $</TD>";
			$html.="<TD WIDTH='250' HEIGHT=22 ALIGN='RIGHT'>".$datos['consorcio']."</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='360' HEIGHT=22 ALIGN='LEFT'>".'     '."TOTAL FACTURADO</TD>";
			$html.="<TD WIDTH='250' HEIGHT=22 ALIGN='RIGHT'>".$datos['suma']."</TD>";
			$html.="</TR>";
			$html.="</TABLE>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD ALIGN='LEFT' WIDTH='760'><br><br>";
		$html.="<FONT SIZE='26'>OBSERVACIÓN(ES): ".substr($datos['observacion'],0,120)."</FONT>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD ALIGN='LEFT' WIDTH='760'>";
		$html.="<FONT SIZE='26'>".substr($datos['observacion'],120,140)."</FONT>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD ALIGN='LEFT' WIDTH='760'>";
		$html.="<FONT SIZE='26'>".substr($datos['observacion'],260,140)."</FONT>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD ALIGN='LEFT' WIDTH='760'>";
		$html.="<FONT SIZE='26'>".substr($datos['observacion'],400,140)."</FONT>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD ALIGN='LEFT' WIDTH='760'>";
		$html.="<FONT SIZE='26'>".substr($datos['observacion'],540,140)."</FONT>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD ALIGN='LEFT' WIDTH='760'>";
		$html.="<FONT SIZE='26'>".substr($datos['observacion'],680,140)."</FONT>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD ALIGN='LEFT' WIDTH='760'><br><br>";
		$html.="<FONT SIZE='26'>DADO EN TULUÁ A LOS ".$datos['diaact']." DÍAS DEL MES DE ".$datos['mesact']." DE ".$datos['anoact']."</FONT>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD ALIGN='LEFT' WIDTH='760'><br><br>";
		$html.="<FONT SIZE='26'>ATENTAMENTE,</FONT>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD ALIGN='LEFT' WIDTH='760'>";
		$html.="<br><br><br>__________________________________________________________<br>";
		$html.="NOMBRE: ";
		$html.="<br>";
		$html.="JEFE DE FACTURACIÓN";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD ALIGN='RIGHT' WIDTH='760'>";
		$html.="<FONT SIZE='6'>"._SIIS_APLICATION_TITLE."</FONT>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="</TABLE>";
		$pdf->WriteHTML($html);
		//$pdf->SetLineWidth(0.7);
		//$pdf->SetFillColor(192);
		//$pdf->RoundedRect(7, 5, 202, 120, 3.5, '');
		$pdf->Output($Dir,'F');
		return True;
	}

?>
