<?php

/**
 * $Id: ReporteCancelacionCitasCE.report.php,v 1.1.1.1 2009/09/11 20:36:56 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Archivo que imprime el formulario anexo1 que se entrega al FOSYGA
 */

class ReporteCancelacionCitasCE_report
{
	function ReporteCancelacionCitasCE_report($datos=array())
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
		$datosReport=$this->BuscarDatosReporte($this->datos['datos']);
		$HTML_WEB_PAGE ="<HTML><BODY>";
          
          $HTML_WEB_PAGE .= "<br><br><center>";
		$HTML_WEB_PAGE .= "<label><font size='6' face='arial'>REPORTE ESTADISTICO CITAS CANCELADAS AGENDA MÉDICA</font></label>";
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
		foreach($datosReport as $identificacion=>$vector){
			$justificacionAnt=-1;
			foreach($vector as $justificacion=>$vector1){
			$i=1;
				foreach($vector1 as $identificacionPac=>$vector2){
					foreach($vector2 as $citaId=>$vectorDatos){
					if($identificacion!=$identificacionAnt){
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
						(list($tipoId,$Identificacion)=explode('-',$identificacion));
						$HTML_WEB_PAGE.="<TD><FONT SIZE='1'>$tipoId</FONT></TD>";
            $HTML_WEB_PAGE.="<TD><FONT SIZE='1'>$Identificacion</FONT></TD>";
						$HTML_WEB_PAGE.="<TD><FONT SIZE='1'>".$vectorDatos['nombre_tercero']."</FONT></TD>";
						$HTML_WEB_PAGE.="<TD><FONT SIZE='1'>".$vectorDatos['especialidad']."</FONT></TD>";
						if($vectorDatos['estado']=='0'){
						  $HTML_WEB_PAGE.="<TD><FONT SIZE='1'>INACTIVO</FONT></TD>";
						}elseif($vectorDatos['estado']=='1'){
						  $HTML_WEB_PAGE.="<TD><FONT SIZE='1'>ACTIVO</FONT></TD>";
						}
						$HTML_WEB_PAGE.="</TR>";
						$identificacionAnt=$identificacion;
						if($justificacion!=$justificacionAnt){
						  $HTML_WEB_PAGE.="<TR>";
							$HTML_WEB_PAGE.="<TD COLSPAN='3'><FONT SIZE='1'>JUSTIFICACION INASISTENCIA</FONT></TD>";
              $HTML_WEB_PAGE.="<TD COLSPAN='3'><FONT SIZE='1'>".strtoupper($vectorDatos['tipojustificacion'])."</TD>";
              $HTML_WEB_PAGE.="</TR>";
							$justificacionAnt=$justificacion;
							$HTML_WEB_PAGE.="<TR>";
							$HTML_WEB_PAGE.="<TD COLSPAN='6'>";
              $HTML_WEB_PAGE.="   <TABLE WIDTH='100%' BORDER=1 ALIGN='CENTER'>";
							$HTML_WEB_PAGE.="   <TR>";
							$HTML_WEB_PAGE.="   <TD WIDTH='5%'><FONT SIZE='1'>Nro.</FONT></TD>";
							$HTML_WEB_PAGE.="   <TD WIDTH='10%'><FONT SIZE='1'>FECHA TURNO</FONT></TD>";
							$HTML_WEB_PAGE.="   <TD WIDTH='10%'><FONT SIZE='1'>HORA</FONT></TD>";
							$HTML_WEB_PAGE.="   <TD WIDTH='10%'><FONT SIZE='1'>DURACIÓN</FONT></TD>";
							$HTML_WEB_PAGE.="   <TD WIDTH='25%'><FONT SIZE='1'>NOMBRE PACIENTE</FONT></TD>";
							$HTML_WEB_PAGE.="   <TD WIDTH='20%'><FONT SIZE='1'>IDENT. PACIENTE</FONT></TD>";
							$HTML_WEB_PAGE.="   <TD WIDTH='20%'><FONT SIZE='1'>OPORTUNIDAD DE CANCELACION</FONT></TD>";
							$HTML_WEB_PAGE.="   </TR>";
							$HTML_WEB_PAGE.="   </TABLE>";
							$HTML_WEB_PAGE.="</TD>";
							$HTML_WEB_PAGE.=" </TR>";
						}
						$HTML_WEB_PAGE.="<TR>";
						$HTML_WEB_PAGE.="<TD COLSPAN=\"6\">";
						$HTML_WEB_PAGE.="<TABLE WIDTH='100%' BORDER=0 ALIGN='CENTER'>";
            $HTML_WEB_PAGE.="<TR>";
            $HTML_WEB_PAGE.="   <TD WIDTH='5%'><FONT SIZE='1'>$i</FONT></TD>";
            $HTML_WEB_PAGE.="   <TD WIDTH='10%'><FONT SIZE='1'>".$vectorDatos['fecha_turno']."</FONT></TD>";
						$HTML_WEB_PAGE.="   <TD WIDTH='10%'><FONT SIZE='1'>".$vectorDatos['hora']."</FONT></TD>";
						$HTML_WEB_PAGE.="   <TD WIDTH='10%'><FONT SIZE='1'>".$vectorDatos['duracion']."</FONT></TD>";
						$HTML_WEB_PAGE.="   <TD WIDTH='25%'><FONT SIZE='1'>".$vectorDatos['nombre']."</FONT></TD>";
						$HTML_WEB_PAGE.="   <TD WIDTH='20%'><FONT SIZE='1'>".$vectorDatos['identificacionpac']."</FONT></TD>";
						(list($fechaCancel,$horaTotCancel)=explode(' ',$vectorDatos['fechacancelacion']));
						(list($anoCancel,$mesCancel,$diaCancel)=explode('-',$fechaCancel));
						(list($horaCancel,$minCancel)=explode(':',$horaTotCancel));
						(list($anoCita,$mesCita,$diaCita)=explode('-',$vectorDatos['fecha_turno']));
						(list($horaCita,$minCita)=explode(':',$vectorDatos['hora']));
						$dias=(((mktime($horaCita,$minCita,0,$mesCita,$diaCita,$anoCita)-mktime($horaCancel,$minCancel,0,$mesCancel,$diaCancel,$anoCancel))/60)/60)/24;
						$HTML_WEB_PAGE.="   <TD WIDTH='20%'><FONT SIZE='1'>".round($dias,1)."</FONT></TD>";
            $HTML_WEB_PAGE.=" </TR>";
            $HTML_WEB_PAGE.="</TABLE>";
            $HTML_WEB_PAGE.="</TD>";
						$HTML_WEB_PAGE.=" </TR>";
						$i++;
					}else{
						if($justificacion!=$justificacionAnt){
							$HTML_WEB_PAGE.="<TR>";
							$HTML_WEB_PAGE.="   <TD COLSPAN='3'><FONT SIZE='1'>JUSTIFICACION  INASISTENCIA</FONT></TD>";
							$HTML_WEB_PAGE.="   <TD COLSPAN='3'><FONT SIZE='1'>".strtoupper($vectorDatos['tipojustificacion'])."</FONT></TD>";
							$justificacionAnt=$justificacion;
							$HTML_WEB_PAGE.="<TR>";
							$HTML_WEB_PAGE.="<TD COLSPAN='6'>";
              $HTML_WEB_PAGE.="   <TABLE WIDTH='100%' BORDER=1 ALIGN='CENTER'>";
							$HTML_WEB_PAGE.="   <TR>";
							$HTML_WEB_PAGE.="   <TD WIDTH='5%'><FONT SIZE='1'>Nro.</FONT></TD>";
							$HTML_WEB_PAGE.="   <TD WIDTH='10%'><FONT SIZE='1'>FECHA TURNO</FONT></TD>";
							$HTML_WEB_PAGE.="   <TD WIDTH='10%'><FONT SIZE='1'>HORA</FONT></TD>";
							$HTML_WEB_PAGE.="   <TD WIDTH='10%'><FONT SIZE='1'>DURACIÓN</FONT></TD>";
							$HTML_WEB_PAGE.="   <TD WIDTH='25%'><FONT SIZE='1'>NOMBRE PACIENTE</FONT></TD>";
							$HTML_WEB_PAGE.="   <TD WIDTH='20%'><FONT SIZE='1'>IDENT. PACIENTE</FONT></TD>";
							$HTML_WEB_PAGE.="   <TD WIDTH='20%'><FONT SIZE='1'>OPORTUNIDAD DE CANCELACION</FONT></TD>";
							$HTML_WEB_PAGE.="   </TR>";
							$HTML_WEB_PAGE.="   </TABLE>";
							$HTML_WEB_PAGE.="</TD>";
							$HTML_WEB_PAGE.=" </TR>";
						}
						$HTML_WEB_PAGE.="<TR>";
						$HTML_WEB_PAGE.="<TD COLSPAN=\"6\">";
						$HTML_WEB_PAGE.="<TABLE WIDTH='100%' BORDER=0 ALIGN='CENTER'>";
            $HTML_WEB_PAGE.="<TR>";
            $HTML_WEB_PAGE.="   <TD WIDTH='5%'><FONT SIZE='1'>$i</FONT></TD>";
            $HTML_WEB_PAGE.="   <TD WIDTH='10%'><FONT SIZE='1'>".$vectorDatos['fecha_turno']."</FONT></TD>";
						$HTML_WEB_PAGE.="   <TD WIDTH='10%'><FONT SIZE='1'>".$vectorDatos['hora']."</FONT></TD>";
						$HTML_WEB_PAGE.="   <TD WIDTH='10%'><FONT SIZE='1'>".$vectorDatos['duracion']."</FONT></TD>";
						$HTML_WEB_PAGE.="   <TD WIDTH='25%'><FONT SIZE='1'>".$vectorDatos['nombre']."</FONT></TD>";
						$HTML_WEB_PAGE.="   <TD WIDTH='20%'><FONT SIZE='1'>".$vectorDatos['identificacionpac']."</FONT></TD>";
						(list($fechaCancel,$horaTotCancel)=explode(' ',$vectorDatos['fechacancelacion']));
						(list($anoCancel,$mesCancel,$diaCancel)=explode('-',$fechaCancel));
						(list($horaCancel,$minCancel)=explode(':',$horaTotCancel));
						(list($anoCita,$mesCita,$diaCita)=explode('-',$vectorDatos['fecha_turno']));
						(list($horaCita,$minCita)=explode(':',$vectorDatos['hora']));
						$dias=(((mktime($horaCita,$minCita,0,$mesCita,$diaCita,$anoCita)-mktime($horaCancel,$minCancel,0,$mesCancel,$diaCancel,$anoCancel))/60)/60)/24;
						$HTML_WEB_PAGE.="   <TD WIDTH='20%'><FONT SIZE='1'>".round($dias,1)."</FONT></TD>";
            $HTML_WEB_PAGE.=" </TR>";
            $HTML_WEB_PAGE.="</TABLE>";
            $HTML_WEB_PAGE.="</TD>";
						$HTML_WEB_PAGE.=" </TR>";
						$i++;
					}
				}	
				}
			}
			$j++;
			$HTML_WEB_PAGE.="</TABLE><br>";
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

		if(!empty($_SESSION['reconecc']['justificacionId']) && $_SESSION['reconecc']['justificacionId']!=-1){
			$justifiFiltro=" AND a.tipo_cancelacion_id='".$_SESSION['reconecc']['justificacionId']."'";
    }
    if(!empty($_SESSION['reconecc']['codigotico']) && $_SESSION['reconecc']['codigotico']!=-1){
			$TipoConsulFiltro=" AND e.tipo_consulta_id='".$_SESSION['reconecc']['codigotico']."'";
    }
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
		/*$query="SELECT DISTINCT g.tipo_id_tercero||'-'||g.tercero_id as identificacionprof,a.tipo_cancelacion_id,c.tipo_id_paciente||'-'||c.paciente_id as identificacionpac,d.agenda_cita_id,g.nombre_tercero,h.estado,i.descripcion as especialidad,
		e.fecha_turno,e.duracion,e.consultorio_id,d.hora,
    pac.primer_nombre||' '||pac.segundo_nombre||' '||pac.primer_apellido||' '||pac.segundo_apellido as nombre,b.descripcion as tipojustificacion,
		a.fecha_registro as fechacancelacion
		FROM agenda_citas_asignadas_cancelacion a,tipos_cancelacion b,
		agenda_citas_asignadas c,agenda_citas d,
		agenda_turnos e,terceros g,profesionales_estado h,tipos_consulta i,
    pacientes pac, profesionales_departamentos f,departamentos dpto 			
		WHERE a.tipo_cancelacion_id=b.tipo_cancelacion_id AND
		a.agenda_cita_asignada_id=c.agenda_cita_asignada_id AND
		c.agenda_cita_id=d.agenda_cita_id AND d.agenda_turno_id=e.agenda_turno_id AND
    g.tipo_id_tercero=e.tipo_id_profesional AND g.tercero_id=e.profesional_id AND
		g.tipo_id_tercero=h.tipo_id_tercero AND g.tercero_id=h.tercero_id AND
		e.tipo_consulta_id=i.tipo_consulta_id AND pac.paciente_id=c.paciente_id AND
		pac.tipo_id_paciente=c.tipo_id_paciente AND
    e.tipo_id_profesional=f.tipo_id_tercero AND e.profesional_id=f.tercero_id AND
    f.departamento=dpto.departamento AND h.departamento=f.departamento
    $sql_centro
    $sql_unidad
    $sql_dpto
    $TipoConsulFiltro
    $ProfeFiltro
		$fechaInFiltro
    $fechaFnFiltro
		$justifiFiltro";		
		*/
		$query="SELECT a.*
		FROM (SELECT DISTINCT g.tipo_id_tercero||'-'||g.tercero_id as identificacionprof,a.tipo_cancelacion_id,c.tipo_id_paciente||'-'||c.paciente_id as identificacionpac,d.agenda_cita_id,g.nombre_tercero,h.estado,i.descripcion as especialidad,
			e.fecha_turno,e.duracion,e.consultorio_id,d.hora,
			pac.primer_nombre||' '||pac.segundo_nombre||' '||pac.primer_apellido||' '||pac.segundo_apellido as nombre,b.descripcion as tipojustificacion,
			a.fecha_registro as fechacancelacion,dpto.empresa_id,dpto.centro_utilidad,dpto.unidad_funcional,dpto.departamento
			FROM agenda_citas_asignadas_cancelacion a,tipos_cancelacion b,
			agenda_citas_asignadas c,agenda_citas d,
			agenda_turnos e,terceros g,profesionales_estado h,tipos_consulta i,
			pacientes pac, profesionales_departamentos f,departamentos dpto
			WHERE a.tipo_cancelacion_id=b.tipo_cancelacion_id AND
			a.agenda_cita_asignada_id=c.agenda_cita_asignada_id AND
			c.agenda_cita_id=d.agenda_cita_id AND d.agenda_turno_id=e.agenda_turno_id AND
			g.tipo_id_tercero=e.tipo_id_profesional AND g.tercero_id=e.profesional_id AND
			g.tipo_id_tercero=h.tipo_id_tercero AND g.tercero_id=h.tercero_id AND
			e.tipo_consulta_id=i.tipo_consulta_id AND pac.paciente_id=c.paciente_id AND
			pac.tipo_id_paciente=c.tipo_id_paciente AND
			e.tipo_id_profesional=f.tipo_id_tercero AND e.profesional_id=f.tercero_id AND
			f.departamento=dpto.departamento AND h.departamento=f.departamento
			AND dpto.empresa_id='".$_SESSION['recoex']['empresa']."'	
			$TipoConsulFiltro
			$ProfeFiltro
			$fechaInFiltro
			$fechaFnFiltro
			$justifiFiltro          
						$sql_centro
						$sql_unidad
						$sql_dpto) as a,userpermisos_repconsultaexterna rep 	
			WHERE			
			a.empresa_id=rep.empresa_id			
			AND a.centro_utilidad=rep.centro_utilidad
			AND a.unidad_funcional=rep.unidad_funcional
			AND a.departamento=rep.departamento
			AND rep.usuario_id='".UserGetUID()."'
					";   
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      if($resulta->RecordCount()>0){
				while(!$resulta->EOF){
					$Tipo_con[$resulta->fields[0]][$resulta->fields[1]][$resulta->fields[2]][$resulta->fields[3]]=$resulta->GetRowAssoc($ToUpper = false);
					$resulta->MoveNext();
				}
			}
		}
		return $Tipo_con;
	}



}

?>
