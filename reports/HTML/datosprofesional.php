<?php
	$_ROOT='../../';
	$VISTA='HTML';
	include $_ROOT.'includes/enviroment.inc.php';
	$fileName = "themes/$VISTA/" . GetTheme() . "/module_theme.php";

	IncludeFile($fileName);
	if(!IncludeFile('classes/validador/validador.class.php',true)){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "classes/validar/validador.class.php' no existe.";
			return false;
	}

	print(ReturnHeader('DATOS EVOLUCION INACTIVA'));
	print(ReturnBody());
	if (!IncludeFile("classes/ResumenHC/ResumenHC.class.php"))
	{
		$this->error = "Error";
		$this->mensajeDeError = "No se pudo incluir : classes/ResumenHC/ResumenHC.class.php";
	}
	global $VISTA;
	if (!IncludeFile("classes/ResumenHC/$VISTA/ResumenHC.$VISTA.php"))
	{
		$this->error = "Error";
		$this->mensajeDeError = "No se pudo incluir : classes/ResumenHC/$VISTA/ResumenHC.$VISTA.php";
	}
	$temp="ResumenHC_$VISTA";
	$resumenhc = new $temp($_REQUEST['evolucion']);
	if (!$resumenhc->Iniciar()){
		$this->error = $resumenhc->Error();
		$this->mensajeDeError = $resumenhc->ErrorMsg();
		return false;
	}
		echo "<br><br>";
		echo ThemeAbrirTabla('DATOS EVOLUCION');
		echo $resumenhc->GetSalida();
	 	echo ThemeCerrarTabla();
	print(ReturnFooter());
?>

