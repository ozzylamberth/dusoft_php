<?php
// reports.class.php  07/07/2004
// -------------------------------------------------------------------------------------
// SIIS v 0.1
// Copyright (C) 2003 IPSOFT SA.
// Email: mail@ipsoft-sa.com
// -------------------------------------------------------------------------------------
// Autor: Alexander Giraldo  -- alexgiraldo@ipsoft-sa.com
// Proposito del Archivo: Clase para la generacion de reportes impresos
// -------------------------------------------------------------------------------------


class reports
{
    var $error;
    var $mensajeDeError;
    var $impresora;
    var $exec_resultado;
    
    function reports()
    {
        $this->error='';
        $this->mensajeDeError='';
        $this->impresora=array();
        $this->exec_resultado=array();
        return true;
    }
    
    function GetError()
    {
        return $this->error;
    }
    
    function MensajeDeError()
    {
        return $this->mensajeDeError;
    }
    
    function GetExecResultado()
    {
        return $this->exec_resultado;
    }


        function GetImpresoraPredeterminada($tipo_reporte)
        {
            list($dbconn) = GetDBconn();
            switch($tipo_reporte)
            {
                case 'pos':
                            $sql="select a.impresora from system_printers_host as a join system_printers as b on(a.impresora=b.impresora and b.sw_pos=1) where a.ip='".GetIPAddress()."' and a.sw_predeterminada='1';";
                            break;
                case 'pdf':
                            $sql="select impresora from system_printers_host where ip='".GetIPAddress()."' and sw_predeterminada='1';";
                            break;
                default:
                            $this->error = "No se pudo encontrar el tipo de reporte";
                            $this->mensajeDeError = "El tipo $tipo_reporte no esta catalogado.";
                            return false;
            }
            $resultado = $dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error en el SQL";
                    $this->mensajeDeError = "Reporte del Servidor : ".$dbconn->ErrorMsg();
                    return false;
            }
            if($resultado->EOF){
                    $this->error = "No se pudo Imprimir el Reporte";
                    $this->mensajeDeError = "La Impresora $impresora no esta catalogada en el sistema";
                    return false;
            }
            return $resultado->fields[0];
        }
    
    
    /*
    Metodo para obtener un reporte de la aplicacion
    */
    function GetLinkReport($tipo_reporte,$tipo_modulo,$modulo,$reporte_name,$retorno='BOTON')
    {
        return true;
    }//fin funcion GetLinkReport
    
    
    /*
    Metodo para mandar a imprimir un reporte desde el servidor aplicacion
    */    
    function PrintReport($tipo_reporte,$tipo_modulo,$modulo,$reporte_name,$datos,$impresora,$orientacion,$unidad,$formato,$html)
    {
        switch($tipo_reporte)
        {
            case 'pos':
                $file="classes/reports/pos/pos.class.php";
                if (!IncludeFile($file)) {
                    $this->error = "No se pudo inicializar la Clase de Reportes POS";
                    $this->mensajeDeError = "No se pudo Incluir el archivo : $file";
                    return false;
                }
            break;
                        case 'pdf':
                                $file="classes/reports/pdf/pdf.class.php";
                                if (!IncludeFile($file)) {
                    $this->error = "No se pudo inicializar la Clase de Reportes PDF";
                    $this->mensajeDeError = "No se pudo Incluir el archivo : $file";
                    return false;
                }
                        break;
            default:
                $this->error = "No se pudo encontrar el tipo de reporte";
                $this->mensajeDeError = "El tipo $tipo_reporte no esta catalogado.";
                return false;
        }

        list($dbconn) = GetDBconn();
        global $ADODB_FETCH_MODE;
        $query = "SELECT *  FROM system_printers WHERE impresora='$impresora'";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $resultado = $dbconn->Execute($query);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error en el SQL";
            $this->mensajeDeError = "Reporte del Servidor : ".$dbconn->ErrorMsg();
            return false;
        }
            
        if($resultado->EOF){
            $this->error = "No se pudo Imprimir el Reporte";
            $this->mensajeDeError = "La Impresora $impresora no esta catalogada en el sistema";
            return false;
        }
                
        $this->impresora=$resultado->FetchRow();
        $resultado->Close();
            
        $file=$tipo_modulo."_modules/".$modulo."/reports/$tipo_reporte/$reporte_name".".report.php";
        if (!IncludeFile($file)) {
            $this->error = "No se pudo encontrar el reporte";
            $this->mensajeDeError = "No se pudo Incluir el archivo : $file";
            return false;  
        }        
        $clase = "$reporte_name"."_report";
        if(!class_exists($clase)){
            $this->error = "Reporte Incorrecto";
            $this->mensajeDeError = "El reporte no contiene la clase : $clase";
            return false;          
        }
        $reporte = new $clase($orientacion,$unidad,$formato,$html);
        $rpt=$reporte->GetReport($datos,$this->impresora);
        if(!$rpt){
            $this->error = $reporte->GetError();
            $this->mensajeDeError = $reporte->MensajeDeError();
            return false;          
        }
        
        $cmd = $this->impresora[comando]." $rpt";
        $cmd = str_replace ("%PRINTER", $this->impresora[impresora], $cmd);
                //echo $cmd;
        exec(EscapeShellCmd($cmd),$this->exec_resultado[salida],$this->exec_resultado[codigo]);
        return true;
    
    }//fin funcion PrintReport
    
    
    /*
    Metodo para obtener los reportes de algun modulo de la aplicacion.
    */        
    function GetTiposReportes($tipo_modulo,$modulo,$reporte_name)
    {    
        return true;
    }//fin funcion GetTiposReportes

}//fin clase reports

?> 
