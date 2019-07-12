<?php

/**
 * $Id: ventanaClasifyUbicacion.php,v 1.3 2005/12/01 20:07:42 lorena Exp $
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
</head>
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

	  if($_REQUEST['Aceptar']){
      $cadena=explode('/',$_REQUEST['ubicacionid']);
      $ubicacionFinal=$cadena[1];
			list($dbconn) = GetDBconn();
			$query="UPDATE existencias_bodegas SET ubicacion_id='$ubicacionFinal' WHERE empresa_id='$Empresa' AND centro_utilidad='$centroutilidad' AND bodega='$Bodega' AND codigo_producto='$CodigoPr'";
			$res=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			echo "<script languaje=\"javascript\">";
      echo "window.opener.xxx();";
      echo "window.close();";
      echo "</script>";
		}else{

			$nivelUno=$_REQUEST['nivelUno'];
      $cadena=explode('/',$nivelUno);
      $nivelUnoCad=$cadena[0];
			$nivelDos=$_REQUEST['nivelDos'];
      $cadena=explode('/',$nivelDos);
      $nivelDosCad=$cadena[0];
      $nivelTres=$_REQUEST['nivelTres'];
      $cadena=explode('/',$nivelTres);
      $nivelTresCad=$cadena[0];
			$nivelCuatro=$_REQUEST['nivelCuatro'];
      $cadena=explode('/',$nivelCuatro);
      $nivelCuatroCad=$cadena[0];
			$Empresa=$_REQUEST['Empresa'];
			$centroutilidad=$_REQUEST['centroutilidad'];
			$Bodega=$_REQUEST['Bodega'];
			$CodigoPr=$_REQUEST['CodigoPr'];
			$Empresa=str_pad($Empresa,2,0,STR_PAD_LEFT);
			$centroutilidad=str_pad($centroutilidad,2,0,STR_PAD_LEFT);

			list($dbconn) = GetDBconn();

			$query = "SELECT x.n1,y.ubicacion_id FROM bodegas_ubicaciones_n1 x,bodegas_ubicaciones y WHERE x.empresa_id='$Empresa' AND x.centro_utilidad='$centroutilidad' AND x.bodega='$Bodega' AND x.n1=y.n1 AND y.n2='' AND n3='' AND n4='' AND
      x.empresa_id=y.empresa_id AND x.centro_utilidad=y.centro_utilidad AND x.bodega=y.bodega";
		  $result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		  }

			$query = "SELECT x.n2,y.ubicacion_id FROM bodegas_ubicaciones_n2 x,bodegas_ubicaciones y WHERE x.empresa_id='$Empresa' AND x.centro_utilidad='$centroutilidad' AND x.bodega='$Bodega' AND x.n1='$nivelUnoCad' AND x.n1=y.n1 AND x.n2=y.n2 AND y.n3='' AND y.n4='' AND
      x.empresa_id=y.empresa_id AND x.centro_utilidad=y.centro_utilidad AND x.bodega=y.bodega";
		  $result2 = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		  }
			$query = "SELECT x.n3,y.ubicacion_id FROM bodegas_ubicaciones_n3 x,bodegas_ubicaciones y WHERE x.empresa_id='$Empresa' AND x.centro_utilidad='$centroutilidad' AND x.bodega='$Bodega' AND x.n1='$nivelUnoCad' AND x.n2='$nivelDosCad' AND x.n1=y.n1 AND x.n2=y.n2 AND x.n3=y.n3 AND y.n4='' AND
      x.empresa_id=y.empresa_id AND x.centro_utilidad=y.centro_utilidad AND x.bodega=y.bodega";
		  $result3 = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		  }

			$query = "SELECT x.n4,y.ubicacion_id FROM bodegas_ubicaciones_n4 x,bodegas_ubicaciones y WHERE x.empresa_id='$Empresa' AND x.centro_utilidad='$centroutilidad' AND x.bodega='$Bodega' AND x.n1='$nivelUnoCad' AND x.n2='$nivelDosCad' AND x.n3='$nivelTresCad' AND x.n1=y.n1 AND x.n2=y.n2 AND x.n3=y.n3 AND x.n4=y.n4 AND
      x.empresa_id=y.empresa_id AND x.centro_utilidad=y.centro_utilidad AND x.bodega=y.bodega";
		  $result4 = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		  }

      ?>
			<form name=forma method=GET action="ventanaClasifyUbicacion.php">
			<BR>
			<table border=0 cellspacing=3 align=center cellpadding=3 valign=bottom width=80% class="modulo_list_oscuro">
      <tr><td>
      <table border=0 cellspacing=3 align=center cellpadding=3 valign=bottom width=95% class="modulo_list_oscuro">
			<input type='hidden' name='ubicacionid' value='<?echo $_REQUEST['ubicacionid']?>'>
			<input type='hidden' name='Empresa' value='<?echo $Empresa ?>'>
			<input type='hidden' name='centroutilidad' value='<?echo $centroutilidad ?>'>
			<input type='hidden' name='Bodega' value='<?echo $Bodega ?>'>
			<input type='hidden' name='CodigoPr' value='<?echo $CodigoPr ?>'>
      <tr class='modulo_list_claro'>
        <td width=30% nowrap class='label'>Primer Nivel:</td>
        <td colspan=2>
        <select name=nivelUno class="select" onChange="cambioUbicaNUno(this.value,this.form)">
        <?php
				  if(!empty($nivelUno)){
					  while(!$result->EOF){
              $NivelUno=$result->fields[0];
							if($NivelUno==$nivelUnoCad){
								?><option value="<?php 	echo $NivelUno.'/'.$result->fields[1] ?>" selected><? echo $NivelUno 	?></option>
								<?php
							}else{
								?>
								<option value= "<? echo $NivelUno.'/'.$result->fields[1] ?>"><? echo $NivelUno ?></option><?
							}
							$result->MoveNext();
						}
					}else{
					  echo ("<option value=0>Seleccione</option>");
						while (!$result->EOF){?>
							<option value= "<? echo $result->fields[0].'/'.$result->fields[1] ?>"><? echo $result->fields[0]  ?></option><?
							$result->MoveNext();
				    }
						$result->close();
					}
				?>
        </select>
	      </td>
	    </tr>
			<tr class="modulo_list_claro">
        <td width=30% nowrap class="label">Segundo Nivel:</td>
        <td colspan=2>
        <select name=nivelDos class="select" onChange="cambioUbicaNDos(this.form)">
        <?php
				  if(!empty($nivelDos)){
					  while(!$result2->EOF){
              $NivelDos=$result2->fields[0];
							if($NivelDos!=""){
								if($NivelDos==$nivelDosCad){
									?><option value= "<? echo $NivelDos.'/'.$result2->fields[1] ?>" selected><? echo $NivelDos ?></option><?
								}else{
									?>
									<option value= "<? echo $result2->fields[0].'/'.$result2->fields[1] ?>"><? echo $result2->fields[0] ?></option><?
								}
							}
							$result2->MoveNext();
						}
					}else{
					  echo ("<option value=0>Seleccione</option>");
						while (!$result2->EOF){
						  if($result2->fields[0]){
						  ?>
							  <option value= "<? echo $result2->fields[0].'/'.$result2->fields[1] ?>"><? echo $result2->fields[0] ?></option><?
							}
							$result2->MoveNext();
						}
					  $result2->close();
				  }
				?>
        </select>
	      </td>
	    </tr>
			<tr class="modulo_list_claro">
        <td class="label" width=30% nowrap>Tercer Nivel:</td>
        <td colspan=2>
        <select name=nivelTres  class="select" onChange="cambioUbicaNTres(this.form)">
        <?php
				  if(!empty($nivelTres)){
					  while(!$result3->EOF){
              $NivelTres=$result3->fields[0];
							if($NivelTres!=""){
								if($NivelTres==$nivelTresCad){
									?><option value= "<? echo $NivelTres.'/'.$result3->fields[1] ?>" selected><? echo $NivelTres ?></option><?
								}else{
									?>
									<option value= "<? echo $result3->fields[0].'/'.$result3->fields[1] ?>"><? echo $result3->fields[0] ?></option><?
								}
							}
							$result3->MoveNext();
						}
					}else{
					  echo ("<option value=0>Seleccione</option>");
						while (!$result3->EOF){
              if($result3->fields[0]!=""){
							?>
							  <option value= "<? echo $result3->fields[0].'/'.$result3->fields[1] ?>"><? echo $result3->fields[0] ?></option><?
							}
							$result3->MoveNext();
						}
					  $result3->close();
				  }
				?>
        </select>
	      </td>
	    </tr>
			<tr class="modulo_list_claro">
        <td width=30% nowrap class="label">Cuarto Nivel:</td>
        <td colspan=2>
        <select name=nivelCuatro  class="select" onChange="cambioUbicaNCuatro(this.form)">
        <?php
				  if(!empty($nivelCuatro)){
					  while(!$result4->EOF){
              $NivelCuatro=$result4->fields[0];
							if($NivelCuatro!=""){
								if($NivelCuatro==$nivelCuatroCad){
									?><option value= "<? echo $NivelCuatro.'/'.$result4->fields[1] ?>" selected><? echo $NivelCuatro ?></option><?
								}else{
									?>
									<option value= "<? echo $result4->fields[0].'/'.$result4->fields[1] ?>"><? echo $result4->fields[0] ?></option><?
								}
							}
							$result4->MoveNext();
						}
					}else{
					  echo ("<option value=0>Seleccione</option>");
						while (!$result4->EOF){
              if($result4->fields[0]!=""){
							?>
							  <option value= "<? echo $result4->fields[0].'/'.$result4->fields[1] ?>"><? echo $result4->fields[0] ?></option><?
							}
							$result4->MoveNext();
						}
					  $result4->close();
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
			  <td align=center>
				  <input type=submit class="input-submit" name=Aceptar value="Aceptar" onClick="valorUbicacion(this.form)">
				</td>
      </tr>
			</table>
			</form>
      </table>
    <?php
		}
		?>
