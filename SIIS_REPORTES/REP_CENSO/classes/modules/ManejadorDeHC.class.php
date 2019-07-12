<?php

class ManejadorDeHC extends classModules
{

     var $usuarioConsultante='';
     var $departamentoOrigenLlamado;

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
     var $hc_submodulos_obligatorios=array();
     var $hc_secciones=array();
     var $datosHcModulo=array();
     var $mostrarSubmodulosOcultos=false;
     var $submodulos_info=array();
     var $paso_info=array();
     var $numPasos=array();
     var $tiposConductas = array();


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
          $sql = "SELECT departamento_actual FROM ingresos WHERE ingreso=" . $_REQUEST['ingreso'] . ";";
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

          //SI LA EVOLUCION ES NUEVA Y EL USUARIO = 0 GENERA ERROR
          if($this->usuarioConsultante === 0)
          {
               $this->error = "Error en el manejador de HC";
               $this->mensajeDeError = "USUARIO EMPTY - 0 PARA CREACION DE EVOLUCION.";
               $this->fileError = __FILE__;
               $this->lineError = __LINE__;
               return false;
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
               if($this->datosEvolucion['usuario_id'] != $this->usuarioConsultante && $this->datosEvolucion['estado']=='1')
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
                    $this->datosHcModulo['parametros'] = ExplodeArrayAssoc($this->datosHcModulo['parametros']);
               }
               $result->Close();

               //Generar el vector de DatosProfesional
               $this->datosProfesional = GetDatosProfesional($this->datosEvolucion['usuario_id']);

               //Generar el vector de las Especialidades del Profesional.
               $queryE = "SELECT A.especialidad
                          FROM profesionales_especialidades AS A,
                              profesionales AS B
                          WHERE B.usuario_id = ".$this->usuarioConsultante."
                          AND A.tipo_id_tercero = B.tipo_id_tercero
                          AND A.tercero_id = B.tercero_id";

               $result = $dbconn->Execute($queryE);

               if($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al consultar pasos de la HC";
                    $this->mensajeDeError = "SQL : $query ". $dbconn->ErrorMsg();
                    $this->fileError = __FILE__;
                    $this->lineError = __LINE__;
                    return false;
               }
               while ($datos = $result->FetchRow()) {
                    $especialidades[] = $datos;
               }
               $result->Close();

               if(!empty($especialidades))
               {
                    for($i=0; $i<sizeof($especialidades); $i++)
                    {
                         $this->datosProfesional['especialidades'][] = $especialidades[$i][0];
                    }
               }

               //Calcular la edad con respecto a la fecha de la evolucion.
               $this->datosPaciente['edad_paciente'] = CalcularEdad($this->datosPaciente['fecha_nacimiento'],$this->datosEvolucion['fecha']);

               if(empty($this->datosPaciente['edad_paciente']['edad_en_dias'])){
                    $this->datosPaciente['edad_paciente']['edad_en_dias']= 0;
               }

               //Generacion de la estructura de la HC
               $query = "
                    SELECT
                         a.paso,
                         a.secuencia,
                         a.submodulo,
                         a.titulo_mostrar,
                         b.descripcion as titulo_generico,
                         a.hc_seccion_id,
                         c.descripcion as hc_seccion_titulo,
                         a.sw_mostrar,
                         a.parametros,
                         a.sw_obligatorio_cierre,
                         b.sw_submodulo_sistema
                    FROM
                         historias_clinicas_templates a,
                         system_hc_submodulos b,
                         historia_clinica_secciones as c
                    WHERE a.hc_modulo = '" . $this->datosEvolucion['hc_modulo'] . "'
                         AND (a.sexo_id='" . $this->datosPaciente['sexo_id'] . "' OR a.sexo_id IS NULL)
                         AND (a.edad_max>=" . $this->datosPaciente['edad_paciente']['edad_en_dias'] . " OR a.edad_max IS NULL)
                         AND (a.edad_min<=" . $this->datosPaciente['edad_paciente']['edad_en_dias'] . " OR a.edad_min IS NULL)
                         AND b.submodulo=a.submodulo
                         AND b.sw_submodulo_sistema='0'
                         AND c.hc_seccion_id = a.hc_seccion_id
                    ORDER BY
                         a.sw_mostrar DESC ,a.hc_seccion_id,a.paso,a.secuencia ASC;";


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

               $j = 0;
               while ($result_paso = $result->FetchRow())
               {
                    $submodulos[$result_paso['sw_mostrar']][$result_paso['hc_seccion_id']][$result_paso['paso']][$result_paso['secuencia']] = $result_paso;
                    if($result_paso['sw_obligatorio_cierre']==='1' && $result_paso['sw_submodulo_sistema']==='0')
                    {
                         $this->hc_submodulos_obligatorios[$j]['submodulo'] = $result_paso['submodulo'];
                         $this->hc_submodulos_obligatorios[$j]['paso'] = $result_paso['paso'];
                         //Se realiza verificacion de funcion GetEstado
                         $submodulo_obj = IncluirSubModuloHC($result_paso['submodulo']);

                         if(!is_object($submodulo_obj))
                         {
                              $this->error = "No se Pudo cargar el submodulo";
                              $this->mensajeDeError = $submodulo_obj;
                              $this->fileError = __FILE__;
                              $this->lineError = __LINE__;
                              return false;
                         }

                         $prefijo = 'frm_'.$result_paso['submodulo'];
                         $submodulo_obj->InicializarSubmodulo($this->datosEvolucion,'',$this->datosPaciente,'','','',false,$prefijo,'','','','');

                         if(method_exists($submodulo_obj,'GetEstado'))
                         {
                              $this->hc_submodulos_obligatorios[$j]['estado'] = $submodulo_obj->GetEstado(); //valor del GetEstado del submodulo
                         }
                         //Se realiza verificacion de funcion GetEstado

                         $j++;
                    }
                    $this->submodulos_info[$result_paso['submodulo']] =  $result_paso;
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

                              $this->paso_info[$I]['hc_seccion_id'] = $DatosSubmodulo['hc_seccion_id'];
                              $this->paso_info[$I]['sw_mostrar']    = $DatosSubmodulo['sw_mostrar'];

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
               $_SESSION['HC_EVOLUCION'][$this->usuarioConsultante][$this->datosEvolucion['evolucion_id']]['hc_submodulos_obligatorios'] = &$this->hc_submodulos_obligatorios;
               $_SESSION['HC_EVOLUCION'][$this->usuarioConsultante][$this->datosEvolucion['evolucion_id']]['datosAdicionales'] = &$this->datosAdicionales;
               $_SESSION['HC_EVOLUCION'][$this->usuarioConsultante][$this->datosEvolucion['evolucion_id']]['paso_info'] = &$this->paso_info;

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

               //Generacion de la estructura de la HC
               $query = "
                    SELECT
                         a.submodulo,
                         a.sw_obligatorio_cierre,
                         a.paso,
                         b.sw_submodulo_sistema
                    FROM
                         historias_clinicas_templates a,
                         system_hc_submodulos b,
                         historia_clinica_secciones as c
                    WHERE a.hc_modulo = '" . $this->datosEvolucion['hc_modulo'] . "'
                         AND (a.sexo_id='" . $this->datosPaciente['sexo_id'] . "' OR a.sexo_id IS NULL)
                         AND (a.edad_max>=" . $this->datosPaciente['edad_paciente']['edad_en_dias'] . " OR a.edad_max IS NULL)
                         AND (a.edad_min<=" . $this->datosPaciente['edad_paciente']['edad_en_dias'] . " OR a.edad_min IS NULL)
                         AND b.submodulo=a.submodulo
                         AND b.sw_submodulo_sistema='0'
                         AND c.hc_seccion_id = a.hc_seccion_id
                    ORDER BY
                         a.sw_mostrar DESC ,a.hc_seccion_id,a.paso,a.secuencia ASC;";


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

               $j = 0;
               while ($result_paso = $result->FetchRow())
               {
                    $submodulos[$result_paso['sw_mostrar']][$result_paso['hc_seccion_id']][$result_paso['paso']][$result_paso['secuencia']] = $result_paso;
                    if($result_paso['sw_obligatorio_cierre']==='1' && $result_paso['sw_submodulo_sistema']==='0')
                    {
                         $this->hc_submodulos_obligatorios[$j]['submodulo'] = $result_paso['submodulo'];
                         $this->hc_submodulos_obligatorios[$j]['paso'] = $result_paso['paso'];

                         //Se realiza verificacion de funcion GetEstado
                         $submodulo_obj = IncluirSubModuloHC($result_paso['submodulo']);

                         if(!is_object($submodulo_obj))
                         {
                              $this->error = "No se Pudo cargar el submodulo";
                              $this->mensajeDeError = $submodulo_obj;
                              $this->fileError = __FILE__;
                              $this->lineError = __LINE__;
                              return false;
                         }

                         $prefijo = 'frm_'.$result_paso['submodulo'];
                         $submodulo_obj->InicializarSubmodulo($this->datosEvolucion,'',$this->datosPaciente,'','','',false,$prefijo,'','','','');

                         if(method_exists($submodulo_obj,'GetEstado'))
                         {
                              $this->hc_submodulos_obligatorios[$j]['estado'] = $submodulo_obj->GetEstado(); //valor del GetEstado del submodulo
                         }
                         //Se realiza verificacion de funcion GetEstado

                         $j++;
                    }
                    $this->submodulos_info[$result_paso['submodulo']] =  $result_paso;
               }
               $result->Close();

               $this->datosAdicionales     = &$_SESSION['HC_EVOLUCION'][$this->usuarioConsultante][$this->datosEvolucion['evolucion_id']]['datosAdicionales'];
               $this->paso_info            = &$_SESSION['HC_EVOLUCION'][$this->usuarioConsultante][$this->datosEvolucion['evolucion_id']]['paso_info'];

          }
          return true;
     } //fin del metodo ConstruirDatosHC()


     function GetPasoHC()
     {
          if($_REQUEST['paso'])
          { $this->paso = $_REQUEST['paso']; }
          else
          { $this->paso = -1; }

          if($this->paso == -1)
          {
               if($this->datosEvolucion['estado'] == 0)
               {
                    $this->paso = "pasoresumen";
               }
          }
          return true;
     }// Fin del metodo GetPasoHC()


     function ProcesoCerrarHistoria($tipoCierre)
     {
          if($_REQUEST['OBLIGAR_CIERRE_HC']=='SI')
          {
               $salida_submodulo=$this->CerrarHistoria();
               return $salida_submodulo;
          }

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


     function ExigirSubmodulosConducta($conducta)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $sql="SELECT submodulo,titulo_mostrar,parametros
                FROM historias_clinicas_tipos_cierres_submodulos
                WHERE historia_clinica_tipo_cierre_id=$conducta
                and (sexo_id='".$this->datosPaciente['sexo_id']."' or sexo_id is null)
                and (edad_max>=".$this->datosPaciente['edad_paciente']['anos']." or edad_max is null)
                and (edad_min<=".$this->datosPaciente['edad_paciente']['anos']." or edad_min is null)
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

          while($datosSubmodulo = $result->FetchRow())
          {
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
               $submodulo_obj->InicializarSubmodulo($this->datosEvolucion,$this->datosAdministrativos,$this->datosPaciente,$this->datosProfesional,$this->datosResponsable,$this->datosAdicionales,$this->paso,$prefijo,$submodulo,$this->datosEvolucion['hc_modulo'],$titulo,$parametros);

               if(!$submodulo_obj->GetEstado())
               {
                    $salida_submodulo=$submodulo_obj->GetSalida();
                    if($submodulo_obj->GetEstado())
                    {
                         $salida_submodulo.="<table align='center'>";
                         $salida_submodulo.="<tr>";
                         $salida_submodulo.="<td>";
                         $url=ModuloHCGetURL($this->datosEvolucion['evolucion_id'],'cerrar',0,$this->hc_modulo,$this->hc_modulo,array('salirmultiplesinsert'=>"1"));
                         $salida_submodulo.="<form name='cerrarhistoria' method='post' action='$url'>";
                         $salida_submodulo.="<input type='submit' value='Continuar Cierre de la Atención' name='cerrar' class='input-submit'>";
                         $salida_submodulo.="</form>";
                         $salida_submodulo.="</td>";
                         $salida_submodulo.="</tr>";
                         $salida_submodulo.="</table>";

                         $_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']][$result->fields[submodulo]]=1;
                    }
                    unset($submodulo_obj);
                    return $salida_submodulo;
               }

               if($_REQUEST['salirmultiplesinsert'] == "1")
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
                         $url=ModuloHCGetURL($this->datosEvolucion['evolucion_id'],'cerrar',0,$this->hc_modulo,$this->hc_modulo,array('salirmultiplesinsert'=>"1"));
                         $salida_submodulo.="<form method='post' action='$url'>";
                         $salida_submodulo.="<input type='submit' value='Continuar Cierre de la Atención' name='cerrar' class='input-submit'>";
                         $salida_submodulo.="</form>";
                         $salida_submodulo.="</td>";
                         $salida_submodulo.="</tr>";
                         $salida_submodulo.="</table>";
                    }
                    unset($submodulo_obj);
                    return $salida_submodulo;
               }
               unset($submodulo_obj);
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

          $_SESSION['HC_EVOLUCION'][$this->usuarioConsultante][$this->datosEvolucion['evolucion_id']]['datosEvolucion'] = $this->GetDatosEvolucion($this->datosEvolucion['evolucion_id']);

          includelib('malla_validadora');
          MallaValidadora($this->datosEvolucion['evolucion_id']);
          return true;
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
          $salida_submodulo = $this->HistoriaClinicaPaciente(&$historia, $this->datosEvolucion['evolucion_id'], $this->hc_modulo);
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


     //Metodo para Incluir los submodulos de un paso de HC
     function IncludePasoHC()
     {
          $submodulosPaso = &$this->hc_submodulos[$this->paso];
          foreach($submodulosPaso as $k=>$datosSubmodulo)
          {
               $SubModulo_obj = &$this->GetObjSubmoduloHC($datosSubmodulo['submodulo']);
               if(!is_object($SubModulo_obj))
               {
                    $Err = 'ERROR AL LLAMAR EL SUBMODULO : '.$datosSubmodulo['submodulo'];
                    $ErrMsg = 'No se pudo crear el objecto.';
                    $TituloVentana = 'ERROR EN LLAMADO A SUBMODULO';
                    return $this->MensajeErrorSubmodulo($datosSubmodulo['submodulo'],$Err,$ErrMsg,$TituloVentana);
               }
               $prefijo='frm_'.$datosSubmodulo['submodulo'];
               $SubModulo_obj->InicializarSubmodulo($this->datosEvolucion,$this->datosAdministrativos,$this->datosPaciente,$this->datosProfesional,$this->datosResponsable,$this->datosAdicionales,$this->paso,$prefijo,$datosSubmodulo['submodulo'],$this->datosEvolucion['hc_modulo'],$datosSubmodulo['parametros']);
               $salida .= $SubModulo_obj->GetSalida();
               foreach($SubModulo_obj->GetJavaScriptsSubmodulos() as $v=>$k)
               {
                    $this->SetJavaScripts($v);
               }
               unset($SubModulo_obj);
          }
          return $salida;
     }

     //Metodo para obtener un objeto tip submodulo
     function GetObjSubmoduloHC($submodulo)
     {
          $fileName = "hc_modules/$submodulo/hc_$submodulo.php";
          if(!IncludeFile($fileName))return false;
          $fileName = "hc_modules/$submodulo/hc_$submodulo"."_HTML.php";
          if(!IncludeFile($fileName))return false;
          $className = "$submodulo";
          if(!class_exists($className))return false;
          $className="$submodulo"."_HTML";
          if(!class_exists($className))return false;
          $SubModulo_obj = new $className();
          return $SubModulo_obj;
     }

     //Metodo para Incluir un submodulo de HC
     function IncludeSubmoduloHC($submodulo)
     {
          $fileName = "hc_modules/$submodulo/hc_$submodulo.php";

          if(!IncludeFile($fileName)){
               return "El archivo '$fileName' no existe.";
          }

          $fileName = "hc_modules/$submodulo/hc_$submodulo"."_HTML.php";

          if(!IncludeFile($fileName)){
               return "El archivo '$fileName' no existe.";
          }

          $className = "$submodulo";

          if(!class_exists($className)){
               return "La clase '$className' no existe.";
          }

          $className="$submodulo"."_HTML";

          if(!class_exists($className)){
               return "La clase '$className' no existe.";
          }

          $SUBMODULO= new $className();

          $prefijo='frm_'.$submodulo;

          $SUBMODULO->InicializarSubmodulo($this->datosEvolucion,$this->datosAdministrativos,$this->datosPaciente,$this->datosProfesional,$this->datosResponsable,$this->datosAdicionales,$this->paso,$prefijo,$submodulo,$this->datosEvolucion['hc_modulo'],$this->submodulos_info[$submodulo]['parametros']);
          $salida = $submodulo_obj->GetSalida();
          foreach($submodulo_obj->GetJavaScriptsSubmodulos() as $v=>$k)
          {
               $this->SetJavaScripts($v);
          }
          unset($SUBMODULO);
          return $salida;
     }


     function PartirFecha($fecha)
     {
          $a=explode('-',$fecha);
          $b=explode(' ',$a[2]);
          $c=explode(':',$b[1]);
          $d=explode('.',$c[2]);
          return $a[0].'-'.$a[1].'-'.$b[0].' '.$c[0].':'.$c[1].':'.$d[0];
     }

    /**
     * Cierra una evolucion
     */
     function CerrarEvolucion()
     {
          list($dbconn) = GetDBconn();
          $sql="
               SELECT
                    b.historia_clinica_tipo_cierre_id,
                    b.titulo_mostrar,
                    b.sw_pedir_submodulo_obligatorios
               FROM
                    historias_clinicas_cierres as a,
                    historias_clinicas_tipos_cierres as b
               WHERE
                    a.hc_modulo='".$this->datosEvolucion['hc_modulo']."'
                    AND a.historia_clinica_tipo_cierre_id=b.historia_clinica_tipo_cierre_id
               ORDER BY
                    a.indice_orden;";
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
               $salida_submodulo = $this->ExigirSubmodulosObligatorios();
               if($salida_submodulo=="OK")
               {
                    $salida_submodulo = $this->CerrarHistoria();
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
               $salida_submodulo = $this->ProcesoCerrarHistoria(&$datos);
          }

          if($salida_submodulo === 'HC_CERRAR_OK')
          {
               includelib('malla_validadora');
               $this->salida = $this->VolverListado($_SESSION['HISTORIACLINICA']['RETORNO']['contenedor'], $_SESSION['HISTORIACLINICA']['RETORNO']['modulo'], $_SESSION['HISTORIACLINICA']['RETORNO']['tipo'], $_SESSION['HISTORIACLINICA']['RETORNO']['metodo'],$this->datosEvolucion['ingreso'],$this->datosEvolucion['evolucion_id']);
               unset($_SESSION['HC_DATOS_ADICIONALES'][$this->datosEvolucion['evolucion_id']]);
               unset($_SESSION['HC_DATOS_CONTROL'][$this->datosEvolucion['evolucion_id']]);
               return true;
          }
          else
               $this->salida .= $salida_submodulo;
          return true;
     }

    /**
     * Retorna la conducta
     */
     function GetConducta()
     {
          if(!empty($_REQUEST['conducta']))
          {
               $_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']]['CONDUCTA'] = $_REQUEST['conducta'];
               return $_REQUEST['conducta'];
          }
          elseif(!empty($_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']]['CONDUCTA']))
          {
               return $_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']]['CONDUCTA'];
          }
          return false;
     }

     /**
     * Fija la conducta
     */
     function SetConducta($conducta)
     {
          $_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']]['CONDUCTA'] = $conducta;

          if($conducta != -1)
          {
               if($this->tiposConductas[$conducta]['sw_pedir_submodulo_obligatorios'] === "1")
               {
                    $salida_submodulo = $this->ExigirSubmodulosObligatorios();
                    if($salida_submodulo!="OK")
                    {
                         $this->salida .= $salida_submodulo;
                         $this->salida .= " </div> \n";
                         return true;
                    }
               }


               $salida_submodulo = $this->ExigirSubmodulosConducta($conducta);
               if($salida_submodulo!="OK")
               {
                    $this->salida .= $salida_submodulo;
                    $this->salida .= "  </div> \n";
                    return true;
               }
          }

          if(($salida_submodulo=$this->CerrarHistoria()) === false)
          {
               if(empty($this->error))
               {
                    $this->error = "ERROR en la Clase HC_HTML";
                    $this->mensajeDeError = "Error retornado por el metodo CerrarHistoria";
                    $this->fileError = __FILE__;
                    $this->lineError = __LINE__;
               }
               return false;
          }

          $this->salida = $this->VolverListado($_SESSION['HISTORIACLINICA']['RETORNO']['contenedor'], $_SESSION['HISTORIACLINICA']['RETORNO']['modulo'], $_SESSION['HISTORIACLINICA']['RETORNO']['tipo'], $_SESSION['HISTORIACLINICA']['RETORNO']['metodo'],$this->datosEvolucion['ingreso'],$this->datosEvolucion['evolucion_id']);
          unset($_SESSION['HC_DATOS_ADICIONALES'][$this->datosEvolucion['evolucion_id']]);
          unset($_SESSION['HC_DATOS_CONTROL'][$this->datosEvolucion['evolucion_id']]);
          return true;

     }//Fin SetConducta

     /**
     * Retorna los tipos de conducta
     */
     function GetTiposConductas()
     {
          if(isset($_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']]['TIPOS_CONDUCTA']))
          {
               $this->tiposConductas = $_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']]['TIPOS_CONDUCTA'];
               return $this->tiposConductas;
          }
          if($this->tiposConductas===null)
               return null;
          elseif(empty($this->tiposConductas))
          {
               list($dbconn) = GetDBconn();
               $sql="
                    SELECT
                         b.historia_clinica_tipo_cierre_id,
                         b.titulo_mostrar,
                         b.sw_pedir_submodulo_obligatorios
                    FROM
                         historias_clinicas_cierres as a,
                         historias_clinicas_tipos_cierres as b
                    WHERE
                         a.hc_modulo='".$this->datosEvolucion['hc_modulo']."'
                         AND a.historia_clinica_tipo_cierre_id=b.historia_clinica_tipo_cierre_id
                    ORDER BY
                         a.indice_orden;";
               global $ADODB_FETCH_MODE;
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
               $result = $dbconn->Execute($sql);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               if($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al consultar los tipos de conductas de la HC";
                    $this->mensajeDeError = "SQL : $query ". $dbconn->ErrorMsg();
                    $this->fileError = __FILE__;
                    $this->lineError = __LINE__;
                    return false;
               }
               if ($result->EOF)
               {
                    $this->tiposConductas = null;
                    return null;
               }
               else
               {
                    while($row = $result->FetchRow())
                    {
                         $this->tiposConductas[$row['historia_clinica_tipo_cierre_id']] = $row;
                    }
                    $result->close();
               }
          }
          $_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']]['TIPOS_CONDUCTA'] = $this->tiposConductas;
          return $this->tiposConductas;
     }


     /**
     *
     **/
     function GetParametrosAdicionales()
     {
          if(empty($_SESSION['HC_EVOLUCION'][$this->usuarioConsultante][$this->datosEvolucion['evolucion_id']]['datosAdicionales']['ocultar_menu'])
                    || $this->paso == -1)
          {
               $_SESSION['HC_EVOLUCION'][$this->usuarioConsultante][$this->datosEvolucion['evolucion_id']]['datosAdicionales']['ocultar_menu'] = 1;
               return true;
          }

          if($_SESSION['HC_EVOLUCION'][$this->usuarioConsultante][$this->datosEvolucion['evolucion_id']]['datosAdicionales']['ocultar_menu'] == 1)
               return 'true';
          return 'false';
     }


     /************************************************************************************
     *
     *************************************************************************************/
     function DatosIngresopaciente()
     {
          /***************************************************************************
          * ingresos que estan relacionados con pacientes urgencias
          ****************************************************************************/
          $sql  = "SELECT   IG.ingreso, ";
          $sql .= "         TO_CHAR(IG.fecha_ingreso,'DD/ MM/ YYYY') AS fecha_ingreso, ";
          $sql .= "         PC.paciente_id, ";
          $sql .= "         PC.tipo_id_paciente, ";
          $sql .= "         PC.primer_apellido ||' '|| PC.segundo_apellido AS apellidos,";
          $sql .= "         PC.primer_nombre ||' '|| PC.segundo_nombre AS nombres, ";
          $sql .= "         DE.descripcion, ";
          $sql .= "             EF.descripcion AS estacion, ";
          $sql .= "             'URG' AS tabla, ";
          $sql .= "             IG.fecha_ingreso AS fechaingreso, ";
          $sql .= "             TS.descripcion AS sexo, ";
          $sql .= "             OC.ocupacion_descripcion ";
          $sql .= "FROM     ingresos IG, pacientes_urgencias PU, ";
          $sql .= "         departamentos DE, estaciones_enfermeria EF,tipo_sexo TS, ";
          $sql .= "         pacientes PC LEFT JOIN ocupaciones OC ";
          $sql .= "         ON(OC.ocupacion_id = PC.ocupacion_id ) ";
          $sql .= "WHERE    IG.ingreso  = ".$this->datosEvolucion['ingreso']." ";
          $sql .= "AND      IG.paciente_id = PC.paciente_id ";
          $sql .= "AND      IG.tipo_id_paciente = PC.tipo_id_paciente ";
          $sql .= "AND      IG.ingreso = PU.ingreso ";
          $sql .= "AND      PU.sw_estado = '1' ";
          $sql .= "AND      IG.departamento_actual = DE.departamento ";
          $sql .= "AND      EF.departamento = DE.departamento ";
          $sql .= "AND      PU.estacion_id = EF.estacion_id ";
          $sql .= "AND      PC.sexo_id = TS.sexo_id ";
          $sql .= "ORDER BY 1 ";

          $paciente = array();
          if(!$rst = $this->ConexionBaseDatos($sql))    return true;

          while(!$rst->EOF)
          {
               $paciente = $rst->GetRowAssoc($ToUpper = false);
               $rst->MoveNext();
          }
          $rst->Close();
          if(sizeof($paciente) == 0)
          {
               /***************************************************************************
               * ingresos que estan relacionados con movimientos habitacion
               ****************************************************************************/
               $sql  = "SELECT  IG.ingreso, ";
               $sql .= "            TO_CHAR(IG.fecha_ingreso,'DD/ MM/ YYYY') AS fecha_ingreso, ";
               $sql .= "            PC.paciente_id, ";
               $sql .= "            PC.tipo_id_paciente, ";
               $sql .= "            PC.primer_apellido ||' '|| PC.segundo_apellido AS apellidos,";
               $sql .= "            PC.primer_nombre ||' '|| PC.segundo_nombre AS nombres, ";
               $sql .= "            DE.descripcion, ";
               $sql .= "            EF.descripcion AS estacion, ";
               $sql .= "            CA.pieza, CA.cama, CA.ubicacion, ";
               $sql .= "            'MVH' AS tabla, ";
               $sql .= "            MH.fecha_ingreso AS fechaingreso, ";
               $sql .= "            TS.descripcion AS sexo, ";
               $sql .= "            OC.ocupacion_descripcion ";
               $sql .= "FROM        ingresos IG, "; 
               $sql .= "            movimientos_habitacion MH, camas CA, tipo_sexo TS, ";
               $sql .= "            departamentos DE,estaciones_enfermeria EF, ";
               $sql .= "            pacientes PC LEFT JOIN ocupaciones OC ";
               $sql .= "            ON(OC.ocupacion_id = PC.ocupacion_id ) ";
               $sql .= "WHERE   IG.ingreso = ".$this->datosEvolucion['ingreso']." ";
               $sql .= "AND IG.paciente_id = PC.paciente_id ";
               $sql .= "AND IG.tipo_id_paciente = PC.tipo_id_paciente ";
               $sql .= "AND IG.departamento_actual = DE.departamento ";
               $sql .= "AND MH.estacion_id = EF.estacion_id ";
               $sql .= "AND MH.ingreso = IG.ingreso ";
               $sql .= "AND MH.cama = CA.cama ";
               $sql .= "AND MH.fecha_egreso IS NULL ";
               $sql .= "AND PC.sexo_id = TS.sexo_id ";
               $sql .= "ORDER BY 1 ";

               if(!$rst = $this->ConexionBaseDatos($sql)) return true;

               while(!$rst->EOF)
               {
                    $paciente = $rst->GetRowAssoc($ToUpper = false);
                    $rst->MoveNext();
               }
               $rst->Close();
          }
          if(sizeof($paciente) == 0)
          {
               /***************************************************************************
               * ingresos que estan relacionados con pacientes en Cirugia
               ****************************************************************************/
               $sql  = "SELECT  IG.ingreso, ";
               $sql .= "            TO_CHAR(IG.fecha_ingreso,'DD/ MM/ YYYY') AS fecha_ingreso, ";
               $sql .= "            PC.paciente_id, ";
               $sql .= "            PC.tipo_id_paciente, ";
               $sql .= "            PC.primer_apellido ||' '|| PC.segundo_apellido AS apellidos,";
               $sql .= "            PC.primer_nombre ||' '|| PC.segundo_nombre AS nombres, ";
               $sql .= "            DE.descripcion, ";
               $sql .= "            EF.descripcion AS estacion, ";
               $sql .= "            'EEC' AS tabla, ";
               $sql .= "            EQX.fecha_ingreso AS fechaingreso, ";
               $sql .= "            TS.descripcion AS sexo, ";
               $sql .= "            OC.ocupacion_descripcion, ";
               $sql .= "            QQ.abreviatura, QQ.descripcion AS quirofano ";
               $sql .= "FROM        ingresos IG, cuentas CU, ";
               $sql .= "            estacion_enfermeria_qx_pacientes_ingresados EQX, tipo_sexo TS, ";
               $sql .= "            departamentos DE,estaciones_enfermeria EF, ";
               $sql .= "            pacientes PC LEFT JOIN ocupaciones OC ";
               $sql .= "            ON(OC.ocupacion_id = PC.ocupacion_id ), ";
               $sql .= "            qx_quirofanos_programacion QXQ, qx_quirofanos QQ ";
               $sql .= "WHERE   IG.ingreso = ".$this->datosEvolucion['ingreso']." ";
               $sql .= "AND     IG.paciente_id = PC.paciente_id ";
               $sql .= "AND     IG.tipo_id_paciente = PC.tipo_id_paciente ";
               $sql .= "AND     EQX.departamento = DE.departamento ";
               $sql .= "AND     IG.ingreso = Cu.ingreso ";
               $sql .= "AND     CU.numerodecuenta = EQX.numerodecuenta ";
               $sql .= "AND     EQX.departamento = EF.departamento ";
               $sql .= "AND     PC.sexo_id = TS.sexo_id ";
               $sql .= "AND     QXQ.programacion_id = EQX.programacion_id ";
               $sql .= "AND     QXQ.quirofano_id = QQ.quirofano ";
               $sql .= "AND     QXQ.qx_tipo_reserva_quirofano_id = '3' ";
               $sql .= "ORDER BY 1 ";

               if(!$rst = $this->ConexionBaseDatos($sql)) return true;

               while(!$rst->EOF)
               {
                    $paciente = $rst->GetRowAssoc($ToUpper = false);
                    $rst->MoveNext();
               }
               $rst->Close();
          }
          if(sizeof($paciente) == 0)
          {
               /***************************************************************************
               * ingresos que estan relacionados con pacientes en Consulta Externa
               ****************************************************************************/
               $sql  = "SELECT  IG.ingreso, ";
               $sql .= "            TO_CHAR(IG.fecha_ingreso,'DD/ MM/ YYYY') AS fecha_ingreso, ";
               $sql .= "            IG.fecha_ingreso AS fechaingreso, ";
               $sql .= "            PC.paciente_id, ";
               $sql .= "            PC.tipo_id_paciente, ";
               $sql .= "            PC.primer_apellido ||' '|| PC.segundo_apellido AS apellidos,";
               $sql .= "            PC.primer_nombre ||' '|| PC.segundo_nombre AS nombres, ";
               $sql .= "            DE.descripcion, ";
               $sql .= "            'CEXT' AS tabla, ";
               $sql .= "            TS.descripcion AS sexo, ";
               $sql .= "            OC.ocupacion_descripcion ";
               $sql .= "FROM        ingresos IG, cuentas CU, ";
               $sql .= "            pacientes PC LEFT JOIN ocupaciones OC ";
               $sql .= "            ON(OC.ocupacion_id = PC.ocupacion_id ), ";
               $sql .= "            departamentos DE, tipo_sexo TS ";
               $sql .= "WHERE   IG.ingreso = ".$this->datosEvolucion['ingreso']." ";
               $sql .= "AND     IG.paciente_id = PC.paciente_id ";
               $sql .= "AND     IG.tipo_id_paciente = PC.tipo_id_paciente ";
               $sql .= "AND     IG.departamento_actual = DE.departamento ";
               $sql .= "AND     IG.ingreso = Cu.ingreso ";
               $sql .= "AND     PC.sexo_id = TS.sexo_id ";
               $sql .= "ORDER BY 1 ";

               if(!$rst = $this->ConexionBaseDatos($sql)) return true;

               while(!$rst->EOF)
               {
                    $paciente = $rst->GetRowAssoc($ToUpper = false);
                    $rst->MoveNext();
               }
               $rst->Close();
          }
          return $paciente;
     }

     /************************************************************************************
     *
     *************************************************************************************/
     function DatosAcudiente()
     {
          $sql .= "SELECT   HC.nombre_completo, ";
          $sql .= "             HC.telefono, ";
          $sql .= "             HC.direccion,";
          $sql .= "             TP.descripcion AS parentesco ";
          $sql .= "FROM     tipos_parentescos TP, ";
          $sql .= "             hc_contactos_paciente HC ";
          $sql .= "WHERE    HC.ingreso = ".$this->datosEvolucion['ingreso']." ";
          $sql .= "AND      HC.tipo_parentesco_id = TP.tipo_parentesco_id ";

          if(!$rst = $this->ConexionBaseDatos($sql)) return true;

          $acudiente = array();
          if(!$rst->EOF)
          {
               $acudiente[] = $rst->GetRowAssoc($ToUpper = false);
               $rst->MoveNext();
          }
          $rst->Close();
          return $acudiente;
     }

     /**
     * Calcula los días que lleva hospitalizada una persona, basandose en la fecha de ingreso.
     *
     * @param timestamp fecha de ingreso del paciente
     * @return integer
     * @access Public
     */
     function GetDiasHospitalizacion($fecha_ingreso)
     {
          if(empty($fecha_ingreso)) return null;

          $date1 = date('Y-m-d H:i:s');

          $fecha_in=explode(".",$fecha_ingreso);
          $date2=$fecha_in[0];

          $s = strtotime($date1)-strtotime($date2);
          $d = intval($s/86400);
          $s -= $d*86400;
          $h = intval($s/3600);
          $s -= $h*3600;
          $m = intval($s/60);
          $s -= $m*60;

          if($d>0)
          {
               $dif= "$d  Dias ";
          }
          else
          {
               $dif = "$h : $m  Horas ";
          }
          return $dif;
     }

     /************************************************************************************
     * Funcion que permite realizar la conexion a la base de datos y ejecutar la consulta
     * sql
     *
     * @param string sentencia sql a ejecutar
     * @return rst
     *************************************************************************************/
     function ConexionBaseDatos($sql)
     {
          list($dbconn)=GetDBConn();
          //$dbconn->debug=true;
          $rst = $dbconn->Execute($sql);

          if ($dbconn->ErrorNo() != 0)
          {
               $this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
               return false;
          }
          return $rst;
     }


}//fin de la clase
?>
