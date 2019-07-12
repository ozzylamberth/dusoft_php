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

open_database();

procesar_entrada("GET", "rechazo_id", "tipo_id_paciente", "paciente_id", 
"nombre", "apellido", "servicio_solicitado", "entidad_solicita", "causa_rechazo", 
"tipo_id_tercero","tercero_id","entidad_tipo_id_tercero", "entidad_tercero_id", "desdefecha", "hastafecha");



$rechazo_id = get_value($_GET["rechazo_id"], "N");
$tipo_id_paciente = get_value($_GET["tipo_id_paciente"], "C");
$paciente_id = get_value($_GET["paciente_id"], "C");
$nombre = get_value($_GET["nombre"], "C");
$apellido = get_value($_GET["apellido"], "C");
$servicio_solicitado = get_value($_GET["servicio_solicitado"], "C");
$entidad_solicita = get_value($_GET["entidad_solicita"], "C");
$causa_rechazo = get_value($_GET["causa_rechazo"], "C");
$tipo_id_tercero = get_value($_GET["tipo_id_tercero"], "C");
$tercero_id = get_value($_GET["tercero_id"], "C");
$entidad_tipo_id_tercero = get_value($_GET["entidad_tipo_id_tercero"], "C");
$entidad_tercero_id = get_value($_GET["entidad_tercero_id"], "C");
$desdefecha = get_value($_GET["desdefecha"], "C");
$hastafecha = get_value($_GET["hastafecha"], "C");

$query = "SELECT a.rechazo_id,
				a.edad,
				e.descripcion as servicio_solicitado,
				b.descripcion as causa_rechazo,
				d.nombre,
				c.descripcion as centro_remision,
				f.nombre_tercero as entidad,
				TO_CHAR(a.fecha_registro, 'YYYY-MM-DD') as fecha_registro, 
				a.tipo_id_paciente, 
				a.paciente_id, 
				a.primer_nombre, 
				a.segundo_nombre, 
				a.primer_apellido, 
				a.segundo_apellido,
				a.descripcion_diagnostico,
				g.nombre as nombre_usuario
 		FROM (((prosi_rechazos a INNER JOIN prosi_motivos_rechazo b ON a.causa_rechazo=b.causa_rechazo) 
	INNER JOIN centros_remision c ON a.entidad_solicita=c.centro_remision) 
	INNER JOIN profesionales d ON (a.tipo_id_tercero=d.tipo_id_tercero) AND (a.tercero_id=d.tercero_id))
	INNER JOIN terceros f ON (a.entidad_tipo_id_tercero=f.tipo_id_tercero) AND (a.entidad_tercero_id=f.tercero_id) 
	INNER JOIN departamentos e ON a.servicio_solicitado=e.departamento
	INNER JOIN system_usuarios g ON a.usuario_id=g.usuario_id";
	
$where = build_where("a.rechazo_id", $rechazo_id, "N",
    "a.tipo_id_paciente", $tipo_id_paciente, "C",
    "a.paciente_id", $paciente_id, "C",
    "a.primer_nombre||a.segundo_nombre", $nombre, "C",
    "a.primer_apellido||a.segundo_apellido", $apellido, "C",
    "e.departamento", $servicio_solicitado, "C",
    "c.centro_remision", $entidad_solicita, "C",
    "b.causa_rechazo", $causa_rechazo, "C",
    "d.tipo_id_tercero", $tipo_id_tercero, "C",
    "d.tercero_id", $tercero_id, "C",
	"f.tipo_id_tercero", $entidad_tipo_id_tercero, "C",
	"f.tercero_id", $entidad_tercero_id, "C");
    

$filtrofecha = build_beetwen("a.fecha_registro", formatdate($desdefecha), formatdate($hastafecha), "C");

if ($where && $filtrofecha) 
	$where .= " AND ";
$where .= $filtrofecha;

$order = "1";

require("includes/consulta.php");	

?>

<table align="CENTER" cellpadding="1" cellspacing="1" border="1" name="Reporte Rechazos" width="100%">
<tr>
	<td colspan="9" align="center">
	<img src="img/logo_clinica.bmp" WIDTH="200" HEIGHT="140">
	</td>
</tr>
<tr>
	<td colspan="9" align="center">
	<font size="3" face="Verdana, Arial, Helvetica, sans-serif"><strong><u>REPORTE DE RECHAZOS SERVICIO DE URGENCIAS</u></strong></font>
	</td>
</tr>	
<tr>
	<td width="14%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>Paciente</strong></font></td>
	<td width="3%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>Edad</strong></font></td>
	<td width="12%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>Diagnostico</strong></font></td>
	<td width="12%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>Servicio Solicitado</strong></font></td>
	<td width="17%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>Clinica/EPS</strong></font></td>
	<td width="10%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>Causa Rechazo</strong></font></td>
	<td width="14%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>Profesional</strong></font></td>
	<td width="10%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>Usuario</strong></font></td>
	<td width="8%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>Fecha Registro</strong></font></td>
</tr>
<?
while ($row = fetch_object($result)) {
?>
<tr>
	<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $row->tipo_id_paciente." ".$row->paciente_id." ".$row->primer_nombre." ".$row->segundo_nombre." ".$row->primer_apellido." ".$row->segundo_apellido?></font></td>
	<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $row->edad?></font></td>
	<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $row->descripcion_diagnostico?></font></td>
	<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $row->servicio_solicitado?></font></td>
	<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $row->centro_remision." / ".$row->entidad?></font></td>
	<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $row->causa_rechazo?></font></td>
	<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $row->nombre?></font></td>
	<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $row->nombre_usuario?></font></td>
	<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $row->fecha_registro?></font></td>
</tr>
<?
}
?>
</table>
</body>
</html>