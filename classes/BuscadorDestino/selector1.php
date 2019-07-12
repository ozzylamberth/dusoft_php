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


if(!$Editar1 && !$Editar){
?>
</head>
<form name=forma method=GET action="selector.php">
 <table  cellspacing=0 cellpadding=5 valign=bottom width=500  bgcolor=#C4D1E2>
<tr>
   <td width=88 nowrap>
    País
   </td>
   <td>
	 <?
       list($dbconn) = GetDBconn();
		   $cons  = "SELECT bloqueado_edicion FROM tipo_pais WHERE tipo_pais_id='$pais'";
		   $resultado=$dbconn->Execute($cons);
	     $Edicion=$resultado->fields[0];
		   $consulta  = "SELECT tipo_pais_id,pais FROM tipo_pais order by pais";
       $resultado=$dbconn->Execute($consulta);
	 ?>
     <select name=pais  class="select" onChange="cambio(this.value)">
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
    <td colspan=2>
				<table  cellspacing=0 cellpadding=5 valign=bottom width=100%>
					<tr>
					<td class=label width=12% wrap>Departamento</td>
					<td width=25% >
							<select name=dpto class="select" onChange="cambioDpto(this.form)"><?
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


					if($Edicion==0){?><input type=submit name=EditarD value="Editar" onclick="EditarDpto(this.form.pais.value)"><?}?></td>
					</tr>
				</table>
		</td>
	</tr>
	<tr>
    <td colspan=2 width=60 wrap>
				<table  cellspacing=0 cellpadding=5 valign=bottom width=100%>
					<tr>
					<td class=label width=88 wrap>Ciudad</td>
					<td width=25% ><select name=mpio class="select"><?php
								$consulta  = "SELECT tipo_pais_id,tipo_dpto_id,tipo_mpio_id,municipio FROM tipo_mpios WHERE tipo_pais_id='$pais' AND tipo_dpto_id= '$dpto' ORDER BY municipio";
								$resultado =$dbconn->Execute($consulta);
									while (!$resultado->EOF){
										$cod=$resultado->fields[2];
										if($cod==$ciudad){
											?><option value="<?echo $cod?>" selected><?echo $resultado->fields[3]?></option><?}
										else{
										?><option value="<?echo $cod?>"><?echo $resultado->fields[3]?></option><?
										}
										$resultado->MoveNext();
									}
									$resultado->close();?>
							</select>
					</td>
					<td><?if($Edicion==0 && $cod){?><input type=submit name=EditarM value="Editar" onclick="EditarMpio(this.form.pais.value,this.form.dpto.value)"><?}?></td>
					</tr>
				</table>
		</td>
	</tr>

	<tr>

   <td colspan=2 align=center>
	    <input type=submit name=Aceptar value="Aceptar" onClick="copiarValor(this.form.pais,this.form.dpto,this.form.mpio,this.form)">
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
				 <table  cellspacing=0 cellpadding=5 valign=bottom width=100%>
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
			  	<table  cellspacing=0 cellpadding=5 valign=bottom width=100%>
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
