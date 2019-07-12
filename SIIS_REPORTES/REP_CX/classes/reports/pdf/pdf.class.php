<?php
// pos.class.php  07/22/2004
// -------------------------------------------------------------------------------------
// SIIS v 0.1
// Copyright (C) 2003 IPSOFT SA.
// Email: mail@ipsoft-sa.com
// -------------------------------------------------------------------------------------
// Autor: Alexander Giraldo  -- alexgiraldo@ipsoft-sa.com
// Proposito del Archivo: Clase para la generacion de reportes POS
// -------------------------------------------------------------------------------------

class pdf_reports_class
{

    var $datos;
    var $driver; // objecto driver
    var $impresora;
    var $reporte;//nombre del archivo 'reporte' generado
    var $error;
    var $mensajeDeError;
    var $dir_spool;
		var $orientacion;
		var $unidad;
		var $formato;
		var $html;


    function pdf_reports_class($orientacion,$unidad,$formato,$html)
    {
				if(empty($orientacion))
				{
					$this->orientacion='P';
				}
				else
				{
					$this->orientacion=$orientacion;
				}
				if(empty($unidad))
				{
					$this->unidad='mm';
				}
				else
				{
					$this->unidad=$unidad;
				}
				if(empty($formato))
				{
					$this->formato='A4';
				}
				else
				{
					$this->formato=$formato;
				}
				if(empty($html))
				{
					$this->html=0;
				}
				else
				{
					$this->html=1;
				}
        $this->datos=array();
        $this->impresora=array();
        $this->reporte='';
        $this->error='';
        $this->mensajeDeError='';
        $this->dir_spool=GetVarConfigAplication('DirSpool');

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

    //metodo que genera el nombre del archivo
    function GetNombreReport()
    {
        $var=tempnam($this->dir_spool, "pdf_");
        return($var);
    }

    function CrearArchivoSpool()
    {
        $driver=&$this->driver;
        /*$contenido=$driver->GetSalida();
        if(empty($contenido)){
            $this->error = "No se pudo generar el reporte.";
            $this->mensajeDeError = "El reporte no genero Contenido";
            return false;
        }*/

        $archivo_report=$this->GetNombreReport();

        /*$fp = fopen($archivo_report, "w");

        if(!$fp){
            $this->error = "No se pudo generar el reporte.";
            $this->mensajeDeError = "No se pudo crear el archivo de impresion : $archivo_report";
            return false;
        }

        fwrite($fp,$contenido);

        if(!fclose($fp)){
            $this->error = "No se pudo generar el reporte.";
            $this->mensajeDeError = "No se pudo cerrar el archivo de impresion.";
            return false;
        }*/
				$driver->Output($archivo_report,'F');
				unset($driver);

        $this->reporte=$archivo_report;
        return true;

    }


    //metodo que me retorna el nombre del archivo - reporte.
    function  GetReport($datos,$impresora)
    {
        $this->datos=$datos;
        $this->impresora=$impresora;

				define('FPDF_FONTPATH','font/');
				if($this->html==0)
				{
					$fileDriver="classes/reports/pdf/drivers/fpdf/fpdf.php";
					if (!IncludeFile($fileDriver)) {
							$this->error = "No se pudo generar el reporte.";
							$this->mensajeDeError = "El Driver fpdf $fileDriver no existe.";
							return false;
					}
					$clase = "FPDF";
					if(!class_exists($clase)){
							$this->error = "No se pudo generar el reporte.";
							$this->mensajeDeError = "El Driver PDF FPDF no es correcto, no existe la clase : $clase";
							return false;
					}
				}
				else
				{
					$fileDriver="classes/reports/pdf/drivers/fpdf/html_class.php";
					if (!IncludeFile($fileDriver)) {
							$this->error = "No se pudo generar el reporte.";
							$this->mensajeDeError = "El Driver fpdf $fileDriver no existe.";
							return false;
					}
					$clase = "PDF";
					if(!class_exists($clase)){
							$this->error = "No se pudo generar el reporte.";
							$this->mensajeDeError = "El Driver PDF FPDF no es correcto, no existe la clase : $clase";
							return false;
					}
				}

        $this->driver = new $clase($this->orientacion,$this->unidad,$this->formato);

        //METODO QUE DEBE CREAR EL PROGRAMADOR EN EL ARCHIVO DE REPORTES.
        if(!$this->CrearReporte()){
            return false;
        }

        if(!$this->CrearArchivoSpool()){
            return false;
        }

        if(empty($this->reporte))
        {
            $this->error = "No se pudo generar el reporte.";
            $this->mensajeDeError = "El reporte no retorno datos.";
            return false;
        }elseif(!file_exists($this->reporte)){
            $this->error = "No se pudo generar el reporte.";
            $this->mensajeDeError = "El archivo para el spool de impresion '".$this->reporte."' no existe o no se genero.";
            return false;
        }else{
            return $this->reporte;
        }

    }//fin GetReport
    
}//fin de la class

?> 
