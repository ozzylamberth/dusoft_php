<?php

/**
 * Archivo Xajax
 * Tiene como responsabilidad hacer el manejo de las funciones
 * que son invocadas por medio de xajax
 *
 * @package IPSOFT-SIIS
 * @version 1.0 $
 * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Sandra Viviana Pantoja Torres 
 */
IncludeClass("ClaseHTML");
/*
 * Funcion que  permite ir a la funcion DetallePreorden cuando a una preorden se le a asignado una orden de compra-
 * @return object $objResponse objeto de respuesta al formulario
 */

function InformacionOrdenComp($proveed, $preorden_id, $empresa, $empresac) {
    $objResponse = new xajaxResponse();

    $sel = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
    $rst = $sel->SeleccionarInformacionDetalle($preorden_id, $proveed);
    $inf = $sel->SeleccionarMaxiPedido();
    $pedido_id = $inf [0]['numero'];
    $dat = $sel->insertarOrden_Pedido($pedido_id, $proveed, $empresa, $empresac);
    $inf = $sel->SeleccionarMaxcompras_ordenes_pedidos($proveed, $empresa);
    $orden_pedido_id = $inf['0']['numero'];

    $infd = $sel->Ingresarcompras_ordenes_pedidos_detalle($rst, $orden_pedido_id);
    $dtos = $sel->ActuEstado($preorden_id, $proveed);
    $url = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "DetallePreorden", array("preorden_id" => $preorden_id));
    $objResponse->script('
      window.location="' . $url . '";
      ');
    return $objResponse;
}

/*
 * Funcion que  permite asignar las condiciones a las ordenes de compras
 * @return object $objResponse objeto de respuesta al formulario
 */

function AsiganarCondiciones($preorden_id, $orden_pedido_id, $empresa) {
    $objResponse = new xajaxResponse();
    $html = "<fieldset class=\"fieldset\">\n";
    $html .= "  <legend class=\"normal_10AN\" align=\"center\">CONDICIONES DE COMPRAS DE PRODUCTOS</legend>\n";
    $html .= " <form name=\"Forma13\" id=\"Forma13\" method=\"post\" >\n";
    $html .= "  <table class=\"modulo_table_list_title\" border=\"1\" align=\"center\" width=\"80%\">\n";
    $html .= "    <tr class=\"modulo_table_list_title\">\n";
    $html .= "      <td width=\"10%\" align=\"center\">* CONDICIONES\n";
    $html .= "      </td>\n";
    $html .= "    </tr>\n";
    $html .= "    <tr class=\"modulo_table_list_title\">\n";
    $html .= "      <td colspan=\"5\"  align=\"center\" class=\"modulo_list_claro\">\n";
    $html .= "        <textarea onkeypress=\"return max(event)\"  name=\"observar\" rows=\"2\" style=\"width:100%\"></textarea>\n";
    $html .= "      </td>\n";
    $html .= "    </tr>\n";
    $html .= "  </table>\n";
    $html .= "  <table width=\"70%\"  border=\"0\"  align=\"center\">";
    $html .= "		<tr>\n";
    $html .= "      <td align=\"center\" class=\"normal_10AN\" >\n";
    $html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnCrear\"   value=\"O.K\" onclick=\"ValidarDtos(document.Forma13,'" . $preorden_id . "','" . $orden_pedido_id . "','" . $empresa . "');\">\n";
    $html .= " </td>\n";
    $html .= "		</tr>\n";
    $html .= "	</table>\n";
    $html .= "  </form>\n";
    $html .= "</fieldset><br>\n";
    $objResponse->assign("Contenido", "innerHTML", $objResponse->setTildes($html));
    $objResponse->call("MostrarSpan");
    return $objResponse;
}

/*
 * Funcion que  permite  ir a la funcion  DetallePreorden una vez se halla insertado las condiciones de compras
 * @return object $objResponse objeto de respuesta al formulario
 */

function TrasferirInformacion($observa, $preorden_id, $orden_pedido_id, $empresa) {
    $objResponse = new xajaxResponse();
    $sel = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
    $rst = $sel->insertarCondicionesOrden_Pedido($empresa, $orden_pedido_id, $observa);
    $url = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "DetallePreorden", array("preorden_id" => $preorden_id));
    $objResponse->script('
        window.location="' . $url . '";
        ');
    return $objResponse;
}

/*
 * Funcion que  permite  tranferir las condiciones de ordenes de compras
 * @return object $objResponse objeto de respuesta al formulario
 */

function TrasferirCondicion($observa, $orden_pedido_id, $empresa) {
    $objResponse = new xajaxResponse();
    $sel = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
    $rst = $sel->insertarCondicionesOrden_Pedido($empresa, $orden_pedido_id, $observa);
    $url = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "ConsultarOrdenes");
    $objResponse->script('
							 window.location="' . $url . '";
                            ');
    return $objResponse;
}

/*
 * Funcion que  permite  visualizar  la empresas que han registrado ordenes de compras
 * @return object $objResponse objeto de respuesta al formulario
 */

function EmpresaOrdenPedido() {
    $objResponse = new xajaxResponse();
    $mdl = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
    $Empresa = $mdl->EmpresasOrden_Pedido();
    $html = "<fieldset class=\"fieldset\">\n";
    $html .= "  <legend class=\"normal_10AN\" align=\"center\">SELECCION DE LA EMPRESA  </legend>\n";
    $html .= "<form name=\"Forma14\" id=\"Forma14\" method=\"post\" >\n";
    $html .= "  <table class=\"modulo_list_oscuro\" border=\"0\" align=\"center\" width=\"80%\">\n";
    $html .= "    <tr class=\"modulo_table_list_title\">\n";
    $html .= "      <td width=\"10%\" align=\"center\">EMPRESAS:\n";
    $html .= "      </td>\n";
    $html .= "		<td  class=\"modulo_list_claro\" colspan=\"4\">\n";
    $html .= "				<select name=\"empresas\" class=\"select\">\n";
    $html .= "             	<option value = '-1'>--  SELECCIONE --</option>\n";
    $csk = "";
    foreach ($Empresa as $indice => $valor) {
        if ($valor[0]['empresa_id'] == $request['empresa_id'])
            $sel = "selected";
        else
            $sel = "";
        $html .= "  <option value=\"" . $valor['empresa_id'] . "\" " . $sel . ">" . $valor['razon_social'] . "</option>\n";
    }
    $html .= "                </select>\n";
    $html .= "						  </td>\n";
    $html .= "    </tr>\n";
    $html .= "	</table>\n";
    $html .= "  <table width=\"70%\"  border=\"0\"  align=\"center\">";
    $html .= "		<tr>\n";
    $html .= "      <td align=\"center\" class=\"normal_10AN\" >\n";
    $html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnCrear\"   value=\"BUSCAR\" onclick=\"xajax_Proveedores(document.Forma14.empresas.value,'" . $valor['razon_social'] . "');\">\n";
    $html .= " </td>\n";
    $html .= "      <td align=\"center\">\n";
    $html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnCrear\"   value=\"CANCELAR\" onclick=\"OcultarSpan();\">   \n";
    $html .= " </td>\n";
    $html .= "		</tr>\n";
    $html .= "	</table>\n";
    $html .= "  <table  border=\"0\" align=\"center\" width=\"80%\">\n";
    $html .= "  <tr  >\n";
    $html .= "      <td colspan=\"12\"><a> <div id=\"Proveedor\"></div></td>\n";
    $html .= "  </tr>\n";
    $html .= "	</table>\n";
    $html .= "  </form>\n";
    $html .= "</fieldset><br>\n";
    $objResponse->assign("Contenido", "innerHTML", $objResponse->setTildes($html));
    $objResponse->call("MostrarSpan");
    return $objResponse;
}

/*
 * Funcion que  permite  listar los proveedores que tiene ordenes de compras
 * @return object $objResponse objeto de respuesta al formulario
 */

function Proveedores($empresapedido, $razon_social, $offset) {
    $objResponse = new xajaxResponse();
    $action['paginador'] = "paginador('" . $empresapedido . "','" . $razon_social . "'";
    $mdl = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
    $prov = $mdl->ConsultarProveedoresOrden_Pedido($empresapedido, $offset);
    $num = count($prov);
    $html .= "<form name=\"Proveedor\" id=\"Proveedor\" method=\"post\" >\n";
    $html .= "  <table width=\"95%\" class=\"modulo_table_list_title\" border=\"0\"  align=\"center\">";
    $html .= "	  <tr  align=\" class=\"modulo_table_list_title\" >\n";
    $html .= "      <td width=\"35%\">IDENTIFICACION</td>\n";
    $html .= "      <td  width=\"55%\">PROVEEDOR.</td>\n";
    $html .= "      <td   width=\"15%\">OP.</td>\n";
    $html .= "  </tr>\n";
    $pghtml = AutoCarga::factory('ClaseHTML');
    foreach ($prov as $llave => $proveedor) {
        $html .= "  <tr class=\"modulo_list_claro\">\n";
        $html .= "      <td  align=\"center\">" . $proveedor['tipo_id_tercero'] . " " . $proveedor['tercero_id'] . " </td>\n";
        $html .= "      <td align=\"left\">" . $proveedor['nombre_tercero'] . "</td>\n";
        $html .= "      <td align=\"center\">\n";
        $html .= "         <a href=\"#\" onclick=\"xajax_PasarVariablesOrden('" . $empresapedido . "','" . $proveedor['codigo_proveedor_id'] . "','" . $proveedor['nombre_tercero'] . "','" . $proveedor['tipo_id_tercero'] . "','" . $proveedor['tercero_id'] . "','" . $razon_social . "')\" class=\"label_error\">UNIFICAR</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
    }
    $html .= "	</table>\n";
    $html .= $pghtml->ObtenerPaginadoXajax($mdl->conteo, $mdl->pagina, $action['paginador']);
    $html .= "  </form>\n";
    $objResponse->assign("Proveedor" . $codigoproducto, "innerHTML", $objResponse->setTildes($html));
    return $objResponse;
}

function TProveedores($EmpresaId) {
    $objResponse = new xajaxResponse();
    $mdl = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
    $TipoId = $mdl->ConsultarTipoId();

    $SelectId .="<select id=\"tipo_id_tercero\" class=\"select\">";
    $SelectId .="<option value=\"\">Todos</option>";
    foreach ($TipoId as $valor => $tp) {
        $SelectId .="<option value=\"" . $tp['tipo_id_tercero'] . "\">" . $tp['tipo_id_tercero'] . "</option>";
    }
    $SelectId .="</select>";

    $html .= "<table width=\"100%\" align=\"center\">\n";
    $html .= "  <tr class=\"normal_10AN\">\n";
    $html .= "    <td>Tipo Id</td>\n";
    $html .= "    <td>" . $SelectId . "</td>\n";
    $html .= "    <td>Nombre</td>\n";
    $html .= "    <td>\n";
    $html .= "      <input type=\"text\" style=\"width:100%\" class=\"input-text\" id=\"nombre_tercero\">\n";
    $html .= "    </td>";
    $html .= "    <td align=\"center\">\n";
    $html .= "      <input onclick=\"xajax_LTProveedores('" . $EmpresaId . "',document.getElementById('tipo_id_tercero').value,document.getElementById('nombre_tercero').value)\" type=\"button\" class=\"input-submit\" value=\"Buscar Proveedor\">\n";
    $html .= "    </td>";
    $html .= "  </tr>";
    $html .= "  <tr>";
    $html .= "    <td colspan=\"5\">\n";
    $html .= "      <div id=\"SelectProveedores\"></div><div id=\"ContinuarCrearOC\"></div>\n";
    $html .= "    </td>";
    $html .= "  </tr>";
    $html .= "</table>";


    //ModuloGetURL("app", "Compras_Orden_Compras", "controller", "ComprasOrdenesPedidos");

    $objResponse->assign("TercerosProveedores", "innerHTML", $objResponse->setTildes($html));
    return $objResponse;
}

function LTProveedores($EmpresaId, $TipoId, $Nombre) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
    $Proveedores = $sql->ListarTercerosProveedores($EmpresaId, $TipoId, $Nombre);

    $Select .="<select id=\"TerceroProveedor\" class=\"select\">";
    foreach ($Proveedores as $valor => $p) {
        $Select .="<option onclick=\"xajax_ContinuarCrearOC('" . $EmpresaId . "','" . $p['codigo_proveedor_id'] . "','" . $p['tipo_id_tercero'] . "-" . $p['tercero_id'] . "-" . $p['nombre_tercero'] . "');\" value=\"" . $p['codigo_proveedor_id'] . "\">" . $p['tipo_id_tercero'] . "-" . $p['tercero_id'] . "-" . $p['nombre_tercero'] . "</option>";
    }
    $Select .="</select>";

    $objResponse->assign("SelectProveedores", "innerHTML", $Select);
    return $objResponse;
}

function ContinuarCrearOC($EmpresaId, $CodigoProveedorId, $DescripcionTercero) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");

    $sql = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
    $NumCompras = $sql->SeleccionarMaxcompras_ordenes_pedidos("-1", "-1");


    $url = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "CrearComprasOrdenesPedidos") . "&empresa_id=" . $EmpresaId . "&codigoproveedorid=" . $CodigoProveedorId . "&orden_pedido_id=" . ($NumCompras[0]['numero'] + 1) . "";



    //SELECT   MAX(orden_pedido_id) AS numero FROM compras_ordenes_pedidos


    $html = "<a href=\"" . $url . "\"  class=\"label_error\">Continuar OC con: " . $DescripcionTercero . "</a>";

    $objResponse->assign("ContinuarCrearOC", "innerHTML", $objResponse->setTildes($html));
    return $objResponse;
}

function SeleccionDeProductos($EmpresaId, $codigo_proveedor_id) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");

    $Laboratorios = $sql->ListaLaboratorios();
    $Moleculas = $sql->ListaMoleculas();

    $SelectLaboratorios = "<select name=\"clase_id\" id=\"clase_id\" class=\"select\" style=\"width:70%;height:70%\">";
    $SelectLaboratorios .= "<option value=\"\">TODOS</option>";
    foreach ($Laboratorios as $key => $valor) {
        $SelectLaboratorios .= "<option value=\"" . $valor['laboratorio_id'] . "\">";
        $SelectLaboratorios .= $valor['descripcion'];
        $SelectLaboratorios .= "</option>";
    }
    $SelectLaboratorios .= "</select>";

    $SelectMoleculas = "<select name=\"subclase_id\" id=\"subclase_id\" class=\"select\" style=\"width:60%;height:60%\">";
    $SelectMoleculas .= "<option value=\"\">TODOS</option>";
    foreach ($Moleculas as $key => $mol) {
        $SelectMoleculas .= "<option value=\"" . $mol['molecula_id'] . "\">";
        $SelectMoleculas .= $mol['descripcion'];
        $SelectMoleculas .= "</option>";
    }
    $SelectMoleculas .= "</select>";


    $html .= "<form name=\"buscador\" id=\"buscador\" method=\"post\">";
    $html .= "<table width=\"90%\" class=\"modulo_table_list\" align=\"center\" class=\"modulo_list_claro\">";
    $html .= "  <tr class=\"modulo_table_list_title\">";
    $html .= "	  <td>CODIGO</td>";
    $html .= "	  <td class=\"modulo_list_claro\" align=\"left\">";
    $html .= "			<input type=\"text\" id=\"codigo_producto_b\" class=\"input-text\">";
    $html .= "	  </td>";
    $html .= "		<td>DESCRIPCION...</td>";
    $html .= "		<td class=\"modulo_list_claro\" align=\"left\">";
    $html .= "			<input type=\"text\" id=\"descripcion_b\" class=\"input-text\">";
    $html .= "	  </td>";
    $html .= "		<td>CONCENTRACION</td>";
    $html .= "	  <td class=\"modulo_list_claro\" align=\"left\">";
    $html .= "			<input type=\"text\" id=\"contenido_unidad_venta_b\" class=\"input-text\">";
    $html .= "		</td>";
    $html .= "	</tr>";
    $html .= "	<tr class=\"modulo_table_list_title\">";
    $html .= "	  <td>LABORATORIO</td>";
    $html .= "		<td class=\"modulo_list_claro\" align=\"left\">";
    $html .= "			" . $SelectLaboratorios;
    $html .= "		</td>";
    $html .= "		<td>MOLECULA</td>";
    $html .= "		<td class=\"modulo_list_claro\" align=\"left\" colspan=\"3\">";
    $html .= "			" . $SelectMoleculas;
    $html .= "		</td>";
    $html .= "	</tr>";
    $html .= "	  <tr class=\"modulo_list_claro\">";
    $html .= "			<td colspan=\"6\" align=\"center\">";
    $html .= "			  <table width=\"100%\">";
    $html .= "			    <tr align=\"center\">\n";
    $html .= "			      <td width=\"50%\">\n";
    $html .= "			        <input type=\"button\" onclick=\"xajax_BuscarProductos(document.getElementById('codigo_producto_b').value,document.getElementById('descripcion_b').value,document.getElementById('contenido_unidad_venta_b').value,'" . $EmpresaId . "',document.getElementById('clase_id').value,document.getElementById('subclase_id').value,'1', '{$codigo_proveedor_id}');\"  class=\"input-submit\" value=\"Buscar\">";
    $html .= "			      </td>\n";
    $html .= "			      <td width=\"50%\">\n";
    $html .= "			        <input type=\"button\" onclick=\"LimpiarCampos(document.buscador)\"  class=\"input-submit\" value=\"Limpiar Campos\">";
    $html .= "			      </td>\n";
    $html .= "			    </tr>\n";
    $html .= "			  </table>\n";
    $html .= "			</td>";
    $html .= "		</tr>";
    $html .= "</table>";
    $html .= "</form>";

    $html .= "<div id=\"ListadoProductos\"></div>";

    $objResponse->assign("Contenido", "innerHTML", $objResponse->setTildes($html));
    $objResponse->script("MostrarSpan();");
    $objResponse->script("xajax_BuscarProductos('','','','" . $EmpresaId . "','','','1');");

    return $objResponse;
}

function BuscarProductos($CodigoProducto, $Descripcion, $Concentracion, $Empresa_Id, $ClaseId, $SubclaseId, $offset, $codigo_proveedor_id) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");

    $Productos = $sql->ListaProductosInventario($CodigoProducto, $Descripcion, $Concentracion, $Empresa_Id, $ClaseId, $SubclaseId, $offset, $codigo_proveedor_id);

    

    $pghtml = AutoCarga::factory('ClaseHTML');

    $action['paginador'] = "Paginador('" . $CodigoProducto . "','" . $Descripcion . "','" . $Concentracion . "','" . $Empresa_Id . "','" . $ClaseId . "','" . $SubclaseId . "'";
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo, $sql->pagina, $action['paginador']);

    $html .= "  <table width=\"90%\" class=\"modulo_table_list_title\" border=\"0\"  align=\"center\">";
    $html .= "	  <tr  align=\" class=\"modulo_table_list_title\" >\n";
    $html .= "      <td >CODIGO PRODUCTO</td>\n";
    $html .= "      <td >DESCRIPCION</td>\n";
    $html .= "      <td >$$ ULTIMA COMPRA</td>";
    $html .= "      <td >OP</td>\n";
    $html .= "  </tr>\n";

    foreach ($Productos as $k => $valor) {
        $html .= "  <tr class=\"modulo_list_claro\">\n";
        $html .= "      <td  align=\"center\">" . $valor['codigo_producto'] . " </td>\n";
        $html .= "      <td align=\"left\">" . $valor['descripcion'] . " " . $valor['presentacion'] . "-" . $valor['clase'] . "</td>\n";

        $msj = "";
        $color = "";
        if ($valor['tiene_valor_pactado'] == "0") {
            $msj = "No tiene contrato";
            $color = " style='color:red;' ";
        }

        $html .= "      <td align=\"center\" {$color}>
                            {$valor['costo_ultima_compra']} <br />
                            <span style='font-size : 7px'>{$msj}</span>    
                        </td>\n";
        $html .= "      <td align=\"center\">\n";
        $Prod = $valor['descripcion'] . " " . $valor['presentacion'] . "-" . $valor['clase'];
        $html .= "		<a onclick=\"AdicionarProducto('" . $_REQUEST['empresa_id'] . "','" . $valor['codigo_producto'] . "','" . $Prod . "','" . $valor['iva'] . "','" . $valor['costo_ultima_compra'] . "','" . trim($valor['cantidad']) . "','" . trim($valor['presentacion']) . "');\">";
        $html .="<img title=\"ADICIONAR PRODUCTOS\" src=\"" . GetThemePath() . "/images/checkno.png\" border=\"0\"></a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
    }
    $html .= "	</table>\n";
    $html .= "<br>\n";
    $objResponse->assign("ListadoProductos", "innerHTML", $objResponse->setTildes($html));
    return $objResponse;
}

function AgregarItemOC($OrdenPedidoId, $Empresa_Id, $CodigoProducto, $NumeroUnidades, $Valor, $PorcIva) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");

    if ($OrdenPedidoId == "" || $Empresa_Id == "" || $CodigoProducto == "" || $NumeroUnidades == "" || $Valor == "" || $PorcIva == "" || $PorcIva < 0 || $NumeroUnidades <= 0 || $Valor <= 0) {
        $objResponse->alert("Faltan Campos Por Diligenciar");
    } else {

        $Token = $sql->AgregarItemOC($OrdenPedidoId, $Empresa_Id, $CodigoProducto, $NumeroUnidades, $Valor, $PorcIva);

        if ($Token) {
            $objResponse->script("QuitarProducto();");
            $objResponse->script("xajax_DetalleOC('" . $OrdenPedidoId . "')");
        }
    }
    return $objResponse;
}

function AgregarItemOCEdicion($OrdenPedidoId, $Empresa_Id, $CodigoProducto, $NumeroUnidades, $Valor, $PorcIva) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");

    if ($OrdenPedidoId == "" || $Empresa_Id == "" || $CodigoProducto == "" || $NumeroUnidades == "" || $Valor == "" || $PorcIva == "" || $PorcIva < 0 || $NumeroUnidades <= 0 || $Valor <= 0) {
        $objResponse->alert("Faltan Campos Por Diligenciar");
    } else {

        $Token = $sql->AgregarItemOC($OrdenPedidoId, $Empresa_Id, $CodigoProducto, $NumeroUnidades, $Valor, $PorcIva);

        $item_id = $sql->ConsultarObtenerUltimoItemOC();

        $UsuarioId = UserGetUID();

        $sql->GuardarAuditoriaItemOC($OrdenPedidoId, $item_id['item_id'], $CodigoProducto, $NumeroUnidades, $Valor, $PorcIva, 1, 'Agregar', $UsuarioId);

        if ($Token) {
            $objResponse->script("QuitarProducto();");
            $objResponse->script("xajax_DetalleOCEdicion('" . $OrdenPedidoId . "')");
        }
    }
    return $objResponse;
}

function DetalleOC($orden_pedido_id) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");


    $Productos = $sql->ConsultarOC($orden_pedido_id);
    $num = count($Productos);
    $objResponse->script("document.getElementById('cantidad_productosOC').value='" . $num . "';");

    $action['volver'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "Empresas");
    if ($num > 0) {
        $link .= "      <a href=\"" . $action['volver'] . "\" class=\"label_error\">\n";
        $link .= "       [[::CONFIRMAR DOCUMENTO::]] \n";
        $link .= "      </a>\n";
        $objResponse->assign("link_confirmar", "innerHTML", $link);
    } else {
        $objResponse->assign("link_confirmar", "innerHTML", "");
    }


    $html .= "  <table width=\"90%\" class=\"modulo_table_list_title\" border=\"0\"  align=\"center\">";
    $html .= "	  <tr  align=\" class=\"modulo_table_list_title\" >\n";
    $html .= "      <td >CODIGO PRODUCTO</td>\n";
    $html .= "      <td >DESCRIPCION</td>\n";
    $html .= "      <td >NUMERO UNIDADES</td>\n";
    $html .= "      <td >IVA</td>\n";
    $html .= "      <td >VALOR</td>\n";
    $html .= "      <td >OP</td>\n";
    $html .= "  </tr>\n";

    foreach ($Productos as $k => $valor) {
        $html .= "  <tr class=\"modulo_list_claro\">\n";
        $html .= "      <td  align=\"center\">" . $valor['codigo_producto'] . " </td>\n";
        $html .= "      <td align=\"left\">" . $valor['descripcion'] . " " . $valor['presentacion'] . "-" . $valor['clase'] . "</td>\n";
        $html .= "      <td  align=\"center\">" . $valor['numero_unidades'] . " </td>\n";
        $html .= "      <td  align=\"center\">" . $valor['iva'] . " </td>\n";
        $html .= "      <td  align=\"center\">$" . FormatoValor($valor['valor'], 2) . " </td>\n";
        $html .= "      <td align=\"center\">\n";

        $Prod = $valor['descripcion'] . " " . $valor['presentacion'] . "-" . $valor['clase'];

        $subtotal = $subtotal + ($valor['valor'] * $valor['numero_unidades']);
        $iva = $iva + (($valor['valor'] * $valor['numero_unidades']) * ($valor['iva'] / 100));

        $html .= "		<a onclick=\"xajax_BorrarItemOC('compras_ordenes_pedidos_detalle','" . $valor['item_id'] . "','item_id','" . $orden_pedido_id . "');\">";
        $html .="<img title=\"BORRAR ITEM\" src=\"" . GetThemePath() . "/images/delete2.gif\" border=\"0\"></a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
    }
    $html .= "  <tr >\n";
    $html .= "   <td>SubTotal : $" . FormatoValor($subtotal, 2) . "</td>";
    $html .= "  </tr>\n";
    $html .= "  <tr >\n";
    $html .= "   <td>Iva : $" . FormatoValor($iva, 2) . "</td>";
    $html .= "  </tr>\n";
    $html .= "  <tr >\n";
    $html .= "   <td>Total : $" . FormatoValor($subtotal + $iva, 2) . "</td>";
    $html .= "  </tr>\n";
    $html .= "	</table>\n";




    $objResponse->assign("DetalleOC", "innerHTML", $objResponse->setTildes($html));
    return $objResponse;
}

function DetalleOCEdicion($orden_pedido_id) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");

    $Productos = $sql->ConsultarOC($orden_pedido_id);
    $num = count($Productos);
    $objResponse->script("document.getElementById('cantidad_productosOC').value='" . $num . "';");

    $action['volver'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "Empresas");
    if ($num > 0) {
        $link .= "      <a href=\"" . $action['volver'] . "\" class=\"label_error\">\n";
        $link .= "       [[::CONFIRMAR DOCUMENTO::]] \n";
        $link .= "      </a>\n";
        $objResponse->assign("link_confirmar", "innerHTML", $link);
    } else {
        $objResponse->assign("link_confirmar", "innerHTML", "");
    }

    $html .= "  <table width=\"90%\" class=\"modulo_table_list_title\" border=\"0\"  align=\"center\">";
    $html .= "	  <tr  align=\" class=\"modulo_table_list_title\" >\n";
    $html .= "      <td >CODIGO PRODUCTO</td>\n";
    $html .= "      <td >DESCRIPCION</td>\n";
    $html .= "      <td >NUMERO UNIDADES</td>\n";
    $html .= "      <td >IVA</td>\n";
    $html .= "      <td >VALOR</td>\n";
    $html .= "      <td >OP</td>\n";
    $html .= "  </tr>\n";

    foreach ($Productos as $k => $valor) {
        $html .= "  <tr class=\"modulo_list_claro\">\n";
        $html .= "      <td  align=\"center\">" . $valor['codigo_producto'] . " </td>\n";
        $html .= "      <td align=\"left\">" . $valor['descripcion'] . " " . $valor['presentacion'] . "-" . $valor['clase'] . "</td>\n";
        $html .= "      <td  align=\"center\">" . $valor['numero_unidades'] . " </td>\n";
        $html .= "      <td  align=\"center\">" . $valor['iva'] . " </td>\n";
        $html .= "      <td  align=\"center\">$" . FormatoValor($valor['valor'], 2) . " </td>\n";
        $html .= "      <td align=\"center\">\n";

        $Prod = $valor['descripcion'] . " " . $valor['presentacion'] . "-" . $valor['clase'];

        $subtotal = $subtotal + ($valor['valor'] * $valor['numero_unidades']);
        $iva = $iva + (($valor['valor'] * $valor['numero_unidades']) * ($valor['iva'] / 100));

        $html .= "		<a onclick=\"xajax_BorrarItemOCEdicion('compras_ordenes_pedidos_detalle','" . $valor['item_id'] . "','item_id','" . $orden_pedido_id . "');\">";
        $html .="<img title=\"BORRAR ITEM\" src=\"" . GetThemePath() . "/images/delete2.gif\" border=\"0\"></a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
    }
    $html .= "  <tr >\n";
    $html .= "   <td>SubTotal : $" . FormatoValor($subtotal, 2) . "</td>";
    $html .= "  </tr>\n";
    $html .= "  <tr >\n";
    $html .= "   <td>Iva : $" . FormatoValor($iva, 2) . "</td>";
    $html .= "  </tr>\n";
    $html .= "  <tr >\n";
    $html .= "   <td>Total : $" . FormatoValor($subtotal + $iva, 2) . "</td>";
    $html .= "  </tr>\n";
    $html .= "	</table>\n";

    $objResponse->assign("DetalleOC", "innerHTML", $objResponse->setTildes($html));
    return $objResponse;
}

function BorrarItemOC($tabla, $id, $campo_id, $orden_pedido_id) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ClaseUtil"); //cargo Funcion General para cambiar de estado un registro.
    $token = $sql->Borrar_Registro($tabla, $id, $campo_id);


    if ($token) {
        $objResponse->script("xajax_DetalleOC('" . $orden_pedido_id . "')");
    }
    else
        $objResponse->alert("Error al Borrar!!");


    return $objResponse;
}

function BorrarItemOCEdicion($tabla, $id, $campo_id, $orden_pedido_id) {
    $objResponse = new xajaxResponse();
    $consulta = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
    $sql = AutoCarga::factory("ClaseUtil"); //cargo Funcion General para cambiar de estado un registro.

    $item = $consulta->ObtenerItemOC($id);

    $UsuarioId = UserGetUID();

    $consulta->GuardarAuditoriaItemOC($item['orden_pedido_id'], $id, $item['codigo_producto'], $item['numero_unidades'], $item['valor'], $item['porc_iva'], $item['estado'], 'Eliminar', $UsuarioId);


    $token = $sql->Borrar_Registro($tabla, $id, $campo_id);

    if ($token) {
        $objResponse->script("xajax_DetalleOCEdicion('" . $orden_pedido_id . "')");
    }
    else
        $objResponse->alert("Error al Borrar!!");

    return $objResponse;
}

function ConfirmarOC($OrdenPedidoId, $Empresa_Id, $Cantidad, $Opc) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
    $url = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "Empresas");


    $Token = $sql->Eliminar_ComprasOrdenPedido_Detalle($OrdenPedidoId, $Empresa_Id);
    $Token1 = $sql->Eliminar_ComprasOrdenPedido($OrdenPedidoId, $Empresa_Id);


    if ($Token)
        if ($Token1) {

            $objResponse->Alert("Documento De Compras #" . $OrdenPedidoId . " Ha sido Eliminado");
            $objResponse->script("
								var pagina='" . $url . "';
								document.location.href=pagina;");
        } else {
            $objResponse->alert("Error");
        }


    //$objResponse->alert($num);

    return $objResponse;
}

/* /*
 * Funcion que  permite  ir a la funcion  DetallePreorden una vez se halla insertado las condiciones de compras
 * @return object $objResponse objeto de respuesta al formulario
 */

function PasarVariablesOrden($empresapedido, $cod_proveedor, $nombre_tercero, $tipo_id_tercero, $tercero_id, $razon_social) {
    $objResponse = new xajaxResponse();
    $url = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "UnificacionOrdePedidoxProveedor", array("empresapedido" => $empresapedido, "proveedor" => $cod_proveedor, "nombre_tercero" => $nombre_tercero, "tipo_id_tercero" => $tipo_id_tercero, "tercero_id" => $tercero_id, "razon_social" => $razon_social));
    $objResponse->script('
							 window.location="' . $url . '";
									');
    return $objResponse;
}

/*
 * Funcion que  permite  tranferir las observaciones  para crear el documento de pedido por orden de compra
 * @return object $objResponse objeto de respuesta al formulario
 */

function TransfeOrdenPedido($observa) {

    $objResponse = new xajaxResponse();
    $url = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "CrearDocumentoYUnificar", array("observa" => $observa, "proveedor" => $cod_proveedor, "nombre_tercero" => $nombre_tercero, "tipo_id_tercero" => $tipo_id_tercero, "tercero_id" => $tercero_id, "razon_social" => $razon_social));
    $objResponse->script('
							 window.location="' . $url . '";
									');
    return $objResponse;
}

/*
 * Funcion que  permite  mostrar las ordenes de compras del proveedor seleccionado
 * @return object $objResponse objeto de respuesta al formulario
 */

function MostrarOrdenesCompra($form) {
    $objResponse = new xajaxResponse();
    $mdl = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
    $prov = $mdl->ConsultarProveedoresOrdenCompra($form['proveedor_id']);
    $html .= "<fieldset class=\"fieldset\">\n";
    $html .= "  <legend    align=\"left\"><b>" . $prov[0]['nombre_tercero'] . " (" . $prov[0]['tercero_id'] . " -" . $prov[0]['tipo_id_tercero'] . ") </b></legend>\n";
    $html .= "<form name=\"Proveedor\" id=\"Proveedor\" method=\"post\" >\n";
    $html .= "  <table width=\"95%\" class=\"modulo_table_list\"  border=\"0\"  align=\"center\">";
    $html .= "	  <tr  class=\"formulacion_table_list\" >\n";
    $html .= "      <td width=\"28%\">ORDEN COMPRA NO</td>\n";
    $html .= "      <td  width=\"25%\">FECHE REGISTRO.</td>\n";
    $html .= "      <td  width=\"55%\">USUARIO.</td>\n";
    $html .= "      <td  width=\"5%\">DETALLE.</td>\n";
    $html .= "      <td  width=\"10%\">OP.</td>\n";
    $html .= "  </tr>\n";

    foreach ($prov as $llave => $proveedor) {
        $html .= "  <tr class=\"modulo_list_claro\">\n";
        $html .= "      <td  align=\"center\">" . $proveedor['orden_pedido_id'] . "</td>\n";
        $html .= "      <td align=\"left\">" . $proveedor['fecha_orden'] . "</td>\n";
        $html .= "      <td align=\"left\">" . $proveedor['nombre'] . "</td>\n";
        $html .= "      <td align=\"center\">\n";
        $html .= "         <a href=\"#\" onclick=\"xajax_DetalleOrdenCompra('" . $proveedor['orden_pedido_id'] . "')\" class=\"label_error\">  <img src=\"" . GetThemePath() . "/images/informacion.png\" border=\"0\" width=\"15\" height=\"17\"></a>\n";
        $html .= "      </td>\n";
        $html .= "      <td align=\"center\">\n";
        $html .= "         <a href=\"#\" onclick=\"xajax_CargarOrdenCompra('" . $proveedor['orden_pedido_id'] . "','" . $form['proveedor_id'] . "')\" class=\"label_error\">  <img src=\"" . GetThemePath() . "/images/arriba.png\" border=\"0\" width=\"15\" height=\"17\"></a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
    }
    $html .= "	</table>\n";
    $html .= "</fieldset><br>\n";
    $html .= "  </form>\n";
    $objResponse->assign("Proveedor", "innerHTML", $objResponse->setTildes($html));
    return $objResponse;
}

/*
 * Funcion que  permite  cargar las ordenes de compras que van hacer unificadas
 * @return object $objResponse objeto de respuesta al formulario
 */

function CargarOrdenCompra($orden, $proveedor) {
    $objResponse = new xajaxResponse();
    $mdl = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
    $datos = $mdl->ConsultarOrdenCompraDetalle_($orden);

    $info = $mdl->Consultar_tmp_OrdenPedido($proveedor, $orden);


    if (!empty($datos)) {
        if (empty($info)) {

            $dats = $mdl->Ingresar_tmpcompras_ordenes_pedidos($datos, $proveedor);
        }
    } else {
        $html .= " <table  width=\"85%\" align=\"center\"  >  <tr> <td align=\"center\" class=\"label_error\">NO SE PUEDE CARGAR ORDEN DE COMPRA SIN DETALLE </td></tr></table>\n";
    }

    $dt = $mdl->Consultar_tmp_OrdenPedidoDetalle($proveedor);
    $html .= "<fieldset class=\"fieldset\">\n";
    $html .= "  <legend    align=\"left\"><b>UNIFICAR</b></legend>\n";
    $html .= "<form name=\"unificar\" id=\"unificar\" method=\"post\" >\n";
    $html .= "  <table width=\"95%\" class=\"modulo_table_list\" border=\"0\"  align=\"center\">";
    $html .= "	  <tr  class=\"formulacion_table_list\" >\n";
    $html .= "      <td width=\"25%\">ORDEN COMPRA NO</td>\n";
    $html .= "      <td  width=\"26%\">FECHE REGISTRO.</td>\n";
    $html .= "      <td  width=\"55%\">USUARIO.</td>\n";
    $html .= "  </tr>\n";
    foreach ($dt as $llave => $pro) {
        $html .= "  <tr class=\"modulo_list_claro\">\n";
        $html .= "      <td  align=\"center\">" . $pro['orden_pedido_id'] . "</td>\n";
        $html .= "      <td align=\"left\">" . $pro['fecha_registro'] . "</td>\n";
        $html .= "      <td align=\"left\">" . $pro['nombre'] . "</td>\n";
        $html .= "  </tr>\n";
    }
    $html .= "	</table>\n";
    $html .= "  <table width=\"85%\"  class=\"normal_10AN\"  border=\"0\"  align=\"right\">";
    $html .= "	  <tr  align=\"right\" >\n";
    $html .= "    <td>\n";
    $html .= "      <input class=\"input-submit\" type=\"button\" name=\"unificar\" value=\"UNIFICAR\" onclick=\"unificarOrdenPedido('" . $proveedor . "')\">\n";
    $html .= "    </td>\n";
    $html .= "    <td>\n";
    $html .= "      <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\" onclick=\"xajax_cancelarTodoOrdenPedido('" . $proveedor . "')\">\n";
    $html .= "    </td>\n";
    $html .= "  </tr>\n";
    $html .= "	</table>\n";
    $html .= "</fieldset><br>\n";
    $html .= "  </form>\n";


    $objResponse->assign("cargarOrden", "innerHTML", $objResponse->setTildes($html));
    return $objResponse;
}

/*
 * Funcion que  permite  visualizar el detalle de la orden de compra seleccionada
 * @return object $objResponse objeto de respuesta al formulario
 */

function DetalleOrdenCompra($orden) {
    $objResponse = new xajaxResponse();
    $mdl = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
    $datos = $mdl->ConsultarOrdenCompraDetalle($orden);
    $html .= "<fieldset class=\"fieldset\">\n";
    $html .= "  <legend    align=\"left\"><b>DETALLE DE LA ORDEN DE COMPRA </b></legend>\n";
    $html .= "<form name=\"detalle\" id=\"detalle\" method=\"post\" >\n";
    $html .= "  <table width=\"35%\"  class=\"modulo_table_list\"  border=\"0\"  align=\"center\">";
    $html .= "	  <tr class=\"formulacion_table_list\"  align=\"center\" >\n";
    $html .= "      <td   width=\"50%\">ORDEN DE COMPRA NO.</td>\n";
    $html .= "      <td  class=\"modulo_list_claro\"   align=\"center\">" . $orden . "</td>\n";
    $html .= "  </tr>\n";
    $html .= "	</table>\n";
    $html .= "  <BR>\n";
    $html .= "  <table width=\"105%\" class=\"modulo_table_list\"  border=\"0\"  align=\"center\">";
    $html .= "	  <tr class=\"formulacion_table_list\" >\n";
    $html .= "      <td width=\"20%\">MOLECULA</td>\n";
    $html .= "      <td width=\"10%\">CODIGO</td>\n";
    $html .= "      <td width=\"25%\">DESCRIPCION</td>\n";
    $html .= "      <td width=\"7%\">CANTIDAD</td>\n";
    $html .= "      <td width=\"7%\">CANT.RE</td>\n";
    $html .= "      <td width=\"7%\">V.PACTADO</td>\n";
    $html .= "  </tr>\n";
    $est = "modulo_list_oscuro";
    $back = "#DDDDDD";
    foreach ($datos as $key => $dtl) {
        $html .= "	  <tr  align=\"CENTER\"    class=\"modulo_list_claro\" >\n";

        $html .= "      <td  align=\"center\">" . $dtl['molecula'] . "</td>\n";
        $html .= "      <td align=\"center\">" . $dtl['codigo_producto'] . " </td>\n";
        $html .= "      <td align=\"center\">" . $dtl['producto'] . "  " . $dtl['contenido_unidad_venta'] . " " . $dtl['abreviatura'] . " -" . $dtl['laboratorio'] . "</td>\n";
        $html .= "      <td align=\"center\">" . round($dtl['numero_unidades']) . "</td>\n";
        $html .= "      <td align=\"center\">" . round($dtl['numero_unidades_recibidas']) . "</td>\n";
        $html .= "      <td align=\"center\">$" . round($dtl['valor']) . "</td>\n";
    }
    $html .= "  </tr>\n";
    $html .= "	</table>\n";
    $html .= "</fieldset><br>\n";
    $html .= "  </form>\n";
    $objResponse->assign("Detalleorden", "innerHTML", $objResponse->setTildes($html));
    return $objResponse;
}

/*
 * Funcion que  permite  cancelar todo lo que se ha realizado cuando se ha seleccionado las ordenes de compras a unificar
 * @return object $objResponse objeto de respuesta al formulario
 */

function cancelarTodoOrdenPedido($proveedor) {
    $objResponse = new xajaxResponse();
    $mdl = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
    $datos = $mdl->Eliminar_tmp_OrdenPedido($proveedor);
    $url = " RemoteXajax/Compras_Orden_Compras/Compras_Orden_Compras.php";
    $objResponse->script('
				 window.location="' . $url . '";
							');
    return $objResponse;
}

/*
 * Funcion que  permite  Crear la primera parte de la orden de compras  y me lleva a la funcion UnificarOrdenPedidoProveedor
 * @return object $objResponse objeto de respuesta al formulario
 */

function unificarTodasOrdenes($proveedor) {
    $objResponse = new xajaxResponse();
    $mdl = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
    $emp = SessionGetVar("DatosEmpresaAF");
    $empresa = $emp['empresa_id'];

    $inf = $mdl->SeleccionarMaxiPedido2($proveedor);


    $pedido_id = $inf[0]['numero'];
    $datos = $mdl->insertarOrden_Pedido($pedido_id, $proveedor, $empresa, $empresa);

    $url = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "UnificarOrdenPedidoProveedor", array("proveedor" => $proveedor));
    $objResponse->script('
					 window.location="' . $url . '";
								');
    return $objResponse;
}

function AnularOC($pedido_id) {
    $objResponse = new xajaxResponse();
    $mdl = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");

    $datos = $mdl->AnularOC($pedido_id);

    //$url=ModuloGetURL("app", "Compras_Orden_Compras", "controller", "UnificarOrdenPedidoProveedor",array("proveedor"=>$proveedor));
    $objResponse->script('
                            document.FormaConsultar2.submit();
													');
    return $objResponse;
}

function ModificarOC($empresa_id, $orden_pedido_id) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
    $Presente = $sql->DocumentoCompraEnBodega($empresa_id, $orden_pedido_id);

    if (empty($Presente)) {
        $Laboratorios = $sql->ListaLaboratorios();
        $Moleculas = $sql->ListaMoleculas();

        $SelectLaboratorios = "<select name=\"clase_id\" id=\"clase_id\" class=\"select\" style=\"width:70%;height:70%\">";
        $SelectLaboratorios .= "<option value=\"\">TODOS</option>";
        foreach ($Laboratorios as $key => $valor) {
            $SelectLaboratorios .= "<option value=\"" . $valor['laboratorio_id'] . "\">";
            $SelectLaboratorios .= $valor['descripcion'];
            $SelectLaboratorios .= "</option>";
        }
        $SelectLaboratorios .= "</select>";

        $SelectMoleculas = "<select name=\"subclase_id\" id=\"subclase_id\" class=\"select\" style=\"width:60%;height:60%\">";
        $SelectMoleculas .= "<option value=\"\">TODOS</option>";
        foreach ($Moleculas as $key => $mol) {
            $SelectMoleculas .= "<option value=\"" . $mol['molecula_id'] . "\">";
            $SelectMoleculas .= $mol['descripcion'];
            $SelectMoleculas .= "</option>";
        }
        $SelectMoleculas .= "</select>";


        $html .= "<form name=\"buscador\" id=\"buscador\" method=\"post\">";
        $html .= "<table width=\"90%\" class=\"modulo_table_list\" align=\"center\" class=\"modulo_list_claro\">";
        $html .= "  <tr class=\"modulo_table_list_title\">";
        $html .= "	  <td>CODIGO</td>";
        $html .= "	  <td class=\"modulo_list_claro\" align=\"left\">";
        $html .= "			<input type=\"text\" id=\"codigo_producto_b\" class=\"input-text\">";
        $html .= "	  </td>";
        $html .= "		<td>DESCRIPCION</td>";
        $html .= "		<td class=\"modulo_list_claro\" align=\"left\">";
        $html .= "			<input type=\"text\" id=\"descripcion_b\" class=\"input-text\">";
        $html .= "	  </td>";
        $html .= "		<td>CONCENTRACION</td>";
        $html .= "	  <td class=\"modulo_list_claro\" align=\"left\">";
        $html .= "			<input type=\"text\" id=\"contenido_unidad_venta_b\" class=\"input-text\">";
        $html .= "		</td>";
        $html .= "	</tr>";
        $html .= "	<tr class=\"modulo_table_list_title\">";
        $html .= "	  <td>LABORATORIO</td>";
        $html .= "		<td class=\"modulo_list_claro\" align=\"left\">";
        $html .= "			" . $SelectLaboratorios;
        $html .= "		</td>";
        $html .= "		<td>MOLECULA</td>";
        $html .= "		<td class=\"modulo_list_claro\" align=\"left\" colspan=\"3\">";
        $html .= "			" . $SelectMoleculas;
        $html .= "		</td>";
        $html .= "	</tr>";
        $html .= "	  <tr class=\"modulo_list_claro\">";
        $html .= "			<td colspan=\"6\" align=\"center\">";
        $html .= "			  <table width=\"100%\">";
        $html .= "			    <tr align=\"center\">\n";
        $html .= "			      <td width=\"50%\">\n";
        $html .= "			        <input type=\"button\" onclick=\"xajax_ModificacionDetalleOC(document.getElementById('codigo_producto_b').value,document.getElementById('descripcion_b').value,document.getElementById('contenido_unidad_venta_b').value,'" . $empresa_id . "',document.getElementById('clase_id').value,document.getElementById('subclase_id').value,'" . $orden_pedido_id . "','1');\"  class=\"input-submit\" value=\"Buscar\">";
        $html .= "			      </td>\n";
        $html .= "			      <td width=\"50%\">\n";
        $html .= "			        <input type=\"reset\" class=\"input-submit\" value=\"Limpiar Campos\">";
        $html .= "			      </td>\n";
        $html .= "			    </tr>\n";
        $html .= "			  </table>\n";
        $html .= "			</td>";
        $html .= "		</tr>";
        $html .= "</table>";
        $html .= "</form>";
        $html .= "    <form id=\"formulario\" name=\"formulario\" method=\"post\">";
        $html .= "<div id=\"DetalleOrdenCompra\"></div>";
        $html .= "    </form>";
        $objResponse->script("xajax_ModificacionDetalleOC('','','','" . $empresa_id . "','','','" . $orden_pedido_id . "','1');");
    } else {
        $html .= "<table width=\"90%\" class=\"modulo_table_list\" align=\"center\" class=\"modulo_list_claro\">";
        $html .= "  <tr >";
        $html .= "	  <td class=\"label_error\" align=\"center\">EL DOCUMENTO DE ORDEN DE COMPRA, DEBE SER CERRADA EN BODEGA</td>";
        $html .= "	</tr>";
        $html .= "	</table>";
    }
    $objResponse->assign("Contenido", "innerHTML", $objResponse->setTildes($html));
    $objResponse->script("MostrarSpan();");


    return $objResponse;
}

function ModificacionDetalleOC($CodigoProducto, $Descripcion, $Concentracion, $Empresa_Id, $ClaseId, $SubclaseId, $orden_pedido_id, $offset) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");


    $Productos = $sql->DetalleOrdenCompra($CodigoProducto, $Descripcion, $Concentracion, $Empresa_Id, $ClaseId, $SubclaseId, $orden_pedido_id, $offset);
    $pghtml = AutoCarga::factory('ClaseHTML');



    $action['paginador'] = "Paginador('" . $CodigoProducto . "','" . $Descripcion . "','" . $Concentracion . "','" . $Empresa_Id . "','" . $ClaseId . "','" . $SubclaseId . "','" . $orden_pedido_id . "'";
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo, $sql->pagina, $action['paginador']);


    $html .= "  <table width=\"90%\" class=\"modulo_table_list_title\" border=\"0\"  align=\"center\">";
    $html .= "	  <tr  align=\" class=\"modulo_table_list_title\" >\n";
    $html .= "    <input type=\"hidden\" name=\"orden_pedido_id\" id=\"orden_pedido_id\" value=\"" . $orden_pedido_id . "\">";
    $html .= "    <input type=\"hidden\" name=\"codigo_producto\" id=\"codigo_producto\" value=\"" . $CodigoProducto . "\">";
    $html .= "    <input type=\"hidden\" name=\"descripcion\" id=\"descripcion\" value=\"" . $Descripcion . "\">";
    $html .= "    <input type=\"hidden\" name=\"contenido_unidad_venta\" id=\"contenido_unidad_venta\" value=\"" . $Concentracion . "\">";
    $html .= "    <input type=\"hidden\" name=\"empresa_id\" id=\"empresa_id\" value=\"" . $Empresa_Id . "\">";
    $html .= "    <input type=\"hidden\" name=\"clase_id\" id=\"clase_id\" value=\"" . $ClaseId . "\">";
    $html .= "    <input type=\"hidden\" name=\"subclase_id\" id=\"subclase_id\" value=\"" . $SubclaseId . "\">";
    $html .= "    <input type=\"hidden\" name=\"offset\" id=\"offset\" value=\"" . $offset . "\">";
    $html .= "      <td >CODIGO PRODUCTO</td>\n";
    $html .= "      <td >DESCRIPCION</td>\n";
    $html .= "      <td >UNIDADES</td>";
    $html .= "      <td >VALOR</td>";
    $html .= "      <td >OP</td>\n";
    $html .= "   </tr>\n";
    $i = 0;

    foreach ($Productos as $k => $valor) {
        $html .= "  <tr class=\"modulo_list_claro\">\n";
        $html .= "      <td  align=\"center\">" . $valor['codigo_producto'] . " </td>\n";
        $html .= "      <td align=\"left\">" . $valor['nombre'] . "</td>\n";

        $html .= "      <td align=\"left\"><input type=\"text\" name=\"cantidad" . $valor['item_id'] . "\" id=\"cantidad" . $valor['item_id'] . "\" class=\"input-text\" value=\"" . $valor['numero_unidades'] . "\"></td>\n";
        $html .= "      <td align=\"left\"><input type=\"text\" name=\"valor" . $valor['item_id'] . "\" id=\"valor" . $valor['item_id'] . "\" class=\"input-text\" value=\"" . $valor['valor'] . "\"></td>\n";

        $html .= "      <td align=\"center\" id=\"ok" . $i . "\">\n";
        $html .= "       <input type=\"hidden\" name=\"Registros\" id=\"Registros\" value=\"" . $i . "\"";
        $html .= "        <input type=\"checkbox\" class=\"checkbox\" name=\"" . $i . "\" id=\"" . $i . "\" value=\"" . $valor['item_id'] . "\">";
        /* $html .= "		<a onclick=\"ModificarProducto('".$_REQUEST['empresa_id']."','".$valor['codigo_producto']."','".$Prod."','".$valor['iva']."','".$valor['costo_ultima_compra']."');\">";
          $html .="<img title=\"ADICIONAR PRODUCTOS\" src=\"".GetThemePath()."/images/checkno.png\" border=\"0\"></a>\n"; */
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $i++;
    }
    $html .= "  <tr>";
    $html .= "    <td align=\"center\" colspan=\"5\"><input class=\"input-submit\" type=\"button\" value=\"MODIFICAR ITEMS\" onclick=\"xajax_ValidarModificacionOC(xajax.getFormValues('formulario'));\"></td>";
    $html .= "  </tr>";
    $html .= "	</table>\n";


    $html .= "<br>\n";

    $objResponse->assign("DetalleOrdenCompra", "innerHTML", $objResponse->setTildes($html));
    return $objResponse;
}

function ValidarModificacionOC($Formulario) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");

    for ($i = 0; $i <= $Formulario['Registros']; $i++) {
        // $objResponse->alert($Formulario[$i]);
        if ($Formulario[$i] != "") {
            if (!is_numeric($Formulario["cantidad" . $Formulario[$i]]) || $Formulario["cantidad" . $Formulario[$i]] < 0) {

                $objResponse->script("document.getElementById('cantidad" . $Formulario[$i] . "').style.backgroundColor='Red';");
            } else {
                $objResponse->script("document.getElementById('cantidad" . $Formulario[$i] . "').style.backgroundColor='white';");
                if (!is_numeric($Formulario["valor" . $Formulario[$i]]) || $Formulario["valor" . $Formulario[$i]] < 0) {
                    $objResponse->script("document.getElementById('valor" . $Formulario[$i] . "').style.backgroundColor='Red';");
                } else {
                    $objResponse->script("document.getElementById('valor" . $Formulario[$i] . "').style.backgroundColor='white';");

                    $Presente = $sql->DocumentoCompraEnBodega($Formulario['empresa_id'], $Formulario['orden_pedido_id']);
                    if (!empty($presente))
                        $objResponse->alert("EL DOCUMENTO DE COMPRA, DEBE SER CERRADO EN BODEGA PARA CONTINUAR!!");
                    else {
                        $usuario_id = UserGetUID();

                        $sql->GuardarAuditoriaItemOriginalActualizadoOC($Formulario['orden_pedido_id'], $Formulario[$i], $usuario_id);

                        $sw = $sql->ModificarOrdenCompra($Formulario['orden_pedido_id'], $Formulario['empresa_id'], $Formulario[$i], $Formulario["cantidad" . $Formulario[$i]], $Formulario["valor" . $Formulario[$i]]);

                        $sql->GuardarAuditoriaItemModificadoActualizadoOC($Formulario['orden_pedido_id'], $Formulario[$i], $Formulario["cantidad" . $Formulario[$i]], $Formulario["valor" . $Formulario[$i]], $usuario_id);
                        if (!$sw)
                            $objResponse->script("document.getElementById('ok" . $i . "').style.backgroundColor='Red';");
                        else
                            $objResponse->script("document.getElementById('ok" . $i . "').style.backgroundColor='green';");
                    }
                }
            }
        }
    }


    //$objResponse->assign("DetalleOrdenCompra","innerHTML",$objResponse->setTildes($html));
    return $objResponse;
}

function mostrar_detalle_orden_compra($numero_orden, $codigo_proveedor) {

    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");

    $Productos = $sql->ConsultarOC($numero_orden);
    $proveedor = $sql->ConsultarInformacionProveedor($codigo_proveedor);

    $est = "modulo_list_claro";
    $html .= "<table width=\"90%\"  class=\"modulo_table_list\" align=\"center\">";
    $html .= "  <tr align=\"CENTER\" class=\"formulacion_table_list\" >\n";
    $html .= "      <td    width=\"15%\">No. ORDEN DE COMPRA.</td>\n";
    $html .= "      <td   width=\"25%\">PROVEEDOR</td>\n";
    $html .= "  </tr>\n";
    $html .= "<tr  align=\"CENTER\" class=\"" . $est . "\" >\n";
    $html .= "  <td align=\"center\">{$numero_orden}</td>\n";
    $html .= "  <td align=\"center\">{$proveedor[0]['nombre_tercero']}</td>\n";
    $html .= " </tr>\n";
    $html .= "</table><br>\n";

    $html .= "  <table width=\"90%\" class=\"modulo_table_list_title\" border=\"0\"  align=\"center\">";
    $html .= "	  <tr  align=\" class=\"modulo_table_list_title\" >\n";
    $html .= "      <td >CODIGO PRODUCTO</td>\n";
    $html .= "      <td >DESCRIPCION</td>\n";
    $html .= "      <td >NUMERO UNIDADES</td>\n";
    $html .= "      <td >IVA</td>\n";
    $html .= "      <td >VALOR</td>\n";
    $html .= "  </tr>\n";

    foreach ($Productos as $k => $valor) {
        $html .= "  <tr class=\"modulo_list_claro\">\n";
        $html .= "      <td  align=\"center\">" . $valor['codigo_producto'] . " </td>\n";
        $html .= "      <td align=\"left\">" . $valor['descripcion'] . " " . $valor['presentacion'] . "-" . $valor['clase'] . "</td>\n";
        $html .= "      <td  align=\"center\">" . $valor['numero_unidades'] . " </td>\n";
        $html .= "      <td  align=\"center\">" . $valor['iva'] . " </td>\n";
        $html .= "      <td  align=\"center\">$" . FormatoValor($valor['valor'], 2) . " </td>\n";

        $Prod = $valor['descripcion'] . " " . $valor['presentacion'] . "-" . $valor['clase'];

        $subtotal = $subtotal + ($valor['valor'] * $valor['numero_unidades']);
        $iva = $iva + (($valor['valor'] * $valor['numero_unidades']) * ($valor['iva'] / 100));


        $html .= "  </tr>\n";
    }
    $html .= "  <tr >\n";
    $html .= "   <td>SubTotal : $" . FormatoValor($subtotal, 2) . "</td>";
    $html .= "  </tr>\n";
    $html .= "  <tr >\n";
    $html .= "   <td>Iva : $" . FormatoValor($iva, 2) . "</td>";
    $html .= "  </tr>\n";
    $html .= "  <tr >\n";
    $html .= "   <td>Total : $" . FormatoValor($subtotal + $iva, 2) . "</td>";
    $html .= "  </tr>\n";
    $html .= "	</table>\n";

    $objResponse->assign("Contenido", "innerHTML", $html);
    $objResponse->script("MostrarSpan();");

    return $objResponse;
}

/* * Funcion que permite seleccionar los diferentes productos que se pueden despachar a partir del medicamento formulado
 * @return Object $objResponse objeto de respuesta al formulario  	
 */
?>