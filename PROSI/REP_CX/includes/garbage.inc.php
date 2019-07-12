<?php

/**
 * $Id: garbage.inc.php,v 1.3 2005/06/30 16:16:18 alex Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: Libreriar para control de limpiezas en la bd.
 */

function Garbage()
{
    list($dbconn) = GetDBconn();
    $sql="SELECT garbage_at_id,funcion FROM system_garbage_at WHERE sw_estado='1' AND fecha_ejecucion < NOW()";
    $resultado=$dbconn->Execute($sql);
    if($dbconn->ErrorNo() != 0) {
        return false;
    }
    if(!$resultado->EOF){
        while($tarea = $resultado->FetchRow()){
            $sql="SELECT $tarea[1];";
            $dbconn->Execute($sql);
            if($dbconn->ErrorNo() != 0) {
                $sql="UPDATE system_garbage_at SET sw_estado='2' WHERE garbage_at_id=$tarea[0]";
            }else{
                $sql="UPDATE system_garbage_at SET sw_estado='0' WHERE garbage_at_id=$tarea[0]";    
            }    
            $dbconn->Execute($sql);    
            if($dbconn->ErrorNo() != 0) {
                return false;
            }                
        }
    }
    $resultado->Close();
    return true;    
}


function Garbage_day()
{
    list($dbconn) = GetDBconn();
    $sql="SELECT garbage_day_id,function FROM system_garbage_day
        WHERE (ultima_ejecucion IS NULL OR ultima_ejecucion < current_date)
        AND hora <= current_time;";
    $resultado=$dbconn->Execute($sql);
    if($dbconn->ErrorNo() != 0) 
    {
        return false;
    }
    if(!$resultado->EOF)
    {
        while($tarea = $resultado->FetchRow())
        {
            $sql="SELECT $tarea[1];";
            $dbconn->Execute($sql);
            if(!$dbconn->ErrorNo() != 0) 
            {
                $sql="UPDATE system_garbage_at SET ultima_ejecucion=NOW() WHERE garbage_at_id=$tarea[0]";        
                $dbconn->Execute($sql);    
                if($dbconn->ErrorNo() != 0) 
                return false;
            }                
        }
    }
    $resultado->Close();
    return true;    
}
?>
