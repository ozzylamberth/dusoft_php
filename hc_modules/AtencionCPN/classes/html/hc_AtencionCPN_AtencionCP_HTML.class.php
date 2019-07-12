<?php

/**
* Submodulo de AtencionCPN
* $Id: hc_AtencionCPN_AtencionCP_HTML.class.php,v 1.3 2007/02/01 20:43:56 luis Exp $
* @author luis alejandro vargas
* @version 1.0
* @package SIIS
*/

class AtencionCP_HTML
{
	function AtencionCP_HTML()
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
		$this->salida="";
		return $this->salida;
	}
	
	function frmAlerta()
	{
		$evolucion=SessionGetvar("Evolucion");
		$pfj=SessionGetvar("Prefijo");
		
		$this->salida.= ThemeAbrirTablaSubModulo("ATENCION CPN");
		$this->salida.="<center>";
		$this->salida.="	<label class=\"label_error\"><img src=\"".GetThemePath()."/images/informacion.png\"> EL PACIENTE NO ESTA INSCRITO EN EL PROGRAMA CPN</label>";
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