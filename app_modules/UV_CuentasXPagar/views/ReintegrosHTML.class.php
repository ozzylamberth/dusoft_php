<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ReintegrosHTML.class.php,v 1.1 2009/01/14 22:22:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Vista: ReintegrosHTML
  * Clase en la que se crean las formas para el modulo de cuentas por pagar
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class ReintegrosHTML
  {
    /**
    * Constructosr de la clase
    */
    function ReintegrosHTML(){}
    /**
    */
    function FormaDatosReintegro($action,$conceptos,$dependencia,$auditores)
    {
      $styl = "style=\"text-align:left;text-indent:8pt\"";
      
      $ctl = AutoCarga::factory("ClaseUtil"); 
      $html  = $ctl->AcceptNum();
      $html  = $ctl->AcceptDate("/");
      $html .= $ctl->LimpiarCampos();
      $html .= $ctl->IsNumeric();
      $html .= $ctl->RollOverFilas();
      $html .= $ctl->TrimScript();
      $html .= "<script>\n";
      $html .= "  function PlantillaFamiliar(valor)\n";
      $html .= "  {\n";
      $html .= "    elemento = document.getElementById('cp_familiar')\n";
      $html .= "    if(document.reintegro.afiliado_id.value == '')\n";
      $html .= "    {\n";
      $html .= "      alert('NO SE HA INDICADO EL COTIZANTE');\n";
      $html .= "      document.reintegro.familiar.checked = false;\n";
      $html .= "      return true;\n";
      $html .= "    }\n";
      $html .= "    if(valor == true)\n";
      $html .= "      elemento.style.display = 'block';\n";
      $html .= "    else\n";
      $html .= "      elemento.style.display = 'none';\n";
      $html .= "    \n";
      $html .= "  }\n";
      $html .= "  function EvaluarDatosOcupacion(objeto)\n"; 
      $html .= "  {\n"; 
      $html .= "    if(objeto.grandes_grupos.value == \"-1\")\n";
      $html .= "    {\n"; 
      $html .= "      document.getElementById(\"error_ocupacion\").innerHTML = \"SE DEBE SELECCIONAR EL GRUPO\";\n"; 
      $html .= "      return true;\n"; 
      $html .= "    }\n"; 
      $html .= "    else if(objeto.sub_grupos_principales.value == \"-1\")\n";
      $html .= "      {\n"; 
      $html .= "        document.getElementById(\"error_p\").innerHTML = \"SE DEBE SELECCIONAR EL SUBGRUPO PRINCIPAL\";\n"; 
      $html .= "        return true;\n"; 
      $html .= "      }\n"; 
  		$html .= "      else if(objeto.sub_grupo.value == \"-1\")\n";
      $html .= "        {\n"; 
      $html .= "          document.getElementById(\"error_p\").innerHTML = \"SE DEBE SELECCIONAR EL SUBGRUPO\";\n"; 
      $html .= "          return true;\n"; 
      $html .= "        }\n"; 
  		$html .= "        else if(objeto.grupos_primarios.value == \"-1\")\n";
      $html .= "          {\n"; 
      $html .= "            document.getElementById(\"error_p\").innerHTML = \"SE DEBE SELECCIONAR EL GRUPO PRIMARIO\";\n"; 
      $html .= "            return true;\n"; 
      $html .= "          }\n"; 
      $html .= "    document.getElementById(\"error_p\").innerHTML = \"\";\n"; 
      $html .= "    xajax_AsignarOcupacion(xajax.getFormValues('oculta'));\n"; 
      $html .= "  }\n";      
      $html .= "  function BuscarAfiliado(off)\n";
      $html .= "  {\n";
      $html .= "    xajax_BuscarAfiliado(xajax.getFormValues('formabuscar'),off);\n";
      $html .= "  }\n";      
      $html .= "  function BuscarTercero(off)\n";
      $html .= "  {\n";
      $html .= "    xajax_BuscarTercero(xajax.getFormValues('formabuscar'),off);\n";
      $html .= "  }\n";
      $html .= "  function AsiganarAfiliado(tipo_id_afiliado,afiliado_id,eps_afiliacion_id)\n";
      $html .= "  {\n";
      $html .= "    xajax_AsiganarAfiliado(tipo_id_afiliado,afiliado_id,eps_afiliacion_id);\n";
      $html .= "  }\n";        
      $html .= "  function AsignarFamiliar(tipo_id_afiliado,afiliado_id,eps_afiliacion_id)\n";
      $html .= "  {\n";
      $html .= "    xajax_AsignarFamiliar(tipo_id_afiliado,afiliado_id,eps_afiliacion_id);\n";
      $html .= "  }\n";      
      $html .= "  function LabelOcupacion(valor)\n";
      $html .= "  {\n";
      $html .= "    document.reintegro.texto.value = valor;\n";
      $html .= "  }\n";      
      $html .= "  function OcupacionDefecto()\n";
      $html .= "  {\n";
      $html .= "    xajax_AsignarOcupacion(xajax.getFormValues('reintegro'));\n";
      $html .= "  }\n";      
      $html .= "  function SeleccionarOcupacion()\n";
      $html .= "  {\n";
      $html .= "    xajax_SeleccionaraOcupacion(xajax.getFormValues('oculta'),2);\n";
      $html .= "  }\n";      
      $html .= "  function ContinuarReintegro()\n";
      $html .= "  {\n";
      $html .= "    document.reintegro.submit();\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      $html .= ThemeAbrirTabla('CUENTAS X PAGAR - REINTEGROS');
      $html .= "<form name=\"reintegro\" id=\"reintegro\" action=\"".$action['aceptar']."\" method=\"post\">\n";
      $html .= "<center>\n";
      $html .= "  <fieldset  class=\"fieldset\" style=\"width:70%\">\n";
      $html .= "    <legend class=\"normal_10AN\">REGISTRO DE INFORMACION DE CUENTAS DE REINTEGRO</legend>\n";
      $html .= "    <table width=\"99%\" align=\"center\">\n";
      $html .= "      <tr>\n";
      $html .= "        <td align=\"center\">\n";
      $html .= "	        <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
      $html .= "            <tr class=\"formulacion_table_list\">\n";
      $html .= "              <td ".$styl." width=\"35%\">FECHA DE SOLICITUD</td>\n";
      $html .= "              <td align=\"left\" class=\"modulo_list_claro\" >\n";
      $html .= "                <input size=\"12\" type=\"text\" name=\"fecha_solicitud\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".date("d/m/Y")."\">\n";
      $html .= "                ".ReturnOpenCalendario('reintegro','fecha_solicitud','/')."\n";
      $html .= "              </td>\n";
      $html .= "            </tr>\n";
      $html .= "		        <tr class=\"formulacion_table_list\">\n";
			$html .= "			        <td ".$styl.">NOMBRE DEL SOLICITANTE </td>\n";
			$html .= "			        <td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "			          <label id=\"solicitante\"></label>\n";
			$html .= "				        <a href=\"#\" onclick=\"return xajax_BuscarAfiliado()\" class=\"label_error\">BUSCAR AFILIADO</a>\n";
			$html .= "			        </td>\n";
			$html .= "		        </tr>\n";      
      $html .= "		        <tr class=\"formulacion_table_list\">\n";
			$html .= "			        <td ".$styl.">IDENTIFICACION </td>\n";
			$html .= "			        <td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "			          <label id=\"identificacion\"></label>\n";
			$html .= "				        <input type=\"hidden\" name=\"afiliado_tipo_id\" id=\"afiliado_tipo_id\" value=\"\"> \n";
			$html .= "				        <input type=\"hidden\" name=\"afiliado_id\" id=\"afiliado_id\" value=\"\">\n";
			$html .= "				        <input type=\"hidden\" name=\"eps_afiliacion_id\" id=\"eps_afiliacion_id\" value=\"\">\n";
			$html .= "			        </td>\n";
			$html .= "		        </tr>\n";
      $html .= "		        <tr class=\"formulacion_table_list\">\n";
      $html .= "			        <td ".$styl.">LUGAR EXPEDICION:</td>\n";
      $html .= "			        <td align=\"left\" class=\"modulo_list_claro\">\n";
      $html .= "    		        <input type=\"text\" name=\"lugar_expedicion_documento\" value=\"".$paciente['lugar_expedicion_documento']."\" class=\"input-text\" size=\"30\" maxlength=\"60\">\n";
      $html .= "			        </td>\n";
      $html .= "		        </tr>\n";
      $html .= "		        <tr class=\"formulacion_table_list\">\n";
      $html .= "			        <td ".$styl.">ESTAMENTO:</td>\n";
      $html .= "			        <td align=\"left\" class=\"modulo_list_claro\">\n";
      $html .= "    		        <label id=\"estamento\"></label>\n";
      $html .= "    		        <input type=\"hidden\" name=\"estamento_id\" id=\"estamento_id\" value=\"\">\n";
      $html .= "			        </td>\n";
      $html .= "		        </tr>\n";   
      $html .= "						<tr class=\"formulacion_table_list\">\n";
			$html .= "							<td ".$styl.">OCUPACION</td>\n";
			$html .= "							<td class=\"modulo_list_claro\" align=\"left\">\n";
 			$html .= "				        <a title=\"SELECCIONAR OCUPACION\" href=\"#\" onclick=\"xajax_SeleccionaraOcupacion(xajax.getFormValues('reintegro'),1)\">\n";
			$html .= "				          <img src=\"".GetThemePath()."/images/pcopiar.png\" border=\"-1\" width=\"16\" height=\"16\">\n";
			$html .= "				        </a>\n";
			$html .= "				        <label id=\"ocupacion_texto\"></label>\n";
			$html .= "							</td>\n";	
			$html .= "						</tr>\n";

      $html .= "						<tr class=\"formulacion_table_list\">\n";
			$html .= "							<td ".$styl." >DEPENDENCIA:</td>\n";
			$html .= "							<td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "								<select name=\"dependencia_laboral\" class=\"select\">\n";
			$html .= "									<option value=\"-1\">-SELECCIONAR-</option>\n";
			
      foreach($dependencia as $key => $detalle)
        $html .= "									<option value=\"".$key."\" $sl>".$detalle['descripcion_dependencia']."</option>\n";
      
			$html .= "								</select>\n";
      $html .= "							</td>\n";	
			$html .= "						</tr>\n";
   
      $html .= "		        <tr class=\"formulacion_table_list\">\n";
      $html .= "			        <td ".$styl." rowspan=\"".(sizeof($conceptos)+1)."\">CONCEPTO DE REINTEGRO:</td>\n";
      
      if(!empty($conceptos))
      {
        $html .= "			        <td align=\"left\" class=\"modulo_list_claro\">\n";
        $html .= "    		        <input type=\"radio\" name=\"concepto_reintegro\" value=\"".$conceptos[0]['cxp_concepto_reintegro_id']."\" onclick=\"document.reintegro.otro_concepto.disable = true\">".$conceptos[0]['descripcion_concepto']."\n";
        $html .= "			        </td>\n";
        $html .= "		        </tr>\n";
        foreach($conceptos as $key => $detalle)
        {
          if($key > 0)
          {
            $html .= "		        <tr class=\"formulacion_table_list\">\n";
            $html .= "			        <td align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "    		        <input type=\"radio\" name=\"concepto_reintegro\" value=\"".$detalle['cxp_concepto_reintegro_id']."\" onclick=\"document.reintegro.otro_concepto.disable = true\">".$detalle['descripcion_concepto']."\n";
            $html .= "			        </td>\n";
            $html .= "		        </tr>\n";
          }
        }
        $html .= "		        <tr class=\"formulacion_table_list\">\n";
      }
      $html .= "			        <td align=\"left\" class=\"modulo_list_claro\">\n";
      $html .= "    		        <input type=\"radio\" name=\"concepto_reintegro\" value=\"OT\" onclick=\"document.reintegro.otro_concepto.disable = false\">OTRO:\n";
      $html .= "    		        <input type=\"text\" class=\"input-text\" name=\"otro_concepto\" disable=\"true\" style=\"width:60%\">\n";
      $html .= "			        </td>\n";
      $html .= "		        </tr>\n";
      
      $html .= "            <tr class=\"formulacion_table_list\">\n"; 
      $html .= "              <td ".$styl.">VALOR SOLICITADO</td>\n"; 
      $html .= "              <td align=\"left\"  class=\"modulo_list_claro\">\n";
      $html .= "                <input style=\"width:40%\"  type=\"text\" name=\"valor_solicitado\" class=\"input-text\" onkeypress=\"return acceptNum(event)\">\n";
      $html .= "              </td>\n"; 
      $html .= "            </tr>\n"; 
      
      $html .= "		        <tr class=\"formulacion_table_list\">\n";
			$html .= "			        <td ".$styl.">PRESTADOR DEL SERVICIO</td>\n";
			$html .= "			        <td align=\"left\" class=\"modulo_list_claro\">\n";
      $html .= "                <input style=\"width:70%\"  type=\"text\" name=\"prestador_servicio\" id=\"prestador_servicio\" class=\"input-text\">\n";
      $html .= "                <input type=\"hidden\" name=\"codigo_proveedor\" id=\"codigo_proveedor\" value=\"\">\n";
			$html .= "				        <a href=\"#\" onclick=\"BuscarTercero('-1')\" class=\"label_error\">BUSCAR TERCERO</a>\n";
			$html .= "			        </td>\n";
			$html .= "		        </tr>\n";   
      
      $html .= "			      <tr class=\"formulacion_table_list\" >\n";
      $html .= "			        <td colspan=\"2\">RAZON POR LA CUAL NO UTILIZO LOS SERVICIOS NORMALES</td>\n";
      $html .= "			      </tr>\n";
      $html .= "			      <tr class=\"formulacion_table_list\">\n";
      $html .= "			        <td colspan=\"2\">\n";
      $html .= "                <textarea class=\"textarea\" name=\"explicacion\" style=\"width:100%\" rows=\"3\">".$informacion['observacion']."</textarea>\n";
      $html .= "              </td>\n";
      $html .= "			      </tr>\n";
      $html .= "			      <tr class=\"formulacion_table_list\" >\n";
      $html .= "			        <td colspan=\"2\">OBSERVACIONES</td>\n";
      $html .= "			      </tr>\n";
      $html .= "			      <tr class=\"formulacion_table_list\">\n";
      $html .= "			        <td colspan=\"2\">\n";
      $html .= "                <textarea class=\"textarea\" name=\"observacion\" style=\"width:100%\" rows=\"3\">".$informacion['observacion']."</textarea>\n";
      $html .= "              </td>\n";
      $html .= "			      </tr>\n";
      $html .= "          </table>\n";
      $html .= "        </td>\n";
      $html .= "		  </tr>\n";   
      $html .= "      <tr>\n";
      $html .= "        <td align=\"center\">\n";
      $html .= "	        <fieldset class=\"fieldset\" >\n";
      $html .= "            <legend class=\"normal_10AN\">";
      $html .= "              <input type=\"checkbox\" name=\"familiar_cx\" value=\"1\" onclick=\"PlantillaFamiliar(this.checked)\">";
      $html .= "              EL REINTEGRO SOLICITADO CORRESPONDE A SERVICIOS PRESTADOS A UN FAMILIAR";
      $html .= "            </legend>\n";
      $html .= "            <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
      $html .= "              <tr class=\"formulacion_table_list\">\n";
      $html .= "                <td ".$styl." width=\"35%\">NOMBRE DEL FAMILIAR</td>\n";
      $html .= "                <td align=\"left\" class=\"modulo_list_claro\" >\n";
			$html .= "                  <div id=\"cp_familiar\" style=\"display:none\">\n";
			$html .= "			              <label id=\"familiar\"></label>\n";
			$html .= "                    <a href=\"#\" onclick=\"return xajax_BuscarFamiliar(xajax.getFormValues('reintegro'))\" class=\"label_error\">BUSCAR BENEFICIARIO</a>\n";
      $html .= "                  </div>\n";
      $html .= "                  <input type=\"hidden\" name=\"familiar_tipo_id\" id=\"familiar_tipo_id\" value=\"\">\n";
      $html .= "                  <input type=\"hidden\" name=\"familiar_id\" id=\"familiar_id\" value=\"\">\n";
      $html .= "                </td>\n";
      $html .= "              </tr>\n";
      $html .= "		          <tr class=\"formulacion_table_list\">\n";
			$html .= "                <td ".$styl.">PARENTESCO </td>\n";
			$html .= "                <td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "                  <label id=\"parentesco\"></label>\n";
			$html .= "                  <input type=\"hidden\" name=\"parentesco_id\" id=\"parentesco_id\" value=\"\">\n";
			$html .= "                </td>\n";
			$html .= "		          </tr>\n";      
      $html .= "            </table>\n";
      $html .= "          </fieldset>\n";
      $html .= "        </td>\n";
      $html .= "      </tr>\n";
      $html .= "      <tr>\n";
      $html .= "        <td align=\"center\">\n";
      $html .= "	        <fieldset class=\"fieldset\" >\n";
      $html .= "            <legend class=\"normal_10AN\">DATOS DE LA FACTURA</legend>\n";
      $html .= "	          <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "		          <tr class=\"formulacion_table_list\">\n";
			$html .= "			          <td width=\"35%\" ".$styl.">PREFIJO FACTURA: </td>\n";
			$html .= "			          <td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "				          <input type=\"text\" class=\"input-text\" name=\"prefijo_factura\">\n";
			$html .= "			          </td>\n";
			$html .= "		          </tr>\n";
      $html .= "		          <tr class=\"formulacion_table_list\">\n";
			$html .= "			          <td ".$styl.">Nº FACTURA: </td>\n";
			$html .= "			          <td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "				          <input type=\"text\" class=\"input-text\" name=\"numero_factura\">\n";
			$html .= "			          </td>\n";
			$html .= "		          </tr>\n";
      $html .= "              <tr class=\"formulacion_table_list\">\n";
      $html .= "                <td ".$styl.">FECHA FACTURA</td>\n";
      $html .= "                <td align=\"left\" class=\"modulo_list_claro\">\n";
      $html .= "                  <input size=\"12\" type=\"text\" name=\"fecha_factura\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" >\n";
      $html .= "                  ".ReturnOpenCalendario('reintegro','fecha_factura','/')."\n";
      $html .= "                </td>\n";
      $html .= "              </tr>\n";
      $html .= "		          <tr class=\"formulacion_table_list\">\n";
			$html .= "			          <td ".$styl." >AUDITOR ADMINISTRATIVO: </td>\n";
			$html .= "			          <td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "				          <select name=\"auditor\" class=\"select\">\n";
			$html .= "					          <option value=\"-1\">---SELECCIONAR---</option>\n";
			
			foreach($auditores as $key => $detalle)
				$html .= "					          <option value=\"".$detalle['usuario_id']."\" >".$detalle['nombre']."</option>\n";

			$html .= "				          </select>\n";
			$html .= "			          </td>\n";
			$html .= "		          </tr>\n";
      $html .= "		          <tr class=\"formulacion_table_list\">\n";
			$html .= "			          <td ".$styl.">VALOR GRAVAMEN: </td>\n";
			$html .= "			          <td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "			            <input type=\"text\" class=\"input-text\" name=\"valor_gravamen\" style=\"width:30%\" onkeypress=\"return acceptNum(event)\" >\n";
			$html .= "			          </td>\n";
			$html .= "		          </tr>\n";
      $html .= "		          <tr class=\"formulacion_table_list\">\n";
			$html .= "			          <td ".$styl.">VALOR IVA APLICADO: </td>\n";
			$html .= "			          <td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "			            <input type=\"text\" class=\"input-text\" name=\"valor_iva\" style=\"width:30%\" onkeypress=\"return acceptNum(event)\" >\n";
			$html .= "			          </td>\n";
			$html .= "		          </tr>\n";      
      $html .= "		          <tr class=\"formulacion_table_list\">\n";
			$html .= "			          <td ".$styl.">VALOR TOTAL: </td>\n";
			$html .= "			          <td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "			            <input type=\"text\" class=\"input-text\" name=\"valor_total\" style=\"width:30%\" onkeypress=\"return acceptNum(event)\" >\n";
			$html .= "			          </td>\n";
			$html .= "		          </tr>\n";
			$html .= "	          </table>\n";
			$html .= "	        </fieldset>\n";
      $html .= "        </td>\n";
      $html .= "      </tr>\n";
      $html .= "    </table>\n";
      $html .= "  </fieldset>\n";
      $html .= "</center>\n";
 			$html .= "	<input type=\"hidden\" name=\"ciu88_grandes_grupos\" id=\"ciu88_grandes_grupos\" value=\"\">\n";
 			$html .= "	<input type=\"hidden\" name=\"ciu88_sub_grupos_principales\" id=\"ciu88_sub_grupos_principales\" value=\"\">\n";
 			$html .= "	<input type=\"hidden\" name=\"ciu88_sub_grupo\" id=\"ciu88_sub_grupo\" value=\"\">\n";
 			$html .= "	<input type=\"hidden\" name=\"ciu88_grupos_primarios\" id=\"ciu88_grupos_primarios\" value=\"\">\n";
 			$html .= "	<input type=\"hidden\" name=\"texto\" id=\"texto\" value=\"\">\n";
      $html .= "  <center>\n";
      $html .= "    <div id=\"error_forma\" class=\"label_error\"></div>\n";
      $html .= "  </center>";
      $html .= "  <table width=\"60%\" align=\"center\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\">\n";
      $html .= "        <input type=\"button\" class=\"input-submit\" name=\"aceptar\" value=\"Aceptar\" onclick=\"xajax_ValidarForma(xajax.getFormValues('reintegro'))\">\n";
      $html .= "    </td>\n";      
      $html .= "      </form>\n";
      $html .= "      <form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
      $html .= "    <td align=\"center\">\n";
      $html .= "        <input type=\"submit\" class=\"input-submit\" name=\"volver\" value=\"Volver\">\n";
      $html .= "    </td>\n";
      $html .= "      </form>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= $this->CrearVentana(400);
      $html .= ThemeCerrarTabla();	
      
      return $html; 
    }
    /**
		* Funcion donde se crea una forma con una ventana con capas para mostrar informacion
    * en pantalle
    *
    * @param string $funcion Funcion a la que se llama cuando se hace submit sobre la forma
    * @param int $tmn Tamaño que tendra la ventana
    *
    * @return string
		*/
		function CrearVentana($tmn = 350)
		{
			$html .= "<script>\n";
			$html .= "	var contenedor = 'Contenedor';\n";
			$html .= "	var titulo = 'titulo';\n";
			$html .= "	var hiZ = 5;\n";
			$html .= "	function OcultarSpan()\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
 			$html .= "			e = xGetElementById('ContenedorP');\n";
			$html .= "			e.style.display = \"none\";\n";
			$html .= "		}\n";
			$html .= "		catch(error){}\n";
			$html .= "	}\n";
			$html .= "	function OcultarSpanGrande()\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
 			$html .= "			e = xGetElementById('Contenedor');\n";
			$html .= "			e.style.display = \"none\";\n";
			$html .= "		}\n";
			$html .= "		catch(error){}\n";
			$html .= "	}\n";
			$html .= "	function MostrarSpan()\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
 			$html .= "			e = xGetElementById('ContenedorP');\n";
			$html .= "			e.style.display = \"\";\n";
			$html .= "		  Iniciar();\n";
			$html .= "		}\n";
			$html .= "		catch(error){alert(error)}\n";
			$html .= "	}\n";			
      
			$html .= "	function MostrarSpanGrande()\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
      $html .= "			e = xGetElementById('Contenedor');\n";
			$html .= "			e.style.display = \"\";\n";
			$html .= "		  IniciarGrande();\n";
			$html .= "		}\n";
			$html .= "		catch(error){alert(error)}\n";
			$html .= "	}\n";		
      
      $html .= "	function MostrarTitle(Seccion)\n";
			$html .= "	{\n";
			$html .= "		xShow(Seccion);\n";
			$html .= "	}\n";
			$html .= "	function OcultarTitle(Seccion)\n";
			$html .= "	{\n";
			$html .= "		xHide(Seccion);\n";
			$html .= "	}\n";

			$html .= "	function Iniciar()\n";
			$html .= "	{\n";
			$html .= "		contenedor = 'ContenedorP';\n";
			$html .= "		titulo = 'tituloP';\n";
      $html .= "		xGetElementById('error_p').innerHTNL = '';\n";
      $html .= "		ele = xGetElementById('ContenidoP');\n";
			$html .= "	  xResizeTo(ele,".$tmn.", 'auto');\n";	
			$html .= "		ele = xGetElementById(contenedor);\n";
			$html .= "	  xResizeTo(ele,".$tmn.", 'auto');\n";
			$html .= "	  xMoveTo(ele, xClientWidth()/4, xScrollTop()+20);\n";
			$html .= "		ele = xGetElementById(titulo);\n";
			$html .= "	  xResizeTo(ele,".($tmn - 20).", 20);\n";
			$html .= "		xMoveTo(ele, 0, 0);\n";
			$html .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$html .= "		ele = xGetElementById('cerrarP');\n";
			$html .= "	  xResizeTo(ele,20, 20);\n";
			$html .= "		xMoveTo(ele,".($tmn - 20).", 0);\n";
			$html .= "	}\n";

      $html .= "	function IniciarGrande()\n";
			$html .= "	{\n";
			$html .= "		contenedor = 'Contenedor';\n";
			$html .= "		titulo = 'titulo';\n";
			$html .= "		ele = xGetElementById('Contenido');\n";
			$html .= "	  xResizeTo(ele,800, 380);\n";			
      $html .= "		ele = xGetElementById(contenedor);\n";
			$html .= "	  xResizeTo(ele,800, 'auto');\n";
			$html .= "	  xMoveTo(ele, xClientWidth()/8, xScrollTop()+20);\n";
			$html .= "		ele = xGetElementById(titulo);\n";
			$html .= "	  xResizeTo(ele,780, 20);\n";
			$html .= "		xMoveTo(ele, 0, 0);\n";
			$html .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$html .= "		ele = xGetElementById('cerrar');\n";
			$html .= "	  xResizeTo(ele,20, 20);\n";
			$html .= "		xMoveTo(ele,780, 0);\n";
			$html .= "	}\n";
      
			$html .= "	function myOnDragStart(ele, mx, my)\n";
			$html .= "	{\n";
			$html .= "	  window.status = '';\n";
			$html .= "	  if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
			$html .= "	  else xZIndex(ele, hiZ++);\n";
			$html .= "	  ele.myTotalMX = 0;\n";
			$html .= "	  ele.myTotalMY = 0;\n";
			$html .= "	}\n";
			$html .= "	function myOnDrag(ele, mdx, mdy)\n";
			$html .= "	{\n";
			$html .= "	  if (ele.id == titulo) {\n";
			$html .= "	    xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
			$html .= "	  }\n";
			$html .= "	  else {\n";
			$html .= "	    xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
			$html .= "	  }  \n";
			$html .= "	  ele.myTotalMX += mdx;\n";
			$html .= "	  ele.myTotalMY += mdy;\n";
			$html .= "	}\n";
			$html .= "	function myOnDragEnd(ele, mx, my)\n";
			$html .= "	{\n";
			$html .= "	}\n";
			$html .= "</script>\n";
			$html .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:5\">\n";
			$html .= "	<div id='titulo' class='draggable' style=\"	text-transform: uppercase;text-align:center;\"></div>\n";
			$html .= "	<div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpanGrande()\" title=\"Cerrar\" style=\"font-size:9px;\"><img src=\"".GetThemePath()."/images/cerrarnuevo.png\" border=\"0\" ></a></div><br><br>\n";
			$html .= "	<div id='Contenido' class='d2Content'>\n";
 			$html .= "    <form name=\"formabuscar\" id=\"formabuscar\" method=\"post\">\n";
			$html .= "	    <div id=\"capa_buscador\"></div>\n";
			$html .= "	  </form>\n";
			$html .= "	</div>\n";
			$html .= "</div>\n";
			$html .= "<div id='ContenedorP' class='d2Container' style=\"display:none;z-index:5\">\n";
			$html .= "	<div id='tituloP' class='draggable' style=\"	text-transform: uppercase;text-align:center;\"></div>\n";
			$html .= "	<div id='cerrarP' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('Contenedor')\" title=\"Cerrar\" style=\"font-size:9px;\"><img src=\"".GetThemePath()."/images/cerrarnuevo.png\" border=\"0\" ></a></div><br><br>\n";
			$html .= "	<div id='ContenidoP' class='d2Content'>\n";
			$html .= "	  <form name=\"oculta\" id=\"oculta\" method=\"post\">\n";
 			$html .= "		  <div id=\"ventana\"></div>\n";
			$html .= "	    <div id='error_p' style=\"text-align:center\" class=\"label_error\"></div>\n";
			$html .= "	  </form>\n";
			$html .= "	</div>\n";			
      $html .= "</div>\n";
			return $html;
		}
    /**
    * Funcion donde se crea la forma que muestra el buscador y la lista de los afiliados
    *
		* @param array $action Vector de links de la aplicaion
		* @param array $request Vector de datos del request
		* @param array $tipos_documento Vector con los tipos de documentos
    * @param array $afiliados Vector con los datos de los afiliados encontrado, segun los criterios de busqueda
    * @param int   $pagina Numero de la pagina que se esta visualizando
    * @param int   $conteo Numero total de registros encontrado (Se usa para el paginador)
    * @param string $msgError Mensaje de error, si lo hay 
    *
    * @return String $html
    */
    function FormaBuscadorAfiliados($action,$request,$tipos_documento,$afiliados = array(),$pagina,$conteo,$msgError)
    {
      $html  = ThemeAbrirTabla('LISTADO DE AFILIADOS',"98%");
			$html .= "	<center>\n";
			$html .= "		<fieldset style=\"width:80%\" class=\"fieldset\"><legend class=\"label\">CRITERIOS DE BUSQUEDA</legend>\n";
			$html .= "    	<table border=\"0\" width=\"100%\" align=\"center\">\n";
			$html .= "        <tr>\n";
			$html .= "        	<td class=\"normal_10AN\" width=\"18%\">TIPO DOCUMENTO: </td>\n";
			$html .= "          <td width=\"32%\">\n";
			$html .= "          	<select name=\"buscador[TipoDocumento]\" class=\"select\">\n";
			$html .= "            	<option value=\"-1\">-------SELECCIONE-------</option>";
			$slt = "";
			foreach($tipos_documento as $key => $ids)
			{
				($request['TipoDocumento'] == $ids['tipo_id_tercero'])? $slt= "selected":$slt = "";
				$html .= "            	<option value=\"".$ids['tipo_id_tercero']."\" $slt>".$ids['descripcion']."</option>";
			}
			$html .= "            </select>\n";
			$html .= "          </td>\n";
			$html .= "          <td width=\"18%\" class=\"normal_10AN\">DOCUMENTO: </td>\n";
			$html .= "          <td>\n";
			$html .= "          	<input type=\"text\" class=\"input-text\" name=\"buscador[Documento]\" maxlength=\"32\" value=\"".$request['Documento']."\">\n";
			$html .= "          </td>\n";
			$html .= "				</tr>\n";
			$html .= "        <tr>\n";
			$html .= "        	<td class=\"normal_10AN\">NOMBRES:</td>\n";
			$html .= "          <td>\n";
			$html .= "          	<input type=\"text\" class=\"input-text\" name=\"buscador[Nombres]\" style=\"width:94%\" maxlength=\"64\" value=\"".$request['Nombres']."\">\n";
			$html .= "          </td>\n";
			$html .= "          <td class=\"normal_10AN\">APELLIDOS:</td>\n";
			$html .= "          <td>\n";
			$html .= "          	<input type=\"text\" class=\"input-text\" name=\"buscador[Apellidos]\" style=\"width:94%\" maxlength=\"64\" value=\"".$request['Apellidos']."\">\n";
			$html .= "          </td>\n";
			$html .= "        </tr>\n";
			$html .= "        <tr>\n";
			$html .= "         	<td colspan = '4' align=\"center\" >\n";
			$html .= "          	<table width=\"70%\">\n";
			$html .= "             	<tr align=\"center\">\n";
			$html .= "               	<td >\n";
			$html .= "                 	<input class=\"input-submit\" type=\"button\" onclick=\"".$action['buscar']."\" name=\"Buscar\" value=\"Buscar\">\n";
			$html .= "                </td>\n";
			$html .= "                <td>\n";
			$html .= "                 	<input class=\"input-submit\" type=\"button\" onclick=\"LimpiarCampos(document.formabuscar)\" name=\"Limpiar\" value=\"Limpiar Campos\">\n";
			$html .= "                </td>\n";
			$html .= "            	</tr>\n";
			$html .= "           	</table>\n";
			$html .= "          </td>\n";
			$html .= "        </tr>\n";
			$html .= "    	</table>\n";
			$html .= "      <div class=\"label_error\">".$msgError."</div>\n";
			$html .= "		</fieldset>\n";
			$html .= "	</center>\n";

      if(!empty($afiliados))
      {        
        $pghtml = AutoCarga::factory("ClaseHTML");
        $html .= $pghtml->ObtenerPaginadoXajax($conteo,$pagina,$action['paginador']);        

        $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$html .= "	  <tr class=\"formulacion_table_list\">\n";
				$html .= "		  <td width=\"%\" colspan=\"2\">AFILIADO</td>\n";
				$html .= "			<td width=\"9%\">TIPO</td>\n";
				$html .= "			<td width=\"20%\">ESTADO - SUBESTADO</td>\n";
				$html .= "			<td width=\"13%\">ESTAMENTO</td>\n";
				$html .= "			<td width=\"%\">OP</td>\n";
				$html .= "		</tr>\n";

        $est = "modulo_list_claro";
        $bck = "#CCCCCC";
				foreach($afiliados as $key => $afiliado)
				{
					($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro'; 
					($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
          
					$html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
					$html .= "		  <td width=\"12%\">".$afiliado['afiliado_tipo_id']." ".$afiliado['afiliado_id']."</td>\n";
					$html .= "		  <td >".$afiliado['apellidos_afiliado']." ".$afiliado['nombres_afiliado']."</td>\n";
					$html .= "		  <td >".$afiliado['descripcion_eps_tipo_afiliado']."</td>\n";
					$html .= "		  <td class=\"label\">".strtoupper($afiliado['descripcion_estado']." - ".$afiliado['descripcion_subestado'])."</td>\n";
					$html .= "		  <td >".strtoupper($afiliado['descripcion_estamento'])."</td>\n";
          $html .= "		  <td align=\"center\">\n";
          $html .= "	      <a class=\"label_error\" title=\"SELECCIONAR\" href=\"javascript:AsiganarAfiliado('".$afiliado['afiliado_tipo_id']."','".$afiliado['afiliado_id']."','".$afiliado['eps_afiliacion_id']."')\">\n";
					$html .= "		      <img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">\n";
					$html .= "	      </a>\n";
          $html .= "		  </td>\n";
          $html .= "		</tr>\n";
				}
				$html .= "		</table>\n";
        
        $html .= "		".$pghtml->ObtenerPaginadoXajax($conteo,$pagina,$action['paginador']);
    		$html .= "		<br>\n";
      }
      else if(!empty($request))
      {
        $html .= "		<center>\n";
        $html .= "		  <label class=\"label_error\">LA BUSQUEDA NO ARROJO RESULTADOS</label>\n";
        $html .= "		</center>\n";
      }
      $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$html .= "	  <tr>\n";
			$html .= "		  <td align=\"center\"><br>\n";
			$html .= "				<input class=\"input-submit\" type=\"button\" onclick=\"".$action['cerrar']."\" name=\"volver\" value=\"Cerrar\">\n";
			$html .= "		  </td>\n";
			$html .= "	  </tr>\n";
			$html .= "  </table>\n";
      $html .= ThemeCerrarTabla();	
      return $html;
    }
    /**
    * Funcion 
    *
 		* @param array $action Vector de links de la aplicaion
    * @param array $afiliados Vector con los datos de los afiliados encontrado, segun los criterios de busqueda
    *
    * @return String $html
    */
    function FormaMostarFamiliares($action,$beneficiarios)
    {
      $html  = ThemeAbrirTabla('BENEFICIARIOS',"98%");
      if(!empty($beneficiarios))
      {        
        $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$html .= "	  <tr class=\"formulacion_table_list\">\n";
				$html .= "		  <td width=\"75%\" colspan=\"2\">AFILIADO</td>\n";
				$html .= "			<td width=\"20%\">PARENTESCO</td>\n";
				$html .= "			<td width=\"%\">OP</td>\n";
				$html .= "		</tr>\n";

        $est = "modulo_list_claro";
        $bck = "#CCCCCC";
				foreach($beneficiarios as $key => $afiliado)
				{
					($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro'; 
					($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
          
					$html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
					$html .= "		  <td width=\"15%\">".$afiliado['afiliado_tipo_id']." ".$afiliado['afiliado_id']."</td>\n";
					$html .= "		  <td >".$afiliado['primer_apellido']." ".$afiliado['segundo_apellido']." ".$afiliado['primer_nombre']." ".$afiliado['segundo_nombre']."</td>\n";
					$html .= "		  <td >".$afiliado['descripcion_parentesco']."</td>\n";
          $html .= "		  <td align=\"center\">\n";
          $html .= "	      <a class=\"label_error\" title=\"SELECCIONAR\" href=\"javascript:AsignarFamiliar('".$afiliado['afiliado_tipo_id']."','".$afiliado['afiliado_id']."','".$afiliado['eps_afiliacion_id']."')\">\n";
					$html .= "		      <img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">\n";
					$html .= "	      </a>\n";
          $html .= "		  </td>\n";
				}
				$html .= "					</table>\n";
      }
      else
      {
        $html .= "		<center>\n";
        $html .= "		  <label class=\"label_error\">LA BUSQUEDA NO ARROJO RESULTADOS</label>\n";
        $html .= "		</center>\n";
      }
      $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$html .= "	  <tr>\n";
			$html .= "		  <td align=\"center\"><br>\n";
			$html .= "				<input class=\"input-submit\" type=\"button\" onclick=\"".$action['cerrar']."\" name=\"volver\" value=\"Cerrar\">\n";
			$html .= "		  </td>\n";
			$html .= "	  </tr>\n";
			$html .= "  </table>\n";
      $html .= ThemeCerrarTabla();
      return $html;
    }
    /**
		*
		*/
		function FormaBuscarTerceros($action,$request,$tipos_terceros,$terceros = array(),$pagina,$conteo,$msgError)
		{
			$html  = ThemeAbrirTabla("TERCEROS","98%");
			$html .= "	<fieldset class=\"fieldset\"><legend class=\"field\">BUSCADOR AVANZADO</legend>\n";
			$html .= "		<table width=\"80%\" align=\"center\">\n";
			$html .= "			<tr>\n";
      $html .= "        <td class=\"normal_10AN\" width=\"35%\">TIPO DOCUMENTO CLIENTE</td>\n";
			$html .= "				<td>\n";
			$html .= "					<select name=\"buscadortercero[tipo_id_tercero]\" class=\"select\">\n";
			$html .= "						<option value='-1'>-----SELECCIONAR-----</option>\n";
      $sel = "";
			foreach($tipos_terceros as $key => $dtl)
			{
				($dtl['tipo_id_tercero'] == $request['tipo_id_tercero'])? $sel = "selected": $sel = "";
			
				$html .= "						<option value='".$dtl['tipo_id_tercero']."' $sel>".ucwords(strtolower($dtl['descripcion']))."</option>\n";
			}
			
			$html .= "					</select>\n";
			$html .= "				</td>\n";
			$html .= "			</tr>\n";	
			$html .= "			<tr>\n";
			$html .= "				<td class=\"normal_10AN\">DOCUMENTO</td>\n";
			$html .= "				<td>\n";
			$html .= "					<input type=\"text\" class=\"input-text\" name=\"buscadortercero[tercero_id]\" size=\"30\" maxlength=\"32\" value=\"".$this->TerceroDocumento."\">\n";
			$html .= "				</td>\n";
			$html .= "			</tr>\n";
			$html .= "			<tr>\n";
			$html .= "				<td class=\"normal_10AN\">NOMBRE</td>\n";
			$html .= "				<td>\n";
			$html .= "					<input type=\"text\" class=\"input-text\" name=\"buscadortercero[nombre_tercero]\" size=\"30\" maxlength=\"100\" value=\"".$this->TerceroNombre."\">\n";
			$html .= "				</td>\n";
			$html .= "			</tr>\n";
      $html .= "        <tr>\n";
			$html .= "         	<td colspan = '2' align=\"center\" >\n";
			$html .= "          	<table width=\"70%\">\n";
			$html .= "             	<tr align=\"center\">\n";
			$html .= "               	<td >\n";
			$html .= "                 	<input class=\"input-submit\" type=\"button\" onclick=\"".$action['buscar']."\" name=\"Buscar\" value=\"Buscar\">\n";
			$html .= "                </td>\n";
			$html .= "                <td>\n";
			$html .= "                 	<input class=\"input-submit\" type=\"button\" onclick=\"LimpiarCampos(document.formabuscar)\" name=\"Limpiar\" value=\"Limpiar Campos\">\n";
			$html .= "                </td>\n";
			$html .= "            	</tr>\n";
			$html .= "           	</table>\n";
			$html .= "          </td>\n";
			$html .= "        </tr>\n";
			$html .= "    	</table>\n";
			$html .= "      <div class=\"label_error\">".$msgError."</div>\n";
			$html .= "	</fieldset>\n";
			
			if(sizeof($terceros) > 0)
			{
        $bck = "#DDDDDD";
        $est = "modulo_list_claro";
        
        $pghtml = AutoCarga::factory("ClaseHTML");
        $html .= $pghtml->ObtenerPaginadoXajax($conteo,$pagina,$action['paginador']);        

        $html .= "<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "  <tr class=\"modulo_table_list_title\">\n";
        $html .= "    <td colspan=\"2\" width=\"60%\">TERCERO</td>\n";
        $html .= "    <td width=\"20%\">DIRECCION</td>\n";
        $html .= "    <td width=\"16%\">TELEFONO</td>\n";
        $html .= "    <td>OP</td>\n";
        $html .= "  </tr>\n";
				foreach($terceros as $key => $dtl)
				{
					($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro'; 
					($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
	
					$html .= "  <tr class=\"".$est."\" height=\"21\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
          $html .= "    <td width=\"20%\">".$dtl['tipo_id_tercero']." ".$dtl['tercero_id']." </td>\n";
          $html .= "    <td>".$dtl['nombre_tercero']."</td>\n";
          $html .= "    <td>".$dtl['direccion']."</td>\n";
          $html .= "    <td>".$dtl['telefono']."</td>\n";
          $html .= "    <td>\n";
          $html .= "	    <a class=\"label_error\" title=\"SELECCIONAR\" href=\"#\" onclick=\"return xajax_AsignarTercero('".$dtl['nombre_tercero']."','".$dtl['codigo_proveedor_id']."')\">\n";
					$html .= "		    <img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">\n";
					$html .= "	    </a>\n";
          $html .= "		</td>\n";
					$html .= "	</tr>\n";
				}
				$html .= "</table><br>\n";
									
        $html .= "		".$pghtml->ObtenerPaginadoXajax($conteo,$pagina,$action['paginador']);
    		$html .= "		<br>\n";
			}
		
			$html .= "	<table width=\"60%\" align=\"center\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td align=\"center\">\n";
			$html .= "				<input type=\"button\" class=\"input-submit\" value=\"Cerrar\" onclick=\"".$action['cerrar']."\" >\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= ThemeCerrarTabla();
			return $html;
		}
  }  
?>