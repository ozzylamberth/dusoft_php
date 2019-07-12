<?php

require("includes/session.php");
/*require("includes/config.php");*/
require("includes/database.php");
require("includes/funciones.php");

open_database();


$input9 = $_GET["input9"];
$input10 = $_GET["input10"];
$plan_id = $_GET["plan_id"];
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
<table width="100%" border="0" cellspacing="0">
<tr>
<td align="center"><img src="img/logo_clinica.bmp" WIDTH="200" HEIGHT="140">
</td>
</tr>
<tr>
<td align="center">REPORTE DE COMPARACION DE CENSO</td>
</tr>
  <table width="90%" border="0" cellspacing="0" align="center">
    <tr> 
      <td  background="imagenes/cellpic1.gif" colspan="2" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2">B&uacute;squeda</font></strong></div></td>
    
			<tr>
      <td bgcolor="#EEEEEE">Fecha Desde:<br>
      <a href="javascript:cal9.popup();"><img src="img/cal.gif" width="20" height="20" border="0"  alt="Click Here to Pick up the date"></a>
				<input type="Text" name="input9" size="25" value="<?php echo $input9;?>">
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
				<input type="Text" name="input10" size="25" value="<?php echo $input10;?>">
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
				cal9.time_comp = true;
				var cal10 = new calendar3(document.forms['tstest'].elements['input10']);
				cal10.year_scroll = false;
				cal10.time_comp = true;
				
			//-->
			</script>			
	
<?php

if ($input9 <> '' AND $input10 <> '' AND $plan_id <> '')
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
?>
<br>
<table width="90%" border="0" cellspacing="0" align="center">
<tr> 
      <td  background="imagenes/cellpic1.gif" colspan="11" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2">Pacientes Ingresados entre <?php echo $input9;?> y <?php echo $input10;?></font></strong></div></td>
    </tr>
  <tr> 
  <td width="10%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Estacion Enf</font></strong></a></td>
  <td width="5%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Cama</font></strong></a></td>
  <td width="25%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Paciente</font></strong></a></td>
  <td width="5%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Cuenta</font></strong></a></td>
  <td width="5%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Ingreso</font></strong></a></td>
  <td width="15%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Plan</font></strong></a></td>
  <td width="10%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Estado</font></strong></a></td>
  <td width="10%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Tot Cuenta</font></strong></a></td>
  <td width="5%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Fecha Ingreso</font></strong></a></td>
  <td width="5%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Fecha Egreso</font></strong></a></td>
  <td width="5%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Dias</font></strong></a></td>
  </tr>
<?php

while($fetch_grupo = pg_fetch_row($result_grupo)) {
 
if($plan_id == 'TODOS'){
$result_max=execute_query($dbh, " SELECT estaciones_enfermeria.descripcion, movimientos_habitacion.cama, 					estaciones_enfermeria.estacion_id, movimientos_habitacion.fecha_ingreso, pacientes.tipo_id_paciente, pacientes.paciente_id, pacientes.primer_nombre, pacientes.segundo_nombre, pacientes.primer_apellido, pacientes.segundo_apellido, cuentas.numerodecuenta, ingresos.ingreso, planes.plan_descripcion, movimientos_habitacion.fecha_egreso, cuentas.total_cuenta, ingresos.fecha_ingreso
 FROM    ((((public.movimientos_habitacion movimientos_habitacion INNER JOIN public.cuentas cuentas ON movimientos_habitacion.numerodecuenta=cuentas.numerodecuenta) INNER JOIN public.estaciones_enfermeria estaciones_enfermeria ON movimientos_habitacion.estacion_id=estaciones_enfermeria.estacion_id) INNER JOIN public.ingresos ingresos ON (movimientos_habitacion.ingreso=ingresos.ingreso) AND (cuentas.ingreso=ingresos.ingreso)) INNER JOIN public.planes planes ON cuentas.plan_id=planes.plan_id) INNER JOIN public.pacientes pacientes ON (ingresos.paciente_id=pacientes.paciente_id) AND (ingresos.tipo_id_paciente=pacientes.tipo_id_paciente)
 WHERE  
 movimiento_id =(Select max(movimiento_id) from movimientos_habitacion WHERE numerodecuenta = '$fetch_grupo[0]')");
	} 
ELSE{
$result_max=execute_query($dbh, " SELECT estaciones_enfermeria.descripcion, movimientos_habitacion.cama, estaciones_enfermeria.estacion_id, movimientos_habitacion.fecha_ingreso, pacientes.tipo_id_paciente, pacientes.paciente_id, pacientes.primer_nombre, pacientes.segundo_nombre, pacientes.primer_apellido, pacientes.segundo_apellido, cuentas.numerodecuenta, ingresos.ingreso, planes.plan_descripcion, movimientos_habitacion.fecha_egreso, cuentas.total_cuenta, ingresos.fecha_ingreso
 FROM    ((((public.movimientos_habitacion movimientos_habitacion INNER JOIN public.cuentas cuentas ON movimientos_habitacion.numerodecuenta=cuentas.numerodecuenta) INNER JOIN public.estaciones_enfermeria estaciones_enfermeria ON movimientos_habitacion.estacion_id=estaciones_enfermeria.estacion_id) INNER JOIN public.ingresos ingresos ON (movimientos_habitacion.ingreso=ingresos.ingreso) AND (cuentas.ingreso=ingresos.ingreso)) INNER JOIN public.planes planes ON cuentas.plan_id=planes.plan_id) INNER JOIN public.pacientes pacientes ON (ingresos.paciente_id=pacientes.paciente_id) AND (ingresos.tipo_id_paciente=pacientes.tipo_id_paciente)
 WHERE  
 movimiento_id =(Select max(movimiento_id) from movimientos_habitacion WHERE numerodecuenta = '$fetch_grupo[0]')
 AND planes.plan_id = $plan_id"); 

}
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
 
IF (isset($fetch_max[13])){
	$estado = 'Paciente Egresado';
	
	
	$ingreso = explode ("-", $fetch_max[15]);
	$salida = explode ("-", $fetch_max[13]); 

//calculo timestam de las dos fechas
$timestamp1 = mktime(0,0,0,$ingreso[1],$ingreso[2],$ingreso[0]);
$timestamp2 = mktime(0,0,0,$salida[1],$salida[2],$salida[0]);


//resto a una fecha la otra
$segundos_diferencia = $timestamp1 - $timestamp2;
//echo $segundos_diferencia;

//convierto segundos en días
$dias_diferencia = $segundos_diferencia / (60 * 60 * 24);

//obtengo el valor absoulto de los días (quito el posible signo negativo)
$dias_diferencia = abs($dias_diferencia);

//quito los decimales a los días de diferencia
$dias_diferencia = floor($dias_diferencia);

}
ELSE{
	$estado = 'Paciente en Estancia';
	$ingreso = explode ("-", $fetch_max[15]);
	$hoy = date("Y-m-d - H:i:s");
	$hoy = explode ("-", $hoy);
	
	//calculo timestam de las dos fechas
$timestamp1 = mktime(0,0,0,$ingreso[1],$ingreso[2],$ingreso[0]);
$timestamp2 = mktime(0,0,0,$hoy[1],$hoy[2],$hoy[0]);

//resto a una fecha la otra
$segundos_diferencia = $timestamp1 - $timestamp2;
//echo $segundos_diferencia;

//convierto segundos en días
$dias_diferencia = $segundos_diferencia / (60 * 60 * 24);

//obtengo el valor absoulto de los días (quito el posible signo negativo)
$dias_diferencia = abs($dias_diferencia);

//quito los decimales a los días de diferencia
$dias_diferencia = floor($dias_diferencia);
}


?>

<tr bgcolor="#EEEEEE" onMouseOver="javascript: this.style.backgroundColor = '#CC9900';" onMouseOut="javascript: this.style.backgroundColor = '#EEEEEE';"> 
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_max[0]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_max[1]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_max[4]." ".$fetch_max[5]." ".$fetch_max[6]." ".$fetch_max[7]." ".$fetch_max[8]." ".$fetch_max[9]?></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_max[10]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_max[11]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_max[12]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $estado?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">$<?php echo number_format($fetch_max[14])?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_max[15]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_max[13]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $dias_diferencia?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $a?></a></font></td>
  </tr>
  <?php
 } 
}

//PACIENTE AUN ESTANCIA
?>
<tr> 
      <td  background="imagenes/cellpic1.gif" colspan="11" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2">Pacientes Hospitalizados</font></strong></div></td>
    </tr>
    <?php

 
 	if($plan_id == 'TODOS'){
$result_max1=execute_query($dbh, " SELECT estaciones_enfermeria.descripcion, movimientos_habitacion.cama, 					estaciones_enfermeria.estacion_id, movimientos_habitacion.fecha_ingreso, pacientes.tipo_id_paciente, pacientes.paciente_id, pacientes.primer_nombre, pacientes.segundo_nombre, pacientes.primer_apellido, pacientes.segundo_apellido, cuentas.numerodecuenta, ingresos.ingreso, planes.plan_descripcion, movimientos_habitacion.fecha_egreso, cuentas.total_cuenta, ingresos.fecha_ingreso
 FROM    ((((public.movimientos_habitacion movimientos_habitacion INNER JOIN public.cuentas cuentas ON movimientos_habitacion.numerodecuenta=cuentas.numerodecuenta) INNER JOIN public.estaciones_enfermeria estaciones_enfermeria ON movimientos_habitacion.estacion_id=estaciones_enfermeria.estacion_id) INNER JOIN public.ingresos ingresos ON (movimientos_habitacion.ingreso=ingresos.ingreso) AND (cuentas.ingreso=ingresos.ingreso)) INNER JOIN public.planes planes ON cuentas.plan_id=planes.plan_id) INNER JOIN public.pacientes pacientes ON (ingresos.paciente_id=pacientes.paciente_id) AND (ingresos.tipo_id_paciente=pacientes.tipo_id_paciente)
 WHERE  movimientos_habitacion.fecha_egreso IS NULL");
	} 
ELSE{
$result_max1=execute_query($dbh, " SELECT estaciones_enfermeria.descripcion, movimientos_habitacion.cama, estaciones_enfermeria.estacion_id, movimientos_habitacion.fecha_ingreso, pacientes.tipo_id_paciente, pacientes.paciente_id, pacientes.primer_nombre, pacientes.segundo_nombre, pacientes.primer_apellido, pacientes.segundo_apellido, cuentas.numerodecuenta, ingresos.ingreso, planes.plan_descripcion, movimientos_habitacion.fecha_egreso, cuentas.total_cuenta, ingresos.fecha_ingreso
 FROM    ((((public.movimientos_habitacion movimientos_habitacion INNER JOIN public.cuentas cuentas ON movimientos_habitacion.numerodecuenta=cuentas.numerodecuenta) INNER JOIN public.estaciones_enfermeria estaciones_enfermeria ON movimientos_habitacion.estacion_id=estaciones_enfermeria.estacion_id) INNER JOIN public.ingresos ingresos ON (movimientos_habitacion.ingreso=ingresos.ingreso) AND (cuentas.ingreso=ingresos.ingreso)) INNER JOIN public.planes planes ON cuentas.plan_id=planes.plan_id) INNER JOIN public.pacientes pacientes ON (ingresos.paciente_id=pacientes.paciente_id) AND (ingresos.tipo_id_paciente=pacientes.tipo_id_paciente)
 WHERE  movimientos_habitacion.fecha_egreso IS NULL AND planes.plan_id = $plan_id"); 

}
 
while($fetch_max1 = pg_fetch_row($result_max1)) { 
 	if ($colorfila==0){
       $color= "white";
       $colorfila=1;
    }else{
       $color="white";
       $colorfila=0;
    }
 
IF (isset($fetch_max1[13])){
	$estado = 'Paciente Egresado';
	
	$ingreso = explode ("-", $fetch_max1[15]);
	$salida = explode ("-", $fetch_max1[13]); 

//calculo timestam de las dos fechas
$timestamp1 = mktime(0,0,0,$ingreso[1],$ingreso[2],$ingreso[0]);
$timestamp2 = mktime(0,0,0,$salida[1],$salida[2],$salida[0]);

//resto a una fecha la otra
$segundos_diferencia = $timestamp1 - $timestamp2;
//echo $segundos_diferencia;

//convierto segundos en días
$dias_diferencia = $segundos_diferencia / (60 * 60 * 24);

//obtengo el valor absoulto de los días (quito el posible signo negativo)
$dias_diferencia = abs($dias_diferencia);

//quito los decimales a los días de diferencia
$dias_diferencia = floor($dias_diferencia);

}
ELSE{
	$estado = 'Paciente en Estancia';
	$ingreso = explode ("-", $fetch_max1[15]);
	$hoy = date("Y-m-d - H:i:s");
	$hoy = explode ("-", $hoy);
	
	//calculo timestam de las dos fechas
$timestamp1 = mktime(0,0,0,$ingreso[1],$ingreso[2],$ingreso[0]);
$timestamp2 = mktime(0,0,0,$hoy[1],$hoy[2],$hoy[0]);

//resto a una fecha la otra
$segundos_diferencia = $timestamp1 - $timestamp2;
//echo $segundos_diferencia;

//convierto segundos en días
$dias_diferencia = $segundos_diferencia / (60 * 60 * 24);

//obtengo el valor absoulto de los días (quito el posible signo negativo)
$dias_diferencia = abs($dias_diferencia);

//quito los decimales a los días de diferencia
$dias_diferencia = floor($dias_diferencia);
}
 
?>

<tr bgcolor="#EEEEEE" onMouseOver="javascript: this.style.backgroundColor = '#CC9900';" onMouseOut="javascript: this.style.backgroundColor = '#EEEEEE';"> 
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_max1[0]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_max1[1]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_max1[4]." ".$fetch_max1[5]." ".$fetch_max1[6]." ".$fetch_max1[7]." ".$fetch_max1[8]." ".$fetch_max1[9]?></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_max1[10]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_max1[11]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_max1[12]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $estado?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">$<?php echo number_format($fetch_max1[14])?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_max1[15]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_max1[13]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $dias_diferencia?></a></font></td>
  </tr>
  <?php
 } 
}


?>

<?php
echo "</table>";






if(isset ($result_max)){
?>
<table width="100%" border="0" cellspacing="0" align="center">
<tr>
<td background="imagenes/cellpic1.gif" align="left"><input type="button" name="Submit3" value="Imprimir Listado" onClick="javascript: window.open('reportes_censo.php?input9=<?=$input9?>&amp;input10=<?=$input10?>&amp;plan_id=<?=$plan_id?>', 'imprimirlistado');" ></td>
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