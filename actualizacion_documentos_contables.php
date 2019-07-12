<?php

$VISTA='HTML';
$_ROOT = '';

include $_ROOT . 'includes/enviroment.inc.php';

GLOBAL $ADODB_FETCH_MODE;
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
list($dbconn) = GetDBconn();

//$sql = "UPDATE fac_facturas SET documento_contable_id=NULL";

//$result = $dbconn->Execute($sql);

// if($dbconn->ErrorNo() != 0)
// {
//     DIE('UPDATE');
// }


$sql = "SELECT * FROM cg_movimientos_contables_facturas";
$result = $dbconn->Execute($sql);

$A = $result->GetRows();
$result->close();

foreach($A as $k=>$v)
{
   
     $sql1 = "UPDATE fac_facturas SET documento_contable_id=$v[documento_contable_id] 
            WHERE empresa_id='$v[empresa_id]'
            AND factura_fiscal=$v[numero]
            AND prefijo='".trim($v[prefijo])."';\n";
            
    $dbconn->Execute($sql1);
    if($dbconn->ErrorNo() != 0)
    {
        DIE('UPDATE2');
    }  
 
    ECHO "$v[prefijo] $v[numero] <BR>";     
}
EXIT;
$result = $dbconn->Execute($sql1);
if($dbconn->ErrorNo() != 0)
{
    DIE('UPDATE2');
}
ECHO 'OK';EXIT;


$sql = "
SELECT DISTINCT d.documento_contable_id

FROM    fac_facturas as a,
        fac_facturas_cuentas as b,
        cuentas_detalle as c,
        cg_movimientos_contables_facturas d
        
WHERE c.cargo = 'IMD'
AND b.numerodecuenta = c.numerodecuenta
AND a.prefijo = b.prefijo
AND a.factura_fiscal = b.factura_fiscal
AND d.prefijo= b.prefijo
AND d.numero= b.factura_fiscal";

$result = $dbconn->Execute($sql);

WHILE($FILA = $result->FETCHROW())
{
    $sql_delete .= "DELETE FROM cg_movimientos_contables_facturas_d WHERE documento_contable_id=$FILA[documento_contable_id];\n";
    $sql_delete .= "DELETE FROM cg_movimientos_contables_facturas WHERE documento_contable_id=$FILA[documento_contable_id];\n";
    $sql_delete .= "DELETE FROM cg_movimientos_contables WHERE documento_contable_id=$FILA[documento_contable_id];\n";

}
$result = $dbconn->Execute($sql_delete);

if($dbconn->ErrorNo() != 0)
{
    DIE('DELETE');
}


?>
