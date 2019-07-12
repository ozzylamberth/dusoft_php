<?php

/**
 * @package IPSOFT-SIIS
 * @version $Id: VentaProductos.php,v 1.1 2010/06/03 20:43:44 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Hugo F  Manrique
 */
/**
 * Archivo Xajax
 * Tiene como responsabilidad hacer el manejo de las funciones
 * que son invocadas por medio de xajax
 *
 * @package IPSOFT-SIIS
 * @version $Revision: 1.1 $
 * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Hugo Manrique
 */

/**
 * Funcion de xajax para la busqueda de productos
 *
 * @param array $form Arreglo de datos con la informcion de la forma
 * @param integer $offset Identificador de la p�gina actual
 *
 * @return object
 */


function BuscarProductos($form, $offset) {
    $objResponse = new xajaxResponse();

    $mdl = AutoCarga::factory("AdministracionFarmaciaSQL", "classes", "app", "VentaFarmacia");

    //$mdl->debug = true;
    if ($form['codigo_producto'] == "" && $form['codigo_alterno'] == "" && $form['codigo_barras'] == "" && $form['descripcion'] == "") {
        $objResponse->alert("Debe seleccionar al menos un parametro!");
    } else {

        $datos = $mdl->BuscaroListarProductoBodega($form, $offset);
//        $objResponse->alert($datos);
//        return $objResponse;
    }

    $html = "";
    if (!empty($datos)) {
        $ctl = AutoCarga::factory("ClaseUtil");

        $action = "BuscarProductos(";
        $html .= "  <table width=\"100%\" class=\"modulo_table_list\" align=\"center\">";
        $html .= "	  <tr class=\"formulacion_table_list\" >\n";
        //$html .= "      <td width=\"20%\">LABORATORIO</td>\n";
        $html .= "      <td width=\"10%\">CODIGO</td>\n";
        $html .= "      <td width=\"20%\">MOLECULA</td>\n";
        $html .= "      <td width=\"35%\">NOMBRE PRODUCTO.</td>\n";
        $html .= "      <td width=\"10%\">VENCIMIENTO</td>\n";
        $html .= "      <td width=\"10%\">LOTE</td>\n";
        $html .= "      <td width=\"15%\">EXI.ACTUAL</td>\n";
        $html .= "      <td width=\"15%\">VR.UNI</td>\n";
        $html .= "      <td width=\"10%\">CANT.</td>\n";
        $html .= "      <td width=\"5%\" >OP</td>\n";
        $html .= "    </tr>\n";

        foreach ($datos as $key => $dtl) {
            $est = ($est == "modulo_list_claro") ? "modulo_list_oscuro" : "modulo_list_claro";
            if (strlen($dtl['descripcion']) > strlen($dtl['descripcion_abreviada'])) {
                $GLOBALS['descripcion'] = $dtl['descripcion'];
            } else {
                $GLOBALS['descripcion'] = $dtl['descripcion'];
            }
            $dat = "xajax.getFormValues('buscador_resultados'),xajax.getFormValues('Forma12'),new Array('" . $dtl['fecha_vencimiento'] . "','" . $dtl['lote'] . "','" . $dtl['codigo_producto'] . "','" . $dtl['precio_venta'] . "')";



            $html .= "    <tr class=\"" . $est . "\" height=\"16\">\n";
            //$html .= "      <td align=\"center\">" . $dtl['laboratorio'] . "</td>\n";
            $html .= "      <td class=\"normal_10AN\">" . $dtl['codigo_producto'] . "</td>\n";
            $html .= "      <td align=\"center\">" . $dtl['molecula'] . "</td>\n";
            $html .= "      <td class=\"label\" align=\"left\">\n";
                        
            $html .= $GLOBALS['descripcion'];
            //$html .= "        ".$ctl->NombreProducto($dtl,$form['empresa_id'])."\n";
            $html .= "      </td>\n";
            $html .= "      <td align=\"center\" class=\"normal_10AN\">" . $dtl['fecha'] . "</td>\n";
            $html .= "      <td align=\"left\" class=\"normal_10AN\">" . $dtl['lote'] . "</td>\n";
            //$html .= "      <td class=\"label\" align=\"right\">".FormatoValor($dtl['existencia_actual'],NULL,false)."</td>\n";
            $html .= "      <td class=\"label\" align=\"right\">" . $dtl['existencia_actual'] . "</td>\n";
            //$html .= "      <td class=\"label\" align=\"right\">$".FormatoValor($dtl['precio_venta'],NULL,false)."</td>\n";
            $html .= "      <td class=\"label\" align=\"right\">$" . $dtl['precio_venta'] . "</td>\n";
            $html .= "      <td>\n";
            if ($dtl['existencia_actual'] > 0) {
                $html .= "        <input type=\"text\" class=\"input-text\" name=\"cantidad[" . $dtl['fecha_vencimiento'] . "][" . $dtl['lote'] . "][" . $dtl['codigo_producto'] . "]\" style=\"width:100%\"  value=\"\" onkeypress=\"return acceptNum(event)\" >\n";
                $html .= "        <input type=\"hidden\" name=\"existencia[" . $dtl['fecha_vencimiento'] . "][" . $dtl['lote'] . "][" . $dtl['codigo_producto'] . "]\" style=\"width:100%\"  value=\"" . $dtl['existencia'] . "\" onkeypress=\"return acceptNum(event)\" >\n";
            }
            $html .= "      </td>\n";
            $html .= "      <td>\n";
            if ($dtl['existencia_actual'] > 0) {
                $html .= "        <div id=\"producto_" . $key . "\">\n";
                $html .= "          <a href=\"#producto_" . $key . "\" onclick=\"xajax_ValidarDatosProductoVenta(" . $dat . ")\">\n";
                $html .= "            <img src=\"" . GetThemePath() . "/images/checkno.png\" border=\"0\">\n";
                $html .= "          </a>\n";
                $html .= "        </div>\n";
            }
            $html .= "      </td>\n";
            $html .= "    </tr>\n";
        }
        $html .= "	</table><br>\n";
        $pgh = AutoCarga::factory("ClaseHTML");
        $html .= $pgh->ObtenerPaginadoXajax($mdl->conteo, $mdl->pagina, $action, "0", 10, "productos_buscador");
    } else {
        $html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA <br>" . $mdl->mensajeDeError . "</center><br>\n";
    }
    $objResponse->assign("productos_buscador", "innerHTML", $html);
    $objResponse->assign("mensaje", "innerHTML", "");
    return $objResponse;
}

/**
 * Funcion donde se valida la entrada de los productos seleccionados
 *
 * @param array $form1 Arreglo de datos de la forma de datos a ingresar
 * @param array $form2 Arreglo de datos de la forma del documento
 * @param array $datos Arreglo de datos con la informacion del producto
 *
 * @return object
 */
function ValidarDatosProductoVenta($form1, $form2, $datos) {
    $objResponse = new xajaxResponse();

    $rpt = AutoCarga::factory("ReporteFacturaSQL", "classes", "app", "VentaFarmacia");

    if (empty($form1['cantidad'][$datos[0]][$datos[1]][$datos[2]])) {
        $objResponse->alert("NO SE HA DIGITADO LA CANTIDAD A DESPACHAR");
    } else if ($form1['cantidad'][$datos[0]][$datos[1]][$datos[2]] > $form1['existencia'][$datos[0]][$datos[1]][$datos[2]]) {
        $objResponse->alert("LA CANTIDAD INGRESADA " . $form1['cantidad'][$datos[0]][$datos[1]][$datos[2]] . " ES MAYOR A LA CANTIDAD EXISTENTE " . $form['existencia'][$datos[0]][$datos[1]][$datos[2]]);
    } else {
        $iva_pdto = 0;
        //added valor iva del producto
        $iva = $rpt->Valida_IvaProd($datos[2]);
        if (FormatoValor($iva['porc_iva']) <> 0) {
            $iva_pdto = (($form1['cantidad'][$datos[0]][$datos[1]][$datos[2]] * $datos[3]) * ($iva['porc_iva'] / 100));
        }

        $mdl = AutoCarga::factory("VentaFarmaciaSQL", "classes", "app", "VentaFarmacia");
        $rst = $mdl->IngresarDocumentoTemporal($form1, $form2, $datos, $iva_pdto);

        if (!$rst)
            $objResponse->alert($mdl->mensajeDeError);
        else {
            if (!$form2['documento']) {
                $form2['documento'] = $rst;
                $objResponse->assign("documento", "value", $rst);
            }
            $productos = $mdl->ObtenerProductosTemporal($form2['documento'], $form2);            
            $html = FormaDetallePedido($productos);
            $objResponse->assign("productos_asignados", "innerHTML", $html);
            $objResponse->assign("productos_buscador", "innerHTML", "");
            $objResponse->assign("mensaje", "innerHTML", "");
        }
    }
    return $objResponse;
}

/**
 * Funcion donde se muestra la informacion del pedido
 *
 * @param array $form Arreglo de datos con la informcion de la forma
 *
 * @return object
 */
function MostrarDetallePedido($form) {
    $objResponse = new xajaxResponse();
    $mdl = AutoCarga::factory("VentaFarmaciaSQL", "classes", "app", "VentaFarmacia");

    $productos = $mdl->ObtenerProductosTemporal($form['documento'], $form);
    $html = FormaDetallePedido($productos);
    $objResponse->assign("productos_asignados", "innerHTML", $html);
    $objResponse->assign("mensaje", "innerHTML", "");
    return $objResponse;
}

/**
 * Funcion donde se hace el proceso para eliminar un item seleccionado
 *
 * @param integer $consecutivo Identificador del consecutivo
 * @param array $form Arreglo de datos con la informcion de la forma
 *
 * @return object
 */
function EliminarTemporal($consecutivo, $form) {
    $objResponse = new xajaxResponse();
    $mdl = AutoCarga::factory("VentaFarmaciaSQL", "classes", "app", "VentaFarmacia");
    $rst = $mdl->EliminarTemporal($consecutivo);
    if (!$rst)
        $objResponse->alert($mdl->mensajeDeError);
    else {
        $productos = $mdl->ObtenerProductosTemporal($form['documento'], $form);
        $html = FormaDetallePedido($productos);
        $objResponse->assign("productos_asignados", "innerHTML", $html);
        $objResponse->assign("productos_buscador", "innerHTML", "");
        $objResponse->assign("mensaje", "innerHTML", "EL ITEM HA SIDO BORRADO DE LA LISTA");
    }
    return $objResponse;
}

/**
 * Funcion donde se hace el proceso para eliminar un documento
 *
 * @param array $form Arreglo de datos con la informcion de la forma
 *
 * @return object
 */
function EliminarDocumento($form) {
    $objResponse = new xajaxResponse();
    $mdl = AutoCarga::factory("VentaFarmaciaSQL", "classes", "app", "VentaFarmacia");
    $rst = $mdl->EliminarDocumentoTemporal($form['documento']);
    if (!$rst)
        $objResponse->alert($mdl->mensajeDeError);
    else {
        $objResponse->assign("productos_asignados", "innerHTML", "");
        $objResponse->assign("productos_buscador", "innerHTML", "");
        $objResponse->assign("documento", "value", "");
        $objResponse->assign("mensaje", "innerHTML", "EL DOCUMENTO HA SIDO BORRADO CORRECTAMENTE");
    }
    return $objResponse;
}

/**
 * Funcion donde se hace el proceso para la busqueda de la informacion del tercero
 *
 * @param array $form Arreglo de datos con la informcion de la forma
 *
 * @return object
 */
function BuscarTercero($form) {
    $objResponse = new xajaxResponse();

    $mensaje = "";
    if ($form['tipo_id_tercero'] == '-1')
        $mensaje = "FAVOR INDICAR EL TIPO DE IDENTIFICACION";
    else if (!$form['tercero_id'])
        $mensaje = "FAVOR INDICAR LA IDENTIFICACION DEL CLIENTE";

    if ($mensaje == "") {
        $mdl = AutoCarga::factory("VentaFarmaciaSQL", "classes", "app", "VentaFarmacia");
        $pct = $mdl->ObtenerDatosCliente($form);
        $objResponse->assign("nombre_tercero", "value", $pct['nombre_tercero']);
        $objResponse->assign("telefono_tercero", "value", $pct['telefono']);
        $objResponse->assign("direccion_tercero", "value", $pct['direccion']);
        $objResponse->assign("celular_tercero", "value", $pct['celular']);
        if ($pct['tipo_pais'] != "") {
            $objResponse->assign("pais", "value", $pct['tipo_pais']);
            $objResponse->assign("dpto", "value", $pct['tipo_dpto']);
            $objResponse->assign("mpio", "value", $pct['tipo_mpio']);
            $objResponse->assign("ubicacion", "innerHTML", $pct['pais'] . " - " . $pct['departamento'] . " - " . $pct['municipio']);
        }
        $objResponse->assign("boton_guardar", "style.display", "block");
    }
    $objResponse->assign("error_tercero", "innerHTML", $mensaje);
    return $objResponse;
}

/**
 * Funcion donde se hace el proceso para mostrar la forma de ingreso del un tercero
 *
 * @param array $form Arreglo de datos con la informcion de la forma
 *
 * @return object
 */
function DatosTercero($form) {
    $objResponse = new xajaxResponse();
    $pais = GetVarConfigAplication('DefaultPais');
    $dpto = GetVarConfigAplication('DefaultDpto');
    $mpio = GetVarConfigAplication('DefaultMpio');

    $mdl = AutoCarga::factory("VentaFarmaciaSQL", "classes", "app", "VentaFarmacia");

    $NomPais = $mdl->ObtenerNombrePais($pais);
    $NomDpto = $mdl->ObtenerNombreDepartamento($pais, $dpto);
    $NomMpio = $mdl->ObtenerNombreCiudad($pais, $dpto, $mpio);
    //$empresa_nombre = $empresa['empresa_nombre'];
    //$objResponse->assign("id_tercero", "value", "");
    //$objResponse->assign("nombre_tercero", "value", "ZONA SALUD");
    //$objResponse->assign("direccion_tercero", "value", "CALLE 21N #4N-48");
    //$objResponse->assign("telefono_tercero", "value", "");
    //$objResponse->assign("celular_tercero", "value", "");

    $objResponse->assign("capa_productos", "style.display", "none");
    $objResponse->assign("pais", "value", $pais);
    $objResponse->assign("dpto", "value", $dpto);
    $objResponse->assign("mpio", "value", $mpio);
    $objResponse->assign("capa_tercero", "style.display", "block");
    $objResponse->assign("temporal", "value", $form['documento']);
    $objResponse->assign("ubicacion", "innerHTML", $NomPais . " - " . $NomDpto . " - " . $NomMpio);
    $objResponse->assign("boton_guardar", "style.display", "block");

    return $objResponse;
}

/**
 * Funcion donde se crea el html del pedido
 *
 * @param array $productos Arreglo de datos con la informacion del los productos
 *
 * @return string
 */
function FormaDetallePedido($productos) {
    $html = "";

    $rpt = AutoCarga::factory("ReporteFacturaSQL", "classes", "app", "VentaFarmacia");

    if (!empty($productos)) {
        $ctl = AutoCarga::factory("ClaseUtil");
        $html .= "<table width=\"100%\" align=\"center\">\n";
        $html .= "  <tr>\n";
        $html .= "    <td colspan=\"2\">\n";
        $html .= "      <fieldset class=\"fieldset\">\n";
        $html .= "        <legend class=\"normal_10AN\">ITEMS SELECCIONADOS</legend>\n";
        $html .= "        <table width=\"98%\" class=\"modulo_table_list\" align=\"center\">";
        $html .= "	        <tr class=\"modulo_table_list_title\" >\n";
        //$html .= "            <td width=\"20%\">LABORATORIO</td>\n";
        $html .= "            <td width=\"10%\">CODIGO</td>\n";
        $html .= "            <td width=\"20%\">MOLECULA</td>\n";
        $html .= "            <td width=\"25%\">NOMBRE PRODUCTO..</td>\n";
        $html .= "            <td width=\"10%\">VENCIMIENTO</td>\n";
        $html .= "            <td width=\"10%\">LOTE</td>\n";
        $html .= "            <td width=\"10%\">CANT.</td>\n";
        $html .= "            <td width=\"15%\">VR.UNI</td>\n";
        $html .= "            <td width=\"15%\">VR.TOTAL</td>\n";
        $html .= "            <td width=\"10%\">IVA</td>\n";
        $html .= "            <td width=\"%\">OP</td>\n";
        $html .= "          </tr>\n";

        $total = 0;
        $totIva = 0; //added acumulador valores iva
        $vaLiva = 0; // valor iva
        foreach ($productos as $key => $dtl) {
            $est = ($est == "modulo_list_claro") ? "modulo_list_oscuro" : "modulo_list_claro";

            $iva = $rpt->Valida_IvaProd($dtl['codigo_producto']);

            if (FormatoValor($iva['porc_iva']) <> 0) {
                //valor del iva si toca adicionarlo al producto
                $vaLiva = round((($dtl['cantidad'] * $dtl['total_costo']) * ($iva['porc_iva'] / 100)), 0);
            }
            //if (strlen($dtl['descripcion']) > strlen($dtl['descripcion_abreviada'])) {
            //    $descripcion = $dtl['descripcion'];
            //} else {
            //    $descripcion = $dtl['descripcion_abreviada'];
            //}
            $html .= "          <tr class=\"" . $est . "\" height=\"16\">\n";
            $html .= "            <td class=\"normal_10AN\">" . $dtl['codigo_producto'] . "</td>\n";
            //$html .= "            <td align=\"center\">" . $dtl['laboratorio'] . "</td>\n";
            $html .= "            <td align=\"center\">" . $dtl['molecula'] . "</td>\n";
            $html .= "            <td class=\"label\" align=\"left\">\n";
            $html .= $dtl['descripcion'];
            //$html .= "              " . $dtl['descripcion'] . "\n";
            $html .= "            </td>\n";
            $html .= "            <td align=\"center\" class=\"normal_10AN\">" . $dtl['fecha_vencimiento'] . "</td>\n";
            $html .= "            <td align=\"left\" class=\"normal_10AN\">" . $dtl['lote'] . "</td>\n";
            $html .= "            <td class=\"label\" align=\"right\">" . $dtl['cantidad'] . "</td>\n";
            $html .= "            <td class=\"label\" align=\"right\">$" . $dtl['total_costo'] . "</td>\n";
            $html .= "            <td class=\"label\" align=\"right\">$" . round(($dtl['cantidad'] * $dtl['total_costo']), 0) . "</td>\n";
            $html .= "            <td class=\"label\" align=\"right\">$" . $vaLiva . "</td>\n";
            $html .= "            <td>\n";
            $html .= "              <div id=\"producto2_" . $key . "\">\n";
            $html .= "                <a href=\"#producto2_" . $key . "\" onclick=\"xajax_EliminarTemporal(" . $dtl['consecutivo'] . ",xajax.getFormValues('Forma12'))\">\n";
            $html .= "                  <img src=\"" . GetThemePath() . "/images/elimina.png\" border=\"0\">\n";
            $html .= "                </a>\n";
            $html .= "              </div>\n";
            $html .= "            </td>\n";
            $html .= "          </tr>\n";
            $total += $dtl['cantidad'] * $dtl['total_costo'];
            $totIva += $vaLiva;
        }

        $html .= "          <tr class=\"formulacion_table_list\">\n";
        $html .= "	          <td colspan=\"8\" align=\"right\">TOTAL A PAGAR + IVA</td>\n";
        //$html .= "            <td class=\"modulo_list_claro\" align=\"right\">$".formatoValor($total)."</td>\n";
        $html .= "            <td class=\"modulo_list_claro\" align=\"right\">$" . ($total + $totIva) . "</td>\n";
        $html .= "	          <td colspan=\"2\"></td>\n";
        $html .= "	        </tr>\n";
        $html .= "	      </table>\n";
        $html .= "	    </fieldset>\n";
        $html .= "	  </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr>\n";
        $html .= "    <td align=\"center\">\n";
        $html .= "      <input type=\"button\" name=\"cancelar\" value=\"Cancelar Documento\" class=\"input-submit\" onclick=\"xajax_EliminarDocumento(xajax.getFormValues('Forma12'))\">\n";
        $html .= "    </td>\n";
        $html .= "    <td align=\"center\">\n";
        $html .= "      <input type=\"button\" name=\"crear\" value=\"Crear Factura\" class=\"input-submit\" onclick=\"xajax_DatosTercero(xajax.getFormValues('Forma12'))\">\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= "<br>\n";
    }
    return $html;
}

/**
 * Funcion donde se evalua el tipo de pago seleccionado
 *
 * @param array $form Arreglo de datos con la informacion de la forma
 * @param string Identificador del tipo de pago
 *
 * @return object
 */
function RealizarPago($form, $opcion) {
    $objResponse = new xajaxResponse();

    $cnt = AutoCarga::factory('CajaHTML', 'views', 'app', 'VentaFarmacia');
    $mdl = AutoCarga::factory("VentaFarmaciaSQL", "classes", "app", "VentaFarmacia");
    switch ($opcion) {
        case "E": $html .= $cnt->FormaPagoEfectivo($form);
            break;
        case "C":
            $confirman = $mdl->ObtenerEntidadesConfirma();
            $bancos = $mdl->ObtenerBancos();
            $informacion = $mdl->ObtenerInformacionChequeTemp($form);
            //$informacion['valor_cancelar'] = $form['valor_cancelar'];
            $informacion['valor_final'] = $form['valor_final'];
            $html .= $cnt->FormaPagoCheque($informacion, $confirman, $bancos);
            break;
        case "R":
            $confirman = $mdl->ObtenerEntidadesConfirma();
            $tarjetas = $mdl->ObtenerTarjetas();
            $informacion = $mdl->ObtenerInformacionTarjetaCTemp($form);
            //$informacion['valor_cancelar'] = $form['valor_cancelar'];
            $informacion['valor_final'] = $form['valor_final'];
            $html .= $cnt->FormaPagoTarjetaCredito($informacion, $confirman, $tarjetas);
            break;
        case "D":
            $tarjetas = $mdl->ObtenerTarjetas();
            $informacion = $mdl->ObtenerInformacionTarjetaDTemp($form);
            //$informacion['valor_cancelar'] = $form['valor_cancelar'];
            $informacion['valor_final'] = $form['valor_final'];
            $html .= $cnt->FormaPagoTarjetaDebito($informacion, $tarjetas);
            break;
        case "B": $html .= $cnt->FormaPagoBonos($form);
            break;
    }
    $objResponse->assign("contenido_pagos", "innerHTML", $html);
    $objResponse->assign("contenido_total", "style.display", "block");
    $objResponse->assign("capa_datos", "style.display", "block");
    return $objResponse;
}

/**
 * Funcion donde se validan los datos del pago realizado
 *
 * @param array $form Arreglo de datos de la forma de datos a ingresar
 * @param array $form2 Arreglo de datos de la forma del documento
 * @param string Identificador del tipo de pago
 *
 * @return object
 */
function EvaluarDatos($form, $form1, $opcion) {
    //$form1 : forma realizar_pagos
    //$form:    forma especifica del metodo de pago
    //$opcion: id de tipo de pago E,B, R, D

    $objResponse = new xajaxResponse();
    $mensaje = "";
    $valor1 = "";
    $mdl = AutoCarga::factory("VentaFarmaciaSQL", "classes", "app", "VentaFarmacia");
    $pagos = $mdl->ObtenerInformacionPagosTemp($form1['documento']);

    switch ($opcion) {
        case "E":
            $label = "efectivo";
            if (!is_numeric($form['valor']))
                $mensaje = "EL VALOR A PAGAR POSEE UN FORMATO DE NUMERO INCORRECTO";
            else {
                //$saldo = $form1['valor_cancelar'] - ($form['valor'] + $pagos['total_cheque'] + $pagos['total_bono'] + $pagos['total_debito'] + $pagos['total_credito']);
                $saldo = $form1['valor_final'] - ($form['valor'] + $pagos['total_cheque'] + $pagos['total_bono'] + $pagos['total_debito'] + $pagos['total_credito']);
                if ($saldo < 0) {
                    //$valor1 = $form1['valor_cancelar'] - ($pagos['total_cheque'] + $pagos['total_bono'] + $pagos['total_debito'] + $pagos['total_credito']);
                    $valor1 = $form1['valor_final'] - ($pagos['total_cheque'] + $pagos['total_bono'] + $pagos['total_debito'] + $pagos['total_credito']);
                    $objResponse->assign("cambio", "innerHTML", FormatoValor($saldo * (-1), null, false));
                }
                else
                    $objResponse->assign("cambio", "innerHTML", "0");
            }
            break;
        case "C":
            //$saldo = $form1['valor_cancelar'] - ($pagos['total_efectivo'] + $pagos['total_bono'] + $pagos['total_debito'] + $pagos['total_credito']);
            $saldo = $form1['valor_final'] - ($pagos['total_efectivo'] + $pagos['total_bono'] + $pagos['total_debito'] + $pagos['total_credito']);

            $label = "cheque";
            if ($form['entidad'] == "-1")
                $mensaje = "NO SE HA SELECCIONADO ENTIDAD CONFIRMA";
            else if ($form['fecha_confirma'] == "")
                $mensaje = "LA FECHA CONFIRMACION POSEE UN FORMATO DE FECHA NO VALIDO";
            else if ($form['numero'] == "")
                $mensaje = "NO SE HA INGRESADO EL NUMERO DE CONFIRMACION";
            else if ($form['numero_cheque'] == "")
                $mensaje = "NO SE HA INGRESADO EL NUMERO DEl CHEQUE";
            else if ($form['banco'] == "-1")
                $mensaje = "NO SE HA SELECCIONADO BANCO";
            else if ($form['numero_cuenta'] == "")
                $mensaje = "NO SE HA INGRESADO EL NUMERO DE CUENTA CORRIENTE";
            else if ($form['fecha_cheque'] == "")
                $mensaje = "LA FECHA CHEQUE POSEE UN FORMATO DE FECHA NO VALIDO";
            else if ($form['fecha_transaccion'] == "")
                $mensaje = "LA FECHA DE TRANSACCION POSEE UN FORMATO DE FECHA NO VALIDO";
            else if (!is_numeric($form['valor']))
                $mensaje = "EL VALOR A PAGAR POSEE UN FORMATO DE NUMERO INCORRECTO";
            else if ($saldo > $form['valor'])
                $mensaje = "EL VALOR INGRESADO ES MENOR AL SALDO A PAGAR: $" . FormatoValor($saldo);
            // else if($saldo < $form['valor'])
            // $mensaje = "EL VALOR INGRESADO ES MAYOR AL SALDO A PAGAR: $".FormatoValor($saldo);
            if ($mensaje == "") {
                $rst = $mdl->IngresarPagoChequeTemporal($form, $form1);
                if (!$rst) {
                    $objResponse->alert($mdl->mensajeDeError);
                    return $objResponse;
                }
            }
            break;
        case "R":
            //$saldo = $form1['valor_cancelar'] - ($pagos['total_efectivo'] + $pagos['total_bono'] + $pagos['total_debito'] + $pagos['total_cheque']);
            $saldo = $form1['valor_final'] - ($pagos['total_efectivo'] + $pagos['total_bono'] + $pagos['total_debito'] + $pagos['total_cheque']);

            $label = "credito";
            if ($form['entidad'] == "-1")
                $mensaje = "NO SE HA SELECCIONADO ENTIDAD CONFIRMA";
            else if ($form['fecha_confirma'] == "")
                $mensaje = "LA FECHA CONFIRMACION POSEE UN FORMATO DE FECHA NO VALIDO";
            else if ($form['numero'] == "")
                $mensaje = "NO SE HA INGRESADO EL NUMERO DE CONFIRMACION";
            else if ($form['tarjeta'] == "-1")
                $mensaje = "NO SE HA SELECCIONADO TARJETA";
            else if ($form['num_tarjeta'] == "")
                $mensaje = "NO SE HA INGRESADO EL NUMERO DE TARJETA";
            else if ($form['socio'] == "")
                $mensaje = "NO SE HA INGRESADO SOCIO";
            else if ($form['fecha_expiracion'] == "")
                $mensaje = "LA FECHA DE EXPIRACION POSEE UN FORMATO DE FECHA NO VALIDO";
            else if ($form['fecha_transaccion'] == "")
                $mensaje = "LA FECHA DE TRANSACCION POSEE UN FORMATO DE FECHA NO VALIDO";
            else if (!is_numeric($form['valor']))
                $mensaje = "EL VALOR A PAGAR POSEE UN FORMATO DE NUMERO INCORRECTO";
            else if ($saldo > $form['valor'])
                $mensaje = "EL VALOR INGRESADO ES MENOR AL SALDO A PAGAR: $" . FormatoValor($saldo);
            // else if($saldo < $form['valor'])
            // $mensaje = "EL VALOR INGRESADO ES MAYOR AL SALDO A PAGAR: $".FormatoValor($saldo);
            if ($mensaje == "") {
                $rst = $mdl->IngresarPagoTarjetaCTemporal($form, $form1);
                if (!$rst) {
                    $objResponse->alert($mdl->mensajeDeError);
                    return $objResponse;
                }
            }
            break;
        case "D":
            //$saldo = $form1['valor_cancelar'] - ($pagos['total_efectivo'] + $pagos['total_bono'] + $pagos['total_cheque'] + $pagos['total_credito']);
            $saldo = $form1['valor_final'] - ($pagos['total_efectivo'] + $pagos['total_bono'] + $pagos['total_cheque'] + $pagos['total_credito']);

            $label = "debito";
            if ($form['tarjeta'] == '-1')
                $mensaje = "NO SE HA SELECCIONADO TARJETA";
            else if ($form['num_tarjeta'] == "")
                $mensaje = "NO SE HA INGRESADO EL NUMERO DE TARJETA";
            else if ($form['num_autorizacion'] == "")
                $mensaje = "NO SE HA INGRESADO EL NUMERO DE AUTORIZACION";
            else if (!is_numeric($form['valor']))
                $mensaje = "EL VALOR A PAGAR POSEE UN FORMATO DE NUMERO INCORRECTO";
            else if ($saldo > $form['valor'])
                $mensaje = "EL VALOR INGRESADO ES MENOR AL SALDO A PAGAR: $" . FormatoValor($saldo);
            // else if($saldo < $form['valor'])
            // $mensaje = "EL VALOR INGRESADO ES MAYOR AL SALDO A PAGAR: $".FormatoValor($saldo);
            if ($mensaje == "") {
                $rst = $mdl->IngresarPagoTarjetaDTemporal($form, $form1);
                if (!$rst) {
                    $objResponse->alert($mdl->mensajeDeError);
                    return $objResponse;
                }
            }
            break;
        case "B":
            //$saldo = $form1['valor_cancelar'] - ($pagos['total_efectivo'] + $pagos['total_cheque'] + $pagos['total_debito'] + $pagos['total_credito']);
            $saldo = $form1['valor_final'] - ($pagos['total_efectivo'] + $pagos['total_cheque'] + $pagos['total_debito'] + $pagos['total_credito']);

            $label = "bono";
            if (!is_numeric($form['valor']))
                $mensaje = "EL VALOR A PAGAR POSEE UN FORMATO DE NUMERO INCORRECTO";
            else if ($saldo > $form['valor'])
                $mensaje = "EL VALOR INGRESADO ES MENOR AL SALDO A PAGAR: $" . FormatoValor($saldo);
            // else if($saldo < $form['valor'])
            // $mensaje = "EL VALOR INGRESADO ES MAYOR AL SALDO A PAGAR: $".FormatoValor($saldo);
            break;
    }
    if ($mensaje == "") {
        $valor = FormatoValor($form['valor'], null, "");
        $valor2 = $valor1;
        if ($valor1 == "") {
            $valor1 = $valor;
            $valor2 = $form['valor'];
        }

        $mdl->IngresarPagoEfectivoBonos($valor2, $form1['documento'], $label);

        $objResponse->assign("label_" . $label, "innerHTML", FormatoValor($valor, null, false));
        $objResponse->assign("h_" . $label, "value", $valor1);
        $objResponse->call("CerraPagos");
        $html = " valor = document.getElementById('label_efectivo').innerHTML*1 + ";
        $html .= "         document.getElementById('h_cheque').value*1 + ";
        $html .= "         document.getElementById('h_credito').value*1 + ";
        $html .= "         document.getElementById('h_debito').value*1 + ";
        $html .= "         document.getElementById('h_bono').value*1; ";
        $html .= " document.getElementById('total').innerHTML = valor;";
        $html .= " document.getElementById('saldo').value = document.realizar_pagos.valor_final.value*1 - valor;";
        //$html .= " document.getElementById('saldo').value = document.realizar_pagos.valor_cancelar.value*1 - valor;";
        $objResponse->script($html);
    }
    $objResponse->assign("error_" . $label, "innerHTML", $mensaje);
    return $objResponse;
}

/**
 * funcion donde se evalua el pago total hecho y si se puede o no continuar
 * con la creacion de la factura
 *
 * @param array $form Arreglo de datos de la forma a evaluar
 *
 * @return object
 */
function IngresarPagos($form) {
    $objResponse = new xajaxResponse();
    $form['cancelado'] = $form['h_efectivo'] + $form['h_cheque'] + $form['h_credito'] + $form['h_debito'] + $form['h_bono'];

    $mensaje = "";
    //if($form['valor_cancelar'] > $form['cancelado'] )
    if ($form['valor_final'] > $form['cancelado'])
    //$mensaje = "EL VALOR PAGADO ($".FormatoValor($form['cancelado']).") ES MENOR AL VALOR A PAGAR ($".FormatoValor($form['valor_cancelar']).") ";
        $mensaje = "EL VALOR PAGADO ($" . FormatoValor($form['cancelado']) . ") ES MENOR AL VALOR A PAGAR ($" . FormatoValor($form['valor_final']) . ") ";
    else {
        $objResponse->script("Continuar()");
        return $objResponse;
    }
    $objResponse->assign("error_pagos", "innerHTML", $mensaje);
    return $objResponse;
}

?>