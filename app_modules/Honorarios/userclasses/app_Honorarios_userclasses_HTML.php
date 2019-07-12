
<?php

/**
* Modulo de Honorarios (PHP).
*
* Modulo para la liquidación de los honorarios profesionales, por grupos o cargos
*
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* app_Honorarios_user.php
*
* Establece los terminos de liquidación de los honorarios a los profesionales medicos,
* sea a modo individual o como un pool, que puede tener o no un tercero para su contabilidad,
* así mismo estos honorarios pueden ser en el ámbito del cargo o clasificación del
* grupo tipo cargo y tipo cargo, ofreciendo la capacidad de ligarse a un servicio como
* segundo nivel y a un plan de contratación como tercer nivel, con horarios adicionales
**/

class app_Honorarios_userclasses_HTML extends app_Honorarios_user
{
	function app_Honorarios_user_HTML()
	{
		$this->app_Honorarios_user(); //Constructor del padre 'modulo'
		$this->salida='';
		return true;
	}

	//Función principal que da las opciones para tener acceso a los datos de HONORARIOS
	function PrincipalHonora2()//Llama a todas las opciones posibles
	{
		UNSET($_SESSION['honora']);
		UNSET($_SESSION['honor1']);
		UNSET($_SESSION['honor2']);
		UNSET($_SESSION['honor3']);
		UNSET($_SESSION['honor4']);
		UNSET($_SESSION['honorp']);
		if($this->UsuariosHonora()==false)
		{
			return false;
		}
		return true;
	}

	//Función principal que da las opciones para tener acceso a los datos de HONORARIOS
	function PrincipalHonora()//Llama a todas las opciones posibles
	{
		if($_SESSION['honora']['empresa']==NULL)
		{
			$_SESSION['honora']['empresa']=$_REQUEST['permisohonorario']['empresa_id'];
			$_SESSION['honora']['razonso']=$_REQUEST['permisohonorario']['descripcion1'];
		}
		UNSET($_SESSION['honor1']);
		UNSET($_SESSION['honor2']);
		UNSET($_SESSION['honor3']);
		UNSET($_SESSION['honor4']);
		UNSET($_SESSION['honorp']);
		$this->salida  = ThemeAbrirTabla('HONORARIOS - OPCIONES');
		$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">";
		$this->salida .= "      NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      MENÚ";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','ProfesionalGrupoHonora') ."\">HONORARIOS DE PROFESIONALES POR GRUPO</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','ProfesionalGrupoSerHonora') ."\">HONORARIOS DE PROFESIONALES POR GRUPO Y SERVICIO</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','ProfesionalGrupoPlaHonora') ."\">HONORARIOS DE PROFESIONALES POR GRUPO Y PLAN</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','ProfesionalCargoHonora') ."\">HONORARIOS DE PROFESIONALES POR CARGO</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','ProfesionalCargoSerHonora') ."\">HONORARIOS DE PROFESIONALES POR CARGO Y SERVICIO</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','ProfesionalCargoPlaHonora') ."\">HONORARIOS DE PROFESIONALES POR CARGO Y PLAN</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','PoolGrupoHonora') ."\">HONORARIOS DE UN POOL DE PROFESIONALES POR GRUPO</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','PoolGrupoSerHonora') ."\">HONORARIOS DE UN POOL DE PROFESIONALES POR GRUPO Y SERVICIO</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','PoolGrupoPlaHonora') ."\">HONORARIOS DE UN POOL DE PROFESIONALES POR GRUPO Y PLAN</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','PoolCargoHonora') ."\">HONORARIOS DE UN POOL DE PROFESIONALES POR CARGO</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','PoolCargoSerHonora') ."\">HONORARIOS DE UN POOL DE PROFESIONALES POR CARGO Y SERVICIO</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','PoolCargoPlaHonora') ."\">HONORARIOS DE UN POOL DE PROFESIONALES POR CARGO Y PLAN</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		/*$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','AdministraPoolHonora') ."\">ADMINISTRACIÓN DE POOL DE PROFESIONALES</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";*/
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','PruebasLiquidaHonora') ."\">PRUEBAS DE LIQUIDACIÓN DE HONORARIOS</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$accion=ModuloGetURL('app','Honorarios','user','PrincipalHonora2');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\"><br>";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"EMPRESAS\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	/*PROFESIONAL Y GRUPOS*/

	//Función que busca un profesional, para establecer sus honorarios
	function ProfesionalGrupoHonora()//Busca el profesional para relacionar
	{
		UNSET($_SESSION['honor1']);
		$this->salida  = ThemeAbrirTabla('HONORARIOS - PROFESIONALES POR GRUPOS');
		$accion=ModuloGetURL('app','Honorarios','user','ValidarProfesionalGrupoHonora');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','PrincipalHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - SELECCIONAR UN PROFESIONAL</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
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
		$this->salida .= "      <td width=\"15%\">IDENTIFICACIÓN</td>";
		$this->salida .= "      <td width=\"65%\">NOMBRE DEL PROFESIONAL</td>";
		$this->salida .= "      <td width=\"10%\">HONORARIOS</td>";
		$this->salida .= "      <td width=\"5%\" >ESTADO</td>";
		$this->salida .= "      <td width=\"5%\" >ELEGIR</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor1']['prgruposho']=$this->BuscarProfesionalGrupoHonora($_SESSION['honora']['empresa']);
		$ciclo=sizeof($_SESSION['honor1']['prgruposho']);
		for($i=0;$i<$ciclo;$i++)
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
			$this->salida .= "".$_SESSION['honor1']['prgruposho'][$i]['tipo_id_tercero']."".' -- '."".$_SESSION['honor1']['prgruposho'][$i]['tercero_id']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['honor1']['prgruposho'][$i]['nombre_tercero']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor1']['prgruposho'][$i]['honorarios']>0)
			{
				$this->salida .= "SI";
			}
			else
			{
				$this->salida .= "NO";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor1']['prgruposho'][$i]['estado']==1)
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/activo.gif\" border=\"0\">";
			}
			else
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/inactivo.gif\" border=\"0\">";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor1']['prgruposho'][$i]['estado']==1)
			{
				$this->salida .= "<input type='radio' name='selprofeho' value=\"".$i."\">";
			}
			else
			{
				$this->salida .= "<input disabled='true' type='radio' name='selprofeho' value=\"".$i."\">";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['honor1']['prgruposho']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"5\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN PROFESIONAL'";
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
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"continuar\" value=\"CONTINUAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$accion=ModuloGetURL('app','Honorarios','user','PrincipalHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <td align=\"center\" width=\"50%\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"OPCIONES\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var=$this->RetornarBarraProGruHon();
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
		$accion=ModuloGetURL('app','Honorarios','user','ProfesionalGrupoHonora',
		array('tipodohono'=>$_REQUEST['tipodohono'],'codigohono'=>$_REQUEST['codigohono'],'descrihono'=>$_REQUEST['descrihono']));
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">TIPO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <select name=\"tipodohono\" class=\"select\">";
		$this->salida .= "  <option value=\"\">----</option>";
		$terceros=$this->TercerosHonora();
		$ciclo1=sizeof($terceros);
		for($k=0;$k<$ciclo1;$k++)
		{
			if($terceros[$k]['tipo_id_tercero']==$_REQUEST['tipodohono'])
			{
				$this->salida .="<option value=\"".$terceros[$k]['tipo_id_tercero']."\" selected>".$terceros[$k]['descripcion']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$terceros[$k]['tipo_id_tercero']."\">".$terceros[$k]['descripcion']."</option>";
			}
		}
		$this->salida .= "  </select>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">DOCUMENTO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigohono\" value=\"".$_REQUEST['codigohono']."\" maxlength=\"32\" size=\"40\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">NOMBRE:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrihono\" value=\"".$_REQUEST['descrihono']."\" maxlength=\"60\" size=\"40\">";
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
		$accion=ModuloGetURL('app','Honorarios','user','ProfesionalGrupoHonora');
		$this->salida .= "  <form name=\"forma3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que permite validar la asignación de los grupos tipo cargos, según los cargos con sw_honorarios en 1
	function GruposHonora()//Válida los grupos tipo cargos que tengan cargos con honorarios
	{
		UNSET($_SESSION['honor1']['prgruposho']);
		UNSET($_SESSION['honor1']['pgrupocaho']);
		UNSET($_SESSION['honor1']['pgrupoadho']);
		UNSET($_SESSION['honor1']['pgrupohadh']);
		$this->salida  = ThemeAbrirTabla('HONORARIOS - PROFESIONALES POR GRUPOS - CLASIFICACIÓN GRUPOS TIPOS CARGOS - CUPS');
		$this->salida .= "<SCRIPT>";
		$this->salida .= "function abrirVentanaClass(nombre, url, ancho, altura, x, frm, indice){\n";
		$this->salida .= "var str = 'width=900,height=600,resizable=no,status=no,scrollbars=si,top=50,left=50';\n";
		$this->salida .= "var cas = 1;";
		$this->salida .= "var url2 = url+'?indice='+indice+'&caso='+cas;";
		$this->salida .= "var rems = window.open(url2, nombre, str);\n";
		$this->salida .= "if (rems != null) {\n";
		$this->salida .= "   if (rems.opener == null) {\n";
		$this->salida .= "       rems.opener = self;\n";
		$this->salida .= "   }\n";
		$this->salida .= "}\n";
		$this->salida .= "}\n";
		$this->salida .= "</SCRIPT>";
		$ruta='app_modules/Honorarios/Honoproadi.php';
		$accion=ModuloGetURL('app','Honorarios','user','ValidarGruposHonora');
		$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','ProfesionalGrupoHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - GRUPOS TIPOS CARGOS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL PROFESIONAL:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor1']['gruposproh']['nombreprof']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">IDENTIFICACIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor1']['gruposproh']['tipodoprof']."".' -- '."".$_SESSION['honor1']['gruposproh']['documeprof']."";
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
		$this->salida .= "      <td width=\"30%\">GRUPOS CARGOS</td>";
		$this->salida .= "      <td width=\"47%\">TIPOS CARGOS</td>";
		$this->salida .= "      <td width=\"10%\">PORCENTAJE</td>";
		$this->salida .= "      <td width=\"5%\" >INFO</td>";
		$this->salida .= "      <td width=\"8%\" >HOR. ADIC.</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor1']['pgrupocaho']=$this->BuscarGruposHonora($_SESSION['honora']['empresa'],
		$_SESSION['honor1']['gruposproh']['tipodoprof'],$_SESSION['honor1']['gruposproh']['documeprof']);
		$ciclo=sizeof($_SESSION['honor1']['pgrupocaho']);
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
			$this->salida .= "".$_SESSION['honor1']['pgrupocaho'][$i]['des1']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" $color>";
			$k=$i;
			while($_SESSION['honor1']['pgrupocaho'][$i]['grupo_tipo_cargo']==$_SESSION['honor1']['pgrupocaho'][$k]['grupo_tipo_cargo'])
			{
				$this->salida .= "  <tr>";
				$this->salida .= "  <td height=\"30\">";
				$this->salida .= "".$_SESSION['honor1']['pgrupocaho'][$k]['des2']."";
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$k++;
			}
			$this->salida .= "      </table>";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" $color>";
			$k=$i;
			while($_SESSION['honor1']['pgrupocaho'][$i]['grupo_tipo_cargo']==$_SESSION['honor1']['pgrupocaho'][$k]['grupo_tipo_cargo'])
			{
				$this->salida .= "  <tr>";
				$this->salida .= "  <td height=\"30\" align=\"center\">";
				if($_SESSION['honor1']['pgrupocaho'][$k]['honorario_grupo_id']<>NULL)
				{
					$_POST['porcentaje'.$k]=$_SESSION['honor1']['pgrupocaho'][$k]['porcentaje'];
				}
				else
				{
					$_POST['porcentaje'.$k]='';
				}
				$this->salida .= "<input type=\"text\" class=\"input-text\" name=\"porcentaje".$k."\" value=\"".$_POST['porcentaje'.$k]."\" maxlength=\"8\" size=\"8\">";
				$this->salida .= "".' %'."";
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$k++;
			}
			$this->salida .= "      </table>";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" $color>";
			$k=$i;
			while($_SESSION['honor1']['pgrupocaho'][$i]['grupo_tipo_cargo']==$_SESSION['honor1']['pgrupocaho'][$k]['grupo_tipo_cargo'])
			{
				$this->salida .= "  <tr>";
				$this->salida .= "  <td height=\"30\" align=\"center\">";
				if($_SESSION['honor1']['pgrupocaho'][$k]['honorarios']>0)
				{
					$this->salida .= "<a href=\"javascript:abrirVentanaClass('PARAMETROS','$ruta',450,200,0,this.form,'$k');\">
					<img src=\"".GetThemePath()."/images/honorarioscon.png\" border=\"0\"></a>";
				}
				else
				{
					$this->salida .= "<img src=\"".GetThemePath()."/images/honorariosconin.png\" border=\"0\">";
				}
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$k++;
			}
			$this->salida .= "      </table>";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" $color>";
			$k=$i;
			while($_SESSION['honor1']['pgrupocaho'][$i]['grupo_tipo_cargo']==$_SESSION['honor1']['pgrupocaho'][$k]['grupo_tipo_cargo'])
			{
				$this->salida .= "  <tr>";
				$this->salida .= "  <td height=\"30\" align=\"center\">";
				if($_SESSION['honor1']['pgrupocaho'][$k]['honorario_grupo_id']<>NULL)
				{
					$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','GruposAdicioHonora',
					array('indicegruh'=>$k)) ."\"><img src=\"".GetThemePath()."/images/honorarios.png\" border=\"0\"></a>";
				}
				else
				{
					$this->salida .= "<img src=\"".GetThemePath()."/images/honorariosin.png\" border=\"0\">";
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
		if(empty($_SESSION['honor1']['pgrupocaho']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"5\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN GRUPO TIPO CARGO'";
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
		$accion=ModuloGetURL('app','Honorarios','user','ProfesionalGrupoHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <td align=\"center\" width=\"50%\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      CAMBIAR DE OPCIÓN SIN CAMBIAR DE PROFESIONAL";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		/*$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','CambiarProfesionalHonora',array('de'=>1,'al'=>1)) ."\">HONORARIOS DE PROFESIONALES POR GRUPO</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";*/
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','CambiarProfesionalHonora',array('de'=>1,'al'=>2)) ."\">HONORARIOS DE PROFESIONALES POR GRUPO Y SERVICIO</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','CambiarProfesionalHonora',array('de'=>1,'al'=>3)) ."\">HONORARIOS DE PROFESIONALES POR GRUPO Y PLAN</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','CambiarProfesionalHonora',array('de'=>1,'al'=>4)) ."\">HONORARIOS DE PROFESIONALES POR CARGO</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','CambiarProfesionalHonora',array('de'=>1,'al'=>5)) ."\">HONORARIOS DE PROFESIONALES POR CARGO Y SERVICIO</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','CambiarProfesionalHonora',array('de'=>1,'al'=>6)) ."\">HONORARIOS DE PROFESIONALES POR CARGO Y PLAN</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que permite generar los honorarios en jornadas de trabajo adicionales
	function GruposAdicioHonora()//Genera "excepciones" al porcentaje del honorario en otro horario
	{
		if($_SESSION['honor1']['pgrupoadho']['honorario_grupo_id']==NULL)
		{
			$_SESSION['honor1']['pgrupoadho']['honorario_grupo_id']=$_SESSION['honor1']['pgrupocaho'][$_REQUEST['indicegruh']]['honorario_grupo_id'];
			$_SESSION['honor1']['pgrupoadho']['des1']=$_SESSION['honor1']['pgrupocaho'][$_REQUEST['indicegruh']]['des1'];
			$_SESSION['honor1']['pgrupoadho']['des2']=$_SESSION['honor1']['pgrupocaho'][$_REQUEST['indicegruh']]['des2'];
			$_SESSION['honor1']['pgrupoadho']['porcentaje']=$_SESSION['honor1']['pgrupocaho'][$_REQUEST['indicegruh']]['porcentaje'];
			UNSET($_SESSION['honor1']['pgrupocaho']);
		}
		UNSET($_SESSION['honor1']['pgrupohadh']);
		$this->salida  = ThemeAbrirTabla('HONORARIOS - PROFESIONALES POR GRUPOS - HORARIOS ADICIONALES');
		$accion=ModuloGetURL('app','Honorarios','user','ValidarGruposAdicioHonora');
		$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','GruposHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - HORARIOS ADICIONALES - PORCENTAJE</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL PROFESIONAL:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor1']['gruposproh']['nombreprof']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">IDENTIFICACIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor1']['gruposproh']['tipodoprof']."".' -- '."".$_SESSION['honor1']['gruposproh']['documeprof']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">GRUPO TIPO CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor1']['pgrupoadho']['des1']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">TIPO CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor1']['pgrupoadho']['des2']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PORCENTAJE:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor1']['pgrupoadho']['porcentaje']."".' %'."";
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
		$this->salida .= "      <td width=\"5%\" ></td>";
		$this->salida .= "      <td width=\"75%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"20%\">PORCENTAJE</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor1']['pgrupohadh']=$this->BuscarGruposAdicioHonora($_SESSION['honor1']['pgrupoadho']['honorario_grupo_id']);
		$ciclo=sizeof($_SESSION['honor1']['pgrupohadh']);
		for($i=0;$i<$ciclo;$i++)
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
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "".($i+1)."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td height=\"30\">";
			$this->salida .= "".$_SESSION['honor1']['pgrupohadh'][$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td height=\"30\" align=\"center\">";
			if($_SESSION['honor1']['pgrupohadh'][$i]['porcentaje']<>NULL)
			{
				$_POST['porcenadic'.$i]=$_SESSION['honor1']['pgrupohadh'][$i]['porcentaje'];
			}
			else
			{
				$_POST['porcenadic'.$i]='';
			}
			$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"porcenadic".$i."\" value=\"".$_POST['porcenadic'.$i]."\" maxlength=\"8\" size=\"10\">";
			$this->salida .= "".' %'."";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['honor1']['pgrupohadh']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"3\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN HORARIO ADICIONAL'";
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
		$accion=ModuloGetURL('app','Honorarios','user','GruposHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	/*PROFESIONAL GRUPOS Y SERVICIOS*/

	//Función que permite elegir un profesional, para asignar los porcentajes según los grupos y servicios
	function ProfesionalGrupoSerHonora()//Válida los profesionales, para crearles sus honorarios
	{
		UNSET($_SESSION['honor1']);
		$this->salida  = ThemeAbrirTabla('HONORARIOS - PROFESIONALES POR GRUPOS Y SERVICIOS');
		$accion=ModuloGetURL('app','Honorarios','user','ValidarProfesionalGrupoSerHonora');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','PrincipalHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - SELECCIONAR UN PROFESIONAL</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
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
		$this->salida .= "      <td width=\"15%\">IDENTIFICACIÓN</td>";
		$this->salida .= "      <td width=\"65%\">NOMBRE DEL PROFESIONAL</td>";
		$this->salida .= "      <td width=\"10%\">HONORARIOS</td>";
		$this->salida .= "      <td width=\"5%\" >ESTADO</td>";
		$this->salida .= "      <td width=\"5%\" >ELEGIR</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor1']['prgruserho']=$this->BuscarProfesionalGrupoHonora($_SESSION['honora']['empresa']);
		$ciclo=sizeof($_SESSION['honor1']['prgruserho']);
		for($i=0;$i<$ciclo;$i++)
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
			$this->salida .= "".$_SESSION['honor1']['prgruserho'][$i]['tipo_id_tercero']."".' -- '."".$_SESSION['honor1']['prgruserho'][$i]['tercero_id']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['honor1']['prgruserho'][$i]['nombre_tercero']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor1']['prgruserho'][$i]['honorarios']>0)
			{
				$this->salida .= "SI";
			}
			else
			{
				$this->salida .= "NO";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor1']['prgruserho'][$i]['estado']==1)
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/activo.gif\" border=\"0\">";
			}
			else
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/inactivo.gif\" border=\"0\">";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor1']['prgruserho'][$i]['estado']==1)
			{
				$this->salida .= "<input type='radio' name='selprofeho' value=\"".$i."\">";
			}
			else
			{
				$this->salida .= "<input disabled='true' type='radio' name='selprofeho' value=\"".$i."\">";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['honor1']['prgruserho']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"5\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN PROFESIONAL'";
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
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"continuar\" value=\"CONTINUAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$accion=ModuloGetURL('app','Honorarios','user','PrincipalHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <td align=\"center\" width=\"50%\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"OPCIONES\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var=$this->RetornarBarraProGruSerHon();
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
		$accion=ModuloGetURL('app','Honorarios','user','ProfesionalGrupoSerHonora',
		array('tipodohono'=>$_REQUEST['tipodohono'],'codigohono'=>$_REQUEST['codigohono'],'descrihono'=>$_REQUEST['descrihono']));
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">TIPO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <select name=\"tipodohono\" class=\"select\">";
		$this->salida .= "  <option value=\"\">----</option>";
		$terceros=$this->TercerosHonora();
		$ciclo1=sizeof($terceros);
		for($k=0;$k<$ciclo1;$k++)
		{
			if($terceros[$k]['tipo_id_tercero']==$_REQUEST['tipodohono'])
			{
				$this->salida .="<option value=\"".$terceros[$k]['tipo_id_tercero']."\" selected>".$terceros[$k]['descripcion']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$terceros[$k]['tipo_id_tercero']."\">".$terceros[$k]['descripcion']."</option>";
			}
		}
		$this->salida .= "  </select>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">DOCUMENTO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigohono\" value=\"".$_REQUEST['codigohono']."\" maxlength=\"32\" size=\"40\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">NOMBRE:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrihono\" value=\"".$_REQUEST['descrihono']."\" maxlength=\"60\" size=\"40\">";
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
		$accion=ModuloGetURL('app','Honorarios','user','ProfesionalGrupoSerHonora');
		$this->salida .= "  <form name=\"forma3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que permite validar la asignación de los grupos tipo cargos y servicios, según los cargos con sw_honorarios en 1
	function GruposSerHonora()//Válida los servicios con honorarios, por grupo tipo cargo y tipo cargo
	{
		UNSET($_SESSION['honor1']['prgruserho']);
		UNSET($_SESSION['honor1']['pgruservho']);
		UNSET($_SESSION['honor1']['servprgrh1']);//LOS SERVICIOS
		UNSET($_SESSION['honor1']['pgruseadho']);
		UNSET($_SESSION['honor1']['pgrusehadh']);
		$this->salida  = ThemeAbrirTabla('HONORARIOS - PROFESIONALES POR GRUPOS Y SERVICIOS - CLASIFICACIÓN GRUPOS TIPOS CARGOS - CUPS');
		$this->salida .= "<SCRIPT>";
		$this->salida .= "function abrirVentanaClass(nombre, url, ancho, altura, x, frm, indice){\n";
		$this->salida .= "var str = 'width=900,height=600,resizable=no,status=no,scrollbars=si,top=50,left=50';\n";
		$this->salida .= "var cas = 2;";
		$this->salida .= "var url2 = url+'?indice='+indice+'&caso='+cas;";
		$this->salida .= "var rems = window.open(url2, nombre, str);\n";
		$this->salida .= "if (rems != null) {\n";
		$this->salida .= "   if (rems.opener == null) {\n";
		$this->salida .= "       rems.opener = self;\n";
		$this->salida .= "   }\n";
		$this->salida .= "}\n";
		$this->salida .= "}\n";
		$this->salida .= "</SCRIPT>";
		$ruta='app_modules/Honorarios/Honoproadi.php';
		$accion=ModuloGetURL('app','Honorarios','user','ValidarGruposSerHonora');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','ProfesionalGrupoSerHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - GRUPOS TIPOS CARGOS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL PROFESIONAL:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor1']['gruserproh']['nombreprof']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">IDENTIFICACIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor1']['gruserproh']['tipodoprof']."".' -- '."".$_SESSION['honor1']['gruserproh']['documeprof']."";
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
		$this->salida .= "      <td width=\"25%\">GRUPOS CARGOS</td>";
		$this->salida .= "      <td width=\"22%\">TIPOS CARGOS</td>";
		$this->salida .= "      <td width=\"30%\">SERVICIOS</td>";
		$this->salida .= "      <td width=\"10%\">PORCENTAJE</td>";
		$this->salida .= "      <td width=\"5%\" >INFO</td>";
		$this->salida .= "      <td width=\"8%\" >HOR. ADIC.</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor1']['pgruservho']=$this->BuscarGruposSerHonora($_SESSION['honora']['empresa'],
		$_SESSION['honor1']['gruserproh']['tipodoprof'],$_SESSION['honor1']['gruserproh']['documeprof']);
		$_SESSION['honor1']['servprgrh1']=$this->BuscarServiciosHonora();
		$ciclo=sizeof($_SESSION['honor1']['pgruservho']);
		$ciclo1=sizeof($_SESSION['honor1']['servprgrh1']);
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
			$this->salida .= "".$_SESSION['honor1']['pgruservho'][$i]['des1']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td colspan=\"3\">";
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" $color>";
			$k=$i;
			while($_SESSION['honor1']['pgruservho'][$i]['grupo_tipo_cargo']==$_SESSION['honor1']['pgruservho'][$k]['grupo_tipo_cargo'])
			{
				$this->salida .= "  <tr>";
				$this->salida .= "  <td height=\"30\" width=\"35%\">";
				$this->salida .= "".$_SESSION['honor1']['pgruservho'][$k]['des2']."";
				$this->salida .= "  </td>";
				$this->salida .= "  <td height=\"30\" width=\"65%\">";
				$this->salida .= "      <table border=\"1\" width=\"100%\" align=\"center\" $color>";
				$l=$k;
				while($_SESSION['honor1']['pgruservho'][$k]['grupo_tipo_cargo']==$_SESSION['honor1']['pgruservho'][$l]['grupo_tipo_cargo']
				AND $_SESSION['honor1']['pgruservho'][$k]['tipo_cargo']==$_SESSION['honor1']['pgruservho'][$l]['tipo_cargo'])
				{
					for($s=0;$s<$ciclo1;$s++)
					{
						$sw=0;
						$this->salida .= "<tr>";
						$this->salida .= "<td height=\"30\" align=\"center\" width=\"75%\">";
						$this->salida .= "".$_SESSION['honor1']['servprgrh1'][$s]['descripcion']."";
						$this->salida .= "</td>";
						$this->salida .= "<td height=\"30\" align=\"center\" width=\"25%\">";
						if($_SESSION['honor1']['pgruservho'][$l]['servicio']==$_SESSION['honor1']['servprgrh1'][$s]['servicio'])
						{
							$_POST['porcentaje'.$k.$s]=$_SESSION['honor1']['pgruservho'][$l]['porcentaje'];
							$sw=1;
						}
						else if($_SESSION['honor1']['pgruservho'][$l]['servicio']<>$_SESSION['honor1']['servprgrh1'][$s]['servicio'])
						{
							$_POST['porcentaje'.$k.$s]='';
						}
						$this->salida .= "<input type=\"text\" class=\"input-text\" name=\"porcentaje".$k.$s."\" value=\"".$_POST['porcentaje'.$k.$s]."\" maxlength=\"8\" size=\"8\">";
						$this->salida .= "".' %'."";
						$this->salida .= "</td>";
						if($sw==1)
						{
							$l++;
						}
						if($_SESSION['honor1']['pgruservho'][$l]['servicio']==NULL AND $s==($ciclo1-1)
						AND $_SESSION['honor1']['pgruservho'][$k]['grupo_tipo_cargo']==$_SESSION['honor1']['pgruservho'][$l]['grupo_tipo_cargo']
						AND $_SESSION['honor1']['pgruservho'][$k]['tipo_cargo']==$_SESSION['honor1']['pgruservho'][$l]['tipo_cargo'])
						{
							$l++;
						}
						$this->salida .= "</tr>";
					}
				}
				$this->salida .= "      </table>";
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$k=$l;
			}
			$this->salida .= "      </table>";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" $color>";
			$k=$i;
			while($_SESSION['honor1']['pgruservho'][$i]['grupo_tipo_cargo']==$_SESSION['honor1']['pgruservho'][$k]['grupo_tipo_cargo'])
			{
				$l=$k;
				while($_SESSION['honor1']['pgruservho'][$k]['grupo_tipo_cargo']==$_SESSION['honor1']['pgruservho'][$l]['grupo_tipo_cargo']
				AND $_SESSION['honor1']['pgruservho'][$k]['tipo_cargo']==$_SESSION['honor1']['pgruservho'][$l]['tipo_cargo'])
				{
					for($s=0;$s<$ciclo1;$s++)
					{
						$sw=0;
						$this->salida .= "<tr>";
						$this->salida .= "<td height=\"30\" align=\"center\">";
						if($_SESSION['honor1']['pgruservho'][$l]['servicio']==$_SESSION['honor1']['servprgrh1'][$s]['servicio'])
						{
							if($_SESSION['honor1']['pgruservho'][$l]['honorarios']>0)
							{
								$this->salida .= "<a href=\"javascript:abrirVentanaClass('PARAMETROS','$ruta',450,200,0,this.form,'$l');\">
								<img src=\"".GetThemePath()."/images/honorarioscon.png\" border=\"0\"></a>";
							}
							else
							{
								$this->salida .= "<img src=\"".GetThemePath()."/images/honorariosconin.png\" border=\"0\">";
							}
							$sw=1;
						}
						else
						{
							if($_SESSION['honor1']['pgruservho'][$l]['honorarios']>0)
							{
								$this->salida .= "<a href=\"javascript:abrirVentanaClass('PARAMETROS','$ruta',450,200,0,this.form,'$l');\">
								<img src=\"".GetThemePath()."/images/honorarioscon.png\" border=\"0\"></a>";
							}
							else
							{
								$this->salida .= "<img src=\"".GetThemePath()."/images/honorariosconin.png\" border=\"0\">";
							}
						}
						$this->salida .= "</td>";
						if($sw==1)
						{
							$l++;
						}
						if($_SESSION['honor1']['pgruservho'][$l]['servicio']==NULL AND $s==($ciclo1-1)
						AND $_SESSION['honor1']['pgruservho'][$k]['grupo_tipo_cargo']==$_SESSION['honor1']['pgruservho'][$l]['grupo_tipo_cargo']
						AND $_SESSION['honor1']['pgruservho'][$k]['tipo_cargo']==$_SESSION['honor1']['pgruservho'][$l]['tipo_cargo'])
						{
							$l++;
						}
						$this->salida .= "</tr>";
					}
				}
				$k=$l;
			}
			$this->salida .= "      </table>";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" $color>";
			$k=$i;
			while($_SESSION['honor1']['pgruservho'][$i]['grupo_tipo_cargo']==$_SESSION['honor1']['pgruservho'][$k]['grupo_tipo_cargo'])
			{
				$l=$k;
				while($_SESSION['honor1']['pgruservho'][$k]['grupo_tipo_cargo']==$_SESSION['honor1']['pgruservho'][$l]['grupo_tipo_cargo']
				AND $_SESSION['honor1']['pgruservho'][$k]['tipo_cargo']==$_SESSION['honor1']['pgruservho'][$l]['tipo_cargo'])
				{
					for($s=0;$s<$ciclo1;$s++)
					{
						$sw=0;
						$this->salida .= "<tr>";
						$this->salida .= "<td height=\"30\" align=\"center\">";
						if($_SESSION['honor1']['pgruservho'][$l]['servicio']==$_SESSION['honor1']['servprgrh1'][$s]['servicio'])
						{
							$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','GruposSerAdicioHonora',
							array('indigruseh'=>$l,'serdescrip'=>$_SESSION['honor1']['servprgrh1'][$s]['descripcion'])) ."\">
							<img src=\"".GetThemePath()."/images/honorarios.png\" border=\"0\"></a>";
							$sw=1;
						}
						else
						{
							$this->salida .= "<img src=\"".GetThemePath()."/images/honorariosin.png\" border=\"0\">";
						}
						$this->salida .= "</td>";
						if($sw==1)
						{
							$l++;
						}
						if($_SESSION['honor1']['pgruservho'][$l]['servicio']==NULL AND $s==($ciclo1-1)
						AND $_SESSION['honor1']['pgruservho'][$k]['grupo_tipo_cargo']==$_SESSION['honor1']['pgruservho'][$l]['grupo_tipo_cargo']
						AND $_SESSION['honor1']['pgruservho'][$k]['tipo_cargo']==$_SESSION['honor1']['pgruservho'][$l]['tipo_cargo'])
						{
							$l++;
						}
						$this->salida .= "</tr>";
					}
				}
				$k=$l;
			}
			$this->salida .= "      </table>";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$i=$k;
		}
		if(empty($_SESSION['honor1']['pgruservho']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"5\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN GRUPO TIPO CARGO'";
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
		$accion=ModuloGetURL('app','Honorarios','user','ProfesionalGrupoSerHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      CAMBIAR DE OPCIÓN SIN CAMBIAR DE PROFESIONAL";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','CambiarProfesionalHonora',array('de'=>2,'al'=>1)) ."\">HONORARIOS DE PROFESIONALES POR GRUPO</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		/*$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','CambiarProfesionalHonora',array('de'=>2,'al'=>2)) ."\">HONORARIOS DE PROFESIONALES POR GRUPO Y SERVICIO</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";*/
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','CambiarProfesionalHonora',array('de'=>2,'al'=>3)) ."\">HONORARIOS DE PROFESIONALES POR GRUPO Y PLAN</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','CambiarProfesionalHonora',array('de'=>2,'al'=>4)) ."\">HONORARIOS DE PROFESIONALES POR CARGO</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','CambiarProfesionalHonora',array('de'=>2,'al'=>5)) ."\">HONORARIOS DE PROFESIONALES POR CARGO Y SERVICIO</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','CambiarProfesionalHonora',array('de'=>2,'al'=>6)) ."\">HONORARIOS DE PROFESIONALES POR CARGO Y PLAN</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que permite generar los honorarios en jornadas de trabajo adicionales
	function GruposSerAdicioHonora()//Genera "excepciones" al porcentaje del honorario en otro horario
	{
		if($_SESSION['honor1']['pgruseadho']['honorario_grupo_id']==NULL)
		{
			$_SESSION['honor1']['pgruseadho']['honorario_grupo_id']=$_SESSION['honor1']['pgruservho'][$_REQUEST['indigruseh']]['honorario_grupo_id'];
			$_SESSION['honor1']['pgruseadho']['des1']=$_SESSION['honor1']['pgruservho'][$_REQUEST['indigruseh']]['des1'];
			$_SESSION['honor1']['pgruseadho']['des2']=$_SESSION['honor1']['pgruservho'][$_REQUEST['indigruseh']]['des2'];
			$_SESSION['honor1']['pgruseadho']['des3']=$_REQUEST['serdescrip'];
			$_SESSION['honor1']['pgruseadho']['porcentaje']=$_SESSION['honor1']['pgruservho'][$_REQUEST['indigruseh']]['porcentaje'];
			UNSET($_SESSION['honor1']['pgruservho']);
		}
		UNSET($_SESSION['honor1']['pgrusehadh']);
		$this->salida  = ThemeAbrirTabla('HONORARIOS - PROFESIONALES POR GRUPOS Y SERVICIOS - HORARIOS ADICIONALES');
		$accion=ModuloGetURL('app','Honorarios','user','ValidarGruposSerAdicioHonora');
		$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','GruposSerHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - HORARIOS ADICIONALES - PORCENTAJE</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL PROFESIONAL:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor1']['gruserproh']['nombreprof']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">IDENTIFICACIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor1']['gruserproh']['tipodoprof']."".' -- '."".$_SESSION['honor1']['gruserproh']['documeprof']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">GRUPO TIPO CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor1']['pgruseadho']['des1']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">TIPO CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor1']['pgruseadho']['des2']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">SERVICIO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor1']['pgruseadho']['des3']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PORCENTAJE:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor1']['pgruseadho']['porcentaje']."".' %'."";
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
		$this->salida .= "      <td width=\"5%\" ></td>";
		$this->salida .= "      <td width=\"75%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"20%\">PORCENTAJE</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor1']['pgrusehadh']=$this->BuscarGruposSerAdicioHonora($_SESSION['honor1']['pgruseadho']['honorario_grupo_id']);
		$ciclo=sizeof($_SESSION['honor1']['pgrusehadh']);
		for($i=0;$i<$ciclo;$i++)
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
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "".($i+1)."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td height=\"30\">";
			$this->salida .= "".$_SESSION['honor1']['pgrusehadh'][$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td height=\"30\" align=\"center\">";
			if($_SESSION['honor1']['pgrusehadh'][$i]['porcentaje']<>NULL)
			{
				$_POST['porcenadic'.$i]=$_SESSION['honor1']['pgrusehadh'][$i]['porcentaje'];
			}
			else
			{
				$_POST['porcenadic'.$i]='';
			}
			$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"porcenadic".$i."\" value=\"".$_POST['porcenadic'.$i]."\" maxlength=\"8\" size=\"10\">";
			$this->salida .= "".' %'."";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['honor1']['pgrusehadh']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"3\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN HORARIO ADICIONAL'";
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
		$accion=ModuloGetURL('app','Honorarios','user','GruposSerHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	/*PROFESIONAL GRUPOS Y PLANES*/

	//Función que permite elegir un profesional, para asignar los porcentajes según los grupos y planes
	function ProfesionalGrupoPlaHonora()//Válida los profesionales, para crearles sus honorarios
	{
		UNSET($_SESSION['honor1']);
		$this->salida  = ThemeAbrirTabla('HONORARIOS - PROFESIONALES POR GRUPOS Y PLANES');
		$accion=ModuloGetURL('app','Honorarios','user','ValidarProfesionalGrupoPlaHonora');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','PrincipalHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - SELECCIONAR UN PROFESIONAL</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
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
		$this->salida .= "      <td width=\"15%\">IDENTIFICACIÓN</td>";
		$this->salida .= "      <td width=\"65%\">NOMBRE DEL PROFESIONAL</td>";
		$this->salida .= "      <td width=\"10%\">HONORARIOS</td>";
		$this->salida .= "      <td width=\"5%\" >ESTADO</td>";
		$this->salida .= "      <td width=\"5%\" >ELEGIR</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor1']['prgruplaho']=$this->BuscarProfesionalGrupoHonora($_SESSION['honora']['empresa']);
		$ciclo=sizeof($_SESSION['honor1']['prgruplaho']);
		for($i=0;$i<$ciclo;$i++)
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
			$this->salida .= "".$_SESSION['honor1']['prgruplaho'][$i]['tipo_id_tercero']."".' -- '."".$_SESSION['honor1']['prgruplaho'][$i]['tercero_id']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['honor1']['prgruplaho'][$i]['nombre_tercero']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor1']['prgruplaho'][$i]['honorarios']>0)
			{
				$this->salida .= "SI";
			}
			else
			{
				$this->salida .= "NO";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor1']['prgruplaho'][$i]['estado']==1)
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/activo.gif\" border=\"0\">";
			}
			else
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/inactivo.gif\" border=\"0\">";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor1']['prgruplaho'][$i]['estado']==1)
			{
				$this->salida .= "<input type='radio' name='selprofeho' value=\"".$i."\">";
			}
			else
			{
				$this->salida .= "<input disabled='true' type='radio' name='selprofeho' value=\"".$i."\">";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['honor1']['prgruplaho']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"5\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN PROFESIONAL'";
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
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"continuar\" value=\"CONTINUAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$accion=ModuloGetURL('app','Honorarios','user','PrincipalHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <td align=\"center\" width=\"50%\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"OPCIONES\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var=$this->RetornarBarraProGruPlaHon();
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
		$accion=ModuloGetURL('app','Honorarios','user','ProfesionalGrupoPlaHonora',
		array('tipodohono'=>$_REQUEST['tipodohono'],'codigohono'=>$_REQUEST['codigohono'],'descrihono'=>$_REQUEST['descrihono']));
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">TIPO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <select name=\"tipodohono\" class=\"select\">";
		$this->salida .= "  <option value=\"\">----</option>";
		$terceros=$this->TercerosHonora();
		$ciclo1=sizeof($terceros);
		for($k=0;$k<$ciclo1;$k++)
		{
			if($terceros[$k]['tipo_id_tercero']==$_REQUEST['tipodohono'])
			{
				$this->salida .="<option value=\"".$terceros[$k]['tipo_id_tercero']."\" selected>".$terceros[$k]['descripcion']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$terceros[$k]['tipo_id_tercero']."\">".$terceros[$k]['descripcion']."</option>";
			}
		}
		$this->salida .= "  </select>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">DOCUMENTO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigohono\" value=\"".$_REQUEST['codigohono']."\" maxlength=\"32\" size=\"40\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">NOMBRE:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrihono\" value=\"".$_REQUEST['descrihono']."\" maxlength=\"60\" size=\"40\">";
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
		$accion=ModuloGetURL('app','Honorarios','user','ProfesionalGrupoPlaHonora');
		$this->salida .= "  <form name=\"forma3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//
	function GruposPlaHonora()//
	{
		UNSET($_SESSION['honor1']['prgruplaho']);
		UNSET($_SESSION['honor1']['pgruplanho']);
		UNSET($_SESSION['honor1']['pgrplporho']);
		UNSET($_SESSION['honor1']['pgruplpoho']);
		$this->salida  = ThemeAbrirTabla('HONORARIOS - PROFESIONALES POR GRUPOS Y PLANES - CLASIFICACIÓN GRUPOS TIPOS CARGOS - CUPS');
		$this->salida .= "<SCRIPT>";
		$this->salida .= "function abrirVentanaClass(nombre, url, ancho, altura, x, frm, indice){\n";
		$this->salida .= "var str = 'width=900,height=600,resizable=no,status=no,scrollbars=si,top=50,left=50';\n";
		$this->salida .= "var cas = 3;";
		$this->salida .= "var url2 = url+'?indice='+indice+'&caso='+cas;";
		$this->salida .= "var rems = window.open(url2, nombre, str);\n";
		$this->salida .= "if (rems != null) {\n";
		$this->salida .= "   if (rems.opener == null) {\n";
		$this->salida .= "       rems.opener = self;\n";
		$this->salida .= "   }\n";
		$this->salida .= "}\n";
		$this->salida .= "}\n";
		$this->salida .= "</SCRIPT>";
		$ruta='app_modules/Honorarios/Honoproadi.php';
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','ProfesionalGrupoPlaHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - GRUPOS TIPOS CARGOS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL PROFESIONAL:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor1']['gruplaproh']['nombreprof']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">IDENTIFICACIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor1']['gruplaproh']['tipodoprof']."".' -- '."".$_SESSION['honor1']['gruplaproh']['documeprof']."";
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
		$this->salida .= "      <td width=\"30%\">GRUPOS CARGOS</td>";
		$this->salida .= "      <td width=\"47%\">TIPOS CARGOS</td>";
		$this->salida .= "      <td width=\"5%\" >INFO</td>";
		$this->salida .= "      <td width=\"10%\">PLAN</td>";
		$this->salida .= "      <td width=\"8%\" >HOR. ADIC.</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor1']['pgruplanho']=$this->BuscarGruposPlaHonora($_SESSION['honora']['empresa'],
		$_SESSION['honor1']['gruplaproh']['tipodoprof'],$_SESSION['honor1']['gruplaproh']['documeprof']);
		$ciclo=sizeof($_SESSION['honor1']['pgruplanho']);
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
			$this->salida .= "".$_SESSION['honor1']['pgruplanho'][$i]['des1']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" $color>";
			$k=$i;
			while($_SESSION['honor1']['pgruplanho'][$i]['grupo_tipo_cargo']==$_SESSION['honor1']['pgruplanho'][$k]['grupo_tipo_cargo'])
			{
				$this->salida .= "  <tr>";
				$this->salida .= "  <td height=\"30\" width=\"35%\">";
				$this->salida .= "".$_SESSION['honor1']['pgruplanho'][$k]['des2']."";
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$k++;
			}
			$this->salida .= "      </table>";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" $color>";
			$k=$i;
			while($_SESSION['honor1']['pgruplanho'][$i]['grupo_tipo_cargo']==$_SESSION['honor1']['pgruplanho'][$k]['grupo_tipo_cargo'])
			{
				$this->salida .= "  <tr>";
				$this->salida .= "  <td height=\"30\" align=\"center\">";
				if($_SESSION['honor1']['pgruplanho'][$k]['honorarios']>0)
				{
					$this->salida .= "<a href=\"javascript:abrirVentanaClass('PARAMETROS','$ruta',450,200,0,this.form,'$k');\">
					<img src=\"".GetThemePath()."/images/honorarioscon.png\" border=\"0\"></a>";
				}
				else
				{
					$this->salida .= "<img src=\"".GetThemePath()."/images/honorariosconin.png\" border=\"0\">";
				}
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$k++;
			}
			$this->salida .= "      </table>";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" $color>";
			$k=$i;
			while($_SESSION['honor1']['pgruplanho'][$i]['grupo_tipo_cargo']==$_SESSION['honor1']['pgruplanho'][$k]['grupo_tipo_cargo'])
			{
				$this->salida .= "  <tr>";
				$this->salida .= "  <td height=\"30\" align=\"center\">";
				if($_SESSION['honor1']['pgruplanho'][$k]['honoraplan']>0)
				{
					$this->salida .= "SI";
				}
				else
				{
					$this->salida .= "NO";
				}
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$k++;
			}
			$this->salida .= "      </table>";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" $color>";
			$k=$i;
			while($_SESSION['honor1']['pgruplanho'][$i]['grupo_tipo_cargo']==$_SESSION['honor1']['pgruplanho'][$k]['grupo_tipo_cargo'])
			{
				$this->salida .= "  <tr>";
				$this->salida .= "  <td height=\"30\" align=\"center\">";
				$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','PorcPlanGruposProfPlaHonora',
				array('indporplanh'=>$k)) ."\"><img src=\"".GetThemePath()."/images/honorarios.png\" border=\"0\"></a>";
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$k++;
			}
			$this->salida .= "      </table>";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$i=$k;
		}
		if(empty($_SESSION['honor1']['pgruplanho']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"5\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN GRUPO TIPO CARGO'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\" width=\"100%\">";
		$accion=ModuloGetURL('app','Honorarios','user','ProfesionalGrupoPlaHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      CAMBIAR DE OPCIÓN SIN CAMBIAR DE PROFESIONAL";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','CambiarProfesionalHonora',array('de'=>3,'al'=>1)) ."\">HONORARIOS DE PROFESIONALES POR GRUPO</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','CambiarProfesionalHonora',array('de'=>3,'al'=>2)) ."\">HONORARIOS DE PROFESIONALES POR GRUPO Y SERVICIO</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		/*$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','CambiarProfesionalHonora',array('de'=>3,'al'=>3)) ."\">HONORARIOS DE PROFESIONALES POR GRUPO Y PLAN</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";*/
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','CambiarProfesionalHonora',array('de'=>3,'al'=>4)) ."\">HONORARIOS DE PROFESIONALES POR CARGO</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','CambiarProfesionalHonora',array('de'=>3,'al'=>5)) ."\">HONORARIOS DE PROFESIONALES POR CARGO Y SERVICIO</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','CambiarProfesionalHonora',array('de'=>3,'al'=>6)) ."\">HONORARIOS DE PROFESIONALES POR CARGO Y PLAN</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//
	function PorcPlanGruposProfPlaHonora()//
	{
		if($_SESSION['honor1']['pgrplporho']['grupo_tipo_cargo']==NULL)
		{
			$_SESSION['honor1']['pgrplporho']['grupo_tipo_cargo']=$_SESSION['honor1']['pgruplanho'][$_REQUEST['indporplanh']]['grupo_tipo_cargo'];
			$_SESSION['honor1']['pgrplporho']['tipo_cargo']=$_SESSION['honor1']['pgruplanho'][$_REQUEST['indporplanh']]['tipo_cargo'];
			$_SESSION['honor1']['pgrplporho']['des1']=$_SESSION['honor1']['pgruplanho'][$_REQUEST['indporplanh']]['des1'];
			$_SESSION['honor1']['pgrplporho']['des2']=$_SESSION['honor1']['pgruplanho'][$_REQUEST['indporplanh']]['des2'];
			UNSET($_SESSION['honor1']['pgruplanho']);
		}
		UNSET($_SESSION['honor1']['pgruplpoho']);
		UNSET($_SESSION['honor1']['pgrupladho']);
		UNSET($_SESSION['honor1']['pgruplhadh']);
		$this->salida  = ThemeAbrirTabla('HONORARIOS - PROFESIONAL POR GRUPOS Y PLANES - PORCENTAJES');
		$accion=ModuloGetURL('app','Honorarios','user','ValidarPorcPlanGruposProfPlaHonora',array('Of'=>$_REQUEST['Of'],
		'paso'=>$_REQUEST['paso'],'codigohono'=>$_REQUEST['codigohono'],'descrihono'=>$_REQUEST['descrihono']));
		$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','GruposPlaHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - PORCENTAJE</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL PROFESIONAL:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor1']['gruplaproh']['nombreprof']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">IDENTIFICACIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor1']['gruplaproh']['tipodoprof']."".' -- '."".$_SESSION['honor1']['gruplaproh']['documeprof']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">GRUPO TIPO CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor1']['pgrplporho']['des1']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">TIPO CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor1']['pgrplporho']['des2']."";
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
		$this->salida .= "      <td width=\"17%\">NÚMERO</td>";
		$this->salida .= "      <td width=\"60%\">PLANES</td>";
		$this->salida .= "      <td width=\"5%\" >ESTADO</td>";
		$this->salida .= "      <td width=\"10%\">PORCENTAJE</td>";
		$this->salida .= "      <td width=\"8%\" >HOR. ADIC.</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor1']['pgruplpoho']=$this->BuscarPorcPlanGruposProfPlaHonora($_SESSION['honora']['empresa'],
		$_SESSION['honor1']['pgrplporho']['grupo_tipo_cargo'],$_SESSION['honor1']['pgrplporho']['tipo_cargo']);
		$ciclo=sizeof($_SESSION['honor1']['pgruplpoho']);
		$j=0;
		for($i=0;$i<$ciclo;$i++)
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
			$this->salida .= "<td height=\"30\" align=\"center\">";
			$this->salida .= "".$_SESSION['honor1']['pgruplpoho'][$i]['num_contrato']."";
			$this->salida .= "</td>";
			$this->salida .= "<td height=\"30\">";
			$this->salida .= "".$_SESSION['honor1']['pgruplpoho'][$i]['plan_descripcion']."";
			$this->salida .= "</td>";
			$this->salida .= "<td height=\"30\" align=\"center\">";
			if($_SESSION['honor1']['pgruplpoho'][$i]['estado']==1)
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/pactivo.png\" border=\"0\">";
			}
			else
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/pinactivo.png\" border=\"0\">";
			}
			$this->salida .= "</td>";
			$this->salida .= "<td height=\"30\" align=\"center\">";
			if($_SESSION['honor1']['pgruplpoho'][$i]['honorario_grupo_id']<>NULL)
			{
				$_POST['porcentaje'.$i]=$_SESSION['honor1']['pgruplpoho'][$i]['porcentaje'];
			}
			else
			{
				$_POST['porcentaje'.$i]='';
			}
			$this->salida .= "<input type=\"text\" class=\"input-text\" name=\"porcentaje".$i."\" value=\"".$_POST['porcentaje'.$i]."\" maxlength=\"8\" size=\"8\">";
			$this->salida .= "".' %'."";
			$this->salida .= "</td>";
			$this->salida .= "<td height=\"30\" align=\"center\">";
			if($_SESSION['honor1']['pgruplpoho'][$i]['honorario_grupo_id']<>NULL)
			{
				$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','GruposProPlaAdicioHonora',
				array('indigruplh'=>$i)) ."\"><img src=\"".GetThemePath()."/images/honorarios.png\" border=\"0\"></a>";
			}
			else
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/honorariosin.png\" border=\"0\">";
			}
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		if(empty($_SESSION['honor1']['pgruplpoho']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"5\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN PLAN'";
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
		$accion=ModuloGetURL('app','Honorarios','user','GruposPlaHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var=$this->RetornarBarraPoPlProGruPlaHon();
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
		$accion=ModuloGetURL('app','Honorarios','user','PorcPlanGruposProfPlaHonora',
		array('codigohono'=>$_REQUEST['codigohono'],'descrihono'=>$_REQUEST['descrihono']));
		$this->salida .= "  <form name=\"contratacion2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">NÚMERO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigohono\" value=\"".$_REQUEST['codigohono']."\" maxlength=\"20\" size=\"20\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrihono\" value=\"".$_REQUEST['descrihono']."\" maxlength=\"60\" size=\"40\">";
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
		$accion=ModuloGetURL('app','Honorarios','user','PorcPlanGruposProfPlaHonora');
		$this->salida .= "  <form name=\"contratacion3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//
	function GruposProPlaAdicioHonora()//
	{
		if($_SESSION['honor1']['pgrupladho']['honorario_grupo_id']==NULL)
		{
			$_SESSION['honor1']['pgrupladho']['honorario_grupo_id']=$_SESSION['honor1']['pgruplpoho'][$_REQUEST['indigruplh']]['honorario_grupo_id'];
			$_SESSION['honor1']['pgrupladho']['num_contrato']=$_SESSION['honor1']['pgruplpoho'][$_REQUEST['indigruplh']]['num_contrato'];
			$_SESSION['honor1']['pgrupladho']['plan_descripcion']=$_SESSION['honor1']['pgruplpoho'][$_REQUEST['indigruplh']]['plan_descripcion'];
			$_SESSION['honor1']['pgrupladho']['porcentaje']=$_SESSION['honor1']['pgruplpoho'][$_REQUEST['indigruplh']]['porcentaje'];
			UNSET($_SESSION['honor1']['pgruplpoho']);
		}
		UNSET($_SESSION['honor1']['pgruplhadh']);
		$this->salida  = ThemeAbrirTabla('HONORARIOS - PROFESIONAL POR GRUPOS Y SERVICIOS - HORARIOS ADICIONALES');
		$accion=ModuloGetURL('app','Honorarios','user','ValidarGruposProPlaAdicioHonora');
		$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','PorcPlanGruposProfPlaHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - HORARIOS ADICIONALES - PORCENTAJE</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL PROFESIONAL:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor1']['gruplaproh']['nombreprof']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">IDENTIFICACIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor1']['gruplaproh']['tipodoprof']."".' -- '."".$_SESSION['honor1']['gruplaproh']['documeprof']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">GRUPO TIPO CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor1']['pgrplporho']['des1']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">TIPO CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor1']['pgrplporho']['des2']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor1']['pgrupladho']['num_contrato']."".' -- '."".$_SESSION['honor1']['pgrupladho']['plan_descripcion']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PORCENTAJE:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor1']['pgrupladho']['porcentaje']."".' %'."";
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
		$this->salida .= "      <td width=\"5%\" ></td>";
		$this->salida .= "      <td width=\"75%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"20%\">PORCENTAJE</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor1']['pgruplhadh']=$this->BuscarGruposProPlaAdicioHonora($_SESSION['honor1']['pgrupladho']['honorario_grupo_id']);
		$ciclo=sizeof($_SESSION['honor1']['pgruplhadh']);
		for($i=0;$i<$ciclo;$i++)
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
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "".($i+1)."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td height=\"30\">";
			$this->salida .= "".$_SESSION['honor1']['pgruplhadh'][$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td height=\"30\" align=\"center\">";
			if($_SESSION['honor1']['pgruplhadh'][$i]['porcentaje']<>NULL)
			{
				$_POST['porcenadic'.$i]=$_SESSION['honor1']['pgruplhadh'][$i]['porcentaje'];
			}
			else
			{
				$_POST['porcenadic'.$i]='';
			}
			$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"porcenadic".$i."\" value=\"".$_POST['porcenadic'.$i]."\" maxlength=\"8\" size=\"10\">";
			$this->salida .= "".' %'."";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['honor1']['pgruplhadh']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"3\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN HORARIO ADICIONAL'";
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
		$accion=ModuloGetURL('app','Honorarios','user','PorcPlanGruposProfPlaHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	/*PROFESIONAL CARGOS*/

	//Función que busca un profesional, para establecer sus honorarios
	function ProfesionalCargoHonora()//Busca el profesional para relacionar
	{
		UNSET($_SESSION['honor2']);
		$this->salida  = ThemeAbrirTabla('HONORARIOS - PROFESIONALES POR CARGOS');
		$accion=ModuloGetURL('app','Honorarios','user','ValidarProfesionalCargoHonora');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','PrincipalHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - SELECCIONAR UN PROFESIONAL</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
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
		$this->salida .= "      <td width=\"15%\">IDENTIFICACIÓN</td>";
		$this->salida .= "      <td width=\"63%\">NOMBRE DEL PROFESIONAL</td>";
		$this->salida .= "      <td width=\"6%\" >H. GRU.</td>";
		$this->salida .= "      <td width=\"6%\" >H. CAR.</td>";
		$this->salida .= "      <td width=\"5%\" >ESTADO</td>";
		$this->salida .= "      <td width=\"5%\" >ELEGIR</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor2']['prcargosho']=$this->BuscarProfesionalCargoHonora($_SESSION['honora']['empresa']);
		$ciclo=sizeof($_SESSION['honor2']['prcargosho']);
		for($i=0;$i<$ciclo;$i++)
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
			$this->salida .= "".$_SESSION['honor2']['prcargosho'][$i]['tipo_id_tercero']."".' -- '."".$_SESSION['honor2']['prcargosho'][$i]['tercero_id']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['honor2']['prcargosho'][$i]['nombre_tercero']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor2']['prcargosho'][$i]['honorarios']>0)
			{
				$this->salida .= "SI";
			}
			else
			{
				$this->salida .= "NO";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor2']['prcargosho'][$i]['honorarios1']>0)
			{
				$this->salida .= "SI";
			}
			else
			{
				$this->salida .= "NO";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor2']['prcargosho'][$i]['estado']==1)
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/activo.gif\" border=\"0\">";
			}
			else
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/inactivo.gif\" border=\"0\">";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor2']['prcargosho'][$i]['estado']==1)
			{
				$this->salida .= "<input type='radio' name='selprofeho' value=\"".$i."\">";
			}
			else
			{
				$this->salida .= "<input disabled='true' type='radio' name='selprofeho' value=\"".$i."\">";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['honor2']['prcargosho']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"5\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN PROFESIONAL'";
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
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"continuar\" value=\"CONTINUAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$accion=ModuloGetURL('app','Honorarios','user','PrincipalHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <td align=\"center\" width=\"50%\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"OPCIONES\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var=$this->RetornarBarraProCarHon();
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
		$accion=ModuloGetURL('app','Honorarios','user','ProfesionalCargoHonora',
		array('tipodohono'=>$_REQUEST['tipodohono'],'codigohono'=>$_REQUEST['codigohono'],'descrihono'=>$_REQUEST['descrihono']));
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">TIPO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <select name=\"tipodohono\" class=\"select\">";
		$this->salida .= "  <option value=\"\">----</option>";
		$terceros=$this->TercerosHonora();
		$ciclo1=sizeof($terceros);
		for($k=0;$k<$ciclo1;$k++)
		{
			if($terceros[$k]['tipo_id_tercero']==$_REQUEST['tipodohono'])
			{
				$this->salida .="<option value=\"".$terceros[$k]['tipo_id_tercero']."\" selected>".$terceros[$k]['descripcion']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$terceros[$k]['tipo_id_tercero']."\">".$terceros[$k]['descripcion']."</option>";
			}
		}
		$this->salida .= "  </select>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">DOCUMENTO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigohono\" value=\"".$_REQUEST['codigohono']."\" maxlength=\"32\" size=\"40\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">NOMBRE:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrihono\" value=\"".$_REQUEST['descrihono']."\" maxlength=\"60\" size=\"40\">";
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
		$accion=ModuloGetURL('app','Honorarios','user','ProfesionalCargoHonora');
		$this->salida .= "  <form name=\"forma3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que permite validar la asignación de los cargos, según los que esten con sw_honorarios en 1
	function CargosHonora()//Válida los cargos con honorarios
	{
		UNSET($_SESSION['honor2']['prcargosho']);
		UNSET($_SESSION['honor2']['pcargocaho']);
		UNSET($_SESSION['honor2']['pcargoadho']);
		UNSET($_SESSION['honor2']['pcargohadh']);
		$this->salida  = ThemeAbrirTabla('HONORARIOS - PROFESIONALES POR CARGOS - CUPS');
		$this->salida .= "<SCRIPT>";
		$this->salida .= "function abrirVentanaClass(nombre, url, ancho, altura, x, frm, indice){\n";
		$this->salida .= "var str = 'width=900,height=600,resizable=no,status=no,scrollbars=si,top=50,left=50';\n";
		$this->salida .= "var cas = 4;";
		$this->salida .= "var url2 = url+'?indice='+indice+'&caso='+cas;";
		$this->salida .= "var rems = window.open(url2, nombre, str);\n";
		$this->salida .= "if (rems != null) {\n";
		$this->salida .= "   if (rems.opener == null) {\n";
		$this->salida .= "       rems.opener = self;\n";
		$this->salida .= "   }\n";
		$this->salida .= "}\n";
		$this->salida .= "}\n";
		$this->salida .= "</SCRIPT>";
		$ruta='app_modules/Honorarios/Honoproadi.php';
		$accion=ModuloGetURL('app','Honorarios','user','ValidarCargosHonora',array('Of'=>$_REQUEST['Of'],
		'paso'=>$_REQUEST['paso'],'codigohono'=>$_REQUEST['codigohono'],'descrihono'=>$_REQUEST['descrihono']));
		$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','ProfesionalCargoHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - CARGOS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL PROFESIONAL:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor2']['cargosproh']['nombreprof']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">IDENTIFICACIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor2']['cargosproh']['tipodoprof']."".' -- '."".$_SESSION['honor2']['cargosproh']['documeprof']."";
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
		$this->salida .= "      <td width=\"7%\" >CARGO</td>";
		$this->salida .= "      <td width=\"60%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"10%\">% CARGO</td>";
		$this->salida .= "      <td width=\"10%\">% GRUPO</td>";
		$this->salida .= "      <td width=\"5%\" >INFO</td>";
		$this->salida .= "      <td width=\"8%\" >HOR. ADIC.</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor2']['pcargocaho']=$this->BuscarCargosHonora($_SESSION['honora']['empresa'],
		$_SESSION['honor2']['cargosproh']['tipodoprof'],$_SESSION['honor2']['cargosproh']['documeprof']);
		$ciclo=sizeof($_SESSION['honor2']['pcargocaho']);
		for($i=0;$i<$ciclo;$i++)
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
			$this->salida .= "".$_SESSION['honor2']['pcargocaho'][$i]['cargo']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['honor2']['pcargocaho'][$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor2']['pcargocaho'][$i]['honorario_cargo_id']<>NULL)
			{
				$_POST['porcentaje'.$i]=$_SESSION['honor2']['pcargocaho'][$i]['porcentaje'];
			}
			else
			{
				$_POST['porcentaje'.$i]='';
			}
			$this->salida .= "<input type=\"text\" class=\"input-text\" name=\"porcentaje".$i."\" value=\"".$_POST['porcentaje'.$i]."\" maxlength=\"8\" size=\"8\">";
			$this->salida .= "".' %'."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor2']['pcargocaho'][$i]['porcengrup']<>NULL)
			{
				$this->salida .= "".$_SESSION['honor2']['pcargocaho'][$i]['porcengrup']."";
				$this->salida .= "".' %'."";
			}
			else
			{
				$this->salida .= "NO";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor2']['pcargocaho'][$i]['honorarios']>0)
			{
				$this->salida .= "<a href=\"javascript:abrirVentanaClass('PARAMETROS','$ruta',450,200,0,this.form,'$i');\">
				<img src=\"".GetThemePath()."/images/honorarioscon.png\" border=\"0\"></a>";
			}
			else
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/honorariosconin.png\" border=\"0\">";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor2']['pcargocaho'][$i]['honorario_cargo_id']<>NULL)
			{
				$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','CargosAdicioHonora',
				array('indicecarh'=>$i)) ."\"><img src=\"".GetThemePath()."/images/honorarios.png\" border=\"0\"></a>";
			}
			else
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/honorariosin.png\" border=\"0\">";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['honor2']['pcargocaho']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"5\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN CARGO'";
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
		$accion=ModuloGetURL('app','Honorarios','user','ProfesionalCargoHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var=$this->RetornarBarraCarHon();
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
		$accion=ModuloGetURL('app','Honorarios','user','CargosHonora',
		array('codigohono'=>$_REQUEST['codigohono'],'descrihono'=>$_REQUEST['descrihono']));
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">CÓDIGO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigohono\" value=\"".$_REQUEST['codigohono']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrihono\" value=\"".$_REQUEST['descrihono']."\" maxlength=\"50\" size=\"35\">";
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
		$accion=ModuloGetURL('app','Honorarios','user','CargosHonora');
		$this->salida .= "  <form name=\"forma3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      CAMBIAR DE OPCIÓN SIN CAMBIAR DE PROFESIONAL";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','CambiarProfesionalHonora',array('de'=>4,'al'=>1)) ."\">HONORARIOS DE PROFESIONALES POR GRUPO</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','CambiarProfesionalHonora',array('de'=>4,'al'=>2)) ."\">HONORARIOS DE PROFESIONALES POR GRUPO Y SERVICIO</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','CambiarProfesionalHonora',array('de'=>4,'al'=>3)) ."\">HONORARIOS DE PROFESIONALES POR GRUPO Y PLAN</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		/*$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','CambiarProfesionalHonora',array('de'=>4,'al'=>4)) ."\">HONORARIOS DE PROFESIONALES POR CARGO</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";*/
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','CambiarProfesionalHonora',array('de'=>4,'al'=>5)) ."\">HONORARIOS DE PROFESIONALES POR CARGO Y SERVICIO</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','CambiarProfesionalHonora',array('de'=>4,'al'=>6)) ."\">HONORARIOS DE PROFESIONALES POR CARGO Y PLAN</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que permite generar los honorarios en jornadas de trabajo adicionales
	function CargosAdicioHonora()//Genera "excepciones" al porcentaje del honorario en otro horario
	{
		if($_SESSION['honor2']['pcargoadho']['honorario_cargo_id']==NULL)
		{
			$_SESSION['honor2']['pcargoadho']['honorario_cargo_id']=$_SESSION['honor2']['pcargocaho'][$_REQUEST['indicecarh']]['honorario_cargo_id'];
			$_SESSION['honor2']['pcargoadho']['cargo']=$_SESSION['honor2']['pcargocaho'][$_REQUEST['indicecarh']]['cargo'];
			$_SESSION['honor2']['pcargoadho']['descripcion']=$_SESSION['honor2']['pcargocaho'][$_REQUEST['indicecarh']]['descripcion'];
			$_SESSION['honor2']['pcargoadho']['porcentaje']=$_SESSION['honor2']['pcargocaho'][$_REQUEST['indicecarh']]['porcentaje'];
			$_SESSION['honor2']['pcargoadho']['porcengrup']=$_SESSION['honor2']['pcargocaho'][$_REQUEST['indicecarh']]['porcengrup'];
			UNSET($_SESSION['honor2']['pcargocaho']);
		}
		UNSET($_SESSION['honor2']['pcargohadh']);
		$this->salida  = ThemeAbrirTabla('HONORARIOS - PROFESIONALES POR CARGOS - HORARIOS ADICIONALES');
		$accion=ModuloGetURL('app','Honorarios','user','ValidarCargosAdicioHonora');
		$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','CargosHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - HORARIOS ADICIONALES - PORCENTAJE</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL PROFESIONAL:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor2']['cargosproh']['nombreprof']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">IDENTIFICACIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor2']['cargosproh']['tipodoprof']."".' -- '."".$_SESSION['honor2']['cargosproh']['documeprof']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor2']['pcargoadho']['cargo']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DESCRIPCIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor2']['pcargoadho']['descripcion']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PORCENTAJE CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor2']['pcargoadho']['porcentaje']."".' %'."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PORCENTAJE GRUPO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		if($_SESSION['honor2']['pcargoadho']['porcengrup']<>NULL)
		{
			$this->salida .= "".$_SESSION['honor2']['pcargoadho']['porcengrup']."".' %'."";
		}
		else
		{
			$this->salida .= "SIN PORCENTAJE";
		}
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
		$this->salida .= "      <td width=\"5%\" ></td>";
		$this->salida .= "      <td width=\"75%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"20%\">PORCENTAJE</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor2']['pcargohadh']=$this->BuscarCargosAdicioHonora($_SESSION['honor2']['pcargoadho']['honorario_cargo_id']);
		$ciclo=sizeof($_SESSION['honor2']['pcargohadh']);
		for($i=0;$i<$ciclo;$i++)
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
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "".($i+1)."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td height=\"30\">";
			$this->salida .= "".$_SESSION['honor2']['pcargohadh'][$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td height=\"30\" align=\"center\">";
			if($_SESSION['honor2']['pcargohadh'][$i]['porcentaje']<>NULL)
			{
				$_POST['porcenadic'.$i]=$_SESSION['honor2']['pcargohadh'][$i]['porcentaje'];
			}
			else
			{
				$_POST['porcenadic'.$i]='';
			}
			$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"porcenadic".$i."\" value=\"".$_POST['porcenadic'.$i]."\" maxlength=\"8\" size=\"10\">";
			$this->salida .= "".' %'."";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['honor2']['pcargohadh']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"3\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN HORARIO ADICIONAL'";
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
		$accion=ModuloGetURL('app','Honorarios','user','CargosHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	/*PROFESIONAL CARGOS Y SERVICIOS*/

	//Función que permite elegir un profesional, para asignar los porcentajes según los cargos y planes
	function ProfesionalCargoSerHonora()//Válida los profesionales, para crearles sus honorarios
	{
		UNSET($_SESSION['honor2']);
		$this->salida  = ThemeAbrirTabla('HONORARIOS - PROFESIONALES POR CARGOS Y SERVICIOS');
		$accion=ModuloGetURL('app','Honorarios','user','ValidarProfesionalCargoSerHonora');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','PrincipalHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - SELECCIONAR UN PROFESIONAL</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
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
		$this->salida .= "      <td width=\"15%\">IDENTIFICACIÓN</td>";
		$this->salida .= "      <td width=\"63%\">NOMBRE DEL PROFESIONAL</td>";
		$this->salida .= "      <td width=\"6%\" >H. GRU.</td>";
		$this->salida .= "      <td width=\"6%\" >H. CAR.</td>";
		$this->salida .= "      <td width=\"5%\" >ESTADO</td>";
		$this->salida .= "      <td width=\"5%\" >ELEGIR</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor2']['prcarserho']=$this->BuscarProfesionalCargoHonora($_SESSION['honora']['empresa']);
		$ciclo=sizeof($_SESSION['honor2']['prcarserho']);
		for($i=0;$i<$ciclo;$i++)
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
			$this->salida .= "".$_SESSION['honor2']['prcarserho'][$i]['tipo_id_tercero']."".' -- '."".$_SESSION['honor2']['prcarserho'][$i]['tercero_id']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['honor2']['prcarserho'][$i]['nombre_tercero']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor2']['prcarserho'][$i]['honorarios']>0)
			{
				$this->salida .= "SI";
			}
			else
			{
				$this->salida .= "NO";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor2']['prcarserho'][$i]['honorarios1']>0)
			{
				$this->salida .= "SI";
			}
			else
			{
				$this->salida .= "NO";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor2']['prcarserho'][$i]['estado']==1)
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/activo.gif\" border=\"0\">";
			}
			else
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/inactivo.gif\" border=\"0\">";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor2']['prcarserho'][$i]['estado']==1)
			{
				$this->salida .= "<input type='radio' name='selprofeho' value=\"".$i."\">";
			}
			else
			{
				$this->salida .= "<input disabled='true' type='radio' name='selprofeho' value=\"".$i."\">";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['honor2']['prcarserho']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"5\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN PROFESIONAL'";
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
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"continuar\" value=\"CONTINUAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$accion=ModuloGetURL('app','Honorarios','user','PrincipalHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <td align=\"center\" width=\"50%\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"OPCIONES\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var=$this->RetornarBarraProCarSerHon();
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
		$accion=ModuloGetURL('app','Honorarios','user','ProfesionalCargoSerHonora',
		array('tipodohono'=>$_REQUEST['tipodohono'],'codigohono'=>$_REQUEST['codigohono'],'descrihono'=>$_REQUEST['descrihono']));
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">TIPO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <select name=\"tipodohono\" class=\"select\">";
		$this->salida .= "  <option value=\"\">----</option>";
		$terceros=$this->TercerosHonora();
		$ciclo1=sizeof($terceros);
		for($k=0;$k<$ciclo1;$k++)
		{
			if($terceros[$k]['tipo_id_tercero']==$_REQUEST['tipodohono'])
			{
				$this->salida .="<option value=\"".$terceros[$k]['tipo_id_tercero']."\" selected>".$terceros[$k]['descripcion']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$terceros[$k]['tipo_id_tercero']."\">".$terceros[$k]['descripcion']."</option>";
			}
		}
		$this->salida .= "  </select>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">DOCUMENTO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigohono\" value=\"".$_REQUEST['codigohono']."\" maxlength=\"32\" size=\"40\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">NOMBRE:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrihono\" value=\"".$_REQUEST['descrihono']."\" maxlength=\"60\" size=\"40\">";
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
		$accion=ModuloGetURL('app','Honorarios','user','ProfesionalCargoSerHonora');
		$this->salida .= "  <form name=\"forma3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que permite validar la asignación de los cargos y servicios, según los cargos con sw_honorarios en 1
	function CargosSerHonora()//Válida los servicios con honorarios, por cargo
	{
		UNSET($_SESSION['honor2']['prcarserho']);
		UNSET($_SESSION['honor2']['pcarservho']);
		UNSET($_SESSION['honor2']['pcaseporho']);//La de los porcentajes
		UNSET($_SESSION['honor2']['servprcah1']);//LOS SERVICIOS
		UNSET($_SESSION['honor2']['pcarsepoho']);//Los porcentajes de los servicios
		$this->salida  = ThemeAbrirTabla('HONORARIOS - PROFESIONALES POR CARGOS Y SERVICIOS - CUPS');
		$this->salida .= "<SCRIPT>";
		$this->salida .= "function abrirVentanaClass(nombre, url, ancho, altura, x, frm, indice){\n";
		$this->salida .= "var str = 'width=900,height=600,resizable=no,status=no,scrollbars=si,top=50,left=50';\n";
		$this->salida .= "var cas = 5;";
		$this->salida .= "var url2 = url+'?indice='+indice+'&caso='+cas;";
		$this->salida .= "var rems = window.open(url2, nombre, str);\n";
		$this->salida .= "if (rems != null) {\n";
		$this->salida .= "   if (rems.opener == null) {\n";
		$this->salida .= "       rems.opener = self;\n";
		$this->salida .= "   }\n";
		$this->salida .= "}\n";
		$this->salida .= "}\n";
		$this->salida .= "</SCRIPT>";
		$ruta='app_modules/Honorarios/Honoproadi.php';
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','ProfesionalCargoSerHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - CARGOS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL PROFESIONAL:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor2']['carserproh']['nombreprof']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">IDENTIFICACIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor2']['carserproh']['tipodoprof']."".' -- '."".$_SESSION['honor2']['carserproh']['documeprof']."";
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
		$this->salida .= "      <td width=\"7%\" >CARGO</td>";
		$this->salida .= "      <td width=\"72%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"5%\" >INFO</td>";
		$this->salida .= "      <td width=\"8%\" >SERVICIO</td>";
		$this->salida .= "      <td width=\"8%\" >PORCENTAJE</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor2']['pcarservho']=$this->BuscarCargosSerHonora($_SESSION['honora']['empresa'],
		$_SESSION['honor2']['carserproh']['tipodoprof'],$_SESSION['honor2']['carserproh']['documeprof']);
		$ciclo=sizeof($_SESSION['honor2']['pcarservho']);
		for($i=0;$i<$ciclo;$i++)
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
			$this->salida .= "".$_SESSION['honor2']['pcarservho'][$i]['cargo']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['honor2']['pcarservho'][$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor2']['pcarservho'][$i]['honorarios']>0)
			{
				$this->salida .= "<a href=\"javascript:abrirVentanaClass('PARAMETROS','$ruta',450,200,0,this.form,'$i');\">
				<img src=\"".GetThemePath()."/images/honorarioscon.png\" border=\"0\"></a>";
			}
			else
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/honorariosconin.png\" border=\"0\">";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor2']['pcarservho'][$i]['honoservic']>0)
			{
				$this->salida .= "SI";
			}
			else
			{
				$this->salida .= "NO";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','PorcServCargosSerHonora',
			array('indporserh'=>$i)) ."\"><img src=\"".GetThemePath()."/images/honorarios.png\" border=\"0\"></a>";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['honor2']['pcarservho']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"5\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN CARGO'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\">";
		$accion=ModuloGetURL('app','Honorarios','user','ProfesionalCargoSerHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var=$this->RetornarBarraCarSerHon();
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
		$accion=ModuloGetURL('app','Honorarios','user','CargosSerHonora',
		array('codigohono'=>$_REQUEST['codigohono'],'descrihono'=>$_REQUEST['descrihono']));
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">CÓDIGO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigohono\" value=\"".$_REQUEST['codigohono']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrihono\" value=\"".$_REQUEST['descrihono']."\" maxlength=\"50\" size=\"35\">";
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
		$accion=ModuloGetURL('app','Honorarios','user','CargosSerHonora');
		$this->salida .= "  <form name=\"forma3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      CAMBIAR DE OPCIÓN SIN CAMBIAR DE PROFESIONAL";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','CambiarProfesionalHonora',array('de'=>5,'al'=>1)) ."\">HONORARIOS DE PROFESIONALES POR GRUPO</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','CambiarProfesionalHonora',array('de'=>5,'al'=>2)) ."\">HONORARIOS DE PROFESIONALES POR GRUPO Y SERVICIO</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','CambiarProfesionalHonora',array('de'=>5,'al'=>3)) ."\">HONORARIOS DE PROFESIONALES POR GRUPO Y PLAN</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','CambiarProfesionalHonora',array('de'=>5,'al'=>4)) ."\">HONORARIOS DE PROFESIONALES POR CARGO</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		/*$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','CambiarProfesionalHonora',array('de'=>5,'al'=>5)) ."\">HONORARIOS DE PROFESIONALES POR CARGO Y SERVICIO</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";*/
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','CambiarProfesionalHonora',array('de'=>5,'al'=>6)) ."\">HONORARIOS DE PROFESIONALES POR CARGO Y PLAN</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que captura los porcentajes para cada uno de los servicios, según el cargo
	function PorcServCargosSerHonora()//Válida los porcentajes para el cargo
	{
		if($_SESSION['honor2']['pcaseporho']['cargo']==NULL)
		{
			$_SESSION['honor2']['pcaseporho']['cargo']=$_SESSION['honor2']['pcarservho'][$_REQUEST['indporserh']]['cargo'];
			$_SESSION['honor2']['pcaseporho']['descripcion']=$_SESSION['honor2']['pcarservho'][$_REQUEST['indporserh']]['descripcion'];
			UNSET($_SESSION['honor2']['pcarservho']);
		}
		UNSET($_SESSION['honor2']['pcarsepoho']);
		UNSET($_SESSION['honor2']['servprcah1']);
		UNSET($_SESSION['honor2']['pcarseadho']);
		UNSET($_SESSION['honor2']['pcarsehadh']);
		$this->salida  = ThemeAbrirTabla('HONORARIOS - PROFESIONALES POR CARGOS Y SERVICIOS - PORCENTAJES');
		$accion=ModuloGetURL('app','Honorarios','user','ValidarPorcServCargosSerHonora');
		$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','CargosSerHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - PORCENTAJE</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL PROFESIONAL:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor2']['carserproh']['nombreprof']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">IDENTIFICACIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor2']['carserproh']['tipodoprof']."".' -- '."".$_SESSION['honor2']['carserproh']['documeprof']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor2']['pcaseporho']['cargo']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DESCRIPCIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor2']['pcaseporho']['descripcion']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		$this->salida .= "      <table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"70%\">SERVICIO</td>";
		$this->salida .= "      <td width=\"20%\">PORCENTAJE</td>";
		$this->salida .= "      <td width=\"10%\">HOR. ADIC.</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor2']['pcarsepoho']=$this->BuscarPorcServCargosSerHonora($_SESSION['honora']['empresa'],
		$_SESSION['honor2']['carserproh']['tipodoprof'],$_SESSION['honor2']['carserproh']['documeprof'],
		$_SESSION['honor2']['pcaseporho']['cargo']);
		$_SESSION['honor2']['servprcah1']=$this->BuscarServiciosHonora();
		$ciclo=sizeof($_SESSION['honor2']['servprcah1']);
		$i=0;
		for($s=0;$s<$ciclo;$s++)
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
			$this->salida .= "<td height=\"30\" align=\"center\">";
			$this->salida .= "".$_SESSION['honor2']['servprcah1'][$s]['descripcion']."";
			$this->salida .= "</td>";
			$this->salida .= "<td height=\"30\" align=\"center\">";
			if($_SESSION['honor2']['pcarsepoho'][$i]['servicio']==$_SESSION['honor2']['servprcah1'][$s]['servicio'])
			{
				$_POST['porcentaje'.$i.$s]=$_SESSION['honor2']['pcarsepoho'][$i]['porcentaje'];
			}
			else
			{
				$_POST['porcentaje'.$i.$s]='';
			}
			$this->salida .= "<input type=\"text\" class=\"input-text\" name=\"porcentaje".$i.$s."\" value=\"".$_POST['porcentaje'.$i.$s]."\" maxlength=\"8\" size=\"8\">";
			$this->salida .= "".' %'."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			if($_SESSION['honor2']['pcarsepoho'][$i]['servicio']==$_SESSION['honor2']['servprcah1'][$s]['servicio'])
			{
				$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','CargosSerAdicioHonora',
				array('indicarseh'=>$i,'indiservih'=>$s)) ."\"><img src=\"".GetThemePath()."/images/honorarios.png\" border=\"0\"></a>";
				$i++;
			}
			else
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/honorariosin.png\" border=\"0\">";
			}
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		if(empty($_SESSION['honor2']['servprcah1']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"3\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN SERVICIO'";
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
		$accion=ModuloGetURL('app','Honorarios','user','CargosSerHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que permite crear los horarios adicionales para los cargos por servicios
	function CargosSerAdicioHonora()//Trae los horarios adicionales para el cargo por servicio
	{
		if($_SESSION['honor2']['pcarseadho']['honorario_cargo_id']==NULL)
		{
			$_SESSION['honor2']['pcarseadho']['honorario_cargo_id']=$_SESSION['honor2']['pcarsepoho'][$_REQUEST['indicarseh']]['honorario_cargo_id'];
			$_SESSION['honor2']['pcarseadho']['porcentaje']=$_SESSION['honor2']['pcarsepoho'][$_REQUEST['indicarseh']]['porcentaje'];
			$_SESSION['honor2']['pcarseadho']['descripcion']=$_SESSION['honor2']['servprcah1'][$_REQUEST['indiservih']]['descripcion'];
			UNSET($_SESSION['honor2']['pcarsepoho']);
		}
		UNSET($_SESSION['honor2']['pcarsehadh']);
		$this->salida  = ThemeAbrirTabla('HONORARIOS - PROFESIONALES POR CARGOS Y SERVICIOS - HORARIOS ADICIONALES');
		$accion=ModuloGetURL('app','Honorarios','user','ValidarCargosSerAdicioHonora');
		$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','PorcServCargosSerHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - HORARIOS ADICIONALES - PORCENTAJE</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL PROFESIONAL:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor2']['carserproh']['nombreprof']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">IDENTIFICACIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor2']['carserproh']['tipodoprof']."".' -- '."".$_SESSION['honor2']['carserproh']['documeprof']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor2']['pcaseporho']['cargo']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DESCRIPCIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor2']['pcaseporho']['descripcion']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">SERVICIO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor2']['pcarseadho']['descripcion']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PORCENTAJE:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor2']['pcarseadho']['porcentaje']."".' %'."";
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
		$this->salida .= "      <td width=\"5%\" ></td>";
		$this->salida .= "      <td width=\"75%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"20%\">PORCENTAJE</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor2']['pcarsehadh']=$this->BuscarCargosSerAdicioHonora($_SESSION['honor2']['pcarseadho']['honorario_cargo_id']);
		$ciclo=sizeof($_SESSION['honor2']['pcarsehadh']);
		for($i=0;$i<$ciclo;$i++)
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
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "".($i+1)."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td height=\"30\">";
			$this->salida .= "".$_SESSION['honor2']['pcarsehadh'][$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td height=\"30\" align=\"center\">";
			if($_SESSION['honor2']['pcarsehadh'][$i]['porcentaje']<>NULL)
			{
				$_POST['porcenadic'.$i]=$_SESSION['honor2']['pcarsehadh'][$i]['porcentaje'];
			}
			else
			{
				$_POST['porcenadic'.$i]='';
			}
			$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"porcenadic".$i."\" value=\"".$_POST['porcenadic'.$i]."\" maxlength=\"8\" size=\"10\">";
			$this->salida .= "".' %'."";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['honor2']['pcarsehadh']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"3\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN HORARIO ADICIONAL'";
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
		$accion=ModuloGetURL('app','Honorarios','user','PorcServCargosSerHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	/*PROFESIONAL CARGOS Y PLANES*/

	//Función que permite elegir un profesional, para asignar los porcentajes según los grupos y planes
	function ProfesionalCargoPlaHonora()//Válida los profesionales, para crearles sus honorarios
	{
		UNSET($_SESSION['honor2']);
		$this->salida  = ThemeAbrirTabla('HONORARIOS - PROFESIONALES POR CARGOS Y PLANES');
		$accion=ModuloGetURL('app','Honorarios','user','ValidarProfesionalCargoPlaHonora');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','PrincipalHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - SELECCIONAR UN PROFESIONAL</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
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
		$this->salida .= "      <td width=\"15%\">IDENTIFICACIÓN</td>";
		$this->salida .= "      <td width=\"63%\">NOMBRE DEL PROFESIONAL</td>";
		$this->salida .= "      <td width=\"6%\" >H. GRU.</td>";
		$this->salida .= "      <td width=\"6%\" >H. CAR.</td>";
		$this->salida .= "      <td width=\"5%\" >ESTADO</td>";
		$this->salida .= "      <td width=\"5%\" >ELEGIR</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor2']['prcarplaho']=$this->BuscarProfesionalCargoHonora($_SESSION['honora']['empresa']);
		$ciclo=sizeof($_SESSION['honor2']['prcarplaho']);
		for($i=0;$i<$ciclo;$i++)
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
			$this->salida .= "".$_SESSION['honor2']['prcarplaho'][$i]['tipo_id_tercero']."".' -- '."".$_SESSION['honor2']['prcarplaho'][$i]['tercero_id']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['honor2']['prcarplaho'][$i]['nombre_tercero']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor2']['prcarplaho'][$i]['honorarios']>0)
			{
				$this->salida .= "SI";
			}
			else
			{
				$this->salida .= "NO";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor2']['prcarplaho'][$i]['honorarios1']>0)
			{
				$this->salida .= "SI";
			}
			else
			{
				$this->salida .= "NO";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor2']['prcarplaho'][$i]['estado']==1)
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/activo.gif\" border=\"0\">";
			}
			else
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/inactivo.gif\" border=\"0\">";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor2']['prcarplaho'][$i]['estado']==1)
			{
				$this->salida .= "<input type='radio' name='selprofeho' value=\"".$i."\">";
			}
			else
			{
				$this->salida .= "<input disabled='true' type='radio' name='selprofeho' value=\"".$i."\">";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['honor2']['prcarplaho']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"5\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN PROFESIONAL'";
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
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"continuar\" value=\"CONTINUAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$accion=ModuloGetURL('app','Honorarios','user','PrincipalHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <td align=\"center\" width=\"50%\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"OPCIONES\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var=$this->RetornarBarraProCarPlaHon();
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
		$accion=ModuloGetURL('app','Honorarios','user','ProfesionalCargoPlaHonora',
		array('tipodohono'=>$_REQUEST['tipodohono'],'codigohono'=>$_REQUEST['codigohono'],'descrihono'=>$_REQUEST['descrihono']));
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">TIPO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <select name=\"tipodohono\" class=\"select\">";
		$this->salida .= "  <option value=\"\">----</option>";
		$terceros=$this->TercerosHonora();
		$ciclo1=sizeof($terceros);
		for($k=0;$k<$ciclo1;$k++)
		{
			if($terceros[$k]['tipo_id_tercero']==$_REQUEST['tipodohono'])
			{
				$this->salida .="<option value=\"".$terceros[$k]['tipo_id_tercero']."\" selected>".$terceros[$k]['descripcion']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$terceros[$k]['tipo_id_tercero']."\">".$terceros[$k]['descripcion']."</option>";
			}
		}
		$this->salida .= "  </select>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">DOCUMENTO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigohono\" value=\"".$_REQUEST['codigohono']."\" maxlength=\"32\" size=\"40\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">NOMBRE:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrihono\" value=\"".$_REQUEST['descrihono']."\" maxlength=\"60\" size=\"40\">";
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
		$accion=ModuloGetURL('app','Honorarios','user','ProfesionalCargoPlaHonora');
		$this->salida .= "  <form name=\"forma3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//
	function CargosPlaHonora()//
	{
		UNSET($_SESSION['honor2']['prcarplaho']);
		UNSET($_SESSION['honor2']['pcarplanho']);
		UNSET($_SESSION['honor2']['pcaplporho']);
		UNSET($_SESSION['honor2']['pcarplpoho']);
		$this->salida  = ThemeAbrirTabla('HONORARIOS - PROFESIONALES POR CARGOS Y PLANES - CLASIFICACIÓN CARGOS - CUPS');
		$this->salida .= "<SCRIPT>";
		$this->salida .= "function abrirVentanaClass(nombre, url, ancho, altura, x, frm, indice){\n";
		$this->salida .= "var str = 'width=900,height=600,resizable=no,status=no,scrollbars=si,top=50,left=50';\n";
		$this->salida .= "var cas = 6;";
		$this->salida .= "var url2 = url+'?indice='+indice+'&caso='+cas;";
		$this->salida .= "var rems = window.open(url2, nombre, str);\n";
		$this->salida .= "if (rems != null) {\n";
		$this->salida .= "   if (rems.opener == null) {\n";
		$this->salida .= "       rems.opener = self;\n";
		$this->salida .= "   }\n";
		$this->salida .= "}\n";
		$this->salida .= "}\n";
		$this->salida .= "</SCRIPT>";
		$ruta='app_modules/Honorarios/Honoproadi.php';
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','ProfesionalCargoPlaHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - GRUPOS TIPOS CARGOS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL PROFESIONAL:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor2']['carplaproh']['nombreprof']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">IDENTIFICACIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor2']['carplaproh']['tipodoprof']."".' -- '."".$_SESSION['honor2']['carplaproh']['documeprof']."";
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
		$this->salida .= "      <td width=\"7%\" >CARGOS</td>";
		$this->salida .= "      <td width=\"72%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"5%\" >INFO</td>";
		$this->salida .= "      <td width=\"8%\" >PLAN</td>";
		$this->salida .= "      <td width=\"8%\" >HOR. ADIC.</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor2']['pcarplanho']=$this->BuscarCargosPlaHonora($_SESSION['honora']['empresa'],
		$_SESSION['honor2']['carplaproh']['tipodoprof'],$_SESSION['honor2']['carplaproh']['documeprof']);
		$ciclo=sizeof($_SESSION['honor2']['pcarplanho']);
		for($i=0;$i<$ciclo;$i++)
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
			$this->salida .= "".$_SESSION['honor2']['pcarplanho'][$i]['cargo']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['honor2']['pcarplanho'][$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor2']['pcarplanho'][$i]['honorarios']>0)
			{
				$this->salida .= "<a href=\"javascript:abrirVentanaClass('PARAMETROS','$ruta',450,200,0,this.form,'$i');\">
				<img src=\"".GetThemePath()."/images/honorarioscon.png\" border=\"0\"></a>";
			}
			else
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/honorariosconin.png\" border=\"0\">";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor2']['pcarplanho'][$i]['honoraplan']>0)
			{
				$this->salida .= "SI";
			}
			else
			{
				$this->salida .= "NO";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','PorcPlanCargosProfPlaHonora',
			array('indporplah'=>$i)) ."\"><img src=\"".GetThemePath()."/images/honorarios.png\" border=\"0\"></a>";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['honor2']['pcarplanho']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"5\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN GRUPO TIPO CARGO'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\" width=\"100%\">";
		$accion=ModuloGetURL('app','Honorarios','user','ProfesionalCargoPlaHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var=$this->RetornarBarraCarPlaHon();
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
		$accion=ModuloGetURL('app','Honorarios','user','CargosPlaHonora',
		array('codigohono'=>$_REQUEST['codigohono'],'descrihono'=>$_REQUEST['descrihono']));
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">CÓDIGO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigohono\" value=\"".$_REQUEST['codigohono']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrihono\" value=\"".$_REQUEST['descrihono']."\" maxlength=\"50\" size=\"35\">";
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
		$accion=ModuloGetURL('app','Honorarios','user','CargosPlaHonora');
		$this->salida .= "  <form name=\"forma3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      CAMBIAR DE OPCIÓN SIN CAMBIAR DE PROFESIONAL";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','CambiarProfesionalHonora',array('de'=>6,'al'=>1)) ."\">HONORARIOS DE PROFESIONALES POR GRUPO</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','CambiarProfesionalHonora',array('de'=>6,'al'=>2)) ."\">HONORARIOS DE PROFESIONALES POR GRUPO Y SERVICIO</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','CambiarProfesionalHonora',array('de'=>6,'al'=>3)) ."\">HONORARIOS DE PROFESIONALES POR GRUPO Y PLAN</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','CambiarProfesionalHonora',array('de'=>6,'al'=>4)) ."\">HONORARIOS DE PROFESIONALES POR CARGO</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','CambiarProfesionalHonora',array('de'=>6,'al'=>5)) ."\">HONORARIOS DE PROFESIONALES POR CARGO Y SERVICIO</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		/*$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Honorarios','user','CambiarProfesionalHonora',array('de'=>6,'al'=>6)) ."\">HONORARIOS DE PROFESIONALES POR CARGO Y PLAN</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";*/
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//
	function PorcPlanCargosProfPlaHonora()//
	{
		if($_SESSION['honor2']['pcaplporho']['cargo']==NULL)
		{
			$_SESSION['honor2']['pcaplporho']['cargo']=$_SESSION['honor2']['pcarplanho'][$_REQUEST['indporplah']]['cargo'];
			$_SESSION['honor2']['pcaplporho']['descripcion']=$_SESSION['honor2']['pcarplanho'][$_REQUEST['indporplah']]['descripcion'];
			UNSET($_SESSION['honor2']['pcarplanho']);
		}
		UNSET($_SESSION['honor2']['pcarplpoho']);
		UNSET($_SESSION['honor2']['pcarpladho']);
		UNSET($_SESSION['honor2']['pcarplhadh']);
		$this->salida  = ThemeAbrirTabla('HONORARIOS - PROFESIONAL POR CARGOS Y PLANES - PORCENTAJES');
		$accion=ModuloGetURL('app','Honorarios','user','ValidarPorcPlanCargosProfPlaHonora',array('Of'=>$_REQUEST['Of'],
		'paso'=>$_REQUEST['paso'],'codigohono'=>$_REQUEST['codigohono'],'descrihono'=>$_REQUEST['descrihono']));
		$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','CargosPlaHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - PORCENTAJE</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL PROFESIONAL:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor2']['carplaproh']['nombreprof']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">IDENTIFICACIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor2']['carplaproh']['tipodoprof']."".' -- '."".$_SESSION['honor2']['carplaproh']['documeprof']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor2']['pcaplporho']['cargo']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DESCRIPCIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor2']['pcaplporho']['descripcion']."";
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
		$this->salida .= "      <td width=\"17%\">NÚMERO</td>";
		$this->salida .= "      <td width=\"60%\">PLANES</td>";
		$this->salida .= "      <td width=\"5%\" >ESTADO</td>";
		$this->salida .= "      <td width=\"10%\">PORCENTAJE</td>";
		$this->salida .= "      <td width=\"8%\" >HOR. ADIC.</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor2']['pcarplpoho']=$this->BuscarPorcPlanCargosProfPlaHonora(
		$_SESSION['honora']['empresa'],$_SESSION['honor2']['pcaplporho']['cargo']);
		$ciclo=sizeof($_SESSION['honor2']['pcarplpoho']);
		$j=0;
		for($i=0;$i<$ciclo;$i++)
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
			$this->salida .= "<td height=\"30\" align=\"center\">";
			$this->salida .= "".$_SESSION['honor2']['pcarplpoho'][$i]['num_contrato']."";
			$this->salida .= "</td>";
			$this->salida .= "<td height=\"30\">";
			$this->salida .= "".$_SESSION['honor2']['pcarplpoho'][$i]['plan_descripcion']."";
			$this->salida .= "</td>";
			$this->salida .= "<td height=\"30\" align=\"center\">";
			if($_SESSION['honor2']['pcarplpoho'][$i]['estado']==1)
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/pactivo.png\" border=\"0\">";
			}
			else
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/pinactivo.png\" border=\"0\">";
			}
			$this->salida .= "</td>";
			$this->salida .= "<td height=\"30\" align=\"center\">";
			if($_SESSION['honor2']['pcarplpoho'][$i]['honorario_cargo_id']<>NULL)
			{
				$_POST['porcentaje'.$i]=$_SESSION['honor2']['pcarplpoho'][$i]['porcentaje'];
			}
			else
			{
				$_POST['porcentaje'.$i]='';
			}
			$this->salida .= "<input type=\"text\" class=\"input-text\" name=\"porcentaje".$i."\" value=\"".$_POST['porcentaje'.$i]."\" maxlength=\"8\" size=\"8\">";
			$this->salida .= "".' %'."";
			$this->salida .= "</td>";
			$this->salida .= "<td height=\"30\" align=\"center\">";
			if($_SESSION['honor2']['pcarplpoho'][$i]['honorario_cargo_id']<>NULL)
			{
				$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','CargosProPlaAdicioHonora',
				array('indigruplh'=>$i)) ."\"><img src=\"".GetThemePath()."/images/honorarios.png\" border=\"0\"></a>";
			}
			else
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/honorariosin.png\" border=\"0\">";
			}
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		if(empty($_SESSION['honor2']['pcarplpoho']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"5\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN PLAN'";
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
		$accion=ModuloGetURL('app','Honorarios','user','CargosPlaHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var=$this->RetornarBarraPoPlProCarPlaHon();
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
		$accion=ModuloGetURL('app','Honorarios','user','PorcPlanCargosProfPlaHonora',
		array('codigohono'=>$_REQUEST['codigohono'],'descrihono'=>$_REQUEST['descrihono']));
		$this->salida .= "  <form name=\"contratacion2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">NÚMERO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigohono\" value=\"".$_REQUEST['codigohono']."\" maxlength=\"20\" size=\"20\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrihono\" value=\"".$_REQUEST['descrihono']."\" maxlength=\"60\" size=\"40\">";
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
		$accion=ModuloGetURL('app','Honorarios','user','PorcPlanCargosProfPlaHonora');
		$this->salida .= "  <form name=\"contratacion3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//
	function CargosProPlaAdicioHonora()//
	{
		if($_SESSION['honor2']['pcarpladho']['honorario_cargo_id']==NULL)
		{
			$_SESSION['honor2']['pcarpladho']['honorario_cargo_id']=$_SESSION['honor2']['pcarplpoho'][$_REQUEST['indigruplh']]['honorario_cargo_id'];
			$_SESSION['honor2']['pcarpladho']['num_contrato']=$_SESSION['honor2']['pcarplpoho'][$_REQUEST['indigruplh']]['num_contrato'];
			$_SESSION['honor2']['pcarpladho']['plan_descripcion']=$_SESSION['honor2']['pcarplpoho'][$_REQUEST['indigruplh']]['plan_descripcion'];
			$_SESSION['honor2']['pcarpladho']['porcentaje']=$_SESSION['honor2']['pcarplpoho'][$_REQUEST['indigruplh']]['porcentaje'];
			UNSET($_SESSION['honor2']['pcarplpoho']);
		}
		UNSET($_SESSION['honor2']['pcarplhadh']);
		$this->salida  = ThemeAbrirTabla('HONORARIOS - PROFESIONAL POR CARGOS Y PLANES - HORARIOS ADICIONALES');
		$accion=ModuloGetURL('app','Honorarios','user','ValidarCargosProPlaAdicioHonora');
		$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','PorcPlanCargosProfPlaHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - HORARIOS ADICIONALES - PORCENTAJE</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL PROFESIONAL:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor2']['carplaproh']['nombreprof']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">IDENTIFICACIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor2']['carplaproh']['tipodoprof']."".' -- '."".$_SESSION['honor2']['carplaproh']['documeprof']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor2']['pcaplporho']['cargo']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DESCRIPCIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor2']['pcaplporho']['descripcion']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor2']['pcarpladho']['num_contrato']."".' -- '."".$_SESSION['honor2']['pcarpladho']['plan_descripcion']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PORCENTAJE:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor2']['pcarpladho']['porcentaje']."".' %'."";
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
		$this->salida .= "      <td width=\"5%\" ></td>";
		$this->salida .= "      <td width=\"75%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"20%\">PORCENTAJE</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor2']['pcarplhadh']=$this->BuscarCargosProPlaAdicioHonora($_SESSION['honor2']['pcarpladho']['honorario_cargo_id']);
		$ciclo=sizeof($_SESSION['honor2']['pcarplhadh']);
		for($i=0;$i<$ciclo;$i++)
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
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "".($i+1)."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td height=\"30\">";
			$this->salida .= "".$_SESSION['honor2']['pcarplhadh'][$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td height=\"30\" align=\"center\">";
			if($_SESSION['honor2']['pcarplhadh'][$i]['porcentaje']<>NULL)
			{
				$_POST['porcenadic'.$i]=$_SESSION['honor2']['pcarplhadh'][$i]['porcentaje'];
			}
			else
			{
				$_POST['porcenadic'.$i]='';
			}
			$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"porcenadic".$i."\" value=\"".$_POST['porcenadic'.$i]."\" maxlength=\"8\" size=\"10\">";
			$this->salida .= "".' %'."";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['honor2']['pcarplhadh']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"3\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN HORARIO ADICIONAL'";
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
		$accion=ModuloGetURL('app','Honorarios','user','PorcPlanCargosProfPlaHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	/*POOL GRUPOS*/

	//Función que busca un profesional, para establecer sus honorarios
	function PoolGrupoHonora()//Busca el profesional para relacionar
	{
		UNSET($_SESSION['honor3']);
		$this->salida  = ThemeAbrirTabla('HONORARIOS - POOL POR GRUPOS');
		$accion=ModuloGetURL('app','Honorarios','user','ValidarPoolGrupoHonora');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','PrincipalHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - SELECCIONAR UN POOL</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
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
		$this->salida .= "      <td width=\"80%\">NOMBRE DEL POOL</td>";
		$this->salida .= "      <td width=\"10%\">HONORARIOS</td>";
		$this->salida .= "      <td width=\"5%\" >ESTADO</td>";
		$this->salida .= "      <td width=\"5%\" >ELEGIR</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor3']['logruposho']=$this->BuscarPoolGrupoHonora($_SESSION['honora']['empresa']);
		$ciclo=sizeof($_SESSION['honor3']['logruposho']);
		for($i=0;$i<$ciclo;$i++)
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
			$this->salida .= "".$_SESSION['honor3']['logruposho'][$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor3']['logruposho'][$i]['honorarios']>0)
			{
				$this->salida .= "SI";
			}
			else
			{
				$this->salida .= "NO";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor3']['logruposho'][$i]['estado']==1)
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/activo.gif\" border=\"0\">";
			}
			else
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/inactivo.gif\" border=\"0\">";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor3']['logruposho'][$i]['estado']==1)
			{
				$this->salida .= "<input type='radio' name='selprofeho' value=\"".$i."\">";
			}
			else
			{
				$this->salida .= "<input disabled='true' type='radio' name='selprofeho' value=\"".$i."\">";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['honor3']['logruposho']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"4\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN POOL'";
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
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"continuar\" value=\"CONTINUAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$accion=ModuloGetURL('app','Honorarios','user','PrincipalHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <td align=\"center\" width=\"50%\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"OPCIONES\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var=$this->RetornarBarraPolGruHon();
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
		$accion=ModuloGetURL('app','Honorarios','user','PoolGrupoHonora',array('descrihono'=>$_REQUEST['descrihono']));
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">NOMBRE:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrihono\" value=\"".$_REQUEST['descrihono']."\" maxlength=\"60\" size=\"40\">";
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
		$accion=ModuloGetURL('app','Honorarios','user','PoolGrupoHonora');
		$this->salida .= "  <form name=\"forma3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que permite validar la asignación de los grupos tipo cargos, según los cargos con sw_honorarios en 1
	function GruposPoolHonora()//Válida los grupos tipo cargos que tengan cargos con honorarios
	{
		UNSET($_SESSION['honor3']['logruposho']);
		UNSET($_SESSION['honor3']['lgrupocaho']);
		UNSET($_SESSION['honor3']['lgrupoadho']);
		UNSET($_SESSION['honor3']['lgrupohadh']);
		$this->salida  = ThemeAbrirTabla('HONORARIOS - POOL POR GRUPOS - CLASIFICACIÓN GRUPOS TIPOS CARGOS - CUPS');
		$this->salida .= "<SCRIPT>";
		$this->salida .= "function abrirVentanaClass(nombre, url, ancho, altura, x, frm, indice){\n";
		$this->salida .= "var str = 'width=900,height=600,resizable=no,status=no,scrollbars=si,top=50,left=50';\n";
		$this->salida .= "var cas = 1;";
		$this->salida .= "var url2 = url+'?indice='+indice+'&caso='+cas;";
		$this->salida .= "var rems = window.open(url2, nombre, str);\n";
		$this->salida .= "if (rems != null) {\n";
		$this->salida .= "   if (rems.opener == null) {\n";
		$this->salida .= "       rems.opener = self;\n";
		$this->salida .= "   }\n";
		$this->salida .= "}\n";
		$this->salida .= "}\n";
		$this->salida .= "</SCRIPT>";
		$ruta='app_modules/Honorarios/Honopoladi.php';
		$accion=ModuloGetURL('app','Honorarios','user','ValidarGruposPoolHonora');
		$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','PoolGrupoHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - GRUPOS TIPOS CARGOS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL POOL:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor3']['grupospolh']['nombreprof']."";
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
		$this->salida .= "      <td width=\"30%\">GRUPOS CARGOS</td>";
		$this->salida .= "      <td width=\"47%\">TIPOS CARGOS</td>";
		$this->salida .= "      <td width=\"10%\">PORCENTAJE</td>";
		$this->salida .= "      <td width=\"5%\" >INFO</td>";
		$this->salida .= "      <td width=\"8%\" >HOR. ADIC.</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor3']['lgrupocaho']=$this->BuscarGruposPoolHonora(
		$_SESSION['honora']['empresa'],$_SESSION['honor3']['grupospolh']['poolidprof']);
		$ciclo=sizeof($_SESSION['honor3']['lgrupocaho']);
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
			$this->salida .= "".$_SESSION['honor3']['lgrupocaho'][$i]['des1']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" $color>";
			$k=$i;
			while($_SESSION['honor3']['lgrupocaho'][$i]['grupo_tipo_cargo']==$_SESSION['honor3']['lgrupocaho'][$k]['grupo_tipo_cargo'])
			{
				$this->salida .= "  <tr>";
				$this->salida .= "  <td height=\"30\">";
				$this->salida .= "".$_SESSION['honor3']['lgrupocaho'][$k]['des2']."";
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$k++;
			}
			$this->salida .= "      </table>";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" $color>";
			$k=$i;
			while($_SESSION['honor3']['lgrupocaho'][$i]['grupo_tipo_cargo']==$_SESSION['honor3']['lgrupocaho'][$k]['grupo_tipo_cargo'])
			{
				$this->salida .= "  <tr>";
				$this->salida .= "  <td height=\"30\" align=\"center\">";
				if($_SESSION['honor3']['lgrupocaho'][$k]['honorario_pool_grupo_id']<>NULL)
				{
					$_POST['porcentaje'.$k]=$_SESSION['honor3']['lgrupocaho'][$k]['porcentaje'];
				}
				else
				{
					$_POST['porcentaje'.$k]='';
				}
				$this->salida .= "<input type=\"text\" class=\"input-text\" name=\"porcentaje".$k."\" value=\"".$_POST['porcentaje'.$k]."\" maxlength=\"8\" size=\"8\">";
				$this->salida .= "".' %'."";
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$k++;
			}
			$this->salida .= "      </table>";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" $color>";
			$k=$i;
			while($_SESSION['honor3']['lgrupocaho'][$i]['grupo_tipo_cargo']==$_SESSION['honor3']['lgrupocaho'][$k]['grupo_tipo_cargo'])
			{
				$this->salida .= "  <tr>";
				$this->salida .= "  <td height=\"30\" align=\"center\">";
				if($_SESSION['honor3']['lgrupocaho'][$k]['honorarios']>0)
				{
					$this->salida .= "<a href=\"javascript:abrirVentanaClass('PARAMETROS','$ruta',450,200,0,this.form,'$k');\">
					<img src=\"".GetThemePath()."/images/honorarioscon.png\" border=\"0\"></a>";
				}
				else
				{
					$this->salida .= "<img src=\"".GetThemePath()."/images/honorariosconin.png\" border=\"0\">";
				}
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$k++;
			}
			$this->salida .= "      </table>";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" $color>";
			$k=$i;
			while($_SESSION['honor3']['lgrupocaho'][$i]['grupo_tipo_cargo']==$_SESSION['honor3']['lgrupocaho'][$k]['grupo_tipo_cargo'])
			{
				$this->salida .= "  <tr>";
				$this->salida .= "  <td height=\"30\" align=\"center\">";
				if($_SESSION['honor3']['lgrupocaho'][$k]['honorario_pool_grupo_id']<>NULL)
				{
					$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','PoolGruposAdicioHonora',
					array('indicegruh'=>$k)) ."\"><img src=\"".GetThemePath()."/images/honorarios.png\" border=\"0\"></a>";
				}
				else
				{
					$this->salida .= "<img src=\"".GetThemePath()."/images/honorariosin.png\" border=\"0\">";
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
		if(empty($_SESSION['honor3']['lgrupocaho']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"5\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN GRUPO TIPO CARGO'";
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
		$accion=ModuloGetURL('app','Honorarios','user','PoolGrupoHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que permite generar los honorarios en jornadas de trabajo adicionales
	function PoolGruposAdicioHonora()//Genera "excepciones" al porcentaje del honorario en otro horario
	{
		if($_SESSION['honor3']['lgrupoadho']['honorario_pool_grupo_id']==NULL)
		{
			$_SESSION['honor3']['lgrupoadho']['honorario_pool_grupo_id']=$_SESSION['honor3']['lgrupocaho'][$_REQUEST['indicegruh']]['honorario_pool_grupo_id'];
			$_SESSION['honor3']['lgrupoadho']['des1']=$_SESSION['honor3']['lgrupocaho'][$_REQUEST['indicegruh']]['des1'];
			$_SESSION['honor3']['lgrupoadho']['des2']=$_SESSION['honor3']['lgrupocaho'][$_REQUEST['indicegruh']]['des2'];
			$_SESSION['honor3']['lgrupoadho']['porcentaje']=$_SESSION['honor3']['lgrupocaho'][$_REQUEST['indicegruh']]['porcentaje'];
			UNSET($_SESSION['honor3']['lgrupocaho']);
		}
		UNSET($_SESSION['honor3']['lgrupohadh']);
		$this->salida  = ThemeAbrirTabla('HONORARIOS - POOL POR GRUPOS - HORARIOS ADICIONALES');
		$accion=ModuloGetURL('app','Honorarios','user','ValidarPoolGruposAdicioHonora');
		$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','GruposPoolHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - HORARIOS ADICIONALES - PORCENTAJE</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL POOL:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor3']['grupospolh']['nombreprof']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">GRUPO TIPO CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor3']['lgrupoadho']['des1']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">TIPO CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor3']['lgrupoadho']['des2']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PORCENTAJE:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor3']['lgrupoadho']['porcentaje']."".' %'."";
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
		$this->salida .= "      <td width=\"5%\" ></td>";
		$this->salida .= "      <td width=\"75%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"20%\">PORCENTAJE</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor3']['lgrupohadh']=$this->BuscarPoolGruposAdicioHonora($_SESSION['honor3']['lgrupoadho']['honorario_pool_grupo_id']);
		$ciclo=sizeof($_SESSION['honor3']['lgrupohadh']);
		for($i=0;$i<$ciclo;$i++)
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
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "".($i+1)."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td height=\"30\">";
			$this->salida .= "".$_SESSION['honor3']['lgrupohadh'][$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td height=\"30\" align=\"center\">";
			if($_SESSION['honor3']['lgrupohadh'][$i]['porcentaje']<>NULL)
			{
				$_POST['porcenadic'.$i]=$_SESSION['honor3']['lgrupohadh'][$i]['porcentaje'];
			}
			else
			{
				$_POST['porcenadic'.$i]='';
			}
			$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"porcenadic".$i."\" value=\"".$_POST['porcenadic'.$i]."\" maxlength=\"8\" size=\"10\">";
			$this->salida .= "".' %'."";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['honor3']['lgrupohadh']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"3\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN HORARIO ADICIONAL'";
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
		$accion=ModuloGetURL('app','Honorarios','user','GruposPoolHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	/*POOL GRUPOS Y SERVICIOS*/

	//Función que permite elegir un profesional, para asignar los porcentajes según los grupos y servicios
	function PoolGrupoSerHonora()//Válida los profesionales, para crearles sus honorarios
	{
		UNSET($_SESSION['honor3']);
		$this->salida  = ThemeAbrirTabla('HONORARIOS - POOL POR GRUPOS Y SERVICIOS');
		$accion=ModuloGetURL('app','Honorarios','user','ValidarPoolGrupoSerHonora');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','PrincipalHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - SELECCIONAR UN POOL</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
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
		$this->salida .= "      <td width=\"80%\">NOMBRE DEL POOL</td>";
		$this->salida .= "      <td width=\"10%\">HONORARIOS</td>";
		$this->salida .= "      <td width=\"5%\" >ESTADO</td>";
		$this->salida .= "      <td width=\"5%\" >ELEGIR</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor3']['logruserho']=$this->BuscarPoolGrupoHonora($_SESSION['honora']['empresa']);
		$ciclo=sizeof($_SESSION['honor3']['logruserho']);
		for($i=0;$i<$ciclo;$i++)
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
			$this->salida .= "".$_SESSION['honor3']['logruserho'][$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor3']['logruserho'][$i]['honorarios']>0)
			{
				$this->salida .= "SI";
			}
			else
			{
				$this->salida .= "NO";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor3']['logruserho'][$i]['estado']==1)
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/activo.gif\" border=\"0\">";
			}
			else
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/inactivo.gif\" border=\"0\">";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor3']['logruserho'][$i]['estado']==1)
			{
				$this->salida .= "<input type='radio' name='selprofeho' value=\"".$i."\">";
			}
			else
			{
				$this->salida .= "<input disabled='true' type='radio' name='selprofeho' value=\"".$i."\">";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['honor3']['logruserho']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"4\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN POOL'";
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
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"continuar\" value=\"CONTINUAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$accion=ModuloGetURL('app','Honorarios','user','PrincipalHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <td align=\"center\" width=\"50%\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"OPCIONES\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var=$this->RetornarBarraPolGruSerHon();
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
		$accion=ModuloGetURL('app','Honorarios','user','PoolGrupoSerHonora',array('descrihono'=>$_REQUEST['descrihono']));
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">NOMBRE:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrihono\" value=\"".$_REQUEST['descrihono']."\" maxlength=\"60\" size=\"40\">";
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
		$accion=ModuloGetURL('app','Honorarios','user','PoolGrupoSerHonora');
		$this->salida .= "  <form name=\"forma3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que permite validar la asignación de los grupos tipo cargos y servicios, según los cargos con sw_honorarios en 1
	function GruposPoolSerHonora()//Válida los servicios con honorarios, por grupo tipo cargo y tipo cargo
	{
		UNSET($_SESSION['honor3']['logruserho']);
		UNSET($_SESSION['honor3']['lgruservho']);
		UNSET($_SESSION['honor3']['servpogrh1']);//LOS SERVICIOS
		UNSET($_SESSION['honor3']['lgruseadho']);
		UNSET($_SESSION['honor3']['lgrusehadh']);
		$this->salida  = ThemeAbrirTabla('HONORARIOS - POOL POR GRUPOS Y SERVICIOS - CLASIFICACIÓN GRUPOS TIPOS CARGOS - CUPS');
		$this->salida .= "<SCRIPT>";
		$this->salida .= "function abrirVentanaClass(nombre, url, ancho, altura, x, frm, indice){\n";
		$this->salida .= "var str = 'width=900,height=600,resizable=no,status=no,scrollbars=si,top=50,left=50';\n";
		$this->salida .= "var cas = 2;";
		$this->salida .= "var url2 = url+'?indice='+indice+'&caso='+cas;";
		$this->salida .= "var rems = window.open(url2, nombre, str);\n";
		$this->salida .= "if (rems != null) {\n";
		$this->salida .= "   if (rems.opener == null) {\n";
		$this->salida .= "       rems.opener = self;\n";
		$this->salida .= "   }\n";
		$this->salida .= "}\n";
		$this->salida .= "}\n";
		$this->salida .= "</SCRIPT>";
		$ruta='app_modules/Honorarios/Honopoladi.php';
		$accion=ModuloGetURL('app','Honorarios','user','ValidarGruposPoolSerHonora');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','PoolGrupoSerHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - GRUPOS TIPOS CARGOS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL POOL:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor3']['gruserpolh']['nombreprof']."";
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
		$this->salida .= "      <td width=\"25%\">GRUPOS CARGOS</td>";
		$this->salida .= "      <td width=\"22%\">TIPOS CARGOS</td>";
		$this->salida .= "      <td width=\"30%\">SERVICIOS</td>";
		$this->salida .= "      <td width=\"10%\">PORCENTAJE</td>";
		$this->salida .= "      <td width=\"5%\" >INFO</td>";
		$this->salida .= "      <td width=\"8%\" >HOR. ADIC.</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor3']['lgruservho']=$this->BuscarGruposPoolSerHonora(
		$_SESSION['honora']['empresa'],$_SESSION['honor3']['gruserpolh']['poolidprof']);
		$_SESSION['honor3']['servpogrh1']=$this->BuscarServiciosHonora();
		$ciclo=sizeof($_SESSION['honor3']['lgruservho']);
		$ciclo1=sizeof($_SESSION['honor3']['servpogrh1']);
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
			$this->salida .= "".$_SESSION['honor3']['lgruservho'][$i]['des1']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td colspan=\"3\">";
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" $color>";
			$k=$i;
			while($_SESSION['honor3']['lgruservho'][$i]['grupo_tipo_cargo']==$_SESSION['honor3']['lgruservho'][$k]['grupo_tipo_cargo'])
			{
				$this->salida .= "  <tr>";
				$this->salida .= "  <td height=\"30\" width=\"35%\">";
				$this->salida .= "".$_SESSION['honor3']['lgruservho'][$k]['des2']."";
				$this->salida .= "  </td>";
				$this->salida .= "  <td height=\"30\" width=\"65%\">";
				$this->salida .= "      <table border=\"1\" width=\"100%\" align=\"center\" $color>";
				$l=$k;
				while($_SESSION['honor3']['lgruservho'][$k]['grupo_tipo_cargo']==$_SESSION['honor3']['lgruservho'][$l]['grupo_tipo_cargo']
				AND $_SESSION['honor3']['lgruservho'][$k]['tipo_cargo']==$_SESSION['honor3']['lgruservho'][$l]['tipo_cargo'])
				{
					for($s=0;$s<$ciclo1;$s++)
					{
						$sw=0;
						$this->salida .= "<tr>";
						$this->salida .= "<td height=\"30\" align=\"center\" width=\"75%\">";
						$this->salida .= "".$_SESSION['honor3']['servpogrh1'][$s]['descripcion']."";
						$this->salida .= "</td>";
						$this->salida .= "<td height=\"30\" align=\"center\" width=\"25%\">";
						if($_SESSION['honor3']['lgruservho'][$l]['servicio']==$_SESSION['honor3']['servpogrh1'][$s]['servicio'])
						{
							$_POST['porcentaje'.$k.$s]=$_SESSION['honor3']['lgruservho'][$l]['porcentaje'];
							$sw=1;
						}
						else if($_SESSION['honor3']['lgruservho'][$l]['servicio']<>$_SESSION['honor3']['servpogrh1'][$s]['servicio'])
						{
							$_POST['porcentaje'.$k.$s]='';
						}
						$this->salida .= "<input type=\"text\" class=\"input-text\" name=\"porcentaje".$k.$s."\" value=\"".$_POST['porcentaje'.$k.$s]."\" maxlength=\"8\" size=\"8\">";
						$this->salida .= "".' %'."";
						$this->salida .= "</td>";
						if($sw==1)
						{
							$l++;
						}
						if($_SESSION['honor3']['lgruservho'][$l]['servicio']==NULL AND $s==($ciclo1-1)
						AND $_SESSION['honor3']['lgruservho'][$k]['grupo_tipo_cargo']==$_SESSION['honor3']['lgruservho'][$l]['grupo_tipo_cargo']
						AND $_SESSION['honor3']['lgruservho'][$k]['tipo_cargo']==$_SESSION['honor3']['lgruservho'][$l]['tipo_cargo'])
						{
							$l++;
						}
						$this->salida .= "</tr>";
					}
				}
				$this->salida .= "      </table>";
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$k=$l;
			}
			$this->salida .= "      </table>";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" $color>";
			$k=$i;
			while($_SESSION['honor3']['lgruservho'][$i]['grupo_tipo_cargo']==$_SESSION['honor3']['lgruservho'][$k]['grupo_tipo_cargo'])
			{
				$l=$k;
				while($_SESSION['honor3']['lgruservho'][$k]['grupo_tipo_cargo']==$_SESSION['honor3']['lgruservho'][$l]['grupo_tipo_cargo']
				AND $_SESSION['honor3']['lgruservho'][$k]['tipo_cargo']==$_SESSION['honor3']['lgruservho'][$l]['tipo_cargo'])
				{
					for($s=0;$s<$ciclo1;$s++)
					{
						$sw=0;
						$this->salida .= "<tr>";
						$this->salida .= "<td height=\"30\" align=\"center\">";
						if($_SESSION['honor3']['lgruservho'][$l]['servicio']==$_SESSION['honor3']['servpogrh1'][$s]['servicio'])
						{
							if($_SESSION['honor3']['lgruservho'][$l]['honorarios']>0)
							{
								$this->salida .= "<a href=\"javascript:abrirVentanaClass('PARAMETROS','$ruta',450,200,0,this.form,'$l');\">
								<img src=\"".GetThemePath()."/images/honorarioscon.png\" border=\"0\"></a>";
							}
							else
							{
								$this->salida .= "<img src=\"".GetThemePath()."/images/honorariosconin.png\" border=\"0\">";
							}
							$sw=1;
						}
						else
						{
							if($_SESSION['honor3']['lgruservho'][$l]['honorarios']>0)
							{
								$this->salida .= "<a href=\"javascript:abrirVentanaClass('PARAMETROS','$ruta',450,200,0,this.form,'$l');\">
								<img src=\"".GetThemePath()."/images/honorarioscon.png\" border=\"0\"></a>";
							}
							else
							{
								$this->salida .= "<img src=\"".GetThemePath()."/images/honorariosconin.png\" border=\"0\">";
							}
						}
						$this->salida .= "</td>";
						if($sw==1)
						{
							$l++;
						}
						if($_SESSION['honor3']['lgruservho'][$l]['servicio']==NULL AND $s==($ciclo1-1)
						AND $_SESSION['honor3']['lgruservho'][$k]['grupo_tipo_cargo']==$_SESSION['honor3']['lgruservho'][$l]['grupo_tipo_cargo']
						AND $_SESSION['honor3']['lgruservho'][$k]['tipo_cargo']==$_SESSION['honor3']['lgruservho'][$l]['tipo_cargo'])
						{
							$l++;
						}
						$this->salida .= "</tr>";
					}
				}
				$k=$l;
			}
			$this->salida .= "      </table>";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" $color>";
			$k=$i;
			while($_SESSION['honor3']['lgruservho'][$i]['grupo_tipo_cargo']==$_SESSION['honor3']['lgruservho'][$k]['grupo_tipo_cargo'])
			{
				$l=$k;
				while($_SESSION['honor3']['lgruservho'][$k]['grupo_tipo_cargo']==$_SESSION['honor3']['lgruservho'][$l]['grupo_tipo_cargo']
				AND $_SESSION['honor3']['lgruservho'][$k]['tipo_cargo']==$_SESSION['honor3']['lgruservho'][$l]['tipo_cargo'])
				{
					for($s=0;$s<$ciclo1;$s++)
					{
						$sw=0;
						$this->salida .= "<tr>";
						$this->salida .= "<td height=\"30\" align=\"center\">";
						if($_SESSION['honor3']['lgruservho'][$l]['servicio']==$_SESSION['honor3']['servpogrh1'][$s]['servicio'])
						{
							$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','GruposPoolSerAdicioHonora',
							array('indigruseh'=>$l,'serdescrip'=>$_SESSION['honor3']['servpogrh1'][$s]['descripcion'])) ."\">
							<img src=\"".GetThemePath()."/images/honorarios.png\" border=\"0\"></a>";
							$sw=1;
						}
						else
						{
							$this->salida .= "<img src=\"".GetThemePath()."/images/honorariosin.png\" border=\"0\">";
						}
						$this->salida .= "</td>";
						if($sw==1)
						{
							$l++;
						}
						if($_SESSION['honor3']['lgruservho'][$l]['servicio']==NULL AND $s==($ciclo1-1)
						AND $_SESSION['honor3']['lgruservho'][$k]['grupo_tipo_cargo']==$_SESSION['honor3']['lgruservho'][$l]['grupo_tipo_cargo']
						AND $_SESSION['honor3']['lgruservho'][$k]['tipo_cargo']==$_SESSION['honor3']['lgruservho'][$l]['tipo_cargo'])
						{
							$l++;
						}
						$this->salida .= "</tr>";
					}
				}
				$k=$l;
			}
			$this->salida .= "      </table>";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$i=$k;
		}
		if(empty($_SESSION['honor3']['lgruservho']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"5\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN GRUPO TIPO CARGO'";
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
		$accion=ModuloGetURL('app','Honorarios','user','PoolGrupoSerHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que permite generar los honorarios en jornadas de trabajo adicionales
	function GruposPoolSerAdicioHonora()//Genera "excepciones" al porcentaje del honorario en otro horario
	{
		if($_SESSION['honor3']['lgruseadho']['honorario_grupo_id']==NULL)
		{
			$_SESSION['honor3']['lgruseadho']['honorario_grupo_id']=$_SESSION['honor3']['lgruservho'][$_REQUEST['indigruseh']]['honorario_grupo_id'];
			$_SESSION['honor3']['lgruseadho']['des1']=$_SESSION['honor3']['lgruservho'][$_REQUEST['indigruseh']]['des1'];
			$_SESSION['honor3']['lgruseadho']['des2']=$_SESSION['honor3']['lgruservho'][$_REQUEST['indigruseh']]['des2'];
			$_SESSION['honor3']['lgruseadho']['des3']=$_REQUEST['serdescrip'];
			$_SESSION['honor3']['lgruseadho']['porcentaje']=$_SESSION['honor3']['lgruservho'][$_REQUEST['indigruseh']]['porcentaje'];
			UNSET($_SESSION['honor3']['lgruservho']);
		}
		UNSET($_SESSION['honor3']['lgrusehadh']);
		$this->salida  = ThemeAbrirTabla('HONORARIOS - POOL POR GRUPOS Y SERVICIOS - HORARIOS ADICIONALES');
		$accion=ModuloGetURL('app','Honorarios','user','ValidarGruposPoolSerAdicioHonora');
		$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','GruposPoolSerHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - HORARIOS ADICIONALES - PORCENTAJE</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL POOL:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor3']['gruserpolh']['nombreprof']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">GRUPO TIPO CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor3']['lgruseadho']['des1']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">TIPO CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor3']['lgruseadho']['des2']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">SERVICIO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor3']['lgruseadho']['des3']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PORCENTAJE:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor3']['lgruseadho']['porcentaje']."".' %'."";
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
		$this->salida .= "      <td width=\"5%\" ></td>";
		$this->salida .= "      <td width=\"75%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"20%\">PORCENTAJE</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor3']['lgrusehadh']=$this->BuscarGruposPoolSerAdicioHonora($_SESSION['honor3']['lgruseadho']['honorario_grupo_id']);
		$ciclo=sizeof($_SESSION['honor3']['lgrusehadh']);
		for($i=0;$i<$ciclo;$i++)
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
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "".($i+1)."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td height=\"30\">";
			$this->salida .= "".$_SESSION['honor3']['lgrusehadh'][$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td height=\"30\" align=\"center\">";
			if($_SESSION['honor3']['lgrusehadh'][$i]['porcentaje']<>NULL)
			{
				$_POST['porcenadic'.$i]=$_SESSION['honor3']['lgrusehadh'][$i]['porcentaje'];
			}
			else
			{
				$_POST['porcenadic'.$i]='';
			}
			$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"porcenadic".$i."\" value=\"".$_POST['porcenadic'.$i]."\" maxlength=\"8\" size=\"10\">";
			$this->salida .= "".' %'."";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['honor3']['lgrusehadh']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"3\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN HORARIO ADICIONAL'";
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
		$accion=ModuloGetURL('app','Honorarios','user','GruposPoolSerHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	/*POOL CARGOS*/

	//Función que busca un profesional, para establecer sus honorarios
	function PoolCargoHonora()//Busca el profesional para relacionar
	{
		UNSET($_SESSION['honor4']);
		$this->salida  = ThemeAbrirTabla('HONORARIOS - POOL POR CARGOS');
		$accion=ModuloGetURL('app','Honorarios','user','ValidarPoolCargoHonora');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','PrincipalHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - SELECCIONAR UN POOL</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
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
		$this->salida .= "      <td width=\"78%\">NOMBRE DEL POOL</td>";
		$this->salida .= "      <td width=\"6%\" >H. GRU.</td>";
		$this->salida .= "      <td width=\"6%\" >H. CAR.</td>";
		$this->salida .= "      <td width=\"5%\" >ESTADO</td>";
		$this->salida .= "      <td width=\"5%\" >ELEGIR</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor4']['locargosho']=$this->BuscarPoolCargoHonora($_SESSION['honora']['empresa']);
		$ciclo=sizeof($_SESSION['honor4']['locargosho']);
		for($i=0;$i<$ciclo;$i++)
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
			$this->salida .= "".$_SESSION['honor4']['locargosho'][$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor4']['locargosho'][$i]['honorarios']>0)
			{
				$this->salida .= "SI";
			}
			else
			{
				$this->salida .= "NO";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor4']['locargosho'][$i]['honorarios1']>0)
			{
				$this->salida .= "SI";
			}
			else
			{
				$this->salida .= "NO";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor4']['locargosho'][$i]['estado']==1)
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/activo.gif\" border=\"0\">";
			}
			else
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/inactivo.gif\" border=\"0\">";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor4']['locargosho'][$i]['estado']==1)
			{
				$this->salida .= "<input type='radio' name='selprofeho' value=\"".$i."\">";
			}
			else
			{
				$this->salida .= "<input disabled='true' type='radio' name='selprofeho' value=\"".$i."\">";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['honor4']['locargosho']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"5\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN POOL'";
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
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"continuar\" value=\"CONTINUAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$accion=ModuloGetURL('app','Honorarios','user','PrincipalHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <td align=\"center\" width=\"50%\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"OPCIONES\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var=$this->RetornarBarraPolCarHon();
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
		$accion=ModuloGetURL('app','Honorarios','user','PoolCargoHonora',
		array('tipodohono'=>$_REQUEST['tipodohono'],'codigohono'=>$_REQUEST['codigohono'],'descrihono'=>$_REQUEST['descrihono']));
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">NOMBRE:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrihono\" value=\"".$_REQUEST['descrihono']."\" maxlength=\"60\" size=\"40\">";
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
		$accion=ModuloGetURL('app','Honorarios','user','PoolCargoHonora');
		$this->salida .= "  <form name=\"forma3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que permite validar la asignación de los cargos, según los que esten con sw_honorarios en 1
	function CargosPoolHonora()//Válida los cargos con honorarios
	{
		UNSET($_SESSION['honor4']['locargosho']);
		UNSET($_SESSION['honor4']['lcargocaho']);
		UNSET($_SESSION['honor4']['lcargoadho']);
		UNSET($_SESSION['honor4']['lcargohadh']);
		$this->salida  = ThemeAbrirTabla('HONORARIOS - POOL POR CARGOS - CUPS');
		$this->salida .= "<SCRIPT>";
		$this->salida .= "function abrirVentanaClass(nombre, url, ancho, altura, x, frm, indice){\n";
		$this->salida .= "var str = 'width=900,height=600,resizable=no,status=no,scrollbars=si,top=50,left=50';\n";
		$this->salida .= "var cas = 4;";
		$this->salida .= "var url2 = url+'?indice='+indice+'&caso='+cas;";
		$this->salida .= "var rems = window.open(url2, nombre, str);\n";
		$this->salida .= "if (rems != null) {\n";
		$this->salida .= "   if (rems.opener == null) {\n";
		$this->salida .= "       rems.opener = self;\n";
		$this->salida .= "   }\n";
		$this->salida .= "}\n";
		$this->salida .= "}\n";
		$this->salida .= "</SCRIPT>";
		$ruta='app_modules/Honorarios/Honopoladi.php';
		$accion=ModuloGetURL('app','Honorarios','user','ValidarCargosPoolHonora',array('Of'=>$_REQUEST['Of'],
		'paso'=>$_REQUEST['paso'],'codigohono'=>$_REQUEST['codigohono'],'descrihono'=>$_REQUEST['descrihono']));
		$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','PoolCargoHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - CARGOS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL POOL:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor4']['cargospolh']['nombreprof']."";
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
		$this->salida .= "      <td width=\"7%\" >CARGO</td>";
		$this->salida .= "      <td width=\"60%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"10%\">% CARGO</td>";
		$this->salida .= "      <td width=\"10%\">% GRUPO</td>";
		$this->salida .= "      <td width=\"5%\" >INFO</td>";
		$this->salida .= "      <td width=\"8%\" >HOR. ADIC.</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor4']['lcargocaho']=$this->BuscarCargosPoolHonora(
		$_SESSION['honora']['empresa'],$_SESSION['honor4']['cargospolh']['poolidprof']);
		$ciclo=sizeof($_SESSION['honor4']['lcargocaho']);
		for($i=0;$i<$ciclo;$i++)
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
			$this->salida .= "".$_SESSION['honor4']['lcargocaho'][$i]['cargo']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['honor4']['lcargocaho'][$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor4']['lcargocaho'][$i]['honorario_pool_cargo_id']<>NULL)
			{
				$_POST['porcentaje'.$i]=$_SESSION['honor4']['lcargocaho'][$i]['porcentaje'];
			}
			else
			{
				$_POST['porcentaje'.$i]='';
			}
			$this->salida .= "<input type=\"text\" class=\"input-text\" name=\"porcentaje".$i."\" value=\"".$_POST['porcentaje'.$i]."\" maxlength=\"8\" size=\"8\">";
			$this->salida .= "".' %'."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor4']['lcargocaho'][$i]['porcengrup']<>NULL)
			{
				$this->salida .= "".$_SESSION['honor4']['lcargocaho'][$i]['porcengrup']."";
				$this->salida .= "".' %'."";
			}
			else
			{
				$this->salida .= "NO";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor4']['lcargocaho'][$i]['honorarios']>0)
			{
				$this->salida .= "<a href=\"javascript:abrirVentanaClass('PARAMETROS','$ruta',450,200,0,this.form,'$i');\">
				<img src=\"".GetThemePath()."/images/honorarioscon.png\" border=\"0\"></a>";
			}
			else
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/honorariosconin.png\" border=\"0\">";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor4']['lcargocaho'][$i]['honorario_pool_cargo_id']<>NULL)
			{
				$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','CargosPoolAdicioHonora',
				array('indicecarh'=>$i)) ."\"><img src=\"".GetThemePath()."/images/honorarios.png\" border=\"0\"></a>";
			}
			else
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/honorariosin.png\" border=\"0\">";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['honor4']['lcargocaho']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"5\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN CARGO'";
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
		$accion=ModuloGetURL('app','Honorarios','user','PoolCargoHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var=$this->RetornarBarraCarPolHon();
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
		$accion=ModuloGetURL('app','Honorarios','user','CargosPoolHonora',
		array('codigohono'=>$_REQUEST['codigohono'],'descrihono'=>$_REQUEST['descrihono']));
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">CÓDIGO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigohono\" value=\"".$_REQUEST['codigohono']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrihono\" value=\"".$_REQUEST['descrihono']."\" maxlength=\"50\" size=\"35\">";
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
		$accion=ModuloGetURL('app','Honorarios','user','CargosPoolHonora');
		$this->salida .= "  <form name=\"forma3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que permite generar los honorarios en jornadas de trabajo adicionales
	function CargosPoolAdicioHonora()//Genera "excepciones" al porcentaje del honorario en otro horario
	{
		if($_SESSION['honor4']['lcargoadho']['honorario_pool_cargo_id']==NULL)
		{
			$_SESSION['honor4']['lcargoadho']['honorario_pool_cargo_id']=$_SESSION['honor4']['lcargocaho'][$_REQUEST['indicecarh']]['honorario_pool_cargo_id'];
			$_SESSION['honor4']['lcargoadho']['cargo']=$_SESSION['honor4']['lcargocaho'][$_REQUEST['indicecarh']]['cargo'];
			$_SESSION['honor4']['lcargoadho']['descripcion']=$_SESSION['honor4']['lcargocaho'][$_REQUEST['indicecarh']]['descripcion'];
			$_SESSION['honor4']['lcargoadho']['porcentaje']=$_SESSION['honor4']['lcargocaho'][$_REQUEST['indicecarh']]['porcentaje'];
			$_SESSION['honor4']['lcargoadho']['porcengrup']=$_SESSION['honor4']['lcargocaho'][$_REQUEST['indicecarh']]['porcengrup'];
			UNSET($_SESSION['honor4']['lcargocaho']);
		}
		UNSET($_SESSION['honor4']['lcargohadh']);
		$this->salida  = ThemeAbrirTabla('HONORARIOS - POOL POR CARGOS - HORARIOS ADICIONALES');
		$accion=ModuloGetURL('app','Honorarios','user','ValidarCargosPoolAdicioHonora');
		$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','CargosPoolHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - HORARIOS ADICIONALES - PORCENTAJE</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL POOL:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor4']['cargospolh']['nombreprof']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor4']['lcargoadho']['cargo']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DESCRIPCIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor4']['lcargoadho']['descripcion']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PORCENTAJE CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor4']['lcargoadho']['porcentaje']."".' %'."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PORCENTAJE GRUPO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		if($_SESSION['honor4']['lcargoadho']['porcengrup']<>NULL)
		{
			$this->salida .= "".$_SESSION['honor4']['lcargoadho']['porcengrup']."".' %'."";
		}
		else
		{
			$this->salida .= "SIN PORCENTAJE";
		}
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
		$this->salida .= "      <td width=\"5%\" ></td>";
		$this->salida .= "      <td width=\"75%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"20%\">PORCENTAJE</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor4']['lcargohadh']=$this->BuscarCargosPoolAdicioHonora($_SESSION['honor4']['lcargoadho']['honorario_pool_cargo_id']);
		$ciclo=sizeof($_SESSION['honor4']['lcargohadh']);
		for($i=0;$i<$ciclo;$i++)
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
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "".($i+1)."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td height=\"30\">";
			$this->salida .= "".$_SESSION['honor4']['lcargohadh'][$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td height=\"30\" align=\"center\">";
			if($_SESSION['honor4']['lcargohadh'][$i]['porcentaje']<>NULL)
			{
				$_POST['porcenadic'.$i]=$_SESSION['honor4']['lcargohadh'][$i]['porcentaje'];
			}
			else
			{
				$_POST['porcenadic'.$i]='';
			}
			$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"porcenadic".$i."\" value=\"".$_POST['porcenadic'.$i]."\" maxlength=\"8\" size=\"10\">";
			$this->salida .= "".' %'."";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['honor4']['lcargohadh']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"3\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN HORARIO ADICIONAL'";
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
		$accion=ModuloGetURL('app','Honorarios','user','CargosPoolHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	/*POOL CARGOS Y SERVICIOS*/

	//Función que permite elegir un profesional, para asignar los porcentajes según los cargos y planes
	function PoolCargoSerHonora()//Válida los profesionales, para crearles sus honorarios
	{
		UNSET($_SESSION['honor4']);
		$this->salida  = ThemeAbrirTabla('HONORARIOS - POOL POR CARGOS Y SERVICIOS');
		$accion=ModuloGetURL('app','Honorarios','user','ValidarPoolCargoSerHonora');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','PrincipalHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - SELECCIONAR UN POOL</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
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
		$this->salida .= "      <td width=\"78%\">NOMBRE DEL POOL</td>";
		$this->salida .= "      <td width=\"6%\" >H. GRU.</td>";
		$this->salida .= "      <td width=\"6%\" >H. CAR.</td>";
		$this->salida .= "      <td width=\"5%\" >ESTADO</td>";
		$this->salida .= "      <td width=\"5%\" >ELEGIR</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor4']['locarserho']=$this->BuscarPoolCargoHonora($_SESSION['honora']['empresa']);
		$ciclo=sizeof($_SESSION['honor4']['locarserho']);
		for($i=0;$i<$ciclo;$i++)
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
			$this->salida .= "".$_SESSION['honor4']['locarserho'][$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor4']['locarserho'][$i]['honorarios']>0)
			{
				$this->salida .= "SI";
			}
			else
			{
				$this->salida .= "NO";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor4']['locarserho'][$i]['honorarios1']>0)
			{
				$this->salida .= "SI";
			}
			else
			{
				$this->salida .= "NO";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor4']['locarserho'][$i]['estado']==1)
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/activo.gif\" border=\"0\">";
			}
			else
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/inactivo.gif\" border=\"0\">";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor4']['locarserho'][$i]['estado']==1)
			{
				$this->salida .= "<input type='radio' name='selprofeho' value=\"".$i."\">";
			}
			else
			{
				$this->salida .= "<input disabled='true' type='radio' name='selprofeho' value=\"".$i."\">";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['honor4']['locarserho']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"5\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN POOL'";
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
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"continuar\" value=\"CONTINUAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$accion=ModuloGetURL('app','Honorarios','user','PrincipalHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <td align=\"center\" width=\"50%\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"OPCIONES\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var=$this->RetornarBarraPolCarSerHon();
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
		$accion=ModuloGetURL('app','Honorarios','user','PoolCargoSerHonora',array('descrihono'=>$_REQUEST['descrihono']));
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">NOMBRE:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrihono\" value=\"".$_REQUEST['descrihono']."\" maxlength=\"60\" size=\"40\">";
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
		$accion=ModuloGetURL('app','Honorarios','user','PoolCargoSerHonora');
		$this->salida .= "  <form name=\"forma3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que permite validar la asignación de los cargos y servicios, según los cargos con sw_honorarios en 1
	function CargosPoolSerHonora()//Válida los servicios con honorarios, por cargo
	{
		UNSET($_SESSION['honor4']['locarserho']);
		UNSET($_SESSION['honor4']['lcarservho']);
		UNSET($_SESSION['honor4']['lcaseporho']);//La de los porcentajes
		UNSET($_SESSION['honor4']['servpocah1']);//LOS SERVICIOS
		UNSET($_SESSION['honor4']['lcarsepoho']);//Los porcentajes de los servicios
		$this->salida  = ThemeAbrirTabla('HONORARIOS - POOL POR CARGOS Y SERVICIOS - CUPS');
		$this->salida .= "<SCRIPT>";
		$this->salida .= "function abrirVentanaClass(nombre, url, ancho, altura, x, frm, indice){\n";
		$this->salida .= "var str = 'width=900,height=600,resizable=no,status=no,scrollbars=si,top=50,left=50';\n";
		$this->salida .= "var cas = 5;";
		$this->salida .= "var url2 = url+'?indice='+indice+'&caso='+cas;";
		$this->salida .= "var rems = window.open(url2, nombre, str);\n";
		$this->salida .= "if (rems != null) {\n";
		$this->salida .= "   if (rems.opener == null) {\n";
		$this->salida .= "       rems.opener = self;\n";
		$this->salida .= "   }\n";
		$this->salida .= "}\n";
		$this->salida .= "}\n";
		$this->salida .= "</SCRIPT>";
		$ruta='app_modules/Honorarios/Honopoladi.php';
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','PoolCargoSerHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - CARGOS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL POOL:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor4']['carserpolh']['nombreprof']."";
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
		$this->salida .= "      <td width=\"7%\" >CARGO</td>";
		$this->salida .= "      <td width=\"72%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"5%\" >INFO</td>";
		$this->salida .= "      <td width=\"8%\" >SERVICIO</td>";
		$this->salida .= "      <td width=\"8%\" >PORCENTAJE</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor4']['lcarservho']=$this->BuscarCargosPoolSerHonora(
		$_SESSION['honora']['empresa'],$_SESSION['honor4']['carserpolh']['poolidprof']);
		$ciclo=sizeof($_SESSION['honor4']['lcarservho']);
		for($i=0;$i<$ciclo;$i++)
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
			$this->salida .= "".$_SESSION['honor4']['lcarservho'][$i]['cargo']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['honor4']['lcarservho'][$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor4']['lcarservho'][$i]['honorarios']>0)
			{
				$this->salida .= "<a href=\"javascript:abrirVentanaClass('PARAMETROS','$ruta',450,200,0,this.form,'$i');\">
				<img src=\"".GetThemePath()."/images/honorarioscon.png\" border=\"0\"></a>";
			}
			else
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/honorariosconin.png\" border=\"0\">";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor4']['lcarservho'][$i]['honoservic']>0)
			{
				$this->salida .= "SI";
			}
			else
			{
				$this->salida .= "NO";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','PorcServCargosPoolSerHonora',
			array('indporserh'=>$i)) ."\"><img src=\"".GetThemePath()."/images/honorarios.png\" border=\"0\"></a>";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['honor4']['lcarservho']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"5\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN CARGO'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\">";
		$accion=ModuloGetURL('app','Honorarios','user','PoolCargoSerHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var=$this->RetornarBarraCarPolSerHon();
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
		$accion=ModuloGetURL('app','Honorarios','user','CargosPoolSerHonora',
		array('codigohono'=>$_REQUEST['codigohono'],'descrihono'=>$_REQUEST['descrihono']));
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">CÓDIGO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigohono\" value=\"".$_REQUEST['codigohono']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrihono\" value=\"".$_REQUEST['descrihono']."\" maxlength=\"50\" size=\"35\">";
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
		$accion=ModuloGetURL('app','Honorarios','user','CargosPoolSerHonora');
		$this->salida .= "  <form name=\"forma3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que captura los porcentajes para cada uno de los servicios, según el cargo
	function PorcServCargosPoolSerHonora()//Válida los porcentajes para el cargo
	{
		if($_SESSION['honor4']['lcaseporho']['cargo']==NULL)
		{
			$_SESSION['honor4']['lcaseporho']['cargo']=$_SESSION['honor4']['lcarservho'][$_REQUEST['indporserh']]['cargo'];
			$_SESSION['honor4']['lcaseporho']['descripcion']=$_SESSION['honor4']['lcarservho'][$_REQUEST['indporserh']]['descripcion'];
			UNSET($_SESSION['honor4']['lcarservho']);
		}
		UNSET($_SESSION['honor4']['lcarsepoho']);
		UNSET($_SESSION['honor4']['servpocah1']);
		UNSET($_SESSION['honor4']['lcarseadho']);
		UNSET($_SESSION['honor4']['lcarsehadh']);
		$this->salida  = ThemeAbrirTabla('HONORARIOS - POOL POR CARGOS Y SERVICIOS - PORCENTAJES');
		$accion=ModuloGetURL('app','Honorarios','user','ValidarPorcServCargosPoolSerHonora');
		$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','CargosPoolSerHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - PORCENTAJE</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL POOL:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor4']['carserpolh']['nombreprof']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor4']['lcaseporho']['cargo']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DESCRIPCIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor4']['lcaseporho']['descripcion']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		$this->salida .= "      <table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"70%\">SERVICIO</td>";
		$this->salida .= "      <td width=\"20%\">PORCENTAJE</td>";
		$this->salida .= "      <td width=\"10%\">HOR. ADIC.</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor4']['lcarsepoho']=$this->BuscarPorcServCargosPoolSerHonora($_SESSION['honora']['empresa'],
		$_SESSION['honor4']['carserpolh']['poolidprof'],$_SESSION['honor4']['lcaseporho']['cargo']);
		$_SESSION['honor4']['servpocah1']=$this->BuscarServiciosHonora();
		$ciclo=sizeof($_SESSION['honor4']['servpocah1']);
		$i=0;
		for($s=0;$s<$ciclo;$s++)
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
			$this->salida .= "<td height=\"30\" align=\"center\">";
			$this->salida .= "".$_SESSION['honor4']['servpocah1'][$s]['descripcion']."";
			$this->salida .= "</td>";
			$this->salida .= "<td height=\"30\" align=\"center\">";
			if($_SESSION['honor4']['lcarsepoho'][$i]['servicio']==$_SESSION['honor4']['servpocah1'][$s]['servicio'])
			{
				$_POST['porcentaje'.$i.$s]=$_SESSION['honor4']['lcarsepoho'][$i]['porcentaje'];
			}
			else
			{
				$_POST['porcentaje'.$i.$s]='';
			}
			$this->salida .= "<input type=\"text\" class=\"input-text\" name=\"porcentaje".$i.$s."\" value=\"".$_POST['porcentaje'.$i.$s]."\" maxlength=\"8\" size=\"8\">";
			$this->salida .= "".' %'."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			if($_SESSION['honor4']['lcarsepoho'][$i]['servicio']==$_SESSION['honor4']['servpocah1'][$s]['servicio'])
			{
				$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','CargosPoolSerAdicioHonora',
				array('indicarseh'=>$i,'indiservih'=>$s)) ."\"><img src=\"".GetThemePath()."/images/honorarios.png\" border=\"0\"></a>";
				$i++;
			}
			else
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/honorariosin.png\" border=\"0\">";
			}
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		if(empty($_SESSION['honor4']['servpocah1']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"3\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN SERVICIO'";
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
		$accion=ModuloGetURL('app','Honorarios','user','CargosPoolSerHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que permite crear los horarios adicionales para los cargos por servicios
	function CargosPoolSerAdicioHonora()//Trae los horarios adicionales para el cargo por servicio
	{
		if($_SESSION['honor4']['lcarseadho']['honorario_pool_cargo_id']==NULL)
		{
			$_SESSION['honor4']['lcarseadho']['honorario_pool_cargo_id']=$_SESSION['honor4']['lcarsepoho'][$_REQUEST['indicarseh']]['honorario_pool_cargo_id'];
			$_SESSION['honor4']['lcarseadho']['porcentaje']=$_SESSION['honor4']['lcarsepoho'][$_REQUEST['indicarseh']]['porcentaje'];
			$_SESSION['honor4']['lcarseadho']['descripcion']=$_SESSION['honor4']['servpocah1'][$_REQUEST['indiservih']]['descripcion'];
			UNSET($_SESSION['honor4']['lcarsepoho']);
		}
		UNSET($_SESSION['honor4']['lcarsehadh']);
		$this->salida  = ThemeAbrirTabla('HONORARIOS - POOL POR CARGOS Y SERVICIOS - HORARIOS ADICIONALES');
		$accion=ModuloGetURL('app','Honorarios','user','ValidarCargosPoolSerAdicioHonora');
		$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','PorcServCargosPoolSerHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - HORARIOS ADICIONALES - PORCENTAJE</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL PROFESIONAL:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor4']['carserpolh']['nombreprof']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor4']['lcaseporho']['cargo']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DESCRIPCIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor4']['lcaseporho']['descripcion']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">SERVICIO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor4']['lcarseadho']['descripcion']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PORCENTAJE:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor4']['lcarseadho']['porcentaje']."".' %'."";
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
		$this->salida .= "      <td width=\"5%\" ></td>";
		$this->salida .= "      <td width=\"75%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"20%\">PORCENTAJE</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor4']['lcarsehadh']=$this->BuscarCargosPoolSerAdicioHonora($_SESSION['honor4']['lcarseadho']['honorario_pool_cargo_id']);
		$ciclo=sizeof($_SESSION['honor4']['lcarsehadh']);
		for($i=0;$i<$ciclo;$i++)
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
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "".($i+1)."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td height=\"30\">";
			$this->salida .= "".$_SESSION['honor4']['lcarsehadh'][$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td height=\"30\" align=\"center\">";
			if($_SESSION['honor4']['lcarsehadh'][$i]['porcentaje']<>NULL)
			{
				$_POST['porcenadic'.$i]=$_SESSION['honor4']['lcarsehadh'][$i]['porcentaje'];
			}
			else
			{
				$_POST['porcenadic'.$i]='';
			}
			$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"porcenadic".$i."\" value=\"".$_POST['porcenadic'.$i]."\" maxlength=\"8\" size=\"10\">";
			$this->salida .= "".' %'."";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['honor4']['lcarsehadh']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"3\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN HORARIO ADICIONAL'";
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
		$accion=ModuloGetURL('app','Honorarios','user','PorcServCargosPoolSerHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	/*POOL GRUPOS Y PLANES*/

	//Función que permite elegir un profesional, para asignar los porcentajes según los grupos y planes
	function PoolGrupoPlaHonora()//Válida los profesionales, para crearles sus honorarios
	{
		UNSET($_SESSION['honor3']);
		$this->salida  = ThemeAbrirTabla('HONORARIOS - POOL POR GRUPOS Y PLANES');
		$accion=ModuloGetURL('app','Honorarios','user','ValidarPoolGrupoPlaHonora');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','PrincipalHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - SELECCIONAR UN POOL</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
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
		$this->salida .= "      <td width=\"80%\">NOMBRE DEL POOL</td>";
		$this->salida .= "      <td width=\"10%\">HONORARIOS</td>";
		$this->salida .= "      <td width=\"5%\" >ESTADO</td>";
		$this->salida .= "      <td width=\"5%\" >ELEGIR</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor3']['logruplaho']=$this->BuscarPoolGrupoHonora($_SESSION['honora']['empresa']);
		$ciclo=sizeof($_SESSION['honor3']['logruplaho']);
		for($i=0;$i<$ciclo;$i++)
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
			$this->salida .= "".$_SESSION['honor3']['logruplaho'][$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor3']['logruplaho'][$i]['honorarios']>0)
			{
				$this->salida .= "SI";
			}
			else
			{
				$this->salida .= "NO";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor3']['logruplaho'][$i]['estado']==1)
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/activo.gif\" border=\"0\">";
			}
			else
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/inactivo.gif\" border=\"0\">";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor3']['logruplaho'][$i]['estado']==1)
			{
				$this->salida .= "<input type='radio' name='selprofeho' value=\"".$i."\">";
			}
			else
			{
				$this->salida .= "<input disabled='true' type='radio' name='selprofeho' value=\"".$i."\">";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['honor3']['logruplaho']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"5\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN POOL'";
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
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"continuar\" value=\"CONTINUAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$accion=ModuloGetURL('app','Honorarios','user','PrincipalHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <td align=\"center\" width=\"50%\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"OPCIONES\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var=$this->RetornarBarraPolGruPlaHon();
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
		$accion=ModuloGetURL('app','Honorarios','user','PoolGrupoPlaHonora',array('descrihono'=>$_REQUEST['descrihono']));
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">NOMBRE:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrihono\" value=\"".$_REQUEST['descrihono']."\" maxlength=\"60\" size=\"40\">";
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
		$accion=ModuloGetURL('app','Honorarios','user','PoolGrupoPlaHonora');
		$this->salida .= "  <form name=\"forma3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//
	function GruposPoolPlaHonora()//
	{
		UNSET($_SESSION['honor3']['logruplaho']);
		UNSET($_SESSION['honor3']['lgruplanho']);
		UNSET($_SESSION['honor3']['lgrplporho']);
		UNSET($_SESSION['honor3']['lgruplpoho']);
		$this->salida  = ThemeAbrirTabla('HONORARIOS - POOL POR GRUPOS Y PLANES - CLASIFICACIÓN GRUPOS TIPOS CARGOS - CUPS');
		$this->salida .= "<SCRIPT>";
		$this->salida .= "function abrirVentanaClass(nombre, url, ancho, altura, x, frm, indice){\n";
		$this->salida .= "var str = 'width=900,height=600,resizable=no,status=no,scrollbars=si,top=50,left=50';\n";
		$this->salida .= "var cas = 3;";
		$this->salida .= "var url2 = url+'?indice='+indice+'&caso='+cas;";
		$this->salida .= "var rems = window.open(url2, nombre, str);\n";
		$this->salida .= "if (rems != null) {\n";
		$this->salida .= "   if (rems.opener == null) {\n";
		$this->salida .= "       rems.opener = self;\n";
		$this->salida .= "   }\n";
		$this->salida .= "}\n";
		$this->salida .= "}\n";
		$this->salida .= "</SCRIPT>";
		$ruta='app_modules/Honorarios/Honopoladi.php';
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','PoolGrupoPlaHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - GRUPOS TIPOS CARGOS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL POOL:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor3']['gruplapolh']['nombreprof']."";
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
		$this->salida .= "      <td width=\"30%\">GRUPOS CARGOS</td>";
		$this->salida .= "      <td width=\"47%\">TIPOS CARGOS</td>";
		$this->salida .= "      <td width=\"5%\" >INFO</td>";
		$this->salida .= "      <td width=\"10%\">PLAN</td>";
		$this->salida .= "      <td width=\"8%\" >HOR. ADIC.</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor3']['lgruplanho']=$this->BuscarGruposPoolPlaHonora(
		$_SESSION['honora']['empresa'],$_SESSION['honor3']['gruplapolh']['poolidprof']);
		$ciclo=sizeof($_SESSION['honor3']['lgruplanho']);
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
			$this->salida .= "".$_SESSION['honor3']['lgruplanho'][$i]['des1']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" $color>";
			$k=$i;
			while($_SESSION['honor3']['lgruplanho'][$i]['grupo_tipo_cargo']==$_SESSION['honor3']['lgruplanho'][$k]['grupo_tipo_cargo'])
			{
				$this->salida .= "  <tr>";
				$this->salida .= "  <td height=\"30\" width=\"35%\">";
				$this->salida .= "".$_SESSION['honor3']['lgruplanho'][$k]['des2']."";
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$k++;
			}
			$this->salida .= "      </table>";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" $color>";
			$k=$i;
			while($_SESSION['honor3']['lgruplanho'][$i]['grupo_tipo_cargo']==$_SESSION['honor3']['lgruplanho'][$k]['grupo_tipo_cargo'])
			{
				$this->salida .= "  <tr>";
				$this->salida .= "  <td height=\"30\" align=\"center\">";
				if($_SESSION['honor3']['lgruplanho'][$k]['honorarios']>0)
				{
					$this->salida .= "<a href=\"javascript:abrirVentanaClass('PARAMETROS','$ruta',450,200,0,this.form,'$k');\">
					<img src=\"".GetThemePath()."/images/honorarioscon.png\" border=\"0\"></a>";
				}
				else
				{
					$this->salida .= "<img src=\"".GetThemePath()."/images/honorariosconin.png\" border=\"0\">";
				}
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$k++;
			}
			$this->salida .= "      </table>";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" $color>";
			$k=$i;
			while($_SESSION['honor3']['lgruplanho'][$i]['grupo_tipo_cargo']==$_SESSION['honor3']['lgruplanho'][$k]['grupo_tipo_cargo'])
			{
				$this->salida .= "  <tr>";
				$this->salida .= "  <td height=\"30\" align=\"center\">";
				if($_SESSION['honor3']['lgruplanho'][$k]['honoraplan']>0)
				{
					$this->salida .= "SI";
				}
				else
				{
					$this->salida .= "NO";
				}
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$k++;
			}
			$this->salida .= "      </table>";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" $color>";
			$k=$i;
			while($_SESSION['honor3']['lgruplanho'][$i]['grupo_tipo_cargo']==$_SESSION['honor3']['lgruplanho'][$k]['grupo_tipo_cargo'])
			{
				$this->salida .= "  <tr>";
				$this->salida .= "  <td height=\"30\" align=\"center\">";
				$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','PorcPlanGruposPoolPlaHonora',
				array('indporplanh'=>$k)) ."\"><img src=\"".GetThemePath()."/images/honorarios.png\" border=\"0\"></a>";
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$k++;
			}
			$this->salida .= "      </table>";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$i=$k;
		}
		if(empty($_SESSION['honor3']['lgruplanho']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"5\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN GRUPO TIPO CARGO'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\" width=\"100%\">";
		$accion=ModuloGetURL('app','Honorarios','user','PoolGrupoPlaHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//
	function PorcPlanGruposPoolPlaHonora()//
	{
		if($_SESSION['honor3']['lgrplporho']['grupo_tipo_cargo']==NULL)
		{
			$_SESSION['honor3']['lgrplporho']['grupo_tipo_cargo']=$_SESSION['honor3']['lgruplanho'][$_REQUEST['indporplanh']]['grupo_tipo_cargo'];
			$_SESSION['honor3']['lgrplporho']['tipo_cargo']=$_SESSION['honor3']['lgruplanho'][$_REQUEST['indporplanh']]['tipo_cargo'];
			$_SESSION['honor3']['lgrplporho']['des1']=$_SESSION['honor3']['lgruplanho'][$_REQUEST['indporplanh']]['des1'];
			$_SESSION['honor3']['lgrplporho']['des2']=$_SESSION['honor3']['lgruplanho'][$_REQUEST['indporplanh']]['des2'];
			UNSET($_SESSION['honor3']['lgruplanho']);
		}
		UNSET($_SESSION['honor3']['lgruplpoho']);
		UNSET($_SESSION['honor3']['lgrupladho']);
		UNSET($_SESSION['honor3']['lgruplhadh']);
		$this->salida  = ThemeAbrirTabla('HONORARIOS - POOL POR GRUPOS Y PLANES - PORCENTAJES');
		$accion=ModuloGetURL('app','Honorarios','user','ValidarPorcPlanGruposPoolPlaHonora',array('Of'=>$_REQUEST['Of'],
		'paso'=>$_REQUEST['paso'],'codigohono'=>$_REQUEST['codigohono'],'descrihono'=>$_REQUEST['descrihono']));
		$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','GruposPoolPlaHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - PORCENTAJE</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL POOL:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor3']['gruplapolh']['nombreprof']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">GRUPO TIPO CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor3']['lgrplporho']['des1']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">TIPO CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor3']['lgrplporho']['des2']."";
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
		$this->salida .= "      <td width=\"17%\">NÚMERO</td>";
		$this->salida .= "      <td width=\"60%\">PLANES</td>";
		$this->salida .= "      <td width=\"5%\" >ESTADO</td>";
		$this->salida .= "      <td width=\"10%\">PORCENTAJE</td>";
		$this->salida .= "      <td width=\"8%\" >HOR. ADIC.</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor3']['lgruplpoho']=$this->BuscarPorcPlanGruposPoolPlaHonora($_SESSION['honora']['empresa'],
		$_SESSION['honor3']['lgrplporho']['grupo_tipo_cargo'],$_SESSION['honor3']['lgrplporho']['tipo_cargo']);
		$ciclo=sizeof($_SESSION['honor3']['lgruplpoho']);
		$j=0;
		for($i=0;$i<$ciclo;$i++)
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
			$this->salida .= "<td height=\"30\" align=\"center\">";
			$this->salida .= "".$_SESSION['honor3']['lgruplpoho'][$i]['num_contrato']."";
			$this->salida .= "</td>";
			$this->salida .= "<td height=\"30\">";
			$this->salida .= "".$_SESSION['honor3']['lgruplpoho'][$i]['plan_descripcion']."";
			$this->salida .= "</td>";
			$this->salida .= "<td height=\"30\" align=\"center\">";
			if($_SESSION['honor3']['lgruplpoho'][$i]['estado']==1)
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/pactivo.png\" border=\"0\">";
			}
			else
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/pinactivo.png\" border=\"0\">";
			}
			$this->salida .= "</td>";
			$this->salida .= "<td height=\"30\" align=\"center\">";
			if($_SESSION['honor3']['lgruplpoho'][$i]['honorario_pool_grupo_id']<>NULL)
			{
				$_POST['porcentaje'.$i]=$_SESSION['honor3']['lgruplpoho'][$i]['porcentaje'];
			}
			else
			{
				$_POST['porcentaje'.$i]='';
			}
			$this->salida .= "<input type=\"text\" class=\"input-text\" name=\"porcentaje".$i."\" value=\"".$_POST['porcentaje'.$i]."\" maxlength=\"8\" size=\"8\">";
			$this->salida .= "".' %'."";
			$this->salida .= "</td>";
			$this->salida .= "<td height=\"30\" align=\"center\">";
			if($_SESSION['honor3']['lgruplpoho'][$i]['honorario_pool_grupo_id']<>NULL)
			{
				$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','GruposPolPlaAdicioHonora',
				array('indigruplh'=>$i)) ."\"><img src=\"".GetThemePath()."/images/honorarios.png\" border=\"0\"></a>";
			}
			else
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/honorariosin.png\" border=\"0\">";
			}
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		if(empty($_SESSION['honor3']['lgruplpoho']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"5\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN PLAN'";
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
		$accion=ModuloGetURL('app','Honorarios','user','GruposPoolPlaHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var=$this->RetornarBarraPoPlPolGruPlaHon();
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
		$accion=ModuloGetURL('app','Honorarios','user','PorcPlanGruposPoolPlaHonora',
		array('codigohono'=>$_REQUEST['codigohono'],'descrihono'=>$_REQUEST['descrihono']));
		$this->salida .= "  <form name=\"contratacion2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">NÚMERO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigohono\" value=\"".$_REQUEST['codigohono']."\" maxlength=\"20\" size=\"20\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrihono\" value=\"".$_REQUEST['descrihono']."\" maxlength=\"60\" size=\"40\">";
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
		$accion=ModuloGetURL('app','Honorarios','user','PorcPlanGruposPoolPlaHonora');
		$this->salida .= "  <form name=\"contratacion3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//
	function GruposPolPlaAdicioHonora()//
	{
		if($_SESSION['honor3']['lgrupladho']['honorario_pool_grupo_id']==NULL)
		{
			$_SESSION['honor3']['lgrupladho']['honorario_pool_grupo_id']=$_SESSION['honor3']['lgruplpoho'][$_REQUEST['indigruplh']]['honorario_pool_grupo_id'];
			$_SESSION['honor3']['lgrupladho']['num_contrato']=$_SESSION['honor3']['lgruplpoho'][$_REQUEST['indigruplh']]['num_contrato'];
			$_SESSION['honor3']['lgrupladho']['plan_descripcion']=$_SESSION['honor3']['lgruplpoho'][$_REQUEST['indigruplh']]['plan_descripcion'];
			$_SESSION['honor3']['lgrupladho']['porcentaje']=$_SESSION['honor3']['lgruplpoho'][$_REQUEST['indigruplh']]['porcentaje'];
			UNSET($_SESSION['honor3']['lgruplpoho']);
		}
		UNSET($_SESSION['honor3']['lgruplhadh']);
		$this->salida  = ThemeAbrirTabla('HONORARIOS - POOL POR GRUPOS Y SERVICIOS - HORARIOS ADICIONALES');
		$accion=ModuloGetURL('app','Honorarios','user','ValidarGruposPolPlaAdicioHonora');
		$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','PorcPlanGruposPoolPlaHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - HORARIOS ADICIONALES - PORCENTAJE</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL PROFESIONAL:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor3']['gruplapolh']['nombreprof']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">GRUPO TIPO CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor3']['lgrplporho']['des1']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">TIPO CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor3']['lgrplporho']['des2']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor3']['lgrupladho']['num_contrato']."".' -- '."".$_SESSION['honor3']['lgrupladho']['plan_descripcion']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PORCENTAJE:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor3']['lgrupladho']['porcentaje']."".' %'."";
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
		$this->salida .= "      <td width=\"5%\" ></td>";
		$this->salida .= "      <td width=\"75%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"20%\">PORCENTAJE</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor3']['lgruplhadh']=$this->BuscarGruposPolPlaAdicioHonora($_SESSION['honor3']['lgrupladho']['honorario_pool_grupo_id']);
		$ciclo=sizeof($_SESSION['honor3']['lgruplhadh']);
		for($i=0;$i<$ciclo;$i++)
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
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "".($i+1)."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td height=\"30\">";
			$this->salida .= "".$_SESSION['honor3']['lgruplhadh'][$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td height=\"30\" align=\"center\">";
			if($_SESSION['honor3']['lgruplhadh'][$i]['porcentaje']<>NULL)
			{
				$_POST['porcenadic'.$i]=$_SESSION['honor3']['lgruplhadh'][$i]['porcentaje'];
			}
			else
			{
				$_POST['porcenadic'.$i]='';
			}
			$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"porcenadic".$i."\" value=\"".$_POST['porcenadic'.$i]."\" maxlength=\"8\" size=\"10\">";
			$this->salida .= "".' %'."";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['honor3']['lgruplhadh']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"3\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN HORARIO ADICIONAL'";
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
		$accion=ModuloGetURL('app','Honorarios','user','PorcPlanGruposPoolPlaHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	/*POOL CARGOS Y PLAN*/

	//Función que permite elegir un profesional, para asignar los porcentajes según los grupos y planes
	function PoolCargoPlaHonora()//Válida los profesionales, para crearles sus honorarios
	{
		UNSET($_SESSION['honor4']);
		$this->salida  = ThemeAbrirTabla('HONORARIOS - POOL POR CARGOS Y PLANES');
		$accion=ModuloGetURL('app','Honorarios','user','ValidarPoolCargoPlaHonora');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','PrincipalHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - SELECCIONAR UN POOL</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
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
		$this->salida .= "      <td width=\"78%\">NOMBRE DEL POOL</td>";
		$this->salida .= "      <td width=\"6%\" >H. GRU.</td>";
		$this->salida .= "      <td width=\"6%\" >H. CAR.</td>";
		$this->salida .= "      <td width=\"5%\" >ESTADO</td>";
		$this->salida .= "      <td width=\"5%\" >ELEGIR</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor4']['locarplaho']=$this->BuscarPoolCargoHonora($_SESSION['honora']['empresa']);
		$ciclo=sizeof($_SESSION['honor4']['locarplaho']);
		for($i=0;$i<$ciclo;$i++)
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
			$this->salida .= "".$_SESSION['honor4']['locarplaho'][$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor4']['locarplaho'][$i]['honorarios']>0)
			{
				$this->salida .= "SI";
			}
			else
			{
				$this->salida .= "NO";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor4']['locarplaho'][$i]['honorarios1']>0)
			{
				$this->salida .= "SI";
			}
			else
			{
				$this->salida .= "NO";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor4']['locarplaho'][$i]['estado']==1)
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/activo.gif\" border=\"0\">";
			}
			else
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/inactivo.gif\" border=\"0\">";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor4']['locarplaho'][$i]['estado']==1)
			{
				$this->salida .= "<input type='radio' name='selprofeho' value=\"".$i."\">";
			}
			else
			{
				$this->salida .= "<input disabled='true' type='radio' name='selprofeho' value=\"".$i."\">";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['honor4']['locarplaho']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"5\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN POOL'";
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
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"continuar\" value=\"CONTINUAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$accion=ModuloGetURL('app','Honorarios','user','PrincipalHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <td align=\"center\" width=\"50%\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"OPCIONES\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var=$this->RetornarBarraPolCarPlaHon();
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
		$accion=ModuloGetURL('app','Honorarios','user','PoolCargoPlaHonora',array('descrihono'=>$_REQUEST['descrihono']));
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">NOMBRE:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrihono\" value=\"".$_REQUEST['descrihono']."\" maxlength=\"60\" size=\"40\">";
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
		$accion=ModuloGetURL('app','Honorarios','user','PoolCargoPlaHonora');
		$this->salida .= "  <form name=\"forma3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//
	function CargosPoolPlaHonora()//
	{
		UNSET($_SESSION['honor4']['locarplaho']);
		UNSET($_SESSION['honor4']['lcarplanho']);
		UNSET($_SESSION['honor4']['lcaplporho']);
		UNSET($_SESSION['honor4']['lcarplpoho']);
		$this->salida  = ThemeAbrirTabla('HONORARIOS - POOL POR CARGOS Y PLANES - CLASIFICACIÓN CARGOS - CUPS');
		$this->salida .= "<SCRIPT>";
		$this->salida .= "function abrirVentanaClass(nombre, url, ancho, altura, x, frm, indice){\n";
		$this->salida .= "var str = 'width=900,height=600,resizable=no,status=no,scrollbars=si,top=50,left=50';\n";
		$this->salida .= "var cas = 6;";
		$this->salida .= "var url2 = url+'?indice='+indice+'&caso='+cas;";
		$this->salida .= "var rems = window.open(url2, nombre, str);\n";
		$this->salida .= "if (rems != null) {\n";
		$this->salida .= "   if (rems.opener == null) {\n";
		$this->salida .= "       rems.opener = self;\n";
		$this->salida .= "   }\n";
		$this->salida .= "}\n";
		$this->salida .= "}\n";
		$this->salida .= "</SCRIPT>";
		$ruta='app_modules/Honorarios/Honopoladi.php';
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','PoolCargoPlaHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - CARGOS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL POOL:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor4']['carplapolh']['nombreprof']."";
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
		$this->salida .= "      <td width=\"7%\" >CARGOS</td>";
		$this->salida .= "      <td width=\"72%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"5%\" >INFO</td>";
		$this->salida .= "      <td width=\"8%\" >PLAN</td>";
		$this->salida .= "      <td width=\"8%\" >HOR. ADIC.</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor4']['lcarplanho']=$this->BuscarCargosPoolPlaHonora(
		$_SESSION['honora']['empresa'],$_SESSION['honor4']['carplapolh']['poolidprof']);
		$ciclo=sizeof($_SESSION['honor4']['lcarplanho']);
		for($i=0;$i<$ciclo;$i++)
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
			$this->salida .= "".$_SESSION['honor4']['lcarplanho'][$i]['cargo']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['honor4']['lcarplanho'][$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor4']['lcarplanho'][$i]['honorarios']>0)
			{
				$this->salida .= "<a href=\"javascript:abrirVentanaClass('PARAMETROS','$ruta',450,200,0,this.form,'$i');\">
				<img src=\"".GetThemePath()."/images/honorarioscon.png\" border=\"0\"></a>";
			}
			else
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/honorariosconin.png\" border=\"0\">";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['honor4']['lcarplanho'][$i]['honoraplan']>0)
			{
				$this->salida .= "SI";
			}
			else
			{
				$this->salida .= "NO";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','PorcPlanCargosPoolPlaHonora',
			array('indporplah'=>$i)) ."\"><img src=\"".GetThemePath()."/images/honorarios.png\" border=\"0\"></a>";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['honor4']['lcarplanho']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"5\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN CARGO'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\" width=\"100%\">";
		$accion=ModuloGetURL('app','Honorarios','user','PoolCargoPlaHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var=$this->RetornarBarraCarPolPlaHon();
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
		$accion=ModuloGetURL('app','Honorarios','user','CargosPoolPlaHonora',
		array('codigohono'=>$_REQUEST['codigohono'],'descrihono'=>$_REQUEST['descrihono']));
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">CÓDIGO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigohono\" value=\"".$_REQUEST['codigohono']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrihono\" value=\"".$_REQUEST['descrihono']."\" maxlength=\"50\" size=\"35\">";
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
		$accion=ModuloGetURL('app','Honorarios','user','CargosPoolPlaHonora');
		$this->salida .= "  <form name=\"forma3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//
	function PorcPlanCargosPoolPlaHonora()//
	{
		if($_SESSION['honor4']['lcaplporho']['cargo']==NULL)
		{
			$_SESSION['honor4']['lcaplporho']['cargo']=$_SESSION['honor4']['lcarplanho'][$_REQUEST['indporplah']]['cargo'];
			$_SESSION['honor4']['lcaplporho']['descripcion']=$_SESSION['honor4']['lcarplanho'][$_REQUEST['indporplah']]['descripcion'];
			UNSET($_SESSION['honor4']['lcarplanho']);
		}
		UNSET($_SESSION['honor4']['lcarplpoho']);
		UNSET($_SESSION['honor4']['lcarpladho']);
		UNSET($_SESSION['honor4']['lcarplhadh']);
		$this->salida  = ThemeAbrirTabla('HONORARIOS - POOL POR CARGOS Y PLANES - PORCENTAJES');
		$accion=ModuloGetURL('app','Honorarios','user','ValidarPorcPlanCargosPoolPlaHonora',array('Of'=>$_REQUEST['Of'],
		'paso'=>$_REQUEST['paso'],'codigohono'=>$_REQUEST['codigohono'],'descrihono'=>$_REQUEST['descrihono']));
		$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','CargosPoolPlaHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - PORCENTAJE</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL POOL:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor4']['carplapolh']['nombreprof']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor4']['lcaplporho']['cargo']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DESCRIPCIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor4']['lcaplporho']['descripcion']."";
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
		$this->salida .= "      <td width=\"17%\">NÚMERO</td>";
		$this->salida .= "      <td width=\"60%\">PLANES</td>";
		$this->salida .= "      <td width=\"5%\" >ESTADO</td>";
		$this->salida .= "      <td width=\"10%\">PORCENTAJE</td>";
		$this->salida .= "      <td width=\"8%\" >HOR. ADIC.</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor4']['lcarplpoho']=$this->BuscarPorcPlanCargosPoolPlaHonora(
		$_SESSION['honora']['empresa'],$_SESSION['honor4']['lcaplporho']['cargo']);
		$ciclo=sizeof($_SESSION['honor4']['lcarplpoho']);
		$j=0;
		for($i=0;$i<$ciclo;$i++)
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
			$this->salida .= "<td height=\"30\" align=\"center\">";
			$this->salida .= "".$_SESSION['honor4']['lcarplpoho'][$i]['num_contrato']."";
			$this->salida .= "</td>";
			$this->salida .= "<td height=\"30\">";
			$this->salida .= "".$_SESSION['honor4']['lcarplpoho'][$i]['plan_descripcion']."";
			$this->salida .= "</td>";
			$this->salida .= "<td height=\"30\" align=\"center\">";
			if($_SESSION['honor4']['lcarplpoho'][$i]['estado']==1)
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/pactivo.png\" border=\"0\">";
			}
			else
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/pinactivo.png\" border=\"0\">";
			}
			$this->salida .= "</td>";
			$this->salida .= "<td height=\"30\" align=\"center\">";
			if($_SESSION['honor4']['lcarplpoho'][$i]['honorario_pool_cargo_id']<>NULL)
			{
				$_POST['porcentaje'.$i]=$_SESSION['honor4']['lcarplpoho'][$i]['porcentaje'];
			}
			else
			{
				$_POST['porcentaje'.$i]='';
			}
			$this->salida .= "<input type=\"text\" class=\"input-text\" name=\"porcentaje".$i."\" value=\"".$_POST['porcentaje'.$i]."\" maxlength=\"8\" size=\"8\">";
			$this->salida .= "".' %'."";
			$this->salida .= "</td>";
			$this->salida .= "<td height=\"30\" align=\"center\">";
			if($_SESSION['honor4']['lcarplpoho'][$i]['honorario_pool_cargo_id']<>NULL)
			{
				$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','CargosPolPlaAdicioHonora',
				array('indigruplh'=>$i)) ."\"><img src=\"".GetThemePath()."/images/honorarios.png\" border=\"0\"></a>";
			}
			else
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/honorariosin.png\" border=\"0\">";
			}
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		if(empty($_SESSION['honor4']['lcarplpoho']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"5\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN PLAN'";
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
		$accion=ModuloGetURL('app','Honorarios','user','CargosPoolPlaHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var=$this->RetornarBarraPoPlPolCarPlaHon();
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
		$accion=ModuloGetURL('app','Honorarios','user','PorcPlanCargosPoolPlaHonora',
		array('codigohono'=>$_REQUEST['codigohono'],'descrihono'=>$_REQUEST['descrihono']));
		$this->salida .= "  <form name=\"contratacion2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">NÚMERO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigohono\" value=\"".$_REQUEST['codigohono']."\" maxlength=\"20\" size=\"20\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrihono\" value=\"".$_REQUEST['descrihono']."\" maxlength=\"60\" size=\"40\">";
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
		$accion=ModuloGetURL('app','Honorarios','user','PorcPlanCargosPoolPlaHonora');
		$this->salida .= "  <form name=\"contratacion3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//
	function CargosPolPlaAdicioHonora()//
	{
		if($_SESSION['honor4']['lcarpladho']['honorario_pool_cargo_id']==NULL)
		{
			$_SESSION['honor4']['lcarpladho']['honorario_pool_cargo_id']=$_SESSION['honor4']['lcarplpoho'][$_REQUEST['indigruplh']]['honorario_pool_cargo_id'];
			$_SESSION['honor4']['lcarpladho']['num_contrato']=$_SESSION['honor4']['lcarplpoho'][$_REQUEST['indigruplh']]['num_contrato'];
			$_SESSION['honor4']['lcarpladho']['plan_descripcion']=$_SESSION['honor4']['lcarplpoho'][$_REQUEST['indigruplh']]['plan_descripcion'];
			$_SESSION['honor4']['lcarpladho']['porcentaje']=$_SESSION['honor4']['lcarplpoho'][$_REQUEST['indigruplh']]['porcentaje'];
			UNSET($_SESSION['honor4']['lcarplpoho']);
		}
		UNSET($_SESSION['honor4']['lcarplhadh']);
		$this->salida  = ThemeAbrirTabla('HONORARIOS - POOL POR CARGOS Y PLANES - HORARIOS ADICIONALES');
		$accion=ModuloGetURL('app','Honorarios','user','ValidarCargosPolPlaAdicioHonora');
		$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','PorcPlanCargosPoolPlaHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">HONORARIOS - HORARIOS ADICIONALES - PORCENTAJE</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL POOL:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor4']['carplapolh']['nombreprof']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor4']['lcaplporho']['cargo']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DESCRIPCIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor4']['lcaplporho']['descripcion']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor4']['lcarpladho']['num_contrato']."".' -- '."".$_SESSION['honor4']['lcarpladho']['plan_descripcion']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PORCENTAJE:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honor4']['lcarpladho']['porcentaje']."".' %'."";
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
		$this->salida .= "      <td width=\"5%\" ></td>";
		$this->salida .= "      <td width=\"75%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"20%\">PORCENTAJE</td>";
		$this->salida .= "      </tr>";
		$_SESSION['honor4']['lcarplhadh']=$this->BuscarCargosPolPlaAdicioHonora($_SESSION['honor4']['lcarpladho']['honorario_pool_cargo_id']);
		$ciclo=sizeof($_SESSION['honor4']['lcarplhadh']);
		for($i=0;$i<$ciclo;$i++)
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
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "".($i+1)."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td height=\"30\">";
			$this->salida .= "".$_SESSION['honor4']['lcarplhadh'][$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td height=\"30\" align=\"center\">";
			if($_SESSION['honor4']['lcarplhadh'][$i]['porcentaje']<>NULL)
			{
				$_POST['porcenadic'.$i]=$_SESSION['honor4']['lcarplhadh'][$i]['porcentaje'];
			}
			else
			{
				$_POST['porcenadic'.$i]='';
			}
			$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"porcenadic".$i."\" value=\"".$_POST['porcenadic'.$i]."\" maxlength=\"8\" size=\"10\">";
			$this->salida .= "".' %'."";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['honor4']['lcarplhadh']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"3\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN HORARIO ADICIONAL'";
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
		$accion=ModuloGetURL('app','Honorarios','user','PorcPlanCargosPoolPlaHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función para administrar los Pool de profesionales para los honorarios
	function AdministraPoolHonora()//Permite la administración de los pool
	{
		return true;
	}

	//
	function PruebasLiquidaHonora()//
	{
    IncludeLib("tarifario_cargos");
		$this->salida  = ThemeAbrirTabla('HONORARIOS - PRUEBAS DE LIQUIDACIÓN DE HONORARIOS');
		$accion=ModuloGetURL('app','Honorarios','user','ValidarPruebasLiquidaHonora');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','PrincipalHonora') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">PRUEBAS DE LIQUIDACIÓN</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['honora']['razonso']."";
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
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"20%\" class=\"".$this->SetStyle("cargoprueh")."\">CARGO - CUPS:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"80%\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"cargoprueh\" value=\"".$_POST['cargoprueh']."\" maxlength=\"10\" size=\"40\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"".$this->SetStyle("tipodprueh")."\">TIPO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <select name=\"tipodprueh\" class=\"select\">";
		$this->salida .= "      <option value=\"\">----</option>";
		$terceros=$this->TercerosHonora();
		$ciclo1=sizeof($terceros);
		for($k=0;$k<$ciclo1;$k++)
		{
			if($terceros[$k]['tipo_id_tercero']==$_POST['tipodprueh'])
			{
				$this->salida .="<option value=\"".$terceros[$k]['tipo_id_tercero']."\" selected>".$terceros[$k]['descripcion']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$terceros[$k]['tipo_id_tercero']."\">".$terceros[$k]['descripcion']."</option>";
			}
		}
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"".$this->SetStyle("identprueh")."\">DOCUMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"identprueh\" value=\"".$_POST['identprueh']."\" maxlength=\"32\" size=\"40\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"".$this->SetStyle("planeprueh")."\">PLAN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <select name=\"planeprueh\" class=\"select\">";
		$this->salida .= "      <option value=\"\">----</option>";
		$planes=$this->PlanesHonora($_SESSION['honora']['empresa']);
		$ciclo1=sizeof($planes);
		for($k=0;$k<$ciclo1;$k++)
		{
			if($planes[$k]['plan_id']==$_POST['planeprueh'])
			{
				$this->salida .="<option value=\"".$planes[$k]['plan_id']."\" selected>".$planes[$k]['num_contrato']."".' -- '."".$planes[$k]['plan_descripcion']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$planes[$k]['plan_id']."\">".$planes[$k]['num_contrato']."".' -- '."".$planes[$k]['plan_descripcion']."</option>";
			}
		}
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"".$this->SetStyle("serviprueh")."\">SERVICIO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <select name=\"serviprueh\" class=\"select\">";
		$this->salida .= "      <option value=\"\">----</option>";
		$servicios=$this->BuscarServiciosHonora();
		$ciclo1=sizeof($servicios);
		for($k=0;$k<$ciclo1;$k++)
		{
			if($servicios[$k]['servicio']==$_POST['serviprueh'])
			{
				$this->salida .="<option value=\"".$servicios[$k]['servicio']."\" selected>".$servicios[$k]['descripcion']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$servicios[$k]['servicio']."\">".$servicios[$k]['descripcion']."</option>";
			}
		}
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"".$this->SetStyle("fechaprueh")."\">FECHA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		if(empty($_POST['fechaprueh']))
		{
			$this->salida .= "<input type=\"text\" class=\"input-text\" name=\"fechaprueh\" value=\"".date ("d/m/Y")."\" maxlength=\"10\" size=\"10\">";
			$this->salida .= "".ReturnOpenCalendario('forma','fechaprueh','/')."";
		}
		else
		{
			$this->salida .= "<input type=\"text\" class=\"input-text\" name=\"fechaprueh\" value=\"".$_POST['fechaprueh']."\" maxlength=\"10\" size=\"10\">";
			$this->salida .= "".ReturnOpenCalendario('forma','fechaprueh','/')."";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"".$this->SetStyle("horario")."\">HORA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <select name=\"horario\" class=\"select\">";
		$this->salida .= "      <option value=\"-1\">--</option>";
		for($i=0;$i<24;$i++)
		{
			if($i<10)
			{
				if($_POST['horario']=="0$i")
				{
					$this->salida .="<option value=\"0$i\" selected>0$i</option>";
				}
				else
				{
					$this->salida .="<option value=\"0$i\">0$i</option>";
				}
			}
			else
			{
				if($_POST['horario']=="$i")
				{
					$this->salida .="<option value=\"$i\" selected>$i</option>";
				}
				else
				{
					$this->salida .="<option value=\"$i\">$i</option>";
				}
			}
		}
		$this->salida .= "      </select>";
		$this->salida .= " : ";
		$this->salida .= "      <select name=\"minutero\" class=\"select\">";
		$this->salida .= "      <option value=\"-1\">--</option>";
		for($i=0;$i<60;$i++)
		{
			if($i<10)
			{
				if($_POST['minutero']=="0$i")
				{
					$this->salida .="<option value=\"0$i\" selected>0$i</option>";
				}
				else
				{
					$this->salida .="<option value=\"0$i\">0$i</option>";
				}
			}
			else
			{
				if($_POST['minutero']=="$i")
				{
					$this->salida .="<option value=\"$i\" selected>$i</option>";
				}
				else
				{
					$this->salida .="<option value=\"$i\">$i</option>";
				}
			}
		}
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\" width=\"50%\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$accion=ModuloGetURL('app','Honorarios','user','PrincipalHonora');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <td align=\"center\" width=\"50%\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"OPCIONES\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		if($this->dos == 1)
		{
			$this->salida .= "  <br><table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= "  <tr><td>";
			$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
			$this->salida .= "      <tr>";
			$this->salida .= "      <td width=\"100%\" align=\"right\">";
			$this->salida .= "<a href=\"". ModuloGetURL('app','Honorarios','user','PrincipalHonora') ."\">";
			$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      </table>";
			$this->salida .= "  </td></tr>";
			$this->salida .= "  <tr><td>";
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"15%\">CARGO:";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"35%\">";
			$this->salida .= "      ".$_SESSION['honorp']['datos']['cargo']."";
			$this->salida .= "      </td>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"15%\">DESCRIPCIÓN:";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"35%\">";
			$this->salida .= "      ".$_SESSION['honorp']['datos']['descripcion']."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"15%\">GRUPO TIPO CARGO:";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"35%\">";
			$this->salida .= "      ".$_SESSION['honorp']['datos']['des1']."";
			$this->salida .= "      </td>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"15%\">TIPO CARGO:";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"35%\">";
			$this->salida .= "      ".$_SESSION['honorp']['datos']['des2']."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"15%\">NOMBRE:";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"35%\">";
			$this->salida .= "      ".$_SESSION['honorp']['datos']['nombre_tercero']."";
			$this->salida .= "      </td>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"15%\">NOMBRE DEL POOL:";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"35%\">";
			$this->salida .= "      ".$_SESSION['honorp']['datos']['descripcion_pool']."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      </table><br>";//$_SESSION['honorp']['cargoshono']
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "      <tr class=\"modulo_table_list_title\">";
			$this->salida .= "      <td colspan=\"5\" align=\"center\">";
			$this->salida .= "'CARGOS EQUIVALENTES SIN PLAN DE CONTRATACIÓN'";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr class=\"modulo_table_list_title\">";
			$this->salida .= "      <td width=\"5%\" ></td>";
			$this->salida .= "      <td width=\"15%\">TARIFARIO</td>";
			$this->salida .= "      <td width=\"10%\">CARGO</td>";
			$this->salida .= "      <td width=\"55%\">DESCRIPCIÓN</td>";
			$this->salida .= "      <td width=\"15%\">PRECIO</td>";
			$this->salida .= "      </tr>";
			$j=0;
			$ciclo=0;
			$ciclo=sizeof($_SESSION['honorp']['cargoshono']);
			for($i=0;$i<$ciclo;$i++)
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
				$this->salida .= "  <td align=\"center\">";
				$this->salida .= "".($i+1)."";
				$this->salida .= "  </td>";
				$this->salida .= "  <td align=\"center\">";
				$this->salida .= "".$_SESSION['honorp']['cargoshono'][$i]['destar']."";
				$this->salida .= "  </td>";
				$this->salida .= "  <td align=\"center\">";
				$this->salida .= "".$_SESSION['honorp']['cargoshono'][$i]['cargo']."";
				$this->salida .= "  </td>";
				$this->salida .= "  <td align=\"center\">";
				$this->salida .= "".$_SESSION['honorp']['cargoshono'][$i]['descripcion']."";
				$this->salida .= "  </td>";
				$this->salida .= "  <td align=\"center\">";
				$por=$_SESSION['honorp']['cargoshono'][$i]['precio'];
				$this->salida .= "".number_format(($por), 2, ',', '.')."";
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
			}
			if(empty($_SESSION['honorp']['cargoshono']))
			{
				$this->salida .= "<tr class=\"modulo_list_claro\">";
				$this->salida .= "<td colspan=\"5\" align=\"center\">";
				$this->salida .= "'NO SE ENCONTRÓ NINGÚN CARGO EQUIVALENTE'";
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
			}
			$this->salida .= "      </tr>";
			$this->salida .= "      </table><br>";
			$prioridad=1;
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "      <tr class=\"modulo_table_list_title\">";
			$this->salida .= "      <td colspan=\"6\" align=\"center\">";
			$this->salida .= "'PORCENTAJE POR CARGO Y PROFESIONAL'";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr class=\"modulo_table_list_title\">";
			$this->salida .= "      <td width=\"3%\" ></td>";
			$this->salida .= "      <td width=\"12%\">SERVICIO</td>";
			$this->salida .= "      <td width=\"50%\">PLAN</td>";//quitar aqui
			$this->salida .= "      <td width=\"10%\">PORCENTAJE</td>";
			$this->salida .= "      <td width=\"15%\">VALOR</td>";
			$this->salida .= "      <td width=\"10%\">COINCIDE</td>";
			$this->salida .= "      </tr>";
			$j=0;
			$ciclo=sizeof($_SESSION['honorp']['datoscargo']);
			$ciclo1=sizeof($servicios);
			$ciclo2=sizeof($planes);
			$ciclo3=sizeof($_SESSION['honorp']['cargoshono']);
			for($i=0;$i<$ciclo;$i++)
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
				for($l=0;$l<$ciclo3;$l++)
				{
					if($_SESSION['honorp']['datoscargo'][$i]['servicio']==$_POST['serviprueh']
					AND $_SESSION['honorp']['datoscargo'][$i]['plan_id']==$_POST['planeprueh']
					AND $prioridad==1)
					{
						$this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">";
						$prioridad=0;
					}
					else
					{
						$this->salida .= "  <tr $color>";
					}
					$this->salida .= "  <td align=\"center\">";
					$this->salida .= "".($l+1)."";
					$this->salida .= "  </td>";
					$this->salida .= "  <td align=\"center\">";
					for($k=0;$k<$ciclo1;$k++)
					{
						if($servicios[$k]['servicio']==$_SESSION['honorp']['datoscargo'][$i]['servicio'])
						{
							$this->salida .= "".$servicios[$k]['descripcion']."";
						}
					}
					$this->salida .= "  </td>";
					$this->salida .= "  <td align=\"center\">";
					for($k=0;$k<$ciclo2;$k++)
					{
						if($planes[$k]['plan_id']==$_SESSION['honorp']['datoscargo'][$i]['plan_id'])
						{
							$this->salida .= "".$planes[$k]['num_contrato']."".' -- '."".$planes[$k]['plan_descripcion']."";
						}
					}
					$this->salida .= "  </td>";
					$this->salida .= "  <td align=\"right\">";
					$this->salida .= "".$_SESSION['honorp']['datoscargo'][$i]['porcentaje']."".' %'."";
					$this->salida .= "  </td>";
					$this->salida .= "  <td align=\"right\">";

					$precio='';
					$precio=LiquidarCargo($_SESSION['honorp']['datoscargo'][$i]['plan_id'],$_SESSION['honorp']['cargoshono'][0]['tarifario'],$_SESSION['honorp']['cargoshono'][$i]['cargo'],1,0);
					$por=(($precio['precio_plan']*$_SESSION['honorp']['datoscargo'][$i]['porcentaje'])/100);
					$this->salida .= "".number_format(($por), 2, ',', '.')."";
					$this->salida .= "  </td>";
					$this->salida .= "  <td align=\"center\">";
					if($_SESSION['honorp']['datoscargo'][$i]['servicio']==$_POST['serviprueh']
					AND $_SESSION['honorp']['datoscargo'][$i]['plan_id']==$_POST['planeprueh'])
					{
						$this->salida .= "SI";
					}
					else
					{
						$this->salida .= "NO";
					}
					$this->salida .= "  </td>";
					$this->salida .= "  </tr>";
				}
			}
			if(empty($_SESSION['honorp']['datoscargo']))
			{
				$this->salida .= "<tr class=\"modulo_list_claro\">";
				$this->salida .= "<td colspan=\"6\" align=\"center\">";
				$this->salida .= "'NO SE ENCONTRÓ NINGÚN HONORARIO POR CARGO'";
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
			}
			$this->salida .= "      </tr>";
			$this->salida .= "      </table><br>";
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "      <tr class=\"modulo_table_list_title\">";
			$this->salida .= "      <td colspan=\"6\" align=\"center\">";
			$this->salida .= "'PORCENTAJE POR GRUPO Y PROFESIONAL'";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr class=\"modulo_table_list_title\">";
			$this->salida .= "      <td width=\"3%\" ></td>";
			$this->salida .= "      <td width=\"12%\">SERVICIO</td>";
			$this->salida .= "      <td width=\"50%\">PLAN</td>";//quitar aqui
			$this->salida .= "      <td width=\"10%\">PORCENTAJE</td>";
			$this->salida .= "      <td width=\"15%\">VALOR</td>";
			$this->salida .= "      <td width=\"10%\">COINCIDE</td>";
			$this->salida .= "      </tr>";
			$j=0;
			$ciclo=sizeof($_SESSION['honorp']['datosgrupo']);
// 			$ciclo1=sizeof($servicios);
// 			$ciclo2=sizeof($planes);
// 			$ciclo3=sizeof($_SESSION['honorp']['cargoshono']);
			for($i=0;$i<$ciclo;$i++)
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
				for($l=0;$l<$ciclo3;$l++)
				{
					if($_SESSION['honorp']['datosgrupo'][$i]['servicio']==$_POST['serviprueh']
					AND $_SESSION['honorp']['datosgrupo'][$i]['plan_id']==$_POST['planeprueh']
					AND $prioridad==1)
					{
						$this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">";
						$prioridad=0;
					}
					else
					{
						$this->salida .= "  <tr $color>";
					}
					$this->salida .= "  <td align=\"center\">";
					$this->salida .= "".($l+1)."";
					$this->salida .= "  </td>";
					$this->salida .= "  <td align=\"center\">";
					for($k=0;$k<$ciclo1;$k++)
					{
						if($servicios[$k]['servicio']==$_SESSION['honorp']['datosgrupo'][$i]['servicio'])
						{
							$this->salida .= "".$servicios[$k]['descripcion']."";
						}
					}
					$this->salida .= "  </td>";
					$this->salida .= "  <td align=\"center\">";
					for($k=0;$k<$ciclo2;$k++)
					{
						if($planes[$k]['plan_id']==$_SESSION['honorp']['datosgrupo'][$i]['plan_id'])
						{
							$this->salida .= "".$planes[$k]['num_contrato']."".' -- '."".$planes[$k]['plan_descripcion']."";
						}
					}
					$this->salida .= "  </td>";
					$this->salida .= "  <td align=\"right\">";
					$this->salida .= "".$_SESSION['honorp']['datosgrupo'][$i]['porcentaje']."".' %'."";
					$this->salida .= "  </td>";
					$this->salida .= "  <td align=\"right\">";
					$precio='';
					$precio=LiquidarCargo($_SESSION['honorp']['datosgrupo'][$i]['plan_id'],$_SESSION['honorp']['cargoshono'][0]['tarifario'],$_SESSION['honorp']['cargoshono'][$i]['cargo'],1,0);
					$por=(($precio['precio_plan']*$_SESSION['honorp']['datosgrupo'][$i]['porcentaje'])/100);
					//$por=(($_SESSION['honorp']['cargoshono'][$l]['precio']*$_SESSION['honorp']['datosgrupo'][$i]['porcentaje'])/100);
					$this->salida .= "".number_format(($por), 2, ',', '.')."";
					$this->salida .= "  </td>";
					$this->salida .= "  <td align=\"center\">";
					if($_SESSION['honorp']['datosgrupo'][$i]['servicio']==$_POST['serviprueh']
					AND $_SESSION['honorp']['datosgrupo'][$i]['plan_id']==$_POST['planeprueh'])
					{
						$this->salida .= "SI";
					}
					else
					{
						$this->salida .= "NO";
					}
					$this->salida .= "  </td>";
					$this->salida .= "  </tr>";
				}
			}
			if(empty($_SESSION['honorp']['datosgrupo']))
			{
				$this->salida .= "<tr class=\"modulo_list_claro\">";
				$this->salida .= "<td colspan=\"6\" align=\"center\">";
				$this->salida .= "'NO SE ENCONTRÓ NINGÚN HONORARIO POR GRUPO'";
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
			}
			$this->salida .= "      </tr>";
			$this->salida .= "      </table><br>";
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "      <tr class=\"modulo_table_list_title\">";
			$this->salida .= "      <td colspan=\"6\" align=\"center\">";
			$this->salida .= "'PORCENTAJE POR CARGO Y POOL'";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr class=\"modulo_table_list_title\">";
			$this->salida .= "      <td width=\"3%\" ></td>";
			$this->salida .= "      <td width=\"12%\">SERVICIO</td>";
			$this->salida .= "      <td width=\"50%\">PLAN</td>";//quitar aqui
			$this->salida .= "      <td width=\"10%\">PORCENTAJE</td>";
			$this->salida .= "      <td width=\"15%\">VALOR</td>";
			$this->salida .= "      <td width=\"10%\">COINCIDE</td>";
			$this->salida .= "      </tr>";
			$j=0;
			$ciclo=sizeof($_SESSION['honorp']['dapolcargo']);
// 			$ciclo1=sizeof($servicios);
// 			$ciclo2=sizeof($planes);
// 			$ciclo3=sizeof($_SESSION['honorp']['cargoshono']);
			for($i=0;$i<$ciclo;$i++)
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
				for($l=0;$l<$ciclo3;$l++)
				{
					if($_SESSION['honorp']['dapolcargo'][$i]['servicio']==$_POST['serviprueh']
					AND $_SESSION['honorp']['dapolcargo'][$i]['plan_id']==$_POST['planeprueh']
					AND $prioridad==1)
					{
						$this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">";
						$prioridad=0;
					}
					else
					{
						$this->salida .= "  <tr $color>";
					}
					$this->salida .= "  <td align=\"center\">";
					$this->salida .= "".($l+1)."";
					$this->salida .= "  </td>";
					$this->salida .= "  <td align=\"center\">";
					for($k=0;$k<$ciclo1;$k++)
					{
						if($servicios[$k]['servicio']==$_SESSION['honorp']['dapolcargo'][$i]['servicio'])
						{
							$this->salida .= "".$servicios[$k]['descripcion']."";
						}
					}
					$this->salida .= "  </td>";
					$this->salida .= "  <td align=\"center\">";
					for($k=0;$k<$ciclo2;$k++)
					{
						if($planes[$k]['plan_id']==$_SESSION['honorp']['dapolcargo'][$i]['plan_id'])
						{
							$this->salida .= "".$planes[$k]['num_contrato']."".' -- '."".$planes[$k]['plan_descripcion']."";
						}
					}
					$this->salida .= "  </td>";
					$this->salida .= "  <td align=\"right\">";
					$this->salida .= "".$_SESSION['honorp']['dapolcargo'][$i]['porcentaje']."".' %'."";
					$this->salida .= "  </td>";
					$this->salida .= "  <td align=\"right\">";
					$por=(($_SESSION['honorp']['cargoshono'][$l]['precio']*$_SESSION['honorp']['dapolcargo'][$i]['porcentaje'])/100);
					$this->salida .= "".number_format(($por), 2, ',', '.')."";
					$this->salida .= "  </td>";
					$this->salida .= "  <td align=\"center\">";
					if($_SESSION['honorp']['dapolcargo'][$i]['servicio']==$_POST['serviprueh']
					AND $_SESSION['honorp']['dapolcargo'][$i]['plan_id']==$_POST['planeprueh'])
					{
						$this->salida .= "SI";
					}
					else
					{
						$this->salida .= "NO";
					}
					$this->salida .= "  </td>";
					$this->salida .= "  </tr>";
				}
			}
			if(empty($_SESSION['honorp']['dapolcargo']))
			{
				$this->salida .= "<tr class=\"modulo_list_claro\">";
				$this->salida .= "<td colspan=\"6\" align=\"center\">";
				$this->salida .= "'NO SE ENCONTRÓ NINGÚN HONORARIO POR CARGO'";
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
			}
			$this->salida .= "      </tr>";
			$this->salida .= "      </table><br>";
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "      <tr class=\"modulo_table_list_title\">";
			$this->salida .= "      <td colspan=\"6\" align=\"center\">";
			$this->salida .= "'PORCENTAJE POR GRUPO Y POOL'";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr class=\"modulo_table_list_title\">";
			$this->salida .= "      <td width=\"3%\" ></td>";
			$this->salida .= "      <td width=\"12%\">SERVICIO</td>";
			$this->salida .= "      <td width=\"50%\">PLAN</td>";//quitar aqui
			$this->salida .= "      <td width=\"10%\">PORCENTAJE</td>";
			$this->salida .= "      <td width=\"15%\">VALOR</td>";
			$this->salida .= "      <td width=\"10%\">COINCIDE</td>";
			$this->salida .= "      </tr>";
			$j=0;
			$ciclo=sizeof($_SESSION['honorp']['dapolgrupo']);
// 			$ciclo1=sizeof($servicios);
// 			$ciclo2=sizeof($planes);
// 			$ciclo3=sizeof($_SESSION['honorp']['cargoshono']);
			for($i=0;$i<$ciclo;$i++)
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
				for($l=0;$l<$ciclo3;$l++)
				{
					if($_SESSION['honorp']['dapolgrupo'][$i]['servicio']==$_POST['serviprueh']
					AND $_SESSION['honorp']['dapolgrupo'][$i]['plan_id']==$_POST['planeprueh']
					AND $prioridad==1)
					{
						$this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">";
						$prioridad=0;
					}
					else
					{
						$this->salida .= "  <tr $color>";
					}
					$this->salida .= "  <td align=\"center\">";
					$this->salida .= "".($l+1)."";
					$this->salida .= "  </td>";
					$this->salida .= "  <td align=\"center\">";
					for($k=0;$k<$ciclo1;$k++)
					{
						if($servicios[$k]['servicio']==$_SESSION['honorp']['dapolgrupo'][$i]['servicio'])
						{
							$this->salida .= "".$servicios[$k]['descripcion']."";
						}
					}
					$this->salida .= "  </td>";
					$this->salida .= "  <td align=\"center\">";
					for($k=0;$k<$ciclo2;$k++)
					{
						if($planes[$k]['plan_id']==$_SESSION['honorp']['dapolgrupo'][$i]['plan_id'])
						{
							$this->salida .= "".$planes[$k]['num_contrato']."".' -- '."".$planes[$k]['plan_descripcion']."";
						}
					}
					$this->salida .= "  </td>";
					$this->salida .= "  <td align=\"right\">";
					$this->salida .= "".$_SESSION['honorp']['dapolgrupo'][$i]['porcentaje']."".' %'."";
					$this->salida .= "  </td>";
					$this->salida .= "  <td align=\"right\">";
					$por=(($_SESSION['honorp']['cargoshono'][$l]['precio']*$_SESSION['honorp']['dapolgrupo'][$i]['porcentaje'])/100);
					$this->salida .= "".number_format(($por), 2, ',', '.')."";
					$this->salida .= "  </td>";
					$this->salida .= "  <td align=\"center\">";
					if($_SESSION['honorp']['dapolgrupo'][$i]['servicio']==$_POST['serviprueh']
					AND $_SESSION['honorp']['dapolgrupo'][$i]['plan_id']==$_POST['planeprueh'])
					{
						$this->salida .= "SI";
					}
					else
					{
						$this->salida .= "NO";
					}
					$this->salida .= "  </td>";
					$this->salida .= "  </tr>";
				}
			}
			if(empty($_SESSION['honorp']['dapolgrupo']))
			{
				$this->salida .= "<tr class=\"modulo_list_claro\">";
				$this->salida .= "<td colspan=\"6\" align=\"center\">";
				$this->salida .= "'NO SE ENCONTRÓ NINGÚN HONORARIO POR GRUPO'";
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
			}
			$this->salida .= "      </tr>";
			$this->salida .= "      </table>";
			$this->salida .= "  </td></tr>";
			$this->salida .= "  </table>";
		}
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

}//fin de la clase
?>
