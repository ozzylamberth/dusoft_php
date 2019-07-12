<?php
/*
* Clase maestra del manejador de submodulos
*
* Esta clase contiene los metodos de acceso publico que retornan la informacion
*
* @access public
*/

class hc_classModules
{
  // var $bodega; ??????????
  //--------------------------------------------

    var $datosEvolucion=array();
    var $datosPaciente=array();
    var $datosProfesional=array();
    var $datosAdministrativos=array();
    var $datosResponsable=array();
    var $datosAdicionales=array();
    var $datosAfiliados=array();
    var $parametro;

    var $paso;
    var $frmPrefijo;
    var $submodulo;
    var $hc_modulo;

    var $salida='';

    var $titulo='';

    var $error='';
    var $mensajeDeError='';
    var $frmError=array();
    var $javas=array();
    var $javaScripts='';
    var $themeVars=array(); //??????????

    var $evolucion;
    var $estado;
    var $ingreso;
    var $cuenta;
    var $plan_id;

    var $empresa_id;
    var $centro_utilidad;
    var $unidad_funcional;
    var $departamento;
    var $servicio;
    var $estacion_id;

    //alias para compatibilidad
    var $estacion;
    var $especialidad;
    var $especialidades_SQL_IN;
    var $tipoFinalidad;
    var $plan;
    var $paciente;
    var $tipoidpaciente;
    var $usuario_id;
    var $tipo_profesional;

    function hc_classModules()
    {
        IncludeClass('AutoCarga');
        IncludeClass('ConexionBD');
        //$this->IncludeJSIncludeJS("Calendario");
        return true;
    }

    function RegistrarSubmodulo($DatosVersion=array('version'=>'1','subversion'=>'0'))
    {
        list($dbconn) = GetDBconn();
        $sql  = "DELETE FROM hc_evoluciones_submodulos WHERE evolucion_id = ".$this->evolucion." AND submodulo = '".$this->submodulo."'; ";
        $sql .= "INSERT INTO hc_evoluciones_submodulos(ingreso,evolucion_id,submodulo,version,subversion)
                        VALUES(".$this->ingreso.",".$this->evolucion.",'".$this->submodulo."','$DatosVersion[version]','$DatosVersion[subversion]')";

        $dbconn->Execute($sql);

        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        else
        {
            return true;
        }
    }
    /**
    * Funcion donde se obtiene la informacion de un afiliado
    *
    * @return array
    */
    function ObtenerInformacionAfiliado()
    { 
      $paciente = $this->datosPaciente;
      $paciente['plan_id'] = $this->plan_id;
      $paciente['plan_id'];
      IncludeClass('ConexionBD');
      IncludeClass('AutoCarga');
      $inp = AutoCarga::factory('InformacionPacientes');
      $datos = $inp->ValidarInformacion($paciente);
          
      if(is_array($datos))
      {
        if($datos['semestre'] && !$datos['estamento_id'])
          $datos['descripcion_estamento'] = "Estudiante";
      }
      else if($datos == 3)
      {
        $sql  = "SELECT AD.afiliado_tipo_id AS tipo_id_paciente , ";
        $sql .= "       AD.afiliado_id AS paciente_id, ";
        $sql .= "       AD.primer_apellido    , ";
        $sql .= "       AD.segundo_apellido   , ";
        $sql .= "       AD.primer_nombre  , ";
        $sql .= "       AD.segundo_nombre     , ";
        $sql .= "       AF.eps_tipo_afiliado_id AS tipo_afiliado_id, ";
        $sql .= "       AC.estamento_id ";
        $sql .= "FROM   eps_afiliados_datos AD,";
        $sql .= "       eps_afiliados AF LEFT JOIN ";
        $sql .= "       eps_afiliados_cotizantes AC ";
        $sql .= "       ON( ";
        $sql .= "         AC.eps_afiliacion_id = AF.eps_afiliacion_id  AND ";
        $sql .= "         AC.afiliado_tipo_id = AF.afiliado_tipo_id AND ";
        $sql .= "         AC.afiliado_id = AF.afiliado_id ";
        $sql .= "       ) ";
        $sql .= "WHERE  AD.afiliado_tipo_id = '".$paciente['tipo_id_paciente']."' ";
        $sql .= "AND    AD.afiliado_id = '".$paciente['paciente_id']."' ";
        $sql .= "AND    AD.afiliado_tipo_id = AF.afiliado_tipo_id ";
        $sql .= "AND    AD.afiliado_id = AF.afiliado_id ";
        
        if(!$rst = $this->ConexionBaseDatos($sql)) return false;

        $datos = array();
        while(!$rst->EOF)
        {
          $datos = $rst->GetRowAssoc($ToUpper = false);
          $rst->MoveNext();
        }
        $rst->Close();
      }
      
      if(!$datos['descripcion_estamento'])
      {
        $sql  = "SELECT descripcion_estamento ";
        $sql .= "FROM   eps_estamentos ";
        $sql .= "WHERE  estamento_id = '".$datos['estamento_id']."' ";

        if(!$rst = $this->ConexionBaseDatos($sql)) return false;

        if(!$rst->EOF)
        {
          $datos['descripcion_estamento'] = $rst->fields[0];
          $rst->MoveNext();
        }
        $rst->Close();
      }
      
      $sql  = "SELECT descripcion_eps_tipo_afiliado ";
      $sql .= "FROM   eps_tipos_afiliados ";
      $sql .= "WHERE  eps_tipo_afiliado_id = '".$datos['tipo_afiliado_id']."' ";

      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

      while (!$rst->EOF)
      {
        $datos['tipo_paciente'] = $rst->fields[0];
        $rst->MoveNext();
      }
      $rst->Close();
        
      return $datos;
    }

    function InicializarSubmodulo($datosEvolucion,$datosAdministrativos,$datosPaciente,$datosProfesional,$datosResponsable,$datosAdicionales,$paso,$prefijo,$submodulo,$hc_modulo,$titulo,$parametros)
    {
          $this->datosEvolucion = $datosEvolucion;
          $this->evolucion = $datosEvolucion['evolucion_id'];
          $this->estado = $datosEvolucion['estado'];
          $this->ingreso = $datosEvolucion['ingreso'];
          $this->cuenta = $datosEvolucion['numerodecuenta'];
          $this->estacion_id = $datosEvolucion['estacion_id'];

          $this->datosPaciente = $datosPaciente;
          $this->datosProfesional = $datosProfesional;
          $this->tipo_profesional = GetTipoProfesional(UserGetUID());
          $this->especialidad = NULL;
          
          if(!empty($this->datosProfesional['especialidades']))
          {
            $this->especialidad = $this->datosProfesional['especialidades'][0];
               for($i=0; $i<sizeof($this->datosProfesional['especialidades']); $i++)
               {
                    if($i == 0){ $cadena = "'".$this->datosProfesional['especialidades'][$i]."'"; }
                    else{ $cadena .= ",'".$this->datosProfesional['especialidades'][$i]."'"; }
               }
               $this->especialidades_SQL_IN = $cadena;
          }

          $this->datosAdministrativos = $datosAdministrativos;
          $this->empresa_id = $this->datosAdministrativos['empresa_id'];
          $this->centro_utilidad = $this->datosAdministrativos['centro_utilidad'];
          $this->unidad_funcional = $this->datosAdministrativos['unidad_funcional'];
          $this->departamento = $this->datosEvolucion['departamento'];
          $this->servicio = $this->datosAdministrativos['servicio'];

          $this->datosResponsable = $datosResponsable;
          $this->plan_id = $this->datosResponsable['plan_id'];

          $this->datosAdicionales = $datosAdicionales;

          $this->paso = $paso;
          $this->frmPrefijo = $prefijo;
          $this->submodulo = $submodulo;
          $this->hc_modulo = $hc_modulo;
          $this->parametro = $parametros;

          $this->salida = '';

          $this->error = '';
          $this->mensajeDeError = '';
          $this->frmError = array();

          $this->parametros = ExplodeArrayAssoc($parametros);

          $this->titulo = $titulo;

          //alias para compatibilidad
          $this->estacion = $this->estacion_id;
          $this->tipoFinalidad=$datosAdicionales['tipoFinalidad'];
          $this->plan=$this->plan_id;
          $this->paciente = $this->datosPaciente['paciente_id'];
          $this->tipoidpaciente = $this->datosPaciente['tipo_id_paciente'];
          $this->usuario_id=UserGetUID();
          $this->tipo_profesional = GetTipoProfesional(UserGetUID());

          //$this->datosAfiliados = $this->ObtenerInformacionAfiliado();
          
          list($dbconn) = GetDBconn();
          $sql="SELECT servicio FROM departamentos WHERE departamento='".$this->departamento."';";
          $resultado = $dbconn->Execute($sql);

          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
               //die("No se pudo obtener el servicio en hc_classmodules.class.php".$dbconn->ErrorMsg());
          }

          if(!$resultado->EOF){
               list($this->servicio)=$resultado->FetchRow();
          }
          $resultado->Close();

          $sql="SELECT a.numerodecuenta FROM cuentas as a, ingresos as b
          WHERE b.ingreso=".$this->ingreso."
          AND a.ingreso=b.ingreso
          AND a.estado='1';";

          $resultado = $dbconn->Execute($sql);

          if ($dbconn->ErrorNo() != 0) {
               die("Error al intentar obtener el numero de cuenta en hc_classmodules.class.php".$dbconn->ErrorMsg());
          }

          if(!$resultado->EOF)
                    {
                        list($this->cuenta)=$resultado->FetchRow();
                         $resultado->Close();
                    }
                    else
                    {
                        $sql="SELECT a.numerodecuenta FROM cuentas as a, ingresos as b
                        WHERE b.ingreso=".$this->ingreso."
                        AND a.ingreso=b.ingreso ORDER BY a.numerodecuenta DESC;";
                        $resultado = $dbconn->Execute($sql);
                            if ($dbconn->ErrorNo() != 0)
                            {
                                die("Error al intentar obtener el numero de cuenta en hc_classmodules.class.php".$dbconn->ErrorMsg());
                            }
                            if(!$resultado->EOF)
                            {
                                list($this->cuenta)=$resultado->FetchRow();
                                $resultado->Close();
                            }
                            else
                            {
                                $this->cuenta=0;
                            }
                    }
                    return true;
    }

    //Metodo anterior (en migracion)
    function InitSubmodulo($datosEvolucion,$paso,$prefijo,$datosPaciente,$tipo_finalidad,$estacion,$bodega,$sw_siquiatria,$hc_modulo,$especialidad,$QXcumplimiento=0,$thisSubmodulo,$datosResponsable,$datosAdministrativos,$parametros)
    {
          $this->error='';
          $this->mensajeDeError='';
          $this->datosEvolucion=$datosEvolucion;
          $this->datosPaciente=$datosPaciente;
          $this->evolucion=$datosEvolucion['evolucion_id'];
          $this->paso=$paso;
          $this->parametro = $parametros;
          $this->estado=$datosEvolucion['estado'];
          $this->ingreso=$datosEvolucion['ingreso'];
          $this->usuario_id=UserGetUID();
          $this->tipo_profesional=GetTipoProfesional(UserGetUID());
          $this->departamento=$datosEvolucion['departamento'];
          $this->datosResponsable = $datosResponsable;
          $this->plan_id = $this->datosResponsable['plan_id'];
          $this->frmPrefijo=$prefijo;
          $this->paciente=$datosPaciente['paciente_id'];
          $this->tipoidpaciente=$datosPaciente['tipo_id_paciente'];
          $this->tipoFinalidad=$tipo_finalidad;
          $this->estacion=$estacion;
          $this->bodega=$bodega;
          $this->sw_siquiatria=$sw_siquiatria;
          $this->hc_modulo=$hc_modulo;
          $this->especialidad=$especialidad;
          $this->InicializarValidador();
          $this->QXcumplimiento=$QXcumplimiento;
          $this->submodulo=$thisSubmodulo;
          $this->cuenta = $datosEvolucion['numerodecuenta'];

          list($dbconn) = GetDBconn();
          $sql="SELECT servicio FROM departamentos WHERE departamento='".$this->departamento."';";
          $resultado = $dbconn->Execute($sql);

          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
               //die("No se pudo obtener el servicio en hc_classmodules.class.php".$dbconn->ErrorMsg());
          }

          if ($dbconn->ErrorNo() != 0) {
               die("No se pudo obtener el servicio en hc_classmodules.class.php".$dbconn->ErrorMsg());
          }

          if(!$resultado->EOF){
               list($this->servicio)=$resultado->FetchRow();
          }
          $resultado->Close();

          $sql="SELECT a.numerodecuenta FROM cuentas as a, ingresos as b
          WHERE b.ingreso=".$this->ingreso."
          AND a.ingreso=b.ingreso
         ;";

          $resultado = $dbconn->Execute($sql);

          if ($dbconn->ErrorNo() != 0) {
               die("Error al intentar obtener el numero de cuenta en hc_classmodules.class.php".$dbconn->ErrorMsg());
          }

          if(!$resultado->EOF){
               list($this->cuenta)=$resultado->FetchRow();
          }else{
               $this->cuenta=0;
          }

          $resultado->Close();

          return true;
    }

    function InicializarValidador()
    {
        if(is_object($this->Validador)){
            unset($this->Validador);
        }
//        $this->Validador = new Validador;
        return true;
    }

    function Err()
    {
        return $this->error;
    }

    function ErrMsg()
    {
        return $this->mensajeDeError;
    }

    function GetSalida()
    {
        if($this->estado){
            if(!$this->GetForma()){
                return $this->MensajeErrorSubmodulo($this->submodulo,$this->Err(),$this->ErrMsg());
                }
        }else{
            if(!$this->GetConsulta()){
                return $this->MensajeErrorSubmodulo($this->submodulo,$this->Err(),$this->ErrMsg());
            }
        }
        return $this->salida;
    }
    /**
    *
    */
    function SetJavaScripts($Java)
    {
        $this->javas[$Java]=1;
        return true;
    }
    /**
    * Metodo para incorporar la libreria de xajax al sistema
    *
    * @params array $func Nombres de las funciones a registrar por xajax
    * @params string $file Ruta del archivo a incluir
    * @return boolean
    **/
    function SetXajax($func,$file=null,$encode = "UTF-8")
    {
      global $xajax;
      list($xajax) = getXajax();

      $xajax->setCharEncoding($encode);
      if($encode != "UTF-8")
        $xajax->setFlag("decodeUTF8Input", true);
      
      foreach($func as $key => $xfunc)
        $xajax->registerFunction($xfunc,$file);

      $xajax->processRequest();
      return true;
    }

    function GetJavaScriptsSubmodulos()
    {
      global $xajax;
      if(is_object($xajax)) $this->javas .= $xajax->printJavascript('classes/xajax/');

      return $this->javas;
    }
    /**
    */
    function MensajeErrorSubmodulo($submodulo='',$Err='',$ErrMsg='',$TituloVentana='')
    {
          if(empty($Err) && empty($ErrMsg)){
               $Err="El submodulo retorno \"FALSE\"";
               $ErrMsg="Reporte este evento al personal encargado de soporte.";
          }
          else
          {
               if(empty($Err))
               {
                    $Err="&nbsp;";
               }

               if(empty($ErrMsg))
               {
                    $ErrMsg="&nbsp;";
               }
          }
          if(!empty($TituloVentana))
          {
               $TituloVentana = "Mensaje retornado por el submodulo $submodulo";
          }
          $salida .= ThemeAbrirTablaSubModulo("Mensaje retornado por el submodulo $submodulo");
          $salida .= "<table align=\"center\" width=\"100%\" border=\"0\">\n";
          $salida .= "    <tr align='center'>\n";
          $salida .= "        <td>\n";
          $salida .= "          <label class=\"titulo3_error\">".strtoupper($Err)."</label>";
          $salida .= "        </td>\n";
          $salida .= "    </tr>\n";
          $salida .= "    <tr align='center'>\n";
          $salida .= "        <td>\n";
          $salida .= "          <label class=\"titulo3\">$ErrMsg</label>";
          $salida .= "        </td>\n";
          $salida .= "    </tr>\n";
          $salida .= "    </table>\n";
          $salida .= ThemeCerrarTablaSubModulo();
          return $salida;
    }
    /**
    * Funcion que permite realizar la conexion a la base de datos y ejecutar la
    * consulta sql
    *
    * @param string $sql sentencia sql a ejecutar
    * @param boolean $asoc Indica el modo en el cual se hara la ejecucion del query,
    *                      por defecto es false
    * @return object $rst
    */
    function ConexionBaseDatos($sql,$asoc = false)
    {
      GLOBAL $ADODB_FETCH_MODE;
      list($dbconn)=GetDBConn();
      //$dbconn->debug = true;

      if($asoc) $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

      $rst = $dbconn->Execute($sql);

      if($asoc) $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
      
      if ($dbconn->ErrorNo() != 0)
      {
        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
        $this->mensajeDeError = $dbconn->ErrorMsg();
        return false;
      }
      return $rst;
    }
}

?>