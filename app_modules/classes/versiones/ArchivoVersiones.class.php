<?php
// ArchivoVersiones.class.php  11/03/2005
// -------------------------------------------------------------------------
// Copyright (C) 2005 IPSOFT S.A.
// Email: alexgiraldo@ipsoft-sa.com
// $Id: ArchivoVersiones.class.php,v 1.2 2005/03/16 19:47:52 alex Exp $
// -------------------------------------------------------------------------
// Autor: Alexander Giraldo
// Proposito del Archivo: Clase para la generacion del archivo de versiones
// -------------------------------------------------------------------------


class ArchivoVersiones
{

    var $error='';
    var $mensajeDeError='';
    var $archivo;
    var $ruta;
    
    
    function ArchivoVersiones()
    {
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
    
    function GetVersiones()
    {
        return $this->GetVector('hc_modules');
    }
    
    function LeerArchivoDeVersiones($directorio='')
    {
        if(empty($directorio))
        {
            global $_ROOT;
            $directorio=$_ROOT.'INSTALL';
        }
        else
        {
            $directorio = ereg_replace("[/]$",'',$directorio);
        }
        
        if(is_dir($directorio) && is_readable($directorio))
        {
            $handle=opendir($directorio);
            $ArchivosDeVersionEncontrados=array(); 
            while ($archivo = readdir($handle)) 
            {
                if(is_file($directorio.'/'.$archivo) && is_readable($directorio.'/'.$file))
                {
                    preg_match('/VERSIONES_SIIS_(.*?)$/im', $archivo, $matches);
                    if(!empty($matches[1]))
                    {
                        $ArchivosDeVersionEncontrados[]=$matches[1];
                    }
                }
            }
            closedir($handle);
            if(empty($ArchivosDeVersionEncontrados))
            {
                $this->error = "NO SE PUDO LEER EL ARCHIVO DE VERSIONES";
                $this->mensajeDeError = 'No se encontro ningun archivo de versión en el directorio '.$directorio;
                return false;                
            }
            
            rsort($ArchivosDeVersionEncontrados);
            
            $archivo = $directorio.'/VERSIONES_SIIS_'.$ArchivosDeVersionEncontrados[0];
            
            if(!file_exists($archivo))
            {
                $this->error = "NO SE PUDO LEER EL ARCHIVO DE VERSIONES";
                $this->mensajeDeError = 'El archivo : '.$archivo .' No existe.';
                return false;            
            }    
                    
            $file = fopen($archivo,"r");
            if(!$file)
            {
                $this->error = "NO SE PUDO LEER EL ARCHIVO DE VERSIONES";
                $this->mensajeDeError = 'Error al leer el archivo : '.$archivo;
                return false;            
            }
            
            $vectorLectura=array();
            
            while (!feof($file)) 
            {
                $linea = fgets($file, 4096);
                $vectorLinea = explode(";",$linea);
                
                if(sizeof($vectorLinea)>=4)
                {    
                    $moduloPath=trim($vectorLinea[0]);
                    $moduloTipo=trim($vectorLinea[1]);
                    $moduloVersion=trim($vectorLinea[2]);
                    $moduloFecha=trim($vectorLinea[3]);
                    $vectorModulo=explode('/',$moduloPath);
                    
                    switch($vectorModulo[0])
                    {
                        case 'hc_modules':
                        case 'app_modules':
                        case 'system_modules': 
                        $vectorLectura[$vectorModulo[0]][$vectorModulo[1]][$moduloTipo]['PATH']=$moduloPath;
                        $vectorLectura[$vectorModulo[0]][$vectorModulo[1]][$moduloTipo]['VERSION']=$moduloVersion;
                        $vectorLectura[$vectorModulo[0]][$vectorModulo[1]][$moduloTipo]['FECHA']=$moduloFecha;
                    }
                }
            }//fin del while
                                   
            fclose($file);
                print_r($vectorLectura);

        }
        else            
        {
            $this->error = "NO SE PUDO LEER EL ARCHIVO DE VERSIONES";
            $this->mensajeDeError = 'No se pudo leer el directorio '.$directorio;
            return false;        
        }
    
    }

    function CrearArchivoDeVersiones($directorio='')
    {
        if(empty($directorio))
        {
            global $_ROOT;
            $directorio=$_ROOT.'INSTALL';
        }
        else
        {
            $directorio = ereg_replace("[/]$",'',$directorio);
        }
        
        if(is_dir($directorio))
        {
            if(is_writable($directorio))
            {
                $file=$directorio."/VERSIONES_SIIS_".date("Ymd");
                $this->archivo = fopen($file,"w");
                if(!$this->archivo)
                {
                        $this->error = "NO SE PUDO CREAR EL ARCHIVO";
                        $this->mensajeDeError = 'fopen no pudo abrir/crear el archivo :'.$file;
                        return false;
                }
                $vectorHC = &$this->GetVector('hc_modules');
                foreach($vectorHC['hc_modules'] as $modulo=>$datos)
                {
                    $linea = $datos['SUBMODULO']['PATH'].';SUBMODULO;' .$datos['VERSION'] .";;\n";
                    $this->EscribirArchivo($linea);
                    
                    if(!empty($datos['PHP']))
                    {
                        $linea=$datos['PHP']['PATH'].';PHP;'.$datos['PHP']['VERSION'].';'.$datos['PHP']['FECHA'].";\n";
                        $this->EscribirArchivo($linea);
                    }
                    if(!empty($datos['HTML']))
                    {
                        $linea=$datos['HTML']['PATH'].';HTML;'.$datos['HTML']['VERSION'].';'.$datos['HTML']['FECHA'].";\n";
       
                        $this->EscribirArchivo($linea);
                    }                    
        
                }
                $this->CerrarArchivo();
                //Esto no es un error pero retorna el mensaje de exito.
                $this->error = "Archivo Generado Correctamente";
                $this->mensajeDeError = 'File : '.$file;                
                return true;            
            }
            else
            {
                $this->error = 'NO SE PUDO CREAR EL ARCHIVO';
                $this->mensajeDeError='El directorio '.$directorio . ' No tiene permiso de escritura.';
                return false;            
            }
            
        }
        else
        {
            $this->error = 'NO SE PUDO CREAR EL ARCHIVO';
            $this->mensajeDeError='No existe el directorio : '.$directorio;
            return false;
        }
    
    }//fin del metodo CrearArchivoDeVersiones()


    function EscribirArchivo($texto)
    {
        fwrite($this->archivo,$texto);
        return true;
    }//fin del metodo EscribirArchivo()


    function CerrarArchivo()
    {
        if(!fclose($this->archivo))
        {
            $this->error = "Error Rips";
            $this->mensajeDeError = 'No pude cerrar El archivo...';
            return false;
        }
        return true;
    }//fin del metodo CerrarArchivo()

    function GetVector($tipo)
    {
        if($tipo == 'hc_modules')
        {
            $handle=opendir('hc_modules'); 
            $vector_hc=array();
            while ($directorio = readdir($handle)) 
            {
                if ($directorio != "." && $directorio != ".." && $directorio != "CVS" && $directorio != "HTML") 
                {
                    if(is_dir('hc_modules/'.$directorio))
                    {
                        $file='hc_modules/'.$directorio.'/hc_'.$directorio.'.php';
                        if(file_exists($file) &&  is_readable($file) && is_file($file))
                        {
                            $archivo= @implode('', @file($file));
                            preg_match('/\$[I][d]:(.*?)\$/i', $archivo, $matches);
                            if($matches[1])
                            {
                                $datosID = explode(' ',trim($matches[1]));
                                $vector['hc_modules'][$directorio]['PHP']['ID'] = $matches[1];
                                $vector['hc_modules'][$directorio]['PHP']['VERSION'] = trim($datosID[1]);
                                $vector['hc_modules'][$directorio]['PHP']['FECHA'] = trim($datosID[2]);
                                $vector['hc_modules'][$directorio]['PHP']['HORA'] = trim($datosID[3]);
                                $vector['hc_modules'][$directorio]['PHP']['AUTOR'] = trim($datosID[4]);
                            }
                            $vector['hc_modules'][$directorio]['PHP']['PATH'] = $file;                       
                        }
                        
                        $file='hc_modules/'.$directorio.'/hc_'.$directorio.'_HTML.php';
                        if(file_exists($file) &&  is_readable($file) && is_file($file))
                        {
                            $archivo= @implode('', @file($file));
                            preg_match('/\$[I][d]:(.*?)\$/i', $archivo, $matches);
                            if($matches[1])
                            {
                                $datosID = explode(' ',trim($matches[1]));
                                $vector['hc_modules'][$directorio]['HTML']['ID'] = $matches[1];
                                $vector['hc_modules'][$directorio]['HTML']['VERSION'] = trim($datosID[1]);
                                $vector['hc_modules'][$directorio]['HTML']['FECHA'] = trim($datosID[2]);
                                $vector['hc_modules'][$directorio]['HTML']['HORA'] = trim($datosID[3]);
                                $vector['hc_modules'][$directorio]['HTML']['AUTOR'] = trim($datosID[4]);
                            }
                            $vector['hc_modules'][$directorio]['HTML']['PATH'] = $file;                                           
                        } 
                        
                        $file='hc_modules/'.$directorio.'/README';
                        if(file_exists($file) &&  is_readable($file) && is_file($file))
                        {
                            $archivo= @implode('', @file($file));
                            preg_match('/^VERSION\s*:(.*?)$/im', $archivo, $matches);
                            if($matches[1])
                            {                                    
                                $vector['hc_modules'][$directorio]['SUBMODULO']['VERSION'] = trim($matches[1]);                                        
                            }
                        } 
                        $vector['hc_modules'][$directorio]['SUBMODULO']['PATH'] = 'hc_modules/'.$directorio;                      
                    }
                }
            }
            closedir($handle);
        }    
        return $vector;
    }// fin del metodo GetVector
    
}//fin de la clase

?> 
