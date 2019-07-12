<?php
	/********************************************************************************* 
 	* $Id: hc_AyudasEducativas.php,v 1.2 2007/02/01 20:44:14 luis Exp $ 
 	*
 	* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 	* @package IPSOFT-SIIS
 	* 
	* @author    Luis Alejandro vargas. 
	* @package   hc_AyudasEducativas
	* 
 	**********************************************************************************/
	
	IncludeClass("Ayudas_HTML","html","hc","AyudasEducativas");
	IncludeClass("Ayudas",null,"hc","AyudasEducativas");
	IncludeClass("RiesgoBS",null,"hc","RiesgoBiopsicosocial");
	
	class AyudasEducativas extends hc_classModules
	{
		function AyudasEducativas()
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
			$ayudas_html=new Ayudas_HTML();
			if($ayudas_html->frmConsulta()==false)
			{
				return true;
			}
			return $ayudas_html->salida;
		}
			
		/**
		* Esta metodo captura los datos de la impresión de la Historia Clinica.
		* @access private
		* @return text Datos HTML de la pantalla.
		*/
	
		function GetReporte_Html()
		{
			$ayudas_html=new Ayudas_HTML();
			$imprimir=$ayudas_html->frmHistoria();
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
			$ayudas=new Ayudas();
			$ayudas_html=new Ayudas_HTML();
			$riesgo=new RiesgoBS();
			
			$evolucion=SessionGetvar("Evolucion");
			$programa=SessionGetVar("Programa");
			$inscripcion=SessionGetVar("Inscripcion_$programa");
			
			$ayudasPro=$ayudas->GetAyudasEducativasPro($programa);
			$ayudasPa=$ayudas->GetAyudasEducativasPa($programa);
			
			if(SessionGetVar("cpn"))
			{
				$fechas=$riesgo->GetDatofum($inscripcion);
				$fum=$fechas[0][fecha_ultimo_periodo];
				$fcp=substr($fechas[0][fecha_calulada_parto],0,10);
				$semana_gestante=intval($riesgo->CalcularSemanasGestante($fum));
				return $ayudas_html->frmForma($ayudasPro,$ayudasPa,$semana_gestante,$fcp);
			}
			else
				return $ayudas_html->frmForma($ayudasPro,$ayudasPa);
		}
	}
?>