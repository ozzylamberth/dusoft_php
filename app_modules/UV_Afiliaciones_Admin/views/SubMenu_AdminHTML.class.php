<?php

    /**
  * @package IPSOFT-SIIS
  * @version $Id: SubMenu_AdminHTML.class.php,v 1.5 2007/11/09 14:05:17 jgomez Exp $ 
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author JAIME GOMEZ
  */

/**
  * Clase Vista: SubMenu_AdminHTML - Administracion del modulo de UV_Afiliaciones
  * Clase que contiene el menu para el mantyenimiento de tablas maestras
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.5 $
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author JAIME GOMEZ
  */

	class  SubMenu_AdminHTML
	{
		/**
		* Constructor de la clase
		*/
		function SubMenu_AdminHTML(){}
		/**
		* Crea un menu principal para el modulo
		*
		* @param array $action vector que continen los link de la aplicacion
		* @return String
		*/
		function FormaSubMenuInicial($action)
		{
			$html  = ThemeAbrirTabla('MANTENIMIENTO DE TABLAS MAESTRAS');
			$html .= "<table border=\"0\" width=\"40%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "			<table border=\"0\" width=\"100%\" align=\"center\" class=\"formulacion_table_list\">\n";
			$html .= "				<tr>\n";
			$html .= "					<td align=\"center\" class=\"formulacion_table_list\" ><b style=\"color:#ffffff\">LISTADO DE TABLAS</td>\n";
			$html .= "				</tr>\n";
			$html .= "				<tr>\n";
			$html .= "					<td class=\"modulo_list_claro\" align=\"left\">\n";
			$html .= "						<a href=\"".$action['eps']."\"><b>1.  ENTIDADES PROMOTORAS DE SALUD (E.P.S)</b></a>\n";
            $html .= "					</td>\n";
			$html .= "				</tr>\n";
            $html .= "              <tr>\n";
            $html .= "                  <td class=\"modulo_list_claro\" align=\"left\">\n";
            $html .= "                      <a href=\"".$action['eafp']."\"><b>2.  ADMIMNISTRADORAS FONDOS DE PENSIONES</b></a>\n";
            $html .= "                  </td>\n";
            $html .= "              </tr>\n";
            $html .= "              <tr>\n";
            $html .= "                  <td class=\"modulo_list_claro\" align=\"left\">\n";
            $html .= "                      <a href=\"".$action['dependencias']."\"><b>3.  DEPENDENCIAS</b></a>\n";
            $html .= "                  </td>\n";
            $html .= "              </tr>\n";
            $html .= "              <tr>\n";
            $html .= "                  <td class=\"modulo_list_claro\" align=\"left\">\n";
            $html .= "                      <a href=\"".$action['tipos_afiliaciones']."\"><b>4.  TIPOS AFILIACIONES</b></a>\n";
            $html .= "                  </td>\n";
            $html .= "              </tr>\n";
            $html .= "              <tr>\n";
            $html .= "                  <td class=\"modulo_list_claro\" align=\"left\">\n";
            $html .= "                      <a href=\"".$action['tipos_afiliados']."\"><b>5.  TIPOS AFILIADOS</b></a>\n";
            $html .= "                  </td>\n";
            $html .= "              </tr>\n";
            $html .= "              <tr>\n";
            $html .= "                  <td class=\"modulo_list_claro\" align=\"left\">\n";
            $html .= "                      <a href=\"".$action['tipos_aportantes']."\"><b>6.  TIPOS APORTANTES</b></a>\n";
            $html .= "                  </td>\n";
            $html .= "              </tr>\n";
            $html .= "              <tr>\n";
            $html .= "                  <td class=\"modulo_list_claro\" align=\"left\">\n";
            $html .= "                      <a href=\"".$action['estamentos']."\"><b>7.  ESTAMENTOS</b></a>\n";
            $html .= "                  </td>\n";
            $html .= "              </tr>\n";
            $html .= "              <tr>\n";
            $html .= "                  <td class=\"modulo_list_claro\" align=\"left\">\n";
            $html .= "                      <a href=\"".$action['afiliados_estados']."\"><b>8.  AFILIADOS ESTADOS</b></a>\n";
            $html .= "                  </td>\n";
            $html .= "              </tr>\n";
            $html .= "              <tr>\n";
            $html .= "                  <td class=\"modulo_list_claro\" align=\"left\">\n";
            $html .= "                      <a href=\"".$action['afiliados_subestados']."\"><b>9.  AFILIADOS SUBESTADOS</b></a>\n";
            $html .= "                  </td>\n";
            $html .= "              </tr>\n";
            $html .= "              <tr>\n";
            $html .= "                  <td class=\"modulo_list_claro\" align=\"left\">\n";
            $html .= "                      <a href=\"".$action['parentescos_benef']."\"><b>10.  PARENTESCOS BENEFICIARIOS</b></a>\n";
            $html .= "                  </td>\n";
            $html .= "              </tr>\n";
            $html .= "              <tr>\n";
            $html .= "                  <td class=\"modulo_list_claro\" align=\"left\">\n";
            $html .= "                      <a href=\"".$action['estado_civil']."\"><b>11.  ESTADOS CIVILES</b></a>\n";
            $html .= "                  </td>\n";
            $html .= "              </tr>\n";
            $html .= "              <tr>\n";
            $html .= "                  <td class=\"modulo_list_claro\" align=\"left\">\n";
            $html .= "                      <a href=\"".$action['tipo_sexo']."\"><b>12.  TIPOS SEXO</b></a>\n";
            $html .= "                  </td>\n";
            $html .= "              </tr>\n";
            $html .= "              <tr>\n";
            $html .= "                  <td class=\"modulo_list_claro\" align=\"left\">\n";
            $html .= "                      <a href=\"".$action['actividades_economicas']."\"><b>13.  ACTIVIDADES ECONOMICAS</b></a>\n";
            $html .= "                  </td>\n";
            $html .= "              </tr>\n";
            $html .= "              <tr>\n";
            $html .= "                  <td class=\"modulo_list_claro\" align=\"left\">\n";
            $html .= "                      <a href=\"".$action['ocupaciones']."\"><b>14. OCUPACIONES</b></a>\n";
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