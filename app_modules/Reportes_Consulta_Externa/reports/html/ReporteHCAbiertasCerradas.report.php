<?php

/**
 * $Id: ReporteHCAbiertasCerradas.report.php,v 1.1.1.1 2009/09/11 20:36:56 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Archivo que imprime el formulario anexo1 que se entrega al FOSYGA
 */

class ReporteHCAbiertasCerradas_report
{
	function ReporteHCAbiertasCerradas_report($datos=array())
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
		
		$HTML_WEB_PAGE ="<HTML><BODY>";
          
    $HTML_WEB_PAGE .= "<br><br><center>";
		$HTML_WEB_PAGE .= "<label><font size='6' face='arial'>REPORTE ESTADISTICO HISTORIAS CLINICAS ABIERTAS Y CERRADAS</font></label>";
		$HTML_WEB_PAGE .= "</center><br><br>";

		$HTML_WEB_PAGE.="<TABLE WIDTH='100%' BORDER=0>";
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD WIDTH='30%' ALIGN='LEFT'><FONT SIZE='1'>NOMBRE DE LA EMPRESA:</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='70%' ALIGN='LEFT'><FONT SIZE='1'>".$this->datos['datos']['razonso']."</FONT></TD>";
		$HTML_WEB_PAGE.="</TR>";
		if($this->datos['variables']['centroU'])
		{
			$HTML_WEB_PAGE.="<TR>";
			$HTML_WEB_PAGE.="<TD WIDTH='30%' ALIGN='LEFT'><FONT SIZE='1'>CENTRO UTILIDAD:</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='70%' ALIGN='LEFT'><FONT SIZE='1'>".$this->datos['variables']['centroutilidad']."</FONT></TD>";
			$HTML_WEB_PAGE.="</TR>";
		}
		if($this->datos['variables']['unidadF'])
		{
			$HTML_WEB_PAGE.="<TR>";
			$HTML_WEB_PAGE.="<TD WIDTH='30%' ALIGN='LEFT'><FONT SIZE='1'>UNIDAD FUNCIONAL:</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='70%' ALIGN='LEFT'><FONT SIZE='1'>".$this->datos['variables']['unidadfunc']."</FONT></TD>";
			$HTML_WEB_PAGE.="</TR>";
		}
		if($this->datos['variables']['DptoSel'])
		{
			$HTML_WEB_PAGE.="<TR>";
			$HTML_WEB_PAGE.="<TD WIDTH='30%' ALIGN='LEFT'><FONT SIZE='1'>DEPARTAMENTO:</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='70%' ALIGN='LEFT'><FONT SIZE='1'>".$this->datos['variables']['departamento']."</FONT></TD>";
			$HTML_WEB_PAGE.="</TR>";
		}
          if($this->datos['datos']['documentos'])
		{
			$HTML_WEB_PAGE.="<TR>";
			$HTML_WEB_PAGE.="<TD WIDTH='30%' ALIGN='LEFT'><FONT SIZE='1'>NOMBRE DEL PROFESIONAL:</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='70%' ALIGN='LEFT'><FONT SIZE='1'>".$this->datos['datos']['nombreprof']."</FONT></TD>";
			$HTML_WEB_PAGE.="</TR>";
		}
		if($this->datos['datos']['justificacionId'])
		{
			$HTML_WEB_PAGE.="<TR>";
			$HTML_WEB_PAGE.="<TD WIDTH='30%' ALIGN='LEFT'><FONT SIZE='1'>JUSTIFICACION:</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='70%' ALIGN='LEFT'><FONT SIZE='1'>".strtoupper($this->datos['datos']['justificacion'])."</FONT></TD>";
			$HTML_WEB_PAGE.="</TR>";
		}
		if($this->datos['datos']['fechadesde'])
		{
			$HTML_WEB_PAGE.="<TR>";
			$HTML_WEB_PAGE.="<TD WIDTH='30%' ALIGN='LEFT'><FONT SIZE='1'>FECHA FINAL:</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='70%' ALIGN='LEFT'><FONT SIZE='1'>".$this->datos['datos']['fechadesde']."</FONT></TD>";
			$HTML_WEB_PAGE.="</TR>";
		}
		if($this->datos['datos']['fechahasta'])
		{
			$HTML_WEB_PAGE.="<TR>";
			$HTML_WEB_PAGE.="<TD WIDTH='30%' ALIGN='LEFT'><FONT SIZE='1'>FECHA FINAL:</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='70%' ALIGN='LEFT'><FONT SIZE='1'>".$this->datos['datos']['fechahasta']."</FONT></TD>";
			$HTML_WEB_PAGE.="</TR>";
		}
		$HTML_WEB_PAGE.="</TABLE><br>";
		$j=0;
		$datosReport=$this->BuscarDatosReporte($this->datos['datos']);
    if($datosReport){				
			for($i=0;$i<sizeof($datosReport);$i++){	
				$HTML_WEB_PAGE.="<TABLE WIDTH='100%' BORDER=1 ALIGN='CENTER'>";
				$HTML_WEB_PAGE.="<TR>";
				$HTML_WEB_PAGE.="<TD WIDTH='4%'><FONT SIZE='1'>No.</FONT></TD>";
				$HTML_WEB_PAGE.="<TD WIDTH='7%'><FONT SIZE='1'>TIPO ID</FONT></TD>";
				$HTML_WEB_PAGE.="<TD WIDTH='10%'><FONT SIZE='1'>IDENTIFICACIÓN</FONT></TD>";
				$HTML_WEB_PAGE.="<TD WIDTH='45%'><FONT SIZE='1'>NOMBRE DEL PROFESIONAL</FONT></TD>";
				$HTML_WEB_PAGE.="<TD WIDTH='27%'><FONT SIZE='1'>ESPECIALIDAD</FONT></TD>";
				$HTML_WEB_PAGE.="<TD WIDTH='7%'><FONT SIZE='1'>ESTADO</FONT></TD>";
				$HTML_WEB_PAGE.="</TR>";
				$HTML_WEB_PAGE.="<TR>";
				$HTML_WEB_PAGE.="<TD><FONT SIZE='1'>$j</FONT></TD>";	
				(list($tipoId,$Identificacion)=explode('-',$datosReport[$i]['identificacionprof']));						
				$HTML_WEB_PAGE.="<TD><FONT SIZE='1'>$tipoId</FONT></TD>";
				$HTML_WEB_PAGE.="<TD><FONT SIZE='1'>$Identificacion</FONT></TD>";
				$HTML_WEB_PAGE.="<TD><FONT SIZE='1'>".$datosReport[$i]['nombre_tercero']."</FONT></TD>";
				$HTML_WEB_PAGE.="<TD><FONT SIZE='1'>".$datosReport[$i]['especialidad']."</FONT></TD>";				
				if($datosReport[$i]['estado']=='0'){
					$HTML_WEB_PAGE.="<TD><FONT SIZE='1'>INACTIVO</FONT></TD>";
				}elseif($datosReport[$i]['estado']=='1'){
					$HTML_WEB_PAGE.="<TD><FONT SIZE='1'>ACTIVO</FONT></TD>";
				}
				$HTML_WEB_PAGE.="</TR>";
				$j++;
				$HTML_WEB_PAGE.="   </TABLE>";
				$HTML_WEB_PAGE.="<TABLE WIDTH='100%' BORDER=1 ALIGN='CENTER'>";
				$HTML_WEB_PAGE.="<TR>";
				$HTML_WEB_PAGE.="<TD WIDTH='50%'><FONT SIZE='1'>HISTORIAS CLINICAS ABIERTAS</FONT></TD>";
				$HTML_WEB_PAGE.="<TD WIDTH='50%'><FONT SIZE='1'>HISTORIAS CLINICAS CERRADAS</FONT></TD>";
				$HTML_WEB_PAGE.="</TR>";							
				$HTML_WEB_PAGE.="<TR>";
				$HTML_WEB_PAGE.="<TD ALIGN='CENTER'><FONT SIZE='1'>".$datosReport[$i]['hc_abiertass']."</FONT></TD>";
				$HTML_WEB_PAGE.="<TD ALIGN='CENTER'><FONT SIZE='1'>".$datosReport[$i]['hc_cerradass']."</FONT></TD>";
				$HTML_WEB_PAGE.="</TR>";												
				$HTML_WEB_PAGE.="   </TABLE><BR>";						
			}						
		}
		$HTML_WEB_PAGE.="</BODY></HTML>";
		return $HTML_WEB_PAGE;
	}

	function BuscarDatosReporte($datosReporte){
    if(!empty($this->datos['variables']['centroU'])){
        $sql_centro = " AND dpto.centro_utilidad = '".$this->datos['variables']['centroU']."'";
    }
    if(!empty($this->datos['variables']['unidadF'])){
        $sql_unidad = " AND dpto.unidad_funcional = '".$this->datos['variables']['unidadF']."'";
    }
    if(!empty($this->datos['variables']['DptoSel'])){
        $sql_dpto = " AND dpto.departamento = '".$this->datos['variables']['DptoSel']."'";
    }

		/*if(!empty($_SESSION['reconecc']['justificacionId']) && $_SESSION['reconecc']['justificacionId']!=-1){
			$justifiFiltro=" AND a.tipo_cancelacion_id='".$_SESSION['reconecc']['justificacionId']."'";
    }
    /*if(!empty($_SESSION['reconecc']['codigotico']) && $_SESSION['reconecc']['codigotico']!=-1){
			$TipoConsulFiltro=" AND e.tipo_consulta_id='".$_SESSION['reconecc']['codigotico']."'";
    }*/
    if(!empty($_SESSION['reconecc']['tipodocume']) && $_SESSION['reconecc']['tipodocume']!=-1 &&
		!empty($_SESSION['reconecc']['documentos']) && $_SESSION['reconecc']['documentos']!=-1){
			$ProfeFiltro=" AND e.tipo_id_profesional='".$_SESSION['reconecc']['tipodocume']."' AND e.profesional_id='".$_SESSION['reconecc']['documentos']."'";
    }
		if($_SESSION['reconecc']['fechadesde']<>NULL){
		  $fechaInFiltro="AND e.fecha_turno>='".$_SESSION['reconecc']['fechadesde']."'";
		}
		if($_SESSION['reconecc']['fechahasta']<>NULL){
		  $fechaFnFiltro="AND e.fecha_turno<='".$_SESSION['reconecc']['fechahasta']."'";
		}
    list($dbconn) = GetDBconn();
				
    
		/*$query="SELECT a.identificacionprof,g.nombre_tercero,h.estado,i.descripcion as especialidad,
		a.hc_abiertas,a.hc_cerradas
		
		FROM (SELECT a.identificacionprof,a.tipo_consulta_id,sum(a.hc_abiertas) as hc_abiertas,sum(a.hc_cerradas) as hc_cerradas
		FROM (SELECT e.tipo_id_profesional||'-'||e.profesional_id as identificacionprof,e.tipo_consulta_id,		
							(SELECT count(*)
							FROM agenda_citas x,agenda_citas_asignadas y,os_cruce_citas z,os_maestro os,hc_evoluciones hc
							WHERE x.agenda_turno_id=e.agenda_turno_id AND 
							x.agenda_cita_id=y.agenda_cita_id AND y.agenda_cita_asignada_id=z.agenda_cita_asignada_id AND
							z.numero_orden_id=os.numero_orden_id AND os.numerodecuenta=hc.numerodecuenta AND
							hc.estado='1'  
							) as hc_abiertas,
							(SELECT count(*)
							FROM agenda_citas x,agenda_citas_asignadas y,os_cruce_citas z,os_maestro os,hc_evoluciones hc
							WHERE x.agenda_turno_id=e.agenda_turno_id AND 
							x.agenda_cita_id=y.agenda_cita_id AND y.agenda_cita_asignada_id=z.agenda_cita_asignada_id AND
							z.numero_orden_id=os.numero_orden_id AND os.numerodecuenta=hc.numerodecuenta AND
							hc.estado='0'  
							) as hc_cerradas	
		FROM agenda_turnos e		
		WHERE e.sw_estado_cancelacion='0'
		$ProfeFiltro
		$fechaInFiltro
    $fechaFnFiltro		       		
		) as a
		WHERE a.hc_abiertas > 0 OR a.hc_cerradas > 0
		GROUP BY a.identificacionprof,a.tipo_consulta_id) as a,
		terceros g,profesionales_estado h,tipos_consulta i,
		profesionales_departamentos f,departamentos dpto
		WHERE a.identificacionprof=g.tipo_id_tercero||'-'||g.tercero_id AND 
		a.identificacionprof=h.tipo_id_tercero||'-'||h.tercero_id AND
		a.tipo_consulta_id=i.tipo_consulta_id AND 
		a.identificacionprof=f.tipo_id_tercero||'-'||f.tercero_id AND
    f.departamento=dpto.departamento AND h.departamento=f.departamento
		$sql_centro
		$sql_unidad
		$sql_dpto";	
		*/
		$query="		
			SELECT prof.tipo_id_profesional||'-'||prof.profesional_id as identificacionprof,
			prof.nombre_tercero,hc_abiertas.cantidad as hc_abiertass,hc_cerradas.cantidad as hc_cerradass,
			prof.estado,prof.especialidad 
			FROM
				(SELECT e.tipo_id_profesional,e.profesional_id,g.nombre_tercero,h.estado,i.descripcion as especialidad
				
				FROM agenda_turnos e,agenda_citas x,agenda_citas_asignadas y,os_cruce_citas z,os_maestro os,hc_evoluciones hc,
				terceros g,profesionales_estado h,tipos_consulta i,profesionales_departamentos f,departamentos dpto
				
				
				WHERE e.agenda_turno_id=x.agenda_turno_id AND x.agenda_cita_id=y.agenda_cita_id AND 
				y.agenda_cita_asignada_id=z.agenda_cita_asignada_id AND
				z.numero_orden_id=os.numero_orden_id AND os.numerodecuenta=hc.numerodecuenta AND
				e.tipo_id_profesional=g.tipo_id_tercero AND e.profesional_id=g.tercero_id AND 
				g.tipo_id_tercero=h.tipo_id_tercero AND g.tercero_id=h.tercero_id AND
				e.tipo_consulta_id=i.tipo_consulta_id AND
				h.tipo_id_tercero=f.tipo_id_tercero AND h.tercero_id=f.tercero_id AND
				f.departamento=dpto.departamento AND h.departamento=f.departamento
				AND dpto.empresa_id='".$_SESSION['recoex']['empresa']."'		
				$ProfeFiltro
				$fechaInFiltro
				$fechaFnFiltro
				
				$sql_centro
				$sql_unidad
				$sql_dpto 
				GROUP BY e.tipo_id_profesional,e.profesional_id,g.nombre_tercero,h.estado,i.descripcion
				
				) as prof
			 	LEFT JOIN  
				(SELECT e.tipo_id_profesional,e.profesional_id,count(*) as cantidad
				
				FROM agenda_turnos e,agenda_citas x,agenda_citas_asignadas y,os_cruce_citas z,os_maestro os,hc_evoluciones hc,
				terceros g,profesionales_estado h,tipos_consulta i,profesionales_departamentos f,departamentos dpto
				
				WHERE e.agenda_turno_id=x.agenda_turno_id AND x.agenda_cita_id=y.agenda_cita_id AND 
				y.agenda_cita_asignada_id=z.agenda_cita_asignada_id AND
				z.numero_orden_id=os.numero_orden_id AND os.numerodecuenta=hc.numerodecuenta AND
				hc.estado='1' AND e.tipo_id_profesional=g.tipo_id_tercero AND e.profesional_id=g.tercero_id AND 
				g.tipo_id_tercero=h.tipo_id_tercero AND g.tercero_id=h.tercero_id AND
				e.tipo_consulta_id=i.tipo_consulta_id AND 
				h.tipo_id_tercero=f.tipo_id_tercero AND h.tercero_id=f.tercero_id AND
				f.departamento=dpto.departamento AND h.departamento=f.departamento
				AND dpto.empresa_id='".$_SESSION['recoex']['empresa']."'		
				$ProfeFiltro
				$fechaInFiltro
				$fechaFnFiltro
				
				$sql_centro
				$sql_unidad
				$sql_dpto 
				GROUP BY e.tipo_id_profesional,e.profesional_id
				
				) as hc_abiertas ON (prof.tipo_id_profesional=hc_abiertas.tipo_id_profesional AND prof.profesional_id=hc_abiertas.profesional_id)
				LEFT JOIN
				(SELECT e.tipo_id_profesional,e.profesional_id,count(*) as cantidad
				
				FROM agenda_turnos e,agenda_citas x,agenda_citas_asignadas y,os_cruce_citas z,os_maestro os,hc_evoluciones hc,
				terceros g,profesionales_estado h,tipos_consulta i,profesionales_departamentos f,departamentos dpto
				
				WHERE e.agenda_turno_id=x.agenda_turno_id AND x.agenda_cita_id=y.agenda_cita_id AND 
				y.agenda_cita_asignada_id=z.agenda_cita_asignada_id AND
				z.numero_orden_id=os.numero_orden_id AND os.numerodecuenta=hc.numerodecuenta AND
				hc.estado='0' AND e.tipo_id_profesional=g.tipo_id_tercero AND e.profesional_id=g.tercero_id AND 
				g.tipo_id_tercero=h.tipo_id_tercero AND g.tercero_id=h.tercero_id AND
				e.tipo_consulta_id=i.tipo_consulta_id AND 
				h.tipo_id_tercero=f.tipo_id_tercero AND h.tercero_id=f.tercero_id AND
				f.departamento=dpto.departamento AND h.departamento=f.departamento
				AND dpto.empresa_id='".$_SESSION['recoex']['empresa']."'		
				$ProfeFiltro
				$fechaInFiltro
				$fechaFnFiltro
				
				$sql_centro
				$sql_unidad
				$sql_dpto 
				GROUP BY e.tipo_id_profesional,e.profesional_id
				
				) as hc_cerradas ON (prof.tipo_id_profesional=hc_cerradas.tipo_id_profesional AND prof.profesional_id=hc_cerradas.profesional_id)
			WHERE (hc_abiertas.cantidad > 0 OR hc_cerradas.cantidad > 0)";	
		
    $resulta = $dbconn->Execute($query);    
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{      
			if($resulta->RecordCount()>0){
				while(!$resulta->EOF){
					$vector[]=$resulta->GetRowAssoc($ToUpper = false);
					$resulta->MoveNext();
				}
			}			
		}
		return $vector;
	}



}

?>
