<?php
// Contenido.php  05/08/2002
// ----------------------------------------------------------------------

// Copyright (C) 2002 Alexander Giraldo
// Emai: alexgiraldo777@yahoo.com

// ----------------------------------------------------------------------
// Autor: Alexander Giraldo
// ----------------------------------------------------------------------


$VISTA='HTML';
include 'includes/enviroment.inc.php';

GLOBAL $ADODB_FETCH_MODE;
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

list($dbconn) = GetDBconn();


$sql="
        SELECT d.*

        FROM cg_movimientos_contables_facturas d,
        fac_facturas a lEFT JOIN
        (
            SELECT DISTINCT b.prefijo,b.factura_fiscal,b.envio_id,b.empresa_id
            FROM
            envios_detalle b,
            envios c
            WHERE
            c.sw_estado != '2'
            AND c.envio_id = b.envio_id
        ) as b
        ON
        (
            b.prefijo=a.prefijo AND
            b.factura_fiscal=a.factura_fiscal AND
            b.empresa_id = a.empresa_id
        )
        WHERE d.prefijo=a.prefijo
        AND d.numero=a.factura_fiscal
        AND d.empresa_id = a.empresa_id
        AND a.sw_clase_factura='1'
        AND b.envio_id IS NULL
        ";

$result = $dbconn->Execute($sql);

if($dbconn->ErrorNo() != 0)
{
    echo "SQL ERROR :<BR>".$dbconn->ErrorMsg();
    exit;
}

if($result->EOF)
{
    echo "NO HAY FACTURAS SIN ENVIO CONTABILIZADAS.";
    exit;
}

echo "<H1>FACTURAS NO ENVIADAS</H1><BR><BR>";

$sql='';
$num = 0;
$salida ='';
while($Fila = $result->FetchRow())
{
    $num++;
    echo $Fila['prefijo']."-".$Fila['numero']."<BR>";
    $salida .= "UPDATE cg_movimientos_contables SET sw_estado='0', total_debitos=0, total_creditos=0 WHERE documento_contable_id=$Fila[documento_contable_id];<br>";
    $salida .= "DELETE FROM cg_movimientos_contables_facturas_d WHERE documento_contable_id=$Fila[documento_contable_id];<br>";

}
ECHO "OK. $num<br><br>";
ECHO $salida;
ECHO "<br><br>OK.";


?>
