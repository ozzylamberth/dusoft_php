<?php
	$_ROOT='../../';
	$VISTA='HTML';
	include $_ROOT.'includes/enviroment.inc.php';
	$fileName = "themes/$VISTA/" . GetTheme() . "/module_theme.php";

	IncludeFile($fileName);

	print(ReturnHeader('DATOS PACIENTE'));
	print(ReturnBody());

  $Cuenta=$_REQUEST['cuenta'];

	list($dbconn) = GetDBconn();
	$query = "SELECT h.valor_cuota_moderadora, h.valor_cuota_paciente,
						h.valor_nocubierto, h.valor_total_paciente, h.gravamen_valor_cubierto
						FROM cuentas as h
						WHERE h.numerodecuenta=$Cuenta" ;
	$resul = $dbconn->Execute($query);
	if ($dbconn->ErrorNo() != 0) {
		$this->error = "Error al Cargar el Modulo";
		$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
		return false;
	}

	$datos= $resul->GetRowAssoc($ToUpper = false);
	$resul->Close();


	echo ThemeAbrirTabla('DETALLE TOTAL PACIENTE');
	echo "<br><table  align=\"center\" border=\"0\" width=\"70%\" class=\"modulo_table_list\">";
	echo "<tr>";
	echo "  <td class=\"modulo_table_list_title\" width=\"55%\">TOTAL A PAGAR PACIENTE: </td>";
	echo "  <td class=\"label_error\">&nbsp;$&nbsp;".FormatoValor($datos[valor_total_paciente])."</td>";
	echo "</tr>";
	echo "<tr>";
	echo "  <td class=\"modulo_table_list_title\">CUOTA MODERADORA: </td>";
	echo "  <td class=\"modulo_list_claro\">&nbsp;&nbsp;".FormatoValor($datos[valor_cuota_moderadora])."</td>";
	echo "</tr>";
	echo "<tr>";
	echo "  <td class=\"modulo_table_list_title\">COPAGO: </td>";
	echo "  <td class=\"modulo_list_claro\">&nbsp;&nbsp;".FormatoValor($datos[valor_cuota_paciente])."</td>";
	echo "</tr>";
	echo "<tr>";
	echo "  <td class=\"modulo_table_list_title\">VALOR NO CUBIERTO: </td>";
	echo "  <td class=\"modulo_list_claro\">&nbsp;&nbsp;".FormatoValor($datos[valor_nocubierto])."</td>";
	echo "</tr>";
	echo "<tr>";
	echo "  <td class=\"modulo_table_list_title\">IVA: </td>";
	echo "  <td class=\"modulo_list_claro\">&nbsp;&nbsp;".FormatoValor($datos[gravamen_valor_cubierto])."</td>";
	echo "</tr>";
	echo "</table>";


	echo ThemeCerrarTabla();
	print(ReturnFooter());
?>

