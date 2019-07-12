<?php
	$_ROOT='../../';
	$VISTA='HTML';
	include $_ROOT.'includes/enviroment.inc.php';
	$fileName = "themes/$VISTA/" . GetTheme() . "/module_theme.php";

	IncludeFile($fileName);

	print(ReturnHeader('DATOS MOVIMIENTOS CAMAS'));
	print(ReturnBody());

   $Ingreso=$_REQUEST['ingreso'];
   $Cuenta=$_REQUEST['cuenta'];

	IncludeLib("funciones_facturacion");
	$var = BuscarMoviemientosCamas($Ingreso,$Cuenta);


	echo "<br><br>";
	echo ThemeAbrirTabla('MOVIMIENTOS DE CAMAS');
	echo "	<table width=\"100%\" cellspacing=\"2\" border=\"0\" cellpadding=\"2\" align=\"center\" class=\"normal_10\">";
	echo "		<tr class=\"modulo_table_list_title\">";
	echo "		<td>CARGO</td>";
	echo "		  <td>DESCRIPCION</td>";
	echo "			<td>FECHA INGRESO</td>";
	echo "			<td>FECHA EGRESO</td>";
	echo "			<td>PIEZA</td>";
	echo "			<td>CAMA</td>";
	echo "			<td>UBICACION</td>";
	echo "			<td>DEPARTAMENTO</td>";
	echo "			<td>ESTADO</td>";
	echo "			</tr>";
	for($i=0; $i<sizeof($var); $i++)
	{
		if( $i % 2) $estilo='modulo_list_claro';
		else $estilo='modulo_list_oscuro';
		echo "		<tr  class=\"$estilo\">";
		echo "		  <td align=\"center\">".$var[$i][cargo]."</td>";
		echo "		  <td>".$var[$i][descar]."</td>";
		echo "			<td align=\"center\">".$var[$i][fecha_ingreso]."</td>";
		if(empty($var[$i][fecha_egreso]))
		{
				$var[$i][fecha_egreso]='CAMA ACTUAL';
				$class='label_mark';
		}
		echo "			<td align=\"center\" class=\"$class\">".$var[$i][fecha_egreso]."</td>";
		echo "			<td align=\"center\">".$var[$i][pieza]."</td>";
		echo "			<td align=\"center\">".$var[$i][cama]."</td>";
		echo "			<td align=\"center\">".$var[$i][ubicacion]."</td>";
		echo "			<td align=\"center\">".$var[$i][descripcion]."</td>";
		$estado='Cargada';
		if(empty($var[$i][transaccion]))
		{  $estado='Sin Cargar';}
		echo "			<td align=\"center\">$estado</td>";
		echo "			</tr>";
	}
	echo "</table>";
	echo ThemeCerrarTabla();
	print(ReturnFooter());
?>

