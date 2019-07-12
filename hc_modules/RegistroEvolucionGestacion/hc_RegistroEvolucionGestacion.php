<?php
	/********************************************************************************* 
 	* $Id: hc_RegistroEvolucionGestacion.php,v 1.3 2007/02/01 20:51:01 luis Exp $ 
 	*
 	* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 	* @package IPSOFT-SIIS
 	* 
	* @author    Luis Alejandro vargas. 
	* @package   hc_RegistroEvolucionGestacion
	* 
 	**********************************************************************************/
	
	IncludeClass("RegistroEG_HTML","html","hc","RegistroEvolucionGestacion");
	IncludeClass("RegistroEG",null,"hc","RegistroEvolucionGestacion");
	IncludeClass("RiesgoBS",null,"hc","RiesgoBiopsicosocial");
	IncludeClass("SolicitudInterconsultas",null,"hc","Interconsulta");
	IncludeClass("APDControl",null,"hc","Apoyos_Diagnosticos_Control");
	IncludeClass("AtencionCP",null,"hc","AtencionCPN");
	IncludeClass("AtencionCP_HTML","html","hc","AtencionCPN");
	
	include_once("hc_modules/Apoyos_Diagnosticos_Control/hc_Apoyos_Diagnosticos_Control_1.php");
	
	class RegistroEvolucionGestacion extends hc_classModules
	{
		function RegistroEvolucionGestacion()
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
			
			$programa=SessionGetVar("Programa");
			$inscripcion=SessionGetVar("Inscripcion_$programa");
			$evolucion=SessionGetVar("Evolucion");
			
			$vector=array	(
											"Fecha","Semana","Peso","Presion Arterial Sentada","Mamas",
											"Altura Uterina","F.C.F","Presentacion Fetal","Estado Nutricional","Movimientos Fetales",
											"Actividad Uterina","Especuloscopia","Clasificacion Riesgo Biopsicosocial","Riesgo Biologico",
											"Riesgo Psicosocial","P","Hospitalizacion antes de este CPN","Asesoria PreTest VIH",
											"Asesoria PosTest VIH","Vacunacion T.T","C","Fecha Sugerida Proxima Cita",
											"Cierre Caso","Riesgo Especifico Identificado"
										);
		
			$registro=new RegistroEG();
			$registro_html=new RegistroEG_HTML();
			$semanas=$registro->GetSemanasCronograma($programa);
			$regevo=$registro->ConsultaRegistroEvolucion($inscripcion,$evolucion);
			
			$consulta=$registro_html->frmConsulta($regevo,$vector,$semanas);
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
			$programa=SessionGetVar("Programa");
			$inscripcion=SessionGetVar("Inscripcion_$programa");
			$evolucion=SessionGetVar("Evolucion");
			
			$vector=array	(
										"Fecha","Semana","Peso","Presion Arterial Sentada","Mamas",
										"Altura Uterina","F.C.F","Presentacion Fetal","Estado Nutricional","Movimientos Fetales",
										"Actividad Uterina","Especuloscopia","Clasificacion Riesgo Biopsicosocial","Riesgo Biologico",
										"Riesgo Psicosocial","P","Hospitalizacion antes de este CPN","Asesoria PreTest VIH",
										"Asesoria PosTest VIH","Vacunacion T.T","C","Fecha Sugerida Proxima Cita",
										"Cierre Caso","Riesgo Especifico Identificado"
									);
		
			$registro=new RegistroEG();
			$registro_html=new RegistroEG_HTML();
			$semanas=$registro->GetSemanasCronograma($programa);
			$regevo=$registro->ConsultaRegistroEvolucion($inscripcion,$evolucion);
			
			$imprimir=$registro_html->frmHistoria($regevo,$vector,$semanas);
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
			$registro=new RegistroEG();
			$registro_html=new RegistroEG_HTML();
			$this->registro_html=$registro_html;
			$riesgo=new RiesgoBS();
			$inter=new SolicitudInterconsultas();
			$apdCL=new APDControl();
			
			$pfj=SessionGetVar("Prefijo");
			$evolucion=SessionGetVar("Evolucion");
			$programa=SessionGetVar("Programa");
			$inscripcion=SessionGetVar("Inscripcion_$programa");
			$ingreso=SessionGetvar("Ingreso");
			$uid=UserGetUID();
			
			$puntaje_gineco=$_SESSION['puntaje_gineco'];
	
			$semanas=$registro->GetSemanasCronograma($programa);

			$fechas=$riesgo->GetDatofum($inscripcion);
			$fum=$fechas[0][fecha_ultimo_periodo];
			$fcp=substr($fechas[0][fecha_calulada_parto],0,10);
			$semana_gestante=intval($riesgo->CalcularSemanasGestante($fum));

			for($i=0;$i<sizeof($semanas);$i++)
			{
				if($semanas[$i][rango_inicio]<=$semana_gestante and $semanas[$i][rango_fin]>=$semana_gestante)
				{
					$periodo_id=$semanas[$i][periodo_id];
					$periodo=$i;
					break;	
				}
			}

			$vector=array(
										"Fecha","Semana","Peso","Presion Arterial Sentada","Mamas",
										"Altura Uterina","F.C.F","Presentacion Fetal","Estado Nutricional","Movimientos Fetales",
										"Actividad Uterina","Especuloscopia","Clasificacion Riesgo Biopsicosocial","Riesgo Biologico",
										"Riesgo Psicosocial","P","Hospitalizacion antes de este CPN","Asesoria PreTest VIH","Asesoria PosTest VIH","Vacunacion T.T","C",
										"Fecha Sugerida Proxima Cita","Cierre Caso","Riesgo Especifico Identificado"
										);
										
			$datosEvolucion=$registro->GetDatosEvolucion();
			
			if($_REQUEST['guardar'.$pfj])
			{
				$validar=$this->ValidarDatos($_REQUEST,$vector,sizeof($datosEvolucion));
				if(!$validar)
				{
					if($registro->GuardarRegistros($_REQUEST,$semanas[$periodo][rango_media],$semana_gestante,$inscripcion,$evolucion))
					{
						$registro_html->frmError["MensajeError"]="REGISTROS GUARDADOS SATISFACTORIAMENTE";
						if($_REQUEST['cierre_caso'.$pfj]==1)
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
			
			$datosConsulta=$registro->GetDatosEvolucionConductas($evolucion,$inscripcion);
			$datosEvolucionregistros=$registro->GetDatosEvolucionRegistros($evolucion,$inscripcion);
			
			for($i=0;$i<sizeof($datosEvolucion);$i++)
				if(!empty($datosEvolucion[$i][especialidad]))
					$datosEspecialidad[$i]=$inter->Busqueda_Avanzada_especialidad($datosEvolucion[$i][especialidad]);
			
			$datossignosConsulta=$registro->GetDatosSignosConsultas($evolucion);

			$datosRegistros=$registro->GetRegistrosEvoluciones($evolucion,$inscripcion);
			$datossignos=$registro->GetDatosSignos($evolucion,$inscripcion,$uid,$ingreso);
			
			$cierre=$registro->GetEstadoProcesosCPN($evolucion,$inscripcion);
			
			$puntajeRiesgos=$_SESSION['puntajeBS'];
			$puntajeTotalRiesgos=$_SESSION['puntajeT'];
			
			$datosprofesional=$registro->GetDatosProfesional($uid);
			$proximaCita=$registro->CalcularProximaCita($semana_gestante,$semanas,$periodo);
			
			$datos_sistemas=$registro->GetExamenFisico($evolucion);
			
			$pruebasLab=$registro->GetCargosPruebas($programa);
			$lab=$registro->VerificarSolicitues($evolucion,$inscripcion,$programa);	
			$resultados=$apdCL->ConsultaResultados($evolucion,$inscripcion);
			
			return $registro_html->frmForma($vector,$semanas,$datosConsulta,$datosEvolucion,$datosEvolucionregistros,$datosEspecialidad,$datosprofesional,$datossignos,$datosRegistros,$puntajeRiesgos,$puntajeTotalRiesgos,$proximaCita,$semana_gestante,$cierre,$fcp,$lab,$resultados,$pruebasLab,$datossignosConsulta,$datos_sistemas);
		}
		
		function ValidarDatos($datos,$vector,$total)
		{
			$pfj=SessionGetVar("Prefijo");
			
			$error=array();
			
			if(!$datos['peso'.$pfj] OR !is_numeric($datos['peso'.$pfj]))
				$error[]=$vector[2];
				
			if(!$datos['ta_alta'.$pfj] OR !is_numeric($datos['ta_alta'.$pfj]))
				$error[]=$vector[3];
			
			if(!$datos['ta_baja'.$pfj] OR !is_numeric($datos['ta_baja'.$pfj]))
				$error[]=$vector[3];
			
			if(!$datos['mamas'.$pfj])
				$error[]=$vector[4];
				
			if((!$datos['altura_uterina'.$pfj] OR !is_numeric($datos['altura_uterina'.$pfj])) AND $datos['altura_uterina'.$pfj]!='0')
				$error[]=$vector[5];
			
			if((!$datos['fcf'.$pfj] OR !is_numeric($datos['fcf'.$pfj])) AND $datos['fcf'.$pfj]!='0')
				$error[]=$vector[6];
			
			if(!$datos['presentacion_fetal'.$pfj])
				$error[]=$vector[7];
			
			if(!$datos['estado_nutricional'.$pfj])
				$error[]=$vector[8];
			
			if(!$datos['movimientos_fetales'.$pfj])
				$error[]=$vector[9];
			
			if(!$datos['actividad_uterina'.$pfj])
				$error[]=$vector[10];
			
			if(!$datos['especu'.$pfj])
				$error[]=$vector[11];
			
			if(!$datos['clasifi_riesgo'.$pfj])
				$error[]=$vector[12];
			
			if(!$datos['riesgo_bio'.$pfj] AND $datos['riesgo_bio'.$pfj]!='0')
				$error[]=$vector[13];
			
			if(!$datos['riesgo_psico'.$pfj] AND $datos['riesgo_psico'.$pfj]!='0')
				$error[]=$vector[14];
			
			if(!$datos['hospt_cpn'.$pfj])
				$error[]=$vector[16];
			
			if(!$datos['pretest'.$pfj])
				$error[]=$vector[17];
			
			if(!$datos['postest'.$pfj])
				$error[]=$vector[18];
			
			if(!$datos['vacunacion_tt'.$pfj])
				$error[]=$vector[19];
			
			for($i=0;$i<$total;$i++)
				if(!$datos['nombre'.$pfj][$i])
					$error[]="C$i";
					
			if(!$datos['cierre_caso'.$pfj])
				$error[]=$vector[22];

			if(empty($datos['riesgos_especifico'.$pfj]))
				$error[]=$vector[23];
	
			return $error;
		}
	}
?>