<?php

require("includes/session.php");
/*require("includes/config.php");*/
require("includes/database.php");
require("includes/funciones.php");

$input9 = $_REQUEST["input9"];
$input10 = $_REQUEST["input10"];
$departamento = $_REQUEST["departamento"];

open_database();

header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=archivo.xls");
header("Pragma: no-cache");
header("Expires: 0");



if ($departamento == 1){
	
	
	
	$result_grupo=execute_query($dbh, "SELECT ingresos.ingreso, cuentas.numerodecuenta, ingresos.fecha_ingreso, pacientes.tipo_id_paciente, pacientes.paciente_id, pacientes.primer_apellido, pacientes.segundo_apellido, pacientes.primer_nombre, pacientes.segundo_nombre, planes.plan_descripcion, departamentos.descripcion, hc_diagnosticos_egreso.sw_principal, servicios.servicio, hc_vistosok_salida_detalle.fecha_registro, diagnosticos.diagnostico_nombre, diagnosticos.diagnostico_id
 FROM   (((((public.cuentas cuentas INNER JOIN (((public.hc_diagnosticos_egreso hc_diagnosticos_egreso INNER JOIN public.diagnosticos diagnosticos ON hc_diagnosticos_egreso.tipo_diagnostico_id=diagnosticos.diagnostico_id) INNER JOIN public.hc_evoluciones hc_evoluciones ON hc_diagnosticos_egreso.evolucion_id=hc_evoluciones.evolucion_id) INNER JOIN public.ingresos ingresos ON hc_evoluciones.ingreso=ingresos.ingreso) ON cuentas.ingreso=ingresos.ingreso) INNER JOIN public.planes planes ON cuentas.plan_id=planes.plan_id) INNER JOIN public.departamentos departamentos ON ingresos.departamento_actual=departamentos.departamento) INNER JOIN public.pacientes pacientes ON (ingresos.paciente_id=pacientes.paciente_id) AND (ingresos.tipo_id_paciente=pacientes.tipo_id_paciente)) INNER JOIN public.hc_vistosok_salida_detalle hc_vistosok_salida_detalle ON ingresos.ingreso=hc_vistosok_salida_detalle.ingreso) INNER JOIN public.servicios servicios ON departamentos.servicio=servicios.servicio
 WHERE  (ingresos.fecha_ingreso>='$input9' AND ingresos.fecha_ingreso<'$input10') AND hc_diagnosticos_egreso.sw_principal='1' AND servicios.servicio='1' ORDER BY pacientes.paciente_id, ingresos.ingreso"); 

		

echo "<table border=1>\n";
echo "<tr>\n";
echo "<td  background='WHITE' colspan='7' bgcolor='#EEEEEE'><div align='center'><strong><font color='BLACK' size='2'>Pacientes Re-Ingresados entre $input9 y $input10</font></strong></div></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<th>Ingreso</th>\n";
echo "<th>Cuenta</th>\n";
echo "<th>Fecha Re-Ingreso</th>\n";
echo "<th>Ingreso Anterior</th>\n";
echo "<th>Paciente</th>\n";
echo "<th>Plan</th>\n";
echo "<th>Diagnostico Egreso</th>\n";
echo "<th>Egreso Anterior</th>\n";
echo "</tr>\n";

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
 	$color_body = "WHITE";

echo "<tr>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_grupo[0]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_grupo[1]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_grupo[2]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_max[3]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_grupo[3]"." "."$fetch_grupo[4]"." "."$fetch_grupo[7]"." "."$fetch_grupo[8]"." "."$fetch_grupo[5]"." "."$fetch_grupo[6]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_grupo[9]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_grupo[14]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_max[6]</div></td>\n";
echo "</tr>\n";
}
}
echo "</table>\n";
}



//PACIENTES CON RE-INGRESO EN URGENCIAS
    
else {
	
	$numero_dias = (60*60*24*3);
	
	$result_grupo1=execute_query($dbh, "  SELECT ingresos.ingreso, cuentas.numerodecuenta, ingresos.fecha_ingreso, pacientes.tipo_id_paciente, pacientes.paciente_id, pacientes.primer_apellido, pacientes.segundo_apellido, pacientes.primer_nombre, pacientes.segundo_nombre, planes.plan_descripcion, departamentos.descripcion, hc_diagnosticos_ingreso.sw_principal, servicios.servicio, hc_vistosok_salida_detalle.fecha_registro, diagnosticos.diagnostico_nombre, diagnosticos.diagnostico_id
 FROM   (((((public.cuentas cuentas INNER JOIN (((public.hc_diagnosticos_ingreso hc_diagnosticos_ingreso INNER JOIN public.diagnosticos diagnosticos ON hc_diagnosticos_ingreso.tipo_diagnostico_id=diagnosticos.diagnostico_id) INNER JOIN public.hc_evoluciones hc_evoluciones ON hc_diagnosticos_ingreso.evolucion_id=hc_evoluciones.evolucion_id) INNER JOIN public.ingresos ingresos ON hc_evoluciones.ingreso=ingresos.ingreso) ON cuentas.ingreso=ingresos.ingreso) INNER JOIN public.planes planes ON cuentas.plan_id=planes.plan_id) INNER JOIN public.departamentos departamentos ON ingresos.departamento_actual=departamentos.departamento) INNER JOIN public.pacientes pacientes ON (ingresos.paciente_id=pacientes.paciente_id) AND (ingresos.tipo_id_paciente=pacientes.tipo_id_paciente)) INNER JOIN public.hc_vistosok_salida_detalle hc_vistosok_salida_detalle ON ingresos.ingreso=hc_vistosok_salida_detalle.ingreso) INNER JOIN public.servicios servicios ON departamentos.servicio=servicios.servicio
 WHERE  (ingresos.fecha_ingreso>='$input9' AND ingresos.fecha_ingreso<'$input10') AND hc_diagnosticos_ingreso.sw_principal='1' AND servicios.servicio='4' ORDER BY pacientes.paciente_id, ingresos.ingreso"); 

		
		
		
echo "<table border=1>\n";
echo "<tr>\n";
echo "<td  background='WHITE' colspan='7' bgcolor='#EEEEEE'><div align='center'><strong><font color='BLACK' size='2'>Pacientes Re-Ingresados entre $input9 y $input10</font></strong></div></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<th>Ingreso</th>\n";
echo "<th>Cuenta</th>\n";
echo "<th>Fecha Re-Ingreso</th>\n";
echo "<th>Ingreso Anterior</th>\n";
echo "<th>Paciente</th>\n";
echo "<th>Plan</th>\n";
echo "<th>Diagnostico Ingreso</th>\n";
echo "<th>Ingreso Anterior</th>\n";
echo "</tr>\n";

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


	
	

while($fetch_max1 = pg_fetch_row($result_max1)) { 
 	if ($colorfila==0){
       $color= "#F0F0F0";
       $colorfila=1;
    }else{
       $color="white";
       $colorfila=0;
    }		
$color_body = "WHITE";

echo "<tr>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_grupo1[0]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_grupo1[1]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_grupo1[2]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_max1[3]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_grupo1[3]"." "."$fetch_grupo1[4]"." "."$fetch_grupo1[7]"." "."$fetch_grupo1[8]"." "."$fetch_grupo1[5]"." "."$fetch_grupo1[6]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_grupo1[9]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_grupo1[14]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_max1[6]</div></td>\n";
echo "</tr>\n";
}
}
}
echo "</table>\n";
?>