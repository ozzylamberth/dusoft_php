<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ArchivosHTML.class.php,v 1.1 2010/12/17 19:20:05 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Vista: ArchivosHTML
  * Clase encargada de crear las formas para mostrar el menu principal del modulo 
  * y los mensajes al usuario
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class ArchivosHTML
  {
    /**
    * Constructosr de la clase
    */
    function ArchivosHTML(){}
		/**
		* Metodo donde se crea la forma para subir los archivos de capitados, segun el plan
		*
		* @param array $action Vector que continen los link de la aplicacion
    * @param array $planes Arreglo con la informacion de los planes
    *
		* @return string
		*/
		function FormaRecepcionArchivos($action,$request)
		{
      $titulo = "";
      switch($request['archivo_subir'])
      {
        case 'DE': $titulo = "DESPACHOS"; break;
        case 'FR': $titulo = "FORMULAS"; break;
        case 'MD': $titulo = "MEDICOS"; break;
        case 'PA': $titulo = "PACIENTES"; break;
      }
      $html  = ThemeAbrirTabla('SUBIR ARCHIVOS PLANOS DE '.$titulo);
      $html .= "<script>\n";
      $html .= "  function Eval(forma)\n";
      $html .= "  {\n";
      $html .= "    errorMsg = document.getElementById('error');\n";
      $html .= "    if(forma.archivo_capitado.value == \"\")\n";
      $html .= "    {\n";
      $html .= "      errorMsg.innerHTML = \"NO SE HA INDICADO EL ARCHIVO A SUBIR\";\n";
      $html .= "      return;\n";
      $html .= "    }\n";      
      $html .= "    if(!forma.separador[0].checked && !forma.separador[1].checked && !forma.separador[2].checked && !forma.separador[3].checked)\n";
      $html .= "    {\n";
      $html .= "      errorMsg.innerHTML = \"NO SE HA INDICADO EL SEPARADOR DEL ARCHIVO\";\n";
      $html .= "      return;\n";
      $html .= "    }\n";      
      $html .= "    if(!forma.encabezado[0].checked && !forma.encabezado[1].checked)\n";
      $html .= "    {\n";
      $html .= "      errorMsg.innerHTML = \"NO SE HA INDICADO SI EL ARCHIVO POSSE O NO ENCABEZADO\";\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    forma.action = \"".$action['aceptar']."\";\n";
      $html .= "    forma.submit();\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      $html .= "<form name=\"subir\" enctype=\"multipart/form-data\" action=\"javascript:Eval(document.subir)\" method = \"post\">\n";
      $html .= "  <table width=\"70%\" class=\"modulo_table_list\" align=\"center\">\n";
      $html .= "    <tr class=\"modulo_list_claro\">\n";
      $html .= "      <td class=\"formulacion_table_list\" >ARCHIVO</td>\n";
      $html .= "      <td colspan=\"4\">\n";
      $html .= "        <input type=\"file\" size=\"45\" class=\"input-text\" name=\"archivo_capitado\" id=\"archivo_capitado\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"modulo_list_claro\" >\n";
      $html .= "      <td class=\"formulacion_table_list\" >SEPARADOR</td>\n";
      $html .= "      <td class=\"label\" width=\"20%\">\n";
      $html .= "        <input type=\"radio\" name=\"separador\" value=\",\">\n";
      $html .= "        COMA [,]\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"label\" width=\"30%\">\n";
      $html .= "        <input type=\"radio\" name=\"separador\" value=\";\">\n";
      $html .= "        PUNTO Y COMA [;]\n";
      $html .= "      </td>\n";      
      $html .= "      <td class=\"label\" width=\"20%\">\n";
      $html .= "        <input type=\"radio\" name=\"separador\" value=\"t\">\n";
      $html .= "        TABS[\t]\n";
      $html .= "      </td>\n";      
      $html .= "      <td class=\"label\" width=\"20%\">\n";
      $html .= "        <input type=\"radio\" name=\"separador\" value=\"@\">\n";
      $html .= "        ARROBA[@]\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"modulo_list_claro\">\n";
      $html .= "      <td class=\"formulacion_table_list\" >\n";
      $html .= "       ARCHIVO CON ENCABEZADO\n";
      $html .= "      </td>\n";      
      $html .= "      <td class=\"label\" >\n";
      $html .= "        <input type=\"radio\" name=\"encabezado\" value=\"1\"> SI\n";
      $html .= "      </td>\n";      
      $html .= "      <td class=\"label\" colspan=\"3\" >\n";
      $html .= "        <input type=\"radio\" name=\"encabezado\" value=\"0\"> NO\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "  <div id=\"error\" style=\"text-align:center\" class=\"label_error\"></div>\n";
      $html .= "  <table width=\"60%\" align=\"center\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\" width=\"50%\">\n";
      $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"enviar\" value=\"Aceptar\">\n";
      $html .= "      </td>\n";
      $html .= "			</form>\n";
			$html .= "			<form name=\"form\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "		  <td align=\"center\">\n";
			$html .= "			  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
			$html .= "		  </td>\n";
			$html .= "			</form>\n";
			$html .= "	  </tr>\n";
			$html .= "  </table>\n";
			$html .= ThemeCerrarTabla();			
			return $html;
		}
  }
?>