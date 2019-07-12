<?php
/**
* modules.class.php - Clase manejadora de modulos (app y system)
* @author Alexander Giraldo Salas <alexgiraldo@dhspro.com>
* @version 1.0
* @package SIIS
*/
// -------------------------------------------------------------

/**
* Clase manejadora de modulos (app y system)
*
* Esta clase se encarga de incluir e instanciar los archivos y clases necesarias
* para realizar la peticion de llamado a un metodo de alguno de los modulos de la
* aplicacion (modulos del tip app y system)
*/

class ManejadorDeModulos extends classModules
{
    var $contenedor;
    var $modulo;
    var $tipo;
    var $metodo;
    var $requestBackup= array();


    function ManejadorDeModulos($contenedor='', $modulo='', $tipo='', $metodo='', $argumentos=array())
    {

        // llamando al constructor del padre
        $this->classModules();
    
        if(empty($contenedor))
        {
            if(empty($_REQUEST['contenedor']))
            {
                $contenedor = 'app';
            }
            else
            {
                $contenedor = $_REQUEST['contenedor'];
            }
        }
    
        if(empty($modulo))
        {
            if(empty($_REQUEST['modulo']))
            {
                if($contenedor == 'app')
                {
                    list($dbconn) = GetDBconn();
                    
                    $sql="SELECT modulo,parametros FROM system_modulos_default 
                            WHERE 
                            ip_host='".GetIPAddress()."'
                            AND modulo_tipo='app'
                            AND activo='1'
                            AND usuario_id=".UserGetUID().";";        
     
                    $resultado = $dbconn->Execute($sql);
                
                    if ($dbconn->ErrorNo() != 0) {
                        $file=__FILE__;
                        $line=__LINE__;
                        die(MsgOut('SQL ERROR',$sql.$dbconn->ErrorMsg(),$file,$line));
                    }                    
                    if($resultado->EOF)
                    {
                        $resultado->Close();
                        $sql="SELECT modulo,parametros FROM system_modulos_default
                                WHERE 
                                ip_host=''
                                AND modulo_tipo='app'
                                AND activo='1'                                
                                AND usuario_id=".UserGetUID().";";    
                                
                        $resultado = $dbconn->Execute($sql);
                    
                        if ($dbconn->ErrorNo() != 0) {
                            $file=__FILE__;
                            $line=__LINE__;
                            die(MsgOut('SQL ERROR',$sql.$dbconn->ErrorMsg(),$file,$line));
                        }                                  
                        if($resultado->EOF)    
                        {
                            $modulo = GetVarConfigAplication('ModuloInicial');
                        }
                        else
                        {
                            list($modulo,$parametros)=$resultado->FetchRow();
                            
                            $arrayParametros=ExplodeArrayAssoc($parametros);
                            foreach($arrayParametros as $k => $v)
                            {
                                $_REQUEST[$k]=$v;
                            }
                            $resultado->Close(); 
                        }    
                    }
                    else
                    {
                        list($modulo,$parametros)=$resultado->FetchRow();
                        $arrayParametros=ExplodeArrayAssoc($parametros);

                        foreach($arrayParametros as $k => $v)
                        {
                            $_REQUEST[$k]=$v;
                        }                        
                        $resultado->Close(); 
                    }    
                }
                else
                {
                    die(MsgOut('ERROR AL LLAMAR EL METODO','El parametro Modulo es obligatorio'));
                }
            }
            else
            {
                $modulo = $_REQUEST['modulo'];
            }
        }
    
        if(empty($tipo))
        {
            if(empty($_REQUEST['tipo']))
            {
                $tipo = 'user';
            }
            else
            {
                $tipo = $_REQUEST['tipo'];
            }
        }
    
        if(empty($metodo))
        {
            if(empty($_REQUEST['metodo']))
            {
                $metodo = 'main';
            }else{
                $metodo = $_REQUEST['metodo'];
            }
        }
        
    
        //Simular la llega por $_REQUEST[], para llamadas internas a metodos desde otros modulos.
        foreach($argumentos as $k => $v)
        {
            if(isset($_REQUEST[$k]))
            {
                $requestBackup[$k]=$_REQUEST[$k];
            }
            $_REQUEST[$k]=$v;
        }
    
    
        $this->error = '';
        $this->mensajeDeError = '';
        $this->fileError = '';
        $this->lineError = '';
        $this->salida = '';
        $this->moduloError='';
        $this->envError='';        
    
        if(UserGetVar(UserGetUID(),'sw_admin')){
        if($contenedor!= 'system' || !(($modulo == 'Administrador' && $tipo == 'admin') || ($modulo == 'log' && $tipo == 'user'))){
    
                    $this->contenedor = 'system';
                    $this->modulo = 'Administrador';
                    $this->tipo = 'admin';
                    $this->metodo = 'main';
            }else{
                $this->contenedor = 'system';
                $this->modulo = $modulo;
                $this->tipo = $tipo;
                $this->metodo = $metodo;
            }
        }
        else
        {
                $this->contenedor = $contenedor;
                $this->modulo = $modulo;
                $this->tipo = $tipo;
                $this->metodo = $metodo;
        }
        $_ENV['MODULO']['contenedor']=$this->contenedor;
        $_ENV['MODULO']['modulo']=$this->modulo;
        $_ENV['MODULO']['tipo']=$this->tipo;
        $_ENV['MODULO']['metodo']=$this->metodo;
    
        return true;
    }

    function RestaurarRequest()
    {
        foreach($this->$requestBackup as $k => $v){
            $_REQUEST[$k]=$v;
        }
    }


    function Inicializar()
    {

        $fileName = GetThemePath() . "/module_theme.php";

        if(!IncludeFile($fileName)){
            $this->error = "No se pudo cargar el Modulo";
            $this->mensajeDeError = "El archivo '$fileName' no existe.";
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }

        if(empty($this->modulo)){
            $this->error = "No se pudo cargar el Modulo";
            $this->mensajeDeError = "No se ha llamado ningun modulo";
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;            
            return false;
        }

        if(!IncludeFile('includes/modules.inc.php',true)){
            $this->error = "No se pudo cargar el Modulo";
            $this->mensajeDeError = "El archivo 'includes/modules.inc.php' no existe.";
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }

        if(!ModuloGetEstado($this->contenedor, $this->modulo)){
            $this->error = "MODULO INACTIVO";
            $this->mensajeDeError = "El Modulo [" . $this->modulo . "] del tipo  [" . $this->contenedor . "] no esta Activado";
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }

         if(!IncludeFile('classes/validador/validador.class.php',true)){
            $this->error = "No se pudo cargar el Modulo";
            $this->mensajeDeError = "classes/validar/validador.class.php' no existe.";
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }

        if(!class_exists('Validador')){
            $this->error = "No se pudo cargar el Modulo";
            $this->mensajeDeError = "La clase 'Validador' no existe.";
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }


        $fileName  = $this->contenedor . "_modules/" . $this->modulo . "/" . $this->contenedor  . "_" . $this->modulo . "_" . $this->tipo . ".php";

        if(!IncludeFile($fileName)){
            $this->error = "No se pudo cargar el Modulo";
            $this->mensajeDeError = "El archivo '$fileName' no existe.";
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }

        $className = $this->contenedor  . "_" . $this->modulo  . "_" . $this->tipo  ;

        if(!class_exists($className)){
            $this->error = "No se pudo cargar el Modulo";
            $this->mensajeDeError = "La clase '$className' no existe.";
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }

        global $VISTA;
        $fileName = $this->contenedor . "_modules/" . $this->modulo . "/" . $this->tipo . "classes/" . $this->contenedor  . "_" . $this->modulo . "_" . $this->tipo . "classes_$VISTA.php";

        if(!IncludeFile($fileName)){
            $this->error = "No se pudo cargar el Modulo";
            $this->mensajeDeError = "El archivo de vistas '" . $fileName . "' no existe.";
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }

        $className = $this->contenedor  . "_" . $this->modulo  . "_" . $this->tipo . "classes_$VISTA";

        if(!class_exists($className)){
            $this->error = "No se pudo cargar el Modulo";
            $this->mensajeDeError = "La clase de la vista '$className' no existe.";
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }

        $MODULO = new $className;

        if(!method_exists($MODULO,$this->metodo)){
            $this->error = "No se pudo cargar el Modulo";
            $this->mensajeDeError = "El metodo '" . $this->metodo . "' no existe.";
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            unset($MODULO);
            return false;
        }

        if(call_user_method_array ($this->metodo, $MODULO, $this->argumentos)){
            $this->salida= $MODULO->GetSalida();
            $this->javaScripts= $MODULO->GetJavaScripts();
            if(GetVarConfigAplication('ActivarDepuracionDeModulos'))
            {
                $this->salida .= vistaDepuracionEnviroment($_REQUEST,get_object_vars($MODULO));
            }else{
                if(ModuloGetVar($this->contenedor, $this->modulo, "ActivarDepurador"))
                {
                    $this->salida .= vistaDepuracionEnviroment($_REQUEST,get_object_vars($MODULO));
                }
            }
            $this->RestaurarRequest();
            unset($MODULO);
            return true;
        }else{
            $this->error = $MODULO->Err();
            $this->mensajeDeError = $MODULO->ErrMsg();
            $this->fileError = $MODULO->ErrFile();
            $this->lineError = $MODULO->ErrLine();      
            $this->moduloError['Contenedor'] = $this->contenedor;
            $this->moduloError['Modulo'] = $this->modulo;
            $this->moduloError['Tipo'] = $this->tipo;
            $this->moduloError['Metodo'] = $this->metodo;
            $this->envError = $_REQUEST;
            $this->errorPropiedadesModulo = get_object_vars($MODULO);
            $this->RestaurarRequest();
            unset($MODULO);
            return false;
        }

    }
}//fin de la class

?>
