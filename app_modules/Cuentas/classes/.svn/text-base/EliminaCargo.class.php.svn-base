<?php
  /******************************************************************************
  * $Id: EliminaCargo.class.php,v 1.7 2010/11/29 14:05:53 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
  * $Revision: 1.7 $ 
  * 
  * @autor Lorena Aragon Galindo
  * Proposito del Archivo:  Manejo de la logica del proceso para eliminar cargos en la cuenta 
  ********************************************************************************/
  class EliminaCargo
  {
    var $offset = 0;
    
    function EliminaCargo(){}
    
    /*************************************************************************
    * Elimina un cargo de la cuenta en cuenta_detalles.
    * @ access public
    * @ return boolean
    **************************************************************************/
    function EliminarCargo($Cuenta,$Transaccion,$observacion,$cambioTransaccion,$ordenes_servicio,$registrosCargos){
      
      list($dbconn)=GetDBConn();
      $dbconn->BeginTrans();
      if($registrosCargos==1){
        $query ="DELETE FROM os_maestro_cargos WHERE transaccion=".$Transaccion."";
        if(!$resultado = $this->ConexionBaseDatosTrans($dbconn,$query))
        return false; 
      }                               
      $query =" SELECT * FROM cuentas_detalle WHERE transaccion=$Transaccion AND numerodecuenta=$Cuenta";
      if(!$resultado = $this->ConexionBaseDatosTrans($dbconn,$query))
      return false;  
      $Dat=$resultado->GetRowAssoc($ToUpper = false);
      if(empty($Dat[autorizacion_int])){$int='NULL';}else{$int=$Dat[autorizacion_int];}
      if(empty($Dat[autorizacion_ext])){$ext='NULL';}else{$ext=$Dat[autorizacion_ext];}
      if(empty($Dat[codigo_agrupamiento_id])){$Dat[codigo_agrupamiento_id]='NULL';}
      if(empty($Dat[consecutivo])){$Dat[consecutivo]='NULL';}
      if(empty($Dat[paquete_codigo_id])){$Dat[paquete_codigo_id]='NULL';}
      if(empty($Dat[sw_paquete_facturado])){$Dat[sw_paquete_facturado]='NULL';}
      
      //sw_actualizacion 2 es eliminacion
      $query = "SELECT nextval('public.audit_cuentas_detalle_audit_cuenta_detalle_id_seq'::text)";
      if(!$resultado = $this->ConexionBaseDatosTrans($dbconn,$query))
      return false;        
      $consecutivoAudit=$resultado->fields[0];
      $query = "INSERT INTO audit_cuentas_detalle(
                audit_cuenta_detalle_id,        transaccion,
                empresa_id,                     centro_utilidad,
                numerodecuenta,                 departamento,
                tarifario_id,                   cargo,
                cantidad,                       precio,
                valor_cargo,                    valor_nocubierto,
                valor_cubierto,                 usuario_id,
                facturado,                      fecha_cargo,
                fecha_registro,                 usuario_id_act,
                fecha_registro_act,             sw_actualizacion,
                valor_descuento_empresa,        valor_descuento_paciente,
                porcentaje_gravamen,            sw_liq_manual,
                servicio_cargo,                 autorizacion_int,
                autorizacion_ext,               sw_cuota_paciente,
                sw_cuota_moderadora,            codigo_agrupamiento_id,
                consecutivo,                    sw_cargue,
                justificacion,                  paquete_codigo_id,
                sw_paquete_facturado)
                VALUES (
                $consecutivoAudit,              $Dat[transaccion],
                '$Dat[empresa_id]',             '$Dat[centro_utilidad]',
                $Dat[numerodecuenta],           '$Dat[departamento]',
                '$Dat[tarifario_id]',           '$Dat[cargo]',
                $Dat[cantidad],                 $Dat[precio],
                $Dat[valor_cargo],              $Dat[valor_nocubierto],
                $Dat[valor_cubierto],           $Dat[usuario_id],
                $Dat[facturado],                '$Dat[fecha_cargo]',
                '$Dat[fecha_registro]',         '".UserGetUID()."',
                '".date("Y-m-d H:i:s")."',      2,
                $Dat[valor_descuento_empresa],  $Dat[valor_descuento_paciente],
                $Dat[porcentaje_gravamen],      $Dat[sw_liq_manual],
                $Dat[servicio_cargo],           $int,
                $ext,                           $Dat[sw_cuota_paciente],
                $Dat[sw_cuota_moderadora],      $Dat[codigo_agrupamiento_id],
                $Dat[consecutivo],              '".$Dat[sw_cargue]."',
                '".$observacion."',             $Dat[paquete_codigo_id],
                $Dat[sw_paquete_facturado])";

      
      if(!$resultado = $this->ConexionBaseDatosTrans($dbconn,$query))
      return false;          
      if($cambioTransaccion==1){        
        for($i=0;$i<sizeof($ordenes_servicio);$i++){
          $query ="UPDATE os_maestro_cargos SET transaccion=NULL WHERE os_maestro_cargos_id=".$ordenes_servicio[$i]['os_maestro_cargos_id']."";  
          if(!$resultado = $this->ConexionBaseDatosTrans($dbconn,$query))
          return false;
          $query ="UPDATE os_maestro_cargos SET transaccion_auditotia=$consecutivoAudit WHERE os_maestro_cargos_id=".$ordenes_servicio[$i]['os_maestro_cargos_id']."";
          if(!$resultado = $this->ConexionBaseDatosTrans($dbconn,$query))
          return false;          
        }
      }
      $query ="DELETE FROM cuentas_detalle_profesionales WHERE transaccion=".$Transaccion."";
      if(!$resultado = $this->ConexionBaseDatosTrans($dbconn,$query))
      return false;
      $query ="DELETE FROM cuentas_detalle_honorarios WHERE transaccion=".$Transaccion."";
      if(!$resultado = $this->ConexionBaseDatosTrans($dbconn,$query))
      return false;
      
      $sql = "DELETE FROM soat_atencion_ambulatoria 
              WHERE transaccion=".$Transaccion." 
              AND numerodecuenta=".$Cuenta."";
      if(!$resultado = $this->ConexionBaseDatosTrans($dbconn,$sql,$final_Transaccion=1))
        return true;
      
      $query ="DELETE FROM cuentas_detalle WHERE transaccion=".$Transaccion." AND numerodecuenta=".$Cuenta."";
      if(!$resultado = $this->ConexionBaseDatosTrans($dbconn,$query,$final_Transaccion=1))
        return false;
      
      return true;
    }
    
    /*************************************************************************
    * Metodo que define si el cargo tiene asociado una orden de servicio y el estado de esta
    *
    * @ access public
    * @ return boolean
    **************************************************************************/
    
    function DefinirExistenciaOS($Transaccion,$Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Fecha,$Ingreso,$observacion,
                                 $Pieza,$doc,$numeracion,$qx,$codigo,$des,$noFacturado,$Consecutivo,$accion){    
      
      //Esta es la validacion que se realizo para el arranque de Cali
      //1. Verifica si el cargo tiene asociado una orden de servicio
      $this->cambioTransaccion=0;      
      $query =" SELECT a.os_maestro_cargos_id,b.sw_estado,b.numero_orden_id
                          FROM os_maestro_cargos a,os_maestro b
                          WHERE a.transaccion=".$Transaccion." AND
                          a.numero_orden_id=b.numero_orden_id";                          
                         
      if(!$result = $this->ConexionBaseDatos($query))
      return false;
      if($result->RecordCount()>0){
        while(!$result->EOF){
          $datos[]=$result->GetRowAssoc($ToUpper = false);
          $result->MoveNext();
        }
        $this->ordenes_servicio=$datos;
        //1. Verifica si el cargo tiene asociado un examen firmado
        if($datos[0]['sw_estado']=='4'){
          $this->cambioTransaccion=1;
          //1. Verifica que desea hacer con la solicitud
        }else{
          //verifica si tiene varios cargo equivalentes
          $query =" SELECT count(*)as total_registros
                      FROM os_maestro_cargos a
                      WHERE a.numero_orden_id=".$datos[0]['numero_orden_id']."";

          if(!$result = $this->ConexionBaseDatos($query))
          return false;
          $contador=$result->GetRowAssoc($ToUpper = false);            
          //Pregunta si tiene mas de una cargo
          $registrosCargos=0;
          if($contador['total_registros']>1){
              $registrosCargos=1;                
          }else{
            $this->VerificacionOrden=1;
          }          
        }
      }
      $this->registrosCargos=$registrosCargos;
      return true;    
    }
    
    
    
    /*********************************************************
    * Metodo para recuperar la variable cambioTransaccion
    *
    * @return string
    * @access public
    **********************************************************/
    function RecuperarcambioTransaccion()
    {
        return $this->cambioTransaccion;
    }//fin del metodo 
    
    /*********************************************************
    * Metodo para recuperar la variable cambioTransaccion
    *
    * @return string
    * @access public
    **********************************************************/
    function Recuperarordenes_servicio()
    {
        return $this->ordenes_servicio;
    }//fin del metodo 
    
     /*********************************************************
    * Metodo para recuperar la variable cambioTransaccion
    *
    * @return string
    * @access public
    **********************************************************/
    function RecuperarregistrosCargos()
    {
        return $this->registrosCargos;
    }//fin del metodo  
    
     /*********************************************************
    * Metodo para recuperar la variable cambioTransaccion
    *
    * @return string
    * @access public
    **********************************************************/
    function RecuperarVerificacionOrden()
    {
        return $this->VerificacionOrden;
    }//fin del metodo  
    
    
    /********************************************************************************************
    * Actualiza la orden de servicio cuando se realiza la eliminasion de un cargo desde la cuenta.
    *
    * @ access public
    * @ return boolean
    *********************************************************************************************/
    function EliminarOSCumplida($Transaccion,$Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Fecha,$Ingreso,$observacion,
                         $Pieza,$doc,$numeracion,$qx,$codigo,$des,$noFacturado,$Consecutivo){
          
      $FechaAct=date("Y-m-d H:i:s");
      $SystemId=UserGetUID();      
      $query =" SELECT a.os_maestro_cargos_id,b.sw_estado,b.numero_orden_id,c.hc_os_solicitud_id,c.evolucion_id
                          FROM os_maestro_cargos a,os_maestro b,hc_os_solicitudes c
                          WHERE a.transaccion=".$Transaccion." AND
                          a.numero_orden_id=b.numero_orden_id AND
                          b.hc_os_solicitud_id=c.hc_os_solicitud_id";
  
      if(!$result = $this->ConexionBaseDatos($query))
      return false;
      if($result->RecordCount()>0){
          while(!$result->EOF){
              $datos[]=$result->GetRowAssoc($ToUpper = false);
              $result->MoveNext();
          }
      }
      list($dbconn) = GetDBconn();      
      $dbconn->BeginTrans();
      $query ="UPDATE os_cumplimientos_detalle SET sw_estado='3' WHERE numero_orden_id=".$datos[0]['numero_orden_id']."";
      if(!$result = $this->ConexionBaseDatosTrans($dbconn,$query))
      return false;  
      if($_REQUEST['Activar']){
        $query ="UPDATE os_maestro SET sw_estado='1' WHERE numero_orden_id=".$datos[0]['numero_orden_id']."";
        if(!$result = $this->ConexionBaseDatosTrans($dbconn,$query))
        return false;  
      }elseif($_REQUEST['Anular']){
        $query ="UPDATE os_maestro SET sw_estado='9' WHERE numero_orden_id=".$datos[0]['numero_orden_id']."";  
        if(!$result = $this->ConexionBaseDatosTrans($dbconn,$query))
        return false;  
        if(!empty($datos[0]['evolucion_id'])){
          $query ="UPDATE hc_os_solicitudes SET sw_estado='1' WHERE hc_os_solicitud_id=".$datos[0]['hc_os_solicitud_id']."";
          if(!$result = $this->ConexionBaseDatosTrans($dbconn,$query))
          return false;  
          $query ="DELETE FROM hc_os_autorizaciones WHERE hc_os_solicitud_id=".$datos[0]['hc_os_solicitud_id']."";
          if(!$result = $this->ConexionBaseDatosTrans($dbconn,$query))
          return false;  
        }else{
          $query ="UPDATE hc_os_solicitudes SET sw_estado='2' WHERE hc_os_solicitud_id=".$datos[0]['hc_os_solicitud_id']."";
          if(!$result = $this->ConexionBaseDatosTrans($dbconn,$query))
          return false;                    
        }
      }  
      $query ="UPDATE os_maestro_cargos SET transaccion=NULL WHERE transaccion=".$Transaccion."";
      if(!$result = $this->ConexionBaseDatosTrans($dbconn,$query))
      return false;                    
      $query =" SELECT * FROM cuentas_detalle WHERE transaccion=".$Transaccion." AND numerodecuenta=".$Cuenta."";
      if(!$result = $this->ConexionBaseDatosTrans($dbconn,$query))
      return false;                    
      $Dat=$result->GetRowAssoc($ToUpper = false);
      if(empty($Dat[autorizacion_int])){$int='NULL';}else{$int=$Dat[autorizacion_int];}
      if(empty($Dat[autorizacion_ext])){$ext='NULL';}else{$ext=$Dat[autorizacion_ext];}
      if(empty($Dat[codigo_agrupamiento_id])){$Dat[codigo_agrupamiento_id]='NULL';}
      if(empty($Dat[consecutivo])){$Dat[consecutivo]='NULL';}
      if(empty($Dat[paquete_codigo_id])){
        $Dat[paquete_codigo_id]='NULL';  
      }
      if(empty($Dat[sw_paquete_facturado])){
        $Dat[sw_paquete_facturado]='NULL';  
      }
      //sw_actualizacion 2 es eliminacion
      $query = "SELECT nextval('public.audit_cuentas_detalle_audit_cuenta_detalle_id_seq'::text)";
      if(!$result = $this->ConexionBaseDatosTrans($dbconn,$query))
      return false;
      $consecutivoAudit=$result->fields[0];
      $query = "INSERT INTO audit_cuentas_detalle(
                                                  audit_cuenta_detalle_id,
                                                  transaccion,
                                                  empresa_id,
                                                  centro_utilidad,
                                                  numerodecuenta,
                                                  departamento,
                                                  tarifario_id,
                                                  cargo,
                                                  cantidad,
                                                  precio,
                                                  valor_cargo,
                                                  valor_nocubierto,
                                                  valor_cubierto,
                                                  usuario_id,
                                                  facturado,
                                                  fecha_cargo,
                                                  fecha_registro,
                                                  usuario_id_act,
                                                  fecha_registro_act,
                                                  sw_actualizacion,
                                                  valor_descuento_empresa,
                                                  valor_descuento_paciente,
                                                  porcentaje_gravamen,
                                                  sw_liq_manual,
                                                  servicio_cargo,
                                                  autorizacion_int,
                                                  autorizacion_ext,
                                                  sw_cuota_paciente,
                                                  sw_cuota_moderadora,
                                                  codigo_agrupamiento_id,
                                                  consecutivo,
                                                  sw_cargue,
                                                  justificacion,
                                                  paquete_codigo_id,
                                                  sw_paquete_facturado)
                                          VALUES ($consecutivoAudit,$Dat[transaccion],'$Dat[empresa_id]','$Dat[centro_utilidad]',$Dat[numerodecuenta],'$Dat[departamento]','$Dat[tarifario_id]','$Dat[cargo]',$Dat[cantidad],$Dat[precio],$Dat[valor_cargo],$Dat[valor_nocubierto],$Dat[valor_cubierto],$Dat[usuario_id],$Dat[facturado],'$Dat[fecha_cargo]','$Dat[fecha_registro]',$SystemId,'$FechaAct',2,$Dat[valor_descuento_empresa],$Dat[valor_descuento_paciente],$Dat[porcentaje_gravamen],$Dat[sw_liq_manual],$Dat[servicio_cargo],$int,$ext,$Dat[sw_cuota_paciente],$Dat[sw_cuota_moderadora],$Dat[codigo_agrupamiento_id],$Dat[consecutivo],'".$Dat[sw_cargue]."','".$_REQUEST['observacion']."',$Dat[paquete_codigo_id],$Dat[sw_paquete_facturado])";

      if(!$result = $this->ConexionBaseDatosTrans($dbconn,$query))
      return false;
      $query ="DELETE FROM cuentas_detalle_profesionales WHERE transaccion=".$Transaccion."";
      if(!$result = $this->ConexionBaseDatosTrans($dbconn,$query))
      return false;
      $query ="DELETE FROM cuentas_detalle_honorarios WHERE transaccion=".$Transaccion."";
      if(!$result = $this->ConexionBaseDatosTrans($dbconn,$query))
      return false;
      
      $sql = "DELETE FROM soat_atencion_ambulatoria 
              WHERE transaccion=".$Transaccion." 
              AND numerodecuenta=".$Cuenta."";
      if(!$resultado = $this->ConexionBaseDatosTrans($dbconn,$sql,$final_Transaccion=1))
        return true;
      
      $query ="DELETE FROM cuentas_detalle WHERE transaccion=".$Transaccion." AND numerodecuenta=".$Cuenta."";
      if(!$result = $this->ConexionBaseDatosTrans($dbconn,$query,$final_Transaccion=1))
      return false;
      return true;      
    }
     
   
    /**********************************************************************************
    * Funcion que permite realizar la conexion a la base de datos y ejecutar la 
    * consulta sql 
    *
    * @access public  
    * @param  string  $sql  sentencia sql a ejecutar 
    * @return rst 
    ************************************************************************************/
    function ConexionBaseDatosTrans($dbconn,$sql,$final_Transaccion){  
        
      $rst = $dbconn->Execute($sql);        
      if ($dbconn->ErrorNo() != 0){
        $this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
        echo "<b class=\"label\">".$this->frmError['MensajeError']."</b>"; 
        $dbconn->RollbackTrans();       
        return false;
      }
      if($final_Transaccion==1){
        $dbconn->CommitTrans();
      }
      return $rst;
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