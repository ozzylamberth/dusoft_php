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
<title>Editar Foto</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../../../../../apache/htdocs/call/Estilos.css" rel="stylesheet" type="text/css">
<link href="../../../../../apache/htdocs/call/Estilos.css" rel="stylesheet" type="text/css">
</head>
<?php
open_database();
procesar_entrada("GET", "id");
procesar_entrada("POST", "cod_foto", "des_foto", "action", "dir_foto");

$self = $_SERVER["PHP_SELF"]; 
// Variables
$id = get_value($_GET["id"], "C");
$cod_foto = get_value($_POST["cod_foto"], "C");
$des_foto = get_value($_POST["des_foto"], "C");
$action = get_value($_POST["action"], "C");

$image_tempname = $_FILES['dir_foto']['name'];
$ImageDir ="imagenes/fotos/";

$ImageThumb = $ImageDir . "thumbs/";

$ImageName = $ImageDir . $image_tempname;

$mensaje = "";

switch ($action) {
	case "1": // Insertar
		open_database();

		if (empty($cod_foto)) {
			$mensaje = "ERROR: Por favor, introduzca un Código válido.";
			$next_action = "1";
			break;
		}

		// Comprueba que el código no éste dado de alta
		if (existe_valor("FOTOS", "COD_FOTO", $cod_foto, "C")){
			$mensaje = "ERROR: El Código elegido ya está dado de alta";
			$next_action = "1";
			break;
		}



if (move_uploaded_file($_FILES['dir_foto']['tmp_name'], $ImageName)){

  list($width, $height, $type, $attr) = getimagesize($ImageName);

  if ($type > 3) {
    echo "Lo siento, pero el archivo que usted cargo no pertenece a los formatos GIF, JPG O PNG.<BR>";
    echo "Por favor busque otra vez la imagen.";
  }
  else {
$fec_foto = date("d/m/Y");
$sql = "INSERT INTO FOTOS(COD_FOTO, DES_FOTO, FEC_FOTO)
VALUES('" . $cod_foto . "','" . $des_foto . "','" . formatdate($fec_foto) . "')";
		if (execute_query($dbh, $sql)){
			$self = $self . "?id=" . $cod_foto;
			$next_action = "2";
		} else {
            $mensaje = "ERROR: No se pudo ejecutar la consulta.";
			$next_action = $action;
		}
$newfilename = $ImageDir . $cod_foto . ".jpg";

if ($type == 2) {
  rename($ImageName, $newfilename);
  }
else{
  if ($type == 1) {
    $image_old = imagecreatefromgif($ImageName);

  }
  else if ($type == 3) {
    $image_old = imagecreatefrompng($ImageName);
    }
$image_jpg = imagecreatetruecolor($width, $height);
imagecopyresampled($image_jpg, $image_old, 0, 0, 0, 0, $width, $height, $width, $height);
imagejpeg($image_jpg, $newfilename);
imagedestroy($image_old);
imagedestroy($image_jpg);
}


$newthumbname = $ImageThumb . $cod_foto . ".jpg";

$thumb_width = $width * '0.10';
$thumb_height = $height * '0.10';


$largeimage = imagecreatefromjpeg($newfilename);


$thumb = imagecreatetruecolor($thumb_width, $thumb_height);


imagecopyresampled($thumb, $largeimage, 0, 0, 0, 0, $thumb_width, $thumb_height, $width, $height);
imagejpeg($thumb, $newthumbname);
imagedestroy($largeimage);
imagedestroy($thumb);



}
}

		break;
		
		case "2": // Modificar
		open_database();

		if (empty($cod_foto)) {
			$mensaje = "ERROR: Por favor, introduzca un Código válido.";
			$self = $self . "?id=" . $id;
			$next_action = "2";
			break;
		}

		// Comprueba que el código no éste dado de alta
		if ($id != $cod_foto && existe_valor("FOTOS", "COD_FOTO", $cod_foto, "C")){
			$mensaje = "ERROR: El Código elegido ya está dado de alta";
			$self = $self . "?id=" . $id;
			$next_action = "2";
			break;
		}

	 $sql = "UPDATE FOTOS SET "
     . "COD_FOTO = '" . $cod_foto . "'"
     . ",DES_FOTO = '" . $des_foto . "'"
	 . " WHERE COD_FOTO = '" . $id . "'";

    if (execute_query($dbh, $sql)){
			$next_action = "2";
			$self = $self . "?id=" . $cod_foto;
		} else {
		    $mensaje = "ERROR: No se pudo ejecutar la consulta.";
			$next_action = $action;
		}
if (move_uploaded_file($_FILES['dir_foto']['tmp_name'], $ImageName)){

  list($width, $height, $type, $attr) = getimagesize($ImageName);

  if ($type > 3) {
    echo "Lo siento, pero el archivo que usted cargo no pertenece a los formatos GIF, JPG O PNG.<BR>";
    echo "Por favor busque otra vez la imagen.";
  }
  else {
		
$imagen = $ImageDir . $id . ".jpg";
$imagen1 = $ImageThumb . $id . ".jpg";
unlink($imagen);
unlink($imagen1);
$newfilename = $ImageDir . $cod_foto . ".jpg";

if ($type == 2) {
  rename($ImageName, $newfilename);
  }
else{
  if ($type == 1) {
    $image_old = imagecreatefromgif($ImageName);

  }
  else if ($type == 3) {
    $image_old = imagecreatefrompng($ImageName);
    }
$image_jpg = imagecreatetruecolor($width, $height);
imagecopyresampled($image_jpg, $image_old, 0, 0, 0, 0, $width, $height, $width, $height);
imagejpeg($image_jpg, $newfilename);
imagedestroy($image_old);
imagedestroy($image_jpg);
}


$newthumbname = $ImageThumb . $cod_foto . ".jpg";

$thumb_width = $width * '0.10';
$thumb_height = $height * '0.10';


$largeimage = imagecreatefromjpeg($newfilename);


$thumb = imagecreatetruecolor($thumb_width, $thumb_height);


imagecopyresampled($thumb, $largeimage, 0, 0, 0, 0, $thumb_width, $thumb_height, $width, $height);
imagejpeg($thumb, $newthumbname);
imagedestroy($largeimage);
imagedestroy($thumb);



}
}
		break;
		
		case "3": // Eliminar
		$idfoto = $id;
		open_database();
		$sql = "DELETE FROM FOTOS WHERE COD_FOTO = '" . $idfoto . "'";
		execute_query($dbh, $sql);
		$imagen = $ImageDir . $idfoto . ".jpg";
		$imagen1 = $ImageThumb . $idfoto . ".jpg";
		unlink($imagen);
		unlink($imagen1);
	    echo "<script language='Javascript'> self.close(); </script>";
		break;


default:
		if ($id != "") {
			open_database();
			$result = execute_query($dbh, "SELECT * FROM FOTOS WHERE COD_FOTO = '" . $id . "'");
			$row = fetch_object($result);

			if ($row) {
				$cod_foto = $row->COD_FOTO;
				$des_foto = $row->DES_FOTO;
				$self = $self . "?id=" . $cod_foto;
				$next_action = "2";
			} else
				$next_action = "1";

			free_result($result);
		} else{
			$next_action = "1";
			$cod_foto = get_next_val("FOTOS", "COD_FOTO");
			}

} // switch
?>
<body background="imagenes/fondo_bloque.gif">
<?=$mensaje?>
<form enctype="multipart/form-data" name="configuracion" action="<?=$self?>" method="post">
  <table width="49%" border="0" bgcolor="#EEEEEE">
    <tr>
      <td colspan="2" background="imagenes/cellpic1.gif"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Editar
        Foto</font></td>
    </tr>
  <tr>
    <td width="31%">Codigo</td>
    <td width="69%"><input name="cod_foto" type="text" id="cod_foto" value="<?=$cod_foto?>" size="30"></td>
  </tr>
  <tr>
    <td>Nombre de la Foto</td>
    <td><input name="des_foto" type="text" id="des_foto" value="<?=$des_foto?>"></td>
  </tr>
  <tr>
    <td>Imagen</td>
    <td>    <input name="dir_foto" type="file" id="dir_foto" size="50">
<?php

echo "<a href=\"".$ImageDir . $cod_foto . ".jpg\">";
echo "<img src=\"" . $ImageThumb . $cod_foto . ".jpg\" border=\"0\">";

?>
	</td>
  </tr>
      <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="Aceptar" value="Aceptar" onClick="javascript: document.getElementById('action').value = '<?=$next_action?>';"> <input type="button" name="Cancelar" value="Cancelar" OnClick="javascript: self.close();">
<?php if ($next_action == "2") { ?>
    <input type="submit" name="borrar" value="Borrar" onClick="javascript: document.getElementById('action').value='3';">
<?php } ?></td>
    </tr>
  </table>
  <input type="hidden" name="action" value="<?=$next_action?>">
</form>
</body>
</html>
