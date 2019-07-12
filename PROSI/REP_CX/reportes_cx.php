<?php

require("includes/session.php");
/*require("includes/config.php");*/
require("includes/database.php");
require("includes/funciones.php");

$input9 = $_GET["input9"];
$input10 = $_GET["input10"];

$rep_tercero = $_GET["rep_tercero"];
$rep_cirujano = $_GET["rep_cirujano"];
$rep_anestesiologo = $_GET["rep_anestesiologo"];
$rep_especialidades = $_GET["rep_especialidades"];
$rep_realizadas = $_GET["rep_realizadas"];
$rep_canceladas = $_GET["rep_canceladas"];
$rep_anestesia = $_GET["rep_anestesia"];
$rep_ambito = $_GET["rep_ambito"];
$rep_urg_realizadas = $_GET["rep_urg_realizadas"];
$rep_procedimientos = $_GET["rep_procedimientos"];




open_database();



?>
<html>
<head>
<title>Reportes de Cirugia</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="Estilos.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>
<script language="JavaScript" src="js/overlib_mini.js"></script>
<script language="JavaScript" src="js/calendar.js"></script>
<script language="JavaScript" src="calendar3.js"></script>
<script LANGUAGE="JavaScript">
<?php

if ($rep_tercero == 1){

 $result_tercero=execute_query($dbh, "select distinct(terceros.tercero_id), terceros.nombre_tercero, count(*)
 FROM   ((public.cuentas_liquidaciones_qx cuentas_liquidaciones_qx INNER JOIN public.cuentas cuentas ON cuentas_liquidaciones_qx.numerodecuenta=cuentas.numerodecuenta) INNER JOIN public.planes planes ON cuentas.plan_id=planes.plan_id) INNER JOIN public.terceros terceros ON (planes.tipo_tercero_id=terceros.tipo_id_tercero) AND (planes.tercero_id=terceros.tercero_id)
 WHERE  (cuentas_liquidaciones_qx.fecha_cirugia>='$input9' AND cuentas_liquidaciones_qx.fecha_cirugia<='$input10') AND cuentas_liquidaciones_qx.departamento='020301' AND cuentas_liquidaciones_qx.estado<>'3' AND  NOT (cuentas.estado='4' OR cuentas.estado='5')
 group by 1,2
ORDER BY 3 Desc"); 


?>

<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<br>
<table width="100%" border="1" cellspacing="0" align="center">
<tr> 
      <td  background="WHITE" colspan="3" bgcolor="WHITE"><div align="center"><strong><font color="BLACK" size="2">Reporte Total Terceros <?php echo $_REQUEST["input9"];?> hasta <?php echo $_REQUEST["input10"];?></font></strong></div></td>
    </tr>
  <tr> 
  <td width="30%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">N IDENTIFICACION</font></strong></a></td>
  <td width="60%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">NOMBRE</font></strong></a></td>
  <td width="10%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">CANTIDAD</font></strong></a></td>
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

<tr bgcolor="WHITE" onMouseOver="javascript: this.style.backgroundColor = '#CC9900';" onMouseOut="javascript: this.style.backgroundColor = '#EEEEEE';"> 
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_tercero[0]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_tercero[1]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_tercero[2]?></font></td>
  </tr>
  <?php
  $suma_terceros = $suma_terceros + $fetch_tercero[2];
}
?>
<tr> 
      <td  background="WHITE" colspan="2" bgcolor="WHITE"><div align="right"><strong><font color="BLACK" size="2">TOTAL:</font></strong></div></td>
      <td  background="WHITE" colspan="1" bgcolor="WHITE"><div align="center"><strong><font color="BLACK" size="2"><?php echo $suma_terceros;?></font></strong></div></td>
    </tr>
    <?php
echo "</table>";
}

if ($rep_cirujano == 1){

 $result_cirujano=execute_query($dbh, "select distinct(cuentas_liquidaciones_qx_procedimientos.cirujano_id), profesionales.nombre, count(*)
FROM   (public.profesionales_especialidades profesionales_especialidades INNER JOIN ((((((public.cuentas_liquidaciones_qx_procedimientos cuentas_liquidaciones_qx_procedimientos INNER JOIN public.cuentas_liquidaciones_qx cuentas_liquidaciones_qx ON cuentas_liquidaciones_qx_procedimientos.cuenta_liquidacion_qx_id=cuentas_liquidaciones_qx.cuenta_liquidacion_qx_id) INNER JOIN public.profesionales profesionales ON (cuentas_liquidaciones_qx_procedimientos.tipo_id_cirujano=profesionales.tipo_id_tercero) AND (cuentas_liquidaciones_qx_procedimientos.cirujano_id=profesionales.tercero_id)) INNER JOIN public.cups cups ON cuentas_liquidaciones_qx_procedimientos.cargo_cups=cups.cargo) INNER JOIN public.ingresos ingresos ON cuentas_liquidaciones_qx.ingreso=ingresos.ingreso) INNER JOIN public.cuentas cuentas ON (cuentas_liquidaciones_qx.numerodecuenta=cuentas.numerodecuenta) AND (ingresos.ingreso=cuentas.ingreso)) INNER JOIN public.pacientes pacientes ON (ingresos.paciente_id=pacientes.paciente_id) AND (ingresos.tipo_id_paciente=pacientes.tipo_id_paciente)) ON (profesionales_especialidades.tipo_id_tercero=profesionales.tipo_id_tercero) AND (profesionales_especialidades.tercero_id=profesionales.tercero_id)) INNER JOIN public.especialidades especialidades ON profesionales_especialidades.especialidad=especialidades.especialidad
 WHERE  cuentas_liquidaciones_qx_procedimientos.consecutivo_procedimiento = (select Max(b.consecutivo_procedimiento) from cuentas_liquidaciones_qx_procedimientos b where cuentas_liquidaciones_qx_procedimientos.cuenta_liquidacion_qx_id = b.cuenta_liquidacion_qx_id) AND (cuentas_liquidaciones_qx.fecha_cirugia>='$input9' AND cuentas_liquidaciones_qx.fecha_cirugia<='$input10') AND cuentas_liquidaciones_qx.departamento='020301' AND cuentas_liquidaciones_qx.estado<>'3' AND  NOT (cuentas.estado='4' OR cuentas.estado='5') 
 group by 1,2
order by 3 Desc"); 


?>
<br>
<table width="100%" border="1" cellspacing="0" align="center">
<tr> 
      <td  background="WHITE" colspan="3" bgcolor="WHITE"><div align="center"><strong><font color="BLACK" size="2">Reporte Total Cirujanos <?php echo $_REQUEST["input9"];?> hasta <?php echo $_REQUEST["input10"];?></font></strong></div></td>
    </tr>
  <tr> 
  <td width="30%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">N IDENTIFICACION</font></strong></a></td>
  <td width="60%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">NOMBRE</font></strong></a></td>
  <td width="10%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">CANTIDAD</font></strong></a></td>
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

<tr bgcolor="WHITE" onMouseOver="javascript: this.style.backgroundColor = '#CC9900';" onMouseOut="javascript: this.style.backgroundColor = '#EEEEEE';"> 
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_cirujano[0]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_cirujano[1]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_cirujano[2]?></font></td>
    </tr>
  <?php
  $suma_cirujanos = $suma_cirujanos + $fetch_cirujano[2];
}
?>
<tr> 
      <td  background="WHITE" colspan="2" bgcolor="WHITE"><div align="right"><strong><font color="BLACK" size="2">TOTAL:</font></strong></div></td>
      <td  background="WHITE" colspan="1" bgcolor="WHITE"><div align="center"><strong><font color="BLACK" size="2"><?php echo $suma_cirujanos;?></font></strong></div></td>
    </tr>
    <?php
echo "</table>";
}

if ($rep_anestesiologo == 1){

 $result_anestesiologo=execute_query($dbh, "select distinct(cuentas_liquidaciones_qx.anestesiologo_id), profesionales.nombre, count(*)
 FROM   (public.cuentas_liquidaciones_qx cuentas_liquidaciones_qx INNER JOIN public.cuentas cuentas ON cuentas_liquidaciones_qx.numerodecuenta=cuentas.numerodecuenta) INNER JOIN public.profesionales profesionales ON (cuentas_liquidaciones_qx.tipo_id_anestesiologo=profesionales.tipo_id_tercero) AND (cuentas_liquidaciones_qx.anestesiologo_id=profesionales.tercero_id)
 WHERE   NOT (cuentas.estado='4' OR cuentas.estado='5') AND cuentas_liquidaciones_qx.estado<>'3' AND cuentas_liquidaciones_qx.departamento='020301' AND (cuentas_liquidaciones_qx.fecha_cirugia>='$input9' AND cuentas_liquidaciones_qx.fecha_cirugia<='$input10')
 group by 1,2
ORDER BY 3 Desc"); 


?>
<br>
<table width="100%" border="1" cellspacing="0" align="center">
<tr> 
      <td  background="WHITE" colspan="3" bgcolor="WHITE"><div align="center"><strong><font color="BLACK" size="2">Reporte Total Anestesiologos <?php echo $_REQUEST["input9"];?> hasta <?php echo $_REQUEST["input10"];?></font></strong></div></td>
    </tr>
  <tr> 
  <td width="30%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">N IDENTIFICACION</font></strong></a></td>
  <td width="60%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">NOMBRE</font></strong></a></td>
  <td width="10%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">CANTIDAD</font></strong></a></td>
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

<tr bgcolor="WHITE" onMouseOver="javascript: this.style.backgroundColor = '#CC9900';" onMouseOut="javascript: this.style.backgroundColor = '#EEEEEE';"> 
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_anestesiologo[0]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_anestesiologo[1]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_anestesiologo[2]?></font></td>
  </tr>
  <?php
  $suma_anestesiologos = $suma_anestesiologos + $fetch_anestesiologo[2];
}
?>
<tr> 
      <td  background="WHITE" colspan="2" bgcolor="WHITE"><div align="right"><strong><font color="BLACK" size="2">TOTAL:</font></strong></div></td>
      <td  background="WHITE" colspan="1" bgcolor="WHITE"><div align="center"><strong><font color="BLACK" size="2"><?php echo $suma_anestesiologos;?></font></strong></div></td>
    </tr>
    <?php
echo "</table>";
}

if ($rep_especialidades == 1){

 $result_especialidades=execute_query($dbh, "select distinct(profesionales_especialidades.especialidad), especialidades.descripcion, count(*)
FROM   (public.profesionales_especialidades profesionales_especialidades INNER JOIN ((((((public.cuentas_liquidaciones_qx_procedimientos cuentas_liquidaciones_qx_procedimientos INNER JOIN public.cuentas_liquidaciones_qx cuentas_liquidaciones_qx ON cuentas_liquidaciones_qx_procedimientos.cuenta_liquidacion_qx_id=cuentas_liquidaciones_qx.cuenta_liquidacion_qx_id) INNER JOIN public.profesionales profesionales ON (cuentas_liquidaciones_qx_procedimientos.tipo_id_cirujano=profesionales.tipo_id_tercero) AND (cuentas_liquidaciones_qx_procedimientos.cirujano_id=profesionales.tercero_id)) INNER JOIN public.cups cups ON cuentas_liquidaciones_qx_procedimientos.cargo_cups=cups.cargo) INNER JOIN public.ingresos ingresos ON cuentas_liquidaciones_qx.ingreso=ingresos.ingreso) INNER JOIN public.cuentas cuentas ON (cuentas_liquidaciones_qx.numerodecuenta=cuentas.numerodecuenta) AND (ingresos.ingreso=cuentas.ingreso)) INNER JOIN public.pacientes pacientes ON (ingresos.paciente_id=pacientes.paciente_id) AND (ingresos.tipo_id_paciente=pacientes.tipo_id_paciente)) ON (profesionales_especialidades.tipo_id_tercero=profesionales.tipo_id_tercero) AND (profesionales_especialidades.tercero_id=profesionales.tercero_id)) INNER JOIN public.especialidades especialidades ON profesionales_especialidades.especialidad=especialidades.especialidad
 WHERE  cuentas_liquidaciones_qx_procedimientos.consecutivo_procedimiento = (select Max(b.consecutivo_procedimiento) from cuentas_liquidaciones_qx_procedimientos b where cuentas_liquidaciones_qx_procedimientos.cuenta_liquidacion_qx_id = b.cuenta_liquidacion_qx_id) AND (cuentas_liquidaciones_qx.fecha_cirugia>='$input9' AND cuentas_liquidaciones_qx.fecha_cirugia<='$input10') AND cuentas_liquidaciones_qx.departamento='020301' AND cuentas_liquidaciones_qx.estado<>'3' AND  NOT (cuentas.estado='4' OR cuentas.estado='5') 
 group by 1,2
order by 3 Desc"); 


?>
<br>
<table width="100%" border="1" cellspacing="0" align="center">
<tr> 
      <td  background="WHITE" colspan="3" bgcolor="WHITE"><div align="center"><strong><font color="BLACK" size="2">Reporte Total Especialidades <?php echo $_REQUEST["input9"];?> hasta <?php echo $_REQUEST["input10"];?></font></strong></div></td>
    </tr>
  <tr> 
  <td width="30%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">COD ESPECIALIDAD</font></strong></a></td>
  <td width="60%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">ESPECIALIDAD</font></strong></a></td>
  <td width="10%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">CANTIDAD</font></strong></a></td>
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

<tr bgcolor="WHITE" onMouseOver="javascript: this.style.backgroundColor = '#CC9900';" onMouseOut="javascript: this.style.backgroundColor = '#EEEEEE';"> 
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_especialidades[0]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_especialidades[1]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_especialidades[2]?></a></font></td>
  </tr>
  <?php
  $suma_especialidades = $suma_especialidades + $fetch_especialidades[2];
}
?>
<tr> 
      <td  background="WHITE" colspan="2" bgcolor="WHITE"><div align="right"><strong><font color="BLACK" size="2">TOTAL:</font></strong></div></td>
      <td  background="WHITE" colspan="1" bgcolor="WHITE"><div align="center"><strong><font color="BLACK" size="2"><?php echo $suma_especialidades;?></font></strong></div></td>
    </tr>
    <?php
echo "</table>";
}


if ($rep_realizadas == 1){

 $result_realizadas=execute_query($dbh, "select distinct(quirofano_id), count(*)
from qx_quirofanos_programacion
where (hora_inicio >= '$input9' and hora_inicio <='$input10')
group by 1"); 


?>
<br>
<table width="100%" border="1" cellspacing="0" align="center">
<tr> 
      <td  background="WHITE" colspan="2" bgcolor="WHITE"><div align="center"><strong><font color="BLACK" size="2">Programaciones Realizadas <?php echo $_REQUEST["input9"];?> hasta <?php echo $_REQUEST["input10"];?></font></strong></div></td>
    </tr>
  <tr> 
  <td width="30%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">SALA</font></strong></a></td>
  <td width="10%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">CANTIDAD</font></strong></a></td>
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

<tr bgcolor="WHITE" onMouseOver="javascript: this.style.backgroundColor = '#CC9900';" onMouseOut="javascript: this.style.backgroundColor = '#EEEEEE';"> 
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_realizadas[0]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_realizadas[1]?></a></font></td>
  </tr>
  <?php
  $suma_realizadas = $suma_realizadas + $fetch_realizadas[1];
}
?>
<tr> 
      <td  background="WHITE" colspan="1" bgcolor="WHITE"><div align="right"><strong><font color="BLACK" size="2">TOTAL:</font></strong></div></td>
      <td  background="WHITE" colspan="1" bgcolor="WHITE"><div align="center"><strong><font color="BLACK" size="2"><?php echo $suma_realizadas;?></font></strong></div></td>
    </tr>
    <?php
echo "</table>";
}

if ($rep_canceladas == 1){

 $result_canceladas=execute_query($dbh, "select distinct(a.qx_motivo_cancelacion_programacion_id), b.descripcion, count(*)
from qx_programaciones_canceladas a, qx_motivos_cancelacion_programaciones b
where a.programacion_id in (select programacion_id from qx_quirofanos_programacion
                             where (hora_inicio >= '$input9' and hora_inicio <='$input10'))
and a.qx_motivo_cancelacion_programacion_id = b.qx_motivo_cancelacion_programacion_id 
group by 1, 2
ORDER BY 1"); 


?>
<br>
<table width="100%" border="1" cellspacing="0" align="center">
<tr> 
      <td  background="WHITE" colspan="3" bgcolor="WHITE"><div align="center"><strong><font color="BLACK" size="2">Programaciones Canceladas <?php echo $_REQUEST["input9"];?> hasta <?php echo $_REQUEST["input10"];?></font></strong></div></td>
    </tr>
  <tr> 
  <td width="30%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">COD CANCELACION</font></strong></a></td>
  <td width="60%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">MOTIVO CANCELACION</font></strong></a></td>
  <td width="10%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">CANTIDAD</font></strong></a></td>
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

<tr bgcolor="WHITE" onMouseOver="javascript: this.style.backgroundColor = '#CC9900';" onMouseOut="javascript: this.style.backgroundColor = '#EEEEEE';"> 
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_canceladas[0]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_canceladas[1]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_canceladas[2]?></a></font></td>
  </tr>
  <?php
  $suma_canceladas = $suma_canceladas + $fetch_canceladas[2];
}
?>
<tr> 
      <td  background="WHITE" colspan="2" bgcolor="WHITE"><div align="right"><strong><font color="BLACK" size="2">TOTAL:</font></strong></div></td>
      <td  background="WHITE" colspan="1" bgcolor="WHITE"><div align="center"><strong><font color="BLACK" size="2"><?php echo $suma_canceladas;?></font></strong></div></td>
    </tr>
    <?php
echo "</table>";
}

if ($rep_anestesia == 1){
 
 	$modif = execute_query($dbh, "update cuentas_liquidaciones_qx
set qx_tipo_anestesia_id = '0'
where qx_tipo_anestesia_id is NULL");



 $result_anestesia=execute_query($dbh, "select distinct(a.qx_tipo_anestesia_id), b.descripcion, count(*)
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


?>
<br>
<table width="100%" border="1" cellspacing="0" align="center">
<tr> 
      <td  background="WHITE" colspan="3" bgcolor="WHITE"><div align="center"><strong><font color="BLACK" size="2">Liq x Tipo de Anestesia</font> <?php echo $_REQUEST["input9"];?> hasta <?php echo $_REQUEST["input10"];?></strong></div></td>
    </tr>
  <tr> 
  <td width="30%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">COD ANESTESIA</font></strong></a></td>
  <td width="60%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">TIPO ANESTESIA</font></strong></a></td>
  <td width="10%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">CANTIDAD</font></strong></a></td>
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

<tr bgcolor="WHITE" onMouseOver="javascript: this.style.backgroundColor = '#CC9900';" onMouseOut="javascript: this.style.backgroundColor = '#EEEEEE';"> 
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_anestesia[0]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_anestesia[1]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_anestesia[2]?></a></font></td>
  </tr>
  <?php
  $suma_anestesia = $suma_anestesia + $fetch_anestesia[2];
}
?>
<tr> 
      <td  background="WHITE" colspan="2" bgcolor="WHITE"><div align="right"><strong><font color="BLACK" size="2">TOTAL:</font></strong></div></td>
      <td  background="WHITE" colspan="1" bgcolor="WHITE"><div align="center"><strong><font color="BLACK" size="2"><?php echo $suma_anestesia;?></font></strong></div></td>
    </tr>
    <?php
echo "</table>";
}


if ($rep_ambito == 1){
 
 
 $result_urg_realizadas=execute_query($dbh, "select  a.cuenta_liquidacion_qx_id, a.ingreso, a.numerodecuenta, p.tipo_id_paciente, p.paciente_id, p.primer_nombre, p.segundo_nombre, p.primer_apellido, p.segundo_apellido

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
 
 $result_ambito=execute_query($dbh, "select distinct(a.ambito_cirugia_id), b.descripcion, count(*)
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


?>
<br>
<table width="100%" border="1" cellspacing="0" align="center">
<tr> 
      <td  background="WHITE" colspan="3" bgcolor="WHITE"><div align="center"><strong><font color="BLACK" size="2">Ambito de Cirugia <?php echo $_REQUEST["input9"];?> hasta <?php echo $_REQUEST["input10"];?></font></strong></div></td>
    </tr>
  <tr> 
  <td width="30%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">COD AMBITO</font></strong></a></td>
  <td width="60%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">DESCRIPCION</font></strong></a></td>
  <td width="10%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">CANTIDAD</font></strong></a></td>
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

<tr bgcolor="WHITE" onMouseOver="javascript: this.style.backgroundColor = '#CC9900';" onMouseOut="javascript: this.style.backgroundColor = '#EEEEEE';"> 
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_ambito[0]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_ambito[1]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_ambito[2]?></a></font></td>
  </tr>
  <?php
  $suma_ambito = $suma_ambito + $fetch_ambito[2];
}
?>
<tr> 
      <td  background="WHITE" colspan="2" bgcolor="WHITE"><div align="right"><strong><font color="BLACK" size="2">TOTAL:</font></strong></div></td>
      <td  background="WHITE" colspan="1" bgcolor="WHITE"><div align="center"><strong><font color="BLACK" size="2"><?php echo $suma_ambito;?></font></strong></div></td>
    </tr>
    <?php
echo "</table>";
}

if ($rep_urg_realizadas == 1){

 $result_urg_realizadas=execute_query($dbh, "select  a.cuenta_liquidacion_qx_id, a.ingreso, a.numerodecuenta, p.tipo_id_paciente, p.paciente_id, p.primer_nombre, p.segundo_nombre, p.primer_apellido, p.segundo_apellido

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


?>
<br>
<table width="100%" border="1" cellspacing="0" align="center">
<tr> 
      <td  background="WHITE" colspan="5" bgcolor="WHITE"><div align="center"><strong><font color="BLACK" size="2">Cirugias Urgentes Realizadas <?php echo $_REQUEST["input9"];?> hasta <?php echo $_REQUEST["input10"];?></font></strong></div></td>
    </tr>
  <tr> 
  <td width="5%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">Liq_Qx</font></strong></a></td>
  <td width="10%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">Ingreso</font></strong></a></td>
  <td width="10%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">Cuenta</font></strong></a></td>
  <td width="35%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">Identifacion</font></strong></a></td>
  <td width="40%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">Nombre Paciente</font></strong></a></td>
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

<tr bgcolor="WHITE" onMouseOver="javascript: this.style.backgroundColor = '#CC9900';" onMouseOut="javascript: this.style.backgroundColor = '#EEEEEE';"> 
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
      <td  background="WHITE" colspan="4" bgcolor="WHITE"><div align="right"><strong><font color="BLACK" size="2">TOTAL:</font></strong></div></td>
      <td  background="WHITE" colspan="1" bgcolor="WHITE"><div align="center"><strong><font color="BLACK" size="2"><?php echo $suma_urg_realizadas;?></font></strong></div></td>
    </tr>
    <?php
echo "</table>";
}

if ($rep_procedimientos == 1){
 
 $rep_procedimientos = 1;

 $result_procedimientos=execute_query($dbh, "select distinct(a.cargo_cups), b.descripcion, count(*)
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


?>
<br>
<table width="100%" border="1" cellspacing="0" align="center">
<tr> 
      <td  background="WHITE" colspan="3" bgcolor="WHITE"><div align="center"><strong><font color="BLACK" size="2">Procedimientos Realizados <?php echo $_REQUEST["input9"];?> hasta <?php echo $_REQUEST["input10"];?></font></strong></div></td>
    </tr>
  <tr> 
  <td width="30%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">COD CARGO</font></strong></a></td>
  <td width="60%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">NOMBRE</font></strong></a></td>
  <td width="10%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">CANTIDAD</font></strong></a></td>
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
      <td  background="WHITE" colspan="2" bgcolor="WHITE"><div align="right"><strong><font color="#003366" size="2">TOTAL:</font></strong></div></td>
      <td  background="WHITE" colspan="1" bgcolor="WHITE"><div align="center"><strong><font color="#003366" size="2"><?php echo $suma_procedimientos;?></font></strong></div></td>
    </tr>
    <?php
echo "</table>";
}
?>