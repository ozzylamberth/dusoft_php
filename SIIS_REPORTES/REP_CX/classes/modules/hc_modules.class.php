<?php

class ManejadorDeHC extends classModules
{

    var $ingreso;
    var $cuenta;
    var $evolucion;
    var $departamento;
    var $estacion_id;
    var $paso;
    var $hc_modulo;
    var $parametro;

    var $hc_estructura=array();
    var $hc_submodulos=array();
    var $submodulos=array();
    var $datosEvolucion=array();
    var $datosPaciente=array();
    var $datosProfesional=array();
    var $datosAdministrativos=array();
    var $datosResponsable=array();
    var $datosAdicionales=array();

    var $edad_paciente=0;

    //?????????????
    var $especialidad;
    var $pruebadatosmodulo;


    function ManejadorDeHC()
    {
        // llamando al constructor del padre
        $this->classModules();
        $this->error = '';
        $this->mensajeDeError = '';
        $this->salida = '';
        return true;
    }


    function NewEvolucion($ingreso,$hc_modulo,$departamento,$cuenta='')
    {
        if(empty($ingreso) || empty($hc_modulo) || empty($departamento))
        {
            return false;
        }

                if(!UserGetUID())
                {
                    return false;
                }

        list($dbconn) = GetDBconn();

        //VERIFICAR SI EXISTE UNA EVOLUCION ABIERTA DEL MISMO PROFESIONAL, SI EXISTE RETORNA LA INFORMACION DE ESTA
        $sql ="select evolucion_id from hc_evoluciones where estado=1 and ingreso=$ingreso and hc_modulo='$hc_modulo' and usuario_id=".UserGetUID()." order by evolucion_id desc;";

        $result = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            return false;
        }

        if (!$result->EOF) {
            return GetDatosEvolucion($result->fields[0]);
        }

        //CREAR LA NUEVA EVOLUCION
        $sql = "SELECT nextval('hc_evoluciones_seq');";

        $result = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0) {
            return false;
        }

        if ($result->EOF) {
            return false;
        }

        list($num_evolucion) = $result->FetchRow();

        if(!empty($cuenta))
        {
            $insertCuentaCampo = ", numerodecuenta";
            $insertcuentaValue = ", $cuenta";
        }

        $sql = "INSERT INTO hc_evoluciones (evolucion_id, ingreso, fecha, usuario_id, departamento, estado, hc_modulo $insertCuentaCampo)
                VALUES ($num_evolucion, $ingreso, now(), ".UserGetUID().", '$departamento', '1', '$hc_modulo' $insertcuentaValue);";

        $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0) {
            return false;
        }

        return GetDatosEvolucion($num_evolucion);
    }

    function Inicializar()
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

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

        GLOBAL $VISTA;

        if(!IncludeFile("hc_modules/$VISTA/lib/browserHC.$VISTA.php",true)){
            $this->error = "No se Pudo Cargar el Modulo";
            $this->mensajeDeError = "El archivo 'hc_modules/$VISTA/lib/browserHC.$VISTA.php' no existe.";
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }

        if(!class_exists('Validador')){
            $this->error = "No se Pudo Cargar el Modulo";
            $this->mensajeDeError = "La clase 'Validador' no existe.";
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }

        if(!empty($_REQUEST['HC_DATOS_CONTROL']['DEPARTAMENTO']))
        {
            $this->departamento = $_REQUEST['HC_DATOS_CONTROL']['DEPARTAMENTO'];
        }

        if(!empty($_REQUEST['HC_DATOS_CONTROL']['ESTACION']))
        {
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
                list($this->departamento) = $result->FetchRow();
                $this->estacion_id = $_REQUEST['HC_DATOS_CONTROL']['ESTACION'];
            }

            $result->Close();
        }


        //Si existe la evolucion
        if(!empty($_REQUEST['evolucion']) )
        {
            $this->evolucion = $_REQUEST['evolucion'];

            //llena el vector datosEvolucion
            $this->datosEvolucion = GetDatosEvolucion($this->evolucion);
            if(!$this->datosEvolucion)
            {
                $this->error = "No se Pudo Cargar la Historia Clinica";
                $this->mensajeDeError = "El Numero de evolucion " . $this->evolucion ." clinica no es valido";
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;
                return false;
            }

            //La plantilla (template o tipo de historia) asociada a la evolucion.
            $this->hc_modulo = $this->datosEvolucion['hc_modulo'];
            $this->departamento = $this->datosEvolucion['departamento'];
            $this->ingreso = $this->datosEvolucion['ingreso'];

            //Verifica si la plantilla esta activa.
            if(!ModuloGetEstado('hc', $this->hc_modulo))
            {
                $this->error = "MODULO INACTIVO";
                $this->mensajeDeError = "El modulo de Historia Clinica [" . $this->hc_modulo. "] no esta Activado";
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;
                return false;
            }

        }
        else // Si no llego un numero de evolucion crea una evolucion nueva a partir del ingreso.
        {
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
            $sql="SELECT departamento_actual FROM ingresos WHERE ingreso=" . $_REQUEST['ingreso'] . ";";
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

            $this->ingreso = $_REQUEST['ingreso'];

            //si no existe el parametro departamento coloco el departamento del ingreso
            if(empty($this->departamento))
            {
                list($this->departamento) = $result->FetchRow();
            }

            $result->Close();


            //Verifico que exita el parametro de la plantilla para crear la nueva HC
            if(empty($_REQUEST['hc_modulo']))
            {
                $this->error = "No se Pudo Cargar la Historia Clinica";
                $this->mensajeDeError = "No se ha indicado un Modulo de Historia Clinica, para la nueva evolucion.";
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;
                return false;
            }

            //Verifico la existencia y el estado de la plantilla HC
            if(!ModuloGetEstado('hc',$_REQUEST['hc_modulo']))
            {
                $this->error = "No se Pudo Cargar la Historia Clinica";
                $this->mensajeDeError = "El modulo de Historia Clinica [" . $this->hc_modulo. "] no esta activado o no existe";
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;
                return false;
            }

            $this->hc_modulo = $_REQUEST['hc_modulo'];


            //Buscar si hay una cuenta activa para el ingreso actual para referenciar la HC
            $sql="SELECT a.numerodecuenta FROM cuentas as a, ingresos as b
                    WHERE b.ingreso=".$this->ingreso."
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
                list($this->cuenta)=$resultado->FetchRow();
                $resultado->Close();
            }
            else
            {
                $resultado->Close();
                //Si no hay cuentas activas busco la ultima cuenta del ingreso para referenciar la HC
                $sql="SELECT a.numerodecuenta FROM cuentas as a, ingresos as b
                        WHERE b.ingreso=".$this->ingreso."
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
                    list($this->cuenta)=$resultado->FetchRow();
                    $resultado->Close();
                }
                else
                {
                    $this->cuenta=0;
                    $resultado->Close();
                }
            }

            //Genero la nueva evolucion.
            $this->datosEvolucion = $this->NewEvolucion($this->ingreso,$this->hc_modulo,$this->departamento,$this->cuenta);

            if(!$this->datosEvolucion)
            {
                $this->error = "No se Pudo Cargar la Historia Clinica";
                $this->mensajeDeError = "No se Pudo crear la nueva evolucion";
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;
                return false;
            }
        }//fin del cargue o creacion de la evolucion.


        //Si se creo la evolucion busco si hay informacion del responsable
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

        $tipo_profesional=GetTipoProfesional(UserGetUID());


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

        // OBTENER DATOS ADMINISTRATIVOS
        $sql="SELECT empresa_id, centro_utilidad, unidad_funcional, departamento, servicio FROM departamentos WHERE departamento='".$this->departamento."'";
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "No se Pudo Cargar la Historia Clinica";
            $this->mensajeDeError = "No se pudo obtener los datos administrativos en hc_classmodules.class.php ".$dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }

        if($result->EOF){
            $this->error = "No se Pudo Cargar la Historia Clinica";
            $this->mensajeDeError = "No se pudo obtener los datos administrativos en hc_classmodules.class.php ".$dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }

        $this->datosAdministrativos = $result->FetchRow();
        $result->Close();

        //Activo el javascript de DatosPaciente para las ventanas emergentes
        $this->SetJavaScripts('DatosPaciente');

        //Obtener el vector de datos paciente desde el include datospaciente.inc.php
        $this->datosPaciente = GetDatosPaciente('','',$this->datosEvolucion['ingreso'],$this->datosEvolucion['evolucion_id']);

        //Calcular la edad con respecto a la fecha de la evolucion.
        $edad=CalcularEdad($this->datosPaciente['fecha_nacimiento'],$this->datosEvolucion['fecha']);

        if(empty($edad['anos'])){
            $edad['anos']=0;
        }

        $this->edad_paciente=$edad;

        //Se ubica en el paso correspondiente y si no llego paso en el listado de submodulos.
        if(!empty($_REQUEST['paso']) or $_REQUEST['paso']==="0")
        {
            $this->paso = $_REQUEST['paso'];
        }
        else
        {
            $this->paso = -1;
        }

        $query = "SELECT paso, count(paso) as numero_de_submodulos
                    FROM historias_clinicas_templates as a join system_hc_submodulos as b on(a.submodulo=b.submodulo and (b.sexo_id='".$this->datosPaciente['sexo_id']."' or b.sexo_id is null) and (b.gestacion='".$this->datosPaciente['gestacion']."' or b.gestacion is null) and (b.edad_max>=".$edad['anos']." or b.edad_max is null) and (b.edad_min<=".$edad['anos']." or b.edad_min is null))
                    WHERE hc_modulo = '" . $this->hc_modulo . "' and b.sw_submodulo_sistema='0'
                    GROUP BY a.hc_seccion_id,paso
                    ORDER BY a.hc_seccion_id,paso";

        $result = $dbconn->Execute($query);

        if($dbconn->ErrorNo() != 0) {
            $this->error = "No se Pudo Cargar el Modulo HC";
            $this->mensajeDeError = $dbconn->ErrorMsg();
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

        while(!$result->EOF)
        {
            $datosModulos[]=$result->fields[0];
            $result->MoveNext();
        }

        if(is_numeric($this->paso) AND $this->paso != "-1")
        {
             $query = "SELECT parametros
                       FROM historias_clinicas_templates
                       WHERE paso = ".$this->paso."
                       AND hc_modulo='".$this->hc_modulo."';";
             $result = $dbconn->Execute($query);

             if($dbconn->ErrorNo() != 0) {
                  $this->error = "No se Pudo Cargar el Modulo HC";
                  $this->mensajeDeError = $dbconn->ErrorMsg();
                  $this->fileError = __FILE__;
                  $this->lineError = __LINE__;
                  return false;
             }
        }
        $this->parametro = $result->fields[0];

        $this->pruebadatosmodulo=$datosModulos;

        $this->hc_estructura = $result->GetAssoc();

        $result->Close();

        $query = "SELECT a.paso,a.secuencia,a.submodulo,b.descripcion, c.tipo_finalidad_id, c.rips_tipo_id, a.hc_seccion_id, d.descripcion as agrupamiento, a.sw_mostrar, a.sw_siquiatria FROM historias_clinicas_templates a, system_hc_submodulos b, system_hc_modulos  as c, historia_clinica_secciones as d WHERE a.hc_modulo = '" . $this->hc_modulo . "' and c.hc_modulo='" . $this->hc_modulo . "' AND a.submodulo=b.submodulo and (b.sexo_id='".$this->datosPaciente['sexo_id']."' or b.sexo_id is null) and (b.gestacion='".$this->datosPaciente['gestacion']."' or b.gestacion is null) and (b.edad_max>=".$edad['anos']." or b.edad_max is null) and (b.edad_min<=".$edad['anos']." or b.edad_min is null) and a.hc_seccion_id=d.hc_seccion_id and b.sw_submodulo_sistema='0' ORDER BY a.hc_seccion_id,a.paso,a.secuencia;";

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

        while ($result_paso = $result->FetchRow()) {
            $this->hc_submodulos[$result_paso['paso']][$result_paso['secuencia']][$result_paso['hc_seccion_id']] = $result_paso;

            //PERDON PROMETO CORREGIR..capturo la finalidad de hc_modulo que es la misma en todos los registros
            $this->datosAdicionales['tipoFinalidad']=$result_paso['tipo_finalidad_id'];
        }


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
                $this->IncludeJS('RemoteScripting');
        		 $this->IncludeJS('classes/ResumenAPD/RemoteScripting/misfunciones.js');
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

                        $submodulo_obj->InicializarSubmodulo($this->datosEvolucion,$this->datosAdministrativos,$this->datosPaciente,$this->datosProfesional,$this->datosResponsable,$this->datosAdicionales,$this->paso,$prefijo,$submodulo,$this->hc_modulo,'',$this->parametro);
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

        /*******PARTE PROVISIONAL*******/
          if(empty($_REQUEST['conducta']) and empty($_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']]['CONDUCTA']))
          {
               if(sizeof($tipoCierre)==1)
               {
                    foreach ($tipoCierre as $k => $X)
                    {
                         $_REQUEST['conducta'] = $X[historia_clinica_tipo_cierre_id];
                    }
               }
          }
        /*******PARTE PROVISIONAL*******/

        if(empty($_REQUEST['conducta']) and empty($_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']]['CONDUCTA']))
        {

            if(sizeof($tipoCierre)==1)
            {
                $ConductaAutomatica = $tipoCierre;
                $_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']]['CONDUCTA']=$ConductaAutomatica;

                if($tipoCierre[$ConductaAutomatica]['sw_pedir_submodulo_obligatorios']==1)
                {
                    $salida_submodulo=$this->ExigirSubmodulosObligatorios();
                    if($salida_submodulo=="OK")
                    {
                        $salida_submodulo=$this->ExigirSubmodulosConducta($ConductaAutomatica);
                        if($salida_submodulo=="OK")
                        {
                            $salida_submodulo=$this->CerrarHistoria();
                        }
                    }
                }
                else
                {
                    $salida_submodulo=$this->ExigirSubmodulosConducta($ConductaAutomatica);
                    if($salida_submodulo=="OK")
                    {
                        $salida_submodulo=$this->CerrarHistoria();
                    }
                }
            }
            else
            {
                $salida_submodulo=ConductaMedica(&$tipoCierre,&$this->datosEvolucion,$this->hc_modulo);
            }
        }
        else
        {
            if(empty($_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']]['CONDUCTA']))
            {
                $_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']]['CONDUCTA']=$_REQUEST['conducta'];
                $_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']]['CONDUCTA_OBSERVACION']=$_REQUEST['conducta_observacion'];
            }

            if($tipoCierre[$_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']]['CONDUCTA']]['sw_pedir_submodulo_obligatorios']==1)
            {
                $salida_submodulo=$this->ExigirSubmodulosObligatorios();
                if($salida_submodulo=="OK")
                {
                    $salida_submodulo=$this->ExigirSubmodulosConducta($_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']]['CONDUCTA']);
                    if($salida_submodulo=="OK")
                    {
                        $salida_submodulo=$this->CerrarHistoria();
                    }
                }
            }
            else
            {
                $salida_submodulo=$this->ExigirSubmodulosConducta($_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']]['CONDUCTA']);
                if($salida_submodulo=="OK")
                {
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
            $submodulo_obj->InitSubmodulo($this->datosEvolucion,"cerrar",'frm_'.$result->fields[submodulo],$this->datosPaciente,'',$_SESSION['HC']['estacion'],$_SESSION['HC']['bodega'],0,$this->hc_modulo,$_SESSION['HC']['especialidad'],0,$result->fields[submodulo],$this->datosResponsable,$this->datosAdministrativos,$this->parametro);
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
            $submodulo_obj->InicializarSubmodulo($this->datosEvolucion,$this->datosAdministrativos,$this->datosPaciente,$this->datosProfesional,$this->datosResponsable,$this->datosAdicionales,'cerrar',$prefijo,$submodulo,$this->hc_modulo,$titulo,$this->parametro);


            $submodulo_obj->InitSubmodulo($this->datosEvolucion,"cerrar",'frm_'.$result->fields[submodulo],$this->datosPaciente,'',$_SESSION['HC']['estacion'],$_SESSION['HC']['bodega'],0,$this->hc_modulo,$_SESSION['HC']['especialidad'],0,$result->fields[submodulo],$this->datosResponsable,$this->datosAdministrativos,$this->parametro);
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

            $sql="SELECT hc_tipo_orden_medica_id FROM historias_clinicas_tipos_cierres WHERE historia_clinica_tipo_cierre_id=".$_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']]['CONDUCTA'];


            $result = $dbconn->Execute($sql);


            if($dbconn->ErrorNo() == 0)
            {
                if(!$result->EOF)
                {
                    list($hc_tipo_orden_medica_id) = $result->FetchRow();

                    if(!empty($hc_tipo_orden_medica_id))
                    {
                        $sql = "
                            UPDATE hc_ordenes_medicas SET sw_estado='2' WHERE ingreso=".$this->datosEvolucion['ingreso'].";
                            DELETE FROM hc_ordenes_medicas WHERE ingreso=".$this->datosEvolucion['ingreso']." AND evolucion_id=".$this->datosEvolucion['evolucion_id'].";
                            INSERT INTO hc_ordenes_medicas(ingreso, evolucion_id, hc_tipo_orden_medica_id, sw_estado) VALUES(".$this->datosEvolucion['ingreso'].",".$this->datosEvolucion['evolucion_id'].",'".$hc_tipo_orden_medica_id."','1');
                        ";
                        $result = $dbconn->Execute($sql);
                    }
                }
            }
        }

        if(!empty($_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']]['CONDUCTA_OBSERVACION'])){
            $conducta_observacion=",observacion_cierre='".$_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']]['CONDUCTA_OBSERVACION']."' ";
        }

        $sql="update hc_evoluciones set estado=0,fecha_cierre='".date("Y-m-d H:i:s")."'$conducta $conducta_observacion where evolucion_id=".$this->datosEvolucion['evolucion_id'].";";
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
        //MODIFICACION PARA CACHE DE LOS RESUMENES.

        list($dbconn) = GetDBconn();

        $sql="SELECT cache_hc FROM system_cache_hc WHERE evolucion_id=$evolucion";
        $result = $dbconn->GetOne($sql);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "No se pudo consultar la tabla de cache hc";
            $this->mensajeDeError = "SQL : $sql ". $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }

        if($result) return $result;

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

        $salida = $resumenhc->GetSalida();
        $sql="INSERT INTO system_cache_hc(evolucion_id,cache_hc) VALUES($evolucion,'".PrepararCadenaParaSQL($salida)."')";
        $dbconn->Execute($sql);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "No se pudo insertar en la tabla de cache hc";
            $this->mensajeDeError = "SQL : $sql ". $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }

        return $salida;
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
     {
          list($dbconn) = GetDBconn();
         $sql="SELECT A.ingreso, A.comentario, TO_CHAR(A.fecha_ingreso,'YYYY-MM-DD') AS fecha_ingreso,
                    B.evolucion_id, TO_CHAR(B.fecha,'YYYY-MM-DD') AS fecha_evolucion,
                    C.descripcion AS motivo_consulta, C.enfermedadactual AS enfermedad_actual,
                    D.diagnostico_nombre,
                    D.diagnostico_id,
                    F.nombre AS nombre_medico, F.descripcion AS descipcion_medico,
                    G.descripcion AS dpto,
                    H.via_ingreso_nombre,
                    I.triage_id,
                    C.evolucion_id AS evo_motivo,
                    E.evolucion_id AS evo_diag

               FROM ingresos AS A
               JOIN vias_ingreso AS H ON(A.via_ingreso_id=H.via_ingreso_id)
               JOIN hc_evoluciones AS B ON (A.ingreso=B.ingreso and B.estado!=1)
               JOIN system_usuarios AS F ON(B.usuario_id=F.usuario_id)
               JOIN departamentos AS G ON(B.departamento=G.departamento)
               LEFT JOIN hc_motivo_consulta AS C ON(B.ingreso = C.ingreso AND B.evolucion_id = C.evolucion_id)
               LEFT JOIN hc_diagnosticos_ingreso AS E ON (B.evolucion_id=E.evolucion_id)
               LEFT JOIN diagnosticos AS D ON(E.tipo_diagnostico_id=D.diagnostico_id)
               LEFT JOIN triages AS I ON(I.ingreso = A.ingreso)

               WHERE A.paciente_id='".$this->datosPaciente['paciente_id']."'
               AND A.tipo_id_paciente='".$this->datosPaciente['tipo_id_paciente']."'
               ORDER BY B.fecha DESC, B.evolucion_id ASC;";

          $result = $dbconn->Execute($sql);
          if($dbconn->ErrorNo() != 0)
          {
               return false;
          }
          else
          {
               while(!$result->EOF)
               {
                    $historia[$result->fields[0]][$result->fields[3]][] = $result->GetRowAssoc($ToUpper = false);//[$result->fields[3]]
                    $result->MoveNext();
               }
          }
          $salida_submodulo=HistoriaClinicaPaciente(&$historia, $this->datosEvolucion['evolucion_id'], $this->hc_modulo);
          return $salida_submodulo;
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
