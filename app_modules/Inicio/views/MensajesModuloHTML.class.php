<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: MensajesModuloHTML.class.php,v 1.2 2008/03/28 18:23:48 hugo Exp $
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
		* Constructor de la clase
		*/
		function MensajesModuloHTML(){}
		/**
    * Funcion donde se crea la forma de Inicio de la apliacion
    * 
    * @param array $action Links de la apliacion
    * 
    * @return string
    */
    function FormaInicial($action)
    {
      $html .= ThemeAbrirTabla('SISTEMA INTEGRAL DE INFORMACIÓN EN SALUD','500');
      $html .= "<br>\n"; 
      $html .= "<br>\n";
      $html .= "<table width='306' height='96' border='0' align='center'>\n";
      $html .= "  <tr>\n";
      $html .= "    <td width='150' height='96' background=\"".GetThemePath() . "/images/logo_grande/logo_grande.png\">\n";
      $html .= "      <div align='center'>\n";
      $html .= "        <font color='#999999'  size='+7'>";
      $html .= "          <strong></strong>\n";
      $html .= "        </font>\n";
      $html .= "      </div>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td width='150'></td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= "<br><br>\n";
      /*if(!empty($action))
      {
        foreach($action as $key => $link)
          $html .= "  <a class=\"label_error\" href=\"".$link."\">".$key."</a>\n";
      }*/
      $html .= ThemeCerrarTabla();
      return $html;
    }
    /**
    * Funcion donde se crea una forma para visualizar las tabñas que tienen vista
    * 
    * @param string $titulo Titulo de la vista
    * @param array $campos arreglo de datos con la informacion de la vista
    * @param array $datos arreglo de datos con la informacion de las tablas
    * @param array $url arreglo de datos con la informacion de los links de las tablas
    * @param string $volver Link de regreso de la tabla
    *
    * @return string
    */
    function FormaMenuTablas($titulo,$campos,$datos,$url,$volver)
    {
      $html  = ThemeMenuAbrirTabla($titulo,"50%");
      $html .= "<table width='100%' border='0' cellspacing=\"0\" cellpadding=\"12\">";
      $html .= "  <tr>\n";
      $html .= "    <td>\n";
      $html .= "      <table border='0' width='0' cellspacing=\"0\" cellpadding=\"0\">\n";
      $html .= "        <tr>\n";
      $html .= "          <td class='normal_10N'><img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'>&nbsp;&nbsp;".$campos[0]."</td>\n";
      $html .= "        </tr>\n";
      $html .= "      </table>\n";
      foreach ($datos as $key => $value)
      {
        $href = ModuloGetURL($url['contenedor'],$url['modulo'],$url['tipo'],$url['metodo'],$value);
        $html .= ThemeSubMenuTabla("<a href=\"".$href."\">$key</a>","100%");
      }
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= "<table width='100%' border='0' cellspacing=\"0\" cellpadding=\"8\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td align='center' class=\"label_error\">\n";
      $html .= "      <a href='".$volver."'>VOLVER</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= ThemeMenuCerrarTabla();
      
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
	}
?>