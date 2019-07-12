
<?php

/*
* gruposclasesysubclases.php  13/04/2004
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @author Lorena Aragón
* @author Jairo Duvan Diaz Martinez
* @author Darling Liliana Dorado
* @version 1.0
* @package SIIS
* Proposito del Archivo: realizar la busqueda de la clasificación
* de los grupos, clases y subclases de los productos
*/

?>

<head>
<title>CLASIFICACION</title>
<script languaje="javascript" src="GruposTiposCargos.js">
</script>
<?php
			$VISTA='HTML';
			$_ROOT='../../';
			include_once $_ROOT.'includes/enviroment.inc.php';
			include_once $_ROOT.'includes/modules.inc.php';
			include_once $_ROOT.'includes/api.inc.php';
			$filename="themes/$VISTA/" . GetTheme() . "/module_theme.php";
			IncludeFile($filename);
			print(ReturnHeader('CLASIFICACIÓN'));
			print(ReturnBody());
			$grupo=$_REQUEST['grupo'];
			$clasePr=$_REQUEST['clasePr'];
			$Empresa=$_REQUEST['Empresa'];
			$bandera=$_REQUEST['bandera'];
			$Empresa=str_pad($Empresa,2,0,STR_PAD_LEFT);
			list($dbconn) = GetDBconn();
			$query = "SELECT grupo_tipo_cargo,
					descripcion
					FROM grupos_tipos_cargo
					WHERE grupo_tipo_cargo<>'SYS'
					ORDER BY descripcion";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$query = "SELECT tipo_cargo,
					descripcion
					FROM tipos_cargos
					WHERE grupo_tipo_cargo='$grupo'
					ORDER BY descripcion";
			$result2 = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
?>
			<form name=forma method=GET action="ClasificacionGrupos.php"><br>
			<table border=0 cellspacing=3 align=center cellpadding=3 valign=bottom width=95% class="modulo_list_oscuro">
			<tr>
			<td>
				<table border=0 cellspacing=3 align=center cellpadding=3 valign=bottom width=95% class="modulo_list_oscuro">
				<tr class="modulo_list_claro">
				<td width=25% nowrap class="label">GRUPO TIPO CARGO:
				</td>
				<td colspan=2>
				<select name=grupo  class="select" onChange="cambioGrupo(this.value,this.form,<?php echo $Empresa?>)">
				<?php
				if(!empty($grupo))
				{
					while(!$result->EOF)
					{
						$Grupo=$result->fields[0];
						if($Grupo==$grupo)
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
				<td width=25% nowrap class="label">TIPO CARGO:
				</td>
				<td colspan=2>
				<select name=clasePr  class="select">
				<?php
				if(!empty($clasePr))
				{
					while(!$result2->EOF)
					{
						$ClasePr=$result2->fields[0];
						if($ClasePr==$clasePr)
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
			<input type='hidden' name='bandera' value='<?echo $bandera?>' class='input-text'>
			<?php
			if($bandera==1)
			{
				?>
				<input type=button name=Aceptar class="input-submit" value="ACEPTAR" onClick="ParametrosBusqueda(this.form.grupo,this.form.clasePr,this.form,<?php echo $Empresa?>)">
				<?php
			}
			else
			{
				?>
				<input type=button name=Aceptar class="input-submit" value="ACEPTAR" onClick="ValoresSeleccion(this.form.grupo,this.form.clasePr,this.form,<?php echo $Empresa?>)">
				<?php
			}
			?>
			</td>
			</tr>
			</table>
			</form>
