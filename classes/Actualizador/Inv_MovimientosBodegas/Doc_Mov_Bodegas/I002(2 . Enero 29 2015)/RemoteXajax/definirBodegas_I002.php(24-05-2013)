<?php

function OrdenesCompra($proveedor) {
    $objResponse = new xajaxResponse();

    $objClass = new doc_bodegas_I002;

    $datos = $objClass->GetOrdenesCompra($proveedor, SessionGetVar('Empresa_id'));

    $salida = OrdenesCompra_HTML($datos);

    $objResponse->assign("orden", "innerHTML", $salida);

    return $objResponse;
}

function OrdenesCompra_HTML($datos) {
    if ($datos) {
        $salida = "";
        foreach ($datos as $key => $valor) {
            $salida.= "<option value=\"" . $valor['orden_pedido_id'] . "\">ORDEN COMPRA N. " . $valor['orden_pedido_id'] . "</option>";
        }
    } else {
        $salida.= "<option value=\"\">NO EXISTEN ORDENES DE COMPRA</option>";
    }

    return $salida;
}

function NewDocumentoTmp($observacion, $orden, $bodegas_doc_id, $tipo_doc_bodega_id) {
    $objResponse = new xajaxResponse();

    if ($datosdocTmp = doc_bodegas_I002::CrearDocTmp($observacion, $orden, $bodegas_doc_id)) {
        $objResponse->assign("bodegas_doc_id", "value", $bodegas_doc_id);
        $objResponse->assign("tipo_doc_bodega_id", "value", $tipo_doc_bodega_id);
        $objResponse->assign("doc_tmp_id", "value", $datosdocTmp['doc_tmp_id']);
        $objResponse->call("cargar");
    }

    return $objResponse;
}

function ListadoProductos($pagina, $orden, $tipo_param, $param, $doc_tmp_id, $bodegas_doc_id) {
    $objResponse = new xajaxResponse();
    $objClass = new doc_bodegas_I002;
    $sql = AutoCarga::factory("MovDocI002", "classes", "app", "Inv_MovimientosBodegas");

    $ProductosFOC = $sql->ListadoProductos_FOC(SessionGetVar('Empresa_id'), SessionGetVar('centro_utilidad'), SessionGetVar('bodega'), $orden, $doc_tmp_id);
    //print_r($ProductosFOC);
    //$datos=$objClass->GetProductos($pagina,$orden,$tipo_param,$param);


    $datos = $sql->ProductoOrdenCompra($orden, $CodigoProducto, $tipo_param, $param);
    $datosItem = $objClass->ConsultarItems($doc_tmp_id, $bodegas_doc_id);

    $salida = ListadoProductos_HTML($datos, $pagina, $orden, $tipo_param, $param, $conteo, $datosItem, $doc_tmp_id, $bodegas_doc_id, $ProductosFOC);
    $objResponse->assign("productos_ordenCompra", "innerHTML", $objResponse->setTildes($salida));
    //Para habilitar/Deshabilitar aquellos que est�n en el documento Temporal
    foreach ($datosItem as $key => $valor) {
        $script .= "for (i=0;i<document.forma_checks.elements.length;i++)";
        $script .= " {";
        $script .= "  if(document.forma_checks.elements[i].type == \"checkbox\" && document.forma_checks.elements[i].value==\"" . $valor['item_id_compras'] . "\")";
        $script .= "  {";
        $script .= "    document.forma_checks.elements[i].disabled=1;";
        $script .= "    document.forma_checks.elements[i].checked=1;";
        $script .= "    document.getElementById('capa_" . $valor['item_id_compras'] . "').style.display=\"block\";";
        $script .= "  }";
        $script .= " }";
        $objResponse->script("         var link=document.getElementById('link" . $valor['item_id_compras'] . "');
                                            link.style.display = 'none';");
    }
    $objResponse->script($script);

    if (count($ProductosFOC) > 0) { //Para habilitar/Deshabilitar aquellos que est�n por Autorizar
        foreach ($ProductosFOC as $key => $foc) {
            $script_ .= "for (i=0;i<document.forma_checks.elements.length;i++)";
            $script_ .= " {";
            $script_ .= "  if(document.forma_checks.elements[i].type == \"checkbox\" && document.forma_checks.elements[i].value==\"" . $foc['item_id'] . "\")";
            $script_ .= "  {";
            $script_ .= "    document.forma_checks.elements[i].disabled=1;";
            $script_ .= "    document.forma_checks.elements[i].checked=1;";
            $script_ .= "  }";
            $script_ .= " }";
            $objResponse->script("         var link=document.getElementById('link" . $foc['item_id'] . "');
                                            link.style.display = 'none';");
            $mensaje = "Item Por Autorizar!!";
            $objResponse->assign("Autorizar" . $foc['item_id'], "innerHTML", $objResponse->setTildes($mensaje));
            $objResponse->assign("mensaje" . $foc['item_id'], "innerHTML", "");
        }
        $objResponse->script($script_);
    }

    return $objResponse;
}

function ListadoProductos_HTML($datos, $pagina, $orden, $tipo_param, $param, $conteo, $datosItem, $doc_tmp_id, $bodegas_doc_id, $ProductosFOC) {
    $objClass = new doc_bodegas_I002;

    $salida .= "<center><label class=\"label_error\"><a title=\"OPCION QUE PERMITE RECARGAR EL DOCUMENTO TEMPORAL\" onclick=\"xajax_GetItems('" . $doc_tmp_id . "','" . $bodegas_doc_id . "');\">[[:RECARGAR DOCUMENTO:]]</a></label></center>";
    $salida .= "<a name=\"here\"></a>";
    $salida .= "<div id=\"mensajes\" class=\"label_error\"></div>";
    $salida .= "   <form name=\"forma_checks\" id=\"forma_checks\">";
    $salida .= "    <input type=\"hidden\" name=\"doc_tmp_id\" id=\"doc_tmp_id\" value=\"" . $doc_tmp_id . "\">";
    $salida .= "    <input type=\"hidden\" name=\"bodegas_doc_id\" id=\"bodegas_doc_id\" value=\"" . $bodegas_doc_id . "\">";
    $salida .= "    <input type=\"hidden\" name=\"cantidad\" id=\"cantidad\" value=\"" . count($datos) . "\">";
    $salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
    $salida .= "  <tr class=\"modulo_table_list_title\">";
    $salida .= "  <td>DESCRIPCION</td>";
    $salida .= "  <td><input class=\"input-text\" type=\"text\" name=\"descripcion\" id=\"descripcion\" style=\"width:100%;height:100%\" onkeydown=\"recogerTeclaBus(event)\"></td>";
    $salida .= "  <td colspan=\"2\">CODIGO DE BARRAS</td>";
    $salida .= "  <td colspan=\"3\"><input class=\"input-text\" type=\"text\" name=\"codigo_barras\" id=\"codigo_barras\" style=\"width:100%;height:100%\" onkeydown=\"recogerTeclaBus(event)\"></td>";
    $salida .= "  <td colspan=\"2\"></td>";
    $salida .= "  </tr>";
    $salida .= "  <tr class=\"modulo_table_list_title\">";
    $salida .= "  <td colspan=\"11\" align=\"center\">PRODUCTOS EN LA ORDEN DE COMPRA</td>";
    $salida .= "  </tr>";
    $salida .= "	<tr class=\"modulo_table_list_title\">";
    $salida .= "		<td width=\"7%\">C�DIGO</td>";
    $salida .= "		<td width=\"15%\">DESCRIPCI�N</td>";
    $salida .= "		<td width=\"3%\">CANTIDAD</td>";
    $salida .= "		<td width=\"4%\">CANTIDAD RECIBIDA</td>";
    $salida .= "		<td width=\"4%\">V. UNITARIO</td>";
    $salida .= "		<td width=\"4%\">LOTE</td>";
    $salida .= "		<td width=\"4%\">LOCALIZACION</td>";
    $salida .= "		<td width=\"8%\">FECHA VENCIMIENTO</td>";
    $checkbox = "    <input type=\"checkbox\" onclick=\"activar_todos()\" name=\"activar\" id=\"activar\">  ";
    $salida .= "		<td width=\"3%\">" . $checkbox . "|ACCION</td>";
    $salida .= "	</tr>";
    $salida .= "  <input type=\"hidden\" id=\"codigo_proveedor_id\" value='" . $datos[0]['codigo_proveedor_id'] . "'>";

    //print_r($ProductosFOC);
    if ($datos) {
        $i = 0;
        $c = 0;
        foreach ($datos as $key => $valor) {

            if ($i % 2 == 0)
                $estilo = "modulo_list_claro";
            else
                $estilo = "modulo_list_oscuro";


            $salida .= "<tr class=\"$estilo\" id=\"capa1$i\">";

            $salida .= "	<td>" . $valor['codigo_producto'] . "<input id=\"codigoproducto" . $i . "\" name=\"codigoproducto" . $i . "\" type=\"hidden\" value=\"" . $valor['codigo_producto'] . "\"></td>";
            $salida .= "	<td>" . $valor['descripcion'] . "</td>";
            $salida .= "	<td>" . $valor['cantidad'] . "</td>";
            $salida .= "	<td>\n";
            $salida .= "    <input type=\"text\" class=\"input-text\" name=\"ucantidad$i\" id=\"ucantidad$i\" value=\"" . $valor['cantidad'] . "\" style=\"width:100%\" onkeypress=\"return acceptNum(event);\" onkeyup=\"ValidarCantidad('ucantidad$i',xGetElementById('ucantidad$i').value,'" . $valor['cantidad'] . "','hell$i');\">\n";
            $salida .= "  </td>";
            $salida .= "	<td>\n";
            $salida .= "    <input class=\"input-text\" type=\"text\" name=\"valor_unitario_factura$i\" id=\"valor_unitario_factura$i\" style=\"width:100%\" onkeypress=\"return acceptNum(event);\" value=\"" . trim($valor['valor']) . "\">\n";
            $salida .= "  </td>";
            $salida .= "	<td>\n";
            $salida .= "    <input type=\"text\" class=\"input-text\" name=\"lote$i\" id=\"lote$i\" style=\"width:100%\" >\n";
            $salida .= "  </td>";
            $salida .= "	<td>\n";
            $salida .= "    <input type=\"text\" class=\"input-text\" name=\"localizacion$i\" id=\"localizacion$i\" value=\"\" style=\"width:100%\" >\n";
            $salida .= "  </td>";
            $salida .= "	<td width=\"150\" height=\"50\">\n";
            $salida .= "	  <div id=\"capa_" . $valor['item_id'] . "\" style=\"display:none;width:150;height:40;position:absolute\"></div>\n";
            $salida .= "      <input type=\"text\" readonly=\"true\" class=\"input-text\" name=\"fecha_vencimiento$i\"id=\"fecha_vencimiento$i\" style=\"width:50%\" >";
            $salida .= "      <input type=\"hidden\" id=\"valor$i\" name=\"valor$i\" value='" . $valor['valor'] . "'>";
            $salida .= "      <input type=\"hidden\" id=\"porc_iva$i\" name=\"porc_iva$i\" value='" . $valor['porc_iva'] . "'>";
            $salida .= ReturnOpenCalendarioHTML("forma_checks", "fecha_vencimiento$i", '-') . "\n";
            $salida .= "		</td>\n";

            $bandera = 0;
            $msj = "";

            $datos_variables = "AgregarItem('$doc_tmp_id','" . $valor['codigo_producto'] . "',xGetElementById('ucantidad$i').value,'" . $valor['valor'] . "','" . $valor['porc_iva'] . "','$bodegas_doc_id','checkear$i',xGetElementById('lote$i').value,xGetElementById('fecha_vencimiento$i').value,document.getElementById('localizacion$i').value,'" . $valor['item_id'] . "',document.getElementById('valor_unitario_factura$i').value);";
            $salida .= "	<td align=\"center\" >\n";
            $salida .= "    <input type=\"checkbox\" id=\"" . $i . "\" name=\"" . $i . "\" value=\"" . $valor['item_id'] . "\" class=\"input-checkbox\">
        <a id=\"link" . $valor['item_id'] . "\" href=\"#producto_adicional" . $valor['item_id'] . "\" title=\"Adicionar Nuevo Lote\" onclick=\"xajax_AdicionarNuevoLote('" . $valor['codigo_producto'] . "','" . $valor['descripcion'] . "','" . $valor['cantidad'] . "','" . $valor['orden_pedido_id'] . "','" . $valor['porc_iva'] . "','" . $valor['valor'] . "','" . $valor['item_id'] . "');\"><b>(+)</b></a><div class=\"label_error\" id=\"Autorizar" . $valor['item_id'] . "\"></div></td>";
            $salida .= "</tr>";
            $i++;
            //se desplegar� Formulario para un producto adicional
            $salida .= " <tr><td colspan=\"10\"><a name=\"#producto_adicional" . $valor['item_id'] . "\"></a><div class=\"label_error\"  id=\"mensaje" . $valor['item_id'] . "\"></div><div id=\"producto_adicional" . $valor['item_id'] . "\"></div></td></tr>";
        }

        $salida .= "<tr>";
        $salida .= "<td colspan=\"7\"></td>";
        $salida .= "<td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"button\" value=\"AGREGAR ITEMS AL DOCUMENTO\" onclick=\"xajax_AgregarItem(xajax.getFormValues(forma_checks));\"></td>";
        $salida .= "</tr>";
    }
    else {
        $salida .= "	<tr class=\"label_error\">";
        $salida .= "		<td width=\"100%\" colspan=\"7\">NO HAY PRODUCTOS SELECCIONADOS</td>";
        $salida .= "	</tr>";
    }
    $salida .= "</form>";
    $salida .= "</table>";

    $limite = 10;
    $salida.= "" . ObtenerPaginado($pagina, GetThemePath(), $conteo, 1, $orden, $tipo_param, $param, $doc_tmp_id, $bodegas_doc_id, $limite);
    $salida .= "";

    return $salida;
}

function AdicionarNuevoLote($CodigoProducto, $Descripcion, $Cantidad, $OrdenPedido, $PorcIva, $Valor, $Item_Id) {
    $objResponse = new xajaxResponse();

    $html .= "<table class=\"modulo_table_list\" width=\"100%\">";

    $html .= "<td>";
    $html .= "<div id=\"mensaje" . $CodigoProducto . "" . $Cantidad . "\"></div>";
    $html .= "</td>";

    $html .= "<td>";
    $html .= "<b>Codigo Producto :</b>" . $CodigoProducto;
    $html .= "</td>";

    $html .= "<td>";
    $html .= "<b>Nombre Producto :</b> " . $Descripcion;
    $html .= "</td>";

    $html .= "<td>";
    $html .= "<b>Cantidad(Digitar < $Cantidad):</b> <input type=\"text\" class=\"input-text\" name=\"cantidadnuevolote\" id=\"cantidadnuevolote" . $Item_Id . "\" value=\"" . ($Cantidad - 1) . "\" onkeypress=\"return acceptNum(event);\" onkeyup=\"ValidarCantidad('cantidadnuevolote" . $Item_Id . "',document.getElementById('cantidadnuevolote" . $Item_Id . "').value,'" . ($Cantidad - 1) . "','mensaje" . $Item_Id . "');\">";
    $html .= "</td>";

    $html .= "<td>";
    $html .= "<input class=\"modulo_table_list\" type=\"button\" value=\"Guardar\" onclick=\"xajax_InsertarProductoOrdenCompra('" . $CodigoProducto . "',document.getElementById('cantidadnuevolote" . $Item_Id . "').value,'" . $OrdenPedido . "','" . $PorcIva . "','" . $Valor . "','" . $Item_Id . "');\">";
    $html .= "</td>";
    $html .= "</table>";

    $objResponse->assign("producto_adicional" . $Item_Id, "innerHTML", $objResponse->setTildes($html));
    //BuscarProductos('1',xGetElementById('orden').value,'0','0',xGetElementById('doc_tmp_id').value,xGetElementById('bodegas_doc_id').value);
    return $objResponse;
}

function InsertarProductoOrdenCompra($CodigoProducto, $Cantidades, $OrdenPedido, $PorcIva, $Valor, $Item_Id) {
    $objResponse = new xajaxResponse();
    $objClass = new doc_bodegas_I002;
    /*
     * Insertar un producto en la orden de compra que simule la entrada de un mismo producto pero con diferente lote.
     *
     */
    $producto = $objClass->BuscarProductoOC($CodigoProducto, $Item_Id);
    if ($producto[0]['numero_unidades'] > $Cantidades)
        $sql = $objClass->IngresarProductoOrdenCompra($CodigoProducto, $Cantidades, $OrdenPedido, $PorcIva, $Valor);
    if ($sql)
        $sql2 = $objClass->ModificarProductoOrdenCompra($CodigoProducto, $Cantidades, $OrdenPedido, $PorcIva, $Valor, $Item_Id);
    else
        $objResponse->alert('ERROR EN EL INGRESO!!!');

    $objResponse->script("BuscarProductos('1',xGetElementById('orden').value,'0','0',xGetElementById('doc_tmp_id').value,xGetElementById('bodegas_doc_id').value);");
    //BuscarProductos('1',xGetElementById('orden').value,'0','0',xGetElementById('doc_tmp_id').value,xGetElementById('bodegas_doc_id').value);
    return $objResponse;
}

function IngresarProductosFueraOrdenCompra($accion, $doc_tmp_id, $bodegas_doc_id, $Proveedor, $OrdenCompra) {
    $objResponse = new xajaxResponse();
    $objClass = new doc_bodegas_I002;

    //$objResponse->alert($doc_tmp_id);
    if ($accion == "1") {
        $html .= "<table width=\"90%\" align=\"center\">";
        //$html .= "	";
        $html .= "	<tr class=\"modulo_table_list_title\">";
        $html .= "<td align=\"center\" width=\"95%\">";
        $html .= "<b>INGRESAR PRODUCTOS NO PRESENTES EN LA ORDEN DE COMPRA </b>";
        $html .= "</td>";
        $html .= "			<td width=\"5%\" align=\"center\" class=\"normal_10AN\" >\n";
        $html .="<a href=\"#ProductosFueraOrdenCompra\" title=\"CERRAR\" onclick=\"xajax_IngresarProductosFueraOrdenCompra('0');\"><img src=\"" . GetThemePath() . "/images/no.png\" border=\"0\" width=\"15\" height=\"15\"></a>";
        $html .= "			</td>\n";
        $html .= "	</tr>";
        $html .= "	<tr class=\"modulo_table_list_title\">";
        $html .= "<td align=\"center\" width=\"100%\" colspan=\"2\">";
        //subtabla
        $html .="<table width=\"100%\">";
        $html .="<tr class=\"modulo_list_claro\" align=\"center\">";
        $html .="<td >";
        $html .="<b>CODIGO PRODUCTO</b>";
        $html .="</td>";
        $html .="<td>";
        $html .="<input type=\"text\" class=\"input-text\" name=\"codigoproducto\" id=\"codigoproducto\"> ";
        $html .="</td>";

        $html .="<td>";
        $html .="<b>DESCRIPCION</b>";
        $html .="</td>";
        $html .="<td>";
        $html .="<input type=\"text\" class=\"input-text\" name=\"nombreproducto\" id=\"nombreproducto\"> ";
        $html .="</td>";

        $html .="<td>";
        $html .="<input type=\"button\" class=\"modulo_table_list\" value=\"buscar\" onclick=\"xajax_BuscarProductos(document.getElementById('codigoproducto').value,document.getElementById('nombreproducto').value,'" . $doc_tmp_id . "','" . $bodegas_doc_id . "','" . $Proveedor . "','" . $OrdenCompra . "');\"> ";
        $html .="</td>";
        $html .="</tr>";
        $html .="</table>";

        $html .= "</td>";
        $html .= "</table>";

        $html.= "<div id=\"listadoproductosbuscados\"></div>";
    }
    else
        $html .="";
    //$objResponse->alert($Proveedor." => ".$OrdenCompra);
    $objResponse->assign("ProductosFueraOrdenCompra", "innerHTML", $objResponse->setTildes($html));
    return $objResponse;
}

function BuscarProductos($CodigoProducto, $NombreProducto, $doc_tmp_id, $bodegas_doc_id, $Proveedor, $OrdenCompra, $offset) {
    //print_r(SessionGetVar("accion"));
    $objResponse = new xajaxResponse();
    $objClass = new doc_bodegas_I002;
    //$objResponse->alert($doc_tmp_id);

    $sql = AutoCarga::factory("MovDocI002", "classes", "app", "Inv_MovimientosBodegas");

    $Productos = $sql->ListadoProductosBuscados($CodigoProducto, $NombreProducto, $offset);
    $ProductosOC = $sql->ListadoProductosOCompra($OrdenCompra);
    $ProductosFOC = $sql->ListadoProductos_FOC(SessionGetVar('Empresa_id'), SessionGetVar('centro_utilidad'), SessionGetVar('bodega'), $OrdenCompra, $doc_tmp_id);
    $action['paginador'] = "Paginador('" . $CodigoProducto . "','" . $NombreProducto . "','" . $doc_tmp_id . "','" . $bodegas_doc_id . "','" . $Proveedor . "','" . $OrdenCompra . "'";
    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo, $sql->pagina, $action['paginador']);

    $k = 1001;
    $html .= "<table width=\"90%\" align=\"center\">";
    //$html .= "	";

    $html .= "	<tr class=\"modulo_table_list_title\">";
    $html .= "<td align=\"center\" width=\"100%\">";
    $html .= "<b>LISTADO DE PRODUCTOS</b>";
    $html .= " <div id='error4'></div>";
    $html .= "</td>";
    $html .= "	</tr>";
    $html .= "	<tr class=\"modulo_table_list_title\">";
    $html .= "<td align=\"center\" width=\"100%\" colspan=\"2\">";
    //subtabla
    $html .="<table width=\"100%\">";
    $html .="<tr class=\"modulo_list_oscuro\" align=\"center\">";

    $html .="<td >";
    $html .="<b>CODIGO PRODUCTO</b>";
    $html .="</td>";

    $html .="<td>";
    $html .="<b>DESCRIPCION</b>";
    $html .="</td>";


    $html .="<td>";
    $html .="<b>CANTIDAD</b>";
    $html .="</td>";

    $html .="<td>";
    $html .="<b>V/UNITARIO</b>";
    $html .="</td>";

    $html .="<td>";
    $html .="<b>%IVA</b>";
    $html .="</td>";


    $html .="<td>";
    $html .="<b>LOTE</b>";
    $html .="</td>";


    $html .="<td>";
    $html .="<b>LOCALIZACION</b>";
    $html .="</td>";

    $html .="<td>";
    $html .="<b>FECHA VENCIMIENTO</b>";
    $html .="</td>";

    $html .="<td>";
    $html .="<b>JUSTIFICACION</b>";
    $html .="</td>";

    $html .="<td>";
    $html .="<b>ADICIONAR</b>";
    $html .="</td>";

    $html .="</tr>";
    $ProductosEnTemporal = $objClass->ConsultarItems($doc_tmp_id, $bodegas_doc_id);

    // print_r($ProductosEnTemporal);          
    foreach ($Productos as $key => $p) {
        $disabled = "";
        $value = "Adicionar";


        $html.= "<tr class=\"modulo_list_claro\">";

        $html .="<td>";
        $html .=$p['codigo_producto'];
        $html .="</td>";

        $html .="<td>";
        $html .=$p['descripcion'];
        $html .="</td>";


        $html .="<td align=\"center\">";
        $html .="<input type=\"text\" class=\"input-text\" id=\"cantidad" . $p['codigo_producto'] . "\" onkeypress=\"return acceptNum(event);\" size=\"4\" >";
        $html .="</td>";

        $html .="<td align=\"center\">";
        $html .="<input type=\"text\" class=\"input-text\" id=\"valor" . $p['codigo_producto'] . "\" onkeypress=\"return acceptNum(event);\" size=\"6\" >";
        $html .="</td>";

        $html .="<td align=\"center\">";
        $html .="<input type=\"text\" class=\"input-text\" id=\"iva" . $p['codigo_producto'] . "\" onkeypress=\"return acceptNum(event);\" size=\"2\" value=\"" . $p['porc_iva'] . "\">";
        $html .="</td>";


        $html .="<td align=\"center\">";
        $html .="<input type=\"text\" class=\"input-text\" id=\"lote" . $p['codigo_producto'] . "\" size=\"6\" >";
        $html .="</td>";

        $html .="<td align=\"center\">";
        $html .="<input type=\"text\" class=\"input-text\" id=\"localizacion" . $p['codigo_producto'] . "\" size=\"6\" >";
        $html .="</td>";

        $html .="<td align=\"center\">";
        $html .="<div id=\"fecha_Vencimiento" . $p['codigo_producto'] . "\"></div>";
        $html .="</td>";



        foreach ($ProductosFOC as $key1 => $pfoc) {
            if ($pfoc['codigo_producto'] == $p['codigo_producto']) {
                $disabled = " disabled=\"true\" ";
                $value = "Por Autorizar!!!";
            }
            //print_r($ProductosEnTemporal);
        }


        foreach ($ProductosOC as $key1 => $poc) {
            if ($poc['codigo_producto'] == $p['codigo_producto']) {
                $disabled = " disabled=\"true\" ";
                $value = "Ya en Orden de Compra!!!";
            }
            //print_r($ProductosEnTemporal);
        }

        //print_r($ProductosEnTemporal);

        foreach ($ProductosEnTemporal as $key1 => $pet) {
            if ($pet['codigo_producto'] == $p['codigo_producto']) {
                $disabled = " disabled=\"true\" ";
                $value = "Ya Ingresado!!!";
            }
            //print_r($ProductosEnTemporal);
        }

        $html .="<td>";
        $html .="<div id=\"campo_justificacion" . $p['codigo_producto'] . "\">";
        $html .="</div>";
        $html .="</td>";


        $html .="<td align=\"center\">";
        $html .="<div id=\"boton" . $p['codigo_producto'] . "\">";
        $html .="<input type=\"button\" class=\"modulo_table_list\" value=\"" . $value . "\" " . $disabled . " onclick=\"xajax_FormaAdicionarProducto('" . $p['codigo_producto'] . "','" . $doc_tmp_id . "','" . $bodegas_doc_id . "','" . $k . "','" . $CodigoProducto . "','" . $NombreProducto . "','" . $Proveedor . "','" . $OrdenCompra . "','" . $offset . "');\"> ";
        $html .="</div>";
        $html .="</td>";


        $k++;
    }


    $html .="</table>";

    $html .= "</td>";
    $html .= "</table>";

    $objResponse->assign("listadoproductosbuscados", "innerHTML", $objResponse->setTildes($html));
    return $objResponse;
}

function FormaAdicionarProducto($CodigoProducto, $doc_tmp_id, $bodegas_doc_id, $k, $Codigo, $NombreProducto, $Proveedor, $OrdenCompra, $offset) {
    $objResponse = new xajaxResponse();
    //$objResponse->alert($doc_tmp_id);
//    $input1 .="";
//    $objResponse->alert($CodigoProducto);		                                                                          $doc_tmp_id,$codigo_producto,                               $cantidad,                                 $total_costo,                                    $porc_iva,              $bodegas_doc_id,                                  $lote,                                                          $fecha_vencimiento,                                                   $localizacion,           $item_id_compras,                 $justificacion,                           $OrdenCompra,                    $ValorUnitarioFactura,                             $ValorUnitarioCompra,                             $offset																																																																																																																																																															//Ac�, este ser� Vac�o Siempre, no tomar en cuenta este parametro
    $input4 .="<input type=\"button\" class=\"modulo_table_list\" value=\"Guardar\" onclick=\"xajax_AgregarItemFOC('" . $doc_tmp_id . "','" . $CodigoProducto . "',document.getElementById('cantidad" . $CodigoProducto . "').value,'" . $total_costo . "',document.getElementById('iva" . $CodigoProducto . "').value,'" . $bodegas_doc_id . "',document.getElementById('lote" . $CodigoProducto . "').value,document.getElementById('fecha_vencimiento" . $CodigoProducto . "').value,document.getElementById('localizacion" . $CodigoProducto . "').value,'',document.getElementById('justificacion" . $CodigoProducto . "').value,'" . $OrdenCompra . "',document.getElementById('valor" . $CodigoProducto . "').value,document.getElementById('valor" . $CodigoProducto . "').value,'$offset');\"> ";

    $input7 = "<form name=\"Formafecha_vencimiento" . $k . "\">";
    $input7 .="<input type=\"text\" readonly=\"true\" class=\"input-text\" name=\"fecha_vencimiento" . $k . "\" id=\"fecha_vencimiento" . $CodigoProducto . "\" size=\"10\" >";
    $input7 .= ReturnOpenCalendarioHTML("Formafecha_vencimiento" . $k, "fecha_vencimiento$k", '-') . "\n";
    $input7 .="</form>";

    $input8 = "		<textarea id=\"justificacion" . $CodigoProducto . "\" class=\"input-text\" style=\"width:100%;height:100%\"></textarea></td>";

    $objResponse->assign("titulo", "innerHTML", $titulo);
    $objResponse->assign("fecha_Vencimiento" . $CodigoProducto, "innerHTML", $input7);
    $objResponse->assign("campo_justificacion" . $CodigoProducto, "innerHTML", $input8);
    $objResponse->assign("boton" . $CodigoProducto, "innerHTML", $input4);

    // $objResponse->script($script);
    return $objResponse;
}

function Actualizartmp($bodega_doc_id, $tmp, $estado, $tipo_documento) {
    $consulta = new MovBodegasSQL();
    $objResponse = new xajaxResponse();
    $buscar = $consulta->ActuEstado($estado, UserGetUID(), $tmp, $tipo_documento);
    return $objResponse;
}

/*
 * Funcion para Agregar un producto que est� fuera de la Orden de Compra
 *
 */

//function AgregarItemFOC($doc_tmp_id,$codigo,$can,$valor,$iva,$bodegas_doc_id,$lote,$fecha_vencimiento,$localizacion,$CodigoProducto,$NombreProducto,$Proveedor,$justificacion,$OrdenCompra,$ItemId,$ValorUnitarioFactura,$offset)
function AgregarItemFOC($doc_tmp_id, $codigo_producto, $cantidad, $total_costo, $porc_iva, $bodegas_doc_id, $lote, $fecha_vencimiento, $localizacion, $item_id_compras, $justificacion, $OrdenCompra, $ValorUnitarioFactura, $ValorUnitarioCompra, $offset) {

    $objResponse = new xajaxResponse();
    $dias_vencimiento = ModuloGetVar('app', 'AdminFarmacia', 'dias_vencimiento_product_bodega_farmacia_' . SessionGetVar('Empresa_id'));
    $fecha_actual = date("m/d/Y");
    $fecha = $fecha_vencimiento;  //esta es la que viene de la DB
    list( $dia, $mes, $ano ) = split('[/.-]', $fecha);
    $fecha = $ano . "-" . $mes . "-" . $dia;

    $fecha_uno_act = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
    $fecha_dos = mktime(0, 0, 0, $mes, $dia, $ano);
    $int_nodias = floor(abs(strtotime($fecha) - strtotime($fecha_actual)) / 86400);

    $objClass = new doc_bodegas_I002;
    $sql = AutoCarga::factory("MovDocI002", "classes", "app", "Inv_MovimientosBodegas");
    $valida = $objClass->ConsultarItemsExistencias($codigo_producto, SessionGetVar('Empresa_id'), SessionGetVar('centro_utilidad'), SessionGetVar('bodega'));
    //Valido que el Producto se encuentre en la Existencia de la empresa
    if (count($valida) == 0) {
        $objResponse->assign("error4", "innerHTML", "<center><label class=\"label_error\">El producto [ " . $codigo_producto . " ] no se encuentra en bodegas_existencias</label>");
    } else {
        //En caso de que la autorizacion que se solicita, viene de fuera de la orden de compra
        if ($item_id_compras == "") {
            $total_costo = $cantidad * ($ValorUnitarioFactura + ($ValorUnitarioFactura * $porc_iva) / 100);





            if ($int_nodias < $dias_vencimiento)
                $justificacion .= "Producto Fuera de la Orden de Compra y Adem�s: proximo a vencer!!";
        }

        if ($fecha_dos <= $fecha_uno_act)
            $objResponse->alert("PRODUCTO VENCIDO: NO SE PUEDE HACER LA SOLICITUD DE AUTORIZACION");
        else {
            if ($cantidad == "" || $cantidad <= 0 || $ValorUnitarioFactura == "" || $ValorUnitarioFactura <= 0 || $lote == "" || $localizacion == "" || $fecha_vencimiento == "" || $justificacion == "") {
                //$objResponse->alert($justificacion);
                $objResponse->assign("error4", "innerHTML", "<center><label class=\"label_error\">Por Favor Diligenciar Todos los Campos</label>");
            } else {
                if ($item_id = $sql->AgregarItemFOC(SessionGetVar('Empresa_id'), SessionGetVar('centro_utilidad'), SessionGetVar('bodega'), UserGetUID(), $doc_tmp_id, $codigo_producto, $cantidad, $total_costo, $porc_iva, $bodegas_doc_id, $lote, $fecha, $localizacion, $item_id_compras, $justificacion, $OrdenCompra, $ValorUnitarioCompra, $ValorUnitarioFactura)) {
                    if ($item_id_compras != "") {
                        $script = "for (i=0;i<document.forma_checks.elements.length;i++)";
                        $script .= " {";
                        $script .= "  if(document.forma_checks.elements[i].type == \"checkbox\" && document.forma_checks.elements[i].value==\"" . $item_id_compras . "\")";
                        $script .= "  {";
                        $script .= "    document.forma_checks.elements[i].disabled=1;";
                        $script .= "    document.forma_checks.elements[i].checked=1;";
                        $script .= "  }";
                        $script .= " }";
                        $objResponse->script($script);
                        $objResponse->script("var link=document.getElementById('link" . $item_id_compras . "');
                                                                link.style.display = 'none';");
                        $salida = "Item Por Autorizar!!";
                        $objResponse->assign("Autorizar" . $item_id_compras, "innerHTML", $objResponse->setTildes($salida));
                        $objResponse->assign("mensaje" . $item_id_compras, "innerHTML", "");
                    } else {
                        $salida = "<center><label class=\"label_error\">Solicitud Exitosa!</label></center>";
                        $objResponse->assign("error4", "innerHTML", $objResponse->setTildes($salida));
                        $objResponse->script("xajax_BuscarProductos('$codigo_producto','','$doc_tmp_id','$bodegas_doc_id','$Proveedor','" . $OrdenCompra . "','$offset');");
                    }
                } else {
                    if ($item_id_compras != "") {
                        $salida = "<center>ERROR: El Producto Debe Estar Pendiente por Autorizar!</center>";
                        $objResponse->assign("mensaje" . $item_id_compras, "innerHTML", $objResponse->setTildes($salida));
                    } else {
                        $salida = "<center><label class=\"label_error\">ERROR: El Producto Debe Estar Pendiente por Autorizar!</label></center>";
                        $objResponse->assign("error4", "innerHTML", $objResponse->setTildes($salida));
                    }
                }
            }
        }
    }
    return $objResponse;
}

function AgregarItem($Formulario) {
    $objResponse = new xajaxResponse();
    $objClass = new doc_bodegas_I002;

    $fecha_actual = date("m/d/Y");
    $dias_vencimiento = ModuloGetVar('app', 'AdminFarmacia', 'dias_vencimiento_product_bodega_farmacia_' . SessionGetVar('Empresa_id'));

    for ($i = 0; $i < $Formulario['cantidad']; $i++) {

        if ($Formulario[$i] != "") {
            $fecha = $Formulario['fecha_vencimiento' . $i];

            list( $dia, $mes, $ano ) = split('[/.-]', $fecha);
            $fecha = $mes . "/" . $dia . "/" . $ano;
            $fecha_ = $ano . "/" . $mes . "/" . $dia;

            //Para despues verificar si un producto est� vencido o n�
            $fecha_uno_act = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $fecha_dos = mktime(0, 0, 0, $mes, $dia, $ano);

            $int_nodias = floor(abs(strtotime($fecha) - strtotime($fecha_actual)) / 86400);

            $datosItem = $objClass->ConsultarItemsExistencias($Formulario['codigoproducto' . $i], SessionGetVar('Empresa_id'), SessionGetVar('centro_utilidad'), SessionGetVar('bodega'));
            //Valido que el Producto se encuentre en la Existencia de la empresa
            if (count($datosItem) == 0) {
                $objResponse->assign("mensaje" . $Formulario[$i] . "", "innerHTML", "<center><label class=\"label_error\">El producto [ " . $Formulario['codigoproducto' . $i] . " ] no se encuentra en bodegas_existencias</label>");
            } else { //Valido que los Campos no est�n vac�os
                if ($Formulario['ucantidad' . $i] == "" || $Formulario['valor_unitario_factura' . $i] == "" || $Formulario['lote' . $i] == "" || $Formulario['localizacion' . $i] == "" || $Formulario['fecha_vencimiento' . $i] == "")
                    $objResponse->assign("mensaje" . $Formulario[$i] . "", "innerHTML", "<center><label class=\"label_error\">Por Favor Diligenciar Todos los Campos</label>");
                else {
                    //Valido que el campo de valor, no est�n Negativos
                    if ($Formulario['valor_unitario_factura' . $i] < 0 || !is_numeric($Formulario['valor_unitario_factura' . $i]))
                        $objResponse->assign("mensaje" . $Formulario[$i] . "", "innerHTML", "<center><label class=\"label_error\">" . $Formulario['valor_unitario_factura' . $i] . "Por Favor, Ingresar Valores V�lidos</label>");
                    else {//Valida que el producto no est� vencido
                        if ($fecha_dos <= $fecha_uno_act) {
                            $objResponse->assign("mensaje" . $Formulario[$i] . "", "innerHTML", "<center><label class=\"label_error\">�PRODUCTO VENCIDO!</label>");
                            $objResponse->alert("EL PRODUCTO QUE INTENTA INGRESAR EST� VENCIDO");
                        } else {
                            $mensaje = "";
                            $bandera = "0";
                            if ($int_nodias < $dias_vencimiento) {
                                $mensaje .= "El Producto Est� proximo a Vencer ";
                                $bandera = "1";
                                $objResponse->alert("EL PRODUCTO QUE INTENTA INGRESAR EST� PROXIMO A VENCER");
                            }

                            if ($Formulario['valor_unitario_factura' . $i] < $Formulario['valor' . $i]) {
                                $mensaje .= "- El Precio Unitario es Menor al de la Orden de Compra ";
                                $bandera = "1";
                            }

                            if ($Formulario['valor_unitario_factura' . $i] > $Formulario['valor' . $i]) {
                                $mensaje .= "- El Precio Unitario es Mayor al de la Orden de Compra ";
                                $bandera = "1";
                            }
                            //$objResponse->alert($Formulario['valor_unitario_factura'.$i]);
                            //$objResponse->alert($Formulario['valor'.$i]);
                            if ($bandera == "1") {
                                $total_costo = $Formulario['ucantidad' . $i] * ($Formulario['valor_unitario_factura' . $i] + ($Formulario['valor_unitario_factura' . $i] * $Formulario['porc_iva' . $i]) / 100);
                                $parametros = "'" . $Formulario['doc_tmp_id'] . "','" . $Formulario['codigoproducto' . $i] . "','" . $Formulario['ucantidad' . $i] . "','" . $total_costo . "','" . $Formulario['porc_iva' . $i] . "','" . $Formulario['bodegas_doc_id'] . "','" . $Formulario['lote' . $i] . "','" . $Formulario['fecha_vencimiento' . $i] . "','" . $Formulario['localizacion' . $i] . "','" . $Formulario[$i] . "'";
                                $objResponse->assign("mensaje" . $Formulario[$i] . "", "innerHTML", "<center><label class=\"label_error\">ATENCION: <a title=\"Solicitar Autorizacion Para este Producto\" onclick=\"xajax_AgregarItemFOC(" . $parametros . ",'" . $mensaje . "',xGetElementById('orden').value,'" . $Formulario['valor_unitario_factura' . $i] . "','" . $Formulario['valor' . $i] . "','');\">�" . $mensaje . "! -Solicitar Autorizacion?</a></label>");
                                $objResponse->assign("producto_adicional" . $Formulario['codigoproducto' . $i] . "" . $Formulario['ucantidad' . $i], "innerHTML", "");
                            } else {//Ya ha pasado por todas las validaciones, solo queda hacer el Ingreso del Producto al Temporal
                                $total_costo = $Formulario['ucantidad' . $i] * ($Formulario['valor_unitario_factura' . $i] + ($Formulario['valor_unitario_factura' . $i] * $Formulario['porc_iva' . $i]) / 100);

                                //Para No Ingrersar mas de un producto en el temporal

                                $producto = $objClass->BuscarProductoLoteTemporal($Formulario['doc_tmp_id'], UserGetUID(), $Formulario['codigoproducto' . $i], $Formulario[$i]);

                                if (empty($producto))
                                    $ItemId = $objClass->AgregarItem($Formulario['doc_tmp_id'], $Formulario['codigoproducto' . $i], $Formulario['ucantidad' . $i], $total_costo, $Formulario['porc_iva' . $i], $Formulario['bodegas_doc_id'], $Formulario['lote' . $i], $Formulario['fecha_vencimiento' . $i], $Formulario['localizacion' . $i]);


                                if ($ItemId == "") {
                                    $objResponse->assign("mensaje" . $Formulario[$i] . "", "innerHTML", "<center><label class=\"label_error\">ATENCION: �ERROR AL INGRESAR EL ITEM O ES POSIBLE QUE YA EXISTA EN EL DOCUMENTO</label>");
                                } else {
                                    $objClass->ItemComprasEnMovimiento($Formulario['doc_tmp_id'], $ItemId, $Formulario[$i]);
                                    //Para Deshabilitar CheckBox
                                    $script = "    document.getElementById('" . $i . "').disabled=true;";
                                    $script .= "    document.getElementById('capa_" . $Formulario[$i] . "').style.display=\"block\";";

                                    $objResponse->script($script);
                                    $objResponse->script("
                      var link=document.getElementById('link" . $Formulario[$i] . "');
                      link.style.display = 'none';
                       ");
                                    $objResponse->assign("producto_adicional" . $Formulario[$i], "innerHTML", "");
                                    $objResponse->assign("mensaje" . $Formulario[$i] . "", "innerHTML", "");
                                    $datosItem = $objClass->ConsultarItems($Formulario['doc_tmp_id'], $Formulario['bodegas_doc_id']);
                                    $objResponse->script("xajax_GetItems('" . $Formulario['doc_tmp_id'] . "','" . $Formulario['bodegas_doc_id'] . "');");
                                    /* $salida = FormaItems_HTML($datosItem,$Formulario['doc_tmp_id'],$Formulario['bodegas_doc_id']); */
                                    $objResponse->assign("crearDoc", "style.display", "block");
                                    $objResponse->assign("listadoP", "innerHTML", $objResponse->setTildes($salida));
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    return $objResponse;
}

function EliminarItem($item_id, $doc_tmp_id, $bodegas_doc_id) {
    $objResponse = new xajaxResponse();

    $objClass = new doc_bodegas_I002;
    $ProductoTemporal = $objClass->ConsultaItemTemporal($item_id);
    //print_r($ProductoTemporal);
    if ($item_id = $objClass->EliminarItem($item_id, $bodegas_doc_id))
        ; {
        $datosItem = $objClass->ConsultarItems($doc_tmp_id, $bodegas_doc_id);

        if (!$datosItem) {
            $objResponse->assign("crearDoc", "style.display", "none");
        }

        $script = "for (i=0;i<document.forma_checks.elements.length;i++)";
        $script .= " {";
        $script .= "  if(document.forma_checks.elements[i].type == \"checkbox\" && document.forma_checks.elements[i].value==\"" . $ProductoTemporal[0]['item_id_compras'] . "\")";
        $script .= "  {";
        $script .= "    var link=document.getElementById('link" . $ProductoTemporal[0]['item_id_compras'] . "');
                                link.style.display = ''; ";
        $script .= "    document.getElementById('capa_" . $ProductoTemporal[0]['item_id_compras'] . "').style.display=\"none\";";
        $script .= "    document.forma_checks.elements[i].disabled=0;";
        $script .= "    document.forma_checks.elements[i].checked=0;";
        $script .= "  }";
        $script .= " }";
        $objResponse->script($script);
        $objResponse->script("xajax_GetItems('" . $doc_tmp_id . "','" . $bodegas_doc_id . "');");
        /* $salida = FormaItems_HTML($datosItem,$doc_tmp_id,$bodegas_doc_id);
          $objResponse->assign("listadoP","innerHTML",$objResponse->setTildes($salida)); */
    }
    return $objResponse;
}

function GetItems($doc_tmp_id, $bodegas_doc_id) {
    $objResponse = new xajaxResponse();

    $objClass = new doc_bodegas_I002;
    $sql = AutoCarga::factory("FacturasDespachoSQL", "classes", "app", "FacturasDespacho");
    $datosCabecera = $objClass->TraerGetDocTemporal($bodegas_doc_id, $doc_tmp_id);
    $datosItem = $objClass->ConsultarItems($doc_tmp_id, $bodegas_doc_id);
    $Parametros_Retencion = $sql->Parametros_Retencion($datosCabecera['empresa_id']);

    if (!empty($datosItem)) {
        $objResponse->assign("crearDoc", "style.display", "block");
    }
    $salida = FormaItems_HTML($datosItem, $doc_tmp_id, $bodegas_doc_id, $datosCabecera, $Parametros_Retencion);
    $objResponse->assign("listadoP", "innerHTML", $objResponse->setTildes($salida));

    return $objResponse;
}

function FormaItems_HTML($datosItem, $doc_tmp_id, $bodegas_doc_id, $datosCabecera, $Parametros_Retencion) {

    $objClass = new doc_bodegas_I002;
    if ($datosItem) {
        //Convenciones
        $colores['PV'] = ModuloGetVar('app', 'ReportesInventariosGral', 'color_proximo_vencer');
        $colores['VN'] = ModuloGetVar('app', 'ReportesInventariosGral', 'color_vencido');

        $salida .= "                <table class=\"modulo_table_list\" width=\"35%\" align=\"center\">";
        $salida .= "					<tr  class=\"modulo_table_list_title\">\n";
        $salida .= "                 		<td style=\"background:" . $colores['PV'] . "\" width=\"50%\" align=\"center\">";
        $salida .= "                  		PROD. PROXIMO A VENCER";
        $salida .= "                  		</td>";
        $salida .= "                 		<td style=\"background:" . $colores['VN'] . "\" width=\"50%\" align=\"center\">";
        $salida .= "                  		PROD. VENCIDO";
        $salida .= "                  		</td>";
        $salida .= "					</tr>\n";
        $salida .= "				</table>";
        $salida .= "               <br>";

        $salida .= "	<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "		<tr  class=\"modulo_table_list_title\">\n";
        $salida .= "			<td >\n";
        $salida .= "				CODIGO";
        $salida .= "			</td>\n";
        $salida .= "			<td >\n";
        $salida .= "				DESCRIPCION";
        $salida .= "			</td>\n";
        $salida .= "			<td >\n";
        $salida .= "				CANT";
        $salida .= "			</td>\n";
        $salida .= "			<td >\n";
        $salida .= "				VLR.UNIT";
        $salida .= "			</td>\n";
        $salida .= "			<td >\n";
        $salida .= "				LOTE";
        $salida .= "			</td>\n";
        $salida .= "			<td >\n";
        $salida .= "				FECHA VENC.";
        $salida .= "			</td>\n";
        $salida .= "			<td >\n";
        $salida .= "				GRAVAMENT";
        $salida .= "			</td>\n";
        $salida .= "			<td  >\n";
        $salida .= "				TOTAL";
        $salida .= "      		</td>\n";
        $salida .= "			<td  width=\"2%\">\n";
        $salida .= "				OP";
        $salida .= "      		</td>\n";
        $salida .= "			<td  width=\"2%\">\n";
        $salida .= "				NC";
        $salida .= "      		</td>\n";
        $salida .= "			<td  width=\"2%\">\n";
        $salida .= "				ACTA T.";
        $salida .= "      		</td>\n";
        $salida .= "		</tr>\n";
        $i = 0;
        $cantidad = $gravamen = $total = 0;
        foreach ($datosItem as $key => $valor) {
            if ($i % 2 == 0) {
                $estilo = "modulo_list_claro";
            } else {
                $estilo = "modulo_list_oscuro";
            }

            $salida1 .= "	<tr class=\"$estilo\">\n";
            $salida1.="<td>" . $valor['codigo_producto'] . "</td>";
            $salida1.="<td>" . $valor['descripcion'] . "</td>";
            $salida1.="<td align=\"right\">" . FormatoValor($valor['cantidad']) . "</td>";
            $salida1.="<td align=\"right\">$" . FormatoValor($valor['valor_unit'], 2) . "</td>";
            $salida1.="<td>" . $valor['lote'] . "</td>";


            $fech_vencmodulo = ModuloGetVar('app', 'AdminFarmacia', 'dias_vencimiento_product_bodega_farmacia_' . SessionGetVar("EMPRESA"));
            //$fech_vencmodulo=ModuloGetVar('app','AdministracionFarmacia','dias_vencimiento_product_bodega_farmacia_02');


            /*
             * Para Sacar los numeros de d�as entre fechas
             */
            $fecha = $valor['fecha_vencimiento'];  //esta es la que viene de la DB
            list($ano, $mes, $dia) = split('[/.-]', $fecha);
            $fecha = $mes . "/" . $dia . "/" . $ano;

            $fecha_actual = date("m/d/Y");
            $fecha_compara_actual = date("Y-m-d");
            //Mes/Dia/A�o  "02/02/2010
            $int_nodias = floor(abs(strtotime($fecha) - strtotime($fecha_actual)) / 86400);
            $colores['PV'] = ModuloGetVar('app', 'ReportesInventariosGral', 'color_proximo_vencer');
            $colores['VN'] = ModuloGetVar('app', 'ReportesInventariosGral', 'color_vencido');

            $fecha_uno_act = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $fecha_dos = mktime(0, 0, 0, $mes, $dia, $ano);
            $color = "";
            if ($int_nodias < $fech_vencmodulo) {
                $color = "style=\"background:" . $colores['PV'] . "\"";
            }

            if ($fecha_dos <= $fecha_uno_act) {
                $color = "style=\"background:" . $colores['VN'] . "\"";
            }



            $salida1.="<td " . $color . ">" . $valor['fecha_vencimiento'] . "</td>";
            $salida1.="<td align=\"right\">" . FormatoValor($valor['porcentaje_gravamen']) . " % </td>";
            $salida1.="<td align=\"right\"> $ " . FormatoValor($valor['total_costo'], 2) . "</td>";
            $salida1.="<td align=\"center\"><a href=\"javascript:Iniciar2('CONFIRMACION DE ELIMINAR');MostrarSpan('d2Container2');FormaConfirm('$key','$doc_tmp_id','$bodegas_doc_id');\"><img src=\"" . GetThemePath() . "/images/delete2.gif\" border=\"0\"></a></td>";
            /*
             * Para los Ingresos No Conformes
             */
            //print_r($valor);
            if ($valor['sw_ingresonc'] == 1)
                $salida1.="<td align=\"center\"><a href=\"#aca\" title=\"NO CONFORME\" onclick=\"xajax_MarcarINC('$key','$doc_tmp_id','$bodegas_doc_id','0');\"><img src=\"" . GetThemePath() . "/images/no_autorizado.png\" border=\"0\"></a></td>";
            else
                $salida1.="<td align=\"center\"><a href=\"#aca\" title=\"INGRESO CONFORME\" onclick=\"xajax_MarcarINC('$key','$doc_tmp_id','$bodegas_doc_id','1');\"><img src=\"" . GetThemePath() . "/images/autorizado.png\" border=\"0\"></a></td>";
            $Datos = $objClass->BuscarResgistroActa($doc_tmp_id, UserGetUID(), $key);
            
            if (empty($Datos))
                $imagen = "folder_vacio.png";
            else
                $imagen = "folder_lleno.png";

            $salida1.="<td align=\"center\"><a href=\"#aca\" title=\"ACTA TECNICA DEL PRODUCTO\" onclick=\"xajax_FormaActa('$key','$doc_tmp_id','$bodegas_doc_id',xGetElementById('orden').value);\"><img src=\"" . GetThemePath() . "/images/" . $imagen . "\" border=\"0\"></a>";
            $salida1 .= "<a name=\"#aca\"></a></td>";
            $salida1 .= "</tr>\n";
            $cantidad += $valor['cantidad'];
            $gravamen += $valor['iva_total'];
            $total += $valor['total_costo']; /* Total  Total */
            $i++;
            $subtotal = $subtotal + $valor['valor_total'];
        }
        /* print_r($Parametros_Retencion);
          print_r("<hr>");
          print_r($datosCabecera); */
        /* if(Parametros_Retencion) */
        if ($Parametros_Retencion['sw_rtf'] == '2' || $Parametros_Retencion['sw_rtf'] == '3')
            if ($subtotal >= $Parametros_Retencion['base_rtf'])
                $retencion_fuente = $subtotal * ($datosCabecera['porcentaje_rtf'] / 100);

        if ($Parametros_Retencion['sw_ica'] == '2' || $Parametros_Retencion['sw_ica'] == '3')
            if ($subtotal >= $Parametros_Retencion['base_ica'])
                $retencion_ica = $subtotal * ($datosCabecera['porcentaje_ica'] / 1000);

        if ($Parametros_Retencion['sw_reteiva'] == '2' || $Parametros_Retencion['sw_reteiva'] == '3')
            if ($subtotal >= $Parametros_Retencion['base_reteiva'])
                $retencion_iva = $gravamen * ($datosCabecera['porcentaje_reteiva'] / 100);



        $impuesto_cree = 0;

        if (!is_null($datosCabecera['porcentaje_cree'])) {
            $impuesto_cree = (($datosCabecera['porcentaje_cree'] / 100) * $subtotal);
        }    

        $total = ((((($subtotal + $gravamen) - $retencion_fuente) - $retencion_ica) - $retencion_iva)-$impuesto_cree);

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

        $salida1 .= "					<td>";
        $salida1 .= "						<u>IMPTO CREE</u>";
        $salida1 .= "					</td>";

        $salida1 .= "					<td>";
        $salida1 .= "						<u>VALOR TOTAL</u>";
        $salida1 .= "					</td>";
        $salida1 .= "				</tr>";
        $salida1 .= "				<tr align=\"center\" >";
        $salida1 .= "					<td>";
        $salida1 .= "						" . FormatoValor($cantidad);
        $salida1 .= "					</td>";
        $salida1 .= "					<td>";
        $salida1 .= "						$" . FormatoValor($subtotal, 2);
        $salida1 .= "					</td>";
        $salida1 .= "					<td>";
        $salida1 .= "						$" . FormatoValor($gravamen, 2);
        $salida1 .= "					</td>";
        $salida1 .= "					<td>";
        $salida1 .= "						$" . FormatoValor($retencion_fuente, 2);
        $salida1 .= "					</td>";
        $salida1 .= "					<td>";
        $salida1 .= "						$" . FormatoValor($retencion_ica, 2);
        $salida1 .= "					</td>";
        $salida1 .= "					<td>";
        $salida1 .= "						$" . FormatoValor($retencion_iva, 2);
        $salida1 .= "					</td>";
        $salida1 .= "					<td>";
        $salida1 .= "						$". FormatoValor($impuesto_cree, 2);
        $salida1 .= "					</td>";

        $salida1 .= "					<td>";
        $salida1 .= "						$" . FormatoValor($total, 2);
        $salida1 .= "					</td>";
        $salida1 .= "				</tr>";
        $salida1 .= "			</table>";
        $salida1 .= "		</td>";
        $salida1 .= "	</tr>";
        $salida .= "	$salida1\n";
        $salida .= "</table><br>\n";
    }
    return $salida;
}

function FormaActa($item_id, $doc_tmp_id, $bodegas_doc_id, $orden_pedido_id) {
    $objResponse = new xajaxResponse();
    $sql = new doc_bodegas_I002;
    $producto = $sql->BuscarItem($item_id, $doc_tmp_id);
    $Datos = $sql->BuscarResgistroActa($doc_tmp_id, UserGetUID(), $item_id);

    //print_r($Datos_Visual);
    $html .= "<center>";
    $html .= "	<div id=\"MensajeDeError\" class=\"label_error\"></div>";
    $html .= "</center>";
    $html .= " <form name=\"FormularioActaTecnica\" id=\"FormularioActaTecnica\"> ";
    $html .= "	<table width=\"100%\" align=\"center\" class=\"modulo_table_list\" rules=\"all\">\n";
    $html .= " <tr class=\"modulo_table_list_title\">";
    $html .= "   <td align=\"center\" colspan=\"5\" >";
    $html .= "    REGISTRO DE INSPECCION DE MEDICAMENTOS";
    $html .= "   </td>";
    $html .= " </tr>";

    $html .= " <tr class=\"modulo_table_list_title\">";
    $html .= "   <td align=\"center\" colspan=\"5\">";
    $html .= "  INFORMACION BASICA";
    $html .= "   </td>";
    $html .= " </tr>";
    $html .= " <tr>";
    $html .= " <td class=\"modulo_table_list_title\">";
    $html .= "  NOMBRE COMERCIAL";
    $html .= " </td>";
    $html .= " <td>";
    $html .= "  " . $producto['descripcion_producto'];
    $html .= " </td>";
    $html .= " <td class=\"modulo_table_list_title\" colspan=\"2\" rowspan=\"2\">";
    $html .= "  PRESENTACION";
    $html .= " </td >";
    $html .= " <td rowspan=\"2\">";
    $html .= "  " . $producto['presentacioncomercial_id'] . " X " . $producto['precantidad'];
    $html .= " </td>";
    $html .= " </tr>";
    $html .= " <tr>";
    $html .= " <td class=\"modulo_table_list_title\">";
    $html .= "  NOMBRE GENERICO";
    $html .= " </td>";
    $html .= " <td  colspan=\"3\">";
    $html .= "  " . $producto['descripcion_producto'];
    $html .= " </td>";
    $html .= " </tr>";
    $html .= " </table>";

    $html .= "	<table width=\"100%\" rules=\"all\" align=\"center\" class=\"modulo_table_list\">\n";
    $html .= " <tr>";
    $html .= "     <td >";
    $html .= "       <B>LOTE:</B> <input type=\"text\" class=\"input-text\" readonly=\"true\" name=\"lote\" id=\"lote\" value=\"" . $producto['lote'] . "\" style=\"width:100%\">";
    $html .= "       </td>";

    if ($Datos['c_nc_lote'] == '1')
        $checked_1 = " checked ";
    else
    if ($Datos['c_nc_lote'] == '0')
        $checked_2 = " checked ";

    $html .= "     <td >";
    $html .= "       *<input $checked_1 class=\"input-radio\" type=\"radio\" name=\"c_nc_lote\" id=\"c_nc_lote\" value=\"1\"><B>C</B><input $checked_2 class=\"input-radio\" type=\"radio\" name=\"c_nc_lote\" id=\"c_nc_lote\" value=\"0\"><B>/NC</B>";
    $html .= "     </td>";

    $html .= "     <td >";
    $html .= "       <B>VENCIMIENTO:</B> <input type=\"text\" class=\"input-text\" readonly=\"true\" name=\"fecha_vencimiento\" id=\"fecha_vencimiento\" value=\"" . $producto['fecha_vencimiento'] . "\" style=\"width:100%\">";
    $html .= "      </td>";

    $checked_1 = "";
    $checked_2 = "";
    if ($Datos['c_nc_vencimiento'] == '1')
        $checked_1 = " checked ";
    else
    if ($Datos['c_nc_vencimiento'] == '0')
        $checked_2 = " checked ";



    $html .= "    <td colspan=\"2\">";
    $html .= "       *<input $checked_1 class=\"input-radio\" type=\"radio\" name=\"c_nc_vencimiento\" id=\"c_nc_vencimiento\" value=\"1\"><B>C</B><input $checked_2 class=\"input-radio\" type=\"radio\" name=\"c_nc_vencimiento\" id=\"c_nc_vencimiento\" value=\"0\"><B>/NC</B>";
    $html .= "     </td>";


    $html .= " </tr>";




    if ($Datos['registro_sanitario'] == "") {
        $registro_sanitario = $producto['codigo_invima'];
    } else {
        $registro_sanitario = $Datos['registro_sanitario'];
    }

    $html .= " <tr>";
    $html .= "     <td  class=\"modulo_table_list_title\">";
    $html .= "         *REGISTRO SANITARIO";
    $html .= "     </td>";
    $html .= "     <td colspan=\"2\">";
    $html .= "         <input type=\"text\" class=\"input-text\" name=\"registro_sanitario\" id=\"registro_sanitario\" value=\"" . $registro_sanitario . "\" style=\"width:100%\">";
    $html .= "     </td>";
    $html .= "     <td class=\"modulo_table_list_title\">";
    $html .= "     FABRICANTE:";
    $html .= "     </td>";
    $html .= "     <td colspan=\"2\">";
    $html .= "       " . $producto['fabricante'];
    $html .= "     </td>";
    $html .= " </tr>";
    $html .= " </table>";
    $html .= " <br>";
    $html .= "<center><u><b>INFORMACION SOBRE MUESTREO</b></u></center>";
    $html .= "	<table width=\"100%\" rules=\"all\" align=\"center\" class=\"modulo_table_list\">\n";
    $html .= " <tr>";
    $html .= "     <td class=\"modulo_table_list_title\">";
    $html .= "       NUMERO DE ORDEN DE COMPRA:";
    $html .= "       </td>";
    $html .= "    <td >";
    $html .= "         <input type=\"text\" class=\"input-text\" readonly=\"true\" name=\"orden_pedido_id\" id=\"orden_pedido_id\" value=\"" . $orden_pedido_id . "\" style=\"width:100%\">";
    $html .= "     </td>";

    $html .= "     <td class=\"modulo_table_list_title\">";
    $html .= "       NUMERO DE REMISION:";
    $html .= "       </td>";
    $html .= "    <td >";
    $html .= "         <input type=\"text\" class=\"input-text\"  name=\"numero_remision\" id=\"numero_remision\" value=\"" . $Datos['numero_remision'] . "\" style=\"width:100%\">";
    $html .= "     </td>";

    $html .= "     <td class=\"modulo_table_list_title\">";
    $html .= "       # FACTURA:";
    $html .= "       </td>";
    $html .= "    <td >";
    $html .= "      <input type=\"text\" class=\"input-text\" name=\"numero_factura\" id=\"numero_factura\" value=\"" . $Datos['numero_factura'] . "\" style=\"width:100%\">";
    $html .= "     </td>";
    $html .= " </tr>";

    $html .= " <tr>";
    $html .= "     <td  class=\"modulo_table_list_title\" >";
    $html .= "         CANTIDAD RECIBIDA";
    $html .= "     </td>";
    $html .= "     <td colspan=\"3\">";
    $html .= "         <input type=\"text\" class=\"input-text\" readonly=\"true\" name=\"cantidad\" id=\"cantidad\" value=\"" . $producto['cantidad'] . "\" style=\"width:100%\">";
    $html .= "     </td>";
    $html .= "     <td class=\"modulo_table_list_title\">";
    $html .= "     TOTAL CORRUGADAS:";
    $html .= "     </td>";
    $html .= "     <td colspan=\"2\">";
    $html .= "       <input type=\"text\" value=\"" . $Datos['total_corrugadas'] . "\" class=\"input-text\" name=\"total_corrugadas\" id=\"total_corrugadas\" value=\"\" style=\"width:100%\">";
    $html .= "     </td>";
    $html .= " </tr>";

    $html .= " <tr>";
    $html .= "     <td class=\"modulo_table_list_title\">";
    $html .= "       UN/CORRUGADA:";
    $html .= "       </td>";
    $html .= "    <td >";
    $html .= "       <input type=\"text\" class=\"input-text\" value=\"" . $Datos['unidad_corrugadas'] . "\" name=\"unidad_corrugadas\" id=\"unidad_corrugadas\" style=\"width:100%\">";
    $html .= "     </td>";

    $html .= "     <td class=\"modulo_table_list_title\">";
    $html .= "       CORRUGADAS A MUESTREAR:";
    $html .= "       </td>";
    $html .= "    <td >";
    $html .= "       <input type=\"text\" class=\"input-text\" name=\"corrugadas_a_muestrear\" id=\"corrugadas_a_muestrear\" value=\"" . $Datos['corrugadas_a_muestrear'] . "\" style=\"width:100%\">";
    $html .= "     </td>";

    $html .= "     <td class=\"modulo_table_list_title\">";
    $html .= "      UN/CORRUGADA A MUESTREAR";
    $html .= "       </td>";
    $html .= "    <td >";
    $html .= "      <input type=\"text\" class=\"input-text\" name=\"unidad_corrugadas_a_muestrear\" id=\"unidad_corrugadas_a_muestrear\" value=\"" . $Datos['unidad_corrugadas_a_muestrear'] . "\" style=\"width:100%\">";
    $html .= "     </td>";
    $html .= " </tr>";

    $html .= " <tr>";
    $html .= "    <td colspan=\"6\" align=\"center\" class=\"modulo_table_list_title\">";
    $html .= "     ARGUMENTACION POR DOBLE MUESTREO";
    $html .= "     </td>";
    $html .= " </tr>";
    $html .= " <tr>";
    $html .= "    <td colspan=\"6\" align=\"center\" class=\"modulo_table_list_title\">";
    $html .= "     <textarea name=\"argumentacion_doble_muestreo\" id=\"argumentacion_doble_muestreo\" class=\"textarea\" style=\"width:100%;\">" . $Datos['argumentacion_doble_muestreo'] . "</textarea>";
    $html .= "     </td>";
    $html .= " </tr>";
    $html .= " </table>";

    $Listar_EvaluacionesVisuales = $sql->Listar_EvaluacionesVisuales();

    $html .= " <br>";
    $html .= "<center><u><b>EVALUACION VISUAL REALIZADA (EMBALAJE)</b></u></center>";
    $html .= "	<table width=\"100%\" rules=\"all\" class=\"modulo_table_list\">\n";
    $i = 0;
    foreach ($Listar_EvaluacionesVisuales as $k => $valor) {
        $Datos_Visual = $sql->BuscarItem_EVisual($doc_tmp_id, UserGetUID(), $item_id, $valor['evaluacion_visual_id']);
        $checked_1 = "";
        $checked_2 = "";
        if ($Datos_Visual['sw_cumple'] == '1')
            $checked_1 = " checked ";
        else
        if ($Datos_Visual['sw_cumple'] == '0')
            $checked_2 = " checked ";
        //print_r($Datos_Visual);

        $html .= " <tr >";
        $html .= "     <td class=\"modulo_table_list_title\" style=\"align:left\" width=\"60%\">";
        $html .= "       " . $valor['descripcion'];
        $html .= "       </td>";

        $html .= "     <td >";
        $html .= "       <input $checked_1 checked class=\"input-radio\" type=\"radio\" name=\"" . $valor['evaluacion_visual_id'] . "\" id=\"" . $valor['evaluacion_visual_id'] . "\" value=\"1\"><B>C</B><input $checked_2 class=\"input-radio\" type=\"radio\" name=\"" . $valor['evaluacion_visual_id'] . "\" id=\"" . $valor['evaluacion_visual_id'] . "\" value=\"0\"><B>/NC</B>";
        //$html .= "       <input type=\"hidden\" name=\"registros\" id=\"registros\" value=\"".$i."\">";
        $html .= "     </td>";
        $html .= " </tr>";
        $i++;
    }

    $html .= " <tr >";
    $html .= "     <td class=\"modulo_table_list_title\" style=\"align:left\" width=\"60%\">";
    $html .= "      OTRO:";
    $html .= "       </td>";

    $html .= "     <td >";
    $html .= "     <textarea name=\"evaluacion_final_otro\" id=\"evaluacion_final_otro\" class=\"textarea\" style=\"width:100%;\">" . $Datos_Visual['observaciones'] . "</textarea>";
    $html .= "     </td>";
    $html .= " </tr>";
    $html .= " </table>";

    $select .= " <select class=\"select\" name=\"sw_concepto_calidad\" id=\"sw_concepto_calidad\">";
    $select .= " <option value=\"\">-- SELECCIONAR -- </option>";
    $select .= " <option value=\"1\">APROBADO</option>";
    $select .= " <option value=\"2\">RECHAZADO</option>";
    $select .= " <option value=\"3\">RETENIDO EN CUARENTENA</option>";
    $select .= " </select>";
    $html .= " <br>";
    $html .= "<center><u><b>OBSERVACIONES GENERALES</b></u></center>";
    $html .= "	<table width=\"100%\" rules=\"all\" class=\"modulo_table_list\">\n";
    $html .= " <tr>";
    $html .= "    <td align=\"center\" class=\"modulo_table_list_title\">";
    $html .= "     *CONCEPTO DE CALIDAD";
    $html .= "     </td>";
    $html .= "    <td>";
    $html .= "     " . $select;
    $html .= "    </td>";
    $html .= " </tr>";
    $html .= " <tr>";
    $html .= "    <td colspan=\"6\" align=\"center\" class=\"modulo_table_list_title\">";
    $html .= "     DILIGENCIAR (CONDICIONES DE TRANSPORTE, EMBALAJE, MATERIAL DE EMPAQUE Y ENVASE, CONDICIONES ADMINISTRATIVAS, TECNICAS DE NEGOCIACION)";
    $html .= "     </td>";
    $html .= " </tr>";
    $html .= " <tr>";
    $html .= "    <td colspan=\"6\" align=\"center\" class=\"modulo_table_list_title\">";
    $html .= "     <textarea name=\"observacion\" id=\"observacion\" class=\"textarea\" style=\"width:100%;\">" . $Datos['observacion'] . "</textarea>";
    $html .= "     </td>";
    $html .= " </tr>";
    $html .= " </table>";

    $html .= " <br>";
    $html .= "	<table width=\"100%\" rules=\"all\" class=\"modulo_table_list\">\n";
    $html .= " <tr>";
    $html .= "     <td class=\"modulo_table_list_title\">";
    $html .= "       *RESPONSABLE (NOMBRE REALIZA)";
    $html .= "       </td>";
    $html .= "    <td >";
    $html .= "       <input type=\"text\" class=\"input-text\" name=\"responsable_realiza\" id=\"responsable_realiza\" value=\"" . $Datos['responsable_realiza'] . "\" style=\"width:100%\">";
    $html .= "     </td>";
    $html .= " </tr>";
    $html .= " <tr>";
    $html .= "     <td class=\"modulo_table_list_title\">";
    $html .= "       *RESPONSABLE (NOMBRE VERIFICA)";
    $html .= "       </td>";
    $html .= "    <td >";
    $html .= "       <input type=\"text\" class=\"input-text\" name=\"responsable_verifica\" id=\"responsable_verifica\" value=\"" . $Datos['responsable_verifica'] . "\" style=\"width:100%\">";
    $html .= "     </td>";
    $html .= " </tr>";
    $html .= " </table>";

    $html .= " <br>";
    $html .= "	<table width=\"100%\" rules=\"all\" class=\"modulo_table_list\">\n";
    $html .= " <tr>";
    $html .= "     <td align=\"center\">";
    if (!empty($Datos)) {
        $html .= "      <input type=\"hidden\" name=\"modificar\" id=\"modificar\" value=\"1\">";
    } else {
        $html .= "      <input type=\"hidden\" name=\"modificar\" id=\"modificar\" value=\"0\">";
    }
    $html .= "      <input type=\"hidden\" name=\"codigo_producto\" id=\"codigo_producto\" value=\"" . $producto['codigo_producto'] . "\">";
    $html .= "      <input type=\"hidden\" name=\"usuario_id\" id=\"usuario_id\" value=\"" . UserGetUID() . "\">";
    $html .= "      <input type=\"hidden\" name=\"doc_tmp_id\" id=\"doc_tmp_id\" value=\"" . $doc_tmp_id . "\">";
    $html .= "      <input type=\"hidden\" name=\"bodegas_doc_id\" id=\"bodegas_doc_id\" value=\"" . $bodegas_doc_id . "\">";
    $html .= "      <input type=\"hidden\" name=\"item_id\" id=\"item_id\" value=\"" . $item_id . "\">";
    $html .= "      <input type=\"hidden\" name=\"empresa_id\" id=\"empresa_id\" value=\"" . $producto['empresa_id'] . "\">";
    $html .= "      <input type=\"hidden\" name=\"centro_utilidad\" id=\"centro_utilidad\" value=\"" . $producto['centro_utilidad'] . "\">";
    $html .= "      <input type=\"hidden\" name=\"bodega\" id=\"bodega\" value=\"" . $producto['bodega'] . "\">";
    $html .= "      <input type=\"button\" value=\"REGISTRAR\" class=\"input-submit\" onclick=\"xajax_RegistrarActaTecnica(xajax.getFormValues('FormularioActaTecnica'));\">";
    $html .= "     </td>";
    $html .= " </tr>";
    $html .= "  </table>";
    $html .= "</form>";
    $objResponse->script("Iniciar3('ACTA TECNICA DE RECEPCION DE PRODUCTOS');");
    $objResponse->script("MostrarSpan('d2Container2');");
    $objResponse->assign("d2Contents2", "innerHTML", $objResponse->setTildes($html));
    $objResponse->script(" for(i=0;i<document.FormularioActaTecnica.sw_concepto_calidad.options.length;i++)
                                if(document.FormularioActaTecnica.sw_concepto_calidad.options[i].value == '" . $Datos['sw_concepto_calidad'] . "')
                                  document.FormularioActaTecnica.sw_concepto_calidad.options[i].selected=true;
                                ");

    return $objResponse;
}

function ActasTecnicas($empresa_id, $prefijo, $numero) {
    $objResponse = new xajaxResponse();
    $sql = new doc_bodegas_I002;
    $datos = $sql->Buscar_ActasTecnicas($empresa_id, $prefijo, $numero);
    //print_r($datos);
    $html .= "	<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
    $html .= "		<tr class=\"modulo_table_list_title\">\n";
    $html .= "      <td>";
    $html .= "        #";
    $html .= "      </td>";
    $html .= "      <td>";
    $html .= "        PREFIJO";
    $html .= "      </td>";
    $html .= "      <td>";
    $html .= "        NUMERO";
    $html .= "      </td>";
    $html .= "      <td>";
    $html .= "        PRODUCTO";
    $html .= "      </td>";
    $html .= "      <td>";
    $html .= "        USUARIO";
    $html .= "      </td>";
    $html .= "      <td>";
    $html .= "        IMP";
    $html .= "      </td>";
    $html .= "    </tr>";
    foreach ($datos as $key => $valor) {
        $html .= "		<tr class=\"modulo_list_claro\">\n";
        $html .= "      <td>";
        $html .= "        " . $valor['acta_tecnica_id'] . "";
        $html .= "      </td>";
        $html .= "      <td>";
        $html .= "        " . $valor['prefijo'] . "";
        $html .= "      </td>";
        $html .= "      <td>";
        $html .= "        " . $valor['numero'] . "";
        $html .= "      </td>";
        $html .= "      <td>";
        $html .= "        " . $valor['producto'] . "";
        $html .= "      </td>";
        $html .= "      <td>";
        $html .= "        " . $valor['nombre'] . "";
        $html .= "      </td>";
        $html .= "                      <td >\n";
        $direccion = "  app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/I002/imprimir/imprimir_ActaTecnica.php";
        $javas = "javascript:Imprimir('$direccion','" . SessionGetVar("Empresa_id") . "','" . $prefijo . "','" . $numero . "@" . $valor['acta_tecnica_id'] . "');";
        $html .= "                        <a title='IMPRIMIR' href=\"" . $javas . "\">\n";
        $html .= "                          <sub><img src=\"" . GetThemePath() . "/images/imprimir.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
        $html .= "                         </a>\n";
        $html .= "                      </td>\n";
        $html .= "    </tr>";
    }
    $objResponse->script("Iniciar3('ACTAS TECNICA DE RECEPCION DE PRODUCTOS');");
    $objResponse->script("MostrarSpan('d2Container2');");
    $objResponse->assign("d2Contents2", "innerHTML", $objResponse->setTildes($html));

    return $objResponse;
}

function RegistrarActaTecnica($Formulario) {
    $objResponse = new xajaxResponse();
    $sql = new doc_bodegas_I002;

    //print_r($query);

    if ($Formulario['c_nc_lote'] == "" || $Formulario['c_nc_vencimiento'] == "" || $Formulario['sw_concepto_calidad'] == "" || $Formulario['responsable_realiza'] == "" || $Formulario['responsable_verifica'] == "") {
        $mensaje_ = " POR FAVOR, DILIGENCIAR LOS CAMPOS (*) OBLIGATORIOS ";
        //$objResponse->assign("MensajeDeError","innerHTML",$mensaje_);
    } else {
        if ($Formulario['modificar'] == '0') {
            foreach ($Formulario as $key => $valor) {
                if (is_numeric($key)) {
                    $query .= "	INSERT INTO esm_acta_tecnica_evaluacion_visual_tmp( ";
                    $query .= "	doc_tmp_id,  ";
                    $query .= "	item_id,  ";
                    $query .= "	usuario_id,  ";
                    $query .= "	evaluacion_visual_id,  ";
                    $query .= "	sw_cumple,  ";
                    $query .= "	observaciones  ";
                    $query .= "	) ";
                    $query .= "VALUES ( ";
                    $query .= "  " . $Formulario['doc_tmp_id'] . ", ";
                    $query .= "  " . $Formulario['item_id'] . ", ";
                    $query .= "  " . UserGetUID() . ", ";
                    $query .= "  " . $key . ", ";
                    $query .= "  '" . $valor . "', ";
                    $query .= "  '" . $Formulario['evaluacion_final_otro'] . "' ";
                    $query .= "       ); ";
                }
            }
            $token = $sql->Insertar_ActaTmp($Formulario, $query);
        } else {
            foreach ($Formulario as $key => $valor) {
                if (is_numeric($key)) {
                    $query .= "	UPDATE esm_acta_tecnica_evaluacion_visual_tmp ";
                    $query .= "	SET ";
                    $query .= "	sw_cumple = '" . $valor . "', ";
                    $query .= "	observaciones = '" . $Formulario['evaluacion_final_otro'] . "' ";
                    $query .= " WHERE ";
                    $query .= "       item_id = " . $Formulario['item_id'] . "
								                    and   doc_tmp_id = " . $Formulario['doc_tmp_id'] . "
								                    and   usuario_id = " . UserGetUID() . "
								                    and   evaluacion_visual_id = " . $key . " ;";
                }
            }
            $token = $sql->Modificar_ActaTmp($Formulario, $query);
        }
    }
    if ($token != '1') {
        $mensaje = $sql->frmError;
        $objResponse->assign("MensajeDeError", "innerHTML", $mensaje_ . "<br>" . $mensaje['MensajeError']);
    } else {
        $objResponse->Alert("Ingreso Exitoso!!");
        $objResponse->script("Cerrar('d2Container2');");
        $objResponse->script("xajax_GetItems('" . $Formulario['doc_tmp_id'] . "','" . $Formulario['bodegas_doc_id'] . "');");
    }
    //print_r($query);
    return $objResponse;
}

function FormaConfirm($item_id, $doc_tmp_id, $bodegas_doc_id) {
    $objResponse = new xajaxResponse();

    $salida.="<table align=\"center\" width=\"100%\">";
    $salida.="	<tr>";
    $salida.="		<td colspan=\"2\" align=\"center\" class=\"label_error\">";
    $salida.="			CONFIRMA LA ELIMINACION DEL ITEM?<br>";
    $salida.="		</td>";
    $salida.="	</tr>";
    $salida.="	<tr align=\"center\">";
    $salida.="		<td>";
    $salida.="			<input type=\"button\" name=\"elimin\" class=\"input-submit\" value=\"ELIMINAR\" onclick=\"xajax_EliminarItem('$item_id','$doc_tmp_id','$bodegas_doc_id');Cerrar('d2Container2');\">";
    $salida.="		</td>";
    $salida.="		<td>";
    $salida.="			<input type=\"button\" name=\"cancel\" class=\"input-submit\" value=\"CANCELAR\" onclick=\"Cerrar('d2Container2');\">";
    $salida.="		</td>";
    $salida.="	</tr>";
    $salida.="</table>";


    $objResponse->assign("d2Contents2", "innerHTML", $objResponse->setTildes($salida));
    $objResponse->assign("d2Contents2", "innerHTML", $objResponse->setTildes($salida));

    return $objResponse;
}

function FormaElimDocTemporal($doc_tmp_id, $bodegas_doc_id, $accion) {
    $objResponse = new xajaxResponse();

    $salida.="<form name=\"FormaE\" action=\"$accion\" method=\"post\">";
    $salida.="<table align=\"center\" width=\"100%\">";
    $salida.="	<tr>";
    $salida.="		<td colspan=\"2\" align=\"center\" class=\"label_error\">";
    $salida.="			CONFIRMA LA ELIMINACION DEL DOCUMENTO TEMPORAL?<br>";
    $salida.="		</td>";
    $salida.="	</tr>";
    $salida.="	<tr align=\"center\">";
    $salida.="		<td>";
    $salida.="			<input type=\"button\" name=\"elimin\" class=\"input-submit\" value=\"ELIMINAR\" onclick=\"xajax_EliminarDocTemporal('$doc_tmp_id','$bodegas_doc_id');Cerrar('d2Container2');\">";
    $salida.="		</td>";
    $salida.="		<td>";
    $salida.="			<input type=\"button\" name=\"cancel\" class=\"input-submit\" value=\"CANCELAR\" onclick=\"Cerrar('d2Container2');\">";
    $salida.="		</td>";
    $salida.="	</tr>";
    $salida.="</table>";
    $salida.="</form>";

    $objResponse->assign("d2Contents2", "innerHTML", $objResponse->setTildes($salida));

    return $objResponse;
}

function EliminarDocTemporal($doc_tmp_id, $bodegas_doc_id) {
    $objResponse = new xajaxResponse();

    $objClass = new doc_bodegas_I002;

    if ($datosElim = $objClass->EliminarDocTemporal($doc_tmp_id, $bodegas_doc_id))
        ; {
        $objResponse->call("RegresarDEliminar");
    }
    return $objResponse;
}

function CrearDocumento($doc_tmp_id, $bodegas_doc_id) {
    $objResponse = new xajaxResponse();
    $consulta = new MovBodegasSQL();
    $objClass = new doc_bodegas_I002;
    $sql = AutoCarga::factory("MovDocI002", "classes", "app", "Inv_MovimientosBodegas");
    $ComprasTemporal = $sql->DocumentoTempIngresoCompras($doc_tmp_id);
    $DetalleTemporal = $consulta->ConsultaTmp(UserGetUID(), $doc_tmp_id);
    $CodigoProveedor = $sql->ConsultaProveedorOC($ComprasTemporal[0]['orden_pedido_id'], $ComprasTemporal[0]['empresa_id']);

    $ProductosAutorizados = $objClass->IngresosAutorizados($ComprasTemporal, $doc_tmp_id);
    $ActaTecnicas_Productos = $objClass->ActasTecnicas_Temporales(UserGetUID(), $doc_tmp_id);
    $EvaluacionesVisuales_Productos = $objClass->EvaluacionesVisuales_Temporales(UserGetUID(), $doc_tmp_id);
    //print_r(ActaTecnicas_Productos);
    //print_r($ActaTecnicas_Productos);
    //if($docs = $objClass->CrearDocumento($doc_tmp_id,$bodegas_doc_id))
    if ($docs = $objClass->CrearDocumento($doc_tmp_id, $bodegas_doc_id)) {
        $dca = AutoCarga::factory("DocumentosAutomaticos");
        $dca->DocumentoPedido(SessionGetVar("Empresa_id"), $docs['prefijo'], $docs['numero']);

        $salida = Documentos_HTML($docs, $CodigoProveedor[0]['codigo_proveedor_id']);
        $IngresoRecepcionParcialCabecera = $sql->InsertarRecepcionParcialCabecera($ComprasTemporal, $docs);
        //cantidad,codigo_producto,fecha_vencimiento,lote,porc_iva,recepcion_parcial_id,valor
        foreach ($DetalleTemporal as $key => $dt) {
            $ValorUnitario = $dt['total_costo'] / $dt['cantidad'];
            $IngresoRecepcionParcialDetalle = $sql->InsertarRecepcionParcialDetalle($dt['cantidad'], $dt['codigo_producto'], $dt['fecha_vencimiento'], $dt['lote'], $dt['porcentaje_gravamen'], $IngresoRecepcionParcialCabecera[0]['recepcion_parcial_id'], $ValorUnitario);
        }

        foreach ($ProductosAutorizados as $key => $pa) {
            $IngresoAutorizaciones = $objClass->IngresoAutorizacion($docs, $pa['orden_pedido_id'], $pa['codigo_producto'], $pa['justificacion_ingreso'], $pa['usuario_id_autorizador'], $pa['usuario_id_autorizador_2'], $pa['observacion_autorizacion'], $pa['lote'], $pa['fecha_vencimiento'], $pa['cantidad'], $pa['fecha_ingreso'], $pa['porcentaje_gravamen'], $pa['valor_unitario_compra'], $pa['valor_unitario_factura'], $pa['total_costo'], $pa['empresa_id']);
        }
        $token = $objClass->Insertar_Acta($ActaTecnicas_Productos, $EvaluacionesVisuales_Productos, $docs);
        //$borrarpara=$consulta->Borrarpara_docg($tipo_doc_bodega_id,$doc_tmp_id);
        //$salida = VentanaConfirmacionCrearDoc($docs);
        //$objResponse->call("RegresarCrear");

        $objResponse->assign("ventanauno", "innerHTML", $objResponse->setTildes($salida));
        $objResponse->assign("ProductosFueraOrdenCompra", "innerHTML", "");
        $objResponse->assign("productos_ordenCompra", "innerHTML", "");
        $objResponse->script("         var link=document.getElementById('link_foc');
		link.style.display = 'none';");
        $objResponse->assign("ventanados", "style.display", "none");
        $objResponse->assign("listadoP", "style.display", "none");
        $objResponse->assign("elimnDoc", "style.display", "none");
        $objResponse->assign("crearDoc", "style.display", "none");
        $mensaje = "DOCUMENTO TEMPORAL DE DESPACHO #:<b class=\"label_error\">" . $dca->mensaje . "</b>";
        $objResponse->assign("mensaje_docs_automaticos", "innerHTML", $mensaje);
    }
    return $objResponse;
}

function VentanaConfirmacionCrearDoc($docs) {
    $salida.="<form name=\"formaAcept1\" action=\"" . SessionGetVar("accion") . "\" method=\"post\">";
    $salida.="<table width=\"100%\">";
    $salida.="	<tr>";
    $salida.="		<td class=\"label_error\">SE CREO EL DOCUMENTO N. " . $docs['documento_id'] . "<br></td>";
    $salida.="	</tr>";
    $salida.="	<tr>";
    $salida.="		<td><input type=\"submit\" name=\"aceptar\" value=\"ACEPTAR\"></td>";
    $salida.="	</tr>";
    $salida.="</table>";
    $salida.="</form>";

    return $salida;
}

function Documentos_HTML($docs, $CodigoProveedorId) {
    if (!empty($docs)) {
        $url .= "&datos[empresa]=" . SessionGetVar("Empresa_id") . "&codigo_proveedor_id=" . $CodigoProveedorId . "&movimiento_bodega=1&nom_bodegax=" . $_REQUEST['DATOS']['nom_bodega'] . "&bodegax=" . SessionGetVar("bodega") . "&utility=" . SessionGetVar("centro_utilidad") . "";
        $Redireccion = ModuloGetURL("app", "Inv_FacturacionProveedor", "controller", "RecepcionesParciales") . "" . $url;
        $salida .= "<center>";
        $salida .= "	<fieldset style=\"width:50%\" class=\"normal_10AN\">";
        $salida .= "		<legend >INFORMACION ADICIONAL</legend>";
        $salida .= "			<div id=\"mensaje_docs_automaticos\" class=\"normal_10AN\"></div>";
        $salida .= "	</fieldset>";
        $salida .= "</center>";
        $salida .= "<center><label class=\"label_error\">SE HA CREADO EL DOCUMENTO EXITOSAMENTE</label></center>";
        $salida .= "                  <table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                      <td align=\"center\" width=\"12%\">\n";
        $salida .= "                        <a title='DOCUMENTO ID'>DOC ID<a> ";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"center\" width=\"25%\">\n";
        $salida .= "                        <a title='PREFIJO-NUMERO'>PREFIJO<a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"center\" width=\"30%\">\n";
        $salida .= "                        <a title='OBSERVACIONES DEL DOCUMENTO'>OBSERVACIONES<a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"center\" width=\"12%\">\n";
        $salida .= "                        <a title='FECHA'>FECHA<a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"center\" width=\"10%\">\n";
        $salida .= "                        <a title='ACCIONES SOBRE EL DOCUMENTO'>ACCIONES<a>";
        $salida .= "                      </td>\n";


        $salida .= "                      <td align=\"center\" width=\"10%\">\n";
        $salida .= "                        <a title='AUTORIZACIONES'>AUTORIZACIONES<a>";
        $salida .= "                      </td>\n";

        $salida .= "                      <td align=\"center\" width=\"10%\">\n";
        $salida .= "                        <a title='FACTURAS PROVEEDOR'>FACTURAS PROVEEDOR<a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"center\" width=\"10%\">\n";
        $salida .= "                        <a title='IMPRIMIR ACTAS TECNICAS'>ACTA TECNICA<a>";
        $salida .= "                      </td>\n";
        $salida .= "                    </tr>\n";

        $salida .= "                    <tr class=\"modulo_list_claro\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
        $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
        $salida .= "                        " . $docs['documento_id'];
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
        $salida .= "                         " . $docs['prefijo'] . "-" . $docs['numero'];
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
        $salida .= "                        " . $docs['observacion'];
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"right\" class=\"label_mark\">\n";
        $salida .= "                         " . substr($docs['fecha_registro'], 0, 10);
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"center\">\n";
        $direccion = "app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/I001/imprimir/imprimir_docI001.php";
        $javas = "javascript:Imprimir('$direccion','" . SessionGetVar("Empresa_id") . "','" . $docs['prefijo'] . "','" . $docs['numero'] . "');";
        $salida .= "                        <a title='IMPRIMIR DOCUMENTO' href=\"" . $javas . "\">\n";
        $salida .= "                          <sub><img src=\"" . GetThemePath() . "/images/imprimir.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
        $salida .= "                         </a>\n";
        $salida .= "                      </td>\n";

        $salida .= "                      <td align=\"center\">\n";
        $direccion = "  app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/I002/imprimir/imprimir_AdocI002.php";
        $javas = "javascript:Imprimir('$direccion','" . SessionGetVar("Empresa_id") . "','" . $docs['prefijo'] . "','" . $docs['numero'] . "');";
        $salida .= "                        <a title='AUTORIZACIONES' href=\"" . $javas . "\">\n";
        $salida .= "                          <sub><img src=\"" . GetThemePath() . "/images/imprimir.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
        $salida .= "                         </a>\n";
        $salida .= "                      </td>\n";

        $salida .= "                      <td align=\"center\">";
        $salida .= "                        <a title='RADICAR LA FACTURA DEL PROVEEDOR' href=\"" . $Redireccion . "\">\n";
        $salida .= "                          <sub><img src=\"" . GetThemePath() . "/images/proveedor.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
        $salida .= "                         </a>\n";
        $salida .= "                      </td>";


        $salida .= "                      <td align=\"center\">\n";
        $salida .= "                        <a title='ACTAS TECNICAS' onclick=\"xajax_ActasTecnicas('" . SessionGetVar("Empresa_id") . "','" . $docs['prefijo'] . "','" . $docs['numero'] . "');\">\n";
        $salida .= "                          <sub><img src=\"" . GetThemePath() . "/images/imprimir.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
        $salida .= "                         </a>\n";
        $salida .= "                      </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                    </table><br>\n";
    }
    return $salida;
}

function ObtenerPaginado($pagina, $path, $slc, $op, $orden, $tipo_param, $param, $doc_tmp_id, $bodegas_doc_id, $limite) {
    $TotalRegistros = $slc;
    $TablaPaginado = "";

    if ($limite == null) {
        $uid = UserGetUID();
        $LimitRow = intval(GetLimitBrowser());
    } else {
        $LimitRow = $limite;
    }
    if ($TotalRegistros > 0) {
        $columnas = 1;
        $NumeroPaginas = intval($TotalRegistros / $LimitRow);

        if ($TotalRegistros % $LimitRow > 0) {
            $NumeroPaginas++;
        }

        $Inicio = $pagina;
        if ($NumeroPaginas - $pagina < 9) {
            $Inicio = $NumeroPaginas - 9;
        } elseif ($pagina > 1) {
            $Inicio = $pagina - 1;
        }

        if ($Inicio <= 0) {
            $Inicio = 1;
        }

        $estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-weight:bold;font-size:11pt;\" ";

        $TablaPaginado .= "<tr>\n";
        if ($NumeroPaginas > 1) {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">Paginas:</td>\n";
            if ($pagina > 1) {
                $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
                //     na,criterio1,criterio2,criterio,div,forma
                $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarProductos('1','" . $orden . "','" . $tipo_param . "','" . $param . "','$doc_tmp_id','$bodegas_doc_id')\" title=\"primero\"><img src=\"" . $path . "/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
                $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarProductos('" . ($pagina - 1) . "','" . $orden . "','" . $tipo_param . "','" . $param . "','$doc_tmp_id','$bodegas_doc_id')\" title=\"anterior\"><img src=\"" . $path . "/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                $TablaPaginado .= "   </td>\n";
                $columnas +=2;
            }
            $Fin = $NumeroPaginas + 1;
            if ($NumeroPaginas > 10) {
                $Fin = 10 + $Inicio;
            }

            for ($i = $Inicio; $i < $Fin; $i++) {
                if ($i == $pagina) {
                    $TablaPaginado .="    <td class=\"modulo_list_oscuro\" $estilo align=\"center\"><b>" . $i . "</b></td>\n";
                } else {
                    $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:BuscarProductos('" . $i . "','" . $orden . "','" . $tipo_param . "','" . $param . "','$doc_tmp_id','$bodegas_doc_id')\">" . $i . "</a></td>\n";
                }
                $columnas++;
            }
        }
        if ($pagina < $NumeroPaginas) {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarProductos('" . ($pagina + 1) . "','" . $orden . "','" . $tipo_param . "','" . $param . "','$doc_tmp_id','$bodegas_doc_id')\" title=\"siguiente\"><img src=\"" . $path . "/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:BuscarProductos('" . $NumeroPaginas . "','" . $orden . "','" . $tipo_param . "','" . $param . "','$doc_tmp_id','$bodegas_doc_id')\" title=\"ultimo\"><img src=\"" . $path . "/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td>\n";
            $columnas +=2;
        }
        $aviso .= "   <tr><td class=\"label\"  colspan=" . $columnas . " align=\"center\">\n";
        $aviso .= "     Pagina&nbsp;" . $pagina . " de " . $NumeroPaginas . "</td>\n";
        $aviso .= "   </tr>\n";

        if ($op == 2) {
            $TablaPaginado .= $aviso;
        } else {
            $TablaPaginado = $aviso . $TablaPaginado;
        }
    }
    $Tabla .= "<table align=\"center\" cellspacing=\"3\" >\n";
    $Tabla .= $TablaPaginado;
    $Tabla .= "</table>";


    return $Tabla;
}

function Ingreso_factura($Numero_Factura, $Fecha_Factura, $Observaciones, $orden, $proveedor, $valor_factura) {
    $objResponse = new xajaxResponse();
    $objClass = new doc_bodegas_I002;

    if (empty($Numero_Factura) || empty($Fecha_Factura) || empty($Observaciones)) {
        $objResponse->alert("Error en el Ingreso!!!, Faltan Diligenciar mas Datos...");
    } else {

        $token = $objClass->IngresarFacturaVentaProveedor($Numero_Factura, $Fecha_Factura, $Observaciones, $orden, $proveedor, $valor_factura);

        if (!$token)
            $objResponse->alert("Error en el Ingreso!!!");
        else
            $objResponse->script("xajax_FacturaXOrdenCompra('" . $orden . "','" . $proveedor . "');");
    }

    //$objResponse->alert($Fecha_Factura);
    return $objResponse;
}

function MarcarINC($ItemId, $doc_tmp_id, $bodegas_doc_id, $valor_sw) {
    $objResponse = new xajaxResponse();

    $sql = AutoCarga::factory("MovDocI002", "classes", "app", "Inv_MovimientosBodegas");
    $token = $sql->MarcarINC($ItemId, $doc_tmp_id, $bodegas_doc_id, $valor_sw);
    if ($token) {
        $objResponse->script("xajax_GetItems('" . $doc_tmp_id . "','" . $bodegas_doc_id . "');");
    }
    else
        $objResponse->alert("Error en el Ingreso!!");
    return $objResponse;
}

?>