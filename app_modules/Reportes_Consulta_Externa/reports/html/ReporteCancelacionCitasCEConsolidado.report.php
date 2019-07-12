<?php

/**
 * $Id: ReporteCancelacionCitasCEConsolidado.report.php,v 1.3 2009/12/11 14:50:43 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Archivo que imprime el formulario anexo1 que se entrega al FOSYGA
 */

class ReporteCancelacionCitasCEConsolidado_report
{
	function ReporteCancelacionCitasCEConsolidado_report($datos=array())
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
		$HTML_WEB_PAGE .= "<label><font size='6' face='arial'>REPORTE ESTADISTICO CONSOLIDADO DE CITAS CANCELADAS AGENDA MÉDICA</font></label>";
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
    if($this->datos['datos']['descripcion_plan'])
		{
			$HTML_WEB_PAGE.="<TR class=\"normal_10\">";
			$HTML_WEB_PAGE.=" <TD >PLAN DE AFILIACIÓN:</TD>";
			$HTML_WEB_PAGE.=" <TD >".strtoupper($this->datos['datos']['descripcion_plan'])."</TD>";
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
		$i=0;
		$datosReport=$this->BuscarDatosReporte($this->datos['datos']);
    if($datosReport){				
			foreach($datosReport as $tipo_id_prof=>$vector){				
				foreach($vector as $id_prof=>$vector1){
					foreach($vector1 as $nom_prof=>$vector2){					
						foreach($vector2 as $espe_prof=>$vector3){		
							$HTML_WEB_PAGE.="<TABLE WIDTH='100%' BORDER=1 ALIGN='CENTER' rules=\"all\">";
							$HTML_WEB_PAGE.="<TR>";
							$HTML_WEB_PAGE.="<TD WIDTH='4%'><FONT SIZE='1'>No.</FONT></TD>";
							$HTML_WEB_PAGE.="<TD WIDTH='7%'><FONT SIZE='1'>TIPO ID</FONT></TD>";
							$HTML_WEB_PAGE.="<TD WIDTH='10%'><FONT SIZE='1'>IDENTIFICACIÓN</FONT></TD>";
							$HTML_WEB_PAGE.="<TD WIDTH='45%'><FONT SIZE='1'>NOMBRE DEL PROFESIONAL</FONT></TD>";
							$HTML_WEB_PAGE.="<TD WIDTH='27%'><FONT SIZE='1'>ESPECIALIDAD</FONT></TD>";
							$HTML_WEB_PAGE.="<TD WIDTH='7%'><FONT SIZE='1'>ESTADO</FONT></TD>";
							$HTML_WEB_PAGE.="</TR>";
							$HTML_WEB_PAGE.="<TR>";
							$HTML_WEB_PAGE.="<TD><FONT SIZE='1'>$i</FONT></TD>";							
							$HTML_WEB_PAGE.="<TD><FONT SIZE='1'>$tipo_id_prof</FONT></TD>";
							$HTML_WEB_PAGE.="<TD><FONT SIZE='1'>$id_prof</FONT></TD>";
							$HTML_WEB_PAGE.="<TD><FONT SIZE='1'>$nom_prof</FONT></TD>";
							$HTML_WEB_PAGE.="<TD><FONT SIZE='1'>$espe_prof</FONT></TD>";
							foreach($vector3 as $tipo_cancel=>$vector4){
								$estado_prof=$vector4['estado'];
								break;
							}
							if($estado_prof=='0'){
								$HTML_WEB_PAGE.="<TD><FONT SIZE='1'>INACTIVO</FONT></TD>";
							}elseif($estado_prof=='1'){
								$HTML_WEB_PAGE.="<TD><FONT SIZE='1'>ACTIVO</FONT></TD>";
							}
							$HTML_WEB_PAGE.="</TR>";
							$i++;
							$HTML_WEB_PAGE.="   </TABLE>";
							$HTML_WEB_PAGE.="<TABLE WIDTH='100%' BORDER=1 ALIGN='CENTER' rules=\"all\">";
							$HTML_WEB_PAGE.="<TR>";
							$HTML_WEB_PAGE.="<TD WIDTH='60%'><FONT SIZE='1'>JUSTIFICACION INASISTENCIA</FONT></TD>";
							$HTML_WEB_PAGE.="<TD WIDTH='40%'><FONT SIZE='1'>CANTIDAD</FONT></TD>";
							$HTML_WEB_PAGE.="</TR>";
							foreach($vector3 as $tipo_cancel=>$vector4){
								$HTML_WEB_PAGE.="<TR>";
								$HTML_WEB_PAGE.="<TD><FONT SIZE='1'>".$vector4['tipojustificacion']."</FONT></TD>";
								$HTML_WEB_PAGE.="<TD><FONT SIZE='1'>".$vector4['cantidad']."</FONT></TD>";
								$HTML_WEB_PAGE.="</TR>";								
							}
							$HTML_WEB_PAGE.="   </TABLE><BR>";
						}
					}
				}
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
    
    $filtroPlan = "";
    if($_SESSION['reconecc']['plan_afiliacion'] != '-1')
      $filtroPlan = "               AND   c.plan_id = ".$_SESSION['reconecc']['plan_afiliacion']." ";

    list($dbconn) = GetDBconn();
		GLOBAL $ADODB_FETCH_MODE;					
    
		$query  = "SELECT DISTINCT a.*,
                      ter.nombre_tercero,
                      i.descripcion as especialidad,
                      h.estado,
                      b.descripcion as tipojustificacion		
              FROM	  (
                        SELECT  e.tipo_id_profesional,
                                e.profesional_id,
                                e.tipo_consulta_id,
                                a.tipo_cancelacion_id,
                                count(*) as cantidad		
                        FROM    agenda_citas_asignadas_cancelacion a,
                                tipos_cancelacion b,
                                agenda_citas_asignadas c, 
                                agenda_citas d,agenda_turnos e    			
                        WHERE   a.tipo_cancelacion_id=b.tipo_cancelacion_id 
                        AND		  a.agenda_cita_asignada_id=c.agenda_cita_asignada_id 
                        AND		  c.agenda_cita_id=d.agenda_cita_id 
                        AND     d.agenda_turno_id=e.agenda_turno_id    
                        $filtroPlan
                        $TipoConsulFiltro 
                        $ProfeFiltro 
                    		$fechaInFiltro 
                        $fechaFnFiltro 
                    		$justifiFiltro  
                        GROUP BY e.tipo_id_profesional,e.profesional_id,e.tipo_consulta_id,a.tipo_cancelacion_id) a,
		terceros ter,tipos_consulta i,profesionales_estado h,profesionales_departamentos f,departamentos dpto,
		userpermisos_repconsultaexterna rep,
		tipos_cancelacion b
		WHERE ter.tipo_id_tercero=a.tipo_id_profesional AND ter.tercero_id=a.profesional_id AND
		a.tipo_consulta_id=i.tipo_consulta_id AND 
		ter.tipo_id_tercero=h.tipo_id_tercero AND ter.tercero_id=h.tercero_id AND
		a.tipo_id_profesional=f.tipo_id_tercero AND a.profesional_id=f.tercero_id AND
    f.departamento=dpto.departamento AND h.departamento=f.departamento AND
		a.tipo_cancelacion_id=b.tipo_cancelacion_id
		AND dpto.empresa_id='".$_SESSION['recoex']['empresa']."'
		AND dpto.empresa_id=rep.empresa_id
		AND dpto.centro_utilidad=rep.centro_utilidad
		AND dpto.unidad_funcional=rep.unidad_funcional
		AND dpto.departamento=rep.departamento
		AND rep.usuario_id='".UserGetUID()."'
		
		$sql_centro
		$sql_unidad
		$sql_dpto";			
				
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
    $resulta = $dbconn->Execute($query);
    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{      
			while($datos=$resulta->FetchRow()){
				$vector[$datos['tipo_id_profesional']][$datos['profesional_id']][$datos['nombre_tercero']][$datos['especialidad']][$datos['tipo_cancelacion_id']]=$datos;				
			}			
		}
		return $vector;
	}
}
?>