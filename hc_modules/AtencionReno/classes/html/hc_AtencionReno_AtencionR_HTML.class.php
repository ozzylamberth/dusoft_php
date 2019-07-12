<?php

/**
* Submodulo de AtencionR_HTML
* $Id: hc_AtencionReno_AtencionR_HTML.class.php,v 1.2 2007/02/01 20:44:08 luis Exp $
* @author luis alejandro vargas
* @version 1.0
* @package SIIS
*/

class AtencionR_HTML
{
	function AtencionR_HTML()
	{
		$this->limit=GetLimitBrowser();
		return true;
	}
	
	function frmHistoria()
	{
		$this->salida="";
		return $this->salida;
	}
	
	function frmConsulta()
	{
		return "";
	}
	
	function frmAlerta()
	{
		$pfj=SessionGetVar("Prefijo");
		$evolucion=SessionGetVar("Evolucion");
		
		$this->salida.= ThemeAbrirTablaSubModulo("ATENCION DE RENOPROTECCION");
		$this->salida.="<center>";
		$this->salida.="	<label class=\"label_error\"><img src=\"".GetThemePath()."/images/informacion.png\"> EL PACIENTE NO ESTA INSCRITO EN EL PROGRAMA DE RENOPROTECCION</label>";
		$this->salida.="</center><br>";
		$accion2=ModuloHCGetURL($evolucion,-1,0,'',false);
		$this->salida.= "	<form name=\"formavolver\" action=\"$accion2\" method=\"post\">";
		$this->salida.= "		<center><input class=\"input-submit\" type=\"submit\" name=\"volver$pfj\" value=\"VOLVER\">";
		$this->salida.= "		</center>";
		$this->salida.= "	</form>";
		$this->salida.= ThemeCerrarTablaSubModulo();
		
		return $this->salida;
	}
}
?>