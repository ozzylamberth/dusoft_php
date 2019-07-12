<?php

/**
 * $Id: soat_reclamo_dinero.inc.php,v 1.1 2009/07/30 12:56:57 johanna Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Archivo que imprime el formulario único de reclamación de entidades
 */

	function GenerarSoatReclamoDinero($datos)
	{
    //echo "<pre>".print_r($datos,true)."</pre>";
		UNSET($_SESSION['REPORTES']['VARIABLE']);
		IncludeLib('funciones_admision');
		$Dir="cache/reclamacion_entidades.pdf";
		require("classes/fpdf/html_class.php");
		define('FPDF_FONTPATH','font/');
		
		$pdf=new PDF('P','mm','letter2');//legal
		$pdf->AddPage();
		$pdf->Image('images/escudo-colombia.jpg',10,10,20);
		$pdf->SetFont('Arial','I',6);
		$html="<TABLE BORDER='0' WIDTH='1520'>";
    //$pdf->Ln(8);
		$html.="  <TR>";
		$html.="    <TD ALIGN='CENTER' WIDTH='760'><br>";
		$html.="      <FONT SIZE='26'><b>REPUBLICA DE COLOMBIA      RESOLUCIÓN 01915 28 MAY 2008</b></FONT><br>";
		$html.="      <FONT SIZE='26'><b>MINISTERIO DE LA PROTECCIÓN SOCIAL</b></FONT><br>";
		$html.="      <FONT SIZE='26'><b>FORMULARIO ÚNICO DE RECLAMACIÓN DE LOS PRESTADORES DE SERVICIOS DE SALUD<br> POR SERVICIOS PRESTADOS A VICTIMAS DE EVENTOS CATASTRÓFICOS Y ACCIDENTES DE TRÁNSITO</b><BR>PERSONAS JURIDICAS - FURIPS.</FONT><BR>";
		$html.="    </TD>";
		$html.="  </TR>";
		$html.="  <TR>";
		$html.="    <TD WIDTH='760' HEIGHT=25 ALIGN='LEFT'>";
		$html.="      <TABLE BORDER='0'>";
		$html.="        <TR>";
		$html.="          <TD WIDTH='300'ALIGN='LEFT'>";
		$html.="            <b>Fecha Radicación</b>".date('d/m/Y');
		$html.="          </TD>";
		$html.="          <TD WIDTH='160' ALIGN='LEFT'>";
		$html.="            <b>RG</b>";
		$html.="          </TD>";
		$html.="          <TD WIDTH='200' ALIGN='LEFT'>";
		$html.="            <b>No Radicado</b>";
		$html.="          </TD>";
		$html.="        </TR>";
		$html.="      </TABLE>";
		$html.="    </TD>";
		$html.="  </TR>";
		$html.="  <TR>";
		$html.="    <TD WIDTH='760' ALIGN='CENTER'>";
		$html.="      <TABLE BORDER='0'>";
		$html.="        <TR>";
		$html.="          <TD WIDTH='400' ALIGN='LEFT'>";
		$html.="            <b>No Radicado anterior(Respuesta<br>a glosa, marcar X en RG</b>";
		$html.="          </TD>";
		$html.="          <TD WIDTH='200' ALIGN='LEFT'>";
		$html.="            <b>No Factura/Cuenta cobro</b>";
		$html.="          </TD>";
		$html.="        </TR>";
		$html.="      </TABLE>";
		$html.="    </TD>";
		$html.="  </TR>";
		$html.="  <TR>";
		$html.="    <TD WIDTH='760' HEIGHT=25 ALIGN='CENTER' bgcolor='#CCCCCC'>";
		$html.="      <b>I. DATOS DE LA INSTITUCIÓN PRESTADORA DE SERVICIOS DE SALUD</b>";
		$html.="    </TD>";
		$html.="  </TR>";
		$html.="  <TR>";
		$html.="    <TD WIDTH='760' HEIGHT=25 ALIGN='LEFT'>Razón Social:  ".$datos['empresa']."</TD>";
		$html.="  </TR>";
		$html.="  <TR>";
		$html.="    <TD WIDTH='510' HEIGHT=25>Código Habilitación: ".$datos['codigo_sgsss']."</TD>";
		$html.="    <TD WIDTH='200' HEIGHT=25 ALIGN='LEFT'>Nit:  ".$datos['id']."</TD>";
		$html.="  </TR>";
		$html.="  <TR>";
		$html.="    <TD WIDTH='760' HEIGHT=25 ALIGN='CENTER' bgcolor='#CCCCCC'>";
		$html.="      <b>II. DATOS DE LA VICTIMA DEL EVENTO CATASTRÓFICO O EL ACCIDENTE DE TRANSITO</b>";
		$html.="    </TD>";
		$html.="  </TR>";
		$html.="  <TR>";
		$html.="    <TD WIDTH='760' ALIGN='CENTER'>";
		$html.="      <TABLE BORDER='0'>";
		$html.="        <TR>";
		$html.="          <TD WIDTH='210' HEIGHT=25 ALIGN='LEFT'>Primer Apellido: ".$datos['primer_apellido']."</TD>";
		$html.="          <TD WIDTH='210' HEIGHT=25 ALIGN='LEFT'>Segundo Apellido: ".$datos['segundo_apellido']."</TD>";
		$html.="        </TR>";
		$html.="        <TR>";
		$html.="          <TD WIDTH='210' HEIGHT=25 ALIGN='LEFT'>Primer Nombre: ".$datos['primer_nombre']."</TD>";
		$html.="          <TD WIDTH='210' HEIGHT=25 ALIGN='LEFT'>Segundo Nombre: ".$datos['segundo_nombre']."</TD>";
		$html.="        </TR>";
		$html.="      </TABLE>";
		$html.="    </TD>";
		$html.="  </TR>";
		$html.="  <TR>";
		$html.="    <TD WIDTH='760' ALIGN='CENTER'>";
		$html.="      <TABLE BORDER='0'>";
		$html.="        <TR>";
		$html.="          <TD WIDTH='250' HEIGHT=25 ALIGN='LEFT'>Tipo de Documento ".$datos['tipo_paciente_id']."</TD>";
		$html.="          <TD WIDTH='250' HEIGHT=25 ALIGN='LEFT'>No Documento ".$datos['paciente_id']."</TD>";
		$html.="        </TR>";
		$html.="      </TABLE>";
		$html.="    </TD>";
		$html.="  </TR>";
		
		$date = explode("-",$datos['fecha_nacimiento']);
		$html.="  <TR>";
		$html.="    <TD WIDTH='200' HEIGHT=25>FECHA DE NACIMIENTO:</TD>";
		$html.="    <TD WIDTH='125' HEIGHT=25>".$date[2].'/'.$date[1].'/'.$date[0]."</TD>";
		$html.="    <TD WIDTH='35' HEIGHT=25>SEXO:</TD>";
		$html.="    <TD WIDTH='155' HEIGHT=25>".$datos['sexo_id']."</TD>";
		$html.="  </TR>";
		$html.="  <TR>";
		$html.="    <TD WIDTH='200' HEIGHT=25>DIRECCIÓN RESIDENCIA:</TD>";
		$html.="    <TD WIDTH='125' HEIGHT=25>".$datos['residencia_direccion']."</TD>";
		$html.="  </TR>";
		$html.="  <TR>";
		$html.="    <TD WIDTH='200' HEIGHT=25>Departamento:</TD>";
		$html.="    <TD WIDTH='125' HEIGHT=25>".$datos['departamento_paciente']."</TD>";
		$html.="    <TD WIDTH='125' HEIGHT=25>Cod. ".$datos['tipo_dpto_id']."                                 Telefono : ".$datos['residencia_telefono']."</TD>";
		$html.="  </TR>";
		$html.="  <TR>";
		$html.="    <TD WIDTH='200' HEIGHT=25>Municipio:</TD>";
		$html.="    <TD WIDTH='125' HEIGHT=25>".$datos['municipio_paciente']."</TD>";
		$html.="    <TD WIDTH='125' HEIGHT=25>Cod. ".$datos['tipo_mpio_id']."</TD>";
		$html.="  </TR>";
		$html.="  <TR>";
		$html.="    <TD WIDTH='160' HEIGHT=25 ALIGN='LEFT'>  CONDICIÓN EL ACCIDENTADO:</TD>";
		if($datos['descondicion']==NULL)
		{
			$datos['descondicion']='****';
		}
		$html.="    <TD WIDTH='150' HEIGHT=25>".$datos['descondicion']."</TD>";
		$html.="  </TR>";
		$html.="  <TR>";
		$html.="    <TD WIDTH='760' HEIGHT=25 ALIGN='CENTER' bgcolor='#CCCCCC'><b>III. DATOS DEL SITIO DONDE OCURRIÓ EL EVENTO CATASTRÓFICO O EL ACCIDENTE DE TRANSITO.</b></TD>";
		$html.="  </TR>";
		$html.="  <TR rowspan=\"3\">";
		$html.="    <TD WIDTH='200' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\">Naturaleza del evento:</TD>";
		$html.="  </TR>";
		$html.="  <TR>";
		$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\"><b>Naturales:</b></TD>";
		if($datos['soat_naturaleza_evento_id']=='01')
		{
			$html.="    <TD WIDTH='100' HEIGHT=25>Sismo</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Accidente de transito [X]</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Maremoto</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Erupciones volcanicas</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Huracán</TD>";
			$html.="  </TR>";
			$html.="  <TR>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\">&nbsp;</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Inundaciones</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Avalancha</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Deslizamieto de tierra</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Incendio Natural</TD>";
			$html.="  </TR>";
			$html.="  <TR>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\"><b>Terroristas:</b></TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Explosión</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Masacre</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Mina antipersonal</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Combate</TD>";
			$html.="  </TR>";
			$html.="  <TR>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\">&nbsp;</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Incendios</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Ataques a municipios</TD>";
			$html.="  </TR>";
		}
		elseif($datos['soat_naturaleza_evento_id']=='02')
		{
			$html.="    <TD WIDTH='100' HEIGHT=25>Sismo [X]</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Accidente de transito</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Maremoto</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Erupciones volcanicas</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Huracán</TD>";
			$html.="  </TR>";
			$html.="  <TR>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\">&nbsp;</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Inundaciones</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Avalancha</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Deslizamieto de tierra</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Incendio Natural</TD>";
			$html.="  </TR>";
			$html.="  <TR>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\"><b>Terroristas:</b></TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Explosión</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Masacre</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Mina antipersonal</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Combate</TD>";
			$html.="  </TR>";
			$html.="  <TR>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\">&nbsp;</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Incendios</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Ataques a municipios</TD>";
			$html.="  </TR>";
		}
		elseif($datos['soat_naturaleza_evento_id']=='03')
		{
			$html.="    <TD WIDTH='100' HEIGHT=25>Sismo</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Accidente de transito</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Maremoto [X]</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Erupciones volcanicas</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Huracán</TD>";
			$html.="  </TR>";
			$html.="  <TR>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\">&nbsp;</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Inundaciones</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Avalancha</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Deslizamieto de tierra</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Incendio Natural</TD>";
			$html.="  </TR>";
			$html.="  <TR>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\"><b>Terroristas:</b></TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Explosión</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Masacre</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Mina antipersonal</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Combate</TD>";
			$html.="  </TR>";
			$html.="  <TR>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\">&nbsp;</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Incendios</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Ataques a municipios</TD>";
			$html.="  </TR>";
		}
		elseif($datos['soat_naturaleza_evento_id']=='04')
		{
			$html.="    <TD WIDTH='100' HEIGHT=25>Sismo</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Accidente de transito</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Maremoto</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Erupciones volcanicas [X]</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Huracán</TD>";
			$html.="  </TR>";
			$html.="  <TR>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\">&nbsp;</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Inundaciones</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Avalancha</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Deslizamieto de tierra</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Incendio Natural</TD>";
			$html.="  </TR>";
			$html.="  <TR>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\"><b>Terroristas:</b></TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Explosión</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Masacre</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Mina antipersonal</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Combate</TD>";
			$html.="  </TR>";
			$html.="  <TR>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\">&nbsp;</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Incendios</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Ataques a municipios</TD>";
			$html.="  </TR>";
		}
		elseif($datos['soat_naturaleza_evento_id']=='16')
		{
			$html.="    <TD WIDTH='100' HEIGHT=25>Sismo</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Accidente de transito</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Maremoto</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Erupciones volcanicas</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Huracán [X]</TD>";
			$html.="  </TR>";
			$html.="  <TR>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\">&nbsp;</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Inundaciones</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Avalancha</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Deslizamieto de tierra</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Incendio Natural</TD>";
			$html.="  </TR>";
			$html.="  <TR>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\"><b>Terroristas:</b></TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Explosión</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Masacre</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Mina antipersonal</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Combate</TD>";
			$html.="  </TR>";
			$html.="  <TR>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\">&nbsp;</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Incendios</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Ataques a municipios</TD>";
			$html.="  </TR>";
		}
		elseif($datos['soat_naturaleza_evento_id']=='06')
		{
			$html.="    <TD WIDTH='100' HEIGHT=25>Sismo</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Accidente de transito</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Maremoto</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Erupciones volcanicas</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Huracán</TD>";
			$html.="  </TR>";
			$html.="  <TR>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\">&nbsp;</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Inundaciones [X]</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Avalancha</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Deslizamieto de tierra</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Incendio Natural</TD>";
			$html.="  </TR>";
			$html.="  <TR>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\"><b>Terroristas:</b></TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Explosión</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Masacre</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Mina antipersonal</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Combate</TD>";
			$html.="  </TR>";
			$html.="  <TR>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\">&nbsp;</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Incendios</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Ataques a municipios</TD>";
			$html.="  </TR>";
		}
		elseif($datos['soat_naturaleza_evento_id']=='05')
		{
			$html.="    <TD WIDTH='100' HEIGHT=25>Sismo</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Accidente de transito</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Maremoto</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Erupciones volcanicas</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Huracán</TD>";
			$html.="  </TR>";
			$html.="  <TR>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\">&nbsp;</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Inundaciones</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Avalancha</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Deslizamieto de tierra [X]</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Incendio Natural</TD>";
			$html.="  </TR>";
			$html.="  <TR>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\"><b>Terroristas:</b></TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Explosión</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Masacre</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Mina antipersonal</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Combate</TD>";
			$html.="  </TR>";
			$html.="  <TR>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\">&nbsp;</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Incendios</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Ataques a municipios</TD>";
			$html.="  </TR>";
		}
		elseif($datos['soat_naturaleza_evento_id']=='08')
		{
			$html.="    <TD WIDTH='100' HEIGHT=25>Sismo</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Accidente de transito</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Maremoto</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Erupciones volcanicas</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Huracán</TD>";
			$html.="  </TR>";
			$html.="  <TR>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\">&nbsp;</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Inundaciones</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Avalancha</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Deslizamieto de tierra</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Incendio Natural [X]</TD>";
			$html.="  </TR>";
			$html.="  <TR>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\"><b>Terroristas:</b></TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Explosión</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Masacre</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Mina antipersonal</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25>Combate</TD>";
			$html.="  </TR>";
			$html.="  <TR>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\">&nbsp;</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Incendios</TD>";
			$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Ataques a municipios</TD>";
			$html.="  </TR>";
		}
		elseif($datos['soat_naturaleza_evento_id']=='09')
		{
			$html.="<TD WIDTH='100' HEIGHT=25>Sismo</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Accidente de transito</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Maremoto</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Erupciones volcanicas</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Huracán</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\">&nbsp;</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Inundaciones</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Avalancha</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Deslizamieto de tierra</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Incendio Natural</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\"><b>Terroristas:</b></TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Explosión [X]</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Masacre</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Mina antipersonal</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Combate</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\">&nbsp;</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Incendios</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Ataques a municipios</TD>";
			$html.="</TR>";
		}
		elseif($datos['soat_naturaleza_evento_id']=='13')
		{
			$html.="<TD WIDTH='100' HEIGHT=25>Sismo</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Accidente de transito</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Maremoto</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Erupciones volcanicas</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Huracán</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\">&nbsp;</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Inundaciones</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Avalancha</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Deslizamieto de tierra</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Incendio Natural</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\"><b>Terroristas:</b></TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Explosión</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Masacre [X]</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Mina antipersonal</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Combate</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\">&nbsp;</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Incendios</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Ataques a municipios</TD>";
			$html.="</TR>";
		}
		elseif($datos['soat_naturaleza_evento_id']=='15')
		{
			$html.="<TD WIDTH='100' HEIGHT=25>Sismo</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Accidente de transito</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Maremoto</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Erupciones volcanicas</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Huracán</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\">&nbsp;</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Inundaciones</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Avalancha</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Deslizamieto de tierra</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Incendio Natural</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\"><b>Terroristas:</b></TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Explosión</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Masacre</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Mina antipersonal [X]</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Combate</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\">&nbsp;</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Incendios</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Ataques a municipios</TD>";
			$html.="</TR>";
		}
		elseif($datos['soat_naturaleza_evento_id']=='11')
		{
			$html.="<TD WIDTH='100' HEIGHT=25>Sismo</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Accidente de transito</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Maremoto</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Erupciones volcanicas</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Huracán</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\">&nbsp;</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Inundaciones</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Avalancha</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Deslizamieto de tierra</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Incendio Natural</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\"><b>Terroristas:</b></TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Explosión</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Masacre</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Mina antipersonal </TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Combate [X]</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\">&nbsp;</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Incendios</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Ataques a municipios</TD>";
			$html.="</TR>";
		}
		elseif($datos['soat_naturaleza_evento_id']=='10')
		{
			$html.="<TD WIDTH='100' HEIGHT=25>Sismo</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Accidente de transito</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Maremoto</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Erupciones volcanicas</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Huracán</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\">&nbsp;</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Inundaciones</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Avalancha</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Deslizamieto de tierra</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Incendio Natural</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\"><b>Terroristas:</b></TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Explosión</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Masacre</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Mina antipersonal</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Combate</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\">&nbsp;</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Incendios [X]</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Ataques a municipios</TD>";
			$html.="</TR>";
		}
		elseif($datos['soat_naturaleza_evento_id']=='12')
		{
			$html.="<TD WIDTH='100' HEIGHT=25>Sismo</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Accidente de transito</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Maremoto</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Erupciones volcanicas</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Huracán</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\">&nbsp;</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Inundaciones</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Avalancha</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Deslizamieto de tierra</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Incendio Natural</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\"><b>Terroristas:</b></TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Explosión</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Masacre</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Mina antipersonal</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Combate</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\">&nbsp;</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Incendios</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Ataques a municipios [X]</TD>";
			$html.="</TR>";
		}
		elseif($datos['soat_naturaleza_evento_id']=='17')
		{
			$html.="<TD WIDTH='100' HEIGHT=25>Sismo</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Accidente de transito</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Maremoto</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Erupciones volcanicas</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Huracán</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\">&nbsp;</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Inundaciones</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Avalancha</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Deslizamieto de tierra</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Incendio Natural</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\"><b>Terroristas:</b></TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Explosión</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Masacre</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Mina antipersonal</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Combate</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\">&nbsp;</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Incendios</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Ataques a municipios</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT' rowspan=\"3\">&nbsp;</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Otros [X]</TD>";
			$html.="<TD WIDTH='100' HEIGHT=200 ALIGN='LEFT'>                     </TD>";
			$html.="</TR>";
		}
/*'01', 'Accidente de transito'
'02', 'Sismo';
'03', 'Maremoto';
'04', 'ErupciÃ³n volcÃ¡nica';
'05', 'Deslizamiento de tierra';
'06', 'InundaciÃ³n';
'07', 'Avalancha';
'08', 'Incendio natural';
'09', 'ExplosiÃ³n terrorista';
'10', 'Incendio terrorista';
'11', 'Combate';
'12', 'Ataques a Municipios';
'13', 'Masacre';
'14', 'Desplazados';
'15', 'Mina Antipersonal';
'16', 'Huracan'; 
'17', 'Otro';*/

		$html.="  <TR>";
		$html.="    <TD WIDTH='150' HEIGHT=25 ALIGN='LEFT'><B>  Dirección accidente:</B></TD>";
		$html.="    <TD WIDTH='245' HEIGHT=25>".$datos['sitio_accidente']."</TD>";
		$html.="  </TR>";

		$fec = explode(" ",$datos['fecha_evento']);

		$ft = explode("-",$fec[0]);
		$html.="  <TR>";//fecha_accidente
		$html.="    <TD WIDTH='150' HEIGHT=25 ALIGN='LEFT'>  Fecha Evento/Accidente:  ".$ft[2].'/'.$ft[1].'/'.$ft[0]."</TD>";
		$html.="    <TD WIDTH='245' HEIGHT=25> Hora: ".$fec[1]."</TD>";
		$html.="  </TR>";
		$html.="  <TR>";
		$html.="    <TD WIDTH='90' HEIGHT=25 ALIGN='LEFT'>DEPARTAMENTO:</TD>";
		$html.="    <TD WIDTH='250' HEIGHT=25>".$datos['departamento']."</TD>   Cod.  ".$datos['dpto_id_evento']."";
		$html.="  </TR>";
		$html.="  <TR>";
		$html.="    <TD WIDTH='65' HEIGHT=25 ALIGN='LEFT'>  MUNICIPIO:</TD>";
		$html.="    <TD WIDTH='245' HEIGHT=25>".$datos['muniaccidente']."  Cod.  ".$datos['mpio_id_evento']."</TD>";
		$html.="    <TD WIDTH='35' HEIGHT=25 ALIGN='LEFT'>ZONA:</TD>";
		$html.="    <TD WIDTH='65' HEIGHT=25>".$datos['deszona']."</TD>";
		$html.="  </TR>";
		$html.="  <TR>";
		$html.="    <TD WIDTH='450' HEIGHT=25 ALIGN='LEFT'><B>Descripción breve del Evento catastrófico o Accidente de Transito Enuncie las principales caracteristicas del evento / accidente:</B></TD>";
		$html.="  </TR>";
		if($datos['informe_accidente']==NULL)
		{
			$datos['informe_accidente']='****';
		}
		$html.="  <TR>";
		$html.="    <TD WIDTH='560' HEIGHT=25>      ".substr($datos['informe_accidente'],0,140)."</TD>";
		$html.="  </TR>";
		if(substr($datos['informe_accidente'],140,280))
		{
		$html.="  <TR>";
		$html.="    <TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['informe_accidente'],140,280)."</TD>";
		$html.="  </TR>";
		}
		if(substr($datos['informe_accidente'],280,420))
		{
		$html.="  <TR>";
		$html.="    <TD WIDTH='760' HEIGHT=25>".'  '."".substr($datos['informe_accidente'],280,420)."</TD>";
		$html.="  </TR>";
		}
		
		/**///IV. DATOS DEL VEHICULO DEL ACCIDENTE DE TRANSITO
		$html.="  <TR>";
		$html.="    <TD WIDTH='760' HEIGHT=25 ALIGN='CENTER' bgcolor='#CCCCCC'><br>";
		$html.="      <b>IV. DATOS DEL VEHICULO DEL ACCIDENTE DE TRANSITO</b>";
		$html.="    </TD>";
		$html.="  </TR>";
		/**/
//print_r($datos);
		$html.="  <TR>";
		$html.="    <TD WIDTH='200' HEIGHT=25 ALIGN='LEFT'><b>ESTADO DE ASEGURAMIENTO:</b></TD>";
		if($datos['asegura'] == 'SI')
		{
			$html.="<TD WIDTH='100' HEIGHT=25>Asegurado [X]</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>No Asegurado</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>V. Fantasma</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Poliza falsa</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Poliza Vencida</TD>";
		}
		elseif($datos['asegura'] == 'NO')
		{
			$html.="<TD WIDTH='100' HEIGHT=25>Asegurado</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>No Asegurado [X]</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>V. Fantasma</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Poliza falsa</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Poliza Vencida</TD>";
		}
		elseif($datos['asegura'] == 'POLIZA FALSA')
		{
			$html.="<TD WIDTH='100' HEIGHT=25>Asegurado</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>No Asegurado</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>V. Fantasma</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Poliza falsa [X]</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Poliza Vencida</TD>";
		}
		elseif($datos['asegura'] == 'POLIZA VENCIDA')
		{
			$html.="<TD WIDTH='100' HEIGHT=25>Asegurado</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>No Asegurado [X]</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>V. Fantasma</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Poliza falsa</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Poliza Vencida [X]</TD>";
		}
		
		$html.="  </TR>";
		$html.="  <TR>";
		$html.="    <TD WIDTH='50' HEIGHT=25 ALIGN='LEFT'>  <b>MARCA:</b></TD>";
		if($datos['marca']==NULL)
		{
			$datos['marca']='****';
		}
		$html.="    <TD WIDTH='260' HEIGHT=25>".$datos['marca_vehiculo']."</TD>";
 		$html.="    <TD WIDTH='40' HEIGHT=25 ALIGN='LEFT'><b>PLACA:</b></TD>";
		if($datos['placa_vehiculo']==NULL)
		{
			$datos['placa_vehiculo']='****';
		}
		$html.="    <TD WIDTH='220' HEIGHT=25>".$datos['placa_vehiculo']."</TD>";
		$html.="  </TR>";
		$html.="  <TR>";
		$html.="    <TD WIDTH='110' HEIGHT=25 ALIGN='LEFT'> <b>Tipo de servicio:</b></TD>";
/*
'0', 'Particular                                                      ');
'1', 'Publico                                                         ');
'2', 'Oficial                                                         ');
'3', 'Vehiculo de emergencia                                          ');
'4', 'Vehiculo de servicio diplomÃ¡tico o consular                    ');
'5', 'Vehiculo de transporte masivo                                   ');
'6', 'Vehiculo escolar
.*/
		if($datos['tipo_servicio_vehiculo_id']=='0')
		{
			$html.="<TD WIDTH='100' HEIGHT=25>Particular [X]</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Publico</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Oficial</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Vehiculo de emergencia</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='160' HEIGHT=25>                  </TD>";
			$html.="<TD WIDTH='200' HEIGHT=25>Vehiculo de servicio diplomático ó consular</TD>";
			$html.="<TD WIDTH='200' HEIGHT=25>Vehiculo de transporte masivo</TD>";
			$html.="<TD WIDTH='200' HEIGHT=25>Vehiculo escolar</TD>";
			$html.="</TR>";
		}
		elseif($datos['tipo_servicio_vehiculo_id']=='1')
		{
			$html.="<TD WIDTH='100' HEIGHT=25>Particular</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Publico [X]</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Oficial</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Vehiculo de emergencia</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='160' HEIGHT=25>                  </TD>";
			$html.="<TD WIDTH='200' HEIGHT=25>Vehiculo de servicio diplomático ó consular</TD>";
			$html.="<TD WIDTH='200' HEIGHT=25>Vehiculo de transporte masivo</TD>";
			$html.="<TD WIDTH='200' HEIGHT=25>Vehiculo escolar</TD>";
			$html.="</TR>";
		}
		elseif($datos['tipo_servicio_vehiculo_id']=='2')
		{
			$html.="<TD WIDTH='100' HEIGHT=25>Particular</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Publico</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Oficial[X]</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Vehiculo de emergencia</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='160' HEIGHT=25>                  </TD>";
			$html.="<TD WIDTH='200' HEIGHT=25>Vehiculo de servicio diplomático ó consular</TD>";
			$html.="<TD WIDTH='200' HEIGHT=25>Vehiculo de transporte masivo</TD>";
			$html.="<TD WIDTH='200' HEIGHT=25>Vehiculo escolar</TD>";
			$html.="</TR>";
		}
		elseif($datos['tipo_servicio_vehiculo_id']=='3')
		{
			$html.="<TD WIDTH='100' HEIGHT=25>Particular</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Publico</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Oficial</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Vehiculo de emergencia [X]</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='160' HEIGHT=25>                  </TD>";
			$html.="<TD WIDTH='200' HEIGHT=25>Vehiculo de servicio diplomático ó consular</TD>";
			$html.="<TD WIDTH='200' HEIGHT=25>Vehiculo de transporte masivo</TD>";
			$html.="<TD WIDTH='200' HEIGHT=25>Vehiculo escolar</TD>";
			$html.="</TR>";
		}
		elseif($datos['tipo_servicio_vehiculo_id']=='4')
		{
			$html.="<TD WIDTH='100' HEIGHT=25>Particular</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Publico</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Oficial</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Vehiculo de emergencia</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='160' HEIGHT=25>                  </TD>";
			$html.="<TD WIDTH='200' HEIGHT=25>Vehiculo de servicio diplomático ó consular [X]</TD>";
			$html.="<TD WIDTH='200' HEIGHT=25>Vehiculo de transporte masivo</TD>";
			$html.="<TD WIDTH='200' HEIGHT=25>Vehiculo escolar</TD>";
			$html.="</TR>";
		}
		elseif($datos['tipo_servicio_vehiculo_id']=='5')
		{
			$html.="<TD WIDTH='100' HEIGHT=25>Particular</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Publico</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Oficial</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Vehiculo de emergencia</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='160' HEIGHT=25>                  </TD>";
			$html.="<TD WIDTH='200' HEIGHT=25>Vehiculo de servicio diplomático ó consular</TD>";
			$html.="<TD WIDTH='200' HEIGHT=25>Vehiculo de transporte masivo [X]</TD>";
			$html.="<TD WIDTH='200' HEIGHT=25>Vehiculo escolar</TD>";
			$html.="</TR>";
		}
		elseif($datos['tipo_servicio_vehiculo_id']=='6')
		{
			$html.="<TD WIDTH='100' HEIGHT=25>Particular</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Publico</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Oficial</TD>";
			$html.="<TD WIDTH='100' HEIGHT=25>Vehiculo de emergencia</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='160' HEIGHT=25>                  </TD>";
			$html.="<TD WIDTH='200' HEIGHT=25>Vehiculo de servicio diplomático ó consular</TD>";
			$html.="<TD WIDTH='200' HEIGHT=25>Vehiculo de transporte masivo</TD>";
			$html.="<TD WIDTH='200' HEIGHT=25>Vehiculo escolar [X]</TD>";
			$html.="</TR>";
		}
    else
      $html.="</TR>";
		
		$html.="  <TR>";
		$html.="    <TD WIDTH='150' HEIGHT=25 ALIGN='LEFT'><b>Código de la aseguradora:</b></TD>";
		$html.="    <TD WIDTH='150' HEIGHT=25>".$datos['codigo_aseguradora']."</TD>";
		$html.="  </TR>";
		$html.="  <TR>";
		$html.="    <TD WIDTH='70' HEIGHT=25 ALIGN='LEFT'><b>Nro. de la Poliza:</b></TD>";
		$html.="    <TD WIDTH='150' HEIGHT=25>".$datos['poliza']."</TD>";
		$html.="    <TD WIDTH='200' HEIGHT=25 ALIGN='LEFT'><b>Intervension de la autoridad:</b></TD>";
		if($datos['intervension_autoridad']=='1')
		{
			$html.="<TD WIDTH='150' HEIGHT=25>SI [X]</TD>";
			$html.="<TD WIDTH='150' HEIGHT=25>NO</TD>";
		}
		elseif($datos['intervension_autoridad']=='0')
		{
			$html.="<TD WIDTH='150' HEIGHT=25>SI</TD>";
			$html.="<TD WIDTH='150' HEIGHT=25>NO [X]</TD>";
		}
		$html.="  </TR>";
		$html.="  <TR>";
		$html.="    <TD WIDTH='150' HEIGHT=25 ALIGN='LEFT'><b>Vigencia</b> Desde:</TD>";
		if($datos['vigencia_desde']=='//')
		{
			$datos['vigencia_desde']='****';
		}
		$html.="<TD WIDTH='100' HEIGHT=25>".$datos['vigencia_desde']."</TD>";
		$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>Hasta:</TD>";
		if($datos['vigencia_hasta']=='//')
		{
			$datos['vigencia_hasta']='****';
		}
		$html.="<TD WIDTH='100' HEIGHT=25>".$datos['vigencia_hasta']."</TD>";
		$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'><b>Cobro Excedente:</b></TD>";
		if($datos['asegura'] == 'POLIZA VENCIDA')
		{
			$html.="<TD WIDTH='100' HEIGHT=25>SI [X]   NO [ ]</TD>";
		}
		else
		{
			$html.="<TD WIDTH='100' HEIGHT=25>SI [ ]   NO [X]</TD>";
		}
		
		$html.="  </TR>";
    $pdf->RoundedRect(7, 3, 202, 283, 3.8, '');
		/**///V. DATOS DEL PROPIETARIO DEL VEHICULO
		$html.="  <TR>";
		$html.="    <TD WIDTH='760' HEIGHT=25 ALIGN='CENTER' bgcolor='#CCCCCC'><br>";
		$html.="      <b>V. DATOS DEL PROPIETARIO DEL VEHICULO</b>";
		$html.="    </TD>";
		$html.="  </TR>";
		$Apellidos=explode(' ',$datos['apellidos_propietario']);
		$html.="  <TR>";
		$html.="    <TD WIDTH='210' HEIGHT=25 ALIGN='LEFT'>Primer Apellido: ".$Apellidos[0]."</TD>";//PRIMER APELLIDO O RAZON SOCIAL
		$html.="    <TD WIDTH='360' HEIGHT=25>Segundo Apellido:  ".$Apellidos[1]."</TD>";
		$html.="  </TR>";
		$Nombres=explode(' ',$datos['nombres_propietario']);
		$html.="  <TR>";
		$html.="    <TD WIDTH='210' HEIGHT=25>Primer Nombre: ".$Nombres[0]."</TD>";
		$html.="    <TD WIDTH='360' HEIGHT=25>Segundo Nombre: ".$Nombres[1]."</TD>";
		$html.="  </TR>";
		$html.="  <TR>";
		$html.="    <TD WIDTH='150' HEIGHT=25 ALIGN='LEFT'><b>Tipo documento</b></TD>";
    $html.="    <TD WIDTH='160' HEIGHT=25>".$datos['tipo_id_propietario']."".' Nro. Documento '."".$datos['propietario_id']."</TD>";
		$html.="  </TR>";
		$html.="  <TR>";
		$html.="    <TD WIDTH='160' HEIGHT=25><b>Dirección Residencia  </b></TD>";
		$html.="    <TD WIDTH='30' HEIGHT=25 ALIGN='LEFT'>".$datos['direccion_propietario']."</TD>";
		$html.="  </TR>";
		$html.="  <TR>";
		$html.="    <TD WIDTH='100' HEIGHT=25><b>Departamento  </b></TD>";
		$html.="    <TD WIDTH='150' HEIGHT=25 ALIGN='LEFT'>".$datos['departamento_propietario']."</TD>";
		$html.="    <TD WIDTH='100' HEIGHT=25><b>Cod.  </b></TD>";
		$html.="    <TD WIDTH='150' HEIGHT=25 ALIGN='LEFT'>".$datos['tipo_dpto_id_propietario']."</TD>";
		$html.="    <TD WIDTH='100' HEIGHT=25><b>Telefono  </b></TD>";
		$html.="    <TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>".$datos['telefono_propietario']."</TD>";
		$html.="  </TR>";
		$html.="  <TR>";
		$html.="    <TD WIDTH='100' HEIGHT=25><b>Municipio Residendia  </b></TD>";
		$html.="    <TD WIDTH='150' HEIGHT=25 ALIGN='LEFT'>".$datos['municipio_propietario']."</TD>";
		$html.="    <TD WIDTH='100' HEIGHT=25><b>Cod.  </b></TD>";
		$html.="    <TD WIDTH='150' HEIGHT=25 ALIGN='LEFT'>".$datos['tipo_mpio_id_propietario']."</TD>";
		$html.="  </TR>";
		//$html.="  <TR>";
		//$html.="    <TD>";
		
		$html.="<TABLE BORDER='1'><TR><PRE>                                                                                                                                                                                                                                                                                     Total Folios:        <TD width='13%' height='13%'>&nbsp;</TD><TD width='13%' height='13%'>&nbsp;</TD><TD width='13%' height='13%'>&nbsp;</TD></PRE></TR>";
    $html.="</TABLE>";		
		//$pdf->Ln(8);
		//$html.="    </TD>";
    //$html.="  </TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25 ALIGN='CENTER' bgcolor='#CCCCCC'><br>";
		$html.="<b>VI. DATOS DEL CONDUCTOR DEL VEHICULO INVOLUCRADO EN EL ACCIDENTE DE TRANSITO</b>";
		$html.="</TD>";
		$html.="</TR>";

		$ApellidosC=explode(' ',$datos['apellidos_conductor']);
		$html.="<TR>";
		$html.="<TD WIDTH='210' HEIGHT=25>Primer Apellido: ".$ApellidosC[0]."</TD>";
		$html.="<TD WIDTH='210' HEIGHT=25>Segundo Apellido: ".$ApellidosC[1]."</TD>";
		$html.="</TR>";
		$NombresC=explode(' ',$datos['nombres_conductor']);
		$html.="<TR>";
		$html.="<TD WIDTH='210' HEIGHT=25>Primer Nombre: ".$NombresC[0]."</TD>";
		$html.="<TD WIDTH='210' HEIGHT=25>Segundo Nombre: ".$NombresC[1]."</TD>";
		$html.="</TR>";
		
		$html.="<TR>";
		$html.="<TD WIDTH='150' HEIGHT=25 ALIGN='LEFT'><b>Tipo documento</b></TD>";
		$html.="<TD WIDTH='160' HEIGHT=25>".$datos['tipo_id_conductor']."".' Nro. Documento '."".$datos['conductor_id']."</TD>";
		$html.="</TR>";
		
		$html.="<TR>";
		$html.="<TD WIDTH='160' HEIGHT=25><b>Dirección Residencia  </b></TD>";
		$html.="<TD WIDTH='30' HEIGHT=25 ALIGN='LEFT'>".$datos['direccion_conductor']."</TD>";
		$html.="</TR>";
		
		$html.="<TR>";
		$html.="<TD WIDTH='100' HEIGHT=25><b>Departamento  </b></TD>";
		$html.="<TD WIDTH='150' HEIGHT=25 ALIGN='LEFT'>".$datos['departamento_conductor']."</TD>";
		$html.="<TD WIDTH='100' HEIGHT=25><b>Cod.  </b></TD>";
		$html.="<TD WIDTH='150' HEIGHT=25 ALIGN='LEFT'>".$datos['tipo_dpto_id_conductor']."</TD>";
		$html.="<TD WIDTH='100' HEIGHT=25><b>Telefono  </b></TD>";
		$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>".$datos['telefono_conductor']."</TD>";
		$html.="</TR>";
		
		$html.="<TR>";
		$html.="<TD WIDTH='100' HEIGHT=25><b>Municipio Residendia  </b></TD>";
		$html.="<TD WIDTH='150' HEIGHT=25 ALIGN='LEFT'>".$datos['municipio_conductor']."</TD>";
		$html.="<TD WIDTH='100' HEIGHT=25><b>Cod.  </b></TD>";
		$html.="<TD WIDTH='150' HEIGHT=25 ALIGN='LEFT'>".$datos['tipo_mpio_id_conductor']."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25 ALIGN='CENTER' bgcolor='#CCCCCC'><br>";
		$html.="<b>VII. DATOS DE REMISIÓN</b>";
		$html.="</TD>";
		$html.="</TR>";
		
		$html.="<TR>";
		$html.="<TD WIDTH='160' HEIGHT=25><b>Tipo Referencia  </b></TD>";

		if($datos['tipo_referencia'] == 'R' )
		{
			$html.="<TD WIDTH='60' HEIGHT=25 ALIGN='LEFT'>Remisión [X]</TD>";
			$html.="<TD WIDTH='30' HEIGHT=25 ALIGN='LEFT'>Orden de Servicio [ ]</TD>";
		}else
		if($datos['tipo_referencia'] == 'OS')
		{
			$html.="<TD WIDTH='50' HEIGHT=25 ALIGN='LEFT'>Remisión [ ]</TD>";
			$html.="<TD WIDTH='30' HEIGHT=25 ALIGN='LEFT'>Orden de Servicio [X]</TD>";
		}
		else
		{
		$html.="<TD WIDTH='50' HEIGHT=25 ALIGN='LEFT'>Remisión[ ]</TD>";
                $html.="<TD WIDTH='30' HEIGHT=25 ALIGN='LEFT'>Orden de Servicio [ ]</TD>";
		}

		$html.="</TR>";
		$fechaO=explode(' ',$datos['fecha_os']);	
		$html.="<TR>";
		$html.="<TD WIDTH='160' HEIGHT=25><b>Fecha Remesión  </b></TD>";
		//$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>".$datos['fecha_recepcion_remision']."</TD>";
		$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>".$fechaO[0]."</TD>";
		$html.="<TD WIDTH='80' HEIGHT=25><b>a las  </b></TD>";
		$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>".$datos[hora_os]."</TD>";
		$html.="</TR>";

		if($datos['tipo_referencia'] != "")	
		{
		$html.="<TR>";
		$html.="<TD WIDTH='160' HEIGHT=25><b>Prestador que remite  </b></TD>";
		$html.="<TD WIDTH='30' HEIGHT=25 ALIGN='LEFT'>".$datos['empresa']."</TD>";
		$html.="</TR>";
		$html.="<TR>";
                $html.="<TD WIDTH='160' HEIGHT=25><b>Código Inscripción  </b></TD>";
                $html.="<TD WIDTH='30' HEIGHT=25 ALIGN='LEFT'>".$datos['codigo_sgsss']."</TD>";
                $html.="</TR>";
		}
		else
		{
		$html.="<TR>";
                $html.="<TD WIDTH='160' HEIGHT=25><b>Prestador que remite  </b></TD>";
                $html.="<TD WIDTH='30' HEIGHT=25 ALIGN='LEFT'></TD>";
                $html.="</TR>";
		$html.="<TR>";
                $html.="<TD WIDTH='160' HEIGHT=25><b>Código Inscripción  </b></TD>";
                $html.="<TD WIDTH='30' HEIGHT=25 ALIGN='LEFT'></TD>";
                $html.="</TR>";
		}
		
		$html.="<TR>";
		$html.="<TD WIDTH='160' HEIGHT=25><b>Profesional que remite  </b></TD>";
		//$html.="<TD WIDTH='300' HEIGHT=25 ALIGN='LEFT'>".$datos['nombre_profesional_recibe_r']."</TD>";
		$html.="<TD WIDTH='300' HEIGHT=25 ALIGN='LEFT'>".$datos[nombre_profesional_remite]."</TD>";
		$html.="<TD WIDTH='160' HEIGHT=25><b>Cargo  </b></TD>";
		//$html.="<TD WIDTH='30' HEIGHT=25 ALIGN='LEFT'>".$datos['profesional_recibe_cargo_r']."</TD>";
		$html.="<TD WIDTH='30' HEIGHT=25 ALIGN='LEFT'>".$datos['profesional_remite_cargo']."</TD>";
		$html.="</TR>";
		
		$fechaR=explode(' ',$datos['fecha_recepcion_remision']);	
		$html.="<TR>";		
		$html.="<TD WIDTH='160' HEIGHT=25><b>Fecha de Aceptación  </b></TD>";
		//$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>".$datos['fecha_os']."</TD>";
		$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>".$fechaR[0]."</TD>";
		$html.="<TD WIDTH='80' HEIGHT=25><b>a las  </b></TD>";
		//$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>".$datos['hora_os']."</TD>";
		$html.="<TD WIDTH='100' HEIGHT=25 ALIGN='LEFT'>".$datos['hora_recepcion_remision']."</TD>";
		$html.="</TR>";
		
		$html.="<TR>";
		$html.="<TD WIDTH='160' HEIGHT=25><b>Prestador que Recibe  </b></TD>";
		$html.="<TD WIDTH='30' HEIGHT=25 ALIGN='LEFT'>".$datos['descentro']."</TD>";
		$html.="</TR>";
			
		$html.="<TR>";
		$html.="<TD WIDTH='160' HEIGHT=25><b>Código Inscripción  </b></TD>";
		$html.="<TD WIDTH='30' HEIGHT=25 ALIGN='LEFT'>".$datos['codigo_inscripcion']."</TD>";
		$html.="</TR>";
		
		$html.="<TR>";
		$html.="<TD WIDTH='160' HEIGHT=25><b>Profesional que recibe  </b></TD>";
		//$html.="<TD WIDTH='300' HEIGHT=25 ALIGN='LEFT'>".$datos['nombre_profesional_recibe_os']."</TD>";
		$html.="<TD WIDTH='300' HEIGHT=25 ALIGN='LEFT'>".$datos['nombre_profesional_recibe']."</TD>";
		$html.="<TD WIDTH='160' HEIGHT=25><b>Cargo  </b></TD>";
		//$html.="<TD WIDTH='30' HEIGHT=25 ALIGN='LEFT'>".$datos['profesional_recibe_cargo_os']."</TD>";
		$html.="<TD WIDTH='30' HEIGHT=25 ALIGN='LEFT'>".$datos['profesional_recibe_cargo']."</TD>";
		$html.="</TR>";
		
		/**///VIII. AMPARO DE TRANSPORTE Y MOVILIZACIÓN DE LA VICTIMA
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25 ALIGN='CENTER' bgcolor='#CCCCCC'><br>";
		$html.="<b>VIII. AMPARO DE TRANSPORTE Y MOVILIZACIÓN DE LA VICTIMA</b>";
		$html.="</TD>";
		$html.="</TR>";
		
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>Diligenciar únicamente para el transportedesde el sitio del eventohasta la primera IPS (transporte primario) ycuando se realiza en ambulancia de la misma IPS.</TD>";
		$html.="</TR>";
		
		$html.="<TR>";
		$html.="<TD WIDTH='160' HEIGHT=25><b>Datos del Vehiculo  </b></TD>";
		$html.="<TD WIDTH='300' HEIGHT=25 ALIGN='LEFT'><b>Placa No.</b>".$datos['placa_ambulancia']."</TD>";
		$html.="</TR>";
		
		$html.="<TR>";
		$html.="<TD WIDTH='160' HEIGHT=25><b>Transporto la victima desde  </b></TD>";
		$html.="<TD WIDTH='300' HEIGHT=25 ALIGN='LEFT'>".$datos['lugar_desde']."</TD>";
		$html.="<TD WIDTH='60' HEIGHT=25><b>Hasta  </b></TD>";
		$html.="<TD WIDTH='250' HEIGHT=25 ALIGN='LEFT'>".$datos['lugar_hasta']."</TD>";
		$html.="</TR>";

		$html.="<TR>";
		$html.="<TD WIDTH='160' HEIGHT=25><b>Tipo de transporte  </b></TD>";
		if(trim($datos[descripcion_ambulancia]) == 'TAB')
		{
			$html.="<TD WIDTH='250' HEIGHT=25 ALIGN='LEFT'>Ambulancia Básica [X]    Ambulancia Medicalizada [ ]</TD>";
		}elseif(trim($datos['descripcion_ambulancia']) == 'TAM')
		{
			$html.="<TD WIDTH='250' HEIGHT=25 ALIGN='LEFT'>Ambulancia Básica [ ]    Ambulancia Medicalizada [X]</TD>";
		}

		$html.="<TD WIDTH='300' HEIGHT=25><b>Lugar donde recoge la victima   Zona</b></TD>";
		if($datos['deszona_traslado'] == 'Rural')
		{
			$html.="<TD WIDTH='150' HEIGHT=25 ALIGN='LEFT'>R [X]    U [ ]</TD>";
		}elseif($datos['deszona_traslado'] == 'Urbana')
		{
			$html.="<TD WIDTH='150' HEIGHT=25 ALIGN='LEFT'>R [ ]    U [X]</TD>";
		}
		$html.="</TR>";
		
		/**///IX. CERTIFICACION DE LA ATENCION MEDICA DE LA VICTIMA COMO PRUEBA DEL ACCIDENTE O EVENTO
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25 ALIGN='CENTER' bgcolor='#CCCCCC'><br>";
		$html.="<b>IX. CERTIFICACION DE LA ATENCION MEDICA DE LA VICTIMA COMO PRUEBA DEL ACCIDENTE O EVENTO</b>";
		$html.="</TD>";
		$html.="</TR>";
		
		$datos[fecha_ingreso] = str_replace("/","",$datos[fecha_ingreso]);
		$datos[fecha_egreso] = str_replace("/","",$datos[fecha_egreso]);
		$html.="<TR>";
		$html.="<TD WIDTH='190' HEIGHT=25><b>Fecha ingreso  </b>".$datos[fecha_ingreso]."    a las  ".$datos[hora_ingreso]."   </TD>";
		$html.="<TD WIDTH='190' HEIGHT=25><b>Fecha Egreso </b>   ".$datos[fecha_egreso]."    a las ".$datos[hora_egreso]."</TD>";
		$html.="</TR>";
		
		$html.="<TR>";
		$html.="<TD WIDTH='180' HEIGHT=25><b>Código de diagnostico principal de ingreso </b>   ".$datos[diagnostio_id_ingreso]."</TD>";
		$html.="<TD WIDTH='180' HEIGHT=25><b>Código de diagnostico principal de Egreso </b>    ".$datos[diagnostio_id_egreso]."</TD>";
		$html.="</TR>";
		
		
		$html.="<TR>";
		$html.="<TD WIDTH='190' HEIGHT=25><b>Otro código de diagnostico de ingreso </b>  ".$datos[diagnostico_id_ingreso1]."</TD>";
		$html.="<TD WIDTH='190' HEIGHT=25><b>Otro código de diagnostico principal de Egreso </b>    ".$datos[diagnostico_id_egreso1]."</TD>";
		$html.="</TR>";
				
		$html.="<TR>";
		$html.="<TD WIDTH='190' HEIGHT=25><b>Otro código de diagnostico de ingreso </b>   ".$datos[diagnostico_id_ingreso2]."</TD>";
		$html.="<TD WIDTH='190' HEIGHT=25><b>Otro código de diagnostico principal de Egreso </b>    ".$datos[diagnostico_id_egreso2]."</TD>";
		$html.="</TR>";
		
		$html.="<TR>";
		$html.="<TD WIDTH='190' HEIGHT=25><b>Nombre médico o Profesional tratante </b>                    </TD>";
		$html.="<TD WIDTH='250' HEIGHT=25>".$datos[nombre_medico]."</TD>";
		$html.="</TR>";
		
		$html.="<TR>";
		$html.="<TD WIDTH='100' HEIGHT=25><b>Tipo documento </b></TD>";
		$html.="<TD WIDTH='150' HEIGHT=25>".$datos[tipo_id_tercero_medico]."</TD>";
		$html.="<TD WIDTH='100' HEIGHT=25><b>No. documento </b></TD>";
		$html.="<TD WIDTH='250' HEIGHT=25>".$datos[tercero_id_medico]."</TD>";
		$html.="</TR>";
		
		$html.="<TR>";
		$html.="<TD WIDTH='100' HEIGHT=25><b>        </b></TD>";
		$html.="<TD WIDTH='150' HEIGHT=25>                    </TD>";
		$html.="<TD WIDTH='300' HEIGHT=25><b>Número de registro médico</b></TD>";
		$html.="<TD WIDTH='250' HEIGHT=25>".$datos[tarjeta_profesional]."</TD>";
		$html.="</TR>";
		
		/**///X. AMPAROS QUE RECLAMA
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25 ALIGN='CENTER' bgcolor='#CCCCCC'><br>";
		$html.="<b>X. AMPAROS QUE RECLAMA</b>";
		$html.="</TD>";
		$html.="</TR>";
		
		$html.="<TR>";
		$html.="<TD WIDTH='350' HEIGHT=25>.</TD>";
		$html.="<TD WIDTH='150' HEIGHT=25><b>VALOR TOTAL FACTURADO</b></TD>";
		$html.="<TD WIDTH='150' HEIGHT=25><b>VALOR RECLAMADO AL FOSYGA</b></TD>";
		$html.="</TR>";
		
		$html.="<TR>";
		$html.="<TD WIDTH='350' HEIGHT=25><b>GASTOS MEDICO QUIRURGICOS</b></TD>";
		$html.="<TD WIDTH='150' HEIGHT=25> ".$datos[valor_factura]."                   </TD>";
		$html.="<TD WIDTH='150' HEIGHT=25> ".$datos['valor_facturaf']."                </TD>";
		$html.="</TR>";
		
		$html.="<TR>";
		$html.="<TD WIDTH='150' HEIGHT=25><b>GASTOS DE TRANSPORTE Y MOVILIZACIÓN DE VICTIMAS</b></TD>";
		$html.="<TD WIDTH='250' HEIGHT=25>                    </TD>";
		$html.="<TD WIDTH='250' HEIGHT=25>                    </TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='150' HEIGHT=25>El total facturado y reclamado descrito en este numeral se debe detallar y hacer descripcion de las actividades, procedimientos, medicamentos, insumos, suministros y materiales, dentro</TD></TR>";
		$html.="<TR><TD WIDTH='250' HEIGHT=25>del anexo técnico numero 2.</TD>";		
		$html.="</TR>";
		
		/**///XI. AMPAROS QUE RECLAMA
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25 ALIGN='CENTER' bgcolor='#CCCCCC'><br>";
		$html.="<b>XI. DECLARACIÓN DE LA INSTITUCIÓN PRESTADORA DE SERVCION DE SALUD</b>";
		$html.="</TD>";
		$html.="</TR>";
		
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>Como representate legal ó Gerente de la Institución Prestadora de Servicios de Salud, declaró bajo la gravedad de juramento que toda la información contenida en este formulario es cierta y podrá ser</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>verificada por la Dirección de Financiamiento del Ministerio de Protección Social, por el Administrador Fiduciario de Solidaridad y Garantia Fosyga, por la Superintendencia Nacioanal de Salud ó</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>la Contraloria General de la Republica con la IPS y las aseguradoras, de no ser así, acepto las concecuencias legales que produzca esta situación.</TD>";
		$html.="</TR>";
		
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>.</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='350' HEIGHT=25 ALIGN='LEFT'><b>_____________________________________</b></TD>";
		$html.="<TD WIDTH='300' HEIGHT=25 ALIGN='RIGHT'><b>________________________________________</b></TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='350' HEIGHT=25 ALIGN='LEFT'>NOMBRE</TD>";
		$html.="<TD WIDTH='300' HEIGHT=25 ALIGN='RIGHT'>FIRMA DEL REPRESENTATE LEGAL, GERENTE O SU DELEGADO</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD ALIGN='RIGHT' WIDTH='760'>";
		$html.="<FONT SIZE='6'>"._SIIS_APLICATION_TITLE."</FONT>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="</TABLE>";
		$pdf->WriteHTML($html);
		$pdf->SetLineWidth(0.3);
/*		$pdf->RoundedRect(7, 5, 202, 11, 3.5, '');
		$pdf->RoundedRect(7, 23, 202, 26, 3.5, '');
		if($datos['lugar_expedicion_documento'])
		{
		$pdf->RoundedRect(7, 48, 202, 70, 3.5, '');
		$pdf->RoundedRect(7, 120, 202, 65, 3.5, '');
		$pdf->RoundedRect(7, 187, 202, 32, 3.5, '');
		$pdf->RoundedRect(7, 221, 202, 40, 3.5, '');
		}
		else
		{
		$pdf->RoundedRect(7, 46, 202, 70, 3.5, '');
		$pdf->RoundedRect(7, 118, 202, 65, 3.5, '');
		$pdf->RoundedRect(7, 185, 202, 32, 3.5, '');
		$pdf->RoundedRect(7, 219, 202, 40, 3.5, '');
		}*/
		//$pdf->RoundedRect(7, 3, 202, 283, 3.8, '');
		$pdf->RoundedRect(7, 3, 202, 283, 3.8, '');
		$pdf->Output($Dir,'F');
		return True;
	}

		
	function GenerarSoatReclamoDinero_forecat($datos)
	{
		UNSET($_SESSION['REPORTES']['VARIABLE']);
		//IncludeLib('funciones_admision');
		$_SESSION['REPORTES']['VARIABLE']='furips';
		$Dir="cache/reclamacion_entidades_forecat.pdf";
		require_once("classes/fpdf/html_class.php");
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
		$pdf->Output($Dir,'F');
		return True;
	}

?>
