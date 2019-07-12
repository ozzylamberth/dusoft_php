<?php

require_once ('nusoap/lib/nusoap.php');

class SincronizacionDusoftFI {

    // ======== Ruta de servidores de Prueba =========
//    var $http_cuentas_x_pagar = "http://10.0.2.229:8080/SinergiasFinanciero3-ejb/getGestionCuentasxPagarWS/getGestionCuentasxPagarWS?wsdl";
//    var $http_cuentas_x_pagar_cosmitet = "http://10.0.0.57:8080/SinergiasFinanciero3-ejb/getGestionCuentasxPagarWS/getGestionCuentasxPagarWS?wsdl";
//    
//    var $http_gestion_contable = "http://10.0.2.229:8080/SinergiasFinanciero3-ejb/getGestionInformacionContableWS/getGestionInformacionContableWS?wsdl";
//    var $http_nota_debito_proveedor = 'http://10.0.2.229:8080/SinergiasFinanciero3-ejb/gestionNotasDebitoWS/gestionNotasDebitoWS?wsdl';
//    var $http_nota_credito_proveedor = "http://10.0.2.229:8080/SinergiasFinanciero3-ejb/gestionNotasCreditoWS/gestionNotasCreditoWS?wsdl";

    // ======== Ruta de servidores de PRODUCCION =========
    /*var $http_cuentas_x_pagar = "http://10.0.2.204:8080/SinergiasFinanciero3-ejb/getGestionCuentasxPagarWS/getGestionCuentasxPagarWS?wsdl";
    var $http_gestion_contable = "http://10.0.2.204:8080/SinergiasFinanciero3-ejb/getGestionInformacionContableWS/getGestionInformacionContableWS?wsdl";
    var $http_cuentas_x_pagar_cosmitet = "http://10.0.6.184:8080/SinergiasFinanciero3-ejb/getGestionCuentasxPagarWS/getGestionCuentasxPagarWS?wsdl";*/
    /* ================== PROVEEDORES ========================== */
    
    var $http_cuentas_x_pagar = "";
    var $http_cuentas_x_pagar_cosmitet = "";
    var $http_gestion_contable = "";
    var $http_nota_debito_proveedor = "";
    var $http_nota_credito_proveedor = "";
  
    function SincronizacionDusoftFI(){
        $this->http_cuentas_x_pagar = ModuloGetVar('app', 'Inv_FacturacionProveedor', 'http_cuentas_x_pagar');
        $this->http_cuentas_x_pagar_cosmitet = ModuloGetVar('app', 'Inv_FacturacionProveedor', 'http_cuentas_x_pagar_cosmitet');
        $this->http_gestion_contable = ModuloGetVar('app', 'Inv_FacturacionProveedor', 'http_gestion_contable');
        $this->http_nota_debito_proveedor = ModuloGetVar('app', 'Inv_FacturacionProveedor', 'http_nota_debito_proveedor');
        $this->http_nota_credito_proveedor = ModuloGetVar('app', 'Inv_FacturacionProveedor', 'http_nota_credito_proveedor');
   }
    
    function cuentas_x_pagar_fi($_empresa_id, $_codigo_proveedor, $_numero_factura) {


        $empresa_id = $_empresa_id;
        $codigo_proveedor = $_codigo_proveedor;
        $numero_factura = $_numero_factura;

        $sql = AutoCarga::factory("FacturacionProveedorSQL", "classes", "app", "Inv_FacturacionProveedor");
        $sql_aux = AutoCarga::factory("FacturasDespachoSQL", "classes", "app", "FacturasDespacho");

        $factura = $sql->FacturaProveedorCabecera($empresa_id, $codigo_proveedor, $numero_factura);
        $factura = $factura[0];

        //echo "fact";
        //var_dump($factura);

        $factura_detalle = $sql->FacturaProveedorDetalle($empresa_id, $codigo_proveedor, $numero_factura);

        //echo "deta fact";
        //var_dump($factura_detalle);

        $proveedor = $sql->ConsultarTerceroProveedor($codigo_proveedor);
        $proveedor = $proveedor[0];

        //echo "provee";
        //var_dump($proveedor);


        $parametros_retencion = $sql_aux->Parametros_Retencion($factura['empresa_id'], $factura['anio_factura']);


        $prefijo_documento = $factura_detalle[0]['prefijo'];
        $prefijo_fi = $sql->obtener_prefijo_fi($empresa_id, $factura_detalle[0]['prefijo']);
        $prefijo_fi = $prefijo_fi['prefijo_fi'];


        //$http = "http://10.0.2.203:8080/SinergiasFinanciero3-ejb/getGestionCuentasxPagarWS/getGestionCuentasxPagarWS?wsdl";
        //$http = "http://10.0.2.204:8080/SinergiasFinanciero3-ejb/getGestionCuentasxPagarWS/getGestionCuentasxPagarWS?wsdl";
        $funcionWs = "crearCuentaxPagar";
        $url = ($prefijo_documento == "ICC") ? $this->http_cuentas_x_pagar_cosmitet  : $this->http_cuentas_x_pagar;
        $clienteWs = new nusoap_client($url, true);

        $error = "";
        $resultado = "";
        $resultado_sincronizacion = true;
        $msj = array();
        $encabezado = array();
        $asientoscontables = array();

        $codigo_empresa = ($prefijo_documento == "ICC") ? "COS" : "DUA";     
        $codigo_documento = trim($prefijo_fi);
        $numero_factura = $factura['numero_factura'];
        $fecha_factura = date("d/m/Y");
        $estado = "3";
        $identificacion_tercero = $proveedor['tercero_id'];

        $cuenta_contable = $proveedor['cxp_proveedor'];
        if ($prefijo_documento == "ICTC" || $prefijo_documento == "CCT"){
            
            $cuenta_contable = 21051015;
        } else if($prefijo_documento == "ICC") {
            $cuenta_contable = 22050501;
        }else if($prefijo_documento == "CDC") {
            $cuenta_contable = 22050501;
        }


        $plazo_tercero = "1";
        $observacion_encabezado = $factura['observaciones'];
        $numero_radicacion = "0";
        $fecha_radicacion = date("d/m/Y");
        $usuario_creacion = UserGetUID();

        $porc_iva =0;
        $subtotal = 0;
        $iva = 0;
        $total = 0;
        $medicamentos_gravados = 0;
        $medicamentos_no_gravados = 0;
        $insumos_gravados = 0;
        $insumos_no_gravados = 0;
        foreach ($factura_detalle as $k => $valor) {

            if ($valor['sw_medicamento'] == "1") {

                if ($valor['iva'] > 0) {
                    $medicamentos_gravados += $valor['subtotal'];
                } else {
                    $medicamentos_no_gravados += $valor['subtotal'];
                }
            } elseif ($valor['sw_insumos'] == "1") {
                if ($valor['iva'] > 0) {
                    $insumos_gravados += $valor['subtotal'];
                } else {
                    $insumos_no_gravados += $valor['subtotal'];
                }
            }
            if($valor['porc_iva']>0){
             $porc_iva = $valor['porc_iva'];
            }
            $subtotal += $valor['subtotal'];
            $iva += $valor['iva_total'];
            $total += $valor['total'];
        }

//========================= ESTRUCTURA ENCABEZADO WS CxP ======================
        $encabezado['codempresa'] = $codigo_empresa;
        if (empty($codigo_empresa)) {
            $resultado_sincronizacion = false;
            array_push($msj, "El Codigo de la Empresa no esta definido");
        }

        $encabezado['coddocumento'] = trim($codigo_documento);
        if (empty($codigo_documento)) {
            $resultado_sincronizacion = false;
            array_push($msj, "El Prefijo FI, no esta parametrizado para ese documento");
        }

        $encabezado['numerofactura'] = $numero_factura;
        if (empty($numero_factura)) {
            $resultado_sincronizacion = false;
            array_push($msj, "El numero de factura es obligatorio");
        }

        $encabezado['identerceroencabezado'] = $identificacion_tercero;
        if (empty($identificacion_tercero)) {
            $resultado_sincronizacion = false;
            array_push($msj, "El proveedor no posee una identificacion valida");
        }

        $encabezado['cuentaterceroencabezado'] = $cuenta_contable;
        if (empty($cuenta_contable)) {
            $resultado_sincronizacion = false;
            array_push($msj, "No se encuentra una cuenta contable para este proveedor");
        }

        $encabezado['observacionencabezado'] = $observacion_encabezado;
        if (empty($observacion_encabezado)) {
            $resultado_sincronizacion = false;
            array_push($msj, "Debe Ingresar una observacion");
        }

        $encabezado['estadoencabezado'] = $estado;
        $encabezado['fechafactura'] = $fecha_factura;
        $encabezado['fecharadicacion'] = $fecha_radicacion;
        $encabezado['numeroradicacion'] = $numero_radicacion;
        $encabezado['plazotercero'] = $plazo_tercero;
        $encabezado['usuariocreacion'] = $usuario_creacion;


// ========================= ESTRUCTURA ASIENTOS CONTABLES WS CxP ======================
        if ($medicamentos_gravados > 0) {
            $asientoscontables[] = array(
                'codcentrocostoasiento' => '0',
                'codcentroutilidadasiento' => '0',
                'codcuentaasiento' => ($prefijo_documento == "ICC")? '14150501' : '14352005',
                'codlineacostoasiento' => '0',
                'identerceroasiento' => $identificacion_tercero,
                'observacionasiento' => 'No Aplica Observacion Asiento',
                'valorbaseasiento' => '0',
                'valorcreditoasiento' => '0',
                'valordebitoasiento' => $medicamentos_gravados,
                'valortasaasiento' => '0'
            );
        }

        if ($medicamentos_no_gravados > 0) {
            $asientoscontables[] = array(
                'codcentrocostoasiento' => '0',
                'codcentroutilidadasiento' => '0',
                'codcuentaasiento' => ($prefijo_documento == "ICC")? '14150501' : '14352010',
                'codlineacostoasiento' => '0',
                'identerceroasiento' => $identificacion_tercero,
                'observacionasiento' => 'No Aplica Observacion Asiento',
                'valorbaseasiento' => '0',
                'valorcreditoasiento' => '0',
                'valordebitoasiento' => $medicamentos_no_gravados,
                'valortasaasiento' => '0'
            );
        }

        if ($insumos_gravados > 0) {
            $asientoscontables[] = array(
                'codcentrocostoasiento' => '0',
                'codcentroutilidadasiento' => '0',
                'codcuentaasiento' =>  ($prefijo_documento == "ICC")? '14200501' : '14350505',
                'codlineacostoasiento' => '0',
                'identerceroasiento' => $identificacion_tercero,
                'observacionasiento' => 'No Aplica Observacion Asiento',
                'valorbaseasiento' => '0',
                'valorcreditoasiento' => '0',
                'valordebitoasiento' => $insumos_gravados,
                'valortasaasiento' => '0'
            );
        }

        if ($insumos_no_gravados > 0) {
            $asientoscontables[] = array(
                'codcentrocostoasiento' => '0',
                'codcentroutilidadasiento' => '0',
                'codcuentaasiento' =>  ($prefijo_documento == "ICC")? '14200501' : '14350510',
                'codlineacostoasiento' => '0',
                'identerceroasiento' => $identificacion_tercero,
                'observacionasiento' => 'No Aplica Observacion Asiento',
                'valorbaseasiento' => '0',
                'valorcreditoasiento' => '0',
                'valordebitoasiento' => $insumos_no_gravados,
                'valortasaasiento' => '0'
            );
        }

        if ($iva > 0) {

            $asientoscontables[] = array(
                'codcentrocostoasiento' => '0',
                'codcentroutilidadasiento' => '0',
                'codcuentaasiento' =>  ($prefijo_documento == "ICC")? '24081001' : '24080507',
                'codlineacostoasiento' => '0',
                'identerceroasiento' => $identificacion_tercero,
                'observacionasiento' => 'No Aplica Observacion Asiento',
                'valorbaseasiento' => $medicamentos_gravados + $insumos_gravados,
                'valorcreditoasiento' => '0',
                'valordebitoasiento' => $iva,
                'valortasaasiento' => $porc_iva
            );
        }



        $retencion_fuente = 0; // Credito
        if ($parametros_retencion['sw_rtf'] == '2' || $parametros_retencion['sw_rtf'] == '3') {
            if ($subtotal >= $parametros_retencion['base_rtf']) {
                $retencion_fuente = $subtotal * ($factura['porc_rtf'] / 100);
                if ($retencion_fuente > 0) {

                    $codigo_cuenta_contable = "";

                    if ($factura['porc_rtf'] == "0.10") {
                        $codigo_cuenta_contable =  ($prefijo_documento == "ICC")? '23644001' : '23654002';
                    }
                    if ($factura['porc_rtf'] == "1.50") {
                        $codigo_cuenta_contable = "23654003";
                    }
                    
                    if ($factura['porc_rtf'] == "1.00" && $prefijo_documento == "ICC") {
                        $codigo_cuenta_contable = "23654002";
                    }
                    
                    if ($factura['porc_rtf'] == "2.50") {
                        $codigo_cuenta_contable = ($prefijo_documento == "ICC")? '23654016' : '23654004';
                    }
                    if ($factura['porc_rtf'] == "3.50") {
                        $codigo_cuenta_contable = ($prefijo_documento == "ICC")? '23654003' : '23654001';
                    }

                    $asientoscontables[] = array(
                        'codcentrocostoasiento' => '0',
                        'codcentroutilidadasiento' => '0',
                        'codcuentaasiento' => $codigo_cuenta_contable,
                        'codlineacostoasiento' => '0',
                        'identerceroasiento' => $identificacion_tercero,
                        'observacionasiento' => 'No Aplica Observacion Asiento',
                        'valorbaseasiento' => $subtotal,
                        'valorcreditoasiento' => $retencion_fuente,
                        'valordebitoasiento' => '0',
                        'valortasaasiento' => $factura['porc_rtf']
                    );
                }
            }
        }

        $retencion_ica = 0;
        if ($parametros_retencion['sw_ica'] == '2' || $parametros_retencion['sw_ica'] == '3') {
            if ($subtotal >= $parametros_retencion['base_ica']) {
                $retencion_ica = $subtotal * ($factura['porc_ica'] / 1000);
                if ($retencion_ica > 0) {

                    $codigo_cuenta_contable = "";

                    if ($factura['porc_ica'] == "3.30") {
                        $codigo_cuenta_contable =  ($prefijo_documento == "ICC")? '23658503' : '23681005';
                    }
                    if ($factura['porc_ica'] == "5.50") {
                        $codigo_cuenta_contable =  ($prefijo_documento == "ICC")? '23658504' : '23681015';
                    }
                    if ($factura['porc_ica'] == "6.60") {
                        $codigo_cuenta_contable = "23680505";
                    }
                    if ($factura['porc_ica'] == "7.70") {
                        $codigo_cuenta_contable =  ($prefijo_documento == "ICC")? '23658505' : '23681010';
                    }
                    if ($factura['porc_ica'] == "11.00") {
                        $codigo_cuenta_contable = "23681020";
                    }


                    $asientoscontables[] = array(
                        'codcentrocostoasiento' => '0',
                        'codcentroutilidadasiento' => '0',
                        'codcuentaasiento' => $codigo_cuenta_contable,
                        'codlineacostoasiento' => '0',
                        'identerceroasiento' => $identificacion_tercero,
                        'observacionasiento' => 'No Aplica Observacion Asiento',
                        'valorbaseasiento' => $subtotal,
                        'valorcreditoasiento' => $retencion_ica,
                        'valordebitoasiento' => '0',
                        'valortasaasiento' => $factura['porc_ica']
                    );
                }
            }
        }

        $retencion_iva = 0;
        if ($parametros_retencion['sw_reteiva'] == '2' || $parametros_retencion['sw_reteiva'] == '3') {
            if ($iva >= $parametros_retencion['base_reteiva']) {
                $retencion_iva = $iva * ($factura['porc_rtiva'] / 100);
                if ($retencion_iva > 0) {
                    $asientoscontables[] = array(
                        'codcentrocostoasiento' => '0',
                        'codcentroutilidadasiento' => '0',
                        'codcuentaasiento' =>  ($prefijo_documento == "ICC")? '23658001' : '23670520',
                        'codlineacostoasiento' => '0',
                        'identerceroasiento' => $identificacion_tercero,
                        'observacionasiento' => 'No Aplica Observacion Asiento',
                        'valorbaseasiento' => $medicamentos_gravados + $insumos_gravados,
                        'valorcreditoasiento' => $retencion_iva,
                        'valordebitoasiento' => '0',
                        'valortasaasiento' => $factura['porc_rtiva']
                    );
                }
            }
        }

        $total = ((((($total) - $retencion_fuente) - $retencion_ica) - $retencion_iva) - $factura['valor_descuento']);

        /* $asientoscontables[] = array('codcentrocostoasiento' => '0',
          'codcentroutilidadasiento' => '0',
          'codcuentaasiento' => '22050502',
          'codlineacostoasiento' => '0',
          'identerceroasiento' => $identificacion_tercero,
          'observacionasiento' => 'No Aplica Observacion Asiento',
          'valorbaseasiento' => '0',
          'valorcreditoasiento' => $total,
          'valordebitoasiento' => '0',
          'valortasaasiento' => '0'
          ); */

        $parametros = array('encabezadofactura' => $encabezado, 'asientoscontables' => $asientoscontables);


        /*echo "<pre>";
              var_dump($parametros);
              echo "</pre>";

              exit();*/

        if ($resultado_sincronizacion) {

            $resultado_ws = 1;
            $err = $clienteWs->getError();
            if ($err) {
                $error = $err;
            }
            $result = $clienteWs->call($funcionWs, $parametros);
            if ($clienteWs->fault) {
                $resultado = $result;
            } else {
                $err = $clienteWs->getError();
                if ($err) {
                    $error = $err;
                } else {
                    $resultado_ws = 0;
                    $resultado = $result;
                }
            }

            /* echo "<pre>";
              var_dump($resultado);
              echo "</pre>";

              exit(); */

            $mensajeWs = "";
            foreach ($resultado as $key => $value) {
                $mensajeWs = $value['descripcion'];
                if ($value['codigo'] == "1" || $value['estado'] == "true") {
//error
                    $resultado_logs = $sql->registrar_resultado_sincronizacion($codigo_proveedor, $numero_factura, $mensajeWs, '1');
                } else {
//exito
                    $resultado_logs = $sql->registrar_resultado_sincronizacion($codigo_proveedor, $numero_factura, $mensajeWs, '0');
                }
            }

            if ($resultado_ws == 1) {
                $mensajeWs = 'Se ha Generado un error con el Ws de Cuentas x Pagar, no se ha podido establecer conexion';
                $resultado_logs = $sql->registrar_resultado_sincronizacion($codigo_proveedor, $numero_factura, $mensajeWs, '1');
            }

            $mensaje_bd = "Log Registrado Correctamente ";
            if (!$resultado_logs)
                $mensaje_bd = "Se ha Generado un error tratando de Insertar/Actualizar en la Tabla logs_facturacion_proveedores_ws_fi";

            return array('resultado_ws' => $resultado_ws, 'mensaje_ws' => $mensajeWs, 'resultado_bd' => $resultado_logs, 'mensaje_bd' => $mensaje_bd);
        } else {
            // Regitrar Error
            $mensajeWs = implode(",", $msj);

            $resultado_logs = $sql->registrar_resultado_sincronizacion($codigo_proveedor, $numero_factura, $mensajeWs, '1');

            $mensaje_bd = "Log Registrado Correctamente ";
            if (!$resultado_logs)
                $mensaje_bd = "Se ha Generado un error tratando de Insertar/Actualizar en la Tabla logs_facturacion_proveedores_ws_fi";

            return array('resultado_ws' => 1, 'mensaje_ws' => $mensajeWs, 'resultado_bd' => $resultado_logs, 'mensaje_bd' => $mensaje_bd);
        }
    }

    function notas_proveedores_fi($empresa_id, $prefijo, $numero, $devolucion = false, $credito = false) {

        //echo "</br>es credito ".var_dump($credito);

        $sql = AutoCarga::factory("FacturacionProveedorSQL", "classes", "app", "Inv_FacturacionProveedor");
        $sql_query = AutoCarga::factory("CrearNotasFacturasProveedores", "classes", "app", "Inv_NotasFacturasProveedor");
        $sql_aux = AutoCarga::factory("FacturasDespachoSQL", "classes", "app", "FacturasDespacho");
        $sql_movimientos_bodega = AutoCarga::factory("MovBodegasSQL", "classes", "app", "Inv_MovimientosBodegas");


        // Notas Debito o Credito
        $documento_proveedor = $sql_query->obtener_encabezado_nota($empresa_id, $prefijo, $numero);
        $documento_proveedor_detalle = $sql_query->DetalleNota(array('empresa_id' => $empresa_id, 'prefijo' => $prefijo, 'numero' => $numero));

        if ($devolucion) {
            // Devoluciones
            $documento_proveedor = $sql_movimientos_bodega->obtener_encabezado_devolucion_proveedores($empresa_id, $prefijo, $numero);
            $documento_proveedor_detalle = $sql_movimientos_bodega->obtener_detalle_devolucion_proveedores($empresa_id, $prefijo, $numero);
        }

        /* echo "<pre>";
          print_r($documento_proveedor);
          print_r($documento_proveedor_detalle);
          echo "</pre>";
          exit(); */

        $factura = $sql->FacturaProveedorCabecera($empresa_id, $documento_proveedor['codigo_proveedor_id'], $documento_proveedor['numero_factura']);
        $factura = $factura[0];

        /* echo "<pre>";
          print_r($factura);
          echo "</pre>"; */



        $factura_detalle = $sql->FacturaProveedorDetalle($empresa_id, $documento_proveedor['codigo_proveedor_id'], $documento_proveedor['numero_factura']);

        $proveedor = $sql->ConsultarTerceroProveedor($documento_proveedor['codigo_proveedor_id']);
        $proveedor = $proveedor[0];

        $parametros_retencion = $sql_aux->Parametros_Retencion($factura['empresa_id'], $factura['anio_factura']);

        $prefijo_fi = $sql->obtener_prefijo_fi($empresa_id, $prefijo);
        $prefijo_fi = $prefijo_fi['prefijo_fi'];


        $funcionWs = "crearNotaDebito";
        $observacion_encabezado = "NOTA DEBITO PROVEEDOR";

        if ($credito) {
            $funcionWs = 'crearNotaCredito';
            $observacion_encabezado = "NOTA CREDITO PROVEEDOR";
        }

        $url = $this->http_nota_debito_proveedor;


        if ($credito) {
            $url = $this->http_nota_credito_proveedor;
        }

        $clienteWs = new nusoap_client($url, true);

        $error = "";
        $resultado = "";

        $resultado_sincronizacion = true;
        $msj = array();
        $encabezado = array();
        $asientoscontables = array();

        $codigo_empresa = "DUA";
        $codigo_documento = trim($prefijo_fi);
        $numero_nota = $documento_proveedor['numero'];
        $fecha_nota = date("d/m/Y");
        $estado = "4";
        $identificacion_tercero = $proveedor['tercero_id'];
        $cuenta_contable = $proveedor['cxp_proveedor'];
        $plazo_tercero = "1";
        $numero_radicacion = "0";
        $fecha_radicacion = date("d/m/Y");
        $usuario_creacion = UserGetUID();



        $subtotal = 0;
        $subtotalfactura = 0;
        $iva = 0;
        $total = 0;
        $medicamentos_gravados = 0;
        $medicamentos_no_gravados = 0;
        $insumos_gravados = 0;
        $insumos_no_gravados = 0;

        //echo "<pre>";
        //print_r($documento_proveedor_detalle);
        //echo "</pre>";

        foreach ($documento_proveedor_detalle as $k => $valor) {

            if ($valor['sw_medicamento'] == "1") {

                if ($valor['iva'] > 0) {
                    //$medicamentos_gravados += $valor['valor_concepto'];
                    $medicamentos_gravados += $valor['valor'];
                } else {
                    //$medicamentos_no_gravados += $valor['valor_concepto'];
                    $medicamentos_no_gravados += $valor['valor'];
                }
            } elseif ($valor['sw_insumos'] == "1") {
                if ($valor['iva'] > 0) {
                    //$insumos_gravados += $valor['valor_concepto'];
                    $insumos_gravados += $valor['valor'];
                } else {
                    //$insumos_no_gravados += $valor['valor_concepto'];
                    $insumos_no_gravados += $valor['valor'];
                }
            }

            $subtotal += $valor['valor'];
            if ($devolucion) {
                $iva += ($valor['cantidad'] * $valor['valor_unitario']) * ($valor['porcentaje_gravamen'] / 100);
            } else {
                $iva += $valor['iva'];
            }

            //$total += $valor['total'];
        }


        foreach ($factura_detalle as $detalle) {
            $subtotalfactura += $detalle['subtotal'];
        }


//========================= ESTRUCTURA ENCABEZADO WS CxP ======================
        $encabezado['codempresa'] = $codigo_empresa;
        if (empty($codigo_empresa)) {
            $resultado_sincronizacion = false;
            array_push($msj, "El Codigo de la Empresa no esta definido");
        }

        $encabezado['coddocumento'] = trim($codigo_documento);
        if (empty($codigo_documento)) {
            $resultado_sincronizacion = false;
            array_push($msj, "El Prefijo FI, no esta parametrizado para ese documento");
        }

        $encabezado['numerofactura'] = $documento_proveedor['numero_factura'];
        if (empty($numero_nota)) {
            $resultado_sincronizacion = false;
            array_push($msj, "El numero de factura es obligatorio");
        }

        $encabezado['identerceroencabezado'] = $identificacion_tercero;
        if (empty($identificacion_tercero)) {
            $resultado_sincronizacion = false;
            array_push($msj, "El proveedor no posee una identificacion valida");
        }

        $encabezado['cuentaterceroencabezado'] = $cuenta_contable;
        if (empty($cuenta_contable)) {
            $resultado_sincronizacion = false;
            array_push($msj, "No se encuentra una cuenta contable para este proveedor");
        }

        $encabezado['observacionencabezado'] = $observacion_encabezado;
        if (empty($observacion_encabezado)) {
            $resultado_sincronizacion = false;
            array_push($msj, "Debe Ingresar una observacion");
        }
        

        $encabezado['estadoencabezado'] = $estado;
        $encabezado['fecharegistro'] = $fecha_nota;
        $encabezado['fecharadicacion'] = $fecha_radicacion;
        $encabezado['numeroradicacion'] = $numero_radicacion;
        $encabezado['plazotercero'] = $plazo_tercero;
        $encabezado['usuariocreacion'] = $usuario_creacion;
        $encabezado['numerodocumentoencabezado'] = $numero;

        /* echo "<pre>";
          print_r($encabezado);
          echo "</pre>";
          exit(); */

// ========================= ESTRUCTURA ASIENTOS CONTABLES WS CxP ======================
        if ($medicamentos_gravados > 0) {

            if ($credito) {
                $asientoscontables[] = $this->crearAsientoNotaProveedor('14352005', $identificacion_tercero, 'No Aplica Observacion Asiento - MEDICAMENTOS GRAVADOS', '0', '0', $medicamentos_gravados);
            } else {
                $asientoscontables[] = $this->crearAsientoNotaProveedor('14352005', $identificacion_tercero, 'No Aplica Observacion Asiento - MEDICAMENTOS GRAVADOS', '0', $medicamentos_gravados, '0');
            }
        }

        //crearAsientoNotaProveedor($cuenta, $tercero, $observacion, $valorbase, $valorcredito, $valordebito)
        if ($medicamentos_no_gravados > 0) {

            if ($credito) {
                $asientoscontables[] = $this->crearAsientoNotaProveedor('14352010', $identificacion_tercero, 'No Aplica Observacion Asiento - MEDICAMENTOS GRAVADOS', '0', '0', $medicamentos_no_gravados);
            } else {
                $asientoscontables[] = $this->crearAsientoNotaProveedor('14352010', $identificacion_tercero, 'No Aplica Observacion Asiento - MEDICAMENTOS GRAVADOS', '0', $medicamentos_no_gravados, '0');
            }
        }



        if ($insumos_gravados > 0) {

            if ($credito) {
                $asientoscontables[] = $this->crearAsientoNotaProveedor('14350505', $identificacion_tercero, 'No Aplica Observacion Asiento - MEDICAMENTOS GRAVADOS', '0', '0', $insumos_gravados);
            } else {
                $asientoscontables[] = $this->crearAsientoNotaProveedor('14350505', $identificacion_tercero, 'No Aplica Observacion Asiento - MEDICAMENTOS GRAVADOS', '0', $insumos_gravados, '0');
            }
        }

        if ($insumos_no_gravados > 0) {
            if ($credito) {
                $asientoscontables[] = $this->crearAsientoNotaProveedor('14350510', $identificacion_tercero, 'No Aplica Observacion Asiento - MEDICAMENTOS GRAVADOS', '0', '0', $insumos_no_gravados);
            } else {
                $asientoscontables[] = $this->crearAsientoNotaProveedor('14350510', $identificacion_tercero, 'No Aplica Observacion Asiento - MEDICAMENTOS GRAVADOS', '0', $insumos_no_gravados, '0');
            }
        }

        if ($iva > 0) {

            if ($credito) {
                $asientoscontables[] = $this->crearAsientoNotaProveedor('24080507', $identificacion_tercero, 'No Aplica Observacion Asiento - IVA', $medicamentos_gravados + $insumos_gravados, '0', $iva);
            } else {
                $asientoscontables[] = $this->crearAsientoNotaProveedor('24080507', $identificacion_tercero, 'No Aplica Observacion Asiento - IVA', $medicamentos_gravados + $insumos_gravados, $iva, '0');
            }
        }

        $retencion_fuente = 0; // Credito
        if ($parametros_retencion['sw_rtf'] == '2' || $parametros_retencion['sw_rtf'] == '3') {
            if ($subtotalfactura >= $parametros_retencion['base_rtf']) {
                $retencion_fuente = $subtotal * ($factura['porc_rtf'] / 100);
                if ($retencion_fuente > 0) {

                    $codigo_cuenta_contable = "";

                    if ($factura['porc_rtf'] == "0.10") {
                        $codigo_cuenta_contable = "23654002";
                    }
                    if ($factura['porc_rtf'] == "1.50") {
                        $codigo_cuenta_contable = "23654003";
                    }
                    if ($factura['porc_rtf'] == "2.50") {
                        $codigo_cuenta_contable = "23654004";
                    }
                    if ($factura['porc_rtf'] == "3.50") {
                        $codigo_cuenta_contable = "23654001";
                    }


                    if ($credito) {
                        $asientoscontables[] = $this->crearAsientoNotaProveedor($codigo_cuenta_contable, $identificacion_tercero, 'No Aplica Observacion Asiento - RTE FTE', $subtotal, $retencion_fuente, '0');
                    } else {
                        $asientoscontables[] = $this->crearAsientoNotaProveedor($codigo_cuenta_contable, $identificacion_tercero, 'No Aplica Observacion Asiento - RTE FTE', $subtotal, '0', $retencion_fuente);
                    }
                }
            }
        }

        $retencion_ica = 0;
        if ($parametros_retencion['sw_ica'] == '2' || $parametros_retencion['sw_ica'] == '3') {
            if ($subtotalfactura >= $parametros_retencion['base_ica']) {
                $retencion_ica = $subtotal * ($factura['porc_ica'] / 1000);
                if ($retencion_ica > 0) {

                    $codigo_cuenta_contable = "";

                    if ($factura['porc_ica'] == "3.30") {
                        $codigo_cuenta_contable = "23681005";
                    }
                    if ($factura['porc_ica'] == "5.50") {
                        $codigo_cuenta_contable = "23681015";
                    }
                    if ($factura['porc_ica'] == "6.60") {
                        $codigo_cuenta_contable = "23680505";
                    }
                    if ($factura['porc_ica'] == "7.70") {
                        $codigo_cuenta_contable = "23681010";
                    }
                    if ($factura['porc_ica'] == "11.00") {
                        $codigo_cuenta_contable = "23681020";
                    }


                    if ($credito) {
                        $asientoscontables[] = $this->crearAsientoNotaProveedor($codigo_cuenta_contable, $identificacion_tercero, 'No Aplica Observacion Asiento - ICA', $subtotal, $retencion_ica, '0');
                    } else {
                        $asientoscontables[] = $this->crearAsientoNotaProveedor($codigo_cuenta_contable, $identificacion_tercero, 'No Aplica Observacion Asiento - ICA', $subtotal, '0', $retencion_ica);
                    }
                }
            }
        }

        $retencion_iva = 0;
        if ($parametros_retencion['sw_reteiva'] == '2' || $parametros_retencion['sw_reteiva'] == '3') {
            if ($iva >= $parametros_retencion['base_reteiva']) {
                $retencion_iva = $iva * ($factura['porc_rtiva'] / 100);
                if ($retencion_iva > 0) {

                    if ($credito) {
                        $asientoscontables[] = $this->crearAsientoNotaProveedor('23670520', $identificacion_tercero, 'No Aplica Observacion Asiento - RTE IVA', $medicamentos_gravados + $insumos_gravados, $retencion_iva, '0');
                    } else {
                        $asientoscontables[] = $this->crearAsientoNotaProveedor('23670520', $identificacion_tercero, 'No Aplica Observacion Asiento - RTE IVA', $medicamentos_gravados + $insumos_gravados, '0', $retencion_iva);
                    }
                }
            }
        }

        $total = (((($medicamentos_gravados + $medicamentos_no_gravados + $insumos_gravados + $insumos_no_gravados + $iva) - $retencion_fuente) - $retencion_ica) - $retencion_iva);

        //crearAsientoNotaProveedor($cuenta, $tercero, $observacion, $valorbase, $valorcredito, $valordebito)
        /*  $impusto_cree = $subtotal * 0.0040;

          if ($impusto_cree > 0) {


          if($credito){
          $asientoscontables[] = $this->crearAsientoNotaProveedor('13551901', $identificacion_tercero, 'No Aplica Observacion Asiento - Cree', '0', ceil($impusto_cree), '0');
          $asientoscontables[] = $this->crearAsientoNotaProveedor('23659501', $identificacion_tercero, 'No Aplica Observacion Asiento - Cree', '0', '0', ceil($impusto_cree) );
          } else {
          $asientoscontables[] = $this->crearAsientoNotaProveedor('13551901', $identificacion_tercero, 'No Aplica Observacion Asiento - Cree', '0', '0', ceil($impusto_cree));
          $asientoscontables[] = $this->crearAsientoNotaProveedor('23659501', $identificacion_tercero, 'No Aplica Observacion Asiento - Cree', '0', ceil($impusto_cree), '0' );
          }

          } */

        //crearAsientoNotaProveedor($cuenta, $tercero, $observacion, $valorbase, $valorcredito, $valordebito)

        if ($credito) {
            $asientoscontables[] = $this->crearAsientoNotaProveedor($cuenta_contable, $identificacion_tercero, 'No Aplica Observacion Asiento - Total', '0', $total, '0');
        } else {
            $asientoscontables[] = $this->crearAsientoNotaProveedor($cuenta_contable, $identificacion_tercero, 'No Aplica Observacion Asiento - Total', '0', '0', $total);
        }


        $parametros = array('encabezadonota' => $encabezado, 'asientoscontablesnota' => $asientoscontables);

        //echo $subtotal. "</br></br>";

        /* echo "<pre>";
          print_r($asientoscontables);
          echo "</pre></br></br>"; */

        //echo print_r($parametros_retencion);
        //echo $url. "</br></br>";

        if ($resultado_sincronizacion) {

            $resultado_ws = 1;
            $err = $clienteWs->getError();
            if ($err) {
                $error = $err;
            }
            $result = $clienteWs->call($funcionWs, $parametros);



            // echo $funcionWs . "</br></br>";
            //  echo "</br> result from ws: ". print_r($result);
            if ($clienteWs->fault) {
                $resultado = $result;
            } else {
                $err = $clienteWs->getError();
                if ($err) {
                    $error = $err;
                } else {
                    $resultado_ws = 0;
                    $resultado = $result;
                }
            }

            /* echo "<pre>";
              var_dump($resultado);
              echo "</pre>";

              exit(); */

            $mensajeWs = "";
            foreach ($resultado as $key => $value) {
                $mensajeWs = $value['descripcion'];
                if ($value['codigo'] == "1" || $value['estado'] == "true") {
                    //error
                    $resultado_logs = $sql->registrar_resultado_sincronizacion_notas($factura['codigo_proveedor_id'], $factura['numero_factura'], $prefijo, $numero_nota, $mensajeWs, '1');
                } else {
                    //exito
                    $resultado_logs = $sql->registrar_resultado_sincronizacion_notas($factura['codigo_proveedor_id'], $factura['numero_factura'], $prefijo, $numero_nota, $mensajeWs, '0');
                }
            }

            if ($resultado_ws == 1) {
                $mensajeWs = 'Se ha Generado un error con el Ws de Cuentas x Pagar, no se ha podido establecer conexion';
                $resultado_logs = $sql->registrar_resultado_sincronizacion_notas($factura['codigo_proveedor_id'], $factura['numero_factura'], $prefijo, $numero_nota, $mensajeWs, '1');
            }

            $mensaje_bd = "Log Registrado Correctamente ";
            if (!$resultado_logs)
                $mensaje_bd = "Se ha Generado un error tratando de Insertar/Actualizar en la Tabla logs_facturacion_proveedores_ws_fi";

            /* echo "<pre>";
              print_r(array('resultado_ws' => $resultado_ws, 'mensaje_ws' => $mensajeWs, 'resultado_bd' => $resultado_logs, 'mensaje_bd' => $mensaje_bd));
              echo "</pre>"; */
            return array('resultado_ws' => $resultado_ws, 'mensaje_ws' => $mensajeWs, 'resultado_bd' => $resultado_logs, 'mensaje_bd' => $mensaje_bd);
        } else {
            // Regitrar Error
            $mensajeWs = implode(",", $msj);

            $resultado_logs = $sql->registrar_resultado_sincronizacion_notas($factura['codigo_proveedor_id'], $factura['numero_factura'], $prefijo, $numero_nota, $mensajeWs, '1');

            $mensaje_bd = "Log Registrado Correctamente ";
            if (!$resultado_logs)
                $mensaje_bd = "Se ha Generado un error tratando de Insertar/Actualizar en la Tabla logs_facturacion_proveedores_ws_fi";

            /* echo "<pre>";
              print_r(array('resultado_ws' => 1, 'mensaje_ws' => $mensajeWs, 'resultado_bd' => $resultado_logs, 'mensaje_bd' => $mensaje_bd));
              echo "</pre>"; */
            return array('resultado_ws' => 1, 'mensaje_ws' => $mensajeWs, 'resultado_bd' => $resultado_logs, 'mensaje_bd' => $mensaje_bd);
        }
    }

    function crearAsientoNotaProveedor($cuenta, $tercero, $observacion, $valorbase, $valorcredito, $valordebito) {


        return array(
            'codcentrocostoasiento' => '0',
            'codcentroutilidadasiento' => '0',
            'codcuentaasiento' => $cuenta,
            'codlineacostoasiento' => '0',
            'identerceroasiento' => $tercero,
            'observacionasiento' => $observacion,
            'valorbaseasiento' => $valorbase,
            'valorcreditoasiento' => $valorcredito,
            'valordebitoasiento' => $valordebito,
            'valortasaasiento' => '0'
        );
    }

    /* ================== CLIENTES ========================== */

    function facturas_talonario_fi($empresa_id, $prefijo, $numero_factura) {
        $sql = AutoCarga::factory("app_Facturacion_Conceptos", "classes", "app", "CajaGeneral");
        $sql_d = AutoCarga::factory("FacturasDespachoSQL", "classes", "app", "FacturasDespacho");
        $sql_aux = AutoCarga::factory("ContratacionProductosClienteSQL", "classes", "app", "ContratacionProductosCliente");
        $factura = $sql->obtener_factura_talonario($empresa_id, $prefijo, $numero_factura);
        $cliente = $sql_aux->ConsultarTercero_Contrato($empresa_id, $factura['tercero_id'], $factura['tipo_id_tercero']);
        $parametros_retencion = $sql_d->Parametros_Retencion($factura['empresa_id'], $factura['anio_factura']);

        $prefijo_fi = $prefijo;

        $funcionWs = "crearInformacionContable";
        $clienteWs = new nusoap_client($this->http_gestion_contable, true);

        $error = "";
        $resultado = "";

        $resultado_sincronizacion = true;
        $msj = array();
        $encabezado = array();
        $asientoscontables = array();

        $codigo_empresa = "DUA";
        $codigo_documento = trim($prefijo_fi);
        $numero_documento = $factura['factura_fiscal'];
        // $fecha_documento = date("d/m/Y");
        $fecha_documento = $factura['fecha_factura'];
        $estado = "4";
        $identificacion_tercero = $factura['tercero_id'];
        $observacion_encabezado = "Factura " . $prefijo . " #" . $numero_documento;


        $usuario_creacion = UserGetUID();
        $cuenta_contable = $cliente['cuenta_contable']; //'13050505';

        $subtotal = $factura['total_factura'] - $factura['gravamen'];	
        $iva = (int) $factura['gravamen'];
        $porc=($factura['total_factura']/$subtotal)-1;
        $porc_iva=0;
        if($porc>0){
         $porc_iva=$porc;
        }
        $total = 0;

        $medicamentos_gravados = 0;
        $costo_medicamentos_gravados = 0;
        $inventario_medicamentos_gravados = 0;

        $medicamentos_no_gravados = 0;
        $costo_medicamentos_no_gravados = 0;
        $inventario_medicamentos_no_gravados = 0;

        $insumos_gravados = 0;
        $costo_insumos_gravados = 0;
        $inventario_insumos_gravados = 0;

        $insumos_no_gravados = 0;
        $costo_insumos_no_gravados = 0;
        $inventario_insumos_no_gravados = 0;


        //no tiene detalle

        $subtotal = (int) $subtotal;
        $total = (int) $total;
        $baseiva = 0;

        $encabezado['codempresa'] = $codigo_empresa;
        if (empty($codigo_empresa)) {
            $resultado_sincronizacion = false;
            array_push($msj, "El Codigo de la Empresa no esta definido");
        }

        $encabezado['coddocumentoencabezado'] = trim($codigo_documento);
        if (empty($codigo_documento)) {
            $resultado_sincronizacion = false;
            array_push($msj, "El Prefijo FI, no esta parametrizado para ese documento");
        }

        $encabezado['numerodocumentoencabezado'] = $numero_documento;
        if (empty($numero_documento)) {
            $resultado_sincronizacion = false;
            array_push($msj, "El numero de factura es obligatorio");
        }

        $encabezado['identerceroencabezado'] = $identificacion_tercero;
        if (empty($identificacion_tercero)) {
            $resultado_sincronizacion = false;
            array_push($msj, "El proveedor no posee una identificacion valida");
        }

        $encabezado['observacionencabezado'] = $observacion_encabezado;
        if (empty($observacion_encabezado)) {
            $resultado_sincronizacion = false;
            array_push($msj, "Debe Ingresar una observacion");
        }

        if (empty($cuenta_contable)) {
            $resultado_sincronizacion = false;
            array_push($msj, "El Cliente NO tiene una Cuenta Contable Asociada");
        }

        $encabezado['estadoencabezado'] = $estado;
        $encabezado['fecharegistroencabezado'] = $fecha_documento;
        $encabezado['usuariocreacion'] = $usuario_creacion;
        $encabezado['tipotercero'] = 3;


        // ========================= ESTRUCTURA ASIENTOS CONTABLES WS CxP ======================
        $medicamentos_gravados = (int) $medicamentos_gravados;

        //se comento porque no sabemos que es medicamento grabado y no grabado
        /*  if ($iva > 0) {
          $asientoscontables[] = array(
          'codcentrocostoasiento' => '1012',
          'codcentroutilidadasiento' => ($prefijo == 'FE') ? '03' : '02',
          'codcuentaasiento' => '41353803',
          'codlineacostoasiento' => '0',
          'identerceroasiento' => $identificacion_tercero,
          'observacionasiento' => 'No Aplica Observacion Asiento - Medicamentos Gravados',
          'valorbaseasiento' => '0',
          'valorcreditoasiento' => $iva,
          'valordebitoasiento' => '0',
          'valortasaasiento' => '0'
          );
          } */

        $costo_medicamentos_gravados = (int) $costo_medicamentos_gravados;
        if ($costo_medicamentos_gravados > 0) {
            $asientoscontables[] = array(
                'codcentrocostoasiento' => '1012',
                'codcentroutilidadasiento' => ($prefijo == 'FE') ? '03' : '02',
                'codcuentaasiento' => '61353803',
                'codlineacostoasiento' => '01',
                'identerceroasiento' => $identificacion_tercero,
                'observacionasiento' => 'No Aplica Observacion Asiento - Costo Medicamentos Gravados',
                'valorbaseasiento' => '0',
                'valorcreditoasiento' => '0',
                'valordebitoasiento' => '0',
                'valortasaasiento' => '0'
            );
        }

        $inventario_medicamentos_gravados = (int) $inventario_medicamentos_gravados;
        if ($inventario_medicamentos_gravados > 0) {
            $asientoscontables[] = array(
                'codcentrocostoasiento' => '1012',
                'codcentroutilidadasiento' => ($prefijo == 'FE') ? '03' : '02',
                'codcuentaasiento' => '14352005',
                'codlineacostoasiento' => '01',
                'identerceroasiento' => $identificacion_tercero,
                'observacionasiento' => 'No Aplica Observacion Asiento - Inventarios Medicamentos Gravados',
                'valorbaseasiento' => '0',
                'valorcreditoasiento' => '0',
                'valordebitoasiento' => '0',
                'valortasaasiento' => '0'
            );
        }


        //si no tiene iva va el valor total



        $costo_medicamentos_no_gravados = (int) $costo_medicamentos_no_gravados;
        if ($costo_medicamentos_no_gravados > 0) {
            $asientoscontables[] = array(
                'codcentrocostoasiento' => '1012',
                'codcentroutilidadasiento' => ($prefijo == 'FE') ? '03' : '02',
                'codcuentaasiento' => '61353804',
                'codlineacostoasiento' => '01',
                'identerceroasiento' => $identificacion_tercero,
                'observacionasiento' => 'No Aplica Observacion Asiento - Costo Medicamentos NO gravados',
                'valorbaseasiento' => '0',
                'valorcreditoasiento' => '0',
                'valordebitoasiento' => '0',
                'valortasaasiento' => '0'
            );
        }

        $inventario_medicamentos_no_gravados = (int) $inventario_medicamentos_no_gravados;
        if ($inventario_medicamentos_no_gravados > 0) {
            $asientoscontables[] = array(
                'codcentrocostoasiento' => '1012',
                'codcentroutilidadasiento' => ($prefijo == 'FE') ? '03' : '02',
                'codcuentaasiento' => '14352010',
                'codlineacostoasiento' => '01',
                'identerceroasiento' => $identificacion_tercero,
                'observacionasiento' => 'No Aplica Observacion Asiento - Invetario Medicamentos NO gravados',
                'valorbaseasiento' => '0',
                'valorcreditoasiento' => '0',
                'valordebitoasiento' => '0',
                'valortasaasiento' => '0'
            );
        }

        $insumos_gravados = (int) $insumos_gravados;
        if ($insumos_gravados > 0) {
            $asientoscontables[] = array(
                'codcentrocostoasiento' => '1012',
                'codcentroutilidadasiento' => ($prefijo == 'FE') ? '03' : '02',
                'codcuentaasiento' => '41353201',
                'codlineacostoasiento' => '01',
                'identerceroasiento' => $identificacion_tercero,
                'observacionasiento' => 'No Aplica Observacion Asiento - Insumos gravados',
                'valorbaseasiento' => '0',
                'valorcreditoasiento' => '0',
                'valordebitoasiento' => '0',
                'valortasaasiento' => '0'
            );
        }

        $costo_insumos_gravados = (int) $costo_insumos_gravados;
        if ($costo_insumos_gravados > 0) {
            $asientoscontables[] = array(
                'codcentrocostoasiento' => '1012',
                'codcentroutilidadasiento' => ($prefijo == 'FE') ? '03' : '02',
                'codcuentaasiento' => '61353201',
                'codlineacostoasiento' => '01',
                'identerceroasiento' => $identificacion_tercero,
                'observacionasiento' => 'No Aplica Observacion Asiento - Costo Insumos gravados',
                'valorbaseasiento' => '0',
                'valorcreditoasiento' => '0',
                'valordebitoasiento' => '0',
                'valortasaasiento' => '0'
            );
        }

        $inventario_insumos_gravados = (int) $inventario_insumos_gravados;
        if ($inventario_insumos_gravados > 0) {
            $asientoscontables[] = array(
                'codcentrocostoasiento' => '1012',
                'codcentroutilidadasiento' => ($prefijo == 'FE') ? '03' : '02',
                'codcuentaasiento' => '14350505',
                'codlineacostoasiento' => '01',
                'identerceroasiento' => $identificacion_tercero,
                'observacionasiento' => 'No Aplica Observacion Asiento - Inventarios Insumos gravados',
                'valorbaseasiento' => '0',
                'valorcreditoasiento' => '0',
                'valordebitoasiento' => '0',
                'valortasaasiento' => '0'
            );
        }

        $insumos_no_gravados = (int) $insumos_no_gravados;
        if ($insumos_no_gravados > 0) {
            $asientoscontables[] = array(
                'codcentrocostoasiento' => '1012',
                'codcentroutilidadasiento' => ($prefijo == 'FE') ? '03' : '02',
                'codcuentaasiento' => '41353202',
                'codlineacostoasiento' => '01',
                'identerceroasiento' => $identificacion_tercero,
                'observacionasiento' => 'No Aplica Observacion Asiento - Insumos NO gravados',
                'valorbaseasiento' => '0',
                'valorcreditoasiento' => '0',
                'valordebitoasiento' => '0',
                'valortasaasiento' => '0'
            );
        }

        $costo_insumos_no_gravados = (int) $costo_insumos_no_gravados;
        if ($costo_insumos_no_gravados > 0) {
            $asientoscontables[] = array(
                'codcentrocostoasiento' => '1012',
                'codcentroutilidadasiento' => ($prefijo == 'FE') ? '03' : '02',
                'codcuentaasiento' => '61353202',
                'codlineacostoasiento' => '01',
                'identerceroasiento' => $identificacion_tercero,
                'observacionasiento' => 'No Aplica Observacion Asiento - Costo Insumos NO gravados',
                'valorbaseasiento' => '0',
                'valorcreditoasiento' => '0',
                'valordebitoasiento' => '0',
                'valortasaasiento' => '0'
            );
        }


        $inventario_insumos_no_gravados = (int) $inventario_insumos_no_gravados;
        if ($inventario_insumos_no_gravados > 0) {
            $asientoscontables[] = array(
                'codcentrocostoasiento' => '1012',
                'codcentroutilidadasiento' => ($prefijo == 'FE') ? '03' : '02',
                'codcuentaasiento' => '14350510',
                'codlineacostoasiento' => '01',
                'identerceroasiento' => $identificacion_tercero,
                'observacionasiento' => 'No Aplica Observacion Asiento - Inventarios Insumos NO gravados',
                'valorbaseasiento' => '0',
                'valorcreditoasiento' => '0',
                'valordebitoasiento' => '0',
                'valortasaasiento' => '0'
            );
        }


        //gravamen
        if ($iva > 0) {
            $iva = (int) $iva;
            $asientoscontables[] = array(
                'codcentrocostoasiento' => '1012',
                'codcentroutilidadasiento' => ($prefijo == 'FE') ? '03' : '02',
                'codcuentaasiento' => '24080604',
                'codlineacostoasiento' => '01',
                'identerceroasiento' => $identificacion_tercero,
                'observacionasiento' => 'No Aplica Observacion Asiento - IVA',
                'valorbaseasiento' => $iva,
                'valorcreditoasiento' => $iva,
                'valordebitoasiento' => '0',
                'valortasaasiento' => $porc_iva
            );

            $baseiva = (int) ($iva / 0.19);
            $asientoscontables[] = array(
                'codcentrocostoasiento' => '1012',
                'codcentroutilidadasiento' => ($prefijo == 'FE') ? '03' : '02',
                'codcuentaasiento' => '41353803',
                'codlineacostoasiento' => '01',
                'identerceroasiento' => $identificacion_tercero,
                'observacionasiento' => 'No Aplica Observacion Asiento - IVA',
                'valorbaseasiento' => '0',
                'valorcreditoasiento' => $baseiva,
                'valordebitoasiento' => '0',
                'valortasaasiento' => $porc_iva
            );
        }




        //****
        $retencion_fuente = 0; // Credito
        if ($parametros_retencion['sw_rtf'] == '2' || $parametros_retencion['sw_rtf'] == '3') {
            if ($subtotal >= $parametros_retencion['base_rtf']) {
                $retencion_fuente = $subtotal * ($factura['porcentaje_rtf'] / 100);
                if ($retencion_fuente > 0) {

                    $retencion_fuente = (int) $retencion_fuente;

                    $codigo_cuenta_contable = "13551505";

                    $asientoscontables[] = array(
                        'codcentrocostoasiento' => '1012',
                        'codcentroutilidadasiento' => ($prefijo == 'FE') ? '03' : '02',
                        'codcuentaasiento' => $codigo_cuenta_contable,
                        'codlineacostoasiento' => '01',
                        'identerceroasiento' => $identificacion_tercero,
                        'observacionasiento' => 'No Aplica Observacion Asiento  - RTE FTE',
                        'valorbaseasiento' => $subtotal,
                        'valorcreditoasiento' => '0',
                        'valordebitoasiento' => $retencion_fuente,
                        'valortasaasiento' => $factura['porcentaje_rtf']
                    );
                }
            }
        }

        $retencion_ica = 0;
        if ($parametros_retencion['sw_ica'] == '2' || $parametros_retencion['sw_ica'] == '3') {
            if ($subtotal >= $parametros_retencion['base_ica']) {
                $retencion_ica = $subtotal * ($factura['porcentaje_ica'] / 1000);
                if ($retencion_ica > 0) {

                    $retencion_ica = (int) $retencion_ica;

                    $codigo_cuenta_contable = "";

                    if ($factura['porcentaje_ica'] == "3.30" || $factura['porcentaje_ica'] == "11.0000") {
                        $codigo_cuenta_contable = "13551805";
                    }
                    if ($factura['porcentaje_ica'] == "5.40") {
                        $codigo_cuenta_contable = "13551808";
                    }
                    if ($factura['porcentaje_ica'] == "6.00") {
                        $codigo_cuenta_contable = "13551807";
                    }
                    
                    if ($factura['porcentaje_ica'] == "7.00") {
                        $codigo_cuenta_contable = "13551809";
                    }

                    if ($factura['porcentaje_ica'] == "10.0000") {
                        $codigo_cuenta_contable = "13551810";
                    }


                    $asientoscontables[] = array(
                        'codcentrocostoasiento' => '1012',
                        'codcentroutilidadasiento' => ($prefijo == 'FE') ? '03' : '02',
                        'codcuentaasiento' => $codigo_cuenta_contable,
                        'codlineacostoasiento' => '01',
                        'identerceroasiento' => $identificacion_tercero,
                        'observacionasiento' => 'No Aplica Observacion Asiento - RTE ICA',
                        'valorbaseasiento' => $subtotal,
                        'valorcreditoasiento' => '0',
                        'valordebitoasiento' => $retencion_ica,
                        'valortasaasiento' => $factura['porcentaje_ica']
                    );
                }
            }
        }

        $impusto_cree = (int) ceil($subtotal * 0.004);

        if ($impusto_cree > 0) {

            $impusto_cree = (int) $impusto_cree;

            $asientoscontables[] = array(
                'codcentrocostoasiento' => '1012',
                'codcentroutilidadasiento' => ($prefijo == 'FE') ? '03' : '02',
                'codcuentaasiento' => '13551901',
                'codlineacostoasiento' => '01',
                // 'identerceroasiento' => $identificacion_tercero, //830080649
                'identerceroasiento' => 830080649,
                'observacionasiento' => 'No Aplica Observacion Asiento - CREE',
                'valorbaseasiento' => $subtotal,
                'valorcreditoasiento' => '0',
                //'valordebitoasiento' => floatval($impusto_cree),
                'valordebitoasiento' => $impusto_cree,
                'valortasaasiento' => '0.004'
            );

            $asientoscontables[] = array(
                'codcentrocostoasiento' => '1012',
                'codcentroutilidadasiento' => ($prefijo == 'FE') ? '03' : '02',
                'codcuentaasiento' => '23659501',
                'codlineacostoasiento' => '01',
                'identerceroasiento' => $identificacion_tercero, // 800197268
                'identerceroasiento' => 800197268,
                'observacionasiento' => 'No Aplica Observacion Asiento - CREE',
                'valorbaseasiento' => $subtotal,
                //'valorcreditoasiento' => floatval($impusto_cree),
                'valorcreditoasiento' => $impusto_cree,
                'valordebitoasiento' => '0',
                'valortasaasiento' => '0.004'
            );
        }


        //  $total = (((($subtotal) - $retencion_fuente) - $retencion_ica) - $retencion_iva);
        $total = (((($subtotal + $iva) - $retencion_fuente) - $retencion_ica) - $retencion_iva);


        $asientoscontables[] = array(
            'codcentrocostoasiento' => '1012',
            'codcentroutilidadasiento' => ($prefijo == 'FE') ? '03' : '02',
            'codcuentaasiento' => $cuenta_contable,
            'codlineacostoasiento' => '01',
            'identerceroasiento' => $identificacion_tercero,
            'observacionasiento' => 'No Aplica Observacion Asiento - Total',
            'valorbaseasiento' => '0',
            'valorcreditoasiento' => '0',
            //'valordebitoasiento' => ceil(floatval($total)),
            'valordebitoasiento' => (int) $total,
            'valortasaasiento' => $porc_iva
        );



         if (($subtotal - $baseiva) > 0) {
            $cosmitet='830023202';
            $medicamentos_no_gravados = (int) $medicamentos_no_gravados;
            $codcuentaasiento=$identificacion_tercero==$cosmitet?'41651005':'41353804';
            $asientoscontables[] = array(
                'codcentrocostoasiento' => '1012',
                'codcentroutilidadasiento' => ($prefijo == 'FE') ? '03' : '02',
                'codcuentaasiento' => $codcuentaasiento,
                'codlineacostoasiento' => '01',
                'identerceroasiento' => $identificacion_tercero,
                'observacionasiento' => 'No Aplica Observacion Asiento - Medicamentos NO gravados',
                'valorbaseasiento' => '0',
                'valorcreditoasiento' => $subtotal - $baseiva,
                'valordebitoasiento' => '0',
                'valortasaasiento' => '0'
            );
        }

        $parametros = array('encabezadofactura' => $encabezado, 'asientoscontables' => $asientoscontables);

        /* echo print_r($parametros);
        exit();*/

        if ($resultado_sincronizacion) {

            $resultado_ws = 1;
            $err = $clienteWs->getError();
            if ($err) {
                $error = $err;
                //echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
            }
            $result = $clienteWs->call($funcionWs, $parametros);
            if ($clienteWs->fault) {
                $resultado = $result;
                //echo '<h2>Fault</h2><pre>';
                //print_r($result);
                //echo '</pre>';
            } else {
                $err = $clienteWs->getError();
                if ($err) {
                    $error = $err;
                    //echo '<h2>Error</h2><pre>' . $err . '</pre>';
                } else {
                    $resultado_ws = 0;
                    $resultado = $result;
                }
            }

            // Display the request and response
            //echo '<h2>Request</h2>';
            //echo '<pre>' . htmlspecialchars($clienteWs->request, ENT_QUOTES) . '</pre>';
            //echo '<h2>Response</h2>';
            //echo '<pre>' . htmlspecialchars($clienteWs->response, ENT_QUOTES) . '</pre>';
            // Display the debug messages
            //echo '<h2>Debug</h2>';
            //echo '<pre>' . htmlspecialchars($clienteWs->debug_str, ENT_QUOTES) . '</pre>';

            /* echo "<pre>";
              var_dump($resultado);
              echo "</pre>";

              exit(); */

            $mensajeWs = "";
            foreach ($resultado as $key => $value) {
                $mensajeWs = $value['descripcion'];
                if ($value['codigo'] == "1" || $value['estado'] == "true") {
                    //error
                    $resultado_logs = $sql_d->registrar_resultado_sincronizacion($prefijo, $numero_factura, $mensajeWs, '1');
                } else {
                    //exito
                    $resultado_logs = $sql_d->registrar_resultado_sincronizacion($prefijo, $numero_factura, $mensajeWs, '0');
                }
            }

            if ($resultado_ws == 1) {
                $mensajeWs = 'Se ha Generado un error con el Ws de Facturas Ventas, no se ha podido establecer conexion';
                $resultado_logs = $sql_d->registrar_resultado_sincronizacion($prefijo, $numero_factura, $mensajeWs, '1');
            }

            $mensaje_bd = "Log Registrado Correctamente ";
            if (!$resultado_logs)
                $mensaje_bd = "Se ha Generado un error tratando de Insertar/Actualizar en la Tabla logs_facturacion_clientes_ws_fi";

            /*  echo print_r($result). "</br></br>";
              echo print_r($asientoscontables). "</br></br>"; */

            return array('resultado_ws' => $resultado_ws, 'mensaje_ws' => $mensajeWs, 'resultado_bd' => $resultado_logs, 'mensaje_bd' => $mensaje_bd);
        } else {
            // Regitrar Error
            $mensajeWs = implode(",", $msj);

            $resultado_logs = $sql_d->registrar_resultado_sincronizacion($prefijo, $numero_factura, $mensajeWs, '1');

            $mensaje_bd = "Log Registrado Correctamente ";
            if (!$resultado_logs)
                $mensaje_bd = "Se ha Generado un error tratando de Insertar/Actualizar en la Tabla logs_facturacion_clientes_ws_fi";

            /* echo print_r($result). "</br></br>";
              echo print_r($asientoscontables). "</br></br>"; */

            return array('resultado_ws' => 1, 'mensaje_ws' => $mensajeWs, 'resultado_bd' => $resultado_logs, 'mensaje_bd' => $mensaje_bd);
        }
    }

    function facturas_venta_fi($empresa_id, $prefijo, $numero_factura) {

        //echo "<pre>";

        $sql = AutoCarga::factory("FacturasDespachoSQL", "classes", "app", "FacturasDespacho");
        $sql_aux = AutoCarga::factory("ContratacionProductosClienteSQL", "classes", "app", "ContratacionProductosCliente");


        $factura = $sql->obtener_factura_despacho($empresa_id, $prefijo, $numero_factura);

        $factura_detalle = $sql->Detalle_Factura($empresa_id, $prefijo, $numero_factura);

        $cliente = $sql_aux->ConsultarTercero_Contrato($empresa_id, $factura['tercero_id'], $factura['tipo_id_tercero']);

        $parametros_retencion = $sql->Parametros_Retencion($factura['empresa_id'], $factura['anio_factura']);

        $prefijo_fi = $sql->obtener_prefijo_fi($empresa_id, $factura_detalle[0]['prefijo']);
        $prefijo_fi = $prefijo_fi['prefijo_fi'];

        $funcionWs = "crearInformacionContable";
        $clienteWs = new nusoap_client($this->http_gestion_contable, true);

        $error = "";
        $resultado = "";

        $resultado_sincronizacion = true;
        $msj = array();
        $encabezado = array();
        $asientoscontables = array();

        $codigo_empresa = "DUA";
        $codigo_documento = trim($prefijo_fi);
        $numero_documento = $factura['factura_fiscal'];
        // $fecha_documento = date("d/m/Y");
        $fecha_documento = $factura['fecha_factura'];
        $estado = "4";
        $identificacion_tercero = $factura['tercero_id'];
        $observacion_encabezado = $factura['observaciones'];

        if (empty($observacion_encabezado) || is_null($observacion_encabezado))
            $observacion_encabezado = "NO TIENE OBSERVACION";

        $usuario_creacion = UserGetUID();
        $cuenta_contable = $cliente['cuenta_contable']; //'13050505';

        $subtotal = 0;
        $iva = 0;
        $total = 0;

        $medicamentos_gravados = 0;
        $costo_medicamentos_gravados = 0;
        $inventario_medicamentos_gravados = 0;

        $medicamentos_no_gravados = 0;
        $costo_medicamentos_no_gravados = 0;
        $inventario_medicamentos_no_gravados = 0;

        $insumos_gravados = 0;
        $costo_insumos_gravados = 0;
        $inventario_insumos_gravados = 0;

        $insumos_no_gravados = 0;
        $costo_insumos_no_gravados = 0;
        $inventario_insumos_no_gravados = 0;
        $porc_iva=0;

        foreach ($factura_detalle as $k => $valor) {

            if ($valor['sw_medicamento'] == "1") {

                if ($valor['iva'] > 0) {
                    //$medicamentos_gravados += ceil($valor['subtotal']);
                    //$costo_medicamentos_gravados += ceil($valor['costo']);
                    //$inventario_medicamentos_gravados += ceil($valor['costo']);
                    $medicamentos_gravados += $valor['subtotal'];
                    $costo_medicamentos_gravados += $valor['costo'];
                    $inventario_medicamentos_gravados += $valor['costo'];
                    if($valor['porc_iva'] > 0){
                    $porc_iva =$valor['porc_iva'];
                    }
                } else {
                    //$medicamentos_no_gravados += ceil($valor['subtotal']) ;
                    //$costo_medicamentos_no_gravados += ceil($valor['costo']);
                    //$inventario_medicamentos_no_gravados += ceil($valor['costo']);
                    $medicamentos_no_gravados += $valor['subtotal'];
                    $costo_medicamentos_no_gravados += $valor['costo'];
                    $inventario_medicamentos_no_gravados += $valor['costo'];
                }
            } elseif ($valor['sw_insumos'] == "1") {
                if ($valor['iva'] > 0) {
                    //$insumos_gravados += ceil($valor['subtotal']);
                    //$costo_insumos_gravados += ceil($valor['costo']);
                    //$inventario_insumos_gravados += ceil($valor['costo']);

                    $insumos_gravados += $valor['subtotal'];
                    $costo_insumos_gravados += $valor['costo'];
                    $inventario_insumos_gravados += $valor['costo'];
                    
                    if($valor['porc_iva'] > 0){
                    $porc_iva =$valor['porc_iva'];
                    }
                } else {
                    //$insumos_no_gravados += ceil($valor['subtotal']);
                    //$costo_insumos_no_gravados += ceil($valor['costo']);
                    //$inventario_insumos_no_gravados += ceil($valor['costo']);
                    $insumos_no_gravados += $valor['subtotal'];
                    $costo_insumos_no_gravados += $valor['costo'];
                    $inventario_insumos_no_gravados += $valor['costo'];
                }
            }

            //$subtotal += ceil($valor['subtotal']);
            //$iva += ceil($valor['iva_total']);
            //$total += ceil($valor['total']);

            $subtotal += $valor['subtotal'];
            $iva += $valor['iva_total'];
            $total += $valor['total'];
        }

        //$subtotal = ceil($subtotal);
        //$total = ceil($total);       


        /* =============================== Estructura WS de facturas_venta_fi =============================== */
        $encabezado['codempresa'] = $codigo_empresa;
        if (empty($codigo_empresa)) {
            $resultado_sincronizacion = false;
            array_push($msj, "El Codigo de la Empresa no esta definido");
        }

        $encabezado['coddocumentoencabezado'] = trim($codigo_documento);
        if (empty($codigo_documento)) {
            $resultado_sincronizacion = false;
            array_push($msj, "El Prefijo FI, no esta parametrizado para ese documento");
        }

        $encabezado['numerodocumentoencabezado'] = $numero_documento;
        if (empty($numero_documento)) {
            $resultado_sincronizacion = false;
            array_push($msj, "El numero de factura es obligatorio");
        }

        $encabezado['identerceroencabezado'] = $identificacion_tercero;
        if (empty($identificacion_tercero)) {
            $resultado_sincronizacion = false;
            array_push($msj, "El proveedor no posee una identificacion valida");
        }

        $encabezado['observacionencabezado'] = $observacion_encabezado;
        if (empty($observacion_encabezado)) {
            $resultado_sincronizacion = false;
            array_push($msj, "Debe Ingresar una observacion");
        }

        if (empty($cuenta_contable)) {
            $resultado_sincronizacion = false;
            array_push($msj, "El Cliente NO tiene una Cuenta Contable Asociada");
        }

        $encabezado['estadoencabezado'] = $estado;
        $encabezado['fecharegistroencabezado'] = $fecha_documento;
        $encabezado['usuariocreacion'] = $usuario_creacion;
        $encabezado['tipotercero'] = 3;


        // ========================= ESTRUCTURA ASIENTOS CONTABLES WS CxP ======================
        $medicamentos_gravados = (int) ($medicamentos_gravados);
        if ($medicamentos_gravados > 0) {
            $asientoscontables[] = array(
                'codcentrocostoasiento' => '1012',
                'codcentroutilidadasiento' => '03',
                'codcuentaasiento' => '41353803',
                'codlineacostoasiento' => '01',
                'identerceroasiento' => $identificacion_tercero,
                'observacionasiento' => 'No Aplica Observacion Asiento - Medicamentos Gravados',
                'valorbaseasiento' => '0',
                'valorcreditoasiento' => ($medicamentos_gravados),
                'valordebitoasiento' => '0',
                'valortasaasiento' => '0'
            );
        }

        $costo_medicamentos_gravados = (int) ($costo_medicamentos_gravados);
        if ($costo_medicamentos_gravados > 0) {
            $asientoscontables[] = array(
                'codcentrocostoasiento' => '1012',
                'codcentroutilidadasiento' => '03',
                'codcuentaasiento' => '61353803',
                'codlineacostoasiento' => '01',
                'identerceroasiento' => '830080649',//$identificacion_tercero,
                'observacionasiento' => 'No Aplica Observacion Asiento - Costo Medicamentos Gravados',
                'valorbaseasiento' => '0',
                'valorcreditoasiento' => '0',
                'valordebitoasiento' => ($costo_medicamentos_gravados),
                'valortasaasiento' => '0'
            );
        }

        $inventario_medicamentos_gravados = (int) ($inventario_medicamentos_gravados);
        if ($inventario_medicamentos_gravados > 0) {
            $asientoscontables[] = array(
                'codcentrocostoasiento' => '1012',
                'codcentroutilidadasiento' => '03',
                'codcuentaasiento' => '14352005',
                'codlineacostoasiento' => '01',
                'identerceroasiento' => $identificacion_tercero,
                'observacionasiento' => 'No Aplica Observacion Asiento - Inventarios Medicamentos Gravados',
                'valorbaseasiento' => '0',
                'valorcreditoasiento' => ($inventario_medicamentos_gravados),
                'valordebitoasiento' => '0',
                'valortasaasiento' => '0'
            );
        }

        $medicamentos_no_gravados = (int) ($medicamentos_no_gravados);
        if ($medicamentos_no_gravados > 0) {
            $asientoscontables[] = array(
                'codcentrocostoasiento' => '1012',
                'codcentroutilidadasiento' => '03',
                'codcuentaasiento' => '41353804',
                'codlineacostoasiento' => '01',
                'identerceroasiento' => $identificacion_tercero,
                'observacionasiento' => 'No Aplica Observacion Asiento - Medicamentos NO gravados',
                'valorbaseasiento' => '0',
                'valorcreditoasiento' => ($medicamentos_no_gravados),
                'valordebitoasiento' => '0',
                'valortasaasiento' => '0'
            );
        }

        $costo_medicamentos_no_gravados = (int) ($costo_medicamentos_no_gravados);
        if ($costo_medicamentos_no_gravados > 0) {
            $asientoscontables[] = array(
                'codcentrocostoasiento' => '1012',
                'codcentroutilidadasiento' => '03',
                'codcuentaasiento' => '61353804',
                'codlineacostoasiento' => '01',
                'identerceroasiento' => '830080649',
                'observacionasiento' => 'No Aplica Observacion Asiento - Costo Medicamentos NO gravados',
                'valorbaseasiento' => '0',
                'valorcreditoasiento' => '0',
                'valordebitoasiento' => ($costo_medicamentos_no_gravados),
                'valortasaasiento' => '0'
            );
        }

        $inventario_medicamentos_no_gravados = (int) ($inventario_medicamentos_no_gravados);
        if ($inventario_medicamentos_no_gravados > 0) {
            $asientoscontables[] = array(
                'codcentrocostoasiento' => '1012',
                'codcentroutilidadasiento' => '03',
                'codcuentaasiento' => '14352010',
                'codlineacostoasiento' => '01',
                'identerceroasiento' => '830080649',
                'observacionasiento' => 'No Aplica Observacion Asiento - Invetario Medicamentos NO gravados',
                'valorbaseasiento' => '0',
                'valorcreditoasiento' => ($inventario_medicamentos_no_gravados),
                'valordebitoasiento' => '0',
                'valortasaasiento' => '0'
            );
        }

        $insumos_gravados = (int) ($insumos_gravados);
        if ($insumos_gravados > 0) {
            $asientoscontables[] = array(
                'codcentrocostoasiento' => '1012',
                'codcentroutilidadasiento' => '03',
                'codcuentaasiento' => '41353201',
                'codlineacostoasiento' => '01',
                'identerceroasiento' => $identificacion_tercero,
                'observacionasiento' => 'No Aplica Observacion Asiento - Insumos gravados',
                'valorbaseasiento' => '0',
                'valorcreditoasiento' => ($insumos_gravados),
                'valordebitoasiento' => '0',
                'valortasaasiento' => '0'
            );
        }

        $costo_insumos_gravados = (int) ($costo_insumos_gravados);
        if ($costo_insumos_gravados > 0) {
            $asientoscontables[] = array(
                'codcentrocostoasiento' => '1012',
                'codcentroutilidadasiento' => '03',
                'codcuentaasiento' => '61353201',
                'codlineacostoasiento' => '01',
                'identerceroasiento' => '830080649',//$identificacion_tercero,
                'observacionasiento' => 'No Aplica Observacion Asiento - Costo Insumos gravados',
                'valorbaseasiento' => '0',
                'valorcreditoasiento' => '0',
                'valordebitoasiento' => ($costo_insumos_gravados),
                'valortasaasiento' => '0'
            );
        }

        $inventario_insumos_gravados = (int) ($inventario_insumos_gravados);
        if ($inventario_insumos_gravados > 0) {
            $asientoscontables[] = array(
                'codcentrocostoasiento' => '1012',
                'codcentroutilidadasiento' => '03',
                'codcuentaasiento' => '14350505',
                'codlineacostoasiento' => '01',
                'identerceroasiento' => '830080649',//$identificacion_tercero,
                'observacionasiento' => 'No Aplica Observacion Asiento - Inventarios Insumos gravados',
                'valorbaseasiento' => '0',
                'valorcreditoasiento' => ($inventario_insumos_gravados),
                'valordebitoasiento' => '0',
                'valortasaasiento' => '0'
            );
        }

        $insumos_no_gravados = (int) ($insumos_no_gravados);
        if ($insumos_no_gravados > 0) {
            $asientoscontables[] = array(
                'codcentrocostoasiento' => '1012',
                'codcentroutilidadasiento' => '03',
                'codcuentaasiento' => '41353202',
                'codlineacostoasiento' => '01',
                'identerceroasiento' => $identificacion_tercero,
                'observacionasiento' => 'No Aplica Observacion Asiento - Insumos NO gravados',
                'valorbaseasiento' => '0',
                'valorcreditoasiento' => ($insumos_no_gravados),
                'valordebitoasiento' => '0',
                'valortasaasiento' => '0'
            );
        }

        $costo_insumos_no_gravados = (int) ($costo_insumos_no_gravados);
        if ($costo_insumos_no_gravados > 0) {
            $asientoscontables[] = array(
                'codcentrocostoasiento' => '1012',
                'codcentroutilidadasiento' => '03',
                'codcuentaasiento' => '61353202',
                'codlineacostoasiento' => '01',
                'identerceroasiento' => '830080649',//$identificacion_tercero,
                'observacionasiento' => 'No Aplica Observacion Asiento - Costo Insumos NO gravados',
                'valorbaseasiento' => '0',
                'valorcreditoasiento' => '0',
                'valordebitoasiento' => ($costo_insumos_no_gravados),
                'valortasaasiento' => '0'
            );
        }


        $inventario_insumos_no_gravados = (int) ($inventario_insumos_no_gravados);
        if ($inventario_insumos_no_gravados > 0) {
            $asientoscontables[] = array(
                'codcentrocostoasiento' => '1012',
                'codcentroutilidadasiento' => '03',
                'codcuentaasiento' => '14350510',
                'codlineacostoasiento' => '01',
                'identerceroasiento' => '830080649',//$identificacion_tercero,
                'observacionasiento' => 'No Aplica Observacion Asiento - Inventarios Insumos NO gravados',
                'valorbaseasiento' => '0',
                'valorcreditoasiento' => ($inventario_insumos_no_gravados),
                'valordebitoasiento' => '0',
                'valortasaasiento' => '0'
            );
        }

        if ($iva > 0) {
            $iva = (int) ($iva);
            $asientoscontables[] = array(
                'codcentrocostoasiento' => '1012',
                'codcentroutilidadasiento' => '03',
                'codcuentaasiento' => '24080604',
                'codlineacostoasiento' => '01',
                'identerceroasiento' => $identificacion_tercero,
                'observacionasiento' => 'No Aplica Observacion Asiento - IVA',
                'valorbaseasiento' =>  ($medicamentos_gravados + $insumos_gravados),
                'valorcreditoasiento' => ($iva),
                'valordebitoasiento' => '0',
                'valortasaasiento' => $porc_iva
            );
        }



        $retencion_fuente = 0; // Credito
        if ($parametros_retencion['sw_rtf'] == '2' || $parametros_retencion['sw_rtf'] == '3') {
            if ($subtotal >= $parametros_retencion['base_rtf']) {
                $retencion_fuente = $subtotal * ($factura['porcentaje_rtf'] / 100);
                if ($retencion_fuente > 0) {

                    $retencion_fuente = (int) ($retencion_fuente);

                    $codigo_cuenta_contable = "13551505";

                    $asientoscontables[] = array(
                        'codcentrocostoasiento' => '1012',
                        'codcentroutilidadasiento' => '03',
                        'codcuentaasiento' => $codigo_cuenta_contable,
                        'codlineacostoasiento' => '01',
                        'identerceroasiento' => $identificacion_tercero,
                        'observacionasiento' => 'No Aplica Observacion Asiento  - RTE FTE',
                        'valorbaseasiento' => (int) ($subtotal),
                        'valorcreditoasiento' => '0',
                        'valordebitoasiento' => ($retencion_fuente),
                        'valortasaasiento' => $factura['porcentaje_rtf']
                    );
                }
            }
        }
 
        $retencion_ica = 0;
        if ($parametros_retencion['sw_ica'] == '2' || $parametros_retencion['sw_ica'] == '3') {
            if ($subtotal >= $parametros_retencion['base_ica']) {
                $retencion_ica = $subtotal * ($factura['porcentaje_ica'] / 1000);
                if ($retencion_ica > 0) {

                    $retencion_ica = (int) ($retencion_ica);

                    $codigo_cuenta_contable = "";

                    if ($factura['porcentaje_ica'] == "3.30" || $factura['porcentaje_ica'] == "11.0") {
                        $codigo_cuenta_contable = "13551805";
                    }
                    if ($factura['porcentaje_ica'] == "5.40") {
                        $codigo_cuenta_contable = "13551808";
                    }
                    if ($factura['porcentaje_ica'] == "6.00") {
                        $codigo_cuenta_contable = "13551807";
                    }
                    if ($factura['porcentaje_ica'] == "7.00") {
                        $codigo_cuenta_contable = "13551805";
                    }



                    $asientoscontables[] = array(
                        'codcentrocostoasiento' => '1012',
                        'codcentroutilidadasiento' => '03',
                        'codcuentaasiento' => $codigo_cuenta_contable,
                        'codlineacostoasiento' => '01',
                        'identerceroasiento' => $identificacion_tercero,
                        'observacionasiento' => 'No Aplica Observacion Asiento - RTE ICA',
                        'valorbaseasiento' => (int) ($subtotal),
                        'valorcreditoasiento' => '0',
                        'valordebitoasiento' => ($retencion_ica),
                        'valortasaasiento' => $factura['porcentaje_ica']
                    );
                }
            }
        }

        $impusto_cree = (int) $subtotal * 0.004;
    /**
    * +Descripcion: añadiendo nit tercero Duana, 
    *               nit tercero Dian
    * @fecha: 22/10/2015
    **/
        $nitTerceroDuana = '830080649';
    $nitTerceroDian = '800197268';
        if ($impusto_cree >= 1) {

            $impusto_cree = (int) ($impusto_cree);

            $asientoscontables[] = array(
                'codcentrocostoasiento' => '1012',
                'codcentroutilidadasiento' => '03',
                'codcuentaasiento' => '13551901',
                'codlineacostoasiento' => '01',
                'identerceroasiento' => $nitTerceroDuana,
                'observacionasiento' => 'No Aplica Observacion Asiento - CREE',
                'valorbaseasiento' => $subtotal,//************************************************DEBE LLEVAR UN VALOR
                'valorcreditoasiento' => '0',
                //'valordebitoasiento' => floatval($impusto_cree),
                'valordebitoasiento' => ($impusto_cree),
                'valortasaasiento' => '0.004'//$factura['porcentaje_cree']//*****************cambio se coloco el porcentje cree
            );

            $asientoscontables[] = array(
                'codcentrocostoasiento' => '1012',
                'codcentroutilidadasiento' => '03',
                'codcuentaasiento' => '23659501',
                'codlineacostoasiento' => '01',
                'identerceroasiento' => $nitTerceroDian,
                'observacionasiento' => 'No Aplica Observacion Asiento - CREE',
                'valorbaseasiento' => $subtotal, //************************************************DEBE LLEVAR UN VALOR
                //'valorcreditoasiento' => floatval($impusto_cree),
                'valorcreditoasiento' => ($impusto_cree),
                'valordebitoasiento' => '0',
                'valortasaasiento' => '0.004'//$factura['porcentaje_cree']//*****************cambio se coloco el porcentje cree
            );
        }


        //$total = (((($total) - $retencion_fuente) - $retencion_ica) - $retencion_iva);
        $total = (((($medicamentos_gravados + $medicamentos_no_gravados + $insumos_gravados + $insumos_no_gravados + $iva) - $retencion_fuente) - $retencion_ica) - $retencion_iva);


        $asientoscontables[] = array(
            'codcentrocostoasiento' => '1012',
            'codcentroutilidadasiento' => '03',
            'codcuentaasiento' => $cuenta_contable,
            'codlineacostoasiento' => '01',
            'identerceroasiento' => $identificacion_tercero,
            'observacionasiento' => 'No Aplica Observacion Asiento - Total',
            'valorbaseasiento' => '0',
            'valorcreditoasiento' => '0',
            //'valordebitoasiento' => ceil(floatval($total)),
            'valordebitoasiento' => (int) ($total),
            'valortasaasiento' => '0'
        );

        $parametros = array('encabezadofactura' => $encabezado, 'asientoscontables' => $asientoscontables);

      /*   print_r($parametros);
          exit();*/

        /*echo "<pre>";
        print_r($parametros);        
        echo "<pre>";
        $sub = $medicamentos_gravados + $medicamentos_no_gravados + $insumos_gravados + $insumos_no_gravados;
        print_r($sub);
        echo "</pre>";
        exit();*/




        if ($resultado_sincronizacion) {

            $resultado_ws = 1;
            $err = $clienteWs->getError();
            if ($err) {
                $error = $err;
                //echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
            }
            $result = $clienteWs->call($funcionWs, $parametros);
            if ($clienteWs->fault) {
                $resultado = $result;
                //echo '<h2>Fault</h2><pre>';
                //print_r($result);
                //echo '</pre>';
            } else {
                $err = $clienteWs->getError();
                if ($err) {
                    $error = $err;
                    //echo '<h2>Error</h2><pre>' . $err . '</pre>';
                } else {
                    $resultado_ws = 0;
                    $resultado = $result;
                }
            }

            // Display the request and response
            //echo '<h2>Request</h2>';
            //echo '<pre>' . htmlspecialchars($clienteWs->request, ENT_QUOTES) . '</pre>';
            //echo '<h2>Response</h2>';
            //echo '<pre>' . htmlspecialchars($clienteWs->response, ENT_QUOTES) . '</pre>';
            // Display the debug messages
            //echo '<h2>Debug</h2>';
            //echo '<pre>' . htmlspecialchars($clienteWs->debug_str, ENT_QUOTES) . '</pre>';

            /* echo "<pre>";
              var_dump($resultado);
              echo "</pre>";

              exit(); */

            $mensajeWs = "";
            foreach ($resultado as $key => $value) {
                $mensajeWs = $value['descripcion'];
                if ($value['codigo'] == "1" || $value['estado'] == "true") {
                    //error
                    $resultado_logs = $sql->registrar_resultado_sincronizacion($prefijo, $numero_factura, $mensajeWs, '1');
                } else {
                    //exito
                    $resultado_logs = $sql->registrar_resultado_sincronizacion($prefijo, $numero_factura, $mensajeWs, '0');
                }
            }

            if ($resultado_ws == 1) {
                $mensajeWs = 'Se ha Generado un error con el Ws de Facturas Ventas, no se ha podido establecer conexion';
                $resultado_logs = $sql->registrar_resultado_sincronizacion($prefijo, $numero_factura, $mensajeWs, '1');
            }

            $mensaje_bd = "Log Registrado Correctamente ";
            if (!$resultado_logs)
                $mensaje_bd = "Se ha Generado un error tratando de Insertar/Actualizar en la Tabla logs_facturacion_clientes_ws_fi";

            return array('resultado_ws' => $resultado_ws, 'mensaje_ws' => $mensajeWs, 'resultado_bd' => $resultado_logs, 'mensaje_bd' => $mensaje_bd);
        } else {
            // Regitrar Error
            $mensajeWs = implode(",", $msj);

            $resultado_logs = $sql->registrar_resultado_sincronizacion($prefijo, $numero_factura, $mensajeWs, '1');

            $mensaje_bd = "Log Registrado Correctamente ";
            if (!$resultado_logs)
                $mensaje_bd = "Se ha Generado un error tratando de Insertar/Actualizar en la Tabla logs_facturacion_clientes_ws_fi";

            return array('resultado_ws' => 1, 'mensaje_ws' => $mensajeWs, 'resultado_bd' => $resultado_logs, 'mensaje_bd' => $mensaje_bd);
        }
    }

    function notas_credito_clientes_fi($nota_debito_despacho_cliente_id, $concepto) {

        $sql = AutoCarga::factory("NotasFacturasClienteSQL", "classes", "app", "NotasFacturasCliente");
        $sql_f = AutoCarga::factory("FacturasDespachoSQL", "classes", "app", "FacturasDespacho");
        $sql_aux = AutoCarga::factory("ContratacionProductosClienteSQL", "classes", "app", "ContratacionProductosCliente");


        $encabezadonotaDebito = $sql->obtenerEncabezadoNotaCreditoCliente($nota_debito_despacho_cliente_id);
        /*  echo "encabezaod";
          echo print_r($encabezadonotaDebito["fecha_registro"]);
          echo "</br>"; */

        $anioactual = date("Y");

        //echo $anioactual . " " .$encabezadonotaDebito["fecha_registro"];



        $cliente = $sql_aux->ConsultarTercero_Contrato($encabezadonotaDebito["empresa_id"], $encabezadonotaDebito['tercero_id'], $encabezadonotaDebito['tipo_id_tercero']);
        $factura_detalle = $sql_f->Detalle_Factura($encabezadonotaDebito['empresa_id'], $encabezadonotaDebito["prefijo"], $encabezadonotaDebito["factura_fiscal"]);
        $parametros_retencion = $sql_f->Parametros_Retencion($encabezadonotaDebito['empresa_id'], $encabezadonotaDebito['anio_factura']);
        /* echo "tercero";  
          echo print_r($cliente);
          echo "</br>";
          exit(); */
        /* echo "detalle";  
          echo print_r($nota_detalle);
          echo "</br>";
          exit(); */



        /* echo "parametros_retencion";  
          echo print_r($parametros_retencion);
          echo "</br>";
          exit(); */

        $prefijo_fi = "NS";

        if (!is_null($encabezadonotaDebito["prefijo_devolucion"])) {
            $prefijo_fi = $sql_f->obtener_prefijo_fi($encabezadonotaDebito['empresa_id'], $encabezadonotaDebito['prefijo_devolucion']);
            $prefijo_fi = $prefijo_fi['prefijo_fi'];
        }

        $funcionWs = "crearInformacionContable";
        $clienteWs = new nusoap_client($this->http_gestion_contable, true);

        $error = "";
        $resultado = "";

        $resultado_sincronizacion = true;
        $msj = array();
        $encabezado = array();
        $asientoscontables = array();

        $codigo_empresa = "DUA";
        $codigo_documento = trim($prefijo_fi);
        $numero_documento = $nota_debito_despacho_cliente_id;
        //$fecha_documento = date("d/m/Y");
        $fecha_documento = $encabezadonotaDebito["fecha_nota"];
        $estado = "3";
        $identificacion_tercero = trim($encabezadonotaDebito['tercero_id']);
        $observacion_encabezado = "SIN OBSERVACION";
        $usuario_creacion = UserGetUID();
        $cuenta_contable = $cliente['cuenta_contable']; //'13050505';
        //$subtotal = 0;
        $iva = 0;
        $total = 0;

        $medicamentos_gravados = 0;
        $costo_medicamentos_gravados = 0;
        $inventario_medicamentos_gravados = 0;

        $medicamentos_no_gravados = 0;
        $costo_medicamentos_no_gravados = 0;
        $inventario_medicamentos_no_gravados = 0;

        $insumos_gravados = 0;
        $costo_insumos_gravados = 0;
        $inventario_insumos_gravados = 0;

        $insumos_no_gravados = 0;
        $costo_insumos_no_gravados = 0;
        $inventario_insumos_no_gravados = 0;
        $retencion_ica = 0;
        $retencion_fuente = 0;
        $subtotal = 0;


        $encabezado['codempresa'] = $codigo_empresa;
        if (empty($codigo_empresa)) {
            $resultado_sincronizacion = false;
            array_push($msj, "El Codigo de la Empresa no esta definido");
        }

        $encabezado['coddocumentoencabezado'] = trim($codigo_documento);
        if (empty($codigo_documento)) {
            $resultado_sincronizacion = false;
            array_push($msj, "El Prefijo FI, no esta parametrizado para ese documento");
        }

        $encabezado['numerodocumentoencabezado'] = $numero_documento;
        if (empty($numero_documento)) {
            $resultado_sincronizacion = false;
            array_push($msj, "El numero de factura es obligatorio");
        }

        $encabezado['identerceroencabezado'] = $identificacion_tercero;
        if (empty($identificacion_tercero)) {
            $resultado_sincronizacion = false;
            array_push($msj, "El proveedor no posee una identificacion valida");
        }

        $encabezado['observacionencabezado'] = $observacion_encabezado;
        if (empty($observacion_encabezado)) {
            $resultado_sincronizacion = false;
            array_push($msj, "Debe Ingresar una observacion");
        }

        if (empty($cuenta_contable)) {
            $resultado_sincronizacion = false;
            array_push($msj, "El Cliente NO tiene una Cuenta Contable Asociada");
        }

        $encabezado['estadoencabezado'] = $estado;
        $encabezado['fecharegistroencabezado'] = $fecha_documento;
        $encabezado['usuariocreacion'] = $usuario_creacion;
        $encabezado['tipotercero'] = 2;

        if ($concepto["id"] == 1 || is_null($concepto)) {
            $nota_detalle = $sql->ObtenerDetalleNotaCredito($nota_debito_despacho_cliente_id, $encabezadonotaDebito["tipo_factura"]);
            foreach ($nota_detalle as $k => $valor) {
                // echo print_r($valor) . "</br>";

                if ($valor['sw_medicamento'] == "1") {

                    if ($valor['valor_iva'] > 0) {
                        $medicamentos_gravados += $valor['valor'];
                        $costo_medicamentos_gravados += $valor['total_costo'];
                        $inventario_medicamentos_gravados += $valor['total_costo'];
                    } else {
                        $medicamentos_no_gravados += $valor['valor'];
                        $costo_medicamentos_no_gravados += $valor['total_costo'];
                        $inventario_medicamentos_no_gravados += $valor['total_costo'];
                    }
                } elseif ($valor['sw_insumos'] == "1") {
                    if ($valor['valor_iva'] > 0) {
                        $insumos_gravados += $valor['valor'];
                        $costo_insumos_gravados += $valor['total_costo'];
                        $inventario_insumos_gravados += $valor['total_costo'];
                    } else {
                        $insumos_no_gravados += $valor['valor'];
                        $costo_insumos_no_gravados += $valor['total_costo'];
                        $inventario_insumos_no_gravados += $valor['total_costo'];
                    }
                }
//echo "<pre>";print_r($valor);
                // $subtotal += $valor['subtotal'];
                //$retencion_fuente += $valor["valor_rtf"];
                //$retencion_ica += $valor["valor_ica"];
                $iva += $valor['valor_iva'];
                $total += $valor['valor'];
            }


            foreach ($factura_detalle as $k => $valor) {
                $subtotal += $valor['subtotal'];
            }


            if ($parametros_retencion['sw_rtf'] == '2' || $parametros_retencion['sw_rtf'] == '3') {
                if ($subtotal >= $parametros_retencion['base_rtf']) {
                    $retencion_fuente = $total * ($encabezadonotaDebito['porcentaje_rtf'] / 100);
                    if ($retencion_fuente > 0) {
                        $retencion_fuente = ceil($retencion_fuente);
                    }
                }
            }

            if ($parametros_retencion['sw_ica'] == '2' || $parametros_retencion['sw_ica'] == '3') {
                if ($subtotal >= $parametros_retencion['base_ica']) {
                    $retencion_ica = $total * ($encabezadonotaDebito['porcentaje_ica'] / 1000);
                    if ($retencion_ica > 0) {
                        $retencion_ica = ceil($retencion_ica);
                    }
                }
            }

            // echo " este es el total ".$retencion_fuente;
            // exit();

            /* =============================== Estructura WS de facturas_venta_fi =============================== */


            $esanioactual = ($anioactual > $encabezadonotaDebito["fecha_registro"]) ? false : true;

            // ========================= ESTRUCTURA ASIENTOS CONTABLES WS CxP ======================

            if ($esanioactual) {
                if ($medicamentos_gravados > 0) {


                    $asientoscontables[] = array(
                        'codcentrocostoasiento' => '1012',
                        'codcentroutilidadasiento' => '03',
                        'codcuentaasiento' => '41353803',
                        'codlineacostoasiento' => '01',
                        'identerceroasiento' => $identificacion_tercero,
                        'observacionasiento' => 'No Aplica Observacion Asiento - Medicamentos Gravados',
                        'valorbaseasiento' => '0',
                        'valorcreditoasiento' => '0',
                        'valordebitoasiento' => (int)$medicamentos_gravados,
                        'valortasaasiento' => '0'
                    );
                }


                if ($costo_medicamentos_gravados > 0) {
                    $asientoscontables[] = array(
                        'codcentrocostoasiento' => '1012',
                        'codcentroutilidadasiento' => '03',
                        'codcuentaasiento' => '61353803',
                        'codlineacostoasiento' => '01',
                        'identerceroasiento' => $identificacion_tercero,
                        'observacionasiento' => 'No Aplica Observacion Asiento - Costo Medicamentos Gravados',
                        'valorbaseasiento' => '0',
                        'valorcreditoasiento' => ceil($costo_medicamentos_gravados),
                        'valordebitoasiento' => '0',
                        'valortasaasiento' => '0'
                    );
                }

                if ($inventario_medicamentos_gravados > 0) {
                    $asientoscontables[] = array(
                        'codcentrocostoasiento' => '1012',
                        'codcentroutilidadasiento' => '03',
                        'codcuentaasiento' => '14352005',
                        'codlineacostoasiento' => '01',
                        'identerceroasiento' => $identificacion_tercero,
                        'observacionasiento' => 'No Aplica Observacion Asiento - Inventarios Medicamentos Gravados',
                        'valorbaseasiento' => '0',
                        'valorcreditoasiento' => '0',
                        'valordebitoasiento' => ceil($inventario_medicamentos_gravados),
                        'valortasaasiento' => '0'
                    );
                }


                if ($medicamentos_no_gravados > 0) {
                    $asientoscontables[] = array(
                        'codcentrocostoasiento' => '1012',
                        'codcentroutilidadasiento' => '03',
                        'codcuentaasiento' => '41353804',
                        'codlineacostoasiento' => '01',
                        'identerceroasiento' => $identificacion_tercero,
                        'observacionasiento' => 'No Aplica Observacion Asiento - Medicamentos NO gravados',
                        'valorbaseasiento' => '0',
                        'valorcreditoasiento' => '0',
                        'valordebitoasiento' => ceil($medicamentos_no_gravados),
                        'valortasaasiento' => '0'
                    );
                }



                if ($costo_medicamentos_no_gravados > 0) {
                    $asientoscontables[] = array(
                        'codcentrocostoasiento' => '1012',
                        'codcentroutilidadasiento' => '03',
                        'codcuentaasiento' => '61353804',
                        'codlineacostoasiento' => '01',
                        'identerceroasiento' => $identificacion_tercero,
                        'observacionasiento' => 'No Aplica Observacion Asiento - Costo Medicamentos NO gravados',
                        'valorbaseasiento' => '0',
                        'valorcreditoasiento' => ceil($costo_medicamentos_no_gravados),
                        'valordebitoasiento' => '0',
                        'valortasaasiento' => '0'
                    );
                }

                if ($inventario_medicamentos_no_gravados > 0) {
                    $asientoscontables[] = array(
                        'codcentrocostoasiento' => '1012',
                        'codcentroutilidadasiento' => '03',
                        'codcuentaasiento' => '14352010',
                        'codlineacostoasiento' => '01',
                        'identerceroasiento' => $identificacion_tercero,
                        'observacionasiento' => 'No Aplica Observacion Asiento - Invetario Medicamentos NO gravados',
                        'valorbaseasiento' => '0',
                        'valorcreditoasiento' => '0',
                        'valordebitoasiento' => ceil($inventario_medicamentos_no_gravados),
                        'valortasaasiento' => '0'
                    );
                }


                if ($insumos_gravados > 0) {
                    $asientoscontables[] = array(
                        'codcentrocostoasiento' => '1012',
                        'codcentroutilidadasiento' => '03',
                        'codcuentaasiento' => '41353201',
                        'codlineacostoasiento' => '01',
                        'identerceroasiento' => $identificacion_tercero,
                        'observacionasiento' => 'No Aplica Observacion Asiento - Insumos gravados',
                        'valorbaseasiento' => '0',
                        'valorcreditoasiento' => '0',
                        'valordebitoasiento' => ceil($insumos_gravados),
                        'valortasaasiento' => '0'
                    );
                }



                if ($costo_insumos_gravados > 0) {
                    $asientoscontables[] = array(
                        'codcentrocostoasiento' => '1012',
                        'codcentroutilidadasiento' => '03',
                        'codcuentaasiento' => '61353201',
                        'codlineacostoasiento' => '01',
                        'identerceroasiento' => $identificacion_tercero,
                        'observacionasiento' => 'No Aplica Observacion Asiento - Costo Insumos gravados',
                        'valorbaseasiento' => '0',
                        'valorcreditoasiento' => ceil($costo_insumos_gravados),
                        'valordebitoasiento' => '0',
                        'valortasaasiento' => '0'
                    );
                }

                if ($inventario_insumos_gravados > 0) {
                    $asientoscontables[] = array(
                        'codcentrocostoasiento' => '1012',
                        'codcentroutilidadasiento' => '03',
                        'codcuentaasiento' => '14350505',
                        'codlineacostoasiento' => '01',
                        'identerceroasiento' => $identificacion_tercero,
                        'observacionasiento' => 'No Aplica Observacion Asiento - Inventarios Insumos gravados',
                        'valorbaseasiento' => '0',
                        'valorcreditoasiento' => '0',
                        'valordebitoasiento' => ceil($inventario_insumos_gravados),
                        'valortasaasiento' => '0'
                    );
                }


                if ($insumos_no_gravados > 0) {
                    $asientoscontables[] = array(
                        'codcentrocostoasiento' => '1012',
                        'codcentroutilidadasiento' => '03',
                        'codcuentaasiento' => '41353202',
                        'codlineacostoasiento' => '01',
                        'identerceroasiento' => $identificacion_tercero,
                        'observacionasiento' => 'No Aplica Observacion Asiento - Insumos NO gravados',
                        'valorbaseasiento' => '0',
                        'valorcreditoasiento' => '0',
                        'valordebitoasiento' => floor($insumos_no_gravados),
                        'valortasaasiento' => '0'
                    );
                }

                if ($costo_insumos_no_gravados > 0) {
                    $asientoscontables[] = array(
                        'codcentrocostoasiento' => '1012',
                        'codcentroutilidadasiento' => '03',
                        'codcuentaasiento' => '61353202',
                        'codlineacostoasiento' => '01',
                        'identerceroasiento' => $identificacion_tercero,
                        'observacionasiento' => 'No Aplica Observacion Asiento - Costo Insumos NO gravados',
                        'valorbaseasiento' => '0',
                        'valorcreditoasiento' => ceil($costo_insumos_no_gravados),
                        'valordebitoasiento' => '0',
                        'valortasaasiento' => '0'
                    );
                }

                if ($inventario_insumos_no_gravados > 0) {
                    $asientoscontables[] = array(
                        'codcentrocostoasiento' => '1012',
                        'codcentroutilidadasiento' => '03',
                        'codcuentaasiento' => '14350510',
                        'codlineacostoasiento' => '01',
                        'identerceroasiento' => $identificacion_tercero,
                        'observacionasiento' => 'No Aplica Observacion Asiento - Inventarios Insumos NO gravados',
                        'valorbaseasiento' => '0',
                        'valorcreditoasiento' => '0',
                        'valordebitoasiento' => ceil($inventario_insumos_no_gravados),
                        'valortasaasiento' => '0'
                    );
                }


                if ($iva > 0) {
                    
                 $porc_iva=(($iva*100)/($medicamentos_gravados + $insumos_gravados));

                    $asientoscontables[] = array(
                        'codcentrocostoasiento' => '1012',
                        'codcentroutilidadasiento' => '03',
                        'codcuentaasiento' => '24080604',
                        'codlineacostoasiento' => '01',
                        'identerceroasiento' => $identificacion_tercero,
                        'observacionasiento' => 'No Aplica Observacion Asiento - IVA',
                        'valorbaseasiento' => floor($medicamentos_gravados + $insumos_gravados),
                        'valorcreditoasiento' => '0',
                        'valordebitoasiento' => ceil($iva),
                        'valortasaasiento' => round($porc_iva),
                    );
                }



                // $retencion_fuente = 0; // Credito
                //echo $retencion_fuente. " retefuente "; 

                if ($retencion_fuente > 0) {
                    $codigo_cuenta_contable = "13551505";

                    $asientoscontables[] = array(
                        'codcentrocostoasiento' => '1012',
                        'codcentroutilidadasiento' => '03',
                        'codcuentaasiento' => $codigo_cuenta_contable,
                        'codlineacostoasiento' => '01',
                        'identerceroasiento' => $identificacion_tercero,
                        'observacionasiento' => 'No Aplica Observacion Asiento  - RTE FTE',
                        'valorbaseasiento' => $total,
                        'valorcreditoasiento' => ceil($retencion_fuente),
                        'valordebitoasiento' => '0',
                        'valortasaasiento' => $encabezadonotaDebito['porcentaje_rtf']
                    );
                }

                if ($retencion_ica > 0) {
                    $codigo_cuenta_contable = "";

                    if ($cliente['porcentaje_ica'] == "3.30") {
                        $codigo_cuenta_contable = "13551805";
                    }
                    if ($cliente['porcentaje_ica'] == "5.40") {
                        $codigo_cuenta_contable = "13551808";
                    }
                    if ($cliente['porcentaje_ica'] == "6.00") {
                        $codigo_cuenta_contable = "13551807";
					}	
					if ($cliente['porcentaje_ica'] == "7.00") {
                        $codigo_cuenta_contable = "13551809";	
                    }

					
                    $asientoscontables[] = array(
                        'codcentrocostoasiento' => '1012',
                        'codcentroutilidadasiento' => '03',
                        'codcuentaasiento' => $codigo_cuenta_contable,
                        'codlineacostoasiento' => '01',
                        'identerceroasiento' => $identificacion_tercero,
                        'observacionasiento' => 'No Aplica Observacion Asiento - RTE ICA',
                        'valorbaseasiento' => $total,
                        'valorcreditoasiento' => ceil($retencion_ica),
                        'valordebitoasiento' => '0',
                        'valortasaasiento' => $cliente['porcentaje_ica']
                    );
                }
                
                $porcentaje_cree=0.0040;
                $impusto_cree = $total * $porcentaje_cree;

                if ($impusto_cree > 0) {

                    $asientoscontables[] = array(
                        'codcentrocostoasiento' => '1012',
                        'codcentroutilidadasiento' => '03',
                        'codcuentaasiento' => '13551901',
                        'codlineacostoasiento' => '01',
                        'identerceroasiento' => $identificacion_tercero,
                        'observacionasiento' => 'No Aplica Observacion Asiento - Cree',
                        'valorbaseasiento' => floor($total),
                        'valorcreditoasiento' => ceil($impusto_cree),
                        'valordebitoasiento' => '0',
                        'valortasaasiento' => $porcentaje_cree
                    );

                    $asientoscontables[] = array(
                        'codcentrocostoasiento' => '1012',
                        'codcentroutilidadasiento' => '03',
                        'codcuentaasiento' => '23659501',
                        'codlineacostoasiento' => '01',
                        'identerceroasiento' => $identificacion_tercero,
                        'observacionasiento' => 'No Aplica Observacion Asiento - Cree',
                        'valorbaseasiento' => floor($total),
                        'valorcreditoasiento' => '0',
                        'valordebitoasiento' => ceil($impusto_cree),
                        'valortasaasiento' => $porcentaje_cree
                    );
                }
            }


            // $total = (((($total) - $retencion_fuente) - $retencion_ica) - $retencion_iva);

            /* echo ($total + $iva - $retencion_ica - $retencion_fuente)."</br>";
              echo $retencion_fuente. " rete </br>";
              echo $total; */

            $asientoscontables[] = array(
                'codcentrocostoasiento' => '1012',
                'codcentroutilidadasiento' => '03',
                'codcuentaasiento' => $cuenta_contable,
                'codlineacostoasiento' => '01',
                'identerceroasiento' => $identificacion_tercero,
                'observacionasiento' => 'No Aplica Observacion Asiento - Total',
                'valorbaseasiento' => '0',
                'valorcreditoasiento' => ceil($total + $iva - $retencion_ica - $retencion_fuente), //cambio floor por ceil
                'valordebitoasiento' => '0',
                'valortasaasiento' => '0'
            );

            if (!$esanioactual) {
                $asientoscontables[] = array(
                    'codcentrocostoasiento' => '1012',
                    'codcentroutilidadasiento' => '03',
                    'codcuentaasiento' => '14352010',
                    'codlineacostoasiento' => '01',
                    'identerceroasiento' => $identificacion_tercero,
                    'observacionasiento' => 'No Aplica Observacion Asiento - Invetario Medicamentos NO gravados',
                    'valorbaseasiento' => '0',
                    'valorcreditoasiento' => '0',
                    'valordebitoasiento' => ceil($total + $iva - $retencion_ica - $retencion_fuente),
                    'valortasaasiento' => '0'
                );
            }
        } else {

            $valordebito = 0;
            $valorcredito = 0;
            $total = $encabezadonotaDebito['valor'];
            $encabezado['observacionencabezado'] = $observacion_encabezado;

            if ($concepto["naturaleza"] == 'D') {
                $valordebito = $total;
            } else {
                $valorcredito = $total;
            }

            $asientoscontables[] = array(
                'codcentrocostoasiento' => '1012',
                'codcentroutilidadasiento' => '03',
                'codcuentaasiento' => $concepto['cuenta'],
                'codlineacostoasiento' => '01',
                'identerceroasiento' => $identificacion_tercero,
                'observacionasiento' => "No Aplica Observacion ",
                'valorbaseasiento' => '0',
                'valorcreditoasiento' => ceil($valorcredito),
                'valordebitoasiento' => ceil($valordebito),
                'valortasaasiento' => '0'
            );

            $asientoscontables[] = array(
                'codcentrocostoasiento' => '1012',
                'codcentroutilidadasiento' => '03',
                'codcuentaasiento' => $cuenta_contable,
                'codlineacostoasiento' => '01',
                'identerceroasiento' => $identificacion_tercero,
                'observacionasiento' => 'No Aplica Observacion Asiento - Total',
                'valorbaseasiento' => '0',
                'valorcreditoasiento' => ceil($total),
                'valordebitoasiento' => '0',
                'valortasaasiento' => '0'
            );
        }

        $parametros = array('encabezadofactura' => $encabezado, 'asientoscontables' => $asientoscontables);

//        echo "<pre>";
////          echo var_dump($parametros);
//          print_r($parametros);
//          echo "</pre>"; 
        // return array('resultado_ws' => 0, 'mensaje_ws' => $encabezado, 'resultado_bd' => $asientoscontables, 'mensaje_bd' => $asientoscontables);
//       exit();
        if ($resultado_sincronizacion) {

            $resultado_ws = 1;
            $err = $clienteWs->getError();
            if ($err) {
                $error = $err;
                echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
            }
            $result = $clienteWs->call($funcionWs, $parametros);
            if ($clienteWs->fault) {
                $resultado = $result;
                /* echo '<h2>Fault</h2><pre>';
                  print_r($result);
                  echo '</pre>'; */
            } else {
                $err = $clienteWs->getError();
                if ($err) {
                    $error = $err;
                    echo '<h2>Error</h2><pre>' . $err . '</pre>';
                } else {
                    $resultado_ws = 0;
                    $resultado = $result;
                }
            }

            // Display the request and response
            //echo '<h2>Request</h2>';
            //echo '<pre>' . htmlspecialchars($clienteWs->request, ENT_QUOTES) . '</pre>';
            //echo '<h2>Response</h2>';
            //echo '<pre>' . htmlspecialchars($clienteWs->response, ENT_QUOTES) . '</pre>';
            // Display the debug messages
            //echo '<h2>Debug</h2>';
            //echo '<pre>' . htmlspecialchars($clienteWs->debug_str, ENT_QUOTES) . '</pre>';

            /* echo "<pre>";
              var_dump($resultado);
              echo "</pre>";

              exit(); */

            $mensajeWs = "";
            foreach ($resultado as $key => $value) {
                $mensajeWs = $value['descripcion'];
                if ($value['codigo'] == "1" || $value['estado'] == "true") {
                    //error
                    $resultado_logs = $sql_f->registrar_resultado_sincronizacion_notas($encabezadonotaDebito["prefijo"], $encabezadonotaDebito["factura_fiscal"], $prefijo_fi, $nota_debito_despacho_cliente_id, $mensajeWs, '1');
                } else {
                    //exito
                    $resultado_logs = $sql_f->registrar_resultado_sincronizacion_notas($encabezadonotaDebito["prefijo"], $encabezadonotaDebito["factura_fiscal"], $prefijo_fi, $nota_debito_despacho_cliente_id, $mensajeWs, '0');
                }
            }

            if ($resultado_ws == 1) {
                $mensajeWs = 'Se ha Generado un error con el Ws de Facturas Ventas, no se ha podido establecer conexion';
                $resultado_logs = $sql_f->registrar_resultado_sincronizacion_notas($encabezadonotaDebito["prefijo"], $encabezadonotaDebito["factura_fiscal"], $prefijo_fi, $nota_debito_despacho_cliente_id, $mensajeWs, '1');
            }

            $mensaje_bd = "Log Registrado Correctamente ";
            if (!$resultado_logs)
                $mensaje_bd = "Se ha Generado un error tratando de Insertar/Actualizar en la Tabla logs_facturacion_clientes_ws_fi";

            return array('resultado_ws' => $resultado_ws, 'mensaje_ws' => $mensajeWs, 'resultado_bd' => $resultado_logs, 'mensaje_bd' => $mensaje_bd);
        } else {
            // Regitrar Error
            $mensajeWs = implode(",", $msj);

            $resultado_logs = $sql_f->registrar_resultado_sincronizacion_notas($encabezadonotaDebito["prefijo"], $encabezadonotaDebito["factura_fiscal"], $prefijo_fi, $nota_debito_despacho_cliente_id, $mensajeWs, '1');

            $mensaje_bd = "Log Registrado Correctamente ";
            if (!$resultado_logs)
                $mensaje_bd = "Se ha Generado un error tratando de Insertar/Actualizar en la Tabla logs_facturacion_clientes_ws_fi";

            return array('resultado_ws' => 1, 'mensaje_ws' => $mensajeWs, 'resultado_bd' => $resultado_logs, 'mensaje_bd' => $mensaje_bd);
        }
    }

    function notas_debito_cliente_fi($nota_debito_despacho_cliente_id) {

        $sql = AutoCarga::factory("NotasFacturasClienteSQL", "classes", "app", "NotasFacturasCliente");
        $sql_f = AutoCarga::factory("FacturasDespachoSQL", "classes", "app", "FacturasDespacho");
        $sql_aux = AutoCarga::factory("ContratacionProductosClienteSQL", "classes", "app", "ContratacionProductosCliente");


        $encabezadonotaDebito = $sql->obtenerEncabezadoNotaDebitoCliente($nota_debito_despacho_cliente_id);
        /* echo "encabezaod";
          echo print_r($encabezadonotaDebito);
          echo "</br>"; */

        //$factura_detalle = $sql->Detalle_Factura($empresa_id, $prefijo, $numero_factura);
        $nota_detalle = $sql->ObtenerDetalleNotaDebito($nota_debito_despacho_cliente_id, $encabezadonotaDebito["tipo_factura"]);
        $cliente = $sql_aux->ConsultarTercero_Contrato($encabezadonotaDebito["empresa_id"], $encabezadonotaDebito['tercero_id'], $encabezadonotaDebito['tipo_id_tercero']);

        /*  echo "tercero";  
          echo print_r($cliente);
          echo "</br>";
          exit(); */
        /*  echo "detalle";  
          echo print_r($nota_detalle);
          echo "</br>";
          exit(); */

        $parametros_retencion = $sql_f->Parametros_Retencion($encabezadonotaDebito['empresa_id'], $encabezadonotaDebito['anio_factura']);
        $factura_detalle = $sql_f->Detalle_Factura($encabezadonotaDebito['empresa_id'], $encabezadonotaDebito["prefijo"], $encabezadonotaDebito["factura_fiscal"]);


        /* echo "parametros_retencion";  
          echo print_r($parametros_retencion);
          echo "</br>";
          exit(); */

        $prefijo_fi = "DN";

        $funcionWs = "crearInformacionContable";
        $clienteWs = new nusoap_client($this->http_gestion_contable, true);

        $error = "";
        $resultado = "";

        $resultado_sincronizacion = true;
        $msj = array();
        $encabezado = array();
        $asientoscontables = array();

        $codigo_empresa = "DUA";
        $codigo_documento = trim($prefijo_fi);
        $numero_documento = $nota_debito_despacho_cliente_id;
        //$fecha_documento = date("d/m/Y");
        $fecha_documento = $encabezadonotaDebito["fecha_nota"];
        $estado = "3";
        $identificacion_tercero = trim($encabezadonotaDebito['tercero_id']);
        $observacion_encabezado = "SIN OBSERVACION";
        $usuario_creacion = UserGetUID();
        $cuenta_contable = $cliente['cuenta_contable']; //'13050505';
        //$subtotal = 0;
        $iva = 0;
        $total = 0;
        $retencion_ica = 0;
        $retencion_fuente = 0;

        $medicamentos_gravados = 0;
        //$costo_medicamentos_gravados = 0;
        //$inventario_medicamentos_gravados = 0;

        $medicamentos_no_gravados = 0;
        //$costo_medicamentos_no_gravados = 0;
        // $inventario_medicamentos_no_gravados = 0;

        $insumos_gravados = 0;
        //  $costo_insumos_gravados = 0;
        //$inventario_insumos_gravados = 0;

        $insumos_no_gravados = 0;
        $subtotal = 0;
        // $costo_insumos_no_gravados = 0;
        //$inventario_insumos_no_gravados = 0;


        foreach ($nota_detalle as $k => $valor) {
            //echo print_r($valor) . "</br>";

            if ($valor['sw_medicamento'] == "1") {

                if ($valor['valor_iva'] > 0) {
                    $medicamentos_gravados += $valor['valor'];
                    //$costo_medicamentos_gravados += $valor['costo'];
                    //$inventario_medicamentos_gravados += $valor['costo'];
                } else {
                    $medicamentos_no_gravados += $valor['valor'];
                    //$costo_medicamentos_no_gravados += $valor['costo'];
                    //$inventario_medicamentos_no_gravados += $valor['costo'];
                }
            } elseif ($valor['sw_insumos'] == "1") {
                if ($valor['valor_iva'] > 0) {
                    $insumos_gravados += $valor['valor'];
                    //$costo_insumos_gravados += $valor['costo'];
                    // $inventario_insumos_gravados += $valor['costo'];
                } else {
                    $insumos_no_gravados += $valor['valor'];
                    //$costo_insumos_no_gravados += $valor['costo'];
                    //$inventario_insumos_no_gravados += $valor['costo'];
                }
            }

            // $subtotal += $valor['subtotal'];
            //$retencion_fuente += $valor["valor_rtf"];
            //$retencion_ica += $valor["valor_ica"];
            $iva += $valor['valor_iva'];
            $total += $valor['valor'];
        }

        foreach ($factura_detalle as $k => $valor) {
            $subtotal += $valor['subtotal'];
        }


        if ($parametros_retencion['sw_rtf'] == '2' || $parametros_retencion['sw_rtf'] == '3') {
            if ($subtotal >= $parametros_retencion['base_rtf']) {
                $retencion_fuente = $total * ($encabezadonotaDebito['porcentaje_rtf'] / 100);
                if ($retencion_fuente > 0) {
                    $retencion_fuente = (int) $retencion_fuente;
                }
            }
        }

        if ($parametros_retencion['sw_ica'] == '2' || $parametros_retencion['sw_ica'] == '3') {
            if ($subtotal >= $parametros_retencion['base_ica']) {
                $retencion_ica = $total * ($encabezadonotaDebito['porcentaje_ica'] / 1000);
                if ($retencion_ica > 0) {
                    $retencion_ica = (int) $retencion_ica;
                }
            }
        }

        $anioactual = date("Y");
        $esanioactual = ($anioactual > $encabezadonotaDebito["fecha_registro"]) ? false : true;

        /* =============================== Estructura WS de facturas_venta_fi =============================== */
        $encabezado['codempresa'] = $codigo_empresa;
        if (empty($codigo_empresa)) {
            $resultado_sincronizacion = false;
            array_push($msj, "El Codigo de la Empresa no esta definido");
        }

        $encabezado['coddocumentoencabezado'] = trim($codigo_documento);
        if (empty($codigo_documento)) {
            $resultado_sincronizacion = false;
            array_push($msj, "El Prefijo FI, no esta parametrizado para ese documento");
        }

        $encabezado['numerodocumentoencabezado'] = $numero_documento;
        if (empty($numero_documento)) {
            $resultado_sincronizacion = false;
            array_push($msj, "El numero de factura es obligatorio");
        }

        $encabezado['identerceroencabezado'] = $identificacion_tercero;
        if (empty($identificacion_tercero)) {
            $resultado_sincronizacion = false;
            array_push($msj, "El proveedor no posee una identificacion valida");
        }

        $encabezado['observacionencabezado'] = $observacion_encabezado;
        if (empty($observacion_encabezado)) {
            $resultado_sincronizacion = false;
            array_push($msj, "Debe Ingresar una observacion");
        }

        if (empty($cuenta_contable)) {
            $resultado_sincronizacion = false;
            array_push($msj, "El Cliente NO tiene una Cuenta Contable Asociada");
        }

        $encabezado['estadoencabezado'] = $estado;
        $encabezado['fecharegistroencabezado'] = $fecha_documento;
        $encabezado['usuariocreacion'] = $usuario_creacion;
        $encabezado['tipotercero'] = 2;

        // ========================= ESTRUCTURA ASIENTOS CONTABLES WS CxP ======================

        if ($esanioactual) {
            if ($medicamentos_gravados > 0) {
                $asientoscontables[] = array(
                    'codcentrocostoasiento' => '0',
                    'codcentroutilidadasiento' => '0',
                    'codcuentaasiento' => '41353803',
                    'codlineacostoasiento' => '0',
                    'identerceroasiento' => $identificacion_tercero,
                    'observacionasiento' => 'No Aplica Observacion Asiento - Medicamentos Gravados',
                    'valorbaseasiento' => '0',
                    'valorcreditoasiento' => $medicamentos_gravados,
                    'valordebitoasiento' => '0',
                    'valortasaasiento' => '0'
                );
            }


            if ($medicamentos_no_gravados > 0) {
                $asientoscontables[] = array(
                    'codcentrocostoasiento' => '1020',
                    'codcentroutilidadasiento' => '03',
                    'codcuentaasiento' => '41353804',
                    'codlineacostoasiento' => '01',
                    'identerceroasiento' => $identificacion_tercero,
                    'observacionasiento' => 'No Aplica Observacion Asiento - Medicamentos NO gravados',
                    'valorbaseasiento' => '0',
                    'valorcreditoasiento' =>(int) ($medicamentos_no_gravados),
                    'valordebitoasiento' => '0',
                    'valortasaasiento' => '0'
                );
            }


            if ($insumos_gravados > 0) {
                $asientoscontables[] = array(
                    'codcentrocostoasiento' => '0',
                    'codcentroutilidadasiento' => '0',
                    'codcuentaasiento' => '41353201',
                    'codlineacostoasiento' => '0',
                    'identerceroasiento' => $identificacion_tercero,
                    'observacionasiento' => 'No Aplica Observacion Asiento - Insumos gravados',
                    'valorbaseasiento' => '0',
                    'valorcreditoasiento' => $insumos_gravados,
                    'valordebitoasiento' => '0',
                    'valortasaasiento' => '0'
                );
            }

            if ($insumos_no_gravados > 0) {
                $asientoscontables[] = array(
                    'codcentrocostoasiento' => '0',
                    'codcentroutilidadasiento' => '0',
                    'codcuentaasiento' => '41353202',
                    'codlineacostoasiento' => '0',
                    'identerceroasiento' => $identificacion_tercero,
                    'observacionasiento' => 'No Aplica Observacion Asiento - Insumos NO gravados',
                    'valorbaseasiento' => '0',
                    'valorcreditoasiento' => $insumos_no_gravados,
                    'valordebitoasiento' => '0',
                    'valortasaasiento' => '0'
                );
            }

            if ($iva > 0) {
                
                $porc_iva=(($iva*100)/($medicamentos_gravados + $insumos_gravados));
                
                $asientoscontables[] = array(
                    'codcentrocostoasiento' => '0',
                    'codcentroutilidadasiento' => '0',
                    'codcuentaasiento' => '24080604',
                    'codlineacostoasiento' => '0',
                    'identerceroasiento' => $identificacion_tercero,
                    'observacionasiento' => 'No Aplica Observacion Asiento - IVA',
                    'valorbaseasiento' => $medicamentos_gravados + $insumos_gravados,
                    'valorcreditoasiento' => ceil($iva),
                    'valordebitoasiento' => '0',
                    'valortasaasiento' => round($porc_iva)
                );
            }



            // $retencion_fuente = 0; // Credito

            if ($retencion_fuente > 0) {
                $codigo_cuenta_contable = "13551505";

                $asientoscontables[] = array(
                    'codcentrocostoasiento' => '0',
                    'codcentroutilidadasiento' => '0',
                    'codcuentaasiento' => $codigo_cuenta_contable,
                    'codlineacostoasiento' => '0',
                    'identerceroasiento' => $identificacion_tercero,
                    'observacionasiento' => 'No Aplica Observacion Asiento  - RTE FTE',
                    'valorbaseasiento' => $total,
                    'valorcreditoasiento' => '0',
                    'valordebitoasiento' => $retencion_fuente,
                    'valortasaasiento' => $encabezadonotaDebito['porcentaje_rtf']
                );
            }

            if ($retencion_ica > 0) {
                $codigo_cuenta_contable = "";

                if ($cliente['porcentaje_ica'] == "3.30") {
                    $codigo_cuenta_contable = "13551805";
                }
                if ($cliente['porcentaje_ica'] == "5.40") {
                    $codigo_cuenta_contable = "13551808";
                }
                if ($cliente['porcentaje_ica'] == "6.00") {
                    $codigo_cuenta_contable = "13551807";
                }
				if ($cliente['porcentaje_ica'] == "7.00") {
					$codigo_cuenta_contable = "13551809";	
				}

                $asientoscontables[] = array(
                    'codcentrocostoasiento' => '0',
                    'codcentroutilidadasiento' => '0',
                    'codcuentaasiento' => $codigo_cuenta_contable,
                    'codlineacostoasiento' => '0',
                    'identerceroasiento' => $identificacion_tercero,
                    'observacionasiento' => 'No Aplica Observacion Asiento - RTE ICA',
                    'valorbaseasiento' => $total,
                    'valorcreditoasiento' => '0',
                    'valordebitoasiento' => $retencion_ica,
                    'valortasaasiento' => '0'
                );
            }
            $impusto_cree = $total * 0.0040;

            if ($impusto_cree > 0) {

                $asientoscontables[] = array(
                    'codcentrocostoasiento' => '0',
                    'codcentroutilidadasiento' => '0',
                    'codcuentaasiento' => '13551901',
                    'codlineacostoasiento' => '0',
                    'identerceroasiento' => $identificacion_tercero,
                    'observacionasiento' => 'No Aplica Observacion Asiento - Total',
                    'valorbaseasiento' => $medicamentos_gravados + $medicamentos_no_gravados + $insumos_gravados + $insumos_no_gravados,
                    'valorcreditoasiento' => '0',
                    'valordebitoasiento' => ceil($impusto_cree),
                    'valortasaasiento' => '0.4'
                );

                $asientoscontables[] = array(
                    'codcentrocostoasiento' => '0',
                    'codcentroutilidadasiento' => '0',
                    'codcuentaasiento' => '23659501',
                    'codlineacostoasiento' => '0',
                    'identerceroasiento' => $identificacion_tercero,
                    'observacionasiento' => 'No Aplica Observacion Asiento - Total',
                    'valorbaseasiento' => $medicamentos_gravados + $medicamentos_no_gravados + $insumos_gravados + $insumos_no_gravados,
                    'valorcreditoasiento' => ceil($impusto_cree),
                    'valordebitoasiento' => '0',
                    'valortasaasiento' => '0.4'
                );
            }

            $asientoscontables[] = array(
                'codcentrocostoasiento' => '0',
                'codcentroutilidadasiento' => '0',
                'codcuentaasiento' => $cuenta_contable,
                'codlineacostoasiento' => '0',
                'identerceroasiento' => $identificacion_tercero,
                'observacionasiento' => 'No Aplica Observacion Asiento - Total',
                'valorbaseasiento' => '0',
                'valorcreditoasiento' => '0',
                'valordebitoasiento' => (int) ($total + $iva - $retencion_ica - $retencion_fuente),
                'valortasaasiento' => '0'
            );
        } else {
            $asientoscontables[] = array(
                'codcentrocostoasiento' => '0',
                'codcentroutilidadasiento' => '0',
                'codcuentaasiento' => $cuenta_contable,
                'codlineacostoasiento' => '0',
                'identerceroasiento' => $identificacion_tercero,
                'observacionasiento' => 'No Aplica Observacion Asiento - Total',
                'valorbaseasiento' => '0',
                'valorcreditoasiento' => (int) ($total + $iva - $retencion_ica - $retencion_fuente),
                'valordebitoasiento' => '0',
                'valortasaasiento' => '0'
            );

            $asientoscontables[] = array(
                'codcentrocostoasiento' => '0',
                'codcentroutilidadasiento' => '0',
                'codcuentaasiento' => '14352010',
                'codlineacostoasiento' => '0',
                'identerceroasiento' => $identificacion_tercero,
                'observacionasiento' => 'No Aplica Observacion Asiento - Invetario Medicamentos NO gravados',
                'valorbaseasiento' => '0',
                'valorcreditoasiento' => '0',
                'valordebitoasiento' => (int) ($total + $iva - $retencion_ica - $retencion_fuente),
                'valortasaasiento' => '0'
            );
        }


        // $total = (((($total) - $retencion_fuente) - $retencion_ica) - $retencion_iva);




        $parametros = array('encabezadofactura' => $encabezado, 'asientoscontables' => $asientoscontables);
//return array('resultado_ws' => 0, 'mensaje_ws' => $encabezado, 'resultado_bd' => $asientoscontables, 'mensaje_bd' => $asientoscontables);
//         echo "<pre>";
//          echo var_dump($parametros);
//          echo "</pre>"; 
//          exit();
        if ($resultado_sincronizacion) {

            $resultado_ws = 1;
            $err = $clienteWs->getError();
            if ($err) {
                $error = $err;
                echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
            }
            $result = $clienteWs->call($funcionWs, $parametros);
            if ($clienteWs->fault) {
                $resultado = $result;
                /* echo '<h2>Fault</h2><pre>';
                  print_r($result);
                  echo '</pre>'; */
            } else {
                $err = $clienteWs->getError();
                if ($err) {
                    $error = $err;
                    echo '<h2>Error</h2><pre>' . $err . '</pre>';
                } else {
                    $resultado_ws = 0;
                    $resultado = $result;
                }
            }

            // Display the request and response
            //echo '<h2>Request</h2>';
            //echo '<pre>' . htmlspecialchars($clienteWs->request, ENT_QUOTES) . '</pre>';
            //echo '<h2>Response</h2>';
            //echo '<pre>' . htmlspecialchars($clienteWs->response, ENT_QUOTES) . '</pre>';
            // Display the debug messages
            //echo '<h2>Debug</h2>';
            //echo '<pre>' . htmlspecialchars($clienteWs->debug_str, ENT_QUOTES) . '</pre>';

            /* echo "<pre>";
              var_dump($resultado);
              echo "</pre>"; */

            // exit(); 

            $mensajeWs = "";
            foreach ($resultado as $key => $value) {
                $mensajeWs = $value['descripcion'];
                if ($value['codigo'] == "1" || $value['estado'] == "true") {
                    //error
                    $resultado_logs = $sql_f->registrar_resultado_sincronizacion_notas($encabezadonotaDebito["prefijo"], $encabezadonotaDebito["factura_fiscal"], $prefijo_fi, $nota_debito_despacho_cliente_id, $mensajeWs, '1');
                } else {
                    //exito
                    $resultado_logs = $sql_f->registrar_resultado_sincronizacion_notas($encabezadonotaDebito["prefijo"], $encabezadonotaDebito["factura_fiscal"], $prefijo_fi, $nota_debito_despacho_cliente_id, $mensajeWs, '0');
                }
            }

            if ($resultado_ws == 1) {
                $mensajeWs = 'Se ha Generado un error con el Ws de Facturas Ventas, no se ha podido establecer conexion';
                $resultado_logs = $sql_f->registrar_resultado_sincronizacion_notas($encabezadonotaDebito["prefijo"], $encabezadonotaDebito["factura_fiscal"], $prefijo_fi, $nota_debito_despacho_cliente_id, $mensajeWs, '1');
            }

            $mensaje_bd = "Log Registrado Correctamente ";
            if (!$resultado_logs)
                $mensaje_bd = "Se ha Generado un error tratando de Insertar/Actualizar en la Tabla logs_facturacion_clientes_ws_fi";

            return array('resultado_ws' => $resultado_ws, 'mensaje_ws' => $mensajeWs, 'resultado_bd' => $resultado_logs, 'mensaje_bd' => $mensaje_bd);
        } else {
            // Regitrar Error
            $mensajeWs = implode(",", $msj);

            $resultado_logs = $sql_f->registrar_resultado_sincronizacion_notas($encabezadonotaDebito["prefijo"], $encabezadonotaDebito["factura_fiscal"], $prefijo_fi, $nota_debito_despacho_cliente_id, $mensajeWs, '1');

            $mensaje_bd = "Log Registrado Correctamente ";
            if (!$resultado_logs)
                $mensaje_bd = "Se ha Generado un error tratando de Insertar/Actualizar en la Tabla logs_facturacion_clientes_ws_fi";

            return array('resultado_ws' => 1, 'mensaje_ws' => $mensajeWs, 'resultado_bd' => $resultado_logs, 'mensaje_bd' => $mensaje_bd);
        }
    }

    /* ================== BONIFICACIONES ========================== */

    function ingreso_bonificaciones_fi($empresa_id, $prefijo, $numero) {


        $sql = AutoCarga::factory("MovDocI007", "classes", "app", "Inv_MovimientosBodegas");
        $sql_aux = AutoCarga::factory("MovBodegasSQL", "classes", "app", "Inv_MovimientosBodegas");

        $encabezado_bonificacion = $sql->obtener_encabezado_bonificacion($empresa_id, $prefijo, $numero);
        $detalle_bonificacion = $sql->obtener_detalle_bonificacion($empresa_id, $prefijo, $numero);

        $prefijo_fi = $sql_aux->obtener_prefijo_fi($empresa_id, $prefijo);
        $prefijo_fi = trim($prefijo_fi['prefijo_fi']);

        $funcionWs = "crearInformacionContable";
        $clienteWs = new nusoap_client($this->http_gestion_contable, true);

        $error = "";
        $resultado = "";

        $resultado_sincronizacion = true;
        $msj = array();
        $encabezado = array();
        $asientoscontables = array();

        $codigo_empresa = "DUA";
        $codigo_documento = trim($prefijo_fi);
        $numero_documento = $encabezado_bonificacion['numero'];
        $fecha_documento = date("d/m/Y");
        $estado = "3";
        $identificacion_tercero = $encabezado_bonificacion['tercero_id'];
        $observacion_encabezado = $encabezado_bonificacion['observacion'];
        $usuario_creacion = UserGetUID();


        $subtotal = 0;
        $iva = 0;
        $total = 0;
        $medicamentos_gravados = 0;
        $medicamentos_no_gravados = 0;
        $insumos_gravados = 0;
        $insumos_no_gravados = 0;
        foreach ($detalle_bonificacion as $k => $valor) {

            if ($valor['sw_medicamento'] == "1") {

                if ($valor['iva'] > 0) {
                    $medicamentos_gravados += $valor['subtotal'];
                } else {
                    $medicamentos_no_gravados += $valor['subtotal'];
                }
            } elseif ($valor['sw_insumos'] == "1") {
                if ($valor['iva'] > 0) {
                    $insumos_gravados += $valor['subtotal'];
                } else {
                    $insumos_no_gravados += $valor['subtotal'];
                }
            }

            $subtotal += $valor['subtotal'];
            $iva += $valor['iva_total'];
            $total += $valor['total'];
        }


        /* =============================== Estructura WS de Bonificaciones =============================== */
        $encabezado['codempresa'] = $codigo_empresa;
        if (empty($codigo_empresa)) {
            $resultado_sincronizacion = false;
            array_push($msj, "El Codigo de la Empresa no esta definido");
        }

        $encabezado['coddocumentoencabezado'] = trim($codigo_documento);
        if (empty($codigo_documento)) {
            $resultado_sincronizacion = false;
            array_push($msj, "El Prefijo FI, no esta parametrizado para ese documento");
        }

        $encabezado['numerodocumentoencabezado'] = $numero_documento;
        if (empty($numero_documento)) {
            $resultado_sincronizacion = false;
            array_push($msj, "El numero de factura es obligatorio");
        }

        $encabezado['identerceroencabezado'] = $identificacion_tercero;
        if (empty($identificacion_tercero)) {
            $resultado_sincronizacion = false;
            array_push($msj, "El proveedor no posee una identificacion valida");
        }

        $encabezado['observacionencabezado'] = $observacion_encabezado;
        if (empty($observacion_encabezado)) {
            $resultado_sincronizacion = false;
            array_push($msj, "Debe Ingresar una observacion");
        }

        $encabezado['estadoencabezado'] = $estado;
        $encabezado['fecharegistroencabezado'] = $fecha_documento;
        $encabezado['usuariocreacion'] = $usuario_creacion;
        $encabezado['tipotercero'] = 3;


        if ($medicamentos_gravados > 0) {
            $asientoscontables[] = array(
                'codcentrocostoasiento' => '0',
                'codcentroutilidadasiento' => '0',
                'codconcepto' => '',
                'codcuentaasiento' => '14352010',
                'codlineacostoasiento' => '0',
                'identerceroasiento' => $identificacion_tercero,
                'observacionasiento' => 'No Aplica Observacion Asiento',
                'valorbaseasiento' => '0',
                'valorcreditoasiento' => '0',
                'valordebitoasiento' => $medicamentos_gravados,
                'valortasaasiento' => '0'
            );
        }

        if ($medicamentos_no_gravados > 0) {
            $asientoscontables[] = array(
                'codcentrocostoasiento' => '0',
                'codcentroutilidadasiento' => '0',
                'codconcepto' => '',
                'codcuentaasiento' => '14352010',
                'codlineacostoasiento' => '0',
                'identerceroasiento' => $identificacion_tercero,
                'observacionasiento' => 'No Aplica Observacion Asiento',
                'valorbaseasiento' => '0',
                'valorcreditoasiento' => '0',
                'valordebitoasiento' => $medicamentos_no_gravados,
                'valortasaasiento' => '0'
            );
        }

        if ($insumos_gravados > 0) {
            $asientoscontables[] = array(
                'codcentrocostoasiento' => '0',
                'codcentroutilidadasiento' => '0',
                'codconcepto' => '',
                'codcuentaasiento' => '14352010',
                'codlineacostoasiento' => '0',
                'identerceroasiento' => $identificacion_tercero,
                'observacionasiento' => 'No Aplica Observacion Asiento',
                'valorbaseasiento' => '0',
                'valorcreditoasiento' => '0',
                'valordebitoasiento' => $insumos_gravados,
                'valortasaasiento' => '0'
            );
        }

        if ($insumos_no_gravados > 0) {
            $asientoscontables[] = array(
                'codcentrocostoasiento' => '0',
                'codcentroutilidadasiento' => '0',
                'codconcepto' => '',
                'codcuentaasiento' => '14352010',
                'codlineacostoasiento' => '0',
                'identerceroasiento' => $identificacion_tercero,
                'observacionasiento' => 'No Aplica Observacion Asiento',
                'valorbaseasiento' => '0',
                'valorcreditoasiento' => '0',
                'valordebitoasiento' => $insumos_no_gravados,
                'valortasaasiento' => '0'
            );
        }

        if ($iva > 0) {

            $asientoscontables[] = array(
                'codcentrocostoasiento' => '0',
                'codcentroutilidadasiento' => '0',
                'codconcepto' => '',
                'codcuentaasiento' => '14352010',
                'codlineacostoasiento' => '0',
                'identerceroasiento' => $identificacion_tercero,
                'observacionasiento' => 'No Aplica Observacion Asiento',
                'valorbaseasiento' => $medicamentos_gravados + $insumos_gravados,
                'valorcreditoasiento' => '0',
                'valordebitoasiento' => $iva,
                'valortasaasiento' => '0'
            );
        }

        $asientoscontables[] = array(
            'codcentrocostoasiento' => '0',
            'codcentroutilidadasiento' => '0',
            'codconcepto' => '',
            'codcuentaasiento' => '42950505',
            'codlineacostoasiento' => '0',
            'identerceroasiento' => $identificacion_tercero,
            'observacionasiento' => 'No Aplica Observacion Asiento',
            'valorbaseasiento' => '0',
            'valorcreditoasiento' => $total,
            'valordebitoasiento' => '0',
            'valortasaasiento' => '0'
        );

        $parametros = array('encabezadofactura' => $encabezado, 'asientoscontables' => $asientoscontables);

        $prefijo_documento = $encabezado_bonificacion['prefijo'];

        if ($resultado_sincronizacion) {

            $resultado_ws = 1;
            $err = $clienteWs->getError();
            if ($err) {
                $error = $err;
            }
            $result = $clienteWs->call($funcionWs, $parametros);
            if ($clienteWs->fault) {
                $resultado = $result;
            } else {
                $err = $clienteWs->getError();
                if ($err) {
                    $error = $err;
                } else {
                    $resultado_ws = 0;
                    $resultado = $result;
                }
            }

            /* echo "<pre>";
              var_dump($resultado);
              echo "</pre>";
              exit(); */


            $numero_documento_fi = "";
            $mensajeWs = "";
            foreach ($resultado as $key => $value) {

                $numero_documento_fi = $value['numerodoc'];
                $mensajeWs = $value['descripcion'];

                if ($value['codigo'] == "1" || $value['estado'] == "true") {
                    //error
                    $resultado_logs = $sql->registrar_resultado_sincronizacion($prefijo_documento, $numero_documento, $numero_documento_fi, $mensajeWs, '1');
                } else {
                    //exito
                    $resultado_logs = $sql->registrar_resultado_sincronizacion($prefijo_documento, $numero_documento, $numero_documento_fi, $mensajeWs, '0');
                }
            }

            if ($resultado_ws == 1) {
                $mensajeWs = 'Se ha Generado un error con el Ws de Cuentas x Pagar, no se ha podido establecer conexion';
                $resultado_logs = $sql->registrar_resultado_sincronizacion($prefijo_documento, $numero_documento, $numero_documento_fi, $mensajeWs, '1');
            }

            $mensaje_bd = "Log Registrado Correctamente";
            if (!$resultado_logs)
                $mensaje_bd = "Se ha Generado un error tratando de Insertar/Actualizar en la Tabla logs_bonificaciones_ws_fi";

            return array('resultado_ws' => $resultado_ws, 'mensaje_ws' => $mensajeWs, 'resultado_bd' => $resultado_logs, 'mensaje_bd' => $mensaje_bd);
        } else {
            // Regitrar Error
            $numero_documento_fi = "";
            $mensajeWs = implode(",", $msj);

            $resultado_logs = $sql->registrar_resultado_sincronizacion($prefijo_documento, $numero_documento, $numero_documento_fi, $mensajeWs, '1');

            $mensaje_bd = "Log Registrado Correctamente ";
            if (!$resultado_logs)
                $mensaje_bd = "Se ha Generado un error tratando de Insertar/Actualizar en la Tabla logs_facturacion_proveedores_ws_fi";

            return array('resultado_ws' => 1, 'mensaje_ws' => $mensajeWs, 'resultado_bd' => $resultado_logs, 'mensaje_bd' => $mensaje_bd);
        }
    }

    /*     * *********************    Recibos     ******************************* */

    function enviarRCWSFI($rc_id, $prefijo, $documento = null, $empresa_id = null) {

        $sql = AutoCarga::factory("app_RecibosCaja_Funciones", "classes", "app", "RecibosCaja");

        if ($prefijo == 'RCD' || $prefijo == 'RCB' ) {
            $resultado = $this->enviarReciboRCD($rc_id, $prefijo, $documento = null, $empresa_id = null);
        } else if ($prefijo == 'RCC') {
            $resultado = $this->enviarReciboRCC($rc_id, $prefijo, $documento = null, $empresa_id = null);
        }

        /* echo "<pre>";
          print_r($resultado);
          echo "</pre>"; */



        $sql->registrar_resultado_sincronizacion($prefijo, $rc_id, $resultado['crearInformacionContableResult']['descripcion'], $resultado['crearInformacionContableResult']['codigo']);

        return $resultado;
    }

    function enviarReciboRCC($rc_id, $prefijo, $documento = null, $empresa_id = null) {
        $url_wsdl = $this->http_gestion_contable;
        $sql = AutoCarga::factory("app_RecibosCaja_Funciones", "classes", "app", "RecibosCaja");

        $encabezado = $sql->getEncabezadoRCWSFI($rc_id, $prefijo);


        $detalle1121 = $sql->getFacturasDFIN1121($rc_id, $prefijo);


        $detalle = $sql->getDetalleRCWSFI($rc_id, $prefijo);



        $soapclient = new nusoap_client($url_wsdl, true);
        $function = "crearInformacionContable";

        //Codigo de Documento Encabezado
        $coddocumentoencabezado['prefijo_fi'] = "CC";


        if (strlen($encabezado['observacion']) < 10) {
            $encabezado['observacion'] = "SIN OBSERVACION PARA EL ENCABEZADO";
        } else {
            $encabezado['observacion'] = $encabezado['observacion'];
        }
        $encabezado_rc = array('coddocumentoencabezado' => $coddocumentoencabezado['prefijo_fi'],
            'codempresa' => 'DUA',
            'estadoencabezado' => '3',
            'fecharegistroencabezado' => $encabezado['fecha_registro'],
            'identerceroencabezado' => $encabezado['tercero_id'],
            'numerodocumentoencabezado' => $encabezado['recibo_caja'],
            'observacionencabezado' => $encabezado['observacion'],
            'usuariocreacion' => $encabezado['usuario_id']
        );
        $asiento = array();
        $total_saldo = 0;

        $cuenta = "";

        if ($encabezado['empresa_recibo'] == '1') {
            $cuenta = "13101005";
        } else if ($encabezado['empresa_recibo'] == '0') {
            $cuenta = "28050510";
        }


        /* echo "<pre>encabezado";
          print_r($encabezado);
          echo "</pre>";
          die(); */

        $cuenta1121 = $sql->getCuenta1121($encabezado['tercero_id'], $encabezado['tipo_id_tercero']);

        /* echo "<pre>encabezado";
          print_r($cuenta1121);
          echo "</pre>";
          die(); */
        for ($i = 0; $i < count($detalle1121); $i++) {
            if ($detalle1121[$i]['valor_abonado_rt'] > 0) {

                $asiento[] = array('codcentrocostoasiento' => $detalle1121[$i]['centro_costo'],
                    'codcentroutilidadasiento' => $detalle1121[$i]['centro_utilidad'],
                    'codcuentaasiento' => $cuenta1121['cuenta'], //$detalle[$i]['cuenta_credito'],
                    'codlineacostoasiento' => $detalle1121[$i]['linea_costo'],
                    'identerceroasiento' => $encabezado['tercero_id'],
                    'observacionasiento' => 'SIN OBSERVACION PARA EL ASIENTO',
                    'valorbaseasiento' => '0',
                    'valorcreditoasiento' => (int) ($detalle1121[$i]['valor_abonado_rt']),
                    'valordebitoasiento' => '0',
                    'valortasaasiento' => '0'
                );
                $total_saldo += (int) ($detalle1121[$i]['valor_abonado_rt']);
            }
        }

        $asiento[] = array('codcentrocostoasiento' => '0',
            'codcentroutilidadasiento' => '0',
            'codcuentaasiento' => $cuenta, //$cuentaTercero,
            'codlineacostoasiento' => $detalle[0]['linea_costo'],
            'identerceroasiento' => $encabezado['tercero_id'],
            'observacionasiento' => 'SIN OBSERVACION PARA EL ASIENTO',
            'valorbaseasiento' => '0',
            'valorcreditoasiento' => '0', //(int)($encabezado['total_abono']),
            'valordebitoasiento' => $total_saldo,
            'valortasaasiento' => '0'
        );

        $inputs = array('encabezadofactura' => $encabezado_rc,
            'asientoscontables' => $asiento);

        $resultado = $soapclient->call($function, $inputs);

        return $resultado;
    }

    function enviarReciboRCD($rc_id, $prefijo, $documento = null, $empresa_id = null) {

        $url_wsdl = $this->http_gestion_contable;
        $sql = AutoCarga::factory("app_RecibosCaja_Funciones", "classes", "app", "RecibosCaja");

        $encabezado = $sql->getEncabezadoRCWSFI($rc_id, $prefijo);

        $detalle1121 = $sql->getFacturasDFIN1121($rc_id, $prefijo);

        $detalle = $sql->getDetalleRCWSFI($rc_id, $prefijo);

        $soapclient = new nusoap_client($url_wsdl, true);
        $function = "crearInformacionContable";

        //Codigo de Documento Encabezado
		if($prefijo == 'RCD'){
        $coddocumentoencabezado['prefijo_fi'] = "NTC";
		}else if($prefijo == 'RCB'){
		$coddocumentoencabezado['prefijo_fi'] = "RCB";
		}
		


        if (strlen($encabezado['observacion']) < 10) {
            $encabezado['observacion'] = "SIN OBSERVACION PARA EL ENCABEZADO";
        } else {
            $encabezado['observacion'] = $encabezado['observacion'];
        }
        $encabezado_rc = array('coddocumentoencabezado' => $coddocumentoencabezado['prefijo_fi'],
            'codempresa' => 'DUA',
            'estadoencabezado' => '3',
            'fecharegistroencabezado' => $encabezado['fecha_registro'],
            'identerceroencabezado' => $encabezado['tercero_id'],
            'numerodocumentoencabezado' => $encabezado['recibo_caja'],
            'observacionencabezado' => $encabezado['observacion'],
            'usuariocreacion' => $encabezado['usuario_id']
        );
        $asiento = array();
        $total_saldo = 0;
          /*echo "<pre>encabezado";
          print_r($prefijo);
          echo "</pre>"; 
		  exit();*/

        $cuenta1121 = $sql->getCuenta1121($encabezado['tercero_id'], $encabezado['tipo_id_tercero']);
        for ($i = 0; $i < count($detalle1121); $i++) {
            if ($detalle1121[$i]['valor_abonado_rt'] > 0) {
				if($detalle[$i]['concepto_id'] != '006'){
					$asiento[] = array('codcentrocostoasiento' => $detalle1121[$i]['centro_costo'],
						'codcentroutilidadasiento' => $detalle1121[$i]['centro_utilidad'],
						'codcuentaasiento' => $cuenta1121['cuenta'], //$detalle[$i]['cuenta_credito'],
						'codlineacostoasiento' => $detalle1121[$i]['linea_costo'],
						'identerceroasiento' => $encabezado['tercero_id'],
						'observacionasiento' => 'SIN OBSERVACION PARA EL ASIENTO',
						'valorbaseasiento' => '0',
						'valorcreditoasiento' => (int) ($detalle1121[$i]['valor_abonado_rt']),
						'valordebitoasiento' => '0',
						'valortasaasiento' => '0'
					);
					$total_saldo += (int) ($detalle1121[$i]['valor_abonado_rt']);
				}else{
					$asiento[] = array('codcentrocostoasiento' => $detalle1121[$i]['centro_costo'],
						'codcentroutilidadasiento' => $detalle1121[$i]['centro_utilidad'],
						'codcuentaasiento' => $cuenta1121['cuenta'], //$detalle[$i]['cuenta_credito'],
						'codlineacostoasiento' => $detalle1121[$i]['linea_costo'],
						'identerceroasiento' => $encabezado['tercero_id'],
						'observacionasiento' => 'SIN OBSERVACION PARA EL ASIENTO',
						'valorbaseasiento' => '0',
						'valorcreditoasiento' => (int) ($detalle1121[$i]['valor_abonado_rt'] - $detalle[$i]['valor']),
						'valordebitoasiento' => '0',
						'valortasaasiento' => '0'
					);
					$total_saldo += (int) ($detalle1121[$i]['valor_abonado_rt'] - $detalle[$i]['valor']);
				}
            }
        }


        for ($i = 0; $i < count($detalle); $i++) {
            if ($detalle[$i]['valor'] > 0) {
				if($detalle[$i]['concepto_id'] != '006'){
					if ($detalle[$i]['naturaleza'] == 'D') {
						$asiento[] = array('codcentrocostoasiento' => $detalle[$i]['centro_costo'],
							'codcentroutilidadasiento' => $detalle[$i]['centro_utilidad'],
							'codcuentaasiento' => $detalle[$i]['cuenta_concepto'],
							'codlineacostoasiento' => $detalle[$i]['linea_costo'],
							'identerceroasiento' => $encabezado['tercero_id'],
							'observacionasiento' => 'ASIENTO PARA EL CONCEPTO',
							'valorbaseasiento' => '0',
							'valorcreditoasiento' => '0',
							'valordebitoasiento' => (int) ($detalle[$i]['valor']),
							'valortasaasiento' => '0'
						);
						$total_saldo -= $detalle[$i]['valor'];
					} else {
						$asiento[] = array('codcentrocostoasiento' => $detalle[$i]['centro_costo'],
							'codcentroutilidadasiento' => $detalle[$i]['centro_utilidad'],
							'codcuentaasiento' => $detalle[$i]['cuenta_concepto'],
							'codlineacostoasiento' => $detalle[$i]['linea_costo'],
							'identerceroasiento' => $encabezado['tercero_id'],
							'observacionasiento' => 'ASIENTO PARA EL CONCEPTO',
							'valorbaseasiento' => '0',
							'valorcreditoasiento' => (int) ($detalle[$i]['valor']),
							'valordebitoasiento' => '0',
							'valortasaasiento' => '0'
						);
						$total_saldo += $detalle[$i]['valor'];
					}
				}
            }
        }

        // $cuentacambio = getCuentaConcepto($descripcioncuentaxc);
        //$cuentacambio = $cuentacambio['concepto'];
        /* echo "<pre>hola";
          print_r($cuentacambio);
          echo"</pre>"; */

        $asiento[] = array('codcentrocostoasiento' => '0',
            'codcentroutilidadasiento' => '03',
            'codcuentaasiento' => '28059510', //$cuentaTercero,
            'codlineacostoasiento' => $detalle[0]['linea_costo'],
            'identerceroasiento' => $encabezado['tercero_id'],
            'observacionasiento' => 'SIN OBSERVACION PARA EL ASIENTO',
            'valorbaseasiento' => '0',
            'valorcreditoasiento' => '0', //(int)($encabezado['total_abono']),
            'valordebitoasiento' => $total_saldo,
            'valortasaasiento' => '0'
        );

        $inputs = array('encabezadofactura' => $encabezado_rc,
            'asientoscontables' => $asiento);
/*echo "<pre>";
print_r($inputs);*/
/*return $inputs;*/

        $resultado = $soapclient->call($function, $inputs);

       return $resultado;
    }
}
