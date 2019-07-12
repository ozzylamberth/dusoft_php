<?php

/**
 * $Id: app_Profesionales_adminclasses_HTML.php,v 1.2 2010/02/24 12:09:54 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_CreacionAgenda_adminclasses_HTML extends app_CreacionAgenda_admin
{

	function app_CreacionAgenda_admin_HTML()
	{
		$this->app_CreacionAgenda_admin(); //Constructor del padre 'modulo'
		$this->salida='';
		return true;
	}

//Funciones de Asignación de Cita.

	function Menu()
	{
		$this->salida = ThemeAbrirTabla('CONSULTA EXTERNA');
		$this->salida .= ThemeCerrarTabla();
		return true;
	}


	function SetStyle($campo)
	{
				if ($this->frmError[$campo] || $campo=="MensajeError"){
					if ($campo=="MensajeError"){
						return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
					}
					else
					{
						return ("label_error");
					}
				}
			return ("label");
	}


}

?>
