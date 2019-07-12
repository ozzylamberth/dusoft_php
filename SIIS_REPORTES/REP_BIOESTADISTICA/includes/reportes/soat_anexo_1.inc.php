<?php

/**
 * $Id: soat_anexo_1.inc.php,v 1.2 2005/06/07 18:40:58 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Archivo que imprime el formulario anexo1 que se entrega al FOSYGA
 */

	function GenerarSoatAnexo1($datos)
	{
		$Dir="cache/fosyga_anexo1.pdf";
		require("classes/fpdf/html_class.php");
		define('FPDF_FONTPATH','font/');
		$pdf=new PDF('P','mm','letter2');//legal
		$pdf->AddPage();
		$pdf->SetFont('Arial','',7);
		$html.="<TABLE BORDER='0' WIDTH='1520'>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25 ALIGN='CENTER'>";
		$html.="<b>REPUBLICA DE COLOMBIA</b>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25 ALIGN='CENTER'>";
		$html.="<b>MINISTERIO DE LA PROTECCIÓN SOCIAL</b>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' ALIGN='CENTER'><br>";
		$html.="FORMULARIOS DE MONTOS MÍNIMOS DE RECLAMACIONES CON CARGO A LA SUBCUENTA ECAT DEL FOSYGA, COMO";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' ALIGN='CENTER'>";
		$html.="RESULTADO DE SERVICIOS DE SALUD PRESTADORA A VÍCTIMAS DE ACCIDENTES DE TRÁNSITO Y EVENTOS CATASTRÓFICOS";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' ALIGN='CENTER'><br>";
		$html.="ANEXO No. 1";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' ALIGN='CENTER'>";
		$html.="FORECAT - CONSOLIDADO";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='140' HEIGHT=25>Fecha de radicación:</TD>";
		$html.="<TD WIDTH='240' HEIGHT=25>".$datos['fechadradi']."</TD>";
		$html.="<TD WIDTH='140' HEIGHT=25>No. Radicado:</TD>";
		$html.="<TD WIDTH='240' HEIGHT=25>".$datos['numeroradi']."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25 ALIGN='CENTER'><br>";
		$html.="<b>I. DATOS DE LA INSTITUCIÓN PRESTADORA DE SERVICIOS DE SALUD</b>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='140' HEIGHT=25 ALIGN='LEFT'><br>Razón Social:</TD>";
		$html.="<TD WIDTH='620' HEIGHT=25>".$datos['razon_social']."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='140' HEIGHT=25>Código IPS:</TD>";
		$html.="<TD WIDTH='240' HEIGHT=25>".$datos['codigo_sgsss']."</TD>";
		$html.="<TD WIDTH='140' HEIGHT=25>".$datos['tipo_id_tercero'].":</TD>";
		$html.="<TD WIDTH='240' HEIGHT=25>".$datos['id']."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='140' HEIGHT=25>Dirección:</TD>";
		$html.="<TD WIDTH='620' HEIGHT=25>".$datos['direccion']."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='140' HEIGHT=25>Departamento:</TD>";
		$html.="<TD WIDTH='240' HEIGHT=25>".$datos['deparempre']." Cód.: ".$datos['tipo_dpto_id']."</TD>";
		$html.="<TD WIDTH='140' HEIGHT=25>Teléfono:</TD>";
		$html.="<TD WIDTH='240' HEIGHT=25>".$datos['telefonos']."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='140' HEIGHT=25>Municipio:</TD>";
		$html.="<TD WIDTH='620' HEIGHT=25>".$datos['tipo_mpio_id']." Cód.: ".$datos['tipo_mpio_id']."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25 ALIGN='CENTER'><br>";
		$html.="<b>II. PERIODO RECLAMADO</b>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='140' HEIGHT=25 ALIGN='LEFT'><br>Periodo Reclamado:</TD>";
		$html.="<TD WIDTH='240' HEIGHT=25>".$datos['periodorec']."</TD>";
		$html.="<TD WIDTH='140' HEIGHT=25>Fecha Inicial:</TD>";
		$html.="<TD WIDTH='240' HEIGHT=25>".$datos['fechainici']."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='140' HEIGHT=25 ALIGN='LEFT'>-</TD>";
		$html.="<TD WIDTH='240' HEIGHT=25>-</TD>";
		$html.="<TD WIDTH='140' HEIGHT=25>Fecha Final:</TD>";
		$html.="<TD WIDTH='240' HEIGHT=25>".$datos['fechafinal']."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25 ALIGN='CENTER'><br>";
		$html.="<b>III. CONSOLIDADO POR EVENTO</b>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='200' HEIGHT=25 ALIGN='LEFT'><br>Tipo de Evento</TD>";
		$html.="<TD WIDTH='280' HEIGHT=25 ALIGN='CENTER'>No. de Reclamaciones</TD>";
		$html.="<TD WIDTH='280' HEIGHT=25 ALIGN='CENTER'>Valor</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='200' HEIGHT=25 ALIGN='LEFT'>-</TD>";
		$html.="<TD WIDTH='280' HEIGHT=25 ALIGN='CENTER'>PENDIENTE</TD>";
		$html.="<TD WIDTH='5' HEIGHT=25 ALIGN='LEFT'>$</TD>";
		$html.="<TD WIDTH='180' HEIGHT=25 ALIGN='RIGHT'>PENDIENTE</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='200' HEIGHT=25 ALIGN='LEFT'>TOTAL</TD>";
		$html.="<TD WIDTH='280' HEIGHT=25 ALIGN='CENTER'>PENDIENTE</TD>";
		$html.="<TD WIDTH='5' HEIGHT=25 ALIGN='LEFT'>$</TD>";
		$html.="<TD WIDTH='180' HEIGHT=25 ALIGN='RIGHT'>PENDIENTE</TD>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25 ALIGN='CENTER'><br>";
		$html.="<b>IV. DECLARACIÓN DE LA INSTITUCIÓN PRESTADORA DE SERVICIOS DE SALUD</b>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25 ALIGN='LEFT'><br>";
		$html.="Como Representante Legal y Revisor Fiscal o Contador de la institución prestadora de servicios de salud, declaramos bajo la gravedad de juramento:";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' ALIGN='LEFT'><br>";
		$html.="1) Que la información contenida en este formulario es cierta y podrá ser verificada por la Dirección General de Financiamiento del Ministerio de la Protección Social, por el";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' ALIGN='LEFT'>";
		$html.="   Administrador Fiduciario del Fondo de Solidaridad y Garantia FOSYGA, por la Superintendencia Nacional de Salud o la Contraloria General de la República, de no ser así,";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' ALIGN='LEFT'>";
		$html.="   acepto todas las consecuencias legales que produzca esta situación.";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' ALIGN='LEFT'>";
		$html.="2) Que las reclamaciones incluidas en el medio magnético cumplan con los requisitos establecidos en los Decretos Ley 1032 de 1991, 663 de 1993 y 1281 de 2002 y";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' ALIGN='LEFT'>";
		$html.="   Decretos 2878 de 1991 y 1283 de 1996 y demás normas que lo adicionen, sustituyan o modifiquen.";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' ALIGN='LEFT'>";
		$html.="3) Que la institución conserva las reclamaciones individuales con el completo de los requisitos establecidos con los Decretos Ley 1032 de 1991, 663 de 1993 y 1281 de 2002 y";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' ALIGN='LEFT'>";
		$html.="   decretos 2878 de 1991 y 1283 de 1996 y demás normas que lo adicionan, sustituyan o modifiquen.";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' ALIGN='LEFT'>";
		$html.="4) Que no se ha realizado fraccionamiento de servicios para incluirlos por este sistema de pago.";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' ALIGN='LEFT'>";
		$html.="5) Que los servicios de salud fueron prestados a víctimas de accidentes de tránsito ocasionados por vehículos no identificados o no asegurados o excedentes, de acuerdo con";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' ALIGN='LEFT'>";
		$html.="   lo establecido en las normas vigentes.";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR><br><br><br>";
		$html.="<TD ALIGN='CENTER' WIDTH='380'>";
		$html.="__________________________________________________________";
		$html.="</TD>";
		$html.="<TD ALIGN='CENTER' WIDTH='380'>";
		$html.="__________________________________________________________";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR><br>";
		$html.="<TD ALIGN='CENTER' WIDTH='380'>";
		$html.="Firma Representante Legal";
		$html.="</TD>";
		$html.="<TD ALIGN='CENTER' WIDTH='380'>";
		$html.="Firma del Revisor Fiscal o Contador";
		$html.="</TD>";
		$html.="</TR>";
		$html.="</TABLE>";
		$pdf->WriteHTML($html);
		$pdf->SetLineWidth(0.3);
		$pdf->RoundedRect(7, 6, 202, 52, 3.5, '');
		$pdf->RoundedRect(7, 60, 202, 32, 3.5, '');
		$pdf->RoundedRect(7, 94, 202, 21, 3.5, '');
		$pdf->RoundedRect(7, 117, 202, 25, 3.5, '');
		$pdf->RoundedRect(7, 144, 202, 115, 3.5, '');
		$pdf->Output($Dir,'F');
		return True;
	}

/*
					IncludeLib("reportes/GeneradorHtmlPdf"); //car
					$HTML_WEB_PAGE=Open_Tags_Html(date("d-m-Y"));
					$CABECERA = "	<TABLE WIDTH=\"100%\" border=\"1\" align=\"center\">";
					$CABECERA .= "			<TR>";
					$CABECERA.= "				<TD align='center' ><font size='2' color='black'><b>".$_SESSION['ESTACION_ENFERMERIA']['EMP']."</b></font></TD>";
					$CABECERA.= "			</TR>";
					$CABECERA .= "			<TR align=justify>";
					$TITULO='REPORTE DE PACIENTES INTERNOS  DE LA ESTACION :'." ".$_SESSION['ESTACION_ENFERMERIA']['NOM'];
					$CABECERA.= "				<TD><font color='black' size=2><b>$TITULO</b></font></TD>";
					$CABECERA.= "			</TR>";
		 			$CABECERA.="</TABLE>";
					$HTML_WEB_PAGE .=Get_Header($CABECERA,'left');
*/
					/***** generamos el html ********/
/*
					$HTML_WEB_PAGE.="<table width='100%' border=1>";
					$HTML_WEB_PAGE.="  <TR bgcolor='#CCCCCC'><font size='3'><b>";
					$HTML_WEB_PAGE.="<TD  WIDTH='70'>PIEZA</TD>";
					$HTML_WEB_PAGE.="<TD  WIDTH='70'>CAMA</TD>";
					$HTML_WEB_PAGE.="<TD  WIDTH='70'>NOMBRE</TD>";
					$HTML_WEB_PAGE.="<TD  WIDTH='70'>FECHA INGRESO</TD>";
					$HTML_WEB_PAGE.="<TD  WIDTH='70'>PLAN</TD>";
					$HTML_WEB_PAGE.="<TD  WIDTH='70'>CUENTA</TD>";
					$HTML_WEB_PAGE.="  </b></font></TR>";
					for($i=0;$i<sizeof($arr);$i++)
					{
						if( $i % 2){ $estilo2='#CCCCCC';}
						else {$estilo2='#DDDDDD';}
						$va=$this->Habitacion($arr[$i][cuenta]);
						$HTML_WEB_PAGE.="<TR>";
						if(empty($va[0][pieza])){$pieza="---";}else{$pieza=$va[0][pieza];}
						if(empty($va[0][cama])){$cama="---";}else{$cama=$va[0][cama];}
						$HTML_WEB_PAGE.="  <TD  WIDTH='70'><font size='1'>".$pieza."</font></TD>";
						$HTML_WEB_PAGE.="  <TD  WIDTH='70'><font size='1'>".$cama."</font></TD>";
						$d=" ";
						$nombre =$arr[$i][primer_nombre].$d.$arr[$i][segundo_nombre].$d.$arr[$i][primer_apellido].$d.$arr[$i][segundo_apellido];
						//$nombre =$arr[$i][primer_nombre].$d.$arr[$i][primer_apellido];
						$HTML_WEB_PAGE.="  <TD  WIDTH='260'><font size='1'>".$nombre."</font></TD>";
						$HTML_WEB_PAGE.="  <TD WIDTH='100'><font size='1'>".$arr[$i][fec_ing]."</font></TD>";
						$nombre_plan=$this->Plan($arr[$i][plan]);
						$HTML_WEB_PAGE.="  <TD WIDTH='155'><font size='1'>".$nombre_plan."</font></TD>";
						$HTML_WEB_PAGE.="  <TD WIDTH='75'><font size='1'>".$arr[$i][cuenta]."</font></TD>";
						$HTML_WEB_PAGE.="</TR>";
					}
						$HTML_WEB_PAGE.="</table>";
						$HTML_WEB_PAGE .=Close_Tags_Html();
/*
		//echo $HTML_WEB_PAGE;exit;
		$RUTA=GetDatos_A_Generar_Html_a_Pdf($HTML_WEB_PAGE);
        return $RUTA;
*/

?>
