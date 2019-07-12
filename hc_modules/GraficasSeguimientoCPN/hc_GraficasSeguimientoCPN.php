<?php
/**
* Submodulo de GraficasSeguimientoCPN
*
* @author luis alejandro vargas
* @version 1.0
* @package SIIS
* $Id: hc_GraficasSeguimientoCPN.php,v 1.2 2007/02/01 20:55:43 luis Exp $
*/

IncludeClass("Graficas",null,"hc","GraficasSeguimientoCPN");
IncludeClass("Graficas_HTML","html","hc","GraficasSeguimientoCPN");
IncludeClass("RiesgoBS",null,"hc","RiesgoBiopsicosocial");

class GraficasSeguimientoCPN extends hc_classModules
{
	function GraficasSeguimientoCPN()
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
	* Esta función retorna los datos de la impresión de la consulta del submodulo.
	*
	* @access private
	* @return text Datos HTML de la pantalla.
	*/
	function GetConsulta()
	{
		$graficas_html=new Graficas_HTML();
		if($graficas_html->frmConsulta()==false)
		{
			return true;
		}
		return $this->salida;
	}
     
	/**
	* Esta metodo captura los datos de la impresión de la Historia Clinica.
	* @access private
	* @return text Datos HTML de la pantalla.
	*/

	function GetReporte_Html()
	{
		$graficas_html=new Graficas_HTML();
		$imprimir=$graficas_html->frmHistoria();
		if($imprimir==false)
		{
			return true;
		}
		return $imprimir;
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
	
	function GetForma()
	{
		$graficas=new Graficas();
		$graficas_html=new Graficas_HTML();
		$riesgo=new RiesgoBS();
		
		$evolucion=SessionGetVar("Evolucion");
		$programa=SessionGetVar("Programa");
		$inscripcion=SessionGetVar("Inscripcion_$programa");
		
		$fechas=$riesgo->GetDatofum($inscripcion);
		$fcp=substr($fechas[0][fecha_calulada_parto],0,10);
		$semana_gestante=intval($riesgo->CalcularSemanasGestante($fechas[0][fecha_ultimo_periodo]));
		
		$datosgraf=$graficas->GetDatosGraficas($evolucion,$inscripcion);
		
		$graficas_html->frmError["MensajeError"]=$graficas->ErrorDB();
		$graficas_html->ban=1;

		return $graficas_html->frmForma($datosgraf,$semana_gestante,$fcp);
	}
	
}
?>
