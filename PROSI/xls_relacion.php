<?php
require("includes/session.php");
/*require("includes/config.php");*/
require("includes/database.php");
require("includes/funciones.php");
procesar_entrada("GET", "relacion_id");
$relacion_id = get_value($_GET["relacion_id"], "C");

open_database(); 

header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=relacion$relacion_id.xls");
header("Pragma: no-cache");
header("Expires: 0");



echo "<table border=1>\n";
echo "<tr>\n";
echo "<td  background='WHITE' colspan='18' bgcolor='#EEEEEE'><div align='center'><strong><font color='BLACK' size='2'>RELACION DE CUENTA NRO $relacion_id</font></strong></div></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<th>Nro Relacion</th>\n";
echo "<th>Factura</th>\n";
echo "<th>Cedula</th>\n";
echo "<th>Cuenta</th>\n";
echo "<th>Nombre Paciente</th>\n";
echo "<th>Codigo</th>\n";
echo "<th>Empresa</th>\n";
echo "<th>Valor</th>\n";
echo "<th>Vlr NC</th>\n";
echo "<th>Vlr ND</th>\n";
echo "<th>Vlr NG</th>\n";
echo "<th>Vlr Aceptado</th>\n";
echo "<th>Estado</th>\n";
echo "<th>Fecha Ingreso</th>\n";
echo "<th>Fecha Egreso</th>\n";
echo "<th>Fecha Factura</th>\n";
echo "<th>Fecha Relacion</th>\n";
echo "<th>Observacion</th>\n";
echo "</tr>\n";

$query_relacion = execute_query($dbh, "SELECT fac_facturas.prefijo, fac_facturas.factura_fiscal, cuentas.numerodecuenta, pacientes.primer_apellido, pacientes.tipo_id_paciente, pacientes.paciente_id, pacientes.primer_nombre, pacientes.segundo_nombre, pacientes.segundo_apellido, planes.plan_id, terceros.nombre_tercero, fac_facturas.fecha_registro AS fecha_factura, relacion_cuentas.fecha_registro AS fecha_relacion, relacion_cuentas.relacion_id, relacion_cuentas_detalle.total_cuenta, relacion_cuentas_detalle.observacion, relacion_cuentas_detalle.estado
 FROM  ((((((public.relacion_cuentas_detalle relacion_cuentas_detalle INNER JOIN public.relacion_cuentas relacion_cuentas ON relacion_cuentas_detalle.relacion_id=relacion_cuentas.relacion_id) INNER JOIN public.cuentas cuentas ON relacion_cuentas_detalle.numerodecuenta=cuentas.numerodecuenta) LEFT OUTER JOIN public.fac_facturas fac_facturas ON ((relacion_cuentas_detalle.empresa_id=fac_facturas.empresa_id) AND (relacion_cuentas_detalle.prefijo=fac_facturas.prefijo)) AND (relacion_cuentas_detalle.factura_fiscal=fac_facturas.factura_fiscal)) INNER JOIN public.planes planes ON cuentas.plan_id=planes.plan_id) INNER JOIN public.ingresos ingresos ON cuentas.ingreso=ingresos.ingreso) INNER JOIN public.pacientes pacientes ON (ingresos.paciente_id=pacientes.paciente_id) AND (ingresos.tipo_id_paciente=pacientes.tipo_id_paciente)) INNER JOIN public.terceros terceros ON (planes.tipo_tercero_id=terceros.tipo_id_tercero) AND (planes.tercero_id=terceros.tercero_id)
 WHERE  relacion_cuentas.relacion_id=$relacion_id
 ORDER BY relacion_cuentas_detalle.rel_det_id");
 
 $cantidad_cuentas_cargadas = pg_num_rows($query_relacion);
			$total_relacion = 0;
			$sum_NC = 0;
			$sum_ND = 0;
			$sum_NG = 0;
			$sum_NG_ACEPTADO = 0;
		while ($row_relacion = fetch_object($query_relacion)) {
		 
		  												 	
				$query_sum_NC = "SELECT SUM(valor_nota) AS NC_total
		 											FROM notas_credito
												 	WHERE prefijo_factura = '" . $row_relacion->prefijo . "'
												 	AND factura_fiscal = " . $row_relacion->factura_fiscal . "
													AND estado = '1'";
    			$resultado_NC = execute_query($dbh, $query_sum_NC);
       			$NC = pg_fetch_row($resultado_NC);
       			$sum_NC = $NC[0];
       			
       			$query_sum_ND = "SELECT SUM(valor_nota) AS ND_total
		 											FROM notas_debito
												 	WHERE prefijo_factura = '" . $row_relacion->prefijo . "'
												 	AND factura_fiscal = " . $row_relacion->factura_fiscal . "
													AND estado = '1'";
													
				$resultado_ND = execute_query($dbh, $query_sum_ND);
       			$ND = pg_fetch_row($resultado_ND);
       			$sum_ND = $ND[0];
				   
				$query_sum_NG = "SELECT SUM(a.valor_glosa) AS NG_TOTAL, SUM(a.valor_aceptado) AS NG_ACEPTADO
		 											FROM glosas a
												 	WHERE a.prefijo = '" . $row_relacion->prefijo . "'
												 	AND a.factura_fiscal = " . $row_relacion->factura_fiscal . "
													AND a.sw_estado IN ('1','2','3')";
													
				$resultado_NG = execute_query($dbh, $query_sum_NG);
       			$NG = pg_fetch_row($resultado_NG);
       			$sum_NG = $NG[0];
				$sum_NG_ACEPTADO = $NG[1];
				
				
				$datos_ingreso = "SELECT cuentas.numerodecuenta, ingresos.ingreso, ingresos.fecha_ingreso, 
	 					ingresos_salidas.fecha_registro
 						FROM   public.cuentas cuentas INNER JOIN (public.ingresos_salidas ingresos_salidas 
						RIGHT OUTER JOIN public.ingresos ingresos ON ingresos_salidas.ingreso=ingresos.ingreso) 
						ON cuentas.ingreso=ingresos.ingreso
 						WHERE  cuentas.numerodecuenta=$row_relacion->numerodecuenta";
	 											
    				$resultado_ingreso = execute_query($dbh, $datos_ingreso);
       				$row_ingreso = pg_fetch_row($resultado_ingreso);
					free_result($resultado_ingreso);
					$quitar_decimal_ingreso = explode(" ", $row_ingreso[2]);
					$fecha_ingreso = $quitar_decimal_ingreso[0];
					if($fecha_ingreso <> ""){
					$fecha_ingreso = strftime('%d/%m/%y',strtotime($fecha_ingreso));
					}
					$quitar_decimal_egreso = explode(" ", $row_ingreso[3]);
					$fecha_egreso = $quitar_decimal_egreso[0];
					if($fecha_egreso <> ""){
					$fecha_egreso = strftime('%d/%m/%y',strtotime($fecha_egreso));
					}									
		 
		 if($row_relacion->estado == "R"){
				$estado_relacion = "Relacionada";
			}
			else if($row_relacion->estado == "E"){
				$estado_relacion = "Recibida";
			}
			else if($row_relacion->estado == "N"){
				$estado_relacion = "Rechazada";
			}
			else{
				
			}
$total_cuenta = explode(".", $row_relacion->total_cuenta);
$sum_NC = explode(".", $sum_NC);
$sum_ND = explode(".", $sum_ND);
$sum_NG = explode(".", $sum_NG);
$sum_NG_ACEPTADO = explode(".", $sum_NG_ACEPTADO);		 
echo "<tr>\n";
echo "<td><div align='left'>$row_relacion->relacion_id</div></td>\n";
echo "<td><div align='left'>$row_relacion->prefijo"." "."$row_relacion->factura_fiscal</div></td>\n";
echo "<td><div align='left'>$row_relacion->tipo_id_paciente"." "."$row_relacion->paciente_id</div></td>\n";
echo "<td><div align='left'>$row_relacion->numerodecuenta</div></td>\n";
echo "<td><div align='left'>$row_relacion->primer_nombre"." "."$row_relacion->segundo_nombre"." "."$row_relacion->primer_apellido"." "."$row_relacion->segundo_apellido</div></td>\n";
echo "<td><div align='left'>$row_relacion->plan_id</div></td>\n";
echo "<td><div align='left'>$row_relacion->nombre_tercero</div></td>\n";
 
echo "<td><div align='left'>$total_cuenta[0]</div></td>\n";

echo "<td><div align='left'>$sum_NC[0]</div></td>\n";
echo "<td><div align='left'>$sum_ND[0]</div></td>\n";
echo "<td><div align='left'>$sum_NG[0]</div></td>\n";
echo "<td><div align='left'>$sum_NG_ACEPTADO[0]</div></td>\n";
echo "<td><div align='left'>$estado_relacion</div></td>\n";
echo "<td><div align='left'>$fecha_ingreso</div></td>\n";
echo "<td><div align='left'>$fecha_egreso</div></td>\n";
echo "<td><div align='left'>$row_relacion->fecha_factura</div></td>\n";
echo "<td><div align='left'>$row_relacion->fecha_relacion</div></td>\n";
echo "<td><div align='left'>$row_relacion->observacion</div></td>\n";
echo "</tr>\n";
$total_relacion = $total_relacion + $total_cuenta[0];
}

echo "<tr>\n";
echo "<td></td>\n";
echo "<td></td>\n";
echo "<td></td>\n";
echo "<td></td>\n";
echo "<td></td>\n";
echo "<td></td>\n";
echo "<td><strong>Total Relacion</strong></td>\n";
echo "<td><strong>$total_relacion</strong></td>\n";
echo "<td></td>\n";
echo "<td></td>\n";
echo "<td></td>\n";
echo "<td></td>\n";
echo "<td></td>\n";
echo "<td></td>\n";
echo "<td></td>\n";
echo "<td></td>\n";
echo "<td></td>\n";
echo "<td></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td></td>\n";
echo "<td></td>\n";
echo "<td></td>\n";
echo "<td></td>\n";
echo "<td></td>\n";
echo "<td></td>\n";
echo "<td><strong>Cantidad Cuentas</strong></td>\n";
echo "<td><strong>$cantidad_cuentas_cargadas</strong></td>\n";
echo "<td></td>\n";
echo "<td></td>\n";
echo "<td></td>\n";
echo "<td></td>\n";
echo "<td></td>\n";
echo "<td></td>\n";
echo "<td></td>\n";
echo "<td></td>\n";
echo "<td></td>\n";
echo "<td></td>\n";
echo "</tr>\n";
echo "</table>\n";
?>