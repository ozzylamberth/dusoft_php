<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: IngresoRipsHTML.class.php,v 1.1 2008/05/13 21:05:29 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Vista: IngresoRipsHTML
  * Clase encargada de crear las formas para mostrar el menu principal del modulo 
  * y los mensajes al usuario
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class IngresoRipsHTML
  {
    /**
    * Constructosr de la clase
    */
    function IngresoRipsHTML(){}
		/**
		* Funcion donde se crea la forma para hacer la recepcion de los rips
		*
		* @param array $action Vector que continen los link de la aplicacion
    *
		* @return string
		*/
		function FormaRecepcionRips($action)
		{
			$html  = ThemeAbrirTabla('CUENTAS X PAGAR');
      $html .= "<script>\n";
      $html .= "  function Eval(forma)\n";
      $html .= "  {\n";
      $html .= "    errorMsg = document.getElementById('error');\n";
      $html .= "    nombre = forma.archivo_control.value;\n";
      $html .= "    archivo = nombre.split('\\\');\n";
      $html .= "    nombre_archivo = archivo[archivo.length-1].split('.');\n";
      $html .= "    if(nombre_archivo[1] != 'txt' && nombre_archivo[1] != 'TXT')\n";
      $html .= "    {\n";
      $html .= "      errorMsg.innerHTML = 'EXTENSION DEL ARCHIVO NO VALIDO, EL ARCHIVO DEBE TENER EXTENSION .txt'\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    else if(nombre_archivo[0].substring(0,2) != 'CT' && nombre_archivo[0].substring(0,2) != 'ct')\n";
      $html .= "    {\n";
      $html .= "      errorMsg.innerHTML = 'ARCHIVO DE CONTROL NO VALIDO, DEBE SUBIR EL ARCHIVO QUE INICIA CON CT '\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    forma.action = \"".$action['aceptar']."\";\n";
      $html .= "    forma.submit();\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      $html .= "<center>\n";
      $html .= "  <div id=\"error\" class=\"label_error\"></div>\n";
      $html .= "</center>\n";
      $html .= "<form name=\"subir\" enctype=\"multipart/form-data\" action=\"javascript:Eval(document.subir)\" method = \"post\">\n";
      $html .= "  <table width=\"50%\" class=\"modulo_table_list\" align=\"center\">\n";
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td colspan=\"2\">\n";
      $html .= "        INGRESO DEL ARCHIVO DE CONTROL\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td colspan=\"2\">\n";
      $html .= "        <input type=\"file\" name=\"archivo_control\" id=\"archivo_control\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "        <td align=\"center\">\n";
      $html .= "          <input class=\"input-submit\" type=\"submit\" name=\"enviar\" value=\"Aceptar\">\n";
      $html .= "        </td>\n";
      $html .= "			</form>";
			$html .= "			<form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
			$html .= "		    <td align=\"center\"><br>\n";
			$html .= "				  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
			$html .= "		    </td>";
			$html .= "			</form>";
			$html .= "	  </tr>";
			$html .= "</table>";
			$html .= ThemeCerrarTabla();			
			return $html;
		}
    /**
		* Funcion donde se solicitan los archivos que hacen parte de los rips 
		*
		* @param array $action Vector que continen los link de la aplicacion
		* @param array $archivos Vector con la informacion de los archivos que se requieren
    *
		* @return string
		*/
		function FormaRecepcionRipsContinuacion($action,$archivos)
		{
			$datos = array();
      $datos['AF'] = "ARCHIVO DE LAS TRANSACCIONES ";
      $datos['US'] = "ARCHIVO DE USUARIOS DE LOS SERVICIOS DE SALUD";
      $datos['AD'] = "ARCHIVO DE DESCRIPCION AGRUPADA DE LOS SERVICIOS DE SALUD PRESTADOS";
      $datos['AC'] = "ARCHIVO DE CONSULTA";
      $datos['AP'] = "ARCHIVO DE PROCEDIMIENTOS";
      $datos['AH'] = "ARCHIVO DE HOSPITALIZACION";
      $datos['AU'] = "ARCHIVO DE URGENCIAS";
      $datos['AN'] = "ARCHIVO DE RECIEN NACIDOS ";
      $datos['AM'] = "ARCHIVO DE MEDICAMENTOS";
      $datos['AT'] = "ARCHIVO DE OTROS SERVICIOS";
      
      $html  = ThemeAbrirTabla('CUENTAS X PAGAR');
      $html .= "<script>\n";
      $html .= "  function Eval(forma)\n";
      $html .= "  {\n";
      $html .= "    errorMsg = document.getElementById('error');\n";
      foreach($archivos as $key => $valor)
      {
        $html .= "    nombre_$key = forma.".$valor['archivo'].".value;\n";
        $html .= "    archivo_$key = nombre_$key.split('\\\');\n";
        $html .= "    nombre_archivo_$key = archivo_".$key."[archivo_$key.length-1].split('.');\n";
        $html .= "    if(nombre_archivo_".$key."[1] != 'txt' && nombre_archivo_".$key."[1] != 'TXT')\n";
        $html .= "    {\n";
        $html .= "      errorMsg.innerHTML = 'EXTENSION DEL ARCHIVO NO VALIDO, EL ".$datos[$key].", DEBE TENER EXTENSION .txt'\n";
        $html .= "      return;\n";
        $html .= "    }\n";
        $html .= "    else if(nombre_archivo_".$key."[0] != '".$valor['archivo']."' )\n";
        $html .= "    {\n";
        $html .= "      errorMsg.innerHTML = '".$datos[$key]." NO VALIDO, EL ARCHIVO QUE SE DEBE INGRESAR ES ".$valor['archivo'].".txt'\n";
        $html .= "      return;\n";
        $html .= "    }\n";
      }
      $html .= "    forma.action = \"".$action['aceptar']."\";\n";
      $html .= "    forma.submit();\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      $html .= "<center>\n";
      $html .= "  <div id=\"error\" class=\"label_error\" style=\"width:50%\"></div>\n";
      $html .= "</center>\n";
      $html .= "<form name=\"subir\" enctype=\"multipart/form-data\" action=\"javascript:Eval(document.subir)\" method = \"post\">\n";
      $html .= "  <table width=\"53%\" class=\"modulo_table_list\" align=\"center\">\n";
      foreach($archivos as $key => $valor)
      {
        $html .= "    <tr class=\"formulacion_table_list\">\n";
        $html .= "      <td colspan=\"3\" align=\"left\">\n";
        $html .= "        ".$datos[$key]."\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr align=\"left\">\n";
        $html .= "      <td width=\"2%\" class=\"formulacion_table_list\">".$key."</td>\n";
        $html .= "      <td colspan=\"2\">\n";
        $html .= "        <input type=\"file\" name=\"".$valor['archivo']."\" id=\"archivo_control\">\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
      }
      $html .= "    <tr>\n";
      $html .= "        <td align=\"center\" colspan=\"2\" width=\"50%\">\n";
      $html .= "          <input class=\"input-submit\" type=\"submit\" name=\"enviar\" value=\"Aceptar\">\n";
      $html .= "			  </form>";
      $html .= "      </td>\n";
			$html .= "		  <td align=\"center\"><br>\n";
			$html .= "			  <form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
			$html .= "				  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
			$html .= "			  </form>";
			$html .= "		  </td>";
			$html .= "	  </tr>";
			$html .= "</table>";
			$html .= ThemeCerrarTabla();			
			return $html;
		}
  }
?>