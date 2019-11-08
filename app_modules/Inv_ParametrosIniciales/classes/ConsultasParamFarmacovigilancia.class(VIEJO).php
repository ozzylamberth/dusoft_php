<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ConsultasParamFarmacovigilancia.class.php
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */
  /**
  * Clase : ConsultasParamFarmacovigilancia
  * 
  *  
  * @package IPSOFT-SIIS
  * @version $Revision: 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */
class ConsultasParamFarmacovigilancia extends ConexionBD
{
  /**
    * Contructor
    */  
	function ConsultasParamFarmacovigilancia(){}
  
  /**
        * Funcion donde consulta el tipo de documento de un paciente
       *
       * @return booleano
      */
  function BuscarTipo_documento()
  {
    //$this->debug=true;
    $sql  = "SELECT	tipo_id_paciente,descripcion ";
    $sql .= "FROM		tipos_id_pacientes ";
          
    if(!$rst = $this->ConexionBaseDatos($sql)) 
    return false;

    $datos = array(); //Definiendo que va a ser un arreglo.
    
    while(!$rst->EOF) //Recorriendo el Vector;
    {
      $datos[] = $rst->GetRowAssoc($ToUpper = false);
      $rst->MoveNext();
    }
    $rst->Close();
    //PRINT_R($datos);
    return $datos;
    
  }
  
  /**
       * Funcion donde busca los productos bloqueados por lote
       *
       * @param var  $codigo_producto la informacion del codigo de producto
       * @param var  $lote la informacion del lote
       * @return booleano
      */
  function Buscarproducto_BloqueadoXL($codigo_producto,$lote)
  {
    //$this->debug=true;
    $sql  = "SELECT	* ";
    $sql .= "FROM		producto_bloqueadoxlote ";
    $sql .= "WHERE	codigo_producto='".$codigo_producto."' ";
    $sql .= "AND	  lote='".$lote."' ";      
    
    if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        
      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
  }
  
  /**
        * Funcion donde se consulta todos los productos y el lote
       *
       * @return booleano
      */
  function ListarProductosXLote($offset)
  {
    //$this->debug=true;
    $sql  = "SELECT	DISTINCT a.codigo_producto,c.descripcion as descripcion,a.lote ";
    $sql .= "FROM		inv_bodegas_movimiento_d as a, ";
    $sql .= "   		existencias_bodegas as b, ";
    $sql .= "   		inventarios_productos as c ";
    $sql .= "WHERE  a.codigo_producto=b.codigo_producto ";
    $sql .= "AND    a.bodega=b.bodega ";
    $sql .= "AND    a.codigo_producto=c.codigo_producto ";        
    
    
     $cont="select COUNT(*) from (".$sql.") AS A";
     $this->ProcesarSqlConteo($cont,$offset);
     $sql .= "GROUP BY a.codigo_producto,a.lote,c.descripcion ";
     $sql .= "ORDER BY c.descripcion ";
     $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
     if(!$rst = $this->ConexionBaseDatos($sql))        return false;
     $datos = array();
     while (!$rst->EOF)
     {
       $datos[] = $rst->GetRowAssoc($ToUpper = false);
       $rst->MoveNext();
     }
     $rst->Close();
     //print_r($datos);
     return $datos;
  }
  
  /**
        * Funcion donde se consulta todos los productos
       *
       * @return booleano
      */
  function ListarProductos($offset)
  {
    //$this->debug=true;
    $sql  = "SELECT	DISTINCT IV.codigo_producto,IV.descripcion  ";
    $sql .= "FROM		inventarios_productos IV, ";
    $sql .= "       hc_formulacion_despachos_medicamentos HM ";
    $sql .= "WHERE  IV.codigo_producto = HM.codigo_medicamento ";
    
     $cont="select COUNT(*) from (".$sql.") AS A";
     $this->ProcesarSqlConteo($cont,$offset);
     $sql .= "ORDER BY descripcion ";
     $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
     if(!$rst = $this->ConexionBaseDatos($sql))        return false;
     $datos = array();
     while (!$rst->EOF)
     {
       $datos[] = $rst->GetRowAssoc($ToUpper = false);
       $rst->MoveNext();
     }
     $rst->Close();
     //print_r($datos);
     return $datos;
  }
  
  
  
   /**
       * Funcion donde se consultan los permisos de un usuario
       *
       * @param array $filtros vector con los datos del request donde se encuentran los
       *  parametos de busqueda
       *  @param string $pg_siguiente
       * @return array $datos vector que contiene la informacion de los usuarios
        */
   function Consultarpacientes($filtros,$pg_siguiente)
   {
     //$this->debug = true;
     $sql  = "SELECT  DISTINCT paciente_id, ";
     $sql .= "                 tipo_id_paciente,  ";
     $sql .= "                 primer_apellido,  ";
     $sql .= "                 segundo_apellido,  ";
     $sql .= "                 primer_nombre,  ";
     $sql .= "                 segundo_nombre  ";
     $sql .= "FROM             pacientes  ";
    
    if($filtros['tipo_documento']!= "-1" )
    {
      $sql.="  WHERE tipo_id_paciente= '". $filtros['tipo_documento']."'  ";
    }
    if($filtros['documento']!="")
    {
     $sql.=" and paciente_id= '".$filtros['documento']."' ";
    }
     $cont="select COUNT(*) from (".$sql.") AS A";
     $this->ProcesarSqlConteo($cont,$pg_siguiente);
     $sql .= "ORDER BY paciente_id ";
     $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
     if(!$rst = $this->ConexionBaseDatos($sql))        return false;
     $datos = array();
     while (!$rst->EOF)
     {
       $datos[] = $rst->GetRowAssoc($ToUpper = false);
       $rst->MoveNext();
     }
     $rst->Close();
     //print_r($datos);
     return $datos;
   }
   
   /**
       * Funcion donde se almacena la informacion de Farmacovigilancia
       *
       * @param  var $tipo_id_paciente contiene el tipo de documento paciente
       * @param  var $paciente_id contiene el numero del documento del paciente
       * @param var  $tipo_doc_general variable con la informacion del tipo de documento
       * @param  var $descripcion_efectos contiene la descripcion de los efectos del medicamento
       * @param  var $codigo_producto contiene el codigo del producto 
       * @return booleano
       */
  function AgregarFarmacovigilancia($paciente_id,$tipo_id_paciente,$descripcion_efectos,$codigo_producto)
  {
    //$this->debug=true;
    $this->ConexionTransaccion();
    
    $sql = "INSERT INTO param_farmacovigilancia( ";
    $sql .= "            id_param_farmacovigilancia, ";
    $sql .= "            paciente_id, ";
    $sql .= "            tipo_id_paciente, ";
    $sql .= "            descripcion_efectos, ";
    $sql .= "            codigo_producto, ";
    $sql .= "            usuario_registro, ";
    $sql .= "            fecha_registro";
    $sql .= ")VALUES    (";
    $sql .= "           default, ";
    $sql .= "           '".$paciente_id."', ";
    $sql .= "           '".$tipo_id_paciente."', ";
    $sql .= "           '".$descripcion_efectos."', ";
    $sql .= "           '".$codigo_producto."', "; 
    $sql .= "           ".UserGetUID().", ";
    $sql .= "           NOW() ) ";
    
    
    
    if(!$rst = $this->ConexionTransaccion($sql))
    {
       echo $this->mensajeDeError;
       return false;
    }
     $this->Commit();
     return true;
  }
  
   /**
       * Funcion donde se almacena la informacion de productos bloqueados
       *
       * @param  var $codigo_producto contiene el codigo del producto 
       * @param  var $lote contiene el lote del producto
       * @param  var $id_producto_bloqueadoxlote contiene el id del producto q se bloqueo
       * @param  var $sw_bloqueado contiene el sw si esta bloqueado
       * @return booleano
      */
  function AgregarProductoBloq($codigo_producto,$lote,$sw_bloqueado,$id_producto_bloqueadoxlote)
  {
    //$this->debug=true;
    $this->ConexionTransaccion();
    if($id_producto_bloqueadoxlote)
    {
       $sql  = "UPDATE   producto_bloqueadoxlote ";
       $sql .= "SET      sw_bloqueado = '".$sw_bloqueado."' ";
       $sql .= "WHERE    id_producto_bloqueadoxlote=".$id_producto_bloqueadoxlote." AND codigo_producto='".$codigo_producto."' AND lote='".$lote."' ";
    }
    else
    {
    
      $sql = "INSERT INTO producto_bloqueadoxlote( ";
      $sql .= "            id_producto_bloqueadoxlote, ";
      $sql .= "            codigo_producto, ";
      $sql .= "            lote, ";
      $sql .= "            sw_bloqueado, ";
      $sql .= "            usuario_registro, ";
      $sql .= "            fecha_registro";
      $sql .= ")VALUES    (";
      $sql .= "           default, ";
      $sql .= "           '".$codigo_producto."', ";
      $sql .= "           '".$lote."', ";
      $sql .= "           '".$sw_bloqueado."', ";
      $sql .= "           ".UserGetUID().", ";
      $sql .= "           NOW() ) ";
    }
    
    
    if(!$rst = $this->ConexionTransaccion($sql))
    {
       echo $this->mensajeDeError;
       return false;
    }
     $this->Commit();
     return true;
  }
}
?>