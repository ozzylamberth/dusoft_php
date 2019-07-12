
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
			
      $centro=$_REQUEST['centro'];               
      $unidad=$_REQUEST['unidad'];
			if($centro){
				$sqlcentro=" AND a.centro_utilidad='".$centro."' ";
			}
			if($unidad){
				$sqlunidad=" AND a.unidad_funcional='".$unidad."'";
			}
			$departam=$_REQUEST['departam'];
			list($dbconn) = GetDBconn();
			$query = "SELECT DISTINCT a.centro_utilidad,
                a.descripcion AS centro
					FROM centros_utilidad a, userpermisos_repconsultaexterna b
					WHERE a.empresa_id='".$_SESSION['recoex']['empresa']."' 
					
					AND a.empresa_id=b.empresa_id 
					AND a.centro_utilidad=b.centro_utilidad 
					AND b.usuario_id='".UserGetUID()."'
					
					ORDER BY a.descripcion;";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			
			$query = "SELECT DISTINCT a.unidad_funcional,
               			  a.descripcion AS unidad
					FROM   unidades_funcionales a, userpermisos_repconsultaexterna b
					WHERE a.empresa_id='".$_SESSION['recoex']['empresa']."'
					$sqlcentro
					
					AND a.empresa_id=b.empresa_id 
					AND a.centro_utilidad=b.centro_utilidad
					AND a.unidad_funcional=b.unidad_funcional 
					AND b.usuario_id='".UserGetUID()."'
					
					ORDER BY a.descripcion;";

			$result2 = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
          $query = "SELECT DISTINCT a.departamento,
               			  a.descripcion AS nombre_dpto
					FROM   departamentos a, userpermisos_repconsultaexterna b
					WHERE a.empresa_id='".$_SESSION['recoex']['empresa']."'
					$sqlcentro
          $sqlunidad 
												 
					AND a.empresa_id=b.empresa_id 
					AND a.centro_utilidad=b.centro_utilidad
					AND a.unidad_funcional=b.unidad_funcional 
					AND a.departamento=b.departamento 
					AND b.usuario_id='".UserGetUID()."'
					
												 
					ORDER BY a.descripcion;";
			$result3 = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

?>
			<form name=forma method=GET action="buscador.php"><br>
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
						$titulo=$result->fields[1];						
						if(trim($Grupo)==trim($centro))
						{							
							?><option value= "<? echo $Grupo ?>" selected><? echo $titulo?></option><?
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
						if(trim($Clase)==trim($unidad))
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
                    
                    <tr class="modulo_list_claro">
				<td width=25% nowrap class="label">DEPARTAMENTO:
				</td>
				<td colspan=2>
				<select name=departam  class="select">
				<?php
				if(!empty($departam))
				{
					while(!$result3->EOF)
					{
						$ClasePr=$result3->fields[0];
						if(trim($ClasePr)==trim($departam))
						{
						 	?><option value= "<? echo $ClasePr?>" selected><? echo $result3->fields[1] ?></option><?
						}
						else
						{
							?><option value= "<? echo $result3->fields[0]?>"><? echo $result3->fields[1] ?></option><?
						}
						$result3->MoveNext();
					}
				}
				else
				{
					echo ("<option value=-1> --- SELECCIONE --- </option>");
					while (!$result3->EOF)
					{
						?><option value= "<? echo $result3->fields[0] ?>"><? echo $result3->fields[1] ?></option><?
						$result3->MoveNext();
					}
					$result3->close();
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
			<input type=button name=Aceptar class="input-submit" value="ACEPTAR" onClick="ValoresSeleccion(this.form.centro,this.form.unidad,this.form.departam,this.form)">
			</td>
			</tr>
			</table>
			</form>
