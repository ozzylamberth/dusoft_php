<?php

/**
 * $Id: ReporteConsultaExterna.report.php,v 1.3 2009/11/27 13:27:16 hugo Exp $
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
		          
          $HTML_WEB_PAGE .= "<br><br><center>";
		$HTML_WEB_PAGE .= "<label><font size='6' face='arial'>REPORTE ESTADISTICO AGENDA MÉDICA</font></label>";
		$HTML_WEB_PAGE .= "</center><br><br>";

          $HTML_WEB_PAGE.="<TABLE WIDTH='100%' BORDER=0>";
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD WIDTH='30%' ALIGN='LEFT'><FONT SIZE='1'>NOMBRE DE LA EMPRESA:</FONT></TD>";
		$HTML_WEB_PAGE.="<TD WIDTH='70%' ALIGN='LEFT'><FONT SIZE='1'>".$this->datos['var']['razonso']."</FONT></TD>";
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
    
    IncludeClass('ConexionBD');
    IncludeClass('Afiliados','classes','app','Reportes_Consulta_Externa');
    $cls = new Afiliados();

		for($i=0;$i<$registros1;$i++)//$registros
		{          
			$HTML_WEB_PAGE.="<TABLE WIDTH='100%' BORDER=1>";
			$HTML_WEB_PAGE.="<TR>";
			$HTML_WEB_PAGE.="<TD WIDTH='4%'  ALIGN='CENTER'><FONT SIZE='1'>No.</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='7%'  ALIGN='CENTER'><FONT SIZE='1'>TIPO ID</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='15%' ALIGN='CENTER'><FONT SIZE='1'>IDENTIFICACIÓN</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='45%' ALIGN='CENTER'><FONT SIZE='1'>NOMBRE</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='22%' ALIGN='CENTER'><FONT SIZE='1'>ESPECIALIDAD</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='7%'  ALIGN='CENTER'><FONT SIZE='1'>ESTADO</FONT></TD>";
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
			$HTML_WEB_PAGE.="<TD WIDTH='45%' ALIGN='LEFT'><FONT SIZE='1'>";
			$HTML_WEB_PAGE.="".$datos1[$i]['nombre_tercero']."";
			$HTML_WEB_PAGE.="</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='22%' ALIGN='LEFT'><FONT SIZE='1'>";
			$HTML_WEB_PAGE.="".$datos1[$i]['descripcion']."";
			$HTML_WEB_PAGE.="</FONT></TD>";
			$HTML_WEB_PAGE.="<TD WIDTH='7%'  ALIGN='CENTER'><FONT SIZE='1'>";
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
			$HTML_WEB_PAGE.="<TABLE WIDTH='100%' BORDER=1 rules=\"all\">";
			$HTML_WEB_PAGE.="<TR>";
			$HTML_WEB_PAGE.="<TD ALIGN='CENTER'><FONT SIZE='1'>No.</FONT></TD>";
			$HTML_WEB_PAGE.="<TD ALIGN='CENTER'><FONT SIZE='1'>FECHA TURNO</FONT></TD>";
			$HTML_WEB_PAGE.="<TD ALIGN='CENTER'><FONT SIZE='1'>HORA</FONT></TD>";
			$HTML_WEB_PAGE.="<TD ALIGN='CENTER'><FONT SIZE='1'>DURACIÓN</FONT></TD>";
			//nv cargosadicionales
			if($datos1[$i]['sw_cargos_adicionales']!=1){
			$HTML_WEB_PAGE.="<TD ALIGN='CENTER'><FONT SIZE='1'>CONSULTORIO</FONT></TD>";
			}
			$HTML_WEB_PAGE.="<TD ALIGN='CENTER'><FONT SIZE='1'>PLAN</FONT></TD>";
			$HTML_WEB_PAGE.="<TD ALIGN='CENTER'><FONT SIZE='1'>ESTADO</FONT></TD>";
			$HTML_WEB_PAGE.="<TD ALIGN='CENTER'><FONT SIZE='1'>COTIZANTE</FONT></TD>";
			$HTML_WEB_PAGE.="<TD ALIGN='CENTER'><FONT SIZE='1'>PACIENTE</FONT></TD>";
			$HTML_WEB_PAGE.="<TD ALIGN='CENTER'><FONT SIZE='1'>EDAD</FONT></TD>";
			$HTML_WEB_PAGE.="<TD ALIGN='CENTER'><FONT SIZE='1'>TELEFONO</FONT></TD>";
			//naydu Cargos adicionales 
			if($datos1[$i]['sw_cargos_adicionales']==1){
				$HTML_WEB_PAGE.="<TD ALIGN='CENTER'><FONT SIZE='1'>CARGOS ADICIONALES</FONT></TD>";
			}
			$HTML_WEB_PAGE.="<TD ALIGN='CENTER'><FONT SIZE='1'>TIPO DE AFILIADO</FONT></TD>";
			$HTML_WEB_PAGE.="<TD ALIGN='CENTER'><FONT SIZE='1'>RANGO</FONT></TD>";
			$HTML_WEB_PAGE.="<TD ALIGN='CENTER'><FONT SIZE='1'>PUNTO ATENCIÓN</FONT></TD>";
			//nv cargosadicionales
			if($datos1[$i]['sw_cargos_adicionales']!=1){
			$HTML_WEB_PAGE.="<TD ALIGN='CENTER'><FONT SIZE='1'>APERTURA</FONT></TD>";
			$HTML_WEB_PAGE.="<TD ALIGN='CENTER'><FONT SIZE='1'>CIERRE</FONT></TD>";+
			$HTML_WEB_PAGE.="<TD ALIGN='CENTER'><FONT SIZE='1'>DURACIÓN</FONT></TD>";
			}
			$HTML_WEB_PAGE.="</TR>";
			$datos2=$this->BuscarDatosReporte2($datos1[$i]['agenda_turno_id']);
			$registros2=sizeof($datos2);
			for($j=0;$j<$registros2;$j++)//$registros
			{
        $afiliacion = array();
        if($datos2[$j]['tipo_id_paciente'])
          $afiliacion = $cls->ObtenerInformacionAfiliado($datos2[$j]);
				$HTML_WEB_PAGE.="<TR class=\"normal_10\">";
				$HTML_WEB_PAGE.="<TD ALIGN='CENTER'><FONT SIZE='1'>";
				$HTML_WEB_PAGE.="".($j+1)."";
				$HTML_WEB_PAGE.="</FONT></TD>";
				$HTML_WEB_PAGE.="<TD ALIGN='CENTER'><FONT SIZE='1'>";
				$HTML_WEB_PAGE.="".$datos1[$i]['fecha_turno']."";
				$HTML_WEB_PAGE.="</FONT></TD>";
				$HTML_WEB_PAGE.="<TD ALIGN='CENTER'><FONT SIZE='1'>";
				$HTML_WEB_PAGE.="".$datos2[$j]['hora']."";
				$HTML_WEB_PAGE.="</FONT></TD>";
				$HTML_WEB_PAGE.="<TD ALIGN='CENTER'><FONT SIZE='1'>";
				$HTML_WEB_PAGE.="".$datos1[$i]['duracion']."";
				$HTML_WEB_PAGE.="</FONT></TD>";
				//nv cargosadicionales
		if($datos1[$i]['sw_cargos_adicionales']!=1){
				$HTML_WEB_PAGE.="<TD ALIGN='CENTER'><FONT SIZE='1'>";
				$HTML_WEB_PAGE.="".$datos1[$i]['consultorio_id']."";
				$HTML_WEB_PAGE.="</FONT></TD>";
		}
        $HTML_WEB_PAGE.="<TD ALIGN='CENTER'><FONT SIZE='1'>";
				$HTML_WEB_PAGE.="".$afiliacion['plan_atencion']."";
				$HTML_WEB_PAGE.="</FONT></TD>";

        $HTML_WEB_PAGE.="<TD ALIGN='CENTER'><FONT SIZE='1'>";
				if($datos2[$j][sw_atencion]==1){
					$HTML_WEB_PAGE.="CANCELADA";
				}elseif($datos2[$j][sw_atencion]==3){
					$HTML_WEB_PAGE.="ATENDIDA";
				}else{
					if($datos2[$j][sw_estado]==2){
						$HTML_WEB_PAGE.="PAGA";
					}elseif($datos2[$j][sw_estado]==3){
						$HTML_WEB_PAGE.="CUMPLIDA";
					}elseif($datos2[$j][sw_agenda_citas]==3){
					  $HTML_WEB_PAGE.="CANCELADA";
					}else{
						$HTML_WEB_PAGE.="ACTIVA";
					}
				}$HTML_WEB_PAGE.="</FONT></TD>";
				
				//aqui naydu 
					
				$afiliacionCotizante = array();
				$afiliacionCotizante = $cls->ObtenerCotizanteAfiliado($datos2[$j]);
				$HTML_WEB_PAGE.="<TD ALIGN='CENTER'><FONT SIZE='1'>";
				if(count($afiliacionCotizante) > 0 ){
					$HTML_WEB_PAGE.=$afiliacionCotizante['tipo_id']." - ".$afiliacionCotizante['id'];
				}else{
					$HTML_WEB_PAGE .= "";
				}
				$HTML_WEB_PAGE.="</FONT></TD>";
				
				
				
				$HTML_WEB_PAGE.="<TD ALIGN='CENTER'><FONT SIZE='1'>";
				if(!empty($datos2[$j]['tipo_id_paciente']) && !empty($datos2[$j]['paciente_id'])){
				  $HTML_WEB_PAGE.="".$datos2[$j]['tipo_id_paciente']."".' - '."".$datos2[$j]['paciente_id']." ".$datos2[$j]['nombre']."";
				}elseif($datos2[$j]['sw_agenda_citas']==3){
          $HTML_WEB_PAGE.="TURNO CANCELADO";
				}else{
					$HTML_WEB_PAGE.="&nbsp;";
				}
				$HTML_WEB_PAGE.="</FONT></TD>";
        $label_edad = "&nbsp;";
        if($datos2[$j]['edad_paciente']!= '')
        {
          $edad_paciente = explode(":",$datos2[$j]['edad_paciente']);
          if($edad_paciente[0] > 0) 
            $label_edad = $edad_paciente[0]." año(s)";
          else if($edad_paciente[1] > 0) 
            $label_edad = $edad_paciente[1]." mes(es)";
          else
            $label_edad = $edad_paciente[2]." día(es)";
        }
        $HTML_WEB_PAGE .= "    <td ALIGN='CENTER'>".$label_edad."</td>";
        $HTML_WEB_PAGE .= "    <td  ALIGN='CENTER'><FONT SIZE='1'>".$datos2[$j]['residencia_telefono']."</FONT></td>\n";
		//naydu cargos adicionales
				if($datos1[$i]['sw_cargos_adicionales']==1){				
					$HTML_WEB_PAGE.="<td ALIGN='CENTER'>";
					if($datosn = $this -> BuscarCargosAdicionales($datos2[$j]['agenda_cita_asignada_id'])){
							for ($n = 0; $n < count($datosn); $n++){
								$HTML_WEB_PAGE.="<TABLE>";
								$HTML_WEB_PAGE.="<TD  ALIGN='CENTER'><FONT SIZE='1'>";
								$HTML_WEB_PAGE.="".$datosn[$n]['descripcion']."";
								$HTML_WEB_PAGE.="</FONT></TD>";
								$HTML_WEB_PAGE.="</TABLE>";
							} 
						}

					$HTML_WEB_PAGE.="</td>\n";
			    }
		
		
        $HTML_WEB_PAGE .= "    <td ALIGN='CENTER'>".$afiliacion['tipo_afiliado_atencion']."</td>\n";
				$HTML_WEB_PAGE .= "    <td ALIGN='CENTER'>".$afiliacion['rango_afiliado_atencion']."</td>\n";
				$HTML_WEB_PAGE .= "    <td ALIGN='CENTER'>".$afiliacion['eps_punto_atencion_nombre']."</td>\n";
				
	//nv cargosadicionales
			if($datos1[$i]['sw_cargos_adicionales']!=1){

				$HTML_WEB_PAGE.="<TD ALIGN='CENTER'><FONT SIZE='1'>";
				$HTML_WEB_PAGE.="".$datos2[$j]['fecha_abre']."";
				$HTML_WEB_PAGE.="</FONT></TD>";
				$HTML_WEB_PAGE.="<TD ALIGN='CENTER'><FONT SIZE='1'>";
				$HTML_WEB_PAGE.="".$datos2[$j]['fecha_cierre']."";
				$HTML_WEB_PAGE.="</FONT></TD>";

				$HTML_WEB_PAGE.="<TD ALIGN='CENTER'><FONT SIZE='1'>";
				$HTML_WEB_PAGE.="".$datos2[$j]['fecha_duracion']."";
				$HTML_WEB_PAGE.="</FONT></TD>";
			}

				$HTML_WEB_PAGE.="</TR>";
			}
			$HTML_WEB_PAGE.="</TABLE>";
		}
		$HTML_WEB_PAGE.="</BODY></HTML>";
		return $HTML_WEB_PAGE;
	}

	function BuscarDatosReporte1($datos)
	{
          if(!empty($this->datos['variables']['centroU'])){
               $sql_centro = " AND dpto.centro_utilidad = '".$this->datos['variables']['centroU']."'";
          }
          if(!empty($this->datos['variables']['unidadF'])){
               $sql_unidad = " AND dpto.unidad_funcional = '".$this->datos['variables']['unidadF']."'";
          }
          if(!empty($this->datos['variables']['depto'])){
              $dpto=explode(',',$this->datos['variables']['depto']);
              $sql_dpto = " AND dpto.departamento = '".$dpto[0]."'";
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

          
        list($dbconn) = GetDBconn();
        /*$query = "SELECT DISTINCT A.tipo_id_tercero,
        A.tercero_id,
        B.nombre_tercero,
        G.estado,
				X.agenda_turno_id,
				X.fecha_turno,
        X.duracion,
        X.tipo_consulta_id,
        X.consultorio_id,
        Y.descripcion
        FROM profesionales AS A,        
        terceros AS B,
        profesionales_estado AS G,
          agenda_turnos AS X,
          tipos_consulta AS Y,
          profesionales_departamentos  AS C,
          departamentos AS dpto           
        WHERE A.tipo_id_tercero=B.tipo_id_tercero
        AND A.tercero_id=B.tercero_id
        AND A.tipo_id_tercero=G.tipo_id_tercero
        AND A.tercero_id=G.tercero_id
        AND A.tipo_id_tercero=X.tipo_id_profesional
        AND A.tercero_id=X.profesional_id
        AND X.empresa_id='".$_SESSION['recoex']['empresa']."'
				AND X.empresa_id=G.empresa_id
				AND X.tipo_consulta_id=Y.tipo_consulta_id
				AND X.sw_estado_cancelacion='0' AND
                    A.tipo_id_tercero=C.tipo_id_tercero
        AND A.tercero_id=C.tercero_id
        AND C.departamento=dpto.departamento
        $busqueda2
        $busqueda3
        $busqueda4
        $busqueda5
        $sql_centro
        $sql_unidad
        $sql_dpto
        ORDER BY X.fecha_turno,A.tipo_id_tercero, A.tercero_id;";
				*/
				/*$query = "SELECT a.*
				FROM (SELECT DISTINCT A.tipo_id_tercero,
        A.tercero_id,
        B.nombre_tercero,
        G.estado,
				X.agenda_turno_id,
				X.fecha_turno,
        X.duracion,
        X.tipo_consulta_id,
        X.consultorio_id,
        Y.descripcion,
				dpto.empresa_id,
				dpto.centro_utilidad,
				dpto.unidad_funcional,
				dpto.departamento
        FROM profesionales AS A,        
        terceros AS B,
        profesionales_estado AS G,
          agenda_turnos AS X,
          tipos_consulta AS Y,
          profesionales_departamentos  AS C,
          departamentos AS dpto
					        
        WHERE A.tipo_id_tercero=B.tipo_id_tercero
        AND A.tercero_id=B.tercero_id
        AND A.tipo_id_tercero=G.tipo_id_tercero
        AND A.tercero_id=G.tercero_id
        AND A.tipo_id_tercero=X.tipo_id_profesional
        AND A.tercero_id=X.profesional_id
        AND X.empresa_id='".$_SESSION['recoex']['empresa']."'
				AND X.empresa_id=G.empresa_id
				AND X.tipo_consulta_id=Y.tipo_consulta_id
				AND X.sw_estado_cancelacion='0' AND
                    A.tipo_id_tercero=C.tipo_id_tercero
            AND A.tercero_id=C.tercero_id
            AND C.departamento=dpto.departamento 					
        $busqueda2
				$busqueda3
        $busqueda4
        $busqueda5
        $sql_centro
        $sql_unidad
        $sql_dpto
        ORDER BY X.fecha_turno,A.tipo_id_tercero, A.tercero_id) as a,userpermisos_repconsultaexterna rep   
				WHERE 
				a.empresa_id=rep.empresa_id
				AND a.centro_utilidad=rep.centro_utilidad
				AND a.unidad_funcional=rep.unidad_funcional
				AND a.departamento=rep.departamento
				AND rep.usuario_id='".UserGetUID()."'
				;";*/
        
        $query = "SELECT a.*
				FROM (SELECT DISTINCT A.tipo_id_tercero,
        A.tercero_id,
        B.nombre_tercero,
        G.estado,
				X.agenda_turno_id,
				X.fecha_turno,
        X.duracion,
        X.tipo_consulta_id,
        X.consultorio_id,
        Y.descripcion,
				dpto.empresa_id,
				dpto.centro_utilidad,
				dpto.unidad_funcional,
				dpto.departamento,
				dpto.sw_cargos_adicionales
        FROM profesionales AS A,        
        terceros AS B,
        profesionales_estado AS G,
          agenda_turnos AS X,
          tipos_consulta AS Y,
          profesionales_departamentos  AS C,
          departamentos AS dpto
					        
        WHERE A.tipo_id_tercero=B.tipo_id_tercero 
        AND A.tercero_id=B.tercero_id 
        AND A.tipo_id_tercero=G.tipo_id_tercero 
        AND A.tercero_id=G.tercero_id 
        AND A.tipo_id_tercero=X.tipo_id_profesional 
        AND A.tercero_id=X.profesional_id 
        AND X.empresa_id='".$_SESSION['recoex']['empresa']."' 
        AND X.empresa_id=G.empresa_id 
        AND X.tipo_consulta_id=Y.tipo_consulta_id 
        AND X.sw_estado_cancelacion='0' 
        AND C.tipo_id_tercero = G.tipo_id_tercero 
        AND C.tercero_id = G.tercero_id
        AND C.departamento=dpto.departamento
        AND Y.departamento=dpto.departamento
        $busqueda2
				$busqueda3
        $busqueda4
        $busqueda5
        $sql_centro
        $sql_unidad
        $sql_dpto
        ORDER BY X.fecha_turno,A.tipo_id_tercero, A.tercero_id) as a,userpermisos_repconsultaexterna rep   
				WHERE 
				a.empresa_id=rep.empresa_id
				AND a.centro_utilidad=rep.centro_utilidad
				AND a.unidad_funcional=rep.unidad_funcional
				AND a.departamento=rep.departamento
				AND rep.usuario_id='".UserGetUID()."'
				;";
          //$dconn->debug=true;
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

	function BuscarDatosReporte2($turno)
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT  C.hora,
                  		C.sw_estado as sw_agenda_citas,
                  		D.tipo_id_paciente,
                  		D.paciente_id,
                      E.primer_apellido ||' '|| E.segundo_apellido ||' '|| E.primer_nombre ||' '|| E.segundo_nombre AS nombre,
               				E.residencia_telefono,
                      CASE WHEN E.fecha_nacimiento IS NOT NULL THEN edad_completa(E.fecha_nacimiento)
                      ELSE '' END AS edad_paciente,

    D.sw_atencion,os_maes.sw_estado,D.agenda_cita_id_padre,
		extract(HOUR FROM evol.fecha)||':'||extract(MINUTE FROM evol.fecha) as fecha_abre,
		extract(HOUR FROM evol.fecha_cierre)||':'||extract(MINUTE FROM evol.fecha_cierre) as fecha_cierre,
		extract(HOUR FROM (evol.fecha_cierre-evol.fecha))||':'||extract(MINUTE FROM (evol.fecha_cierre-evol.fecha)) as fecha_duracion,
		D.agenda_cita_asignada_id
		FROM agenda_citas AS C
		LEFT JOIN agenda_citas_asignadas AS D ON (C.agenda_cita_id=D.agenda_cita_id)
		LEFT JOIN os_cruce_citas AS os_cruz ON(D.agenda_cita_asignada_id=os_cruz.agenda_cita_asignada_id)
		LEFT JOIN os_maestro AS os_maes ON(os_cruz.numero_orden_id=os_maes.numero_orden_id)

		LEFT JOIN cuentas AS ctas ON(os_maes.numerodecuenta=ctas.numerodecuenta)
		LEFT JOIN hc_evoluciones AS evol ON(ctas.ingreso=evol.ingreso)

		LEFT JOIN pacientes AS E ON (D.tipo_id_paciente=E.tipo_id_paciente AND D.paciente_id=E.paciente_id)
		WHERE C.agenda_turno_id=".$turno."
		ORDER BY C.hora;";
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
		//naydu este tambien
	function BuscarCargosAdicionales($cita_id)
	{
		list($dbconn) = GetDBconn();
		$query = "select CA.cargo, CU.descripcion
					from cargos_adicionales_citas CA LEFT JOIN cups CU 
					ON(CU.cargo= CA.cargo)
					where agenda_cita_asignada_id =".$cita_id.";";
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
