<?php

/**
 * Archivo Xajax
 * Tiene como responsabilidad hacer el manejo de las funciones
 * que son invocadas por medio de xajax
 *
 * @package IPSOFT-SIIS
 * @version $Revision: 1.11 $
 * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Sandra Viviana Pantoja Torres 
 */

/**
 * Funcion que permite seleccionar el numero de contrato relacionado a un Proveedor  para modificar el contrato
 * @param string $noId cadena numero de identificacion del proveedor
 * @param string $tipoId cadena con el tipo de identificacion del proveedor
 * @return Object $objResponse objeto de respuesta al formulario  
 */
function ListadoTerceros($TipoIdTercero, $TerceroId, $Descripcion, $offset) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("FacturacionProveedorSQL", "classes", "app", "Inv_FacturacionProveedor");

    $datos = $sql->ObtenerTerceros($TipoIdTercero, $TerceroId, $Descripcion, $offset);

    if (!empty($datos)) {

        $action['paginador'] = "Paginador('" . $TipoIdTercero . "','" . $TerceroId . "','" . $Descripcion . "'";

        $pghtml = AutoCarga::factory("ClaseHTML");
        $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo, $sql->pagina, $action['paginador']);
        $url = ModuloGetURL("app", "Inv_FacturacionProveedor", "controller", "MenuPrincipal");
        //		print_r($_REQUEST);

        $html .= "<table width=\"95%\" class=\"modulo_table_list_title\" align=\"center\">";
        $html .= "  <tr align=\" class=\"modulo_table_list_title\" >\n";
        $html .= "      <td width=\"15%\">IDENTIFICACION</td>\n";
        $html .= "      <td width=\"25%\">PROVEEDOR</td>\n";
        $html .= "      <td width=\"10%\">TELEFONOS</td>\n";
        $html .= "      <td width=\"18%\">DIRECCI�N</td>\n";
        $html .= "      <td  width=\"25%\" >E-M@IL</td>\n";
        $html .= "      <td width=\"3%\">MENU.</td>\n";
        $html .= "  </tr>\n";
        $est = "modulo_list_claro";
        $back = "#DDDDDD";
        foreach ($datos as $key => $dtl) {
            $url_final = $url . "&codigo_proveedor_id=" . $dtl['codigo_proveedor_id'] . "&datos[empresa]=" . $_REQUEST['datos']['empresa'] . "";
            $html .= "  <tr class=\"modulo_list_claro\">\n";
            $html .= "      <td align=\"center\">" . $dtl['tipo_id_tercero'] . " " . $dtl['tercero_id'] . "</td>\n";
            $html .= "      <td align=\"left\">" . $dtl['nombre_tercero'] . "</td>\n";
            $html .= "      <td align=\"left\">" . $dtl['telefono'] . "</td>\n";
            $html .= "      <td align=\"left\">" . $dtl['direccion'] . "</td>\n";
            $html .= "      <td align=\"left\">" . $dtl['email'] . "</td>\n";
            $html .= "      <td align=\"center\">\n";
            $html .= "      <a href=\"" . $url_final . "\">\n";
            $html .= "      <img src=\"" . GetThemePath() . "/images/ingresar.png\" border=\"0\" title=\"parametrizacion\">";
            $html .= "      </a>\n";
            $html .= "      </td>\n";
            $html .= "  </tr>\n";
        }

        $html .= "	</table><br>\n";

        $html .= "	<br>\n";
    } else {
        $html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
    }

    $objResponse->assign("ListadoTerceros", "innerHTML", $html);
    return $objResponse;
}

function BuscarOrdenDeCompra($EmpresaId, $CodigoProveedorId, $fecha_Inicio, $Fecha_Final, $offset) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("FacturacionProveedorSQL", "classes", "app", "Inv_FacturacionProveedor");
    $datos = $sql->ConsultarOrdenesCompraProveedor($EmpresaId, $CodigoProveedorId, $fecha_Inicio, $Fecha_Final, $offset);


    $pghtml = AutoCarga::factory("ClaseHTML");

    if (!empty($datos)) {
        $action['paginador'] = "Paginador('" . $EmpresaId . "','" . $CodigoProveedorId . "','" . $fecha_Inicio . "','" . $Fecha_Final . "'";
        $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo, $sql->pagina, $action['paginador']);
        $html .= "	<table align=\"center\" border=\"0\" width=\"60%\" class=\"modulo_table_list\">\n";
        $html .= "  </table>\n";
        $html .= "  <br>\n";

        $html .= "	<table align=\"center\" border=\"0\" width=\"50%\" class=\"modulo_table_list\">\n";
        //print_r($request);
        if ($fecha_Inicio != "" && $Fecha_Final != "") {
            $html .= "		<tr class=\"formulacion_table_list\" aling=\"center\">\n";
            $html .= "			<td width=\"20%\" colspan=\"5\">Entre " . $fecha_Inicio . " Y " . $Fecha_Final . "</td>\n";
            $html .= "		</tr>\n";
        }

        $html .= "		<tr class=\"formulacion_table_list\" >\n";
        $html .= "			<td width=\"20%\">#OC</td>\n";
        $html .= "			<td width=\"30%\">FECHA</td>\n";
        $html .= "			<td width=\"30%\">USUARIO</td>\n";
        $html .= "			<td width=\"30%\">CANT.REP.PARCIALES</td>\n";
        $html .= "		</tr>\n";

        foreach ($datos as $k1 => $dtl) {
            $est = ($est == 'modulo_list_oscuro') ? 'modulo_list_claro' : 'modulo_list_oscuro';
            $bck = ($bck == "#CCCCCC") ? "#DDDDDD" : "#CCCCCC";
            $RepPar = $sql->ConsultarRecepcionesParciales($EmpresaId, $dtl['orden_pedido_id']);
            $num = count($RepPar);
            $html .= "		<tr " . $clase . " onmouseout=mOut(this,\"" . $bck . "\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
            $html .= "			<td align=\"center\"><b>" . $dtl['orden_pedido_id'] . "</b></td>\n";
            $html .= "			<td align=\"center\">" . $dtl['fecha_orden'] . "</td>\n";
            $html .= "			<td align=\"center\">" . $dtl['nombre'] . "</td>\n";
            $html .= "			<td align=\"center\">";

            $html .= "				<table align=\"center\" border=\"1\" width=\"100%\" class=\"modulo_table_list\">\n";
            $html .= "				<tr align=\"center\">";
            if ($num > 0) {
                $html .= "				<td width=\"50%\">";
                $html .= "					<b>" . $num . "</b>";
                $html .= "				</td>";
                $html .= "				<td width=\"50%\">";
                $html .= "					<a onclick=\"xajax_RecepcionesParcialesPorOC('" . $EmpresaId . "','" . $dtl['orden_pedido_id'] . "');\">";
                $html .="					<img title=\"FACTURAR PROVEEDOR\" src=\"" . GetThemePath() . "/images/producto_precio.png\" border=\"0\"></a>\n";
                $html .= "				</td>";
            } else {
                $html .= "				<td width=\"50%\">";
                $html .= "				" . $num . "";
                $html .= "				</td>";
                $html .= "				<td width=\"50%\">";
                $html .= "				.";
                $html .= "				</td>";
            }
            $html .= "				</tr>";
            $html .= "				</table>";

            $html .= "</td>\n";
            $html .= "		</tr>\n";
        }
        $html .= "		</table>\n";
        $html .= "		<br>\n";
    } else {
        $html .= "<center>\n";
        $html .= "  <label class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</label>\n";
        $html .= "</center>\n";
    }

    $objResponse->assign("OrdenesCompra", "innerHTML", $html);
    return $objResponse;
}

function RecepcionesParcialesPorOC($EmpresaId, $OrdenPedidoId, $codigo_proveedor_id) {
    $objResponse = new xajaxResponse();


    $sql = AutoCarga::factory("FacturacionProveedorSQL", "classes", "app", "Inv_FacturacionProveedor");
    $datos = $sql->ConsultarRecepcionesParciales($EmpresaId, $OrdenPedidoId, $codigo_proveedor_id);

    $mensaje_ws = $_REQUEST['mensaje_ws'];
    $mensaje_bd = $_REQUEST['mensaje_bd'];

    if (!empty($mensaje_ws) || !empty($mensaje_bd)) {
        $html_aux .= "<center><p><label class=\"\"><b>Exito En El Ingreso!!!</b></label></p>";
        $html_aux .= "  <p><label class=\"label_error\">Resultado Sincronizacion</label></p>";
        $html_aux .= "  <p><label class=\"label_error\">Respuesta Ws: </label>{$mensaje_ws}</p>";
        $html_aux .= "  <p><label class=\"label_error\">Respuesta BD: </label>{$mensaje_bd}</p>";
        $html_aux .= "</center>";
        $html .= $html_aux;
    }
 
    if (!empty($datos)) {

 
        $html .= "					<form name=\"RecepcionesParcialesSeleccion\" id=\"RecepcionesParcialesSeleccion\" method=\"post\">";

        $html .= "	<table align=\"center\" border=\"0\" width=\"80%\" class=\"modulo_table_list\">\n";
        //print_r($request);
        $html .= "		<tr class=\"formulacion_table_list\" >\n";
        $html .= "			<td width=\"20%\" colspan=\"7\">Recepciones, Para La Orden De Compra # " . $OrdenPedidoId . "</td>\n";
        $html .= "		</tr>\n";

        $html .= "		<tr class=\"formulacion_table_list\" >\n";
        $html .= "			<td width=\"20%\">#RECEPCION</td>\n";
        $html .= "			<td width=\"30%\">DOCUMENTO RECEPCION</td>\n";
        $html .= "			<td width=\"30%\">FECHA</td>\n";
        $html .= "			<td width=\"30%\">USUARIO</td>\n";
        $html .= "			<td width=\"30%\">VER DETALLE..</td>\n";
        $html .= "			<td width=\"30%\">OBSERVACION</td>\n";
        $html .= "			<td width=\"30%\">OPC</td>\n";
        $html .= "		</tr>\n";

        $i = 0;
        foreach ($datos as $k1 => $dtl) {
            $est = ($est == 'modulo_list_oscuro') ? 'modulo_list_claro' : 'modulo_list_oscuro';
            $bck = ($bck == "#CCCCCC") ? "#DDDDDD" : "#CCCCCC";

            $html .= "		<tr " . $clase . " onmouseout=mOut(this,\"" . $bck . "\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
            $html .= "			<td align=\"center\"><b>" . $dtl['recepcion_parcial_id'] . "</b></td>\n";
            $html .= "			<td align=\"center\"><b>" . $dtl['prefijo'] . "-" . $dtl['numero'] . "</b></td>\n";
            $html .= "			<td align=\"center\">" . $dtl['fecha_registro'] . "</td>\n";
            $html .= "			<td align=\"center\">" . $dtl['nombre'] . "</td>\n";
            $html .= "			<td align=\"center\">";
            $html .= "					<a onclick=\"xajax_VerDetalleRecepcionParcial('" . $EmpresaId . "','" . $dtl['orden_pedido_id'] . "','" . $dtl['recepcion_parcial_id'] . "','{$codigo_proveedor_id}');\">";
            $html .="					<img title=\"VER DETALLE DE LA RECEPCION\" src=\"" . GetThemePath() . "/images/informacion.png\" border=\"0\"></a>\n";
            $html .= "			</td>\n";
            $html .= "			<td>\n";
            $html .= "				" . $dtl['observacion'];
            /* $html .= "			<input type=\"hidden\" value=\"".$dtl['observacion']."\" id=\"observacion".$dtl['recepcion_parcial_id']."\" name=\"observacion".$dtl['recepcion_parcial_id']."\">"; */
            $html .= "			</td>\n";

            if ($dtl['sw_facturado'] == '1') {
                $html .= "			<td align=\"center\">";

                $html .="			<input disabled checked type=\"checkbox\" class=\"input-checkbox\" name=\"RP" . $i . "\" value=\"" . $dtl['recepcion_parcial_id'] . "\">\n";
                $html .= "			</td>\n";
            } else {
                $html .= "			<td align=\"center\">";
                $html .="			<input type=\"checkbox\" class=\"input-checkbox\" name=\"RP" . $i . "\" value=\"" . $dtl['recepcion_parcial_id'] . "\">\n";
                $html .= "			</td>\n";
            }
            $html .= "		</tr>\n";
            $i++;
        }
        $html .= "		<tr " . $clase . " onmouseout=mOut(this,\"" . $bck . "\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
        $html .= "			<td align=\"center\" colspan=\"7\">\n";


        $html .= "			<input type=\"button\" value=\"FACTURAR\" class=\"modulo_table_list\" onclick=\"xajax_FacturarRecepcionesParciales(xajax.getFormValues('RecepcionesParcialesSeleccion'),'" . $OrdenPedidoId . "','" . $codigo_proveedor_id . "','" . $i."');\">\n";
        $html .= "			</td>";
        $html .= "		</tr>\n";


        $html .= "		</table>\n";
        $html .= "					 </form>";

        $html .= "		<br>\n";
    } else {
        $html .= "<center>\n";
        $html .= "  <label class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</label>\n";
        $html .= "</center>\n";
    }
    $objResponse->script("document.getElementById('orden_pedido_id').value=" . $OrdenPedidoId . ";");
    $objResponse->script("document.getElementById('codigo_proveedor_id').value=" . $codigo_proveedor_id . ";");
    $objResponse->assign("RecepcionesParciales", "innerHTML", $html);
    $objResponse->script("tabPane.setSelectedIndex(1);");
    return $objResponse;
}

function VerDetalleRecepcionParcial($EmpresaId, $orden_pedido_id, $recepcion_parcial_id, $codigo_proveedor_id) {
    $objResponse = new xajaxResponse();

    $sql = AutoCarga::factory("FacturacionProveedorSQL", "classes", "app", "Inv_FacturacionProveedor");
    $sql_aux = AutoCarga::factory("FacturasDespachoSQL", "classes", "app", "FacturasDespacho");

    $datos_proveedor = $sql->ConsultarTerceroProveedor($codigo_proveedor_id);
    $datos_proveedor = $datos_proveedor[0];
    /* echo "<pre>";
      print_r($datos_proveedor);
      echo "</pre>"; */

    $datos = $sql->DetalleRecepcionParcial($EmpresaId, $orden_pedido_id, $recepcion_parcial_id);
    $parametros_retencion = $sql_aux->Parametros_Retencion($EmpresaId);

    /* echo "<pre>";
      print_r($datos);
      echo "</pre>"; */

    if (!empty($datos)) {
        $html .= "					<form name=\"RecepcionesParcialesSeleccion\" id=\"RecepcionesParcialesSeleccion\" method=\"post\">";

        $html .= "	<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">\n";
        //print_r($request);
        $html .= "		<tr class=\"formulacion_table_list\" >\n";
        $html .= "			<td width=\"20%\" colspan=\"8\">Recepciones, Para La Orden De Compra #" . $orden_pedido_id . " - Recepcion #" . $recepcion_parcial_id . "</td>\n";
        $html .= "		</tr>\n";

        $html .= "		<tr class=\"formulacion_table_list\" >\n";
        $html .= "			<td width=\"20%\">CODIGO PRODUCTO</td>\n";
        $html .= "			<td width=\"30%\">DESCRIPCION</td>\n";
        $html .= "			<td width=\"30%\">CANTIDAD</td>\n";
        $html .= "			<td width=\"30%\">VALOR</td>\n";
        $html .= "			<td width=\"30%\">% IVA</td>\n";
        $html .= "			<td width=\"30%\">LOTE</td>\n";
        $html .= "			<td width=\"30%\">FECHA VENCIMIENTO</td>\n";
        $html .= "			<td width=\"30%\">SUBTOTAL</td>\n";
        $html .= "		</tr>\n";

        $_cantidad = 0;
        $_subtotal = 0;
        $_iva = 0;
        $_retencion_fuente = 0;
        $_retencion_ica = 0;
        $_retencion_iva = 0;
        $_impuesto_cree = 0;


        $i = 0;
        foreach ($datos as $k1 => $dtl) {
            $est = ($est == 'modulo_list_oscuro') ? 'modulo_list_claro' : 'modulo_list_oscuro';
            $bck = ($bck == "#CCCCCC") ? "#DDDDDD" : "#CCCCCC";

            $html .= "		<tr " . $clase . " onmouseout=mOut(this,\"" . $bck . "\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
            $html .= "			<td align=\"center\"><b>" . $dtl['codigo_producto'] . "</b></td>\n";
            $html .= "			<td align=\"center\"><b>" . $dtl['descripcion'] . "</b></td>\n";
            $html .= "			<td align=\"center\">" . $dtl['cantidad'] . "</td>\n";
            $html .= "			<td align=\"center\">" . FormatoValor($dtl['valor'], 2) . "</td>\n";
            $html .= "			<td align=\"center\">" . $dtl['porc_iva'] . "</td>\n";
            $html .= "			<td align=\"center\">" . $dtl['lote'] . "</td>\n";
            $html .= "			<td align=\"center\">" . $dtl['fecha_vencimiento'] . "</td>\n";
            $html .= "			<td align=\"center\">" . FormatoValor(($dtl['valor'] * $dtl['cantidad']), 2) . "</td>\n";
            $Total = $Total + ($dtl['valor'] * $dtl['cantidad']);
            $porcIva = ($dtl['porc_iva'] / 100) + 1;
            $SubTotal = $dtl['valor'] * $dtl['cantidad'];
            $Iva = $Iva + ($SubTotal - ($SubTotal / $porcIva));
            $html .= "		</tr>\n";

            // Calculo de Valores
            $_cantidad += $dtl['cantidad'];
            $_subtotal += ($dtl['valor'] * $dtl['cantidad']) / (($dtl['porc_iva'] / 100) + 1);
            $_iva += ($dtl['valor'] * $dtl['cantidad']) - (($dtl['valor'] * $dtl['cantidad']) / (($dtl['porc_iva'] / 100) + 1));
            //$_iva += $Iva;
        }

        if ($parametros_retencion['sw_rtf'] == '2' || $parametros_retencion['sw_rtf'] == '3')
            if ($_subtotal >= $parametros_retencion['base_rtf'])
                $_retencion_fuente = $_subtotal * ($datos_proveedor['porcentaje_rtf'] / 100);

        if ($parametros_retencion['sw_ica'] == '2' || $parametros_retencion['sw_ica'] == '3')
            if ($_subtotal >= $parametros_retencion['base_ica'])
                $_retencion_ica = $_subtotal * ($datos_proveedor['porcentaje_ica'] / 1000);

        if ($parametros_retencion['sw_reteiva'] == '2' || $parametros_retencion['sw_reteiva'] == '3') {

            if ($_iva >= $parametros_retencion['base_reteiva'])
                $_retencion_iva = $_iva * ($datos_proveedor['porcentaje_reteiva'] / 100);
        }



        if (!is_null($datos_proveedor['porcentaje_cree'])) {
            $_impuesto_cree = (($datos_proveedor['porcentaje_cree'] / 100) * $_subtotal);
        }

        $_total = ((((($_subtotal + $_iva) - $_retencion_fuente) - $_retencion_ica) - $_retencion_iva) - $_impuesto_cree);


        $salida1 .= "	<tr  class=\"modulo_list_claro\">\n";
        $salida1 .= "		<td colspan=\"11\">";
        $salida1 .= "			<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $salida1 .= "				<tr align=\"center\" class=\"label\">";
        $salida1 .= "					<td>";
        $salida1 .= "						<u>CANTIDAD</u>";
        $salida1 .= "					</td>";
        $salida1 .= "					<td>";
        $salida1 .= "						<u>SUBTOTAL</u>";
        $salida1 .= "					</td>";
        $salida1 .= "					<td>";
        $salida1 .= "						<u>IVA</u>";
        $salida1 .= "					</td>";
        $salida1 .= "					<td>";
        $salida1 .= "						<u>RET-FTE</u>";
        $salida1 .= "					</td>";
        $salida1 .= "					<td>";
        $salida1 .= "						<u>RETE-ICA</u>";
        $salida1 .= "					</td>";
        $salida1 .= "					<td>";
        $salida1 .= "						<u>RETE-IVA</u>";
        $salida1 .= "					</td>";

        //$salida1 .= "					<td>";
        //$salida1 .= "						<u>IMPTO CREE</u>";
        //$salida1 .= "					</td>";

        $salida1 .= "					<td>";
        $salida1 .= "						<u>VALOR TOTAL</u>";
        $salida1 .= "					</td>";
        $salida1 .= "				</tr>";
        $salida1 .= "				<tr align=\"center\" >";
        $salida1 .= "					<td>";
        $salida1 .= "						" . FormatoValor($_cantidad);
        $salida1 .= "					</td>";
        $salida1 .= "					<td>";
        $salida1 .= "						$" . FormatoValor($_subtotal, 2);
        $salida1 .= "					</td>";
        $salida1 .= "					<td>";
        $salida1 .= "						$" . FormatoValor($_iva, 2);
        $salida1 .= "					</td>";
        $salida1 .= "					<td>";
        $salida1 .= "						$" . FormatoValor($_retencion_fuente, 2);
        $salida1 .= "					</td>";
        $salida1 .= "					<td>";
        $salida1 .= "						$" . FormatoValor($_retencion_ica, 2);
        $salida1 .= "					</td>";
        $salida1 .= "					<td>";
        $salida1 .= "						$" . FormatoValor($_retencion_iva, 2);
        $salida1 .= "					</td>";

        $salida1 .= "					<td>";
        $salida1 .= "						$" . FormatoValor($_total, 2);
        $salida1 .= "					</td>";
        $salida1 .= "				</tr>";
        $salida1 .= "			</table>";
        $salida1 .= "		</td>";
        $salida1 .= "	</tr>";

        $html .= $salida1;

        /* $html .= "    <tr class=\"modulo_list_oscuro\">";
          $html .= "        <td><B>IVA:</B> $" . FormatoValor($Iva, 2) . "</td>";
          $html .= "    </tr>";
          $html .= "    <tr class=\"modulo_list_claro\">";
          $html .= "        <td><B>TOTAL:</B> $" . FormatoValor($Total, 2) . "</td>";

          $html .= "    </tr>"; */
        $html .= "		</table>\n";
        $html .= "					 </form>";

        $html .= "		<br>\n";
    } else {
        $html .= "<center>\n";
        $html .= "  <label class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</label>\n";
        $html .= "</center>\n";
    }

    $objResponse->assign("Contenido", "innerHTML", $html);
    $objResponse->call("MostrarSpan");
    return $objResponse;
}

function FacturarRecepcionesParciales($Formulario, $OrdenPedidoId, $codigo_proveedor_id,$observaciones) {

    $recepcion_parcial_id=implode(",", $Formulario);
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("FacturacionProveedorSQL", "classes", "app", "Inv_FacturacionProveedor");
    $datos = $sql->ConsultarRecepcionesParcialesRecepcion($_REQUEST['datos']['empresa'], $Formulario['orden_pedido_id'], $codigo_proveedor_id,$recepcion_parcial_id);
    $observaciones=$datos;

    
    $i = 0;

    $recepcion_parcial = array();

    foreach ($Formulario as $k => $valor) {

        array_push($recepcion_parcial, $valor);

        $hidden .="<input name=\"RP" . $i . "\" type=\"hidden\" value=\"" . $valor . "\"> ";
        $DetalleRecepcion = $sql->DetalleRecepcionParcial($_REQUEST['datos']['empresa'], $Formulario['orden_pedido_id'], $valor);

        //Para Calcular el Valor Total con el que va a quedar la factura para que el descuento no vaya a quedar mayor que el valor de la misma.
        foreach ($DetalleRecepcion as $k => $dtl) {
            $valor_total = $valor_total + ($dtl['valor'] * $dtl['cantidad']);
            $cantidades = $cantidades + $dtl['cantidad'];
        }

        $i++;
    }

    // Validar que sean del mismo tipo de documento
    $validacion_tipo_documento = $sql->validar_tipos_documentos($_REQUEST['datos']['empresa'], $codigo_proveedor_id, implode(",", $recepcion_parcial));

    if (count($validacion_tipo_documento) > 1) {
        $objResponse->alert('Se deben Facturar Documentos con el mismo prefijo');
        return $objResponse;
    }

    // Validar parametrizacion de prefijo FI
    $validacion_prefijo_fi = $sql->validar_parametrizacion_prefijo_fi($_REQUEST['datos']['empresa'], $codigo_proveedor_id, implode(",", $recepcion_parcial));

    if ($validacion_prefijo_fi['parametrizado'] == 'no')
        $objResponse->alert('Este Documeno No tiene Parametrizado el prefijo FI, por lo tanto, no se sincronizara con el DUSOFT FI');


    $prefijo_fi = $sql->obtener_prefijo_fi($_REQUEST['datos']['empresa'], $validacion_tipo_documento[0]['prefijo']);
    $prefijo_fi = $prefijo_fi['prefijo_fi'];

    if (!empty($Formulario)) {

    
        $html .= "<center>\n";
        $html .= "  <label class=\"label_error\">ADVERTENCIA:!DESPUES DE FACTURAR, NO SE PUEDE MODIFICAR EL DOCUMENTO</label>\n";
        $html .= "</center>\n";

        $html .= "<center>\n";
        $html .= "  <div class=\"label_error\" id=\"error\"></div>\n";
        $html .= "</center>\n";

        $html .= "					<form name=\"FormaAntesFacturar\" id=\"FormaAntesFacturar\" method=\"post\">";

        $html .= "	<table align=\"center\" border=\"0\" width=\"80%\" class=\"modulo_table_list\">\n";
        //print_r($request);
        $html .= "		<tr class=\"formulacion_table_list\" >\n";
        $html .= "			<td width=\"20%\" colspan=\"7\">Se Van a Unificar Las Recepciones " . implode(",", $recepcion_parcial) . "</td>\n";
        $html .= "		</tr>\n";

        $html .= "		<tr >\n";
        $html .= "			<td width=\"40%\"><b>NUMERO FACTURA PROVEEDOR</b></td><td width=\"60%\"><input style=\"width:100%;height:100%\" id='nFactura' onkeyup='eventoFactura()' type=\"text\" class=\"input-text\" name=\"numero_factura\" ></td>\n";
        $html .= "		</tr >\n";
        $html .= "		<tr >\n";
        $html .= "		<input type=\"hidden\" name=\"valor_total_factura\" value=\"" . $valor_total . "\">";
        $html .= "			<td width=\"40%\"><b>VALOR TOTAL FACTURA</b></td><td width=\"60%\"><input style=\"width:100%;height:100%\" type=\"text\" value=\"" . FormatoValor($valor_total) . "\" class=\"input-text\" name=\"valor_total_factura_\" readonly=\"true\"></td>\n";
        $html .= "		</tr >\n";
        $html .= "		<tr >\n";
        $html .= "			<td width=\"40%\"><b>VALOR DE DESCUENTO</b></td><td width=\"60%\"><input value=\"0\" onkeypress=\"return acceptNum(event)\" style=\"width:100%;height:100%\" type=\"text\" class=\"input-text\" name=\"valor_descuento\"></td>\n";
        $html .= "		</tr >\n";
        /* $html .= "		<tr >\n";
          $html .= "			<td width=\"40%\"><b>FLETE</b></td><td width=\"60%\"><input value=\"0\" onkeypress=\"return acceptNum(event)\" style=\"width:100%;height:100%\" type=\"text\" class=\"input-text\" name=\"valor_flete\"></td>\n";
          $html .= "		</tr >\n"; */
        $html .= "		<tr >\n";
        $html .= "			<td width=\"40%\" rowspan ='2'><b>OBSERVACIONES</b></td>\n";
        $html .= "		</tr >\n";
        
        $html .= "		<tr >\n";
        $html .= "			<td width=\"100%\">";
        $html .= "                          <table border='0' width=\"100%\"  class=\"modulo_table_list\">";
        $html .= "                                  <tr>";
        $html .=                                        "   <td width=\"100%\"><textarea class=\"textarea\" style=\"width:100%;height:100%\"  readonly id=\"observacionesfija\" name=\"observacionesfija\">".$observaciones."</textarea></td>";
        $html .= "                                  </tr>";
        $html .= "                                  <tr>";
        $html .= "                                          <td width=\"100%\"><textarea class=\"textarea\" style=\"width:100%;height:100%\"  id=\"observaciones\" name=\"observaciones\"></textarea></td>\n";
        $html .= "                                  </tr>";
        $html .= "                          </table>";
        $html .= "			</td>";
//        $html .= "			<input type=\"hidden\" id=\"observacionFactura\" value=\"" . $observaciones . "\">";
        $html .= "		</tr >\n";
        $html .= "		<tr " . $clase . " onmouseout=mOut(this,\"" . $bck . "\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
        $html .= "			<td align=\"center\" colspan=\"3\">\n";
        $html .= "			" . $hidden . " ";

        $html .= "		<input type=\"hidden\" name=\"empresa_id\" value=\"" . $Formulario['empresa_id'] . "\">";
        $html .= "		<input type=\"hidden\" name=\"orden_pedido_id\" value=\"" . $Formulario['orden_pedido_id'] . "\">";
        $html .= "		<input type=\"hidden\" name=\"codigo_proveedor_id\" value=\"" . $codigo_proveedor_id . "\">";
        $html .= "		<input type=\"hidden\" name=\"prefijo_fi\" value='{$prefijo_fi}'>";

        $html .= "			<input type=\"button\" value=\"FACTURAR..\" class=\"modulo_table_list\" onclick=\"Validar(xajax.getFormValues('FormaAntesFacturar'));\">\n";
        $html .= "			</td>";
        $html .= "		</tr>\n";



        $html .= "		</table>\n";
        $html .= "					 </form>";
    } else {
        $html .= "<center>\n";
        $html .= "  <label class=\"label_error\">NO SE ENCONTRARON PARAMETROS PARA FACTURAR</label>\n";
        $html .= "</center>\n";
    }
    $html .= "		<br>\n";



    $objResponse->assign("Contenido", "innerHTML", $html);
    $objResponse->call("MostrarSpan");

    return $objResponse;
}

/**
 * Funcion que permite mostrar los laboratorios
 * @param array  $form vector con toda la forma
 * @return Object $objResponse objeto de respuesta al formulario  
 */
function Facturar($Formulario, $OrdenPedidoId) {

    $objResponse = new xajaxResponse();

    $dusoft_fi = AutoCarga::factory("SincronizacionDusoftFI", "SincronizacionDusoftFI", "", "");
    $sql = AutoCarga::factory("FacturacionProveedorSQL", "classes", "app", "Inv_FacturacionProveedor");
    $sql_2 = AutoCarga::factory("FacturasDespachoSQL", "classes", "app", "FacturasDespacho");
    $Parametros_Retencion = $sql_2->Parametros_Retencion($_REQUEST['datos']['empresa']);
    $DatosProveedor = $sql->ConsultarTerceroProveedor($_REQUEST['codigo_proveedor_id']);

    $Datos = $sql->ConsultarRecepcionesParciales($_REQUEST['datos']['empresa'], $OrdenPedidoId, $Formulario['codigo_proveedor_id']);

    $num = count($Formulario);
    $num = ($num - 6);


    /* $resultado_sincronizacion_ws = $dusoft_fi->sincronizacion_dusoft_financiero($_REQUEST['datos']['empresa'], 1597, 'Y10');
      print_r($resultado_sincronizacion_ws);
      exit(); */

    $TokenFactura = $sql->IngresarFacturaCabecera($Formulario, $Datos, $DatosProveedor, $Parametros_Retencion);

    if ($TokenFactura) {

        for ($i = 0; $i < $num; $i++) {
            $DetalleRecepcion = $sql->DetalleRecepcionParcial($_REQUEST['datos']['empresa'], $OrdenPedidoId, $Formulario['RP' . $i]);
            /* print_r($DetalleRecepcion); */
            $TokenDetalle = $sql->IngresarFacturaCabecera_D($DetalleRecepcion, $Formulario, $_REQUEST['codigo_proveedor_id']);

            $TokenCambioEstado = $sql->CambiarEstadoRecepcionParcial($Formulario['RP' . $i]);
        }

        // Sincronizacion FI 
        $url = ModuloGetURL("app", "Inv_FacturacionProveedor", "controller", "cuentas_x_pagar_fi", array('empresa_id' => $_REQUEST['datos']['empresa'], 'codigo_proveedor_id' => $_REQUEST['codigo_proveedor_id'], 'numero_factura' => trim($Formulario['numero_factura'])));
        $script = "window.location='{$url}'";
        $objResponse->script("alert('Factura Registrada Correctamente!!!')");
        $objResponse->script($script);

        /* $resultado_sincronizacion_ws = $dusoft_fi->sincronizacion_dusoft_financiero($_REQUEST['datos']['empresa'], $_REQUEST['codigo_proveedor_id'], trim($Formulario['numero_factura']));                       
          $html .= "<center>\n";
          $html .= "  <p><label class=\"\">Exito En El Ingreso!!!</label></p>\n";
          $html .= "  <p><label class=\"label_error\">Resultado Sincronizacion</label></p>\n";
          $html .= "  <p><label class=\"\">Respuesta Ws: {$resultado_sincronizacion_ws['mensaje_ws']}</p></label>\n";
          $html .= "  <p><label class=\"\">Respuesta BD: {$resultado_sincronizacion_ws['mensaje_bd']}</p></label>\n";
          $html .= "</center>\n";

          $objResponse->script("xajax_RecepcionesParcialesPorOC('" . trim($_REQUEST['datos']['empresa']) . "','" . trim($OrdenPedidoId) . "','" . trim($Formulario['codigo_proveedor_id']) . "');");
          $objResponse->assign("Contenido", "innerHTML", $html); */
    } else {
        $objResponse->alert("Error En El Ingreso de la Factura!!");
    }

    return $objResponse;
}

function ListarFacturasProveedor($EmpresaId, $CodigoProveedorId, $fecha_Inicio, $Fecha_Final, $offset) {

    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("FacturacionProveedorSQL", "classes", "app", "Inv_FacturacionProveedor");
    $datos = $sql->ListarFacturasProveedor($EmpresaId, $CodigoProveedorId, $fecha_Inicio, $Fecha_Final, $offset);

    $url .= "&datos[empresa]=" . $_REQUEST['datos']['empresa'] . "&codigo_proveedor_id=" . $_REQUEST['codigo_proveedor_id'] . "&bodegax=" . $_REQUEST['bodegax'] . "&utility=" . $_REQUEST['utility'] . "&movimiento_bodega=" . $_REQUEST['movimiento_bodega'] . "";
    //$url  .= "&datos[empresa]=".$_REQUEST['datos']['empresa']."&codigo_proveedor_id=".$_REQUEST['codigo_proveedor_id']."";	
    $UrlVerFactura = ModuloGetURL("app", "Inv_FacturacionProveedor", "controller", "VerFactura");
    $pghtml = AutoCarga::factory("ClaseHTML");

    if (!empty($datos)) {
        $action['paginador'] = "Paginador('" . $EmpresaId . "','" . $CodigoProveedorId . "','" . $fecha_Inicio . "','" . $Fecha_Final . "'";
        $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo, $sql->pagina, $action['paginador']);
        $html .= "	<table align=\"center\" border=\"0\" width=\"60%\" class=\"modulo_table_list\">\n";
        $html .= "  </table>\n";
        $html .= "  <br>\n";

        $html .= "	<table align=\"center\" border=\"0\" width=\"50%\" class=\"modulo_table_list\">\n";
        //print_r($request);
        if ($fecha_Inicio != "" && $Fecha_Final != "") {
            $html .= "		<tr class=\"formulacion_table_list\" aling=\"center\">\n";
            $html .= "			<td width=\"20%\" colspan=\"6\">Entre " . $fecha_Inicio . " Y " . $Fecha_Final . "</td>\n";
            $html .= "		</tr>\n";
        }

        $html .= "		<tr class=\"formulacion_table_list\" >\n";
        $html .= "			<td width=\"20%\">#FACTURA</td>\n";
        $html .= "			<td width=\"30%\">FECHA</td>\n";
        $html .= "			<td width=\"30%\">USUARIO</td>\n";
        $html .= "			<td width=\"30%\">VER/IMPRIMIR</td>\n";
        $html .= "			<td width=\"30%\">SINCRONIZADO DUSOFT FI</td>\n";
        $html .= "			<td width=\"30%\">OP</td>\n";
        $html .= "		</tr>\n";

        foreach ($datos as $k1 => $dtl) {
            $est = ($est == 'modulo_list_oscuro') ? 'modulo_list_claro' : 'modulo_list_oscuro';
            $bck = ($bck == "#CCCCCC") ? "#DDDDDD" : "#CCCCCC";
            $url .= "&numero_factura=" . $dtl['numero_factura'] . "";
            $Url = $UrlVerFactura . "" . $url;
            $html .= "		<tr " . $clase . " onmouseout=mOut(this,\"" . $bck . "\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
            $html .= "			<td align=\"center\"><b>" . $dtl['numero_factura'] . "</b></td>\n";
            $html .= "			<td align=\"center\">" . $dtl['fecha_registro'] . "</td>\n";
            $html .= "			<td align=\"center\">" . $dtl['nombre'] . "</td>\n";
            $html .= "			<td align=\"center\">";
            $html .= "					<a href=\"" . $Url . "\">";
            $html .="					<img title=\"VER DETALLE DE LA RECEPCION\" src=\"" . GetThemePath() . "/images/imprimir.png\" border=\"0\"></a>\n";
            $html .= "			</td>\n";

            $class_error = 'label_error';
            if ($dtl['estado'] == '0')
                $class_error = '';

            $html .= "			<td align=\"center\" class='{$class_error}'>{$dtl['descripcion_estado']}</td>\n";


            $url_images = GetThemePath() . "/images/desconectado.png";
            $funcion_sincronizar = "confirmar_sincronizacion('{$dtl['empresa_id']}', {$dtl['codigo_proveedor_id']}, '{$dtl['numero_factura']}');";
            if ($dtl['estado'] == '0') {
                $url_images = GetThemePath() . "/images/conectado.png";
                $funcion_sincronizar = "alert('La Factura ya esta Sincronizada');";
            }

            $html .= "			<td align=\"center\" ><a href='#' onclick=\"{$funcion_sincronizar}\"><img title=\"SINCRONIZAR CON FI\" src='{$url_images}' border=\"0\"></a></td>\n";
            $html .= "		</tr>\n";
        }
        $html .= "		</table>\n";
        $html .= "		<br>\n";
    } else {
        $html .= "<center>\n";
        $html .= "  <label class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA..</label>\n";
        $html .= "</center>\n";
    }

    $objResponse->assign("OrdenesCompra", "innerHTML", $html);
    return $objResponse;
}

function sincronizar_facturas_pendientes_ws_fi($empresa_id, $codigo_proveedor_id, $numero_factura) {

    $objResponse = new xajaxResponse();

    $dusoft_fi = AutoCarga::factory("SincronizacionDusoftFI", "SincronizacionDusoftFI", "", "");
    
    $resultado_sincronizacion_ws = $dusoft_fi->cuentas_x_pagar_fi($empresa_id, $codigo_proveedor_id,$numero_factura);
    
    $mensaje_ws = $resultado_sincronizacion_ws['mensaje_ws'];
    $mensaje_bd = $resultado_sincronizacion_ws['mensaje_bd'];
    
    $mensaje = " Mensaje ws : {$mensaje_ws} \n Mensaje bd = {$mensaje_bd} ";
    
    $objResponse->call("Paginador('{$empresa_id}', {$codigo_proveedor_id}, document.getElementById('fecha_inicio').value, document.getElementById('fecha_final').value)");
    $objResponse->alert("{$mensaje}");
    
    return $objResponse;
}

?>