<?php

/**
 * $Id: selectorplan.php,v 1.1.1.1 2009/09/11 20:36:30 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: realizar la busqueda de los planes
 * y mostrar el plan tarifario del plan seleccionado
 */

	?>
	<head>
	<title>PLAN TARIFARIO</title>
	<script languaje="javascript" src="selectortarifario.js">
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
	<form name=contratacion method=GET action="selectorplan.php">
	<table valign=bottom width=100%>
	<tr>
	<td align=center><br>
		<table valign=bottom width=98% border=0 class="modulo_table_list">
		<tr class="modulo_list_claro">
		<td class="modulo_table_list_title" width=20%>N?MERO DE CONTRATO:</td>
		<td width=20%>
		<?
			$esta=-1;
			echo ("<input type=\"hidden\" name=\"empresacon\" value=".$_REQUEST['empresacon'].">");
			echo ("<input type=\"hidden\" name=\"tipoplacon\" value=".$_REQUEST['tipoplacon'].">");
			echo ("<input type=\"hidden\" name=\"estadocont\" value=".$_REQUEST['estadocont'].">");
			if(!($_REQUEST['empresacon']==-1))
			{
				$busqueda1="AND A.empresa_id='".$_REQUEST['empresacon']."'";
			}
			else
			{
				$busqueda1='';
			}
			if(!($_REQUEST['tipoplacon']==-1))
			{
				$busqueda2="AND A.sw_tipo_plan='".$_REQUEST['tipoplacon']."'";
			}
			else
			{
				$busqueda2='';
			}
			if($_REQUEST['estadocont']==1)
			{
				$busqueda3='';
			}
			else if($_REQUEST['estadocont']==2)
			{
				$busqueda3="AND A.estado='1'";
			}
			else if($_REQUEST['estadocont']==3)
			{
				$busqueda3="AND A.estado='0'";
			}
			list($dbconn) = GetDBconn();
			$consulta  = "SELECT A.plan_id,
						A.num_contrato,
						A.plan_descripcion,
						B.razon_social,
						C.nombre_tercero,
						D.descripcion,
						A.estado
						FROM planes AS A,
						empresas AS B,
						terceros AS C,
						tipos_planes AS D
						WHERE A.empresa_id=B.empresa_id
						AND A.tipo_tercero_id=C.tipo_id_tercero
						AND A.tercero_id=C.tercero_id
						AND A.sw_tipo_plan=D.sw_tipo_plan
						AND A.plan_id<>".$_SESSION['ctrpla']['planeleg']."
						$busqueda1
						$busqueda2
						$busqueda3
						ORDER BY num_contrato";
			$resultado=$dbconn->Execute($consulta);
		?>
		<select name=tarifario1 onChange="cambio(this.form)" class="select">
		<option value=-1>--  SELECCIONE  --</option>"
		<?php
			while(!$resultado->EOF)
			{
				if($resultado->fields[1]==$_REQUEST['tarifario1'])
				{
					echo "<option value=\"".$resultado->fields[1]."\" selected>".$resultado->fields[1]."".' -- '."".$resultado->fields[2]."</option>";
					$plan=$resultado->fields[0];
					$desc=$resultado->fields[2];
					$empr=$resultado->fields[3];
					$clie=$resultado->fields[4];
					$tipo=$resultado->fields[5];
					$esta=$resultado->fields[6];
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
		<td class="modulo_table_list_title" width=12%>TIPO PLAN:</td>
		<td width=18% align=left>
		<?
		echo $tipo;
		?>
		</td>
		<td class="modulo_table_list_title" width=12%>ESTADO:</td>
		<td width=18% align=left>
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
		<td class="modulo_table_list_title" width=20%>DESCRIPCI?N DEL CONTRATO:</td>
		<td colspan=5 align=left>
		<?
		echo $desc;
		?>
		</td>
		</tr>
		<tr class="modulo_list_claro">
		<td class="modulo_table_list_title" width=20%>EMPRESA:</td>
		<td colspan=5 align=left>
		<?
		echo $empr;
		?>
		</td>
		</tr>
		<tr class="modulo_list_claro">
		<td class="modulo_table_list_title" width=20%>CLIENTE:</td>
		<td colspan=5 align=left>
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
		<td class="modulo_table_list_title" width=29% nowrap>GRUPOS TARIFARIOS</td>
		<td class="modulo_table_list_title" width=29% nowrap>SUBGRUPOS TARIFARIOS</td>
		<td class="modulo_table_list_title" width=18% nowrap>TARIFARIO</td>
		<td class="modulo_table_list_title" width=10% nowrap>PORCENTAJE</td>
		<td class="modulo_table_list_title" width=10% nowrap>COBERTURA</td>
		<td class="modulo_table_list_title" width=4%  nowrap>DES.</td>
		</tr>
		<?
			$consulta = "SELECT B.grupo_tarifario_descripcion AS des1,
					C.subgrupo_tarifario_descripcion AS des2,
					D.descripcion AS des3,
					A.porcentaje,
					A.por_cobertura,
					A.sw_descuento
					FROM plan_tarifario AS A,
					grupos_tarifarios AS B,
					subgrupos_tarifarios AS C,
					tarifarios AS D,
					planes AS E
					WHERE E.num_contrato='".$_REQUEST['tarifario1']."'
					AND E.plan_id=A.plan_id
					AND A.grupo_tarifario_id<>'00'
					AND A.grupo_tarifario_id=B.grupo_tarifario_id
					AND A.grupo_tarifario_id=C.grupo_tarifario_id
					AND A.subgrupo_tarifario_id=C.subgrupo_tarifario_id
					AND A.tarifario_id<>'SYS'
					AND A.tarifario_id=D.tarifario_id
					ORDER BY des1, des2, des3;";
			$resultado = $dbconn->Execute($consulta);
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
				<td align=center>
				<?
				echo $resultado->fields[4];
				?>
				</td>
				<td align=center>
				<?
				if($resultado->fields[5]==1)
				{
					echo "<img src=\"".GetThemePath()."/images/checksi.png\" border=\"0\">";
				}
				else
				{
					echo "<img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">";
				}
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
