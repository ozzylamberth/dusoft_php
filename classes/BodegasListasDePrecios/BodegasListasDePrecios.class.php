<?php

/**
* $Id: BodegasListasDePrecios.class.php,v 1.13 2007/08/22 18:35:22 jgomez Exp $
*/

/**
* Clase con metodos para el mantenimiento de listas de precios de inventario.
*
* @author Alexander Giraldo <alexgiraldo@ipsoft-sa.com>
* @version $Revision: 1.13 $
* @package SIIS
*/
class BodegasListasDePrecios
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
    function BodegasListasDePrecios()
    {
        return true;
    }

    
    /**
    * Metodo para recuperar el titulo del error
    *
    * @return string
    * @access public
    */
    function Err()
    {
        return $this->error;
    }


    /**
    * Metodo para recuperar el detalle del error
    *
    * @return string
    * @access public
    */
    function ErrMsg()
    {
        return $this->mensajeDeError;
    }
    

    /**
    * metodo para obtener porcenje de utilidad minimo
    *
    * @param string  $empresa_id
    * @return array
    * @access public
    */
    function GetPorcentajeUtilidadMinimo($empresa_id)
    {
        if(empty($empresa_id))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "El parametro [empresa_id] es nulo.";
            return false;
        }

        $porcentaje = ModuloGetVar('app', 'Lista_Precios', "pocentaje_minimo_empresa_$empresa_id");

        if(!is_numeric($porcentaje))
        {
            $porcentaje = 0;
        }

        return $porcentaje;
    }


    /**
    * metodo para obtener porcenje de utilidad minimo
    *
    * @param string  $empresa_id
    * @return array
    * @access public
    */
    function SetPorcentajeUtilidadMinimo($empresa_id,$porcentaje=0)
    {
        if(empty($empresa_id))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "El parametro [empresa_id] es nulo.";
            return false;
        }

        ModuloSetVar('app', 'Lista_Precios', "pocentaje_minimo_empresa_$empresa_id", $porcentaje);
        return $porcentaje;
    }



    /**
    * metodo para obtener la cantidad de productos que tienen un porcenje de venta inferior al del costo
    *
    * @param string  $empresa_id
    * @param numeric $porcentaje
    * @return array
    * @access public
    */
    function GetCantidadDeProductosPorPorcentaje($empresa_id, $porcentaje=null)
    {
        if(empty($empresa_id))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "El parametro [empresa_id] es nulo.";
            return false;
        }

        if($porcentaje===null)
        {
            $porcentaje = $this->GetPorcentajeUtilidadMinimo($empresa_id);
        }

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql="
            SELECT
                A.codigo_lista,
                A.descripcion,
                B.cantidad
            FROM
            listas_precios AS A LEFT JOIN
            (
                (
                    SELECT
                        count(*) as cantidad,
                        '000' as codigo_lista
                    FROM
                    (SELECT * FROM inventarios WHERE empresa_id = '$empresa_id' AND costo > 0) AS a

                    WHERE (((a.precio_venta/a.costo)-1) * 100) < $porcentaje
                    AND a.costo > 0
                )
                UNION
                (
                    SELECT
                        count(*) as cantidad,
                        codigo_lista
                    FROM
                    (
                        SELECT
                            x.costo,
                            y.precio as precio_venta,
                            y.codigo_lista

                        FROM inventarios as x,
                            listas_precios_detalle as y
                        WHERE x.costo > 0
                            AND y.empresa_id = '$empresa_id'
                            AND y.codigo_lista != '000'
                            AND x.empresa_id = y.empresa_id
                            AND x.codigo_producto = y.codigo_producto

                    ) AS a

                    WHERE (((a.precio_venta/a.costo)-1) * 100) < $porcentaje
                    AND a.costo > 0
                    GROUP BY  codigo_lista
                )
            ) AS B ON (B.codigo_lista = A.codigo_lista)
            ORDER BY codigo_lista;
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

        $salida = array();

        //POR CADA LISTA DE PRECIOS
        while($fila = $result->FetchRow())
        {
            $salida[$fila['codigo_lista']]=$fila;
        }

        return $salida;
    }



    /**
    * metodo para obtener los productos que tienen un porcenje de venta inferior al del costo de una lista
    *
    * @param string  $empresa_id
    * @param string  $lista
    * @param numeric $porcentaje
    * @param boolean $count (opcional) True/False indica si retorna el numero de registros o los registros
    * @param integer $limit (opcional) limite de registros que retorna la consulta
    * @param integer $offset (opcional) desde que registro inicia la consulta
    * @param string $orderby
    * @return array
    * @access public
    */
    function GetListaDeProductosPorPorcentaje($empresa_id, $lista, $porcentaje=null, $count=null, $limit=null, $offset=null, $orderby = 'ASC')
    {
        if(empty($empresa_id))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "El parametro [empresa_id] es nulo.";
            return false;
        }

        if(empty($lista))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "El parametro [lista] es nulo.";
            return false;
        }


        if($porcentaje===null)
        {
            $porcentaje = $this->GetPorcentajeUtilidadMinimo($empresa_id);
        }

        if(empty($count))
        {
            $select = "
                            a.codigo_producto,
                            b.descripcion,
                            b.unidad_id,
                            c.descripcion as descripcion_unidad,
                            a.existencia,
                            a.costo_penultima_compra,
                            a.costo_ultima_compra,
                            a.costo_anterior,
                            a.costo,
                            a.precio_venta,
                            round(((a.precio_venta/a.costo)-1) * 100) as porcentaje
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
            $filtro_orderby = " ORDER BY a.codigo_producto $orderby";
        }

        if($lista === '000')
        {
            $sql="
                    SELECT
                        $select
                    FROM
                        (SELECT * FROM inventarios WHERE empresa_id = '$empresa_id' AND costo > 0) AS a,
                        inventarios_productos as b,
                        unidades as c


                    WHERE (((a.precio_venta/a.costo)-1) * 100) < $porcentaje
                        AND a.costo > 0
                        AND b.codigo_producto = a.codigo_producto
                        AND c.unidad_id = b.unidad_id
                        $filtro_orderby
                        $filtro_limit;
            ";
        }
        else
        {
            $sql="
                    SELECT
                        $select
                    FROM
                    (
                        SELECT
                            x.codigo_producto,
                            x.existencia,
                            x.costo_penultima_compra,
                            x.costo_ultima_compra,
                            x.costo_anterior,
                            x.costo,
                            y.precio as precio_venta

                        FROM inventarios as x,
                            listas_precios_detalle as y
                        WHERE x.costo > 0
                            AND (((y.precio/x.costo)-1) * 100) < $porcentaje
                            AND y.empresa_id = '$empresa_id'
                            AND y.codigo_lista = '$lista'
                            AND x.empresa_id = y.empresa_id
                            AND x.codigo_producto = y.codigo_producto
                    ) AS a,
                        inventarios_productos as b,
                        unidades as c

                    WHERE
                        b.codigo_producto = a.codigo_producto
                        AND c.unidad_id = b.unidad_id
                        $filtro_orderby
                        $filtro_limit;
            ";
        }

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
    * metodo para obtener los productos de una lista
    *
    * @param string  $empresa_id
    * @param string  $lista
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
    function GetListaDeProductos($empresa_id, $lista=null, $grupo_id=null, $clase_id=null, $subclase_id=null, $count=null, $limit=null, $offset=null, $orderby = 'ASC')
    {
        if(empty($empresa_id))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "El parametro [empresa_id] es nulo.";
            return false;
        }

        if($lista===null)
        {
            $lista = '000';
        }


        if(empty($count))
        {
            $select = "
                            x.codigo_producto,
                            b.descripcion,
                            b.unidad_id,
                            c.descripcion as descripcion_unidad,
                            x.existencia,
                            x.costo_penultima_compra,
                            x.costo_ultima_compra,
                            x.costo_anterior,
                            x.costo,
                            x.precio_venta,
                            CASE WHEN x.costo > 0 THEN round(((x.precio_venta/x.costo)-1) * 100) ELSE NULL END as porcentaje,
                            '000' as lista 
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
            $filtro_orderby = " ORDER BY x.codigo_producto $orderby";
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

            $sql="
                    SELECT
                        $select
                    FROM
                        inventarios as x,
                        inventarios_productos as b,
                        unidades as c


                    WHERE x.empresa_id = '$empresa_id'
                        AND b.codigo_producto = x.codigo_producto
                        AND c.unidad_id = b.unidad_id
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
    * Modificar el precio de venta de un producto en una lista de precios.
    *
    * @param string  $empresa_id
    * @param string  $lista
    * @param string  $codigo_producto
    * @param numeric $precio
    * @return boolean
    * @access public
    */
    function SetPrecioListaProducto($empresa_id, $lista, $codigo_producto, $precio)
    {
        if(empty($empresa_id))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "El parametro [empresa_id] es nulo.";
            return false;
        }

        if(empty($lista))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "El parametro [lista] es nulo.";
            return false;
        }

        if(empty($codigo_producto))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "El parametro [codigo_producto] es nulo.";
            return false;
        }

        if(!is_numeric($precio))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "El parametro [$precio] no es numerico.";
            return false;
        }

        if($lista==='000' OR $lista==='0000')
        {
            $sql = "UPDATE inventarios SET precio_venta = $precio
                    WHERE empresa_id = '".$empresa_id."' AND codigo_producto = '".$codigo_producto."';
            ";

        }
        else
        {
            $sql = "UPDATE listas_precios_detalle SET precio = $precio
                    WHERE empresa_id = '".$empresa_id."'
                    AND codigo_lista = '".$lista."'
                    AND codigo_producto = '".$codigo_producto."';
            ";
        }

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();
        $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        return true;

    }


    /**
    * Obtener la Informacion de un Producto.
    *
    * @param string  $empresa_id
    * @param string  $codigo_producto
    * @return boolean
    * @access public
    */
    function GetInfoProducto($empresa_id,$codigo_producto)
    {
        if(empty($empresa_id))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "El parametro [empresa_id] es nulo.";
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
                    b.codigo_producto,
                    b.descripcion,
                    b.descripcion_abreviada,
                    b.unidad_id,
                    c.descripcion as descripcion_unidad,
                    b.estado,
                    b.codigo_invima,
                    b.contenido_unidad_venta,
                    b.sw_control_fecha_vencimiento,
                    m.cod_anatomofarmacologico,
                    m.cod_principio_activo,
                    a.existencia_minima,
                    a.existencia_maxima,
                    a.existencia,
                    a.costo_anterior,
                    a.costo,
                    CASE WHEN a.costo > 0 THEN ROUND(((a.precio_venta/a.costo)-1) * 100) ELSE NULL END as porcentaje_utlidad,
                    a.costo_penultima_compra,
                    a.costo_ultima_compra,
                    a.precio_venta_anterior,
                    a.precio_venta,
                    a.precio_minimo,
                    a.precio_maximo,
                    a.sw_vende,
                    a.grupo_contratacion_id,
                    a.nivel_autorizacion_id

                FROM
                    inventarios as a,
                    inventarios_productos as b
                    LEFT JOIN medicamentos as m
                    ON (m.codigo_medicamento = b.codigo_producto),
                    unidades as c
                WHERE
                    a.empresa_id = '$empresa_id'
                    AND a.codigo_producto = '$codigo_producto'
                    AND b.codigo_producto = a.codigo_producto
                    AND c.unidad_id = b.unidad_id
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

        $costo = $fila['costo'];

        $sql = "
                SELECT
                    a.codigo_lista,
                    a.descripcion,
                    b.precio as precio_venta,
                    CASE WHEN $costo > 0 THEN ROUND(((b.precio/$costo)-1) * 100) ELSE NULL END as porcentaje_utlidad

                FROM
                    listas_precios as a,
                    listas_precios_detalle as b
                WHERE
                    b.codigo_producto = '$codigo_producto';
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
            $fila['LISTAS_DE_PRECIOS'][$lista['codigo_lista']] = $lista;
        }
        $result->Close();


        $sql = "
                SELECT
                    b.centro_utilidad,
                    b.bodega,
                    b.descripcion,
                    a.existencia,
                    a.existencia_minima,
                    a.existencia_maxima

                FROM existencias_bodegas as a, bodegas as b

                WHERE
                    a.codigo_producto = '$codigo_producto'
                    AND a.empresa_id = '$empresa_id'
                    AND a.existencia > 0
                    AND a.bodega NOT IN ('FF')
                    AND b.empresa_id = a.empresa_id
                    AND b.centro_utilidad = a.centro_utilidad
                    AND b.bodega = a.bodega

                ORDER BY 1,2;
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
            $fila['EXISTENCIAS'][] = $lista;
        }
        $result->Close();



        $sql = "
                    SELECT
                        CASE WHEN d.cargo = 'IMD'  THEN 'C' WHEN d.cargo = 'DIMD' THEN 'D' ELSE '?' END as tipo_movimiento,
                        to_char(b.fecha_registro,'YYYY-MM-DD HH24:MI') as fecha,
                        e.centro_utilidad,
                        e.bodega,
                        e.descripcion as nombre_bodega,
                        b.bodegas_doc_id,
                        c.prefijo,
                        b.numeracion as numero,
                        a.cantidad,
                        a.total_costo as costo,
                        f.usuario,
                        f.nombre,
                        d.numerodecuenta,
                        12345 as existencia

                    FROM

                        bodegas_documentos_d as a,
                        bodegas_documentos as b,
                        bodegas_doc_numeraciones as c,
                        cuentas_detalle as d,
                        bodegas as e,
                        system_usuarios as f

                    WHERE
                        a.codigo_producto = '$codigo_producto'
                        AND b.bodegas_doc_id = a.bodegas_doc_id
                        AND b.numeracion = a.numeracion
                        AND c.bodegas_doc_id = b.bodegas_doc_id
                        AND c.empresa_id = '$empresa_id'
                        AND d.consecutivo = a.consecutivo
                        AND e.empresa_id = c.empresa_id
                        AND e.centro_utilidad = c.centro_utilidad
                        AND e.bodega = c.bodega
                        AND f.usuario_id = b.usuario_id


                    ORDER BY b.fecha_registro DESC
                    LIMIT 100 OFFSET 0;
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