<?php
	/********************************************************************************* 
 	* $Id: hc_CronogramaCitasyProcedimientos.php,v 1.2 2007/02/01 20:44:37 luis Exp $ 
 	*
 	* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 	* @package IPSOFT-SIIS
 	* 
	* @author    Luis Alejandro vargas. 
	* @package   hc_CronogramaCitasyProcedimientos
	* 
 	**********************************************************************************/
	
	IncludeClass("CitasyProcedimientos_HTML","html","hc","CronogramaCitasyProcedimientos");
	IncludeClass("CitasyProcedimientos",null,"hc","CronogramaCitasyProcedimientos");
	IncludeClass("RiesgoBS",null,"hc","RiesgoBiopsicosocial");
	IncludeClass("RegistroEG",null,"hc","ResgistroEvolucionGestacion");

	
	class CronogramaCitasyProcedimientos extends hc_classModules
	{

		function CronogramaCitasyProcedimientos()
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
			$cronograma_html=new CitasyProcedimientos_HTML();
			if($cronograma_html->frmConsulta()==false)
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
			$cronograma_html=new CitasyProcedimientos_HTML();
			$imprimir=$cronograma_html->frmHistoria();
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
			$cronograma=new CitasyProcedimientos();
			$cronograma_html=new CitasyProcedimientos_HTML();
			$riesgo=new RiesgoBS();
			$registro=new RegistroEG();
			
			$pfj=SessionGetvar("Prefijo");
			$evolucion=SessionGetVar("Evolucion");
			$programa=SessionGetVar("Programa");
			$inscripcion=SessionGetVar("Inscripcion_$programa");
			SessionSetVar("ImgRuta",GetThemePath());
			
			$semanas=$registro->GetSemanasCronograma($programa);
			$tp_semana=$cronograma->GetTipoProfesionalSemana($programa);
			
			$fechas=$riesgo->GetDatofum($inscripcion);
			$fum=$fechas[0][fecha_ultimo_periodo];
			$fcp=substr($fechas[0][fecha_calulada_parto],0,10);
			$semana_gestante=intval($riesgo->CalcularSemanasGestante($fum));
	
			$listaPro=$cronograma->GetListaProcedimientos($programa);
			$datosPro=$cronograma->GetDatosProcedimientos($programa);

			if($_REQUEST['solicitar'.$pfj])
			{
				$proc=$_REQUEST['procedimientos'.$pfj];
				$periodo_sugerido=$_REQUEST['periodo_sugerido'.$pfj];
				$periodo_solicitado=$_REQUEST['periodo_solicitado'.$pfj];
				
				if(!empty($proc))
				{
					if($cronograma->GuardarProcedimientosSolicitados($evolucion,$inscripcion,$programa,$proc,$periodo_sugerido,$periodo_solicitado))
					{
						$cronograma_html->frmError["MensajeError"]="SOLICITUDES GUARDADOS EXITOSAMENTE";
						$cronograma_html->ban=1;
					}
					else
					{
						$cronograma_html->frmError["MensajeError"]=$cronograma->ErrorDB();
						$cronograma_html->ban=1;	
					}
				}
				else
				{
						$cronograma_html->frmError["MensajeError"]="SELECCIONE ALGUNA SOLICITUD";
						$cronograma_html->ban=1;
				}
			}
			
			$datosProcSolicitados=$cronograma->GetDatosProcedimientosSolicitados($evolucion,$inscripcion,$programa);
				
			return $cronograma_html->frmCitasyProcedimientos($listaPro,$datosPro,$datosProcSolicitados,$semanas,$tp_semana,$semana_gestante,$fcp);
		}

	}
?>