<?php
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */
   /**
      * Funcion que muestra la parametrizacion del conteo de los productos
      *
      * @return Object $objResponse objeto de respuesta al formulario  
      */
   function ProductosConteo()
   {
     $objResponse = new xajaxResponse();
     $html .= "<form name=\"formCantidadProducto\" id=\"formCantidadProducto\" method=\"post\" action=\"\">\n";
     $html .= "<table width=\"40%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
     $html .= "  <tr class=\"modulo_table_list_title\">\n";
     $html .= "    <td align=\"center\" colspan=\"2\">CANTIDAD PRODUCTOS\n";
     $html .= "    </td>\n";
     $html .= "  </tr>\n";
     $html .= "  <tr class=\"modulo_list_claro\"width=\"30%\">\n";
     $html .= "    <td align=\"center\">CANTIDAD\n";
     $html .= "    </td>\n";
     $html .= "    <td class=\"modulo_list_claro\" width=\"10%\">\n";
     $html .= "      <input type=\"text\" class=\"input-text\" name=\"cantida_p\" maxlength=\"5\" value=\"\">";
     $html .= "    </td>\n";
     $html .= "  </tr>\n";
     $html .= "  <tr class=\"modulo_table_list_title\">\n";
     $html .= "    <td align=\"center\" colspan=\"2\">PRODUCTOS ALEATORIOS\n";
     $html .= "    </td>\n";
     $html .= "  </tr>\n";
     $html .= "  <tr class=\"modulo_list_claro\">\n";
     $html .= "    <td align=\"center\">MAYOR ROTACION\n";
     $html .= "    </td>\n";
     $html .= "    <td align=\"center\" width=\"10%\"> \n";
     $html .= "      <input type=\"checkbox\" name=\"mayor_rotacion\" value=\"1\"> \n";
     $html .= "    </td>";
     $html .= "  </tr>\n";
     $html .= "  <tr class=\"modulo_list_claro\">\n";
     $html .= "    <td align=\"center\">MAYOR COSTO\n";
     $html .= "    </td>\n";
     $html .= "    <td align=\"center\" width=\"10%\"> \n";
     $html .= "      <input type=\"checkbox\" name=\"mayor_costo\" value=\"1\"> \n";
     $html .= "    </td>";
     $html .= "  </tr>\n";
     $html .= "  <tr class=\"modulo_table_list_title\">\n";
     $html .= "    <td align=\"center\" colspan=\"2\">DIAS CONTEO ALEATORIO\n";
     $html .= "    </td>\n";
     $html .= "  </tr>\n";
     $html .= "  <tr class=\"modulo_list_claro\">\n";
     $html .= "    <td align=\"center\">LUNES\n";
     $html .= "    </td>\n";
     $html .= "    <td align=\"center\" width=\"10%\"> \n";
     $html .= "      <input type=\"checkbox\" name=\"lunes\" value=\"\"> \n";
     $html .= "    </td>";
     $html .= "  </tr>\n";
     $html .= "  <tr class=\"modulo_list_claro\">\n";
     $html .= "    <td align=\"center\">MARTES\n";
     $html .= "    </td>\n";
     $html .= "    <td align=\"center\" width=\"10%\"> \n";
     $html .= "      <input type=\"checkbox\" name=\"martes\" value=\"\"> \n";
     $html .= "    </td>";
     $html .= "  </tr>\n";
     $html .= "  <tr class=\"modulo_list_claro\">\n";
     $html .= "    <td align=\"center\">MIERCOLES\n";
     $html .= "    </td>\n";
     $html .= "    <td align=\"center\" width=\"10%\"> \n";
     $html .= "      <input type=\"checkbox\" name=\"miercoles\" value=\"\"> \n";
     $html .= "    </td>";
     $html .= "  </tr>\n";
     $html .= "  <tr class=\"modulo_list_claro\">\n";
     $html .= "    <td align=\"center\">JUEVES\n";
     $html .= "    </td>\n";
     $html .= "    <td align=\"center\" width=\"10%\"> \n";
     $html .= "      <input type=\"checkbox\" name=\"jueves\" value=\"\"> \n";
     $html .= "    </td>";
     $html .= "  </tr>\n";
     $html .= "  <tr class=\"modulo_list_claro\">\n";
     $html .= "    <td align=\"center\">VIERNES\n";
     $html .= "    </td>\n";
     $html .= "    <td align=\"center\" width=\"10%\"> \n";
     $html .= "      <input type=\"checkbox\" name=\"viernes\" value=\"\"> \n";
     $html .= "    </td>";
     $html .= "  </tr>\n";
     $html .= "  <tr class=\"modulo_list_claro\">\n";
     $html .= "    <td align=\"center\">SABADO\n";
     $html .= "    </td>\n";
     $html .= "    <td align=\"center\" width=\"10%\"> \n";
     $html .= "      <input type=\"checkbox\" name=\"sabado\" value=\"\"> \n";
     $html .= "    </td>";
     $html .= "  </tr>\n";
     $html .= "  <tr class=\"modulo_list_claro\">\n";
     $html .= "    <td align=\"center\">DOMINGO\n";
     $html .= "    </td>\n";
     $html .= "    <td align=\"center\" width=\"10%\"> \n";
     $html .= "      <input type=\"checkbox\" name=\"domingo\" value=\"\"> \n";
     $html .= "    </td>";
     $html .= "  </tr>\n";
     $html .= "  <tr class=\"modulo_list_claro\">\n";
     $html .= "    <td align=\"center\">ALEATORIO\n";
     $html .= "    </td>\n";
     $html .= "    <td align=\"center\" width=\"10%\"> \n";
     $html .= "      <input type=\"checkbox\" name=\"aleatorio\" value=\"\"> \n";
     $html .= "    </td>";
     $html .= "  </tr>\n";
     
     $html .= "  </table>\n";
     
     $html .= "<table align=\"center\">\n";
     $html .= "   <tr>"; 
     $html .= "    <td colspan=\"2\" align=\"center\"><br>";
		 $html .= '      <input class="input-submit" type="button" name="guardar" value="Guardar" onClick="GuardarProductosConteo(document.formCantidadProducto);">';
		 $html .= "    </td>";
     $html .= "   </tr>";
     $html .= "</table>\n";
     $html .= "</form>";
     $objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
     $objResponse->call("MostrarSpan");
     return $objResponse;
   }
   
   /**
      * Funcion que permite guarda los productos del conteo
      *
      * @param  var $cantidad contiene la cantidad
      * @param  var $mayor_rotacion contiene el sw de mayor_rotacion
      * @param  var $mayor_costo contiene el sw de mayor_costo
      * @param  var $lunes contiene el sw de lunes
      * @param  var $martes contiene el sw de martes
      * @param  var $miercoles contiene el sw de miercoles
      * @param  var $jueves contiene el sw de jueves
      * @param  var $viernes contiene el sw de viernes
      * @param  var $sabado contiene el sw de sabado
      * @param  var $domingo contiene el sw de domingo
      * @param  var $aleatorio contiene el sw de aleatorio
      * @return Object $objResponse objeto de respuesta al formulario  
      */
   function GuardarProductosConteo($cantidad,$mayor_rotacion,$mayor_costo,$lunes,$martes,$miercoles,$jueves,$viernes,$sabado,$domingo,$aleatorio)
   {
     $objResponse = new xajaxResponse();
     $mdl = AutoCarga::factory("ParametrizacionTomaFisica","classes","app","Inv_ParametrosIniciales");
     $guardar=$mdl->AgregarParamTomaFisica($cantidad,$mayor_rotacion,$mayor_costo,$lunes,$martes,$miercoles,$jueves,$viernes,$sabado,$domingo,$aleatorio);
     return $objResponse;
   }
   
   /**
      * Funcion que permite actualizar la activacion de un conteo diario
      *
      * @param  var $id_paramtomafisica contiene el id de la parametrizacion
      * @param  var $activarlo contiene si esta activado
      * @return Object $objResponse objeto de respuesta al formulario  
      */
   function GuardarActivado($id_paramtomafisica,$activarlo)
   {
     $objResponse = new xajaxResponse();
     $mdl = AutoCarga::factory("ParametrizacionTomaFisica","classes","app","Inv_ParametrosIniciales");
     $guardar=$mdl->ActualizarActivar($id_paramtomafisica,$activarlo);
     return $objResponse;
   }
?>