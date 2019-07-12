<?php

/**
 * $Id: Soat_Anexo1.report.php,v 1.4 2006/09/20 16:42:04 carlos Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Archivo que imprime el formulario anexo1 que se entrega al FOSYGA
 */

class Soat_Anexo1_report
{
	function Soat_Anexo1_report($datos=array())
	{
		$this->datos=$datos;
		return true;
	}

	var $datos;
	//PARAMETROS PARA LA CONFIGURACION DEL REPORTE
	//NO MODIFICAR POR EL MOMENTO - DELEN UN TIEMPITO PARA TERMINAR EL DESARROLLO
	var $title       = '';
	var $author      = '';
	var $sizepage    = 'leter';
	var $Orientation = '';
	var $grayScale   = false;
	var $headers     = array();
	var $footers     = array();

	function CrearReporte()
	{
		$datos1=$this->BuscarDatosReporte1($this->datos['var']);
		$datos2=$this->BuscarDatosReporte2($this->datos['var']);
		$registros=sizeof($datos2);
		//$registros=32;
		$valor=0;
		for($i=0;$i<$registros;$i++)
		{
			$valor=$valor+$datos2[$i]['total_factura'];
		}
		$HTML_WEB_PAGE ="<HTML><BODY>";
		$HTML_WEB_PAGE.="<TABLE WIDTH='100%' BORDER=0>";
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD COLSPAN='4' ALIGN='CENTER'><FONT SIZE='1'>";
		$HTML_WEB_PAGE.="<b>REPÚBLICA DE COLOMBIA</b>";
		$HTML_WEB_PAGE.="</FONT></TD>";
		$HTML_WEB_PAGE.="</TR>";
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD COLSPAN='4' ALIGN='CENTER'><FONT SIZE='1'>";
		$HTML_WEB_PAGE.="<b>MINISTERIO DE LA PROTECCIÓN SOCIAL</b>";
		$HTML_WEB_PAGE.="</FONT></TD>";
		$HTML_WEB_PAGE.="</TR>";
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD COLSPAN='4' ALIGN='CENTER'><FONT SIZE='1'><br>";
		$HTML_WEB_PAGE.="FORMULARIOS DE MONTOS MÍNIMOS DE RECLAMACIONES CON CARGO A LA SUBCUENTA ECAT DEL FOSYGA, COMO<br>";
		$HTML_WEB_PAGE.="RESULTADO DE SERVICIOS DE SALUD PRESTADORA A VÍCTIMAS DE ACCIDENTES DE TRÁNSITO Y EVENTOS CATASTRÓFICOS<br></FONT>";
		$HTML_WEB_PAGE.="</TD>";
		$HTML_WEB_PAGE.="</TR>";
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD COLSPAN='4' ALIGN='CENTER'><FONT SIZE='1'><br>";
		$HTML_WEB_PAGE.="ANEXO No. 1<br>";
		$HTML_WEB_PAGE.="FORECAT - CONSOLIDADO<br><br>";
		$HTML_WEB_PAGE.="</FONT></TD>";
		$HTML_WEB_PAGE.="</TR>";
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD WIDTH='20%' ALIGN='LEFT'><FONT SIZE='1'>Fecha de radicación:</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='30%' ALIGN='LEFT'><FONT SIZE='1'>".$datos1['fechadradi']."</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='20%' ALIGN='LEFT'><FONT SIZE='1'>No. Radicado:</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='30%' ALIGN='LEFT'><FONT SIZE='1'>".$datos1['numeroradi']."</FONT></TD>";
		$HTML_WEB_PAGE.="</TR>";
		$HTML_WEB_PAGE.="</TABLE>";
		$HTML_WEB_PAGE.="<TABLE WIDTH='100%' BORDER=1>";
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD COLSPAN='4' ALIGN='CENTER'><FONT SIZE='1'>";
		$HTML_WEB_PAGE.="&nbsp;<br><b>I. DATOS DE LA INSTITUCIÓN PRESTADORA DE SERVICIOS DE SALUD</b>&nbsp;<br>&nbsp;<br>";
		$HTML_WEB_PAGE.="</FONT></TD>";
		$HTML_WEB_PAGE.="</TR>";
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD WIDTH='20%' ALIGN='LEFT'><FONT SIZE='1'>Razón Social:</FONT></TD>";
		$HTML_WEB_PAGE.="<TD COLSPAN='3' ALIGN='LEFT'><FONT SIZE='1'>".$datos1['razon_social']."</FONT></TD>";
		$HTML_WEB_PAGE.="</TR>";
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD WIDTH='20%' ALIGN='LEFT'><FONT SIZE='1'>Código IPS:</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='30%' ALIGN='LEFT'><FONT SIZE='1'>".$datos1['codigo_sgsss']."</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='20%' ALIGN='LEFT'><FONT SIZE='1'>".$datos1['tipo_id_tercero'].":</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='30%' ALIGN='LEFT'><FONT SIZE='1'>".$datos1['id']."</FONT></TD>";
		$HTML_WEB_PAGE.="</TR>";
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD WIDTH='20%' ALIGN='LEFT'><FONT SIZE='1'>Dirección:</FONT></TD>";
		$HTML_WEB_PAGE.="<TD COLSPAN='3' ALIGN='LEFT'><FONT SIZE='1'>".$datos1['direccion']."</FONT></TD>";
		$HTML_WEB_PAGE.="</TR>";
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD WIDTH='20%' ALIGN='LEFT'><FONT SIZE='1'>Departamento:</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='30%' ALIGN='LEFT'><FONT SIZE='1'>".$datos1['deparempre']." Cód.: ".$datos1['tipo_dpto_id']."</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='20%' ALIGN='LEFT'><FONT SIZE='1'>Teléfono:</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='30%' ALIGN='LEFT'><FONT SIZE='1'>".$datos1['telefonos']."</FONT></TD>";
		$HTML_WEB_PAGE.="</TR>";
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD WIDTH='20%' ALIGN='LEFT'><FONT SIZE='1'>Municipio:</FONT></TD>";
		$HTML_WEB_PAGE.="<TD COLSPAN='3' ALIGN='LEFT'><FONT SIZE='1'>".$datos1['municempre']." Cód.: ".$datos1['tipo_mpio_id']."</FONT></TD>";
		$HTML_WEB_PAGE.="</TR>";
		$HTML_WEB_PAGE.="</TABLE>";
		$HTML_WEB_PAGE.="<TABLE WIDTH='100%' BORDER=1>";
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD COLSPAN='4' ALIGN='CENTER'><FONT SIZE='1'>";
		$HTML_WEB_PAGE.="&nbsp;<br><b>II. PERIODO RECLAMADO</b>&nbsp;<br>&nbsp;<br>";
		$HTML_WEB_PAGE.="</FONT></TD>";
		$HTML_WEB_PAGE.="</TR>";
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD WIDTH='20%' ALIGN='LEFT'><FONT SIZE='1'>Periodo Reclamado:</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='30%' ALIGN='LEFT'><FONT SIZE='1'>".$datos1['periodorec']."</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='20%' ALIGN='LEFT'><FONT SIZE='1'>Fecha Inicial:</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='30%' ALIGN='LEFT'><FONT SIZE='1'>".$datos1['fechainici']."</FONT></TD>";
		$HTML_WEB_PAGE.="</TR>";
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD WIDTH='20%' ALIGN='LEFT'><FONT SIZE='1'></FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='30%' ALIGN='LEFT'><FONT SIZE='1'></FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='20%' ALIGN='LEFT'><FONT SIZE='1'>Fecha Final:</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='30%' ALIGN='LEFT'><FONT SIZE='1'>".$datos1['fechafinal']."</FONT></TD>";
		$HTML_WEB_PAGE.="</TR>";
		$HTML_WEB_PAGE.="</TABLE>";
		$HTML_WEB_PAGE.="<TABLE WIDTH='100%' BORDER=1>";
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD COLSPAN='5' ALIGN='CENTER'><FONT SIZE='1'>";
		$HTML_WEB_PAGE.="&nbsp;<br><b>III. CONSOLIDADO POR EVENTO</b>&nbsp;<br>&nbsp;<br>";
		$HTML_WEB_PAGE.="</FONT></TD>";
		$HTML_WEB_PAGE.="</TR>";
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD WIDTH='25%' ALIGN='CENTER'><FONT SIZE='1'>Tipo de Evento</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='25%' ALIGN='CENTER'><FONT SIZE='1'>No. de Reclamaciones</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='5%' ALIGN='CENTER'><FONT SIZE='1'></FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='30%' ALIGN='CENTER'><FONT SIZE='1'>Valor</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='15%' ALIGN='CENTER'><FONT SIZE='1'></FONT></TD>";
		$HTML_WEB_PAGE.="</TR>";
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD WIDTH='25%' ALIGN='CENTER'><FONT SIZE='1'></FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='25%' ALIGN='CENTER'><FONT SIZE='1'></FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='5%' ALIGN='CENTER'><FONT SIZE='1'>$</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='30%' ALIGN='RIGHT'><FONT SIZE='1'>0</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='15%' ALIGN='CENTER'><FONT SIZE='1'></FONT></TD>";
		$HTML_WEB_PAGE.="</TR>";
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD WIDTH='25%' ALIGN='CENTER'><FONT SIZE='1'>TOTAL</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='25%' ALIGN='CENTER'><FONT SIZE='1'>".$registros."</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='5%' ALIGN='CENTER'><FONT SIZE='1'>$</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='30%' ALIGN='RIGHT'><FONT SIZE='1'>".number_format(($valor), 0, ',', '.')."</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='15%' ALIGN='CENTER'><FONT SIZE='1'></FONT></TD>";
		$HTML_WEB_PAGE.="</TR>";
		$HTML_WEB_PAGE.="</TABLE>";
		$HTML_WEB_PAGE.="<TABLE WIDTH='100%' BORDER=1>";
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD WIDTH='100%' ALIGN='CENTER'><FONT SIZE='1'>";
		$HTML_WEB_PAGE.="&nbsp;<br><b>IV. DECLARACIÓN DE LA INSTITUCIÓN PRESTADORA DE SERVICIOS DE SALUD</b>&nbsp;<br>&nbsp;<br>";
		$HTML_WEB_PAGE.="</FONT></TD>";
		$HTML_WEB_PAGE.="</TR>";
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD WIDTH='100%' ALIGN='LEFT'><FONT SIZE='0.8'>";
		$HTML_WEB_PAGE.="Como Representante Legal y Revisor Fiscal o Contador de la institución prestadora de servicios de salud, declaramos bajo la gravedad de juramento:";
		$HTML_WEB_PAGE.="</FONT></TD>";
		$HTML_WEB_PAGE.="</TR>";
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD WIDTH='100%' ALIGN='JUSTIFY'><FONT SIZE='0.8'><ol>";
		$HTML_WEB_PAGE.="<li>Que la información contenida en este formulario es cierta y podrá ser verificada por la Dirección General de Financiamiento del Ministerio de la";
		$HTML_WEB_PAGE.=" Protección Social, por el Administrador Fiduciario del Fondo de Solidaridad y Garantia FOSYGA, por la Superintendencia Nacional de Salud o la";
		$HTML_WEB_PAGE.=" Contraloria General de la República, de no ser así, acepto todas las consecuencias legales que produzca esta situación.</li><br>";
		$HTML_WEB_PAGE.="<li>Que las reclamaciones incluidas en el medio magnético cumplan con los requisitos establecidos en los Decretos Ley 1032 de 1991, 663";
		$HTML_WEB_PAGE.=" de 1993 y 1281 de 2002 y Decretos 2878 de 1991 y 1283 de 1996 y demás normas que lo adicionen, sustituyan o modifiquen.</li><br>";
		$HTML_WEB_PAGE.="<li>Que la institución conserva las reclamaciones individuales con el completo de los requisitos establecidos con los Decretos Ley 1032 de 1991,";
		$HTML_WEB_PAGE.=" 663 de 1993 y 1281 de 2002 y decretos 2878 de 1991 y 1283 de 1996 y demás normas que lo adicionan, sustituyan o modifiquen.</li><br>";
		$HTML_WEB_PAGE.="<li>Que no se ha realizado fraccionamiento de servicios para incluirlos por este sistema de pago.</li><br>";
		$HTML_WEB_PAGE.="<li>Que los servicios de salud fueron prestados a víctimas de accidentes de tránsito ocasionados por vehículos no identificados o no asegurados o";
		$HTML_WEB_PAGE.=" excedentes, de acuerdo con lo establecido en las normas vigentes.</li></ol></FONT>";
		$HTML_WEB_PAGE.="</TD>";
		$HTML_WEB_PAGE.="</TR>";
		$HTML_WEB_PAGE.="</TABLE>";
		$HTML_WEB_PAGE.="<TABLE WIDTH='100%' BORDER=0>";
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD WIDTH='50%' ALIGN='CENTER'><FONT SIZE='1'>";
		$HTML_WEB_PAGE.="&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;______________________________________________________";
		$HTML_WEB_PAGE.="<br>Firma Representante Legal";
		$HTML_WEB_PAGE.="</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='50%' ALIGN='CENTER'><FONT SIZE='1'>";
		$HTML_WEB_PAGE.="&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;______________________________________________________";
		$HTML_WEB_PAGE.="<br>Firma del Revisor Fiscal o Contador";
		$HTML_WEB_PAGE.="</FONT></TD>";
		$HTML_WEB_PAGE.="</TR>";
		$HTML_WEB_PAGE.="</TABLE>";
		$HTML_WEB_PAGE.="&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>";

/*$numeropagina=intval(($registros/30));
$numeroresidu=($registros%30);
		if($numeropagina>0)
		{
		//llame funciones
		//un ciclo
			for($k=0;$k)
			{
			}
		}
		if($numeroresidu>0)
		{
		//llame funciones
		//no ciclo
		}*/
		$HTML_WEB_PAGE.="<TABLE WIDTH='100%' BORDER=0>";
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD WIDTH='100%' ALIGN='CENTER'><FONT SIZE='1'><br>";
		$HTML_WEB_PAGE.="ANEXO No. 2<br>";
		$HTML_WEB_PAGE.="RECLAMACIÓN CON CARGO A LA SUBCUENTA ECAT DEL FOSYGA COMO RESULTADO DE SERVICIOS DE SALUD PRESTADOS A<br>";
		$HTML_WEB_PAGE.="VÍCTIMAS DE ACCIDENTES DE TRÁNSITO Y EVENTOS CATASTRÓFICOS (Catástrofes Naturales y Atentados Terroristas)<br><br>";
		$HTML_WEB_PAGE.="</FONT></TD>";
		$HTML_WEB_PAGE.="</TR>";
		$HTML_WEB_PAGE.="</TABLE>";
		$HTML_WEB_PAGE.="<TABLE WIDTH='100%' BORDER=1>";
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD COLSPAN='2' ALIGN='CENTER'><FONT SIZE='0.8'>Nombre de la Víctima</FONT></TD>";
		$HTML_WEB_PAGE.="<TD COLSPAN='2' ALIGN='CENTER'><FONT SIZE='0.8'>Identificación</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='6%'  ALIGN='CENTER'><FONT SIZE='0.8'>Tipo</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='6%'  ALIGN='CENTER'><FONT SIZE='0.8'>F. Ingreso</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='6%'  ALIGN='CENTER'><FONT SIZE='0.8'>F. Egreso</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='12%' ALIGN='CENTER'><FONT SIZE='0.8'>V. Servicios</FONT></TD>";
		$HTML_WEB_PAGE.="</TR>";
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD WIDTH='28%' ALIGN='CENTER'><FONT SIZE='0.8'>Apellidos</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='28%' ALIGN='CENTER'><FONT SIZE='0.8'>Nombres</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='4%'  ALIGN='CENTER'><FONT SIZE='0.8'>Tipo</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='10%' ALIGN='CENTER'><FONT SIZE='0.8'>Número</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='6%'  ALIGN='CENTER'><FONT SIZE='0.8'>Evento</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='6%'  ALIGN='CENTER'><FONT SIZE='0.8'>dd/mm/año</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='6%'  ALIGN='CENTER'><FONT SIZE='0.8'>dd/mm/año</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='12%' ALIGN='CENTER'><FONT SIZE='0.8'>Prestados</FONT></TD>";
		$HTML_WEB_PAGE.="</TR>";
		for($i=0;$i<$registros;$i++)//$registros
		{
			$HTML_WEB_PAGE.="<TR>";
			$HTML_WEB_PAGE.="<TD WIDTH='27%' ALIGN='LEFT'><FONT SIZE='0.8'>";
			$HTML_WEB_PAGE.="".$datos2[$i]['apellidos']."";
			$HTML_WEB_PAGE.="</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='27%' ALIGN='LEFT'><FONT SIZE='0.8'>";
			$HTML_WEB_PAGE.="".$datos2[$i]['nombres']."";
			$HTML_WEB_PAGE.="</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='4%'  ALIGN='CENTER'><FONT SIZE='0.8'>";
			$HTML_WEB_PAGE.="".$datos2[$i]['tipo_id_paciente']."";
			$HTML_WEB_PAGE.="</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='10%' ALIGN='RIGHT'><FONT SIZE='0.8'>";
			$HTML_WEB_PAGE.="".$datos2[$i]['paciente_id']."";
			$HTML_WEB_PAGE.="</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='6%'  ALIGN='CENTER'><FONT SIZE='0.8'>";
			$HTML_WEB_PAGE.="01";
			$HTML_WEB_PAGE.="</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='6%'  ALIGN='CENTER'><FONT SIZE='0.8'>";
			$HTML_WEB_PAGE.="".$datos2[$i]['fecha_ingreso']."";
			$HTML_WEB_PAGE.="</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='6%'  ALIGN='CENTER'><FONT SIZE='0.8'>";
			$HTML_WEB_PAGE.="".$datos2[$i]['fecha_registro']."";
			$HTML_WEB_PAGE.="</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='12%' ALIGN='RIGHT'><FONT SIZE='0.8'>";
			$HTML_WEB_PAGE.="".number_format(($datos2[$i]['total_factura']), 0, ',', '.')."";//".$datos2[$i]['total_factura']."
			$HTML_WEB_PAGE.="</FONT></TD>";
			$HTML_WEB_PAGE.="</TR>";
		}
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD COLSPAN='7' ALIGN='RIGHT'><FONT SIZE='0.8'>SUBTOTAL</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='12%' ALIGN='RIGHT'><FONT SIZE='0.8'>".number_format(($valor), 0, ',', '.')."</FONT></TD>";
		$HTML_WEB_PAGE.="</TR>";
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD COLSPAN='7' ALIGN='RIGHT'><FONT SIZE='0.8'>TOTAL</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='12%' ALIGN='RIGHT'><FONT SIZE='0.8'>".number_format(($valor), 0, ',', '.')."</FONT></TD>";
		$HTML_WEB_PAGE.="</TR>";
		$HTML_WEB_PAGE.="</TABLE>";
		$HTML_WEB_PAGE.="<TABLE WIDTH='100%' BORDER=1>";
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD COLSPAN='5' ALIGN='LEFT'><FONT SIZE='0.8'>TIPO EVENTOS:</FONT></TD>";
		$HTML_WEB_PAGE.="</TR>";
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD WIDTH='20%' ALIGN='LEFT'><FONT SIZE='0.8'>01 - Accidente de Tránsito<br>06 - Inundación<br>11 - Combate</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='20%' ALIGN='LEFT'><FONT SIZE='0.8'>02 - Sismo<br>07 - Avalancha<br>12 - Toma Guerrillera</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='20%' ALIGN='LEFT'><FONT SIZE='0.8'>03 - Maremoto<br>08 - Incendio Natural<br>13 - Masacre</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='20%' ALIGN='LEFT'><FONT SIZE='0.8'>04 - Erupción Volcanica<br>09 - Explosión Terrorista</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='20%' ALIGN='LEFT'><FONT SIZE='0.8'>05 - Deslizamiento de Tierra<br>10 - Incendio Terrorista</FONT></TD>";
		$HTML_WEB_PAGE.="</TR>";
		$HTML_WEB_PAGE.="</TABLE>";
		$HTML_WEB_PAGE.="<TABLE WIDTH='100%' BORDER=0>";
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD COLSPAN='2' ALIGN='LEFT'><FONT SIZE='0.8'>";
		$HTML_WEB_PAGE.="El agrupado de catástrofes naturales contempla los códigos: 02 03 04 05 06 07 08<br>";
		$HTML_WEB_PAGE.="El agrupado de Eventos Terroristas contempla los códigos:   09 10 11 12 13";
		$HTML_WEB_PAGE.="</FONT></TD>";
		$HTML_WEB_PAGE.="</TR>";
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD WIDTH='50%' ALIGN='CENTER'><FONT SIZE='1'>";
		$HTML_WEB_PAGE.="&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;______________________________________________________";
		$HTML_WEB_PAGE.="<br>Firma Representante Legal";
		$HTML_WEB_PAGE.="</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='50%' ALIGN='CENTER'><FONT SIZE='1'>";
		$HTML_WEB_PAGE.="&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;______________________________________________________";
		$HTML_WEB_PAGE.="<br>Firma del Revisor Fiscal o Contador";
		$HTML_WEB_PAGE.="</FONT></TD>";
		$HTML_WEB_PAGE.="</TR>";
		$HTML_WEB_PAGE.="</TABLE>";

		$HTML_WEB_PAGE.="</BODY></HTML>";
		return $HTML_WEB_PAGE;
	}

	function BuscarDatosReporte1($datos)
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT A.razon_social,
				A.codigo_sgsss,
				A.tipo_id_tercero,
				A.id,
				A.direccion,
				A.telefonos,
				A.tipo_dpto_id,
				A.tipo_mpio_id,
				B.departamento AS deparempre,
				C.municipio AS municempre
				FROM empresas AS A,
				tipo_dptos AS B,
				tipo_mpios AS C
				WHERE A.empresa_id='".$datos['empresa']."'
				AND A.tipo_pais_id=B.tipo_pais_id
				AND A.tipo_dpto_id=B.tipo_dpto_id
				AND A.tipo_pais_id=C.tipo_pais_id
				AND A.tipo_dpto_id=C.tipo_dpto_id
				AND A.tipo_mpio_id=C.tipo_mpio_id;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		$var['fechadradi']=$datos['fechadradi'];
		$var['numeroradi']=$datos['numeroradi'];
		$var['periodorec']=$datos['periodorec'];
		$var['fechainici']=$datos['fechainici'];
		$var['fechafinal']=$datos['fechafinal'];
		return $var;
	}

	function BuscarDatosReporte2($datos)
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT A.ingreso,
				B.tipo_id_paciente,
				B.paciente_id,
				B.fecha_ingreso,
				C.numerodecuenta,
				C.plan_id,
				C.empresa_id,
				C.fecha_cierre,
				D.sw_tipo_plan,
				E.prefijo,
				E.factura_fiscal,
				F.total_factura,
				F.fecha_registro,
				G.primer_apellido||' '||G.segundo_apellido AS apellidos,
				G.primer_nombre||' '||G.segundo_nombre AS nombres
				FROM ingresos_soat AS A,
				ingresos AS B,
				cuentas AS C,
				planes AS D,
				fac_facturas_cuentas AS E,
				fac_facturas AS F,
				pacientes AS G
				WHERE A.ingreso=B.ingreso
				AND B.ingreso=C.ingreso
				AND C.empresa_id='".$datos['empresa']."'
				AND C.estado='0'
				AND C.plan_id=D.plan_id
				AND C.numerodecuenta=E.numerodecuenta
				AND E.prefijo=F.prefijo
				AND E.factura_fiscal=F.factura_fiscal
				AND F.fecha_registro LIKE '".$datos['periodorec']."%'
				AND F.total_factura<=".$datos['salariomon']."
				AND B.tipo_id_paciente=G.tipo_id_paciente
				AND B.paciente_id=G.paciente_id
				ORDER BY A.ingreso;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		$j=0;
		for($i=0;$i<sizeof($var);$i++)
		{
			if($var[$i]['ingreso']==$datos['datovector'][$j]['ingreso'])
			{
				$datosdefinit[$j]=$var[$i];
				$datosdefinit[$j]['fecha_ingreso']=$datos['datovector'][$j]['fecha_ingreso'];
				$datosdefinit[$j]['fecha_cierre']=$datos['datovector'][$j]['fecha_cierre'];
				$datosdefinit[$j]['fecha_registro']=$datos['datovector'][$j]['fecha_registro'];
				$j++;
			}
		}
		return $datosdefinit;
	}

}

?>
