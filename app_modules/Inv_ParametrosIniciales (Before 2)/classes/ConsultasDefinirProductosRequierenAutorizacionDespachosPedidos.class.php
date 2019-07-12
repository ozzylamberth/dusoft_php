<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ConsultasEstadosDocumentos.class.php,
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.4 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */ 
  
  
  
  class ConsultasDefinirProductosRequierenAutorizacionDespachosPedidos extends ConexionBD
  {
    /**
    * Contructor
    */
    
	function ConsultasDefinirProductosRequierenAutorizacionDespachosPedidos(){}
	
 
	
      function GuardarDaticos($datos)
	{
	  
    //$this->debug=true;
    $sql  = "UPDATE inventarios_productos ";
    $sql .= "SET porcentaje_venta = '".$datos['porcentaje_venta']."'";
	  $sql .= " Where ";
    $sql .= "costoventa_empresa_tipoproducto_id ='".$datos['codigo']."';";
	  
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
  
  

    function Lista_Productos_Creados($offset)
  {
      //$this->debug=true;
      $sql="
              Select 
                          grp.grupo_id || ' - ' ||grp.descripcion as Grupo,
                          cla.clase_id || ' - ' ||cla.descripcion as Clase,
                          sub.subclase_id || ' - ' || sub.descripcion as Subclase,
                          prod.codigo_producto,
                          prod.descripcion,
                          uni.descripcion || ' X ' || prod.contenido_unidad_venta as presentacion,
                          prod.porc_iva as iva,
                          prod.estado,
                          grp.sw_medicamento,
                          prod.sw_requiereautorizacion_despachospedidos
                    from
                          inv_grupos_inventarios grp,
                          inv_clases_inventarios cla,
                          inv_subclases_inventarios sub,
                          inventarios_productos prod,
                          unidades uni
                    where
                          grp.grupo_id = cla.grupo_id
                          and
                          cla.clase_id = sub.clase_id
                          and
                          sub.grupo_id = grp.grupo_id
                          and
                          sub.subclase_id = prod.subclase_id
                          and
                          sub.grupo_id = prod.grupo_id
                          and
                          sub.clase_id = prod.clase_id
                          and
                          prod.unidad_id = uni.unidad_id
                          and
                          prod.estado = '1' ";
  if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
        
    $sql .= " ORDER BY prod.grupo_id ";
      $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";  


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

  function Lista_Productos_CreadosBuscados($Grupo_Id,$Clase_Id,$SubClase_Id,$Descripcion,$CodAnatofarmacologico,$CodigoBarras,$offset)
  {
  
  $codigo_barras=eregi_replace("'","-",$CodigoBarras);
//$this->debug=true;  
  $sql="
            Select 
                          grp.grupo_id || ' ' ||grp.descripcion as Grupo,
                          cla.clase_id || ' ' || cla.descripcion as Clase,
                          sub.subclase_id || ' ' || sub.descripcion as Subclase,
                          prod.codigo_producto,
                          prod.descripcion,
                          uni.descripcion || ' X ' || prod.contenido_unidad_venta as presentacion,
                          prod.porc_iva as iva,
                          prod.estado,
                          grp.sw_medicamento,
                          prod.sw_requiereautorizacion_despachospedidos
                    from
                          inv_grupos_inventarios grp,
                          inv_clases_inventarios cla,
                          inv_subclases_inventarios sub,
                          inventarios_productos prod,
                          unidades uni
                          
                    where
                          prod.grupo_id ILike '%".$Grupo_Id."%'
                          and
                          prod.clase_id ILike '%".$Clase_Id."%'
                          and
                          prod.subclase_id ILike '%".$SubClase_Id."%'
                          and
                          prod.cod_anatofarmacologico ILike '%".$CodAnatofarmacologico."%'
                          and
                          prod.codigo_barras ILike '%".$codigo_barras."%'
                          and
                          prod.descripcion ILike '%".$Descripcion."%' 
                          and
                          prod.subclase_id = sub.subclase_id
                          and
                          sub.clase_id = prod.clase_id
                          and
                          sub.grupo_id = prod.grupo_id
                          and
                          sub.clase_id = cla.clase_id
                          and
                          cla.grupo_id = prod.grupo_id
                          and
                          cla.grupo_id = grp.grupo_id
                          and
                          prod.unidad_id = uni.unidad_id
                          and
                          prod.estado = '1' ";
            if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
        
    $sql .= "ORDER BY grp.grupo_id, prod.estado DESC ";
      $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";  

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
  
  
  
  
  
  function ListadoGrupos()
{
$sql="SELECT 
      grupo_id,
      descripcion,
      sw_medicamento
      from
      inv_grupos_inventarios
      order by grupo_id;";
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
  
  
  //Para el Buscador
  //Busqueda Por ClasesXGrupos
  function ListadoClasesxGrupo($CodigoGrupo)
{

          $sql="
          SELECT 
          cla.clase_id as laboratorio_id,
		  cla.descripcion
          FROM 
          inv_clases_inventarios cla
          where
          cla.grupo_id = '".$CodigoGrupo."'
           ";
          
     $sql .= "ORDER BY cla.clase_id;";
      
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
  
  
  
  function ListadoSubClasesConClase($CodigoGrupo,$CodigoClase)
{

          $sql="
            select
            sub.molecula_id,
            sub.descripcion as molecula
            from
            inv_subclases_inventarios sub
            where
            sub.grupo_id = '".$CodigoGrupo."'
            and
            sub.clase_id = '".$CodigoClase."'
             ";
            
      
    $sql .= "ORDER BY sub.molecula_id; ";
      
            
            
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