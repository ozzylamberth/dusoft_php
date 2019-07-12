<?php
  /**
  * $Id: hc_Triage_HTML.php,v 1.1 2009/06/09 19:11:18 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  * 
  * $Revision: 1.1 $   
  * @author Hugo F. Manrique
  *
  */
  class Triage_HTML extends Triage
  {
    /**
    * Constructor de la clase
    */
    function Triage_HTML()
    {
      $this->Triage();
      $this->frmError = array();
      $this->error = '';
      $this->empresa = SessionGetVar('SYSTEM_USUARIO_EMPRESA');
      $this->max_edad_pediatrica = ModuloGetVar('','','max_edad_pediatrica');
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
      
      $trg = AutoCarga::factory('TriageSQL', 'classes', 'hc1', 'Triage');
      $mdl = AutoCarga::factory('TriageHTML', 'views', 'hc1', 'Triage');
      $niveles = $signos = $signo = $ocular = $verbal = $motora = array();
      
      $datos = $trg->ObtenerDatosTriage($this->empresa_id,$this->datosPaciente,$request['triage_id']);
      if(!empty($datos))
      {
        $niveles = $trg->ObtenerNivelesTriage();
        $signos = $trg->ObtenerSignosVitales($datos['triage_id']);
        $signo = $trg->ObtenerSignosObligatorios();
        $ocular = $trg->ObtenerRespuestaOcular();
        $verbal = $trg->ObtenerRespuestaVerbal($this->datosPaciente['edad_paciente']['anos'],$this->max_edad_pediatrica);
        $motora = $trg->ObtenerRespuestaMotora($this->datosPaciente['edad_paciente']['anos'],$this->max_edad_pediatrica);
      } 
      switch($request["accion".$this->frmPrefijo])
      {
        case 'IngresarNotificacion':
          $rst = $trg->ActualizarClasificacionTriage($request,UserGetUID());
          $datos = $trg->ObtenerDatosTriage($this->datosAdministrativos['empresa_id'],$this->datosPaciente,$request['triage_id']);

          $mensaje['informacion'] = "LOS DATOS DEL TRIGAE HAN SIDO ACTUALIZADOS CORRECTAMENTE";
          if(!$rst)
            $mensaje['error'] = "HA OCURRIDO UN ERROR ".$trg->ErrMsg();
          else
            $this->RegistrarSubmodulo($this->GetVersion());
          $this->salida .= $mdl->FormaMostrarDatosTriage($action,$datos,$niveles,$signos,$signo,$ocular,$verbal,$motora,$this->datosPaciente['edad_paciente']['anos'],$this->max_edad_pediatrica,$mensaje);
         
        break;
        default:
          if(!empty($datos))
          {
            $action['aceptar'] = ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$this->frmPrefijo=>'IngresarNotificacion',"triage_id"=>$datos['triage_id']));
            $this->salida .= $mdl->FormaMostrarDatosTriage($action,$datos,$niveles,$signos,$signo,$ocular,$verbal,$motora,$this->datosPaciente['edad_paciente']['anos'],$this->max_edad_pediatrica);
          }
          else
          {
            $this->salida .= $mdl->FormaMensajeModulo("EL PACIENTE NO PRESENTA UNA CLASIFICACION INICIAL DE TRIAGE");
          }
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
      return true;
		}
    /**
		* Llama a la funcion que crea un reporte en html para mostrar en el browser
    *
    * @return string
		*/
		function GetConsulta()
		{
      return true;
		}
  }
?>