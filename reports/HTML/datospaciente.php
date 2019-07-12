<?php
	$_ROOT='../../';
	$VISTA='HTML';
	include $_ROOT.'includes/enviroment.inc.php';
	$fileName = "themes/$VISTA/" . GetTheme() . "/module_theme.php";

	IncludeFile($fileName);

	print(ReturnHeader('DATOS PACIENTE'));
	print(ReturnBody());

  $TipoId=$_REQUEST['TipoId'];
	$PacienteId=$_REQUEST['PacienteId'];

	list($dbconn) = GetDBconn();
  $query = " SELECT a.primer_apellido,
										a.segundo_apellido,
										a.primer_nombre,
										a.segundo_nombre,
										a.fecha_nacimiento,
										a.residencia_direccion,
										a.residencia_telefono,
										a.fecha_registro,
										a.sexo_id,
										a.ocupacion_id,
										a.nombre_madre
						FROM pacientes as a
						WHERE a.tipo_id_paciente='$TipoId' AND a.paciente_id='$PacienteId'";

	$result = $dbconn->Execute($query);

	if ($dbconn->ErrorNo() != 0) {
		$this->error = "Error al Cargar el Modulo";
		$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
		return false;
	}
	$datos= $result->GetRowAssoc($ToUpper = false);
	$query = " SELECT b.descripcion, c.via_ingreso_nombre, a.ingreso
						FROM ingresos as a, causas_externas as b, vias_ingreso as c
						WHERE a.tipo_id_paciente='$TipoId' AND a.paciente_id='$PacienteId' and a.estado=1 and a.causa_externa_id=b.causa_externa_id and a.via_ingreso_id=c.via_ingreso_id";

	$result = $dbconn->Execute($query);
	if ($dbconn->ErrorNo() != 0) {
		$this->error = "Error al Cargar el Modulo";
		$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
		return false;
	}
	$datos1=$result->GetRowAssoc($ToUpper = false);
	$query = "SELECT descripcion FROM tipo_sexo WHERE sexo_id='$datos[sexo_id]'";
	$result = $dbconn->Execute($query);

	if ($dbconn->ErrorNo() != 0) {
		$this->error = "Error al Cargar el Modulo";
		$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
		return false;
	}
	$Sexo=$result->fields[0];
	$query = "SELECT ocupacion_descripcion FROM ocupaciones WHERE ocupacion_id='$datos[ocupacion_id]'";
	$result = $dbconn->Execute($query);

	if ($dbconn->ErrorNo() != 0) {
		$this->error = "Error al Cargar el Modulo";
		$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
		return false;
	}
	$Ocupacion=$result->fields[0];
	if(!empty($datos1['ingreso']))
	{
		$query="select observacion_ingreso from autorizaciones where ingreso=".$datos1['ingreso'].";";
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$ObservacionI=$result->fields[0];
	}
	echo "<br><br>";
	echo ThemeAbrirTabla('DATOS PACIENTE');
	echo "	<table width=\"90%\" cellspacing=\"2\" border=\"0\" cellpadding=\"2\" align=\"center\" class=\"normal_10\">";
	echo "		<tr height=\"20\"><td>TIPO DOCUMENTO: </td>";
	echo "		<td class=\"label\">$TipoId</td></tr>";
	echo "		<tr height=\"20\">";
	echo "	  	<td>DOCUMENTO: </td>";
	echo "	  	<td class=\"label\">$PacienteId</td>";
	echo "	 		<td></td>";
	echo "		</tr>";
	echo "		<tr height=\"20\">";
	echo "	  	<td>PRIMER NOMBRE: </td>";
	echo "	  	<td class=\"label\">$datos[primer_nombre]</td>";
	echo "	 		<td></td>";
	echo "		</tr>";
	echo "		<tr height=\"20\">";
	echo "	  	<td>SEGUNDO NOMBRE: </td>";
	echo "	  	<td class=\"label\">$datos[segundo_nombre]</td>";
	echo "	 		<td></td>";
	echo "		</tr>";
	echo "		<tr height=\"20\">";
	echo "	  	<td>PRIMER APELLIDO: </td>";
	echo "	  	<td class=\"label\">$datos[primer_apellido]</td>";
	echo "	  	<td></td>";
	echo "		</tr>";
	echo "		<tr height=\"20\">";
	echo "	  	<td>SEGUNDO APELLIDO: </td>";
	echo "	  	<td class=\"label\">$datos[segundo_apellido]</td>";
	echo "	  	<td></td>";
	echo "		</tr>";
	echo "		<tr>";
	echo "	  	<td>FECHA NACIMIENTO: </td>";
	echo "	  	<td class=\"label\">$datos[fecha_nacimiento]</td>";
	echo "	  	<td width=\"25%\"></td>";
	echo "		</tr>";
	echo "		<tr height=\"20\">";
	echo "	  	<td>DIRECCION: </td>";
	echo "	  	<td class=\"label\">$datos[residencia_direccion]</td>";
	echo "	  	<td></td>";
	echo "		</tr>";
	echo "		<tr height=\"20\">";
	echo "	  	<td>TELEFONO: </td>";
	echo "	  	<td class=\"label\">$datos[residencia_telefono]</td>";
	echo "	  	<td></td>";
	echo "		</tr>";
	echo "		<tr height=\"20\">";
	echo "	  	<td>NOMBRE MADRE: </td>";
	echo "	  	<td class=\"label\">$datos[nombre_madre]</td>";
	echo "	  	<td></td>";
	echo "		</tr>";
	echo "		<tr height=\"20\">";
	echo "	  	<td>OCUPACION: </td>";
	echo "	  	<td class=\"label\">$Ocupacion</td>";
	echo "	  	<td></td>";
	echo "		</tr>";
	echo "		<tr height=\"20\">";
	echo "	  	<td>SEXO: </td>";
	echo "	  	<td class=\"label\">$Sexo</td>";
	echo "	  	<td></td>";
	echo "		</tr>";
	if(!empty($datos1[via_ingreso_nombre]))
	{
		echo "		<tr height=\"20\">";
		echo "	  	<td>CAUSA EXTERNA: </td>";
		echo "	  	<td class=\"label\">$datos1[descripcion]</td>";
		echo "	  	<td></td>";
		echo "		</tr>";
		echo "		<tr height=\"20\">";
		echo "	  	<td>VIA INGRESO: </td>";
		echo "	  	<td class=\"label\">$datos1[via_ingreso_nombre]</td>";
		echo "	  	<td></td>";
		echo "		</tr>";
	}
	if(!empty($ObservacionI))
	{
		echo "		<tr height=\"20\">";
		echo "	  	<td>OBSERVACIÓN INGRESO: </td>";
		echo "	  	<td class=\"label\">$ObservacionI</td>";
		echo "	  	<td></td>";
		echo "		</tr>";
	}
	echo "</table>";
	echo ThemeCerrarTabla();
	print(ReturnFooter());
?>

