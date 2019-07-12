<?php
	/**
	* $Id: app_Cafeteria_user.php,v 1.5 2009/06/04 20:31:24 hugo Exp $
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* $Revision: 1.5 $ 	
	* @author 
	*
	*/
  IncludeClass("ConexionBD");
  class app_Cafeteria_user extends classModulo
  {
    var $caracteristicas;
    /**
    * Constructor de la clase
    */
  	function app_Cafeteria_user()
  	{
  		$this->limit=GetLimitBrowser();
      SessionSetVar("rutaImagenes",GetThemePath());
      return true;
  	}
    /**
    * Muestra el menu de las empresas
    *
    * @access public
    */
    function main()
    {
      SessionSetVar("rutaImagenes",GetThemePath());
      unset($_SESSION['cafeteria']);
      $empresas=$this->BuscarCaEmpresasUsuario();
      $mtz[0]='EMPRESAS';
      $url[0]='app';							//contenedor
      $url[1]='Cafeteria';						//módulo
      $url[2]='user';							//clase
      $url[3]='FrmConsulta';	//método 
      $url[4]='permisocafeteria';			//indice del request
      $this->salida = gui_theme_menu_acceso('Dietas', $mtz, $empresas, $url, ModuloGetURL('system','Menu'));
      
      return true;
    }
  /*********************************************************************************
  * Retorna las empresas a las cuales tiene permisos el usuario de acceder
  * 
  * @access public
  *********************************************************************************/
	function BuscarCaEmpresasUsuario()
  {   
    $conn = new ConexionBD();
    //$conn->debug=true;
    $sql .= " SELECT E.empresa_id, ";
    $sql .= "        E.razon_social, ";
    $sql .= "        D.sw_confirmada ";
    $sql .= " FROM userpermisos_dietas D, ";
    $sql .= "      empresas E ";
    $sql .= " WHERE D.empresa_id = E.empresa_id ";
    $sql .= "   AND D.usuario_id = ".UserGetUID()." ";
    $sql .= "   AND sw_activo = '1'; ";
    
    if(!$rst = $conn->ConexionBaseDatos($sql))
				return false;

    while(!$rst->EOF)
    {
      $empresas[$rst->fields[1]]=$rst->GetRowAssoc($ToUpper = false);
      $rst->MoveNext();
    }
    $rst->Close();
    return $empresas;
  }
  
  //    ingreso_id  fecha_solicitud     tipo_solicitud_dieta_id     estacion_id     tipo_cama_id    hc_dieta_id     sw_fraccionada  sw_ayuno    observacion     fecha_registro  usuario_id_registro     sw_adicional    sw_recibida     usuario_recibe  fecha_recibida

    function ConfirmarSN($ingreso_id,$fecha_solicitud,$tipo_solicitud_dieta_id,$estacion_id,$sw,$user)
    {
        $query="UPDATE  dietas_solicitud_detalle
                SET     sw_recibida = '".$sw."',
                        usuario_recibe = ".$user.",
                        fecha_recibida = NOW()
                        
                WHERE   ingreso_id  = ".$ingreso_id."
                AND     fecha_solicitud = '".$fecha_solicitud."'
                AND     tipo_solicitud_dieta_id = ".$tipo_solicitud_dieta_id."
                AND     estacion_id = '".$estacion_id."'";
                list($dbconn) = GetDBconn();
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al actualizar Confirmacion ";
                    $this->mensajeDeError = "Ocurri�un error al actualizar CierreDesayuno <br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    return false;
                }
        
                return true;
    }
    
    /**
	*
	*/
	function ConsultaEmpresa(){
		$query="	SELECT empresa_id, razon_social
							FROM	empresas
							ORDER BY razon_social";
			 list($dbconn) = GetDBconn();
       $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Consulta empresas";
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
	/**
	*
	*/
	function ConsultaTiposDieta(){
		$query="SELECT 	hc_dieta_id, descripcion,abreviatura
							FROM	hc_tipos_dieta
							ORDER BY descripcion";
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
	/**
	*
	*/
	function ConsultaTiposSolicitud(){
		$query="SELECT 	tipo_solicitud_dieta_id, descripcion_solicitud
							FROM	dietas_tipos_solicitud
							ORDER BY descripcion_solicitud";
			 list($dbconn) = GetDBconn();
       $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Consulta de dietas_tipos_solicitud";
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
	/**
	*
	*/
	function BuscaDatos(){
		$empresa_id=$_REQUEST['empresa'];
		$tipos_consulta=$_REQUEST['Consulta'];
		
		if($tipos_consulta=='TxD'){
			$this->InfTotalxDieta($empresa_id);
		}elseif($tipos_consulta=='TxE'){
			$this->InfTotalxEstacion($empresa_id);
		}else{
			$this->InfTotalDetallado($empresa_id);
		}

		
		return true;
	}

	/**
	*
	*/
	function ConsultaCaracteristicaDieta()
	{
		$query="
						SELECT 	caracteristica_id, descripcion, descripcion_agrupamiento
						FROM		hc_solicitudes_dietas_caracteristicas
						WHERE		sw_activo = '1'
		";
		list($dbconn) = GetDBconn();
		
		global $ADODB_FETCH_MODE;
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0){
        $this->error = "Error al consultar caracteisticas dietas";
        $this->mensajeDeError = "Ocurri�un error al intentar consultar hc_solicitudes_dietas_caracteristicas<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
        return false;
    }else{
			while(!$result->EOF)
			{
				$datos[]=$result->FetchRow();
			}
		}
		return $datos;
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
	*	Consulta las dietas de los pacientes asiganadas en las estaciones
	*
	* @param	$tipo								
	* @param 	$estacion						Identificador de la estacion
	* @param 	$fecha_solicitud			Fecha en que se solicito la dieta
	* @param 	$tipos_dieta					tipo de dieta asignada al paciente
	* @param 	$tipo_solicitud			Tipo de dieta solicitada, desayuno, almuerzo  o comida
	* @param 	$fec_solicitud_ini		Rango min. para la solicitud de dietas
	* @param 	$fec_solicitud_fin		Rango max. para la solicitud de dietas
	* @param	$ordena_por				Ordenamiento 1=pieza y cama, 2=tipo dieta y caracteristica
    * @param    $sw_adicional
    * @return	array
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
			//$condicion_fec_solicitud="AND DS.fecha_solicitud = '2009-04-23'";
		}
		else{
			$condicion_fec_solicitud="AND DS.fecha_solicitud = '".date("Y-m-d")."'";
			//$condicion_fec_solicitud="AND DS.fecha_solicitud = '2009-04-23'";
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
								(SELECT 	A.*,
												DSDC.caracteristica_id,
                                                MAYU.motivo as motivo_ayuno

								FROM
											(SELECT 	MH.estacion_id,
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
											FROM	movimientos_habitacion MH
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
											WHERE			MH.estacion_id = DS.estacion_id AND
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
        //$query;
        
        
        list($dbconn) = GetDBconn();
        //$dbconn->debug=true;
		global $ADODB_FETCH_MODE;
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0){
				echo "error";
        $this->error = "Error al ejecutar la conexion";
        $this->mensajeDeError = "Ocurri�un error al intentar consultar los TxD<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
        return false;
    }else{
			$I=0;
			if($tipo == '1')
            {
				while($fila=$result->FetchRow())
                {

                    $vector[$fila['descripcion_estacion']][$fila['ingreso_id']][$fila['pieza']."-".$fila['tipo_cama_id']][$fila['descripcion_solicitud']][$fila['nombre']][$fila['sw_ayuno']][$fila['observaciones_dieta']][$fila['descripcion_tipo_dieta']][] = $fila;
                    //$result->GetRowAssoc($ToUpper = false)
                    //$result->MoveNext();



                    //$datos=$result->FetchRow();
					//$vector[$datos['descripcion_estacion']] [$datos['pieza']] [$datos['tipo_cama_id']] ['nombre']=$datos['nombre'];
					//$vector[$datos['descripcion_estacion']] [$datos['pieza']] [$datos['tipo_cama_id']] ['solicitud'][$datos['descripcion_solicitud']][$datos['descripcion_tipo_dieta'] ][$I]=$datos['descripcion_caracteristica']." ".$datos['descripcion_agrupamiento']." ".$datos['observaciones_dieta'];
                    //$vector[$datos['descripcion_estacion']] [$datos['pieza']] [$datos['tipo_cama_id']] ['ayuno']=$datos['sw_ayuno'];
                    //$vector[$datos['descripcion_estacion']] [$datos['pieza']] [$datos['tipo_cama_id']] ['obser_ayuno']=$datos['motivo'];
                    //$I++;
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
		//print_r($vector);
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
	function CierreDesayuno()
	{
		$query="UPDATE	dietas_solicitud
						SET			fecha_cierra = 'now()',
										usuario_id_cierre = '".UserGetUID()."'
						WHERE  	fecha_solicitud  = '".date("Y-m-d")."' AND
										tipo_solicitud_dieta_id = '1'";
		list($dbconn) = GetDBconn();
		$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
				echo "error";
				$this->error = "Error al actualizar CierreDesayuno ";
				$this->mensajeDeError = "Ocurri�un error al actualizar CierreDesayuno <br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
		}
		$this->FrmConsulta($_REQUEST['empresa_id']);
		return true;
	}
	/**
	*
	*/
	function CierreAlmuerzo()
	{
		$query="UPDATE	dietas_solicitud
						SET			fecha_cierra = 'now()',
										usuario_id_cierre = '".UserGetUID()."'
						WHERE  	fecha_solicitud  = '".date("Y-m-d")."' AND
										tipo_solicitud_dieta_id = '2'";
		list($dbconn) = GetDBconn();
		$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
				echo "error";
				$this->error = "Error al actualizar CierreAlmuerzo ";
				$this->mensajeDeError = "Ocurri�un error al actualizar CierreAlmuerzo <br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
		}
		$this->FrmConsulta($_REQUEST['empresa_id']);
		return true;
	}
	/**
	*
	*/
	function CierreComida()
	{
		$query="UPDATE	dietas_solicitud
						SET			fecha_cierra = 'now()',
										usuario_id_cierre = '".UserGetUID()."'
						WHERE  	fecha_solicitud  = '".date("Y-m-d")."' AND
										tipo_solicitud_dieta_id = '3'";
		list($dbconn) = GetDBconn();
		$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
				echo "error";
				$this->error = "Error al actualizar CierreComida ";
				$this->mensajeDeError = "Ocurri�un error al actualizar CierreComida <br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
		}
		$this->FrmConsulta($_REQUEST['empresa_id']);
		return true;
	}
}//end of class

?>
