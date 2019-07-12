<?php
  /******************************************************************************
  * $Id: DetalleCta.class.php,v 1.3 2007/03/08 20:59:58 lorena Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.3 $ 
	* 
	* @autor Lorena Aragon Galindo
  * Proposito del Archivo:	Manejo de la logica del proceso de paqetes de cargos en la cuenta 
  ********************************************************************************/
	class DetalleCta
	{
		var $offset = 0;
		
		function DetalleCta(){}
		
    
		/**********************************************************************************
    * Busca los cargos de la cuenta 
    *
    * @access public 
    * @params int $Cuenta numero de la cuenta a consultar
    * @return array
    ***********************************************************************************/
    
    function BuscarDetalleCuenta($Cuenta){                   
       /*
       1er select de cargos normales
       2do select de cargos agrupados
       3er select de cargos de la liquidaciones qx y medicamentos e insumos
       4to query medcamentos e insumos
       5to query paquetes
       */
        $query = " 
                  (
                    SELECT   
                              a.transaccion,
                              a.cargo,
                              a.tarifario_id,
                              a.cargo_cups,
                              a.cantidad,
                              a.precio,
                              a.valor_nocubierto,                              
                              a.fecha_cargo,                            
                              a.valor_cubierto,
                              a.valor_cargo,
                              a.porcentaje_descuento_paciente,
                              a.porcentaje_descuento_empresa,
                              a.valor_descuento_empresa,
                              a.valor_descuento_paciente,
                              a.facturado,
                              case a.facturado when 1 then a.valor_cargo else 0 end as fac,
                              a.autorizacion_int as interna,
                              a.autorizacion_ext as externa,
                              NULL as codigo_agrupamiento_id,
                              NULL as consecutivo, 
                              NULL::integer as cuenta_liquidacion_qx_id,
                              b.descripcion,
                              NULL::integer as paquete_codigo_id
                      FROM cuentas_detalle as a,tarifarios_detalle b
                      WHERE a.numerodecuenta='$Cuenta'
                      AND a.cargo=b.cargo 
                      AND a.tarifario_id=b.tarifario_id   
                      AND a.codigo_agrupamiento_id IS NULL 
                      AND a.consecutivo IS NULL
                      AND a.paquete_codigo_id IS NULL                                                                 
                  )                    
                  UNION                    
                  (
                    SELECT   
                            NULL as transaccion,
                            NULL as cargo,
                            NULL as tarifario_id,
                            NULL as cargo_cups,
                            sum(a.cantidad) as cantidad,
                            NULL as precio,
                            sum(a.valor_nocubierto) as valor_nocubierto,                            
                            NULL as fecha_cargo,                            
                            sum(a.valor_cubierto) as valor_cubierto,
                            sum(a.valor_cargo) as valor_cargo,
                            NULL as porcentaje_descuento_paciente,
                            NULL as porcentaje_descuento_empresa,
                            sum(a.valor_descuento_empresa) as valor_descuento_empresa,
                            sum(a.valor_descuento_paciente) as valor_descuento_paciente,
                            NULL as facturado,
                            NULL as fac,
                            NULL as interna,
                            NULL as externa,
                            NULL as codigo_agrupamiento_id,
                            NULL as consecutivo, 
                            NULL::integer as cuenta_liquidacion_qx_id,
                            b.descripcion,
                            NULL::integer as paquete_codigo_id
                    FROM cuentas_detalle as a, cuentas_codigos_agrupamiento b
                    WHERE a.numerodecuenta='$Cuenta' 
                    AND a.codigo_agrupamiento_id=b.codigo_agrupamiento_id
                    AND b.cuenta_liquidacion_qx_id IS NULL                                         
                    AND a.consecutivo IS NULL
                    AND a.paquete_codigo_id IS NULL 
                    GROUP BY b.descripcion
                  )
                  UNION                    
                  (
                    SELECT   
                            NULL as transaccion,
                            NULL as cargo,
                            NULL as tarifario_id,
                            NULL as cargo_cups,
                            sum(a.cantidad) as cantidad,
                            NULL as precio,
                            sum(a.valor_nocubierto) as valor_nocubierto,                            
                            NULL as fecha_cargo,                            
                            sum(a.valor_cubierto) as valor_cubierto,
                            sum(a.valor_cargo) as valor_cargo,
                            NULL as porcentaje_descuento_paciente,
                            NULL as porcentaje_descuento_empresa,
                            sum(a.valor_descuento_empresa) as valor_descuento_empresa,
                            sum(a.valor_descuento_paciente) as valor_descuento_paciente,
                            NULL as facturado,
                            NULL as fac,
                            NULL as interna,
                            NULL as externa,
                            NULL as codigo_agrupamiento_id,
                            NULL as consecutivo, 
                            b.cuenta_liquidacion_qx_id,
                            'ACTO QUIRURGICO No. '||b.cuenta_liquidacion_qx_id as descripcion,
                            NULL::integer as paquete_codigo_id
                    FROM cuentas_detalle as a, cuentas_codigos_agrupamiento b
                    WHERE a.numerodecuenta='$Cuenta' 
                    AND a.codigo_agrupamiento_id=b.codigo_agrupamiento_id
                    AND b.cuenta_liquidacion_qx_id IS NOT NULL                                                             
                    AND a.paquete_codigo_id IS NULL                    
                    GROUP BY b.cuenta_liquidacion_qx_id
                  )
                  UNION                    
                  (
                    SELECT   
                            NULL as transaccion,
                            NULL as cargo,
                            NULL as tarifario_id,
                            NULL as cargo_cups,
                            sum(a.cantidad) as cantidad,
                            NULL as precio,
                            sum(a.valor_nocubierto) as valor_nocubierto,                            
                            NULL as fecha_cargo,                            
                            sum(a.valor_cubierto) as valor_cubierto,
                            sum(a.valor_cargo) as valor_cargo,
                            NULL as porcentaje_descuento_paciente,
                            NULL as porcentaje_descuento_empresa,
                            sum(a.valor_descuento_empresa) as valor_descuento_empresa,
                            sum(a.valor_descuento_paciente) as valor_descuento_paciente,
                            NULL as facturado,
                            NULL as fac,
                            NULL as interna,
                            NULL as externa,
                            NULL as codigo_agrupamiento_id,
                            NULL as consecutivo, 
                            NULL::integer as cuenta_liquidacion_qx_id,
                            b.descripcion,
                            NULL::integer as paquete_codigo_id
                    FROM cuentas_detalle as a, 
                         cuentas_codigos_agrupamiento b, 
                         bodegas_documentos_d c
                    WHERE a.numerodecuenta='$Cuenta' 
                    AND a.codigo_agrupamiento_id=b.codigo_agrupamiento_id                                         
                    AND b.cuenta_liquidacion_qx_id IS NULL
                    AND a.consecutivo=c.consecutivo
                    AND b.bodegas_doc_id=c.bodegas_doc_id
                    AND b.numeracion=c.numeracion                    
                    AND a.paquete_codigo_id IS NULL
                    GROUP BY b.descripcion 
                     
                  )                  
                  UNION                                      
                  (
                    SELECT   
                            NULL as transaccion,
                            NULL as cargo,
                            NULL as tarifario_id,
                            NULL as cargo_cups,
                            sum(a.cantidad) as cantidad,
                            NULL as precio,
                            sum(a.valor_nocubierto) as valor_nocubierto,                            
                            NULL as fecha_cargo,                            
                            sum(a.valor_cubierto) as valor_cubierto,
                            sum(a.valor_cargo) as valor_cargo,
                            NULL as porcentaje_descuento_paciente,
                            NULL as porcentaje_descuento_empresa,
                            sum(a.valor_descuento_empresa) as valor_descuento_empresa,
                            sum(a.valor_descuento_paciente) as valor_descuento_paciente,
                            NULL as facturado,
                            NULL as fac,
                            NULL as interna,
                            NULL as externa,
                            NULL as codigo_agrupamiento_id,
                            NULL as consecutivo, 
                            NULL::integer as cuenta_liquidacion_qx_id,
                            'PAQUETE No. '||a.paquete_codigo_id as descripcion,
                            a.paquete_codigo_id
                    FROM cuentas_detalle as a
                    WHERE a.numerodecuenta='$Cuenta'                                
                    AND a.paquete_codigo_id IS NOT NULL 
                    GROUP BY a.paquete_codigo_id,descripcion
                  )
                   
                  ";
        if(!$resultado = $this->ConexionBaseDatos($query))
        return false;
        while(!$resultado->EOF){
            $vars[]=$resultado->GetRowAssoc($toUpper=false);
            $resultado->MoveNext();
        }
        $resultado->Close();          
        return $vars;
        
    }
    
    /**********************************************************************************
    * Consulta los paquetes de la cuenta.
    *
    * @access public          
    * @return array
    ***********************************************************************************/ 
    
    function BuscarPaquetesCuentas($Cuenta,$paqueteId){              
          
          $query="SELECT a.*,c.codigo_producto,
               (CASE WHEN a.consecutivo IS NOT NULL THEN d.descripcion ELSE a.descripcion END) as descripcion
                FROM 
                (
                  SELECT 
                       a.tarifario_id,
                       a.cargo,
                       e.descripcion,
                       f.descripcion as des_tarifario,
                       a.cantidad,                       
                       a.precio,  
                       a.porcentaje_descuento_empresa,                                              
                       a.valor_cargo,  
                       a.valor_nocubierto,
                       a.valor_cubierto,
                       a.sw_paquete_facturado as facturado,   
                       a.fecha_cargo,
                       a.usuario_id,
                       a.fecha_registro,
                       a.sw_liq_manual,
                       a.valor_descuento_empresa,
                       a.valor_descuento_paciente,
                       a.porcentaje_descuento_paciente,
                       a.servicio_cargo,
                       a.autorizacion_int,
                       a.autorizacion_ext,
                       a.porcentaje_gravamen,
                       a.sw_cuota_paciente,
                       a.sw_cuota_moderadora,
                       a.consecutivo,
                       a.cargo_cups,
                       a.codigo_agrupamiento_id,
                       a.sw_cargue,
                       a.empresa_id,
                       a.centro_utilidad,
                       a.departamento_al_cargar,
                       a.empresa_id,
                       a.centro_utilidad,
                       a.departamento,
                       a.transaccion,
                       a.numerodecuenta,
                       b.bodegas_doc_id,
                       b.numeracion,
                       b.descripcion as des,
                       b.cuenta_liquidacion_qx_id                                          
                  FROM cuentas_detalle as a,
                  cuentas_codigos_agrupamiento as b,
                  tarifarios_detalle e,
                  tarifarios f
                  WHERE a.numerodecuenta='$Cuenta'
                  AND a.cargo=e.cargo 
                  AND a.tarifario_id=e.tarifario_id
                  AND e.tarifario_id=f.tarifario_id
                  AND a.paquete_codigo_id='$paqueteId'
                  AND a.codigo_agrupamiento_id=b.codigo_agrupamiento_id 
                  ORDER BY a.fecha_cargo                                    
                ) as a                 
                LEFT JOIN bodegas_documentos_d as c ON (a.consecutivo=c.consecutivo AND a.bodegas_doc_id=c.bodegas_doc_id AND a.numeracion=c.numeracion)
                LEFT JOIN inventarios_productos as d ON(c.codigo_producto=d.codigo_producto) 
                
              ";  
          if(!$resultado = $this->ConexionBaseDatos($query))
          return false;
          while(!$resultado->EOF){
              $vars[]=$resultado->GetRowAssoc($toUpper=false);
              $resultado->MoveNext();
          }        
          $resultado->Close();              
          return $vars;            
          
    } 
    
    /*****************************************************
    * Se encarga consultar los datos de la cirugia en la cuenta del paciente
    * @access public
    * @return array
    * @param $NoLiquidacion numero de la liquidacion
    * @param $Cuenta numero de la cuenta
    ******************************************************/
    
    function DatosCirugia($NoLiquidacion,$Cuenta){
    
      GLOBAL $ADODB_FETCH_MODE;      
      $query="SELECT d.tipo_id_cirujano,d.cirujano_id,d.cargo_cups,d.consecutivo_procedimiento,
                  c.tarifario_id as tarifario_id_procedimiento,c.cargo as cargo_procedimiento,
                  c.tipo_cargo_qx_id,b.tarifario_id,b.cargo,c.porcentaje,c.secuencia,b.valor_nocubierto,b.valor_cubierto,
                  e.tipo_tercero_id as tipo_id_profesional,e.tercero_id as profesional_id,f.descripcion,uv.uvrs
                  FROM cuentas_codigos_agrupamiento a,cuentas_detalle b
                  LEFT JOIN cuentas_detalle_profesionales e ON (b.transaccion=e.transaccion)
                  ,cuentas_cargos_qx_procedimientos c
                  JOIN cuentas_liquidaciones_qx_procedimientos_cargos uv ON (uv.consecutivo_procedimiento=c.consecutivo_procedimiento AND uv.tarifario_id=c.tarifario_id AND uv.cargo=c.cargo)
                  ,cuentas_liquidaciones_qx_procedimientos d,tarifarios_detalle f,tipos_cargos_qx g
                  WHERE a.cuenta_liquidacion_qx_id='".$NoLiquidacion."' AND a.descripcion='ACTO QUIRURGICO' AND a.bodegas_doc_id IS NULL AND a.numeracion IS NULL AND
                  a.codigo_agrupamiento_id=b.codigo_agrupamiento_id AND
                  b.transaccion=c.transaccion AND c.consecutivo_procedimiento=d.consecutivo_procedimiento AND c.cargo=f.cargo AND
                  c.tarifario_id=f.tarifario_id AND c.tipo_cargo_qx_id=g.tipo_cargo_qx_id
                  AND b.numerodecuenta='$Cuenta' AND b.paquete_codigo_id IS NULL
                  ORDER BY c.secuencia,g.indice_de_orden";
      
      $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
      if(!$resultado = $this->ConexionBaseDatos($query))
          return false;
      $ADODB_FETCH_MODE = ADODB_FETCH_NUM;     
      
      while($cargo=$resultado->FetchRow()){
          $secuencia=explode('-',$cargo['secuencia']);
          $v[$secuencia[0]][$secuencia[1]]['tipo_id_cirujano']=$cargo['tipo_id_cirujano'];
          $v[$secuencia[0]][$secuencia[1]]['cirujano_id']=$cargo['cirujano_id'];
          $v[$secuencia[0]][$secuencia[1]]['consecutivo_procedimiento']=$cargo['consecutivo_procedimiento'];
          $v[$secuencia[0]][$secuencia[1]]['cargo_cups']=$cargo['cargo_cups'];
          $v[$secuencia[0]][$secuencia[1]]['tarifario_id']=$cargo['tarifario_id_procedimiento'];
          $v[$secuencia[0]][$secuencia[1]]['cargo']=$cargo['cargo_procedimiento'];
          $v[$secuencia[0]][$secuencia[1]]['descripcion']=$cargo['descripcion'];
          $v[$secuencia[0]][$secuencia[1]]['uvrs']=$cargo['uvrs'];
  
          $v[$secuencia[0]][$secuencia[1]]['liquidacion'][$cargo['tipo_cargo_qx_id']]['tarifario_id']=$cargo['tarifario_id'];
          $v[$secuencia[0]][$secuencia[1]]['liquidacion'][$cargo['tipo_cargo_qx_id']]['cargo']=$cargo['cargo'];
          $v[$secuencia[0]][$secuencia[1]]['liquidacion'][$cargo['tipo_cargo_qx_id']]['valor_cubierto']=$cargo['valor_cubierto'];
          $v[$secuencia[0]][$secuencia[1]]['liquidacion'][$cargo['tipo_cargo_qx_id']]['valor_no_cubierto']=$cargo['valor_nocubierto'];
          $v[$secuencia[0]][$secuencia[1]]['liquidacion'][$cargo['tipo_cargo_qx_id']]['PORCENTAJE']=$cargo['porcentaje'];
          $v[$secuencia[0]][$secuencia[1]]['liquidacion'][$cargo['tipo_cargo_qx_id']]['SECUENCIA']=$cargo['secuencia'];
          if(!empty($cargo['tipo_id_profesional']) && !empty($cargo['profesional_id'])){
              $v[$secuencia[0]][$secuencia[1]]['liquidacion'][$cargo['tipo_cargo_qx_id']]['tipo_id_tercero']=$cargo['tipo_id_profesional'];
              $v[$secuencia[0]][$secuencia[1]]['liquidacion'][$cargo['tipo_cargo_qx_id']]['tercero_id']=$cargo['profesional_id'];
          }
      }
      $vector[0]=$v;
      
      $query="SELECT a.*
      FROM (SELECT c.precio as precio_plan,c.cantidad,c.valor_cargo,c.valor_cubierto,c.valor_nocubierto as valor_no_cubierto,c.porcentaje_gravamen,
      e.descripcion,c.tarifario_id,c.cargo,c.facturado,c.sw_cuota_paciente,c.sw_cuota_moderadora,c.valor_descuento_empresa,
      c.valor_descuento_paciente,'fijo' as tipo_equipo,b.equipo_id,
      (SELECT te.descripcion FROM qx_equipos_quirofanos te WHERE te.equipo_id=b.equipo_id) as descripcion_equipo,
      b.duracion,c.cargo_cups
      FROM cuentas_liquidaciones_qx_equipos_fijos b,
      cuentas_codigos_agrupamiento a,cuentas_detalle c,tarifarios_detalle e
      WHERE b.cuenta_liquidacion_qx_id='".$NoLiquidacion."'  AND
      b.cuenta_liquidacion_qx_id=a.cuenta_liquidacion_qx_id AND
      a.codigo_agrupamiento_id=c.codigo_agrupamiento_id AND
      b.transaccion=c.transaccion AND
      c.cargo=e.cargo AND c.tarifario_id=e.tarifario_id
      AND c.numerodecuenta='$Cuenta' AND c.paquete_codigo_id IS NULL
      UNION
      SELECT c.precio as precio_plan,c.cantidad,c.valor_cargo,c.valor_cubierto,c.valor_nocubierto as valor_no_cubierto,c.porcentaje_gravamen,
      e.descripcion,c.tarifario_id,c.cargo,c.facturado,c.sw_cuota_paciente,c.sw_cuota_moderadora,c.valor_descuento_empresa,
      c.valor_descuento_paciente,'movil' as tipo_equipo,b.equipo_id,
      (SELECT te.descripcion FROM qx_equipos_moviles te WHERE te.equipo_id=b.equipo_id) as descripcion_equipo,
      b.duracion,c.cargo_cups
      FROM cuentas_liquidaciones_qx_equipos_moviles b,
      cuentas_codigos_agrupamiento a,cuentas_detalle c,tarifarios_detalle e
      WHERE b.cuenta_liquidacion_qx_id='".$NoLiquidacion."'  AND
      b.cuenta_liquidacion_qx_id=a.cuenta_liquidacion_qx_id AND
      a.codigo_agrupamiento_id=c.codigo_agrupamiento_id AND
      b.transaccion=c.transaccion AND
      c.cargo=e.cargo AND c.tarifario_id=e.tarifario_id
      AND c.numerodecuenta='$Cuenta' AND c.paquete_codigo_id IS NULL
      ) a
      ORDER BY a.tipo_equipo";
      if(!$resultado = $this->ConexionBaseDatos($query))
      return false;                  
      while(!$resultado->EOF){
          $vars[]=$resultado->GetRowAssoc($toUpper=false);
          $resultado->MoveNext();
      }
      $vector[1]=$vars;
      return $vector;
    }
    
    
    /*****************************************************
    * Se encarga consultar los medicamentos de la cirugia en la cuenta del paciente
    * @access public
    * @return array
    * @param $NoLiquidacion numero de la liquidacion
    * @param $Cuenta numero de la cuenta
    ******************************************************/
    function CargosMedicamentosCuentaPaciente($NoLiquidacion,$Cuenta){
    
        $query="SELECT c.codigo_producto,sum(c.cantidad) as cantidad,sum(b.valor_cargo) as valor_cargo,sum(b.valor_cubierto) as valor_cubierto,
        sum(b.valor_nocubierto) as valor_nocubierto,b.facturado,
        (SELECT d.descripcion FROM inventarios_productos d WHERE c.codigo_producto=d.codigo_producto) as descripcion
        FROM cuentas_codigos_agrupamiento a,cuentas_detalle b,bodegas_documentos_d c
        WHERE a.cuenta_liquidacion_qx_id='".$NoLiquidacion."' AND a.codigo_agrupamiento_id=b.codigo_agrupamiento_id AND b.cargo='IMD' AND
        a.bodegas_doc_id=c.bodegas_doc_id AND a.numeracion=c.numeracion AND b.consecutivo=c.consecutivo
        AND b.numerodecuenta=$Cuenta AND b.paquete_codigo_id IS NULL
        GROUP BY c.codigo_producto,b.facturado";
        if(!$resultado = $this->ConexionBaseDatos($query))
        return false;  
        if($resultado->RecordCount()>0){
          while(!$resultado->EOF){
            $vars[]=$resultado->GetRowAssoc($toUpper=false);
            $resultado->MoveNext();
          }
        }        
        return $vars;
      }

    /*****************************************************
    * Se encarga consultar los medicamentos devueltos de la cirugia en la cuenta del paciente
    * @access public
    * @return array
    * @param $NoLiquidacion numero de la liquidacion
    * @param $Cuenta numero de la cuenta
    ******************************************************/
    
    function CargosMedicamentosCuentaPacienteDevol($NoLiquidacion,$Cuenta){
      $query="SELECT c.codigo_producto,sum(c.cantidad) as cantidad,sum(b.valor_cargo) as valor_cargo,sum(b.valor_cubierto) as valor_cubierto,
      sum(b.valor_nocubierto) as valor_nocubierto,b.facturado,
      (SELECT d.descripcion FROM inventarios_productos d WHERE c.codigo_producto=d.codigo_producto) as descripcion
      FROM cuentas_codigos_agrupamiento a,cuentas_detalle b,bodegas_documentos_d c
      WHERE a.cuenta_liquidacion_qx_id='".$NoLiquidacion."' AND a.codigo_agrupamiento_id=b.codigo_agrupamiento_id AND b.cargo='DIMD' AND
      a.bodegas_doc_id=c.bodegas_doc_id AND a.numeracion=c.numeracion AND b.consecutivo=c.consecutivo
      AND b.numerodecuenta=$Cuenta AND b.paquete_codigo_id IS NULL
      GROUP BY c.codigo_producto,b.facturado";
      if(!$resultado = $this->ConexionBaseDatos($query))
      return false;       
      if($resultado->RecordCount()>0){
        while(!$resultado->EOF){
          $vars[]=$resultado->GetRowAssoc($toUpper=false);
          $resultado->MoveNext();
        }
      }      
      return $vars;
    }
    
    function DatosEquiposQX($NoLiquidacion){
      $query="SELECT a.*
      FROM (SELECT c.precio as precio_plan,c.cantidad,c.valor_cargo,c.valor_cubierto,c.valor_nocubierto as valor_no_cubierto,c.porcentaje_gravamen,
      e.descripcion,c.tarifario_id,c.cargo,c.facturado,c.sw_cuota_paciente,c.sw_cuota_moderadora,c.valor_descuento_empresa,
      c.valor_descuento_paciente,'fijo' as tipo_equipo,b.equipo_id,
      (SELECT te.descripcion FROM qx_equipos_quirofanos te WHERE te.equipo_id=b.equipo_id) as descripcion_equipo,
      b.duracion,c.cargo_cups
      FROM cuentas_liquidaciones_qx_equipos_fijos b,
      cuentas_codigos_agrupamiento a,cuentas_detalle c,tarifarios_detalle e
      WHERE b.cuenta_liquidacion_qx_id='".$NoLiquidacion."'  AND
      b.cuenta_liquidacion_qx_id=a.cuenta_liquidacion_qx_id AND
      a.codigo_agrupamiento_id=c.codigo_agrupamiento_id AND
      b.transaccion=c.transaccion AND
      c.cargo=e.cargo AND c.tarifario_id=e.tarifario_id
      UNION
      SELECT c.precio as precio_plan,c.cantidad,c.valor_cargo,c.valor_cubierto,c.valor_nocubierto as valor_no_cubierto,c.porcentaje_gravamen,
      e.descripcion,c.tarifario_id,c.cargo,c.facturado,c.sw_cuota_paciente,c.sw_cuota_moderadora,c.valor_descuento_empresa,
      c.valor_descuento_paciente,'movil' as tipo_equipo,b.equipo_id,
      (SELECT te.descripcion FROM qx_equipos_moviles te WHERE te.equipo_id=b.equipo_id) as descripcion_equipo,
      b.duracion,c.cargo_cups
      FROM cuentas_liquidaciones_qx_equipos_moviles b,
      cuentas_codigos_agrupamiento a,cuentas_detalle c,tarifarios_detalle e
      WHERE b.cuenta_liquidacion_qx_id='".$NoLiquidacion."'  AND
      b.cuenta_liquidacion_qx_id=a.cuenta_liquidacion_qx_id AND
      a.codigo_agrupamiento_id=c.codigo_agrupamiento_id AND
      b.transaccion=c.transaccion AND
      c.cargo=e.cargo AND c.tarifario_id=e.tarifario_id
      ) a
      ORDER BY a.tipo_equipo";
      if(!$resultado = $this->ConexionBaseDatos($query))
      return false;       
      if($resultado->RecordCount()>0){
        while(!$resultado->EOF){
          $vars[]=$resultado->GetRowAssoc($toUpper=false);
          $resultado->MoveNext();
        }
      }      
      return $vars; 
    
    }
    
    /**********************************************************************************
    * Consulta los cargos agrupados en la cuenta.
    *
    * @access public          
    * @return array
    ***********************************************************************************/ 
    
    function BuscarCargosAgrupadosCuentas($Cuenta,$descripcion){
              
        $query="SELECT a.*,c.codigo_producto,
               (CASE WHEN a.consecutivo IS NOT NULL THEN d.descripcion ELSE a.descripcion END) as descripcion
                FROM 
                (
                  SELECT 
                       a.tarifario_id,
                       a.cargo,
                       e.descripcion,
                       f.descripcion as des_tarifario,
                       a.cantidad,                       
                       a.precio,  
                       a.porcentaje_descuento_empresa,                                              
                       a.valor_cargo,  
                       a.valor_nocubierto,
                       a.valor_cubierto,
                       a.facturado,   
                       a.fecha_cargo,
                       a.usuario_id,
                       a.fecha_registro,
                       a.sw_liq_manual,
                       a.valor_descuento_empresa,
                       a.valor_descuento_paciente,
                       a.porcentaje_descuento_paciente,
                       a.servicio_cargo,
                       a.autorizacion_int,
                       a.autorizacion_ext,
                       a.porcentaje_gravamen,
                       a.sw_cuota_paciente,
                       a.sw_cuota_moderadora,
                       a.consecutivo,
                       a.cargo_cups,
                       a.codigo_agrupamiento_id,
                       a.sw_cargue,
                       a.departamento_al_cargar,
                       a.empresa_id,
                       a.centro_utilidad,
                       a.departamento,
                       a.transaccion,
                       a.numerodecuenta,
                       b.bodegas_doc_id,
                       b.numeracion,
                       b.descripcion as des,
                       b.cuenta_liquidacion_qx_id                                          
                  FROM cuentas_detalle as a,
                  cuentas_codigos_agrupamiento as b,
                  tarifarios_detalle e,
                  tarifarios f
                  WHERE a.numerodecuenta='$Cuenta'
                  AND a.cargo=e.cargo 
                  AND a.tarifario_id=e.tarifario_id
                  AND e.tarifario_id=f.tarifario_id
                  AND a.paquete_codigo_id IS NULL                      
                  AND a.codigo_agrupamiento_id=b.codigo_agrupamiento_id
                  AND b.descripcion='$descripcion' 
                  AND b.cuenta_liquidacion_qx_id IS NULL
                  ORDER BY a.fecha_cargo
                )as a                 
                LEFT JOIN bodegas_documentos_d as c ON (a.consecutivo=c.consecutivo AND a.bodegas_doc_id=c.bodegas_doc_id AND a.numeracion=c.numeracion)
                LEFT JOIN inventarios_productos as d ON(c.codigo_producto=d.codigo_producto) 
                ORDER BY c.codigo_producto;
              ";        
          
          if(!$resultado = $this->ConexionBaseDatos($query))
          return false;
          while(!$resultado->EOF){
              $vars[]=$resultado->GetRowAssoc($toUpper=false);
              $resultado->MoveNext();
          }        
          $resultado->Close();              
          return $vars; 
    }  
    
    /*************************************************
    * Busca los datos principales del tercero(responsable) nombre y tipo_id_tercero.
    * @access public
    * @return array
    * @param int id del tercero
    *************************************************/
    function BuscarTercero($TipoTercero,$TerceroId){            
      $query = "SELECT nombre_tercero,tipo_id_tercero FROM terceros WHERE tercero_id='$TerceroId' AND tipo_id_tercero='$TipoTercero'";
      if(!$resultado = $this->ConexionBaseDatos($query))
      return false;           
      $var[0]=$resultado->fields[0];
      $var[1]=$resultado->fields[1];
      $resultado->Close();
      return $var;
    }
    /*************************************************
    * Busca un cargo facturado del paquete
    * @access public
    * @return array
    * @param $Cuenta numero de la cuenta
    * @param $paqueteCodigoId numero del paquete de la cuenta
    *************************************************/
    function BuscarCargoFacturado($Cuenta,$paqueteCodigoId){
      $query="SELECT b.descripcion
      FROM cuentas_detalle a,tarifarios_detalle b
      WHERE a.numerodecuenta='".$Cuenta."'
      AND a.paquete_codigo_id='".$paqueteCodigoId."'
      AND a.sw_paquete_facturado='1'
      AND a.tarifario_id=b.tarifario_id
      AND a.cargo=b.cargo
      LIMIT 1 OFFSET 0;";
      if(!$resultado = $this->ConexionBaseDatos($query))
      return false;       
      $vars=$resultado->GetRowAssoc($toUpper=false);              
      $resultado->Close();
      return $vars;
    }
    /*************************************************
    * Busca la descripcion del tarifario
    * @access public
    * @return array
    * @param $tarifario_id descripcion del tarifario    
    *************************************************/
    function DescripcionCargosTarifario($tarifario_id){
       
      $query="SELECT a.descripcion as tarifario
      FROM tarifarios a
      WHERE a.tarifario_id='".$tarifario_id."'";
      if(!$resultado = $this->ConexionBaseDatos($query))
      return false; 
      if($resultado->RecordCount()>0){
        $vars=$resultado->GetRowAssoc($toUpper=false);
      }        
      $resultado->Close();
      return $vars;
    } 
    
    /**********************************************************************************
    * Funcion que permite realizar la conexion a la base de datos y ejecutar la 
    * consulta sql 
    *
    * @access public  
    * @param  string  $sql  sentencia sql a ejecutar 
    * @return rst 
    ************************************************************************************/
    function ConexionBaseDatos($sql)
    {
      list($dbconn)=GetDBConn();
      //$dbconn->debug=true;
      $rst = $dbconn->Execute($sql);
        
      if ($dbconn->ErrorNo() != 0)
      {
        $this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
        echo "<b class=\"label\">".$this->frmError['MensajeError']."</b>";
        return false;
      }
      return $rst;
    }    
    
   
	}
?>