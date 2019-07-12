<?php
/** 
    * $Id: ExamenFisico.php,v 1.2 2007/10/12 14:40:56 jgomez Exp $
    * 
    * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
    * @package IPSOFT-SIIS
    * 
    * $Revision: 1.2 $ 
    * 
    * @autor J gomez
    */

session_start();

IncludeClass("LogicaAF",NULL,"hc","UV_FormulacionAntecedentes");
IncludeClass("VistaAF_HTML","HTML","hc","UV_FormulacionAntecedentes");

class UV_FormulacionAntecedentes extends hc_classModules
{
/**
* Esta funci� Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/
	function UV_FormulacionAntecedentes()
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
      $md = new LogicaAF();
      $medicamentos = $md->Busqueda_Medicamentos_Usuario($this->datosPaciente['tipo_id_paciente'],$this->datosPaciente['paciente_id'],$this->ingreso);
      
      if(!empty($medicamentos))
      {
        $frm = new VistaAF_HTML();
        $html = $frm->FormaMedicamentosFormulados($medicamentos);
        $this->salida = $html;
      }
      return true;
    }
    /**
    * Esta metodo captura los datos de la impresi� de la Historia Clinica.
    * @access private
    * @return text Datos HTML de la pantalla.
    */
    function GetReporte_Html()
    {
      $md = new LogicaAF();
      $medicamentos = $md->Busqueda_Medicamentos_Usuario($this->datosPaciente['tipo_id_paciente'],$this->datosPaciente['paciente_id'],$this->ingreso);
      
      $html = "".$md->Error['MensajeError'];
      if(!empty($medicamentos))
      {
        $frm = new VistaAF_HTML();
        $html = $frm->FormaMedicamentosFormuladosHTML($medicamentos);
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
        $file ='hc_modules/UV_FormulacionAntecedentes/RemoteXajax/FornulacionAntecedentes_Xajax.php';
        $this->SetXajax(array("datos_medicamentoUp","buscar_medicamento","datos_medicamento","GuardarMedNoformu","ModMedNoformu","Insertar_Medicamentos"),$file);
        $this->salida .= "<script language='javascript' src='hc_modules/UV_FormulacionAntecedentes/RemoteXajax/FornulacionAntecedentes.js'></script>";
        $Forma_html=new VistaAF_HTML();
        $consultar=new LogicaAF();
        $Lista_medicamentos_usuario=$consultar->Busqueda_Medicamentos_Usuario($this->datosPaciente['tipo_id_paciente'],$this->datosPaciente['paciente_id']);

        $accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Getforma'));
        $accion2=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Getforma1'));
        //var_dump($Lista_medicamentos_usuario);
        //var_dump($Lista_ciclos_familiares_seleccionados);
        //echo "aqui esot".$_REQUEST['accion'];
        if($_REQUEST['accion'.$pfj]=='Getforma')
        {
            $this->salida.=$Forma_html->Forma_adicionar_medicamento_nf($this,$accion2);
        }
        else
        {
            $this->salida.=$Forma_html->Forma($this,$Lista_medicamentos_usuario,$accion1);
        }    
        $this->RegistrarSubmodulo($this->GetVersion());
        return true;
    }


// fin de la clase
}
?>