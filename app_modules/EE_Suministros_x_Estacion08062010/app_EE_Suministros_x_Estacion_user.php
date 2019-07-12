<?php

/**
 * $Id: app_EE_Suministros_x_Estacion_user.php,v 1.4 2005/12/23 16:24:37 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author  Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
 * @package IPSOFT-SIIS
 */

class app_EE_Suministros_x_Estacion_user extends classModulo
{

     /**
     * Valida si el usuario esta logueado en La Estacion de Enfermeria y si tiene permiso
     * Para este componente ('01'= Admision - Asignacion Cama)
     *
     * @return boolean
     * @access private
     */
     function GetUserPermisos($componente=null)
     {
          $estacion_id = $_SESSION['EE_PanelEnfermeria']['ESTACION_SELECCIONADA'][UserGetUID()];
          
          if($componente)
          {
               if(!empty($_SESSION['EE_PanelEnfermeria']['ESTACIONES_USUARIO'][UserGetUID()][$estacion_id]['COMPONENTES'][$componente]))
               {
                    return true;
               }
               else
               {
                    return null;
               }
          }
     
          if(!empty($_SESSION['EE_PanelEnfermeria']['ESTACIONES_USUARIO'][UserGetUID()][$estacion_id]))
          {
               return true;
          }
          else
          {
               return null;
          }
     }

     /**
     * Retorna los datos de la estacion de enfermeria actual.
     *
     * @return array
     * @access private
     */
     function GetdatosEstacion()
     {
          $estacion_id = $_SESSION['EE_PanelEnfermeria']['ESTACION_SELECCIONADA'][UserGetUID()];
          return $_SESSION['EE_PanelEnfermeria']['DATOS_ESTACION'][$estacion_id];
     }

          
     /* 
     * Llamar forma de Confirmacion de suministros X EE
     * @Tizziano Perea.
     */
     function CallConSuministros_x_estacion()
     {
          if(!$this->ConSuministros_x_estacion($_REQUEST['datos_estacion'],$_REQUEST['bodega'],$_REQUEST['switche']))
          {
               $this->error = "Error al ejecutar el modulo";
               $this->mensajeDeError = "Ocurrió un error al intentar llamar la funcion 'ConSuministros_x_estacion'";
               return false;
          }
          return true;
     }
     
     
     /* 
     * Llamar forma de Solicitud de suministros X EE
     * @Tizziano Perea.
     */
     function CallSolSuministros_x_estacion()
     {
          if(!$this->SolSuministros_x_estacion($_REQUEST['datos_estacion'],$_REQUEST['bodega'],$_REQUEST['switche']))
          {
               $this->error = "Error al ejecutar el modulo";
               $this->mensajeDeError = "Ocurrió un error al intentar llamar la funcion 'InsumosXrecibir'";
               return false;
          }
          return true;
     }


     /**
	*		GetEstacionBodega
	*		obtiene la estacion asociada a una bodega.
	*
	*		@Author Jairo Duvan Diaz M.
	*		@return array, false ó string
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	function GetEstacionBodega($datos,$sw)
     {
		if($sw==1)
		{
			$filtro="AND b.sw_consumo_directo='0'";
		}
		elseif($sw==2)
		{
			$filtro="AND b.sw_consumo_directo='1'";
		}
		list($dbconn) = GetDBconn();
     	$query="SELECT a.empresa_id,a.centro_utilidad,a.bodega,b.descripcion
	             FROM bodegas_estaciones a,bodegas b
                  WHERE  a.estacion_id='".$datos[estacion_id]."'
                  AND a.centro_utilidad=b.centro_utilidad
                  AND a.empresa_id=b.empresa_id
                  AND a.bodega=b.bodega
                  $filtro
                  AND a.centro_utilidad='".$datos[centro_utilidad]."'
                  AND a.empresa_id='".$datos[empresa_id]."'";
          $resulta=$dbconn->execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
     	$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de medicamentos recetados";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          	return false;
		}

          if($result->EOF)
          {
               return '';
          }
          $i=0;
          while (!$result->EOF)
          {
               $vector[$i]=$result->GetRowAssoc($ToUpper = false);
               $result->MoveNext();
               $i++;
          }
     	return $vector;
	}
     
     
     /*$num es el numero de opcion que escogio en el combo */
	/*$busca es la busqueda*/
	function GetFiltro($num,$busca)
	{
          switch($num)
          {
               case "1":
               {
                    $buscar = trim($busca);
                    if(is_numeric($buscar))
                    {
                         $filtro="AND a.codigo_producto like '%".$buscar."%'";
                     }
                    else
                    {
                         $filtro="";
                    }
                    break;
               }
               case "2":
               {
	               $buscar = strtolower(trim($busca));
                    if(!empty($buscar))
                    {
                         $filtro="AND lower(b.descripcion) like '%".$buscar."%'";
                    }
                    break;
               }
          }
          return $filtro;
	}

     
     /*
     * funcion que trae los insumos de cada bodega asociada a la EE.
     */
	function Get_SuministrosEstacion($bodega,$filtro,$cod,$sw)
	{
          $this->limit=GetLimitBrowser();
          list($dbconn) = GetDBconn();
		
          if($bodega=='-1')
		{$filtro_bodega="";}else{$filtro_bodega="AND a.bodega='$bodega'";}
		
		if(empty($_REQUEST['conteo'])){
               $query = "SELECT b.descripcion,b.descripcion_abreviada,producto_id,
               			  a.codigo_producto
                         FROM
                              existencias_bodegas a,
                              inventarios_productos b,
                              inv_grupos_inventarios c
                         WHERE 
                              a.codigo_producto=b.codigo_producto
                              AND c.grupo_id=b.grupo_id
                              AND (c.sw_insumos='1' OR c.sw_medicamento='1')
                         $filtro_bodega
                         $filtro";
     
               $result = $dbconn->Execute($query);
               list($this->conteo)=$result->RecordCount();
               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
          
          	$this->conteo=$result->RecordCount();
          }else{
      		$this->conteo=$_REQUEST['conteo'];
		}
		
          if(!$_REQUEST['Of']){
      	$Of='0';
		}else{
          $Of=$_REQUEST['Of'];
		}
		
		if($bodega=="-1" OR empty($bodega))
		{
			return '';
		}

          if($cod != 0)
          {
          	$filtro_temp = "AND a.codigo_producto = '".$cod."'";
               $limites = "";
          }
          
          if(empty($filtro) AND $sw == 0){ $limites = "LIMIT " . $this->limit . " OFFSET $Of";}
          elseif(empty($filtro) AND $sw == 1){$limites = "";}

          $query="SELECT b.descripcion,b.descripcion_abreviada,producto_id,
          		     a.codigo_producto, d.descripcion as unidad
                  FROM
                  		existencias_bodegas a,
                         inventarios_productos b,
                    	inv_grupos_inventarios c,
                         unidades d
                  WHERE 
                  		a.codigo_producto=b.codigo_producto
                    	AND c.grupo_id=b.grupo_id
                    	AND (c.sw_insumos='1' OR c.sw_medicamento='1')
                         AND d.unidad_id = b.unidad_id
                    	$filtro_bodega
                    	$filtro
                         $filtro_temp
                         $limites;";

		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de medicamentos recetados";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      		return false;
		}
          $i=0;
          while (!$result->EOF)
          {
               $vector[$i]=$result->GetRowAssoc($ToUpper = false);
               $result->MoveNext();
               $i++;
          }
          return $vector;
	}//
     
     
     /**
	*	GetEstacion_BodegaReposicionManual
	*
	*	obtiene la estacion asociada a una bodega.
	*
	*	@Author Tizziano Perea.
	*	@access Public
	*	@return array, false ó string
	*	@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	function GetEstacion_BodegaReposicionManual($datos,$bodega_Cargar)
	{
		list($dbconn) = GetDBconn();
          if($bodega_Cargar)
          {
          	$filtrar = "AND a.bodega = '$bodega_Cargar'";
          }
     	
          $query="SELECT a.empresa_id,a.centro_utilidad,a.bodega,b.descripcion
                    FROM bodegas_estaciones a,bodegas b
                    WHERE
                         a.estacion_id='".$datos[estacion_id]."'
                         AND a.centro_utilidad=b.centro_utilidad
                         AND a.empresa_id=b.empresa_id
                         $filtrar                         
                         AND a.bodega=b.bodega
                         AND b.sw_restitucion = '0'
                         AND b.sw_consumo_directo = '1'
                         AND a.centro_utilidad='".$datos[centro_utilidad]."'
                         AND a.empresa_id='".$datos[empresa_id]."'";
          $resulta=$dbconn->execute($query);
          if ($dbconn->ErrorNo() != 0) {
                         $this->error = "Error al Cargar el Modulo";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         return false;
          }
     	$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de medicamentos recetados";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      		return false;
		}

          if($result->EOF)
          {
               return '';
          }
          $i=0;
          while (!$result->EOF)
          {
               $vector[$i]=$result->GetRowAssoc($ToUpper = false);
               $result->MoveNext();
               $i++;
          }
          return $vector;
	}


     //esta funcion genera la solicitud de los suministros.
	function Solicitar_SuministrosEstacion()
	{
		$datos_estacion=$_REQUEST["datos_estacion"];
          $bodega=$_REQUEST['bodega'];
          $bodega_ConsumoD = $_REQUEST['bodega_ConsumoD'];
          
		$_SESSION['bodega_ConsumoD'] = $bodega_ConsumoD;
          
          if(empty($_SESSION['ESTAR'])){
               $this->frmError["MensajeError"]="DEBE ADICIONAR PRIMERO LAS CANTIDADES DE LA SOLICITUD Y DESPUES GUARDARLAS.";
			$this->SolSuministros_x_estacion($datos_estacion,$bodega);
               return true;
		}
          
          list($dbconn) = GetDBconn();
		$query="SELECT NEXTVAL('public.hc_solicitudes_suministros_estacion_solicitud_id_seq');";
		$res=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "No se pudo traer la secuencia ";
			$this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}

		$solicitud=$res->fields[0];


          $query="INSERT INTO hc_solicitudes_suministros_estacion
                         (    solicitud_id,
                              empresa_id,
                              centro_utilidad,
                              bodega,
                              estacion_id,
                              usuario_id,
                              fecha_registro,
                              bodega_solicita                             
                              )VALUES('$solicitud',
                                   '".$datos_estacion[empresa_id]."',
                                   '".$datos_estacion[centro_utilidad]."',
                                   '".$bodega."',
                                   '".$datos_estacion[estacion_id]."',
                                   ".UserGetUID().",
                                   now(),
                                   '".$bodega_ConsumoD."')";
          $dbconn->StartTrans();
          $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "No se inserto en hc_solicitudes_suministros_estacion";
               $this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
          }

          for($i=1;$i<=sizeof($_SESSION['ESTAR']);$i++)							
          {
               foreach($_SESSION['ESTAR'][$i] as $index=>$valor)
               {
                    $dat_op=explode("*",$_SESSION['ESTAR'][$i][$index]);
                    $query="INSERT INTO hc_solicitudes_suministros_estacion_detalle
                                   (    solicitud_id,
                                        codigo_producto,
                                        cantidad,
                                        cantidad_despachada,
                                        sw_estado
                                   )VALUES('$solicitud',
                                           '".$dat_op[0]."',
                                           '".$dat_op[1]."',
                                           0,
                                           '0')";
                    
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "No se inserto en hc_solicitudes_insumos_d ";
                         $this->mensajeDeError = "Ocurrió un error al insertar la solicitud de insumos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                         $dbconn->RollbackTrans();
                         return false;
                    }

               }
                         
		}
		$dbconn->CompleteTrans();//termina la transaccion
		unset($_SESSION['ESTAR']);
		unset($_SESSION['codigos']);
          unset($_SESSION['cantidad_a_perdi_sol']);
		$this->frmError["MensajeError"]="SUMINISTROS DE ESTACION SOLICITADOS SATISFACTORIAMENTE.";
		$this->SolSuministros_x_estacion($datos_estacion,$bodega);
		return true;
	}


     /*
     * Funcion que mustra los datos de las solicitudes requeridas por la EE.
     */
     function GetSolicitudes_x_Estacion($datos_estacion,$bodega)
     {
	     GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
		$query_busqueda = "SELECT DISTINCT A.* 
          				FROM hc_solicitudes_suministros_estacion A 
                              WHERE estacion_id = '".$datos_estacion[estacion_id]."'
                              AND bodega = ".$bodega."
                              group by bodega_solicita, solicitud_id, empresa_id, centro_utilidad, bodega, 
                              estacion_id, usuario_id, fecha_registro 
                              ORDER BY solicitud_id DESC;";
          
          
          /*SELECT DISTINCT A.*
          				FROM hc_solicitudes_suministros_estacion A 
          				WHERE estacion_id = '".$estacion[estacion_id]."'
                              AND bodega = ".$bodega."
                              ORDER BY solicitud_id DESC;";*/
          
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado = $dbconn->Execute($query_busqueda);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error en la consulta en hc_solicitudes_suministros_estacion";
               $this->mensajeDeError = "Ocurrió un error al insertar la solicitud de insumos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          while ($datos = $resultado->FetchRow())
          {
          	$solicitudes[] = $datos;
          }
          return $solicitudes;
     }
     
     
     /*
     * Funcion que muestra los detalles de las solicitudes requeridas por la EE.
     */
     function GetSuministrosSolicitadosConfirmar_x_Estacion($solicitud)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
		$query_solicitud = "SELECT A.*, B.descripcion, B.descripcion_abreviada, 
          					  C.cantidad, C.confirmacion_id, D.descripcion AS unidad
          				FROM  hc_solicitudes_suministros_estacion_detalle AS A,
                              	 inventarios_productos AS B,
                                    hc_solicitudes_suministros_est_x_confirmar AS C,
                                    unidades AS D
          				WHERE solicitud_id = ".$solicitud."
                              AND A.consecutivo = C.consecutivo
                              AND bodegas_doc_id IS NULL
                              AND numeracion IS NULL
                              AND C.estado='1'
                              AND B.codigo_producto = A.codigo_producto
                              AND (A.sw_estado='1' OR A.sw_estado='2')
                              AND D.unidad_id=B.unidad_id
                              ORDER BY consecutivo ASC;";
          
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado = $dbconn->Execute($query_solicitud);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error en la consulta en hc_solicitudes_suministros_estacion_detalle";
               $this->mensajeDeError = "Ocurrió un error al insertar la solicitud de insumos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          while ($datos = $resultado->FetchRow())
          {
          	$solicitudes[] = $datos;
          }
          return $solicitudes;
     }

     
     /*
     * Funcion que muestra los detalles de las solicitudes aptas para cancelacion por la EE.
     */
     function GetSuministrosSolicitadosCancelar_x_Estacion($solicitud)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
		$query_solicitud = "SELECT A.*, B.descripcion, B.descripcion_abreviada, D.descripcion AS unidad
          				FROM  hc_solicitudes_suministros_estacion_detalle AS A,
                              	 inventarios_productos AS B,
                                    unidades AS D
          				WHERE solicitud_id = ".$solicitud."
                              AND B.codigo_producto = A.codigo_producto
                              AND A.sw_estado='0'
                              AND D.unidad_id=B.unidad_id
                              ORDER BY consecutivo ASC;";
          
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado = $dbconn->Execute($query_solicitud);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error en la consulta en hc_solicitudes_suministros_estacion_detalle";
               $this->mensajeDeError = "Ocurrió un error al insertar la solicitud de insumos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          while ($datos = $resultado->FetchRow())
          {
          	$solicitudes[] = $datos;
          }
          return $solicitudes;
     }

     
     /*
     * Funcion que muestra los detalles de las solicitudes que pueden ser devueltas
     * por la EE y que fueron ya despachadas por la Bodega de la EE.
     */
     function GetSuministrosSolicitados_Devoluciones_x_Estacion($solicitud)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
		$query_solicitud = "SELECT A.*, B.descripcion, B.descripcion_abreviada, C.cantidad,
          					  C.confirmacion_id, D.descripcion AS unidad
          				FROM  hc_solicitudes_suministros_estacion_detalle AS A,
                              	 inventarios_productos AS B,
                                    hc_solicitudes_suministros_est_x_confirmar AS C,
                                    unidades AS D
          				WHERE solicitud_id = ".$solicitud."
                              AND A.consecutivo = C.consecutivo
                              AND bodegas_doc_id IS NOT NULL
                              AND numeracion IS NOT NULL
                              AND C.estado='1'
                              AND B.codigo_producto = A.codigo_producto
                              AND D.unidad_id=B.unidad_id
                              AND (A.sw_estado='1' OR A.sw_estado='2')
                              ORDER BY consecutivo ASC;";
          
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado = $dbconn->Execute($query_solicitud);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error en la consulta en hc_solicitudes_suministros_estacion_detalle";
               $this->mensajeDeError = "Ocurrió un error al insertar la solicitud de insumos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          while ($datos = $resultado->FetchRow())
          {
          	$solicitudes[] = $datos;
          }
          return $solicitudes;
     }

     
     /*
     * Funcion que permite el llamado de las funciones para la confirmacion de los
     * despachos.
     */
     function AccionCancelCon_Solicitud()
     {
     	$opcion = $_REQUEST['opcion'];
          $despacho = $_REQUEST['despachos'];
          $datos_estacion = $_REQUEST['datos_estacion'];
          $bodega = $_REQUEST['bodega'];
          $SWITCHE = $_REQUEST['switche'];
     	
          if($_REQUEST['accion'] == 'confirmar')
          {
               for($i=0; $i<sizeof($opcion); $i++)
          	{
                    $datos = explode(",",$opcion[$i]);
                    if(empty($Bodega1))
                    {
                    	$Bodega1 = $datos[3];
                    }
                    if($Bodega1 != $datos[3])
                    {
                         $this->frmError["MensajeError"]="DEBE CARGAR LOS PRODUCTOS A LA BODEGA DEL MISMO SOLICITANTE.";
                         $this->ConSuministros_x_estacion($datos_estacion,$bodega,$SWITCHE);
                         return true;
                    }
               }
          
			/*if ($_REQUEST['devolver_con'] == 'DEVOLVER')
               {
               	$this->DevolverSolicitud_ConSuministro($opcion,$despacho,$estacion,$bodega,$SWITCHE);
               }*/
               //else
               //{
               	$this->ConfirmarSolicitud_ConSuministro($opcion,$despacho,$datos_estacion,$bodega,$SWITCHE);               
               //}
          }elseif($_REQUEST['accion'] == 'cancelar')
          {
          	$this->CancelarSolicitud_ConSuministro($opcion,$despacho,$datos_estacion,$bodega,$SWITCHE);
          }
          
          return true;
     }
     
     
     /*
     * Funcion que realiza el llamado de la funcion EgresoConfirmacionesSuministros en 
     * InvBodegas y la cual carga los suministros solicitados a las bodegas de Consumo Directo.
     *
     * @autor Tizziano Perea.
     * @param $opcion, $despacho, $estacion, $bodega, $SWITCHE
     */
     function ConfirmarSolicitud_ConSuministro($opcion,$despacho,$datos_estacion,$bodega,$SWITCHE)
     { 
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          for($i=0; $i<sizeof($opcion); $i++)
          {
          	$datos = explode(",",$opcion[$i]);
               $bodega_ConsumoD = $datos[3];
               if($datos[1] != '')
               {
                     $query ="SELECT confirmacion_id 
                    		FROM hc_solicitudes_suministros_est_x_confirmar
	                         WHERE consecutivo = ".$datos[1]."
                              AND bodegas_doc_id IS NULL
                              AND numeracion IS NULL;";
                    
                    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                    $resultado = $dbconn->Execute($query);
                    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                    
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error en la consulta en hc_solicitudes_suministros_estacion_detalle";
                         $this->mensajeDeError = "Ocurrió un error al insertar la solicitud de insumos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                         return false;
                    }
                    while ($datos = $resultado->FetchRow())
                    {
                         $corfirmacion[] = $datos['confirmacion_id'];
                    }
               }
          }
          
          $_SESSION['SUMINISTRO_X_ESTACION']['Empresa'] = $datos_estacion[empresa_id];
          $_SESSION['SUMINISTRO_X_ESTACION']['CentroUtiliE'] = $datos_estacion[centro_utilidad];
          $_SESSION['SUMINISTRO_X_ESTACION']['BodegaE'] = $bodega;
          $_SESSION['SUMINISTRO_X_ESTACION']['CONFIRMACIONES'] = $corfirmacion;
          $_SESSION['SUMINISTRO_X_ESTACION']['CentroUtiliI'] = $datos_estacion[centro_utilidad];
          $_SESSION['SUMINISTRO_X_ESTACION']['BodegaI'] = $bodega_ConsumoD;

          $this->CallMetodoExterno('app','InvBodegas','user','EgresoConfirmacionesSuministros');
          $this->frmError["MensajeError"]= $_SESSION['SUMINISTRO_X_ESTACION']['MENSAJE'];
          $this->ConSuministros_x_estacion($datos_estacion,$bodega,$SWITCHE);
          return true;
     }
   
     
     /*
     * Funcion que permite devolver los suministros ya cargados en 
     * Bodega de Consumo Directo previamente.
     *
     * @autor Tizziano Perea.
     * @param $opcion, $despacho, $estacion, $bodega, $SWITCHE
     */
     function DevolverSolicitud_ConSuministro($opcion,$despacho,$datos_estacion,$bodega,$SWITCHE)
     {
          list($dbconn) = GetDBconn();
          for($i=0; $i<$despacho; $i++)
          {
          	$datos = explode(",",$opcion[$i]);
               if($datos[1] != '')
               {
                    $query ="UPDATE hc_solicitudes_suministros_est_x_confirmar
                             SET estado='2'
                             WHERE confirmacion_id = ".$datos[2].";";

                    $resultado = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error en la consulta en hc_solicitudes_suministros_estacion_detalle";
                         $this->mensajeDeError = "Ocurrió un error al actualizar la solicitud de insumos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                         return false;
                    }
               }
          }
		$this->frmError["MensajeError"]="DEVOLUCION DE SUMINISTROS SATISFACTORIA.";
		$this->ConSuministros_x_estacion($datos_estacion,$bodega,$SWITCHE);
          return true;
     }
     
     
     /*
     * Funcion que permite cancelar los suministros pedidos a las Bodegas de la EE.
     * Esto se realiza antes del despacho de Bodega.
     *
     * @autor Tizziano Perea.
     * @param $opcion, $despacho, $estacion, $bodega, $SWITCHE
     */
	
     function CancelarSolicitud_ConSuministro($opcion,$despacho,$datos_estacion,$bodega,$SWITCHE)
     {
          list($dbconn) = GetDBconn();
          for($i=0; $i<$despacho; $i++)
          {
          	$datos = explode(",",$opcion[$i]);
               if($datos[1] != '')
               {
                    $query ="UPDATE hc_solicitudes_suministros_estacion_detalle
                             SET sw_estado='3'
                             WHERE consecutivo = ".$datos[1].";";

                    $resultado = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error en la consulta en hc_solicitudes_suministros_estacion_detalle";
                         $this->mensajeDeError = "Ocurrió un error al insertar la solicitud de insumos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                         return false;
                    }
               }
          }
		$this->frmError["MensajeError"]="CANCELACION DE SUMINISTROS SATISFACTORIA.";
		$this->ConSuministros_x_estacion($datos_estacion,$bodega,$SWITCHE);
          return true;
     }


     /**
	*	GetEstacionBodega
	*
	*	obtiene la estacion asociada a una bodega.
	*
	*	@Author Jairo Duvan Diaz M.
	*	@access Public
	*	@return array, false ó string
	*	@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	//buscar aqui
	function TraerNombreBodega($datos_estacion,$bodega)
	{
          list($dbconn) = GetDBconn();
	     $query="SELECT descripcion FROM bodegas
                    WHERE empresa_id='".$datos_estacion['empresa_id']."'
				AND centro_utilidad='".$datos_estacion['centro_utilidad']."'
				AND bodega='$bodega'";
          $resulta=$dbconn->execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
     	return $resulta->fields[0]; //empresa
	}

     
     //DARLING
	/**
	* Separa la fecha del formato timestamp
	* @access private
	* @return string
	* @param date fecha
	*/
	 function FechaStamp($fecha)
	 {
          if($fecha){
               $fech = strtok ($fecha,"-");
               for($l=0;$l<3;$l++)
               {
                    $date[$l]=$fech;
                    $fech = strtok ("-");
               }
               return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
          }
	}


	/**
	* Separa la hora del formato timestamp
	* @access private
	* @return string
	* @param date hora
	*/
	function HoraStamp($hora)
	{
          $hor = strtok ($hora," ");
          for($l=0;$l<4;$l++)
          {
               $time[$l]=$hor;
               $hor = strtok (":");
          }

          $x = explode (".",$time[3]);
          return  $time[1].":".$time[2].":".$x[0];
	}

}//end of class

?>
