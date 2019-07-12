<?php
  /**
  * $Id: Etiquetas_Totaldetallado.report.php,v 1.1 2009/06/04 20:31:24 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
  *  Reporte de prueba formato HTML
  */
  class Etiquetas_Totaldetallado_report 
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
    function Etiquetas_Totaldetallado_report($datos=array())
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
  
      $stl = "font-family: sans_serif, Verdana, helvetica, Arial;font-size: 13px; font-weight: bold";
      $st2 = "font-family: sans_serif, Verdana, helvetica, Arial;font-size: 10px; font-weight: bold";
      $datos=$this->ConsultaDietasEstaciones('1','','','','',$empresa_id['tipo_solicitud'],'','','',$empresa_id['sw_adicional']);
      if(!empty($datos))
      {
        $i = 0;
        $scpt  = "<script type=\"text/javascript\" src=\"../javascripts/NiftyCube/niftycube.js\"></script>\n";
        $scpt .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"../javascripts/NiftyCube/niftyCorners.css\">\n";
  
        $html .= "<table width=\"100%\" align=\"center\" cellpadding=\"10\">\n";
        $divs = "";
        $j = 0;
        foreach($datos AS $tipo_dieta => $dtl1)
        {
          foreach($dtl1 AS $k1 => $dtl11)
          {
            (!$divs)? $divs = "div#contents_".$j: $divs .= ",div#contents_".$j;
            $cbcr = "";
            $body = "";
            $ctr = "";
            foreach($dtl11 AS $k5 => $dtl2)
            {
              (!$ctr)? $ctr = $dtl2['descripcion_caracteristica']:$ctr .= ",".$dtl2['descripcion_caracteristica'];
              $cbcr  = "        <tr >\n";
              $cbcr .= "          <td width=\"20%\" align=\"center\">\n";
              $cbcr .= "            <img src=\"images/cafeteria.png\" height=\"60\">\n";
              $cbcr .= "          </td>\n";
              $cbcr .= "          <td width=\"%\" align=\"center\">\n";
              $cbcr .= "            <table style=\"".$stl."\" width=\"100%\" align=\"center\">\n";
              $cbcr .= "              <tr align=\"center\">\n";
              $cbcr .= "                <td >\n";
              $cbcr .= "                  <div id=\"contents_".$j."\" style=\"width:250px;padding:5px 0;".(($dtl2['color_nombre1'])?"background: ".$dtl2['color_nombre1'].";":"")."".(($dtl2['color_letra1'])?"color:".$dtl2['color_letra1']:"")."\">\n";
              $cbcr .= "                    ".$tipo_dieta."\n";
              if($ctr != "") $cbcr .= "<br>";
              $cbcr .= "                    <label style=\"".$st2."\">".$ctr."</label>\n";
              $cbcr .= "                  </div>\n";
              $cbcr .= "                </td>\n";
              $cbcr .= "              </tr>\n";
              $cbcr .= "            </table>\n";
              $cbcr .= "          </td>\n";
              $cbcr .= "          <td width=\"20%\" align=\"center\">\n";
              $cbcr .= "            <img src=\"images/logocliente.png\" height=\"50\">\n";
              $cbcr .= "          </td>\n";   
              $cbcr .= "        </tr>\n";
              $cbcr .= "        <tr>\n";
              $cbcr .= "          <td colspan=\"3\">\n";
              $cbcr .= "            <table width=\"100%\" bordercolor=\"#000000\" align=\"center\" cellpading=\"0\" cellspacing=\"0\" border=\"1\" rules=\"all\">\n";
              $cbcr .= "              <tr class=\"label\">\n";
              $cbcr .= "                <td colspan=\"4\" width=\"80%\"> Nombre: ".$dtl2['nombre']." - ".$dtl2['tipo_cama_id']."</td>\n";
              $cbcr .= "                <td>FECHA</td>\n";
              $cbcr .= "                <td align=\"center\">".$dtl2['dia_solicitud']."</td>\n";
              $cbcr .= "                <td align=\"center\">".$dtl2['mes_solicitud']."</td>\n";
              $cbcr .= "                <td align=\"center\">".$dtl2['year_solicitud']."</td>\n";
              $cbcr .= "              </tr>\n";
              $cbcr .= "              <tr class=\"label\" align=\"center\">\n";
              $cbcr .= "                <td width=\"10%\">D</td>\n";
              $cbcr .= "                <td width=\"10%\">A</td>\n";
              $cbcr .= "                <td width=\"10%\">C</td>\n";
              $cbcr .= "                <td width=\"%\" colspan=\"5\">Observaciones</td>\n";
              $cbcr .= "              </tr>\n";
              
              $body  = "              <tr class=\"label\" >\n";
              $body .= "                <td align=\"center\">".(($dtl2['tipo_solicitud_dieta_id'] == '1')? "X":"&nbsp;")."</td>\n";
              $body .= "                <td align=\"center\">".(($dtl2['tipo_solicitud_dieta_id'] == '2')? "X":"&nbsp;")."</td>\n";
              $body .= "                <td align=\"center\">".(($dtl2['tipo_solicitud_dieta_id'] == '3')? "X":"&nbsp;")."</td>\n";
              $body .= "                <td colspan=\"5\">".$dtl2['observaciones_dieta']."</td>\n";
              $body .= "              </tr>\n";
              $body .= "            </table>\n";
              $body .= "          </td>\n";   
              $body .= "        </tr>\n";
            }

            if($i == 0)
            {
              $i = 1; $html .= "  <tr valign=\"top\">\n";
            }
            else
              $i = 0;
            
            $html .= "    <td width=\"50%\">\n";
            $html .= "      <table width=\"90%\" align=\"center\" cellpading=\"0\" cellspacing=\"0\" border=\"0\" >\n";
            $html .= $cbcr;
            $html .= $body;
            $html .= "      </table>\n";
            $html .= "    </td>\n";
            if( $i == 0)
              $html .= " </tr>\n";
            $j++;
          }
        }
        
        if($j % 2 == 1)
        {
          $html .= "    <td width=\"50%\">&nbsp;</td>\n";
          $html .= "  </tr>\n";
        }
        $html .="</table>\n";
        
        $scpt .= "<script type=\"text/javascript\">\n";
        $scpt .= "  window.onload=function()\n";
        $scpt .= "  {\n";
        $scpt .= "   Nifty(\"".$divs."\");\n";
        $scpt .= "  }\n";
        $scpt .= "</script>\n";
        $html = $scpt.$html;
      }
      return $html ;
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
                SELECT  B.*,
                        HSDC.descripcion AS descripcion_caracteristica,
                        HSDC.descripcion_agrupamiento
                FROM    ( SELECT  A.*,
                                  DSDC.caracteristica_id,
                                  MAYU.motivo as motivo_ayuno
                          FROM    (
                                    SELECT  MH.estacion_id,
                                            EE.descripcion AS descripcion_estacion,
                                            DS.ingreso_id,
                                            DS.fecha_solicitud,
                                            TO_CHAR(DS.fecha_solicitud,'DD') AS dia_solicitud,
                                            TO_CHAR(DS.fecha_solicitud,'MM') AS mes_solicitud,
                                            TO_CHAR(DS.fecha_solicitud,'YY') AS year_solicitud,
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
                                            p.primer_nombre ||' '||p.segundo_nombre ||' '|| p.primer_apellido ||' '|| p.segundo_apellido AS nombre,
                                            CL.color_nombre1,
                                            CL.color_letra1
                                    FROM    movimientos_habitacion MH
                                            LEFT JOIN dietas_solicitud DS
                                            ON (MH.ingreso = DS.ingreso_id
                                            $excalibur),
                                            dietas_solicitud_detalle DSD,
                                            hc_tipos_dieta HTD,
                                            colores CL,
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
                                            HTD.color_id = CL.color_id AND
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
        //$dbconn->debug = true;
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
          while($fila=$result->FetchRow())
          {
            $vector[$fila['descripcion_tipo_dieta']][$fila['ingreso_id']][] = $fila;
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