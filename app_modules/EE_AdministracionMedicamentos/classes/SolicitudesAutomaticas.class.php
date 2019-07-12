<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: SolicitudesAutomaticas.class.php,v 1.3 2011/04/26 15:14:17 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase: SolicitudesAutomaticas
  * Clase Encargada de la logica, para el manejo de las presolicitudes
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.3 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F. Manrique
  */
  class SolicitudesAutomaticas extends ConexionBD
  {
    /**
    * Contructor de la clase
    */
    function SolicitudesAutomaticas(){}
    /**
    * Funcion donde se obtienes los medicamentos de las formulaciones hechas 
    * al paciente
    * 
    * @param array $datos Arreglo con los datos del paciente
    *
    * @return mixed
    */
    function ObtenerMedicamentosSolicitud($datos)
    {
      $sql  = "SELECT ST.solicitud_tratamiento_id,";
      $sql .= " 	    ST.codigo_medicamento,";
      $sql .= " 	    ST.dias_tratamiento ,";
      $sql .= " 	    ST.total_solicitudes_realizadas ,";
      $sql .= " 	    ST.intensidad ,";
      $sql .= " 	    ST.intensidad_cantidad 	,";
      $sql .= " 	    ST.fecha_inicio 	,";
      $sql .= " 	    TO_CHAR(ST.fecha_siguiente_solictud, 'DD/MM/YYYY') AS fecha_siguiente_solictud 	,";
      $sql .= " 	    ST.cantidad 	,";
      $sql .= " 	    ST.incremento_dias, ";
      $sql .= "	      IA.descripcion AS principio_activo, ";
      $sql .= "       fc_descripcion_producto(ST.codigo_medicamento) AS producto, ";
//      $sql .= "       PR.descripcion AS producto, ";
      $sql .= "       PR.contenido_unidad_venta AS concentracion_producto, ";
      $sql .= "       (SELECT MFF.descripcion FROM inv_med_cod_forma_farmacologica MFF WHERE MFF.cod_forma_farmacologica = MD.cod_forma_farmacologica) AS forma, ";
      $sql .= "       (SELECT BP.stock FROM bodega_paciente BP WHERE BP.ingreso = ST.ingreso AND BP.codigo_producto = MD.codigo_medicamento)AS stock, ";
      $sql .= "       (SELECT (bp.cantidad_en_solicitud)	FROM bodega_paciente bp WHERE bp.ingreso = ST.ingreso and bp.codigo_producto = MD.codigo_medicamento)AS solicitadoval ";
      $sql .= "FROM   solicitudes_tratamiento ST, ";
      $sql .= "       medicamentos MD, ";
      $sql .= "       inventarios_productos PR, ";
      $sql .= "       inv_med_cod_principios_activos IA ";
      $sql .= "WHERE 	ingreso = ".$datos['ingreso']." ";
      $sql .= "AND	  ST.codigo_medicamento = MD.codigo_medicamento ";
      $sql .= "AND    MD.codigo_medicamento = PR.codigo_producto ";
			$sql .= "AND 		MD.cod_principio_activo = IA.cod_principio_activo ";
      $sql .= "AND 	  ST.sw_finalizado = '0' ";
      $sql .= "AND 	  ST.fecha_siguiente_solictud <= NOW()::date	";
      
      $datos = array();
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      while(!$rst->EOF)
      {
        $datos[$rst->fields[1]] =  $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();

      return $datos;
    }
    /**
    * Funcion donde se obtienen los insumos asociados a los medicamentos asociados
    *
    * @param array $medicamentos Arreglo de datos con los medicamentos formulados
    * @param string Identificador del departamento
    *
    * @return mixed
    */
    function ObtenerInsumosAsociados($medicamentos,$departamento)
    {
      $codigos = "";
      foreach($medicamentos as $key => $dtl)
       ($codigos == "")? $codigos .= "'".$dtl['codigo_medicamento']."'" :$codigos .= ",'".$dtl['codigo_medicamento']."'";
      
      $sql  = "SELECT IM.codigo_medicamento,";
      $sql .= "       IM.codigo_producto, ";
      $sql .= "       IM.cantidad, ";
      $sql .= "       PR.descripcion AS producto, ";
      $sql .= "				IA.descripcion AS principio_activo, ";
      $sql .= "       CASE WHEN ME.codigo_medicamento IS NULL THEN '1' ";
      $sql .= "            ELSE '0' END AS insumo ";
      $sql .= "FROM   insumos_x_medicamentos IM, ";
      $sql .= "       inventarios_productos PR LEFT JOIN ";
      $sql .= "       medicamentos ME ";
      $sql .= "       ON(PR.codigo_producto = ME.codigo_medicamento) ";
      $sql .= "       LEFT JOIN inv_med_cod_principios_activos IA ";
      $sql .= "       ON(ME.cod_principio_activo = IA.cod_principio_activo) ";
      $sql .= "WHERE  IM.codigo_producto = PR.codigo_producto ";
      $sql .= "AND    IM.departamento = '".$departamento."' ";
      $sql .= "AND    IM.codigo_medicamento IN (".$codigos.") ";
      
      $datos = array();
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      while(!$rst->EOF)
      {
        $datos[$rst->fields[0]][] =  $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();

      return $datos;
    }
    /**
    * Funcion donde se cancelan las presolicitudes
    *
    * @param array $request Arreglo de datos con el reques, donde esta el identificador de la presolicitud
    *
    * @return boolean
    */
    function CancelarPreSolicitud($request)
    {
      $datos = $this->ObtenerDatosSolicitud($request);
      $this->ConexionTransaccion();
      $sql  = "INSERT INTO solicitudes_tratamiento_d ";
      $sql .= " ("; 
      $sql .= "   solicitud_tratamiento_d_id,"; 
      $sql .= "   solicitud_tratamiento_id,"; 
      $sql .= "   fecha_solicitud,"; 
      $sql .= "   usuario_id,"; 
      $sql .= "   estado_solictud"; 
      $sql .= " )"; 
      $sql .= "VALUES"; 
      $sql .= " ("; 
      $sql .= "   DEFAULT,"; 
      $sql .= "   ".$datos['solicitud_tratamiento_id'].",";
      $sql .= "   NOW(), ";
      $sql .= "   ".UserGetUID().", ";
      $sql .= "   '0' ";
      $sql .= " )";
      
      if(!$rst = $this->ConexionTransaccion($sql)) 
        return false;
      
      $f = explode("/",$datos['fecha_siguiente_solictud']);
      $fecha_siguiente_solictud = date("d/m/Y", mktime(0, 0, 0,$f[1],($f[0]+$datos['incremento_dias']),$f[2]));
      
      $sw_finalizado = "0";
      $ctl = AutoCarga::factory("ClaseUtil");
      if($ctl->CompararFechas($fecha_siguiente_solictud,$datos['fecha_finalizacion']) <= 0)
      {
        /*$fecha_siguiente_solictud = "'".$fecha_siguiente_solictud."'::date";*/
          $f = explode("/",$fecha_siguiente_solictud);
          $fecha_siguiente_solictud = "'".$f[2]."-".$f[1]."-".$f[0]."'";
      }
      else
      {
        $sw_finalizado = "1";
        $fecha_siguiente_solictud = "NULL";
      }
      
      $sql  = "UPDATE solicitudes_tratamiento ";
      $sql .= "SET    total_solicitudes_realizadas = 	total_solicitudes_realizadas +1, ";
      $sql .= "       sw_finalizado = '".$sw_finalizado."', ";
      $sql .= "       fecha_siguiente_solictud = ".$this->DividirFecha($fecha_siguiente_solictud)." ";
      $sql .= "WHERE  solicitud_tratamiento_id = ".$datos['solicitud_tratamiento_id']." ";
    
      if(!$rst = $this->ConexionTransaccion($sql)) 
        return false;
      
      $this->Commit();
      
      return true;
    }
    /**
    * Funcion donde se obtiene los datos de la presolicitud
    *
    * @param array $datos Arreglo con los datos de la presolicitud
    *
    * @retun mixed
    */
    function ObtenerDatosSolicitud($datos)
    {
      $sql  = "SELECT solicitud_tratamiento_id,";
      $sql .= " 	    codigo_medicamento,";
      $sql .= " 	    dias_tratamiento ,";
      $sql .= " 	    total_solicitudes_realizadas ,";
      $sql .= " 	    intensidad ,";
      $sql .= " 	    intensidad_cantidad 	,";
      $sql .= " 	    TO_CHAR(fecha_inicio,'DD/MM/YYYY') AS fecha_inicio 	,";
      $sql .= " 	    TO_CHAR(fecha_finalizacion,'DD/MM/YYYY') AS fecha_finalizacion,";
      $sql .= " 	    TO_CHAR(fecha_siguiente_solictud,'DD/MM/YYYY') AS fecha_siguiente_solictud,";
      $sql .= " 	    cantidad 	,";
      $sql .= " 	    incremento_dias ";
      $sql .= "FROM   solicitudes_tratamiento  ";
      $sql .= "WHERE 	solicitud_tratamiento_id = ".$datos['solicitud_tratamiento_id']." ";
      
      $datos = array();
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      while(!$rst->EOF)
      {
        $datos =  $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();

      return $datos;
    }
    /**
    * Funcion donde se crea la solicitud de medicamentos e insumos,
    * de acuerdo a la parametrizacion dada
    * 
    * @param array $datos Arreglo con los datos para crear las solcitudes
    *
    * @return boolean
    */
    function CrearSolicitudMedicamentos($datos)
    {
      
   
      $pct = $datos['datosPaciente'];
      $est = $datos['datos_estacion'];
      
      $solicitud = array();
      $this->ConexionTransaccion();
      
      $codigos = array();
      
      $insumo = $medicamento = "";
      foreach($datos['opcion_presolicitud'] as $key => $dtl1)
      { 
        foreach($dtl1 as $k2 => $dtl2)
        {
          if($datos['presolicitud'][$key][$dtl2]['insumo'] == 1)
            $insumo = "I";
          else
            $medicamento = "M";
          $codigos[$dtl2]['cantidad'] += $datos['presolicitud'][$key][$dtl2]['cantidad'];
          
          
          
        }
      }
//      print_r($solicitud);
      if($medicamento == "M")
      {
        $sql = "SELECT NEXTVAL('public.hc_solicitudes_medicamentos_solicitud_id_seq') AS solicitud_id ";
        if(!$rst = $this->ConexionTransaccion($sql)) 
          return false;
        
        if(!$rst->EOF)
        {
          $solicitud['medicamento'] =  $rst->GetRowAssoc($ToUpper = false);
          $rst->MoveNext();
        }
        
        $sql  = "INSERT INTO hc_solicitudes_medicamentos ";
        $sql .= "   (";
        $sql .= "     solicitud_id,";
        $sql .= "     ingreso,";
        $sql .= "     bodega,";
        $sql .= "     empresa_id,";
        $sql .= "     centro_utilidad,";
        $sql .= "     usuario_id,";
        $sql .= "     sw_estado,";
        $sql .= "     fecha_solicitud,";
        $sql .= "     estacion_id,";
        $sql .= "     tipo_solicitud";
        $sql .= "   )";
        $sql .= "VALUES";
        $sql .= "   (";
        $sql .= "     '".$solicitud['medicamento']['solicitud_id']."',";
        $sql .= "      ".$pct['ingreso'].",";
        $sql .= "     '".$datos['bodega_presolicitud']."',";
        $sql .= "     '".$est['empresa_id']."', ";
        $sql .= "     '".$est['centro_utilidad']."', ";
        $sql .= "      ".UserGetUID().", ";
        $sql .= "     '0', ";
        $sql .= "     '".date("Y-m-d H:i:s")."', ";
        $sql .= "     '".$est['estacion_id']."', ";
        $sql .= "     '".$medicamento."'";
        $sql .= "   ) ";
        
        if(!$rst = $this->ConexionTransaccion($sql)) 
          return false;
      }
      
      if($insumo == "I")
      {
        $sql = "SELECT NEXTVAL('public.hc_solicitudes_medicamentos_solicitud_id_seq') AS solicitud_id ";
        if(!$rst = $this->ConexionTransaccion($sql)) 
          return false;
        
        if(!$rst->EOF)
        {
          $solicitud['insumo'] =  $rst->GetRowAssoc($ToUpper = false);
          $rst->MoveNext();
        }
        
        $sql  = "INSERT INTO hc_solicitudes_medicamentos ";
        $sql .= "   (";
        $sql .= "     solicitud_id,";
        $sql .= "     ingreso,";
        $sql .= "     bodega,";
        $sql .= "     empresa_id,";
        $sql .= "     centro_utilidad,";
        $sql .= "     usuario_id,";
        $sql .= "     sw_estado,";
        $sql .= "     fecha_solicitud,";
        $sql .= "     estacion_id,";
        $sql .= "     tipo_solicitud";
        $sql .= "   )";
        $sql .= "VALUES";
        $sql .= "   (";
        $sql .= "     '".$solicitud['insumo']['solicitud_id']."',";
        $sql .= "      ".$pct['ingreso'].",";
        $sql .= "     '".$datos['bodega_presolicitud']."',";
        $sql .= "     '".$est['empresa_id']."', ";
        $sql .= "     '".$est['centro_utilidad']."', ";
        $sql .= "      ".UserGetUID().", ";
        $sql .= "     '0', ";
        $sql .= "     '".date("Y-m-d H:i:s")."', ";
        $sql .= "     '".$est['estacion_id']."', ";
        $sql .= "     '".$insumo."'";
        $sql .= "   ) ";
        
        if(!$rst = $this->ConexionTransaccion($sql)) 
          return false;
      }
      
      foreach($datos['opcion_presolicitud'] as $key => $dtl1)
      { 
        foreach($dtl1 as $k2 => $dtl2)
        { 
          $sql = "";
          if(!$codigos[$dtl2]['insert'])
          {         
            $formulado = 0;
            if($datos['presolicitud'][$key][$dtl2]['insumo'] == 1)
            {
              $sql  = "INSERT INTO hc_solicitudes_insumos_d ";
              $sql .= "   ( ";
              $sql .= "     consecutivo_d,";
              $sql .= "     solicitud_id,";
              $sql .= "     codigo_producto,";
              $sql .= "     cantidad";
              $sql .= "   )";
              $sql .= "VALUES";
              $sql .= "   (";
              $sql .= "     DEFAULT,";
              $sql .= "     '".$solicitud['insumo']['solicitud_id']."',";
              $sql .= "     '".$dtl2."',";
              $sql .= "     '".$codigos[$dtl2]['cantidad']."'";
              $sql .= "   )";
            }
            else
            {
              if(!$pct['evolucion_id']) $pct['evolucion_id'] = 'NULL';
              $sql  = "INSERT INTO hc_solicitudes_medicamentos_d ";
              $sql .= "   (";
              $sql .= "     consecutivo_d,";
              $sql .= "     solicitud_id,";
              $sql .= "     medicamento_id,";
              $sql .= "     evolucion_id,";
              $sql .= "     cant_solicitada,";
              $sql .= "     ingreso";
              $sql .= "   )";
              $sql .= "VALUES";
              $sql .= "   (";
              $sql .= "     DEFAULT,";
              $sql .= "     '".$solicitud['medicamento']['solicitud_id']."',";
              $sql .= "     '".$dtl2."',";
              $sql .= "      ".$pct['evolucion_id'].",";
              $sql .= "      ".$codigos[$dtl2]['cantidad'].",";
              $sql .= "      ".$pct['ingreso']."";
              $sql .= "   ) ";
              
              $formulado = $this->VerificarFormulacion($pct['ingreso'],$dtl2);
            }
            $codigos[$dtl2]['insert'] = true;
            if(!$rst = $this->ConexionTransaccion($sql)) 
              return false;
            if($formulado == 2)
            {
              $sql  = "INSERT INTO hc_formulacion_medicamentos_eventos( ";
        			$sql .= "				ingreso,";
        			$sql .= "				evolucion_id,";
        			$sql .= "				codigo_producto,";
        			$sql .= "				usuario_id,";
        			$sql .= "				fecha_registro,";
        			$sql .= "				observacion,";
        			$sql .= "				via_administracion_id,";
        			$sql .= "				unidad_dosificacion,";
        			$sql .= "				dosis,";
        			$sql .= "				frecuencia,";
        			$sql .= "				cantidad, ";
        			$sql .= "				usuario_registro, ";
        			$sql .= "				dias_tratamiento ";
        			$sql .= "				) ";
              $sql .= "SELECT DISTINCT ".$pct['ingreso']." AS ingreso, ";
              $sql .= "       MAX(HE.evolucion_id) AS evolucion_id, ";
              $sql .= "       '".$dtl2."' AS codigo_producto, ";
              $sql .= "       HE.usuario_id, ";
              $sql .= "       NOW(),";
              $sql .= "       'FORMULACION MEDICAMENTO PRE-SOLICITUD' AS observacion,";
              $sql .= "       HM.via_administracion_id, ";
              $sql .= "       HM.unidad_dosificacion, ";
              $sql .= "       HM.dosis, ";
              $sql .= "       HM.frecuencia, ";
              $sql .= "       IM.cantidad, ";
              $sql .= "       ".UserGetUID()." AS usuario, ";
              $sql .= "       HM.dias_tratamiento ";
              $sql .= "FROM   insumos_x_medicamentos IM,  ";
              $sql .= "       solicitudes_tratamiento ST, ";
              $sql .= "       hc_formulacion_medicamentos HM, ";
              $sql .= "       hc_formulacion_medicamentos_eventos HE ";
              $sql .= "WHERE  IM.codigo_medicamento = ST.codigo_medicamento ";
              $sql .= "AND    ST.ingreso = ".$pct['ingreso']." ";
              $sql .= "AND    HM.ingreso = ".$pct['ingreso']." ";
              $sql .= "AND    HM.codigo_producto = IM.codigo_medicamento ";
              $sql .= "AND    HM.num_reg_formulacion = HE.num_reg ";
              $sql .= "AND    ST.solicitud_tratamiento_id = ".$key." ";
              $sql .= "GROUP BY 1,3,4,5,6,7,8,9,10,11,12,13 ";
              
              if(!$rst = $this->ConexionTransaccion($sql)) 
                return false;
            }
          }
        }
        $datos['solicitud_tratamiento_id'] = $key;
        $presol = $this->ObtenerDatosSolicitud($datos);
        
        $sql  = "INSERT INTO solicitudes_tratamiento_d ";
        $sql .= " ("; 
        $sql .= "   solicitud_tratamiento_d_id,"; 
        $sql .= "   solicitud_tratamiento_id,"; 
        $sql .= "   fecha_solicitud,"; 
        $sql .= "   usuario_id,"; 
        $sql .= "   solicitud_id,"; 
        $sql .= "   estado_solictud"; 
        $sql .= " )"; 
        $sql .= "VALUES"; 
        $sql .= " ("; 
        $sql .= "   DEFAULT,"; 
        $sql .= "   ".$key.",";
        $sql .= "   NOW(), ";
        $sql .= "   ".UserGetUID().", ";
        $sql .= "   ".$solicitud['medicamento']['solicitud_id'].", ";
        $sql .= "   '1' ";
        $sql .= " )";
        
        if(!$rst = $this->ConexionTransaccion($sql)) 
          return false;
        
        $f = explode("/",$presol['fecha_siguiente_solictud']);
        $fecha_siguiente_solictud = date("d/m/Y", mktime(0, 0, 0,$f[1],($f[0]+$presol['incremento_dias']),$f[2]));
        
        $sw_finalizado = "0";
        $ctl = AutoCarga::factory("ClaseUtil");

        if($ctl->CompararFechas($fecha_siguiente_solictud,$presol['fecha_finalizacion']) < 0)
        {
          //$fecha_siguiente_solictud = "'".$fecha_siguiente_solictud."'::date";
          $f = explode("/",$fecha_siguiente_solictud);
          $fecha_siguiente_solictud = "'".$f[2]."-".$f[1]."-".$f[0]."'";
        }
        else
        {
          $fecha_siguiente_solictud = "NULL";
          $sw_finalizado = "1";
        }
        
        $sql  = "UPDATE solicitudes_tratamiento ";
        $sql .= "SET    total_solicitudes_realizadas = 	total_solicitudes_realizadas +1, ";
        $sql .= "       sw_finalizado = '".$sw_finalizado."', ";
        $sql .= "       fecha_siguiente_solictud = ".$fecha_siguiente_solictud." ";
        $sql .= "WHERE  solicitud_tratamiento_id = ".$presol['solicitud_tratamiento_id']." ";
      
        if(!$rst = $this->ConexionTransaccion($sql)) 
          return false;
       
      }
      $this->Commit();
      return true;
      
    }
    /**
    * Funcion donde se obtiene el factor de conversion, por medicamento
    *
    */
    function ObtenerFactorConversion($medicamentos)
    {      
      $codigos = "";
      foreach($medicamentos as $key => $dtl)
       ($codigos == "")? $codigos .= $dtl['solicitud_tratamiento_id'] :$codigos .= ",".$dtl['solicitud_tratamiento_id'];

      $sql  = "SELECT ME.codigo_producto,";
      $sql .= " 	    HC.factor_conversion ";
      $sql .= "FROM   solicitudes_tratamiento ST, ";
      $sql .= "       hc_formulacion_medicamentos ME, ";
      $sql .= "       hc_formulacion_factor_conversion HC ";
      $sql .= "WHERE 	ST.solicitud_tratamiento_id IN (".$codigos.") ";
      $sql .= "AND    ME.ingreso = ST.ingreso ";
      $sql .= "AND    ME.codigo_producto = ST.codigo_medicamento ";
      $sql .= "AND    HC.codigo_producto = ME.codigo_producto ";
      $sql .= "AND    HC.unidad_dosificacion = ME.unidad_dosificacion ";
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $datos = array();
      while (!$rst->EOF)
			{
				$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
      
      return $datos;
    }
    function ObtenerFactorConversion1($medicamentos)
    {      
      $codigos = "";
      foreach($medicamentos as $key => $dtl)
       ($codigos == "")? $codigos .= $dtl['solicitud_tratamiento_id'] :$codigos .= ",".$dtl['solicitud_tratamiento_id'];

      $sql  = "SELECT ME.codigo_producto,";
      $sql .= " 	    HC.factor_conversion ";
      $sql .= "FROM   solicitudes_tratamiento ST, ";
      $sql .= "       hc_formulacion_medicamentos ME, ";
      $sql .= "       hc_formulacion_factor_conversion HC ";
      $sql .= "WHERE 	ST.solicitud_tratamiento_id IN (".$codigos.") ";
      $sql .= "AND    ME.ingreso = ST.ingreso ";
      $sql .= "AND    ME.codigo_producto = ST.codigo_medicamento ";
      $sql .= "AND    HC.codigo_producto = ME.codigo_producto ";
      $sql .= "AND    HC.unidad_dosificacion = ME.unidad_dosificacion ";
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $datos = array();
      while (!$rst->EOF)
			{
				$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
      
      return $datos;
    }
    /**
    *
    */
    function ObtenerInsumosPaquetes($request,$datos_estacion)
    {
      $sql  = "SELECT IV.descripcion,";
      $sql .= "       IV.descripcion_abreviada,";
      $sql .= "       IV.producto_id,";
      $sql .= "       EB.codigo_producto, ";
      $sql .= "       IT.cantidad, ";
      $sql .= "       IQ.insumo_paquete_id, ";
      $sql .= "       IQ.descripcion AS descripcion_paquete ";
      $sql .= "FROM   existencias_bodegas EB,";
      $sql .= "       inventarios_productos IV, ";
      $sql .= "       insumos_paquetes IQ, ";
      $sql .= "       insumos_paquetes_d IT, ";
      $sql .= "       inv_grupos_inventarios IG ";
      $sql .= "WHERE  EB.codigo_producto = IV.codigo_producto ";
      $sql .= "AND    IG.grupo_id = IV.grupo_id ";
      $sql .= "AND    IG.sw_insumos = '1' ";
      $sql .= "AND    EB.estado = '1' ";
      $sql .= "AND    IV.estado = '1' ";
      $sql .= "AND    IQ.insumo_paquete_id = IT.insumo_paquete_id ";
      $sql .= "AND    IT.codigo_producto = IV.codigo_producto ";
      $sql .= "AND    IQ.sw_activo = '1' ";
      
      if($request['busqueda'] != "")
        $sql .= "AND    IQ.descripcion ILIKE '%".$request['busqueda']."%' ";
            
      if($request['bodega'] && $request['bodega'] != "*/*")
      {
        $sql .= "AND    EB.bodega = '".$request['bodega']."' ";
        $sql .= "AND    EB.centro_utilidad = '".$datos_estacion['centro_utilidad']."' ";
        $sql .= "AND    EB.empresa_id = '".$datos_estacion['empresa_id']."' ";
      }
      
      $this->ProcesarSqlConteo("SELECT COUNT(*) FROM (".$sql.") A",$request['offset']);
      
      $sql .= "ORDER BY IQ.insumo_paquete_id ";
      $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $vector = array();
      while (!$rst->EOF)
      {
        $vector[] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      return $vector;
    }
    /**
    * Funcion donde se verifica si un medicamento ha sido formulado
    *
    * @param integer $ingreso Referencia al ingreso del paciente
    * @param string $medicamento Codigo del medicamento a verificar
    *
    * @return integer
    */
    function VerificarFormulacion($ingreso,$medicamento)
    {
      $sql  = "SELECT * ";
      $sql .= "FROM   hc_formulacion_medicamentos  ";
      $sql .= "WHERE  ingreso = ".$ingreso." ";
      $sql .= "AND    codigo_producto = '".$medicamento."' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $vector = array();
      if(!$rst->EOF)
      {
        $vector = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      
      if(empty($vector))
        return 2;
        
      return 1;
    }
    /* Funcion que permite Listar los medicamentos que han sido despachados
     * @param integer $ingreso Referencia al ingreso del paciente
    * @param string $letra Tipo de solicitud
    *
    * @return array
    */
    function Despachados_por_Medicamento_Solicitado($Ingreso,$letra,$medicamento_sol,$estacion_id)
    {     
    

               $sql = "       SELECT   B.codigo_producto, sum(B.cantidad)as cantidad,
                                              fc_descripcion_producto(B.codigo_producto) as descripcion 
                                   FROM   bodegas_documento_despacho_med AS A,
                                              bodegas_documento_despacho_med_d AS B,
                                              hc_solicitudes_medicamentos AS X,
                                              hc_solicitudes_medicamentos_d HM 
                                   WHERE X.solicitud_id = HM.solicitud_id 
                                   AND     X.documento_despacho = A.documento_despacho_id 
                                   AND     A.documento_despacho_id = B.documento_despacho_id 
                                   AND             X.ingreso = ".$Ingreso."
                                   AND     X.tipo_solicitud = '".$letra."'
                                   AND     X.sw_estado IN ('2','5')
                                  AND      HM.medicamento_id='".$medicamento_sol."'
                                  AND     HM.consecutivo_d=B.consecutivo_solicitud
           GROUP BY B.codigo_producto
                      Order by 1,2       
                                 ";
                         
              
            if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $vector = array();
      while (!$rst->EOF)
      {
        $vector[] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      return $vector;
    }
          function Consulta_Solicitud_Medicamentos($ingreso)
    {
          list($dbconnect) = GetDBconn();
		  
          GLOBAL $ADODB_FETCH_MODE;
          $query= " SELECT  A.ingreso,
                            A.codigo_producto,
                            A.num_reg,
                            A.num_reg_formulacion,
                            A.sw_estado,
                            A.observacion,
                            A.via_administracion_id,
                            A.unidad_dosificacion,
                            A.dosis,
                            A.frecuencia,
                            A.sw_confirmacion_formulacion,
                            A.sw_requiere_autorizacion_no_pos,
                            A.dias_tratamiento,
                            A.justificacion_no_pos_id,
                            A.cantidad*(CASE WHEN A.dias_tratamiento IS NULL THEN 5000 ELSE A.dias_tratamiento END)AS cantidad,          
                            'M' AS tipo_solicitud,
                            G.evolucion_id, 
                            G.usuario_id, 
                            TO_CHAR(B.fecha_registro,'YYYY-MM-DD HH24:MI:SS') as fecha_registro,
                            --C.descripcion AS producto,
							fc_descripcion_producto(A.codigo_producto) as producto,
                            C.descripcion_abreviada, 
                            C.contenido_unidad_venta,
                            C.unidad_id,
                            D.nombre AS via_administracion,
                            CASE WHEN E.sw_pos = '1' THEN 'POS' 
                                 ELSE 'NO POS' END AS codigo_pos,
                            F.descripcion AS unidad
                    FROM    hc_formulacion_medicamentos AS A
							INNER JOIN hc_formulacion_medicamentos_eventos G 
							ON(G.num_reg = A.num_reg_formulacion),
                            hc_formulacion_medicamentos_eventos AS B,
                            inventarios_productos AS C,
                            hc_vias_administracion AS D,
                            medicamentos AS E,
                            unidades AS F
                    
                    WHERE A.ingreso = ".$ingreso."
                    AND A.num_reg = B.num_reg
                    AND A.sw_estado IN ('1','2')
                    AND A.codigo_producto = C.codigo_producto
                    AND A.via_administracion_id = D.via_administracion_id
                    AND A.codigo_producto = E.codigo_medicamento
                    AND F.unidad_id = C.unidad_id
                    ORDER BY A.sw_estado, G.evolucion_id;";
          
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconnect->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
     
          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de medicamentos recetados";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }
          while ($data = $result->FetchRow())
          {
               $vector[] = $data;
          }
     
          return $vector;
    }
    function GetEstacionBodega_Existencias($datos,$sw,$codigo)
     {
          if($sw==1)
          {
               $filtro="AND b.sw_consumo_directo='0'";
               $Order="ORDER BY sw_bodega_principal DESC";
          }
          elseif($sw==2)
          {
               $filtro="AND b.sw_consumo_directo='1'";
               $Order="ORDER BY sw_bodega_principal DESC";
          }
          elseif($sw==3)
          {
	          $filtro="AND (b.sw_consumo_directo = '1' OR b.sw_consumo_directo = '0')";
               $Order="ORDER BY sw_bodega_principal DESC";          
          }
          
          
          $query="SELECT a.empresa_id,a.centro_utilidad,a.bodega,b.descripcion,c.existencia
                    FROM bodegas_estaciones a,bodegas b,existencias_bodegas c
                    WHERE a.estacion_id='".$datos[estacion_id]."'
                    AND a.centro_utilidad=b.centro_utilidad
                    AND a.empresa_id=b.empresa_id
                    AND a.bodega=b.bodega
                    AND a.bodega=c.bodega
                    AND c.existencia > 0
                    AND (b.sw_restriccion_stock = '0' OR b.sw_restriccion_stock = '1')
                    $filtro
                    AND c.codigo_producto='$codigo'
                    AND a.empresa_id=c.empresa_id
                    AND a.centro_utilidad=c.centro_utilidad
                    AND a.centro_utilidad='".$datos[centro_utilidad]."'
                    AND a.empresa_id='".$datos[empresa_id]."'
                    $Order;";

     if(!$rst = $this->ConexionBaseDatos($query))
     return false;
      
      $datos = array();
      while (!$rst->EOF)
      {
	$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
	$rst->MoveNext();
      }
      $rst->Close();
      
      return $datos;
     }
      function GetCantidades_BodegaPaciente($ingreso,$codigo_producto)
     {   
            list($dbconnect) = GetDBconn();
            GLOBAL $ADODB_FETCH_MODE;		    
	    $sql="SELECT *            					
	           FROM bodega_paciente
                WHERE ingreso = ".$ingreso."
                AND codigo_producto = '".$codigo_producto."';";
	  $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconnect->Execute($sql);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
     
          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de medicamentos recetados";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }
          while ($data = $result->FetchRow())
          {
               $vector[] = $data;
          }
     
          return $vector;
     }
          function Consultar_Control_Suministro($codigo_producto, $ingreso, $tipo_solicitud)
     {
         
          $_tableName = "";
          //Busqueda de suministros por tipos de producto (Medicamentos - Soluciones)
          if($tipo_solicitud == "M")
          {
            $_tableName = "hc_formulacion_suministro_medicamentos";
          }else{
            $_tableName = "hc_formulacion_suministro_soluciones";
          }
          
          // Todos los suministros del ingreso
          $sql = "SELECT A.*, B.nombre
                    FROM  $_tableName AS A, 
                          system_usuarios AS B
                    WHERE A.ingreso = '".$ingreso."'
                    AND A.codigo_producto = '".$codigo_producto."'
                    AND A.sw_estado = '1'
                    AND A.usuario_id_control = B.usuario_id
                    ORDER BY suministro_id DESC;";
          if(!$rst = $this->ConexionBaseDatos($sql))
	  return false;
	    
	    $datos = array();
	    while (!$rst->EOF)
	    {
	      $datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
	      $rst->MoveNext();
	    }
	    $rst->Close();
	    
	    return $datos;
     }
      function SeleccionUnidadSuministro($unidad_dosificacion, $unidad)
     {
                
          $sql = "SELECT Count(*)
          		    FROM hc_formulacion_cruce_unidades
                        WHERE unidad_dosificacion = '".trim($unidad_dosificacion)."'
                        AND unidad_id = '".trim($unidad)."';";

     	if(!$rst = $this->ConexionBaseDatos($sql))
	  return false;
	    
	    $datos = array();
	    while (!$rst->EOF)
	    {
	      $datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
	      $rst->MoveNext();
	    }
	    $rst->Close();
	    
	   if($rst->fields[0] > 0)
          { return 1; } else { return 0; }
     }
     function SeleccionFactorConversion($codigo, $unidad, $unidad_dosificacion)
     {
         list($dbconnect) = GetDBconn();
            GLOBAL $ADODB_FETCH_MODE;		  
          $sql = "SELECT * 
          			FROM hc_formulacion_factor_conversion
                         WHERE codigo_producto = '".$codigo."'
                         AND unidad_id = '".trim($unidad)."'
                         AND unidad_dosificacion = '".trim($unidad_dosificacion)."';";

           $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconnect->Execute($sql);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
     
          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de medicamentos recetados";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }
          while ($data = $result->FetchRow())
          {
               $vector[] = $data;
          }
     
          return $vector;
     }
     function Consulta_Solicitud_Insumos($ingreso)
    {
      list($dbconnect) = GetDBconn();
      GLOBAL $ADODB_FETCH_MODE;

      $query = " SELECT DISTINCT
                        B.codigo_producto,
                        X.empresa_id, 
                        X.centro_utilidad, 
                        INV.descripcion,
                        BOD.codigo_producto AS non_existencia,     
                        BOD.stock_paciente
                FROM    bodegas_documento_despacho_med A,
                        bodegas_documento_despacho_ins_d B,
                        hc_solicitudes_medicamentos X,
                        inventarios_productos INV
                        LEFT JOIN bodega_paciente BOD 
                        ON( INV.codigo_producto = BOD.codigo_producto AND 
                            BOD.ingreso = ".$ingreso." )
                WHERE   X.ingreso = ".$ingreso."
                AND     X.tipo_solicitud = 'I'
                AND     X.sw_estado IN ('2','5')
                AND     A.documento_despacho_id = X.documento_despacho
                AND     B.documento_despacho_id = A.documento_despacho_id
                AND     B.codigo_producto = INV.codigo_producto
                ORDER BY non_existencia;";

      $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
      $result = $dbconnect->Execute($query);
      $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
 
      if ($dbconnect->ErrorNo() != 0)
      {
        $this->error = "Error al buscar en la consulta de medicamentos recetados";
        $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
        return false;
      }
      
      while ($data = $result->FetchRow())
        $vector[] = $data;
     
      return $vector;
    }
  }
?>