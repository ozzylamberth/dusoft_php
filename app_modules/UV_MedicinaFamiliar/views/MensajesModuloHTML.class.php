<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: MensajesModuloHTML.class.php,v 1.15 2008/01/15 13:40:39 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Vista: MensajesModuloHTML
  * Clase encargada de crear las formas para mostrar el menu principal del modulo 
  * y los mensajes al usuario
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.15 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
	class MensajesModuloHTML	
	{
		/**
		* Constructor de la clase
		*/
		function MensajesModuloHTML(){}
		/**
		* Crea un menu principal para el modulo
		*
		* @param array $action Vector que continen los link de la aplicacion
		* @param array $permiso Vector con los datos de los permisos del usuario
    *
		* @return string
		*/
    function FormaMenuInicial($action)
    {
        $html  = ThemeAbrirTabla('AFILIACIONES');
        $html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
        $html .= "	<tr>\n";
        $html .= "		<td>\n";
        $html .= "			<table border=\"0\" width=\"100%\" align=\"center\" class=\"formulacion_table_list\">\n";
        $html .= "				<tr>\n";
        $html .= "					<td align=\"center\" class=\"formulacion_table_list\" ><b style=\"color:#ffffff\">MENU PRINCIPAL</td>\n";
        $html .= "				</tr>\n";
//         $html .= "				<tr>\n";
//         $html .= "					<td class=\"modulo_list_claro\" align=\"center\">\n";
//         $html .= "						<a href=\"".$action['afiliacion']."\"><b>REGISTRAR AFILIACION AL SISTEMA DE SALUD</b></a>\n";
//         $html .= "					</td>\n";
//         $html .= "				</tr>\n";
//         $html .= "				<tr>\n";
//         $html .= "					<td class=\"modulo_list_claro\" align=\"center\">\n";
//         $html .= "						<a href=\"".$action['modificacion']."\"><b>MODIFICAR DATOS AFILIADOS</b></a>\n";
//         $html .= "					</td>\n";
//         $html .= "				</tr>\n";
//         $html .= "				<tr>\n";
//         $html .= "					<td class=\"modulo_list_claro\" align=\"center\">\n";
//         $html .= "						<a href=\"".$action['novedades']."\"><b>MENU NOVEDADES</b></a>\n";
//         $html .= "					</td>\n";
//         $html .= "				</tr>\n";
         $html .= "				<tr>\n";
         $html .= "					<td class=\"modulo_list_claro\" align=\"center\">\n";
         $html .= "						<a href=\"".$action['consultar_medico']."\"><b>LISTAR MEDICOS CON CANTIDAD GRUPOS FAMILIARES</b></a>\n";
         $html .= "					</td>\n";
         $html .= "				</tr>\n";
        $html .= "        <tr>\n";
        $html .= "          <td class=\"modulo_list_claro\" align=\"center\">\n";
        $html .= "            <a href=\"".$action['consulta_afiliados']."\"><b>ASIGNAR MEDICO A GRUPO FAMILIAR</b></a>\n";
        $html .= "          </td>\n";
        $html .= "        </tr>\n";
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
    * Crea una forma, para mostrar mensajes informativos con un solo boton
    * @param array $action vector que continen los link de la aplicacion
    * @param string $mensaje Cadena con el texto del mensaje a mostrar  en pantalla
    * @return string
    */
		function FormaMensajeModulo($action,$mensaje)
		{
			$html  = ThemeAbrirTabla('MENSAJE');
			$html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "		  <table width=\"100%\" class=\"modulo_table_list\">\n";
			$html .= "		    <tr class=\"normal_10AN\">\n";
			$html .= "		      <td align=\"center\">\n".$mensaje."</td>\n";
			$html .= "		    </tr>\n";
			$html .= "		  </table>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr>\n";
			$html .= "		<td align=\"center\"><br>\n";
			$html .= "			<form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
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