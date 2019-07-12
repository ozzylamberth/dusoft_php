<?php
/**
* Submodulo de Areas de Conducta (Examen Fisico)
*
* @author Tizziano Perea
* @version 1.0
* @package SIIS
* $Id: hc_Conducta_ExamenFisico.php,v 1.1 2007/11/30 20:41:12 tizziano Exp $
*/



IncludeClass("Examen", null, "hc", "Conducta_ExamenFisico");
IncludeClass("Examen_HTML", "html", "hc", "Conducta_ExamenFisico");

class Conducta_ExamenFisico extends hc_classModules
{
     
     /**
     * Constructor
     *
     * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
     * @access public
     */
     function Conducta_ExamenFisico()
	{
		$this->limit=GetLimitBrowser();
		return true;
	}
	
	/**
	* Esta función retorna los datos de concernientes a la version del submodulo
	* @access private
	*/
	function GetVersion()
	{
		$informacion=array(
		'version'=>'1',
		'subversion'=>'0',
		'revision'=>'0',
		'fecha'=>'19/09/2007',
		'autor'=>'TIZZIANO PEREA O',
		'descripcion_cambio' => '',
		'requiere_sql' => false,
		'requerimientos_adicionales' => '',
		'version_kernel' => '1.0'
		);
		return $informacion;
	}


	/**
	* Esta función retorna los datos de la impresión de la consulta del submodulo.
	*
	* @access private
	* @return text Datos HTML de la pantalla.
	*/
	function GetConsulta()
	{
		$epicrisis_html=new GeneracionEpicrisis_HTML();
		return $epicrisis_html->frmConsulta();
	}
     
	/**
	* Esta metodo captura los datos de la impresión de la Historia Clinica.
	* @access private
	* @return text Datos HTML de la pantalla.
	*/

	function GetReporte_Html()
	{
		$epicrisis_html=new GeneracionEpicrisis_HTML();
		return $epicrisis_html->frmHistoria();
		
	}

	/**
	* Esta función verifica si este submodulo fue utilizado para la atencion de un paciente.
	*
	* @access private
	* @return boolean True si fue utilizado el submodulo en la atencion.
	*/
	function GetEstado()
	{
          return true;
	}
	
	/**
	* Esta función instancia a las clases pertinentes para la construccion de la vista HTML.
	*
	* @access private
	* @return boolean True.
	*/
	function GetForma()
	{
		$_Examen = new Examen();
		$_Examen_HTML = new Examen_HTML();
		
		SessionSetVar("Ingreso",$this->ingreso);
		SessionSetVar("Evolucion",$this->evolucion);
		SessionSetVar("Paso",$this->paso);
		SessionSetVar("DatosPaciente",$this->datosPaciente);
		SessionSetVar("Usuario", UserGetUID());
		
          SessionSetVar("tipoidpaciente",$this->tipoidpaciente);
		SessionSetVar("paciente",$this->paciente);
		
		SessionSetVar("RutaImg",GetThemePath());
		SessionSetVar("Limite",GetLimitBrowser());
        
          /*$this->IncludeJS("TabPaneLayout");
          $this->IncludeJS("TabPaneApi");
		$this->IncludeJS("TabPane");*/


          include_once 'hc_modules/Conducta_ExamenFisico/RemoteXajax/Conducta_ExamenFisico_Xajax.php';
          $this->SetXajax(array("InsertFisico","InsertMental"));

          $ExFisico = $_Examen->Get_ConductaFisica();
          
          $ExMental = $_Examen->Get_ConductaMental();

          $this->salida = $_Examen_HTML->frmForma($ExFisico, $ExMental);
          return true;
	}
}

?>