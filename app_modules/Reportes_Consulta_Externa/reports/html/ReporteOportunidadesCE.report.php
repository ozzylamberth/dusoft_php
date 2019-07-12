<?php

/**
 * $Id: ReporteOportunidadesCE.report.php,v 1.1.1.1 2009/09/11 20:36:56 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Archivo que imprime el formulario anexo1 que se entrega al FOSYGA
 */

class ReporteOportunidadesCE_report
{
	function ReporteOportunidadesCE_report($datos=array())
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
    $registros=$this->ConsultaEstadisticaOportunidadCE($this->datos['centroU'],$this->datos['unidadF'],$this->datos['DptoSel'],$this->datos['profesional_escojer'],$this->datos['feinictra'],$this->datos['fefinctra']);
    if($registros){
      $CantRegistrosTotal=0;
      $diasTotales=0;
      foreach($registros as $usuarioId=>$vector){
        foreach($vector as $nombreProf=>$vectorUno){
          $diasTotalProf=0;
          $CantRegistros=0;
          $HTML_WEB_PAGE.="<TABLE WIDTH='100%' BORDER=1 ALIGN='CENTER'>";
          $HTML_WEB_PAGE.="<TR>";
          $HTML_WEB_PAGE.="<TD><FONT SIZE='1' COLSPAN='4'>".$nombreProf."</FONT></TD>";
          $HTML_WEB_PAGE.="</TR>";
          $HTML_WEB_PAGE.="<TR>";
          $HTML_WEB_PAGE.="<TD><FONT SIZE='1'>PACIENTE</FONT></TD>";
          $HTML_WEB_PAGE.="<TD WIDTH='20%'><FONT SIZE='1'>FECHA ASIGNACION</FONT></TD>";
          $HTML_WEB_PAGE.="<TD WIDTH='20%'><FONT SIZE='1'>FECHA ATENCION</FONT></TD>";
          $HTML_WEB_PAGE.="<TD WIDTH='20%'><FONT SIZE='1'>DIAS TRANSCURRIDOS</FONT></TD>";
          $HTML_WEB_PAGE.="</TR>";
          foreach($vectorUno as $citaId=>$Datos){
            $HTML_WEB_PAGE.="<TR>";
            $HTML_WEB_PAGE.="<TD><FONT SIZE='1'>".$Datos['nombre_pac']."</FONT></TD>";
            (list($fechaIn,$HoraIn)=explode(' ',$Datos['fecha_registro']));
            (list($anoIn,$mesIn,$diaIn)=explode('-',$fechaIn));
            (list($horIn,$minutosIn)=explode(':',$HoraIn));
            $HTML_WEB_PAGE.="<TD><FONT SIZE='1'>".ucfirst(strftime("%b %d de %Y %H:%M",mktime($horIn,$minutosIn,0,$mesIn,$diaIn,$anoIn)))."</FONT></TD>";
            (list($fechaFn,$HoraFn)=explode(' ',$Datos['fecha']));
            (list($anoFn,$mesFn,$diaFn)=explode('-',$fechaFn));
            (list($horFn,$minutosFn)=explode(':',$HoraFn));
            $HTML_WEB_PAGE.="<TD><FONT SIZE='1'>".ucfirst(strftime("%b %d de %Y %H:%M",mktime($horFn,$minutosFn,0,$mesFn,$diaFn,$anoFn)))."</FONT></TD>";
            $dias=(int)((((mktime($horFn,$minutosFn,0,$mesFn,$diaFn,$anoFn)-mktime($horIn,$minutosIn,0,$mesIn,$diaIn,$anoIn))/60)/60)/24);
            $HTML_WEB_PAGE.="<TD><FONT SIZE='1'>".$dias."</FONT></TD>";
            $HTML_WEB_PAGE.="</TR>";
            $diasTotalProf+=$dias;
            $CantRegistros++;
          }
          $diasTotales+=$diasTotalProf;
          $CantRegistrosTotal+=$CantRegistros;
          $HTML_WEB_PAGE.="<TR>";
          $HTML_WEB_PAGE.="<TD COLSPAN='3' ALIGN='LEFT'><FONT SIZE='1'>TOTAL DIAS</FONT></TD>";
          $HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>".$diasTotalProf."</FONT></TD>";
          $HTML_WEB_PAGE.="</TR>";
          $HTML_WEB_PAGE.="<TR>";
          $HTML_WEB_PAGE.="<TD COLSPAN='3' ALIGN='LEFT'><FONT SIZE='1'>TOTAL CONSULTAS</FONT></TD>";
          $HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>".$CantRegistros."</FONT></TD>";
          $HTML_WEB_PAGE.="</TR>";
          $HTML_WEB_PAGE.="<TR>";
          $HTML_WEB_PAGE.="<TD COLSPAN='3' ALIGN='LEFT'><FONT SIZE='1'>PROMEDIO</FONT></TD>";
          $HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>".round(($diasTotalProf/$CantRegistros),2)."</FONT></TD>";
          $HTML_WEB_PAGE.="</TR>";
          $HTML_WEB_PAGE.="</TABLE><br>";
        }
      }
      $HTML_WEB_PAGE.="<TABLE WIDTH='100%' BORDER=1 ALIGN='CENTER'>";
			$HTML_WEB_PAGE.="<TR>";
      $HTML_WEB_PAGE.="<TD COLSPAN='2'><FONT SIZE='1'>TOTALES</FONT></TD>";
      $HTML_WEB_PAGE.="</TR>";
      $HTML_WEB_PAGE.="<TR>";
      $HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>TOTAL DIAS</FONT></TD>";
      $HTML_WEB_PAGE.="<TD WIDTH='20%' ALIGN='LEFT'><FONT SIZE='1'>".$diasTotales."</FONT></TD>";
      $HTML_WEB_PAGE.="</TR>";
      $HTML_WEB_PAGE.="<TR>";
      $HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>TOTAL CONSULTAS</FONT></TD>";
      $HTML_WEB_PAGE.="<TD WIDTH='20%' ALIGN='LEFT'><FONT SIZE='1'>".$CantRegistrosTotal."</FONT></TD>";
      $HTML_WEB_PAGE.="</TR>";
      $HTML_WEB_PAGE.="<TR>";
      $HTML_WEB_PAGE.="<TD ALIGN='LEFT'><FONT SIZE='1'>PROMEDIO</FONT></TD>";
      $HTML_WEB_PAGE.="<TD WIDTH='20%' ALIGN='LEFT'><FONT SIZE='1'>".round(($diasTotales/$CantRegistrosTotal),2)."</FONT></TD>";
      $HTML_WEB_PAGE.="</TR>";
			$HTML_WEB_PAGE.="</TABLE><br>";
		}
		$HTML_WEB_PAGE.="</BODY></HTML>";
		return $HTML_WEB_PAGE;
	}

	function ConsultaEstadisticaOportunidadCE($centroU,$unidadF,$DptoSel,$profesional_escojer,$feinictra,$fefinctra)
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
			//---CAMBIO DAR	se creo un indice y se quito una condicion q no 							
			/*$query = "SELECT y.usuario_id,ter.nombre_tercero,c.agenda_cita_asignada_id,
								f.fecha,c.fecha_registro, pac.primer_nombre||' '||pac.segundo_nombre||' '||pac.primer_apellido||' '||pac.segundo_apellido as nombre_pac 
								FROM agenda_turnos a,
								profesionales_usuarios y,
								terceros ter, 
								agenda_citas b,
								agenda_citas_asignadas c,
								os_cruce_citas d,
								os_maestro e,
								hc_evoluciones f, 
								pacientes pac,
								departamentos as x 
								WHERE a.sw_estado_cancelacion='0' 
								$sql_fecha
								AND a.tipo_id_profesional=y.tipo_tercero_id 
								AND a.profesional_id=y.tercero_id 
								AND y.tipo_tercero_id=ter.tipo_id_tercero 
								AND y.tercero_id=ter.tercero_id 
								$sql_usuario
								AND a.agenda_turno_id=b.agenda_turno_id 
								AND b.agenda_cita_id=c.agenda_cita_id 
								AND b.agenda_cita_id=c.agenda_cita_id_padre 
								AND (b.sw_estado='1' OR b.sw_estado='2') 
								AND c.agenda_cita_asignada_id NOT IN (SELECT agenda_cita_asignada_id FROM agenda_citas_asignadas_cancelacion) 
								AND c.agenda_cita_asignada_id=d.agenda_cita_asignada_id 
								AND d.numero_orden_id=e.numero_orden_id 
								AND e.numerodecuenta=f.numerodecuenta 
								AND f.departamento=x.departamento
								$sql_centro $sql_unidad $sql_dpto  
								AND c.tipo_id_paciente=pac.tipo_id_paciente 
								AND c.paciente_id=pac.paciente_id 
								ORDER BY ter.nombre_tercero";*/
			$query = "SELECT y.usuario_id,ter.nombre_tercero,c.agenda_cita_asignada_id,
								f.fecha,c.fecha_registro, pac.primer_nombre||' '||pac.segundo_nombre||' '||pac.primer_apellido||' '||pac.segundo_apellido as nombre_pac 
								FROM agenda_turnos a,
								profesionales_usuarios y,
								terceros ter, 
								agenda_citas b,
								agenda_citas_asignadas c,
								os_cruce_citas d,
								os_maestro e,
								hc_evoluciones f, 
								pacientes pac,
								departamentos as dpto,
								userpermisos_repconsultaexterna rep 
								WHERE a.sw_estado_cancelacion='0' 
								$sql_fecha
								AND a.tipo_id_profesional=y.tipo_tercero_id 
								AND a.profesional_id=y.tercero_id 
								AND y.tipo_tercero_id=ter.tipo_id_tercero 
								AND y.tercero_id=ter.tercero_id 
								$sql_usuario
								AND a.agenda_turno_id=b.agenda_turno_id 
								AND b.agenda_cita_id=c.agenda_cita_id 
								AND b.agenda_cita_id=c.agenda_cita_id_padre 
								AND (b.sw_estado='1' OR b.sw_estado='2') 
								AND c.agenda_cita_asignada_id NOT IN (SELECT agenda_cita_asignada_id FROM agenda_citas_asignadas_cancelacion) 
								AND c.agenda_cita_asignada_id=d.agenda_cita_asignada_id 
								AND d.numero_orden_id=e.numero_orden_id 
								AND e.numerodecuenta=f.numerodecuenta 
								AND f.departamento=dpto.departamento
								$sql_centro $sql_unidad $sql_dpto  
								AND c.tipo_id_paciente=pac.tipo_id_paciente 
								AND c.paciente_id=pac.paciente_id 
								
								AND dpto.empresa_id='".$_SESSION['recoex']['empresa']."'
								AND dpto.empresa_id=rep.empresa_id
								AND dpto.centro_utilidad=rep.centro_utilidad
								AND dpto.unidad_funcional=rep.unidad_funcional
								AND dpto.departamento=rep.departamento
								AND rep.usuario_id='".UserGetUID()."'
								
								ORDER BY ter.nombre_tercero";					
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}else{
				while ($data = $result->FetchRow()){
						$vars[$data['usuario_id']][$data['nombre_tercero']][$data['agenda_cita_asignada_id']] = $data;
				}
			}
			$result->Close();
			return $vars;
  }
	

}
