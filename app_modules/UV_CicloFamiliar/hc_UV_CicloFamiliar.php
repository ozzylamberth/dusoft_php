<?php
/** 
    * $Id: hc_UV_CicloFamiliar.php,v 1.1 2008/09/03 18:50:27 hugo Exp $
    * 
    * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
    * @package IPSOFT-SIIS
    * 
    * $Revision: 1.1 $ 
    * 
    * @autor J gomez
    */

session_start();

IncludeClass("LogicaCF",NULL,"hc","UV_CicloFamiliar");
IncludeClass("VistaCF_HTML","HTML","hc","UV_CicloFamiliar");

class UV_CicloFamiliar extends hc_classModules
{
/**
* Esta funci� Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/
	function UV_CicloFamiliar()
	{
		return true;
	}

/**
* Esta funci� verifica si este submodulo fue utilizado para la atencion de un paciente.
*
* @access private
* @return text Datos HTML de la pantalla.
*/
    function GetEstado()
    {
      return true;
    }
    /**
    *
    */
    function GetConsulta()
    {
      $html = "";
      $lf = new LogicaCF();
      $datos = $lf->ObtenerDatosCicloFamiliar($this->ingreso);
      if(!empty($datos))
      {
        $indiv = $lf->ObtenerDatosCicloIndividual($this->ingreso);
        $md = new VistaCF_HTML();
        $html = $md->FormaCiclosHistoria($datos,$indiv);
      }
      $this->salida = $html;
      return true;
    }
    /**
    * Esta metodo captura los datos de la impresi� de la Historia Clinica.
    * @access private
    * @return text Datos HTML de la pantalla.
    */
    function GetReporte_Html()
    {
      $html = "";
      $lf = new LogicaCF();
      $datos = $lf->ObtenerDatosCicloFamiliar($this->ingreso);
      if(!empty($datos))
      {
        $indiv = $lf->ObtenerDatosCicloIndividual($this->ingreso);
        $md = new VistaCF_HTML();
        $html = $md->FormaCiclosHistoria($datos,$indiv);
      }
      return $html;
    }
    /**
    * Esta funcion retorna la presentacion del submodulo. 
    * @access public
    * @return boolean true si todo esta OK 
    **/
    function GetForma()
    {
      $pfj=$this->frmPrefijo;
      SessionSetVar("rutaImagenes",GetThemePath());
      $edad=$this->datosPaciente['edad_paciente']['anos'];

      $file ='hc_modules/UV_CicloFamiliar/RemoteXajax/CicloFamiliar_Xajax.php';
      $this->SetXajax(array("Prueba","GuardarObsCvf","GuardarFR","SeleccionarSFR"),$file);
      $this->salida .= "<script language='javascript' src='hc_modules/UV_CicloFamiliar/RemoteXajax/CicloFamiliar.js'></script>";
      $Forma_html=new VistaCF_HTML();
      $consultar=new LogicaCF();
      $CicloIndividual=$consultar->ObtenerCicloIndividual($edad);
      $Lista_ciclos_familiares=$consultar->ConsultaCicloFamiliares();
      $Lista_ciclos_familiares_seleccionados=$consultar->ConsultaCiclosFamiliaresPaciente($this->datosEvolucion['ingreso'],$this->datosPaciente['tipo_id_paciente'],$this->datosPaciente['paciente_id']);
      $Lista_ciclos_familiares_observacion=$consultar->ConsultaCiclosObservaciones($this->datosEvolucion['ingreso'],$this->datosPaciente['tipo_id_paciente'],$this->datosPaciente['paciente_id']);
      $fc=$consultar->ConsultaCiclosFR($this->datosEvolucion['ingreso'],$this->datosPaciente['tipo_id_paciente'],$this->datosPaciente['paciente_id']);
      $factores_riesgo=$consultar->ObtenerFactoresRiesgoPaciente($edad,$this->datosEvolucion['ingreso'],$this->datosPaciente['tipo_id_paciente'],$this->datosPaciente['paciente_id']);
      
      $this->salida.=$Forma_html->Forma($this,$CicloIndividual,$Lista_ciclos_familiares,$Lista_ciclos_familiares_seleccionados,$Lista_ciclos_familiares_observacion,$fc,$factores_riesgo);
      $this->RegistrarSubmodulo($this->GetVersion());
      return true;
    }
  }
?>