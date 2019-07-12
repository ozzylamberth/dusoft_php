<?php

$VISTA='HTML';
$_ROOT = '';

include $_ROOT . 'includes/enviroment.inc.php';

list($dbconn) = GetDBconn();

$A[]='CB';
$A[]='CU';
$A[]='FB';
$A[]='FE';
$A[]='FT';
$A[]='CE';
$A[]='FF';

echo "PREFIJO,MIN.,MAX.,NUMERO_FACTURAS,ANULADAS,PASADAS,VALOR_TOTAL <BR>\n";
FOREACH($A AS $K=>$V)
{
    $sql = "SELECT COUNT(*) 
            FROM fac_facturas 
            
            WHERE date_trunc('day',fecha_registro)>='2006-01-01'
            AND date_trunc('day',fecha_registro)<='2006-01-31'
            AND empresa_id='01'
            AND prefijo='$V';";
            
    $result = $dbconn->Execute($sql);
    list($NUM)=$result->FetchRow();
    $result->close();
       
    $sql = "SELECT factura_fiscal   
            FROM fac_facturas 
            
            WHERE date_trunc('day',fecha_registro)>='2006-01-01'
            AND date_trunc('day',fecha_registro)<='2006-01-31'
            AND empresa_id='01'
            AND prefijo='$V' 
            ORDER BY 1 ASC
            LIMIT 1;";
            
    $result = $dbconn->Execute($sql);
    list($MIN)=$result->FetchRow();
    $result->close();   
  
    $sql = "SELECT factura_fiscal   
            FROM fac_facturas 
            
            WHERE date_trunc('day',fecha_registro)>='2006-01-01'
            AND date_trunc('day',fecha_registro)<='2006-01-31'
            AND empresa_id='01'
            AND prefijo='$V' 
            ORDER BY 1 DESC
            LIMIT 1;";
    $result = $dbconn->Execute($sql);
    list($MAX)=$result->FetchRow();
    $result->close(); 
    
    $sql = "SELECT COUNT(*) 
            FROM fac_facturas 
            
            WHERE date_trunc('day',fecha_registro)>='2006-01-01'
            AND date_trunc('day',fecha_registro)<='2006-01-31'
            AND empresa_id='01'
            AND estado='2'
            AND prefijo='$V';";
            
    $result = $dbconn->Execute($sql);
    list($ANL)=$result->FetchRow();
    $result->close();  
    
    $sql = "SELECT SUM(total_factura) 
            FROM fac_facturas 
            
            WHERE date_trunc('day',fecha_registro)>='2006-01-01'
            AND date_trunc('day',fecha_registro)<='2006-01-31'
            AND empresa_id='01'
            AND estado!='2'
            AND prefijo='$V';"; 
              
    $result = $dbconn->Execute($sql);
    list($TOTAL)=$result->FetchRow();
    $result->close();            
    
    $CAN = ($NUM - $ANL);
    
    echo "$V,$MIN,$MAX,$NUM,$ANL,$CAN,$TOTAL <BR>\n";  
}


?>
