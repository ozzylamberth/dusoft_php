
<?php

/**
* Modulo de Contrataci�n (PHP).
*
* Modulo para el manejo de la contrataci�n (determinar las caracter�sticas de los planes)
*
* @author Jorge Eli�cer �vila Garz�n <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* app_Contrataci�n_adminclasses_HTML.php
*
* Clase que establece las pantallas y m�todos de la elaboraci�n de un plan de contrataci�n
* Modulo administrativo
**/

class app_Contratacion_adminclasses_HTML extends app_contratacion_admin
{
	function app_Contratacion_admin_HTML()
	{
		$this->app_Contratacion_admin(); //Constructor del padre 'modulo'
		$this->salida='';
		return true;
	}

/*
 * Funcion donde se visualiza el menu de usuario.
 * @return boolean
*/

	function Menu()
	{
		$this->salida .= ThemeAbrirTabla('MEN� ADMINISTRADOR - CONTRATACI�N SIIS');
		$this->salida .="   <table border=\"0\" class=\"modulo_table_list\" align=\"center\" width=\"80%\" >";
		$this->salida .="   <tr>";
		$this->salida .="   <td align=\"center\" class=\"modulo_table_title\" >ADMINISTRACI�N CONTRATACI�N</td>";
		$this->salida .="   <td align=\"center\" class=\"modulo_table_title\" >DEPARTAMENTO</td>";
		$this->salida .="   </tr>";
		$this->salida .="   <tr>";
		$this->salida .="   <td class=\"modulo_list_oscuro\" align=\"center\">CONTRATACI�N</td>";
		$this->salida .="   <td class=\"modulo_list_oscuro\" align=\"center\">EMPRESA:&nbsp;&nbsp;".$_SESSION['USER_ADMIN_MOD']['EMPRESA']."&nbsp;&nbsp;".$_SESSION['USER_ADMIN_MOD']['NOMBRE']."";
		$this->salida .="   </td>";
		$this->salida .="   </table>";
		$this->salida .="   <br><table border=\"0\" class=\"modulo_table_list\" align=\"center\" width=\"80%\" >";
		$this->salida .="   <tr>";
	    $this->salida .="   <td colspan=\"2\" align=\"center\" class=\"modulo_table_title\">ADMINISTRACI�N DE USUARIOS</td>";
		$this->salida .="   </tr>";
		$ac=ModuloGetURL('app','Contratacion','admin','RetornarPermisos');
		$this->salida .="   <tr>";
		$this->salida .="   <td colspan=\"2\" align=\"center\" class=\"modulo_list_claro\"><a href=\"$ac\">ADICIONAR USUARIO</a>";
		$this->salida .="   </td>";
		$this->salida .="   </tr>";
		$this->salida .="   </table><br>";
		$this->salida .="   <table border=\"0\" align=\"center\">";
		$action3=ModuloGetURL('app','Contratacion','admin','Retornar');
		$this->salida .="   <form name=\"forma\" action=\"$action3\" method=\"post\">";
		$this->salida .="   <tr>";
		$this->salida .="   <td align=\"center\">";
		$this->salida .="   <input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"MEN�\">";
		$this->salida .="   </td></tr>";
		$this->salida .="   </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

}//fin de la clase
?>
