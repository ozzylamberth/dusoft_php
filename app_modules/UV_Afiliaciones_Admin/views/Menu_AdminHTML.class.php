<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: Menu_AdminHTML.class.php,v 1.7 2007/11/09 14:05:17 jgomez Exp $ 
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author JAIME GOMEZ
  */

/**
  * Clase Vista: Menu_AdminHTML - Administracion del modulo de UV_Afiliaciones
  * Clase que contiene el menu del modulo
  * @package IPSOFT-SIIS
  * @version $Revision: 1.7 $
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Jaime Gomez
  */


	class Menu_AdminHTML
	{
		/**
		* Constructor de la clase
		*/
		function Menu_AdminHTML(){}
		/**
		* Crea un menu principal para el modulo
		*
		* @param array $action vector que continen los link de la aplicacion
		* @return String
		*/
		function FormaMenuInicial($action)
		{
			$html  = ThemeAbrirTabla('AFILIACIONES ADMINISTRACION');
			$html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "			<table border=\"0\" width=\"100%\" align=\"center\" class=\"formulacion_table_list\">\n";
			$html .= "				<tr>\n";
			$html .= "					<td align=\"center\" class=\"formulacion_table_list\" ><b style=\"color:#ffffff\">MENU PRINCIPAL</td>\n";
			$html .= "				</tr>\n";
			$html .= "				<tr>\n";
			$html .= "					<td class=\"modulo_list_claro\" align=\"center\">\n";
			$html .= "						<a href=\"".$action['usuarios_per']."\"><b>USUARIOS PERFILES</b></a>\n";
			$html .= "					</td>\n";
			$html .= "				</tr>\n";
            $html .= "              <tr>\n";
            $html .= "                  <td class=\"modulo_list_claro\" align=\"center\">\n";
            $html .= "                      <a href=\"".$action['instituciones_convenio']."\"><b>INSTITUCIONES CONVENIOS</b></a>\n";
            $html .= "                  </td>\n";
            $html .= "              </tr>\n";
            $html .= "              <tr>\n";
            $html .= "                  <td class=\"modulo_list_claro\" align=\"center\">\n";
            $html .= "                      <a href=\"".$action['admin_tablas_maestras']."\"><b>MANTENIMIENTO DE TABLAS MAESTRAS</b></a>\n";
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