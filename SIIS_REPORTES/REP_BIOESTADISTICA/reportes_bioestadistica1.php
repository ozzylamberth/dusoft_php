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

$result_grupo=pg_exec($dbconn, "SELECT ingresos.ingreso, cuentas.numerodecuenta, pacientes.tipo_id_paciente, pacientes.paciente_id, pacientes.primer_apellido, pacientes.segundo_apellido, pacientes.primer_nombre, pacientes.segundo_nombre, ingresos.fecha_ingreso, planes.plan_descripcion, hc_diagnosticos_ingreso.sw_principal, diagnosticos.diagnostico_nombre, hc_diagnosticos_ingreso.tipo_diagnostico_id
 FROM   ((public.cuentas cuentas INNER JOIN (((public.hc_diagnosticos_ingreso hc_diagnosticos_ingreso INNER JOIN public.diagnosticos diagnosticos ON hc_diagnosticos_ingreso.tipo_diagnostico_id=diagnosticos.diagnostico_id) INNER JOIN public.hc_evoluciones hc_evoluciones ON hc_diagnosticos_ingreso.evolucion_id=hc_evoluciones.evolucion_id) INNER JOIN public.ingresos ingresos ON hc_evoluciones.ingreso=ingresos.ingreso) ON cuentas.ingreso=ingresos.ingreso) INNER JOIN public.planes planes ON cuentas.plan_id=planes.plan_id) INNER JOIN public.pacientes pacientes ON (ingresos.paciente_id=pacientes.paciente_id) AND (ingresos.tipo_id_paciente=pacientes.tipo_id_paciente)
 WHERE  (ingresos.fecha_ingreso>='$input9' AND ingresos.fecha_ingreso<='$input10') AND hc_diagnosticos_ingreso.sw_principal='1' 
 ORDER BY ingresos.ingreso"); 

if (!$result_grupo) {
    echo "Error en Consulta1.\n";
    exit;
}

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
echo "<th>Diagnostico</th>\n";
echo "</tr>\n";

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
 	$color_body = "WHITE";

echo "<tr>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_grupo[0]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_grupo[1]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_grupo[8]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_max[0]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_grupo[2]"." "."$fetch_grupo[3]"." "."$fetch_grupo[6]"." "."$fetch_grupo[7]"." "."$fetch_grupo[4]"." "."$fetch_grupo[5]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_grupo[9]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_grupo[11]</div></td>\n";
echo "</tr>\n";
}
}
echo "</table>\n";

?>