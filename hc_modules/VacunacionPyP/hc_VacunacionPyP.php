<?php
/**
* $Id: hc_VacunacionPyP.php,v 1.1 2009/12/03 14:59:13 alexander Exp $
* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
* @package IPSOFT-SIIS
* 
* 
* Clase VacunacionPyP.
* $Revision: 1.1 $   
* @author Alexander Biedma
*/
class VacunacionPyP extends hc_classModules
{
    /**
    * Constructor de la clase
    */
    function VacunacionPyP()
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
  			'fecha'=>'06/10/2009',
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
      
      $mdl = AutoCarga::factory('VacunacionSQL', 'classes', 'hc1', 'VacunacionPyP');
      $html = AutoCarga::factory('VacunacionHTML', 'views', 'hc1', 'VacunacionPyP');

      switch($request["accion"])
      {
        case 'verDosisVacunas':             
          break;
        case 'registrarDosisVacuna';
          break;
        default: 
          IncludeFileModulo("eventosVacunacion","RemoteXajax","hc","VacunacionPyP");
          $this->SetXajax(array("verDosisVacunas", "registrarDosisVacuna","GuardarAplicacion"),null,"ISO-8859-1");
          
          $action['ver_datos']=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'=>'verDosisVacunas'));
          $action['aplicar']=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'=>'registrarDosisVacuna'));
          $action['guardar']=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'=>'GuardarAplicacion'));

          $vacunas = $mdl->traerPosiblesVacunas($this->datosPaciente);  
          $historial = $mdl->historialVacunacion($this->datosPaciente);  

          $this->salida.= $html->FormaMostrarVacunas($vacunas,$this->datosPaciente,$action, $this->evolucion, $historial);
          //$this->salida .= "<pre>".print_r($request,true)."</pre>";
          
        break;      
      }
      
      return $this->salida;
    }
 }
?>