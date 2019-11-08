<?php

   /**
  * @package IPSOFT-SIIS
  * @version $Id: ParametrizarEmpresas_HTML.class.php,
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

  /**
  * Clase Vista: ParametrizarEmpresas_HTML
  * Clase Contiene Metodos para el despliegue de Formularios del Módulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.3 $
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

	class ParametrizarEmpresas_HTML
	{
		/**
		* Constructor de la clase
		*/
		function ParametrizarEmpresas_HTML(){}
		/**
	    * @param array 
      * $action Vector de links de la aplicaion
		* 
		*/
		function main($action,$request)
    {
    $accion=$action['volver'];
			  
      
    $html .= ThemeAbrirTabla('PARAMETRIZAR EMPRESAS');
    
      $html .= "<script>\n";
      $html .= "  function mOvr(src,clrOver)\n";
      $html .= "  {\n";
      $html .= "    src.style.background = clrOver;\n";
      $html .= "  }\n";
      $html .= "  function mOut(src,clrIn)\n";
      $html .= "  {\n";
      $html .= "    src.style.background = clrIn;\n";
      $html .= "  }\n";
      $html .= "  function acceptDate(evt)\n";
      $html .= "  {\n";
      $html .= "    var nav4 = window.Event ? true : false;\n";
      $html .= "    var key = nav4 ? evt.which : evt.keyCode;\n";
      $html .= "    return (key <= 13 ||(key >= 47 && key <= 57));\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      
      $html .= "<center>";
      $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
      $html .= "    <tr class=\"modulo_table_list_title\">\n";
      $html .= "    <td colspan=\"5\" align=\"center\">";
      $html .= "     BUSCADOR";
      $html .= "    </td>";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"modulo_list_claro\">\n";
      $html .= "          <td class=\"modulo_table_list_title\">";
      $html .= "    CODIGO";
      $html .= "          </td>";
      $html .= "          <td align=\"center\">\n";
      $html .= "              <input class=\"input-text\" type=\"text\" style=\"width:100%\" name=\"empresa_id\" id=\"empresa_id\">\n";
      $html .= "          </td>\n";
      $html .= "          <td class=\"modulo_table_list_title\">";
      $html .= "          DESCRIPCION";
      $html .= "          </td>";
      $html .= "          <td align=\"center\">\n";
      $html .= "            <input class=\"input-text\" type=\"text\" style=\"width:100%\" name=\"razon_social\" id=\"razon_social\">\n";
      $html .= "          </td>\n";
      $html .= "          <td>";
      $html .= "        <input type=\"button\" value=\"BUSCAR\" onclick=\"paginador(document.getElementById('empresa_id').value,document.getElementById('razon_social').value,'1');\" style=\"width:100%\" class=\"input-submit\">";
      $html .= "    </td>";
      $html .= "    </tr>\n";
      
      $html .= "  </table>";
      $html .= "</center>";
      $html .= "<br>";
      
      $html .= "  <div id=\"listado_empresas\"></div> ";
      $html .= "   <script>";
      $html .= "   xajax_ListadoEmpresas('','','1');";
      $html .= "          function paginador(codigo_empresa,razon_social,offset)";
      $html .= "          {";
      $html .= "          xajax_ListadoEmpresas(codigo_empresa,razon_social,offset);";
      $html .= "          }";
      
      $html .= "   </script>";
      
      $html .= "<form name=\"forma\" action=\"".$accion."\" method=\"post\">\n";
      $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\">\n";
      $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "</form>\n";
      $html .= ThemeCerrarTabla();
    return($html);
    }
    
  }
?>