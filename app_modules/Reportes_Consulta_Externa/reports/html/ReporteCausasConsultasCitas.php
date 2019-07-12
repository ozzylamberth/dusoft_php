<?php

/**
 * $Id: ReporteCausasConsultasCitas.php,v 1.1.1.1 2009/09/11 20:36:56 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Archivo que imprime el formulario anexo1 que se entrega al FOSYGA
 */

class ReporteCausasConsultasCitas_report
{
	function ReporteCausasConsultasCitas_report($datos=array())
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
		$HTML_WEB_PAGE .= "<label><font size='6' face='arial'>REPORTE ESTADISTICO CAUSAS DE CONSULTAS MEDICAS</font></label>";
		$HTML_WEB_PAGE .= "</center><br><br>";
         
          $HTML_WEB_PAGE.="<TABLE WIDTH='100%' BORDER=0>";
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD WIDTH='30%' ALIGN='LEFT'><FONT SIZE='1'>NOMBRE DE LA EMPRESA:</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='70%' ALIGN='LEFT'><FONT SIZE='1'>".$this->datos['datos']['razonso']."</FONT></TD>";
		$HTML_WEB_PAGE.="</TR>";
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
    $vector=$this->BuscarDatosReporte();
    if($vector){
      $HTML_WEB_PAGE.="<TABLE WIDTH='100%' BORDER=1 ALIGN='CENTER'>";
      $HTML_WEB_PAGE.="<TR>";
      $HTML_WEB_PAGE.="<TD WIDTH='15%'><FONT SIZE='1'>CODIGO</FONT></TD>";
      $HTML_WEB_PAGE.="<TD WIDTH='7%'><FONT SIZE='1'>DESCRIPCION</FONT></TD>";
      $HTML_WEB_PAGE.="<TD WIDTH='10%'><FONT SIZE='1'>CANTIDAD</FONT></TD>";
      $HTML_WEB_PAGE.="</TR>";
      for($i=0;$i<sizeof($vector);$i++){
        $HTML_WEB_PAGE.="<TR>";
        $HTML_WEB_PAGE.="<TD WIDTH='15%'><FONT SIZE='1'>".$vector[$i]['tipo_diagnostico_id']."</FONT></TD>";
        $HTML_WEB_PAGE.="<TD WIDTH='7%'><FONT SIZE='1'>".$vector[$i]['descripcion']."</FONT></TD>";
        $HTML_WEB_PAGE.="<TD WIDTH='10%'><FONT SIZE='1'>".$vector[$i]['cantidad']."</FONT></TD>";
        $HTML_WEB_PAGE.="</TR>";
      }
      $HTML_WEB_PAGE.="</TABLE><br>";
    }
		$HTML_WEB_PAGE.="</BODY></HTML>";
		return $HTML_WEB_PAGE;

	}

	function BuscarDatosReporte(){

    list($dbconn) = GetDBconn();
    $query="SELECT a.tipo_diagnostico_id,count(*) as cantidad,
    (SELECT c.diagnostico_nombre FROM diagnosticos c WHERE a.tipo_diagnostico_id=c.diagnostico_id) as descripcion
    FROM hc_diagnosticos_ingreso a,hc_evoluciones b,departamentos d
    WHERE a.evolucion_id=b.evolucion_id AND b.fecha
    BETWEEN '".$this->datos['datos']['fechadesde']."' AND '".$this->datos['datos']['fechahasta']."' AND b.departamento=d.departamento AND d.servicio='3'
    GROUP BY a.tipo_diagnostico_id";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      if($resulta->RecordCount()>0){
				while(!$resulta->EOF){
					$Tipo_con[]=$resulta->GetRowAssoc($ToUpper = false);
					$resulta->MoveNext();
				}
			}
		}
		return $Tipo_con;
	}



}

?>
