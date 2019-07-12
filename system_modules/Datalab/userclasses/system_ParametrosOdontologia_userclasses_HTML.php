
<?php

/**
* Modulo de ParametrosOdontologia (PHP).
*
* Modulo para el mantenimiento de los cargos del tarifario cups y de
* los cargos de la interface con datalab, asi como sus equivalencias
*
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* system_ParametrosOdontologia_userclasses_HTML.php
*
**/

class system_ParametrosOdontologia_userclasses_HTML extends system_ParametrosOdontologia_user
{
	function system_ParametrosOdontologia_user_HTML()
	{
		$this->system_ParametrosOdontologia_user(); //Constructor del padre 'modulo'
		$this->salida='';
		return true;
	}

	//Función principal que da las opciones para tener acceso a los datos del Tarifario
	function PrincipalPOdont()//Llama a todas las opciones posibles
	{
		UNSET($_SESSION['podont']);
		$this->salida  = ThemeAbrirTabla('PARAMETRIZACIÓN DE ODONTOLOGÍA - OPCIONES');
		$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td>";
		$this->salida .= "  <fieldset><legend class=\"field\">DATOS DE PARAMETRIZACIÓN</legend>";
		$this->salida .= "      <table border=\"1\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" width=\"70%\">TIPOS CUADRANTES O SUPERFICIES</td>";
		$this->salida .= "      <td class=\"label\" width=\"30%\" align=\"center\">";
		$this->salida .= "<a href=\"". ModuloGetURL('system','ParametrosOdontologia','user','TiposCuadrantesPOdont') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/flecha.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" width=\"70%\">TIPOS DE PROBLEMAS O HALLAZGOS</td>";
		$this->salida .= "      <td class=\"label\" width=\"30%\" align=\"center\">";
		$this->salida .= "<a href=\"". ModuloGetURL('system','ParametrosOdontologia','user','ConsultarCargosDatala') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/flecha.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" width=\"70%\">TIPOS DE SOLUCIONES O TRATAMIENTOS</td>";
		$this->salida .= "      <td class=\"label\" width=\"30%\" align=\"center\">";
		$this->salida .= "<a href=\"". ModuloGetURL('system','ParametrosOdontologia','user','ConsultarCargosDatala') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/flecha.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\">";
		$accion=ModuloGetURL('system','Menu');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <br><input class=\"input-submit\" type=\"submit\" name=\"menu\" value=\"MENÚ\"><br>";
		$this->salida .= "  </form>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		if($this->uno == 1)
		{
			$this->salida .= "  <tr><td>";
			$this->salida .= "  <br><table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "  </table><br>";
			$this->salida .= "  </td></tr>";
		}
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//
	function TiposCuadrantesPOdont()//
	{
		UNSET($_SESSION['podont']['cuadragumo']);
		$this->salida  = ThemeAbrirTabla('PARAMETRIZACIÓN DE ODONTOLOGÍA - TIPOS CUADRANTES O SUPERFICIES');
		if($this->uno == 1)//Cambiar la variable
		{
			$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "</table><br>";
		}
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td>";
		$this->salida .= "  <fieldset><legend class=\"field\">TIPOS CUADRANTES O SUPERFICIES</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"70%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"10%\">INDICE DE ORDEN</td>";
		$this->salida .= "      <td width=\"10%\">ACTIVO</td>";
		$this->salida .= "      <td width=\"10%\">MODIFICAR</td>";
		$this->salida .= "      </tr>";
		$j=0;
		$_SESSION['podont']['tipocuadra']=$this->BuscarTiposCuadrantesPOdont();
		$ciclo=sizeof($_SESSION['podont']['tipocuadra']);
		for($i=0;($i<$ciclo);$i++)
		{
			if($j==0)
			{
				$color="class=\"modulo_list_claro\"";
				$j=1;
			}
			else
			{
				$color="class=\"modulo_list_oscuro\"";
				$j=0;
			}
			$this->salida .= "<tr $color>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".$_SESSION['podont']['tipocuadra'][$i]['descripcion']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".$_SESSION['podont']['tipocuadra'][$i]['indice_orden']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			if($_SESSION['podont']['tipocuadra'][$i]['sw_mostrar']==1)
			{
				$this->salida .= "SI";
			}
			else
			{
				$this->salida .= "NO";
			}
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "<a href=\"". ModuloGetURL('system','ParametrosOdontologia','user','NuevoTiposCuadrantesPOdont',
			array('guarmodi'=>2,'indicecuad'=>$i)) ."\"><img src=\"".GetThemePath()."/images/modificar.png\" border=\"0\"></a>";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		if(empty($_SESSION['podont']['tipocuadra']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"4\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN REGISTRO'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "  		</table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$accion=ModuloGetURL('system','ParametrosOdontologia','user','NuevoTiposCuadrantesPOdont',array('guarmodi'=>1));
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <td align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"nuevo\" value=\"NUEVO CUADRANTE\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$accion=ModuloGetURL('system','ParametrosOdontologia','user','PrincipalPOdont');
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <td align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//
	function NuevoTiposCuadrantesPOdont()//
	{
		if($_SESSION['podont']['cuadragumo']['hc_tipo_cuadrante_id']==NULL AND $_REQUEST['guarmodi']==2)
		{
			$_SESSION['podont']['cuadragumo']['hc_tipo_cuadrante_id']=$_SESSION['podont']['tipocuadra'][$_REQUEST['indicecuad']]['hc_tipo_cuadrante_id'];
			$_SESSION['podont']['cuadragumo']['descripcion']=$_SESSION['podont']['tipocuadra'][$_REQUEST['indicecuad']]['descripcion'];
			$_SESSION['podont']['cuadragumo']['indice_orden']=$_SESSION['podont']['tipocuadra'][$_REQUEST['indicecuad']]['indice_orden'];
			$_SESSION['podont']['cuadragumo']['sw_mostrar']=$_SESSION['podont']['tipocuadra'][$_REQUEST['indicecuad']]['sw_mostrar'];
			UNSET($_SESSION['podont']['tipocuadra']);
		}
		if($_REQUEST['guarmodi']==1)
		{
			$this->salida  = ThemeAbrirTabla('PARAMETRIZACIÓN DE ODONTOLOGÍA - NUEVO TIPO DE CUADRANTES O SUPERFICIE');
		}
		else
		{
			$this->salida  = ThemeAbrirTabla('PARAMETRIZACIÓN DE ODONTOLOGÍA - MODIFICAR TIPO DE CUADRANTES O SUPERFICIE');
		}
		$accion=ModuloGetURL('system','ParametrosOdontologia','user','ValidarNuevoTiposCuadrantesPOdont',array('guarmodi'=>$_REQUEST['guarmodi']));
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"50%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td>";
		$this->salida .= "  <fieldset><legend class=\"field\">INFORMACIÓN DEL CUADRANTE</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"30%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("descripcio")."\">DESCRIPCIÓN:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"70%\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"descripcio\" value=\"".$_SESSION['podont']['cuadragumo']['descripcion']."\" maxlength=\"20\" size=\"20\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td width=\"30%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("ordeindice")."\">INDICE DE ORDEN:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"70%\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"ordeindice\" value=\"".$_SESSION['podont']['cuadragumo']['indice_orden']."\" maxlength=\"4\" size=\"20\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"30%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("swmostrara")."\">ACTIVO (MOSTRAR):</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"70%\">";
		$this->salida .= "SI  ";
		if($_SESSION['podont']['cuadragumo']['sw_mostrar']==1)
		{
			$this->salida .= "      <input type=\"radio\" name=\"swmostrara\" value=1 checked>";
		}
		else
		{
			$this->salida .= "      <input type=\"radio\" name=\"swmostrara\" value=1>";
		}
		$this->salida .= "    NO  ";
		if($_SESSION['podont']['cuadragumo']['sw_mostrar']==0)
		{
			$this->salida .= "      <input type=\"radio\" name=\"swmostrara\" value=0 checked>";
		}
		else
		{
			$this->salida .= "      <input type=\"radio\" name=\"swmostrara\" value=0>";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "  		</table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\" width=\"50%\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$accion=ModuloGetURL('system','ParametrosOdontologia','user','TiposCuadrantesPOdont');
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <td align=\"center\" width=\"50%\">";
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
