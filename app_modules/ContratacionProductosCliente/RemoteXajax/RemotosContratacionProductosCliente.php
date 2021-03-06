<?php

/**
 * Archivo Xajax
 * Tiene como responsabilidad hacer el manejo de las funciones
 * que son invocadas por medio de xajax
 *
 * @package IPSOFT-SIIS
 * @version $Revision: 1.11 $
 * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Mauricio Adrian Medina Santacruz
 */

/**
 * Funcion que permite seleccionar el numero de contrato relacionado a un Proveedor  para modificar el contrato
 * @param string $noId cadena numero de identificacion del proveedor
 * @param string $tipoId cadena con el tipo de identificacion del proveedor
 * @return Object $objResponse objeto de respuesta al formulario  
 */
function BuscarTerceroCliente() {
    $objResponse = new xajaxResponse();
    $datos = SessionGetVar("Datos");
    /* print_r($datos); */
    $sql = AutoCarga::factory("ContratacionProductosClienteSQL", "", "app", "ContratacionProductosCliente");
    $tipos_ids_terceros = $sql->Tipos_Ids_Terceros();

    $select .= "<select id=\"tipo_id_tercero\" name=\"tipo_id_tercero\" class=\"select\">";
    $select .= "<option value=\"\">-- TODOS --</option>";
    foreach ($tipos_ids_terceros as $k => $valor) {
        $select .= "<option value=\"" . $valor['tipo_id_tercero'] . "\">" . $valor['tipo_id_tercero'] . "</option>";
    }
    $select .= "</select>";
    $html .= "<form name=\"Buscador_\" id=\"Buscador_\" action=\"\" method=\"post\">";
    $html .= "<table  width=\"100%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
    $html .= "  <tr align=\"center\" >\n";
    $html .= "      <td  class=\"formulacion_table_list\">TIPO IDENTIFICACION</td>\n";
    $html .= "      <td  class=\"formulacion_table_list\">" . $select . "</td>\n";
    $html .= "      <td  class=\"formulacion_table_list\"># IDENTIFICACION</td>\n";
    $html .= "      <td  class=\"formulacion_table_list\"><input name=\"tercero_id\" id=\"tercero_id\" class=\"input-text\" style=\"width:100%\"></td>\n";
    $html .= "      <td  class=\"formulacion_table_list\">NOMBRE</td>\n";
    $html .= "      <td  class=\"formulacion_table_list\"><input name=\"nombre_tercero\" id=\"nombre_tercero\" class=\"input-text\" style=\"width:100%\"></td>\n";
    $html .= "	</tr>";
    $html .= "  <tr align=\"center\" >\n";
    $html .= "      <input type=\"hidden\" value=\"" . $datos['empresa'] . "\" name=\"empresa_id\" id=\"empresa_id\"></td>\n";
    $html .= "      <td  class=\"formulacion_table_list\" colspan=\"6\"><input type=\"button\" value=\"BUSCAR TERCERO\" class=\"input-submit\" onclick=\"xajax_Listado_TerceroCliente(xajax.getFormValues('Buscador_'));\"></td>\n";
    $html .= "	</tr>";
    $html .= "</table>";
    $html .= "</form>";
    $html .= "<div id=\"listado_terceros\"></div>";
    $objResponse->assign("Contenido", "innerHTML", $objResponse->setTildes($html));
    $objResponse->call("MostrarSpan");
    return $objResponse;
}

function Listado_TerceroCliente($Formulario, $offset) {

    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ContratacionProductosClienteSQL", "", "app", "ContratacionProductosCliente");

    $Terceros = $sql->Terceros_Clientes($Formulario, $Offset);

    $pghtml = AutoCarga::factory('ClaseHTML');
    $action['paginador'] = "Paginador(xajax.getFormValues('Buscador')";
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo, $sql->pagina, $action['paginador']);
    $html .= "  <table width=\"100%\" class=\"modulo_table_list_title\" border=\"0\"  align=\"center\">";
    $html .= "		<tr  align=\" class=\"modulo_table_list_title\" >\n";
    $html .= "      	<td >IDENTIFICACION</td>\n";
    $html .= "      	<td >NOMBRE</td>\n";
    $html .= "      	<td >DIRECCION</td>\n";
    $html .= "      	<td >TELEFONO</td>\n";
    $html .= "      	<td >OP</td>\n";
    $html .= "  	</tr>\n";
    foreach ($Terceros as $k => $valor) {
        $html .= "		<tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
        $html .= "			<td>" . $valor['tipo_id_tercero'] . "-" . $valor['tercero_id'] . "</td>";
        $html .= "			<td>" . $valor['nombre_tercero'] . "</b></td>";
        $html .= "			<td>" . $valor['direccion'] . "</td>";
        $html .= "			<td>" . $valor['telefono'] . "</td>";
        if ($valor['contrato_cliente_id'] == "0")
            $html .= "			<td><a onclick=\"Seleccionar_Tercero('" . $valor['tipo_id_tercero'] . "','" . $valor['tercero_id'] . "','" . $valor['nombre_tercero'] . "','" . $valor['direccion'] . "','" . $valor['telefono'] . "','" . $valor['email'] . "');\"><img src=\"" . GetThemePath() . "/images/checkN.gif\" border='0'></a></td>";
        else
            $html .= "			<td><a title=\"EL USUARIO YA TIENE UN CONTRATO ACTIVO\"><img src=\"" . GetThemePath() . "/images/infor.png\" border='0'></a></td>";
        $html .= "  	</tr>\n";
    }
    $html .= "	</table>";


    $objResponse->assign("listado_terceros", "innerHTML", $objResponse->setTildes($html));
    return $objResponse;
}

function Buscar_ProductosInventario($empresa_id, $pedido_cliente_id_tmp, $contrato_cliente_id) {
    $objResponse = new xajaxResponse();

    $html .="<div align=\"center\" class=\"label_error\" id='error'></div> ";
    $html .= "	<form id=\"Buscador\" name=\"Buscador\" action=\"\" method=\"POST\">";
    $html .= "  <table class=\"modulo_table_list\" align=\"center\" width=\"100%\">\n";
    $html .= "    <tr class=\"modulo_table_list_title\">\n";
    $html .= "      <td class=\"formulacion_table_list\" width=\"15%\" align=\"center\">CODIGO PRODUCTO\n";
    $html .= "      </td>\n";
    $html .= "      <td ><input type=\"text\" class=\"input-text\" id=\"codigo_producto\" name=\"codigo_producto\" style=\"width:100%\">\n";
    $html .= "      </td>\n";
    $html .= "      <td class=\"formulacion_table_list\" width=\"5%\" align=\"center\"> NOMBRE \n";
    $html .= "      </td>\n";
    $html .= "      <td ><input type=\"text\" class=\"input-text\" id=\"descripcion\"  name=\"descripcion\" style=\"width:100%\">\n";
    $html .= "      </td>\n";
    $html .= "      <td class=\"formulacion_table_list\" width=\"5%\" align=\"center\"> CONCENTRACION \n";
    $html .= "      </td>\n";
    $html .= "      <td ><input type=\"text\" class=\"input-text\" id=\"concentracion\"  name=\"concentracion\" style=\"width:100%\">\n";
    $html .= "      </td>\n";
    $html .= "    </tr>\n";
    $html .= "	<tr class=\"modulo_table_list_title\">";
    $html .= "		<td>";
    $html .= "		LABORATORIO";
    $html .= "		</td>";
    $html .= "		<td>";
    $html .= "		<input class=\"input-text\" type=\"text\" name=\"laboratorio\" id=\"laboratorio\" style=\"width:100%\">";
    $html .= "		</td>";
    $html .= "		<td>";
    $html .= "		MOLECULA";
    $html .= "		</td>";
    $html .= "		<td>";
    $html .= "		<input type=\"hidden\" name=\"pedido_cliente_id_tmp\" id=\"pedido_cliente_id_tmp\" value=\"" . $pedido_cliente_id_tmp . "\">";
    $html .= "		<input type=\"hidden\" name=\"contrato_cliente_id\" id=\"contrato_cliente_id\" value=\"" . $contrato_cliente_id . "\">";
    $html .= "		<input type=\"hidden\" name=\"empresa_id\" id=\"empresa_id\" value=\"" . $empresa_id . "\">";
    $html .= "		<input class=\"input-text\" type=\"text\" name=\"molecula\" id=\"molecula\" style=\"width:100%\">";
    $html .= "		</td>";
    $html .= "      <td align=\"center\" colspan=\"2\"><input style=\"width:100%\" class=\"input-submit\" type=\"button\" value=\"BUSCAR PRODUCTO\" onclick=\"xajax_Buscar_ProductosInventario_d(xajax.getFormValues('Buscador'),'1');\"> \n";
    $html .= "      </td>\n";
    $html .= "	</tr>";
    $html .= "  </table>\n";
    $html .= "</form>";

    $html .= "  <br>\n";
    $html .= "		<div id=\"Productos\"></div>";
    $objResponse->assign("Contenido", "innerHTML", $objResponse->setTildes($html));
    $objResponse->call("MostrarSpan");
    //$objResponse->script("xajax_Buscar_ProductosInventario_d(xajax.getFormValues('Buscador'),'1');");
    return $objResponse;
}

function Buscar_ProductosInventario_d($Formulario, $Offset) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ContratacionProductosClienteSQL", "", "app", "ContratacionProductosCliente");
    $Productos = $sql->BuscarProductos_InventariosExistencias($Formulario, $Offset);

    $pghtml = AutoCarga::factory('ClaseHTML');
    $action['paginador'] = "Paginador(xajax.getFormValues('Buscador')";
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo, $sql->pagina, $action['paginador']);

    $html .= "<div id=\"error\" class=\"label_error\"></div> ";
    $html .= "<div id=\"error_\" class=\"label_error\"></div> ";

    $html .= "	      <center><strong>*PRODUCTOS EN ROJO SON REGULADOS</strong>";
    $html .= "	      </center>    ";

    $html .= "<form name=\"GrupoProductos\" id=\"GrupoProductos\" action=\"\" method=\"POST\">";
    $html .= "  <table width=\"100%\" class=\"modulo_table_list_title\" border=\"0\"  align=\"center\">";

    $html .= "	  <tr  align=\"\" class=\"modulo_table_list_title\" >\n";
    $html .= "      <td >CODIGO PRODUCTO</td>\n";
    $html .= "      <td >DESCRIPCION</td>\n";
    $html .= "      <td >CUM</td>\n";
    $html .= "      <td >COD.INVIMA/F.VENC.</td>\n";
    $html .= "      <td >IVA</td>\n";
    $html .= "      <td >PRECIO REGULADO</td>\n";
    $html .= "      <td >PRECIO VENTA</td>\n";
    $html .= "      <td >EXIST</td>\n";
    $html .= "      <td >C.DISP.</td>\n";
    $html .= "      <td >CANT.</td>\n";
    $html .= "      <td >SEL</td>\n";
    $html .= "  </tr>\n";
    $i = 0;
    foreach ($Productos as $k => $valor) {

        $Prod = $sql->Get_dataProd($valor['codigo_producto']);
        $precioR = $sql->Get_PrecioRegulado(($valor['codigo_producto']));

        $html .= "	<tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";

        $html .= "      <td  align=\"center\"><a title=\"Precio Reg.:" . $precioR['precio'] . "\"> " . $valor['codigo_producto'] . " </a></td>\n";

        $precio_regulado = 0;

        if ($Prod['sw_regulado'] == '1') {
            $html .= "      <td align=\"left\" style=\"color:red;\">" . $valor['descripcion'] . "</td>\n";
            $precio_regulado = $valor["precio_regulado"];
        } else {
            $html .= "      <td align=\"left\">" . $valor['descripcion'] . "</td>\n";
            $precio_regulado = "";
        }


        $precio = explode("@", $valor['precio']);
        $background = "";
        $valor_compra = $valor['costo_ultima_compra'];


        if ($precio[1] != "") {
            $background = "background-color:#2EFE2E;";
            $valor_compra = 0;
        }


        $html .= "      <td  align=\"center\">" . $Prod['codigo_cum'] . " </td>\n";
        $html .= "      <td align=\"left\">" . $Prod['codigo_invima'] . "/" . $Prod['vencimiento_codigo_invima'] . "</td>\n";
        $html .= "      <td align=\"center\">" . FormatoValor($Prod['porc_iva'], 1) . "%</td>\n";

        $html .= "<td style='color:red;'>{$precio_regulado}</td>";
        $html .= "      <td  align=\"center\"><input rel={$precio_regulado}_{$Prod["sw_regulado"]} onkeypress=\"return acceptNum(event)\" " . $precio[1] . " type=\"text\" name=\"valor_unitario" . $i . "\" id=\"valor_unitario" . $i . "\" class=\"input-text\" style=\"width:100%;" . $background . "\" value=\"" . $precio[0] . "\" onkeyup=\"ValidarCantidad('valor_unitario" . $i . "',this.value,'" . $precio[0] . "','$i');\"></td>\n";
        $html .= "      <td  align=\"center\">" . FormatoValor($valor['existencia'], 0) . "</td>\n";
        $html .= "      <td  align=\"center\">" . FormatoValor($valor['disponible'], 0) . "</td>\n";


        if ($valor["estado"] == "1") {

            $html .= "      <td  align=\"center\"><input onkeypress=\"return acceptNum(event)\" type=\"text\" name=\"cantidad" . $i . "\" id=\"cantidad" . $i . "\" class=\"input-text\" style=\"width:60%\" value=\"\" onkeyup=\"ValidarCantidad('cantidad" . $i . "',this.value,'0','$i');\"></td>\n";
            $html .= "      <td align=\"center\" id=\"celda" . $i . "\">\n";
            $html .= "			<input type=\"hidden\" name=\"porc_iva" . $i . "\" id=\"porc_iva" . $i . "\" value=\"" . $valor['porc_iva'] . "\"> ";
            $html .= "			<input type=\"hidden\" name=\"costo_ultima_compra" . $i . "\" id=\"costo_ultima_compra" . $i . "\" value=\"" . $valor_compra . "\"> ";
            $html .= "          <input type=\"hidden\" name=\"tipo_producto" . $i . "\"  id=\"tipo_producto" . $i . "\" value=\"" . $Prod['tipo_producto_id'] . "\"> ";

            $funcion_NoPactado = "";
            #$funcion_NoPactado = "ValidarValores('valor_unitario" . $i . "','" . $valor['costo_ultima_compra'] . "','$i');";
            if ($precio[1] != "readonly")
                $funcion_NoPactado = "ValidarValores('valor_unitario" . $i . "','" . $precio[0] . "','$i');";

            $html .= "			<input disabled type=\"checkbox\" name=\"" . $i . "\" id=\"" . $i . "\" value=\"" . $valor['codigo_producto'] . "\" class=\"input-checkbox\" onclick=\"ValidarCantidad('valor_unitario" . $i . "',document.getElementById('valor_unitario" . $i . "').value,'0','$i');" . $funcion_NoPactado . "\"> ";
            if ($valor['sw_requiereautorizacion_despachospedidos'] == '1')
                $html .= " <img title=\"EL PRODUCTO REQUIERE AUTORIZACION PARA SER DESPACHADO\" src=\"" . GetThemePath() . "/images/alarma.gif\" border='0' >	";
            $html .= "		";
            $html .= "      </td>\n";
        }

        $html .= "  </tr>\n";
        $i++;
    }
    $html .= "	  <tr  align=\" class=\"modulo_table_list_title\">\n";
    $html .= "      <td  colspan=\"6\">";
    $html .= "			<input type=\"hidden\" value=\"" . $Formulario['empresa_id'] . "\" name=\"empresa_id\" id=\"empresa_id\">";
    $html .= "			<input type=\"hidden\" value=\"" . $Formulario['pedido_cliente_id_tmp'] . "\" name=\"pedido_cliente_id_tmp\" id=\"pedido_cliente_id_tmp\">";
    $html .= "			<input type=\"hidden\" value=\"" . $i . "\" name=\"cantidad_registros\" id=\"cantidad_registros\">";
    $html .= "			<input type=\"button\" id=\"btn_registar_datos\" value=\"REGISTRAR DATOS\" class=\"input-submit\" onclick=\"validarFormulario();\">";
    $html .= "		</td>\n";
    $html .= "	 </tr>";
    $html .= "	</table>\n";
    $html .= "</form>";

    $objResponse->assign("Productos", "innerHTML", $objResponse->setTildes($html));
    return $objResponse;
}

function Guardar_ProductoCotizacion($Formulario) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ContratacionProductosClienteSQL", "classes", "app", "ContratacionProductosCliente");
    $band = '0';

    //  echo print_r($Formulario);
    for ($i = 0; $i < $Formulario['cantidad_registros']; $i++) {

        if ($Formulario[$i] != "") {

            if ($Formulario['tipo_producto' . $i] == '5' || $Formulario['tipo_producto' . $i] == '3') {
                $objResponse->alert("El producto es controlado o de Nevera.");
            }

            if ($Formulario['valor_unitario' . $i] > $Formulario['costo_ultima_compra' . $i])
                $query .=$sql->Sql_IngresarProductoCotizacion($Formulario['pedido_cliente_id_tmp'], $Formulario[$i], $Formulario['porc_iva' . $i], $Formulario['cantidad' . $i], $Formulario['valor_unitario' . $i]);
            else {
                $band = '1';
                $objResponse->assign("error_", "innerHTML", "EL PRECIO NO DEBE SER MENOR AL DE COMPRA: REVISAR PRECIO VENTA");
                $objResponse->script("document.getElementById('celda" . $i . "').style.backgroundColor='Red';");
            }
        }
    }


    if ($band == '0' && $query != "") {
        $token = $sql->IngresarPedidoDetalleTemporal($query);
        if ($token) {
            $Cotizacion = $sql->Consulta_PedidoTemporal($Formulario['pedido_cliente_id_tmp']);
            $url = ModuloGetURL("app", "ContratacionProductosCliente", "controller", "Modificar_Cotizacion", array("pedido_cliente_id_tmp" => $Formulario['pedido_cliente_id_tmp'], "tipo_id_tercero" => $Cotizacion['tipo_id_tercero'], "tercero_id" => $Cotizacion['tercero_id']));
            $script .= "window.location=\"" . $url . "\";";
            $objResponse->script($script);
        }
        else
            $objResponse->assign("error", "innerHTML", "ERROR EN LA CONSULTA");
    }
    /* print_r($query); */
    return $objResponse;
}

function guardar_observacion_tmp_cotizacion($cotizacion_id, $observaciones) {

    $objResponse = new xajaxResponse();

    $sql = AutoCarga::factory("ContratacionProductosClienteSQL", "classes", "app", "ContratacionProductosCliente");

    if (!$sql->actualizar_observaciones_cotizacion($cotizacion_id, $observaciones))
        $objResponse->script("alert('SE HA GENERADO UN ERROR ACTUALIZANDO LA OBSERVACION');");

    return $objResponse;
}

function AdicionarDetalleContrato($ContratoId, $CodigoProducto, $PrecioUltimaCompra, $PrecioCliente) {
    $objResponse = new xajaxResponse();

    $sql = AutoCarga::factory("ContratacionProductosClienteSQL", "", "app", "ContratacionProductosCliente");
    $token = $sql->IngresarProductoAlContrato($ContratoId, $CodigoProducto, $PrecioUltimaCompra, $PrecioCliente, $_REQUEST['datos']['empresa']);
    if (!$token)
        $objResponse->assign("error", "innerHTML", $sql->error);
    else {
        $objResponse->assign("error", "innerHTML", "Ingreso Exitoso");
        $objResponse->script("QuitarDatos();");
        $objResponse->script("xajax_BuscarDetalleContrato('','','','" . $ContratoId . "');");
    }

    return $objResponse;
}

function AnularPedido($pedido_cliente_id, $estado, $accion) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ContratacionProductosClienteSQL", "", "app", "ContratacionProductosCliente");
    $Productos = $sql->Consulta_Pedido_d($pedido_cliente_id);
    $html .= "<form id=\"FormaJustificacion\" name=\"FormaJustificacion\" action=\"\" action=\"post\">";
    $html .= "<table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
    $html .= "  <tr align=\"center\" >\n";
    $html .= "      <td  class=\"formulacion_table_list\"># PEDIDO: " . $pedido_cliente_id . "</td>\n";
    $html .= "	</tr>";
    $html .= "  <tr align=\"center\" >\n";
    $html .= "      <td  class=\"modulo_table_list_title\">JUSTIFICACION</td>\n";
    $html .= "	</tr>";
    $html .= "  <tr align=\"center\" >\n";
    $html .= "      <td  class=\"modulo_table_list_title\"><TEXTAREA class=\"textarea\" style=\"width:100%\" name=\"observacion_anulacion\" id=\"observacion_anulacion\"></TEXTAREA></td>\n";
    $html .= "	</tr>";
    $html .= "  <tr align=\"center\" >\n";
    $html .= "      <td  class=\"modulo_table_list_title\">";
    $html .= "		<input type=\"hidden\" name=\"estado\" id=\"estado\" value=\"" . $estado . "\">";
    $html .= "		<input type=\"hidden\" name=\"pedido_cliente_id\" id=\"pedido_cliente_id\" value=\"" . $pedido_cliente_id . "\">";
    $html .= "		<input type=\"button\" class=\"input-submit\" value=\"" . $accion . " EL PEDIDO\" onclick=\"xajax_Guardar_AnularPedido(xajax.getFormValues('FormaJustificacion'));\"";
    $html .= "		</td>\n";
    $html .= "	</tr>";
    $html .= "</table>";
    $html .= "</form>";

    $html .= "  <table width=\"100%\" class=\"modulo_table_list_title\" border=\"0\"  align=\"center\">";
    $html .= "	  <tr  align=\" class=\"modulo_table_list_title\" >\n";
    $html .= "      <td >CODIGO PRODUCTO</td>\n";
    $html .= "      <td >DESCRIPCION</td>\n";
    $html .= "      <td >V/U</td>\n";
    $html .= "      <td >CANTIDAD</td>\n";
    $html .= "  </tr>\n";
    $i = 0;
    foreach ($Productos as $k => $valor) {
        $html .= "	<tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
        $html .= "      <td  align=\"center\">" . $valor['codigo_producto'] . " </td>\n";
        $html .= "      <td align=\"left\">" . $valor['descripcion'] . "</td>\n";
        $html .= "      <td  align=\"center\">$" . FormatoValor($valor['valor_unitario'], 2) . "</td>\n";
        $html .= "      <td  align=\"center\">" . FormatoValor($valor['numero_unidades'], 0) . "</td>\n";
        $html .= "  </tr>\n";
    }
    $html .= "</table>";

    $objResponse->assign("Contenido", "innerHTML", $objResponse->setTildes($html));
    $objResponse->call("MostrarSpan");
    return $objResponse;
}

function Guardar_AnularPedido($Formulario) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ContratacionProductosClienteSQL", "", "app", "ContratacionProductosCliente");
    $datos = SessionGetVar("Datos");
    $Pedidos = $sql->Listado_Pedidos($datos['empresa'], $Formulario, $request['offset']);

    if (trim($Formulario['observacion_anulacion']) == "")
        $objResponse->alert("ES OBLIGATORIO, DILIGENCIAR LA JUSTIFICACION DE LA ACCION!!");
    else {
        if ($Pedidos[0]['temporal'] != "")
            $objResponse->alert("EL PEDIDO " . $Formulario['pedido_cliente_id'] . " DEBE CERRARSE EN BODEGA PARA PODER CAMBIAR SU ESTADO");
        else {
            $token = $sql->CambiarEstado_Pedido($Formulario['pedido_cliente_id'], $Formulario['observacion_anulacion'], $Formulario['estado']);
            if ($token) {
                $arreglo['buscador']['pedido_cliente_id'] = $Formulario['pedido_cliente_id'];
                $action = ModuloGetURL("app", "ContratacionProductosCliente", "controller", "Pedidos", array("buscador" => $arreglo['buscador']));
                $script = "window.location=\"" . $action . "\";";
                $objResponse->script($script);
            } else {
                $objResponse->alert("ERROR AL CAMBIAR EL ESTADO DEL PEDIDO!!!");
            }
        }
    }
    return $objResponse;
}

function Buscar_Productos($empresa_id, $pedido_cliente_id, $contrato_cliente_id) {
    $objResponse = new xajaxResponse();

    $html .="<div align=\"center\" class=\"label_error\" id='error'></div> ";
    $html .= "	<form id=\"Buscador\" name=\"Buscador\" action=\"\" method=\"POST\">";
    $html .= "  <table class=\"modulo_table_list\" align=\"center\" width=\"100%\">\n";
    $html .= "    <tr class=\"modulo_table_list_title\">\n";
    $html .= "      <td class=\"formulacion_table_list\" width=\"15%\" align=\"center\">CODIGO PRODUCTO\n";
    $html .= "      </td>\n";
    $html .= "      <td ><input type=\"text\" class=\"input-text\" id=\"codigo_producto\" name=\"codigo_producto\" style=\"width:100%\">\n";
    $html .= "      </td>\n";
    $html .= "      <td class=\"formulacion_table_list\" width=\"5%\" align=\"center\"> NOMBRE \n";
    $html .= "      </td>\n";
    $html .= "      <td ><input type=\"text\" class=\"input-text\" id=\"descripcion\"  name=\"descripcion\" style=\"width:100%\">\n";
    $html .= "      </td>\n";
    $html .= "      <td class=\"formulacion_table_list\" width=\"5%\" align=\"center\"> CONCENTRACION \n";
    $html .= "      </td>\n";
    $html .= "      <td ><input type=\"text\" class=\"input-text\" id=\"concentracion\"  name=\"concentracion\" style=\"width:100%\">\n";
    $html .= "      </td>\n";
    $html .= "    </tr>\n";
    $html .= "	<tr class=\"modulo_table_list_title\">";
    $html .= "		<td>";
    $html .= "		LABORATORIO";
    $html .= "		</td>";
    $html .= "		<td>";
    $html .= "		<input class=\"input-text\" type=\"text\" name=\"laboratorio\" id=\"laboratorio\" style=\"width:100%\">";
    $html .= "		</td>";
    $html .= "		<td>";
    $html .= "		MOLECULA";
    $html .= "		</td>";
    $html .= "		<td>";
    $html .= "		<input type=\"hidden\" name=\"pedido_cliente_id\" id=\"pedido_cliente_id\" value=\"" . $pedido_cliente_id . "\">";
    $html .= "		<input type=\"hidden\" name=\"contrato_cliente_id\" id=\"contrato_cliente_id\" value=\"" . $contrato_cliente_id . "\">";
    $html .= "		<input type=\"hidden\" name=\"empresa_id\" id=\"empresa_id\" value=\"" . $empresa_id . "\">";
    $html .= "		<input class=\"input-text\" type=\"text\" name=\"molecula\" id=\"molecula\" style=\"width:100%\">";
    $html .= "		</td>";
    $html .= "      <td align=\"center\" colspan=\"2\"><input style=\"width:100%\" class=\"input-submit\" type=\"button\" value=\"BUSCAR PRODUCTO\" onclick=\"xajax_Buscar_Productos_d(xajax.getFormValues('Buscador'),'1');\"> \n";
    $html .= "      </td>\n";
    $html .= "	</tr>";
    $html .= "  </table>\n";
    $html .= "</form>";

    $html .= "  <br>\n";
    $html .= "		<div id=\"Productos\"></div>";
    $objResponse->assign("Contenido", "innerHTML", $objResponse->setTildes($html));
    $objResponse->call("MostrarSpan");
    $objResponse->script("xajax_Buscar_Productos_d(xajax.getFormValues('Buscador'),'1');");
    return $objResponse;
}

function Buscar_Productos_d($Formulario, $Offset) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ContratacionProductosClienteSQL", "", "app", "ContratacionProductosCliente");
    $Productos = $sql->BuscarProductos_InventariosExistencias_P($Formulario, $Offset);
    /* print_r($Productos); */

    $pghtml = AutoCarga::factory('ClaseHTML');
    $action['paginador'] = "Paginador(xajax.getFormValues('Buscador')";
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo, $sql->pagina, $action['paginador']);
    $html .= "<div id=\"error\" class=\"label_error\"></div> ";
    $html .= "<div id=\"error_\" class=\"label_error\"></div> ";
    $html .= "<form name=\"GrupoProductos\" id=\"GrupoProductos\" action=\"\" method=\"POST\">";
    $html .= "  <table width=\"100%\" class=\"modulo_table_list_title\" border=\"0\"  align=\"center\">";
    $html .= "	  <tr  align=\" class=\"modulo_table_list_title\" >\n";
    $html .= "      <td >CODIGO PRODUCTO</td>\n";
    $html .= "      <td >DESCRIPCION</td>\n";
    $html .= "      <td >PRECIO</td>\n";
    $html .= "      <td >EXIST</td>\n";
    $html .= "      <td >CANT.DISP.</td>\n";
    $html .= "      <td >CANTIDAD</td>\n";
    $html .= "      <td >SEL</td>\n";
    $html .= "  </tr>\n";
    $i = 0;
    foreach ($Productos as $k => $valor) {
        $html .= "	<tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
        $html .= "      <td  align=\"center\">" . $valor['codigo_producto'] . " </td>\n";
        $html .= "      <td align=\"left\">" . $valor['descripcion'] . "</td>\n";
        $precio = explode("@", $valor['precio']);
        $background = "";
        $valor_compra = $valor['costo_ultima_compra'];
        if ($precio[1] != "") {
            $background = "background-color:#2EFE2E;";
            $valor_compra = 0;
        }


        $html .= "      <td  align=\"center\"><input onkeypress=\"return acceptNum(event)\" " . $precio[1] . " type=\"text\" name=\"valor_unitario" . $i . "\" id=\"valor_unitario" . $i . "\" class=\"input-text\" style=\"width:100%;" . $background . "\" value=\"" . $precio[0] . "\" onkeyup=\"ValidarCantidad('valor_unitario" . $i . "',this.value,'" . $precio[0] . "','$i');\"></td>\n";
        $html .= "      <td  align=\"center\">" . FormatoValor($valor['existencia'], 0) . "</td>\n";
        $html .= "      <td  align=\"center\">" . FormatoValor($valor['disponible'], 0) . "</td>\n";
        $html .= "      <td  align=\"center\"><input onkeypress=\"return acceptNum(event)\" type=\"text\" name=\"cantidad" . $i . "\" id=\"cantidad" . $i . "\" class=\"input-text\" style=\"width:100%\" value=\"\" onkeyup=\"ValidarCantidad('cantidad" . $i . "',this.value,'0','$i');\"></td>\n";
        $html .= "      <td align=\"center\" id=\"celda" . $i . "\">\n";
        $html .= "			<input type=\"hidden\" name=\"porc_iva" . $i . "\" id=\"porc_iva" . $i . "\" value=\"" . $valor['porc_iva'] . "\"> ";
        $html .= "			<input type=\"hidden\" name=\"costo_ultima_compra" . $i . "\" id=\"costo_ultima_compra" . $i . "\" value=\"" . $valor_compra . "\"> ";

        $funcion_NoPactado = "";
        if ($precio[1] != "readonly")
            $funcion_NoPactado = "ValidarValores('valor_unitario" . $i . "','" . $valor['costo_ultima_compra'] . "','$i');";

        $html .= "			<input disabled type=\"checkbox\" name=\"" . $i . "\" id=\"" . $i . "\" value=\"" . $valor['codigo_producto'] . "\" class=\"input-checkbox\" onclick=\"ValidarCantidad('valor_unitario" . $i . "',document.getElementById('valor_unitario" . $i . "').value,'0','$i');" . $funcion_NoPactado . "\"> "; /* $html .= "			<input disabled type=\"checkbox\" name=\"".$i."\" id=\"".$i."\" value=\"".$valor['codigo_producto']."\" class=\"input-checkbox\" onclick=\"ValidarCantidad('cantidad".$i."',document.getElementById('cantidad".$i."').value,'0','$i');ValidarCantidad('valor_unitario".$i."',document.getElementById('valor_unitario".$i."').value,'0','$i');ValidarValores('valor_unitario".$i."','".$valor['costo_ultima_compra']."','$i');\"> "; */
        if ($valor['sw_requiereautorizacion_despachospedidos'] == '1')
            $html .= " <img title=\"EL PRODUCTO REQUIERE AUTORIZACION PARA SER DESPACHADO\" src=\"" . GetThemePath() . "/images/alarma.gif\" border='0' >	";
        $html .= "		";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $i++;
    }
    $html .= "	  <tr  align=\" class=\"modulo_table_list_title\">\n";
    $html .= "      <td  colspan=\"6\">";
    $html .= "			<input type=\"hidden\" value=\"" . $Formulario['empresa_id'] . "\" name=\"empresa_id\" id=\"empresa_id\">";
    $html .= "			<input type=\"hidden\" value=\"" . $Formulario['pedido_cliente_id'] . "\" name=\"pedido_cliente_id\" id=\"pedido_cliente_id\">";
    $html .= "			<input type=\"hidden\" value=\"" . $i . "\" name=\"cantidad_registros\" id=\"cantidad_registros\">";
    $html .= "			<input type=\"button\" value=\"REGISTRAR DATOS\" class=\"input-submit\" onclick=\"xajax_Guardar_ProductoPedido(xajax.getFormValues('GrupoProductos'));\">";
    $html .= "		</td>\n";
    $html .= "	 </tr>";
    $html .= "	</table>\n";
    $html .= "</form>";

    $objResponse->assign("Productos", "innerHTML", $objResponse->setTildes($html));
    return $objResponse;
}

function Guardar_ProductoPedido($Formulario) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ContratacionProductosClienteSQL", "classes", "app", "ContratacionProductosCliente");

    $band = '0';
    for ($i = 0; $i < $Formulario['cantidad_registros']; $i++) {
        if ($Formulario[$i] != "") {
            if ($Formulario['valor_unitario' . $i] > $Formulario['costo_ultima_compra' . $i])
                $query .=$sql->Sql_IngresarProductoPedido($Formulario['pedido_cliente_id'], $Formulario[$i], $Formulario['porc_iva' . $i], $Formulario['cantidad' . $i], $Formulario['valor_unitario' . $i]);
            else {
                $band = '1';
                $objResponse->assign("error_", "innerHTML", "EL PRECIO NO DEBE SER MENOR AL DE COMPRA: REVISAR PRECIO VENTA");
                $objResponse->script("document.getElementById('celda" . $i . "').style.backgroundColor='Red';");
            }
        }
    }

    if ($band == '0' && $query != "") {
        $token = $sql->IngresarPedidoDetalleTemporal($query);
        if ($token) {
            $Cotizacion = $sql->Consulta_PedidoTemporal($Formulario['pedido_cliente_id']);
            $url = ModuloGetURL("app", "ContratacionProductosCliente", "controller", "Modificar_Pedido", array("pedido_cliente_id" => $Formulario['pedido_cliente_id']));
            $script .= "window.location=\"" . $url . "\";";
            $objResponse->script($script);
        }
        else
            $objResponse->assign("error", "innerHTML", "ERROR EN LA CONSULTA");
    }
    /* print_r($query); */
    return $objResponse;
}

?>