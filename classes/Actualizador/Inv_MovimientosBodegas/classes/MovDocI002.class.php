<?php
  /******************************************************************************
  * $Id: MovDocI002.class.php,v 1.0 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.0 $ 
	* 
	* @autor Mauricio Adrian Medina
  ********************************************************************************/
	

class MovDocI002 extends ConexionBD
{
/***********************
* constructora
*************************/
function MovDocI002() {}

function ListadoProductosBuscados($CodigoProducto,$NombreProducto,$Offset)
		{
						
			
		$sql = 	"
									SELECT 	
													c.codigo_producto,
                          fc_descripcion_producto(c.codigo_producto) as descripcion,
													c.porc_iva,
													u.unidad_id
                          
									FROM	
                          inventarios_productos as c,
                          unidades as u
                  WHERE
                          c.codigo_producto=c.codigo_producto
                          and
                          c.descripcion ILIKE '%$NombreProducto%'
                          and
                          c.codigo_producto ILIKE '%$CodigoProducto%'
                          and
                          c.estado=1
                          and
                          c.unidad_id = u.unidad_id 
						  AND substring(c.codigo_producto from 1 for 2) <>'FO' "; // Se adiciona para q no muestre codigos del grupo FO

     
     if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A ",$Offset))
        return false;
           
      $sql .= " ORDER BY descripcion ";
      $sql .= " LIMIT ".$this->limit." OFFSET ".$this->offset." ;";
                  

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

    /*
    * Listado de Productos que estan en la orden de Compra
    */
    
    function ListadoProductosOCompra($OrdenCompra)
		{
						
			
		$sql = 	"
								SELECT 	cd.codigo_producto
								FROM	compras_ordenes_pedidos_detalle cd
								WHERE 
                cd.orden_pedido_id = ".$OrdenCompra."";
                                
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
    
     /*
    * Listado de Productos que estan en la orden de Compra
    */
    
    function ConsultaProveedorOC($OrdenCompra,$EmpresaId)
		{
						
			
		$sql = 	"
								SELECT 	*
								FROM	compras_ordenes_pedidos
								WHERE 
                orden_pedido_id = ".$OrdenCompra."
                and
                empresa_id = '".$EmpresaId."'";
                                
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
    
    /*
    * Listado de Productos que estan fuera de la orden de Compra
    */
    
     function ListadoProductos_FOC($empresa_id,$centro_utilidad,$bodega,$OrdenCompra,$doc_tmp_id)
		{
						
			
		$sql = 	"
								SELECT
				        prodfoc.item_id,
                prodfoc.codigo_producto,
                prodfoc.sw_autorizado,
				        prodfoc.doc_tmp_id
								FROM	
                compras_ordenes_pedidos_productosfoc prodfoc
								WHERE 
                prodfoc.empresa_id = '".$empresa_id."'
                and
                prodfoc.centro_utilidad = '".$centro_utilidad."'
                and
                prodfoc.bodega = '".$bodega."'
                and
                prodfoc.orden_pedido_id = ".$OrdenCompra."
                and
                doc_tmp_id = ".$doc_tmp_id."
                and
                prodfoc.sw_autorizado = '0';";
                                
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
    
    
     /*
    * Funcion de Guardar Productos fuera de la orden de Compra,
    * por Autorizar
    */
    function AgregarItemFOC($empresa_id,$centro_utilidad,$bodega,$usuario_id,$doc_tmp_id,$codigo_producto,$cantidad,$total_costo,$porc_iva,$bodegas_doc_id,$lote,$fecha_vencimiento,$localizacion,$item_id_compras,$justificacion,$OrdenCompra,$ValorUnitarioCompra,$ValorUnitarioFactura)
    {
         //$this->debug=true;
		if($item_id_compras=="")
			$item_id_compras=(-1);
			
        $sql  = " INSERT INTO compras_ordenes_pedidos_productosfoc (";
        $sql .= "       bodega     , ";
        $sql .= "       cantidad     , ";
        $sql .= "       centro_utilidad     , ";
        $sql .= "       codigo_producto     , ";
        $sql .= "       doc_tmp_id,     ";
        $sql .= "       empresa_id,     ";
        $sql .= "       fecha_ingreso,     ";
        $sql .= "       fecha_vencimiento,     ";
        $sql .= "       justificacion_ingreso,     ";
        $sql .= "       lote,     ";
        $sql .= "       orden_pedido_id,     ";
        $sql .= "       porcentaje_gravamen,     ";
        $sql .= "       total_costo,     ";
        $sql .= "       local_prod,     ";
        $sql .= "       usuario_id,     ";
        $sql .= "       item_id,     ";
        $sql .= "       valor_unitario_compra,     ";
        $sql .= "       valor_unitario_factura     ";
        $sql .= "         ";
        $sql .= ") ";
        $sql .= "VALUES ( ";
        $sql .= "        '".$bodega."',";
        $sql .= "        ".$cantidad.",";
        $sql .= "        '".$centro_utilidad."',";
        $sql .= "        '".$codigo_producto."',";
        $sql .= "        ".$doc_tmp_id.",";
        $sql .= "        '".$empresa_id."',";
        $sql .= "        NOW(),";
        $sql .= "        '".$fecha_vencimiento."',";
        $sql .= "        '".$justificacion."',";
        $sql .= "        '".$lote."',";
        $sql .= "        ".$OrdenCompra.",";
        $sql .= "        ".$porc_iva.",";
        $sql .= "        ".$total_costo.",";
        $sql .= "        '".$localizacion."',";
        $sql .= "        ".$usuario_id.",";
		$sql .= "        ".$item_id_compras.",";
		$sql .= "        ".$ValorUnitarioCompra.",";
		$sql .= "        ".$ValorUnitarioFactura." ";
		    $sql .= "       ); ";	
//print_r($sql);ValorUnitarioFactura
        
      
		if(!$result = $this->ConexionBaseDatos($sql))
				return false;
	    else
         return true;
			
			$result->Close();
       
    }
    /*
    *  Funcion para el switch de ingreso de productos a la tabla temporal
    */
  function ItemAgregadoCompras($codigo,$can,$lote,$fecha_vencimiento,$ItemId,$ValorUnitarioFactura)
    {
       
	$sql  = " UPDATE compras_ordenes_pedidos_detalle ";
  $sql .= " SET ";
  $sql .= " fecha_vencimiento_temp = '".$fecha_vencimiento."', ";
  $sql .= " lote_temp = '".$lote."', ";
  $sql .= " valor_unitario_factura = ".$ValorUnitarioFactura." ";
  $sql .= " Where ";
  $sql .= " item_id = ".$ItemId." ";
	//print_r($sql);
        
      
			if(!$result = $this->ConexionBaseDatos($sql))
				return false;
	    else
         return true;
			
			$result->Close();
       
    }
	
	function MarcarINC($ItemId,$doc_tmp_id,$bodegas_doc_id,$valor_sw)
    {
       
	//$this->debug=true;
  $sql  = " UPDATE inv_bodegas_movimiento_tmp_d ";
  $sql .= " SET ";
  $sql .= " sw_ingresonc = '".$valor_sw."' ";
  $sql .= " Where ";
  $sql .= " item_id = ".$ItemId." ";
  $sql .= " and ";
  $sql .= " doc_tmp_id ='".$doc_tmp_id."'";
	//print_r($sql);
        
      
			if(!$result = $this->ConexionBaseDatos($sql))
				return false;
	    else
         return true;
			
			$result->Close();
       
    }
       
	   function ConsultarCostoProducto($empresa_id,$centro_utilidad,$bodega,$OrdenCompra)
		{
						
			
		$sql = 	"
								SELECT
				prodfoc.item_id,
                prodfoc.codigo_producto,
                prodfoc.sw_autorizado,
				prodfoc.doc_tmp_id
								FROM	
                compras_ordenes_pedidos_productosfoc prodfoc
								WHERE 
                prodfoc.empresa_id = '".$empresa_id."'
                and
                prodfoc.centro_utilidad = '".$centro_utilidad."'
                and
                prodfoc.bodega = '".$bodega."'
                and
                prodfoc.orden_pedido_id = ".$OrdenCompra."
                and
                prodfoc.sw_autorizado = '0';";
                                
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
	   
	  function DocumentoTempIngresoCompras($doc_tmp_id)
		{
						
			
		$sql="  SELECT  a.*,
                        b.orden_pedido_id,
                        c.documento_id,
                        c.empresa_id,
                        c.centro_utilidad,
                        c.bodega
                FROM
                    inv_bodegas_movimiento_tmp as a
                    LEFT JOIN inv_bodegas_movimiento_tmp_ordenes_compra as b
                    ON (b.usuario_id = a.usuario_id AND b.doc_tmp_id = a.doc_tmp_id),
                    inv_bodegas_documentos as c
                WHERE
                    a.usuario_id = ".UserGetUID()."
					AND a.doc_tmp_id = ".$doc_tmp_id."
                    AND c.bodegas_doc_id = a.bodegas_doc_id
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
    
    function TerceroProveedor($CodigoProveedorId)
		{
		$sql="  
            SELECT 	          
                  pro.codigo_proveedor_id,
				          ter.tipo_id_tercero,
                  ter.tercero_id,
                  ter.nombre_tercero,
                  ter.dv
              FROM	
                  terceros ter,
                  terceros_proveedores pro
									WHERE 
                  pro.codigo_proveedor_id = ".$CodigoProveedorId."
                  and
                  pro.tipo_id_tercero = ter.tipo_id_tercero
                  and
                  pro.tercero_id = ter.tercero_id";
                                
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
     
	function InsertarRecepcionParcialCabecera($ComprasTemporal,$docs)
    {
      //$this->debug=true;
		//print_r($docs);			
        $sql  = " INSERT INTO inv_recepciones_parciales (";
        $sql .= "       empresa_id,     ";
		$sql .= "       bodega     , ";
		$sql .= "       centro_utilidad     , ";
        $sql .= "       orden_pedido_id,     ";
        $sql .= "       recepcion_parcial_id,     ";
        $sql .= "       usuario_id, ";
        $sql .= "       prefijo, ";
        $sql .= "       numero ";
        $sql .= ") ";
        $sql .= "VALUES ( ";
        $sql .= "        '".$ComprasTemporal[0]['empresa_id']."',";
        $sql .= "        '".$ComprasTemporal[0]['bodega']."',";
        $sql .= "        '".$ComprasTemporal[0]['centro_utilidad']."',";
        $sql .= "        ".$ComprasTemporal[0]['orden_pedido_id'].",";
        $sql .= "        DEFAULT, ";
		$sql .= "        ".UserGetUID().", ";
		$sql .= "        '".$docs['prefijo']."', ";
		$sql .= "        ".$docs['numero']." ";
        $sql .= "       )RETURNING(recepcion_parcial_id); ";	
//print_r($sql);ValorUnitarioFactura
        
      
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
	
	function InsertarRecepcionParcialDetalle($cantidad,$codigo_producto,$fecha_vencimiento,$lote,$porc_iva,$recepcion_parcial_id,$valor)
    {
         //$this->debug=true;
					
        $sql  = " INSERT INTO inv_recepciones_parciales_d (";
        $sql .= "       cantidad,     ";
		$sql .= "       codigo_producto     , ";
		$sql .= "       fecha_vencimiento     , ";
        $sql .= "       item_id,     ";
        $sql .= "       lote,     ";
        $sql .= "       porc_iva,     ";
        $sql .= "       recepcion_parcial_id,     ";
        $sql .= "       valor     ";
        $sql .= ") ";
        $sql .= "VALUES ( ";
        $sql .= "        ".$cantidad.",";
        $sql .= "        '".$codigo_producto."',";
        $sql .= "        '".$fecha_vencimiento."',";
        $sql .= "        DEFAULT,";
        $sql .= "        '".$lote."',";
        $sql .= "        ".$porc_iva.",";
        $sql .= "        ".$recepcion_parcial_id.",";
        $sql .= "        ".$valor."		";
        $sql .= "       ); ";	
//print_r($sql);ValorUnitarioFactura
     	if(!$result = $this->ConexionBaseDatos($sql))
				return false;
	    else
         return true;
			
			$result->Close();
       
    }
    
    
    /*
    *  MODIFICACIONES A LA INTERFAZ DE INGRESO POR ORDEN DE COMPRA
    */
    function ProductoOrdenCompra($orden,$CodigoProducto,$Descripcion,$CodigoBarras)
		{
			//print_r($Descripcion);
			if(!empty($CodigoBarras))
                       {
                       $filtro = " AND c.codigo_barras = '".$CodigoBarras."' ";
			   }

                    if(!empty($Descripcion))
                       {
                       $filtro =" AND c.descripcion ILIKE '%".$Descripcion."%' ";
			   }
                   		
			$sql = 	"
									SELECT 	
													a.orden_pedido_id,
													b.item_id,
									                            b.fecha_vencimiento_temp as fecha_vencimiento,
									                            b.lote_temp as lote,
													b.codigo_producto,
													a.codigo_proveedor_id,
													fc_descripcion_producto(c.codigo_producto) as descripcion,
									                           c.unidad_id as desunidad,
													c.contenido_unidad_venta,
													(b.numero_unidades - COALESCE(numero_unidades_recibidas,0) ) as cantidad,
													b.valor,
													b.porc_iva,
                                                                                                        c.codigo_cum
									FROM	compras_ordenes_pedidos as a
									JOIN compras_ordenes_pedidos_detalle as b
									ON
									(
										a.orden_pedido_id = b.orden_pedido_id
									)
									JOIN inventarios_productos as c
									ON
									(
									      b.codigo_producto=c.codigo_producto
                                                                    AND
                                                                    c.estado = '1'
										".$filtro."
							              )
									WHERE 
                  a.orden_pedido_id=".$orden."
                  AND
                  a.estado='1'
                  AND (b.numero_unidades - COALESCE(numero_unidades_recibidas,0)) != 0

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