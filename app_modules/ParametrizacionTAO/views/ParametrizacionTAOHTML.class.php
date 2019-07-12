<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ParametrizacionInicialHTML.class.php,v 1.1 2009/09/14 08:19:24
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author 
  */
  /**
  * Clase Vista: ParametrizacionTAOHTML
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author 
  */
  
  class ParametrizacionTAOHTML
  {
    /*
    * Constructor de la clase
    */
    function ParametrizacionTAOHTML(){}
    
    /**
    * Funcion donde se crea la forma para el menu de Parametrizacion de TAO
    *
    * @param array $action vector que contiene los link de la aplicacion
    * @return string $html retorna la cadena con el codigo html de la pagina
    */
    function formaMenu($action)
    {
      $html  = ThemeAbrirTabla('PARAMETRIZAR TAO');
        
      $html .= "<table width=\"40%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
      $html .= "  <tr class=\"formulacion_table_list\">\n";
      $html .= "    <td align=\"center\">MENU\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"modulo_list_claro\">\n";
      $html .= "    <td align=\"center\">\n";
      $html .= "      <a href=\"".$action['parametrizar_busqueda']."\" class=\"label_error\">PARAMETRIZAR BUSQUEDA</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= "<br>\n";
      $html .= "<table align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\">\n";
      $html .= "      <a href=\"".$action['volver']."\" class=\"label_error\">VOLVER</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= ThemeCerrarTabla();
    
      return $html;
    }
    
    /**
    * Funcion donde se muestra el form de busqueda y la opcion de incluir en tao.
    *
    * @param array $action vector que contiene los link de la aplicacion
    * @param array $datos  vector con la informacion de los planes
    * @return string $html retorna la cadena con el codigo html de la pagina
    */
    function FormaBuscarMedicamentos($action,$datos,$empresa_id,$conteo,$pagina)
    { 
      $html  = ThemeAbrirTabla("MEDICAMENTOS");
      
      $html .= "<form name=\"formaBuscarCargos\" id=\"formBuscarCargos\" method=\"post\" action=\"".$action['parametrizar_busqueda']."\">\n";
      $html .= "<table class=\"modulo_table_list\" align=\"center\" width=\"70%\">\n";
      $html .= "  <tr class=\"modulo_table_title\">\n";
      $html .= "    <td colspan=\"2\" align=\"center\">BUSCADOR\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "  <td class=\"formulacion_table_list\"align=\"left\" width=\"25%\">CODIGO </td>\n";
      $html .= "  <td class=\"modulo_list_claro\"> <input type=\"text\" name=\"codigo\" class=\"input-text\"/> </td>\n";
      $html .= "   </tr>\n";
      $html .= "  <tr>\n";
      $html .= "  <td class=\"formulacion_table_list\"align=\"left\" width=\"25%\">DESCRIPCION </td>\n";
      $html .= "  <td class=\"modulo_list_claro\"> <input type=\"text\" name=\"descripcion\" class=\"input-text\"/> </td>\n";
      $html .= "   </tr>\n";

      $html .= "   <tr> <td colspan=\"2\" align=\"center\"> <input type=\"submit\" value=\"Buscar\" name=\"buscar\" class=\"input-submit\"/> </td></tr>\n";
      $html .= "      <input type=\"hidden\" class=\"input-text\" name=\"empresa\" value=\"".$empresa_id."\" >\n";
      $html .= "  </table>\n";
      $html .= "</form>\n";
      
      $html .= "<table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
      $html .= "  <tr class=\"formulacion_table_list\">\n";
      $html .= "    <td width=\"20%\">COD-MED</td>\n";
      $html .= "    <td width=\"35%\">DESCRIPCION</td>\n";
      $html .= "    <td width=\"30%\">DESCRIPCION ABREVIADA</td>\n";
      $html .= "    <td width=\"5%\">EXISTENCIA</td>\n";
      $html .= "    <td width=\"10%\">OPCION</td>\n";
      $html .= "  </tr>\n";
      foreach($datos as $key => $detalle)
      {
        $est = ($est == "modulo_list_claro")? "modulo_list_oscuro": "modulo_list_claro";
        
        $html .= "  <tr class=\"".$est."\">\n";
        $html .= "    <td >".$detalle['codigo_producto']." </td>\n";
        $html .= "    <td >".$detalle['descripcion']." </td>\n";
        $html .= "    <td >".$detalle['descripcion_abreviada']."</td>\n";
        $html .= "    <td >".$detalle['existencia']."</td>\n";
        if($detalle['estado'] == '1')
        {
        $html .= "    <td>\n";
        $html .= "      <a href=\"#\" onclick=\"xajax_AsignarTao('".$empresa_id."','".$detalle['codigo_producto']."')\">\n";
        $html .= "        <img id=\"div_".$empresa_id.$detalle['codigo_producto']."\" src=\"".GetThemePath()."/images/checkS.gif\" border=\"0\" >\n";
        $html .= "      </a>\n";
        $html .= "    </td>\n";
        }else{
        $html .= "    <td>\n";
        $html .= "      <a href=\"#\" onclick=\"xajax_AsignarTao('".$empresa_id."','".$detalle['codigo_producto']."')\">\n";
        $html .= "        <img id=\"div_".$empresa_id.$detalle['codigo_producto']."\" src=\"".GetThemePath()."/images/checkN.gif\" border=\"0\" >\n";
        $html .= "      </a>\n";
        $html .= "    </td>\n";
        }
        $html .= "  </tr>\n";
      }
      $html .= "</table>\n";
      $html .= "<center>\n";
      $html .= "  <a href=\"".$action['volver']."\" class=\"label\">VOLVER</a>\n";
      $chtml = AutoCarga::factory('ClaseHTML');
      $html .= "		".$chtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
      $html .= "</center>\n";
      $html .= ThemeCerrarTabla();
      return $html;
    }
  }
  
?>