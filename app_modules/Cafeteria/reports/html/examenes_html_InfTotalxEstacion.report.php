<?php

/**
 * $Id: examenes_html_InfTotalxEstacion.report.php,v 1.2 2009/06/04 20:31:24 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de prueba formato HTML
 */

class examenes_html_InfTotalxEstacion_report 
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
    function examenes_html_InfTotalxEstacion_report($datos=array())
    {
			$this->datos=$datos;
      return true;
    }
		
	
// 	//METODO PRIVADO NO MODIFICAR
// 	function GetParametrosReport()
// 	{
// 		$parametros = array('title' => $this->title,'author' => $this->author,'sizepage' => $this->sizepage,'Orientation'=> $this->Orientation,'grayScale' => $this->grayScale,'headers' => $this->headers,'footers' =>$this->footers )
// 		return $parametros;
// 	}
// 	
// 	
	
	//FUNCION GetMembrete() - SI NO VA UTILIZAR MEMBRETE EXTERNO PUEDE BORRAR ESTE METODO
	//RETORNA EL MEMBRETE DEL DOCUMENTO
	//
	// SI RETORNA FALSO SIGNIFICA EL REPORTE NO UTILIZA MEMBRETE EXTERNO AL MISMO REPORTE.
	// SI RETORNA ARRAY HAY DOS OPCIONES:
	//
	// 1. SI $file='NombreMembrete' EL REPORTE UTILIZARA UN MEMBRETE UBICADO EN
	//    reports/HTML/MEMBRETES/NombreMembrete y el arraglo $datos_membrete
	//    seran los parametros especificos de este membrete.
	//
	//	  EJEMPLO:
	//
	// 			function GetMembrete()
	// 			{
	// 				$Membrete = array('file'=>'NombreMembrete','datos_membrete'=>array('titulo'=>'ESTE ES EL TITULO DEL REPORTE','subtitulo'=>'SUBTITULO'));
	// 				return $Membrete;
	// 			}	
	//
	// 2. SI $file=false  SIGNIFICA QUE UTILIZA UN MEMBRETE GENERICO QUE CONCISTE EN UN
	//    LOGO (SI LO HAY), UN TITULO, UN SUBTITULO Y UNA POSICION DEL LOGO (IZQUIERDA,DERECHA O CENTRO)
	//    LOS PARAMETROS DEL VECTOR datos_membrete DEBN SER:
	//    titulo    : TITULO DE REPORTE
	//    subtitulo : SUBTITULO DEL REPORTE
	//    logo      : LA RUTA DE UN LOGO DENTRO DEL DIRECTORIO images (EN EL RAIZ)
	//    align     : POSICION DEL LOGO (left,center,right)
	//
	//	  EJEMPLO:
	//
	// 			function GetMembrete()
	// 			{
	// 				$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>'ESTE ES EL TITULO DEL REPORTE',
	// 																		'subtitulo'=>'subtitulo'
	// 																		'logo'=>'logocliente.png'
	// 																		'align'=>'left'));
	// 				return $Membrete;
	// 			}
	
// 	function GetMembrete()
// 	{
// 		$Membrete = array('file'=>'MembreteDePrueba','datos_membrete'=>array('titulo'=>'ESTE ES EL TITULO DEL REPORTE',
// 																'subtitulo'=>'subtitulo',
// 																'logo'=>'logocliente.png',
// 																'align'=>'left'));
// 		return $Membrete;
// 	}
	
	function GetMembrete()
	{
		$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>'INFORME DE DIETAS CAFETERIA',
																'subtitulo'=>"INFORME TOTAL DE DIETAS POR ESTACION",
																'logo'=>'logocliente.png',
																'align'=>'left'));
		return $Membrete;
	}
    //FUNCION CrearReporte()
	//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
    function CrearReporte()
    {
			$empresa_id=$this->datos;
			$empresa_id=$empresa_id['empresa_id'];
			$usuario=$this->ConsultaNombreUsuario(UserGetUID());
			$estaciones=$this->ConsultaEstaciones($empresa_id);
			
			$salida.="<table  align=\"left\" border=\"0\"  width=\"100%\">\n";
			$salida.=" <tr>\n";
			$salida.=" <td>\n";
				$salida.="<table  align=\"left\" border=\"0\"  width=\"70%\">\n";
				$salida.=" <tr>\n";
				$salida.="   <td class=\"Normal_10N\" align=\"left\" width=\"20%\">INFORME IMPRESO POR :</td>\n";
				$salida.="   <td class=\"Normal_10\" align=\"left\" width=\"80%\" >".$usuario['usuario']."</td>\n";
				$salida.=" </tr>\n";
				$salida.=" <tr>\n";
				$salida.="   <td class=\"Normal_10N\" align=\"left\" width=\"20%\">FECHA DE IMPRESION :</td>\n";
				$salida.="   <td class=\"Normal_10\" align=\"left\" width=\"80%\" >".date("Y-m-d")."</td>\n";
				$salida.=" </tr>\n";
				$salida.=" <tr>\n";
				$salida.="   <td class=\"Normal_10N\" align=\"left\" width=\"20%\">HORA DE IMPRESION :</td>\n";
				$salida.="   <td class=\"Normal_10\" align=\"left\" width=\"80%\" >".date("h:i")."</td>\n";
				$salida.=" </tr>\n";
				$salida.="</table>\n";
				$salida.="<br>\n";
				$salida.=" </tr>\n";
				$salida.=" <tr>\n";
				
				$salida.="<table  align=\"center\" border=\"1\"  width=\"70%\" >";
				
				foreach($estaciones AS $estacion_id => $estacion)
				{
					$ingresos=$this->ConsultaDietasEstaciones('2',$estacion['estacion_id']);
					//print_r($ingresos);echo "<br>";
					if(!empty($ingresos))
					{
						foreach($ingresos as $k=>$v)
						{
							foreach($v as $solicitud_id => $sol)
							{
								foreach($sol as $dietas_id => $d)
								{
									$valor = 0;
									foreach($d['CARACTERISTICAS'] as $c=>$vc)
									{
										$x=$this->GetValorCaracteristica($vc);
										$valor = $valor + $x['VALOR'];
									}
									$dietas[$solicitud_id][$dietas_id][$valor] = $dietas[$solicitud_id][$dietas_id][$valor]  + 1;
								}
							}
						}
				
						foreach($dietas As $solicitud_id => $d)
						{
							$solicitud_descripcion = $this->GetDescripcionSolicitud($solicitud_id);
							$salida.="	<tr class=\"Normal_10N\">";
							$salida.="		<td colspan = 3 align=\"center\" width=\"100%\">DIETAS PARA : ".$solicitud_descripcion."</td>";
							$salida.="	</tr>";
							$salida.="	<tr class=\"Normal_10N\">";
							$salida.="		<td colspan = 3 align=\"center\" width=\"100%\">ESTACION : ".$estacion['descripcion']."</td>";
							$salida.="	</tr>";
							$salida.="	<tr class=\"Normal_10N\">";
							$salida.="		<td width=\"70%\" align=\"center\">DESCRIPCION</td>";
							$salida.="		<td width=\"30%\" align=\"center\">CANTIDAD</td>";
							$salida.="		<td width=\"30%\" align=\"center\">ENTREGADO</td>";
							$salida.="	</tr>";
							foreach($d AS $dieta_id => $c)
							{
								$dieta_descripcion=$this->GetDescripcionDieta($dieta_id);
								foreach($c AS $caract_id => $card)
								{
									$des_dieta=$dieta_descripcion." ".$this->GetCadenaCaracteristicas($caract_id);
										$salida.="	<tr class=\"Normal_10\">";
										$salida.="		<td width=\"70%\" align=\"left\">".$des_dieta."</td>";
										$salida.="		<td width=\"30%\" align=\"center\">".$card."</td>";
										$salida.="		<td>&nbsp;</td>";
										$salida.="	</tr>";
										$salida.="<tr>&nbsp;</tr>";
								}
							}
						}
					}
				}
			
				$salida.="</table>";
				$salida.=" </tr>\n";
			$salida.=" </td>\n";
			$salida.=" </tr>\n";
			$salida.="</table>\n";
			return $salida;
    }


    
    //AQUI TODOS LOS METODOS QUE USTED QUIERA
    //---------------------------------------
		
		
		/**
	*	Consulta las dietas de los pacientes asiganadas en las estaciones
	*
	* @param	$tipo								
	* @param 	$estacion						Identificador de la estacion
	* @param 	$fecha_solicitud			Fecha en que se solicito la dieta
	* @param 	$tipos_dieta					tipo de dieta asignada al paciente
	* @param 	$tipo_solicitud			Tipo de dieta solicitada, desayuno, almuerzo  o comida
	* @param 	$fec_solicitud_ini		Rango min. para la solicitud de dietas
	* @param 	$fec_solicitud_fin		Rango max. para la solicitud de dietas
	* @param	$ordena_por						Ordenamiento 1=pieza y cama, 2=tipo dieta y caracteristica
		@return	array
	*/
	function ConsultaDietasEstaciones($tipo,$estacion='',$fecha_solicitud='',$tipos_dieta='',$caract_dieta='',$tipo_solicitud='',
																		$fec_solicitud_ini='',$fec_solicitud_fin='',$ordena_por='')
	{
		$condicion_estacion = '';
		$condicion_fec_solicitud = '';
		$condicion_tipos_dieta = '';
		$condicion_tipo_solicitud  = '';
		$condicion_rangos_solicitud = '';
		$condicion_caract_dieta = '';
		$condicion_datos = '';
		
		if($datos=='1'){
			$condicion_datos = "	B.*,
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
		
		if(!empty($ordena_por)){
			if($ordena_por=='1'){
				$condicion_orden= "B.pieza, B.tipo_cama_id";
			}elseif($ordena_por=='2'){
				$condicion_orden = "B.descripcion_tipo_dieta,HSDC.descripcion,HSDC.descripcion_agrupamiento";
			}
		}else{
			$condicion_orden= "B.pieza, B.tipo_cama_id";
		}
		
		$query="
						SELECT B.*,
										HSDC.descripcion AS descripcion_caracteristica,
										HSDC.descripcion_agrupamiento
						FROM
								(SELECT 	A.*,
												DSDC.caracteristica_id
								FROM
											(SELECT 	MH.estacion_id,
															EE.descripcion AS descripcion_estacion,
															DS.ingreso_id,
															DS.fecha_solicitud,
															DS.tipo_solicitud_dieta_id,
															DTS.descripcion_solicitud,
															C.pieza,
															DSD.tipo_cama_id,
															DSD.hc_dieta_id,
															HTD.descripcion AS descripcion_tipo_dieta,
															p.primer_nombre ||' '||p.segundo_nombre ||' '|| p.primer_apellido ||' '|| p.segundo_apellido AS nombre
											FROM	movimientos_habitacion MH
														LEFT JOIN dietas_solicitud DS 
														ON (MH.ingreso = DS.ingreso_id ),
														dietas_solicitud_detalle DSD,
														hc_tipos_dieta HTD,
														camas C,
														dietas_tipos_solicitud DTS,
														ingresos i,
														pacientes p,
														estaciones_enfermeria EE
											WHERE			MH.estacion_id = DS.estacion_id AND
																MH.fecha_egreso ISNULL  AND
																MH.ingreso = DSD.ingreso_id AND
																DS.fecha_solicitud = DSD.fecha_solicitud AND
																DS.tipo_solicitud_dieta_id = DSD.tipo_solicitud_dieta_id  AND
																DS.estacion_id = DSD.estacion_id AND
																DSD.hc_dieta_id =HTD.hc_dieta_id AND
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
											LEFT JOIN	dietas_solicitud_detalle_caracteristicas DSDC
											ON (A.tipo_solicitud_dieta_id = DSDC.tipo_solicitud_dieta_id AND
													A.fecha_solicitud = DSDC.fecha_solicitud AND
													A.estacion_id = DSDC.estacion_id AND
													A.ingreso_id = DSDC.ingreso_id)
								ORDER BY A.ingreso_id, A.tipo_solicitud_dieta_id) AS B
								LEFT JOIN hc_solicitudes_dietas_caracteristicas HSDC
								ON	(B.caracteristica_id = HSDC.caracteristica_id)
						$condicion_caract_dieta
						ORDER BY $condicion_orden
		";
		list($dbconn) = GetDBconn();
		//if ($estacion=='4'){echo "<br>";print_r($query);}
		global $ADODB_FETCH_MODE;
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0){
				echo "error";
        $this->error = "Error al ejecutar la conexion";
        $this->mensajeDeError = "Ocurrió un error al intentar consultar los TxD<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
        return false;
    }else{
			$I=0;
			if($tipo == '1'){
				while(!$result->EOF)
				{
					$datos=$result->FetchRow();
					$vector[$datos['descripcion_estacion']] [$datos['pieza']] [$datos['tipo_cama_id']] ['nombre']=$datos['nombre'];
					$vector[$datos['descripcion_estacion']] [$datos['pieza']] [$datos['tipo_cama_id']] ['solicitud'][$datos['descripcion_solicitud']][$datos['descripcion_tipo_dieta']][$I]=$datos['descripcion_caracteristica']." ".$datos['descripcion_agrupamiento']; 
					$I++;
				}
			}else{
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
						$this->mensajeDeError = "Ocurrió un error al intentar consultar los TxD<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
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
        $this->mensajeDeError = "Ocurrió un error al intentar consultar dietas_tipos_solicitud<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
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
        $this->mensajeDeError = "Ocurrió un error al intentar consultar hc_tipos_dieta<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
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