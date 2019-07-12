
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
		$this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td>";
		$this->salida .= "      <table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td colspan=\"2\" align=\"center\">";
		$this->salida .= "      MENÚ - DATOS DE PARAMETRIZACIÓN";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" width=\"30%\" align=\"center\">";
		$this->salida .= "<a href=\"". ModuloGetURL('system','ParametrosOdontologia','user','TiposCuadrantesPOdont') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/flecha.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td class=\"label\" width=\"70%\">TIPOS CUADRANTES O SUPERFICIES</td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" width=\"30%\" align=\"center\">";
		$this->salida .= "<a href=\"". ModuloGetURL('system','ParametrosOdontologia','user','TiposProblemasPOdont') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/flecha.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td class=\"label\" width=\"70%\">TIPOS DE PROBLEMAS O HALLAZGOS</td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" width=\"30%\" align=\"center\">";
		$this->salida .= "<a href=\"". ModuloGetURL('system','ParametrosOdontologia','user','TiposSolucionesPOdont') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/flecha.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td class=\"label\" width=\"70%\">TIPOS DE SOLUCIONES O TRATAMIENTOS</td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr>";
		$accion=ModuloGetURL('system','Menu');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <td align=\"center\"><br>";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"menu\" value=\"MENÚ\"><br>";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//
	function TiposCuadrantesPOdont()//
	{
		UNSET($_SESSION['podont']['cuadragumo']);
		UNSET($_SESSION['podont']['tipocuadra']);
		$this->salida  = ThemeAbrirTabla('PARAMETRIZACIÓN DE ODONTOLOGÍA - TIPOS CUADRANTES O SUPERFICIES');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('system','ParametrosOdontologia','user','PrincipalPOdont') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		if($this->uno == 1)
		{
			$this->salida .= "<tr><td>";
			$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "</table>";
			$this->salida .= "</td></tr>";
		}
		$this->salida .= "  <tr>";
		$this->salida .= "  <td>";
		$this->salida .= "  <fieldset><legend class=\"field\">TIPOS CUADRANTES O SUPERFICIES</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"70%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"10%\">INDICE ORDEN</td>";
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
			array('guarmodi1'=>2,'indicecuad'=>$i)) ."\"><img src=\"".GetThemePath()."/images/modificar.png\" border=\"0\"></a>";
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
		$accion=ModuloGetURL('system','ParametrosOdontologia','user','NuevoTiposCuadrantesPOdont',array('guarmodi1'=>1));
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
		if($_SESSION['podont']['cuadragumo']['hc_tipo_cuadrante_id']==NULL AND $_REQUEST['guarmodi1']==2)
		{
			$_SESSION['podont']['cuadragumo']['hc_tipo_cuadrante_id']=$_SESSION['podont']['tipocuadra'][$_REQUEST['indicecuad']]['hc_tipo_cuadrante_id'];
			$_SESSION['podont']['cuadragumo']['descripcion']=$_SESSION['podont']['tipocuadra'][$_REQUEST['indicecuad']]['descripcion'];
			$_SESSION['podont']['cuadragumo']['indice_orden']=$_SESSION['podont']['tipocuadra'][$_REQUEST['indicecuad']]['indice_orden'];
			$_SESSION['podont']['cuadragumo']['sw_mostrar']=$_SESSION['podont']['tipocuadra'][$_REQUEST['indicecuad']]['sw_mostrar'];
			UNSET($_SESSION['podont']['tipocuadra']);
		}
		if($_REQUEST['guarmodi1']==1)
		{
			$this->salida  = ThemeAbrirTabla('PARAMETRIZACIÓN DE ODONTOLOGÍA - NUEVO TIPO DE CUADRANTES O SUPERFICIE');
		}
		else
		{
			$this->salida  = ThemeAbrirTabla('PARAMETRIZACIÓN DE ODONTOLOGÍA - MODIFICAR TIPO DE CUADRANTES O SUPERFICIE');
		}
		$accion=ModuloGetURL('system','ParametrosOdontologia','user','ValidarNuevoTiposCuadrantesPOdont',array('guarmodi1'=>$_REQUEST['guarmodi1']));
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		if($this->uno == 1)
		{
			$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "</table><br>";
		}
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
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"descripcio\" value=\"".$_SESSION['podont']['cuadragumo']['descripcion']."\" maxlength=\"20\" size=\"30\">";
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

	//
	function TiposProblemasPOdont()//
	{
		UNSET($_SESSION['podont']['problegumo']);
		UNSET($_SESSION['podont']['tipoproble']);
		UNSET($_SESSION['podont']['cargoscups']);
		$this->salida  = ThemeAbrirTabla('PARAMETRIZACIÓN DE ODONTOLOGÍA - TIPOS PROBLEMAS O HALLAZGOS');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('system','ParametrosOdontologia','user','PrincipalPOdont') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		if($this->uno == 1)
		{
			$this->salida .= "<tr><td>";
			$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "</table>";
			$this->salida .= "</td></tr>";
		}
		$this->salida .= "  <tr>";
		$this->salida .= "  <td>";
		$this->salida .= "  <fieldset><legend class=\"field\">TIPOS PROBLEMAS O HALLAZGOS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"36%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"12%\">INDICE ORDEN</td>";
		$this->salida .= "      <td width=\"12%\">PRESUPUESTO</td>";
		$this->salida .= "      <td width=\"18%\">SANO C.O.P.</td>";
		$this->salida .= "      <td width=\"14%\">DIENTE COMPLETO</td>";
		$this->salida .= "      <td width=\"8%\" >MODIFICAR</td>";
		$this->salida .= "      </tr>";
		$j=0;
		$_SESSION['podont']['tipoproble']=$this->BuscarTiposProblemasPOdont();
		$ciclo=sizeof($_SESSION['podont']['tipoproble']);
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
			$this->salida .= "".$_SESSION['podont']['tipoproble'][$i]['descripcion']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".$_SESSION['podont']['tipoproble'][$i]['indice_orden']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			if($_SESSION['podont']['tipoproble'][$i]['sw_presupuesto']==1)
			{
				$this->salida .= "SI";
			}
			else
			{
				$this->salida .= "NO";
			}
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "	<table border=\"1\" width=\"100%\" align=\"center\" $color>";
			$this->salida .= "	<tr>";
			$this->salida .= "	<td width=\"50%\" align=\"center\">";
			$this->salida .= "SANO";
			$this->salida .= "	</td>";
			$this->salida .= "	<td width=\"50%\" align=\"center\">";
			if($_SESSION['podont']['tipoproble'][$i]['sw_sanos']==1)
			{
				$this->salida .= "SI";
			}
			else
			{
				$this->salida .= "NO";
			}
			$this->salida .= "	</td>";
			$this->salida .= "	</tr>";
			$this->salida .= "	<tr>";
			$this->salida .= "	<td width=\"50%\" align=\"center\">";
			$this->salida .= "CARIADO";
			$this->salida .= "	</td>";
			$this->salida .= "	<td width=\"50%\" align=\"center\">";
			if($_SESSION['podont']['tipoproble'][$i]['sw_cariado']==1)
			{
				$this->salida .= "SI";
			}
			else
			{
				$this->salida .= "NO";
			}
			$this->salida .= "	</td>";
			$this->salida .= "	</tr>";
			$this->salida .= "	<tr>";
			$this->salida .= "	<td width=\"50%\" align=\"center\">";
			$this->salida .= "OBTURADO";
			$this->salida .= "	</td>";
			$this->salida .= "	<td width=\"50%\" align=\"center\">";
			if($_SESSION['podont']['tipoproble'][$i]['sw_obturado']==1)
			{
				$this->salida .= "SI";
			}
			else
			{
				$this->salida .= "NO";
			}
			$this->salida .= "	</td>";
			$this->salida .= "	</tr>";
			$this->salida .= "	<tr>";
			$this->salida .= "	<td width=\"50%\" align=\"center\">";
			$this->salida .= "PERDIDO";
			$this->salida .= "	</td>";
			$this->salida .= "	<td width=\"50%\" align=\"center\">";
			if($_SESSION['podont']['tipoproble'][$i]['sw_perdidos']==1)
			{
				$this->salida .= "SI";
			}
			else
			{
				$this->salida .= "NO";
			}
			$this->salida .= "	</td>";
			$this->salida .= "	</tr>";
			$this->salida .= "	</table>";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			if($_SESSION['podont']['tipoproble'][$i]['sw_diente_completo']==1)
			{
				$this->salida .= "SI";
			}
			else
			{
				$this->salida .= "NO";
			}
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "<a href=\"". ModuloGetURL('system','ParametrosOdontologia','user','NuevoTiposProblemasPOdont',
			array('guarmodi2'=>2,'indiceprob'=>$i)) ."\"><img src=\"".GetThemePath()."/images/modificar.png\" border=\"0\"></a>";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		if(empty($_SESSION['podont']['tipoproble']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"6\" align=\"center\">";
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
		$accion=ModuloGetURL('system','ParametrosOdontologia','user','NuevoTiposProblemasPOdont',array('guarmodi2'=>1));
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <td align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"nuevo\" value=\"NUEVO PROBLEMA\">";
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
	function NuevoTiposProblemasPOdont()//
	{
		UNSET($_SESSION['podont']['cargoscups']);
		if($_SESSION['podont']['problegumo']['hc_tipo_problema_diente_id']==NULL AND $_REQUEST['guarmodi2']==2)
		{
			$_SESSION['podont']['problegumo']['hc_tipo_problema_diente_id']=$_SESSION['podont']['tipoproble'][$_REQUEST['indiceprob']]['hc_tipo_problema_diente_id'];
			$_SESSION['podont']['problegumo']['descripcion']=$_SESSION['podont']['tipoproble'][$_REQUEST['indiceprob']]['descripcion'];
			$_SESSION['podont']['problegumo']['sw_presupuesto']=$_SESSION['podont']['tipoproble'][$_REQUEST['indiceprob']]['sw_presupuesto'];
			$_SESSION['podont']['problegumo']['indice_orden']=$_SESSION['podont']['tipoproble'][$_REQUEST['indiceprob']]['indice_orden'];
			$_SESSION['podont']['problegumo']['sw_cariado']=$_SESSION['podont']['tipoproble'][$_REQUEST['indiceprob']]['sw_cariado'];
			$_SESSION['podont']['problegumo']['sw_obturado']=$_SESSION['podont']['tipoproble'][$_REQUEST['indiceprob']]['sw_obturado'];
			$_SESSION['podont']['problegumo']['sw_perdidos']=$_SESSION['podont']['tipoproble'][$_REQUEST['indiceprob']]['sw_perdidos'];
			$_SESSION['podont']['problegumo']['sw_sanos']=$_SESSION['podont']['tipoproble'][$_REQUEST['indiceprob']]['sw_sanos'];
			$_SESSION['podont']['problegumo']['sw_diente_completo']=$_SESSION['podont']['tipoproble'][$_REQUEST['indiceprob']]['sw_diente_completo'];
			UNSET($_SESSION['podont']['tipoproble']);
		}
		if($_REQUEST['guarmodi2']==1)
		{
			$this->salida  = ThemeAbrirTabla('PARAMETRIZACIÓN DE ODONTOLOGÍA - NUEVO TIPO DE PROBLEMAS O HALLAZGOS');
		}
		else
		{
			$this->salida  = ThemeAbrirTabla('PARAMETRIZACIÓN DE ODONTOLOGÍA - MODIFICAR TIPO DE PROBLEMAS O HALLAZGOS');
		}
		$accion=ModuloGetURL('system','ParametrosOdontologia','user','ValidarNuevoTiposProblemasPOdont',array('guarmodi2'=>$_REQUEST['guarmodi2']));
		$this->salida .= "<form name=\"forma1\" action=\"$accion\" method=\"post\">";
		if($this->uno == 1)
		{
			$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "</table><br>";
		}
		$this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td>";
		$this->salida .= "  <fieldset><legend class=\"field\">INFORMACIÓN DEL PROBLEMA</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td width=\"30%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("descripcio")."\">DESCRIPCIÓN:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"70%\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"descripcio\" value=\"".$_SESSION['podont']['problegumo']['descripcion']."\" maxlength=\"40\" size=\"50\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"30%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("ordeindice")."\">INDICE DE ORDEN:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"70%\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"ordeindice\" value=\"".$_SESSION['podont']['problegumo']['indice_orden']."\" maxlength=\"4\" size=\"20\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td width=\"30%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("presupuest")."\">INCLUIR EN EL PRESUPUESTO:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"70%\">";
		$this->salida .= "SI  ";
		if($_SESSION['podont']['problegumo']['sw_presupuesto']==1)
		{
			$this->salida .= "      <input type=\"radio\" name=\"presupuest\" value=1 checked>";
		}
		else
		{
			$this->salida .= "      <input type=\"radio\" name=\"presupuest\" value=1>";
		}
		$this->salida .= "    NO  ";
		if($_SESSION['podont']['problegumo']['sw_presupuesto']==0)
		{
			$this->salida .= "      <input type=\"radio\" name=\"presupuest\" value=0 checked>";
		}
		else
		{
			$this->salida .= "      <input type=\"radio\" name=\"presupuest\" value=0>";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"30%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("cariados")."\">DIENTE CARIADO:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"70%\">";
		$this->salida .= "SI  ";
		if($_SESSION['podont']['problegumo']['sw_cariado']==1)
		{
			$this->salida .= "      <input type=\"radio\" name=\"cariados\" value=1 checked>";
		}
		else
		{
			$this->salida .= "      <input type=\"radio\" name=\"cariados\" value=1>";
		}
		$this->salida .= "    NO  ";
		if($_SESSION['podont']['problegumo']['sw_cariado']==0)
		{
			$this->salida .= "      <input type=\"radio\" name=\"cariados\" value=0 checked>";
		}
		else
		{
			$this->salida .= "      <input type=\"radio\" name=\"cariados\" value=0>";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td width=\"30%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("obturado")."\">DIENTE OBTURADO:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"70%\">";
		$this->salida .= "SI  ";
		if($_SESSION['podont']['problegumo']['sw_obturado']==1)
		{
			$this->salida .= "      <input type=\"radio\" name=\"obturado\" value=1 checked>";
		}
		else
		{
			$this->salida .= "      <input type=\"radio\" name=\"obturado\" value=1>";
		}
		$this->salida .= "    NO  ";
		if($_SESSION['podont']['problegumo']['sw_obturado']==0)
		{
			$this->salida .= "      <input type=\"radio\" name=\"obturado\" value=0 checked>";
		}
		else
		{
			$this->salida .= "      <input type=\"radio\" name=\"obturado\" value=0>";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"30%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("perdidos")."\">DIENTE PERDIDO:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"70%\">";
		$this->salida .= "SI  ";
		if($_SESSION['podont']['problegumo']['sw_perdidos']==1)
		{
			$this->salida .= "      <input type=\"radio\" name=\"perdidos\" value=1 checked>";
		}
		else
		{
			$this->salida .= "      <input type=\"radio\" name=\"perdidos\" value=1>";
		}
		$this->salida .= "    NO  ";
		if($_SESSION['podont']['problegumo']['sw_perdidos']==0)
		{
			$this->salida .= "      <input type=\"radio\" name=\"perdidos\" value=0 checked>";
		}
		else
		{
			$this->salida .= "      <input type=\"radio\" name=\"perdidos\" value=0>";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td width=\"30%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("dsanitos")."\">DIENTE SANO:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"70%\">";
		$this->salida .= "SI  ";
		if($_SESSION['podont']['problegumo']['sw_sanos']==1)
		{
			$this->salida .= "      <input type=\"radio\" name=\"dsanitos\" value=1 checked>";
		}
		else
		{
			$this->salida .= "      <input type=\"radio\" name=\"dsanitos\" value=1>";
		}
		$this->salida .= "    NO  ";
		if($_SESSION['podont']['problegumo']['sw_sanos']==0)
		{
			$this->salida .= "      <input type=\"radio\" name=\"dsanitos\" value=0 checked>";
		}
		else
		{
			$this->salida .= "      <input type=\"radio\" name=\"dsanitos\" value=0>";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"30%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("completo")."\">PROBLEMA DIENTE COMPLETO:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"70%\">";
		$this->salida .= "SI  ";
		if($_SESSION['podont']['problegumo']['sw_diente_completo']==1)
		{
			$this->salida .= "      <input type=\"radio\" name=\"completo\" value=1 checked>";
		}
		else
		{
			$this->salida .= "      <input type=\"radio\" name=\"completo\" value=1>";
		}
		$this->salida .= "    NO  ";
		if($_SESSION['podont']['problegumo']['sw_diente_completo']==0)
		{
			$this->salida .= "      <input type=\"radio\" name=\"completo\" value=0 checked>";
		}
		else
		{
			$this->salida .= "      <input type=\"radio\" name=\"completo\" value=0>";
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
		$accion=ModuloGetURL('system','ParametrosOdontologia','user','TiposProblemasPOdont');
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

	function RetornarBarraCargo()//Barra paginadora para elegir un cargo CUPS
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloGetURL('system','ParametrosOdontologia','user','SeleccionarCargoPOdont',array('conteo'=>$this->conteo,
		'codigopodo'=>$_REQUEST['codigopodo'],'descripodo'=>$_REQUEST['descripodo'],'guarmodi2'=>$_REQUEST['guarmodi2']));
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
		if(($_REQUEST['Of'])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		else
		{
			if($numpasos>10)
			{
				$valor=10+5;
			}
			else
			{
				$valor=$numpasos+5;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		return $salida;
	}

	//
	function TiposSolucionesPOdont()//
	{
		UNSET($_SESSION['podont']['solucigumo']);
		UNSET($_SESSION['podont']['tiposoluci']);
		$this->salida  = ThemeAbrirTabla('PARAMETRIZACIÓN DE ODONTOLOGÍA - TIPOS DE SOLUCIONES O TRATAMIENTOS');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('system','ParametrosOdontologia','user','PrincipalPOdont') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		if($this->uno == 1)
		{
			$this->salida .= "<tr><td>";
			$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "</table>";
			$this->salida .= "</td></tr>";
		}
		$this->salida .= "  <tr>";
		$this->salida .= "  <td>";
		$this->salida .= "  <fieldset><legend class=\"field\">TIPOS DE SOLUCIONES</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"80%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"10%\">INDICE ORDEN</td>";
		$this->salida .= "      <td width=\"10%\">MODIFICAR</td>";
		$this->salida .= "      </tr>";
		$j=0;
		$_SESSION['podont']['tiposoluci']=$this->BuscarTiposSolucionesPOdont();
		$ciclo=sizeof($_SESSION['podont']['tiposoluci']);
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
			$this->salida .= "".$_SESSION['podont']['tiposoluci'][$i]['descripcion']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".$_SESSION['podont']['tiposoluci'][$i]['indice_orden']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "<a href=\"". ModuloGetURL('system','ParametrosOdontologia','user','NuevoTiposSolucionesPOdont',
			array('guarmodi3'=>2,'indicesolu'=>$i)) ."\"><img src=\"".GetThemePath()."/images/modificar.png\" border=\"0\"></a>";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		if(empty($_SESSION['podont']['tiposoluci']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"3\" align=\"center\">";
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
		$accion=ModuloGetURL('system','ParametrosOdontologia','user','NuevoTiposSolucionesPOdont',array('guarmodi3'=>1));
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <td align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"nuevo\" value=\"NUEVA SOLUCIÓN\">";
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
	function NuevoTiposSolucionesPOdont()//
	{
		if($_SESSION['podont']['solucigumo']['hc_tipo_producto_diente_id']==NULL AND $_REQUEST['guarmodi3']==2)
		{
			$_SESSION['podont']['solucigumo']['hc_tipo_producto_diente_id']=$_SESSION['podont']['tiposoluci'][$_REQUEST['indicesolu']]['hc_tipo_producto_diente_id'];
			$_SESSION['podont']['solucigumo']['descripcion']=$_SESSION['podont']['tiposoluci'][$_REQUEST['indicesolu']]['descripcion'];
			$_SESSION['podont']['solucigumo']['indice_orden']=$_SESSION['podont']['tiposoluci'][$_REQUEST['indicesolu']]['indice_orden'];
			UNSET($_SESSION['podont']['tiposoluci']);
		}
		if($_REQUEST['guarmodi3']==1)
		{
			$this->salida  = ThemeAbrirTabla('PARAMETRIZACIÓN DE ODONTOLOGÍA - NUEVO TIPO DE SOLUCIÓN O TRATAMIENTO');
		}
		else
		{
			$this->salida  = ThemeAbrirTabla('PARAMETRIZACIÓN DE ODONTOLOGÍA - MODIFICAR TIPO DE SOLUCIÓN O TRATAMIENTO');
		}
		$accion=ModuloGetURL('system','ParametrosOdontologia','user','ValidarNuevoTiposSolucionesPOdont',array('guarmodi3'=>$_REQUEST['guarmodi3']));
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		if($this->uno == 1)
		{
			$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "</table><br>";
		}
		$this->salida .= "  <table border=\"0\" width=\"50%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td>";
		$this->salida .= "  <fieldset><legend class=\"field\">INFORMACIÓN DE LA SOLUCIÓN</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"30%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("descripcio")."\">DESCRIPCIÓN:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"70%\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"descripcio\" value=\"".$_SESSION['podont']['solucigumo']['descripcion']."\" maxlength=\"40\" size=\"40\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td width=\"30%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("ordeindice")."\">INDICE DE ORDEN:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"70%\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"ordeindice\" value=\"".$_SESSION['podont']['solucigumo']['indice_orden']."\" maxlength=\"4\" size=\"20\">";
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
		$accion=ModuloGetURL('system','ParametrosOdontologia','user','TiposSolucionesPOdont');
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
