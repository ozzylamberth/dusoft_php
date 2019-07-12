<?php

/**
 * $Id: soat_reclamo_dinero.inc.php,v 1.5 2007/01/29 23:19:50 carlos Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Archivo que imprime el formulario único de reclamación de entidades
 */

	function GenerarSoatReclamoDinero($datos)
	{
		UNSET($_SESSION['REPORTES']['VARIABLE']);
		IncludeLib('funciones_admision');
		$Dir="cache/reclamacion_entidades.pdf";
		require("classes/fpdf/html_class.php");
		define('FPDF_FONTPATH','font/');
		$pdf=new PDF('P','mm','letter2');//legal
		$pdf->AddPage();
		$pdf->SetFont('Arial','',7);
		$html.="<TABLE BORDER='0' WIDTH='1520'>";
		/**/
		$html.="<TR>";
		$html.="<TD WIDTH='360' HEIGHT=25 ALIGN='LEFT'>";
		$html.="FECHA DE AVISO: _____________________________";
		$html.="</TD>";
		$html.="<TD WIDTH='400' HEIGHT=25 ALIGN='LEFT'>";
		$html.="VALOR RECLAMADO $: _______________________________________";
		$html.="</TD>";
		$html.="</TR>";
		/**/
		$html.="<TR>";
		$html.="<TD ALIGN='CENTER' WIDTH='760'><br>";
		$html.="<FONT SIZE='26'><b>FORMULARIO ÚNICO DE RECLAMACIÓN DE ENTIDADES HOSPITALARIAS<br>POR EL SEGURO OBLIGARIO DE ACCIDENTES DE TRÁNSITO</b></FONT>";
		$html.="</TD>";
		$html.="</TR>";
		/**/
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25 ALIGN='LEFT'>";
		$html.="<b>1- DATOS DEL CENTRO ASISTENCIAL</b>";
		$html.="</TD>";
		$html.="</TR>";
		/**/
		$html.="<TR>";
		$html.="<TD WIDTH='60' HEIGHT=25 ALIGN='LEFT'>EMPRESA:</TD>";
		$html.="<TD WIDTH='510' HEIGHT=25>".$datos['empresa']."</TD>";
		$html.="<TD WIDTH='30' HEIGHT=25 ALIGN='LEFT'>CON:</TD>";
		$html.="<TD WIDTH='160' HEIGHT=25>".$datos['tipo_id_tercero']."".' - '."".$datos['id']."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='65' HEIGHT=25 ALIGN='LEFT'>DIRECCIÓN:</TD>";
		$html.="<TD WIDTH='245' HEIGHT=25>".$datos['direccion']."</TD>";
		$html.="<TD WIDTH='45' HEIGHT=25 ALIGN='LEFT'>CIUDAD:</TD>";
		$html.="<TD WIDTH='215' HEIGHT=25>".$datos['muniempresa']."</TD>";
		$html.="<TD WIDTH='60' HEIGHT=25 ALIGN='LEFT'>TELÉFONO:</TD>";
		$html.="<TD WIDTH='130' HEIGHT=25>".$datos['telefonos']."</TD>";
		$html.="</TR>";
		/**/
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25 ALIGN='LEFT'><br>";
		$html.="<b>2- DATOS DEL ACCIDENTADO</b>";
		$html.="</TD>";
		$html.="</TR>";
		/**/
		$html.="<TR>";
		$html.="<TD WIDTH='310' HEIGHT=25 ALIGN='LEFT'>  <b>2.1- INFORMACIÓN DEL ACCIDENTADO</b></TD>";
		$html.="<TD WIDTH='35' HEIGHT=25>EDAD:</TD>";
		$html.="<TD WIDTH='225' HEIGHT=25>".$datos['edad']."</TD>";
		$html.="<TD WIDTH='35' HEIGHT=25>SEXO:</TD>";
		$html.="<TD WIDTH='155' HEIGHT=25>".$datos['dessexo']."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='125' HEIGHT=25 ALIGN='LEFT'>  APELLIDOS Y NOMBRE:</TD>";
		$html.="<TD WIDTH='445' HEIGHT=25>".$datos['nombrpa']."</TD>";
		$html.="<TD WIDTH='30' HEIGHT=25 ALIGN='LEFT'>CON:</TD>";
		$html.="<TD WIDTH='160' HEIGHT=25>".$datos['pacient']."</TD>";
		$html.="</TR>";
//LUGAR EXPEDICION DOC
		if($datos['lugar_expedicion_documento'])
		{
		$html.="<TR>";
		$html.="<TD WIDTH='125' HEIGHT=25 ALIGN='LEFT'>&nbsp;</TD>";
		$html.="<TD WIDTH='445' HEIGHT=25>&nbsp;</TD>";
		$html.="<TD WIDTH='30' HEIGHT=25 ALIGN='LEFT'>DE:</TD>";
		$html.="<TD WIDTH='160' HEIGHT=25>".$datos['lugar_expedicion_documento']."</TD>";
		$html.="</TR>";
		}
//LUGAR EXPEDICION DOC
		$html.="<TR>";
		$html.="<TD WIDTH='70' HEIGHT=25 ALIGN='LEFT'>  DIRECCIÓN:</TD>";
		if($datos['residencia_direccion']==NULL)
		{
			$datos['residencia_direccion']='****';
		}
		$html.="<TD WIDTH='240' HEIGHT=25>".$datos['residencia_direccion']."</TD>";
		$html.="<TD WIDTH='45' HEIGHT=25 ALIGN='LEFT'>CIUDAD:</TD>";
		$html.="<TD WIDTH='215' HEIGHT=25>".$datos['munipaciente']."</TD>";
		$html.="<TD WIDTH='60' HEIGHT=25 ALIGN='LEFT'>TELÉFONO:</TD>";
		if($datos['residencia_telefono']==NULL)
		{
			$datos['residencia_telefono']='****';
		}
		$html.="<TD WIDTH='130' HEIGHT=25>".$datos['residencia_telefono']."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='160' HEIGHT=25 ALIGN='LEFT'>  CONDICIÓN EL ACCIDENTADO:</TD>";
		if($datos['descondicion']==NULL)
		{
			$datos['descondicion']='****';
		}
		$html.="<TD WIDTH='150' HEIGHT=25>".$datos['descondicion']."</TD>";
		$html.="<TD WIDTH='45' HEIGHT=25 ALIGN='LEFT'>FECHA:</TD>";
		$html.="<TD WIDTH='215' HEIGHT=25>".$datos['fecha_accidente']."</TD>";
		$html.="<TD WIDTH='35' HEIGHT=25 ALIGN='LEFT'>HORA:</TD>";
		$html.="<TD WIDTH='155' HEIGHT=25>".$datos['hora']."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25 ALIGN='LEFT'>  <b>2.2- IDENTIFICACIÓN DEL ACCIDENTE</b></TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='200' HEIGHT=25 ALIGN='LEFT'>  SITIO DÓNDE OCURRIÓ EL ACCIDENTE:</TD>";
		if($datos['sitio_accidente']==NULL)
		{
			$datos['sitio_accidente']='****';
		}
		$html.="<TD WIDTH='560' HEIGHT=25>".$datos['sitio_accidente']."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='65' HEIGHT=25 ALIGN='LEFT'>  MUNICIPIO:</TD>";
		$html.="<TD WIDTH='245' HEIGHT=25>".$datos['muniaccidente']."</TD>";
		$html.="<TD WIDTH='90' HEIGHT=25 ALIGN='LEFT'>DEPARTAMENTO:</TD>";
		$html.="<TD WIDTH='250' HEIGHT=25>".$datos['departamento']."</TD>";
		$html.="<TD WIDTH='35' HEIGHT=25 ALIGN='LEFT'>ZONA:</TD>";
		$html.="<TD WIDTH='65' HEIGHT=25>".$datos['deszona']."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='300' HEIGHT=25 ALIGN='LEFT'>  INFORMACIÓN DEL ACCIDENTE (Relato breve de los hechos):</TD>";
		if($datos['informe_accidente']==NULL)
		{
			$datos['informe_accidente']='****';
		}
		$html.="<TD WIDTH='460' HEIGHT=25>".substr($datos['informe_accidente'],0,70)."</TD>";
		$html.="</TR>";
		if(substr($datos['informe_accidente'],70,170))
		{
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['informe_accidente'],70,170)."</TD>";
		$html.="</TR>";
		}
		if(substr($datos['informe_accidente'],170,270))
		{
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['informe_accidente'],170,270)."</TD>";
		$html.="</TR>";
		}
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25 ALIGN='LEFT'>  <b>2.3- INFORMACIÓN DEL VEHÍCULO</b></TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='50' HEIGHT=25 ALIGN='LEFT'>  MARCA:</TD>";
		if($datos['marca']==NULL)
		{
			$datos['marca']='****';
		}
		$html.="<TD WIDTH='260' HEIGHT=25>".$datos['marca_vehiculo']."</TD>";
 		$html.="<TD WIDTH='40' HEIGHT=25 ALIGN='LEFT'>PLACA:</TD>";
		if($datos['placa_vehiculo']==NULL)
		{
			$datos['placa_vehiculo']='****';
		}
		$html.="<TD WIDTH='220' HEIGHT=25>".$datos['placa_vehiculo']."</TD>";
		$html.="<TD WIDTH='30' HEIGHT=25 ALIGN='LEFT'>TIPO:</TD>";
		if($datos['tipo_vehiculo']==NULL)
		{
			$datos['tipo_vehiculo']='****';
		}
		$html.="<TD WIDTH='160' HEIGHT=25>".$datos['tipo_vehiculo']."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='140' HEIGHT=25 ALIGN='LEFT'>  NOMBRE ASEGURADORA:</TD>";
		$html.="<TD WIDTH='340' HEIGHT=25>".$datos['nombre_tercero']."</TD>";
		$html.="<TD WIDTH='120' HEIGHT=25 ALIGN='LEFT'>SUCURSAL O AGENCIA:</TD>";
		if($datos['sucursal']==NULL)
		{
			$datos['sucursal']='****';
		}
		$html.="<TD WIDTH='160' HEIGHT=25>".$datos['sucursal']."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='80' HEIGHT=25 ALIGN='LEFT'>  ASEGURADO:</TD>";
		$html.="<TD WIDTH='180' HEIGHT=25>".$datos['asegura']."</TD>";
		$html.="<TD WIDTH='70' HEIGHT=25 ALIGN='LEFT'>POLIZA SOAT:</TD>";
		$html.="<TD WIDTH='150' HEIGHT=25>".$datos['poliza']."</TD>";
		$html.="<TD WIDTH='40' HEIGHT=25 ALIGN='LEFT'>DESDE:</TD>";
		if($datos['vigencia_desde']=='//')
		{
			$datos['vigencia_desde']='****';
		}
		$html.="<TD WIDTH='100' HEIGHT=25>".$datos['vigencia_desde']."</TD>";
		$html.="<TD WIDTH='40' HEIGHT=25 ALIGN='LEFT'>HASTA:</TD>";
		if($datos['vigencia_hasta']=='//')
		{
			$datos['vigencia_hasta']='****';
		}
		$html.="<TD WIDTH='100' HEIGHT=25>".$datos['vigencia_hasta']."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='210' HEIGHT=25 ALIGN='LEFT'>  APELLIDOS Y NOMBRE DEL CONDUCTOR:</TD>";
		if($datos['apellidos_conductor']==NULL)
		{
			$datos['apellidos_conductor']='****';
		}
		if($datos['nombres_conductor']==NULL)
		{
			$datos['nombres_conductor']='****';
		}
		$html.="<TD WIDTH='360' HEIGHT=25>".$datos['apellidos_conductor']."".' '."".$datos['nombres_conductor']."</TD>";
		$html.="<TD WIDTH='30' HEIGHT=25 ALIGN='LEFT'>CON:</TD>";
		if($datos['tipo_id_conductor']==NULL)
		{
			$datos['tipo_id_conductor']='****';
		}
		if($datos['conductor_id']==NULL)
		{
			$datos['conductor_id']='****';
		}
		$html.="<TD WIDTH='160' HEIGHT=25>".$datos['tipo_id_conductor']."".' - '."".$datos['conductor_id']."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='70' HEIGHT=25 ALIGN='LEFT'>  DIRECCIÓN:</TD>";
		if($datos['direccion_conductor']==NULL)
		{
			$datos['direccion_conductor']='****';
		}
		$html.="<TD WIDTH='240' HEIGHT=25>".$datos['direccion_conductor']."</TD>";
		$html.="<TD WIDTH='45' HEIGHT=25 ALIGN='LEFT'>CIUDAD:</TD>";
		$html.="<TD WIDTH='215' HEIGHT=25>".$datos['munivehiculo']."</TD>";
		$html.="<TD WIDTH='60' HEIGHT=25 ALIGN='LEFT'>TELÉFONO:</TD>";
		if($datos['telefono_conductor']==NULL)
		{
			$datos['telefono_conductor']='****';
		}
		$html.="<TD WIDTH='130' HEIGHT=25>".$datos['telefono_conductor']."</TD>";
		$html.="</TR>";
		/**/
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25 ALIGN='LEFT'><br>";
		$html.="<b>3- DATOS SOBRE LA ATENCIÓN DEL ACCIDENTE</b>";
		$html.="</TD>";
		$html.="</TR>";
		/**/
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25 ALIGN='LEFT'>  <b>3.1- DATOS SOBRE LA ATENCIÓN DEL ACCIDENTE</b></TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='110' HEIGHT=25 ALIGN='LEFT'>  FECHA DE INGRESO:</TD>";
		if($datos['fecha_ingreso']=='//')
		{
			$datos['fecha_ingreso']='****';
		}
		if($datos['hora_ingreso']==NULL)
		{
			$datos['hora_ingreso']='****';
		}
		$html.="<TD WIDTH='90' HEIGHT=25>".$datos['fecha_ingreso']."</TD>";
		$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>HORA DE INGRESO:</TD>";
		$html.="<TD WIDTH='90' HEIGHT=25>".$datos['hora_ingreso']."</TD>";
		$html.="<TD WIDTH='120' HEIGHT=25 ALIGN='LEFT'>HISTORIA CLINICA No.:</TD>";
		$html.="<TD WIDTH='250' HEIGHT=25>".$datos['pacient']."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='110' HEIGHT=25 ALIGN='LEFT'>  FECHA DE EGRESO:</TD>";
		if($datos['fecha_egreso']=='//' OR $datos['fecha_egreso']==NULL)
		{
			$datos['fecha_egreso']='****';
		}
		$html.="<TD WIDTH='90' HEIGHT=25>".$datos['fecha_egreso']."</TD>";
		$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>DÍAS DE ESTANCIA:</TD>";
		if($datos['dias_estancia']==NULL)
		{
			$datos['dias_estancia']='****';
		}
		$html.="<TD WIDTH='90' HEIGHT=25>".$datos['dias_estancia']."</TD>";
		$html.="<TD WIDTH='80' HEIGHT=25 ALIGN='LEFT'>TRATAMIENTO:</TD>";
		if($datos['tratamiento']==NULL)
		{
			$datos['tratamiento']='****';
		}
		$html.="<TD WIDTH='310' HEIGHT=25>".$datos['tratamiento']."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='145' HEIGHT=25 ALIGN='LEFT'>  <b>DIAGNÓSTICO DE INGRESO:</b></TD>";
		if($datos['desc_diagnostico_in']==NULL)
		{
			$datos['desc_diagnostico_in']='****';
		}
		$html.="<TD WIDTH='615' HEIGHT=25>".substr($datos['desc_diagnostico_in'],0,105)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['desc_diagnostico_in'],105,135)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['desc_diagnostico_in'],240,135)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['desc_diagnostico_in'],375,135)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='145' HEIGHT=25 ALIGN='LEFT'>  <b>DIAGNÓSTICO DEFINITIVO:</b></TD>";
		if($datos['desc_diagnostico_de']==NULL)
		{
			$datos['desc_diagnostico_de']='****';
		}
		$html.="<TD WIDTH='615' HEIGHT=25>".substr($datos['desc_diagnostico_de'],0,105)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['desc_diagnostico_de'],105,135)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['desc_diagnostico_de'],240,135)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['desc_diagnostico_de'],375,135)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25 ALIGN='LEFT'>  <b>3.2- REMISIÓN</b></TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='130' HEIGHT=25 ALIGN='LEFT'>  PERSONA REMITIDA DE:</TD>";
		if($datos['descentro']==NULL)
		{
			$datos['descentro']='****';
			$datos['fecha_remision']='****';
			$datos['municentro']='****';
			$html.="<TD WIDTH='275' HEIGHT=25>****</TD>";
			$html.="<TD WIDTH='45' HEIGHT=25 ALIGN='LEFT'>CIUDAD:</TD>";
			$html.="<TD WIDTH='215' HEIGHT=25>****</TD>";
			$html.="<TD WIDTH='45' HEIGHT=25 ALIGN='LEFT'>FECHA:</TD>";
			$html.="<TD WIDTH='50' HEIGHT=25>****</TD>";
		}
		else
		{
			$html.="<TD WIDTH='275' HEIGHT=25>".$datos['empresa']."</TD>";
			$html.="<TD WIDTH='45' HEIGHT=25 ALIGN='LEFT'>CIUDAD:</TD>";
			$html.="<TD WIDTH='215' HEIGHT=25>".$datos['muniempresa']."</TD>";
			$html.="<TD WIDTH='45' HEIGHT=25 ALIGN='LEFT'>FECHA:</TD>";
			$html.="<TD WIDTH='50' HEIGHT=25>".$datos['fecha_remision']."</TD>";//FALTA PREGUNTAR ESTA FECHA
		}
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='130' HEIGHT=25 ALIGN='LEFT'>  PERSONA REMITIDA A:</TD>";
		$html.="<TD WIDTH='275' HEIGHT=25>".$datos['descentro']."</TD>";
		$html.="<TD WIDTH='45' HEIGHT=25 ALIGN='LEFT'>CIUDAD:</TD>";
		$html.="<TD WIDTH='215' HEIGHT=25>".$datos['municentro']."</TD>";
		$html.="<TD WIDTH='45' HEIGHT=25 ALIGN='LEFT'>FECHA:</TD>";
		$html.="<TD WIDTH='50' HEIGHT=25>".$datos['fecha_remision']."</TD>";
		$html.="</TR>";
		/**/
		$html.="<TR>";
		$html.="<TD WIDTH='400' HEIGHT=25 ALIGN='LEFT'><br>";
		$html.="<b>4- DATOS SOBRE LA MUERTE DEL ACCIDENTADO</b>(Estos datos no tienen valor legal)";
		$html.="</TD>";
		$html.="</TR>";
		/**/
		$html.="<TR>";
		$html.="<TD WIDTH='185' HEIGHT=25 ALIGN='LEFT'>  CAUSA INMEDIATA DE LA MUERTE:</TD>";
		if($datos['causa_muerte']==NULL)
		{
			$datos['causa_muerte']='****';
		}
		$html.="<TD WIDTH='575' HEIGHT=25>".substr($datos['causa_muerte'],0,100)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['causa_muerte'],100,130)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['causa_muerte'],230,130)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='125' HEIGHT=25 ALIGN='LEFT'>  FECHA DE LA MUERTE:</TD>";
		$html.="<TD WIDTH='275' HEIGHT=25>".FechaStamp($datos[fecha_defuncion])."</TD>";
		$html.="<TD WIDTH='110' HEIGHT=25 ALIGN='LEFT'>HORA DE LA MUERTE:</TD>";
		$html.="<TD WIDTH='250' HEIGHT=25>".HoraStamp($datos[fecha_defuncion])."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='410' HEIGHT=25 ALIGN='LEFT'>  APELLIDOS Y NOMBRE DEL MÉDICO QUE FIRMÓ EL CERTIFICADO DE DEFUNCIÓN:</TD>";
		$html.="<TD WIDTH='350' HEIGHT=25>".$datos[profesional_defuncion]."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='125' HEIGHT=25 ALIGN='LEFT'>  REGISTRO MÉDICO No.:</TD>";
		$html.="<TD WIDTH='380' HEIGHT=25>".$datos[registro_salud_departamental]."</TD>";
		$html.="<TD WIDTH='20' HEIGHT=25 ALIGN='LEFT'>DE:</TD>";
		$html.="<TD WIDTH='235' HEIGHT=25>****</TD>";
		$html.="</TR>";
		/**/
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25 ALIGN='LEFT'><br>";
		$html.="<b>5- DECLARACIÓN DEL CENTRO ASISTENCIAL</b>";
		$html.="</TD>";
		$html.="</TR>";
		/**/
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25 ALIGN='LEFT'><FONT SIZE='4'>
		EN REPRESENTACIÓN DEL CENTRO ASISTENCIAL EN MENCIÓN, DECLARO BAJO LA GRAVEDAD DE JURAMENTO, QUE LA INFORMACIÓN DILIGENCIADA EN
		</FONT></TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25 ALIGN='LEFT'><FONT SIZE='4'>
		ESTE DOCUMENTO ES CIERTA Y PUEDE SER VERIFICADA POR LA COMPAÑIA DE SEGUROS Y/O FONSAT, DENTRO DE LOS (30) DIAS SIGUIENTES A LA FECHA
		</FONT></TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25 ALIGN='LEFT'><FONT SIZE='4'>
		DE PRESENTACIÓN, DE NO SER ASÍ, ACEPTO TODAS LAS CONSECUENCIAS LEGALES QUE PRODUZCA ESTA SITUACIÓN.
		</FONT></TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<br><TD ALIGN='CENTER' WIDTH='760'>";
		$html.="<br>__________________________________________________________<br>";
		$html.="FIRMA Y SELLOS AUTORIZADOS";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD ALIGN='RIGHT' WIDTH='760'>";
		$html.="<FONT SIZE='6'>"._SIIS_APLICATION_TITLE."</FONT>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="</TABLE>";
		$pdf->WriteHTML($html);
		$pdf->SetLineWidth(0.3);
		$pdf->RoundedRect(7, 5, 202, 11, 3.5, '');
		$pdf->RoundedRect(7, 18, 202, 26, 3.5, '');
		if($datos['lugar_expedicion_documento'])
		{
		$pdf->RoundedRect(7, 46, 202, 74, 3.5, '');
		$pdf->RoundedRect(7, 122, 202, 65, 3.5, '');
		$pdf->RoundedRect(7, 189, 202, 32, 3.5, '');
		$pdf->RoundedRect(7, 223, 202, 40, 3.5, '');
		}
		else
		{
		$pdf->RoundedRect(7, 46, 202, 70, 3.5, '');
		$pdf->RoundedRect(7, 118, 202, 65, 3.5, '');
		$pdf->RoundedRect(7, 185, 202, 32, 3.5, '');
		$pdf->RoundedRect(7, 219, 202, 40, 3.5, '');
		}
		$pdf->Output($Dir,'F');
		return True;
	}

?>
