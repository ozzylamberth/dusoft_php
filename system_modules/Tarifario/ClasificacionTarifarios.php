
<?php

/*
* ClasificacionTarifarios.php  20/07/2004
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @author Lorena Aragón
* @author Jairo Duvan Diaz Martinez
* @author Darling Liliana Dorado
* @version 1.0
* @package SIIS
* Proposito del Archivo: realizar la busqueda de la clasificación de los Grupos y Subgrupos Tarifarios
*/

?>

<head>
<title>CLASIFICACION</title>
<script languaje="javascript" src="GruposTarifariosCargos.js">
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
			$grupotarif=$_REQUEST['grupotarif'];
			$subgrtarif=$_REQUEST['subgrtarif'];
			$Empresa=$_REQUEST['Empresa'];
			$bandera=$_REQUEST['bandera'];
			$Empresa=str_pad($Empresa,2,0,STR_PAD_LEFT);
			list($dbconn) = GetDBconn();
			$query = "SELECT grupo_tarifario_id,
					grupo_tarifario_descripcion
					FROM grupos_tarifarios
					WHERE grupo_tarifario_id<>'00'
					ORDER BY grupo_tarifario_descripcion;";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$query = "SELECT subgrupo_tarifario_id,
					subgrupo_tarifario_descripcion
					FROM subgrupos_tarifarios
					WHERE grupo_tarifario_id='$grupotarif'
					ORDER BY subgrupo_tarifario_descripcion;";
			$result2 = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
?>
			<form name=forma method=GET action="ClasificacionTarifarios.php"><br>
			<table border=0 cellspacing=3 align=center cellpadding=3 valign=bottom width=95% class="modulo_list_oscuro">
			<tr>
			<td>
				<table border=0 cellspacing=3 align=center cellpadding=3 valign=bottom width=95% class="modulo_list_oscuro">
				<tr class="modulo_list_claro">
				<td width=25% nowrap class="label">GRUPO TARIFARIO:
				</td>
				<td colspan=2>
				<select name=grupotarif  class="select" onChange="cambioGrupo(this.value,this.form,<?php echo $Empresa?>)">
				<?php
				if(!empty($grupotarif))
				{
					while(!$result->EOF)
					{
						$Grupo=$result->fields[0];
						if($Grupo==$grupotarif)
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
				<td width=25% nowrap class="label">SUBGRUPO TARIFARIO:
				</td>
				<td colspan=2>
				<select name=subgrtarif  class="select">
				<?php
				if(!empty($subgrtarif))
				{
					while(!$result2->EOF)
					{
						$ClasePr=$result2->fields[0];
						if($ClasePr==$subgrtarif)
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
			<input type=button name=Aceptar class="input-submit" value="ACEPTAR" onClick="ValoresSeleccion(this.form.grupotarif,this.form.subgrtarif,this.form,<?php echo $Empresa?>)">
			</td>
			</tr>
			</table>
			</form>
