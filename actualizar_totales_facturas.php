<?
        $VISTA='HTML';
        include 'includes/enviroment.inc.php';
        //$_ROOT = 'SIIS/';
        //include $_ROOT . 'includes/enviroment.inc.php';
        list($dbconn) = GetDBconn();
        
        $EmpresaId='01';
        $Prefijo='FF';
        
        //$Plan=210;      
        //$Factura=6661;
        $Plan=223; 
        $Factura=6662;  
                                 //PLAN
        $filtroFecha="and date(a.fecha_registro) <= date('2006-05-31') and date(a.fecha_registro) >= date('2006-04-29')";
              //FACTURA
        $query="INSERT INTO fac_facturas_cuentas
                                (
                                    empresa_id,
                                    prefijo,
                                    factura_fiscal,
                                    numerodecuenta,
                                    sw_tipo
                                )
                                SELECT '01',
                                                '$Prefijo',
                                                $Factura,
                                                a.numerodecuenta,
                                                '1'
                                FROM cuentas as a, 
                                            ingresos as b
                                WHERE a.empresa_id='01'
                                AND a.estado=3  
                                AND a.valor_total_empresa > 0
                                AND a.ingreso=b.ingreso  
                                $filtroFecha
                                AND a.plan_id='".$Plan."'";
        $result=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
                echo "Error al Guardar fac_facturas_cuentas";
                echo "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
        }
        else
        {
        
                $query = "UPDATE cuentas SET estado='0' 
                                            WHERE numerodecuenta IN 
                                                        (
                                                            SELECT a.numerodecuenta
                                                            FROM cuentas as a, 
                                                                        ingresos as b
                                                            WHERE a.empresa_id='01'
                                                            AND a.estado=3  
                                                            AND a.valor_total_empresa > 0
                                                            AND a.ingreso=b.ingreso  
                                                            $filtroFecha
                                                            AND a.plan_id='".$Plan."'
                                                        );";
                $result=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        echo "Error al Guardar5";
                        echo "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                }
                else
                {
                
                    $query = "SELECT a.ingreso
                                        FROM cuentas as a, 
                                                    ingresos as b
                                        WHERE a.empresa_id='01'
                                        AND a.estado=3  
                                        AND a.valor_total_empresa > 0
                                        AND a.ingreso=b.ingreso  
                                        $filtroFecha
                                        AND a.plan_id='".$Plan."';";
                        $result=$dbconn->Execute($query);
                        while (!$result->EOF)
                        {
                                $var[]=$result->GetRowAssoc($ToUpper = false);
                                $result->MoveNext();
                        }
                        foreach($var as $k => $v)
                        {
                            foreach($v as $k1 => $v1)
                            {
                            
                                    $query = "SELECT count(*) 
                                                        FROM cuentas
                                                        WHERE ingreso=$v1 AND estado not in(0,5)";
                                    $result=$dbconn->Execute($query);
                                    if($result->fields[0] == 1)
                                    {
                                                echo '<br><br><br>';
                                                echo    $query = "UPDATE ingresos SET estado='0',fecha_cierre='now()'
                                                                        WHERE ingreso=$v1";
                                                    $result=$dbconn->Execute($query);
                                                    if ($dbconn->ErrorNo() != 0) {
                                                            echo "Error al Guardar6";
                                                            echo "Error DB : " . $dbconn->ErrorMsg();
                                                            $dbconn->RollbackTrans();
                                                            return false;
                                                        }
                                    }
                            }
                        }
                }
        }
    
        //ACTUALIZAR TOTALES FACTURAS
        $query="SELECT actualizar_totales_facturas('$EmpresaId','$Prefijo',$Factura);";
        $result=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
                echo "Error al Guardar fac_facturas_cuentas";
                echo "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
        }
        //FIN ACTUALIZAR TOTALES FACTURAS
    
        $dbconn->CommitTrans();

echo '<br>'; 
echo "<center>TERMINADO</center>";
    
?>




