<?php

/**
 * $Id: soat_certificado_atencion.inc.php,v 1.2 2005/06/07 18:40:58 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Archivo que imprime el certificado de atención en urgencias
 */

	function GenerarSoatAtencion($datos)
	{
		$Dir="cache/certificado_atencion_medica.pdf";
		require("classes/fpdf/html_class.php");
		define('FPDF_FONTPATH','font/');
		$pdf=new PDF('P','mm','legal');//legal
		$pdf->AddPage();
		$pdf->SetFont('Arial','',7);
		$html.="<TABLE BORDER='0' WIDTH='1520'>";
		$html.="<TR>";
		$html.="<TD ALIGN='CENTER' WIDTH='760'>";
		$html.="<FONT SIZE='26'><b>CERTIFICADO DE ATENCIÓN MÉDICA PARA VÍCTIMAS DE ACCIDENTES DE TRÁNSITO</b></FONT>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD ALIGN='CENTER' WIDTH='760'>";
		$html.="<FONT SIZE='26'><b>EXPEDIDA POR LA INSTITUCIÓN PRESTADORA DE SALUD</b></FONT>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25 ALIGN='LEFT'><br>";
		$html.="El suscrito médico del servicio de Urgencias de la Institución prestadora de servicios";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".$datos['razon_social']."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='80' HEIGHT=25 ALIGN='LEFT'>Con domicilio en:</TD>";
		$html.="<TD WIDTH='340' HEIGHT=25>".$datos['direccion']."</TD>";
		$html.="<TD WIDTH='45' HEIGHT=25 ALIGN='LEFT'>Ciudad:</TD>";
		$html.="<TD WIDTH='295' HEIGHT=25>".$datos['municempre']."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='80' HEIGHT=25 ALIGN='LEFT'>Departamento:</TD>";
		$html.="<TD WIDTH='340' HEIGHT=25>".$datos['deparempre']."</TD>";
		$html.="<TD WIDTH='45' HEIGHT=25 ALIGN='LEFT'>Teléfono:</TD>";
		$html.="<TD WIDTH='295' HEIGHT=25>".$datos['telefonos']."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='300' HEIGHT=25 ALIGN='LEFT'>Certifica que atendió en el servicio de Urgencias al Señor(a):</TD>";
		$html.="<TD WIDTH='460' HEIGHT=25>".$datos['apellido']."".' '."".$datos['nombre']."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='80' HEIGHT=25 ALIGN='LEFT'>Identificado con:</TD>";
		$html.="<TD WIDTH='290' HEIGHT=25>".$datos['tipo_id_paciente_eve']."</TD>";
		$html.="<TD WIDTH='40' HEIGHT=25 ALIGN='LEFT'>No.:</TD>";
		$html.="<TD WIDTH='150' HEIGHT=25>".$datos['paciente_id_eve']."</TD>";
		$html.="<TD WIDTH='40' HEIGHT=25 ALIGN='LEFT'>de</TD>";
		$html.="<TD WIDTH='200' HEIGHT=25>".$datos['lugar_expedicion_eve']."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='80' HEIGHT=25 ALIGN='LEFT'>Residente en:</TD>";
		if($datos['residencia_direccion']==NULL)
		{
			$datos['residencia_direccion']='****';
		}
		$html.="<TD WIDTH='290' HEIGHT=25>".$datos['residencia_direccion']."</TD>";
		$html.="<TD WIDTH='40' HEIGHT=25 ALIGN='LEFT'>Ciudad:</TD>";
		if($datos['municipio']==NULL)
		{
			$datos['municipio']='****';
		}
		$html.="<TD WIDTH='350' HEIGHT=25>".$datos['municipio']."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='80' HEIGHT=25 ALIGN='LEFT'>Departamento:</TD>";
		if($datos['departamento']==NULL)
		{
			$datos['departamento']='****';
		}
		$html.="<TD WIDTH='290' HEIGHT=25>".$datos['departamento']."</TD>";
		$html.="<TD WIDTH='40' HEIGHT=25 ALIGN='LEFT'>Teléfono:</TD>";
		if($datos['residencia_telefono']==NULL)
		{
			$datos['residencia_telefono']='****';
		}
		$html.="<TD WIDTH='350' HEIGHT=25>".$datos['residencia_telefono']."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='300' HEIGHT=25 ALIGN='LEFT'>Quién según declaración de:</TD>";
		$html.="<TD WIDTH='460' HEIGHT=25>".$datos['apellidos_declara']."".' '."".$datos['nombres_declara']."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='80' HEIGHT=25 ALIGN='LEFT'>Identificado con:</TD>";
		$html.="<TD WIDTH='60' HEIGHT=25>".$datos['tipo_id_paciente']."</TD>";
		$html.="<TD WIDTH='30' HEIGHT=25 ALIGN='LEFT'>No.:</TD>";
		$html.="<TD WIDTH='130' HEIGHT=25>".$datos['declara_id']."</TD>";
		$html.="<TD WIDTH='60' HEIGHT=25 ALIGN='LEFT'>Expedida en:</TD>";
		$html.="<TD WIDTH='400' HEIGHT=25>".$datos['expedida']."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='280' HEIGHT=25 ALIGN='LEFT'>Fue víctima del accidente de Tránsito ocurrido el día</TD>";
		$fecha=explode(' ',$datos['fecha_accidente']);
		$fecha1=explode('-',$fecha[0]);
		$fecha2=explode(':',$fecha[1]);
		$html.="<TD WIDTH='480' HEIGHT=25>".$fecha1[2]."".' mes '."".$fecha1[1]."".' año '."".$fecha1[0]."".' a las '."".$fecha2[0]."".' : '."".$fecha2[1]."";
		$html.=" horas</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='280' HEIGHT=25 ALIGN='LEFT'>Ingresando al servicio de urgencias de esta institución el día</TD>";
		$fecha=explode(' ',$datos['fecha_ingreso']);
		$fecha1=explode('-',$fecha[0]);
		$fecha2=explode(':',$fecha[1]);
		$html.="<TD WIDTH='480' HEIGHT=25>".$fecha1[2]."".' mes '."".$fecha1[1]."".' año '."".$fecha1[0]."".' a las '."".$fecha2[0]."".' : '."".$fecha2[1]."";
		$html.=" horas con los siguientes hallazgos:</TD>";
		$html.="</TR>";
		if($datos['datos1_ta']==NULL)
		{
			$datos['datos1_ta']='****';
		}
		if($datos['datos2_fc']==NULL)
		{
			$datos['datos2_fc']='****';
		}
		if($datos['datos3_fr']==NULL)
		{
			$datos['datos3_fr']='****';
		}
		if($datos['datos4_te']==NULL)
		{
			$datos['datos4_te']='****';
		}
		if($datos['datos5_conciencia']==NULL)
		{
			$datos['datos5_conciencia']='****';
		}
		if($datos['datos5_conciencia']==1)
		{
			$uno=' X ';
		}
		else
		{
			$uno=' __ ';
		}
		if($datos['datos5_conciencia']==2)
		{
			$dos=' X ';
		}
		else
		{
			$dos=' __ ';
		}
		if($datos['datos5_conciencia']==3)
		{
			$tres=' X ';
		}
		else
		{
			$tres=' __ ';
		}
		if($datos['datos5_conciencia']==4)
		{
			$cuatro=' X ';
		}
		else
		{
			$cuatro=' __ ';
		}
		if($datos['datos6_glasgow']==NULL)
		{
			$datos['datos6_glasgow']='****';
		}
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25 ALIGN='LEFT'>Signos vitales:  TA  ".$datos['datos1_ta']."  mmHg  FC  ".$datos['datos2_fc']."  x min.  FR  ".$datos['datos3_fr']."  x min.  Tº  ".$datos['datos4_te']."  ºC</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25 ALIGN='LEFT'>Estado de conciencia:  Alerta  ".$uno."  Obnubilado  ".$dos."  Estuporoso  ".$tres." Coma  ".$cuatro."  Glasgow(7)  ".$datos['datos6_glasgow']."</TD>";
		$html.="</TR>";
		if($datos['estado_embriaguez']==1)
		{
			$uno=' X ';
		}
		else
		{
			$uno=' __ ';
		}
		if($datos['estado_embriaguez']==2)
		{
			$dos=' X ';
		}
		else
		{
			$dos=' __ ';
		}
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25 ALIGN='LEFT'>Estado de Embriaguez  SI  ".$uno."  NO  ".$dos."  (En caso positivo tomar muestra para alcoholemia u otras drogas)</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25 ALIGN='LEFT'>DATOS POSITIVOS</TD>";
		$html.="</TR>";
		if($datos['diagnostico1']==NULL)
		{
			$datos['diagnostico1']='NORMAL';
		}
		$html.="<TR>";
		$html.="<TD WIDTH='200' HEIGHT=25 ALIGN='LEFT'>Cabeza y Organos de los sentidos:</TD>";
		$html.="<TD WIDTH='560' HEIGHT=25 ALIGN='LEFT'>".substr($datos['diagnostico1'],0,90)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico1'],90,115)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico1'],205,115)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico1'],320,115)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico1'],435,115)."</TD>";
		$html.="</TR>";
		if($datos['diagnostico2']==NULL)
		{
			$datos['diagnostico2']='NORMAL';
		}
		$html.="<TR>";
		$html.="<TD WIDTH='200' HEIGHT=25 ALIGN='LEFT'>Cuello:</TD>";
		$html.="<TD WIDTH='560' HEIGHT=25 ALIGN='LEFT'>".substr($datos['diagnostico2'],0,90)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico2'],90,115)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico2'],205,115)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico2'],320,115)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico2'],435,115)."</TD>";
		$html.="</TR>";
		if($datos['diagnostico3']==NULL)
		{
			$datos['diagnostico3']='NORMAL';
		}
		$html.="<TR>";
		$html.="<TD WIDTH='200' HEIGHT=25 ALIGN='LEFT'>Torax y Cardiopulmonar:</TD>";
		$html.="<TD WIDTH='560' HEIGHT=25 ALIGN='LEFT'>".substr($datos['diagnostico3'],0,90)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico3'],90,115)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico3'],205,115)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico3'],320,115)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico3'],435,115)."</TD>";
		$html.="</TR>";
		if($datos['diagnostico4']==NULL)
		{
			$datos['diagnostico4']='NORMAL';
		}
		$html.="<TR>";
		$html.="<TD WIDTH='200' HEIGHT=25 ALIGN='LEFT'>Abdomen:</TD>";
		$html.="<TD WIDTH='560' HEIGHT=25 ALIGN='LEFT'>".substr($datos['diagnostico4'],0,90)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico4'],90,115)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico4'],205,115)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico4'],320,115)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico4'],435,115)."</TD>";
		$html.="</TR>";
		if($datos['diagnostico5']==NULL)
		{
			$datos['diagnostico5']='NORMAL';
		}
		$html.="<TR>";
		$html.="<TD WIDTH='200' HEIGHT=25 ALIGN='LEFT'>Genitourinario:</TD>";
		$html.="<TD WIDTH='560' HEIGHT=25 ALIGN='LEFT'>".substr($datos['diagnostico5'],0,90)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico5'],90,115)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico5'],205,115)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico5'],320,115)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico5'],435,115)."</TD>";
		$html.="</TR>";
		if($datos['diagnostico6']==NULL)
		{
			$datos['diagnostico6']='NORMAL';
		}
		$html.="<TR>";
		$html.="<TD WIDTH='200' HEIGHT=25 ALIGN='LEFT'>Pelvis:</TD>";
		$html.="<TD WIDTH='560' HEIGHT=25 ALIGN='LEFT'>".substr($datos['diagnostico6'],0,90)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico6'],90,115)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico6'],205,115)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico6'],320,115)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico6'],435,115)."</TD>";
		$html.="</TR>";
		if($datos['diagnostico7']==NULL)
		{
			$datos['diagnostico7']='NORMAL';
		}
		$html.="<TR>";
		$html.="<TD WIDTH='200' HEIGHT=25 ALIGN='LEFT'>Dorso y Extremidades:</TD>";
		$html.="<TD WIDTH='560' HEIGHT=25 ALIGN='LEFT'>".substr($datos['diagnostico7'],0,90)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico7'],90,115)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico7'],205,115)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico7'],320,115)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico7'],435,115)."</TD>";
		$html.="</TR>";
		if($datos['diagnostico8']==NULL)
		{
			$datos['diagnostico8']='NORMAL';
		}
		$html.="<TR>";
		$html.="<TD WIDTH='200' HEIGHT=25 ALIGN='LEFT'>Neurológico:</TD>";
		$html.="<TD WIDTH='560' HEIGHT=25 ALIGN='LEFT'>".substr($datos['diagnostico8'],0,90)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico8'],90,115)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico8'],205,115)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico8'],320,115)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico8'],435,115)."</TD>";
		$html.="</TR>";
		if($datos['diagnostico9']==NULL)
		{
			$datos['diagnostico9']='NORMAL';
		}
		$html.="<TR>";
		$html.="<TD WIDTH='200' HEIGHT=25 ALIGN='LEFT'>Impresión Diagnóstica:</TD>";
		$html.="<TD WIDTH='560' HEIGHT=25 ALIGN='LEFT'>".substr($datos['diagnostico9'],0,90)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico9'],90,115)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico9'],205,115)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico9'],320,115)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico9'],435,115)."</TD>";
		$html.="</TR>";
		if($datos['diagnostico_def']==NULL)
		{
			$datos['diagnostico_def']='****';
		}
		$html.="<TR>";
		$html.="<TD WIDTH='200' HEIGHT=25 ALIGN='LEFT'>Diagnóstico Definitivo:</TD>";
		$html.="<TD WIDTH='560' HEIGHT=25 ALIGN='LEFT'>".substr($datos['diagnostico_def'],0,90)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico_def'],90,115)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico_def'],205,115)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico_def'],320,115)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico_def'],435,115)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico_def'],550,115)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['diagnostico_def'],665,115)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD ALIGN='LEFT' WIDTH='360'>Nombres y Apellidos del Médico: ".$datos['nombre_tercero']."</TD>";
		$html.="<TD ALIGN='LEFT' WIDTH='400'>FIRMA Y SELLO __________________________________________________________</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD ALIGN='LEFT' WIDTH='760'>Registro Médico No. ".$datos['tarjeta_profesional']."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD ALIGN='RIGHT' WIDTH='760'><br>"._SIIS_APLICATION_TITLE."";
		$html.="</TD>";
		$html.="</TR>";
		$html.="</TABLE>";
		$pdf->WriteHTML($html);
		$pdf->SetLineWidth(0.3);
		$pdf->RoundedRect(7, 5, 202, 330, 3.5, '');
		$pdf->Output($Dir,'F');
		return True;
	}

?>
