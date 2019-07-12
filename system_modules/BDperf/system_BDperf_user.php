
<?php

/**
* Modulo de Performance del Motor de BD (PHP).
*
*
* @author Alexander Giraldo salas <alexgiraldo@ipsoft-sa.com>
* @version 1.0
* @package SIIS
**/



class system_BDperf_user extends classModulo
{

    function system_BDperf_user()
    {
        return true;
    }


    function main()
    {

        if(!function_exists('NewPerfMonitor'))
        {
            $this->Msg('El metodo NewPerfMonitor no existe.');
        }
        else
        {
            list($dbconn) = GetDBconn();
            $perf = NewPerfMonitor($dbconn);
            $this->LinkRecargar();
            //$this->salida .= $perf->UI($pollsecs=5);
            $this->salida .= $perf->HealthCheck();
            $this->salida .= $perf->SuspiciousSQL();
            $this->salida .= $perf->ExpensiveSQL();
            $this->salida .= $perf->InvalidSQL();
            //$this->salida .= $perf->Tables();
            $this->LinkRecargar();
        }
        return true;
    }

    function  clearBD()
    {
        list($dbconn) = GetDBconn();
        $sql= "DELETE FROM adodb_logsql;";
        $dbconn->Execute($sql);
        $this->main();
        return true;
    }

    function DesactivarMonitorSQL()
    {
        global $ConfigAplication;
        ModuloSetVar('system', 'BDperf', 'ActivarDepuracionSQL',false);
        $ConfigAplication['ActivarDepuracionSQL']=false;
        $this->main();
        return true;
    }

    function ActivarMonitorSQL()
    {
        global $ConfigAplication;
        ModuloSetVar('system', 'BDperf', 'ActivarDepuracionSQL',true);
        $ConfigAplication['ActivarDepuracionSQL']=true;
        $this->main();
        return true;
    }

}//fin de la clase
?>
