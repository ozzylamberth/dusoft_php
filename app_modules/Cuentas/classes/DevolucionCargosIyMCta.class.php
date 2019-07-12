<?php
  /******************************************************************************
  * $Id: DevolucionCargosIyMCta.class.php,v 1.8 2011/07/25 20:37:18 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
  * $Revision: 1.8 $ 
  * 
  * @autor Lorena Aragon Galindo
  * Proposito del Archivo:  Manejo de la logica del proceso de paqetes de cargos en la cuenta 
  ********************************************************************************/
  class DevolucionCargosIyMCta
  {
    var $offset = 0;
    
    function DevolucionCargosIyMCta(){}
    
    
    
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
    
    /*****************************************************
    * Se encarga consultar los insumos y medicamentos de la cuenta
    * @access public
    * @return array
    * @param $NoLiquidacion numero de la liquidacion
    * @param $Cuenta numero de la cuenta
    ******************************************************/
    
    function IYMCuenta($Cuenta){
    
      GLOBAL $ADODB_FETCH_MODE;      
      $query="SELECT a.*,fc_descripcion_producto(b.codigo_producto) as descripcion,c.descripcion as nom_bodega,
             (CASE WHEN d.usuario_id IS NOT NULL THEN '1' ELSE '0' END) as permiso,
             b.sw_control_fecha_vencimiento
            FROM
            (SELECT a.codigo_producto,
                    a.fecha_vencimiento,
                    a.lote,					
                    a.empresa_id,
                    a.centro_utilidad,
                    a.bodega,
                    sum(a.cantidad) as cantidad,
                    a.departamento_al_cargar
             FROM
             ((
                SELECT b.codigo_producto,
					   b.fecha_vencimiento,
					   b.lote,
                      sum(a.cantidad) as cantidad,
                      c.empresa_id,
                      c.centro_utilidad,
                      c.bodega, 
                      a.departamento_al_cargar 
                FROM cuentas_detalle a, 
                    bodegas_documentos_d b,
                    bodegas_doc_numeraciones c
                WHERE a.numerodecuenta=$Cuenta
                AND a.tarifario_id='SYS'
                AND a.cargo='IMD'
                AND a.consecutivo=b.consecutivo
                AND b.bodegas_doc_id=c.bodegas_doc_id
                GROUP BY b.codigo_producto,
				b.fecha_vencimiento,
				b.lote,
                c.empresa_id,
                c.centro_utilidad,
                c.bodega, 
                a.departamento_al_cargar
              )              
              UNION
              (
                SELECT b.codigo_producto,
					   b.fecha_vencimiento,
					   b.lote,
                      (sum(a.cantidad)*-1) as cantidad,
                      c.empresa_id,
                      c.centro_utilidad,
                      c.bodega, 
                      a.departamento_al_cargar 
                FROM cuentas_detalle a, 
                    bodegas_documentos_d b,
                    bodegas_doc_numeraciones c
                WHERE a.numerodecuenta=$Cuenta
                AND a.tarifario_id='SYS'
                AND a.cargo='DIMD'
                AND a.consecutivo=b.consecutivo
                AND b.bodegas_doc_id=c.bodegas_doc_id
                GROUP BY b.codigo_producto,
				b.fecha_vencimiento,
				b.lote,
                c.empresa_id,
                c.centro_utilidad,
                c.bodega,
                a.departamento_al_cargar
              ))as a
              GROUP BY
              a.codigo_producto,
              a.fecha_vencimiento,
              a.lote,			  
              a.empresa_id,
              a.centro_utilidad,
              a.bodega,
              a.departamento_al_cargar) as a
              
              LEFT JOIN bodegas_usuarios_devoluciones_cuentas d ON
              (a.empresa_id=d.empresa_id 
              AND a.centro_utilidad=d.centro_utilidad 
              AND a.bodega=d.bodega 
              AND d.usuario_id='".UserGetUID()."'), 
              
              inventarios_productos b,
              bodegas c
              WHERE a.cantidad > 0
              AND a.codigo_producto=b.codigo_producto
              AND a.empresa_id=c.empresa_id
              AND a.centro_utilidad=c.centro_utilidad
              AND a.bodega=c.bodega
              ORDER BY permiso DESC";      
      $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
      if(!$resultado = $this->ConexionBaseDatos($query))
          return false;
      $ADODB_FETCH_MODE = ADODB_FETCH_NUM;     
      
      while($cargos=$resultado->FetchRow()){
        $vector[$cargos['empresa_id']][$cargos['centro_utilidad']]
        [$cargos['bodega']][$cargos['nom_bodega']][$cargos['lote']][$cargos['fecha_vecimiento']][$cargos['codigo_producto']]=$cargos;    
      }      
      return $vector;
    }
    
    /*****************************************************
    * Se encarga consultar los motivos de devolucion de insumos 
    * y medicamentos de la cuenta
    * @access public
    * @return array    
    ******************************************************/
    
    function MotivosDevolucionIyM(){
            
      $query = "SELECT motivo_devolucion_id,descripcion
      FROM bodegas_documentos_devolucion_motivos";
      if(!$resultado = $this->ConexionBaseDatos($query))
      return false;
      while(!$resultado->EOF){
        $vars[]=$resultado->GetRowAssoc($toUpper=false);
        $resultado->MoveNext();
      }        
      return $vars;
      
    }
    
    function ValidarInsercionDevolucion($vector){
      
      foreach($vector as $valor=>$cantidad){        
        
        (list($none,$empresa,$centro_utilidad,$bodega,$codigo_producto,$nom_bodega,$cantidadLimite)=explode('||//',$valor));                  
        if(array_key_exists($codigo_producto,$_SESSION['FECHAS_VENCIMIENTO']['REQUIERE'])){
          foreach($_SESSION['FECHAS_VENCIMIENTO']['DEVOLUCION_CUENTAS'][$empresa."||//".$centro_utilidad."||//".$bodega][$codigo_producto] as $loteT=>$arreglo){
            (list($cantidadLoteT,$fechaVencimientoT)=explode('||//',$arreglo));
            $sumacantidadLoteT+=$cantidadLoteT;
          }
          if($sumacantidadLoteT != $cantidad){  
            $this->mensajeDeError='Error en la suma de la cantidad de los lotes  del producto '.$codigo_producto.' no coincide con la cantidad total a devolver';        
            return 1;
          }
        }       
                
      }
      return 0;
        
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