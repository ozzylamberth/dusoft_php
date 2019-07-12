<?php
class app_Solicitud_Soporte_user extends classModulo
{

	function app_Solicitud_Soporte_user()
	{
		return true;
	}

	function main()
	{
		$this->PantallaInicial();
		return true;
	}
//LlamaFormaNuevaSolicitud
  function LlamaFormaNuevaSolicitud()
  {
    $this->FormaNuevaSolicitud();
    return true;
  }
}
?>
