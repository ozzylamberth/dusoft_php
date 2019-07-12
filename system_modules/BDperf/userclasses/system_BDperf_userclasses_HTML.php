
<?php

/**
* Modulo de Performance del Motor de BD (PHP).
*
*
* @author Alexander Giraldo salas <alexgiraldo@ipsoft-sa.com>
* @version 1.0
* @package SIIS
**/


class system_BDperf_userclasses_HTML extends system_BDperf_user
{
    function system_BDperf_userclasses_HTML()
    {
        $this->system_BDperf_user();
        return true;
    }

    function LinkRecargar()
    {
        if(GetVarConfigAplication('ActivarDepuracionSQL'))
        {
            $url3=ModuloGetURL('system','BDperf','user','DesactivarMonitorSQL');
            $msg="Desactivar Monitoreo SQL";
        }
        else
        {
            $url3=ModuloGetURL('system','BDperf','user','ActivarMonitorSQL');
            $msg="Activar Monitoreo SQL";
        }
        $url=ModuloGetURL('system','BDperf','user','main');
        $url2=ModuloGetURL('system','BDperf','user','clearBD');
        $this->salida .= "<table width='100%' border=0><tr>\n" ;
        $this->salida .= "<td align='left'><a href='$url'>Recargar pagina</a></td>\n";
        $this->salida .= "<td align='center'><a href='$url3'>$msg</a></td>\n";
        $this->salida .= "<td align='right'><a href='$url2'>Limpiar Historico</a></td>\n";
        $this->salida .= "</tr></table><br>\n" ;
        return true;
    }

}//fin de la class
?>
