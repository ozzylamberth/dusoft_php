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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Documento sin t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="Estilos.css" rel="stylesheet" type="text/css">
</head>
<script language="JavaScript">
function ponerUsuario(idusuario, usuario, nombre, admin){
	document.getElementById("id").value = idusuario;
	document.getElementById("usuario").value = usuario;
	document.getElementById("nombre").value = nombre;
	if (admin == "1")
		document.getElementById("admin").checked = true;
	else
		document.getElementById("admin").checked = false;
}
</script>
<body background="imagenes/fondo_bloque.gif">
<?php
procesar_entrada("POST", "action", "usuario", "nombre", "passwd", "admin", "id");

open_database();
$id = get_value($_POST["id"], "N");
$action = get_value($_POST["action"], "C");

$usuario = get_value($_POST["usuario"], "C");
$nombre = get_value($_POST["nombre"], "C");
$passwd = get_value($_POST["passwd"], "C");
$admin = get_value($_POST["admin"], "N");

$mensaje = "";

switch ($action) {
    case "1": // Inserta un nuevo usuario
		if (empty($usuario) || empty($passwd) || empty($nombre)){
			$mensaje = "ERROR: Introduzca todos los datos de usuario.";
			break;
		}
		
		if (existe_valor("USUARIOS", "USUARIO", $usuario, "C")){
			$mensaje = "ERROR: Ya existe un usuario con ese identificador";
			break;
		}
		
        $id = get_next_val("USUARIOS", "IDUSUARIO");
        $sql = "INSERT INTO USUARIOS(IDUSUARIO, USUARIO, NOMBRE, PASSWD, ADMINISTRADOR) " . " VALUES(" . $id . ",'" . $usuario . "','" . $nombre . "','" . md5($passwd) . "'," . $admin . ");";
        execute_query($dbh, $sql);
        break;

    case "2": // Modifica un usuario
		if (empty($id)){
			$mensaje = "ERROR: Por favor, seleccione un usuario a editar.";
			break;
		}
		
		if (empty($usuario) || empty($passwd) || empty($nombre)){
			$mensaje = "ERROR: Introduzca todos los datos de usuario.";
			break;
		}
		
        $sql = "UPDATE USUARIOS SET "
         . "  NOMBRE = '" . $nombre . "'"
         . ", PASSWD = '" . md5($passwd) . "'"
         . ", ADMINISTRADOR = " . $admin . " WHERE IDUSUARIO = " . $id;
        execute_query($dbh, $sql);
        break;

    case "3": // Eliminar usuario
        $sql = "DELETE FROM USUARIOS WHERE IDUSUARIO = " . $id;
        execute_query($dbh, $sql);
        break;
} 

?>
<form name="usuarios" method="post" action="editarusuario.php">
<?=$mensaje?>
<table width="100%" border="0" cellspacing="0">

  <tr>
    <td width="14%" background="imagenes/cellpic1.gif">&nbsp;</td>
    <td width="14%" background="imagenes/cellpic1.gif"><strong><font color="#003366">Usuario</font></strong></td>
    <td width="47%" background="imagenes/cellpic1.gif"><strong><font color="#003366">Nombre</font></strong></td>
    <td width="39%" background="imagenes/cellpic1.gif"><strong><font color="#003366">Administrador</font></strong></td>
  </tr>
<?php
$sql = "SELECT * FROM USUARIOS ORDER BY USUARIO, NOMBRE";
$result = execute_query($dbh, $sql);
while (($row = fetch_object($result))) {
?>
  <tr bgcolor="#EEEEEE" onMouseOver="javascript: this.style.backgroundColor = '#CC9900';" onMouseOut="javascript: this.style.backgroundColor = '#EEEEEE';">
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
      <input type="button" name="Submit2" value="Borrar" onClick="javascript: document.getElementById('id').value = <?=$row->IDUSUARIO?>; document.getElementById('action').value = 3; document.forms[0].submit();">
    </font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
      <a href="#" onClick="javascript: ponerUsuario('<?=$row->IDUSUARIO?>', '<?=$row->USUARIO?>', '<?=$row->NOMBRE?>', '<?=$row->ADMINISTRADOR?>');"><?=$row->USUARIO?></a>
</font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
      <?=$row->NOMBRE?>
    </font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
      <?=($row->ADMINISTRADOR)?"Si":"No"?>
    </font></td>
  </tr>	 
<?php
} 
free_result($result);
?>
</table>
<p>&nbsp;</p>
<p><strong>Insertar/Editar usuario</strong><br>
  Usuario
  <input name="usuario" type="text" class="textbox" id="usuario" size="15" maxlength="10">
  Nombre
  <input name="nombre" type="text" class="textbox" id="nombre" maxlength="50">
  Password
  <input name="passwd" type="password" id="passwd" size="14" maxlength="6">
  <input name="admin" type="checkbox" id="admin" value="1">
  Administrador
  <input type="submit" name="Submit" value="Insertar">
  <input type="submit" name="Submit3" value="Modificar" onClick="javascript: document.getElementById('action').value = 2;">
  <input type="hidden" name="action" value="1">
  <input type="hidden" name="id" id="id">
  <br>
  <br>
</p>
</form>
</body>
</html>
