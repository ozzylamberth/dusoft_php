<?php
/** 
    * $Id: hc_UV_DatosSaludOcupacional.php,v 1.1 2009/06/09 19:13:58 hugo Exp $
    * 
    * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
    * @package IPSOFT-SIIS
    * 
    * $Revision: 1.1 $ 
    * 
    * @autor J gomez
    */

session_start();

IncludeClass("LogicaSO",NULL,"hc","UV_DatosSaludOcupacional");
IncludeClass("VistaSO_HTML","HTML","hc","UV_DatosSaludOcupacional");

class UV_DatosSaludOcupacional extends hc_classModules
{
/**
* Esta funci� Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/
	function UV_DatosSaludOcupacional()
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

	
      function GetConsulta()
      {
//           $signo_HH = new signos_HTML($this);
//           $signo1 = new signos($this);
//           //var_dump($this);          
//           $b_solo = $signo1->ConsultaExamenes(4);
//           $hallazgo_solo = $signo1->ConsultarHallazgo(3);
//           $ConsultaHTML  = $signo_HH->frmConsulta($b_solo,$hallazgo_solo);
//           if($ConsultaHTML == false)
//           {
                return true;
//           }
//           return $ConsultaHTML;
     }


/**
* Esta metodo captura los datos de la impresi� de la Historia Clinica.
* @access private
* @return text Datos HTML de la pantalla.
*/

      function GetReporte_Html()
      {
//           $signo_HH=new signos_HTML($this);
//           $signo1=new signos($this);
//           $b_solo=$signo1->ConsultaExamenes(4);
//           $hallazgo_solo=$signo1->ConsultarHallazgo(3);
//           $imprimir=$signo_HH->frmHistoria($b_solo,$hallazgo_solo);
//           if($imprimir==false)
//           {
                return true;
//           }
//           return $imprimir;
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
        //var_dump($this);
        $file ='hc_modules/UV_DatosSaludOcupacional/RemoteXajax/DatosSaludOcupacional_Xajax.php';
        $this->SetXajax(array("MostrarDatosOcupacion","MostrarDatosEspacio","GuardarInfoO","GuardarInfoE"),$file);
        $this->salida .= "<script language='javascript' src='hc_modules/UV_DatosSaludOcupacional/RemoteXajax/DatosSaludOcupacional.js'></script>";
        $Forma_html=new VistaSO_HTML();
        $consultar=new LogicaSO();
        
        
        //$Lista_ciclos_familiares_seleccionados=$consultar->ConsultaCiclosFamiliaresPaciente($this->datosEvolucion['ingreso'],$this->datosPaciente['tipo_id_paciente'],$this->datosPaciente['paciente_id']);
        //$Lista_ciclos_familiares_observacion=$consultar->ConsultaCiclosObservaciones($this->datosEvolucion['ingreso'],$this->datosPaciente['tipo_id_paciente'],$this->datosPaciente['paciente_id']);
//$this->datosPaciente['paciente_id']
        //$fc=$consultar->ConsultaCiclosFR($this->datosEvolucion['ingreso'],$this->datosPaciente['tipo_id_paciente']);
        //var_dump($Lista_ciclos_familiares);
        //var_dump($Lista_ciclos_familiares_seleccionados);
        $ocupaciones=$consultar->ObtenerOcupaciones();
        $espacios=$consultar->ObtenerEspacios();                    
        $datos_funcionario=$consultar->ObtenerDatosFuncionario('CC','38876783');//$this->datosPaciente['tipo_id_paciente'],$this->datosPaciente['paciente_id']
        var_dump($datos_funcionario);
        //$ocupaciones_paciente=$consultar->ObtenerOcupacionesPaciente;
        //$espacios_paciente=$consultar->ObtenerOcupacionesPaciente;
        $this->salida.=$Forma_html->Forma($this,$ocupaciones,$espacios,$datos_funcionario);
        $this->RegistrarSubmodulo($this->GetVersion());
        return true;
    }

// fin de la clase
}
?>