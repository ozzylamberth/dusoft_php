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
  
  
  
  class ConsultasDefinirCostosDeVentaProductos extends ConexionBD
  {
    /**
    * Contructor
    */
    
	function ConsultasDefinirCostosDeVentaProductos(){}
	
  
    /**********************************************************************************
		* Insertar una mol�cula en la base de datos. Datos enviados desde formulario de Moleculas
		* 
		* @return token
		************************************************************************************/
		
	function InsertarEstadoDocumento($datos)
	{
	  $sql  = "INSERT INTO inv_estados_documentos (";
    $sql .= "       abreviatura     , ";
	  $sql .= "       descripcion     , ";
	  $sql .= "       estado   ) ";
      $sql .= "VALUES ( ";
      $sql .= "        '".$datos['abreviatura']."',";
	    $sql .= "        '".$datos['descripcion']."',";
	    $sql .= "        '".$datos['estado']."'";
      $sql .= "       ); ";			
		//$this->debug=true;
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
	
	

/**********************************************************************************
		* Insertar un Cambio de estado
		* 
		* @return token
		************************************************************************************/
		
	function InsertarCostoVentaTipoProducto($Empresa_Id,$TipoProducto)
	{
    $temp = $Empresa_Id."".$TipoProducto;
	  $sql  = "INSERT INTO inv_costoventa_empresas_tiposproductos (";
    $sql .= "       costoventa_empresa_tipoproducto_id     , ";
	  $sql .= "       empresa_id     , ";
    $sql .= "       tipo_producto_id     , ";
	  $sql .= "       porcentaje_venta) ";
      $sql .= "VALUES ( ";
      $sql .= "        '".$temp."',";
	  $sql .= "        '".$Empresa_Id."',";
    $sql .= "        '".$TipoProducto."',";
	  $sql .= "        '0'";
      $sql .= "       ); ";			
		
    //$this->debug=true;
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
    

		
  function ListarTiposParaAsignarCostos($Empresa_Id)
  {
  //$this->debug=true;
			$sql = "
               SELECT 
                    icvetp.costoventa_empresa_tipoproducto_id as codigo,
                    icvetp.porcentaje_venta,
                    tp.tipo_producto_id,
                    tp.descripcion
                    FROM 
                        inv_costoventa_empresas_tiposproductos icvetp,
                        inv_tipo_producto tp
                    where
                    '".$Empresa_Id."' = icvetp.empresa_id
                    and
                    icvetp.tipo_producto_id = tp.tipo_producto_id;
      
               ";
						
			
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;

			$datos = array(); //Definiendo que va a ser un arreglo.
			
			while(!$rst->EOF) //Recorriendo el Vector;
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
  
  
  
  
  
     
 function Listar_Empresas($offset)
		{
				//$this->debug=true;
        $sql = "
              SELECT	
              EM.razon_social AS Empresa,
              EM.direccion,
              EM.telefonos,
              d.departamento,
              m.municipio,
              EM.empresa_id
							FROM		
              empresas EM,
              tipo_dptos d,
              tipo_mpios m
							WHERE		
              EM.empresa_id = EM.empresa_id
              and
              EM.sw_tipo_empresa = '1'
              and
              m.tipo_mpio_id = EM.tipo_mpio_id
              and
              m.tipo_dpto_id = EM.tipo_dpto_id
              and
              m.tipo_pais_id = EM.tipo_pais_id
              and
              m.tipo_dpto_id = d.tipo_dpto_id
              and
              EM.sw_activa ='1'
              ";
		
    
    if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
       
    $sql .= " ORDER BY EM.empresa_id ";
      $sql .= " LIMIT ".$this->limit." OFFSET ".$this->offset." ";

    
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;

			$datos = array(); //Definiendo que va a ser un arreglo.
			
			while(!$rst->EOF) //Recorriendo el Vector;
			{
				$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
	
function Listar_CentrosUtilidad($EmpresaId)
		{
				//$this->debug=true;
        $sql = "
              SELECT	
              c.centro_utilidad,
              c.descripcion
							FROM		
              centros_utilidad c
							WHERE		
              c.empresa_id = '".$EmpresaId."'
              ";
	   
    $sql .= " ORDER BY c.centro_utilidad ";
    
    
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;

			$datos = array(); //Definiendo que va a ser un arreglo.
			
			while(!$rst->EOF) //Recorriendo el Vector;
			{
				$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
  
  
  
  function Listar_Bodegas($EmpresaId,$CentroUtilidad)
		{
				//$this->debug=true;
        $sql = "
              SELECT	
              b.bodega,
              b.descripcion
							FROM		
              bodegas b 
							WHERE		
              b.empresa_id = '".$EmpresaId."'
              and
              b.centro_utilidad = '".$CentroUtilidad."'
              ";
	   
    $sql .= " ORDER BY b.bodega ";
    
    
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;

			$datos = array(); //Definiendo que va a ser un arreglo.
			
			while(!$rst->EOF) //Recorriendo el Vector;
			{
				$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
  
  
  function Lista_Precios($EmpresaId,$CentroUtilidad,$Bodega)
		{
				//$this->debug=true;
        $sql = "
              SELECT	
              codigo_lista,
              descripcion
							FROM		
              listas_precios
							WHERE		
              empresa_id = '".$EmpresaId."'
              and
              centro_utilidad = '".$CentroUtilidad."'
              and
              bodega = '".$Bodega."'
              ";
	   
        
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
  
  
  function InsertarListaPrecios($Datos)
	{
    
	  $sql  = "INSERT INTO listas_precios(";
    $sql .= "       codigo_lista     , ";
    $sql .= "       descripcion     , ";
	  $sql .= "       empresa_id     , ";
    $sql .= "       centro_utilidad     , ";
    $sql .= "       bodega     ";
	  $sql .= "                         )";
    $sql .= "VALUES ( ";
    $sql .= "        '".$Datos['codigo_lista']."',";
	  $sql .= "        '".$Datos['descripcion']."',";
    $sql .= "        '".$Datos['empresa_id']."',";
	  $sql .= "        '".$Datos['centro_utilidad']."',";
	  $sql .= "        '".$Datos['bodega']."'";
    $sql .= "       ); ";			
		
    //$this->debug=true;
			if(!$rst = $this->ConexionBaseDatos($sql)) 
		   return false;
     		else
				return true;
				
			$rst->Close();
			
		}
  
  function InsertarItemListaPrecios($Datos)
	{
    
    if($Datos['porcentaje']=="")
        $Datos['porcentaje']=0;
    
	  $sql  = "INSERT INTO listas_precios_detalle(";
    $sql .= "       codigo_lista     , ";
    $sql .= "       codigo_producto     , ";
	  $sql .= "       empresa_id     , ";
    $sql .= "       porcentaje     , ";
    $sql .= "       precio,     ";
    $sql .= "       sw_porcentaje,     ";
    $sql .= "       valor_inicial     ";
	  $sql .= "                         )";
    $sql .= "VALUES ( ";
    $sql .= "        '".$Datos['codigo_lista']."',";
	  $sql .= "        '".$Datos['codigo_producto']."',";
    $sql .= "        '".$Datos['empresa_id']."',";
	  $sql .= "        ".$Datos['porcentaje'].",";
	  $sql .= "        ".$Datos['precio'].",";
	  $sql .= "        '".$Datos['sw_porcentaje']."',";
	  $sql .= "        ".$Datos['valor_inicial']."";
    $sql .= "       ); ";			
		
    //$this->debug=true;
			if(!$rst = $this->ConexionBaseDatos($sql)) 
		   return false;
     		else
				return true;
				
			$rst->Close();
			
		}
  
    function ModificarItemListaPrecios($Datos)
	{
    
    if($Datos['porcentaje']=="")
        $Datos['porcentaje']=0;
        
    if($Datos['sw_porcentaje']=="")
        $Datos['sw_porcentaje']=0;
    
	  $sql  = "UPDATE listas_precios_detalle SET";
    $sql .= "       porcentaje = ".$Datos['porcentaje'].", ";
    $sql .= "       precio = ".$Datos['precio'].",     ";
    $sql .= "       sw_porcentaje = ".$Datos['sw_porcentaje'].",     ";
    $sql .= "       valor_inicial = ".$Datos['valor_inicial']."     ";
	  $sql .= "WHERE  ";
    $sql .= "        codigo_lista = '".$Datos['codigo_lista']."' ";
    $sql .= "        and ";
	  $sql .= "        codigo_producto = '".$Datos['codigo_producto']."' ";
    $sql .= "        and ";
    $sql .= "        empresa_id ='".$Datos['empresa_id']."' ";
	  $sql .= "       ; ";			
		
    //$this->debug=true;
			if(!$rst = $this->ConexionBaseDatos($sql)) 
		   return false;
     		else
				return true;
				
			$rst->Close();
			
		}
  
    function ListarItemsListaPrecios($CodigoProducto,$Descripcion,$Concentracion,$Empresa_Id,$CodigoLista,$offset)
  {
      $sql="
          SELECT 
              fc_descripcion_producto(prod.codigo_producto) as descripcion,
              lpd.empresa_id,
              lpd.codigo_producto,
              lpd.codigo_lista,
              lpd.precio,
              lpd.sw_porcentaje,
              lpd.porcentaje,
              lpd.valor_inicial,
              lpd.sw_habilitado_entregar,
              lp.descripcion AS listas_nombre
          FROM
              listas_precios_detalle lpd,
              inventarios_productos prod,
              listas_precios lp
          WHERE
              lpd.empresa_id = '".$Empresa_Id."'
          AND
              lp.codigo_lista = lpd.codigo_lista
          AND
              lpd.codigo_producto = prod.codigo_producto
          AND
              prod.codigo_producto ILIKE '%".$CodigoProducto."%'
          AND
              prod.descripcion ILIKE '%".$Descripcion."%'
          AND
              prod.contenido_unidad_venta ILIKE '%".$Concentracion."%'
          AND
              prod.estado = '1'
          AND
              NOT lp.status = 0
      ";
                          
  if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
        
    $sql .= " ORDER BY listas_nombre, prod.grupo_id ";
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

  
    function EliminarItemListaPrecios($EmpresaId,$CodigoLista,$CodigoProducto)
	{
    
    $sql  = "DELETE FROM listas_precios_detalle ";
    $sql .= "WHERE  ";
    $sql .= "        empresa_id ='".$EmpresaId."' ";
	  $sql .= "         and ";
    $sql .= "        codigo_lista ='".$CodigoLista."' ";
    $sql .= "         and ";
    $sql .= "        codigo_producto = '".$CodigoProducto."' ";
	  
    $sql .= "      ; ";			
		
    //$this->debug=true;
			if(!$rst = $this->ConexionBaseDatos($sql)) 
		   return false;
     		else
				return true;
				
			$rst->Close();
			
		}
  
  
	function ListaProductosInventario($CodigoProducto,$Descripcion,$Concentracion,$Empresa_Id,$CentroUtilidad,$Bodega,$CodigoLista,$offset)
  {
 // $codigo_barras=eregi_replace("'","-",$CodigoBarras);
      //$this->debug=true;
	  $sql="
            Select 
                          grp.descripcion as Grupo,
                          sub.descripcion as Subclase,
                          prod.codigo_producto,
                          fc_descripcion_producto(prod.codigo_producto) as descripcion,
                          
                          prod.porc_iva as iva,
                          grp.sw_medicamento,
                          inv.precio_venta,
                          inv.costo,
						  ex.existencia
                    from
                          inv_grupos_inventarios grp,
                          inv_clases_inventarios cla,
                          inv_subclases_inventarios sub,
                          inventarios_productos prod,
                          unidades uni,
                          inventarios inv,
                          existencias_bodegas ex
                          
                    where
                          prod.descripcion ILike '%".$Descripcion."%' 
            						  and
            						  prod.codigo_producto ILike '%".$CodigoProducto."%' 
            						  and
            						  prod.contenido_unidad_venta ILike '%".$Concentracion."%' 
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
            						  prod.codigo_producto = inv.codigo_producto
            						  and
            						  inv.empresa_id = '".trim($Empresa_Id)."'
                          and
                          inv.codigo_producto = ex.codigo_producto
                          and
                          ex.empresa_id = '".trim($Empresa_Id)."'
                          and
                          ex.centro_utilidad = '".trim($CentroUtilidad)."'
                          and
                          ex.bodega = '".trim($Bodega)."'
            						  and
                          prod.codigo_producto NOT IN
                                                      (
                                                      select 
                                                      codigo_producto
                                                      from
                                                      listas_precios_detalle
                                                      where
                                                      codigo_lista = '".trim($CodigoLista)."'
                                                      and
                                                      empresa_id = '".trim($Empresa_Id)."'
                                                      )
                          and
            						  prod.estado = '1' ";
         if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
        
    $sql .= "ORDER BY ex.existencia DESC ";
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
  
  
	}
	
?>