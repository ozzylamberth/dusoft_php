<?php
// versiones.class.php  27/01/2005
// ----------------------------------------------------------------------
// Copyright (C) 2004 IPSOFT SA
// www.ipsoft-sa.com
// ----------------------------------------------------------------------
// Autor: Alexander Giraldo
// Proposito del Archivo: Clase para consultar la version de modulos en
// la aplicacion.
// ----------------------------------------------------------------------


class versiones
{

    function versiones()
    {
        return true;
    }
    
    function GetKernelVersion()
    {
        global     $SIIS_VERSION;
        return $SIIS_VERSION;
    }
    
    
    
    function GetModulosInfo($contenedor='', $modulo='')
    {
        list($dbconn) = GetDBconn();
        global $ADODB_FETCH_MODE;    
        
        $query = "SELECT * FROM system_modulos WHERE modulo_tipo='app';";
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $resultado = $dbconn->Execute($query);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;    
        $system_modulos = $resultado->F etchRows();
            
        $Modulos_app=$this->ModulosContenedor('app');
        foreach($Modulos_app as $k=>$modulo)
        {
            $info=false;
            $file='app_modules/'.$modulo.'/app_'.$modulo.'_version.php';
            if(file_exists($file)){
                include_once($file);
                $clase='app_'.$modulo.'_version';
                if(class_exists($clase)){
                    $classInfo=new $clase;
                    if(method_exists($classInfo,'GetVersion')){
                        $info=$classInfo->GetVersion();
                    }
                    unset($classInfo);
                }
            }
            $modulosinfo['app'][$modulo]=$info;
            unset($info);
        }
        
        $Modulos_system=$this->ModulosContenedor('system');
        include_once('classes/modules/hc_classmodules.class.php');
        foreach($Modulos_system as $k=>$modulo)
        {
            $info=false;
            $file='system_modules/'.$modulo.'/system_'.$modulo.'_version.php';
            if(file_exists($file)){
                include_once($file);
                $clase='system_'.$modulo.'_version';
                if(class_exists($clase)){
                    $classInfo=new $clase;
                    if(method_exists($classInfo,'GetVersion')){
                        $info=$classInfo->GetVersion();
                    }
                    unset($classInfo);
                }
            }
            $modulosinfo['system'][$modulo]=$info;
            unset($info);
        }        
        
        $hc_system=$this->ModulosContenedor('hc');
        foreach($hc_system as $k=>$modulo)
        {
            $info=false;
            $file='hc_modules/'.$modulo;
            if(file_exists($file)){
                include_once($file);
                $clase=str_replace ( '.php', '', $modulo);            
                if(class_exists($clase)){
                    $classInfo=new $clase;
                    if(method_exists($classInfo,'GetVersion')){
                        $info=$classInfo->GetVersion();                    
                    }
                    unset($classInfo);
                }
            }
            $modulosinfo['hc'][$clase]=$info;
            unset($info);
        }            
        
        return $modulosinfo;
        
    }
    
    function ModulosContenedor($contenedor)
    {
        switch ($contenedor)
        {
            case 'app':
                  $handle=opendir('app_modules'); 
                  while ($file = readdir($handle)) {
                    if ($file != "." && $file != "..") {
                        if(is_dir('app_modules/'.$file)){
                            $modulo[]=$file;
                        }
                    }
                 }
                 closedir($handle);
                return $modulo;                 
            
            break;
            
            case 'system':
                  $handle=opendir('system_modules'); 
                  while ($file = readdir($handle)) {
                    if ($file != "." && $file != "..") {
                        if(is_dir('system_modules/'.$file)){
                            $modulo[]=$file;
                        }
                    }
                 }
                 closedir($handle);
                return $modulo;             
            break;
            
            case 'hc':
                  $handle=opendir('hc_modules'); 
                  while ($file = readdir($handle)) {
                    if ($file != "." && $file != "..") {
                        if(is_file('hc_modules/'.$file)){
                            $modulo[]=$file;
                        }
                    }
                 }
                 closedir($handle);
                return $modulo; 
            break;
            
            default:
            return false;
        
        }
    }

}//End class.

?>
