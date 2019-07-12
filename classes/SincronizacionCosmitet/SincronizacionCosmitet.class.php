<?php

require_once ('nusoap/lib/nusoap.php');

class SincronizacionCosmitet {

    //var $url_servicio = "http://10.0.1.80/desarrollo/SIIS/ws/ws_documentosBodega.php?wsdl";
    //andres.benitez@cosmitet.net

    var $produccion = true;

    function ingresarDocumentoTemporal($bodegasDoc, $documentoId, $observacion, $destino = '0') {

        $url_servicio = $this->obtenerUrl($destino);

        $encabezado['usuarioId'] = $this->obtenerUsuario($destino);
        $encabezado['bodegasDoc'] = $bodegasDoc;
        $encabezado['documentoId'] = $documentoId;
        $encabezado['observacion'] = $observacion;

        $parametros = $encabezado;

        /* echo "<pre>";
          print_r($parametros);
          echo "</pre>";
          exit(); */

        $funcionWs = "bodegasMovimientoTmp";
        $clienteWs = new nusoap_client($url_servicio, true);
        //$clienteWs = new nusoap_client($this->url_servicio, true);

        $error_ws = false;
        $err = $clienteWs->getError();
        if ($err) {
            $error = $err;
        }
        $result = $clienteWs->call($funcionWs, $parametros);
        if ($clienteWs->fault) {
            $error_ws = true;
            $resultado = $result;
        } else {
            $err = $clienteWs->getError();
            if ($err) {
                $error_ws = true;
                $error = $err;
            } else {
                $error_ws = false;
                $resultado = $result;
            }
        }

        if ($error_ws) {
            $mensajeWs = 'Se ha Generado un error con el Ws de bodegasMovimientoTmp.., no se ha podido establecer conexion';
        }


        //return $resultado;
        return array('parametros'=>$parametros, 'resultado'=>$resultado);
    }

    function ingresarDetalleDocumentoTemporal($docTmpId, $producto, $destino = '0') {


        $url_servicio = $this->obtenerUrl($destino);

        $detalle_documento['usuarioId'] = $this->obtenerUsuario($destino);
        $detalle_documento['docTmpId'] = $docTmpId;
        $detalle_documento['tipoTercero'] = "NIT";
        $detalle_documento['terceroId'] = "830080649";
        $detalle_documento['documentoCompra'] = "AAA000";
        $detalle_documento['fechaDocCompra'] = "2014-06-27";
        $detalle_documento['codigoProducto'] = $producto['codigo_producto'];
        $detalle_documento['cantidad'] = $producto['cantidad'];
        $detalle_documento['porcentajeGravamen'] = $producto['porcentaje_gravamen'];
        $detalle_documento['totalCosto'] = $producto['total_costo'];
        $detalle_documento['fechaVencimiento'] = $producto['fecha_vencimiento'];
        $detalle_documento['lote'] = $producto['lote'];
        $detalle_documento['localizacionProducto'] = "N/A";
        $detalle_documento['totalcostoPedido'] = $producto['total_costo_pedido'];
        $detalle_documento['valorUnitario'] = $producto['valor_unitario'];
        $detalle_documento['descuento'] = 0;


        $parametros = $detalle_documento;

        /* echo "<pre>";
          print_r($parametros);
          echo "</pre>";
          exit(); */

        $funcionWs = "bodegasMovimientoTmpD";
        $clienteWs = new nusoap_client($url_servicio, true);
        
        $error_ws = false;
        $err = $clienteWs->getError();
        
        
        if ($err) {
            $error = $err;
        }
        $result = $clienteWs->call($funcionWs, $parametros);
        if ($clienteWs->fault) {
            $error_ws = true;
            $resultado = $result;
        } else {
            $err = $clienteWs->getError();
            if ($err) {
                $error_ws = true;
                $error = $err;
            } else {
                $error_ws = false;
                $resultado = $result;
            }
        }


        if ($error_ws) {
            $mensajeWs = 'Se ha Generado un error con el Ws de bodegasMovimientoTmpD, no se ha podido establecer conexion';
        }

        /*echo "=========";
        var_dump($parametros);
        var_dump($mensajeWs);
        var_dump($resultado);
        echo "=========";
        exit();*/

        //return $resultado;
        return array('parametros'=>$parametros, 'resultado'=>$resultado);
    }
    
    function ingresar_Despacho_Clientes($parametros, $destino = '0'){
        $url_servicio = $this->obtenerUrl($destino);

        $funcionWs = "almacenarRemisionMedicamentosInsumos";
        $clienteWs = new nusoap_client($url_servicio, true);
        
        $error_ws = false;
        $err = $clienteWs->getError();        
    
        if ($err) {
            $error = $err;
        }
      
        $result = $clienteWs->call($funcionWs, $parametros);

        if ($clienteWs->fault) {
            $error_ws = true;
            $resultado = $result;
        } else {
            $err = $clienteWs->getError();
            if ($err) {
                $error_ws = true;
                $error = $err;
            } else {
                $error_ws = false;
                $resultado = $result;
            }
        }
        if ($error_ws) {
            $resultado = 'Se ha Generado un error con el Ws, no se ha podido establecer conexion'.$result["message"];
        }else{
            $resultado = $result["message"];
        }
        return array('parametros'=>$parametros, 'resultado'=>$resultado);
    }
    
   
    function obtenerUrl($destino) {
<<<<<<< HEAD
<<<<<<< HEAD
     $flack=false;//pruebas = false
        if ($destino == '0') {
            //Sincronizar con Cosmitet 
            if($flack){
=======
     $bandera=false;//pruebas = false
        if ($destino == '0') {
            //Sincronizar con Cosmitet 
            if($bandera){
>>>>>>> facturacion_proveedores_ventana
=======
     $bandera=false;//pruebas = false
        if ($destino == '0') {
            //Sincronizar con Cosmitet 
            if($bandera){
>>>>>>> e3908f812a30cd1de0817713bd53d3ff28549116
              return "http://10.0.0.3/pg9/desarrollo/SIIS/ws/ws_documentosBodega.php?wsdl"; // Pruebas   
            }else{
              return "http://dusoft.cosmitet.net/SIIS/ws/ws_documentosBodega.php?wsdl"; // Produccion
            }
        } else if ($destino == '1') {
            //Sincronizar con Farmacias Cartagena
<<<<<<< HEAD
<<<<<<< HEAD
            if($flack){
=======
            if($bandera){
>>>>>>> facturacion_proveedores_ventana
=======
            if($bandera){
>>>>>>> e3908f812a30cd1de0817713bd53d3ff28549116
               return "http://10.0.0.3/SIIS/ws/ws_documentosBodega.php?wsdl"; // Pruebas
             }else{
               return "http://10.245.1.140/SIIS/ws/ws_documentosBodega.php?wsdl"; // Produccion
             }
        }else if ($destino == '2') {
            //Sincronizar con Dumian
<<<<<<< HEAD
<<<<<<< HEAD
            if($flack){
=======
            if($bandera){
>>>>>>> facturacion_proveedores_ventana
=======
            if($bandera){
>>>>>>> e3908f812a30cd1de0817713bd53d3ff28549116
               
             }else{
                return "http://10.0.0.3/pg9/julian.barbosa/asistencial/ws/ws_remision_medicamentos_insumos.php?wsdl"; // Pruebas
            }
        }
    }

    function obtenerUsuario($destino) {

        if ($destino == '0') {
            //Sincronizar con Cosmitet                        
            return "4608";
        } else if ($destino == '1') {
            //Sincronizar con Farmacias Cartagena
            return "4608";
        }
    }

}

?>