<?php
  /**
  * Submodulo de Atencion.
  *
  * Submodulo para manejar el tipo de atencion (rips) de un paciente en una evolucion.
  * @version 1.0
  * @package SIIS
  * $Id: hc_NotasOperatoriasCOC.php,v 1.2 2009/08/11 21:28:44 hugo Exp $
  */
  /**
  * Atencion
  *
  * Clase para accesar los metodos privados de la clase de presentacion, se compone de metodos publicos para insertar
  * en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de insercion y la consulta del
  * submodulo de atencion.
  */
  class NotasOperatoriasCOC extends hc_classModules
  {
    /**
    * Esta funcion Inicializa las variable de la clase
    *
    * @access public
    * @return boolean Para identificar que se realizo.
    */
  	function NotasOperatoriasCOC()
    {
  	 	$this->limit=GetLimitBrowser();
    	return true;
  	}

/**
* Esta funcion retorna los datos de la impresion de la consulta del submodulo.
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
* Esta metodo captura los datos de la impresion de la Historia Clinica.
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
* Esta funcion verifica si este submodulo fue utilizado para la atencion de un paciente.
*
* @access private
* @return text Datos HTML de la pantalla.
*/

	function GetEstado()
	{
		return true;
	}
  /**
  * Esta funcion retorna la presentacion del submodulo (consulta o insercion).
  *
  * @access public
  * @return text Datos HTML de la pantalla.
  * @param text Determina la accion a realizar.
  */
	function GetForma()
	{
    $request = $_REQUEST;
    if($request['acto_quirurgico'])
      SessionSetVar("ActoQuirurgicoNotaO",$request['acto_quirurgico']);
    if($request['usuario_registro']) 
      SessionSetVar("UsuarioRegistroNotaO",$request['usuario_registro']);
    if($request['hc_nota_operatoria_cirugia_id']) 
      SessionSetVar("hc_nota_operatoria_cirugia_id",$request['hc_nota_operatoria_cirugia_id']);
    
    if($request['crear_nota'] == '1')
      SessionSetVar("NotaOperatoriaNueva","1");
    
    $acto = SessionGetVar("ActoQuirurgicoNotaO");
    
		if(empty($_REQUEST['accion'.$this->frmPrefijo]) || !$_REQUEST['accion'.$this->frmPrefijo])
		{
      SessionDelVar('NOTAS_OPERATORIAS'.$this->frmPrefijo);
      SessionDelVar("ActoQuirurgicoNotaO");
      SessionDelVar("UsuarioRegistroNotaO");
      SessionDelVar("NotaOperatoriaNueva");
      
      $this->frmFormaActoQ();
    }
    else if($request['accion'.$this->frmPrefijo] == "AnularNota")
    {
      $mensaje = "LA NOTA OPERATORIA ".$request['hc_nota_operatoria_cirugia_id']." HA SIDO ELIMINADA";
      $rst = $this->AnularNotasOperatorias($request);
      if(!$rst)
        $this->FormaMensajeModulo($action,$this->mensajeDeError);
      else
        $this->frmFormaActoQ($mensaje);
    }
    else if($_REQUEST['accion'.$this->frmPrefijo]=='FrmInicialNotasOperatorias')
		{
      //Datos de la Programacion			
			if(!is_array($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]))
			{	
				$datos=$this->NotaOperatoriaActual();
				if(is_array($datos))
				{	
					$cirujano = $this->DatosProfesionalesCirujano($datos['programacion_id'],$acto,$datos['hc_nota_operatoria_cirugia_id']);
					(list($fechaIn,$horaIn)=explode(" ",$datos['hora_inicio']));			
					(list($anoIni,$mesIni,$diaIni)=explode("-",$fechaIn));
					(list($hhIn,$mmIn,$ddIn)=explode(":",$horaIn));
					(list($fechaFn,$horaFn)=explode(" ",$datos['hora_fin']));			
					(list($anoFin,$mesFin,$diaFin)=explode("-",$fechaFn));			
					(list($hhFn,$mmFn)=explode(":",$horaFn));
					$DuracionMin=(mktime($hhFn,$mmFn+1,0,$mesFin,$diaFin,$anoFin)-mktime($hhIn,$mmIn,0,$mesIni,$diaIni,$anoIni))/60;			
					$horasDur=(int)($DuracionMin / 60);
					$minDur=($DuracionMin % 60);					
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROGRAMACION']=$datos['programacion_id'];
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']=$datos['hc_nota_operatoria_cirugia_id'];
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['fechainicio']=$diaIni.'-'.$mesIni.'-'.$anoIni;
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['fechafinal']=$diaFin.'-'.$mesFin.'-'.$anoFin;
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['horafin']=$hhFn;
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['minutosfin']=$mmFn;
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['hora']=$hhIn;
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['minutos']=$mmIn;
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['horadur']=$horasDur;
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['minutosdur']=$minDur;
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['quirofano']=$datos['quirofano_id'];
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['viaAcceso']=$datos['via_acceso'];
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['ambitoCirugia']=$datos['ambito_cirugia'];
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['finalidadCirugia']=$datos['finalidad_procedimiento_id'];
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['tipoCirugia']=$datos['tipo_cirugia'];
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['POST_QUIRURGICO'][$datos['diagnostico_post_qx']][$datos['tipo_diagnostico_post_qx']]=$datos['diagnostico_nombre_post_qx'];
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['COMPLICACION'][$datos['diagnostico_id_complicacion']][$datos['tipo_diagnostico_complicacion']]=$datos['diagnostico_nombre_complica'];
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['justificacion']=$datos['justificacion_norealizados'];
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['cirujano']=$cirujano['cirujano_id'].'/'.$cirujano['tipo_id_cirujano'];
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['tipoanestesia']=$datos['qx_tipo_anestesia_id'].'/'.$datos['sw_uso_gases'];	
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['sw_urg_prog']=$datos['sw_urg_prog'];	
					
					$gases = $this->ConsultarGases($acto);
         
					if($gases)
					{
						for($i=0;$i<sizeof($gases);$i++)
						{           
							$_SESSION['Liquidacion_QX']['GASES'][$i]['TipoGasDes']=$this->consultartipogas($gases[$i][0]);           
							$_SESSION['Liquidacion_QX']['GASES'][$i]['MetodoGas']=$gases[$i][1];           
							$_SESSION['Liquidacion_QX']['GASES'][$i]['MetodoGasDes']=$this->consultartiposuministro($gases[$i][1]);           
							$_SESSION['Liquidacion_QX']['GASES'][$i]['FrecuenciaGas']=$gases[$i][2];
							$_SESSION['Liquidacion_QX']['GASES'][$i]['FrecuenciaGasDes']=$gases[$i][4];
							$_SESSION['Liquidacion_QX']['GASES'][$i]['MinutosGas']=$gases[$i][3];
							$_SESSION['Liquidacion_QX']['GASES'][$i]['GasId']=$gases[$i][7];
						}
					}
           
					if($_REQUEST['MaterialPat'] !="")
					{
						$this->InsertDatosPatologia($acto);
					}
					if($_REQUEST['Cultivo'] !="")
					{
						$this->InsertDatosCultivos($acto);
					}
					if($_REQUEST['hallazgos'] !="")
					{
						$this->InsertDatosHallazgos($acto);
					}
					if($_REQUEST['descripciones'] !="")
					{
						$this->InsertDatosDescripcion($acto);
					}
                
					$procedimientos=$this->BuscarProcedimientosCirugia($_SESSION['NOTAS_OPERATORIASfrm_NotasOperatoriasCOC']['PROGRAMACION']);				
        
					if($procedimientos)
					{
						for($i=0;$i < sizeof($procedimientos);$i++)
						{
								if($procedimientos[$i]['programado']=='1')
								{
									$diagnosticos=$this->DiagsProcedimientosNotasOperatorias($procedimientos[$i]['procedimiento_qx'],$acto);
									if($diagnosticos)
									{
										for($j=0;$j < sizeof($diagnosticos);$j++)
										{
											$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['DIAGNOSTICOS'][$procedimientos[$i]['procedimiento_qx']][$diagnosticos[$j]['diagnostico_id']][$diagnosticos[$j]['tipo_diagnostico']]=$diagnosticos[$j]['diagnostico_nombre'];
											if($diagnosticos[$j]['sw_principal']=='1')
											{
												$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['DIAGNOSTICO_PRINCIPAL'][$procedimientos[$i]['procedimiento_qx']]=$diagnosticos[$j]['diagnostico_id'];
											}											
										}
									}
								}							
							else
							{
								$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['2'][$procedimientos[$i]['procedimiento_qx']]=$procedimientos[$i]['cargo'];
                  $procedimientosOpc=$this->BuscarProcedimientosInsertados($programacion,$procedimientos[$i]['procedimiento_qx']); 
                  for($m=0;$m<sizeof($procedimientosOpc);$m++)
                  {
                      $_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS_OPCIONES'][$procedimientos[$i]['procedimiento_qx']][$procedimientosOpc[$m]['procedimiento_opcion']]=$procedimientosOpc[$m]['descripcion'];
                  }
							}
              
                  $procedimientosOpc=$this->BuscarProcedimientosInsertadosNotaOperatoria($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID'],$procedimientos[$i]['procedimiento_qx'],$acto); 
                  for($m=0;$m<sizeof($procedimientosOpc);$m++)
                  {
                    $_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS_OPCIONES'][$procedimientos[$i]['procedimiento_qx']][$procedimientosOpc[$m]['procedimiento_opcion']]=$procedimientosOpc[$m]['descripcion'];
                  }              							
						}					
					}
					$datosProfesionales=$this->DatosProfesionalesCirugia($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROGRAMACION'],$acto,$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']);
					if($datosProfesionales)
					{					
						$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['anestesiologo']=$datosProfesionales['tercero_id'].'/'.$datosProfesionales['tipo_id_tercero'];
						$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['ayudante']=$datosProfesionales['ayudante_id'].'/'.$datosProfesionales['tipo_id_ayudante'];
						$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['instrumentador']=$datosProfesionales['instrumentista_id'].'/'.$datosProfesionales['tipo_id_instrumentista'];
						$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['circulante']=$datosProfesionales['circulante_id'].'/'.$datosProfesionales['tipo_id_circulante'];
					}					
				}
			}			
		//if(!empty($acto))
    //{    
			if(!is_array($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]))
			{					
				$programacion=$this->ProgramacionActivaPaciente($acto);
				
				$cirujano = $this->DatosProfesionalesCirujano($programacion,$acto,$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']);
				
				if($programacion)
				{
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROGRAMACION']=$programacion;
				}			
				$datosProfesionales=$this->DatosProfesionalesCirugia($programacion,$acto,$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']);
				if($datosProfesionales)
				{
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['anestesiologo']=$datosProfesionales['tercero_id'].'/'.$datosProfesionales['tipo_id_tercero'];
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['ayudante']=$datosProfesionales['ayudante_id'].'/'.$datosProfesionales['tipo_id_ayudante'];
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['instrumentador']=$datosProfesionales['instrumentista_id'].'/'.$datosProfesionales['tipo_id_instrumentista'];
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['circulante']=$datosProfesionales['circulante_id'].'/'.$datosProfesionales['tipo_id_circulante'];
				}
				$datos=$this->DatosCirugia($programacion);
				if($datos)
				{	
					(list($fechaIn,$horaIn)=explode(" ",$datos['hora_inicio']));			
					(list($anoIni,$mesIni,$diaIni)=explode("-",$fechaIn));
					(list($hhIn,$mmIn,$ddIn)=explode(":",$horaIn));
					(list($fechaFn,$horaFn)=explode(" ",$datos['hora_fin']));			
					(list($anoFin,$mesFin,$diaFin)=explode("-",$fechaFn));			
					(list($hhFn,$mmFn)=explode(":",$horaFn));
					$DuracionMin=(mktime($hhFn,$mmFn+1,0,$mesFin,$diaFin,$anoFin)-mktime($hhIn,$mmIn,0,$mesIni,$diaIni,$anoIni))/60;			
					$horasDur=(int)($DuracionMin / 60);
					$minDur=($DuracionMin % 60);
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['fechainicio']=$diaIni.'-'.$mesIni.'-'.$anoIni;
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['fechafinal']=$diaFin.'-'.$mesFin.'-'.$anoFin;
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['hora']=$hhIn;
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['minutos']=$mmIn;
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['horafin']=$hhFn;
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['minutosfin']=$mmFn;
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['horadur']=$horasDur;
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['minutosdur']=$minDur;
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['quirofano']=$datos['quirofano_id'];
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['viaAcceso']=$datos['via_acceso'];
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['ambitoCirugia']=$datos['ambito_cirugia'];
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['tipoCirugia']=$datos['tipo_cirugia'];
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['finalidadCirugia']=$datos['finalidad_procedimiento_id'];
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['cirujano']=$cirujano['cirujano_id'].'/'.$cirujano['tipo_id_cirujano'];
					$gases = $this->ConsultarGases($acto);
					if($gases)
					{
						for($i=0;$i<sizeof($gases);$i++)
						{
							$_SESSION['Liquidacion_QX']['GASES'][$i]['TipoGas']=$gases[$i][0];           
							$_SESSION['Liquidacion_QX']['GASES'][$i]['TipoGasDes']=$this->consultartipogas($gases[$i][0]);           
							$_SESSION['Liquidacion_QX']['GASES'][$i]['MetodoGas']=$gases[$i][1];           
							$_SESSION['Liquidacion_QX']['GASES'][$i]['MetodoGasDes']=$this->consultartiposuministro($gases[$i][1]);           
							$_SESSION['Liquidacion_QX']['GASES'][$i]['FrecuenciaGas']=$gases[$i][2];
							$_SESSION['Liquidacion_QX']['GASES'][$i]['FrecuenciaGasDes']=$gases[$i][4];
							$_SESSION['Liquidacion_QX']['GASES'][$i]['MinutosGas']=$gases[$i][3];           
							$_SESSION['Liquidacion_QX']['GASES'][$i]['GasId']=$gases[$i][7];
						}
					}					
									
				}				
        
				$procedimientos=$this->BuscarProcedimientosCirugia($programacion);
				if($procedimientos)
				{
					for($i=0;$i<sizeof($procedimientos);$i++)
					{
						$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['1'][$procedimientos[$i]['procedimiento_qx']]=$procedimientos[$i]['cargo'];
            $procedimientosOpc=$this->BuscarProcedimientosInsertados($programacion,$procedimientos[$i]['procedimiento_qx']);
            for($m=0;$m<sizeof($procedimientosOpc);$m++)
            {
                $_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS_OPCIONES'][$procedimientos[$i]['procedimiento_qx']][$procedimientosOpc[$m]['procedimiento_opcion']]=$procedimientosOpc[$m]['descripcion'];
            }
					}	
				}        
			}
    //}
			if(!is_array($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]))
			{
				$notas=$this->ConsultaNotasOperatoriasRealizadas($acto);
				if(is_array($notas))
				{
					$this->FrmConsultaNotasOperatoriasRealizadas($notas);
					return true;
				}
			}	
	   		$this->frmForma();
		}
		else
		{	
    /*SessionDelVar("NotaOperatoriaNueva");
			SessionDelVar('NOTAS_OPERATORIAS'.$this->frmPrefijo);*/
			if($_REQUEST['accion'.$this->frmPrefijo]=='FrmNotasOperatorias')
			{
        //$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['acto_qui']==NULL;
					
        
				if($_REQUEST['GuardarNota'.$this->frmPrefijo])
				{
					if($this->InsertDatos()==true)
					{
						$this->frmError["MensajeError"]="Datos Guardados Satisfactoriamente";
					}
          else
					{
						$this->frmError["MensajeError"]="Error al Guardar los Datos E2";
					}
				}
        
     if(is_array($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]))
		 {
			if(sizeof($_SESSION['Liquidacion_QX']['GASES'])>0)
			{	
				if($_REQUEST['GuardarNota'.$this->frmPrefijo])
				{ 
					$perfil = $this->PerfilProfesional();
					if($perfil=='1')
					{
					foreach($_SESSION['Liquidacion_QX']['GASES'] as $i=>$vector)
					{  
						if(!$this->ValidarCantidadGasesNota($vector,$acto,$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']))
						{
							if($this->InsertarGases($vector)==true)
							{
								$this->frmError["MensajeError"]="Datos Guardados Satisfactoriamente";
							}
              else
							{
								$this->frmError["MensajeError"]="Error al Guardar los Datos E1";
							}
						}
						
					}
					}  
				}
				if(sizeof($_REQUEST['array'])>0)
				{
					$this->EliminarGasAnestesicoVector($_REQUEST['array'],$acto,$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']);
					
				}
			}
		}
        
        
        $this->ActualizarVariablesSession();
				if($_REQUEST['MaterialPat'] !="")
				{
					$this->ActualizarVariablesSession();
					$this->InsertDatosPatologia($acto);
				}
				if($_REQUEST['Cultivo'] !="")
				{
					$this->ActualizarVariablesSession();
					$this->InsertDatosCultivos($acto);	
				}
				if($_REQUEST['hallazgos'] !="")
				{
					$this->ActualizarVariablesSession();
					$this->InsertDatosHallazgos($acto);
				}
				if($_REQUEST['descripciones'] !="")
				{
					$this->InsertDatosDescripcion($acto);
				}
				
				$this->ActualizarVariablesSession();
        
			
				
    		if($_REQUEST['activa']=='1')
    		{
    			$cargosNO = $this->ConsultaCupsNota();
    				while(!$cargosNO->EOF)
    				{
    					if($_REQUEST[$cargosNO->fields[0]])
    					{
    					if(!empty($_SESSION['NotaID']))
    					{
    						if(!$this->ExisteCupsNota($_REQUEST[$cargosNO->fields[0]]))
    						{
    							$this->InsertarCupsNota($_REQUEST[$cargosNO->fields[0]],$_SESSION['NotaID']); 
    						}
    					}
    					else 
    					{
    					if(!$this->ExisteCupsNota($_REQUEST[$cargosNO->fields[0]]))
    						{
    							$this->InsertarCupsNota($_REQUEST[$cargosNO->fields[0]],$_SESSION['NOTAS_OPERATORIASfrm_NotasOperatoriasCOC']['NOTA_ID']); 
    						}
    					}
    					}
    					else
    					{
    					if(!empty($_SESSION['NotaID']))
    					{
    						if($this->ExisteCupsNota($cargosNO->fields[0]))
    						{
    							$this->EliminarCupsNota($cargosNO->fields[0]); 
    						}
    						elseif(!$this->ExisteCupsNota($_REQUEST[$cargosNO->fields[0]]))
    						{
    							$this->InsertarCupsNota($_REQUEST[$cargosNO->fields[0]],$_SESSION['NotaID']); 
    						}
    					}
    					else 
    					{
    						if($this->ExisteCupsNota($cargosNO->fields[0]))
    						{
    							$this->EliminarCupsNota($cargosNO->fields[0]); 
    						}
    						elseif(!$this->ExisteCupsNota($_REQUEST[$cargosNO->fields[0]]))
    						{
    							$this->InsertarCupsNota($_REQUEST[$cargosNO->fields[0]],$_SESSION['NOTAS_OPERATORIASfrm_NotasOperatoriasCOC']['NOTA_ID']); 
    						}
    					}
    					}
    					$cargosNO->MoveNext();	 
    				}
    				$this->frmForma();
    		}
			
				elseif($_REQUEST['AdicionarProc'.$this->frmPrefijo])
				{
					$this->frmForma_ProcedimientosQX();
					return true;
				}
        elseif($_REQUEST['BuscarPreQX'.$this->frmPrefijo])
				{
					$this->frmForma_BuscarDiagnosticos($tipoDiag='PreQX');
					return true;
				}
        elseif($_REQUEST['BuscarPostQX'.$this->frmPrefijo])
				{
					$this->frmForma_BuscarDiagnosticos($tipoDiag='PostQX');
					return true;
				}
        elseif($_REQUEST['BuscarComplicacion'.$this->frmPrefijo])
				{
					$this->frmForma_BuscarDiagnosticos($tipoDiag='Complicacion');
					return true;
				}
				$this->frmForma();
				return true;
			}
			elseif($_REQUEST['accion'.$this->frmPrefijo]=='FrmBuscadorProcedimientos')
			{
				if($_REQUEST['buscar'.$this->frmPrefijo])
				{
					$this->frmForma_ProcedimientosQX();					
				}
        elseif($_REQUEST['volver'.$this->frmPrefijo])
				{
					$this->frmForma();							
				}
        elseif($_REQUEST['guardar'.$this->frmPrefijo])
				{
					$vector=$_REQUEST['op'.$this->frmPrefijo];
					foreach($vector as $cargo=>$procedimiento)
					{
						$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['3'][$cargo]=$procedimiento;
						$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['9'][$cargo]=$procedimiento;
					}
					$this->frmForma();
				}
				return true;
			}
			elseif($_REQUEST['accion'.$this->frmPrefijo]=='EliminaProcedimientoVec3')
			{
				$codigo=$_REQUEST['codigo'];				
        $this->EliminarProcedimiento($codigo,$acto);
        $this->frmForma();
				return true;				
			}
      elseif($_REQUEST['accion'.$this->frmPrefijo]=='EliminaProcedimientoVec4')
			{
				$codigo=$_REQUEST['codigo'];				
			  $this->EliminarProcedimientoProgramado($codigo);
				$this->frmForma();
				return true;				
			}
			
			elseif($_REQUEST['accion'.$this->frmPrefijo]=='EliminaProcedimientoV')
			{
				$codigo=$_REQUEST['codigo'];				
				$this->EliminarP($codigo);
				$this->frmForma();
				return true;				
			}
					
			elseif($_REQUEST['accion'.$this->frmPrefijo]=='insertar_varios_diagnosticos')
			{
				if($_REQUEST['guardar'.$this->frmPrefijo])
				{	
					$vector=$_REQUEST['opD'.$this->frmPrefijo];
					$vectorDiags=$_REQUEST['dx'.$this->frmPrefijo];
					foreach($vector as $cargoProc=>$Vecdiagnostico)
					{
						foreach($Vecdiagnostico as $codiag=>$nombreDiag)
						{
							if(empty($vectorDiags[$cargoProc][$codiag]))
							{
								$tipoDiag='1';
							}
              else
							{
								$tipoDiag=$vectorDiags[$cargoProc][$codiag];
							}
							$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['DIAGNOSTICOS'][$cargoProc][$codiag][$tipoDiag]=$nombreDiag;
							if(empty($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['DIAGNOSTICO_PRINCIPAL'][$cargoProc]))
							{
								$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['DIAGNOSTICO_PRINCIPAL'][$cargoProc]=$codiag;
							}
						}	
					}					
				}
        elseif($_REQUEST['Volver'.$this->frmPrefijo])
				{
					$this->frmForma();
					return true;
				}
				$this->frmForma_Modificar_Observacion($_REQUEST['cargo'.$this->frmPrefijo],$_REQUEST['descripcion'.$this->frmPrefijo]);
				return true;
			}
      elseif($_REQUEST['accion'.$this->frmPrefijo]=='FrmModificarProdedimiento')
			{	
				if($_REQUEST['CambioDiagPrincipal'.$this->frmPrefijo])
				{
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['DIAGNOSTICO_PRINCIPAL'][$_REQUEST['cargo'.$this->frmPrefijo]]=$_REQUEST['codiag'.$this->frmPrefijo];
				}
        elseif($_REQUEST['EliminacionDiagnostico'.$this->frmPrefijo])
				{
					unset($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['DIAGNOSTICOS'][$_REQUEST['cargo'.$this->frmPrefijo]][$_REQUEST['codiag'.$this->frmPrefijo]]);
					if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['DIAGNOSTICO_PRINCIPAL'][$_REQUEST['cargo'.$this->frmPrefijo]]==$_REQUEST['codiag'.$this->frmPrefijo])
					{
						unset($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['DIAGNOSTICO_PRINCIPAL'][$_REQUEST['cargo'.$this->frmPrefijo]]);
						$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['DIAGNOSTICO_PRINCIPAL'][$_REQUEST['cargo'.$this->frmPrefijo]]=$_REQUEST['codiag_uno'.$this->frmPrefijo];
					}
				}
        elseif($_REQUEST['guardar'.$this->frmPrefijo])
				{
					$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['OBSERVACIONES'][$_REQUEST['cargo'.$this->frmPrefijo]]=$_REQUEST['obs'.$this->frmPrefijo];
					$this->frmForma();
					return true;
				}
        elseif($_REQUEST['procedimiento_opcion'.$this->frmPrefijo])
				{
          unset($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS_OPCIONES'][$_REQUEST['cargo'.$this->frmPrefijo]][$_REQUEST['procedimiento_opcion'.$this->frmPrefijo]]);  
          
        }
        elseif($_REQUEST['modify_procedimiento_opcion'.$this->frmPrefijo])
				{
          unset($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS_OPCIONES'][$_REQUEST['cargo'.$this->frmPrefijo]]);      
          $vector=$_REQUEST['seleccion'.$this->frmPrefijo];
          for($i=0;$i<sizeof($vector);$i++)
					{
            $_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS_OPCIONES'][$_REQUEST['cargo'.$this->frmPrefijo]][$vector[$i]]=$this->NombreOpcionProcedimiento($vector[$i],$_REQUEST['cargo'.$this->frmPrefijo]);
          }
        }
				$this->frmForma_Modificar_Observacion($_REQUEST['cargo'.$this->frmPrefijo],$_REQUEST['descripcion'.$this->frmPrefijo]);
				return true;
			}
      elseif($_REQUEST['accion'.$this->frmPrefijo]=='FrmBuscarDiagnosticosPost')
			{
				if($_REQUEST['buscar'.$this->frmPrefijo])
				{
					$this->frmForma_BuscarDiagnosticos($_REQUEST['tipoDiag'.$this->frmPrefijo]);
					return true;
				}
        elseif($_REQUEST['Volver'.$this->frmPrefijo])
				{
					$this->frmForma();
					return true;				
				}
        elseif($_REQUEST['guardar'.$this->frmPrefijo])
				{
					$vector=$_REQUEST['opD'.$this->frmPrefijo];					
					(list($codigo,$diagnostico)=explode('||//',$vector[0]));
					$vectorDiags=$_REQUEST['dx'.$this->frmPrefijo];
					$tipo=$vectorDiags[$codigo];					
					if(empty($tipo))
					{
						$tipo=1;
					}						
					if($_REQUEST['tipoDiag'.$this->frmPrefijo]=='PostQX')
					{
						unset($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['POST_QUIRURGICO']);
						$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['POST_QUIRURGICO'][$codigo][$tipo]=$diagnostico;
					}
          elseif($_REQUEST['tipoDiag'.$this->frmPrefijo]=='PreQX')
					{
						unset($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PRE_QUIRURGICO']);
						$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PRE_QUIRURGICO'][$codigo][$tipo]=$diagnostico;
					}
          else
					{
						unset($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['COMPLICACION']);
						$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['COMPLICACION'][$codigo][$tipo]=$diagnostico;						
					}							
					$this->frmForma();					
					return true;
				}	
			}
		}
		return $this->salida;
	}
	
  
	function consultartipogas($id)
	{
		list($dbconn) = GetDBconn();
		$sql= "  SELECT descripcion 
            FROM   tipos_gases 
            WHERE  tipo_gas_id='".$id."'";
            
		$result = $dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {
			if($result->RecordCount()>0)
			{
				$result->Close();
				return $result->fields[0];
			}
		}		
		return true;	
	}
  
	function consultartiposuministro($id)
  {
		list($dbconn) = GetDBconn();
		 $sql=" SELECT descripcion 
            FROM   tipos_metodos_suministro_gases 
            WHERE  tipo_suministro_id='".$id."'";
            
		$result = $dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0)
    {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {
			if($result->RecordCount()>0)
			{
				$result->Close();
				return $result->fields[0];
			}
		}
		return true;
	}
  
	function ConsultarGases($acto)
	{
    if(empty($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']))
      return array();
      
		list($dbconn) = GetDBconn();
    
    $acto = SessionGetVar("ActoQuirurgicoNotaO");
		$VALOR=$this->ProgramacionActivaPaciente($acto);
   
		$query = "  SELECT MAX(a.evolucion_id), 
                       a.hc_nota_operatoria_cirugia_id 
                FROM   hc_notas_operatorias_cirugias a, 
                       hc_evoluciones b 
                WHERE  programacion_id= ".$VALOR." 
                  AND  a.evolucion_id=b.evolucion_id 
                  AND  b.estado='1'
                  AND  a.acto_quiru = ".$acto."
			          GROUP  BY hc_nota_operatoria_cirugia_id ";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
    {
			$this->error = "Error al Cargar el Modulo en la tabla qx_cumplimientos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {
			if($result->RecordCount() > 0){
				$evolucion_id = $result->fields[0];
				$_SESSION['nota']['evolucion_id'] = $evolucion_id;
			}
		}
		
		 $sql = "  SELECT h.tipo_gas_id, 
                     h.tipo_suministro_id, 
                     h.frecuencia_id, 
                     h.tiempo_suministro, 
                     g.unidad, 
                     h.evolucionid, 
                     h.ingresoid, 
                     h.hc_notaqx_gases_anestesicos_id
              FROM   hc_notaqx_gases_anestesicos h, 
                     tipos_frecuencia_gases g
		          WHERE  h.ingresoid = ".$this->ingreso." 
                 AND h.programacion_id= ".$VALOR." 
                 AND h.frecuencia_id=g.frecuencia_id
                 AND h.acto_quiru = ".$acto."
                 AND h.hc_nota_operatoria_cirugia_id = ".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']."  ";
		
		
		$result = $dbconn->Execute($sql);
		//jab - Si la consulta no retorna gases debido a una nueva evolucion
		//q limpie la variable de sesion GASES
		if($result->EOF)
    {
			unset($_SESSION['Liquidacion_QX']['GASES']);
    }	
		if($dbconn->ErrorNo() != 0)
    {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {
			if($result->RecordCount()>0)
			{
				$i=0;
				while (!$result->EOF) 
        {
					$datos[$i][0] =$result->fields[0]; //tipo_gas_id
					$datos[$i][1] =$result->fields[1]; //tipo_suministro_id
					$datos[$i][2] =$result->fields[2]; //frecuencia_id
					$datos[$i][3] =$result->fields[3]; //tiempo_suministro
					$datos[$i][4] =$result->fields[4]; //unidad
					$datos[$i][5] =$result->fields[5]; //evolucionid
					$datos[$i][6] =$result->fields[6]; //ingresoid
					$datos[$i][7] =$result->fields[7]; //hc_notaqx_gases_anestesicos_id
			  		$result->MoveNext();
					$i++;
				}
				return $datos;
			}
			else
      {
				return false;
			}
		}
		return true;
	}
  
  function ConsultarGasesImpresion($programacion)
	{
		if(empty($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']))
      return array();
    list($dbconn) = GetDBconn();
    $acto = SessionGetVar("ActoQuirurgicoNotaO");
		$VALOR=$this->ProgramacionActivaPaciente($acto); 
    
		$sql = "  SELECT h.tipo_gas_id, 
                     h.tipo_suministro_id, 
                     h.frecuencia_id, 
                     h.tiempo_suministro, 
                     g.unidad, 
		                 h.evolucionid, 
                     h.ingresoid, 
                     h.hc_notaqx_gases_anestesicos_id
		          FROM   hc_notaqx_gases_anestesicos h, 
                     tipos_frecuencia_gases g
		          WHERE  h.ingresoid = ".$this->ingreso." 
                 AND h.programacion_id= ".$programacion." 
                 AND h.frecuencia_id=g.frecuencia_id
                 AND h.hc_nota_operatoria_cirugia_id = ".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']."";
                 
		$result = $dbconn->Execute($sql);
		//jab - Si la consulta no retorna gases debido a una nueva evolucion
		//q limpie la variable de sesion GASES
		if($result->EOF)
    {	
			unset($_SESSION['Liquidacion_QX']['GASES']);
    }	
		if($dbconn->ErrorNo() != 0)
    {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {
			if($result->RecordCount()>0)
			{
				$i=0;
				while (!$result->EOF) 
        {
					$datos[$i][0] =$result->fields[0]; //tipo_gas_id
					$datos[$i][1] =$result->fields[1]; //tipo_suministro_id
					$datos[$i][2] =$result->fields[2]; //frecuencia_id
					$datos[$i][3] =$result->fields[3]; //tiempo_suministro
					$datos[$i][4] =$result->fields[4]; //unidad
					$datos[$i][5] =$result->fields[5]; //evolucionid
					$datos[$i][6] =$result->fields[6]; //ingresoid
			  	$result->MoveNext();
					$i++;
				}
				return $datos;
			}
			else
      {
				return false;
			}
		}
		return true;
	}
	
	function EliminarGasAnestesicoVector($vector,$acto,$notaid)
	{
		list($dbconn) = GetDBconn();
		for($i=0;$i<sizeof($_SESSION['Liquidacion_QX']['GASES']);$i++)
		{
			if($vector[0] == $i)
			{
		  	$sql=" DELETE 
               FROM   hc_notaqx_gases_anestesicos 
               WHERE  hc_notaqx_gases_anestesicos_id = ".$_SESSION['Liquidacion_QX']['GASES'][$i]['GasId']."
               AND    acto_quiru = ".$acto." 
               AND    hc_nota_operatoria_cirugia_id = ".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']."";
							 
			  $result = $dbconn->Execute($sql);
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
			
  			$_SESSION['Liquidacion_QX']['GASES'][$i]['TipoGas']=$_SESSION['Liquidacion_QX']['GASES'][$i+1]['TipoGas'];           
  			$_SESSION['Liquidacion_QX']['GASES'][$i]['TipoGasDes']=$_SESSION['Liquidacion_QX']['GASES'][$i+1]['TipoGasDes'];           
  			$_SESSION['Liquidacion_QX']['GASES'][$i]['MetodoGas']=$_SESSION['Liquidacion_QX']['GASES'][$i+1]['MetodoGas'];           
  			$_SESSION['Liquidacion_QX']['GASES'][$i]['MetodoGasDes']=$_SESSION['Liquidacion_QX']['GASES'][$i+1]['MetodoGasDes'];            
  			$_SESSION['Liquidacion_QX']['GASES'][$i]['FrecuenciaGas']=$_SESSION['Liquidacion_QX']['GASES'][$i+1]['FrecuenciaGas'];           
  			$_SESSION['Liquidacion_QX']['GASES'][$i]['FrecuenciaGasDes']=$_SESSION['Liquidacion_QX']['GASES'][$i+1]['FrecuenciaGasDes'];           
  			$_SESSION['Liquidacion_QX']['GASES'][$i]['MinutosGas']=$_SESSION['Liquidacion_QX']['GASES'][$i+1]['MinutosGas'];
			}
    }   
		unset($_SESSION['Liquidacion_QX']['GASES'][$i-1]); 
		return true; 
	}
	
	function profesionalesEspecialista($tipo_tercero=null,$tercero=null,$nombre=null)
  {		
		list($dbconn) = GetDBconn();
		$datos="";
		if(!empty($tipo_tercero) and !empty($tercero))
    {
			$datos="AND x.tipo_id_tercero='$tipo_tercero' 
              AND x.tercero_id='$tercero'";
		}		
		if(!empty($nombre))
    {
			$datos.="AND z.nombre_tercero ILIKE '%$nombre%'";
		}	
		
		$query = " SELECT  x.tercero_id,
                       z.nombre_tercero as nombre,
                       x.tipo_id_tercero
               FROM    profesionales x,
                       profesionales_departamentos y,
                       terceros z,
		                   profesionales_especialidades a,
                       especialidades b
		           WHERE  (x.tipo_profesional='1' OR x.tipo_profesional='2') 
                 AND  x.estado <> '0' 
                 AND  x.tipo_id_tercero=y.tipo_id_tercero 
                 AND  x.tercero_id=y.tercero_id
		             AND  x.tercero_id=z.tercero_id 
                 AND  x.tipo_id_tercero=z.tipo_id_tercero
		             AND  x.tercero_id=a.tercero_id 
                 AND  x.tipo_id_tercero=a.tipo_id_tercero 
                 AND  a.especialidad=b.especialidad 
                 AND  b.sw_cirujano=1
                $datos
                GROUP BY x.tercero_id,
                         z.nombre_tercero,
                         x.tipo_id_tercero 
                ORDER BY z.nombre_tercero";
		
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
    {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {
			if($result->EOF)
      {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'profesionales' esta vacia ";
				return false;
			}
			while (!$result->EOF) 
      {
				$vars[]=$result->GetRowAssoc($toUpper=false);
			  	$result->MoveNext();
			}
		}
		$result->Close();
 		return $vars;
	}
	
	function NombreProfesional($id_profesional)
  {
    list($dbconn) = GetDBconn();
		$query = "SELECT  nombre 
              FROM    profesionales
              WHERE   tercero_id = $id_profesional";
		
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
    {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$profe = $result->fields[0];
		return $profe;
	}
	//$notaid=$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID'];
	function ValidarCantidadGasesNota($vector,$acto,$notaid)
	{	
		list($dbconn) = GetDBconn();
		 $sql=" SELECT COUNT(*) 
           FROM hc_notaqx_gases_anestesicos 
           WHERE IngresoId = ".$this->ingreso."
		         AND tipo_gas_id = '".$vector['TipoGas']."'
		         AND tipo_suministro_id = '".$vector['MetodoGas']."'
		         AND frecuencia_id = '".$vector['FrecuenciaGas']."'
		         AND tiempo_suministro = '".$vector['MinutosGas']."'
             AND acto_quiru = ".$acto." 
             AND hc_nota_operatoria_cirugia_id = ".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']." ;";
		         
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB i: " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			if($result->fields[0] > 0)
			{
			//echo 'retorno mayor q 00000000';
			//exit;
				return true;
			}
			else
			{
			//echo 'retorno igual a 00000000';
			//exit;
				return false;
			}
		}
		return true;
	}
	
	
	function InsertarGases($vector)
  {
    $acto = SessionGetVar("ActoQuirurgicoNotaO");
    $VALOR=$this->ProgramacionActivaPaciente($acto);
		list($dbconn) = GetDBconn();
    
		$sql=" SELECT NEXTVAL('hc_notaqx_gases_anestesicos_hc_notaqx_gases_anestesicos_id_seq');";
		
    $result8 = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
    {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB i: " . $dbconn->ErrorMsg();
			return false;
		}
		if(!$result8->EOF)
    {
			$hc_notaqx_gases_anestesicos_id = $result8->fields[0];
			$result8->Close();
		}
    
    $acto = SessionGetVar("ActoQuirurgicoNotaO");
    
			 $query=" INSERT INTO hc_notaqx_gases_anestesicos( 
                            hc_notaqx_gases_anestesicos_id, 
					                  evolucionid,
                            ingresoid,
                            tipo_gas_id,
                            tipo_suministro_id,
					                  frecuencia_id,
                            fecha_registro,
                            usuario_id,
                            programacion_id,
                            tiempo_suministro,
                            acto_quiru,
                            hc_nota_operatoria_cirugia_id)
					      VALUES (".$hc_notaqx_gases_anestesicos_id.",
                        ".$this->evolucion.", 
                        ".$this->ingreso.",
                        '".$vector['TipoGas']."',
                        '".$vector['MetodoGas']."',
                      --'".$vector['FrecuenciaGas']."/*-".$vector['FrecuenciaGasDes']."*/',
                        '".$vector['FrecuenciaGas']."',
                        
					              '".date("Y-m-d H:i:s")."',
					              ".UserGetUID().",
                        ".$VALOR.",
                        '".$vector['MinutosGas']."',
                        ".$acto.",
                        ".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID'].");";
         ///'".$vector['MinutosGas']."',               
				$resulta=$dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0)
        {
					$this->error = "Error al insertar en hc_os_solicitudes";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
		return true;
	}
	
	function PerfilProfesional()
	{
		list($dbconn) = GetDBconn();
		$sql=" SELECT tipo_profesional 
           FROM   profesionales 
           WHERE  usuario_id =".UserGetUID().";";
           
		$result=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
				$this->error = "Error al consultar tipo profesional";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		else
		{
			if($result->RecordCount())
      {
				if($result->fields[0]=='2' || $result->fields[0]=='1')
				{
					return '0';
				}
				else if ($result->fields[0]=='8' || $result->fields[0]=='3' || $result->fields[0]=='4' || $result->fields[0]=='12' || $result->fields[0]=='7')
				{
					return '1';
				}
				else
				{
					return '2';
				}
			}
			else
      {
				return false;
			}
		}
  }
	
	function InsertDatosDescripcion($acto)
	{
    $acto = SessionGetVar("ActoQuirurgicoNotaO");
    $VALOR=$this->ProgramacionActivaPaciente($acto);
			list($dbconn) = GetDBconn();
	
			 $sql="INSERT INTO hc_descripcion_cirugia
							     ( descripcion,
							       evolucion_id,
							       ingreso,
							       usuario_id,
							       fecha_registro,
                     programacion_id,
                     acto_quiru,
                     hc_nota_operatoria_cirugia_id
                    )
						VALUES  ('".$_REQUEST['descripciones']."',
							       ".$this->evolucion.",
							       ".$this->ingreso.",
							       ".UserGetUID().",
							       now(),
                     ".$VALOR.",
                     ".$acto.",
                     ".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']."
                     );";
              
			$result=$dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al insertar las notas de Cirugia";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				$this->frmError['MensajeError']="Datos Guardados Satisfactoriamente.";
				$this->RegistrarSubmodulo($this->GetVersion());
        return true;
			}
	}
	
	function InsertarCupsNota($id,$Nota)
	{
		list($dbconn) = GetDBconn();
		
		$sql=" INSERT INTO cups_notaoperatoria (notaid,cargoid) 
           VALUES (".$Nota.",'".$id."');";
		$result=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
				$this->error = "Error al insertar Nota operatoria";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		else
    {
			if($result->RecordCount())
      {
				return true;
			}
			else
      {
				return false;
			}
		}
	}
	
	function EliminarCupsNota($id)
	{
		list($dbconn) = GetDBconn();
		$sql=" DELETE 
           FROM cups_notaoperatoria 
           WHERE notaid=".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']." 
             AND CargoId='".$id."';";
             
		$result=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
				$this->error = "Error al eliminar cups de Nota operatoria";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		else
    {
			return true;
		}	
	}
	
	function ExisteCupsNota($id)
	{
		list($dbconn) = GetDBconn();
		$sql=" SELECT * 
           FROM   cups_notaoperatoria 
           WHERE  notaid=".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']." 
           AND    CargoId='".$id."';";
           
		$result=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
				$this->error = "Error al consultar la nota operatoria";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		else
    {
			if($result->RecordCount())
      {
				return true;
			}
			else
      {
				return false;
			}
		}
	}
	
	function ConsultaCupsNota()
	{
		list($dbconn) = GetDBconn();
			$sql=" SELECT * 
             FROM   cups 
             WHERE  sw_notaoperatoria = '1'";
			$result=$dbconn->Execute($sql);
			if($result->RecordCount())
      {
				return $result;
			}
			else
      {
				return false;
			}	
	}

	function InsertDatosHallazgos($acto)
	{
    $acto = SessionGetVar("ActoQuirurgicoNotaO");
    $VALOR=$this->ProgramacionActivaPaciente($acto);
		list($dbconn) = GetDBconn();
			
    $sql= " INSERT INTO hc_hallazgos_quirurgicos
                       (descripcion,
                        evolucion_id,
                        ingreso,
                        usuario_id,
                        fecha_registro,
                        programacion_id,
                        acto_quiru,
                        hc_nota_operatoria_cirugia_id
                        )
          VALUES       ('".$_REQUEST['hallazgos']."',
                        ".$this->evolucion.",
                        ".$this->ingreso.",
                        ".UserGetUID().",
                        now(),
                        ".$VALOR.",
                        ".$acto.",
                        ".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID'].");";
			$result=$dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al insertar los Hallazgos Quirurgicos.";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				$this->frmError['MensajeError']="Datos Guardados Satisfactoriamente.";
				return true;
			}
	}
	
	function InsertDatosPatologia($acto)
	{
    $acto = SessionGetVar("ActoQuirurgicoNotaO");
    $VALOR=$this->ProgramacionActivaPaciente($acto);
		list($dbconn) = GetDBconn();
			
    $sql= " INSERT INTO hc_patologia_quirurgicos
                        ( descripcion,
                          evolucion_id,
                          ingreso,
                          usuario_id,
                          fecha_registro,
                          envio_patologico,
                          programacion_id,
                          acto_quiru,
                          hc_nota_operatoria_cirugia_id)
            VALUES      ('".$_REQUEST['MaterialPat']."',
                          ".$this->evolucion.",
                          ".$this->ingreso.",
                          ".UserGetUID().",
                          now(),
                          '".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['SelMaterialPat']."',
                          ".$VALOR.",
                          ".$acto.",
                          ".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID'].");";
    $result=$dbconn->Execute($sql);
    if ($dbconn->ErrorNo() != 0)
    {
      $this->error = "Error al insertar los Hallazgos Quirurgicos.";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }
    else
    {
      $this->frmError['MensajeError']="Datos Guardados Satisfactoriamente.";
      return true;
    }
	}
	
	function InsertDatosCultivos($acto)
	{
    $acto = SessionGetVar("ActoQuirurgicoNotaO");
    $VALOR=$this->ProgramacionActivaPaciente($acto);
	  list($dbconn) = GetDBconn();
    $sql= " INSERT INTO hc_cultivos_quirurgicos
                       (descripcion,
                        evolucion_id,
                        ingreso,
                        usuario_id,
                        fecha_registro,
                        envio_cultivo,
                        programacion_id,
                        acto_quiru,
                        hc_nota_operatoria_cirugia_id)
            VALUES    ('".$_REQUEST['Cultivo']."',
                        ".$this->evolucion.",
                        ".$this->ingreso.",
                        ".UserGetUID().",
                        now(),
                        '".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['SelCultivo']."',
                        ".$VALOR.",
                        ".$acto.",
                        ".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID'].");";
			$result=$dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al insertar los Hallazgos Quirurgicos.";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				$this->frmError['MensajeError']="Datos Guardados Satisfactoriamente.";
				return true;
			}
	}
		
	function DescripcionCirugia_Reporte()
	{
    if(empty($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']))
      return array();
      
    $acto = SessionGetVar("ActoQuirurgicoNotaO"); 
    $VALOR=$this->ProgramacionActivaPaciente($acto);
    $pfj=$this->frmPrefijo;
 
    list($dbconn) = GetDBconn();
			$query= " SELECT A.descripcion_cirugia_id,
               		         A.fecha_registro, 
                           A.descripcion, 
                           B.nombre, 
                           B.usuario
                    FROM   hc_descripcion_cirugia AS A,
					                 system_usuarios AS B
				            WHERE  A.ingreso='".$this->ingreso."'
                    AND    A.programacion_id=".$VALOR."
				            AND    B.usuario_id=A.usuario_id
                    AND    A.hc_nota_operatoria_cirugia_id = ".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']."
				            ORDER  BY fecha_registro DESC
				           --LIMIT ".$this->limit." OFFSET $Of;";
           
    $resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$datosfila=$resulta->GetRowAssoc($ToUpper = false);
			list($fecha,$hora) = explode(" ",$this->PartirFecha($datosfila['fecha_registro']));//substr(,0,10);
			list($ano,$mes,$dia) = explode("-",$fecha);//substr(,0,10);
			list($hora,$min) = explode(":",$hora);//substr(,0,10);
			$datosfila[hora]=$hora.":".$min;
			$datos[$fecha][]=$datosfila;
			$resulta->MoveNext();
		}
		if($this->conteo==='0')
		{
			$this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
		    	return false;
		}
		return $datos;
	}
	
	function HallazgosQuirurgicos_Reporte()
	{
    if(empty($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']))
      return array();
      
    $acto = SessionGetVar("ActoQuirurgicoNotaO");
		$VALOR=$this->ProgramacionActivaPaciente($acto);
   
		list($dbconn) = GetDBconn();
		  $query= " SELECT A.hallazgos_id,
               		   A.fecha_registro, 
                     A.descripcion, 
                     B.nombre, 
                     B.usuario
				       FROM  hc_hallazgos_quirurgicos AS A,
					           system_usuarios AS B
				       WHERE A.ingreso='".$this->ingreso."'
               AND   A.programacion_id=".$VALOR."
				       AND   B.usuario_id=A.usuario_id
               AND   A.hc_nota_operatoria_cirugia_id = ".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']." 
				       ORDER BY fecha_registro DESC
				       --LIMIT ".$this->limit." OFFSET $Of;";

		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$datosfila=$resulta->GetRowAssoc($ToUpper = false);
			list($fecha,$hora) = explode(" ",$this->PartirFecha($datosfila['fecha_registro']));//substr(,0,10);
			list($ano,$mes,$dia) = explode("-",$fecha);
			list($hora,$min) = explode(":",$hora);
			$datosfila[hora]=$hora.":".$min;
			$datos[$fecha][]=$datosfila;
			$resulta->MoveNext();
		}
		return $datos;
	}
	
	function PatologiaQuirurgicos_Reporte()
	{
    if(empty($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']))
      return array();
    $acto = SessionGetVar("ActoQuirurgicoNotaO");
		$VALOR=$this->ProgramacionActivaPaciente($acto);
   
		list($dbconn) = GetDBconn();
		 $query= " SELECT A.patologia_id,
               		        A.fecha_registro, 
                          A.descripcion, 
                          B.nombre, 
                          B.usuario
				           FROM   hc_patologia_quirurgicos AS A,
					                system_usuarios AS B
				           WHERE  A.ingreso='".$this->ingreso."'
                   AND    A.programacion_id=".$VALOR."
				           AND    B.usuario_id=A.usuario_id
                   AND    A.hc_nota_operatoria_cirugia_id = ".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']."
				           ORDER BY fecha_registro DESC
				           --LIMIT ".$this->limit." OFFSET $Of;";

		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$datosfila=$resulta->GetRowAssoc($ToUpper = false);
			list($fecha,$hora) = explode(" ",$this->PartirFecha($datosfila['fecha_registro']));//substr(,0,10);
			list($ano,$mes,$dia) = explode("-",$fecha);
			list($hora,$min) = explode(":",$hora);
			$datosfila[hora]=$hora.":".$min;
			$datos[$fecha][]=$datosfila;
			$resulta->MoveNext();
		}
		return $datos;
	}
	
	
	function CultivoQuirurgicos_Reporte()
	{
    if(empty($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']))
      return array();
    $acto = SessionGetVar("ActoQuirurgicoNotaO");
		$VALOR=$this->ProgramacionActivaPaciente($acto);
		
    list($dbconn) = GetDBconn();
		$query= " SELECT A.cultivos_id,
               		   A.fecha_registro, 
                     A.descripcion, 
                     B.nombre, 
                     B.usuario
				      FROM   hc_cultivos_quirurgicos AS A,
					           system_usuarios AS B
				      WHERE  A.ingreso='".$this->ingreso."'
              AND    A.programacion_id=".$VALOR."
				      AND    B.usuario_id=A.usuario_id
              AND    A.hc_nota_operatoria_cirugia_id = ".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']."
				      ORDER BY fecha_registro DESC
				   --LIMIT ".$this->limit." OFFSET $Of;";

		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$datosfila=$resulta->GetRowAssoc($ToUpper = false);
			list($fecha,$hora) = explode(" ",$this->PartirFecha($datosfila['fecha_registro']));//substr(,0,10);
			list($ano,$mes,$dia) = explode("-",$fecha);
			list($hora,$min) = explode(":",$hora);
			$datosfila[hora]=$hora.":".$min;
			$datos[$fecha][]=$datosfila;
			$resulta->MoveNext();
		}
		return $datos;
	}

	function PartirFecha($fecha)
	{
		$a=explode('-',$fecha);
		$b=explode(' ',$a[2]);
		$c=explode(':',$b[1]);
		$d=explode('.',$c[2]);
		return $a[0].'-'.$a[1].'-'.$b[0].' '.$c[0].':'.$c[1].':'.$d[0];
	}
	
	function ProcedimientosNotasOperatorias()
  {
		list($dbconn) = GetDBconn();
		$query=" SELECT a.procedimiento_qx,
                    b.descripcion,
                    a.realizado,
                    a.observaciones,
                  (SELECT 1 
                   FROM   qx_procedimientos_programacion c 
                   WHERE  c.programacion_id='".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROGRAMACION']."' 
                   AND    c.procedimiento_qx=a.procedimiento_qx) as programado
		               FROM   hc_notas_operatorias_procedimientos a,
                          cups b
		               WHERE  a.hc_nota_operatoria_cirugia_id='".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']."' 
                   AND    a.procedimiento_qx=b.cargo";
                   
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
    {
			$this->error = "Error al Cargar el Modulo en la tabla qx_cumplimientos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {
			if($result->RecordCount() > 0)
      {
				while(!$result->EOF)
        {
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}				
			}
		}
		return $vars;	
	}
	
	function DiagsProcedimientosNotasOperatorias($procedimiento,$acto)
  {
		list($dbconn) = GetDBconn();
		$query=" SELECT  a.diagnostico_id,
                     b.diagnostico_nombre,
                     a.sw_principal,
                     a.tipo_diagnostico
		         FROM    hc_notas_operatorias_procedimientos_diags a,
                     diagnosticos b
		         WHERE   a.hc_nota_operatoria_cirugia_id='".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']."' 
             AND     a.procedimiento_qx='$procedimiento' 
             AND     a.diagnostico_id=b.diagnostico_id
             AND     a.acto_quiru = ".$acto." ";
		
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
    {
			$this->error = "Error al Cargar el Modulo en la tabla qx_cumplimientos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {
			if($result->RecordCount() > 0)
      {
				while(!$result->EOF)
        {
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}				
			}
		}
		return $vars;		
	}
	
	function NotaOperatoriaActual()
	{
		list($dbconn) = GetDBconn();
    //$dbconn->debug=true;
    $acto = SessionGetVar("ActoQuirurgicoNotaO");
    $nota = SessionGetVar("hc_nota_operatoria_cirugia_id");
		$VALOR=$this->ProgramacionActivaPaciente($acto);
    
    $usuario_r = UserGetUID();
    if(SessionIsSetVar("UsuarioRegistroNotaO"))
      $usuario_r = SessionGetVar("UsuarioRegistroNotaO");
      
    if(!empty($acto))
    	$filtro1 .= "AND a.acto_quiru = ".$acto." " ;
		   
    $query  = "SELECT MAX(a.evolucion_id), ";
    $query .= "       a.hc_nota_operatoria_cirugia_id ";
    $query .= "FROM   hc_notas_operatorias_cirugias a, ";
    $query .= "       hc_evoluciones b ";
    $query .= "WHERE  programacion_id= ".$VALOR." ";
    $query .= "AND    a.evolucion_id=b.evolucion_id ";
    $query .= "AND    b.estado = '1' ";
    $query .= " ".$filtro1;
    if(!empty($nota))
      $query .= "AND    a.hc_nota_operatoria_cirugia_id = ".$nota." ";
      
    $query .= "AND    a.usuario_id = ".$usuario_r." ";
    $query .= "GROUP BY hc_nota_operatoria_cirugia_id ";
		    
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo en la tabla qx_cumplimientos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {
			if($result->RecordCount() > 0)
			{
				$evolucion_id = $result->fields[0];
			}
		}
		if(!$result->EOF)
		{
			$var_evol= " and a.evolucion_id = '".$evolucion_id."' ";
		}
    
    $query=" SELECT a.hc_nota_operatoria_cirugia_id,
                    a.quirofano_id,
                    a.hora_inicio,
		                a.hora_fin,
                    a.usuario_id,
                    a.fecha_registro,
                    a.via_acceso,
                    a.tipo_cirugia,
                    a.ambito_cirugia,
                    a.finalidad_procedimiento_id,
                    a.evolucion_id,
                    a.justificacion_norealizados,
                    a.programacion_id,		
                    a.diagnostico_post_qx,
                    a.tipo_diagnostico_post_qx,
                    b.diagnostico_nombre as diagnostico_nombre_post_qx,
                    a.diagnostico_id_complicacion,
                    a.tipo_diagnostico_complicacion,
                    c.diagnostico_nombre as diagnostico_nombre_complica,
                    a.envio_patologico,
                    a.descripcion_envio_patologico,
                    a.envio_cultivo,
                    a.descripcion_envio_cultivo, 
                    a.qx_tipo_anestesia_id, 
                    d.sw_uso_gases,
                    a.sw_urg_prog,
                    a.acto_quiru
             FROM   hc_notas_operatorias_cirugias a                    
		         LEFT JOIN diagnosticos b ON(a.diagnostico_post_qx=b.diagnostico_id)
		         LEFT JOIN diagnosticos c ON(a.diagnostico_id_complicacion=c.diagnostico_id)
		         LEFT JOIN qx_tipos_anestesia d ON(a.qx_tipo_anestesia_id=d.qx_tipo_anestesia_id)
		         WHERE a.programacion_id = ".$VALOR." 
             $var_evol  
             $filtro1";
    if(!empty($nota))
      $query .= "AND    a.hc_nota_operatoria_cirugia_id = ".$nota." ";         
    
    $query .= "AND   a.usuario_id = ".$usuario_r." ";
    
		$result = $dbconn->Execute($query);
		 if ($dbconn->ErrorNo() != 0)
     {
			$this->error = "Error al Cargar el Modulo en la tabla qx_cumplimientos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		 }
     else
     {
       //while(!$result->EOF)
       //{
			 if($result->RecordCount() > 0)
       {
			   $datos=$result->GetRowAssoc($toUpper=false);
		   }
       //$datos[]=$result->GetRowAssoc($toUpper=false);
       //$result->MoveNext();
       //}
		 }  
		return $datos;
  }
	
		/**
	* Funcion que retorna los tipo de gases anestesicos existentes en la base de datos
	* @return array
	*/
	function TiposDeAnestesias()
  {
		list($dbconn) = GetDBconn();
		$query = " SELECT qx_tipo_anestesia_id,
                      descripcion,
                      sw_uso_gases
		           FROM   qx_tipos_anestesia";
		
    $result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
    {
  		$this->error = "Error al Cargar el Modulo";
  		$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
  		return false;
		}
    else
    {
		if($result->RecordCount())
    {
  		while (!$result->EOF)
      {
  			$vars[]=$result->GetRowAssoc($toUpper=false);
  			$result->MoveNext();
		  }
		}
		}
		$result->Close();
		return $vars;
	}
	
	/**
	* Funcion que retorna los tipo de gases anestesicos existentes en la base de datos
	* @return array
	*/
	function TiposGasesAnestesicos()
  {
		list($dbconn) = GetDBconn();
		$query = " SELECT tipo_gas_id,descripcion
		           FROM   tipos_gases";
               
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) 
    {
  		$this->error = "Error al Cargar el Modulo";
  		$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
  		return false;
		}
    else
    {
	  if($result->RecordCount())
    {
			while(!$result->EOF)
      {
			$vars[$result->fields[0]]=$result->fields[1];
			$result->MoveNext();
			}
		}
		}
		$result->Close();
		return $vars;
	}
	
	/**
	* Funcion que retorna los tipo de gases anestesicos existentes en la base de datos
	* @return array
	*/
	function TiposMetodosSuministrosGases()
  {
		list($dbconn) = GetDBconn();
		$query = " SELECT  tipo_suministro_id,
                       descripcion
		           FROM    tipos_metodos_suministro_gases";
               
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) 
    {
  		$this->error = "Error al Cargar el Modulo";
  		$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
  		return false;
		}
    else
    {
  	if($result->RecordCount())
    {
  			while(!$result->EOF)
        {
  			$vars[$result->fields[0]]=$result->fields[1];
  			$result->MoveNext();
  			}
  	}
		}
		$result->Close();
		return $vars;
	}
		
	function ActualizarVariablesSession()
  {
		if(!$_REQUEST['sw_urg_prog']) $_REQUEST['sw_urg_prog'] = '0';
    
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['fechainicio']=$_REQUEST['fechainicio'.$this->frmPrefijo];
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['fechafinal']=$_REQUEST['fechafinal'.$this->frmPrefijo];
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['hora']=$_REQUEST['hora'.$this->frmPrefijo];
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['minutos']=$_REQUEST['minutos'.$this->frmPrefijo];
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['horafin']=$_REQUEST['horafin'.$this->frmPrefijo];
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['minutosfin']=$_REQUEST['minutosfin'.$this->frmPrefijo];
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
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['cirujano'] = $_REQUEST['cirujano'.$this->frmPrefijo];
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['tipoanestesia'] = $_REQUEST['tipoanestesia'.$this->frmPrefijo];
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['sw_urg_prog'] = $_REQUEST['sw_urg_prog'];
		
		if($_REQUEST['SelMaterialPat'.$this->frmPrefijo]){
			$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['SelMaterialPat']=1;
		}
    else
    {
			unset($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['SelMaterialPat']);
		}
		if($_REQUEST['SelCultivo'.$this->frmPrefijo])
    {
			$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['SelCultivo']=1;
		}
    else
    {
			unset($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['SelCultivo']);
		}					
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['MaterialPat']=$_REQUEST['MaterialPat'.$this->frmPrefijo];
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['Cultivo']=$_REQUEST['Cultivo'.$this->frmPrefijo];
	}
	
	function ProgramacionActivaPaciente($acto)
  {
		list($dbconn) = GetDBconn();
   
		$query=" SELECT  a.programacion_id  
		         FROM    qx_programaciones a
		                 LEFT JOIN hc_notas_operatorias_cirugias x ON (x.programacion_id=a.programacion_id AND x.usuario_id='".UserGetUID()."' $filtro1 ),
		                 qx_quirofanos_programacion b,
                     estacion_enfermeria_qx_pacientes_ingresados c
		         WHERE   a.tipo_id_paciente='".$this->tipoidpaciente."' 
             AND     a.paciente_id='".$this->paciente."' 
             AND     a.estado IN ('1','2') 
		         AND     a.programacion_id=b.programacion_id 
		         AND     b.qx_tipo_reserva_quirofano_id='3' 
		         AND     a.programacion_id=c.programacion_id 
             AND     c.sw_estado IN ('1','0')
             ;";
    
    if(!empty($acto))
    {
			$filtro1 .= "AND x.acto_quiru = ".$acto."" ;
		}
       
    
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
    {
			$this->error = "Error al Cargar el Modulo en la tabla qx_cumplimientos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {
			if($result->RecordCount() > 0)
      {
				return $result->fields[0];
			}
		}
		return 0;	
	}
	
    function DatosProfesionalesCirujano($programacion,$acto,$nota_operatoria)
    {
      $datosProfesionales = array();
      if($acto)
      {
        $query="SELECT  tipo_id_cirujano, 
                        cirujano_id,
                        sw_urg_prog
                FROM    hc_notas_operatorias_cirugias 
                WHERE   programacion_id = ".$programacion."
                AND     acto_quiru = ".$acto." ";
        if($nota_operatoria)
          $query .= "AND    hc_nota_operatoria_cirugia_id = ".$nota_operatoria." ";
              
        list($dbconn) = GetDBconn();
       
  			$result = $dbconn->Execute($query);
  			if ($dbconn->ErrorNo() != 0)
        {
  				$this->error = "Error al Cargar el Modulo en la tabla hc_notas_operatorias_cirugias";
  				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
  				return false;
  			}
       
  			if(!$result->EOF)
        {
          $datosProfesionales = $result->GetRowAssoc($toUpper=false);
  			}
      }				
      return $datosProfesionales;
    }
		
	function IdProfesionalesCirujano($programacion)
  {
		list($dbconn) = GetDBconn();
		$query="SELECT tipo_id_cirujano, 
                   cirujano_id,
                   sw_urg_prog
            FROM   hc_notas_operatorias_cirugias 
            WHERE  programacion_id = ".$programacion."";
            
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
      {
				$this->error = "Error al Cargar el Modulo en la tabla hc_notas_operatorias_cirugias";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
      else
      {
				if(!$result->EOF)
        {
					$datosP=$result->GetRowAssoc($toUpper=false);
				}
			}					
		return $datosP;
	}
	
  	function DatosProfesionalesCirugia($programacion,$acto,$nota_operatoria)
    {
      list($dbconn) = GetDBconn();
      
      $sql  = "SELECT A.tipo_id_tercero, ";
      $sql .= "       A.tercero_id, ";
      $sql .= "       B.tipo_id_ayudante, ";
      $sql .= "       B.ayudante_id, ";
      $sql .= "       C.tipo_id_instrumentista, ";
      $sql .= "       C.instrumentista_id, ";
      $sql .= "       D.tipo_id_circulante, ";
      $sql .= "       D.circulante_id ";
      $sql .= "FROM   ( ";
      $sql .= "          SELECT DISTINCT tipo_id_anestesiologo as tipo_id_tercero, ";
      $sql .= "                anestesiologo_id as tercero_id, ";
      $sql .= "                hc_nota_operatoria_cirugia_id ";
      $sql .= "         FROM   hc_notas_operatorias_cirugias  ";
      $sql .= "         WHERE  programacion_id='$programacion' ";
      $sql .= "         AND    acto_quiru = ".$acto." ";
      //$sql .= "         AND    anestesiologo_id IS NOT NULL ";
      if($nota_operatoria)
        $sql .= "         AND   hc_nota_operatoria_cirugia_id = ".$nota_operatoria." ";
      
      $sql .= "         ORDER BY hc_nota_operatoria_cirugia_id DESC ";
      $sql .= "       ) A,";
      $sql .= "       ( ";
      $sql .= "         SELECT DISTINCT tipo_id_ayudante, ";
      $sql .= "                ayudante_id, ";
      $sql .= "                hc_nota_operatoria_cirugia_id ";
      $sql .= "         FROM   hc_notas_operatorias_cirugias  ";
      $sql .= "         WHERE  programacion_id='$programacion' ";
      $sql .= "         AND    acto_quiru = ".$acto." ";
      //$sql .= "         AND    ayudante_id IS NOT NULL ";
      if($nota_operatoria)
        $sql .= "         AND   hc_nota_operatoria_cirugia_id = ".$nota_operatoria." ";

      $sql .= "         ORDER BY hc_nota_operatoria_cirugia_id DESC ";
      $sql .= "       ) B, "; 
      $sql .= "       (";
      $sql .= "         SELECT DISTINCT	tipo_id_instrumentista, ";
      $sql .= "                instrumentista_id, ";
      $sql .= "                hc_nota_operatoria_cirugia_id ";
      $sql .= "         FROM   hc_notas_operatorias_cirugias  ";
      $sql .= "         WHERE  programacion_id = '$programacion' ";
      $sql .= "         AND    acto_quiru = ".$acto." ";
      //$sql .= "         AND    instrumentista_id IS NOT NULL ";
      if($nota_operatoria)
        $sql .= "         AND   hc_nota_operatoria_cirugia_id = ".$nota_operatoria." ";

      $sql .= "         ORDER BY hc_nota_operatoria_cirugia_id DESC ";
      $sql .= "       ) C, ";
      $sql .= "       (";
      $sql .= "         SELECT DISTINCT	tipo_id_circulante, ";
      $sql .= "                circulante_id, ";
      $sql .= "                hc_nota_operatoria_cirugia_id ";
      $sql .= "         FROM   hc_notas_operatorias_cirugias  ";
      $sql .= "         WHERE  programacion_id='$programacion' ";
      $sql .= "         AND    acto_quiru = ".$acto." ";
      //$sql .= "         AND    circulante_id IS NOT NULL ";
      if($nota_operatoria)
        $sql .= "         AND   hc_nota_operatoria_cirugia_id = ".$nota_operatoria." ";

      $sql .= "         ORDER BY hc_nota_operatoria_cirugia_id DESC ";
      $sql .= "       ) D ";
    
      $result = $dbconn->Execute($sql);
      if ($dbconn->ErrorNo() != 0)
      {
        $this->error = "Error al Cargar el Modulo en la tabla qx_cumplimientos";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
      else
      {
        if(!$result->EOF)
        {
          $datosProfesionales=$result->GetRowAssoc($toUpper=false);
        }
        else
        {
          $query="SELECT a.tipo_id_tercero,a.tercero_id,
                         a.tipo_id_ayudante,a.ayudante_id,
                         a.tipo_id_instrumentista,a.instrumentista_id,
                         a.tipo_id_circulante,a.circulante_id
                  FROM   qx_anestesiologo_programacion a
                  WHERE  a.programacion_id='$programacion'";
          
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
            $this->error = "Error al Cargar el Modulo en la tabla qx_cumplimientos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }
          else
          {
            if(!$result->EOF)
            {
              $datosProfesionales=$result->GetRowAssoc($toUpper=false);
            }					
          }
				}
			}		
		return $datosProfesionales;
	}
	
	function DatosCirugia($programacion)
  {
   	list($dbconn) = GetDBconn();
 
		$query= " SELECT b.hora_inicio,
                     b.hora_fin,
                     g.descripcion as quirofano,
                     b.quirofano_id    
		         FROM    qx_quirofanos_programacion b,		
		                 qx_quirofanos g
		         WHERE   b.programacion_id='".$programacion."'  
             AND     b.qx_tipo_reserva_quirofano_id='3' 
             AND     b.quirofano_id=g.quirofano";
             
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
    {
			$this->error = "Error al Cargar el Modulo en la tabla qx_cumplimientos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {
			if(!$result->EOF)
      {
				$datos=$result->GetRowAssoc($toUpper=false);
			}
		}
		return $datos;
	}
	
	function BuscarProcedimientosCirugia($programacion)
  {
   	list($dbconn) = GetDBconn();
    
		$query = " SELECT a.procedimiento_qx,
                      b.descripcion as cargo, 
                      a.programado   
		           FROM   qx_procedimientos_programacion a,
                      cups b,
                      profesionales_usuarios c
		           WHERE  a.programacion_id='".$programacion."' 
               AND    a.procedimiento_qx=b.cargo 
               AND    c.tipo_tercero_id=a.tipo_id_cirujano 
               AND    c.tercero_id=a.cirujano_id";
               
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
    {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {
			while(!$resulta->EOF)
      {
				$vars[]=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
			}
		}
		$resulta->Close();
 		return $vars;
	}
		
/**
* Esta funcion inserta los datos del submodulo.
*
* @access private
* @return boolean Informa si lo logro o no.
*/
  function Consultarnota()
  {
    list($dbconn) = GetDBconn();
    $query = " SELECT 	hc_nota_operatoria_cirugia_id
               FROM     hc_notas_operatorias_procedimientos
               WHERE    hc_nota_operatoria_cirugia_id = '".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']."'";									
		
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
    {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }
    else
    {
      if($result->fields[0]>=1)
      {
        return 1;
      }
      else
      {
        return 0;
      }
    }
	}
  
  function InsertDatos()
	{ 
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['fechainicio']=$_REQUEST['fechainicio'.$this->frmPrefijo];
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['fechafinal']=$_REQUEST['fechafinal'.$this->frmPrefijo];
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['hora']=$_REQUEST['hora'.$this->frmPrefijo];
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['horafin']=$_REQUEST['horafin'.$this->frmPrefijo];
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['minutosfin']=$_REQUEST['minutosfin'.$this->frmPrefijo];
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
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['circulante'] = $_REQUEST['circulante'.$this->frmPrefijo];
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['cirujano'] = $_REQUEST['cirujano'.$this->frmPrefijo];
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['tipoanestesia'] = $_REQUEST['tipoanestesia'.$this->frmPrefijo];
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['sw_urg_prog'] = $_REQUEST['sw_urg_prog'];
		
    if(!SessionIsSetVar("ActoQuirurgicoNotaO"))
    {
      list($dbconn) = GetDBconn();
      
      $query="SELECT nextval('acto_quirurgico_acto_quiru_seq')";
			$result = $dbconn->Execute($query);
			$acto_qx = $result->fields[0];
       
      $query = " INSERT INTO acto_quirurgico
                  (
                    acto_quiru,
                    ingreso
                  )
                VALUES
                (
                   ".$acto_qx.",
                  '".$this->ingreso."'
                )";
      $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() != 0)
      {
        $this->error = "Error al Crear Acto quirurgico";
        $this->mensajeDeError = "Error DB Acto qx : " . $dbconn->ErrorMsg();
        return false;
      }
      SessionSetVar("ActoQuirurgicoNotaO",$acto_qx);
    }

    $acto_qx = SessionGetVar("ActoQuirurgicoNotaO");
		
    if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['cirujano'])
    {
			$datoscirujano = explode("/", $_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['cirujano']);	
			if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROGRAMACION'])
      {
				$cirujano = $this->DatosProfesionalesCirujano($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROGRAMACION'],$acto_qx,$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']);
				$idcirujano = $cirujano['cirujano_id'];
				$tipoid = $cirujano['tipo_id_cirujano'];
				if($datoscirujano[0] != $idcirujano || $datoscirujano[1] != trim($tipoid))
				{
					list($dbconn) = GetDBconn();
          
					 $sql= " UPDATE   hc_notas_operatorias_cirugias 
                   SET      tipo_id_cirujano = '".$datoscirujano[1]."', 
                            cirujano_id = '".$datoscirujano[0]."'
                   WHERE    programacion_id = ".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROGRAMACION']."
                   AND      acto_quiru = ".$acto_qx." ";
          if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID'])
            $sql .= "AND    hc_nota_operatoria_cirugia_id = ".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']." ";
					
          $resulta = $dbconn->Execute($sql);
					if($dbconn->ErrorNo() != 0)
          {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB11 : " . $dbconn->ErrorMsg();
						return false;
					}      		      
				}
			}
		}		
		if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['fechainicio'])
    {
		  $fechaInicio=ereg_replace("-","/",$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['fechainicio']);
      (list($dia,$mes,$ano)=explode('/',$fechaInicio));
      $fechaInicio=$ano.'-'.$mes.'-'.$dia.' '.$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['hora'].':'.$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['minutos'].':'.'00';
		}
		if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['fechafinal'])
    {
			$fechaFin=ereg_replace("-","/",$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['fechafinal']);
			(list($dia,$mes,$ano)=explode('/',$fechaFin));
			$fechaFin = $ano.'-'.$mes.'-'.$dia.' '.$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['horafin'].':'.$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['minutosfin'].':'.'00';
		}
	
		if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['quirofano']==-1)
    {
      $quirofano='NULL';
    }
		else
    {
      $quirofano="'".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['quirofano']."'";	
    }
		if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['viaAcceso']==-1)
    {
      $viaAcceso='NULL';	
    }
		else
    {
      $viaAcceso="'".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['viaAcceso']."'";
    }
		if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['tipoCirugia']==-1)
    {
      $tipoCirugia='NULL';
    }
		else
    {
      $tipoCirugia="'".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['tipoCirugia']."'";
    }
		if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['ambitoCirugia']==-1)
    {
      $ambitoCirugia='NULL';
    }
		else
    {
      $ambitoCirugia="'".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['ambitoCirugia']."'";
    }
		if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['finalidadCirugia']==-1)
    {
      $finalidadCirugia='NULL';
    }
		else
    {
      $finalidadCirugia="'".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['finalidadCirugia']."'";
    }
	
		if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['POST_QUIRURGICO'])
    {
			foreach($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['POST_QUIRURGICO'] as $codigo=>$vect)
      {
				foreach($vect as $tipo=>$diagnostico)
        {
					if($codigo)
          {
						$diagnostico_post_qx="'".$codigo."'";
						$tipo_post_qx=$tipo;
					}
          else
          {
						$diagnostico_post_qx='NULL';
						$tipo_post_qx='';
					}
				}
			}
		}
    else
    {
			$diagnostico_post_qx='NULL';
			$tipo_post_qx='';
		}
		if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['COMPLICACION'])
    {
			foreach($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['COMPLICACION'] as $codigo=>$vect)
      {
				foreach($vect as $tipo=>$diagnostico)
        {
					if($codigo)
          {
						$diagnostico_complicacion="'".$codigo."'";
						$tipo_complicacion=$tipo;
					}
          else
          {
						$diagnostico_complicacion='NULL';
						$tipo_complicacion='';
					}
				}
			}
		}
    else
    {
			$diagnostico_complicacion='NULL';
			$tipo_complicacion='';
		}		
		list($dbconn) = GetDBconn();
   
		$dbconn->BeginTrans();
    
    
		if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['anestesiologo']!=-1 && !empty($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['anestesiologo']))
    {
     
      (list($AnestesiologoId,$tipoIdAnestesiologo)=explode('/',$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['anestesiologo']));
			$tipoIdAnestesiologo="'".$tipoIdAnestesiologo."'";
			$AnestesiologoId="'".$AnestesiologoId."'";
		}
    else
    {
			$tipoIdAnestesiologo='NULL';
			$AnestesiologoId='NULL';
		}
		if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['ayudante']!=-1 && !empty($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['ayudante']))
    {
			(list($AyudanteId,$tipoIdAyudante)=explode('/',$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['ayudante']));
			$tipoIdAyudante="'".$tipoIdAyudante."'";
			$AyudanteId="'".$AyudanteId."'";
		}
    else
    {
			$tipoIdAyudante='NULL';
			$AyudanteId='NULL';
		}
		if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['instrumentador']!=-1 && !empty($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['instrumentador']))
    {
			(list($Instrumentador,$tipoIdInstrumentador)=explode('/',$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['instrumentador']));
			$tipoIdInstrumentador="'".$tipoIdInstrumentador."'";
			$Instrumentador="'".$Instrumentador."'";
		}
    else
    {
			$tipoIdInstrumentador='NULL';
			$Instrumentador='NULL';
		}
		if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['circulante']!=-1 && !empty($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['circulante']))
    {
			(list($Circulante,$tipoIdCirculante)=explode('/',$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['circulante']));
			$tipoIdCirculante="'".$tipoIdCirculante."'";
			$Circulante="'".$Circulante."'";
		}
    else
    {
			$tipoIdCirculante='NULL';
			$Circulante='NULL';
		}
		if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['tipoanestesia']!=-1 && !empty($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['tipoanestesia']))
    {
			(list($TipoAnestesia)=explode('/',$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['tipoanestesia']));
			$TipoAnestesia="'".$TipoAnestesia."'";
		}
    else
    {
			$TipoAnestesia='NULL';
		}
		if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['SelMaterialPat']==1 || !empty($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['MaterialPat']))
    {
			$matPat=1;
		}
    else
    {
			$matPat=0;			
		}
		if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['SelCultivo']==1 || !empty($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['Cultivo']))
    {
			$cultivo=1;
		}
    else
    {
			$cultivo=0;
		}
    /*$perfil = $this->PerfilProf();
    if($perfil!='3' OR $perfil!='4'OR $perfil!='8')
    {
      $datos = $this->DatosProfesionalesT($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROGRAMACION'],$acto_qx); 
      $tipoIdAnestesiologo = $datos['tipo_id_anestesiologo'];
      $AnestesiologoId = $datos['anestesiologo_id'];
      $tipoIdInstrumentador = $datos['tipo_id_instrumentista'];
      $Instrumentador = $datos['instrumentista_id'];
      $tipoIdCirculante = $datos['tipo_id_circulante'];
      $Circulante = $datos['circulante_id'];
      $tipoIdAyudante = $datos['tipo_id_ayudante'];
      $AyudanteId = $datos['ayudante_id'];
    }*/
   
		if(empty($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']) || SessionGetVar("NotaOperatoriaNueva") == '1')
    {							
		  $query = "SELECT nextval('hc_notas_operatorias_cirugias_hc_nota_operatoria_cirugia_id_seq')";
			$result = $dbconn->Execute($query);
			$notaId = $result->fields[0];
			$_SESSION['NotaID']=$notaId;
			$cirujanoD=explode('/',$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['cirujano']);
    
       $query="INSERT INTO hc_notas_operatorias_cirugias
                      (
                        hc_nota_operatoria_cirugia_id,
                        quirofano_id,
                        hora_inicio,
                        hora_fin,
                        usuario_id,
                        fecha_registro,
                        via_acceso,
                        tipo_cirugia,
                        ambito_cirugia,
                        finalidad_procedimiento_id,
                        evolucion_id,
                        justificacion_norealizados,
                        programacion_id,
                        diagnostico_post_qx,
                        tipo_diagnostico_post_qx,
                        diagnostico_id_complicacion,
                        tipo_diagnostico_complicacion,
                        tipo_id_anestesiologo,
                        anestesiologo_id,
                        tipo_id_instrumentista,
                        instrumentista_id,
                        tipo_id_circulante,
                        circulante_id,
                        tipo_id_ayudante,
                        ayudante_id,
                        envio_patologico,
                        descripcion_envio_patologico,
                        envio_cultivo,
                        descripcion_envio_cultivo,
                        tipo_id_cirujano,
                        cirujano_id,
                        qx_tipo_anestesia_id,
                        sw_urg_prog,
                        acto_quiru)
              VALUES(  '$notaId',		
                        $quirofano,
                       '$fechaInicio',
                       '$fechaFin',
                       '".UserGetUID()."',
                       '".date("Y-m-d H:i:s")."',
                       $viaAcceso,
                       $tipoCirugia,
                       $ambitoCirugia,
                       $finalidadCirugia,
                       '".$this->evolucion."',
                       '".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['justificacion']."',
                       '".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROGRAMACION']."',
                       $diagnostico_post_qx,
                      '$tipo_post_qx',$diagnostico_complicacion,
                      '$tipo_complicacion',
                       $tipoIdAnestesiologo,
                       $AnestesiologoId,
                       $tipoIdInstrumentador,
                       $Instrumentador,
                       $tipoIdCirculante,
                       $Circulante,
                       $tipoIdAyudante,
                       $AyudanteId,
                      '$matPat',
                      '".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['MaterialPat']."',
                      '$cultivo',
                      '".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['Cultivo']."',
                      '".$cirujanoD[1]."','".$cirujanoD[0]."', 
                      $TipoAnestesia,
                      '".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['sw_urg_prog']."',
                      ".$acto_qx."
                      )";
			//$TipoAnestesia,
      //qx_tipo_anestesia_id,
			$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']=$notaId;
      SessionSetVar("NotaOperatoriaNueva","2");
    }
    else
    {
  		$query=" UPDATE hc_notas_operatorias_cirugias 
  			       SET    quirofano_id=$quirofano,
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
                      descripcion_envio_cultivo='".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['Cultivo']."',
                      qx_tipo_anestesia_id = $TipoAnestesia,
                      sw_urg_prog = '".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['sw_urg_prog']."'
              WHERE   hc_nota_operatoria_cirugia_id='".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']."'
              AND     acto_quiru = ".$acto_qx." ";
			$notaId=$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID'];
			//diagnostico_pre_qx=$diagnostico_pre_qx,
			//tipo_diagnostico_pre_qx='$tipo_pre_qx',
    }
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
    {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
      
		}
    else
    { 	
			if(!empty($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']))
      {
        $query=" DELETE 
                   FROM   hc_notas_operatorias_procedimientos_opciones 
                   WHERE  hc_nota_operatoria_cirugia_id='".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']."'
                   AND    acto_quiru = ".$acto_qx." "; 
        
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          $dbconn->RollbackTrans();
          return false;
        }
        else
        {
          $query="DELETE 
                FROM   hc_notas_operatorias_procedimientos 
                WHERE  hc_nota_operatoria_cirugia_id='".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']."'
                AND    acto_quiru = ".$acto_qx." ";
                   
          $resulta = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0)
          {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
          }
				}	
			} 
            
			foreach($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['1'] as $codigo=>$procedimiento)
      {
        $query="SELECT nextval('hc_notas_operatorias_procedimientos_operatoria_id_seq')";
        $result = $dbconn->Execute($query);
        $oper_id=$result->fields[0];
			 
				$query= " INSERT INTO hc_notas_operatorias_procedimientos
                        (
                              hc_nota_operatoria_cirugia_id,
                              procedimiento_qx,
                              realizado,
                              observaciones,
                              operatoria_id,
                              acto_quiru
                        )
                  VALUES
                        (
                            '$notaId',
                            '$codigo',
                            '1',
                            '".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['OBSERVACIONES'][$codigo]."',
                            $oper_id,
                            ".$acto_qx."
                        );";
        
				$resulta = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0)
        {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
        else
        {
				//Para angiografia
					if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS_OPCIONES'][$codigo])
          {          
						foreach($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS_OPCIONES'][$codigo] as $pro=>$des)
            {
							
							$query=" INSERT INTO hc_notas_operatorias_procedimientos_opciones(
                                   hc_nota_operatoria_cirugia_id,
                                   procedimiento_qx,
                                   procedimiento_opcion,
                                   operatoria_id,
                                   acto_quiru
                            )
                      VALUES('$notaId','$codigo','$pro',$oper_id,".$acto_qx.")";              
							$resulta = $dbconn->Execute($query);
							if($dbconn->ErrorNo() != 0)
              {
								$this->error = "Error al Cargar el Modulo";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
							}
						}
					}
		//fin angio
					foreach($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['DIAGNOSTICOS'][$codigo] as $codigoDiagnostico=>$vectorDiag)
          {
            foreach($vectorDiag as $tipoDiagnostico=>$nombreDiag)
            {
              if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['DIAGNOSTICO_PRINCIPAL'][$codigo]==$codigoDiagnostico)
              {
                $principal='1';
						  }
              else
              {
							$principal='0';
						  }
						$query=" INSERT INTO hc_notas_operatorias_procedimientos_diags
                      (
                        hc_nota_operatoria_cirugia_id,
                        procedimiento_qx,
                        diagnostico_id,
                        sw_principal,
                        tipo_diagnostico,
                        acto_quiru
                      )
                      VALUES
                      (
                        '$notaId',
                        '$codigo',
                        '$codigoDiagnostico',
                        '$principal',
                        '$tipoDiagnostico',
                        ".$acto_qx."
                      )";
								
						$resulta = $dbconn->Execute($query);
						if($dbconn->ErrorNo() != 0)
            {
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
						}
					  }
					}		
				}
			}
			 
			foreach($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['3'] as $codigo=>$procedimiento)
      {	
				$query=" INSERT INTO hc_notas_operatorias_procedimientos(
                             hc_nota_operatoria_cirugia_id,
                             procedimiento_qx,realizado,
                             observaciones,
                             acto_quiru)
				         VALUES     ('$notaId',
                             '$codigo',
                             '1',
                             '".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['OBSERVACIONES'][$codigo]."',
                             ".$acto_qx."
                            );";
				
				$resulta = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0)
        {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
        else
        {
          //Para angiografia
          if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS_OPCIONES'][$codigo])
          {          
            foreach($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS_OPCIONES'][$codigo] as $pro=>$des)
            {
                $query=" INSERT INTO hc_notas_operatorias_procedimientos_opciones(
                                     hc_nota_operatoria_cirugia_id,
                                     procedimiento_qx,
                                     procedimiento_opcion,
                                     acto_quiru
                                     )
                         VALUES     ('$notaId',
                                      '$codigo',
                                      '$pro',
                                      ".$acto_qx."
                                    )"; 
                                      
              $resulta = $dbconn->Execute($query);
              if($dbconn->ErrorNo() != 0)
              {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
              }
            }
          }
          //fin angio 					
					foreach($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['DIAGNOSTICOS'][$codigo] as $codigoDiagnostico=>$vectorDiag)
          {
						foreach($vectorDiag as $tipoDiagnostico=>$nombreDiag)
            {
							if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['DIAGNOSTICO_PRINCIPAL'][$codigo]==$codigoDiagnostico)
              {
								$principal='1';
							}
              else
              {
								$principal='0';
							}
							$query=" INSERT INTO hc_notas_operatorias_procedimientos_diags(
                                   hc_nota_operatoria_cirugia_id,
                                   procedimiento_qx,
                                   diagnostico_id,
                                   sw_principal,
                                   tipo_diagnostico,
                                   acto_quiru)
                            VALUES('$notaId',
                                   '$codigo',
                                   '$codigoDiagnostico',
                                   '$principal',
                                   '$tipoDiagnostico',
                                   ".$acto_qx."
                                  )";
							
							$resulta = $dbconn->Execute($query);
							if($dbconn->ErrorNo() != 0)
              {
								$this->error = "Error al Cargar el Modulo";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
							}
						}
					}	
				}
			}
			$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['3']='';
			      
			if(!empty($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['9']))
      {
        for($i=0;$i<sizeof($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['9']);$i++)
        {	
        
  				$query=" INSERT INTO hc_notas_operatorias_procedimientos(
                               hc_nota_operatoria_cirugia_id,
                               procedimiento_qx,
                               realizado,
                               observaciones,
                               acto_quiru)
  				          VALUES    (
                               '$notaId',
                               '".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['9'][$i]['procedimiento_qx']."',
                               '1',
                               '".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['OBSERVACIONES'][$codigo]."',
                               ".$acto_qx."
                               );";
				
				$resulta = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0)
        {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
        else
        { 
          //Para angiografia
          if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS_OPCIONES'][$codigo])
          {          
            foreach($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS_OPCIONES'][$codigo] as $pro=>$des)
            {
      	      $query=" INSERT INTO hc_notas_operatorias_procedimientos_opciones(
                                   hc_nota_operatoria_cirugia_id,
                                   procedimiento_qx,
                                   procedimiento_opcion,
                                   acto_quiru
                                   )
                            VALUES('$notaId',
                                   '$codigo',
                                   '$pro',
                                   ".$acto_qx."
                                  )";   
                                   
              $resulta = $dbconn->Execute($query);
              if($dbconn->ErrorNo() != 0)
              {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
              }
            }
          }
          //fin angio					
					foreach($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['DIAGNOSTICOS'][$codigo] as $codigoDiagnostico=>$vectorDiag)
          {
						foreach($vectorDiag as $tipoDiagnostico=>$nombreDiag)
            {
							if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['DIAGNOSTICO_PRINCIPAL'][$codigo]==$codigoDiagnostico)
              {
								$principal='1';
							}
              else
              {
								$principal='0';
							}
							$query=" INSERT INTO hc_notas_operatorias_procedimientos_diags(
                                   hc_nota_operatoria_cirugia_id,
                                   procedimiento_qx,
                                   diagnostico_id,
                                   sw_principal,
                                   tipo_diagnostico,
                                   acto_quiru
                                   )
                           VALUES ('$notaId',
                                   '$codigo',
                                   '$codigoDiagnostico',
                                   '$principal',
                                   '$tipoDiagnostico',
                                   ".$acto_qx."
                                   )";
							
							$resulta = $dbconn->Execute($query);
							if($dbconn->ErrorNo() != 0)
              {
								$this->error = "Error al Cargar el Modulo";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
							}
						}
					}	
				}
			}
      }
			$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['9']='';
			foreach($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['2'] as $codigo=>$procedimiento)
      {
      
				$query=" INSERT INTO hc_notas_operatorias_procedimientos(
                             hc_nota_operatoria_cirugia_id,
                             procedimiento_qx,
                             realizado,
                             observaciones,
                             acto_quiru)
                       VALUES('$notaId',
                              '$codigo',
                              '0',
                              '".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['OBSERVACIONES'][$codigo]."',
                              ".$acto_qx."
                             );";
				
				$resulta = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0)
        {
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
	
	function Busqueda_Avanzada_Diagnosticos($codigo,$diagnostico)
  {	
		$pfj=$this->frmPrefijo;
		$FechaInicio = $this->datosPaciente[fecha_nacimiento];
		$FechaFin = date("Y-m-d");
		$edad_paciente = CalcularEdad($FechaInicio,$FechaFin);
		list($dbconn) = GetDBconn();
    $codigo = STRTOUPPER ($codigo);
		$diagnostico  =STRTOUPPER($diagnostico);

		$busqueda1 = '';
		$busqueda2 = '';

		if ($codigo != '')
    {
			$busqueda1 =" WHERE diagnostico_id LIKE '$codigo%'";
		}
		if (($diagnostico != '') AND ($codigo != ''))
    {
    	if (eregi('%',$diagnostico))
      {
				$busqueda2 ="AND diagnostico_nombre LIKE '$diagnostico'";      
      }
      else
      {
      	$busqueda2 ="AND diagnostico_nombre LIKE '%$diagnostico%'";
      }
		}

		if (($diagnostico != '') AND ($codigo == ''))
    {
			if(eregi('%',$diagnostico))
      {
					$busqueda2 ="WHERE diagnostico_nombre LIKE '$diagnostico'";
			}
      else
      {
					$busqueda2 ="WHERE diagnostico_nombre LIKE '%$diagnostico%'";
			}
		}
		//filtro por clasificacion de diagnosticos
		$filtro='';
		if(empty($busqueda1) AND empty($busqueda2))
    {
			$filtro = " WHERE (sexo_id='".$this->datosPaciente['sexo_id']."' OR sexo_id is null)
                  AND   (edad_max>=".$edad_paciente[edad_en_dias]." OR edad_max is null)
                  AND   (edad_min<=".$edad_paciente[edad_en_dias]." OR edad_min is null)";
		}
    else
    {
			$filtro = " AND (sexo_id='".$this->datosPaciente['sexo_id']."' OR sexo_id is null)
                  AND (edad_max>=".$edad_paciente[edad_en_dias]." OR edad_max is null)
                  AND (edad_min<=".$edad_paciente[edad_en_dias]." OR edad_min is null)";
		}
		$filtro1='';
		if(!empty($this->capitulo))
    {
			$filtro1 = " AND (B.capitulo='".$this->capitulo."' OR B.capitulo is null)";
		}
		if(!empty($this->grupo))
    {
			$filtro1 .= " AND (B.grupo='".$this->grupo."' OR B.grupo is null)";
		}
		if(!empty($this->categoria))
    {
			$filtro1 .= " AND (B.categoria='".$this->categoria."' OR B.categoria is null)";
		}

		$query = " SELECT  diagnostico_id, 
                       diagnostico_nombre
               FROM    diagnosticos
                       $busqueda1 $busqueda2
                       $filtro $filtro1";									
		
		list($dbconn) = GetDBconn();
		if(empty($_REQUEST['conteo']))
    {
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0)
      {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$this->conteo=$result->RecordCount();
		}
    else
    {
			$this->conteo=$_REQUEST['conteo'];
		}
		
		$query.=" ORDER BY diagnostico_id";
		
		$query.=" LIMIT " . $this->limit . " OFFSET ".$this->offset."";
		
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
    {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {
			while(!$result->EOF)
      {
				$vars[]=$result->GetRowAssoc($toUpper=false);
				$result->MoveNext();
			}
		}    
   	if($this->conteo==='0')
    {
		  $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
			return false;
		}
		$result->Close();			
		return $vars;	
	}

	function DatosCumplimiento()
  {
    list($dbconn) = GetDBconn();
		$query=" SELECT a.tipo_id_cirujano,
                    a.cirujano_id,
                    b.nombre as nombrecirujano,
                    a.diagnostico_id,
                    c.diagnostico_nombre,
                    a.programacion_id,
		                d.quirofano_id,
                    e.descripcion as quirofano,
                    d.hora_inicio,
                    d.hora_fin,
                    f.via_acceso,
                    g.descripcion as via,
                    f.tipo_cirugia,
                    h.descripcion as tipocirugia,
                    f.ambito_cirugia,
                    i.descripcion as ambitocirugia
              FROM  qx_cumplimientos a
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
		if ($dbconn->ErrorNo() != 0)
    {
			$this->error = "Error al Cargar el Modulo en la tabla qx_cumplimientos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {
			if(!$result->EOF)
      {
				$datosCumplimiento=$result->GetRowAssoc($toUpper=false);
			}
		}
		return $datosCumplimiento;
	}

	function DatosProcedimientosCumplimiento()
  {
    list($dbconn) = GetDBconn();
		$query=" SELECT DISTINCT a.procedimiento_qx,
                             c.descripcion,
                             a.via_procedimiento_bilateral,
                             d.descripcion as via
             FROM            qx_cumplimiento_procedimientos a
		         LEFT JOIN       qx_vias_acceso d ON(a.via_procedimiento_bilateral=d.via_acceso),
                             profesionales_usuarios b,
                             cups c
             WHERE           a.qx_cumplimiento_id='".$this->QXcumplimiento."' 
             AND             b.usuario_id='".UserGetUID()."' 
             AND             b.tipo_tercero_id=a.tipo_id_cirujano 
             AND             b.tercero_id=a.cirujano_id 
             AND             a.procedimiento_qx=c.cargo";
		
    $result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
    {
			$this->error = "Error al Cargar el Modulo en la tabla qx_cumplimientos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {
			if(!$result->EOF)
      {
			  while (!$result->EOF) 
        {
				  $datosProcedimientos[]=$result->GetRowAssoc($toUpper=false);
				  $result->MoveNext();
			  }
			}
		}
		return $datosProcedimientos;
	}

	function DatosAyundantesCumplimiento()
  {
    list($dbconn) = GetDBconn();
		
		$query=" SELECT DISTINCT a.tipo_id_ayudante,
                             a.ayudante_id,
                             c.nombre
             FROM            qx_cumplimiento_procedimientos a
             LEFT JOIN profesionales c ON(c.tipo_id_tercero=a.tipo_id_ayudante 
                             AND c.tercero_id=a.ayudante_id),
                             profesionales_usuarios b
		         WHERE           a.qx_cumplimiento_id='".$this->QXcumplimiento."' 
             AND             b.usuario_id='".UserGetUID()."' 
             AND             b.tipo_tercero_id=a.tipo_id_cirujano 
             AND             b.tercero_id=a.cirujano_id";
             
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
    {
			$this->error = "Error al Cargar el Modulo en la tabla qx_cumplimientos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {
			if(!$result->EOF)
      {
			  while (!$result->EOF) 
        {
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
	function TotalQuirofanos()
  {
		list($dbconn) = GetDBconn();
		$query = "SELECT quirofano,
                     descripcion 
              FROM   qx_quirofanos";
              
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) 
    {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {
		  $datos=$result->RecordCount();
			if($datos)
      {
			  while (!$result->EOF) 
        {
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
	function ViaAccesosCirugia()
  {
		list($dbconn) = GetDBconn();
		$query = "SELECT via_acceso,
                     descripcion 
                FROM qx_vias_acceso";
		
    $result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) 
    {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {
			if($result->EOF)
      {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'diagnosticos' esta vacia ";
				return false;
			}
			while (!$result->EOF) 
      {
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
	function TiposdeCirugia()
  {
  
    list($dbconn) = GetDBconn();
		$query = "SELECT tipo_cirugia_id,
                     descripcion 
                FROM qx_tipos_cirugia";
                
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) 
    {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {
			if($result->EOF)
      {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'vias_acceso_cx' esta vacia ";
				return false;
			}
			while (!$result->EOF) 
      {
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
	function TiposdeAmbitosdeCirugia()
  {
    list($dbconn) = GetDBconn();
		$query = "SELECT ambito_cirugia_id,
                     descripcion 
                FROM qx_ambitos_cirugias";
                
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) 
    {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {
			if($result->EOF)
      {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'qx_ambitos_cirugias' esta vacia ";
				return false;
			}
			while (!$result->EOF) 
      {
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
	function TiposfinalidadesCirugia()
  {
    list($dbconn) = GetDBconn();
		$query = "SELECT finalidad_procedimiento_id,
                     descripcion 
                FROM qx_finalidades_procedimientos";
                
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) 
    {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {
			if($result->EOF)
      {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'qx_ambitos_cirugias' esta vacia ";
				return false;
			}
			while (!$result->EOF) 
      {
				$vars[$result->fields[0]]=$result->fields[1];
				$result->MoveNext();
			}
		}
		$result->Close();
 		return $vars;
	}

  function BusquedaDiagnostico()
  {
	  $this->BuscadorDiagnostico($_REQUEST['codigoDes'.$this->frmPrefijo],$_REQUEST['descripcionDes'.$this->frmPrefijo]);
		return true;
	}

	function RegistrosDiagnosticos($codigo,$descripcion)
  {
    $pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		if($codigo)
    {
      $concat=" WHERE diagnostico_id LIKE '%$codigo%'";
			$s=1;
		}
		if($descripcion)
    {
      if($s==1)
      {
        $concat.=" AND diagnostico_nombre LIKE '%".strtoupper($descripcion)."%'";
			}
      else
      {
        $concat=" WHERE diagnostico_nombre LIKE '%".strtoupper($descripcion)."%'";
			}
		}
    if(empty($_REQUEST['conteo'.$pfj]))
    {
			$query = "SELECT count(*) 
                FROM   diagnosticos $concat";
                
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
      {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			list($this->conteo)=$resulta->fetchRow();
		}
    else
    {
			$this->conteo=$_REQUEST['conteo'.$pfj];
		}
		if(!$_REQUEST['Of'.$pfj])
    {
			$Of='0';
		}
    else
    {
			$Of=$_REQUEST['Of'.$pfj];
      if($Of > $this->conteo)
      {
				$Of=0;
				$_REQUEST['Of'.$pfj]=0;
				$_REQUEST['paso1'.$pfj]=1;
			}
		}
	  
	  $query = "SELECT diagnostico_id,
                     diagnostico_nombre 
              FROM   diagnosticos $concat
              LIMIT " . $this->limit . " OFFSET $Of";
              
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
    {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {
			while(!$resulta->EOF)
      {
				$var[]=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
			}
		}
   	if($this->conteo==='0')
    {
		  $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
			return false;
		}
		$resulta->Close();
		return $var;
	}

	function GetImprimir($y)
  {
		$imprimir=$this->frmImprimir($y);
		if($imprimir[0]==false)
		{
			return true;
		}
		return $imprimir;
	}

/**
* Funcion que consulta de la base de tado los tipos de cargos agrupados
* @return array
*/
	function tiposdeProcedimientos()
  {
    list($dbconn) = GetDBconn();
		$query=" SELECT a.tipo_cargo,
                    a.grupo_tipo_cargo,
                    a.descripcion
               FROM tipos_cargos a,
                    qx_grupos_tipo_cargo b
		          WHERE a.grupo_tipo_cargo=b.grupo_tipo_cargo";
              
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) 
    {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {
		  $datos=$result->RecordCount();
			if($datos)
      {
        while(!$result->EOF)
        {
          $vars[]=$result->GetRowAssoc($toUpper=false);
          $result->MoveNext();
        }
			}
		}
		$result->Close();
 		return $vars;
	}

	function RegistrosCargosCups($tipoProcedimiento,$cargo,$descripcion)
  {
		$query=" SELECT a.cargo,
                    a.descripcion,
                    c.descripcion as tipo 
		         FROM   cups a,
                    qx_grupos_tipo_cargo b,
                    tipos_cargos c
		         WHERE  a.grupo_tipo_cargo=b.grupo_tipo_cargo 
             AND    b.grupo_tipo_cargo=c.grupo_tipo_cargo 
             AND    a.tipo_cargo=c.tipo_cargo";   
             
		if(!empty($tipoProcedimiento) && $tipoProcedimiento!=-1)
    {
			(list($tipo,$grupo)=explode('/',$tipoProcedimiento));
			$query.=" AND a.grupo_tipo_cargo='$grupo' AND a.tipo_cargo='$tipo'";			
		}
		if(!empty($cargo))
    {        
    	$query.=" AND a.cargo='$cargo'";				
		}
    if(!empty($descripcion))
    {
			$descripcion=strtoupper($descripcion);
      $query.=" AND a.descripcion LIKE '%$descripcion%'";			
		}		
		$pfj=$this->frmPrefijo;		
		list($dbconn) = GetDBconn();
		
		if(empty($_REQUEST['conteo']))
    {
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0)
      {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$this->conteo=$result->RecordCount();
		}
    else
    {
			$this->conteo=$_REQUEST['conteo'];
		}
		$query.=" ORDER BY a.descripcion";
		$query.=" LIMIT " . $this->limit . " OFFSET ".$this->offset."";
		
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
    {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {
			while(!$result->EOF)
      {
				$vars[]=$result->GetRowAssoc($toUpper=false);
				$result->MoveNext();
			}
		}    
    if($this->conteo==='0')
    {
		  $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
			return false;
		}
		$result->Close();
		return $vars;	
	}

	function GuardarDatosNotasOperatorias()
  {
		if(!$_REQUEST['fechainicio'.$this->frmPrefijo] || $_REQUEST['hora'.$this->frmPrefijo]==-1 || $_REQUEST['minutos'.$this->frmPrefijo]==-1 || $_REQUEST['horadur'.$this->frmPrefijo]==-1 || $_REQUEST['minutosdur'.$this->frmPrefijo]==-1)
    {
		  $this->frmError["MensajeError"]="La Fecha de Inicio y la Duracion son Datos Obligatorios";
		  $this->frmForma();
			return true;
		}
		if($_REQUEST['fechainicio'.$this->frmPrefijo])
    {
		  $fechaInicio=ereg_replace("-","/",$_REQUEST['fechainicio'.$this->frmPrefijo]);
      (list($dia,$mes,$ano)=explode('/',$fechaInicio));
      $fechaInicio=$ano.'-'.$mes.'-'.$dia.' '.$_REQUEST['hora'.$this->frmPrefijo].':'.$_REQUEST['minutos'.$this->frmPrefijo].':'.'00';
		}
		$fechaFin=date("Y-m-d H:i:s",mktime(($_REQUEST['hora'.$this->frmPrefijo]+$_REQUEST['horadur'.$this->frmPrefijo]),($_REQUEST['minutos'.$this->frmPrefijo]+$_REQUEST['minutosdur'.$this->frmPrefijo]),0,$mes,$dia,$ano));
		if($_REQUEST['quirofano'.$this->frmPrefijo]==-1)
    {
      $quirofano='NULL';	
    }
		else
    {
      $quirofano="'".$_REQUEST['quirofano'.$this->frmPrefijo]."'";	
    }
		if($_REQUEST['viaAcceso'.$this->frmPrefijo]==-1)
    {
      $viaAcceso='NULL';	
    }
		else
    {
      $viaAcceso="'".$_REQUEST['viaAcceso'.$this->frmPrefijo]."'";
    }
		if($_REQUEST['tipoCirugia'.$this->frmPrefijo]==-1)
    {
      $tipoCirugia='NULL';
    }
		else
    {
      $tipoCirugia="'".$_REQUEST['tipoCirugia'.$this->frmPrefijo]."'";
    }
		if($_REQUEST['ambitoCirugia'.$this->frmPrefijo]==-1)
    {
      $ambitoCirugia='NULL';
    }
		else
    {
      $ambitoCirugia="'".$_REQUEST['ambitoCirugia'.$this->frmPrefijo]."'";
    }
		if($_REQUEST['finalidadCirugia'.$this->frmPrefijo]==-1)
    {
      $finalidadCirugia='NULL';
    }
		else
    {
      $finalidadCirugia="'".$_REQUEST['finalidadCirugia'.$this->frmPrefijo]."'";
    }
		
		list($dbconn) = GetDBconn();
    
    $acto = SessionGetVar("ActoQuirurgicoNotaO");
		if(!$_SESSION['NotaOperatoria']['NotaId'])
    {
			$query="SELECT nextval('hc_notas_operatorias_cirugias_hc_nota_operatoria_cirugia_id_seq')";
			$result = $dbconn->Execute($query);
			$notaId=$result->fields[0];
      if (!empty($acto))
      {
  			$query="  INSERT INTO hc_notas_operatorias_cirugias
                             (hc_nota_operatoria_cirugia_id,
                              qx_cumplimiento_id,
                              quirofano_id,hora_inicio,
                              hora_fin,
                              usuario_id,
                              fecha_registro,
                              via_acceso,
                              tipo_cirugia,
                              ambito_cirugia,
                              finalidad_procedimiento_id,
                              evolucion_id,
                              sw_urg_prog,
                              acto_quiru)
                   VALUES   ( '$notaId',
                              '".$this->QXcumplimiento."',
                              $quirofano,
                              '$fechaInicio',
                              '$fechaFin',
                              '".UserGetUID()."',
                              '".date("Y-m-d H:i:s")."',
                              $viaAcceso,
                              $tipoCirugia,
                              $ambitoCirugia,
                              $finalidadCirugia,
                              '".$this->evolucion."',
                              '".$_REQUEST['sw_urg_prog']."',
                              $acto)";
                            
  			$resulta = $dbconn->Execute($query);
  			if($dbconn->ErrorNo() != 0)
        {
  				$this->error = "Error al Cargar el Modulo";
  				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
  				return false;
  			}
        else
        {
          $_SESSION['NotaOperatoria']['NotaId']=$notaId;
  			}
      }
      else
      {
        $query="SELECT nextval('acto_quirurgico_acto_quiru_seq')";
			  $result = $dbconn->Execute($query);
			  $acto_q=$result->fields[0];
			  $acto=$acto_q;
        $query = " INSERT INTO acto_quirurgico
                             (
                              acto_quiru,
                              ingreso
                             )
                   VALUES    (
                             $acto_q,
                             '".$this->ingreso."'
                             )";
         //$vars['acto_quiru']=$acto_q; 
        $resulta = $dbconn->Execute($query);
  			if($dbconn->ErrorNo() != 0)
        {
  				$this->error = "Error al Cargar el Modulo";
  				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
  				return false;
  			}
                
      }
		}
    else
    {
      if(!empty($acto))
      {
  		  $query=" UPDATE hc_notas_operatorias_cirugias
  			         SET    quirofano_id=$quirofano,
                        hora_inicio='".$fechaInicio."',
                        hora_fin='".$fechaFin."',
                        via_acceso=$viaAcceso,
  			                tipo_cirugia=$tipoCirugia,
                        ambito_cirugia=$ambitoCirugia,
                        finalidad_procedimiento_id=$finalidadCirugia,
                        sw_urg_prog ='".$_REQUEST['sw_urg_prog']."',
                        acto_quiru = $acto_q                        
  			         WHERE  hc_nota_operatoria_cirugia_id='".$_SESSION['NotaOperatoria']['NotaId']."'";
  			
        $resulta = $dbconn->Execute($query);
  			if($dbconn->ErrorNo() != 0)
        {
  				$this->error = "Error al Cargar el Modulo";
  				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
  				return false;
  			}
      }
      else
      {
       $query="SELECT nextval('acto_quirurgico_acto_quiru_seq')";
			  $result = $dbconn->Execute($query);
			  $acto_q=$result->fields[0];
			  $act=$acto_q;
        $query = " INSERT INTO acto_quirurgico
                             (
                              acto_quiru,
                              ingreso
                             )
                   VALUES    (
                             $acto_q,
                             '".$this->ingreso."'
                             )";
         //$vars['acto_quiru']=$acto_q; 
        $resulta = $dbconn->Execute($query);
  			if($dbconn->ErrorNo() != 0)
        {
  				$this->error = "Error al Cargar el Modulo";
  				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
  				return false;
  			} 
      }
		}
		$this->frmForma();
		return true;
	}

	function GuardarDiagnosticosNotasOperatorias()
  {
    if(!$_SESSION['NotaOperatoria']['NotaId'])
    {
		  $this->frmError["MensajeError"]="Inserte Primero los Datos Principales de la Nota";
			$this->frmForma();
			return true;
		}
		list($dbconn) = GetDBconn();
		if(!$_REQUEST['codigo'.$this->frmPrefijo])
    {
      $codigo='NULL';
    }
		else
    {
      $codigo="'".$_REQUEST['codigo'.$this->frmPrefijo]."'";
    }
		if(!$_REQUEST['codigo1'.$this->frmPrefijo])
    {
      $codigo1='NULL';
    }
		else
    {
      $codigo1="'".$_REQUEST['codigo1'.$this->frmPrefijo]."'";
    }
    
		$query = " SELECT  tipo_tercero_id,
                       tercero_id 
               FROM    profesionales_usuarios 
               WHERE   usuario_id='".UserGetUID()."'";
               
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
    {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {
			$var=$resulta->GetRowAssoc($ToUpper = false);
			$query="SELECT * 
              FROM   hc_notas_operatorias_cirujanos 
              WHERE  tipo_id_cirujano='".$var['tipo_tercero_id']."' 
              AND    cirujano_id='".$var['tercero_id']."'";
              
			$resulta = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0)
      {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
      else
      {
				$datos=$resulta->RecordCount();
				if($datos)
        {
					$query=" UPDATE hc_notas_operatorias_cirujanos
					         SET    diagnostico_id=$codigo,
                          complicacion_id=$codigo1
					         WHERE  hc_nota_operatoria_cirugia_id='".$_SESSION['NotaOperatoria']['NotaId']."' 
                   AND    tipo_id_cirujano='".$var['tipo_tercero_id']."' 
                   AND    cirujano_id='".$var['tercero_id']."'";
				}
        else
        {
					$query="INSERT INTO hc_notas_operatorias_cirujanos(
                              hc_nota_operatoria_cirugia_id,
                              tipo_id_cirujano,cirujano_id,
                              diagnostico_id,complicacion_id)
                   VALUES     ('".$_SESSION['NotaOperatoria']['NotaId']."',
                               '".$var['tipo_tercero_id']."',
                               '".$var['tercero_id']."',
                               $codigo,
                               $codigo1)";
				}
        
				$resulta = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0)
        {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
			}
		}
		$this->frmForma();
	}


	function DatosNotasOperatorias()
  {
    list($dbconn) = GetDBconn();
    $acto = SessionGetVar("ActoQuirurgicoNotaO");
    
    $query=" SELECT a.hora_inicio,
                    a.hora_fin,
                    a.quirofano_id,
                    a.via_acceso,
                    a.tipo_cirugia,
                    a.ambito_cirugia,
                    a.finalidad_procedimiento_id,
                    a.sw_urg_prog,
                    a.usuario_id
             FROM   hc_notas_operatorias_cirugias a
             WHERE  a.hc_nota_operatoria_cirugia_id='".$_SESSION['NotaOperatoria']['NotaId']."'
             AND    a.acto_quiru = ".$acto."
             AND    a.usuario_id = ".UserGetUID()."";
		
    $result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
    {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {
			$vars=$result->GetRowAssoc($ToUpper = false);
		}
		return $vars;
	}

	function DiagnosticosNotasOperatorias()
  {
    list($dbconn) = GetDBconn();
    $query=" SELECT a.diagnostico_id,
                    b.diagnostico_nombre,
                    a.complicacion_id,
                    c.diagnostico_nombre as complicacion
             FROM   hc_notas_operatorias_cirujanos a
             LEFT JOIN diagnosticos b ON(a.diagnostico_id=b.diagnostico_id)
             LEFT JOIN diagnosticos c ON(a.complicacion_id=c.diagnostico_id),
                    profesionales_usuarios d
             WHERE  a.hc_nota_operatoria_cirugia_id='".$_SESSION['NotaOperatoria']['NotaId']."' 
             AND    d.usuario_id='".UserGetUID()."' 
             AND    a.tipo_id_cirujano=d.tipo_tercero_id 
             AND    a.cirujano_id=d.tercero_id";
             
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
    {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {
			$vars=$result->GetRowAssoc($ToUpper = false);
		}
		return $vars;
	}

	function GuardarProcedimientoNota()
  {
    list($dbconn) = GetDBconn();
    $query = " SELECT a.tipo_id_ayudante,
                      a.ayudante_id,
                      a.procedimiento_qx,
                      b.descripcion
		           FROM   qx_cumplimiento_procedimientos a,
                      cups b
		           WHERE  a.qx_cumplimiento_id='".$this->QXcumplimiento."' 
               AND    a.procedimiento_qx='".$_REQUEST['procedimiento']."' 
               AND    a.procedimiento_qx=b.cargo";
               
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
    {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {
			$vars=$result->GetRowAssoc($ToUpper = false);
		}
		$_REQUEST['textnuevoProcedimiento'.$this->frmPrefijo]=$vars['descripcion'];
		$_REQUEST['nuevoProcedimiento'.$this->frmPrefijo]=$vars['procedimiento_qx'];
		$_REQUEST['ayudante'.$this->frmPrefijo]=$vars['ayudante_id'].'/'.$vars['tipo_id_ayudante'];
		$this->frmForma();
		return true;
	}

	function InsercionProcedimientosNotaBD()
  {
    list($dbconn) = GetDBconn();
		if(!$_SESSION['NotaOperatoria']['NotaId'])
    {
		  $this->frmError["MensajeError"]="Inserte Primero los Datos Principales de la Nota";
			$this->frmForma();
			return true;
		}
		
		$query = " SELECT  tipo_tercero_id,tercero_id 
               FROM    profesionales_usuarios 
               WHERE   usuario_id='".UserGetUID()."'";
               
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
    {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {
		  $var=$resulta->GetRowAssoc($ToUpper = false);
      $query = " SELECT  * 
                 FROM    hc_notas_operatorias_cirujanos 
                 WHERE   tipo_id_cirujano='".$var['tipo_tercero_id']."' 
                 AND     cirujano_id='".$var['tercero_id']."' 
                 AND     hc_nota_operatoria_cirugia_id='".$_SESSION['NotaOperatoria']['NotaId']."'";
			
      $resulta = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0)
      {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
      else
      {
				$datos=$resulta->RecordCount();
				if(!$_REQUEST['codigo'.$this->frmPrefijo])
        {
          $codigo='NULL';
        }
				else
        {
          $codigo="'".$_REQUEST['codigo'.$this->frmPrefijo]."'";
        }
				if(!$_REQUEST['codigo1'.$this->frmPrefijo])
        {
          $codigo1='NULL';
        }
				else
        {
          $codigo1="'".$_REQUEST['codigo1'.$this->frmPrefijo]."'";
        }
				if($datos)
        {
          $query=" UPDATE hc_notas_operatorias_cirujanos
      					   SET    diagnostico_id=$codigo,complicacion_id=$codigo1
      					   WHERE  hc_nota_operatoria_cirugia_id='".$_SESSION['NotaOperatoria']['NotaId']."' 
                   AND    tipo_id_cirujano='".$var['tipo_tercero_id']."' AND cirujano_id='".$var['tercero_id']."'";
				}
        else
        {
          $query=" INSERT INTO hc_notas_operatorias_cirujanos(
                               hc_nota_operatoria_cirugia_id,
                               tipo_id_cirujano,
                               cirujano_id,
                               diagnostico_id,
                               complicacion_id)
      					        VALUES('".$_SESSION['NotaOperatoria']['NotaId']."',
                               '".$var['tipo_tercero_id']."',
                               '".$var['tercero_id']."',
                               $codigo,
                               $codigo1)";
				}
        
				$resulta = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0)
        {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
			}
		}
		if($_REQUEST['nuevoProcedimiento'.$this->frmPrefijo] && $_REQUEST['textnuevoProcedimiento'.$this->frmPrefijo])
    {
			(list($ayudante,$tipoAyudante)=explode('/',$_REQUEST['ayudante'.$this->frmPrefijo]));
			$query="  INSERT INTO hc_notas_operatorias_procedimientos(
                            hc_nota_operatoria_cirugia_id,
                            tipo_id_cirujano,cirujano_id,
                            procedimiento_qx,
                            tipo_id_ayudante,
                            ayudante_id,
                            tecnica_quirurgica,
                            hallazgos_quirurgicos)
                      VALUES('".$_SESSION['NotaOperatoria']['NotaId']."',
                             '".$var['tipo_tercero_id']."',
                             '".$var['tercero_id']."',
                             '".$_REQUEST['nuevoProcedimiento'.$this->frmPrefijo]."',
                             '$tipoAyudante',
                             '$ayudante',
                             '".$_REQUEST['descripcionQuirugica'.$this->frmPrefijo]."',
                             '".$_REQUEST['hallazgos'.$this->frmPrefijo]."')";
                             
			$resulta = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0)
      {
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

	function EliminaProcedimientoNota($codigo = "")
  {
	  if($codigo=="")
    {
	  	$codigo = $_REQUEST['procedimiento'];
	  }
	  list($dbconn) = GetDBconn();
		$query="  DELETE
              FROM   hc_notas_operatorias_procedimientos
              WHERE  hc_nota_operatoria_cirugia_id='".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']."' 
              AND    procedimiento_qx='".$codigo."'";
		
    $resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
    {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$this->frmForma();
		return true;
	}
	
	function EliminarP($codigo)
  {
    unset($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['3'][$codigo]);
    return true;
	}

	function EliminarProcedimiento($codigo,$acto)
  {
    list($dbconn) = GetDBconn();
	  //$dbconn->debug=true;
    $query1 = "DELETE 
               FROM   hc_notas_operatorias_procedimientos_opciones 
               WHERE  hc_nota_operatoria_cirugia_id= '".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']."' 
               AND    procedimiento_qx= '".$codigo."' 
               AND    acto_quiru = ".$acto." ";
    $resulta1 = $dbconn->Execute($query1);
  	if($dbconn->ErrorNo() != 0)
    {
  		$this->error = "Error al Cargar el Modulo";
  		$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
  		$dbconn->RollbackTrans();
  		return false;
  	}
               
    $query = " DELETE 
               FROM   hc_notas_operatorias_procedimientos 
               WHERE  hc_nota_operatoria_cirugia_id= '".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']."' 
               AND    procedimiento_qx= '".$codigo."' 
               AND    acto_quiru = ".$acto." ";
				
  	$resulta = $dbconn->Execute($query);
  	if($dbconn->ErrorNo() != 0)
    {
  		$this->error = "Error al Cargar el Modulo";
  		$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
  		$dbconn->RollbackTrans();
  		return false;
  	}
  	return true;
	}
  
	function EliminarProcedimientoProgramado($codigo,$programacion)
  {
    list($dbconn) = GetDBconn();
    unset($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['1']);
	  $query = " DELETE 
               FROM   qx_procedimientos_programacion 
               WHERE  procedimiento_qx= '".$codigo."' 
               AND    programacion_id= '".$_SESSION['NOTAS_OPERATORIASfrm_NotasOperatoriasCOC']['PROGRAMACION']."'";
				
  	$resulta = $dbconn->Execute($query);
  	if($dbconn->ErrorNo() != 0)
    {
  		$this->error = "Error al Cargar el Modulo";
  		$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
  		$dbconn->RollbackTrans();
  		return false;
  	}
	return true;
	}
	
	function ConfirmarExisteCumplimiento()
  {
    list($dbconn) = GetDBconn();
    $query = " SELECT  hc_nota_operatoria_cirugia_id
		           FROM    hc_notas_operatorias_cirugias
		           WHERE   qx_cumplimiento_id='".$this->QXcumplimiento."'";
		
    $resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
    {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {
			$var=$resulta->GetRowAssoc($ToUpper = false);
			if($var['hc_nota_operatoria_cirugia_id'])
      {
        $_SESSION['NotaOperatoria']['NotaId']=$var['hc_nota_operatoria_cirugia_id'];
			}
			return true;
		}
	}


	function ConsultaAtencion()
	{
		list($dbconn) = GetDBconn();
		$query = " SELECT  detalle 
               FROM    hc_atencion,
                       hc_tipos_atencion 
               WHERE   hc_atencion.tipo_atencion_id = hc_tipos_atencion.tipo_atencion_id 
               AND     evolucion_id=".$this->evolucion.";";
               
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		return $result;
	}
	
	function ConsultarIdNota()
	{ 
    $acto = SessionGetVar("ActoQuirurgicoNotaO");
		$VAL=$this->ProgramacionActivaPaciente($acto);
		list($dbconn) = GetDBconn();
		 $query = " SELECT  hc_nota_operatoria_cirugia_id 
                FROM    hc_notas_operatorias_cirugias  
			          WHERE   programacion_id= ".$VAL." ;";
     //AND     acto_quiru = ".$acto."           
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		return $result;
	}
	
	function ConsultaNotasOperatoriasRealizadas($acto)
  {
    $acto = SessionGetVar("ActoQuirurgicoNotaO");
		list($dbconn) = GetDBconn();
    
		 $query = " SELECT a.hc_nota_operatoria_cirugia_id,
                      a.quirofano_id,
                      x.descripcion as nom_quirofano,
                      a.hora_inicio,
                      a.hora_fin,
		                  a.via_acceso,
                      b.descripcion as via,
                      a.tipo_cirugia,
                      c.descripcion as tipo,
                      a.ambito_cirugia,
                      d.descripcion as ambito,
                      a.finalidad_procedimiento_id,
                      e.descripcion as finalidad,
                      a.justificacion_norealizados,		
                      a.diagnostico_post_qx,
                      diag.diagnostico_nombre as diag_nom,
                      a.tipo_diagnostico_post_qx,
		                  a.diagnostico_id_complicacion,
                      diag1.diagnostico_nombre as diag_nom1,
                      a.tipo_diagnostico_complicacion,
                      a.envio_patologico,
                      a.descripcion_envio_patologico,
                      a.envio_cultivo,
                      a.descripcion_envio_cultivo,
                      a.	sw_urg_prog,
                      instru.nombre_tercero as instrumentador,
                      circu.nombre_tercero as circulante,
                      aneste.nombre_tercero as anestesiologo,
                      ayu.nombre_tercero as ayudante, 
                      tipo_anes.descripcion as tipo_anestesia
                FROM  hc_notas_operatorias_cirugias a
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
                LEFT JOIN terceros ayu ON (ayu.tipo_id_tercero=a.tipo_id_ayudante AND ayu.tercero_id=a.ayudante_id)
                LEFT JOIN qx_tipos_anestesia tipo_anes ON (tipo_anes.qx_tipo_anestesia_id=a.qx_tipo_anestesia_id),    
                          hc_evoluciones evol,ingresos ing
                WHERE  a.evolucion_id<>".$this->evolucion." 
                AND    a.usuario_id='".UserGetUID()."' 
                AND    ing.ingreso='".$this->ingreso."' 
                AND    a.evolucion_id=evol.evolucion_id 
                AND    evol.ingreso=ing.ingreso 
                AND    ing.tipo_id_paciente='".$this->tipoidpaciente."' 
                AND    ing.paciente_id='".$this->paciente."'
                AND    a.acto_quiru = ".$acto."
                ";
		
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
    {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {
			while(!$result->EOF)
      {
				$vars[]=$result->GetRowAssoc($toUpper=false);
				$result->MoveNext();
			}
		}			
		return $vars;	
	}
	
	function ConsultaNotasOperatoriasRealizadasHis()
  {
    
		list($dbconn) = GetDBconn();
		$query =" SELECT a.hc_nota_operatoria_cirugia_id,
                     a.quirofano_id,x.descripcion as nom_quirofano,
                     a.hora_inicio,
                     a.hora_fin,
		                 a.via_acceso,
                     b.descripcion as via,
                     a.tipo_cirugia,
                     c.descripcion as tipo,
		                 a.ambito_cirugia,
                     d.descripcion as ambito,
                     a.finalidad_procedimiento_id,
                     e.descripcion as finalidad,
		                 a.justificacion_norealizados,		
		                 a.diagnostico_post_qx,
                     diag.diagnostico_nombre as diag_nom,
                     a.tipo_diagnostico_post_qx,
		                 a.diagnostico_id_complicacion,
                     diag1.diagnostico_nombre as diag_nom1,
                     a.tipo_diagnostico_complicacion,
		                 a.envio_patologico,
                     a.descripcion_envio_patologico,
                     a.envio_cultivo,
                     a.descripcion_envio_cultivo,
                     instru.nombre_tercero as instrumentador,
                     circu.nombre_tercero as circulante,
                     aneste.nombre_tercero as anestesiologo,
                     ayu.nombre_tercero as ayudante, 
                     a.evolucion_id, a.programacion_id,
                     a.sw_urg_prog,
                     tipo_anes.descripcion as tipo_anestesia
           FROM      hc_notas_operatorias_cirugias a
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
           LEFT JOIN terceros ayu ON (ayu.tipo_id_tercero=a.tipo_id_ayudante AND ayu.tercero_id=a.ayudante_id)
	         LEFT JOIN qx_tipos_anestesia tipo_anes ON (tipo_anes.qx_tipo_anestesia_id=a.qx_tipo_anestesia_id),    
		                 hc_evoluciones evol,ingresos ing
		       WHERE     ing.ingreso='".$this->ingreso."' 
           AND       a.evolucion_id=evol.evolucion_id 
           AND       evol.ingreso=ing.ingreso 
           AND       ing.tipo_id_paciente='".$this->tipoidpaciente."' 
           AND       ing.paciente_id='".$this->paciente."'
           
           ";
		
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
    {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {
			while(!$result->EOF)
      {
				$vars[]=$result->GetRowAssoc($toUpper=false);
				$result->MoveNext();
			}
		}			
		return $vars;	
	}
	
	function ProcedimientosNotaOperatoria($NotaId)
  {
		list($dbconn) = GetDBconn();
    
    //
		$query = " SELECT  a.procedimiento_qx,
                       b.descripcion,
                       a.observaciones
		           FROM    hc_notas_operatorias_procedimientos a,
                       cups b
		           WHERE   a.hc_nota_operatoria_cirugia_id=".$NotaId." 
               AND     a.procedimiento_qx=b.cargo 
               AND     a.realizado='1' ";
               
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
    {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {
			while(!$result->EOF)
      {
				$vars[]=$result->GetRowAssoc($toUpper=false);
				$result->MoveNext();
			}
		}			
		return $vars;	
	}
	
	function Diagnosticos_ProcedimientosNO($NotaId,$procedimiento_qx)
  {
		list($dbconn) = GetDBconn();
		$query = " SELECT  a.diagnostico_id,
                       b.diagnostico_nombre,
                       a.tipo_diagnostico,
		                   a.sw_principal
		           FROM    hc_notas_operatorias_procedimientos_diags a,
                       diagnosticos b
		           WHERE   a.hc_nota_operatoria_cirugia_id=".$NotaId." 
               AND     a.procedimiento_qx=".$procedimiento_qx." 
               AND     a.diagnostico_id=b.diagnostico_id";
               
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
    {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {
			while(!$result->EOF)
      {
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
	function profesionalesEspecialistaAnestecistas()
  {  
		list($dbconn) = GetDBconn();
    $query = " SELECT  x.tercero_id,
                       c.nombre_tercero as nombre,
                       x.tipo_id_tercero
               FROM    profesionales x,
                       profesionales_departamentos y,
                       especialidades z,
                       profesionales_especialidades l,
                       terceros c
               WHERE  (x.tipo_profesional='1' OR x.tipo_profesional='2') 
               AND     x.tipo_id_tercero=y.tipo_id_tercero 
               AND     x.tercero_id=y.tercero_id 
               AND     y.departamento='".$this->departamento."' 
               AND     z.especialidad=l.especialidad 
               AND     z.sw_anestesiologo='1' 
               AND     x.tercero_id=l.tercero_id 
               AND     x.tipo_id_tercero=l.tipo_id_tercero  
               AND     x.tercero_id=c.tercero_id 
               AND     x.tipo_id_tercero=c.tipo_id_tercero 
               AND     profesional_activo(c.tipo_id_tercero,c.tercero_id,'".$this->departamento."')='1'
               ORDER BY c.nombre_tercero";
               
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
    {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {
			if($result->EOF)
      {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'profesionales' esta vacia ";
				return false;
			}
			while (!$result->EOF) 
      {
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
	function profesionalesAyudantes()
  {  
		list($dbconn) = GetDBconn();
		$query = " SELECT  x.tercero_id,
                       z.nombre_tercero as nombre,
                       x.tipo_id_tercero
               FROM    profesionales x,
                       profesionales_departamentos y,
                       terceros z
               WHERE  (x.tipo_profesional='1' OR x.tipo_profesional='2') 
               AND     x.tipo_id_tercero=y.tipo_id_tercero 
               AND     x.tercero_id=y.tercero_id 
               AND     y.departamento='".$this->departamento."' 
               AND     x.tercero_id=z.tercero_id 
               AND     x.tipo_id_tercero=z.tipo_id_tercero 
               AND     profesional_activo(z.tipo_id_tercero,z.tercero_id,'".$this->departamento."')='1'
               ORDER BY z.nombre_tercero";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
    {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {
			if($result->EOF)
      {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'profesionales' esta vacia ";
				return false;
			}
			while (!$result->EOF) 
      {
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
	function profesionalesEspecialistaCiculantes()
  {    
		list($dbconn) = GetDBconn();
		$query = " SELECT  x.tercero_id,
                       c.nombre_tercero as nombre,
                       x.tipo_id_tercero
               FROM    profesionales x,
                       profesionales_departamentos y,
                       especialidades z,
                       profesionales_especialidades l,
                       terceros c
               WHERE   x.tipo_id_tercero=y.tipo_id_tercero 
               AND     x.tercero_id=y.tercero_id 
               AND     y.departamento='".$this->departamento."' 
               AND     z.especialidad=l.especialidad 
               AND     z.sw_circulante='1' 
               AND     x.tercero_id=l.tercero_id 
               AND     x.tipo_id_tercero=l.tipo_id_tercero  
               AND     x.tercero_id=c.tercero_id 
               AND     x.tipo_id_tercero=c.tipo_id_tercero 
               AND     profesional_activo(c.tipo_id_tercero,c.tercero_id,'".$this->departamento."')='1'
               ORDER BY c.nombre_tercero";
               
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
    {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {
			if($result->EOF)
      {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'profesionales' esta vacia ";
				return false;
			}
			while (!$result->EOF) 
      {
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
	function profesionalesEspecialistaInstrumentistas()
  {
    $departamento=$_SESSION['LocalCirugias']['departamento'];
		list($dbconn) = GetDBconn();
		
		$query = " SELECT  x.tercero_id,
                       c.nombre_tercero as nombre,
                       x.tipo_id_tercero
               FROM    profesionales x,
                       profesionales_departamentos y,
                       especialidades z,
                       profesionales_especialidades l,
                       terceros c
               WHERE   x.tipo_id_tercero=y.tipo_id_tercero 
               AND     x.tercero_id=y.tercero_id 
               AND     y.departamento='".$this->departamento."' 
               AND     z.especialidad=l.especialidad 
               AND     z.sw_instrumentista='1' 
               AND     x.tercero_id=l.tercero_id 
               AND     x.tipo_id_tercero=l.tipo_id_tercero  
               AND     x.tercero_id=c.tercero_id 
               AND     x.tipo_id_tercero=c.tipo_id_tercero 
               AND     profesional_activo(c.tipo_id_tercero,c.tercero_id,'".$this->departamento."')='1' 
               ORDER BY c.nombre_tercero";
               
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) 
    {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {
			if($result->EOF)
      {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'profesionales' esta vacia ";
				return false;
			}
			while (!$result->EOF)
      {
				$vars[]=$result->GetRowAssoc($toUpper=false);
			  $result->MoveNext();
			}
		}
		$result->Close();
 		return $vars;
	}
  
  function BuscarProcedimientosInsertados($programacion_qx,$cargo)
  {              
    list($dbconn) = GetDBconn();
    $query = " SELECT  b.procedimiento_opcion,
                       b.descripcion
               FROM    qx_cups_opc_procedimientos_programacion a,
                       qx_cups_opciones_procedimientos b 
               WHERE   a.programacion_id='".$programacion_qx."' 
               AND     a.procedimiento_qx='".$cargo."' 
               AND     a.procedimiento_qx=b.cargo 
               AND     a.procedimiento_opcion=b.procedimiento_opcion";
               
      $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() != 0) 
      {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
      else
      {
        while (!$result->EOF)
        {
          $vars[]=$result->GetRowAssoc($toUpper=false);
          $result->MoveNext();
        }
      }                 
      return $vars;
  }
  
  function BuscarProcedimientosInsertadosNotaOperatoria($NotaId,$cargo,$acto)
  {              
      list($dbconn) = GetDBconn();
      $query = " SELECT  b.procedimiento_opcion,
                         b.descripcion
                 FROM    hc_notas_operatorias_procedimientos_opciones a,
                         qx_cups_opciones_procedimientos b 
                 WHERE   a.hc_nota_operatoria_cirugia_id='".$NotaId."' 
                 AND     a.procedimiento_qx='".$cargo."' 
                 AND     a.procedimiento_qx=b.cargo 
                 AND     a.procedimiento_opcion=b.procedimiento_opcion
                 AND     a.acto_quiru = ".$acto." ";
                 
      $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() != 0)
      {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
      else
      {
        while (!$result->EOF) 
        {
          $vars[]=$result->GetRowAssoc($toUpper=false);
          $result->MoveNext();
        }
      }                 
      return $vars;
      
  }
  
  function BuscarOpcionesProcedimientos($cups)
  { 
      list($dbconn) = GetDBconn();         
      $query = " SELECT  a.procedimiento_opcion,
                         a.descripcion
                 FROM    qx_cups_opciones_procedimientos a
                 WHERE   a.cargo='$cups' 
                 ORDER BY a.descripcion";
                 
      $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() != 0)
      {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
      else
      {      
        while(!$result->EOF)
        {
          $vars[]=$result->GetRowAssoc($toUpper=false);
          $result->MoveNext();
        }
      }     
    return $vars;
  }
  
  function ComprobarOpcionesProcedimientosCups()
  {
    list($dbconn) = GetDBconn();
    $query = " SELECT  a.valor
               FROM    system_modulos_variables a
               WHERE   a.modulo='Quirurgicos' 
               AND     a.modulo_tipo='app' 
               AND     a.variable='cups_opciones_procedimientos'";
    
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0)
    {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }
    else
    {
      if($result->fields[0]==1)
      {
        return 1;
      }
      else
      {
        return 0;
      }
    }
  }
  
  function DescripcionTecnicaQX($programacion)
  {
    if(empty($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']))
      return array();
    $acto = SessionGetVar("ActoQuirurgicoNotaO");
    $VALOR=$this->ProgramacionActivaPaciente($acto); 
		list($dbconn) = GetDBconn();
   
		 $query = " SELECT  descripcion,
                       nombre_tercero
		           FROM    hc_descripcion_cirugia a,
                       profesionales_usuarios prof,terceros ter
		           WHERE   a.ingreso='".$this->ingreso."' 
               AND     a.programacion_id=".$programacion."
               AND     a.usuario_id=prof.usuario_id 
               AND     prof.tipo_tercero_id=ter.tipo_id_tercero 
               AND     prof.tercero_id=ter.tercero_id
               AND     a.hc_nota_operatoria_cirugia_id = ".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']." ";
    
    
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
    {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {		
			while(!$result->EOF)
      {
				$vars[]=$result->GetRowAssoc($toUpper=false);
				$result->MoveNext();
			}
		}			
		return $vars;	
	}
  
  function HallazgosQX($programacion)
  {
    if(empty($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']))
      return array();
    
    $acto = SessionGetVar("ActoQuirurgicoNotaO");
    $VALOR=$this->ProgramacionActivaPaciente($acto);
		list($dbconn) = GetDBconn();
    
    
		 $query = " SELECT  descripcion,
                       nombre_tercero
		           FROM 	 hc_hallazgos_quirurgicos a,
                       profesionales_usuarios prof,
                       terceros ter
		           WHERE   a.ingreso='".$this->ingreso."' 
               AND     a.programacion_id=".$programacion."
               AND	   a.usuario_id=prof.usuario_id 
               AND     prof.tipo_tercero_id=ter.tipo_id_tercero 
               AND     prof.tercero_id=ter.tercero_id
               AND     a.hc_nota_operatoria_cirugia_id = ".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']." " ;
               
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
    {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {		
			while(!$result->EOF)
      {
				$vars[]=$result->GetRowAssoc($toUpper=false);
				$result->MoveNext();
			}
		}			
		return $vars;	
	}
	
	function RegistroPatologias($programacion)
  {
    if(empty($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']))
      return array();
      
    $acto = SessionGetVar("ActoQuirurgicoNotaO");
    $VALOR=$this->ProgramacionActivaPaciente($acto);
   
		list($dbconn) = GetDBconn();
		$query = " SELECT  A.patologia_id,
               		     A.fecha_registro, 
                       A.descripcion, 
                       B.nombre, 
                       B.usuario,
                       A.envio_patologico
				       FROM    hc_patologia_quirurgicos AS A,
					             system_usuarios AS B
				       WHERE   A.ingreso='".$this->ingreso."'
               AND     A.programacion_id=".$programacion."
				       AND     B.usuario_id=A.usuario_id
               AND     A.hc_nota_operatoria_cirugia_id = ".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']."
				       ORDER BY fecha_registro DESC
				";
        
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
    {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {		
			while(!$result->EOF)
      {
				$vars[]=$result->GetRowAssoc($toUpper=false);
				$result->MoveNext();
			}
		}			
		return $vars;	
	}
	
	function RegistroCultivos($programacion)
  {
		if(empty($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']))
      return array();
      
    list($dbconn) = GetDBconn();
    $acto = SessionGetVar("ActoQuirurgicoNotaO");
	  $VALOR=$this->ProgramacionActivaPaciente($acto);
   
    $query= " SELECT  A.cultivos_id,
               		    A.fecha_registro, 
                      A.descripcion, 
                      B.nombre, 
                      B.usuario,
                      A.envio_cultivo
				      FROM    hc_cultivos_quirurgicos AS A,
					            system_usuarios AS B
				      WHERE   A.ingreso='".$this->ingreso."'
              AND     A.programacion_id=".$programacion."
				      AND     B.usuario_id=A.usuario_id
              AND     A.hc_nota_operatoria_cirugia_id = ".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']."
				      ORDER BY fecha_registro DESC";
              
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
    {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    else
    {		
			while(!$result->EOF)
      {
				$vars[]=$result->GetRowAssoc($toUpper=false);
				$result->MoveNext();
			}
		}			
		return $vars;	
	}
  
  
  function NombreOpcionProcedimiento($procedimiento,$cargo)
  {                  
    list($dbconn) = GetDBconn();      
    $query = " SELECT  descripcion
               FROM    qx_cups_opciones_procedimientos
               WHERE   cargo='".$cargo."' 
               AND     procedimiento_opcion='".$procedimiento."'
                ";
                
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0)
    {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }
    else
    {      
      return $result->fields[0];      
    }
  //CENTRO KRA 27 #27-19 MARIAELISA LOCAL102 8-5 JORNADA CONTINUA
  } 
    /**
    * Funcion donde se obtiene la informacion de los actos quirurgicos 
    * creados
    *
    * @param integer ingreso Identificador del ingreso
    *
    * @return mixed
    */
    function ActoQuir($ingreso)
  	{
      $sql  = "SELECT AQ.acto_quiru, ";
      $sql .= "       HC.hc_nota_operatoria_cirugia_id, ";
      $sql .= "       cirujano.nombre_tercero as nombre_c, ";
      $sql .= "       cirujano.tercero_id,";
      $sql .= "       SU.usuario_id, ";
      $sql .= "       SU.nombre ";
      $sql .= "FROM   acto_quirurgico AQ, ";
      $sql .= "       hc_notas_operatorias_cirugias HC ";
      $sql .= "       LEFT JOIN terceros cirujano ";
      $sql .= "       ON( cirujano.tipo_id_tercero=HC.tipo_id_cirujano AND ";
      $sql .= "           cirujano.tercero_id=HC.cirujano_id ), ";
      $sql .= "       system_usuarios SU ";
      $sql .= "WHERE  AQ.ingreso = ".$ingreso." ";
      $sql .= "AND    AQ.acto_quiru = HC.acto_quiru ";
      $sql .= "AND    HC.usuario_id = SU.usuario_id ";
      $sql .= "AND    HC.sw_estado = '1' ";
      $sql .= "ORDER BY AQ.acto_quiru, HC.hc_nota_operatoria_cirugia_id ";
      
  		list($dbconn) = GetDBconn();
      
      $result = $dbconn->Execute($sql);
  		if ($dbconn->ErrorNo() != 0)
      {
  			$this->error = "Error al Cargar el Modulo en la tabla qx_cumplimientos";
  			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
  			return false;
  		}
      
      while(!$result->EOF)
      {
        $datos[$result->fields[0]][]=$result->GetRowAssoc($toUpper=false);
        $result->MoveNext();
      }  
      
  		return $datos;
    }    
    /**
    * Funcion donde se obtiene la informacion de los actos quirurgicos 
    * creados
    *
    * @param array $datos Datos de la nota operatoria
    *
    * @return boolean
    */
    function AnularNotasOperatorias($datos)
  	{
      $sql  = "UPDATE hc_notas_operatorias_cirugias  ";
      $sql .= "SET    sw_estado = '0' ";
      $sql .= "WHERE  acto_quiru = ".$datos['acto_quirurgico']." ";
      $sql .= "AND    hc_nota_operatoria_cirugia_id = ".$datos['hc_nota_operatoria_cirugia_id']." ";
      
  		list($dbconn) = GetDBconn();
      
      $result = $dbconn->Execute($sql);
  		if ($dbconn->ErrorNo() != 0)
      {
  			$this->error = "Error al ejecutar el update de la nota operatoria";
  			$this->mensajeDeError = "Error DB : ".$sql." <br> ". $dbconn->ErrorMsg();
  			return false;
  		}
      
      $result->Close();  
      
  		return true;
    }
  
  function UsuarioNombre()
  {
    list($dbconn) = GetDBconn();
    $nota =$this->NotaOperatoriaActual();
    $query1 = " SELECT a.nombre, b.hc_nota_operatoria_cirugia_id
                     FROM   system_usuarios as a,
                            hc_notas_operatorias_cirugias as b,
                            acto_quirurgico c
                     WHERE  a.usuario_id=b.usuario_id
                     AND    b.acto_quiru = c.acto_quiru
                    
                   ";
                   //--- AND    b.hc_nota_operatoria_cirugia_id =".$nota['hc_nota_operatoria_cirugia_id']."
    $result = $dbconn->Execute($query1);
		 if ($dbconn->ErrorNo() != 0)
     {
			$this->error = "Error al Cargar el Modulo en la tabla qx_cumplimientos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		 }
     else
     {
			 if($result->RecordCount() > 0)
       {
			   $datos1=$result->GetRowAssoc($toUpper=false);
		   }
		 }
    return $datos1;     
  }
  
  function DatosProfesionalesT($programacion,$acto)
  {
		list($dbconn) = GetDBconn();
    //
		$query="SELECT 
                   tipo_id_anestesiologo,
                   anestesiologo_id,
                   tipo_id_instrumentista,
                   instrumentista_id,
                   tipo_id_circulante,
                   circulante_id,
                   tipo_id_ayudante,
                   ayudante_id,
                   sw_urg_prog
            FROM   hc_notas_operatorias_cirugias 
            WHERE  programacion_id = ".$programacion."
            AND    acto_quiru = ".$acto." ";
            
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
      {
				$this->error = "Error al Cargar el Modulo en la tabla hc_notas_operatorias_cirugias";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
      else
      {
				if(!$result->EOF)
        {
					$datosProfesionales=$result->GetRowAssoc($toUpper=false);
				}
			}					
		return $datosProfesionales;
	}
  
  function PerfilProf()
	{
		list($dbconn) = GetDBconn();
		$sql=" SELECT tipo_profesional 
           FROM   profesionales 
           WHERE  usuario_id =".UserGetUID().";";
           
		$result=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
				$this->error = "Error al consultar tipo profesional";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		
  }
}
?>