<?php

/**
 * $Id: ventanaClasificacion.php,v 1.2 2005/06/02 19:28:26 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: realizar la busqueda de los paises de origen de los pacientes
 * y permite adicionar departamentos y municipios.
 */

?>
<head>
<title>CLASIFICACION</title>
<script languaje="javascript" src="selectorGrupo.js">
</script>
  <?php
	    $VISTA='HTML';
			$_ROOT='../../';
			include_once $_ROOT.'includes/enviroment.inc.php';
			include_once $_ROOT.'includes/modules.inc.php';
			include_once $_ROOT.'includes/api.inc.php';
			$filename="themes/$VISTA/" . GetTheme() . "/module_theme.php";
			IncludeFile($filename);
			print (ReturnHeader('LISTADO FABRICANTES'));
			print(ReturnBody());

			$grupo=$_REQUEST['grupo'];
			$clasePr=$_REQUEST['clasePr'];
			$subclase=$_REQUEST['subclase'];
			$Empresa=$_REQUEST['Empresa'];
			$bandera=$_REQUEST['bandera'];
			$Empresa=str_pad($Empresa,2,0,STR_PAD_LEFT);
			list($dbconn) = GetDBconn();
			$query = "SELECT grupo_id,descripcion FROM inv_grupos_inventarios";
		  $result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		  }

			$query = "SELECT clase_id,descripcion FROM inv_clases_inventarios WHERE grupo_id='$grupo'";
		  $result2 = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		  }
			$query = "SELECT subclase_id,descripcion FROM inv_subclases_inventarios WHERE grupo_id='$grupo' AND clase_id='$clasePr'";
		  $result3 = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		  }

  ?>
			<form name=forma method=GET action="ventanaClasificacion.php">
			<BR>
			<table border=0 cellspacing=3 align=center cellpadding=3 valign=bottom width=80% class="modulo_list_oscuro">
      <tr><td>
      <table border=0 cellspacing=3 align=center cellpadding=3 valign=bottom width=95% class="modulo_list_oscuro">
      <tr class="modulo_list_claro">
        <td width=30% nowrap class="label">Gupo:</td>
        <td colspan=2>
        <select name=grupo  class="select" onChange="cambioGrupo(this.value,this.form,<?php echo $Empresa?>)">
        <?php
				  if(!empty($grupo)){
					  while(!$result->EOF){
              $Grupo=$result->fields[0];
						  if($Grupo==$grupo){
							  ?><option value= "<? echo $Grupo ?>" selected><? echo $Grupo.'  '.$result->fields[1]?></option><?
						  }else{
								?>
								<option value= "<? echo $result->fields[0]?>"><? echo $result->fields[0].'  '.$result->fields[1] ?></option><?
							}
							$result->MoveNext();
						}
					}else{
					  echo ("<option value=0>Seleccione</option>");
						while (!$result->EOF){?>
							<option value= "<? echo $result->fields[0] ?>"><? echo $result->fields[0].'  '.$result->fields[1] ?></option><?
							$result->MoveNext();
						}
					  $result->close();
				  }
				?>
        </select>
	      </td>
	    </tr>
			<tr class="modulo_list_claro">
        <td width=30% nowrap class="label">SubGupo:</td>
        <td colspan=2>
        <select name=clasePr  class="select" onChange="cambioClase(this.form,<?php echo $Empresa?>)">
        <?php
				  if(!empty($clasePr)){
					  while(!$result2->EOF){
              $ClasePr=$result2->fields[0];
						  if($ClasePr==$clasePr){
							  ?><option value= "<? echo $ClasePr?>" selected><? echo $ClasePr.'  '.$result2->fields[1] ?></option><?
						  }else{
								?>
								<option value= "<? echo $result2->fields[0]?>"><? echo $result2->fields[0].'  '.$result2->fields[1] ?></option><?
							}
							$result2->MoveNext();
						}
					}else{
					  echo ("<option value=0>Seleccione</option>");
						while (!$result2->EOF){?>
							<option value= "<? echo $result2->fields[0] ?>"><? echo $result2->fields[0].'  '.$result2->fields[1] ?></option><?
							$result2->MoveNext();
						}
					  $result2->close();
				  }
				?>
        </select>
	      </td>
	    </tr>
			<tr class="modulo_list_claro">
        <td width=30% nowrap class="label">SubClase:</td>
        <td colspan=2>
        <select name=subclase  class="select">
        <?php
				  if(!empty($subclase)){
					  while(!$result3->EOF){
              $SubClase=$result3->fields[0];
						  if($SubClase==$subclase){
							  ?><option value= "<? echo $SubClase?>" selected><? echo $SubClase.'  '.$result3->fields[1] ?></option><?
						  }else{
								?>
								<option value= "<? echo $result3->fields[0]?>"><? echo $result3->fields[0].'  '.$result3->fields[1] ?></option><?
							}
							$result3->MoveNext();
						}
					}else{
					  echo ("<option value=0>Seleccione</option>");
						while (!$result3->EOF){?>
							<option value= "<? echo $result3->fields[0] ?>"><? echo $result3->fields[0].'  '.$result3->fields[1] ?></option><?
							$result3->MoveNext();
						}
					  $result3->close();
				  }
				?>
        </select>
	      </td>
	    </tr>
			</table>
			</td></tr>
      </table>
			<table border=0 cellspacing=3 align=center cellpadding=3 valign=bottom width=90%>
			<tr>
			  <td align=center colspan=3>
				<input type='hidden' name='bandera' value='<?echo $bandera?>' class='input-text'>
				<?php
				if($bandera==1){
        ?>
				<input type=submit name=Aceptar class="input-submit" value="Aceptar" onClick="ParametrosBusqueda(this.form.grupo,this.form.clasePr,this.form.subclase,this.form,<?php echo $Empresa?>)">
        <?php
				}else{
				?>
        <input type=submit name=Aceptar class="input-submit" value="Aceptar" onClick="ValoresSeleccion(this.form.grupo,this.form.clasePr,this.form.subclase,this.form,<?php echo $Empresa?>)">
				<?php
				}
				?>
				</td>
      </tr>
			</form>
      </table>
