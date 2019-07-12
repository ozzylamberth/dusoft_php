<?php
  /** 
  * $Id: hc_UV_PacienteTrabajosAnteriores.php,v 1.1 2009/06/09 19:14:07 hugo Exp $
  * 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  * 
  * $Revision: 1.1 $ 
  * 
  * @autor J gomez
  */
  session_start();
  IncludeClass("LogicaPTA",NULL,"hc","UV_PacienteTrabajosAnteriores");
  IncludeClass("VistaPTA_HTML","HTML","hc","UV_PacienteTrabajosAnteriores");

  class UV_PacienteTrabajosAnteriores extends hc_classModules
  {
    /**
    * Esta funci� Inicializa las variable de la clase
    *
    * @access public
    * @return boolean Para identificar que se realizo.
    */
    function UV_PacienteTrabajosAnteriores()
    {
      return true;
    }
    /**
    * Esta función verifica si este submodulo fue utilizado para la atencion de un paciente.
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
      $ptr = new LogicaPTA();
      $mdl = new VistaPTA_HTML();
      $datos = $this->datosPaciente;
      
      $eps = $ptr->ConsultarEPS_Anterior($datos['tipo_id_paciente'],$datos['paciente_id']);
      $trabajos = $ptr->ConsultarTrabajosAnteriores($datos['tipo_id_paciente'],$datos['paciente_id']);
      $enfermedades = $ptr->ObtenerDatosEnfermedades($datos['tipo_id_paciente'],$datos['paciente_id']);
      
      $this->salida  = $mdl->FormaConsulta($trabajos,$eps,$enfermedades);

      return $this->salida;
    }
    /**
    * Esta metodo captura los datos de la impresi� de la Historia Clinica.
    * @access private
    * @return text Datos HTML de la pantalla.
    */
    function GetReporte_Html()
    {
      $ptr = new LogicaPTA();
      $mdl = new VistaPTA_HTML();
      $datos = $this->datosPaciente;
      
      $eps = $ptr->ConsultarEPS_Anterior($datos['tipo_id_paciente'],$datos['paciente_id']);
      $trabajos = $ptr->ConsultarTrabajosAnteriores($datos['tipo_id_paciente'],$datos['paciente_id']);
      $enfermedades = $ptr->ObtenerDatosEnfermedades($datos['tipo_id_paciente'],$datos['paciente_id']);
      
      $html  = $mdl->FormaHistoria($trabajos,$eps,$enfermedades);
      return $html;
    }
    /**
    * Esta funcion retorna la presentacion del submodulo. 
    * @access public
    * @return boolean true si todo esta OK 
    **/
    function GetForma()
    {
      $pfj = $this->frmPrefijo;
      SessionSetVar("rutaImagenes",GetThemePath());
      $edad = $this->datosPaciente['edad_paciente']['anos'];
     
      $file ='hc_modules/UV_PacienteTrabajosAnteriores/RemoteXajax/PacienteTrabajosAnteriores_Xajax.php';
      $this->SetXajax(array("MostrarEPS_Anteriores_x","MostrarTrabajosAnteriores","MostrarEnfermedades","GuardarEPS_Anteror","GuardarInfo","Llamar_ciudades","GuardarRiesgos_Paciente"),$file);
      
      $Forma_html = new VistaPTA_HTML();
      $consultar = new LogicaPTA();
      $deptos = $consultar->ObtenerDepartamentos();
      $tipos_agentes_riesgo = $consultar->ObtenerTipos_Agentes_de_riesgo();
      
      $this->salida .= "<script language='javascript' src='hc_modules/UV_PacienteTrabajosAnteriores/RemoteXajax/PacienteTrabajosAnteriores.js'></script>";
      $this->salida .= $Forma_html->Forma($this,$deptos,$tipos_agentes_riesgo);
      $this->RegistrarSubmodulo($this->GetVersion());
      return true;
    }
  }
?>