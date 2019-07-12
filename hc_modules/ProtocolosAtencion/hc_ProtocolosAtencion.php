<?php
	/********************************************************************************* 
 	* $Id: hc_ProtocolosAtencion.php,v 1.2 2007/02/01 20:50:52 luis Exp $ 
 	*
 	* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 	* @package IPSOFT-SIIS
 	* 
	* @author    Luis Alejandro vargas. 
	* @package   hc_ProtocolosAtencion
	* 
 	**********************************************************************************/
	
	IncludeClass("Protocolos_HTML","html","hc","ProtocolosAtencion");
	IncludeClass("Protocolos",null,"hc","ProtocolosAtencion");
	IncludeClass("RiesgoBS",null,"hc","RiesgoBiopsicosocial");
	
	class ProtocolosAtencion extends hc_classModules
	{

		function ProtocolosAtencion()
		{
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
			$protocolos_html=new Protocolos_HTML($this);
			if($protocolos_html->frmConsulta()==false)
			{
				return true;
			}
			return $protocolos_html->salida;
		}
			
		/**
		* Esta metodo captura los datos de la impresión de la Historia Clinica.
		* @access private
		* @return text Datos HTML de la pantalla.
		*/
	
		function GetReporte_Html()
		{
			$protocolos_html=new Protocolos_HTML($this);
			$imprimir=$protocolos_html->frmHistoria();
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
			$protocolos=new Protocolos();
			$protocolos_html=new Protocolos_HTML();
			$riesgo=new RiesgoBS();

			$evolucion=SessionGetvar("Evolucion");
			$programa=SessionGetVar("Programa");
			$inscripcion=SessionGetVar("Inscripcion_$programa");
			
			$ProtocolosAtencion=$protocolos->GetProtocolosAtencion($programa);
			
			if(SessionGetVar("cpn"))
			{
				$fechas=$riesgo->GetDatofum($inscripcion);
				$fum=$fechas[0][fecha_ultimo_periodo];
				$fcp=substr($fechas[0][fecha_calulada_parto],0,10);
				$semana_gestante=intval($riesgo->CalcularSemanasGestante($fum));
				return $protocolos_html->frmForma($ProtocolosAtencion,$semana_gestante,$fcp);
			}
			else
				return $protocolos_html->frmForma($ProtocolosAtencion);
		}

	}
?>