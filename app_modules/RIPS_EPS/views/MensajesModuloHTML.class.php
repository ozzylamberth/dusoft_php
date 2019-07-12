<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: MensajesModuloHTML.class.php,v 1.2 2009/02/03 16:08:40 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Vista: MensajesModuloHTML
  * Clase encargada de crear las formas para mostrar el menu principal del modulo 
  * y los mensajes al usuario
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class MensajesModuloHTML
  {
    /**
    * Constructosr de la clase
    */
    function MensajesModuloHTML(){}
		/**
		* Crea un menu principal para el modulo
		*
		* @param array $action Vector que continen los link de la aplicacion
    *
		* @return string
		*/
		function FormaMenuInicial($action)
		{
			$html  = ThemeAbrirTabla('CUENTAS X PAGAR');
			$html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "			<table border=\"0\" width=\"100%\" align=\"center\" class=\"formulacion_table_list\">\n";
			$html .= "				<tr>\n";
			$html .= "					<td align=\"center\" class=\"formulacion_table_list\" ><b style=\"color:#ffffff\">MENU PRINCIPAL</td>\n";
			$html .= "				</tr>\n";
      $html .= "        <tr>\n";
      $html .= "          <td class=\"modulo_list_claro\" align=\"center\">\n";
      $html .= "            <a href=\"".$action['radicar']."\"><b>RADICAR FACTURA</b></a>\n";
      $html .= "          </td>\n";
      $html .= "        </tr>\n";      
      $html .= "        <tr>\n";
      $html .= "          <td class=\"modulo_list_claro\" align=\"center\">\n";
      $html .= "            <a href=\"".$action['cxp_reintegro']."\"><b>RADICAR CUENTAS DE REINTEGRO</b></a>\n";
      $html .= "          </td>\n";
      $html .= "        </tr>\n";
      $html .= "        <tr>\n";
      $html .= "          <td class=\"modulo_list_claro\" align=\"center\">\n";
      $html .= "            <a href=\"".$action['revisar_medic']."\"><b>REVISION MEDICA DE CUENTAS POR PAGAR</b></a>\n";
      $html .= "          </td>\n";
      $html .= "        </tr>\n";   
      $html .= "        <tr>\n";
      $html .= "          <td class=\"modulo_list_claro\" align=\"center\">\n";
      $html .= "            <a href=\"".$action['revisar_admin']."\"><b>REVISION ADMINISTRATIVA DE CUENTAS POR PAGAR</b></a>\n";
      $html .= "          </td>\n";
      $html .= "        </tr>\n";
      $html .= "        <tr>\n";
      $html .= "          <td class=\"modulo_list_claro\" align=\"center\">\n";
      $html .= "            <a href=\"".$action['cxp_radicacion']."\"><b>MOSTRAR RADICACIONES</b></a>\n";
      $html .= "          </td>\n";
      $html .= "        </tr>\n";      
      $html .= "        <tr>\n";
      $html .= "          <td class=\"modulo_list_claro\" align=\"center\">\n";
      $html .= "            <a href=\"".$action['cxp']."\"><b>MOSTRAR CUENTAS POR PAGAR</b></a>\n";
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
		*
		* @param array $action vector que continen los link de la aplicacion
    * @param string $mensaje Cadena con el texto del mensaje a mostrar 
    *         en pantalla
    *
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
    /**
		* Crea una forma, para la descarga de los archivos de rips
		*
		* @param array $action vector que continen los link de la aplicacion
    * @param string $mensaje Cadena con el texto del mensaje a mostrar 
    *         en pantalla
    *
		* @return string
		*/
    function FormaDescargarArchivos($action,$mensaje,$id)
    {
      $urlDwn = "classes/zipArchive/zipArchiveDownload.php?id=".$id."&ubicacion=RIPS_EPS";      
  		
      $html .= ThemeAbrirTabla('GENERACION DE RIPS - CONFIRMACION');		
      $html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "		  <table width=\"100%\" class=\"modulo_table_list\">\n";
			$html .= "		    <tr class=\"normal_10AN\">\n";
			$html .= "		      <td align=\"center\">\n";
      $html .= "            ".$mensaje."<br>";
      $html .= "            <a href=\"".$urlDwn."\" class=\"label_error\" >\n";
      $html .= "              <img src=\"".GetThemePath()."/images/abajo.png\" border='0'>DESCARGAR\n";
			$html .= "            </a>\n";
      $html .= "          </td>\n";
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