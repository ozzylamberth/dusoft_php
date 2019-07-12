<?php
require("includes/session.php");
require("includes/config.php");
require("includes/database.php");
require("includes/funciones.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Documento sin t&iacute;tulo</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="Estilos.css" rel="stylesheet" type="text/css">
</head>
<?php
open_database();

?>
<body bgcolor="#FFFFFF" topmargin="0">
<div align="left"> 
  <table width="100%" border="0">
    <tr> 
      <td width="70%" height="22"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000"><img src="imagenes/logo_clinica.bmp" width="100" height="80" align="middle"><strong> 
        <font size="3">MANEJO DE INFORMACION DEL PROYECTO SIIS</font></strong>versi&oacute;n <?=$version?></font></td>
      <td width="30%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000"> 
       <div align="right"> 
	          <?php
echo "<b>Usuario activo:</B> " . $_SESSION["nombre_usuario"];

?><br>
          

        </div>
        </font></td>
        
    </tr>
  </table>
  
</div>
</body>
</html>
