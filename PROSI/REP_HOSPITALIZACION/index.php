<?php
require("includes/session.php");
/*require("includes/config.php");*/
require("includes/database.php");
require("includes/funciones.php");


$input9 = $_REQUEST["input9"].' 00:00:00';
$input10 = $_REQUEST["input10"].' 23:59:59';
$plan_id = $_REQUEST["plan_id"];

open_database();

$query = "DELETE FROM tmp_reporte_hospitalizacion"; 
$result = execute_query($dbh,$query); 


?>

<html>
<head>
<body background="#EEEEEE">
<title>Reporte de Censo</title>
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
<td align="center">REPORTE DE COMPARACION DE CENSO</td>
</tr>
  <table width="70%" border="0" cellspacing="0" align="center">
    <tr> 
      <td  background="imagenes/cellpic1.gif" colspan="2" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2">B&uacute;squeda</font></strong></div></td>
    
			<tr>
      <td bgcolor="#EEEEEE">Fecha Desde:<br>
      <a href="javascript:cal9.popup();"><img src="img/cal.gif" width="20" height="20" border="0"  alt="Click Here to Pick up the date"></a>
				<input type="Text" name="input9" size="25" value="<?php echo $_REQUEST['input9'];?>">
				</td>
		<td bgcolor="#EEEEEE">Plan:<br>
				<?php
				print ("
				<select name=\"plan_id\"  >
				");
				
				
				print ("<option selected value='TODOS'>Todos los Planes</option>");
				
				
				$result_combo=execute_query($dbh,"SELECT  plan_id, plan_descripcion
				FROM  planes WHERE estado = '1' ORDER BY 2");
							
				while($fila = pg_fetch_row($result_combo)){
				print("<option value=\"$fila[0]\"  ");
				if ($fila[0] == $plan_id) {
				print ("selected");
				}
				
				print(">$fila[1]</option>\n
				");
				}
				print("</select>");
				?>
				</td>
				</tr>
				<tr>
				<td bgcolor="#EEEEEE">
				Fecha Hasta:<br>
				<a href="javascript:cal10.popup();"><img src="img/cal.gif" width="20" height="20" border="0" alt="Click Here to Pick up the date"></a>
				<input type="Text" name="input10" size="25" value="<?php echo $_REQUEST['input10'];?>">
				</td>
				<td bgcolor="#EEEEEE">
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
				cal10.year_scroll = false;
				cal10.time_comp = false;
				
			//-->
			</script>			
	
<?php

if (isset ($input9) AND isset ($input10) AND isset ($plan_id))
{

//AGRUPA LAS CUENTAS A EVALUAR

$result_grupo=execute_query($dbh, "SELECT b.numerodecuenta
 FROM   ingresos a, cuentas b
 WHERE  a.fecha_ingreso>='$input9' AND a.fecha_ingreso<='$input10' AND a.ingreso = b.ingreso
 GROUP BY b.numerodecuenta 
 ORDER BY b.numerodecuenta"); 

if (!$result_grupo) {
    echo "Error en Consulta1.\n";
    exit;
}
while($fetch_grupo = pg_fetch_row($result_grupo)) {
 
$insert_pacientes_hospitalizados="insert into tmp_reporte_hospitalizacion(numerodecuenta) 
											VALUES($fetch_grupo[0]);";
											execute_query($dbh,$insert_pacientes_hospitalizados);

$result_max=execute_query($dbh, "SELECT cuentas.numerodecuenta, ingresos.ingreso, cuentas_liquidaciones_qx.cuenta_liquidacion_qx_id, cuentas_liquidaciones_qx_procedimientos.cargo_cups, ingresos.fecha_ingreso, cups.grupo_tarifario_id, cups.subgrupo_tarifario_id
 FROM   (((public.cuentas_liquidaciones_qx_procedimientos cuentas_liquidaciones_qx_procedimientos INNER JOIN public.cuentas_liquidaciones_qx cuentas_liquidaciones_qx ON cuentas_liquidaciones_qx_procedimientos.cuenta_liquidacion_qx_id=cuentas_liquidaciones_qx.cuenta_liquidacion_qx_id) INNER JOIN public.cups cups ON cuentas_liquidaciones_qx_procedimientos.cargo_cups=cups.cargo) INNER JOIN public.cuentas cuentas ON cuentas_liquidaciones_qx.numerodecuenta=cuentas.numerodecuenta) INNER JOIN public.ingresos ingresos ON (cuentas_liquidaciones_qx.ingreso=ingresos.ingreso) AND (cuentas.ingreso=ingresos.ingreso)
 WHERE  cuentas.numerodecuenta = $fetch_grupo[0]
 ORDER BY cuentas.numerodecuenta"); 

if (!$result_max) {
    echo "Error en Consulta2.\n";
    exit;
	}
 
while($fetch_max = pg_fetch_row($result_max)) {



											
if ($fetch_max[5] == '10')
{
	if ($fetch_max[6] == '00')
	{
		$update_pacientes_hospitalizados="update tmp_reporte_hospitalizacion SET sw_cx_cardio = '1'
											WHERE numerodecuenta = $fetch_grupo[0]";
											execute_query($dbh,$update_pacientes_hospitalizados);
	}
	if ($fetch_max[6] == '08')
	{
		$update_pacientes_hospitalizados="update tmp_reporte_hospitalizacion SET sw_cx_cardio = '1'
											WHERE numerodecuenta = $fetch_grupo[0]";
											execute_query($dbh,$update_pacientes_hospitalizados);
	}
}
else if ($fetch_max[5] == '12') 
{
		$update_pacientes_hospitalizados="update tmp_reporte_hospitalizacion SET sw_cx_cardio = '1'
											WHERE numerodecuenta = $fetch_grupo[0]";
											execute_query($dbh,$update_pacientes_hospitalizados);
}
else{
	
}


}
}


?>


<?php
}






if(isset ($result_max)){
?>
<table width="90%" border="0" cellspacing="0" align="center">
<FORM ACTION='reportes_censo.php' METHOD='POST' >
<?php
echo"
<input type=hidden name='input9' value='$input9'>
<input type=hidden name='input10' value='$input10'>
<input type=hidden name='plan_id' value='$plan_id'>";
?>
<tr>
<td background="imagenes/cellpic1.gif" align="left">
<INPUT TYPE="SUBMIT" VALUE="IMPRIMIR">
</TD>
</FORM>
<FORM ACTION='reportes_censo1.php' METHOD='POST' >
<?php
echo"
<input type=hidden name='input9' value='$input9'>
<input type=hidden name='input10' value='$input10'>
<input type=hidden name='plan_id' value='$plan_id'>";
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