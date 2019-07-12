<?php
  /**
  * $Id: hc_Faneras.php,v 1.1 2009/11/06 14:42:11 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  * 
  * 
  * Clase Faneras.
  * $Revision: 1.1 $   
  * @author Hugo F. Manrique
  */
  class Faneras extends hc_classModules
  {
    /**
    * Constructor de la clase
    */
    function Faneras()
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
  			'fecha'=>'09/06/2009',
  			'autor'=>'HUGO F. MANRIQUE',
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
      
      $fnr = AutoCarga::factory('FanerasSQL', 'classes', 'hc1', 'Faneras');
      $mdl = AutoCarga::factory('FanerasHTML', 'views', 'hc1', 'Faneras');

      switch($request["accion"])
      {
        case 'IngresarNotificacion':  break;
        default:
          IncludeFileModulo("Faneras","RemoteXajax","hc","Faneras");
          $this->SetXajax(array("IngresarClasificacion","EliminarClasificacion","IngresarPuntajeEva"),null,"ISO-8859-1");
          $puntajes = $fnr->ObtenerPuntajesEva();
          $coordenadas = $fnr->ObtenerCoordenadas();
          $sensibilidad = $fnr->ObtenerSensibilidad();
          $puntajeEvolucion = $fnr->ObtenerPuntajesEvaEvolucion($this->evolucion);
          $sectores = $fnr->ObtenerClasificacion($this->evolucion);
          $action['aceptar'] = ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$this->frmPrefijo=>'IngresarNotificacion',"triage_id"=>$datos['triage_id']));
          
          $this->salida .= $mdl->FormaMostrarPielFaneras($action,$coordenadas,$sensibilidad,$sectores,$puntajes,$puntajeEvolucion,$this->evolucion,$this->ingreso);
        break;        
      }
      return $this->salida;
    }
    /**
		* Esta metodo captura los datos de la impresión de la Historia Clinica.
		* 
		* @return string
		*/
		function GetReporte_Html()
		{
      $fnr = AutoCarga::factory('FanerasSQL', 'classes', 'hc1', 'Faneras');

      $puntajeEvolucion = $fnr->ObtenerPuntajesEvaEvolucion($this->evolucion);
      $sectores = $fnr->ObtenerClasificacion($this->evolucion);
    	
      if(!empty($puntajeEvolucion) || !empty($sectores))
      {
        $mdl = AutoCarga::factory('FanerasHTML', 'views', 'hc1', 'Faneras');
        $puntajes = $fnr->ObtenerPuntajesEva();
        $coordenadas = $fnr->ObtenerCoordenadas();
        $sensibilidad = $fnr->ObtenerSensibilidad();
        
        $imprimir = $mdl->FormaHistoria($coordenadas,$sensibilidad,$sectores,$puntajes,$puntajeEvolucion);
        return $imprimir;
      }
      
      return true;
		}
    /**
		* Llama a la funcion que crea un reporte en html para mostrar en el browser
    *
    * @return string
		*/
		function GetConsulta()
		{
      $fnr = AutoCarga::factory('FanerasSQL', 'classes', 'hc1', 'Faneras');

      $puntajeEvolucion = $fnr->ObtenerPuntajesEvaEvolucion($this->evolucion);
      $sectores = $fnr->ObtenerClasificacion($this->evolucion);
    	
      if(!empty($puntajeEvolucion) || !empty($sectores))
      {
        $mdl = AutoCarga::factory('FanerasHTML', 'views', 'hc1', 'Faneras');
        $puntajes = $fnr->ObtenerPuntajesEva();
        $coordenadas = $fnr->ObtenerCoordenadas();
        $sensibilidad = $fnr->ObtenerSensibilidad();
        
        $this->salida = $mdl->FormaHistoriaModulo($coordenadas,$sensibilidad,$sectores,$puntajes,$puntajeEvolucion);
        return $this->salida;
      }
      
      return true;
		}
  }
?>