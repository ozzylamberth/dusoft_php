
<?php

/*
* hc_convenciones.php  04/04/2005
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
* Proposito del Archivo: Cargar la imagen de las convenciones de odontologia
*/

?>

<head>
<?php
			$VISTA='HTML';
			$_ROOT='../../';
			include_once $_ROOT.'includes/enviroment.inc.php';
			include_once $_ROOT.'includes/modules.inc.php';
			include_once $_ROOT.'includes/api.inc.php';
			$filename="themes/$VISTA/" . GetTheme() . "/module_theme.php";
			IncludeFile($filename);
			$imagen=GetThemePath()."/images/simbolos1.png";
			print (ReturnHeader('CONVENCIONES'));
			print(ReturnBody());
?>

			<form name=forma method=GET>
			<table border=0 align=center valign=bottom width=100%>
			<tr>
			<td align=center>
			<img src='<?php echo $imagen?>' border=\"0\">
			</td>
			</tr><br>
			<tr>
			<td align=center>
			<input type=submit name=cerrar class="input-submit" value="CERRAR" onClick="window.close()">
			</td>
			</tr>
			</table>
			</form>
