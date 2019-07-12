<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: MovBodSQL.class.php
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */
 
  /**
  * Clase : MovBodSQL
  * 
  *  
  * @package IPSOFT-SIIS
  * @version $Revision: 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */
class MovBodSQL extends ConexionBD
{
  /**
    * Contructor
    */  
	function MovBodSQL()
  {
  return true;
  }
  
  /**
        * Funcion donde consulta los productos en todas las bodegas
       *
       * @return booleano
      */
  function BuscarPBodegas($empresa_id,$centro,$codigo_producto,$descripcion,$offset)
  {
    //$this->debug=true;
    if(!empty($codigo_producto))
     {
       $filtro_codigo_producto = " AND b.codigo_producto = '".$codigo_producto."' ";
     }
     else
     {
       $filtro_codigo_producto = "";
     }
    
     if(!empty($descripcion))
     {
       $filtro_descripcion = " AND c.descripcion = '".$descripcion."' ";
     }
     else
     {
       $filtro_descripcion = "";
     }
    $sql  = " SELECT  b.*, ";
    $sql .= "         a.descripcion as bodega_desc, "; 
    $sql .= "         c.descripcion as nombrepro, ";
    $sql .= "         d.descripcion as unidades ";
    $sql .= "FROM     bodegas as a, ";
    $sql .= "         existencias_bodegas as b, "; 
    $sql .= "         inventarios_productos as c, ";
    $sql .= "         unidades as d ";
    $sql .= "WHERE    b.empresa_id='01' ";
    $sql .= "AND      b.centro_utilidad='01' ";
    $sql .= "AND      b.empresa_id=a.empresa_id ";
    $sql .= "AND      b.centro_utilidad=a.centro_utilidad "; 
    $sql .= "$filtro_codigo_producto ";
    $sql .= "$filtro_descripcion ";
    $sql .= "AND      a.bodega=b.bodega ";
    $sql .= "AND      c.codigo_producto=b.codigo_producto ";
    $sql .= "AND      c.unidad_id=d.unidad_id ";
      
     $cont="select COUNT(*) from (".$sql.") AS A";
     
     if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
       return false;
     
     $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
     
     if(!$rst = $this->ConexionBaseDatos($sql))        
     return false;
     
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
}
?>