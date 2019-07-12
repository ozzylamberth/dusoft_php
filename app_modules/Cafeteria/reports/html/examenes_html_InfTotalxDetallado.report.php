<?php
  /**
  * $Id: examenes_html_InfTotalxDetallado.report.php,v 1.3 2009/06/04 20:31:24 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
  *  Reporte de prueba formato HTML
  */
  class examenes_html_InfTotalxDetallado_report 
  { 
    //VECTOR DE DATOS O PARAMETROS PARA GENERAR EL REPORTE
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
	
    //CONSTUCTOR DE LA CLASE - RECIBE EL VECTOR DE DATOS - METODO PRIVADO NO MODIFICAR
    function examenes_html_InfTotalxDetallado_report($datos=array())
    {
			$this->datos=$datos;
            //var_dump($this->datos);
      return true;
    }
    /**
    *
    */
  	function GetMembrete()
  	{
  		$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>'',//INFORME DE DIETAS CAFETERIA
  																'subtitulo'=>"",
  																'logo'=>'',//logocliente.png
  																'align'=>'left'));
  		return $Membrete;
  	}
    //FUNCION CrearReporte()
    //FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
    function CrearReporte()
    {
      $path = SessionGetVar("rutaImagenes");
      $empresa_id=$this->datos;
      $empresa_id1=$empresa_id['empresa_id'];
      $estaciones=$this->ConsultaEstaciones($empresa_id1);
  
      $datos=$this->ConsultaDietasEstaciones('1','','','','',$empresa_id['tipo_solicitud'],'','','',$empresa_id['sw_adicional']);
      if(!empty($datos))
      {
            foreach($datos AS $estacion => $ingreso)
            {
                $this->salida.="<table  cellpading=\"0\" cellspacing=\"0\" align=\"center\" border=\"1\" BORDERCOLOR=BLACK width=\"100%\">";
                $this->salida.="    <tr>";
                $this->salida.="        <td colspan ='10' align=\"LEFT\" class='label_error'>".$empresa_id['titulo']." &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  FECHA ".date("Y-m-d")." &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HORA DE IMPRESION ".date("G:i:s")."</td>";
                $this->salida.="    </tr>";
                $this->salida.="</table>";
    
                $this->salida.="<table  cellpading=\"0\" cellspacing=\"0\" align=\"center\" border=\"1\"  BORDERCOLOR=BLACK width=\"100%\">";
                $this->salida.=" <tr BORDERCOLOR=BLACK>";
                $this->salida.="     <td colspan = 11 class='label_error' align=\"center\" width=\"100%\">DATOS BASICOS PARA LA CONSULTA</td>";
                $this->salida.=" </tr>";
                $this->salida.=" <tr BORDERCOLOR=BLACK class='label_error'>";
                $this->salida.="     <td width=\"15%\" align=\"center\">ESTACION</td>";
                $this->salida.="     <td width=\"5%\" align=\"center\">INGRESO</td>";
                $this->salida.="     <td width=\"5%\" align=\"center\">CAMA</td>";
                $this->salida.="     <td width=\"10%\" align=\"center\">SOLICITUD</td>";
                $this->salida.="     <td width=\"27%\" align=\"center\">PACIENTE</td>";
                $this->salida.="     <td width=\"4%\" align=\"center\">AYUNO</td>";
                $this->salida.="     <td width=\"12%\" align=\"center\">DIETA</td>";
                $this->salida.="     <td width=\"10%\" align=\"center\">MOTIVO AYUNO</td>";
                $this->salida.="     <td width=\"5%\" align=\"center\">FECHA SOLICITADA</td>";
                $this->salida.="     <td width=\"7%\" align=\"center\">ESTADO</td>";
                $this->salida.="     <td width=\"5%\" align=\"center\">CONFIRMADA</td>";
                $this->salida.=" </tr>";
                
                $this->salida.="    <tr BORDERCOLOR=BLACK>";
                $this->salida.="        <td rowspan='".count($ingreso)."' align=\"left\">";
                $this->salida.="           ".$estacion."";
                $this->salida.="        </td>";
                foreach($ingreso AS $n_ingreso => $habitacion)
                {
                    $this->salida.="        <td  align=\"left\">";
                    $this->salida.="           ".$n_ingreso."";
                    $this->salida.="        </td>";

                    foreach($habitacion AS $n_habitacion => $tipo_solicitud)
                    {
                        $this->salida.="        <td  align=\"left\">";
                        $this->salida.="           ".$n_habitacion."";
                        $this->salida.="        </td>";
                        foreach($tipo_solicitud AS $des_habitacion => $nom_paciente)
                        {
                            $this->salida.="        <td  align=\"left\">";
                            $this->salida.="           ".$des_habitacion."";
                            $this->salida.="        </td>";

                            foreach($nom_paciente AS $des_nom_paciente => $sw_ayuno)
                            {
                                $this->salida.="        <td  align=\"left\">";
                                $this->salida.="           ".$des_nom_paciente."";
                                $this->salida.="        </td>";

                                    foreach($sw_ayuno AS $des_sw_ayuno => $tipo_dieta)
                                    {
                                            //var_dump($tipo_dieta);
                                            $this->salida.="        <td  align=\"center\">";
                                            if($des_sw_ayuno=='1')
                                            {

                                                $this->salida.="            <a title='".$observaciones[0]['motivo_ayuno']."' href='#'><sub><img src=\"".$path."/images/endturn.png\" border=\"0\" width=\"17\" height=\"17\"></sub></a>\n";
                                            }
                                            elseif($des_sw_ayuno==' ')
                                            {
                                                $this->salida.="            <sub><img src=\"".$path."/images/delete.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                                            }
                                            $this->salida.="        </td>";

                                            foreach($tipo_dieta AS $observacion => $CARACTERISTICAS)
                                            {
                                                $this->salida.="        <td  align=\"left\">";
                                                $solosalida="           ".$observacion."";
                                                //$this->salida.="        </td>";
                                            }

                                            foreach($CARACTERISTICAS AS $des_caracteristicas => $DATOX)
                                            {
                                                //var_dump($DATOX);
                                                //$this->salida.="        <td align=\"center\">";
                                                $this->salida.="           ".$des_caracteristicas."";
                                                for($i=0;$i<count($DATOX);$i++)
                                                {
                                                    $this->salida.="           ,".$DATOX[$i]['descripcion_caracteristica']."";
                                                }
                                                $this->salida.= ". &nbsp".$solosalida;
                                                $this->salida.="        </td>";
                                                $this->salida.="        <td  align=\"left\">";
                                                $this->salida.="           ".$DATOX[0]['motivo_ayuno']."&nbsp;";
                                                $this->salida.="        </td>";
                                                $this->salida.="        <td  align=\"left\">";
                                                $this->salida.="           ".substr($DATOX[0]['fecha_confirmacion'],0,19)."";
                                                $this->salida.="        </td>";
                                                if($DATOX[0]['estado_dieta']==1)
                                                {
                                                    $this->salida.="        <td  align=\"left\">";
                                                    $this->salida.="           ACTIVO";
                                                    $this->salida.="        </td>";
                                                }
                                                else
                                                {
                                                    $this->salida.="        <td bgcolor=\"#FFdddd\" align=\"left\">";
                                                    $this->salida.="          CANCELADA:  ".$DATOX[0]['motivo_cancelacion_dieta']." USUARIO: ".$DATOX[0]['usuario_id_cancelacion'];
                                                    $this->salida.="        </td>";

                                                }

                                                
                                                if($DATOX[0]['sw_recibida']=='0')
                                                {
                                                    $this->salida.="        <td id='total".$DATOX[0]['ingreso_id']."' class=\"".$estilo."\" align=\"center\">";
                                                    $this->salida.="            <a title='DIETA SIN CONFIRMAR POR EL USUARIO DE CAFETERIA' href=\"#\">";//
                                                    $this->salida.="              <sub><img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"17\" height=\"17\"></sub></a>\n";
                                                    $this->salida.="        </td>";
                                                }
                                                elseif($DATOX[0]['sw_recibida']=='1')
                                                {
                                                    $this->salida.="        <td  align=\"center\">";
                                                    $this->salida.="            <a title='DIETA CONFIRMADA USUARIO ".$DATOX[0]['usuario_recibe']." FECHA DE CONFIR ".$DATOX[0]['fecha_recibida']."' >";// href=\"javascript:CambiarEstado('".$DATOX[0]['ingreso_id']."','".$DATOX[0]['fecha_solicitud']."','".$DATOX[0]['tipo_solicitud_dieta_id']."','".$DATOX[0]['estacion_id']."','0','".UserGetUID()."','total".$DATOX[0]['ingreso_id']."');\"
                                                    $this->salida.="              <sub><img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"17\" height=\"17\"></sub></a>\n";
                                                    $this->salida.="        </td>";
                                                }
                                                

                                            }

                                    }

                            }
                        }
                    }
                $this->salida.="    </tr>";
                }
            $this->salida.="</table>";
            $this->salida .= "                 <table width=\"80%\" style=\"page-break-after: always\" border='0' align=\"center\" >\n";
            $this->salida .= "                    <tr class=\"normal_10AN\">\n";
            $this->salida .= "                      <td width=\"100%\" align=\"left\">\n";
            $this->salida .= "                        &nbsp;";
            $this->salida .= "                      </td>\n";
            $this->salida .= "                   </tr>\n";
            $this->salida .= "                 </table>\n";

            }
     

    


            
     }
    return $this->salida;
  }
    /**
    *   Consulta las dietas de los pacientes asiganadas en las estaciones
    *
    * @param    $tipo                               
    * @param    $estacion                       Identificador de la estacion
    * @param    $fecha_solicitud            Fecha en que se solicito la dieta
    * @param    $tipos_dieta                    tipo de dieta asignada al paciente
    * @param    $tipo_solicitud         Tipo de dieta solicitada, desayuno, almuerzo  o comida
    * @param    $fec_solicitud_ini      Rango min. para la solicitud de dietas
    * @param    $fec_solicitud_fin      Rango max. para la solicitud de dietas
    * @param    $ordena_por             Ordenamiento 1=pieza y cama, 2=tipo dieta y caracteristica
    * @param    $sw_adicional
    * @return   array
    */
    function ConsultaDietasEstaciones($tipo,$estacion='',$fecha_solicitud='',$tipos_dieta='',$caract_dieta='',$tipo_solicitud='',
                                                                        $fec_solicitud_ini='',$fec_solicitud_fin='',$ordena_por='',$sw_adicional='',$sw_estado='',$sw_conmfirma='')
    {
        $condicion_estacion = '';
        $condicion_fec_solicitud = '';
        $condicion_tipos_dieta = '';
        $condicion_tipo_solicitud  = '';
        $condicion_rangos_solicitud = '';
        $condicion_caract_dieta = '';
        $condicion_datos = '';

        if($datos=='1'){
            $condicion_datos = "    B.*,
                                                        HSDC.descripcion AS descripcion_caracteristica,
                                                        HSDC.descripcion_agrupamiento";
        }else{
            $condicion_datos = "COUNT(B.pieza)";
        }
        if(!empty($estacion)){
            $condicion_estacion="AND MH.estacion_id = '".$estacion."'";
        }

        if(!empty($fecha_solicitud)){
            $condicion_fec_solicitud="AND DS.fecha_solicitud = '".$fecha_solicitud."'";
            
        }
        else{
            $condicion_fec_solicitud="AND DS.fecha_solicitud = '".date("Y-m-d")."'";
            
        }

        if(!empty($tipos_dieta)){
            $condicion_tipos_dieta = "AND DSD.hc_dieta_id = '".$tipos_dieta."'";
        }
        //echo "esta es".$sw_adicional;
        if(!empty($tipo_solicitud)){
            $condicion_tipo_solicitud = "AND DTS.tipo_solicitud_dieta_id = '".$tipo_solicitud."'";
        }

        if( (!empty($fec_solicitud_ini)) && (!empty($fec_solicitud_fin)) ){
            $condicion_rangos_solicitud = "AND DS.fecha_solicitud > '".$fec_solicitud_ini."'
                                                                         AND DS.fecha_solicitud < '".$fec_solicitud_fin."'";
        }

        if(!empty($caract_dieta)){
            $condicion_caract_dieta = "WHERE HSDC.caracteristica_id = '".$caract_dieta."'";
        }

        if(!empty($ordena_por))
        {
            if($ordena_por=='1')
            {
                $condicion_orden= "B.pieza, B.tipo_cama_id";
            }
            elseif($ordena_por=='2')
            {
                $condicion_orden = "B.descripcion_tipo_dieta,HSDC.descripcion,HSDC.descripcion_agrupamiento";
            }
        }
        else
        {
            $condicion_orden= "B.pieza, B.tipo_cama_id";
        }

        if($sw_estado!='')
        {
            $excalibur="AND estado_dieta ='".$sw_estado."'";
        }
        else
        {
            $excalibur='';
        }

        if($sw_conmfirma!='')
        {
            $confirmacion=" DSD.sw_recibida ='".$sw_conmfirma."' AND ";
        }
        else
        {
            $confirmacion="";
        }

        if($sw_adicional!='')
        {
            $dieta_adicional=" DSD.sw_adicional ='".$sw_adicional."' AND ";
        }
        else
        {
            $dieta_adicional="";
        }

        
        $query="
                        SELECT B.*,
                                HSDC.descripcion AS descripcion_caracteristica,
                                HSDC.descripcion_agrupamiento
                        FROM
                                ( SELECT  A.*,
                                          DSDC.caracteristica_id,
                                          MAYU.motivo as motivo_ayuno
                                  FROM    (
                                            SELECT  MH.estacion_id,
                                                    EE.descripcion AS descripcion_estacion,
                                                    DS.ingreso_id,
                                                    DS.fecha_solicitud,
                                                    DS.fecha_confirmacion,
                                                    DS.tipo_solicitud_dieta_id,
                                                    DS.estado_dieta,
                                                    DS.motivo_cancelacion_dieta,
                                                    DS. usuario_id_cancelacion,
                                                    DTS.descripcion_solicitud,
                                                    C.pieza,
                                                    DSD.tipo_cama_id,
                                                    DSD.hc_dieta_id,
                                                    DSD.observacion as observaciones_dieta,
                                                    DSD.sw_ayuno,
                                                    DSD.sw_adicional,
                                                    DSD.sw_recibida,
                                                    DSD.usuario_recibe,
                                                    DSD.fecha_recibida,
                                                    HTD.descripcion AS descripcion_tipo_dieta,
                                                    p.primer_nombre ||' '||p.segundo_nombre ||' '|| p.primer_apellido ||' '|| p.segundo_apellido AS nombre
                                            FROM    movimientos_habitacion MH
                                                    LEFT JOIN dietas_solicitud DS
                                                    ON (MH.ingreso = DS.ingreso_id
                                                    $excalibur),
                                                    dietas_solicitud_detalle DSD,
                                                    hc_tipos_dieta HTD,
                                                    
                                                    camas C,
                                                    dietas_tipos_solicitud DTS,
                                                    ingresos i,
                                                    pacientes p,
                                                    estaciones_enfermeria EE
                                            WHERE   MH.estacion_id = DS.estacion_id AND
                                                    MH.fecha_egreso ISNULL  AND
                                                    MH.ingreso = DSD.ingreso_id AND
                                                    DS.fecha_solicitud = DSD.fecha_solicitud AND
                                                    DS.tipo_solicitud_dieta_id = DSD.tipo_solicitud_dieta_id  AND
                                                    DS.estacion_id = DSD.estacion_id AND
                                                    DSD.hc_dieta_id =HTD.hc_dieta_id AND
                                                    $dieta_adicional
                                                    $confirmacion
                                                    C.cama= MH.cama AND
                                                    DTS.tipo_solicitud_dieta_id = DSD.tipo_solicitud_dieta_id AND
                                                    mh.ingreso = i.ingreso AND
                                                    i.tipo_id_paciente = p.tipo_id_paciente AND
                                                    i.paciente_id = p.paciente_id AND
                                                    EE.estacion_id = MH.estacion_id
                                                    $condicion_estacion
                                                    $condicion_fec_solicitud
                                                    $condicion_tipos_dieta
                                                    $condicion_tipo_solicitud
                                                    $condicion_rangos_solicitud
                                          ) AS A
                                          LEFT JOIN hc_solicitudes_dietas_ayunos MAYU
                                          ON (A.ingreso_id=MAYU.ingreso
                                              AND A.fecha_solicitud=MAYU.fecha)
                                          LEFT JOIN dietas_solicitud_detalle_caracteristicas DSDC
                                          ON (A.tipo_solicitud_dieta_id = DSDC.tipo_solicitud_dieta_id AND
                                              A.fecha_solicitud = DSDC.fecha_solicitud AND
                                              A.estacion_id = DSDC.estacion_id AND
                                              A.ingreso_id = DSDC.ingreso_id)
                                  ORDER BY A.ingreso_id, A.tipo_solicitud_dieta_id
                                ) AS B
                                LEFT JOIN hc_solicitudes_dietas_caracteristicas HSDC
                                ON  (B.caracteristica_id = HSDC.caracteristica_id)
                        $condicion_caract_dieta
                        ORDER BY $condicion_orden ";

        list($dbconn) = GetDBconn();
        global $ADODB_FETCH_MODE;
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($query);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        if ($dbconn->ErrorNo() != 0)
        {
          $this->error = "Error al ejecutar la conexion";
          $this->mensajeDeError = "Ocurri�un error al intentar consultar los TxD<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
          return false;
        }
        else
        {
            $I=0;
            if($tipo == '1')
            {
                while($fila=$result->FetchRow())
                {
                  $vector[$fila['descripcion_estacion']][$fila['ingreso_id']][$fila['pieza']."-".$fila['tipo_cama_id']][$fila['descripcion_solicitud']][$fila['nombre']][$fila['sw_ayuno']][$fila['observaciones_dieta']][$fila['descripcion_tipo_dieta']][] = $fila;
                }
            }
            else
            {
                while($fila=$result->FetchRow())
                {
                    $ingresos[$fila['ingreso_id']][$fila['tipo_solicitud_dieta_id']][$fila['hc_dieta_id']]['CARACTERISTICAS'][$fila['caracteristica_id']]=$fila['caracteristica_id'];

                }
                $result->Close();

                $vector = $ingresos;
            }
        }
        return $vector;
    }
	/**
	*	Consulta las dietas de los pacientes asiganadas en las estaciones
	*
	* @param 	$tipos_dieta					tipo de dieta asignada al paciente
	* @param 	$tipo_solicitud			Tipo de dieta solicitada, desayuno, almuerzo  o comida
	* @param 	$caract_dieta
		@return	array
	*/
	function GetValorCaracteristica($caracteristica_id)
	{
			if(empty($this->caracteristicas))
			{			
				list($dbconn) = GetDBconn();	

				$query = "SELECT caracteristica_id, descripcion FROM hc_solicitudes_dietas_caracteristicas";		
				
				global $ADODB_FETCH_MODE;
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;		
				$result = $dbconn->Execute($query);
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				
				if ($dbconn->ErrorNo() != 0){
						echo "error";
						$this->error = "Error al ejecutar la conexion";
						$this->mensajeDeError = "Ocurri�un error al intentar consultar los TxD<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
						return false;
				}
				
				$I=0;
				while($fila=$result->FetchRow())
				{
					$this->caracteristicas[$fila['caracteristica_id']]['INDICE'] = $I;
					$this->caracteristicas[$fila['caracteristica_id']]['DESCRIPCION'] = $fila['descripcion'];
					$this->caracteristicas[$fila['caracteristica_id']]['VALOR'] = pow(2,$I);
					$I++;
				}
				$result->Close();								
			}
			return $this->caracteristicas[$caracteristica_id];
	}	
	
	/**
	*
	*/
	function GetCadenaCaracteristicas($valor)
	{
		$cadena='';
		foreach($this->caracteristicas as $k=>$v)
		{
			if($valor & $v['VALOR'])
			{
				$cadena.= $v['DESCRIPCION'] . ", ";
			}
		}
		return $cadena;
	}
	
	/**
	*
	*/
	function GetDescripcionSolicitud($tiposolicitud){
		 $query="
						SELECT descripcion_solicitud
						FROM	dietas_tipos_solicitud
						WHERE	tipo_solicitud_dieta_id = '".$tiposolicitud."'
		";
		list($dbconn) = GetDBconn();
    $result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
        $this->error = "Error al consultar dietas_tipos_solicitud";
        $this->mensajeDeError = "Ocurri�un error al intentar consultar dietas_tipos_solicitud<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
        return false;
    }else{
			$datos=$result->fields[0];
		}
		return $datos;
	}
	/**
	*
	*/
	function GetDescripcionDieta($tipodieta){
		$query="
						SELECT descripcion
						FROM	hc_tipos_dieta
						WHERE	hc_dieta_id = '".$tipodieta."'
		";
		list($dbconn) = GetDBconn();
    $result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
        $this->error = "Error al consultar hc_tipos_dieta";
        $this->mensajeDeError = "Ocurri�un error al intentar consultar hc_tipos_dieta<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
        return false;
    }else{
			$datos=$result->fields[0];
		}
		return $datos;
	}
	
	/**
	*
	*/
	function ConsultaEstaciones($empresa_id){
		$query="SELECT b.estacion_id, b.descripcion
							FROM	departamentos a,
										estaciones_enfermeria b
							WHERE a.empresa_id =  '$empresa_id'  AND
										b.departamento = a.departamento
							
							ORDER BY b.descripcion";
			 list($dbconn) = GetDBconn();
       $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Consulta estaciones_enfermeria";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
				while(!$result->EOF)
				{
						$vector[]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
				}
			
			return $vector;
	}
	
	function ConsultaNombreUsuario($usuario_id)
	{
		list($dbconnect) = GetDBconn();
		$query= "SELECT usuario FROM system_usuarios
		WHERE  usuario_id= ".$usuario_id."";
	
		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
				$this->error = "Error al Consultar el nombre del usuario";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
		}
		$a=$result->GetRowAssoc($ToUpper = false);
		return $a;
	}
}

?>