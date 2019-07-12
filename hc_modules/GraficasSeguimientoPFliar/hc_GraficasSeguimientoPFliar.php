<?php
/**
* Submodulo de GraficasSeguimientoPFliar
*
* @author luis alejandro vargas
* @version 1.0
* @package SIIS
* $Id: hc_GraficasSeguimientoPFliar.php,v 1.2 2007/02/01 20:48:30 luis Exp $
*/

IncludeClass("GraficasPFliar",null,"hc","GraficasSeguimientoPFliar");
IncludeClass("GraficasPFliar_HTML","html","hc","GraficasSeguimientoPFliar");

class GraficasSeguimientoPFliar extends hc_classModules
{
	
	function GraficasSeguimientoPFliar()
	{
		$this->graficas=new GraficasPFliar();
		$this->graficas_html=new GraficasPFliar_HTML();
		return true;
	} 
	
	/**
	* Esta funci�n retorna los datos de concernientes a la version del submodulo
	* @access private
	*/

	function GetVersion()
	{
		$informacion=array(
		'version'=>'1',
		'subversion'=>'0',
		'revision'=>'0',
		'fecha'=>'30/06/2006',
		'autor'=>'LUIS ALEJANDRO VARGAS',
		'descripcion_cambio' => '',
		'requiere_sql' => false,
		'requerimientos_adicionales' => '',
		'version_kernel' => '1.0'
		);
		return $informacion;
	}


	/**
	* Esta funci�n retorna los datos de la impresi�n de la consulta del submodulo.
	*
	* @access private
	* @return text Datos HTML de la pantalla.
	*/
	function GetConsulta()
	{
		$graficas_html=new Graficas_HTML($this);
		if($graficas_html->frmConsulta()==false)
		{
			return true;
		}
		return $this->salida;
	}
     
	/**
	* Esta metodo captura los datos de la impresi�n de la Historia Clinica.
	* @access private
	* @return text Datos HTML de la pantalla.
	*/

	function GetReporte_Html()
	{
		$graficas_html=new Graficas_HTML($this);
		$imprimir=$graficas_html->frmHistoria();
		if($imprimir==false)
		{
			return true;
		}
		return $imprimir;
	}

	/**
	* Esta funci�n verifica si este submodulo fue utilizado para la atencion de un paciente.
	*
	* @access private
	* @return text Datos HTML de la pantalla.
	*/

	function GetEstado()
	{
		return true;
	}
	
	function GetForma()
	{
		$graficas=$this->graficas;
		$graficas_html=$this->graficas_html;
		
		$programa=SessionGetVar("Programa");
		$inscripcion=SessionGetVar("Inscripcion_$programa");
		$evolucion=SessionGetVar("Evolucion");

		$datosgraf=$graficas->GetDatosGraficas($evolucion,$inscripcion);
		if(!$datosgraf)
		{
			$graficas_html->frmError["MensajeError"]=$graficas->ErrorDB();
			$graficas_html->ban=1;
		}
		
		return $graficas_html->frmForma($datosgraf);
	}
	
}
?>