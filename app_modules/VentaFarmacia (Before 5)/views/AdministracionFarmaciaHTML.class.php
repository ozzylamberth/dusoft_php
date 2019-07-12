<?php

/**
 * @package IPSOFT-SIIS
 * @version $Id: AdministracionFarmaciaHTML.class.php,v 1.0
 * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Sandra Viviana Pantoja Torres
 */
IncludeClass("ClaseHTML");
IncludeClass("ClaseUtil");
$empresa = "";

class AdministracionFarmaciaHTML {

    /**
     * Constructor de la clase
     */
    function AdministracionFarmaciaHTML() {

    }



    /* Funcion que Contiene la Forma de buscar productos
     *
     * @param array $action vector que contiene los link de la aplicacion
     * @param array $empresa vector que contiene la informacion de la empresa
     * @param integer $documento Identificador del documento temporal creado
     * @param array $tipos_documentos Arreglo con los tipos de identificacion
     *
     * @return String
     */

    function FormaBuscarProductosVenta($action, $empresa, $documento, $tipos_documentos) {
        $GLOBALS['empresa'] = $empresa;
        $ctl = AutoCarga::factory("ClaseUtil");
        if(!isset($html)){ $html = ''; }

        $html .= $ctl->LimpiarCampos();
        $html .= $ctl->AcceptNum();
        $html .= "<script>\n";
        $html .= "  function BuscarProductos(offset)\n";
        $html .= "  {\n";
        $html .= "    xajax_BuscarProductos(xajax.getFormValues('Forma12'),offset)\n";
        $html .= "  }\n";
        $html .= "  function BuscadorLocaliza()\n";
        $html .= "  {\n";
        $html .= "    pais = document.datos_tercero.pais.value;\n";
        $html .= "    dpto = document.datos_tercero.dpto.value;\n";
        $html .= "    mpio = document.datos_tercero.mpio.value;\n";
        $html .= "    var url =\"classes/BuscadorLocalizacion/BuscadorLocalizacion.class.php?pais=\"+pais+\"&dept=\"+dpto+\"&mpio=\"+mpio+\"&forma=datos_tercero\";\n";
        $html .= "    window.open(url,'localidad','toolbar=no,width=500,height=350,resizable=no,scrollbars=yes').focus();\n";
        $html .= "  }\n";

        $html .= "  var existForm = setInterval(function(){\n";
        $html .= "      if(document.getElementById(\"datos_tercero\") !== undefined)\n";
        $html .= "      {\n";
        $html .= "          clearInterval(existForm);\n";
        $html .= "          document.getElementById(\"datos_tercero\").addEventListener(\"submit\", function(e)\n";
        $html .= "          {\n";
        $html .= "            e.preventDefault();\n";
        $html .= "            if(document.getElementById(\"tipo_id_tercero\").getAttribute(\"value\") == '')\n";
        $html .= "            {\n";
        $html .= "               document.getElementById('error_tercero').innerHTML = 'FAVOR ESPECIFICAR EL TIPO DE IDENTIFICACION';\n";
        $html .= "               return;\n";
        $html .= "            }\n";
        $html .= "            else if(document.getElementById(\"id_tercero\").value == '-1')\n";
        $html .= "            {\n";
        $html .= "               document.getElementById('error_tercero').innerHTML = 'FAVOR ESPECIFICAR EL NUMERO DE IDENTIFICACION';\n";
        $html .= "               return;\n";
        $html .= "            }\n";
        $html .= "            else if(document.getElementById(\"nombre_tercero\").value == '')\n";
        $html .= "            {\n";
        $html .= "               document.getElementById('error_tercero').innerHTML = 'FAVOR ESPECIFICAR EL NOMBRE DEL CLIENTE';\n";
        $html .= "               return;\n";
        $html .= "            }\n";
        $html .= "            else{\n";
        $html .= "              document.getElementById(\"datos_tercero\").submit();\n";
        $html .= "            }\n";
        $html .= "          }, false)\n";
        $html .= "      }\n";
        $html .= "  }, 1000);\n";
        //$html .= "    var url_accion = '';";
        //$html .= "    objeto.action=\"http://10.0.2.237/APP/PRUEBAS_LINA_OSPINA/Contenido.php?SIIS_SID=ec7546fd4b657121cdf61e7dde97dd8c&amp;modulo=VentaFarmacia&amp;tipo=controller&amp;metodo=RealizarPagos&amp;Caja[caja_id]=633&amp;Caja[razon_social]=FCIAS+VENTA+PUBLICO&amp;Caja[descripcion]=ZONA+SALUD&amp;Caja[descripcion3]=ZONA+SALUD&amp;Caja[empresa_id]=VP&amp;Caja[prefijo_fac_contado]=398&amp;Caja[centro_utilidad]=ZS&amp;Caja[cuenta_tipo_id]=08&amp;Caja[servicio]=3&amp;Caja[via_ingreso]=2&amp;Caja[departamento]=ZS&amp;Caja[descripcion2]=VENTA+PUBLICO&amp;Caja[tipo_factura]=6\";\n";
        $html .= "  function AccionSpan(capa,prop)\n";
        $html .= "  { \n";
        $html .= "    try\n";
        $html .= "    {\n";
        $html .= "      e = document.getElementById(capa);\n";
        $html .= "      e.style.display = prop;\n";
        $html .= "    }\n";
        $html .= "    catch(error){}\n";
        $html .= "  }\n";
        $html .= "</script>\n";

        $html .= "<script>";
        $html .= "  function reset(){";
        $html .= "      document.getElementById(\"codigo_producto\").readOnly = true;";
        $html .= "      document.getElementById(\"codigo_producto\").style.width = \"100%\";";
        $html .= "      document.getElementById(\"codigo_producto\").style.background = \"#dedede\";";
        $html .= "      document.getElementById(\"codigo_producto\").style.cursor = \"not-allowed\";";

        $html .= "      document.getElementById(\"codigo_barras\").readOnly = true;";
        $html .= "      document.getElementById(\"codigo_barras\").style.width = \"100%\";";
        $html .= "      document.getElementById(\"codigo_barras\").style.background = \"#dedede\";";
        $html .= "      document.getElementById(\"codigo_barras\").style.cursor = \"not-allowed\";";

        $html .= "      document.getElementById(\"descripcion\").readOnly = true;";
        $html .= "      document.getElementById(\"descripcion\").style.width = \"100%\";";
        $html .= "      document.getElementById(\"descripcion\").style.background = \"#dedede\";";
        $html .= "      document.getElementById(\"descripcion\").style.cursor = \"not-allowed\";";

        $html .= "      document.getElementById(\"codigo_alterno\").readOnly = true;";
        $html .= "      document.getElementById(\"codigo_alterno\").style.width = \"100%\";";
        $html .= "      document.getElementById(\"codigo_alterno\").style.background = \"#dedede\";";
        $html .= "      document.getElementById(\"codigo_alterno\").style.cursor = \"not-allowed\";";

        $html .= "      document.getElementById(\"btn_search\").setAttribute('type', 'button');";
        $html .= "      document.getElementById(\"btn_search\").style.cursor = \"not-allowed\";";
        $html .= "      document.getElementById(\"productos_buscador\").innerHTML = \"\";";
        $html .= "  }";

        $html .= "  function UpdateList(){";
        $html .= "      var url = 'app_modules/Inv_Listas_Precios/classes/VerificarPrecios.class.php';";
        $html .= "      var xhttp = '';";
        $html .= "      if (window.XMLHttpRequest) {";
        $html .= "          xhttp = new XMLHttpRequest();";
        $html .= "      } else {";
        $html .= "          xhttp = new ActiveXObject(\"Microsoft.XMLHTTP\");";
        $html .= "      }\n";
        $html .= "      xhttp.onreadystatechange = function() {\n";
        $html .= "          if (this.readyState == 4 && this.status == 200) {\n";
        $html .= "            initForm();\n";
        $html .= "            document.getElementById(\"select_listas_precios\").innerHTML = this.responseText;\n";
        $html .= "            document.getElementById(\"lista_0\").selected = false;\n";
        $html .= "            document.getElementById(\"lista_1\").selected = true;\n";
        $html .= "          }\n";
        $html .= "      };\n";
        $html .= "      xhttp.open('POST', url, true);\n";
        $html .= "      xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');\n";
        $html .= "      var empresa_id = document.getElementById(\"empresa_id\").value;\n";
        $html .= "      xhttp.send(\"accion=tipos_listas_precios&empresa=\"+empresa_id); \n";
        $html .= "  }\n";

        $html .= "  function initForm(){";
        $html .= "      var codigo_lista = document.getElementById(\"select_listas_precios\").value;";

        $html .= "      document.getElementById(\"codigo_producto\").readOnly = false;";
        $html .= "      document.getElementById(\"codigo_producto\").style.width = \"100%\";";
        $html .= "      document.getElementById(\"codigo_producto\").style.background = \"\";";
        $html .= "      document.getElementById(\"codigo_producto\").style.cursor = \"text\";";

        $html .= "      document.getElementById(\"codigo_alterno\").readOnly = false;";
        $html .= "      document.getElementById(\"codigo_alterno\").style.width = \"100%\";";
        $html .= "      document.getElementById(\"codigo_alterno\").style.background = \"\";";
        $html .= "      document.getElementById(\"codigo_alterno\").style.cursor = \"text\";";

        $html .= "      document.getElementById(\"codigo_barras\").readOnly = false;";
        $html .= "      document.getElementById(\"codigo_barras\").style.width = \"100%\";";
        $html .= "      document.getElementById(\"codigo_barras\").style.background = \"\";";
        $html .= "      document.getElementById(\"codigo_barras\").style.cursor = \"text\";";

        $html .= "      document.getElementById(\"descripcion\").readOnly = false;";
        $html .= "      document.getElementById(\"descripcion\").style.width = \"100%\";";
        $html .= "      document.getElementById(\"descripcion\").style.background = \"\";";
        $html .= "      document.getElementById(\"descripcion\").style.cursor = \"text\";";

        $html .= "      document.getElementById(\"btn_search\").setAttribute(\"type\", \"submit\");";
        $html .= "      document.getElementById(\"btn_search\").style.cursor = \"pointer\";";
        $html .= "  }";


        $html .= "  var existList = setInterval(function(){ ";
        $html .= "    if(document.getElementById(\"select_listas_precios\") != undefined){";
        $html .= "      select_listas_precios = document.getElementById(\"select_listas_precios\");";
        $html .= "      reset();";

        $html .= "      this.UpdateList();";
        $html .= "      clearInterval(existList);";
        $html .= "      var btn_search = document.getElementById(\"btn_search\");";
        $html .= "      var btn_search2 = document.getElementById(\"btn_search2\");";
        $html .= "      document.getElementById(\"select_listas_precios\").addEventListener(\"change\", function(){";
        $html .= "          initForm();";
        $html .= "      }, false);";
        $html .= "    }";
        $html .= "  }, 2000);";

        $html .= "  function LimpiarCampos_0(Forma12){";
        $html .= "      reset();";
        $html .= "      LimpiarCampos(Forma12);";
        $html .= "  }";

        $html .= "</script> ";

        $html .= ThemeAbrirTabla("BUSCAR PRODUCTO- VENTA");
        $html .= "<div id=\"capa_productos\">\n";
        $html .= "<form name=\"Forma12\" id=\"Forma12\" method=\"post\"  action=\"javascript:BuscarProductos(0)\">\n";
        $html .= "  <input id=\"empresa_id\" type=\"hidden\" name=\"empresa_id\" value=\"" . $empresa['empresa_id'] . "\">\n";
        $html .= "  <input type=\"hidden\" name=\"centro_utilidad\" value=\"" . $empresa['centro_utilidad'] . "\">\n";
        $html .= "  <input type=\"hidden\" name=\"bodega\" value=\"" . $empresa['bodega'] . "\">\n";
        SessionSetVar("bodega", $empresa['bodega']);
        $html .= "  <input type=\"hidden\" name=\"bodegas_doc_id\" value=\"" . $empresa['bodegas_doc_id'] . "\">\n";
        $html .= "  <input type=\"hidden\" name=\"usuario_id\" value=\"" . $empresa['usuario_id'] . "\">\n";
        $html .= "  <input type=\"hidden\" name=\"documento\" id=\"documento\" value=\"" . $documento['documento'] . "\">\n";
        $html .= "  <table width=\"50%\"  class=\"modulo_table_list\" align=\"center\" border=\"0\" >\n";
        $html .= "    <tr class=\"formulacion_table_list\">\n";
        $html .= "		  <td colspan=\"2\">BUSCAR PRODUCTOS</td>\n";
        $html .= "	  </tr>\n";
        $html .= "    <tr class=\"formulacion_table_list\">\n";
        $html .= "        <td width=\"30%\" align=\"left\">LISTA DE PRECIOS</td>";
        $html .= "        <td class=\"modulo_list_claro\" align=\"left\">";
        $html .= "            <select name='select_listas_precios' id='select_listas_precios'>";
        $html .= "                <option value='0' selected disabled>-Selecciona un Lista-</option>";
        $html .= "            </select>";
        $html .= "        </td>";
        $html .= "    </tr>";
        $html .= "    <tr class=\"formulacion_table_list\">\n";


        $html .= "		  <td width=\"30%\" align=\"left\">CODIGO:</td>\n";
        $html .= "	    <td class=\"modulo_list_claro\" align=\"left\">\n";
        $html .= "        <input type=\"text\" id=\"codigo_producto\" class=\"input-text\" style=\"width:100%; background: rgb(222, 222, 222); cursor: not-allowed;\" name=\"codigo_producto\" value=" . $request['codigo_producto'] . ">\n";
        $html .= "      </td>\n";
        $html .= "	  </tr>\n";
        $html .= "    <tr class=\"formulacion_table_list\">\n";
        $html .= "	    <td align=\"left\">CODIGO ALTERNO:</td>\n";
        $html .= "		  <td class=\"modulo_list_claro\" align=\"left\">\n";
        $html .= "        <input type=\"text\" id=\"codigo_alterno\" class=\"input-text\" style=\"width:100%; background: rgb(222, 222, 222); cursor: not-allowed;\" name=\"codigo_alterno\" value=" . $request['codigo_alterno'] . ">\n";
        $html .= "      </td>\n";
        $html .= "  	</tr>\n";
        $html .= "    <tr class=\"formulacion_table_list\">\n";
        $html .= "	    <td align=\"left\">CODIGO DE BARRAS:</td>\n";
        $html .= "		  <td class=\"modulo_list_claro\" align=\"left\">\n";
        $html .= "        <input type=\"text\" id=\"codigo_barras\" class=\"input-text\" style=\"width:100%; background: rgb(222, 222, 222); cursor: not-allowed;\" name=\"codigo_barras\" value=" . $request['codigo_barras'] . ">\n";
        $html .= "      </td>\n";
        $html .= "	  </tr>\n";
        $html .= "    <tr class=\"formulacion_table_list\">\n";
        $html .= "	    <td align=\"left\">DESCRIPCION:</td>\n";
        $html .= "		  <td class=\"modulo_list_claro\" align=\"left\">\n";
        $html .= "        <input type=\"text\" id=\"descripcion\" class=\"input-text\" style=\"width:100%; background: rgb(222, 222, 222); cursor: not-allowed;\" name=\"descripcion\" value=" . $request['descripcion'] . ">\n";
        $html .= "      </td>\n";
        $html .= "	  </tr>\n";
        $html .= "  </table>\n";
        $html .= "	<table height=\"40\" width=\"50%\" align=\"center\" >\n";
        $html .= "    <tr>\n";
        $html .= "	    <td width=\"50%\" align=\"center\">\n";
        $html .= "			  <input class=\"input-submit\" type=\"button\" style=\"cursor: not-allowed;\" id=\"btn_search\" name=\"Buscar\" value=\"Buscar\"  >\n";
        $html .= "		  </td>\n";
        $html .= "			<td width=\"50%\" align=\"center\">\n";
        $html .= "			  <input class=\"input-submit\" type=\"button\" style=\"cursor: pointer;\" name=\"limpiar\" onclick=\"LimpiarCampos_0(document.Forma12)\" value=\"Limpiar Campos\">\n";
        $html .= "	  	</td>\n";
        $html .= "	  </tr>\n";
        $html .= "  </table>\n";
        $html .= "</form>\n";
        $html .= "<center>\n";
        $html .= "  <div id=\"mensaje\" class=\"normal_10AN\"></div>\n";
        $html .= "</center>\n";
        $html .= "<form name=\"buscador_resultados\" id=\"buscador_resultados\" method=\"post\"  action=\"\">\n";
        $html .= "  <div id=\"productos_asignados\"></div>\n";
        $html .= "  <div id=\"productos_buscador\"></div>\n";
        $html .= "</form>\n";
        $html .= "<table align=\"center\" width=\"50%\">\n";
        $html .= "  <tr>\n";
        $html .= "    <td align=\"center\">\n";
        $html .= "      <a href=\"" . $action['volver'] . " \" class=\"label_error\">\n";
        $html .= "        SALIR\n";
        $html .= "      </a>\n";
        $html .= "    </td>\n";
        $rprt = ModuloGetURL('app', 'VentaFarmacia', 'controller', "Reimprimir") . "&DatosEmpresa[empresa_id]=" . $empresa['empresa_id'] . "&DatosEmpresa[centro_utilidad]=" . $empresa['centro_utilidad'] . "&DatosEmpresa[bodega]=" . $empresa['bodega'];
        $html .= "    <td align=\"right\">\n";
        $html .= "      <a href=\"" . $rprt . "\" class=\"label_error\">\n";
        $html .= "        REIMPRIMIR FACTURAS\n";
        $html .= "      </a>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= "</div>\n";
        $html .= "<script>";
        $html .= "</script>";//$empresa['empresa_id'];
        $html .= "<div id=\"capa_tercero\" style=\"display:none\">\n";
        $html .= "  <form name=\"datos_tercero\" id=\"datos_tercero\" action=\"".$action['tercero']."\" method=\"post\">\n";
        $html .= "		<input type=\"hidden\" name=\"pais\" id=\"pais\" value=\"" . $pais . "\">\n";
        $html .= "		<input type=\"hidden\" name=\"dpto\" id=\"dpto\" value=\"" . $dpto . "\">\n";
        $html .= "		<input type=\"hidden\" name=\"mpio\" id=\"mpio\" value=\"" . $mpio . "\">\n";
        $html .= "    <input type=\"hidden\" name=\"documento\" id=\"temporal\" value=\"" . $documento['documento'] . "\">\n";
        $html .= "    <table width=\"60%\" align=\"center\">\n";
        $html .= "      <tr>\n";
        $html .= "        <td>\n";
        $html .= "          <fieldset class=\"fieldset\">\n";
        $html .= "            <legend class=\"normal_10AN\">DATOS CLIENTE</legend>\n";
        $html .= "            <table width=\"98%\" align=\"center\" class=\"normal_10AN\">\n";
        $html .= "              <tr>\n";
        $html .= "                <td width=\"30%\" >* TIPO DOCUMENTO</td>\n";
        $html .= "                <td width=\"70%\" >\n";
        $html .= "                  <select id=\"tipo_id_tercero\" name=\"tipo_id_tercero\" class=\"select\">\n";
        $html .= "                      <option value=\"-1\">--SELECCIONAR--</option> \n";
                                        foreach ($tipos_documentos as $key => $dtl){
                                            if($dtl['tipo_id_tercero'] === 'NIT'){ $selected = 'selected'; }
                                            else{ $selected = ''; }
                                            $html .= "<option value=\"".$dtl['tipo_id_tercero']."\" ".$selected.">".$dtl['descripcion']."</option>\n";
                                        }
        $html .= "                  </select>\n";
        $html .= "                </td>\n";
        $html .= "              </tr>\n";
        $html .= "              <tr>\n";
        $html .= "                <td >* DOCUMENTO</td>\n";
        $html .= "                <td>\n";
        $html .= "                  <input type=\"text\" class=\"input-text\" name=\"tercero_id\" id=\"id_tercero\" maxlength=\"32\" style=\"width:70%\" value=\"\">\n";
        $html .= "                </td>\n";
        $html .= "              </tr>\n";
        $html .= "            </table>\n";
        $html .= "            <center>\n";
        $html .= "              <div id=\"buscador_cliente\" style=\"height:20px\">\n";

        $empresa_id = $GLOBALS['empresa']['empresa_id'];

        $html .= "                <a href=\"#buscador_cliente\" onclick=\"javascript:xajax_BuscarTercero(xajax.getFormValues('datos_tercero'))\">\n";
        $html .= "                  BUSCAR CLIENTE\n";
        $html .= "                </a>\n";
        $html .= "              </div>\n";
        $html .= "              <div id=\"error_tercero\" class=\"label_error\"></div>\n";
        $html .= "            </center>\n";
        $html .= "            <table width=\"98%\" align=\"center\" class=\"normal_10AN\">\n";
        $html .= "              <tr >\n";
        $html .= "                <td width=\"30%\">* NOMBRE</td>\n";
        $html .= "                <td width=\"70%\">\n";
        $html .= "                  <input type=\"text\" class=\"input-text\" style=\"width:100%\" name=\"nombre_tercero\" id=\"nombre_tercero\" maxlenght=\"100\" value=\"\">\n";
        $html .= "                </td>\n";
        $html .= "              </tr>\n";
        $html .= "              <tr>\n";
        $html .= "                <td>DIRECCION</td>\n";
        $html .= "                <td>\n";
        $html .= "                  <input type=\"text\" class=\"input-text\" style=\"width:70%\" name=\"direccion\" id=\"direccion_tercero\" maxlength=\"100\" value=\"\">\n";
        $html .= "                </td>\n";
        $html .= "              </tr>\n";
        $html .= "              <tr class=\"normal_10AN\" height=\"20\">\n";
        $html .= "			          <td><label id=\"lugar_residencia\">* LUGAR RESIDENCIA:</label></td>\n";
        $html .= "			          <td >\n";
        $html .= "				          <label id=\"ubicacion\"></label>\n";
        $html .= "				          <input class=\"input-submit\" type=\"button\" name=\"Cambiar\" value=\"Cambiar\" onclick=\"BuscadorLocaliza()\"\">\n";
        $html .= "			          </td>\n";
        $html .= "              </tr>\n";
        $html .= "              <tr >\n";
        $html .= "                <td>TELEFONO</td>\n";
        $html .= "                <td>\n";
        $html .= "                  <input type=\"text\" class=\"input-text\" style=\"width:70%\" id=\"telefono_tercero\" name=\"telefono\" maxlength=\"30\" value=\"".$response['empresa_telefono']."\">\n";
        $html .= "                </td>\n";
        $html .= "              </tr>\n";
        $html .= "              <tr>\n";
        $html .= "                <td>CELULAR</td>\n";
        $html .= "                <td>\n";
        $html .= "                  <input type=\"text\" class=\"input-text\" style=\"width:70%\" id=\"celular_tercero\" name=\"celular\" maxlength=\"15\" value=\"".$response['empresa_celular']."\">\n";
        $html .= "                </td>\n";
        $html .= "              </tr>\n";
        $html .= "            </table>\n";
        $html .= "            <table width=\"98%\" align=\"center\" class=\"normal_10AN\">\n";
        $html .= "              <tr>\n";
        $html .= "                <td align=\"center\">\n";
        $html .= "                  <div id=\"boton_guardar\" style=\"display:none\">\n";
        $html .= "                    <input type=\"submit\" class=\"input-submit\" name=\"aceptar\" value=\"Continuar\">\n";
        $html .= "                  </div>\n";
        $html .= "                </td>\n";
        $html .= "                <td align=\"center\">\n";
        $html .= "                  <input type=\"button\" class=\"input-submit\" name=\"volver\" value=\"Cancelar\" onclick=\"AccionSpan('capa_tercero','none');AccionSpan('capa_productos','block')\">\n";
        $html .= "                </td>\n";
        $html .= "              </tr>\n";
        $html .= "            </table>\n";
        $html .= "          </fieldset>\n";
        $html .= "        </td>\n";
        $html .= "      </tr>\n";
        $html .= "    </table>\n";
        $html .= "  </form>\n";
        $html .= "</div>\n";
        $html .= "<script>\n";
        $html .= "  xajax_MostrarDetallePedido(xajax.getFormValues('Forma12'))\n";
        $html .= "</script>\n";
        $html .= ThemeCerrarTabla();
        return $html;
    }

    /**
     * Crea una forma, para mostrar mensajes informativos con un solo boton
     *
     * @param array $action vector que continen los link de la aplicacion
     * @param string $mensaje Cadena con el texto del mensaje a mostrar
     *         en pantalla
     *
     * @return string
     */
    function FormaMensajeModulo($action, $mensaje) {
        $html = ThemeAbrirTabla('MENSAJE');
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
        $html .= "	<tr>\n";
        $html .= "		<td align=\"center\"><br>\n";
        $html .= "			<form name=\"form\" action=\"" . $action['volver'] . "\" method=\"post\">";
        $html .= "				<input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">";
        $html .= "			</form>";
        $html .= "		</td>";
        $html .= "	</tr>";
        $html .= "</table>";
        $html .= ThemeCerrarTabla();
        return $html;
    }

}

?>