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
	

class MovDocE002 extends ConexionBD
{
/***********************
* constructora
*************************/
function MovDocE002() {}

function BuscarFacturaProveedor($empresa_id,$centro_utilidad,$bodega,$CodigoProveedorId,$NumeroFactura)
    {
       $sql="
				SELECT 	
									*
							
									
									FROM	
									inv_facturas_proveedores fp
									                          
						WHERE
				  
              fp.codigo_proveedor_id = ".$CodigoProveedorId."
						  AND
						  fp.numero_factura = '".$NumeroFactura."'
						  AND
						  fp.empresa_id ='".$empresa_id."'
						  AND
						  fp.centro_utilidad ='".$centro_utilidad."'
						  AND
						  fp.bodega ='".$bodega."'
						  
						  ";
              
            // $this->debug=true;
               if(!$resultado = $this->ConexionBaseDatos($sql))
                  return $this->frmError['MensajeError'];
                    
                  $cuentas=Array();
                  while(!$resultado->EOF)
                  {
                    $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
                    $resultado->MoveNext();
                  }
                  
                  $resultado->Close();
                  
                  return $cuentas;
    }


	function RegistrarFacturaProveedor($doc_tmp_id,$usuario_id,$NumeroFactura)
	{
	  
    //$this->debug=true;
    $sql  = " UPDATE inv_bodegas_movimiento_tmp ";
    $sql .= " SET 
	          factura_proveedor = '".$NumeroFactura."' ";
    $sql .= " Where ";
    $sql .= " doc_tmp_id = ".$doc_tmp_id."";
	$sql .= " AND ";
	$sql .= " usuario_id =".$usuario_id."";
	
	  
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
		
		function RegistrarFacturaDespacho($doc_tmp_id,$usuario_id,$Prefijo,$NumeroFactura)
	{
	  
   //$this->debug=true;
    $sql  = " UPDATE inv_bodegas_movimiento_tmp ";
    $sql .= " SET 
	          numero_factura = ".$NumeroFactura.", ";
	$sql .= " prefijo_factura = '".$Prefijo."' ";
    $sql .= " Where ";
    $sql .= " doc_tmp_id = ".$doc_tmp_id."";
	$sql .= " AND ";
	$sql .= " usuario_id =".$usuario_id."";
	
	  
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
	

function ListadoProductosFactura($empresa_id,$centro_utilidad,$bodega,$CodigoProveedorId,$NumeroFactura,$Codigo,$Descripcion,$doc_tmp_id,$offset)
		{
			//$this->debug=true;
		$sql = 	"
									SELECT 	
  								copd.codigo_producto,
									copd.fecha_vencimiento,
									copd.lote,
									copd.cantidad_devuelta,
									copd.cantidad as numero_unidades_recibidas,
									--prod.descripcion,
                  fc_descripcion_producto(prod.codigo_producto) as descripcion,
									uni.descripcion as unidad,
									copd.valor as costo
									
									FROM	
									inv_facturas_proveedores fp,
									inv_facturas_proveedores_d copd,
									inventarios_productos prod,
									unidades uni
									
                          
						WHERE
				  
              fp.codigo_proveedor_id = ".$CodigoProveedorId."
						  AND
						  fp.numero_factura = '".$NumeroFactura."'
						  AND
						  fp.empresa_id ='".$empresa_id."'
						  AND
						  fp.centro_utilidad ='".$centro_utilidad."'
						  AND
						  fp.bodega ='".$bodega."'
						  AND
						  fp.numero_factura = copd.numero_factura
						  AND
						  copd.codigo_producto = prod.codigo_producto
						  and
						  prod.descripcion ILIKE '%$Descripcion%'
						  and
						  prod.codigo_producto ILIKE '%$Codigo%'
						  and
						  prod.unidad_id = uni.unidad_id
              and
              prod.estado = '1'
             
						 -- and
                                     -- copd.codigo_producto
						  ";

       if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
        
    $sql .= "ORDER BY prod.descripcion ASC,copd.fecha_vencimiento ASC ";
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

    
	
	function CantidadesDevueltasProductosOC($NumeroFactura,$codigo_producto,$lote,$cantidad)
	{
	  
    //$this->debug=true;
    $sql  = " UPDATE inv_facturas_proveedores_d ";
    $sql .= " SET 
	          cantidad_devuelta = cantidad_devuelta + ".$cantidad." ";
    $sql .= " Where ";
    $sql .= " numero_factura = ".$NumeroFactura."";
	$sql .= " AND ";
	$sql .= " codigo_producto = '".$codigo_producto."'";
	$sql .= " AND ";
	$sql .= " lote = '".$lote."'";
	
	  
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
	
	/*
	* Va Restando de las cantidades pendientes por devolver
	*/
	function CantidadesDevueltasFacturasDespacho($prefijo_factura,$numero_factura,$codigo_producto,$lote,$cantidad,$lote_devuelto)
	{
	  
    //$this->debug=true;
    $sql  = " UPDATE inv_facturas_despacho_d ";
    $sql .= " SET 
	          cantidad_devuelta = cantidad_devuelta - ".$cantidad." ";
    $sql .= " Where ";
    $sql .= " prefijo = '".$prefijo_factura."'";
	$sql .= " AND ";
	$sql .= " inv_facturas_despacho = ".$numero_factura."";
	$sql .= " AND ";
	$sql .= " codigo_producto = '".$codigo_producto."'";
	$sql .= " AND ";
	$sql .= " lote = '".$lote_devuelto."'";
	
	  
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
	
	function RegistroLoteDevuelto($doc_tmp_id,$codigo_producto,$lotec,$lote_devuelto)
	{
	  
    //$this->debug=true;
    $sql  = " UPDATE inv_bodegas_movimiento_tmp_d ";
    $sql .= " SET 
	          lote_devuelto = '".$lote_devuelto."' ";
    $sql .= " Where ";
    $sql .= " doc_tmp_id = ".$doc_tmp_id."";
	$sql .= " AND ";
	$sql .= " codigo_producto = '".$codigo_producto."'";
	$sql .= " AND ";
	$sql .= " lote = '".$lotec."'";
	
	  
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
	
	
	/*
    * Listado de Productos que estan en la orden de Compra
    */
    
    function ExistenciaLote($empresa_id,$centro_utilidad,$bodega,$codigo_producto,$lote)
		{
						
			
		$sql = 	"
								SELECT 	
								existencia_actual
								FROM	
								existencias_bodegas_lote_fv
								WHERE 
								empresa_id = '".$empresa_id."'
								AND
								centro_utilidad = '".$centro_utilidad."'
								AND
								bodega = '".$bodega."'
								AND
								codigo_producto = '".$codigo_producto."'
								AND
								lote = '".$lote."'
								AND
								existencia_actual > 0
                ;";
                                
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
    
	function ListarPrefijosFacturasTercero($tercero_id,$tipo_id_tercero,$empresa_id)
    {
       $sql="SELECT 
               prefijo
             FROM
                  inv_facturas_despacho
              WHERE
                tercero_id = '".$tercero_id."'
                and
                tipo_id_tercero ='".$tipo_id_tercero."'
                and
                empresa_id ='".$empresa_id."'
                
                group by prefijo;
              ";
              
            // $this->debug=true;
               if(!$resultado = $this->ConexionBaseDatos($sql))
                  return $this->frmError['MensajeError'];
                    
                  $cuentas=Array();
                  while(!$resultado->EOF)
                  {
                    $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
                    $resultado->MoveNext();
                  }
                  
                  $resultado->Close();
                  
                  return $cuentas;
    }



function BuscarFactura($NumeroFactura,$Prefijo,$empresa_id)
    {
       $sql="SELECT 
               prefijo,
               numero,
               factura,
               fecha_registro,
               valor_total,
               valor_notacredito,
               valor_notadebito
               
             FROM
                  inv_facturas_despacho
              WHERE
                factura = ".$NumeroFactura."
                and
                prefijo ='".$Prefijo."'
                and
                empresa_id ='".$empresa_id."'
                
              ";
              
           //  $this->debug=true;
               if(!$resultado = $this->ConexionBaseDatos($sql))
                  return $this->frmError['MensajeError'];
                    
                  $cuentas=Array();
                  while(!$resultado->EOF)
                  {
                    $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
                    $resultado->MoveNext();
                  }
                  
                  $resultado->Close();
                  
                  return $cuentas;
    }
    
    
     function Productos_FacturaDespacho($empresa_id,$centro_utilidad,$bodega,$codigo_producto,$descripcion,$numero_factura,$prefijo,$offset)
    {
	  //$this->debug=true;
      
	  
	  $sql="
				SELECT
                  ifd.lote,
                  ifd.codigo_producto,
				  ifd.cantidad_devuelta,
				  ifd.cantidad,
				  inv.costo,
				  --invp.descripcion,
          fc_descripcion_producto(invp.codigo_producto) as descripcion, 
				  invp.contenido_unidad_venta,
                  unid.descripcion as descripcion_unidad
              FROM
                  inv_facturas_despacho_d as ifd,
				  inventarios as inv,
				  inventarios_productos as invp,
                  unidades as unid
              WHERE
                      ifd.empresa_id = '".$empresa_id."'
                      and
                      ifd.prefijo = '".$prefijo."'
                      and
                      ifd.inv_facturas_despacho = ".$numero_factura."
					  and
					  ifd.cantidad_devuelta > 0
					  and
					  ifd.empresa_id = inv.empresa_id
					  and
					  ifd.codigo_producto = inv.codigo_producto
					  and
					  inv.codigo_producto = invp.codigo_producto
					  and
					  invp.unidad_id = unid.unidad_id
					";

              if(!$resultado = $this->ConexionBaseDatos($sql))
                  return $this->frmError['MensajeError'];
                    
                  $cuentas=Array();
                  while(!$resultado->EOF)
                  {
                    $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
                    $resultado->MoveNext();
                  }
                  
                  $resultado->Close();
                  
                  return $cuentas;   
    }


	function ExistenciaLoteProductoDevolver($empresa_id,$centro_utilidad,$bodega,$codigo_producto)
		{
						
			
		$sql = 	"
								SELECT
								fv.codigo_producto,
								fv.existencia_actual,
								fv.fecha_vencimiento,
								fv.lote,
								inv.descripcion,
								inv.contenido_unidad_venta,
								unid.descripcion as descripcion_unidad
								FROM	
								existencias_bodegas_lote_fv fv,
								inventarios_productos inv,
								unidades unid
								WHERE 
								fv.empresa_id = '".$empresa_id."'
								AND
								fv.centro_utilidad = '".$centro_utilidad."'
								AND
								fv.bodega = '".$bodega."'
								AND
								fv.codigo_producto = '".$codigo_producto."'
								AND
								existencia_actual > 0
								and
								fv.codigo_producto = inv.codigo_producto
								and
								inv.unidad_id = unid.unidad_id
								;";
                                
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
 function ListadoProductos_FOC($empresa_id,$centro_utilidad,$bodega,$OrdenCompra)
		{
						
			
		$sql = 	"
								SELECT 	
                prodfoc.codigo_producto,
                prodfoc.sw_autorizado
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
    
    
     /*
    * Funcion de Guardar Productos fuera de la orden de Compra,
    * por Autorizar
    */
    function AgregarItemFOC($empresa_id,$centro_utilidad,$bodega,$usuario_id,$doc_tmp_id,$OrdenCompra,$CodigoProducto,$justificacion,$can,$total_costo,$iva,$lote,$fecha_vencimiento,$localizacion)
    {
              
        $sql  = "INSERT INTO compras_ordenes_pedidos_productosfoc (";
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
        $sql .= "       usuario_id     ";
        $sql .= "         ";
        $sql .= ") ";
        $sql .= "VALUES ( ";
        $sql .= "        '".$bodega."',";
        $sql .= "        ".$can.",";
        $sql .= "        '".$centro_utilidad."',";
        $sql .= "        '".$CodigoProducto."',";
        $sql .= "        ".$doc_tmp_id.",";
        $sql .= "        '".$empresa_id."',";
        $sql .= "        NOW(),";
        $sql .= "        '".$fecha_vencimiento."',";
        $sql .= "        '".$justificacion."',";
        $sql .= "        '".$lote."',";
        $sql .= "        ".$OrdenCompra.",";
        $sql .= "        ".$iva.",";
        $sql .= "        ".$total_costo.",";
        $sql .= "        '".$localizacion."',";
        $sql .= "        ".$usuario_id."";
		    $sql .= "       ); ";	
//			print_r($sql);
        
      
		if(!$result = $this->ConexionBaseDatos($sql))
				return false;
	    else
         return true;
			
			$result->Close();
       
    }
    
    function ProductoTemporal($empresa_id,$doc_tmp_id,$codigo_producto,$lote)
		{
						
			
		$sql = 	"
								SELECT 	
                *
								FROM	
                inv_bodegas_movimiento_tmp_d 
								WHERE 
                empresa_id = '".$empresa_id."'
                and
                doc_tmp_id = ".$doc_tmp_id."
                and
                usuario_id = ".UserGetUID()."
                and
                codigo_producto = '".$codigo_producto."'
                and
                lote = '".$lote."'
                ;";
                                
		//	$this->debug=true;
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