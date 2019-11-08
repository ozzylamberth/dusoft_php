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
    $sql .= "FROM	producto_bloqueadoxlote ";
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
  function ListarProductosXLote($codigo_producto,$nombre_producto,$concentracion,$clase_id,$subclase_id,$lote,$offset)
  {
    
    if($clase_id!="")
        $aumento = "AND    c.clase_id = '".$clase_id."' ";
      
    
      
    
   // $this->debug=true;
    $sql  = "SELECT	DISTINCT 
                    a.codigo_producto,
                    fc_descripcion_producto(c.codigo_producto) as descripcion,
                    a.lote,
                    a.estado ";
    $sql .= "FROM		existencias_bodegas_lote_fv as a, ";
    $sql .= "   		existencias_bodegas as b, ";
    $sql .= "   		inventarios_productos as c ";
    $sql .= "WHERE  a.codigo_producto=b.codigo_producto ";
    $sql .= "AND    a.bodega=b.bodega ";
    $sql .= "AND    a.codigo_producto=c.codigo_producto ";        
    $sql .= "AND    c.codigo_producto ILIKE '%".$codigo_producto."%' ";
    $sql .= "AND    c.descripcion ILIKE '%".$nombre_producto."%' ";
    $sql .= "AND    c.subclase_id ILIKE '%".$subclase_id."%' ";
    $sql .= "       ".$aumento;
    $sql .= "AND    c.contenido_unidad_venta ILIKE '%".$concentracion."%'    ";
    $sql .= "AND    a.lote ILIKE '%".$lote."%'    ";
    $sql .= "   GROUP BY a.codigo_producto,a.lote,fc_descripcion_producto(c.codigo_producto),c.contenido_unidad_venta,c.unidad_id,a.estado ";
     $sql .= "  ORDER BY fc_descripcion_producto(c.codigo_producto),a.estado ";
    
    
	             if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
        /*
        * 3) Paso Implementar paginador... Incluir paramento offset
        *  Ejecutar el conteo de Registros a encontrar con ProcesarSqlConteo.
        *  Organizar la Busqueda
        *  Aplicar $this->limit, significa numero de registros a mostrar y offset-> siguiente.
        */   

     

     $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
	
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
  
  
  
  function BuscarProductoLoteEmpresas($codigo_producto,$lote)
  {
    //$this->debug=true;
    $sql  = "SELECT	    fv.*,bod.descripcion as bodega_descripcion,cen.descripcion as centro_descripcion,emp.razon_social as empresa_descripcion, ";
    $sql .= "			inv.descripcion, ";
	$sql .= "			inv.contenido_unidad_venta, ";
	$sql .= "			uni.descripcion as unidad, ";
	$sql .= "			cla.descripcion as clase ";
	
	
	$sql .= "FROM		existencias_bodegas_lote_fv fv, ";
	$sql .= "			bodegas bod, ";
	$sql .= "			centros_utilidad cen, ";
	$sql .= "			empresas emp, ";
	$sql .= "			inventarios_productos as inv, ";
	$sql .= "			unidades as uni, ";
	$sql .= "			inv_clases_inventarios as cla, ";
	$sql .= "			inv_subclases_inventarios as sub ";

	
    $sql .= "WHERE  fv.codigo_producto= '".$codigo_producto."' ";
    $sql .= "AND    fv.lote = '".$lote."' ";
	$sql .= "AND    fv.bodega = bod.bodega ";
	$sql .= "AND    fv.centro_utilidad = bod.centro_utilidad ";
	$sql .= "AND    fv.empresa_id = bod.empresa_id ";
	$sql .= "AND    bod.centro_utilidad = cen.centro_utilidad ";
	$sql .= "AND    bod.empresa_id = cen.empresa_id ";
	$sql .= "AND    cen.empresa_id = emp.empresa_id ";
	$sql .= "AND    fv.codigo_producto = inv.codigo_producto ";
	$sql .= "AND    inv.unidad_id = uni.unidad_id ";
	
	$sql .= "AND    inv.grupo_id = sub.grupo_id ";
	$sql .= "AND    inv.subclase_id = sub.subclase_id ";
	$sql .= "AND    inv.clase_id = sub.clase_id ";
	
	$sql .= "AND    sub.grupo_id = cla.grupo_id ";
	$sql .= "AND    sub.clase_id = cla.clase_id ";
    
    
    
     
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
    $this->debug=true;
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
  function AgregarProductoBloq($bodega,$centro_utilidad,$codigo_producto,$empresa_id,$fecha_vencimiento,$lote,$usuario_id,$existencia_actual)
  {
    //$this->debug=true;
    $this->ConexionTransaccion();
      
      $sql = "INSERT INTO producto_bloqueadoxlote( ";
      $sql .= "            bodega, ";
      $sql .= "            centro_utilidad, ";
      $sql .= "            codigo_producto, ";
      $sql .= "            empresa_id, ";
      $sql .= "            fecha_vencimiento, ";
      $sql .= "            fecha_registro, ";
      $sql .= "            usuario_id, ";
      $sql .= "            existencia_bloqueada, ";
      $sql .= "            lote";
      $sql .= ")VALUES    (";
      $sql .= "           '".$bodega."', ";
      $sql .= "           '".$centro_utilidad."', ";
      $sql .= "           '".$codigo_producto."', ";
      $sql .= "           '".$empresa_id."', ";
      $sql .= "           '".$fecha_vencimiento."', ";
      $sql .= "           NOW(), ";
      $sql .= "           '".$usuario_id."', ";
      $sql .= "           ".$existencia_actual.", ";
      $sql .= "           '".$lote."' ";
      $sql .= "            ) ";
     
    
    if(!$rst = $this->ConexionTransaccion($sql))
    {
       echo $this->mensajeDeError;
       return false;
    }
     $this->Commit();
     return true;
  }
  
  
  function ModificarExistenciaBloquear($bodega,$centro_utilidad,$codigo_producto,$empresa_id,$fecha_vencimiento,$lote,$usuario_id,$estado)
  {
    //$this->debug=true;
    $this->ConexionTransaccion();
   
       $sql  = "UPDATE   existencias_bodegas_lote_fv ";
       $sql .= "SET      estado = '".$estado."' ";
       $sql .= "WHERE    
                          codigo_producto='".$codigo_producto."' 
                          AND lote='".$lote."' 
                          AND fecha_vencimiento = '".$fecha_vencimiento."' 
                          AND empresa_id='".$empresa_id."' 
                          AND bodega='".$bodega."' 
                          AND centro_utilidad='".$centro_utilidad."' ";
    
    
    if(!$rst = $this->ConexionTransaccion($sql))
    {
       echo $this->mensajeDeError;
       return false;
    }
     $this->Commit();
     return true;
  }
  
  function ListaLaboratorios()
  {
 // $codigo_barras=eregi_replace("'","-",$CodigoBarras);
      //$this->debug=true;
	  $sql="
            Select 
                    laboratorio_id,
                    descripcion
                    from
                          inv_laboratorios
                    where
                          estado = '1'
                          
                          ORDER BY descripcion ASC
                          ";
         
   //$this->debug=true;
  if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
   }
   
   function ListaMoleculas()
  {
 // $codigo_barras=eregi_replace("'","-",$CodigoBarras);
      //$this->debug=true;
	  $sql="
            Select 
                    molecula_id,
                    descripcion
                    from
                          inv_moleculas
                    where
                          estado = '1'
                          
                          ORDER BY descripcion ASC
                          ";
         
   //$this->debug=true;
  if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
   }
  
}
?>