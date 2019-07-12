<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ParametrizacionInicialSQL.class.php,v 1.1 2009/09/14 08:19:24 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author
  */
  /**
  * Clase : ParametrizacionTAOSQL
  * 
  *  
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author
  */
  
  class ParametrizacionTAOSQL extends ConexionBD
  {
    /**
    * Constructor de la clase
    */
    function ParametrizacionTAOSQL(){}
    
    /**
    * Funcion donde se verifica el permiso del usuario para el ingreso al modulo
    *
    * @return array $datos vector que contiene la informacion de la consulta del codigo de
    * la empresa y la razon social
    */
    function ObtenerPermisos()
    {
      //$this->debug = true;
      $sql  = "SELECT   EM.empresa_id AS empresa, ";
      $sql .= "         EM.razon_social AS razon_social ";
      $sql .= "FROM     userpermisos_parametrizacion CP, empresas EM ";
      $sql .= "WHERE    CP.usuario_id = ".UserGetUID()." ";
      $sql .= "         AND CP.empresa_id = EM.empresa_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      } 
      $rst->Close();
      return $datos;
    }
    
    /**
    * Funcion donde se consulta los medicamentos asignados a TAO
    *
    * @param array $empresa_id variable donde se encuentra el id de la empresa 
    * @param string $descripcion_medicamento variable donde se guarda la descripcion
    * @param string $codigo_medicamento variable donde se guarda el codigo del medicamento
    * @return array $datos retorna la consulta.
    */
    function ConsultarMedicamentos($empresa_id, $descripcion_medicamento = null, $codigo_medicamento = null, $pg_siguiente)
    { 
      //$this->debug = true;
      $sql  = "SELECT ip.codigo_producto AS codigo_producto, ip.descripcion AS descripcion, ";
      $sql .= "      ip.descripcion_abreviada AS  descripcion_abreviada, i.existencia AS existencia, mt.sw_estado as estado ";
      $sql .= "FROM ";
      $sql .= "   medicamentos m INNER JOIN inventarios_productos ip ";
      $sql .= "                                 INNER JOIN inventarios i ON (i.codigo_producto = ip.codigo_producto AND i.empresa_id='".$empresa_id."') ";
      $sql .= "                     ON ip.codigo_producto = m.codigo_medicamento ";
      $sql .= "                  LEFT JOIN medicamentos_tao mt ";
      $sql .= "                     ON ( mt.codigo_medicamento = m.codigo_medicamento AND mt.empresa_id='".$empresa_id."' AND mt.sw_estado = '1' ) ";
      
      if(!empty($descripcion_medicamento) || !empty($codigo_medicamento))
      {
        $sql .= " WHERE ";
      }
       
      if( !empty($descripcion_medicamento))
      {
        $sql .= " (ip.descripcion like '%". $descripcion_medicamento."%' or ";
        $sql .= " ip.descripcion_abreviada like '%". $descripcion_medicamento."%' ) ";
      }
      
      if( !empty($codigo_medicamento))
      {
        if( !empty($descripcion_medicamento))
          $sql .= " and ";
        $sql .= " m.codigo_medicamento like '%".$codigo_medicamento."%' ";
      }
      //echo $sql;
      //$sql .= "LIMIT 20 OFFSET 0 ";
      if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM (".$sql.") as data",$pg_siguiente))
        return false;
      
      $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] =  $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      
      return $datos;
    }
    
    /**
    * Funcion que asigna si un medicamento esta en tao o no.
    *
    * @param string $descripcion_medicamento variable donde se guarda la descripcion
    * @param string $codigo_medicamento variable donde se guarda el codigo del medicamento
    * @return boolean, true o false, el nuevo estado del medicamento, asignado a tao o no.
    */
    function AsignarMedicamentoTao($empresa_id,$codigo_medicamento)
    {
      $bool = true;
      $sql  = "SELECT sw_estado FROM medicamentos_tao ";
      $sql .= " WHERE empresa_id = '".$empresa_id."' AND  codigo_medicamento ='".$codigo_medicamento."' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $sw_estado; // Estado del medicamento.
      $existe = false; //Si el resgistro existe en la bd.
      $datos = array(); //array del resgistro.
      while(!$rst->EOF)
      {
        $datos =  $rst->GetRowAssoc($ToUpper = false);
        $sw_estado = $datos["sw_estado"];
        $rst->MoveNext();
        $existe= true;
      }
      $rst->Close();
      
      /* Actualizando si exsite el resgistro en medicamentos_tao */
      if($existe)
      {
        $sql  = "UPDATE medicamentos_tao ";
        if($sw_estado=='1')
        {
          $sql .= "SET sw_estado='0', fecha_registro=now() ";
          $bool = false;
        }
        else
          $sql .= "SET sw_estado='1', fecha_registro=now() ";
        $sql .= " WHERE empresa_id = '".$empresa_id."' AND  codigo_medicamento ='".$codigo_medicamento."' ";
        
      }else{ /* Si no existe se ingresa activado */
        $sql  = "INSERT INTO medicamentos_tao (";
        $sql .= "  codigo_medicamento, empresa_id, usuario_id, sw_estado ) ";
        $sql .= " VALUES ";
        $sql .= " ('".$codigo_medicamento."','".$empresa_id."',".UserGetUID().",'1') ";
      }
      $this->ConexionTransaccion();
      
      if(!$rst = $this->ConexionTransaccion($sql))
      {
         //return $this->mensajeDeError;
         return false;
      }
      
      $this->Commit();
      
      return $bool;
    }
  }
  
?>