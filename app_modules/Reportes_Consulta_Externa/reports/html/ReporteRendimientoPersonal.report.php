<?php

/**
 * $Id: ReporteRendimientoPersonal.report.php,v 1.2 2009/12/11 14:50:43 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Archivo que imprime el formulario anexo1 que se entrega al FOSYGA
 */

class ReporteRendimientoPersonal_report
{
	function ReporteRendimientoPersonal_report($datos=array())
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
		$HTML_WEB_PAGE .= "<label><font size='6' face='arial'>REPORTE ESTADISTICO RENDIMIENTO PERSONAL</font></label>";
		$HTML_WEB_PAGE .= "</center><br><br>";

		$HTML_WEB_PAGE.="<TABLE WIDTH='100%' BORDER=0>";
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD WIDTH='30%' ALIGN='CENTER'><FONT SIZE='1'>".$this->datos['empresa']."</FONT></TD>";
		$HTML_WEB_PAGE.="</TR>";
    $HTML_WEB_PAGE.="</TABLE><BR>";
    $HTML_WEB_PAGE.="<TABLE WIDTH='100%' BORDER=0>";
    if($this->datos['centroutilidad'])
		{
			$HTML_WEB_PAGE.="<TR>";
			$HTML_WEB_PAGE.="<TD WIDTH='30%' ALIGN='LEFT'><FONT SIZE='1'>CENTRO UTILIDAD:</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='70%' ALIGN='LEFT'><FONT SIZE='1'>".$this->datos['centroutilidad']."</FONT></TD>";
			$HTML_WEB_PAGE.="</TR>";
		}
    if($this->datos['unidadfunc'])
		{
			$HTML_WEB_PAGE.="<TR>";
			$HTML_WEB_PAGE.="<TD WIDTH='30%' ALIGN='LEFT'><FONT SIZE='1'>UNIDAD FUNCIONAL:</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='70%' ALIGN='LEFT'><FONT SIZE='1'>".$this->datos['unidadfunc']."</FONT></TD>";
			$HTML_WEB_PAGE.="</TR>";
		}
		if($this->datos['departamento'])
		{
			$HTML_WEB_PAGE.="<TR>";
			$HTML_WEB_PAGE.="<TD WIDTH='30%' ALIGN='LEFT'><FONT SIZE='1'>DEPARTAMENTO:</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='70%' ALIGN='LEFT'><FONT SIZE='1'>".$this->datos['departamento']."</FONT></TD>";
			$HTML_WEB_PAGE.="</TR>";
		}
    if($this->datos['usuario_escojer']!=-1){
      $HTML_WEB_PAGE.="<TR>";
			$HTML_WEB_PAGE.="<TD WIDTH='30%' ALIGN='LEFT'><FONT SIZE='1'>NOMBRE DEL USUARIO:</FONT></TD>";
      $vector=explode(',',$this->datos['usuario_escojer']);
			$HTML_WEB_PAGE.="<TD WIDTH='70%' ALIGN='LEFT'><FONT SIZE='1'>".$vector[1]."</FONT></TD>";
			$HTML_WEB_PAGE.="</TR>";
		}    
    if($this->datos['descripcion_plan'])
    {
      $HTML_WEB_PAGE.=" <TR class=\"normal_10\">";
			$HTML_WEB_PAGE.="   <TD>PLAN DE AFILIACIÓN:</TD>";
			$HTML_WEB_PAGE.="   <TD>".$this->datos['descripcion_plan']."</TD>";
			$HTML_WEB_PAGE.=" </TR>";
		}
		if($this->datos['feinictra'])
		{
			$HTML_WEB_PAGE.="<TR>";
			$HTML_WEB_PAGE.="<TD WIDTH='30%' ALIGN='LEFT'><FONT SIZE='1'>FECHA INICIAL:</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='70%' ALIGN='LEFT'><FONT SIZE='1'>".$this->datos['feinictra']."</FONT></TD>";
			$HTML_WEB_PAGE.="</TR>";
		}
		if($this->datos['fefinctra'])
		{
			$HTML_WEB_PAGE.="<TR>";
			$HTML_WEB_PAGE.="<TD WIDTH='30%' ALIGN='LEFT'><FONT SIZE='1'>FECHA FINAL:</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='70%' ALIGN='LEFT'><FONT SIZE='1'>".$this->datos['fefinctra']."</FONT></TD>";
			$HTML_WEB_PAGE.="</TR>";
		}
		$HTML_WEB_PAGE.="</TABLE><br>";
    $registros=$this->ConsultaEstadisticaRendimientoPersonal($this->datos['centroU'],$this->datos['unidadF'],$this->datos['DptoSel'],$this->datos['usuario_escojer'],$this->datos['feinictra'],$this->datos['fefinctra'],$this->datos['plan_afiliacion']);
    if($registros)
    {
      $HTML_WEB_PAGE.="<TABLE WIDTH='100%' BORDER=1 ALIGN='CENTER' rules=\"all\" class=\"normal_10\">\n";
      $HTML_WEB_PAGE.="<TR class=\"label\" align=\"center\">";
      $HTML_WEB_PAGE.="<TD >USUARIO</TD>";
      $HTML_WEB_PAGE.="<TD >CITAS ASIGNADAS</FONT></TD>";
      $HTML_WEB_PAGE.="<TD >CITAS CUMPLIDAS</FONT></TD>";
      $HTML_WEB_PAGE.="<TD >CITAS CANCELADAS</FONT></TD>";
      $HTML_WEB_PAGE.="<TD >PROMEDIO</FONT></TD>";
      $HTML_WEB_PAGE.="</TR>";
      $TotalDias=0;
      $valorAsignadasTotal=0;
      foreach($registros as $usuario_id=>$Datos)
      {
        $asignadasCancel=$this->CitasAsignadasCanceladasRendimientoPersonal($usuario_id,$this->datos['centroU'],$this->datos['unidadF'],$this->datos['DptoSel'],$this->datos['usuario_escojer'],$this->datos['feinictra'],$this->datos['fefinctra'],$this->datos['plan_afiliacion']);
        $HTML_WEB_PAGE.="<TR>";
        $HTML_WEB_PAGE.="<TD>".$Datos['nombre']."</TD>";
        $HTML_WEB_PAGE.="<TD>".$asignadasCancel['asignadas']."</TD>";
        $HTML_WEB_PAGE.="<TD>".$asignadasCancel['cumplimiento']."</TD>";
        $HTML_WEB_PAGE.="<TD>".$asignadasCancel['canceladas']."</TD>";
        $HTML_WEB_PAGE.="<TD>".round((($valorAsignadas=$asignadasCancel['asignadas'] - $asignadasCancel['canceladas'])/$asignadasCancel['cantidaddias']),2)."</TD>";
        $HTML_WEB_PAGE.="</TR>";
        $TotalDias+=$asignadasCancel['cantidaddias'];
        $valorAsignadasTotal+=$valorAsignadas;
			}
      $HTML_WEB_PAGE.="<TR>";
      $HTML_WEB_PAGE.="<TD COLSPAN='4'><FONT SIZE='1'>TOTAL</FONT></TD>";
      $HTML_WEB_PAGE.="<TD><FONT SIZE='1'>".round(($valorAsignadasTotal/$TotalDias),2)."</FONT></TD>";
      $HTML_WEB_PAGE.="</TR>";
      $HTML_WEB_PAGE.="</TABLE><br>";
		}
		$HTML_WEB_PAGE.="</BODY></HTML>";
		return $HTML_WEB_PAGE;
	}


  function ConsultaEstadisticaRendimientoPersonal($centroU,$unidadF,$DptoSel,$usuario_escojer,$feinictra,$fefinctra,$plan_afiliacion)
  {

    GLOBAL $ADODB_FETCH_MODE;
    list($dbconn) = GetDBconn();
    if(!empty($centroU)){
      $sql_centro = " AND dpto.centro_utilidad = '".$centroU."'";
    }
    if(!empty($unidadF)){
      $sql_unidad = " AND dpto.unidad_funcional = '".$unidadF."'";
    }
    if(!empty($DptoSel)){
      $sql_dpto = " AND dpto.departamento = '".$DptoSel."'";
    }
    if($usuario_escojer != '-1'){
      $usuario_id = explode(',',$usuario_escojer);
      if(!empty($usuario_id[0]))
      {
        $sql_usuario  = " AND usut.usuario_id = ".$usuario_id[0]."";
        $sql_usuarioI = " AND     CU.usuario_id  = ".$usuario_id[0]."";
      }
    }
    if(!empty($feinictra) AND !empty($fefinctra)){
      (list($diaIn,$mesIn,$anoIn)=explode('/',$feinictra));
      (list($diaFn,$mesFn,$anoFn)=explode('/',$fefinctra));
      if(!empty($feinictra) AND !empty($fefinctra)){
        $sql_fecha = " AND date(c.fecha_registro) BETWEEN '".$anoIn."-".$mesIn."-".$diaIn."' AND '".$anoFn."-".$mesFn."-".$diaFn."'";
      }
    }
    
      $fplan = "";
      if($plan_afiliacion != '-1' && $plan_afiliacion)
        $fplan = "  AND      c.plan_id = ".$plan_afiliacion." ";
    
    $query="SELECT DISTINCT d.usuario_id,
                    d.nombre
            FROM    agenda_turnos a,
                    agenda_citas b,
                    agenda_citas_asignadas c,
                    system_usuarios d,
                    tipos_consulta x,
                    departamentos dpto,
                    userpermisos_tipos_consulta  usut,
                    userpermisos_repconsultaexterna rep
            WHERE   a.agenda_turno_id=b.agenda_turno_id 
            AND     b.agenda_cita_id=c.agenda_cita_id 
            AND     c.agenda_cita_id=c.agenda_cita_id_padre 
            AND     c.usuario_id=d.usuario_id 
            AND     a.tipo_consulta_id=x.tipo_consulta_id 
            AND     x.departamento=dpto.departamento 
            AND     d.usuario_id=usut.usuario_id 
            AND     usut.tipo_consulta_id=x.tipo_consulta_id
        		AND     dpto.empresa_id='".$_SESSION['recoex']['empresa']."'
            AND     dpto.empresa_id=rep.empresa_id
            AND     dpto.centro_utilidad=rep.centro_utilidad
            AND     dpto.unidad_funcional=rep.unidad_funcional
            AND     dpto.departamento=rep.departamento
            AND     rep.usuario_id='".UserGetUID()."'
            $sql_centro 
            $sql_unidad 
            $sql_dpto 
            $sql_usuario 
            $sql_fecha
            ".$fplan."
            UNION DISTINCT 
            SELECT DISTINCT d.usuario_id,
                    d.nombre
            FROM    agenda_turnos a,
                    agenda_citas b,
                    agenda_citas_asignadas c,
                    os_cruce_citas e,
                    os_maestro f,
                    cuentas CU,
                    system_usuarios d,
                    tipos_consulta x,
                    departamentos dpto,
                    userpermisos_repconsultaexterna rep
            WHERE   a.agenda_turno_id=b.agenda_turno_id 
            AND     b.agenda_cita_id=c.agenda_cita_id 
            AND     c.agenda_cita_id=c.agenda_cita_id_padre 
            AND     a.tipo_consulta_id=x.tipo_consulta_id 
            AND     x.departamento=dpto.departamento
            AND     dpto.empresa_id='".$_SESSION['recoex']['empresa']."'
            AND     dpto.empresa_id=rep.empresa_id
            AND     dpto.centro_utilidad=rep.centro_utilidad
            AND     dpto.unidad_funcional=rep.unidad_funcional
            AND     dpto.departamento=rep.departamento
            AND     rep.usuario_id='".UserGetUID()."'
            AND     c.agenda_cita_asignada_id=e.agenda_cita_asignada_id 
            AND     e.numero_orden_id=f.numero_orden_id 
            AND     CU.numerodecuenta = f.numerodecuenta
            AND     d.usuario_id= CU.usuario_id 
            $sql_centro 
            $sql_unidad 
            $sql_dpto 
            $sql_usuarioI 
            $sql_fecha 
            ".$fplan." ";
    
    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
    $result = $dbconn->Execute($query);
    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
    if($dbconn->ErrorNo() != 0){
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
    }else{
      while($data = $result->FetchRow()){
        $vars[$data['usuario_id']] = $data;
      }
    }
    $result->Close();
    return $vars;
  }

    function CitasAsignadasCanceladasRendimientoPersonal($usuario,$centroU,$unidadF,$DptoSel,$usuario_escojer,$feinictra,$fefinctra,$plan_afiliacion)
    {
      list($dbconn) = GetDBconn();
      //$dbconn->debug = true;
      if(!empty($centroU)){
        $sql_centro = " AND y.centro_utilidad = '".$centroU."'";
      }
      if(!empty($unidadF)){
        $sql_unidad = " AND y.unidad_funcional = '".$unidadF."'";
      }
      if(!empty($DptoSel)){
        $sql_dpto = " AND y.departamento = '".$DptoSel."'";
      }
      if($usuario_escojer != '-1'){
        $usuario_id = explode(',',$usuario_escojer);
        if(!empty($usuario_id[0])){
          $sql_usuario = " AND usut.usuario_id = ".$usuario_id[0]."";
        }
      }
      if(!empty($feinictra) AND !empty($fefinctra)){
        (list($diaIn,$mesIn,$anoIn)=explode('/',$feinictra));
        (list($diaFn,$mesFn,$anoFn)=explode('/',$fefinctra));
        if(!empty($feinictra) AND !empty($fefinctra)){
          $sql_fecha = " AND date(c.fecha_registro) BETWEEN '".$anoIn."-".$mesIn."-".$diaIn."' AND '".$anoFn."-".$mesFn."-".$diaFn."'";
        }
      }
      $fplan = "";
      if($plan_afiliacion != '-1' && $plan_afiliacion)
        $fplan = "  AND      c.plan_id = ".$plan_afiliacion." ";
      
    
      $sql  = "SELECT  count(*) as asignadas ";
      $sql .= "FROM    agenda_turnos a,";
      $sql .= "        agenda_citas b,";
      $sql .= "        agenda_citas_asignadas c,";
      $sql .= "        tipos_consulta x,";
      $sql .= "        departamentos y ";
      $sql .= "WHERE   c.usuario_id='".$usuario."' ";
      $sql .= "AND     a.agenda_turno_id=b.agenda_turno_id ";
      $sql .= "AND     b.agenda_cita_id=c.agenda_cita_id ";
      $sql .= "AND     c.agenda_cita_id=c.agenda_cita_id_padre ";
      $sql .= "AND     a.tipo_consulta_id=x.tipo_consulta_id ";
      $sql .= "AND     x.departamento=y.departamento ";
      $sql .= "AND     y.empresa_id='".$_SESSION['recoex']['empresa']."' ";
  		$sql .= $sql_centro." ";
      $sql .= $sql_unidad." "; 
      $sql .= $sql_dpto." ";
      $sql .= $sql_fecha." ";
      $sql .= $fplan;
      
      $result = $dbconn->Execute($sql);
      if($dbconn->ErrorNo() != 0)
      {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
      
      $vars['asignadas'] = $result->fields[0];
    
      $query = "SELECT  count(*) as cumplidas
                FROM    agenda_turnos a,
                        agenda_citas b,
                        agenda_citas_asignadas c,
                        os_cruce_citas as e,
                        os_maestro f,
                        cuentas CU,
                        tipos_consulta x,
                        departamentos y 
                WHERE   a.agenda_turno_id=b.agenda_turno_id 
                AND     b.agenda_cita_id=c.agenda_cita_id 
                AND     c.agenda_cita_id=c.agenda_cita_id_padre 
                AND     c.agenda_cita_asignada_id=e.agenda_cita_asignada_id 
                AND     e.numero_orden_id=f.numero_orden_id 
                AND     a.tipo_consulta_id=x.tipo_consulta_id 
                AND     x.departamento=y.departamento
            		AND     y.empresa_id='".$_SESSION['recoex']['empresa']."'
                AND     CU.numerodecuenta = f.numerodecuenta
                AND     CU.usuario_id = ".$usuario."
  		
      $sql_centro $sql_unidad $sql_dpto $sql_fecha ".$fplan."";
      $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() != 0){
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
      }
      
      $vars['cumplimiento'] = $result->fields[0];
    
      $query="SELECT count(*) as canceladas
              FROM  agenda_turnos a,
                    agenda_citas b,
                    agenda_citas_asignadas c,
                    system_usuarios usu,
                    tipos_consulta x,
                    departamentos y,
                    agenda_citas_asignadas_cancelacion AC 
              WHERE AC.usuario_id='".$usuario."' 
              AND   a.agenda_turno_id=b.agenda_turno_id 
              AND   b.agenda_cita_id=c.agenda_cita_id 
              AND   c.agenda_cita_id=c.agenda_cita_id_padre 
              AND   c.usuario_id=usu.usuario_id
              AND   c.agenda_cita_asignada_id = AC.agenda_cita_asignada_id
              AND   a.tipo_consulta_id=x.tipo_consulta_id 
              AND   x.departamento=y.departamento
          		AND   y.empresa_id='".$_SESSION['recoex']['empresa']."'
  		
      $sql_centro $sql_unidad $sql_dpto $sql_fecha ".$fplan."";
      $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() != 0)
      {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
      
      $vars['canceladas'] = $result->fields[0];
    
      $query = "SELECT count(*) as cantidaddias
                FROM  (
                        SELECT  DISTINCT c.fecha_registro::date
                        FROM    agenda_turnos a,
                                agenda_citas b,
                                agenda_citas_asignadas c,
                                system_usuarios d,
                                userpermisos_tipos_consulta  usut,
                                tipos_consulta x,
                                departamentos y 
                        WHERE   d.usuario_id='".$usuario."' 
                        AND     a.agenda_turno_id=b.agenda_turno_id 
                        AND     b.agenda_cita_id=c.agenda_cita_id 
                        AND     c.agenda_cita_id=c.agenda_cita_id_padre 
                        AND     c.usuario_id=d.usuario_id 
                        AND     d.usuario_id=usut.usuario_id 
                        AND     usut.tipo_consulta_id=x.tipo_consulta_id 
                        AND     a.tipo_consulta_id=x.tipo_consulta_id 
                        AND     x.departamento=y.departamento
                        $sql_centro 
                        $sql_unidad 
                        $sql_dpto 
                        $sql_usuario 
                        $sql_fecha
                        ".$fplan."
                      ) x ";
      
      $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() != 0)
      {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
      $vars['cantidaddias'] = $result->fields[0];
    
      $result->Close();
      return $vars;
    }
  }
?>