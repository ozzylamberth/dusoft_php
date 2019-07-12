<?php

/**
 * $Id: app_CompararBD_userclasses_HTML.php,v 1.2 2005/06/02 17:08:31 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_EJEMPLO_userclasses_HTML extends app_EJEMPLO_user
{

	function app_EJEMPLO_user_HTML()
	{
	  $this->app_EJEMPLO_user(); //Constructor del padre 'modulo'
		$this->salida='';
		return true;
	}


  function forma1()
	{
  IncludeLib('tarifario');

	$this->salida  = ThemeAbrirTabla('HISTORIA CLINICA');
	$this->salida .= "$var<br><a href=\"" . ModuloHCGetURL(0,0,1,'ConsultaExterna') ."\">VER SUBMODULO</a><br><br>";
  $this->salida .= "valores<br>$ver";
  $this->salida .= ThemeCerrarTabla();
  return true;

  }


}//fin de la clase
?>

