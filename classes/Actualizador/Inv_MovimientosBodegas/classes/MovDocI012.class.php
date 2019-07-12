<?php

/* * ****************************************************************************
 * $Id: MovDocI002.class.php,v 1.0 
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * $Revision: 1.0 $ 
 * 
 * @autor Mauricio Adrian Medina
 * ****************************************************************************** */

class MovDocI012 extends ConexionBD {
    /*     * *********************
     * constructora
     * *********************** */

    function MovDocI012() {
        
    }

    function Listar_TercerosProveedores($TipoIdTercero, $TerceroId, $Descripcion, $Empresa_Id, $offset) {
        /* $this->debug=true; */
        $sql = "
                SELECT DISTINCT
                ter.tipo_id_tercero,
                ter.tercero_id,
                ter.dv,
                ter.nombre_tercero,
                ter.direccion,
                ter.telefono,
                tp.pais
                FROM terceros ter
                JOIN inv_facturas_despacho AS b ON (ter.tipo_id_tercero = b.tipo_id_tercero) AND (ter.tercero_id = b.tercero_id) AND (b.empresa_id = '" . SessionGetVar("EMPRESA") . "')
                JOIN tipo_pais as tp ON (ter.tipo_pais_id = tp.tipo_pais_id)
                WHERE ter.tipo_id_tercero ILIKE '%" . $TipoIdTercero . "%' AND ter.tercero_id ILIKE '%" . $TerceroId . "%' AND ter.nombre_tercero ILIKE '%" . $Descripcion . "%'
                
                union
                
                SELECT DISTINCT
                ter.tipo_id_tercero,
                ter.tercero_id,
                ter.dv,
                ter.nombre_tercero,
                ter.direccion,
                ter.telefono,
                tp.pais
                FROM terceros ter
                JOIN inv_facturas_agrupadas_despacho AS b ON (ter.tipo_id_tercero = b.tipo_id_tercero) AND (ter.tercero_id = b.tercero_id) AND (b.empresa_id = '" . SessionGetVar("EMPRESA") . "')
                JOIN tipo_pais as tp ON (ter.tipo_pais_id = tp.tipo_pais_id)
                WHERE ter.tipo_id_tercero ILIKE '%" . $TipoIdTercero . "%' AND ter.tercero_id ILIKE '%" . $TerceroId . "%' AND ter.nombre_tercero ILIKE '%" . $Descripcion . "%'
      ";
        
        if (!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(" . $sql . ") AS A", $offset))
            return false;

        $sql .= " ORDER BY 1 ";
        $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset . " ";
        
        
        /*echo "<pre>";
        print_r($sql);
        echo "</pre>";
        exit();*/
        

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    /*     * ************************************************************************************
     * Busca los tipos de identificacion que puede tener un tercero
     * 
     * @return array 
     * ************************************************************************************* */

    function Listar_TiposIdTerceros() {
        $sql = "SELECT	
                      tipo_id_tercero,
                      descripcion
							FROM		tipo_id_terceros;";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array(); //Definiendo que va a ser un arreglo.

        while (!$rst->EOF) { //Recorriendo el Vector;
            $datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    function Listar_FacturasProveedor($tipo_id_tercero, $tercero_id, $Empresa_Id) {

        $sql = "
				SELECT
				a.*
				FROM
				inv_facturas_despacho a
				JOIN (
				SELECT DISTINCT
				x.empresa_id,
				x.prefijo,
				x.factura_fiscal
				FROM
				inv_facturas_despacho_d as x
				WHERE
				x.empresa_id = '" . $Empresa_Id . "' 
				AND x.cantidad <>x.cantidad_devuelta  
				) as c ON (a.empresa_id = c.empresa_id)
				AND (a.prefijo = c.prefijo)
				AND (a.factura_fiscal = c.factura_fiscal)
				WHERE
				a.empresa_id =  '" . $Empresa_Id . "'
				AND a.tipo_id_tercero = '" . $tipo_id_tercero . "'
				AND a.tercero_id = '" . $tercero_id . "'
				ORDER BY a.fecha_registro ";
        $sql = "
            select  a.empresa_id, a.factura_fiscal, a.prefijo, a.documento_id,a.tipo_id_tercero, a.tercero_id, a.valor_notacredito,  a.valor_notadebito, a.fecha_registro,
            a.usuario_id, a.valor_total, a.fecha_vencimiento_factura, a.observaciones, a.porcentaje_rtf, a.porcentaje_ica, a.porcentaje_reteiva, a.saldo, a.porcentaje_cree,0 as fac_agrupada
            from inv_facturas_despacho a 
            inner join inv_facturas_despacho_d c on (a.empresa_id = c.empresa_id) AND (a.prefijo = c.prefijo) AND (a.factura_fiscal = c.factura_fiscal)
            WHERE a.empresa_id =  '{$Empresa_Id}' AND a.tipo_id_tercero = '{$tipo_id_tercero}' AND a.tercero_id = '{$tercero_id}'
            AND c.cantidad <> c.cantidad_devuelta  
            group by 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18
            UNION 
            select  a.empresa_id, a.factura_fiscal, a.prefijo, a.documento_id,a.tipo_id_tercero, a.tercero_id, a.valor_notacredito,  a.valor_notadebito, a.fecha_registro,
            a.usuario_id, a.valor_total, a.fecha_vencimiento_factura, a.observaciones, a.porcentaje_rtf, a.porcentaje_ica, a.porcentaje_reteiva, a.saldo, a.porcentaje_cree,1 as fac_agrupada
            from inv_facturas_agrupadas_despacho a 
            inner join inv_facturas_agrupadas_despacho_d c on (a.empresa_id = c.empresa_id) AND (a.prefijo = c.prefijo) AND (a.factura_fiscal = c.factura_fiscal)
            WHERE a.empresa_id =  '{$Empresa_Id}' AND a.tipo_id_tercero = '{$tipo_id_tercero}' AND a.tercero_id = '{$tercero_id}'
            AND c.cantidad <> c.cantidad_devuelta  
            group by 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18
            ORDER BY 2 
        ";
        
        /*print_r($sql);
        exit();*/

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function RegistrarFacturaProveedor($doc_tmp_id, $usuario_id, $NumeroFactura) {

        //$this->debug=true;
        $sql = " UPDATE inv_bodegas_movimiento_tmp ";
        $sql .= " SET 
	          factura_proveedor = '" . $NumeroFactura . "' ";
        $sql .= " Where ";
        $sql .= " doc_tmp_id = " . $doc_tmp_id . "";
        $sql .= " AND ";
        $sql .= " usuario_id =" . $usuario_id . "";


        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        else
            return true;

        $rst->Close();
    }

    function RegistrarFacturaDespacho($doc_tmp_id, $usuario_id, $Prefijo, $NumeroFactura) {

        //$this->debug=true;
        $sql = " UPDATE inv_bodegas_movimiento_tmp ";
        $sql .= " SET 
	          numero_factura = " . $NumeroFactura . ", ";
        $sql .= " prefijo_factura = '" . $Prefijo . "' ";
        $sql .= " Where ";
        $sql .= " doc_tmp_id = " . $doc_tmp_id . "";
        $sql .= " AND ";
        $sql .= " usuario_id =" . $usuario_id . "";


        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        else
            return true;

        $rst->Close();
    }

    function ListadoProductosFactura($empresa_id, $centro_utilidad, $bodega, $CodigoProveedorId, $NumeroFactura, $Codigo, $Descripcion, $doc_tmp_id, $offset) {
        //$this->debug=true;
        $sql = "
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
				  
              fp.codigo_proveedor_id = " . $CodigoProveedorId . "
						  AND
						  fp.numero_factura = '" . $NumeroFactura . "'
						  AND
						  fp.empresa_id ='" . $empresa_id . "'
						  AND
						  fp.centro_utilidad ='" . $centro_utilidad . "'
						  AND
						  fp.bodega ='" . $bodega . "'
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

        if (!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(" . $sql . ") AS A", $offset))
            return false;


        $sql .= "ORDER BY prod.descripcion ASC,copd.fecha_vencimiento ASC ";
        $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset . " ";

        //$this->debug=true;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function CantidadesDevueltasProductosOC($NumeroFactura, $codigo_producto, $lote, $cantidad) {

        //$this->debug=true;
        $sql = " UPDATE inv_facturas_proveedores_d ";
        $sql .= " SET 
	          cantidad_devuelta = cantidad_devuelta + " . $cantidad . " ";
        $sql .= " Where ";
        $sql .= " numero_factura = " . $NumeroFactura . "";
        $sql .= " AND ";
        $sql .= " codigo_producto = '" . $codigo_producto . "'";
        $sql .= " AND ";
        $sql .= " lote = '" . $lote . "'";


        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        else
            return true;

        $rst->Close();
    }

    /*
     * Va Restando de las cantidades pendientes por devolver
     */

    function CantidadesDevueltasFacturasDespacho($prefijo_factura, $numero_factura, $codigo_producto, $lote, $cantidad, $lote_devuelto) {

        //$this->debug=true;
        $sql = " UPDATE inv_facturas_despacho_d ";
        $sql .= " SET 
	          cantidad_devuelta = cantidad_devuelta - " . $cantidad . " ";
        $sql .= " Where ";
        $sql .= " prefijo = '" . $prefijo_factura . "'";
        $sql .= " AND ";
        $sql .= " inv_facturas_despacho = " . $numero_factura . "";
        $sql .= " AND ";
        $sql .= " codigo_producto = '" . $codigo_producto . "'";
        $sql .= " AND ";
        $sql .= " lote = '" . $lote_devuelto . "'";


        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        else
            return true;

        $rst->Close();
    }

    function RegistroLoteDevuelto($doc_tmp_id, $codigo_producto, $lotec, $lote_devuelto) {

        //$this->debug=true;
        $sql = " UPDATE inv_bodegas_movimiento_tmp_d ";
        $sql .= " SET 
	          lote_devuelto = '" . $lote_devuelto . "' ";
        $sql .= " Where ";
        $sql .= " doc_tmp_id = " . $doc_tmp_id . "";
        $sql .= " AND ";
        $sql .= " codigo_producto = '" . $codigo_producto . "'";
        $sql .= " AND ";
        $sql .= " lote = '" . $lotec . "'";


        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        else
            return true;

        $rst->Close();
    }

    /*
     * Listado de Productos que estan en la orden de Compra
     */

    function ExistenciaLote($empresa_id, $centro_utilidad, $bodega, $codigo_producto, $lote) {


        $sql = "
								SELECT 	
								existencia_actual
								FROM	
								existencias_bodegas_lote_fv
								WHERE 
								empresa_id = '" . $empresa_id . "'
								AND
								centro_utilidad = '" . $centro_utilidad . "'
								AND
								bodega = '" . $bodega . "'
								AND
								codigo_producto = '" . $codigo_producto . "'
								AND
								lote = '" . $lote . "'
								AND
								existencia_actual > 0
                ;";

        //$this->debug=true;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function ListarPrefijosFacturasTercero($tercero_id, $tipo_id_tercero, $empresa_id) {
        $sql = "SELECT 
               prefijo
             FROM
                  inv_facturas_despacho
              WHERE
                tercero_id = '" . $tercero_id . "'
                and
                tipo_id_tercero ='" . $tipo_id_tercero . "'
                and
                empresa_id ='" . $empresa_id . "'
                
                group by prefijo;
              ";

        // $this->debug=true;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return $this->frmError['MensajeError'];

        $cuentas = Array();
        while (!$resultado->EOF) {
            $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();

        return $cuentas;
    }

    function BuscarFactura($NumeroFactura, $Prefijo, $empresa_id) {
        $sql = "SELECT 
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
                factura = " . $NumeroFactura . "
                and
                prefijo ='" . $Prefijo . "'
                and
                empresa_id ='" . $empresa_id . "'
                
              ";

        //  $this->debug=true;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return $this->frmError['MensajeError'];

        $cuentas = Array();
        while (!$resultado->EOF) {
            $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();

        return $cuentas;
    }

    function Productos_FacturaDespacho($empresa_id, $centro_utilidad, $bodega, $codigo_producto, $descripcion, $numero_factura, $prefijo, $offset) {
        //$this->debug=true;


        $sql = "
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
                      ifd.empresa_id = '" . $empresa_id . "'
                      and
                      ifd.prefijo = '" . $prefijo . "'
                      and
                      ifd.inv_facturas_despacho = " . $numero_factura . "
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

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return $this->frmError['MensajeError'];

        $cuentas = Array();
        while (!$resultado->EOF) {
            $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();

        return $cuentas;
    }

    function ExistenciaLoteProductoDevolver($empresa_id, $centro_utilidad, $bodega, $codigo_producto) {


        $sql = "
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
								fv.empresa_id = '" . $empresa_id . "'
								AND
								fv.centro_utilidad = '" . $centro_utilidad . "'
								AND
								fv.bodega = '" . $bodega . "'
								AND
								fv.codigo_producto = '" . $codigo_producto . "'
								AND
								existencia_actual > 0
								and
								fv.codigo_producto = inv.codigo_producto
								and
								inv.unidad_id = unid.unidad_id
								;";

        //$this->debug=true;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
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

    function ListadoProductos_FOC($empresa_id, $centro_utilidad, $bodega, $OrdenCompra) {


        $sql = "
								SELECT 	
                prodfoc.codigo_producto,
                prodfoc.sw_autorizado
								FROM	
                compras_ordenes_pedidos_productosfoc prodfoc
								WHERE 
                prodfoc.empresa_id = '" . $empresa_id . "'
                and
                prodfoc.centro_utilidad = '" . $centro_utilidad . "'
                and
                prodfoc.bodega = '" . $bodega . "'
                and
                prodfoc.orden_pedido_id = " . $OrdenCompra . "
                and
                prodfoc.sw_autorizado = '0';";

        //$this->debug=true;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
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

    function AgregarItemFOC($empresa_id, $centro_utilidad, $bodega, $usuario_id, $doc_tmp_id, $OrdenCompra, $CodigoProducto, $justificacion, $can, $total_costo, $iva, $lote, $fecha_vencimiento, $localizacion) {

        $sql = "INSERT INTO compras_ordenes_pedidos_productosfoc (";
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
        $sql .= "        '" . $bodega . "',";
        $sql .= "        " . $can . ",";
        $sql .= "        '" . $centro_utilidad . "',";
        $sql .= "        '" . $CodigoProducto . "',";
        $sql .= "        " . $doc_tmp_id . ",";
        $sql .= "        '" . $empresa_id . "',";
        $sql .= "        NOW(),";
        $sql .= "        '" . $fecha_vencimiento . "',";
        $sql .= "        '" . $justificacion . "',";
        $sql .= "        '" . $lote . "',";
        $sql .= "        " . $OrdenCompra . ",";
        $sql .= "        " . $iva . ",";
        $sql .= "        " . $total_costo . ",";
        $sql .= "        '" . $localizacion . "',";
        $sql .= "        " . $usuario_id . "";
        $sql .= "       ); ";
//			print_r($sql);


        if (!$result = $this->ConexionBaseDatos($sql))
            return false;
        else
            return true;

        $result->Close();
    }

    function ProductoTemporal($empresa_id, $doc_tmp_id, $codigo_producto, $lote) {


        $sql = "
								SELECT 	
                *
								FROM	
                inv_bodegas_movimiento_tmp_d 
								WHERE 
                empresa_id = '" . $empresa_id . "'
                and
                doc_tmp_id = " . $doc_tmp_id . "
                and
                usuario_id = " . UserGetUID() . "
                and
                codigo_producto = '" . $codigo_producto . "'
                and
                lote = '" . $lote . "'
                ;";

        //	$this->debug=true;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

}

?>