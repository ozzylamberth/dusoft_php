<?php
  /******************************************************************************
  * $Id: ModificacionCargo.class.php,v 1.7 2010/11/29 14:05:53 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
  * $Revision: 1.7 $ 
  * 
  * @autor Lorena Aragon Galindo
  * Proposito del Archivo:  Manejo de la logica del proceso de paqetes de cargos en la cuenta 
  ********************************************************************************/
  class ModificacionCargo
  {
    var $offset = 0;
    
    function ModificacionCargo(){}
    
    
    /**********************************************************************
     * Busca el nombre y el precio del cargo en la tabla tarifarios_detalle.
     * @access public
     * @return string
     * @param text numero del tarifario
     * @param text id del Cargo
     **********************************************************************/
    function BuscarNombreCargo($TarifarioId,$Cargo){
        
      $query = "SELECT descripcion
                FROM tarifarios_detalle 
                WHERE tarifario_id='$TarifarioId' 
                AND cargo='$Cargo'";
      if(!$resultado = $this->ConexionBaseDatos($query))
      return false;        
      
      return $resultado->fields[0];
    }
    
    /***************************************************************************************
      * La funcion BuscarNombresPaciente se encarga de buscar en la base de datos los nombres de los pacientes.
      * @access public
      * @return array
      * @param string tipo de documento
      * @param int numero de documento
      **************************************************************************************/
      
     function BuscarNombresPaciente($tipo,$documento){
          
          $query = "SELECT primer_nombre,segundo_nombre FROM pacientes WHERE tipo_id_paciente='$tipo' AND paciente_id='$documento'";
          if(!$resultado = $this->ConexionBaseDatos($query))
          return false; 
          $Nombres=$resultado->fields[0]." ".$resultado->fields[1];
          $resultado->Close();
          return $Nombres;
     }
     
     /**************************************************************************
      * Se encarga de buscar en la base de datos los apellidos de los pacientes.
      * @access public
      * @return array
      * @param string tipo de documento
      * @param int numero de documento
      **************************************************************************/
      
      function BuscarApellidosPaciente($tipo,$documento){
          
          $query = "SELECT primer_apellido,segundo_apellido FROM pacientes WHERE tipo_id_paciente='$tipo' AND paciente_id='$documento'";
          if(!$resultado = $this->ConexionBaseDatos($query))
          return false;          
          $Apellidos=$resultado->fields[0]." ".$resultado->fields[1];
          $resultado->Close();
          return $Apellidos;
      }
      
      /******************************************************************
      * Busca el departamento y su descripcion en la tabla departamentos.
      * @access public
      * @return array
      *****************************************************************/
      function Departamentos($EmpresaId,$CentroU){      
          GLOBAL $ADODB_FETCH_MODE;   
          if($CentroU){
            $CU="and centro_utilidad='$CentroU'"; 
          }         
          $query = "SELECT a.departamento,a.descripcion
                      FROM departamentos as a, servicios as b WHERE a.empresa_id='$EmpresaId'
                      and a.servicio=b.servicio and b.sw_asistencial=1";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;            
          if(!$resultado = $this->ConexionBaseDatos($query))
          return false;          
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;        
          while($datos=$resultado->FetchRow()){
            $vars[$datos['departamento']]=$datos['descripcion'];
          }
          $resultado->Close();                
          return $vars;         
      }
      
      /**************************************************************
      * Busca en la base de datos si la cantidad se puede cambiar(0).
      * @access public
      * @return array
      * @param string tarifario_id
      * @param string grupo
      * @param string subgrupo
      *************************************************************/
      function SWCantidad($TarifarioId,$Cargo){
            
          $sql="SELECT sw_cantidad FROM tarifarios_detalle WHERE tarifario_id='$TarifarioId' AND cargo='$Cargo'";
          if(!$resultado = $this->ConexionBaseDatos($sql))
          return false;  
          return $resultado->fields[0];
      }
      
      /****************************************************
      * Modifica un cargo de la cuenta en cuenta_detalles.
      * @ access public
      * @ return boolean
      ****************************************************/
      function ModificarCargo($Cuenta,$PlanId,$EmpresaId,$Departamento,$Transaccion,$TarifarioId,$Cargo,$Consecutivo,$Cantidad,$observacion,$ValorPaciente,$ValorEmpresa,$FechaCargo,$Manual,$PorEmp,$PorPac){

          list($dbconn)=GetDBConn();
          $dbconn->BeginTrans();
             
          if(empty($observacion)){                          
            $this->mensajeDeError ="DEBE ESCRIBIR LA JUSTIFICACION.";            
            return false;
          }
          if(!$Cantidad || !$Cargo || !$FechaCargo){
              if(!$Cantidad){ $this->frmError["Cantidad"]=1; }
              if(!$Cargo){ $this->frmError["Cargo"]=1; }
              if(!$FechaCargo){ $this->frmError["FechaCargo"]=1; }                              
              $this->mensajeDeError='Faltan datos obligatorios.';
              return false;               
          }
          
          
/*          $f = (int) $Cantidad;
          $y = $Cantidad - $f;
          if($y != 0){
            if($y != 0){ 
            //$this->frmError["Cantidad"]=1; 
            }
            $this->mensajeDeError='La Cantidad debe ser entera.';              
            return false; 
          }            */
          $f=explode('/',$_REQUEST['FechaCargo']);
          $FechaCargo=$f[2].'-'.$f[1].'-'.$f[0]; 
                   
          IncludeLib("tarifario_cargos");
          $query ="SELECT b.servicio
                    FROM departamentos as b
                    WHERE b.departamento='$Departamento'";
          if(!$resultado = $this->ConexionBaseDatosTrans($dbconn,$query))
          return false;  
          $Servicio=$resultado->fields[0];
          //----------------------------esto es para los calculos-------------------------
          //Modificado por Lorena para la liquidacion de medicamentos          
          if($Cargo!='IMD' && $Cargo!='DIMD'){
            $Liq=LiquidarCargoCuenta($Cuenta,$TarifarioId,$Cargo,$Cantidad,$PorEmp,$PorPac,true,true,0,'','','','',true);
          }else{
            $query ="SELECT codigo_producto
            FROM bodegas_documentos_d
            WHERE consecutivo='".$Consecutivo."'";
            if(!$resultado = $this->ConexionBaseDatosTrans($dbconn,$query))
            return false;  
            $producto=$resultado->fields[0];
            $Liq=LiquidarIyM($Cuenta,$producto,$Cantidad,$descuento_manual_empresa=0 ,$descuento_manual_paciente=0 ,$aplicar_descuento_empresa=true ,$aplicar_descuento_paciente=true ,NULL,$PlanId,$autorizar=false,$Departamento,$EmpresaId);
            $Liq[valor_no_cubierto]=$Liq[valor_nocubierto];
            //$Liq=LiquidarCargoCuenta($Cuenta,$TarifarioId,$Cargo,$Cantidad,$PorEmp,$PorPac,true,true,0,'','','','',true);
          }
          //fin
          $DescuentoEmp=$Liq[valor_descuento_empresa];
          $DescuentoPac=$Liq[valor_descuento_paciente];
          $Moderadora=$Liq [cuota_moderadora];
          $Precio=$Liq[precio_plan];
          $ValorCargo=$Liq[valor_cargo];
          $ValorPac=$Liq[copago];
          $ValorPac=$Liq[valor_no_cubierto];
          $ValorCub=$Liq[valor_cubierto];
          $PorEmp=$Liq[porcentaje_descuento_empresa];
          $PorPac=$Liq[porcentaje_descuento_paciente];
          //-------------------------------------------------------------------------------
          $query =" SELECT * FROM cuentas_detalle WHERE transaccion=$Transaccion AND numerodecuenta=$Cuenta";
          if(!$resultado = $this->ConexionBaseDatosTrans($dbconn,$query))
          return false;
          $Dat=$resultado->GetRowAssoc($ToUpper = false);
          if(empty($Dat[autorizacion_int]))
          {  $int='NULL';  }
          else
          {  $int=$Dat[autorizacion_int];  }
          if(empty($Dat[autorizacion_ext]))
          {  $ext='NULL';  }
          else
          {  $ext=$Dat[autorizacion_ext];  }
          if(empty($Dat[codigo_agrupamiento_id]))
          {  $Dat[codigo_agrupamiento_id]='NULL';  }
          if(empty($Dat[consecutivo]))
          {  $Dat[consecutivo]='NULL';  }
          if(empty($Dat[cargo_cups])){$Dat[cargo_cups]='NULL';}else{$Dat[cargo_cups]="'".$Dat[cargo_cups]."'";}
          if(empty($Dat[paquete_codigo_id])){
            $Dat[paquete_codigo_id]='NULL';  
          }
          if(empty($Dat[sw_paquete_facturado])){
            $Dat[sw_paquete_facturado]='NULL';  
          }
          
          $query = "INSERT INTO audit_cuentas_detalle(
                                transaccion,               empresa_id,
                                centro_utilidad,           numerodecuenta,
                                departamento,              tarifario_id,
                                cargo,                     cantidad,
                                precio,                    valor_cargo,
                                valor_nocubierto,          valor_cubierto,
                                usuario_id,                facturado,
                                fecha_cargo,               fecha_registro,
                                usuario_id_act,            fecha_registro_act,
                                sw_actualizacion,          valor_descuento_empresa,
                                valor_descuento_paciente,  porcentaje_gravamen,
                                sw_liq_manual,             servicio_cargo,
                                autorizacion_int,          autorizacion_ext,
                                sw_cuota_paciente,         sw_cuota_moderadora,
                                codigo_agrupamiento_id,    consecutivo,
                                sw_cargue,                 cargo_cups,
                                justificacion,             paquete_codigo_id,
                                sw_paquete_facturado)
                    VALUES(     $Dat[transaccion],                 '$Dat[empresa_id]',
                                '$Dat[centro_utilidad]',           $Dat[numerodecuenta],
                                '$Dat[departamento]',              '$Dat[tarifario_id]',
                                '$Dat[cargo]',                     $Dat[cantidad],
                                $Dat[precio],                      $Dat[valor_cargo],
                                $Dat[valor_nocubierto],            $Dat[valor_cubierto],
                                $Dat[usuario_id],                  $Dat[facturado],
                                '$Dat[fecha_cargo]',               '$Dat[fecha_registro]',
                                '".UserGetUID()."',                '".date("Y-m-d H:i:s")."',
                                1,                                 $Dat[valor_descuento_empresa],
                                $Dat[valor_descuento_paciente],    $Dat[porcentaje_gravamen],
                                $Dat[sw_liq_manual],               $Dat[servicio_cargo],
                                $int,                              $ext,
                                $Dat[sw_cuota_paciente],           $Dat[sw_cuota_moderadora],
                                $Dat[codigo_agrupamiento_id],      $Dat[consecutivo],
                                '$Dat[sw_cargue]',                 $Dat[cargo_cups],
                                '".$observacion."',                $Dat[paquete_codigo_id],
                                $Dat[sw_paquete_facturado])";                              
              
              if(!$resultado = $this->ConexionBaseDatosTrans($dbconn,$query))
              return false;             
              if($Manual){
                $ValorPac=str_replace(".","",$ValorPaciente);
                $ValorCub=str_replace(".","",$ValorEmpresa);                   
                $Precio=(str_replace(".","",FormatoValor($ValorPac))/$Cantidad)+(str_replace(".","",FormatoValor($ValorCub))/$Cantidad);
                if($Cargo=='DIMD'){
                  if($Precio<0){$Precio*=-1;}                  
                }
                if($Cargo=='DIMD'){
                  if($ValorCub>0){$ValorCub*=-1;}
                  if($ValorPac>0){$ValorPac*=-1;}
                }
                //modificacion Lorena pues insertaba en la base de datos el cargo que trae la liquidacion y no el precio por la cantidad
                $ValorCargo=str_replace(".","",FormatoValor($ValorPac))+str_replace(".","",FormatoValor($ValorCub));
                //fin modificacion
                $swM=1;
              }else{
                $swM=0; 
              }
              if(empty($Liq[codigo_agrupamiento_id])){
                $Liq[codigo_agrupamiento_id]='NULL';  
              }
              if(!is_numeric($ValorPac)){
                $ValorPac='0';  
              }
              if(!is_numeric($ValorCub)){
                $ValorCub='0';  
              }
              //VALIDAR  $ValorPac + $ValorCub <> 0
              /*if($ValorPac == '0' AND $ValorCub == '0')
              {                          
                $this->mensajeDeError ="EL VALOR VALOR CUBIERTO Y NO CUBIERTO NO DEBEN SER CERO.";            
                return false;
              }*/
              //FIN VALIDAR  $ValorPac + $ValorCub  <> 0
              
              if(!$DescuentoEmp) $DescuentoEmp = 0;
              if(!$DescuentoPac) $DescuentoPac = 0;
              if(!$Liq[porcentaje_gravamen]) $Liq[porcentaje_gravamen] = 0;
              if(!$Liq[sw_cuota_moderadora]) $Liq[sw_cuota_moderadora] = 0;
              if(!$Liq[sw_cuota_paciente]) $Liq[sw_cuota_paciente] = 0;
              
              $query =" UPDATE cuentas_detalle 
                        SET
                            departamento='$Departamento',                      departamento_al_cargar='$Departamento',
                            tarifario_id='$TarifarioId',                       cargo='$Cargo',
                            cantidad=$Cantidad,                                precio=$Precio,
                            valor_cargo=$ValorCargo,                           valor_nocubierto=$ValorPac,
                            valor_cubierto=$ValorCub,                          fecha_cargo='$FechaCargo',
                            usuario_id='".UserGetUID()."',                     fecha_registro='".date("Y-m-d H:i:s")."',
                            sw_liq_manual=$swM,                                valor_descuento_empresa=$DescuentoEmp,
                            valor_descuento_paciente=$DescuentoPac,            servicio_cargo=$Servicio,
                            porcentaje_gravamen=".$Liq[porcentaje_gravamen].", sw_cuota_paciente=".$Liq[sw_cuota_paciente].",
                            sw_cuota_moderadora=".$Liq[sw_cuota_moderadora]."
                        WHERE transaccion=$Transaccion AND numerodecuenta=$Cuenta";
                        
              if(!$resultado = $this->ConexionBaseDatosTrans($dbconn,$query,$final_Transaccion=1))
              return false;                                      
              return true;
      }
      
    /*********************************************************
    * Metodo para recuperar el detalle del error
    *
    * @return string
    * @access public
    */
    function ErrMsg()
    {
        return $this->mensajeDeError;
    }//fin del metodo    
    
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
      $rst = $dbconn->Execute($sql);        
      if ($dbconn->ErrorNo() != 0)
      {
        $this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();        
        echo "<b class=\"label\">".$this->frmError['MensajeError']."</b>";        
        return false;
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
    
   
  }
?>
