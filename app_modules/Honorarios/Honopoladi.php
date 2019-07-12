
<?php

/*
* ClasificacionTarifarios.php  25/09/2004
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
* Proposito del Archivo:
*/

?>

<head>
<title>CLASIFICACION</title>
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
			$indice=$_REQUEST['indice'];
			list($dbconn) = GetDBconn();
			if($_REQUEST['caso']==1)
			{
				$nombre=$_SESSION['honor3']['grupospolh']['nombreprof'];
				$tipodo=$_SESSION['honor3']['grupospolh']['poolidprof'];
				$grupod=$_SESSION['honor3']['lgrupocaho'][$_REQUEST['indice']]['des1'];
				$gtipod=$_SESSION['honor3']['lgrupocaho'][$_REQUEST['indice']]['des2'];
				$des1='GRUPO TIPO CARGO:';
				$des2='TIPO CARGO:';
				$query = "SELECT A.porcentaje,
						B.descripcion,
						C.plan_descripcion
						FROM prof_honorarios_pool_grupos AS A
						LEFT JOIN servicios AS B ON
						(A.servicio=B.servicio)
						LEFT JOIN planes AS C ON
						(A.plan_id=C.plan_id)
						WHERE A.empresa_id='".$_SESSION['honora']['empresa']."'
						AND A.prof_pool_id='".$_SESSION['honor3']['grupospolh']['poolidprof']."'
						AND A.grupo_tipo_cargo='".$_SESSION['honor3']['lgrupocaho'][$_REQUEST['indice']]['grupo_tipo_cargo']."'
						AND A.tipo_cargo='".$_SESSION['honor3']['lgrupocaho'][$_REQUEST['indice']]['tipo_cargo']."'
						AND (A.servicio IS NOT NULL
						OR A.plan_id IS NOT NULL);";
			}
			else if($_REQUEST['caso']==2)
			{
				$nombre=$_SESSION['honor3']['gruserpolh']['nombreprof'];
				$tipodo=$_SESSION['honor3']['gruserpolh']['poolidprof'];
				$grupod=$_SESSION['honor3']['lgruservho'][$_REQUEST['indice']]['des1'];
				$gtipod=$_SESSION['honor3']['lgruservho'][$_REQUEST['indice']]['des2'];
				$des1='GRUPO TIPO CARGO:';
				$des2='TIPO CARGO:';
				$query = "SELECT A.porcentaje,
						B.descripcion,
						C.plan_descripcion
						FROM prof_honorarios_pool_grupos AS A
						LEFT JOIN servicios AS B ON
						(A.servicio=B.servicio)
						LEFT JOIN planes AS C ON
						(A.plan_id=C.plan_id)
						WHERE A.empresa_id='".$_SESSION['honora']['empresa']."'
						AND A.prof_pool_id='".$_SESSION['honor3']['gruserpolh']['poolidprof']."'
						AND A.grupo_tipo_cargo='".$_SESSION['honor3']['lgruservho'][$_REQUEST['indice']]['grupo_tipo_cargo']."'
						AND A.tipo_cargo='".$_SESSION['honor3']['lgruservho'][$_REQUEST['indice']]['tipo_cargo']."'
						AND (A.servicio IS NULL
						OR (A.servicio IS NOT NULL
						AND A.plan_id IS NOT NULL));";
			}
			else if($_REQUEST['caso']==3)
			{
				$nombre=$_SESSION['honor3']['gruplapolh']['nombreprof'];
				$tipodo=$_SESSION['honor3']['gruplapolh']['poolidprof'];
				$grupod=$_SESSION['honor3']['lgruplanho'][$_REQUEST['indice']]['des1'];
				$gtipod=$_SESSION['honor3']['lgruplanho'][$_REQUEST['indice']]['des2'];
				$des1='GRUPO TIPO CARGO:';
				$des2='TIPO CARGO:';
				$query = "SELECT A.porcentaje,
						B.descripcion,
						C.plan_descripcion
						FROM prof_honorarios_pool_grupos AS A
						LEFT JOIN servicios AS B ON
						(A.servicio=B.servicio)
						LEFT JOIN planes AS C ON
						(A.plan_id=C.plan_id)
						WHERE A.empresa_id='".$_SESSION['honora']['empresa']."'
						AND A.prof_pool_id ='".$_SESSION['honor3']['gruplapolh']['poolidprof']."'
						AND A.grupo_tipo_cargo='".$_SESSION['honor3']['lgruplanho'][$_REQUEST['indice']]['grupo_tipo_cargo']."'
						AND A.tipo_cargo='".$_SESSION['honor3']['lgruplanho'][$_REQUEST['indice']]['tipo_cargo']."'
						AND (A.plan_id IS NULL
						OR (A.plan_id IS NOT NULL
						AND A.servicio IS NOT NULL));";
			}
			if($_REQUEST['caso']==4)
			{
				$nombre=$_SESSION['honor4']['cargospolh']['nombreprof'];
				$tipodo=$_SESSION['honor4']['cargospolh']['poolidprof'];
				$grupod=$_SESSION['honor4']['lcargocaho'][$_REQUEST['indice']]['cargo'];
				$gtipod=$_SESSION['honor4']['lcargocaho'][$_REQUEST['indice']]['descripcion'];
				$des1='CARGO:';
				$des2='DESCRIPCIÓN:';
				$query = "SELECT A.porcentaje,
						B.descripcion,
						C.plan_descripcion
						FROM prof_honorarios_pool_cargos AS A
						LEFT JOIN servicios AS B ON
						(A.servicio=B.servicio)
						LEFT JOIN planes AS C ON
						(A.plan_id=C.plan_id)
						WHERE A.empresa_id='".$_SESSION['honora']['empresa']."'
						AND A.prof_pool_id='".$_SESSION['honor4']['cargospolh']['poolidprof']."'
						AND A.cargo='".$_SESSION['honor4']['lcargocaho'][$_REQUEST['indice']]['cargo']."'
						AND (A.servicio IS NOT NULL
						OR A.plan_id IS NOT NULL);";
			}
			else if($_REQUEST['caso']==5)
			{
				$nombre=$_SESSION['honor4']['carserpolh']['nombreprof'];
				$tipodo=$_SESSION['honor4']['carserpolh']['poolidprof'];
				$grupod=$_SESSION['honor4']['lcarservho'][$_REQUEST['indice']]['cargo'];
				$gtipod=$_SESSION['honor4']['lcarservho'][$_REQUEST['indice']]['descripcion'];
				$des1='CARGO:';
				$des2='DESCRIPCIÓN:';
				$query = "SELECT A.porcentaje,
						B.descripcion,
						C.plan_descripcion
						FROM prof_honorarios_pool_cargos AS A
						LEFT JOIN servicios AS B ON
						(A.servicio=B.servicio)
						LEFT JOIN planes AS C ON
						(A.plan_id=C.plan_id)
						WHERE A.empresa_id='".$_SESSION['honora']['empresa']."'
						AND A.prof_pool_id='".$_SESSION['honor4']['carserpolh']['poolidprof']."'
						AND A.cargo='".$_SESSION['honor4']['lcarservho'][$_REQUEST['indice']]['cargo']."'
						AND (A.servicio IS NULL
						OR (A.servicio IS NOT NULL
						AND A.plan_id IS NOT NULL));";
			}
			else if($_REQUEST['caso']==6)
			{
				$nombre=$_SESSION['honor4']['carplapolh']['nombreprof'];
				$tipodo=$_SESSION['honor4']['carplapolh']['poolidprof'];
				$grupod=$_SESSION['honor4']['lcarplanho'][$_REQUEST['indice']]['cargo'];
				$gtipod=$_SESSION['honor4']['lcarplanho'][$_REQUEST['indice']]['descripcion'];
				$des1='CARGO:';
				$des2='DESCRIPCIÓN:';
				$query = "SELECT A.porcentaje,
						B.descripcion,
						C.plan_descripcion
						FROM prof_honorarios_pool_cargos AS A
						LEFT JOIN servicios AS B ON
						(A.servicio=B.servicio)
						LEFT JOIN planes AS C ON
						(A.plan_id=C.plan_id)
						WHERE A.empresa_id='".$_SESSION['honora']['empresa']."'
						AND A.prof_pool_id='".$_SESSION['honor4']['carplapolh']['poolidprof']."'
						AND A.cargo='".$_SESSION['honor4']['lcarplanho'][$_REQUEST['indice']]['cargo']."'
						AND (A.plan_id IS NULL
						OR (A.plan_id IS NOT NULL
						AND A.servicio IS NOT NULL));";
			}
			$resultado = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
?>
			<form name=forma method=GET action="Honogruadi.php"><br>
			<table border=0 cellspacing=3 align=center cellpadding=3 valign=bottom width=95% class="modulo_table_list">
			<tr class="modulo_list_claro">
			<td class="modulo_table_list_title" width=35%>EMPRESA:</td>
			<td width=65% align=left>
			<?
			echo $_SESSION['honora']['razonso'];
			?>
			</td>
			</tr>
			<tr class="modulo_list_claro">
			<td class="modulo_table_list_title" width=35%>NOMBRE DEL POOL:</td>
			<td width=65% align=left>
			<?
			echo $nombre;
			?>
			</td>
			</tr>
			<tr class="modulo_list_claro">
			<td class="modulo_table_list_title" width=35%><?echo $des1;?></td>
			<td width=65% align=left>
			<?
			echo $grupod;
			?>
			</td>
			</tr>
			<tr class="modulo_list_claro">
			<td class="modulo_table_list_title" width=35%><?echo $des2;?></td>
			<td width=65% align=left>
			<?
			echo $gtipod;
			?>
			</td>
			</tr>
			</table>
			<br><table border=0 cellspacing=3 align=center cellpadding=3 valign=bottom width=95% class="modulo_table_list">
			<tr class="modulo_list_claro">
			<td class="modulo_table_list_title" width=25% nowrap>SERVICIO</td>
			<td class="modulo_table_list_title" width=60% nowrap>PLAN</td>
			<td class="modulo_table_list_title" width=15% nowrap>PORCENTAJE</td>
			</tr>
			<?
				while (!$resultado->EOF)
				{
					?>
					<tr class="modulo_list_claro">
					<td>
					<?
					echo $resultado->fields[1];
					?>
					</td>
					<td>
					<?
					echo $resultado->fields[2];
					?>
					</td>
					<td align=center>
					<?
					echo $resultado->fields[0];
					?>
					</td>
					</tr>
					<?
					$resultado->MoveNext();
				}
			?>
			</table>
			<br><table border=0 cellspacing=3 align=center cellpadding=3 valign=bottom width=95%>
			<tr>
			<td align=center colspan=3>
			<input type='hidden' name='bandera' value='1' class='input-text'>
			<input type=button name=Aceptar class="input-submit" value="ACEPTAR" onClick="window.close();">
			</td>
			</tr>
			</table>
			</form>
