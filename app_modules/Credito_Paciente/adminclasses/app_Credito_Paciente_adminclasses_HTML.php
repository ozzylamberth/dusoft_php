
<?php

/**
* Modulo de Cartera (PHP).
*
//*
*
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* app_Cartera_adminclasses_HTML.php
*
//*
**/

class app_Soat_adminclasses_HTML extends app_Soat_admin
{
	function app_Soat_admin_HTML()
	{
		$this->app_Soat_admin(); //Constructor del padre 'modulo'
		$this->salida='';
		return true;
	}

	//Determina las empresas, en las cuales el usuario tiene permisos
	function PrincipalContra2()//Selecciona las empresas disponibles
	{
		UNSET($_SESSION['contra']);
		if($this->UsuariosContra()==false)
		{
			return false;
		}
		return true;
	}

	//Función principal que da las opciones para tener acceso a los datos de CONTRATACIÓN
	function PrincipalContra()//Llama a todas las opciones posibles
	{
		if($_SESSION['contra']['empresa']==NULL)
		{
			$_SESSION['contra']['empresa']=$_REQUEST['permisoscontra']['empresa_id'];
			$_SESSION['contra']['razonso']=$_REQUEST['permisoscontra']['descripcion1'];
		}
		$this->salida  = ThemeAbrirTabla('CONTRATACIÓN - OPCIONES');
		$this->salida .= "<br><table border=\"0\" width=\"70%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"100%\">";
		$this->salida .= "      <table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_list_oscuro\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Contratacion','user','EmpresasContra') ."\">CONTRATACIÓN PARA CLIENTES</a><br>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_list_claro\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Contratacion','user','ProvedorContra') ."\">CONTRATACIÓN PARA PROVEEDORES</a><br>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$action=ModuloGetURL('app','Contratacion','user','PrincipalContra2');
		$this->salida .= "  <form name=\"form\" action=\"$action\" method=\"post\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\"><br>";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"EMPRESAS\"><br>";
		$this->salida .= "  </form>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "</td></tr></table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//
	function Menu()//
	{
		$this->salida.= ThemeAbrirTabla('MENU ADMINISTRADOR SOAT');
		$this->salida.="<br><table border=\"0\"  class=\"modulo_table_list\"  align=\"center\"   width=\"80%\" >";
		$this->salida.="<tr>";
		$this->salida .= "<td colspan=\"2\"   align=\"center\" class=\"modulo_table_title\" >EVENTOS DE USUARIOS</td>";
		$this->salida.="</tr>";
		$ac=ModuloGetURL('app','CajaGeneral','admin','RetornarPermisos');
		$ax=ModuloGetURL('system','Usuarios','user','LlamaFormaModificarPasswd');
		$this->salida.="<tr>";
		$this->salida .= "<td  colspan=\"2\"  class=\"modulo_list_oscuro\"  align=\"center\"><a href=\"$ax\">CREAR NUEVA CAJA</a>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr>";
		$this->salida .= "<td   colspan=\"2\"  class=\"modulo_list_claro\"  align=\"center\"><a href=\"$ac\">ADICIONAR USUARIO</a>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.="<table border='0' align='center'>";
		$action3=ModuloGetURL('app','CajaGeneral','admin','Retornar');
		$this->salida .= "           <form name=\"forma\" action=\"$action3\" method=\"post\">";
		$this->salida.="	<tr>";
		$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"Menu\"></td></tr>";
		$this->salida.="</table>";
		$this->salida.= ThemeCerrarTabla();
		return true;
	}

}//fin de la clase
?>
