<?php

  /**
  * @package IPSOFT-SIIS
  * @version $Id: OcupacionesHTML.class.php,v 1.6 2008/06/13 19:39:04 jgomez Exp $ 
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author JAIME GOMEZ
  */

/**
  * Clase Vista: OcupacionesHTML - Administracion del modulo de UV_Afiliaciones
  * Clase que contiene el menu de las tablas de ocupaciones
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.6 $
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author JAIME GOMEZ
  */

	class  OcupacionesHTML
	{
		/**
		* Constructor de la clase
		*/
		function OcupacionesHTML(){}
		/**
		* Crea un menu principal para el modulo
		*
		* @param array $action vector que continen los link de la aplicacion
		* @return String
    */           
		function FormaSubMenuOcupaciones($action)
		{      
			$html  = ThemeAbrirTabla('MANTENIMIENTO DE TABLAS MAESTRAS');
			$html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "			<table border=\"0\" width=\"100%\" align=\"center\" class=\"formulacion_table_list\">\n";
			$html .= "				<tr>\n";
			$html .= "					<td align=\"center\" class=\"formulacion_table_list\" ><b style=\"color:#ffffff\">OCUPACIONES</td>\n";
			$html .= "				</tr>\n";
			$html .= "				<tr>\n";
			$html .= "					<td class=\"modulo_list_claro\" align=\"left\">\n";
			$html .= "						<a href=\"".$action['grandes']."\"><b>1.  GRANDES GRUPOS DE OCUPACIONES </b></a>\n";
            $html .= "					</td>\n";
			$html .= "				</tr>\n";
            $html .= "              <tr>\n";
            $html .= "                  <td class=\"modulo_list_claro\" align=\"left\">\n";
            $html .= "                      <a href=\"".$action['primarios']."\"><b>2.  GRUPOS PRIMARIOS DE OCUPACIONES</b></a>\n";
            $html .= "                  </td>\n";
            $html .= "              </tr>\n";
            $html .= "              <tr>\n";
            $html .= "                  <td class=\"modulo_list_claro\" align=\"left\">\n";
            $html .= "                      <a href=\"".$action['principales']."\"><b>3.  SUBGRUPOS PRINCIPALES DE OCUPACIONES</b></a>\n";
            $html .= "                  </td>\n";
            $html .= "              </tr>\n";
            $html .= "              <tr>\n";
            $html .= "                  <td class=\"modulo_list_claro\" align=\"left\">\n";
            $html .= "                      <a href=\"".$action['subgrupos']."\"><b>4.  SUBGRUPOS DE OCUPACIONES</b></a>\n";
            $html .= "                  </td>\n";
            $html .= "              </tr>\n";
            $html .= "			</table>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr>\n";
			$html .= "		<td align=\"center\"><br>\n";
			$html .= "			<form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
			$html .= "			</form>";
			$html .= "		</td>";
			$html .= "	</tr>";
			$html .= "</table>";
			$html .= ThemeCerrarTabla();			
			return $html;
		}



        /**
        * FUNCION QUE SIRVE PARA INDICAR QUE EL USUARIO QUE ACCEDIO AL MODULO NO TIENE PERMISO
        * @param array $action
        * @return string $html
        *
        **/
        function FormaPermisoNegado($action)
        {
            $html  = ThemeAbrirTabla('AFILIACIONES ADMINISTRACION');
            $html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
            $html .= "  <tr>\n";
            $html .= "      <td>\n";
            $html .= "          <table border=\"0\" width=\"100%\" align=\"center\" class=\"formulacion_table_list\">\n";
            $html .= "              <tr>\n";
            $html .= "                  <td align=\"center\" class=\"formulacion_table_list\" ><b style=\"color:#ffffff\">ACCESO DENEGADO</td>\n";
            $html .= "              </tr>\n";
            $html .= "              <tr>\n";
            $html .= "                  <td class=\"modulo_list_claro\" align=\"center\">\n";
            $html .= "                      <b><label class='label_error'>ESTE USUARIO NO TIENE PERMISO PARA ACCEDER A ESTE MODULO</label></b>\n";
            $html .= "                  </td>\n";
            $html .= "              </tr>\n";
            $html .= "          </table>\n";
            $html .= "      </td>\n";
            $html .= "  </tr>\n";
            $html .= "  <tr>\n";
            $html .= "      <td align=\"center\"><br>\n";
            $html .= "          <form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
            $html .= "              <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
            $html .= "          </form>";
            $html .= "      </td>";
            $html .= "  </tr>";
            $html .= "</table>";
            $html .= ThemeCerrarTabla();            
            return $html;
        }
	}
?>