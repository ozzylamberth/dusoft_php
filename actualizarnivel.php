<?php
    $VISTA = "HTML";
    $_ROOT = "";
    
    include "includes/enviroment.inc.php";
    list($dbconn)=GetDBConn();
    
    $sql .= "SELECT TR.triage_id,
                                    IG.ingreso
                    FROM        triages TR, 
                                    ingresos IG,
                                    pacientes_urgencias PU
                    WHERE       TR.sw_no_atender = 0 
                    AND         TR.tipo_id_paciente = IG.tipo_id_paciente 
                    AND         TR.paciente_id = IG.paciente_id 
                    AND         IG.ingreso = PU.ingreso
                    AND         IG.ingreso = TR.ingreso
                    AND         PU.triage_id IS NULL ";

    $rst = $dbconn->Execute($sql);
                
    if ($dbconn->ErrorNo() != 0)
    {
        $frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
        echo "<b class=\"label\">".$frmError['MensajeError']."</b>";
        return false;
    }
    
    $ingresos = array();
    while (!$rst->EOF)
    {
        $ingresos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
    }
    $rst->Close();
    
    $i = 0;
    list($dbconn)=GetDBConn();
    foreach($ingresos as $key=> $nivel)
    {
        $sql = "UPDATE  pacientes_urgencias 
                        SET         triage_id = ".$nivel['triage_id']." 
                        WHERE       ingreso = ".$key." 
                        AND         triage_id IS NULL ";
        $rst = $dbconn->Execute($sql);
        
        if ($dbconn->ErrorNo() != 0)
        {
            $frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
            echo "<b class=\"label\">".$frmError['MensajeError']."</b>";
            return false;
        }
        
        echo ($i++)."->Ingreso Nº ".$key." Actualizado <br>";
    }
    
    if(sizeof($ingreso) == 0)
        echo "NO HAY REGISTROS PARA ACTUALIZAR";
        
    $rst->Close();
?>