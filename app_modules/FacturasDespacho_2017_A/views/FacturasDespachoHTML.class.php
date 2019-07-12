<?php

/**
 * @package IPSOFT-SIIS
 * @version $Id: FacturasDespachoHTML.class.php
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Mauricio Adrian Medina Santacruz
 */

/**
 * Clase Vista: FacturasDespachoHTML
 *
 * @package IPSOFT-SIIS
 * @version $Revision: 
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Mauricio Adrian Medina Santacruz
 */
class FacturasDespachoHTML {

    /**
     * Constructor de la clase
     */
    function FacturasDespachoHTML() {
        
    }

    /**
     * Funcion donde se crea la forma para el menu de Parametrizacion tiempo de cita
     *
     * @param array $action vector que contiene los link de la aplicacion
     * @return string $html retorna la cadena con el codigo html de la pagina
     */
    function formaMenu($action) {
        $html = ThemeAbrirTabla('FACTURAS DE DESPACHO');
        $html .= "<table width=\"40%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
        $html .= "  <tr class=\"modulo_table_list_title\">\n";
        $html .= "    <td align=\"center\">MENU\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr class=\"modulo_list_oscuro\">\n";
        $html .= "    <td align=\"center\">\n";
        $html .= "      <a href=\"" . $action['FacturaDespacho'] . "\" class=\"label_error\">FACTURA DE DESPACHO</a>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr class=\"modulo_list_oscuro\">\n";
        $html .= "    <td align=\"center\">\n";
        $html .= "      <a href=\"" . $action['FacturaDespachoGeneradas'] . "\" class=\"label_error\">FACTURA DE DESPACHO GENERADAS</a>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= "<br>\n";
        $html .= "<table align=\"center\">\n";
        $html .= "  <tr>\n";
        $html .= "    <td align=\"center\">\n";
        $html .= "      <a href=\"" . $action['volver'] . "\" class=\"label_error\">VOLVER</a>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= ThemeCerrarTabla();

        return $html;
    }

    // CREAR LA CAPITA
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
        $html .= "    catch(error){alert(error)}\n";
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

    /**
     * Funcion donde se crea la Forma del Contrato a llenar  
     * @param array $datos vector que contiene la informacion de Los Proveedores
     * @param array $action vector que contiene los link de la aplicacion
     * @param array $datos_empresa vector que contiene la informacion de Empresa que contrata al proveedor
     * @param string $fecha contiene la informacion de la fecha actual.
     * @return string $html retorna la cadena con el codigo html de la pagina
     */
    function Listado_TercerosDespachos($action, $datos, $Terceros_Clientes, $tipos_ids_terceros, $conteo, $pagina) {
        $html = ThemeAbrirTabla(' LISTADO - CLIENTE ');
        //Que solo aparezca Cuando NO hay un contrato activo y/o existente para ese tercero selecionado

        $ctl = AutoCarga::factory("ClaseUtil");
        $html .= $ctl->IsDate("-");
        $html .= $ctl->AcceptDate("-");
        $html .= $ctl->AcceptNum("-");
        $html .= $ctl->LimpiarCampos();
        $html .= $ctl->RollOverFilas();
        $html .="<div align=\"center\" class=\"label_error\" id='error'></div> ";
        $html .="<script>\n";
        $html .= "  function max(e){  ";
        $html .= "  tecla = (document.all) ? e.keyCode : e.which; ";
        $html .= "  if (tecla==8) return true;";
        $html .= "  if (tecla==13) return false;";
        $html .= " }";
        $html .= "</script>\n";
        $select .= "<select id=\"buscador[tipo_id_tercero]\" name=\"buscador[tipo_id_tercero]\" class=\"select\">";
        $select .= "<option value=\"\">-- TODOS --</option>";
        foreach ($tipos_ids_terceros as $k => $valor) {
            $select .= "<option value=\"" . $valor['tipo_id_tercero'] . "\">" . $valor['tipo_id_tercero'] . "</option>";
        }
        $select .= "</select>";
        $html .= "<form name=\"Buscador\" id=\"Buscador\" action=\"" . $action['buscador'] . "\" method=\"post\">";
        $html .= "<table  width=\"50%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
        $html .= "  <tr align=\"center\" >\n";
        $html .= "      <td  class=\"formulacion_table_list\">TIPO IDENTIFICACION</td>\n";
        $html .= "      <td  class=\"formulacion_table_list\">" . $select . "</td>\n";
        $html .= "      <td  class=\"formulacion_table_list\"># IDENTIFICACION</td>\n";
        $html .= "      <td  class=\"formulacion_table_list\"><input name=\"buscador[tercero_id]\" id=\"buscador[tercero_id]\" class=\"input-text\" style=\"width:100%\"></td>\n";
        $html .= "      <td  class=\"formulacion_table_list\">NOMBRE</td>\n";
        $html .= "      <td  class=\"formulacion_table_list\"><input name=\"buscador[nombre_tercero]\" id=\"buscador[nombre_tercero]\" class=\"input-text\" style=\"width:100%\"></td>\n";
        $html .= "	</tr>";
        $html .= "  <tr align=\"center\" >\n";
        $html .= "      <td  class=\"formulacion_table_list\" colspan=\"6\"><input type=\"submit\" value=\"BUSCAR TERCERO\" class=\"input-submit\"></td>\n";
        $html .= "	</tr>";
        $html .= "</table>";
        $html .= "</form>";

        $html .= "<br>";
        $pgn = AutoCarga::factory("ClaseHTML");
        $html .= "		" . $pgn->ObtenerPaginado($conteo, $pagina, $action['paginador']);
        $html .= "                  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "                    <tr class=\"modulo_table_list_title\">\n";
        $html .= "                      <td align=\"center\" width=\"2%\">\n";
        $html .= "                        <a title='IDENTIFICACION'>IDENTIFICACION</a>";
        $html .= "                      </td>\n";
        $html .= "                      <td align=\"center\" >\n";
        $html .= "                        <a title='CLIENTE'>NOMBRE DEL CLIENTE</a>";
        $html .= "                      </td>\n";
        $html .= "                      <td align=\"center\" >\n";
        $html .= "                        <a title=''>UBICACION<a>";
        $html .= "                      </td>\n";
        $html .= "                      <td align=\"center\" >\n";
        $html .= "                        <a title='DIRECCION'>DIRECCION";
        $html .= "                      </td>\n";
        $html .= "                      <td align=\"center\" >\n";
        $html .= "                        <a title='TELEFONO'>TELEFONO";
        $html .= "                      </td>\n";
        $html .= "                      <td align=\"center\" width=\"3%\">\n";
        $html .= "                        <a title='CREAR FACTURAS'>CONT..<a>";
        $html .= "                      </td>\n";
        $html .= "                    </tr>\n";
        foreach ($Terceros_Clientes as $key => $valor) {
            $html .= "                  <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
            $html .= "						<td align=\"center\">" . $valor['tipo_id_tercero'] . "-" . $valor['tercero_id'] . "</td>";
            $html .= "						<td>" . $valor['nombre_tercero'] . "</td>";
            $html .= "						<td>" . $valor['pais'] . "-" . $valor['departamento'] . "-" . $valor['municipio'] . "</b></td>";
            $html .= "						<td>" . $valor['direccion'] . "</b></td>";
            $html .= "						<td>" . $valor['telefono'] . "</b></td>";
            $html .= "						<td>";
            //$html .= "						<center><a href=\"" . ModuloGetURL('app', 'FacturasDespacho', 'controller', 'Crear_Facturas', array("tipo_id_tercero" => $valor['tipo_id_tercero'], "tercero_id" => $valor['tercero_id'])) . "\" class=\"label_error\"  title=\"CREAR FACTURAS AL CLIENTE\"><img src=\"" . GetThemePath() . "/images/pcargos.png\" border='0' ></a></center>\n";
            $html .= "						<center><a href=\"" . ModuloGetURL('app', 'FacturasDespacho', 'controller', 'tipo_factura', array("tipo_id_tercero" => $valor['tipo_id_tercero'], "tercero_id" => $valor['tercero_id'])) . "\" class=\"label_error\"  title=\"CREAR FACTURAS AL CLIENTE\"><img src=\"" . GetThemePath() . "/images/pcargos.png\" border='0' ></a></center>\n";
            $html .= "						</td>";
            $html .= "                   </tr>\n";
        }
        $html .= "				</table>";

        $html .= "<br>";


        $html .= "<table align=\"center\" width=\"50%\">\n";
        $html .= "  <tr>\n";
        $html .= "    <td align=\"center\">\n";
        $html .= "      <a href=\"" . $action['volver'] . "\"  class=\"label_error\">\n";
        $html .= "        VOLVER\n";
        $html .= "      </a>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";

        $html .= ThemeCerrarTabla();
        return $html;
    }

    /**
     * Funcion donde se crea la Forma del Contrato a llenar  
     * @param array $datos vector que contiene la informacion de Los Proveedores
     * @param array $action vector que contiene los link de la aplicacion
     * @param array $datos_empresa vector que contiene la informacion de Empresa que contrata al proveedor
     * @param string $fecha contiene la informacion de la fecha actual.
     * @return string $html retorna la cadena con el codigo html de la pagina
     */

    /**
     * Funcion donde se crea la Forma del Contrato a llenar  
     * @param array $datos vector que contiene la informacion de Los Proveedores
     * @param array $action vector que contiene los link de la aplicacion
     * @param array $datos_empresa vector que contiene la informacion de Empresa que contrata al proveedor
     * @param string $fecha contiene la informacion de la fecha actual.
     * @return string $html retorna la cadena con el codigo html de la pagina
     */
    function Listado_PedidosFacturar($action, $datos, $documento_id, $Tercero, $Pedidos, $conteo, $pagina) {
        $html = ThemeAbrirTabla(' PEDIDOS - CLIENTE ');
        //Que solo aparezca Cuando NO hay un contrato activo y/o existente para ese tercero selecionado
        $sql = AutoCarga::factory("FacturasDespachoSQL", "classes", "app", "FacturasDespacho");
        $ctl = AutoCarga::factory("ClaseUtil");
        $html .= $ctl->IsDate("-");
        $html .= $ctl->AcceptDate("-");
        $html .= $ctl->AcceptNum("-");
        $html .= $ctl->LimpiarCampos();
        $html .= $ctl->RollOverFilas();
        $html .="<div align=\"center\" class=\"label_error\" id='error'></div> ";
        $html .="<script >\n";
        $html .= "  function max(e){  ";
        $html .= "  tecla = (document.all) ? e.keyCode : e.which; ";
        $html .= "  if (tecla==8) return true;";
        $html .= "  if (tecla==13) return false;";
        $html .= " }";
        $html .= "</script>\n";
        $html .= "<script>";
        $html .= "
                    function Imprimir(direccion,empresa_id,prefijo,numero)
                    {
                    var url=direccion+\"?empresa_id=\"+empresa_id+\"&prefijo=\"+prefijo+\"&numero=\"+numero;
                    window.open(url,'','width=700,height=450,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no,menubar=yes,toolbar=yes');
                    }
                    
                    
        ";
        $html .= "</script>";
        $html .= "<br>";
        $html .= "<form name=\"Buscador\" id=\"Buscador\" action=\"" . $action['buscador'] . "\" method=\"post\">";
        $html .= "	<input type=\"hidden\" name=\"tipo_id_tercero\" id=\"tipo_id_tercero\" value=\"" . $_REQUEST['tipo_id_tercero'] . "\">";
        $html .= "	<input type=\"hidden\" name=\"tercero_id\" id=\"tercero_id\" value=\"" . $_REQUEST['tercero_id'] . "\">";
        $html .= "<table  width=\"50%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
        $html .= "  <tr align=\"center\" >\n";
        $html .= "      <td  class=\"formulacion_table_list\"># PEDIDO</td>\n";
        $html .= "      <td  class=\"formulacion_table_list\"><input name=\"buscador[pedido_cliente_id]\" id=\"buscador[pedido_cliente_id]\" class=\"input-text\" style=\"width:100%\"></td>\n";
        $html .= "      <td  class=\"formulacion_table_list\"><input type=\"submit\" value=\"BUSCAR PEDIDO\" class=\"input-submit\"></td>\n";
        $html .= "	</tr>";
        $html .= "</table>";
        $html .= "</form>";
        $disabled = "";
        if ($documento_id == "") {
            $html .= "	<center class=\"label_error\"><h2 >¡¡No Hay Documento Factura, Parametrizado En El Sistema Para La Empresa Seleccionada. Los Botones Permaneceran Des-Habilitados!!</h2></center>";
            $disabled = " disabled ";
        }
        $pgn = AutoCarga::factory("ClaseHTML");
        $html .= "		" . $pgn->ObtenerPaginado($conteo, $pagina, $action['paginador']);
        $html .= "                  <table width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "                    <tr class=\"modulo_table_list_title\">\n";
        $html .= "                      <td align=\"center\" width=\"2%\">\n";
        $html .= "                        <a title='DOCUMENTOS DE DEPACHOS POR PEDIDO'>PEDIDOS DESPACHADOS AL CLIENTE: " . $Tercero['tipo_id_tercero'] . "-" . $Tercero['tercero_id'] . ":" . $Tercero['nombre_tercero'] . "</a>";
        $html .= "                      </td>\n";
        $html .= "                    </tr>\n";
        $rpt = new GetReports();


        $html .= "";
        foreach ($Pedidos as $key => $valor) {
            $html .= "				<form name=\"" . $valor['pedido_cliente_id'] . "\" id=\"" . $valor['pedido_cliente_id'] . "\" method=\"POST\" action=\"" . $action['guardar'] . "\">";
            $html .= "                  <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
            $html .= "						<td width=\"100%\">";
            $html .= "							<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "                  			<tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
            $html .= "									<td>";
            $html .= "										<UL>";
            $html .= "											<LI>";
            $html .= "											<b>PEDIDO #</b>" . $valor['pedido_cliente_id'] . " <b>Vendedor:</b>(" . $valor['tipo_id_vendedor'] . " " . $valor['vendedor_id'] . "-" . $valor['nombre'] . ") <b>Fecha:</b>(" . $valor['fecha_registro'] . ")";
            $html .= "											</LI>";
            $html .= "										</UL>";
            $html .= "									</td>";
            $html .= "									<td>";
            $html .= "										<input type=\"hidden\" name=\"vendedor_id\" id=\"vendedor_id\" value=\"" . $valor['vendedor_id'] . "\">";
            $html .= "										<input type=\"hidden\" name=\"tipo_id_vendedor\" id=\"tipo_id_vendedor\" value=\"" . $valor['tipo_id_vendedor'] . "\">";
            $html .= "										<input type=\"hidden\" name=\"tipo_id_tercero\" id=\"tipo_id_tercero\" value=\"" . $valor['tipo_id_tercero'] . "\">";
            $html .= "										<input type=\"hidden\" name=\"tercero_id\" id=\"tercero_id\" value=\"" . $valor['tercero_id'] . "\">";
            $html .= "										<input type=\"hidden\" name=\"pedido_cliente_id\" id=\"pedido_cliente_id\" value=\"" . $valor['pedido_cliente_id'] . "\">";
            $html .= $rpt->GetJavaReport('app', 'ContratacionProductosCliente', 'reporte_pedido_cliente', $valor, array('rpt_name' => '', 'rpt_dir' => 'cache', 'rpt_rewrite' => TRUE));
            $fnc = $rpt->GetJavaFunction();
            $html .= "										<center>\n";
            $html .= "										<a title=\"IMPRIMIR\" class=\"label_error\" href=\"javascript:" . $fnc . "\">\n";
            $html .= "	  									<image title=\"IMPRIMIR PEDIDO\" src=\"" . GetThemePath() . "/images/imprimir.png\" border=\"0\">\n";
            $html .= "  									</a>\n";
            $html .= "										</center>\n";
            $html .= "									</td>";
            $html .= "									<td>";
            $html .= "									</td>";
            $html .= "								</tr>";
            $Documentos = $sql->DocumentosDespacho($datos['empresa_id'], $valor['pedido_cliente_id']);
            $i = 0;
            foreach ($Documentos as $key => $v) {
                $html .= "                  			<tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
                $html .= "									<td>";
                $html .= "										<UL>";
                $html .= "												<UL>";
                $html .= "													<LI>";
                $html .= "														DOCUMENTO: <b>" . $v['prefijo'] . "-" . $v['numero'] . "</b>";
                $html .= "													</LI>";
                $html .= "												</UL>";
                $html .= "										</UL>";
                $html .= "									</td>";
                $html .= "									<td align=\"center\">";
                $direccion = "app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/E008/imprimir/imprimir_docE008.php";
                $imagen = GetThemePath() . "/images/imprimir.png";
                $alt = "IMPRIMIR DOCUMENTO";
                $x = $this->RetornarImpresionDoc($direccion, $alt, $imagen, $v['empresa_id'], $v['prefijo'], $v['numero']);
                $html .= "                     				" . $x . "";
                $html .= "									</td>";
                $html .= "									<td align=\"center\">";
                $html .= "									<input type=\"checkbox\" name=\"" . $i . "\" id=\"" . $i . "\" class=\"input-checkbox\" value=\"" . $v['empresa_id'] . "/" . $v['prefijo'] . "/" . $v['numero'] . "\">";
                $html .= "									</td>";
                $html .= "								</tr>";
                $i++;
            }
            $html .= "                  			<tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
            $html .= "									<td colspan=\"3\" align=\"center\">";
            $html .= "									<input type=\"hidden\" name=\"cantidad_registros\" id=\"cantidad_registros\" value=\"" . $i . "\">";
            $html .= "									<input type=\"submit\" " . $disabled . " value=\"GENERAR FACTURA DE PEDIDO. #" . $valor['pedido_cliente_id'] . "\" class=\"input-submit\">";
            $html .= "									<td>";
            $html .= "                  			</tr>\n";
            $html .= "							</table>";
            $html .= "						</td>";
            $html .= "					</tr>";
            $html .= "				</form>";
        }
        $html .= "				</table>";

        $html .= "<br>";


        $html .= "<table align=\"center\" width=\"50%\">\n";
        $html .= "  <tr>\n";
        $html .= "    <td align=\"center\">\n";
        $html .= "      <a href=\"" . $action['volver'] . "\"  class=\"label_error\">\n";
        $html .= "        VOLVER\n";
        $html .= "      </a>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";

        $html .= ThemeCerrarTabla();
        $html .= $this->CrearVentana(800, "PEDIDOS CLIENTE");
        return $html;
    }

    /**
     * Funcion donde se crea la forma para mostrar los productos realizar los documentos de salida de productos
     * 
     * @param array $action vector que contiene los link de la aplicacion
     * @param array $empresa_id vector que contiene los datos
     * @param array $datos vector que contiene los datos de las facturas
     * @return string $html retorna la cadena con el codigo html de la pagina
     */
    function formaListaFacturasGeneradas($action, $empresa_id, $conteo, $pagina, $datos) {
        $html = ThemeAbrirTabla('FACTURAS DE DESPACHO GENERADAS');

        $reporte = new GetReports();

        $html .= "<form name=\"formListaFacturasGenera\" id=\"formListaFacturasGenera\" method=\"post\" action=\"\">\n";
        $html .= "<table width=\"95%\" class=\"modulo_table_list_title\" border=\"1\"  align=\"center\">";
        $html .= "	<tr align=\" class=\"modulo_table_list_title\" >\n";
        $html .= "    <td width=\"15%\">PREFIJO </td>\n";
        $html .= "    <td width=\"15%\">EMPRESA </td>\n";
        $html .= "    <td width=\"15%\">TIPO DOCUMENTO </td>\n";
        $html .= "    <td width=\"25%\">DOCUMENTO </td>\n";
        $html .= "    <td  width=\"25%\" >NOMBRE CLIENTE </td>\n";
        $html .= "    <td width=\"18%\">NUMERO FACTURA </td>\n";
        $html .= "  </tr>\n";
        $est = "modulo_list_claro";
        $back = "#DDDDDD";

        $html .= "<form name=\"formProductosDP\" id=\"formProductosDP\" method=\"post\" action=\"\">\n";

        foreach ($datos as $key => $valor) {

            $mostrar = $reporte->GetJavaReport('app', 'FacturasDespacho', 'ReporteFacturaDespacho', array('numero_factura' => $valor['numero'], 'tecero_id' => $valor['tipo_id_tercero'], 'nombre_tercero' => $valor['nombre_tercero'], 'documento' => $valor['tercero_id']), array('rpt_name' => '', 'rpt_dir' => 'cache', 'rpt_rewrite' => TRUE));
            $funcion = $reporte->GetJavaFunction();
            //$html .= "<pre>".print_r($valor['numero'],true)."</pre>";
            $html .= "  <tr class=\"modulo_list_claro\">\n";
            $html .= "      <td align=\"center\">" . $valor['prefijo'] . "</td>\n";
            $html .= "      <td align=\"center\">" . $valor['empresa_id'] . "</td>\n";
            $html .= "      <td align=\"center\">" . $valor['tipo_id_tercero'] . "</td>\n";
            $html .= "      <td align=\"center\">" . $valor['tercero_id'] . "</td>\n";
            $html .= "      <td align=\"left\">" . $valor['nombre_tercero'] . "</td>\n";
            $html .= "      <td align=\"left\">" . $valor['factura'] . "</td>\n";
            $html .= "      <td align=\"center\" >\n";
            $html .= "          " . $mostrar . "\n";
            $html .= "	      <a href=\"javascript:$funcion\" class=\"label_error\"><sub><img src=\"" . GetThemePath() . "/images/imprimir.png\" border=\"0\" width=\"17\" height=\"17\" title=\"GENERAR REPORTE \"></sub>&nbsp;</a>\n";
            $html .= "      </td>\n";
            $html .= "  </tr>\n";
        }
        $html .= "</form>";
        $html .= "</table>";
        $html .= "</form>";
        $html .= "<br>\n";
        $html .= "<table align=\"center\">\n";
        $html .= "  <tr>\n";
        $html .= "    <td align=\"center\">\n";
        $html .= "      <a href=\"" . $action['volver'] . "\" class=\"label_error\">VOLVER</a>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";

        $html .= $this->CrearVentana(600, "MENSAJE");
        $html .= ThemeCerrarTabla();
        return $html;
    }

    /**
     * Funcion donde se crea la forma de mensaje
     *
     * @param array $action vector que contiene los link de la aplicacion
     * @param var $mensaje variable que contiene el mensaje
     * @return string $html retorna la cadena con el codigo html de la pagina
     */
    function formaMensajeInTc($action, $mensaje) {
        $html = ThemeAbrirTabla('MENSAJE', 500);
        $html .= "<table class=\"modulo_table_list\"align=\"center\">\n ";
        $html .= "  <tr>\n";
        $html .= "    <td> " . $mensaje . " </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= "<table align=\"center\">";
        $html .= "  <tr>\n";
        $html .= "    <td>\n";
        $html .= "      <a href=\"" . $action['volver'] . "\" class=\"label_error\">VOLVER</a>\n";
        $html .= "    </td>";
        $html .= "  </tr>";
        $html .= "</table>";
        $html .= ThemeCerrarTabla();

        return $html;
    }

    function RetornarImpresionDoc($direccion, $alt, $imagen, $empresa_id, $prefijo, $numero) {
        global $VISTA;
        $imagen1 = "<img src=\"" . $imagen . "\" border=\"0\" >\n";
        $salida1 = "<a title='" . $alt . "' href=javascript:Imprimir('$direccion','$empresa_id','$prefijo','$numero')>" . $imagen1 . "</a>";
        return $salida1;
    }

    /**
     * Funcion donde se crea la Forma del Contrato a llenar  
     * @param array $datos vector que contiene la informacion de Los Proveedores
     * @param array $action vector que contiene los link de la aplicacion
     * @param array $datos_empresa vector que contiene la informacion de Empresa que contrata al proveedor
     * @param string $fecha contiene la informacion de la fecha actual.
     * @return string $html retorna la cadena con el codigo html de la pagina
     */
    function Listado_FacturasGeneradas($action, $datos, $facturas_cliente, $tipos_ids_terceros, $Prefijos_Facturas, $conteo, $pagina) {
        $html = ThemeAbrirTabla(' FACTURAS - CLIENTE ');
        //Que solo aparezca Cuando NO hay un contrato activo y/o existente para ese tercero selecionado

        $ctl = AutoCarga::factory("ClaseUtil");
        $html .= $ctl->IsDate("-");
        $html .= $ctl->AcceptDate("-");
        $html .= $ctl->AcceptNum("-");
        $html .= $ctl->LimpiarCampos();
        $html .= $ctl->RollOverFilas();
        $html .="<div align=\"center\" class=\"label_error\" id='error'></div> ";
        $html .="<script>\n";
        $html .= "  function max(e){  ";
        $html .= "  tecla = (document.all) ? e.keyCode : e.which; ";
        $html .= "  if (tecla==8) return true;";
        $html .= "  if (tecla==13) return false;";
        $html .= " }";
        $html .= "
                    function confirmar_sincronizacion(empresa_id, prefijo, numero_factura){
                        if(confirm('Desea Sincronizar La Factura ['+prefijo+'-'+numero_factura+'] ')){
                            xajax_sincronizar_facturas_pendientes_ws_fi(empresa_id, prefijo, numero_factura);
                            return false;
                        }else{                                
                            return false;
                        }
                    }


                 ";
        $html .= "</script>\n";
        $select .= "<select id=\"buscador[tipo_id_tercero]\" name=\"buscador[tipo_id_tercero]\" class=\"select\">";
        $select .= "<option value=\"\">-- TODOS --</option>";
        foreach ($tipos_ids_terceros as $k => $valor) {
            $select .= "<option value=\"" . $valor['tipo_id_tercero'] . "\">" . $valor['tipo_id_tercero'] . "</option>";
        }
        $select .= "</select>";

        $select_prefijos .= "<select id=\"buscador[prefijo]\" name=\"buscador[prefijo]\" class=\"select\">";
        $select_prefijos .= "<option value=\"\">-- TODOS --</option>";
        foreach ($Prefijos_Facturas as $key => $v) {
            $select_prefijos .= "<option value=\"" . $v['prefijo'] . "\">" . $v['prefijo'] . "</option>";
        }
        $select_prefijos .= "</select>";
        $html .= "<form name=\"Buscador\" id=\"Buscador\" action=\"" . $action['buscador'] . "\" method=\"post\">";
        $html .= "<table  width=\"85%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
        $html .= "  <tr align=\"center\" >\n";
        $html .= "      <td  class=\"formulacion_table_list\"># PEDIDO</td>\n";
        $html .= "      <td  class=\"formulacion_table_list\"><input name=\"buscador[pedido_cliente_id]\" id=\"buscador[pedido_cliente_id]\" class=\"input-text\" style=\"width:100%\"></td>\n";
        $html .= "      <td  class=\"formulacion_table_list\">PREFIJO FACTURA</td>\n";
        $html .= "      <td  class=\"formulacion_table_list\">" . $select_prefijos . "</td>\n";
        $html .= "      <td  class=\"formulacion_table_list\"># DE FACTURA</td>\n";
        $html .= "      <td  class=\"formulacion_table_list\"><input name=\"buscador[numero]\" id=\"buscador[numero]\" class=\"input-text\" style=\"width:100%\"></td>\n";
        $html .= "      <td  class=\"formulacion_table_list\">TIPO.ID</td>\n";
        $html .= "      <td  class=\"formulacion_table_list\">" . $select . "</td>\n";
        $html .= "      <td  class=\"formulacion_table_list\">#IDENTIFICACION</td>\n";
        $html .= "      <td  class=\"formulacion_table_list\"><input name=\"buscador[tercero_id]\" id=\"buscador[tercero_id]\" class=\"input-text\" style=\"width:100%\"></td>\n";
        $html .= "      <td  class=\"formulacion_table_list\">NOMBRE</td>\n";
        $html .= "      <td  class=\"formulacion_table_list\"><input name=\"buscador[nombre_tercero]\" id=\"buscador[nombre_tercero]\" class=\"input-text\" style=\"width:100%\"></td>\n";
        $html .= "	</tr>";
        $html .= "  <tr align=\"center\" >\n";
        $html .= "      <td  class=\"formulacion_table_list\" colspan=\"12\"><input type=\"submit\" value=\"BUSCAR\" class=\"input-submit\"></td>\n";
        $html .= "	</tr>";
        $html .= "</table>";
        $html .= "</form>";

        $html .= "<br>";

        $mensaje_ws = $_REQUEST['resultado_ws']['mensaje_ws'];
        $mensaje_bd = $_REQUEST['resultado_ws']['mensaje_bd'];

        if (!empty($mensaje_ws) || !empty($mensaje_bd)) {
            $html_aux .= "<center><p><label class=\"\"><b>Factura Registrada.</b></label></p>";
            $html_aux .= "  <p><label class=\"label_error\">Resultado Sincronizacion</label></p>";
            $html_aux .= "  <p><label class=\"label_error\">Respuesta Ws: </label>{$mensaje_ws}</p>";
            $html_aux .= "  <p><label class=\"label_error\">Respuesta BD: </label>{$mensaje_bd}</p>";
            $html_aux .= "</center>";
            $html .= $html_aux;
        }

        $pgn = AutoCarga::factory("ClaseHTML");
        $html .= "		" . $pgn->ObtenerPaginado($conteo, $pagina, $action['paginador']);
        $html .= "                  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "                    <tr class=\"modulo_table_list_title\">\n";
        $html .= "                      <td align=\"center\" width=\"2%\">\n";
        $html .= "                        <a title='NUMERO DE FACTURA'>#FACTURA</a>";
        $html .= "                      </td>\n";
        $html .= "                      <td align=\"center\" width=\"2%\">\n";
        $html .= "                        <a title='IDENTIFICACION'>IDENTIFICACION</a>";
        $html .= "                      </td>\n";
        $html .= "                      <td align=\"center\" >\n";
        $html .= "                        <a title='CLIENTE'>NOMBRE DEL CLIENTE</a>";
        $html .= "                      </td>\n";
        $html .= "                      <td align=\"center\" >\n";
        $html .= "                        <a title=''>UBICACION<a>";
        $html .= "                      </td>\n";
        $html .= "                      <td align=\"center\" >\n";
        $html .= "                        <a title='TELEFONO'>TELEFONO";
        $html .= "                      </td>\n";
        $html .= "                      <td align=\"center\" >\n";
        $html .= "                        <a title='VENDEDOR'>VENDEDOR";
        $html .= "                      </td>\n";
        $html .= "                      <td align=\"center\" >\n";
        $html .= "                        <a title='FECHA'>FECHA FACTURA";
        $html .= "                      </td>\n";
        $html .= "                      <td align=\"center\" >\n";
        $html .= "                        <a title='FECHA VENCIMIENTO - FACTURA'>FECHA VENC.";
        $html .= "                      </td>\n";
        $html .= "                      <td align=\"center\" >\n";
        $html .= "                        <a title='VALOR TOTAL DE LA FACTURA'>VALOR.";
        $html .= "                      </td>\n";
        $html .= "                      <td align=\"center\" >\n";
        $html .= "                        <a title='SALDO'>SALDO.";
        $html .= "                      </td>\n";

        /*
          $html .= "			           <td>\n";
          $html .= "				          <a title='OBSER'>OBSER.";
          $html .= "			           </td>\n";
         */
        $html .= "                      <td align=\"center\" width=\"3%\">\n";
        $html .= "                        <a title='IMPRIMIR FACTURA DEL CLIENTE'>IMP<a>";
        $html .= "                      </td>\n";
        $html .= "                      <td align=\"center\" width=\"3%\">\n";
        $html .= "                        <a title='SINCRONIZADO DUSOFT FI'>SINCRONIZADO DUSOFT FI<a>";
        $html .= "                      </td>\n";
        $html .= "                      <td align=\"center\" width=\"3%\">\n";
        $html .= "                        <a title='SINCRONIZAR CON FI'>OP<a>";
        $html .= "                      </td>\n";
        $html .= "                    </tr>\n";
        $rpt = new GetReports();
        foreach ($facturas_cliente as $key => $valor) {
            $html .= "                  <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
            $html .= "						<td align=\"center\">" . $valor['prefijo'] . "-" . $valor['factura_fiscal'] . "</td>";
            $html .= "						<td align=\"center\">" . $valor['tipo_id_tercero'] . "-" . $valor['tercero_id'] . "</td>";
            $html .= "						<td>" . $valor['nombre_tercero'] . "</td>";
            $html .= "						<td>" . $valor['ubicacion'] . ": " . $valor['direccion'] . "</td>";
            $html .= "						<td>" . $valor['telefono'] . "</b></td>";
            $html .= "						<td>" . $valor['tipo_id_vendedor'] . "-" . $valor['vendedor_id'] . ": " . $valor['nombre'] . "</td>";
            $html .= "						<td>" . $valor['fecha_registro'] . "</td>";
            $html .= "						<td>" . $valor['fecha_vencimiento_factura'] . "</td>";
            $html .= "						<td>$" . FormatoValor($valor['valor_total'], 2) . "</td>";
            $html .= "						<td>$" . FormatoValor($valor['saldo'], 2) . "</td>";
            /* $html .= "						<td>".$valor['observacion']."</td>"; */
            $html .= "						<td>";
            $html .= $rpt->GetJavaReport('app', 'FacturasDespacho', 'facturas_cliente', $valor, array('rpt_name' => '', 'rpt_dir' => 'cache', 'rpt_rewrite' => TRUE));
            $fnc = $rpt->GetJavaFunction();
            $html .= "										<center>\n";
            $html .= "										<a title=\"IMPRIMIR\" class=\"label_error\" href=\"javascript:" . $fnc . "\">\n";
            $html .= "	  									<image title=\"IMPRIMIR FACTURA DEL CLIENTE\" src=\"" . GetThemePath() . "/images/imprimir.png\" border=\"0\">\n";
            $html .= "  									</a>\n";
            $html .= "										</center>\n";
            $html .= "						</td>";

            $class_error = 'label_error';
            if ($valor['estado'] == '0')
                $class_error = '';

            $html .= "			<td align=\"center\" class='{$class_error}'>{$valor['descripcion_estado']}</td>\n";


            $url_images = GetThemePath() . "/images/desconectado.png";
            $funcion_sincronizar = "confirmar_sincronizacion('{$valor['empresa_id']}', '{$valor['prefijo']}', '{$valor['factura_fiscal']}');";
            $fn_tmp = $funcion_sincronizar;
            if ($valor['estado'] == '0') {
                $url_images = GetThemePath() . "/images/conectado.png";
                $funcion_sincronizar = "alert('La Factura ya esta Sincronizada');";
            }

            $html .= "			<td align=\"center\" ><a href='#' onclick=\"{$funcion_sincronizar}\"><img title=\"SINCRONIZAR CON FI\" src='{$url_images}' border=\"0\"></a>
                                        <a href='#' onclick=\"{$fn_tmp}\">..</a>
                                        </td>\n";

            $html .= "                   </tr>\n";
        }
        $html .= "				</table>";

        $html .= "<br>";


        $html .= "<table align=\"center\" width=\"50%\">\n";
        $html .= "  <tr>\n";
        $html .= "    <td align=\"center\">\n";
        $html .= "      <a href=\"" . $action['volver'] . "\"  class=\"label_error\">\n";
        $html .= "        VOLVER\n";
        $html .= "      </a>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";

        $html .= ThemeCerrarTabla();
        return $html;
    }

    function tipo_factura($action) {

        $html = ThemeAbrirTabla('TIPO GENERACION DE FACTURA');
        $html .= "<table width=\"40%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
        $html .= "  <tr class=\"modulo_table_list_title\">\n";
        $html .= "    <td align=\"center\">MENU\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr class=\"modulo_list_oscuro\">\n";
        $html .= "    <td align=\"center\">\n";
        $html .= "      <a href='{$action['facturacion_individual']}' class=\"label_error\">FACTURACION INDIVIDUAL</a>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr class=\"modulo_list_oscuro\">\n";
        $html .= "    <td align=\"center\">\n";
        $html .= "      <a href='{$action['facturacion_agrupada']}' class=\"label_error\">FACTURACION AGRUPADA</a>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= "<br>\n";
        $html .= "<table align=\"center\">\n";
        $html .= "  <tr>\n";
        $html .= "    <td align=\"center\">\n";
        $html .= "      <a href='{$action['volver']}' class=\"label_error\">VOLVER</a>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= ThemeCerrarTabla();

        return $html;
    }

    function facturacion_agrupada($datos_cliente, $lista_pedidos_cliente, $datos_empresa, $documento_id, $action) {

        //var_dump($datos_cliente);
        $url_server = GetBaseURL();
        $html .= "
            <script>
                
                var documentos =[];
                
                function consultar_lista_pedidos(){
                    var numero_pedido = document.getElementById('numero_pedido').value;
                    
                    xajax_consultar_pedidos_clientes('{$datos_empresa['empresa_id']}',{tipo_id_tercero : '{$datos_cliente['tipo_id_tercero']}', tercero_id:'{$datos_cliente['tercero_id']}', nombre_tercero:'{$datos_cliente['nombre_tercero']}'}, numero_pedido, documentos);
                }
                
                
                
                function administrar_checkbox(cb){
                
                    var index = documentos.indexOf(cb.value);
                    if(cb.checked){
                        documentos.push(cb.value);
                    }else{
                        documentos.splice(index, 1);
                    }                    
                }
                
                function generar_facturacion_agrupada(){
                
                    if(documentos.length == 0){
                        alert('POR FAVOR SELECCIONES LOS DOCUMENTOS A DESPACHAR!!!')
                    }else{
                        if(documentos.length > 1)
                            xajax_enviar_documentos_agrupados('{$datos_cliente['tipo_id_tercero']}', '{$datos_cliente['tercero_id']}', documentos);
                        else
                            alert('DEBE SELECCIONAR MAS DE UN DOCUMENTO PARA GENERAR FACTURAS AGRUPADAS.!!!');
                    }
                }
                
                function imprimir_pedido(parametros){
                
                    var nombre=''
                    var width='400'
                    var height='300'
                    var winX=Math.round(screen.width/2)-(width/2);
                    var winY=Math.round(screen.height/2)-(height/2);
                    var nombre='Printer_Mananger';
                    var str ='width='+width+',height='+height+',left='+winX+',top='+winY+',resizable=no,status=no,scrollbars=yes,location=no';                                      
                    var url ='{$url_server}/printer.php?tipo=app&modulo=ContratacionProductosCliente&reporte=reporte_pedido_cliente&'+parametros+'&opciones[rpt_name]=&opciones[rpt_dir]=cache&opciones[rpt_rewrite]=1';
                       
                    window.open(url, nombre, str).focus();
                }
                
                function imprimir_documento_despacho(empresa_id, prefijo, numero){
                    var url = 'app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/E008/imprimir/imprimir_docE008.php';
                    var url_reporte=url+\"?empresa_id=\"+empresa_id+\"&prefijo=\"+prefijo+\"&numero=\"+numero;
                    window.open(url_reporte,'','width=700,height=450,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no,menubar=yes,toolbar=yes');
                }
            
            </script>
        ";

        $html .= "<table  width=\"50%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
        $html .= "  <tr align=\"center\" >\n";
        $html .= "      <td  class=\"formulacion_table_list\"># PEDIDO</td>\n";
        $html .= "      <td  class=\"formulacion_table_list\"><input name=\"buscador[pedido_cliente_id]\" id=\"numero_pedido\" class=\"input-text\" style=\"width:100%\"></td>\n";
        $html .= "      <td  class=\"formulacion_table_list\"><input type=\"button\" value=\"BUSCAR PEDIDO\" class=\"input-submit\" onclick='consultar_lista_pedidos();'  ></td>\n";
        $html .= "  </tr>";
        $html .= "</table><br/><br/>";

        $html .= "<center><input type=\"button\" value=\"GENERAR FACTURACION AGRUPADA \" class=\"input-submit\" onclick='generar_facturacion_agrupada();' ></center><br/>";
        $html .= "<div id='lista_pedidos'>
                    <script>consultar_lista_pedidos();</script>
                  </div>";
        return $html;
    }

}

?>