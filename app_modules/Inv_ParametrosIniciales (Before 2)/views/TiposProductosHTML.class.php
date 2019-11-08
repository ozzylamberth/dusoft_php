<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: 
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F Manrique
  */
  /**
  * Clase Vista: TiposProductosHTML
  * Clase Contiene Metodos para el despliegue de Formularios del Módulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F Manrique
  */
	class TiposProductosHTML
	{
		/**
		* Constructor de la clase
		*/
		function TiposProductosHTML(){}
    /**
    *
    */
		function FormaDiasEnvio($action,$datos,$adicionales)
    {  
      $html .= "<script>\n";
      $html .= "  function EnviarDatos()\n";
      $html .= "  {\n";
      $html .= "    xajax_IngresarDias(xajax.getFormValues('envios'))\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      $html .= ThemeAbrirTabla('DIAS DE ENVIO TIPOS PRODUCTOS');
      $html .= "<center>\n";
      $html .= "  <div id=\"error\"></div>\n";
      $html .= "</center>\n";
      $html .= "<form name=\"envios\" id=\"envios\" method=\"post\" action=\"javascript:EnviarDatos()\">";
      $html .= "  <input type=\"hidden\" name=\"empresa_id\" value=\"".$adicionales['empresa_id']."\">\n";
      $html .= "  <input type=\"hidden\" name=\"usuario_id\" value=\"".$adicionales['usuario_id']."\">\n";
      $html .= "  <table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td rowspan=\"2\" width=\"30%\">TIPO PRODUCTO</td>\n";
      $html .= "      <td colspan=\"7\" width=\"70%\">DIAS DE ENVIO</td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td width=\"10%\">LUNES</td>\n";
      $html .= "      <td width=\"10%\">MARTES</td>\n";
      $html .= "      <td width=\"10%\">MIERCOLES</td>\n";
      $html .= "      <td width=\"10%\">JUEVES</td>\n";
      $html .= "      <td width=\"10%\">VIERNES</td>\n";
      $html .= "      <td width=\"10%\">SABADO</td>\n";
      $html .= "      <td width=\"10%\">DOMINGO</td>\n";
      $html .= "    </tr>\n";
      foreach($datos as $key => $dtl)
      {
        $est = ($est == "modulo_list_oscuro")? "modulo_list_claro":"modulo_list_oscuro";
        $html .= "    <tr class=\"".$est."\" align=\"center\">\n";
        $html .= "      <td align=\"left\" class=\"label\">".$dtl['descripcion']."</td>\n";
        $html .= "      <td >\n";
        $html .= "        <input type=\"checkbox\" name=\"producto[".$dtl['tipo_producto_id']."][sw_lunes]\" value=\"1\" ".(($dtl['sw_lunes'] == '1')? "checked":"").">\n";
        $html .= "      </td>\n";        
        $html .= "      <td >\n";
        $html .= "        <input type=\"checkbox\" name=\"producto[".$dtl['tipo_producto_id']."][sw_martes]\" value=\"1\" ".(($dtl['sw_martes'] == '1')? "checked":"").">\n";
        $html .= "      </td>\n";        
        $html .= "      <td >\n";
        $html .= "        <input type=\"checkbox\" name=\"producto[".$dtl['tipo_producto_id']."][sw_miercoles]\" value=\"1\" ".(($dtl['sw_miercoles'] == '1')? "checked":"").">\n";
        $html .= "      </td>\n";        
        $html .= "      <td >\n";
        $html .= "        <input type=\"checkbox\" name=\"producto[".$dtl['tipo_producto_id']."][sw_jueves]\" value=\"1\" ".(($dtl['sw_jueves'] == '1')? "checked":"").">\n";
        $html .= "      </td>\n";        
        $html .= "      <td >\n";
        $html .= "        <input type=\"checkbox\" name=\"producto[".$dtl['tipo_producto_id']."][sw_viernes]\" value=\"1\" ".(($dtl['sw_viernes'] == '1')? "checked":"").">\n";
        $html .= "      </td>\n";        
        $html .= "      <td >\n";
        $html .= "        <input type=\"checkbox\" name=\"producto[".$dtl['tipo_producto_id']."][sw_sabado]\" value=\"1\" ".(($dtl['sw_sabado'] == '1')? "checked":"").">\n";
        $html .= "      </td>\n";        
        $html .= "      <td >\n";
        $html .= "        <input type=\"checkbox\" name=\"producto[".$dtl['tipo_producto_id']."][sw_domingo]\" value=\"1\" ".(($dtl['sw_domingo'] == '1')? "checked":"").">\n";
        $html .= "      </td>\n";

        $html .= "    </tr>\n";
      }
      $html .= "  </table><br>\n";
      $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\"><br>\n";
      $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"Guardar\">\n";
      $html .= "      </td>\n";
      $html .= "    </form>";
      $html .= "    <form name=\"forma\" action=\"".$action['volver']."\" method=\"post\">\n";
      $html .= "      <td align=\"center\"><br>\n";
      $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
      $html .= "      </td>\n";
      $html .= "    </form>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= ThemeCerrarTabla();
    
      return($html);
    }
  }
?>