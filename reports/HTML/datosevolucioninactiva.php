<?php
	$_ROOT='../../';
	$VISTA='HTML';
	include $_ROOT.'includes/enviroment.inc.php';
	$fileName = "themes/$VISTA/" . GetTheme() . "/module_theme.php";

	IncludeFile($fileName);

	print(ReturnHeader('DATOS PROFESIONAL'));
	print(ReturnBody());
	list($dbconn) = GetDBconn();
  $query = " select c.nombre_tercero, case when a.estado is null then '1' else a.estado end, a.tipo_profesional, a.sexo_id, a.tarjeta_profesional, a.universidad from profesionales as a, profesionales_empresas as b, terceros as c where a.tipo_id_tercero=b.tipo_id_tercero and a.tercero_id=b.tercero_id and a.tipo_id_tercero=c.tipo_id_tercero and a.tercero_id=c.tercero_id and a.tipo_id_tercero='".$_REQUEST['TipoId']."' and a.tercero_id='".$_REQUEST['ProfesionalId']."';";

	$result = $dbconn->Execute($query);

	if ($dbconn->ErrorNo() != 0) {
		$this->error = "Error al Cargar el Modulo";
		$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
		return false;
	}
	$datos= $result->GetRowAssoc($ToUpper = false);
	$query = "select descripcion from tipos_profesionales where tipo_profesional='".$datos['tipo_profesional']."';";

	$result = $dbconn->Execute($query);
	if ($dbconn->ErrorNo() != 0) {
		$this->error = "Error al Cargar el Modulo";
		$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
		return false;
	}
	$datos1=$result->GetRowAssoc($ToUpper = false);
	$query = "select descripcion from tipo_sexo where sexo_id='".$datos['sexo_id']."';";

	$result = $dbconn->Execute($query);
	if ($dbconn->ErrorNo() != 0) {
		$this->error = "Error al Cargar el Modulo";
		$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
		return false;
	}
	$Sexo=$result->fields[0];
	$query = "select b.descripcion from profesionales_especialidades as a, especialidades as b where a.tipo_id_tercero='".$_REQUEST['TipoId']."' and a.tercero_id='".$_REQUEST['ProfesionalId']."' and a.especialidad=b.especialidad";
	$result = $dbconn->Execute($query);

	if ($dbconn->ErrorNo() != 0) {
		$this->error = "Error al Cargar el Modulo";
		$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
		return false;
	}
	$datos2.=$result->fields[0];
	$result->MoveNext();
	while(!$result->EOF)
	{
		$datos2.=', '.$result->fields[0];
		$result->MoveNext();
	}
	echo "<br><br>";
	echo ThemeAbrirTabla('DATOS PROFESIONAL');
	echo "	<table width=\"90%\" cellspacing=\"2\" border=\"0\" cellpadding=\"2\" align=\"center\" class=\"normal_10\">";
	echo "		<tr height=\"20\"><td>TIPO DOCUMENTO: </td>";
	echo "		<td class=\"label\">".$_REQUEST['TipoId']."</td></tr>";
	echo "		<tr height=\"20\">";
	echo "	  	<td>DOCUMENTO: </td>";
	echo "	  	<td class=\"label\">".$_REQUEST['ProfesionalId']."</td>";
	echo "	 		<td></td>";
	echo "		</tr>";
	echo "		<tr height=\"20\">";
	echo "	  	<td>NOMBRE: </td>";
	echo "	  	<td class=\"label\">$datos[nombre_tercero]</td>";
	echo "	 		<td></td>";
	echo "		</tr>";
	echo "		<tr>";
	echo "	  	<td>TARJETA PROFESIONAL: </td>";
	echo "	  	<td class=\"label\">$datos[tarjeta_profesional]</td>";
	echo "	  	<td width=\"25%\"></td>";
	echo "		</tr>";
	echo "		<tr height=\"20\">";
	echo "	  	<td>TIPO PROFESIONAL: </td>";
	echo "	  	<td class=\"label\">$datos1[descripcion]</td>";
	echo "	  	<td></td>";
	echo "		</tr>";
	echo "		<tr height=\"20\">";
	echo "	  	<td>ESTADO: </td>";
	echo "	  	<td class=\"label\">";
	if($datos['estado']==0)
	{
		echo 'INACTIVO';
	}
	else
	{
		echo 'ACTIVO';
	}
	echo "</td>";
	echo "	  	<td></td>";
	echo "		</tr>";
	echo "		<tr height=\"20\">";
	echo "	  	<td>SEXO: </td>";
	echo "	  	<td class=\"label\">$Sexo</td>";
	echo "	  	<td></td>";
	echo "		</tr>";
	echo "		<tr height=\"20\">";
	echo "	  	<td>ESPECIALIDADES: </td>";
	echo "	  	<td class=\"label\">$datos2</td>";
	echo "	  	<td></td>";
	echo "		</tr>";
	echo "		<tr height=\"20\">";
	echo "	  	<td>UNIVERSIDAD: </td>";
	echo "	  	<td class=\"label\">$datos[universidad]</td>";
	echo "	  	<td></td>";
	echo "		</tr>";
	echo "</table>";
	echo ThemeCerrarTabla();
	print(ReturnFooter());
?>

