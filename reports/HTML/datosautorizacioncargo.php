<?php
	$_ROOT='../../';
	$VISTA='HTML';
	include $_ROOT.'includes/enviroment.inc.php';
	$fileName = "themes/$VISTA/" . GetTheme() . "/module_theme.php";

	IncludeFile($fileName);

	print(ReturnHeader('DATOS AUTORIZACION'));
	print(ReturnBody());

  $Cuenta=$_REQUEST['Cuenta'];
	$Autorizacion=$_REQUEST['Autorizacion'];
	$Cargo=$_REQUEST['Cargo'];
	$Tarifario=$_REQUEST['Tarifario'];
	$ayudas=$_REQUEST['Ayudas'];

	$query='';
	switch($_REQUEST['Tipo'])
	{
				case 'Int':
					if(!empty($ayudas))
					{
								$query = "select a.transaccion, a.cantidad,  a.cargo, b.*, g.nombre, e.descripcion, f.autorizacion as sistema, h.autorizacion as escrita, i.autorizacion as tele, j.autorizacion as elec, k.autorizacion as bd
													from cuentas as d, ayudas_diagnosticas as a, autorizaciones as b left join autorizaciones_por_sistema as f on(b.autorizacion=f.autorizacion) left join autorizaciones_escritas as h on(b.autorizacion=h.autorizacion) left join autorizaciones_telefonicas as i on(b.autorizacion=i.autorizacion) left join autorizaciones_electronicas as j on(b.autorizacion=j.autorizacion) left join autorizaciones_bd as k on(b.autorizacion=k.autorizacion), system_usuarios as g, tarifarios_detalle as e
													where d.numerodecuenta=$Cuenta and a.tarifario_id='$Tarifario'
													and d.numerodecuenta=a.numerodecuenta and a.cargo='$Cargo'
													and b.autorizacion=$Autorizacion and b.usuario_id=g.usuario_id
													and e.cargo='$Cargo' and e.tarifario_id='$Tarifario'";
					}
					else
					{
								$query = "select a.transaccion, a.cantidad,  a.cargo, b.*, g.nombre, e.descripcion, f.autorizacion as sistema, h.autorizacion as escrita, i.autorizacion as tele, j.autorizacion as elec, k.autorizacion as bd
													from cuentas as d, cuentas_detalle as a, autorizaciones as b left join autorizaciones_por_sistema as f on(b.autorizacion=f.autorizacion) left join autorizaciones_escritas as h on(b.autorizacion=h.autorizacion) left join autorizaciones_telefonicas as i on(b.autorizacion=i.autorizacion) left join autorizaciones_electronicas as j on(b.autorizacion=j.autorizacion) left join autorizaciones_bd as k on(b.autorizacion=k.autorizacion), system_usuarios as g, tarifarios_detalle as e
													where d.numerodecuenta=$Cuenta and a.tarifario_id='$Tarifario'
													and d.numerodecuenta=a.numerodecuenta and a.cargo='$Cargo'
													and b.autorizacion=$Autorizacion and b.usuario_id=g.usuario_id
													and e.cargo='$Cargo' and e.tarifario_id='$Tarifario'";
					}
				break;
	}

	list($dbconn) = GetDBconn();
	$result = $dbconn->Execute($query);

	while(!$result->EOF)
	{
			$var[]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
	}

	IncludeLib("tarifario");
	echo "<br><br>";
	echo ThemeAbrirTabla('DATOS AUTORIZACION');
	echo "	<table width=\"100%\" cellspacing=\"2\" border=\"0\" cellpadding=\"2\" align=\"center\" class=\"normal_10\">";
	echo "		<tr class=\"modulo_table_list_title\">";
	echo "		  <td>TARIFARIO</td>";
	echo "		  <td>CARGO</td>";
	echo "			<td>DESCRIPCION</td>";
	echo "			<td>CANT.</td>";
	echo "			<td>TIPO AUTORIZACION</td>";
	echo "			<td>COD. AUTO.</td>";
	echo "			<td>AUTORIZADOR</td>";
	echo "			<td>FECHA</td>";
	echo "			<td>OBSERVACIONES</td>";
	echo "			</tr>";
	for($i=0; $i<sizeof($var); $i++)
	{
	echo "		<tr  class=\"modulo_list_claro\">";
	echo "		  <td align=\"center\">$Tarifario</td>";
	echo "		  <td align=\"center\">$Cargo</td>";
	echo "			<td>".$var[$i][descripcion]."</td>";
	echo "			<td align=\"center\">".FormatoValor($var[$i][cantidad])."</td>";
	if(!empty($var[$i][sistema])){   $nom='Por Sistema'; }
	if(!empty($var[$i][escrita])){   $nom='Escrita';     }
	if(!empty($var[$i][tele])){      $nom='Telefonica';  }
	if(!empty($var[$i][elec])){      $nom='Electronica'; }
	if(!empty($var[$i][bdc])){       $nom='Base Datos';  }
	echo "			<td align=\"center\">$nom</td>";
	echo "			<td align=\"center\">$Autorizacion</td>";
	echo "			<td align=\"center\">".$var[$i][nombre]."</td>";
	echo "			<td align=\"center\">".$var[$i][fecha_registro]."</td>";
	echo "			<td>".$var[$i][observaciones]."</td>";
	echo "			</tr>";
	}
	echo "</table>";
	echo ThemeCerrarTabla();
	print(ReturnFooter());
?>

