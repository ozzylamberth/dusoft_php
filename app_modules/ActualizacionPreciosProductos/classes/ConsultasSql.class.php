<?php

class ConsultasSql extends ConexionBD {

    //Busca productos segun el criterio $criterio = {codigo | nombre | molecula}, $termino = cadena para busqueda
    function BuscarProductos($criterio, $termino, $offset){
        $where = '';

        if($criterio == 'codigo'){
            $where = "AND i.codigo_producto ILIKE '%$termino%'";
        }
        if($criterio == 'nombre'){
            $where = "AND ip.descripcion ILIKE '%$termino%'";
        }
        if($criterio == 'molecula'){
            $where = "AND fc_descripcion_producto_molecula(i.codigo_producto) ILIKE '%$termino%'";
        }

        $sql =  "SELECT i.codigo_producto AS codigo_producto, ip.descripcion as nombre_producto, fc_descripcion_producto_molecula(i.codigo_producto) as molecula_producto, i.precio_regulado as precio_regulado_producto
                FROM inventarios  i JOIN inventarios_productos ip  ON (i.codigo_producto  = ip.codigo_producto)
                WHERE 1=1 $where AND i.empresa_id = '03' ";

        $cont  = "SELECT COUNT(*) FROM (".$sql.") A  ";
        $this->ProcesarSqlConteo($cont,$offset);
        $sql .= " LIMIT ".$this->limit." OFFSET ".$this->offset;

        if (!$results = $this->ConexionBaseDatos($sql))
            return false;
        while (!$results->EOF) {
            $vars[] = $results->GetRowAssoc($ToUpper = false);
            $results->MoveNext();
        }
        $results->Close();

        return $vars;
    } 


    function ActualizarPrecioRegulado($codigo_producto, $precio_regulado_producto, $nuevo_precio_regulado_producto){
        $usuario_id = UserGetUID();

        $sql = "BEGIN;
                UPDATE inventarios SET
                precio_regulado = " . $nuevo_precio_regulado_producto . "
                WHERE  codigo_producto= '" . $codigo_producto . "'; 
                INSERT INTO log_actualizacion_precio_regulado (codigo_producto, usuario_id, fecha, anterior, actual) VALUES ('".$codigo_producto."', ".$usuario_id.", now(), ".$precio_regulado_producto.", ".$nuevo_precio_regulado_producto.");
                COMMIT;";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $rst->Close();
        return true;
    }

    function ConsultarLogActualizacionesPrecioRegulado($codigo_producto, $offset){
        $sql = "SELECT su.nombre as nombre_usuario, lapr.anterior, lapr.actual, lapr.fecha
                FROM log_actualizacion_precio_regulado  lapr JOIN
                    system_usuarios su ON (lapr.usuario_id = su.usuario_id)
                where codigo_producto ='" . $codigo_producto . "' ORDER BY lapr.fecha DESC ";

        $cont  = "SELECT COUNT(*) FROM (".$sql.") A  ";
        $this->ProcesarSqlConteo($cont,$offset);
        $sql .= " LIMIT ".$this->limit." OFFSET ".$this->offset;

        if (!$results = $this->ConexionBaseDatos($sql))
            return false;
        while (!$results->EOF) {
            $vars[] = $results->GetRowAssoc($ToUpper = false);
            $results->MoveNext();
        }
        $results->Close();
        return $vars;
    }
}

?>