<?php
$VISTA='HTML';
include 'includes/enviroment.inc.php';
include('conexion.php'); 
    if (!$dbconn) {
    echo "No hay Conexion.\n";
    exit;
}
$input9 = $_REQUEST["input9"].' 00:00:00';
$input10 = $_REQUEST["input10"].' 23:59:59';

$t_tercero = $_REQUEST["t_tercero"];
$t_cirujano = $_REQUEST["t_cirujano"];
$t_anestesiologo = $_REQUEST["t_anestesiologo"];
$t_especialidades = $_REQUEST["t_especialidades"];
$t_realizadas = $_REQUEST["t_realizadas"];
$t_canceladas = $_REQUEST["t_canceladas"];
$t_anestesia = $_REQUEST["t_anestesia"];
$t_ambito = $_REQUEST["t_ambito"];
$t_urg_realizadas = $_REQUEST["t_urg_realizadas"];
$t_procedimientos = $_REQUEST["t_procedimientos"];


?>

<html>
<head>
<body background="#EEEEEE">
<title>Reportes de Cirugia</title>
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
<td align="center">REPORTES DE CIRUGIA</td>
</tr>
  <table width="90%" border="0" cellspacing="0" align="center">
    <tr> 
      <td  background="imagenes/cellpic1.gif" colspan="2" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2">B&uacute;squeda</font></strong></div></td>
    </tr>
			<tr>
      <td bgcolor="#EEEEEE">Fecha Desde:<br>
      <a href="javascript:cal9.popup();"><img src="img/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the date"></a>
				<input type="Text" name="input9" value="<?php echo $_REQUEST['input9'];?>">
				</td>
				<td bgcolor="#EEEEEE">
				Fecha Hasta:<br>
				<input type="Text" name="input10" value="<?php echo $_REQUEST['input10'];?>">
				<a href="javascript:cal10.popup();"><img src="img/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the date"></a><br></td>
			</tr>

<tr> 
<td bgcolor="#EEEEEE">Reporte Total Terceros:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="checkbox" name="t_tercero" value="<?php echo $t_tercero;?>" align="LEFT"/>
</td>
<td bgcolor="#EEEEEE">Reporte Total Cirujano:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="checkbox" name="t_cirujano" value="<?php echo $t_cirujano;?>" align="LEFT" />
</td>
</tr>
<tr> 
<td bgcolor="#EEEEEE">Reporte Total Anestesiologo:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="checkbox" name="t_anestesiologo" value="<?php echo $t_anestesiologo;?>" align="LEFT"/>
</td>
<td bgcolor="#EEEEEE">Reporte Total Especialidades:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="checkbox" name="t_especialidades" value="<?php echo $t_especialidades;?>" align="LEFT" />
</td>
</tr>
<tr> 
<td bgcolor="#EEEEEE">Reporte Programaciones Realizadas:
<input type="checkbox" name="t_realizadas" value="<?php echo $t_realizadas;?>" align="LEFT"/>
</td>
<td bgcolor="#EEEEEE">Reporte Programaciones Canceladas:
<input type="checkbox" name="t_canceladas" value="<?php echo $t_canceladas;?>" align="LEFT" />
</td>
</tr>
<tr> 
<td bgcolor="#EEEEEE">Reporte Liquidacion x Anestesia:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="checkbox" name="t_anestesia" value="<?php echo $t_anestesia;?>" align="LEFT"/>
</td>
<td bgcolor="#EEEEEE">Reporte Ambito Cirugia:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="checkbox" name="t_ambito" value="<?php echo $t_ambito;?>" align="LEFT" />
</td>
</tr>
<tr>
<td bgcolor="#EEEEEE">Cirugias Urgentes Realizadas:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="checkbox" name="t_urg_realizadas" value="<?php echo $t_urg_realizadas;?>" align="LEFT" />
</td>
<td bgcolor="#EEEEEE">Reporte Total x Procedimiento:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="checkbox" name="t_procedimientos" value="<?php echo $t_procedimientos;?>" align="LEFT" />
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


if (isset ($t_tercero)){
 
$rep_tercero = 1;
 $result_tercero=pg_exec($dbconn, " select distinct(terceros.tercero_id), terceros.nombre_tercero, count(*)
 FROM   ((public.cuentas_liquidaciones_qx cuentas_liquidaciones_qx INNER JOIN public.cuentas cuentas ON cuentas_liquidaciones_qx.numerodecuenta=cuentas.numerodecuenta) INNER JOIN public.planes planes ON cuentas.plan_id=planes.plan_id) INNER JOIN public.terceros terceros ON (planes.tipo_tercero_id=terceros.tipo_id_tercero) AND (planes.tercero_id=terceros.tercero_id)
 WHERE  (cuentas_liquidaciones_qx.fecha_cirugia>='$input9' AND cuentas_liquidaciones_qx.fecha_cirugia<='$input10') AND cuentas_liquidaciones_qx.departamento='020301' AND cuentas_liquidaciones_qx.estado<>'3' AND  NOT (cuentas.estado='4' OR cuentas.estado='5')
 group by 1,2
ORDER BY 3 Desc"); 

if (!$result_tercero) {
    echo "Error en Consulta.\n";
    exit;
}
?>
<br>
<table width="90%" border="0" cellspacing="0" align="center">
<tr> 
      <td  background="imagenes/cellpic1.gif" colspan="3" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2">Reporte Total Terceros desde <?php echo $_REQUEST["input9"];?> hasta <?php echo $_REQUEST["input10"];?></font></strong></div></td>
    </tr>
  <tr> 
  <td width="30%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">N IDENTIFICACION</font></strong></a></td>
  <td width="60%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">NOMBRE</font></strong></a></td>
  <td width="10%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">CANTIDAD</font></strong></a></td>
  </tr>
<?php
$suma_terceros = 0;
while($fetch_tercero = pg_fetch_row($result_tercero)) {
 
 if ($colorfila==0){
       $color= "#F0F0F0";
       $colorfila=1;
    }else{
       $color="white";
       $colorfila=0;
    }
    
?>

<tr bgcolor="#EEEEEE" onMouseOver="javascript: this.style.backgroundColor = '#CC9900';" onMouseOut="javascript: this.style.backgroundColor = '#EEEEEE';"> 
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_tercero[0]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_tercero[1]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_tercero[2]?></font></td>
  </tr>
  <?php
  $suma_terceros = $suma_terceros + $fetch_tercero[2];
}
?>
<tr> 
      <td  background="imagenes/cellpic1.gif" colspan="2" bgcolor="#EEEEEE"><div align="right"><strong><font color="#003366" size="2">TOTAL:</font></strong></div></td>
      <td  background="imagenes/cellpic1.gif" colspan="1" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2"><?php echo $suma_terceros;?></font></strong></div></td>
    </tr>
    <?php
echo "</table>";
}

if (isset ($t_cirujano)){
 
 $rep_cirujano = 1;

 $result_cirujano=pg_exec($dbconn, "select distinct(cuentas_liquidaciones_qx_procedimientos.cirujano_id), profesionales.nombre, count(*)
FROM   (public.profesionales_especialidades profesionales_especialidades INNER JOIN ((((((public.cuentas_liquidaciones_qx_procedimientos cuentas_liquidaciones_qx_procedimientos INNER JOIN public.cuentas_liquidaciones_qx cuentas_liquidaciones_qx ON cuentas_liquidaciones_qx_procedimientos.cuenta_liquidacion_qx_id=cuentas_liquidaciones_qx.cuenta_liquidacion_qx_id) INNER JOIN public.profesionales profesionales ON (cuentas_liquidaciones_qx_procedimientos.tipo_id_cirujano=profesionales.tipo_id_tercero) AND (cuentas_liquidaciones_qx_procedimientos.cirujano_id=profesionales.tercero_id)) INNER JOIN public.cups cups ON cuentas_liquidaciones_qx_procedimientos.cargo_cups=cups.cargo) INNER JOIN public.ingresos ingresos ON cuentas_liquidaciones_qx.ingreso=ingresos.ingreso) INNER JOIN public.cuentas cuentas ON (cuentas_liquidaciones_qx.numerodecuenta=cuentas.numerodecuenta) AND (ingresos.ingreso=cuentas.ingreso)) INNER JOIN public.pacientes pacientes ON (ingresos.paciente_id=pacientes.paciente_id) AND (ingresos.tipo_id_paciente=pacientes.tipo_id_paciente)) ON (profesionales_especialidades.tipo_id_tercero=profesionales.tipo_id_tercero) AND (profesionales_especialidades.tercero_id=profesionales.tercero_id)) INNER JOIN public.especialidades especialidades ON profesionales_especialidades.especialidad=especialidades.especialidad
 WHERE  cuentas_liquidaciones_qx_procedimientos.consecutivo_procedimiento = (select Max(b.consecutivo_procedimiento) from cuentas_liquidaciones_qx_procedimientos b where cuentas_liquidaciones_qx_procedimientos.cuenta_liquidacion_qx_id = b.cuenta_liquidacion_qx_id) AND (cuentas_liquidaciones_qx.fecha_cirugia>='$input9' AND cuentas_liquidaciones_qx.fecha_cirugia<='$input10') AND cuentas_liquidaciones_qx.departamento='020301' AND cuentas_liquidaciones_qx.estado<>'3' AND  NOT (cuentas.estado='4' OR cuentas.estado='5') 
 group by 1,2
order by 3 Desc"); 

if (!$result_cirujano) {
    echo "Error en Consulta.\n";
    exit;
}
?>
<br>
<table width="90%" border="0" cellspacing="0" align="center">
<tr> 
      <td  background="imagenes/cellpic1.gif" colspan="3" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2">Reporte Total Cirujanos <?php echo $_REQUEST["input9"];?> hasta <?php echo $_REQUEST["input10"];?></font></strong></div></td>
    </tr>
  <tr> 
  <td width="30%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">N IDENTIFICACION</font></strong></a></td>
  <td width="60%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">NOMBRE</font></strong></a></td>
  <td width="10%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">CANTIDAD</font></strong></a></td>
  </tr>
<?php
$suma_cirujanos = 0;
while($fetch_cirujano = pg_fetch_row($result_cirujano)) {
 if ($colorfila==0){
       $color= "#F0F0F0";
       $colorfila=1;
    }else{
       $color="white";
       $colorfila=0;
    }
    ?>

<tr bgcolor="#EEEEEE" onMouseOver="javascript: this.style.backgroundColor = '#CC9900';" onMouseOut="javascript: this.style.backgroundColor = '#EEEEEE';"> 
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_cirujano[0]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_cirujano[1]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_cirujano[2]?></font></td>
    </tr>
  <?php
  $suma_cirujanos = $suma_cirujanos + $fetch_cirujano[2];
}
?>
<tr> 
      <td  background="imagenes/cellpic1.gif" colspan="2" bgcolor="#EEEEEE"><div align="right"><strong><font color="#003366" size="2">TOTAL:</font></strong></div></td>
      <td  background="imagenes/cellpic1.gif" colspan="1" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2"><?php echo $suma_cirujanos;?></font></strong></div></td>
    </tr>
    <?php
echo "</table>";
}

if (isset ($t_anestesiologo)){
 
 $rep_anestesiologo = 1;

 $result_anestesiologo=pg_exec($dbconn, "select distinct(cuentas_liquidaciones_qx.anestesiologo_id), profesionales.nombre, count(*)
 FROM   (public.cuentas_liquidaciones_qx cuentas_liquidaciones_qx INNER JOIN public.cuentas cuentas ON cuentas_liquidaciones_qx.numerodecuenta=cuentas.numerodecuenta) INNER JOIN public.profesionales profesionales ON (cuentas_liquidaciones_qx.tipo_id_anestesiologo=profesionales.tipo_id_tercero) AND (cuentas_liquidaciones_qx.anestesiologo_id=profesionales.tercero_id)
 WHERE   NOT (cuentas.estado='4' OR cuentas.estado='5') AND cuentas_liquidaciones_qx.estado<>'3' AND cuentas_liquidaciones_qx.departamento='020301' AND (cuentas_liquidaciones_qx.fecha_cirugia>='$input9' AND cuentas_liquidaciones_qx.fecha_cirugia<='$input10')
 group by 1,2
ORDER BY 3 Desc"); 

if (!$result_anestesiologo) {
    echo "Error en Consulta.\n";
    exit;
}
?>
<br>
<table width="90%" border="0" cellspacing="0" align="center">
<tr> 
      <td  background="imagenes/cellpic1.gif" colspan="3" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2">Reporte Total Anestesiologos <?php echo $_REQUEST["input9"];?> hasta <?php echo $_REQUEST["input10"];?></font></strong></div></td>
    </tr>
  <tr> 
  <td width="30%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">N IDENTIFICACION</font></strong></a></td>
  <td width="60%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">NOMBRE</font></strong></a></td>
  <td width="10%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">CANTIDAD</font></strong></a></td>
  </tr>
<?php
$suma_anestesiologos = 0;
while($fetch_anestesiologo = pg_fetch_row($result_anestesiologo)) {
 if ($colorfila==0){
       $color= "#F0F0F0";
       $colorfila=1;
    }else{
       $color="white";
       $colorfila=0;
    }
?>

<tr bgcolor="#EEEEEE" onMouseOver="javascript: this.style.backgroundColor = '#CC9900';" onMouseOut="javascript: this.style.backgroundColor = '#EEEEEE';"> 
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_anestesiologo[0]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_anestesiologo[1]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_anestesiologo[2]?></font></td>
  </tr>
  <?php
  $suma_anestesiologos = $suma_anestesiologos + $fetch_anestesiologo[2];
}
?>
<tr> 
      <td  background="imagenes/cellpic1.gif" colspan="2" bgcolor="#EEEEEE"><div align="right"><strong><font color="#003366" size="2">TOTAL:</font></strong></div></td>
      <td  background="imagenes/cellpic1.gif" colspan="1" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2"><?php echo $suma_anestesiologos;?></font></strong></div></td>
    </tr>
    <?php
echo "</table>";
}

if (isset ($t_especialidades)){
 
 $rep_especialidades = 1;

 $result_especialidades=pg_exec($dbconn, "select distinct(profesionales_especialidades.especialidad), especialidades.descripcion, count(*)
FROM   (public.profesionales_especialidades profesionales_especialidades INNER JOIN ((((((public.cuentas_liquidaciones_qx_procedimientos cuentas_liquidaciones_qx_procedimientos INNER JOIN public.cuentas_liquidaciones_qx cuentas_liquidaciones_qx ON cuentas_liquidaciones_qx_procedimientos.cuenta_liquidacion_qx_id=cuentas_liquidaciones_qx.cuenta_liquidacion_qx_id) INNER JOIN public.profesionales profesionales ON (cuentas_liquidaciones_qx_procedimientos.tipo_id_cirujano=profesionales.tipo_id_tercero) AND (cuentas_liquidaciones_qx_procedimientos.cirujano_id=profesionales.tercero_id)) INNER JOIN public.cups cups ON cuentas_liquidaciones_qx_procedimientos.cargo_cups=cups.cargo) INNER JOIN public.ingresos ingresos ON cuentas_liquidaciones_qx.ingreso=ingresos.ingreso) INNER JOIN public.cuentas cuentas ON (cuentas_liquidaciones_qx.numerodecuenta=cuentas.numerodecuenta) AND (ingresos.ingreso=cuentas.ingreso)) INNER JOIN public.pacientes pacientes ON (ingresos.paciente_id=pacientes.paciente_id) AND (ingresos.tipo_id_paciente=pacientes.tipo_id_paciente)) ON (profesionales_especialidades.tipo_id_tercero=profesionales.tipo_id_tercero) AND (profesionales_especialidades.tercero_id=profesionales.tercero_id)) INNER JOIN public.especialidades especialidades ON profesionales_especialidades.especialidad=especialidades.especialidad
 WHERE  cuentas_liquidaciones_qx_procedimientos.consecutivo_procedimiento = (select Max(b.consecutivo_procedimiento) from cuentas_liquidaciones_qx_procedimientos b where cuentas_liquidaciones_qx_procedimientos.cuenta_liquidacion_qx_id = b.cuenta_liquidacion_qx_id) AND (cuentas_liquidaciones_qx.fecha_cirugia>='$input9' AND cuentas_liquidaciones_qx.fecha_cirugia<='$input10') AND cuentas_liquidaciones_qx.departamento='020301' AND cuentas_liquidaciones_qx.estado<>'3' AND  NOT (cuentas.estado='4' OR cuentas.estado='5') 
 group by 1,2
order by 3 Desc"); 

if (!$result_especialidades) {
    echo "Error en Consulta.\n";
    exit;
}
?>
<br>
<table width="90%" border="0" cellspacing="0" align="center">
<tr> 
      <td  background="imagenes/cellpic1.gif" colspan="3" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2">Reporte Total Especialidades <?php echo $_REQUEST["input9"];?> hasta <?php echo $_REQUEST["input10"];?></font></strong></div></td>
    </tr>
  <tr> 
  <td width="30%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">COD ESPECIALIDAD</font></strong></a></td>
  <td width="60%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">ESPECIALIDAD</font></strong></a></td>
  <td width="10%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">CANTIDAD</font></strong></a></td>
  </tr>
<?php
$suma_especialidades = 0;
while($fetch_especialidades = pg_fetch_row($result_especialidades)) {
 if ($colorfila==0){
       $color= "#F0F0F0";
       $colorfila=1;
    }else{
       $color="white";
       $colorfila=0;
    }
?>

<tr bgcolor="#EEEEEE" onMouseOver="javascript: this.style.backgroundColor = '#CC9900';" onMouseOut="javascript: this.style.backgroundColor = '#EEEEEE';"> 
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_especialidades[0]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_especialidades[1]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_especialidades[2]?></a></font></td>
  </tr>
  <?php
  $suma_especialidades = $suma_especialidades + $fetch_especialidades[2];
}
?>
<tr> 
      <td  background="imagenes/cellpic1.gif" colspan="2" bgcolor="#EEEEEE"><div align="right"><strong><font color="#003366" size="2">TOTAL:</font></strong></div></td>
      <td  background="imagenes/cellpic1.gif" colspan="1" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2"><?php echo $suma_especialidades;?></font></strong></div></td>
    </tr>
    <?php
echo "</table>";
}


if (isset ($t_realizadas)){
 $rep_realizadas = 1;

 $result_realizadas=pg_exec($dbconn, "select distinct(quirofano_id), count(*)
from qx_quirofanos_programacion
where (hora_inicio >= '$input9' and hora_inicio <='$input10')
group by 1"); 

if (!$result_realizadas) {
    echo "Error en Consulta.\n";
    exit;
}
?>
<br>
<table width="53%" border="0" cellspacing="0" align="center">
<tr> 
      <td  background="imagenes/cellpic1.gif" colspan="2" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2">Programaciones Realizadas <?php echo $_REQUEST["input9"];?> hasta <?php echo $_REQUEST["input10"];?></font></strong></div></td>
    </tr>
  <tr> 
  <td width="30%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">SALA</font></strong></a></td>
  <td width="10%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">CANTIDAD</font></strong></a></td>
  </tr>
<?php
$suma_realizadas = 0;
while($fetch_realizadas = pg_fetch_row($result_realizadas)) {
 if ($colorfila==0){
       $color= "#F0F0F0";
       $colorfila=1;
    }else{
       $color="white";
       $colorfila=0;
    }
?>

<tr bgcolor="#EEEEEE" onMouseOver="javascript: this.style.backgroundColor = '#CC9900';" onMouseOut="javascript: this.style.backgroundColor = '#EEEEEE';"> 
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_realizadas[0]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_realizadas[1]?></a></font></td>
  </tr>
  <?php
  $suma_realizadas = $suma_realizadas + $fetch_realizadas[1];
}
?>
<tr> 
      <td  background="imagenes/cellpic1.gif" colspan="1" bgcolor="#EEEEEE"><div align="right"><strong><font color="#003366" size="2">TOTAL:</font></strong></div></td>
      <td  background="imagenes/cellpic1.gif" colspan="1" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2"><?php echo $suma_realizadas;?></font></strong></div></td>
    </tr>
    <?php
echo "</table>";
}

if (isset ($t_canceladas)){
 
 $rep_canceladas = 1;

 $result_canceladas=pg_exec($dbconn, "select distinct(a.qx_motivo_cancelacion_programacion_id), b.descripcion, count(*)
from qx_programaciones_canceladas a, qx_motivos_cancelacion_programaciones b
where a.programacion_id in (select programacion_id from qx_quirofanos_programacion
                             where (hora_inicio >= '$input9' and hora_inicio <='$input10'))
and a.qx_motivo_cancelacion_programacion_id = b.qx_motivo_cancelacion_programacion_id 
group by 1, 2
ORDER BY 1"); 

if (!$result_canceladas) {
    echo "Error en Consulta.\n";
    exit;
}
?>
<br>
<table width="90%" border="0" cellspacing="0" align="center">
<tr> 
      <td  background="imagenes/cellpic1.gif" colspan="3" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2">Programaciones Canceladas <?php echo $_REQUEST["input9"];?> hasta <?php echo $_REQUEST["input10"];?></font></strong></div></td>
    </tr>
  <tr> 
  <td width="30%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">COD CANCELACION</font></strong></a></td>
  <td width="60%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">MOTIVO CANCELACION</font></strong></a></td>
  <td width="10%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">CANTIDAD</font></strong></a></td>
  </tr>
<?php
$suma_canceladas = 0;
while($fetch_canceladas = pg_fetch_row($result_canceladas)) {
 if ($colorfila==0){
       $color= "#F0F0F0";
       $colorfila=1;
    }else{
       $color="white";
       $colorfila=0;
    }
?>

<tr bgcolor="#EEEEEE" onMouseOver="javascript: this.style.backgroundColor = '#CC9900';" onMouseOut="javascript: this.style.backgroundColor = '#EEEEEE';"> 
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_canceladas[0]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_canceladas[1]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_canceladas[2]?></a></font></td>
  </tr>
  <?php
  $suma_canceladas = $suma_canceladas + $fetch_canceladas[2];
}
?>
<tr> 
      <td  background="imagenes/cellpic1.gif" colspan="2" bgcolor="#EEEEEE"><div align="right"><strong><font color="#003366" size="2">TOTAL:</font></strong></div></td>
      <td  background="imagenes/cellpic1.gif" colspan="1" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2"><?php echo $suma_canceladas;?></font></strong></div></td>
    </tr>
    <?php
echo "</table>";
}

if (isset ($t_anestesia)){
 
 $rep_anestesia = 1;
 
 	$modif = pg_exec($dbconn, "update cuentas_liquidaciones_qx
set qx_tipo_anestesia_id = '0'
where qx_tipo_anestesia_id is NULL");

if (!$modif) {
    echo "Error en Actualizacion.\n";
    exit;
}

 $result_anestesia=pg_exec($dbconn, "select distinct(a.qx_tipo_anestesia_id), b.descripcion, count(*)
from cuentas_liquidaciones_qx a, qx_tipos_anestesia b, cuentas c
 where 
a.fecha_cirugia>= '$input9' AND a.fecha_cirugia<='$input10' AND 

a.estado<>'3'
and a.departamento = '020301'
and a.qx_tipo_anestesia_id = b.qx_tipo_anestesia_id
and c.numerodecuenta = a.numerodecuenta
and c.estado not in ('4','5')
group by 1,2
ORDER BY 1"); 

if (!$result_anestesia) {
    echo "Error en Consulta.\n";
    exit;
}
?>
<br>
<table width="90%" border="0" cellspacing="0" align="center">
<tr> 
      <td  background="imagenes/cellpic1.gif" colspan="3" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2">Liq x Tipo de Anestesia <?php echo $_REQUEST["input9"];?> hasta <?php echo $_REQUEST["input10"];?></font></strong></div></td>
    </tr>
  <tr> 
  <td width="30%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">COD ANESTESIA</font></strong></a></td>
  <td width="60%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">TIPO ANESTESIA</font></strong></a></td>
  <td width="10%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">CANTIDAD</font></strong></a></td>
  </tr>
<?php
$suma_anestesia = 0;
while($fetch_anestesia = pg_fetch_row($result_anestesia)) {
 if ($colorfila==0){
       $color= "#F0F0F0";
       $colorfila=1;
    }else{
       $color="white";
       $colorfila=0;
    }
?>

<tr bgcolor="#EEEEEE" onMouseOver="javascript: this.style.backgroundColor = '#CC9900';" onMouseOut="javascript: this.style.backgroundColor = '#EEEEEE';"> 
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_anestesia[0]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_anestesia[1]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_anestesia[2]?></a></font></td>
  </tr>
  <?php
  $suma_anestesia = $suma_anestesia + $fetch_anestesia[2];
}
?>
<tr> 
      <td  background="imagenes/cellpic1.gif" colspan="2" bgcolor="#EEEEEE"><div align="right"><strong><font color="#003366" size="2">TOTAL:</font></strong></div></td>
      <td  background="imagenes/cellpic1.gif" colspan="1" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2"><?php echo $suma_anestesia;?></font></strong></div></td>
    </tr>
    <?php
echo "</table>";
}


if (isset ($t_ambito)){
 
 $rep_ambito = 1;
 
 $result_urg_realizadas=pg_exec($dbconn, "select  a.cuenta_liquidacion_qx_id, a.ingreso, a.numerodecuenta, p.tipo_id_paciente, p.paciente_id, p.primer_nombre, p.segundo_nombre, p.primer_apellido, p.segundo_apellido

from cuentas_liquidaciones_qx a,  cuentas c, ingresos i, pacientes p
 where 
a.fecha_cirugia>= '$input9' AND a.fecha_cirugia<='$input10' AND 
a.estado<>'3'
and c.numerodecuenta = a.numerodecuenta
and c.estado not in ('4','5')
and c.ingreso = i.ingreso
and i.tipo_id_paciente = p.tipo_id_paciente
and i.paciente_id = p.paciente_id
and a.departamento = '020301'
and a.programacion_id is NULL
and a.ambito_cirugia_id not in('01','02')
ORDER BY 1 "); 
while($fetch_urg_realizadas = pg_fetch_row($result_urg_realizadas)) {
 
 $suma_urg_realizadas++;
}
if (!$result_urg_realizadas) {
    echo "Error en Consulta.\n";
    exit;
}

 $result_ambito=pg_exec($dbconn, "select distinct(a.ambito_cirugia_id), b.descripcion, count(*)
from cuentas_liquidaciones_qx a, qx_ambitos_cirugias b, cuentas c
 where 
a.fecha_cirugia>= '$input9' AND a.fecha_cirugia<='$input10' AND 
a.estado<>'3'
and c.numerodecuenta = a.numerodecuenta
and c.estado not in ('4','5')
and a.departamento = '020301'
and a.ambito_cirugia_id = b.ambito_cirugia_id
group by 1,2
--having count(*) > 1
ORDER BY 1"); 


if (!$result_ambito) {
    echo "Error en Consulta.\n";
    exit;
}
?>
<br>
<table width="90%" border="0" cellspacing="0" align="center">
<tr> 
      <td  background="imagenes/cellpic1.gif" colspan="3" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2">Ambito de Cirugia <?php echo $_REQUEST["input9"];?> hasta <?php echo $_REQUEST["input10"];?></font></strong></div></td>
    </tr>
  <tr> 
  <td width="30%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">COD AMBITO</font></strong></a></td>
  <td width="60%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">DESCRIPCION</font></strong></a></td>
  <td width="10%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">CANTIDAD</font></strong></a></td>
  </tr>
<?php
$suma_ambito = 0;
while($fetch_ambito = pg_fetch_row($result_ambito)) {
 if ($colorfila==0){
       $color= "#F0F0F0";
       $colorfila=1;
    }else{
       $color="white";
       $colorfila=0;
    }
    
    if($fetch_ambito[0]=='03'){
      $fetch_ambito[2] = $suma_urg_realizadas;
	}
?>

<tr bgcolor="#EEEEEE" onMouseOver="javascript: this.style.backgroundColor = '#CC9900';" onMouseOut="javascript: this.style.backgroundColor = '#EEEEEE';"> 
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_ambito[0]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_ambito[1]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_ambito[2]?></a></font></td>
  </tr>
  <?php
  $suma_ambito = $suma_ambito + $fetch_ambito[2];
}
?>
<tr> 
      <td  background="imagenes/cellpic1.gif" colspan="2" bgcolor="#EEEEEE"><div align="right"><strong><font color="#003366" size="2">TOTAL:</font></strong></div></td>
      <td  background="imagenes/cellpic1.gif" colspan="1" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2"><?php echo $suma_ambito;?></font></strong></div></td>
    </tr>
    <?php
echo "</table>";
}

if (isset ($t_urg_realizadas)){
 
 $rep_urg_realizadas = 1;

 $result_urg_realizadas=pg_exec($dbconn, "select  a.cuenta_liquidacion_qx_id, a.ingreso, a.numerodecuenta, p.tipo_id_paciente, p.paciente_id, p.primer_nombre, p.segundo_nombre, p.primer_apellido, p.segundo_apellido

from cuentas_liquidaciones_qx a,  cuentas c, ingresos i, pacientes p
 where 
a.fecha_cirugia>= '$input9' AND a.fecha_cirugia<='$input10' AND 
a.estado<>'3'
and c.numerodecuenta = a.numerodecuenta
and c.estado not in ('4','5')
and c.ingreso = i.ingreso
and i.tipo_id_paciente = p.tipo_id_paciente
and i.paciente_id = p.paciente_id
and a.departamento = '020301'
and a.programacion_id is NULL
and a.ambito_cirugia_id not in('01','02')
ORDER BY 1 "); 

if (!$result_urg_realizadas) {
    echo "Error en Consulta.\n";
    exit;
}
?>
<br>
<table width="90%" border="0" cellspacing="0" align="center">
<tr> 
      <td  background="imagenes/cellpic1.gif" colspan="5" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2">Cirugias Urgentes Realizadas <?php echo $_REQUEST["input9"];?> hasta <?php echo $_REQUEST["input10"];?></font></strong></div></td>
    </tr>
  <tr> 
  <td width="5%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Liq_Qx</font></strong></a></td>
  <td width="10%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Ingreso</font></strong></a></td>
  <td width="10%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Cuenta</font></strong></a></td>
  <td width="35%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Identifacion</font></strong></a></td>
  <td width="40%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Nombre Paciente</font></strong></a></td>
  </tr>
<?php
$suma_urg_realizadas = 0;
while($fetch_urg_realizadas = pg_fetch_row($result_urg_realizadas)) {
 if ($colorfila==0){
       $color= "#F0F0F0";
       $colorfila=1;
    }else{
       $color="white";
       $colorfila=0;
    }
?>

<tr bgcolor="#EEEEEE" onMouseOver="javascript: this.style.backgroundColor = '#CC9900';" onMouseOut="javascript: this.style.backgroundColor = '#EEEEEE';"> 
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_urg_realizadas[0]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_urg_realizadas[1]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_urg_realizadas[2]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_urg_realizadas[3]." ".$fetch_urg_realizadas[4]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_urg_realizadas[5]." ".$fetch_urg_realizadas[6]." ".$fetch_urg_realizadas[7]." ".$fetch_urg_realizadas[8 ]?></a></font></td>
  </tr>
  <?php
  $suma_urg_realizadas++;
}
?>
<tr> 
      <td  background="imagenes/cellpic1.gif" colspan="4" bgcolor="#EEEEEE"><div align="right"><strong><font color="#003366" size="2">TOTAL:</font></strong></div></td>
      <td  background="imagenes/cellpic1.gif" colspan="1" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2"><?php echo $suma_urg_realizadas;?></font></strong></div></td>
    </tr>
    <?php
echo "</table>";
}

if (isset ($t_procedimientos)){
 
 $rep_procedimientos = 1;

 $result_procedimientos=pg_exec($dbconn, "select distinct(a.cargo_cups), b.descripcion, count(*)
from cuentas_liquidaciones_qx_procedimientos a, cups b, cuentas_liquidaciones_qx c, cuentas d
where a.cuenta_liquidacion_qx_id = c.cuenta_liquidacion_qx_id
and a.cargo_cups = b.cargo
and (c.fecha_cirugia >= '$input9' AND c.fecha_cirugia<= '$input10')
and c.estado <> '3'
and c.departamento = '020301'
and c.numerodecuenta = d.numerodecuenta
and d.estado not in ('4','5')
group by 1,2
ORDER BY 3 Desc"); 

if (!$result_procedimientos) {
    echo "Error en Consulta.\n";
    exit;
}
?>
<br>
<table width="90%" border="0" cellspacing="0" align="center">
<tr> 
      <td  background="imagenes/cellpic1.gif" colspan="3" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2">Procedimientos Realizados <?php echo $_REQUEST["input9"];?> hasta <?php echo $_REQUEST["input10"];?></font></strong></div></td>
    </tr>
  <tr> 
  <td width="30%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">COD CARGO</font></strong></a></td>
  <td width="60%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">NOMBRE</font></strong></a></td>
  <td width="10%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">CANTIDAD</font></strong></a></td>
  </tr>
<?php
$suma_procedimientos = 0;
while($fetch_procedimientos = pg_fetch_row($result_procedimientos)) {
 if ($colorfila==0){
       $color= "#F0F0F0";
       $colorfila=1;
    }else{
       $color="white";
       $colorfila=0;
    }
?>

<tr bgcolor="#EEEEEE" onMouseOver="javascript: this.style.backgroundColor = '#CC9900';" onMouseOut="javascript: this.style.backgroundColor = '#EEEEEE';"> 
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_procedimientos[0]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_procedimientos[1]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_procedimientos[2]?></a></font></td>
  </tr>
  <?php
  $suma_procedimientos = $suma_procedimientos + $fetch_procedimientos[2];
}
?>
<tr> 
      <td  background="imagenes/cellpic1.gif" colspan="2" bgcolor="#EEEEEE"><div align="right"><strong><font color="#003366" size="2">TOTAL:</font></strong></div></td>
      <td  background="imagenes/cellpic1.gif" colspan="1" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2"><?php echo $suma_procedimientos;?></font></strong></div></td>
    </tr>
    <?php
echo "</table>";
}


if(isset ($t_tercero) OR isset($t_cirujano) OR isset($t_anestesiologo) 
OR isset($t_especialidades) OR isset($t_realizadas) OR isset($t_canceladas)
OR isset($t_anestesia) OR isset($t_ambito) OR isset($t_urg_realizadas)
OR isset($t_procedimientos)){
?>
<table width="53%" border="0" cellspacing="0" align="center">
<TR>
<FORM ACTION='reportes_cx.php' METHOD='GET' >
<?php

echo"
<input type=hidden name='input9' value='$input9'>
<input type=hidden name='input10' value='$input10'>
<input type=hidden name='rep_tercero' value='$rep_tercero'>
<input type=hidden name='rep_cirujano' value='$rep_cirujano'>
<input type=hidden name='rep_especialidades' value='$rep_especialidades'>
<input type=hidden name='rep_anestesiologo' value='$rep_anestesiologo'>
<input type=hidden name='rep_realizadas' value='$rep_realizadas'>
<input type=hidden name='rep_canceladas' value='$rep_canceladas'>
<input type=hidden name='rep_anestesia' value='$rep_anestesia'>
<input type=hidden name='rep_ambito' value='$rep_ambito'>
<input type=hidden name='rep_urg_realizadas' value='$rep_urg_realizadas'>
<input type=hidden name='rep_procedimientos' value='$rep_procedimientos'>";
?>
<table width="53%" border="0" cellspacing="0" align="center">
<tr>
<td background="imagenes/cellpic1.gif" align="center">
<INPUT TYPE="SUBMIT" VALUE="IMPRIMIR">
</TD>
</TR>
</TABLE>
<?php
}
?>
</TABLE>