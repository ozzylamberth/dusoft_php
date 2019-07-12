
	<?php
/*
* Selectortariserv.php  13/04/2004
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
* Proposito del Archivo: realizar la busqueda de los planes
* y mostrar el plan tarifario del plan seleccionado para los servicios
*/
	?>
	<head>
	<title>PLAN TARIFARIO</title>
	<script languaje="javascript" src="selectortariserv.js">
	</script>
	<style>
	.input-submit
	{
		color: #000000;
		font-size: 11px;
	}
	.input-bottom
	{
		color: #000000;
		font-weight: bold;
		font-size: 9px;
	}
	</style>
	<?php
	$_ROOT='../../';
	$VISTA='HTML';
	include $_ROOT.'includes/enviroment.inc.php';
	$fileName = $_ROOT."themes/$VISTA/".GetTheme()."/style/style.css";
	$fileName = "<link href=\"$fileName\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo $fileName;
	?>
	</head>
	<form name=contratacion method=GET action="selectortariserv.php">
	<table valign=bottom width=100%>
	<tr>
	<td align=center><br>
		<table valign=bottom width=98% border=0 class="modulo_table_list">
		<tr class="modulo_list_claro">
		<td class="modulo_table_list_title" width=25%>NÚMERO DE CONTRATO:</td>
		<td width=75%>
		<?
			$esta=-1;
			echo ("<input type=\"hidden\" name=\"empresacon\" value=".$_REQUEST['empresacon'].">");
			echo ("<input type=\"hidden\" name=\"estadocont\" value=".$_REQUEST['estadocont'].">");
			if(!($_REQUEST['empresacon']==-1))
			{
				$busqueda1="AND A.empresa_id='".$_REQUEST['empresacon']."'";
			}
			else
			{
				$busqueda1='';
			}
			if($_REQUEST['estadocont']==1)
			{
				$busqueda2='';
			}
			else if($_REQUEST['estadocont']==2)
			{
				$busqueda2="AND A.estado='1'";
			}
			else if($_REQUEST['estadocont']==3)
			{
				$busqueda2="AND A.estado='0'";
			}
			list($dbconn) = GetDBconn();
			$consulta  = "SELECT A.plan_proveedor_id,
						A.num_contrato,
						A.plan_descripcion,
						B.razon_social,
						C.nombre_tercero,
						A.estado
						FROM planes_proveedores AS A,
						empresas AS B,
						terceros AS C
						WHERE A.empresa_id=B.empresa_id
						AND A.tipo_id_tercero=C.tipo_id_tercero
						AND A.tercero_id=C.tercero_id
						AND A.plan_proveedor_id<>".$_SESSION['propla']['planelpr']."
						$busqueda1
						$busqueda2
						ORDER BY num_contrato";
			$resultado=$dbconn->Execute($consulta);
		?>
		<select name=tarifario1 onChange="cambio(this.form)" class="select">
		<option value=-1>--  SELECCIONE  --</option>"
		<?php
			if($resultado->EOF)
			{
				$_REQUEST['tarifario1']='';
			}
			while(!$resultado->EOF)
			{
				if($resultado->fields[1]==$_REQUEST['tarifario1'])
				{
					echo "<option value=\"".$resultado->fields[1]."\" selected>".$resultado->fields[1]."".' -- '."".$resultado->fields[2]."</option>";
					$plan=$resultado->fields[0];
					$desc=$resultado->fields[2];
					$empr=$resultado->fields[3];
					$clie=$resultado->fields[4];
					$esta=$resultado->fields[5];
				}
				else
				{
					echo "<option value=\"".$resultado->fields[1]."\">".$resultado->fields[1]."".' -- '."".$resultado->fields[2]."</option>";
				}
				$resultado->MoveNext();
			}
		?>
		</select>
		<?php
		echo ("<input type=\"hidden\" name=\"plan\" value=".$plan.">");
		?>
		</td>
		</tr>
		<tr class="modulo_list_claro">
		<td class="modulo_table_list_title" width=25%>ESTADO:</td>
		<td width=75% align=left>
		<?
		if($esta==1)
		{
		echo "ACTIVO";
		}
		else if($esta==0)
		{
		echo "INACTIVO";
		}
		?>
		</td>
		</tr>
		<tr class="modulo_list_claro">
		<td class="modulo_table_list_title" width=25%>DESCRIPCIÓN DEL CONTRATO:</td>
		<td width=75% align=left>
		<?
		echo $desc;
		?>
		</td>
		</tr>
		<tr class="modulo_list_claro">
		<td class="modulo_table_list_title" width=25%>EMPRESA:</td>
		<td width=75% align=left>
		<?
		echo $empr;
		?>
		</td>
		</tr>
		<tr class="modulo_list_claro">
		<td class="modulo_table_list_title" width=25%>CLIENTE:</td>
		<td width=75% align=left>
		<?
		echo $clie;
		?>
		</td>
		</tr>
		</table>
	</td>
	</tr>
	<tr>
	<td align=center><br>
		<table valign=bottom width=98% border=0 class="modulo_table_list">
		<tr class="modulo_list_claro">
		<td class="modulo_table_list_title" width=30% nowrap>GRUPOS TARIFARIOS</td>
		<td class="modulo_table_list_title" width=40% nowrap>SUBGRUPOS TARIFARIOS</td>
		<td class="modulo_table_list_title" width=20% nowrap>TARIFARIO</td>
		<td class="modulo_table_list_title" width=10% nowrap>PORCENTAJE</td>
		</tr>
		<?
			$consulta = "SELECT B.grupo_tarifario_descripcion AS des1,
					C.subgrupo_tarifario_descripcion AS des2,
					D.descripcion AS des3,
					A.porcentaje
					FROM plan_tarifario_proveedores AS A,
					grupos_tarifarios AS B,
					subgrupos_tarifarios AS C,
					tarifarios AS D,
					planes_proveedores AS E
					WHERE E.num_contrato='".$_REQUEST['tarifario1']."'
					AND E.plan_proveedor_id=A.plan_proveedor_id
					AND A.grupo_tarifario_id<>'00'
					AND A.grupo_tarifario_id=B.grupo_tarifario_id
					AND A.grupo_tarifario_id=C.grupo_tarifario_id
					AND A.subgrupo_tarifario_id=C.subgrupo_tarifario_id
					AND A.tarifario_id<>'SYS'
					AND A.tarifario_id=D.tarifario_id
					ORDER BY des1, des2, des3;";
			$resultado = $dbconn->Execute($consulta);
			if($resultado->EOF)
			{
				?>
				<tr class="modulo_list_claro">
				<td colspan=4 align=center>
				<?
				echo "NO SE ENCONTRÓ NINGÚNA INFORMACIÓN";
				?>
				</td>
				</tr>
				<?
			}
			while (!$resultado->EOF)
			{
				?>
				<tr class="modulo_list_claro">
				<td>
				<?
				echo $resultado->fields[0];
				?>
				</td>
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
				echo $resultado->fields[3];
				?>
				</td>
				</tr>
				<?
				$resultado->MoveNext();
			}
		?>
		</table>
	</td>
	</tr>
	<tr>
	<td align=center><br>
		<table valign=bottom width=30% border=0>
		<tr>
		<td align=center>
		<input type=submit name=Aceptar class="input-bottom" value="ACEPTAR" onClick="copiarValor(this.form)">
		</td>
		</tr>
		</table>
	</td>
	</tr>
	</table>
	</form>
