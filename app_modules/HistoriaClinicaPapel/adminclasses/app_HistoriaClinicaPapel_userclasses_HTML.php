<?php

class app_HistoriaClinicaPapel_userclasses_HTML extends app_HistoriaClinicaPapel_user
{

	function app_HistoriaClinicaPapel_user_HTML()
	{
	  $this->app_HistoriaClinicaPapel_user(); //Constructor del padre 'modulo'
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

