<?php

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

class app_ActualizacionPreciosProductos_controller extends classModulo {

    function app_ActualizacionPreciosProductos_controller() {
        return true;
    }

    function main() {
        $request = $_REQUEST;
        $objHtml2 = AutoCarga::factory("Agregar_Actual_HTML", "views", "app", "ActualizacionPreciosProductos");
        $productos = array();
        $criterio = isset($request['criterio'])? $request['criterio'] : '';
        $termino = isset($request['termino'])? $request['termino'] : '';
        $conteo = 0;
        $pagina = 0;
        $paginador = ModuloGetURL("app","ActualizacionPreciosProductos","controller","main", array('criterio'=> $criterio, 'termino'=> $termino));
        $offset = isset($request['offsetBusqueda'])? $request['offsetBusqueda'] : $request['offset'];

        //Si se usa el buscador o el paginador
        if(isset($request['buscar']) || (isset($offset) && !empty($offset))){
            $objSql = AutoCarga::factory("ConsultasSql", "classes", "app", "ActualizacionPreciosProductos");
            //si se esta realizando una busqueda el offset queda en 1
            $offset = $request['buscar']? 1 : $offset;
            $productos = $objSql->BuscarProductos($criterio, $termino, $offset);
            $conteo = $objSql->conteo;
            $pagina = $objSql->pagina;
        }

        $this->salida = $objHtml2->VistaInicio($productos, $criterio, $termino, $conteo, $pagina, $paginador, $offset);

        return true;
    }

    function ActualizarPrecioRegulado(){
        $request = $_REQUEST;
        $objHtml2 = AutoCarga::factory("Agregar_Actual_HTML", "views", "app", "ActualizacionPreciosProductos");
        $objSql = AutoCarga::factory("ConsultasSql", "classes", "app", "ActualizacionPreciosProductos");
        $paginador = ModuloGetURL("app","ActualizacionPreciosProductos","controller","ActualizarPrecioRegulado", array(
                                                                                                                    'criterio'=> $request['criterio'],
                                                                                                                    'termino' => $request['termino'],
                                                                                                                    'codigo_producto' => $request['codigo_producto'],
                                                                                                                    'nombre_producto' => $request['nombre_producto'],
                                                                                                                    'molecula_producto' => $request['molecula_producto'],
                                                                                                                    'precio_regulado_producto' => $request['precio_regulado_producto'],
                                                                                                                    'offsetBusqueda' => $request['offsetBusqueda']
                                                                                                                ));

        if(isset($request['nuevo_precio_regulado_producto']) && (!empty($request['nuevo_precio_regulado_producto']) || $request['nuevo_precio_regulado_producto'] == "0")){
            $rs = $objSql->ActualizarPrecioRegulado($request['codigo_producto'], $request['precio_regulado_producto'], $request['nuevo_precio_regulado_producto']);
        }

        $logs = $objSql->ConsultarLogActualizacionesPrecioRegulado($request['codigo_producto'], $request['offset']);
        $this->salida = $objHtml2->FormularioActualizacionPrecioProducto($request['nuevo_precio_regulado_producto'], $logs, $objSql->conteo, $objSql->pagina, $paginador);

        return true;
    }
}

?>

