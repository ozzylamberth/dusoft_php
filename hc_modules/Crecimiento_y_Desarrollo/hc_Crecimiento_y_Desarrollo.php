<?php
/**
* $Id: hc_Crecimiento_y_Desarrollo.php,v 1.2 2010/02/05 21:40:42 alexander Exp $
* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
* @package IPSOFT-SIIS
* 
* 
* Clase Crecimiento_y_Desarrollo.
* $Revision: 1.2 $   
* @author Alexander Biedma
*/
class Crecimiento_y_Desarrollo extends hc_classModules
{
    /**
    * Constructor de la clase
    */
    function Crecimiento_y_Desarrollo()
    {
      $this->frmError = array();
      $this->error = '';
      $this->empresa = SessionGetVar('SYSTEM_USUARIO_EMPRESA');
      $this->user_id = UserGetUID();
    }
    
    /**
		* Esta función retorna los datos de concernientes a la version 
    * del submodulo
		* 
    * @return array
		*/
		function GetVersion()
		{
			$informacion = array
      (
  			'version'=>'1',
  			'subversion'=>'0',
  			'revision'=>'0',
  			'fecha'=>'28/12/2009',
  			'autor'=>'ALEXANDER BIEDMA',
  			'descripcion_cambio' => '',
  			'requiere_sql' => false,
  			'requerimientos_adicionales' => '',
  			'version_kernel' => '1.0'
  		);
      SessionSetVar("GetVersion",$informacion);
			return $informacion;
		}
        
    /**
    * Funcion principal del submodulo
    *
    * @return string
    */
    function GetForma()
    {
      $request = $_REQUEST;
      //$action['volver']=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array()); 
      //$this->salida = $html-> FormaMensajeModulo($action,$mensaje);
      $mdl = AutoCarga::factory('Crecimiento_y_desarrolloSQL', 'classes', 'hc1', 'Crecimiento_y_Desarrollo');
      $html = AutoCarga::factory('Crecimiento_y_desarrolloHTML', 'views', 'hc1', 'Crecimiento_y_Desarrollo');
      $cadena = $this->datosPaciente['edad_completa'];
      $x = explode(':',$cadena);
      $meses = $x['0']*12 + $x['1'];
      
      switch($request["accion"])
      {
        case 'IngresarDatosInscripcion';
        $educacion = $mdl->traerDatosNivelesEducacion($request); 
        $ocupaciones = $mdl->traerDatosOcupaciones($request); 
        $patologias = $mdl->traerPatologias($request);
        $ex_fisica = $mdl->traerExploracionFisica($request);
        $cavidad_oral = $mdl->traerTiposCavidadOral($request);
        $alimentos = $mdl->traerDatosAlimenticios($request);
        $edad = $mdl->traerEdadPaciente($meses);
        $insertar = $mdl->registrarPaciente($this->datosPaciente, $request);          
        
        $datos = $mdl->traerDatos();
        if($insertar)
        {
          $mensaje='Todos los datos se han guardados satisfactoriamente';
          //$action['volver']=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'=>'RegistrarControl')); 
          //$this->salida = $html-> FormaMensajeModulo($action,$mensaje);
        }
        else
        {
            $mensaje='Error no se han podido guardar los datos '.$html->mensajeDeError; 
            //$action['volver']=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'=>'RegistrarControl')); 
            //$this->salida = $html-> FormaMensajeModulo($action,$mensaje);  
        }    
        $action['guardarInscripcion']=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'=>'RegistrarControl'));
        $this->salida.= $html-> FormaMostrarVentanas($action,$this->datosPaciente,$this->evolucion,$datos, $educacion,$ocupaciones,$patologias,$ex_fisica,$cavidad_oral,$alimentos);
          
        break;
        
        case 'RegistrarControl';
          IncludeFileModulo("eventosCrecimiento_y_desarrollo","RemoteXajax","hc","Crecimiento_y_Desarrollo");
          $this->SetXajax(array("MostrarPuntaje","GuardarPuntuacion"),null,"ISO-8859-1");
          $action['calcular_puntaje']=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'=>'MostrarPuntaje'));
          $edad = $mdl->traerEdadPaciente($meses);
          $insertar = $mdl->registrarControl($request);
          if($insertar)
            $mensaje='Todos los datos se han guardados satisfactoriamente';
            //$action['volver']=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'=>'RegistrarControl')); 
            //$this->salida = $html-> FormaMensajeModulo($action,$mensaje);
          
          else
            $mensaje='Error no se han podido guardar los datos '.$html->mensajeDeError;
            //$action['volver']=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'=>'RegistrarControl')); 
            //$this->salida = $html-> FormaMensajeModulo($action,$mensaje);
         
          $action['calcular_puntaje']=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'=>'MostrarPuntaje'));
          //$action['guardarControl']=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'=>'MostrarPuntaje'));
          $traerPuntajes = $mdl->traerPuntajesEscalasAD($meses);
          $this->salida.= $html->FormaMostrarPuntajes($action,$this->datosPaciente, $this->evolucion,$traerPuntajes);     
        
        break;
        
        case 'MostrarPuntaje': 
          IncludeFileModulo("eventosCrecimiento_y_desarrollo","RemoteXajax","hc","Crecimiento_y_Desarrollo");
          $this->SetXajax(array("MostrarPuntaje","GuardarPuntuacion"),null,"ISO-8859-1");
          
          $action['guardarPuntaje']=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'=>'GuardarPuntuacion'));
          //$this->salida.= $html->FormaMostrarPuntajes($action,$this->datosPaciente, $this->evolucion,$traerPuntajes);     
          $this->salida.= $html-> FormaMostrarVentanas($action,$this->datosPaciente,$this->evolucion,$datos, $educacion,$ocupaciones,$patologias,$ex_fisica,$cavidad_oral,$alimentos);
          break;
        case 'GuardarPuntuacion';
          $insertarPuntajes = $mdl->insertarPuntajes($request);
          $this->salida.= $html-> FormaMostrarVentanas($action,$this->datosPaciente,$this->evolucion,$datos, $educacion,$ocupaciones,$patologias,$ex_fisica,$cavidad_oral,$alimentos);
          break;
        
        default: 
          $cadena = $this->datosPaciente['edad_completa'];
          $x = explode(':',$cadena);
          $meses = $x['0']*12 + $x['1'];
          
          if($meses > 84)
          {
             $mensaje = 'LA PERSONA NO TIENE LA EDAD PERMITIDA PARA ENTRAR';
             $this->salida.= $html->FormaMostrarRegistro($action,$this->datosPaciente,$this->evolucion,$datos,$educacion,$ocupaciones,$patologias,$mensaje);
          }
          else 
          {
            $mensaje ="";
            $verificarInscripcion = $mdl->verificarInscripcion(); 
            if(!$verificarInscripcion)
            {
              $action['inscripcion']=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'=>'IngresarDatosInscripcion'));
              $educacion = $mdl->traerDatosNivelesEducacion(); 
              $ocupaciones = $mdl->traerDatosOcupaciones(); 
              $patologias = $mdl->traerPatologias();
              $this->salida.= $html->FormaMostrarRegistro($action,$this->datosPaciente,$this->evolucion,$datos,$educacion,$ocupaciones,$patologias,$mensaje);
            }
            else
            {
              $action['guardarControl']=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'=>'RegistrarControl'));
              $educacion = $mdl->traerDatosNivelesEducacion($request); 
              $ocupaciones = $mdl->traerDatosOcupaciones($request); 
              $patologias = $mdl->traerPatologias($request);
              $ex_fisica = $mdl->traerExploracionFisica($request);
              $cavidad_oral = $mdl->traerTiposCavidadOral($request);
              $alimentos = $mdl->traerDatosAlimenticios($request);
              $edad = $mdl->traerEdadPaciente($meses);
              $datos = $mdl->traerDatos();
              $puntaje = $mdl->traerPuntajes($meses);
              $this->salida.= $html->FormaMostrarVentanas($action,$this->datosPaciente, $this->evolucion,$datos,$educacion,$ocupaciones,$patologias,$ex_fisica,$cavidad_oral,$alimentos,$edad,$puntaje);
            }
          }
          
         /*
          IncludeFileModulo("eventosCrecimiento_y_desarrollo","RemoteXajax","hc","Crecimiento_y_Desarrollo");
          $this->SetXajax(array("MostrarPuntaje","GuardarPuntuacion"),null,"ISO-8859-1");
          
          $action['calcular_puntaje']=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'=>'MostrarPuntaje'));
          $action['guardarPuntaje']=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'=>'GuardarPuntuacion'));
    
          $cadena = $this->datosPaciente['edad_completa'];     
          $x = explode(':',$cadena);
          $meses = $x['0']*12 + $x['1'];
          if($meses > 84)
             $mensaje = 'LA PERSONA NO TIENE LA EDAD PERMITIDA PARA ENTRAR';
          else 
          $mensaje ="";
          //$escalasPuntajes = $mdl->traerEscalasPuntajes($meses);
          $traerPuntajes = $mdl->traerPuntajesEscalasAD($meses);
          $insertarPuntajes = $mdl->insertarPuntajes($request);
          $this->salida.= $html->FormaMostrarPuntajes($action,$this->datosPaciente, $this->evolucion,$traerPuntajes,$insertarPuntajes);
          */
          
      }
      return $this->salida;
    }
 }
?>