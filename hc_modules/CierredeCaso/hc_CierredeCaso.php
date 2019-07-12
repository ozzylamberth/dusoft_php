<?php

/**
* Submodulo de AtencionGestantes.
* $Id: hc_CierredeCaso.php,v 1.2 2007/02/01 20:44:26 luis Exp $
* @author luis alejandro vargas
* @version 1.0
* @package SIIS
* 
*/

IncludeClass("Cierre",null,"hc","CierredeCaso");
IncludeClass("Cierre_HTML","html","hc","CierredeCaso");
IncludeClass("Riesgo_BS",null,"hc","RiesgoBiopsicosocial");

include_once "hc_modules/DatosRecienNacidos/hc_DatosRecienNacidos.php";

class CierredeCaso extends hc_classModules
{
		/**
		* Esta función Inicializa las variable de la clase
		*
		* @access public
		* @return boolean Para identificar que se realizo.
		*/
	
		var $obj;
		
		function CierredeCaso($objeto)
		{
			$this->obj=$objeto;
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
			$cierre_html=new Cierre_HTML($this);
			if($cierre_html->frmConsulta()==false)
			{
				return true;
			}
			return $cierre_html->salida;
		}
			
		/**
		* Esta metodo captura los datos de la impresión de la Historia Clinica.
		* @access private
		* @return text Datos HTML de la pantalla.
		*/
	
		function GetReporte_Html()
		{
			$cierre_html=new Cierre_HTML($this);
			$imprimir=$cierre_html->frmHistoria();
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
			$cierre=new Cierre();
			$cierre_html=new Cierre_HTML();
			$riesgo=new RiesgoBS();
	
			$pfj=SessionGetVar("Prefijo");
			$evolucion=SessionGetVar("Evolucion");
			$paso=SessionGetVar("Paso");
			$programa=SessionGetVar("Programa");
			$inscripcion=SessionGetVar("Inscripcion_$programa");
			
			$fechas=$riesgo->GetDatofum($inscripcion);
			$fum=$fechas[0][fecha_ultimo_periodo];
			$fcp=substr($fechas[0][fecha_calulada_parto],0,10);
			$semana_gestante=intval($riesgo->CalcularSemanasGestante($fum));
	
			if(!SessionGetVar("cierre_caso_$programa") AND SessionGetVar("cpn"))
				return $cierre_html->frmCierreCaso($semana_gestante,$link_nacidos,$fcp);
			else if(SessionGetVar("cierre_caso_$programa") OR !SessionGetVar("cpn"))
			{
				$datosC=$cierre->ConsultaInfo($inscripcion);
				return $cierre_html->frmCierreCasoConsulta($datosC);
			}
		}
}
?>