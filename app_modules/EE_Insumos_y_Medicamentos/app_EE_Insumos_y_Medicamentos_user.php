<?php

/**
 * $Id: app_EE_Insumos_y_Medicamentos_user.php,v 1.4 2010/03/15 18:59:15 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author  Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
 * @package IPSOFT-SIIS
 */

class app_EE_Insumos_y_Medicamentos_user extends classModulo
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
     * Llamar forma de InsumosMed_X_Despachar
     * @Tizziano Perea.
     */
     function CallInsumosMed_X_Despachar()
     {
          if(!$_REQUEST['datos_estacion']) $_REQUEST['datos_estacion'] = $_SESSION['EE_I_y_M']['Estacion'];
          
          if(!$_REQUEST['datosPaciente']) $_REQUEST['datosPaciente'] = $_SESSION['EE_I_y_M']['Pacientes'];
          	
          if(!$this->InsumosMed_X_Despachar($_REQUEST['datos_estacion'],$_REQUEST['bodega'],$_REQUEST['switche'],$_REQUEST['datosPaciente']))
          {
               $this->error = "Error al ejecutar el modulo";
               $this->mensajeDeError = "Ocurrió un error al intentar llamar la funcion 'BuscaMedicamentosPorRecibir'";
               return false;
          }
          return true;
     }
     
     
     /* 
     * Llamar forma de CallMedicamentosIns_X_Recibir
     * @Tizziano Perea.
     */
     function CallMedicamentosIns_X_Recibir()
     {
          if(!$this->MedicamentosIns_X_Recibir($_REQUEST['datos_estacion'],$_REQUEST['bodega'],$_REQUEST['switche']))
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
     

     //funcion que trae los medicamentos solicitados para cancelarlos.
     function GetInsumosPendDesp($ingreso,$estacion,$bodega,$solicitud_id=NULL)
     {
          list($dbconnect) = GetDBconn();
          
          if(!empty($solicitud_id)){
            $sol=" AND x.solicitud_id='$solicitud_id'";
          }
          $query  = "SELECT x.documento_despacho as doc,";
          $query .= "       z.codigo_producto, ";
          $query .= "       z.cantidad, ";
          $query .= "       z.cantidad as cant_solicitada,";
          $query .= "       h.descripcion as producto,";
          $query .= "       l.descripcion,";
          $query .= "       x.solicitud_id,";
          $query .= "       z.consecutivo_d,";
          $query .= "       x.sw_estado as sw, ";
          $query .= "       EB.existencia ";
          $query .= "FROM   existencias_bodegas EB,";
          $query .= "       inventarios_productos as h,";
          $query .= "       unidades as l,";
          $query .= "       hc_solicitudes_medicamentos x,";
          $query .= "       hc_solicitudes_insumos_d z, ";
          $query .= "       bodegas_estaciones y ";
          $query .= "WHERE  x.ingreso = ".$ingreso." ";
          $query .= "AND    x.solicitud_id=z.solicitud_id ";
          $query .= "AND    x.empresa_id='".$estacion[empresa_id]."' ";
          $query .= "AND    x.empresa_id=y.empresa_id ";
          $query .= "AND    x.bodega=y.bodega ";
          $query .= "AND    x.centro_utilidad=y.centro_utilidad ";
          $query .= "AND    x.estacion_id=y.estacion_id ";
          $query .= "AND    x.sw_estado = '1' ";
          $query .= "AND    x.tipo_solicitud IN ('I','D') ";
          $query .= "AND    h.codigo_producto = z.codigo_producto ";
          $query .= "AND    h.unidad_id = l.unidad_id ";
          $query .= "AND    y.estacion_id='".$estacion[estacion_id]."' ";
          $query .= "AND    y.empresa_id='".$estacion[empresa_id]."' ";
          $query .= "AND    y.centro_utilidad='".$estacion[centro_utilidad]."' ";
          $query .= "AND    y.bodega='$bodega' ";
          $query .= "AND    EB.empresa_id = '".$estacion[empresa_id]."' "; 
          $query .= "AND    EB.centro_utilidad = '".$estacion[centro_utilidad]."' "; 
          $query .= "AND    EB.bodega = '".$bodega."' ";
          $query .= "AND    EB.codigo_producto = h.codigo_producto "; 
          $query .= " $sol ";
          $query .= "ORDER BY z.solicitud_id DESC ";
     
          $result = $dbconnect->Execute($query);

          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de medicamentos recetados";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }
          else
          { 
               $i=0;
               while (!$result->EOF)
               {
                    $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                    $i++;
               }
          }
          return $vector;
     }
     
     
    //funcion que trae los medicamentos solicitados para cancelarlos.
    function GetMedicamentosPendDesp($ingreso,$estacion,$bodega,$solicitud_id=NULL)
    {
      list($dbconnect) = GetDBconn();
      
      if(!empty($solicitud_id))
      { $sol=" AND X.solicitud_id='$solicitud_id'"; }
          
      $query  = "SELECT DE.*,";
      $query .= "       EB.existencia ";
      $query .= "FROM   existencias_bodegas EB,";
      $query .= "       (";
      $query .= "         (";
      $query .= "           SELECT DISTINCT A.ingreso,";
      $query .= "                  A.codigo_producto,";
      $query .= "                  X.documento_despacho as doc,";
      $query .= "                  H.descripcion as producto,";
      $query .= "                  C.descripcion as principio_activo,";
      $query .= "                  L.descripcion,";
      $query .= "                  X.solicitud_id, ";
      $query .= "                  X.sw_estado AS sw,";
      $query .= "                  Z.consecutivo_d, ";
      $query .= "                  Z.cant_solicitada";
      $query .= "           FROM   hc_formulacion_medicamentos AS A,";
      $query .= "                  inv_med_cod_principios_activos AS C,";
      $query .= "                  inventarios_productos AS H, ";
      $query .= "                  medicamentos AS K,";
      $query .= "                  unidades AS L,";
      $query .= "                  hc_solicitudes_medicamentos AS X,";
      $query .= "                  hc_solicitudes_medicamentos_d AS Z";
      $query .= "           WHERE  X.ingreso = ".$ingreso." ";
      $query .= "           AND    X.solicitud_id = Z.solicitud_id";
      $query .= "           AND    X.empresa_id = '".$estacion[empresa_id]."'";
      $query .= "           AND    X.estacion_id = '".$estacion[estacion_id]."'";
      $query .= "           AND    X.centro_utilidad = '".$estacion[centro_utilidad]."'";
      $query .= "           AND    X.bodega='$bodega'";
      $query .= "           AND    X.sw_estado = '1'";
      $query .= "           AND    Z.medicamento_id = A.codigo_producto";
      $query .= "           AND    A.ingreso = Z.ingreso";
      $query .= "           AND    A.codigo_producto = H.codigo_producto";
      $query .= "           AND    H.unidad_id = L.unidad_id ";
      $query .= "           AND    A.codigo_producto = K.codigo_medicamento";
      $query .= "           AND    K.cod_principio_activo = C.cod_principio_activo ";
      $query .= "           $sol";
      $query .= "         )";
      $query .= "         UNION ALL";
      $query .= "         (";
      $query .= "           SELECT DISTINCT Z.ingreso, ";
      $query .= "                  Z.medicamento_id AS codigo_producto, ";
      $query .= "                  X.documento_despacho as doc, ";
      $query .= "                  H.descripcion as producto,  ";
      $query .= "                  C.descripcion as principio_activo, ";
      $query .= "                  L.descripcion, ";
      $query .= "                  X.solicitud_id, X.sw_estado AS sw, ";
      $query .= "                  Z.consecutivo_d, Z.cant_solicitada ";
      $query .= "           FROM   inv_med_cod_principios_activos AS C,  ";
      $query .= "                  inventarios_productos AS H, medicamentos AS K, ";
      $query .= "                  unidades AS L,  ";
      $query .= "                  hc_solicitudes_medicamentos AS X, "; 
      $query .= "                  hc_solicitudes_medicamentos_d AS Z ";
      $query .= "                  LEFT JOIN  hc_formulacion_medicamentos AS A ";
      $query .= "                  ON ( ";
      $query .= "                       Z.medicamento_id = A.codigo_producto AND ";
      $query .= "                       A.ingreso = Z.ingreso ";
      $query .= "                     ) ";
      $query .= "           WHERE  X.ingreso = ".$ingreso." ";
      $query .= "           AND    X.solicitud_id = Z.solicitud_id ";
      $query .= "           AND    X.empresa_id = '".$estacion[empresa_id]."' ";
      $query .= "           AND    X.estacion_id = '".$estacion[estacion_id]."' ";
      $query .= "           AND    X.centro_utilidad = '".$estacion[centro_utilidad]."' ";
      $query .= "           AND    X.bodega='$bodega' ";
      $query .= "           AND    X.sw_estado = '1' ";
      $query .= "           AND    Z.medicamento_id = H.codigo_producto  ";                                       
      $query .= "           AND    H.unidad_id = L.unidad_id  ";   
      $query .= "           AND    Z.medicamento_id = K.codigo_medicamento ";
      $query .= "           AND    K.cod_principio_activo = C.cod_principio_activo ";
      $query .= "           AND    A.ingreso IS NULL ";
      $query .= "           $sol ";
      $query .= "         ) ";
      $query .= "         UNION ALL";
      $query .= "         (";
      $query .= "           SELECT DISTINCT A.ingreso,";
      $query .= "                  D.codigo_producto,";
      $query .= "                  X.documento_despacho as doc,";
      $query .= "                  H.descripcion as producto,";
      $query .= "                  C.descripcion as principio_activo,";
      $query .= "                  L.descripcion,";
      $query .= "                  X.solicitud_id, ";
      $query .= "                  X.sw_estado AS sw,";
      $query .= "                  Z.consecutivo_d, ";
      $query .= "                  Z.cant_solicitada";
      $query .= "           FROM   hc_formulacion_mezclas AS A,";
      $query .= "                  hc_formulacion_mezclas_detalle AS D,";
      $query .= "                  inv_med_cod_principios_activos AS C,";
      $query .= "                  inventarios_productos AS H, ";
      $query .= "                  medicamentos AS K,";
      $query .= "                  unidades AS L,";
      $query .= "                  hc_solicitudes_medicamentos AS X,";
      $query .= "                  hc_solicitudes_medicamentos_d AS Z";
      $query .= "           WHERE  X.ingreso = ".$ingreso."";
      $query .= "           AND    X.solicitud_id = Z.solicitud_id";
      $query .= "           AND    X.empresa_id = '".$estacion[empresa_id]."'";
      $query .= "           AND    X.estacion_id = '".$estacion[estacion_id]."'";
      $query .= "           AND    X.centro_utilidad = '".$estacion[centro_utilidad]."'";
      $query .= "           AND    X.bodega='$bodega'";
      $query .= "           AND    X.sw_estado = '1'";
      $query .= "           AND    A.num_mezcla = D.num_mezcla";
      $query .= "           AND    Z.medicamento_id = D.codigo_producto";
      $query .= "           AND    A.ingreso = Z.ingreso";
      $query .= "           AND    D.codigo_producto = H.codigo_producto";
      $query .= "           AND    H.unidad_id = L.unidad_id ";
      $query .= "           AND    D.codigo_producto = K.codigo_medicamento";
      $query .= "           AND    K.cod_principio_activo = C.cod_principio_activo ";
      $query .= "           $sol ";
      $query .= "         ) ";
      $query .= "       ) DE ";
      $query .= "WHERE  EB.empresa_id = '".$estacion[empresa_id]."' "; 
      $query .= "AND    EB.centro_utilidad = '".$estacion[centro_utilidad]."' "; 
      $query .= "AND    EB.bodega = '".$bodega."' ";
      $query .= "AND    EB.codigo_producto = DE.codigo_producto "; 
      $query .= "ORDER BY DE.consecutivo_d ASC, DE.solicitud_id DESC ";
                    
          $result = $dbconnect->Execute($query);

          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de medicamentos recetados";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }
          else
          { 
               $i=0;
               while (!$result->EOF)
               {
                    $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                    $i++;
               }
          }
          return $vector;
     }
     
     
     /**
     * Calcula los días que lleva hospitalizada una persona, basandose en la fecha de ingreso.
     *
     * @param timestamp fecha de ingreso del paciente
     * @return integer
     * @access Public
     */
     function GetDiasHospitalizacion($fecha_ingreso)
     {
          if(empty($fecha_ingreso)) return null;
     
          $date1 = date('Y-m-d H:i:s');
     
          $fecha_in=explode(".",$fecha_ingreso);
          $date2=$fecha_in[0];
     
          $s = strtotime($date1)-strtotime($date2);
          $d = intval($s/86400);
          $s -= $d*86400;
          $h = intval($s/3600);
          $s -= $h*3600;
          $m = intval($s/60);
          $s -= $m*60;
     
          if($d>0)
          {
               $dif= "$d  dias ";
          }
          else
          {
               $dif = "$h:$m horas ";
          }
          return $dif;
     }
     
     
     /*
     * funcion q trae los datos si se despacho sale la cantidad, el producto
     * si no se despacho pintarmos en la forma 'No despachado'
     */
    function GetDatosDespachoIns($doc,$serial,$solicitud)
    {
      list($dbconnect) = GetDBconn();
            
      $query="SELECT  SUM(b.cantidad) AS cantidad,
                      c.descripcion,
                      c.codigo_producto,
					  b.fecha_vencimiento,
					  b.lote,
					  e.existencia_actual
                FROM bodegas_documento_despacho_med a,
                     bodegas_documento_despacho_ins_d b,
                     inventarios_productos c,
					 hc_solicitudes_medicamentos d,
					 existencias_bodegas_lote_fv e
                     WHERE a.documento_despacho_id='$doc'
                     AND a.documento_despacho_id=b.documento_despacho_id
                     AND b.codigo_producto=c.codigo_producto
                     AND b.consecutivo_solicitud='$serial'
					 AND d.documento_despacho = a.documento_despacho_id
					 AND d.empresa_id = e.empresa_id
				     AND d.bodega = e.bodega
				     AND b.codigo_producto = e.codigo_producto
				     AND b.fecha_vencimiento = e.fecha_vencimiento
				     AND b.lote = e.lote
                GROUP BY c.descripcion, c.codigo_producto, b.fecha_vencimiento, b.lote, e.existencia_actual
				ORDER BY c.codigo_producto";

  		$result = $dbconnect->Execute($query);

  		if ($dbconnect->ErrorNo() != 0)
  		{
  			$this->error = "Error al buscar en la consulta de medicamentos recetados";
  			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
  		  return false;
  		}
      
      $vector = array();
      while (!$result->EOF)
  		{
        $vector[]=$result->GetRowAssoc($ToUpper = false);
        $result->MoveNext();
      }
      $result->Close();
      return $vector;
  	}

     
     /*
     * funcion q trae los datos si se despacho sale la cantidad, el producto
     * si no se despacho pintarmos en la forma 'No despachado'
     */
     function GetDatosDespacho($doc,$serial,$solicitud)
     {
          list($dbconnect) = GetDBconn();
		  
          $query="SELECT  SUM(b.cantidad) AS cantidad,
                          c.descripcion,
                          c.codigo_producto,
						  b.fecha_vencimiento,
						  b.lote,
						  e.existencia_actual
                  FROM    bodegas_documento_despacho_med a,
                          bodegas_documento_despacho_med_d b,
                          inventarios_productos c,
						  hc_solicitudes_medicamentos d,
						  existencias_bodegas_lote_fv e
                  WHERE a.documento_despacho_id='$doc'
                  AND a.documento_despacho_id=b.documento_despacho_id
                  AND b.codigo_producto=c.codigo_producto
                  AND b.consecutivo_solicitud='$serial'
				  AND d.documento_despacho = a.documento_despacho_id
				  AND d.empresa_id = e.empresa_id
				  AND d.bodega = e.bodega
				  AND b.codigo_producto = e.codigo_producto
				  AND b.fecha_vencimiento = e.fecha_vencimiento
				  AND b.lote = e.lote
                  GROUP BY c.descripcion, c.codigo_producto, b.fecha_vencimiento, b.lote, e.existencia_actual
				  ORDER BY c.codigo_producto";

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de medicamentos recetados";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
		}
    $vector = array();
    while (!$result->EOF)
		{
      $vector[]=$result->GetRowAssoc($ToUpper = false);
      $result->MoveNext();
    }
    $result->Close();
    return $vector;
	}
    
     
     //funcion que confirma el despacho de medicamentos en la estacion
     function InsertDespSolicitudMed()
     {
          $matriz = $_REQUEST['matriz'];
          $bodega = $_REQUEST['bodega'];
          
          $new_matriz = array();
          foreach($matriz as $k => $dtl)
            $new_matriz[$dtl] = $dtl;
            
          $datos_estacion = $_REQUEST['datos_estacion'];
          if(!empty($_SESSION['EE_I_y_M']['Pacientes']))
          {
          	$datosPaciente = $_SESSION['EE_I_y_M']['Pacientes'];
          }
          $obs = $_REQUEST['obs'];
          $spy = $_REQUEST['spia'];
          $plan = $_REQUEST['plan'];
          $cuenta = $_REQUEST['cuenta'];
          $SWITCHE = $_REQUEST['switche'];

          //este vector se puede utilizar en un futuro ya que viene la solicitud y el consecutivo
          //entonces lo dejamos alli mientras.
          $op=$_REQUEST['opcion'];
     
          list($dbconn) = GetDBconn();
		  
          //$dbconn->StartTrans();
          IncludeLib("despacho_medicamentos");
     
          foreach($new_matriz as $ki => $dti)
          {
            $_SESSION['DESPACHO']['MEDICAMENTOS']['CUENTA'] = $cuenta;
            $_SESSION['DESPACHO']['MEDICAMENTOS']['SOLICITUD'] = $ki;
            $_SESSION['DESPACHO']['MEDICAMENTOS']['PLAN'] = $plan;
            DocumentoDespachoMedicamentos();
            if($_SESSION['DESPACHO']['MEDICAMENTOS']['RETORNO']==4)
            {
              $this->error = $_SESSION['DESPACHO']['MEDICAMENTOS']['Mensaje'];
			  //$this->frmError["MensajeError"]="LA SOLICITUD NO PUDO SER CONFIRMADA.";
            }
            else
            {
              $this->error = $_SESSION['DESPACHO']['MEDICAMENTOS']['Mensaje'];
			  //return false;
            }
          }
         
          if($_REQUEST['retorno'] == "M")
          {
               $this->frmError["MensajeError"]="SOLICITUD DESPACHADA SATISFACTORIAMENTE.";          	
               $this->Frm_ConsultaEstadoMedicamentos($datos_estacion, $_REQUEST['datosPaciente']);
          }
          elseif($_REQUEST['retorno'] == "I")
          {
               $this->frmError["MensajeError"]="SOLICITUD DESPACHADA SATISFACTORIAMENTE.";          	
               $this->Frm_ConsultaEstadoInsumos($datos_estacion, $_REQUEST['datosPaciente']);
          }
          else
          {
          	$this->InsumosMed_X_Despachar($datos_estacion,$bodega,$SWITCHE,$datosPaciente);
          }
          return true;
     }
  
     //funcion que trae los Insumos solicitados para cancelarlos.
     function GetInsumosSolicitados($ingreso,$estacion,$bodega)
     {
          list($dbconnect) = GetDBconn();
          $query= "SELECT 
                    h.codigo_producto,z.cantidad,
                    h.descripcion as producto, 
                    l.descripcion,x.solicitud_id,z.consecutivo_d,x.bodega
          
                    FROM
                    inventarios_productos as h,
                    unidades as l,hc_solicitudes_medicamentos x,hc_solicitudes_insumos_d z
                    ,bodegas_estaciones y
          
                    WHERE
                    x.ingreso = ".$ingreso."
                    and x.solicitud_id=z.solicitud_id
                    and x.empresa_id='".$estacion[empresa_id]."'
                    and x.empresa_id=y.empresa_id
                    and x.centro_utilidad=y.centro_utilidad
                    and x.estacion_id=y.estacion_id
                    and x.bodega=y.bodega
          
                    and x.sw_estado = '0'
                    and h.codigo_producto = z.codigo_producto
                    and x.tipo_solicitud='I'
                    and h.unidad_id = l.unidad_id
                    and y.estacion_id='".$estacion[estacion_id]."'
                    and y.empresa_id='".$estacion[empresa_id]."'
                    and y.centro_utilidad='".$estacion[centro_utilidad]."'
                    and y.bodega='$bodega'
                    ORDER BY z.solicitud_id DESC";
     
          $result = $dbconnect->Execute($query);

          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de medicamentos recetados";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }
          else
          { 
               $i=0;
               while (!$result->EOF)
               {
                    $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                    $i++;
               }
          }
          return $vector;
     }
     
     
     //funcion que trae los medicamentos solicitados para cancelarlos.
     function GetMedicamentosSolicitados($ingreso,$estacion,$bodega)
     {
          list($dbconnect) = GetDBconn();
          
          $query= "SELECT A.*,
                            H.descripcion AS producto,
                            C.descripcion AS principio_activo,
                            L.descripcion, X.solicitud_id, X.bodega,
                            Z.consecutivo_d, Z.cant_solicitada, X.tipo_solicitud
                    FROM		hc_formulacion_medicamentos AS A,
                            inventarios_productos AS H, 
                            medicamentos AS K,
                            inv_med_cod_principios_activos AS C,
                            unidades AS L,
                            hc_solicitudes_medicamentos AS X,
                            hc_solicitudes_medicamentos_d AS Z
                    WHERE X.ingreso = ".$ingreso."
                    AND X.solicitud_id = Z.solicitud_id
                    AND X.empresa_id = '".$estacion[empresa_id]."'
                    AND X.estacion_id = '".$estacion[estacion_id]."'
                    AND X.centro_utilidad = '".$estacion[centro_utilidad]."'
                    AND X.bodega='$bodega'
                    AND X.sw_estado = '0'
                    AND A.codigo_producto = Z.medicamento_id
                    AND A.ingreso = Z.ingreso
                    AND A.sw_estado IN ('1','2','0')
                    AND K.cod_principio_activo = C.cod_principio_activo
                    AND A.codigo_producto = K.codigo_medicamento
                    AND A.codigo_producto = H.codigo_producto
                    AND H.unidad_id = L.unidad_id
                    ORDER BY Z.solicitud_id DESC
                  ";
          
          $result = $dbconnect->Execute($query);

          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de medicamentos recetados";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }
          else
          { 
               $i=0;
               while (!$result->EOF)
               {
                    $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                    $i++;
               }
          }
          return $vector;
     }
		
	/* Funcion que permite cancelar desde el panel de enfermeria 
     *  los medicamentos o insumos q aun no han sido despachados
     */
	function CancelSolicitudInsumos()
	{
		$matriz=$_REQUEST['matriz'];
		$bodega=$_REQUEST['bodega'];
		$SWITCHE=$_REQUEST['switche'];
		$datos_estacion=$_REQUEST['datos_estacion'];
		$obs=$_REQUEST['obs'];
		$spy=$_REQUEST['spia'];
		//este vector se puede utilizar en un futuro ya que viene la solicitud y el consecutivo
		//entonces lo dejamos alli mientras.
		$op=$_REQUEST['opcion'];

		list($dbconn) = GetDBconn();
		$dbconn->StartTrans();
		for($i=0;$i<sizeof($matriz);$i++)
		{

			 $query="UPDATE hc_solicitudes_medicamentos
					SET sw_estado='3'
					WHERE solicitud_id='".$matriz[$i]."'";

               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "No se inserto en hc_solicitudes_medicamentos ";
                    $this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }

			$query="INSERT INTO hc_auditoria_solicitudes_medicamentos
						 (fecha_registro,usuario_id,observacion,solicitud_id)
						 VALUES(now(),'".UserGetUID()."','$obs','".$matriz[$i]."');";

               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "no se inserto en  hc_auditoria_solicitudes_medicamentos";
                    $this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
		}
		$dbconn->CompleteTrans();   //termina la transaccion

          $this->frmError["MensajeError"]="SOLICITUD CANCELADA SATISFACTORIAMENTE.";
          $this->CallMedicamentosIns_X_Recibir($datos_estacion,$bodega,$SWITCHE);
          return true;
	}
     
	/* Funcion que permite cancelar desde el panel de enfermeria 
     *  los medicamentos o insumos q aun no han sido despachados
     */
     function CancelSolicitudMedicametos()
     {
		$matriz=$_REQUEST['matriz'];
		$bodega=$_REQUEST['bodega'];
		$SWITCHE=$_REQUEST['switche'];
		$datos_estacion=$_REQUEST['datos_estacion'];
		$obs=$_REQUEST['obs'];
		$spy=$_REQUEST['spia'];

		//este vector se puede utilizar en un futuro ya que viene la solicitud y el consecutivo
		//entonces lo dejamos alli mientras.
		$op=$_REQUEST['opcion'];

		list($dbconn) = GetDBconn();
		$dbconn->StartTrans();
		for($i=0;$i<sizeof($matriz);$i++)
		{

               $query="UPDATE hc_solicitudes_medicamentos
                         SET sw_estado='3'
                         WHERE solicitud_id='".$matriz[$i]."'";
     
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "No se inserto en hc_solicitudes_medicamentos ";
                    $this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }

			$query="INSERT INTO hc_auditoria_solicitudes_medicamentos
						 (fecha_registro,usuario_id,observacion,solicitud_id)
						 VALUES(now(),'".UserGetUID()."','$obs','".$matriz[$i]."');";

               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "no se inserto en  hc_auditoria_solicitudes_medicamentos";
                    $this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }

		}
		$dbconn->CompleteTrans();   //termina la transaccion

          $this->frmError["MensajeError"]="SOLICITUD CANCELADA SATISFACTORIAMENTE.";
          $this->CallMedicamentosIns_X_Recibir($datos_estacion,$bodega,$SWITCHE);
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
     
     
     /**
     * Cancelar el despacho por confimrar a la bodega
     * @access public
     * @return boolean  
     */
     function CancelarDespSolicitud(){
          $this->FrmCancelarDespSolicitud($_REQUEST['solicitud_id'],$_REQUEST['ingreso'],$_REQUEST['plan'],$_REQUEST['cuenta'],$_SESSION['EE_I_y_M']['Estacion'],$_SESSION['EE_I_y_M']['Pacientes'],$_REQUEST['bodega'],$_REQUEST['switche']);
          return true;      
     }
     
     
     function GuardarCancelarDespSolicitud()
     {
          if(empty($_REQUEST['obs'])){
               $this->frmError["MensajeError"]="Escriba la Justificacion para la Cancelacion de la Solicitud";
               $this->FrmCancelarDespSolicitud($_REQUEST['solicitud_id'],$_REQUEST['ingreso'],$_REQUEST['plan'],$_REQUEST['cuenta'],$_SESSION['EE_I_y_M']['Estacion'],$_SESSION['EE_I_y_M']['Pacientes'],$_REQUEST['bodega'],$_REQUEST['switche']);
               return true;
          }
          list($dbconn) = GetDBconn();
          $query="UPDATE hc_solicitudes_medicamentos
          SET sw_estado='6' 
          WHERE solicitud_id='".$_REQUEST['solicitud_id']."'";
          $resulta=$dbconn->execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }else{
               $query="INSERT INTO hc_auditoria_solicitudes_medicamentos
               (usuario_id,fecha_registro,observacion,solicitud_id)
               VALUES('".UserGetUID()."','".date("Y-m-d H:i:s")."','".$_REQUEST['obs']."','".$_REQUEST['solicitud_id']."')";
	          $resulta=$dbconn->execute($query);
               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
          }
          $this->InsumosMed_X_Despachar($_SESSION['EE_I_y_M']['Estacion'],$_REQUEST['bodega'],$_REQUEST['switche'],$_SESSION['EE_I_y_M']['Pacientes']);
          return true;    
     }
     
     function Call_RutaProductos()
     {
     	if($_REQUEST['Seleccion'] == 'Medicamentos')
          {
			$this->Frm_ConsultaEstadoMedicamentos($_REQUEST['datos_estacion'],$_REQUEST['datosPaciente']);
               return true;
          }elseif($_REQUEST['Seleccion'] == 'Insumos')
          {
			$this->Frm_ConsultaEstadoInsumos($_REQUEST['datos_estacion'],$_REQUEST['datosPaciente']);
               return true;
          }elseif($_REQUEST['Seleccion'] == 'Devoluciones')
          {
			$this->Frm_ConsultaSolicitudesDevolucion($_REQUEST['datos_estacion'],$_REQUEST['datosPaciente']);
               return true;
          }
     }
     
     /**
     * Funcion que busca en la base de datos las solicitudes de medicamentos que no se hano despachado
     * @return array
     * @param string codigo de la empresa a la que pertenece la solicitud
     * @param string codigo del centro de utilidad al que pertenece la solicitud
     * @param string codigo de la bodega donde fue relaizado la silicitud
     */
	function SolicitudesMedicamentos($ingreso, $datos_estacion)
     {
		list($dbconn) = GetDBconn();

		$query = "SELECT c.departamento||'-'||c.descripcion as dpto, a.solicitud_id, a.estacion_id, 
          			  a.fecha_solicitud, a.ingreso, d.nombre as usuarioestacion, 
                           a.usuario_id, c.descripcion as deptoestacion,
                           e.rango, k.tipo_afiliado_nombre as tipo_afiliado_id, 
                           h.plan_descripcion, i.tipo_id_paciente, i.paciente_id, 
                           l.primer_nombre||' '||l.segundo_nombre||' '||l.primer_apellido||' '||l.segundo_apellido as nombrepac

				FROM hc_solicitudes_medicamentos a, estaciones_enfermeria b, departamentos c,
                         system_usuarios d, cuentas e,
                         planes h, ingresos i, tipos_afiliado k, pacientes l
				
                    WHERE a.ingreso = $ingreso
                    AND a.tipo_solicitud = 'M'
                    AND a.empresa_id='".$datos_estacion['empresa_id']."' 
                    AND a.centro_utilidad='".$datos_estacion['centro_utilidad']."' 
                    AND a.sw_estado='0' 
                    AND a.estacion_id=b.estacion_id 
                    AND b.departamento=c.departamento 
                    AND a.usuario_id=d.usuario_id 
                    AND a.ingreso=e.ingreso 
                    AND (e.estado='1' OR e.estado='2')
                    AND a.ingreso=i.ingreso 
                    AND e.plan_id=h.plan_id 
                    AND k.tipo_afiliado_id=e.tipo_afiliado_id 
                    AND i.tipo_id_paciente=l.tipo_id_paciente 
                    AND i.paciente_id=l.paciente_id
                    
                    ORDER BY dpto,a.fecha_solicitud";

		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
          else
          {
			$datos=$result->RecordCount();
			if($datos)
               {
				while(!$result->EOF) 
                    {
					$vars[$result->fields[0]][$result->fields[1]]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		$result->Close();
     	return $vars;
	}

     /**
     * Funcion que busca en la base de datos las solicitudes de medicamentos que fueron despachadas
     * @return array
     * @param string codigo de la empresa a la que pertenece la solicitud
     * @param string codigo del centro de utilidad al que pertenece la solicitud
     * @param string codigo de la bodega donde fue relaizado la silicitud
     */
	function DespachoMedicamentosForaneos($ingreso, $datos_estacion)
     {
		list($dbconn) = GetDBconn();

		$query = "SELECT c.departamento||'-'||c.descripcion as dpto, a.solicitud_id, a.estacion_id, 
          			  a.fecha_solicitud, a.ingreso, d.nombre as usuarioestacion, 
                           a.usuario_id, c.descripcion as deptoestacion,
                           e.rango, k.tipo_afiliado_nombre as tipo_afiliado_id, 
                           h.plan_descripcion, i.tipo_id_paciente, i.paciente_id, 
                           l.primer_nombre||' '||l.segundo_nombre||' '||l.primer_apellido||' '||l.segundo_apellido as nombrepac

				FROM hc_solicitudes_medicamentos a, estaciones_enfermeria b, departamentos c,
                         system_usuarios d, cuentas e,
                         planes h, ingresos i, tipos_afiliado k, pacientes l
				
                    WHERE a.ingreso = $ingreso
                    AND a.tipo_solicitud = 'M'
                    AND a.empresa_id='".$datos_estacion['empresa_id']."' 
                    AND a.centro_utilidad='".$datos_estacion['centro_utilidad']."' 
                    AND a.sw_estado='1' 
                    AND a.estacion_id=b.estacion_id 
                    AND b.departamento=c.departamento 
                    AND a.usuario_id=d.usuario_id 
                    AND a.ingreso=e.ingreso 
                    AND (e.estado='1' OR e.estado='2')
                    AND a.ingreso=i.ingreso 
                    AND e.plan_id=h.plan_id 
                    AND k.tipo_afiliado_id=e.tipo_afiliado_id 
                    AND i.tipo_id_paciente=l.tipo_id_paciente 
                    AND i.paciente_id=l.paciente_id
                    
                    ORDER BY dpto,a.fecha_solicitud";

		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
          else
          {
			$datos=$result->RecordCount();
			if($datos)
               {
				while(!$result->EOF) 
                    {
					$vars[$result->fields[0]][$result->fields[1]]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		$result->Close();
     	return $vars;
	}
      
      /**
     * Funcion que busca en la base de datos las solicitudes de medicamentos que no han sido despachadas
     * @return array
     * @param string codigo de la empresa a la que pertenece la solicitud
     * @param string codigo del centro de utilidad al que pertenece la solicitud
     * @param string codigo de la bodega donde fue relaizado la silicitud
     */
	function SolicitudesInsumos($ingreso, $datos_estacion)
     {
		list($dbconn) = GetDBconn();

		$query = "SELECT c.departamento||'-'||c.descripcion as dpto, a.solicitud_id, a.estacion_id, 
          			  a.fecha_solicitud, a.ingreso, d.nombre as usuarioestacion, 
                           a.usuario_id, c.descripcion as deptoestacion,
                           e.rango, k.tipo_afiliado_nombre as tipo_afiliado_id, 
                           h.plan_descripcion, i.tipo_id_paciente, i.paciente_id, 
                           l.primer_nombre||' '||l.segundo_nombre||' '||l.primer_apellido||' '||l.segundo_apellido as nombrepac

				FROM hc_solicitudes_medicamentos a, estaciones_enfermeria b, departamentos c,
                         system_usuarios d, cuentas e,
                         planes h, ingresos i, tipos_afiliado k, pacientes l
				
                    WHERE a.ingreso = $ingreso
                    AND a.tipo_solicitud = 'I'
                    AND a.empresa_id='".$datos_estacion['empresa_id']."' 
                    AND a.centro_utilidad='".$datos_estacion['centro_utilidad']."' 
                    AND a.sw_estado='0' 
                    AND a.estacion_id=b.estacion_id 
                    AND b.departamento=c.departamento 
                    AND a.usuario_id=d.usuario_id 
                    AND a.ingreso=e.ingreso 
                    AND (e.estado='1' OR e.estado='2')
                    AND a.ingreso=i.ingreso 
                    AND e.plan_id=h.plan_id 
                    AND k.tipo_afiliado_id=e.tipo_afiliado_id 
                    AND i.tipo_id_paciente=l.tipo_id_paciente 
                    AND i.paciente_id=l.paciente_id
                    
                    ORDER BY dpto,a.fecha_solicitud";

		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
          else
          {
			$datos=$result->RecordCount();
			if($datos)
               {
				while(!$result->EOF) 
                    {
					$vars[$result->fields[0]][$result->fields[1]]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		$result->Close();
     	return $vars;
	}
    
     /**
     * Funcion que busca en la base de datos las solicitudes de medicamentos que fueron despachadas
     * @return array
     * @param string codigo de la empresa a la que pertenece la solicitud
     * @param string codigo del centro de utilidad al que pertenece la solicitud
     * @param string codigo de la bodega donde fue relaizado la silicitud
     */
	function DespachoInsumosForaneos($ingreso, $datos_estacion)
     {
		list($dbconn) = GetDBconn();

		$query = "SELECT c.departamento||'-'||c.descripcion as dpto, a.solicitud_id, a.estacion_id, 
          			  a.fecha_solicitud, a.ingreso, d.nombre as usuarioestacion, 
                           a.usuario_id, c.descripcion as deptoestacion,
                           e.rango, k.tipo_afiliado_nombre as tipo_afiliado_id, 
                           h.plan_descripcion, i.tipo_id_paciente, i.paciente_id, 
                           l.primer_nombre||' '||l.segundo_nombre||' '||l.primer_apellido||' '||l.segundo_apellido as nombrepac

				FROM hc_solicitudes_medicamentos a, estaciones_enfermeria b, departamentos c,
                         system_usuarios d, cuentas e,
                         planes h, ingresos i, tipos_afiliado k, pacientes l
				
                    WHERE a.ingreso = $ingreso
                    AND a.tipo_solicitud = 'I'
                    AND a.empresa_id='".$datos_estacion['empresa_id']."' 
                    AND a.centro_utilidad='".$datos_estacion['centro_utilidad']."' 
                    AND a.sw_estado='1' 
                    AND a.estacion_id=b.estacion_id 
                    AND b.departamento=c.departamento 
                    AND a.usuario_id=d.usuario_id 
                    AND a.ingreso=e.ingreso 
                    AND (e.estado='1' OR e.estado='2')
                    AND a.ingreso=i.ingreso 
                    AND e.plan_id=h.plan_id 
                    AND k.tipo_afiliado_id=e.tipo_afiliado_id 
                    AND i.tipo_id_paciente=l.tipo_id_paciente 
                    AND i.paciente_id=l.paciente_id
                    
                    ORDER BY dpto,a.fecha_solicitud";

		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
          else
          {
			$datos=$result->RecordCount();
			if($datos)
               {
				while(!$result->EOF) 
                    {
					$vars[$result->fields[0]][$result->fields[1]]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		$result->Close();
     	return $vars;
	}

     
     /**
     * Funcion que retorna el nombre de la estacion de enfermeria a partir de su codigo
     * @return array
     * @param string codigo de la estacion
     */
	function NombreEstacion($codigo)
     {
		list($dbconn) = GetDBconn();
		$query = "SELECT descripcion 
          		FROM estaciones_enfermeria 
                    WHERE estacion_id='$codigo'";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) 
          {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else
          {
			if($result->EOF)
               {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'estaciones_enfermeria' esta vacia ";
				return false;
			}else
               {
				$vars=$result->GetRowAssoc($toUpper=false);
			}
		}
		$result->Close();
 		return $vars;
	}

     /**
     * Funcion que llama la forma que visualiza el detalle de la solicitud realizada a la bodega
     * @return boolean
     */
	function DetalleSolicitudMedicamento()
     {
		$this->FrmAtenderSolicitudPaciente($_REQUEST['SolicitudId'],$_REQUEST['Ingreso'],$_REQUEST['EstacionId'],
		$_REQUEST['NombreEstacion'],$_REQUEST['Fecha'],$_REQUEST['usuarioestacion'],$_REQUEST['nombrepac'],
		$_REQUEST['tipo_id_paciente'],$_REQUEST['paciente_id'],$_REQUEST['datos_estacion'],$_REQUEST['datosPaciente'],$_REQUEST['estado']);
		return true;
	}
     
     /**
     * Funcion que retorna el tipo de la solicitud
     * @return array
     * @param integer codigo unico que identifica la solicitud
     * @param integer codigo de la empresa a donde pertenece la bodega
     * @param integer codigo del centro de utilidad al que pertenece la bodega
     * @param integer bodega a la que realizaron la solicitud
     */
     function GetTipoSolicitudBodega($solicitud)
     {
          list($dbconn) = GetDBconn();
          $query = "SELECT a.tipo_solicitud, a.bodega
                    FROM hc_solicitudes_medicamentos a
                    LEFT JOIN hc_auditoria_solicitudes_medicamentos b ON(a.solicitud_id=b.solicitud_id)
                    LEFT JOIN system_usuarios c ON(b.usuario_id=c.usuario_id)
                    WHERE a.solicitud_id='$solicitud'";
          $result = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }else
          {
               if($result->EOF)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "La tabla 'hc_solicitudes_medicamentos' esta vacia ";
                    return false;
               }else
               {
               	$vars=$result->GetRowAssoc($toUpper=false);
               }
          }
          $result->Close();
          return $vars;
     }

     /**
     * Funcion que retorna los medicamentos de una solicitud de medicamentos
     * @return array
     * @param integer codigo unico que identifica la solicitud
     * @param array datos de la ubicacion de la peticion de la solicitud
     */
	function GetMedicamentosSolicitud($solicitud,$empresa_id)
	{
		$query = "(SELECT SMD.solicitud_id,
                            SMD.consecutivo_d,
                            SM.documento_despacho,
                            NULL as mezcla_recetada_id,
                            SMD.medicamento_id,
                            SMD.evolucion_id,
                            SMD.cant_solicitada,
                            M.cod_forma_farmacologica,
                            INVP.descripcion as nomMedicamento,
                            FF.descripcion as FF,
                            INV.codigo_producto as codigo_medicamento
                    FROM
	                    hc_solicitudes_medicamentos_d SMD,
                         hc_solicitudes_medicamentos SM,
                         medicamentos M,
                         inventarios INV,
                         inventarios_productos INVP,
                         inv_med_cod_forma_farmacologica FF
                    WHERE
                         SMD.solicitud_id = '$solicitud'
                         AND SM.solicitud_id = SMD.solicitud_id
                         AND SMD.medicamento_id=M.codigo_medicamento
                         AND INV.codigo_producto = M.codigo_medicamento
                         AND INV.empresa_id ='".$empresa_id."'
                         AND INV.codigo_producto = INVP.codigo_producto
                         AND FF.cod_forma_farmacologica = M.cod_forma_farmacologica)";

		list($dbconn) = GetDBconn();
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
          {
			$this->error = "Atención";
			$this->mensajeDeError = "Ocurrió un error al intentar obtener los medicamentos de la solicitud seleccionada";
			return false;
		}
          else
          {
			$datos=$result->RecordCount();
			if($datos)
               {
               	$vars=array();
				while(!$result->EOF)
                    {
					array_push($vars,$result->GetRowAssoc($ToUpper = false));
					$result->MoveNext();
				}
          	}//si retornó mezclas de la solicitud
		}
		return $vars;
	}//fin GetMedicamentosSolicitud($solicitud)

     
     /**
     * Funcion que retorna los medicamentos de una solicitud de medicamentos de una mezcla
     * @return array
     * @param integer codigo unico que identifica la solicitud
     * @param array datos de la ubicacion de la peticion de la solicitud
     */
	function GetMezclasSolicitud($solicitud,$empresa_id)
	{
		$query = "(SELECT
                         SMD.solicitud_id,
                         SMD.consecutivo_d,
                         SMD.mezcla_recetada_id,
                         SMD.medicamento_id,
                         SMD.evolucion_id,
                         SMD.cant_solicitada,
                         M.cod_forma_farmacologica,
                         INVP.descripcion as nomMedicamento,
                         FF.descripcion as FF,
                         M.codigo_medicamento
                    FROM hc_solicitudes_medicamentos_mezclas_d SMD,
                         medicamentos M,
                         inventarios INV,
                         inventarios_productos INVP,
                         inv_med_cod_forma_farmacologica FF
                    WHERE SMD.solicitud_id = '$solicitud'
                         AND SMD.medicamento_id=M.codigo_medicamento
                         AND INV.codigo_producto = M.codigo_medicamento
                         AND INV.empresa_id  = '".$empresa_id."'
                         AND INV.codigo_producto=INVP.codigo_producto
                         AND FF.cod_forma_farmacologica = M.cod_forma_farmacologica)";

		list($dbconn) = GetDBconn();
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
          {
			$this->error = "Atención";
			$this->mensajeDeError = "Ocurrió un error al intentar obtener los medicamentos de la solicitud seleccionada";
			return false;
		}
          else
          {
			$datos=$result->RecordCount();
			if($datos)
               {
               	$vars=array();
				while(!$result->EOF)
                    {
					array_push($vars,$result->GetRowAssoc($ToUpper = false));
					$result->MoveNext();
				}
          	}
		}//si retornó mezclas de la solicitud
		return $vars;
	}//fin GetMedicamentosSolicitud($solicitud)

     
     /**
     * Funcion que retorna los insumos de una solicitud
     * @return array
     * @param integer codigo unico que identifica la solicitud
     * @param array datos de la ubicacion de la peticion de la solicitud
     */
	function GetInsumosSolicitud($solicitud,$empresa_id)
     {
     	list($dbconn) = GetDBconn();
		$query = "(SELECT   SMD.solicitud_id,
                              SMD.consecutivo_d,
                              SM.documento_despacho,
                              NULL as mezcla_recetada_id,
                              SMD.codigo_producto as medicamento_id,
                              NULL as evolucion_id,
                              SMD.cantidad as cant_solicitada,
                              NULL as cod_forma_farmacologica,
                              INVP.descripcion||' '||UNI.descripcion||' '||INVP.contenido_unidad_venta  as nomMedicamento,
                              NULL as FF,
                              INV.codigo_producto as codigo_medicamento
                     FROM  hc_solicitudes_insumos_d SMD,
                     	  hc_solicitudes_medicamentos SM,
                           inventarios INV,inventarios_productos INVP,
                           unidades UNI
                     WHERE SMD.solicitud_id = '$solicitud'
                     AND SM.solicitud_id = SMD.solicitud_id
                     AND SMD.codigo_producto = INVP.codigo_producto 
                     AND INVP.codigo_producto = INV.codigo_producto 
                     AND INV.empresa_id  = '".$empresa_id."' 
                     AND INVP.unidad_id = UNI.unidad_id)";
     	$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
          {
			$this->error = "Atención";
			$this->mensajeDeError = "Ocurrió un error al intentar obtener los medicamentos de la solicitud seleccionada";
			return false;
		}
          else
          {
			$datos=$result->RecordCount();
			if($datos)
               {
               	$vars=array();
				while(!$result->EOF)
                    {
					array_push($vars,$result->GetRowAssoc($ToUpper = false));
					$result->MoveNext();
				}
          	}
		}//si retornó mezclas de la solicitud
		return $vars;
	}//fin GetMedicamentosSolicitud($solicitud)
     
     /**
     * Funcion que consulta en la base de datos las devoluciones para recibir
     * @return boolean
     * @param integer empresa a la que pertenece la bodega donde se va a crear el documento
     * @param integer centro de utilidad al  que partenece la bodega donde se va a crear el documento
     * @param integer codigo de la bodega donde se va a crear el documento
     */
	function DevolucionesMedicamentos($ingreso,$datos_estacion)
     {

		list($dbconn) = GetDBconn();
		$query = "SELECT c.departamento||'-'||c.descripcion as dpto, a.documento,
          			  a.estacion_id, a.fecha_registro as fecha, a.ingreso,
                           d.nombre as usuarioestacion, a.usuario_id, c.descripcion as deptoestacion,
					  e.rango, k.tipo_afiliado_nombre as tipo_afiliado_id,
                           h.plan_descripcion, i.tipo_id_paciente, i.paciente_id,
					  l.primer_nombre||' '||l.segundo_nombre||' '||l.primer_apellido||' '||l.segundo_apellido as nombrepac,
                           a.observacion, est.descripcion as parametro, a.bodega
				FROM inv_solicitudes_devolucion a
				LEFT JOIN estacion_enfermeria_parametros_devolucion est ON(est.parametro_devolucion_id=a.parametro_devolucion_id),
    					estaciones_enfermeria b, departamentos c, system_usuarios d,
                         cuentas e, planes h, ingresos i, tipos_afiliado k, pacientes l
                    WHERE a.ingreso = ".$ingreso."
                    AND a.empresa_id = '".$datos_estacion['empresa_id']."'
                    AND a.centro_utilidad = '".$datos_estacion['centro_utilidad']."' 
                    AND a.estado='0' 
                    AND a.estacion_id=b.estacion_id
                    AND b.departamento=c.departamento 
                    AND a.usuario_id=d.usuario_id 
                    AND a.ingreso=e.ingreso 
                    AND (e.estado='1' OR e.estado='2')
				AND a.ingreso=i.ingreso 
                    AND e.plan_id=h.plan_id 
                    AND k.tipo_afiliado_id=e.tipo_afiliado_id 
                    AND i.tipo_id_paciente=l.tipo_id_paciente 
                    AND i.paciente_id=l.paciente_id";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
          {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
          else
          {
               $datos=$result->RecordCount();
			if($datos)
               {
				while(!$result->EOF) 
                    {
					$vars[$result->fields[0]][$result->fields[1]]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		$result->Close();
 		return $vars;
	}

     /**
     * Funcion que llama la forma que visualiza el detalle de una devolucion realizada a la bodega
     * @return boolean
     */
	function DetalleDevolucionMedicamentos(){
		$this->FormaDetalleDevolucionMedicamentos($_REQUEST['EstacionId'],$_REQUEST['NombreEstacion'],$_REQUEST['Fecha'],$_REQUEST['Documento'],$_REQUEST['Ingreso'],$_REQUEST['observaciones'],
		'','','','','',$_REQUEST['identificacion'],$_REQUEST['nombrepac'],$_REQUEST['datos_estacion'],$_REQUEST['datosPaciente'],$_REQUEST['parametro'],$_REQUEST['bodega_s']);
		return true;
	}
     
     /**
     * Funcion que consulta en la base de datos los medicametos o insumos que hacen parte de una solicitud de devolucion
     * @return boolean
     * @param integer empresa a la que pertenece la bodega donde se va a crear el documento
     * @param integer centro de utilidad al  que partenece la bodega donde se va a crear el documento
     * @param integer codigo de la bodega donde se va a crear el documento
     * @param array solicitudes de devolucion activas
     */
	function  ProductosDevolucion($Documento,$datos_estacion)
     {
		list($dbconn) = GetDBconn();
		$query="SELECT b.codigo_producto, b.cantidad, d.descripcion, 
          			e.sw_control_fecha_vencimiento,b.consecutivo
                  FROM inv_solicitudes_devolucion a, inv_solicitudes_devolucion_d b,
				   inventarios c, inventarios_productos d,
                       existencias_bodegas e
                  WHERE a.empresa_id='".$datos_estacion['empresa_id']."' 
                  AND a.centro_utilidad='".$datos_estacion['centro_utilidad']."' 
                  AND a.documento='$Documento' 
                  AND a.documento=b.documento 
                  AND c.empresa_id=a.empresa_id 
                  AND c.codigo_producto=b.codigo_producto 
                  AND d.codigo_producto=b.codigo_producto 
                  AND a.empresa_id=e.empresa_id 
                  AND a.centro_utilidad=e.centro_utilidad 
                  AND a.bodega=e.bodega 
                  AND b.codigo_producto=e.codigo_producto 
                  AND b.estado='0'";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) 
          {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
          else
          {
			$datos=$result->RecordCount();
			if($datos)
               {
				while(!$result->EOF) 
                    {
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		$result->Close();
 		return $vars;
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