<?php
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=archivo.xls");
header("Pragma: no-cache");
header("Expires: 0");

$VISTA='HTML';
include 'includes/enviroment.inc.php';
include('conexion.php'); 
    if (!$dbconn) {
    echo "No hay Conexion.\n";
    exit;

}

$result_grupo=pg_exec($dbconn, "SELECT b.numerodecuenta
 FROM   ingresos a, cuentas b
 WHERE  a.fecha_ingreso>='$input9' AND a.fecha_ingreso<='$input10' AND a.ingreso = b.ingreso
 GROUP BY b.numerodecuenta 
 ORDER BY b.numerodecuenta"); 

if (!$result_grupo) {
    echo "Error en Consulta1.\n";
    exit;
}

echo "<table border=1>\n";
echo "<tr>\n";
echo "<td  background='WHITE' colspan='11' bgcolor='#EEEEEE'><div align='center'><strong><font color='BLACK' size='2'>Pacientes Ingresados entre $input9 y $input10</font></strong></div></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<th>Estacion Enf</th>\n";
echo "<th>Cama</th>\n";
echo "<th>Paciente</th>\n";
echo "<th>Cuenta</th>\n";
echo "<th>Ingreso</th>\n";
echo "<th>Plan</th>\n";
echo "<th>Estado</th>\n";
echo "<th>Tot Cuenta</th>\n";
echo "<th>Fecha Ingreso</th>\n";
echo "<th>Fecha Egreso</th>\n";
echo "<th>Dias</th>\n";
echo "</tr>\n";

while($fetch_grupo = pg_fetch_row($result_grupo)) {
 
 	if($plan_id == 'TODOS'){
$result_max=pg_exec($dbconn, " SELECT estaciones_enfermeria.descripcion, movimientos_habitacion.cama, 					estaciones_enfermeria.estacion_id, movimientos_habitacion.fecha_ingreso, pacientes.tipo_id_paciente, pacientes.paciente_id, pacientes.primer_nombre, pacientes.segundo_nombre, pacientes.primer_apellido, pacientes.segundo_apellido, cuentas.numerodecuenta, ingresos.ingreso, planes.plan_descripcion, movimientos_habitacion.fecha_egreso, cuentas.total_cuenta, ingresos.fecha_ingreso
 FROM    ((((public.movimientos_habitacion movimientos_habitacion INNER JOIN public.cuentas cuentas ON movimientos_habitacion.numerodecuenta=cuentas.numerodecuenta) INNER JOIN public.estaciones_enfermeria estaciones_enfermeria ON movimientos_habitacion.estacion_id=estaciones_enfermeria.estacion_id) INNER JOIN public.ingresos ingresos ON (movimientos_habitacion.ingreso=ingresos.ingreso) AND (cuentas.ingreso=ingresos.ingreso)) INNER JOIN public.planes planes ON cuentas.plan_id=planes.plan_id) INNER JOIN public.pacientes pacientes ON (ingresos.paciente_id=pacientes.paciente_id) AND (ingresos.tipo_id_paciente=pacientes.tipo_id_paciente)
 WHERE  
 movimiento_id =(Select max(movimiento_id) from movimientos_habitacion WHERE numerodecuenta = '$fetch_grupo[0]')");
	} 
ELSE{
$result_max=pg_exec($dbconn, " SELECT estaciones_enfermeria.descripcion, movimientos_habitacion.cama, estaciones_enfermeria.estacion_id, movimientos_habitacion.fecha_ingreso, pacientes.tipo_id_paciente, pacientes.paciente_id, pacientes.primer_nombre, pacientes.segundo_nombre, pacientes.primer_apellido, pacientes.segundo_apellido, cuentas.numerodecuenta, ingresos.ingreso, planes.plan_descripcion, movimientos_habitacion.fecha_egreso, cuentas.total_cuenta, ingresos.fecha_ingreso
 FROM    ((((public.movimientos_habitacion movimientos_habitacion INNER JOIN public.cuentas cuentas ON movimientos_habitacion.numerodecuenta=cuentas.numerodecuenta) INNER JOIN public.estaciones_enfermeria estaciones_enfermeria ON movimientos_habitacion.estacion_id=estaciones_enfermeria.estacion_id) INNER JOIN public.ingresos ingresos ON (movimientos_habitacion.ingreso=ingresos.ingreso) AND (cuentas.ingreso=ingresos.ingreso)) INNER JOIN public.planes planes ON cuentas.plan_id=planes.plan_id) INNER JOIN public.pacientes pacientes ON (ingresos.paciente_id=pacientes.paciente_id) AND (ingresos.tipo_id_paciente=pacientes.tipo_id_paciente)
 WHERE  
 movimiento_id =(Select max(movimiento_id) from movimientos_habitacion WHERE numerodecuenta = '$fetch_grupo[0]')
 AND planes.plan_id = $plan_id"); 

}
 
while($fetch_max = pg_fetch_row($result_max)) {
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

if ($dias_diferencia == 0){
 $color_body = "#FFFF99";
 }
else {
 $color_body = "#FFCC99";
}
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
$color_body = "#D8FFCC";
}

echo "<tr>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_max[0]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_max[1]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_max[4]"." "."$fetch_max[5]"." "."$fetch_max[6]"." "."$fetch_max[7]"." "."$fetch_max[8]"." "."$fetch_max[9]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_max[10]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_max[11]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_max[12]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$estado</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_max[14]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_max[15]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_max[13]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$dias_diferencia</div></td>\n";
echo "</tr>\n";
} 
}
echo "<tr>\n";
echo "<td  background='WHITE' colspan='11' bgcolor='#EEEEEE'><div align='center'><strong><font color='BLACK' size='2'>Pacientes Hospitalizados</font></strong></div></td>\n";
echo "</tr>\n";

if($plan_id == 'TODOS'){
$result_max1=pg_exec($dbconn, " SELECT estaciones_enfermeria.descripcion, movimientos_habitacion.cama, 					estaciones_enfermeria.estacion_id, movimientos_habitacion.fecha_ingreso, pacientes.tipo_id_paciente, pacientes.paciente_id, pacientes.primer_nombre, pacientes.segundo_nombre, pacientes.primer_apellido, pacientes.segundo_apellido, cuentas.numerodecuenta, ingresos.ingreso, planes.plan_descripcion, movimientos_habitacion.fecha_egreso, cuentas.total_cuenta, ingresos.fecha_ingreso
 FROM    ((((public.movimientos_habitacion movimientos_habitacion INNER JOIN public.cuentas cuentas ON movimientos_habitacion.numerodecuenta=cuentas.numerodecuenta) INNER JOIN public.estaciones_enfermeria estaciones_enfermeria ON movimientos_habitacion.estacion_id=estaciones_enfermeria.estacion_id) INNER JOIN public.ingresos ingresos ON (movimientos_habitacion.ingreso=ingresos.ingreso) AND (cuentas.ingreso=ingresos.ingreso)) INNER JOIN public.planes planes ON cuentas.plan_id=planes.plan_id) INNER JOIN public.pacientes pacientes ON (ingresos.paciente_id=pacientes.paciente_id) AND (ingresos.tipo_id_paciente=pacientes.tipo_id_paciente)
 WHERE  movimientos_habitacion.fecha_egreso IS NULL AND movimientos_habitacion.fecha_ingreso < '$input9'");
	} 
ELSE{
$result_max1=pg_exec($dbconn, " SELECT estaciones_enfermeria.descripcion, movimientos_habitacion.cama, estaciones_enfermeria.estacion_id, movimientos_habitacion.fecha_ingreso, pacientes.tipo_id_paciente, pacientes.paciente_id, pacientes.primer_nombre, pacientes.segundo_nombre, pacientes.primer_apellido, pacientes.segundo_apellido, cuentas.numerodecuenta, ingresos.ingreso, planes.plan_descripcion, movimientos_habitacion.fecha_egreso, cuentas.total_cuenta, ingresos.fecha_ingreso
 FROM    ((((public.movimientos_habitacion movimientos_habitacion INNER JOIN public.cuentas cuentas ON movimientos_habitacion.numerodecuenta=cuentas.numerodecuenta) INNER JOIN public.estaciones_enfermeria estaciones_enfermeria ON movimientos_habitacion.estacion_id=estaciones_enfermeria.estacion_id) INNER JOIN public.ingresos ingresos ON (movimientos_habitacion.ingreso=ingresos.ingreso) AND (cuentas.ingreso=ingresos.ingreso)) INNER JOIN public.planes planes ON cuentas.plan_id=planes.plan_id) INNER JOIN public.pacientes pacientes ON (ingresos.paciente_id=pacientes.paciente_id) AND (ingresos.tipo_id_paciente=pacientes.tipo_id_paciente)
 WHERE  movimientos_habitacion.fecha_egreso IS NULL AND movimientos_habitacion.fecha_ingreso < '$input9' AND planes.plan_id = $plan_id"); 

}
while($fetch_max1 = pg_fetch_row($result_max1)) { 
IF (isset($fetch_max1[13])){
	$estado = 'Paciente Egresado';
	$color_body = "white";
	
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
	$color_body = "white";
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
echo "<tr>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_max1[0]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_max1[1]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_max1[4]"." "."$fetch_max1[5]"." "."$fetch_max1[6]"." "."$fetch_max[7]"." "."$fetch_max1[8]"." "."$fetch_max1[9]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_max1[10]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_max1[11]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_max1[12]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$estado</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_max1[14]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_max1[15]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_max1[13]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$dias_diferencia</div></td>\n";
echo "</tr>\n";
}
echo "</table>\n";
?>