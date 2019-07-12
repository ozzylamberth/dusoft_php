<?php
	/********************************************************************************* 
 	* $Id: hc_PruebasLaboratorioReno.php,v 1.2 2007/02/01 20:51:01 luis Exp $ 
 	*
 	* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 	* @package IPSOFT-SIIS
 	* 
	* @author    Luis Alejandro vargas. 
	* @package   hc_ProtocolosAtencion
	* 
 	**********************************************************************************/
	
	IncludeClass("PruebasLaboratorio_HTML","html","hc","PruebasLaboratorioReno");
	IncludeClass("PruebasLaboratorio",null,"hc","PruebasLaboratorioReno");
	IncludeClass("Renopro",null,"hc","InscripcionReno");
	IncludeClass("APD_Solicitudes",null,"hc","Apoyos_Diagnosticos_Solicitud");
	
	class PruebasLaboratorioReno extends hc_classModules
	{
		function PruebasLaboratorioReno()
		{
			$this->pruebas=new PruebasLaboratorio();
			$this->pruebas_html=new PruebasLaboratorio_HTML();
			$this->inscripcion=new Renopro();
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
			$pruebas=$this->pruebas;
			$pruebas_html=$this->pruebas_html;
			$ins=$this->inscripcion;
			
			$pfj=SessionGetVar("Prefijo");
			$programa=SessionGetVar("Programa");
			$inscripcion=SessionGetVar("Inscripcion_$programa");
			$evolucion=SessionGetVar("Evolucion");
			

			if($_REQUEST['solicitar'.$pfj])
				$pruebas_html->frmError["MensajeError"]=$this->Solicitar_Examenes($_REQUEST['apoyos'.$pfj]);
			
			$pruebasLab=$pruebas->GetPruebasLaboratorio($programa);
			
			if(!$pruebasLab)
				$pruebas_html->frmError["MensajeError"]=$pruebas->ErrorDB();
				
			$consulta=$ins->ConsultaSolicitudes();

			return $pruebas_html->frmForma($pruebasLab,$consulta);
		}
		
		function Solicitar_Examenes($datos,$validacion)
		{
			$apd=new APD_Solicitudes();
			$pfj=SessionGetVar("Prefijo");
			$evolucion=SessionGetVar("Evolucion");
			$programa=SessionGetVar("Programa");
			$inscripcion=SessionGetVar("Inscripcion_$programa");

			if(!empty($datos))
			{
				if($apd->Insertar_Varias_Solicitudes($datos,$evolucion,$inscripcion,$programa))
					return "SOLICITDES GUARDADAS SATISFACTORIAMENTE";
				else
					return $apd->ErrorDB();
			}
			else
				return "DEBE SELECCIONAR ALGUN EXAMEN";
			
		}
	}
?>