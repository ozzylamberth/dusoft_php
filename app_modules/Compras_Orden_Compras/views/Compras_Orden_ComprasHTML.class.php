<?php

/**
 * @package IPSOFT-SIIS
 * @version $Id: Compras_Orden_ComprasHTML.class.php,v 1.0 
 * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Sandra Viviana Pantoja Torres
 */
IncludeClass("ClaseHTML");
IncludeClass("ClaseUtil");

class Compras_Orden_ComprasHTML {

    /**
     * Constructor de la clase
     */
    function Compras_Orden_ComprasHTML()
    {
        
    }

    /*
     * Funcion donde se crea la forma para el menu la Rotacion Productos
     * @param array $action vector que contiene los link de la aplicacion
     * @return string $html retorna la cadena con el codigo html de la pagina

     */

    function FormaMenu($action, $EmpresaId)
    {

        $html = ThemeAbrirTabla('COMPRAS');
        $html .= "    <script>";

        $html .= "    function paginador(empresapedido,razon_social,offset) ";
        $html .= "    {";
        $html .= "    xajax_Proveedores(empresapedido,razon_social,offset);";
        $html .= "    }";
        $html .= "    function editar_orden_compra() ";
        $html .= "    {";
        $html .= "      e = document.getElementById('edicion_orden_compra');";
        $html .= "      e.style.display = \"block\";";
        $html .= "    }";
        $html .= "    </script>";
        $html .= "<table width=\"60%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
        $html .= "  <tr class=\"modulo_table_list_title\">\n";
        $html .= "     <td align=\"center\">MENU\n";
        $html .= "     </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr  class=\"modulo_list_oscuro\">\n";
        $html .= "      <td   class=\"label\" align=\"center\">\n";
        $html .= "        <a href=\"" . $action['div'] . "\">GENERAR PREORDENES-ORDENES</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        /* $html .= "  <tr  class=\"modulo_list_oscuro\">\n";
          $html .= "      <td   class=\"label\" align=\"center\">\n";
          $html .= "        <a href=\"".$action['Pre-orden']."\">BUSCAR PRE-ORDEN</a>\n";
          $html .= "      </td>\n";
          $html .= "  </tr>\n"; */
        $html .= "  <tr  class=\"modulo_list_oscuro\">\n";
        $html .= "      <td   class=\"label\" align=\"center\">\n";
        $html .= "        <a href=\"" . $action['BuscarPre'] . "\">CONSULTAR ORDEN DE COMPRAS</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr  class=\"modulo_list_oscuro\">\n";
        $html .= "      <td   class=\"label\" align=\"center\">\n";
        $html .= "        <a href=\"" . $action['unificarcompras'] . "\">UNIFICAR ORDENES DE COMPRAS POR PROVEEDOR</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr  class=\"modulo_list_oscuro\">\n";
        $html .= "      <td align=\"center\">\n";
        $html .= "         <a href=\"#\" onclick=\"xajax_EmpresaOrdenPedido()\"  class=\"label_error\">CREAR DOCUMENTO POR PRODUCTOS PENDIENTES(ORDEN-COMPRA)</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";

        $html .= "  <tr  class=\"modulo_list_oscuro\">\n";
        $html .= "    <td align=\"center\">\n";
        $html .= "      <a href=\"#\" onclick=\"xajax_TProveedores('" . $EmpresaId . "');\" class=\"label_error\">CREAR DOCUMENTO DE COMPRA (Sin Rotacion)</a>\n";
        $html .= "		  <div id=\"TercerosProveedores\"></div>";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";$html .= "  <tr  class=\"modulo_list_oscuro\">\n";
        $html .= "      <td align=\"center\">\n";
        $html .= "         <a href=\"" . $action['novedadesordenescompra'] . "\"  class=\"label_error\">NOVEDADES ORDENES COMPRA</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        
        $html .= "  <tr  class=\"modulo_list_oscuro\">\n";
        $html .= "      <td align=\"center\">\n";
        //$html .= "         <a href=\"" . $action['editarordenescompra'] . "\"  class=\"label_error\">EDITAR ORDENES COMPRA</a>\n";
        $html .= "      <a href=\"#\" onclick=\"editar_orden_compra();\" class=\"label_error\">EDITAR ORDENES COMPRA</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        
        //$html .= "    <tr id='Contenedor' class='modulo_list_oscuro\' style=\"display:none;z-index:4\">\n";
        $html .= "  <tr id='edicion_orden_compra' class=\"modulo_list_oscuro\" style=\"display:none\">\n";
        //$html .= "    <tr id='edicion_orden_compra' class=\"modulo_list_oscuro\">\n";
        $html .= "      <td align=\"center\">\n";
        $html .= "          <form name=\"Forma_Orden_Compra\" id=\"Forma_Orden_Compra\" action=\"" . $action['editarordenescompra'] . "\" method=\"POST\">";
        $html .= "              <strong>\n";
        $html .= "                  Orden de Compra\n";
        $html .= "                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
        $html .= "                  <input type=\"text\" class=\"input-text\" name=\"orden_compra\" id=\"orden_compra\">\n";
        $html .= "                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
        $html .= "                  <input type=\"submit\" class=\"input-submit\" value=\"Editar Orden Compra\">\n";
        $html .= "              </strong>\n";
        $html .= "          </form>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        
        $html .= "  <tr  class=\"modulo_list_oscuro\">\n";
        $html .= "      <td   class=\"label\" align=\"center\">\n";
        $html .= "        <a href=\"" . $action['consultarauditoriasordenescompra'] . "\">CONSULTAR AUDITORIA ORDEN DE COMPRAS</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        
        $html .= "  <tr  class=\"modulo_list_oscuro\">\n";
        $html .= "      <td   class=\"label\" align=\"center\">\n";
        $html .= "        <a href=\"" . $action['subir_plano_rotacion'] . "\">SUBIR PLANO ROTACION PARA OC.</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        
        $html .= "</table>\n";

        $html .= "<table align=\"center\">\n";
        $html .= "<br>";
        $html .= "  <tr>\n";
        $html .= "      <td align=\"center\" class=\"label_error\">\n";
        $html .= "        <a href=\"" . $action['volver'] . "\">VOLVER</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= $this->CrearVentana(650, "PROVEEDORES");
        $html .= ThemeCerrarTabla();
        return $html;
    }

    /*
     * Funcion donde se crea una Forma con una Ventana con capas para mostrar informacion
     * en pantalla
     * @param int $tmn Tamaño que tendra la ventana
     * @return string
     */

    function CrearVentana($tmn, $Titulo)
    {
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
        $html .= "  </div>\n";
        $html .= "</div>\n";
        $html .= "</script>\n";
        $html .= "<div id='Contenedor2' class='d2Container' style=\"display:none;z-index:4\">\n";
        $html .= "  <div id='titulo2' class='draggable' style=\" text-transform: uppercase;text-align:center;\">" . $Titulo . "</div>\n";
        $html .= "  <div id='cerrar2' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
        $html .= "  <div id='Contenido2' class='d2Content'>\n";
        $html .= "  </div>\n";
        $html .= "</div>\n";
        return $html;
    }

    /*
     * Funcion donde se crea la forma para Buscar La Preorden Generada desde la Rotacion
     * @param array $action vector que contiene los link de la aplicacion
     * @return string $html retorna la cadena con el codigo html de la pagina
     */

    function FormaBuscarDocumento($action, $request, $datos, $conteo, $pagina)
    {
        $ctl = AutoCarga::factory("ClaseUtil");
        $html .= $ctl->IsDate("-");
        $html .= $ctl->AcceptDate("-");
        $html .= $ctl->LimpiarCampos();
        $bodegades = SessionGetVar("bodegaDesc");
        $html = "  <script>\n";
        $html .= "	  function LimpiarCampos(frm)\n";
        $html .= "	  {\n";
        $html .= "		  for(i=0; i<frm.length; i++)\n";
        $html .= "		  {\n";
        $html .= "			  switch(frm[i].type)\n";
        $html .= "			  {\n";
        $html .= "				  case 'text': frm[i].value = ''; break;\n";
        $html .= "				  case 'select-one': frm[i].selectedIndex = 0; break;\n";
        $html .= "			  }\n";
        $html .= "		  }\n";
        $html .= "	  }\n";
        $html .= "		function mOvr(src,clrOver)\n";
        $html .= "		{\n";
        $html .= "			src.style.background = clrOver;\n";
        $html .= "		}\n";
        $html .= "		function mOut(src,clrIn)\n";
        $html .= "		{\n";
        $html .= "			src.style.background = clrIn;\n";
        $html .= "		}\n";
        $html .="  </script>\n";
        $html .= ThemeAbrirTabla('CONSULTAR PRE-ORDEN');
        $html .= "<form name=\"FormaConsultar\" id=\"FormaConsultar\" action=\"" . $action['buscador'] . "\"  method=\"post\" >\n";
        $html .= "<table  width=\"45%\" align=\"center\" border=\"0\" class=\"modulo_table_list\" >\n";
        $html .= "  <tr  colspan=\"5\" class=\"formulacion_table_list\" >\n";
        $html .= "      <td align=\"left\">NUMERO PRE-ORDEN:</td>\n";
        $html .= "      <td colspan=\"5\" align=\"left\"  class=\"modulo_list_claro\">  <input class=\"input-text\" type=\"text\"  name=\"buscador[orden]\"  id=\"txtncontrato\"   value=\"\" size=\"30%\" maxlength=\"30\" >\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr  colspan=\"5\" class=\"formulacion_table_list\" >\n";
        $html .= "      <td align=\"left\">FARMACIA:</td>\n";
        $html .= "      <td colspan=\"5\" align=\"left\"  class=\"modulo_list_claro\">  <input class=\"input-text\" type=\"text\"  name=\"buscador[farmacia]\"  id=\"txtncontrato\"   value=\"\" size=\"30%\" maxlength=\"30\" >\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= "			<table   width=\"30%\" align=\"center\" border=\"0\"   >";
        $html .= "  <tr>\n";
        $html .= "	             	<td  colspan=\"10\"  align='center'>\n";
        $html .= "			         <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\"  >\n";
        $html .= "		          	</td>\n";
        $html .= "			<td  colspan=\"10\" align='center' >\n";
        $html .= "			<input class=\"input-submit\" type=\"button\" name=\"limpiar\" onclick=\"LimpiarCampos(document.FormaConsultar)\" value=\"Limpiar Campos\">\n";
        $html .= "	  	</td>\n";
        $html .= "		</tr>\n";
        $html .= "</table><br>\n";

        if (!empty($datos))
        {
            $pghtml = AutoCarga::factory('ClaseHTML');
            $html .= "  <table width=\"100%\"  class=\"modulo_table_list\" align=\"center\">";
            $html .= "	  <tr align=\"CENTER\" class=\"formulacion_table_list\" >\n";
            $html .= "      <td    width=\"10%\">NUMERO DOC.</td>\n";
            $html .= "      <td   width=\"25%\">EMPRESA</td>\n";
            $html .= "      <td   width=\"25%\">OBSERVACION</td>\n";
            $html .= "      <td  width=\"30%\">USUARIO</td>\n";
            $html .= "      <td   width=\"15%\">FECHA REGISTRO</td>\n";
            $html .= "	      <td  width=\"12%\">";
            $html .= "      INFORMACION</td>\n";
            $html .= "  </tr>\n";

            $est = "modulo_list_claro";
            $back = "#DDDDDD";
            foreach ($datos as $key => $dtl)
            {
                $html .= "	  <tr  align=\"CENTER\" class=\"" . $est . "\" >\n";
                $html .= "      <td   align=\"center\">" . $dtl['preorden_id'] . "</td>\n";
                $html .= "      <td align=\"left\">" . $dtl['razon_social'] . "</td>\n";
                $html .= "      <td align=\"left\">" . $dtl['observacion'] . "</td>\n";
                $html .= "      <td align=\"left\">" . $dtl['nombre'] . "</td>\n";
                $html .= "      <td align=\"left\">" . $dtl['fecha_registro'] . "</td>\n";
                $mdl = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
                $inft = $mdl->consultarSw_Unificados($dtl['preorden_id']);
                if (!empty($inft))
                {
                    $html .= "      <td align=\"center\">\n";
                    $html .= "      <a href=\"" . $action['detalle'] . URLRequest(array("preorden_id" => $dtl['preorden_id'], "farmacia_id" => $dtl['farmacia_id'])) . "\">\n";
                    $html .= "       VER\n";
                    $html .= "    </a>\n";
                    $html .= "			</td>\n";
                }
                else
                {
                    $html .= "      <td align=\"center\">\n";
                    $html .= "    OK";
                    $html .= "			</td>\n";
                }
            }
            $html .= "			</tr>\n";
            $html .= "	</table><br>\n";

            $html .= $pghtml->ObtenerPaginado($conteo, $pagina, $action['paginador']);
        }
        else
        {
            if ($request)
                $html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
        }
        $html .= " <br>";
        $html .= "<table align=\"center\" width=\"50%\">\n";
        $html .= "  <tr>\n";
        $html .= "    <td align=\"center\">\n";
        $html .= "      <a href=\"" . $action['volver'] . URLRequest(array("bodegades" => $bodegades)) . "\"  class=\"label_error\">\n";
        $html .= "        Volver\n";
        $html .= "      </a>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= ThemeCerrarTabla();
        return $html;
    }

    /*
     * Funcion donde se crea la forma para el detalle de la pre orden de compras
     * @param array $action vector que contiene los link de la aplicacion
     * @return string $html retorna la cadena con el codigo html de la pagina
     */

    function FormaDetalleDocumento($action, $dat, $datos, $conteo, $pagina, $preorden_id, $empresa)
    {

        $html = "  <script>\n";
        $html .="    function ValidarInformaOrdenCompra(frm)";
        $html .="    {";
        $html .= " 	   xajax_InformacionOrdenComp(document.getElementById('proveedor').value,'" . $preorden_id . "',document.getElementById('empresa').value,'" . $empresa . "'); ";
        $html .= "      return;\n";
        $html .="   }";
        $html .= " function ValidarDtos(frm,preorden_id,orden_pedido_id,empresa)";
        $html .= " {";
        $html .= "    xajax_TrasferirInformacion(frm.observar.value,preorden_id,orden_pedido_id,empresa); ";
        $html .= "      return;\n";
        $html .="   }";
        $html .="  </script>\n";
        $html .= ThemeAbrirTabla('DETALLE DEL DOCUMENTO DE PRE -ORDEN');
        $html .= "<form name=\"FormaDetalle\" id=\"FormaDetalle\"  action=\"" . $action['buscador'] . "\" method=\"post\" >\n";
        $html .= "			<table   width=\"35%\" align=\"center\" border=\"2\"  class=\"modulo_table_list\" >";
        $html .= "		</tr>\n";
        $html .= "   <tr class=\"formulacion_table_list\" > \n";
        $html .= "			<td align=\"center\"  ><b> PROVEEDOR:</B></td>\n";
        $html .= "			<td  class=\"modulo_list_claro\" colspan=\"4\">\n";
        $html .= "					<select name=\"buscador[proveedor_id]\" class=\"select\">\n";
        $html .= "             	<option value = '-1'>--  SELECCIONE --</option>\n";
        $csk = "";
        foreach ($dat as $indice => $valor)
        {
            if ($valor['codigo_proveedor_id'] == $request['codigo_proveedor_id'])
                $sel = "selected";
            else
                $sel = "";
            $html .= "  <option value=\"" . $valor['codigo_proveedor_id'] . "\" " . $sel . ">" . $valor['nombre_tercero'] . "</option>\n";
        }
        $html .= "                </select>\n";
        $html .= "						  </td>\n";
        $html .= "	 </tr>\n";
        $html .= "</table><br>\n";
        $html .= "			<table   width=\"40%\"  class=\"normal_10AN\" align=\"center\" border=\"0\"  >";
        $html .= "		<tr>\n";
        $html .= "	   	<td align='center'>\n";
        $html .= "			<input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\">\n";
        $html .= "			</td>\n";
        $html .= "			</tr>\n";
        $html .= "</table><br>\n";

        if (!empty($datos))
        {
            $pghtml = AutoCarga::factory('ClaseHTML');
            $html .= "  <table width=\"110%\"  class=\"modulo_table_list\" align=\"center\">";
            $html .= "	  <tr align=\"CENTER\"    class=\"formulacion_table_list\">\n";

            $html .= "      <td width=\"15%\">MOLECULA</td>\n";
            $html .= "      <td width=\"7%\">IDENTIFICACION</td>\n";
            $html .= "      <td width=\"25%\">PROVEEDOR</td>\n";
            $html .= "      <td width=\"15%\">CODIGO</td>\n";
            $html .= "      <td width=\"45%\">DESCRIPCION</td>\n";
            $html .= "      <td width=\"10%\">CANTIDAD</td>\n";
            $html .= "      <td width=\"10%\">VALOR_PACTADO</td>\n";
            $html .= "  </tr>\n";
            $html .= "     <input type=\"hidden\" name=\"proveedor\" id=\"proveedor\" value=\"" . $datos ['0']['codigo_proveedor_id'] . "\">\n";
            $html .= "     <input type=\"hidden\" name=\"empresa\" id=\"empresa\" value=\"" . $datos ['0']['farmacia_id'] . "\">\n";
            $est = "modulo_list_claro";
            $back = "#DDDDDD";
            foreach ($datos as $key => $dtl)
            {
                $html .= "	  <tr  align=\"CENTER\"    class=\"modulo_list_claro\" >\n";

                $html .= "      <td  align=\"left\">" . $dtl['molecula'] . "</td>\n";
                $html .= "      <td align=\"left\">" . $dtl['tipo_id_tercero'] . "  " . $dtl['tercero_id'] . "</td>\n";
                $html .= "      <td align=\"left\">" . $dtl['nombre_tercero'] . "</td>\n";
                $html .= "      <td align=\"left\">" . $dtl['codigo_producto'] . " </td>\n";
                $html .= "      <td align=\"left\">" . $dtl['descripcion'] . " " . $dtl['contenido_unidad_venta'] . "" . $dtl['abreviatura'] . " -" . $dtl['laboratorio'] . "</td>\n";
                $html .= "      <td align=\"center\">" . $dtl['cantidad'] . "</td>\n";
                $html .= "      <td align=\"center\">" . round($dtl['valor_total_pactado']) . "</td>\n";
            }
            $html .= "  </tr>\n";
            $html .= "	</table><br>\n";
            $html .= "  <table width=\"70%\"  border=\"0\"  align=\"center\">";
            $html .= "		<tr>\n";
            $html .= "	             	<td  colspan=\"10\"  align='center'>\n";
            $html .= "			         <input class=\"input-submit\" type=\"button\" name=\"Buscar\" value=\"GENERAR ORDEN COMPRA\" onClick=\"ValidarInformaOrdenCompra(document.FormaDetalle);\"  >\n";
            $html .= "		          	</td>\n";
            $html .= "		<tr>\n";
            $html .= "	</table><br>\n";
            $html .= $pghtml->ObtenerPaginado($conteo, $pagina, $action['paginador']);
        }
        else
        {
            if ($request)
                $html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
        }
        $html .= " <br>";
        $html .= "<table align=\"center\" width=\"50%\">\n";
        $html .= "  <tr>\n";
        $html .= "    <td align=\"center\">\n";
        $html .= "      <a href=\"" . $action['volver'] . URLRequest(array("bodega" => $bod)) . "\"  class=\"label_error\">\n";
        $html .= "       VOLVER \n";
        $html .= "      </a>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= "				    </form>\n";
        $html .= $this->CrearVentana(650, "MENSAJE");
        $html .= ThemeCerrarTabla();
        return $html;
    }

    /*
     * Funcion donde se crea la forma para  consultar las ordenes de compras
     * @param array $action vector que contiene los link de la aplicacion
     * @return string $html retorna la cadena con el codigo html de la pagina
     */

    function FormaBuscarDocumentoOrdenCompra($action, $request, $datos, $conteo, $pagina, $tiposdoc, $unidades_negocio)
    {
        $ctl = AutoCarga::factory("ClaseUtil");
        $html .= $ctl->IsDate("-");
        $html .= $ctl->AcceptDate("-");
        $html .= $ctl->LimpiarCampos();

        $html .="  <script>\n";
        $html .= "  function Paginador(CodigoProducto,Descripcion,Concentracion,Empresa_Id,ClaseId,SubclaseId,orden_pedido_id,offset)\n";
        $html .= "  {\n";
        $html .= "     xajax_ModificacionDetalleOC(CodigoProducto,Descripcion,Concentracion,Empresa_Id,ClaseId,SubclaseId,orden_pedido_id,offset);";
        $html .= "  }\n";
        $html .= "  </script>\n";

        $html .="  <script>\n";
        $html .= "  function ConfirmarAnulacionOC(orden_pedido_id)\n";
        $html .= "  {\n";
        $html .= "     OcultarSpan();";
        $html .= "  var entrar = confirm(\"Desea Anular la Orden de Compra Numero:\"+orden_pedido_id);";
        $html .="        if (entrar) ";
        $html .="       {";
        $html .="       ";
        $html .="       xajax_AnularOC(orden_pedido_id);";
        $html .="       }";
        $html .="      /*     else \n";
        $html .="       {";
        $html .="       ";
        $html .="       return(false);";
        $html .="       } */";
        $html .= "  }\n";
        $html .= "  </script>\n";

        $html .="  <script>\n";
        $html .= "  function ConfirmarModificacionOC(empresa_id,orden_pedido_id)\n";
        $html .= "  {\n";
        $html .= "     OcultarSpan();";
        $html .= "  var entrar = confirm(\"Atencion: Al modificar la Orden de Compra \"+orden_pedido_id +\", Esta Será Suspendida Mientras Se Hacen Los Cambios\");";
        $html .="        if (entrar) ";
        $html .="       {";
        $html .="       ";
        $html .="       xajax_ModificarOC(empresa_id,orden_pedido_id);";
        $html .="       }";
        $html .="      /*     else \n";
        $html .="       {";
        $html .="       ";
        $html .="       return(false);";
        $html .="       } */";
        $html .= "  }\n";
        $html .= "  </script>\n";

        $html .="  <script>\n";
        $html .= "		function mOvr(src,clrOver)\n";
        $html .= "		{\n";
        $html .= "			src.style.background = clrOver;\n";
        $html .= "		}\n";
        $html .= "		function mOut(src,clrIn)\n";
        $html .= "		{\n";
        $html .= "			src.style.background = clrIn;\n";
        $html .= "		}\n";
        $html .="  </script>\n";

        $select .= "	<select name=\"buscador[codigo_unidad_negocio]\" class=\"select\">\n";
        $select .= "		<option value=\"\">-- NINGUNO --</option>";
        foreach ($unidades_negocio as $k => $v)
        {
            if ($v['codigo_unidad_negocio'] == $request['codigo_unidad_negocio'])
                $selected = " selected ";
            else
                $selected = " ";
            $select .= "		<option " . $selected . " value=\"" . $v['codigo_unidad_negocio'] . "\">" . $v['descripcion'] . "</option>";
        }
        $select .= "	</select>";

        $html .= ThemeAbrirTabla('CONSULTAR ORDEN-COMPRAS');
        $html .= "<form name=\"FormaConsultar2\" id=\"FormaConsultar2\" action=\"" . $action['buscador'] . "\"  method=\"post\" >\n";
        $html .= "<table  width=\"45%\" align=\"center\" border=\"0\" class=\"modulo_table_list\" >\n";
        $html .= "  <tr class=\"formulacion_table_list\">\n";
        $html .= "			<td   width=\"40%\">TIPO DOCUMENTO: </td>\n";
        $html .= "			<td  align=\"left\" class=\"modulo_list_claro\" colspan=\"4\">\n";
        $html .= "					<select name=\"buscador[tipo_id_tercero]\" class=\"select\">\n";
        $html .= "             	<option value = '-1'>--  SELECCIONE --</option>\n";
        $csk = "";
        foreach ($tiposdoc as $indice => $valor)
        {
            if ($valor['tipo_id_tercero'] == $request['tipo_id_tercero'])
                $sel = "selected";
            else
                $sel = "";
            $html .= "  <option value=\"" . $valor['tipo_id_tercero'] . "\" " . $sel . ">" . $valor['descripcion'] . "</option>\n";
        }
        $html .= "                </select>\n";
        $html .= "						  </td>\n";
        $html .= "	 </tr>\n";
        $html .= "  <tr class=\"formulacion_table_list\">\n";
        $html .= "			<td  width=\"40%\" >DOCUMENTO:</td>\n";
        $html .= "	    <td class=\"modulo_list_claro\" align=\"left\" colspan=\"4\">\n";
        $html .= "     <input type=\"text\" class=\"input-text\" name=\"buscador[tercero_id]\" maxlength=\"32\" value=" . $request['tercero_id'] . "></td>\n";
        $html .= "		</tr>\n";
        $html .= "		<tr class=\"formulacion_table_list\">\n";
        $html .= "			<td >PROVEEDOR:</td>\n";
        $html .= "			<td  align=\"left\" colspan=\"4\" class=\"modulo_list_claro\" ><input type=\"text\" class=\"input-text\" name=\"buscador[nombre_tercero]\" maxlength=\"32\" value=" . $request['nombre_tercero'] . "></td>\n";
        $html .= "		</tr>\n";
        $html .= "  <tr class=\"formulacion_table_list\">\n";
        $html .= "		<td  width=\"30%\" >FECHA REGISTRO:</td>\n";
        $html .= "		<td width=\"15%\" align=\"left\" class=\"modulo_list_claro\" >\n";
        $html .= "		  <input type=\"text\" class=\"input-text\" name=\"buscador[fecha_inicio]\"   id=\"fecha_inicio\" size=\"20\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"" . $request['fecha_inicio'] . "\"  >\n";
        $html .= "		</td>\n";
        $html .= "    <td  width=\"15%\" class=\"modulo_list_claro\" >\n";
        $html .= "				" . ReturnOpenCalendario('FormaConsultar2', 'fecha_inicio', '-') . "\n";
        $html .= "		</td>\n";
        $html .= "  </tr >\n";
        $html .= "  <tr  colspan=\"5\" class=\"formulacion_table_list\" >\n";
        $html .= "      <td  >NUMERO ORDEN COMPRA:</td>\n";
        $html .= "      <td colspan=\"4\" align=\"left\" class=\"modulo_list_claro\">  <input class=\"input-text\" type=\"text\"  name=\"buscador[orden]\"  id=\"txtncontrato\"   value=\"" . $request['orden'] . "\" size=\"30%\" maxlength=\"30\" >\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr  colspan=\"5\" class=\"formulacion_table_list\" >\n";
        $html .= "      <td  >UNIDAD DE NEGOCIO:</td>\n";
        $html .= "      <td colspan=\"4\" align=\"left\" class=\"modulo_list_claro\">  \n";
        $html .= $select;
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= "			<table   width=\"30%\" align=\"center\" border=\"0\"   >";
        $html .= "  <tr>\n";
        $html .= "	             	<td  colspan=\"10\"  align='center'>\n";
        $html .= "			         <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\"  >\n";
        $html .= "		          	</td>\n";
        $html .= "			<td  colspan=\"10\" align='center' >\n";
        $html .= "			<input class=\"input-submit\" type=\"button\" name=\"limpiar\" onclick=\"LimpiarCampos(document.FormaConsultar2)\" value=\"Limpiar Campos\">\n";
        $html .= "	  	</td>\n";
        $html .= "		</tr>\n";
        $html .= "</table><br>\n";

        if (!empty($datos))
        {
            $emp = SessionGetVar("DatosEmpresaAF");

            $pghtml = AutoCarga::factory('ClaseHTML');
            $html .= "  <table width=\"100%\" class=\"modulo_table_list\" align=\"center\">";
            $html .= "	  <tr align=\"CENTER\"    class=\"formulacion_table_list\" >\n";
            $html .= "      <td  width=\"5%\">NUMERO.</td>\n";
            $html .= "      <td  width=\"10%\">IDENTIFICACION.</td>\n";
            $html .= "      <td  width=\"30%\">PROVEEDOR.</td>\n";
            $html .= "      <td  width=\"25%\">OBSERVACION</td>\n";
            $html .= "      <td  width=\"15%\">USUARIO</td>\n";
            $html .= "      <td  width=\"35%\">FECHA REGISTRO</td>\n";
            $html .= "	    <td  width=\"10%\">INF</td>\n";
            $html .= "	    <td  width=\"10%\">ASIG</td>\n";
            $html .= "	    <td  title=\"MUESTRA MINDEFENSA\" width=\"10%\">MINDF</td>\n";
            $html .= "	    <td  width=\"10%\">PDF</td>\n";
            $html .= "	    <td  width=\"10%\">HTML</td>\n";
            $html .= "      <td title=\"Modificar Orden De Compra\">MOD</td>";
            $html .= "	    <td  width=\"10%\">ESTADO</td>\n";
            $html .= "  </tr>\n";

            $xml = Autocarga::factory("ReportesCsv");
            $xml2 = Autocarga::factory("ReportesCsv");
            $reporte = new GetReports();

            $est = "modulo_list_claro";
            $back = "#DDDDDD";
            foreach ($datos as $key => $dtl)
            {
                if ($dtl['estado'] == "2")
                {
                    $imagen = "pinactivo.png";
                    $mensaje = "Documento Anulado";
                    $Elemento = "        <img title=\"" . $mensaje . "\" src=\"" . GetThemePath() . "/images/" . $imagen . "\" border=\"0\">\n";
                }
                else if ($dtl['estado'] == "1")
                {

                    $Elemento = "        <a href=\"javascript:ConfirmarAnulacionOC('" . $dtl['orden_pedido_id'] . "')\" title=\"ANULAR DOCUMENTO\">\n";
                    $Elemento .= "          <img src=\"" . GetThemePath() . "/images/elimina.png\" border=\"0\">\n";
                    $Elemento .= "        </a>\n";
                }
                else
                {
                    $Elemento = "        <img title=\"DOCUMENTO RECIBIDO\" src=\"" . GetThemePath() . "/images/pactivo.png\" border=\"0\">\n";
                }

                $html .= "	  <tr  align=\"CENTER\" class=\"" . $est . "\" >\n";
                $html .= "      <td   align=\"center\">" . $dtl['orden_pedido_id'] . "</td>\n";
                $html .= "      <td   align=\"center\">" . $dtl['tipo_id_tercero'] . "   " . $dtl['tercero_id'] . "</td>\n";
                $html .= "      <td   align=\"center\">" . $dtl['nombre_tercero'] . "</td>\n";
                $html .= "      <td align=\"left\">" . $dtl['observacion'] . "</td>\n";
                $html .= "      <td align=\"left\">" . $dtl['nombre'] . "</td>\n";
                $html .= "      <td align=\"left\">" . $dtl['fecha_registro'] . "</td>\n";
                $html .= "      <td align=\"center\">\n";
                $html .= "      <a href=\"" . $action['detalle2'] . URLRequest(array("orden_pedido_id" => $dtl['orden_pedido_id'], "empresa_id" => $dtl['empresa_id'], "tipo_id_tercero" => $dtl['tipo_id_tercero'], "tercero_id" => $dtl['tercero_id'], "nombre" => $dtl['nombre'], "razon_social" => $dtl['razon_social'])) . "\">\n";
                $html .= "        <img src=\"" . GetThemePath() . "/images/informacion.png\" border=\"0\">\n";
                $html .= "    </a>\n";
                $html .= "			</td>\n";
                //print_r($emp);


                $html .= "      <td align=\"center\">\n";
                if ($dtl['estado'] == "1")
                {
                    $html .= "        <a href=\"" . $action['asignar'] . URLRequest(array("orden_pedido_id" => $dtl['orden_pedido_id'], "empresa_id" => $dtl['empresa_id'])) . "\" title=\"Asignar Condiciones\">\n";
                    $html .= "          <img src=\"" . GetThemePath() . "/images/news.gif\" border=\"0\">\n";
                    $html .= "        </a>\n";
                }
                $html .= "			</td>\n";


                $datos2['orden_pedido_id'] = $dtl['orden_pedido_id'];
                $dato2s['tipo_id_tercero'] = $dtl['tipo_id_tercero'];
                $datos2['tercero_id'] = $dtl['tercero_id'];
                $datos2['empresa_id'] = $dtl['empresa_id'];
                $datos2['codigo_proveedor_id'] = $dtl['codigo_proveedor_id'];
                $datos2['codigo_unidad_negocio'] = $request['codigo_unidad_negocio'];


                $html .= $xml2->GetJavacriptReporteFPDF('app', 'Compras_Orden_Compras', 'InformeOrdenPedidoMin', $datos2, array("interface" => 5));
                $fnc0 = $xml2->GetJavaFunction();
                $html .= "    <td>\n";
                $html .= "        <a href=\"javascript:" . $fnc0 . "\" title=\"MUESTRA MINDEFENSA\">\n";
                $html .= "          <img src=\"" . GetThemePath() . "/images/imprimir.png\" border=\"0\">\n";
                $html .= "        </a>\n";
                $html .= "    </td>\n";


                $html .= $xml->GetJavacriptReporteFPDF('app', 'Compras_Orden_Compras', 'InformeOrdenPedido', $datos2, array("interface" => 5));
                $fnc1 = $xml->GetJavaFunction();
                $html .= "    <td>\n";
                $html .= "        <a href=\"javascript:" . $fnc1 . "\" title=\"IMPRIMIR ORDEN PDF\">\n";
                $html .= "          <img src=\"" . GetThemePath() . "/images/imprimir.png\" border=\"0\">\n";
                $html .= "        </a>\n";
                $html .= "    </td>\n";
                //$html .= "			</td>\n";

                $mostrar = $reporte->GetJavaReport('app', 'Compras_Orden_Compras', 'OrdenCompra', array("orden_pedido_id" => $dtl['orden_pedido_id'], "codigo_unidad_negocio" => $request['codigo_unidad_negocio']), array('rpt_name' => '', 'rpt_dir' => 'cache', 'rpt_rewrite' => TRUE));
                $funcion = $reporte->GetJavaFunction();

                $html .= "				<td align=\"center\" >\n";
                $html .= "				" . $mostrar . "\n";
                $html .= "					<a href=\"javascript:$funcion\" class=\"label_error\"  title=\"REPORTE DEL PEDIDO\"><img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0' >\n";
                $html .= "					</a></center>\n";
                $html .= "			</td>\n";


                if ($emp['sw_modifica'] == '1' && $dtl['estado'] == "1")
                {
                    $html .= "         <td>";
                    $html .= "        <a href=\"javascript:ConfirmarModificacionOC('" . $dtl['empresa_id'] . "','" . $dtl['orden_pedido_id'] . "')\" title=\"MODIFICAR DOCUMENTO DE COMPRA\">\n";
                    $html .= "          <img src=\"" . GetThemePath() . "/images/editar.png\" border=\"0\">\n";
                    $html .= "        </a>\n";
                    $html .= "         </td>";
                }
                else
                {
                    $html .= "<td>";
                    $html .= "</td>";
                }


                $html .= "      <td>";
                $html .= "        " . $Elemento;
                $html .= "        </td>";
            }
            $html .= "			</tr>\n";
            $html .= "	</table><br>\n";
            $html .= $pghtml->ObtenerPaginado($conteo, $pagina, $action['paginador']);
        }
        else
        {
            if ($request)
                $html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
        }
        $html .= " <br>";
        $html .= "<table align=\"center\" width=\"50%\">\n";
        $html .= "  <tr>\n";
        $html .= "    <td align=\"center\">\n";
        $html .= "      <a href=\"" . $action['volver'] . URLRequest(array("bodegades" => $bodegades)) . "\"  class=\"label_error\">\n";
        $html .= "        Volver\n";
        $html .= "      </a>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= $this->CrearVentana(820, "ORDENES DE COMPRA");
        $html .= ThemeCerrarTabla();

        return $html;
    }
    
    /*
     * Funcion donde se crea la forma para  consultar las auditorias de los detalles (itemd) de las ordenes de compras
     * @param array $action vector que contiene los link de la aplicacion
     * @return string $html retorna la cadena con el codigo html de la pagina
     */

    function FormaConsultarAuditoriasDetallesOrdenesCompras($action, $request, $datos, $conteo, $pagina, $tiposdoc, $unidades_negocio, $datos_documento_temporal, $datos_documento)
    {
        $ctl = AutoCarga::factory("ClaseUtil");
        $html .= $ctl->IsDate("-");
        $html .= $ctl->AcceptDate("-");
        $html .= $ctl->LimpiarCampos();
        
        $select .= "	<select name=\"buscador[codigo_unidad_negocio]\" class=\"select\">\n";
        $select .= "		<option value=\"\">-- NINGUNO --</option>";
        foreach($unidades_negocio as $k => $v)
        {
        if($v['codigo_unidad_negocio']==$request['codigo_unidad_negocio'])
        $selected = " selected ";
                else
                $selected = " ";
        $select .= "		<option ".$selected." value=\"".$v['codigo_unidad_negocio']."\">".$v['descripcion']."</option>";
        }
        $select .= "	</select>";
        
        $html .= ThemeAbrirTabla('CONSULTAR AUDITORIA ITEMS ORDEN-COMPRAS');
        $html .= "<form name=\"FormaConsultar2\" id=\"FormaConsultar2\" action=\"" . $action['buscador'] . "\"  method=\"post\" >\n";
        $html .= "<table  width=\"45%\" align=\"center\" border=\"0\" class=\"modulo_table_list\" >\n";
        /*$html .= "  <tr class=\"formulacion_table_list\">\n";
        $html .= "      <td   width=\"40%\">TIPO DOCUMENTO: </td>\n";
        $html .= "      <td  align=\"left\" class=\"modulo_list_claro\" colspan=\"4\">\n";
        $html .= "          <select name=\"buscador[tipo_id_tercero]\" class=\"select\">\n";
        $html .= "             	<option value = '-1'>--  SELECCIONE --</option>\n";
        $csk = "";
        foreach ($tiposdoc as $indice => $valor)
        {
            if ($valor['tipo_id_tercero'] == $request['tipo_id_tercero'])
                $sel = "selected";
            else
                $sel = "";
            $html .= "              <option value=\"" . $valor['tipo_id_tercero'] . "\" " . $sel . ">" . $valor['descripcion'] . "</option>\n";
        }
        $html .= "          </select>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr class=\"formulacion_table_list\">\n";
        $html .= "      <td  width=\"40%\" >DOCUMENTO:</td>\n";
        $html .= "      <td class=\"modulo_list_claro\" align=\"left\" colspan=\"4\">\n";
        $html .= "          <input type=\"text\" class=\"input-text\" name=\"buscador[tercero_id]\" maxlength=\"32\" value=" . $request['tercero_id'] . ">\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr class=\"formulacion_table_list\">\n";
        $html .= "      <td >PROVEEDOR:</td>\n";
        $html .= "      <td  align=\"left\" colspan=\"4\" class=\"modulo_list_claro\" ><input type=\"text\" class=\"input-text\" name=\"buscador[nombre_tercero]\" maxlength=\"32\" value=" . $request['nombre_tercero'] . "></td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr class=\"formulacion_table_list\">\n";
        $html .= "      <td  width=\"30%\" >FECHA REGISTRO:</td>\n";
        $html .= "      <td width=\"15%\" align=\"left\" class=\"modulo_list_claro\" >\n";
        $html .= "          <input type=\"text\" class=\"input-text\" name=\"buscador[fecha_inicio]\"   id=\"fecha_inicio\" size=\"20\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$request['fecha_inicio']."\"  >\n";
        $html .= "      </td>\n";
        $html .= "      <td  width=\"15%\" class=\"modulo_list_claro\" >\n";
        $html .= "          " . ReturnOpenCalendario('FormaConsultar2', 'fecha_inicio', '-') . "\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";*/
        $html .= "  <tr colspan=\"5\" class=\"formulacion_table_list\">\n";
        $html .= "      <td >NUMERO ORDEN COMPRA:</td>\n";
        $html .= "      <td colspan=\"4\" align=\"left\" class=\"modulo_list_claro\">  <input class=\"input-text\" type=\"text\"  name=\"buscador[orden]\"  id=\"txtncontrato\"   value=\"".$request['orden']."\" size=\"30%\" maxlength=\"30\" >\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        /*$html .= "  <tr  colspan=\"5\" class=\"formulacion_table_list\" >\n";
        $html .= "      <td  >UNIDAD DE NEGOCIO:</td>\n";
        $html .= "      <td colspan=\"4\" align=\"left\" class=\"modulo_list_claro\">  \n";
        $html .= $select;
        $html .= "      </td>\n";
        $html .= "  </tr>\n";*/
        $html .= "</table>\n";
        $html .= "<table   width=\"30%\" align=\"center\" border=\"0\"   >";
        $html .= "  <tr>\n";
        $html .= "      <td  colspan=\"10\"  align='center'>\n";
        $html .= "          <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\"  >\n";
        $html .= "      </td>\n";
        $html .= "      <td  colspan=\"10\" align='center' >\n";
        $html .= "          <input class=\"input-submit\" type=\"button\" name=\"limpiar\" onclick=\"LimpiarCampos(document.FormaConsultar2)\" value=\"Limpiar Campos\">\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table><br>\n";

        if (!empty($datos))
        {
            //$emp = SessionGetVar("DatosEmpresaAF");

            //$pghtml = AutoCarga::factory('ClaseHTML');
            $html .= "<table width=\"100%\" class=\"modulo_table_list\" align=\"center\">";
            $html .= "      <tr align=\"center\" class=\"formulacion_table_list\">\n";
            $html .= "      <td width=\"5%\">NUMERO OC.</td>\n";
            $html .= "      <td width=\"5%\">IDENTIFICACION.</td>\n";
            $html .= "      <td width=\"20%\">PROVEEDOR.</td>\n";
            $html .= "      <td width=\"10%\">USUARIO</td>\n";
            $html .= "      <td width=\"8%\">FECHA REGISTRO OC</td>\n";
            $html .= "      <td width=\"8%\">CÓDIGO PRODUCTO</td>\n";
            $html .= "      <td width=\"30%\">DESCRIPCIÓN</td>\n";
            $html .= "      <td width=\"3%\">UNIDADES</td>\n";
            $html .= "      <td width=\"3%\">IVA</td>\n";
            $html .= "      <td width=\"3%\">VALOR</td>\n";
            $html .= "      <td width=\"3%\">ACCIÓN</td>\n";
            $html .= "      <td width=\"10%\">USUARIO AUD.</td>\n";
            $html .= "      <td width=\"10%\">VERSIÓN REGISTRO</td>\n";
            $html .= "      <td width=\"24%\">FECHA REGISTRO AUD.</td>\n";
            $html .= "  </tr>\n";
            
            $est = "modulo_list_claro";
            
            foreach ($datos as $dtl)
            {
                $html .= "	 <tr align=\"center\" class=\"" . $est . "\" >\n";
                $html .= "      <td>" . $dtl['orden_pedido_id'] . "</td>\n";
                $html .= "      <td>" . $dtl['tipo_id_tercero'] . "   " . $dtl['tercero_id'] . "</td>\n";
                $html .= "      <td>" . $dtl['nombre_tercero'] . "</td>\n";
                $html .= "      <td>" . $dtl['nombre'] . "</td>\n";
                $html .= "      <td>" . $dtl['fecha_registro'] . "</td>\n";
                $html .= "      <td>" . $dtl['codigo_producto'] . "</td>\n";
                $html .= "      <td>" . $dtl['descripcion'] . "</td>\n";
                $html .= "      <td>" . $dtl['numero_unidades'] . "</td>\n";
                $html .= "      <td>" . $dtl['porc_iva'] . "</td>\n";
                $html .= "      <td>" . $dtl['valor'] . "</td>\n";
                $html .= "      <td>" . $dtl['accion'] . "</td>\n";
                $html .= "      <td>" . $dtl['usuario_auditoria'] . "</td>\n";
                $html .= "      <td>" . $dtl['version'] . "</td>\n";
                $html .= "      <td>" . $dtl['fecha_registro_auditoria'] . "</td>\n";
            }
            $html .= "  </tr>\n";
            $html .= "</table><br>\n";
            
            //$html .= $pghtml->ObtenerPaginado($conteo, $pagina, $action['paginador']);
        }
        else
        {
            if ($request)
                //$html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
                $html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS DE AUDITORIA GENERAL EN LA ORDEN DE COMPRA</center><br>\n";
        }
        $html .= " <br>";
        
        if (!empty($datos_documento_temporal))
        {
            //$emp = SessionGetVar("DatosEmpresaAF");

            $html .= "<table width=\"100%\" class=\"modulo_table_list\" align=\"center\">";
            $html .= "  <tr align=\"center\" class=\"formulacion_table_list\">\n";
            $html .= "      <td width=\"10%\" rowspan=\"2\">CÓDIGO</td>\n";
            $html .= "      <td width=\"8%\" rowspan=\"2\">DESCRIPCIÓN</td>\n";
            $html .= "      <td width=\"10%\" colspan=\"2\">CANTIDAD</td>\n";
            $html .= "      <td width=\"10%\" colspan=\"2\">VALOR</td>\n";
            $html .= "      <td width=\"10%\" rowspan=\"2\">USUARIO REGISTRA ITEM TEMPORAL</td>\n";
            $html .= "      <td width=\"8%\" rowspan=\"2\">FECHA REGISTRO DOCUMENTO TEMPORAL</td>\n";
            $html .= "  </tr>\n";
            $html .= "  <tr align=\"center\" class=\"formulacion_table_list\">\n";
            $html .= "      <td width=\"5%\">Antiguo Valor</td>\n";
            $html .= "      <td width=\"5%\">Nuevo Valor</td>\n";
            $html .= "      <td width=\"5%\">Antiguo Valor</td>\n";
            $html .= "      <td width=\"5%\">Nuevo Valor</td>\n";
            $html .= "  </tr>\n";
            
            $est = "modulo_list_claro";

            foreach ($datos_documento_temporal as $dtl)
            {
                $html .= "	 <tr align=\"center\" class=\"" . $est . "\" >\n";
                $html .= "      <td>" . $dtl['codigo_producto'] . "</td>\n";
                $html .= "      <td>" . $dtl['descripcion'] . "</td>\n";
                /*$html .= "      <td>" . $dtl['cantidad_original'] . "</td>\n";
                $html .= "      <td>" . $dtl['cantidad_modificada'] . "</td>\n";
                $html .= "      <td>" . $dtl['valor_original'] . "   " . $dtl['tercero_id'] . "</td>\n";
                $html .= "      <td>" . $dtl['valor_modificado'] . "</td>\n";*/
                $html .= "      <td>" . $dtl['cantidad_original'] . "</td>\n";
                $html .= "      <td>" . $dtl['cantidad_modificada'] . "</td>\n";
                $html .= "      <td>" . $dtl['valor_original'] . "   " . $dtl['tercero_id'] . "</td>\n";
                $html .= "      <td>" . $dtl['valor_modificado'] . "</td>\n";
                $html .= "      <td>" . $dtl['nombre'] . "</td>\n";
                $html .= "      <td>" . $dtl['fecha_registro'] . "</td>\n";
            }
            $html .= "  </tr>\n";
            $html .= "</table><br>\n";
        }

        if (!empty($datos_documento))
        {
            //$emp = SessionGetVar("DatosEmpresaAF");

            $html .= "<table width=\"100%\" class=\"modulo_table_list\" align=\"center\">";
            $html .= "  <tr align=\"center\" class=\"formulacion_table_list\">\n";
            $html .= "      <td width=\"10%\" rowspan=\"2\">CÓDIGO</td>\n";
            $html .= "      <td width=\"8%\" rowspan=\"2\">DESCRIPCIÓN</td>\n";
            $html .= "      <td width=\"10%\" colspan=\"2\">CANTIDAD</td>\n";
            $html .= "      <td width=\"10%\" colspan=\"2\">VALOR</td>\n";
            $html .= "      <td width=\"10%\" rowspan=\"2\">USUARIO REGISTRA ITEM</td>\n";
            $html .= "      <td width=\"8%\" rowspan=\"2\">FECHA REGISTRO DOCUMENTO</td>\n";
            $html .= "  </tr>\n";
            $html .= "  <tr align=\"center\" class=\"formulacion_table_list\">\n";
            $html .= "      <td width=\"5%\">Antiguo Valor</td>\n";
            $html .= "      <td width=\"5%\">Nuevo Valor</td>\n";
            $html .= "      <td width=\"5%\">Antiguo Valor</td>\n";
            $html .= "      <td width=\"5%\">Nuevo Valor</td>\n";
            $html .= "  </tr>\n";
            
            $est = "modulo_list_claro";

            foreach ($datos_documento as $dtl)
            {
                $html .= "	 <tr align=\"center\" class=\"" . $est . "\" >\n";
                $html .= "      <td>" . $dtl['codigo_producto'] . "</td>\n";
                $html .= "      <td>" . $dtl['descripcion'] . "</td>\n";
                /*$html .= "      <td>" . $dtl['numero_unidades'] . "</td>\n";
                $html .= "      <td>" . $dtl['cantidad'] . "</td>\n";
                $html .= "      <td>" . $dtl['valor'] . "   " . $dtl['tercero_id'] . "</td>\n";
                $html .= "      <td>" . $dtl['total_costo'] . "</td>\n";*/
                $html .= "      <td>" . $dtl['cantidad_original'] . "</td>\n";
                $html .= "      <td>" . $dtl['cantidad_modificada'] . "</td>\n";
                $html .= "      <td>" . $dtl['valor_original'] . "   " . $dtl['tercero_id'] . "</td>\n";
                $html .= "      <td>" . $dtl['valor_modificado'] . "</td>\n";
                $html .= "      <td>" . $dtl['nombre'] . "</td>\n";
                $html .= "      <td>" . $dtl['fecha_registro'] . "</td>\n";
            }
            $html .= "  </tr>\n";
            $html .= "</table><br>\n";
        }
        
        $html .= "<table align=\"center\" width=\"50%\">\n";
        $html .= "  <tr>\n";
        $html .= "    <td align=\"center\">\n";
        $html .= "      <a href=\"" . $action['volver'] . URLRequest(array("bodegades" => $bodegades)) . "\"  class=\"label_error\">\n";
        $html .= "        Volver\n";
        $html .= "      </a>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= $this->CrearVentana(820, "ORDENES DE COMPRA");
        $html .= ThemeCerrarTabla();

        return $html;
    }
    
    /*
     * Funcion donde se crea la forma para  consultar las ordenes de compras con las novedades de sus detalles
     * @param array $action vector que contiene los link de la aplicacion
     * @return string $html retorna la cadena con el codigo html de la pagina
     */

    function FormaBuscarNovedadesDetallesOrdenesCompras($action, $request, $datos, $observaciones, $ruta_archivo, $conteo, $pagina/*, $tiposdoc*/)
    {
        $ctl = AutoCarga::factory("ClaseUtil");
        $html .= $ctl->IsDate("-");
        $html .= $ctl->AcceptDate("-");
        $html .= $ctl->LimpiarCampos();
        
        $html .="<script >\n";
        $html .= "  function CambiarEstadoCampoFecha(id)";
        $html .= "  {";
        $html .= "      var id_novedad = document.getElementById('observacion_'+id).value;\n";
        $html .= "      var fecha_posible_envio = document.getElementById('fecha_posible_envio_'+id);\n";
        $html .= "      if(id_novedad == 1) {";
        $html .= "          fecha_posible_envio.disabled = false;\n";
        $html .= "      } else {";
        $html .= "          fecha_posible_envio.disabled = true;\n";
        $html .= "      }";
        $html .="   }";
        $html .= "  function ValidarCampos(id)";
        $html .= "  {";
        $html .= "      objeto = document.getElementById('error');\n";
        $html .= "      var forma = 'formaNovedadOrdenCompra_'+id;\n";
        $html .= "      var fecha = 'fecha_posible_envio_'+id;\n";
        $html .= "      var novedad = 'observacion_'+id;\n";
        $html .= "      if(document.getElementById(novedad).value == \"\")\n";
        $html .= "      {\n";
        $html .= "          objeto.innerHTML = \"SE DEBE SELECCIONAR UNA NOVEDAD\";\n";
        $html .= "          return;\n";
        $html .= "      }\n";
        $html .= "      if(document.getElementById(fecha).value == \"\" && document.getElementById(fecha).disabled != true)\n";
        $html .= "      {\n";
        $html .= "          objeto.innerHTML = \"SE DEBE DIGITAR UNA FECHA DE POSIBLE ENVÍO\";\n";
        $html .= "          return;\n";
        $html .= "      }\n";
        $html .= "      document.getElementById(forma).action =\"".$action['guardar']."\"; \n";
        $html .= "      document.getElementById(forma).submit();\n";
        $html .="   }";
        
        $html .= "  function ConfirmarEliminarArchivoNovedad(idArchivo, nombreArchivo, idForma)";
        $html .= "  {";
        $html .= "      var forma = 'formaNovedadOrdenCompra_'+idForma;\n";
        $html .= "      document.getElementById('id_archivo_unico_'+idForma).value = idArchivo;\n";
        $html .= "      document.getElementById('archivo_unico_'+idForma).value = nombreArchivo;\n";
        $html .= "      if(confirm('¿ESTÁ SEGURO(A) QUE DESEA ELIMINAR ESTE ARCHIVO ADJUNTO?')) {";
        $html .= "          document.getElementById(forma).action =\"".$action['borrarArchivoNovedad']."\"; \n";
        $html .= "          document.getElementById(forma).submit();\n";
        $html .= "      }";
        $html .="   }";
        
        $html .= "  function ConfirmarEliminarNovedad(id)";
        $html .= "  {";
        $html .= "      var forma = 'formaNovedadOrdenCompra_'+id;\n";
        $html .= "      if(confirm('¿ESTÁ SEGURO(A) QUE DESEA ELIMINAR ESTA NOVEDAD? TENGA EN CUENTA QUE DE HACERLO TAMBIÉN SE ELIMINARAN SUS ARCHIVOS ADJUNTOS')) {";
        $html .= "          document.getElementById(forma).action =\"".$action['borrarNovedad']."\"; \n";
        $html .= "          document.getElementById(forma).submit();\n";
        $html .= "      }";
        $html .="   }";
        $html .="</script>\n";
        
        $html .= ThemeAbrirTabla('NOVEDADES ORDEN-COMPRAS');
        $html .= "<form name=\"FormaConsultar2\" id=\"FormaConsultar2\" action=\"" . $action['buscador'] . "\"  method=\"post\" >\n";
        $html .= "  <table  width=\"45%\" align=\"center\" border=\"0\" class=\"modulo_table_list\" >\n";
        $html .= "      <tr class=\"formulacion_table_list\">\n";
        $html .= "          <td  width=\"30%\" >FECHA REGISTRO:</td>\n";
        $html .= "          <td width=\"15%\" align=\"left\" class=\"modulo_list_claro\" >\n";
        $html .= "              <input type=\"text\" class=\"input-text\" name=\"buscador[fecha_inicio]\"   id=\"fecha_inicio\" size=\"20\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"\"  >\n";
        $html .= "          </td>\n";
        $html .= "          <td  width=\"15%\" class=\"modulo_list_claro\" >\n";
        $html .= "              " . ReturnOpenCalendario('FormaConsultar2', 'fecha_inicio', '-') . "\n";
        $html .= "          </td>\n";
        $html .= "      </tr >\n";
        $html .= "      <tr  colspan=\"5\" class=\"formulacion_table_list\" >\n";
        $html .= "          <td  >NUMERO ORDEN COMPRA:</td>\n";
        $html .= "          <td colspan=\"4\" align=\"left\" class=\"modulo_list_claro\">  <input class=\"input-text\" type=\"text\"  name=\"buscador[orden]\"  id=\"txtncontrato\"   value=\"\" size=\"30%\" maxlength=\"30\" >\n";
        $html .= "          </td>\n";
        $html .= "      </tr>\n";
        $html .= "  </table>\n";
        $html .= "  <table   width=\"30%\" align=\"center\" border=\"0\"   >";
        $html .= "      <tr>\n";
        $html .= "          <td  colspan=\"10\"  align='center'>\n";
        $html .= "              <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\"  >\n";
        $html .= "          </td>\n";
        $html .= "          <td  colspan=\"10\" align='center' >\n";
        $html .= "              <input class=\"input-submit\" type=\"button\" name=\"limpiar\" onclick=\"LimpiarCampos(document.FormaConsultar2)\" value=\"Limpiar Campos\">\n";
        $html .= "          </td>\n";
        $html .= "      </tr>\n";
        $html .= "  </table><br>\n";
        $html .= "</form>\n";

        if (!empty($datos))
        {
            $sql = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");

            $pghtml = AutoCarga::factory('ClaseHTML');
            $html .= "<center><div class=\"label_error\" id=\"error\"></div></center>\n";
            $html .= "<table width=\"100%\" class=\"modulo_table_list\" align=\"center\">";
            $html .= "  <tr align=\"CENTER\"    class=\"formulacion_table_list\" >\n";
            $html .= "      <td width=\"5%\">NUMERO.</td>\n";
            $html .= "      <td width=\"10%\">PRODUCTO.</td>\n";
            $html .= "      <td width=\"10%\">CANTIDAD SOLICITADA.</td>\n";
            $html .= "      <td width=\"10%\">CANTIDAD RECIBIDA.</td>\n";
            $html .= "      <td width=\"10%\">CANTIDAD PENDIENTE.</td>\n";
            $html .= "      <td width=\"10%\">NOVEDAD.</td>\n";
            $html .= "      <td width=\"10%\">FECHA POSIBLE ENVÍO.</td>\n";
            $html .= "      <td width=\"10%\">DESCRIPCIÓN.</td>\n";
            $html .= "      <td width=\"10%\">ARCHIVO(S).</td>\n";
            $html .= "      <td width=\"5%\">GUARDAR.</td>\n";
            $html .= "      <td width=\"5%\">BORRAR.</td>\n";
            $html .= "  </tr>\n";
            
            $est = "modulo_list_claro";
            
            $i = 1;
            
            foreach ($datos as $dtl)
            {
                $archivos = $sql->ConsultarArchivosNovedadDetalleOrdenCompra($dtl['novedad_orden_compra_id']);
                
                if(!empty($archivos[0]['archivo'])) {
                    $numero_filas = count($archivos) + 1;
                } else {
                    $numero_filas = count($archivos);
                }
                
                if($numero_filas == 0) {
                    $numero_filas = 1;
                }
                
                $html .= "  <form name=\"formaNovedadOrdenCompra_".$i."\" id=\"formaNovedadOrdenCompra_".$i."\" action=\"javascript:ValidarCampos(".$i.")\" enctype=\"multipart/form-data\" method=\"post\">\n";
                $html .= "      <tr class=\"" . $est . "\" >\n";
                $html .= "          <td align=\"center\" rowspan=\"".$numero_filas."\">\n";
                $html .= "              " . $dtl['orden_pedido_id'] . "\n";
                $html .= "              <input class=\"input-text\" type=\"hidden\" name=\"compra_orden_detalle_id\" value=\"" . $dtl['compra_orden_detalle_id'] . "\">\n";
                $html .= "          </td>\n";
                $html .= "          <td align=\"center\" rowspan=\"".$numero_filas."\">" . $dtl['nombre'] . "</td>\n";
                $html .= "          <td align=\"center\" rowspan=\"".$numero_filas."\">" . $dtl['cantidad_solicitada'] . "</td>\n";
                $html .= "          <td align=\"center\" rowspan=\"".$numero_filas."\">" . $dtl['cantidad_recibida'] . "</td>\n";
                $html .= "          <td align=\"center\" rowspan=\"".$numero_filas."\">" . $dtl['cantidad_pendiente'] . "</td>\n";
                $html .= "          <td align=\"center\" rowspan=\"".$numero_filas."\">\n";
                $html .= "              <input class=\"input-text\" type=\"hidden\" name=\"novedad_orden_compra_id\" value=\"" . $dtl['novedad_orden_compra_id'] . "\">\n";
                $html .= "              <select class=\"observacion\" name=\"observacion\" id=\"observacion_".$i."\" onchange=\"CambiarEstadoCampoFecha(".$i.");\">\n";
                                
                if ($dtl['observacion_orden_compra_id'] == NULL)
                {
                    $html .= "              <option value=\"\"></option>\n";
                }
                else
                {
                    $html .= "              <option value=\"\"></option>\n";
                    $html .= "              <option value=\"" . $dtl['observacion_orden_compra_id'] . "\" selected=\"selected\">" . $dtl['codigo'] . " - " . $dtl['observacion'] . "</option>\n";
                }
                for ($j = 0; $j < count($observaciones); $j++)
                {
                    $html .= "              <option value=\"" . $observaciones[$j]['observacion_orden_compra_id'] . "\">" . $observaciones[$j]['codigo'] . " - " . $observaciones[$j]['descripcion'] . "</option>\n";
                }
                $html .= "              </select>\n";
                $html .= "          </td>\n";
                
                $html .= "          <td align=\"center\" width=\"15%\" align=\"left\" class=\"modulo_list_claro\" rowspan=\"".$numero_filas."\">\n";
                if($dtl['observacion_orden_compra_id'] == 1) {
                    $html .= "              <input type=\"text\" class=\"input-text\" name=\"fecha_posible_envio\"   id=\"fecha_posible_envio_".$i."\" size=\"20\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"" . $dtl['fecha_posible_envio'] . "\" >\n";
                    $html .= "              " . ReturnOpenCalendario('formaNovedadOrdenCompra_'.$i, 'fecha_posible_envio_'.$i, '-') . "\n";
                } else {
                    $html .= "              <input type=\"text\" class=\"input-text\" name=\"fecha_posible_envio\"   id=\"fecha_posible_envio_".$i."\" size=\"20\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"" . $dtl['fecha_posible_envio'] . "\" disabled=\"disabled\">\n";
                    $html .= "              " . ReturnOpenCalendario('formaNovedadOrdenCompra_'.$i, 'fecha_posible_envio_'.$i, '-') . "\n";
                }
                $html .= "          </td>\n";
                
                $html .= "          <td rowspan=\"".$numero_filas."\">\n";
                $html .= "              <textarea name=\"descripcion\" rows=\"3\" cols=\"30\">" . $dtl['descripcion'] . "</textarea>\n";
                $html .= "          </td>\n";
                
                if ($archivos[0]['archivo'] != "") {
                    $html .= "          <td>\n";
                    $html .= "              <center>\n";
                    $html .= "                  <a class=\"label_error\" href=\"" . $ruta_archivo . $archivos[0]['archivo'] . "\"  title=\"DESCARGAR ARCHIVO\"><img src=\"" . GetThemePath() . "/images/guarda.png\" border='0'></a> - ".$archivos[0]['nombre_original_archivo']."<br>\n";
                    $html .= "              </center>\n";
                    $html .= "              <input class=\"input-submit\" type=\"button\" name=\"borrarArchivoNovedad\" value=\"Eliminar Archivo\" onclick=\"ConfirmarEliminarArchivoNovedad(".$archivos[0]['archivo_novedad_orden_compra_id'].", '".$archivos[0]['archivo']."', ".$i.");\">\n";
                    $html .= "              <input type=\"hidden\" name=\"archivo[]\" value=\"" . $archivos[0]['archivo'] . "\">\n";
                    $html .= "          </td>\n";
                }
                
                if($numero_filas == 1) {
                    $html .= "          <td   align=\"center\">\n";
                    $html .= "              <input type=\"file\" name=\"archivo\" id=\"archivo\">\n";
                    $html .= "              <input type=\"hidden\" class=\"input-text\" name=\"id_archivo_unico\" id=\"id_archivo_unico_".$i."\" value=\"\">\n";
                    $html .= "              <input type=\"hidden\" class=\"input-text\" name=\"archivo_unico\" id=\"archivo_unico_".$i."\" value=\"\">\n";
                    $html .= "          </td>\n";
                }

                $html .= "          <td rowspan=\"".$numero_filas."\">\n";
                $html .= "              <input type=\"hidden\" name=\"buscador[fecha_inicio]\" value=\"" . $request['fecha_inicio'] . "\">\n";
                $html .= "              <input type=\"hidden\" name=\"buscador[orden]\" value=\"" . $request['orden'] . "\">\n";
                $html .= "              <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"Guardar\">\n";
                $html .= "          </td>\n";
                
                $html .= "          <td rowspan=\"".$numero_filas."\">\n";
                if(!empty($dtl['novedad_orden_compra_id'])) {
                    $html .= "              <input class=\"input-submit\" type=\"button\" name=\"borrarNovedad\" value=\"Borrar\" onclick=\"ConfirmarEliminarNovedad(".$i.");\">\n";
                }                
                $html .= "          </td>\n";
                
                $html .= "      </tr>\n";
                
                for($j = 1; $j < count($archivos); $j++) {
                    $html .= "      <tr class=\"" . $est . "\" >\n";
                    $html .= "          <td>\n";
                    $html .= "              <center>\n";
                    $html .= "                  <a class=\"label_error\" href=\"" . $ruta_archivo . $archivos[$j]['archivo'] . "\"  title=\"DESCARGAR ARCHIVO\"><img src=\"" . GetThemePath() . "/images/guarda.png\" border='0'></a> - ".$archivos[$j]['nombre_original_archivo']."\n";
                    $html .= "              </center>\n";
                    $html .= "              <input class=\"input-submit\" type=\"button\" name=\"borrarArchivoNovedad\" value=\"Eliminar Archivo\" onclick=\"ConfirmarEliminarArchivoNovedad(".$archivos[$j]['archivo_novedad_orden_compra_id'].", '".$archivos[$j]['archivo']."', ".$i.");\">\n";
                    $html .= "              <input type=\"hidden\" name=\"archivo[]\" value=\"" . $archivos[$j]['archivo'] . "\">\n";
                    $html .= "          </td>\n";
                    $html .= "      </tr>\n";
                }
                
                if($numero_filas != 1) {
                    $html .= "      <tr align=\"center\" class=\"" . $est . "\" >\n";
                    $html .= "          <td   align=\"center\">\n";
                    $html .= "              <input type=\"file\" name=\"archivo\" id=\"archivo\">\n";
                    $html .= "              <input type=\"hidden\" class=\"input-text\" name=\"id_archivo_unico\" id=\"id_archivo_unico_".$i."\" value=\"\">\n";
                    $html .= "              <input type=\"hidden\" class=\"input-text\" name=\"archivo_unico\" id=\"archivo_unico_".$i."\" value=\"\">\n";
                    $html .= "          </td>\n";
                    $html .= "      </tr>\n";
                }
                                
                $html .= "  </form>\n";
                $i++;
            }
            $html .= "</table><br>\n";
            $html .= $pghtml->ObtenerPaginado($conteo, $pagina, $action['paginador']);
        }
        else
        {
            if ($request)
                $html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
        }
        $html .= "<br>";
        $html .= "<table align=\"center\" width=\"50%\">\n";
        $html .= "  <tr>\n";
        $html .= "    <td align=\"center\">\n";
        $html .= "      <a href=\"" . $action['volver'] . URLRequest(array("bodegades" => $bodegades)) . "\"  class=\"label_error\">\n";
        $html .= "        Volver\n";
        $html .= "      </a>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= $this->CrearVentana(820, "ORDENES DE COMPRA");
        $html .= ThemeCerrarTabla();

        return $html;
    }

    /*
     * Funcion donde se crea la forma para el detalle de la orden de compras generadas
     * @param array $action vector que contiene los link de la aplicacion
     * @return string $html retorna la cadena con el codigo html de la pagina
     */

    function FormaDetalleDocumentoOrdenCompra($action, $datos, $conteo, $pagina, $tipo_id_tercero, $tercero_id, $nombre, $razon, $orden_pedido_id)
    {
        $html .= ThemeAbrirTabla('DETALLE DEL DOCUMENTO DE ORDEN-COMPRA');
        $html .= "<form name=\"FormaDetalle2\" id=\"FormaDetalle2\"  action=\"" . $action['buscador'] . "\" method=\"post\" >\n";
        $html .= "<table  width=\"70%\" align=\"center\" border=\"0\" class=\"modulo_table_list\" >\n";
        $html .= "  <tr class=\"formulacion_table_list\">\n";
        $html .= "			<td  width=\"20%\">IDENTIFICACION: </td>\n";
        $html .= "			<td  width=\"20%\" align=\"left\" class=\"modulo_list_claro\" colspan=\"4\">\n";
        $html .= "				" . $tipo_id_tercero . "  " . $tercero_id . "		  </td>\n";
        $html .= "			<td   width=\"20%\">PROVEEDOR: </td>\n";
        $html .= "			<td  width=\"30%\" align=\"left\" class=\"modulo_list_claro\" colspan=\"4\">\n";
        $html .= "				" . $nombre . "  </td>\n";
        $html .= "	 </tr>\n";
        $html .= "  <tr class=\"formulacion_table_list\">\n";
        $html .= "			<td  width=\"20%\">EMPRESA: </td>\n";
        $html .= "			<td  width=\"20%\" align=\"left\" class=\"modulo_list_claro\" colspan=\"4\">\n";
        $html .= "				" . $razon . "  </td>\n";
        $html .= "			<td   width=\"20%\">ORDEN DE COMPRA No : </td>\n";
        $html .= "			<td  align=\"left\" class=\"modulo_list_claro\" colspan=\"4\">\n";
        $html .= "				" . $orden_pedido_id . "  </td>\n";
        $html .= "	 </tr>\n";
        $html .= "</table><br>\n";
        if (!empty($datos))
        {
            $pghtml = AutoCarga::factory('ClaseHTML');
            $html .= "  <table width=\"90%\" class=\"modulo_table_list\" align=\"center\">";
            $html .= "	  <tr align=\"CENTER\"   class=\"formulacion_table_list\" >\n";
            //$html .= "      <td width=\"15%\">MOLECULA</td>\n";
            $html .= "      <td width=\"15%\">CODIGO</td>\n";
            $html .= "      <td width=\"40%\">DESCRIPCION</td>\n";
            $html .= "      <td width=\"7%\">CANTIDAD</td>\n";
            $html .= "      <td width=\"12%\">VALOR UNITARIO</td>\n";
            $html .= "  </tr>\n";
            $est = "modulo_list_claro";
            $back = "#DDDDDD";
            foreach ($datos as $key => $dtl)
            {
                if (($dtl['numero_unidades']) > 0)
                {
                    $html .= "	  <tr  align=\"CENTER\"    class=\"modulo_list_claro\" >\n";
                    //$html .= "      <td  align=\"left\">".$dtl['molecula']."</td>\n";
                    $html .= "      <td align=\"left\">" . $dtl['codigo_producto'] . " </td>\n";
                    $html .= "      <td align=\"left\">" . $dtl['producto'] . " " . $dtl['contenido_unidad_venta'] . " " . $dtl['abreviatura'] . " -" . $dtl['laboratorio'] . "</td>\n";
                    $html .= "      <td align=\"center\">" . round($dtl['numero_unidades']) . "</td>\n";
                    $html .= "      <td align=\"center\">" . FormatoValor($dtl['valor'], 2) . "</td>\n";
                }
            }
            $html .= "  </tr>\n";
            $html .= "	</table><br>\n";
        }
        else
        {
            if ($request)
                $html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
        }
        $html .= " <br>";
        $html .= "<table align=\"center\" width=\"50%\">\n";
        $html .= "  <tr>\n";
        $html .= "    <td align=\"center\">\n";
        $html .= "      <a href=\"" . $action['volver'] . URLRequest(array("bodega" => $bod)) . "\"  class=\"label_error\">\n";
        $html .= "       VOLVER \n";
        $html .= "      </a>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= "				    </form>\n";
        $html .= ThemeCerrarTabla();
        return $html;
    }

    /*
     * Funcion donde se crea la forma para las Condiciones de orden de compra 
     * @param array $action vector que contiene los link de la aplicacion
     * @return string $html retorna la cadena con el codigo html de la pagina
     */

    function FormaAsiganarCondiciones($action, $orden_pedid, $empresa_id, $dats, $observacion)
    {
        $html .="<script >\n";
        $html .= "  function max(e){  ";
        $html .= "  tecla = (document.all) ? e.keyCode : e.which; ";
        $html .= "  if (tecla==8) return true;";
        $html .= "  if (tecla==13) return false;";
        $html .= " }";
        $html .= " function ValidarCondicion(frm)";
        $html .= " {";
        $html .="   if(frm.observar.value==\"\"){ ";
        $html .= "    document.getElementById('error').innerHTML = 'DEBE INGRESAR LA CONDICION DE COMPRA DE PRODUCTOS';\n";
        $html .= "      return;\n";
        $html .= "    }\n";
        $html .="   if(frm.observar.value!=\"\"){ ";
        $html .= "    xajax_TrasferirCondicion(frm.observar.value,'" . $orden_pedid . "','" . $empresa_id . "'); ";
        $html .= "      return;\n";
        $html .="   }";
        $html .="   }";
        $html .="  </script>\n";
        $html .= ThemeAbrirTabla('DETALLE DEL DOCUMENTO DE ORDEN-COMPRA ');
        $html .="<div align=\"center\" class=\"label_error\" id='error'></div> ";
        $html .= "<fieldset class=\"fieldset\">\n";
        $html .= "  <legend class=\"normal_10AN\" align=\"center\">CONDICIONES DE COMPRAS DE PRODUCTOS</legend>\n";
        $html .= " <form name=\"Forma13\" id=\"Forma13\" method=\"post\" >\n";
        $html .= "  <table class=\"modulo_table_list_title\" border=\"1\" align=\"center\" width=\"80%\">\n";
        $html .= "    <tr class=\"modulo_table_list_title\">\n";
        $html .= "      <td width=\"10%\" align=\"center\">* CONDICIONES\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr class=\"modulo_table_list_title\">\n";
        $html .= "      <td colspan=\"5\"  align=\"center\" class=\"modulo_list_claro\">\n";
        $nu = count($dats);
        $info = "";
        for ($i = 0; $i < $nu; $i++)
        {
            $info = $info . "" . $dats[$i][descripcion] . ",";
        }
        $html .= "        <textarea onkeypress=\"return max(event)\"  name=\"observar\" rows=\"2\" style=\"width:100%\">" . $observacion['observacion'] . "---" . $info . "</textarea>\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $html .= "  </table>\n";
        $html .= "  <table width=\"70%\"  border=\"0\"  align=\"center\">";
        $html .= "		<tr>\n";
        $html .= "      <td align=\"center\" class=\"normal_10AN\" >\n";
        $html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnCrear\"   value=\"GUARDAR\" onclick=\"ValidarCondicion(document.Forma13);\">\n";
        $html .= " </td>\n";
        $html .= "		</tr>\n";
        $html .= "	</table>\n";
        $html .= "<table align=\"center\" width=\"50%\">\n";
        $html .= "  <tr>\n";
        $html .= "    <td align=\"center\">\n";
        $html .= "      <a href=\"" . $action['volver'] . "\"  class=\"label_error\">\n";
        $html .= "       VOLVER \n";
        $html .= "      </a>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= "				    </form>\n";
        $html .= "</fieldset><br>\n";
        $html .= ThemeCerrarTabla();
        return $html;
    }

    /*
     * Funcion donde se crea la forma para Unificar las preordenes de compras por proveedor
     * @param array $action vector que contiene los link de la aplicacion
     * @return string $html retorna la cadena con el codigo html de la pagina
     */

    function FormaDocumentoOrdenPedido($action, $orden_pedido_id, $empresa_id, $nombre_tercero, $tipo_id_tercero, $tercero_id, $razon_social)
    {
        $url = ModuloGetURL("app", "ConsultarOrdenes", "controller", "UnificarYcreasDocumento");
        $html .= ThemeAbrirTabla('DOCUMENTO DE ORDEN DE COMPRA ');
        $html .="  <script>\n";
        $html .= "	  function GrabarDocumentosOrden(frm)\n";
        $html .= "	  {\n";
        $html .= " 	xajax_TransfeOrdenPedido(frm.observar.value);";
        $html .= "      return;\n";
        $html .= "    frm.submit();\n";
        $html .= "    }\n";
        $html .="  </script>\n";
        $html .= "<form name=\"Forma17\" id=\"Forma17\"  method=\"post\" >\n";
        $html .= "                 <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "                   <tr class=\"modulo_table_list_title\">\n";
        $html .= "                      <td align=\"center\">\n";
        $html .= "                         IDENTIFICACION ";
        $html .= "                       </td>\n";
        $html .= "                      <td align=\"left\" class=\"modulo_list_claro\">\n";
        $html .= "                        " . $tipo_id_tercero . " " . $tercero_id . " ";
        $html .= "                       </td>\n";
        $html .= "                     <td align=\"center\">\n";
        $html .= "                        <a title='farmacia'>PROVEEDOR:<a>";
        $html .= "                      </td>\n";
        $html .= "                       <td align=\"left\" class=\"modulo_list_claro\" class=\"label_mark\">\n";
        $html .= "                          " . $nombre_tercero;
        $html .= "                       </td>\n";
        $html .= "                     <tr>\n";
        $html .= "                   <tr class=\"modulo_table_list_title\">\n";
        $html .= "                      <td align=\"center\">\n";
        $html .= "                         EMPRESA ";
        $html .= "                       </td>\n";
        $html .= "                      <td   colspan=\"3\"  align=\"left\" class=\"modulo_list_claro\">\n";
        $html .= "                        " . $razon_social;
        $html .= "                       </td>\n";
        $html .= "                     <tr>\n";
        $html .= "                        <td rowspan='1' colspan='10' align=\"center\" class=\"modulo_list_claro\"> \n";
        $html .= "                          <fieldset>";
        $html .= "                           <legend>OBSERVACIONES</legend>";
        $html .= "                              <TEXTAREA id='observar' ROWS='2' COLS=55 ></TEXTAREA>\n";
        $html .= "                        </td>\n";
        $html .= "                     </tr>\n";
        $html .= "                          </fieldset>";
        $html .= "</table><br>\n";
        $html .= "			<table   width=\"30%\" align=\"center\" border=\"0\"   >";
        $html .= "  <tr>\n";
        $html .= "	             	<td  colspan=\"10\"  align='center'>\n";
        $html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnCrear\"   value=\"CREAR DOCUMENTO\" onclick=\"GrabarDocumentosOrden(document.Forma17);\" >\n";
        $html .= " </td>\n";
        $html .= "		</tr>\n";
        $html .= "</table><br>\n";
        $html .= ThemeCerrarTabla();
        return $html;
    }

    /*
     * Funcion donde se crea la forma para el documento de pedido por orden de compra
     * @param array $action vector que contiene los link de la aplicacion
     * @return string $html retorna la cadena con el codigo html de la pagina
     */

    function FormaDocumentoDePedido($action, $id, $empresa, $nombre_tercero, $tipo_id_tercero, $tercero_id, $razon_social, $numero)
    {
        $html .= ThemeAbrirTabla('DOCUMENTO DE ORDEN DE COMPRA ');
        $html .= "<form name=\"Forma18\" id=\"Forma18\"  method=\"post\" >\n";
        $html .= "                 <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "                   <tr class=\"modulo_table_list_title\">\n";
        $html .= "                      <td align=\"center\">\n";
        $html .= "                         IDENTIFICACION ";
        $html .= "                       </td>\n";
        $html .= "                      <td align=\"left\" class=\"modulo_list_claro\">\n";
        $html .= "                        " . $tipo_id_tercero . " " . $tercero_id . " ";
        $html .= "                       </td>\n";
        $html .= "                     <td align=\"center\">\n";
        $html .= "                        <a title='farmacia'>PROVEEDOR:<a>";
        $html .= "                      </td>\n";
        $html .= "                       <td align=\"left\" class=\"modulo_list_claro\" class=\"label_mark\">\n";
        $html .= "                          " . $nombre_tercero;
        $html .= "                       </td>\n";
        $html .= "                     <tr>\n";
        $html .= "</table>\n";
        $html .= "<br>\n";
        $html .= "                 <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "                   <tr class=\"modulo_table_list_title\">\n";
        $html .= "                      <td align=\"center\">\n";
        $html .= "                         SE CREO EL DOCUMENTO DE PRODUCTO PENDIENTES No " . $id . "  ";
        $html .= "                       </td>\n";
        $html .= "                     <tr>\n";
        $html .= "                   <tr class=\"modulo_table_list_title\">\n";
        $html .= "                      <td align=\"center\">\n";
        $html .= "                         SE CREO LA ORDEN   ..DE PEDIDO No" . $numero . "  ";
        $html .= "                       </td>\n";
        $html .= "                     <tr>\n";
        $html .= "</table>\n";
        $html .= "<table align=\"center\" width=\"50%\">\n";
        $html .= "  <tr>\n";
        $html .= "    <td align=\"center\">\n";
        $html .= "      <a href=\"" . $action['volver'] . "\"  class=\"label_error\">\n";
        $html .= "       VOLVER \n";
        $html .= "      </a>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= ThemeCerrarTabla();
        return $html;
    }

    /*
     * Funcion donde se crea la forma  para unificar las ordenes de compra
     * @param array $action vector que contiene los link de la aplicacion
     * @return string $html retorna la cadena con el codigo html de la pagina
     */

    function FormaUnificarOrdenes($action, $dat)
    {
        $html = "  <script>\n";
        $html .="    function ValidarInformaOrdenCompra(frm)";
        $html .="    {";
        $html .= " 	   xajax_InformacionOrdenComp(document.getElementById('proveedor').value,'" . $preorden_id . "',document.getElementById('empresa').value,'" . $empresa . "'); ";
        $html .= "      return;\n";
        $html .="   }";
        $html .= " function ValidarDtos(frm,preorden_id,orden_pedido_id,empresa)";
        $html .= " {";
        $html .= "    xajax_TrasferirInformacion(frm.observar.value,preorden_id,orden_pedido_id,empresa); ";
        $html .= "      return;\n";
        $html .="   }";
        $html .= " function unificarOrdenPedido(proveedor)";
        $html .= "{";
        $html .="     xajax_unificarTodasOrdenes(proveedor); ";
        $html .= "    return;";
        $html .= " }";
        $html .="  </script>\n";
        $html .= ThemeAbrirTabla('UNIFICAR ORDENES DE COMPRA POR PROVEEDOR');
        $html .= "<form name=\"FormaDetalle\" id=\"FormaDetalle\"   method=\"post\" >\n";
        $html .= "			<table   width=\"35%\"  class=\"modulo_table_list\" align=\"center\" border=\"0\"  >";
        $html .= "		</tr>\n";
        $html .= "   <tr  class=\"formulacion_table_list\"> \n";
        $html .= "			<td align=\"center\"  ><b> PROVEEDOR:</B></td>\n";
        $html .= "			<td  class=\"modulo_list_claro\" colspan=\"6\">\n";
        $html .= "					<select name=\"proveedor_id\" class=\"select\" onchange=\"xajax_MostrarOrdenesCompra(xajax.getFormValues('FormaDetalle'))\">\n";
        $html .= "             	<option value = '-1'>--  SELECCIONE --</option>\n";
        $csk = "";
        foreach ($dat as $indice => $valor)
        {
            if ($valor['codigo_proveedor_id'] == $request['codigo_proveedor_id'])
                $sel = "selected";
            else
                $sel = "";
            $html .= "  <option value=\"" . $valor['codigo_proveedor_id'] . "\" " . $sel . ">" . $valor['nombre_tercero'] . "</option>\n";
        }
        $html .= "                </select>\n";
        $html .= "						  </td>\n";
        $html .= "	 </tr>\n";
        $html .= "</table><br>\n";
        $html .= "				    </form>\n";
        $html .= "<table  width=\"60%\"   align=\"center\">\n";
        $html .= "  <tr>\n";
        $html .= "      <td colspan=\"15\"><div id=\"cargarOrden\"></div></td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= "<br> ";
        $html .= "<table  width=\"60%\"   align=\"center\">\n";
        $html .= "  <tr>\n";
        $html .= "      <td colspan=\"15\"><div id=\"Proveedor\"></div></td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= "<br> ";
        $html .= "<table  width=\"100%\"   align=\"center\">\n";
        $html .= "  <tr>\n";
        $html .= "      <td colspan=\"15\"><div id=\"Detalleorden\"></div></td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= "<table align=\"center\" width=\"50%\">\n";
        $html .= "  <tr>\n";
        $html .= "    <td align=\"center\">\n";
        $html .= "      <a href=\"" . $action['volver'] . URLRequest(array("bodega" => $bod)) . "\"  class=\"label_error\">\n";
        $html .= "       VOLVER \n";
        $html .= "      </a>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= $this->CrearVentana(650, "MENSAJE");
        $html .= ThemeCerrarTabla();
        return $html;
    }

    /**
     * Funcion donde se crea la Forma del Mensaje de aviso si se ha ingresado correctamente o no los datos
     * @param array $action vector que contiene los link de la aplicacion.
     * @param string $msg1 Cadena con el texto del mensaje a mostrar  en pantalla.
     * @return string $html retorna la cadena con el codigo html de la pagina.
     */
    function FormaMensaje($action, $msg1 = null, $msg1 = null, $datos)
    {
        $html = ThemeAbrirTabla("MENSAJE");
        $html .= " <form name=\"form8\" method=\"post\" enctype=\"multipart/form-data\" >";
        $html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
        $html .= "	<tr>\n";
        $html .= "		<td>\n";
        $html .= "		  <table width=\"100%\" class=\"modulo_table_list\">\n";
        $html .= "		    <tr class=\"normal_10AN\">\n";
        $html .= "		      <td align=\"center\">\n" . $msg1 . "</td>\n";
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
        return $html;
    }

    /*     * ************************************** */

    function FormaDivididaPre_ordenC($action, $dat, $empresa, $datos, $productos, $productos_preorden, $preorden_id, $conteo, $pagina)
    {
        $ctl = AutoCarga::factory("ClaseUtil");
        $sql = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
        $html .= $ctl->IsDate("-");
        $html .= $ctl->AcceptDate("-");
        $html = $ctl->IsNumeric();
        $html .= $ctl->AcceptNum(true);

        $html .= $ctl->RollOverFilas();
        $laboratorios_todos = $sql->ProveedoresProducto($productos[$i]['laboratorio']);
        $html .= ThemeAbrirTabla("SOLICITUDES DE GERENCIA ");
        $pghtml = AutoCarga::factory('ClaseHTML');
        $ctl = AutoCarga::factory("ClaseUtil");
        $html .= $ctl->RollOverFilas();
        $html .= $ctl->LimpiarCampos();
        /* OPTIONS PARA TODOS LOS TERCEROS */
        foreach ($laboratorios_todos as $llave => $valor_lab)
        {
            $option_todos .= "<option value=\"" . $valor_lab['codigo_proveedor_id'] . "\">" . $valor_lab['tercero'] . "</option>";
        }
        /* FIN OPTIONS */

        $html .= "<script>";
        $html .= "	function aparecer(id)";
        $html .= "	{";
        $html .= "		document.getElementById(id).style.display=\"\";";
        $html .= "	}";
        $html .= "	function esconder(id)";
        $html .= "	{";
        $html .= "		document.getElementById(id).style.display=\"none\";";
        $html .= "	}";
        $html .= "</script>";

        /*
         * Primer Tab Para Pantallas despues de Rotacion
         */
        $html .= "	<table width=\"100%\" align=\"center\">\n";
        $html .= "		<tr>\n";
        $html .= "			<td>\n";
        $html .= "				<table width=\"100%\" align=\"center\">\n";
        $html .= "					<tr>\n";
        $html .= "						<td>\n";
        $html .= "							<div class=\"tab-pane\" id=\"compras_rotaciones\">\n";
        $html .= "								<script>	tabPane = new WebFXTabPane( document.getElementById( \"compras_rotaciones\" )); </script>\n";
        $html .= "								<div class=\"tab-page\" id=\"pre_solicitudes\">\n";
        $html .= "									<h2 class=\"tab\">PRE- SOLICITUDES DE COMPRA</h2>\n";
        $html .= "									<script>	tabPane.addTabPage( document.getElementById(\"pre_solicitudes\")); </script>\n";


        /*
         * BUSCADOR
         */
        $html .= "<form name=\"buscador\" id=\"buscador\" method=\"post\" action=\"" . $action['paginador'] . "\">";
        $html .= "<table width=\"100%\" class=\"modulo_table_list\" border=\"0\"  align=\"center\" rules=\"all\">";
        $html .= "		<tr class=\"formulacion_table_list\">";
        $html .= "			<td colspan=\"7\">";
        $html .= "				BUSCADOR";
        $html .= "			</td>";
        $html .= "		</tr>";
        $html .= "		<tr class=\"formulacion_table_list\">";
        $html .= "			<td>";
        $html .= "				MOLECULA";
        $html .= "			</td>";
        $html .= "			<td>";
        $html .= "				<input type=\"text\" value=\"" . $_REQUEST['buscador']['molecula'] . "\" name=\"buscador[molecula]\" id=\"buscador[molecula]\" class=\"input-text\" style=\"width:100%\" onkeyup=\"this.value=this.value.toUpperCase();\">";
        $html .= "			</td>";
        $html .= "			<td>";
        $html .= "				CONCENTRACION";
        $html .= "			</td>";
        $html .= "			<td>";
        $html .= "				<input type=\"text\" value=\"" . $_REQUEST['buscador']['concentracion'] . "\" name=\"buscador[concentracion]\" id=\"buscador[concentracion]\" class=\"input-text\" style=\"width:100%\" onkeyup=\"this.value=this.value.toUpperCase();\">";
        $html .= "			</td>";
        $html .= "			<td>";
        $html .= "				PRESENTACION";
        $html .= "			</td>";
        $html .= "			<td>";
        $html .= "				<input type=\"text\" value=\"" . $_REQUEST['buscador']['unidad'] . "\" name=\"buscador[unidad]\" id=\"buscador[unidad]\" class=\"input-text\" style=\"width:100%\" onkeyup=\"this.value=this.value.toUpperCase();\">";
        $html .= "			</td>";
        $html .= "			<td>";
        $html .= "				<input type=\"submit\" value=\"BUSCAR\" class=\"input-submit\" style=\"width:100%\">";
        $html .= "			</td>";
        $html .= "		</tr>";
        $html .= "</table>";
        $html .= "</form>";
        $html .="<br>";
        $html .= $pghtml->ObtenerPaginado($conteo, $pagina, $action['paginador']);
        $html .= "<form name=\"proveedores_orden\" id=\"proveedores_orden\"  action=\"" . $action['pre_orden'] . "\" method=\"post\" >\n";
        $html .= "		<table width=\"100%\" class=\"modulo_table_list\" border=\"0\"  align=\"center\" rules=\"all\">";
        $html .= "			<tr  class=\"formulacion_table_list\">";
        $html .= "				<td align=\"center\">";
        $html .= "					<input type=\"submit\" value=\"GUARDAR PRE-ORDEN\" class=\"input-submit\" style=\"width:30%\">";
        $html .= "				</td>";
        $html .= "			</tr>";
        $html .= "		</table>";
        $html .= "	<br>";
        $cantidad = count($productos);

        $k = 0;
        foreach ($dat as $key => $dtl2)
        {
            $html .= "  <table width=\"100%\" class=\"modulo_table_list\" border=\"0\"  align=\"center\">";
            $html .= "  	<tr class=\"formulacion_table_list\" >\n";
            $html .= "      	<td width=\"80%\">PRODUCTO</td>\n";
            $html .= "      	<td width=\"10%\">CANTIDAD</td>\n";

            $html .= "  	</tr>\n";
            $est = "modulo_list_claro";
            $back = "#DDDDDD";
            $html .= "  	<tr  class=\"modulo_list_claro\" onmouseout=mOut(this,\"" . $back . "\"); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
            $html .= "      	<td align=\"left\">" . $dtl2['molecula'] . "::: " . $dtl2['subclase_id'] . "-" . $dtl2['contenido_unidad_venta'] . "-" . $dtl2['unidad_id'] . "</td>\n";
            $html .= "      	<td align=\"center\" class=\"label_error\">" . FormatoValor($dtl2['cantidad']) . "</td>\n";
            $html .= "  	</tr>\n";
            $html .= "  <tr class=\"modulo_list_claro\" onmouseout=mOut(this,\"" . $back . "\"); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
            $html .= "		<td colspan=\"2\">";
            $html .= "			<table rules=\"all\" class=\"modulo_table_list\" width=\"100%\">";
            $html .= "				<tr class=\"formulacion_table_list\">";
            $html .= "					<td width=\"10%\">";
            $html .= "						COD PROD.";
            /* $html .= "						<input type=\"hidden\" name=\"\">"; */
            $html .= "					</td>";
            $html .= "					<td width=\"50%\">";
            $html .= "						DESCRIPCION";
            $html .= "					</td>";
            $html .= "					<td width=\"10%\">";
            $html .= "						ULT.COMPRA";
            $html .= "					</td>";
            $html .= "					<td width=\"4%\">";
            $html .= "						IVA";
            $html .= "					</td>";
            $html .= "					<td width=\"5%\">";
            $html .= "						EXIST";
            $html .= "					</td>";
            $html .= "					<td width=\"10%\">";
            $html .= "						CANT";
            $html .= "					</td>";
            $html .= "					<td width=\"15%\">";
            $html .= "						PROV.";
            $html .= "					</td>";
            $html .= "					<td width=\"15%\">";
            $html .= "						OP";
            $html .= "					</td>";
            $html .= "				</tr>";
            for ($i = 0; $i < $cantidad; $i++)
            {

                if (strcmp($productos[$i]['molecula'], $dtl2['molecula']) == 0)
                {
                    $laboratorios = $sql->ProveedoresProducto($productos[$i]['laboratorio']);
                    $option = "";
                    foreach ($laboratorios as $key => $valor)
                    {
                        $option .= "<option value=\"" . $valor['codigo_proveedor_id'] . "\">" . $valor['tercero'] . "</option>";
                    }

                    if (empty($laboratorios))
                    {
                        $option = $option_todos;
                    }
                    $html .= "				<tr>";
                    $html .= "					<td>";
                    $html .= "						" . $productos[$i]['codigo_producto'];
                    /* $html .= "						<input type=\"hidden\" name=\"codigo_producto".$k."\" id=\"codigo_producto".$k."\" value=\"".$productos[$i]['codigo_producto']."\">"; */
                    $html .= "					</td>";
                    $html .= "					<td>";
                    $html .= "						" . $productos[$i]['producto'];
                    $html .= "					</td>";
                    $html .= "					<td>";
                    $html .= "						<input " . $productos[$i]['seleccionado'] . " type=\"text\" name=\"valor_unitario" . $k . "\" id=\"valor_unitario" . $k . "\" class=\"input-text\" style=\"width:100%\" value=\"" . $productos[$i]['costo_ultima_compra'] . "\" onkeypress=\"return acceptNum(event)\"> ";
                    $html .= "					</td>";
                    $html .= "					<td>";
                    $html .= "						<input " . $productos[$i]['seleccionado'] . " type=\"text\" name=\"porc_iva" . $k . "\" id=\"porc_iva" . $k . "\" class=\"input-text\" style=\"width:100%\" value=\"" . $productos[$i]['porc_iva'] . "\" onkeypress=\"return acceptNum(event)\"> ";
                    $html .= "					</td>";
                    $html .= "					<td>";
                    $html .= "						" . FormatoValor($productos[$i]['existencia'], 0);
                    $html .= "					</td>";
                    $html .= "					<td>";
                    $html .= "						<input " . $productos[$i]['seleccionado'] . " type=\"text\" name=\"cantidad" . $k . "\" id=\"cantidad" . $k . "\" class=\"input-text\" style=\"width:100%\" value=\"" . FormatoValor($productos[$i]['total']) . "\" onkeypress=\"return acceptNum(event)\"> ";
                    $html .= "					</td>";
                    $html .= "					<td>";
                    $html .= "						<select " . $productos[$i]['seleccionado'] . " style=\"width:100%\" class=\"select\" name=\"codigo_proveedor_id" . $k . "\" id=\"codigo_proveedor_id" . $k . "\">";
                    $html .= "						" . $option;
                    $html .= "						</select>";
                    $html .= "					</td>";
                    $html .= "					<td align=\"center\">";
                    $html .= "						<input " . $productos[$i]['seleccionado'] . " type=\"checkbox\" class=\"checkbox\" name=\"" . $k . "\" id=\"" . $k . "\" value=\"" . $productos[$i]['codigo_producto'] . "\">";
                    $html .= "					</td>";
                    $html .= "				</tr>";
                    $total = $total + $productos[$i]['total'];
                    $k++;
                }
            }
            $html .= "				<tr>";
            $html .= "					<td colspan=\"3\">";
            $html .= "					</td>";
            $html .= "					<td colspan=\"2\" class=\"normal_10AN\">";
            $html .= "					<b>EN PRE-ORDEN:</b>";
            $html .= "					</td>";
            $html .= "					<td class=\"label_error\" align=\"center\">";
            $html .= "						" . FormatoValor($total);
            $total = 0;
            $html .= "					</td>";
            $html .= "					</tr>";
            $html .= "			</table>";
            $html .= "		</td>";
            $html .= "  </tr>\n";
            $html .= "	</table>";
            $html .= "<br>";
        }
        $html .= "		<table width=\"100%\" class=\"modulo_table_list\" border=\"0\"  align=\"center\" rules=\"all\">";
        $html .= "			<tr  class=\"formulacion_table_list\">";
        $html .= "				<td align=\"center\">";
        $html .= "					<input type=\"submit\" value=\"GUARDAR PRE-ORDEN\" class=\"input-submit\" style=\"width:30%\">";
        $html .= "					<input type=\"hidden\" name=\"registros\" id=\"registros\" value=\"" . $k . "\">";
        $html .= "					<input type=\"hidden\" name=\"guardar\" id=\"guardar\" value=\"1\">";
        $html .= "					<input type=\"hidden\" name=\"offset\" id=\"offset\" value=\"" . $_REQUEST['offset'] . "\">";
        $html .= "				</td>";
        $html .= "			</tr>";
        $html .= "		</table>";
        $html .= "</form>";
        $html .= $pghtml->ObtenerPaginado($conteo, $pagina, $action['paginador']);
        $html .= "								  </div>\n";

        /* APERTURA DEL SEGUNDO TAB */
        $html .= "								<div class=\"tab-page\" id=\"crear_compras\">\n";
        $html .= "									<h2 class=\"tab\">CREAR ORDENES DE COMPRA</h2>\n";
        $html .= "									<script>	tabPane.addTabPage( document.getElementById(\"crear_compras\")); </script>\n";
        $html .= "	<center>";
        $html .= "<form name=\"crear_compras\"  id=\"crear_compras\" method=\"POST\" action=\"" . $action['crear_compras'] . "\">";
        $html .= "	<input type=\"hidden\" name=\"preorden_id\" id=\"preorden_id\" value=\"" . $preorden_id . "\">";
        $html .= "	<input type=\"submit\" value=\"CREAR ORDENES DE COMPRA\" class=\"input-submit\">";
        $html .= "</form>";
        $html .= "	</center>";
        $html .= "<table width=\"100%\" class=\"modulo_table_list\" border=\"0\"  align=\"center\" rules=\"all\">";
        $j = 0;
        foreach ($productos_preorden as $ll => $val)
        {

            $est = "modulo_list_claro";
            $back = "#DDDDDD";
            $html .= "  <tr  class=\"modulo_list_claro\" onmouseout=mOut(this,\"" . $back . "\"); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
            $html .= "		<td class=\"normal_10AN\">";
            $html .= "			<li>" . $ll . "</li>";
            $html .= "		</td>";
            $html .= "		<td class=\"label_error\" align=\"center\">";
            $html .= "			<a onclick=\"aparecer('Tabla" . $j . "');\">";
            $html .= "				<b><img title=\"Mostrar Informacion\" src=\"" . GetThemePath() . "/images/abajo.png\" border=\"0\"></b>";
            $html .= "			</a>";
            $html .= "		</td>";
            $html .= "		<td class=\"label_error\" align=\"center\">";
            $html .= "			<a onclick=\"esconder('Tabla" . $j . "');\">";
            $html .= "				<b><img title=\"EsConder Informacion\" src=\"" . GetThemePath() . "/images/arriba.png\" border=\"0\"></b>";
            $html .= "			</a>";
            $html .= "		</td>";
            $html .= "	</tr>";
            $html .= "	<tr id=\"Tabla" . $j . "\" style=\"display:none;\" >";
            $html .= "		<td colspan=\"3\">";
            $html .= "			<table width=\"100%\" class=\"modulo_table_list\" border=\"0\"  align=\"center\" rules=\"all\">";
            $html .= "				<tr  class=\"formulacion_table_list\">";
            $html .= "					<td width=\"10%\">";
            $html .= "						CODIGO PRODUCTO";
            $html .= "					</td>";
            $html .= "					<td width=\"40%\">";
            $html .= "						DESCRIPCION";
            $html .= "					</td>";
            $html .= "					<td width=\"5%\">";
            $html .= "						CANTIDAD ";
            $html .= "					</td>";
            $html .= "					<td width=\"5%\">";
            $html .= "						VALOR U.";
            $html .= "					</td>";
            $html .= "					<td width=\"5%\">";
            $html .= "						%IVA";
            $html .= "					</td>";
            $html .= "					<td width=\"5%\">";
            $html .= "						SUBTOTAL";
            $html .= "					</td>";
            $html .= "					<td width=\"5%\">";
            $html .= "						OP";
            $html .= "					</td>";
            $html .= "				</tr>";
            foreach ($val as $ll2 => $val2)
            {
                foreach ($val2 as $ll3 => $val3)
                {
                    $subtotal_compra = ($val3['valor_unitario'] * $val3['cantidad']);
                    $subtotal_iva = $subtotal_compra * ($val3['porc_iva'] / 100);
                    $est = "modulo_list_claro";
                    $back = "#DDDDDD";
                    $html .= "				<tr class=\"modulo_list_claro\" onmouseout=mOut(this,\"" . $back . "\"); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
                    $html .= "					<td>";
                    $html .= "						" . $val3['codigo_producto'];
                    $html .= "					</td>";
                    $html .= "					<td>";
                    $html .= "						" . $val3['producto'];
                    $html .= "					</td>";
                    $html .= "					<td>";
                    $html .= "						" . FormatoValor($val3['cantidad']);
                    $html .= "					</td>";
                    $html .= "					<td>";
                    $html .= "						" . FormatoValor($val3['valor_unitario'], 2);
                    $html .= "					</td>";
                    $html .= "					<td>";
                    $html .= "						" . FormatoValor($val3['porc_iva']);
                    $html .= "					</td>";
                    $html .= "					<td>";
                    $html .= "						" . FormatoValor($subtotal_compra, 2);
                    $html .= "					</td>";
                    $html .= "					<td align=\"center\">";
                    $url = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "Ordenes_Compras", array("codigo_proveedor_id" => $val3['codigo_proveedor_id'], "codigo_producto" => $val3['codigo_producto'], "eliminar" => "1", "id" => "Tabla" . $j . ""));
                    $html .= "						<a href=\"" . $url . "\">";
                    $html .= "						<img title=\"Eliminar Item\" src=\"" . GetThemePath() . "/images/delete2.gif\" border=\"0\">";
                    $html .= "						</a>";
                    $html .= "					</td>";
                    $html .= "				</tr>";
                    $subtotal_prov = $subtotal_prov + $subtotal_compra;
                    $subtotal_iva_prov = $subtotal_iva_prov + $subtotal_iva;
                    $subtotal_compra = 0;
                    $subtotal_iva = 0;
                }
            }
            $html .= "				<tr  class=\"modulo_list_claro\" >";
            $html .= "					<td colspan=\"5\" class=\"normal_10AN\" align=\"right\">";
            $html .= "						SUBTOTAL:";
            $html .= "					</td>";
            $html .= "					<td class=\"label_error\" colspan=\"2\">";
            $html .= "						$" . FormatoValor($subtotal_prov, 2);
            $html .= "					</td>";
            $html .= "				</tr>";
            $html .= "				<tr  class=\"modulo_list_claro\" >";
            $html .= "					<td colspan=\"5\" class=\"normal_10AN\" align=\"right\">";
            $html .= "						IVA:";
            $html .= "					</td>";
            $html .= "					<td class=\"label_error\" colspan=\"2\">";
            $html .= "						$" . FormatoValor($subtotal_iva_prov, 2);
            $html .= "					</td>";
            $html .= "				</tr>";
            $html .= "				<tr  class=\"modulo_list_claro\" >";
            $html .= "					<td colspan=\"5\" class=\"normal_10AN\" align=\"right\">";
            $html .= "						TOTAL:";
            $html .= "					</td>";
            $html .= "					<td class=\"label_error\" colspan=\"2\">";
            $html .= "						$" . FormatoValor(($subtotal_prov + $subtotal_iva_prov), 2);
            $html .= "					</td>";
            $html .= "				</tr>";
            $html .= "			</table>";
            $html .= "		</td>";
            $html .= "	</tr>";
            $j++;
            $subtotal_iva_prov = 0;
            $subtotal_prov = 0;
        }
        $html .= "</table>";

        /* CIERRE DE TABS */
        $html .= "								</div>\n";
        $html .= "							</div>\n";
        $html .= "						</td>\n";
        $html .= "					</tr>\n";
        $html .= "				</table>\n";
        $html .= "			</td>\n";
        $html .= "		</tr>\n";
        $html .= "  </table>\n";

        $html .= "<table align=\"center\" width=\"50%\">\n";
        $html .= "  <tr>\n";
        $html .= "    <td align=\"center\">\n";
        $html .= "      <a href=\"" . $action['volver'] . "\"  class=\"label_error\">\n";
        $html .= "       VOLVER \n";
        $html .= "      </a>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";

        if ($_REQUEST['eliminar'] == '1')
        {
            $html .= "	<script>";
            $html .= "		aparecer('" . $_REQUEST['id'] . "');";
            $html .= "	</script>";
        }
        $html .= ThemeCerrarTabla();
        return $html;
    }

    function FormaMensaje_preorden($action, $compras)
    {
        $html = ThemeAbrirTabla("COMPRAS - ROTACION");

        $html .= "  <table width=\"100%\" class=\"modulo_table_list\" align=\"center\">";
        $html .= "	  <tr align=\"CENTER\"    class=\"formulacion_table_list\" >\n";
        $html .= "      <td  width=\"5%\">NUMERO.</td>\n";
        $html .= "      <td  width=\"10%\">IDENTIFICACION.</td>\n";
        $html .= "      <td  width=\"30%\">PROVEEDOR.</td>\n";
        $html .= "      <td  width=\"25%\">OBSERVACION</td>\n";
        $html .= "      <td  width=\"15%\">USUARIO</td>\n";
        $html .= "      <td  width=\"35%\">FECHA REGISTRO</td>\n";
        $html .= "	    <td  width=\"10%\">PDF</td>\n";
        $html .= "	    <td  width=\"10%\">HTML</td>\n";
        $html .= "  </tr>\n";

        $xml = Autocarga::factory("ReportesCsv");
        $reporte = new GetReports();

        $est = "modulo_list_claro";
        $back = "#DDDDDD";
        foreach ($compras as $key => $dtl)
        {
            $html .= "	  <tr  align=\"CENTER\" class=\"" . $est . "\" >\n";
            $html .= "      <td   align=\"center\">" . $dtl['orden_pedido_id'] . "</td>\n";
            $html .= "      <td   align=\"center\">" . $dtl['tipo_id_tercero'] . "   " . $dtl['tercero_id'] . "</td>\n";
            $html .= "      <td   align=\"center\">" . $dtl['nombre_tercero'] . "</td>\n";
            $html .= "      <td align=\"left\">" . $dtl['observacion'] . "</td>\n";
            $html .= "      <td align=\"left\">" . $dtl['nombre'] . "</td>\n";
            $html .= "      <td align=\"left\">" . $dtl['fecha_registro'] . "</td>\n";

            $datos2['orden_pedido_id'] = $dtl['orden_pedido_id'];
            $dato2s['tipo_id_tercero'] = $dtl['tipo_id_tercero'];
            $datos2['tercero_id'] = $dtl['tercero_id'];
            $datos2['empresa_id'] = $dtl['empresa_id'];
            $datos2['codigo_proveedor_id'] = $dtl['codigo_proveedor_id'];
            $datos2['codigo_unidad_negocio'] = $request['codigo_unidad_negocio'];
            $html .= $xml->GetJavacriptReporteFPDF('app', 'Compras_Orden_Compras', 'InformeOrdenPedido', $datos2, array("interface" => 5));
            $fnc1 = $xml->GetJavaFunction();
            $html .= "    <td>\n";
            $html .= "        <a href=\"javascript:" . $fnc1 . "\" title=\"IMPRIMIR ORDEN\">\n";
            $html .= "          <img src=\"" . GetThemePath() . "/images/imprimir.png\" border=\"0\">\n";
            $html .= "        </a>\n";
            $html .= "    </td>\n";

            $mostrar = $reporte->GetJavaReport('app', 'Compras_Orden_Compras', 'OrdenCompra', array("orden_pedido_id" => $dtl['orden_pedido_id'], "codigo_unidad_negocio" => $request['codigo_unidad_negocio']), array('rpt_name' => '', 'rpt_dir' => 'cache', 'rpt_rewrite' => TRUE));
            $funcion = $reporte->GetJavaFunction();

            $html .= "		<td align=\"center\" >\n";
            $html .= "				" . $mostrar . "\n";
            $html .= "			<a href=\"javascript:$funcion\" class=\"label_error\"  title=\"REPORTE DEL PEDIDO\"><img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0' >\n";
            $html .= "			</a></center>\n";
            $html .= "		</td>\n";
            $html .= "		</tr>\n";
        }

        $html .= "	</table><br>\n";


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
        $html .= ThemeCerrarTabla();
        return $html;
    }

}

?>