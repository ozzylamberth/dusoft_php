<?php

/**
 * $Id: ReporteRendimientoProfesionales.report.php,v 1.1.1.1 2009/09/11 20:36:56 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Archivo que imprime el formulario anexo1 que se entrega al FOSYGA
 */

class ReporteRendimientoProfesionales_report
{
	function ReporteRendimientoProfesionales_report($datos=array())
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
		$HTML_WEB_PAGE .= "<label><font size='6' face='arial'>REPORTE ESTADISTICO RENDIMIENTO PROFESIONAL</font></label>";
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
    if($this->datos['profesional_escojer']!=-1)
		{
      $vector=explode(',',$this->datos['profesional_escojer']);
			$HTML_WEB_PAGE.="<TR>";
			$HTML_WEB_PAGE.="<TD WIDTH='30%' ALIGN='LEFT'><FONT SIZE='1'>NOMBRE DEL PROFESIONAL:</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='70%' ALIGN='LEFT'><FONT SIZE='1'>".$vector[1]."</FONT></TD>";
			$HTML_WEB_PAGE.="</TR>";
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
    $registros=$this->ConsultaEstadisticaRendimientoProf($this->datos['centroU'],$this->datos['unidadF'],$this->datos['DptoSel'],$this->datos['profesional_escojer'],$this->datos['feinictra'],$this->datos['fefinctra']);
    if($registros){
      $HTML_WEB_PAGE.="<TABLE WIDTH='100%' BORDER=1 ALIGN='CENTER'>";
      $HTML_WEB_PAGE.="<TR>";
      $HTML_WEB_PAGE.="<TD><FONT SIZE='1'>PROFESIONALES</FONT></TD>";
      $HTML_WEB_PAGE.="<TD WIDTH='10%'><FONT SIZE='1'>ASIGNADAS</FONT></TD>";
      $HTML_WEB_PAGE.="<TD WIDTH='10%'><FONT SIZE='1'>CANCELADAS</FONT></TD>";
      $HTML_WEB_PAGE.="<TD WIDTH='10%'><FONT SIZE='1'>ATENDIDAS</FONT></TD>";
      $HTML_WEB_PAGE.="<TD WIDTH='10%'><FONT SIZE='1'>HC ABIERTAS</FONT></TD>";
			$HTML_WEB_PAGE .="<td width=\"10%\"><FONT SIZE='1'>DIAS</FONT></td>";
      $HTML_WEB_PAGE.="<TD WIDTH='15%'><FONT SIZE='1'>PROMEDIO DE ATENCION (HH:mm)</FONT></TD>";
      $HTML_WEB_PAGE.="<TD WIDTH='15%'><FONT SIZE='1'>PROMEDIO CONSULTAS POR DIA</FONT></TD>";
      $HTML_WEB_PAGE.="</TR>";
			//------------nuevo dar
			for($i=0; $i<sizeof($registros); $i++)
			{
					if($i % 2){ $estilo='modulo_list_oscuro';}else{$estilo='modulo_list_claro';}
					$HTML_WEB_PAGE .= "      <tr class=\"$estilo\">";
					$HTML_WEB_PAGE .= "      <td><FONT SIZE='1'>".$registros[$i]['nombre']."</FONT></td>";
					$HTML_WEB_PAGE .= "      <td><FONT SIZE='1'>".$registros[$i]['asignadas']."</FONT></td>";
					$HTML_WEB_PAGE .= "      <td><FONT SIZE='1'>".$registros[$i]['canceladas']."</FONT></td>";
					$HTML_WEB_PAGE .= "      <td><FONT SIZE='1'>".$registros[$i]['atendidas']."</FONT></td>";
					$HTML_WEB_PAGE .= "      <td><FONT SIZE='1'>".$registros[$i]['abiertas']."</FONT></td>";	
					if($registros[$i]['promedio'])
					{
							$diasConsulta=$this->DiasLaboradosProfesional($this->datos['feinictra'],$this->datos['fefinctra'],$registros[$i]['usuario']);
							$HTML_WEB_PAGE .= "      <td><FONT SIZE='1'>".$diasConsulta."</FONT></td>";
							(list($duracion,$minutos)=explode(':',$registros[$i]['promedio']));
							$HTML_WEB_PAGE .= "      <td><FONT SIZE='1'>".$duracion.":".$minutos."</FONT></td>";					
							$HTML_WEB_PAGE .= "      <td><FONT SIZE='1'>".round($registros[$i]['atendidas']/$diasConsulta,1)."</FONT></td>";
					}
					else
					{
							$HTML_WEB_PAGE .= "      <td>&nbsp;</td>";
							$HTML_WEB_PAGE .= "      <td>&nbsp;</td>";
							$HTML_WEB_PAGE .= "      <td>&nbsp;</td>";
					}						
			}			
			//------------fin nuevo dar						
      /*(list($diaIn,$mesIn,$anoIn)=explode('/',$this->datos['feinictra']));
      (list($diaFn,$mesFn,$anoFn)=explode('/',$this->datos['fefinctra']));
      $diasConsulta=(int)((((mktime(0,0,0,$mesFn,$diaFn,$anoFn)-mktime(0,0,0,$mesIn,$diaIn,$anoIn))/60)/60)/24);
      foreach($registros as $indice=>$datosVec){
        $HTML_WEB_PAGE.="<TR>";
        $HTML_WEB_PAGE.="<TD><FONT SIZE='1'>".$datosVec['nombre_tercero']."</FONT></TD>";
        $HTML_WEB_PAGE.="<TD><FONT SIZE='1'>".$datosVec['total_asignadas']."</FONT></TD>";
        $HTML_WEB_PAGE.="<TD><FONT SIZE='1'>".$datosVec['total_canceladas']."</FONT></TD>";
        $HTML_WEB_PAGE.="<TD><FONT SIZE='1'>".$datosVec['total_atendidas']."</FONT></TD>";
        $HTML_WEB_PAGE.="<TD><FONT SIZE='1'>".$datosVec['total_abiertas']."</FONT></TD>";
        if($datosVec['promedio']){
          (list($duracion,$minutos)=explode(':',$datosVec['promedio']));
          $HTML_WEB_PAGE.="<TD><FONT SIZE='1'>".$duracion.":".$minutos."</FONT></TD>";
          $HTML_WEB_PAGE.="<TD><FONT SIZE='1'>".round($datosVec['total_atendidas']/$diasConsulta,1)."</FONT></TD>";
        }else{
          $HTML_WEB_PAGE.="<TD><FONT SIZE='1'>&nbsp;</FONT></TD>";
          $HTML_WEB_PAGE.="<TD><FONT SIZE='1'>&nbsp;</FONT></TD>";
        }
        $HTML_WEB_PAGE.="</TR>";
      }*/
      $HTML_WEB_PAGE.="</TABLE><br>";
    }
		$HTML_WEB_PAGE.="</BODY></HTML>";
		return $HTML_WEB_PAGE;
	}

//-------------------NUEVO DAR---------------
  function ConsultaEstadisticaRendimientoProf($centroU,$unidadF,$DptoSel,$profesional_escojer,$feinictra,$fefinctra)
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
		if($profesional_escojer != '-1'){
			$usuario_id = explode(',',$profesional_escojer);
			if(!empty($usuario_id[0])){
				$sql_usuario = " AND y.usuario_id = ".$usuario_id[0]."";
			}
		}
    if(!empty($feinictra) AND !empty($fefinctra)){
      (list($diaIn,$mesIn,$anoIn)=explode('/',$feinictra));
      (list($diaFn,$mesFn,$anoFn)=explode('/',$fefinctra));
      if(!empty($feinictra) AND !empty($fefinctra)){
        $sql_fecha = " AND a.fecha_turno BETWEEN '".$anoIn."-".$mesIn."-".$diaIn."' AND '".$anoFn."-".$mesFn."-".$diaFn."'";
      }
    }	
		//CAMBIO DAR
		$query = "SELECT a.tipo_id_profesional,a.profesional_id,count(c.agenda_cita_asignada_id) as asignadas, count(f.agenda_cita_asignada_id) as canceladas 
              FROM agenda_turnos a,agenda_citas b,
              agenda_citas_asignadas c 
              LEFT JOIN agenda_citas_asignadas_cancelacion as f 
              ON(c.agenda_cita_asignada_id=f.agenda_cita_asignada_id),
              tipos_consulta z,
              departamentos dpto, userpermisos_repconsultaexterna rep , profesionales_usuarios as y           
              WHERE a.agenda_turno_id=b.agenda_turno_id 
              $sql_fecha
              AND (b.sw_estado='1' OR b.sw_estado='2') 
              AND b.agenda_cita_id=c.agenda_cita_id           
              AND a.tipo_id_profesional=y.tipo_tercero_id 
              AND a.profesional_id=y.tercero_id
              $sql_usuario    
              AND a.tipo_consulta_id=z.tipo_consulta_id
              AND z.departamento=dpto.departamento
              $sql_centro $sql_unidad $sql_dpto
              AND dpto.empresa_id='".$_SESSION['recoex']['empresa']."'
              AND dpto.empresa_id=rep.empresa_id
              AND dpto.centro_utilidad=rep.centro_utilidad
              AND dpto.unidad_funcional=rep.unidad_funcional
              AND dpto.departamento=rep.departamento
              AND rep.usuario_id='".UserGetUID()."'
              
              
              GROUP BY a.tipo_id_profesional,a.profesional_id
              ORDER BY a.tipo_id_profesional,a.profesional_id";
              
         
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			if(!$result->EOF)
			{
					while(!$result->EOF)
					{
							//------trae el nombre del tercero
							$sql = "SELECT a.nombre_tercero, b.usuario_id FROM terceros as a, profesionales_usuarios as b
											WHERE a.tipo_id_tercero='".$result->fields[0]."' AND a.tercero_id='".$result->fields[1]."'
											and a.tipo_id_tercero=b.tipo_tercero_id and a.tercero_id=b.tercero_id";
							$resul = $dbconn->Execute($sql);
							if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Cargar el Modulo";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
							}	
							$nombre=$resul->fields[0];
							$usuario=$resul->fields[1];
							$resul->Close();					
							//busca atendidas 0 evolucion cerras
							$atendidas = $this->CitasAtendidasoAbiertas(0,$result->fields[0],$result->fields[1],$sql_fecha,$sql_centro,$sql_unidad,$sql_dpto);
							//busca abiertas 1 evolucion abiertas
							$abiertas = $this->CitasAtendidasoAbiertas(1,$result->fields[0],$result->fields[1],$sql_fecha,$sql_centro,$sql_unidad,$sql_dpto);
							$var[]=array('usuario'=>$usuario,'nombre'=>$nombre,'asignadas'=>$result->fields[2],'canceladas'=>$result->fields[3],'atendidas'=>$atendidas[total],'promedio'=>$atendidas[promedio],'abiertas'=>$abiertas[total]);
							$result->MoveNext();
					}
					$result->Close();													
			}
			return $var;
  }	
	//-------------------NUEVO DAR---------------
	function CitasAtendidasoAbiertas($tipo,$tipo_profesional,$id_profesional,$sql_fecha,$sql_centro,$sql_unidad,$sql_dpto)
  {   //tipo 0 atendidad 1 abiertas
      $var='';
      //abiertas
      if($tipo==1)
      {
          $x='';
          $y= " AND f.estado='1'";
      }
      else
      {   //son atendidad
          $x= ", (sum(f.fecha_cierre - f.fecha)/count(c.agenda_cita_asignada_id)) as promedio";
          $y= " AND f.estado='0'";
      }
      list($dbconn) = GetDBconn();
      $query = "SELECT count(c.agenda_cita_asignada_id) as total $x 
                FROM agenda_turnos a,
                agenda_citas b,
                agenda_citas_asignadas c,
                os_cruce_citas d,
                os_maestro e,
                hc_evoluciones f,
                tipos_consulta z,
                departamentos dpto,
                userpermisos_repconsultaexterna rep 
                WHERE a.tipo_id_profesional='$tipo_profesional' 
                AND a.profesional_id='$id_profesional'
                $sql_fecha
                AND a.tipo_consulta_id=z.tipo_consulta_id 
                AND z.departamento=dpto.departamento
                $sql_centro $sql_unidad $sql_dpto
                AND a.agenda_turno_id=b.agenda_turno_id 
                AND a.sw_estado_cancelacion='0' 
                AND b.agenda_cita_id=c.agenda_cita_id 
                AND b.agenda_cita_id=c.agenda_cita_id_padre 
                AND (b.sw_estado='1' OR b.sw_estado='2') 
                AND c.agenda_cita_asignada_id 
                NOT IN (SELECT agenda_cita_asignada_id FROM agenda_citas_asignadas_cancelacion) 
                AND c.agenda_cita_asignada_id=d.agenda_cita_asignada_id 
                AND d.numero_orden_id=e.numero_orden_id 
                AND e.numerodecuenta=f.numerodecuenta
                AND dpto.empresa_id='".$_SESSION['recoex']['empresa']."'
                AND dpto.empresa_id=rep.empresa_id
                AND dpto.centro_utilidad=rep.centro_utilidad
                AND dpto.unidad_funcional=rep.unidad_funcional
                AND dpto.departamento=rep.departamento
                AND rep.usuario_id='".UserGetUID()."'
                 
                $y"; 
      $resul = $dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      } 
      if(!$result->EOF)
      {
          $var=$resul->GetRowAssoc($ToUpper = false);
          $resul->Close();                  
      }
      return $var;
  }
	
  function DiasLaboradosProfesional($feinictra,$fefinctra,$profesional_escojer)
	{
    list($dbconn) = GetDBconn();
		(list($diaIn,$mesIn,$anoIn)=explode('/',$feinictra));
		(list($diaFn,$mesFn,$anoFn)=explode('/',$fefinctra));
		
    $feinictra=$anoIn."-".$mesIn."-".$diaIn;
		$fefinctra=$anoFn."-".$mesFn."-".$diaFn;
	
   // $feinictra = $this->FechaStamp($feinictra);
   // $fefinctra = $this->FechaStamp($fefinctra);
    $query="SELECT count(*) as total
            FROM
                    (SELECT date(con.fecha_turno)
                      FROM agenda_turnos con,profesionales_usuarios a
                      WHERE date(con.fecha_turno) BETWEEN '".$feinictra."' AND '".$fefinctra."' AND
                      con.tipo_id_profesional=a.tipo_tercero_id AND con.profesional_id=a.tercero_id AND
                      a.usuario_id='".$profesional_escojer."'
                      GROUP BY date(con.fecha_turno)
                    ) as diaslab";
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0) {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
    }else{
      return $result->fields[0];
    }
  }			
	
	//------------------FIN NUEVO DAR---------------		
/*	function ConsultaEstadisticaRendimientoProf($centroU,$unidadF,$DptoSel,$profesional_escojer,$feinictra,$fefinctra){

    GLOBAL $ADODB_FETCH_MODE;
    list($dbconn) = GetDBconn();
    if(!empty($centroU)){
      $sql_centro = " AND x.centro_utilidad = '".$centroU."'";
    }
    if(!empty($unidadF)){
      $sql_unidad = " AND x.unidad_funcional = '".$unidadF."'";
    }
    if(!empty($DptoSel)){
      $sql_dpto = " AND x.departamento = '".$DptoSel."'";
    }
    if($profesional_escojer != '-1'){
      $usuario_id = explode(',',$profesional_escojer);
      if(!empty($usuario_id[0])){
        $sql_usuario = " AND y.usuario_id = ".$usuario_id[0]."";
      }
    }
    if(!empty($feinictra) AND !empty($fefinctra)){
      (list($diaIn,$mesIn,$anoIn)=explode('/',$feinictra));
      (list($diaFn,$mesFn,$anoFn)=explode('/',$fefinctra));
      if(!empty($feinictra) AND !empty($fefinctra)){
        $sql_fecha = " AND a.fecha_turno BETWEEN '".$anoIn."-".$mesIn."-".$diaIn."' AND '".$anoFn."-".$mesFn."-".$diaFn."'";
      }
    }

		$query="SELECT y.usuario_id,asignadas.total as total_asignadas,canceladas.total as total_canceladas,atendidas.total as total_atendidas,abiertas.total as total_abiertas,ter.nombre_tercero,
            atendidas.promedio
            FROM agenda_turnos a,tipos_consulta z,departamentos x,profesionales_usuarios y
            LEFT JOIN (
                SELECT y.usuario_id,count(*) as total
                FROM agenda_turnos a,agenda_citas b,agenda_citas_asignadas c,tipos_consulta z,departamentos x,profesionales_usuarios y
                WHERE a.agenda_turno_id=b.agenda_turno_id AND a.sw_estado_cancelacion='0' AND
                b.agenda_cita_id=c.agenda_cita_id AND b.agenda_cita_id=c.agenda_cita_id_padre AND
                (b.sw_estado='1' OR b.sw_estado='2') AND c.sw_atencion='0' AND a.tipo_consulta_id=z.tipo_consulta_id
                AND z.departamento=x.departamento AND a.tipo_id_profesional=y.tipo_tercero_id AND a.profesional_id=y.tercero_id
                $sql_centro $sql_unidad $sql_dpto $sql_usuario $sql_fecha
                GROUP BY y.usuario_id
            ) as asignadas ON (asignadas.usuario_id=y.usuario_id)

            LEFT JOIN (
                SELECT y.usuario_id,count(*) as total
                FROM agenda_turnos a,agenda_citas b,agenda_citas_asignadas c,tipos_consulta z,departamentos x,profesionales_usuarios y
                WHERE a.agenda_turno_id=b.agenda_turno_id AND a.sw_estado_cancelacion='0' AND
                b.agenda_cita_id=c.agenda_cita_id AND b.agenda_cita_id=c.agenda_cita_id_padre AND
                c.agenda_cita_asignada_id IN (SELECT agenda_cita_asignada_id FROM agenda_citas_asignadas_cancelacion)
                AND a.tipo_consulta_id=z.tipo_consulta_id AND z.departamento=x.departamento AND
                a.tipo_id_profesional=y.tipo_tercero_id AND a.profesional_id=y.tercero_id
                $sql_centro $sql_unidad $sql_dpto $sql_usuario $sql_fecha
                GROUP BY y.usuario_id
            ) as canceladas ON (canceladas.usuario_id=y.usuario_id)

            LEFT JOIN (
                SELECT y.usuario_id,count(*) as total,(sum(f.fecha_cierre - f.fecha)/count(*)) as promedio
                FROM agenda_turnos a,agenda_citas b,agenda_citas_asignadas c,os_cruce_citas d,os_maestro e,hc_evoluciones f,tipos_consulta z,departamentos x,profesionales_usuarios y
                WHERE a.agenda_turno_id=b.agenda_turno_id AND a.sw_estado_cancelacion='0' AND
                b.agenda_cita_id=c.agenda_cita_id AND b.agenda_cita_id=c.agenda_cita_id_padre AND
                (b.sw_estado='1' OR b.sw_estado='2') AND
                c.agenda_cita_asignada_id NOT IN (SELECT agenda_cita_asignada_id FROM agenda_citas_asignadas_cancelacion) AND
                c.agenda_cita_asignada_id=d.agenda_cita_asignada_id AND d.numero_orden_id=e.numero_orden_id AND
                e.numerodecuenta=f.numerodecuenta AND f.estado='0'
                AND a.tipo_consulta_id=z.tipo_consulta_id AND z.departamento=x.departamento AND
                a.tipo_id_profesional=y.tipo_tercero_id AND a.profesional_id=y.tercero_id
                $sql_centro $sql_unidad $sql_dpto $sql_usuario $sql_fecha
                GROUP BY y.usuario_id
            ) as atendidas ON (atendidas.usuario_id=y.usuario_id)

            LEFT JOIN (
                SELECT y.usuario_id,count(*) as total
                FROM agenda_turnos a,agenda_citas b,agenda_citas_asignadas c,os_cruce_citas d,os_maestro e,hc_evoluciones f,tipos_consulta z,departamentos x,profesionales_usuarios y
                WHERE a.agenda_turno_id=b.agenda_turno_id AND a.sw_estado_cancelacion='0' AND
                b.agenda_cita_id=c.agenda_cita_id AND b.agenda_cita_id=c.agenda_cita_id_padre AND
                (b.sw_estado='1' OR b.sw_estado='2') AND
                c.agenda_cita_asignada_id NOT IN (SELECT agenda_cita_asignada_id FROM agenda_citas_asignadas_cancelacion) AND
                c.agenda_cita_asignada_id=d.agenda_cita_asignada_id AND d.numero_orden_id=e.numero_orden_id AND
                e.numerodecuenta=f.numerodecuenta AND f.estado='1'
                AND a.tipo_consulta_id=z.tipo_consulta_id AND z.departamento=x.departamento AND
                a.tipo_id_profesional=y.tipo_tercero_id AND a.profesional_id=y.tercero_id
                $sql_centro $sql_unidad $sql_dpto $sql_usuario $sql_fecha
                GROUP BY y.usuario_id
            ) as abiertas ON (abiertas.usuario_id=y.usuario_id),
            terceros ter

            WHERE a.sw_estado_cancelacion='0' AND a.tipo_consulta_id=z.tipo_consulta_id AND
            z.departamento=x.departamento AND a.tipo_id_profesional=y.tipo_tercero_id AND
            a.profesional_id=y.tercero_id AND y.tipo_tercero_id=ter.tipo_id_tercero AND y.tercero_id=ter.tercero_id
            $sql_centro $sql_unidad $sql_dpto $sql_usuario $sql_fecha
            GROUP BY y.usuario_id,asignadas.total,canceladas.total,atendidas.total,abiertas.total,ter.nombre_tercero,atendidas.promedio
            ORDER BY ter.nombre_tercero
            ";
    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
    $result = $dbconn->Execute($query);
    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
    if($dbconn->ErrorNo() != 0) {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
    }else{
      while ($data = $result->FetchRow()){
          $vars[] = $data;
      }
    }
    $result->Close();
    return $vars;

  }*/

}  
