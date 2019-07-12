<?php

/**
* Submodulo de Atención.
*
* Submodulo para manejar el tipo de atención (rips) de un paciente en una evolución.
* @version 1.0
* @package SIIS
* $Id: hc_NotasOperatorias.php,v 1.21 2007/11/06 15:08:18 tizziano Exp $
*/


/**
* Atencion
*
* Clase para accesar los metodos privados de la clase de presentación, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserción y la consulta del
* submodulo de atención.
*/

class NotasOperatorias extends hc_classModules
{
 /**
* Esta función Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/
	function NotasOperatorias(){
	  $this->limit=GetLimitBrowser();
    return true;
	}


/**
* Esta función retorna los datos de concernientes a la version del submodulo
* @access private
*/

// 	function GetVersion()
// 	{
// 		$informacion=array(
// 		'version'=>'1',
// 		'subversion'=>'0',
// 		'revision'=>'0',
// 		'fecha'=>'01/27/2005',
// 		'autor'=>'JAIME ANDRES VALENCIA',
// 		'descripcion_cambio' => '',
// 		'requiere_sql' => false,
// 		'requerimientos_adicionales' => '',
// 		'version_kernel' => '1.0'
// 		);
// 		return $informacion;
// 	}


/**
* Esta función retorna los datos de la impresión de la consulta del submodulo.
*
* @access private
* @return text Datos HTML de la pantalla.
*/
	function GetConsulta(){
		if($this->frmConsulta()==false){
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
		$imprimir=$this->frmHistoria();
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


/**
* Esta función retorna la presentación del submodulo (consulta o inserción).
*
* @access public
* @return text Datos HTML de la pantalla.
* @param text Determina la acción a realizar.
*/
	function GetForma(){
		
	  //$this->QXcumplimiento=16;
		if(empty($_REQUEST['accion'.$this->frmPrefijo])){		
			unset($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]);
			//Datos de la Programacion			
			if(!is_array($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo])){
				
				$datos=$this->NotaOperatoriaActual();
				
				if(is_array($datos)){	
					
					(list($fechaIn,$horaIn)=explode(" ",$datos['hora_inicio']));			
					(list($anoIni,$mesIni,$diaIni)=explode("-",$fechaIn));
					(list($hhIn,$mmIn,$ddIn)=explode(":",$horaIn));
					(list($fechaFn,$horaFn)=explode(" ",$datos['hora_fin']));			
					(list($anoFin,$mesFin,$diaFin)=explode("-",$fechaIn));			
					(list($hhFn,$mmFn)=explode(":",$horaFn));
					$DuracionMin=(mktime($hhFn,$mmFn+1,0,$mesFin,$diaFin,$anoFin)-mktime($hhIn,$mmIn,0,$mesIni,$diaIni,$anoIni))/60;			
					$horasDur=(int)($DuracionMin/60);
					$minDur=($DuracionMin%60);					
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROGRAMACION']=$datos['programacion_id'];
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']=$datos['hc_nota_operatoria_cirugia_id'];
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['fechainicio']=$diaIni.'-'.$mesIni.'-'.$anoIni;
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['hora']=$hhIn;
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['minutos']=$mmIn;
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['horadur']=$horasDur;
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['minutosdur']=$minDur;
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['quirofano']=$datos['quirofano_id'];
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['viaAcceso']=$datos['via_acceso'];
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['ambitoCirugia']=$datos['ambito_cirugia'];
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['finalidadCirugia']=$datos['finalidad_procedimiento_id'];
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['tipoCirugia']=$datos['tipo_cirugia'];
					//$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PRE_QUIRURGICO'][$datos['diagnostico_pre_qx']][$datos['tipo_diagnostico_pre_qx']]=$datos['diagnostico_nombre_pre_qx'];
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['POST_QUIRURGICO'][$datos['diagnostico_post_qx']][$datos['tipo_diagnostico_post_qx']]=$datos['diagnostico_nombre_post_qx'];
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['COMPLICACION'][$datos['diagnostico_id_complicacion']][$datos['tipo_diagnostico_complicacion']]=$datos['diagnostico_nombre_complica'];
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['justificacion']=$datos['justificacion_norealizados'];
					
					if($datos['envio_patologico']==1){
						$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['SelMaterialPat']=1;
					}else{
						unset($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['SelMaterialPat']);
					}
					if($datos['envio_cultivo']==1){
						$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['SelCultivo']=1;
					}else{
						unset($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['SelCultivo']);
					}					
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['MaterialPat']=$datos['descripcion_envio_patologico'];
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['Cultivo']=$datos['descripcion_envio_cultivo'];
					
					$procedimientos=$this->ProcedimientosNotasOperatorias();
					if($procedimientos){
						for($i=0;$i < sizeof($procedimientos);$i++){
							if($procedimientos[$i]['realizado']=='1'){
								if($procedimientos[$i]['programado']=='1'){
									$diagnosticos=$this->DiagsProcedimientosNotasOperatorias($procedimientos[$i]['procedimiento_qx']);
									if($diagnosticos){
										for($j=0;$j < sizeof($diagnosticos);$j++){
											$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['DIAGNOSTICOS'][$procedimientos[$i]['procedimiento_qx']][$diagnosticos[$j]['diagnostico_id']][$diagnosticos[$j]['tipo_diagnostico']]=$diagnosticos[$j]['diagnostico_nombre'];
											if($diagnosticos[$j]['sw_principal']=='1'){
												$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['DIAGNOSTICO_PRINCIPAL'][$procedimientos[$i]['procedimiento_qx']]=$diagnosticos[$j]['diagnostico_id'];
											}											
										}
									}	
									$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['1'][$procedimientos[$i]['procedimiento_qx']]=$procedimientos[$i]['descripcion'];
								}else{
									$diagnosticos=$this->DiagsProcedimientosNotasOperatorias($procedimientos[$i]['procedimiento_qx']);
									if($diagnosticos){
										for($j=0;$j < sizeof($diagnosticos);$j++){
											$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['DIAGNOSTICOS'][$procedimientos[$i]['procedimiento_qx']][$diagnosticos[$j]['diagnostico_id']][$diagnosticos[$j]['tipo_diagnostico']]=$diagnosticos[$j]['diagnostico_nombre'];
											if($diagnosticos[$j]['sw_principal']=='1'){
												$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['DIAGNOSTICO_PRINCIPAL'][$procedimientos[$i]['procedimiento_qx']]=$diagnosticos[$j]['diagnostico_id'];
											}											
										}
									}
									$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['3'][$procedimientos[$i]['procedimiento_qx']]=$procedimientos[$i]['descripcion'];								
								}							
							}else{
								$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['2'][$procedimientos[$i]['procedimiento_qx']]=$procedimientos[$i]['descripcion'];								
							}
              
              $procedimientosOpc=$this->BuscarProcedimientosInsertadosNotaOperatoria($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID'],$procedimientos[$i]['procedimiento_qx']); 
              for($m=0;$m<sizeof($procedimientosOpc);$m++){
                $_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS_OPCIONES'][$procedimientos[$i]['procedimiento_qx']][$procedimientosOpc[$m]['procedimiento_opcion']]=$procedimientosOpc[$m]['descripcion'];
              }
              							
						}					
					}
					$datosProfesionales=$this->DatosProfesionalesCirugia($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROGRAMACION']);
					if($datosProfesionales){					
						$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['anestesiologo']=$datosProfesionales['tercero_id'].'/'.$datosProfesionales['tipo_id_tercero'];
						$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['ayudante']=$datosProfesionales['ayudante_id'].'/'.$datosProfesionales['tipo_id_ayudante'];
						$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['instrumentador']=$datosProfesionales['instrumentista_id'].'/'.$datosProfesionales['tipo_id_instrumentista'];
						$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['circulante']=$datosProfesionales['circulante_id'].'/'.$datosProfesionales['tipo_id_circulante'];
					}					
				}						
			}			
			
			if(!is_array($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo])){				
				$programacion=$this->ProgramacionActivaPaciente();
				if($programacion){
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROGRAMACION']=$programacion;
				}			
				$datosProfesionales=$this->DatosProfesionalesCirugia($programacion);
				if($datosProfesionales){
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['anestesiologo']=$datosProfesionales['tercero_id'].'/'.$datosProfesionales['tipo_id_tercero'];
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['ayudante']=$datosProfesionales['ayudante_id'].'/'.$datosProfesionales['tipo_id_ayudante'];
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['instrumentador']=$datosProfesionales['instrumentista_id'].'/'.$datosProfesionales['tipo_id_instrumentista'];
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['circulante']=$datosProfesionales['circulante_id'].'/'.$datosProfesionales['tipo_id_circulante'];
				}
				$datos=$this->DatosCirugia($programacion);
				if($datos){	
					(list($fechaIn,$horaIn)=explode(" ",$datos['hora_inicio']));			
					(list($anoIni,$mesIni,$diaIni)=explode("-",$fechaIn));
					(list($hhIn,$mmIn,$ddIn)=explode(":",$horaIn));
					(list($fechaFn,$horaFn)=explode(" ",$datos['hora_fin']));			
					(list($anoFin,$mesFin,$diaFin)=explode("-",$fechaIn));			
					(list($hhFn,$mmFn)=explode(":",$horaFn));
					$DuracionMin=(mktime($hhFn,$mmFn+1,0,$mesFin,$diaFin,$anoFin)-mktime($hhIn,$mmIn,0,$mesIni,$diaIni,$anoIni))/60;			
					$horasDur=(int)($DuracionMin/60);
					$minDur=($DuracionMin%60);
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['fechainicio']=$diaIni.'-'.$mesIni.'-'.$anoIni;
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['hora']=$hhIn;
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['minutos']=$mmIn;
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['horadur']=$horasDur;
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['minutosdur']=$minDur;
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['quirofano']=$datos['quirofano_id'];
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['viaAcceso']=$datos['via_acceso'];
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['ambitoCirugia']=$datos['ambito_cirugia'];
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['tipoCirugia']=$datos['tipo_cirugia'];
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['finalidadCirugia']=$datos['finalidad_procedimiento_id'];
				}				
				$procedimientos=$this->BuscarProcedimientosCirugia($programacion);
				if($procedimientos){
					for($i=0;$i<sizeof($procedimientos);$i++){
						$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['1'][$procedimientos[$i]['procedimiento_qx']]=$procedimientos[$i]['cargo'];
            $procedimientosOpc=$this->BuscarProcedimientosInsertados($programacion,$procedimientos[$i]['procedimiento_qx']); 
            for($m=0;$m<sizeof($procedimientosOpc);$m++){
              $_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS_OPCIONES'][$procedimientos[$i]['procedimiento_qx']][$procedimientosOpc[$m]['procedimiento_opcion']]=$procedimientosOpc[$m]['descripcion'];
            }
					}	
				}        
			}
			if(!is_array($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo])){
				$notas=$this->ConsultaNotasOperatoriasRealizadas();
				if(is_array($notas)){
					$this->FrmConsultaNotasOperatoriasRealizadas($notas);
					return true;
				}
			}	
	    $this->frmForma();
		}else{
			
			if($_REQUEST['accion'.$this->frmPrefijo]=='FrmNotasOperatorias'){
				$this->ActualizarVariablesSession();
				if($_REQUEST['GuardarNota'.$this->frmPrefijo]){
					if($this->InsertDatos()==true){
						$this->frmError["MensajeError"]="Datos Guardados Satisfactoriamente";
					}else{
						$this->frmError["MensajeError"]="Error al Guardar los Datos";
					}
				}elseif($_REQUEST['EliminarProc'.$this->frmPrefijo]){					
					$vector=$_REQUEST['SeleccionElimina'.$this->frmPrefijo];
					foreach($vector as $codigo=>$procedimiento){						
						unset($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['1'][$codigo]);
						$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['2'][$codigo]=$procedimiento;	
					}
				}elseif($_REQUEST['AdicionarProc'.$this->frmPrefijo]){					
					$this->frmForma_ProcedimientosQX();
					return true;
				}elseif($_REQUEST['BuscarPreQX'.$this->frmPrefijo]){
					$this->frmForma_BuscarDiagnosticos($tipoDiag='PreQX');
					return true;
				}elseif($_REQUEST['BuscarPostQX'.$this->frmPrefijo]){
					$this->frmForma_BuscarDiagnosticos($tipoDiag='PostQX');
					return true;
				}elseif($_REQUEST['BuscarComplicacion'.$this->frmPrefijo]){
					$this->frmForma_BuscarDiagnosticos($tipoDiag='Complicacion');
					return true;
				}
				$this->frmForma();
				return true;
			}elseif($_REQUEST['accion'.$this->frmPrefijo]=='FrmBuscadorProcedimientos'){
				if($_REQUEST['buscar'.$this->frmPrefijo]){					
					$this->frmForma_ProcedimientosQX();					
				}elseif($_REQUEST['volver'.$this->frmPrefijo]){
					$this->frmForma();							
				}elseif($_REQUEST['guardar'.$this->frmPrefijo]){
					$vector=$_REQUEST['op'.$this->frmPrefijo];
					foreach($vector as $cargo=>$procedimiento){
						$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['3'][$cargo]=$procedimiento;
					}
					$this->frmForma();
				}
				return true;
			}elseif($_REQUEST['accion'.$this->frmPrefijo]=='EliminaProcedimientoVec3'){
        $this->ActualizarVariablesSession();
				unset($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['3'][$_REQUEST['cargo'.$this->frmPrefijo]]);
				$this->frmForma();
				return true;				
			}elseif($_REQUEST['accion'.$this->frmPrefijo]=='FrmEditarEspecificaionProc'){
        $this->ActualizarVariablesSession();
				$this->frmForma_Modificar_Observacion($_REQUEST['cargo'.$this->frmPrefijo],$_REQUEST['descripcion'.$this->frmPrefijo]);
				return true;
			}elseif($_REQUEST['accion'.$this->frmPrefijo]=='insertar_varios_diagnosticos'){			
				if($_REQUEST['guardar'.$this->frmPrefijo]){
					
					$vector=$_REQUEST['opD'.$this->frmPrefijo];
					$vectorDiags=$_REQUEST['dx'.$this->frmPrefijo];
					foreach($vector as $cargoProc=>$Vecdiagnostico){
						foreach($Vecdiagnostico as $codiag=>$nombreDiag){											
							if(empty($vectorDiags[$cargoProc][$codiag])){
								$tipoDiag='1';
							}else{
								$tipoDiag=$vectorDiags[$cargoProc][$codiag];
							}
							$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['DIAGNOSTICOS'][$cargoProc][$codiag][$tipoDiag]=$nombreDiag;
							if(empty($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['DIAGNOSTICO_PRINCIPAL'][$cargoProc])){
								$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['DIAGNOSTICO_PRINCIPAL'][$cargoProc]=$codiag;
							}
						}	
					}					
				}elseif($_REQUEST['Volver'.$this->frmPrefijo]){
					$this->frmForma();
					return true;
				}
				$this->frmForma_Modificar_Observacion($_REQUEST['cargo'.$this->frmPrefijo],$_REQUEST['descripcion'.$this->frmPrefijo]);
				return true;
			}elseif($_REQUEST['accion'.$this->frmPrefijo]=='FrmModificarProdedimiento'){
				
				if($_REQUEST['CambioDiagPrincipal'.$this->frmPrefijo]){
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['DIAGNOSTICO_PRINCIPAL'][$_REQUEST['cargo'.$this->frmPrefijo]]=$_REQUEST['codiag'.$this->frmPrefijo];						
				}elseif($_REQUEST['EliminacionDiagnostico'.$this->frmPrefijo]){
					unset($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['DIAGNOSTICOS'][$_REQUEST['cargo'.$this->frmPrefijo]][$_REQUEST['codiag'.$this->frmPrefijo]]);
					if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['DIAGNOSTICO_PRINCIPAL'][$_REQUEST['cargo'.$this->frmPrefijo]]==$_REQUEST['codiag'.$this->frmPrefijo]){
						unset($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['DIAGNOSTICO_PRINCIPAL'][$_REQUEST['cargo'.$this->frmPrefijo]]);
						$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['DIAGNOSTICO_PRINCIPAL'][$_REQUEST['cargo'.$this->frmPrefijo]]=$_REQUEST['codiag_uno'.$this->frmPrefijo];
					}
				}elseif($_REQUEST['guardar'.$this->frmPrefijo]){          
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['OBSERVACIONES'][$_REQUEST['cargo'.$this->frmPrefijo]]=$_REQUEST['obs'.$this->frmPrefijo];
					$this->frmForma();
					return true;
				}elseif($_REQUEST['procedimiento_opcion'.$this->frmPrefijo]){
          unset($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS_OPCIONES'][$_REQUEST['cargo'.$this->frmPrefijo]][$_REQUEST['procedimiento_opcion'.$this->frmPrefijo]]);  
          
        }elseif($_REQUEST['modify_procedimiento_opcion'.$this->frmPrefijo]){
          unset($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS_OPCIONES'][$_REQUEST['cargo'.$this->frmPrefijo]]);      
          $vector=$_REQUEST['seleccion'.$this->frmPrefijo];
          for($i=0;$i<sizeof($vector);$i++){          
            $_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS_OPCIONES'][$_REQUEST['cargo'.$this->frmPrefijo]][$vector[$i]]=$this->NombreOpcionProcedimiento($vector[$i],$_REQUEST['cargo'.$this->frmPrefijo]);  
          }
        }
				$this->frmForma_Modificar_Observacion($_REQUEST['cargo'.$this->frmPrefijo],$_REQUEST['descripcion'.$this->frmPrefijo]);
				return true;
			}elseif($_REQUEST['accion'.$this->frmPrefijo]=='FrmBuscarDiagnosticosPost'){
			
				if($_REQUEST['buscar'.$this->frmPrefijo]){
					$this->frmForma_BuscarDiagnosticos($_REQUEST['tipoDiag'.$this->frmPrefijo]);
					return true;
				}elseif($_REQUEST['Volver'.$this->frmPrefijo]){
					$this->frmForma();
					return true;				
				}elseif($_REQUEST['guardar'.$this->frmPrefijo]){					
					$vector=$_REQUEST['opD'.$this->frmPrefijo];					
					(list($codigo,$diagnostico)=explode('||//',$vector[0]));
					$vectorDiags=$_REQUEST['dx'.$this->frmPrefijo];
					$tipo=$vectorDiags[$codigo];					
					if(empty($tipo)){
						$tipo=1;
					}	
									
					if($_REQUEST['tipoDiag'.$this->frmPrefijo]=='PostQX'){
						unset($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['POST_QUIRURGICO']);
						$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['POST_QUIRURGICO'][$codigo][$tipo]=$diagnostico;
					}elseif($_REQUEST['tipoDiag'.$this->frmPrefijo]=='PreQX'){						
						unset($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PRE_QUIRURGICO']);
						$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PRE_QUIRURGICO'][$codigo][$tipo]=$diagnostico;
					}else{
						unset($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['COMPLICACION']);
						$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['COMPLICACION'][$codigo][$tipo]=$diagnostico;						
					}
					$this->frmForma();
					return true;
				}	
			}
		  /*if($_REQUEST['accion'.$this->frmPrefijo]=='Buscadordiagnostico'){
        $this->GuardarDatosNotasOperatorias();
				return true;
			}elseif($_REQUEST['accion'.$this->frmPrefijo]=='BusquedaTodosDiagnosticos'){
        if($_REQUEST['buscar'.$this->frmPrefijo] || $_REQUEST['buscar1'.$this->frmPrefijo]){
				  $this->BuscadorDiagnostico();
				}else{
          $this->GuardarDiagnosticosNotasOperatorias();
					return true;
				}
			}elseif($_REQUEST['accion'.$this->frmPrefijo]=='Bucardiagnostico'){
        $this->BusquedaDiagnostico();
			}elseif($_REQUEST['accion'.$this->frmPrefijo]=='SeleccionDiagnostico'){
			  if($_REQUEST['salir'.$this->frmPrefijo]){
          $this->frmForma();
					return true;
				}
				if($_REQUEST['bandera'.$this->frmPrefijo]==1){
					if($_REQUEST['buscar'.$this->frmPrefijo]){
            $diagnostico=$_REQUEST['codigoDiagnostico'.$this->frmPrefijo];
						$nomdiagnostico=$_REQUEST['nombreDiagnostico'.$this->frmPrefijo];
					}elseif($_REQUEST['buscar1'.$this->frmPrefijo]){
						$complicacion=$_REQUEST['codigoDiagnostico'.$this->frmPrefijo];
						$nomcomplicacion=$_REQUEST['nombreDiagnostico'.$this->frmPrefijo];
					}
					if($nomdiagnostico){
            $_REQUEST['cargo'.$this->frmPrefijo]=$nomdiagnostico;
					}
					if($diagnostico){
            $_REQUEST['codigo'.$this->frmPrefijo]=$diagnostico;
					}
					if($nomcomplicacion){
            $_REQUEST['cargo1'.$this->frmPrefijo]=$nomcomplicacion;
					}
					if($complicacion){
            $_REQUEST['codigo1'.$this->frmPrefijo]=$complicacion;
					}
					$this->frmForma();
					return true;
				}
				if($_REQUEST['Buscar'.$this->frmPrefijo]){
          $this->BuscadorDiagnostico($_REQUEST['codigoDes'.$this->frmPrefijo],$_REQUEST['descripcionDes'.$this->frmPrefijo]);
				}
			}elseif($_REQUEST['accion'.$this->frmPrefijo]=='GuardarProcedimientoNota'){
        $this->GuardarProcedimientoNota();
				return true;
			}elseif($_REQUEST['accion'.$this->frmPrefijo]=='InsercionProcedimientosNota'){
			  if($_REQUEST['buscar'.$this->frmPrefijo] || $_REQUEST['buscar1'.$this->frmPrefijo]){
				  $this->BuscadorDiagnostico();
				}elseif($_REQUEST['CancelarPro'.$this->frmPrefijo]){
				  $_REQUEST['ayudante'.$this->frmPrefijo]='';
					$_REQUEST['descripcionQuirugica'.$this->frmPrefijo]='';
					$_REQUEST['nuevoProcedimiento'.$this->frmPrefijo]='';
					$_REQUEST['hallazgos'.$this->frmPrefijo]='';
					$_REQUEST['textnuevoProcedimiento'.$this->frmPrefijo]='';
          $this->frmForma();
					return true;
				}elseif($_REQUEST['GuardarPro'.$this->frmPrefijo]){
          $this->InsercionProcedimientosNotaBD();
				  return true;
				}elseif($_REQUEST['buscarProc'.$this->frmPrefijo]){
				  if($_REQUEST['bandera'.$this->frmPrefijo]==1){
					  $_REQUEST['nuevoProcedimiento'.$this->frmPrefijo]=$_REQUEST['cargo'.$this->frmPrefijo];
						$_REQUEST['textnuevoProcedimiento'.$this->frmPrefijo]=$_REQUEST['nombreProcedimiento'.$this->frmPrefijo];
            $this->frmForma();
						return true;
					}
					$this->BuscadorCups($_REQUEST['tipoProcedimiento'.$this->frmPrefijo],$_REQUEST['codigoPro'.$this->frmPrefijo],$_REQUEST['descripcionPro'.$this->frmPrefijo]);
					return true;
				}elseif($_REQUEST['salir'.$this->frmPrefijo]){
				  $this->frmForma();
					return true;
				}
			}elseif($_REQUEST['accion'.$this->frmPrefijo]=='EliminaProcedimientoNota'){
        $this->EliminaProcedimientoNota();
				return true;
			}elseif($this->InsertDatos()==true){
				$this->frmForma();
			}*/
		}
		return $this->salida;
	}
	
	function ProcedimientosNotasOperatorias(){
		list($dbconn) = GetDBconn();
		$query="SELECT a.procedimiento_qx,b.descripcion,a.realizado,a.observaciones,
		(SELECT 1 
		FROM qx_procedimientos_programacion c 
		WHERE c.programacion_id='".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROGRAMACION']."' AND 
		c.procedimiento_qx=a.procedimiento_qx) as programado
		FROM hc_notas_operatorias_procedimientos a,cups b
		WHERE a.hc_nota_operatoria_cirugia_id='".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']."' AND 
		a.procedimiento_qx=b.cargo";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo en la tabla qx_cumplimientos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->RecordCount() > 0){
				while(!$result->EOF){
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}				
			}
		}
		return $vars;	
	}
	
	function DiagsProcedimientosNotasOperatorias($procedimiento){
		list($dbconn) = GetDBconn();
		$query="SELECT a.diagnostico_id,b.diagnostico_nombre,a.sw_principal,a.tipo_diagnostico
		FROM hc_notas_operatorias_procedimientos_diags a,diagnosticos b
		WHERE a.hc_nota_operatoria_cirugia_id='".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']."' AND 
		a.procedimiento_qx='$procedimiento' AND a.diagnostico_id=b.diagnostico_id";
		
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo en la tabla qx_cumplimientos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->RecordCount() > 0){
				while(!$result->EOF){
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}				
			}
		}
		return $vars;		
	}
	
	
	function NotaOperatoriaActual(){
		list($dbconn) = GetDBconn();
		$query="SELECT a.hc_nota_operatoria_cirugia_id,a.quirofano_id,a.hora_inicio,
		a.hora_fin,a.usuario_id,a.fecha_registro,a.via_acceso,a.tipo_cirugia,
		a.ambito_cirugia,a.finalidad_procedimiento_id,a.evolucion_id,
		a.justificacion_norealizados,a.programacion_id,		
		a.diagnostico_post_qx,a.tipo_diagnostico_post_qx,b.diagnostico_nombre as diagnostico_nombre_post_qx,
		a.diagnostico_id_complicacion,a.tipo_diagnostico_complicacion,c.diagnostico_nombre as diagnostico_nombre_complica,
		a.envio_patologico,a.descripcion_envio_patologico,a.envio_cultivo,a.descripcion_envio_cultivo 
		FROM hc_notas_operatorias_cirugias a		
		LEFT JOIN diagnosticos b ON(a.diagnostico_post_qx=b.diagnostico_id)
		LEFT JOIN diagnosticos c ON(a.diagnostico_id_complicacion=c.diagnostico_id)
		WHERE a.evolucion_id='".$this->evolucion."' AND a.usuario_id='".UserGetUID()."'";
		//a.diagnostico_pre_qx,a.tipo_diagnostico_pre_qx,bb.diagnostico_nombre as diagnostico_nombre_pre_qx,
		//LEFT JOIN diagnosticos bb ON(a.diagnostico_pre_qx=bb.diagnostico_id)
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo en la tabla qx_cumplimientos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->RecordCount() > 0){
				$datos=$result->GetRowAssoc($toUpper=false);
			}
		}
		return $datos;	
	}
	
	
	function ActualizarVariablesSession(){
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['fechainicio']=$_REQUEST['fechainicio'.$this->frmPrefijo];
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['hora']=$_REQUEST['hora'.$this->frmPrefijo];
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['minutos']=$_REQUEST['minutos'.$this->frmPrefijo];
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['horadur']=$_REQUEST['horadur'.$this->frmPrefijo];
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['minutosdur']=$_REQUEST['minutosdur'.$this->frmPrefijo];
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['quirofano']=$_REQUEST['quirofano'.$this->frmPrefijo];
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['viaAcceso']=$_REQUEST['viaAcceso'.$this->frmPrefijo];
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['ambitoCirugia']=$_REQUEST['ambitoCirugia'.$this->frmPrefijo];
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['tipoCirugia']=$_REQUEST['tipoCirugia'.$this->frmPrefijo];
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['finalidadCirugia']=$_REQUEST['finalidadCirugia'.$this->frmPrefijo];	
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['justificacion']=$_REQUEST['justificacion'.$this->frmPrefijo];	
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['anestesiologo']=$_REQUEST['anestesista'.$this->frmPrefijo];	
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['ayudante']=$_REQUEST['ayudante'.$this->frmPrefijo];	
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['instrumentador']=$_REQUEST['instrumentista'.$this->frmPrefijo];	
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['circulante']=$_REQUEST['circulante'.$this->frmPrefijo];	;
		if($_REQUEST['SelMaterialPat'.$this->frmPrefijo]){
			$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['SelMaterialPat']=1;
		}else{
			unset($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['SelMaterialPat']);
		}
		if($_REQUEST['SelCultivo'.$this->frmPrefijo]){
			$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['SelCultivo']=1;
		}else{
			unset($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['SelCultivo']);
		}					
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['MaterialPat']=$_REQUEST['MaterialPat'.$this->frmPrefijo];
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['Cultivo']=$_REQUEST['Cultivo'.$this->frmPrefijo];
		
	}
	
	
	function ProgramacionActivaPaciente(){
		list($dbconn) = GetDBconn();
		$query="SELECT a.programacion_id  
		FROM qx_programaciones a
		LEFT JOIN hc_notas_operatorias_cirugias x ON (x.programacion_id=a.programacion_id AND x.usuario_id='".UserGetUID()."')
		,qx_quirofanos_programacion b,estacion_enfermeria_qx_pacientes_ingresados c
		WHERE a.tipo_id_paciente='".$this->tipoidpaciente."' AND a.paciente_id='".$this->paciente."' AND a.estado IN ('1','2') AND 
		a.programacion_id=b.programacion_id AND b.qx_tipo_reserva_quirofano_id='3' AND  
		x.hc_nota_operatoria_cirugia_id IS NULL AND a.programacion_id=c.programacion_id AND 
		c.sw_estado IN ('1','0');";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo en la tabla qx_cumplimientos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->RecordCount() > 0){
				return $result->fields[0];
			}
		}
		return 0;	
	}
	
	function DatosProfesionalesCirugia($programacion){
    list($dbconn) = GetDBconn();
		        	$query="SELECT 	x.tipo_id_anestesiologo as tipo_id_tercero,x.anestesiologo_id as tercero_id,
													x.tipo_id_ayudante,x.ayudante_id,
													x.tipo_id_instrumentista,x.instrumentista_id,
													x.tipo_id_circulante,x.circulante_id
													FROM hc_notas_operatorias_cirugias x
													WHERE x.programacion_id='$programacion' 
													ORDER BY x.hc_nota_operatoria_cirugia_id DESC";
							$result = $dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0){
								$this->error = "Error al Cargar el Modulo en la tabla qx_cumplimientos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
							}else{
								if(!$result->EOF){
									$datosProfesionales=$result->GetRowAssoc($toUpper=false);
								}else{
									$query="SELECT a.tipo_id_tercero,a.tercero_id,
														a.tipo_id_ayudante,a.ayudante_id,
														a.tipo_id_instrumentista,a.instrumentista_id,
														a.tipo_id_circulante,a.circulante_id
														FROM qx_anestesiologo_programacion a
														WHERE a.programacion_id='$programacion'";
														
									$result = $dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0){
										$this->error = "Error al Cargar el Modulo en la tabla qx_cumplimientos";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
									}else{
										if(!$result->EOF){
											$datosProfesionales=$result->GetRowAssoc($toUpper=false);
										}					
									}
								}
							}		
		
							/*
							b.nombre_tercero as anestesiologo,c.nombre_tercero as ayudante,c.nombre_tercero as instrumentador,e.nombre_tercero as circulante
							LEFT JOIN terceros b ON (a.tipo_id_tercero=b.tipo_id_tercero AND a.tercero_id=b.tercero_id)
							LEFT JOIN terceros c ON (a.tipo_id_ayudante=c.tipo_id_tercero AND a.ayudante_id=c.tercero_id)
							LEFT JOIN terceros d ON (a.tipo_id_instrumentista=d.tipo_id_tercero AND a.instrumentista_id=d.tercero_id)
							LEFT JOIN terceros e ON (a.tipo_id_circulante=e.tipo_id_tercero AND a.circulante_id=e.tercero_id)
							*/		
						return $datosProfesionales;
	}
	
	function DatosCirugia($programacion){
    list($dbconn) = GetDBconn();
		/*
		c.descripcion as via,d.descripcion as tipo,e.descripcion as ambito,f.descripcion as finalidad,
		a.via_acceso,a.tipo_cirugia,a.ambito_cirugia,a.finalidad_procedimiento_id
		LEFT JOIN qx_datos_procedimientos_cirugias a ON(a.programacion_id=b.programacion_id) 
		LEFT JOIN qx_vias_acceso c ON (c.via_acceso=a.via_acceso)
		LEFT JOIN qx_tipos_cirugia d ON (d.tipo_cirugia_id=a.tipo_cirugia)
		LEFT JOIN qx_ambitos_cirugias e ON (e.ambito_cirugia_id=a.ambito_cirugia)
		LEFT JOIN qx_finalidades_procedimientos f ON (f.finalidad_procedimiento_id=a.finalidad_procedimiento_id),
		*/
		$query="SELECT b.hora_inicio,b.hora_fin,g.descripcion as quirofano,b.quirofano_id    
		FROM qx_quirofanos_programacion b,		
		qx_quirofanos g
		WHERE b.programacion_id='".$programacion."'  AND 
		b.qx_tipo_reserva_quirofano_id='3' AND b.quirofano_id=g.quirofano";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo en la tabla qx_cumplimientos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if(!$result->EOF){
				$datos=$result->GetRowAssoc($toUpper=false);
			}
		}
		return $datos;
	}
	
	function BuscarProcedimientosCirugia($programacion){
    list($dbconn) = GetDBconn();		
		$query = "SELECT a.procedimiento_qx,b.descripcion as cargo    
		FROM qx_procedimientos_programacion a,cups b,profesionales_usuarios c
		WHERE a.programacion_id='".$programacion."' AND a.procedimiento_qx=b.cargo AND c.usuario_id='".UserGetUID()."' AND
		c.tipo_tercero_id=a.tipo_id_cirujano AND c.tercero_id=a.cirujano_id";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			while(!$resulta->EOF){
				$vars[]=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
			}
		}
		$resulta->Close();
		//print_R($vars);
 		return $vars;
	}
	
	
	
/**
* Esta función inserta los datos del submodulo.
*
* @access private
* @return boolean Informa si lo logro o no.
*/
  function InsertDatos()
	{
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['fechainicio']=$_REQUEST['fechainicio'.$this->frmPrefijo];
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['hora']=$_REQUEST['hora'.$this->frmPrefijo];
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['minutos']=$_REQUEST['minutos'.$this->frmPrefijo];
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['horadur']=$_REQUEST['horadur'.$this->frmPrefijo];
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['minutosdur']=$_REQUEST['minutosdur'.$this->frmPrefijo];
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['quirofano']=$_REQUEST['quirofano'.$this->frmPrefijo];
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['viaAcceso']=$_REQUEST['viaAcceso'.$this->frmPrefijo];
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['ambitoCirugia']=$_REQUEST['ambitoCirugia'.$this->frmPrefijo];
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['tipoCirugia']=$_REQUEST['tipoCirugia'.$this->frmPrefijo];
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['finalidadCirugia']=$_REQUEST['finalidadCirugia'.$this->frmPrefijo];
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['anestesiologo']=$_REQUEST['anestesista'.$this->frmPrefijo];
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['ayudante']=$_REQUEST['ayudante'.$this->frmPrefijo];
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['instrumentador']=$_REQUEST['instrumentista'.$this->frmPrefijo];
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['circulante']=$_REQUEST['circulante'.$this->frmPrefijo];
		if(!$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['fechainicio'] || $_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['hora']==-1 || $_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['minutos']==-1 || $_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['horadur']==-1 || $_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['minutosdur']==-1){
		  $this->frmError["MensajeError"]="La Fecha de Inicio y la Duracion son Datos Obligatorios";
		  $this->frmForma();
			return true;
		}
		if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['fechainicio']){
		  $fechaInicio=ereg_replace("-","/",$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['fechainicio']);
      (list($dia,$mes,$ano)=explode('/',$fechaInicio));
      $fechaInicio=$ano.'-'.$mes.'-'.$dia.' '.$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['hora'].':'.$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['minutos'].':'.'00';
		}
		if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['quirofano']==-1){$quirofano='NULL';	}
		else{$quirofano="'".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['quirofano']."'";	}
		if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['viaAcceso']==-1){$viaAcceso='NULL';	}
		else{$viaAcceso="'".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['viaAcceso']."'";}
		if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['tipoCirugia']==-1){$tipoCirugia='NULL';}
		else{$tipoCirugia="'".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['tipoCirugia']."'";}
		if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['ambitoCirugia']==-1){$ambitoCirugia='NULL';}
		else{$ambitoCirugia="'".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['ambitoCirugia']."'";}
		if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['finalidadCirugia']==-1){$finalidadCirugia='NULL';}
		else{$finalidadCirugia="'".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['finalidadCirugia']."'";}
		$fechaFin=date("Y-m-d H:i:s",mktime(($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['hora']+$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['horadur']),($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['minutos']+($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['minutosdur']-1)),0,$mes,$dia,$ano));
		/*if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PRE_QUIRURGICO']){
			foreach($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PRE_QUIRURGICO'] as $codigo=>$vect){
				foreach($vect as $tipo=>$diagnostico){
					if($codigo){
						$diagnostico_pre_qx="'".$codigo."'";
						$tipo_pre_qx=$tipo;
					}else{
						$diagnostico_pre_qx='NULL';
						$tipo_pre_qx='';
					}
				}
			}
		}else{
			$diagnostico_pre_qx='NULL';
			$tipo_pre_qx='';
		}*/
		if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['POST_QUIRURGICO']){
			foreach($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['POST_QUIRURGICO'] as $codigo=>$vect){
				foreach($vect as $tipo=>$diagnostico){
					if($codigo){
						$diagnostico_post_qx="'".$codigo."'";
						$tipo_post_qx=$tipo;
					}else{
						$diagnostico_post_qx='NULL';
						$tipo_post_qx='';
					}
				}
			}
		}else{
			$diagnostico_post_qx='NULL';
			$tipo_post_qx='';
		}
		if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['COMPLICACION']){
			foreach($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['COMPLICACION'] as $codigo=>$vect){
				foreach($vect as $tipo=>$diagnostico){
					if($codigo){
						$diagnostico_complicacion="'".$codigo."'";
						$tipo_complicacion=$tipo;
					}else{
						$diagnostico_complicacion='NULL';
						$tipo_complicacion='';
					}
				}
			}
		}else{
			$diagnostico_complicacion='NULL';
			$tipo_complicacion='';
		}		
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['anestesiologo']!=-1 && !empty($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['anestesiologo'])){
			(list($AnestesiologoId,$tipoIdAnestesiologo)=explode('/',$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['anestesiologo']));
			$tipoIdAnestesiologo="'".$tipoIdAnestesiologo."'";
			$AnestesiologoId="'".$AnestesiologoId."'";
		}else{
			$tipoIdAnestesiologo='NULL';
			$AnestesiologoId='NULL';
		}
		if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['ayudante']!=-1 && !empty($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['ayudante'])){
			(list($AyudanteId,$tipoIdAyudante)=explode('/',$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['ayudante']));
			$tipoIdAyudante="'".$tipoIdAyudante."'";
			$AyudanteId="'".$AyudanteId."'";
		}else{
			$tipoIdAyudante='NULL';
			$AyudanteId='NULL';
		}
		if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['instrumentador']!=-1 && !empty($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['instrumentador'])){
			(list($Instrumentador,$tipoIdInstrumentador)=explode('/',$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['instrumentador']));
			$tipoIdInstrumentador="'".$tipoIdInstrumentador."'";
			$Instrumentador="'".$Instrumentador."'";
		}else{
			$tipoIdInstrumentador='NULL';
			$Instrumentador='NULL';
		}
		if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['circulante']!=-1 && !empty($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['circulante'])){
			(list($Circulante,$tipoIdCirculante)=explode('/',$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['circulante']));
			$tipoIdCirculante="'".$tipoIdCirculante."'";
			$Circulante="'".$Circulante."'";
		}else{
			$tipoIdCirculante='NULL';
			$Circulante='NULL';
		}
		if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['SelMaterialPat']==1 || !empty($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['MaterialPat'])){
			$matPat=1;
		}else{
			$matPat=0;			
		}
		if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['SelCultivo']==1 || !empty($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['Cultivo'])){
			$cultivo=1;
		}else{
			$cultivo=0;
		}
			
		if(empty($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID'])){							
			$query="SELECT nextval('hc_notas_operatorias_cirugias_hc_nota_operatoria_cirugia_id_seq')";
			$result = $dbconn->Execute($query);
			$notaId=$result->fields[0];
			$query="INSERT INTO hc_notas_operatorias_cirugias(hc_nota_operatoria_cirugia_id,
			quirofano_id,hora_inicio,hora_fin,usuario_id,fecha_registro,via_acceso,
			tipo_cirugia,ambito_cirugia,finalidad_procedimiento_id,evolucion_id,
			justificacion_norealizados,programacion_id,
			diagnostico_post_qx,tipo_diagnostico_post_qx,
			diagnostico_id_complicacion,tipo_diagnostico_complicacion,
			tipo_id_anestesiologo,anestesiologo_id,tipo_id_instrumentista,instrumentista_id,tipo_id_circulante,circulante_id,
			tipo_id_ayudante,ayudante_id,
			envio_patologico,descripcion_envio_patologico,envio_cultivo,descripcion_envio_cultivo)VALUES('$notaId',		
			$quirofano,'$fechaInicio','$fechaFin','".UserGetUID()."','".date("Y-m-d H:i:s")."',
			$viaAcceso,$tipoCirugia,$ambitoCirugia,$finalidadCirugia,'".$this->evolucion."','".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['justificacion']."',
			'".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROGRAMACION']."',
			$diagnostico_post_qx,'$tipo_post_qx',$diagnostico_complicacion,'$tipo_complicacion',
			$tipoIdAnestesiologo,$AnestesiologoId,$tipoIdInstrumentador,$Instrumentador,$tipoIdCirculante,$Circulante,
			$tipoIdAyudante,$AyudanteId,
			'$matPat','".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['MaterialPat']."','$cultivo','".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['Cultivo']."')";
			$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']=$notaId;								
			//$diagnostico_pre_qx,'$tipo_pre_qx',
			//diagnostico_pre_qx,tipo_diagnostico_pre_qx,
		}else{		
			$query="UPDATE hc_notas_operatorias_cirugias 
			SET quirofano_id=$quirofano,
					hora_inicio='$fechaInicio',
					hora_fin='$fechaFin',
					via_acceso=$viaAcceso,
					tipo_cirugia=$tipoCirugia,
					ambito_cirugia=$ambitoCirugia,
					finalidad_procedimiento_id=$finalidadCirugia,
					justificacion_norealizados='".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['justificacion']."',					
					diagnostico_post_qx=$diagnostico_post_qx,
					tipo_diagnostico_post_qx='$tipo_post_qx',
					diagnostico_id_complicacion=$diagnostico_complicacion,
					tipo_diagnostico_complicacion='$tipo_complicacion',
					tipo_id_anestesiologo=$tipoIdAnestesiologo,
					anestesiologo_id=$AnestesiologoId,
					tipo_id_instrumentista=$tipoIdInstrumentador,
					instrumentista_id=$Instrumentador,
					tipo_id_circulante=$tipoIdCirculante,
					circulante_id=$Circulante,
					tipo_id_ayudante=$tipoIdAyudante,
					ayudante_id=$AyudanteId,
					envio_patologico='$matPat',
					descripcion_envio_patologico='".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['MaterialPat']."',
					envio_cultivo='$cultivo',
					descripcion_envio_cultivo='".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['Cultivo']."'
			WHERE hc_nota_operatoria_cirugia_id='".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']."'";							
			$notaId=$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID'];		
			//diagnostico_pre_qx=$diagnostico_pre_qx,
			//tipo_diagnostico_pre_qx='$tipo_pre_qx',
		}
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}else{	
			if(!empty($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID'])){
				$query="DELETE FROM hc_notas_operatorias_procedimientos_diags WHERE hc_nota_operatoria_cirugia_id='".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']."'";
				
				$resulta = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}else{
					$query="DELETE FROM hc_notas_operatorias_procedimientos WHERE hc_nota_operatoria_cirugia_id='".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']."'";
					
					$resulta = $dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}else{
            $query="DELETE FROM hc_notas_operatorias_procedimientos_opciones WHERE hc_nota_operatoria_cirugia_id='".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']."'";          
            $resulta = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0){
              $this->error = "Error al Cargar el Modulo";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              $dbconn->RollbackTrans();
              return false;
            }
          }	
				}				
			}
			foreach($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['1'] as $codigo=>$procedimiento){
				$query="INSERT INTO hc_notas_operatorias_procedimientos(hc_nota_operatoria_cirugia_id,procedimiento_qx,realizado,observaciones)
				VALUES('$notaId','$codigo','1','".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['OBSERVACIONES'][$codigo]."');";
				
				$resulta = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}else{
          //Para angiografia
					if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS_OPCIONES'][$codigo]){          
            foreach($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS_OPCIONES'][$codigo] as $pro=>$des){
              $query="INSERT INTO hc_notas_operatorias_procedimientos_opciones(hc_nota_operatoria_cirugia_id,procedimiento_qx,
              procedimiento_opcion)VALUES('$notaId','$codigo','$pro')";              
              $resulta = $dbconn->Execute($query);
              if($dbconn->ErrorNo() != 0){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
              }
            }
          }
          //fin angio
          
					foreach($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['DIAGNOSTICOS'][$codigo] as $codigoDiagnostico=>$vectorDiag){
						foreach($vectorDiag as $tipoDiagnostico=>$nombreDiag){
							if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['DIAGNOSTICO_PRINCIPAL'][$codigo]==$codigoDiagnostico){
								$principal='1';
							}else{
								$principal='0';
							}
							$query="INSERT INTO hc_notas_operatorias_procedimientos_diags(hc_nota_operatoria_cirugia_id,procedimiento_qx,
							diagnostico_id,sw_principal,tipo_diagnostico)VALUES('$notaId','$codigo','$codigoDiagnostico','$principal','$tipoDiagnostico')";
							
							$resulta = $dbconn->Execute($query);
							if($dbconn->ErrorNo() != 0){
								$this->error = "Error al Cargar el Modulo";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
							}
						}
					}		
				}
			}
			foreach($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['3'] as $codigo=>$procedimiento){
				$query="INSERT INTO hc_notas_operatorias_procedimientos(hc_nota_operatoria_cirugia_id,procedimiento_qx,realizado,observaciones)
				VALUES('$notaId','$codigo','1','".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['OBSERVACIONES'][$codigo]."');";
				
				$resulta = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}else{
        
          //Para angiografia
          if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS_OPCIONES'][$codigo]){          
            foreach($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS_OPCIONES'][$codigo] as $pro=>$des){
              $query="INSERT INTO hc_notas_operatorias_procedimientos_opciones(hc_nota_operatoria_cirugia_id,procedimiento_qx,
              procedimiento_opcion)VALUES('$notaId','$codigo','$pro')";              
              $resulta = $dbconn->Execute($query);
              if($dbconn->ErrorNo() != 0){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
              }
            }
          }
          //fin angio
          					
					foreach($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['DIAGNOSTICOS'][$codigo] as $codigoDiagnostico=>$vectorDiag){
						foreach($vectorDiag as $tipoDiagnostico=>$nombreDiag){
							if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['DIAGNOSTICO_PRINCIPAL'][$codigo]==$codigoDiagnostico){
								$principal='1';
							}else{
								$principal='0';
							}
							$query="INSERT INTO hc_notas_operatorias_procedimientos_diags(hc_nota_operatoria_cirugia_id,procedimiento_qx,
							diagnostico_id,sw_principal,tipo_diagnostico)VALUES('$notaId','$codigo','$codigoDiagnostico','$principal','$tipoDiagnostico')";
							
							$resulta = $dbconn->Execute($query);
							if($dbconn->ErrorNo() != 0){
								$this->error = "Error al Cargar el Modulo";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
							}
						}
					}	
				}
			}
			foreach($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['2'] as $codigo=>$procedimiento){
				$query="INSERT INTO hc_notas_operatorias_procedimientos(hc_nota_operatoria_cirugia_id,procedimiento_qx,realizado,observaciones)
				VALUES('$notaId','$codigo','0','".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['OBSERVACIONES'][$codigo]."');";
				
				$resulta = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
			}			
			$dbconn->CommitTrans();
			 $this->RegistrarSubmodulo($this->GetVersion());            
      return true;
		}		
		return false;
	}
	
	
	//cor - clzc-jea - ads
	function Busqueda_Avanzada_Diagnosticos($codigo,$diagnostico){
		
		$pfj=$this->frmPrefijo;
		$FechaInicio = $this->datosPaciente[fecha_nacimiento];
		$FechaFin = date("Y-m-d");
		$edad_paciente = CalcularEdad($FechaInicio,$FechaFin);
		list($dbconn) = GetDBconn();
    $codigo = STRTOUPPER ($codigo);
		$diagnostico  =STRTOUPPER($diagnostico);

		$busqueda1 = '';
		$busqueda2 = '';

		if ($codigo != ''){
			$busqueda1 =" WHERE diagnostico_id LIKE '$codigo%'";
		}

		if (($diagnostico != '') AND ($codigo != '')){
    	if (eregi('%',$diagnostico)){
				$busqueda2 ="AND diagnostico_nombre LIKE '$diagnostico'";      
      }else{
      	$busqueda2 ="AND diagnostico_nombre LIKE '%$diagnostico%'";
      }
		}

		if (($diagnostico != '') AND ($codigo == '')){
			if(eregi('%',$diagnostico)){
					$busqueda2 ="WHERE diagnostico_nombre LIKE '$diagnostico'";
			}else{
					$busqueda2 ="WHERE diagnostico_nombre LIKE '%$diagnostico%'";
			}
		}
		//filtro por clasificacion de diagnosticos
		$filtro='';
		if(empty($busqueda1) AND empty($busqueda2)){
		
			$filtro = "WHERE (sexo_id='".$this->datosPaciente['sexo_id']."' OR sexo_id is null)
					 AND   (edad_max>=".$edad_paciente[edad_en_dias]." OR edad_max is null)
					 AND   (edad_min<=".$edad_paciente[edad_en_dias]." OR edad_min is null)";
		}else{
			$filtro = "AND (sexo_id='".$this->datosPaciente['sexo_id']."' OR sexo_id is null)
					 AND (edad_max>=".$edad_paciente[edad_en_dias]." OR edad_max is null)
					 AND (edad_min<=".$edad_paciente[edad_en_dias]." OR edad_min is null)";
		}

		$filtro1='';
		if(!empty($this->capitulo)){
			$filtro1 = " AND (B.capitulo='".$this->capitulo."' OR B.capitulo is null)";
		}
		if(!empty($this->grupo)){
			$filtro1 .= " AND (B.grupo='".$this->grupo."' OR B.grupo is null)";
		}
		if(!empty($this->categoria)){
			$filtro1 .= " AND (B.categoria='".$this->categoria."' OR B.categoria is null)";
		}

		$query = "SELECT diagnostico_id, diagnostico_nombre
                    FROM diagnosticos
                    $busqueda1 $busqueda2
                    $filtro $filtro1";									
		
		list($dbconn) = GetDBconn();
		
		if(empty($_REQUEST['conteo'])){
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$this->conteo=$result->RecordCount();
		}else{
			$this->conteo=$_REQUEST['conteo'];
		}
		$query.=" ORDER BY diagnostico_id";
		$query.=" LIMIT " . $this->limit . " OFFSET ".$this->offset."";
		
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			while(!$result->EOF){
				$vars[]=$result->GetRowAssoc($toUpper=false);
				$result->MoveNext();
			}
		}    
   	if($this->conteo==='0'){
		  $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
			return false;
		}
		$result->Close();			
		return $vars;	
	}

	function DatosCumplimiento(){
    list($dbconn) = GetDBconn();
		$query="SELECT a.tipo_id_cirujano,a.cirujano_id,b.nombre as nombrecirujano,a.diagnostico_id,c.diagnostico_nombre,a.programacion_id,
		d.quirofano_id,e.descripcion as quirofano,d.hora_inicio,d.hora_fin,f.via_acceso,g.descripcion as via,f.tipo_cirugia,h.descripcion as tipocirugia,
    f.ambito_cirugia,i.descripcion as ambitocirugia
		FROM qx_cumplimientos a
		LEFT JOIN profesionales b ON(a.tipo_id_cirujano=b.tipo_id_tercero AND a.cirujano_id=b.tercero_id)
		LEFT JOIN diagnosticos c ON(a.diagnostico_id=c.diagnostico_id)
		LEFT JOIN qx_cumplimientos_datos f ON (a.qx_cumplimiento_id=f.qx_cumplimiento_id)
    LEFT JOIN qx_vias_acceso g ON (f.via_acceso=g.via_acceso)
    LEFT JOIN qx_tipos_cirugia h ON (f.tipo_cirugia=h.tipo_cirugia_id)
		LEFT JOIN qx_ambitos_cirugias i ON (f.ambito_cirugia=i.ambito_cirugia_id),
		qx_cumplimientos_quirofano d
    LEFT JOIN qx_quirofanos e ON (d.quirofano_id=e.quirofano)
		WHERE a.qx_cumplimiento_id='".$this->QXcumplimiento."' AND a.qx_cumplimiento_id=d.qx_cumplimiento_id";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo en la tabla qx_cumplimientos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if(!$result->EOF){
				$datosCumplimiento=$result->GetRowAssoc($toUpper=false);
			}
		}
		return $datosCumplimiento;
	}

	

	function DatosProcedimientosCumplimiento(){
    list($dbconn) = GetDBconn();
		$query="SELECT DISTINCT a.procedimiento_qx,c.descripcion,
		a.via_procedimiento_bilateral,d.descripcion as via
		FROM qx_cumplimiento_procedimientos a
		LEFT JOIN qx_vias_acceso d ON(a.via_procedimiento_bilateral=d.via_acceso),
		profesionales_usuarios b,cups c
		WHERE a.qx_cumplimiento_id='".$this->QXcumplimiento."' AND b.usuario_id='".UserGetUID()."' AND
		b.tipo_tercero_id=a.tipo_id_cirujano AND b.tercero_id=a.cirujano_id AND a.procedimiento_qx=c.cargo";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo en la tabla qx_cumplimientos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if(!$result->EOF){
			  while (!$result->EOF) {
				  $datosProcedimientos[]=$result->GetRowAssoc($toUpper=false);
				  $result->MoveNext();
			  }
			}
		}
		return $datosProcedimientos;
	}

	function DatosAyundantesCumplimiento(){

    list($dbconn) = GetDBconn();
		$query="SELECT DISTINCT a.tipo_id_ayudante,a.ayudante_id,c.nombre
		FROM qx_cumplimiento_procedimientos a
		LEFT JOIN profesionales c ON(c.tipo_id_tercero=a.tipo_id_ayudante AND c.tercero_id=a.ayudante_id),
		profesionales_usuarios b
		WHERE a.qx_cumplimiento_id='".$this->QXcumplimiento."' AND b.usuario_id='".UserGetUID()."' AND
		b.tipo_tercero_id=a.tipo_id_cirujano AND b.tercero_id=a.cirujano_id";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo en la tabla qx_cumplimientos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if(!$result->EOF){
			  while (!$result->EOF) {
				  $datosAyudantes[]=$result->GetRowAssoc($toUpper=false);
				  $result->MoveNext();
			  }
			}
		}
		return $datosAyudantes;
	}

/**
* Funcion que retorna un arreglo de los quirofanos con los que cuenta la ips en el departamento en el que esta logueado el usuario
* @return array
*/
	function TotalQuirofanos(){
		list($dbconn) = GetDBconn();
		$query = "SELECT quirofano,descripcion FROM qx_quirofanos";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
			  while (!$result->EOF) {
					$vars[$result->fields[0]]=$result->fields[1];
					$result->MoveNext();
				}
			}
		}
		$result->Close();
 		return $vars;
	}

/**
* Funcion que retorna un arreglo de las vias de acceso de la base de datos para realizar una cirugia
* @return array
*/
	function ViaAccesosCirugia(){

		list($dbconn) = GetDBconn();
		$query = "SELECT via_acceso,descripcion FROM qx_vias_acceso";
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'diagnosticos' esta vacia ";
				return false;
			}
			while (!$result->EOF) {
				$vars[$result->fields[0]]=$result->fields[1];
				$result->MoveNext();
			}
		}
		$result->Close();
 		return $vars;
	}

/**
* Funcion consulta de la base de datos los tipos de cirugia existentes
* @return array
*/
	function TiposdeCirugia(){
    list($dbconn) = GetDBconn();
		$query = "SELECT tipo_cirugia_id,descripcion FROM qx_tipos_cirugia";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'vias_acceso_cx' esta vacia ";
				return false;
			}
			while (!$result->EOF) {
				$vars[$result->fields[0]]=$result->fields[1];
				$result->MoveNext();
			}
		}
		$result->Close();
 		return $vars;
	}

/**
* Funcion que consulta de la base de datos los tipos de ambito que puede tener una cirugia
* @return array
*/
	function TiposdeAmbitosdeCirugia(){
    list($dbconn) = GetDBconn();
		$query = "SELECT ambito_cirugia_id,descripcion FROM qx_ambitos_cirugias";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'qx_ambitos_cirugias' esta vacia ";
				return false;
			}
			while (!$result->EOF) {
				$vars[$result->fields[0]]=$result->fields[1];
				$result->MoveNext();
			}
		}
		$result->Close();
 		return $vars;
	}

/**
* Funcion que consulta de la base de datos los tipos de ambito que puede tener una cirugia
* @return array
*/
	function TiposfinalidadesCirugia(){
    list($dbconn) = GetDBconn();
		$query = "SELECT finalidad_procedimiento_id,descripcion FROM qx_finalidades_procedimientos";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'qx_ambitos_cirugias' esta vacia ";
				return false;
			}
			while (!$result->EOF) {
				$vars[$result->fields[0]]=$result->fields[1];
				$result->MoveNext();
			}
		}
		$result->Close();
 		return $vars;
	}

  function BusquedaDiagnostico(){
	  $this->BuscadorDiagnostico($_REQUEST['codigoDes'.$this->frmPrefijo],$_REQUEST['descripcionDes'.$this->frmPrefijo]);
		return true;
	}

	function RegistrosDiagnosticos($codigo,$descripcion){

    $pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		if($codigo){
      $concat=" WHERE diagnostico_id LIKE '%$codigo%'";
			$s=1;
		}
		if($descripcion){
      if($s==1){
        $concat.=" AND diagnostico_nombre LIKE '%".strtoupper($descripcion)."%'";
			}else{
        $concat=" WHERE diagnostico_nombre LIKE '%".strtoupper($descripcion)."%'";
			}
		}
    if(empty($_REQUEST['conteo'.$pfj])){
			$query = "SELECT count(*) FROM diagnosticos $concat";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			list($this->conteo)=$resulta->fetchRow();
		}else{
			$this->conteo=$_REQUEST['conteo'.$pfj];
		}
		if(!$_REQUEST['Of'.$pfj]){
			$Of='0';
		}else{
			$Of=$_REQUEST['Of'.$pfj];
      if($Of > $this->conteo){
				$Of=0;
				$_REQUEST['Of'.$pfj]=0;
				$_REQUEST['paso1'.$pfj]=1;
			}
		}
	  $query = "SELECT diagnostico_id,diagnostico_nombre FROM diagnosticos $concat
		LIMIT " . $this->limit . " OFFSET $Of";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			while(!$resulta->EOF){
				$var[]=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
			}
		}
   	if($this->conteo==='0'){
		  $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
			return false;
		}
		$resulta->Close();
		return $var;
	}

	function GetImprimir($y){
		$imprimir=$this->frmImprimir($y);
		if($imprimir[0]==false)
		{
			return true;
		}
		return $imprimir;
	}

	/*function BuscarProcedimientosCumplidos(){
    list($dbconn) = GetDBconn();
		if(!$_SESSION['NotaOperatoria']['NotaId']){$notaId='NULL';}else{$notaId="'".$_SESSION['NotaOperatoria']['NotaId']."'";}
		$query = "SELECT 1 as origen,(SELECT 1 FROM profesionales_usuarios WHERE usuario_id='".UserGetUID()."' AND a.tipo_id_cirujano=tipo_tercero_id AND a.cirujano_id=tercero_id) as propio,
		a.procedimiento_qx,d.descripcion as cargo,a.tipo_id_cirujano,a.cirujano_id,b.nombre as cirujano,a.tipo_id_ayudante,a.ayudante_id,c.nombre as ayudante,
    NULL as tecnica_quirurgica,NULL as hallazgos_quirurgicos
		FROM qx_cumplimiento_procedimientos a
		LEFT JOIN profesionales b ON(a.tipo_id_cirujano=b.tipo_id_tercero AND a.cirujano_id=b.tercero_id)
		LEFT JOIN profesionales c ON(a.tipo_id_ayudante=c.tipo_id_tercero AND a.ayudante_id=c.tercero_id)
		LEFT JOIN cups d ON(a.procedimiento_qx=d.cargo)
		WHERE a.qx_cumplimiento_id='".$this->QXcumplimiento."'
		UNION
		SELECT 2 as origen,(SELECT 1 FROM profesionales_usuarios WHERE usuario_id='".UserGetUID()."' AND b.tipo_id_cirujano=tipo_tercero_id AND b.cirujano_id=tercero_id) as propio,
		b.procedimiento_qx,d.descripcion as cargo,b.tipo_id_cirujano,b.cirujano_id,e.nombre as cirujano,b.tipo_id_ayudante,b.ayudante_id,f.nombre as ayudante,
    b.tecnica_quirurgica,b.hallazgos_quirurgicos
		FROM hc_notas_operatorias_cirugias a,hc_notas_operatorias_procedimientos b
		LEFT JOIN profesionales e ON(b.tipo_id_cirujano=e.tipo_id_tercero AND b.cirujano_id=e.tercero_id)
		LEFT JOIN profesionales f ON(b.tipo_id_ayudante=f.tipo_id_tercero AND b.ayudante_id=f.tercero_id)
		LEFT JOIN cups d ON(b.procedimiento_qx=d.cargo),
		profesionales_usuarios c
		WHERE a.qx_cumplimiento_id='".$this->QXcumplimiento."' AND a.hc_nota_operatoria_cirugia_id=$notaId AND
		a.hc_nota_operatoria_cirugia_id=b.hc_nota_operatoria_cirugia_id";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			while(!$resulta->EOF){
				$vars[$resulta->fields[0]][$resulta->fields[1]][$resulta->fields[2]]=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
			}
		}
		$resulta->Close();
		//print_R($vars);
 		return $vars;
	}*/


/**
* Funcion que consulta de la base de tado los tipos de cargos agrupados
* @return array
*/
	function tiposdeProcedimientos(){
    list($dbconn) = GetDBconn();
		$query="SELECT a.tipo_cargo,a.grupo_tipo_cargo,a.descripcion
		FROM tipos_cargos a,qx_grupos_tipo_cargo b
		WHERE a.grupo_tipo_cargo=b.grupo_tipo_cargo";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
        while(!$result->EOF){
          $vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		$result->Close();
 		return $vars;
	}

	function RegistrosCargosCups($tipoProcedimiento,$cargo,$descripcion){

		$query="SELECT a.cargo,a.descripcion,c.descripcion as tipo 
		FROM cups a,qx_grupos_tipo_cargo b,tipos_cargos c
		WHERE a.grupo_tipo_cargo=b.grupo_tipo_cargo AND b.grupo_tipo_cargo=c.grupo_tipo_cargo AND
		a.tipo_cargo=c.tipo_cargo";    
		if(!empty($tipoProcedimiento) && $tipoProcedimiento!=-1){
			(list($tipo,$grupo)=explode('/',$tipoProcedimiento));
			$query.=" AND a.grupo_tipo_cargo='$grupo' AND a.tipo_cargo='$tipo'";			
		}
		if(!empty($cargo)){        
    	$query.=" AND a.cargo='$cargo'";				
		}
    if(!empty($descripcion)){
			$descripcion=strtoupper($descripcion);
      $query.=" AND a.descripcion LIKE '%$descripcion%'";			
		}		
		$pfj=$this->frmPrefijo;		
		list($dbconn) = GetDBconn();
		
		if(empty($_REQUEST['conteo'])){
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$this->conteo=$result->RecordCount();
		}else{
			$this->conteo=$_REQUEST['conteo'];
		}
		$query.=" ORDER BY a.descripcion";
		$query.=" LIMIT " . $this->limit . " OFFSET ".$this->offset."";
		
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			while(!$result->EOF){
				$vars[]=$result->GetRowAssoc($toUpper=false);
				$result->MoveNext();
			}
		}    
   	if($this->conteo==='0'){
		  $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
			return false;
		}
		$result->Close();
		return $vars;	
	}

	function GuardarDatosNotasOperatorias(){

		if(!$_REQUEST['fechainicio'.$this->frmPrefijo] || $_REQUEST['hora'.$this->frmPrefijo]==-1 || $_REQUEST['minutos'.$this->frmPrefijo]==-1 || $_REQUEST['horadur'.$this->frmPrefijo]==-1 || $_REQUEST['minutosdur'.$this->frmPrefijo]==-1){
		  $this->frmError["MensajeError"]="La Fecha de Inicio y la Duracion son Datos Obligatorios";
		  $this->frmForma();
			return true;
		}
		if($_REQUEST['fechainicio'.$this->frmPrefijo]){
		  $fechaInicio=ereg_replace("-","/",$_REQUEST['fechainicio'.$this->frmPrefijo]);
      (list($dia,$mes,$ano)=explode('/',$fechaInicio));
      $fechaInicio=$ano.'-'.$mes.'-'.$dia.' '.$_REQUEST['hora'.$this->frmPrefijo].':'.$_REQUEST['minutos'.$this->frmPrefijo].':'.'00';
		}
		if($_REQUEST['quirofano'.$this->frmPrefijo]==-1){$quirofano='NULL';	}
		else{$quirofano="'".$_REQUEST['quirofano'.$this->frmPrefijo]."'";	}
		if($_REQUEST['viaAcceso'.$this->frmPrefijo]==-1){$viaAcceso='NULL';	}
		else{$viaAcceso="'".$_REQUEST['viaAcceso'.$this->frmPrefijo]."'";}
		if($_REQUEST['tipoCirugia'.$this->frmPrefijo]==-1){$tipoCirugia='NULL';}
		else{$tipoCirugia="'".$_REQUEST['tipoCirugia'.$this->frmPrefijo]."'";}
		if($_REQUEST['ambitoCirugia'.$this->frmPrefijo]==-1){$ambitoCirugia='NULL';}
		else{$ambitoCirugia="'".$_REQUEST['ambitoCirugia'.$this->frmPrefijo]."'";}
		if($_REQUEST['finalidadCirugia'.$this->frmPrefijo]==-1){$finalidadCirugia='NULL';}
		else{$finalidadCirugia="'".$_REQUEST['finalidadCirugia'.$this->frmPrefijo]."'";}
		$fechaFin=date("Y-m-d H:i:s",mktime(($_REQUEST['hora'.$this->frmPrefijo]+$_REQUEST['horadur'.$this->frmPrefijo]),($_REQUEST['minutos'.$this->frmPrefijo]+$_REQUEST['minutosdur'.$this->frmPrefijo]),0,$mes,$dia,$ano));
		list($dbconn) = GetDBconn();
		if(!$_SESSION['NotaOperatoria']['NotaId']){
			$query="SELECT nextval('hc_notas_operatorias_cirugias_hc_nota_operatoria_cirugia_id_seq')";
			$result = $dbconn->Execute($query);
			$notaId=$result->fields[0];
			$query="INSERT INTO hc_notas_operatorias_cirugias(hc_nota_operatoria_cirugia_id,qx_cumplimiento_id,
			quirofano_id,hora_inicio,hora_fin,usuario_id,fecha_registro,via_acceso,
			tipo_cirugia,ambito_cirugia,finalidad_procedimiento_id,evolucion_id)VALUES('$notaId','".$this->QXcumplimiento."',
			$quirofano,'$fechaInicio','$fechaFin','".UserGetUID()."','".date("Y-m-d H:i:s")."',
			$viaAcceso,$tipoCirugia,$ambitoCirugia,$finalidadCirugia,'".$this->evolucion."')";
			$resulta = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}else{
        $_SESSION['NotaOperatoria']['NotaId']=$notaId;
			}
		}else{
		  $query="UPDATE hc_notas_operatorias_cirugias
			SET quirofano_id=$quirofano,hora_inicio='".$fechaInicio."',hora_fin='".$fechaFin."',via_acceso=$viaAcceso,
			tipo_cirugia=$tipoCirugia,ambito_cirugia=$ambitoCirugia,finalidad_procedimiento_id=$finalidadCirugia
			WHERE hc_nota_operatoria_cirugia_id='".$_SESSION['NotaOperatoria']['NotaId']."'";
			$resulta = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
		}
		$this->frmForma();
		return true;
	}

	function GuardarDiagnosticosNotasOperatorias(){
    if(!$_SESSION['NotaOperatoria']['NotaId']){
		  $this->frmError["MensajeError"]="Inserte Primero los Datos Principales de la Nota";
			$this->frmForma();
			return true;
		}
		list($dbconn) = GetDBconn();
		if(!$_REQUEST['codigo'.$this->frmPrefijo]){$codigo='NULL';}
		else{$codigo="'".$_REQUEST['codigo'.$this->frmPrefijo]."'";}
		if(!$_REQUEST['codigo1'.$this->frmPrefijo]){$codigo1='NULL';}
		else{$codigo1="'".$_REQUEST['codigo1'.$this->frmPrefijo]."'";}
		$query="SELECT tipo_tercero_id,tercero_id FROM profesionales_usuarios WHERE usuario_id='".UserGetUID()."'";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$var=$resulta->GetRowAssoc($ToUpper = false);
			$query="SELECT * FROM hc_notas_operatorias_cirujanos WHERE tipo_id_cirujano='".$var['tipo_tercero_id']."' AND cirujano_id='".$var['tercero_id']."'";
			$resulta = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}else{
				$datos=$resulta->RecordCount();
				if($datos){
					$query="UPDATE hc_notas_operatorias_cirujanos
					SET diagnostico_id=$codigo,complicacion_id=$codigo1
					WHERE hc_nota_operatoria_cirugia_id='".$_SESSION['NotaOperatoria']['NotaId']."' AND
					tipo_id_cirujano='".$var['tipo_tercero_id']."' AND cirujano_id='".$var['tercero_id']."'";
				}else{
					$query="INSERT INTO hc_notas_operatorias_cirujanos(hc_nota_operatoria_cirugia_id,tipo_id_cirujano,cirujano_id,diagnostico_id,complicacion_id)
					VALUES('".$_SESSION['NotaOperatoria']['NotaId']."','".$var['tipo_tercero_id']."','".$var['tercero_id']."',$codigo,$codigo1)";
				}
				$resulta = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
			}
		}
		$this->frmForma();
	}


	function DatosNotasOperatorias(){
    list($dbconn) = GetDBconn();
    $query="SELECT a.hora_inicio,a.hora_fin,a.quirofano_id,a.via_acceso,a.tipo_cirugia,a.ambito_cirugia,a.finalidad_procedimiento_id
		FROM hc_notas_operatorias_cirugias a
		WHERE a.hc_nota_operatoria_cirugia_id='".$_SESSION['NotaOperatoria']['NotaId']."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$vars=$result->GetRowAssoc($ToUpper = false);
		}
		return $vars;
	}

	function DiagnosticosNotasOperatorias(){
    list($dbconn) = GetDBconn();
    $query="SELECT a.diagnostico_id,b.diagnostico_nombre,a.complicacion_id,c.diagnostico_nombre as complicacion
		FROM hc_notas_operatorias_cirujanos a
		LEFT JOIN diagnosticos b ON(a.diagnostico_id=b.diagnostico_id)
		LEFT JOIN diagnosticos c ON(a.complicacion_id=c.diagnostico_id),
    profesionales_usuarios d
		WHERE a.hc_nota_operatoria_cirugia_id='".$_SESSION['NotaOperatoria']['NotaId']."' AND
		d.usuario_id='".UserGetUID()."' AND a.tipo_id_cirujano=d.tipo_tercero_id AND a.cirujano_id=d.tercero_id";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$vars=$result->GetRowAssoc($ToUpper = false);
		}
		return $vars;
	}

	function GuardarProcedimientoNota(){
    list($dbconn) = GetDBconn();
    $query="SELECT a.tipo_id_ayudante,a.ayudante_id,a.procedimiento_qx,b.descripcion
		FROM qx_cumplimiento_procedimientos a,cups b
		WHERE a.qx_cumplimiento_id='".$this->QXcumplimiento."' AND a.procedimiento_qx='".$_REQUEST['procedimiento']."' AND a.procedimiento_qx=b.cargo";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$vars=$result->GetRowAssoc($ToUpper = false);
		}
		$_REQUEST['textnuevoProcedimiento'.$this->frmPrefijo]=$vars['descripcion'];
		$_REQUEST['nuevoProcedimiento'.$this->frmPrefijo]=$vars['procedimiento_qx'];
		$_REQUEST['ayudante'.$this->frmPrefijo]=$vars['ayudante_id'].'/'.$vars['tipo_id_ayudante'];
		$this->frmForma();
		return true;
	}

	function InsercionProcedimientosNotaBD(){
    list($dbconn) = GetDBconn();
		if(!$_SESSION['NotaOperatoria']['NotaId']){
		  $this->frmError["MensajeError"]="Inserte Primero los Datos Principales de la Nota";
			$this->frmForma();
			return true;
		}
		$query="SELECT tipo_tercero_id,tercero_id FROM profesionales_usuarios WHERE usuario_id='".UserGetUID()."'";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $var=$resulta->GetRowAssoc($ToUpper = false);
      $query="SELECT * FROM hc_notas_operatorias_cirujanos WHERE tipo_id_cirujano='".$var['tipo_tercero_id']."' AND cirujano_id='".$var['tercero_id']."' AND hc_nota_operatoria_cirugia_id='".$_SESSION['NotaOperatoria']['NotaId']."'";
			$resulta = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}else{
				$datos=$resulta->RecordCount();
				if(!$_REQUEST['codigo'.$this->frmPrefijo]){$codigo='NULL';}
				else{$codigo="'".$_REQUEST['codigo'.$this->frmPrefijo]."'";}
				if(!$_REQUEST['codigo1'.$this->frmPrefijo]){$codigo1='NULL';}
				else{$codigo1="'".$_REQUEST['codigo1'.$this->frmPrefijo]."'";}
				if($datos){
          $query="UPDATE hc_notas_operatorias_cirujanos
					SET diagnostico_id=$codigo,complicacion_id=$codigo1
					WHERE hc_nota_operatoria_cirugia_id='".$_SESSION['NotaOperatoria']['NotaId']."' AND
					tipo_id_cirujano='".$var['tipo_tercero_id']."' AND cirujano_id='".$var['tercero_id']."'";
				}else{
          $query="INSERT INTO hc_notas_operatorias_cirujanos(hc_nota_operatoria_cirugia_id,tipo_id_cirujano,cirujano_id,diagnostico_id,complicacion_id)
					VALUES('".$_SESSION['NotaOperatoria']['NotaId']."','".$var['tipo_tercero_id']."','".$var['tercero_id']."',$codigo,$codigo1)";
				}
				$resulta = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
			}
		}
		if($_REQUEST['nuevoProcedimiento'.$this->frmPrefijo] && $_REQUEST['textnuevoProcedimiento'.$this->frmPrefijo]){
			(list($ayudante,$tipoAyudante)=explode('/',$_REQUEST['ayudante'.$this->frmPrefijo]));
			$query="INSERT INTO hc_notas_operatorias_procedimientos(hc_nota_operatoria_cirugia_id,tipo_id_cirujano,cirujano_id,
			procedimiento_qx,tipo_id_ayudante,ayudante_id,tecnica_quirurgica,hallazgos_quirurgicos)
			VALUES('".$_SESSION['NotaOperatoria']['NotaId']."','".$var['tipo_tercero_id']."','".$var['tercero_id']."',
			'".$_REQUEST['nuevoProcedimiento'.$this->frmPrefijo]."','$tipoAyudante','$ayudante','".$_REQUEST['descripcionQuirugica'.$this->frmPrefijo]."',
			'".$_REQUEST['hallazgos'.$this->frmPrefijo]."')";
			$resulta = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$_REQUEST['ayudante'.$this->frmPrefijo]='';
			$_REQUEST['descripcionQuirugica'.$this->frmPrefijo]='';
			$_REQUEST['nuevoProcedimiento'.$this->frmPrefijo]='';
			$_REQUEST['hallazgos'.$this->frmPrefijo]='';
			$_REQUEST['textnuevoProcedimiento'.$this->frmPrefijo]='';
		}
		$this->frmForma();
		return true;
	}

	function EliminaProcedimientoNota(){
	  list($dbconn) = GetDBconn();
    $query="DELETE
		FROM hc_notas_operatorias_procedimientos
		WHERE hc_nota_operatoria_cirugia_id='".$_SESSION['NotaOperatoria']['NotaId']."' AND procedimiento_qx='".$_REQUEST['procedimiento']."'";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$this->frmForma();
		return true;
	}

	function ConfirmarExisteCumplimiento(){
    list($dbconn) = GetDBconn();
    $query="SELECT hc_nota_operatoria_cirugia_id
		FROM hc_notas_operatorias_cirugias
		WHERE qx_cumplimiento_id='".$this->QXcumplimiento."'";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$var=$resulta->GetRowAssoc($ToUpper = false);
			if($var['hc_nota_operatoria_cirugia_id']){
        $_SESSION['NotaOperatoria']['NotaId']=$var['hc_nota_operatoria_cirugia_id'];
			}
			return true;
		}
	}


/*
	function AtencionConsulta()
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT detalle,a.tipo_atencion_id,b.evolucion_id FROM hc_tipos_atencion as a left join hc_atencion as b on (a.tipo_atencion_id=b.tipo_atencion_id and b.evolucion_id=".$this->evolucion.") order by tipo_atencion_id asc;";
		//echo $query;
		$result = $dbconn->Execute($query);
		$i=0;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
				$atencion[0][$i]=$result->fields[0];
				$atencion[1][$i]=$result->fields[1];
				$atencion[2][$i]=$result->fields[2];
				$result->MoveNext();
				$i++;
			}
		}
		return $atencion;
	}

	function RiesgoAtencion()
	{
		list($dbconn) = GetDBconn();
		$query = "select d.tipo_atencion_id, d.detalle from pacientes as a join hc_diagnosticos_ingreso as b on(a.paciente_id='".$this->paciente."' and a.tipo_id_paciente='".$this->tipoidpaciente."') join enfermedades_profesionales as c on(b.tipo_diagnostico_id=c.diagnostico_id and a.ocupacion_id=c.ocupacion_id) join hc_tipos_atencion as d on(c.tipo_atencion_id=d.tipo_atencion_id) where b.evolucion_id=".$this->evolucion.";";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			if(!$result->EOF)
			{
				while(!$result->EOF)
				{
					$dato[]=$result->GetRowAssoc(false);
					$result->MoveNext();
				}
			}
			else
			{
				return false;
			}
		}
		return $dato;
	}
*/
	function ConsultaAtencion()
	{
		list($dbconn) = GetDBconn();
		$query ="SELECT detalle FROM hc_atencion,hc_tipos_atencion where hc_atencion.tipo_atencion_id = hc_tipos_atencion.tipo_atencion_id and evolucion_id=".$this->evolucion.";";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		return $result;
	}
	
	function ConsultaNotasOperatoriasRealizadas(){
		list($dbconn) = GetDBconn();
		$query ="SELECT a.hc_nota_operatoria_cirugia_id,a.quirofano_id,x.descripcion as nom_quirofano,a.hora_inicio,a.hora_fin,
		a.via_acceso,b.descripcion as via,a.tipo_cirugia,c.descripcion as tipo,
		a.ambito_cirugia,d.descripcion as ambito,a.finalidad_procedimiento_id,e.descripcion as finalidad,
		a.justificacion_norealizados,		
		a.diagnostico_post_qx,diag.diagnostico_nombre as diag_nom,a.tipo_diagnostico_post_qx,
		a.diagnostico_id_complicacion,diag1.diagnostico_nombre as diag_nom1,a.tipo_diagnostico_complicacion,
		a.envio_patologico,a.descripcion_envio_patologico,a.envio_cultivo,a.descripcion_envio_cultivo,
    instru.nombre_tercero as instrumentador,circu.nombre_tercero as circulante,aneste.nombre_tercero as anestesiologo,ayu.nombre_tercero as ayudante
		FROM hc_notas_operatorias_cirugias a
		LEFT JOIN qx_quirofanos x ON (a.quirofano_id=x.quirofano)
		LEFT JOIN qx_vias_acceso b ON (a.via_acceso=b.via_acceso)
		LEFT JOIN qx_tipos_cirugia c ON (a.tipo_cirugia=c.tipo_cirugia_id)
		LEFT JOIN qx_ambitos_cirugias d ON (a.ambito_cirugia=d.ambito_cirugia_id)
		LEFT JOIN qx_finalidades_procedimientos e ON (a.finalidad_procedimiento_id=e.finalidad_procedimiento_id)		
		LEFT JOIN diagnosticos diag ON (a.diagnostico_post_qx=diag.diagnostico_id)
		LEFT JOIN diagnosticos diag1 ON (a.diagnostico_id_complicacion=diag1.diagnostico_id)
    LEFT JOIN terceros instru ON (instru.tipo_id_tercero=a.tipo_id_instrumentista AND instru.tercero_id=a.instrumentista_id)
    LEFT JOIN terceros circu ON (circu.tipo_id_tercero=a.tipo_id_circulante AND circu.tercero_id=a.circulante_id)    
    LEFT JOIN terceros aneste ON (aneste.tipo_id_tercero=a.tipo_id_anestesiologo AND aneste.tercero_id=a.anestesiologo_id)    
    LEFT JOIN terceros ayu ON (ayu.tipo_id_tercero=a.tipo_id_ayudante AND ayu.tercero_id=a.ayudante_id),    
		hc_evoluciones evol,ingresos ing
		WHERE a.evolucion_id<>".$this->evolucion." AND a.usuario_id='".UserGetUID()."' AND ing.ingreso='".$this->ingreso."' AND
		a.evolucion_id=evol.evolucion_id AND evol.ingreso=ing.ingreso AND ing.tipo_id_paciente='".$this->tipoidpaciente."' AND ing.paciente_id='".$this->paciente."'";
		//LEFT JOIN diagnosticos diag2 ON (a.diagnostico_pre_qx=diag2.diagnostico_id)
		//a.diagnostico_pre_qx,diag2.diagnostico_nombre as diag_nom2,a.tipo_diagnostico_pre_qx,
		
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			while(!$result->EOF){
				$vars[]=$result->GetRowAssoc($toUpper=false);
				$result->MoveNext();
			}
		}			
		return $vars;	
	}
	
	function ConsultaNotasOperatoriasRealizadasHis(){
		list($dbconn) = GetDBconn();
		$query ="SELECT a.hc_nota_operatoria_cirugia_id,a.quirofano_id,x.descripcion as nom_quirofano,a.hora_inicio,a.hora_fin,
		a.via_acceso,b.descripcion as via,a.tipo_cirugia,c.descripcion as tipo,
		a.ambito_cirugia,d.descripcion as ambito,a.finalidad_procedimiento_id,e.descripcion as finalidad,
		a.justificacion_norealizados,		
		a.diagnostico_post_qx,diag.diagnostico_nombre as diag_nom,a.tipo_diagnostico_post_qx,
		a.diagnostico_id_complicacion,diag1.diagnostico_nombre as diag_nom1,a.tipo_diagnostico_complicacion,
		a.envio_patologico,a.descripcion_envio_patologico,a.envio_cultivo,a.descripcion_envio_cultivo,
    instru.nombre_tercero as instrumentador,circu.nombre_tercero as circulante,aneste.nombre_tercero as anestesiologo,ayu.nombre_tercero as ayudante
		FROM hc_notas_operatorias_cirugias a
		LEFT JOIN qx_quirofanos x ON (a.quirofano_id=x.quirofano)
		LEFT JOIN qx_vias_acceso b ON (a.via_acceso=b.via_acceso)
		LEFT JOIN qx_tipos_cirugia c ON (a.tipo_cirugia=c.tipo_cirugia_id)
		LEFT JOIN qx_ambitos_cirugias d ON (a.ambito_cirugia=d.ambito_cirugia_id)
		LEFT JOIN qx_finalidades_procedimientos e ON (a.finalidad_procedimiento_id=e.finalidad_procedimiento_id)		
		LEFT JOIN diagnosticos diag ON (a.diagnostico_post_qx=diag.diagnostico_id)
		LEFT JOIN diagnosticos diag1 ON (a.diagnostico_id_complicacion=diag1.diagnostico_id)
    LEFT JOIN terceros instru ON (instru.tipo_id_tercero=a.tipo_id_instrumentista AND instru.tercero_id=a.instrumentista_id)
    LEFT JOIN terceros circu ON (circu.tipo_id_tercero=a.tipo_id_circulante AND circu.tercero_id=a.circulante_id)    
    LEFT JOIN terceros aneste ON (aneste.tipo_id_tercero=a.tipo_id_anestesiologo AND aneste.tercero_id=a.anestesiologo_id)    
    LEFT JOIN terceros ayu ON (ayu.tipo_id_tercero=a.tipo_id_ayudante AND ayu.tercero_id=a.ayudante_id),    
		hc_evoluciones evol,ingresos ing
		WHERE ing.ingreso='".$this->ingreso."' AND
		a.evolucion_id=evol.evolucion_id AND evol.ingreso=ing.ingreso AND ing.tipo_id_paciente='".$this->tipoidpaciente."' AND ing.paciente_id='".$this->paciente."'";
		//LEFT JOIN diagnosticos diag2 ON (a.diagnostico_pre_qx=diag2.diagnostico_id)
		//a.diagnostico_pre_qx,diag2.diagnostico_nombre as diag_nom2,a.tipo_diagnostico_pre_qx,
		
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			while(!$result->EOF){
				$vars[]=$result->GetRowAssoc($toUpper=false);
				$result->MoveNext();
			}
		}			
		return $vars;	
	}
	
	function ProcedimientosNotaOperatoria($NotaId){
		list($dbconn) = GetDBconn();
		$query ="SELECT a.procedimiento_qx,b.descripcion,a.observaciones
		FROM hc_notas_operatorias_procedimientos a,cups b
		WHERE a.hc_nota_operatoria_cirugia_id=".$NotaId." AND 
		a.procedimiento_qx=b.cargo AND a.realizado='1'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			while(!$result->EOF){
				$vars[]=$result->GetRowAssoc($toUpper=false);
				$result->MoveNext();
			}
		}			
		return $vars;	
	}
	
	function Diagnosticos_ProcedimientosNO($NotaId,$procedimiento_qx){
		list($dbconn) = GetDBconn();
		$query ="SELECT a.diagnostico_id,b.diagnostico_nombre,a.tipo_diagnostico,
		a.sw_principal
		FROM hc_notas_operatorias_procedimientos_diags a,diagnosticos b
		WHERE a.hc_nota_operatoria_cirugia_id=".$NotaId." AND 
		a.procedimiento_qx=".$procedimiento_qx." AND a.diagnostico_id=b.diagnostico_id";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			while(!$result->EOF){
				$vars[]=$result->GetRowAssoc($toUpper=false);
				$result->MoveNext();
			}
		}			
		return $vars;		
	}	
	
/**
* Funcion que busca en los profesionales especialistas anestesiologos existentes en la base de datos
* @return array
*/
	function profesionalesEspecialistaAnestecistas(){
    
		list($dbconn) = GetDBconn();
    $query = "SELECT  x.tercero_id,c.nombre_tercero as nombre,x.tipo_id_tercero
    FROM profesionales x,profesionales_departamentos y,especialidades z,profesionales_especialidades l,terceros c
    WHERE (x.tipo_profesional='1' OR x.tipo_profesional='2') AND x.tipo_id_tercero=y.tipo_id_tercero AND
    x.tercero_id=y.tercero_id AND y.departamento='".$this->departamento."' AND z.especialidad=l.especialidad AND
    z.sw_anestesiologo='1' AND x.tercero_id=l.tercero_id AND x.tipo_id_tercero=l.tipo_id_tercero  AND
    x.tercero_id=c.tercero_id AND x.tipo_id_tercero=c.tipo_id_tercero AND
    profesional_activo(c.tipo_id_tercero,c.tercero_id,'".$this->departamento."')='1'
    ORDER BY c.nombre_tercero";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'profesionales' esta vacia ";
				return false;
			}
			while (!$result->EOF) {
				$vars[]=$result->GetRowAssoc($toUpper=false);
			  $result->MoveNext();
			}
		}
		$result->Close();
 		return $vars;
	}
	
	/**
* Funcion que busca los profesionales Ayudantes existentes en la base de datos
* @return array
*/
	function profesionalesAyudantes(){
    
		list($dbconn) = GetDBconn();
		$query = "SELECT x.tercero_id,z.nombre_tercero as nombre,x.tipo_id_tercero
    FROM profesionales x,profesionales_departamentos y,terceros z
    WHERE (x.tipo_profesional='1' OR x.tipo_profesional='2') AND x.tipo_id_tercero=y.tipo_id_tercero AND
    x.tercero_id=y.tercero_id AND y.departamento='".$this->departamento."' AND x.tercero_id=z.tercero_id AND
    x.tipo_id_tercero=z.tipo_id_tercero AND profesional_activo(z.tipo_id_tercero,z.tercero_id,'".$this->departamento."')='1'
    ORDER BY z.nombre_tercero";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'profesionales' esta vacia ";
				return false;
			}
			while (!$result->EOF) {
				$vars[]=$result->GetRowAssoc($toUpper=false);
			  $result->MoveNext();
			}
		}
		$result->Close();
 		return $vars;
	}
	
	/**
* Funcion que busca en los profesionales especialistas anestesiologos existentes en la base de datos
* @return array
*/
	function profesionalesEspecialistaCiculantes(){    
		list($dbconn) = GetDBconn();
		$query = "SELECT x.tercero_id,c.nombre_tercero as nombre,x.tipo_id_tercero
    FROM profesionales x,profesionales_departamentos y,especialidades z,profesionales_especialidades l,terceros c
    WHERE x.tipo_id_tercero=y.tipo_id_tercero AND x.tercero_id=y.tercero_id AND y.departamento='".$this->departamento."' AND
    z.especialidad=l.especialidad AND z.sw_circulante='1' AND x.tercero_id=l.tercero_id AND
    x.tipo_id_tercero=l.tipo_id_tercero  AND x.tercero_id=c.tercero_id AND x.tipo_id_tercero=c.tipo_id_tercero AND
    profesional_activo(c.tipo_id_tercero,c.tercero_id,'".$this->departamento."')='1'
    ORDER BY c.nombre_tercero";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'profesionales' esta vacia ";
				return false;
			}
			while (!$result->EOF) {
				$vars[]=$result->GetRowAssoc($toUpper=false);
			  $result->MoveNext();
			}
		}
		$result->Close();
 		return $vars;
	}

/**
* Funcion que busca en los profesionales especialistas anestesiologos existentes en la base de datos
* @return array
*/
	function profesionalesEspecialistaInstrumentistas(){
    $departamento=$_SESSION['LocalCirugias']['departamento'];
		list($dbconn) = GetDBconn();
		$query = "SELECT  x.tercero_id,c.nombre_tercero as nombre,x.tipo_id_tercero
    FROM profesionales x,profesionales_departamentos y,especialidades z,profesionales_especialidades l,terceros c
    WHERE x.tipo_id_tercero=y.tipo_id_tercero AND x.tercero_id=y.tercero_id AND y.departamento='".$this->departamento."' AND
    z.especialidad=l.especialidad AND z.sw_instrumentista='1' AND x.tercero_id=l.tercero_id AND
    x.tipo_id_tercero=l.tipo_id_tercero  AND x.tercero_id=c.tercero_id AND x.tipo_id_tercero=c.tipo_id_tercero AND
    profesional_activo(c.tipo_id_tercero,c.tercero_id,'".$this->departamento."')='1' ORDER BY c.nombre_tercero";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'profesionales' esta vacia ";
				return false;
			}
			while (!$result->EOF) {
				$vars[]=$result->GetRowAssoc($toUpper=false);
			  $result->MoveNext();
			}
		}
		$result->Close();
 		return $vars;
	}
  
  function BuscarProcedimientosInsertados($programacion_qx,$cargo){          
      
      list($dbconn) = GetDBconn();
      $query = "SELECT b.procedimiento_opcion,b.descripcion
                FROM qx_cups_opc_procedimientos_programacion a,qx_cups_opciones_procedimientos b 
                WHERE a.programacion_id='".$programacion_qx."' 
                AND a.procedimiento_qx='".$cargo."' 
                AND a.procedimiento_qx=b.cargo AND a.procedimiento_opcion=b.procedimiento_opcion";
      $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() != 0) {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }else{
        while (!$result->EOF) {
          $vars[]=$result->GetRowAssoc($toUpper=false);
          $result->MoveNext();
        }
      }                 
      return $vars;
      
  }
  
  function BuscarProcedimientosInsertadosNotaOperatoria($NotaId,$cargo){          
      
      list($dbconn) = GetDBconn();
      $query = "SELECT b.procedimiento_opcion,b.descripcion
                FROM hc_notas_operatorias_procedimientos_opciones a,qx_cups_opciones_procedimientos b 
                WHERE a.hc_nota_operatoria_cirugia_id='".$NotaId."' 
                AND a.procedimiento_qx='".$cargo."' 
                AND a.procedimiento_qx=b.cargo AND a.procedimiento_opcion=b.procedimiento_opcion";
      $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() != 0) {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }else{
        while (!$result->EOF) {
          $vars[]=$result->GetRowAssoc($toUpper=false);
          $result->MoveNext();
        }
      }                 
      return $vars;
      
  }
  
  function BuscarOpcionesProcedimientos($cups){ 
      list($dbconn) = GetDBconn();         
      $query = "SELECT a.procedimiento_opcion,a.descripcion
      FROM qx_cups_opciones_procedimientos a
      WHERE a.cargo='$cups' ORDER BY a.descripcion";
      $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{      
      while(!$result->EOF){
        $vars[]=$result->GetRowAssoc($toUpper=false);
        $result->MoveNext();
      }
    }     
    return $vars;
  }
  
  function ComprobarOpcionesProcedimientosCups(){
    list($dbconn) = GetDBconn();
    $query="SELECT a.valor
    FROM system_modulos_variables a
    WHERE a.modulo='Quirurgicos' AND 
    a.modulo_tipo='app' AND 
    a.variable='cups_opciones_procedimientos'";
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      if($result->fields[0]==1){
        return 1;
      }else{
        return 0;
      }
    }
  }
  
  function NombreOpcionProcedimiento($procedimiento,$cargo){                  
    list($dbconn) = GetDBconn();      
    $query = "SELECT descripcion
              FROM qx_cups_opciones_procedimientos
              WHERE cargo='".$cargo."' 
              AND procedimiento_opcion='".$procedimiento."'
                ";
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{      
      return $result->fields[0];      
    }
  }
	
}
?>
