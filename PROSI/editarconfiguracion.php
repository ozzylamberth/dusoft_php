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

<body background="imagenes/fondo_bloque.gif">
<?php
open_database();

procesar_entrada("POST", "empresa", "cif", "dircompleta", "telefono", "action", "logo");

$self = $_SERVER["PHP_SELF"]; 
// Variables
$empresa = get_value($_POST["empresa"], "C");
$cif = get_value($_POST["cif"], "C");
$dircompleta = get_value($_POST["dircompleta"], "C");
$telefono = get_value($_POST["telefono"], "C");
$action = get_value($_POST["action"], "C");

if (isset($HTTP_POST_FILES["logo"])){
	if ($HTTP_POST_FILES["logo"]["name"] != "") {
		$path_parts = pathinfo($_SERVER['PATH_TRANSLATED']);
		$path = $path_parts["dirname"]."/imagenes/";
		copy($HTTP_POST_FILES['logo']['tmp_name'], $path . $HTTP_POST_FILES['logo']['name']);
		$dirimagen = "imagenes/" . $HTTP_POST_FILES["logo"]["name"];
	} 
}

if ($action == "1") {
    $sql = "UPDATE CONFIGURACION SET "
     . "EMPRESA = '" . $empresa . "'"
     . ",CIF = '" . $cif . "'"
     . ",DIRCOMPLETA = '" . $dircompleta . "'"
     . ",TELEFONO = '" . $telefono . "'";

    if (isset($dirimagen)) {
        $sql .= ",LOGO = '" . $dirimagen . "'";
    } 

    execute_query($dbh, $sql);
} 

$result = execute_query($dbh, "SELECT * FROM CONFIGURACION");
if (($row = fetch_object($result))) {
    $empresa = $row->EMPRESA;
    $cif = $row->CIF;
    $dircompleta = $row->DIRCOMPLETA;
    $telefono = $row->TELEFONO;
    $dirimagen = $row->LOGO;
} 
free_result($result);

?>
<p><strong>Editar configuraci&oacute;n</strong></p>
<form enctype="multipart/form-data" name="configuracion" action="<?=$self?>" method="post">
<table width="60%" border="0">
  <tr>
    <td width="31%" align="right">Empresa</td>
    <td width="69%"><input name="empresa" type="text" id="empresa" value="<?=$empresa?>" size="30"></td>
  </tr>
  <tr>
    <td align="right">CIF</td>
    <td><input name="cif" type="text" id="cif" value="<?=$cif?>"></td>
  </tr>
  <tr>
    <td align="right" valign="top">Imagen</td>
    <td>    <input name="logo" type="file" id="logo" size="50">
<?php
if ($dirimagen != "") {
?>
  <img src="<?=$dirimagen?>">
<?php } ?>
	</td>
  </tr>
  <tr>
    <td align="right" valign="top">Direcci&oacute;n completa</td>
    <td><textarea name="dircompleta" cols="50" rows="5" id="dircompleta"><?=$dircompleta?>
    </textarea></td>
  </tr>
  <tr>
    <td align="right">Tel&eacute;fono</td>
    <td><input name="telefono" type="text" id="telefono" value="<?=$telefono?>"></td>
  </tr>
  <tr>
    <td align="right">&nbsp;</td>
    <td><input type="submit" name="Submit" value="Guardar cambios">
	<input type="hidden" name="action" value="1"></td>
  </tr>
</table>
</form>
</body>
</html>
