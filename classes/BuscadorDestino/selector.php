<?php
// Selector.php  09/12/2003
// ---------------------------------------------------------------------------------------//
// eHospital v 0.1                                                                       //
// Copyright (C) 2003 InterSoftware Ltda.                                               //
// Emai: intersof@telesat.com.co                                                       //
// -----------------------------------------------------------------------------------//
// Autor: Jairo Duvan Diaz Martinez,Darling Dorado,Lorena Aragón                     //                       //
// Proposito del Archivo: realizar la busqueda de los paises de origen de los       //
// pacientes,y permite adicionar departamentos y municipos.                        //
//                                                                                //
// ------------------------------------------------------------------------------//
?>
<head>
<title>UBICACION</title>
<script languaje="javascript" src="selectorCiudad.js">
</script>

<style>
.tabla { font-family: sans_serif, Verdana, helvetica, Arial;
    			      color: #000000;
                font-size: 12px;
    				    background-color: #C4D1E2;
		    		    border-color: #330066;
                padding: 2pt
				}

.input-submit { color: #000000; font-size: 11px;}
.input-bottom { color: #000000; font-weight: bold; font-size: 9px;}
</style>

<?php

				$VISTA='HTML';
				$_ROOT='../../';
				include_once $_ROOT.'includes/enviroment.inc.php';

				/*  if(!empty($_REQUEST['forma'])){
				$_SESSION['NOMBRE_FORMA'] = $_REQUEST['forma'];
				}
				$dato=$_SESSION['NOMBRE_FORMA'];*/

				if(!empty($_REQUEST['pais']))
				{
					$pais=$_REQUEST['pais'];
					$dpto=$_REQUEST['dpto'];
					$ciudad=$_REQUEST['mpio'];
					$comuna=$_REQUEST['comuna'];
				}
				else if(!empty($_REQUEST['paisE']))
				{
					$pais=$_REQUEST['paisE'];
					$dpto=$_REQUEST['dptoE'];
					$ciudad=$_REQUEST['mpioE'];
					$comuna=$_REQUEST['comunaE'];
				}
				else
				{
					$pais=GetVarConfigAplication('DefaultPais');
					$dpto=GetVarConfigAplication('DefaultDpto');
					$ciudad=GetVarConfigAplication('DefaultMpio');
				}

				$Editar1=$_REQUEST['EditarD'];
				$Editar=$_REQUEST['EditarM'];
			//echo "esto es editar dpto-->**$Editar1<br>";
			//echo "esto es editar ciudad-->**$Editar";

				list($dbconn) = GetDBconn();
				$query="SELECT campo,sw_mostrar,sw_obligatorio FROM pacientes_campos_obligatorios";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}

				while(!$result->EOF){
						$campo[$result->fields[0]]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
				}
				$result->Close();

if(!$Editar1 && !$Editar){
?>
</head>
<form name=forma method=GET action="selector.php">
 <table cellspacing=3 cellpadding=3 valign=bottom width=80%  class=tabla>
<tr>
   <td width=30% nowrap>País:</td>
   <td colspan=2>
	 <?
       list($dbconn) = GetDBconn();
		   $cons  = "SELECT bloqueado_edicion FROM tipo_pais WHERE tipo_pais_id='$pais'";
		   $resultado=$dbconn->Execute($cons);
	     $Edicion=$resultado->fields[0];
		   $consulta  = "SELECT tipo_pais_id,pais FROM tipo_pais order by pais";
       $resultado=$dbconn->Execute($consulta);
	 ?>
     <select name=pais  onChange="cambio(this.value)" class="input-submit">
     <?php
			if($pais){
       while(!$resultado->EOF){
         $PaisId=$resultado->fields[0];
				 if($PaisId==$pais){
          ?><option value= "<? echo $PaisId?>" selected><? echo $resultado->fields[1] ?></option><?
				 }else{ ?>
						<option value= "<? echo $PaisId?>"><? echo $resultado->fields[1] ?></option><?
					}
			  $resultado->MoveNext();
			 }
		 }
		else{
        echo ("<option value=0>Seleccione</option>");
			   while (!$resultado->EOF){?>
   		     <option value= "<? echo $resultado->fields[0] ?>"><? echo $resultado->fields[1] ?></option><?
			     $resultado->MoveNext();
				 }
			$resultado->close();
     } ?>
    </select>
	 </td>
	</tr>
	<tr>
					<td>Departamento:</td>
					<td>
							<select name=dpto onChange="cambioDpto(this.form)" class="input-submit"><?
								$consulta  = "SELECT tipo_dpto_id,departamento FROM tipo_dptos WHERE tipo_pais_id='$pais' ORDER BY departamento";
								$resultado = $dbconn->Execute($consulta);
                  $i=0;
									while (!$resultado->EOF)
									{
										$cod=$resultado->fields[0];
										if($i==0){ $dpto1=$resultado->fields[0];  }
										if($cod==$dpto){
											?><option value="<?echo $cod?>" selected><?echo $resultado->fields[1]?></option><?}
										else{
											?><option value="<?echo $cod?>"><?echo $resultado->fields[1]?></option><?
									  }
										$i++;
									$resultado->MoveNext();
									}								?>
							</select>
					</td>
					<td><?
					if(empty($dpto)){ $dpto=$dpto1; }


					if($Edicion==0){?><input class="input-bottom" type=submit name=EditarD value="Editar" onclick="EditarDpto(this.form.pais.value)"><?}?></td>
					</tr>
					<tr>
					<td>Ciudad:</td>
					<td>
							<select name=mpio  onChange="cambioMpio(this.form)" class="input-submit"><?
								$consulta  = "SELECT tipo_mpio_id,municipio FROM tipo_mpios WHERE tipo_pais_id='$pais' AND tipo_dpto_id= '$dpto' ORDER BY municipio";
								$resultado = $dbconn->Execute($consulta);
                  $i=0;
									while (!$resultado->EOF)
									{
										$cod=$resultado->fields[0];
										if($i==0){ $ciudad1=$resultado->fields[0];  }
										if($cod==$ciudad){
											?><option value="<?echo $cod?>" selected><?echo $resultado->fields[1]?></option><?}
										else{
											?><option value="<?echo $cod?>"><?echo $resultado->fields[1]?></option><?
									  }
										$i++;
									$resultado->MoveNext();
									}								?>
							</select>
					</td>
					<td><?
					if(empty($ciudad)){ $ciudad=$ciudad1; }
					if($Edicion==0){?><input class="input-bottom" type=submit name=EditarD value="Editar" onclick="EditarDpto(this.form.pais.value)"><?}?></td>
					</tr><?
						if($campo[tipo_comuna_id][sw_mostrar]==1)
						{?>
					<tr>
					<td><?echo ModuloGetVar('app','Pacientes','NombreComuna')?>:</td>
					<td>
							<select name=comuna onChange="cambioComuna(this.form)" class="input-submit"><?
									$consulta  = "SELECT tipo_comuna_id,comuna FROM tipo_comunas
																WHERE tipo_pais_id='$pais' AND tipo_dpto_id= '$dpto' AND tipo_mpio_id='$ciudad'
																ORDER BY comuna";
									$resultado = $dbconn->Execute($consulta);
                  $i=0;
									if($resultado->EOF)
									{
											?>	<option value="0"><?echo "--------NO HAY--------"?></option><?
									}
									else
									{
											?>	<option value="0"><?echo "--------TODAS--------"?></option><?
									}

									while (!$resultado->EOF)
									{
										$cod=$resultado->fields[0];
										if($i==0){ $comuna1=$resultado->fields[0];  }
										if($cod==$comuna){
											?><option value="<?echo $cod?>" selected><?echo $resultado->fields[1]?></option><?}
										else{
											?><option value="<?echo $cod?>"><?echo $resultado->fields[1]?></option><?
									  }
										$i++;
									$resultado->MoveNext();
									}?>
							</select>
					</td>
					<td><?
					//if(empty($comuna)){ $comuna=$comuna1; }
					if($Edicion==0){?><input class="input-bottom" type=submit name=EditarD value="Editar" onclick="EditarDpto(this.form.pais.value)"><?}?></td>
					</tr>	<?
					}
					else
					{?>
                 <input type=hidden name=comuna value=0 class=input-text>
				<?}

						if($campo[lugar_residencia][sw_mostrar]==1 AND $campo[lugar_residencia][sw_obligatorio]==1
								AND $campo[tipo_barrio_id][sw_mostrar]==1 AND $campo[tipo_comuna_id][sw_obligatorio]==1)
						{
					?>
			<tr>
					<td>Barrio:</td>
					<td><select name=barrio class="input-submit"><?
								if($comuna!=0 AND !empty($comuna))
								{
										$consulta  = "SELECT a.tipo_barrio_id,a.barrio, a.tipo_comuna_id, b.comuna, a.tipo_estrato_id
										FROM tipo_barrios as a, tipo_comunas as b
										WHERE a.tipo_pais_id='$pais' AND a.tipo_dpto_id= '$dpto' AND a.tipo_mpio_id='$ciudad' AND a.tipo_comuna_id='$comuna'
										AND a.tipo_comuna_id=b.tipo_comuna_id ORDER BY barrio";
								}
								else
								{
										$consulta  = "SELECT a.tipo_barrio_id,a.barrio, a.tipo_comuna_id, b.comuna, a.tipo_estrato_id
										FROM tipo_barrios as a, tipo_comunas as b
										WHERE a.tipo_pais_id='$pais' AND a.tipo_dpto_id= '$dpto' AND a.tipo_mpio_id='$ciudad'
										AND a.tipo_comuna_id=b.tipo_comuna_id ORDER BY barrio";
								}
								$resultado =$dbconn->Execute($consulta);
									if($resultado->EOF)
									{
											?>	<option value="0"><?echo "--------NO HAY--------"?></option><?
									}
									else
									{
											?>	<option value="0"><?echo "--------SELECCIONE--------"?></option><?
									}
									while (!$resultado->EOF){
										$cod=$resultado->fields[0];
										if($cod==$barrio){
											?><option value="<?echo $cod.",".$resultado->fields[2].",".$resultado->fields[3].",".$resultado->fields[4]?>" selected><?echo $resultado->fields[1]?></option><?}
										else{
										?><option value="<?echo $cod.",".$resultado->fields[2].",".$resultado->fields[3].",".$resultado->fields[4]?>"><?echo $resultado->fields[1]?></option><?
										}
										$resultado->MoveNext();
								}
								$resultado->close();?>
							</select>
					</td>
					<td><?if($Edicion==0 && $cod){?><input class="input-bottom" type=submit name=EditarM value="Editar" onclick="EditarMpio(this.form.pais.value,this.form.dpto.value)"><?}?></td>
					</tr><?
						}
						else
						{?>
                 <input type=hidden name=barrio value=0 class=input-text>
				<?  } ?>
	<tr>
   <td colspan=3 align=center>
   <?php
	if(!(empty($_REQUEST['spia'])))
	{
		if($_SESSION['SELECTO']<>$_REQUEST['spia'])
		{
			$_SESSION['SELECTO']=$_REQUEST['spia'];
		}
	}
	$da=$_SESSION['SELECTO'];
	echo("<input type=\"hidden\" name=\"boton\"  value=\"".$da."\">");?>
	    <input type=submit name=Aceptar class="input-bottom" value="Aceptar" onClick="copiarValor(this.form.pais,this.form.dpto,this.form.mpio,this.form.comuna,this.form.barrio,this.form,this.form.boton)">
   </td>
  </tr>
 </table>

<?php
					$resp= $_REQUEST['resp'];
          if ($resp=='true')
					    {
								echo ("<table align=\"left\"><tr>
								 <td <font size=\"2\" color=\"BLUE\">Datos insertados</font></td>
								</tr></table>");
						 	}
							elseif($resp=='false')
							{
                  echo ("<table align=\"left\"><tr>
								 <td <font size=\"2\" color=\"GREEN\">Los Datos No se insertaron</font></td>
								</tr></table>");
							}
           ?>


</form>
<?
}
	if($Editar1){ ?>
	       <?php
					list($dbconn) = GetDBconn();
					$on  = "SELECT pais FROM tipo_pais WHERE tipo_pais_id='$pais'";
					$re=$dbconn->Execute($on);
					$nom=$re->fields[0];
          ?>
          <form name="insert" action="InsDestino.php" method="post">
				 <table  cellspacing=0 cellpadding=5 valign=bottom width=80%>
          <tr>
           <td class=label width=45%wrap>Nombre del Pais :</td>
					<td><?php echo "<font color=\"BLUE\">$nom</font>"; ?></td>
					<input type=hidden name=codpais1 value='<?php echo   $pais; ?>'>
          </tr>
					<tr>
					<td class=label width=25% wrap>Código Depto/Estado: : </td>
					<td><input type=text name=coddpto></td>
					</tr>
					<tr>
					<td class=label width=25% wrap>Nombre Depto/Estado: </td>
					<td><input type=text name=nompto></td>
					</tr>
          <?php
					$resp= $_REQUEST['resp'];
          if ($resp=='falso')
					    {
								echo ("<tr>
								 <td <font color=\"RED\">Datos incompletos</font></td>
								</tr>");
					  	}
							elseif($resp=='true')
					    {
								echo ("<tr>
								 <td <font color=\"RED\">Los Datos Existen </font></td>
								</tr>");
					  	}
           ?>
              <tr>
							<td align=right><input type=submit name=Aceptar value="Crear"></form></td>
							<form name="jaja" action="selector.php">
							<td><input type=submit name=Regresar value="Volver"></td></form>
							</tr>
					</table>
<?}

if($Editar){ ?>
          <?php
					list($dbconn) = GetDBconn();
					$on  = "SELECT pais FROM tipo_pais WHERE tipo_pais_id='$pais'";
					$re=$dbconn->Execute($on);
					$nom=$re->fields[0];
          $cosul="SELECT departamento FROM tipo_dptos WHERE tipo_dpto_id='$dpto'";
					$rer=$dbconn->Execute($cosul);
					$nomd=$rer->fields[0];

					?>
					<form name="inserta" action="InsDestino2.php" method="post">
			  	<table  cellspacing=0 cellpadding=5 valign=bottom width=80%>
					<tr>
					<td class=label width=45%wrap>Nombre del Pais :</td>
					<td><?php echo "<font color=\"BLUE\">$nom</font>"; ?></td>
					<input type=hidden name=codpais value='<?php echo   $pais; ?>'>
          </tr>
					<tr>
					<td class=label width=45%wrap>Nombre departamento :</td>
					<td><?php echo "<font color=\"BLUE\">$nomd</font>";?></td>
					<input type=hidden name=codepto value='<?php echo   $dpto ;?>'>
          </tr>
					<tr>
					<td class=label width=25% wrap>Código Ciudad/Municipio : </td>
					<td><input type=text name=codmpio></td>
					</tr>
					<tr>
					<td class=label width=25% wrap>Ciudad/Municipio: </td>
					<td><input type=text name=nompio></td>
					</tr>
					<?php
					$resp= $_REQUEST['resp'];
          if ($resp=='falso')
					    {
								echo ("<tr>
								 <td <font color=\"RED\">Datos incompletos</font></td>
								</tr>");
					  	}
							elseif($resp=='true')
					    {
								echo ("<tr>
								 <td <font color=\"RED\">Los Datos Existen </font></td>
								</tr>");
					  	}
           ?>
					<tr>
					<td align=right><input type=submit name=Aceptar value="Crear"></form></td>
          <form name="jaja" action="selector.php">
					<td><input type=submit name=Regresar value="Volver"></td></form>
          </tr>

      		</table>

<?}?>
