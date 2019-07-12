<?php
	/********************************************************************************* 
 	* $Id: hc_RegistroEvolucionPFliar.php,v 1.3 2007/02/01 20:51:01 luis Exp $ 
 	*
 	* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 	* @package IPSOFT-SIIS
 	* 
	* @author    Luis Alejandro vargas. 
	* @package   hc_RegistroEvolucionPFliar
	* 
 	**********************************************************************************/
	
	IncludeClass("RegistroEPF_HTML","html","hc","RegistroEvolucionPFliar");
	IncludeClass("RegistroEPF",null,"hc","RegistroEvolucionPFliar");
	IncludeClass("InscripcionPF",null,"hc","InscripcionPlanFamiliar");
	
	include_once("hc_modules/Apoyos_Diagnosticos_Control/hc_Apoyos_Diagnosticos_Control_1.php");
	
	class RegistroEvolucionPFliar extends hc_classModules
	{
		function RegistroEvolucionPFliar()
		{
			$this->registro=new RegistroEPF();
			$this->registro_html=new RegistroEPF_HTML();
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
			$registro=new RegistroEPF();
			$registro_html=new RegistroEPF_HTML();
			
			$programa=SessionGetVar("Programa");
			$evolucion=SessionGetVar("Evolucion");
			$inscripcion=SessionGetVar("Inscripcion_$programa");
			$datosPaciente=SessionGetVar("DatosPaciente");
			
			if($datosPaciente['sexo_id']=='M')
			{
				$vector=array	(
												"Metodo","Fecha Inicio","Fecha Terminacion","Realiza Periodicamente Autoexamen de Testiculos","Realiza Periodicamente Autoexamen de Mamas","Profesional","Cargo"
											);
				
			}
			elseif($datosPaciente['sexo_id']=='F')
			{
				$vector=array(
											"Metodo"=>array("Combo","1"),
											"Fecha Ultima Mestruacion"=>array("TextFecha","1"),
											"Fecha Inicio del Metodo"=>array("TextFecha","2"),
											"Fecha Terminacion del Metodo"=>array("TextFecha","3"),
											"Realiza Periodicamente Autoexamen de Mamas"=>array("Radio","1"),
											"P"=>array("Lab"),
											"SIGNOS Y SINTOMAS"=>array("Titulo",""),
											"Tension Arterial"=>array("2Text",""),
											"Mareos"=>array("Radio","2"),
											"Cefalea"=>array("Radio","3"),
											"Manchas en la Piel"=>array("Radio","4"),
											"Acne"=>array("Radio","5"),
											"Nauceas"=>array("Radio","6"),
											"Dolor Mamas"=>array("Radio","7"),
											"Dolor Pelvico"=>array("Radio","8"),
											"Expulsion del Dispositivo"=>array("Radio","9"),
											"Tratamiento Propio de Leucorrea"=>array("Radio","10"),
											"Tratamiento Pareja de Leucorrea"=>array("Radio","11"),
											"Sintomas Urinarios"=>array("Radio","12"),
											"Hemorragia"=>array("Radio","13"),
											"Varices"=>array("Radio","14"),
											"Edema"=>array("Radio","15"),
											"Cambios de Comportamiento"=>array("Radio","16"),
											"Satisfaccion del Metodo"=>array("Radio","17"),
											"CONDUCTA"=>array("Titulo",""),
											"Cambiar Metodo"=>array("Radio","18","1"),
											"Cual"=>array("Combo","2"),
											"DETECCION TEMPRANA DE CANCER DE CERVIX & MAMA"=>array("Titulo",""),
											"MAMA IZQUIERDA"=>array("SubTitulo",""),
											"Cuadrante Superior Externo (Izq)"=>array("Combo","3","1"),
											"Cuadrante Superior Interno (Izq)"=>array("Combo","3","1"),
											"Cuadrante Inferior Externo (Izq)"=>array("Combo","3","1"),
											"Cuadrante Inferior Interno (Izq)"=>array("Combo","3","1"),
											"Pezon (Izq)"=>array("Combo","3","1"),
											"Axila (Izq)"=>array("Combo","3","1"),
											"Piel (Izq)"=>array("Combo","3","1"),
											"MAMA DERECHA"=>array("SubTitulo",""),
											"Cuadrante Superior Externo (Der)"=>array("Combo","3","2"),
											"Cuadrante Superior Interno (Der)"=>array("Combo","3","2"),
											"Cuadrante Inferior Externo (Der)"=>array("Combo","3","2"),
											"Cuadrante Inferior Interno (Der)"=>array("Combo","3","2"),
											"Pezon (Der)"=>array("Combo","3","2"),
											"Axila (Der)"=>array("Combo","3","2"),
											"Piel (Der)"=>array("Combo","3","2"),
											"Cierre de Caso"=>array("Combo","4"),
											"Profesional"=>array("Label","1"),
											"Cargo"=>array("Label","2"),
											);
			}
			
			$registros=$registro->GetDatosEvolucionPF($evolucion,$inscripcion,$datosPaciente['sexo_id']);
			$consulta=$registro_html->frmConsulta($vector,$registros);
			
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
			$registro=new RegistroEPF();
			$registro_html=new RegistroEPF_HTML();
			
			$programa=SessionGetVar("Programa");
			$evolucion=SessionGetVar("Evolucion");
			$inscripcion=SessionGetVar("Inscripcion_$programa");
			$datosPaciente=SessionGetVar("DatosPaciente");
			
			if($datosPaciente['sexo_id']=='M')
			{
				$vector=array	(
												"Metodo","Fecha Inicio","Fecha Terminacion",
												"Realiza Periodicamente Autoexamen de Testiculos",
												"Realiza Periodicamente Autoexamen de Mamas",
												"Profesional","Cargo"
											);
				
			}
			if($datosPaciente['sexo_id']=='F')
			{
				$vector=array(
											"Metodo"=>array("Combo","1"),
											"Fecha Ultima Mestruacion"=>array("TextFecha","1"),
											"Fecha Inicio del Metodo"=>array("TextFecha","2"),
											"Fecha Terminacion del Metodo"=>array("TextFecha","3"),
											"Realiza Periodicamente Autoexamen de Mamas"=>array("Radio","1"),
											"P"=>array("Lab"),
											"SIGNOS Y SINTOMAS"=>array("Titulo",""),
											"Tension Arterial"=>array("2Text",""),
											"Mareos"=>array("Radio","2"),
											"Cefalea"=>array("Radio","3"),
											"Manchas en la Piel"=>array("Radio","4"),
											"Acne"=>array("Radio","5"),
											"Nauseas"=>array("Radio","6"),
											"Dolor Mamas"=>array("Radio","7"),
											"Dolor Pelvico"=>array("Radio","8"),
											"Expulsion del Dispositivo"=>array("Radio","9"),
											"Tratamiento Propio de Leucorrea"=>array("Radio","10"),
											"Tratamiento Pareja de Leucorrea"=>array("Radio","11"),
											"Sintomas Urinarios"=>array("Radio","12"),
											"Hemorragia"=>array("Radio","13"),
											"Varices"=>array("Radio","14"),
											"Edema"=>array("Radio","15"),
											"Cambios de Comportamiento"=>array("Radio","16"),
											"Satisfaccion del Metodo"=>array("Radio","17"),
											"CONDUCTA"=>array("Titulo",""),
											"Cambiar Metodo"=>array("Radio","18","1"),
											"Cual"=>array("Combo","2"),
											"DETECCION TEMPRANA DE CANCER DE CERVIX & MAMA"=>array("Titulo",""),
											"MAMA IZQUIERDA"=>array("SubTitulo",""),
											"Cuadrante Superior Externo (Izq)"=>array("Combo","3","1"),
											"Cuadrante Superior Interno (Izq)"=>array("Combo","3","1"),
											"Cuadrante Inferior Externo (Izq)"=>array("Combo","3","1"),
											"Cuadrante Inferior Interno (Izq)"=>array("Combo","3","1"),
											"Pezon (Izq)"=>array("Combo","3","1"),
											"Axila (Izq)"=>array("Combo","3","1"),
											"Piel (Izq)"=>array("Combo","3","1"),
											"MAMA DERECHA"=>array("SubTitulo",""),
											"Cuadrante Superior Externo (Der)"=>array("Combo","3","2"),
											"Cuadrante Superior Interno (Der)"=>array("Combo","3","2"),
											"Cuadrante Inferior Externo (Der)"=>array("Combo","3","2"),
											"Cuadrante Inferior Interno (Der)"=>array("Combo","3","2"),
											"Pezon (Der)"=>array("Combo","3","2"),
											"Axila (Der)"=>array("Combo","3","2"),
											"Piel (Der)"=>array("Combo","3","2"),
											"Cierre de Caso"=>array("Radio","19","2"),
											"Motivo Cierre de Caso"=>array("Combo","4"),
											"Profesional"=>array("Label","1"),
											"Cargo"=>array("Label","2"),
											);
			}
			
			$registros=$registro->GetDatosEvolucionPF($evolucion,$inscripcion,$datosPaciente['sexo_id']);
			$imprimir=$registro_html->frmConsulta($vector,$registros);
			
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
			$ins=new InscripcionPF();
			$apdCL=new APDControl();
			
			$pfj=SessionGetVar("Prefijo");
			$programa=SessionGetVar("Programa");
			$uid=UserGetUID();
			$evolucion=SessionGetVar("Evolucion");
			$inscripcion=SessionGetVar("Inscripcion_$programa");
			$datosPaciente=SessionGetVar("DatosPaciente");
			
			$_SESSION['frmprefijo']=SessionGetVar("Prefijo");	
			$_SESSION['datospaciente']=SessionGetVar("DatosPaciente");;
			$_SESSION['ingreso']=SessionGetVar("Ingreso");;
			$_SESSION['evolucion']=SessionGetVar("Evolucion");;
			$_SESSION['paso']=SessionGetVar("Paso");
			$_SESSION['plan']=SessionGetVar("Plan");

			if($datosPaciente["sexo_id"]=='M')
			{
				$vector=array	(
												"Metodo",
												"Fecha Inicio","Fecha Terminacion","Realiza Periodicamente Autoexamen de Testiculos",
												"Realiza Periodicamente Autoexamen de Mamas","Profesional","Cargo"
											);
			}
			elseif($datosPaciente["sexo_id"]=='F')
			{
				$vector=array(
											"Metodo"=>array("Combo","1"),
											"Fecha Ultima Mestruacion"=>array("TextFecha","1"),
											"Fecha Inicio del Metodo"=>array("TextFecha","2"),
											"Fecha Terminacion del Metodo"=>array("TextFecha","3"),
											"Realiza Periodicamente Autoexamen de Mamas"=>array("Radio","1"),
											"P"=>array("Lab"),
											"SIGNOS Y SINTOMAS"=>array("Titulo",""),
											"Tension Arterial"=>array("2Text",""),
											"Mareos"=>array("Radio","2"),
											"Cefalea"=>array("Radio","3"),
											"Manchas en la Piel"=>array("Radio","4"),
											"Acne"=>array("Radio","5"),
											"Nauseas"=>array("Radio","6"),
											"Dolor Mamas"=>array("Radio","7"),
											"Dolor Pelvico"=>array("Radio","8"),
											"Expulsion del Dispositivo"=>array("Radio","9"),
											"Tratamiento Propio de Leucorrea"=>array("Radio","10"),
											"Tratamiento Pareja de Leucorrea"=>array("Radio","11"),
											"Sintomas Urinarios"=>array("Radio","12"),
											"Hemorragia"=>array("Radio","13"),
											"Varices"=>array("Radio","14"),
											"Edema"=>array("Radio","15"),
											"Cambios de Comportamiento"=>array("Radio","16"),
											"Satisfaccion del Metodo"=>array("Radio","17"),
											"CONDUCTA"=>array("Titulo",""),
											"Cambiar Metodo"=>array("Radio","18","1"),
											"Cual"=>array("Combo","2"),
											"DETECCION TEMPRANA DE CANCER DE CERVIX & MAMA"=>array("Titulo",""),
											"MAMA IZQUIERDA"=>array("SubTitulo",""),
											"Cuadrante Superior Externo (Izq)"=>array("Combo","3","1"),
											"Cuadrante Superior Interno (Izq)"=>array("Combo","3","1"),
											"Cuadrante Inferior Externo (Izq)"=>array("Combo","3","1"),
											"Cuadrante Inferior Interno (Izq)"=>array("Combo","3","1"),
											"Pezon (Izq)"=>array("Combo","3","1"),
											"Axila (Izq)"=>array("Combo","3","1"),
											"Piel (Izq)"=>array("Combo","3","1"),
											"MAMA DERECHA"=>array("SubTitulo",""),
											"Cuadrante Superior Externo (Der)"=>array("Combo","3","2"),
											"Cuadrante Superior Interno (Der)"=>array("Combo","3","2"),
											"Cuadrante Inferior Externo (Der)"=>array("Combo","3","2"),
											"Cuadrante Inferior Interno (Der)"=>array("Combo","3","2"),
											"Pezon (Der)"=>array("Combo","3","2"),
											"Axila (Der)"=>array("Combo","3","2"),
											"Piel (Der)"=>array("Combo","3","2"),
											"Cierre de Caso"=>array("Radio","19","2"),
											"Motivo Cierre de Caso"=>array("Combo","4"),
											"Profesional"=>array("Label","1"),
											"Cargo"=>array("Label","2"),
											);
			}
			
			$metodos=$ins->GetMetodosPF();
			$datosprofesional=$registro->GetDatosProfesional($uid);

			if($_REQUEST['guardar'.$pfj])
			{
				$validar=$this->ValidarDatos($_REQUEST,$vector,$datosPaciente['sexo_id']);
				if(!$validar)
				{
					if($registro->GuardarRegistros($_REQUEST,$inscripcion,$evolucion,$datosPaciente['sexo_id']))
					{
						$registro_html->frmError["MensajeError"]="REGISTROS GUARDADOS SATISFACTORIAMENTE";
						if($_REQUEST['cierre_de_caso'.$pfj]=='1')
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
						{
							$registro_html->frmError[$validar[$i]]=1;
						}
					$registro_html->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";	
				}
			}
			
			$registros=$registro->GetDatosEvolucionPF($evolucion,$inscripcion,$datosPaciente['sexo_id']);
			$signos=$registro->GetDatosSignosConsultas($evolucion);
			$pruebas=$registro->GetCargosPruebas($programa);	
			$Laboratorios=$registro->GetSolicitudesCargos($evolucion,$inscripcion,$programa);	
			$resultadosLab=$apdCL->ConsultaResultadosPyp($evolucion,$inscripcion);

			return $registro_html->frmForma($vector,$registros,$metodos,$datosprofesional,$signos,$pruebas,$Laboratorios,$resultadosLab); 
		}
		
		function ValidarDatos($datos,$vector,$sexo)
		{
			$pfj=SessionGetvar("Prefijo");
			$registro=$this->registro;
			
			$error=array();
			
			if($sexo=='M')
			{
				if(!$datos['metodo'.$pfj])
					$error[]=$vector[0];
					
				if(!$datos['fecha_ini'.$pfj])
					$error[]=$vector[1];	
				
				if(!empty($datos['fecha_ini'.$pfj]))
				{
					$datos['fecha_ini'.$pfj]=$this->FechaStamp($datos['fecha_ini'.$pfj]);
					if($datos['fecha_ini'.$pfj] > date('Y-m-d'))
						$error[]=$vector[1];
				}
				
				if(!$datos['testiculos'.$pfj])
					$error[]=$vector[3];	
					
				if(!$datos['mamas'.$pfj])
					$error[]=$vector[4];
			}
			elseif($sexo=='F')
			{
				$k=0;
				foreach($vector as $key=>$valor)
				{
					$nombre=strtolower(str_replace(" ","_",$key));	
					
					if(!$datos[$nombre.$pfj] AND $valor[0]!="Titulo" AND $valor[0]!="SubTitulo" AND $valor[0]!="Label")
					{
						if($nombre=="tension_arterial")
						{
							if(!$datos['ta_alta'.$pfj] OR !is_numeric($datos['ta_alta'.$pfj]))
								$error[]="C$k";
							if(!$datos['ta_baja'.$pfj] OR !is_numeric($datos['ta_baja'.$pfj]))
								$error[]="C$k";
						}
						elseif($nombre!="motivo_cierre_de_caso" AND $nombre!="cual" AND $key!="P" AND $nombre!="fecha_terminacion_del_metodo")
									$error[]="C$k";
					}
					$k++;
				}
			}

			return $error;
		}
		
		function FechaStamp($fecha)
		{
			if($fecha)
			{
				$fech = strtok ($fecha,"-");
				for($l=0;$l<3;$l++)
				{
					$date[$l]=$fech;
					$fech = strtok ("-");
				}
				
				return  ceil($date[2])."-".str_pad(ceil($date[1]),2,0,STR_PAD_LEFT)."-".str_pad(ceil($date[0]),2,0,STR_PAD_LEFT);
			}
		}
		
	}
?>