<html>
<body>
<?php


$VISTA='HTML';
$_ROOT='../../';
//echo '<link href="'.$_ROOT.'themes/HTML/default/style/style.css" rel="stylesheet" type="text/css">';
include $_ROOT.'includes/enviroment.inc.php';
include $_ROOT.'includes/calendario.inc.php';
	$fileName = "themes/$VISTA/" . GetTheme() . "/module_theme.php";

	IncludeFile($fileName);

echo CalendarioTodos();
?>
</body>
</html>
