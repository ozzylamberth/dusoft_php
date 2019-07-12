<?php
	/********************************************************************************* 
 	* $Id: hc_GrupoRiesgoRenoproteccion.php,v 1.2 2007/02/01 20:48:41 luis Exp $ 
 	*
 	* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 	* @package IPSOFT-SIIS
 	* 
	* @author    Luis Alejandro vargas. 
	* @package   hc_GrupoRiesgoRenoproteccion
	* 
 	**********************************************************************************/
	
	IncludeClass("GrupoRiesgo_HTML","html","hc","GrupoRiesgoRenoproteccion");
	IncludeClass("GrupoRiesgo",null,"hc","GrupoRiesgoRenoproteccion");
	IncludeClass("InscripcionesPYP",null,"hc","InscripcionPYP");
	
	class GrupoRiesgoRenoproteccion extends hc_classModules
	{
		function GrupoRiesgoRenoproteccion()
		{
			$this->gpReno=new GrupoRiesgo();
			$this->gpReno_html=new GrupoRiesgo_HTML();
			$this->inspyp=new InscripcionesPYP();
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
			$evolucion=SessionGetvar("Evolucion");
			$programa=SessionGetvar("Programa");
			$inscripcion=SessionGetvar("Inscripcion_$programa");
			
			$riesgo=new GrupoRiesgo();
			$riesgo_html=new GrupoRiesgo_HTML();
			$riesgosbp=$riesgo->ConsultaRiesgoBiopsicosocial($evolucion,$inscripcion,$programa);
			$consulta=$riesgo_html->frmConsulta($riesgosbp);

			if($consulta==false)
				return "";
			
			return $consulta;
		}
			
		/**
		* Esta metodo captura los datos de la impresión de la Historia Clinica.
		* @access private
		* @return text Datos HTML de la pantalla.
		*/
		
		function GetReporte_Html()
		{
			$evolucion=SessionGetvar("Evolucion");
			$programa=SessionGetvar("Programa");
			$inscripcion=SessionGetvar("Inscripcion_$programa");
			
			$riesgo=new GrupoRiesgo();
			$riesgo_html=new GrupoRiesgo_HTML();
			$riesgosbp=$riesgo->ConsultaRiesgoBiopsicosocial($evolucion,$inscripcion,$programa);
			$imprimir=$riesgo_html->frmHistoria($riesgosbp);
			
			if($imprimir==false)
				return "";
			
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
			$gpReno=$this->gpReno;
			$gpReno_html=$this->gpReno_html;
			$inspyp=$this->inspyp;
			
			$pfj=SessionGetVar("Prefijo");
			$evolucion=SessionGetVar("Evolucion");
			$programa=SessiongetVar("Programa");
			$inscripcion=SessionGetVar("Inscripcion_$programa");
			
			$insevo=$inspyp->GetInscripcionEvolucion();
			
			if(sizeof($insevo)>0)
			{
				$ins=$insevo[0][inscripcion_id];
				$evo=$insevo[0][evolucion_id];
				
				if($evolucion > $evo)
					if(!$inspyp->InsertarEvoProcesos('0',$evolucion,$inscripcion))
						$gpReno_html->frmError["MensajeError"]=$inspyp->ErrorDB();
			}
			
			if($_REQUEST['guardar'.$pfj])
				$gpReno_html->frmError["MensajeError"]=$this->GuardarDatos($_REQUEST);
			
			$datos_adicionales=$gpReno->GetDatosAdicionalesPaciente();
			$ocupacion=$gpReno->GetDatoOcupacion();
			$tipos_raza=$gpReno->GetTiposRaza();
			$conteo=$gpReno->GetConteoEvolucion();
			
			$riesgosR=$gpReno->GetRiesgoBiopsicosocial();
			$grupos=$gpReno->GetGruposRiesgos();
			$DatosR=$gpReno->GetDatosRiesgoBiopsicosocial();
			
			$puntajes=$gpReno->CalcularPuntajeRiesgoEvolucion();
			
			return $gpReno_html->frmForma($datos_adicionales,$tipos_raza,$riesgosR,$conteo,$DatosR,$grupos,$puntajes,$ocupacion);
		}
		
		function GuardarDatos($datos)
		{
			$gpReno=$this->gpReno;
			
			if(!empty($datos))
			{
				if($gpReno->GuardarDatosAdicionalesPaciente($datos))
				{
					if($gpReno->GuardarRiesgosBiopsicosocial($datos))
						return "DATOS GUARDADOS SATISFACTORIAMENTE";
					else
						return $gpReno->ErrorDB();
				}
				else
					return $gpReno->ErrorDB();
			}
			else
				return "FALTAN DATOS OBLIGATORIOS";
		}
	}
?>