<?php
	/********************************************************************************* 
 	* $Id: hc_RegistroEvolucionReno.php,v 1.2 2007/02/01 20:51:01 luis Exp $ 
 	*
 	* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 	* @package IPSOFT-SIIS
 	* 
	* @author    Luis Alejandro vargas. 
	* @package   hc_RegistroEvolucionReno
	* 
 	**********************************************************************************/
	
	IncludeClass("RegistroER_HTML","html","hc","RegistroEvolucionReno");
	IncludeClass("RegistroER",null,"hc","RegistroEvolucionReno");
	
	include_once("hc_modules/Apoyos_Diagnosticos_Control/hc_Apoyos_Diagnosticos_Control_1.php");
	IncludeClass("SolicitudInterconsultas",null,"hc","Interconsulta");

	class RegistroEvolucionReno extends hc_classModules
	{
		function RegistroEvolucionReno()
		{
			$this->registro=new RegistroER();
			$this->registro_html=new RegistroER_HTML();
			$this->apdCL=new APDControl();
			$this->inter=new SolicitudInterconsultas();
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
			
			$registro=new RegistroER();
			$registro_html=new RegistroER_HTML();
			$vector=array (
											"Peso","Talla","Indice de masa corporal","Estado nutricional","Presion arterial sistolica",
											"Presion arterial diastolica","Estadio kDOQI",
											"Riesgo de deterioro acelerado","Evidencia de deterioro acelerado",
											"Retinopatía Hipertensiva",
											"Lesión de organo blanco","Presencia de ulceras en los pies",
											"Riesgo de ulceras en los pies","Adherencia farmacologica",
											"Cambio hábitos alimenticios","Habito de actividad fisica","Riesgo psicosocial",
											"Asitencia grupo de apoyo","C","Cierre de caso","Causa cierre de caso",
											"Próxima cita sugerida","P","PROFESIONAL","CARGO"
										);
			$codigos=$registro->ConsultaDatosEvolucion();
			$registros=$registro->ConsultaDatosEvolucionConductas($evolucion,$inscripcion);
			$registros_cod=$registro->ConsultaDatosEvolucionRegistros($evolucion,$inscripcion);
	
			$consulta=$registro_html->frmConsulta($vector,$registros,$codigos,$registros_cod);
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
			
			$registro=new RegistroER();
			$registro_html=new RegistroER_HTML();
			$vector=array (
											"Peso","Talla","Indice de masa corporal","Estado nutricional","Presion arterial sistolica",
											"Presion arterial diastolica","Estadio kDOQI",
											"Riesgo de deterioro acelerado","Evidencia de deterioro acelerado",
											"Retinopatía Hipertensiva",
											"Lesión de organo blanco","Presencia de ulceras en los pies",
											"Riesgo de ulceras en los pies","Adherencia farmacologica",
											"Cambio hábitos alimenticios","Habito de actividad fisica","Riesgo psicosocial",
											"Asitencia grupo de apoyo","C","Cierre de caso","Causa cierre de caso",
											"Próxima cita sugerida","P","PROFESIONAL","CARGO"
										);
			$codigos=$registro->ConsultaDatosEvolucion();
			$registros=$registro->ConsultaDatosEvolucionConductas($evolucion,$inscripcion);
			$registros_cod=$registro->ConsultaDatosEvolucionRegistros($evolucion,$inscripcion);
	
			$imprimir=$registro_html->frmHistoria($vector,$registros,$codigos,$registros_cod);
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
			$registro=$this->registro;
			$registro_html=$this->registro_html;
			$apdCL=$this->apdCL;
			$inter=$this->inter;
			
			$_SESSION['frmprefijo']=SessionGetVar("Prefijo");	
			$_SESSION['datospaciente']=SessionGetVar("DatosPaciente");;
			$_SESSION['ingreso']=SessionGetVar("Ingreso");;
			$_SESSION['evolucion']=SessionGetVar("Evolucion");;
			$_SESSION['paso']=SessionGetVar("Paso");
			$_SESSION['plan']=SessionGetVar("Plan");
			
			$pfj=SessionGetVar("Prefijo");
			$programa=SessionGetVar("Programa");
			$uid=UserGetUID();
			
			$evolucion=SessionGetVar("Evolucion");
			$inscripcion=SessionGetVar("Inscripcion_$programa");

			$vector=array(
										"Peso","Talla","Indice de masa corporal","Estado nutricional","Presion arterial sistolica",
										"Presion arterial diastolica","Estadio kDOQI",
										"Riesgo de deterioro acelerado","Evidencia de deterioro acelerado",
										"Retinopatía Hipertensiva",
										"Lesión de organo blanco","Presencia de ulceras en los pies",
										"Riesgo de ulceras en los pies","Adherencia farmacologica",
										"Cambio hábitos alimenticios","Habito de actividad fisica","Riesgo psicosocial",
										"Asitencia grupo de apoyo","C","Cierre de caso","Causa cierre de caso",
										"Próxima cita sugerida","P","PROFESIONAL","CARGO"
										);
										
			$codigos=$registro->GetDatosEvolucion();
			
			if($_REQUEST['guardar'.$pfj])
			{
				$validar=$this->ValidarDatos($_REQUEST,$vector,sizeof($codigos));
				if(!$validar)
				{
					if($registro->GuardarRegistros($_REQUEST,$inscripcion,$evolucion))
					{
						$registro_html->frmError["MensajeError"]="REGISTROS GUARDADOS SATISFACTORIAMENTE";
						if($_REQUEST['cierre_caso'.$pfj]=="true")
						{
							if(!$registro->ActualizarEstadoProcesos('4',$evolucion,$inscripcion))
								$registro_html->frmError["MensajeError"]=$registro->ErrorDB();
								
							if(!$registro->ActualizarEstadoInscripcion($inscripcion,'0'))
								$registro_html->frmError["MensajeError"]=$registro->ErrorDB();
						}
					}
					else
						$registro_html->frmError["MensajeError"]=$registro->ErrorDB();
				}
				else
				{
					for($i=0;$i<sizeof($validar);$i++)
						if(!empty($validar[$i]))
							$registro_html->frmError[$validar[$i]]=1;	
					
					$registro_html->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";	
				}
			}
			$pruebas=$registro->GetCargosPruebas($programa);	
			$Laboratorios=$registro->GetSolicitudesCargos($evolucion,$inscripcion,$programa);	
			$resultadosLab=$apdCL->ConsultaResultadosPyp($evolucion,$inscripcion);

			$codigos=$registro->GetDatosEvolucion();
			$registros=$registro->GetDatosEvolucionConductas($evolucion,$inscripcion);
			$registros_cod=$registro->GetDatosEvolucionRegistros($evolucion,$inscripcion);
			$signos=$registro->GetDatosSignosConsultas($evolucion);
			$datosprofesional=$registro->GetDatosProfesional($uid);
			$especialidadesT=$registro->GetEspecialidadesTotal();

			for($i=0;$i<sizeof($codigos);$i++)
				if(!empty($codigos[$i][especialidad]))
					if(!$inter->Busqueda_Avanzada_especialidad($codigos[$i][especialidad]))
					{
						$registro_html->frmError["MensajeError"]=$registro->ErrorDB();
						break;
					}
					else
						$datosEspecialidad[$i]=$inter->Busqueda_Avanzada_especialidad($codigos[$i][especialidad]);
			
			return $registro_html->frmForma($vector,$registros,$codigos,$registros_cod,$pruebas,$Laboratorios,$resultadosLab,$signos,$datosEspecialidad,$datosprofesional,$especialidadesT);
		}
		
		function ValidarDatos($datos,$vector,$total)
		{
			$pfj=SessionGetvar("Prefijo");
			$registro=$this->registro;
			
			$error=array();
			
			if(!$datos['peso'.$pfj] OR !is_numeric($datos['peso'.$pfj]))
				$error[]=$vector[0];
				
			if(!$datos['talla'.$pfj] OR !is_numeric($datos['talla'.$pfj]))
				$error[]=$vector[1];	
			
			$datos['imc'.$pfj]=substr($datos['peso'.$pfj]/(pow($datos['talla'.$pfj]/100,2)),0,5);
			
			if(!$datos['imc'.$pfj] OR !is_numeric($datos['imc'.$pfj]))
				$error[]=$vector[2];
			
			if(!$datos['estado_nutricional'.$pfj])
				$error[]=$vector[3];	
				
			if(!$datos['ta_alta'.$pfj] OR !is_numeric($datos['ta_alta'.$pfj]))
				$error[]=$vector[4];
			
			if(!$datos['ta_baja'.$pfj] OR !is_numeric($datos['ta_baja'.$pfj]))
				$error[]=$vector[5];
			
			if(!$datos['kdoqi'.$pfj])
				$error[]=$vector[6];

			if(empty($datos['riesgo_deterioro_acelerado'.$pfj]))
				$error[]=$vector[7];
			
			if(empty($datos['deterioro_acelerado'.$pfj]))
				$error[]=$vector[8];
			
			if(empty($datos['retinopatia'.$pfj]))
				$error[]=$vector[9];

			if(empty($datos['lesion_organo_blanco'.$pfj]))
				$error[]=$vector[10];
			
			if(empty($datos['presencia_ulcera_pies'.$pfj]))
				$error[]=$vector[11];
			
			if(empty($datos['riesgo_ulcera_pies'.$pfj]))
				$error[]=$vector[12];
			
			if(empty($datos['af'.$pfj]))
				$error[]=$vector[13];
			
			if(empty($datos['cambio_habitos_alimenticios'.$pfj]))
				$error[]=$vector[14];
			
			if(empty($datos['habito_actividad_fisica'.$pfj]))
				$error[]=$vector[15];
			
			if(empty($datos['riesgo_psicosocial'.$pfj]) AND $datos['riesgo_psicosocial'.$pfj]!='0')
				$error[]=$vector[16];
			
			if(empty($datos['asistencia_grupo_apoyo'.$pfj]))
				$error[]=$vector[17];
				
			for($i=0;$i<$total;$i++)
				if(empty($datos['nombre'.$pfj][$i]))
					$error[]="C$i";
			
			if(empty($datos['cierre_caso'.$pfj]))
				$error[]=$vector[19];

			if(!$datos['proxima_cita'.$pfj] OR $registro->FechaStamp($datos['proxima_cita'.$pfj]) <= DATE("Y-m-d"))
				$error[]=$vector[21];
				
			return $error;
		}
	}
?>