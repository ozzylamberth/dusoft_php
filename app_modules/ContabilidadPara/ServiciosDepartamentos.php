
<?php

/*
* ServiciosDepartamentos.php  21/08/2004
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
* Proposito del Archivo: realizar la busqueda de los departamentos según el servicio elegido
*/

?>

<head>
<title>CLASIFICACION</title>
<script languaje="javascript" src="Servicios.js">
</script>
<?php
			$VISTA='HTML';
			$_ROOT='../../';
			include_once $_ROOT.'includes/enviroment.inc.php';
			include_once $_ROOT.'includes/modules.inc.php';
			include_once $_ROOT.'includes/api.inc.php';
			$filename="themes/$VISTA/" . GetTheme() . "/module_theme.php";
			IncludeFile($filename);
			print(ReturnHeader('SERVICIOS - DEPARTAMENTOS'));
			print(ReturnBody());
			$servicio=$_REQUEST['servicio'];
			$departam=$_REQUEST['departam'];
			list($dbconn) = GetDBconn();
			$query = "SELECT servicio,
					descripcion
					FROM servicios
					WHERE servicio<>'0'
					ORDER BY servicio;";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$query = "SELECT departamento,
					descripcion
					FROM departamentos
					WHERE empresa_id='".$_SESSION['conpar']['empresa']."'
					AND servicio='".$servicio."'
					ORDER BY descripcion;";
			$result2 = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
?>
			<form name=forma method=GET action="ServiciosDepartamentos.php"><br>
			<table border=0 cellspacing=3 align=center cellpadding=3 valign=bottom width=95% class="modulo_list_oscuro">
			<tr>
			<td>
				<table border=0 cellspacing=3 align=center cellpadding=3 valign=bottom width=95% class="modulo_list_oscuro">
				<tr class="modulo_list_claro">
				<td width=25% nowrap class="label">SERVICIO:
				</td>
				<td colspan=2>
				<select name=servicio  class="select" onChange="cambioGrupo(this.value,this.form)">
				<?php
				if(!empty($servicio))
				{
					while(!$result->EOF)
					{
						$Grupo=$result->fields[0];
						if($Grupo==$servicio)
						{
							?><option value= "<? echo $Grupo ?>" selected><? echo $result->fields[1]?></option><?
						}
						else
						{
							?><option value= "<? echo $result->fields[0]?>"><? echo $result->fields[1] ?></option><?
						}
						$result->MoveNext();
					}
				}
				else
				{
					echo ("<option value=-1>SELECCIONE</option>");
					while (!$result->EOF)
					{
						?><option value= "<? echo $result->fields[0] ?>"><? echo $result->fields[1] ?></option><?
						$result->MoveNext();
					}
					$result->close();
				}
				?>
				</select>
				</td>
				</tr>
				<tr class="modulo_list_claro">
				<td width=25% nowrap class="label">DEPARTAMENTO:
				</td>
				<td colspan=2>
				<select name=departam  class="select">
				<?php
				if(!empty($departam))
				{
					while(!$result2->EOF)
					{
						$ClasePr=$result2->fields[0];
						if($ClasePr==$departam)
						{
						 	?><option value= "<? echo $ClasePr?>" selected><? echo $result2->fields[1] ?></option><?
						}
						else
						{
							?><option value= "<? echo $result2->fields[0]?>"><? echo $result2->fields[1] ?></option><?
						}
						$result2->MoveNext();
					}
				}
				else
				{
					echo ("<option value=-1>SELECCIONE</option>");
					while (!$result2->EOF)
					{
						?><option value= "<? echo $result2->fields[0] ?>"><? echo $result2->fields[1] ?></option><?
						$result2->MoveNext();
					}
					$result2->close();
				}
				?>
				</select>
				</td>
				</tr>
				</table>
			</td>
			</tr>
			</table>
			<table border=0 cellspacing=3 align=center cellpadding=3 valign=bottom width=95%>
			<tr>
			<td align=center colspan=3>
			<input type=button name=Aceptar class="input-submit" value="ACEPTAR" onClick="ValoresSeleccion(this.form.servicio,this.form.departam,this.form)">
			</td>
			</tr>
			</table>
			</form>
