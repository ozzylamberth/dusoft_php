
<?php

/**
* Modulo de InvProveedores (PHP).
*
//*
*
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* app_InvProveedores_adminclasses_HTML.php
*
//*
**/

class app_InvProveedores_adminclasses_HTML extends app_InvProveedores_admin
{
	function app_InvProveedores_admin_HTML()
	{
		$this->app_InvProveedores_admin(); //Constructor del padre 'modulo'
		$this->salida='';
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
