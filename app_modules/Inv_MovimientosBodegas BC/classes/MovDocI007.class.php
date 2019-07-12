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

class MovDocI007 extends ConexionBD
{
/***********************
* constructora
*************************/
function MovDocI007() {}

//Está para Pruebas y ejemplos
function Modificar_Laboratorio($datos)
	{
	  
    //$this->debug=true;
    $sql  = "UPDATE inv_laboratorios ";
    $sql .= "SET descripcion = '".$datos['descripcion']."',";
	  $sql .= "       telefono   = '".$datos['telefono']."',";
	  $sql .= "       direccion     ='".$datos['direccion']."', ";
	  $sql .= "       tipo_pais_id  ='".$datos['pais']."'";
    $sql .= " Where ";
    $sql .= "laboratorio_id ='".$datos['laboratorio_id']."';";
	  
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
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
    
    
     function Productos_Factura($empresa_id,$centro_utilidad,$bodega,$codigo_producto,$descripcion,$numero_factura,$prefijo,$numero,$offset)
    {
	//$this->debug=true;
      $sql="SELECT
                  ifd.lote,
                  ifd.codigo_producto,
				  ifd.cantidad_devuelta,
				  ifd.cantidad,
				  ebfv.existencia_actual,
				  ebfv.fecha_vencimiento,
				  inv.costo,
				  invp.descripcion,
				  invp.contenido_unidad_venta,
                  unid.descripcion as descripcion_unidad
                  
              FROM
                  inv_facturas_despacho_d as ifd,
                  existencias_bodegas_lote_fv as ebfv,
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
					  ifd.codigo_producto = ebfv.codigo_producto
					  and
					  ifd.lote = ebfv.lote
					  and
					  ifd.empresa_id = ebfv.empresa_id
					  and
					  ebfv.centro_utilidad = '".$centro_utilidad."'
					  and
					  ebfv.bodega = '".$bodega."'
					  and
					  ebfv.codigo_producto = inv.codigo_producto
					  and
					  ebfv.empresa_id = inv.empresa_id
					  and
					  inv.codigo_producto = invp.codigo_producto
					  and
					  invp.unidad_id = unid.unidad_id
					  ";
      
      
      /*$sql="SELECT
                  
                  b.codigo_producto,
                  b.descripcion,
                  b.unidad_id,
                  c.descripcion as descripcion_unidad,
                  a.existencia, 
                  d.costo
              FROM
                  existencias_bodegas as a,
                  inventarios_productos as b,
                  unidades as c,
                  inventarios as d
              WHERE
              a.empresa_id = '$empresa_id'
              AND a.centro_utilidad = '$centro_utilidad'
              AND a.bodega = '$bodega'
              ".$aumento." 
              AND b.codigo_producto = a.codigo_producto
              AND c.unidad_id = b.unidad_id
              AND d.empresa_id = a.empresa_id
              AND d.codigo_producto = a.codigo_producto ";*/
               //RETURN $sql;
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
    
    function BuscarProducto($empresa_id,$centro_utilidad,$bodega,$aumento,$offset)
    {
      $sql="SELECT
                  
                  b.codigo_producto,
                  b.descripcion,
                  b.unidad_id,
                  c.descripcion as descripcion_unidad,
                  a.existencia, 
                  d.costo
              FROM
                  inv_facturas_despacho_d
                  existencias_bodegas as a,
                  inventarios_productos as b,
                  unidades as c,
                  inventarios as d
              WHERE
              a.empresa_id = '$empresa_id'
              AND a.centro_utilidad = '$centro_utilidad'
              AND a.bodega = '$bodega'
              ".$aumento." 
              AND b.codigo_producto = a.codigo_producto
              AND c.unidad_id = b.unidad_id
              AND d.empresa_id = a.empresa_id
              AND d.codigo_producto = a.codigo_producto ";
      
      
      /*$sql="SELECT
                  
                  b.codigo_producto,
                  b.descripcion,
                  b.unidad_id,
                  c.descripcion as descripcion_unidad,
                  a.existencia, 
                  d.costo
              FROM
                  existencias_bodegas as a,
                  inventarios_productos as b,
                  unidades as c,
                  inventarios as d
              WHERE
              a.empresa_id = '$empresa_id'
              AND a.centro_utilidad = '$centro_utilidad'
              AND a.bodega = '$bodega'
              ".$aumento." 
              AND b.codigo_producto = a.codigo_producto
              AND c.unidad_id = b.unidad_id
              AND d.empresa_id = a.empresa_id
              AND d.codigo_producto = a.codigo_producto ";*/
               //RETURN $sql;
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

	
	function Buscar_ProductoEnDocTemporal($DocTmpId,$UsuarioId,$EmpresaId,$CentroUtilidad,$Bodega,$CodigoProducto,$Lote)
    {
	//$this->debug=true;
      $sql="SELECT
                  codigo_producto
                  
              FROM
                  inv_bodegas_movimiento_tmp_d
              WHERE
					  doc_tmp_id = ".$DocTmpId."
					  and
					  usuario_id = ".$UsuarioId."
                      and
					  empresa_id = '".$EmpresaId."'
                      and
                      centro_utilidad = '".$CentroUtilidad."'
                      and
                      bodega = '".$Bodega."'
					  and
					  codigo_producto = '".$CodigoProducto."'
					  and
					  lote = '".$Lote."'
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
	
	
	function ActualizarDevoluciones($prefijo,$numero,$codigo_producto,$lote,$cantidad)
	{
	  
   // $this->debug=true;
    $sql  = "UPDATE inv_facturas_despacho_d ";
    $sql .= "SET 
	          cantidad_devuelta = cantidad_devuelta + ".$cantidad.",";
    $sql .= " sw_pendientedevolver = '1'";
	
	$sql .= " Where ";
    $sql .= " codigo_producto ='".$codigo_producto."'";
	$sql .= " AND ";
	$sql .= " prefijo ='".$prefijo."'";
	$sql .= " AND ";
	$sql .= " inv_facturas_despacho = ".$numero." ";
	$sql .= " AND ";
	$sql .= " lote ='".$lote."'";
	  
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
	}
  
  function ActualizarCantidadRecibida($prefijo,$numero,$codigo_producto,$lote,$cantidad)
	{
	  
    //$this->debug=true;
    $sql  = "UPDATE inv_bodegas_movimiento_d ";
    $sql .= "SET 
	          cantidad_recibida = cantidad_recibida + ".$cantidad." ";
	$sql .= " Where ";
  $sql .= " codigo_producto ='".$codigo_producto."'";
	$sql .= " AND ";
	$sql .= " prefijo ='".$prefijo."'";
	$sql .= " AND ";
	$sql .= " numero = ".$numero." ";
	$sql .= " AND ";
	$sql .= " lote ='".$lote."'";
	  
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}

}
?>