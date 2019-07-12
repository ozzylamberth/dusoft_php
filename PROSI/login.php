<?php
/*require("includes/config.php");*/
require("includes/database.php");
require("includes/funciones.php");

procesar_entrada("POST", "usuario", "password");

$usuario = get_value($_POST["usuario"], "C");
$password = get_value($_POST["password"], "C");
$login_ok = false;

if ($usuario && $password) {
    open_database();
    $result = execute_query($dbh, "SELECT * FROM system_usuarios WHERE usuario = '" . $usuario . "'");
    if (($row = fetch_object($result)))
    
        if (md5($password) == $row->passwd) {
            session_start();
            
            $_SESSION["nombre_usuario"] = $row->nombre;
            $_SESSION["usuario_id"] = $row->usuario_id;
            free_result($result);
            
            	$result_admin = execute_query($dbh, "SELECT * FROM system_usuarios_departamentos 
				WHERE usuario_id = " . $_SESSION["usuario_id"] . " AND departamento = '011401'");
    			if (($row1 = fetch_object($result_admin)))
    			$_SESSION["administrador"] = 1;
    			else
           		$_SESSION["administrador"] = 0;
           		free_result($result_admin);
            header("Location: index.php");
            exit;
        }
		ELSE{
			
		} 
        free_result($result);
    } 

    ?>
<html>
<head>
<title>Login </title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../PROSI/Estilos.css" rel="stylesheet" type="text/css">
</head>
<body background="../PROSI/imagenes/fondo_bloque.gif">
<form name="usuario" action="<?=$_SERVER['PHP_SELF']?>" method="post">
<p>&nbsp;</p>
<p>&nbsp;</p>
<p align="center"><font size="3" face="Verdana, Arial, Helvetica, sans-serif"><strong>Acceso
      al Programa Interno del Proyecto SIIS</strong></font></p>
	  <div align="center"><img src='imagenes/logo_siis.jpg' alt='' width="282" height="218" >	  </div>
<table width="275" border="0" align="center">
  <tr>
    <td colspan="2" background="../PROSI/imagenes/cellpic1.gif"><strong><font color="#003366">Login</font></strong></td>
    </tr>
  <tr>
    <td><div align="right">Usuario</div></td>
    <td><input name="usuario" type="text" id="usuario" maxlength="10" class="textbox"></td>
  </tr>
  <tr>
    <td><div align="right">Password</div></td>
    <td><input name="password" type="password" id="password" maxlength="40" class="textbox"></td>
  </tr>
  <tr>
    <td height="26">&nbsp;</td>
    <td><input type="submit" name="Submit" value="Aceptar">
    </td>
  </tr>
  <tr>
    <td colspan="2" background="../PROSI/imagenes/cellpic1.gif">&nbsp;</td>
    </tr>
</table>
</form>
</body>
</html>
