<?php

/**
* $Id: BodegasProductos.class.php,v 1.8 2007/08/28 23:12:19 alexgiraldo Exp $
*/

/**
* Clase con metodos para el mantenimiento productos de inventario.
*
* @author Alexander Giraldo <alexgiraldo@ipsoft-sa.com>
* @version $Revision: 1.8 $
* @package SIIS
*/
class BodegasProductos
{
    /**
    * Codigo de error
    *
    * @var string
    * @access private
    */
    var $error;

    /**
    * Mensaje de error
    *
    * @var string
    * @access private
    */
    var $mensajeDeError;



    /************************************************************************************
    * M E T O D O S
    ************************************************************************************/

    /**
    * Constructor
    *
    * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
    * @access public
    */
    function BodegasProductos()
    {
        return true;
    }


    /**
    * metodo para obtener los productos de una bodega
    *
    * @param string  $empresa_id Identificador de la bodega
    * @param string  $centro_utilidad Identificador de la bodega
    * @param string  $bodega Identificador de la bodega
    * @param string  $grupo_id
    * @param string  $clase_id
    * @param string  $subclase_id
    * @param boolean $count (opcional) True/False indica si retorna el numero de registros o los registros
    * @param integer $limit (opcional) limite de registros que retorna la consulta
    * @param integer $offset (opcional) desde que registro inicia la consulta
    * @param string  $orderby
    * @return array
    * @access public
    */
    function GetBodegaProductos($empresa_id, $centro_utilidad, $bodega, $codigo_producto=null, $descripcion=null, $grupo_id=null, $clase_id=null, $subclase_id=null, $count=null, $limit=null, $offset=null, $orderby = 'ASC')
    {
        if(empty($empresa_id) || empty($centro_utilidad) || empty($bodega) )
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "Parametros incompletos.";
            return false;
        }

        if(empty($count))
        {
            $select = "
                        a.empresa_id,
                        a.centro_utilidad,
                        a.bodega,
                        b.codigo_producto,
                        b.descripcion,
                        b.descripcion_abreviada,
                        b.unidad_id,
                        c.descripcion as descripcion_unidad,
                        b.estado,
                        b.codigo_invima,
                        b.contenido_unidad_venta,
                        b.sw_control_fecha_vencimiento,
                        d.existencia_minima,
                        d.existencia_maxima,
                        a.existencia,
                        d.existencia as existencia_global,
                        d.costo_anterior,
                        d.costo,
                        CASE WHEN d.costo > 0 THEN ROUND(((d.precio_venta/d.costo)-1) * 100) ELSE NULL END as porcentaje_utlidad,
                        d.costo_penultima_compra,
                        d.costo_ultima_compra,
                        d.precio_venta_anterior,
                        d.precio_venta,
                        d.precio_minimo,
                        d.precio_maximo,
                        d.sw_vende,
                        d.grupo_contratacion_id,
                        d.nivel_autorizacion_id,
                        b.grupo_id,
                        b.clase_id,
                        b.subclase_id
            ";
        }
        else
        {
            $select = " COUNT(*) as cantidad";
        }

        if(is_numeric($limit) && is_numeric($offset))
        {
            $filtro_limit = " LIMIT $limit OFFSET $offset ";
        }
        else
        {
            $filtro_limit = "";
        }

        if($orderby)
        {
            $filtro_orderby = " ORDER BY b.codigo_producto $orderby";
        }

        if(!empty($grupo_id))
        {
            $filtro_grupo = " AND b.grupo_id = '$grupo_id' ";
        }
        else
        {
            $filtro_grupo = "";
        }

        if(!empty($clase_id))
        {
            $filtro_clase = " AND b.clase_id = '$clase_id' ";
        }
        else
        {
            $filtro_clase = "";
        }

        if(!empty($subclase_id))
        {
            $filtro_subclase = " AND b.subclase_id = '$subclase_id' ";
        }
        else
        {
            $filtro_subclase = "";
        }


        if(!empty($codigo_producto))
        {
            $filtro_codigo_producto = " AND a.codigo_producto ILIKE '$codigo_producto"."%' ";
        }
        else
        {
            $filtro_codigo_producto = "";
        }

        if(!empty($descripcion))
        {
            $filtro_descripcion = " AND b.descripcion ILIKE '%".$descripcion."%' ";
        }
        else
        {
            $filtro_descripcion = "";
        }

        $sql="
                SELECT
                    $select

                FROM
                    existencias_bodegas as a,
                    inventarios_productos as b,
                    unidades as c,
                    inventarios as d

                WHERE
                    a.empresa_id = '$empresa_id'
                    AND a.centro_utilidad = '$centro_utilidad'
                    AND a.bodega = '$bodega'
                    AND b.codigo_producto = a.codigo_producto
                    AND c.unidad_id = b.unidad_id
                    AND d.empresa_id = a.empresa_id
                    AND d.codigo_producto = a.codigo_producto
                    $filtro_codigo_producto
                    $filtro_descripcion
                    $filtro_grupo
                    $filtro_clase
                    $filtro_subclase
                    $filtro_orderby
                    $filtro_limit;
        ";


        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if(empty($count))
        {
            $retorno = array();

            while($fila = $result->FetchRow())
            {
                $retorno[]=$fila;
            }
            $result->Close();
        }
        else
        {
            $fila = $result->FetchRow();
            $retorno = $fila['cantidad'];
        }

        return  $retorno;
    }



    /**
    * Obtener la Informacion de un Producto.
    *
    * @param string  $empresa_id
    * @param string  $centro_utilidad Identificador de la bodega
    * @param string  $bodega Identificador de la bodega
    * @param string  $codigo_producto
    * @return boolean
    * @access public
    */
    function GetInfoProducto($empresa_id, $centro_utilidad, $bodega, $codigo_producto, $limit=null)
    {
        if(empty($empresa_id) || empty($centro_utilidad) || empty($bodega) )
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "Parametros de bodega nulos.";
            return false;
        }

        if(empty($codigo_producto))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "El parametro [codigo_producto] es nulo.";
            return false;
        }

        $sql = "
                SELECT
                        a.empresa_id,
                        a.centro_utilidad,
                        a.bodega,
                        b.codigo_producto,
                        b.descripcion,
                        b.descripcion_abreviada,
                        b.unidad_id,
                        c.descripcion as descripcion_unidad,
                        b.estado,
                        b.codigo_invima,
                        b.contenido_unidad_venta,
                        b.sw_control_fecha_vencimiento,
                        d.existencia_minima,
                        d.existencia_maxima,
                        a.existencia,
                        d.existencia as existencia_global,
                        d.costo_anterior,
                        d.costo,
                        CASE WHEN d.costo > 0 THEN ROUND(((d.precio_venta/d.costo)-1) * 100) ELSE NULL END as porcentaje_utlidad,
                        d.costo_penultima_compra,
                        d.costo_ultima_compra,
                        d.precio_venta_anterior,
                        d.precio_venta,
                        d.precio_minimo,
                        d.precio_maximo,
                        d.sw_vende,
                        d.grupo_contratacion_id,
                        d.nivel_autorizacion_id,
                        b.grupo_id,
                        b.clase_id,
                        b.subclase_id

                FROM
                    existencias_bodegas as a,
                    inventarios_productos as b,
                    unidades as c,
                    inventarios as d
                WHERE

                    a.empresa_id = '$empresa_id'
                    AND a.centro_utilidad = '$centro_utilidad'
                    AND a.bodega = '$bodega'
                    AND a.codigo_producto = '$codigo_producto'
                    AND b.codigo_producto = a.codigo_producto
                    AND c.unidad_id = b.unidad_id
                    AND d.empresa_id = a.empresa_id
                    AND d.codigo_producto = a.codigo_producto
                ";

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($result->EOF)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "EL PRODUCTO CONSULTADO NO EXISTE.";
            return false;
        }

        $fila = $result->FetchRow();
        $result->Close();

        if(is_numeric($limit))
        {
            $filtro_limit = " LIMIT $limit OFFSET 0";
        }
        else
        {
            $filtro_limit = "";
        }

        $sql = "

            SELECT *
            FROM
            (
                (
                    SELECT
                        CASE WHEN d.inv_tipo_movimiento IN ('E','T')  THEN 'EGRESO'  ELSE 'INGRESO' END as tipo,
                        d.inv_tipo_movimiento as tipo_movimiento,
                        to_char(a.fecha_registro,'YYYY-MM-DD HH24:MI') as fecha,
                        a.prefijo,
                        a.numero,
                        b.cantidad,
                        round(b.total_costo/b.cantidad) as costo,
                        f.usuario,
                        f.nombre,
                        a.observacion

                    FROM
                        inv_bodegas_documentos as e,
                        inv_bodegas_movimiento as a,
                        inv_bodegas_movimiento_d as b,
                        documentos as c,
                        tipos_doc_generales as d,
                        system_usuarios as f
                    WHERE
                        e.empresa_id = '$empresa_id'
                        AND e.centro_utilidad = '$centro_utilidad'
                        AND e.bodega = '$bodega'
                        AND a.documento_id = e.documento_id
                        AND a.empresa_id = e.empresa_id
                        AND a.centro_utilidad = e.centro_utilidad
                        AND a.bodega = e.bodega
                        AND b.empresa_id = a.empresa_id
                        AND b.prefijo = a.prefijo
                        AND b.numero = a.numero
                        AND b.codigo_producto = '$codigo_producto'
                        AND c.documento_id = a.documento_id
                        AND c.empresa_id = a.empresa_id
                        AND d.tipo_doc_general_id = c.tipo_doc_general_id
                        AND f.usuario_id = a.usuario_id
                )
                UNION ALL
                (
                    SELECT
                        CASE WHEN d.cargo = 'IMD'  THEN 'EGRESO' WHEN d.cargo = 'DIMD' THEN 'INGRESO' ELSE '?' END as tipo,
                        CASE WHEN d.cargo = 'IMD'  THEN 'C' WHEN d.cargo = 'DIMD' THEN 'D' ELSE '?' END as tipo_movimiento,
                        to_char(b.fecha_registro,'YYYY-MM-DD HH24:MI') as fecha,
                        c.prefijo,
                        b.numeracion as numero,
                        a.cantidad,
                        a.total_costo as costo,
                        f.usuario,
                        f.nombre,
                        'Cuenta No.' || d.numerodecuenta as observacion

                    FROM
                        bodegas_documentos_d as a,
                        bodegas_documentos as b,
                        bodegas_doc_numeraciones as c,
                        cuentas_detalle as d,
                        system_usuarios as f

                    WHERE
                        c.empresa_id = '$empresa_id'
                        AND c.centro_utilidad = '$centro_utilidad'
                        AND c.bodega = '$bodega'
                        AND b.bodegas_doc_id = c.bodegas_doc_id
                        AND a.bodegas_doc_id = b.bodegas_doc_id
                        AND a.numeracion = b.numeracion
                        AND a.codigo_producto = '$codigo_producto'
                        AND d.consecutivo = a.consecutivo
                        AND f.usuario_id = b.usuario_id
                )
                UNION ALL
                (
                    SELECT
                        'INGRESO' as tipo,
                        d.inv_tipo_movimiento as tipo_movimiento,
                        to_char(a.fecha_registro,'YYYY-MM-DD HH24:MI') as fecha,
                        a.prefijo,
                        a.numero,
                        b.cantidad,
                        round(b.total_costo/b.cantidad) as costo,
                        f.usuario,
                        f.nombre,
                        'BODEGA ORIGEN ['||t.centro_utilidad_destino ||']['|| t.bodega_destino ||'] ' || a.observacion as observacion

                    FROM
                        inv_bodegas_movimiento_traslados as t,
                        inv_bodegas_movimiento as a,
                        inv_bodegas_movimiento_d as b,
                        documentos as c,
                        tipos_doc_generales as d,
                        system_usuarios as f
                    WHERE
                        t.empresa_id = '$empresa_id'
                        AND t.centro_utilidad_destino = '$centro_utilidad'
                        AND t.bodega_destino = '$bodega'
                        AND a.empresa_id = t.empresa_id
                        AND a.prefijo = t.prefijo
                        AND a.numero = t.numero
                        AND b.empresa_id = a.empresa_id
                        AND b.prefijo = a.prefijo
                        AND b.numero = a.numero
                        AND b.codigo_producto = '$codigo_producto'
                        AND c.documento_id = a.documento_id
                        AND c.empresa_id = a.empresa_id
                        AND d.tipo_doc_general_id = c.tipo_doc_general_id
                        AND f.usuario_id = a.usuario_id

                )
            ) AS DATOS
            ORDER BY DATOS.fecha DESC
            $filtro_limit;
        ";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        while($lista = $result->FetchRow())
        {
            $fila['KARDEX'][] = $lista;
        }
        $result->Close();


        return $fila;
    }

}
?>