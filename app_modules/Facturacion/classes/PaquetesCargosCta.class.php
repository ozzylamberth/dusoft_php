<?php
  /******************************************************************************
  * $Id: PaquetesCargosCta.class.php,v 1.1 2007/02/21 16:36:42 lorena Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.1 $ 
	* 
	* @autor Lorena Aragon Galindo
  * Proposito del Archivo:	Manejo de la logica del proceso de paqetes de cargos en la cuenta 
  ********************************************************************************/
	class PaquetesCargosCta
	{
		var $offset = 0;
		
		function PaquetesCargosCta(){}
		
    
		/**********************************************************************************
    * Busca los cargos de la cuenta que no hacen parte de un paquete
    *
    * @access public 
    * @params int $Cuenta numero de la cuenta donde se modificaran los paquetes de cargos
    * @return array
    ***********************************************************************************/
    
    function BuscarCargosCuentaPaquetes($Cuenta){                   
        $query = "SELECT a.*,d.codigo_producto,
                        (CASE WHEN d.consecutivo IS NOT NULL 
                        THEN e.descripcion 
                        ELSE b.descripcion 
                        END) as descripcion,                          
                        (CASE a.facturado WHEN 1 
                        THEN a.valor_cargo 
                        ELSE 0 
                        END) as fac,dpto.descripcion as departamento
                  FROM cuentas_detalle as a
                  LEFT JOIN cuentas_codigos_agrupamiento f ON (a.codigo_agrupamiento_id=f.codigo_agrupamiento_id)
                  LEFT JOIN bodegas_documentos_d d ON (a.consecutivo=d.consecutivo AND f.bodegas_doc_id=d.bodegas_doc_id AND f.numeracion=d.numeracion)
                  LEFT JOIN inventarios_productos e ON (e.codigo_producto=d.codigo_producto)
                  LEFT JOIN departamentos dpto ON (a.departamento=dpto.departamento)
                  , tarifarios_detalle as b
                  WHERE a.numerodecuenta=$Cuenta 
                  AND a.cargo=b.cargo 
                  AND a.tarifario_id=b.tarifario_id                   
                  AND a.paquete_codigo_id IS NULL
                  ORDER BY a.fecha_cargo,a.codigo_agrupamiento_id,a.transaccion";                  
        
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
    * Busca los paquetes creados para los cargos de la ceunta
    *
    * @access public 
    * @params int $Cuenta numero de la cuenta donde se modificaran los paquetes de cargos
    * @return array
    ***********************************************************************************/
      
    function BuscarPaquetesCuenta($Cuenta){
      GLOBAL $ADODB_FETCH_MODE;      
      $query = "SELECT a.*,d.codigo_producto,
                      (CASE WHEN d.consecutivo IS NOT NULL 
                      THEN e.descripcion 
                      ELSE b.descripcion 
                      END) as descripcion,                          
                      (CASE a.facturado WHEN 1 
                      THEN a.valor_cargo 
                      ELSE 0 
                      END) as fac,dpto.descripcion as departamento
                FROM cuentas_detalle as a
                LEFT JOIN cuentas_codigos_agrupamiento f ON (a.codigo_agrupamiento_id=f.codigo_agrupamiento_id)
                LEFT JOIN bodegas_documentos_d d ON (a.consecutivo=d.consecutivo AND f.bodegas_doc_id=d.bodegas_doc_id AND f.numeracion=d.numeracion)
                LEFT JOIN inventarios_productos e ON (e.codigo_producto=d.codigo_producto)
                LEFT JOIN departamentos dpto ON (a.departamento=dpto.departamento)
                ,tarifarios_detalle as b
                WHERE a.numerodecuenta=$Cuenta 
                AND a.cargo=b.cargo 
                AND a.tarifario_id=b.tarifario_id                   
                AND a.paquete_codigo_id IS NOT NULL
                ORDER BY a.paquete_codigo_id,a.sw_paquete_facturado DESC,a.fecha_cargo,a.codigo_agrupamiento_id,a.transaccion";                          
      
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        if(!$resultado = $this->ConexionBaseDatos($query))
        return false;
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;        
        while($datos=$resultado->FetchRow()){
          $vars[$datos['paquete_codigo_id']][$datos['transaccion']]=$datos;
        }
        $resultado->Close();                
        return $vars;          
    
    }
    
    /**********************************************************************************
    * Busca en la base de datos los nombres de los pacientes.
    *
    * @access public 
    * @param string tipo de documento
    * @param int numero de documento
    * @return array
    ***********************************************************************************/
      
    
     function BuscarNombresPaciente($tipo,$documento){
        
        $query = "SELECT primer_nombre,segundo_nombre 
                  FROM pacientes 
                  WHERE tipo_id_paciente='$tipo' 
                  AND paciente_id='$documento'";
        if(!$resultado = $this->ConexionBaseDatos($query))
        return false;         
        $Nombres=$resultado->fields[0]." ".$resultado->fields[1];
        $resultado->Close();
        return $Nombres;
     }
     
    /**********************************************************************************
    * Busca en la base de datos los apellidos de los pacientes.
    *
    * @access public 
    * @param string tipo de documento
    * @param int numero de documento
    * @return array
    ***********************************************************************************/
               
    function BuscarApellidosPaciente($tipo,$documento){
        
      $query = "SELECT primer_apellido,segundo_apellido 
                FROM pacientes 
                WHERE tipo_id_paciente='$tipo' 
                AND paciente_id='$documento'";
      if(!$resultado = $this->ConexionBaseDatos($query))
      return false;       
      $Apellidos=$resultado->fields[0]." ".$resultado->fields[1];
      $resultado->Close();
      return $Apellidos;
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