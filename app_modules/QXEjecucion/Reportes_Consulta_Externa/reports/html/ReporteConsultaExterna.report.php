<?php

/**
 * $Id: ReporteConsultaExterna.report.php,v 1.2 2005/06/03 18:46:59 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Archivo que imprime el formulario anexo1 que se entrega al FOSYGA
 */

class ReporteConsultaExterna_report
{
	function ReporteConsultaExterna_report($datos=array())
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
		$HTML_WEB_PAGE ="<HTML><BODY>";
		$HTML_WEB_PAGE.="<TABLE WIDTH='100%' BORDER=0>";
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD WIDTH='30%' ALIGN='LEFT'><FONT SIZE='1'>NOMBRE DE LA EMPRESA:</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='70%' ALIGN='LEFT'><FONT SIZE='1'>".$this->datos['var']['razonso']."</FONT></TD>";
		$HTML_WEB_PAGE.="</TR>";
		if($this->datos['var']['codigodepa'])
		{
			$HTML_WEB_PAGE.="<TR>";
			$HTML_WEB_PAGE.="<TD WIDTH='30%' ALIGN='LEFT'><FONT SIZE='1'>DEPARTAMENTO:</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='70%' ALIGN='LEFT'><FONT SIZE='1'>".$this->datos['var']['descridepa']."</FONT></TD>";
			$HTML_WEB_PAGE.="</TR>";
		}
		if($this->datos['var']['codigotico'])
		{
			$HTML_WEB_PAGE.="<TR>";
			$HTML_WEB_PAGE.="<TD WIDTH='30%' ALIGN='LEFT'><FONT SIZE='1'>TIPO DE CONSULTA:</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='70%' ALIGN='LEFT'><FONT SIZE='1'>".$this->datos['var']['descritico']."</FONT></TD>";
			$HTML_WEB_PAGE.="</TR>";
		}
		if($this->datos['var']['documentos'])
		{
			$HTML_WEB_PAGE.="<TR>";
			$HTML_WEB_PAGE.="<TD WIDTH='30%' ALIGN='LEFT'><FONT SIZE='1'>NOMBRE DEL PROFESIONAL:</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='70%' ALIGN='LEFT'><FONT SIZE='1'>".$this->datos['var']['nombreprof']."</FONT></TD>";
			$HTML_WEB_PAGE.="</TR>";
		}
		if($this->datos['var']['fechadesde'])
		{
			$HTML_WEB_PAGE.="<TR>";
			$HTML_WEB_PAGE.="<TD WIDTH='30%' ALIGN='LEFT'><FONT SIZE='1'>FECHA FINAL:</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='70%' ALIGN='LEFT'><FONT SIZE='1'>".$this->datos['var']['fechadesde']."</FONT></TD>";
			$HTML_WEB_PAGE.="</TR>";
		}
		if($this->datos['var']['fechahasta'])
		{
			$HTML_WEB_PAGE.="<TR>";
			$HTML_WEB_PAGE.="<TD WIDTH='30%' ALIGN='LEFT'><FONT SIZE='1'>FECHA FINAL:</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='70%' ALIGN='LEFT'><FONT SIZE='1'>".$this->datos['var']['fechahasta']."</FONT></TD>";
			$HTML_WEB_PAGE.="</TR>";
		}
		$HTML_WEB_PAGE.="</TABLE>";
		$registros1=sizeof($datos1);
		for($i=0;$i<$registros1;$i++)//$registros
		{
			$HTML_WEB_PAGE.="<TABLE WIDTH='100%' BORDER=1>";
			$HTML_WEB_PAGE.="<TR>";
			$HTML_WEB_PAGE.="<TD WIDTH='4%'  ALIGN='CENTER'><FONT SIZE='1'>No.</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='7%'  ALIGN='CENTER'><FONT SIZE='1'>TIPO ID</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='15%' ALIGN='CENTER'><FONT SIZE='1'>IDENTIFICACIÓN</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='65%' ALIGN='CENTER'><FONT SIZE='1'>NOMBRE</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='9%'  ALIGN='CENTER'><FONT SIZE='1'>ESTADO</FONT></TD>";
			$HTML_WEB_PAGE.="</TR>";
			$HTML_WEB_PAGE.="<TR>";
			$HTML_WEB_PAGE.="<TD WIDTH='4%'  ALIGN='CENTER'><FONT SIZE='1'>";
			$HTML_WEB_PAGE.="".($i+1)."";
			$HTML_WEB_PAGE.="</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='7%'  ALIGN='CENTER'><FONT SIZE='1'>";
			$HTML_WEB_PAGE.="".$datos1[$i]['tipo_id_tercero']."";
			$HTML_WEB_PAGE.="</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='15%' ALIGN='CENTER'><FONT SIZE='1'>";
			$HTML_WEB_PAGE.="".$datos1[$i]['tercero_id']."";
			$HTML_WEB_PAGE.="</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='65%' ALIGN='LEFT'><FONT SIZE='1'>";
			$HTML_WEB_PAGE.="".$datos1[$i]['nombre_tercero']."";
			$HTML_WEB_PAGE.="</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='9%'  ALIGN='CENTER'><FONT SIZE='1'>";
			if($datos1[$i]['tercero_id']['estado']==1)
			{
				$HTML_WEB_PAGE.="ACTIVO";
			}
			else
			{
				$HTML_WEB_PAGE.="INACTIVO";
			}
			$HTML_WEB_PAGE.="</FONT></TD>";
			$HTML_WEB_PAGE.="</TR>";
			$HTML_WEB_PAGE.="</TABLE>";
			$HTML_WEB_PAGE.="<TABLE WIDTH='100%' BORDER=1>";
			$HTML_WEB_PAGE.="<TR>";
			$HTML_WEB_PAGE.="<TD WIDTH='5%'  ALIGN='CENTER'><FONT SIZE='1'>No.</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='10%' ALIGN='CENTER'><FONT SIZE='1'>FECHA TURNO</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='4%'  ALIGN='CENTER'><FONT SIZE='1'>HORA</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='10%' ALIGN='CENTER'><FONT SIZE='1'>DURACIÓN</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='10%' ALIGN='CENTER'><FONT SIZE='1'>CONSULTORIO</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='20%' ALIGN='CENTER'><FONT SIZE='1'>DESCRIPCIÓN</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='28%' ALIGN='CENTER'><FONT SIZE='1'>NOMBRE PACIENTE</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='13%' ALIGN='CENTER'><FONT SIZE='1'>IDENT. PACIENTE</FONT></TD>";
			$HTML_WEB_PAGE.="</TR>";
			$datos2=$this->BuscarDatosReporte2($datos1[$i]['tipo_id_tercero'],$datos1[$i]['tercero_id'],$this->datos['var']);
			$registros2=sizeof($datos2);
			for($j=0;$j<$registros2;$j++)//$registros
			{
				$HTML_WEB_PAGE.="<TR>";
				$HTML_WEB_PAGE.="<TD WIDTH='5%'  ALIGN='CENTER'><FONT SIZE='1'>";
				$HTML_WEB_PAGE.="".($j+1)."";
				$HTML_WEB_PAGE.="</FONT></TD>";
				$HTML_WEB_PAGE.="<TD WIDTH='10%' ALIGN='CENTER'><FONT SIZE='1'>";
				$HTML_WEB_PAGE.="".$datos2[$j]['fecha_turno']."";
				$HTML_WEB_PAGE.="</FONT></TD>";
				$HTML_WEB_PAGE.="<TD WIDTH='4%'  ALIGN='CENTER'><FONT SIZE='1'>";
				$HTML_WEB_PAGE.="".$datos2[$j]['hora']."";
				$HTML_WEB_PAGE.="</FONT></TD>";
				$HTML_WEB_PAGE.="<TD WIDTH='10%' ALIGN='CENTER'><FONT SIZE='1'>";
				$HTML_WEB_PAGE.="".$datos2[$j]['duracion']."";
				$HTML_WEB_PAGE.="</FONT></TD>";
				$HTML_WEB_PAGE.="<TD WIDTH='10%'><FONT SIZE='1'>";
				$HTML_WEB_PAGE.="".$datos2[$j]['consultorio_id']."";
				$HTML_WEB_PAGE.="</FONT></TD>";
				$HTML_WEB_PAGE.="<TD WIDTH='20%'><FONT SIZE='1'>";
				$HTML_WEB_PAGE.="".$datos2[$j]['descripcion']."";
				$HTML_WEB_PAGE.="</FONT></TD>";
				$HTML_WEB_PAGE.="<TD WIDTH='28%' ALIGN='CENTER'><FONT SIZE='1'>";
				$HTML_WEB_PAGE.="".$datos2[$j]['nombre']."";
				$HTML_WEB_PAGE.="</FONT></TD>";
				$HTML_WEB_PAGE.="<TD WIDTH='13%' ALIGN='CENTER'><FONT SIZE='1'>";
				$HTML_WEB_PAGE.="".$datos2[$j]['tipo_id_paciente']."".' - '."".$datos2[$j]['paciente_id']."";
				$HTML_WEB_PAGE.="</FONT></TD>";
				$HTML_WEB_PAGE.="</TR>";
			}
			$HTML_WEB_PAGE.="</TABLE>";
		}
		$HTML_WEB_PAGE.="</BODY></HTML>";
		return $HTML_WEB_PAGE;
	}

	function BuscarDatosReporte1($datos)
	{
		list($dbconn) = GetDBconn();
		if($this->datos['var']['codigodepa'])
		{
			$busqueda1="JOIN profesionales_departamentos AS D ON
			(A.tipo_id_tercero=D.tipo_id_tercero
			AND A.tercero_id=D.tercero_id
			AND D.departamento='".$this->datos['var']['codigodepa']."')";
		}
		if($this->datos['var']['codigotico'])
		{
			$busqueda3="AND X.tipo_consulta_id='".$this->datos['var']['codigotico']."'";
		}
		if($this->datos['var']['documentos'])
		{
			$busqueda2="AND A.tipo_id_tercero='".$this->datos['var']['tipodocume']."'
			AND A.tercero_id='".$this->datos['var']['documentos']."'";
		}
		if($this->datos['var']['fechadesde'])
		{
			$busqueda4="AND X.fecha_turno>='".$this->datos['var']['fechadesde']."'";
		}
		if($this->datos['var']['fechahasta'])
		{
			$busqueda5="AND X.fecha_turno<='".$this->datos['var']['fechahasta']."'";
		}
		$query = "SELECT DISTINCT A.tipo_id_tercero,
		A.tercero_id,
		B.nombre_tercero,
		G.estado
		FROM profesionales AS A
		".$busqueda1.",
		terceros AS B,
		profesionales_estado AS G,
		agenda_turnos AS X
		WHERE A.tipo_id_tercero=B.tipo_id_tercero
		AND A.tercero_id=B.tercero_id
		AND A.tipo_id_tercero=G.tipo_id_tercero
		AND A.tercero_id=G.tercero_id
		AND A.tipo_id_tercero=X.tipo_id_profesional
		AND A.tercero_id=X.profesional_id
		AND X.empresa_id='".$this->datos['var']['empresa']."'
		AND X.empresa_id=G.empresa_id
		$busqueda2
		$busqueda3
		$busqueda4
		$busqueda5
		ORDER BY A.tipo_id_tercero, A.tercero_id;";
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
		return $var;
	}

	function BuscarDatosReporte2($tipo,$docu,$datos)
	{
		if($datos['codigotico']<>NULL)
		{
			$busqueda1="AND A.tipo_consulta_id='".$datos['codigotico']."'";
		}
		if($datos['fechadesde']<>NULL)
		{
			$busqueda2="AND A.fecha_turno>='".$datos['fechadesde']."'";
		}
		if($datos['fechahasta']<>NULL)
		{
			$busqueda3="AND A.fecha_turno<='".$datos['fechahasta']."'";
		}
		$busqueda4="AND A.tipo_id_profesional='".$tipo."'
		AND A.profesional_id='".$docu."'";
		list($dbconn) = GetDBconn();
		$query = "SELECT A.fecha_turno,
		A.duracion,
		A.agenda_turno_id,
		A.tipo_consulta_id,
		A.consultorio_id,
		B.descripcion,
		C.hora,
		D.tipo_id_paciente,
		D.paciente_id,
		E.primer_apellido ||' '|| E.segundo_apellido ||' '|| E.primer_nombre ||' '|| E.segundo_nombre AS nombre
		FROM agenda_turnos AS A,
		tipos_consulta AS B,
		agenda_citas AS C
		LEFT JOIN agenda_citas_asignadas AS D ON
		(C.agenda_cita_id=D.agenda_cita_id)
		LEFT JOIN pacientes AS E ON
		(D.tipo_id_paciente=E.tipo_id_paciente
		AND D.paciente_id=E.paciente_id)
		WHERE A.tipo_consulta_id=B.tipo_consulta_id
		AND A.agenda_turno_id=C.agenda_turno_id
		$busqueda1
		$busqueda2
		$busqueda3
		$busqueda4
		ORDER BY A.tipo_consulta_id,
		A.fecha_turno, C.hora;";
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
		return $var;
	}

}

?>
