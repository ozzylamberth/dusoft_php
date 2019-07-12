<?php

/**
 * $Id: ReporteCancelacionCitasCEConsolidadoEntidad.report.php,v 1.2 2009/12/11 14:50:43 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Archivo que imprime el formulario anexo1 que se entrega al FOSYGA
 */

class ReporteCancelacionCitasCEConsolidadoEntidad_report
{
	function ReporteCancelacionCitasCEConsolidadoEntidad_report($datos=array())
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
		$HTML_WEB_PAGE .= "<label><font size='6' face='arial'>REPORTE ESTADISTICO CONSOLIDADO DE CITAS MÉDICAS CANCELADAS EN LA ENTIDAD</font></label>";
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
    
		if($this->datos['datos']['justificacionId'])
		{
			$HTML_WEB_PAGE.="<TR>";
			$HTML_WEB_PAGE.="<TD WIDTH='30%' ALIGN='LEFT'><FONT SIZE='1'>JUSTIFICACION:</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='70%' ALIGN='LEFT'><FONT SIZE='1'>".strtoupper($this->datos['datos']['justificacion'])."</FONT></TD>";
			$HTML_WEB_PAGE.="</TR>";
		}		
    
    if($this->datos['datos']['descripcion_plan'])
		{
			$HTML_WEB_PAGE.="<TR>";
			$HTML_WEB_PAGE.="<TD>PLAN DE AFILIACIÓN:</TD>";
			$HTML_WEB_PAGE.="<TD>".strtoupper($this->datos['datos']['descripcion_plan'])."</TD>";
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
			$HTML_WEB_PAGE.="<TABLE WIDTH='100%' BORDER=1 ALIGN='CENTER' rules=\"all\">";
			$HTML_WEB_PAGE.="<TR class=\"label\">";
			$HTML_WEB_PAGE.="<TD WIDTH='60%'>JUSTIFICACION INASISTENCIA</TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='40%'>CANTIDAD</TD>";
			$HTML_WEB_PAGE.="</TR>";
			foreach($datosReport as $tipo_cancel=>$vector4){
				$HTML_WEB_PAGE.="<TR>";
				$HTML_WEB_PAGE.="<TD><FONT SIZE='1'>".$vector4['tipojustificacion']."</FONT></TD>";
				$HTML_WEB_PAGE.="<TD><FONT SIZE='1'>".$vector4['cantidad']."</FONT></TD>";
				$HTML_WEB_PAGE.="</TR>";								
			}
			$HTML_WEB_PAGE.="   </TABLE><BR>";											
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
    
    if($_SESSION['reconecc']['plan_afiliacion'] != '-1' && $_SESSION['reconecc']['plan_afiliacion'])
      $fplan = "  AND     c.plan_id = ".$_SESSION['reconecc']['plan_afiliacion']." ";
    
    list($dbconn) = GetDBconn();
		GLOBAL $ADODB_FETCH_MODE;					
    $query = "SELECT  a.*,
                      b.descripcion as tipojustificacion
              FROM    (
                        SELECT  a.tipo_cancelacion_id,
                                count(*) as cantidad
                        FROM    agenda_citas_asignadas_cancelacion a,
                        				agenda_citas_asignadas c,
                                agenda_citas d,
                                agenda_turnos e,
                        				tipos_consulta x, 
                                departamentos dpto, 
                                userpermisos_repconsultaexterna rep
                				WHERE 	a.agenda_cita_asignada_id=c.agenda_cita_asignada_id 
                        AND			c.agenda_cita_id=d.agenda_cita_id 
                        AND     d.agenda_turno_id=e.agenda_turno_id				
                				$TipoConsulFiltro
                				$ProfeFiltro
                				$fechaInFiltro
                				$fechaFnFiltro
                				$justifiFiltro
                        $fplan
                				AND e.tipo_consulta_id=x.tipo_consulta_id    	    
                				AND x.departamento=dpto.departamento				  
                				AND dpto.empresa_id='".$_SESSION['recoex']['empresa']."'						
                				$sql_centro
                				$sql_unidad
                				$sql_dpto
                        $fplan
                				AND dpto.empresa_id='".$_SESSION['recoex']['empresa']."'
                				AND dpto.empresa_id=rep.empresa_id
                				AND dpto.centro_utilidad=rep.centro_utilidad
                				AND dpto.unidad_funcional=rep.unidad_funcional
                				AND dpto.departamento=rep.departamento
                				AND rep.usuario_id='".UserGetUID()."'
                				GROUP BY a.tipo_cancelacion_id
                      ) as a,tipos_cancelacion b   			
              WHERE   a.tipo_cancelacion_id=b.tipo_cancelacion_id	";
              
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
    $resulta = $dbconn->Execute($query);
    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{      
			while($datos=$resulta->FetchRow()){
				$vector[$datos['tipo_cancelacion_id']]=$datos;				
			}			
		}
		return $vector;
	}
}
?>