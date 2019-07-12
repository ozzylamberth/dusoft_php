<?php

$VISTA='HTML';
include 'includes/enviroment.inc.php';
include('conexion.php'); 
    if (!$dbconn) {
    echo "No hay Conexion.\n";
    exit;
}


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
<form name="t_terceros" action="index.php" method='POST'>
<table width="80%" border="0" cellspacing="0">
<tr>
<td align="center"><img src="img/logo_clinica.bmp" WIDTH="200" HEIGHT="140">
</td>
</tr>
<tr>
<td align="center">REPORTE DE REINGRESOS EN LAS 72 HRS ANTERIORES</td>
</tr>
  <table width="70%" border="0" cellspacing="0" align="center">
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

if (isset ($input9) AND isset ($input10))
{

//AGRUPA LAS CUENTAS A EVALUAR
$result_grupo=pg_exec($dbconn, "SELECT ingresos.ingreso, cuentas.numerodecuenta, pacientes.tipo_id_paciente, pacientes.paciente_id, pacientes.primer_apellido, pacientes.segundo_apellido, pacientes.primer_nombre, pacientes.segundo_nombre, ingresos.fecha_ingreso, planes.plan_descripcion, hc_diagnosticos_ingreso.sw_principal, diagnosticos.diagnostico_nombre, hc_diagnosticos_ingreso.tipo_diagnostico_id
 FROM   ((public.cuentas cuentas INNER JOIN (((public.hc_diagnosticos_ingreso hc_diagnosticos_ingreso INNER JOIN public.diagnosticos diagnosticos ON hc_diagnosticos_ingreso.tipo_diagnostico_id=diagnosticos.diagnostico_id) INNER JOIN public.hc_evoluciones hc_evoluciones ON hc_diagnosticos_ingreso.evolucion_id=hc_evoluciones.evolucion_id) INNER JOIN public.ingresos ingresos ON hc_evoluciones.ingreso=ingresos.ingreso) ON cuentas.ingreso=ingresos.ingreso) INNER JOIN public.planes planes ON cuentas.plan_id=planes.plan_id) INNER JOIN public.pacientes pacientes ON (ingresos.paciente_id=pacientes.paciente_id) AND (ingresos.tipo_id_paciente=pacientes.tipo_id_paciente)
 WHERE  (ingresos.fecha_ingreso>='$input9' AND ingresos.fecha_ingreso<='$input10') AND hc_diagnosticos_ingreso.sw_principal='1' 
 ORDER BY ingresos.ingreso"); 

if (!$result_grupo) {
    echo "Error en Consulta1.\n";
    exit;
}
?>
<br>
<table width="90%" border="0" cellspacing="0" align="center">
<tr> 
      <td  background="imagenes/cellpic1.gif" colspan="7" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2">Pacientes Re-Ingresados entre <?php echo $input9;?> y <?php echo $input10;?></font></strong></div></td>
    </tr>
  <tr> 
  <td width="9%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Ingreso</font></strong></a></td>
  <td width="9%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Cuenta</font></strong></a></td>
  <td width="10%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Fecha Re-Ingreso</font></strong></a></td>
  <td width="10%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Ingreso Anterior</font></strong></a></td>
  <td width="25%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Paciente</font></strong></a></td>
  <td width="17%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Plan</font></strong></a></td>
  <td width="20%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Diagnostico</font></strong></a></td>
  </tr>
<?php

while($fetch_grupo = pg_fetch_row($result_grupo)) {

$fecha =  $fetch_grupo[8];
$timestamp = strtotime($fecha);
$fecha_expira = $timestamp - (60*60*24*3);
$fecha_anterior = date("Y-m-d",$fecha_expira);

$result_max=pg_exec($dbconn, " SELECT ingresos.fecha_ingreso, ingresos.tipo_id_paciente, ingresos.paciente_id, hc_diagnosticos_ingreso.tipo_diagnostico_id, hc_diagnosticos_ingreso.sw_principal
 FROM   (public.hc_diagnosticos_ingreso hc_diagnosticos_ingreso INNER JOIN public.hc_evoluciones hc_evoluciones ON hc_diagnosticos_ingreso.evolucion_id=hc_evoluciones.evolucion_id) INNER JOIN public.ingresos ingresos ON hc_evoluciones.ingreso=ingresos.ingreso
 WHERE  (ingresos.tipo_id_paciente = '$fetch_grupo[2]' AND ingresos.paciente_id = '$fetch_grupo[3]' AND ingresos.fecha_ingreso ILIKE '%$fecha_anterior%') AND hc_diagnosticos_ingreso.tipo_diagnostico_id='$fetch_grupo[12]' AND hc_diagnosticos_ingreso.sw_principal='1'");


	if (!$result_max) {
    echo "Error en Consulta2.\n";
    exit;
	}

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
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_grupo[8]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_max[0]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_grupo[2]." ".$fetch_grupo[3]." ".$fetch_grupo[6]." ".$fetch_grupo[7]." ".$fetch_grupo[4]." ".$fetch_grupo[5]?></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_grupo[9]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_grupo[11]?></a></font></td>
  </tr>
  <?php

} 
}
}


?>

<?php
echo "</table>";






if(isset ($result_max)){
?>
<table width="90%" border="0" cellspacing="0" align="center">
<FORM ACTION='reportes_bioestadistica.php' METHOD='POST' >
<?php
echo"
<input type=hidden name='input9' value='$input9'>
<input type=hidden name='input10' value='$input10'>";
?>
<tr>
<td background="imagenes/cellpic1.gif" align="left">
<INPUT TYPE="SUBMIT" VALUE="IMPRIMIR">
</TD>
</FORM>
<FORM ACTION='reportes_bioestadistica1.php' METHOD='POST' >
<?php
echo"
<input type=hidden name='input9' value='$input9'>
<input type=hidden name='input10' value='$input10'>";
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