<?php

/**
* $Id: BodegasProductos.class.php,v 1.47 2008/04/09 19:20:39 jgomez Exp $
*/

/**
* Clase con metodos para el mantenimiento productos de inventario.
*
* @author Alexander Giraldo <alexgiraldo@ipsoft-sa.com>
* @version $Revision: 1.47 $
* @package SIIS
*/
IncludeClass('ClaseUtil');
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
    function GetBodegaProductos($empresa_id, $centro_utilidad, $bodega, $codigo_producto=null, $descripcion=null, $grupo_id=null, $clase_id=null, $subclase_id=null,$molecula_bus=null, $count=null, $limit=null, $offset=null, $orderby = 'ASC')
    {
        if(empty($empresa_id) || empty($centro_utilidad) || empty($bodega) )
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "Parametros incompletos.";
            return false;
        }

        //
        
        if(empty($count))
        {
            $select = "
                        a.empresa_id,
                        a.centro_utilidad,
                        a.bodega,
                        b.codigo_producto,
                        fc_descripcion_producto(b.codigo_producto) as nombre,
                        b.descripcion,
                        b.descripcion_abreviada,
                        b.unidad_id,
                        c.descripcion as descripcion_unidad,
                        b.estado,
                        b.codigo_invima,
                        b.contenido_unidad_venta,
                        b.sw_control_fecha_vencimiento,
                        a.existencia_minima,
                        a.existencia_maxima,
                        a.existencia,
                        d.existencia as existencia_global,
                        d.existencia_minima as existencia_minima_global,
                        d.existencia_maxima as existencia_maxima_global,
                        d.costo_anterior,
                        d.costo,
						CASE WHEN b.porc_iva > 0 THEN ROUND(b.porc_iva,2) ELSE 0 END as iva_pdcto,
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
                        b.subclase_id,
						b.porc_iva
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
            //$filtro_orderby = " ORDER BY b.codigo_producto $orderby";
            $filtro_orderby = " ORDER BY b.descripcion $orderby,a.existencia DESC";
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
		
        if(!empty($molecula_bus))
        {
            $filtro_subclase = " AND b.subclase_id = '$molecula_bus' ";
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
    function GetInfoProductoLapsoActual($empresa_id, $centro_utilidad, $bodega, $codigo_producto,$LapsoInicial,$LapsoFinal,$tipo_movimiento,$tipo_doc_general_id)
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

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        //INFORMACION DEL PRODUCTO

        if(strlen($centro_utilidad) === 1){
            $centro_utilidad = $centro_utilidad.' ';
        }

        $sql = "
            SELECT
                    a.empresa_id,
                    a.centro_utilidad,
                    a.bodega,
                    b.codigo_producto,
                    fc_descripcion_producto(b.codigo_producto) as nombre,
                    b.descripcion,
                    b.descripcion_abreviada,
                    b.unidad_id,
                    c.descripcion as descripcion_unidad,
                    b.estado,
                    b.codigo_invima,
                    b.contenido_unidad_venta,
                    b.sw_control_fecha_vencimiento,
                    a.existencia_minima,
                    a.existencia_maxima,
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

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        //$dbconn->debug=true;
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
		
		if($LapsoInicial!="-")
            {
            list($a???o,$mes)=split('-', $LapsoInicial);
			$lapso_anterior = date('Ym',mktime(0,0,0, $mes-1,'1',$a???o));
			/*lapso = '" . date('Ym',mktime(0, 0, 0, date("m")-1, 1, date("Y"))) . "'*/
			$lapso = " lapso = '".$lapso_anterior."' ";
			}
			else
				{
				$lapso = " lapso = '-' ";
				}
			
        $sql = "SELECT existencia_final
                FROM inv_bodegas_movimiento_cierres_por_lapso
                WHERE
                    ".$lapso."
                    AND empresa_id = '$empresa_id'
                    AND centro_utilidad = '$centro_utilidad'
                    AND bodega = '$bodega'
                    AND codigo_producto = '$codigo_producto';
        ";
        
        $result = $dbconn->Execute($sql);
        //$dbconn->debug=true;
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($result->EOF)
        {
            $fila['existencia_inicial'] ="-";
        }
        else
        {
            list($fila['existencia_inicial'])= $result->FetchRow();
            $result->Close();
        }
        $ctl = new ClaseUtil();
         list( $anio, $mes) = split( '[/.-]', $LapsoFinal ); 
        //echo ($ctl->ObtenerDiasDelMes($mes,$anio));
        
        $NumDias= $ctl->ObtenerDiasDelMes($mes,$anio);
        //print_r($NumDias);
        /*
        * Se incluiran los lapsos Fecha Incial - Final y si no tiene, lapso Actual
        */
        //Para sacar el Listado de Movimientos del Producto
        if($LapsoInicial=="-" && $LapsoFinal=="-")
        {
        $Fecha_actual=date('Y-m');
        list( $anio, $mes) = split( '[/.-]', $Fecha_actual ); 
        $NumDias= $ctl->ObtenerDiasDelMes($mes,$anio);
        /*$filtro_exist = "AND a.fecha_registro <= '".date('Y-m')."-".$NumDias." 24:00:00'";
		 $filtro_exist1 = "AND b.fecha_registro <= '".date('Y-m')."-".$NumDias." 24:00:00'";*/
        }
        
			if($LapsoInicial!="-")
            {
            $filtro_existI = " AND a.fecha_registro >= '".$LapsoInicial."-01 00:00:00' ";
            $filtro_existI1 = " AND b.fecha_registro >= '".$LapsoInicial."-01 00:00:00' ";
            }
            
            if($LapsoFinal!="-")
            {
            $filtro_existF = " AND a.fecha_registro <= '".$LapsoFinal."-".$NumDias." 24:00:00' ";
            $filtro_existF1 = " AND b.fecha_registro <= '".$LapsoFinal."-".$NumDias." 24:00:00' ";
            }
        
        
        $sql = "

            SELECT
                tipo,
                SUM(cantidad) as cantidad,
                SUM(costo * cantidad) as costo_total
            FROM
            (
                (
                    SELECT
                        CASE WHEN d.inv_tipo_movimiento IN ('E','T')  THEN 'EGRESO'  ELSE 'INGRESO' END as tipo,
                        d.inv_tipo_movimiento as tipo_movimiento,
                        to_char(a.fecha_registro,'YYYY-MM-DD HH24:MI') as fecha,
                        a.fecha_registro,
                        a.prefijo,
                        a.numero,
                        b.cantidad,
                        round(b.total_costo/b.cantidad) as costo,
                        f.usuario,
                        f.nombre,
                        NULL as bodegas_doc_id,
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
						".$filtro_existI."
						".$filtro_existF."
              
                )
                UNION ALL
                (
                    SELECT
                        CASE WHEN d.cargo = 'IMD'  THEN 'EGRESO' WHEN d.cargo = 'DIMD' THEN 'INGRESO' ELSE '?' END as tipo,
                        CASE WHEN d.cargo = 'IMD'  THEN 'C' WHEN d.cargo = 'DIMD' THEN 'D' ELSE '?' END as tipo_movimiento,
                        to_char(b.fecha_registro,'YYYY-MM-DD HH24:MI') as fecha,
                        b.fecha_registro,
                        c.prefijo,
                        b.numeracion as numero,
                        a.cantidad,
                        a.total_costo as costo,
                        f.usuario,
                        f.nombre,
                        b.bodegas_doc_id,
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
						".$filtro_existI1."
						".$filtro_existF1."
                )
                UNION ALL
                (
                    SELECT
                        'INGRESO' as tipo,
                        d.inv_tipo_movimiento as tipo_movimiento,
                        to_char(a.fecha_registro,'YYYY-MM-DD HH24:MI') as fecha,
                        a.fecha_registro,
                        a.prefijo,
                        a.numero,
                        b.cantidad,
                        round(b.total_costo/b.cantidad) as costo,
                        f.usuario,
                        f.nombre,
                        NULL as bodegas_doc_id,
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
                        ".$filtro_existI."
						".$filtro_existF."
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
                        CASE WHEN c.tipo_movimiento IN ('E','T')  THEN 'EGRESO'  ELSE 'INGRESO' END as tipo,
                        c.tipo_movimiento,
                        to_char(b.fecha_registro,'YYYY-MM-DD HH24:MI') as fecha, 
                        b.fecha_registro,
                        c.prefijo, 
                        b.numeracion as numero,
                        a.cantidad,
                        a.total_costo as costo, 
                        f.usuario, 
                        f.nombre, 
                        b.bodegas_doc_id,
                        b.observacion
                        
                                FROM 
                                bodegas_documentos_d as a, 
                                bodegas_documentos as b, 
                                bodegas_doc_numeraciones as c, 
                                system_usuarios as f 
                                            WHERE 
                                            c.empresa_id = '$empresa_id' 
                                            AND c.centro_utilidad = '$centro_utilidad'
                                            AND c.bodega = '$bodega' 
                                            AND b.bodegas_doc_id = c.bodegas_doc_id 
                                            ".$filtro_existI1."
											".$filtro_existF1."
                                            AND a.bodegas_doc_id = b.bodegas_doc_id 
                                            AND a.numeracion = b.numeracion 
                                            AND a.codigo_producto = '$codigo_producto' 
                                            AND f.usuario_id = b.usuario_id
                                            AND a.consecutivo NOT IN
                                                                   (
                                                                   select 
                                                                   consecutivo
                                                                   from
                                                                   cuentas_detalle
                                                                   where
                                                                   empresa_id ='$empresa_id' 
                                                                   AND centro_utilidad = '$centro_utilidad'
																   AND bodega = '$bodega' 
																   AND consecutivo IS NOT NULL
																   AND a.consecutivo = consecutivo
                                                                    )
                )
                
            ) AS DATOS
            GROUP BY tipo;
        ";
        
         /*print_r($sql);*/
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        //$dbconn->debug=true;
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        while($lista = $result->FetchRow())
        {
            $TOTALES[$lista['tipo']] = $lista;
        }
        $result->Close();

        if(is_numeric($TOTALES['INGRESO']['cantidad']))
        {
            $fila['ingresos'] = $TOTALES['INGRESO']['cantidad'];
        }
        else
        {
            $fila['ingresos'] ="0";
        }

        if(is_numeric($TOTALES['EGRESO']['cantidad']))
        {
            $fila['egresos'] = $TOTALES['EGRESO']['cantidad'];
        }
        else
        {
            $fila['egresos'] ="0";
        }

        /* DESCUADRE*/
		/*$fila['descuadre'] = $fila['existencia'] - ($fila['existencia_inicial'] + $fila['ingresos'] - $fila['egresos']);*/
		$fila['descuadre'] = $fila['existencia'] - ($fila['existencia_inicial'] + $fila['ingresos'] - $fila['egresos']);
		/* 	FIN DESCUADRE		*/
        
        //list( $anio, $mes) = split( '[/.-]', $LapsoFinal ); 
        //echo ($ctl->ObtenerDiasDelMes($mes,$anio));
        //$NumDias= $ctl->ObtenerDiasDelMes($mes,$anio);
		/*
		* Se incluiran los lapsos Fecha Incial - Final y si no tiene, lapso Actual
		*/
        //Para sacar el Listado de Movimientos del Producto
         if($LapsoInicial=="-" && $LapsoFinal=="-")
        {
        $Fecha_actual=date('Y-m');
        list( $anio, $mes) = split( '[/.-]', $Fecha_actual ); 
        $NumDias= $ctl->ObtenerDiasDelMes($mes,$anio);
        $filtro = "AND a.fecha_registro <= '".date('Y-m')."-".$NumDias." 24:00:00'";
        $filtro1 = "AND b.fecha_registro <= '".date('Y-m')."-".$NumDias." 24:00:00'";
        }
        
        /*if($LapsoInicial=="-" && $LapsoFinal=="-")
        {
        $filtro = "AND a.fecha_registro >= '".date('Y-m')."-01 00:00:00'";
        $filtro1 = "AND b.fecha_registro >= '".date('Y-m')."-01 00:00:00'";
        }*/
            if($LapsoInicial!="-")
            {
            $filtro = " AND a.fecha_registro >= '".$LapsoInicial."-01 00:00:00' ";
            $filtro1 = " AND b.fecha_registro >= '".$LapsoInicial."-01 00:00:00' ";
            }
            
            if($LapsoFinal!="-")
            {
            $filtro .= " AND a.fecha_registro <= '".$LapsoFinal."-".$NumDias." 24:00:00' ";
            $filtro1 .= " AND b.fecha_registro <= '".$LapsoFinal."-".$NumDias." 24:00:00' ";
            }

        
        
        

        if($tipo_movimiento!="")
        {
        $filtro2  ="  AND d.inv_tipo_movimiento = '".$tipo_movimiento."' ";
        $filtro3  ="  AND c.tipo_movimiento = '".$tipo_movimiento."' ";
        }
        
        if($tipo_doc_general_id!="")
        {
        $filtro2 .="   AND d.tipo_doc_general_id = '".$tipo_doc_general_id."' ";
        //$filtro3 .="  AND c.v_tipo_movimiento = '".$tipo_movimiento."' ";
        }
        
		/*AND a.prefijo = invdes.prefijo
						AND a.numero = invdes.numero
						AND solic.solicitud_prod_a_bod_ppal_id = invdes.solicitud_prod_a_bod_ppal_id
						
						inv_bodegas_movimiento_despachos_farmacias invdes,
						solicitud_productos_a_bodega_principal solic*/
        $sql = "

            SELECT *
            FROM
            (
                (
                    SELECT
                        CASE WHEN d.inv_tipo_movimiento IN ('E','T')  THEN 'EGRESO'  ELSE 'INGRESO' END as tipo,
                        d.inv_tipo_movimiento as tipo_movimiento,
                        to_char(a.fecha_registro,'YYYY-MM-DD HH24:MI') as fecha,
                        a.fecha_registro,
                        a.prefijo,
                        a.numero,
                        b.cantidad,
                        b.lote,
                        b.fecha_vencimiento,
                        round(b.total_costo/b.cantidad) as costo,
                        f.usuario,
                        f.nombre,
                        NULL as bodegas_doc_id,
                        a.observacion,
						solic.farmacia_id ,
						solic.centro_utilidad,
						solic.bodega

                    FROM
                        inv_bodegas_documentos as e INNER JOIN inv_bodegas_movimiento as a
						ON   a.documento_id = e.documento_id
                        AND a.empresa_id = e.empresa_id
                        AND a.centro_utilidad = e.centro_utilidad
                        AND a.bodega = e.bodega
                        
						INNER JOIN inv_bodegas_movimiento_d as b ON
						 b.empresa_id = a.empresa_id
                        AND b.prefijo = a.prefijo
                        AND b.numero = a.numero
						
                        
                        INNER JOIN documentos as c ON 
						c.documento_id = a.documento_id
                        AND c.empresa_id = a.empresa_id
						
                        INNER JOIN tipos_doc_generales as d ON
						 d.tipo_doc_general_id = c.tipo_doc_general_id
						
                        INNER JOIN system_usuarios as f ON
						 f.usuario_id = a.usuario_id
						
						
						LEFT JOIN inv_bodegas_movimiento_despachos_farmacias invdes ON a.prefijo = invdes.prefijo
						AND a.numero = invdes.numero
						LEFT JOIN solicitud_productos_a_bodega_principal solic ON solic.solicitud_prod_a_bod_ppal_id = invdes.solicitud_prod_a_bod_ppal_id
						
						
                    WHERE
                        e.empresa_id = '$empresa_id'
                        AND e.centro_utilidad = '$centro_utilidad'
                        AND e.bodega = '$bodega'                    
                        ".$filtro."
                        AND b.codigo_producto = '$codigo_producto'                 
                        ".$filtro2."
                )
                UNION ALL
                (
                    SELECT
                        CASE WHEN d.cargo = 'IMD'  THEN 'EGRESO' WHEN d.cargo = 'DIMD' THEN 'INGRESO' ELSE '?' END as tipo,
                        CASE WHEN d.cargo = 'IMD'  THEN 'C' WHEN d.cargo = 'DIMD' THEN 'D' ELSE '?' END as tipo_movimiento,
                        to_char(b.fecha_registro,'YYYY-MM-DD HH24:MI') as fecha,
                        b.fecha_registro,
                        c.prefijo,
                        b.numeracion as numero,
                        a.cantidad,
                        a.lote,
                        a.fecha_vencimiento,
                        a.total_costo as costo,
                        f.usuario,
                        f.nombre,
                        b.bodegas_doc_id,
                        'Cuenta No.' || d.numerodecuenta as observacion,
						null as farmacia_id ,
						null as centro_utilidad,
						null as bodega

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
                        ".$filtro1."
                        AND a.bodegas_doc_id = b.bodegas_doc_id
                        AND a.numeracion = b.numeracion
                        AND a.codigo_producto = '$codigo_producto'
                        AND d.consecutivo = a.consecutivo
                        AND f.usuario_id = b.usuario_id
                        ".$filtro3."
                )
                UNION ALL
                (
                    SELECT
                        'INGRESO' as tipo,
                        d.inv_tipo_movimiento as tipo_movimiento,
                        to_char(a.fecha_registro,'YYYY-MM-DD HH24:MI') as fecha,
                        a.fecha_registro,
                        a.prefijo,
                        a.numero,
                        b.cantidad,
                        b.lote,
                        b.fecha_vencimiento,
                        round(b.total_costo/b.cantidad) as costo,
                        f.usuario,
                        f.nombre,
                        NULL as bodegas_doc_id,
                        'BODEGA ORIGEN ['||t.centro_utilidad_destino ||']['|| t.bodega_destino ||'] ' || a.observacion as observacion,
						null as farmacia_id ,
						null as centro_utilidad,
						null as bodega

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
                        ".$filtro."
                        AND b.empresa_id = a.empresa_id
                        AND b.prefijo = a.prefijo
                        AND b.numero = a.numero
                        AND b.codigo_producto = '$codigo_producto'
                        AND c.documento_id = a.documento_id
                        AND c.empresa_id = a.empresa_id
                        AND d.tipo_doc_general_id = c.tipo_doc_general_id
                        AND f.usuario_id = a.usuario_id
                        ".$filtro2."

                )
                UNION ALL
                (
                    SELECT 
                        CASE WHEN c.tipo_movimiento IN ('E','T')  THEN 'EGRESO'  ELSE 'INGRESO' END as tipo,
                        c.tipo_movimiento,
                        to_char(b.fecha_registro,'YYYY-MM-DD HH24:MI') as fecha, 
                        b.fecha_registro,
                        c.prefijo, 
                        b.numeracion as numero,
                        a.cantidad,
                        a.lote,
                        a.fecha_vencimiento,
                        a.total_costo as costo, 
                        f.usuario, 
                        f.nombre, 
                        b.bodegas_doc_id,
                        b.observacion,
						null as farmacia_id ,
						null as centro_utilidad,
						null as bodega
                        
                                FROM 
                                bodegas_documentos_d as a, 
                                bodegas_documentos as b, 
                                bodegas_doc_numeraciones as c, 
                                system_usuarios as f 
                                            WHERE 
                                            c.empresa_id = '$empresa_id' 
                                            AND c.centro_utilidad = '$centro_utilidad'
                                            AND c.bodega = '$bodega' 
                                            AND b.bodegas_doc_id = c.bodegas_doc_id 
                                            ".$filtro1."
                                            AND a.bodegas_doc_id = b.bodegas_doc_id 
                                            AND a.numeracion = b.numeracion 
                                            AND a.codigo_producto = '$codigo_producto' 
                                            AND  f.usuario_id = b.usuario_id
                                            ".$filtro3."
                                            AND a.consecutivo NOT IN
                                                                   (
                                                                   select 
                                                                   consecutivo
                                                                   from
                                                                   cuentas_detalle
                                                                   where
                                                                   empresa_id ='$empresa_id' 
                                                                   AND centro_utilidad = '$centro_utilidad'
																   AND bodega = '$bodega' 
																   AND consecutivo IS NOT NULL
																   AND a.consecutivo = consecutivo
                                                                    )

                )
            ) AS DATOS 
				
            ORDER BY DATOS.fecha;
        ";
		
		/*
		*LEFT JOIN inv_bodegas_movimiento_despachos_farmacias invdes ON DATOS.prefijo = invdes.prefijo
				AND DATOS.numero = invdes.numero
				LEFT JOIN solicitud_productos_a_bodega_principal solic ON solic.solicitud_prod_a_bod_ppal_id = invdes.solicitud_prod_a_bod_ppal_id
				AND (solic.farmacia_id = 'FD' AND solic.centro_utilidad ='18' AND solic.bodega = '18')
		*/
       
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        //$dbconn->debug=true;
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        $retorno = array();

        while($lista = $result->FetchRow())
        {
            $fila['KARDEX'][] = $lista;
        }
        $result->Close();

        return $fila;
    }


    /**
    * Obtener los movimientos de un Producto en un periodo de tiempo.
    *
    * @param string  $empresa_id
    * @param string  $centro_utilidad Identificador de la bodega
    * @param string  $bodega Identificador de la bodega
    * @param string  $codigo_producto
    * @return boolean
    * @access public
    */
    function GetMovimientoProductoPorperiodo($empresa_id, $centro_utilidad, $bodega, $codigo_producto, $fecha_inicial, $fecha_final)
    {

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        if($fecha_inicial=="--")
        {
        $filtro .= "   AND a.fecha_registro::date >='".date('Y-m-d')."' ";
        $filtro .= "   AND a.fecha_registro::date <= '".date('Y-m-d')."' ";     
        }
        
        if($fecha_final=="--")
        {
        
        $filtro1 .= "   AND b.fecha_registro::date >= '".date('Y-m-d')."'    ";
        $filtro1 .= "   AND b.fecha_registro::date <= '".date('Y-m-d')."'       ";     
        }
        
            if($fecha_inicial!="--")
            {
            $filtro  .= " AND a.fecha_registro::date >='".$fecha_inicial."' ";
            $filtro1 .= " AND b.fecha_registro::date >= '".date('Y-m-d')."' ";
            }
            
            if($fecha_final!="--")
            {
            $filtro  .= " AND a.fecha_registro::date <= '".$fecha_final."' ";
            $filtro1 .= " AND b.fecha_registro::date <= '".$fecha_final."'  ";
            }
        
        $sql = "
                SELECT
                    SUM(x.ingresos) as ingresos,
                    SUM(x.egresos) as egresos,
                    (SUM(x.ingresos) - SUM(x.egresos)) as movimiento_total

                FROM
                (
                    (
                        SELECT
                            CASE WHEN d.inv_tipo_movimiento IN ('E','T')  THEN 0  ELSE b.cantidad END as ingresos,
                            CASE WHEN d.inv_tipo_movimiento IN ('E','T')  THEN b.cantidad  ELSE 0 END as egresos
                        FROM
                            inv_bodegas_documentos as e,
                            inv_bodegas_movimiento as a,
                            inv_bodegas_movimiento_d as b,
                            documentos as c,
                            tipos_doc_generales as d

                        WHERE
                            e.empresa_id = '$empresa_id'
                            AND e.centro_utilidad = '$centro_utilidad'
                            AND e.bodega = '$bodega'
                            AND a.documento_id = e.documento_id
                            AND a.empresa_id = e.empresa_id
                            AND a.centro_utilidad = e.centro_utilidad
                            AND a.bodega = e.bodega
                            ".$filtro."
                            AND b.empresa_id = a.empresa_id
                            AND b.prefijo = a.prefijo
                            AND b.numero = a.numero
                            AND b.codigo_producto = '$codigo_producto'
                            AND c.documento_id = a.documento_id
                            AND c.empresa_id = a.empresa_id
                            AND d.tipo_doc_general_id = c.tipo_doc_general_id

                    )
                    UNION ALL
                    (
                        SELECT
                            CASE WHEN d.cargo = 'IMD'  THEN 0  ELSE a.cantidad END as ingresos,
                            CASE WHEN d.cargo = 'IMD'  THEN a.cantidad  ELSE 0 END as egresos
                        FROM
                            bodegas_documentos_d as a,
                            bodegas_documentos as b,
                            bodegas_doc_numeraciones as c,
                            cuentas_detalle as d

                        WHERE
                            c.empresa_id = '$empresa_id'
                            AND c.centro_utilidad = '$centro_utilidad'
                            AND c.bodega = '$bodega'
                            AND b.bodegas_doc_id = c.bodegas_doc_id
                            ".$filtro1."
                            AND a.bodegas_doc_id = b.bodegas_doc_id
                            AND a.numeracion = b.numeracion
                            AND a.codigo_producto = '$codigo_producto'
                            AND d.consecutivo = a.consecutivo
                    )
                    UNION ALL
                    (
                        SELECT
                            b.cantidad as ingresos,
                            0 as egresos
                        FROM
                            inv_bodegas_movimiento_traslados as t,
                            inv_bodegas_movimiento as a,
                            inv_bodegas_movimiento_d as b

                        WHERE
                            t.empresa_id = '$empresa_id'
                            AND t.centro_utilidad_destino = '$centro_utilidad'
                            AND t.bodega_destino = '$bodega'
                            AND a.empresa_id = t.empresa_id
                            ".$filtro."
                            AND a.prefijo = t.prefijo
                            AND a.numero = t.numero
                            AND b.empresa_id = a.empresa_id
                            AND b.prefijo = a.prefijo
                            AND b.numero = a.numero
                            AND b.codigo_producto = '$codigo_producto'
                    )
                ) as X;
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

        if($result->EOF)
        {
            $fila['ingresos']=0;
            $fila['egresos']=0;
            $fila['movimiento_total']=0;
        }
        else
        {
            $fila = $result->FetchRow();
        }

        $result->Close();

        return $fila;
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
    function GetInfoProductoPorLapso($empresa_id, $centro_utilidad, $bodega, $codigo_producto, $limit=null, $offset=null, $count=null, $lapso=null, $dia_inicial=null, $dia_final=null,$tipo,$tipo_movimiento, $fecha_inicio_lapso,$fecha_final_lapso)
    {
        if(empty($empresa_id) || empty($centro_utilidad) || empty($bodega) )
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "Parametros de bodega nulos.";
            return false;
        }

        //print_r($fecha_inicio_lapso);
        
        if(empty($codigo_producto))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "El parametro [codigo_producto] es nulo.";
            return false;
        }

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $filtro_limit   = '';
        $fecha_inicial  = '';
        $fecha_final    = '';

        if(empty($lapso))
        {
            $ano = date('Y');
            $mes = date('m');
        }
        else
        {
            $ano = substr($lapso, 0, 4);
            $mes = substr($lapso, 4, 2);
        }
//print_r("Joder".$fecha_final_lapso);
        /*if(is_numeric($dia_inicial))
        {
            $fecha_inicial = date('Y-m-d',mktime(0, 0, 0, $mes, $dia_inicial, $ano));

            if(is_numeric($dia_final))
            {
                $fecha_final = date('Y-m-d',mktime(0, 0, 0, $mes, $dia_final + 1, $ano));
            }
            else
            {
                $fecha_final = date('Y-m-d',mktime(0, 0, 0, $mes + 1, 1, $ano));
            }
        }
        else
        {
            $fecha_inicial = date('Y-m-d',mktime(0, 0, 0, $mes, 1, $ano));
            $fecha_final   = date('Y-m-d',mktime(0, 0, 0, $mes + 1, 1, $ano));
        }*/
        list( $dia1, $mes1, $ano1 ) = split( '[/.-]', $fecha_inicio_lapso ); 
        list( $dia2, $mes2, $ano2 ) = split( '[/.-]', $fecha_final_lapso ); 
        
        
        
        $fecha_inicial=$ano1."-".$mes1."-".$dia1;
        $fecha_final=$ano2."-".$mes2."-".$dia2;
        
        
        if($fecha_inicial=="--")
        {
        $filtro .= "   AND a.fecha_registro::date >='".date('Y-m-d')."' ";
        $filtro .= "   AND a.fecha_registro::date <= '".date('Y-m-d')."' ";     
        }
        
        if($fecha_final=="--")
        {
        
        $filtro1 .= "   AND b.fecha_registro::date >= '".date('Y-m-d')."'    ";
        $filtro1 .= "   AND b.fecha_registro::date <= '".date('Y-m-d')."'       ";     
        }
        
            if($fecha_inicial!="--")
            {
            $filtro  .= " AND a.fecha_registro::date >='".$fecha_inicial."' ";
            $filtro1 .= " AND b.fecha_registro::date >= '".date('Y-m-d')."' ";
            }
            
            if($fecha_final!="--")
            {
            $filtro  .= " AND a.fecha_registro::date <= '".$fecha_final."' ";
            $filtro1 .= " AND b.fecha_registro::date <= '".$fecha_final."'  ";
            }
        
                         
        if(empty($count))
        {
            //---------------------------------------------------------------------------------------------
            //-- DATOS DEL PRODUCTO---- -------------------------------------------------------------------
            //---------------------------------------------------------------------------------------------

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
                            a.existencia_minima,
                            a.existencia_maxima,
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

            // -- CALCULO DE INGRESOS Y EGRESOS DEL PERIODO
            $mov = $this->GetMovimientoProductoPorperiodo($empresa_id, $centro_utilidad, $bodega, $codigo_producto, $fecha_inicial, $fecha_final);
            $fila['ingresos'] = $mov['ingresos'];
            $fila['egresos']  = $mov['egresos'];
            $movimiento_total = $mov['movimiento_total'];


            //---------------------------------------------------------------------------------------------
            //-- CALCULAR SALDO INICIAL -------------------------------------------------------------------
            //---------------------------------------------------------------------------------------------

            // -- SI EL PERIODO CORRESPONDE AL MES ACTUAL
            if(($ano == date('Y')) && ($mes == date('m')))
            {
                $sql = "SELECT existencia_final, NULL,0
                        FROM inv_bodegas_movimiento_cierres_por_lapso
                        WHERE
                            lapso = '" . date('Ym',mktime(0, 0, 0, date("m")-1, 1, date("Y"))) . "'
                            AND empresa_id = '$empresa_id'
                            AND centro_utilidad = '$centro_utilidad'
                            AND bodega = '$bodega'
                            AND codigo_producto = '$codigo_producto';
                ";
            }
            else // -- SI EL PERIODO CORRESPONDE A OTRO PERIODO
            {
                $sql = "SELECT existencia_inicial,existencia_final,diferencia as descuadre
                        FROM inv_bodegas_movimiento_cierres_por_lapso
                        WHERE
                            lapso = '" . date('Ym',mktime(0, 0, 0, $mes, 1, $ano)) . "'
                            AND empresa_id = '$empresa_id'
                            AND centro_utilidad = '$centro_utilidad'
                            AND bodega = '$bodega'
                            AND codigo_producto = '$codigo_producto';
                ";
                //print_r($sql);
            }
            
            $result = $dbconn->Execute($sql);

            if($dbconn->ErrorNo() != 0)
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                return false;
            }

            // -- SI NO EXISTE EL CIERRE DEL PERIODO
            if($result->EOF)
            {
                $fila['existencia_inicial'] = "?";
                $fila['existencia_final']   = "?";
                $fila['descuadre']          = "?";
            }
            else
            {
                // -- ESTA ES LA EXISTENCIA INICIAL DEL PERIODO
                list($existencia_inicial, $existencia_final,$descuadre)= $result->FetchRow();
                $result->Close();

                // SI EL DIA INICIAL NO ES EL PRIMER DIA DEL PERIODO
                if(is_numeric($dia_inicial) && ($dia_inicial > 1))
                {
                    $mov = $this->GetMovimientoProductoPorperiodo($empresa_id, $centro_utilidad, $bodega, $codigo_producto, $fecha_inicial, $fecha_final);
                    $fila['existencia_inicial'] = $existencia_inicial + $mov['movimiento_total'];
                }
                else
                {
                    $fila['existencia_inicial'] = $existencia_inicial;
                }


                ($descuadre < 0)? $fila['descuadre'] = $descuadre*(-1): $fila['descuadre'] = $descuadre;
                    
                if(!$existencia_final)
                {//---------------------------------------------------------------------------------------------
                    //-- CALCULAR SALDO FINAL ---------------------------------------------------------------------
                    //---------------------------------------------------------------------------------------------

                    $fila['existencia_final'] = $fila['existencia_inicial'] + $movimiento_total + $fila['descuadre'];
                }
                else
                {
                    $fila['existencia_final'] = $existencia_final;
                }
                //---------------------------------------------------------------------------------------------
                //-- CALCULAR DESCUADRES ----------------------------------------------------------------------
                //---------------------------------------------------------------------------------------------
                
                //$fila['descuadre'] = "?";

            }// FIN DEL CALCULO DEL SALDO INICIAL

            $select = " * ";
            $orderBy = " ORDER BY DATOS.fecha ";

            if(is_numeric($limit))
            {
                if(!is_numeric($offset))
                {
                    $offset = '0';
                }
                $filtro_limit = " LIMIT $limit OFFSET $offset ";
            }
        } //FIN DE LA CONSULTA DISTINTA A COUNT(*)
        else
        {
            $select  = " COUNT(*) as cantidad ";
            $orderBy = "";
        }

        $sql = "

            SELECT $select
            FROM
            (
                (
                    SELECT
                        CASE WHEN d.inv_tipo_movimiento IN ('E','T')  THEN 'EGRESO'  ELSE 'INGRESO' END as tipo,
                        d.inv_tipo_movimiento as tipo_movimiento,
                        to_char(a.fecha_registro,'YYYY-MM-DD HH24:MI') as fecha,
                        a.fecha_registro,
                        a.prefijo,
                        a.numero,
                        b.cantidad,
                        round(b.total_costo/b.cantidad) as costo,
                        f.usuario,
                        f.nombre,
                        NULL as bodegas_doc_id,
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
                        ".$filtro."
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
                        b.fecha_registro,
                        c.prefijo,
                        b.numeracion as numero,
                        a.cantidad,
                        a.total_costo as costo,
                        f.usuario,
                        f.nombre,
                        b.bodegas_doc_id,
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
                        ".$filtro1."
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
                        a.fecha_registro,
                        a.prefijo,
                        a.numero,
                        b.cantidad,
                        round(b.total_costo/b.cantidad) as costo,
                        f.usuario,
                        f.nombre,
                        NULL as bodegas_doc_id,
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
                        ".$filtro."
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
                        CASE WHEN c.tipo_movimiento IN ('E','T')  THEN 'EGRESO'  ELSE 'INGRESO' END as tipo,
                        c.tipo_movimiento,
                        to_char(b.fecha_registro,'YYYY-MM-DD HH24:MI') as fecha, 
                        b.fecha_registro,
                        c.prefijo, 
                        b.numeracion as numero,
                        a.cantidad,
                        a.total_costo as costo, 
                        f.usuario, 
                        f.nombre, 
                        b.bodegas_doc_id,
                        b.observacion
                        
                                FROM 
                                bodegas_documentos_d as a, 
                                bodegas_documentos as b, 
                                bodegas_doc_numeraciones as c, 
                                system_usuarios as f 
                                            WHERE 
                                            c.empresa_id = '$empresa_id' 
                                            AND c.centro_utilidad = '$centro_utilidad'
                                            AND c.bodega = '$bodega' 
                                            AND b.bodegas_doc_id = c.bodegas_doc_id 
                                            ".$filtro1."
                                            AND a.bodegas_doc_id = b.bodegas_doc_id 
                                            AND a.numeracion = b.numeracion 
                                            AND a.codigo_producto = '$codigo_producto' 
                                            AND  f.usuario_id = b.usuario_id
                                            ".$filtro3."
                                            AND a.consecutivo NOT IN
                                                                   (
                                                                   select 
                                                                   consecutivo
                                                                   from
                                                                   cuentas_detalle
                                                                   where
                                                                   empresa_id ='$empresa_id' 
                                                                   AND centro_utilidad = '$centro_utilidad'
                                                                    )

                )
                
            ) AS DATOS
            $orderBy
            $filtro_limit;
        ";
       //print_r($sql);
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

            while($lista = $result->FetchRow())
            {
                $fila['KARDEX'][] = $lista;
            }
            $result->Close();
        }
        else
        {
            $fila = $result->FetchRow();
            $retorno = $fila['cantidad'];
            return $retorno;
        }


        //-------------------------------------------------------------------------------------
        // HISTORICO POR LAPSO -----------------------------------------------------------------
        //---------------------------------------------------------------------------------------
        $sql = "
                SELECT
                a.lapso,
                SUM(b.cantidad_total) as unidades

                FROM
                inv_bodegas_movimiento_costo_por_lapso as a,
                inv_bodegas_movimiento_costo_por_lapso_d as b
                WHERE
                a.empresa_id = '$empresa_id'
                AND b.empresa_id = a.empresa_id
                AND b.prefijo = a.prefijo
                AND b.numero = a.numero
                AND b.codigo_producto = '$codigo_producto'
                GROUP BY a.lapso
                ORDER BY a.lapso DESC
                LIMIT 12 OFFSET 0;
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
            $fila['HISTORICO'][] = $lista;
        }
        $result->Close();


        //---------------------------------------------------------------------------------------
        // CALCULO DE LA EXISTENCIA EN TODAS LAS BODEGAS DE LA EMPRESA --------------------------
        //---------------------------------------------------------------------------------------
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

        return $fila;
    }


    /**
    * Obtener los registros de entradas y salidas de un Producto en todas las bodegas, con su respectivo costeo.
    *
    * @param string  $empresa_id
    * @param string  $codigo_producto
    * @return boolean
    * @access public
    */
    function GetMovimientoCostosProductoPorLapso($empresa_id, $codigo_producto, $lapso=null, $dia_inicial=null, $dia_final=null)
    {
        if(empty($empresa_id) || empty($codigo_producto))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "Parametros nulos.";
            return false;
        }

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $fecha_inicial  = '';
        $fecha_final    = '';

        if(empty($lapso))
        {
            $ano = date('Y');
            $mes = date('m');
        }
        else
        {
            $ano = substr($lapso, 0, 4);
            $mes = substr($lapso, 4, 2);
        }

        if(is_numeric($dia_inicial))
        {
            $fecha_inicial = date('Y-m-d',mktime(0, 0, 0, $mes, $dia_inicial, $ano));

            if(is_numeric($dia_final))
            {
                $fecha_final = date('Y-m-d',mktime(0, 0, 0, $mes, $dia_final + 1, $ano));
            }
            else
            {
                $fecha_final = date('Y-m-d',mktime(0, 0, 0, $mes + 1, 1, $ano));
            }
        }
        else
        {
            $fecha_inicial = date('Y-m-d',mktime(0, 0, 0, $mes, 1, $ano));
            $fecha_final   = date('Y-m-d',mktime(0, 0, 0, $mes + 1, 1, $ano));
        }


        //---------------------------------------------------------------------------------------------
        //-- DATOS DEL PRODUCTO---- -------------------------------------------------------------------
        //---------------------------------------------------------------------------------------------

        $sql = "
                SELECT
                        a.empresa_id,
                        b.codigo_producto,
                        b.descripcion,
                        b.descripcion_abreviada,
                        b.unidad_id,
                        c.descripcion as descripcion_unidad,
                        b.estado,
                        b.codigo_invima,
                        b.contenido_unidad_venta,
                        b.sw_control_fecha_vencimiento,
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
                        a.nivel_autorizacion_id,
                        b.grupo_id,
                        b.clase_id,
                        b.subclase_id

                FROM
                    inventarios as a,
                    inventarios_productos as b,
                    unidades as c

                WHERE
                    a.empresa_id = '$empresa_id'
                    AND a.codigo_producto = '$codigo_producto'
                    AND b.codigo_producto = a.codigo_producto
                    AND c.unidad_id = b.unidad_id
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

        if($result->EOF)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "EL PRODUCTO CONSULTADO NO EXISTE.";
            return false;
        }

        $fila = $result->FetchRow();
        $result->Close();



        //---------------------------------------------------------------------------------------------
        //-- DATOS DEL MOVIMIENTO EN EL PERIODO -------------------------------------------------------
        //---------------------------------------------------------------------------------------------

        $sql = "
                SELECT
                    SUM(x.ingresos) as ingresos,
                    SUM(x.egresos) as egresos,
                    (SUM(x.ingresos) - SUM(x.egresos)) as movimiento_total

                FROM
                (
                    (
                        SELECT
                            CASE WHEN d.inv_tipo_movimiento IN ('E','T')  THEN 0  ELSE b.cantidad END as ingresos,
                            CASE WHEN d.inv_tipo_movimiento IN ('E','T')  THEN b.cantidad  ELSE 0 END as egresos
                        FROM
                            inv_bodegas_documentos as e,
                            inv_bodegas_movimiento as a,
                            inv_bodegas_movimiento_d as b,
                            documentos as c,
                            tipos_doc_generales as d

                        WHERE
                            e.empresa_id = '$empresa_id'
                            AND e.bodega != 'FF'
                            AND a.documento_id = e.documento_id
                            AND a.empresa_id = e.empresa_id
                            AND a.centro_utilidad = e.centro_utilidad
                            AND a.bodega = e.bodega
                            AND a.fecha_registro::date >= '$fecha_inicial'
                            AND a.fecha_registro::date < '$fecha_final'
                            AND b.empresa_id = a.empresa_id
                            AND b.prefijo = a.prefijo
                            AND b.numero = a.numero
                            AND b.codigo_producto = '$codigo_producto'
                            AND c.documento_id = a.documento_id
                            AND c.empresa_id = a.empresa_id
                            AND d.tipo_doc_general_id = c.tipo_doc_general_id

                    )
                    UNION ALL
                    (
                        SELECT
                            CASE WHEN d.cargo = 'IMD'  THEN 0  ELSE a.cantidad END as ingresos,
                            CASE WHEN d.cargo = 'IMD'  THEN a.cantidad  ELSE 0 END as egresos
                        FROM
                            bodegas_documentos_d as a,
                            bodegas_documentos as b,
                            bodegas_doc_numeraciones as c,
                            cuentas_detalle as d

                        WHERE
                            c.empresa_id = '$empresa_id'
                            AND c.bodega != 'FF'
                            AND b.bodegas_doc_id = c.bodegas_doc_id
                            AND b.fecha_registro::date >= '$fecha_inicial'
                            AND b.fecha_registro::date < '$fecha_final'
                            AND a.bodegas_doc_id = b.bodegas_doc_id
                            AND a.numeracion = b.numeracion
                            AND a.codigo_producto = '$codigo_producto'
                            AND d.consecutivo = a.consecutivo
                    )
                    UNION ALL
                    (
                        SELECT
                            b.cantidad as ingresos,
                            0 as egresos
                        FROM
                            inv_bodegas_movimiento_traslados as t,
                            inv_bodegas_movimiento as a,
                            inv_bodegas_movimiento_d as b

                        WHERE
                            t.empresa_id = '$empresa_id'
                            AND t.bodega_destino != 'FF'
                            AND a.empresa_id = t.empresa_id
                            AND a.fecha_registro::date >= '$fecha_inicial'
                            AND a.fecha_registro::date < '$fecha_final'
                            AND a.prefijo = t.prefijo
                            AND a.numero = t.numero
                            AND b.empresa_id = a.empresa_id
                            AND b.prefijo = a.prefijo
                            AND b.numero = a.numero
                            AND b.codigo_producto = '$codigo_producto'
                    )
                ) as X;
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

        if($result->EOF)
        {
            $mov['ingresos']=0;
            $mov['egresos']=0;
            $mov['movimiento_total']=0;
        }
        else
        {
            $mov = $result->FetchRow();
        }
        $result->Close();


        $fila['ingresos'] = $mov['ingresos'];
        $fila['egresos']  = $mov['egresos'];
        $movimiento_total = $mov['movimiento_total'];

        //---------------------------------------------------------------------------------------------
        //-- CALCULAR SALDO INICIAL -------------------------------------------------------------------
        //---------------------------------------------------------------------------------------------

        // -- SI EL PERIODO CORRESPONDE AL MES ACTUAL
        if(($ano == date('Y')) && ($mes == date('m')))
        {
            $sql = "SELECT
                        SUM(existencia_final) as existencia_inicial,
                        NULL as existencia_final
                    FROM inv_bodegas_movimiento_cierres_por_lapso
                    WHERE
                        lapso = '" . date('Ym',mktime(0, 0, 0, date("m")-1, 1, date("Y"))) . "'
                        AND empresa_id = '$empresa_id'
                        AND bodega != 'FF'
                        AND codigo_producto = '$codigo_producto';
            ";
        }
        else // -- SI EL PERIODO CORRESPONDE A OTRO PERIODO
        {
            $sql = "SELECT
                        SUM(existencia_inicial) as existencia_inicial,
                        SUM(existencia_final) as existencia_final
                        
                    FROM inv_bodegas_movimiento_cierres_por_lapso
                    WHERE
                        lapso = '" . date('Ym',mktime(0, 0, 0, $mes, 1, $ano)) . "'
                        AND empresa_id = '$empresa_id'
                        AND bodega != 'FF'
                        AND codigo_producto = '$codigo_producto';
            ";
        }

        $result = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        // -- SI NO EXISTE EL CIERRE DEL PERIODO
        if($result->EOF)
        {
            $fila['existencia_inicial'] = "?";
            $fila['existencia_final']   = "?";
        }
        else
        {
            // -- ESTA ES LA EXISTENCIA INICIAL DEL PERIODO
            list($existencia_inicial, $existencia_final)= $result->FetchRow();
            $result->Close();

            // SI EL DIA INICIAL NO ES EL PRIMER DIA DEL PERIODO
            if(is_numeric($dia_inicial) && ($dia_inicial > 1))
            {
                $mov = $this->GetMovimientoProductoPorperiodo($empresa_id, $centro_utilidad, $bodega, $codigo_producto, $fecha_inicial, $fecha_final);
                $fila['existencia_inicial'] = $existencia_inicial + $mov['movimiento_total'];
            }
            else
            {
                $fila['existencia_inicial'] = $existencia_inicial;
            }

            //---------------------------------------------------------------------------------------------
            //-- CALCULAR SALDO FINAL ---------------------------------------------------------------------
            //---------------------------------------------------------------------------------------------

            $fila['existencia_final'] = $fila['existencia_inicial'] + $movimiento_total ;

        }// FIN DEL CALCULO DEL SALDO INICIAL


        //---------------------------------------------------------------------------------------------
        //-- REGISTROS --------------------------------------------------------------------------------
        //---------------------------------------------------------------------------------------------

        $sql = "

            SELECT *
            FROM
            (
                (
                    SELECT
                        e.centro_utilidad,
                        e.bodega,
                        CASE WHEN d.inv_tipo_movimiento IN ('E','T')  THEN 'EGRESO'  ELSE 'INGRESO' END as tipo,
                        d.inv_tipo_movimiento as tipo_movimiento,
                        to_char(a.fecha_registro,'YYYY-MM-DD HH24:MI') as fecha,
                        a.fecha_registro,
                        a.prefijo,
                        a.numero,
                        b.cantidad,
                        round(b.total_costo/b.cantidad) as costo,
                        f.usuario,
                        f.nombre,
                        NULL as bodegas_doc_id,
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
                        AND e.bodega != 'FF'
                        AND a.documento_id = e.documento_id
                        AND a.empresa_id = e.empresa_id
                        AND a.centro_utilidad = e.centro_utilidad
                        AND a.bodega = e.bodega
                        AND a.fecha_registro::date >= '$fecha_inicial'
                        AND a.fecha_registro::date < '$fecha_final'
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
                        c.centro_utilidad,
                        c.bodega,
                        CASE WHEN d.cargo = 'IMD'  THEN 'EGRESO' WHEN d.cargo = 'DIMD' THEN 'INGRESO' ELSE '?' END as tipo,
                        CASE WHEN d.cargo = 'IMD'  THEN 'C' WHEN d.cargo = 'DIMD' THEN 'D' ELSE '?' END as tipo_movimiento,
                        to_char(b.fecha_registro,'YYYY-MM-DD HH24:MI') as fecha,
                        b.fecha_registro,
                        c.prefijo,
                        b.numeracion as numero,
                        a.cantidad,
                        a.total_costo as costo,
                        f.usuario,
                        f.nombre,
                        b.bodegas_doc_id,
                        'Cuenta No.' || d.numerodecuenta as observacion

                    FROM
                        bodegas_documentos_d as a,
                        bodegas_documentos as b,
                        bodegas_doc_numeraciones as c,
                        cuentas_detalle as d,
                        system_usuarios as f

                    WHERE
                        c.empresa_id = '$empresa_id'
                        AND c.bodega != 'FF'
                        AND b.bodegas_doc_id = c.bodegas_doc_id
                        AND b.fecha_registro::date >= '$fecha_inicial'
                        AND b.fecha_registro::date < '$fecha_final'
                        AND a.bodegas_doc_id = b.bodegas_doc_id
                        AND a.numeracion = b.numeracion
                        AND a.codigo_producto = '$codigo_producto'
                        AND d.consecutivo = a.consecutivo
                        AND f.usuario_id = b.usuario_id
                )
                UNION ALL
                (
                    SELECT
                        t.centro_utilidad_destino as centro_utilidad,
                        t.bodega_destino as bodega,
                        'INGRESO' as tipo,
                        d.inv_tipo_movimiento as tipo_movimiento,
                        to_char(a.fecha_registro,'YYYY-MM-DD HH24:MI') as fecha,
                        a.fecha_registro,
                        a.prefijo,
                        a.numero,
                        b.cantidad,
                        round(b.total_costo/b.cantidad) as costo,
                        f.usuario,
                        f.nombre,
                        NULL as bodegas_doc_id,
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
                        AND t.bodega_destino != 'FF'
                        AND a.empresa_id = t.empresa_id
                        AND a.prefijo = t.prefijo
                        AND a.numero = t.numero
                        AND a.fecha_registro::date >= '$fecha_inicial'
                        AND a.fecha_registro::date < '$fecha_final'
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
            ORDER BY DATOS.fecha;
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


        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        $retorno = array();

        while($lista = $result->FetchRow())
        {
            $fila['KARDEX'][] = $lista;
        }

        $result->Close();

        return $fila;
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
    function ObtenerCantidadInicial($empresa_id, $centro_utilidad, $bodega, $codigo_producto,$LapsoInicial)
    {
        
       
        $ctl = new ClaseUtil();
        list( $anio, $mes) = split( '[/.-]', $LapsoFinal ); 
        //echo ($ctl->ObtenerDiasDelMes($mes,$anio));
        $NumDias= $ctl->ObtenerDiasDelMes($mes,$anio);
        /*
        * Se incluiran los lapsos Fecha Incial - Final y si no tiene, lapso Actual
        */
        //Para sacar el Listado de Movimientos del Producto
        if($LapsoInicial=="-")
        {
        $filtro = "AND a.fecha_registro < '".date('Y-m')."-01 00:00:00'";
        $filtro1 = "AND b.fecha_registro < '".date('Y-m')."-01 00:00:00'";
        }
            if($LapsoInicial!="-")
            {
            $filtro = " AND a.fecha_registro < '".$LapsoInicial."-01 00:00:00' ";
            $filtro1 = " AND b.fecha_registro < '".$LapsoInicial."-01 00:00:00' ";
            }
            
           


        $sql = "

            SELECT 
            tipo,
            cantidad
            FROM
            (
                (
                    SELECT
                        CASE WHEN d.inv_tipo_movimiento IN ('E','T')  THEN 'EGRESO'  ELSE 'INGRESO' END as tipo,
                        d.inv_tipo_movimiento as tipo_movimiento,
                        to_char(a.fecha_registro,'YYYY-MM-DD HH24:MI') as fecha,
                        a.fecha_registro,
                        a.prefijo,
                        a.numero,
                        b.cantidad,
                        round(b.total_costo/b.cantidad) as costo,
                        f.usuario,
                        f.nombre,
                        NULL as bodegas_doc_id,
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
                        ".$filtro."
                        AND b.empresa_id = a.empresa_id
                        AND b.prefijo = a.prefijo
                        AND b.numero = a.numero
                        AND b.codigo_producto = '$codigo_producto'
                        AND c.documento_id = a.documento_id
                        AND c.empresa_id = a.empresa_id
                        AND d.tipo_doc_general_id = c.tipo_doc_general_id
                        AND f.usuario_id = a.usuario_id
                        ".$filtro2."
                )
                UNION ALL
                (
                    SELECT
                        CASE WHEN d.cargo = 'IMD'  THEN 'EGRESO' WHEN d.cargo = 'DIMD' THEN 'INGRESO' ELSE '?' END as tipo,
                        CASE WHEN d.cargo = 'IMD'  THEN 'C' WHEN d.cargo = 'DIMD' THEN 'D' ELSE '?' END as tipo_movimiento,
                        to_char(b.fecha_registro,'YYYY-MM-DD HH24:MI') as fecha,
                        b.fecha_registro,
                        c.prefijo,
                        b.numeracion as numero,
                        a.cantidad,
                        a.total_costo as costo,
                        f.usuario,
                        f.nombre,
                        b.bodegas_doc_id,
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
                        ".$filtro1."
                        AND a.bodegas_doc_id = b.bodegas_doc_id
                        AND a.numeracion = b.numeracion
                        AND a.codigo_producto = '$codigo_producto'
                        AND d.consecutivo = a.consecutivo
                        AND f.usuario_id = b.usuario_id
                        ".$filtro3."
                )
                UNION ALL
                (
                    SELECT
                        'INGRESO' as tipo,
                        d.inv_tipo_movimiento as tipo_movimiento,
                        to_char(a.fecha_registro,'YYYY-MM-DD HH24:MI') as fecha,
                        a.fecha_registro,
                        a.prefijo,
                        a.numero,
                        b.cantidad,
                        round(b.total_costo/b.cantidad) as costo,
                        f.usuario,
                        f.nombre,
                        NULL as bodegas_doc_id,
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
                        ".$filtro."
                        AND b.empresa_id = a.empresa_id
                        AND b.prefijo = a.prefijo
                        AND b.numero = a.numero
                        AND b.codigo_producto = '$codigo_producto'
                        AND c.documento_id = a.documento_id
                        AND c.empresa_id = a.empresa_id
                        AND d.tipo_doc_general_id = c.tipo_doc_general_id
                        AND f.usuario_id = a.usuario_id
                        ".$filtro2."

                )
                UNION ALL
                (
                    SELECT 
                        CASE WHEN c.tipo_movimiento IN ('E','T')  THEN 'EGRESO'  ELSE 'INGRESO' END as tipo,
                        c.tipo_movimiento,
                        to_char(b.fecha_registro,'YYYY-MM-DD HH24:MI') as fecha, 
                        b.fecha_registro,
                        c.prefijo, 
                        b.numeracion as numero,
                        a.cantidad,
                        a.total_costo as costo, 
                        f.usuario, 
                        f.nombre, 
                        b.bodegas_doc_id,
                        b.observacion
                        
                                FROM 
                                bodegas_documentos_d as a, 
                                bodegas_documentos as b, 
                                bodegas_doc_numeraciones as c, 
                                system_usuarios as f 
                                            WHERE 
                                            c.empresa_id = '$empresa_id' 
                                            AND c.centro_utilidad = '$centro_utilidad'
                                            AND c.bodega = '$bodega' 
                                            AND b.bodegas_doc_id = c.bodegas_doc_id 
                                            ".$filtro1."
                                            AND a.bodegas_doc_id = b.bodegas_doc_id 
                                            AND a.numeracion = b.numeracion 
                                            AND a.codigo_producto = '$codigo_producto' 
                                            AND  f.usuario_id = b.usuario_id
                                            ".$filtro3."
                                            AND a.consecutivo NOT IN
                                                                   (
                                                                   select 
                                                                   consecutivo
                                                                   from
                                                                   cuentas_detalle
                                                                   where
                                                                   empresa_id ='$empresa_id' 
                                                                   AND centro_utilidad = '$centro_utilidad'
                                                                    )

                )
            ) AS DATOS
            ORDER BY DATOS.fecha;
        ";
       // print_r($sql);
       GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();
      //  $dbconn->debug=true;
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        $retorno = array();
/*
        while($lista = $result->FetchRow())
        {
            $fila['KARDEX'][] = $lista;
        }
        $result->Close();*/

      

        while (!$result->EOF)
        {
       $retorno[] = $result->GetRowAssoc($ToUpper = false);
       $result->MoveNext();
        }
        $result->Close();
        
        return $retorno;
    }
    
    
    /**
    * Obtener Los Productos Pendientes por Ingresar por Compra.
    *
    * @param string  $empresa_id
    * @param string  $centro_utilidad Identificador de la bodega
    * @param string  $bodega Identificador de la bodega
    * @param string  $codigo_producto
    * @return boolean
    * @access public
    */
    function ObtenerProductosPendientesPorComprar($empresa_id,$centro_id,$bodega,$codigo_producto)
    {
        $ctl = new ClaseUtil();
        list( $anio, $mes) = split( '[/.-]', $LapsoFinal ); 
        //echo ($ctl->ObtenerDiasDelMes($mes,$anio));
        $NumDias= $ctl->ObtenerDiasDelMes($mes,$anio);
       

        $sql = "

            SELECT 
            ((copd.numero_unidades)-COALESCE(numero_unidades_recibidas,0))as cantidad,
            cop.orden_pedido_id,
            cop.fecha_registro,
            t.nombre_tercero,
            t.tipo_id_tercero,
            t.tercero_id,
            u.usuario
            FROM
            compras_ordenes_pedidos cop,
            compras_ordenes_pedidos_detalle copd,
            terceros_proveedores tp,
            terceros t,
            system_usuarios u
            where
                  copd.codigo_producto = '".$codigo_producto."'
            AND   copd.numero_unidades<> COALESCE(numero_unidades_recibidas,0)
            AND   copd.orden_pedido_id = cop.orden_pedido_id
            AND   cop.empresa_id = '".$empresa_id."'
            AND   cop.estado = '1'
            AND   cop.codigo_proveedor_id = tp.codigo_proveedor_id
            AND   tp.tipo_id_tercero = t.tipo_id_tercero
            AND   tp.tercero_id = t.tercero_id
            AND   cop.usuario_id = u.usuario_id
                         
            ORDER BY cop.orden_pedido_id;
        ";
       // print_r($sql);
       GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();
        //$dbconn->debug=true;
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        $retorno = array();

        while (!$result->EOF)
        {
       $retorno[] = $result->GetRowAssoc($ToUpper = false);
       $result->MoveNext();
        }
        $result->Close();
        
        return $retorno;
    }
    
      /**
    * Obtener Los Productos Ingresados por Compra.
    *
    * @param string  $empresa_id
    * @param string  $centro_utilidad Identificador de la bodega
    * @param string  $bodega Identificador de la bodega
    * @param string  $codigo_producto
    * @return boolean
    * @access public
    */
    function ObtenerProductosIngresadosCompras($empresa_id,$centro_id,$bodega,$codigo_producto,$LapsoInicial,$LapsoFinal)
    {
        $ctl = new ClaseUtil();
        list( $anio, $mes) = split( '[/.-]', $LapsoFinal ); 
        //echo ($ctl->ObtenerDiasDelMes($mes,$anio));
        $NumDias= $ctl->ObtenerDiasDelMes($mes,$anio);
       
 if($LapsoInicial=="-" && $LapsoFinal=="-")
        {
        $filtro = "AND rp.fecha_registro <= '".date('Y-m')."-01 24:00:00'";
        }
        
       
            if($LapsoInicial!="-")
            {
            $filtro = " AND rp.fecha_registro >= '".$LapsoInicial."-01 00:00:00' ";
            
            }
            
            if($LapsoFinal!="-")
            {
            $filtro .= " AND rp.fecha_registro <= '".$LapsoFinal."-".$NumDias." 24:00:00' ";
            }
            
        $sql = "

            SELECT 
            SUM (rpd.cantidad) as cantidad,
            rp.orden_pedido_id,
            rp.prefijo,
            rp.numero,
            rp.fecha_registro,
            t.nombre_tercero,
            t.tipo_id_tercero,
            t.tercero_id
            FROM
            inv_recepciones_parciales rp,
            inv_recepciones_parciales_d rpd,
            compras_ordenes_pedidos cop,
            terceros_proveedores tp,
            terceros t
            
            where
                  rpd.codigo_producto = '".$codigo_producto."'
            AND   rpd.recepcion_parcial_id = rp.recepcion_parcial_id 
            AND   rp.empresa_id = '".$empresa_id."'
            AND   rp.centro_utilidad = '".$centro_id."'
            AND   rp.bodega = '".$bodega."'
            ".$filtro."
            AND   rp.orden_pedido_id = cop.orden_pedido_id
            AND   cop.codigo_proveedor_id = tp.codigo_proveedor_id
            AND   tp.tipo_id_tercero = t.tipo_id_tercero
            AND   tp.tercero_id = t.tercero_id
                         
            GROUP BY rp.orden_pedido_id,rp.prefijo,rp.numero,rp.fecha_registro, t.nombre_tercero,
            t.tipo_id_tercero,t.tercero_id;
        ";
       // print_r($sql);
       GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();
        //$dbconn->debug=true;
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        $retorno = array();

        while (!$result->EOF)
        {
       $retorno[] = $result->GetRowAssoc($ToUpper = false);
       $result->MoveNext();
        }
        $result->Close();
        
        return $retorno;
    }
    
     /**
    * Obtener Los Productos Pendientes por Despachar a Las Farmacias.
    *
    * @param string  $empresa_id
    * @param string  $centro_utilidad Identificador de la bodega
    * @param string  $bodega Identificador de la bodega
    * @param string  $codigo_producto
    * @return boolean
    * @access public
    */
    function ObtenerProductosPendientesDespacharAFarmacias($empresa_id,$centro_id,$bodega,$codigo_producto)
    {
        $ctl = new ClaseUtil();
        list( $anio, $mes) = split( '[/.-]', $LapsoFinal ); 
        //echo ($ctl->ObtenerDiasDelMes($mes,$anio));
        $NumDias= $ctl->ObtenerDiasDelMes($mes,$anio);
       
        $sql  = "
             select a.solicitud_prod_a_bod_ppal_id,
             a.cantidad_pendiente,
             a.cantidad_solicitada,
             a.farmacia_id,
             f.razon_social,
             a.usuario_id,
             u.usuario
            from
              (
            SELECT 
                      sd.solicitud_prod_a_bod_ppal_id,
                      sd.cantidad_solic as cantidad_pendiente,
                      sd.cantidad_solic as cantidad_solicitada,
                      sd.farmacia_id,
                      s.usuario_id
                      from
                      solicitud_productos_a_bodega_principal_detalle sd,
                      solicitud_productos_a_bodega_principal s
                      where
                            sd.codigo_producto = '".$codigo_producto."'
                      and   sd.solicitud_prod_a_bod_ppal_id = s.solicitud_prod_a_bod_ppal_id
                      and   s.empresa_destino = '".$empresa_id."'
                      and   s.sw_despacho = '0'
                      UNION     
                      SELECT 
                      sd.solicitud_prod_a_bod_ppal_id,
                      ips.cantidad_pendiente,
                      ips.cantidad_solicitad as cantidad_solicitada,
                      ips.farmacia_id,
                      s.usuario_id
                      from
                      solicitud_productos_a_bodega_principal_detalle sd,
                      solicitud_productos_a_bodega_principal s,
                      inv_mov_pendientes_solicitudes_frm ips
                      where
                            sd.codigo_producto = '".$codigo_producto."'
                      and   sd.solicitud_prod_a_bod_ppal_id = s.solicitud_prod_a_bod_ppal_id
                      and   s.empresa_destino = '".$empresa_id."'
                      and   s.sw_despacho = '1'
                      and   sd.solicitud_prod_a_bod_ppal_id = ips.solicitud_prod_a_bod_ppal_id
                      and   sd.solicitud_prod_a_bod_ppal_id = ips.solicitud_prod_a_bod_ppal_id
                      and   sd.solicitud_prod_a_bod_ppal_det_id = ips.solicitud_prod_a_bod_ppal_det_id
                 )as a,
                 empresas as f,
                 system_usuarios u 
                 where
                          f.empresa_id = a.farmacia_id
                      AND u.usuario_id = a.usuario_id; ";
        
        
        
       // print_r($sql);
       GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();
        //$dbconn->debug=true;
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        $retorno = array();

        while (!$result->EOF)
        {
       $retorno[] = $result->GetRowAssoc($ToUpper = false);
       $result->MoveNext();
        }
        $result->Close();
        
        return $retorno;
    }
      
     /**
    * Obtener Los Productos Pendientes por Despachar a Las Farmacias.
    *
    * @param string  $empresa_id
    * @param string  $centro_utilidad Identificador de la bodega
    * @param string  $bodega Identificador de la bodega
    * @param string  $codigo_producto
    * @return boolean
    * @access public
    */
    function ObtenerProductosTemporalesDespacharAFarmacias($empresa_id,$centro_id,$bodega,$codigo_producto)
    {
        $ctl = new ClaseUtil();
        list( $anio, $mes) = split( '[/.-]', $LapsoFinal ); 
        //echo ($ctl->ObtenerDiasDelMes($mes,$anio));
        $NumDias= $ctl->ObtenerDiasDelMes($mes,$anio);
      
        $sql  = "SELECT x.cantidad_solic as cantidad,
				x.codigo_producto,
				a.descripcion ||'-'||z.descripcion as farmacia,
				b.usuario_id,
				b.nombre
				FROM
				solicitud_pro_a_bod_prpal_tmp as x
				JOIN solicitud_bodega_principal_aux as y ON(x.farmacia_id = y.farmacia_id)
				AND (x.centro_utilidad = y.centro_utilidad)
				AND (x.bodega = y.bodega)
				AND (x.usuario_id = y.usuario_id)
				JOIN bodegas as z ON (y.farmacia_id = z.empresa_id)
				AND (y.centro_utilidad = z.centro_utilidad)
				AND (y.bodega = z.bodega)
				JOIN centros_utilidad as a ON (z.empresa_id = a.empresa_id)
				AND (z.centro_utilidad = a.centro_utilidad)
				JOIN system_usuarios as b ON (y.usuario_id = b.usuario_id)
				WHERE y.empresa_destino = '".trim($empresa_id)."'
				AND x.codigo_producto = '".trim($codigo_producto)."'
				;";
        
        
        
       /*print_r($sql);*/
       GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();
        //$dbconn->debug=true;
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        $retorno = array();

        while (!$result->EOF)
        {
       $retorno[] = $result->GetRowAssoc($ToUpper = false);
       $result->MoveNext();
        }
        $result->Close();
        
        return $retorno;
    }
    
    /**
    * Obtener Los Productos Pendientes por Despachar a Los Clientes.
    *
    * @param string  $empresa_id
    * @param string  $centro_utilidad Identificador de la bodega
    * @param string  $bodega Identificador de la bodega
    * @param string  $codigo_producto
    * @return boolean
    * @access public
    */
    function ObtenerProductosPendientesDespacharAClientes($empresa_id,$centro_id,$bodega,$codigo_producto)
    {
        $ctl = new ClaseUtil();
        list( $anio, $mes) = split( '[/.-]', $LapsoFinal ); 
        //echo ($ctl->ObtenerDiasDelMes($mes,$anio));
        $NumDias= $ctl->ObtenerDiasDelMes($mes,$anio);
       

        $sql  = "				
				SELECT
				((b.numero_unidades - b.cantidad_despachada)) as numero_unidades,
				a.pedido_cliente_id,
				a.fecha_registro,
				a.tipo_id_tercero,
                a.tercero_id,
                c.nombre_tercero,
                d.usuario
				FROM
				ventas_ordenes_pedidos AS a
				JOIN ventas_ordenes_pedidos_d AS b ON (a.pedido_cliente_id = b.pedido_cliente_id)
				AND (a.estado = '1')
				AND (a.empresa_id = '".trim($empresa_id)."')
				AND (b.numero_unidades <> b.cantidad_despachada)
				JOIN terceros as c ON (a.tipo_id_tercero = c.tipo_id_tercero)
				AND (a.tercero_id = c.tercero_id)
				JOIN system_usuarios as d ON (a.usuario_id = d.usuario_id)
				WHERE
				b.codigo_producto='".trim($codigo_producto)."'
				ORDER BY a.pedido_cliente_id; ";
						  
       // print_r($sql);
       GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();
        //$dbconn->debug=true;
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        $retorno = array();

        while (!$result->EOF)
        {
       $retorno[] = $result->GetRowAssoc($ToUpper = false);
       $result->MoveNext();
        }
        $result->Close();
        
        return $retorno;
    }
    
       
    /**
    * Obtener El Cierre de un producto en Bodega
    *
    * @param string  $empresa_id
    * @param string  $centro_utilidad Identificador de la bodega
    * @param string  $bodega Identificador de la bodega
    * @param string  $codigo_producto
    * @return boolean
    * @access public
    */
    function ObtenerCierreMes($empresa_id,$centro_id,$bodega,$codigo_producto,$LapsoInicial)
    {
        $ctl = new ClaseUtil();
        
        if($LapsoInicial!= '-')
          {
          $filtro .= "and a.fecha < '".$LapsoInicial."-01'
                      and a.fecha >= '2010-11-01' ";
          }
          
        
        $sql  = "select
                    COALESCE(CASE WHEN a.cantidad_ingreso > 0 and a.cantidad_egreso >0
                    THEN (a.cantidad_ingreso - a.cantidad_egreso)
                    WHEN a.cantidad_egreso > 0 and a.cantidad_inicial >0
                    THEN (a.cantidad_inicial - a.cantidad_egreso)
                    WHEN a.cantidad_ingreso > 0 and a.cantidad_inicial <0
                    THEN (a.cantidad_ingreso - a.cantidad_inicial)
                    END  ,0)as existencia_cierre
                    from
                    rotacion_producto_x_empresa as a,
                    (
                    SELECT 
                    a.codigo_producto,
                    MAX(a.fecha) as fecha

                    from rotacion_producto_x_empresa as a 
                    where 
                    a.empresa_id= '".$empresa_id."' 
                    and a.centro_utilidad = '".$centro_id."' 
                    and a.bodega = '".$bodega."'
                    and a.codigo_producto = '".$codigo_producto."' 
                    $filtro
                    group by a.codigo_producto
                    )as b
                    where
                    a.empresa_id= '".$empresa_id."' 
                    and a.centro_utilidad = '".$centro_id."' 
                    and a.bodega = '".$bodega."' 
                    and a.codigo_producto = b.codigo_producto
                    and a.fecha = b.fecha
						  ";
       // print_r($sql);
       GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();
        //$dbconn->debug=true;
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        $retorno = array();

        while (!$result->EOF)
        {
       $retorno = $result->GetRowAssoc($ToUpper = false);
       $result->MoveNext();
        }
        $result->Close();
        
        return $retorno;
    }

}
?>