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

 $result_tercero=pg_exec($dbconn, "select distinct(t.tercero_id), t.nombre_tercero, count(*)
from cuentas_liquidaciones_qx a, terceros t, cuentas c, planes p
where 
a.numerodecuenta = c.numerodecuenta
and c.estado not in ('4','5')
and c.plan_id = p.plan_id
and (p.tipo_tercero_id = t.tipo_id_tercero and p.tercero_id = t.tercero_id)
and a.fecha_cirugia>= '$input9' AND a.fecha_cirugia<='$input10' AND 
a.estado<>'3'
and a.departamento = '020301'
group by 1,2
ORDER BY 3 Desc"); 

if (!$result_tercero) {
    echo "Error en Consulta.\n";
    exit;
}
?>

<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<br>
<table width="100%" border="1" cellspacing="0" align="center">
<tr> 
      <td  background="WHITE" colspan="3" bgcolor="WHITE"><div align="center"><strong><font color="BLACK" size="2">Reporte Total Terceros</font></strong></div></td>
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

 $result_cirujano=pg_exec($dbconn, "select distinct(cuentas_liquidaciones_qx_procedimientos.cirujano_id), profesionales.nombre, count(*)
from (cuentas_liquidaciones_qx_procedimientos left join  profesionales ON 
      cuentas_liquidaciones_qx_procedimientos.cirujano_id =  profesionales.tercero_id) 
  inner join cuentas_liquidaciones_qx ON ( cuentas_liquidaciones_qx_procedimientos.cuenta_liquidacion_qx_id =      
                                           cuentas_liquidaciones_qx.cuenta_liquidacion_qx_id)                                                   
  inner join departamentos on (cuentas_liquidaciones_qx.departamento = departamentos.departamento)
  inner join cuentas on (cuentas.numerodecuenta = cuentas_liquidaciones_qx.numerodecuenta)
and cuentas.estado not in ('4','5')
 where 
cuentas_liquidaciones_qx.fecha_cirugia>= '$input9' AND cuentas_liquidaciones_qx.fecha_cirugia<='$input10' AND 

cuentas_liquidaciones_qx.estado<>'3'
and cuentas_liquidaciones_qx.departamento = '020301'
group by 1,2
ORDER BY 3 Desc"); 

if (!$result_cirujano) {
    echo "Error en Consulta.\n";
    exit;
}
?>
<br>
<table width="100%" border="1" cellspacing="0" align="center">
<tr> 
      <td  background="WHITE" colspan="3" bgcolor="WHITE"><div align="center"><strong><font color="BLACK" size="2">Reporte Total Cirujanos</font></strong></div></td>
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

 $result_anestesiologo=pg_exec($dbconn, "select distinct(cuentas_liquidaciones_qx.anestesiologo_id), profesionales.nombre, count(*)
from (cuentas_liquidaciones_qx left join  profesionales on cuentas_liquidaciones_qx.anestesiologo_id = 
                                                            profesionales.tercero_id)
  inner join departamentos on (cuentas_liquidaciones_qx.departamento = departamentos.departamento)
inner join cuentas on (cuentas.numerodecuenta = cuentas_liquidaciones_qx.numerodecuenta)
and cuentas.estado not in ('4','5')
 where 
cuentas_liquidaciones_qx.fecha_cirugia>= '$input9' AND cuentas_liquidaciones_qx.fecha_cirugia<='$input10' AND 

cuentas_liquidaciones_qx.estado<>'3'
and cuentas_liquidaciones_qx.departamento = '020301'
group by 1,2
ORDER BY 3 Desc"); 

if (!$result_anestesiologo) {
    echo "Error en Consulta.\n";
    exit;
}
?>
<br>
<table width="100%" border="1" cellspacing="0" align="center">
<tr> 
      <td  background="WHITE" colspan="3" bgcolor="WHITE"><div align="center"><strong><font color="BLACK" size="2">Reporte Total Anestesiologos</font></strong></div></td>
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

 $result_especialidades=pg_exec($dbconn, "select distinct(profesionales_especialidades.especialidad), especialidades.descripcion, count(*)
from (cuentas_liquidaciones_qx_procedimientos left join  profesionales_especialidades on              
        cuentas_liquidaciones_qx_procedimientos.cirujano_id =  profesionales_especialidades.tercero_id)                                            
        INNER JOIN especialidades ON (profesionales_especialidades.especialidad = especialidades.especialidad)        
        INNER JOIN profesionales ON (profesionales_especialidades.tercero_id = profesionales.tercero_id)
        inner join cuentas_liquidaciones_qx ON (cuentas_liquidaciones_qx.cuenta_liquidacion_qx_id = cuentas_liquidaciones_qx_procedimientos.cuenta_liquidacion_qx_id )
inner join departamentos on (cuentas_liquidaciones_qx.departamento = departamentos.departamento)
inner join cuentas on (cuentas.numerodecuenta = cuentas_liquidaciones_qx.numerodecuenta)
and cuentas.estado not in ('4','5')
 where 
cuentas_liquidaciones_qx.fecha_cirugia>= '$input9' AND cuentas_liquidaciones_qx.fecha_cirugia<='$input10' AND 

cuentas_liquidaciones_qx.estado<>'3'
and cuentas_liquidaciones_qx.departamento = '020301'
group by 1,2
order by 3 Desc"); 

if (!$result_especialidades) {
    echo "Error en Consulta.\n";
    exit;
}
?>
<br>
<table width="100%" border="1" cellspacing="0" align="center">
<tr> 
      <td  background="WHITE" colspan="3" bgcolor="WHITE"><div align="center"><strong><font color="BLACK" size="2">Reporte Total Especialidades</font></strong></div></td>
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
<table width="100%" border="1" cellspacing="0" align="center">
<tr> 
      <td  background="WHITE" colspan="2" bgcolor="WHITE"><div align="center"><strong><font color="BLACK" size="2">Programaciones Realizadas</font></strong></div></td>
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
<table width="100%" border="1" cellspacing="0" align="center">
<tr> 
      <td  background="WHITE" colspan="3" bgcolor="WHITE"><div align="center"><strong><font color="BLACK" size="2">Programaciones Canceladas</font></strong></div></td>
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
<table width="100%" border="1" cellspacing="0" align="center">
<tr> 
      <td  background="WHITE" colspan="3" bgcolor="WHITE"><div align="center"><strong><font color="BLACK" size="2">Liq x Tipo de Anestesia</font></strong></div></td>
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
ORDER BY 1 "); 
while($fetch_urg_realizadas = pg_fetch_row($result_urg_realizadas)) {
 
 $suma_urg_realizadas++;
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
<table width="100%" border="1" cellspacing="0" align="center">
<tr> 
      <td  background="WHITE" colspan="3" bgcolor="WHITE"><div align="center"><strong><font color="BLACK" size="2">Ambito de Cirugia</font></strong></div></td>
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
ORDER BY 1 "); 

if (!$result_urg_realizadas) {
    echo "Error en Consulta.\n";
    exit;
}
?>
<br>
<table width="100%" border="1" cellspacing="0" align="center">
<tr> 
      <td  background="WHITE" colspan="3" bgcolor="WHITE"><div align="center"><strong><font color="BLACK" size="2">Cirugias Urgentes Realizadas</font></strong></div></td>
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
<table width="100%" border="1" cellspacing="0" align="center">
<tr> 
      <td  background="WHITE" colspan="3" bgcolor="WHITE"><div align="center"><strong><font color="BLACK" size="2">Procedimientos Realizados</font></strong></div></td>
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