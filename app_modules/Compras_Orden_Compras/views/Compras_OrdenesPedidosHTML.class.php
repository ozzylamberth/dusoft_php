<?php

/**
 * @package IPSOFT-SIIS
 * @version $Id: Compras_Orden_ComprasHTML.class.php,v 1.0 
 * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Sandra Viviana Pantoja Torres
 */
class Compras_OrdenesPedidosHTML {

    /**
     * Constructor de la clase
     */
    function Compras_OrdenesPedidosHTML() {
        
    }

    /*
     * Funcion donde se crea una Forma con una Ventana con capas para mostrar informacion
     * en pantalla
     * @param int $tmn Tamaño que tendra la ventana
     * @return string
     */

    /*
     * Funcion donde se crea la forma para el documento de pedido por orden de compra
     * @param array $action vector que contiene los link de la aplicacion
     * @return string $html retorna la cadena con el codigo html de la pagina
     */

    function FormaOC($action, $InfoProveedor, $unidades_negocio, $Compras, $productos_invalidos) {

        $codigo_proveedor = $InfoProveedor[0]['codigo_proveedor_id'];

        $ctl = AutoCarga::factory("ClaseUtil");
        $_ROOT = GetBaseURL();
        $html .= $ctl->LimpiarCampos();
        $html .= "<script>";
        $html .= "function AdicionarProducto(empresa_id,codigo_producto,Descripcion,iva,costo_ultima_compra,cantidad,presentacion)";
        $html .= "{";
        $html .= "	document.getElementById('codigo_producto').value=codigo_producto;";
        $html .= "	document.getElementById('DescripcionProducto').innerHTML=Descripcion;";
        $html .= "	document.getElementById('porc_iva').value=iva;";
        $html .= "	document.getElementById('valor').value=costo_ultima_compra;";
        $html .= "	document.getElementById('cantidad').value=cantidad;";
        $html .= "	document.getElementById('presentacion').innerHTML=presentacion;";
        $html .= "	OcultarSpan();";
        $html .= "}";
        $html .= "</script>";

        $html .= "<script>";
        $html .= "function QuitarProducto()";
        $html .= "{";
        $html .= "	document.getElementById('codigo_producto').value='';";
        $html .= "	document.getElementById('DescripcionProducto').innerHTML='';";
        $html .= "	document.getElementById('numero_unidades').value='';";
        $html .= "	document.getElementById('valor').value='';";
        $html .= "	document.getElementById('porc_iva').value='';";
        $html .= "	document.getElementById('cantidad_presentacion').value='';";
        $html .= "	document.getElementById('cantidad').value='';";
        $html .= "	document.getElementById('valor_total').value='';";
        $html .= "	document.getElementById('presentacion').innerHTML='';";
        $html .= "}";
        $html .= "</script>";

        $html .= "<script>";
        $html .= "function Calcular()";
        $html .= "{";
        $html .= "	var valor_unitario=0.0;";
        $html .= "	cantidad_presentacion=parseInt(document.getElementById('cantidad_presentacion').value);";
        $html .= "	cantidad=parseInt(document.getElementById('cantidad').value);";
        $html .= "	valor_total=parseFloat(document.getElementById('valor_total').value);";
        $html .= "	document.getElementById('valor').value=((cantidad_presentacion*valor_total)/(cantidad_presentacion*cantidad));";
        $html .= "	document.getElementById('numero_unidades').value=(cantidad_presentacion*cantidad);";
        $html .= "}";
        $html .= "</script>";

        $html .= "<script>"; /* ModificacionDetalleOC($CodigoProducto,$Descripcion,$Concentracion,$Empresa_Id,$ClaseId,$SubclaseId,$orden_pedido_id,$offset) */
        $html .= "function Paginador(CodigoProducto,Descripcion,Concentracion,Empresa_Id,ClaseId,Subclase_id,offset)";
        $html .= "{";
        $html .= "	xajax_BuscarProductos(CodigoProducto,Descripcion,Concentracion,Empresa_Id,ClaseId,Subclase_id,offset);";
        $html .= "}";
        $html .= "</script>";


        $select .= "	<select name=\"unidad_negocio[codigo_unidad_negocio]\" class=\"select\">\n";
        $select .= "		<option value=\"\">-- NINGUNO --</option>";
        foreach ($unidades_negocio as $k => $v) {
            if ($v['codigo_unidad_negocio'] == $Compras[0]['codigo_unidad_negocio'])
                $selected = " selected ";
            else
                $selected = " ";
            $select .= "		<option " . $selected . " value=\"" . $v['codigo_unidad_negocio'] . "\">" . $v['descripcion'] . "</option>";
        }
        $select .= "	</select>";

        $html .= ThemeAbrirTabla('DOCUMENTO DE ORDEN DE COMPRA ');
        $html .= "<form name=\"Forma18\" id=\"Forma18\"  method=\"post\" >\n";
        $html .= "                 <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "                   <tr class=\"modulo_table_list_title\">\n";
        $html .= "                      <td align=\"center\">\n";
        $html .= "                         IDENTIFICACION ";
        $html .= "                       </td>\n";
        $html .= "                      <td align=\"left\" class=\"modulo_list_claro\">\n";
        $html .= "                        " . $InfoProveedor[0]['tipo_id_tercero'] . " " . $InfoProveedor[0]['tercero_id'] . " ";
        $html .= "                       </td>\n";
        $html .= "                     <td align=\"center\">\n";
        $html .= "                        <a title='farmacia'>PROVEEDOR:<a>";
        $html .= "                      </td>\n";
        $html .= "                       <td align=\"left\" class=\"modulo_list_claro\" class=\"label_mark\">\n";
        $html .= "                          " . $InfoProveedor[0]['nombre_tercero'];
        $html .= "                       </td>\n";
        $html .= "                     <tr>\n";
        $html .= "</table>\n";
        $html .= "<br>\n";

        $html .= "<center>";
        $html .= "	<fieldset class=\"modulo_table_list\" style=\"width:60%\">";
        $html .= "		<legend>UNIDAD DE NEGOCIO</legend>";
        $html .= "	<form name=\"Forma_UnidadNegocio\" id=\"Forma_UnidadNegocio\" action=\"" . $action['GuardarUnidad'] . "\" method=\"POST\">";
        $html .= "		<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $html .= "      	<tr class=\"modulo_table_list_title\">\n";
        $html .= "				<td colspan=\"2\">";
        $html .= "				UNIDAD DE NEGOCIO";
        $html .= "				</td>";
        $html .= "      	</tr>\n";
        $html .= "      	<tr class=\"modulo_list_claro\">";
        $html .= "				<td>";
        $html .= "					<b>SELECCIONAR UNIDAD DE NEGOCIO:</b>";
        $html .= "				</td>";
        $html .= "				<td>";
        $html .= "					" . $select;
        $html .= "				</td>";
        $html .= "      	</tr>";
        $html .= "      	<tr class=\"modulo_list_claro\">";
        $html .= "				<td colspan=\"2\" align=\"center\">";
        $html .= "				<input type=\"submit\" value=\"GUARDAR UNIDAD DE NEGOCIO\" class=\"input-submit\">";
        $html .= "				</td>";
        $html .= "      	</tr>";
        $html .= "      	<tr class=\"modulo_table_list_title\">\n";
        $html .= "				<td colspan=\"2\">";
        $html .= "					ACTUALMENTE:";
        $html .= "				</td>";
        $html .= "      	<tr class=\"modulo_list_claro\">";
        $html .= "				<td colspan=\"2\" align=\"center\">";
        if ($Compras[0]['codigo_unidad_negocio'] == "")
            $html .= "					<img src=\"" . $_ROOT . "/images/logocliente.png\" border='0'>";
        else
            $html .= "					<img src=\"" . $_ROOT . "/images/" . $Compras[0]['imagen'] . "\" border='0'>";
        $html .= "				</td>";
        $html .= "			</tr>";
        $html .= "		</table>";
        $html .= "	</form>";
        $html .= "	</fieldset>";
        $html .= "</center>";

        $html .= "<br>\n";
        $html .= "                 <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "                   <tr class=\"modulo_table_list_title\">\n";
        $html .= "                      <td align=\"center\">\n";
        $html .= "                         SE CREO LA ORDEN DE PEDIDOS No " . $_REQUEST['orden_pedido_id'] . "  ";
        $html .= "                       </td>\n";
        $html .= "                     <tr>\n";

        $html .= "</table>\n";


        //================================ Cargar Archivo Plano ======================================================================
        $html .= "<br> ";
        $html .= "<center>\n";
        $html .= "<fieldset width=\"95%\" class=\"fieldset\" style=\"width:60%\">\n";
        $html .= "<legend class=\"normal_10AN\" align=\"center\">SUBIR ARCHIVO PLANO</legend>\n";
        $html .= "<form name=\"cargar_archivo_plano\" id=\"cargar_archivo_plano\" enctype=\"multipart/form-data\" action=\"{$action['buscador']}\" method = \"post\">\n";
        $html .= "  <table   width=\"100%\" align=\"center\" border=\"0\" class=\"modulo_table_list\"    >";
        $html .= "      <tr class=\"formulacion_table_list\">\n";
        $html .= "          <td width=\"40%\"  align=\"left\">CARGAR ARCHIVO PLANO:</td>\n";
        $html .= "          <td  colspan=\"5\" class=\"modulo_list_oscuro\" align=\"left\"> <input type=\"file\"   class=\"input-text\" name=\"archivo_plano\" id=\"archivo_plano\" size=\"75\"> <input class=\"input-submit\" name=\"enviar\" type=\"submit\" onclick=\"javascript:validar_subida_archivo(document.getElementById('archivo_plano'))\" value=\"Subir...\" /></td>\n";
        $html .= "          <input name=\"accion\" id=\"accion\" type=\"hidden\" value='subir_archivo' />";
        $html .= "          <input name='orden_pedido_id' id='orden_pedido_id' type=\"hidden\" value='{$_REQUEST['orden_pedido_id']}' />";
        $html .= "          <input name='empresa_id' id='empresa_id' type=\"hidden\" value='{$_REQUEST['empresa_id']}' />";
        $html .= "          <input name='codigoproveedorid' id='codigoproveedorid' type=\"hidden\" value='{$codigo_proveedor}' />";
        $html .= "         <br><center class=\"label_error\">{$mensaje_error}</center><br>\n";
        $html .= "      </tr>\n";
        $html .= "  </table>\n";
        $html .= "</form>\n";
        $html .= "</fieldset>\n";
        $html .= "</center>\n";
        $html .= "<br> ";
        //================================ Fin Cargar Archivo Plano ====================================================================

        if (count($productos_invalidos) > 0) {
            $html .= "<center>\n";
            $html .= "<h3 class='label_error'> Los siguientes códigos no fueron cargados </h3>";
            $html .= "  <a href='{$action['descargar_archivo']}'  target= 'blank' class=\"label_error\">\n";
            $html .= "      DESCARGAR ARCHIVO CODIGOS NO INCLUIDOS \n";
            $html .= "  </a>\n";
            $html .= "</center>\n";
        }

        $html .= "<br>";
        /* 	item_id 	orden_pedido_id 	codigo_producto 	numero_unidades 	valor 	porc_iva 	estado 	acta_autorizacion 	numero_unidades_recibidas 	lote_temp 	fecha_vencimiento_temp 	preorden_detalle_id 	valor_unitario 	valor_unitario_factura 	cantidad_devuelta */
        $html .= "                 <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "				  <tr>";

        $html .= "				  <tr>";
        $html .= "				 	 <td>";
        $html .= "				      <b>PRODUCTO:</b>";
        $html .= "				  	 </td>";
        $html .= "				 	 <td>";
        $html .= "				      <a onclick=\"xajax_SeleccionDeProductos('" . $_REQUEST['empresa_id'] . "', '{$codigo_proveedor}')\">";
        $html .="<img title=\"SELECCIONAR PRODUCTOS\" src=\"" . GetThemePath() . "/images/producto.png\" border=\"0\"></a>\n";
        $html .= "				  	 </td>";
        $html .= "				  </tr>";

        $html .= "				  <tr>";
        $html .= "				 	 <td>";
        $html .= "				      <b>Codigo Producto:</b>";
        $html .= "				  	 </td>";
        $html .= "				 	 <td>";
        $html .= "				      <input id=\"codigo_producto\" type=\"text\" class=\"input-text\" readonly>";
        $html .= "				      <input id=\"cantidad_productosOC\" type=\"hidden\" >";
        $html .= "				  	 </td>";
        $html .= "				  </tr>";

        $html .= "				  <tr>";
        $html .= "				 	 <td>";
        $html .= "				      <b>Descripcion:</b>";
        $html .= "				  	 </td>";
        $html .= "				 	 <td>";
        $html .= "				      <div id=\"DescripcionProducto\" class=\"label_error\"></div>";
        $html .= "				  	 </td>";
        $html .= "				  </tr>";

        $html .= "				  <tr>";
        $html .= "				 	 <td>";
        $html .= "				      <b>IVA:</b>";
        $html .= "				  	 </td>";
        $html .= "				 	 <td>";
        $html .= "				      <input id=\"porc_iva\" type=\"text\" class=\"input-text\" >";
        $html .= "				  	 </td>";
        $html .= "				  </tr>";

        /* NUEVO PARA ORDENES DE COMPRA, CALCULO DE VALORES UNITARIOS, SEGUN PRESENTACION */
        $html .= "				  	<tr>";
        $html .= "						<td colspan=\"2\">";
        $html .= "							<table width=\"100%\" align=\"center\" class=\"modulo_table_list\" rules=\"all\">";
        $html .= "								<tr>";
        $html .= "									<td class=\"modulo_table_list_title\" colspan=\"6\">";
        $html .= "										CALCULO DE VALORES UNITARIOS POR PRESENTACION";
        $html .= "									</td>";
        $html .= "								</tr>";
        $html .= "								<tr class=\"modulo_list_claro\">";
        $html .= "									<td class=\"modulo_table_list_title\">";
        $html .= "										CANT.";
        $html .= "									</td>";
        $html .= "									<td class=\"modulo_list_claro\">";
        $html .= "										<input type=\"text\" name=\"cantidad_presentacion\" id=\"cantidad_presentacion\" class=\"input-text\" size=\"20%\"> ";
        $html .= "									</td>";
        $html .= "									<td id=\"presentacion\" class=\"label_error\">";
        $html .= "									</td>";
        $html .= "									<td class=\"modulo_list_claro\">";
        $html .= "										<input type=\"text\" name=\"cantidad\" id=\"cantidad\" class=\"input-text\" style=\"width:60%\"><b>Unids.</b> ";
        $html .= "									</td>";
        $html .= "									<td class=\"modulo_table_list_title\">";
        $html .= "										VALOR PRES.";
        $html .= "									</td>";
        $html .= "									<td class=\"modulo_list_claro\">";
        $html .= "										<input type=\"text\" name=\"valor_total\" id=\"valor_total\" class=\"input-text\" style=\"width:100%\"> ";
        $html .= "									</td>";
        $html .= "								</tr>";
        $html .= "								<tr>";
        $html .= "									<td class=\"modulo_table_list_title\" colspan=\"6\">";
        $html .= "										<input type=\"button\" value=\"CALCULAR\" class=\"input-submit\" style=\"width:50%\" onclick=\"Calcular();\">";
        $html .= "									</td>";
        $html .= "								</tr>";

        $html .= "							</table>";
        $html .= "						</td>";
        $html .= "				  	</tr>";
        /* FIN */
        $html .= "				  <tr>";
        $html .= "				 	 <td>";
        $html .= "				      <b>NUMERO DE UNIDADES:</b>";
        $html .= "				  	 </td>";
        $html .= "				 	 <td>";
        $html .= "				      <input type=\"text\" class=\"input-text\" id=\"numero_unidades\">";
        $html .= "				  	 </td>";
        $html .= "				  </tr>";


        $html .= "				 	 <td>";
        $html .= "				      <b>PRECIO DE COMPRA:</b>";
        $html .= "				  	 </td>";
        $html .= "				 	 <td>";
        $html .= "				      <input type=\"text\" class=\"input-text\" id=\"valor\">";
        $html .= "				  	 </td>";
        $html .= "				  </tr>";


        $html .= "				  <tr>";
        $html .= "				 	 <td align=\"center\" colspan=\"2\">";
        $java = "'" . $_REQUEST['orden_pedido_id'] . "','" . $_REQUEST['empresa_id'] . "',document.getElementById('codigo_producto').value,document.getElementById('numero_unidades').value,document.getElementById('valor').value,document.getElementById('porc_iva').value";

        $html .= "				      <input type=\"button\" onclick=\"xajax_AgregarItemOC(" . $java . ")\"  class=\"modulo_table_list\" value=\"Adicionar\">";
        $html .= "				  	 </td>";
        $html .= "				  </tr>";

        $html .= "					</table>";

        $html .= "<br>";

        $html .= "<div id=\"DetalleOC\"></div>";

        //href=\"".$action['volver']."\"
        $html .= "<table  align=\"center\" width=\"50%\">\n";
        $html .= "  <tr>\n";
        $html .= "    <td align=\"center\">\n";
        //Solo se mostrará en caso de que tenga Productos Asociados
        $html .= "		<div id=\"link_confirmar\"></div>";

        $html .= "    </td>\n";
        $html .= "    <td align=\"center\">\n";
        $html .= "      <a onclick=\"xajax_ConfirmarOC('" . $_REQUEST['orden_pedido_id'] . "','" . $_REQUEST['empresa_id'] . "',document.getElementById('cantidad_productosOC').value,'2');\" class=\"label_error\">\n";
        $html .= "       [[::ELIMINAR DOCUMENTO::]] \n";
        $html .= "      </a>\n";
        $html .= "    </td>\n";

        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= "</form>";
        $html .= ThemeCerrarTabla();
        $html .= $this->CrearVentana(800, "SELECCIONE PRODUCTO");

        $html .="<script>";
        $html .="xajax_DetalleOC('" . $_REQUEST['orden_pedido_id'] . "');";
        $html .="</script>";

        return $html;
    }

    /*
     * Funcion donde se crea la forma para editar el pedido por orden de compra
     * @param array $action vector que contiene los link de la aplicacion
     * @return string $html retorna la cadena con el codigo html de la pagina
     */

    function FormaEOC($action, $orden_compra, $empresa_id, $mensaje, $codigo_proveedor_id) {
        
        if (!empty($mensaje)) {
            
            $html = ThemeAbrirTabla("MENSAJE");
            $html .= " <form name=\"form8\" method=\"post\" enctype=\"multipart/form-data\" >";
            $html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
            $html .= "	<tr>\n";
            $html .= "		<td>\n";
            $html .= "		  <table width=\"100%\" class=\"modulo_table_list\">\n";
            $html .= "		    <tr class=\"normal_10AN\">\n";
            $html .= "		      <td align=\"center\">\n" . $mensaje . "</td>\n";
            $html .= "		    </tr>\n";
            $html .= "		  </table>\n";
            $html .= "		</td>\n";
            $html .= "	</tr>\n";
            $html .= "</table>";
            $html .= " <br>";
            $html .= "<table align=\"center\" width=\"50%\">\n";
            $html .= "  <tr>\n";
            $html .= "    <td align=\"center\">\n";
            $html .= "      <a href=\"" . $action['volver'] . "\"  class=\"label_error\">\n";
            $html .= "       VOLVER \n";
            $html .= "      </a>\n";
            $html .= "    </td>\n";
            $html .= "  </tr>\n";
            $html .= "</table>\n";
            $html .= "</form>";
            $html .= ThemeCerrarTabla();
        } else {
            
            $ctl = AutoCarga::factory("ClaseUtil");
            $_ROOT = GetBaseURL();
            $html .= $ctl->LimpiarCampos();
            
            $html .= "<script>";
            $html .= "function AdicionarProducto(empresa_id,codigo_producto,Descripcion,iva,costo_ultima_compra)";
            $html .= "{";
            $html .= "	document.getElementById('codigo_producto').value=codigo_producto;";
            $html .= "	document.getElementById('DescripcionProducto').innerHTML=Descripcion;";
            $html .= "	document.getElementById('porc_iva').value=iva;";
            $html .= "	document.getElementById('valor').value=costo_ultima_compra;";
            $html .= "	OcultarSpan();";
            $html .= "}";
            $html .= "</script>";

            $html .= "<script>";
            $html .= "function QuitarProducto()";
            $html .= "{";
            $html .= "	document.getElementById('codigo_producto').value='';";
            $html .= "	document.getElementById('DescripcionProducto').innerHTML='';";
            $html .= "	document.getElementById('numero_unidades').value='';";
            $html .= "	document.getElementById('valor').value='';";
            $html .= "	document.getElementById('porc_iva').value='';";
            $html .= "}";
            $html .= "</script>";
            $html .= "<script>";
            $html .= "function Calcular()";
            $html .= "{";
            $html .= "	var valor_unitario=0.0;";
            $html .= "	cantidad_presentacion=parseInt(document.getElementById('cantidad_presentacion').value);";
            $html .= "	cantidad=parseInt(document.getElementById('cantidad').value);";
            $html .= "	valor_total=parseFloat(document.getElementById('valor_total').value);";
            $html .= "	document.getElementById('valor').value=((cantidad_presentacion*valor_total)/(cantidad_presentacion*cantidad));";
            $html .= "	document.getElementById('numero_unidades').value=(cantidad_presentacion*cantidad);";
            $html .= "}";
            $html .= "</script>";
            $html .= "<script>";
            $html .= "function Paginador(CodigoProducto,Descripcion,Concentracion,Empresa_Id,ClaseId,Subclase_id,offset)";
            $html .= "{";
            $html .= "	xajax_BuscarProductos(CodigoProducto,Descripcion,Concentracion,Empresa_Id,ClaseId,Subclase_id,offset);";
            $html .= "}";
            $html .= "</script>";

            $html .= ThemeAbrirTabla('DOCUMENTO DE ORDEN DE COMPRA ');
            $html .= "                 <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "                   <tr class=\"modulo_table_list_title\">\n";
            $html .= "                      <td align=\"center\">\n";
            $html .= "                         ORDEN DE PEDIDOS No " . $orden_compra . "  ";
            $html .= "                       </td>\n";
            $html .= "                     <tr>\n";
            $html .= "</table>\n";
            $html .= "<br>";
            //	item_id 	orden_pedido_id 	codigo_producto 	numero_unidades 	valor 	porc_iva 	estado 	acta_autorizacion 	numero_unidades_recibidas 	lote_temp 	fecha_vencimiento_temp 	preorden_detalle_id 	valor_unitario 	valor_unitario_factura 	cantidad_devuelta
            $html .= "                 <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "				  <tr>";
            $html .= "				  <tr>";
            $html .= "				 	 <td>";
            $html .= "				      <b>PRODUCTO:</b>";
            $html .= "				  	 </td>";
            $html .= "				 	 <td>";
            $html .= "				      <a onclick=\"xajax_SeleccionDeProductos('" . $empresa_id . "','{$codigo_proveedor_id}')\">";
            $html .="<img title=\"SELECCIONAR PRODUCTOS\" src=\"" . GetThemePath() . "/images/producto.png\" border=\"0\"></a>\n";
            $html .= "				  	 </td>";
            $html .= "				  </tr>";
            $html .= "				  <tr>";
            $html .= "				 	 <td>";
            $html .= "				      <b>Codigo Producto:</b>";
            $html .= "				  	 </td>";
            $html .= "				 	 <td>";
            $html .= "				      <input id=\"codigo_producto\" type=\"text\" class=\"input-text\" readonly>";
            $html .= "				      <input id=\"cantidad_productosOC\" type=\"hidden\" >";
            $html .= "				  	 </td>";
            $html .= "				  </tr>";
            $html .= "				  <tr>";
            $html .= "				 	 <td>";
            $html .= "				      <b>Descripcion:</b>";
            $html .= "				  	 </td>";
            $html .= "				 	 <td>";
            $html .= "				      <div id=\"DescripcionProducto\" class=\"label_error\"></div>";
            $html .= "				  	 </td>";
            $html .= "				  </tr>";
            $html .= "				  <tr>";
            $html .= "				 	 <td>";
            $html .= "				      <b>IVA:</b>";
            $html .= "				  	 </td>";
            $html .= "				 	 <td>";
            $html .= "				      <input id=\"porc_iva\" type=\"text\" class=\"input-text\" >";
            $html .= "				  	 </td>";
            $html .= "				  </tr>";

            /* NUEVO PARA ORDENES DE COMPRA, CALCULO DE VALORES UNITARIOS, SEGUN PRESENTACION */
            $html .= "				  	<tr>";
            $html .= "						<td colspan=\"2\">";
            $html .= "							<table width=\"100%\" align=\"center\" class=\"modulo_table_list\" rules=\"all\">";
            $html .= "								<tr>";
            $html .= "									<td class=\"modulo_table_list_title\" colspan=\"6\">";
            $html .= "										CALCULO DE VALORES UNITARIOS POR PRESENTACION";
            $html .= "									</td>";
            $html .= "								</tr>";
            $html .= "								<tr class=\"modulo_list_claro\">";
            $html .= "									<td class=\"modulo_table_list_title\">";
            $html .= "										CANT.";
            $html .= "									</td>";
            $html .= "									<td class=\"modulo_list_claro\">";
            $html .= "										<input type=\"text\" name=\"cantidad_presentacion\" id=\"cantidad_presentacion\" class=\"input-text\" size=\"20%\"> ";
            $html .= "									</td>";
            $html .= "									<td id=\"presentacion\" class=\"label_error\">";
            $html .= "									</td>";
            $html .= "									<td class=\"modulo_list_claro\">";
            $html .= "										<input type=\"text\" name=\"cantidad\" id=\"cantidad\" class=\"input-text\" style=\"width:60%\"><b>Unids.</b> ";
            $html .= "									</td>";
            $html .= "									<td class=\"modulo_table_list_title\">";
            $html .= "										VALOR PRES.";
            $html .= "									</td>";
            $html .= "									<td class=\"modulo_list_claro\">";
            $html .= "										<input type=\"text\" name=\"valor_total\" id=\"valor_total\" class=\"input-text\" style=\"width:100%\"> ";
            $html .= "									</td>";
            $html .= "								</tr>";
            $html .= "								<tr>";
            $html .= "									<td class=\"modulo_table_list_title\" colspan=\"6\">";
            $html .= "										<input type=\"button\" value=\"CALCULAR\" class=\"input-submit\" style=\"width:50%\" onclick=\"Calcular();\">";
            $html .= "									</td>";
            $html .= "								</tr>";
            $html .= "							</table>";
            $html .= "						</td>";
            $html .= "				  	</tr>";
            /* FIN */
            $html .= "				  <tr>";
            $html .= "				 	 <td>";
            $html .= "				      <b>NUMERO DE UNIDADES:</b>";
            $html .= "				  	 </td>";
            $html .= "				 	 <td>";
            $html .= "				      <input type=\"text\" class=\"input-text\" id=\"numero_unidades\">";
            $html .= "				  	 </td>";
            $html .= "				  </tr>";
            $html .= "				 	 <td>";
            $html .= "				      <b>PRECIO DE COMPRA:</b>";
            $html .= "				  	 </td>";
            $html .= "				 	 <td>";
            $html .= "				      <input type=\"text\" class=\"input-text\" id=\"valor\">";
            $html .= "				  	 </td>";
            $html .= "				  </tr>";
            $html .= "				  <tr>";
            $html .= "				 	 <td align=\"center\" colspan=\"2\">";
            //$java = "'" . $_REQUEST['orden_pedido_id'] . "','" . $_REQUEST['empresa_id'] . "',document.getElementById('codigo_producto').value,document.getElementById('numero_unidades').value,document.getElementById('valor').value,document.getElementById('porc_iva').value";
            $java = "'" . $orden_compra . "','" . $empresa_id . "',document.getElementById('codigo_producto').value,document.getElementById('numero_unidades').value,document.getElementById('valor').value,document.getElementById('porc_iva').value";
            $html .= "				      <input type=\"button\" onclick=\"xajax_AgregarItemOCEdicion(" . $java . ")\"  class=\"modulo_table_list\" value=\"Adicionar\">";
            $html .= "				  	 </td>";
            $html .= "				  </tr>";
            $html .= "					</table>";
            $html .= "<br>";

            $html .= "<div id=\"DetalleOC\"></div>";

            $html .= "<table align=\"center\">\n";
            $html .= "<br>";
            $html .= "  <tr>\n";
            $html .= "      <td align=\"center\" class=\"label_error\">\n";
            $html .= "        <a href=\"" . $action['volver'] . "\">VOLVER</a>\n";
            $html .= "      </td>\n";
            $html .= "  </tr>\n";
            $html .= "</table>\n";
            $html .= ThemeCerrarTabla();
            $html .= $this->CrearVentana(800, "SELECCIONE PRODUCTO");

            $html .="<script>";
            //$html .="xajax_DetalleOC('" . $_REQUEST['orden_pedido_id'] . "');";
            $html .="xajax_DetalleOCEdicion('" . $orden_compra . "');";
            $html .="</script>";
        }
        return $html;
    }

    function CrearVentana($tmn, $Titulo) {

        $html .= "<script>\n";
        $html .= "  var contenedor = 'Contenedor';\n";
        $html .= "  var titulo = 'titulo';\n";
        $html .= "  var hiZ = 4;\n";
        $html .= "  function OcultarSpan()\n";
        $html .= "  { \n";
        $html .= "    try\n";
        $html .= "    {\n";
        $html .= "      e = xGetElementById('Contenedor');\n";
        $html .= "      e.style.display = \"none\";\n";
        $html .= "    }\n";
        $html .= "    catch(error){}\n";
        $html .= "  }\n";
        //Mostrar Span
        $html .= "  function MostrarSpan()\n";
        $html .= "  { \n";
        $html .= "    try\n";
        $html .= "    {\n";
        $html .= "      e = xGetElementById('Contenedor');\n";
        $html .= "      e.style.display = \"\";\n";
        $html .= "      Iniciar();\n";
        $html .= "    }\n";
        $html .= "    catch(error){alert(\"vaya\"+error)}\n";
        $html .= "  }\n";

        $html .= "  function MostrarTitle(Seccion)\n";
        $html .= "  {\n";
        $html .= "    xShow(Seccion);\n";
        $html .= "  }\n";
        $html .= "  function OcultarTitle(Seccion)\n";
        $html .= "  {\n";
        $html .= "    xHide(Seccion);\n";
        $html .= "  }\n";

        $html .= "  function Iniciar()\n";
        $html .= "  {\n";
        $html .= "    contenedor = 'Contenedor';\n";
        $html .= "    titulo = 'titulo';\n";
        $html .= "    ele = xGetElementById('Contenido');\n";
        $html .= "    xResizeTo(ele," . $tmn . ", 'auto');\n";
        $html .= "    ele = xGetElementById(contenedor);\n";
        $html .= "    xResizeTo(ele," . $tmn . ", 'auto');\n";
        $html .= "    xMoveTo(ele, xClientWidth()/4, xScrollTop()+20);\n";
        $html .= "    ele = xGetElementById(titulo);\n";
        $html .= "    xResizeTo(ele," . ($tmn - 20) . ", 20);\n";
        $html .= "    xMoveTo(ele, 0, 0);\n";
        $html .= "    xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
        $html .= "    ele = xGetElementById('cerrar');\n";
        $html .= "    xResizeTo(ele,20, 20);\n";
        $html .= "    xMoveTo(ele," . ($tmn - 20) . ", 0);\n";
        $html .= "  }\n";

        $html .= "  function myOnDragStart(ele, mx, my)\n";
        $html .= "  {\n";
        $html .= "    window.status = '';\n";
        $html .= "    if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
        $html .= "    else xZIndex(ele, hiZ++);\n";
        $html .= "    ele.myTotalMX = 0;\n";
        $html .= "    ele.myTotalMY = 0;\n";
        $html .= "  }\n";
        $html .= "  function myOnDrag(ele, mdx, mdy)\n";
        $html .= "  {\n";
        $html .= "    if (ele.id == titulo) {\n";
        $html .= "      xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
        $html .= "    }\n";
        $html .= "    else {\n";
        $html .= "      xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
        $html .= "    }  \n";
        $html .= "    ele.myTotalMX += mdx;\n";
        $html .= "    ele.myTotalMY += mdy;\n";
        $html .= "  }\n";
        $html .= "  function myOnDragEnd(ele, mx, my)\n";
        $html .= "  {\n";
        $html .= "  }\n";


        $html.= "function Cerrar(Elemento)\n";
        $html.= "{\n";
        $html.= "    capita = xGetElementById(Elemento);\n";
        $html.= "    capita.style.display = \"none\";\n";
        $html.= "}\n";

        $html .= "</script>\n";
        $html .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:4\">\n";
        $html .= "  <div id='titulo' class='draggable' style=\" text-transform: uppercase;text-align:center;\">" . $Titulo . "</div>\n";
        $html .= "  <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
        $html .= "  <div id='Contenido' class='d2Content'>\n";
        //En ese espacio se visualiza la informacion extraida de la base de datos.
        $html .= "  </div>\n";
        $html .= "</div>\n";


        return $html;
    }

    function subir_plano_rotacion($action, $numeros_ordenes,$productos_invalidos) {

        
        $ctl = AutoCarga::factory("ClaseUtil");
        
        $sql = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");

        $html = ThemeAbrirTabla("SUBIR PLANO ROTACION PARA ORDEN DE COMPRA");


        //================================ Cargar Archivo Plano ======================================================================
        $html .= "<br> ";
        $html .= "<center>\n";
        $html .= "<fieldset width=\"95%\" class=\"fieldset\" style=\"width:60%\">\n";
        $html .= "<legend class=\"normal_10AN\" align=\"center\">SUBIR ARCHIVO PLANO</legend>\n";
        $html .= "<form name=\"cargar_archivo_plano\" id=\"cargar_archivo_plano\" enctype=\"multipart/form-data\" action=\"{$action['subir_plano']}\" method = \"post\">\n";
        $html .= "  <table   width=\"100%\" align=\"center\" border=\"0\" class=\"modulo_table_list\"    >";
        $html .= "      <tr class=\"formulacion_table_list\">\n";
        $html .= "          <td width=\"40%\"  align=\"left\">CARGAR ARCHIVO PLANO:</td>\n";
        $html .= "          <td  colspan=\"5\" class=\"modulo_list_oscuro\" align=\"left\"> <input type=\"file\"   class=\"input-text\" name=\"archivo_plano\" id=\"archivo_plano\" size=\"75\"> <input class=\"input-submit\" name=\"enviar\" type=\"submit\" onclick=\"javascript:validar_subida_archivo(document.getElementById('archivo_plano'))\" value=\"Subir...\" /></td>\n";
        $html .= "          <input name=\"accion\" id=\"accion\" type=\"hidden\" value='subir_archivo' />";
        $html .= "          <input name='empresa_id' id='empresa_id' type=\"hidden\" value='{$_REQUEST['empresa_id']}' />";
        $html .= "      </tr>\n";
        $html .= "  </table>\n";
        $html .= "</form>\n";
        $html .= "</fieldset>\n";
        $html .= "</center>\n";
        $html .= "<br> ";
        //================================ Fin Cargar Archivo Plano ====================================================================
        //================================ Resultado  ====================================================================

        if (count($productos_invalidos) > 0) {
            $html .= "<center>\n";
            $html .= "<h3 class='label_error'> Los siguientes códigos no fueron cargados </h3>";
            $html .= "  <a href='{$action['descargar_archivo']}'  target= 'blank' class=\"label_error\">\n";
            $html .= "      DESCARGAR ARCHIVO CODIGOS NO INCLUIDOS \n";
            $html .= "  </a>\n";
            $html .= "</center>\n";
        }

        $html .= "<br>";
        
        if (count($numeros_ordenes) > 0) {

            $html .= "<table width=\"50%\"  class=\"modulo_table_list\" align=\"center\">";
            $html .= "  <tr align=\"CENTER\" class=\"formulacion_table_list\" >\n";
            $html .= "      <td    width=\"15%\">No. ORDEN DE COMPRA.</td>\n";
            $html .= "      <td   width=\"25%\">PROVEEDOR</td>\n";
            $html .= "      <td   width=\"5%\">OPCION</td>\n";
            $html .= "  </tr>\n";

            $est = "modulo_list_claro";

            foreach ($numeros_ordenes as $key => $dtl) {

                $proveedor = $sql->ConsultarInformacionProveedor($dtl['codigo_proveedor']);

                $html .= "<tr  align=\"CENTER\" class=\"" . $est . "\" >\n";
                $html .= "  <td align=\"center\">{$dtl['numero_orden']}</td>\n";
                $html .= "  <td align=\"center\">{$proveedor[0]['nombre_tercero']}</td>\n";                
                $html .= "  <td>";
                $html .= "	<a onclick='xajax_mostrar_detalle_orden_compra({$dtl['numero_orden']}, {$dtl['codigo_proveedor']})' >";
                $html .="       <img title=\"SELECCIONAR PRODUCTOS\" src=\"" . GetThemePath() . "/images/producto.png\" border=\"0\"></a>\n";
                $html .= "  </td>";                
                $html .= " </tr>\n";
            }
            $html .= "</table><br>\n";
        }

        $html .= "<table align=\"center\" width=\"50%\">\n";
        $html .= "  <tr>\n";
        $html .= "    <td align=\"center\">\n";
        $html .= "      <a href=\"" . $action['volver'] . "\"  class=\"label_error\">\n";
        $html .= "       VOLVER \n";
        $html .= "      </a>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= "</form>";
        $html .= $this->CrearVentana(800, "DETALLE ORDEN DE COMPRA");
        $html .= ThemeCerrarTabla();

        return $html;
    }

}

?>