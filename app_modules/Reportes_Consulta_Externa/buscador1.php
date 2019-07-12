
<?php

/*
* ServiciosDepartamentos.php  21/08/2004
* @author Tizziano Perea Ocoro <tizzianop@gmail.com>
* @version 1.0
* @package SIIS
* Proposito del Archivo: realizar la busqueda de los departamentos según el Centro de Uitilidad elegidocio elegido.
*/

?>

<head>
<title>CLASIFICACION</title>
<script languaje="javascript" src="Servicios1.js">
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
			
               $centro=$_REQUEST['centro'];               
               $unidad=$_REQUEST['unidad'];
			$departam=$_REQUEST['departam'];
			list($dbconn) = GetDBconn();
			$query = "SELECT centro_utilidad,
               			  descripcion AS centro
					FROM   centros_utilidad
					WHERE  empresa_id='".$_SESSION['recoex']['empresa']."'
					ORDER BY centro_utilidad;";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$query = "SELECT unidad_funcional,
               			  descripcion AS unidad
					FROM   unidades_funcionales
					WHERE empresa_id='".$_SESSION['recoex']['empresa']."'
					AND centro_utilidad='".$centro."'
					ORDER BY unidad;";

			$result2 = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}      

?>
			<form name=forma method=GET action="buscador1.php"><br>
			<table border=0 cellspacing=3 align=center cellpadding=3 valign=bottom width=95% class="modulo_list_oscuro">
			<tr>
			<td>
				<table border=0 cellspacing=3 align=center cellpadding=3 valign=bottom width=95% class="modulo_list_oscuro">
				<tr class="modulo_list_claro">
				<td width=25% nowrap class="label">CENTRO DE UTILIDAD:
				</td>
				<td colspan=2>
				<select name=centro  class="select" onChange="cambioGrupo(this.value,this.form)">
				<?php
				if(!empty($centro))
				{
					while(!$result->EOF)
					{
						$Grupo=$result->fields[0];
						if($Grupo==$centro)
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
					echo ("<option value=-1> --- SELECCIONE --- </option>");
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
				<td width=25% nowrap class="label">UNIDAD FUNCIONAL:
				</td>
				<td colspan=2>
				<select name=unidad  class="select" onChange="cambioGrupo2(this.value,this.form,'<?php echo $_REQUEST['centro']?>')">
				<?php
				if(!empty($unidad))
				{
					while(!$result2->EOF)
					{
						$Clase=$result2->fields[0];
						if($Clase==$unidad)
						{
						 	?><option value= "<? echo $Clase?>" selected><? echo $result2->fields[1] ?></option><?
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
					echo ("<option value=-1> --- SELECCIONE --- </option>");
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
			<input type=button name=Aceptar class="input-submit" value="ACEPTAR" onClick="ValoresSeleccion(this.form.centro,this.form.unidad,this.form)">
			</td>
			</tr>
			</table>
			</form>
