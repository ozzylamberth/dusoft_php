<html>
<body>
<?php
	/**************************************************************************************
	* $Id: Calendario.php,v 1.2 2006/04/26 23:10:51 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	**************************************************************************************/
	// $VISTA='HTML';
	// $_ROOT='../../';
	// echo '<link href="'.$_ROOT.'themes/HTML/default/style/style.css" rel="stylesheet" type="text/css">';
	// include $_ROOT.'includes/enviroment.inc.php';
	// include $_ROOT.'includes/calendario.inc.php';
	// $fileName = "themes/$VISTA/" . GetTheme() . "/module_theme.php";
	// IncludeFile($fileName);
	// echo CalendarioTodos();

	$VISTA='HTML';
	$_ROOT='../../';
	include $_ROOT.'includes/enviroment.inc.php';
	IncludeClass("CalendarioHtml");
	$fileName = "themes/$VISTA/" . GetTheme() . "/module_theme.php";

	IncludeFile($fileName);
	$calendario = new CalendarioHtml();
	$cadena = $calendario->ObtenerCalendario();

	echo $cadena
?>
</body>
</html>
