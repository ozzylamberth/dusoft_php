<?php
require("includes/session.php");
/*require("includes/config.php");*/
require("includes/database.php");
require("includes/funciones.php");
procesar_entrada("GET", "envio_id");
$envio_id = get_value($_GET["envio_id"], "N");

open_database(); 

//header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=envio$envio_id.txt");
header("Pragma: no-cache");
header("Expires: 0");


$query_envio = execute_query($dbh, "SELECT a.razon_social as razon_social, 
								       a.id as nit,
								       b.prefijo||' '||b.factura_fiscal as num_factura,
								       TO_CHAR(b.fecha_registro, 'YYYY-MM-DD') as fecha_factura,
								       '04' as tipo_servicio,
								       b.tercero_id as identificacion_pagador,
								       TRUNC(b.total_factura,0) as vlr_bruto_ips,
								       TRUNC(b.descuento,0) as vlr_dcto,
								       TRUNC((b.valor_cuota_paciente + b.valor_cuota_moderadora),0) as vlr_pagos_usuario,
								       TRUNC((b.total_factura - b.descuento),0) as vlr_neto_factura
								FROM empresas a,
								     fac_facturas b,
								     fac_facturas_cuentas c,
								     envios d,
								     envios_detalle e
								WHERE d.envio_id = $envio_id
								AND d.envio_id = e.envio_id
								AND (e.prefijo = b.prefijo AND e.factura_fiscal = b.factura_fiscal)
								AND (b.prefijo = c.prefijo AND b.factura_fiscal = c.factura_fiscal)
								AND b.empresa_id = a.empresa_id
								ORDER BY b.prefijo, b.factura_fiscal");
								
while ($row_envio = fetch_object($query_envio)) {
 

echo $row_envio->razon_social.',';
echo $row_envio->nit.',';
echo $row_envio->num_factura.',';
echo $row_envio->fecha_factura.',';
echo $row_envio->tipo_servicio.',';
echo $row_envio->identificacion_pagador.',';
echo $row_envio->vlr_bruto_ips.',';
echo $row_envio->vlr_dcto.',';
echo $row_envio->vlr_pagos_usuario.',';
echo $row_envio->vlr_neto_factura;
echo"\r\n"; 
 
} 								

?>