<?php
/**
*  class hc_classes_PlanTerapeutico_HTML
*
*  Clase que maneja todas las funciones de consultas e insercion a la base de datos
*  relacionadas con la formulación de medicamentos (liquidos parenterales y medicamentos) de hospitalización de cada paciente.
*  ubicacion => hc_modules/classes/HTML/hc_classes_PlanTerapeutico_HTML.php
*  fecha creación => 20/10/2003 15:30
*
*
*  @var => integer $cantMostrar
*  @autor => ARLEY VELASQUEZ CASTILLO <arleyvc@yahoo.com>
*  @version => 1.0
*  @package SIIS
* $Id: hc_PlanTerapeutico_HTML.php,v 1.27 2006/06/02 19:25:10 tizziano Exp $
*/

class PlanTerapeutico_HTML extends PlanTerapeutico
{
		/**
		*  funcion hc_classes_PlanTerapeutico_HTML()
		*
		*  constructor de la clase
		*
		*  @access private
		*  @return boolean
		*/
		function PlanTerapeutico_HTML()
		{
			$this->PlanTerapeutico();//constructor del padre
			$this->cantMostrar=2;
			return true;
		}//End function

    var $cantMostrar=0;

		/**
		*  funcion SetStyle($campo,$campo2,$colum)
		*
		*  Cambia el estilo del label de los campos ($campo,$campo2), para indicar el error cambiando a
		*  color rojo el label del campo "obligatorio" sin llenar.
		*  $colum define el tamaño del colspan de la tabla donde se llama
		*  retorna la etiqueta donde está el error
		*
		*  @access private
		*  @param string $campo
		*  @param string $campo2
		*  @param string $colum
		*  @return string
		*/
		function SetStyle($campo,$campo2,$colum)
		{
			if ($this->frmError[$campo] || $this->frmError[$campo2] || $campo=="MensajeError")
			{
			  if ($campo=="MensajeError") {
					return ("<tr><td colspan='".$colum."' class='label_error' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
				}
				return ("label_error");
			}
			return ("label");
		}//End function


//clzc - jea - ptce
	function CalcularNumeroPasos($conteo)
	{
		$numpaso=ceil($conteo/$this->limit);
		return $numpaso;
	}

	//clzc - jea - ptce
	function CalcularBarra($paso)
	{
		$barra=floor($paso/10)*10;
		if(($paso%10)==0)
		{
			$barra=$barra-10;
		}
		return $barra;
	}

	//clzc - jea - ptce
	function CalcularOffset($paso)
	{
		$offset=($paso*$this->limit)-$this->limit;
		return $offset;
	}


		/**
		*  funcion FrmForma($action)
		*
		*  Metodo donde dependiendo del valor de $action se ejecutan diferentes acciones (adicionar, insertar, entre otras)
		*  retornando otra accion ó un boolean
		*  Por defecto la accion esta vacia y muestra solo la consulta del plan terapeutico de mezclas y medicamentos
		*  Estas acciones son cada uno de los enlaces del submodulo.
		*
		*  @access private
		*  @param string $action
		*  @return boolean
		*/
		function FrmForma($action)
		{
			$pfj=$this->frmPrefijo;
			switch ($action)
			{
        			//INICIO CASOS DE CLAUDIA DE SOLICTUD DE MEDICAMENTOS
				case "Busqueda_Avanzada_Medicamentos":
				 	$vectorA= $this->Busqueda_Avanzada_Medicamentos();
					$this-> frmForma_Seleccion_Medicamentos($vectorA);
					return true;
					break;

				case "llenar_solicitud_medicamento":
						//variables de sesion usadas en el proceso de medicamentos no pos con justificacion
						//se setean cada vez que se escoja un medicamento nuevo
						unset ($_SESSION['MEDICAMENTOS'.$pfj]);
						unset ($_SESSION['POSOLOGIA4'.$pfj]);
						unset ($_SESSION['DIAGNOSTICOS'.$pfj]);
						unset ($_SESSION['JUSTIFICACION'.$pfj]);
						if ($_REQUEST['opE'.$pfj] != '')
							{
								$_REQUEST['opE'.$pfj]=urldecode($_REQUEST['opE'.$pfj]);
								$_SESSION['DATOS_M'.$pfj]['PLAN_TERAPEUTICO']= $_REQUEST['opE'.$pfj];
								$arreglo=explode('|/',$_SESSION['DATOS_M'.$pfj]['PLAN_TERAPEUTICO']);
								$existe_medicamento = $this->Verificacion_Existe_Medicamento($arreglo[1]);
								//EL IF ES DIFERENTE EN HOSPITALIZACION PARA VALIDAR CASOS ESPECIALES AQUI.
								if(empty($existe_medicamento))
								{
									$this->frmForma_Llenar_Solicitud_Medicamento();
								}
								else
								{   
								    $permitido=0;
                                             for($i=0;$i<sizeof($existe_medicamento);$i++)
                                             {
                                                       if($existe_medicamento[$i][sw_estado]=='0' OR $existe_medicamento[$i][sw_estado]=='8')
                                                       {
                                                            $permitido=1;
                                                            break;
                                                       }
                                             }
                                             if($permitido==1){
                                                  $this->frmForma_Llenar_Solicitud_Medicamento();
                                             }
                                             else
                                             {
                                                  $this->frmError["MensajeError"]="ESTE MEDICAMENTO YA FUE FORMULADO EN ESTE INGRESO";
                                                  $this->frmForma();
                                             }
								}
								/*if($existe_medicamento[sw_estado]=='0' OR $existe_medicamento[sw_estado]=='')
								{
									$this->frmForma_Llenar_Solicitud_Medicamento();
								}
								else
								{
									$this->frmError["MensajeError"]="ESTE MEDICAMENTO YA FUE FORMULADO EN ESTE INGRESO";
									$this->frmForma();
								}*/
							}
						else
							{
								$this->frmError["MensajeError"]="PARA FORMULAR DEBE SELECCIONAR UN MEDICAMENTO DE LA LISTA";
								$this->frmForma();
							}
				return true;
				break;
				case "justificacion_no_pos":
						if(!empty($_REQUEST['guardar_formula'.$pfj]))
						{
							if ($_REQUEST['item'.$pfj] == 'NO POS' AND $_REQUEST['no_pos_paciente'.$pfj] == NULL)
								{
									//$no_pos_paciente = '0';
									if ($this->Verificacion_Previa_Insertar_Medicamentos() == true)
										{
											$this->Justificacion_Medicamentos_No_Pos();
										}
									else
										{
											$this->frmForma_Llenar_Solicitud_Medicamento($_REQUEST['datos_m'.$pfj]);
										}
								}
							else
								{
									$no_pos_paciente = '1';
									if ($this->Insertar_Medicamentos($no_pos_paciente) == true)
									{
											unset ($_SESSION['DATOS_M'.$pfj]['PLAN_TERAPEUTICO']);
											$this->frmForma();
									}
									else
									{
											$this->frmForma_Llenar_Solicitud_Medicamento($_REQUEST['datos_m'.$pfj]);
									}
								}
						}

				return true;
				break;

				case "volver":
						//variables de sesion son seteadas al dar volver estando en la justificacion
						unset ($_SESSION['DIAGNOSTICOS'.$pfj]);
						unset ($_SESSION['JUSTIFICACION'.$pfj]);
						//cargo los request de la forma de solicitud de medicamento con la sesion
						$_REQUEST['via_administracion'.$pfj]= $_SESSION['MEDICAMENTOS'.$pfj]['via_administracion_id'];
						$_REQUEST['dosis'.$pfj]							= $_SESSION['MEDICAMENTOS'.$pfj]['dosis'];
            $_REQUEST['sw_ambulatorio'.$pfj] = $_SESSION['MEDICAMENTOS'.$pfj]['sw_ambulatorio'];
						$_REQUEST['unidad_dosis'.$pfj]			=	$_SESSION['MEDICAMENTOS'.$pfj]['unidad_dosificacion'];
						$_REQUEST['opcion'.$pfj]						= $_SESSION['MEDICAMENTOS'.$pfj]['tipo_opcion_posologia_id'];
						$_REQUEST['cantidad'.$pfj]					= $_SESSION['MEDICAMENTOS'.$pfj]['cantidad'];
						$_REQUEST['observacion'.$pfj]				=	$_SESSION['MEDICAMENTOS'.$pfj]['observacion'];
						$_REQUEST['sw_ambulatorio'.$pfj]				=	$_SESSION['MEDICAMENTOS'.$pfj]['sw_ambulatorio'];
            $_REQUEST['solucion'.$pfj]				=	$_SESSION['MEDICAMENTOS'.$pfj]['solucion'];
            $_REQUEST['solucionUnidad'.$pfj]				=	$_SESSION['MEDICAMENTOS'.$pfj]['solucionUnidad'];

						if ($_SESSION['MEDICAMENTOS'.$pfj]['tipo_opcion_posologia_id']=='1')
						{
							$_REQUEST['periocidad'.$pfj] 				= $_SESSION['MEDICAMENTOS'.$pfj]['periocidad_id'];
							$_REQUEST['tiempo'.$pfj]						= $_SESSION['MEDICAMENTOS'.$pfj]['tiempo'];
						}
						if ($_SESSION['MEDICAMENTOS'.$pfj]['tipo_opcion_posologia_id']=='2')
						{
							$_REQUEST['duracion'.$pfj]					= $_SESSION['MEDICAMENTOS'.$pfj]['duracion_id'];
						}
						if ($_SESSION['MEDICAMENTOS'.$pfj]['tipo_opcion_posologia_id']=='3')
						{
							$_REQUEST['momento'.$pfj]						= $_SESSION['MEDICAMENTOS'.$pfj]['sw_estado_momento'];
							$_REQUEST['desayuno'.$pfj]					= $_SESSION['MEDICAMENTOS'.$pfj]['sw_estado_desayuno'];
							$_REQUEST['almuerzo'.$pfj]					= $_SESSION['MEDICAMENTOS'.$pfj]['sw_estado_almuerzo'];
							$_REQUEST['cena'.$pfj]							= $_SESSION['MEDICAMENTOS'.$pfj]['sw_estado_cena'];
						}
						//si escoje la opcion 4 y regresa desde la justificacion se pierden los datos de la opcion 4
						if ($_SESSION['MEDICAMENTOS'.$pfj]['tipo_opcion_posologia_id']=='4')
						{
							for ($i=0;$i<25;$i++)
							{
								$_REQUEST['opH'.$pfj][$i] = $_SESSION['POSOLOGIA4'.$pfj]['hora_especifica'][$i];
							}
						}
						if ($_SESSION['MEDICAMENTOS'.$pfj]['tipo_opcion_posologia_id']=='5')
						{
							$_REQUEST['frecuencia_suministro'.$pfj]	= $_SESSION['MEDICAMENTOS'.$pfj]['frecuencia_suministro'];
						}
						$this->frmForma_Llenar_Solicitud_Medicamento($_REQUEST['datos_m'.$pfj]);
				return true;
				break;

				case "insertar_justificacion_no_pos":
					if ($this->Insertar_Justificacion_No_Pos()== false)
					{
							$this->Justificacion_Medicamentos_No_Pos();
					}
					else
					{
							$this->frmForma();
					}
				return true;
				break;

				case "agregar_diagnosticos":
				if ($_SESSION['MODIFICANDO'.$pfj]!=1)
					{
						//**********creacion de la variable de sesion con los datos de la justificacion**
						$_SESSION['JUSTIFICACION'.$pfj]['dosis_dia']								=$_REQUEST['dosis_dia'.$pfj];
						$_SESSION['JUSTIFICACION'.$pfj]['duracion_tratamiento']			=$_REQUEST['duracion_tratamiento'.$pfj];
						$_SESSION['JUSTIFICACION'.$pfj]['descripcion_caso_clinico']	=$_REQUEST['descripcion_caso_clinico'.$pfj];
						for ($j=1;$j<3;$j++)
							{
									$_SESSION['JUSTIFICACION'.$pfj]['medicamento_pos'.$j]						=$_REQUEST['medicamento_pos'.$j.$pfj];
									$_SESSION['JUSTIFICACION'.$pfj]['principio_activo_pos'.$j]			=$_REQUEST['principio_activo_pos'.$j.$pfj];
									$_SESSION['JUSTIFICACION'.$pfj]['dosis_dia_pos'.$j]							=$_REQUEST['dosis_dia_pos'.$j.$pfj];
									$_SESSION['JUSTIFICACION'.$pfj]['duracion_tratamiento_pos'.$j]	=$_REQUEST['duracion_tratamiento_pos'.$j.$pfj];
									$_SESSION['JUSTIFICACION'.$pfj]['sw_no_mejoria'.$j]							=$_REQUEST['sw_no_mejoria'.$j.$pfj];
									$_SESSION['JUSTIFICACION'.$pfj]['sw_reaccion_secundaria'.$j]		=$_REQUEST['sw_reaccion_secundaria'.$j.$pfj];
									$_SESSION['JUSTIFICACION'.$pfj]['reaccion_secundaria'.$j]				=$_REQUEST['reaccion_secundaria'.$j.$pfj];
									$_SESSION['JUSTIFICACION'.$pfj]['sw_contraindicacion'.$j]				=$_REQUEST['sw_contraindicacion'.$j.$pfj];
									$_SESSION['JUSTIFICACION'.$pfj]['contraindicacion'.$j]					=$_REQUEST['contraindicacion'.$j.$pfj];
									$_SESSION['JUSTIFICACION'.$pfj]['otras'.$j]											=$_REQUEST['otras'.$j.$pfj];
							}
						$_SESSION['JUSTIFICACION'.$pfj]['justificacion_solicitud']	=$_REQUEST['justificacion_solicitud'.$pfj];
						$_SESSION['JUSTIFICACION'.$pfj]['ventajas_medicamento']			=$_REQUEST['ventajas_medicamento'.$pfj];
						$_SESSION['JUSTIFICACION'.$pfj]['ventajas_tratamiento']			=$_REQUEST['ventajas_tratamiento'.$pfj];
						$_SESSION['JUSTIFICACION'.$pfj]['precauciones']							=$_REQUEST['precauciones'.$pfj];
						$_SESSION['JUSTIFICACION'.$pfj]['controles_evaluacion_efectividad']=$_REQUEST['controles_evaluacion_efectividad'.$pfj];
						$_SESSION['JUSTIFICACION'.$pfj]['tiempo_respuesta_esperado']=$_REQUEST['tiempo_respuesta_esperado'.$pfj];
						$_SESSION['JUSTIFICACION'.$pfj]['sw_riesgo_inminente']			=$_REQUEST['sw_riesgo_inminente'.$pfj];
						$_SESSION['JUSTIFICACION'.$pfj]['riesgo_inminente']					=$_REQUEST['riesgo_inminente'.$pfj];
						$_SESSION['JUSTIFICACION'.$pfj]['sw_agotadas_posibilidades_existentes']=$_REQUEST['sw_agotadas_posibilidades_existentes'.$pfj] ;
						$_SESSION['JUSTIFICACION'.$pfj]['sw_homologo_pos']					=$_REQUEST['sw_homologo_pos'.$pfj] ;
						$_SESSION['JUSTIFICACION'.$pfj]['sw_comercializacion_pais']	=$_REQUEST['sw_comercializacion_pais'.$pfj];
						$_SESSION['JUSTIFICACION'.$pfj]['pare']=0;
						//**********fin******************************************************************
					}
				$this->frmFormaDiagnosticos();
				return true;
				break;

				case "Busqueda_Avanzada_Diagnosticos":
						$vectorD= $this->Busqueda_Avanzada_Diagnosticos();
						$this->frmFormaDiagnosticos($vectorD);
				return true;
				break;

				case "insertar_varios_diagnosticos":
						$this->Insertar_Varios_Diagnosticos();
						if ($_SESSION['MODIFICANDO'.$pfj]!=1)
						{
							$this->Justificacion_Medicamentos_No_Pos();
						}
						else
						{
							$this->Consultar_Justificacion_Medicamentos_No_Pos($_REQUEST['codigo_p'.$pfj]);
						}
				return true;
				break;

				case "eliminardiagnostico":
					unset ($_SESSION['DIAGNOSTICOS'.$pfj][$_REQUEST['diagnostico'.$pfj]]);
					$_SESSION['JUSTIFICACION'.$pfj]['pare']=1;
					$this->Justificacion_Medicamentos_No_Pos();
				return true;
				break;

				case 'eliminardiagnosticom':
					unset ($_SESSION['DIAGNOSTICOSM'.$pfj][$_REQUEST['diagnostico'.$pfj]]);
					$this->Consultar_Justificacion_Medicamentos_No_Pos($_REQUEST['codigo_p'.$pfj]);
				return true;
				break;

				case "Consultar_Justificacion":
					$_SESSION['MEDICAMENTOSM'.$pfj][codigo_producto]=$_REQUEST['codigo_p'.$pfj];
					$_SESSION['MEDICAMENTOSM'.$pfj][evolucion]=$_REQUEST['evolucion'.$pfj];
					$_SESSION['MEDICAMENTOSM'.$pfj][producto]=$_REQUEST['product'.$pfj];
					$_SESSION['MEDICAMENTOSM'.$pfj][principio_activo]=$_REQUEST['principio_a'.$pfj];
					$_SESSION['MEDICAMENTOSM'.$pfj][via]=$_REQUEST['via'.$pfj];
					$_SESSION['MEDICAMENTOSM'.$pfj][dosis]=$_REQUEST['dosis'.$pfj];
					$_SESSION['MEDICAMENTOSM'.$pfj][unidad_dosificacion]=$_REQUEST['unidad'.$pfj];
					$_SESSION['MEDICAMENTOSM'.$pfj][cantidad]=$_REQUEST['canti'.$pfj];
					$_SESSION['MEDICAMENTOSM'.$pfj][descripcion]=$_REQUEST['desc'.$pfj];
					$_SESSION['MEDICAMENTOSM'.$pfj][contenido_unidad_venta]=$_REQUEST['contenido_u_v'.$pfj];
					$_SESSION['MEDICAMENTOSM'.$pfj][observacion]=$_REQUEST['obs'.$pfj];
					$_SESSION['MODIFICANDO'.$pfj]=1;
					$this->Consultar_Justificacion_Medicamentos_No_Pos();
				return true;
				break;



				case "modificar_justificacion_no_pos":
				//$this->Modificacion_Justificacion_Medicamentos_No_Pos($_REQUEST['hc_justificaciones_no_pos_hosp'.$pfj]);
				$this->Modificacion_Justificacion_Medicamentos_No_Pos($_REQUEST['hc_justificaciones_no_pos_hosp'.$pfj]);
     		$this->frmForma();
				return true;
				break;

				case "eliminar":
				$this->Eliminar_Medicamento_Solicitada($_REQUEST['codigo_producto'.$pfj], $_REQUEST['opcion_posologia'.$pfj]);
				$this->frmForma();
				//no necesito setear ninguna variable ok
				return true;
				break;

				case "forma_modificar_medicamento":
				unset($_SESSION['SPIA'.$pfj]);
				$this->frmForma_Modificar_Solicitud_Medicamento($_REQUEST['codigo_producto'.$pfj]);
				return true;
				break;

				case "modificar_datos":
				if ($_REQUEST['item'.$pfj] == 'POS')
					{
						$_REQUEST['no_pos_paciente'.$pfj] = '1';
						$this->Modificar_Medicamento_Solicitado($_REQUEST['codigo_producto'.$pfj], $_REQUEST['opcion_posol'.$pfj]);
						$this->frmForma();
					}
				else
					{
						if (empty($_REQUEST['no_pos_paciente'.$pfj]))
						{
								//si llega aqui es porque el medicamento se iba a pagar y se modifico para justificarlo
								$_SESSION['SPIA'.$pfj]=1;
								if ($this->Verificacion_Previa_Insertar_Medicamentos() == true)
									{
										$this->Justificacion_Medicamentos_No_Pos();
									}
								else
									{
										$this->frmForma_Modificar_Solicitud_Medicamento($_REQUEST['codigo_producto'.$pfj]);
									}
						}
						else
						{
							$this->Modificar_Medicamento_Solicitado($_REQUEST['codigo_producto'.$pfj], $_REQUEST['opcion_posol'.$pfj]);
							$this->frmForma();
						}
					}
				return true;
				break;

				//FIN CASOS DE CLAUDIA DE SOLICTUD DE MEDICAMENTOS
				/*case $this->frmPrefijo."Add":
				$this->frmForma_Add();
				return true;
				break;*/

				//CASOS DE CLAUDIA ESPECIALES PARA LA SOLICTUD DEL MEDICAMENTO EN HOSPITALIZACION
				case "Suspender_Medicamento":
							$this->Nota_Suspension_Medicamento();
							return true;
				break;

				case "InsertarSuspensionMedicamento":

						if ($this->Insertar_Suspension_Medicamento()==true)
						{
							$this->frmForma();
						}
						else
						{
							$this->Nota_Suspension_Medicamento();
						}
						return true;
				break;

				case "Finalizar_Medicamento":
					    $this->Finalizar_Medicamento();
							$this->frmForma();
          return true;
				break;

				case "Activar_Medicamento_Medico":
					    $this->Activar_Medicamento_Medico();
							$this->frmForma();
          return true;
				break;

				case "Detalle_Suministro":
					    $this->Detalle_Suministro();
							//$this->frmForma();
          return true;
				break;

				case "Control_Suministro":
						$_SESSION['CABECERA_CONTROL'.$pfj][codigo_producto]=$_REQUEST['codigo_producto'.$pfj];
						$_SESSION['CABECERA_CONTROL'.$pfj][evolucion]=$_REQUEST['evolucion_id'.$pfj];
						$_SESSION['CABECERA_CONTROL'.$pfj][producto]=$_REQUEST['producto'.$pfj];
						$_SESSION['CABECERA_CONTROL'.$pfj][principio_activo]=$_REQUEST['principio_activo'.$pfj];
						$_SESSION['CABECERA_CONTROL'.$pfj][cantidad]=$_REQUEST['cantidad'.$pfj];
						$_SESSION['CABECERA_CONTROL'.$pfj][descripcion]=$_REQUEST['descripcion'.$pfj];
						$_SESSION['CABECERA_CONTROL'.$pfj][contenido_unidad_venta]=$_REQUEST['contenido_unidad_venta'.$pfj];
						$_SESSION['CABECERA_CONTROL'.$pfj][unidad_dosificacion]=$_REQUEST['unidad_dosificacion'.$pfj];
						$_SESSION['CABECERA_CONTROL'.$pfj][dosis]=$_REQUEST['dosis'.$pfj];
					    $this->Control_Suministro();
							//$this->frmForma();
          return true;
				break;

				case "InsertarControlSuministro":
				$this->Insertar_Control_Suministro();
				$this->Control_Suministro();
				return true;
				break;

                    //FIN DE CLAUDIA ESPECIALES PARA LA SOLICTUD DEL MEDICAMENTO EN HOSPITALIZACION
                    //caso de claudia para la impresion
                    case "imprimir_justificacion_nopos":
				$this->Imprimir_Justificacion_nopos();
				return true;
				break;
				//fin de la impresion

                    //casos para solicitar insumos - claudia
                    //ESTOS DOS CASES DE INSUMOS APENAS ESTAN EN DESARROLLO, POR ELLO
				//ESTA COMENTADO EL HTML QUE MANDA ESTAS ACCIONES.
				case "Busqueda_Avanzada_Insumos":
				$vectorI= $this->Busqueda_Avanzada_Insumos();
				$this->Control_Suministro($vectorI);
				return true;
				break;

                    case "insertar_varios_insumos":
                    $this->Insertar_Varios_Insumos();
                    $this->Control_Suministro($vectorI);
                    return true;
                    break;
                    //fin de insumos claudia

				case $this->frmPrefijo."AddLiquidosP":

					foreach(SessionGetVar($this->frmPrefijo.'REQUEST_BKUP') as $key => $value){
						if (!array_key_exists($key,$_REQUEST)){
							$_REQUEST[$key]=$value;
						}
					}

					if (!empty($_REQUEST[$this->frmPrefijo."Eliminar_Mezcla"]) && !empty($_REQUEST[$this->frmPrefijo."Eliminar"])){
						foreach($_REQUEST[$this->frmPrefijo."Eliminar_Mezcla"] as $key => $value){
							unset($_SESSION[$this->frmPrefijo.'DAT_MEZCLA'][$value]);
						}
						unset($_REQUEST[$this->frmPrefijo.'Datos']);
					}
					if ($_REQUEST[$this->frmPrefijo.'Cancelar']==='Cancelar' || $_REQUEST['Borrar_session']){
						SessionDelVar($this->frmPrefijo.'MTZ_MEZCLAS');
						SessionDelVar($this->frmPrefijo.'MTZ_MEZCLASB');
						SessionDelVar($this->frmPrefijo.'MTZ_MEDICAMENTOS');
						SessionDelVar($this->frmPrefijo.'MTZ_MEDICAMENTOS_BODEGA');
						SessionDelVar($this->frmPrefijo.'DAT_MEZCLA');
						SessionDelVar($this->frmPrefijo.'REQUEST_BKUP');
						if ($_REQUEST[$this->frmPrefijo.'Cancelar']==='Cancelar'){
							$this->FrmForma('');
							return true;
						}
					}

					if ($_REQUEST[$this->frmPrefijo.'Add_Mezclas']==='Add Mezcla' && empty($_SESSION[$this->frmPrefijo.'DAT_MEZCLA'])){
						$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."AddMezcla"));
						unset($_REQUEST[$this->frmPrefijo.'Add_Mezclas']);
						SessionSetVar($this->frmPrefijo.'REQUEST_BKUP',$_REQUEST);
						$this->FrmForma($this->frmPrefijo."AddMezcla");
						//$this->salida.="<script>\n";
						//$this->salida.="<a href=\"$href\">ir a</a>;\n";
						//$this->salida.="</script>\n";
						return true;
					}

					if ($_REQUEST[$this->frmPrefijo.'Add_Medicamentos']==='Add Medicamento'){
						$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."CreaMezcla"));
						unset($_REQUEST[$this->frmPrefijo.'Add_Medicamentos']);
						SessionSetVar($this->frmPrefijo.'REQUEST_BKUP',$_REQUEST);
						unset($_REQUEST[$this->frmPrefijo.'Add_Medicamentos']);
						/*$this->salida.="<script>\n";
						$this->salida.="	location.href=\"$href\";\n";
						$this->salida.="</script>\n";*/
						$this->FrmForma($this->frmPrefijo."CreaMezcla");
						return true;
					}

					if ($_REQUEST[$this->frmPrefijo.'Enviar']==='Guardar'){
						$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."GuardarMezcla"));
						unset($_REQUEST[$this->frmPrefijo.'Enviar']);
						SessionSetVar($this->frmPrefijo.'REQUEST_BKUP',$_REQUEST);
						/*$this->salida.="<script>\n";
						$this->salida.="	location.href=\"$href\";\n";
						$this->salida.="</script>\n";*/
						$this->FrmForma($this->frmPrefijo."GuardarMezcla");
						return true;
					}

					$bodegas=array();
					$bodegas=$this->Bodegas();

					if (!empty($_REQUEST[$this->frmPrefijo."Fecha"]))  $fecha=$_REQUEST[$this->frmPrefijo."Fecha"];
					else  $fecha=date("Y-m-d H:i:s");

					if (!$bodegas)
					{
						$this->error = "NO EXISTEN BODEGAS";
						$this->mensajeDeError = "El usuario no tiene asignada alguna bodega de medicamentos.";
			 			return false;
					}

					$datos_emp=$this->GetDatosEmpresas();
					$query=$this->GetQueryBodegas($this->empresa,$datos_emp['centro_utilidad'],false);
					$bdgas=urlencode(serialize($bodegas));

					$this->salida .= ThemeAbrirTablaSubModulo('LIQUIDOS PARENTERALES');
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."AddLiquidosP"));
					$this->salida.= "<form name='".$this->frmPrefijo."datos' action=\"$href\" method='POST'>";

					$this->salida.="<script>\n";
					$this->salida.="function buscaCampos(campo) {\n";
					$this->salida.="var i=0; var j=0;";
					$this->salida.="while (!i) { if (document.".$this->frmPrefijo."datos.elements[j].name!=campo) j++; else return(j); } \n";
					$this->salida.="return (-1);\n }\n\n";

					$this->salida.="function abrirVentana(forma) {\n";
					$this->salida.="var nombre='';\n";
					$this->salida.="var url2='';\n";
					$this->salida.="var str='';\n";
					$this->salida.="var nombre='Buscador_General';\n";
					$this->salida.="var bTipoUrl=0;\n";
					$this->salida.="var bTipoQuest='".$query."';\n";
					$this->salida.="var bBodegas='".$bdgas."';\n";
					$this->salida.="var bTipoQuestKey='';\n";
					$this->salida.="var Ancho=screen.width;\n";
					$this->salida.="var Alto=screen.height;\n";
					$this->salida.="var str ='Alto Ancho resizable=no status=no scrollbars=yes';\n";
					$this->salida.="bTipoQuestKey='codigo_producto,descripcion';\n";
					$this->salida.="url2 ='classes/classbuscador/buscador.php?tipo=planT&key='+bTipoQuestKey+'&forma='+forma+'&sql='+bTipoQuest+'&bdgas='+bBodegas; \n";
					$this->salida.="window.open(url2, nombre, str);\n";
					$this->salida.="var otro=document.".$this->frmPrefijo."datos.elements[buscaCampos('".$this->frmPrefijo."datosEsPos')].value;\n\n";
					$this->salida.="\n}\n\n";

					$this->salida.="function Calcula_Conversion(forma) {\n";
					$this->salida.="	var i=0;\n";
					$this->salida.="	var cantidad=forma.".$this->frmPrefijo."datosCantidadMedicamento.value; \n";
					$this->salida.="	var horas=forma.".$this->frmPrefijo."frecuencia.value; \n";
					$this->salida.="	var largo=horas.length;\n";
					$this->salida.="	var Hora=parseFloat(horas,10); \n";
					$this->salida.="	var valRestoHoras=0; \n";
					$this->salida.="	var cmHora=0; \n";
					$this->salida.="	var cmMinuto=0; \n";
					$this->salida.="	var gotasMin=0; \n";
					$this->salida.="	var gotas=0; \n";
					$this->salida.="	var microgotas=0; \n\n";
					$this->salida.="	var microgotasMin=0; \n\n";
					$this->salida.="	var UdsC=forma.elements[buscaCampos('".$this->frmPrefijo."UdsCalculo')].options[forma.elements[buscaCampos('".$this->frmPrefijo."UdsCalculo')].selectedIndex].value; \n\n";
					$this->salida.="		if (cantidad.length==0 && forma.".$this->frmPrefijo."SelectFr.options[forma.".$this->frmPrefijo."SelectFr.selectedIndex].value!='-1') {\n ";
					$this->salida.="			alert('Digite la Cantidad.');\n";
					$this->salida.="			forma.".$this->frmPrefijo."datosCantidadMedicamento.focus();\n";
					$this->salida.="			forma.elements[buscaCampos('".$this->frmPrefijo."UdsCalculo')].options[0].selected=true;\n";
					$this->salida.="		return false; }\n";
					$this->salida.="		if (horas.length==0 && forma.".$this->frmPrefijo."SelectFr.options[forma.".$this->frmPrefijo."SelectFr.selectedIndex].value=='Horas') {\n ";
					$this->salida.="			alert('Digite la frecuencia.');\n";
					$this->salida.="			forma.".$this->frmPrefijo."frecuencia.focus();\n";
					$this->salida.="			forma.elements[buscaCampos('".$this->frmPrefijo."UdsCalculo')].options[0].selected=true;\n";
					$this->salida.="		return false; }\n";
					$this->salida.="		if (isNaN(horas) || isNaN(cantidad)) {\n";
					$this->salida.="			alert('Debe digitar un valor entero o float.');\n";
					$this->salida.="			if (isNaN(horas)) \n";
					$this->salida.="				forma.".$this->frmPrefijo."frecuencia.focus();\n";
					$this->salida.="			else forma.".$this->frmPrefijo."datosCantidadMedicamento.focus();\n";
					$this->salida.="		forma.elements[buscaCampos('".$this->frmPrefijo."UdsCalculo')].options[0].selected=true;\n";
					$this->salida.="		return false; }\n\n";

					$this->salida.="		cmHora=cantidad/Hora;\n";
					$this->salida.="		cmMinuto=cmHora/60;\n";
					$this->salida.="		gotas=20*cantidad;\n";
					$this->salida.="		gotasMin=gotas/Hora;\n";
					$this->salida.="		gotasMin=gotasMin/60;\n";
					$this->salida.="		microgotasMin=cantidad/Hora;\n\n";

					$this->salida.="	if (forma.".$this->frmPrefijo."SelectFr.options[forma.".$this->frmPrefijo."SelectFr.selectedIndex].value=='Horas') {\n";
					$this->salida.="		if (UdsC=='-1') {\n";
					$this->salida.="			forma.".$this->frmPrefijo."calculo.value='';\n }";
					$this->salida.="		else {\n";
					$this->salida.="			switch(UdsC) {\n";
					$this->salida.="				case '1' : forma.".$this->frmPrefijo."calculo.value=Math.floor(cmHora);\n";
					$this->salida.="				break;\n";
					$this->salida.="				case '2' : forma.".$this->frmPrefijo."calculo.value=Math.floor(gotasMin);\n";
					$this->salida.="				break;\n";
					$this->salida.="				case '3' : forma.".$this->frmPrefijo."calculo.value=Math.floor(microgotasMin);\n";
					$this->salida.="				break;\n";
					$this->salida.="			}\n";
					$this->salida.="			if (forma.".$this->frmPrefijo."calculo.value.length > forma.".$this->frmPrefijo."calculo.size){\n";
					$this->salida.="				forma.".$this->frmPrefijo."calculo.size=forma.".$this->frmPrefijo."calculo.value.length; \n";
					$this->salida.="				forma.".$this->frmPrefijo."calculo.maxlength=forma.".$this->frmPrefijo."calculo.value.length; \n";
					$this->salida.="			}\n";
					$this->salida.="		}\n";
					$this->salida.=" 	}\n";
					$this->salida.="}\n";


					$this->salida.="function Calcular(valor,forma) {\n";
					$this->salida.="	var i=0;\n";
					$this->salida.="	var cantidad=forma.".$this->frmPrefijo."datosCantidadMedicamento.value; \n";
					$this->salida.="	var horas=forma.".$this->frmPrefijo."frecuencia.value; \n";
					$this->salida.="		if (cantidad.length==0 && valor!='-1') {\n ";
					$this->salida.="			alert('Digite la Cantidad.');\n";
					$this->salida.="			forma.".$this->frmPrefijo."datosCantidadMedicamento.focus();\n";
					$this->salida.="			forma.".$this->frmPrefijo."SelectFr.options[0].selected=true;\n";
					$this->salida.="		return false; }\n";
					$this->salida.="		if (horas.length==0 && valor=='Horas') {\n ";
					$this->salida.="			alert('Digite la frecuencia.');\n";
					$this->salida.="			forma.".$this->frmPrefijo."frecuencia.focus();\n";
					$this->salida.="			forma.".$this->frmPrefijo."SelectFr.options[0].selected=true;\n";
					$this->salida.="		return false; }\n";
					$this->salida.="		if (isNaN(horas) || isNaN(cantidad)) {\n";
					$this->salida.="			alert('Debe digitar un valor entero o float.');\n";
					$this->salida.="			if (isNaN(horas)) \n";
					$this->salida.="				forma.".$this->frmPrefijo."frecuencia.focus();\n";
					$this->salida.="			else forma.".$this->frmPrefijo."datosCantidadMedicamento.focus();\n";
					$this->salida.="			forma.".$this->frmPrefijo."SelectFr.options[0].selected=true;\n";
					$this->salida.="		return false; }\n\n";
					$this->salida.="		if (valor=='Bolo' || valor=='-1') {";
					$this->salida.="			forma.".$this->frmPrefijo."frecuencia.value='';\n";
					$this->salida.="			forma.".$this->frmPrefijo."calculo.value='';\n";
					$this->salida.="			forma.".$this->frmPrefijo."UdsCalculo.options[0].selected=true; \n";
					$this->salida.="		}\n";
					$this->salida.="}\n";

					$this->salida.="</script>\n";

					$this->salida .= "<table width='100%' cellpadding='2' border='1'>\n";
					$this->salida .= $this->SetStyle("MensajeError",'',2);
					$this->salida .= "	<tr>\n";
					$this->salida .= "		<td width='15%' class='".$this->SetStyle($this->frmPrefijo."mezclaMedicamento",'',2)."'>Mezcla: </td>\n";
					$this->salida .= "		<td width='85%' class='label'>\n";
					$this->salida .= "			<table width='100%' border='0'>\n";
					$this->salida .= "				<tr>\n";
					if (!empty($_SESSION[$this->frmPrefijo.'DAT_MEZCLA']) || !empty($_REQUEST[$this->frmPrefijo.'Datos'][$this->frmPrefijo.'mezcla_grupo'])){
						$this->salida .= "					<td width='50%'>\n";
						$this->salida .= "						<input type='submit' class='input-submit' name='".$this->frmPrefijo."Add_Mezclas' value='Add Mezcla' disabled></td>\n";
						$this->salida .= "					</td>\n";
					}
					else{
						$this->salida .= "					<td width='50%'>\n";
						$this->salida .= "						<input type='submit' class='input-submit' name='".$this->frmPrefijo."Add_Mezclas' value='Add Mezcla'></td>\n";
						$this->salida .= "					</td>\n";
					}
					$this->salida .= "					<td width='10%' class='label'>Medicamentos: </td>\n";
					$this->salida .= "					<td width='40%'>\n";
					$this->salida .= "						<input type='submit' class='input-submit' name='".$this->frmPrefijo."Add_Medicamentos' value='Add Medicamento'></td>\n";
					$this->salida .= "					</td>\n";
					$this->salida .= "				</tr>\n";
					$this->salida .= "			</table>\n";
					$this->salida .= "		</td>\n";
					$this->salida .= "	</tr>\n";
					$this->salida .= "	<tr>\n";
					$this->salida .= "		<td width='15%' class='".$this->SetStyle($this->frmPrefijo."cantidadMedicamento",'',2)."'>Cantidad: </td>\n";
					$this->salida .= "		<td width='85%'><input type='text' name='".$this->frmPrefijo."datosCantidadMedicamento' size=10 class='input-text' value='".$_REQUEST[$this->frmPrefijo."datosCantidadMedicamento"]."'></td>\n";
					$this->salida .= "	</tr>\n";

					$this->salida .= "	<tr>\n";
					$this->salida .= "		<td width='15%' class='".$this->SetStyle($this->frmPrefijo."Frecuencia",'',2)."'>Frecuencia: </td>\n";
					$this->salida .= "		<td width='85%'>\n";
					$this->salida .= "			<table border='0' width='100%'>\n";
					$this->salida .= "				<tr>\n";
					$this->salida .= "					<td align='justify' width='20%'><input type='text' name='".$this->frmPrefijo."frecuencia' size='10' class='input-text' value='".$_REQUEST[$this->frmPrefijo."frecuencia"]."'></td>\n";
					$this->salida .= "					<td align='justify' width='80%'>\n";
					$this->salida .= "						<select name='".$this->frmPrefijo."SelectFr' class='select' onchange='Calcular(this.options[selectedIndex].value,this.form);'>\n";
					$this->salida .= "							<option value='-1'>--</option>\n";
					if ($_REQUEST[$this->frmPrefijo.'SelectFr']==='Horas'){
						$this->salida .= "							<option value='Horas' selected>Horas</option>\n";
					}
					else{
						$this->salida .= "							<option value='Horas'>Horas</option>\n";
					}
					if ($_REQUEST[$this->frmPrefijo.'SelectFr']==='Bolo'){
						$this->salida .= "							<option value='Bolo' selected>Bolo</option>\n";
					}
					else{
						$this->salida .= "							<option value='Bolo'>Bolo</option>\n";
					}
					$this->salida .= "						</select>\n";
					$this->salida .= "					</td>\n";
					$this->salida .= "				</tr>\n";
					$this->salida .= "			</table>\n";
					$this->salida .= "		</td>\n";
					$this->salida .= "	</tr>\n";
					$this->salida .= "	<tr>\n";
					$this->salida .= "		<td width='15%' class='".$this->SetStyle($this->frmPrefijo."Conversion",'',2)."'>Conversión: </td>\n";
					$this->salida .= "		<td width='85%'><input type='text' class='input-text' name='".$this->frmPrefijo."calculo' size='15' value='".$_REQUEST[$this->frmPrefijo.'calculo']."'>&nbsp;&nbsp;&nbsp;\n";
					$this->salida .= "				<select name='".$this->frmPrefijo."UdsCalculo' onchange='Calcula_Conversion(this.form);' class='select'>\n<option value='-1'>--</option>\n";
					$frecuenciaUds=$this->GetFrecuenciaUds();
						for ($j=0;$j<sizeof($frecuenciaUds);$j++)
						{
							if ($frecuenciaUds[$j]['tipo_unidad_fr_id']==$_REQUEST[$this->frmPrefijo."UdsCalculo"])
								$this->salida.="				<option value='".$frecuenciaUds[$j]['tipo_unidad_fr_id']."' selected>".$frecuenciaUds[$j]['descripcion']."</option>\n";
							else
								$this->salida.="				<option value='".$frecuenciaUds[$j]['tipo_unidad_fr_id']."'>".$frecuenciaUds[$j]['descripcion']."</option>\n";
						}
					$this->salida .= "				</select>\n";
					$this->salida .= "		</td>\n";
					$this->salida .= "	</tr>\n";

					$this->salida .= "	<tr>\n";
					$this->salida .= "		<td width='15%' class='".$this->SetStyle($this->frmPrefijo."Observaciones",'',2)."'>Observaciones: </td>\n";
					$this->salida .= "		<td width='85%'><textarea class='textarea' name='".$this->frmPrefijo."ObservacionesMezcla' rows='5' cols='60'>".$_REQUEST[$this->frmPrefijo.'ObservacionesMezcla']."</textarea>\n";
					$this->salida .= "		</td>\n";
					$this->salida .= "	</tr>\n";

					$this->salida .= "</table>\n";
					if (!empty($_REQUEST[$this->frmPrefijo.'Datos'][$this->frmPrefijo.'mezcla_grupo']) ){
						$resultadoMz=$this->GetMezclasGrupos($datos_emp['empresa_id'],$datos_emp['centro_utilidad'],$bodegas[0]['estacion_id'],$_REQUEST[$this->frmPrefijo.'Datos'][$this->frmPrefijo.'mezcla_grupo'][0]);
						if (!$resultadoMz) {
							return false;
						}
						else{
							$data=$resultadoMz->FetchNextObject($toupper=false);
							$mezcla['codigo']=$data->mezcla_grupo_id;
							$mezcla['nombre']=$data->descripcion;
							$mezcla['empresa']=$data->empresa_id;
							$mezcla['cu']=$data->centro_utilidad;
							$mezcla['bodega']=$data->bodega;
							$mezcla['estacion']=$data->estacion_id;
							$_SESSION[$this->frmPrefijo.'DAT_MEZCLA'][$mezcla['codigo']]=$mezcla;
							unset($mezcla);
						}
						$resultadoMzMed=$this->GetMezclaMedicamentos($_REQUEST[$this->frmPrefijo.'Datos'][$this->frmPrefijo.'mezcla_grupo'][0]);
						if (!$resultadoMzMed) {
							return false;
						}
						else{
							while($data=$resultadoMzMed->FetchNextObject($toupper=false)){
								$indice=sizeof($_SESSION[$this->frmPrefijo.'MTZ_MEZCLAS']);
								$dat_medicamentos['codigo']=$data->medicamento_id;
								$dat_medicamentos['nombre']=$this->GetNombMedicamentos($data->medicamento_id);
								$dat_medicamentos['ind_suministro']=$data->indicaciones_suministro;
								$dat_medicamentos['cantidad']=$data->cantidad;
								$dat_mezcla[]=$dat_medicamentos;
							}
							$_SESSION[$this->frmPrefijo.'DAT_MEZCLA'][$_REQUEST[$this->frmPrefijo.'Datos'][$this->frmPrefijo.'mezcla_grupo'][0]]['D_MEZCLA']=$dat_mezcla;
							unset($dat_mezcla);
							unset($dat_medicamentos);
						}
					}

					if (SessionIsSetVar($this->frmPrefijo.'MTZ_MEDICAMENTOS')){
						foreach($_SESSION[$this->frmPrefijo.'MTZ_MEDICAMENTOS'] as $key => $value){
							if (!in_array($value['codigo'],$_REQUEST[$this->frmPrefijo.'Datos'][$this->frmPrefijo.'Medicamentos']) || !$this->ValidaDatoMedSession($this->frmPrefijo.'MTZ_MEZCLAS','codigo',$value['codigo'])){
								unset($_SESSION[$this->frmPrefijo.'MTZ_MEDICAMENTOS'][$key]);
								unset($_SESSION[$this->frmPrefijo.'MTZ_MEDICAMENTOS_BODEGA'][$key]);
							}
						}
						foreach($_SESSION[$this->frmPrefijo.'MTZ_MEDICAMENTOS'] as $key => $value){
							if ($this->ValidaDatoMedSession($this->frmPrefijo.'MTZ_MEZCLAS','codigo',$value['codigo'])){

								list($indice)=array_keys($_SESSION[$this->frmPrefijo.'MTZ_MEZCLAS'],end($_SESSION[$this->frmPrefijo.'MTZ_MEZCLAS']));
								$indice+=1;
								$_SESSION[$this->frmPrefijo.'MTZ_MEZCLAS'][$indice]['codigo']=$value['codigo'];
								$_SESSION[$this->frmPrefijo.'MTZ_MEZCLAS'][$indice]['nombre']=$value['nombre'];
								$_SESSION[$this->frmPrefijo.'MTZ_MEZCLAS'][$indice]['cantidad']=$value['cantidad'];
								$_SESSION[$this->frmPrefijo.'MTZ_MEZCLASB'][$indice]['bodega']=$_SESSION[$this->frmPrefijo.'MTZ_MEDICAMENTOS_BODEGA'][$key]['MTZ_BOD'];
							}
						}
					}

					if (SessionIsSetVar($this->frmPrefijo.'DAT_MEZCLA')){
						$this->salida .= "<br><br>\n";
						$this->salida .= "							<table width='100%' border='1' class='modulo_table_list' align='center'>";
						foreach($_SESSION[$this->frmPrefijo.'DAT_MEZCLA'] as $key => $value){
							$this->salida .= "								<tr>\n";
							$this->salida .= "									<td width='15%' class='modulo_table_list_title'>ELIMINAR</td>\n";
							$this->salida .= "									<td width='85%' colspan='3' rowspan='2' class='modulo_table_list_title'>MEZCLA - [ ".$value['nombre']." ]</td>\n";
							$this->salida .= "								</tr>\n";
							$this->salida .= "								<tr>\n";
							$this->salida .= "									<td width='15%' class='modulo_table_list_title'><input type='checkbox' name='".$this->frmPrefijo."Eliminar_Mezcla[]' value='".$value['codigo']."'></td>\n";
							$this->salida .= "								</tr>\n";
							$this->salida .= "								<tr>\n";
							$this->salida .= "									<td width='15%' class='modulo_table_list_title'>CODIGO</td>\n";
							$this->salida .= "									<td width='30%' class='modulo_table_list_title'>NOMBRE</td>\n";
							$this->salida .= "									<td width='40%' class='modulo_table_list_title'>INIDCACIONES SUMINISTRO</td>\n";
							$this->salida .= "									<td width='15%' class='modulo_table_list_title'>CANTIDAD</td>\n";
							$this->salida .= "								</tr>\n";
							foreach($value['D_MEZCLA'] as $key1 => $valor){
								$this->salida .= "								<tr ".$this->Lista($key1).">\n";
								$this->salida .= "									<td width='15%'>".$valor['codigo']."</td>\n";
								$this->salida .= "									<td width='30%'>".$valor['nombre']."</td>\n";
								$this->salida .= "									<td width='40%'>".$valor['ind_suministro']."</td>\n";
								$this->salida .= "									<td width='15%' align='right'>".$valor['cantidad']."</td>\n";
								$this->salida .= "								</tr>\n";
							}
						}
						$this->salida .= "							</table>\n";
					}

					$mezcla_medicamentos=SessionGetVar($this->frmPrefijo.'MTZ_MEZCLAS');
					if (!empty($mezcla_medicamentos)){
						$this->salida .= "							<div align='center' class='label'><br><br>MEDICAMENTOS<br>";
						$this->salida .= "							<table width='100%' border='1' class='modulo_table_list' align='center'>";
						$this->salida .= "								<tr>\n";
						$this->salida .= "									<td width='15%' class='modulo_table_list_title'>CODIGO</td>\n";
						$this->salida .= "									<td width='70%' class='modulo_table_list_title'>NOMBRE</td>\n";
						$this->salida .= "									<td width='15%' class='modulo_table_list_title'>CANTIDAD</td>\n";
						$this->salida .= "								</tr>\n";
						foreach($mezcla_medicamentos as $key => $value){
							$this->salida .= "								<tr ".$this->Lista($key).">\n";
							$this->salida .= "									<td width='15%'>".$value['codigo']."</td>\n";
							$this->salida .= "									<td width='70%'>".$value['nombre']."</td>\n";
							$this->salida .= "									<td width='15%'>".$value['cantidad']."</td>\n";
							$this->salida .= "								</tr>\n";
						}
						$this->salida .= "							</table>\n";
						$this->salida .= "							</div>\n";
					}

					$this->salida .= "<br><br>\n";
					$this->salida .= "<div align='center'>\n";
					$this->salida .= "<table width='50%' border='0' class='label'>\n";
					$this->salida .= "	<tr>";
					$this->salida .= "		<td width='35%' align='center'><br><input type='submit' class='input-submit' name='".$this->frmPrefijo."Enviar' value='Guardar'></td>\n";
					$this->salida .= "		<td width='35%' align='center'><br><input type='submit' class='input-submit' name='".$this->frmPrefijo."Eliminar' value='Eliminar'></td>\n";
					$this->salida .= "		<td width='30%' align='center'><br><input type='submit' class='input-submit' name='".$this->frmPrefijo."Cancelar' value='Cancelar'></td>\n";
					$this->salida .= "	</tr>";
					$this->salida .= "</table>\n";
					$this->salida .= "</div>\n";

					$this->salida .= "<input type='hidden' name='".$this->frmPrefijo."datosPresentMedicamento' value='".$_REQUEST[$this->frmPrefijo."datosPresentMedicamento"]."'>\n";
					$this->salida .= "<input type='hidden' name='".$this->frmPrefijo."datosFormFarmMedicamento' value='".$_REQUEST[$this->frmPrefijo."datosFormFarmMedicamento"]."'>\n";
					$this->salida .= "<input type='hidden' name='".$this->frmPrefijo."datosConcMedicamento' value='".$_REQUEST[$this->frmPrefijo."datosConcMedicamento"]."'>\n";
					$this->salida .= "<input type='hidden' name='".$this->frmPrefijo."datosPrincipioActivo' value='".$_REQUEST[$this->frmPrefijo."datosPrincipioActivo"]."'>\n";
					$this->salida .= "<input type='hidden' name='".$this->frmPrefijo."datosBodega' value='".$_REQUEST[$this->frmPrefijo."datosBodega"]."'>\n";
					$this->salida .= "<input type='hidden' name='".$this->frmPrefijo."datosFecha' value='".$fecha."'>\n";
					$this->salida .= "<input type='hidden' name='".$this->frmPrefijo."datosUnidad' value='".$_REQUEST[$this->frmPrefijo."datosUnidad"]."'>\n";
					$this->salida .= "<input type='hidden' name='".$this->frmPrefijo."datosEsPos' value='".$_REQUEST[$this->frmPrefijo."datosEsPos"]."'>\n";

					$this->salida .= "</form>\n\n";
					$this->salida .= ThemeCerrarTablaSubModulo();
					return true;
			break;
				case $this->frmPrefijo."AddMezcla":
					if ($_REQUEST[$this->frmPrefijo.'Cancelar']==='Cancelar'){
						unset($_REQUEST[$this->frmPrefijo.'Cancelar']);
						unset($_REQUEST[$this->frmPrefijo."Datos"]);
						$this->FrmForma($this->frmPrefijo.'AddLiquidosP');
						return true;
					}

					if (!empty($_REQUEST[$this->frmPrefijo.'Enviar'])){
						$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."AddLiquidosP",$this->frmPrefijo."Datos"=>$_REQUEST));
						unset($_REQUEST[$this->frmPrefijo.'Enviar']);
						$_REQUEST[$this->frmPrefijo."Datos"]=$_REQUEST;
						/*$this->salida.="<script>\n";
						$this->salida.="	location.href=\"$href\";\n";
						$this->salida.="</script>\n";*/
						$this->FrmForma($this->frmPrefijo."AddLiquidosP");
						return true;
					}

					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."AddMezcla"));
					$this->salida .="<form name='".$this->frmPrefijo."datos' action='".$href."' method='POST'>";
					$this->salida .= ThemeAbrirTabla("MEZCLAS","100%","L");
					list($dbconn) = GetDBconn();
					$datos_emp=$this->GetDatosEmpresas();

					$bodegas=$this->Bodegas();
					$contador=1;
					$resultadoMz=$this->GetMezclasGrupos($datos_emp['empresa_id'],$datos_emp['centro_utilidad'],$bodegas[0]['estacion_id']);
					if (!$resultadoMz) {
						return false;
					}
						while ($dataMz = $resultadoMz->FetchNextObject($toupper=false))
						{
							$resultadoMzMed=$this->GetMezclaMedicamentos($dataMz->mezcla_grupo_id);
								if (!$resultadoMzMed) {
									return false;
								}
								else
								{
									$this->salida .= "<table width='100%' class=\"modulo_table_list\" align=\"center\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">\n";
									$this->salida .= $this->SetStyle("MensajeError","",2);
									$this->salida .= "	<tr>\n";
									$this->salida .= "		<td width='5%' class=\"hc_table_title\"><input type='hidden' name='".$this->frmPrefijo."Radio".$contador."' value=''><input type='radio' name='".$this->frmPrefijo."mezcla_grupo[]' value='".$dataMz->mezcla_grupo_id."' >\n";
									$this->salida .= "		</td>\n";
									$this->salida .= "		<td width='95%' class=\"hc_table_title\" align='center'>".strtoupper($dataMz->descripcion)."</td>\n";
									$this->salida .= "	</tr>\n";
									$this->salida .= "	<tr>\n";
									$this->salida .= "		<td width='100%' colspan='2'>\n";

									$this->salida .= "			<table width='100%' class=\"modulo_table_list\" align=\"center\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">\n";
									$this->salida .= "				<tr>\n";
									$this->salida .= "					<td width='20%' class=\"modulo_table_list_title\">CODIGO</td>\n";
									$this->salida .= "					<td width='30%' class=\"modulo_table_list_title\">NOMBRE</td>\n";
									$this->salida .= "					<td width='40%' class=\"modulo_table_list_title\">INDICACION SUMINISTRO</td>\n";
									$this->salida .= "					<td width='10%' class=\"modulo_table_list_title\">CANTIDAD</td>\n";
									$this->salida .= "					</tr>\n";
									$contador++;
									$cont=0;
									while ($dataMzMed = $resultadoMzMed->FetchNextObject($toupper=false))
									{
										$this->salida .= "						<tr ".$this->Lista($cont).">\n";
										$this->salida .= "							<td width='20%'>".$dataMzMed->medicamento_id."</td>\n";
										$this->salida .= "							<td width='30%'>".strtoupper($this->GetNombMedicamentos($dataMzMed->medicamento_id))."</td>\n";
										if (!empty($dataMzMed->indicaciones_suministro)){
											$this->salida .= "							<td width='40%'>".$dataMzMed->indicaciones_suministro."</td>\n";
										}
										else{
											$this->salida .= "							<td width='40%'>&nbsp;</td>\n";
										}
										$this->salida .= "							<td width='10%' align='center'>".$dataMzMed->cantidad."</td>\n";
										$this->salida .= "						</tr>\n";
										$cont++;
									}
									$this->salida .= "			</table>\n\n";
									$this->salida .= "		<input type='hidden' name='".$this->frmPrefijo."Cant_Medicamentos[]' value='".$cont."'></td>\n";
									$this->salida .= "	</tr>\n";
									$this->salida .= "</table>\n\n";
								}
						}

					$this->salida .= "<br><br>\n";
					$this->salida .= "<div align='center'>\n";
					$this->salida .= "<table width='50%' border='0' class='label'>\n";
					$this->salida .= "	<tr>";
					$this->salida .= "		<td width='50%' align='center'><br><input type='submit' class='input-submit' name='".$this->frmPrefijo."Enviar' value='Guardar'></td>\n";
					$this->salida .= "		<td width='50%' align='center'><br><input type='submit' class='input-submit' name='".$this->frmPrefijo."Cancelar' value='Cancelar'></td>\n";
					$this->salida .= "	</tr>";
					$this->salida .= "</table>\n";
					$this->salida .= "</div>\n";

					$this->salida .= ThemeCerrarTablaSubModulo();
					$this->salida .= "</form>\n\n";
					return true;
			break;
				case $this->frmPrefijo."CreaMezcla":
					list($dbconn) = GetDBconn();
					$bodegas=$this->Bodegas();
					$datos_emp=$this->GetDatosEmpresas();
					$mtz_med=array();
					$mtz_bod=array();

					if (!empty($_REQUEST[$this->frmPrefijo."Fecha"]))  $fecha=$_REQUEST[$this->frmPrefijo."Fecha"];
					else  $fecha=date("Y-m-d H:i:s");

					if (!empty($_REQUEST[$this->frmPrefijo.'Enviar'])){
					unset($_REQUEST[$this->frmPrefijo.'Enviar']);
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."AddLiquidosP",$this->frmPrefijo."Datos"=>$_REQUEST));
						unset($_REQUEST[$this->frmPrefijo.'Enviar']);
						unset($_REQUEST[$this->frmPrefijo.'Add_Medicamentos']);
						/*$this->salida.="<script>\n";
						$this->salida.="	location.href=\"$href\";\n";
						$this->salida.="</script>\n";*/
						$this->FrmForma($this->frmPrefijo."AddLiquidosP");
						return true;
					}

					if ($_REQUEST[$this->frmPrefijo.'Cancelar']==='Cancelar'){
						unset($_REQUEST[$this->frmPrefijo.'Cancelar']);
						$this->FrmForma($this->frmPrefijo.'AddLiquidosP');
						return true;
					}

					if (!empty($_REQUEST[$this->frmPrefijo.'MezclaEliminar'])){
						foreach($_SESSION[$this->frmPrefijo.'MTZ_MEZCLAS'] as $key => $value){
							if (in_array($_SESSION[$this->frmPrefijo.'MTZ_MEZCLAS'][$key]['codigo'],$_REQUEST[$this->frmPrefijo.'MezclaEliminar'])){
								unset($_SESSION[$this->frmPrefijo.'MTZ_MEZCLAS'][$key]);
								unset($_SESSION[$this->frmPrefijo.'MTZ_MEZCLASB'][$key]);
							}
						}
					}

					if (!empty($_REQUEST[$this->frmPrefijo.'datosNombreMedicamento'])){
						$mezcla_medica['mezclas']['codigo']=$_REQUEST[$this->frmPrefijo.'datosIdMedicamento'];
						$mezcla_medica['mezclas']['nombre']=$_REQUEST[$this->frmPrefijo.'datosNombreMedicamento'];
						$mezcla_medica['mezclas']['cantidad']=$_REQUEST[$this->frmPrefijo.'datosCantidadMedicamento'];
						$mezcla_bodegas['bodegas']=$_REQUEST[$this->frmPrefijo.'datosBodega'];
						foreach($_SESSION[$this->frmPrefijo.'MTZ_MEZCLAS'] as $key => $value){
							if ($_SESSION[$this->frmPrefijo.'MTZ_MEZCLAS'][$key]['codigo']==$_REQUEST[$this->frmPrefijo.'datosIdMedicamento']){
								$flag=1;
							}
						}
						if (!$flag){
							$_SESSION[$this->frmPrefijo.'MTZ_MEZCLAS'][]=$mezcla_medica['mezclas'];
							$_SESSION[$this->frmPrefijo.'MTZ_MEZCLASB'][]=$mezcla_bodegas;
						}
						else{
							$this->salida.="<script>\n";
							$this->salida.="	alert('El código del medicamento ya se encuentra registrado.');\n";
							$this->salida.="</script>\n";
						}
						unset($mezcla_medica);
						unset($mezcla_bodegas);
					}

					if (!$bodegas)
					{
						$this->error = "NO EXISTEN BODEGAS";
						$this->mensajeDeError = "El usuario no tiene asignada alguna bodega de medicamentos.";
			 			return false;
					}

					//Query par los medicamentos NO POS de la formula medica y de la justificacion
					$query=$this->GetQueryBodegas($datos_emp['empresa_id'],$datos_emp['centro_utilidad'],$pos=false);
					$bdgas=urlencode(serialize($bodegas));

					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."CreaMezcla"));
					$this->salida .="<form name=\"".$this->frmPrefijo."datos\" action=\"$href\" method=\"POST\" onsubmit='return Valida(this);'>\n";
					$this->salida .= ThemeAbrirTablaSubModulo('CREAR MEZCLAS');

					$this->salida.="<script>\n";
					$this->salida.="function Valida(forma) {\n";
					$this->salida.="	if ((forma.".$this->frmPrefijo."datosNombreMedicamento.value!='' && forma.".$this->frmPrefijo."datosIdMedicamento.value=='' && forma.".$this->frmPrefijo."datosCantidadMedicamento.value=='') || (forma.".$this->frmPrefijo."datosNombreMedicamento.value!='' && forma.".$this->frmPrefijo."datosIdMedicamento.value!='' && forma.".$this->frmPrefijo."datosCantidadMedicamento.value=='')){\n";
					$this->salida.="		alert('Digite la cantidad del medicamento');\n";
					$this->salida.="		forma.".$this->frmPrefijo."datosCantidadMedicamento.focus();\n";
					$this->salida.="		return false;\n}\n";
					$this->salida.="	if (forma.".$this->frmPrefijo."datosNombreMedicamento.value!='' && forma.".$this->frmPrefijo."datosIdMedicamento.value=='' && forma.".$this->frmPrefijo."datosCantidadMedicamento.value!=''){\n";
					$this->salida.="		alert('No se puede insertar el medicamento.');\n";
					$this->salida.="		return false;\n}\n";
					$this->salida.="return true;\n }\n\n";

					$this->salida.="function buscaCampos(campo) {\n";
					$this->salida.="var i=0; var j=0;";
					$this->salida.="while (!i) { if (document.".$this->frmPrefijo."datos.elements[j].name!=campo) j++; else return(j); } \n";
					$this->salida.="return (-1);\n }\n\n";

					$this->salida.="function abrirVentana(forma) {\n";
					$this->salida.="var nombre='';\n";
					$this->salida.="var url2='';\n";
					$this->salida.="var str='';\n";
					$this->salida.="var nombre='Buscador_General';\n";
					$this->salida.="var idTipoCargo=0;\n";
					$this->salida.="var bTipoCargo=0;\n";
					$this->salida.="var bTipoUrl=0;\n";
					$this->salida.="var bTipoQuest='".$query."';\n";
					$this->salida.="var bBodegas='".$bdgas."';\n";
					$this->salida.="var bTipoQuestKey='';\n";
					$this->salida.="var Ancho=screen.width;\n";
					$this->salida.="var Alto=screen.height;\n";
					$this->salida.="var str ='Alto Ancho resizable=no status=no scrollbars=yes';\n";
					$this->salida.="bTipoQuestKey='codigo_producto,descripcion';\n";
					$this->salida.="url2 ='classes/classbuscador/buscador.php?tipo=planT&key='+bTipoQuestKey+'&forma='+forma+'&sql='+bTipoQuest+'&bdgas='+bBodegas; \n";
					$this->salida.="window.open(url2, nombre, str);\n}\n\n";
					$this->salida.="</script>\n";

					$bodegas=$this->Bodegas();
					$resultadoMz=$this->GetGruposMezclas($this->empresa,$datos_emp['centro_utilidad'],$bodegas[0]['estacion_id']);
					if (!$resultadoMz) {
						return false;
					}

					$this->salida .= "<table width='100%' border='0' class='modulo_table_list' align='center'>\n";
					$this->salida .= $this->SetStyle("MensajeError",1);
					$this->salida .= "			<table border='0' width='100%' class='modulo_table_list'>\n";
					$this->salida .= "				<tr>\n";
					$this->salida .= "					<td width='100%' class='modulo_table_list_title'>ADICIONAR MEDICAMENTOS</td>\n";
					$this->salida .= "				</tr>\n";
					$this->salida .= "				<tr>\n";
					$this->salida .= "					<td width='100%' align='center'>\n";
					$this->salida .= "						<table border='0' width='100%' class='modulo_table_list'>\n";
					$this->salida .= "							<tr class='modulo_list_claro'>\n";
					$this->salida .= "								<td align='center'><input type='button' name='".$this->frmPrefijo."Buscar' class='input-bottom' value='Buscar' onClick='abrirVentana(this.form.name);'></td>\n";
					$this->salida .= "								<td align='left'><input type='text' name='".$this->frmPrefijo."datosNombreMedicamento' size='30' class='input-text' readonly='true' value=''></td>\n";
					$this->salida .= "								<td align='left'>Cantidad &nbsp;&nbsp;<input type='text' name='".$this->frmPrefijo."datosCantidadMedicamento' size='15' class='input-text' value=''></td>\n";
					$this->salida .= "							</tr>\n";
					$this->salida .= "							<tr class='modulo_list_claro'>\n";
					$this->salida .= "								<td width='100%' align='center' colspan='3'><input type='submit' name='Insertar' value='Insertar' class='input-bottom'></td>";
					$this->salida .= "							</tr>";
					$this->salida .= "						</table>\n";

					$mezcla_medicamentos=SessionGetVar($this->frmPrefijo.'MTZ_MEZCLAS');
					if (!empty($mezcla_medicamentos)){
						$this->salida .= "							<table width='100%' border='0' class='modulo_table_list' align='center'>";
						$this->salida .= "								<tr class='modulo_list_claro'>\n";
						$this->salida .= "									<td width='5%' class='modulo_table_list_title'>BORRAR</td>\n";
						$this->salida .= "									<td width='15%' class='modulo_table_list_title'>CODIGO</td>\n";
						$this->salida .= "									<td width='65%' class='modulo_table_list_title'>NOMBRE</td>\n";
						$this->salida .= "									<td width='15%' class='modulo_table_list_title'>CANTIDAD</td>\n";
						$this->salida .= "								</tr>\n";
						foreach($mezcla_medicamentos as $key => $value){
							$this->salida .= "								<tr ".$this->Lista($key).">\n";
							$this->salida .= "									<td width='5%' align='center'><input type='checkbox' name='".$this->frmPrefijo."MezclaEliminar[]' value='".$value['codigo']."'></td>\n";
							$this->salida .= "									<td width='15%'>".$value['codigo']."</td>\n";
							$this->salida .= "									<td width='65%'>".$value['nombre']."</td>\n";
							$this->salida .= "									<td width='15%'>".$value['cantidad']."</td>\n";
							$this->salida .= "								</tr>\n";
						}
						$this->salida .= "							</table>\n";
					}

					$this->salida .= "					</td>\n";
					$this->salida .= "				</tr>\n";
					$this->salida .= "				<tr width='100%'>\n";
					$this->salida .= "					<td align='center'>\n";
					$this->salida .= "						<table width='50%' border='0'>\n";
					$this->salida .= "							<tr>";
					$this->salida .= "								<td width='33%' align='center'><br><input type='submit' class='input-submit' name='".$this->frmPrefijo."Enviar' value='Guardar'></td>\n";
					$this->salida .= "								<td width='33%' align='center'><br><input type='submit' class='input-submit' name='".$this->frmPrefijo."Eliminar' value='Eliminar'></td>\n";
					$this->salida .= "								<td width='33%' align='center'><br><input type='submit' class='input-submit' name='".$this->frmPrefijo."Cancelar' value='Cancelar'></td>\n";
					$this->salida .= "							</tr>";
					$this->salida .= "						</table>\n";
					$this->salida .= "					</td>\n";
					$this->salida .= "				</tr>\n";
					$this->salida .= "			</table>\n";
					$this->salida .= "<br><br>\n";

					while ($dataMz = $resultadoMz->FetchNextObject($toupper=false))
					{
						$resultadoMzMed=$this->GetGrupoMezclaMedicamentos($dataMz->grupo_mezcla_id);
							if (!$resultadoMzMed) {
								return false;
							}
							else
							{
								$this->salida .= "<table width='100%' class=\"modulo_table_list\" align=\"center\" border=\"0\">\n";
								$this->salida .= $this->SetStyle("MensajeError","",2);
								$this->salida .= "	<tr>\n";
								$this->salida .= "		<td width='95%' class=\"modulo_table_list_title\" align='center'>".strtoupper($dataMz->descripcion)."</td>\n";
								$this->salida .= "	</tr>\n";
								$this->salida .= "	<tr>\n";
								$this->salida .= "		<td width='100%'>\n";
								$this->salida .= "			<table width='100%' class=\"modulo_table_list\" align=\"center\" border=\"0\">\n";
								$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
								$this->salida .= "					<td width='20%' colspan='2'>CODIGO</td>\n";
								$this->salida .= "					<td width='60%'>MEDICAMENTO</td>\n";
								$this->salida .= "					<td width='20%'>CANTIDAD</td>\n";
								$this->salida .= "				</tr>\n";
								$cont=0;
								while ($dataMzMed = $resultadoMzMed->FetchNextObject($toupper=false))
								{
									$this->salida .= "				<tr ".$this->Lista($cont).">\n";
									$this->salida .= "					<td width='2%' align='center'><input type='checkbox' name='".$this->frmPrefijo."Medicamentos[]' value='".$dataMzMed->medicamento_id."' ></td>\n";
									$this->salida .= "					<td width='13%'>".$dataMzMed->medicamento_id."</td>\n";
									$mtz_med['MTZ_MED']['codigo']=$dataMzMed->medicamento_id;
									$mtz_med['MTZ_MED']['nombre']=$this->GetNombMedicamentos($dataMzMed->medicamento_id);
									$mtz_med['MTZ_MED']['cantidad']=$dataMzMed->cantidad;
									$mtz_bod['MTZ_BOD']=$dataMz->bodega;
									if ($this->ValidaDatoMedSession($this->frmPrefijo.'MTZ_MEDICAMENTOS','codigo',$dataMzMed->medicamento_id)){
										$_SESSION[$this->frmPrefijo.'MTZ_MEDICAMENTOS'][]=$mtz_med['MTZ_MED'];
										$_SESSION[$this->frmPrefijo.'MTZ_MEDICAMENTOS_BODEGA'][]=$mtz_bod;
									}
									$this->salida .= "					<td width='75%'>".strtoupper($this->GetNombMedicamentos($dataMzMed->medicamento_id))."</td>\n";
									$this->salida .= "					<td width='10%' align='center'>".$dataMzMed->cantidad."</td>\n";
									$this->salida .= "				</tr>\n";
									$cont++;
								}
								unset($mtz_med);
								$this->salida .= "			</table>\n";
								$this->salida .= "		</td>\n";
								$this->salida .= "	</tr>\n";
								$this->salida .= "</table>\n";
							}
					}

					$this->salida .= "<input type='hidden' name='".$this->frmPrefijo."datosEsPos' value=''>\n";
					$this->salida .= "<input type='hidden' name='".$this->frmPrefijo."datosIdMedicamento' value=''>\n";
					$this->salida .= "<input type='hidden' name='".$this->frmPrefijo."datosPresentMedicamento' value=''>\n";
					$this->salida .= "<input type='hidden' name='".$this->frmPrefijo."datosFormFarmMedicamento' value=''>\n";
					$this->salida .= "<input type='hidden' name='".$this->frmPrefijo."datosConcMedicamento' value=''>\n";
					$this->salida .= "<input type='hidden' name='".$this->frmPrefijo."datosPrincipioActivo' value=''>\n";
					$this->salida .= "<input type='hidden' name='".$this->frmPrefijo."datosUnidad' value=''>\n";
					$this->salida .= "<input type='hidden' name='".$this->frmPrefijo."datosBodega' value=''>\n";
					$this->salida .= "<input type='hidden' name='".$this->frmPrefijo."datosFecha' value='".$fecha."'>\n";
					$this->salida .= ThemeCerrarTablaSubModulo();
					$this->salida .= "<br>\n";
					$this->salida .= "</form>\n\n";
					return true;
			break;
				case $this->frmPrefijo."GuardarMezcla":
					if (!$this->InsertarMezcla() && !empty($this->frmError)){
						$this->FrmForma($this->frmPrefijo."AddLiquidosP");
					}
					return true;
			break;
				case $this->frmPrefijo."InsertarPos":
					if (!$this->ValidaDatosMed() && !empty($this->frmError)){
						$this->FrmForma($this->frmPrefijo."Add");
					}
					return true;
			break;
				case $this->frmPrefijo."InsertarSuspension":
					if (!$this->InsertarSuspension() && !empty($this->frmError)){
						$this->FrmForma('');
						return false;
					}
					return true;
			break;

				case $this->frmPrefijo."Suspender":

					$DatoMedicamento=$_REQUEST[$this->frmPrefijo."Medicamentos"];

					if (empty($DatoMedicamento))
					{
						$medId=$_REQUEST[$this->frmPrefijo."MedID"];
						$evolucion=$_REQUEST[$this->frmPrefijo."EvoId"];
						$mezcla=$_REQUEST[$this->frmPrefijo."Mezcla"];
					}
					else
					{
						if (empty($_REQUEST[$this->frmPrefijo."Mezcla"])) {
							$mezcla=0;
						}
						else {
							$mezcla=1;
						}
						$medId=$DatoMedicamento["MedID"];
						$evolucion=$DatoMedicamento["EvoId"];
					}
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."InsertarSuspension",$this->frmPrefijo."DatMed"=>$_REQUEST[$this->frmPrefijo."Medicamentos"]));
					$this->salida .= ThemeAbrirTablaSubModulo('SUSPENDER MEDICAMENTO');

					$this->salida .="<form name='".$this->frmPrefijo."datos' action=\"".$href."\" method='POST'>";
					$this->salida .= "<table border='1' align='center' class='modulo_table_list'>\n";
					$this->salida .= $this->SetStyle("MensajeError",'',1);
					$this->salida .= "		<tr>\n";
					$this->salida .= "		<td class='".$this->SetStyle($this->frmPrefijo."NotaSuspension",'',2)."'><b>Observación acerca de la suspensión</b></td>\n";
					$this->salida .= "		</tr>\n";
					$this->salida .= "		<tr>\n";
					$this->salida .= "		<td><textarea name='".$this->frmPrefijo."NotaSuspension' class='textarea' cols='55' rows='5'>".$_REQUEST[$this->frmPrefijo."NotaSuspension"]."</textarea></td>\n";
					$this->salida .= "		</tr>\n";
					$this->salida .= "		<tr>\n";
					$this->salida .= "		<td align='center'><input type='submit' class='input-text' name='".$this->frmPrefijo."Suspender_M' value='Suspender'></td>\n";
					$this->salida .= "		</tr>\n";
					$this->salida .= "</table>\n\n";
					$this->salida .= "		<input type='hidden' class='input-text' name='".$this->frmPrefijo."MedID' value='".$medId."'>\n";
					$this->salida .= "		<input type='hidden' class='input-text' name='".$this->frmPrefijo."EvoId' value='".$evolucion."'>\n";
					$this->salida .= "		<input type='hidden' class='input-text' name='".$this->frmPrefijo."Mezcla' value='".$mezcla."'>\n";
					$this->salida .= "</form>\n\n";
					$this->salida .= ThemeCerrarTablaSubModulo();
					return true;
			break;
				case $this->frmPrefijo."Finalizar":
					if (!$this->Finalizar() && !empty($this->frmError))
					{
            $this->FrmForma('');
					  return false;
					}
					return true;
        break;

				case $this->frmPrefijo."Continuar":
					if (!$this->Continuar() && !empty($this->frmError)){
						$this->FrmForma('');
						return false;
					}
					return true;
			break;
				case $this->frmPrefijo."InsertarJust":

					if ($_REQUEST[$this->frmPrefijo."CancelarJust"]==="Cancelar"){
						$this->salida .= ThemeAbrirTablaSubModulo("CANCELAR JUSTIFICACIÓN","90%");
						$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."GuardarJust"));
						$this->salida .= "<form name='Verficacion_Justificacion' action=\"$href\" method=\"POST\">";
						$this->salida .= "<table width='100%' border='0'>\n";
						$this->salida .= "	<tr>";
						$this->salida .= "		<td colspan='2' align=\"center\" class='label'>";
						$this->salida .= "			<br><br><br>Al Cancelar la Justificación los Medicamentos o Mezclas seleccionados anteriormente serán eliminados.\n";
						$this->salida .= "			<br><br>¿Desea Realmente Cancelar la Justificacion ?<br><br><br>";
						$this->salida .= "		</td>";
						$this->salida .= "	</tr>";
						$this->salida .= "	<tr>";
						$this->salida .= "		<td align='center' width='30%'>";
						$this->salida .= "			<input class='input-submit' type=\"submit\" name='".$this->frmPrefijo."Aceptar_Just' value='Aceptar'>";
						$this->salida .= "		</td>";
						$this->salida .= "		<td align='center' width='30%'>";
						$this->salida .= "			<input class='input-submit' type=\"submit\" name='".$this->frmPrefijo."Cancelar_Just' value='Cancelar'>";
						$this->salida .= "		</td>";
						$this->salida .= "	</tr>";
						$this->salida .= "</table>";
						$this->salida .= "<input type='hidden' name='MezclaId' value='".$_REQUEST['MezclaId']."'>";
						$this->salida .= "<input type='hidden' name='".$this->frmPrefijo."ADDJ' value='".$_REQUEST[$this->frmPrefijo."ADDJ"]."'>";
						$this->salida .= "</form>";
						SessionSetVar("REQUEST_DATOS_JUST",$_REQUEST);
						$this->salida .= ThemeCerrarTablaSubModulo();
					}
					else{
 						$this->FrmForma($this->frmPrefijo."GuardarJust");
					}
					return true;
			break;
				case $this->frmPrefijo."GuardarJust":
					if ($_REQUEST[$this->frmPrefijo."Aceptar_Just"]==="Aceptar"){
						unset($_REQUEST['subModuloAction']);
						SessionDelVar("REQUEST_MED_JUST");
						SessionDelVar("REQUEST_DATOS_JUST");
						$this->GetForma();
					}
					elseif ($_REQUEST[$this->frmPrefijo."Cancelar_Just"]==="Cancelar"){
							$_REQUEST=SessionGetVar("REQUEST_DATOS_JUST");
							$_REQUEST["DatMed"]=SessionGetVar("REQUEST_MED_JUST");
							$this->FrmForma($this->frmPrefijo."AddJust");
							return true;
					}
					else{
						SessionSetVar("REQUEST_DATOS_JUST",$_REQUEST);
						if (!$this->InsertarJust() && !empty($this->frmError)){
							$_REQUEST[$this->frmPrefijo."Error_AddJ"]=1;
							$this->FrmForma($this->frmPrefijo."AddJust");
							return false;
						}
					}
					return true;
			break;
				case $this->frmPrefijo."AddJust":
					$Dx=array();
					$Aux=array();
					$bodegas=array();
					$bodegas=$this->Bodegas();
					$datos_emp=$this->GetDatosEmpresas();

					if (!IncludeLib('datospaciente')){
						$this->error = "Error al cargar la libreria [datospaciente].";
						$this->mensajeDeError = "datospaciente";
						return false;
					}

					if (!empty($_REQUEST[$this->frmPrefijo."Fecha"]))  $fecha=$_REQUEST[$this->frmPrefijo."Fecha"];
					else  $fecha=date("Y-m-d H:i:s");

					if (!$bodegas)
					{
						$this->error = "NO EXISTEN BODEGAS";
						$this->mensajeDeError = "El usuario no tiene asignada alguna bodega de medicamentos.";
			 			return false;
					}
					//Query para los medicamentos POS de la justificacion
					$queryBgas=$this->GetQueryBodegas($this->empresa,$datos_emp['centro_utilidad'],true);
					$bdgas=urlencode(serialize($bodegas));

					if ($_REQUEST[$this->frmPrefijo."ADDJ"]){
						$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."InsertarJust",$this->frmPrefijo."ADDJ"=>1));
						if (!SessionIsSetVar("REQUEST_MED_JUST")){
							SessionSetVar("REQUEST_MED_JUST",$_REQUEST);
						}
					}
					else{
						$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."InsertarJust"));
					}

					$this->salida .="<form name='".$this->frmPrefijo."datos' action='".$href."' method='POST'>";

					$vecTemp=array();
					$resultado=$this->GetJustDxPaciente($this->evolucion);
					if (!$resultado){
						return false;
					}

					while ($dataDx = $resultado->FetchNextObject($toUpper=false))
					{
						array_push($vecTemp,$dataDx->tipo_diagnostico_id,$dataDx->diagnostico_nombre);
						array_push($Dx,$vecTemp);
						unset($vecTemp); $vecTemp=array();
					}

					$datos_hc=GetDatosPaciente("","","",$this->evolucion,"");
					$cantMostrar=ModuloGetVar('hc_submodulo','PlanTerapeutico','AltenativaNoPos');

//					if (sizeof($Dx))
//					{
						$this->salida.="<script>\n var valor=0;";

						$this->salida.="function ir(boton){\n";
						$this->salida.="	var url=document.".$this->frmPrefijo."datos.action;\n";
						$this->salida.="		location.href=url+'&".$this->frmPrefijo."Cancelar='+boton+'&".$this->frmPrefijo."JustificacionMedicamento=1';\n";
						$this->salida.="}\n";

						$this->salida.="function buscaCampos(campo) {\n";
						$this->salida.="var i=0; var j=0;";
						$this->salida.="while (!i) { if (document.datos.elements[j].name!=campo) j++; else return(j); } \n";
						$this->salida.="return (-1);\n }\n\n";

						$this->salida.="function abrirVentana(forma,quest) {\n";
						$this->salida.="var nombre='';\n";
						$this->salida.="var url2='';\n";
						$this->salida.="var str='';\n";
						$this->salida.="var nombre='Buscador_General';\n";
						$this->salida.="var idTipoCargo=0;\n";
						$this->salida.="var bTipoCargo=0;\n";
						$this->salida.="var bTipoUrl=0;\n";
						$this->salida.="var bTipoQuest='".$queryBgas."';\n";
						$this->salida.="var bBodegas='".$bdgas."';\n";
						$this->salida.="var bTipoQuestKey='';\n";
						$this->salida.="var Ancho=screen.width;\n";
						$this->salida.="var Alto=screen.height;\n";
						$this->salida.="var str ='Alto Ancho resizable=no status=no scrollbars=yes';\n";
						$this->salida.="bTipoQuestKey='codigo_producto,descripcion';\n";
						$this->salida.="if (quest=='Dxs'){\n";
						$this->salida.="	url2 ='classes/classbuscador/buscador.php?tipo=diagnostico&pfj='+forma+'&forma='+forma+'&alias='+quest; \n";
						$this->salida.="}\n";
						$this->salida.="else {\n";
						$this->salida.="	url2 ='classes/classbuscador/buscador.php?tipo=planT&key='+bTipoQuestKey+'&forma='+forma+'&alias='+quest+'&sql='+bTipoQuest+'&bdgas='+bBodegas; \n";
						$this->salida.="}\n";

						$this->salida.="window.open(url2, nombre, str); valor=quest;\n\n";
						$this->salida.=" if (quest==0) p(valor);\n";
						$this->salida.="}\n\n";
						$this->salida.="</script>\n";

						$this->salida .= "<div align='center' class='titulo2'><br>JUSTIFICACION DE MEDICAMENTOS NO POS<br><br></div>";

						$datos_user=$this->GetDatosUsuario();
						if (!$datos_user){
							return false;
						}
						$this->salida .= "<table class='hc_table_submodulo_list' width='100%' cellpadding='2' border='1'>\n";
						$this->salida .= $this->SetStyle("MensajeError",'',3);
						$this->salida .= "			<tr>\n";
						$this->salida .= "				<td class='".$this->SetStyle($this->frmPrefijo."DatosGrales",'',3)."' align='center' colspan='3'>I. DATOS GENERALES</td>\n";
						$this->salida .= "			</tr>\n";
						$this->salida .= "			<tr>\n";
						$this->salida .= "				<td>Fecha de diligenciamiento<br><input type='text' class='input-text' name='".$this->frmPrefijo."FechaJusti' value='".$fecha."' size='16' readonly></td>\n";
						$this->salida .= "				<td>Nombre<br><input type='text' class='input-text' name='".$this->frmPrefijo."NombPaciJusti' value='".$datos_hc['primer_nombre']." ".$datos_hc['segundo_nombre']." ".$datos_hc['primer_apellido']." ".$datos_hc['segundo_apellido']."' size='30' maxlength='".(3+strlen($datos_hc['primer_nombre'])+strlen($datos_hc['segundo_nombre'])+strlen($datos_hc['primer_apellido'])+strlen($datos_hc['segundo_apellido']))."' readonly></td>\n";
						$this->salida .= "				<td align='center'>Documento de Identificación<br>";
						$this->salida .= "						<table class='hc_table_submodulo_list' width='100%' border='1'>\n";
						$this->salida .= "							<tr>\n";
						$this->salida .= "								<td align='center'><input class='input-text' type='text' name='".$this->frmPrefijo."TipDocJusti' value='".$datos_hc['tipo_id_paciente']."' size='5' readonly></td>\n";
						$this->salida .= "							</tr>\n";
						$this->salida .= "							<tr>\n";
						$this->salida .= "								<td align='center'><input type='text' class='input-text' name='".$this->frmPrefijo."PaciIdJusti' value='".$datos_hc['paciente_id']."' size='25' maxlength='".strlen($datos_hc['paciente_id'])."' readonly></td>\n";
						$this->salida .= "							</tr>\n";
						$this->salida .= "						</table>\n";
						$this->salida .= "				</td>\n";
						$this->salida .= "			</tr>\n";
						$this->salida .= "			<tr>\n";
						$this->salida .= "				<td>Ciudad<br><input type='text' class='input-text' name='".$this->frmPrefijo."CiudJusti' value='".$datos_hc['municipio']."' size='25' maxlength='".strlen($datos_hc['municipio'])."' readonly></td>\n";
						$this->salida .= "				<td>Dirección<br><input type='text' class='input-text' name='".$this->frmPrefijo."DirJusti' value='".$datos_hc['residencia_direccion']."' size='30' maxlength='".strlen($datos_hc['residencia_direccion'])."' readonly></td>\n";
						$this->salida .= "				<td>Teléfono<br><input type='text' class='input-text' name='".$this->frmPrefijo."TelJusti' value='".$datos_hc['']."' size='".strlen($datos_hc[''])."' readonly></td>\n";
						$this->salida .= "			</tr>\n";
						$this->salida .= "			<tr>\n";
						$this->salida .= "				<td>I.P.S Asignada<br><input type='text' class='input-text' name='".$this->frmPrefijo."IPSJusti' value='Cooemva' size='20' readonly></td>\n";
						$this->salida .= "				<td>Oficina<br><input type='text' class='input-text' name='".$this->frmPrefijo."OficJusti' value='Peñon' size='25' readonly></td>\n";
						$this->salida .= "				<td align='center'>Tipo Afiliado<br>";
						$this->salida .= "						<table class='hc_table_submodulo_list' width='100%' border='1'>\n";
						$this->salida .= "							<tr>\n";
						if ($datos_hc['tipo_afiliado_nombre']==='Cotizante')
							$this->salida .= "								<td>Cot.<input type='radio' name='".$this->frmPrefijo."TipAfiJusti' value='Cot' checked></td>\n";
						else
							$this->salida .= "								<td>Cot.<input type='radio' name='".$this->frmPrefijo."TipAfiJusti' value='Cot'></td>\n";
						if ($datos_hc['tipo_afiliado_nombre']==='Beneficiario')
							$this->salida .= "								<td>Ben.<input type='radio' name='".$this->frmPrefijo."TipAfiJusti' value='Ben' checked></td>\n";
						else
							$this->salida .= "								<td>Ben.<input type='radio' name='".$this->frmPrefijo."TipAfiJusti' value='Ben'></td>\n";
						if ($datos_hc['tipo_afiliado_nombre']==='Otro')
							$this->salida .= "								<td>Otr.<input type='radio' name='".$this->frmPrefijo."TipAfiJusti' value='Otr' checked></td>\n";
						else
							$this->salida .= "								<td>Otr.<input type='radio' name='".$this->frmPrefijo."TipAfiJusti' value='Otr'></td>\n";
						$this->salida .= "							</tr>\n";
						$this->salida .= "						</table>\n";
						$this->salida .= "				</td>\n";
						$this->salida .= "			</tr>\n";
						$this->salida .= "			<tr>\n";
						$this->salida .= "				<td>Nombre Médico<br><input type='text' class='input-text' name='".$this->frmPrefijo."NomMedJusti' value='".$datos_user['nombre']."' size='30' maxlength='".strlen($datos_user['nombre'])."' readonly></td>\n";
						$this->salida .= "				<td>Registro<br><input type='text' class='input-text' name='".$this->frmPrefijo."RegMedJusti' value='".$datos_user['tarjeta_profesional']."' size='32' maxlength='".strlen($datos_user['tarjeta_profesional'])."' readonly></td>\n";
						$this->salida .= "				<td>Especialidad<br><input type='text' class='input-text' name='".$this->frmPrefijo."EspMedJusti' value='".$datos_user['descripcion']."' size='25' maxlength='".strlen($datos_user['descripcion'])."' readonly></td>\n";
						$this->salida .= "			</tr>\n";
						$this->salida .= "</table><br>";

						$vecTemp=array();
						$contador=0;
						$this->salida .= "<table class='modulo_table_list' width='100%' cellpadding='2' border='1'>\n";

						$res=(sizeof($Dx)%$cantMostrar);
						$cant=floor(sizeof($Dx)/$cantMostrar);

						$this->salida .= "			<tr>\n";
						$this->salida .= "				<td class='".$this->SetStyle($this->frmPrefijo."Diagnostico",'',3)."' align='center' colspan='".(2*$cantMostrar)."'>II. DIAGNOSTICOS</td>\n";
						$this->salida .= "			</tr>\n";
						for ($i=0;$i<$cant;$i++)
						{
							$this->salida .= "		<tr>\n";
							for ($j=0;$j<$cantMostrar;$j++)
							{
								$vecTemp=$Dx[$contador];
								$this->salida .= "			<td>".$vecTemp[1]."</td>\n";
								if ($_REQUEST[$this->frmPrefijo."DxJusti"]==$vecTemp[0])
									$this->salida .= "			<td align='center'><input type='radio' name='".$this->frmPrefijo."DxJusti' value='".$vecTemp[0]."' checked='true'></td>\n";
								else
									$this->salida .= "			<td align='center'><input type='radio' name='".$this->frmPrefijo."DxJusti' value='".$vecTemp[0]."'></td>\n";
								$contador++;
							}
							$this->salida .= "		</tr>\n";
						}
						for ($i=0;$i<$res;$i++)
						{
							$vecTemp=$Dx[$contador];
							$this->salida .= "		<tr>\n";
							$this->salida .= "			<td>".$vecTemp[1]."</td>\n";
							if ($_REQUEST[$this->frmPrefijo."DxJusti"]==$vecTemp[0])
								$this->salida .= "			<td align='center'><input type='radio' name='".$this->frmPrefijo."DxJusti' value='".$vecTemp[0]."' checked='true'></td>\n";
							else
								$this->salida .= "			<td align='center'><input type='radio' name='".$this->frmPrefijo."DxJusti' value='".$vecTemp[0]."'></td>\n";
							$this->salida .= "		</tr>\n";
							$contador++;
						}
						$this->salida .= "			<tr>\n";
						$this->salida .= "				<td>\n";
						$this->salida .= "					<table width='100%' border='0'>\n";
						$this->salida .= "						<tr>\n";
						$this->salida .= "							<td align='center'>\n";
						$this->salida .= "								<input type='button' class='input-bottom' name='".$this->frmPrefijo."BuscarDxs' value='Buscar Dx' onclick=\"abrirVentana(this.form.name,'Dxs');\">\n";
						$this->salida .= "							</td>\n";
						$this->salida .= "							<td align='center'>\n";
						$this->salida .= "								<input type='text' class='input-text' name='cargo".$this->frmPrefijo."datos' value='".$_REQUEST["cargo".$this->frmPrefijo."datos"]."' size='70'>\n";
						$this->salida .= "								<input type='hidden' name='codigo".$this->frmPrefijo."datos' value='".$_REQUEST["codigo".$this->frmPrefijo."datos"]."'>\n";
						$this->salida .= "							</td>\n";
						$this->salida .= "						</tr>\n";
						$this->salida .= "					</table>\n";
						$this->salida .= "				</td>\n";
						$this->salida .= "			</tr>\n";
						$this->salida .= "</table><br>";

						if (isset($_REQUEST["DatMed"]))
						{
							$DatMedicamento=$_REQUEST["DatMed"];

							$Med_ID=$DatMedicamento[$this->frmPrefijo."datosIdMedicamento"];
							$dosis_diaria=$this->Posologia($DatMedicamento);
							if (empty($dosis_diaria)){
								$dosis_diaria=number_format ($DatMedicamento[$this->frmPrefijo."cantidad"],1,',','.');
							}

							$Med_Nombre=$DatMedicamento[$this->frmPrefijo.'datosNombreMedicamento'];
							$MedicamentoNPC=$DatMedicamento[$this->frmPrefijo.'datosConcMedicamento'];
							$MedicamentoNPP=$DatMedicamento[$this->frmPrefijo.'datosPresentMedicamento'];
						}
						else
						{
							$Med_ID=$_REQUEST[$this->frmPrefijo."MedicamentoNPID"];
							$Med_Nombre=$_REQUEST[$this->frmPrefijo."MedicamentoNPN"];
							$MedicamentoNPC=$_REQUEST[$this->frmPrefijo."MedicamentoNPC"];
							$MedicamentoNPP=$_REQUEST[$this->frmPrefijo."MedicamentoNPP"];
							$dosis_diaria=$_REQUEST[$this->frmPrefijo."MedicamentoNPD"];
							$dias_tto=$_REQUEST[$this->frmPrefijo."MedicamentoNPDT"];
						}

						$this->salida .= "<table class='hc_table_submodulo_list' width='100%' cellpadding='2' border='1'>\n";
						$this->salida .= "			<tr>\n";
						$this->salida .= "				<td class='".$this->SetStyle($this->frmPrefijo."AlternativasPU",'',3)."' align='center' colspan='3'>III. ALTERNATIVAS POS PREVIAMENTE UTILIZADAS</td>\n";
						$this->salida .= "			</tr>\n";
						$this->salida .= "			<tr>\n";
						if ($_REQUEST[$this->frmPrefijo."AlterJusti"]=="S")
							$this->salida .= "				<td colspan='3'>1. Existe algun medicamento dentro del P.O.S. para el tratamiento de esta patología <b>Si</b> <input type='radio' name='".$this->frmPrefijo."AlterJusti' value='S' checked='true'> <b>No</b> <input type='radio' name='".$this->frmPrefijo."AlterJusti' value='N'> (Si la respuesta en <b>No</b>, pase al paso siguiente.)</td>\n";
						else
							if ($_REQUEST[$this->frmPrefijo."AlterJusti"]=="N" || empty($_REQUEST[$this->frmPrefijo."AlterJusti"]))
								$this->salida .= "				<td colspan='3'>1. Existe algun medicamento dentro del P.O.S. para el tratamiento de esta patología <b>Si</b> <input type='radio' name='".$this->frmPrefijo."AlterJusti' value='S'> <b>No</b> <input type='radio' name='".$this->frmPrefijo."AlterJusti' value='N' checked='true'> (Si la respuesta en <b>No</b>, pase al paso siguiente.)</td>\n";
						$this->salida .= "			</tr>\n";

						//For de una var de ambiente para las posibilidades
						for ($i=0;$i<$cantMostrar;$i++)
						{
							$this->salida .= "			<tr>\n";
							$this->salida .= "				<td colspan='3'><b>Posibilidad terapeutica POS numero [".($i+1)."] para la patología en mención</b><br><br>";
							$this->salida .= "						<table class='hc_table_submodulo_list' width='100%' border='1'>\n";
							$this->salida .= "							<tr>\n";
							$this->salida .= "								<td colspan='3'>Nombre Medicamento<br>\n";
							$this->salida .= "									<table border='1' width='100%'><tr><td align='center'><input type='button' name='".$this->frmPrefijo."BuscarJ".$i."' class='input-bottom' value='Buscar' onClick=\"abrirVentana(this.form.name,'Opt$i');\"></td>";
							$this->salida .= "										<td align='center'><input type='hidden' name='".$this->frmPrefijo."datosIdMedicamentoOpt".$i."' value='".$_REQUEST[$this->frmPrefijo."datosIdMedicamentoOpt".$i]."'><input type='text' name='".$this->frmPrefijo."datosNombreMedicamentoOpt".$i."' value='".$_REQUEST[$this->frmPrefijo."datosNombreMedicamentoOpt".$i]."' size='50' class='input-text' readonly='true'></td>\n";
							$this->salida .= "										</tr></table>\n";
							$this->salida .= "								</td>\n";
							$this->salida .= "							</tr>\n";
							$this->salida .= "							<tr>\n";
							$this->salida .= "										<td width='50%'>Principio Activo<br><input type='text' name='".$this->frmPrefijo."datosPrincipioActivoOpt$i' value='".$_REQUEST[$this->frmPrefijo."datosPrincipioActivoOpt".$i]."' class='input-text' readonly='true'></td>\n";
							$this->salida .= "										<td width='25%'>Dosis/Dia<br><input type='text' name='".$this->frmPrefijo."DosisPJusti".$i."' value='".$_REQUEST[$this->frmPrefijo."DosisPJusti".$i]."' class='input-text' size='2' maxlength='2'></td>\n";
							$this->salida .= "										<td width='25%'>Tiempo Utilizacion(Dias)<br><input type='text' name='".$this->frmPrefijo."TotalPJusti".$i."' value='".$_REQUEST[$this->frmPrefijo."TotalPJusti".$i]."' class='input-text' size='3' maxlength='3'></td>\n";
							$this->salida .= "							</tr>\n";
							$this->salida .= "						</table>\n";
							$this->salida .= "						<b>Respuesta Clinica Observada</b><br><br>\n";
							$this->salida .= "						<table class='hc_table_submodulo_list' width='100%' border='1'>\n";
							$this->salida .= "							<tr>\n";
							if ($_REQUEST[$this->frmPrefijo."MejoraPJusti".$i]=="S")
								$this->salida .= "										<td colspan='3' width='100%'>Mejora<br><b>Si</b><input type='radio' name='".$this->frmPrefijo."MejoraPJusti".$i."' value='S' checked='true'> <b>No</b><input type='radio' name='".$this->frmPrefijo."MejoraPJusti".$i."' value='N'></td>\n";
							else
								if ($_REQUEST[$this->frmPrefijo."MejoraPJusti".$i]=="N" || empty($_REQUEST[$this->frmPrefijo."MejoraPJusti".$i]))
									$this->salida .= "										<td colspan='3' width='100%'>Mejora<br><b>Si</b><input type='radio' name='".$this->frmPrefijo."MejoraPJusti".$i."' value='S'> <b>No</b><input type='radio' name='".$this->frmPrefijo."MejoraPJusti".$i."' value='N' checked='true'></td>\n";

							$this->salida .= "							</tr>\n";
							$this->salida .= "							<tr>\n";
							$this->salida .= "										<td width='33%'>Reacción Secundaria<br><input type='text' name='".$this->frmPrefijo."ReaccionPJusti".$i."' value='".$_REQUEST[$this->frmPrefijo."ReaccionPJusti".$i]."' class='input-text' size='25'></td>\n";
							$this->salida .= "										<td width='33%'>Constraindicación Expresa<br><input type='text' name='".$this->frmPrefijo."ContraPJusti".$i."' value='".$_REQUEST[$this->frmPrefijo."ContraPJusti".$i]."' class='input-text' size='25'></td>\n";
							$this->salida .= "										<td width='34%'>Otra<br><input type='text' name='".$this->frmPrefijo."OtraContraPJusti".$i."' value='".$_REQUEST[$this->frmPrefijo."OtraContraPJusti".$i]."' class='input-text' size='25'></td>\n";
							$this->salida .= "							</tr>\n";
							$this->salida .= "						</table>\n";
							$this->salida .= "				</td>\n";
							$this->salida .= "			</tr>\n";
							$this->salida .= "<input type='hidden' name='".$this->frmPrefijo."datosEsPosOpt$i'>";
							$this->salida .= "<input type='hidden' name='".$this->frmPrefijo."datosUnidadOpt$i'>";
							$this->salida .= "<input type='hidden' name='".$this->frmPrefijo."datosPresentMedicamentoOpt$i' value='".$_REQUEST[$this->frmPrefijo."datosPresentMedicamentoOpt$i"]."'>\n";
							$this->salida .= "<input type='hidden' name='".$this->frmPrefijo."datosFormFarmMedicamentoOpt$i' value='".$_REQUEST[$this->frmPrefijo."datosFormFarmMedicamentoOpt$i"]."'>\n";
							$this->salida .= "<input type='hidden' name='".$this->frmPrefijo."datosConcMedicamentoOpt$i' value='".$_REQUEST[$this->frmPrefijo."datosConcMedicamentoOpt$i"]."'>\n";
							$this->salida .= "<input type='hidden' name='".$this->frmPrefijo."datosBodegaOpt$i' value='".$_REQUEST[$this->frmPrefijo."datosBodegaOpt$i"]."'>\n";
							$this->salida .= "<input type='hidden' name='".$this->frmPrefijo."datosFecha' value='".$fecha."'>\n";
							if (!empty($_REQUEST[$this->frmPrefijo."Error_AddJ"])){
								$this->salida .= "<input type='hidden' name='".$this->frmPrefijo."Error_AddJ' value='1'>\n";
							}
						}
						$this->salida .= "</table><br>";
						$this->salida .= "<input type='hidden' name='".$this->frmPrefijo."CantOpt' value='$i'>";

						$this->salida .= "<table class='hc_table_submodulo_list' width='100%' cellpadding='2' border='1'>\n";
						$this->salida .= "			<tr>\n";
						$this->salida .= "				<td class='".$this->SetStyle($this->frmPrefijo."MedicamentoNPos",'',3)."' align='center' colspan='3'>IV. MEDICAMENTO NO POS SOLICITADO</td>\n";
						$this->salida .= "			</tr>\n";
						$this->salida .= "			<tr>\n";
						$this->salida .= "				<td>Nombre Genérico<br><input type='hidden' name='".$this->frmPrefijo."MedicamentoNPID' value='".$Med_ID."'><input type='text' name='".$this->frmPrefijo."MedicamentoNPN' class='input-text' readonly='true' value='".$Med_Nombre."'></td>\n";
						$this->salida .= "				<td>Concentración (mgs,%,mcg,etc.)<br><input type='text' name='".$this->frmPrefijo."MedicamentoNPC' class='input-text' readonly='true' value='".$MedicamentoNPC."'></td>\n";
						$this->salida .= "				<td>Presentación<br><input type='text' name='".$this->frmPrefijo."MedicamentoNPP' class='input-text' readonly='true' value='".$MedicamentoNPP."'></td>\n";
						$this->salida .= "			</tr>\n";
						$this->salida .= "			<tr>\n";
						$this->salida .= "				<td>Dosis<br><input type='text' name='".$this->frmPrefijo."MedicamentoNPD' class='input-text' readonly='true' value='".$dosis_diaria."'></td>\n";
						$this->salida .= "				<td>Dias de tratamiento<br><input type='text' name='".$this->frmPrefijo."MedicamentoNPDT' class='input-text' value='".$_REQUEST[$this->frmPrefijo.'MedicamentoNPDT']."'></td>\n";//".$dias_tto."
						$this->salida .= "				<td>&nbsp;</td>\n";
						$this->salida .= "			</tr>\n";
						$this->salida .= "</table><br>";

						$criterio_respuesta=$this->GetCriterioRespuesta($Med_ID);
						if (!$criterio_respuesta){
							return false;
						}

						$this->salida .= "<table class='hc_table_submodulo_list' width='100%' cellpadding='2' border='1'>\n";
						$this->salida .= "			<tr>\n";
						$this->salida .= "				<td class='".$this->SetStyle($this->frmPrefijo."CriteriosNOPOSJust",'',3)."' align='center'>V. CRITERIOS QUE JUSTIFICAN LA PRESENTE SOLICITUD</td>\n";
						$this->salida .= "			</tr>\n";

						$contador=0;
						while ($data=$criterio_respuesta->FetchNextObject($toUpper=false))
						{
							$this->salida .= "			<tr>\n";
							if (!$data->sw_criterio_respuesta)
							{
								if (empty($_REQUEST[$this->frmPrefijo."CriterioNPN".$contador]) && $data->respuesta=="S" )
									$this->salida .= "				<td>".$data->descripcion." <b>Si</b> <input type='radio' name='".$this->frmPrefijo."CriterioNPN".$contador."' value='S'  checked> <b>No</b> <input type='radio' name='".$this->frmPrefijo."CriterioNPN".$contador."' value='N'></td>\n";
								elseif ($_REQUEST[$this->frmPrefijo."CriterioNPN".$contador]=="S")
									$this->salida .= "				<td>".$data->descripcion." <b>Si</b> <input type='radio' name='".$this->frmPrefijo."CriterioNPN".$contador."' value='S'  checked> <b>No</b> <input type='radio' name='".$this->frmPrefijo."CriterioNPN".$contador."' value='N'></td>\n";
									elseif (empty($_REQUEST[$this->frmPrefijo."CriterioNPN".$contador]) || empty($_REQUEST[$this->frmPrefijo."CriterioNPN"]) && $data->respuesta=="N")
										$this->salida .= "				<td>".$data->descripcion." <b>Si</b> <input type='radio' name='".$this->frmPrefijo."CriterioNPN".$contador."' value='S'> <b>No</b> <input type='radio' name='".$this->frmPrefijo."CriterioNPN".$contador."' value='N' checked></td>\n";
										elseif ($_REQUEST[$this->frmPrefijo."CriterioNPN".$contador]=="N" || empty($_REQUEST[$this->frmPrefijo."CriterioNPN"]))
											$this->salida .= "				<td>".$data->descripcion." <b>Si</b> <input type='radio' name='".$this->frmPrefijo."CriterioNPN".$contador."' value='S'> <b>No</b> <input type='radio' name='".$this->frmPrefijo."CriterioNPN".$contador."' value='N' checked=></td>\n";
							}
							else
							{
								if ($data->respuesta!=NULL && !empty($_REQUEST[$this->frmPrefijo."CriterioNPN".$contador]))
									$this->salida .= "				<td>".$data->descripcion." <br><textarea cols='85' rows='3' name='".$this->frmPrefijo."CriterioNPN".$contador."' class='textarea' >".$_REQUEST[$this->frmPrefijo."CriterioNPN".$contador]."</textarea></td>\n";
								elseif ($data->respuesta!=NULL && empty($_REQUEST[$this->frmPrefijo."CriterioNPN".$contador]))
									$this->salida .= "				<td>".$data->descripcion." <br><textarea cols='85' rows='3' name='".$this->frmPrefijo."CriterioNPN".$contador."' class='textarea' >".$data->respuesta."</textarea></td>\n";
									else
										$this->salida .= "				<td>".$data->descripcion." <br><textarea cols='85' rows='3' name='".$this->frmPrefijo."CriterioNPN".$contador."' class='textarea' >".$_REQUEST[$this->frmPrefijo."CriterioNPN".$contador]."</textarea></td>\n";
							}
							$this->salida .= "			</tr>\n";
							$contador++;
						}
						$this->salida .= "</table><br>";

						$this->salida .= "<table class='hc_table_submodulo_list' width='100%' cellpadding='2' border='1'>\n";
						$this->salida .= "	<tr>\n";
						$this->salida .= "		<td class='label'>NOTA:</td>\n";
						$this->salida .= "	</tr>\n";
						$this->salida .= "	<tr>\n";
						$this->salida .= "		<td class='label' align='justify'>Para el trámite de esta solicitud es obligatorio el diligenciamiento completo, anexando el original de la formula médica y el resumen de la historia clinica.<br>La entrega del medicamento está sujeta
																		a la aprobación del comité técnico-cientifico, de acuerdo a lo establecido en la resolución 5061 del 23 de diciembre de 1997.</td>\n";
						$this->salida .= "	</tr>\n";
						$this->salida .= "</table><br>";
						$this->salida .= "<div align='center'>\n";
						$this->salida .= "	<table width='50%' border='0'>\n";
						$this->salida .= "		<tr>\n";
						$this->salida .= "			<td width='50%' align='center'>\n";
						$this->salida .= "				<input class='input-submit' type=\"submit\" name='".$this->frmPrefijo."CancelarJust' value='Cancelar'>";
						$this->salida .= "			</td>\n";

						$this->salida .= "			<td width='50%' align='center'><input type='submit' class='input-submit' name'".$this->frmPrefijo."SEND' value='Guardar'></td>\n";
						$this->salida .= "		</tr>\n";
						$this->salida .= "	</table><br><br>\n";
						$this->salida .= "</div>\n";
/*					}
					else {
						$this->salida .= "<table class='hc_table_submodulo' width='100%' cellpadding='2' border='1'>\n";
						$this->salida .= "			<tr>\n";
						$this->salida .= "				<td class='label_error'>MENSAJE DEL SISTEMA<br></td>\n";
						$this->salida .= "			</tr>\n";
						$this->salida .= "			<tr>\n";
						$this->salida .= "				<td class='label' align='justify'>EL PACIENTE NO CUENTA CON DIAGNOSTICOS PARA AGREGARSE A LA JUSTIFICACIÓN.</td>\n";
						$this->salida .= "			</tr>\n";
						$this->salida .= "</table><br>";
						$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
						$this->salida .= "<div align='center'><a href=\"".$href."\">Volver</a></div>";
					}*/
					$this->salida .= "<input type='hidden' name='MezclaId' value='".$_REQUEST['MezclaId']."'>";
					$this->salida .= "</form>";
					return true;
			break;
				case $this->frmPrefijo."ShowJust":
					$datos_emp=$this->GetDatosEmpresas();
					$vecTemp=array();

					$DatMedicamento=$_REQUEST["DatMed"]["Medicamento"];

					$Med_ID=$DatMedicamento["codigo_producto"];
					$dosis_diaria=$this->Posologia($DatMedicamento);
					$dias_tto=$DatMedicamento['dias_tto'];

					/*$dosis_diaria=(24/($DatMedicamento["horario"]/60));
					$cant_pastas=($dosis_diaria*$DatMedicamento["cant"]);
					$dias_tto=floor($DatMedicamento["cantTotal"]/$cant_pastas);
					$resto_dias_tto=($DatMedicamento["cantTotal"]%$cant_pastas);
					if (($resto_dias_tto))
					{
						$dias_tto++;
						$dosis_ultimo_dia=floor($resto_dias_tto/$dosis_diaria);
						$cant_pastas_ultima_dosis=($resto_dias_tto%$dosis_diaria);
					}*/

					$Med_Nombre=$DatMedicamento['descripcion'];
					$MedicamentoNPC=$DatMedicamento['concentracion'];
					$MedicamentoNPP=$DatMedicamento['presentacion'];
					$Pos=$DatMedicamento["Pos"];
					$JustId=$DatMedicamento["justificacion_no_pos_id"];

					$datosJust = $this->GetJustDx($JustId);
					if (!$datosJust){
						return false;
					}

					$datos_hc=array();
					$datos_hc=GetDatosPaciente("","","",$datosJust['evolucion_id'],"");
					$datos_user=$this->GetDatosUsuario($datosJust['usuario_id']);
					if (!$datos_user){
						return false;
					}

					$this->salida .= "<div align='center' class='titulo2'><br>JUSTIFICACION DE MEDICAMENTOS NO POS<br><br></div>";
					$this->salida .= "<table class='hc_table_submodulo_list' width='100%' cellpadding='2' border='1'>\n";
					$this->salida .= $this->SetStyle("MensajeError",'',3);
					$this->salida .= "			<tr>\n";
					$this->salida .= "				<td class='".$this->SetStyle($this->frmPrefijo."DatosGrales",'',3)."' align='center' colspan='3'>I. DATOS GENERALES</td>\n";
					$this->salida .= "			</tr>\n";
					$this->salida .= "			<tr>\n";
					$this->salida .= "				<td>Fecha de diligenciamiento<br><input type='text' class='input-text' name='".$this->frmPrefijo."FechaJusti' value='".$datosJust['fecha']."' size='16' readonly></td>\n";
					$this->salida .= "				<td>Nombre<br><input type='text' class='input-text' name='".$this->frmPrefijo."NombPaciJusti' value='".$datos_hc['primer_nombre']." ".$datos_hc['segundo_nombre']." ".$datos_hc['primer_apellido']." ".$datos_hc['segundo_apellido']."' size='30' maxlength='".(strlen($datos_hc['primer_nombre'])+strlen($datos_hc['segundo_nombre'])+strlen($datos_hc['primer_apellido'])+strlen($datos_hc['segundo_apellido']))."' readonly></td>\n";
					$this->salida .= "				<td align='center'>Documento de Identificación<br>\n";
					$this->salida .= "						<table class='hc_table_submodulo_list' width='100%' border='1'>\n";
					$this->salida .= "							<tr>\n";
					$this->salida .= "								<td align='center'><input class='input-text' type='text' name='".$this->frmPrefijo."TipDocJusti' value='".$datos_hc['paciente_id']."' size='".(strlen($datos_hc['paciente_id'])+1)."' readonly></td>\n";
					$this->salida .= "							</tr>\n";
					$this->salida .= "							<tr>\n";
					$this->salida .= "								<td align='center'><input type='text' class='input-text' name='".$this->frmPrefijo."PaciIdJusti' value='".$datos_hc['tipoidpaciente']."' size='25' maxlength='".strlen($datos_hc['tipoidpaciente'])."' readonly></td>\n";
					$this->salida .= "							</tr>\n";
					$this->salida .= "						</table>\n";
					$this->salida .= "				</td>\n";
					$this->salida .= "			</tr>\n";
					$this->salida .= "			<tr>\n";
					$this->salida .= "				<td>Ciudad<br><input type='text' class='input-text' name='".$this->frmPrefijo."CiudJusti' value='".$datos_hc['municipio']."' size='25' maxlength='".strlen($datos_hc['municipio'])."' readonly></td>\n";
					$this->salida .= "				<td>Dirección<br><input type='text' class='input-text' name='".$this->frmPrefijo."DirJusti' value='".$datos_hc['residencia_direccion']."' size='30' maxlength='".strlen($datos_hc['residencia_direccion'])."' readonly></td>\n";
					$this->salida .= "				<td>Teléfono<br><input type='text' class='input-text' name='".$this->frmPrefijo."TelJusti' value='' size='1' readonly></td>\n";
					$this->salida .= "			</tr>\n";
					$this->salida .= "			<tr>\n";
					$this->salida .= "				<td>I.P.S Asignada<br><input type='text' class='input-text' name='".$this->frmPrefijo."IPSJusti' value='Cooemva' size='20' readonly></td>\n";
					$this->salida .= "				<td>Oficina<br><input type='text' class='input-text' name='".$this->frmPrefijo."OficJusti' value='Peñon' size='25' readonly></td>\n";
					$this->salida .= "				<td align='center'>Tipo Afiliado<br>\n";
					$this->salida .= "						<table class='hc_table_submodulo_list' width='100%' border='1'>\n";
					$this->salida .= "							<tr>\n";
					if ($datos_hc['tipo_afiliado_nombre']==='Cotizante')
						$this->salida .= "								<td>Cot.<input type='radio' name='".$this->frmPrefijo."TipAfiJusti' value='Cot' checked></td>\n";
					else
						$this->salida .= "								<td>Cot.<input type='radio' name='".$this->frmPrefijo."TipAfiJusti' value='Cot'></td>\n";
					if ($datos_hc['tipo_afiliado_nombre']==='Beneficiario')
						$this->salida .= "								<td>Ben.<input type='radio' name='".$this->frmPrefijo."TipAfiJusti' value='Ben' checked></td>\n";
					else
						$this->salida .= "								<td>Ben.<input type='radio' name='".$this->frmPrefijo."TipAfiJusti' value='Ben'></td>\n";
					if ($datos_hc['tipo_afiliado_nombre']==='Otro')
						$this->salida .= "								<td>Otr.<input type='radio' name='".$this->frmPrefijo."TipAfiJusti' value='Otr' checked></td>\n";
					else
						$this->salida .= "								<td>Otr.<input type='radio' name='".$this->frmPrefijo."TipAfiJusti' value='Otr'></td>\n";
					$this->salida .= "							</tr>\n";
					$this->salida .= "						</table>\n";
					$this->salida .= "				</td>\n";
					$this->salida .= "			</tr>\n";
					$this->salida .= "			<tr>\n";
					$this->salida .= "				<td>Nombre Médico<br><input type='text' class='input-text' name='".$this->frmPrefijo."NomMedJusti' value='".$datos_user['nombre']."' size='30' maxlength='".strlen($datos_user['nombre'])."' readonly></td>\n";
					$this->salida .= "				<td>Registro<br><input type='text' class='input-text' name='".$this->frmPrefijo."RegMedJusti' value='".$datos_user['tarjeta_profesional']."' size='32' maxlength='".strlen($datos_user['tarjeta_profesional'])."' readonly></td>\n";
					$this->salida .= "				<td>Especialidad<br><input type='text' class='input-text' name='".$this->frmPrefijo."EspMedJusti' value='".$datos_user['descripcion']."' size='25' maxlength='".strlen($datos_user['descripcion'])."' readonly></td>\n";
					$this->salida .= "			</tr>\n";
					$this->salida .= "</table><br>";

					$this->salida .= "<table class='modulo_table_list' width='100%' cellpadding='2' border='1'>\n";
					$this->salida .= "		<tr>\n";
					$this->salida .= "			<td class='".$this->SetStyle($this->frmPrefijo."Diagnostico",'',3)."' align='center' colspan='".(2*$cantMostrar)."'>II. DIAGNOSTICOS</td>\n";
					$this->salida .= "		</tr>\n";
					$this->salida .= "		<tr>\n";
					$this->salida .= "			<td align='center' colspan='3'><input type='text' size='".(strlen($datosJust['diagnostico_nombre'])+1)."' class='input-text' value='".$datosJust['diagnostico_nombre']."' readonly></td>\n";
					$this->salida .= "		</tr>\n";
					$this->salida .= "</table><br>";

					$this->salida .= "<table class='hc_table_submodulo_list' width='100%' cellpadding='2' border='1'>\n";
					$this->salida .= "			<tr>\n";
					$this->salida .= "				<td class='".$this->SetStyle($this->frmPrefijo."AlternativasPU",'',3)."' align='center' colspan='3'>III. ALTERNATIVAS POS PREVIAMENTE UTILIZADAS</td>\n";
					$this->salida .= "			</tr>\n";

					$resultadoPosT=$this->GetPosibilidadesTer($datosJust['justificacion_no_pos_id'],$datos_emp['centro_utilidad']);
					if (!$resultadoPosT && !is_array($resultadoPosT)){
						return false;
					}

					if (!empty($resultadoPosT)){
						foreach($resultadoPosT as $key => $value){
							foreach ($value as $key1 => $valor){
								$this->salida .= "			<tr>\n";
								$this->salida .= "				<td colspan='3'><b>Posibilidad terapeutica POS numero [".($key1+1)."] para la patología en mención</b><br><br>";
								$this->salida .= "						<table class='hc_table_submodulo_list' width='100%' border='1'>\n";
								$this->salida .= "							<tr>\n";
								$this->salida .= "								<td colspan='3'>Nombre Medicamento<br><input type='text' class='input-text' size='25' readonly='yes' value='".$valor['descripcion']." ".$valor['formfarmnombre']." ".$valor['presentacion']."'></td>\n";
								$this->salida .= "							</tr>\n";
								$this->salida .= "							<tr>\n";
								$this->salida .= "										<td width='50%'>Principio Activo<br><input type='text' class='input-text' size='20' readonly='yes' value='".$valor['principio_activo']."'></td>\n";
								$this->salida .= "										<td width='25%'>Dosis/Dia<br><input type='text' class='input-text' size='3' readonly='yes' value='".$valor['dosis_dia']."'></td>\n";
								$this->salida .= "										<td width='25%'>Tiempo Utilizacion(Dias)<br><input type='text' size='3' readonly='yes' class='input-text' value='".$valor['cantidad_total']."'></td>\n";
								$this->salida .= "							</tr>\n";
								$this->salida .= "						</table>\n";
								$this->salida .= "						<b>Respuesta Clinica Observada</b><br><br>\n";
								$this->salida .= "						<table class='hc_table_submodulo_list' width='100%' border='1'>\n";
								$this->salida .= "							<tr>\n";
								if ($valor['mejora']=="S")
									$this->salida .= "								<td colspan='3' width='100%'>Mejora<br><b><input type='text' size='3' readonly='yes' class='input-text' value='Si'></b></td>\n";
								else
									$this->salida .= "								<td colspan='3' width='100%'>Mejora<br><b><input type='text' size='3' readonly='yes' class='input-text' value='No'></b></td>\n";
								$this->salida .= "							</tr>\n";
								$this->salida .= "							<tr>\n";
								$this->salida .= "										<td width='33%'>Reacción Secundaria<br><input type='text' size='25' readonly='yes' class='input-text' value='".$valor['reaccion_secundaria']."'></td>\n";
								$this->salida .= "										<td width='33%'>Constraindicación Expresa<br><input type='text' size='25' readonly='yes' class='input-text' value='".$valor['contraindicacion']."'></td>\n";
								$this->salida .= "										<td width='34%'>Otra<br><input type='text' size='25' readonly='yes' class='input-text' value='".$valor['otra']."'></td>\n";
								$this->salida .= "							</tr>\n";
								$this->salida .= "						</table>\n";
								$this->salida .= "				</td>\n";
								$this->salida .= "			</tr>\n";
							}
						}
					}
					else{
						$this->salida .= "			<tr>\n";
						$this->salida .= "				<td class='label' align='center' colspan='3'>NINGUNA</td>\n";
						$this->salida .= "			</tr>\n";
					}
					$this->salida .= "</table><br>";

					$this->salida .= "<table class='hc_table_submodulo_list' width='100%' cellpadding='2' border='1'>\n";
					$this->salida .= "			<tr>\n";
					$this->salida .= "				<td class='".$this->SetStyle($this->frmPrefijo."MedicamentoNOPOSJust",'',3)."' align='center' colspan='3'>IV. MEDICAMENTO NO POS SOLICITADO</td>\n";
					$this->salida .= "			</tr>\n";
					$this->salida .= "			<tr>\n";
					$this->salida .= "				<td>Nombre Genérico<br><input type='text' size='40' readonly='yes' class='input-text' value='".$Med_Nombre."'></td>\n";
					$this->salida .= "				<td>Concentración (mgs,%,mcg,etc.)<br><input type='text' size='10' readonly='yes' class='input-text' value='".$MedicamentoNPC."'></td>\n";
					$this->salida .= "				<td>Presentación<br><input type='text' size='15' readonly='yes' class='input-text' value='".$MedicamentoNPP."'></td>\n";
					$this->salida .= "			</tr>\n";
					$this->salida .= "			<tr>\n";
					$this->salida .= "				<td>Dosis<br><input type='text' size='".strlen($dosis_diaria)."' readonly='yes' class='input-text' value='".$dosis_diaria."'></td>\n";
					$this->salida .= "				<td>Dias de tratamiento<br><input type='text' size='3' readonly='yes' class='input-text' value='".$dias_tto."'></td>\n";
					if ($resto_dias_tto && $cant_pastas_ultima_dosis)
					{
						$this->salida .= "				<td wrap>Detalle del tratamiento<br><b>El último día del tratamiento, el número de dosis son [".$dosis_ultimo_dia."] y en la última dosis
																				la cantidad del medicamento son [".$cant_pastas_ultima_dosis."]</b></td>\n";
					}
					else
						if ($resto_dias_tto && !$cant_pastas_ultima_dosis)  $this->salida .= "				<td wrap>Detalle del tratamiento<br><b>El último día del tratamiento, el número de dosis son [".$dosis_ultimo_dia."].</b></td>\n";
						else  $this->salida .= "				<td>&nbsp;</td>\n";
					$this->salida .= "			</tr>\n";
					$this->salida .= "</table><br>";

					$resultado=$this->GetJustCriteriosRespuesta($datosJust['justificacion_no_pos_id']);
					if (!$resultado){
						return false;
					}

					$this->salida .= "<table class='hc_table_submodulo_list' width='100%' cellpadding='2' border='1'>\n";
					$this->salida .= "			<tr>\n";
					$this->salida .= "				<td class='".$this->SetStyle($this->frmPrefijo."CriteriosNOPOSJust",'',3)."' align='center'>V. CRITERIOS QUE JUSTIFICAN LA PRESENTE SOLICITUD</td>\n";
					$this->salida .= "			</tr>\n";

					$contador=0;
					while ($data=$resultado->FetchNextObject($toUpper=false))
					{
						$this->salida .= "			<tr>\n";
						if (!$data->cjcriterio_respuesta)
						{
							if ($data->criterio_respuesta=="S")
								$this->salida .= "				<td>".$data->descripcion." <b>Si</b></td>\n";
							else
								if ($data->criterio_respuesta=="N" || empty($data->criterio_respuesta))
									$this->salida .= "				<td>".$data->descripcion." <b>No</b></td>\n";
						}
						else  $this->salida .= "				<td>".$data->descripcion." <br><textarea cols='85' rows='3' class='textarea' readonly>".$data->respuesta."</textarea></td>\n";
						$this->salida .= "			</tr>\n";
						$contador++;
					}
					$this->salida .= "</table><br>";

					$this->salida .= "<table class='hc_table_submodulo_list' width='100%' cellpadding='2' border='1'>\n";
					$this->salida .= "			<tr>\n";
					$this->salida .= "				<td class='label'>NOTA:</td>\n";
					$this->salida .= "			</tr>\n";
					$this->salida .= "			<tr>\n";
					$this->salida .= "				<td class='label' align='justify'>Para el trámite de esta solicitud es obligatorio el diligenciamiento completo, anexando el original de la formula médica y el resumen de la historia clinica.<br>La entrega del medicamento está sujeta
																	a la aprobación del comité técnico-cientifico, de acuerdo a lo establecido en la resolución 5061 del 23 de diciembre de 1997.</td>\n";
					$this->salida .= "			</tr>\n";
					$this->salida .= "</table><br>";

					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
					$this->salida .= "<form action=\"$href\" name=\"Volver\" method=\"post\">\n";
					$this->salida .= "<br>\n";
					$this->salida .= "<div align='center' class='normal_10'><input class='input-submit' type='submit' name='".$this->frmPrefijo."Volver' value='Volver'></div>\n";
					$this->salida .= "</form><br>\n";
					return true;
			break;


			case $this->frmPrefijo."SumiMedica":
					list($dbconn) = GetDBconn();

					$fecha=date("Y-m-d H:i:s");
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."InsertarNota"));
					$this->salida .="<form name='Notas' action='".$href."' method='POST'>";
					$this->salida .= ThemeAbrirTablaSubModulo('DETALLES DEL SUMINISTRO');
					$Mezcla=array();

					$Mezcla=$_REQUEST[$this->frmPrefijo."Mezcla"];
					if (!$Mezcla[0])
					{
						$medicamentos=array();
						$medicamentos=$_REQUEST[$this->frmPrefijo."Medicamentos"];
						$this->salida .= "<table width=\"100%\" class='modulo_table_list' border=\"0\" align='left'>\n";
						$this->salida .= $this->SetStyle("MensajeError",'',2);
						$this->salida .= "	<tr>\n";
						$this->salida .= "		<td class=\"".$this->SetStyle($this->frmPrefijo."medicamentoID",'',2)."\">Codigo: </td>\n";
						$this->salida .= "		<td>".$medicamentos['codigo_producto']."</td>\n";
						$this->salida .= "	</tr>\n";
						$this->salida .= "	<tr>\n";
						$this->salida .= "		<td class=\"".$this->SetStyle($this->frmPrefijo."nombreMedicamento",'',2)."\">Nombre: </td>\n";
						$this->salida .= "		<td>".$medicamentos['descripcion']." ".$medicamentos['formfarmnombre']." ".$medicamentos['concentracion']." [".$medicamentos['presentacion']."]</td>\n";
						$this->salida .= "	</tr>\n";
						$this->salida .= "	<tr>\n";
						$this->salida .= "		<td class=\"".$this->SetStyle($this->frmPrefijo."cantidadTotal",'',2)."\">Cantidad</td>\n";
						if (!empty($medicamentos['unidad_dosis']))
						{
							$unidad=$this->GetViasAdmonUds($medicamentos['via_administracion_id'],$medicamentos['unidad_dosis']);
							if (!$unidad){
								return false;
							}
							$unidad="[".$unidad."]";
						}
						$this->salida .= "		<td>".$medicamentos['cantidad_total']." ".$unidad."</td>\n";
						$this->salida .= "	</tr>\n";
						$this->salida .= "	<tr>\n";
						$this->salida .= "		<td class=\"".$this->SetStyle($this->frmPrefijo."Dosis",'',2)."\">Dosis</td>\n";
						$this->salida .= "		<td>".$this->Posologia($medicamentos)."</td>\n";
						$this->salida .= "	</tr>\n";
						$this->salida .= "	<tr>\n";
						$this->salida .= "		<td class=\"".$this->SetStyle($this->frmPrefijo."viaAdm",'',2)."\">Via administración: </td>\n";

						if (!empty($medicamentos['via_administracion_id']))
						{
							$resultado=$this->GetViasAdmon($medicamentos['via_administracion_id']);
							if (!$resultado){
								return false;
							}
							$data = $resultado->FetchNextObject($toupper=false);
							$vias="[".$data->nombre."]";
						}
						$this->salida .= "		<td>".$vias."</td>\n";
						$this->salida .= "	</tr>\n";
						$this->salida .= "	<tr>\n";
						$this->salida .= "		<td class='label'>Cantidad Suministrada: </td>\n";
						$this->salida .= "		<td>95%</td>\n";
						$this->salida .= "	</tr>\n";
						$this->salida .= "	<tr>\n";
						$this->salida .= "		<td class=\"".$this->SetStyle($this->frmPrefijo."comentario",'',2)."\">Observaciones: </td>\n";
						if (!empty($medicamentos['observaciones']))
							$this->salida .= "		<td>".$medicamentos['observaciones']."</td>\n";
						else
							$this->salida .= "		<td>Sin observaciones</td>\n";
						$this->salida .= "	</tr>\n";
						if (!empty($medicamentos['indicacion_suministro']))
						{
							$this->salida .= "	<tr>\n";
							$this->salida .= "		<td class=\"".$this->SetStyle($this->frmPrefijo."comentario",'',2)."\">Indicaciones de Suminstro: </td>\n";
							$this->salida .= "		<td>".$medicamentos['indicacion_suministro']."</td>\n";
							$this->salida .= "	</tr>\n";
						}
						$this->salida .= "	<tr>\n";
						$this->salida .= "		<td colspan=\"2\">\n";
						$this->salida .= "		<table width='100%' class=\"tabla\" align=\"center\" border='0'>\n";
						$this->salida .= "			<tr>\n";
						$this->salida .= "			<td>\n";
						$this->salida .= "			<table width='100%' class=\"modulo_table_list\" align=\"center\" border='0'>\n";
						$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
						$this->salida .= "					<td>FECHA</td>\n";
						$this->salida .= "					<td>HORA</td>\n";
						$this->salida .= "					<td>DOSIS</td>\n";
						$this->salida .= "					<td>USUARIO</td>\n";
						$this->salida .= "				</tr>\n";
						for ($i=0;$i<1;$i++)
						{
							$this->salida .= "				<tr class='modulo_list_oscuro'>";
							$this->salida .= "					<td>FECHA</td>\n";
							$this->salida .= "					<td>HORA</td>\n";
							$this->salida .= "					<td>DOSIS</td>\n";
							$this->salida .= "					<td>USUARIO</td>\n";
							$this->salida .= "				</tr>\n";
						}
						$this->salida .= "			</table>\n";
						$this->salida .= "		</td>\n";
						$this->salida .= "		</tr>\n";
						$this->salida .= "		</table>\n";
						$this->salida .= "		</td>\n";
						$this->salida .= "	</tr>\n";
						$this->salida .= "	<tr>\n";
						$this->salida .= "		<td colspan=\"2\">\n<br>";
						$this->salida .= "			<table width='100%' align=\"center\" border='0'>\n";
						$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
						$this->salida .= "					<td>NOTAS MEDICAS</td>\n";
						$this->salida .= "				</tr>\n";
						$this->salida .= "				<tr>\n";

							$resultado=$this->GetSuministroMedNotas($medicamentos['medicamento_id'],$medicamentos['evolucion_id'],1);
							if (!$resultado){
								return false;
							}
							$this->salida .= "					<td align='justify'>\n";
							if (!$resultado->RecordCount())
								$this->salida .= "&nbsp -- <br>";
							else
							{
								while($data = $resultado->FetchNextObject($toupper=false)){
									$resultadoUser=$this->GetDatosUsuario($data->usuario_id);
									if (!$resultadoUser){
										return false;
									}
									$this->salida .= "[".$data->fecha."] - [".$resultadoUser['nombre']." / ".$resultadoUser['descripcion']."]<br>".$data->nota."<br><hr>";
								}
							}
						$this->salida .= "					</td>\n";
						$this->salida .= "				</tr>\n";
						$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
						$this->salida .= "					<td>NOTAS ENFERMERIA</td>\n";
						$this->salida .= "				</tr>\n";
						$this->salida .= "				<tr>\n";

							$resultado=$this->GetSuministroMedNotas($medicamentos['medicamento_id'],$medicamentos['evolucion_id'],2);
							if (!$resultado){
								return false;
							}
							$this->salida .= "					<td align='justify'>\n";
							if (!$resultado->RecordCount())
								$this->salida .= "&nbsp -- <br>";
							else
							{
								while($data = $resultado->FetchNextObject($toupper=false))
								{
									$resultadoUser=$this->GetDatosUsuario($data->usuario_id);
									if (!$resultadoUser){
										return false;
									}
									$this->salida .= "[".$data->fecha."] - [".$resultadoUser['nombre']." / ".$resultadoUser['descripcion']."]<br>".$data->nota."<br><hr>";
								}
							}
						$this->salida .= "					</td>\n";
						$this->salida .= "				</tr>\n";
						if ($_REQUEST[$this->frmPrefijo.'Accion']!=1){
							$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
							$this->salida .= "					<td>NOTA</td>\n";
							$this->salida .= "				</tr>\n";
							$this->salida .= "				<tr>\n";
							$this->salida .= "					<td align='center'><textarea class='textarea' name='".$this->frmPrefijo."notaDetSum' cols='65' rows='5'></textarea>\n";
							$this->salida .= "					<br><br><input type='submit' class='input-bottom' name='".$this->frmPrefijo."IngresarNota' value='IngresarNota'><br></td>\n";
							$this->salida .= "				</tr>\n";
						}
						$this->salida .= "			</table>\n\n";
						$this->salida .= "		<input type='hidden' name='".$this->frmPrefijo."Medicamento_id' value='".$medicamentos['codigo_producto']."'>\n";
						$this->salida .= "		<input type='hidden' name='".$this->frmPrefijo."evolucion_id' value='".$medicamentos['evolucion_id']."'>\n";
						$this->salida .= "		<input type='hidden' name='".$this->frmPrefijo."Fecha' value='".$fecha."'>\n";
						$this->salida .= "		<input type='hidden' name='".$this->frmPrefijo."Perfil' value='".$this->tipo_profesional."'>\n";
						$this->salida .= "		</td>\n";
						$this->salida .= "	</tr>\n";

					}//End if (!mezclas)
					else
					{//Si es una mezcla
						$medicamentos=array();
						$medicamentos=$_REQUEST[$this->frmPrefijo."Medicamentos"];
						$unidad="";
						$this->salida .= "<table width=\"100%\" class='modulo_table_list' border=\"0\" align='left'>\n";
						$this->salida .= $this->SetStyle("MensajeError",'',2);
						$this->salida .= "	<tr>\n";
						$this->salida .= "		<td class=\"".$this->SetStyle($this->frmPrefijo."cantidadTotal",'',2)."\">Cantidad</td>\n";
						$this->salida .= "		<td>".$medicamentos['cantidad']." ".$unidad."</td>\n";
						$this->salida .= "	</tr>\n";
						$this->salida .= "	<tr>\n";
						$this->salida .= "		<td class=\"".$this->SetStyle($this->frmPrefijo."Dosis",'',2)."\">Dosis</td>\n";
						$this->salida .= "		<td>".$this->PosologiaMezcla($medicamentos)."</td>\n";
						$this->salida .= "	</tr>\n";
						$this->salida .= "	<tr>\n";
						$this->salida .= "		<td class='label'>Cantidad Suministrada: </td>\n";
						$this->salida .= "		<td>95%</td>\n";
						$this->salida .= "	</tr>\n";
						$this->salida .= "	<tr>\n";
						$this->salida .= "		<td class=\"".$this->SetStyle($this->frmPrefijo."comentario",'',2)."\">Observaciones: </td>\n";
						if (!empty($medicamentos['observaciones']))
							$this->salida .= "		<td>".$medicamentos['observaciones']."</td>\n";
						else
							$this->salida .= "		<td>Sin observaciones</td>\n";
						$this->salida .= "	</tr>\n";
						if (!empty($medicamentos['indicacion_suministro']))
						{
							$this->salida .= "	<tr>\n";
							$this->salida .= "		<td class=\"".$this->SetStyle($this->frmPrefijo."comentario",'',2)."\">Indicaciones de Suminstro: </td>\n";
							$this->salida .= "		<td>".$medicamentos['indicacion_suministro']."</td>\n";
							$this->salida .= "	</tr>\n";
						}

						if (!empty($medicamentos['mezcla_recetada_id']))
						{
							$resultado=$this->GetMezclas($medicamentos['mezcla_recetada_id']);
							if (!$resultado){
								return false;
							}
							if ($resultado->RecordCount())
							{
								$this->salida .= "	<tr>\n";
								$this->salida .= "		<td colspan=\"2\"><br>\n";
								$this->salida .= "			<table width='100%' class=\"modulo_table_list\" align=\"center\" border='0'>\n";
								$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
								$this->salida .= "					<td>MEDICAMENTO</td>\n";
								$this->salida .= "					<td>CANTIDAD</td>\n";
								$this->salida .= "					<td>INDICACION SUMINISTRO</td>\n";
								$this->salida .= "					<td>MEDICAMENTO POS</td>\n";
								$this->salida .= "				</tr>\n";
								$cont=0;
								while ($data=$resultado->FetchNextObject($toupper=false))
								{
									$this->salida .= "				<tr ".$this->Lista($cont).">";
									$this->salida .= "					<td>".$this->GetNombMedicamentos($data->medicamento_id)."</td>\n";
									$this->salida .= "					<td>".$data->cantidad."</td>\n";
									$this->salida .= "					<td>".$data->indicacion_suministro."</td>\n";
									if ($data->sw_pos)
										$this->salida .= "					<td>Medicamento P.O.S</td>\n";
									else
										$this->salida .= "					<td>Medicamento NO P.O.S</td>\n";
									$this->salida .= "				</tr>\n";
									$cont++;
								}
								$this->salida .= "			</table>\n";
								$this->salida .= "		</td>\n";
								$this->salida .= "	</tr>\n";
							}
						}

						$this->salida .= "	<tr>\n";
						$this->salida .= "		<td colspan=\"4\">\n";
						$this->salida .= "			<table width='100%' class=\"modulo_table_list\" align=\"center\" border='0'>\n";
						$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
						$this->salida .= "					<td>FECHA</td>\n";
						$this->salida .= "					<td>HORA</td>\n";
						$this->salida .= "					<td>DOSIS</td>\n";
						$this->salida .= "					<td>USUARIO</td>\n";
						$this->salida .= "				</tr>\n";
						for ($i=0;$i<1;$i++)
						{
							$this->salida .= "				<tr ".$this->Lista($i).">";
							$this->salida .= "					<td>FECHA</td>\n";
							$this->salida .= "					<td>HORA</td>\n";
							$this->salida .= "					<td>DOSIS</td>\n";
							$this->salida .= "					<td>USUARIO</td>\n";
							$this->salida .= "				</tr>\n";
						}
						$this->salida .= "			</table>\n";
						$this->salida .= "		</td>\n";
						$this->salida .= "	</tr>\n";
						$this->salida .= "	<tr>\n";
						$this->salida .= "		<td colspan=\"2\"><br>\n";
						$this->salida .= "			<table width='100%' align=\"center\" border='0'>\n";
						$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
						$this->salida .= "					<td>NOTAS MEDICAS</td>\n";
						$this->salida .= "				</tr>\n";
						$this->salida .= "				<tr>\n";
							$resultado=$this->GetSuministroMezclasNotas($medicamentos['mezcla_recetada_id'],$medicamentos['evolucion_id'],1);
							if (!$resultado){
								return false;
							}
							$this->salida .= "					<td align='justify'>\n";
							if (!$resultado->RecordCount())
								$this->salida .= "&nbsp -- <br>";
							else
							{
								while($data = $resultado->FetchNextObject($toupper=false))
								{
									$resultadoUser=$this->GetDatosUsuario($data->usuario_id);
									if (!$resultadoUser){
										return false;
									}
									$this->salida .= "[".$data->fecha."] - [".$resultadoUser['nombre']." / ".$resultadoUser['descripcion']."]<br>".$data->nota."<br><hr>";
								}
							}
						$this->salida .= "					</td>\n";
						$this->salida .= "				</tr>\n";
						$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
						$this->salida .= "					<td>NOTAS ENFERMERIA</td>\n";
						$this->salida .= "				</tr>\n";
						$this->salida .= "				<tr>\n";
							$resultado=$this->GetSuministroMezclasNotas($medicamentos['mezcla_recetada_id'],$medicamentos['evolucion_id'],2);
							if (!$resultado){
								return false;
							}
							$this->salida .= "					<td align='justify'>\n";
							if (!$resultado->RecordCount())
								$this->salida .= "&nbsp -- <br>";
							else
							{
								while($data = $resultado->FetchNextObject($toupper=false))
								{
									$resultadoUser=$this->GetDatosUsuario($data->usuario_id);
									if (!$resultadoUser){
										return false;
									}
									$this->salida .= "[".$data->fecha."] - [".$resultadoUser['nombre']." / ".$resultadoUser['descripcion']."]<br>".$data->nota."<br><hr>";
								}
							}
						$this->salida .= "					</td>\n";
						$this->salida .= "				</tr>\n";
						if ($_REQUEST[$this->frmPrefijo.'Accion']!=1){
							$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
							$this->salida .= "					<td>NOTA</td>\n";
							$this->salida .= "				</tr>\n";
							$this->salida .= "				<tr>\n";
							$this->salida .= "					<td align='center'><textarea class='textarea' name='".$this->frmPrefijo."notaDetSum' cols='65' rows='5'></textarea>\n";
							$this->salida .= "						<br><br><input type='submit' class='input-submit' name='".$this->frmPrefijo."IngresarNota' value='Ingresar Nota Mezcla'><br></td>\n";
							$this->salida .= "				</tr>\n";
						}
						$this->salida .= "			</table>\n\n";
						$this->salida .= "			<input type='hidden' name='".$this->frmPrefijo."evolucion_id' value='".$medicamentos['evolucion_id']."'>\n";
						$this->salida .= "			<input type='hidden' name='".$this->frmPrefijo."Fecha' value='".$fecha."'>\n";
						$this->salida .= "			<input type='hidden' name='".$this->frmPrefijo."Perfil' value='".$this->tipo_profesional."'>\n";
						$this->salida .= "			<input type='hidden' name='".$this->frmPrefijo."Mezcla_id' value='".$medicamentos['mezcla_recetada_id']."'>\n";
						$this->salida .= "		</td>\n";
						$this->salida .= "	</tr>\n";
					}//End else (Mezcla)


					$this->salida .= "</table>.\n";
					$this->salida .= "</form>\n";

					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
					$this->salida .= "<form action=\"$href\" name=\"Volver\" method=\"post\">\n";
					$this->salida .= "<div align='center' class='normal_10'><br><input class='input-submit' type='submit' name='".$this->frmPrefijo."Volver' value='Volver'></div>\n";
					$this->salida .= "</form>\n";
					$this->salida .= ThemeCerrarTablaSubModulo();
					return true;
			break;

			case $this->frmPrefijo."InsertarNota":
					if (!$this->InsertarNota() && !empty($this->frmError)){
						$this->FrmForma($this->frmPrefijo."SumiMedica");
					}
					else{
						unset($_REQUEST['subModuloAction']);
					  $this->FrmForma('');
					}
					return true;
			break;
               default :

                    $consultaMz=$this->ShowPlanMedicamentosMezclas(2);

                    if ($consultaMz==="ShowMensaje")
                    {
                         return true;
                    }

                    if ($consultaMz===false)
                    {
                         return false;
                    }

                    $this->frmForma_Add();
                    return true;
                    break;
			}
		}

		function ShowPlanMedicamentos($action)
		{
			$consulta="";
			list($dbconn) = GetDBconn();
			$vecPlanMedicamentos = array();
			$vecPlanMedicamentos = $this->ObtenerPlanTerapeutico($action);

			if (is_array($vecPlanMedicamentos) && !sizeof($vecPlanMedicamentos) && $action==2)
			{
				$consulta .= ThemeAbrirTablaSubModulo('MEDICAMENTOS INEXISTENTES');
				$consulta .="<b>El paciente no tiene medicamentos relacionados</b>";
				//Para que solo pueda ingresar si la evolucion esta activa
				if ($this->estado)
				{
					$consulta .="<br><table width='80%' align='center' border='0' class='modulo_table_list'>";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."Add"));
					$consulta .= "				<tr><td align='center'><a href='".$href."'>Adicionar Medicamentos</a></td></tr>\n";
					$consulta .= "</table>\n\n";
				}
				$consulta .= ThemeCerrarTablaSubModulo();
				return $consulta;
			}
			elseif (($vecPlanMedicamentos===false && $action==2) || ($vecPlanMedicamentos===false && $action==1))
			{
				return false;
			}
			elseif (empty($vecPlanMedicamentos) && $action==1)
			{
				return "ShowMensaje";
			}
			elseif ($vecPlanMedicamentos)
			{
				$consulta .= ThemeAbrirTablaSubModulo('MEDICAMENTOS');
				$consulta .= "<table width='100%' class=\"modulo_table_list\" align=\"center\" border=\"0\">\n";
				$consulta .= "	<tr class=\"modulo_table_list_title\">\n";
				$consulta .= "		<td>FECHA</td>\n";
				$consulta .= "		<td>MEDICAMENTO</td>\n";
				$consulta .= "		<td>CANT.</td>\n";
				$consulta .= "		<td>VIA</td>\n";
				$consulta .= "		<td>ESTADO</td>\n";
				$consulta .= "		<td>COMENTARIO</td>\n";
				$consulta .= "		<td>EVOLUCION</td>\n";
				$consulta .= "		<td>MEDICAMENTO POS</td>\n";
				$consulta .= "		<td>ACCIÓN</td>\n";
				$consulta .= "		<td>DETALLE SUMINISTRO</td>\n";
				$consulta .= "  </tr>\n";

				for($i=0; $i<sizeof($vecPlanMedicamentos); $i++)
				{
					$consulta .= "<tr ".$this->Lista($i).">";
					$consulta .= "<td align='center'>".$vecPlanMedicamentos[$i]['fecha']."</td>\n";
					$consulta .= "<td>".$vecPlanMedicamentos[$i]['descripcion']." ".$vecPlanMedicamentos[$i]['unidescripcion']." ".$vecPlanMedicamentos[$i]['presentacion']."</td>\n";
					$consulta .= "<td align='center'>".$vecPlanMedicamentos[$i]['cantidad_total']."</td>\n";
					$consulta .= "<td>".$vecPlanMedicamentos[$i]['vianombre']."</td>\n";
					if($vecPlanMedicamentos[$i]['sw_estado'] == '0')
					{
						$consulta .= "<td align='center'><font color=\"#990000\">Suspendido</font></td>\n";
						if (empty($vecPlanMedicamentos[$i]['observaciones']) && empty($vecPlanMedicamentos[$i]['indicacion_suministro']))
							$consulta .= "<td>".$vecPlanMedicamentos[$i]['nota_suspension']."</td>\n";
						else
							if (!empty($vecPlanMedicamentos[$i]['indicacion_suministro']))
								$consulta .= "<td><b>Nota Suspensión</b><br>".$vecPlanMedicamentos[$i]['nota_suspension']."<hr>".$vecPlanMedicamentos[$i]['observaciones']."<br><b>Indicaciones de suministro</b><br>".$vecPlanMedicamentos[$i]['indicacion_suministro']."</td>\n";
							else
								$consulta .= "<td><b>Nota Suspensión</b><br>".$vecPlanMedicamentos[$i]['nota_suspension']."<hr>".$vecPlanMedicamentos[$i]['observaciones']."</td>\n";
					}
					elseif ($vecPlanMedicamentos[$i]['sw_estado'] == '1')
					{
						$consulta .= "		<td align='center'>Finalizado</td>\n";
						if (empty($vecPlanMedicamentos[$i]['observaciones']) && empty($vecPlanMedicamentos[$i]['indicacion_suministro']))
							$consulta .= "		<td>Plan Finalizado</td>\n";
						else
							if (!empty($vecPlanMedicamentos[$i]['indicacion_suministro']))
								$consulta .= "		<td>".$vecPlanMedicamentos[$i]['observaciones']."<br><b>Indicaciones de suministro</b><br>".$vecPlanMedicamentos[$i]['indicacion_suministro']."</td>\n";
							else
								$consulta .= "		<td>".$vecPlanMedicamentos[$i]['observaciones']."</td>\n";
					}
					else
					{
						$consulta .= "		<td align='center'>Vigente</td>\n";
						if (empty($vecPlanMedicamentos[$i]['observaciones']) && empty($vecPlanMedicamentos[$i]['indicacion_suministro']))
							$consulta .= "		<td>Se continua con el tratamiento</td>\n";
						else
							if (!empty($vecPlanMedicamentos[$i]['indicacion_suministro']))
								$consulta .= "		<td>".$vecPlanMedicamentos[$i]['observaciones']."<br><b>Indicaciones de suministro</b><br>".$vecPlanMedicamentos[$i]['indicacion_suministro']."</td>\n";
							else
								$consulta .= "		<td>".$vecPlanMedicamentos[$i]['observaciones']."</td>\n";
					}
					$consulta .= "		<td align='center'><font color=\"#990000\">".$vecPlanMedicamentos[$i]['evolucion_id']."</font></td>\n";
					//Para saber si es un medicamento POS o no
					if ($this->tipo_profesional==1 || $this->tipo_profesional==2)
					{
						if (is_null($vecPlanMedicamentos[$i]['justificacion_no_pos_id']) && !$vecPlanMedicamentos[$i]['sw_pos'])
						{
							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."AddJust",$this->frmPrefijo."Evolucion"=>$vecPlanMedicamentos[$i]['evolucion_id'],"DatMedC"=>$vecPlanMedicamentos[$i]));
							$consulta .= "		<td align='center'><a href='".$href."'><font color='#3A702F'>Adicionar Justificación</font></a></td>\n";
						}
						else
							if (!is_null($vecPlanMedicamentos[$i]['justificacion_no_pos_id']))
							{
								$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."ShowJust","DatMed"=>array("Medicamento"=>$vecPlanMedicamentos[$i])));
								$consulta .= "		<td align='center'><a href='".$href."'><font color='#A15F18'></font>Ver Justificación</a></td>\n";
							}
							else
								$consulta .= "		<td align='center'>Medicamento P.O.S</td>\n";
					}
					else
					{
						if (is_null($vecPlanMedicamentos[$i]['justificacion_no_pos_id']) && !$vecPlanMedicamentos[$i]['sw_pos'])
							$consulta .= "		<td align='center'>Sin Justificación</td>\n";
						else
							if (!is_null($vecPlanMedicamentos[$i]['justificacion_no_pos_id']))
							{
								$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."ShowJust","DatMed"=>array("Medicamento"=>$vecPlanMedicamentos[$i])));
								$consulta .= "<td align='center'><a href='".$href."'><font color='#A15F18'></font>Ver Justificación</a></td>\n";
							}
							else
								$consulta .= "<td align='center'>Medicamento P.O.S</td>\n";
					}
					$consulta .= "<td align='center'>\n";
					if ($action==2)
					{
						if($vecPlanMedicamentos[$i]['sw_estado'] == '0' && ($this->tipo_profesional==1 || $this->tipo_profesional==2) )
						{
							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."Continuar",$this->frmPrefijo."Mezcla"=>0,$this->frmPrefijo."Medicamentos"=>array("MedID"=>$vecPlanMedicamentos[$i]['codigo_producto'],"MedNombre"=>$vecPlanMedicamentos[$i]['descripcion'],"JustId"=>$vecPlanMedicamentos[$i]['justificacion_no_pos_id'],"Pos"=>$vecPlanMedicamentos[$i]['sw_pos'],"cantTotal"=>$vecPlanMedicamentos[$i]['cantidad_total'],"cant"=>$vecPlanMedicamentos[$i]['cantidad'],"horario"=>$vecPlanMedicamentos[$i]['horario'],"via"=>$vecPlanMedicamentos[$i]['via_administracion_id'],"EvoId"=>$vecPlanMedicamentos[$i]['evolucion_id'],"comentario"=>"Se continua con el tratamiento")));
							$consulta .= "<a href='".$href."'><font color='#063496'>Continuar</font></a>\n";
							$href2=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."Finalizar",$this->frmPrefijo."Mezcla"=>0,$this->frmPrefijo."Medicamentos"=>array("MedID"=>$vecPlanMedicamentos[$i]['codigo_producto'],"MedNombre"=>$vecPlanMedicamentos[$i]['descripcion'],"JustId"=>$vecPlanMedicamentos[$i]['justificacion_no_pos_id'],"Pos"=>$vecPlanMedicamentos[$i]['sw_pos'],"cantTotal"=>$vecPlanMedicamentos[$i]['cantidad_total'],"cant"=>$vecPlanMedicamentos[$i]['cantidad'],"horario"=>$vecPlanMedicamentos[$i]['horario'],"via"=>$vecPlanMedicamentos[$i]['via_administracion_id'],"EvoId"=>$vecPlanMedicamentos[$i]['evolucion_id'],"comentario"=>"Se finaliza el tratamiento")));
							$consulta .= "<br><a href='".$href2."'><font color='#8C8030'>Finalizar</font></a>\n";
						}
						elseif ($vecPlanMedicamentos[$i]['sw_estado'] == '0'  && ($this->tipo_profesional==3 || $this->tipo_profesional==4))
						{
							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."Continuar",$this->frmPrefijo."Mezcla"=>0,$this->frmPrefijo."Medicamentos"=>array("MedID"=>$vecPlanMedicamentos[$i]['codigo_producto'],"MedNombre"=>$vecPlanMedicamentos[$i]['descripcion'],"JustId"=>$vecPlanMedicamentos[$i]['justificacion_no_pos_id'],"Pos"=>$vecPlanMedicamentos[$i]['sw_pos'],"cantTotal"=>$vecPlanMedicamentos[$i]['cantidad_total'],"cant"=>$vecPlanMedicamentos[$i]['cantidad'],"horario"=>$vecPlanMedicamentos[$i]['horario'],"via"=>$vecPlanMedicamentos[$i]['via_administracion_id'],"EvoId"=>$vecPlanMedicamentos[$i]['evolucion_id'],"comentario"=>"Se continua con el tratamiento")));
							$consulta .= "<a href='".$href."'><font color='#063496'>Continuar</font></a>\n";
						}
						elseif ($vecPlanMedicamentos[$i]['sw_estado'] == '2' && ($this->tipo_profesional==1 || $this->tipo_profesional==2))
						{
							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."Finalizar",$this->frmPrefijo."Mezcla"=>0,$this->frmPrefijo."Medicamentos"=>array("MedID"=>$vecPlanMedicamentos[$i]['codigo_producto'],"MedNombre"=>$vecPlanMedicamentos[$i]['descripcion'],"JustId"=>$vecPlanMedicamentos[$i]['justificacion_no_pos_id'],"Pos"=>$vecPlanMedicamentos[$i]['sw_pos'],"cantTotal"=>$vecPlanMedicamentos[$i]['cantidad_total'],"cant"=>$vecPlanMedicamentos[$i]['cantidad'],"horario"=>$vecPlanMedicamentos[$i]['horario'],"via"=>$vecPlanMedicamentos[$i]['via_administracion_id'],"EvoId"=>$vecPlanMedicamentos[$i]['evolucion_id'],"comentario"=>"Se finaliza el tratamiento")));
							$consulta .= "<br><a href='".$href."'><font color='#8C8030'>Finalizar</font></a>\n";
						}
						elseif ($vecPlanMedicamentos[$i]['sw_estado'] == '2' && ($this->tipo_profesional==3 || $this->tipo_profesional==4))
						{
							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."Suspender",$this->frmPrefijo."Mezcla"=>0,$this->frmPrefijo."Medicamentos"=>array("MedID"=>$vecPlanMedicamentos[$i]['codigo_producto'],"MedNombre"=>$vecPlanMedicamentos[$i]['descripcion'],"JustId"=>$vecPlanMedicamentos[$i]['justificacion_no_pos_id'],"Pos"=>$vecPlanMedicamentos[$i]['sw_pos'],"cantTotal"=>$vecPlanMedicamentos[$i]['cantidad_total'],"cant"=>$vecPlanMedicamentos[$i]['cantidad'],"horario"=>$vecPlanMedicamentos[$i]['horario'],"via"=>$vecPlanMedicamentos[$i]['via_administracion_id'],"EvoId"=>$vecPlanMedicamentos[$i]['evolucion_id'])));
							$consulta .= "<br><a href='".$href."'><font color='#035512'>Suspender</font></a>";
						}
					}
					else
					{
						$consulta .= "--";
					}
					$consulta .= "</td>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."SumiMedica",$this->frmPrefijo."Medicamentos"=>$vecPlanMedicamentos[$i],$this->frmPrefijo."Mezcla"=>0,$this->frmPrefijo."Accion"=>$action));
					$consulta .= "		<td align='center'><font color=\"#990000\"><a href='".$href."'>Notas del Medicamento</a></font></td>\n";
					$consulta .= "  </tr>\n";
				}//End for
				$consulta .= "</table>\n\n\n";

				//Para que solo pueda ingresar si la evolucion esta activa
				if (($this->tipo_profesional==1 || $this->tipo_profesional==2) && $action==2)
				{
					$consulta .="<br><table width='80%' align='center' border='0' class='hc_table_submodulo'>";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."Add"));
					$consulta .= "				<tr><td align='center'><a href='".$href."'>Adicionar Medicamentos</a></td></tr>\n";
					$consulta .= "</table>\n\n";
				}
				$consulta .= ThemeCerrarTablaSubModulo();
				return $consulta;
			}
			return true;
		}


/************************************************/
		function ShowPlanMedicamentosHis($action)
		{
			$salida="";
			list($dbconn) = GetDBconn();
			$vecPlanMedicamentos = array();
			$vecPlanMedicamentos = $this->ObtenerPlanTerapeutico($action);

			if (is_array($vecPlanMedicamentos) && !sizeof($vecPlanMedicamentos) && $action==2)
			{
				$salida .= ThemeAbrirTablaSubModulo('MEDICAMENTOS INEXISTENTES');
				$salida .="<b>El paciente no tiene medicamentos relacionados</b>";
				//Para que solo pueda ingresar si la evolucion esta activa
				if ($this->estado)
				{
					$salida .="<br><table width='80%' align='center' border='0' class='modulo_table_list'>";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."Add"));
					$salida .= "				<tr><td align='center'><a href='".$href."'>Adicionar Medicamentos</a></td></tr>\n";
					$salida .= "</table>\n\n";
				}
				$salida .= ThemeCerrarTablaSubModulo();
				return $salida;
			}
			elseif (($vecPlanMedicamentos===false && $action==2) || ($vecPlanMedicamentos===false && $action==1))
			{
				return false;
			}
			elseif (empty($vecPlanMedicamentos) && $action==1)
			{
				return "ShowMensaje";
			}
			elseif ($vecPlanMedicamentos)
			{
				$salida .= ThemeAbrirTablaSubModulo('MEDICAMENTOS');
				$salida .= "<table width='100%' class=\"modulo_table_list\" align=\"center\" border=\"0\">\n";
				$salida .= "	<tr class=\"modulo_table_list_title\">\n";
				$salida .= "		<td>FECHA</td>\n";
				$salida .= "		<td>MEDICAMENTO</td>\n";
				$salida .= "		<td>CANT.</td>\n";
				$salida .= "		<td>VIA</td>\n";
				$salida .= "		<td>ESTADO</td>\n";
				$salida .= "		<td>COMENTARIO</td>\n";
				$salida .= "		<td>EVOLUCION</td>\n";
				$salida .= "		<td>MEDICAMENTO POS</td>\n";
				$salida .= "		<td>ACCIÓN</td>\n";
				$salida .= "		<td>DETALLE SUMINISTRO</td>\n";
				$salida .= "  </tr>\n";

				for($i=0; $i<sizeof($vecPlanMedicamentos); $i++)
				{
					$salida .= "<tr ".$this->Lista($i).">";
					$salida .= "<td align='center'>".$vecPlanMedicamentos[$i]['fecha']."</td>\n";
					$salida .= "<td>".$vecPlanMedicamentos[$i]['descripcion']." ".$vecPlanMedicamentos[$i]['unidescripcion']." ".$vecPlanMedicamentos[$i]['presentacion']."</td>\n";
					$salida .= "<td align='center'>".$vecPlanMedicamentos[$i]['cantidad_total']."</td>\n";
					$salida .= "<td>".$vecPlanMedicamentos[$i]['vianombre']."</td>\n";
					if($vecPlanMedicamentos[$i]['sw_estado'] == '0')
					{
						$salida .= "<td align='center'><font color=\"#990000\">Suspendido</font></td>\n";
						if (empty($vecPlanMedicamentos[$i]['observaciones']) && empty($vecPlanMedicamentos[$i]['indicacion_suministro']))
							$salida .= "<td>".$vecPlanMedicamentos[$i]['nota_suspension']."</td>\n";
						else
							if (!empty($vecPlanMedicamentos[$i]['indicacion_suministro']))
								$salida .= "<td><b>Nota Suspensión</b><br>".$vecPlanMedicamentos[$i]['nota_suspension']."<hr>".$vecPlanMedicamentos[$i]['observaciones']."<br><b>Indicaciones de suministro</b><br>".$vecPlanMedicamentos[$i]['indicacion_suministro']."</td>\n";
							else
								$salida .= "<td><b>Nota Suspensión</b><br>".$vecPlanMedicamentos[$i]['nota_suspension']."<hr>".$vecPlanMedicamentos[$i]['observaciones']."</td>\n";
					}
					elseif ($vecPlanMedicamentos[$i]['sw_estado'] == '1')
					{
						$salida .= "		<td align='center'>Finalizado</td>\n";
						if (empty($vecPlanMedicamentos[$i]['observaciones']) && empty($vecPlanMedicamentos[$i]['indicacion_suministro']))
							$salida .= "		<td>Plan Finalizado</td>\n";
						else
							if (!empty($vecPlanMedicamentos[$i]['indicacion_suministro']))
								$salida .= "		<td>".$vecPlanMedicamentos[$i]['observaciones']."<br><b>Indicaciones de suministro</b><br>".$vecPlanMedicamentos[$i]['indicacion_suministro']."</td>\n";
							else
								$salida .= "		<td>".$vecPlanMedicamentos[$i]['observaciones']."</td>\n";
					}
					else
					{
						$salida .= "		<td align='center'>Vigente</td>\n";
						if (empty($vecPlanMedicamentos[$i]['observaciones']) && empty($vecPlanMedicamentos[$i]['indicacion_suministro']))
							$salida .= "		<td>Se continua con el tratamiento</td>\n";
						else
							if (!empty($vecPlanMedicamentos[$i]['indicacion_suministro']))
								$salida .= "		<td>".$vecPlanMedicamentos[$i]['observaciones']."<br><b>Indicaciones de suministro</b><br>".$vecPlanMedicamentos[$i]['indicacion_suministro']."</td>\n";
							else
								$salida .= "		<td>".$vecPlanMedicamentos[$i]['observaciones']."</td>\n";
					}
					$salida .= "		<td align='center'><font color=\"#990000\">".$vecPlanMedicamentos[$i]['evolucion_id']."</font></td>\n";
					//Para saber si es un medicamento POS o no
					if ($this->tipo_profesional==1 || $this->tipo_profesional==2)
					{
						if (is_null($vecPlanMedicamentos[$i]['justificacion_no_pos_id']) && !$vecPlanMedicamentos[$i]['sw_pos'])
						{
							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."AddJust",$this->frmPrefijo."Evolucion"=>$vecPlanMedicamentos[$i]['evolucion_id'],"DatMedC"=>$vecPlanMedicamentos[$i]));
							$salida .= "		<td align='center'><a href='".$href."'><font color='#3A702F'>Adicionar Justificación</font></a></td>\n";
						}
						else
							if (!is_null($vecPlanMedicamentos[$i]['justificacion_no_pos_id']))
							{
								$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."ShowJust","DatMed"=>array("Medicamento"=>$vecPlanMedicamentos[$i])));
								$salida .= "		<td align='center'><a href='".$href."'><font color='#A15F18'></font>Ver Justificación</a></td>\n";
							}
							else
								$salida .= "		<td align='center'>Medicamento P.O.S</td>\n";
					}
					else
					{
						if (is_null($vecPlanMedicamentos[$i]['justificacion_no_pos_id']) && !$vecPlanMedicamentos[$i]['sw_pos'])
							$salida .= "		<td align='center'>Sin Justificación</td>\n";
						else
							if (!is_null($vecPlanMedicamentos[$i]['justificacion_no_pos_id']))
							{
								$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."ShowJust","DatMed"=>array("Medicamento"=>$vecPlanMedicamentos[$i])));
								$salida .= "<td align='center'><a href='".$href."'><font color='#A15F18'></font>Ver Justificación</a></td>\n";
							}
							else
								$salida .= "<td align='center'>Medicamento P.O.S</td>\n";
					}
					$salida .= "<td align='center'>\n";
					if ($action==2)
					{
						if($vecPlanMedicamentos[$i]['sw_estado'] == '0' && ($this->tipo_profesional==1 || $this->tipo_profesional==2) )
						{
							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."Continuar",$this->frmPrefijo."Mezcla"=>0,$this->frmPrefijo."Medicamentos"=>array("MedID"=>$vecPlanMedicamentos[$i]['codigo_producto'],"MedNombre"=>$vecPlanMedicamentos[$i]['descripcion'],"JustId"=>$vecPlanMedicamentos[$i]['justificacion_no_pos_id'],"Pos"=>$vecPlanMedicamentos[$i]['sw_pos'],"cantTotal"=>$vecPlanMedicamentos[$i]['cantidad_total'],"cant"=>$vecPlanMedicamentos[$i]['cantidad'],"horario"=>$vecPlanMedicamentos[$i]['horario'],"via"=>$vecPlanMedicamentos[$i]['via_administracion_id'],"EvoId"=>$vecPlanMedicamentos[$i]['evolucion_id'],"comentario"=>"Se continua con el tratamiento")));
							$salida .= "<a href='".$href."'><font color='#063496'>Continuar</font></a>\n";
							$href2=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."Finalizar",$this->frmPrefijo."Mezcla"=>0,$this->frmPrefijo."Medicamentos"=>array("MedID"=>$vecPlanMedicamentos[$i]['codigo_producto'],"MedNombre"=>$vecPlanMedicamentos[$i]['descripcion'],"JustId"=>$vecPlanMedicamentos[$i]['justificacion_no_pos_id'],"Pos"=>$vecPlanMedicamentos[$i]['sw_pos'],"cantTotal"=>$vecPlanMedicamentos[$i]['cantidad_total'],"cant"=>$vecPlanMedicamentos[$i]['cantidad'],"horario"=>$vecPlanMedicamentos[$i]['horario'],"via"=>$vecPlanMedicamentos[$i]['via_administracion_id'],"EvoId"=>$vecPlanMedicamentos[$i]['evolucion_id'],"comentario"=>"Se finaliza el tratamiento")));
							$salida .= "<br><a href='".$href2."'><font color='#8C8030'>Finalizar</font></a>\n";
						}
						elseif ($vecPlanMedicamentos[$i]['sw_estado'] == '0'  && ($this->tipo_profesional==3 || $this->tipo_profesional==4))
						{
							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."Continuar",$this->frmPrefijo."Mezcla"=>0,$this->frmPrefijo."Medicamentos"=>array("MedID"=>$vecPlanMedicamentos[$i]['codigo_producto'],"MedNombre"=>$vecPlanMedicamentos[$i]['descripcion'],"JustId"=>$vecPlanMedicamentos[$i]['justificacion_no_pos_id'],"Pos"=>$vecPlanMedicamentos[$i]['sw_pos'],"cantTotal"=>$vecPlanMedicamentos[$i]['cantidad_total'],"cant"=>$vecPlanMedicamentos[$i]['cantidad'],"horario"=>$vecPlanMedicamentos[$i]['horario'],"via"=>$vecPlanMedicamentos[$i]['via_administracion_id'],"EvoId"=>$vecPlanMedicamentos[$i]['evolucion_id'],"comentario"=>"Se continua con el tratamiento")));
							$salida .= "<a href='".$href."'><font color='#063496'>Continuar</font></a>\n";
						}
						elseif ($vecPlanMedicamentos[$i]['sw_estado'] == '2' && ($this->tipo_profesional==1 || $this->tipo_profesional==2))
						{
							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."Finalizar",$this->frmPrefijo."Mezcla"=>0,$this->frmPrefijo."Medicamentos"=>array("MedID"=>$vecPlanMedicamentos[$i]['codigo_producto'],"MedNombre"=>$vecPlanMedicamentos[$i]['descripcion'],"JustId"=>$vecPlanMedicamentos[$i]['justificacion_no_pos_id'],"Pos"=>$vecPlanMedicamentos[$i]['sw_pos'],"cantTotal"=>$vecPlanMedicamentos[$i]['cantidad_total'],"cant"=>$vecPlanMedicamentos[$i]['cantidad'],"horario"=>$vecPlanMedicamentos[$i]['horario'],"via"=>$vecPlanMedicamentos[$i]['via_administracion_id'],"EvoId"=>$vecPlanMedicamentos[$i]['evolucion_id'],"comentario"=>"Se finaliza el tratamiento")));
							$salida .= "<br><a href='".$href."'><font color='#8C8030'>Finalizar</font></a>\n";
						}
						elseif ($vecPlanMedicamentos[$i]['sw_estado'] == '2' && ($this->tipo_profesional==3 || $this->tipo_profesional==4))
						{
							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."Suspender",$this->frmPrefijo."Mezcla"=>0,$this->frmPrefijo."Medicamentos"=>array("MedID"=>$vecPlanMedicamentos[$i]['codigo_producto'],"MedNombre"=>$vecPlanMedicamentos[$i]['descripcion'],"JustId"=>$vecPlanMedicamentos[$i]['justificacion_no_pos_id'],"Pos"=>$vecPlanMedicamentos[$i]['sw_pos'],"cantTotal"=>$vecPlanMedicamentos[$i]['cantidad_total'],"cant"=>$vecPlanMedicamentos[$i]['cantidad'],"horario"=>$vecPlanMedicamentos[$i]['horario'],"via"=>$vecPlanMedicamentos[$i]['via_administracion_id'],"EvoId"=>$vecPlanMedicamentos[$i]['evolucion_id'])));
							$salida .= "<br><a href='".$href."'><font color='#035512'>Suspender</font></a>";
						}
					}
					else
					{
						$salida .= "--";
					}
					$salida .= "</td>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."SumiMedica",$this->frmPrefijo."Medicamentos"=>$vecPlanMedicamentos[$i],$this->frmPrefijo."Mezcla"=>0,$this->frmPrefijo."Accion"=>$action));
					$salida .= "		<td align='center'><font color=\"#990000\"><a href='".$href."'>Notas del Medicamento</a></font></td>\n";
					$salida .= "  </tr>\n";
				}//End for
				$salida .= "</table>\n\n\n";

				//Para que solo pueda ingresar si la evolucion esta activa
				if (($this->tipo_profesional==1 || $this->tipo_profesional==2) && $action==2)
				{
					$salida .="<br><table width='80%' align='center' border='0' class='hc_table_submodulo'>";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."Add"));
					$salida .= "				<tr><td align='center'><a href='".$href."'>Adicionar Medicamentos</a></td></tr>\n";
					$salida .= "</table>\n\n";
				}
				$salida .= ThemeCerrarTablaSubModulo();
				return $salida;
			}
			return true;
		}


		function ShowPlanMedicamentosMezclasHis($action)
		{
			$salida="";
			list($dbconn) = GetDBconn();
			$vecPlanMedicamentos=array();
			$vecPlanMedicamentos = $this->ObtenerPlanTerpeuticoMezclas($action);
			if (empty($vecPlanMedicamentos) && $action==2)
			{
				$salida .= ThemeAbrirTablaSubModulo('LIQUIDOS PARENTERALES INEXISTENTE');
				$salida .="<b>El paciente no tiene liquidos parenterales relacionados</b>";
				//Para que solo pueda ingresar si la evolucion esta activa
				if ($this->estado)
				{
					$salida .="<br><table width='80%' align='center' border='0' class='hc_table_submodulo'>";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."AddLiquidosP","Borrar_session"=>1));
					$salida .= "				<tr><td align='center'><a href='".$href."'>Adicionar Liquidos Parenterales</a></td></tr>";
					$salida .= "</table>\n\n";
				}
				$salida .= ThemeCerrarTablaSubModulo();
				return $salida;
			}
			elseif (($vecPlanMedicamentos===false && $action==2) || ($vecPlanMedicamentos===false && $action==1)){
				return false;
			}
			elseif (empty($vecPlanMedicamentos) && $action==1)
			{
				return "ShowMensaje";
			}
			elseif ($vecPlanMedicamentos)
			{
				$salida .= ThemeAbrirTablaSubModulo('LIQUIDOS PARENTERALES');
				for($j=0; $j<sizeof($vecPlanMedicamentos); $j++)
				{
					$salida .= "<table width='100%' class=\"modulo_table_list\" align=\"center\" border=\"0\">\n";
					$salida .= "	<tr class=\"modulo_table_list_title\">\n";
					$salida .= "		<td>FECHA</td>\n";
					$salida .= "		<td>CANT.</td>\n";
					$salida .= "		<td>POSOLOGÍA</td>\n";
					$salida .= "		<td>ESTADO</td>\n";
					$salida .= "		<td>COMENTARIO</td>\n";
					$salida .= "		<td>EVOLUCION</td>\n";
					$salida .= "		<td>ACCIÓN</td>\n";
					$salida .= "		<td>DETALLE SUMINISTRO</td>\n";
					$salida .= "  </tr>\n";

					$mezcla=$vecPlanMedicamentos[$j][0];
					$medicamento=$vecPlanMedicamentos[$j][1];

					$salida .= "	<tr ".$this->Lista($i).">";
					$salida .= "		<td align='center'>".$mezcla['fecha']."</td>\n";
					$salida .= "		<td align='center'>".$mezcla['cantidad']."</td>\n";
					$salida .= "		<td nowrap>".$this->PosologiaMezcla($mezcla)."</td>\n";
					if($mezcla['sw_estado'] == '0'){
						$salida .= "		<td align='center'><font color=\"#990000\">Suspendida</font></td>\n";
						if (empty($mezcla['observaciones']) && empty($mezcla['indicacion_suministro']))
							$salida .= "		<td>".$mezcla['nota_suspension']."</td>\n";
						else
							if (!empty($mezcla['indicacion_suministro']))
								$salida .= "		<td><b>Nota Suspensión</b><br>".$mezcla['nota_suspension']."<hr>".$mezcla['observaciones']."<br><b>Indicaciones de suministro</b><br>".$mezcla['indicacion_suministro']."</td>\n";
							else
								$salida .= "		<td><b>Nota Suspensión</b><br>".$mezcla['nota_suspension']."<hr>".$mezcla['observaciones']."</td>\n";
					}
					elseif ($mezcla['sw_estado'] == '1'){
						$salida .= "		<td align='center'>Finalizada</td>\n";
						if (empty($mezcla['observaciones']) && empty($mezcla['indicacion_suministro']))
							$salida .= "		<td>Plan finalizado</td>\n";
						else
							if (!empty($mezcla['indicacion_suministro']))
								$salida .= "		<td>".$mezcla['observaciones']."<br><b>Indicaciones de suministro</b><br>".$mezcla['indicacion_suministro']."</td>\n";
							else
								$salida .= "		<td>".$mezcla['observaciones']."</td>\n";
					}
					else {
						$salida .= "		<td align='center'>Vigente</td>\n";
						if (empty($mezcla['observaciones']) && empty($mezcla['indicacion_suministro']))
							$salida .= "		<td>Continuar con el tratamiento</td>\n";
						else
							if (!empty($mezcla['indicacion_suministro']))
								$salida .= "		<td>".$mezcla['observaciones']."<br><b>Indicaciones de suministro</b><br>".$mezcla['indicacion_suministro']."</td>\n";
							else
								$salida .= "		<td>".$mezcla['observaciones']."</td>\n";
					}
					$salida .= "		<td align='center'><font color=\"#990000\">".$mezcla['evolucion_id']."</font></td>\n";
					$salida .= "		<td align='center'>\n";
					if ($action==2)
					{
						if($mezcla['sw_estado'] == '0' && ($this->tipo_profesional==1 || $this->tipo_profesional==2)){
							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."Continuar",$this->frmPrefijo."Mezcla"=>1,$this->frmPrefijo."Medicamentos"=>array("MedID"=>$mezcla['mezcla_recetada_id'],"EvoId"=>$mezcla['evolucion_id'],"comentario"=>"Se continua con el tratamiento")));
							$salida .= "		<a href='".$href."'><font color='#063496'>Continuar</font></a>\n";
							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."Finalizar",$this->frmPrefijo."Mezcla"=>1,$this->frmPrefijo."Medicamentos"=>array("MedID"=>$mezcla['mezcla_recetada_id'],"EvoId"=>$mezcla['evolucion_id'],"comentario"=>"Se finaliza el tratamiento")));
							$salida .= "		<br><a href='".$href."'><font color='#8C8030'>Finalizar</font></a>\n";
						}
						elseif ($mezcla['sw_estado'] == '0'  && ($this->tipo_profesional==3 || $this->tipo_profesional==4)){
							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."Continuar",$this->frmPrefijo."Mezcla"=>1,$this->frmPrefijo."Medicamentos"=>array("MedID"=>$mezcla['mezcla_recetada_id'],"EvoId"=>$mezcla['evolucion_id'],"comentario"=>"Se continua con el tratamiento")));
							$salida .= "		<a href='".$href."'><font color='#063496'>Continuar</font></a>\n";
						}
						elseif ($mezcla['sw_estado'] == '2' && ($this->tipo_profesional==1 || $this->tipo_profesional==2)){
							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."Finalizar",$this->frmPrefijo."Mezcla"=>1,$this->frmPrefijo."Medicamentos"=>array("MedID"=>$mezcla['mezcla_recetada_id'],"EvoId"=>$mezcla['evolucion_id'],"comentario"=>"Se finaliza el tratamiento")));
							$salida .= "		<br><a href='".$href."'><font color='#8C8030'>Finalizar</font></a>\n";
						}
						elseif ($mezcla['sw_estado'] == '2' && $this->tipo_profesional==3 || $this->tipo_profesional==4){
							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."Suspender",$this->frmPrefijo."Mezcla"=>1,$this->frmPrefijo."Medicamentos"=>array("MedID"=>$mezcla['mezcla_recetada_id'],"EvoId"=>$mezcla['evolucion_id'])));
							$salida .= "		<br><a href='".$href."'><font color='#035512'>Suspender</font></a>";
						}
					}
					else
					{
						$salida .= "--";
					}
					$salida .= "		</td>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."SumiMedica",$this->frmPrefijo."Medicamentos"=>$mezcla,$this->frmPrefijo."Mezcla"=>1,$this->frmPrefijo."Accion"=>$action));
					$salida .= "		<td align='center'><font color=\"#990000\"><a href='".$href."'>Notas del Medicamento</a></font></td>\n";
					$salida .= "  </tr>\n";

					$salida .= "<table width='100%' class=\"modulo_table_list\" align=\"center\" border=\"0\">\n";
					$salida .= "	<tr class=\"modulo_table_list_title\">\n";
					$salida .= "		<td width='85%'>MEDICAMENTO</td>\n";
					$salida .= "		<td width='5%'>CANT.</td>\n";
					$salida .= "		<td width='10%'>MEDICAMENTO POS</td>\n";
					$salida .= "  </tr>\n";

					for($i=0; $i<sizeof($medicamento); $i++)
					{
						$salida .= "	<tr ".$this->Lista($i).">";
						$salida .= "		<td>".$medicamento[$i]['descripcion']." ".$medicamento[$i]['unidescripcion']." ".$medicamento[$i]['presentacion']."</td>\n";
						$salida .= "		<td align='center'>".$medicamento[$i]['cantidad']."</td>\n";
						if ($this->tipo_profesional==1 || $this->tipo_profesional==2)
						{
							//Para saber si es un medicamento POS o no
							if (is_null($medicamento[$i]['justificacion_no_pos_id']) && !$medicamento[$i]['sw_pos']){
								$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."AddJust",$this->frmPrefijo."Evolucion"=>$mezcla['evolucion_id'],"DatMed"=>$medicamento[$i],"MezclaId"=>$mezcla['mezcla_recetada_id']));
								$salida .= "		<td align='center'><a href='".$href."'><font color='#3A702F'>Adicionar Justificación</font></a></td>\n";
							}
							elseif (!is_null($medicamento[$i]['justificacion_no_pos_id'])){
									$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."ShowJust","DatMed"=>array("Medicamento"=>$medicamento[$i])));
									$salida .= "		<td align='center'><a href='".$href."'><font color='#A15F18'></font>Ver Justificación</a></td>\n";
							}
							else
								$salida .= "		<td align='center'>Medicamento P.O.S</td>\n";
						}
						else
						{
							//Para saber si es un medicamento POS o no
							if (is_null($medicamento[$i]['justificacion_no_pos_id']) && !$medicamento[$i]['sw_pos'])
								$salida .= "		<td align='center'><b>Sin Justificación</b></td>\n";
							else
								if (!is_null($medicamento[$i]['justificacion_no_pos_id'])){
									$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."ShowJust","DatMed"=>array("Medicamento"=>$medicamento[$i])));
									$salida .= "		<td align='center'><a href='".$href."'><font color='#A15F18'></font>Ver Justificación</a></td>\n";
								}
								else
									$salida .= "		<td align='center'>Medicamento P.O.S</td>\n";
						}
						$salida .= "  </tr>\n";
					}//End for medicamento
					$salida .= "</table><br><br>\n";
				}//End for

				//Para que solo pueda ingresar si la evolucion esta activa
				if (($this->tipo_profesional==1 || $this->tipo_profesional==2) && $action==2)
				{
					$salida .="<table width='80%' align='center' border='0' class='hc_table_submodulo'>";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."AddLiquidosP","Borrar_session"=>1));
					$salida .= "				<tr><td align='center'><a href='".$href."'>Adicionar Liquidos Parenterales</a></td></tr>";
					$salida .= "</table>\n\n";
				}
				$salida .= ThemeCerrarTablaSubModulo();
				return $salida;
			}
			return $salida;
		}

/************************************************/

		function ShowPlanMedicamentosMezclas($action)
		{
			$consulta="";
			list($dbconn) = GetDBconn();
			$vecPlanMedicamentos=array();
			$vecPlanMedicamentos = $this->ObtenerPlanTerpeuticoMezclas($action);
			if (empty($vecPlanMedicamentos) && $action==2)
			{
				$consulta .= ThemeAbrirTablaSubModulo('LIQUIDOS PARENTERALES INEXISTENTE');
				$consulta .="<b>El paciente no tiene liquidos parenterales relacionados</b>";
				//Para que solo pueda ingresar si la evolucion esta activa
				if ($this->estado)
				{
					$consulta .="<br><table width='80%' align='center' border='0' class='hc_table_submodulo'>";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."AddLiquidosP","Borrar_session"=>1));
					$consulta .= "				<tr><td align='center'><a href='".$href."'>Adicionar Liquidos Parenterales</a></td></tr>";
					$consulta .= "</table>\n\n";
				}
				$consulta .= ThemeCerrarTablaSubModulo();
				return $consulta;
			}
			elseif (($vecPlanMedicamentos===false && $action==2) || ($vecPlanMedicamentos===false && $action==1)){
				return false;
			}
			elseif (empty($vecPlanMedicamentos) && $action==1)
			{
				return "ShowMensaje";
			}
			elseif ($vecPlanMedicamentos)
			{
				$consulta .= ThemeAbrirTablaSubModulo('LIQUIDOS PARENTERALES');
				for($j=0; $j<sizeof($vecPlanMedicamentos); $j++)
				{
					$consulta .= "<table width='100%' class=\"modulo_table_list\" align=\"center\" border=\"0\">\n";
					$consulta .= "	<tr class=\"modulo_table_list_title\">\n";
					$consulta .= "		<td>FECHA</td>\n";
					$consulta .= "		<td>CANT.</td>\n";
					$consulta .= "		<td>POSOLOGÍA</td>\n";
					$consulta .= "		<td>ESTADO</td>\n";
					$consulta .= "		<td>COMENTARIO</td>\n";
					$consulta .= "		<td>EVOLUCION</td>\n";
					$consulta .= "		<td>ACCIÓN</td>\n";
					$consulta .= "		<td>DETALLE SUMINISTRO</td>\n";
					$consulta .= "  </tr>\n";

					$mezcla=$vecPlanMedicamentos[$j][0];
					$medicamento=$vecPlanMedicamentos[$j][1];

					$consulta .= "	<tr ".$this->Lista($i).">";
					$consulta .= "		<td align='center'>".$mezcla['fecha']."</td>\n";
					$consulta .= "		<td align='center'>".$mezcla['cantidad']."</td>\n";
					$consulta .= "		<td nowrap>".$this->PosologiaMezcla($mezcla)."</td>\n";
					if($mezcla['sw_estado'] == '0'){
						$consulta .= "		<td align='center'><font color=\"#990000\">Suspendida</font></td>\n";
						if (empty($mezcla['observaciones']) && empty($mezcla['indicacion_suministro']))
							$consulta .= "		<td>".$mezcla['nota_suspension']."</td>\n";
						else
							if (!empty($mezcla['indicacion_suministro']))
								$consulta .= "		<td><b>Nota Suspensión</b><br>".$mezcla['nota_suspension']."<hr>".$mezcla['observaciones']."<br><b>Indicaciones de suministro</b><br>".$mezcla['indicacion_suministro']."</td>\n";
							else
								$consulta .= "		<td><b>Nota Suspensión</b><br>".$mezcla['nota_suspension']."<hr>".$mezcla['observaciones']."</td>\n";
					}
					elseif ($mezcla['sw_estado'] == '1'){
						$consulta .= "		<td align='center'>Finalizada</td>\n";
						if (empty($mezcla['observaciones']) && empty($mezcla['indicacion_suministro']))
							$consulta .= "		<td>Plan finalizado</td>\n";
						else
							if (!empty($mezcla['indicacion_suministro']))
								$consulta .= "		<td>".$mezcla['observaciones']."<br><b>Indicaciones de suministro</b><br>".$mezcla['indicacion_suministro']."</td>\n";
							else
								$consulta .= "		<td>".$mezcla['observaciones']."</td>\n";
					}
					else {
						$consulta .= "		<td align='center'>Vigente</td>\n";
						if (empty($mezcla['observaciones']) && empty($mezcla['indicacion_suministro']))
							$consulta .= "		<td>Continuar con el tratamiento</td>\n";
						else
							if (!empty($mezcla['indicacion_suministro']))
								$consulta .= "		<td>".$mezcla['observaciones']."<br><b>Indicaciones de suministro</b><br>".$mezcla['indicacion_suministro']."</td>\n";
							else
								$consulta .= "		<td>".$mezcla['observaciones']."</td>\n";
					}
					$consulta .= "		<td align='center'><font color=\"#990000\">".$mezcla['evolucion_id']."</font></td>\n";
					$consulta .= "		<td align='center'>\n";
					if ($action==2)
					{
						if($mezcla['sw_estado'] == '0' && ($this->tipo_profesional==1 || $this->tipo_profesional==2)){
							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."Continuar",$this->frmPrefijo."Mezcla"=>1,$this->frmPrefijo."Medicamentos"=>array("MedID"=>$mezcla['mezcla_recetada_id'],"EvoId"=>$mezcla['evolucion_id'],"comentario"=>"Se continua con el tratamiento")));
							$consulta .= "		<a href='".$href."'><font color='#063496'>Continuar</font></a>\n";
							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."Finalizar",$this->frmPrefijo."Mezcla"=>1,$this->frmPrefijo."Medicamentos"=>array("MedID"=>$mezcla['mezcla_recetada_id'],"EvoId"=>$mezcla['evolucion_id'],"comentario"=>"Se finaliza el tratamiento")));
							$consulta .= "		<br><a href='".$href."'><font color='#8C8030'>Finalizar</font></a>\n";
						}
						elseif ($mezcla['sw_estado'] == '0'  && ($this->tipo_profesional==3 || $this->tipo_profesional==4)){
							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."Continuar",$this->frmPrefijo."Mezcla"=>1,$this->frmPrefijo."Medicamentos"=>array("MedID"=>$mezcla['mezcla_recetada_id'],"EvoId"=>$mezcla['evolucion_id'],"comentario"=>"Se continua con el tratamiento")));
							$consulta .= "		<a href='".$href."'><font color='#063496'>Continuar</font></a>\n";
						}
						elseif ($mezcla['sw_estado'] == '2' && ($this->tipo_profesional==1 || $this->tipo_profesional==2)){
							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."Finalizar",$this->frmPrefijo."Mezcla"=>1,$this->frmPrefijo."Medicamentos"=>array("MedID"=>$mezcla['mezcla_recetada_id'],"EvoId"=>$mezcla['evolucion_id'],"comentario"=>"Se finaliza el tratamiento")));
							$consulta .= "		<br><a href='".$href."'><font color='#8C8030'>Finalizar</font></a>\n";
						}
						elseif ($mezcla['sw_estado'] == '2' && $this->tipo_profesional==3 || $this->tipo_profesional==4){
							$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."Suspender",$this->frmPrefijo."Mezcla"=>1,$this->frmPrefijo."Medicamentos"=>array("MedID"=>$mezcla['mezcla_recetada_id'],"EvoId"=>$mezcla['evolucion_id'])));
							$consulta .= "		<br><a href='".$href."'><font color='#035512'>Suspender</font></a>";
						}
					}
					else
					{
						$consulta .= "--";
					}
					$consulta .= "		</td>\n";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."SumiMedica",$this->frmPrefijo."Medicamentos"=>$mezcla,$this->frmPrefijo."Mezcla"=>1,$this->frmPrefijo."Accion"=>$action));
					$consulta .= "		<td align='center'><font color=\"#990000\"><a href='".$href."'>Notas del Medicamento</a></font></td>\n";
					$consulta .= "  </tr>\n";

					$consulta .= "<table width='100%' class=\"modulo_table_list\" align=\"center\" border=\"0\">\n";
					$consulta .= "	<tr class=\"modulo_table_list_title\">\n";
					$consulta .= "		<td width='85%'>MEDICAMENTO</td>\n";
					$consulta .= "		<td width='5%'>CANT.</td>\n";
					$consulta .= "		<td width='10%'>MEDICAMENTO POS</td>\n";
					$consulta .= "  </tr>\n";

					for($i=0; $i<sizeof($medicamento); $i++)
					{
						$consulta .= "	<tr ".$this->Lista($i).">";
						$consulta .= "		<td>".$medicamento[$i]['descripcion']." ".$medicamento[$i]['unidescripcion']." ".$medicamento[$i]['presentacion']."</td>\n";
						$consulta .= "		<td align='center'>".$medicamento[$i]['cantidad']."</td>\n";
						if ($this->tipo_profesional==1 || $this->tipo_profesional==2)
						{
							//Para saber si es un medicamento POS o no
							if (is_null($medicamento[$i]['justificacion_no_pos_id']) && !$medicamento[$i]['sw_pos']){
								$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."AddJust",$this->frmPrefijo."Evolucion"=>$mezcla['evolucion_id'],"DatMed"=>$medicamento[$i],"MezclaId"=>$mezcla['mezcla_recetada_id']));
								$consulta .= "		<td align='center'><a href='".$href."'><font color='#3A702F'>Adicionar Justificación</font></a></td>\n";
							}
							elseif (!is_null($medicamento[$i]['justificacion_no_pos_id'])){
									$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."ShowJust","DatMed"=>array("Medicamento"=>$medicamento[$i])));
									$consulta .= "		<td align='center'><a href='".$href."'><font color='#A15F18'></font>Ver Justificación</a></td>\n";
							}
							else
								$consulta .= "		<td align='center'>Medicamento P.O.S</td>\n";
						}
						else
						{
							//Para saber si es un medicamento POS o no
							if (is_null($medicamento[$i]['justificacion_no_pos_id']) && !$medicamento[$i]['sw_pos'])
								$consulta .= "		<td align='center'><b>Sin Justificación</b></td>\n";
							else
								if (!is_null($medicamento[$i]['justificacion_no_pos_id'])){
									$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."ShowJust","DatMed"=>array("Medicamento"=>$medicamento[$i])));
									$consulta .= "		<td align='center'><a href='".$href."'><font color='#A15F18'></font>Ver Justificación</a></td>\n";
								}
								else
									$consulta .= "		<td align='center'>Medicamento P.O.S</td>\n";
						}
						$consulta .= "  </tr>\n";
					}//End for medicamento
					$consulta .= "</table><br><br>\n";
				}//End for

				//Para que solo pueda ingresar si la evolucion esta activa
				if (($this->tipo_profesional==1 || $this->tipo_profesional==2) && $action==2)
				{
					$consulta .="<table width='80%' align='center' border='0' class='hc_table_submodulo'>";
					$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("subModuloAction"=>$this->frmPrefijo."AddLiquidosP","Borrar_session"=>1));
					$consulta .= "				<tr><td align='center'><a href='".$href."'>Adicionar Liquidos Parenterales</a></td></tr>";
					$consulta .= "</table>\n\n";
				}
				$consulta .= ThemeCerrarTablaSubModulo();
				return $consulta;
			}
			return true;
		}


//------------------------------------------------------------------------
// Otras funciones
//------------------------------------------------------------------------


		function PosologiaMezcla($vector)
		{
			switch ($vector['unidad_calculo'])
			{
				case 1:  return ($vector['cantidad_calculo']." cm3 cada hora.");  break;
				case 2:  return ($vector['cantidad_calculo']." gotas cada minuto.");  break;
				case 3:  return ($vector['cantidad_calculo']." microgotas cada minuto.");  break;
				case 4:  return ($vector['cantidad_calculo']." cm3 cada hora.");  break;
				case 5:  return ($vector['cantidad_calculo']." en bolo.");  break;
			}
		}


		function Posologia($vector)
		{
			list($dbconn) = GetDBconn();
			$posologia="";
			$unidad="";

			if (!empty($vector['unidad_dosis']))
			{
				$unidades=$this->GetViasAdmonUds($vector['via_administracion_id'],$vector['unidad_dosis']);
				if (!$unidades){
					return false;
				}
				$unidad="[".$unidades."]";
			}

			$unidad=$vector['cantidad']." ".$unidad." ";
			if ($vector['horario'])
			{
				if ($vector['horario'] < 59)
				{ return ($unidad." cada ".$vector['horario']." minutos"); }
				else
				{
					$horas = floor($vector['horario'] / 60);
					$minutos = $vector['horario'] % 60;
					if($minutos && $horas)  return ($unidad." cada ".$horas." horas ".$minutos." minutos");
					else  return ($unidad." cada ".$horas." hora(s) ");
				}
			}
			if (!empty($vector['sw_rango']))
			{
				if ($vector['sw_rango']=='D')
					$posologia="Durante";
				if ($vector['sw_rango']=='A')
					$posologia="Antes";
				if ($vector['sw_rango']=='U')
					$posologia="Despues";

				if (($vector['desayuno']!=" ") && ($vector['almuerzo']!=" ") && ($vector['comida']!=" ")){
					return ($unidad." ".$posologia." (Desayuno,Almuerzo y Cena).");
				}
				if (($vector['desayuno']!=" ") && ($vector['almuerzo']!=" ") && ($vector['comida']==" ")){
					return ($unidad." ".$posologia." (Desayuno y Almuerzo).");
				}
				if (($vector['desayuno']!=" ") && ($vector['almuerzo']==" ") && ($vector['comida']!=" ")){
					return ($unidad." ".$posologia." (Desayuno y Cena).");
				}
				if (($vector['desayuno']==" ") && ($vector['almuerzo']!=" ") && ($vector['comida']!=" ")){
					return ($unidad." ".$posologia." (Almuerzo y Cena).");
				}
				if ($vector['desayuno']!=" "){
					return ($unidad." ".$posologia." (Desayuno).");
				}
				if ($vector['almuerzo']!=" "){
					return ($unidad." ".$posologia." (Almuerzo).");
				}
				if ($vector['comida']!=" "){
					return ($unidad." ".$posologia." (Cena).");
				}
			}
			if (!empty($vector['duracion_id']))
			{
					$data=$this->GetHorario($vector['duracion_id']);
					if (!$data){
						return false;
					}
					$data_r=$data->FetchNextObject($toupper=false);
					if ($data_r->duracion_id=='01')
						return ($unidad." Durante el ".$data_r->descripcion);
					else
						return ($unidad." Durante la ".$data_r->descripcion);
			}
			if (!empty($vector['hora_especifica']))
			{
				$hora_especifica=unserialize($vector['hora_especifica']);
				$posologia=$unidad." durante las siguientes horas:<br>";
				$hora="";
				for($i=0;$i<sizeof($hora_especifica);$i++){
					$hora.="<b>[".$hora_especifica[$i]."]</b>&nbsp;";
				}
				return ($posologia.$hora);
			}
		}


		function Chequeo($aguja,$pajar,$forms)
		{
		  switch ($forms)
			{
				case 0 :
								if ($aguja==$pajar)
									return " checked";
								return "";
				break;
			}
		}


		/*
		* function GetHoraMedicamento($Horario)
		* $Horario es el horario en minutos para la toma del medicamento
		* Se calcula el horario de la posología
		* retorna el horario para la toma del medicamento
		*/
		function GetHoraMedicamento($Horario)
		{
			//-----------saber si el horario está en horas o minutos ------------
			if($Horario < 59)
				{ return ($Horario." minutos "); }
			else
			{
 				$horas = floor($Horario / 60);
				$minutos = $Horario % 60;
				if($minutos && $horas)  return ($horas." horas ".$minutos." minutos");
				else  return ($horas." hora(s) ");
			}
		}//End function

		/*
		* function PutOptionsToSelectViasAdm()
		* retorna los options al select via de administracion instanciado con los
		* valores obtenidos por medio de la funcion ObtenerViasAdministracion
		*/
		function PutOptionsToSelectViasAdm($Vias,$valor)
		{
			for($i=0; $i<sizeof($Vias); $i++)
			{
				if ($Vias[$i][0]==$valor)
					$this->salida .= "<option value=\"".$Vias[$i][0]."\" selected>".$Vias[$i][1]."</option>";
				else
					$this->salida .= "<option value=\"".$Vias[$i][0]."\">".$Vias[$i][1]."</option>";
			}
			return true;
		}//End function



		function ValidaDatoMedSession($var_session,$clave,$busqueda)
		{
			foreach($_SESSION[$var_session] as $key => $value){
				if ($_SESSION[$var_session][$key][$clave]==$busqueda){
					return false;
				}
			}
			return true;
		}


		function GetHora()
		{
			$salida="";
			list($anno, $mes, $dia)=explode("-",date("Y-m-d"));
			for($j=0; $j<24; $j++)
			{
				$i=date("H",mktime($j,0,0,$mes,$dia,$anno));

				if (date("H")===$i)
					$salida .= "				<option value='".$i."' selected>".$i."</option>\n";
				else
					$salida .= "				<option value='".$i."'>".$i."</option>\n";
			}
			return $salida;
		}

		function GetHoraMinutos()
		{
			$salida="";
			list($anno, $mes, $dia)=explode("-",date("Y-m-d"));
			for($j=0; $j<60; $j++)
			{
				$i=date("i",mktime(0,$j,0,$mes,$dia,$anno));

				if (date("i")===$i)
					$salida .= "				<option value='".$i."' selected>".$i."</option>\n";
				else
					$salida .= "				<option value='".$i."'>".$i."</option>\n";
			}
			return $salida;
		}

		function Lista($numero)
		{
			if ($numero%2)
				return ("class='hc_list_oscuro'");
			return ("class='hc_list_claro'");
		}//End function



//*************************************************************************************
//FUNCIONES DE CLAUDIA - SOLICITUD DE MEDICAMENTOS
//*************************************************************************************


     //clzc - si - *
     function frmForma_Seleccion_Medicamentos($vectorE)
     {
          $pfj=$this->frmPrefijo;
          $this->salida= ThemeAbrirTablaSubModulo('SOLICITUD DE MEDICAMENTOS');
          $accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Medicamentos',
          'Of'.$pfj=>$_REQUEST['Of'.$pfj],'paso1'=>$_REQUEST['paso1'.$pfj],
          'criterio1'.$pfj=>$_REQUEST['criterio1'.$pfj],
          'producto'.$pfj=>$_REQUEST['producto'.$pfj],
          'principio_activo'.$pfj=>$_REQUEST['principio_activo'.$pfj]));
     
          $this->salida .= "<form name=\"formades$pfj\" action=\"$accion1\" method=\"post\">";
          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
          $this->salida.="<tr class=\"modulo_table_title\">";
          $this->salida.="  <td align=\"center\" colspan=\"7\">BUSQUEDA AVANZADA </td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
          $this->salida.="<td width=\"5%\">TIPO</td>";

          $this->salida.="<td width=\"10%\" align = left >";
          $this->salida.="<select size = 1 name = 'criterio1$pfj'  class =\"select\">";
          $this->salida.="<option value = '001' selected>Todos</option>";
          if (($_REQUEST['criterio1'.$pfj])  == '002')
          {
               $this->salida.="<option value = '002' selected>Frecuentes</option>";
          }
          else
          {
               $this->salida.="<option value = '002' >Frecuentes</option>";
          }
          $this->salida.="</select>";
          $this->salida.="</td>";

          $this->salida.="<td width=\"7%\">PRODUCTO:</td>";
          $this->salida .="<td width=\"23%\" align='center'><input type='text' class='input-text'  size = 22 name = 'producto$pfj'  value =\"".$_REQUEST['producto'.$pfj]."\"    ></td>" ;

          $this->salida.="<td width=\"8%\">PRINCIPIO ACTIVO:</td>";
          $this->salida .="<td width=\"22%\" align='center' ><input type='text' class='input-text' size = 22 name = 'principio_activo$pfj'   value =\"".$_REQUEST['principio_activo'.$pfj]."\"></td>" ;

          $this->salida .= "<td  width=\"5%\" align=\"center\"><input class=\"input-submit\" name=\"buscar$pfj\" type=\"submit\" value=\"BUSCAR\"></td>";
          $this->salida.="</tr>";
          $this->salida.="</table><br>";

          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
          $this->salida .= $this->SetStyle("MensajeError");
          $this->salida.="</table>";
          $this->salida.="</form>";
     
          $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'llenar_solicitud_medicamento'));
          $this->salida .= "<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
          if ($vectorE)
          {
               $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
               $this->salida.="<tr class=\"modulo_table_title\">";
               $this->salida.="  <td align=\"center\" colspan=\"7\">RESULTADO DE LA BUSQUEDA</td>";
               $this->salida.="</tr>";

               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="  <td align=\"center\" width=\"5%\"></td>";
               $this->salida.="  <td align=\"center\" width=\"5%\">CODIGO</td>";
               $this->salida.="  <td align=\"center\" width=\"23%\">PRODUCTO</td>";
               $this->salida.="  <td align=\"center\" width=\"23%\">PRINCIPIO ACTIVO</td>";
               if ($this->bodega==='')
               {
                    $this->salida.="  <td colspan = 2 width=\"15%\">FORMA</td>";
               }
               else
               {
                    $this->salida.="  <td width=\"15%\">FORMA</td>";
                    $this->salida.="  <td width=\"5%\">EXISTENCIA</td>";
               }
               $this->salida.="  <td align=\"center\" width=\"4%\">OPCION</td>";
               $this->salida.="</tr>";
               for($i=0;$i<sizeof($vectorE);$i++)
               {
                    if( $i % 2){ $estilo='modulo_list_claro';}
                    else {$estilo='modulo_list_oscuro';}
                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="  <td align=\"left\" width=\"5%\">".$vectorE[$i][item]."</td>";
                    $this->salida.="  <td align=\"left\" width=\"5%\">".$vectorE[$i][codigo_producto]."</td>";
                    $this->salida.="  <td align=\"left\" width=\"20%\">".$vectorE[$i][producto]."</td>";
                    $this->salida.="  <td align=\"left\" width=\"20%\">".$vectorE[$i][principio_activo]."</td>";

                    if ($this->bodega==='')
                    {
                         $this->salida.="  <td colspan = 2align=\"left\" width=\"15%\">".$vectorE[$i][forma]."</td>";
                    }
                    else
                    {
                         $this->salida.="  <td align=\"left\" width=\"15%\">".$vectorE[$i][forma]."</td>";
                         if(!empty($vectorE[$i][existencia]))
                         {
                              $this->salida.="  <td align=\"center\" width=\"5%\">".$vectorE[$i][existencia]."</td>";
                         }
                         else
                         {
                              $this->salida.="  <td align=\"center\" width=\"5%\">--</td>";
                         }
                    }
                    $valor=urlencode($vectorE[$i][item].'|/'.$vectorE[$i][codigo_producto].'|/'.$vectorE[$i][producto].'|/'.$vectorE[$i][principio_activo].'|/'.$vectorE[$i][concentracion_forma_farmacologica].'|/'.$vectorE[$i][unidad_medida_medicamento_id].'|/'.$vectorE[$i][forma].'|/'.$vectorE[$i][cod_forma_farmacologica].'|/'.$vectorE[$i][unidad_dosificacion]);
                    $this->salida.="  <td align=\"center\" width=\"5%\"><input type = radio name= 'opE$pfj' value = $valor></td>";
                    $this->salida.="</tr>";
               }
               $this->salida.="<tr class=\"$estilo\">";
               $this->salida .= "<td align=\"right\" colspan=\"7\"><input class=\"input-submit\" name=\"guardar$pfj\" type=\"submit\" value=\"FORMULAR\"></td>";
               $this->salida.="</tr>";
               $this->salida.="</table><br>";

               $var=$this->RetornarBarraMedicamentos_Avanzada();
               if(!empty($var))
               {
                    $this->salida .= "  <br><table border=\"0\" width=\"60%\" align=\"center\">";// class=\"modulo_table\"
                    $this->salida .= "  <tr>";
                    $this->salida .= "  <td width=\"100%\" align=\"center\">";
                    $this->salida .=$var;
                    $this->salida .= "  </td>";
                    $this->salida .= "  </tr>";
                    $this->salida .= "  </table><br>";
               }
          }
          $this->salida .= "</form>";
		//BOTON DEVOLVER
          $accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
          $this->salida .= "<form name=\"forma$pfj\" action=\"$accionV\" method=\"post\">";
          $this->salida .= "<tr><td  colspan = 6 align=\"center\"><br><input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\"></form></td></tr>";
          $this->salida .= ThemeCerrarTablaSubModulo();
          return true;
     }

     //*
     function frmForma_Llenar_Solicitud_Medicamento($datos_m)
     {
		$pfj=$this->frmPrefijo;
		if(empty($datos_m))
		{
			$datos_m=$_SESSION['DATOS_M'.$pfj]['PLAN_TERAPEUTICO'];
		}

		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'justificacion_no_pos', 'datos_m'.$pfj=>$_REQUEST['opE'.$pfj]));
		$this->salida .= "<form name=\"forma_med$pfj\" action=\"$accion\" method=\"post\">";

		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";

		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"6\">FORMULACION DEL MEDICAMENTO</td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class=\"modulo_table_title\">";

		$this->salida.="  <td align=\"center\" width=\"5%\"></td>";
		$this->salida.="  <td align=\"center\" width=\"5%\">CODIGO</td>";
		$this->salida.="  <td align=\"center\" width=\"23%\">PRODUCTO</td>";
		$this->salida.="  <td align=\"center\" width=\"23%\">PRINCIPIO ACTIVO</td>";
		$this->salida.="  <td align=\"center\" width=\"23%\">CONCENTRACION</td>";
		$this->salida.="  <td align=\"center\" width=\"15%\">FORMA</td>";
		//$this->salida.="  <td width=\"5%\">EXISTENCIA</td>";
		$this->salida.="</tr>";

		if( $i % 2){ $estilo='modulo_list_claro';}
		else {$estilo='modulo_list_oscuro';}


		$arreglo=explode("|/",$datos_m);

		$this->salida.="  <input type='hidden' name = 'item$pfj'  value = '".$arreglo[0]."'>";
		$this->salida.="  <input type='hidden' name = 'codigo_producto$pfj'  value = '".$arreglo[1]."'>";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";

		$this->salida.="<td align=\"center\" width=\"5%\">".$arreglo[0]."</td>";
		$this->salida.="<td align=\"center\" width=\"5%\">".$arreglo[1]."</td>";
		$this->salida.="<td align=\"center\" width=\"23%\" >".$arreglo[2]."</td>";
		$this->salida.="<td align=\"center\" width=\"23%\" >".$arreglo[3]."</td>";
		$this->salida.="<td align=\"center\" width=\"15%\" >".$arreglo[4]." ".$arreglo[5]."</td>";
		$this->salida.="<td align=\"center\" width=\"15%\" >".$arreglo[6]."</td>";

		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";

		//via de administracion
		$this->salida.="<tr class=\"$estilo\">";
		$this->salida.="<td class=".$this->SetStyle("via_administracion")." width=\"20%\"align=\"left\" >VIA DE ADMINISTRACION</td>";
		$via_admon = $this->tipo_via_administracion($arreglo[1]);

		//es la unidad de dosificacion que viene $arreglo[8]

		if ((sizeof($via_admon)>1))
		{
               $this->salida.="<td width=\"60%\" align = left >";
               if	(empty($arreglo[8]))
               {
                    $EventoOnclick="OnChange='UnidadPorVia(this)'";
               }
               else
               {
                    $EventoOnclick="";
               }

               $this->salida.="\n\n<select name = 'via_administracion$pfj'  class =\"select\" $EventoOnclick>";
               $this->salida.="<option value = '-1' selected>-Seleccione-</option>";

               $javita.="<script>\n";
               $javita.="function UnidadPorVia(forma) {\n";
               $javita.="if (forma.value=='-1') {\n";
               $javita.="  document.forma_med$pfj.unidad_dosis$pfj.length=0;\n";
               $javita.="}\n\n";
               for($i=0;$i<sizeof($via_admon);$i++)
               {
                    if ((($_REQUEST['via_administracion'.$pfj])  != $via_admon[$i][via_administracion_id]) )
                    {
                         $this->salida.="<option value = ".$via_admon[$i][via_administracion_id].">".$via_admon[$i][nombre]."</option>";
                    }
                    else
                    {
                         $this->salida.="<option value = ".$via_admon[$i][via_administracion_id]." selected >".$via_admon[$i][nombre]."</option>";
                    }

                    //generar java para el combo de unidades de dosificacion
                    if	(empty($arreglo[8]))
                    {
                         $javita.="if (forma.value=='".$via_admon[$i][via_administracion_id]."') {\n";

                         $unidadesViaAdministracion = $this->GetunidadesViaAdministracion($via_admon[$i][via_administracion_id]);

                         $javita.="document.forma_med$pfj.unidad_dosis$pfj.length=".count($unidadesViaAdministracion)."\n";

                         for($cont=0;$cont<count($unidadesViaAdministracion);$cont++){
                                   $javita.="document.forma_med$pfj.unidad_dosis$pfj.options[".$cont."]= new Option('".$unidadesViaAdministracion[$cont][unidad_dosificacion]."','".$unidadesViaAdministracion[$cont][unidad_dosificacion]."');\n";
                                             }
                         $javita.="}\n\n";
                    }
                    //fin javita
               }
               $javita.="}\n\n";
               $javita.="</script>\n";
               $this->salida.="</select>\n\n";
               $this->salida.="</td>";
		}
		else
		{
			if ((sizeof($via_admon)==1))
			{
                    $this->salida.="<td width=\"60%\" align = left >";
                    $this->salida.="\n\n<select name = 'via_administracion$pfj'  class =\"select\">";
                    $this->salida.="<option value = ".$via_admon[0][via_administracion_id]." selected >".$via_admon[0][nombre]."</option>";
                    $this->salida.="</select>\n\n";
                    $this->salida.="</td>";
			}
			else
			{
                    $this->salida.="<td width=\"60%\" align = left >&nbsp;</td>";
			}
		}
		$this->salida.="</tr>";

          //-----------------
          
          //Generar Combo de unidades de dosificacion
		$ComboUnidadDosis ="<select size = 1 name = 'unidad_dosis$pfj'  class =\"select\">";
		if	(!empty($arreglo[8]))
		{
					$ComboUnidadDosis.="<option value = '".$arreglo[8]."' selected >".$arreglo[8]."</option>";
		}
		else
		{
			if ((sizeof($via_admon)==1))
			{
				$unidadesViaAdministracion = $this->GetunidadesViaAdministracion($via_admon[0][via_administracion_id]);
				$ComboUnidadDosis.="<option value = '-1' selected >--Seleccione--</option>";
				for($i=0;$i<sizeof($unidadesViaAdministracion);$i++)
				{
					//aqui agreggue este if para que se seleccione la unidad seleccionada y guardada en el request
                         if($_REQUEST['unidad_dosis'.$pfj]==$unidadesViaAdministracion[$i][unidad_dosificacion])
                         {
                              $ComboUnidadDosis.="<option selected value = '".$unidadesViaAdministracion[$i][unidad_dosificacion]."'>".$unidadesViaAdministracion[$i][unidad_dosificacion]."</option>";
                         }
                         else
                         {
                              $ComboUnidadDosis.="<option value = '".$unidadesViaAdministracion[$i][unidad_dosificacion]."'>".$unidadesViaAdministracion[$i][unidad_dosificacion]."</option>";
                         }
					//fin del if y sigue comentado lo que estaba antes de que se creara el if
					//$ComboUnidadDosis.="<option value = '".$unidadesViaAdministracion[$i][unidad_dosificacion]."'>".$unidadesViaAdministracion[$i][unidad_dosificacion]."</option>";
				}
			}
			if (empty($via_admon))
			{
				$unidadesViaAdministracion = $this->Unidades_Dosificacion();
				$ComboUnidadDosis.="<option value = '-1' selected >--Seleccione--</option>";
				for($i=0;$i<sizeof($unidadesViaAdministracion);$i++)
				{
					//aqui agreggue este if para que se seleccione la unidad guardadad en la bd
                         if($_REQUEST['unidad_dosis'.$pfj]==$unidadesViaAdministracion[$i][unidad_dosificacion])
                         {
                              $ComboUnidadDosis.="<option selected value = '".$unidadesViaAdministracion[$i][unidad_dosificacion]."'>".$unidadesViaAdministracion[$i][unidad_dosificacion]."</option>";
                         }
                         else
                         {
                         	$ComboUnidadDosis.="<option value = '".$unidadesViaAdministracion[$i][unidad_dosificacion]."'>".$unidadesViaAdministracion[$i][unidad_dosificacion]."</option>";
                         }
				}
			}
		}
		$ComboUnidadDosis.="</select>";
//--------------

//posologia neonatos
/*
		$FechaInicio = $this->datosPaciente[fecha_nacimiento];
		$FechaFin = date("Y-m-d");
		$edad_paciente = CalcularEdad($FechaInicio,$FechaFin);
		if ( $edad_paciente[anos] < ModuloGetVar('','','max_edad_pediatrica'))
			{
				$peso_pac = $this->Peso_Paciente();
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td width=\"20%\"align=\"left\" >POSOLOGIA NEONATOS</td>";
				$this->salida.="<td width=\"60%\" align = left >";
				$this->salida.="<table>";
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td  class=".$this->SetStyle("peso")." width=\"20%\" align = left >PESO</td>";
				$this->salida.="<td colspan = 2 width=\"40%\" align='left' ><input type='text' class='input-text' size = 10 name = 'peso$pfj'   value = \"".$peso_pac[peso]."\">  Kg</td>" ;
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td  width=\"20%\" align=\"left\" >DOSIS ORDENADA</td>";
				$this->salida.="<td width=\"15%\" align=\"left\" ><input type='text' class='input-text' size = 10 name = 'dosis_ordenada$pfj'   value =\"".$_REQUEST['dosis_ordenada'.$pfj]."\">  mg/Kg por: </td>" ;
				$this->salida.="<td width=\"25%\" align=\"left\" >";
				$this->salida.="<select size = 1 name = 'criterio_dosis$pfj'  class =\"select\">";
				$this->salida.="<option value = 'dosis' selected>Dosis</option>";
				if (($_REQUEST['criterio_dosis'.$pfj])  == 'Dia')
					{
						$this->salida.="<option value = '002' selected>Dia</option>";
					}
				else
					{
						$this->salida.="<option value = '002' >Dia</option>";
					}
				$this->salida.="</select>";
				$this->salida.="</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida .= "<td width=\"20%\"  align=\"left\"><input type='button' name='calcular_dosis$pfj' value='Calcular Dosis' onclick='Calcular_Dosis(this.form)'></td>";
				$this->salida.="<td colspan=2 width=\"40%\" align=\"left\" ><input type='text' class='input-text' readonly size = 10 name = 'dosis_total$pfj'>  mg</td>" ;
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td class=".$this->SetStyle("cantidad")." width=\"50%\" align = left >CANTIDAD</td>";
				$this->salida.="<td  width=\"65%\" align='left' ><input type='text' class='input-text' size = 15 name = 'cantidad$pfj'   value =\"".$_REQUEST['cantidad'.$pfj]."\"></td>" ;
				$this->salida.="<td  width=\"50%\" align = left >UNIDAD</td>";
				$this->salida.="<td width=\"65%\" align='left' ><input type='text' class='input-text' readonly size = 15 name = 'unidad$pfj'   value =\"".$_REQUEST['unidad'.$pfj]."\"></td>" ;
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";

				//funcion que calcula la dosis
				$this->salida .= "<script>\n";
				$this->salida .= "function Calcular_Dosis(formulario){\n";
				$this->salida .= "var a;\n";
				$this->salida .= "var b;\n";
				$this->salida .= "a=formulario.peso$pfj.value;\n";
				$this->salida .= "b=formulario.dosis_ordenada$pfj.value;\n";
				$this->salida .= "c=a*b;\n";
				$this->salida .= "if(isNaN(c)){\n";
				$this->salida .= "alert('valores no validos');\n";
				$this->salida .= "formulario.dosis_total$pfj.value='';\n";
				$this->salida .= "if(isNaN(b)){\n";
				$this->salida .= "formulario.dosis_ordenada$pfj.value='';\n";
				$this->salida .= "formulario.dosis_ordenada$pfj.focus();\n";
				$this->salida .= "}\n";

				$this->salida .= "if(isNaN(a)){\n";
				$this->salida .= "formulario.peso$pfj.value='';\n";
				$this->salida .= "formulario.peso$pfj.focus();\n";
				$this->salida .= "}\n";

				$this->salida .= "} else {\n";
				$this->salida .= "formulario.dosis_total$pfj.value=c;\n";
				$this->salida .= "}\n";
				$this->salida .= "}\n";
				$this->salida .= "</script>\n";
				//fin de la funcion
					}
*/
//posologia-dosis
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td width=\"20%\"align=\"left\" >DOSIS</td>";
				$this->salida.="<td width=\"60%\" align = left >";
				$this->salida.="<table>";
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td  width=\"10%\" class=".$this->SetStyle("dosis")." align = left >DOSIS</td>";
				$this->salida.="<td width=\"15%\" align='left' ><input type='text' class='input-text' size = 15 name = 'dosis$pfj'   value =\"".$_REQUEST['dosis'.$pfj]."\"></td>" ;

//unidades de dosificacion
				$this->salida.="<td width=\"35%\" class=".$this->SetStyle("unidad_dosis")." align = left >";
				//si no trae unidad de dosificacion segun la forma del producto pinta combo de vias interactivo

				if	(empty($arreglo[8]))
				{
					$this->salida.=$javita;
					//este es el if nuevo que coloque para cargar unidades
                         if ((sizeof($via_admon)>1))
                         {
                              $ComboUnidadDosis ="<select size = 1 name = 'unidad_dosis$pfj'  class =\"select\">";
                              $unidadesViaAdministracion = $this->GetunidadesViaAdministracion($_REQUEST['via_administracion'.$pfj]);
                              $ComboUnidadDosis.="<option value = '-1' selected >--Seleccione--</option>";
                              for($i=0;$i<sizeof($unidadesViaAdministracion);$i++)
                              {
                                   if($_REQUEST['unidad_dosis'.$pfj]==$unidadesViaAdministracion[$i][unidad_dosificacion])
                                   {
                                        $ComboUnidadDosis.="<option selected value = '".$unidadesViaAdministracion[$i][unidad_dosificacion]."'>".$unidadesViaAdministracion[$i][unidad_dosificacion]."</option>";
                                   }
                                   else
                                   {
                                        $ComboUnidadDosis.="<option value = '".$unidadesViaAdministracion[$i][unidad_dosificacion]."'>".$unidadesViaAdministracion[$i][unidad_dosificacion]."</option>";
                                   }
                              }
                              $ComboUnidadDosis.="</select>";
                         }
	                    //fin del evento nuevo
				}
				$this->salida.="$ComboUnidadDosis";
				$this->salida.="</td>";
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";

//horario
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td width=\"20%\" class=".$this->SetStyle("frecuencia")." align=\"left\" >FRECUENCIA</td>";
				$this->salida.="<td width=\"60%\" align = left >";
				$this->salida.="<table border = 0 >";

//opcion 1
				$this->salida.="<tr class=\"modulo_list_claro\">";

				if ($_REQUEST['opcion'.$pfj] != '1')
                    {
                         $this->salida.="<td width=\"10%\" class=".$this->SetStyle("opcion1")." align=\"left\" ><input type = radio name= 'opcion$pfj' value = 1>OPCION 1</td>";
                    }
				else
                    {
                         $this->salida.="<td width=\"10%\" class=".$this->SetStyle("opcion1")." align=\"left\" ><input type = radio checked name= 'opcion$pfj' value = 1>OPCION 1</td>";
                    }

				$this->salida.="<td width=\"50%\"align=\"left\" >";
				$this->salida.="<table>";
				$this->salida.="<tr class=\"modulo_list_claro\">";
				$this->salida.="<td width=\"10%\" align = left >CADA</td>";
				$cada_periocidad = $this->Cargar_Periocidad();
				$this->salida.="<td width=\"10%\" align = left >";
				$this->salida.="<select size = 1 name = 'periocidad$pfj'  class =\"select\">";
				$this->salida.="<option value = '-1' selected>-Seleccione-</option>";
				for($i=0;$i<sizeof($cada_periocidad);$i++)
				{
                         if ((($_REQUEST['periocidad'.$pfj])  != $cada_periocidad[$i][periocidad_id]) )
                         {
                              $this->salida.="<option value = ".$cada_periocidad[$i][periocidad_id].">".$cada_periocidad[$i][periocidad_id]."</option>";
                         }
                         else
                         {
                              $this->salida.="<option value = ".$cada_periocidad[$i][periocidad_id]." selected >".$cada_periocidad[$i][periocidad_id]."</option>";
                         }
				}
				$this->salida.="</select>";
				$this->salida.="</td>";
				$this->salida.="<td width=\"30%\" align = 'left' >";
				$this->salida.="<select size = 1 name = 'tiempo$pfj'  class =\"select\">";
				$this->salida.="<option value = '-1' selected>-Seleccione-</option>";
				//opcion de minutos
				if (($_REQUEST['tiempo'.$pfj])  == 'Min')
                    {
                         $this->salida.="<option value = 'Min' selected>Min</option>";
                    }
				else
                    {
                         $this->salida.="<option value = 'Min' >Min</option>";
                    }
				//opcion de horas
				if (($_REQUEST['tiempo'.$pfj])  == 'Hora(s)')
                    {
                         $this->salida.="<option value = 'Hora(s)' selected>Hora(s)</option>";
                    }
				else
                    {
                         $this->salida.="<option value = 'Hora(s)' >Hora(s)</option>";
                    }
				//opcion de dias
				if (($_REQUEST['tiempo'.$pfj])  == 'Dia(s)')
                    {
                         $this->salida.="<option value = 'Dia(s)' selected>Dia(s)</option>";
                    }
				else
                    {
                         $this->salida.="<option value = 'Dia(s)' >Dia(s)</option>";
                    }
                    //opcion de semanas
				if (($_REQUEST['tiempo'.$pfj])  == 'Semana(s)')
                    {
                         $this->salida.="<option value = 'Semana(s)' selected>Semana(s)</option>";
                    }
				else
                    {
                         $this->salida.="<option value = 'Semana(s)' >Semana(s)</option>";
                    }
				$this->salida.="</select>";
				$this->salida.="</td>";
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";
			//OPCION 2
				$this->salida.="<tr class=\"modulo_list_claro\">";
				if ($_REQUEST['opcion'.$pfj] != '2')
                    {
                         $this->salida.="<td width=\"10%\" class=".$this->SetStyle("opcion2")." align=\"left\" ><input type = radio name= 'opcion$pfj' value = 2>OPCION 2</td>";
                    }
				else
                    {
                         $this->salida.="<td width=\"10%\" class=".$this->SetStyle("opcion2")." align=\"left\" ><input type = radio checked name= 'opcion$pfj' value = 2>OPCION 2</td>";
                    }
				$this->salida.="<td width=\"50%\"align=\"left\" >";
				$this->salida.="<table>";
				$this->salida.="<tr class=\"modulo_list_claro\">";
				$horario = $this->horario();
				$this->salida.="<td class=".$this->SetStyle("durante")." width=\"20%\"align=\"left\" >&nbsp;</td>";
				$this->salida.="<td width=\"60%\" align = left >";
				$this->salida.="<select size = 1 name = 'duracion$pfj'  class =\"select\">";
				$this->salida.="<option value = -1 selected>-Seleccione-</option>";


				for($i=0;$i<sizeof($horario);$i++)
				{
					if ($_REQUEST['duracion'.$pfj]==trim($horario[$i][duracion_id]))
                         {
                              $this->salida.="<option value = ".$horario[$i][duracion_id]." selected >".$horario[$i][descripcion]."</option>";
                         }
					else
                         {
                              $this->salida.="<option value = ".$horario[$i][duracion_id].">".$horario[$i][descripcion]."</option>";
                         }
				}
				$this->salida.="</select>";
				$this->salida.="</td>";
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";
			//opcion 3
				$this->salida.="<tr class=\"modulo_list_claro\">";
				if ($_REQUEST['opcion'.$pfj] != '3')
                    {
                         $this->salida.="<td width=\"10%\" class=".$this->SetStyle("opcion3")." align=\"left\" ><input type = radio name= 'opcion$pfj' value = 3>OPCION 3</td>";
                    }
				else
                    {
                         $this->salida.="<td width=\"10%\" class=".$this->SetStyle("opcion3")." align=\"left\" ><input type = radio checked name= 'opcion$pfj' value = 3>OPCION 3</td>";
                    }
				$this->salida.="<td width=\"50%\"align=\"left\" >";
				$this->salida.="<table>";
				$this->salida.="<tr class=\"modulo_list_claro\">";
				if ($_REQUEST['momento'.$pfj] != '1')
                    {
                         $this->salida.="<td width=\"15%\" align = left ><input type = radio name= 'momento$pfj' value = '1'>ANTES</td>";
                    }
				else
                    {
                         $this->salida.="<td width=\"15%\" align = left ><input type = radio name= 'momento$pfj' checked value = '1'>ANTES</td>";
                    }
				if ($_REQUEST['momento'.$pfj] != '2')
                    {
                         $this->salida.="<td width=\"15%\" align = left ><input type = radio name= 'momento$pfj' value = '2'>DURANTE</td>";
                    }
				else
                    {
                         $this->salida.="<td width=\"15%\" align = left ><input type = radio name= 'momento$pfj' checked value = '2'>DURANTE</td>";
                    }
				if ($_REQUEST['momento'.$pfj] != '3')
                    {
                         $this->salida.="<td width=\"20%\" align = left ><input type = radio name= 'momento$pfj' value = '3'>DESPUES</td>";
                    }
				else
                    {
                         $this->salida.="<td width=\"20%\" align = left ><input type = radio name= 'momento$pfj' checked value = '3'>DESPUES</td>";
                    }
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"modulo_list_claro\">";
				if ($_REQUEST['desayuno'.$pfj] != '1')
                    {
                         $this->salida.="<td  colspan = 3 width=\"50%\" align = left ><input type = checkbox name= 'desayuno$pfj' value = '1'>DESAYUNO</td>";
                    }
				else
                    {
                         $this->salida.="<td  colspan = 3 width=\"50%\" align = left ><input type = checkbox name= 'desayuno$pfj' checked value = '1'>DESAYUNO</td>";
                    }
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"modulo_list_claro\">";
				if ($_REQUEST['almuerzo'.$pfj] != '1')
                    {
                         $this->salida.="<td  colspan = 3 width=\"50%\" align = left ><input type = checkbox name= 'almuerzo$pfj' value = '1'>ALMUERZO</td>";
                    }
				else
                    {
                         $this->salida.="<td  colspan = 3 width=\"50%\" align = left ><input type = checkbox name= 'almuerzo$pfj' checked value = '1'>ALMUERZO</td>";
                    }
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"modulo_list_claro\">";
				if ($_REQUEST['cena'.$pfj] != '1')
                    {
                         $this->salida.="<td  colspan = 3 width=\"50%\" align = left ><input type = checkbox name= 'cena$pfj' value = '1'>CENA</td>";
                    }
				else
                    {
                         $this->salida.="<td  colspan = 3 width=\"50%\" align = left ><input type = checkbox name= 'cena$pfj' checked value = '1'>CENA</td>";
                    }
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";
			//OPCION 4
				$this->salida.="<tr class=\"modulo_list_claro\">";
                    if ($_REQUEST['opcion'.$pfj] != '4')
                    {
                         $this->salida.="<td width=\"10%\" class=".$this->SetStyle("opcion4")." align=\"left\" ><input type = radio name= 'opcion$pfj' value = 4>OPCION 4</td>";
                    }
                    else
                    {
                         $this->salida.="<td width=\"10%\" class=".$this->SetStyle("opcion4")." align=\"left\" ><input type = radio checked name= 'opcion$pfj' value = 4>OPCION 4</td>";
                    }
				$this->salida.="<td width=\"50%\"align=\"left\" >";
				$this->salida.="<table>";
				$this->salida.="<tr class=\"modulo_list_claro\">";
				$this->salida.="<td colspan = 8 width=\"50%\" align = left >HORA ESPECIFICA</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"modulo_list_claro\">";

				$hora_especifica = $_REQUEST['opH'.$pfj];
				if (($hora_especifica[6])  != '06 am')
                    {
                         $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[6]' value = '06 am'>06</td>";
                    }
				else
                    {
                         $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox checked name= 'opH".$pfj."[6]' value = '06 am'>06</td>";
                    }

				if ((($hora_especifica[9])  != '09 am'))
                    {
                         $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[9]' value = '09 am'>09</td>";
                    }
				else
                    {
                         $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox checked name= 'opH".$pfj."[9]' value = '09 am'>09</td>";
                    }

				if ((($hora_especifica[12])  != '12 pm'))
                    {
                         $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[12]' value = '12 pm'>12</td>";
                    }
				else
                    {
                         $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox checked name= 'opH".$pfj."[12]' value = '12 pm'>12</td>";
                    }

				if ((($hora_especifica[15])  != '03 pm'))
                    {
                         $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[15]' value = '03 pm'>15</td>";
                    }
				else
                    {
                         $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox checked name= 'opH".$pfj."[15]' value = '03 pm'>15</td>";
                    }

				if ((($hora_especifica[18])  != '06 pm'))
                    {
                         $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[18]' value = '06 pm'>18</td>";
                    }
				else
                    {
                         $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox checked name= 'opH".$pfj."[18]' value = '06 pm'>18</td>";
                    }

				if ((($hora_especifica[21])  != '09 pm'))
                    {
                         $this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[21]' value = '09 pm'>21</td>";
                    }
				else
                    {
                         $this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox checked name= 'opH".$pfj."[21]' value = '09 pm'>21</td>";
                    }

				if ((($hora_especifica[24])  != '00 am'))
                    {
                         $this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[24]' value = '00 am'>24</td>";
                    }
				else
                    {
                         $this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[24]' value = '00 am'>24</td>";
                    }

				if ((($hora_especifica[3])  != '03 am'))
                    {
                         $this->salida.="<td class =label_error colspan = 1 width=\"15%\" align = left ><input type = checkbox name= 'opH".$pfj."[3]' value = '03 am'>03</td>";
                    }
				else
                    {
                         $this->salida.="<td class =label_error colspan = 1 width=\"15%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[3]' value = '03 am'>03</td>";
                    }

				$this->salida.="</tr>";

				$this->salida.="<tr class=\"modulo_list_claro\">";
				if ((($hora_especifica[7])  != '07 am'))
                    {
                         $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[7]' value = '07 am'>07</td>";
                    }
				else
                    {
                         $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[7]' value = '07 am'>07</td>";
                    }

				if ((($hora_especifica[10])  != '10 am'))
                    {
                         $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[10]' value = '10 am'>10</td>";
                    }
				else
                    {
                         $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[10]' value = '10 am'>10</td>";
                    }

				if ((($hora_especifica[13])  != '01 pm'))
                    {
                         $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[13]' value = '01 pm'>13</td>";
                    }
				else
                    {
                         $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[13]' value = '01 pm'>13</td>";
                    }

				if ((($hora_especifica[16])  != '04 pm'))
                    {
                         $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[16]' value = '04 pm'>16</td>";
                    }
				else
                    {
                         $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[16]' value = '04 pm'>16</td>";
                    }

				if ((($hora_especifica[19])  != '07 pm'))
                    {
                         $this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[19]' value = '07 pm'>19</td>";
                    }
				else
                    {
                    	$this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[19]' value = '07 pm'>19</td>";
                    }

				if ((($hora_especifica[22])  != '10 pm'))
                    {
                         $this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[22]' value = '10 pm'>22</td>";
                    }
				else
                    {
                         $this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[22]' value = '10 pm'>22</td>";
                    }

				if ((($hora_especifica[1])  != '01 am'))
                    {
                         $this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[1]' value = '01 am'>01</td>";
                    }
				else
                    {
                         $this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[1]' value = '01 am'>01</td>";
                    }

				if ((($hora_especifica[4])  != '04 am'))
                    {
                         $this->salida.="<td class =label_error colspan = 1 width=\"15%\" align = left ><input type = checkbox name= 'opH".$pfj."[4]' value = '04 am'>04</td>";
                    }
				else
                    {
                         $this->salida.="<td class =label_error colspan = 1 width=\"15%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[4]' value = '04 am'>04</td>";
                    }

				$this->salida.="</tr>";
				$this->salida.="<tr class=\"modulo_list_claro\">";
				if ((($hora_especifica[8])  != '08 am'))
                    {
                         $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[8]' value = '08 am'>08</td>";
                    }
				else
                    {
                         $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[8]' value = '08 am'>08</td>";
                    }

				if ((($hora_especifica[11])  != '11 am'))
                    {
                         $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[11]' value = '11 am'>11</td>";
                    }
				else
                    {
                         $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[11]' value = '11 am'>11</td>";
                    }

				if ((($hora_especifica[14])  != '02 pm'))
                    {
                         $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[14]' value = '02 pm'>14</td>";
                    }
				else
                    {
                         $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[14]' value = '02 pm'>14</td>";
                    }

				if ((($hora_especifica[17])  != '05 pm'))
                    {
                         $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[17]' value = '05 pm'>17</td>";
                    }
				else
                    {
                         $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[17]' value = '05 pm'>17</td>";
                    }

				if ((($hora_especifica[20])  != '08 pm'))
                    {
                         $this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[20]' value = '08 pm'>20</td>";
                    }
				else
                    {
                         $this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[20]' value = '08 pm'>20</td>";
                    }

				if ((($hora_especifica[23])  != '11 pm'))
                    {
                         $this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[23]' value = '11 pm'>23</td>";
                    }
				else
                    {
                         $this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[23]' value = '11 pm'>23</td>";
                    }

				if ((($hora_especifica[2])  != '02 am'))
                    {
                         $this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[2]' value = '02 am'>02</td>";
                    }
				else
                    {
                         $this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[2]' value = '02 am'>02</td>";
                    }

				if ((($hora_especifica[5])  != '05 am'))
                    {
                         $this->salida.="<td class =label_error colspan = 1 width=\"15%\" align = left ><input type = checkbox name= 'opH".$pfj."[5]' value = '05 am'>05</td>";
                    }
				else
                    {
                         $this->salida.="<td class =label_error colspan = 1 width=\"15%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[5]' value = '05 am'>05</td>";
                    }
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";
			//OPCION 5
				$this->salida.="<tr class=\"modulo_list_claro\">";
				if ($_REQUEST['opcion'.$pfj] != '5')
				{
					$this->salida.="<td width=\"10%\" class=".$this->SetStyle("opcion5")." align=\"left\" ><input type = radio name= 'opcion$pfj' value = 5>OPCION 5</td>";
				}
				else
				{
					$this->salida.="<td width=\"10%\" class=".$this->SetStyle("opcion5")." align=\"left\" ><input type = radio checked name= 'opcion$pfj' value = 5>OPCION 5</td>";
				}
				$this->salida.="<td width=\"50%\"align=\"left\" >";
				$this->salida.="<table>";
				$this->salida.="<tr class=\"modulo_list_claro\">";
				$this->salida.="<td  colspan = 3 width=\"50%\" align = left >DESCRIBA LA FRECUENCIA PARA EL SUMINISTRO DEL MEDICAMENTO</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"modulo_list_claro\">";
				if (($_REQUEST['frecuencia_suministro'.$pfj])  == '')
               	{
                    	$this->salida.="<td colspan = 3 width=\"50%\" align='left' ><textarea style = \"width:80%\" class='textarea' name = 'frecuencia_suministro$pfj' cols = 60 rows = 5></textarea></td>" ;
                    }
				else
                    {
                         $this->salida.="<td colspan = 3 width=\"50%\" align='left' ><textarea style = \"width:80%\" class='textarea' name = 'frecuencia_suministro$pfj' cols = 60 rows = 5>".$_REQUEST['frecuencia_suministro'.$pfj]."</textarea></td>" ;
                    }
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";

				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";
			//cantidad
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td width=\"20%\"align=\"left\" >CANTIDAD</td>";
				$this->salida.="<td width=\"60%\" align = left >";
				$this->salida.="<table>";
				$this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="<td class=".$this->SetStyle("cantidad")." width=\"5%\" align = left >CANTIDAD</td>";
                    $this->salida.="<td  width=\"5%\" align='left' ><input type='text' class='input-text' size = 5 name = 'cantidad$pfj'   value =\"".$_REQUEST['cantidad'.$pfj]."\"></td>" ;
                    $unidad_venta = $this->Unidad_Venta($arreglo[1]);
                    $frase = ' ';
                    if ($unidad_venta[contenido_unidad_venta]!='')
                    {
                         $frase = ' por ';
                    }
                    $this->salida.="<td width=\"30%\" align='left' ><input type='text' class='input-text' readonly size = 30 name = 'unidad$pfj'   value = '".$unidad_venta[descripcion]."".$frase."".$unidad_venta[contenido_unidad_venta]."'></td>" ;
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";
			//fin de cantidad
               if($via_admon[0]['tipo_via_id'])
               {
               	//Relacion del medicamento con una solucion
                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="<td width=\"15%\" align=\"left\" >SOLUCION PARA LA MEZCLA DEL MEDICAMENTO</td>";
                    $this->salida.="<td valign=\"top\">";
                    $this->salida.="<select size = 1 name = 'solucion$pfj'  class =\"select\">";
                    $this->salida.="<option value = '-1' selected>-Seleccione-</option>";
                    $tiposSoluciones = $this->tiposSoluciones();
                    for($i=0;$i<sizeof($tiposSoluciones);$i++)
                    {
                         $id = $tiposSoluciones[$i][solucion_id];
                         $opcion = $tiposSoluciones[$i][descripcion];
                         if (($_REQUEST['solucion'.$pfj])  != $id){
                              $this->salida.="<option value = '$id'>$opcion</option>";
                         }else{
	                         $this->salida.="<option value = '$id' selected >$opcion</option>";
                         }
                    }
                    $this->salida.="</select>&nbsp&nbsp&nbsp&nbsp&nbsp;";
                    $this->salida.="<label>CANTIDAD</label>&nbsp&nbsp;";
                    $this->salida.="<select size = 1 name = 'solucionUnidad$pfj'  class =\"select\">";
                    $this->salida.="<option value = '-1' selected>-Seleccione-</option>";
                    $tiposUnidades = $this->tiposUnidadesSoluciones();
                    for($i=0;$i<sizeof($tiposUnidades);$i++)
                    {
                         $id = $tiposUnidades[$i][cantidad_id];
                         if (($_REQUEST['solucionUnidad'.$pfj])  != $id){
                              $this->salida.="<option value = '$id'>".$tiposUnidades[$i][cantidad]."  ".$tiposUnidades[$i][unidad_id]."</option>";
                         }else{
                         	$this->salida.="<option value = '$id' selected >".$tiposUnidades[$i][cantidad]."  ".$tiposUnidades[$i][unidad_id]."</option>";
                         }
                    }
                    $this->salida.="</select>";
                    $this->salida.="</td>";
                    $this->salida.="</tr>";
                    //fin soluciones
               }  
               $this->salida.="<tr class=\"$estilo\">";
               $this->salida.="<td width=\"15%\" align=\"left\" >OBSERVACIONES E INDICACION DE SUMINISTRO</td>";

               if (($_REQUEST['observacion'.$pfj])  == '')
               {
                    $this->salida.="<td width=\"65%\"align='center'><textarea style = \"width:80%\" class='textarea' name = 'observacion$pfj' cols = 60 rows = 5>$observacion</textarea></td>" ;
               }
               else
               {
                    $this->salida.="<td width=\"50%\"align='center'><textarea style = \"width:80%\" class='textarea' name = 'observacion$pfj' cols = 60 rows = 5>".$_REQUEST['observacion'.$pfj]."</textarea></td>" ;
               }
               $this->salida.="</tr>";
               if($arreglo[0] == 'NO POS')
               {
                    $this->salida.="<tr class=\"$estilo\">";
                    if ($_REQUEST['no_pos_paciente'.$pfj]  == '1')
                    {
                         $this->salida.="  <td class = label_error colspan = 2 align=\"center\" width=\"5%\"><input type = \"checkbox\" name= 'no_pos_paciente$pfj' checked value = 1>FORMULACION NO POS A PETICION DEL PACIENTE</td>";
                    }
                    else
                    {
                         $this->salida.="  <td class = label_error colspan = 2 align=\"center\" width=\"5%\"><input type = \"checkbox\" name= 'no_pos_paciente$pfj' value = 1 >FORMULACION NO POS A PETICION DEL PACIENTE</td>";
                    }
                    $this->salida.="</tr>";
               }

          	if($this->servicio!=3){
               	$this->salida.="<tr class=\"$estilo\">";
               if(!empty($_REQUEST['sw_ambulatorio'.$pfj]) || !empty($_SESSION['MEDICAMENTOS'.$pfj]['sw_ambulatorio'])){
                    $che='checked';
               }else{
                    $che='';
               }
               $this->salida.="  <td class = 'label' colspan = 2 align=\"center\" width=\"5%\"><input type = \"checkbox\" name= 'sw_ambulatorio$pfj' value = 1  $che>MEDICAMENTO AMBULATORIO</td>";
               $this->salida.="</tr>";
	     }

          $this->salida.="</table><br>";
          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"0\"><tr>";
          $this->salida .= "<td   align=\"center\"><input class=\"input-submit\" name= 'guardar_formula$pfj' type=\"submit\" value=\"GUARDAR FORMULA\"></td>";

          $this->salida .= "</form>";
          $accion3=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false, array('accion'.$pfj=>''));
          $this->salida .= "<form name=\"forma\" action=\"$accion3\" method=\"post\">";
          $this->salida .= "<td   align=\"center\"><input class=\"input-submit\" name= 'cancelar$pfj' type=\"submit\" value=\"CANCELAR\"></form></td>";
          $this->salida.="</tr></table>";
          return true;
	}

	//*
	function frmFormaDiagnosticos($vectorD)
	{
		$pfj=$this->frmPrefijo;
		$this->salida= ThemeAbrirTablaSubModulo('DIAGNOSTICOS PARA LA JUSTIFICACION DEL MEDICAMENTO');
		$accionD=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Diagnosticos', 'Of'.$pfj=>$_REQUEST['Of'.$pfj],'paso1'=>$_REQUEST['paso1'.$pfj], 'codigo'.$pfj=>$_REQUEST['codigo'.$pfj], 'diagnostico'.$pfj=>$_REQUEST['diagnostico'.$pfj]));
		$this->salida .= "<form name=\"formades$pfj\" action=\"$accionD\" method=\"post\">";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"75%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"5\">BUSQUEDA AVANZADA DE DIAGNOSTICOS </td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td width=\"4%\">CODIGO:</td>";
		$this->salida .="<td width=\"5%\" align='center'><input type='text' class='input-text' size = 6 maxlength = 6	name = 'codigo$pfj'></td>" ;
		$this->salida.="<td width=\"9%\">DIAGNOSTICO:</td>";
		$this->salida .="<td width=\"55%\" align='center'><input type='text' size =50 class='input-text' 	name = 'diagnostico$pfj'   value =\"".$_REQUEST['diagnostico'.$pfj]."\"        ></td>" ;
		$this->salida .= "<td  width=\"7%\" align=\"center\"><input class=\"input-submit\" name=\"buscar$pfj\" type=\"submit\" value=\"BUSCAR\"></td>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";
		$this->salida.="</form>";
		$accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar_varios_diagnosticos'));
		$this->salida .= "<form name=\"formades$pfj\" action=\"$accionI\" method=\"post\">";
		if ($vectorD)
		{
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"3\">RESULTADO DE LA BUSQUEDA</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td width=\"10%\">CODIGO</td>";
			$this->salida.="  <td width=\"65%\">DIAGNOSTICO</td>";
			$this->salida.="  <td width=\"5%\">OPCION</td>";
			$this->salida.="</tr>";
			for($i=0;$i<sizeof($vectorD);$i++)
			{
				$codigo          = $vectorD[$i][diagnostico_id];
				$diagnostico    = $vectorD[$i][diagnostico_nombre];
				if( $i % 2){$estilo='modulo_list_claro';}
				else {$estilo='modulo_list_oscuro';}
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="  <td align=\"center\" width=\"10%\">$codigo</td>";
				$this->salida.="  <td align=\"left\" width=\"65%\">$diagnostico</td>";
				$this->salida.="  <td align=\"center\" width=\"5%\"><input type = checkbox name= 'opD".$pfj."[$i]' value = '".$codigo.",".$diagnostico."'></td>";
				$this->salida.="</tr>";
			}
			$this->salida.="<tr class=\"$estilo\">";
			$this->salida .= "<td align=\"right\" colspan=\"3\"><input class=\"input-submit\" name=\"guardardiagnostico$pfj\" type=\"submit\" value=\"GUARDAR\"></td>";
			$this->salida.="</tr>";
			$this->salida.="</table><br>";
			$var=$this->RetornarBarraDiagnosticos_Avanzada();
			if(!empty($var))
			{
				$this->salida .= "  <br><table border=\"0\" width=\"60%\" align=\"center\">";// class=\"modulo_table\"
				$this->salida .= "  <tr>";
				$this->salida .= "  <td width=\"100%\" align=\"center\">";
				$this->salida .=$var;
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$this->salida .= "  </table><br>";
			}
		}
		$this->salida .= "</form>";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}



	//cor - jea - ads - *
	function RetornarBarraDiagnosticos_Avanzada()//Barra paginadora
	{
		$pfj=$this->frmPrefijo;
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso1'.$pfj];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Diagnosticos',
		'conteo'.$pfj=>$this->conteo,'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
		'codigo'.$pfj=>$_REQUEST['codigo'.$pfj],
		'diagnostico'.$pfj=>$_REQUEST['diagnostico'.$pfj]));

		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset(1)."&paso1$pfj=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso-1)."&paso1$pfj=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($i)."&paso1$pfj=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso+1)."&paso1$pfj=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($numpasos)."&paso1$pfj=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($i)."&paso1$pfj=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso+1)."&paso1$pfj=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($numpasos)."&paso1$pfj=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
		if(($_REQUEST['Of'.$pfj])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		else
		{
			if($numpasos>10)
			{
				$valor=10+5;
			}
			else
			{
				$valor=$numpasos+5;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		return $salida;
	}


	//*
     function Consultar_Justificacion_Medicamentos_No_Pos()
     {
          $pfj=$this->frmPrefijo;

          if ($_REQUEST['consultar_just'.$pfj]==1)
          {
               $this->salida= ThemeAbrirTablaSubModulo('CONSULTA DE LA JUSTIFICACION');
          }
          else
          {
               $this->salida= ThemeAbrirTablaSubModulo('MODIFICACION DE LA JUSTIFICACION');
          }

          $vector_justificacion = $this->Consulta_Datos_Justificacion($_SESSION['MEDICAMENTOSM'.$pfj][codigo_producto], $_SESSION['MEDICAMENTOSM'.$pfj][evolucion]);

          if(empty($_SESSION['DIAGNOSTICOSM'.$pfj]))
          {
               $vector_diagnosticos = $this->Consulta_Diagnosticos_Justificacion($vector_justificacion[0][hc_justificaciones_no_pos_hosp]);
          }
          if($vector_justificacion[0][sw_existe_alternativa_pos]==1)
          {
               $vector_alternartiva = $this->Consulta_Alternativas_Pos($vector_justificacion[0][hc_justificaciones_no_pos_hosp]);
          }

          $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'modificar_justificacion_no_pos', 'hc_justificaciones_no_pos_hosp'.$pfj =>$vector_justificacion[0][hc_justificaciones_no_pos_hosp]));
          $this->salida .= "<form name=\"formamodjus$pfj\" action=\"$accion\" method=\"post\">";

    		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
          $this->salida .= $this->SetStyle("MensajeError");
          $this->salida.="</table>";

          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
    		$this->salida.="<tr class=\"modulo_table_list_title\">";
          if ($_REQUEST['consultar_just'.$pfj]==1)
          {
               $this->salida.="  <td align=\"center\" colspan=\"5\">CONSULTA DE LA JUSTIFICACION DE MEDICAMENTOS NO POS</td>";
          }
          else
          {
               $this->salida.="  <td align=\"center\" colspan=\"5\">MODIFICACION DE LA JUSTIFICACION DE MEDICAMENTOS NO POS</td>";
          }
          $this->salida.="</tr>";

     	if( $i % 2){ $estilo='modulo_list_claro';}
          else {$estilo='modulo_list_oscuro';}

		//datos del medicamento
          $this->salida.="<tr class=\"modulo_table_title\">";
          $this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >DATOS DEL MEDICAMENTO</td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
          $this->salida.="  <td align=\"center\" width=\"5%\">CODIGO</td>";
          $this->salida.="  <td align=\"center\" width=\"20%\">PRODUCTO</td>";
          $this->salida.="  <td align=\"center\" width=\"20%\">PRINCIPIO ACTIVO</td>";
          $this->salida.="  <td align=\"center\" width=\"20%\">CONCENTRACION</td>";
          $this->salida.="  <td align=\"center\" width=\"15%\">FORMA</td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
          $this->salida.="<td align=\"center\" width=\"5%\">".$vector_justificacion[0][codigo_producto]."</td>";
          $this->salida.="<td align=\"center\" width=\"20%\" >".$_SESSION['MEDICAMENTOSM'.$pfj][producto]."</td>";
          $this->salida.="<td align=\"center\" width=\"20%\" >".$_SESSION['MEDICAMENTOSM'.$pfj][principio_activo]."</td>";
          $this->salida.="<td align=\"center\" width=\"20%\" >".$vector_justificacion[0][concentracion_forma_farmacologica]." ".$vector_justificacion[0][unidad_medida_medicamento_id]."</td>";
          $this->salida.="<td align=\"center\" width=\"15%\" >".$vector_justificacion[0][forma]."</td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td colspan = 5>";
          $this->salida.="<table>";
          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="  <td colspan = 2 align=\"left\" width=\"80%\">VIA DE ADMINISTRACION: ".$_SESSION['MEDICAMENTOSM'.$pfj][via]."</td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="  <td align=\"left\" width=\"20%\">DOSIS:</td>";
          $e=$_SESSION['MEDICAMENTOSM'.$pfj][dosis]/(floor($_SESSION['MEDICAMENTOSM'.$pfj][dosis]));
          if($e==1)
          {
               $this->salida.="  <td align=\"left\" width=\"60%\">".floor($_SESSION['MEDICAMENTOSM'.$pfj][dosis])."  ".$_SESSION['MEDICAMENTOSM'.$pfj][unidad_dosificacion]."</td>";
          }
          else
          {
               $this->salida.="  <td align=\"left\" width=\"60%\">".$_SESSION['MEDICAMENTOSM'.$pfj][dosis]."  ".$_SESSION['MEDICAMENTOSM'.$pfj][unidad_dosificacion]."</td>";
          }
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="  <td align=\"left\" width=\"20%\">CANTIDAD:</td>";
          $e=($_SESSION['MEDICAMENTOSM'.$pfj][cantidad])/(floor($_SESSION['MEDICAMENTOSM'.$pfj][cantidad]));
               if ($vector1[$i][contenido_unidad_venta])
          {
               if($e==1)
               {
                    $this->salida.="  <td align=\"left\" width=\"60%\">".floor($_SESSION['MEDICAMENTOSM'.$pfj][cantidad])." ".$_SESSION['MEDICAMENTOSM'.$pfj][descripcion]." por ".$_SESSION['MEDICAMENTOSM'.$pfj][contenido_unidad_venta]."</td>";
               }
               else
               {
                    $this->salida.="  <td align=\"left\" width=\"60%\">".$_SESSION['MEDICAMENTOSM'.$pfj][cantidad]." ".$_SESSION['MEDICAMENTOSM'.$pfj][descripcion]." por ".$_SESSION['MEDICAMENTOSM'.$pfj][contenido_unidad_venta]."</td>";
               }
          }
          else
          {
               if($e==1)
               {
                    $this->salida.="  <td align=\"left\" width=\"60%\">".floor($_SESSION['MEDICAMENTOSM'.$pfj][cantidad])." ".$_SESSION['MEDICAMENTOSM'.$pfj][descripcion]."</td>";
               }
               else
               {
                    $this->salida.="  <td align=\"left\" width=\"60%\">".$_SESSION['MEDICAMENTOSM'.$pfj][cantidad]." ".$_SESSION['MEDICAMENTOSM'.$pfj][descripcion]."</td>";
               }
          }
          $this->salida.="</tr>";
          $this->salida.="</td>";
          $this->salida.="</table>";

          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td colspan = 5>";
          $this->salida.="<table>";
    		$this->salida.="<tr class=\"$estilo\">";
          $this->salida.="  <td align=\"left\" width=\"20%\">OBSERVACION:</td>";
          $this->salida.="  <td align=\"left\" width=\"60%\">".$_SESSION['MEDICAMENTOSM'.$pfj][observacion]."</td>";
          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="</table>";
          $this->salida.="</td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\">";
          $this->salida.="<table>";
          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td colspan=\"1\" class=".$this->SetStyle("dosis_dia")." width=\"20%\"align=\"left\" >DOSIS POR DIA</td>";
          if ($_REQUEST['consultar_just'.$pfj]==1)
          {
               $this->salida.="<td colspan=\"1\" width=\"60\" align=\"left\" ><input type='text' readonly class='input-text' size = 40 name = 'dosis_dia$pfj'   value =\"".$vector_justificacion[0][dosis_dia]."\"></td>" ;
          }
          else
          {
               if($_REQUEST['dosis_dia'.$pfj] != '')
               {
                    $this->salida.="<td colspan=\"1\" width=\"60\" align=\"left\" ><input type='text' class='input-text' size = 40 name = 'dosis_dia$pfj'   value =\"".$_REQUEST['dosis_dia'.$pfj]."\"></td>" ;
               }
               else
               {
                    $this->salida.="<td colspan=\"1\" width=\"60\" align=\"left\" ><input type='text' class='input-text' size = 40 name = 'dosis_dia$pfj'   value =\"".$vector_justificacion[0][dosis_dia]."\"></td>" ;
               }
          }
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td colspan=\"1\" class=".$this->SetStyle("duracion_tratamiento")." width=\"20%\"align=\"left\" >DIAS DE TRATAMIENTO</td>";

          if ($_REQUEST['consultar_just'.$pfj]==1)
          {
                    $this->salida.="<td colspan=\"1\"  width=\"60\" align=\"left\" ><input readonly type='text' class='input-text' size = 60 name = 'duracion_tratamiento$pfj'   value =\"".$vector_justificacion[0][duracion]."\"></td>" ;
          }
          else
          {
               if ($_REQUEST['duracion_tratamiento'.$pfj] != '')
               {
                    $this->salida.="<td colspan=\"1\" width=\"60\" align=\"left\" ><input type='text' class='input-text' size = 60 name = 'duracion_tratamiento$pfj'   value =\"".$_REQUEST['duracion_tratamiento'.$pfj]."\"></td>" ;
               }
               else
               {
                    $this->salida.="<td colspan=\"1\" width=\"60\" align=\"left\" ><input type='text' class='input-text' size = 60 name = 'duracion_tratamiento$pfj'   value =\"".$vector_justificacion[0][duracion]."\"></td>" ;
               }
          }
          $this->salida.="</tr>";
          $this->salida.="</table>";
          $this->salida.="</td>";
          $this->salida.="</tr>";

	//Diagnosticos
          $this->salida.="<script>";
          $this->salida.="function diagnostico1(url){\n";
          $this->salida.="document.formamodjus$pfj.action=url;\n";
          $this->salida.="document.formamodjus$pfj.submit();}";
          $this->salida.="</script>";
          $this->salida.="<tr class=\"modulo_table_title\">";
          $this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >DIAGNOSTICO</td>";
          $this->salida.="</tr>";

          if ($_REQUEST['consultar_just'.$pfj]==1)
          {
               if ($_SESSION['DIAGNOSTICOSM'.$pfj])
               {
                    foreach ($_SESSION['DIAGNOSTICOSM'.$pfj] as $k=>$v)
                    {
                         $this->salida.="<tr class=\"modulo_list_claro\">";
                         $this->salida.="<td colspan = 5>".$k." - ".$v."</td>";
                         $this->salida.="</tr>";
                    }
               }
          }
          else
          {
               if ($_SESSION['DIAGNOSTICOSM'.$pfj])
               {
                    foreach ($_SESSION['DIAGNOSTICOSM'.$pfj] as $k=>$v)
                    {
                         $accion5=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'eliminardiagnosticom', 'diagnostico'.$pfj=>$k));
                         $this->salida.="<tr class=\"modulo_list_claro\">";
                         $this->salida.="  <td class=\"$estilo\" align=\"center\" width=\"5%\"><a href='javascript:diagnostico1(\"$accion5\")'><img title=\"Eliminar\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
                         $this->salida.="<td colspan = 4>".$k." - ".$v."</td>";
                         $this->salida.="  <input type='hidden' name = id$k$pfj' value = ".$k.">";
                         $this->salida.="</tr>";
                    }
               }
               $this->salida.="<tr class=\"modulo_list_oscuro\">";
               $accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'agregar_diagnosticos'));
               $this->salida.="  <td colspan = 5 align=\"center\" width=\"63%\"><a href='javascript:diagnostico1(\"$accion1\")'><font color='#190CA2'><b><u>AGREGAR MAS DIAGNOSTICOS</u></b></font></a></td>";
               $this->salida.="</tr>";
          }

	//descripcion del caso clinico
          $this->salida.="<tr class=\"modulo_table_title\">";
          $this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >DESCRIPCION DEL CASO CLINICO</td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"$estilo\">";
          if ($_REQUEST['consultar_just'.$pfj]==1)
          {
               $this->salida.="<td colspan = 5 width=\"80%\" align='center' ><textarea readonly style = \"width:80%\" class='textarea' name = 'descripcion_caso_clinico$pfj' cols = 60 rows = 3>".$vector_justificacion[0][descripcion_caso_clinico]."</textarea></td>" ;
          }
          else
          {
               if (($_REQUEST['descripcion_caso_clinico'.$pfj])  == '')
               {
                    $this->salida.="<td colspan = 5 width=\"80%\" align='center' ><textarea style = \"width:80%\" class='textarea' name = 'descripcion_caso_clinico$pfj' cols = 60 rows = 3>".$vector_justificacion[0][descripcion_caso_clinico]."</textarea></td>" ;
               }
               else
               {
                    $this->salida.="<td colspan = 5 width=\"80%\" align='center' ><textarea style = \"width:80%\" class='textarea' name = 'descripcion_caso_clinico$pfj' cols = 60 rows = 3>".$_REQUEST['descripcion_caso_clinico'.$pfj]."</textarea></td>" ;
               }
          }
          $this->salida.="</tr>";


		//alternativas pos previamente utilizadas pendiente
          $this->salida.="<tr class=\"modulo_table_title\">";
          $this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >ALTERNATIVAS POS PREVIAMENTE UTILIZADAS</td>";
          $this->salida.="</tr>";
		for ($j=1;$j<3;$j++)
		{
			if ($j==1)
			{
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >PRIMERA POSIBILIDAD TERAPEUTICA POS</td>";
				$this->salida.="</tr>";
			}
			else
			{
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >SEGUNDA POSIBILIDAD TERAPEUTICA POS</td>";
				$this->salida.="</tr>";
			}

				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\">";
				$this->salida.="<table>";
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"1\" width=\"15%\"align=\"left\" >MEDICAMENTO</td>";
				if ($_REQUEST['consultar_just'.$pfj]==1)
				{
						$this->salida.="<td colspan=\"1\" width=\"28\" align=\"left\" ><input type='text' readonly class='input-text' size = 30 name = 'medicamento_pos$j$pfj'   value =\"".$vector_alternartiva[$j-1][medicamento_pos]."\"></td>" ;
				}
				else
				{
					if($_REQUEST['medicamento_pos'.$j.$pfj]=='')
					{
						$this->salida.="<td colspan=\"1\" width=\"28\" align=\"left\" ><input type='text' class='input-text' size = 30 name = 'medicamento_pos$j$pfj'   value =\"".$vector_alternartiva[$j-1][medicamento_pos]."\"></td>" ;
					}
					else
					{
							$this->salida.="<td colspan=\"1\" width=\"28\" align=\"left\" ><input type='text' class='input-text' size = 30 name = 'medicamento_pos$j$pfj'   value =\"".$_REQUEST['medicamento_pos'.$j.$pfj]."\"></td>" ;
					}
				}

				$this->salida.="<td colspan=\"1\" width=\"18%\"align=\"left\" >PRINCIPIO ACTIVO</td>";
				if ($_REQUEST['consultar_just'.$pfj]==1)
				{
					$this->salida.="<td colspan=\"1\" width=\"20\" align=\"left\" ><input readonly type='text' class='input-text' size = 30 name = 'principio_activo_pos$j$pfj'   value =\"".$vector_alternartiva[$j-1][principio_activo]."\"></td>" ;
				}
				else
				{
					if($_REQUEST['principio_activo_pos'.$j.$pfj]=='')
					{
							$this->salida.="<td colspan=\"1\" width=\"20\" align=\"left\" ><input type='text' class='input-text' size = 30 name = 'principio_activo_pos$j$pfj'   value =\"".$vector_alternartiva[$j-1][principio_activo]."\"></td>" ;
					}
					else
					{
							$this->salida.="<td colspan=\"1\" width=\"20\" align=\"left\" ><input type='text' class='input-text' size = 30 name = 'principio_activo_pos$j$pfj'   value =\"".$_REQUEST['principio_activo_pos'.$j.$pfj]."\"></td>" ;
					}
				}
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\">";
				$this->salida.="<table>";
				$this->salida.="<tr class=\"$estilo\">";

				$this->salida.="<td colspan=\"1\" width=\"15%\"align=\"left\" >DOSIS POR DIA</td>";
				if ($_REQUEST['consultar_just'.$pfj]==1)
				{
					$this->salida.="<td colspan=\"1\" width=\"13\" align=\"left\" ><input readonly type='text' class='input-text' size = 20 name = 'dosis_dia_pos$j$pfj'   value =\"".$vector_alternartiva[$j-1][dosis_dia_pos]."\"></td>" ;
				}
				else
				{
						if($_REQUEST['dosis_dia_pos'.$j.$pfj]=='')
						{
							$this->salida.="<td colspan=\"1\" width=\"13\" align=\"left\" ><input type='text' class='input-text' size = 20 name = 'dosis_dia_pos$j$pfj'   value =\"".$vector_alternartiva[$j-1][dosis_dia_pos]."\"></td>" ;
						}
						else
						{
							$this->salida.="<td colspan=\"1\" width=\"13\" align=\"left\" ><input type='text' class='input-text' size = 20 name = 'dosis_dia_pos$j$pfj'   value =\"".$_REQUEST['dosis_dia_pos'.$j.$pfj]."\"></td>" ;
						}
				}
				$this->salida.="<td colspan=\"1\" width=\"25%\"align=\"left\" >DURACION DEL TRATAMIENTO</td>";
        			if ($_REQUEST['consultar_just'.$pfj]==1)
				{
					  $this->salida.="<td colspan=\"1\" width=\"13\" align=\"left\" ><input readonly type='text' class='input-text' size = 20 name = 'duracion_tratamiento_pos$j$pfj'   value =\"".$vector_alternartiva[$j-1][duracion_pos]."\"></td>" ;
				}
				else
				{
						if($_REQUEST['duracion_tratamiento_pos'.$j.$pfj]=='')
						{
								$this->salida.="<td colspan=\"1\" width=\"13\" align=\"left\" ><input type='text' class='input-text' size = 20 name = 'duracion_tratamiento_pos$j$pfj'   value =\"".$vector_alternartiva[$j-1][duracion_pos]."\"></td>" ;
						}
						else
						{
								$this->salida.="<td colspan=\"1\" width=\"13\" align=\"left\" ><input type='text' class='input-text' size = 20 name = 'duracion_tratamiento_pos$j$pfj'   value =\"".$_REQUEST['duracion_tratamiento_pos'.$j.$pfj]."\"></td>" ;
						}
				}
				if ($_REQUEST['consultar_just'.$pfj]==1)
				{
				    if ($vector_alternartiva[$j-1][sw_no_mejoria]!= '1')
						{
							$this->salida.="<td width=\"14%\"align=\"left\" ><input disabled type = checkbox name= 'sw_no_mejoria$j$pfj' value = 1>NO MEJORIA</td>";
						}
						else
						{
							$this->salida.="<td width=\"14%\"align=\"left\" ><input disabled type = checkbox checked name= 'sw_no_mejoria$j$pfj' value = 1>NO MEJORIA</td>";
						}
				}
				else
				{
				    if (($_REQUEST['sw_no_mejoria'.$j.$pfj] != '1') AND ($vector_alternartiva[$j-1][sw_no_mejoria]!= '1'))
                         {
                              $this->salida.="<td width=\"14%\"align=\"left\" ><input type = checkbox name= 'sw_no_mejoria$j$pfj' value = 1>NO MEJORIA</td>";
                         }
                         else
                         {
                              $this->salida.="<td width=\"14%\"align=\"left\" ><input type = checkbox checked name= 'sw_no_mejoria$j$pfj' value = 1>NO MEJORIA</td>";
                         }
				}
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\">";
				$this->salida.="<table>";
				$this->salida.="<tr class=\"$estilo\">";
        			if ($_REQUEST['consultar_just'.$pfj]==1)
				{
                         if ($vector_alternartiva[$j-1][sw_reaccion_secundaria]!= '1')
                         {
                              $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input disabled type = checkbox name= 'sw_reaccion_secundaria$j$pfj' value = 1>&nbsp; REACCION SECUNDARIA</td>";
                         }
                         else
                         {
                              $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input disabled type = checkbox checked name= 'sw_reaccion_secundaria$j$pfj' value = 1>&nbsp; REACCION SECUNDARIA</td>";
                         }
				}
				else
				{
                         if (($_REQUEST['sw_reaccion_secundaria'.$j.$pfj] != '1') AND ($vector_alternartiva[$j-1][sw_reaccion_secundaria]!= '1'))
                         {
                              $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = checkbox name= 'sw_reaccion_secundaria$j$pfj' value = 1>&nbsp; REACCION SECUNDARIA</td>";
                         }
                         else
                         {
                              $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = checkbox checked name= 'sw_reaccion_secundaria$j$pfj' value = 1>&nbsp; REACCION SECUNDARIA</td>";
                         }
				}
                    if ($_REQUEST['consultar_just'.$pfj]==1)
				{
                         $this->salida.="<td colspan=\"1\" width=\"50%\" align='center' ><textarea readonly style = \"width:80%\" class='textarea' name = 'reaccion_secundaria$j$pfj' cols = 60 rows = 3>".$vector_alternartiva[$j-1][reaccion_secundaria]."</textarea></td>" ;
				}
				else
				{
                         if (($_REQUEST['reaccion_secundaria'.$j.$pfj])  == '')
                         {
                              $this->salida.="<td colspan=\"1\" width=\"50%\" align='center' ><textarea style = \"width:80%\" class='textarea' name = 'reaccion_secundaria$j$pfj' cols = 60 rows = 3>".$vector_alternartiva[$j-1][reaccion_secundaria]."</textarea></td>" ;
                         }
                         else
                         {
                              $this->salida.="<td colspan=\"1\" width=\"50%\" align='center' ><textarea style = \"width:80%\" class='textarea' name = 'reaccion_secundaria$j$pfj' cols = 60 rows = 3>".$_REQUEST['reaccion_secundaria'.$j.$pfj]."</textarea></td>" ;
                         }
				}
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"$estilo\">";
                    if ($_REQUEST['consultar_just'.$pfj]==1)
                    {
               		if ($vector_alternartiva[$j-1][sw_contraindicacion]!= '1')
                         {
                              $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input disabled type = checkbox name= 'sw_contraindicacion$j$pfj' value = 1>&nbsp; CONTRAINDICACION EXPRESA</td>";
                         }
                         else
                         {
                              $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input disabled type = checkbox checked name= 'sw_contraindicacion$j$pfj' value = 1>&nbsp; CONTRAINDICACION EXPRESA</td>";
                         }
				}
				else
                    {
                         if (($_REQUEST['sw_contraindicacion'.$j.$pfj] != '1') AND ($vector_alternartiva[$j-1][sw_contraindicacion]!= '1'))
                         {
                              $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = checkbox name= 'sw_contraindicacion$j$pfj' value = 1>&nbsp; CONTRAINDICACION EXPRESA</td>";
                         }
                         else
                         {
                              $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = checkbox checked name= 'sw_contraindicacion$j$pfj' value = 1>&nbsp; CONTRAINDICACION EXPRESA</td>";
                         }
	               }
               	if ($_REQUEST['consultar_just'.$pfj]==1)
				{
            			$this->salida.="<td colspan=\"1\" width=\"50%\" align='center' ><textarea readonly style = \"width:80%\" class='textarea' name = 'contraindicacion$j$pfj' cols = 60 rows = 3>".$vector_alternartiva[$j-1][contraindicacion]."</textarea></td>" ;
				}
				else
				{
                         if (($_REQUEST['contraindicacion'.$j.$pfj])  == '')
                         {
                              $this->salida.="<td colspan=\"1\" width=\"50%\" align='center' ><textarea style = \"width:80%\" class='textarea' name = 'contraindicacion$j$pfj' cols = 60 rows = 3>".$vector_alternartiva[$j-1][contraindicacion]."</textarea></td>" ;
                         }
                         else
                         {
                              $this->salida.="<td colspan=\"1\" width=\"50%\" align='center' ><textarea style = \"width:80%\" class='textarea' name = 'contraindicacion$j$pfj' cols = 60 rows = 3>".$_REQUEST['contraindicacion'.$j.$pfj]."</textarea></td>" ;
                         }
				}

				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\">";
				$this->salida.="<table>";

		    		$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"2\" width=\"19%\"align=\"center\" >OTRAS</td>";
        			if ($_REQUEST['consultar_just'.$pfj]==1)
				{
                         $this->salida.="<td colspan = 3 width=\"61%\" align='center' ><textarea readonly style = \"width:80%\" class='textarea' name = 'otras$j$pfj' cols = 60 rows = 3>".$vector_alternartiva[$j-1][otras]."</textarea></td>" ;
        			}
				else
				{
					if (($_REQUEST['otras'.$j.$pfj])  == '')
                         {
                              $this->salida.="<td colspan = 3 width=\"61%\" align='center' ><textarea style = \"width:80%\" class='textarea' name = 'otras$j$pfj' cols = 60 rows = 3>".$vector_alternartiva[$j-1][otras]."</textarea></td>" ;
                         }
					else
                         {
                              $this->salida.="<td colspan = 3 width=\"61%\" align='center' ><textarea style = \"width:80%\" class='textarea' name = 'otras$j$pfj' cols = 60 rows = 3>".$_REQUEST['otras'.$j.$pfj]."</textarea></td>" ;
                         }
				}
               $this->salida.="</tr>";
               $this->salida.="</table>";
               $this->salida.="</td>";
               $this->salida.="</tr>";
		}
		//fin de alternativas pos previamente utilizadas

		//criterios que justifican la solicitud
          $this->salida.="<tr class=\"modulo_table_title\">";
          $this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >CRITERIOS DE JUSTIFICACION</td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\">";
          //$this->salida.="<table>";
          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";

          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >JUSTIFICACION DE LA SOLICITUD:</td>";
          $this->salida.="</tr>";
          $this->salida.="<tr class=\"$estilo\">";
	     if ($_REQUEST['consultar_just'.$pfj]==1)
          {
               $this->salida.="<td colspan=\"5\" width=\"80%\" align='left' ><textarea readonly style = \"width:100%\" class='textarea' name = 'justificacion_solicitud$pfj' cols = 60 rows = 3>".$vector_justificacion[0][justificacion]."</textarea></td>" ;
          }
          else
          {
          	if (($_REQUEST['justificacion_solicitud'.$pfj])  == '')
               {
                    $this->salida.="<td colspan=\"5\" width=\"80%\" align='left' ><textarea style = \"width:100%\" class='textarea' name = 'justificacion_solicitud$pfj' cols = 60 rows = 3>".$vector_justificacion[0][justificacion]."</textarea></td>" ;
               }
          	else
               {
                    $this->salida.="<td colspan=\"5\" width=\"80%\" align='left' ><textarea style = \"width:100%\" class='textarea' name = 'justificacion_solicitud$pfj' cols = 60 rows = 3>".$_REQUEST['justificacion_solicitud'.$pfj]."</textarea></td>" ;
               }
          }

          $this->salida.="</tr>";
		$this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >VENTAJAS DE ESTE MEDICAMENTO:</td>";
          $this->salida.="</tr>";
          $this->salida.="<tr class=\"$estilo\">";
          if ($_REQUEST['consultar_just'.$pfj]==1)
          {
	          $this->salida.="<td colspan=\"5\" width=\"80%\" align='left' ><textarea readonly style = \"width:100%\" class='textarea' name = 'ventajas_medicamento$pfj' cols = 60 rows = 3>".$vector_justificacion[0][ventajas_medicamento]."</textarea></td>" ;
          }
          else
          {
		     if (($_REQUEST['ventajas_medicamento'.$pfj])  == '')
               {
                    $this->salida.="<td colspan=\"5\" width=\"80%\" align='left' ><textarea style = \"width:100%\" class='textarea' name = 'ventajas_medicamento$pfj' cols = 60 rows = 3>".$vector_justificacion[0][ventajas_medicamento]."</textarea></td>" ;
               }
               else
               {
                    $this->salida.="<td colspan=\"5\" width=\"80%\" align='left' ><textarea style = \"width:100%\" class='textarea' name = 'ventajas_medicamento$pfj' cols = 60 rows = 3>".$_REQUEST['ventajas_medicamento'.$pfj]."</textarea></td>" ;
               }
          }
          $this->salida.="</tr>";
          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >VENTAJAS DEL TRATAMIENTO:</td>";
          $this->salida.="</tr>";
          $this->salida.="<tr class=\"$estilo\">";
          if ($_REQUEST['consultar_just'.$pfj]==1)
          {
     		$this->salida.="<td colspan=\"5\" width=\"80%\" align='left' ><textarea readonly style = \"width:100%\" class='textarea' name = 'ventajas_tratamiento$pfj' cols = 60 rows = 3>".$vector_justificacion[0][ventajas_tratamiento]."</textarea></td>" ;
          }
          else
          {
     		if (($_REQUEST['ventajas_tratamiento'.$pfj])  == '')
               {
                    $this->salida.="<td colspan=\"5\" width=\"80%\" align='left' ><textarea style = \"width:100%\" class='textarea' name = 'ventajas_tratamiento$pfj' cols = 60 rows = 3>".$vector_justificacion[0][ventajas_tratamiento]."</textarea></td>" ;
               }
               else
               {
                    $this->salida.="<td colspan=\"5\" width=\"80%\" align='left' ><textarea style = \"width:100%\" class='textarea' name = 'ventajas_tratamiento$pfj' cols = 60 rows = 3>".$_REQUEST['ventajas_tratamiento'.$pfj]."</textarea></td>" ;
               }
     	}

          $this->salida.="</tr>";

          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >PRECAUCIONES:</td>";
          $this->salida.="</tr>";
          $this->salida.="<tr class=\"$estilo\">";
          if ($_REQUEST['consultar_just'.$pfj]==1)
          {
	          $this->salida.="<td colspan=\"5\" width=\"80%\" align='left' ><textarea readonly style = \"width:100%\" class='textarea' name = 'precauciones$pfj' cols = 60 rows = 3>".$vector_justificacion[0][precauciones]."</textarea></td>" ;
          }
          else
          {
          	if (($_REQUEST['precauciones'.$pfj])  == '')
               {
                    $this->salida.="<td colspan=\"5\" width=\"80%\" align='left' ><textarea style = \"width:100%\" class='textarea' name = 'precauciones$pfj' cols = 60 rows = 3>".$vector_justificacion[0][precauciones]."</textarea></td>" ;
               }
               else
               {
                    $this->salida.="<td colspan=\"5\" width=\"80%\" align='left' ><textarea style = \"width:100%\" class='textarea' name = 'precauciones$pfj' cols = 60 rows = 3>".$_REQUEST['precauciones'.$pfj]."</textarea></td>" ;
               }
          }

          $this->salida.="</tr>";

          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >CONTROLES PARA EVALUAR LA EFECTIVIDAD DEL MEDICAMENTO:</td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"$estilo\">";
          if ($_REQUEST['consultar_just'.$pfj]==1)
          {
	          $this->salida.="<td colspan=\"5\" width=\"80%\" align='left' ><textarea readonly style = \"width:80%\" class='textarea' name = 'controles_evaluacion_efectividad$pfj' cols = 60 rows = 3>".$vector_justificacion[0][controles_evaluacion_efectividad]."</textarea></td>" ;
          }
          else
          {
               if (($_REQUEST['controles_evaluacion_efectividad'.$pfj])  == '')
               {
                    $this->salida.="<td colspan=\"5\" width=\"80%\" align='left' ><textarea style = \"width:80%\" class='textarea' name = 'controles_evaluacion_efectividad$pfj' cols = 60 rows = 3>".$vector_justificacion[0][controles_evaluacion_efectividad]."</textarea></td>" ;
               }
               else
               {
                    $this->salida.="<td colspan=\"5\" width=\"80%\" align='left' ><textarea style = \"width:80%\" class='textarea' name = 'controles_evaluacion_efectividad$pfj' cols = 60 rows = 3>".$_REQUEST['controles_evaluacion_efectividad'.$pfj]."</textarea></td>" ;
               }
          }
          $this->salida.="</tr>";
          $this->salida.="</table>";
          $this->salida.="</td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\">";
          $this->salida.="<table>";
          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td colspan=\"1\" width=\"40%\"align=\"left\" >TIEMPO DE RESPUESTA ESPERADO</td>";
          if ($_REQUEST['consultar_just'.$pfj]==1)
          {
               $this->salida.="<td colspan=\"4\" width=\"30\" align=\"left\" ><input readonly type='text' class='input-text' size = 20 name = 'tiempo_respuesta_esperado$pfj'   value =\"".$vector_justificacion[0][tiempo_respuesta_esperado]."\"></td>" ;
          }
          else
          {
               if ($_REQUEST['tiempo_respuesta_esperado'.$pfj]!='')
               {
                    $this->salida.="<td colspan=\"4\" width=\"30\" align=\"left\" ><input type='text' class='input-text' size = 20 name = 'tiempo_respuesta_esperado$pfj'   value =\"".$_REQUEST['tiempo_respuesta_esperado'.$pfj]."\"></td>" ;
               }
               else
               {
                    $this->salida.="<td colspan=\"4\" width=\"30\" align=\"left\" ><input type='text' class='input-text' size = 20 name = 'tiempo_respuesta_esperado$pfj'   value =\"".$vector_justificacion[0][tiempo_respuesta_esperado]."\"></td>" ;
               }
          }
          $this->salida.="</tr>";
          $this->salida.="</table>";
          $this->salida.="</td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\">";
          $this->salida.="<table>";
          $this->salida.="<tr class=\"$estilo\">";
          if ($_REQUEST['consultar_just'.$pfj]==1)
          {
               if ($vector_justificacion[0][sw_riesgo_inminente] != '1')
               {
                    $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input disabled type = checkbox name= 'sw_riesgo_inminente$pfj' value = 1>&nbsp; RIESGO INMINENTE</td>";
               }
               else
               {
                    $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input disabled type = checkbox checked name= 'sw_riesgo_inminente$pfj' value = 1>&nbsp; RIESGO INMINENTE</td>";
               }
          }
          else
          {
               if (($_REQUEST['sw_riesgo_inminente'.$pfj] != '1') AND ($vector_justificacion[0][sw_riesgo_inminente] != '1'))
               {
                    $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = checkbox name= 'sw_riesgo_inminente$pfj' value = 1>&nbsp; RIESGO INMINENTE</td>";
               }
               else
               {
                    $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = checkbox checked name= 'sw_riesgo_inminente$pfj' value = 1>&nbsp; RIESGO INMINENTE</td>";
               }
          }
          if ($_REQUEST['consultar_just'.$pfj]==1)
          {
               $this->salida.="<td colspan=\"1\" width=\"60%\" align='center' ><textarea readonly style = \"width:80%\" class='textarea' name = 'riesgo_inminente$pfj' cols = 60 rows = 3>".$vector_justificacion[0][riesgo_inminente]."</textarea></td>" ;
          }
          else
          {
               if (($_REQUEST['riesgo_inminente'.$pfj])  == '')
               {
                    $this->salida.="<td colspan=\"1\" width=\"60%\" align='center' ><textarea style = \"width:80%\" class='textarea' name = 'riesgo_inminente$pfj' cols = 60 rows = 3>".$vector_justificacion[0][riesgo_inminente]."</textarea></td>" ;
               }
               else
               {
                    $this->salida.="<td colspan=\"1\" width=\"60%\" align='center' ><textarea style = \"width:80%\" class='textarea' name = 'riesgo_inminente$pfj' cols = 60 rows = 3>".$_REQUEST['riesgo_inminente'.$pfj]."</textarea></td>" ;
               }
          }
          $this->salida.="</tr>";
          $this->salida.="</table>";
          $this->salida.="</td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\">";
          $this->salida.="<table>";
          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" >SE HAN AGOTADO LAS POSIBILIDADES EXISTENTES:</td>";
          if ($_REQUEST['consultar_just'.$pfj]==1)
          {
               if ($vector_justificacion[0][sw_agotadas_posibilidades_existentes]!= '1')
               {
                    $this->salida.="<td colspan=\"1\" width=\"5%\"align=\"left\" ><input disabled type = radio name= 'sw_agotadas_posibilidades_existentes$pfj' value = '1'>&nbsp; SI</td>";
                    $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input disabled type = radio checked name= 'sw_agotadas_posibilidades_existentes$pfj' value = '0'>&nbsp; NO</td>";
               }
               else
               {
                    $this->salida.="<td colspan=\"1\" width=\"5%\"align=\"left\" ><input disabled type = radio checked name= 'sw_agotadas_posibilidades_existentes$pfj' value = '1'>&nbsp; SI</td>";
                    $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input disabled type = radio name= 'sw_agotadas_posibilidades_existentes$pfj' value = '0'>&nbsp; NO</td>";
               }
          }
          else
          {
               if (($_REQUEST['sw_agotadas_posibilidades_existentes'.$pfj] != '1') AND ($vector_justificacion[0][sw_agotadas_posibilidades_existentes]!= '1'))
               {
                    $this->salida.="<td colspan=\"1\" width=\"5%\"align=\"left\" ><input type = radio name= 'sw_agotadas_posibilidades_existentes$pfj' value = '1'>&nbsp; SI</td>";
                    $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = radio checked name= 'sw_agotadas_posibilidades_existentes$pfj' value = '0'>&nbsp; NO</td>";
               }
               else
               {
                    $this->salida.="<td colspan=\"1\" width=\"5%\"align=\"left\" ><input type = radio checked name= 'sw_agotadas_posibilidades_existentes$pfj' value = '1'>&nbsp; SI</td>";
                    $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = radio name= 'sw_agotadas_posibilidades_existentes$pfj' value = '0'>&nbsp; NO</td>";
               }
          }

          $this->salida.="</tr>";
          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" >TIENE HOMOLOGO EN EL POS:</td>";
          if ($_REQUEST['consultar_just'.$pfj]==1)
          {
               if ($vector_justificacion[0][sw_homologo_pos]!= '1')
               {
                    $this->salida.="<td colspan=\"1\" width=\"5%\"align=\"left\" ><input disabled type = radio name= 'sw_homologo_pos$pfj' value = '1'>&nbsp; SI</td>";
                    $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input disabled type = radio checked name= 'sw_homologo_pos$pfj' value = '0'>&nbsp; NO</td>";
               }
               else
               {
                    $this->salida.="<td colspan=\"1\" width=\"5%\"align=\"left\" ><input disabled type = radio checked name= 'sw_homologo_pos$pfj' value = '1'>&nbsp; SI</td>";
                    $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input disabled type = radio name= 'sw_homologo_pos$pfj' value = '0'>&nbsp; NO</td>";
               }
          }
          else
          {
               if (($_REQUEST['sw_homologo_pos'.$pfj] != '1') AND ($vector_justificacion[0][sw_homologo_pos]!= '1'))
               {
                    $this->salida.="<td colspan=\"1\" width=\"5%\"align=\"left\" ><input type = radio name= 'sw_homologo_pos$pfj' value = '1'>&nbsp; SI</td>";
                    $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = radio checked name= 'sw_homologo_pos$pfj' value = '0'>&nbsp; NO</td>";
               }
               else
               {
                    $this->salida.="<td colspan=\"1\" width=\"5%\"align=\"left\" ><input type = radio checked name= 'sw_homologo_pos$pfj' value = '1'>&nbsp; SI</td>";
                    $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = radio name= 'sw_homologo_pos$pfj' value = '0'>&nbsp; NO</td>";
               }

          }
          $this->salida.="</tr>";
          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" >ES COMERCIALIZADO EN EL PAIS:</td>";
          if ($_REQUEST['consultar_just'.$pfj]==1)
          {
               if ($vector_justificacion[0][sw_comercializacion_pais]!= '1')
               {
                    $this->salida.="<td colspan=\"1\" width=\"5%\"align=\"left\" ><input disabled type = radio name= 'sw_comercializacion_pais$pfj' value = '1'>&nbsp; SI</td>";
                    $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input disabled type = radio checked name= 'sw_comercializacion_pais$pfj' value = '0'>&nbsp; NO</td>";
               }
               else
               {
                    $this->salida.="<td colspan=\"1\" width=\"5%\"align=\"left\" ><input disabled type = radio checked name= 'sw_comercializacion_pais$pfj' value = '1'>&nbsp; SI</td>";
                    $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input disabled type = radio name= 'sw_comercializacion_pais$pfj' value = '0'>&nbsp; NO</td>";
               }
          }
          else
          {
               if (($_REQUEST['sw_comercializacion_pais'.$pfj] != '1') AND ($vector_justificacion[0][sw_comercializacion_pais]!= '1'))
               {
                    $this->salida.="<td colspan=\"1\" width=\"5%\"align=\"left\" ><input type = radio name= 'sw_comercializacion_pais$pfj' value = '1'>&nbsp; SI</td>";
                    $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = radio checked name= 'sw_comercializacion_pais$pfj' value = '0'>&nbsp; NO</td>";
               }
               else
               {
                    $this->salida.="<td colspan=\"1\" width=\"5%\"align=\"left\" ><input type = radio checked name= 'sw_comercializacion_pais$pfj' value = '1'>&nbsp; SI</td>";
                    $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = radio name= 'sw_comercializacion_pais$pfj' value = '0'>&nbsp; NO</td>";
               }
          }
          $this->salida.="</tr>";
          $this->salida.="</table>";
          $this->salida.="</td>";
          $this->salida.="</tr>";
     //FIN OK
          $this->salida.="<tr class=\"modulo_table_title\">";
          $this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >NOTA</td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >Para el trámite de esta solicitud es obligatorio el diligenciamiento completo, anexando el original de la formula médica y el resumen de la historia clinica.<br>La entrega del medicamento está sujeta
                                                                      a la aprobación del comité técnico-cientifico, de acuerdo a lo establecido en la resolución 5061 del 23 de diciembre de 1997.</td>";
          $this->salida.="</tr>";

          $this->salida.="</table><br>";

          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
          if ($_REQUEST['consultar_just'.$pfj]==1)
          {
               //lo de alex
               $reporte= new GetReports();
               $mostrar=$reporte->GetJavaReport('system','reportes','justificacion_nopos_med_html',array('codigo_producto'=>$_SESSION['MEDICAMENTOSM'.$pfj][codigo_producto], 'evolucion'=>$_SESSION['MEDICAMENTOSM'.$pfj][evolucion]),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
               $nombre_funcion=$reporte->GetJavaFunction();
               $this->salida .=$mostrar;
               $this->salida.="<tr class='$estilo' align='center'>";
               $this->salida.="<td width=\"10%\" valign=\"center\"><a href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>IMPRIMIR PDF</a></td>";
               $this->salida .= "</tr>";
               //fin de alex

               //fin de la linea.
               $this->salida .= "</form>";
               $accion3=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false, array('accion'.$pfj=>''));
               $this->salida .= "<form name=\"forma\" action=\"$accion3\" method=\"post\">";
               $this->salida.="<tr class='$estilo' align='center'>";
               $this->salida .= "<td align=\"center\"><input class=\"input-submit\" name= 'cancelar$pfj' type=\"submit\" value=\"VOLVER\"></form></td>";
               $this->salida .= "</tr>";
          }
          else
          {
               $this->salida .= "<tr>";
               $this->salida .= "<td   width=\"50%\" align=\"center\"><input class=\"input-submit\" name= 'guardar_justificacion$pfj' type=\"submit\" value=\"GUARDAR JUSTIFICACION MODIFICADA\"></td>";
               $this->salida .= "</form>";
               $accion3=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false, array('accion'.$pfj=>''));
               $this->salida .= "<form name=\"forma\" action=\"$accion3\" method=\"post\">";
               $this->salida .= "<td   width=\"50%\" align=\"center\"><input class=\"input-submit\" name= 'cancelar$pfj' type=\"submit\" value=\"CANCELAR\"></form></td>";
               $this->salida .= "</tr>";
          }
          $this->salida.="</table>";
          $this->salida .= ThemeCerrarTablaSubModulo();
	}


	//*
     function Justificacion_Medicamentos_No_Pos()
     {
          $pfj=$this->frmPrefijo;
          $this->salida= ThemeAbrirTablaSubModulo('JUSTIFICACION DEL MEDICAMENTO');
          $datos_m = $_SESSION['DATOS_M'.$pfj]['PLAN_TERAPEUTICO'];
          if(!empty($_SESSION['JUSTIFICACION'.$pfj]) AND ($_SESSION['JUSTIFICACION'.$pfj]['pare']==0))
          {
               //***********cargando los datos en la justificacion de la variable de sesion a los request
               $_REQUEST['dosis_dia'.$pfj] 							= $_SESSION['JUSTIFICACION'.$pfj]['dosis_dia'];
               $_REQUEST['duracion_tratamiento'.$pfj] 		= $_SESSION['JUSTIFICACION'.$pfj]['duracion_tratamiento'];
               $_REQUEST['descripcion_caso_clinico'.$pfj]= $_SESSION['JUSTIFICACION'.$pfj]['descripcion_caso_clinico'];
               for ($j=1;$j<3;$j++)
               {
                    $_REQUEST['medicamento_pos'.$j.$pfj] 					= $_SESSION['JUSTIFICACION'.$pfj]['medicamento_pos'.$j];
                    $_REQUEST['principio_activo_pos'.$j.$pfj] 		= $_SESSION['JUSTIFICACION'.$pfj]['principio_activo_pos'.$j];
                    $_REQUEST['dosis_dia_pos'.$j.$pfj] 						= $_SESSION['JUSTIFICACION'.$pfj]['dosis_dia_pos'.$j];
                    $_REQUEST['duracion_tratamiento_pos'.$j.$pfj]	= $_SESSION['JUSTIFICACION'.$pfj]['duracion_tratamiento_pos'.$j];
                    $_REQUEST['sw_no_mejoria'.$j.$pfj]						= $_SESSION['JUSTIFICACION'.$pfj]['sw_no_mejoria'.$j];
                    $_REQUEST['sw_reaccion_secundaria'.$j.$pfj]		= $_SESSION['JUSTIFICACION'.$pfj]['sw_reaccion_secundaria'.$j];
                    $_REQUEST['reaccion_secundaria'.$j.$pfj]			= $_SESSION['JUSTIFICACION'.$pfj]['reaccion_secundaria'.$j];
                    $_REQUEST['sw_contraindicacion'.$j.$pfj]			= $_SESSION['JUSTIFICACION'.$pfj]['sw_contraindicacion'.$j];
                    $_REQUEST['contraindicacion'.$j.$pfj]					= $_SESSION['JUSTIFICACION'.$pfj]['contraindicacion'.$j];
                    $_REQUEST['otras'.$j.$pfj]										=	$_SESSION['JUSTIFICACION'.$pfj]['otras'.$j];
               }
               $_REQUEST['justificacion_solicitud'.$pfj]						= $_SESSION['JUSTIFICACION'.$pfj]['justificacion_solicitud'];
               $_REQUEST['ventajas_medicamento'.$pfj]							= $_SESSION['JUSTIFICACION'.$pfj]['ventajas_medicamento'];
               $_REQUEST['ventajas_tratamiento'.$pfj]							= $_SESSION['JUSTIFICACION'.$pfj]['ventajas_tratamiento'];
               $_REQUEST['precauciones'.$pfj]											= $_SESSION['JUSTIFICACION'.$pfj]['precauciones'];
               $_REQUEST['controles_evaluacion_efectividad'.$pfj]	= $_SESSION['JUSTIFICACION'.$pfj]['controles_evaluacion_efectividad'];
               $_REQUEST['tiempo_respuesta_esperado'.$pfj]					= $_SESSION['JUSTIFICACION'.$pfj]['tiempo_respuesta_esperado'];
               $_REQUEST['sw_riesgo_inminente'.$pfj]								= $_SESSION['JUSTIFICACION'.$pfj]['sw_riesgo_inminente'];
               $_REQUEST['riesgo_inminente'.$pfj]									= $_SESSION['JUSTIFICACION'.$pfj]['riesgo_inminente'];
               $_REQUEST['sw_agotadas_posibilidades_existentes'.$pfj]= $_SESSION['JUSTIFICACION'.$pfj]['sw_agotadas_posibilidades_existentes'];
               $_REQUEST['sw_homologo_pos'.$pfj]										= $_SESSION['JUSTIFICACION'.$pfj]['sw_homologo_pos'];
               $_REQUEST['sw_comercializacion_pais'.$pfj]					= $_SESSION['JUSTIFICACION'.$pfj]['sw_comercializacion_pais'];

               //***********fin******************************************************************************
          }

          $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar_justificacion_no_pos'));
          $this->salida .= "<form name=\"formajus$pfj\" action=\"$accion\" method=\"post\">";

    		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
          $this->salida .= $this->SetStyle("MensajeError");
          $this->salida.="</table>";

          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
    		$this->salida.="<tr class=\"modulo_table_list_title\">";
          $this->salida.="  <td align=\"center\" colspan=\"5\">JUSTIFICACION DE MEDICAMENTOS NO POS</td>";
          $this->salida.="</tr>";

          if( $i % 2){ $estilo='modulo_list_claro';}
          	else {$estilo='modulo_list_oscuro';}

		//datos del medicamento
          $this->salida.="<tr class=\"modulo_table_title\">";
          $this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >DATOS DEL MEDICAMENTO</td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
          $this->salida.="  <td align=\"center\" width=\"5%\">CODIGO</td>";
          $this->salida.="  <td align=\"center\" width=\"20%\">PRODUCTO</td>";
          $this->salida.="  <td align=\"center\" width=\"20%\">PRINCIPIO ACTIVO</td>";
          $this->salida.="  <td align=\"center\" width=\"20%\">CONCENTRACION</td>";
          $this->salida.="  <td align=\"center\" width=\"15%\">FORMA</td>";
          $this->salida.="</tr>";


          if ($_SESSION['SPIA'.$pfj]==1)
          {
               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="<td align=\"center\" width=\"5%\">".$_SESSION['MEDICAMENTOS'.$pfj]['codigo_producto']."</td>";
               $this->salida.="<td align=\"center\" width=\"20%\" >".$_SESSION['MEDICAMENTOS'.$pfj]['producto']."</td>";
               $this->salida.="<td align=\"center\" width=\"20%\" >".$_SESSION['MEDICAMENTOS'.$pfj]['principio_activo']."</td>";
               $this->salida.="<td align=\"center\" width=\"20%\" >".$_SESSION['MEDICAMENTOS'.$pfj]['concentracion_forma_farmacologica']." ".$_SESSION['MEDICAMENTOS'.$pfj]['unidad_medida_medicamento_id']."</td>";
               $this->salida.="<td align=\"center\" width=\"15%\" >".$_SESSION['MEDICAMENTOS'.$pfj]['forma']."</td>";
               $this->salida.="</tr>";
          }
          else
          {
               $arreglo=explode("|/",$datos_m);
               $this->salida.="  <input type='hidden' name = 'item$pfj'  value = '".$arreglo[0]."'>";
               $this->salida.="  <input type='hidden' name = 'codigo_producto$pfj'  value = '".$arreglo[1]."'>";
               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="<td align=\"center\" width=\"5%\">".$arreglo[1]."</td>";
               $this->salida.="<td align=\"center\" width=\"20%\" >".$arreglo[2]."</td>";
               $this->salida.="<td align=\"center\" width=\"20%\" >".$arreglo[3]."</td>";
               $this->salida.="<td align=\"center\" width=\"20%\" >".$arreglo[4]." ".$arreglo[5]."</td>";
               $this->salida.="<td align=\"center\" width=\"15%\" >".$arreglo[6]."</td>";
               $this->salida.="</tr>";
          }
          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td colspan = 5>";
          $this->salida.="<table>";

          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="  <td align=\"left\" width=\"20%\">DOSIS:</td>";
          $e=($_SESSION['MEDICAMENTOS'.$pfj]['dosis'])/(floor($_SESSION['MEDICAMENTOS'.$pfj]['dosis']));
          if($e==1)
          {
               $this->salida.="  <td align=\"left\" width=\"60%\">".floor($_SESSION['MEDICAMENTOS'.$pfj]['dosis'])."  ".$_SESSION['MEDICAMENTOS'.$pfj]['unidad_dosificacion']."</td>";
          }
          else
          {
               $this->salida.="  <td align=\"left\" width=\"60%\">".$_SESSION['MEDICAMENTOS'.$pfj]['dosis']."  ".$_SESSION['MEDICAMENTOS'.$pfj]['unidad_dosificacion']."</td>";
          }
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="  <td align=\"left\" width=\"20%\">CANTIDAD:</td>";
          $e=($_SESSION['MEDICAMENTOS'.$pfj]['cantidad'])/(floor($_SESSION['MEDICAMENTOS'.$pfj]['cantidad']));
		//ojo este contenido_unidad_venta aca no esta llegando
          if ($vector1[$i][contenido_unidad_venta])
          {
               if($e==1)
               {
                    $this->salida.="  <td align=\"left\" width=\"60%\">".floor($_SESSION['MEDICAMENTOS'.$pfj]['cantidad'])." ".$vector1[$i][descripcion]." por ".$vector1[$i][contenido_unidad_venta]."</td>";
               }
               else
               {
                    $this->salida.="  <td align=\"left\" width=\"60%\">".$_SESSION['MEDICAMENTOS'.$pfj]['cantidad']." ".$vector1[$i][descripcion]." por ".$vector1[$i][contenido_unidad_venta]."</td>";
               }
          }
          else
          {
               if($e==1)
               {
                    $this->salida.="  <td align=\"left\" width=\"60%\">".floor($_SESSION['MEDICAMENTOS'.$pfj]['cantidad'])." ".$vector1[$i][descripcion]."</td>";
               }
               else
               {
                    $this->salida.="  <td align=\"left\" width=\"60%\">".$_SESSION['MEDICAMENTOS'.$pfj]['cantidad']." ".$vector1[$i][descripcion]."</td>";
               }
          }
          $this->salida.="</tr>";
          $this->salida.="</td>";
          $this->salida.="</table>";

          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td colspan = 5>";
          $this->salida.="<table>";
    		$this->salida.="<tr class=\"$estilo\">";
          $this->salida.="  <td align=\"left\" width=\"20%\">OBSERVACION:</td>";
          $this->salida.="  <td align=\"left\" width=\"60%\">".$_SESSION['MEDICAMENTOS'.$pfj]['observacion']."</td>";
          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="</table>";
          $this->salida.="</td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\">";
          $this->salida.="<table>";
          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td colspan=\"1\" class=".$this->SetStyle("dosis_dia")." width=\"20%\"align=\"left\" >DOSIS POR DIA</td>";
          $this->salida.="<td colspan=\"1\" width=\"60\" align=\"left\" ><input type='text' class='input-text' size = 40 name = 'dosis_dia$pfj'   value =\"".$_REQUEST['dosis_dia'.$pfj]."\"></td>" ;
          $this->salida.="</tr>";
          
          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td colspan=\"1\" class=".$this->SetStyle("duracion_tratamiento")." width=\"20%\"align=\"left\" >DIAS DE TRATAMIENTO</td>";
          $this->salida.="<td colspan=\"1\" width=\"60\" align=\"left\" ><input type='text' class='input-text' size = 60 name = 'duracion_tratamiento$pfj'   value =\"".$_REQUEST['duracion_tratamiento'.$pfj]."\"></td>" ;
          $this->salida.="</tr>";
          $this->salida.="</table>";
          $this->salida.="</td>";
          $this->salida.="</tr>";

//Diagnosticos
          $this->salida.="<script>";
          $this->salida.="function diagnostico(url){\n";
          $this->salida.="document.formajus$pfj.action=url;\n";
          $this->salida.="document.formajus$pfj.submit();}";
          $this->salida.="</script>";
          $flag = $this->SetStyle("diagnostico_id");
          if ($flag == 'label_error')
          {
	          $this->salida.="<tr class=".$this->SetStyle("diagnostico_id").">";
          }
          else
          {
          	$this->salida.="<tr class=\"modulo_table_title\">";
          }
          $this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >DIAGNOSTICO</td>";
          $this->salida.="</tr>";
          if ($_SESSION['DIAGNOSTICOS'.$pfj])
          {
               foreach ($_SESSION['DIAGNOSTICOS'.$pfj] as $k=>$v)
               {
                    $accion5=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'eliminardiagnostico', 'diagnostico'.$pfj=>$k));
                    $this->salida.="<tr class=\"modulo_list_claro\">";
                    $this->salida.="  <td class=\"$estilo\" align=\"center\" width=\"5%\"><a href='javascript:diagnostico(\"$accion5\")'><img title=\"Eliminar\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
                    $this->salida.="<td colspan = 4>".$k." - ".$v."</td>";
                    $this->salida.="  <input type='hidden' name = id$k$pfj' value = ".$k.">";
                    $this->salida.="</tr>";
               }
          }
          $this->salida.="<tr class=\"modulo_list_oscuro\">";
          $accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'agregar_diagnosticos'));
          $this->salida.="  <td colspan = 5 align=\"center\" width=\"63%\"><a href='javascript:diagnostico(\"$accion1\")'><font color='#190CA2'><b><u>AGREGAR MAS DIAGNOSTICOS</u></b></font></a></td>";
          $this->salida.="</tr>";

//descripcion del caso clinico
          $this->salida.="<tr class=\"modulo_table_title\">";
          $this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >DESCRIPCION DEL CASO CLINICO</td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"$estilo\">";
          if (($_REQUEST['descripcion_caso_clinico'.$pfj])  == '')
          {
               $this->salida.="<td colspan = 5 width=\"80%\" align='center' ><textarea style = \"width:80%\" class='textarea' name = 'descripcion_caso_clinico$pfj' cols = 60 rows = 3></textarea></td>" ;
          }
          else
          {
               $this->salida.="<td colspan = 5 width=\"80%\" align='center' ><textarea style = \"width:80%\" class='textarea' name = 'descripcion_caso_clinico$pfj' cols = 60 rows = 3>".$_REQUEST['descripcion_caso_clinico'.$pfj]."</textarea></td>" ;
          }
          $this->salida.="</tr>";

//alternativas pos previamente utilizadas
          $this->salida.="<tr class=\"modulo_table_title\">";
          $this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >ALTERNATIVAS POS PREVIAMENTE UTILIZADAS</td>";
          $this->salida.="</tr>";
          for ($j=1;$j<3;$j++)
          {
               if ($j==1)
               {
                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >PRIMERA POSIBILIDAD TERAPEUTICA POS</td>";
                    $this->salida.="</tr>";
               }
               else
               {
                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >SEGUNDA POSIBILIDAD TERAPEUTICA POS</td>";
                    $this->salida.="</tr>";
               }

               $this->salida.="<tr class=\"$estilo\">";
               $this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\">";
               $this->salida.="<table>";
               $this->salida.="<tr class=\"$estilo\">";
               $this->salida.="<td colspan=\"1\" width=\"15%\"align=\"left\" >MEDICAMENTO</td>";
               $this->salida.="<td colspan=\"1\" width=\"28\" align=\"left\" ><input type='text' class='input-text' size = 30 name = 'medicamento_pos$j$pfj'   value =\"".$_REQUEST['medicamento_pos'.$j.$pfj]."\"></td>" ;
               $this->salida.="<td colspan=\"1\" width=\"18%\"align=\"left\" >PRINCIPIO ACTIVO</td>";
               $this->salida.="<td colspan=\"1\" width=\"20\" align=\"left\" ><input type='text' class='input-text' size = 30 name = 'principio_activo_pos$j$pfj'   value =\"".$_REQUEST['principio_activo_pos'.$j.$pfj]."\"></td>" ;
               $this->salida.="</tr>";
               $this->salida.="</table>";
               $this->salida.="</td>";
               $this->salida.="</tr>";
               
               $this->salida.="<tr class=\"$estilo\">";
               $this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\">";
               $this->salida.="<table>";
               $this->salida.="<tr class=\"$estilo\">";
               $this->salida.="<td colspan=\"1\" width=\"15%\"align=\"left\" >DOSIS POR DIA</td>";
               $this->salida.="<td colspan=\"1\" width=\"13\" align=\"left\" ><input type='text' class='input-text' size = 20 name = 'dosis_dia_pos$j$pfj'   value =\"".$_REQUEST['dosis_dia_pos'.$j.$pfj]."\"></td>" ;
               $this->salida.="<td colspan=\"1\" width=\"25%\"align=\"left\" >DURACION DEL TRATAMIENTO</td>";
               $this->salida.="<td colspan=\"1\" width=\"13\" align=\"left\" ><input type='text' class='input-text' size = 20 name = 'duracion_tratamiento_pos$j$pfj'   value =\"".$_REQUEST['duracion_tratamiento_pos'.$j.$pfj]."\"></td>" ;
               if (($_REQUEST['sw_no_mejoria'.$j.$pfj] != '1') AND ($datos[0][tipo_opcion_posologia_id]!= '1'))
               {
                    $this->salida.="<td width=\"14%\"align=\"left\" ><input type = checkbox name= 'sw_no_mejoria$j$pfj' value = 1>NO MEJORIA</td>";
               }
               else
               {
                    $this->salida.="<td width=\"14%\"align=\"left\" ><input type = checkbox checked name= 'sw_no_mejoria$j$pfj' value = 1>NO MEJORIA</td>";
               }
               $this->salida.="</tr>";
               $this->salida.="</table>";
               $this->salida.="</td>";
               $this->salida.="</tr>";

               $this->salida.="<tr class=\"$estilo\">";
               $this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\">";
               $this->salida.="<table>";
               $this->salida.="<tr class=\"$estilo\">";
               if (($_REQUEST['sw_reaccion_secundaria'.$j.$pfj] != '1') AND ($datos[0][	tipo_opcion_posologia_id]!= '1'))
               {
                    $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = checkbox name= 'sw_reaccion_secundaria$j$pfj' value = 1>&nbsp; REACCION SECUNDARIA</td>";
               }
               else
               {
                    $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = checkbox checked name= 'sw_reaccion_secundaria$j$pfj' value = 1>&nbsp; REACCION SECUNDARIA</td>";
               }
               if (($_REQUEST['reaccion_secundaria'.$j.$pfj])  == '')
               {
                    $this->salida.="<td colspan=\"1\" width=\"50%\" align='center' ><textarea style = \"width:80%\" class='textarea' name = 'reaccion_secundaria$j$pfj' cols = 60 rows = 3></textarea></td>" ;
               }
               else
               {
                    $this->salida.="<td colspan=\"1\" width=\"50%\" align='center' ><textarea style = \"width:80%\" class='textarea' name = 'reaccion_secundaria$j$pfj' cols = 60 rows = 3>".$_REQUEST['reaccion_secundaria'.$j.$pfj]."</textarea></td>" ;
               }
               $this->salida.="</tr>";

               $this->salida.="<tr class=\"$estilo\">";
               if (($_REQUEST['sw_contraindicacion'.$j.$pfj] != '1') AND ($datos[0][	tipo_opcion_posologia_id]!= '1'))
               {
                    $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = checkbox name= 'sw_contraindicacion$j$pfj' value = 1>&nbsp; CONTRAINDICACION EXPRESA</td>";
               }
               else
               {
                    $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = checkbox checked name= 'sw_contraindicacion$j$pfj' value = 1>&nbsp; CONTRAINDICACION EXPRESA</td>";
               }

               if (($_REQUEST['contraindicacion'.$j.$pfj])  == '')
               {
                    $this->salida.="<td colspan=\"1\" width=\"50%\" align='center' ><textarea style = \"width:80%\" class='textarea' name = 'contraindicacion$j$pfj' cols = 60 rows = 3></textarea></td>" ;
               }
               else
               {
                    $this->salida.="<td colspan=\"1\" width=\"50%\" align='center' ><textarea style = \"width:80%\" class='textarea' name = 'contraindicacion$j$pfj' cols = 60 rows = 3>".$_REQUEST['contraindicacion'.$j.$pfj]."</textarea></td>" ;
               }
               $this->salida.="</tr>";
               $this->salida.="</table>";
               $this->salida.="</td>";
               $this->salida.="</tr>";

               $this->salida.="<tr class=\"$estilo\">";
               $this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\">";
               $this->salida.="<table>";

               $this->salida.="<tr class=\"$estilo\">";
               $this->salida.="<td colspan=\"2\" width=\"19%\"align=\"center\" >OTRAS</td>";

               if (($_REQUEST['otras'.$j.$pfj])  == '')
               {
                    $this->salida.="<td colspan = 3 width=\"61%\" align='center' ><textarea style = \"width:80%\" class='textarea' name = 'otras$j$pfj' cols = 60 rows = 3></textarea></td>" ;
               }
               else
               {
                    $this->salida.="<td colspan = 3 width=\"61%\" align='center' ><textarea style = \"width:80%\" class='textarea' name = 'otras$j$pfj' cols = 60 rows = 3>".$_REQUEST['otras'.$j.$pfj]."</textarea></td>" ;
               }
               $this->salida.="</tr>";
               $this->salida.="</table>";
               $this->salida.="</td>";
               $this->salida.="</tr>";
          }	
     //fin de alternativas pos previamente utilizadas

     //criterios que justifican la solicitud

          $this->salida.="<tr class=\"modulo_table_title\">";
          $this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >CRITERIOS DE JUSTIFICACION</td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td colspan=\"5\" width=\"80%\"align=\"center\">";

          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";

          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >JUSTIFICACION DE LA SOLICITUD:</td>";
          $this->salida.="</tr>";
          $this->salida.="<tr class=\"$estilo\">";
          if (($_REQUEST['justificacion_solicitud'.$pfj])  == '')
          {
               $this->salida.="<td colspan=\"5\" width=\"80%\" align=\"center\" ><textarea style = \"width:100%\" class='textarea' name = 'justificacion_solicitud$pfj' cols = 60 rows = 3></textarea></td>" ;
          }
          else
          {
               $this->salida.="<td colspan=\"5\" width=\"80%\" align=\"center\" ><textarea style = \"width:100%\" class='textarea' name = 'justificacion_solicitud$pfj' cols = 60 rows = 3>".$_REQUEST['justificacion_solicitud'.$pfj]."</textarea></td>" ;
          }

          $this->salida.="</tr>";


          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >VENTAJAS DE ESTE MEDICAMENTO:</td>";
          $this->salida.="</tr>";
          $this->salida.="<tr class=\"$estilo\">";
          if (($_REQUEST['ventajas_medicamento'.$pfj])  == '')
          {
               $this->salida.="<td colspan=\"5\" width=\"80%\" align=\"center\" ><textarea style = \"width:100%\" class='textarea' name = 'ventajas_medicamento$pfj' cols = 60 rows = 3></textarea></td>" ;
          }
          else
          {
               $this->salida.="<td colspan=\"5\" width=\"80%\" align=\"center\" ><textarea style = \"width:100%\" class='textarea' name = 'ventajas_medicamento$pfj' cols = 60 rows = 3>".$_REQUEST['ventajas_medicamento'.$pfj]."</textarea></td>" ;
          }

          $this->salida.="</tr>";

          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >VENTAJAS DEL TRATAMIENTO:</td>";
          $this->salida.="</tr>";
          $this->salida.="<tr class=\"$estilo\">";
          if (($_REQUEST['ventajas_tratamiento'.$pfj])  == '')
          {
               $this->salida.="<td colspan=\"5\" width=\"80%\" align=\"center\" ><textarea style = \"width:100%\" class='textarea' name = 'ventajas_tratamiento$pfj' cols = 60 rows = 3></textarea></td>" ;
          }
          else
          {
               $this->salida.="<td colspan=\"5\" width=\"80%\" align=\"center\" ><textarea style = \"width:100%\" class='textarea' name = 'ventajas_tratamiento$pfj' cols = 60 rows = 3>".$_REQUEST['ventajas_tratamiento'.$pfj]."</textarea></td>" ;
          }

          $this->salida.="</tr>";

          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >PRECAUCIONES:</td>";
          $this->salida.="</tr>";
          $this->salida.="<tr class=\"$estilo\">";
          if (($_REQUEST['precauciones'.$pfj])  == '')
          {
               $this->salida.="<td colspan=\"5\" width=\"80%\" align=\"center\" ><textarea style = \"width:100%\" class='textarea' name = 'precauciones$pfj' cols = 60 rows = 3></textarea></td>" ;
          }
          else
          {
               $this->salida.="<td colspan=\"5\" width=\"80%\" align=\"center\" ><textarea style = \"width:100%\" class='textarea' name = 'precauciones$pfj' cols = 60 rows = 3>".$_REQUEST['precauciones'.$pfj]."</textarea></td>" ;
          }

          $this->salida.="</tr>";

          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >CONTROLES PARA EVALUAR LA EFECTIVIDAD DEL MEDICAMENTO:</td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"$estilo\">";
          if (($_REQUEST['controles_evaluacion_efectividad'.$pfj])  == '')
          {
               $this->salida.="<td colspan=\"5\" width=\"80%\" align=\"center\" ><textarea style = \"width:100%\" class='textarea' name = 'controles_evaluacion_efectividad$pfj' cols = 60 rows = 3></textarea></td>" ;
          }
          else
          {
               $this->salida.="<td colspan=\"5\" width=\"80%\" align=\"center\" ><textarea style = \"width:100%\" class='textarea' name = 'controles_evaluacion_efectividad$pfj' cols = 60 rows = 3>".$_REQUEST['controles_evaluacion_efectividad'.$pfj]."</textarea></td>" ;
          }

          $this->salida.="</tr>";
          $this->salida.="</table>";
          $this->salida.="</td>";
          $this->salida.="</tr>";


          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\">";
          $this->salida.="<table>";
          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td colspan=\"1\" width=\"40%\"align=\"left\" >TIEMPO DE RESPUESTA ESPERADO</td>";
          $this->salida.="<td colspan=\"4\" width=\"30\" align=\"left\" ><input type='text' class='input-text' size = 20 name = 'tiempo_respuesta_esperado$pfj'   value =\"".$_REQUEST['tiempo_respuesta_esperado'.$pfj]."\"></td>" ;
          $this->salida.="</tr>";
          $this->salida.="</table>";
          $this->salida.="</td>";
          $this->salida.="</tr>";


          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\">";
          $this->salida.="<table>";
          $this->salida.="<tr class=\"$estilo\">";
          if (($_REQUEST['sw_riesgo_inminente'.$pfj] != '1') AND ($datos[0][	tipo_opcion_posologia_id	]!= '1'))
          {
               $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = checkbox name= 'sw_riesgo_inminente$pfj' value = 1>&nbsp; RIESGO INMINENTE</td>";
          }
          else
          {
               $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = checkbox checked name= 'sw_riesgo_inminente$pfj' value = 1>&nbsp; RIESGO INMINENTE</td>";
          }
          if (($_REQUEST['riesgo_inminente'.$pfj])  == '')
          {
               $this->salida.="<td colspan=\"1\" width=\"60%\" align='center' ><textarea style = \"width:80%\" class='textarea' name = 'riesgo_inminente$pfj' cols = 60 rows = 3></textarea></td>" ;
          }
          else
          {
               $this->salida.="<td colspan=\"1\" width=\"60%\" align='center' ><textarea style = \"width:80%\" class='textarea' name = 'riesgo_inminente$pfj' cols = 60 rows = 3>".$_REQUEST['riesgo_inminente'.$pfj]."</textarea></td>" ;
          }
          $this->salida.="</tr>";
          $this->salida.="</table>";
          $this->salida.="</td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\">";
          $this->salida.="<table>";
          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" >SE HAN AGOTADO LAS POSIBILIDADES EXISTENTES:</td>";
          if (($_REQUEST['sw_agotadas_posibilidades_existentes'.$pfj] != '1') AND ($datos[0][	tipo_opcion_posologia_id	]!= '1'))
          {
               $this->salida.="<td colspan=\"1\" width=\"5%\"align=\"left\" ><input type = radio name= 'sw_agotadas_posibilidades_existentes$pfj' value = '1'>&nbsp; SI</td>";
               $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = radio checked name= 'sw_agotadas_posibilidades_existentes$pfj' value = '0'>&nbsp; NO</td>";
          }
          else
          {
               $this->salida.="<td colspan=\"1\" width=\"5%\"align=\"left\" ><input type = radio checked name= 'sw_agotadas_posibilidades_existentes$pfj' value = '1'>&nbsp; SI</td>";
               $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = radio name= 'sw_agotadas_posibilidades_existentes$pfj' value = '0'>&nbsp; NO</td>";
          }
          $this->salida.="</tr>";
          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" >TIENE HOMOLOGO EN EL POS:</td>";
          if (($_REQUEST['sw_homologo_pos'.$pfj] != '1') AND ($datos[0][	tipo_opcion_posologia_id	]!= '1'))
          {
               $this->salida.="<td colspan=\"1\" width=\"5%\"align=\"left\" ><input type = radio name= 'sw_homologo_pos$pfj' value = '1'>&nbsp; SI</td>";
               $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = radio checked name= 'sw_homologo_pos$pfj' value = '0'>&nbsp; NO</td>";
          }
          else
          {
               $this->salida.="<td colspan=\"1\" width=\"5%\"align=\"left\" ><input type = radio checked name= 'sw_homologo_pos$pfj' value = '1'>&nbsp; SI</td>";
               $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = radio name= 'sw_homologo_pos$pfj' value = '0'>&nbsp; NO</td>";
          }
          $this->salida.="</tr>";
          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" >ES COMERCIALIZADO EN EL PAIS:</td>";
          if (($_REQUEST['sw_comercializacion_pais'.$pfj] != '1') AND ($datos[0][	tipo_opcion_posologia_id	]!= '1'))
          {
               $this->salida.="<td colspan=\"1\" width=\"5%\"align=\"left\" ><input type = radio name= 'sw_comercializacion_pais$pfj' value = '1'>&nbsp; SI</td>";
               $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = radio checked name= 'sw_comercializacion_pais$pfj' value = '0'>&nbsp; NO</td>";
          }
          else
          {
               $this->salida.="<td colspan=\"1\" width=\"5%\"align=\"left\" ><input type = radio checked name= 'sw_comercializacion_pais$pfj' value = '1'>&nbsp; SI</td>";
               $this->salida.="<td colspan=\"1\" width=\"20%\"align=\"left\" ><input type = radio name= 'sw_comercializacion_pais$pfj' value = '0'>&nbsp; NO</td>";
          }
          $this->salida.="</tr>";
          $this->salida.="</table>";
          $this->salida.="</td>";
          $this->salida.="</tr>";
     //FIN OK

          $this->salida.="<tr class=\"modulo_table_title\">";
          $this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >NOTA</td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td colspan=\"5\" width=\"80%\"align=\"left\" >Para el trámite de esta solicitud es obligatorio el diligenciamiento completo, anexando el original de la formula médica y el resumen de la historia clinica.<br>La entrega del medicamento está sujeta
                                                                      a la aprobación del comité técnico-cientifico, de acuerdo a lo establecido en la resolución 5061 del 23 de diciembre de 1997.</td>";
          $this->salida.="</tr>";

          $this->salida.="</table><br>";

          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"0\"><tr>";
          $this->salida .= "<td   align=\"center\"><input class=\"input-submit\" name= 'guardar_justificacion$pfj' type=\"submit\" value=\"GUARDAR MEDICAMENTO JUSTIFICADO\"></td>";

          $this->salida .= "</form>";
          if ($_SESSION['SPIA'.$pfj]!=1)
          {
               $accion3=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false, array('accion'.$pfj=>'volver'));
               $this->salida .= "<form name=\"forma\" action=\"$accion3\" method=\"post\">";
               $this->salida .= "<td   align=\"center\"><input class=\"input-submit\" name= 'volver$pfj' type=\"submit\" value=\"VOLVER A LA SOLICITUD DEL MEDICAMENTO\"></form></td>";
          }
          $accion3=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false, array('accion'.$pfj=>''));
          $this->salida .= "<form name=\"forma\" action=\"$accion3\" method=\"post\">";
          $this->salida .= "<td   align=\"center\"><input class=\"input-submit\" name= 'cancelar$pfj' type=\"submit\" value=\"CANCELAR\"></form></td>";
          $this->salida.="</tr></table>";
          $this->salida .= ThemeCerrarTablaSubModulo();
     }


     //clzc - si - *
     function frmForma_Modificar_Solicitud_Medicamento($codigo_producto)
     {
		$pfj=$this->frmPrefijo;
		$datos = $this->ConsultaGeneralModificacionMedicamento($codigo_producto);
          $this->salida= ThemeAbrirTablaSubModulo('MODIFICACION DE LA SOLICITUD DE MEDICAMENTOS');
          $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'modificar_datos','codigo_producto'.$pfj=>$codigo_producto, 'opcion_posol'.$pfj=>$datos[0][tipo_opcion_posologia_id], 'item'.$pfj=>$datos[0][item], 'producto'.$pfj=>$datos[0][producto], 'principio_activo'.$pfj=>$datos[0][principio_activo], 'concentracion_forma_farmacologica'.$pfj=>$datos[0][concentracion_forma_farmacologica], 'unidad_medida_medicamento_id'.$pfj=>$datos[0][unidad_medida_medicamento_id], 'forma'.$pfj=>$datos[0][forma]));
          $this->salida .= "<form name=\"forma_med1$pfj\" action=\"$accion\" method=\"post\">";
          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
          $this->salida .= $this->SetStyle("MensajeError");
          $this->salida.="</table>";
//...............modificacion de la captura de medicamentos
          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
          $this->salida.="<tr class=\"modulo_table_title\">";
          $this->salida.="  <td align=\"center\" colspan=\"6\">MODIFICACION DE LA FORMULACION DEL MEDICAMENTO</td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"modulo_table_title\">";

          $this->salida.="  <td align=\"center\" width=\"5%\"></td>";
          $this->salida.="  <td align=\"center\" width=\"5%\">CODIGO</td>";
          $this->salida.="  <td align=\"center\" width=\"23%\">PRODUCTO</td>";
          $this->salida.="  <td align=\"center\" width=\"23%\">PRINCIPIO ACTIVO</td>";
          $this->salida.="  <td align=\"center\" width=\"23%\">CONCENTRACION</td>";
          $this->salida.="  <td align=\"center\" width=\"15%\">FORMA</td>";
          //$this->salida.="  <td width=\"5%\">EXISTENCIA</td>";
          $this->salida.="</tr>";

          if( $i % 2){ $estilo='modulo_list_claro';}
          else {$estilo='modulo_list_oscuro';}

          $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";

          $this->salida.="<td align=\"center\" width=\"5%\">".$datos[0][item]."</td>";
          $this->salida.="<td align=\"center\" width=\"5%\">".$datos[0][codigo_producto]."</td>";
          $this->salida.="<td align=\"center\" width=\"23%\" >".$datos[0][producto]."</td>";
          $this->salida.="<td align=\"center\" width=\"23%\" >".$datos[0][principio_activo]."</td>";
          $this->salida.="<td align=\"center\" width=\"15%\" >".$datos[0][concentracion_forma_farmacologica]." ".$datos[0][unidad_medida_medicamento_id]."</td>";
          $this->salida.="<td align=\"center\" width=\"15%\" >".$datos[0][forma]."</td>";

          $this->salida.="</tr>";
          $this->salida.="</table>";
          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";

//via de administracion
          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td class=".$this->SetStyle("via_administracion")." width=\"20%\"align=\"left\" >VIA DE ADMINISTRACION</td>";
          $via_admon = $this->tipo_via_administracion($datos[0][codigo_producto]);

          if ((sizeof($via_admon)>1))
          {
               $this->salida.="<td width=\"60%\" align = left >";
               if	(empty($datos[0][unidad_dosificacion_forma]))
               {
                    $EventoOnclick="Onchange='UnidadPorVia(this)'";
               }
               else
               {
                    $EventoOnclick="";
               }

               $this->salida.="\n\n<select name = 'via_administracion$pfj'  class =\"select\" $EventoOnclick>";
               $this->salida.="<option value = '-1' selected>-Seleccione-</option>";

               $javita.="<script>\n";
               $javita.="function UnidadPorVia(forma) {\n";
               $javita.="if (forma.value=='-1') {\n";
               $javita.="  document.forma_med1$pfj.unidad_dosis$pfj.length=0;\n";
               $javita.="}\n\n";
               for($i=0;$i<sizeof($via_admon);$i++)
               {
                    if ($datos[0][via_administracion_id]  != $via_admon[$i][via_administracion_id])
                    {
                         $this->salida.="<option value = ".$via_admon[$i][via_administracion_id].">".$via_admon[$i][nombre]."</option>";
                    }
	               else
                    {
                         $this->salida.="<option value = ".$via_admon[$i][via_administracion_id]." selected >".$via_admon[$i][nombre]."</option>";
                    }

                    //generar java para el combo de unidades de dosificacion
                    if	(empty($datos[0][unidad_dosificacion_forma]))
                    {
                              $javita.="if (forma.value=='".$via_admon[$i][via_administracion_id]."') {\n";

                              $unidadesViaAdministracion = $this->GetunidadesViaAdministracion($via_admon[$i][via_administracion_id]);

                              $javita.="document.forma_med1$pfj.unidad_dosis$pfj.length=".count($unidadesViaAdministracion)."\n";

                              for($cont=0;$cont<count($unidadesViaAdministracion);$cont++){
                                        $javita.="document.forma_med1$pfj.unidad_dosis$pfj.options[".$cont."]= new Option('".$unidadesViaAdministracion[$cont][unidad_dosificacion]."','".$unidadesViaAdministracion[$cont][unidad_dosificacion]."');\n";
                                                  }
                              $javita.="}\n\n";
                    }
                    //fin javita
               }
               $javita.="}\n\n";
               $javita.="</script>\n";
               $this->salida.="</select>\n\n";
               $this->salida.="</td>";
          }
          else
          {
               if ((sizeof($via_admon)==1))
               {
                         $this->salida.="<td width=\"60%\" align = left >";
                         $this->salida.="\n\n<select name = 'via_administracion$pfj'  class =\"select\">";
                         $this->salida.="<option value = ".$via_admon[0][via_administracion_id]." selected >".$via_admon[0][nombre]."</option>";
                         $this->salida.="</select>\n\n";
                         $this->salida.="</td>";
               }
               else
               {
                         $this->salida.="<td width=\"60%\" align = left >&nbsp;</td>";
               }
          }
          $this->salida.="</tr>";
//-----------------
//Generar Combo de unidades de dosificacion
          $ComboUnidadDosis ="<select size = 1 name = 'unidad_dosis$pfj'  class =\"select\">";
          if	(!empty($datos[0][unidad_dosificacion_forma]))
          {
                         $ComboUnidadDosis.="<option value = '".$datos[0][unidad_dosificacion_forma]."' selected >".$datos[0][unidad_dosificacion_forma]."</option>";
          }
          else
          {
               if ((sizeof($via_admon)==1))
               {
                    $unidadesViaAdministracion = $this->GetunidadesViaAdministracion($via_admon[0][via_administracion_id]);
                    $ComboUnidadDosis.="<option value = '-1' selected >--Seleccione--</option>";
                    for($i=0;$i<sizeof($unidadesViaAdministracion);$i++)
                    {
					//aqui agreggue este if para que se seleccione la unidad guardadad en la bd
                         if($datos[0][unidad_dosificacion]==$unidadesViaAdministracion[$i][unidad_dosificacion])
                         {
                              $ComboUnidadDosis.="<option selected value = '".$unidadesViaAdministracion[$i][unidad_dosificacion]."'>".$unidadesViaAdministracion[$i][unidad_dosificacion]."</option>";
                         }
                         else
                         {
                              $ComboUnidadDosis.="<option value = '".$unidadesViaAdministracion[$i][unidad_dosificacion]."'>".$unidadesViaAdministracion[$i][unidad_dosificacion]."</option>";
                         }
					//fin del if y sigue comentado lo que estaba antes de que se creara el if
                         //$ComboUnidadDosis.="<option value = '".$unidadesViaAdministracion[$i][unidad_dosificacion]."'>".$unidadesViaAdministracion[$i][unidad_dosificacion]."</option>";
                    }
               }
               if (empty($via_admon))
               {
                    $unidadesViaAdministracion = $this->Unidades_Dosificacion();
                    $ComboUnidadDosis.="<option value = '-1' selected >--Seleccione--</option>";
                    for($i=0;$i<sizeof($unidadesViaAdministracion);$i++)
                    {
                         //aqui agreggue este if para que se seleccione la unidad guardadad en la bd
                         if($datos[0][unidad_dosificacion]==$unidadesViaAdministracion[$i][unidad_dosificacion])
                         {
                              $ComboUnidadDosis.="<option selected value = '".$unidadesViaAdministracion[$i][unidad_dosificacion]."'>".$unidadesViaAdministracion[$i][unidad_dosificacion]."</option>";
                         }
                         else
                         {
                              $ComboUnidadDosis.="<option value = '".$unidadesViaAdministracion[$i][unidad_dosificacion]."'>".$unidadesViaAdministracion[$i][unidad_dosificacion]."</option>";
                         }
                         //fin del if

                    }
               }
          }
          $ComboUnidadDosis.="</select>";
//--------------

//posologia neonatos
/*
				$FechaInicio = $this->datosPaciente[fecha_nacimiento];
				$FechaFin = date("Y-m-d");
				$edad_paciente = CalcularEdad($FechaInicio,$FechaFin);
				if ( $edad_paciente[anos] < ModuloGetVar('','','max_edad_pediatrica'))
					{
						$peso_pac = $this->Peso_Paciente();
						$this->salida.="<tr class=\"$estilo\">";
						$this->salida.="<td width=\"20%\"align=\"left\" >POSOLOGIA NEONATOS</td>";
						$this->salida.="<td width=\"60%\" align = left >";
						$this->salida.="<table>";
						$this->salida.="<tr class=\"$estilo\">";
						$this->salida.="<td  class=".$this->SetStyle("peso")." width=\"20%\" align = left >PESO</td>";
						$this->salida.="<td colspan = 2 width=\"40%\" align='left' ><input type='text' class='input-text' size = 10 name = 'peso$pfj'   value = \"".$peso_pac[peso]."\">  Kg</td>" ;
						$this->salida.="</tr>";
						$this->salida.="<tr class=\"$estilo\">";
						$this->salida.="<td  width=\"20%\" align=\"left\" >DOSIS ORDENADA</td>";
						$this->salida.="<td width=\"15%\" align=\"left\" ><input type='text' class='input-text' size = 10 name = 'dosis_ordenada$pfj'   value =\"".$_REQUEST['dosis_ordenada'.$pfj]."\">  mg/Kg por: </td>" ;
						$this->salida.="<td width=\"25%\" align=\"left\" >";
						$this->salida.="<select size = 1 name = 'criterio_dosis$pfj'  class =\"select\">";
						$this->salida.="<option value = 'dosis' selected>Dosis</option>";
						if (($_REQUEST['criterio_dosis'.$pfj])  == 'Dia')
							{
								$this->salida.="<option value = '002' selected>Dia</option>";
							}
						else
							{
								$this->salida.="<option value = '002' >Dia</option>";
							}
						$this->salida.="</select>";
						$this->salida.="</td>";
						$this->salida.="</tr>";
						$this->salida.="<tr class=\"$estilo\">";
						$this->salida .= "<td width=\"20%\"  align=\"left\"><input type='button' name='calcular_dosis$pfj' value='Calcular Dosis' onclick='Calcular_Dosis(this.form)'></td>";
						$this->salida.="<td colspan=2 width=\"40%\" align=\"left\" ><input type='text' class='input-text' readonly size = 10 name = 'dosis_total$pfj'>  mg</td>" ;
						$this->salida.="</tr>";
						$this->salida.="<tr class=\"$estilo\">";
						$this->salida.="<td class=".$this->SetStyle("cantidad")." width=\"50%\" align = left >CANTIDAD</td>";
						$this->salida.="<td  width=\"65%\" align='left' ><input type='text' class='input-text' size = 15 name = 'cantidad$pfj'   value =\"".$_REQUEST['cantidad'.$pfj]."\"></td>" ;
						$this->salida.="<td  width=\"50%\" align = left >UNIDAD</td>";
						$this->salida.="<td width=\"65%\" align='left' ><input type='text' class='input-text' readonly size = 15 name = 'unidad$pfj'   value =\"".$_REQUEST['unidad'.$pfj]."\"></td>" ;
						$this->salida.="</tr>";
						$this->salida.="</table>";
						$this->salida.="</td>";
						$this->salida.="</tr>";

						//funcion que calcula la dosis
						$this->salida .= "<script>\n";
						$this->salida .= "function Calcular_Dosis(formulario){\n";
						$this->salida .= "var a;\n";
						$this->salida .= "var b;\n";
						$this->salida .= "a=formulario.peso$pfj.value;\n";
						$this->salida .= "b=formulario.dosis_ordenada$pfj.value;\n";
						$this->salida .= "c=a*b;\n";
						$this->salida .= "if(isNaN(c)){\n";
						$this->salida .= "alert('valores no validos');\n";
						$this->salida .= "formulario.dosis_total$pfj.value='';\n";
						$this->salida .= "if(isNaN(b)){\n";
						$this->salida .= "formulario.dosis_ordenada$pfj.value='';\n";
						$this->salida .= "formulario.dosis_ordenada$pfj.focus();\n";
						$this->salida .= "}\n";

						$this->salida .= "if(isNaN(a)){\n";
						$this->salida .= "formulario.peso$pfj.value='';\n";
						$this->salida .= "formulario.peso$pfj.focus();\n";
						$this->salida .= "}\n";

						$this->salida .= "} else {\n";
						$this->salida .= "formulario.dosis_total$pfj.value=c;\n";
						$this->salida .= "}\n";
						$this->salida .= "}\n";
						$this->salida .= "</script>\n";
				//fin de la funcion
          }
*/
//posologia-dosis
          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td width=\"20%\"align=\"left\" >DOSIS</td>";
          $this->salida.="<td width=\"60%\" align = left >";
          $this->salida.="<table>";
          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td  width=\"10%\" class=".$this->SetStyle("dosis")." align = left >DOSIS</td>";
          if (($_REQUEST['dosis'.$pfj])  == '')
        	{
          	$this->salida.="<td width=\"15%\" align='left' ><input type='text' class='input-text' size = 15 name = 'dosis$pfj'   value =\"".$datos[0][dosis]."\"></td>" ;
          }
          else
          {
          $this->salida.="<td width=\"15%\" align='left' ><input type='text' class='input-text' size = 15 name = 'dosis$pfj'   value =\"".$_REQUEST['dosis'.$pfj]."\"></td>" ;
          }

//unidades de dosificacion
          $this->salida.="<td width=\"35%\" class=".$this->SetStyle("unidad_dosis")." align = left >";
          //si no trae unidad de dosificacion segun la forma del producto pinta combo de vias interactivo
          if	(empty($datos[0][unidad_dosificacion_forma]))
          {
               $this->salida.=$javita;
               //este es el if nuevo que coloque para cargar unidades
               if ((sizeof($via_admon)>1))
               {
                    $ComboUnidadDosis ="<select size = 1 name = 'unidad_dosis$pfj'  class =\"select\">";
                    $unidadesViaAdministracion = $this->GetunidadesViaAdministracion($datos[0][via_administracion_id]);
                    $ComboUnidadDosis.="<option value = '-1' selected >--Seleccione--</option>";
                    for($i=0;$i<sizeof($unidadesViaAdministracion);$i++)
                    {
                         if($datos[0][unidad_dosificacion]==$unidadesViaAdministracion[$i][unidad_dosificacion])
                         {
                              $ComboUnidadDosis.="<option selected value = '".$unidadesViaAdministracion[$i][unidad_dosificacion]."'>".$unidadesViaAdministracion[$i][unidad_dosificacion]."</option>";
                         }
                         else
                         {
                              $ComboUnidadDosis.="<option value = '".$unidadesViaAdministracion[$i][unidad_dosificacion]."'>".$unidadesViaAdministracion[$i][unidad_dosificacion]."</option>";
                         }
                    }
                    $ComboUnidadDosis.="</select>";
               }
               //fin del evento nuevo
          }
          $this->salida.="$ComboUnidadDosis";
          $this->salida.="</td>";
          $this->salida.="</tr>";
          $this->salida.="</table>";
          $this->salida.="</td>";
          $this->salida.="</tr>";

//horario
          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td width=\"20%\"align=\"left\" >FRECUENCIA</td>";
          $this->salida.="<td width=\"60%\" align = left >";
          $this->salida.="<table border = 0 >";

          $vector_posologia= $this->Consulta_Solicitud_Medicamentos_Posologia($datos[0][codigo_producto], $datos[0][tipo_opcion_posologia_id], $datos[0][evolucion_id]);
//opcion 1
          $this->salida.="<tr class=\"modulo_list_claro\">";
          if (($_REQUEST['opcion'.$pfj] != '1') AND ($datos[0][	tipo_opcion_posologia_id	]!= '1'))
          {
               $this->salida.="<td width=\"10%\" class=".$this->SetStyle("opcion1")." align=\"left\" ><input type = radio name= 'opcion$pfj' value = 1>OPCION 1</td>";
          }
          else
          {
               $this->salida.="<td width=\"10%\" class=".$this->SetStyle("opcion1")." align=\"left\" ><input type = radio checked name= 'opcion$pfj' value = 1>OPCION 1</td>";
          }

          $this->salida.="<td width=\"50%\"align=\"left\" >";
          $this->salida.="<table>";
          $this->salida.="<tr class=\"modulo_list_claro\">";
          $this->salida.="<td width=\"10%\" align = left >CADA</td>";
          $cada_periocidad = $this->Cargar_Periocidad();
          $this->salida.="<td width=\"10%\" align = left >";
          $this->salida.="<select size = 1 name = 'periocidad$pfj'  class =\"select\">";
          $this->salida.="<option value = '-1' selected>-Seleccione-</option>";
          for($i=0;$i<sizeof($cada_periocidad);$i++)
          {
               if ((($_REQUEST['periocidad'.$pfj])  != $cada_periocidad[$i][periocidad_id]) AND ($cada_periocidad[$i][periocidad_id]!= $vector_posologia[0][periocidad_id]))
               {
                    $this->salida.="<option value = ".$cada_periocidad[$i][periocidad_id].">".$cada_periocidad[$i][periocidad_id]."</option>";
               }
               else
               {
                    $this->salida.="<option value = ".$cada_periocidad[$i][periocidad_id]." selected >".$cada_periocidad[$i][periocidad_id]."</option>";
               }
          }
          $this->salida.="</select>";
          $this->salida.="</td>";
          $this->salida.="<td width=\"30%\" align = 'left' >";
          $this->salida.="<select size = 1 name = 'tiempo$pfj'  class =\"select\">";
          $this->salida.="<option value = '-1' selected>-Seleccione-</option>";
          //opcion de minutos
          if ((($_REQUEST['tiempo'.$pfj])  != 'Min') AND ($vector_posologia[0][tiempo] != 'Min'))
          {
               $this->salida.="<option value = 'Min'>Min</option>";
          }
          else
          {
               $this->salida.="<option value = 'Min' selected >Min</option>";
          }
          //opcion de horas
          if ((($_REQUEST['tiempo'.$pfj])  != 'Hora(s)') AND ($vector_posologia[0][tiempo] != 'Hora(s)'))
          {
               $this->salida.="<option value = 'Hora(s)' >Hora(s)</option>";
          }
          else
          {
               $this->salida.="<option value = 'Hora(s)' selected >Hora(s)</option>";
          }
          //opcion de dias
          if ((($_REQUEST['tiempo'.$pfj])  != 'Dia(s)') AND ($vector_posologia[0][tiempo] != 'Dia(s)'))
          {
               $this->salida.="<option value = 'Dia(s)' >Dia(s)</option>";
          }
          else
          {
               $this->salida.="<option value = 'Dia(s)' selected>Dia(s)</option>";
          }
          //opcion de semanas
          if ((($_REQUEST['tiempo'.$pfj])  != 'Semana(s)') AND ($vector_posologia[0][tiempo] != 'Semana(s)'))
          {
               $this->salida.="<option value = 'Semana(s)' >Semana(s)</option>";
          }
          else
          {
               $this->salida.="<option value = 'Semana(s)' selected >Semana(s)</option>";
          }
          $this->salida.="</select>";
          $this->salida.="</td>";
          $this->salida.="</tr>";
          $this->salida.="</table>";
          $this->salida.="</td>";
          $this->salida.="</tr>";

//OPCION 2
          $this->salida.="<tr class=\"modulo_list_claro\">";
          if (($_REQUEST['opcion'.$pfj] != '2') AND ($datos[0][	tipo_opcion_posologia_id	]!= '2'))
          {
               $this->salida.="<td width=\"10%\" class=".$this->SetStyle("opcion2")." align=\"left\" ><input type = radio name= 'opcion$pfj' value = 2>OPCION 2</td>";
          }
          else
          {
               $this->salida.="<td width=\"10%\" class=".$this->SetStyle("opcion2")." align=\"left\" ><input type = radio checked name= 'opcion$pfj' value = 2>OPCION 2</td>";
          }
          $this->salida.="<td width=\"50%\"align=\"left\" >";
          $this->salida.="<table>";
          $this->salida.="<tr class=\"modulo_list_claro\">";
          $horario = $this->horario();
          $this->salida.="<td class=".$this->SetStyle("durante")." width=\"20%\"align=\"left\" >&nbsp;</td>";
          $this->salida.="<td width=\"60%\" align = left >";
          $this->salida.="<select size = 1 name = 'duracion$pfj'  class =\"select\">";
          $this->salida.="<option value = -1 selected>-Seleccione-</option>";
          for($i=0;$i<sizeof($horario);$i++)
          {
               if ((($_REQUEST['duracion'.$pfj])  != $horario[$i][duracion_id]) AND ($vector_posologia[0][duracion_id] != $horario[$i][duracion_id]))
               {
                    $this->salida.="<option value = ".$horario[$i][duracion_id].">".$horario[$i][descripcion]."</option>";
               }
               else
               {
                    $this->salida.="<option value = ".$horario[$i][duracion_id]." selected >".$horario[$i][descripcion]."</option>";
               }
          }
          $this->salida.="</select>";
          $this->salida.="</td>";
          $this->salida.="</tr>";
          $this->salida.="</table>";
          $this->salida.="</td>";
          $this->salida.="</tr>";

//opcion 3
          $this->salida.="<tr class=\"modulo_list_claro\">";
          if (($_REQUEST['opcion'.$pfj] != '3') AND ($datos[0][	tipo_opcion_posologia_id	]!= '3'))
          {
                    $this->salida.="<td width=\"10%\" class=".$this->SetStyle("opcion3")." align=\"left\" ><input type = radio name= 'opcion$pfj' value = 3>OPCION 3</td>";
          }
          else
          {
                    $this->salida.="<td width=\"10%\" class=".$this->SetStyle("opcion3")." align=\"left\" ><input type = radio checked name= 'opcion$pfj' value = 3>OPCION 3</td>";
          }
          $this->salida.="<td width=\"50%\"align=\"left\" >";
          $this->salida.="<table>";
          $this->salida.="<tr class=\"modulo_list_claro\">";
          if (($_REQUEST['momento'.$pfj] != '1') AND  ($vector_posologia[0][sw_estado_momento]!= '1'))
          {
               $this->salida.="<td width=\"15%\" align = left ><input type = radio name= 'momento$pfj' value = '1'>ANTES</td>";
          }
          else
          {
               $this->salida.="<td width=\"15%\" align = left ><input type = radio name= 'momento$pfj' checked value = '1'>ANTES</td>";
          }
          if (($_REQUEST['momento'.$pfj] != '2') AND  ($vector_posologia[0][sw_estado_momento]!= '2'))
          {
               $this->salida.="<td width=\"15%\" align = left ><input type = radio name= 'momento$pfj' value = '2'>DURANTE</td>";
          }
          else
          {
               $this->salida.="<td width=\"15%\" align = left ><input type = radio name= 'momento$pfj' checked value = '2'>DURANTE</td>";
          }
          if (($_REQUEST['momento'.$pfj] != '3') AND  ($vector_posologia[0][sw_estado_momento]!= '3'))
          {
               $this->salida.="<td width=\"20%\" align = left ><input type = radio name= 'momento$pfj' value = '3'>DESPUES</td>";
          }
          else
          {
               $this->salida.="<td width=\"20%\" align = left ><input type = radio name= 'momento$pfj' checked value = '3'>DESPUES</td>";
          }
          $this->salida.="</tr>";
          $this->salida.="<tr class=\"modulo_list_claro\">";

          if (($_REQUEST['desayuno'.$pfj] != '1') AND ($vector_posologia[0][sw_estado_desayuno]!= '1'))
          {
               $this->salida.="<td  colspan = 3 width=\"50%\" align = left ><input type = checkbox name= 'desayuno$pfj' value = '1'>DESAYUNO</td>";
          }
          else
          {
               $this->salida.="<td  colspan = 3 width=\"50%\" align = left ><input type = checkbox name= 'desayuno$pfj' checked value = '1'>DESAYUNO</td>";
          }
          $this->salida.="</tr>";
          $this->salida.="<tr class=\"modulo_list_claro\">";
          if (($_REQUEST['almuerzo'.$pfj] != '1') AND ($vector_posologia[0][sw_estado_almuerzo]!= '1'))
          {
               $this->salida.="<td  colspan = 3 width=\"50%\" align = left ><input type = checkbox name= 'almuerzo$pfj' value = '1'>ALMUERZO</td>";
          }
          else
          {
               $this->salida.="<td  colspan = 3 width=\"50%\" align = left ><input type = checkbox name= 'almuerzo$pfj' checked value = '1'>ALMUERZO</td>";
          }
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"modulo_list_claro\">";
          if (($_REQUEST['cena'.$pfj] != '1') AND ($vector_posologia[0][sw_estado_cena]!= '1'))
          {
               $this->salida.="<td  colspan = 3 width=\"50%\" align = left ><input type = checkbox name= 'cena$pfj' value = '1'>CENA</td>";
          }
          else
          {
               $this->salida.="<td  colspan = 3 width=\"50%\" align = left ><input type = checkbox name= 'cena$pfj' checked value = '1'>CENA</td>";
          }
          $this->salida.="</tr>";

          $this->salida.="</table>";
          $this->salida.="</td>";
          $this->salida.="</tr>";


//OPCION 4
          $this->salida.="<tr class=\"modulo_list_claro\">";
          if (($_REQUEST['opcion'.$pfj] != '4') AND ($datos[0][	tipo_opcion_posologia_id	]!= '4'))
          {
               $this->salida.="<td width=\"10%\" class=".$this->SetStyle("opcion4")." align=\"left\" ><input type = radio name= 'opcion$pfj' value = 4>OPCION 4</td>";
          }
          else
          {
               $this->salida.="<td width=\"10%\" class=".$this->SetStyle("opcion4")." align=\"left\" ><input type = radio checked name= 'opcion$pfj' value = 4>OPCION 4</td>";
          }
          $this->salida.="<td width=\"50%\"align=\"left\" >";
          $this->salida.="<table>";
          $this->salida.="<tr class=\"modulo_list_claro\">";
          $this->salida.="<td colspan = 8 width=\"50%\" align = left >HORA ESPECIFICA</td>";
          $this->salida.="</tr>";
          $this->salida.="<tr class=\"modulo_list_claro\">";
          $hora_especifica = $_REQUEST['opH'.$pfj];

          if ((($hora_especifica[6])  != '06 am') AND empty($vector_posologia['06 am']))
          {
               $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[6]' value = '06 am'>06</td>";
          }
          else
          {
               $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox checked name= 'opH".$pfj."[6]' value = '06 am'>06</td>";
          }
          
          if ((($hora_especifica[9])  != '09 am') AND empty($vector_posologia['09 am']))
          {
               $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[9]' value = '09 am'>09</td>";
          }
          else
          {
               $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox checked name= 'opH".$pfj."[9]' value = '09 am'>09</td>";
          }

          if ((($hora_especifica[12])  != '12 pm') AND empty($vector_posologia['12 pm']))
          {
               $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[12]' value = '12 pm'>12</td>";
          }
          else
          {
               $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox checked name= 'opH".$pfj."[12]' value = '12 pm'>12</td>";
          }
          
          if ((($hora_especifica[15])  != '03 pm') AND empty($vector_posologia['03 pm']))
          {
               $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[15]' value = '03 pm'>15</td>";
          }
          else
          {
               $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox checked name= 'opH".$pfj."[15]' value = '03 pm'>15</td>";
          }

          if ((($hora_especifica[18])  != '06 pm') AND empty($vector_posologia['06 pm']))
          {
               $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[18]' value = '06 pm'>18</td>";
          }
		else
          {
               $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox checked name= 'opH".$pfj."[18]' value = '06 pm'>18</td>";
          }
          
          if ((($hora_especifica[21])  != '09 pm') AND empty($vector_posologia['09 pm']))
          {
               $this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[21]' value = '09 pm'>21</td>";
          }
          else
          {
               $this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox checked name= 'opH".$pfj."[21]' value = '09 pm'>21</td>";
          }
          
          if ((($hora_especifica[24])  != '00 am') AND empty($vector_posologia['00 am']))
          {
               $this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[24]' value = '00 am'>24</td>";
          }
          else
          {
               $this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[24]' value = '00 am'>24</td>";
          }
          
          if ((($hora_especifica[3])  != '03 am') AND empty($vector_posologia['03 am']))
          {
               $this->salida.="<td class =label_error colspan = 1 width=\"15%\" align = left ><input type = checkbox name= 'opH".$pfj."[3]' value = '03 am'>03</td>";
          }
          else
          {
               $this->salida.="<td class =label_error colspan = 1 width=\"15%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[3]' value = '03 am'>03</td>";
          }
          $this->salida.="</tr>";
          $this->salida.="<tr class=\"modulo_list_claro\">";
          
          if ((($hora_especifica[7])  != '07 am') AND empty($vector_posologia['07 am']))
          {
               $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[7]' value = '07 am'>07</td>";
          }
          else
          {
               $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[7]' value = '07 am'>07</td>";
          }
          
          if ((($hora_especifica[10])  != '10 am') AND empty($vector_posologia['10 am']))
          {
               $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[10]' value = '10 am'>10</td>";
          }
          else
          {
               $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[10]' value = '10 am'>10</td>";
          }
          
          if ((($hora_especifica[13])  != '01 pm') AND empty($vector_posologia['01 pm']))
          {
               $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[13]' value = '01 pm'>13</td>";
          }
          else
          {
               $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[13]' value = '01 pm'>13</td>";
          }
          
          if ((($hora_especifica[16])  != '04 pm') AND empty($vector_posologia['04 pm']))
          {
               $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[16]' value = '04 pm'>16</td>";
          }
          else
          {
               $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[16]' value = '04 pm'>16</td>";
          }
          
          if ((($hora_especifica[19])  != '07 pm') AND empty($vector_posologia['07 pm']))
          {
               $this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[19]' value = '07 pm'>19</td>";
          }
          else
          {
               $this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[19]' value = '07 pm'>19</td>";
          }
          
          if ((($hora_especifica[22])  != '10 pm') AND empty($vector_posologia['10 pm']))
          {
               $this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[22]' value = '10 pm'>22</td>";
          }
          else
          {
               $this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[22]' value = '10 pm'>22</td>";
          }
          
          if ((($hora_especifica[1])  != '01 am') AND empty($vector_posologia['01 am']))
          {
               $this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[1]' value = '01 am'>01</td>";
          }
          else
          {
               $this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[1]' value = '01 am'>01</td>";
          }
          
          if ((($hora_especifica[4])  != '04 am') AND empty($vector_posologia['04 am']))
          {
               $this->salida.="<td class =label_error colspan = 1 width=\"15%\" align = left ><input type = checkbox name= 'opH".$pfj."[4]' value = '04 am'>04</td>";
          }
          else
          {
               $this->salida.="<td class =label_error colspan = 1 width=\"15%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[4]' value = '04 am'>04</td>";
          }
          $this->salida.="</tr>";
          $this->salida.="<tr class=\"modulo_list_claro\">";

          if ((($hora_especifica[8])  != '08 am') AND empty($vector_posologia['08 am']))
          {
               $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[8]' value = '08 am'>08</td>";
          }
          else
          {
               $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[8]' value = '08 am'>08</td>";
          }
          if ((($hora_especifica[11])  != '11 am') AND empty($vector_posologia['11 am']))
          {
               $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[11]' value = '11 am'>11</td>";
          }
          else
          {
               $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[11]' value = '11 am'>11</td>";
          }

          if ((($hora_especifica[14])  != '02 pm') AND empty($vector_posologia['02 pm']))
          {
               $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[14]' value = '02 pm'>14</td>";
          }
          else
          {
               $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[14]' value = '02 pm'>14</td>";
          }
          if ((($hora_especifica[17])  != '05 pm') AND empty($vector_posologia['05 pm']))
          {
               $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[17]' value = '05 pm'>17</td>";
          }
          else
          {
               $this->salida.="<td colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[17]' value = '05 pm'>17</td>";
          }
          if ((($hora_especifica[20])  != '08 pm') AND empty($vector_posologia['08 pm']))
          {
               $this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[20]' value = '08 pm'>20</td>";
          }
          else
          {
               $this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[20]' value = '08 pm'>20</td>";
          }
          if ((($hora_especifica[23])  != '11 pm') AND empty($vector_posologia['11 pm']))
          {
               $this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[23]' value = '11 pm'>23</td>";
          }
          else
          {
               $this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[23]' value = '11 pm'>23</td>";
          }
          if ((($hora_especifica[2])  != '02 am') AND empty($vector_posologia['02 am']))
          {
               $this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH".$pfj."[2]' value = '02 am'>02</td>";
          }
          else
          {
               $this->salida.="<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[2]' value = '02 am'>02</td>";
          }
          if ((($hora_especifica[5])  != '05 am') AND empty($vector_posologia['05 am']))
          {
               $this->salida.="<td class =label_error colspan = 1 width=\"15%\" align = left ><input type = checkbox name= 'opH".$pfj."[5]' value = '05 am'>05</td>";
          }
          else
          {
               $this->salida.="<td class =label_error colspan = 1 width=\"15%\" align = left ><input type = checkbox  checked name= 'opH".$pfj."[5]' value = '05 am'>05</td>";
          }
          $this->salida.="</tr>";
          $this->salida.="</table>";
          $this->salida.="</td>";
          $this->salida.="</tr>";

//OPCION 5
          $this->salida.="<tr class=\"modulo_list_claro\">";
          if (($_REQUEST['opcion'.$pfj] != '5') AND ($datos[0][	tipo_opcion_posologia_id]!= '5'))
          {
               $this->salida.="<td width=\"10%\" class=".$this->SetStyle("opcion5")." align=\"left\" ><input type = radio name= 'opcion$pfj' value = 5>OPCION 5</td>";
          }
          else
          {
               $this->salida.="<td width=\"10%\" class=".$this->SetStyle("opcion5")." align=\"left\" ><input type = radio checked name= 'opcion$pfj' value = 5>OPCION 5</td>";
          }
          $this->salida.="<td width=\"50%\"align=\"left\" >";
          $this->salida.="<table>";
          $this->salida.="<tr class=\"modulo_list_claro\">";
          $this->salida.="<td  colspan = 3 width=\"50%\" align = left >DESCRIBA LA FRECUENCIA PARA EL SUMINISTRO DEL MEDICAMENTO</td>";
          $this->salida.="</tr>";
          $this->salida.="<tr class=\"modulo_list_claro\">";

          if (($_REQUEST['frecuencia_suministro'.$pfj])  == '')
          {
               $this->salida.="<td colspan = 3 width=\"50%\" align='left' ><textarea style = \"width:80%\" class='textarea' name = 'frecuencia_suministro$pfj' cols = 60 rows = 3>".$vector_posologia[0][frecuencia_suministro]."</textarea></td>" ;

          }
		else
          {
               $this->salida.="<td colspan = 3 width=\"50%\" align='left' ><textarea style = \"width:80%\" class='textarea' name = 'frecuencia_suministro$pfj' cols = 60 rows = 3>".$_REQUEST['frecuencia_suministro'.$pfj]."</textarea></td>" ;
          }
          $this->salida.="</tr>";
          $this->salida.="</table>";
          $this->salida.="</td>";
          $this->salida.="</tr>";

          $this->salida.="</table>";
          $this->salida.="</td>";
          $this->salida.="</tr>";

//cantidad
          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td width=\"20%\"align=\"left\" >CANTIDAD</td>";
          $this->salida.="<td width=\"60%\" align = left >";
          $this->salida.="<table>";
          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td class=".$this->SetStyle("cantidad")." width=\"5%\" align = left >CANTIDAD</td>";
          if (($_REQUEST['cantidad'.$pfj])  == '')
          {
                    $this->salida.="<td  width=\"5%\" align='left' ><input type='text' class='input-text' size = 5 name = 'cantidad$pfj'   value =\"".$datos[0][cantidad]."\"></td>" ;
          }
          else
          {
                    $this->salida.="<td  width=\"5%\" align='left' ><input type='text' class='input-text' size = 5 name = 'cantidad$pfj'   value =\"".$_REQUEST['cantidad'.$pfj]."\"></td>" ;
          }

          $frase = ' ';
          if ($datos[0][contenido_unidad_venta]!='')
          {
                         $frase = ' por ';
          }
          $this->salida.="<td width=\"30%\" align='left' ><input type='text' class='input-text' readonly size = 30 name = 'unidad$pfj'   value = '".$datos[0][descripcion]."".$frase."".$datos[0][contenido_unidad_venta]."'></td>" ;
          $this->salida.="</tr>";
          $this->salida.="</table>";
          $this->salida.="</td>";
          $this->salida.="</tr>";
//fin de cantidad
        if($via_admon[0]['tipo_via_id'])
        {
               //Relacion del medicamento con una solucion
               $this->salida.="<tr class=\"$estilo\">";
               $this->salida.="<td width=\"15%\" align=\"left\" >SOLUCION PARA LA MEZCLA DEL MEDICAMENTO</td>";
               $this->salida.="<td valign=\"top\">";
               $this->salida.="<select size = 1 name = 'solucion$pfj'  class =\"select\">";
               $this->salida.="<option value = '-1' selected>-Seleccione-</option>";
               $tiposSoluciones = $this->tiposSoluciones();
               for($i=0;$i<sizeof($tiposSoluciones);$i++)
               {
                    $id = $tiposSoluciones[$i][solucion_id];
                    $opcion = $tiposSoluciones[$i][descripcion];
                    if ($_REQUEST['solucion'.$pfj] == $id || $datos[0]['solucion_id'] == $id){
	                    $this->salida.="<option value = '$id' selected >$opcion</option>";
                    }else{
     	               $this->salida.="<option value = '$id'>$opcion</option>";
                    }
               }
               $this->salida.="</select>&nbsp&nbsp&nbsp&nbsp&nbsp;";
               $this->salida.="<label>CANTIDAD</label>&nbsp&nbsp;";
               $this->salida.="<select size = 1 name = 'solucionUnidad$pfj'  class =\"select\">";
               $this->salida.="<option value = '-1' selected>-Seleccione-</option>";
               $tiposUnidades = $this->tiposUnidadesSoluciones();
               for($i=0;$i<sizeof($tiposUnidades);$i++){
                    $id = $tiposUnidades[$i][cantidad_id];
                    if($_REQUEST['solucionUnidad'.$pfj]  == $id)
                    {
                    	$this->salida.="<option value = '$id' selected >".$tiposUnidades[$i][cantidad]."  ".$tiposUnidades[$i][unidad_id]."</option>";
                    }elseif($datos[0]['cantidad_id'] == $id){
                    	$this->salida.="<option value = '$id' selected >".$tiposUnidades[$i][cantidad]."  ".$tiposUnidades[$i][unidad_id]."</option>";
                    }else{
                    	$this->salida.="<option value = '$id'>".$tiposUnidades[$i][cantidad]."  ".$tiposUnidades[$i][unidad_id]."</option>";
                    }
               }
               $this->salida.="</select>";
               $this->salida.="</td>";
               $this->salida.="</tr>";
               //fin soluciones
	     }
          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td width=\"15%\" align=\"left\" >OBSERVACIONES E INDICACION DE SUMINISTRO</td>";
          
          if (($_REQUEST['observacion'.$pfj])  == '')
          {
               $this->salida.="<td width=\"65%\"align='center'><textarea style = \"width:80%\" class='textarea' name = 'observacion$pfj' cols = 60 rows = 3>".$datos[0][observacion]."</textarea></td>" ;
          }
          else
          {
               $this->salida.="<td width=\"50%\"align='center'><textarea style = \"width:80%\" class='textarea' name = 'observacion$pfj' cols = 60 rows = 3>".$_REQUEST['observacion'.$pfj]."</textarea></td>" ;
          }
          $this->salida.="</tr>";
          
          if($datos[0][item] == 'NO POS')
          {
               $this->salida.="<tr class=\"$estilo\">";
               if (($_REQUEST['no_pos_paciente'.$pfj] != '1') AND  ($datos[0][sw_paciente_no_pos]!= '1'))
               {
                         $this->salida.="  <td colspan = 2 align=\"center\" width=\"5%\"><input type = \"checkbox\" name= 'no_pos_paciente$pfj' value = 1>FORMULACION NO POS A PETICION DEL PACIENTE</td>";
               }
               else
               {
                         $this->salida.="  <td colspan = 2 align=\"center\" width=\"5%\"><input type = \"checkbox\" name= 'no_pos_paciente$pfj' checked value = 1>FORMULACION NO POS A PETICION DEL PACIENTE</td>";
               }
               $this->salida.="</tr>";
          }
          $this->salida.="<tr class=\"$estilo\">";
          if(!empty($_REQUEST['sw_ambulatorio'.$pfj]) || !empty($datos[0]['sw_ambulatorio'])){
               $che='checked';
          }else{
               $che='';
          }
          $this->salida.="  <td class = 'label' colspan = 2 align=\"center\" width=\"5%\"><input type = \"checkbox\" name= 'sw_ambulatorio$pfj' value = 1  $che>MEDICAMENTO AMBULATORIO</td>";
          $this->salida.="</tr>";

          $this->salida.="</table><br>";
          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"0\"><tr>";
          $this->salida .= "<td   align=\"center\"><input class=\"input-submit\" name= 'guardar_formula$pfj' type=\"submit\" value=\"MODIFICAR FORMULA\"></td>";

          $this->salida .= "</form>";
          $accion3=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false, array('accion'.$pfj=>''));
          $this->salida .= "<form name=\"forma\" action=\"$accion3\" method=\"post\">";
          $this->salida .= "<td   align=\"center\"><input class=\"input-submit\" name= 'cancelar$pfj' type=\"submit\" value=\"VOLVER\"></form></td>";
          $this->salida.="</tr></table>";
//................fin de la modificacion
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}


//clzc - jea - ptce - *
	function RetornarBarraMedicamentos_Avanzada()//Barra paginadora de los planes clientes
	{
		$pfj=$this->frmPrefijo;
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso1'.$pfj];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Medicamentos',
		'conteo'.$pfj=>$this->conteo,'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
		'criterio1'.$pfj=>$_REQUEST['criterio1'.$pfj],
		'producto'.$pfj=>$_REQUEST['producto'.$pfj],
		'principio_activo'.$pfj=>$_REQUEST['principio_activo'.$pfj]));

		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";

     	if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset(1)."&paso1$pfj=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso-1)."&paso1$pfj=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($i)."&paso1$pfj=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso+1)."&paso1$pfj=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($numpasos)."&paso1$pfj=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($i)."&paso1$pfj=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso+1)."&paso1$pfj=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($numpasos)."&paso1$pfj=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
		if(($_REQUEST['Of'.$pfj])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		else
		{
			if($numpasos>10)
			{
				$valor=10+5;
			}
			else
			{
				$valor=$numpasos+5;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		return $salida;
	}


     function frmForma_Add()
     {
		$pfj=$this->frmPrefijo;
		unset ($_SESSION['DATOS_M'.$pfj]['PLAN_TERAPEUTICO']);
		unset ($_SESSION['MEDICAMENTOS'.$pfj]);
		unset ($_SESSION['POSOLOGIA4'.$pfj]);
		unset ($_SESSION['DIAGNOSTICOS'.$pfj]);
		unset ($_SESSION['JUSTIFICACION'.$pfj]);
		unset ($_SESSION['MODIFICANDO'.$pfj]);
		unset ($_SESSION['DIAGNOSTICOSM'.$pfj]);
		unset ($_SESSION['MEDICAMENTOSM'.$pfj]);
		if(empty($this->titulo))
		{
			$this->salida.= ThemeAbrirTablaSubModulo('SOLICITUD DE MEDICAMENTOS');
		}
		else
		{
			$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
		}

		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
		$this->salida .= "<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
		$vector1=$this->Consulta_Solicitud_Medicamentos();
		$m = 0;
		if($vector1)
		{
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida.="</table>";

			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="<td align=\"center\" colspan=\"8\">MEDICAMENTOS SOLICITADOS</td>";
			$this->salida.="</tr>";

			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td width=\"7%\">CODIGO</td>";
			$this->salida.="  <td width=\"30%\">PRODUCTO</td>";
			$this->salida.="  <td width=\"29%\">PRINCIPIO ACTIVO</td>";
			$this->salida.="  <td colspan= 5 width=\"14%\">OPCIONES</td>";
			$this->salida.="</tr>";
			//$this->salida.="</tr>";
			for($i=0;$i<sizeof($vector1);$i++)
			{
                    if($vector1[$i]['sw_ambulatorio']==1){$sumaRows=1;}else{$sumaRows=0;}
                         //if($_SESSION['PROFESIONAL'.$pfj]!=1){$sumaRows+=1;}

                         $vectorMSH = $this->Consulta_Solicitud_Medicamentos_Historial($vector1[$i][codigo_producto]);
                         if( $i % 2){ $estilo='modulo_list_claro';}
                         else {$estilo='modulo_list_oscuro';}
                         $this->salida.="<tr class=\"$estilo\">";
                         if($vector1[$i][item] == 'NO POS')
                         {
                              if ($vectorMSH AND (sizeof($vectorMSH) > '1'))
                              {
                                   $sumaRows+=6;
                                   $this->salida.="  <td ROWSPAN = \"$sumaRows\" align=\"center\" width=\"7%\">".$vector1[$i][codigo_producto]."<BR>NO_POS</td>";
                              }
                              else
                              {
                                   $sumaRows+=5;
                                   $this->salida.="  <td ROWSPAN = \"$sumaRows\" align=\"center\" width=\"7%\">".$vector1[$i][codigo_producto]."<BR>NO_POS</td>";
                              }
                         }
                         else
                         {
                              if($vectorMSH AND (sizeof($vectorMSH) > '1'))
                              {
                                   $sumaRows+=5;
                                   $this->salida.="  <td ROWSPAN = \"$sumaRows\" align=\"center\" width=\"7%\">".$vector1[$i][codigo_producto]."</td>";
                              }
                              else
                              {
                                   $sumaRows+=4;
                                   $this->salida.="  <td ROWSPAN = \"$sumaRows\" align=\"center\" width=\"7%\">".$vector1[$i][codigo_producto]."</td>";
                              }
                         }
                         //LINEA ALTERADA para ver la evolucion
                         $this->salida.="  <td align=\"center\" width=\"30%\">".$vector1[$i][producto]."-".$vector1[$i][evolucion_id]."</td>";
                         $this->salida.="  <td align=\"left\" width=\"29%\">".$vector1[$i][principio_activo]."</td>";

                         $this->salida.="  <td align=\"center\" width=\"3%\">";
                         if($vector1[$i][evolucion_id] != $this->evolucion)
                         {
                              //*lo que inserte de FINALIZACION
                              if($vector1[$i]['sw_estado'] == '1')
                              {
                                   if ($_SESSION['PROFESIONAL'.$pfj]==1)
                                   {
                                        $href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Suspender_Medicamento', 'codigo_producto'.$pfj=>$vector1[$i]['codigo_producto'], 'producto'.$pfj=>$vector1[$i]['producto'],'principio_activo'.$pfj=>$vector1[$i]['principio_activo'], 'evolucion_id'.$pfj=>$vector1[$i]['evolucion_id'], 'tipo_nota'.$pfj=>'2'));
                                        $this->salida .= "<a href='".$href."'><font color='#0000FF'>Suspender</font></a>";

                                        $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Finalizar_Medicamento', 'codigo_producto'.$pfj=>$vector1[$i]['codigo_producto'], 'evolucion_id'.$pfj=>$vector1[$i]['evolucion_id']));
                                        $this->salida .= "<br><a href='".$accion."'><font color='#8C8030'>Finalizar</font></a>\n";
                                   }
                                   elseif($_SESSION['PROFESIONAL'.$pfj]==3)
                                   {
                                        $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Suspender_Medicamento', 'codigo_producto'.$pfj=>$vector1[$i]['codigo_producto'], 'producto'.$pfj=>$vector1[$i]['producto'],'principio_activo'.$pfj=>$vector1[$i]['principio_activo'], 'evolucion_id'.$pfj=>$vector1[$i]['evolucion_id'], 'tipo_nota'.$pfj=>'2'));
                                        $this->salida .= "<br><a href='".$accion."'><font color='#035512'>Suspender</font></a>";
                                   }
                              }
                              //fin
                         }
                         $this->salida.="</td>";

                         //*lo que inserte de control de suministro
                         if($vector1[$i]['sw_estado'] == '1')
                         {
                              $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Control_Suministro', 'codigo_producto'.$pfj=>$vector1[$i]['codigo_producto'],'producto'.$pfj=>$vector1[$i]['producto'],'principio_activo'.$pfj=>$vector1[$i]['principio_activo'], 'evolucion_id'.$pfj=>$vector1[$i]['evolucion_id'], 'cantidad'.$pfj=>$vector1[$i][cantidad], 'descripcion'.$pfj=>$vector1[$i][descripcion], 'contenido_unidad_venta'.$pfj=>$vector1[$i][contenido_unidad_venta], 'unidad_dosificacion'.$pfj=>$vector1[$i][unidad_dosificacion], 'dosis'.$pfj=>$vector1[$i][dosis]));
                              //$this->salida .= "		<td align='center' width=\"3%\"><a href='".$accion."'><font color=\"#077325\">Suministro Medicamentos</font></a></td>\n";
                              $this->salida .= "<td align='center' width=\"3%\"><font color=\"#077325\">Suministro Medicamentos</font></td>\n";
                         }
                         else
                         {
                              $this->salida .= "		<td align='center' width=\"3%\">&nbsp;</td>\n";
                         }
                         //fin

                         //*lo que inserte de Ver Detalle Suministro
                         $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Detalle_Suministro', 'codigo_producto'.$pfj=>$vector1[$i]['codigo_producto'], 'producto'.$pfj=>$vector1[$i]['producto'], 'principio_activo'.$pfj=>$vector1[$i]['principio_activo']));
                         $this->salida .= "<td align='center' width=\"3%\"><font color=\"#990000\"><a href='".$accion."'>Notas del Medicamento</a></font></td>\n";
                         //fin
                         
                         //validar quien puede eliminar o modifiacar el medicamento
                         if($vector1[$i][evolucion_id] == $this->evolucion)
                         {
                              $accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'forma_modificar_medicamento', 'codigo_producto'.$pfj => $vector1[$i][codigo_producto]));
                              $this->salida.="  <td align=\"center\" width=\"3%\"><a href='$accion1'><img title=\"Modificar\" src=\"".GetThemePath()."/images/modificar.png\" border='0'></a></td>";
                              $accion2=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'eliminar', 'codigo_producto'.$pfj => $vector1[$i][codigo_producto], 'opcion_posologia'.$pfj => $vector1[$i][tipo_opcion_posologia_id]));
                              $this->salida.="  <td align=\"center\" width=\"2%\"><a href='$accion2'><img title=\"Eliminar\" src=\"".GetThemePath()."/images/elimina.png\"  border='0'></a></td>";								}
                         else
                         {
                              $this->salida.="  <td colspan=\"2\" align=\"center\" width=\"5%\">&nbsp;</td>";
                         }

                         //fin del validador
                         $this->salida.="</tr>";


                         $this->salida.="<tr class=\"$estilo\">";
                         $this->salida.="<td colspan = 7>";
                         $this->salida.="<table>";

                         $this->salida.="<tr class=\"$estilo\">";
                                   $this->salida.="  <td colspan = 3 align=\"left\" width=\"9%\">Via de Administracion: ".$vector1[$i][via]."</td>";
                         $this->salida.="</tr>";

                         $this->salida.="<tr class=\"$estilo\">";
                         $this->salida.="  <td align=\"left\" width=\"9%\">Dosis:</td>";
                         $e=$vector1[$i][dosis]/floor($vector1[$i][dosis]);
                         if($e==1)
                         {
                              $this->salida.="  <td align=\"left\" width=\"14%\">".floor($vector1[$i][dosis])."  ".$vector1[$i][unidad_dosificacion]."</td>";
                         }
                         else
                         {
                              $this->salida.="  <td align=\"left\" width=\"14%\">".$vector1[$i][dosis]."  ".$vector1[$i][unidad_dosificacion]."</td>";
                         }

                         $vector_posologia= $this->Consulta_Solicitud_Medicamentos_Posologia($vector1[$i][codigo_producto], $vector1[$i][tipo_opcion_posologia_id], $vector1[$i][evolucion_id]);

//pintar formula para opcion 1
                         if($vector1[$i][tipo_opcion_posologia_id]== 1)
                         {
                              $this->salida.="  <td align=\"left\" width=\"50%\">cada ".$vector_posologia[0][periocidad_id]." ".$vector_posologia[0][tiempo]."</td>";
                         }

//pintar formula para opcion 2
                         if($vector1[$i][tipo_opcion_posologia_id]== 2)
                         {
                              $this->salida.="  <td align=\"left\" width=\"50%\">".$vector_posologia[0][descripcion]."</td>";
                         }

//pintar formula para opcion 3
                         if($vector1[$i][tipo_opcion_posologia_id]== 3)
                         {
                              $momento = '';
                              if($vector_posologia[0][sw_estado_momento]== '1')
                              {
                                   $momento = 'antes de ';
                              }
                              else
                              {
                                   if($vector_posologia[0][sw_estado_momento]== '2')
                                   {
                                        $momento = 'durante ';
                                   }
                                   else
                                   {
                                        if($vector_posologia[0][sw_estado_momento]== '3')
                                        {
                                             $momento = 'despues de ';
                                        }
                                   }
                              }
                              $Cen = $Alm = $Des= '';
                              $cont= 0;
                              $conector = '  ';
                              $conector1 = '  ';
                              if($vector_posologia[0][sw_estado_desayuno]== '1')
                              {
                                   $Des = $momento.'el Desayuno';
                                   $cont++;
                              }
                              if($vector_posologia[0][sw_estado_almuerzo]== '1')
                              {
                                   $Alm = $momento.'el Almuerzo';
                                   $cont++;
                              }
                              if($vector_posologia[0][sw_estado_cena]== '1')
                              {
                                   $Cen = $momento.'la Cena';
                                   $cont++;
                              }
                              if ($cont== 2)
                              {
                                   $conector = ' y ';
                                   $conector1 = '  ';
                              }
                              if ($cont== 1)
                              {
                                   $conector = '  ';
                                   $conector1 = '  ';
                              }
                              if ($cont== 3)
                              {
                                   $conector = ' , ';
                                   $conector1 = ' y ';
                              }
                              $this->salida.="  <td align=\"left\" width=\"50%\">".$Des."".$conector."".$Alm."".$conector1."".$Cen."</td>";
                         }

//pintar formula para opcion 4
                         if($vector1[$i][tipo_opcion_posologia_id]== 4)
                         {
                              $conector = '  ';
                              $frecuencia='';
                              $j=0;
                              foreach ($vector_posologia as $k => $v)
                              {
                                   if ($j+1 ==sizeof($vector_posologia))
                                   {
                                        $conector = '  ';
                                   }
                                   else
                                   {
                                             if ($j+2 ==sizeof($vector_posologia))
                                                  {
                                                       $conector = ' y ';
                                                  }
                                             else
                                                  {
                                                       $conector = ' - ';
                                                  }
                                   }
                                   $frecuencia = $frecuencia.$k.$conector;
                                   $j++;
                              }
                              $this->salida.="  <td align=\"left\" width=\"50%\">a la(s): $frecuencia</td>";
                         }

//pintar formula para opcion 5
                         if($vector1[$i][tipo_opcion_posologia_id]== 5)
                         {
                              $this->salida.="  <td align=\"left\" width=\"50%\">".$vector_posologia[0][frecuencia_suministro]."</td>";
                         }
                         $this->salida.="</tr>";
          
                         $this->salida.="<tr class=\"$estilo\">";
                         $this->salida.="  <td align=\"left\" width=\"9%\">Cantidad:</td>";
                         $e=$vector1[$i][cantidad]/floor($vector1[$i][cantidad]);
                         if ($vector1[$i][contenido_unidad_venta])
                         {
                              if($e==1)
                              {
                                   $this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".floor($vector1[$i][cantidad])." ".$vector1[$i][descripcion]." por ".$vector1[$i][contenido_unidad_venta]."</td>";
                              }
                              else
                              {
                                   $this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".$vector1[$i][cantidad]." ".$vector1[$i][descripcion]." por ".$vector1[$i][contenido_unidad_venta]."</td>";
                              }
                         }
                         else
                         {
                              if($e==1)
                              {
                                   $this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".floor($vector1[$i][cantidad])." ".$vector1[$i][descripcion]."</td>";
                              }
                              else
                              {
                                   $this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".$vector1[$i][cantidad]." ".$vector1[$i][descripcion]."</td>";
                              }
                         }
                         $this->salida.="</tr>";
          
                         $this->salida.="</table>";
                         $this->salida.="</td>";
                         $this->salida.="</tr>";
          
                         $this->salida.="<tr class=\"$estilo\">";
                         $this->salida.="<td colspan = 7 class=\"$estilo\">";
                         $this->salida.="<table>";
                         $this->salida.="<tr class=\"$estilo\">";
                         $this->salida.="  <td align=\"left\" width=\"4%\">Observacion:</td>";
                         $this->salida.="  <td align=\"left\" width=\"69%\">".$vector1[$i][observacion]."</td>";
                         $this->salida.="</tr>";
                         $this->salida.="<tr class=\"$estilo\">";
                         if($vector1[$i][sw_uso_controlado]==1){
                              $this->salida.="<tr class=\"$estilo\">";
                              $this->salida.="  <td align=\"left\" colspan = 2 width=\"73%\">MEDICAMENTO DE USO CONTROLADO</td>";
                              $this->salida.="<tr class=\"$estilo\">";
                         }
                         $this->salida.="</table>";
                         $this->salida.="</td>";
                         $this->salida.="</tr>";
          
                         if($vector1[$i][sw_ambulatorio]==1){
                              $this->salida.="<tr class=\"$estilo\">";
                              $this->salida.="<td colspan = 7 class=\"$estilo\">";
                              $this->salida.="  <table>";
                              $this->salida.="  <tr class=\"$estilo\">";
                              $this->salida.="    <td colspan=\"2\" align=\"left\" width=\"4%\" class=\"label\">Medicamento Ambulatorio</td>";
                              $this->salida.="  </tr>";
                              $this->salida.="  </table>";
                              $this->salida.=" </td>";
                              $this->salida.="</tr>";
                         }
          
                         /*if($_SESSION['PROFESIONAL'.$pfj]!=1){
                              $this->salida.="<tr class=\"$estilo\">";
                              $this->salida.="<td colspan = 7 class=\"$estilo\">";
                              $this->salida.="  <table>";
                              $this->salida.="  <tr class=\"$estilo\">";
                              $this->salida.="    <td colspan=\"2\" align=\"left\" width=\"4%\" class=\"label\">FORMULADO POR:&nbsp&nbsp&nbsp;".$vector1[$i][nombre_tercero]."</td>";
                              $this->salida.="  </tr>";
                              $this->salida.="  </table>";
                              $this->salida.=" </td>";
                              $this->salida.="</tr>";
                         }*/                         
                         if (!empty($vector1[$i][nombre_tercero]))
                         {
                              $fechaf = $this->FechaStamp($vector1[$i][fecha]);
                              $this->salida.="<tr class=\"$estilo\">";
                              $this->salida.="<td align=\"left\" width=\"50%\" colspan=\"2\"><b>Formuló:&nbsp;&nbsp;</b>".$vector1[$i][nombre_tercero]."</td>";
                              $this->salida.="<td align=\"left\" width=\"20%\" colspan=\"5\"><b>Fecha Formulación:&nbsp;&nbsp;</b>".$fechaf."</td>";
                              $this->salida.="</tr>";
                          }
          
                         if($vector1[$i][item] == 'NO POS'){
                              $this->salida.="<tr class=\"$estilo\">";
                              if($vector1[$i][sw_paciente_no_pos] != '1')
                              {
                                   if($vector1[$i][evolucion_id] == $this->evolucion)
                                   {
                                        $accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Consultar_Justificacion', 'codigo_p'.$pfj => $vector1[$i][codigo_producto], 'product'.$pfj => $vector1[$i][producto], 'principio_a'.$pfj => $vector1[$i][principio_activo], 'via'.$pfj => $vector1[$i][via],'dosis'.$pfj => $vector1[$i][dosis], 'unidad'.$pfj => $vector1[$i][unidad_dosificacion], 'canti'.$pfj => $vector1[$i][cantidad],'desc'.$pfj => $vector1[$i][descripcion],'contenido_u_v'.$pfj => $vector1[$i][contenido_unidad_venta], 'obs'.$pfj => $vector1[$i][observacion], 'evolucion'.$pfj => $vector1[$i][evolucion_id]));
                                        $this->salida.="<td colspan = 7 align=\"center\" width=\"63%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/auditoria.png\" border='0'> VER JUSTIFICACION</a></td>";
                                   }
                                   else
                                   {
                                        $accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Consultar_Justificacion', 'codigo_p'.$pfj => $vector1[$i][codigo_producto], 'product'.$pfj => $vector1[$i][producto], 'principio_a'.$pfj => $vector1[$i][principio_activo], 'via'.$pfj => $vector1[$i][via],'dosis'.$pfj => $vector1[$i][dosis], 'unidad'.$pfj => $vector1[$i][unidad_dosificacion], 'canti'.$pfj => $vector1[$i][cantidad],'desc'.$pfj => $vector1[$i][descripcion],'contenido_u_v'.$pfj => $vector1[$i][contenido_unidad_venta], 'obs'.$pfj => $vector1[$i][observacion],'evolucion'.$pfj => $vector1[$i][evolucion_id],'consultar_just'.$pfj => 1));
                                        $this->salida.="<td colspan = 7 align=\"center\" width=\"63%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/auditoria.png\" border='0'> CONSULTAR JUSTIFICACION</a></td>";
                                   }
                              }
                              else
                              {
                                   $this->salida.="<td class = label_error colspan = 7 align=\"center\" width=\"63%\">MEDICAMENTO NO POS FORMULADO A PETICION DEL PACIENTE</td>";
                              }
                              $this->salida.="</tr>";
                         }
          
          //HISTORIAL DEL MEDICAMENTO
                         if ($vectorMSH AND (sizeof($vectorMSH) > '1'))
                         {
                              $registros_historial = sizeof($vectorMSH);
                              $this->salida.="<tr class=\"$estilo\">";
                              $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Detalle_Suministro', 'codigo_producto'.$pfj=>$vector1[$i]['codigo_producto'], 'producto'.$pfj=>$vector1[$i]['producto'], 'principio_activo'.$pfj=>$vector1[$i]['principio_activo']));
                              $this->salida.="<td colspan = 7 align=\"center\" width=\"63%\"><a href='".$accion."'><font color=\"#240000\">HISTORIAL (No. veces formulado: ".$registros_historial." --- Primera Formulacion: ".$this->FechaStamp($vectorMSH[0][fecha])." --- Ultima Formulacion: ".$this->FechaStamp($vectorMSH[$registros_historial-1][fecha]).")</font></a></td>";
                              $this->salida.="</tr>";
                         }
                    //fin del for muy importante
                    }
                    $this->salida.="</table><br>";
               }
               else
               {
                    $m = $m+1;
               }
               //**pintar los medicamentos finalizados y suspendidos
               $vectorMSF = $this->Consulta_Solicitud_Medicamentos_Finalizados_y_Suspendidos();
               if ($vectorMSF)
               {
                    for($i=0;$i<sizeof($vectorMSF);$i++)
                    {
                         if ($vectorMSF[$i][sw_estado] != $vectorMSF[$i-1][sw_estado] )
                         {
                              $this->salida.="<table  align=\"center\" border=\"1\"  width=\"80%\">";
                              $this->salida.="<tr class=\"modulo_table_title\">";
                              if ($vectorMSF[$i][sw_estado]=='2')
                              {
                                   $this->salida.="<td align=\"center\" colspan=\"5\">MEDICAMENTOS SUSPENDIDOS</td>";
                              }
                              elseif ($vectorMSF[$i][sw_estado]=='0')
                              {
                                   $this->salida.="<td align=\"center\" colspan=\"5\">MEDICAMENTOS FINALIZADOS</td>";
                              }
                              else
                              {
                                   $this->salida.="<td align=\"center\" colspan=\"5\">MEDICAMENTOS FINALIZADOS DESDE ESTACION</td>";
                              }
                              $this->salida.="</tr>";
     
                              $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                              $this->salida.="  <td width=\"10%\">CODIGO</td>";
                              $this->salida.="  <td width=\"28%\">PRODUCTO</td>";
                              $this->salida.="  <td width=\"28%\">PRINCIPIO ACTIVO</td>";
                              $this->salida.="  <td colspan= 2 width=\"14%\">OPCIONES</td>";
                              $this->salida.="</tr>";
                         }
     
                         if( $i % 2){ $estilo='modulo_list_claro';}
                              else {$estilo='modulo_list_oscuro';}
                              $this->salida.="<tr class=\"$estilo\">";
                              if($vectorMSF[$i][item] == 'NO POS')
                              {
                                   $this->salida.="  <td align=\"center\" width=\"10%\">".$vectorMSF[$i][codigo_producto]."<BR>NO_POS</td>";
                              }
                              else
                              {
                                   $this->salida.="  <td align=\"center\" width=\"10%\">".$vectorMSF[$i][codigo_producto]."</td>";
                              }
                              $this->salida.="  <td align=\"center\" width=\"28%\">".$vectorMSF[$i][producto]."-".$vectorMSF[$i][evolucion_id]."</td>";
                              $this->salida.="  <td align=\"left\" width=\"28%\">".$vectorMSF[$i][principio_activo]."</td>";
     
                              //DETALLE DE SUMINISTRO
                              $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Detalle_Suministro', 'codigo_producto'.$pfj=>$vectorMSF[$i]['codigo_producto'], 'producto'.$pfj=>$vectorMSF[$i]['producto'], 'principio_activo'.$pfj=>$vectorMSF[$i]['principio_activo']));
                              $this->salida .= "		<td align='center' width=\"7%\"><font color=\"#990000\"><a href='".$accion."'>Notas del Medicamento</a></font></td>\n";
                              //REENVIO
     
                              if ($vectorMSF[$i][sw_estado]=='2')
                              {
                                   if($_SESSION['PROFESIONAL'.$pfj]==1)
                                   {
                                        $this->salida.="  <td align=\"center\" width=\"7%\">";
                                        $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Activar_Medicamento_Medico', 'codigo_producto'.$pfj=>$vectorMSF[$i]['codigo_producto'], 'evolucion_id'.$pfj=>$vectorMSF[$i]['evolucion_id']));
                                        $this->salida .= "<a href='".$accion."'><font color='#063496'>Activar</font></a>\n";
                                        $accion2=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Finalizar_Medicamento', 'codigo_producto'.$pfj=>$vectorMSF[$i]['codigo_producto'], 'evolucion_id'.$pfj=>$vectorMSF[$i]['evolucion_id']));
                                        $this->salida .= "<br><a href='".$accion2."'><font color='#8C8030'>Finalizar</font></a>\n";
                                        $this->salida.="  </td>";
                                   }
                                   if($_SESSION['PROFESIONAL'.$pfj]==3)
                                   {
                                             $this->salida.="  <td align=\"center\" width=\"7%\">";
                                             $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Suspender_Medicamento', 'codigo_producto'.$pfj=>$vectorMSF[$i]['codigo_producto'], 'producto'.$pfj=>$vectorMSF[$i]['producto'],'principio_activo'.$pfj=>$vectorMSF[$i]['principio_activo'], 'evolucion_id'.$pfj=>$vectorMSF[$i]['evolucion_id'], 'tipo_nota'.$pfj=>'1'));
                                             $this->salida .= "<a href='".$accion."'><font color='#063496'>Activar</font></a>";
                                             $this->salida.="  </td>";
                                   }
                              }
                              else
                              {
                                   if ($_SESSION['PROFESIONAL'.$pfj]==1)
                                   {
                                        $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Medicamentos', 'codigo_producto'.$pfj=>$vectorMSF[$i]['codigo_producto']));
                                        $this->salida.="  <td align=\"center\" width=\"7%\"><a href='".$accion."'><font color='#8C8030'>Reenviar</font></a></td>";
                                   }
                                   elseif($_SESSION['PROFESIONAL'.$pfj]==3)
                                   {
                                        $this->salida.="  <td aalign=\"center\" width=\"7%\">Finalizado</td>";
                                   }
                              }
     
                              $this->salida.="</tr>";
     
                              if ($vectorMSF[$i][sw_estado] != $vectorMSF[$i+1][sw_estado] )
                              {
                                   $this->salida.="</table><br>";
                              }
                         }
     
                    }
                    
                    /*SE ACTIVARA CUANDO SEA REQUERIDA*/
                    
                    //**pintar los medicamentos finalizados
				/*$vectorMF = $this->Consulta_Solicitud_Medicamentos_Finalizados();
				if ($vectorMF)
				{
                         $this->salida.="<table  align=\"center\" border=\"1\"  width=\"80%\">";
                         $this->salida.="<tr class=\"modulo_table_title\">";

                         $this->salida.="<td align=\"center\" colspan=\"6\">MEDICAMENTOS FINALIZADOS</td>";

                         $this->salida.="</tr>";

                         $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                         $this->salida.="  <td width=\"10%\">CODIGO</td>";
                         $this->salida.="  <td width=\"28%\">PRODUCTO</td>";
                         $this->salida.="  <td width=\"28%\">PRINCIPIO ACTIVO</td>";
                         $this->salida.="  <td colspan= 2 width=\"14%\">OPCIONES</td>";
                         $this->salida.="  <td width=\"7%\">FECHA F.</td>";
                         $this->salida.="</tr>";
					
                         for($i=0;$i<sizeof($vectorMF);$i++)
					{
						$codigo = $this->verificar($vectorMF[$i][codigo_producto]);
  						if( $i % 2){ $estilo='modulo_list_claro';}
                              else {$estilo='modulo_list_oscuro';}
                              $this->salida.="<tr class=\"$estilo\">";
                              if($vectorMF[$i][item] == 'NO POS')
                              {
                                   $this->salida.="  <td align=\"center\" width=\"10%\">".$vectorMF[$i][codigo_producto]."<BR>NO_POS</td>";
                              }
                              else
                              {
                                   $this->salida.="  <td align=\"center\" width=\"10%\">".$vectorMF[$i][codigo_producto]."</td>";
                              }
                              $this->salida.="  <td align=\"center\" width=\"28%\">".$vectorMF[$i][producto]."-".$vectorMF[$i][evolucion_id]."</td>";
                              $this->salida.="  <td align=\"left\" width=\"28%\">".$vectorMF[$i][principio_activo]."</td>";

			              //DETALLE DE SUMINISTRO
                              $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Detalle_Suministro', 'codigo_producto'.$pfj=>$vectorMF[$i]['codigo_producto'], 'producto'.$pfj=>$vectorMF[$i]['producto'], 'principio_activo'.$pfj=>$vectorMF[$i]['principio_activo']));
                              $this->salida .= "		<td align='center' width=\"7%\"><font color=\"#990000\"><a href='".$accion."'>Notas del Medicamento</a></font></td>\n";
              				//REENVIO
                              if ($_SESSION['PROFESIONAL'.$pfj]==1)
                              {
                                   if($codigo != $vectorMF[$i][codigo_producto])
                                   {
                                        $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Medicamentos', 'codigo_producto'.$pfj=>$vectorMF[$i]['codigo_producto']));
                                        $this->salida.="  <td align=\"center\" width=\"7%\"><a href='".$accion."'><font color='#8C8030'>Reenviar</font></a></td>";
                                   }
                                   else
                                   {
                                        $this->salida.="  <td align=\"center\" width=\"7%\"><font color='#990000'>Reenviado</font></td>";
                                   }
                              }
                              elseif($_SESSION['PROFESIONAL'.$pfj]==3)
                              {
                                   if($codigo != $vectorMF[$i][codigo_producto])
                                   {
                                        $this->salida.="<td align=\"center\" width=\"7%\">Finalizado</td>";
                                   }
                                   else
                                   {
                                        $this->salida.="<td align=\"center\" width=\"7%\">Reenviado</td>";
                                   }
                              }
                              $fecha_f = $this->FechaStamp($vectorMF[$i][fecha_registro]);
                              $this->salida.="<td align=\"center\" width=\"7%\">".$fecha_f."</td>";
                              $this->salida.="</tr>";
	                    }
     	               $this->salida.="</table><br>";
                    }
                    else
                    {
                         $m = $m+1;
                    }

                    //**pintar los medicamentos suspendidos
				$vectorMS = $this->Consulta_Solicitud_Medicamentos_Suspendidos();
				if ($vectorMS)
				{
                         $this->salida.="<table  align=\"center\" border=\"1\"  width=\"80%\">";
                         $this->salida.="<tr class=\"modulo_table_title\">";
                              
                         $this->salida.="<td align=\"center\" colspan=\"6\">MEDICAMENTOS SUSPENDIDOS</td>";
                         
                         $this->salida.="</tr>";

                         $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                         $this->salida.="  <td width=\"10%\">CODIGO</td>";
                         $this->salida.="  <td width=\"28%\">PRODUCTO</td>";
                         $this->salida.="  <td width=\"28%\">PRINCIPIO ACTIVO</td>";
                         $this->salida.="  <td colspan= 2 width=\"14%\">OPCIONES</td>";
                         $this->salida.="  <td width=\"7%\">FECHA S.</td>";
                         $this->salida.="</tr>";

					for($i=0;$i<sizeof($vectorMS);$i++)
					{
						$codigo = $this->verificar($vectorMS[$i][codigo_producto]);
  						if( $i % 2){ $estilo='modulo_list_claro';}
                              else {$estilo='modulo_list_oscuro';}
                              $this->salida.="<tr class=\"$estilo\">";
                              if($vectorMS[$i][item] == 'NO POS')
                              {
                                   $this->salida.="  <td align=\"center\" width=\"10%\">".$vectorMS[$i][codigo_producto]."<BR>NO_POS</td>";
                              }
                              else
                              {
                                   $this->salida.="  <td align=\"center\" width=\"10%\">".$vectorMS[$i][codigo_producto]."</td>";
                              }
                              $this->salida.="  <td align=\"center\" width=\"28%\">".$vectorMS[$i][producto]."-".$vectorMS[$i][evolucion_id]."</td>";
                              $this->salida.="  <td align=\"left\" width=\"28%\">".$vectorMS[$i][principio_activo]."</td>";

			              //DETALLE DE SUMINISTRO
                              $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Detalle_Suministro', 'codigo_producto'.$pfj=>$vectorMS[$i]['codigo_producto'], 'producto'.$pfj=>$vectorMS[$i]['producto'], 'principio_activo'.$pfj=>$vectorMS[$i]['principio_activo']));
                              $this->salida .= "		<td align='center' width=\"7%\"><font color=\"#990000\"><a href='".$accion."'>Notas del Medicamento</a></font></td>\n";
              				//REENVIO

                              if($_SESSION['PROFESIONAL'.$pfj]==1)
                              {
                                   if($codigo != $vectorMS[$i][codigo_producto])
                                   {
                                        $this->salida.="  <td align=\"center\" width=\"7%\">";
                                        $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Activar_Medicamento_Medico', 'codigo_producto'.$pfj=>$vectorMS[$i]['codigo_producto'], 'evolucion_id'.$pfj=>$vectorMS[$i]['evolucion_id']));
                                        $this->salida .= "<a href='".$accion."'><font color='#063496'>Activar</font></a>\n";
                                        $accion2=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Finalizar_Medicamento', 'codigo_producto'.$pfj=>$vectorMS[$i]['codigo_producto'], 'evolucion_id'.$pfj=>$vectorMS[$i]['evolucion_id']));
                                        $this->salida .= "<br><a href='".$accion2."'><font color='#8C8030'>Finalizar</font></a>\n";
                                        $this->salida.="  </td>";
                                   }
                                   else
                                   {
                                        $this->salida.="  <td align=\"center\" width=\"7%\">";
                                        $this->salida .= "<font color='#990000'>Activo</font>\n";
                                        $this->salida.="  </td>";
                                   }
                              }
                              if($_SESSION['PROFESIONAL'.$pfj]==3)
                              {
                                   if($codigo != $vectorMS[$i][codigo_producto])
                                   {
                                        $this->salida.="  <td align=\"center\" width=\"7%\">";
                                        $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Suspender_Medicamento', 'codigo_producto'.$pfj=>$vectorMS[$i]['codigo_producto'], 'producto'.$pfj=>$vectorMS[$i]['producto'],'principio_activo'.$pfj=>$vectorMS[$i]['principio_activo'], 'evolucion_id'.$pfj=>$vectorMS[$i]['evolucion_id'], 'tipo_nota'.$pfj=>'1'));
                                        $this->salida .= "<a href='".$accion."'><font color='#063496'>Activar</font></a>";
                                        $this->salida.="  </td>";
                                   }
                                   else
                                   {
                                        $this->salida.="<td align=\"center\" width=\"7%\">";
                                        $this->salida.="<font color='#990000'>Activo</font>";
                                        $this->salida.="</td>";
                                   }
                              }
                              $fecha_s = $this->FechaStamp($vectorMS[$i][fecha_registro]);
                              $this->salida.="<td align=\"center\" width=\"7%\">".$fecha_s."</td>";
                         	$this->salida.="</tr>";
                    	}
                         $this->salida.="</table><br>";
				}*/
               else
               {
                    $m = $m+1;
               }

               //fin de mediacamentos finalizadops
               $this->salida .= "</form>";
               if ($_SESSION['PROFESIONAL'.$pfj]!=1)
               {
                    if($m==2)
                    {
                         $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
                         $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                         $this->salida.="  <td align=\"center\" width=\"7%\">EL PACIENTE NO TIENE MEDICAMENTOS FORMULADOS</td>";
                         $this->salida.="</tr>";
                         $this->salida.="</table><br>";
                    }
               }

          //los medicamentos frecuentes por diagnostico
          //este if es especial en hospitalizacion para que solo se ejecute cuando es medico y no enfermera
          if ($_SESSION['PROFESIONAL'.$pfj]==1)
          {
               $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'llenar_solicitud_medicamento'));
               $this->salida .= "<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
               $vectorMF = $this->Medicamentos_Frecuentes_Diagnostico();
               if ($vectorMF)
               {
                    $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
                    $this->salida.="<tr class=\"modulo_table_title\">";
                    $this->salida.="  <td align=\"center\" colspan=\"7\">MEDICAMENTOS EMPLEADOS PARA LOS DIAGNOSTICOS DE ESTA HISTORIA CLINICA</td>";
                    $this->salida.="</tr>";

                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida.="  <td width=\"5%\"></td>";
                    $this->salida.="  <td width=\"5%\">CODIGO</td>";
                    $this->salida.="  <td width=\"23%\">PRODUCTO</td>";
                    $this->salida.="  <td width=\"23%\">PRINCIPIO ACTIVO</td>";
                    if ($this->bodega==='')
                    {
                         $this->salida.="  <td colspan = 2 width=\"15%\">FORMA</td>";
                    }
                    else
                    {
                         $this->salida.="  <td width=\"15%\">FORMA</td>";
                         $this->salida.="  <td width=\"5%\">EXISTENCIA</td>";
                    }
                    $this->salida.="  <td width=\"4%\">OPCION</td>";
                    $this->salida.="</tr>";
                    for($i=0;$i<sizeof($vectorMF);$i++)
                    {
                         if( $i % 2){ $estilo='modulo_list_claro';}
                         else {$estilo='modulo_list_oscuro';}
                         $this->salida.="<tr class=\"$estilo\">";
                         $this->salida.="  <td align=\"center\" width=\"5%\">".$vectorMF[$i][item]."</td>";
                         $this->salida.="  <td align=\"center\" width=\"5%\">".$vectorMF[$i][codigo_producto]."</td>";
                         $this->salida.="  <td align=\"left\" width=\"20%\">".$vectorMF[$i][producto]."</td>";
                         $this->salida.="  <td align=\"left\" width=\"20%\">".$vectorMF[$i][principio_activo]."</td>";

                         if ($this->bodega==='')
                         {
                              $this->salida.="  <td colspan = 2 align=\"center\" width=\"15%\">".$vectorMF[$i][forma]."</td>";
                         }
                         else
                         {
                              $this->salida.="  <td align=\"center\" width=\"15%\">".$vectorMF[$i][forma]."</td>";
                              if(!empty($vectorMF[$i][existencia]))
                              {
                                        $this->salida.="  <td align=\"center\" width=\"5%\">".$vectorMF[$i][existencia]."</td>";
                              }
                              else
                              {
                                        $this->salida.="  <td align=\"center\" width=\"5%\">--</td>";
                              }
                              //$this->salida.="  <td align=\"center\" width=\"5%\"><input type = checkbox name= 'opE".$pfj."[$i]' value = ".$cargo.",".$vectorE[$i][especialidad]."></td>";
                         }
                         $this->salida.="  <td align=\"center\" width=\"5%\"><input type = radio name= 'opE$pfj' value = '".$vectorMF[$i][item].",".$vectorMF[$i][codigo_producto].",".$vectorMF[$i][producto].",".$vectorMF[$i][principio_activo].",".$vectorE[$i][concentracion_forma_farmacologica].",".$vectorE[$i][unidad_medida_medicamento_id].",".$vectorE[$i][forma].",".$vectorE[$i][cod_forma_farmacologica]."'></td>";
                         $this->salida.="</tr>";
                    }
                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida .= "<td align=\"right\" colspan=\"7\"><input class=\"input-submit\" name=\"guardar$pfj\" type=\"submit\" value=\"FORMULAR\"></td>";
                    $this->salida.="</tr>";
                    $this->salida.="</table><br>";
               }
               $this->salida .= "</form>";
          }
          //fin de medicamentos MAS FRECUENTES POR DIAGNMOSTICO

		//este if es especial en hospitalizacion para que solo se ejecute cuando es medico y no enfermera
          if ($_SESSION['PROFESIONAL'.$pfj]==1)
          {
               //lo que inserte
                    $accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Medicamentos',
               'Of'.$pfj=>$_REQUEST['Of'.$pfj],'paso1'=>$_REQUEST['paso1'.$pfj],
               'criterio1'.$pfj=>$_REQUEST['criterio1'.$pfj],
               'producto'.$pfj=>$_REQUEST['producto'.$pfj],
               'principio_activo'.$pfj=>$_REQUEST['principio_activo'.$pfj]));
     
               $this->salida .= "<form name=\"formades$pfj\" action=\"$accion1\" method=\"post\">";
               $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
               $this->salida.="<tr class=\"modulo_table_title\">";
               $this->salida.="  <td align=\"center\" colspan=\"7\">ADICION DE MEDICAMENTOS - BUSQUEDA AVANZADA </td>";
               $this->salida.="</tr>";
     
               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="<td width=\"5%\">TIPO</td>";
     
               $this->salida.="<td width=\"10%\" align = left >";
               $this->salida.="<select size = 1 name = 'criterio1$pfj'  class =\"select\">";
               $this->salida.="<option value = '001' selected>Todos</option>";
               if (($_REQUEST['criterio1'.$pfj])  == '002')
               {
                    $this->salida.="<option value = '002' selected>Frecuentes</option>";
               }
               else
               {
                    $this->salida.="<option value = '002' >Frecuentes</option>";
               }
               $this->salida.="</select>";
               $this->salida.="</td>";
     
               $this->salida.="<td width=\"7%\">PRODUCTO:</td>";
               $this->salida .="<td width=\"23%\" align='center'><input type='text' class='input-text'  size = 22 name = 'producto$pfj'  value =\"".$_REQUEST['producto'.$pfj]."\"    ></td>" ;
     
               $this->salida.="<td width=\"8%\">PRINCIPIO ACTIVO:</td>";
               $this->salida .="<td width=\"22%\" align='center' ><input type='text' class='input-text' size = 22 name = 'principio_activo$pfj'   value =\"".$_REQUEST['principio_activo'.$pfj]."\"        ></td>" ;
     
               $this->salida .= "<td  width=\"5%\" align=\"center\"><input class=\"input-submit\" name=\"buscar$pfj\" type=\"submit\" value=\"BUSCAR\"></td>";
               $this->salida.="</tr>";
               $this->salida.="</table><br>";
     
     
               $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
               $this->salida .= $this->SetStyle("MensajeError");
               $this->salida.="</table>";
               $this->salida.="</form>";
	          //hasta aqui lo que inserte
          }
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}

	
     //FUNCIONES UTILIZADAS PARA MEDICAMENTOS EN HOSPITALIZACION - CLAUDIA
     function pintar_historial($vector1)
     {
          $pfj=$this->frmPrefijo;
          if ($vector1)
          {
			for($i=0;$i<sizeof($vector1);$i++)
			{
                    if( $i % 2){ $estilo='modulo_list_claro';}
                    else {$estilo='modulo_list_oscuro';}
                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="  <td align=\"left\" width=\"18%\">EVOLUCION: ".$vector1[$i][evolucion_id]."</td>";
                    $this->salida.="  <td align=\"left\" width=\"28%\">FECHA: ".$this->FechaStamp($vector1[$i][fecha])." - ".$this->HoraStamp($vector1[$i][fecha])."</td>";
                    $this->salida.="  <td align=\"center\" colspan = 4 width=\"34%\">MED. ".$vector1[$i][nombre]."</td>";
                    $this->salida.="</tr>";

                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="<td colspan = 6>";
                    $this->salida.="<table>";

                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="  <td align=\"left\" width=\"9%\">Dosis:</td>";
                    $e=$vector1[$i][dosis]/floor($vector1[$i][dosis]);
                    if($e==1)
                    {
                         $this->salida.="  <td align=\"left\" width=\"14%\">".floor($vector1[$i][dosis])."  ".$vector1[$i][unidad_dosificacion]."</td>";
                    }
                    else
                    {
                         $this->salida.="  <td align=\"left\" width=\"14%\">".$vector1[$i][dosis]."  ".$vector1[$i][unidad_dosificacion]."</td>";
                    }

                    $vector_posologia= $this->Consulta_Solicitud_Medicamentos_Posologia($vector1[$i][codigo_producto], $vector1[$i][tipo_opcion_posologia_id], $vector1[$i][evolucion_id]);

//pintar formula para opcion 1
                    if($vector1[$i][tipo_opcion_posologia_id]== 1)
                    {
                         $this->salida.="  <td align=\"left\" width=\"50%\">cada ".$vector_posologia[0][periocidad_id]." ".$vector_posologia[0][tiempo]."</td>";
                    }

//pintar formula para opcion 2
                    if($vector1[$i][tipo_opcion_posologia_id]== 2)
                    {
                         $this->salida.="  <td align=\"left\" width=\"50%\">".$vector_posologia[0][descripcion]."</td>";
                    }

//pintar formula para opcion 3
                    if($vector1[$i][tipo_opcion_posologia_id]== 3)
                    {
                         $momento = '';
                         if($vector_posologia[0][sw_estado_momento]== '1')
                         {
                              $momento = 'antes de ';
                         }
                         else
                         {
                              if($vector_posologia[0][sw_estado_momento]== '2')
                              {
                                   $momento = 'durante ';
                              }
                              else
                              {
                                   if($vector_posologia[0][sw_estado_momento]== '3')
                                        {
                                             $momento = 'despues de ';
                                        }
                              }
                         }
                         $Cen = $Alm = $Des= '';
                         $cont= 0;
                         $conector = '  ';
                         $conector1 = '  ';
                         if($vector_posologia[0][sw_estado_desayuno]== '1')
                         {
                              $Des = $momento.'el Desayuno';
                              $cont++;
                         }
                         if($vector_posologia[0][sw_estado_almuerzo]== '1')
                         {
                              $Alm = $momento.'el Almuerzo';
                              $cont++;
                         }
                         if($vector_posologia[0][sw_estado_cena]== '1')
                         {
                              $Cen = $momento.'la Cena';
                              $cont++;
                         }
                         if ($cont== 2)
                         {
                              $conector = ' y ';
                              $conector1 = '  ';
                         }
                         if ($cont== 1)
                         {
                              $conector = '  ';
                              $conector1 = '  ';
                         }
                         if ($cont== 3)
                         {
                              $conector = ' , ';
                              $conector1 = ' y ';
                         }
                         $this->salida.="  <td align=\"left\" width=\"50%\">".$Des."".$conector."".$Alm."".$conector1."".$Cen."</td>";
                    }

//pintar formula para opcion 4
                    if($vector1[$i][tipo_opcion_posologia_id]== 4)
                    {
                         $conector = '  ';
                         $frecuencia='';
                         $j=0;
                         foreach ($vector_posologia as $k => $v)
                         {
                              if ($j+1 ==sizeof($vector_posologia))
                              {
                                   $conector = '  ';
                              }
                              else
                              {
                                        if ($j+2 ==sizeof($vector_posologia))
                                             {
                                                  $conector = ' y ';
                                             }
                                        else
                                             {
                                                  $conector = ' - ';
                                             }
                              }
                              $frecuencia = $frecuencia.$k.$conector;
                              $j++;
                         }
                         $this->salida.="  <td align=\"left\" width=\"50%\">a la(s): $frecuencia</td>";
                    }

//pintar formula para opcion 5
                    if($vector1[$i][tipo_opcion_posologia_id]== 5)
                    {
                         $this->salida.="  <td align=\"left\" width=\"50%\">".$vector_posologia[0][frecuencia_suministro]."</td>";
                    }
                    $this->salida.="</tr>";

                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="  <td align=\"left\" width=\"9%\">Cantidad:</td>";
                    $e=$vector1[$i][cantidad]/floor($vector1[$i][cantidad]);
                    if ($vector1[$i][contenido_unidad_venta])
                    {
                         if($e==1)
                         {
                              $this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".floor($vector1[$i][cantidad])." ".$vector1[$i][descripcion]." por ".$vector1[$i][contenido_unidad_venta]."</td>";
                         }
                         else
                         {
                              $this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".$vector1[$i][cantidad]." ".$vector1[$i][descripcion]." por ".$vector1[$i][contenido_unidad_venta]."</td>";
                         }
                    }
                    else
                    {
                         if($e==1)
                         {
                              $this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".floor($vector1[$i][cantidad])." ".$vector1[$i][descripcion]."</td>";
                         }
                         else
                         {
                              $this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".$vector1[$i][cantidad]." ".$vector1[$i][descripcion]."</td>";
                         }
                    }
                    $this->salida.="</tr>";

                    $this->salida.="</table>";
                    $this->salida.="</td>";
                    $this->salida.="</tr>";


                    if($vector1[$i][item] == 'NO POS')
                    {
                         $this->salida.="<tr class=\"$estilo\">";
                         if($vector1[$i][sw_paciente_no_pos] != '1')
                         {
                              if($vector1[$i][evolucion_id] == $this->evolucion)
                              {
                                        $accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Consultar_Justificacion', 'codigo_p'.$pfj => $vector1[$i][codigo_producto], 'product'.$pfj => $vector1[$i][producto], 'principio_a'.$pfj => $vector1[$i][principio_activo], 'via'.$pfj => $vector1[$i][via],'dosis'.$pfj => $vector1[$i][dosis], 'unidad'.$pfj => $vector1[$i][unidad_dosificacion], 'canti'.$pfj => $vector1[$i][cantidad],'desc'.$pfj => $vector1[$i][descripcion],'contenido_u_v'.$pfj => $vector1[$i][contenido_unidad_venta], 'obs'.$pfj => $vector1[$i][observacion], 'evolucion'.$pfj => $vector1[$i][evolucion_id]));
                                        $this->salida.="  <td colspan = 6 align=\"center\" width=\"63%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/auditoria.png\" border='0'> VER JUSTIFICACION</a></td>";
                              }
                              else
                              {
                                        $accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Consultar_Justificacion', 'codigo_p'.$pfj => $vector1[$i][codigo_producto], 'product'.$pfj => $vector1[$i][producto], 'principio_a'.$pfj => $vector1[$i][principio_activo], 'via'.$pfj => $vector1[$i][via],'dosis'.$pfj => $vector1[$i][dosis], 'unidad'.$pfj => $vector1[$i][unidad_dosificacion], 'canti'.$pfj => $vector1[$i][cantidad],'desc'.$pfj => $vector1[$i][descripcion],'contenido_u_v'.$pfj => $vector1[$i][contenido_unidad_venta], 'obs'.$pfj => $vector1[$i][observacion],'evolucion'.$pfj => $vector1[$i][evolucion_id],'consultar_just'.$pfj => 1));
                                        $this->salida.="  <td colspan = 6 align=\"center\" width=\"63%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/auditoria.png\" border='0'> CONSULTAR JUSTIFICACION</a></td>";
                              }
                         }
                         else
                         {
                              $this->salida.="  <td class = label_error colspan = 6 align=\"center\" width=\"63%\">MEDICAMENTO NO POS FORMULADO A PETICION DEL PACIENTE</td>";
                         }
                         $this->salida.="</tr>";
                    }
                    //fin del if alterado
               }
               //fin del for muy importante
		}
	}

     function Nota_Suspension_Medicamento()
     {
		$pfj=$this->frmPrefijo;
		if ($_REQUEST['tipo_nota'.$pfj]=='1')
		{
          	$this->salida= ThemeAbrirTablaSubModulo('ACTIVAR MEDICAMENTO');
		}
		elseif($_REQUEST['tipo_nota'.$pfj]=='2')
		{
        		$this->salida= ThemeAbrirTablaSubModulo('SUSPENDER MEDICAMENTO');
		}
          $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'InsertarSuspensionMedicamento','tipo_nota'.$pfj=>$_REQUEST['tipo_nota'.$pfj], 'codigo_producto'.$pfj=>$_REQUEST['codigo_producto'.$pfj], 'evolucion_id'.$pfj=>$_REQUEST['evolucion_id'.$pfj], 'producto'.$pfj=>$_REQUEST['producto'.$pfj], 'principio_activo'.$pfj=>$_REQUEST['principio_activo'.$pfj]));
          $this->salida .= "<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
          $this->salida .= $this->SetStyle("MensajeError");
          $this->salida.="</table>";

          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
          $this->salida.="<tr class=\"modulo_table_title\">";
          if ($_REQUEST['tipo_nota'.$pfj]=='1')
          {
               $this->salida.="  <td align=\"center\" colspan=\"3\">NOTA DE ACTIVACION DEL MEDICAMENTO</td>";
          }
          elseif($_REQUEST['tipo_nota'.$pfj]=='2')
          {
	          $this->salida.="  <td align=\"center\" colspan=\"3\">NOTA DE SUSPENSION DEL MEDICAMENTO</td>";
          }
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
          $this->salida.="  <td width=\"15%\">CARGO</td>";
          $this->salida.="  <td width=\"30%\">PRODUCTO</td>";
          $this->salida.="  <td width=\"35%\">PRINCIPIO ACTIVO</td>";
          $this->salida.="</tr>";

          if( $i % 2){ $estilo='modulo_list_claro';}
          else {$estilo='modulo_list_oscuro';}
          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="  <td align=\"center\" width=\"15%\">".$_REQUEST['codigo_producto'.$pfj]."</td>";
          $this->salida.="  <td align=\"left\" width=\"30%\">".$_REQUEST['producto'.$pfj]."</td>";
          $this->salida.="  <td align=\"left\" width=\"35%\">".$_REQUEST['principio_activo'.$pfj]."</td>";
          $this->salida.="</tr>";
          $this->salida.="<tr class=\"$estilo\">";
          if ($_REQUEST['tipo_nota'.$pfj]=='1')
          {
               $this->salida.="  <td align=\"center\" width=\"15%\">NOTA DE ACTIVACION</td>";
          }
          elseif($_REQUEST['tipo_nota'.$pfj]=='2')
          {
               $this->salida.="  <td align=\"center\" width=\"15%\">NOTA DE SUSPENSION</td>";
          }

          $this->salida .="<td colspan=\"2\" width=\"65%\" align='center'><textarea class='textarea' name = 'nota_suspension_medicamento$pfj' cols = 100 rows = 3>".$_REQUEST['nota_suspension_medicamento'.$pfj]."</textarea></td>" ;
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"$estilo\">";
          $this->salida .= "<td align=\"center\" colspan=\"3\"><input class=\"input-submit\" name=\"guardar$pfj\" type=\"submit\" value=\"GUARDAR\"></td>";
          $this->salida.="</tr>";
          $this->salida.="</table><br>";
     	$this->salida .= "</form>";

          //BOTON DEVOLVER
          $accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
          $this->salida .= "<form name=\"forma\" action=\"$accionV\" method=\"post\">";
          $this->salida .= "<tr><td  colspan = 6 align=\"center\"><br><input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\"></form></td></tr>";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}


     function Detalle_Suministro()
     {
		$pfj=$this->frmPrefijo;
          $this->salida= ThemeAbrirTablaSubModulo('NOTAS DEL MEDICAMENTO');
          //$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'InsertarSuspensionMedicamento','tipo_nota'.$pfj=>$_REQUEST['tipo_nota'.$pfj], 'codigo_producto'.$pfj=>$_REQUEST['codigo_producto'.$pfj], 'evolucion_id'.$pfj=>$_REQUEST['evolucion_id'.$pfj], 'producto'.$pfj=>$_REQUEST['producto'.$pfj], 'principio_activo'.$pfj=>$_REQUEST['principio_activo'.$pfj]));
          $this->salida .= "<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";

          if( $i % 2){ $estilo='modulo_list_claro';}
          else {$estilo='modulo_list_oscuro';}
	    //DOSIFICACIONES ANTERIORES EN EL DETALLE DEL SUMINISTRO
		$this->salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="<td align=\"center\" colspan=\"3\">MEDICAMENTO REFERIDO</td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="  <td width=\"10%\">CODIGO</td>";
		$this->salida.="  <td width=\"35%\">PRODUCTO</td>";
		$this->salida.="  <td width=\"35%\">PRINCIPIO ACTIVO</td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class=\"$estilo\">";
		$this->salida.="  <td width=\"10%\">".$_REQUEST['codigo_producto'.$pfj]."</td>";
		$this->salida.="  <td width=\"35%\">".$_REQUEST['producto'.$pfj]."</td>";
		$this->salida.="  <td width=\"35%\">".$_REQUEST['principio_activo'.$pfj]."</td>";
		$this->salida.="</tr>";
     	$vectorMSH = $this->Consulta_Solicitud_Medicamentos_Historial($_REQUEST['codigo_producto'.$pfj]);
		if ($vectorMSH)
		{
			$this->salida.="<tr class=\"$estilo\">";
			$this->salida.="<td colspan = 3 align=\"center\" width=\"63%\">";
			$this->salida.="<table>";

			$this->salida.="<tr>";
			$this->salida.="<td>";
			$this->pintar_historial($vectorMSH);
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="</table>";
			$this->salida.="</td>";
			$this->salida.="</tr>";
		}
          $this->salida.="</table><br>";
		//FIN DOSIFICACIONES ANTERIORES EN EL DETALLE DEL SUMINISTRO

		$notas = $this->Consultar_Notas_Suministro($_REQUEST['codigo_producto'.$pfj]);
		$this->salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"5\">NOTAS DE ESTADO DE MEDICAMENTOS</td>";
		$this->salida.="</tr>";
		if($notas)
		{
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td width=\"5%\">NOTA_ID</td>";
			$this->salida.="  <td width=\"10%\">TIPO DE NOTA</td>";
			$this->salida.="  <td width=\"10%\">FECHA</td>";
			$this->salida.="  <td width=\"15%\">USUARIO</td>";
			$this->salida.="  <td width=\"40%\">NOTA DE ESTADO</td>";
			$this->salida.="</tr>";
			for($i=0;$i<sizeof($notas);$i++)
			{
				if( $i % 2){ $estilo='modulo_list_claro';}
				else {$estilo='modulo_list_oscuro';}
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="  <td align=\"center\" width=\"5%\">".$notas[$i][hc_nota_suministro_id]."</td>";
				if($notas[$i][tipo_observacion]=='1')
				{
					$this->salida.="  <td align=\"center\" width=\"10%\">NOTA DE ACTIVACION</td>";
				}
				elseif($notas[$i][tipo_observacion]=='2')
				{
					$this->salida.="  <td align=\"center\" width=\"10%\">NOTA DE SUSPENSION</td>";
				}
                    elseif($notas[$i][tipo_observacion]=='3')
				{
					$this->salida.="  <td align=\"center\" width=\"10%\">NOTA DE PROCEDIMIENTO</td>";
				}

				$this->salida.="  <td align=\"left\" width=\"10%\">".$this->FechaStamp($notas[$i][fecha_registro_nota])." - ".$this->HoraStamp($notas[$i][fecha_registro_nota])."</td>";
                    if ($notas[$i][nombre]!=NULL)
				{
                         $this->salida.="  <td align=\"left\" width=\"15%\">".$notas[$i][nombre]."</td>";
				}
				else
				{
			          $this->salida.="  <td align=\"left\" width=\"15%\">".$notas[$i][nombre_usuario]."</td>";
				}

				$this->salida.="  <td align=\"left\" width=\"40%\">".$notas[$i][observacion]."</td>";
				$this->salida.="</tr>";
			}
		}
		else
		{
               $this->salida.="<tr class=\"modulo_list_claro\">";
               $this->salida.="<td align=\"center\" colspan=\"5\" class='label_mark'>ESTE MEDICAMENTO AUN NO PRESENTA NOTAS</td>";
               $this->salida.="</tr>";
		}
		$this->salida.="</table><br>";

          $this->salida .= "</form>";

          //PINTAR DOSIS SUMINISTRADAS DE MEDICAMENTOS FINALIZADOS O SUSPENDIDOS
          $this->Dosis_Suministradas($notas,5);
          //FIN PINTAR DOSIS SUMINISTRADAS DE MEDICAMENTOS FINALIZADOS O SUSPENDIDOS
	
          //BOTON DEVOLVER
		$accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
          $this->salida .= "<form name=\"forma\" action=\"$accionV\" method=\"post\">";
          $this->salida .= "<tr><td  colspan = 6 align=\"center\"><br><input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\"></form></td></tr>";

		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}

     function Control_Suministro($vectorI)
     {
          $pfj=$this->frmPrefijo;
          $this->salida= ThemeAbrirTablaSubModulo('CONTROL DE SUMINISTRO DEL MEDICAMENTO');
          $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'InsertarControlSuministro', 'codigo_producto'.$pfj=>$_SESSION['CABECERA_CONTROL'.$pfj][codigo_producto], 'evolucion_id'.$pfj=>$_SESSION['CABECERA_CONTROL'.$pfj][evolucion]));
          $this->salida .= "<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
          $this->salida.="<tr class=\"modulo_table_title\">";
          $this->salida.="<td align=\"left\" colspan=\"7\">CONTROL DEL MEDICAMENTO:</td>";
          $this->salida.="</tr>";
          $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
          $this->salida.="  <td align=\"center\" width=\"7%\">CODIGO</td>";
          $this->salida.="  <td align=\"center\" width=\"30%\">PRODUCTO</td>";
          $this->salida.="  <td align=\"center\" width=\"29%\">PRINCIPIO ACTIVO</td>";
          $this->salida.="  <td align=\"center\" colspan= 4 width=\"14%\">CANTIDAD</td>";
          $this->salida.="</tr>";
          $this->salida.="<tr class='modulo_list_claro'>";
          $this->salida.="  <td align=\"center\" width=\"7%\">".$_SESSION['CABECERA_CONTROL'.$pfj][codigo_producto]."</td>";
          $this->salida.="  <td align=\"center\" width=\"30%\">".$_SESSION['CABECERA_CONTROL'.$pfj][producto]."</td>";
          $this->salida.="  <td align=\"center\" width=\"29%\">".$_SESSION['CABECERA_CONTROL'.$pfj][principio_activo]."</td>";
          $this->salida.="  <td align=\"center\" colspan= 4 width=\"14%\">".$_SESSION['CABECERA_CONTROL'.$pfj][cantidad]." ".$_SESSION['CABECERA_CONTROL'.$pfj][descripcion]." ".$_SESSION['CABECERA_CONTROL'.$pfj][contenido_unidad_venta]."</td>";
          $this->salida.="</tr>";
          $this->salida.="</table><br>";
          
          $control = $this->Consultar_Control_Suministro($_SESSION['CABECERA_CONTROL'.$pfj][codigo_producto], $_SESSION['CABECERA_CONTROL'.$pfj][evolucion]);
          if($control){
               $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
               $this->salida.="<tr class=\"modulo_table_title\">";
               $this->salida.="  <td align=\"center\" colspan=\"5\">DOSIS SUMINISTRADAS</td>";
               $this->salida.="</tr>";
               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="  <td width=\"5%\">FECHA REALIZADO</td>";
               $this->salida.="  <td width=\"5%\">HORA REALIZADO</td>";
               $this->salida.="  <td width=\"15%\">CANTIDAD</td>";
               $this->salida.="  <td width=\"20%\">USUARIO</td>";
               $this->salida.="  <td width=\"35%\">OBSERVACION DEL SUMINISTRO</td>";
               $this->salida.="</tr>";
               $total_suministro=0;
               for($i=0;$i<sizeof($control);$i++){
                    if( $i % 2){ $estilo='modulo_list_claro';}
                    else {$estilo='modulo_list_oscuro';}
                    $this->salida.="<tr class=\"$estilo\">";
                    //$this->salida.="  <td align=\"center\" width=\"5%\">".$control[$i][hc_control_suministro_id]."</td>";
                    $this->salida.="  <td align=\"left\" width=\"5%\">".$this->FechaStamp($control[$i][fecha_realizado])."</td>";
                    $this->salida.="  <td align=\"left\" width=\"5%\">".$this->HoraStamp($control[$i][fecha_realizado])."</td>";
                    $this->salida.="  <td align=\"center\" width=\"15%\">".$control[$i][cantidad_suministrada]."&nbsp;".$_SESSION['CABECERA_CONTROL'.$pfj][unidad_dosificacion]."</td>";
                    if ($control[$i][nombre] != NULL)
                    {
                         $this->salida.="  <td align=\"left\" width=\"20%\">".$control[$i][nombre]."</td>";
                    }
                    else
                    {
                         $this->salida.="  <td align=\"left\" width=\"20%\">".$control[$i][nombre_usuario]."</td>";
                    }
                    $this->salida.="  <td align=\"left\" width=\"35%\">".$control[$i][observacion]."</td>";
                    $this->salida.="</tr>";
                    $cantidad_suministrada = $control[$i][cantidad_suministrada];
                    $total_suministro = $total_suministro + $cantidad_suministrada;
               }
               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="  <td colspan = 2 width=\"10%\">TOTAL SUMINISTRADO</td>";
               $this->salida.="  <td width=\"15%\">".$total_suministro."&nbsp;".$_SESSION['CABECERA_CONTROL'.$pfj][unidad_dosificacion]."</td>";
               $this->salida.="  <td colspan = 2 width=\"55%\">&nbsp;</td>";
               $this->salida.="</tr>";
               $this->salida.="</table><br>";
          }
          if($total_suministro < $_SESSION['CABECERA_CONTROL'.$pfj][cantidad]){
               if( $i % 2){ $estilo='modulo_list_claro';}
               else {$estilo='modulo_list_oscuro';}

               $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
               $this->salida .= $this->SetStyle("MensajeError");
               $this->salida.="</table>";

               $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
               $this->salida.="<input type=\"hidden\" name=\"total_suministro\" value=\"$total_suministro\">";
               $this->salida.="<tr class=\"modulo_table_title\">";
               $this->salida.="<td align=\"center\" colspan=\"4\">INGRESAR SUMINISTRO</td>";
               $this->salida.="</tr>";

               $this->salida.="<tr class='modulo_list_claro'>";
               $this->salida.="<td colspan=\"1\" align=\"left\"   width=\"15%\">HORA DEL SUMINISTRO:</td>";
               $this->salida.="<td colspan=\"1\" align=\"center\" width=\"30%\">";

	     		//EL SELECT DE LA HORA DE ARLEY
                    $hora_inicio_turno = ModuloGetVar('app','EstacionEnfermeria','HoraInicioTurnoControles');
                    $rango_turno = ModuloGetVar('app','EstacionEnfermeria','RangoVerTurnosControles');
                    if(date("H:i:s") >= $hora_inicio_turno)
                    {
                         list($fecha_control,$hora_control)=explode(" ",date("Y-m-d H:i:s"));
                         list($h,$m,$s)=explode(":",$hora_control);
                    }
                    else
                    {//si la hora actual es menor que la de inicio turno, se debe poner la fecha anterior
                         list($fecha_control,$hora_control)=explode(" ",date("Y-m-d H:i:s",mktime(date("h"),date("i"),date("s"),date("m"),(date("d")-1),date("Y"))));
                         list($h,$m,$s)=explode(":",$hora_control);
                    }


                    $i=0;
                    $this->salida .= "<select name=\"selectHora$pfj\" class=\"select\">\n";
                    for($j=0; $j<$rango_turno; $j++)
                    {
                         list($anno, $mes, $dia)=explode("-",$fecha_control);
                         if ($i==23)
                         {
                              list($h,$m,$s)=explode(":",$hora_inicio_turno);
                              $i=date("H",mktime($h+$j,$m,$s,$mes,$dia,$anno));
                              $fecha2=date("Y-m-d H:i:s",mktime(24,0,0,$mes,$dia,$anno));
                              $fecha_control=date("Y-m-d",mktime(24,0,0,$mes,$dia,$anno));
                         }
                         else
                         {
                              list($h,$m,$s)=explode(":",$hora_inicio_turno);
                              $i=date("H",mktime($h+$j,$m,$s,$mes,$dia,$anno));
                              $fecha2=date("Y-m-d H:i:s",mktime($i,0,0,$mes,$dia,$anno));
                              $fecha_control=date("Y-m-d",mktime($i,0,0,$mes,$dia,$anno));
                         }
                         if(empty($selectHora)){
                              if($i == date("H")){ $selected = "selected='true'";} else { $selected = "";}
                         }
                         else
                         {//viene de insertar pero no lo hizo, asi que pongo por defecto los valores enviados
                              list($A,$B) = explode(" ",$selectHora);
                              if($i == $B){ $selected = "selected='true'";} else { $selected = "";}
                         }
                         #################################################
                         list($yy,$mm,$dd)=explode(" ",$fecha_control);//(date("m"),(date("d")),date("Y")));
                         if($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")),date("Y"))))){
                              $show = "Hoy a las";
                         }
                         elseif($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")+1),date("Y"))))){
                              $show = "MaÃ±ana a las";
                         }
                         elseif($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")-1),date("Y"))))){
                              $show = "Ayer a las";
                         }
                         else{
                              $show = $fecha_control;
                         }
                         ###########################
                         $this->salida .="<option value='".$fecha_control." ".$i."' $selected>".$show." ".$i."</option>\n";
                    }//fin for
                    $this->salida .= "</select>:&nbsp;\n";
                    $this->salida .= "<select name=\"selectMinutos$pfj\" class=\"select\">\n";

                    for($j=0; $j<=59; $j++)
                    {
                         if(empty($selectMinutos)){
                              if($j == date("i")){ $selected = "selected='true'";} else { $selected = "";}
                         }
                         else
                         {//viene de insertar pero no lo hizo, asi que pongo por defecto los valores enviados
                              list($A,$B) = explode(" ",$selectMinutos);
                              if($j == $A){ $selected = "selected='true'";} else { $selected = "";}
                         }
                         if ($j<10){
                              $this->salida .= "<option value='0$j:00' $selected>0$j</option>\n";
                         }
                              else{
                              $this->salida .= "<option value='$j:00' $selected>$j</option>\n";
                         }
                    }
                    $this->salida .= "</select>\n";

          //FIN
                    $this->salida.="</td>" ;
                    $this->salida.="<td colspan=\"1\" align=\"left\"   width=\"15%\" class=".$this->SetStyle("cantidad_suministrada").">CANTIDAD</td>";
                    if($_REQUEST['cantidad_suministrada'.$pfj]=='')
                    {
                         $this->salida.="<td colspan=\"1\" align=\"center\" width=\"20%\"><input type='text' class='input-text' size = 5 name = 'cantidad_suministrada$pfj'   value =\"".$_SESSION['CABECERA_CONTROL'.$pfj][dosis]."\">&nbsp; ".$_SESSION['CABECERA_CONTROL'.$pfj][unidad_dosificacion]."</td>" ;
                    }
                    else
                    {
                         $this->salida.="<td colspan=\"1\" align=\"center\" width=\"20%\"><input type='text' class='input-text' size = 5 name = 'cantidad_suministrada$pfj'   value =\"".$_REQUEST['cantidad_suministrada'.$pfj]."\">&nbsp; ".$_SESSION['CABECERA_CONTROL'.$pfj][unidad_dosificacion]."</td>" ;
                    }
                    $this->salida.="</tr>";
                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="<td align=\"center\" width=\"15%\">OBSERVACION DE SUMINISTRO</td>";
                    $this->salida.="<td colspan=\"3\" width=\"65%\" align='center'><textarea class='textarea' name = 'observacion_suministro$pfj' cols = 100 rows = 3>".$_REQUEST['observacion_suministro'.$pfj]."</textarea></td>" ;
                    $this->salida.="</tr>";
     
                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="<td align=\"center\" colspan=\"4\"><input class=\"input-submit\" name=\"guardar$pfj\" type=\"submit\" value=\"GUARDAR\"></td>";
                    $this->salida.="</tr>";
     
                    $this->salida.="</table><br>";
               }
	          $this->salida.="</form>";
     
     //bnusqueda de insumos
     //nueva forma
     /*
          $accionB=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Insumos',
                    'Of'.$pfj=>$_REQUEST['Of'.$pfj],'paso1'=>$_REQUEST['paso1'.$pfj],
                    'criterio1'.$pfj=>$_REQUEST['criterio1'.$pfj],
                    'codigo_insumo'.$pfj=>$_REQUEST['codigo_insumo'.$pfj],
                    'insumo'.$pfj=>$_REQUEST['insumo'.$pfj]));
     
                    $this->salida .= "<form name=\"formades$pfj\" action=\"$accionB\" method=\"post\">";
                    $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
                    $this->salida.="<tr class=\"modulo_table_title\">";
                    $this->salida.="  <td align=\"center\" colspan=\"7\">BUSQUEDA AVANZADA DE INSUMOS</td>";
                    $this->salida.="</tr>";
     
                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida.="<td  width=\"5%\">TIPO:</td>";
                    $this->salida.="<td  width=\"10%\" align = left >";
                    $this->salida.="<select size = 1 name = 'criterio1$pfj'  class =\"select\">";
                    $this->salida.="<option value = '-1' selected>Todos</option>";
                    if (($_REQUEST['criterio1'.$pfj])  != '-2')
                    {
                              $this->salida.="<option value = '-2'>Frecuentes</option>";
                    }
                    else
                    {
                              $this->salida.="<option value = '-2' selected>Frecuentes</option>";
                    }
                    $this->salida.="</select>";
                    $this->salida.="</td>";
     
                    $this->salida.="<td width=\"5%\">CODIGO:</td>";
                    //$this->salida .="<td width=\"5%\" align='center'><input type='text' class='input-text' size = 6 maxlength = 6	name = 'codigo_insumo$pfj'></td>" ;
                    $this->salida .="<td width=\"10%\" align='center'><input type='text' class='input-text' size = 10 maxlength = 10	name = 'codigo_insumo$pfj'  value =\"".$_REQUEST['codigo_insumo'.$pfj]."\"></td>" ;
     
                    $this->salida.="<td width=\"8%\">INSUMO:</td>";
                    $this->salida .="<td width=\"35%\" align='center'><input type='text' size =35 class='input-text' 	name = 'insumo$pfj'   value =\"".$_REQUEST['insumo'.$pfj]."\"></td>" ;
     
                    $this->salida .= "<td  width=\"7%\" align=\"center\"><input class=\"input-submit\" name=\"buscar$pfj\" type=\"submit\" value=\"BUSCAR\"></td>";
                    $this->salida.="</tr>";
                    $this->salida.="</table><br>";
     
                    $this->salida.="</form>";
     
                    $accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar_varios_insumos'));
                    $this->salida .= "<form name=\"formades$pfj\" action=\"$accionI\" method=\"post\">";
               if ($vectorI)
          {
                         $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
                              $this->salida.="<tr class=\"modulo_table_title\">";
                              $this->salida.="  <td align=\"center\" colspan=\"3\">RESULTADO DE LA BUSQUEDA</td>";
                              $this->salida.="</tr>";
     
                              $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                              $this->salida.="  <td width=\"10%\">CODIGO</td>";
                              $this->salida.="  <td width=\"65%\">INSUMO</td>";
                              $this->salida.="  <td width=\"5%\">OPCION</td>";
                              $this->salida.="</tr>";
                              for($i=0;$i<sizeof($vectorI);$i++)
               {
                                   if( $i % 2){ $estilo='modulo_list_claro';}
                                   else {$estilo='modulo_list_oscuro';}
                                   $this->salida.="<tr class=\"$estilo\">";
     
                                   $this->salida.="  <td align=\"center\" width=\"10%\">".$vectorI[$i][codigo_producto]."</td>";
                                   $this->salida.="  <td align=\"left\" width=\"65%\">".$vectorI[$i][descripcion]."</td>";
                                   $this->salida.="  <td align=\"center\" width=\"5%\"><input type = checkbox name= 'opD".$pfj."[$i]' value = ".$hc_os_solicitud_id.",".$vectorI[$i][codigo_producto]."></td>";
                                   $this->salida.="</tr>";
                              }
                              $this->salida.="<tr class=\"$estilo\">";
                              $this->salida .= "<td align=\"right\" colspan=\"3\"><input class=\"input-submit\" name=\"guardar$pfj\" type=\"submit\" value=\"GUARDAR\"></td>";
                              $this->salida.="</tr>";
                              $this->salida.="</table><br>";
                              $var=$this->RetornarBarraInsumosAvanzada();
                              if(!empty($var))
                              {
                                   $this->salida .= "  <br><table border=\"0\" width=\"60%\" align=\"center\">";// class=\"modulo_table\"
                                   $this->salida .= "  <tr>";
                                   $this->salida .= "  <td width=\"100%\" align=\"center\">";
                                   $this->salida .=$var;
                                   $this->salida .= "  </td>";
                                   $this->salida .= "  </tr>";
                                   $this->salida .= "  </table><br>";
                              }
                    }
          $this->salida .= "</form>";*/
          //fin de la nueva forma
          //fin de insumos
     
          //BOTON DEVOLVER
          $accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
          $this->salida .= "<form name=\"forma\" action=\"$accionV\" method=\"post\">";
          $this->salida .= "<tr><td  colspan = 6 align=\"center\"><br><input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\"></form></td></tr>";

          $this->salida .= ThemeCerrarTablaSubModulo();
          return true;
     }



 	function RetornarBarraInsumosAvanzada()//Barra paginadora de los planes clientes
	{
		$pfj=$this->frmPrefijo;
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso1'.$pfj];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Insumos',
		'conteo'.$pfj=>$this->conteo,'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
		'criterio1'.$pfj=>$_REQUEST['criterio1'.$pfj],
		'codigo_insumo'.$pfj=>$_REQUEST['codigo_insumo'.$pfj],
		'insumo'.$pfj=>$_REQUEST['insumo'.$pfj]));

		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset(1)."&paso1$pfj=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso-1)."&paso1$pfj=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($i)."&paso1$pfj=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso+1)."&paso1$pfj=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($numpasos)."&paso1$pfj=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($i)."&paso1$pfj=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso+1)."&paso1$pfj=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($numpasos)."&paso1$pfj=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
		if(($_REQUEST['Of'.$pfj])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		else
		{
			if($numpasos>10)
			{
				$valor=10+5;
			}
			else
			{
				$valor=$numpasos+5;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		return $salida;
	}


     function FrmHistoria()
     {
          $pfj=$this->frmPrefijo;
          $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
          $salida .= "<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
          $vector1=$this->Consulta_Solicitud_Medicamentos();
          $m = 0;
          if($vector1)
          {
               $salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
               $salida .= $this->SetStyle("MensajeError");
               $salida.="</table>";

               $salida.="<br><table  align=\"center\" border=\"1\"  width=\"100%\">";
               $salida.="<tr class=\"modulo_table_title\">";
               $salida.="<td align=\"center\" colspan=\"3\">MEDICAMENTOS SOLICITADOS EN ESTADO ACTIVO</td>";
               $salida.="</tr>";

               $salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $salida.="  <td width=\"7%\">CODIGO</td>";
               $salida.="  <td width=\"30%\">PRODUCTO</td>";
               $salida.="  <td width=\"29%\">PRINCIPIO ACTIVO</td>";
               $salida.="</tr>";
               //$salida.="</tr>";
               for($i=0;$i<sizeof($vector1);$i++)
               {
                    $vectorMSH = $this->Consulta_Solicitud_Medicamentos_Historial($vector1[$i][codigo_producto]);
                    if( $i % 2){ $estilo='modulo_list_claro';}
                    else {$estilo='modulo_list_oscuro';}
                    $salida.="<tr class=\"$estilo\">";
                    if($vector1[$i][item] == 'NO POS')
                         {
                                   if ($vectorMSH AND (sizeof($vectorMSH) > '1'))
                                   {
                                        $salida.="  <td ROWSPAN = 6 align=\"center\" width=\"7%\">".$vector1[$i][codigo_producto]."<BR>NO_POS</td>";
                                   }
                                   else
                                   {
                                        $salida.="  <td ROWSPAN = 5 align=\"center\" width=\"7%\">".$vector1[$i][codigo_producto]."<BR>NO_POS</td>";
                                   }
                         }
                         else
                         {
                              if($vectorMSH AND (sizeof($vectorMSH) > '1'))
                              {
                                   $salida.="  <td ROWSPAN = 5 align=\"center\" width=\"7%\">".$vector1[$i][codigo_producto]."</td>";
                              }
                              else
                              {

                                   $salida.="  <td ROWSPAN = 4 align=\"center\" width=\"7%\">".$vector1[$i][codigo_producto]."</td>";
                              }
                         }
                    //LINEA ALTERADA para ver la evolucion
                    $salida.="  <td align=\"center\" width=\"30%\">".$vector1[$i][producto]."-".$vector1[$i][evolucion_id]."</td>";
                    $salida.="  <td align=\"left\" width=\"29%\">".$vector1[$i][principio_activo]."</td>";
                    //fin del validador
                    $salida.="</tr>";


                    $salida.="<tr class=\"$estilo\">";
                    $salida.="<td colspan = 2>";
                    $salida.="<table>";

                    $salida.="<tr class=\"$estilo\">";
                              $salida.="  <td colspan = 3 align=\"left\" width=\"9%\">Via de Administracion: ".$vector1[$i][via]."</td>";
                    $salida.="</tr>";

                    $salida.="<tr class=\"$estilo\">";
                    $salida.="  <td align=\"left\" width=\"9%\">Dosis:</td>";
                    $e=$vector1[$i][dosis]/floor($vector1[$i][dosis]);
                    if($e==1)
                    {
                         $salida.="  <td align=\"left\" width=\"14%\">".floor($vector1[$i][dosis])."  ".$vector1[$i][unidad_dosificacion]."</td>";
                    }
                    else
                    {
                         $salida.="  <td align=\"left\" width=\"14%\">".$vector1[$i][dosis]."  ".$vector1[$i][unidad_dosificacion]."</td>";
                    }

                    $vector_posologia= $this->Consulta_Solicitud_Medicamentos_Posologia($vector1[$i][codigo_producto], $vector1[$i][tipo_opcion_posologia_id], $vector1[$i][evolucion_id]);

//pintar formula para opcion 1
                    if($vector1[$i][tipo_opcion_posologia_id]== 1)
                    {
                         $salida.="  <td align=\"left\" width=\"50%\">cada ".$vector_posologia[0][periocidad_id]." ".$vector_posologia[0][tiempo]."</td>";
                    }

//pintar formula para opcion 2
                    if($vector1[$i][tipo_opcion_posologia_id]== 2)
                    {
                         $salida.="  <td align=\"left\" width=\"50%\">".$vector_posologia[0][descripcion]."</td>";
                    }

//pintar formula para opcion 3
                    if($vector1[$i][tipo_opcion_posologia_id]== 3)
                    {
                         $momento = '';
                         if($vector_posologia[0][sw_estado_momento]== '1')
                         {
                              $momento = 'antes de ';
                         }
                         else
                         {
                              if($vector_posologia[0][sw_estado_momento]== '2')
                              {
                                   $momento = 'durante ';
                              }
                              else
                              {
                                   if($vector_posologia[0][sw_estado_momento]== '3')
                                        {
                                             $momento = 'despues de ';
                                        }
                              }
                         }
                         $Cen = $Alm = $Des= '';
                         $cont= 0;
                         $conector = '  ';
                         $conector1 = '  ';
                         if($vector_posologia[0][sw_estado_desayuno]== '1')
                         {
                              $Des = $momento.'el Desayuno';
                              $cont++;
                         }
                         if($vector_posologia[0][sw_estado_almuerzo]== '1')
                         {
                              $Alm = $momento.'el Almuerzo';
                              $cont++;
                         }
                         if($vector_posologia[0][sw_estado_cena]== '1')
                         {
                              $Cen = $momento.'la Cena';
                              $cont++;
                         }
                         if ($cont== 2)
                         {
                              $conector = ' y ';
                              $conector1 = '  ';
                         }
                         if ($cont== 1)
                         {
                              $conector = '  ';
                              $conector1 = '  ';
                         }
                         if ($cont== 3)
                         {
                              $conector = ' , ';
                              $conector1 = ' y ';
                         }
                         $salida.="  <td align=\"left\" width=\"50%\">".$Des."".$conector."".$Alm."".$conector1."".$Cen."</td>";
                    }

//pintar formula para opcion 4
                    if($vector1[$i][tipo_opcion_posologia_id]== 4)
                    {
                         $conector = '  ';
                         $frecuencia='';
                         $j=0;
                         foreach ($vector_posologia as $k => $v)
                         {
                              if ($j+1 ==sizeof($vector_posologia))
                              {
                                   $conector = '  ';
                              }
                              else
                              {
                                   if ($j+2 ==sizeof($vector_posologia))
                                   {
                                        $conector = ' y ';
                                   }
                                   else
                                   {
                                        $conector = ' - ';
                                   }
                              }
                              $frecuencia = $frecuencia.$k.$conector;
                              $j++;
                         }
                         $salida.="  <td align=\"left\" width=\"50%\">a la(s): $frecuencia</td>";
                    }

//pintar formula para opcion 5
                    if($vector1[$i][tipo_opcion_posologia_id]== 5)
                    {
                         $salida.="  <td align=\"left\" width=\"50%\">".$vector_posologia[0][frecuencia_suministro]."</td>";
                    }
                    $salida.="</tr>";

                    $salida.="<tr class=\"$estilo\">";
                    $salida.="  <td align=\"left\" width=\"9%\">Cantidad:</td>";
                    $e=$vector1[$i][cantidad]/floor($vector1[$i][cantidad]);
                    if ($vector1[$i][contenido_unidad_venta])
                    {
                         if($e==1)
                         {
                              $salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".floor($vector1[$i][cantidad])." ".$vector1[$i][descripcion]." por ".$vector1[$i][contenido_unidad_venta]."</td>";
                         }
                         else
                         {
                              $salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".$vector1[$i][cantidad]." ".$vector1[$i][descripcion]." por ".$vector1[$i][contenido_unidad_venta]."</td>";
                         }
                    }
                    else
                    {
                         if($e==1)
                         {
                              $salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".floor($vector1[$i][cantidad])." ".$vector1[$i][descripcion]."</td>";
                         }
                         else
                         {
                              $salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".$vector1[$i][cantidad]." ".$vector1[$i][descripcion]."</td>";
                         }
                    }
                    $salida.="</tr>";

                    $salida.="</table>";
                    $salida.="</td>";
                    $salida.="</tr>";

                    $salida.="<tr class=\"$estilo\">";
                    $salida.="<td colspan = 2 class=\"$estilo\">";
                    $salida.="<table>";
                    $salida.="<tr class=\"$estilo\">";
                    $salida.="  <td align=\"left\" width=\"4%\">Observacion:</td>";
                    $salida.="  <td align=\"left\" width=\"69%\">".$vector1[$i][observacion]."</td>";
                    $salida.="<tr class=\"$estilo\">";


                    if($vector1[$i][sw_uso_controlado]==1)
                    {
                         $salida.="<tr class=\"$estilo\">";
                         $salida.="  <td align=\"left\" colspan = 2 width=\"73%\">MEDICAMENTO DE USO CONTROLADO</td>";
                         $salida.="<tr class=\"$estilo\">";
                    }

                    /*PARTE NUEVA DE MEDICAMENTOS..... MEDICAMENTO AMBULAROTIO*/
                    if($vector1[$i][sw_ambulatorio]==1)
                    {
                         $salida.="<tr class=\"$estilo\">";
                         $salida.="<td colspan = 7 class=\"$estilo\">";
                         $salida.="  <table>";
                         $salida.="  <tr class=\"$estilo\">";
                         $salida.="    <td colspan=\"2\" align=\"center\" width=\"4%\" class=\"label\">MEDICAMENTO AMBULATORIO</td>";
                         $salida.="  </tr>";
                         $salida.="  </table>";
                         $salida.=" </td>";
                         $salida.="</tr>";
                    }

                    $salida.="</table>";

                    $salida.="</td>";
                    $salida.="</tr>";
               
                    if (!empty($vector1[$i][nombre_tercero]))
                    {
                         $fechaf = $this->FechaStamp($vector1[$i][fecha]);
                         $salida.="<tr class=\"$estilo\">";
                         $salida.="<td align=\"left\" width=\"50%\"><b>Formuló:&nbsp;&nbsp;</b>".$vector1[$i][nombre_tercero]."</td>";
                         $salida.="<td align=\"left\" width=\"20%\"><b>Fecha Formulación:&nbsp;&nbsp;</b>".$fechaf."</td>";
                         $salida.="</tr>";
                    }

                    if($vector1[$i][item] == 'NO POS')
                    {
                         $salida.="<tr class=\"$estilo\">";
                         if($vector1[$i][sw_paciente_no_pos] != '1')
                         {
                                        $salida.="<td class = label_error colspan = 2 align=\"center\" width=\"63%\">MEDICAMENTO JUSTIFICADO</td>";
                         }
                         else
                         {
                              $salida.="<td class = label_error colspan = 2 align=\"center\" width=\"63%\">MEDICAMENTO NO POS FORMULADO A PETICION DEL PACIENTE</td>";
                         }
                         $salida.="</tr>";
                    }


                    //HISTORIAL DEL MEDICAMENTO
                    if ($vectorMSH AND (sizeof($vectorMSH) > '1'))
                    {
                         $registros_historial = sizeof($vectorMSH);
                         $salida.="<tr class=\"$estilo\">";
                         $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Detalle_Suministro', 'codigo_producto'.$pfj=>$vector1[$i]['codigo_producto'], 'producto'.$pfj=>$vector1[$i]['producto'], 'principio_activo'.$pfj=>$vector1[$i]['principio_activo']));
                         $salida.="<td colspan = 2 align=\"center\" width=\"63%\"><font color=\"#240000\">HISTORIAL (No. veces formulado: ".$registros_historial." --- Primer Formulacion: ".$this->FechaStamp($vectorMSH[0][fecha])." --- Ultima Formulacion: ".$this->FechaStamp($vectorMSH[$registros_historial-1][fecha]).")</font></td>";
                         $salida.="</tr>";
                    }
               //fin del for muy importante
               }
               $salida.="</table><br>";
          }
          else
          {
               $m = $m+1;
          }
          
          $salida.= $this->Dosis_Suministradas('',0);
          
          $salida.= $this->frmConsultaCanastaMedica();

          //**pintar los medicamentos finalizados
          $vectorMF = $this->Consulta_Solicitud_Medicamentos_Finalizados();
          if ($vectorMF)
          {
               $salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";
               $salida.="<tr class=\"modulo_table_title\">";
               
               $salida.="<td align=\"center\" colspan=\"3\">MEDICAMENTOS FINALIZADOS</td>";
               
               $salida.="</tr>";

               $salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $salida.="  <td width=\"7%\">CODIGO</td>";
               $salida.="  <td width=\"30%\">PRODUCTO</td>";
               $salida.="  <td width=\"29%\">PRINCIPIO ACTIVO</td>";

               $salida.="</tr>";

	          for($i=0;$i<sizeof($vectorMF);$i++)
               {
                    if( $i % 2){ $estilo='modulo_list_claro';}
                    else {$estilo='modulo_list_oscuro';}
                    
                    $salida.="<tr class=\"$estilo\">";
                    if($$vectorMF[$i][item] == 'NO POS')
                    {
                         $salida.=" <td ROWSPAN = 4 align=\"center\" width=\"7%\">".$vectorMF[$i][codigo_producto]."<BR>NO_POS</td>";
                    }
                    else
                    {
                         $salida.="  <td ROWSPAN = 4 align=\"center\" width=\"7%\">".$vectorMF[$i][codigo_producto]."</td>";
                    }
                    //LINEA ALTERADA para ver la evolucion
                    $salida.="  <td align=\"center\" width=\"30%\">".$vectorMF[$i][producto]."-".$vectorMF[$i][evolucion_id]."</td>";
                    $salida.="  <td align=\"left\" width=\"29%\">".$vectorMF[$i][principio_activo]."</td>";
                    //fin del validador
                    $salida.="</tr>";
                    
                    $salida.="<tr class=\"$estilo\">";
                    $salida.="<td colspan = 2>";
                    $salida.="<table>";

                    $salida.="<tr class=\"$estilo\">";
                    $salida.="  <td colspan = 3 align=\"left\" width=\"9%\">Via de Administracion: ".$vectorMF[$i][via]."</td>";
                    $salida.="</tr>";

                    $salida.="<tr class=\"$estilo\">";
                    $salida.="  <td align=\"left\" width=\"9%\">Dosis:</td>";
                    $e=$vectorMF[$i][dosis]/floor($vectorMF[$i][dosis]);
                    if($e==1)
                    {
                         $salida.="  <td align=\"left\" width=\"14%\">".floor($vectorMF[$i][dosis])."  ".$vectorMF[$i][unidad_dosificacion]."</td>";
                    }
                    else
                    {
                         $salida.="  <td align=\"left\" width=\"14%\">".$vectorMF[$i][dosis]."  ".$vectorMF[$i][unidad_dosificacion]."</td>";
                    }

                    $vector_posologia= $this->Consulta_Solicitud_Medicamentos_Posologia($vectorMF[$i][codigo_producto], $vectorMF[$i][tipo_opcion_posologia_id], $vectorMF[$i][evolucion_id]);

//pintar formula para opcion 1
                    if($vectorMF[$i][tipo_opcion_posologia_id]== 1)
                    {
                         $salida.="  <td align=\"left\" width=\"50%\">cada ".$vector_posologia[0][periocidad_id]." ".$vector_posologia[0][tiempo]."</td>";
                    }

//pintar formula para opcion 2
                    if($vectorMF[$i][tipo_opcion_posologia_id]== 2)
                    {
                         $salida.="  <td align=\"left\" width=\"50%\">".$vector_posologia[0][descripcion]."</td>";
                    }

//pintar formula para opcion 3
                    if($vectorMF[$i][tipo_opcion_posologia_id]== 3)
                    {
                         $momento = '';
                         if($vector_posologia[0][sw_estado_momento]== '1')
                         {
                              $momento = 'antes de ';
                         }
                         else
                         {
                              if($vector_posologia[0][sw_estado_momento]== '2')
                              {
                                   $momento = 'durante ';
                              }
                              else
                              {
                                   if($vector_posologia[0][sw_estado_momento]== '3')
                                        {
                                             $momento = 'despues de ';
                                        }
                              }
                         }
                         $Cen = $Alm = $Des= '';
                         $cont= 0;
                         $conector = '  ';
                         $conector1 = '  ';
                         if($vector_posologia[0][sw_estado_desayuno]== '1')
                         {
                              $Des = $momento.'el Desayuno';
                              $cont++;
                         }
                         if($vector_posologia[0][sw_estado_almuerzo]== '1')
                         {
                              $Alm = $momento.'el Almuerzo';
                              $cont++;
                         }
                         if($vector_posologia[0][sw_estado_cena]== '1')
                         {
                              $Cen = $momento.'la Cena';
                              $cont++;
                         }
                         if ($cont== 2)
                         {
                              $conector = ' y ';
                              $conector1 = '  ';
                         }
                         if ($cont== 1)
                         {
                              $conector = '  ';
                              $conector1 = '  ';
                         }
                         if ($cont== 3)
                         {
                              $conector = ' , ';
                              $conector1 = ' y ';
                         }
                         $salida.="  <td align=\"left\" width=\"50%\">".$Des."".$conector."".$Alm."".$conector1."".$Cen."</td>";
                    }

//pintar formula para opcion 4
                    if($vectorMF[$i][tipo_opcion_posologia_id]== 4)
                    {
                         $conector = '  ';
                         $frecuencia='';
                         $j=0;
                         foreach ($vector_posologia as $k => $v)
                         {
                              if ($j+1 ==sizeof($vector_posologia))
                              {
                                   $conector = '  ';
                              }
                              else
                              {
                                        if ($j+2 ==sizeof($vector_posologia))
                                             {
                                                  $conector = ' y ';
                                             }
                                        else
                                             {
                                                  $conector = ' - ';
                                             }
                              }
                              $frecuencia = $frecuencia.$k.$conector;
                              $j++;
                         }
                         $salida.="  <td align=\"left\" width=\"50%\">a la(s): $frecuencia</td>";
                    }

//pintar formula para opcion 5
                    if($vectorMF[$i][tipo_opcion_posologia_id]== 5)
                    {
                         $salida.="  <td align=\"left\" width=\"50%\">".$vector_posologia[0][frecuencia_suministro]."</td>";
                    }
                    $salida.="</tr>";

                    $salida.="<tr class=\"$estilo\">";
                    $salida.="  <td align=\"left\" width=\"9%\">Cantidad:</td>";
                    $e=$vectorMF[$i][cantidad]/floor($vectorMF[$i][cantidad]);
                    if ($vectorMF[$i][contenido_unidad_venta])
                    {
                         if($e==1)
                         {
                              $salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".floor($vectorMF[$i][cantidad])." ".$vectorMF[$i][descripcion]." por ".$vectorMF[$i][contenido_unidad_venta]."</td>";
                         }
                         else
                         {
                              $salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".$vectorMF[$i][cantidad]." ".$vectorMF[$i][descripcion]." por ".$vectorMF[$i][contenido_unidad_venta]."</td>";
                         }
                    }
                    else
                    {
                         if($e==1)
                         {
                              $salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".floor($vectorMF[$i][cantidad])." ".$vectorMF[$i][descripcion]."</td>";
                         }
                         else
                         {
                              $salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".$vectorMF[$i][cantidad]." ".$vectorMF[$i][descripcion]."</td>";
                         }
                    }
                    $salida.="</tr>";

                    $salida.="</table>";
                    $salida.="</td>";
                    $salida.="</tr>";
                    
                    $salida.="<tr class=\"$estilo\">";
                    $salida.="<td colspan = 2 class=\"$estilo\">";
                    $salida.="<table>";
                    $salida.="<tr class=\"$estilo\">";
                    $salida.="  <td align=\"left\" width=\"4%\">Observacion:</td>";
                    $salida.="  <td align=\"left\" width=\"69%\">".$vectorMF[$i][observacion]."</td>";
                    $salida.="<tr class=\"$estilo\">";

                    $salida.="</table>";

                    $salida.="</td>";
                    $salida.="</tr>";
                    
                    if (!empty($vectorMF[$i][nombre_tercero]))
	               {
                         $fechaf = $this->FechaStamp($vectorMF[$i][fecha_registro]);
                         $salida.="<tr class=\"$estilo\">";
                         $salida.="<td align=\"left\" width=\"50%\"><b>Formuló:&nbsp;&nbsp;</b>".$vectorMF[$i][nombre_tercero]."</td>";
                         $salida.="<td align=\"left\" width=\"20%\"><b>Fecha Finalización:&nbsp;&nbsp;</b>".$fechaf."</td>";
                         $salida.="</tr>";
  				}
               }
               $salida.="</table><br>";
          }
          else
          {
               $m = $m+1;
          }

               
          //**pintar los medicamentos suspendidos
          $vectorMS = $this->Consulta_Solicitud_Medicamentos_Suspendidos();
          if ($vectorMS)
          {
               $salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";
               $salida.="<tr class=\"modulo_table_title\">";
               
               $salida.="<td align=\"center\" colspan=\"3\">MEDICAMENTOS SUSPENDIDOS</td>";
               
               $salida.="</tr>";

               $salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $salida.="  <td width=\"7%\">CODIGO</td>";
               $salida.="  <td width=\"30%\">PRODUCTO</td>";
               $salida.="  <td width=\"29%\">PRINCIPIO ACTIVO</td>";

               $salida.="</tr>";

               for($i=0;$i<sizeof($vectorMS);$i++)
               {
                    if( $i % 2){ $estilo='modulo_list_claro';}
                    else {$estilo='modulo_list_oscuro';}
                    
                    $salida.="<tr class=\"$estilo\">";
                    if($$vectorMS[$i][item] == 'NO POS')
                    {
                         $salida.=" <td ROWSPAN = 4 align=\"center\" width=\"7%\">".$vectorMS[$i][codigo_producto]."<BR>NO_POS</td>";
                    }
                    else
                    {
                         $salida.="  <td ROWSPAN = 4 align=\"center\" width=\"7%\">".$vectorMS[$i][codigo_producto]."</td>";
                    }
                    //LINEA ALTERADA para ver la evolucion
                    $salida.="  <td align=\"center\" width=\"30%\">".$vectorMS[$i][producto]."-".$vectorMS[$i][evolucion_id]."</td>";
                    $salida.="  <td align=\"left\" width=\"29%\">".$vectorMS[$i][principio_activo]."</td>";
                    //fin del validador
                    $salida.="</tr>";
                    
                    $salida.="<tr class=\"$estilo\">";
                    $salida.="<td colspan = 2>";
                    $salida.="<table>";

                    $salida.="<tr class=\"$estilo\">";
                    $salida.="  <td colspan = 3 align=\"left\" width=\"9%\">Via de Administracion: ".$vectorMS[$i][via]."</td>";
                    $salida.="</tr>";

                    $salida.="<tr class=\"$estilo\">";
                    $salida.="  <td align=\"left\" width=\"9%\">Dosis:</td>";
                    $e=$vectorMS[$i][dosis]/floor($vectorMS[$i][dosis]);
                    if($e==1)
                    {
                         $salida.="  <td align=\"left\" width=\"14%\">".floor($vectorMS[$i][dosis])."  ".$vectorMS[$i][unidad_dosificacion]."</td>";
                    }
                    else
                    {
                         $salida.="  <td align=\"left\" width=\"14%\">".$vectorMS[$i][dosis]."  ".$vectorMS[$i][unidad_dosificacion]."</td>";
                    }

                    $vector_posologia= $this->Consulta_Solicitud_Medicamentos_Posologia($vectorMS[$i][codigo_producto], $vectorMS[$i][tipo_opcion_posologia_id], $vectorMS[$i][evolucion_id]);

//pintar formula para opcion 1
                    if($vectorMS[$i][tipo_opcion_posologia_id]== 1)
                    {
                         $salida.="  <td align=\"left\" width=\"50%\">cada ".$vector_posologia[0][periocidad_id]." ".$vector_posologia[0][tiempo]."</td>";
                    }

//pintar formula para opcion 2
                    if($vectorMS[$i][tipo_opcion_posologia_id]== 2)
                    {
                         $salida.="  <td align=\"left\" width=\"50%\">".$vector_posologia[0][descripcion]."</td>";
                    }

//pintar formula para opcion 3
                    if($vectorMS[$i][tipo_opcion_posologia_id]== 3)
                    {
                    $momento = '';
                    if($vector_posologia[0][sw_estado_momento]== '1')
                    {
                         $momento = 'antes de ';
                    }
                    else
                    {
                         if($vector_posologia[0][sw_estado_momento]== '2')
                         {
                              $momento = 'durante ';
                         }
                         else
                         {
                              if($vector_posologia[0][sw_estado_momento]== '3')
                                   {
                                        $momento = 'despues de ';
                                   }
                         }
                    }
                    $Cen = $Alm = $Des= '';
                    $cont= 0;
                    $conector = '  ';
                    $conector1 = '  ';
                    if($vector_posologia[0][sw_estado_desayuno]== '1')
                    {
                         $Des = $momento.'el Desayuno';
                         $cont++;
                    }
                         if($vector_posologia[0][sw_estado_almuerzo]== '1')
                         {
                              $Alm = $momento.'el Almuerzo';
                              $cont++;
                         }
                         if($vector_posologia[0][sw_estado_cena]== '1')
                         {
                              $Cen = $momento.'la Cena';
                              $cont++;
                         }
                         if ($cont== 2)
                         {
                              $conector = ' y ';
                              $conector1 = '  ';
                         }
                         if ($cont== 1)
                         {
                              $conector = '  ';
                              $conector1 = '  ';
                         }
                         if ($cont== 3)
                         {
                              $conector = ' , ';
                              $conector1 = ' y ';
                         }
                         $salida.="  <td align=\"left\" width=\"50%\">".$Des."".$conector."".$Alm."".$conector1."".$Cen."</td>";
                    }

//pintar formula para opcion 4
                    if($vectorMS[$i][tipo_opcion_posologia_id]== 4)
                    {
                         $conector = '  ';
                         $frecuencia='';
                         $j=0;
                         foreach ($vector_posologia as $k => $v)
                         {
                              if ($j+1 ==sizeof($vector_posologia))
                              {
                                   $conector = '  ';
                              }
                              else
                              {
                                        if ($j+2 ==sizeof($vector_posologia))
                                             {
                                                  $conector = ' y ';
                                             }
                                        else
                                             {
                                                  $conector = ' - ';
                                             }
                              }
                              $frecuencia = $frecuencia.$k.$conector;
                              $j++;
                         }
                         $salida.="  <td align=\"left\" width=\"50%\">a la(s): $frecuencia</td>";
                    }

//pintar formula para opcion 5
                    if($vectorMS[$i][tipo_opcion_posologia_id]== 5)
                    {
                         $salida.="  <td align=\"left\" width=\"50%\">".$vector_posologia[0][frecuencia_suministro]."</td>";
                    }
                    $salida.="</tr>";

                    $salida.="<tr class=\"$estilo\">";
                    $salida.="  <td align=\"left\" width=\"9%\">Cantidad:</td>";
                    $e=$vectorMS[$i][cantidad]/floor($vectorMS[$i][cantidad]);
                    if ($vectorMS[$i][contenido_unidad_venta])
                    {
                         if($e==1)
                         {
                              $salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".floor($vectorMS[$i][cantidad])." ".$vectorMS[$i][descripcion]." por ".$vectorMS[$i][contenido_unidad_venta]."</td>";
                         }
                         else
                         {
                              $salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".$vectorMS[$i][cantidad]." ".$vectorMS[$i][descripcion]." por ".$vectorMS[$i][contenido_unidad_venta]."</td>";
                         }
                    }
                    else
                    {
                         if($e==1)
                         {
                              $salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".floor($vectorMS[$i][cantidad])." ".$vectorMS[$i][descripcion]."</td>";
                         }
                         else
                         {
                              $salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".$vectorMS[$i][cantidad]." ".$vectorMS[$i][descripcion]."</td>";
                         }
                    }
                    $salida.="</tr>";

                    $salida.="</table>";
                    $salida.="</td>";
                    $salida.="</tr>";
                    
                    $salida.="<tr class=\"$estilo\">";
                    $salida.="<td colspan = 2 class=\"$estilo\">";
                    $salida.="<table>";
                    $salida.="<tr class=\"$estilo\">";
                    $salida.="  <td align=\"left\" width=\"4%\">Observacion:</td>";
                    $salida.="  <td align=\"left\" width=\"69%\">".$vectorMS[$i][observacion]."</td>";
                    $salida.="<tr class=\"$estilo\">";

                    $salida.="</table>";

                    $salida.="</td>";
                    $salida.="</tr>";
                    
                    if (!empty($vectorMS[$i][nombre_tercero]))
	               {
                         $fechaf = $this->FechaStamp($vectorMS[$i][fecha_registro]);
                         $salida.="<tr class=\"$estilo\">";
                         $salida.="<td align=\"left\" width=\"50%\"><b>Formuló:&nbsp;&nbsp;</b>".$vectorMS[$i][nombre_tercero]."</td>";
                         $salida.="<td align=\"left\" width=\"20%\"><b>Fecha Suspensión:&nbsp;&nbsp;</b>".$fechaf."</td>";
                         $salida.="</tr>";
  				}
               }
               $salida.="</table><br>";
          }
          else
          {
               $m = $m+1;
          }

          //fin de mediacamentos finalizados
          $salida .= "</form>";

          if ($_SESSION['PROFESIONAL'.$pfj]!=1)
          {
               if($m==2)
               {
                    $salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
                    $salida.="<tr class=\"hc_table_submodulo_list_title\">";
                    $salida.="  <td align=\"center\" width=\"7%\">EL PACIENTE NO TIENE MEDICAMENTOS FORMULADOS</td>";
                    $salida.="</tr>";
                    $salida.="</table><br>";
               }
          }
          return $salida;
     }


     function FrmConsulta()
     {
		$pfj=$this->frmPrefijo;
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
		$this->salida .= "<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
		$vector1=$this->Consulta_Solicitud_Medicamentos();
		$m = 0;
		if($vector1)
		{
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida.="</table>";

			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="<td align=\"center\" colspan=\"3\">MEDICAMENTOS SOLICITADOS EN ESTADO ACTIVO</td>";
			$this->salida.="</tr>";

			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td width=\"7%\">CODIGO</td>";
			$this->salida.="  <td width=\"30%\">PRODUCTO</td>";
			$this->salida.="  <td width=\"29%\">PRINCIPIO ACTIVO</td>";
			$this->salida.="</tr>";
			//$this->salida.="</tr>";
			for($i=0;$i<sizeof($vector1);$i++)
			{
                    if($vector1[$i]['sw_ambulatorio']==1){$sumaRows=1;}else{$sumaRows=0;}
                    if($_SESSION['PROFESIONAL'.$pfj]!=1){$sumaRows+=1;}

                    $vectorMSH = $this->Consulta_Solicitud_Medicamentos_Historial($vector1[$i][codigo_producto]);
                    if( $i % 2){ $estilo='modulo_list_claro';}
                    else {$estilo='modulo_list_oscuro';}
                    $this->salida.="<tr class=\"$estilo\">";
                    if($vector1[$i][item] == 'NO POS')
                    {
                         if ($vectorMSH AND (sizeof($vectorMSH) > '1'))
                         {
                                   $sumaRows+=6;
                                   $this->salida.="  <td ROWSPAN = \"$sumaRows\" align=\"center\" width=\"7%\">".$vector1[$i][codigo_producto]."<BR>NO_POS</td>";
                         }			
                         else
                         {
                                   $sumaRows+=5;
                         $this->salida.="  <td ROWSPAN = \"$sumaRows\" align=\"center\" width=\"7%\">".$vector1[$i][codigo_producto]."<BR>NO_POS</td>";
                         }
                    }
                    else
                    {
                         if($vectorMSH AND (sizeof($vectorMSH) > '1'))
                         {
                              $sumaRows+=5;
                              $this->salida.="  <td ROWSPAN = \"$sumaRows\" align=\"center\" width=\"7%\">".$vector1[$i][codigo_producto]."</td>";
                         }
                         else
                         {
                              $sumaRows+=4;
                              $this->salida.="  <td ROWSPAN = \"$sumaRows\" align=\"center\" width=\"7%\">".$vector1[$i][codigo_producto]."</td>";
                         }
                    }												 
                                        
                    //LINEA ALTERADA para ver la evolucion
                    $this->salida.="  <td align=\"center\" width=\"30%\">".$vector1[$i][producto]."-".$vector1[$i][evolucion_id]."</td>";
                    $this->salida.="  <td align=\"left\" width=\"29%\">".$vector1[$i][principio_activo]."</td>";
                    //fin del validador
                    $this->salida.="</tr>";

                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="<td colspan = 2>";
                    $this->salida.="<table>";

                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="  <td colspan = 3 align=\"left\" width=\"9%\">Via de Administracion: ".$vector1[$i][via]."</td>";
                    $this->salida.="</tr>";

                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="  <td align=\"left\" width=\"9%\">Dosis:</td>";
                    $e=$vector1[$i][dosis]/floor($vector1[$i][dosis]);
                    if($e==1)
                    {
                         $this->salida.="  <td align=\"left\" width=\"14%\">".floor($vector1[$i][dosis])."  ".$vector1[$i][unidad_dosificacion]."</td>";
                    }
                    else
                    {
                         $this->salida.="  <td align=\"left\" width=\"14%\">".$vector1[$i][dosis]."  ".$vector1[$i][unidad_dosificacion]."</td>";
                    }

                    $vector_posologia= $this->Consulta_Solicitud_Medicamentos_Posologia($vector1[$i][codigo_producto], $vector1[$i][tipo_opcion_posologia_id], $vector1[$i][evolucion_id]);

//pintar formula para opcion 1
                    if($vector1[$i][tipo_opcion_posologia_id]== 1)
                    {
                         $this->salida.="  <td align=\"left\" width=\"50%\">cada ".$vector_posologia[0][periocidad_id]." ".$vector_posologia[0][tiempo]."</td>";
                    }

//pintar formula para opcion 2
                    if($vector1[$i][tipo_opcion_posologia_id]== 2)
                    {
                         $this->salida.="  <td align=\"left\" width=\"50%\">".$vector_posologia[0][descripcion]."</td>";
                    }

//pintar formula para opcion 3
                    if($vector1[$i][tipo_opcion_posologia_id]== 3)
                    {
                         $momento = '';
                         if($vector_posologia[0][sw_estado_momento]== '1')
                         {
                              $momento = 'antes de ';
                         }
                         else
                         {
                              if($vector_posologia[0][sw_estado_momento]== '2')
                              {
                                   $momento = 'durante ';
                              }
                              else
                              {
                                   if($vector_posologia[0][sw_estado_momento]== '3')
                                        {
                                             $momento = 'despues de ';
                                        }
                              }
                         }
                         $Cen = $Alm = $Des= '';
                         $cont= 0;
                         $conector = '  ';
                         $conector1 = '  ';
                         if($vector_posologia[0][sw_estado_desayuno]== '1')
                         {
                              $Des = $momento.'el Desayuno';
                              $cont++;
                         }
                              if($vector_posologia[0][sw_estado_almuerzo]== '1')
                              {
                                   $Alm = $momento.'el Almuerzo';
                                   $cont++;
                              }
                              if($vector_posologia[0][sw_estado_cena]== '1')
                              {
                                   $Cen = $momento.'la Cena';
                                   $cont++;
                              }
                              if ($cont== 2)
                              {
                                   $conector = ' y ';
                                   $conector1 = '  ';
                              }
                              if ($cont== 1)
                              {
                                   $conector = '  ';
                                   $conector1 = '  ';
                              }
                              if ($cont== 3)
                              {
                                   $conector = ' , ';
                                   $conector1 = ' y ';
                              }
                              $this->salida.="  <td align=\"left\" width=\"50%\">".$Des."".$conector."".$Alm."".$conector1."".$Cen."</td>";
                    }

//pintar formula para opcion 4
                    if($vector1[$i][tipo_opcion_posologia_id]== 4)
                    {
                         $conector = '  ';
                         $frecuencia='';
                         $j=0;
                         foreach ($vector_posologia as $k => $v)
                         {
                              if ($j+1 ==sizeof($vector_posologia))
                              {
                                   $conector = '  ';
                              }
                              else
                              {
                                        if ($j+2 ==sizeof($vector_posologia))
                                             {
                                                  $conector = ' y ';
                                             }
                                        else
                                             {
                                                  $conector = ' - ';
                                             }
                              }
                              $frecuencia = $frecuencia.$k.$conector;
                              $j++;
                         }
                         $this->salida.="  <td align=\"left\" width=\"50%\">a la(s): $frecuencia</td>";
                    }

//pintar formula para opcion 5
                    if($vector1[$i][tipo_opcion_posologia_id]== 5)
                    {
                         $this->salida.="  <td align=\"left\" width=\"50%\">".$vector_posologia[0][frecuencia_suministro]."</td>";
                    }
                    $this->salida.="</tr>";

                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="  <td align=\"left\" width=\"9%\">Cantidad:</td>";
                    $e=$vector1[$i][cantidad]/floor($vector1[$i][cantidad]);
                    if ($vector1[$i][contenido_unidad_venta])
                    {
                         if($e==1)
                         {
                              $this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".floor($vector1[$i][cantidad])." ".$vector1[$i][descripcion]." por ".$vector1[$i][contenido_unidad_venta]."</td>";
                         }
                         else
                         {
                              $this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".$vector1[$i][cantidad]." ".$vector1[$i][descripcion]." por ".$vector1[$i][contenido_unidad_venta]."</td>";
                         }
                    }
                    else
                    {
                         if($e==1)
                         {
                              $this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".floor($vector1[$i][cantidad])." ".$vector1[$i][descripcion]."</td>";
                         }
                         else
                         {
                              $this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".$vector1[$i][cantidad]." ".$vector1[$i][descripcion]."</td>";
                         }
                    }
                    $this->salida.="</tr>";

                    $this->salida.="</table>";
                    $this->salida.="</td>";
                    $this->salida.="</tr>";
                    
                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="<td colspan = 2 class=\"$estilo\">";
                    $this->salida.="<table>";
                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="  <td align=\"left\" width=\"4%\">Observacion:</td>";
                    $this->salida.="  <td align=\"left\" width=\"69%\">".$vector1[$i][observacion]."</td>";
                    $this->salida.="<tr class=\"$estilo\">";


                    if($vector1[$i][sw_uso_controlado]==1)
                    {
                         $this->salida.="<tr class=\"$estilo\">";
                         $this->salida.="<td align=\"left\" colspan = 2 width=\"73%\">MEDICAMENTO DE USO CONTROLADO</td>";
                         $this->salida.="<tr class=\"$estilo\">";
                    }
                    $this->salida.="</table>";

                    $this->salida.="</td>";
                    $this->salida.="</tr>";						
																									
                    if (!empty($vector1[$i][nombre_tercero]))
                    {
                         $fechaf = $this->FechaStamp($vector1[$i][fecha]);
                         $this->salida.="<tr class=\"$estilo\">";
                         $this->salida.="<td align=\"left\" width=\"50%\"><b>Formuló:&nbsp;&nbsp;</b>".$vector1[$i][nombre_tercero]."</td>";
                         $this->salida.="<td align=\"left\" width=\"20%\"><b>Fecha Formulación:&nbsp;&nbsp;</b>".$fechaf."</td>";
                         $this->salida.="</tr>";
                    }
                    
                    if($vector1[$i][sw_ambulatorio]==1){
                              $this->salida.="  <tr class=\"$estilo\">";
                              $this->salida.="    <td colspan=\"2\" align=\"left\" width=\"4%\" class=\"label\">Medicamento Ambulatorio</td>";
                              $this->salida.="  </tr>";						
                    }	
                    if($vector1[$i][item] == 'NO POS')
                    {
                         $this->salida.="<tr class=\"$estilo\">";
                         if($vector1[$i][sw_paciente_no_pos] != '1')
                         {
                                        $this->salida.="<td class = label_error colspan = 2 align=\"center\" width=\"63%\">MEDICAMENTO JUSTIFICADO</td>";
                         }
                         else
                         {
                              $this->salida.="<td class = label_error colspan = 2 align=\"center\" width=\"63%\">MEDICAMENTO NO POS FORMULADO A PETICION DEL PACIENTE</td>";
                         }
                         $this->salida.="</tr>";
                    }

     //HISTORIAL DEL MEDICAMENTO
                    if ($vectorMSH AND (sizeof($vectorMSH) > '1'))
                    {
                         $registros_historial = sizeof($vectorMSH);
                         $this->salida.="<tr class=\"$estilo\">";
                         $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Detalle_Suministro', 'codigo_producto'.$pfj=>$vector1[$i]['codigo_producto'], 'producto'.$pfj=>$vector1[$i]['producto'], 'principio_activo'.$pfj=>$vector1[$i]['principio_activo']));
                         $this->salida.="<td colspan = 2 align=\"center\" width=\"63%\"><font color=\"#240000\">HISTORIAL (No. veces formulado: ".$registros_historial." --- Primer Formulacion: ".$this->FechaStamp($vectorMSH[0][fecha])." --- Ultima Formulacion: ".$this->FechaStamp($vectorMSH[$registros_historial-1][fecha]).")</font></td>";
                         $this->salida.="</tr>";
                    }
//fin del for muy importante
               }
               $this->salida.="</table><br>";
          }
          else
          {
               $m = $m+1;
          }

          $this->Dosis_Suministradas('',1);
          
          $this->frmConsultaCanastaMedica(1);
          
          
          //**pintar los medicamentos finalizados
          $vectorMF = $this->Consulta_Solicitud_Medicamentos_Finalizados();
          if ($vectorMF)
          {
               $this->salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";
               $this->salida.="<tr class=\"modulo_table_title\">";
               
               $this->salida.="<td align=\"center\" colspan=\"3\">MEDICAMENTOS FINALIZADOS</td>";
               
               $this->salida.="</tr>";

               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="  <td width=\"7%\">CODIGO</td>";
               $this->salida.="  <td width=\"30%\">PRODUCTO</td>";
               $this->salida.="  <td width=\"29%\">PRINCIPIO ACTIVO</td>";

               $this->salida.="</tr>";

	          for($i=0;$i<sizeof($vectorMF);$i++)
               {
                    if( $i % 2){ $estilo='modulo_list_claro';}
                    else {$estilo='modulo_list_oscuro';}
                    
                    $this->salida.="<tr class=\"$estilo\">";
                    if($$vectorMF[$i][item] == 'NO POS')
                    {
                         $this->salida.=" <td ROWSPAN = 4 align=\"center\" width=\"7%\">".$vectorMF[$i][codigo_producto]."<BR>NO_POS</td>";
                    }
                    else
                    {
                         $this->salida.="  <td ROWSPAN = 4 align=\"center\" width=\"7%\">".$vectorMF[$i][codigo_producto]."</td>";
                    }
                    //LINEA ALTERADA para ver la evolucion
                    $this->salida.="  <td align=\"center\" width=\"30%\">".$vectorMF[$i][producto]."-".$vectorMF[$i][evolucion_id]."</td>";
                    $this->salida.="  <td align=\"left\" width=\"29%\">".$vectorMF[$i][principio_activo]."</td>";
                    //fin del validador
                    $this->salida.="</tr>";
                    
                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="<td colspan = 2>";
                    $this->salida.="<table>";

                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="  <td colspan = 3 align=\"left\" width=\"9%\">Via de Administracion: ".$vectorMF[$i][via]."</td>";
                    $this->salida.="</tr>";

                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="  <td align=\"left\" width=\"9%\">Dosis:</td>";
                    $e=$vectorMF[$i][dosis]/floor($vectorMF[$i][dosis]);
                    if($e==1)
                    {
                         $this->salida.="  <td align=\"left\" width=\"14%\">".floor($vectorMF[$i][dosis])."  ".$vectorMF[$i][unidad_dosificacion]."</td>";
                    }
                    else
                    {
                         $this->salida.="  <td align=\"left\" width=\"14%\">".$vectorMF[$i][dosis]."  ".$vectorMF[$i][unidad_dosificacion]."</td>";
                    }

                    $vector_posologia= $this->Consulta_Solicitud_Medicamentos_Posologia($vectorMF[$i][codigo_producto], $vectorMF[$i][tipo_opcion_posologia_id], $vectorMF[$i][evolucion_id]);

//pintar formula para opcion 1
                    if($vectorMF[$i][tipo_opcion_posologia_id]== 1)
                    {
                         $this->salida.="  <td align=\"left\" width=\"50%\">cada ".$vector_posologia[0][periocidad_id]." ".$vector_posologia[0][tiempo]."</td>";
                    }

//pintar formula para opcion 2
                    if($vectorMF[$i][tipo_opcion_posologia_id]== 2)
                    {
                         $this->salida.="  <td align=\"left\" width=\"50%\">".$vector_posologia[0][descripcion]."</td>";
                    }

//pintar formula para opcion 3
                    if($vectorMF[$i][tipo_opcion_posologia_id]== 3)
                    {
                         $momento = '';
                         if($vector_posologia[0][sw_estado_momento]== '1')
                         {
                              $momento = 'antes de ';
                         }
                         else
                         {
                              if($vector_posologia[0][sw_estado_momento]== '2')
                              {
                                   $momento = 'durante ';
                              }
                              else
                              {
                                   if($vector_posologia[0][sw_estado_momento]== '3')
                                        {
                                             $momento = 'despues de ';
                                        }
                              }
                         }
                         $Cen = $Alm = $Des= '';
                         $cont= 0;
                         $conector = '  ';
                         $conector1 = '  ';
                         if($vector_posologia[0][sw_estado_desayuno]== '1')
                         {
                              $Des = $momento.'el Desayuno';
                              $cont++;
                         }
                              if($vector_posologia[0][sw_estado_almuerzo]== '1')
                              {
                                   $Alm = $momento.'el Almuerzo';
                                   $cont++;
                              }
                              if($vector_posologia[0][sw_estado_cena]== '1')
                              {
                                   $Cen = $momento.'la Cena';
                                   $cont++;
                              }
                              if ($cont== 2)
                              {
                                   $conector = ' y ';
                                   $conector1 = '  ';
                              }
                              if ($cont== 1)
                              {
                                   $conector = '  ';
                                   $conector1 = '  ';
                              }
                              if ($cont== 3)
                              {
                                   $conector = ' , ';
                                   $conector1 = ' y ';
                              }
                              $this->salida.="  <td align=\"left\" width=\"50%\">".$Des."".$conector."".$Alm."".$conector1."".$Cen."</td>";
                    }

//pintar formula para opcion 4
                    if($vectorMF[$i][tipo_opcion_posologia_id]== 4)
                    {
                         $conector = '  ';
                         $frecuencia='';
                         $j=0;
                         foreach ($vector_posologia as $k => $v)
                         {
                              if ($j+1 ==sizeof($vector_posologia))
                              {
                                   $conector = '  ';
                              }
                              else
                              {
                                        if ($j+2 ==sizeof($vector_posologia))
                                             {
                                                  $conector = ' y ';
                                             }
                                        else
                                             {
                                                  $conector = ' - ';
                                             }
                              }
                              $frecuencia = $frecuencia.$k.$conector;
                              $j++;
                         }
                         $this->salida.="  <td align=\"left\" width=\"50%\">a la(s): $frecuencia</td>";
                    }

//pintar formula para opcion 5
                    if($vectorMF[$i][tipo_opcion_posologia_id]== 5)
                    {
                         $this->salida.="  <td align=\"left\" width=\"50%\">".$vector_posologia[0][frecuencia_suministro]."</td>";
                    }
                    $this->salida.="</tr>";

                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="  <td align=\"left\" width=\"9%\">Cantidad:</td>";
                    $e=$vectorMF[$i][cantidad]/floor($vectorMF[$i][cantidad]);
                    if ($vectorMF[$i][contenido_unidad_venta])
                    {
                         if($e==1)
                         {
                              $this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".floor($vectorMF[$i][cantidad])." ".$vectorMF[$i][descripcion]." por ".$vectorMF[$i][contenido_unidad_venta]."</td>";
                         }
                         else
                         {
                              $this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".$vectorMF[$i][cantidad]." ".$vectorMF[$i][descripcion]." por ".$vectorMF[$i][contenido_unidad_venta]."</td>";
                         }
                    }
                    else
                    {
                         if($e==1)
                         {
                              $this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".floor($vectorMF[$i][cantidad])." ".$vectorMF[$i][descripcion]."</td>";
                         }
                         else
                         {
                              $this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".$vectorMF[$i][cantidad]." ".$vectorMF[$i][descripcion]."</td>";
                         }
                    }
                    $this->salida.="</tr>";

                    $this->salida.="</table>";
                    $this->salida.="</td>";
                    $this->salida.="</tr>";
                    
                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="<td colspan = 2 class=\"$estilo\">";
                    $this->salida.="<table>";
                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="  <td align=\"left\" width=\"4%\">Observacion:</td>";
                    $this->salida.="  <td align=\"left\" width=\"69%\">".$vectorMF[$i][observacion]."</td>";
                    $this->salida.="<tr class=\"$estilo\">";

                    $this->salida.="</table>";

                    $this->salida.="</td>";
                    $this->salida.="</tr>";
                    
                    if (!empty($vectorMF[$i][nombre_tercero]))
	               {
                         $fechaf = $this->FechaStamp($vectorMF[$i][fecha_registro]);
                         $this->salida.="<tr class=\"$estilo\">";
                         $this->salida.="<td align=\"left\" width=\"50%\"><b>Formuló:&nbsp;&nbsp;</b>".$vectorMF[$i][nombre_tercero]."</td>";
                         $this->salida.="<td align=\"left\" width=\"20%\"><b>Fecha Finalización:&nbsp;&nbsp;</b>".$fechaf."</td>";
                         $this->salida.="</tr>";
  				}
               }
               $this->salida.="</table><br>";
          }
          else
          {
               $m = $m+1;
          }

               
          //**pintar los medicamentos suspendidos
          $vectorMS = $this->Consulta_Solicitud_Medicamentos_Suspendidos();
          if ($vectorMS)
          {
               $this->salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";
               $this->salida.="<tr class=\"modulo_table_title\">";
               
               $this->salida.="<td align=\"center\" colspan=\"3\">MEDICAMENTOS SUSPENDIDOS</td>";
               
               $this->salida.="</tr>";

               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="  <td width=\"7%\">CODIGO</td>";
               $this->salida.="  <td width=\"30%\">PRODUCTO</td>";
               $this->salida.="  <td width=\"29%\">PRINCIPIO ACTIVO</td>";

               $this->salida.="</tr>";

               for($i=0;$i<sizeof($vectorMS);$i++)
               {
                    if( $i % 2){ $estilo='modulo_list_claro';}
                    else {$estilo='modulo_list_oscuro';}
                    
                    $this->salida.="<tr class=\"$estilo\">";
                    if($$vectorMS[$i][item] == 'NO POS')
                    {
                         $this->salida.=" <td ROWSPAN = 4 align=\"center\" width=\"7%\">".$vectorMS[$i][codigo_producto]."<BR>NO_POS</td>";
                    }
                    else
                    {
                         $this->salida.="  <td ROWSPAN = 4 align=\"center\" width=\"7%\">".$vectorMS[$i][codigo_producto]."</td>";
                    }
                    //LINEA ALTERADA para ver la evolucion
                    $this->salida.="  <td align=\"center\" width=\"30%\">".$vectorMS[$i][producto]."-".$vectorMS[$i][evolucion_id]."</td>";
                    $this->salida.="  <td align=\"left\" width=\"29%\">".$vectorMS[$i][principio_activo]."</td>";
                    //fin del validador
                    $this->salida.="</tr>";
                    
                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="<td colspan = 2>";
                    $this->salida.="<table>";

                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="  <td colspan = 3 align=\"left\" width=\"9%\">Via de Administracion: ".$vectorMS[$i][via]."</td>";
                    $this->salida.="</tr>";

                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="  <td align=\"left\" width=\"9%\">Dosis:</td>";
                    $e=$vectorMS[$i][dosis]/floor($vectorMS[$i][dosis]);
                    if($e==1)
                    {
                         $this->salida.="  <td align=\"left\" width=\"14%\">".floor($vectorMS[$i][dosis])."  ".$vectorMS[$i][unidad_dosificacion]."</td>";
                    }
                    else
                    {
                         $this->salida.="  <td align=\"left\" width=\"14%\">".$vectorMS[$i][dosis]."  ".$vectorMS[$i][unidad_dosificacion]."</td>";
                    }

                    $vector_posologia= $this->Consulta_Solicitud_Medicamentos_Posologia($vectorMS[$i][codigo_producto], $vectorMS[$i][tipo_opcion_posologia_id], $vectorMS[$i][evolucion_id]);

//pintar formula para opcion 1
                    if($vectorMS[$i][tipo_opcion_posologia_id]== 1)
                    {
                         $this->salida.="  <td align=\"left\" width=\"50%\">cada ".$vector_posologia[0][periocidad_id]." ".$vector_posologia[0][tiempo]."</td>";
                    }

//pintar formula para opcion 2
                    if($vectorMS[$i][tipo_opcion_posologia_id]== 2)
                    {
                         $this->salida.="  <td align=\"left\" width=\"50%\">".$vector_posologia[0][descripcion]."</td>";
                    }

//pintar formula para opcion 3
                    if($vectorMS[$i][tipo_opcion_posologia_id]== 3)
                    {
                         $momento = '';
                         if($vector_posologia[0][sw_estado_momento]== '1')
                         {
                              $momento = 'antes de ';
                         }
                         else
                         {
                              if($vector_posologia[0][sw_estado_momento]== '2')
                              {
                                   $momento = 'durante ';
                              }
                              else
                              {
                                   if($vector_posologia[0][sw_estado_momento]== '3')
                                        {
                                             $momento = 'despues de ';
                                        }
                              }
                         }
                         $Cen = $Alm = $Des= '';
                         $cont= 0;
                         $conector = '  ';
                         $conector1 = '  ';
                         if($vector_posologia[0][sw_estado_desayuno]== '1')
                         {
                              $Des = $momento.'el Desayuno';
                              $cont++;
                         }
                              if($vector_posologia[0][sw_estado_almuerzo]== '1')
                              {
                                   $Alm = $momento.'el Almuerzo';
                                   $cont++;
                              }
                              if($vector_posologia[0][sw_estado_cena]== '1')
                              {
                                   $Cen = $momento.'la Cena';
                                   $cont++;
                              }
                              if ($cont== 2)
                              {
                                   $conector = ' y ';
                                   $conector1 = '  ';
                              }
                              if ($cont== 1)
                              {
                                   $conector = '  ';
                                   $conector1 = '  ';
                              }
                              if ($cont== 3)
                              {
                                   $conector = ' , ';
                                   $conector1 = ' y ';
                              }
                              $this->salida.="  <td align=\"left\" width=\"50%\">".$Des."".$conector."".$Alm."".$conector1."".$Cen."</td>";
                    }

//pintar formula para opcion 4
                    if($vectorMS[$i][tipo_opcion_posologia_id]== 4)
                    {
                         $conector = '  ';
                         $frecuencia='';
                         $j=0;
                         foreach ($vector_posologia as $k => $v)
                         {
                              if ($j+1 ==sizeof($vector_posologia))
                              {
                                   $conector = '  ';
                              }
                              else
                              {
                                        if ($j+2 ==sizeof($vector_posologia))
                                             {
                                                  $conector = ' y ';
                                             }
                                        else
                                             {
                                                  $conector = ' - ';
                                             }
                              }
                              $frecuencia = $frecuencia.$k.$conector;
                              $j++;
                         }
                         $this->salida.="  <td align=\"left\" width=\"50%\">a la(s): $frecuencia</td>";
                    }

//pintar formula para opcion 5
                    if($vectorMS[$i][tipo_opcion_posologia_id]== 5)
                    {
                         $this->salida.="  <td align=\"left\" width=\"50%\">".$vector_posologia[0][frecuencia_suministro]."</td>";
                    }
                    $this->salida.="</tr>";

                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="  <td align=\"left\" width=\"9%\">Cantidad:</td>";
                    $e=$vectorMS[$i][cantidad]/floor($vectorMS[$i][cantidad]);
                    if ($vectorMS[$i][contenido_unidad_venta])
                    {
                         if($e==1)
                         {
                              $this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".floor($vectorMS[$i][cantidad])." ".$vectorMS[$i][descripcion]." por ".$vectorMS[$i][contenido_unidad_venta]."</td>";
                         }
                         else
                         {
                              $this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".$vectorMS[$i][cantidad]." ".$vectorMS[$i][descripcion]." por ".$vectorMS[$i][contenido_unidad_venta]."</td>";
                         }
                    }
                    else
                    {
                         if($e==1)
                         {
                              $this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".floor($vectorMS[$i][cantidad])." ".$vectorMS[$i][descripcion]."</td>";
                         }
                         else
                         {
                              $this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".$vectorMS[$i][cantidad]." ".$vectorMS[$i][descripcion]."</td>";
                         }
                    }
                    $this->salida.="</tr>";

                    $this->salida.="</table>";
                    $this->salida.="</td>";
                    $this->salida.="</tr>";
                    
                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="<td colspan = 2 class=\"$estilo\">";
                    $this->salida.="<table>";
                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="  <td align=\"left\" width=\"4%\">Observacion:</td>";
                    $this->salida.="  <td align=\"left\" width=\"69%\">".$vectorMS[$i][observacion]."</td>";
                    $this->salida.="<tr class=\"$estilo\">";

                    $this->salida.="</table>";

                    $this->salida.="</td>";
                    $this->salida.="</tr>";
                    
                    if (!empty($vectorMS[$i][nombre_tercero]))
	               {
                         $fechaf = $this->FechaStamp($vectorMS[$i][fecha_registro]);
                         $this->salida.="<tr class=\"$estilo\">";
                         $this->salida.="<td align=\"left\" width=\"50%\"><b>Formuló:&nbsp;&nbsp;</b>".$vectorMS[$i][nombre_tercero]."</td>";
                         $this->salida.="<td align=\"left\" width=\"20%\"><b>Fecha Suspensión:&nbsp;&nbsp;</b>".$fechaf."</td>";
                         $this->salida.="</tr>";
  				}
               }
               $this->salida.="</table><br>";
          }
          else
          {
               $m = $m+1;
          }
          //fin de mediacamentos finalizados
          $this->salida .= "</form>";

          if ($_SESSION['PROFESIONAL'.$pfj]!=1)
          {
               if($m==2)
               {
                    $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida.="  <td align=\"center\" width=\"7%\">EL PACIENTE NO TIENE MEDICAMENTOS FORMULADOS</td>";
                    $this->salida.="</tr>";
                    $this->salida.="</table><br>";
               }
          }
          return true;
     }//End function
         
     function Dosis_Suministradas($vectorI,$flag)
     {
          $pfj=$this->frmPrefijo;
          if((empty($vectorI)) AND ($flag != 5))
          {
	          $vectorI = $this->Consulta_Todos_Medicamentos();
          }
          for($j=0; $j<sizeof($vectorI); $j++)
          {
               if($vectorI[$j][evolucion_id] != $vectorI[$j-1][evolucion_id] OR $vectorI[$j][codigo_producto] != $vectorI[$j-1][codigo_producto])
               {
               	$control = $this->Consultar_Control_Suministro($vectorI[$j][codigo_producto], $vectorI[$j][evolucion_id]);
     
                    if(!empty($control))
                    {
                         $salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";
                         $salida.="<tr class=\"modulo_table_title\">";
                         $salida.="  <td align=\"left\" colspan=\"5\">DOSIS SUMINISTRADAS DEL MEDICAMENTO:  ( ".$vectorI[$j][codigo_producto]." )  -   ".$vectorI[$j][producto]."</td>";
                         $salida.="</tr>";
                         
                         if($flag != 5)
                         {
                              $salida.="<tr class=\"hc_table_submodulo_list_title\">";
                              $salida.="<td align=\"center\" colspan=\"3\">EVOLUCION:  ".$vectorI[$j][evolucion_id]."</td>";
                              
                              if($vectorI[$j][sw_estado] == '1')
                              {$estado = "ACTIVO";}
                              elseif($vectorI[$j][sw_estado] == '2')
                              {$estado = "SUSPENDIDO";}
                              elseif($vectorI[$j][sw_estado] == '9')
                              {$estado = "REENVIADO";}
                              elseif($vectorI[$j][sw_estado] == '0')
                              {$estado = "FINALIZADO";}
                              elseif($vectorI[$j][sw_estado] == '8')
                              {$estado = "FINALIZADO DESDE LA ESTACION";}
                              $salida.="<td align=\"center\" colspan=\"2\">NOTA DE ESTADO:  ".$estado."</td>";
                              $salida.="</tr>";
                         }
                         
                         $salida.="<tr class=\"hc_table_submodulo_list_title\">";
                         $salida.="  <td align=\"center\" width=\"5%\">FECHA</td>";
                         $salida.="  <td align=\"center\" width=\"5%\">HORA</td>";
                         $salida.="  <td align=\"center\" width=\"15%\">CANTIDAD</td>";
                         $salida.="  <td align=\"center\" width=\"20%\">USUARIO</td>";
                         $salida.="  <td align=\"center\" width=\"35%\">OBSERVACION DEL SUMINISTRO</td>";
                         $salida.="</tr>";
                         $total_suministro=0;
                         for($i=0;$i<sizeof($control);$i++){
                              if( $i % 2){ $estilo='modulo_list_claro';}
                              else {$estilo='modulo_list_oscuro';}
                              $salida.="<tr class=\"$estilo\">";
                              $salida.="  <td align=\"center\" width=\"5%\">".$this->FechaStamp($control[$i][fecha_realizado])."</td>";
                              $salida.="  <td align=\"center\" width=\"5%\">".$this->HoraStamp($control[$i][fecha_realizado])."</td>";
                              $salida.="  <td align=\"center\" width=\"15%\">".$control[$i][cantidad_suministrada]."&nbsp;".$vectorI[$j][unidad_dosificacion]."</td>";//$_SESSION['CABECERA_CONTROL'.$pfj]
                              if ($control[$i][nombre] != NULL)
                              {
                                   $salida.="  <td align=\"left\" width=\"20%\">".$control[$i][nombre]."</td>";
                              }
                              else
                              {
                                   $salida.="  <td align=\"left\" width=\"20%\">".$control[$i][nombre_usuario]."</td>";
                              }
                              $salida.="  <td align=\"left\" width=\"35%\">".$control[$i][observacion]."</td>";
                              $salida.="</tr>";
                              $cantidad_suministrada = $control[$i][cantidad_suministrada];
                              $total_suministro = $total_suministro + $cantidad_suministrada;
                         }
                         $salida.="<tr class=\"hc_table_submodulo_list_title\">";
                         $salida.="  <td colspan = \"3\" align=\"center\" width=\"10%\">TOTAL SUMINISTRADO:</td>";
                         $salida.="  <td width=\"15%\" align=\"center\" colspan = \"2\">".$total_suministro."&nbsp;".$vectorI[$j][unidad_dosificacion]."</td>";//$_SESSION['CABECERA_CONTROL'.$pfj]
                         //$salida.="  <td colspan = 2 width=\"55%\">&nbsp;</td>";
                         $salida.="</tr>";
                         $salida.="</table><br>";
                    }
               }
          }
          if(($flag == 1) OR ($flag == 5))
          {
          	$this->salida.= $salida;
          }
          elseif($flag == 0)
          {
          	return $salida;
          }
	}
     

     /**
     * Consulta de los medicamentos de la canasta de Cirugia.
     * Pinta los medicamentos suministrados durante una cirugia realizada al paciente.
     */     
     function frmConsultaCanastaMedica($flag)
     {
          $medicamentos = $this->ConsultaCanastaMedica();
          if (!empty ($medicamentos))
          {
               $salida2 .="<br><table width=\"100%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
               $salida2 .="<tr class=\"hc_table_submodulo_list_title\">";
               $salida2 .="<td align=\"center\"colspan=\"6\">SUMINISTRO DE MEDICAMENTOS (CANASTA DE CIRUGIA)</td>";
               $salida2 .="</tr>";
               
               $salida2 .="<tr class=\"hc_table_submodulo_list_title\">";
               $salida2 .="<td align=\"center\">Fecha y hora</td>";
               $salida2 .="<td align=\"center\">Codigo Med.</td>";
               $salida2 .="<td align=\"center\">Nombre Med.</td>";
               $salida2 .="<td align=\"center\">Cantidad</td>";
               $salida2 .="<td align=\"center\">Usuario Orden</td>";
               $salida2 .="<td align=\"center\">Usuario Suministro</td>";
               $salida2 .="</tr>";
               for($i=0; $i<sizeof($medicamentos); $i++)
               {
                    if( $i % 2){ $estilo='modulo_list_claro';}
                         else {$estilo='modulo_list_oscuro';}
                    $salida2 .="<tr class=\"$estilo\">";
                    $fecha = $this->FechaStamp($medicamentos[$i][fecha_registro]);
                    $hora = $this->HoraStamp($medicamentos[$i][fecha_registro]);
                    $salida2 .="<td align=\"center\">".$fecha." - ".$hora."</td>";
                    $salida2 .="<td align=\"center\">".$medicamentos[$i][codigo_producto]."</td>";
                    $salida2 .="<td align=\"center\">".$medicamentos[$i][descripcion]."</td>";
                    $salida2 .="<td align=\"center\">".ceil($medicamentos[$i][cantidad_suministrada])."</td>";
                    $salida2 .="<td align=\"center\">".$medicamentos[$i][us_orden]."</td>";
                    $salida2 .="<td align=\"center\">".$medicamentos[$i][us_suministro]."</td>";
                    $salida2 .="</tr>";
                    if($medicamentos[$i][observacion] != $medicamentos[$i+1][observacion])
                    {
                         $salida2 .="<tr>";
                         $salida2 .="<td align=\"center\" class=\"hc_table_submodulo_list_title\">OBSERVACIONES</td>";
                         $salida2 .="<td align=\"justify\" class=\"$estilo\" colspan=\"5\">".$medicamentos[$i][observacion]."</td>";
                         $salida2 .="</tr>";
                    }
               }
               $salida2.="</table><br>";
          }
          if($flag == 1)
          {
          	$this->salida.= $salida2;
          }
          else
          {
			return $salida2;
          }
     }

}//End class
?>
