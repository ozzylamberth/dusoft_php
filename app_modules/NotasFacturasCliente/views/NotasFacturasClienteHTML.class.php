<?php

/* * *******************************************************
 * @package DUANA & CIA
 * @version 1.0 $Id: NotasFacturasClienteHTML.class
 * @copyright DUANA & CIA DIC-2013
 * @author L.G.T.L
 * ******************************************************** */

/* * *********************************************************
 * Clase Vista: NotasFacturasClienteHTML
 * Clase Contiene menus de modulo 
 * ********************************************************** */

class NotasFacturasClienteHTML {
    /*     * ******************************************************
     * Constructor de la clase
     * ****************************************************** */

    function NotasFacturasClienteHTML()
    {
        
    }

    /*     * ******************************************************
     * Listado de Documentos de la Empresa
     * ****************************************************** */

    function Menu($action)
    {
        $html = ThemeAbrirTabla('MENÚ');
        $html .= "<table width=\"60%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
        $html .= "  <tr class=\"modulo_table_list_title\">\n";
        $html .= "     <td align=\"center\">MENU\n";
        $html .= "     </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr  class=\"modulo_list_oscuro\">\n";
        $html .= "      <td class=\"label\" align=\"center\">\n";
        $html .= "        <a href=\"" . $action['BuscarFacturaNotaCredito'] . "&tiponotacredito=valor\">CREAR NOTAS - CRÉDITO POR VALOR</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
         $html .= "  <tr  class=\"modulo_list_oscuro\">\n";
        $html .= "      <td class=\"label\" align=\"center\">\n";
        $html .= "        <a href=\"" . $action['BuscarFacturaNotaCredito'] . "&tiponotacredito=devolucion\">CREAR NOTAS - CRÉDITO POR DEVOLUCION</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr  class=\"modulo_list_oscuro\">\n";
        $html .= "      <td class=\"label\" align=\"center\">\n";
        $html .= "        <a href=\"" . $action['BuscarFacturaNotaDebito'] . "\">CREAR NOTAS - DÉBITO</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr  class=\"modulo_list_oscuro\">\n";
        $html .= "      <td class=\"label\" align=\"center\">\n";
        $html .= "        <a href=\"" . $action['BuscarNotaCredito'] . "\">NOTAS CRÉDITO - CONSULTAR</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr  class=\"modulo_list_oscuro\">\n";
        $html .= "      <td class=\"label\" align=\"center\">\n";
        $html .= "        <a href=\"" . $action['BuscarNotaDebito'] . "\">NOTAS DÉBITO - CONSULTAR</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= ThemeCerrarTabla();
        return $html;
    }

    function BuscadorFactura($action, $tipo, $factura, $datosFactura, $tiponotacredito)
    {
        $html .= ThemeAbrirTabla('NOTAS ' . $tipo);
        $html .= "<form name=\"buscadorfacturas\" action=\"" . $action['BuscarFactura'] . "\" method=\"post\">\n";
        $html .= "<input type='hidden' value='{$tiponotacredito}' name='tiponotacredito' /> ";
        $html .= "  <table class=\"modulo_table_list\" align=\"center\">\n";
        $html .= "      <tr>\n";
        $html .= "          <td class=\"modulo_table_list_title\">FACTURA:</td>\n";
        $html .= "          <td>\n";
        $html .= "              <input type=\"text\" class=\"input-text\" name=\"factura\" size=\"25\" value=\"" . $factura . "\">\n";
        $html .= "          </td>\n";
        $html .= "          <td>\n";
        $html .= "              <input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
        $html .= "          </td>\n";
        $html .= "      </tr>\n";
        $html .= "  </table>\n";
        $html .= "</form>\n";
        $html .= "  <br><br>\n";
        if ($factura && $datosFactura)
        {
            $html .= "  <table class=\"modulo_table_list\" align=\"center\">\n";
            $html .= "      <tr>\n";
            $html .= "          <td class=\"modulo_table_list_title\">FACTURA</td>\n";
            $html .= "          <td class=\"modulo_table_list_title\">PREFIJO</td>\n";
            $html .= "          <td class=\"modulo_table_list_title\">TERCERO</td>\n";
            $html .= "          <td class=\"modulo_table_list_title\">IDENTIFICACIÓN</td>\n";
            $html .= "          <td class=\"modulo_table_list_title\">VALOR</td>\n";
            $html .= "          <td class=\"modulo_table_list_title\">SALDO</td>\n";
            $html .= "          <td class=\"modulo_table_list_title\">FECHA</td>\n";
            $html .= "      </tr>\n";
            for ($i = 0; $i < count($datosFactura); $i++)
            {
                $html .= "      <tr class=\"modulo_list_claro\">\n";
                $html .= "          <td><a href=\"" . $action['CrearNota'] . "\" title=\"CREAR NOTA TEMPORAL\">" . $datosFactura[$i]['factura_fiscal'] . "</a></td>\n";
                $html .= "          <td>" . $datosFactura[$i]['prefijo'] . "</td>\n";
                $html .= "          <td>" . $datosFactura[$i]['nombre_tercero'] . "</td>\n";
                $html .= "          <td>" . $datosFactura[$i]['tipo_id_tercero'] . " - " . $datosFactura[$i]['tercero_id'] . "</td>\n";
                $html .= "          <td>$ " .FormatoValor( $datosFactura[$i]['valor_total']) . "</td>\n";
                $html .= "          <td>$ " . FormatoValor($datosFactura[$i]['saldo']) . "</td>\n";
                $html .= "          <td>" . $datosFactura[$i]['fecha_registro'] . "</td>\n";
                $html .= "      </tr>\n";
            }
            $html .= "  </table>\n";
            $html .= "  <br><br><br><br>\n";
        }
        elseif ($factura && empty($datosFactura))
        {
            $html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
            $html .= "  <br><br>\n";
        }
        $html .= "<table align=\"center\">\n";
        $html .= "  <tr>\n";
        $html .= "      <td align=\"center\" class=\"label_error\">\n";
        $html .= "        <a href=\"" . $action['volver'] . "\">VOLVER</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= ThemeCerrarTabla();
        return $html;
    }

    function FormaCrearNotaCredito($action, $factura, $datosFactura, $detalleFactura, $tmp_nota_credito_despacho_cliente_id, $totalItemsFactura, $tipo, $diferenciaDias, $cantidadDetallesTemporales, $cantidadValoresTotalProductoDevuelto, $cantidadTmpDetalleNotaCreditoDespachoClienteId, $calculofactura, $conceptos)
    {
        $html = "<script>\n";
        $html .= "  cantidadinvalidos = [];"; 
        $html .= "  function ValidarDiferenciaDias()\n";
        $html .= "  {\n";
        $html .= "      alert(\"ESTA FACTURA FUE CREADA HACE MÁS DE 30 DÍAS, NO ES POSIBLE CREARLE UNA NOTA POR VALOR\")\n";
        $html .= "      document.location.href = '" . $action['volverAnterior'] . "';\n";
        $html .= "  }\n";
        $html .= "  function CalcularTotalDiferencia(id)\n";
        $html .= "  {\n";
        $html .= "      objeto = document.getElementById('error_forma');\n";
        $html .= "      var cantidad = parseInt(document.getElementById('cantidad_'+id).value);\n";
        $html .= "      var nota = document.getElementById('valor_'+id).value;\n";
        $html .= "      if(nota == \"\") {\n";
        $html .= "          nota = 0;\n";
        $html .= "      }\n";
        $html .= "      var valorNota = parseFloat(nota);\n";
        $html .= "      var totalNota = cantidad * valorNota;\n";
        $html .= "      document.getElementById('total_'+id).innerHTML = '$ '+totalNota;\n";
        $html .= "      document.getElementById('valor_oculto_'+id).value = totalNota;\n";
        $html .= "      var valorUnitario = parseFloat(document.getElementById('valor_unitario_'+id).value);\n";
        $html .= "      total = cantidad * valorUnitario;\n";
        $html .= "      var diferencia = total - totalNota;\n";
        $html .= "      document.getElementById('diferencia_'+id).innerHTML = '$ '+diferencia;\n";
        $html .= "     var botoncrearnota = document.getElementById('botoncrearnota');
                            var botonguardarnota = document.getElementById('botonguardarnota');";
        $html .= "      if(diferencia < 0) {\n";
        $html .= "          objeto.innerHTML = \"LA DIFERENCIA NO PUEDE SER MENOR A CERO\";\n";
        $html .= "     
                                agregarInvalido(id);
                    ";
        $html .="       ";
        $html .= "      } else {\n";
        $html .= "          objeto.innerHTML = \"\";\n
                               removerInvalido(id);
                    ";
        $html .= "      }\n";
        $html .= "      desahabilitarBotones(botoncrearnota,botonguardarnota, objeto);";
        $html .= "  }\n";
        
        $html .= "function desahabilitarBotones(botoncrearnota,botonguardarnota,objeto ){
                            var disabled = false;
                            objeto.innerHTML = '';
                            if(cantidadinvalidos.length > 0){
                                disabled = true;
                                objeto.innerHTML = 'LA DIFERENCIA NO PUEDE SER MENOR A CERO';
                            }
                                  if(botoncrearnota){
                                    botoncrearnota.disabled = disabled;
                                }

                                botonguardarnota.disabled = disabled;
                       }
                       
                       function onConceptoChange(obj, factura){
                           xajax_mostrarFormularioConceptoNota(factura, 'credito', obj.value);
                       }
                       

                ";
        
        $html .= "
                function agregarInvalido(id){
                    var encontrado = false;
                    for(var i in cantidadinvalidos){
                        if(id == cantidadinvalidos[i]){
                            encontrado = true;
                            break;
                        }
                    }
                    
                    if(!encontrado){
                        cantidadinvalidos.push(id);
                    }
                }
                
                function removerInvalido(id){
                     for(var i in cantidadinvalidos){
                        if(id == cantidadinvalidos[i]){
                            cantidadinvalidos.splice(i,1);
                            break;
                        }
                    }
                }
        ";
        $html .= "  function ValidarFormaCrearNota(forma)\n";
        $html .= "  {\n";
        $html .= "      objeto = document.getElementById('error_forma');\n";
        $html .= "      var saldoFactura = document.getElementById('saldo_factura').value;\n";
        $html .= "      var valor = document.getElementsByClassName('valor');\n";
        $html .= "      var valores = valor.length;\n";
        $html .= "      var valoresNoNumericos = 0;\n";
        $html .= "      var totalValores = 0;\n";
        $html .="       var tipo_nota = document.getElementById('tipo_nota').value;";
        $html .= "      for (i=0;i<valores;i++)\n";
        $html .= "      {\n";
        $html .= "          if(isNaN(valor[i].value))\n";
        $html .= "          {\n";
        $html .= "              valoresNoNumericos = 1;\n";
        $html .= "          } else {\n";
        $html .= "              if(valor[i].value == \"\") {\n";
        $html .= "                  totalValores = totalValores + 0;\n";
        $html .= "              } else {\n";
        $html .= "                  totalValores = totalValores + parseFloat(valor[i].value);\n";
        $html .= "              }\n";
        $html .= "          }\n";
        $html .= "      }\n";
        $html .= "      if(valoresNoNumericos == 1)\n";
        $html .= "      {\n";
        $html .= "          objeto.innerHTML = \"LOS VALORES DE NOTAS DEBEN SER NUMÉRICOS\";\n";
        $html .= "          return;\n";
        $html .= "      }\n";
        $html .= "      if(totalValores > saldoFactura && tipo_nota != 'DEVOLUCIÓN')\n";   
        $html .= "      {\n";
        $html .= "          objeto.innerHTML = \"LA SUMATORIA DE LAS NOTAS NO PUEDE SER MAYOR AL VALOR DEL SALDO DE LA FACTURA\";\n";
        $html .= "          return;\n";
        $html .= "      }";
        $html .= "      document.FormaCrearNota.action = \"" . $action['GuardarNotaTemporal'] . "\"; \n";
        $html .= "      document.FormaCrearNota.submit();\n";
        $html .= "  }\n";
        if ($diferenciaDias >= 30)
        {
            $html .= "  ValidarDiferenciaDias();\n";
        }
        $html .= "</script>\n";
        $html .= ThemeAbrirTabla('NOTAS CRÉDITO');
        $html .= "  <table class=\"modulo_table_list\" align=\"center\">\n";
        $html .= "      <tr>\n";
        $html .= "          <td class=\"modulo_table_list_title\">FACTURA</td>\n";
        $html .= "          <td class=\"modulo_table_list_title\">PREFIJO</td>\n";
        $html .= "          <td class=\"modulo_table_list_title\">TERCERO</td>\n";
        $html .= "          <td class=\"modulo_table_list_title\">IDENTIFICACIÓN</td>\n";
        $html .= "          <td class=\"modulo_table_list_title\">VALOR</td>\n";
        $html .= "          <td class=\"modulo_table_list_title\">SALDO</td>\n";
        $html .= "          <td class=\"modulo_table_list_title\">FECHA</td>\n";
        $html .= "      </tr>\n";
        $html .= "      <tr class=\"modulo_list_claro\">\n";
        $html .= "          <td>" . $factura . "</td>\n";
        $html .= "          <td>" . $datosFactura[0]['prefijo'] . "</td>\n";
        $html .= "          <td>" . $datosFactura[0]['nombre_tercero'] . "</td>\n";
        $html .= "          <td>" . $datosFactura[0]['tipo_id_tercero'] . " - " . $datosFactura[0]['tercero_id'] . "</td>\n";
        $html .= "          <td>$ " .FormatoValor($datosFactura[0]['valor_total']) . "</td>\n";
        $html .= "          <td>$ " . FormatoValor($datosFactura[0]['saldo']) . "</td>\n";
        $html .= "          <td>" . $datosFactura[0]['fecha_registro'] . "</td>\n";
        $html .= "      </tr>\n";
        $html .= "  </table>\n";
        $html .= "  <br><br>";
        $html .= "  <table class=\"modulo_table_list\" align=\"center\">\n";
        $html .= "      <tr class=\"formulacion_table_list\">\n";
        $html .= "          <td class=\"modulo_table_list_title\">\n";
        $html .= "              NOTA CRÉDITO POR " . $tipo . "\n";
        $html .= "          </td>\n";
        $html .= "      </tr>\n";
        $html .= "  </table>\n";
        $html .= "  <br><br>";
        
        
          $concepto_nota =SessionGetVar("concepto_nota");
          if(isset($_REQUEST["concepto"]) &&  $_REQUEST["concepto"] > 0){
             //if(is_null($concepto_nota)){
                  $concepto_nota = array("id" =>$_REQUEST["concepto"]);
             // }
          }
          
        //echo   var_dump($_REQUEST["concepto"]). " ================";
  //     echo var_dump($concepto_nota) . " ========================";
        if($concepto_nota["id"]== 1 || is_null($concepto_nota)){
                $html .= "<form name=\"FormaCrearNota\" id=\"FormaCrearNota\" action=\"javascript:ValidarFormaCrearNota(document.FormaCrearNota)\" method=\"post\">";
                $html .= "<input type=\"hidden\" id=\"saldo_factura\" value=\"" . $datosFactura[0]['saldo'] . "\">\n";
                $html .= "<center><div class=\"label_error\" id=\"error_forma\"></div></center>\n";



                if(count($detalleFactura) == 0){
                    $detalledevolucion = "NO SE ENCONTRO NINGUN DETALLE PARA LA NOTA";
                    if($tipo == "DEVOLUCIÓN"){
                        $detalledevolucion = "NO SE ENCONTRO DEVOLUCIONES PARA LA FACTURA";
                    }

                    $html .= "<center><h5 style='color:red;'>{$detalledevolucion}</h5></center>";
                     $html .= "<table align=\"center\">\n";
                    $html .= "  <tr>\n";
                    $html .= "      <td align=\"center\" class=\"label_error\">\n";
                    $html .= "        <a href=\"" . $action['volver'] . "\">VOLVER</a>\n";
                    $html .= "      </td>\n";
                    $html .= "  </tr>\n";
                    $html .= "</table>\n";
                    $html .= ThemeCerrarTabla();
                    return $html;
                }
                  if ( $cantidadDetallesTemporales == 0 && $tipo != "DEVOLUCIÓN" ){
                        $select = "<select class='select' name='concepto_id' id='concepto_id' onchange='onConceptoChange(this, {$datosFactura[0]['factura_fiscal']})'>";
                            foreach($conceptos as $c){
                                $select .= "<option value='{$c['id']}'>{$c['descripcion']}</option>";
                            }
                      $select .= "</select>";
                            $html .= "<table class=\"modulo_table_list\" align=\"center\">
                                   <tr>
                                         <td class=\"modulo_table_list_title\">CONCEPTO NOTA</td><td>{$select}</td>
                                   </tr>

                             </table></br></br>";
                }


                $html .= "  <table class=\"modulo_table_list\" align=\"center\">\n";
                $html .= "      <tr>\n";
                $html .= "          <td class=\"modulo_table_list_title\">CÓDIGO</td>\n";
                $html .= "          <td class=\"modulo_table_list_title\">PRODUCTO</td>\n";
                $html .= "          <td class=\"modulo_table_list_title\">\n";
                $html .= "              CANTIDAD\n";



                if ($tipo == "DEVOLUCIÓN")
                {
                    $html .= "              DEVUELTA\n";
                }
                $html .= "          </td>\n";
                $html .= "          <td class=\"modulo_table_list_title\">LOTE</td>\n";
                $html .= "          <td class=\"modulo_table_list_title\">VALOR UNITARIO</td>\n";
                $html .= "          <td class=\"modulo_table_list_title\">VALOR NOTA</td>\n";
                if ($tipo == "VALOR")
                {
                    $html .= "          <td class=\"modulo_table_list_title\">TOTAL NOTA</td>\n";
                    $html .= "          <td class=\"modulo_table_list_title\">DIFERENCIA</td>\n";
                }
                $html .= "          <td class=\"modulo_table_list_title\">OBSERVACIÓN</td>\n";
                $html .= "          <td class=\"modulo_table_list_title\">GUARDAR/ELIMINAR</td>\n";
                $html .= "      </tr>\n";
                for ($i = 0; $i < count($detalleFactura); $i++)
                {
                    $html .= "      <tr class=\"modulo_list_claro\">\n";
                    $html .= "          <td>" . $detalleFactura[$i]['codigo_producto'] . "</td>\n";
                    $html .= "          <td>" . $detalleFactura[$i]['producto'] . "</td>\n";
                    if ($tipo == "DEVOLUCIÓN")
                    {
                        $html .= "          <td>" . $detalleFactura[$i]['cantidad_producto_devuelto'] . "</td>\n";
                    }
                    else
                    {
                        $html .= "          <td>\n";
                        $html .= "              " . $detalleFactura[$i]['cantidad_existente'] . "\n";
                        $html .= "              <input type=\"hidden\" id=\"cantidad_" . $i . "\" name=\"cantidad_" . $i . "\" value=\"" . $detalleFactura[$i]['cantidad_existente'] . "\">\n";
                        $html .= "          </td>\n";
                    }
                    $html .= "          <td>" . $detalleFactura[$i]['lote'] . "</td>\n";
                    $html .= "          <td>\n";
                    $html .= "              $ " . FormatoValor($detalleFactura[$i]['valor_unitario']) . "\n";
                    if ($tipo == "VALOR")
                    {
                        $html .= "              <input type=\"hidden\" id=\"valor_unitario_" . $i . "\" name=\"valor_unitario_" . $i . "\" value=\"" . $detalleFactura[$i]['valor_unitario'] . "\">\n";
                    }
                    $html .= "          </td>\n";
                    $html .= "          <td align=\"left\" class=\"modulo_list_claro\">\n";
                    if ($tipo == "DEVOLUCIÓN")
                    {
                        $html .= "              <input type=\"text\" class=\"input-text\" name=\"valor\" value=\"" . FormatoValor($detalleFactura[$i]['valor_total_producto_devuelto']) . "\" disabled>\n";
                        $html .= "              <input type=\"hidden\" class=\"valor\" name=\"valor_" . $i . "\" value=\"" . $detalleFactura[$i]['valor_total_producto_devuelto'] . "\">\n";
                    }
                    else
                    {
                        if (empty($detalleFactura[$i]['tmp_detalle_nota_credito_despacho_cliente_id']))
                        {
                            $disabled = "";
                            if($detalleFactura[$i]['cantidad_existente'] == 0){
                                $disabled = "disabled";
                            }

                            $html .= "              <input type=\"text\" id=\"valor_" . $i . "\" class=\"input-text valor\" name=\"valor\" value=\"\"  {$disabled} onkeyup=\"CalcularTotalDiferencia(" . $i . ");\">\n";
                            $html .= "              <input type=\"hidden\" id=\"valor_oculto_" . $i . "\" name=\"valor_" . $i . "\" value=\"\">\n";
                        }
                        else
                        {

                            $html .= "              <input type=\"text\" class=\"input-text valor\" name=\"valor_" . $i . "\" value=\"" . FormatoValor($detalleFactura[$i]['valor_digitado_nota']) . "\" disabled>\n";
                        }
                    }
                    $html .= "          </td>\n";
                    if ($tipo == "VALOR")
                    {
                        $html .= "          <td class=\"modulo_list_claro\" id=\"total_" . $i . "\">$ " . FormatoValor($detalleFactura[$i]['valor']) . "</td>\n";
                        $html .= "          <td class=\"modulo_list_claro\" id=\"diferencia_" . $i . "\">$ " . FormatoValor($detalleFactura[$i]['diferencia']) . "</td>\n";
                    }
                    $html .= "          <td align=\"left\" class=\"modulo_list_claro\">\n";
                    if (empty($detalleFactura[$i]['tmp_detalle_nota_credito_despacho_cliente_id']))
                    {
                         $disabled = "";
                            if($detalleFactura[$i]['cantidad_existente'] == 0){
                                $disabled = "disabled";
                            }
                        $html .= "              <textarea name=\"observacion_" . $i . "\" rows=\"3\" cols=\"42\" ></textarea>\n";
                    }
                    else
                    {
                        $html .= "              <textarea name=\"observacion_" . $i . "\" rows=\"3\" cols=\"42\" >" . $detalleFactura[$i]['observacion'] . "</textarea>\n";
                    }
                    $html .= "          </td>\n";
                    $html .= "          <td align=\"center\" class=\"modulo_list_claro\">\n";
                    if (!empty($detalleFactura[$i]['documento_id']) && empty($detalleFactura[$i]['movimiento_id']) && $tipo == "DEVOLUCIÓN") 
                    {
                        $html .= "              NO INCLUIDO EN LA DEVOLUCIÓN\n";
                    }
                    else
                    {
                        if (empty($detalleFactura[$i]['tmp_detalle_nota_credito_despacho_cliente_id']))
                        {
                            if ($tipo == "DEVOLUCIÓN")
                            {
                                $html .= "              <input type=\"checkbox\" name=\"nota_devolucion\" value=\"\" checked disabled>\n";
                                $html .= "              <input type=\"checkbox\" name=\"guarda_" . $i . "\" value=\"\" checked style=\"display:none\">\n";
                            }
                            else
                            {
                                 $disabled = "";
                                if($detalleFactura[$i]['cantidad_existente'] == 0){
                                    $disabled = "disabled";
                                }

                                    $html .= "              <input type=\"checkbox\" name=\"guarda_" . $i . "\" value=\"\"  {$disabled}>\n";

                            }
                        }
                        else
                        {
                            $html .= "              <a href=\"" . $action['EliminarNotaTemporal'] . "&tmp_detalle_nota_credito_despacho_cliente_id=" . $detalleFactura[$i]['tmp_detalle_nota_credito_despacho_cliente_id'] . "\" title=\"ELIMINAR NOTA TEMPORAL\">ELIMINAR NOTA</a>\n";
                        }
                    }
                    $html .= "              <input type=\"hidden\" name=\"item_id_" . $i . "\" value=\"" . $detalleFactura[$i]['item_id'] . "\">\n";
                    if ($tipo == "DEVOLUCIÓN")
                    {
                        $html .= "              <input type=\"hidden\" name=\"movimiento_id_" . $i . "\" value=\"" . $detalleFactura[$i]['movimiento_id'] . "\">\n";
                    }
                    $html .= "          </td>\n";
                    $html .= "      </tr>\n";
                }
                $html .= "      <tr class=\"formulacion_table_list\">\n";
                if ($tipo == "VALOR")
                {
                    $html .= "          <td colspan=\"10\" align=\"center\" class=\"modulo_list_claro\">\n";
                }
                else
                {
                    $html .= "          <td colspan=\"8\" align=\"center\" class=\"modulo_list_claro\">\n";
                }
                $html .= "              <input type=\"hidden\" name=\"guarda_temporal\" value=\"1\">\n";
                if ($tipo == "DEVOLUCIÓN")
                {
                    $html .= "              <input type=\"hidden\" name=\"empresa_id_devolucion\" value=\"" . $datosFactura[0]['empresa_id_devolucion'] . "\">\n";
                    $html .= "              <input type=\"hidden\" name=\"prefijo_devolucion\" value=\"" . $datosFactura[0]['prefijo_devolucion'] . "\">\n";
                    $html .= "              <input type=\"hidden\" name=\"numero_devolucion\" value=\"" . $datosFactura[0]['numero_devolucion'] . "\">\n";
                }
                $html .= "              <input class=\"input-submit\" type=\"submit\" name=\"guarda\" value=\"GUARDAR NOTA(S) TEMPORAL(ES)\" id='botonguardarnota'>\n";
                $html .= "              <input type=\"hidden\" name=\"tmp_nota_credito_despacho_cliente_id\" value=\"" . $tmp_nota_credito_despacho_cliente_id['tmp_nota_credito_despacho_cliente_id'] . "\">\n";
                $html .= "              <input type=\"hidden\" name=\"totalItemsFactura\" value=\"" . $totalItemsFactura . "\">\n";
                $html .= "              <input type=\"hidden\"  id='tipo_nota' name=\"tipo\" value=\"" . $tipo . "\">\n";
                $html .= "          </td>\n";
                $html .= "      </tr>\n";
                $html .= "  </table>\n";
                $html .= "</form>\n";
                $html .= "  <br><br>";
        
    } else {
        if ($tipo != "DEVOLUCIÓN" ){
                      
                      
                    $deshabilitar = "";
                    $columnaeleminar = "";
                    $columnaeleminarcabecera = " ";
                    if(!empty($tmp_nota_credito_despacho_cliente_id['tmp_nota_credito_despacho_cliente_id'])){
                        $deshabilitar = "disabled";
                        $columnaeleminarcabecera = "<td class=\"modulo_table_list_title\">ACCION</td>";
                        $columnaeleminar = "<td align='center' ><a href=\"" . ModuloGetURL("app", "NotasFacturasCliente", "controller", "eliminarNotaConcepto",
                                array("factura" => $factura, "tmp_nota_credito_despacho_cliente_id" => $tmp_nota_credito_despacho_cliente_id['tmp_nota_credito_despacho_cliente_id']))."\" title=\"ELIMINAR NOTA TEMPORAL\">ELIMINAR NOTA</a></td>";
                    }

                      $select = "<select class='select' name='concepto_id' id='concepto_id' $deshabilitar onchange='onConceptoChange(this, {$datosFactura[0]['factura_fiscal']})'>";
                       foreach($conceptos as $c){
                           $selected = "";
                           if($concepto_nota["id"] == $c['id']){
                               $selected = "selected = selected";
                           }

                           $select .= "<option {$selected} value='{$c['id']}'>{$c['descripcion']}</option>";
                       }
                      $select .= "</select>";
                      $tablaselect =  "<table class=\"modulo_table_list\" align=\"center\">
                                            <tr>
                                                  <td class=\"modulo_table_list_title\">CONCEPTO NOTA</td><td>{$select}</td>
                                            </tr>

                                      </table></br></br>";
                                         
                          $html .= "<form name=\"FormaCrearNota\" id=\"FormaCrearNota\" action=\"javascript:ValidarFormaCrearNota(document.FormaCrearNota)\" method=\"post\">
                                        <center><div class=\"label_error\" id=\"error_forma\"></div></center></br>
                                        {$tablaselect}
                                        <table class=\"modulo_table_list\" align=\"center\">
                                          <tr>
                                                <td class=\"modulo_table_list_title\">VALOR NOTA</td>
                                                <td class=\"modulo_table_list_title\">DESCRIPCION</td>
                                                {$columnaeleminarcabecera}
                                          </tr>
                                          <tr>
                                                <td ><input type='text' class='input-text valor' name='valor_nota' {$deshabilitar} value='{$detalleFactura[0]["valor_nota"]}' /></td>
                                                <td ><textarea name=\"descripcion\"  rows=\"3\" cols=\"42\" {$deshabilitar}  >{$detalleFactura[0]["descripcion"]}</textarea>\n</td>
                                                 {$columnaeleminar}
                                          </tr>

                                    </table></br></br>";
                                    
                                    
                                    if(empty($tmp_nota_credito_despacho_cliente_id['tmp_nota_credito_despacho_cliente_id'])){
                                         $html .= "<table align='center'>
                                            <tr><td><input class=\"input-submit\" type=\"submit\" name=\"guarda\" value=\"GUARDAR NOTA(S) TEMPORAL(ES)\" id='botonguardarnota'></td></tr>
                                         </table>";
                                    }
                                   
                                     

                                     $html .="<input type=\"hidden\" id=\"saldo_factura\" value=\"" . $datosFactura[0]['saldo'] . "\">
                                     <input type=\"hidden\" id=\"tipo_nota\" value='{$tipo}'>
                                     <input type=\"hidden\" name=\"guarda_temporal\" value=\"1\">
                                      <input type=\"hidden\" name=\"tmp_nota_credito_despacho_cliente_id\" value=\"" . $tmp_nota_credito_despacho_cliente_id['tmp_nota_credito_despacho_cliente_id'] . "\">
                                      <input type=\"hidden\"  id='tipo_nota' name=\"tipo\" value=\"" . $tipo . "\">
                                </form>
                             ";
                }
                
                
    }
       
   // echo var_dump($tmp_nota_credito_despacho_cliente_id['tmp_nota_credito_despacho_cliente_id']);
        
        if ((!empty($tmp_nota_credito_despacho_cliente_id['tmp_nota_credito_despacho_cliente_id']) /*&& $cantidadDetallesTemporales != 0 && $concepto_nota["id"] == 1 || is_null($concepto_nota)*/) && (($tipo == "VALOR") 
                || ($tipo == "DEVOLUCIÓN" && $cantidadValoresTotalProductoDevuelto == $cantidadTmpDetalleNotaCreditoDespachoClienteId)) /*||
               ($concepto_nota["id"] != 1 && !empty($tmp_nota_credito_despacho_cliente_id['tmp_nota_credito_despacho_cliente_id']))*/)
        {
                
           
                if($concepto_nota["id"] != 1 && !is_null($concepto_nota)){

                                     
                         $calculofactura["retefuente"] = 0;
                         $calculofactura["reteica"] = 0;
                         $calculofactura["total_iva"] = 0;
                         $calculofactura["sub_total"]  =FormatoValor($detalleFactura[0]["valor_nota"]);
                         $calculofactura["total_nota"] = FormatoValor($detalleFactura[0]["valor_nota"]) ;
                         
                }
                
                if(($cantidadDetallesTemporales != 0 && $concepto_nota["id"] == 1 || is_null($concepto_nota)) || ($concepto_nota["id"] > 1) ){
                    
                    $html .= "<table class='modulo_table_list' align='center'>
                            <tr>
                                <th class='modulo_table_list_title'>Retefuente</th>
                                <th class='modulo_table_list_title'>Rete Ica</th>
                                <th class='modulo_table_list_title'>Iva</th>
                                <th class='modulo_table_list_title'>Subtotal</th>
                                <th class='modulo_table_list_title'>Total</th>
                            </tr>
                            <tr>
                                <th class='modulo_list_claro'><input type=\"text\" class=\"input-text valor\" value='{$calculofactura["retefuente"]}'  id='calculoretefuente' value='' disabled /></th>
                                <th class='modulo_list_claro'><input type=\"text\" class=\"input-text valor\" value='{$calculofactura["reteica"]}'  id='calculoreteica' value='' disabled /></th>
                                <th class='modulo_list_claro'><input type=\"text\" class=\"input-text valor\" value='{$calculofactura["total_iva"]}'  id='calculoiva' value='' disabled /></th>
                                <th class='modulo_list_claro'><input type=\"text\" class=\"input-text valor\" value='{$calculofactura["sub_total"]}'  id='calculototal' value='' disabled /></th>
                                 <th class='modulo_list_claro'><input type=\"text\" class=\"input-text valor\" value='{$calculofactura["total_nota"]}'  id='calculototal' value='' disabled /></th>
                            </tr>
                        </table></br></br>
                         ";

                    $html .= "<form name=\"FormaCrearNotaOficial\" id=\"FormaCrearNotaOficial\" action=\"" . $action['GuardarNota'] . "\" method=\"post\">\n";
                    $html .= "  <table class=\"modulo_table_list\" align=\"center\">\n";
                    $html .= "      <tr class=\"formulacion_table_list\">\n";
                    $html .= "          <td align=\"center\" class=\"modulo_list_claro\">\n";
                    $html .= "              <input type=\"hidden\" name=\"nota_credito_despacho_cliente_id\" value=\"" . $tmp_nota_credito_despacho_cliente_id['tmp_nota_credito_despacho_cliente_id'] . "\">\n";
                    $html .= "              <input type=\"hidden\" name=\"tipo\" value=\"" . $tipo . "\">\n";
                    $html .= "              <input type=\"hidden\" name=\"tipo_factura\" value=\"" . $datosFactura[0]['tipo_factura'] . "\">\n";
                    $html .= "              <input class=\"input-submit\" type=\"submit\" name=\"guarda\" value=\"CREAR NOTA(S)\" id='botoncrearnota'>\n";
                    $html .= "          </td>\n";
                    $html .= "      </tr>\n";
                    $html .= "  </table>\n";
                    $html .= "</form>\n";
                    $html .= "  <br><br>";
                }
           
        }
        
        
        $html .= "<table align=\"center\">\n";
        $html .= "  <tr>\n";
        $html .= "      <td align=\"center\" class=\"label_error\">\n";
        $html .= "        <a href=\"" . $action['volver'] . "\">VOLVER</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= ThemeCerrarTabla();
        return $html;
    }

    function FormaCrearNotaDebito($action, $factura, $datosFactura, $detalleFactura, $tmp_nota_debito_despacho_cliente_id, $totalItemsFactura, $diferenciaDias, $cantidadDetallesTemporales, $calculofactura)
    {
        $html = "<script>\n";
        $html .= "  function ValidarDiferenciaDias()\n";
        $html .= "  {\n";
        $html .= "      alert(\"ESTA FACTURA FUE CREADA HACE MÁS DE 30 DÍAS, NO ES POSIBLE CREARLE UNA NOTA\")\n";
        $html .= "      document.location.href = '" . $action['volverAnterior'] . "';\n";
        $html .= "  }\n";
        $html .= "  function CalcularTotal(id)\n";
        $html .= "  {\n";
        $html .= "      objeto = document.getElementById('error_forma');\n";
        $html .= "      var cantidad = parseInt(document.getElementById('cantidad_'+id).value);\n";
        $html .= "      var nota = document.getElementById('valor_'+id).value;\n";
        $html .= "      if(nota == \"\") {\n";
        $html .= "          nota = 0;\n";
        $html .= "      }\n";
        $html .= "      var valorNota = parseFloat(nota);\n";
        $html .= "      var totalNota = cantidad * valorNota;\n";
        $html .= "      document.getElementById('total_'+id).innerHTML = '$ '+totalNota;\n";
        $html .= "      document.getElementById('valor_oculto_'+id).value = totalNota;\n";
        $html .= "  }\n";
        $html .= "  function ValidarFormaCrearNota(forma)\n";
        $html .= "  {\n";
        $html .= "      objeto = document.getElementById('error_forma');\n";
        $html .= "      var saldoFactura = document.getElementById('saldo_factura').value;\n";
        $html .= "      var valor = document.getElementsByClassName('valor');\n";
        $html .= "      var valores = valor.length;\n";
        $html .= "      var valoresNoNumericos = 0;\n";
        $html .= "      var totalValores = 0;\n";
        $html .= "      for (i=0;i<valores;i++)\n";
        $html .= "      {\n";
        $html .= "          if(isNaN(valor[i].value))\n";
        $html .= "          {\n";
        $html .= "              valoresNoNumericos = 1;\n";
        $html .= "          } else {\n";
        $html .= "              if(valor[i].value == \"\") {\n";
        $html .= "                  totalValores = totalValores + 0;\n";
        $html .= "              } else {\n";
        $html .= "                  totalValores = totalValores + parseFloat(valor[i].value);\n";
        $html .= "              }\n";
        $html .= "          }\n";
        $html .= "      }\n";
        $html .= "      if(valoresNoNumericos == 1)\n";
        $html .= "      {\n";
        $html .= "          objeto.innerHTML = \"LOS VALORES DE NOTAS DEBEN SER NUMÉRICOS\";\n";
        $html .= "          return;\n";
        $html .= "      }\n";
        $html .= "      if(totalValores >= saldoFactura)\n";
        $html .= "      {\n";
        $html .= "          objeto.innerHTML = \"LA SUMATORIA DE LAS NOTAS NO PUEDE SER MAYOR AL VALOR DEL SALDO DE LA FACTURA\";\n";
        $html .= "          return;\n";
        $html .= "      }\n";
        $html .= "      document.FormaCrearNota.action = \"" . $action['GuardarNotaDebitoTemporal'] . "\"; \n";
        $html .= "      document.FormaCrearNota.submit();\n";
        $html .= "  }\n";
        if ($diferenciaDias >= 30)
        {
            $html .= "  ValidarDiferenciaDias();\n";
        }
        $html .= "</script>\n";
        $html .= ThemeAbrirTabla('NOTAS DÉBITO');
        $html .= "  <table class=\"modulo_table_list\" align=\"center\">\n";
        $html .= "      <tr>\n";
        $html .= "          <td class=\"modulo_table_list_title\">FACTURA</td>\n";
        $html .= "          <td class=\"modulo_table_list_title\">PREFIJO</td>\n";
        $html .= "          <td class=\"modulo_table_list_title\">TERCERO</td>\n";
        $html .= "          <td class=\"modulo_table_list_title\">IDENTIFICACIÓN</td>\n";
        $html .= "          <td class=\"modulo_table_list_title\">VALOR</td>\n";
        $html .= "          <td class=\"modulo_table_list_title\">SALDO</td>\n";
        $html .= "          <td class=\"modulo_table_list_title\">FECHA</td>\n";
        $html .= "      </tr>\n";
        $html .= "      <tr class=\"modulo_list_claro\">\n";
        $html .= "          <td>" . $factura . "</td>\n";
        $html .= "          <td>" . $datosFactura[0]['prefijo'] . "</td>\n";
        $html .= "          <td>" . $datosFactura[0]['nombre_tercero'] . "</td>\n";
        $html .= "          <td>" . $datosFactura[0]['tipo_id_tercero'] . " - " . $datosFactura[0]['tercero_id'] . "</td>\n";
        $html .= "          <td>$ " .FormatoValor( $datosFactura[0]['valor_total']) . "</td>\n";
        $html .= "          <td>$ " .FormatoValor( $datosFactura[0]['saldo']) . "</td>\n";
        $html .= "          <td>" . $datosFactura[0]['fecha_registro'] . "</td>\n";
        $html .= "      </tr>\n";
        $html .= "  </table>\n";
        $html .= "  <br><br>";
        $html .= "  <table class=\"modulo_table_list\" align=\"center\">\n";
        $html .= "      <tr class=\"formulacion_table_list\">\n";
        $html .= "          <td class=\"modulo_table_list_title\">\n";
        $html .= "              NOTA DÉBITO\n";
        $html .= "          </td>\n";
        $html .= "      </tr>\n";
        $html .= "  </table>\n";
        $html .= "  <br><br>";
        $html .= "<form name=\"FormaCrearNota\" id=\"FormaCrearNota\" action=\"javascript:ValidarFormaCrearNota(document.FormaCrearNota)\" method=\"post\">";
        $html .= "  <input type=\"hidden\" id=\"saldo_factura\" value=\"" . $datosFactura[0]['saldo'] . "\">\n";
        $html .= "  <center><div class=\"label_error\" id=\"error_forma\"></div></center>\n";
        $html .= "  <table class=\"modulo_table_list\" align=\"center\">\n";
        $html .= "      <tr>\n";
        $html .= "          <td class=\"modulo_table_list_title\">CÓDIGO</td>\n";
        $html .= "          <td class=\"modulo_table_list_title\">PRODUCTO</td>\n";
        $html .= "          <td class=\"modulo_table_list_title\">\n";
        $html .= "              CANTIDAD\n";
        $html .= "          </td>\n";
        $html .= "          <td class=\"modulo_table_list_title\">LOTE</td>\n";
        $html .= "          <td class=\"modulo_table_list_title\">VALOR UNITARIO</td>\n";
        $html .= "          <td class=\"modulo_table_list_title\">VALOR NOTA</td>\n";
        $html .= "          <td class=\"modulo_table_list_title\">TOTAL NOTA</td>\n";
        $html .= "          <td class=\"modulo_table_list_title\">OBSERVACIÓN</td>\n";
        $html .= "          <td class=\"modulo_table_list_title\">GUARDAR/ELIMINAR</td>\n";
        $html .= "      </tr>\n";
        for ($i = 0; $i < count($detalleFactura); $i++)
        {
            $html .= "      <tr class=\"modulo_list_claro\">\n";
            $html .= "          <td>" . $detalleFactura[$i]['codigo_producto'] . "</td>\n";
            $html .= "          <td>" . $detalleFactura[$i]['producto'] . "</td>\n";
            $html .= "          <td>\n";
            $html .= "              " . $detalleFactura[$i]['cantidad'] . "\n";
            $html .= "              <input type=\"hidden\" id=\"cantidad_" . $i . "\" name=\"cantidad_" . $i . "\" value=\"" . $detalleFactura[$i]['cantidad'] . "\">\n";
            $html .= "          </td>\n";

            $html .= "          <td>" . $detalleFactura[$i]['lote'] . "</td>\n";
            $html .= "          <td>\n";
            $html .= "              $ " . FormatoValor($detalleFactura[$i]['valor_unitario']) . "\n";
            $html .= "              <input type=\"hidden\" id=\"valor_unitario_" . $i . "\" name=\"valor_unitario_" . $i . "\" value=\"" . $detalleFactura[$i]['valor_unitario'] . "\">\n";
            $html .= "          </td>\n";
            $html .= "          <td align=\"left\" class=\"modulo_list_claro\">\n";
            if (empty($detalleFactura[$i]['tmp_detalle_nota_debito_despacho_cliente_id']))
            {
                $html .= "              <input type=\"text\" id=\"valor_" . $i . "\" class=\"input-text valor\" name=\"valor\" value=\"\" onkeyup=\"CalcularTotal(" . $i . ");\">\n";
                $html .= "              <input type=\"hidden\" id=\"valor_oculto_" . $i . "\" name=\"valor_" . $i . "\" value=\"\">\n";
            }
            else
            {
                $html .= "              <input type=\"text\" class=\"input-text valor\" name=\"valor_" . $i . "\" value=\"" . FormatoValor($detalleFactura[$i]['valor_digitado_nota']) . "\" disabled>\n";
            }
            $html .= "          </td>\n";
            $html .= "          <td class=\"modulo_list_claro\" id=\"total_" . $i . "\">$ " .FormatoValor( $detalleFactura[$i]['valor']) . "</td>\n";
            $html .= "          <td align=\"left\" class=\"modulo_list_claro\">\n";
            if (empty($detalleFactura[$i]['tmp_detalle_nota_debito_despacho_cliente_id']))
            {
                $html .= "              <textarea name=\"observacion_" . $i . "\" rows=\"3\" cols=\"42\"></textarea>\n";
            }
            else
            {
                $html .= "              <textarea name=\"observacion_" . $i . "\" rows=\"3\" cols=\"42\" >" . $detalleFactura[$i]['observacion'] . "</textarea>\n";
            }
            $html .= "          </td>\n";
            $html .= "          <td align=\"center\" class=\"modulo_list_claro\">\n";
            if (empty($detalleFactura[$i]['tmp_detalle_nota_debito_despacho_cliente_id']))
            {
                $html .= "              <input type=\"checkbox\" name=\"guarda_" . $i . "\" value=\"\">\n";
            }
            else
            {
                $html .= "              <a href=\"" . $action['EliminarNotaDebitoTemporal'] . "&tmp_detalle_nota_debito_despacho_cliente_id=" . $detalleFactura[$i]['tmp_detalle_nota_debito_despacho_cliente_id'] . "\" title=\"ELIMINAR NOTA TEMPORAL\">ELIMINAR NOTA</a>\n";
            }

            $html .= "              <input type=\"hidden\" name=\"item_id_" . $i . "\" value=\"" . $detalleFactura[$i]['item_id'] . "\">\n";
            $html .= "          </td>\n";
            $html .= "      </tr>\n";
        }
        $html .= "      <tr class=\"formulacion_table_list\">\n";
        $html .= "          <td colspan=\"9\" align=\"center\" class=\"modulo_list_claro\">\n";
        $html .= "              <input type=\"hidden\" name=\"guarda_temporal\" value=\"1\">\n";
        $html .= "              <input class=\"input-submit\" type=\"submit\" name=\"guarda\" value=\"GUARDAR NOTA(S) TEMPORAL(ES)\">\n";
        $html .= "              <input type=\"hidden\" name=\"tmp_nota_debito_despacho_cliente_id\" value=\"" . $tmp_nota_debito_despacho_cliente_id['tmp_nota_debito_despacho_cliente_id'] . "\">\n";
        $html .= "              <input type=\"hidden\" name=\"totalItemsFactura\" value=\"" . $totalItemsFactura . "\">\n";
        $html .= "          </td>\n";
        $html .= "      </tr>\n";
        $html .= "  </table>\n";
        $html .= "</form>\n";
        $html .= "  <br><br>";
        if (!empty($tmp_nota_debito_despacho_cliente_id['tmp_nota_debito_despacho_cliente_id']) && $cantidadDetallesTemporales != 0)
        {
            
            $html .= "<table class='modulo_table_list' align='center'>
                            <tj>
                                <th class='modulo_table_list_title'>Retefuente</th>
                                <th class='modulo_table_list_title'>Rete Ica</th>
                                <th class='modulo_table_list_title'>Iva</th>
                                <th class='modulo_table_list_title'>Subtotal</th>
                                <th class='modulo_table_list_title'>Total</th>
                            </tr>
                            <tr>
                                <th class='modulo_list_claro'><input type=\"text\" class=\"input-text valor\" value='{$calculofactura["retefuente"]}'  id='calculoretefuente' value='' disabled /></th>
                                <th class='modulo_list_claro'><input type=\"text\" class=\"input-text valor\" value='{$calculofactura["reteica"]}'  id='calculoreteica' value='' disabled /></th>
                                <th class='modulo_list_claro'><input type=\"text\" class=\"input-text valor\" value='{$calculofactura["total_iva"]}'  id='calculoiva' value='' disabled /></th>
                                <th class='modulo_list_claro'><input type=\"text\" class=\"input-text valor\" value='{$calculofactura["sub_total"]}'  id='calculototal' value='' disabled /></th>
                                 <th class='modulo_list_claro'><input type=\"text\" class=\"input-text valor\" value='{$calculofactura["total_nota"]}'  id='calculototal' value='' disabled /></th>
                            </tr>
                        </table></br></br>
        ";
                                 
            $html .= "<form name=\"FormaCrearNotaOficial\" id=\"FormaCrearNotaOficial\" action=\"" . $action['GuardarNotaDebito'] . "\" method=\"post\">\n";
            $html .= "  <table class=\"modulo_table_list\" align=\"center\">\n";
            $html .= "      <tr class=\"formulacion_table_list\">\n";
            $html .= "          <td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "              <input type=\"hidden\" name=\"nota_debito_despacho_cliente_id\" value=\"" . $tmp_nota_debito_despacho_cliente_id['tmp_nota_debito_despacho_cliente_id'] . "\">\n";
            $html .= "              <input class=\"input-submit\" type=\"submit\" name=\"guarda\" value=\"CREAR NOTA(S)\">\n";
            $html .= "          </td>\n";
            $html .= "      </tr>\n";
            $html .= "  </table>\n";
            $html .= "</form>\n";
            $html .= "  <br><br>";
        }
        $html .= "<table align=\"center\">\n";
        $html .= "  <tr>\n";
        $html .= "      <td align=\"center\" class=\"label_error\">\n";
        $html .= "        <a href=\"" . $action['volver'] . "\">VOLVER</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= ThemeCerrarTabla();
        return $html;
    }

    function FormaMensajeModulo($action, $mensaje, $modoNota, $url)
    {
        $html = ThemeAbrirTabla('NOTAS ' . $modoNota);
        $html .= "<script>\n";
        $html .= "  function ImprimirNotaCredito(url)\n";
        $html .= "  {\n";
        $html .= "      window.open(url, \"marco\", \"width=1000, height=600, scrollbars=yes\");\n";
        $html .= "  }\n";
        $html .= "</script>\n";
        $html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
        $html .= "  <tr>\n";
        $html .= "      <td>\n";
        $html .= "          <table width=\"100%\" class=\"modulo_table_list\">\n";
        $html .= "              <tr class=\"normal_10AN\">\n";
        $html .= "                  <td align=\"center\">\n";
        $html .= "                      " . $mensaje . "\n";
        $html .= "                      <a href=\"javascript:ImprimirNotaCredito('" . $url . "')\" title=\"IMPRIMIR\">\n";
        $html .= "                          <img border=\"0\" src=\"themes/HTML/AzulXp/images/imprimir.png\" title=\"IMPRIMIR NOTA CRÉDITO\">\n";
        $html .= "                      </a>\n";
        $html .= "                  </td>\n";
        $html .= "              </tr>\n";
        $html .= "          </table>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr>\n";
        $html .= "      <td align=\"center\" class=\"label_error\">\n";
        $html .= "          <br><a href=\"" . $action['volver'] . "\">VOLVER</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>";
        $html .= "</table>";
        $html .= ThemeCerrarTabla();
        return $html;
    }

    function DetalleNotaCredito($nota_credito_despacho_cliente_id, $informacionTerceroFactura, $productos, $valoresTotalesNotaCredito, $valoFinalNotaCredito, $imagen, $concepto_nota)
    {

        $html = "
         <style>
            @media print
                    {    
                        .no-print
                        {
                            display: none !important;
                        }
                    }
         </style>

        <script>
                    function imprimirNota(link){
                        window.print();
                        return false;
                    }

        </script>";

        $html .= "          <table rules=\"all\" width=\"100%\" align=\"center\" style=\"border: 1px solid rgb(0, 0, 0); font-size: 8.5px;\">\n";
        $html .= "              <tr class=\"label\">\n";
        $html .= "                  <td colspan=\"3\" align=\"center\">\n";
        $html .= "                       <table rules=\"all\" width=\"100%\" align=\"center\" style=\"border: 0px solid rgb(0, 0, 0); font-size: 8.5px;\">\n";
        $html .= "                          <tr class=\"label\">\n";
        $html .= "                              <td align=\"center\">\n";
        $html .= "                                  COMERCIALIZADORA<br>\n";
        $html .= "                                  <img border=\"0\" src=\"" . $imagen . "\"><br>\n";
        $html .= "                                  NIT 830.080.649-2<br>\n";
        $html .= "                                  Calle 9B No. 42-115 • Tels: 488 2020 • Cali • Valle<br>\n";
        $html .= "                                  Calle 45A No. 14-46 • Tels: 5714785 • Bogotá, D.C.<br>\n";
        $html .= "                              </td>\n";
        $html .= "                              <td align=\"center\">\n";
        $html .= "                                  <h2>NOTA CRÉDITO No. " . $nota_credito_despacho_cliente_id . " - POR: " . $informacionTerceroFactura['tipo'] . "</h2><br>\n";
        $html .= "                                  <h2>FACTURA No. " . $informacionTerceroFactura['prefijo'] . " - " . $informacionTerceroFactura['factura_fiscal'] . "</h2>\n"; 
        $html .= "                                  <h2>CONCEPTO NOTA: " . $informacionTerceroFactura['descripcion_concepto'] . "</h2>\n";
        
        
        $html .= "                              </td>\n";
        $html .= "                          </tr>\n";
        $html .= "                       </table>\n";
        $html .= "                  </td>\n";
        $html .= "              </tr>\n";
        $html .= "              <tr class=\"label\">\n";
        $html .= "                  <td align=\"left\">\n";
        $html .= "                      Fecha: " . $informacionTerceroFactura['fecha_registro_nota'] . "\n";
        $html .= "                  </td>\n";
        $html .= "                  <td align=\"left\">\n";
        $html .= "                      Señor(es): " . $informacionTerceroFactura['nombre_tercero'] . "\n";
        $html .= "                  </td>\n";
        $html .= "                  <td align=\"left\">\n";
        $html .= "                      " . $informacionTerceroFactura['tipo_id_tercero'] . ": " . $informacionTerceroFactura['tercero_id'] . "\n";
        $html .= "                  </td>\n";
        $html .= "              </tr>\n";
        $html .= "              <tr class=\"label\">\n";
        $html .= "                  <td colspan=\"2\" align=\"left\">\n";
        $html .= "                      Dirección: " . $informacionTerceroFactura['direccion'] . "\n";
        $html .= "                  </td>\n";
        $html .= "                  <td align=\"left\">\n";
        $html .= "                      Tel: " . $informacionTerceroFactura['telefono'] . "\n";
        $html .= "                  </td>\n";
        $html .= "              </tr>\n";
        $html .= "              <tr class=\"label\">\n";
        if ($informacionTerceroFactura['tipo'] == "DEVOLUCIÓN")
        {
            $html .= "                  <td align=\"center\">\n";
            $html .= "                      CONCEPTO\n";
            $html .= "                  </td>\n";
            $html .= "                  <td align=\"center\">\n";
            $html .= "                      CANTIDAD DEVUELTA\n";
            $html .= "                  </td>\n";
        }
        else
        {
            $html .= "                  <td colspan=\"2\" align=\"center\">\n";
            $html .= "                      CONCEPTO\n";
            $html .= "                  </td>\n";
        }
        $html .= "                  <td align=\"center\">\n";
        $html .= "                      VALOR\n";
        $html .= "                  </td>\n";
        $html .= "              </tr>\n";
        $subtotal = 0;
        
         if($concepto_nota["id"] == 1 || is_null($concepto_nota)){
                for ($i = 0; $i < count($productos); $i++)
                {
                    $html .= "              <tr class=\"label\">\n";
                    if ($informacionTerceroFactura['tipo'] == "DEVOLUCIÓN")
                    {
                        $html .= "                  <td>\n";
                        $html .= "                      " . $productos[$i]['codigo_producto'] . " - " . $productos[$i]['descripcion'] . "</br>";
                         if(trim($productos[$i]['observacion']) != ""){
                            $html .= "{$productos[$i]['observacion']} </br></br> ";
                        }
                        $html .= "                  </td>\n";
                        $html .= "                  </td>\n";
                        $html .= "                  <td>\n";
                        $html .= "                      " . (int)$productos[$i]['cantidad_devuelta'] . "\n";
                        $html .= "                  </td>\n";
                    }
                    else
                    {
                        $html .= "                  <td colspan=\"2\">\n";
                        $html .= "                      " . $productos[$i]['codigo_producto'] . " - " . $productos[$i]['descripcion'] . "</br>";
                        if(trim($productos[$i]['observacion']) != ""){
                            $html .= "{$productos[$i]['observacion']} </br></br> ";
                        }
                        $html .= "                  </td>\n";
                    }
                    $html .= "                  <td align=\"center\">\n";
                    $html .= "                      $ " .FormatoValor( $productos[$i]['valor']) . "\n";
                    $html .= "                  </td>\n";
                    $html .= "              </tr>\n";

                    $subtotal += $productos[$i]['valor'];
                }
         } else {
             
             $html .= "<tr class='label'>
                                    <td colspan='3'>
                                            {$informacionTerceroFactura['descripcion_nota']}
                                    </td>

                            </tr>";
             $subtotal = $valoFinalNotaCredito;
         }
        $html .= " <tr class=\"label\"><td colspan=\"2\" align=\"right\">SUBTOTAL</td><td align=\"center\">$ ".FormatoValor($subtotal)."</td></tr>";
        $html .= "              <tr class=\"label\">\n";
        $html .= "                  <td colspan=\"2\" align=\"right\">\n";
        $html .= "                      RETENCIÓN\n";
        $html .= "                  </td>\n";
        $html .= "                  <td align=\"center\">\n";
        $html .= "                      $ " .FormatoValor( $valoresTotalesNotaCredito['valor_total_rtf']) . "\n";
        $html .= "                  </td>\n";
        $html .= "              </tr>\n";
        $html .= "              <tr class=\"label\">\n";
        $html .= "                  <td colspan=\"2\" align=\"right\">\n";
        $html .= "                      IVA\n";
        $html .= "                  </td>\n";
        $html .= "                  <td align=\"center\">\n";
        $html .= "                      $ " .FormatoValor( $valoresTotalesNotaCredito['valor_total_iva']) . "\n";
        $html .= "                  </td>\n";
        $html .= "              </tr>\n";
        $html .= "              <tr class=\"label\">\n";
        $html .= "                  <td colspan=\"2\" align=\"right\">\n";
        $html .= "                      RETE ICA\n";
        $html .= "                  </td>\n";
        $html .= "                  <td align=\"center\">\n";
        $html .= "                      $ " .FormatoValor( $valoresTotalesNotaCredito['valor_total_ica']) . "\n";
        $html .= "                  </td>\n";
        $html .= "              </tr>\n";
        $html .= "              <tr class=\"label\">\n";
        $html .= "                  <td colspan=\"2\" align=\"right\">\n";
        $html .= "                      TOTAL\n";
        $html .= "                  </td>\n";
        $html .= "                  <td>\n";
        $html .= "                      $ " .FormatoValor($valoFinalNotaCredito) . "\n";
        $html .= "                  </td>\n";
        $html .= "              </tr>\n";
        $html .= "              <tr class=\"normal_10AN\">\n";
        $html .= "                  <td colspan=\"3\" align=\"center\">\n";
        $html .= "                       <table rules=\"all\" width=\"100%\" align=\"center\" style=\"border: 1px solid rgb(0, 0, 0); font-size: 8.5px;\">\n";
        $html .= "                          <tr class=\"label\">\n";
        $html .= "                              <td align=\"center\">\n";
        $html .= "                                  Elaborado\n";
        $html .= "                              </td>\n";
        $html .= "                              <td align=\"center\">\n";
        $html .= "                                  Revisado\n";
        $html .= "                              </td>\n";
        $html .= "                              <td align=\"center\">\n";
        $html .= "                                  Autorizado\n";
        $html .= "                              </td>\n";
           $html .= "                              <td align=\"center\">\n";
        $html .= "                                  Contabilidad";
        $html .= "                              </td>\n";
           $html .= "                              <td align=\"center\">\n";
        $html .= "                                  Recibido";
        $html .= "                              </td>\n";
        $html .= "                          </tr>\n";
        $html .= "                          <tr class=\"label\">\n";
        $html .= "                              <td height=\"60\">\n";
        $html .= "                                  &nbsp;\n";
        $html .= "                              </td>\n";
        $html .= "                              <td>\n";
        $html .= "                                  &nbsp;\n";
        $html .= "                              </td>\n";
        $html .= "                              <td>\n";
        $html .= "                                  &nbsp;\n";
        $html .= "                              </td>\n";
        $html .= "                              <td>\n";
        $html .= "                                  &nbsp;\n";
        $html .= "                              </td>\n";
        $html .= "                              <td>\n";
        $html .= "                                  &nbsp;\n";
        $html .= "                              </td>\n";
        $html .= "                          </tr>\n";
        $html .= "                       </table>\n";
        $html .= "                  </td>\n";
        $html .= "              </tr>\n";
        $html .= "          </table>\n";
        $html .= "</br><a href='#' onclick='imprimirNota(this)' class='no-print'>Imprimir</a> ";
        return $html;
    }

    function DetalleNotaDebito($nota_debito_despacho_cliente_id, $informacionTerceroFactura, $productos, $valoresTotalesNotaDebito, $valoFinalNotaDebito, $imagen)
    {
        $html = "          <table rules=\"all\" width=\"100%\" align=\"center\" style=\"border: 1px solid rgb(0, 0, 0); font-size: 8.5px;\">\n";
        $html .= "              <tr class=\"label\">\n";
        $html .= "                  <td colspan=\"3\" align=\"center\">\n";
        $html .= "                       <table rules=\"all\" width=\"100%\" align=\"center\" style=\"border: 0px solid rgb(0, 0, 0); font-size: 8.5px;\">\n";
        $html .= "                          <tr class=\"label\">\n";
        $html .= "                              <td align=\"center\">\n";
        $html .= "                                  COMERCIALIZADORA<br>\n";
        $html .= "                                  <img border=\"0\" src=\"" . $imagen . "\"><br>\n";
        $html .= "                                  NIT 830.080.649-2<br>\n";
        $html .= "                                  Calle 9B No. 42-115 • Tels: 488 2020 • Cali • Valle<br>\n";
        $html .= "                                  Calle 45A No. 14-46 • Tels: 5714785 • Bogotá, D.C.<br>\n";
        $html .= "                              </td>\n";
        $html .= "                              <td align=\"center\">\n";
        $html .= "                                  <h2>NOTA DÉBITO No. " . $nota_debito_despacho_cliente_id . " - POR: VALOR</h2><br>\n";
        $html .= "                                  <h2>FACTURA No. " . $informacionTerceroFactura['prefijo'] . " - " . $informacionTerceroFactura['factura_fiscal'] . "</h2>\n";
        $html .= "                              </td>\n";
        $html .= "                          </tr>\n";
        $html .= "                       </table>\n";
        $html .= "                  </td>\n";
        $html .= "              </tr>\n";
        $html .= "              <tr class=\"label\">\n";
        $html .= "                  <td align=\"left\">\n";
        $html .= "                      Fecha: " . $informacionTerceroFactura['fecha_registro'] . "\n";
        $html .= "                  </td>\n";
        $html .= "                  <td align=\"left\">\n";
        $html .= "                      Señor(es): " . $informacionTerceroFactura['nombre_tercero'] . "\n";
        $html .= "                  </td>\n";
        $html .= "                  <td align=\"left\">\n";
        $html .= "                      " . $informacionTerceroFactura['tipo_id_tercero'] . ": " . $informacionTerceroFactura['tercero_id'] . "\n";
        $html .= "                  </td>\n";
        $html .= "              </tr>\n";
        $html .= "              <tr class=\"label\">\n";
        $html .= "                  <td colspan=\"2\" align=\"left\">\n";
        $html .= "                      Dirección: " . $informacionTerceroFactura['direccion'] . "\n";
        $html .= "                  </td>\n";
        $html .= "                  <td align=\"left\">\n";
        $html .= "                      Tel: " . $informacionTerceroFactura['telefono'] . "\n";
        $html .= "                  </td>\n";
        $html .= "              </tr>\n";
        $html .= "              <tr class=\"label\">\n";
        $html .= "                  <td colspan=\"2\" align=\"center\">\n";
        $html .= "                      CONCEPTO\n";
        $html .= "                  </td>\n";
        $html .= "                  <td align=\"center\">\n";
        $html .= "                      VALOR\n";
        $html .= "                  </td>\n";
        $html .= "              </tr>\n";
        $subtotal = 0;
        for ($i = 0; $i < count($productos); $i++)
        {
            $html .= "              <tr class=\"label\">\n";
            $html .= "                  <td colspan=\"2\">\n";
            $html .= "                      " . $productos[$i]['codigo_producto'] . " - " . $productos[$i]['descripcion'] . "</br>";
            if(trim($productos[$i]['observacion']) != ""){
                $html .= "{$productos[$i]['observacion']} </br></br> ";
            }
                
            $html .= "                  </td>\n";
            $html .= "                  <td align=\"center\">\n";
            $html .= "                      $ " .FormatoValor($productos[$i]['valor']) . "\n";
            $html .= "                  </td>\n";
            $html .= "              </tr>\n";
            
            $subtotal += $productos[$i]['valor'];
        }
        $html .= " <tr class=\"label\"><td colspan=\"2\" align=\"right\">SUBTOTAL</td><td align=\"center\">$ ".FormatoValor($subtotal)."</td></tr>";
        $html .= "              <tr class=\"label\">\n";
        $html .= "                  <td colspan=\"2\" align=\"right\">\n";
        $html .= "                      RETENCIÓN\n";
        $html .= "                  </td>\n";
        $html .= "                  <td align=\"center\">\n";
        $html .= "                      $ " .FormatoValor( $valoresTotalesNotaDebito['valor_total_rtf']) . "\n";
        $html .= "                  </td>\n";
        $html .= "              </tr>\n";
        $html .= "              <tr class=\"label\">\n";
        $html .= "                  <td colspan=\"2\" align=\"right\">\n";
        $html .= "                      IVA\n";
        $html .= "                  </td>\n";
        $html .= "                  <td align=\"center\">\n";
        $html .= "                      $ " .FormatoValor($valoresTotalesNotaDebito['valor_total_iva']) . "\n";
        $html .= "                  </td>\n";
        $html .= "              </tr>\n";
        $html .= "              <tr class=\"label\">\n";
        $html .= "                  <td colspan=\"2\" align=\"right\">\n";
        $html .= "                      RETE ICA\n";
        $html .= "                  </td>\n";
        $html .= "                  <td align=\"center\">\n";
        $html .= "                      $ " .FormatoValor( $valoresTotalesNotaDebito['valor_total_ica']) . "\n";
        $html .= "                  </td>\n";
        $html .= "              </tr>\n";
        $html .= "              <tr class=\"label\">\n";
        $html .= "                  <td colspan=\"2\" align=\"right\">\n";
        $html .= "                      TOTAL\n";
        $html .= "                  </td>\n";
        $html .= "                  <td>\n";
        $html .= "                      $ " .FormatoValor($valoFinalNotaDebito) . "\n";
        $html .= "                  </td>\n";
        $html .= "              </tr>\n";
        $html .= "              <tr class=\"normal_10AN\">\n";
        $html .= "                  <td colspan=\"3\" align=\"center\">\n";
        $html .= "                       <table rules=\"all\" width=\"100%\" align=\"center\" style=\"border: 1px solid rgb(0, 0, 0); font-size: 8.5px;\">\n";
        $html .= "                          <tr class=\"label\">\n";
        $html .= "                              <td align=\"center\">\n";
        $html .= "                                  Elaborado\n";
        $html .= "                              </td>\n";
        $html .= "                              <td align=\"center\">\n";
        $html .= "                                  Revisado\n";
        $html .= "                              </td>\n";
        $html .= "                              <td align=\"center\">\n";
        $html .= "                                  Autorizado\n";
        $html .= "                              </td>\n";
        $html .= "                          </tr>\n";
        $html .= "                          <tr class=\"label\">\n";
        $html .= "                              <td height=\"60\">\n";
        $html .= "                                  &nbsp;\n";
        $html .= "                              </td>\n";
        $html .= "                              <td>\n";
        $html .= "                                  &nbsp;\n";
        $html .= "                              </td>\n";
        $html .= "                              <td>\n";
        $html .= "                                  &nbsp;\n";
        $html .= "                              </td>\n";
        $html .= "                          </tr>\n";
        $html .= "                       </table>\n";
        $html .= "                  </td>\n";
        $html .= "              </tr>\n";
        $html .= "          </table>\n";
        return $html;
    }

    function BuscadorNotaCredito($action, $factura, $datosNotasCredito, $url, $tiponota)
    {
        $html = ThemeAbrirTabla('BUSCAR NOTAS CRÉDITO');
        $html .= "<script>\n";
        $html .= "  function ImprimirNotaCredito(url)\n";
        $html .= "  {\n";
        $html .= "      window.open(url, \"marco\", \"width=1000, height=600, scrollbars=yes\");\n";
        $html .= "  }\n";
        $html .= "
                    function confirmar_sincronizacion(empresa_id, prefijo, numero_factura, id_nota, tiponota){
                        if(confirm('Desea Sincronizar La Nota ['+id_nota+'] ')){
                            xajax_sincronizar_notas_pendientes_ws_fi(empresa_id, prefijo, numero_factura, id_nota, tiponota);
                            return false;
                        }else{                                
                            return false;
                        }
                    }
                   

                 ";
        $html .= "</script>\n";
        $html .= "<form name=\"buscadorfacturas\" action=\"" . $action['BuscarNotaCredito'] . "\" method=\"post\">\n";
        $html .= "  <table class=\"modulo_table_list\" align=\"center\">\n";
        $html .= "      <tr>\n";
        $html .= "          <td class=\"modulo_table_list_title\">FACTURA / NOTA:</td>\n";
        $html .= "          <td>\n";
        $html .= "              <input type=\"text\" class=\"input-text\" name=\"factura\" size=\"25\" value=\"" . $factura . "\">\n";
        $html .= "          </td>\n";
        $html .= "          <td>\n";
        $html .= "              <input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
        $html .= "          </td>\n";
        $html .= "      </tr>\n";
        $html .= "  </table>\n";
        $html .= "</form>\n";
        $html .= "<br><br>\n";

        if ($factura && $datosNotasCredito)
        {
            $html .= "  <table class=\"modulo_table_list\" align=\"center\">\n";
            $html .= "      <tr>\n";
            $html .= "          <td class=\"modulo_table_list_title\">NOTA</td>\n";
            $html .= "          <td class=\"modulo_table_list_title\">VALOR NOTA</td>\n";
            $html .= "          <td class=\"modulo_table_list_title\">TIPO NOTA</td>\n";
            $html .= "          <td class=\"modulo_table_list_title\">FECHA NOTA</td>\n";
             $html .= "          <td class=\"modulo_table_list_title\">CONCEPTO</td>\n";
            $html .= "          <td class=\"modulo_table_list_title\">FACTURA</td>\n";
            $html .= "          <td class=\"modulo_table_list_title\">PREFIJO FACTURA</td>\n";
            $html .= "          <td class=\"modulo_table_list_title\">TERCERO</td>\n";
            $html .= "          <td class=\"modulo_table_list_title\">IDENTIFICACIÓN</td>\n";
            $html .= "          <td class=\"modulo_table_list_title\">VALOR FACTURA</td>\n";
            $html .= "          <td class=\"modulo_table_list_title\">SALDO FACTURA</td>\n";
            $html .= "          <td class=\"modulo_table_list_title\">FECHA FACTURA</td>\n";
            $html .= "          <td class=\"modulo_table_list_title\">SINCRONIZADO DUSOFT FI</td>\n";
            $html .= "          <td class=\"modulo_table_list_title\">OP</td>\n";
            $html .= "      </tr>\n";
            
            
            for ($i = 0; $i < count($datosNotasCredito); $i++)
            {
                
                $color = "black";
                $url_images = GetThemePath() . "/images/conectado.png";
                $funcion_sincronizar = "alert('La Factura ya esta Sincronizada');";
                
                if($datosNotasCredito[$i]['estado'] != "0"){
                    $color = "red";
                     $url_images = GetThemePath() . "/images/desconectado.png";
                      $funcion_sincronizar = "confirmar_sincronizacion('{$datosNotasCredito[$i]['empresa_id']}', '{$datosNotasCredito[$i]['prefijo']}', '{$datosNotasCredito[$i]['factura_fiscal']}', '{$datosNotasCredito[$i]['nota_credito_despacho_cliente_id']}', '{$tiponota}');";
                }
            
                $html .= "      <tr class=\"modulo_list_claro\">\n";
                $html .= "          <td><a href=\"javascript:ImprimirNotaCredito('" . $url . "&nota_credito_despacho_cliente_id[nota_credito_despacho_cliente_id]=" . $datosNotasCredito[$i]['nota_credito_despacho_cliente_id'] . "')\" title=\"VER DETALLE NOTA CRÉDITO\">" . $datosNotasCredito[$i]['nota_credito_despacho_cliente_id'] . "</a></td>\n";
                $html .= "          <td>" . FormatoValor($datosNotasCredito[$i]['valor_nota']) . "</td>\n";
                $html .= "          <td>" . $datosNotasCredito[$i]['tipo_nota'] . "</td>\n";
                $html .= "          <td>" . $datosNotasCredito[$i]['fecha_registro_nota'] . "</td>\n";
                $html .= "          <td>" . $datosNotasCredito[$i]['descripcion_concepto'] . "</td>\n";
                $html .= "          <td>" . $datosNotasCredito[$i]['factura_fiscal'] . "</td>\n";
                $html .= "          <td>" . $datosNotasCredito[$i]['prefijo'] . "</td>\n";
                $html .= "          <td>" . $datosNotasCredito[$i]['nombre_tercero'] . "</td>\n";
                $html .= "          <td>" . $datosNotasCredito[$i]['tipo_id_tercero'] . " - " . $datosNotasCredito[$i]['tercero_id'] . "</td>\n";
                $html .= "          <td>$ " . FormatoValor($datosNotasCredito[$i]['valor_total']) . "</td>\n";
                $html .= "          <td>$ " . FormatoValor($datosNotasCredito[$i]['saldo']) . "</td>\n";
                $html .= "          <td>" . $datosNotasCredito[$i]['fecha_registro'] . "</td>\n";
                $html .= "          <td style='color:{$color}'>" . $datosNotasCredito[$i]['descripcion_estado'] . "</td>\n";
                   
               
                $html .= "	    <td align=\"center\" ><a href='#' onclick=\"{$funcion_sincronizar}\"><img title=\"SINCRONIZAR CON FI\" src='{$url_images}' border=\"0\"></a></td>\n";
                $html .= "      </tr>\n";
            }
            $html .= "  </table>\n";
            $html .= "  <br><br><br><br>\n";
        }
        elseif ($factura && empty($datosNotasCredito))
        {
            $html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
            $html .= "<br><br>\n";
        }
        $html .= "<table align=\"center\">\n";
        $html .= "  <tr>\n";
        $html .= "      <td align=\"center\" class=\"label_error\">\n";
        $html .= "        <a href=\"" . $action['volver'] . "\">VOLVER</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= ThemeCerrarTabla();
        return $html;
    }

    function BuscadorNotaDebito($action, $factura, $datosNotasDebito, $url, $tiponota)
    {
        $html = ThemeAbrirTabla('BUSCAR NOTAS DÉBITO');
        $html .= "<script>\n";
        $html .= "  function ImprimirNotaDebito(url)\n";
        $html .= "  {\n";
        $html .= "      window.open(url, \"marco\", \"width=1000, height=600, scrollbars=yes\");\n";
        $html .= "  }\n";
       $html .= "
                    function confirmar_sincronizacion(empresa_id, prefijo, numero_factura, id_nota, tiponota){
                        if(confirm('Desea Sincronizar La Nota ['+id_nota+'] ')){
                            xajax_sincronizar_notas_pendientes_ws_fi(empresa_id, prefijo, numero_factura, id_nota, tiponota);
                            return false;
                        }else{                                
                            return false;
                        }
                    }
                   

                 ";
        $html .= "</script>\n";
        $html .= "<form name=\"buscadorfacturas\" action=\"" . $action['BuscarNotaDebito'] . "\" method=\"post\">\n";
        $html .= "  <table class=\"modulo_table_list\" align=\"center\">\n";
        $html .= "      <tr>\n";
        $html .= "          <td class=\"modulo_table_list_title\">FACTURA / NOTA:</td>\n";
        $html .= "          <td>\n";
        $html .= "              <input type=\"text\" class=\"input-text\" name=\"factura\" size=\"25\" value=\"" . $factura . "\">\n";
        $html .= "          </td>\n";
        $html .= "          <td>\n";
        $html .= "              <input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
        $html .= "          </td>\n";
        $html .= "      </tr>\n";
        $html .= "  </table>\n";
        $html .= "</form>\n";
        $html .= "<br><br>\n";

        if ($factura && $datosNotasDebito)
        {
            
            $html .= "  <table class=\"modulo_table_list\" align=\"center\">\n";
            $html .= "      <tr>\n";
            $html .= "          <td class=\"modulo_table_list_title\">NOTA</td>\n";
            $html .= "          <td class=\"modulo_table_list_title\">VALOR NOTA</td>\n";
            $html .= "          <td class=\"modulo_table_list_title\">FECHA NOTA</td>\n";
            $html .= "          <td class=\"modulo_table_list_title\">FACTURA</td>\n";
            $html .= "          <td class=\"modulo_table_list_title\">PREFIJO FACTURA</td>\n";
            $html .= "          <td class=\"modulo_table_list_title\">TERCERO</td>\n";
            $html .= "          <td class=\"modulo_table_list_title\">IDENTIFICACIÓN</td>\n";
            $html .= "          <td class=\"modulo_table_list_title\">VALOR FACTURA</td>\n";
            $html .= "          <td class=\"modulo_table_list_title\">SALDO FACTURA</td>\n";
            $html .= "          <td class=\"modulo_table_list_title\">FECHA FACTURA</td>\n";
              $html .= "          <td class=\"modulo_table_list_title\">SINCRONIZADO DUSOFT FI</td>\n";
            $html .= "          <td class=\"modulo_table_list_title\">OP</td>\n";
            $html .= "      </tr>\n";
            for ($i = 0; $i < count($datosNotasDebito); $i++)
            {
                
                $color = "black";
                $url_images = GetThemePath() . "/images/conectado.png";
                $funcion_sincronizar = "alert('La Factura ya esta Sincronizada');";
                
                if($datosNotasDebito[$i]['estado'] != "0"){
                    $color = "red";
                     $url_images = GetThemePath() . "/images/desconectado.png";
                      $funcion_sincronizar = "confirmar_sincronizacion('{$datosNotasDebito[$i]['empresa_id']}', '{$datosNotasDebito[$i]['prefijo']}', '{$datosNotasDebito[$i]['factura_fiscal']}', '{$datosNotasDebito[$i]['nota_debito_despacho_cliente_id']}', '{$tiponota}');";
                }
                
                $html .= "      <tr class=\"modulo_list_claro\">\n";
                $html .= "          <td><a href=\"javascript:ImprimirNotaDebito('" . $url . "&nota_debito_despacho_cliente_id[nota_debito_despacho_cliente_id]=" . $datosNotasDebito[$i]['nota_debito_despacho_cliente_id'] . "')\" title=\"VER DETALLE NOTA CRÉDITO\">" . $datosNotasDebito[$i]['nota_debito_despacho_cliente_id'] . "</a></td>\n";
                $html .= "          <td>" . FormatoValor($datosNotasDebito[$i]['valor_nota']) . "</td>\n";
                $html .= "          <td>" . $datosNotasDebito[$i]['fecha_registro_nota'] . "</td>\n";
                $html .= "          <td>" . $datosNotasDebito[$i]['factura_fiscal'] . "</td>\n";
                $html .= "          <td>" . $datosNotasDebito[$i]['prefijo'] . "</td>\n";
                $html .= "          <td>" . $datosNotasDebito[$i]['nombre_tercero'] . "</td>\n";
                $html .= "          <td>" . $datosNotasDebito[$i]['tipo_id_tercero'] . " - " . $datosNotasDebito[$i]['tercero_id'] . "</td>\n";
                $html .= "          <td>$ " . FormatoValor($datosNotasDebito[$i]['valor_total']) . "</td>\n";
                $html .= "          <td>$ " . FormatoValor($datosNotasDebito[$i]['saldo']) . "</td>\n";
                $html .= "          <td>" . $datosNotasDebito[$i]['fecha_registro'] . "</td>\n";
                $html .= "          <td style='color:{$color}'>" . $datosNotasDebito[$i]['descripcion_estado'] . "</td>\n";

                $html .= "	    <td align=\"center\" ><a href='#' onclick=\"{$funcion_sincronizar}\"><img title=\"SINCRONIZAR CON FI\" src='{$url_images}' border=\"0\"></a></td>\n";
                $html .= "      </tr>\n";
            }
            $html .= "  </table>\n";
            $html .= "  <br><br><br><br>\n";
        }
        elseif ($factura && empty($datosNotasDebito))
        {
            $html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
            $html .= "<br><br>\n";
        }
        $html .= "<table align=\"center\">\n";
        $html .= "  <tr>\n";
        $html .= "      <td align=\"center\" class=\"label_error\">\n";
        $html .= "        <a href=\"" . $action['volver'] . "\">VOLVER</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= ThemeCerrarTabla();
        return $html;
    }

}

?>