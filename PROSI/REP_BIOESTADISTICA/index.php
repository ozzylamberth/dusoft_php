<?php

require("includes/session.php");
/*require("includes/config.php");*/
require("includes/database.php");
require("includes/funciones.php");

$input9 = $_GET["input9"];
$input10 = $_GET["input10"];
$departamento = $_GET["departamento"];

open_database();
?>

<html>
<head>
<body background="#EEEEEE">
<title>Reporte de Bioestadistica</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="Estilos.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>
<script language="JavaScript" src="js/overlib_mini.js"></script>
<script language="JavaScript" src="js/calendar.js"></script>
<script language="JavaScript" src="calendar3.js"></script>

<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<form name="tstest">
<form name="t_terceros" action="index.php" method='GET'>
<table width="100%" border="0" cellspacing="0">
<tr>
<td align="center"><img src="img/logo_clinica.bmp" WIDTH="200" HEIGHT="140">
</td>
</tr>
<tr>
<td align="center">REPORTE DE REINGRESOS</td>
</tr>
  <table width="90%" border="0" cellspacing="0" align="center">
    <tr> 
      <td  background="imagenes/cellpic1.gif" colspan="2" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2">B&uacute;squeda</font></strong></div></td>
    
			<tr>
      <td bgcolor="#EEEEEE">Fecha Desde:<br>
      <a href="javascript:cal9.popup();"><img src="img/cal.gif" width="20" height="20" border="0"  alt="Click Here to Pick up the date"></a>
				<input type="Text" name="input9" size="25" value="<?php echo $input9;?>">
				</td>
				<td bgcolor="#EEEEEE">
				Fecha Hasta:<br>
				<a href="javascript:cal10.popup();"><img src="img/cal.gif" width="20" height="20" border="0" alt="Click Here to Pick up the date"></a>
				<input type="Text" name="input10" size="25" value="<?php echo $input10;?>">
				</td>
				</tr>
			
	<tr>
		<td bgcolor="#EEEEEE">
		  <p>
		    <label>
		      <input type="radio" name="departamento" value="1"  CHECKED>
		      Hospitalizacion</label>
		    <br>
		    <label>
		      <input type="radio" name="departamento" value="2">
		      Urgencias</label>
		    <br>
	      </p>
		  </td>
		 <td bgcolor="#EEEEEE">
		 
		 </td> 			
<tr> 
<td bgcolor="#EEEEEE"><input type="submit" name="buscar" value="Buscar"></td>
<td bgcolor="#EEEEEE">&nbsp;</td>
</tr>
</table>
</form>
<script language="JavaScript">
			<!-- // create calendar object(s) just after form tag closed
				 // specify form element as the only parameter (document.forms['formname'].elements['inputname']);
				 // note: you can have as many calendar objects as you need for your application
				

				var cal9 = new calendar3(document.forms['tstest'].elements['input9']);
				cal9.year_scroll = true;
				cal9.time_comp = false;
				var cal10 = new calendar3(document.forms['tstest'].elements['input10']);
				cal10.year_scroll = true;
				cal10.time_comp = false;
				
			//-->
			</script>			
	
<?php

if ($input9 <> '' AND  $input10 <> '')
{

//PACIENTES CON RE-INGRESO EN HOSPITALIZACION

	if ($departamento == 1){
	
	
	
	$result_grupo=execute_query($dbh, "SELECT ingresos.ingreso, cuentas.numerodecuenta, ingresos.fecha_ingreso, pacientes.tipo_id_paciente, pacientes.paciente_id, pacientes.primer_apellido, pacientes.segundo_apellido, pacientes.primer_nombre, pacientes.segundo_nombre, planes.plan_descripcion, departamentos.descripcion, hc_diagnosticos_egreso.sw_principal, servicios.servicio, hc_vistosok_salida_detalle.fecha_registro, diagnosticos.diagnostico_nombre, diagnosticos.diagnostico_id
 FROM   (((((public.cuentas cuentas INNER JOIN (((public.hc_diagnosticos_egreso hc_diagnosticos_egreso INNER JOIN public.diagnosticos diagnosticos ON hc_diagnosticos_egreso.tipo_diagnostico_id=diagnosticos.diagnostico_id) INNER JOIN public.hc_evoluciones hc_evoluciones ON hc_diagnosticos_egreso.evolucion_id=hc_evoluciones.evolucion_id) INNER JOIN public.ingresos ingresos ON hc_evoluciones.ingreso=ingresos.ingreso) ON cuentas.ingreso=ingresos.ingreso) INNER JOIN public.planes planes ON cuentas.plan_id=planes.plan_id) INNER JOIN public.departamentos departamentos ON ingresos.departamento_actual=departamentos.departamento) INNER JOIN public.pacientes pacientes ON (ingresos.paciente_id=pacientes.paciente_id) AND (ingresos.tipo_id_paciente=pacientes.tipo_id_paciente)) INNER JOIN public.hc_vistosok_salida_detalle hc_vistosok_salida_detalle ON ingresos.ingreso=hc_vistosok_salida_detalle.ingreso) INNER JOIN public.servicios servicios ON departamentos.servicio=servicios.servicio
 WHERE  (ingresos.fecha_ingreso>='$input9' AND ingresos.fecha_ingreso<'$input10') AND hc_diagnosticos_egreso.sw_principal='1' AND servicios.servicio='1' ORDER BY pacientes.paciente_id, ingresos.ingreso"); 

		
	
	
?>
<br>
<table width="100%" border="0" cellspacing="0" align="center">
<tr> 
      <td  background="imagenes/cellpic1.gif" colspan="8" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2">Pacientes Re-Ingresados entre <?php echo $input9;?> y <?php echo $input10;?></font></strong></div></td>
    </tr>
  <tr> 
  <td width="7%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Ingreso</font></strong></a></td>
  <td width="7%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Cuenta</font></strong></a></td>
  <td width="8%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Fecha Re-Ingreso</font></strong></a></td>
  <td width="8%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Ingreso Anterior</font></strong></a></td>
  <td width="15%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Paciente</font></strong></a></td>
  <td width="15%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Plan</font></strong></a></td>
  <td width="20%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Diagnostico Egreso</font></strong></a></td>
  <td width="20%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Egreso Anterior</font></strong></a></td>
  </tr>	
  
<?php
$numero_dias = (60*60*24*20);
while($fetch_grupo = pg_fetch_row($result_grupo)) {

$fecha =  $fetch_grupo[13];
$fecha1 = substr ($fecha,0,19);
$timestamp = strtotime($fecha1);
$fecha_expira = $timestamp -($numero_dias);
$fecha_anterior = date("Y-m-d",$fecha_expira);

$afo = substr ($fetch_grupo[15], 0, 3);



$result_max=execute_query($dbh, "  SELECT ingresos.ingreso, pacientes.tipo_id_paciente, pacientes.paciente_id, ingresos.fecha_ingreso, diagnosticos.diagnostico_id, hc_diagnosticos_egreso.sw_principal, diagnosticos.diagnostico_nombre
 FROM   (((public.hc_diagnosticos_egreso hc_diagnosticos_egreso INNER JOIN public.diagnosticos diagnosticos ON hc_diagnosticos_egreso.tipo_diagnostico_id=diagnosticos.diagnostico_id) INNER JOIN public.hc_evoluciones hc_evoluciones ON hc_diagnosticos_egreso.evolucion_id=hc_evoluciones.evolucion_id) INNER JOIN public.ingresos ingresos ON hc_evoluciones.ingreso=ingresos.ingreso) INNER JOIN public.pacientes pacientes ON (ingresos.paciente_id=pacientes.paciente_id) AND (ingresos.tipo_id_paciente=pacientes.tipo_id_paciente)
 WHERE  pacientes.paciente_id='$fetch_grupo[4]' 
 AND pacientes.tipo_id_paciente='$fetch_grupo[3]' 
 AND (ingresos.fecha_ingreso>='$fecha_anterior' 
 AND ingresos.fecha_ingreso<'$fetch_grupo[2]')  
 AND diagnosticos.diagnostico_id LIKE '$afo%' 
 AND hc_diagnosticos_egreso.sw_principal='1'");


	

while($fetch_max = pg_fetch_row($result_max)) { 
 	if ($colorfila==0){
       $color= "#F0F0F0";
       $colorfila=1;
    }else{
       $color="white";
       $colorfila=0;
    }
	
	
 
?>

<tr bgcolor="#EEEEEE" onMouseOver="javascript: this.style.backgroundColor = '#CC9900';" onMouseOut="javascript: this.style.backgroundColor = '#EEEEEE';"> 
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_grupo[0]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_grupo[1]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_grupo[2]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_max[3]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_grupo[3]." ".$fetch_grupo[4]." ".$fetch_grupo[7]." ".$fetch_grupo[8]." ".$fetch_grupo[5]." ".$fetch_grupo[6]?></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_grupo[9]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_grupo[14]?></a></font></td>
	<td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_max[6]?></a></font></td>
  </tr>
  <?php
  }
  }
  }

//PACIENTES CON RE-INGRESO EN URGENCIAS
    
else {
	
	$numero_dias = (60*60*24*3);
	
	$result_grupo1=execute_query($dbh, "  SELECT ingresos.ingreso, cuentas.numerodecuenta, ingresos.fecha_ingreso, pacientes.tipo_id_paciente, pacientes.paciente_id, pacientes.primer_apellido, pacientes.segundo_apellido, pacientes.primer_nombre, pacientes.segundo_nombre, planes.plan_descripcion, departamentos.descripcion, hc_diagnosticos_ingreso.sw_principal, servicios.servicio, hc_vistosok_salida_detalle.fecha_registro, diagnosticos.diagnostico_nombre, diagnosticos.diagnostico_id
 FROM   (((((public.cuentas cuentas INNER JOIN (((public.hc_diagnosticos_ingreso hc_diagnosticos_ingreso INNER JOIN public.diagnosticos diagnosticos ON hc_diagnosticos_ingreso.tipo_diagnostico_id=diagnosticos.diagnostico_id) INNER JOIN public.hc_evoluciones hc_evoluciones ON hc_diagnosticos_ingreso.evolucion_id=hc_evoluciones.evolucion_id) INNER JOIN public.ingresos ingresos ON hc_evoluciones.ingreso=ingresos.ingreso) ON cuentas.ingreso=ingresos.ingreso) INNER JOIN public.planes planes ON cuentas.plan_id=planes.plan_id) INNER JOIN public.departamentos departamentos ON ingresos.departamento_actual=departamentos.departamento) INNER JOIN public.pacientes pacientes ON (ingresos.paciente_id=pacientes.paciente_id) AND (ingresos.tipo_id_paciente=pacientes.tipo_id_paciente)) INNER JOIN public.hc_vistosok_salida_detalle hc_vistosok_salida_detalle ON ingresos.ingreso=hc_vistosok_salida_detalle.ingreso) INNER JOIN public.servicios servicios ON departamentos.servicio=servicios.servicio
 WHERE  (ingresos.fecha_ingreso>='$input9' AND ingresos.fecha_ingreso<'$input10') AND hc_diagnosticos_ingreso.sw_principal='1' AND servicios.servicio='4' ORDER BY pacientes.paciente_id, ingresos.ingreso"); 

		
	

?>
<br>
<table width="100%" border="0" cellspacing="0" align="center">
<tr> 
      <td  background="imagenes/cellpic1.gif" colspan="8" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2">Pacientes Re-Ingresados entre <?php echo $input9;?> y <?php echo $input10;?></font></strong></div></td>
    </tr>
  <tr> 
  <td width="7%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Ingreso</font></strong></a></td>
  <td width="7%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Cuenta</font></strong></a></td>
  <td width="8%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Fecha Re-Ingreso</font></strong></a></td>
  <td width="8%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Ingreso Anterior</font></strong></a></td>
  <td width="15%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Paciente</font></strong></a></td>
  <td width="15%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Plan</font></strong></a></td>
  <td width="20%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Diagnostico Ingreso</font></strong></a></td>
  <td width="20%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Ingreso Anterior</font></strong></a></td>
  </tr>
<?php

while($fetch_grupo1 = pg_fetch_row($result_grupo1)) {

$fecha =  $fetch_grupo1[13];
$fecha1 = substr ($fecha,0,19);
$timestamp = strtotime($fecha1);
$fecha_expira = $timestamp -($numero_dias);
$fecha_anterior = date("Y-m-d",$fecha_expira);

$afo = substr ($fetch_grupo1[15], 0, 3);

$result_max1=execute_query($dbh, " SELECT ingresos.ingreso, pacientes.tipo_id_paciente, pacientes.paciente_id, ingresos.fecha_ingreso, diagnosticos.diagnostico_id, hc_diagnosticos_ingreso.sw_principal, diagnosticos.diagnostico_nombre
 FROM   (((public.hc_diagnosticos_ingreso hc_diagnosticos_ingreso INNER JOIN public.diagnosticos diagnosticos ON hc_diagnosticos_ingreso.tipo_diagnostico_id=diagnosticos.diagnostico_id) INNER JOIN public.hc_evoluciones hc_evoluciones ON hc_diagnosticos_ingreso.evolucion_id=hc_evoluciones.evolucion_id) INNER JOIN public.ingresos ingresos ON hc_evoluciones.ingreso=ingresos.ingreso) INNER JOIN public.pacientes pacientes ON (ingresos.paciente_id=pacientes.paciente_id) AND (ingresos.tipo_id_paciente=pacientes.tipo_id_paciente)
 WHERE  pacientes.paciente_id='$fetch_grupo1[4]' 
 AND pacientes.tipo_id_paciente='$fetch_grupo1[3]' 
 AND (ingresos.fecha_ingreso>='$fecha_anterior' 
 AND ingresos.fecha_ingreso<'$fetch_grupo1[2]') 
 AND diagnosticos.diagnostico_id LIKE '$afo%' 
 AND hc_diagnosticos_ingreso.sw_principal='1'");


	if (!$result_max1) {
    echo "Error en Consulta2.\n";
    exit;
	}

while($fetch_max1 = pg_fetch_row($result_max1)) { 
 	if ($colorfila==0){
       $color= "#F0F0F0";
       $colorfila=1;
    }else{
       $color="white";
       $colorfila=0;
    }
	?>
	<tr bgcolor="#EEEEEE" onMouseOver="javascript: this.style.backgroundColor = '#CC9900';" onMouseOut="javascript: this.style.backgroundColor = '#EEEEEE';"> 
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_grupo1[0]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_grupo1[1]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_grupo1[2]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_max1[3]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_grupo1[3]." ".$fetch_grupo1[4]." ".$fetch_grupo1[7]." ".$fetch_grupo1[8]." ".$fetch_grupo1[5]." ".$fetch_grupo1[6]?></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_grupo1[9]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_grupo1[14]?></a></font></td>
	<td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_max1[6]?></a></font></td>
  </tr>
  <?php
  }
  }
  }
  

}


?>

<?php
echo "</table>";






if(isset ($result_max) OR isset ($result_max1)){
?>
<table width="100%" border="0" cellspacing="0" align="center">
<td background="imagenes/cellpic1.gif" align="left"><input type="button" name="Submit3" value="Imprimir Listado" onClick="javascript: window.open('reportes_bioestadistica.php?input9=<?=$input9?>&amp;input10=<?=$input10?>&amp;departamento=<?=$departamento?>', 'imprimirlistado');" ></td>
<FORM ACTION='reportes_bioestadistica1.php' METHOD='POST' >
<?php
echo"
<input type=hidden name='input9' value='$input9'>
<input type=hidden name='input10' value='$input10'>
<input type=hidden name='departamento' value='$departamento'>";
?>
<td background="imagenes/cellpic1.gif" align="left">
<INPUT TYPE="SUBMIT" VALUE="EXPORTAR A EXCEL">
</TD>
</FORM>

</TR>
</TABLE>
<?php
}
?>
</TABLE>