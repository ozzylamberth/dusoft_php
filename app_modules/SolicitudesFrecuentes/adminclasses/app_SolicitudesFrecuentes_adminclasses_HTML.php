
<?php

/**
* Modulo de Solicitudes Frecuentes (PHP).
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* app_SolicitudesFrecuentes_adminclasses_HTML.php
**/

class app_SolicitudesFrecuentes_adminclasses_HTML extends app_SolicitudesFrecuentes_admin
{
	function app_SolicitudesFrecuentes_admin_HTML()
	{
		$this->app_SolicitudesFrecuentes_admin(); //Constructor del padre 'modulo'
		$this->salida='';
		return true;
	}

/*
 * Funcion donde se visualiza el menu de usuario.
 * @return boolean
*/
	function Menu()
	{
		$this->salida .= ThemeAbrirTabla('MENÚ ADMINISTRADOR - SOLICITUDES FRECUENTES');
		$this->salida .="   <table border=\"0\" class=\"modulo_table_list\" align=\"center\" width=\"80%\" >";
		$this->salida .="   <tr>";
		$this->salida .="   <td align=\"center\" class=\"modulo_table_title\" >ADMINISTRACIÓN";
		$this->salida .="   </td>";
		$this->salida .="   <td align=\"center\" class=\"modulo_table_title\" >DEPARTAMENTO";
		$this->salida .="   </td>";
		$this->salida .="   </tr>";
		$this->salida .="   <tr>";
		$this->salida .="   <td class=\"modulo_list_oscuro\" align=\"center\">SOLICITUDES FRECUENTES";
		$this->salida .="   </td>";
		$this->salida .="   <td class=\"modulo_list_oscuro\" align=\"center\">EMPRESA:&nbsp;&nbsp;".$_SESSION['USER_ADMIN_MOD']['EMPRESA']."&nbsp;&nbsp;".$_SESSION['USER_ADMIN_MOD']['NOMBRE']."";
		$this->salida .="   </td>";
		$this->salida .="   </tr>";
		$this->salida .="   </table><br>";
		$this->salida .="   <table border=\"0\" class=\"modulo_table_list\" align=\"center\" width=\"80%\" >";
		$this->salida .="   <tr>";
	    $this->salida .="   <td colspan=\"2\" align=\"center\" class=\"modulo_table_title\">ADMINISTRACIÓN DE USUARIOS";
		$this->salida .="   </td>";
		$this->salida .="   </tr>";
		$ac=ModuloGetURL('app','SolicitudesFrecuentes','admin','RetornarPermisos');
		$this->salida .="   <tr>";
		$this->salida .="   <td colspan=\"2\" align=\"center\" class=\"modulo_list_claro\">";
		$this->salida .="   <a href=\"$ac\">ADICIONAR USUARIO</a>";
		$this->salida .="   </td>";
		$this->salida .="   </tr>";
		$this->salida .="   </table><br>";
		$this->salida .="   <table border=\"0\" align=\"center\">";
		$action3=ModuloGetURL('app','SolicitudesFrecuentes','admin','Retornar');
		$this->salida .="   <form name=\"forma\" action=\"$action3\" method=\"post\">";
		$this->salida .="   <tr>";
		$this->salida .="   <td align=\"center\">";
		$this->salida .="   <input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"MENÚ\">";
		$this->salida .="   </td>";
		$this->salida .="   </tr>";
		$this->salida .="   </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

}//fin de la clase
?>
