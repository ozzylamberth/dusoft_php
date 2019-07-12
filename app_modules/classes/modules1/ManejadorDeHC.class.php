<?php

class ManejadorDeHC extends classModules
{
    var $error='';
    var $mensajeDeError='';
    var $fileError='';
    var $lineError='';
    
    var $usuarioConsultante='';
    var $departamentoOrigenLlamado;
    
    var $ingreso;
    var $cuenta;
    var $evolucion;    
    var $departamento;
    var $estacion_id;
    var $paso;
    var $hc_modulo;
            
    var $hc_estructura=array();
    var $hc_submodulos=array();
    var $hc_secciones=array();
    var $datosHcModulo=array();
    var $mostrarSubmodulosOcultos=false;
    var $submodulos=array();
    var $numPasos=array();
    
    
    var $datosEvolucion=array();
    var $datosPaciente=array();
    var $datosProfesional=array();
    var $datosAdministrativos=array();
    var $datosResponsable=array();
    var $datosAdicionales=array();
    
   
    //Constructor
    function ManejadorDeHC()
    {     
        $this->classModules();       // Llamando al constructor del padre
        $this->error = '';           // Limpia Parametro
        $this->mensajeDeError = '';  // Limpia Parametro
        $this->salida = '';          // Limpia Parametro
        
        $this->usuarioConsultante = UserGetUID(); // Usuario que realiza la consulta
        return true;
    }
    
    // Metodo para obtener los datos de una evolucion
    function GetDatosEvolucion($evolucion)
    {
        if(empty($evolucion)) {
            $this->error = "Error en el manejador de HC";
            $this->mensajeDeError = "El argumento del Metodo : ManejadorDeHC.GetDatosEvolucion(evolucion) esta empty.";
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;         
            return false;
        }
        
        list($dbconn) = GetDBconn();
        
        $query = "SELECT *
                FROM hc_evoluciones
                WHERE evolucion_id = $evolucion";
                
        GLOBAL $ADODB_FETCH_MODE;
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        
        $result = $dbconn->Execute($query);
        
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error en el manejador de HC";
            $this->mensajeDeError = "SQL ERROR : ".$dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;         
            return false;
        }
        
        if ($result->EOF) {
            $this->error = "Error en el manejador de HC";
            $this->mensajeDeError = "El numero de evolucion no existe.";
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;         
            return false;
        }
                
        $vars = $result->FetchRow();
        $result->Close();
        return($vars);
    }    
    
    //Metodo para la creacion de una evolucion
    function NewEvolucion()
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();
            
        // verifico que el parametro ingreso exista
        if(empty($_REQUEST['ingreso']))
        {
            $this->error = "No se pudo crear la Historia Clinica";
            $this->mensajeDeError = "No se pudo obtener un numero de ingreso valido para crear la evolucion.";
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;                                
            return false;    
        }  

        //Validar el numero de ingreso
        $sql = "SELECT departamento FROM ingresos WHERE ingreso=" . $_REQUEST['ingreso'] . ";";
        $result = $dbconn->Execute($sql);
        
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error en la consulta";
            $this->mensajeDeError = $sql.$dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;                                
            return false;
        }
        
        if ($result->EOF) 
        {
            $this->error = "No se pudo crear la Historia Clinica";
            $this->mensajeDeError = "No se pudo obtener un numero de ingreso valido para crear la evolucion.";
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;                                
            return false;    
        }
            
        $ingreso = $_REQUEST['ingreso'];
        
        $departamento = $this->departamentoOrigenLlamado;
        
        //si no existe el parametro departamento coloco el departamento del ingreso
        if(empty($departamento))
        {
            list($departamento) = $result->FetchRow();
        }
 
        $result->Close();               

        //Verifico que exita el parametro de la plantilla para crear la nueva HC
        if(empty($_REQUEST['hc_modulo']))
        {
            $this->error = "No se pudo crear la Historia Clinica";
            $this->mensajeDeError = "No se ha indicado un Modulo de Historia Clinica, para la nueva evolucion.";
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;                                
            return false;
        }
                
        //Verifico la existencia y el estado de la plantilla HC
        if(!ModuloGetEstado('hc',$_REQUEST['hc_modulo']))
        {
            $this->error = "No se pudo crear la Historia Clinica";
            $this->mensajeDeError = "El modulo de Historia Clinica [" . $_REQUEST['hc_modulo'] . "] no esta activado o no existe";
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;                               
            return false;
        }
        
        $hc_modulo = $_REQUEST['hc_modulo'];
          
        //Buscar si hay una cuenta activa para el ingreso actual para referenciar la HC
        $sql = "SELECT a.numerodecuenta FROM cuentas as a, ingresos as b
                WHERE b.ingreso=".$ingreso."
                AND a.ingreso=b.ingreso
                AND a.estado='1';";

        $resultado = $dbconn->Execute($sql);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error en la consulta";
            $this->mensajeDeError = $sql.$dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;                                
            return false;
        }

        if(!$resultado->EOF)
        {
            list($cuenta)=$resultado->FetchRow();
            $resultado->Close();
        }
        else
        {
            $resultado->Close();
            //Si no hay cuentas activas busco la ultima cuenta del ingreso para referenciar la HC
            $sql="SELECT a.numerodecuenta FROM cuentas as a, ingresos as b
                    WHERE b.ingreso=".$ingreso."
                    AND a.ingreso=b.ingreso;";
                    
            $resultado = $dbconn->Execute($sql);
            
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error en la consulta";
                $this->mensajeDeError = $sql.$dbconn->ErrorMsg();
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;                                
                return false;
            }                    
            if(!$resultado->EOF)
            {
                list($cuenta)=$resultado->FetchRow();
            }
            else
            {        
                $cuenta=0;
            }
            $resultado->Close();
        }
                               
        //VERIFICAR SI EXISTE UNA EVOLUCION ABIERTA DEL MISMO PROFESIONAL, SI EXISTE RETORNA LA INFORMACION DE ESTA
        $sql ="select evolucion_id from hc_evoluciones where estado=1 and ingreso=$ingreso and hc_modulo='$hc_modulo' and departamento='$departamento' and usuario_id=".$this->usuarioConsultante." order by evolucion_id desc;";
        
        $result = $dbconn->Execute($sql);
        
        if($dbconn->ErrorNo() != 0) 
        {
            $this->error = "Error en el manejador de HC";
            $this->mensajeDeError = "SQL ERROR : ".$dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;         
            return false;
        }
    
        if (!$result->EOF) {
            list($EvolucionActiva)=$result->FetchRow();
            $this->datosEvolucion = $this->GetDatosEvolucion($EvolucionActiva);
            return true;
        }
        
        //SI EL PROFESIONAL NO TENIA UNA EVOLUCION ACTIVA CREA UNA NUEVA EVOLUCION
        $sql = "SELECT nextval('hc_evoluciones_seq');";
        
        $result = $dbconn->Execute($sql);
        
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error en el manejador de HC";
            $this->mensajeDeError = "SQL ERROR : ".$dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;             
            return false;
        }
        
        if ($result->EOF) {
            $this->error = "Error en el manejador de HC";
            $this->mensajeDeError = "Llamado a la secuencia hc_evoluciones_seq no retorno valor.";
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;             
            return false;
        }
        
        list($num_evolucion) = $result->FetchRow();
        
        //Si el parametro cuenta existe
        if(!empty($cuenta))
        {
            $insertCuentaCampo = ", numerodecuenta";
            $insertcuentaValue = ", $cuenta";
        }
        
        $sql = "INSERT INTO hc_evoluciones (evolucion_id, ingreso, fecha, usuario_id, departamento, estado, hc_modulo $insertCuentaCampo)
                VALUES ($num_evolucion, $ingreso, now(), ".$this->usuarioConsultante.", '$departamento', '1', '$hc_modulo' $insertcuentaValue);";
        
        $dbconn->Execute($sql);
        
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error en el manejador de HC";
            $this->mensajeDeError = "SQL ERROR : ".$dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;             
            return false;
        }
        
        $this->datosEvolucion = $this->GetDatosEvolucion($num_evolucion);
        return true;
        
    } //fin del metodo NewEvolucion()
    
    
    // Incluye el entorno necesario para mostrar la HC
    function Enviroment()
    {      
        $fileName = GetThemePath() . "/module_theme.php";

        if(!IncludeFile($fileName)){
            $this->error = "No se Pudo Cargar el Modulo";
            $this->mensajeDeError = "El archivo '$fileName' no existe.";
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;            
            return false;
        }        

        if(!IncludeFile('classes/validador/validador.class.php',true)){
                $this->error = "No se Pudo Cargar el Modulo";
                $this->mensajeDeError = "classes/validar/validador.class.php' no existe.";
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;                 
                return false;
        }

        if(!IncludeLib('modules')){
            $this->error = "No se Pudo Cargar el Modulo";
            $this->mensajeDeError = "No se pudo cargar la libreria de modulos";
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;             
            return false;
        }

        if(!IncludeLib('datospaciente')){
            $this->error = "No se Pudo Cargar el Modulo";
            $this->mensajeDeError = "No se pudo cargar la libreria de datos de pacientes.";
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;            
            return false;
        }

        if(!IncludeLib('historia_clinica')){
            $this->error = "No se Pudo Cargar el Modulo";
            $this->mensajeDeError = "No se pudo cargar la libreria de datos de Historia Clinica";
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;            
            return false;
        }

        if(!IncludeFile('classes/modules/hc_classmodules.class.php',true)){
            $this->error = "No se Pudo Cargar el Modulo";
            $this->mensajeDeError = "El archivo 'includes/historia_clinica.inc.php' no existe.";
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;            
            return false;
        }

       
        // VALIDO INFORMACION LLEGADA POR $_REQUEST['HC_DATOS_CONTROL']
        
        // reviso si se paso el dato del departamento donde se esta creando la evolucion.    
        if(!empty($_REQUEST['HC_DATOS_CONTROL']['DEPARTAMENTO']))
        {
            $this->departamentoOrigenLlamado = $_REQUEST['HC_DATOS_CONTROL']['DEPARTAMENTO'];        
        }     
                
        if(!empty($_REQUEST['HC_DATOS_CONTROL']['ESTACION']))
        { 
            list($dbconn) = GetDBconn();
            $sql="SELECT departamento FROM estaciones_enfermeria WHERE estacion_id='".$_REQUEST['HC_DATOS_CONTROL']['ESTACION']."';";
            $result = $dbconn->Execute($sql);
            if($dbconn->ErrorNo() != 0) 
            {
                $this->error = "Error en la consulta";
                $this->mensajeDeError = $sql.$dbconn->ErrorMsg();
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;                                
                return false;
            }    
                    
            if (!$result->EOF) 
            {
                list($this->departamentoOrigenLlamado) = $result->FetchRow();
                $this->estacion_id = $_REQUEST['HC_DATOS_CONTROL']['ESTACION'];
            }
            
            $result->Close();            
        }  
        
        return true;      
    
    } //fin del metodo Enviroment()
    
    
    // Metodo utilizado por ReturnModulo para mostrar la HC en modo Ingreso de
    function Inicializar()
    {//unset($_SESSION['HC_EVOLUCION']);
        //Cargar el entorno de HC
        if(!($this->Enviroment())) return false;

        //Construir o Recuperar el Vector de SESSION de la HC
        if(!($this->ConstruirDatosHC())) return false;    
          
        //Ubicar el paso en el que esta la HC
        if(!($this->GetPasoHC())) return false;
             
        //Revisar si hay un cambio de estado en el listado "Mostrar Ocultos"
        if(array_key_exists('HC_MOSTRAR_SUBMODULOS_OCULTOS',$_REQUEST))
        {
            $_SESSION['HC_EVOLUCION'][$this->usuarioConsultante][$this->datosEvolucion['evolucion_id']]['HC_MOSTRAR_SUBMODULOS_OCULTOS'] = $_REQUEST['HC_MOSTRAR_SUBMODULOS_OCULTOS'];
        }
        
        //Establecer el estado de mostrar modulos ocultos
        $this->mostrarSubmodulosOcultos = $_SESSION['HC_EVOLUCION'][$this->usuarioConsultante][$this->datosEvolucion['evolucion_id']]['HC_MOSTRAR_SUBMODULOS_OCULTOS'];
        
        if($this->paso >= 1 && $this->paso <= ($this->numPasos[0]+$this->numPasos[1]))
        {
            $this->salida  = $this->IncludeCabeceraHC();
            $this->salida .= $this->IncludePasoHC();
            $this->salida .= $this->IncludePieDePaginaHC();
            return true;        
        }
        else
        {
            switch($this->paso)
            {
                case -1:
                      $this->salida  = $this->IncludeCabeceraHC();
                    $this->salida .= $this->ListadoInicioHC();
                    $this->salida .= $this->IncludePieDePaginaHC();              
                break;
            
            }        
        }
        
        //print_r($_SESSION);
        return true;      
    }    
    
    
    // Metodo para generar la hc
    function ConstruirDatosHC()
    {
        //GENERAR EL VECTOR CON LOS DATOS DE LA EVOLUCION : $this->datosEvolucion
        //HAY 3 CASOS PARA GENERAR LA EVOLUCION:
        //CASO 1 : Los datos de la evolucion estan en $_SESSION
        //CASO 2 : La evolucion existe pero no esta en $_SESSION (se sube)
        //CASO 3 : La evolucion  no existe se crea una nueva y se sube a $_SESSION
        //------------------------------------------------------------------------

        if(!empty($_REQUEST['evolucion']) )
        {   
            // CASO 1/3 : Los datos de la evolucion estan en $_SESSION
            if(!empty($_SESSION['HC_EVOLUCION'][$this->usuarioConsultante][$_REQUEST['evolucion']]))
            {
                $this->datosEvolucion = $_SESSION['HC_EVOLUCION'][$this->usuarioConsultante][$_REQUEST['evolucion']]['datosEvolucion'];        
            }
            else // CASO 2/3 : La evolucion existe pero no esta en $_SESSION 
            {
                $this->datosEvolucion = $this->GetDatosEvolucion($_REQUEST['evolucion']);
            }
            
            if(empty($this->datosEvolucion))
            {
                $this->error = "No se pudo cargar la Historia Clinica";
                $this->mensajeDeError = "No se pudieron obtener los datos de la evolucion";
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;                
                return false;
            }    
            
            //ojo que aqui puede abrirla en estado de consulta!!!!!!!!!!
            if($this->datosEvolucion['usuario_id'] != $this->usuarioConsultante)
            {
                $this->error = "No se pudo cargar la Historia Clinica";
                $this->mensajeDeError = "El usuario que intenta editar la HC no es el mismo que la creo.";
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;                
                return false;
            }    
                                    
            //Verifica si la plantilla hc esta activa.
            if(!ModuloGetEstado('hc', $this->datosEvolucion['hc_modulo']))
            {
                $this->error = "MODULO INACTIVO";
                $this->mensajeDeError = "El modulo de Historia Clinica [" . $this->datosEvolucion['hc_modulo'] . "] no esta activado";
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;                                
                return false;
            }   
        }
        else // CASO 3/3 : La evolucion  no existe se crea y se sube a $_SESSION
        {
            if(!($this->NewEvolucion())) return false;
        
            if(empty($this->datosEvolucion))
            {
                $this->error = "No se pudo cargar la Historia Clinica";
                $this->mensajeDeError = "No se pudieron obtener los datos de la evolucion";
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;                
                return false;
            }                
        }
            
        //UNA VEZ GENERADA LA EVOLUCION SE CONTINUA CON LA ESTRUCTURA DEL HC_MODULO
        //PARA LOS CASOS EN QUE NO ESTE EN SESSION
        
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();    
            
        if(empty($_SESSION['HC_EVOLUCION'][$this->usuarioConsultante][$this->datosEvolucion['evolucion_id']]))
        {    
            // Generacion del vector de datosResponsable
            if(!empty($this->datosEvolucion['numerodecuenta']))
            {
                $sql="SELECT a.plan_id, a.tipo_afiliado_id, a.rango, a.semanas_cotizadas, b.plan_descripcion, b.tipo_tercero_id, b.tercero_id, b.num_contrato, c.nombre_tercero 
                        FROM cuentas as a, planes as b, terceros as c                  
                        WHERE
                        a.plan_id = b.plan_id
                        AND b.tercero_id = c.tercero_id
                        AND b.tercero_id = c.tercero_id
                        AND a.numerodecuenta = ".$this->datosEvolucion['numerodecuenta'].";";
                
                $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;        
                $resultado = $dbconn->Execute($sql);
                $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error en la consulta";
                    $this->mensajeDeError = $sql.$dbconn->ErrorMsg();
                    $this->fileError = __FILE__;
                    $this->lineError = __LINE__;                                
                    return false;
                }                    
                if(!$resultado->EOF)
                {
                    $this->datosResponsable = $resultado->FetchRow();
                }            
                $resultado->Close();        
            }            
        
            //  Generacion del vector de datosAdministrativos        
            $sql="SELECT empresa_id, centro_utilidad, unidad_funcional, departamento, servicio FROM departamentos WHERE departamento='".$this->datosEvolucion['departamento']."'";
            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $result = $dbconn->Execute($sql);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;    
                
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "No se Pudo Cargar la Historia Clinica";
                $this->mensajeDeError = "$sql".$dbconn->ErrorMsg();
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;           
                return false;
            }
            
            if($result->EOF){
                $this->error = "No se Pudo Cargar la Historia Clinica";
                $this->mensajeDeError = "No se pudo obtener los datos administrativos ";
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;           
                return false;
            }
            
            $this->datosAdministrativos = $result->FetchRow();
            $result->Close();      
              
            // Generacion del vector de datosPaciente
            
            $this->datosPaciente = GetDatosPaciente('','',$this->datosEvolucion['ingreso'],$this->datosEvolucion['evolucion_id']);
            if(empty($this->datosPaciente))
            {
                $this->error = "No se Pudo Cargar la Historia Clinica";
                $this->mensajeDeError = "No se pudo obtener los datos del paciente";
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;           
                return false;            
            }
            
             //PARAMETROS DEL HC_MODULO
            $query = "SELECT *
                        FROM system_hc_modulos
                        WHERE hc_modulo='".$this->datosEvolucion['hc_modulo']."'";
    
            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $result = $dbconn->Execute($query);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
    
            if($dbconn->ErrorNo() != 0) {
                $this->error = "Error al consultar pasos de la HC";
                $this->mensajeDeError = "SQL : $query ". $dbconn->ErrorMsg();            
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;         
                return false;
            }
    
            if (!$result->EOF) {
                $this->datosHcModulo = $result->FetchRow();
                $this->datosHcModulo['parametros']=ExplodeArrayAssoc($this->datosHcModulo['parametros']);
            }    
            $result->Close();       
            
            //Generar el vector de DatosProfesional
            $this->datosProfesional=GetDatosProfesional($this->datosEvolucion['usuario_id']);

            //Calcular la edad con respecto a la fecha de la evolucion.
            $this->datosPaciente['edad_paciente'] = CalcularEdad($this->datosPaciente['fecha_nacimiento'],$this->datosEvolucion['fecha']);
           
            if(empty($this->datosPaciente['edad_paciente']['edad_en_dias'])){
                $this->datosPaciente['edad_paciente']['edad_en_dias']= 0;
            }
            
            //Generacion de la estructura de la HC
            $query = "SELECT a.paso, a.secuencia, a.submodulo, a.titulo_mostrar, b.descripcion as titulo_generico, a.hc_seccion_id, c.descripcion as hc_seccion_titulo, a.sw_mostrar, a.parametros 
                        FROM historias_clinicas_templates a, system_hc_submodulos b,  historia_clinica_secciones as c
                        WHERE a.hc_modulo = '" . $this->datosEvolucion['hc_modulo'] . "' 
                        AND (a.sexo_id='" . $this->datosPaciente['sexo_id'] . "' OR a.sexo_id IS NULL) 
                        AND (a.edad_max>=" . $this->datosPaciente['edad_paciente']['edad_en_dias'] . " OR a.edad_max IS NULL) 
                        AND (a.edad_min<=" . $this->datosPaciente['edad_paciente']['edad_en_dias'] . " OR a.edad_min IS NULL) 
                        AND b.submodulo=a.submodulo 
                        AND b.sw_submodulo_sistema='0' 
                        AND c.hc_seccion_id = a.hc_seccion_id 
                        ORDER BY a.sw_mostrar DESC ,a.hc_seccion_id,a.paso,a.secuencia ASC;";
    
            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $result = $dbconn->Execute($query);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
    
            if($dbconn->ErrorNo() != 0) {
                $this->error = "Error al consultar pasos de la HC";
                $this->mensajeDeError = "SQL : $query ". $dbconn->ErrorMsg();            
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;         
                return false;
            }
    
            if ($result->EOF) {
                $this->error = "Modulo de Historia Clinica Vacio";
                $this->mensajeDeError = "No hay submodulos para este modulo de historia Clinica [$this->hc_modulo]";
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;             
                return false;
            }
            

            while ($result_paso = $result->FetchRow()) 
            {
                $submodulos[$result_paso['sw_mostrar']][$result_paso['hc_seccion_id']][$result_paso['paso']][$result_paso['secuencia']] = $result_paso;
            }
            $result->Close();

            $I=1;
            $NumPasosVisibles =0;
            $NumPasosOcultos  =0;
            foreach($submodulos as $Key_Mostrar=>$NivelSeccion)
            {
                foreach($NivelSeccion as $Key_Seccion=>$NivelPaso)
                {
                    foreach($NivelPaso as $Key_Paso=>$NivelSecuencia)
                    {
                        if($Key_Mostrar)
                        {
                            $NumPasosVisibles++;
                        }
                        else
                        {
                            $NumPasosOcultos++;
                        }
                        foreach($NivelSecuencia as $Key_Secuencia=>$DatosSubmodulo)
                        {                          
                            if(!empty($DatosSubmodulo['titulo_mostrar']))
                            {
                                $titulo = $DatosSubmodulo['titulo_mostrar'];
                            }
                            else
                            {
                                $titulo = $DatosSubmodulo['titulo_generico'];
                            }

                            //MANEJO DEL PARAMETRO TitulosUCASE : Convertir a mayusculas todos los titulos de los submodulos
                            if(array_key_exists('TitulosUCASE',$this->datosHcModulo['parametros']))
                            {
                                $titulo=strtoupper($titulo);
                            }                            
                            $DatosSubmodulo['TITULO']=$titulo;
                            $this->hc_submodulos[$I][]=$DatosSubmodulo;
                            $this->hc_estructura[$DatosSubmodulo['sw_mostrar']][$DatosSubmodulo['hc_seccion_id']][$DatosSubmodulo['submodulo']]['TITULO']=$titulo;    
                            $this->hc_estructura[$DatosSubmodulo['sw_mostrar']][$DatosSubmodulo['hc_seccion_id']][$DatosSubmodulo['submodulo']]['PASO']=$I;                            
                            if(empty($this->hc_secciones[$DatosSubmodulo['hc_seccion_id']]))
                            {
                                if(array_key_exists('TitulosUCASE',$this->datosHcModulo['parametros']))
                                {
                                    $this->hc_secciones[$DatosSubmodulo['hc_seccion_id']]=strtoupper($DatosSubmodulo['hc_seccion_titulo']);
                                }
                                else
                                {
                                    $this->hc_secciones[$DatosSubmodulo['hc_seccion_id']]=$DatosSubmodulo['hc_seccion_titulo'];
                                }                            
                            }
                        }            
                        $I++;
                    }                
                }
                
            }
                    
            $this->numPasos[0]=$NumPasosOcultos;
            $this->numPasos[1]=$NumPasosVisibles;
            
            //SE CREA EL VECTOR EN SESSION
            $_SESSION['HC_EVOLUCION'][$this->usuarioConsultante][$this->datosEvolucion['evolucion_id']]['datosEvolucion'] = &$this->datosEvolucion;
            $_SESSION['HC_EVOLUCION'][$this->usuarioConsultante][$this->datosEvolucion['evolucion_id']]['datosResponsable'] = &$this->datosResponsable;
            $_SESSION['HC_EVOLUCION'][$this->usuarioConsultante][$this->datosEvolucion['evolucion_id']]['datosAdministrativos'] = &$this->datosAdministrativos;
            $_SESSION['HC_EVOLUCION'][$this->usuarioConsultante][$this->datosEvolucion['evolucion_id']]['datosPaciente'] = &$this->datosPaciente;
            $_SESSION['HC_EVOLUCION'][$this->usuarioConsultante][$this->datosEvolucion['evolucion_id']]['datosProfesional'] = &$this->datosProfesional;
            //$_SESSION['HC_EVOLUCION'][$this->usuarioConsultante][$this->datosEvolucion['evolucion_id']]['datosPaciente']['edad_paciente'] = &$this->edad_paciente;
            $_SESSION['HC_EVOLUCION'][$this->usuarioConsultante][$this->datosEvolucion['evolucion_id']]['hc_submodulos'] = &$this->hc_submodulos;
            $_SESSION['HC_EVOLUCION'][$this->usuarioConsultante][$this->datosEvolucion['evolucion_id']]['hc_estructura'] = &$this->hc_estructura; 
            $_SESSION['HC_EVOLUCION'][$this->usuarioConsultante][$this->datosEvolucion['evolucion_id']]['hc_secciones'] = &$this->hc_secciones;
            $_SESSION['HC_EVOLUCION'][$this->usuarioConsultante][$this->datosEvolucion['evolucion_id']]['datosHcModulo'] = &$this->datosHcModulo;
            $_SESSION['HC_EVOLUCION'][$this->usuarioConsultante][$this->datosEvolucion['evolucion_id']]['numPasos'] = &$this->numPasos;
        }
        else //Si existe el vector en session se aterriza a los vectores locales
        { 
            $this->datosResponsable     = &$_SESSION['HC_EVOLUCION'][$this->usuarioConsultante][$this->datosEvolucion['evolucion_id']]['datosResponsable'];
            $this->datosAdministrativos = &$_SESSION['HC_EVOLUCION'][$this->usuarioConsultante][$this->datosEvolucion['evolucion_id']]['datosAdministrativos'];
            $this->datosPaciente        = &$_SESSION['HC_EVOLUCION'][$this->usuarioConsultante][$this->datosEvolucion['evolucion_id']]['datosPaciente'];
            $this->datosProfesional     = &$_SESSION['HC_EVOLUCION'][$this->usuarioConsultante][$this->datosEvolucion['evolucion_id']]['datosProfesional'];
            $this->hc_submodulos        = &$_SESSION['HC_EVOLUCION'][$this->usuarioConsultante][$this->datosEvolucion['evolucion_id']]['hc_submodulos'];   
            $this->hc_estructura        = &$_SESSION['HC_EVOLUCION'][$this->usuarioConsultante][$this->datosEvolucion['evolucion_id']]['hc_estructura']; 
            $this->hc_secciones         = &$_SESSION['HC_EVOLUCION'][$this->usuarioConsultante][$this->datosEvolucion['evolucion_id']]['hc_secciones']; 
            $this->datosHcModulo        = &$_SESSION['HC_EVOLUCION'][$this->usuarioConsultante][$this->datosEvolucion['evolucion_id']]['datosHcModulo']; 
            $this->numPasos             = &$_SESSION['HC_EVOLUCION'][$this->usuarioConsultante][$this->datosEvolucion['evolucion_id']]['numPasos']; 
        }            
        
        return true;    
    } //fin del metodo ConstruirDatosHC()
        
    
    function GetPasoHC()
    {
        //Se ubica en el paso correspondiente y si no llego paso en el listado de submodulos PASO -1.
        if(!empty($_REQUEST['paso']))
        {
            if(!empty($this->hc_submodulos[$_REQUEST['paso']]))
            {
                $this->paso = $_REQUEST['paso'];
                return true;
            }
        }
        
        $this->paso = -1;
        return true;
    }
    
    function Cooooooooooo()
    {        
        
        
        //Si se creo la evolucion busco si hay informacion del responsable

        
        $tipo_profesional=GetTipoProfesional(UserGetUID());

        
        //validar que el profesional dueño de la evolucion sea el mismo profesional conectado
        //$_SESSION['HC_EVOLUCION'][$this->evolucion]['datosProfesional']['tipo_profesional'] = $tipo_profesional;

        //SUBIR VECTORES DE DATOS PARA LOS SUBMODULOS A LA SESSION
        //-------------------------------------------------------------------------------------------------------------------
    
        // HC_DATOS_CONTROL - Información de la Lista de Trabajo que hace el llamado a la HC
        if(!empty($_REQUEST['HC_DATOS_CONTROL'])){
            $_SESSION['HC_DATOS_CONTROL'][$this->datosEvolucion['evolucion_id']]=$_REQUEST['HC_DATOS_CONTROL'];
        }
        
        //-------------------------------------------------------------------------------------------------------------------
                 
        // RESET DE LA CONDUCTA DE CIERRE (PARA CAMBIAR LA SELECCION DE LA CONDUCTA)
        if($_REQUEST['RESET_CONDUCTA_CIERRE_HC']=='SI')
        {
            unset($_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']]['CONDUCTA']);
        }      
        

        
        

        //Activo el javascript de DatosPaciente para las ventanas emergentes      
        $this->SetJavaScripts('DatosPaciente');
        
         



        
        foreach($_REQUEST as $v=>$s)
        {
            if(substr_count ($v,'accion')==1)
            {
                $k=$s;
                break;
            }
        }

        if(empty($_REQUEST['mostrar']) and $this->paso > 0 and empty($k))
        {
            while(sizeof($datosModulos)>$this->paso and $this->paso>0)
            {
                $t=current($this->hc_submodulos[$datosModulos[$this->paso]]);
                $s=current($t);
                if($s['sw_mostrar']!=1)
                {
                    if(empty($_REQUEST['devolver']))
                    {
                        $this->paso=$this->paso+1;
                    }
                    else
                    {
                        $this->paso=$this->paso-1;
                    }
                }
                if($s['sw_mostrar']==1)
                {
                    break;
                }
            }
        }

        if(sizeof($datosModulos) <= $this->paso)
        {
            $this->paso = "cerrar";
        }

        if($this->paso == -1)
        {
            if($this->datosEvolucion['estado'] == 1)
            {
                $this->paso = "inicio";
            }
            else
            {
                $this->paso = "pasoresumen";
            }
        }

        switch($this->paso)
        {
            case "inicio":
            {
                unset($_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']]['CONDUCTA']);
                $salida_submodulo=ListadoInicioHC(&$this->hc_submodulos,$this->datosEvolucion['evolucion_id'],$this->hc_modulo);
                break;
            }

            case "pasoresumen":
            {
                $dato=$this->HistoriaClinicaResumeEvolucion($this->evolucion);
                if(!empty($dato))
                {
                        $salida_submodulo=$dato;
                }
                break;
            }

            case "cerrar":
            {
                $this->paso=sizeof($datosModulos);
                $sql="select b.historia_clinica_tipo_cierre_id, b.titulo_mostrar, b.sw_pedir_submodulo_obligatorios from historias_clinicas_cierres as a, historias_clinicas_tipos_cierres as b where a.hc_modulo='".$this->hc_modulo."' and a.historia_clinica_tipo_cierre_id=b.historia_clinica_tipo_cierre_id order by a.indice_orden;";
                $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                $result = $dbconn->Execute($sql);
                $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        
                if($dbconn->ErrorNo() != 0) 
                {
                    $this->error = "Error al consultar el tipo de cierre de la HC";
                    $this->mensajeDeError = "SQL : $query ". $dbconn->ErrorMsg();            
                    $this->fileError = __FILE__;
                    $this->lineError = __LINE__;                 
                    return false;
                }
                
                if ($result->EOF) 
                { 
                    //cuando esta vacio
                    $salida_submodulo=$this->ExigirSubmodulosObligatorios();
                    if($salida_submodulo=="OK")
                    {
                        $salida_submodulo=$this->CerrarHistoria();
                    }
                }
                else
                {
                    while(!$result->EOF)
                    {
                      $datos[$result->fields[historia_clinica_tipo_cierre_id]]=$result->GetRowAssoc(false);
                    $result->MoveNext();
                    }
                    $result->close();
                    $salida_submodulo=$this->ProcesoCerrarHistoria(&$datos);
                           
//                     $tipoCierre = $result->FetchRow();
//                     $result->close();
//                     $salida_submodulo=$this->ProcesoCerrarHistoria(&$tipoCierre);
                }
                
                if($salida_submodulo === 'HC_CERRAR_OK')
                {
                    includelib('malla_validadora');
                    $this->salida = VolverListado($_SESSION['HISTORIACLINICA']['RETORNO']['contenedor'], $_SESSION['HISTORIACLINICA']['RETORNO']['modulo'], $_SESSION['HISTORIACLINICA']['RETORNO']['tipo'], $_SESSION['HISTORIACLINICA']['RETORNO']['metodo'],$this->datosEvolucion['ingreso'],$this->datosEvolucion['evolucion_id']);
                    unset($_SESSION['HC_DATOS_ADICIONALES'][$this->datosEvolucion['evolucion_id']]);
                    unset($_SESSION['HC_DATOS_CONTROL'][$this->datosEvolucion['evolucion_id']]);
                    return true;
                }
                
                break;
            }
            case "historia":
            {
                $salida_submodulo=$this->HistoriaClinicaCompleta();
                break;
            }

            case "pasohc":
            {
                $dato=$this->HistoriaClinicaResumeEvolucion($_REQUEST['evolucion_consulta']);
                if(!empty($dato))
                {
                    $salida_submodulo=$dato;
                }
                break;
            }
            case "pasone":
            {
                if (!IncludeFile("classes/notas_enfermeria/notas_enfermeria.class.php"))
                {
                    echo "<br>ERROR AL INCLUIR LA CLASE DE \"notas_enfermeria\"<br>";
                }

                $fecha="";
                if (!empty($_REQUEST['select_fecha'])){
                        $fecha=$_REQUEST['select_fecha'];
                }
                $url=ModuloHCGetURL($this->datosEvolucion['evolucion_id'],'pasone',0,$this->hc_modulo,$this->hc_modulo,array('ingreso_consulta'=>$_REQUEST['ingreso_consulta']));
                $notas_e=new notas_enfermeria($_REQUEST['ingreso_consulta'],$fecha,$url);
                $salida_submodulo=FormaDiasNE(&$notas_e);
                break;
            }
            case "apoyod":
            {
                $dato=$this->HistoriaClinicaResumeApoyod($this->datosPaciente['tipo_id_paciente'],$this->datosPaciente['paciente_id'],$this->datosEvolucion['evolucion_id'],$this->hc_modulo);
                if(!empty($dato))
                {
                    $salida_submodulo=$dato;
                }
                break;
            }
            default:
            {
                foreach($this->hc_submodulos[$datosModulos[$this->paso]] as $k=>$v)
                {
                    foreach($v as $t=>$r)
                    {
                        $submodulo_obj=IncluirSubModuloHC($r['submodulo']);
                        if(!is_object($submodulo_obj)){
                            $this->error = "No se Pudo cargar el submodulo";
                            $this->mensajeDeError = $submodulo_obj;
                            $this->fileError = __FILE__;
                            $this->lineError = __LINE__;                             
                            return false;
                        }
                        
                        //paracompatibilidad versiones anteriores
                        $this->datosAdicionales['sw_siquiatria'] = $r['sw_siquiatria'];        
                        //-----------------------------------
                        $prefijo='frm_'.$r['submodulo'];
                        $submodulo = $r['submodulo'];
                        
                        $submodulo_obj->InicializarSubmodulo($this->datosEvolucion,$this->datosAdministrativos,$this->datosPaciente,$this->datosProfesional,$this->datosResponsable,$this->datosAdicionales,$this->paso,$prefijo,$submodulo,$this->hc_modulo);
                        //$submodulo_obj->InitSubmodulo($this->datosEvolucion,$this->paso,'frm_'.$r['submodulo'],$this->datosPaciente,'',$_SESSION['HC']['estacion'],$_SESSION['HC']['bodega'],$r['sw_siquiatria'],$this->hc_modulo,$_SESSION['HC']['especialidad'],$this->QXcumplimiento,$r['submodulo']);
                        $salida_submodulo.=$submodulo_obj->GetSalida();
                        foreach($submodulo_obj->GetJavaScriptsSubmodulos() as $v=>$k)
                        {
                            $this->SetJavaScripts($v);
                        }
                    }
                }
            }
        }

        if($this->paso=="inicio" or $this->paso=="pasone" or $this->paso=="pasoresumen")
        {
            $this->paso=-1;
        }

        if($this->paso=="historia" or $this->paso=="pasohc")
        {
            $this->paso=-2;
        }

        if($this->paso=="apoyod")
        {
            $this->paso=-3;
        }

        $cabecera=IncludeCabeceraHC(&$this->datosPaciente,&$this->datosEvolucion,$this->paso,$this->hc_modulo,sizeof($datosModulos),$this->datosResponsable);

        if(empty($cabecera))
        {
            $this->error = "Modulo de Historia Clinica";
            $this->mensajeDeError = "Los datos de la cabecera estan vacios";
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;             
            return false;
        }

        $this->salida  = $cabecera;
        $this->salida .= IncludePasosDeHC(&$this->datosEvolucion ,$this->paso ,$this->hc_modulo ,sizeof($datosModulos));
        $this->salida .= $salida_submodulo;
        $this->salida .= IncludePieDePaginaHC(&$this->datosEvolucion ,$this->paso ,$this->hc_modulo ,sizeof($datosModulos));
        return true;

    }//fin de Inicializar()


    function ProcesoCerrarHistoria($tipoCierre)
    {
    
        if($_REQUEST['OBLIGAR_CIERRE_HC']=='SI')
        {
            $salida_submodulo=$this->CerrarHistoria();
            return $salida_submodulo;
        }
                
        if(empty($_REQUEST['conducta']) and empty($_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']]['CONDUCTA'])){
           
            if(sizeof($tipoCierre)==1){
                $ConductaAutomatica = $tipoCierre;
                $_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']]['CONDUCTA']=$ConductaAutomatica;
             
                if($tipoCierre[$ConductaAutomatica]['sw_pedir_submodulo_obligatorios']==1)
                {
                    $salida_submodulo=$this->ExigirSubmodulosObligatorios();
                    if($salida_submodulo=="OK"){
                        $salida_submodulo=$this->ExigirSubmodulosConducta($ConductaAutomatica);
                        if($salida_submodulo=="OK"){
                            $salida_submodulo=$this->CerrarHistoria();
                        }
                    }
                }else{
                    $salida_submodulo=$this->ExigirSubmodulosConducta($ConductaAutomatica);
                    if($salida_submodulo=="OK"){
                        $salida_submodulo=$this->CerrarHistoria();
                    }
                }                
            }else{ 
                $salida_submodulo=ConductaMedica(&$tipoCierre,&$this->datosEvolucion,$this->hc_modulo);
            }
        }else{
            if(empty($_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']]['CONDUCTA'])){
                $_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']]['CONDUCTA']=$_REQUEST['conducta'];
            }
            if($tipoCierre[$_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']]['CONDUCTA']]['sw_pedir_submodulo_obligatorios']==1)
            {
                $salida_submodulo=$this->ExigirSubmodulosObligatorios();
                if($salida_submodulo=="OK"){
                    $salida_submodulo=$this->ExigirSubmodulosConducta($_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']]['CONDUCTA']);
                    if($salida_submodulo=="OK"){
                        $salida_submodulo=$this->CerrarHistoria();
                    }
                }
            }else{
                $salida_submodulo=$this->ExigirSubmodulosConducta($_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']]['CONDUCTA']);
                if($salida_submodulo=="OK"){
                    $salida_submodulo=$this->CerrarHistoria();
                }
            }
        }
        return $salida_submodulo;
    }


    function ExigirSubmodulosObligatorios()
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();
        $sql="select a.submodulo from historias_clinicas_templates as a, system_hc_submodulos as b where a.submodulo=b.submodulo and a.hc_modulo='".$this->hc_modulo."' and a.sw_obligatorio_cierre='1' and b.sw_submodulo_sistema='0';";
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al consultar los submodulos obligatorios de la HC";
            $this->mensajeDeError = "SQL : $sql ". $dbconn->ErrorMsg();            
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;          
            return false;
        }
        while(!$result->EOF)
        {
            $submodulo_obj=IncluirSubModuloHC($result->fields[submodulo]);
            if(!is_object($submodulo_obj)){
                $this->error = "No se Pudo cargar el submodulo";
                $this->mensajeDeError = $submodulo_obj;
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;                 
                return false;
            }
            $submodulo_obj->InitSubmodulo($this->datosEvolucion,"cerrar",'frm_'.$result->fields[submodulo],$this->datosPaciente,'',$_SESSION['HC']['estacion'],$_SESSION['HC']['bodega'],0,$this->hc_modulo,$_SESSION['HC']['especialidad'],0,$result->fields[submodulo]);
            if(!$submodulo_obj->GetEstado())
            {
                $salida_submodulo=$submodulo_obj->GetSalida();
                if($submodulo_obj->GetEstado())
                {
                    $salida_submodulo.="<table align='center'>";
                    $salida_submodulo.="<tr>";
                    $salida_submodulo.="<td>";
                    $url=ModuloHCGetURL($this->datosEvolucion['evolucion_id'],'cerrar',0,$this->hc_modulo,$this->hc_modulo,array('DESMARCAR'=>1));
                    $salida_submodulo.="<form name='cerrarhistoria' method='post' action='$url'>";
                    $salida_submodulo.="<input type='submit' value='cerrar' name='cerrar' class='input-submit'>";
                    $salida_submodulo.="</form>";
                    $salida_submodulo.="</td>";
                    $salida_submodulo.="</tr>";
                    $salida_submodulo.="</table>";
                    
                    if(method_exists($submodulo_obj,'SubmoduloMensaje')){
                        if($submodulo_obj->SubmoduloMensaje()){
                            $NoMarcar=true;
                        }
                    }
                }
                unset($submodulo_obj);
                if(!$NoMarcar){
                    $_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']][$result->fields[submodulo]]=1;
                }
                return $salida_submodulo;
            }
            
            if(!empty($_REQUEST['DESMARCAR']))
            {
                unset($_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']][$result->fields[submodulo]]);
            }
            
            if(!empty($_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']][$result->fields[submodulo]]))
            {
                $salida_submodulo=$submodulo_obj->GetSalida();
                if($submodulo_obj->GetEstado())
                {
                    $salida_submodulo.="<table align='center'>";
                    $salida_submodulo.="<tr>";
                    $salida_submodulo.="<td>";
                    $url=ModuloHCGetURL($this->datosEvolucion['evolucion_id'],'cerrar',0,$this->hc_modulo,$this->hc_modulo,array('DESMARCAR'=>1));
                    $salida_submodulo.="<form method='post' action='$url'>";
                    $salida_submodulo.="<input type='submit' value='cerrar' name='cerrar' class='input-submit'>";
                    $salida_submodulo.="</form>";
                    $salida_submodulo.="</td>";
                    $salida_submodulo.="</tr>";
                    $salida_submodulo.="</table>";
                }
                unset($submodulo_obj);
                return $salida_submodulo;
            }
            unset($submodulo_obj);
            $result->MoveNext();
          }
        return "OK";
    }


    function ExigirSubmodulosConducta($conducta)
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();
        
        $sql="SELECT submodulo,titulo_mostrar,parametros
                FROM historias_clinicas_tipos_cierres_submodulos
                WHERE historia_clinica_tipo_cierre_id=$conducta 
                and (sexo_id='".$this->datosPaciente['sexo_id']."' or sexo_id is null) 
                and (edad_max>=".$this->edad_paciente['anos']." or edad_max is null) 
                and (edad_min<=".$this->edad_paciente['anos']." or edad_min is null)
                ORDER BY indice_orden;";
                
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al consultar submodulos del tipo de cierre.";
            $this->mensajeDeError = "SQL : $sql ". $dbconn->ErrorMsg();            
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;          
            return false;
        }
        while(!$result->EOF)
        {
            $datosSubmodulo=$result->FetchRow();
            $submodulo_obj=IncluirSubModuloHC($datosSubmodulo['submodulo']);
            if(!is_object($submodulo_obj)){
                $this->error = "No se Pudo cargar el submodulo";
                $this->mensajeDeError = $submodulo_obj;
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;                 
                return false;
            }

            $prefijo = 'frm_'.$datosSubmodulo['submodulo'];
            $submodulo = $datosSubmodulo['submodulo'];
            $titulo = $datosSubmodulo['titulo_mostrar'];
            $parametros = $datosSubmodulo['parametros'];
            $submodulo_obj->InicializarSubmodulo($this->datosEvolucion,$this->datosAdministrativos,$this->datosPaciente,$this->datosProfesional,$this->datosResponsable,$this->datosAdicionales,'cerrar',$prefijo,$submodulo,$this->hc_modulo,$titulo,$parametros);
            
            
            $submodulo_obj->InitSubmodulo($this->datosEvolucion,"cerrar",'frm_'.$result->fields[submodulo],$this->datosPaciente,'',$_SESSION['HC']['estacion'],$_SESSION['HC']['bodega'],0,$this->hc_modulo,$_SESSION['HC']['especialidad'],0,$result->fields[submodulo]);
            if(!$submodulo_obj->GetEstado())
            {
                $salida_submodulo=$submodulo_obj->GetSalida();
                if($submodulo_obj->GetEstado())
                {
                    $salida_submodulo.="<table align='center'>";
                    $salida_submodulo.="<tr>";
                    $salida_submodulo.="<td>";
                    $url=ModuloHCGetURL($this->datosEvolucion['evolucion_id'],'cerrar',0,$this->hc_modulo,$this->hc_modulo,array('salirmultiplesinsert'=>1));
                    $salida_submodulo.="<form name='cerrarhistoria' method='post' action='$url'>";
                    $salida_submodulo.="<input type='submit' value='cerrar' name='cerrar' class='input-submit'>";
                    $salida_submodulo.="</form>";
                    $salida_submodulo.="</td>";
                    $salida_submodulo.="</tr>";
                    $salida_submodulo.="</table>";
                }
                unset($submodulo_obj);
                $_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']][$result->fields[submodulo]]=1;
                return $salida_submodulo;
            }
            
            if(!empty($_REQUEST['salirmultiplesinsert']))
            {
                unset($_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']][$result->fields[submodulo]]);
            }
            
            
            if(!empty($_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']][$result->fields[submodulo]]))
            {
                $salida_submodulo=$submodulo_obj->GetSalida();
                if($submodulo_obj->GetEstado())
                {
                    $salida_submodulo.="<table align='center'>";
                    $salida_submodulo.="<tr>";
                    $salida_submodulo.="<td>";
                    $url=ModuloHCGetURL($this->datosEvolucion['evolucion_id'],'cerrar',0,$this->hc_modulo,$this->hc_modulo,array('salirmultiplesinsert'=>1));
                    $salida_submodulo.="<form method='post' action='$url'>";
                    $salida_submodulo.="<input type='submit' value='cerrar' name='cerrar' class='input-submit'>";
                    $salida_submodulo.="</form>";
                    $salida_submodulo.="</td>";
                    $salida_submodulo.="</tr>";
                    $salida_submodulo.="</table>";
                }
                unset($submodulo_obj);
                return $salida_submodulo;
            }
            unset($submodulo_obj);
            $result->MoveNext();
        }
        return "OK";
    }


    function CerrarHistoria()
    {
        list($dbconn) = GetDBconn();
        if(!empty($_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']]['CONDUCTA']))
        {
            $conducta=",historia_clinica_tipo_cierre_id=".$_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']]['CONDUCTA'];
        }
        $sql="update hc_evoluciones set estado=0,fecha_cierre='".date("Y-m-d H:i:s")."'$conducta where evolucion_id=".$this->datosEvolucion['evolucion_id'].";";
        $result = $dbconn->Execute($sql);
        if($dbconn->ErrorNo() != 0) 
        {
            $this->error = "No se Pudo cerrar la Hc";
            $this->mensajeDeError = "SQL : $sql ". $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;         
            return false;
        }
        
        includelib('malla_validadora');
        MallaValidadora($this->datosEvolucion['evolucion_id']);

        return 'HC_CERRAR_OK';
        
    }//fin de CerrarHistoria()
    
 
    function HistoriaClinicaResumeEvolucion($evolucion)
    {
        if (!IncludeFile("classes/ResumenHC/ResumenHC.class.php"))
        {
            $this->error = "Error";
            $this->mensajeDeError = "No se pudo incluir : classes/ResumenHC/ResumenHC.class.php";
        }
        global $VISTA;
        if (!IncludeFile("classes/ResumenHC/$VISTA/ResumenHC.$VISTA.php"))
        {
            $this->error = "Error";
            $this->mensajeDeError = "No se pudo incluir : classes/ResumenHC/$VISTA/ResumenHC.$VISTA.php";
        }
        $temp="ResumenHC_$VISTA";
        $resumenhc = new $temp($evolucion);
        if (!$resumenhc->Iniciar())
        {
            $this->error = $resumenhc->Error();
            $this->mensajeDeError = $resumenhc->ErrorMsg();
            return false;
        }
        $resumenhc->GetImpresion();
        return $resumenhc->GetSalida();
    }

    function HistoriaClinicaResumeApoyod($tipoidpaciente,$pacienteid,$evolucion,$modulo)
    {
        if (!IncludeFile("classes/ResumenAPD/ResumenAPD.class.php"))
        {
            $this->error = "Error";
            $this->mensajeDeError = "No se pudo incluir : classes/ResumenAPD/ResumenAPD.class.php";
        }
        global $VISTA;
        if (!IncludeFile("classes/ResumenAPD/$VISTA/ResumenAPD.$VISTA.php"))
        {
            $this->error = "Error";
            $this->mensajeDeError = "No se puo incluir : classes/ResumenAPD/$VISTA/ResumenAPD.$VISTA.php";
        }
        $temp="ResumenAPD_$VISTA";
        $resumenhc = new $temp($pacienteid,$tipoidpaciente,$evolucion,$modulo);
        if (!$resumenhc->Iniciar()){
            $this->error = $resumenhc->Error();
            $this->mensajeDeError = $resumenhc->ErrorMsg();
            return false;
        }
        return $resumenhc->GetSalida();
    }


        function HistoriaClinicaCompleta()
        {//HH24:MI //HH24:MM
            list($dbconn) = GetDBconn();
            $sql="SELECT        a.comentario,
                                to_char(b.fecha,'YYYY-MM-DD') as fecha,
                                d.nombre, e.descripcion as descripciondepto,
                                i.via_ingreso_nombre, f.descripcion as descripcionmotiv,
                                f.enfermedadactual, h.diagnostico_nombre,
                                b.ingreso, b.evolucion_id,
                                to_char(a.fecha_ingreso,'YYYY-MM-DD') as fecha_ingreso, x.triage_id
                    FROM        ingresos as a join vias_ingreso as i on (a.via_ingreso_id=i.via_ingreso_id)
                                join hc_evoluciones as b on (a.ingreso=b.ingreso and b.estado!=1)
                                join profesionales_usuarios as c on (b.usuario_id=c.usuario_id)
                                join profesionales as d on(c.tercero_id=d.tercero_id and c.tipo_tercero_id=d.tipo_id_tercero)
                                join departamentos as e on (b.departamento=e.departamento)
                                left join hc_motivo_consulta as f on(b.evolucion_id=f.evolucion_id)
                                left join hc_diagnosticos_ingreso as g on (b.evolucion_id=g.evolucion_id)
                                left join diagnosticos as h on (g.tipo_diagnostico_id=h.diagnostico_id)
                                left join triages as x on (x.ingreso = b.ingreso)
                    WHERE        a.paciente_id='".$this->datosPaciente['paciente_id']."'
                    AND            a.tipo_id_paciente='".$this->datosPaciente['tipo_id_paciente']."'
                    ORDER BY    b.fecha desc, b.evolucion_id asc;";

            $result = $dbconn->Execute($sql);
            if($dbconn->ErrorNo() != 0)
            {
                return false;
            }
            else
            {
                if(!$result->EOF)
                {
                    while(!$result->EOF)
                    {
                        $historia[$result->fields[8]][$result->fields[9]]=$result->GetRowAssoc(false);
                        $result->MoveNext();
                    }
                    $salida_submodulo=HistoriaClinicaPaciente(&$historia, $this->datosEvolucion['evolucion_id'], $this->hc_modulo);
                }
                return $salida_submodulo;
            }
        }


  function GetIngresosEvolucionesPaciente($tipo_id, $paciente_id)
  {
    $query = "SELECT ingresos.ingreso,
                    ingresos.fecha_ingreso,
                    ingresos.departamento,
                    hc_evoluciones.evolucion_id,
                    departamentos.descripcion
              FROM ingresos,
                    hc_evoluciones,
                    departamentos
              WHERE ingresos.tipo_id_paciente = '$tipo_id' AND
                    ingresos.paciente_id = '$paciente_id' AND
                    hc_evoluciones.ingreso = ingresos.ingreso AND
                    departamentos.departamento = ingresos.departamento
              ";

    GLOBAL $ADODB_FETCH_MODE;
    list($dbconn) = GetDBconn();
    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
    $result = $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0)
    {
      $this->error = "No se Pudo ejecutar la conexion";
      $this->mensajeDeError = "Ocurrió un error al intentar obtener las evoluciones del paciente<br>".$dbconn->ErrorMsg();
      return false;
    }
    else
    {
      if($result->EOF){
        return "ShowMensaje";
      }
      else
      {
        while ($data = $result->FetchRow()) {
          $evoluciones[$data[ingreso]][] = $data;
        }
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        return ShowEvolucionesPaciente($evoluciones);
      }
    }
  }//fin GetIngresosEvolucionesPaciente

}//fin de la clase
?>
