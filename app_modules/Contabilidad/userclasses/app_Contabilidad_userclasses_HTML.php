
<?php

/**
* Modulo de Contabilidad (PHP).
*
*
*
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* app_Contabilidad_user.php
*
**/

class app_Contabilidad_userclasses_HTML extends app_Contabilidad_user
{
	function app_Contabilidad_user_HTML()
	{
		$this->app_Contabilidad_user(); //Constructor del padre 'modulo'
		$this->salida='';
		return true;
	}

	//Función principal que da las opciones para tener acceso a los datos de PARAMETROS DE HISTORIA CLINICA
	function PrincipalParaHC()//Llama a todas las opciones posibles
	{
		$this->salida  = ThemeAbrirTabla('PARAMETROS DE HISTORIA CLÍNICA - OPCIONES');
		$this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td colspan=\"2\" align=\"center\">";
		$this->salida .= "      MENÚ";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td width=\"20%\" class=\"modulo_list_claro\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','SaludOcupacional','user','PrincipalSalud2') ."\"><img src=\"".GetThemePath()."/images/flecha.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"80%\" class=\"label\" align=\"left\">SALUD OCUPACIONAL";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td width=\"20%\" class=\"modulo_list_claro\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','SolicitudesFrecuentes','user','PrincipalSolfre2') ."\"><img src=\"".GetThemePath()."/images/flecha.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"80%\" class=\"label\" align=\"left\">SOLICITUDES FRECUENTES";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td width=\"20%\" class=\"modulo_list_claro\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','PYP','user','Menu') ."\"><img src=\"".GetThemePath()."/images/flecha.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"80%\" class=\"label\" align=\"left\">PROMOCIÓN Y PREVENCIÓN";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$accion=ModuloGetURL('system','Menu');
		$this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\"><br>";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

}//fin de la clase
?>
