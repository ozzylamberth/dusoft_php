<?php

/**
 * $Id: ReporteEstadisticasTiposCitas.report.php,v 1.1.1.1 2009/09/11 20:36:56 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Archivo que imprime el formulario anexo1 que se entrega al FOSYGA
 */

class ReporteEstadisticasTiposCitas_report
{
	function ReporteEstadisticasTiposCitas_report($datos=array())
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
          
          
          $_REQUEST['centroU']=$this->datos[variables][centroU];
          $_REQUEST['unidadF']=$this->datos[variables][unidadF];
          $_REQUEST['DptoSel']=$this->datos[variables][DptoSel];
          $_REQUEST['centroutilidad'] = $this->datos[variables][centroutilidad];
          $_REQUEST['unidadfunc'] = $this->datos[variables][unidadfunc];
          $_REQUEST['departamento'] = $this->datos[variables][departamento];
          $_REQUEST['profesional_escojer'] = $this->datos[variables][profesional_escojer];
          $_REQUEST['tipocita'] = $this->datos[variables][tipocita];
          $_REQUEST['feinictra'] = $this->datos[variables][feinictra];
          $_REQUEST['fefinctra'] = $this->datos[variables][fefinctra];
          $vector=$this->BusquedaReporteEstadisticasCausasTipo();
          $Total_consulta = $vector[0];
          $total_tipo_cita = $vector[1];          
          $Finalidad = $vector[2];
          $Origen = $vector[3];
          
          
		$HTML_WEB_PAGE ="<HTML><BODY>";
          
          $HTML_WEB_PAGE .= "<br><br><center>";
		$HTML_WEB_PAGE .= "<label><font size='6' face='arial'>REPORTE ESTADISTICO DE CAUSAS Y TIPOS DE CONSULTA</font></label>";
		$HTML_WEB_PAGE .= "</center><br><br>";
		
          if(!empty($_REQUEST['centroutilidad']) OR !empty($_REQUEST['unidadfunc']) OR !empty($_REQUEST['departamento']) OR ($_REQUEST['profesional_escojer']!='-1') OR ($_REQUEST['tipocita']!='-1') OR !empty($_REQUEST['feinictra']) OR !empty($_REQUEST['fefinctra']))
          {
               $HTML_WEB_PAGE .= "<table border=\"0\" width=\"80%\" align=\"center\">";
               $HTML_WEB_PAGE .= "<tr>";
               $HTML_WEB_PAGE .= "<td align=\"center\" colspan=\"2\"><b><FONT SIZE='1'>DATOS DE LA BUSQUEDA</FONT></b></td>";
               $HTML_WEB_PAGE .= "</tr>";
               $HTML_WEB_PAGE .= "<tr>";
               $HTML_WEB_PAGE .= "<td width=\"40%\" align=\"left\"><FONT SIZE='1'>CENTRO DE UTILIDAD</FONT></td>";
               if(!empty($_REQUEST['centroutilidad']))
               { $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><FONT SIZE='1'>".$_REQUEST['centroutilidad']."</FONT></td>"; }
               else
               { $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><FONT SIZE='1'>SIN DATOS</FONT></td>"; }
               $HTML_WEB_PAGE .= "</tr>";
               
               $HTML_WEB_PAGE .= "<tr>";
               $HTML_WEB_PAGE .= "<td width=\"40%\" align=\"left\"><FONT SIZE='1'>UNIDAD FUNCIONAL</FONT></td>";
               if(!empty($_REQUEST['unidadfunc']))
               { $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><FONT SIZE='1'>".$_REQUEST['unidadfunc']."</FONT></td>"; }
               else
               { $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><FONT SIZE='1'>SIN DATOS</FONT></td>"; }
               $HTML_WEB_PAGE .= "</tr>";
               
               $HTML_WEB_PAGE .= "<tr>";
               $HTML_WEB_PAGE .= "<td width=\"40%\" align=\"left\"><FONT SIZE='1'>DEPARTAMENTO</FONT></td>";
               if(!empty($_REQUEST['departamento']))
               { $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><FONT SIZE='1'>".$_REQUEST['departamento']."</FONT></td>"; }
               else
               { $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><FONT SIZE='1'>SIN DATOS</FONT></td>"; }
               $HTML_WEB_PAGE .= "</tr>";
                    
               $usuario_id = explode(',',$_REQUEST['profesional_escojer']);
     
               $HTML_WEB_PAGE .= "<tr>";
               $HTML_WEB_PAGE .= "<td width=\"40%\" align=\"left\"><FONT SIZE='1'>PROFESIONAL</FONT></td>";
               if(!empty($usuario_id[1]))
               { $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><FONT SIZE='1'>".$usuario_id[1]."</FONT></td>"; }
               else
               { $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><FONT SIZE='1'>SIN DATOS</FONT></td>"; }
               $HTML_WEB_PAGE .= "</tr>";
                    
               $tipo_cita = explode(',',$_REQUEST['tipocita']);
     
               $HTML_WEB_PAGE .= "<tr>";
               $HTML_WEB_PAGE .= "<td width=\"40%\" align=\"left\"><FONT SIZE='1'>TIPO DE CITA</FONT></td>";
               if(!empty($tipo_cita[1]))
               { $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><FONT SIZE='1'>".$tipo_cita[1]."</FONT></td>"; }
               else
               { $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><FONT SIZE='1'>SIN DATOS</FONT></td>"; }
               $HTML_WEB_PAGE .= "</tr>";
               
               $HTML_WEB_PAGE .= "<tr>";
               $HTML_WEB_PAGE .= "<td width=\"40%\" align=\"left\"><FONT SIZE='1'>FECHA INICIAL</FONT></td>";
               if(!empty($_REQUEST['feinictra']))
               { $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><FONT SIZE='1'>".$_REQUEST['feinictra']."</FONT></td>"; }
               else
               { $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><FONT SIZE='1'>SIN DATOS</FONT></td>"; }
               $HTML_WEB_PAGE .= "</tr>";
     
               $HTML_WEB_PAGE .= "<tr>";
               $HTML_WEB_PAGE .= "<td width=\"40%\" align=\"left\"><FONT SIZE='1'>FECHA FINAL</FONT></td>";
               if(!empty($_REQUEST['fefinctra']))
               { $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><FONT SIZE='1'>".$_REQUEST['fefinctra']."</FONT></td>"; }
               else
               { $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><FONT SIZE='1'>SIN DATOS</FONT></td>"; }
               $HTML_WEB_PAGE .= "</tr>";
     
               $HTML_WEB_PAGE .= "</table><br>";
          }
          
		$HTML_WEB_PAGE .= "<table border=\"0\" width=\"80%\" align=\"center\">";
		$HTML_WEB_PAGE .= "<tr>";
		$HTML_WEB_PAGE .= "<td align=\"center\" colspan=\"2\"><FONT SIZE='1'><b>ESTADISTICAS</b></FONT></td>";
          $HTML_WEB_PAGE .= "</tr>";
          		
          $HTML_WEB_PAGE .= "<tr>";
          $HTML_WEB_PAGE .= "<td width=\"80%\" align=\"left\"><FONT SIZE='1'>TOTAL DE LAS CONSULTAS : </FONT></td>";
          $HTML_WEB_PAGE .= "<td align=\"center\" width=\"20%\"><FONT SIZE='1'><b>".$Total_consulta[total_consulta]."</b></FONT></td>";
          $HTML_WEB_PAGE .= "</tr>";
		
          $HTML_WEB_PAGE .= "<tr>";
		$HTML_WEB_PAGE .= "<td align=\"center\" colspan=\"2\">&nbsp;</td>";
          $HTML_WEB_PAGE .= "</tr>";
          if(!empty($total_tipo_cita))
          {
               $HTML_WEB_PAGE .= "<tr>";
               $HTML_WEB_PAGE .= "<td align=\"center\" colspan=\"2\"><FONT SIZE='1'><b>ESTADISTICAS DE TIPOS DE CONSULTA</b></FONT></td>";
               $HTML_WEB_PAGE .= "</tr>";
               
               for($j=0;$j<sizeof($total_tipo_cita); $j++)
               {
                    $HTML_WEB_PAGE .= "<tr>";
                    $HTML_WEB_PAGE .= "<td width=\"80%\" align=\"left\"><FONT SIZE='1'>CONSULTAS DE: ".$total_tipo_cita[$j][tipos_de_citas]."</FONT></td>";
                    $HTML_WEB_PAGE .= "<td align=\"center\" width=\"20%\"><FONT SIZE='1'><b>".$total_tipo_cita[$j][total_tipo_cita]."</b></FONT></td>";
                    $HTML_WEB_PAGE .= "</tr>";
               }
               
               $HTML_WEB_PAGE .= "<tr>";
               $HTML_WEB_PAGE .= "<td align=\"center\" colspan=\"2\">&nbsp;</td>";
               $HTML_WEB_PAGE .= "</tr>";
          }
          
          if(!empty($Finalidad))
          {
               $HTML_WEB_PAGE .= "<tr>";
               $HTML_WEB_PAGE .= "<td align=\"center\" colspan=\"2\"><FONT SIZE='1'><b>ESTADISTICAS DE CONSULTAS POR FINALIDAD</b></FONT></td>";
               $HTML_WEB_PAGE .= "</tr>";
               
               $HTML_WEB_PAGE .= "<tr>";
               $HTML_WEB_PAGE .= "<td width=\"60%\" align=\"center\"><FONT SIZE='1'><b>FINALIDAD</b></FONT></td>";
               $HTML_WEB_PAGE .= "<td align=\"center\" width=\"40%\"><FONT SIZE='1'><b>CONSULTAS</b></FONT></td>";
               $HTML_WEB_PAGE.= "</tr>";
     
               for($a=0;$a<sizeof($Finalidad); $a++)
               {
                    $HTML_WEB_PAGE .= "<tr>";
                    $HTML_WEB_PAGE .= "<td width=\"80%\" align=\"left\"><FONT SIZE='1'>".$Finalidad[$a][detalle]."</FONT></td>";
                    $HTML_WEB_PAGE .= "<td align=\"center\" width=\"20%\"><FONT SIZE='1'><b>".$Finalidad[$a][total_citas_finalidad]."</b></FONT></td>";
                    $HTML_WEB_PAGE .= "</tr>";
               }
     
               $HTML_WEB_PAGE .= "<tr>";
               $HTML_WEB_PAGE .= "<td align=\"center\" colspan=\"2\">&nbsp;</td>";
               $HTML_WEB_PAGE .= "</tr>";
          }
          
          if(!empty($Origen))
          {
               $HTML_WEB_PAGE .= "<tr>";
               $HTML_WEB_PAGE .= "<td align=\"center\" colspan=\"2\"><FONT SIZE='1'><b>ESTADISTICAS DE CONSULTAS POR ORIGEN DE ATENCION</b></FONT></td>";
               $HTML_WEB_PAGE .= "</tr>";
               
               $HTML_WEB_PAGE .= "<tr>";
               $HTML_WEB_PAGE .= "<td width=\"60%\" align=\"center\"><FONT SIZE='1'><b>ORIGEN ATENCION</b></FONT></td>";
               $HTML_WEB_PAGE .= "<td align=\"center\" width=\"40%\"><FONT SIZE='1'><b>CONSULTAS</b></FONT></td>";
               $HTML_WEB_PAGE.= "</tr>";
     
               for($x=0;$x<sizeof($Origen); $x++)
               {
                    $HTML_WEB_PAGE .= "<tr>";
                    $HTML_WEB_PAGE .= "<td width=\"80%\" align=\"left\"><FONT SIZE='1'>".$Origen[$x][detalle]."</FONT></td>";
                    $HTML_WEB_PAGE .= "<td align=\"center\" width=\"20%\"><FONT SIZE='1'><b>".$Origen[$x][total_citas_origen]."</b></FONT></td>";
                    $HTML_WEB_PAGE .= "</tr>";
               }
          }
        
		$HTML_WEB_PAGE .= "</table>";
		return $HTML_WEB_PAGE;
	}
  
  function BusquedaReporteEstadisticasCausasTipo()
     {    
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          
          $centro_utilidad = $_REQUEST['centroU'];
          if (!empty($centro_utilidad))
          { $sql_centro = "AND dpto.centro_utilidad = '$centro_utilidad'"; }

          $unidad_funcional = $_REQUEST['unidadF'];
          if (!empty($unidad_funcional))
          { $sql_unidad = "AND dpto.unidad_funcional = '$unidad_funcional'"; }
          
          $departamento = $_REQUEST['DptoSel'];
          if (!empty($departamento))
          { $sql_dpto = "AND dpto.departamento = '$departamento'"; }
          
          if($_REQUEST['profesional_escojer'] != '-1')     
          {
               $usuario_id = explode(',',$_REQUEST['profesional_escojer']);
               if(!empty($usuario_id[0]))
               { $sql_usuario = "AND B.usuario_id = ".$usuario_id[0].""; }
          }
          
          if (!empty($_REQUEST['feinictra']) AND !empty($_REQUEST['fefinctra']))
          {
               $feinictra = $this->FechaStamp($_REQUEST['feinictra']);
               $fefinctra = $this->FechaStamp($_REQUEST['fefinctra']);
               
               if(!empty($feinictra) AND !empty($fefinctra))
               { $sql_fecha = "AND date(B.fecha) BETWEEN '".$feinictra."' AND '".$fefinctra."'";}
          }
          
          if ($_REQUEST['tipocita'] != '-1')
          {
               $tipo_cita = explode(',',$_REQUEST['tipocita']);
               if(!empty($tipo_cita[0]))
               { $sql_tipocita = "AND E.tipo_cita = '".$tipo_cita[0]."'";}
          }
          
          /*if(empty($_REQUEST['feinictra']) OR empty($_REQUEST['fefinctra'])){
               $this->frmError["feinictra"]=1;
               $this->frmError["MensajeError"]="DEBE LLENAR LA FECHA INICIAL Y LA FECHA FINAL.";
               $this->FormaReporteEstadisticasCausasTipo();
               return true;
          }*/

          
          $queryT ="SELECT count(*) as total_consulta
                    FROM  departamentos dpto, userpermisos_repconsultaexterna rep,  hc_evoluciones B, os_maestro C,
                      os_cruce_citas D, agenda_citas_asignadas E
                    WHERE
                         dpto.empresa_id = '".$_SESSION['recoex']['empresa']."'
                         $sql_centro
                         $sql_unidad
                         $sql_dpto
                         AND dpto.departamento = B.departamento
                         
                        AND dpto.empresa_id=rep.empresa_id
                        AND dpto.centro_utilidad=rep.centro_utilidad
                        AND dpto.unidad_funcional=rep.unidad_funcional
                        AND dpto.departamento=rep.departamento
                        AND rep.usuario_id='".UserGetUID()."'
                         
                         AND B.estado = '0'
                         $sql_usuario
                         $sql_fecha
                         AND B.numerodecuenta = C.numerodecuenta
                         AND C.numero_orden_id = D.numero_orden_id
                         AND D.agenda_cita_asignada_id = E.agenda_cita_asignada_id
                         $sql_tipocita;";
                         
               
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
    $resulta = $dbconn->Execute($queryT);
    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                         
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }
               
          $Total_consulta = $resulta->FetchRow();
          
               
          $queryTi ="SELECT count(*) as total_tipo_cita, F.descripcion as tipos_de_citas
                     FROM  departamentos dpto, userpermisos_repconsultaexterna rep, hc_evoluciones B, os_maestro C, 
                         os_cruce_citas D, agenda_citas_asignadas E, tipos_cita F
                     WHERE
                         dpto.empresa_id = '".$_SESSION['recoex']['empresa']."'
                         
                        AND dpto.empresa_id=rep.empresa_id
                        AND dpto.centro_utilidad=rep.centro_utilidad
                        AND dpto.unidad_funcional=rep.unidad_funcional
                        AND dpto.departamento=rep.departamento
                        AND rep.usuario_id='".UserGetUID()."'
                         
                         $sql_centro
                         $sql_unidad
                         $sql_dpto
                         AND dpto.departamento = B.departamento
                         AND B.estado = '0'
                         $sql_usuario
                         $sql_fecha
                         AND B.numerodecuenta = C.numerodecuenta
                         AND C.numero_orden_id = D.numero_orden_id
                         AND D.agenda_cita_asignada_id = E.agenda_cita_asignada_id
                         $sql_tipocita
                         AND E.tipo_cita = F.tipo_cita
                         GROUP BY tipos_de_citas
                         ORDER BY total_tipo_cita DESC;";
          
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
    $resulta = $dbconn->Execute($queryTi);
    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          
          if($dbconn->ErrorNo() != 0){
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          else
          {
               while ($data = $resulta->FetchRow())
               {
                    $total_tipo_cita[] = $data;
               }
          }
          
          $queryFi ="SELECT count(*) as total_citas_finalidad, V.detalle, V.tipo_finalidad_id
                    FROM  departamentos dpto, userpermisos_repconsultaexterna rep ,hc_evoluciones B, os_maestro C, 
                       os_cruce_citas D, agenda_citas_asignadas E,
                          hc_finalidad J LEFT JOIN hc_tipos_finalidad V ON (J.tipo_finalidad_id = V.tipo_finalidad_id)
                    WHERE
                         dpto.empresa_id = '".$_SESSION['recoex']['empresa']."'
                         $sql_centro
                         $sql_unidad
                         $sql_dpto
                         AND dpto.departamento = B.departamento
                         
                        AND dpto.empresa_id=rep.empresa_id
                        AND dpto.centro_utilidad=rep.centro_utilidad
                        AND dpto.unidad_funcional=rep.unidad_funcional
                        AND dpto.departamento=rep.departamento
                        AND rep.usuario_id='".UserGetUID()."'
                         
                         AND B.estado = '0'
                         $sql_usuario
                         $sql_fecha
                         AND B.evolucion_id = J.evolucion_id
                         AND B.numerodecuenta = C.numerodecuenta
                         AND C.numero_orden_id = D.numero_orden_id
                         AND D.agenda_cita_asignada_id = E.agenda_cita_asignada_id
                         $sql_tipocita
                         GROUP BY V.detalle, V.tipo_finalidad_id
          ORDER BY total_citas_finalidad DESC;";

          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
    $resulta = $dbconn->Execute($queryFi);
    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          
          if($dbconn->ErrorNo() != 0){
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
       while ($data = $resulta->FetchRow())
          {
               $total_consulta_Finalidad[] = $data;
          }
          
          $queryOr ="SELECT count(*) as total_citas_origen, V.detalle, V.tipo_atencion_id
                    FROM  departamentos dpto, userpermisos_repconsultaexterna rep , hc_evoluciones B, os_maestro C, 
                       os_cruce_citas D, agenda_citas_asignadas E,
                          hc_atencion J LEFT JOIN hc_tipos_atencion V ON (J.tipo_atencion_id = V.tipo_atencion_id)
                    WHERE
                         dpto.empresa_id = '".$_SESSION['recoex']['empresa']."'
                         $sql_centro
                         $sql_unidad
                         $sql_dpto
                         AND dpto.departamento = B.departamento
                         AND B.estado = '0'
                         
                        AND dpto.empresa_id=rep.empresa_id
                        AND dpto.centro_utilidad=rep.centro_utilidad
                        AND dpto.unidad_funcional=rep.unidad_funcional
                        AND dpto.departamento=rep.departamento
                        AND rep.usuario_id='".UserGetUID()."'
                         
                         $sql_usuario
                         $sql_fecha
                         AND B.evolucion_id = J.evolucion_id
                         AND B.numerodecuenta = C.numerodecuenta
                         AND C.numero_orden_id = D.numero_orden_id
                         AND D.agenda_cita_asignada_id = E.agenda_cita_asignada_id
                         $sql_tipocita
                         GROUP BY V.detalle, V.tipo_atencion_id
          ORDER BY total_citas_origen DESC;";

          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
    $resulta = $dbconn->Execute($queryOr);
    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          
          if($dbconn->ErrorNo() != 0){
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
       while ($data = $resulta->FetchRow())
          {
               $total_consulta_Origen[] = $data;
          }          
             

          $vector[0]=$Total_consulta;
          $vector[1]=$total_tipo_cita;
          $vector[2]=$total_consulta_Finalidad;
          $vector[3]=$total_consulta_Origen;
          return $vector;
    }
    
        function FechaStamp($fecha)
  {
      $fecha = explode ('/',$fecha);
          $fecha = $fecha[2]."-".$fecha[1]."-".$fecha[0];
          return $fecha;
          
  }
     

}

?>
