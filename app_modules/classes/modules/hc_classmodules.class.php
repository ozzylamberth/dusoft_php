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
    var $parametros;    
    
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
    var $tipoFinalidad;
    var $plan;
    var $paciente;
    var $tipoidpaciente;    
    var $usuario_id;
    var $tipo_profesional;
    
    function hc_classModules()
    {
        return true;
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
          
          $this->salida = '';
     
          $this->error = '';
          $this->mensajeDeError = '';
          $this->frmError = array();   
          
          $this->parametros = ExplodeArrayAssoc($parametros);
          
          $this->titulo = $titulo;
          
          //alias para compatibilidad    
          $this->estacion = $this->estacion_id;
          $this->especialidad = $_SESSION['HC_DATOS_CONTROL'][$this->evolucion]['ESPECIALIDAD'];        
          $this->tipoFinalidad=$datosAdicionales['tipoFinalidad'];
          $this->plan=$this->plan_id;
          $this->paciente = $this->datosPaciente['paciente_id'];
          $this->tipoidpaciente = $this->datosPaciente['tipo_id_paciente'];
          $this->usuario_id=UserGetUID();
          $this->tipo_profesional = GetTipoProfesional(UserGetUID());
          
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
    function InitSubmodulo($datosEvolucion,$paso,$prefijo,$datosPaciente,$tipo_finalidad,$estacion,$bodega,$sw_siquiatria,$hc_modulo,$especialidad,$QXcumplimiento=0,$thisSubmodulo,$datosResponsable,$datosAdministrativos)
    {
          $this->error='';
          $this->mensajeDeError='';
          $this->datosEvolucion=$datosEvolucion;
          $this->datosPaciente=$datosPaciente;
          $this->evolucion=$datosEvolucion['evolucion_id'];
          $this->paso=$paso;
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
                return MensajeErrorSubmodulo($this->submodulo,$this->Err(),$this->ErrMsg());
                }
        }else{
            if(!$this->GetConsulta()){
                return MensajeErrorSubmodulo($this->submodulo,$this->Err(),$this->ErrMsg());
            }
        }
        return $this->salida;
    }


    function SetJavaScripts($Java)
    {
        $this->javas[$Java]=1;
        return true;
    }


    function GetJavaScriptsSubmodulos()
    {
        return $this->javas;
    }
    
}

?>
