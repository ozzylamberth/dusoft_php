<?php

$input9 = $_REQUEST["input9"];
$input10 = $_REQUEST["input10"];


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

$result_tmp=pg_exec($dbconn, "SELECT a.envio_id, a.fecha_envio, b.nombre_tercero, a.fecha_radicacion, a.prefijo, a.factura_fiscal, c.nombre, a.prefijo_nota, a.nota_id, a.vlr_nota, a.prefijo_ng, a.ng_id, a.vlr_ng_aceptado, a.vlr_ng_noaceptado, fecha_factura, total_factura
 FROM   tmp_reporte_cobranzas a, terceros b, system_usuarios c
 WHERE a.tercero_id = b.tercero_id AND a.tipo_tercero_id = b.tipo_id_tercero AND a.usuario_id = c.usuario_id
 ORDER BY 1"); 

if (!$result_tmp) {
    echo "Error en Consulta1.\n";
    exit;
}

echo "<table border=1>\n";
echo "<tr>\n";
echo "<td  background='WHITE' colspan='16' bgcolor='#EEEEEE'><div align='center'><strong><font color='BLACK' size='2'>Facturacion Radicada entre $input9 y $input10</font></strong></div></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<th>Envio</th>\n";
echo "<th>Fecha Envio</th>\n";
echo "<th>Responsable</th>\n";
echo "<th>Fecha Radicacion</th>\n";
echo "<th>Prefijo</th>\n";
echo "<th>Factura</th>\n";
echo "<th>Valor</th>\n";
echo "<th>Fecha</th>\n";
echo "<th>Usuario</th>\n";
echo "<th>Prefijo Nota</th>\n";
echo "<th>Numero Nota</th>\n";
echo "<th>Valor Nota</th>\n";
echo "<th>Prefijo NG</th>\n";
echo "<th>Numero NG</th>\n";
echo "<th>Valor Aceptado NG</th>\n";
echo "<th>Valor no Aceptado NG</th>\n";
echo "</tr>\n";

while($fetch_tmp = pg_fetch_row($result_tmp)) {
 	$color_body = "WHITE";

echo "<tr>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_tmp[0]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_tmp[1]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_tmp[2]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_tmp[3]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_tmp[4]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_tmp[5]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_tmp[15]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_tmp[14]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_tmp[6]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_tmp[7]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_tmp[8]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_tmp[9]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_tmp[10]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_tmp[11]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_tmp[12]</div></td>\n";
echo "<td bgcolor='$color_body'><div align='left'>$fetch_tmp[13]</div></td>\n";
echo "</tr>\n";
}

echo "</table>\n";
?>