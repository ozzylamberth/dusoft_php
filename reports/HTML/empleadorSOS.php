<?php
	$_ROOT='../../';
	$VISTA='HTML';
	include $_ROOT.'includes/enviroment.inc.php';
	$fileName = "themes/$VISTA/" . GetTheme() . "/module_theme.php";
	IncludeFile($fileName);
	print(ReturnHeader('DATOS EMPLEADOR'));
	print(ReturnBody());
	echo ThemeAbrirTabla('DATOS EMPLEADOR');
	echo "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"100%\" align=\"center\">";
	echo "<tr>";
	echo "<td colspan=\"2\" align=\"center\" class=\"modulo_table_list_title\">DATOS EMPLEADOR</td>";
	echo "</tr>";
	$spy=0;
	if($spy==1)
	{
		echo "<tr class=\"modulo_list_oscuro\">";
		$spy=0;
	}
	else
	{
		echo "<tr class=\"modulo_list_claro\">";
		$spy=1;
	}
	echo "<td class=\"label\">";
	echo "IDENTIFICACION: ";
	echo "</td>";
	echo "<td>";
	echo $_SESSION['DATOSAFILIADOEMPLEADOR'][$_REQUEST['val']][cdgo_tpo_idntfccn].' - '.$_SESSION['DATOSAFILIADOEMPLEADOR'][$_REQUEST['val']][nmro_idntfccn];
	echo "</td>";
	echo "</tr>";
	if($spy==1)
	{
		echo "<tr class=\"modulo_list_oscuro\">";
		$spy=0;
	}
	else
	{
		echo "<tr class=\"modulo_list_claro\">";
		$spy=1;
	}
	echo "<td class=\"label\">";
	echo "RAZON SOCIAL: ";
	echo "</td>";
	echo "<td>";
	echo $_SESSION['DATOSAFILIADOEMPLEADOR'][$_REQUEST['val']][rzn_scl];
	echo "</td>";
	echo "</tr>";
	if($spy==1)
	{
		echo "<tr class=\"modulo_list_oscuro\">";
		$spy=0;
	}
	else
	{
		echo "<tr class=\"modulo_list_claro\">";
		$spy=1;
	}
	echo "<td class=\"label\">";
	echo "SUCURSAL: ";
	echo "</td>";
	echo "<td>";
	echo $_SESSION['DATOSAFILIADOEMPLEADOR'][$_REQUEST['val']][cnsctvo_scrsl_ctznte];
	echo "</td>";
	echo "</tr>";
	if(!($_SESSION['DATOSAFILIADOEMPLEADOR'][$_REQUEST['val']][cnsctvo_cdgo_actvdd_ecnmca]==='0'))
	{
		if($spy==1)
		{
			echo "<tr class=\"modulo_list_oscuro\">";
			$spy=0;
		}
		else
		{
			echo "<tr class=\"modulo_list_claro\">";
			$spy=1;
		}
		echo "<td class=\"label\">";
		echo "ACTIVIDAD ECONOMICA: ";
		echo "</td>";
		echo "<td>";
		echo $_SESSION['DATOSAFILIADOEMPLEADOR'][$_REQUEST['val']][cnsctvo_cdgo_actvdd_ecnmca].' - '.$_SESSION['DATOSAFILIADOEMPLEADOR'][$_REQUEST['val']][dscrpcn_actvdd_ecnmca];
		echo "</td>";
		echo "</tr>";
	}
	if($spy==1)
	{
		echo "<tr class=\"modulo_list_oscuro\">";
		$spy=0;
	}
	else
	{
		echo "<tr class=\"modulo_list_claro\">";
		$spy=1;
	}
	echo "<td class=\"label\">";
	echo "CARGO: ";
	echo "</td>";
	echo "<td>";
	echo $_SESSION['DATOSAFILIADOEMPLEADOR'][$_REQUEST['val']][cdgo_crgo].' - '.$_SESSION['DATOSAFILIADOEMPLEADOR'][$_REQUEST['val']][dscrpcn_crgo];
	echo "</td>";
	echo "</tr>";
	if($spy==1)
	{
		echo "<tr class=\"modulo_list_oscuro\">";
		$spy=0;
	}
	else
	{
		echo "<tr class=\"modulo_list_claro\">";
		$spy=1;
	}
	echo "<td class=\"label\">";
	echo "ARP: ";
	echo "</td>";
	echo "<td>";
	echo $_SESSION['DATOSAFILIADOEMPLEADOR'][$_REQUEST['val']][cdgo_entdd].' - '.$_SESSION['DATOSAFILIADOEMPLEADOR'][$_REQUEST['val']][dscrpcn_entdd];
	echo "</td>";
	echo "</tr>";
	if($spy==1)
	{
		echo "<tr class=\"modulo_list_oscuro\">";
		$spy=0;
	}
	else
	{
		echo "<tr class=\"modulo_list_claro\">";
		$spy=1;
	}
	echo "<td class=\"label\">";
	echo "TIPO COTIZANTE: ";
	echo "</td>";
	echo "<td>";
	echo $_SESSION['DATOSAFILIADOEMPLEADOR'][$_REQUEST['val']][cdgo_tpo_ctznte].' - '.$_SESSION['DATOSAFILIADOEMPLEADOR'][$_REQUEST['val']][dscrpcn_tpo_ctznte];
	echo "</td>";
	echo "</tr>";
	if($spy==1)
	{
		echo "<tr class=\"modulo_list_oscuro\">";
		$spy=0;
	}
	else
	{
		echo "<tr class=\"modulo_list_claro\">";
		$spy=1;
	}
	echo "<td class=\"label\">";
	echo "VIGENCIA: ";
	echo "</td>";
	echo "<td>";
	echo $_SESSION['DATOSAFILIADOEMPLEADOR'][$_REQUEST['val']][inco_vgnca_cbrnza].' - '.$_SESSION['DATOSAFILIADOEMPLEADOR'][$_REQUEST['val']][fn_vgnca_cbrnza];
	echo "</td>";
	echo "</tr>";
	if($spy==1)
	{
		echo "<tr class=\"modulo_list_oscuro\">";
		$spy=0;
	}
	else
	{
		echo "<tr class=\"modulo_list_claro\">";
		$spy=1;
	}
	echo "<td class=\"label\">";
	echo "COPAGO Y CUOTA MODERADORA: ";
	echo "</td>";
	echo "<td>";
	echo $_SESSION['DATOSAFILIADOEMPLEADOR'][$_REQUEST['val']][txto_cpgo_cta_mdrdra];
	echo "</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td class=\"label\" colspan='2' align='center'>";
	echo "<input type='button' name='CERRAR' value='CERRAR' class='input-submit' onclick='window.close()'>";
	echo "</td>";
	echo "</tr>";
	echo "</table>";
	//print_r($_SESSION['DATOSAFILIADOEMPLEADOR'][$_REQUEST['val']]);
	echo ThemeCerrarTabla();
	print(ReturnFooter());
	?>
