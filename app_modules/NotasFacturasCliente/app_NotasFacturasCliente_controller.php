<?php

/**
 * @package DUANA & CIA
 * @version 1.0 $Id: app_NotasFacturasCliente_controller.php,v 1.0 $
 * @copyright DUANA & CIA 06-DIC-2013
 * @author L.G.T.L
 */

/**
 * Clase Control: NotasFacturasCliente
 * Responsabilidad: Clase encargada del control de llamado de metodos en el modulo
 * */
class app_NotasFacturasCliente_controller extends classModulo {

    /**
     * @var array $action  Vector donde se almacenan los links de la aplicacion
     */
    var $action = array();

    /**
     * @var array $request Vector donde se almacenan los datos pasados por request
     */
    var $request = array();

    /*     * **********************************************************
     * Constructor de la clase
     * ********************************************************** */

    function app_NotasFacturasCliente_controller()
    {
        
    }

    /*     * ********************************************************** 
      Funcion principal del modulo
      @return boolean
     * ********************************************************** */

    function main()
    {
        $url[0] = 'app'; //Tipo de Modulo
        $url[1] = 'NotasFacturasCliente'; //Nombre del Modulo
        $url[2] = 'controller'; //tipo controller...
        $url[3] = 'Menu'; //Metodo.
        $url[4] = 'datos'; //vector de $_request.
        $arreglo[0] = 'EMPRESA'; //Sub Titulo de la Tabla
        //Generar busqueda de Permisos SQL
        $permiso = AutoCarga::factory("Permisos", "", "app", "NotasFacturasCliente");
        $datos = $permiso->BuscarPermisos();

        // Menu de empresas con permiso 
        $forma = gui_theme_menu_acceso("SELECCIONE EMPRESA", $arreglo, $datos, $url, ModuloGetURL('system', 'Menu'));
        $this->salida = $forma;

        return true;
    }

    /*     * *************************************************************
     * Función en la que se visualizan los documentos relacionados a la empresa
     * ************************************************************* */

    function Menu()
    {
        $request = $_REQUEST;

        SessionSetVar("empresa_id", $request['datos']['empresa_id']);

        $vista = AutoCarga::factory("NotasFacturasClienteHTML", "views", "app", "NotasFacturasCliente");

        $action['volver'] = ModuloGetURL("app", "NotasFacturasCliente", "controller", "main");

        $action['BuscarFacturaNotaCredito'] = ModuloGetURL("app", "NotasFacturasCliente", "controller", "BuscarFactura", array("modoNota" => "credito"));
        $action['BuscarNotaCredito'] = ModuloGetURL("app", "NotasFacturasCliente", "controller", "BuscarNotaCredito");

        $action['BuscarFacturaNotaDebito'] = ModuloGetURL("app", "NotasFacturasCliente", "controller", "BuscarFactura", array("modoNota" => "debito"));
        $action['BuscarNotaDebito'] = ModuloGetURL("app", "NotasFacturasCliente", "controller", "BuscarNotaDebito");

        $this->salida = $vista->Menu($action);

        return true;
    }

    /*     * *************************************************************
     * Función para buscar las facturas para crear notas crédito
     * ************************************************************* */

    function BuscarFactura($action)
    {
        $request = $_REQUEST;

        $modoNota = $request['modoNota'];
        $factura = $request['factura'];

        $empresa_id = SessionGetVar("empresa_id");
        
         SessionSetVar("tiponotacredito", $request['tiponotacredito']);
       //  echo print_r(SessionGetVar("tiponotacredito")). " ???????";
        
        $sql = AutoCarga::factory("NotasFacturasClienteSQL", "", "app", "NotasFacturasCliente");

        if ($factura)
        {
            $datosFactura = $sql->ObtenerFactura($factura, $empresa_id);
        }

        $vista = AutoCarga::factory("NotasFacturasClienteHTML", "views", "app", "NotasFacturasCliente");

        if ($modoNota == "credito")
        {
            $tipo = "CRÉDITO";
            $action['CrearNota'] = ModuloGetURL("app", "NotasFacturasCliente", "controller", "CrearNota", array("modoNota" => $modoNota, "factura" => $factura));
        }
        else
        {
            $tipo = "DÉBITO";
            $action['CrearNota'] = ModuloGetURL("app", "NotasFacturasCliente", "controller", "CrearNotaDebito", array("modoNota" => $modoNota, "factura" => $factura));
        }

        $action['BuscarFactura'] = ModuloGetURL("app", "NotasFacturasCliente", "controller", "BuscarFactura", array("modoNota" => $modoNota));
        $action['volver'] = ModuloGetURL("app", "NotasFacturasCliente", "controller", "main");

        $this->salida = $vista->BuscadorFactura($action, $tipo, $factura, $datosFactura, $request['tiponotacredito']);

        return true;
    }

    /*     * *************************************************************
     * Función para crear las notas crédito
     * ************************************************************* */

    function CrearNota()
    {
        $request = $_REQUEST;

        $modoNota = $request['modoNota'];
        $factura = $request['factura'];

        $empresa_id = SessionGetVar("empresa_id");
        $tiponotacredito = SessionGetVar("tiponotacredito");

        $sql = AutoCarga::factory("NotasFacturasClienteSQL", "", "app", "NotasFacturasCliente");
        $sql_f = AutoCarga::factory("FacturasDespachoSQL", "classes", "app", "FacturasDespacho");

        $datosFactura = $sql->ObtenerFactura($factura, $empresa_id);
        $conceptos = $sql->ObtenerConceptos();
        $this->SetXajax(array("mostrarFormularioConceptoNota"), "app_modules/NotasFacturasCliente/RemoteXajax/Notas_Facturas_ClienteSQL.php", "ISO-8859-1");
        
       //echo print_r($datosFactura);
        //if(empty($datosFactura[0]['numero'])) {
       /* if ($datosFactura[0]['numero_devolucion'] == $datosFactura[0]['numero_devolucion_nota_credito'])
        {
            $tipo = "VALOR";
            $segundos = strtotime('now') - strtotime($datosFactura[0]['fecha_registro']);
          // $diferenciaDias = intval($segundos / 60 / 60 / 24);
            $diferenciaDias = false;
        }
        else
        {
            $tipo = "DEVOLUCIÓN";
            $diferenciaDias = false;
        }*/
        
        if($tiponotacredito == "valor"){
            $tipo = "VALOR";
            $segundos = strtotime('now') - strtotime($datosFactura[0]['fecha_registro']);
             $diferenciaDias = false;
        } else {
            $tipo = "DEVOLUCIÓN";
            $diferenciaDias = false;
        }

        SessionSetVar("factura_fiscal", $datosFactura[0]['factura_fiscal']);
        SessionSetVar("prefijo", $datosFactura[0]['prefijo']);

        if ($tipo == "VALOR")
        {
            $detalleFactura = $sql->ObtenerDetalleFacturaNotaCreditoValor($factura, $empresa_id, $tipo);
        }
        else
        {
           /* if ($datosFactura[0]['numero_devolucion'] == $datosFactura[0]['numero_devolucion_nota_credito']){
                $detalleFactura = array();
            } else {
                
            }*/
            //Traer  los temporales
            $detalleFactura = $sql->buscarTemporalesNotaCreditoPorDevolucion($factura, $empresa_id, $datosFactura[0]["tipo_factura"], $tipo);
           
            //devoluciones
            if(count($detalleFactura) == 0){
                 $detalleFactura = $sql->ObtenerDetalleFacturaNotaCreditoDevolucion($factura, $empresa_id, $datosFactura[0]["tipo_factura"], $tipo); 
            }
          
            
        }
        
        $cantidadDetallesTemporales = 0;
        $totalnota = 0;
        $valoriva = 0;

        for ($i = 0; $i < count($detalleFactura); $i++)
        {
            if (!empty($detalleFactura[$i]['tmp_detalle_nota_credito_despacho_cliente_id']))
            {
                $cantidadDetallesTemporales = $cantidadDetallesTemporales + 1;
                $totalnota += $detalleFactura[$i]['valor'];  
                $valoriva += $detalleFactura[$i]["valor_iva"];
            }
        }

        $totalItemsFactura = count($detalleFactura);

        if (!isset($request['guarda_temporal']))
        {//Al entrar a la acción por "primera vez" ó al eliminar un detalle temporal de nota
            $tmp_nota_credito_despacho_cliente_id = $sql->ObtenerIdNotaCreditoTemporal($empresa_id, $datosFactura[0]['factura_fiscal'], $datosFactura[0]['prefijo'], $tipo);
            $concepto = $sql->ObtenerConceptosPorId($tmp_nota_credito_despacho_cliente_id['concepto_id']);
             SessionSetVar("concepto_nota", $concepto);
        }

        $cantidadValoresTotalProductoDevuelto = 0;
        $cantidadTmpDetalleNotaCreditoDespachoClienteId = 0;

        if ($tipo == "DEVOLUCIÓN")
        {
            for ($i = 0; $i < count($detalleFactura); $i++)
            {
                if (!empty($detalleFactura[$i]['valor_total_producto_devuelto']))
                {
                    $cantidadValoresTotalProductoDevuelto = $cantidadValoresTotalProductoDevuelto + 1;
                }

                if (!empty($detalleFactura[$i]['tmp_detalle_nota_credito_despacho_cliente_id']))
                {
                    $cantidadTmpDetalleNotaCreditoDespachoClienteId = $cantidadTmpDetalleNotaCreditoDespachoClienteId + 1;
                }
            }
        }
                
        $calculofactura = array();
       $factura_detalle = $sql_f->Detalle_Factura($datosFactura[0]["empresa_id"], $datosFactura[0]["prefijo"], $datosFactura[0]["factura_fiscal"]);
         
       $parametros_retencion = $sql_f->Parametros_Retencion($datosFactura[0]["empresa_id"], $datosFactura[0]["anio_factura"]);
         
          foreach ($factura_detalle as $k => $valor) {
              //echo $valor['subtotal'];
            $subtotal += $valor['subtotal'];
        }
       
        $subtotal = ceil($subtotal);
       
        $retencion_fuente = 0; // Credito
        if ($parametros_retencion['sw_rtf'] == '2' || $parametros_retencion['sw_rtf'] == '3') {      
            if ($subtotal >= $parametros_retencion['base_rtf']) {
                // echo $totalnota;
                $retencion_fuente = $totalnota  * ($datosFactura[0]['porcentaje_rtf'] / 100);
                if ($retencion_fuente > 0) {
                    $retencion_fuente = ceil($retencion_fuente);
                }
            }
        }
        
       // echo $retencion_fuente;
        $retencion_ica = 0;
        if ($parametros_retencion['sw_ica'] == '2' || $parametros_retencion['sw_ica'] == '3') {
            if ($subtotal >= $parametros_retencion['base_ica']) {
                $retencion_ica = $totalnota  * ($datosFactura[0]['porcentaje_ica'] / 1000);
                if ($retencion_ica > 0) {
                    $retencion_ica = ceil($retencion_ica);
                }
            }
        }

        $calculofactura["sub_total"] = FormatoValor($totalnota);
        $calculofactura["total_iva"] = FormatoValor($valoriva);
        $calculofactura["retefuente"] = FormatoValor($retencion_fuente);
        $calculofactura["reteica"] = FormatoValor($retencion_ica);
        $calculofactura["total_nota"] = FormatoValor($totalnota + $valoriva -$retencion_fuente - $retencion_ica);
                 
        
        

        $vista = AutoCarga::factory("NotasFacturasClienteHTML", "views", "app", "NotasFacturasCliente");

        $action['GuardarNotaTemporal'] = ModuloGetURL("app", "NotasFacturasCliente", "controller", "GuardarNotaTemporal");

        $action['EliminarNotaTemporal'] = ModuloGetURL("app", "NotasFacturasCliente", "controller", "EliminarNotaTemporal", array("factura" => $factura));

        $action['GuardarNota'] = ModuloGetURL("app", "NotasFacturasCliente", "controller", "GuardarNota");

        $action['volver'] = ModuloGetURL("app", "NotasFacturasCliente", "controller", "main");

        $action['volverAnterior'] = ModuloGetURL("app", "NotasFacturasCliente", "controller", "BuscarFactura", array("modoNota" => $modoNota, "factura" => $factura));

    $this->salida = $vista->FormaCrearNotaCredito($action, $factura, $datosFactura, $detalleFactura, $tmp_nota_credito_despacho_cliente_id, $totalItemsFactura, $tipo, $diferenciaDias, $cantidadDetallesTemporales, $cantidadValoresTotalProductoDevuelto, $cantidadTmpDetalleNotaCreditoDespachoClienteId, $calculofactura, $conceptos);

        return true;
    }

    /*     * *************************************************************
     * Función para crear las notas débito
     * ************************************************************* */

    function CrearNotaDebito()
    {
        $request = $_REQUEST;

        $modoNota = $request['modoNota'];
        $factura = $request['factura'];

        $empresa_id = SessionGetVar("empresa_id");

        $sql = AutoCarga::factory("NotasFacturasClienteSQL", "", "app", "NotasFacturasCliente");
        $sql_f = AutoCarga::factory("FacturasDespachoSQL", "classes", "app", "FacturasDespacho");

        $datosFactura = $sql->ObtenerFactura($factura, $empresa_id);

        $segundos = strtotime('now') - strtotime($datosFactura[0]['fecha_registro']);
       //$diferenciaDias = intval($segundos / 60 / 60 / 24);
        $diferenciaDias = false;

        SessionSetVar("factura_fiscal", $datosFactura[0]['factura_fiscal']);
        SessionSetVar("prefijo", $datosFactura[0]['prefijo']);

        $detalleFactura = $sql->ObtenerDetalleFacturaNotaDebito($factura, $empresa_id);

        $cantidadDetallesTemporales = 0;
        $totalnota = 0;
        $valoriva = 0;

        for ($i = 0; $i < count($detalleFactura); $i++)
        {
            if (!empty($detalleFactura[$i]['tmp_detalle_nota_debito_despacho_cliente_id']))
            {
                $cantidadDetallesTemporales = $cantidadDetallesTemporales + 1;
                 $totalnota += $detalleFactura[$i]['valor'];  
                $valoriva += $detalleFactura[$i]["valor_iva"];
            }
        }

        $totalItemsFactura = count($detalleFactura);

        if (!isset($request['guarda_temporal']))
        {//Al entrar a la acción por "primera vez" ó al eliminar un detalle temporal de nota
            $tmp_nota_debito_despacho_cliente_id = $sql->ObtenerIdNotaDebitoTemporal($empresa_id, $datosFactura[0]['factura_fiscal'], $datosFactura[0]['prefijo']);
        }
        
        
        
        
         $calculofactura = array();
       $factura_detalle = $sql_f->Detalle_Factura($datosFactura[0]["empresa_id"], $datosFactura[0]["prefijo"], $datosFactura[0]["factura_fiscal"]);
         
       $parametros_retencion = $sql_f->Parametros_Retencion($datosFactura[0]["empresa_id"], $datosFactura[0]["anio_factura"]);
         
          foreach ($factura_detalle as $k => $valor) {
            $subtotal += $valor['subtotal'];
        }

        $subtotal = (int) $subtotal;
        
        $retencion_fuente = 0; // Credito
        if ($parametros_retencion['sw_rtf'] == '2' || $parametros_retencion['sw_rtf'] == '3') {      
            if ($subtotal >= $parametros_retencion['base_rtf']) {
                $retencion_fuente = $totalnota  * ($datosFactura[0]['porcentaje_rtf'] / 100);
                if ($retencion_fuente > 0) {
                    $retencion_fuente = (int) $retencion_fuente;
                }
            }
        }

        $retencion_ica = 0;
        if ($parametros_retencion['sw_ica'] == '2' || $parametros_retencion['sw_ica'] == '3') {
            if ($subtotal >= $parametros_retencion['base_ica']) {
                $retencion_ica = $totalnota  * ($datosFactura[0]['porcentaje_ica'] / 1000);
                if ($retencion_ica > 0) {
                    $retencion_ica = (int) $retencion_ica;
                }
            }
        }

        $calculofactura["sub_total"] = FormatoValor($totalnota);
        $calculofactura["total_iva"] = FormatoValor($valoriva);
        $calculofactura["retefuente"] = FormatoValor($retencion_fuente);
        $calculofactura["reteica"] = FormatoValor($retencion_ica);
        $calculofactura["total_nota"] = FormatoValor($totalnota + $valoriva -$retencion_fuente - $retencion_ica);
        
        
        
        

        $vista = AutoCarga::factory("NotasFacturasClienteHTML", "views", "app", "NotasFacturasCliente");

        $action['GuardarNotaDebitoTemporal'] = ModuloGetURL("app", "NotasFacturasCliente", "controller", "GuardarNotaDebitoTemporal");

        $action['EliminarNotaDebitoTemporal'] = ModuloGetURL("app", "NotasFacturasCliente", "controller", "EliminarNotaDebitoTemporal", array(/* "modoNota" => $modoNota, */"factura" => $factura));

        $action['GuardarNotaDebito'] = ModuloGetURL("app", "NotasFacturasCliente", "controller", "GuardarNotaDebito");

        $action['volver'] = ModuloGetURL("app", "NotasFacturasCliente", "controller", "main");

        $action['volverAnterior'] = ModuloGetURL("app", "NotasFacturasCliente", "controller", "BuscarFactura", array("modoNota" => $modoNota, "factura" => $factura));

        $this->salida = $vista->FormaCrearNotaDebito($action, $factura, $datosFactura, $detalleFactura, $tmp_nota_debito_despacho_cliente_id, $totalItemsFactura, $diferenciaDias, $cantidadDetallesTemporales, $calculofactura);

        return true;
    }

    /*     * *************************************************************
     * Función para guardar las notas crédito temporales
     * ************************************************************* */

    function GuardarNotaTemporal()
    {
        $request = $_REQUEST;
        

        $factura = SessionGetVar("factura_fiscal");

        $totalItemsFactura = $request['totalItemsFactura'];

        $empresa_id = SessionGetVar("empresa_id");

        $sql = AutoCarga::factory("NotasFacturasClienteSQL", "", "app", "NotasFacturasCliente");

        $datosFactura = $sql->ObtenerFactura($factura, $empresa_id);
          
        $concepto = $sql->ObtenerConceptosPorId($request['concepto_id']);
        SessionSetVar("concepto_nota", $concepto);
        
        
        if (empty($request['tmp_nota_credito_despacho_cliente_id']) && isset($request['guarda_temporal']))
        {//Al guardar primer(os) detalle(s) temporal(es) de notas, es decir al guardar el "encabezado"
            if ($request['tipo'] == "VALOR")
            {
                $valor_nota = (isset($request['valor_nota']))?$request['valor_nota']:0;
                $descripcion = (isset($request['descripcion']))?$request['descripcion']:"";
                
                $tmp_nota_credito_despacho_cliente_id = $sql->GuardarNotaCreditoTemporalPorValor($empresa_id, $datosFactura[0]['factura_fiscal'], $datosFactura[0]['prefijo'], $request['tipo'], $datosFactura[0]["tipo_factura"], $request['concepto_id'], $valor_nota,
                                                                                                                                                            $descripcion);
            }
            elseif ($request['tipo'] == "DEVOLUCIÓN")
            {
                $tmp_nota_credito_despacho_cliente_id = $sql->GuardarNotaCreditoTemporalPorDevolucion($empresa_id, $datosFactura[0]['factura_fiscal'], $datosFactura[0]['prefijo'], $request['empresa_id_devolucion'], $request['prefijo_devolucion'], $request['numero_devolucion'], $request['tipo'], $datosFactura[0]["tipo_factura"]);
            }

            
            if(is_null($request['concepto_id']) || $request['concepto_id'] == 1){
                
                    for ($i = 0; $i < $totalItemsFactura; $i++)
                    {
                        if (isset($request['guarda_' . $i]))
                        {
                            $valor = number_format($request['valor_' . $i], 4, '.', '');
                            $valoresImpuestosNotaTemporal = $sql->ObtenerValoresImpuestosNotaTemporal($request['item_id_' . $i], $valor, $datosFactura[0]["tipo_factura"]);
                            if (isset($request['movimiento_id_' . $i]))
                            {
                                $movimiento_id = $request['movimiento_id_' . $i];
                            }
                            else
                            {
                                $movimiento_id = "NULL";
                            }
                         //   $sql->GuardarDetalleNotaCreditoTemporal($tmp_nota_credito_despacho_cliente_id['tmp_nota_credito_despacho_cliente_id'], $request['item_id_' . $i], $request['valor_' . $i], $request['observacion_' . $i], $valoresImpuestosNotaTemporal[0]['valor_total_iva_nota'], $valoresImpuestosNotaTemporal[0]['valor_total_rtf_nota'], $valoresImpuestosNotaTemporal[0]['valor_total_ica_nota'], $movimiento_id);
                            $sql->GuardarDetalleNotaCreditoTemporal($tmp_nota_credito_despacho_cliente_id['tmp_nota_credito_despacho_cliente_id'], $request['item_id_' . $i], $request['valor_' . $i], $request['observacion_' . $i], $valoresImpuestosNotaTemporal[0]['valor_total_iva_nota'], 0, 0, $movimiento_id);
                        }
                    }
            }
        }
        else if (!empty($request['tmp_nota_credito_despacho_cliente_id']) && isset($request['guarda_temporal']))
        {//Al guardar nota detalle temporal cuando el "encabezado" ya ha sido creado
            
            $sql->modificarConcepto($request['tmp_nota_credito_despacho_cliente_id'], $request['concepto_id']);
            
            
            $tmp_nota_credito_despacho_cliente_id = $request['tmp_nota_credito_despacho_cliente_id'];
            
             if(is_null($request['concepto_id']) || $request['concepto_id'] == 1){
                    for ($i = 0; $i < $totalItemsFactura; $i++)
                    {
                        if (isset($request['guarda_' . $i]))
                        {
                            $valor = number_format($request['valor_' . $i], 4, '.', '');
                            $valoresImpuestosNotaTemporal = $sql->ObtenerValoresImpuestosNotaTemporal($request['item_id_' . $i], $valor, $datosFactura[0]["tipo_factura"]);
                            if (isset($request['movimiento_id_' . $i]))
                            {
                                $movimiento_id = $request['movimiento_id_' . $i];
                            }
                            else
                            {
                                $movimiento_id = "NULL";
                            }
                            $sql->GuardarDetalleNotaCreditoTemporal($tmp_nota_credito_despacho_cliente_id, $request['item_id_' . $i], $request['valor_' . $i], $request['observacion_' . $i], $valoresImpuestosNotaTemporal[0]['valor_total_iva_nota'], $valoresImpuestosNotaTemporal[0]['valor_total_rtf_nota'], $valoresImpuestosNotaTemporal[0]['valor_total_ica_nota'], $movimiento_id);
                        }
                    }
             }
        }

        $consulta = ModuloGetURL('app', 'NotasFacturasCliente', 'controller', 'CrearNota', array("factura" => $factura, "tmp_nota_credito_despacho_cliente_id" => $tmp_nota_credito_despacho_cliente_id, "concepto" => $request['concepto_id']));

        header('Location: ' . $consulta . '');
    }

    /*     * *************************************************************
     * Función para guardar las notas débito temporales
     * ************************************************************* */

    function GuardarNotaDebitoTemporal()
    {
        $request = $_REQUEST;

        $factura = SessionGetVar("factura_fiscal");

        $totalItemsFactura = $request['totalItemsFactura'];

        $empresa_id = SessionGetVar("empresa_id");

        $sql = AutoCarga::factory("NotasFacturasClienteSQL", "", "app", "NotasFacturasCliente");

        $datosFactura = $sql->ObtenerFactura($factura, $empresa_id);

        if (empty($request['tmp_nota_debito_despacho_cliente_id']) && isset($request['guarda_temporal']))
        {//Al guardar primer(os) detalle(s) temporal(es) de notas, es decir al guardar el "encabezado"
            $tmp_nota_debito_despacho_cliente_id = $sql->GuardarNotaDebitoTemporal($empresa_id, $datosFactura[0]['factura_fiscal'], $datosFactura[0]['prefijo'], $datosFactura[0]["tipo_factura"]);

            for ($i = 0; $i < $totalItemsFactura; $i++)
            {
                if (isset($request['guarda_' . $i]))
                {
                    $valor = number_format($request['valor_' . $i], 4, '.', '');
                    $valoresImpuestosNotaTemporal = $sql->ObtenerValoresImpuestosNotaTemporal($request['item_id_' . $i], $valor, $datosFactura[0]["tipo_factura"]);
                    $sql->GuardarDetalleNotaDebitoTemporal($tmp_nota_debito_despacho_cliente_id['tmp_nota_debito_despacho_cliente_id'], $request['item_id_' . $i], $request['valor_' . $i], $request['observacion_' . $i], $valoresImpuestosNotaTemporal[0]['valor_total_iva_nota'], $valoresImpuestosNotaTemporal[0]['valor_total_rtf_nota'], $valoresImpuestosNotaTemporal[0]['valor_total_ica_nota']);
                }
            }
        }
        else if (!empty($request['tmp_nota_debito_despacho_cliente_id']) && isset($request['guarda_temporal']))
        {//Al guardar nota detalle temporal cuando el "encabezado" ya ha sido creado
            $tmp_nota_debito_despacho_cliente_id = $request['tmp_nota_debito_despacho_cliente_id'];
            for ($i = 0; $i < $totalItemsFactura; $i++)
            {
                if (isset($request['guarda_' . $i]))
                {
                    $valor = number_format($request['valor_' . $i], 4, '.', '');
                    $valoresImpuestosNotaTemporal = $sql->ObtenerValoresImpuestosNotaTemporal($request['item_id_' . $i], $valor, $datosFactura[0]["tipo_factura"]);
                    $sql->GuardarDetalleNotaDebitoTemporal($tmp_nota_debito_despacho_cliente_id, $request['item_id_' . $i], $request['valor_' . $i], $request['observacion_' . $i], $valoresImpuestosNotaTemporal[0]['valor_total_iva_nota'], $valoresImpuestosNotaTemporal[0]['valor_total_rtf_nota'], $valoresImpuestosNotaTemporal[0]['valor_total_ica_nota']);
                }
            }
        }

        $consulta = ModuloGetURL('app', 'NotasFacturasCliente', 'controller', 'CrearNotaDebito', array("factura" => $factura, "tmp_nota_debito_despacho_cliente_id" => $tmp_nota_debito_despacho_cliente_id));

        header('Location: ' . $consulta . '');
    }

    /*     * *************************************************************
     * Función para eliminar las notas crédito temporales
     * ************************************************************* */

    function EliminarNotaTemporal()
    {
        $request = $_REQUEST;

        $sql = AutoCarga::factory("NotasFacturasClienteSQL", "", "app", "NotasFacturasCliente");

        $sql->EliminarDetalleNotaCreditoTemporal($request['tmp_detalle_nota_credito_despacho_cliente_id']);

        $consulta = ModuloGetURL('app', 'NotasFacturasCliente', 'controller', 'CrearNota', array("factura" => $request['factura']));

        header('Location: ' . $consulta . '');
    }
    
    function eliminarNotaConcepto(){
        $request = $_REQUEST;

        $sql = AutoCarga::factory("NotasFacturasClienteSQL", "", "app", "NotasFacturasCliente");

        $sql->eleminarNotaConcepto($request['tmp_nota_credito_despacho_cliente_id']);

        $consulta = ModuloGetURL('app', 'NotasFacturasCliente', 'controller', 'CrearNota', array("factura" => $request['factura']));

        header('Location: ' . $consulta . '');
    }

    /*     * *************************************************************
     * Función para eliminar las notas débito temporales
     * ************************************************************* */

    function EliminarNotaDebitoTemporal()
    {
        $request = $_REQUEST;

        $sql = AutoCarga::factory("NotasFacturasClienteSQL", "", "app", "NotasFacturasCliente");

        $sql->EliminarDetalleNotaDebitoTemporal($request['tmp_detalle_nota_debito_despacho_cliente_id']);

        $consulta = ModuloGetURL('app', 'NotasFacturasCliente', 'controller', 'CrearNotaDebito', array("factura" => $request['factura']));

        header('Location: ' . $consulta . '');
    }

    /*     * *************************************************************
     * Función para guardar las notas crédito (oficiales) y afectar el saldo de la factura
     * ************************************************************* */

    function GuardarNota()
    {
        $request = $_REQUEST;

        $sql = AutoCarga::factory("NotasFacturasClienteSQL", "", "app", "NotasFacturasCliente");
        $sql_f = AutoCarga::factory("FacturasDespachoSQL", "classes", "app", "FacturasDespacho");
        
        $empresa_id = SessionGetVar("empresa_id");
        $factura_fiscal = SessionGetVar("factura_fiscal");
        $prefijo = SessionGetVar("prefijo");
        

        $nota_credito_despacho_cliente_id = $sql->GuardarNotaCredito($request['nota_credito_despacho_cliente_id'], $request["tipo_factura"]);
        
        
         $concepto_nota = $sql->obtenerConceptoPorNota($nota_credito_despacho_cliente_id['nota_credito_despacho_cliente_id']);
         
        
         
        
        if($concepto_nota["id"] == 1 || is_null($concepto_nota)){
                    $datosFactura = $sql->ObtenerFactura($factura_fiscal, $empresa_id);
                    $sql->GuardarDetalleNotaCredito($nota_credito_despacho_cliente_id['nota_credito_despacho_cliente_id'], $request['nota_credito_despacho_cliente_id'], $request["tipo_factura"]);
					
					$valoresTotalesNotaCredito = $sql->ObtenerValorTotalNotaCredito($nota_credito_despacho_cliente_id['nota_credito_despacho_cliente_id'],$request["tipo_factura"]);
					
                    $sql->EliminarDetallesNotasCreditoTemporal($request['nota_credito_despacho_cliente_id']);
                    $factura_detalle = $sql_f->Detalle_Factura($datosFactura[0]["empresa_id"], $datosFactura[0]["prefijo"], $factura_fiscal);
                    $parametros_retencion = $sql_f->Parametros_Retencion($datosFactura[0]["empresa_id"], $datosFactura[0]["anio_factura"]);

                     foreach ($factura_detalle as $k => $valor) {
                       $subtotal += $valor['subtotal'];
                   }

                   $subtotal = (int) $subtotal;
                   
                    $retencion_fuente = 0; // Credito
                    if ($parametros_retencion['sw_rtf'] == '2' || $parametros_retencion['sw_rtf'] == '3') {      
                        if ($subtotal >= $parametros_retencion['base_rtf']) {
                            $retencion_fuente = $valoresTotalesNotaCredito['valor_total']  * ($datosFactura[0]['porcentaje_rtf'] / 100);
                            if ($retencion_fuente > 0) {
                                $retencion_fuente = ceil($retencion_fuente);
                            }
                        }
                    }

                    $retencion_ica = 0;
                    if ($parametros_retencion['sw_ica'] == '2' || $parametros_retencion['sw_ica'] == '3') {
                        if ($subtotal >= $parametros_retencion['base_ica']) {
                            $retencion_ica = $valoresTotalesNotaCredito['valor_total']  * ($datosFactura[0]['porcentaje_ica'] / 1000);
                            if ($retencion_ica > 0) {
                                $retencion_ica = ceil($retencion_ica);
                            }
                        }
                    }


                    /* echo $subtotal."</br>";
                     echo $retencion_fuente."</br>";
                     echo $retencion_ica. "</br>";
                     echo print_r($datosFactura);*/

                   //$valoFinalNotaCredito = $valoresTotalesNotaCredito['valor_total'] - $valoresTotalesNotaCredito['valor_total_iva'] - $valoresTotalesNotaCredito['valor_total_rtf'] - $valoresTotalesNotaCredito['valor_total_ica'];
                     $valoFinalNotaCredito = $valoresTotalesNotaCredito['valor_total'] + $valoresTotalesNotaCredito['valor_total_iva'] - $retencion_fuente - $retencion_ica;
        } else {
			$valoresTotalesNotaCredito = $sql->ObtenerValorTotalNotaCredito($nota_credito_despacho_cliente_id['nota_credito_despacho_cliente_id'],$request["tipo_factura"]);
            $valoFinalNotaCredito = $valoresTotalesNotaCredito['valor_total'];
        }
        
          $sql->EliminarNotaCreditoTemporal($request['nota_credito_despacho_cliente_id']);
        $tipo = "";
        if (isset($request["tipo"]))
        {
            $tipo = $request["tipo"];
        }

       
        $sql->ActualizarFacturaNotaCredito($valoFinalNotaCredito, $empresa_id, $factura_fiscal, $prefijo, $tipo, $request["tipo_factura"]);    

        //llamado al webservice
        $dusoft_fi = AutoCarga::factory("SincronizacionDusoftFI", "SincronizacionDusoftFI", "", "");
        $resultado_sincronizacion_ws = $dusoft_fi->notas_credito_clientes_fi($nota_credito_despacho_cliente_id['nota_credito_despacho_cliente_id'], $concepto_nota);

        $mensaje_ws = $resultado_sincronizacion_ws['mensaje_ws'];
        $mensaje_bd = $resultado_sincronizacion_ws['mensaje_bd'];


        $vista = AutoCarga::factory("NotasFacturasClienteHTML", "views", "app", "NotasFacturasCliente");

        $action['volver'] = ModuloGetURL("app", "NotasFacturasCliente", "controller", "main");

        $mensaje = "SE HA CREADO LA NOTA CRÉDITO <h3 style='display:inline;'>#{$nota_credito_despacho_cliente_id['nota_credito_despacho_cliente_id']}</h3>  </br>";
         $mensaje .= "<h3 style='color:orange'>SINCRONIZACION FI:</BR>"; 
         $mensaje .= $mensaje_ws . "</h3>";

        $modoNota = "CRÉDITO";

        $url = ModuloGetURL("app", "NotasFacturasCliente", "controller", "VerDetalleNotaCredito", array("nota_credito_despacho_cliente_id" => $nota_credito_despacho_cliente_id, "tipo_factura" => $request["tipo_factura"]));

        $this->salida = $vista->FormaMensajeModulo($action, $mensaje, $modoNota, $url);

        return true;
    }

    /*     * *************************************************************
     * Función para guardar las notas débito (oficiales) y afectar el saldo de la factura
     * ************************************************************* */

    function GuardarNotaDebito()
    {
        $request = $_REQUEST;
        
        $empresa_id = SessionGetVar("empresa_id");
        $factura_fiscal = SessionGetVar("factura_fiscal");
        $prefijo = SessionGetVar("prefijo");
        $sql = AutoCarga::factory("NotasFacturasClienteSQL", "", "app", "NotasFacturasCliente");
        $sql_f = AutoCarga::factory("FacturasDespachoSQL", "classes", "app", "FacturasDespacho");
        $subtotal = 0;
        
        $datosFactura = $sql->ObtenerFactura($factura_fiscal, $empresa_id);

        $nota_debito_despacho_cliente_id = $sql->GuardarNotaDebito($request['nota_debito_despacho_cliente_id'], $datosFactura[0]["tipo_factura"]);

        $sql->GuardarDetalleNotaDebito($nota_debito_despacho_cliente_id['nota_debito_despacho_cliente_id'], $request['nota_debito_despacho_cliente_id'], $datosFactura[0]["tipo_factura"]);

        $sql->EliminarDetallesNotasDebitoTemporal($request['nota_debito_despacho_cliente_id']);

        $sql->EliminarNotaDebitoTemporal($request['nota_debito_despacho_cliente_id']);
        

        $valoresTotalesNotaDebito = $sql->ObtenerValorTotalNotaDebito($nota_debito_despacho_cliente_id['nota_debito_despacho_cliente_id'], $datosFactura[0]["tipo_factura"]);
         $factura_detalle = $sql_f->Detalle_Factura($datosFactura[0]["empresa_id"], $datosFactura[0]["prefijo"], $factura_fiscal);
         $parametros_retencion = $sql_f->Parametros_Retencion($datosFactura[0]["empresa_id"], $datosFactura[0]["anio_factura"]);
         
         
           foreach ($factura_detalle as $k => $valor) {
            $subtotal += $valor['subtotal'];
         }
         
           $retencion_fuente = 0; // Credito
        if ($parametros_retencion['sw_rtf'] == '2' || $parametros_retencion['sw_rtf'] == '3') {      
            if ($subtotal >= $parametros_retencion['base_rtf']) {
                $retencion_fuente = $valoresTotalesNotaDebito['valor_total']  * ($datosFactura[0]['porcentaje_rtf'] / 100);
                if ($retencion_fuente > 0) {
                    $retencion_fuente = (int) $retencion_fuente;
                }
            }
        }

        $retencion_ica = 0;
        if ($parametros_retencion['sw_ica'] == '2' || $parametros_retencion['sw_ica'] == '3') {
            if ($subtotal >= $parametros_retencion['base_ica']) {
                $retencion_ica = $valoresTotalesNotaDebito['valor_total']  * ($datosFactura[0]['porcentaje_ica'] / 1000);
                if ($retencion_ica > 0) {
                    $retencion_ica = (int) $retencion_ica;
                }
            }
        }
        
          /* echo $subtotal."</br>";
         echo $retencion_fuente."</br>";
         echo $retencion_ica. "</br>";
         echo print_r($datosFactura);*/

         
        //echo print_r($$valoresTotalesNotaDebito);
       // $valoFinalNotaDebito = $valoresTotalesNotaDebito['valor_total'] + $valoresTotalesNotaDebito['valor_total_iva'] + $valoresTotalesNotaDebito['valor_total_rtf'] + $valoresTotalesNotaDebito['valor_total_ica'];
        $valoFinalNotaDebito = ($valoresTotalesNotaDebito['valor_total'] + $valoresTotalesNotaDebito['valor_total_iva']) - $retencion_fuente - $retencion_ica;
        
        $sql->ActualizarFacturaNotaDebito($valoFinalNotaDebito, $empresa_id, $factura_fiscal, $prefijo, $datosFactura[0]["tipo_factura"]);
        
          //llamado al webservice
        $dusoft_fi = AutoCarga::factory("SincronizacionDusoftFI", "SincronizacionDusoftFI", "", "");
        $resultado_sincronizacion_ws = $dusoft_fi->notas_debito_cliente_fi($nota_debito_despacho_cliente_id['nota_debito_despacho_cliente_id']);

        $mensaje_ws = $resultado_sincronizacion_ws['mensaje_ws'];
        $mensaje_bd = $resultado_sincronizacion_ws['mensaje_bd'];
        
        

        $vista = AutoCarga::factory("NotasFacturasClienteHTML", "views", "app", "NotasFacturasCliente");

        $action['volver'] = ModuloGetURL("app", "NotasFacturasCliente", "controller", "main");

        $mensaje = "SE HA CREADO LA NOTA DÉBITO  <h3 style='display:inline;'>#{$nota_debito_despacho_cliente_id['nota_debito_despacho_cliente_id']}</h3>  </br>";
        
        $mensaje .= "<h3 style='color:orange'>SINCRONIZACION FI:</BR>"; 
         $mensaje .= $mensaje_ws . "</h3>";

        $modoNota = "DÉBITO";

        $url = ModuloGetURL("app", "NotasFacturasCliente", "controller", "VerDetalleNotaDebito", array("nota_debito_despacho_cliente_id" => $nota_debito_despacho_cliente_id, "tipo_factura" => $datosFactura[0]["tipo_factura"]));

        $this->salida = $vista->FormaMensajeModulo($action, $mensaje, $modoNota, $url);

        return true;
    }

    /*     * *************************************************************
     * Función para visualizar el detalle de las notas crédito
     * ************************************************************* */

    function VerDetalleNotaCredito()    
    {
        $request = $_REQUEST;

        $nota_credito_despacho_cliente_id = $request['nota_credito_despacho_cliente_id']['nota_credito_despacho_cliente_id'];

        $path_app = basename(GetVarConfigaplication('DIR_SIIS')); //Nombre aplicacion
        $ip = $_SERVER['SERVER_ADDR'];
        $imagen = $ruta_archivo = "http://" . $ip . "/APP/" . $path_app . "/images/duana1.jpg";

        $sql = AutoCarga::factory("NotasFacturasClienteSQL", "", "app", "NotasFacturasCliente");
        $sql_f = AutoCarga::factory("FacturasDespachoSQL", "classes", "app", "FacturasDespacho");

        $informacionTerceroFactura = $sql->ObtenerInformacionTerceroFacturaNotaCredito($nota_credito_despacho_cliente_id, $request["tipo_factura"]);
        
        $concepto_nota = $sql->obtenerConceptoPorNota($nota_credito_despacho_cliente_id);
        
        //echo print_r($informacionTerceroFactura);

        $productos = $sql->ObtenerDetalleNotaCredito($nota_credito_despacho_cliente_id, $request["tipo_factura"]);
        $subtotal = 0;
        
        
        $factura_detalle = $sql_f->Detalle_Factura($informacionTerceroFactura["empresa_id"], $informacionTerceroFactura["prefijo"], $informacionTerceroFactura["factura_fiscal"]);
         $parametros_retencion = $sql_f->Parametros_Retencion($informacionTerceroFactura["empresa_id"], $informacionTerceroFactura["anio_factura"]);
         $valoresTotalesNotaCredito = $sql->ObtenerValorTotalNotaCredito($nota_credito_despacho_cliente_id, $request["tipo_factura"]);
         
         if($concepto_nota["id"] == 1 || is_null($concepto_nota)){
                    foreach ($factura_detalle as $k => $valor) {
                     $subtotal += $valor['subtotal'];
                  }

                    $retencion_fuente = 0; // Credito
                 if ($parametros_retencion['sw_rtf'] == '2' || $parametros_retencion['sw_rtf'] == '3') {      
                     if ($subtotal >= $parametros_retencion['base_rtf']) {
                         $retencion_fuente = $valoresTotalesNotaCredito['valor_total']  * ($informacionTerceroFactura['porcentaje_rtf'] / 100);
                         if ($retencion_fuente > 0) {
                             $retencion_fuente = (int) $retencion_fuente;
                         }
                     }
                 }

                 $retencion_ica = 0;
                 if ($parametros_retencion['sw_ica'] == '2' || $parametros_retencion['sw_ica'] == '3') {
                     if ($subtotal >= $parametros_retencion['base_ica']) {
                         $retencion_ica = $valoresTotalesNotaCredito['valor_total']  * ($informacionTerceroFactura['porcentaje_ica'] / 1000);
                         if ($retencion_ica > 0) {
                             $retencion_ica = (int) $retencion_ica;
                         }
                     }
                 }

                 $valoresTotalesNotaCredito['valor_total_rtf'] = $retencion_fuente;
                 $valoresTotalesNotaCredito['valor_total_ica'] = $retencion_ica;

                 //echo print_r($informacionTerceroFactura);

                // $valoFinalNotaCredito = $valoresTotalesNotaCredito['valor_total'] - $valoresTotalesNotaCredito['valor_total_iva'] - $valoresTotalesNotaCredito['valor_total_rtf'] - $valoresTotalesNotaCredito['valor_total_ica'];
                //  $valoFinalNotaCredito = ($valoresTotalesNotaCredito['valor_total'] + $valoresTotalesNotaCredito['valor_total_iva']) - $valoresTotalesNotaCredito['valor_total_rtf'] - $valoresTotalesNotaCredito['valor_total_ica'];
                 $valoFinalNotaCredito = ($valoresTotalesNotaCredito['valor_total'] + $valoresTotalesNotaCredito['valor_total_iva']) - $retencion_fuente - $retencion_ica;
               /*  floatval($total + $iva) - $retencion_ica - $retencion_fuente
                 echo print_r($valoresTotalesNotaCredito);
                 Array ( [valor_total] => 402000.0000 [valor_total_iva] => 320.0000 [valor_total_rtf] => 10050.0000 [valor_total_ica] => 1326.6000 ) 1  */
         } else {
             $valoFinalNotaCredito = $valoresTotalesNotaCredito['valor_total'];
              $valoresTotalesNotaCredito['valor_total_rtf'] = 0;
              $valoresTotalesNotaCredito['valor_total_ica'] = 0;
         }
        $vista = AutoCarga::factory("NotasFacturasClienteHTML", "views", "app", "NotasFacturasCliente");

        $this->salida = $vista->DetalleNotaCredito($nota_credito_despacho_cliente_id, $informacionTerceroFactura, $productos, $valoresTotalesNotaCredito, $valoFinalNotaCredito, $imagen, $concepto_nota);

        return true;
    }

    /*     * *************************************************************
     * Función para visualizar el detalle de las notas débito
     * ************************************************************* */

    function VerDetalleNotaDebito()
    {
        $request = $_REQUEST;

        $nota_debito_despacho_cliente_id = $request['nota_debito_despacho_cliente_id']['nota_debito_despacho_cliente_id'];

        $path_app = basename(GetVarConfigaplication('DIR_SIIS')); //Nombre aplicacion
        $ip = $_SERVER['SERVER_ADDR'];
        $imagen = $ruta_archivo = "http://" . $ip . "/APP/" . $path_app . "/images/duana1.jpg";

        $sql = AutoCarga::factory("NotasFacturasClienteSQL", "", "app", "NotasFacturasCliente");
         $sql_f = AutoCarga::factory("FacturasDespachoSQL", "classes", "app", "FacturasDespacho");

        $informacionTerceroFactura = $sql->ObtenerInformacionTerceroFacturaNotaDebito($nota_debito_despacho_cliente_id, $request["tipo_factura"]);

        $productos = $sql->ObtenerDetalleNotaDebito($nota_debito_despacho_cliente_id, $request["tipo_factura"]);
        $factura_detalle = $sql_f->Detalle_Factura($informacionTerceroFactura["empresa_id"], $informacionTerceroFactura["prefijo"], $informacionTerceroFactura["factura_fiscal"]);
        $parametros_retencion = $sql_f->Parametros_Retencion($informacionTerceroFactura["empresa_id"], $informacionTerceroFactura["anio_factura"]);
         $valoresTotalesNotaDebito = $sql->ObtenerValorTotalNotaDebito($nota_debito_despacho_cliente_id , $request["tipo_factura"]);
        
          foreach ($factura_detalle as $k => $valor) {
            $subtotal += $valor['subtotal'];
         }
         
           $retencion_fuente = 0; // Credito
        if ($parametros_retencion['sw_rtf'] == '2' || $parametros_retencion['sw_rtf'] == '3') {      
            if ($subtotal >= $parametros_retencion['base_rtf']) {
                $retencion_fuente = $valoresTotalesNotaDebito['valor_total']  * ($informacionTerceroFactura['porcentaje_rtf'] / 100);
                if ($retencion_fuente > 0) {
                    $retencion_fuente = (int) $retencion_fuente;
                }
            }
        }

        $retencion_ica = 0;
        if ($parametros_retencion['sw_ica'] == '2' || $parametros_retencion['sw_ica'] == '3') {
            if ($subtotal >= $parametros_retencion['base_ica']) {
                $retencion_ica = $valoresTotalesNotaDebito['valor_total']  * ($informacionTerceroFactura['porcentaje_ica'] / 1000);
                if ($retencion_ica > 0) {
                    $retencion_ica = (int) $retencion_ica;
                }
            }
        }

        $valoresTotalesNotaDebito['valor_total_rtf'] = $retencion_fuente;
        $valoresTotalesNotaDebito['valor_total_ica'] = $retencion_ica;
      

       // $valoFinalNotaDebito = $valoresTotalesNotaDebito['valor_total'] - $valoresTotalesNotaDebito['valor_total_iva'] - $valoresTotalesNotaDebito['valor_total_rtf'] - $valoresTotalesNotaDebito['valor_total_ica'];
       //$valoFinalNotaDebito = ($valoresTotalesNotaDebito['valor_total'] + $valoresTotalesNotaDebito['valor_total_iva']) - $valoresTotalesNotaDebito['valor_total_rtf'] - $valoresTotalesNotaDebito['valor_total_ica'];
         $valoFinalNotaDebito = ($valoresTotalesNotaDebito['valor_total'] + $valoresTotalesNotaDebito['valor_total_iva']) - $retencion_fuente - $retencion_ica;
        $vista = AutoCarga::factory("NotasFacturasClienteHTML", "views", "app", "NotasFacturasCliente");

        $this->salida = $vista->DetalleNotaDebito($nota_debito_despacho_cliente_id, $informacionTerceroFactura, $productos, $valoresTotalesNotaDebito, $valoFinalNotaDebito, $imagen);

        return true;
    }

    /*     * *************************************************************
     * Función para buscar las notas crédito
     * ************************************************************* */

    function BuscarNotaCredito()
    {
        $request = $_REQUEST;

        $factura = $request['factura'];
        

        $empresa_id = SessionGetVar("empresa_id");

        $sql = AutoCarga::factory("NotasFacturasClienteSQL", "", "app", "NotasFacturasCliente");
        $this->SetXajax(array("sincronizar_notas_pendientes_ws_fi"), "app_modules/NotasFacturasCliente/RemoteXajax/Notas_Facturas_ClienteSQL.php", "ISO-8859-1");

        if ($factura)
        {
            $datosNotasCredito = $sql->ObtenerNotasCredito($factura, $empresa_id);
        }

        $vista = AutoCarga::factory("NotasFacturasClienteHTML", "views", "app", "NotasFacturasCliente");

        /* $action['BuscarFactura'] = ModuloGetURL("app", "NotasFacturasCliente", "controller", "BuscarFactura");
          $action['CrearNota'] = ModuloGetURL("app", "NotasFacturasCliente", "controller", "CrearNota", array("factura" => $factura)); */

        $action['volver'] = ModuloGetURL("app", "NotasFacturasCliente", "controller", "main");

        $url = ModuloGetURL("app", "NotasFacturasCliente", "controller", "VerDetalleNotaCredito", array("tipo_factura" => $datosNotasCredito[0]["tipo_factura"] ));

        $this->salida = $vista->BuscadorNotaCredito($action, $factura, $datosNotasCredito, $url, "0");

        return true;
    }

    /*     * *************************************************************
     * Función para buscar las notas débito
     * ************************************************************* */

    function BuscarNotaDebito()
    {
        $request = $_REQUEST;

        $factura = $request['factura'];

        $empresa_id = SessionGetVar("empresa_id");

        $sql = AutoCarga::factory("NotasFacturasClienteSQL", "", "app", "NotasFacturasCliente");
         $this->SetXajax(array("sincronizar_notas_pendientes_ws_fi"), "app_modules/NotasFacturasCliente/RemoteXajax/Notas_Facturas_ClienteSQL.php", "ISO-8859-1");

        if ($factura)
        {
            $datosNotasDebito = $sql->ObtenerNotasDebito($factura, $empresa_id);
        }

        $vista = AutoCarga::factory("NotasFacturasClienteHTML", "views", "app", "NotasFacturasCliente");

        $action['volver'] = ModuloGetURL("app", "NotasFacturasCliente", "controller", "main");

        $url = ModuloGetURL("app", "NotasFacturasCliente", "controller", "VerDetalleNotaDebito", array("tipo_factura" => $datosNotasDebito[0]["tipo_factura"] ));

        $this->salida = $vista->BuscadorNotaDebito($action, $factura, $datosNotasDebito, $url, "1");

        return true;
    }

}