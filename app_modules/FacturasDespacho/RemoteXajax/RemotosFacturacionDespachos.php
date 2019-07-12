<?php

/**
 * Archivo Xajax
 * Tiene como responsabilidad hacer el manejo de las funciones
 * que son invocadas por medio de xajax
 *
 * @package IPSOFT-SIIS
 * @version 
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Mauricio Adrian Medina Santacruz
 */

/**
 * Funcion registra las facturas
 *
 * @param  var $prefijo contiene el prefijo del documento
 * @param  var $empresa_id contiene la empresa
 * @param  var $tipo_id_tercero contiene el tipo del tercero
 * @param  var $tercero_id contiene el id del tercero
 * @param  var $nombre_tercero contiene el primer apellido del paciente 
 * @param  var $segundo_apellido contiene el segundo apellido del paciente 
 * @return Object $objResponse objeto de respuesta al formulario  
 */
/* ($prefijo,$empresa_id,$tipo_id_tercero,$tercero_id,$nombre_tercero,$numero) */
function RegistraFactura($frm) {
    $objResponse = new xajaxResponse();
    ///print_r($request);
    $mdl = AutoCarga::factory("FacturasDespachoSQL", "", "app", "FacturasDespacho");
    $ProductosDP = $mdl->BuscarProductosDP($empresa_id, 'DP', $numero);
    //$html .= "<pre>".print_r($numero,true)."</pre>";
    $numeroFa = $mdl->DocumentosFact();
    $objResponse->assign("Contenido", "innerHTML", $html);
    $objResponse->call("MostrarSpan");
    return $objResponse;
}

/**
 * Funcion guarda la factura
 *
 * @return Object $objResponse objeto de respuesta al formulario  
 */
function GuardarFactura($nombre_tercero, $tipo_id_tercero, $tercero_id, $numeracion, $empresa_id, $numeroDSP, $valorTotal, $prefijo) {
    $objResponse = new xajaxResponse();
    $mdl = AutoCarga::factory("FacturasDespachoSQL", "", "app", "FacturasDespacho");
    $ProductosDP = $mdl->BuscarProductosDP($empresa_id, 'DP', $numeroDSP);
    $contar = count($ProductosDP);
    //print_r($ProductosDP);

    $GuardarFactura = $mdl->InsertarFactura($empresa_id, $numeracion, $prefijo, 17, $numeroDSP, $tipo_id_tercero, $tercero_id, $valorTotal);
    for ($i = 0; $i < $contar; $i++) {
        //print_r(number_format($ProductosDP[$i]['cantidad'])."jsdkjdk");
        $GuardarFacturaD = $mdl->InsertarFacturaDetalle($prefijo, $numeracion, $ProductosDP[$i]['codigo_producto'], number_format($ProductosDP[$i]['cantidad']), $ProductosDP[$i]['fecha_vencimiento'], $ProductosDP[$i]['lote'], $ProductosDP[$i]['costo_inventario'], $empresa_id);
    }
    $Actualiza = $mdl->ActualizarDocumentos(17, $prefijo);
    return $objResponse;
}

function consultar_pedidos_clientes($empresa_id, $datos_cliente, $numero_pedido, $documentos_seleccionados) {

    $objResponse = new xajaxResponse();


    $filtros['tipo_id_tercero'] = $datos_cliente['tipo_id_tercero'];
    $filtros['tercero_id'] = $datos_cliente['tercero_id'];
    $filtros['pedido_cliente_id'] = $numero_pedido;

    $sql = AutoCarga::factory("FacturasDespachoSQL", "classes", "app", "FacturasDespacho");
    $lista_pedidos_cliente = $sql->Listado_Pedidos($empresa_id, $filtros);
    $tipoPagos=$sql->tipo_pago();
    $html .= "<table width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";
    $html .= "  <tr class=\"modulo_table_list_title\">\n";
    $html .= "      <td align=\"center\" width=\"2%\">\n";
    $html .= "          <a title='DOCUMENTOS DE DEPACHOS POR PEDIDO'>PEDIDOS DESPACHADOS AL CLIENTE: {$datos_cliente['tipo_id_tercero']}- {$datos_cliente['tercero_id']}: {$datos_cliente['nombre_tercero']}</a>";
    $html .= "      </td>\n";
    $html .= "  </tr>\n";
    
    $html .= "<tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
    $html .= "<td colspan='1'>";
    $html .= "<b>Forma de Pago: </b><select id=\"forma_pago\" name=\"forma_pago$id\" class=\"select\">";
    $html .= "<option value=\"-1\">-- seleccionar --</option>";
    foreach ($tipoPagos as $k => $valores) {
        $html .= "<option value=\"" . $valores['tipo_pago_id'] . "\">" . $valores['descripcion'] . "</option>";
    }
    $html .= "</select>";
    $html .= "</td>";
    $html .= "</tr>";
    
    if (!empty($lista_pedidos_cliente)) {
        foreach ($lista_pedidos_cliente as $key => $value) {


            $parametros_reporte = "datos[tipo_id_tercero]={$value['tipo_id_vendedor']}&
                                   datos[tercero_id]={$value['tercero_id']}&
                                   datos[nombre_tercero]={$value['nombre_tercero']}&
                                   datos[direccion]={$value['direccion']}&
                                   datos[pedido_cliente_id]={$value['pedido_cliente_id']}&
                                   datos[fecha_registro]={$value['fecha_registro']}&
                                   datos[tipo_id_vendedor]={$value['tipo_id_vendedor']}&
                                   datos[vendedor_id]={$value['vendedor_id']}&
                                   datos[empresa_id]={$value['empresa_id']}&
                                   datos[nombre]={$value['nombre']}&
                                   datos[observacion]={$value['observacion']}";

            $parametros_reporte = trim($parametros_reporte);
            $html .="<tr  class='modulo_list_claro'>";
            $html .="   <td width='100%'>
                            <table width='100%' align='center' class='modulo_table_list'>
                                <tr>
                                    <td>
                                        <ul>
                                            <li><b>PEDIDO #</b>{$value['pedido_cliente_id']} <b>Vendedor:</b>({$value['tipo_id_vendedor']} {$value['vendedor_id']} - {$value['nombre']}) <b>Fecha:</b>({$value['fecha_registro']})</li>
                                        </ul>
                                    </td>    
                                    <td>
                                        <center>
                                            
                                            <a title='IMPRIMIR' class='label_error' href=\"javascript:imprimir_pedido('{$parametros_reporte}');\">
                                                <image title=\"IMPRIMIR PEDIDO\" src=\"" . GetThemePath() . "/images/imprimir.png\" border=\"0\">
                                            </a>
                                        </center>
                                    </td>    
                                </tr> ";

            if (!empty($value['observacion'])) {
                $html .="           <tr>
                                        <td colspan='2'>
                                            <fieldset>
                                                <legend>Observacion</legend>
                                                {$value['observacion']}
                                            </fieldset>
                                         </td>
                                    </tr>";
            }

            $documentos_despacho = $sql->DocumentosDespacho($empresa_id, $value['pedido_cliente_id']);

            foreach ($documentos_despacho as $despacho => $documento) {
                $html .= "<tr>";
                $html .= "  <td>";
                $html .= "      <ul>";
                $html .= "          <ul>";
                $html .= "              <li>DOCUMENTO: <b>{$documento['prefijo']}-{$documento['numero']}</b></li>";
                $html .= "          </ul>";
                $html .= "      </ul>";
                $html .= "  </td>";
                $html .= "  <td align='center'>";
                $html .= "      <a title='IMPRIMIR' class='label_error' href=\"javascript:imprimir_documento_despacho('{$documento['empresa_id']}', '{$documento['prefijo']}', {$documento['numero']});\">
                                    <image title=\"IMPRIMIR DOC. DESPACHO \" src=\"" . GetThemePath() . "/images/imprimir.png\" border=\"0\">
                                </a>";
                $html .= "  </td>";
                $html .= "  <td>";
                $id_checkbox = $documento['empresa_id'] . "/" . $documento['prefijo'] . "/" . $documento['numero'] . "/" . trim($value['tipo_id_vendedor']) . "/" . $value['vendedor_id'] . "/" . $value['pedido_cliente_id'];

                $checked = " ";
                if (in_array($id_checkbox, $documentos_seleccionados))
                    $checked = " checked ";

                $html .= "      <input type=\"checkbox\" name='' id='' {$checked} onclick=\"administrar_checkbox(this);\" class=\"input-checkbox\" value='{$id_checkbox}'>";
                $html .= "  </td>";
                $html .= "</tr>";
            }

            $html .="       </table>";
            $html .="   </td>";
            $html .="</tr>";
        }
    } else {
        $html .= "<tr>\n";
        $html .= "  <td> <center class=\"label_error\"><h2 >¡¡No Hay Documento Factura, Parametrizado En El Sistema Para La Empresa Seleccionada. Los Botones Permaneceran Des-Habilitados!!</h2></center>  </td>\n";
        $html .= "</tr>\n";
    }

    $html .= "</table>\n";

    $objResponse->assign("lista_pedidos", "innerHTML", $html);
    return $objResponse;
}

function enviar_documentos_agrupados($tipo_id_tercero, $tercero_id, $documentos,$forma_pago) {

    $objResponse = new xajaxResponse();
    $url = ModuloGetURL("app", "FacturasDespacho", "controller", "generar_facturacion_agrupada", array('tipo_id_tercero' => $tipo_id_tercero, 'tercero_id' => $tercero_id, 'documentos' => $documentos, 'forma_pago' => $forma_pago));
    $objResponse->script("window.location='{$url}'");
    return $objResponse;
}

function sincronizar_facturas_pendientes_ws_fi($empresa_id, $prefijo, $numero_factura) {

    $objResponse = new xajaxResponse();

    $dusoft_fi = AutoCarga::factory("SincronizacionDusoftFI", "SincronizacionDusoftFI", "", "");


    $resultado_sincronizacion_ws = $dusoft_fi->facturas_venta_fi($empresa_id, $prefijo, $numero_factura);

    $mensaje_ws = $resultado_sincronizacion_ws['mensaje_ws'];
    $mensaje_bd = $resultado_sincronizacion_ws['mensaje_bd'];    

    $request['resultado_sincronizacion_ws']['mensaje_ws'] = $mensaje_ws;
    $request['resultado_sincronizacion_ws']['mensaje_bd'] = $mensaje_bd;

    $url = ModuloGetURL("app", "FacturasDespacho", "controller", "Facturas_Generadas", array("buscador" => array('numero'=>$numero_factura), 'resultado_ws' => $request['resultado_sincronizacion_ws']));
    $mensaje = " Mensaje ws : {$mensaje_ws} \n Mensaje bd = {$mensaje_bd} ";

    //$objResponse->call("Paginador('{$empresa_id}', {$prefijo}, document.getElementById('fecha_inicio').value, document.getElementById('fecha_final').value)");
    $objResponse->alert("{$mensaje}");
    $objResponse->script("window.location='{$url}';");
    

    return $objResponse;
}

?>