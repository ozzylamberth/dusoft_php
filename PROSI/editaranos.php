<?php
require("includes/session.php");
require("includes/config.php");
require("includes/database.php");
require("includes/funciones.php");

if ($_SESSION["administrador"] != 1) {
    header("Location: noacceso.html");
    exit;
} 


?>
<html>
<head>
<title>Documento sin t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="Estilos.css" rel="stylesheet" type="text/css">
</head>
<script language="JavaScript">
function validar(){
	if (document.getElementById("action").value == "3"){
		if (!confirm("Si elimina año contable perderá todos sus albaranes y facturas asociadas. ¿Desea continuar?"))
			return false
	}
	
	return true;
}
</script>
<?php

open_database();

$self = $_SERVER["PHP_SELF"];

procesar_entrada("POST", "action", "ano", "anoactivo");

$action = get_value($_POST["action"], "C");

$ano = get_value($_POST["ano"], "N");
$anoactivo = get_value($_POST["anoactivo"], "N");

switch ($action) {
    case "1": // Insertar anyo
        $sql = "INSERT INTO ANOS(ANO, ACTIVO) VALUES(" . $ano . ",0);";
        execute_query($dbh, $sql);
        break;
    case "2": // Seleccionar anyo activo por defecto
        $sql = "UPDATE ANOS SET ACTIVO = 0 WHERE ACTIVO = 1;";
        execute_query($dbh, $sql);
        $sql = "UPDATE ANOS SET ACTIVO = 1 WHERE ANO = " . $anoactivo;
        execute_query($dbh, $sql);
        break;
		
	case "3": // Eliminar año contable
		$sql = "DELETE FROM ANOS WHERE ANO = ". $anoactivo;
		execute_query($dbh, $sql);
		break;
} 

?>
<body background="imagenes/fondo_bloque.gif">
<form name="anyos" method="post" action="<?=$self?>" onSubmit="javascript: return validar();">
<p><strong>A&ntilde;os contables</strong><br>
<?php
$result = execute_query($dbh, "SELECT * FROM ANOS ORDER BY ANO");
?>
  <select name="anos" size="6" id="anos">
<?php
while (($row = fetch_object($result))) {
?>
	<option value="<?=$row->ANO?>" <?=($row->ACTIVO == 1)?"selected":""?>> <?=$row->ANO?></option>
<?php } 
free_result($result);
?>
  </select>
  <br>
  <br>
  <strong>A&ntilde;o activo por defecto</strong><br>
<?php
$result = execute_query($dbh, "SELECT * FROM ANOS ORDER BY ANO");
?>
  <select name="anoactivo" id="anoactivo">
<?php
while (($row = fetch_object($result))) {
?>
	<option value="<?=$row->ANO?>" <?=($row->ACTIVO == 1)?"selected":""?>> <?=$row->ANO?></option>
<?php } 
free_result($result);
?>
  </select>
  <input type="button" name="Submit2" value="Activar" onClick="javascript: document.getElementById('action').value = 2; document.forms[0].submit();">
  <input type="submit" name="borrar" value="Borrar" onClick="javascript: document.getElementById('action').value='3';">
  <br>
  <br>
  <strong>Insertar a&ntilde;o</strong><br>
  <input name="ano" type="text" id="ano" class="textbox">
  <input type="submit" name="Submit" value="Insertar" onClick="javascript: document.getElementById('action').value = '1';">
  <br>
  <br>
  <input type="hidden" name="action" value="1">
</form>
</p>
</body>
</html>
