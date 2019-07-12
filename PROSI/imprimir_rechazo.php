<html>
<head>
<title>Documento Rechazo Servicio Urgencias</title>
</head>
<body>


<?php 

require("includes/session.php");
/*require("includes/config.php");*/
require("includes/database.php");
require("includes/funciones.php");
procesar_entrada("GET", "rechazo_id");
$rechazo_id = get_value($_GET["rechazo_id"], "C");

open_database();


$result = execute_query($dbh, "SELECT a.rechazo_id,
								a.edad,
								e.descripcion as servicio_solicitado,
								b.descripcion as causa_rechazo,
								c.descripcion as entidad_solicita,
								f.nombre_tercero,
								d.nombre,
								TO_CHAR(a.fecha_registro, 'YYYY-MM-DD HH:MM:SS') as fecha_registro, 
								a.tipo_id_paciente, 
								a.paciente_id, 
								a.primer_nombre, 
								a.segundo_nombre, 
								a.primer_apellido, 
								a.segundo_apellido,
								a.descripcion_diagnostico,
								a.funcionario_externo,
								a.observacion,
								g.nombre as nombre_usuario
 		FROM (((prosi_rechazos a INNER JOIN prosi_motivos_rechazo b ON a.causa_rechazo=b.causa_rechazo) 
	INNER JOIN centros_remision c ON a.entidad_solicita=c.centro_remision) 
	INNER JOIN profesionales d ON (a.tipo_id_tercero=d.tipo_id_tercero) AND (a.tercero_id=d.tercero_id))
	INNER JOIN terceros f ON (a.entidad_tipo_id_tercero=f.tipo_id_tercero) AND (a.entidad_tercero_id=f.tercero_id) 
	INNER JOIN departamentos e ON a.servicio_solicitado=e.departamento
	INNER JOIN system_usuarios g ON a.usuario_id=g.usuario_id 
	WHERE rechazo_id = '" . $rechazo_id . "'");
	
$row = fetch_object($result);

?>

<table align="CENTER" width="650" height="100" border="1">
<tr>
	<td colspan="2" height="30" align="center">
	<img src="img/logo_clinica.bmp" WIDTH="200" HEIGHT="140">
	</td>
<tr>
	<td width="50%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Rechazo: </strong><?=$row->rechazo_id?></font></td>
	<td width="50%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Fecha Registro: </strong><?=$row->fecha_registro?></font></td>
</tr>
<tr>
	<td width="50%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Identificacion: </strong><?=$row->tipo_id_paciente." ".$row->paciente_id?></font></td>
	<td width="50%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Nombre: </strong><?=$row->primer_nombre." ".$row->segundo_nombre." ".$row->primer_apellido." ".$row->segundo_apellido?></font></td>
</tr>
<tr>
	<td colspan="2"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Edad: </strong><?=$row->edad?></font></td>
</tr>
<tr>
	<td colspan="2"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Entidad: </strong><?=$row->nombre_tercero?></font></td>
</tr>
<tr>
	<td colspan="2"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Diagnostico: </strong><?=$row->descripcion_diagnostico?></font></td>
</tr>
<tr>
	<td colspan="2"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Servicio Solicitado: </strong><?=$row->servicio_solicitado?></font></td>
</tr>
<tr>
	<td colspan="2"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Clinica: </strong><?=$row->entidad_solicita?></font></td>
</tr>
<tr>
	<td colspan="2"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Funcionario: </strong><?=$row->funcionario_externo?></font></td>
</tr>
<tr>
	<td width="50%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Causa Rechazo: </strong><?=$row->causa_rechazo?></font></td>
	<td width="50%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Profesional: </strong><?=$row->nombre?></font></td>
</tr>
<tr>
	<td colspan="2" height="60"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Observacion: </strong><?=$row->observacion?></font></td>
</tr>
<tr>
	<td colspan="2"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>Elaborado por: </strong><?=$row->nombre_usuario?></font></td>
</tr>
<tr>
	<td></td>
	<td></td>
</tr>
</table>
</body>
</html>