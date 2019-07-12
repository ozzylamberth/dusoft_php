
<?php

/**
* Modulo de Parametros de Contabilidad (PHP).
*
* Modulo que permite parametrizar las características de la contabilidad
*
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* app_ContabilidadPara_user.php
*
**/

class app_ContabilidadPara_userclasses_HTML extends app_ContabilidadPara_user
{
	function app_ContabilidadPara_user_HTML()
	{
		$this->app_ContabilidadPara_user(); //Constructor del padre 'modulo'
		$this->salida='';
		return true;
	}

	//Determina las empresas, en las cuales el usuario tiene permisos
	function PrincipalConpar2()//Selecciona las empresas disponibles
	{
		UNSET($_SESSION['conpar']);
		UNSET($_SESSION['conpa1']);
		if($this->UsuariosConpar()==false)
		{
			return false;
		}
		return true;
	}

	//Función principal que da las opciones para tener acceso a los datos de PARAMETROS DE LA CONTABILIDAD
	function PrincipalConpar()//Llama a todas las opciones posibles
	{
		if($_SESSION['conpar']['empresa']==NULL)
		{
			$_SESSION['conpar']['empresa']=$_REQUEST['permisoscontpa']['empresa_id'];
			$_SESSION['conpar']['razonso']=$_REQUEST['permisoscontpa']['descripcion1'];
		}
		UNSET($_SESSION['conpa1']);
		$this->salida  = ThemeAbrirTabla('PARÁMETROS DE LA CONTABILIDAD - OPCIONES');
		$this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      MENÚ";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','ContabilidadPara','user','LlamaServiciosConpar') ."\">CONTABILIDAD SEGÚN LOS SERVICIOS</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$accion=ModuloGetURL('app','ContabilidadPara','user','PrincipalConpar2');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
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

	//Selecciona el servicio a parametrizar
	function ServiciosConpar()//Selecciona un servicio y un departamento
	{
		if($_SESSION['conpa1']['serviciocp']<>NULL)
		{
			$_POST['servicio']=$_SESSION['conpa1']['serviciocp'];
			$_POST['nombserv']=$_SESSION['conpa1']['nombservcp'];
			$_POST['departam']=$_SESSION['conpa1']['departamcp'];
			$_POST['nombdepa']=$_SESSION['conpa1']['nombdepacp'];
		}
		UNSET($_SESSION['conpa1']);
		$this->salida  = ThemeAbrirTabla('PARÁMETROS DE LA CONTABILIDAD - SERVICIOS');
		$this->salida .= "<SCRIPT>";
		$this->salida .= "function abrirVentanaClass(nombre, url, ancho, altura, x, frm){\n";
		$this->salida .= "var str = 'width=600,height=180,resizable=no,status=no,scrollbars=no,top=200,left=200';\n";
		$this->salida .= "var url2 = url+'?servicio='+frm.servicio.value+'&departam='+frm.departam.value;";
		$this->salida .= "var rems = window.open(url2, nombre, str);\n";
		$this->salida .= "if (rems != null) {\n";
		$this->salida .= "   if (rems.opener == null) {\n";
		$this->salida .= "       rems.opener = self;\n";
		$this->salida .= "   }\n";
		$this->salida .= "}\n";
		$this->salida .= "}\n";
		$this->salida .= "</SCRIPT>";
		$accion=ModuloGetURL('app','ContabilidadPara','user','LlamaParametrosCuentasConpar');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">OPCIONES DE SERVICIOS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['conpar']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td width=\"30%\" class=\"label\">SERVICIO</td>";
		$this->salida .= "      <td width=\"70%\">";
		$this->salida .= "      <input type=\"text\" name=\"nombserv\" value=\"".$_POST['nombserv']."\" size=\"100\" class=\"input-text\" readonly>";
		$this->salida .= "      <input type=\"hidden\" name=\"servicio\" value=\"".$_POST['servicio']."\" class=\"input-text\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td width=\"30%\" class=\"label\">DEPARTAMENTO</td>";
		$this->salida .= "      <td width=\"70%\">";
		$this->salida .= "      <input type=\"text\" name=\"nombdepa\" value=\"".$_POST['nombdepa']."\" size=\"100\" class=\"input-text\" readonly>";
		$this->salida .= "      <input type=\"hidden\" name=\"departam\" value=\"".$_POST['departam']."\" class=\"input-text\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$ruta='app_modules/ContabilidadPara/ServiciosDepartamentos.php';
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td colspan=\"2\" align=\"center\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"button\" name=\"cambiar\" value=\"SELECCIONAR\" onclick=\"abrirVentanaClass('PARAMETROS','$ruta',450,200,0,this.form)\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\" width=\"50%\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"CONTINUAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  <td align=\"center\" width=\"50%\">";
		$accion=ModuloGetURL('app','ContabilidadPara','user','PrincipalConpar');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"OPCIONES\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que permite clasificar los cargos según el grupo y subgrupo tarifario, en las cuentas
	function ParametrosCuentasConpar()//Válida las cuentas en donde se contabilizarán los cargos
	{
		if($_SESSION['conpa1']['serviciocp']==NULL)
		{
			$_SESSION['conpa1']['serviciocp']=$_POST['servicio'];
			$_SESSION['conpa1']['nombservcp']=$_POST['nombserv'];
			$_SESSION['conpa1']['departamcp']=$_POST['departam'];
			$_SESSION['conpa1']['nombdepacp']=$_POST['nombdepa'];
		}
		UNSET($_SESSION['conpa1']['grutaconpa']);
		UNSET($_SESSION['conpa1']['datgruconp']);
		UNSET($_SESSION['conpa1']['cargocuecp']);
		$this->salida  = ThemeAbrirTabla('PARÁMETROS DE LA CONTABILIDAD - CUENTAS');
		$accion=ModuloGetURL('app','ContabilidadPara','user','ValidarParametrosCuentasConpar');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','ContabilidadPara','user','LlamaServiciosConpar') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">CUENTAS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['conpar']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">SERVICIO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['conpa1']['nombservcp']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DEPARTAMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['conpa1']['nombdepacp']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"25%\">GRUPOS TIPOS CARGOS</td>";
		$this->salida .= "      <td width=\"40%\">TIPOS CARGOS</td>";
		$this->salida .= "      <td width=\"25%\">CUENTA</td>";
		$this->salida .= "      <td width=\"10%\">DETALLES</td>";
		$this->salida .= "      </tr>";
		$j=0;
		$_SESSION['conpa1']['grutaconpa']=$this->BuscarParametrosCuentasConpar(
		$_SESSION['conpar']['empresa'],$_SESSION['conpa1']['departamcp']);
		$cuenta=$this->BuscarPlanCuentasConpar($_SESSION['conpar']['empresa']);
		$ciclo=sizeof($_SESSION['conpa1']['grutaconpa']);
		$ciclo1=sizeof($cuenta);
		for($i=0;$i<$ciclo;)
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
			$this->salida .= "  <tr $color>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['conpa1']['grutaconpa'][$i]['des1']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" $color>";
			$k=$i;
			while($_SESSION['conpa1']['grutaconpa'][$i]['grupo_tipo_cargo']==$_SESSION['conpa1']['grutaconpa'][$k]['grupo_tipo_cargo'])
			{
				$this->salida .= "  <tr>";
				$this->salida .= "  <td height=\"30\">";
				$this->salida .= "".$_SESSION['conpa1']['grutaconpa'][$k]['des2']."";
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$k++;
			}
			$this->salida .= "      </table>";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" $color>";
			$k=$i;
			while($_SESSION['conpa1']['grutaconpa'][$i]['grupo_tipo_cargo']==$_SESSION['conpa1']['grutaconpa'][$k]['grupo_tipo_cargo'])
			{
				$this->salida .= "  <tr>";
				$this->salida .= "  <td height=\"30\" align=\"center\">";
				$this->salida .= "  <select name=\"cuentaconp".$k."\" class=\"select\">";
				$this->salida .= "  <option value=\"\">----</option>";
				$l=$k;
				while($_SESSION['conpa1']['grutaconpa'][$k]['grupo_tipo_cargo']==$_SESSION['conpa1']['grutaconpa'][$l]['grupo_tipo_cargo']
				AND $_SESSION['conpa1']['grutaconpa'][$k]['tipo_cargo']==$_SESSION['conpa1']['grutaconpa'][$l]['tipo_cargo'])
				{
					$m=0;
					while($m<$ciclo1)
					{
						if($_SESSION['conpa1']['grutaconpa'][$l]['cuenta']<>NULL AND $_SESSION['conpa1']['grutaconpa'][$l]['cuenta']==$cuenta[$m]['cuenta'])
						{
							$this->salida .="<option value=\"".$cuenta[$m]['cuenta']."\" selected>".$cuenta[$m]['cuenta']."&nbsp&nbsp;".$cuenta[$m]['descripcion']."</option>";
						}
						else if($_SESSION['conpa1']['grutaconpa'][$l]['cuenta']==NULL OR ($_POST['cuentatodo']==$cuenta[$m]['cuenta'] AND $_POST['cuentatodo']<>NULL)
						OR $_SESSION['conpa1']['grutaconpa'][$l]['cuenta']<>NULL AND $_SESSION['conpa1']['grutaconpa'][$l]['cuenta']<>$cuenta[$m]['cuenta'])
						{
							$this->salida .="<option value=\"".$cuenta[$m]['cuenta']."\">".$cuenta[$m]['cuenta']."&nbsp&nbsp;".$cuenta[$m]['descripcion']."</option>";
						}
						$m++;
					}
					$l++;
				}
				$this->salida .= "  </select>";
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$k=$l;
			}
			$this->salida .= "      </table>";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" $color>";
			$k=$i;
			while($_SESSION['conpa1']['grutaconpa'][$i]['grupo_tipo_cargo']==$_SESSION['conpa1']['grutaconpa'][$k]['grupo_tipo_cargo'])
			{
				$this->salida .= "  <tr>";
				$this->salida .= "  <td height=\"30\" align=\"center\">";
				if($_SESSION['conpa1']['grutaconpa'][$k]['cuenta']<>NULL)
				{
					$this->salida .= "<a href=\"". ModuloGetURL('app','ContabilidadPara','user','LlamaParaExceCuentasConpar',
					array('indicegruc'=>$k)) ."\"><img src=\"".GetThemePath()."/images/pexcepcion.png\" border=\"0\"></a>";
				}
				else
				{
					$this->salida .= "<img src=\"".GetThemePath()."/images/pinexcepcion.png\" border=\"0\">";
				}
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$k++;
			}
			$this->salida .= "      </table>";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$i=$k;
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\" width=\"50%\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  <td align=\"center\" width=\"50%\">";
		$accion=ModuloGetURL('app','ContabilidadPara','user','LlamaServiciosConpar');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//
	function ParaExceCuentasConpar()//
	{
		if($_SESSION['conpa1']['datgruconp']['grupo_tipo_cargo']==NULL)
		{
			$_SESSION['conpa1']['datgruconp']['grupo_tipo_cargo']=$_SESSION['conpa1']['grutaconpa'][$_REQUEST['indicegruc']]['grupo_tipo_cargo'];
			$_SESSION['conpa1']['datgruconp']['des1']=$_SESSION['conpa1']['grutaconpa'][$_REQUEST['indicegruc']]['des1'];
			$_SESSION['conpa1']['datgruconp']['tipo_cargo']=$_SESSION['conpa1']['grutaconpa'][$_REQUEST['indicegruc']]['tipo_cargo'];
			$_SESSION['conpa1']['datgruconp']['des2']=$_SESSION['conpa1']['grutaconpa'][$_REQUEST['indicegruc']]['des2'];
			$_SESSION['conpa1']['datgruconp']['cuenta']=$_SESSION['conpa1']['grutaconpa'][$_REQUEST['indicegruc']]['cuenta'];
			$_SESSION['conpa1']['datgruconp']['descripcion']=$_SESSION['conpa1']['grutaconpa'][$_REQUEST['indicegruc']]['descripcion'];
			UNSET($_SESSION['conpa1']['grutaconpa']);
		}
		UNSET($_SESSION['conpa1']['cargocuecp']);
		$this->salida  = ThemeAbrirTabla('PARÁMETROS DE LA CONTABILIDAD - CUENTAS - EXCEPCIONES');
		$accion=ModuloGetURL('app','ContabilidadPara','user','ValidarParaExceCuentasConpar',array('Of'=>$_REQUEST['Of'],
		'paso'=>$_REQUEST['paso'],'codigoconp'=>$_REQUEST['codigoconp'],'descriconp'=>$_REQUEST['descriconp']));
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','ContabilidadPara','user','ParametrosCuentasConpar') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">EXCEPCIONES A LAS CUENTAS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['conpar']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">SERVICIO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['conpa1']['nombservcp']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DEPARTAMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['conpa1']['nombdepacp']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"20%\">GRUPO TIPO CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"40%\">";
		$this->salida .= "      ".$_SESSION['conpa1']['datgruconp']['des1']."";
		$this->salida .= "      </td>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"10%\">CUENTA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"30%\">";
		$this->salida .= "      ".$_SESSION['conpa1']['datgruconp']['cuenta']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"20%\">TIPO CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"40%\">";
		$this->salida .= "      ".$_SESSION['conpa1']['datgruconp']['des2']."";
		$this->salida .= "      </td>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"10%\">DESCRIPCIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"30%\">";
		$this->salida .= "      ".$_SESSION['conpa1']['datgruconp']['descripcion']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"8%\" >CARGO</td>";
		$this->salida .= "      <td width=\"67%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"25%\">CUENTA</td>";
		$this->salida .= "      </tr>";
		$_SESSION['conpa1']['cargocuecp']=$this->BuscarParaExceCuentasConpar($_SESSION['conpar']['empresa'],$_SESSION['conpa1']['departamcp'],
		$_SESSION['conpa1']['datgruconp']['grupo_tipo_cargo'],$_SESSION['conpa1']['datgruconp']['tipo_cargo']);
		$cuenta=$this->BuscarPlanCuentasConpar($_SESSION['conpar']['empresa']);
		$ciclo=sizeof($_SESSION['conpa1']['cargocuecp']);
		$ciclo1=sizeof($cuenta);
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
			$this->salida .= "".$_SESSION['conpa1']['cargocuecp'][$i]['cargo']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$_SESSION['conpa1']['cargocuecp'][$i]['descripcion']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "<select name=\"cuentaexpc".$i."\" class=\"select\">";
			$this->salida .= "<option value=\"\">----</option>";
			$m=0;
			while($m<$ciclo1)
			{
				if($_SESSION['conpa1']['cargocuecp'][$i]['cuenta']<>NULL AND $_SESSION['conpa1']['cargocuecp'][$i]['cuenta']==$cuenta[$m]['cuenta'])
				{
					$this->salida .="<option value=\"".$cuenta[$m]['cuenta']."\" selected>".$cuenta[$m]['cuenta']."&nbsp&nbsp;".$cuenta[$m]['descripcion']."</option>";
				}
				else
				{
					$this->salida .="<option value=\"".$cuenta[$m]['cuenta']."\">".$cuenta[$m]['cuenta']."&nbsp&nbsp;".$cuenta[$m]['descripcion']."</option>";
				}
				$m++;
			}
			$this->salida .= "</select>";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		if(empty($_SESSION['conpa1']['cargocuecp']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"3\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN CARGO EN EL TARIFARIO PARA ESTE GRUPO Y SUBGRUPO'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\" width=\"50%\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  <td align=\"center\" width=\"50%\">";
		$accion=ModuloGetURL('app','ContabilidadPara','user','ParametrosCuentasConpar');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var=$this->RetornarBarraCarPlanCue();
		if(!empty($var))
		{
			$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td width=\"100%\" align=\"center\">";
			$this->salida .=$var;
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  </table><br>";
		}
		$accion=ModuloGetURL('app','ContabilidadPara','user','ParaExceCuentasConpar',array(
		'codigoconp'=>$_REQUEST['codigoconp'],'descriconp'=>$_REQUEST['descriconp']));
		$this->salida .= "  <form name=\"contrata2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">CÓDIGO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigoconp\" value=\"".$_REQUEST['codigoconp']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descriconp\" value=\"".$_REQUEST['descriconp']."\" maxlength=\"50\" size=\"35\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td colspan=\"2\" align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"BUSCAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td colspan=\"2\" align=\"center\">";
		$accion=ModuloGetURL('app','ContabilidadPara','user','ParaExceCuentasConpar');
		$this->salida .= "  <form name=\"contrata3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function RetornarBarraCarPlanCue()//Barra paginadora de los cargos del grupo y subgrupo
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
		$accion=ModuloGetURL('app','ContabilidadPara','user','ParaExceCuentasConpar',array('conteo'=>$this->conteo,
		'codigoconp'=>$_REQUEST['codigoconp'],'descriconp'=>$_REQUEST['descriconp']));
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

}//fin de la clase
?>
