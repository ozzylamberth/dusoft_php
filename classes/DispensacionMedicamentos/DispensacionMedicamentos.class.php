<?php

/**
 * $Id: DispensacionMedicamentos.class.php,v 1.10
 */

/**
 * Clase para la dispensacion o despacho de Medicamentos
 *
 * @author Sandra Viviana Pantoja Torres
 * @version 1.0
 * @package SIIS
 */
class DispensacionMedicamentos extends ConexionBD {
    /*
     * Constructor de la clase
     */

    function DispensacionMedicamentos() {
        
    }

    /*
     * Funcion donde se crea un menu para la dispensacion normal.
     */

    function MenuOpcion($opcion, $empre, $bodega, $ParametrizacionReformular, $observacion, $plan_id, $evolucion, $todo_pendiente) {
        /* $lista=$this->Lista_Plan_idConsul($plan_id);
          $lista_id=$lista['lista_precios']; */
    
      /*  echo "<pre> -Opcion: ";
        print_r($opcion);
        echo "<br/> -Empre: ";
        print_r($empre);
        echo "<br/> -Bodega: ";
        print_r($bodega);
        echo "<br/> -Reformular: ";
        print_r($ParametrizacionReformular);
        echo "<br/> -Observacion: ";
        print_r($observacion);
        echo "<br/> -Plan ID: ";
        print_r($plan_id);
        echo "<br/> -Evolucion ID: ";
        print_r($evolucion);
        echo "<br/> -Todo PDTE: ";
        print_r($todo_pendiente);
        echo "</pre>";
        exit(); */

        switch ($opcion) {
            case '1':
                $totalcosto = 0;
                $bodegas_doc_id = ModuloGetVar('app', 'Dispensacion', 'documento_dispensacion_' . trim($empre) . '_' . trim($bodega));
                $temporales = $this->Buscar_producto_tmp_normal($evolucion);

                /*echo "<pre> -Total Costo: ";
                print_r($totalcosto);
                echo "<br/> -Bodegas DOC ID: ";
                print_r($bodegas_doc_id);
                echo "<br/> -Temporales: ";
                print_r($temporales);
                echo "</pre>";
                exit();*/

                if (!empty($temporales)) {
                    $datos2 = $this->AsignarNumeroDocumentoDespacho_d($bodegas_doc_id);
                    $numeracion = $datos2['numeracion'];
					
					/**
					* Inserta bodegas_documentos
					**/
                    $sql = $this->IngresarInv_Bodegas_documentos($bodegas_doc_id, $observacion, $numeracion,0);
					
					/**
					* Inserta hc_formulacion_despachos_medicamentos
					**/
                    $sql .= $this->GuardarHC_formulacion_despachos_medicamentos($evolucion, $bodegas_doc_id, $numeracion, $persona_reclama, $persona_reclama_tipo_id, $persona_reclama_id);
                }


                $totalcosto = 0;
                $pactado = " ";
                $ind = 0;
                $indice1 = 0;
	
			   /**
				 *
				 *Actualiza existencias_bodegas_lotes_fv, 
				 *Actualiza existencias_bodegas
				 *Inserta   bodegas_documentos_d
				 *
				 **/
                $sql .=$this->Guardar_Inv_bodegas_documento_d($temporales, $bodegas_doc_id, $numeracion, $empre, $pactado = null, $plan_id);

                /*echo "<pre> -Datos2: ";
                print_r($datos2);
                echo "<br/> -Numeracion: ";
                print_r($numeracion);
                echo "<br/> -SQL: ";   
                print_r($sql);  
                echo "</pre>";
                exit();*/

                $temporales_2 = $this->Buscar_producto_TMP_NoPend($evolucion);

                //echo "<pre> -Temporales2: ";
                //print_r($temporales_2);
                //exit();

                if (!empty($temporales_2) || $todo_pendiente == '1') {
                    $pendientes = $this->Medicamentos_PendientesNormal($evolucion);

                   /* echo "<pre> -Pendientes: ";
                    print_r($pendientes);
                    exit();*/

                    foreach ($pendientes as $k1 => $detalle) {
                        $total_pendiente = $detalle['total'];
                        if ($total_pendiente != 0) {
						
							/**
							* Inserta hc_pendientes_por_dispensar
							**/
                            $sql .= $this->Guardar_Pendientes_Dispensacion($evolucion, $detalle['codigo_producto'], $total_pendiente, $todo_pendiente);
                        }
                    }
                }

//                echo "<pre>";
//                print_r($sql);

             /*   echo "</pre>";
                exit();
                exit();*/
				
				/**
				* Elimina hc_dispensacion_medicamentos_tmp
				**/
                $dat = $this->Eliminar_tmp_Dispensacion($evolucion, $sql);

                break;               
            case '2':
				
				$consultaProductoTodoPendiente = $this->BuscarProductosTodoPendiente($evolucion);	                  

				$bodegasDocTodoPendiente;
				if (!empty($consultaProductoTodoPendiente)) {  
                    $bodegasDocTodoPendiente = 1;
                }else{
					$bodegasDocTodoPendiente = 0;
				}
				
				/*echo "<pre> ---> ".$bodegasDocTodoPendiente;
				print_r($consultaProductoTodoPendiente);
				exit();*/
				
                $totalcosto = 0;
                $bodegas_doc_id = ModuloGetVar('app', 'Dispensacion', 'documento_dispensacion_' . trim($empre) . '_' . trim($bodega));

                $datos2 = $this->AsignarNumeroDocumentoDespacho_d($bodegas_doc_id);
                $numeracion = $datos2['numeracion'];
				
                $temporales = $this->Buscar_producto_tmp_normal($evolucion);
                if (!empty($temporales)) {
                    $sql = $this->IngresarInv_Bodegas_documentos($bodegas_doc_id, $observacion, $numeracion,$bodegasDocTodoPendiente);
                }
                $totalcosto = 0;
                $pactado = " ";
                $ind = 0;
                $indice1 = 0; 

                $sql .=$this->Guardar_Inv_bodegas_documento_d($temporales, $bodegas_doc_id, $numeracion, $empre, $pactado = null, $plan_id);
               
                $sql .=$this->Update_producto_x_bodega($evolucion, $bodegas_doc_id, $numeracion);
                $sql .=$this->Guardar_Pendientes_Adicional_Dispensacion($evolucion, $bodegas_doc_id, $numeracion,$bodegasDocTodoPendiente);

                $temporales_2 = $this->Buscar_producto_tmp_normal($evolucion);
				
                if (!empty($temporales_2)) {
                    $pendientes = $this->Medicamentos_Pendientes_sin_dispensar($evolucion);
					
                    foreach ($pendientes as $k1 => $detalle) {
                        $total_pendiente = $detalle['total'];
					
							$sql .=$this->Guardar_Pendientes_Dispensacion_($evolucion, $detalle['codigo_producto'], $total_pendiente);
						
                    }
                }
                $dat = $this->Eliminar_tmp_Dispensacion($evolucion, $sql);
                break;
            default:
                break;
        }
    }

    /*
     * Funcion donde se crea un menu para la dispensacion de la ESM.
     */

    function MenuOpcion_Esm($opcion, $empre, $bodega, $ParametrizacionReformular, $observacion, $dats_productos_dis, $Cabecera_Formulacion, $plan_id, $formula_id, $medi_form, $todo_pendiente) {
        $lista = $this->Lista_Plan_idConsul($plan_id);
        $lista_id = $lista['lista_precios'];
        switch ($opcion) {
            case '1':

                $desp = AutoCarga::factory('ESM');
                $bodegas = $this->Consultar_Bodegas_despacho($formula_id);
                $totalcosto = 0;
                $porcentaje = ModuloGetVar('', '', 'ESM_PorcentajeIntermediacion');
                foreach ($bodegas as $k1 => $bodegad) {
                    $bodegas_doc_id = ModuloGetVar('app', 'DispensacionESM', 'documento_dispensacion_' . trim($empre) . '_' . trim($bodegad['bodega']));

                    $datos2 = $this->AsignarNumeroDocumentoDespacho_d($bodegas_doc_id);
                    $numeracion = $datos2['numeracion'];

                    $temporales = $this->Buscar_producto_tmp_conc($formula_id, $bodegad['bodega']);
                    $inv_bodegas = $this->IngresarInv_Bodegas_documentos($bodegas_doc_id, $observacion, $numeracion,0);
                    $info = $this->Guardaresm_formulacion_despachos_medicamentos($formula_id, $bodegas_doc_id, $numeracion, $persona_reclama, $persona_reclama_tipo_id, $persona_reclama_id);

                    $totalcosto = 0;
                    $pactado = " ";
                    $ind = 0;
                    $indice1 = 0;
                    foreach ($temporales as $k1 => $dt1) {
                        $info_pac = $this->ConsultarListaDetalle($lista_id, $empre, $dt1['codigo_producto']);
                        if (!empty($info_pac)) {
                            $pactado = '1';
                        } else {
                            $pactado = '0';
                        }
                        $datos = $this->precio_producto_plan($plan_id, $dt1['codigo_producto'], $empre, $bodegad['sw_bodegamindefensa'], '0');
                        $costo_producto+=$dt1['cantidad_despachada'] * $datos['pre'];

                        $detalle = $this->Guardar_Inv_bodegas_documento_d($dt1['codigo_producto'], $dt1['cantidad_despachada'], $costo_producto, $bodegas_doc_id, $numeracion, $empre, $dt1['fecha_vencimiento'], $dt1['lote'], $pactado, $dt1['empresa_id'], $dt1['centro_utilidad'], $dt1['bodega']);
                        if (!$detalle) {
                            $array_pendiente[$ind]['codigo_producto'] = $dt1['codigo_producto'];
                            $array_pendiente[$ind]['cantidad'] = $dt1['cantidad_despachada'];
                        }
                        $informacion_medicamento = $this->Medicamentos_Formulados_R($dt1['codigo_producto'], $formula_id);
                        if ($informacion_medicamento['sw_ambulatoria'] == '0') {
                            if ($informacion_medicamento['sw_durante_tratamiento'] == '1') {
                                $Conversion = $this->ConsultarFactorConversion($informacion_medicamento['codigo_producto']);
                                $factor_conversion = $Conversion['0']['factor_conversion'];
                                if (empty($factor_conversion)) {
                                    $factor_conversion = '1';
                                }
                                $cantidad_e = $informacion_medicamento['cantidad'];
                                $Entregar = ($cantidad_e / $factor_conversion);
                                $CantidaEntregar = intval($Entregar);
                            } else {
                                $cantidad_veces = round($informacion_medicamento['cantidad_veces']);
                                $dosisA = $informacion_medicamento['dosis'];
                                $entrega_diaria = $cantidad_veces * $dosisA;
                                $periodicidad_entrega = $informacion_medicamento['periodicidad_entrega'];
                                $unidad_periodicidad_entrega = $informacion_medicamento['unidad_periodicidad_entrega'];

                                if ($unidad_periodicidad_entrega == '1') {
                                    $dias_s = $periodicidad_entrega * 365;
                                }
                                if ($unidad_periodicidad_entrega == '2') {

                                    $dias_s = $periodicidad_entrega * 30;
                                }
                                if ($unidad_periodicidad_entrega == '3') {
                                    $dias_s = $periodicidad_entrega * 7;
                                }
                                if ($unidad_periodicidad_entrega == '4') {
                                    $dias_s = $periodicidad_entrega * 1;
                                }
                                $cantidad_e = $entrega_diaria * $dias_s;
                                $Conversion = $this->ConsultarFactorConversion($informacion_medicamento['codigo_producto']);
                                $unidad_dosif = $Conversion['0']['unidad_dosificacion'];
                                $factor_conversion = $Conversion['0']['factor_conversion'];
                                $Entregar = ($cantidad_e / $factor_conversion);
                                $CantidaEntregar = intval($Entregar);
                                $unidad_periodicidad_entrega = $informacion_medicamento['unidad_periodicidad_entrega'];
                                $periodicidad_entrega = $informacion_medicamento['periodicidad_entrega'];

                                if ($unidad_periodicidad_entrega == '1') {
                                    $dias_s = $periodicidad_entrega * 365;
                                }
                                if ($unidad_periodicidad_entrega == '2') {
                                    $dias_s = $periodicidad_entrega * 30;
                                }
                                if ($unidad_periodicidad_entrega == '3') {

                                    $dias_s = $periodicidad_entrega * 7;
                                }
                                if ($unidad_periodicidad_entrega == '4') {

                                    $dias_s = $periodicidad_entrega * 1;
                                }

                                $dias_total = $dias_s + $ParametrizacionReformular;
                                $fecha_entrega = date('Y-m-d');
                                list($year, $month, $day) = explode("-", $fecha_entrega);
                                $fecha_entrega_numeros = mktime(0, 0, 0, $month, $day, $year);
                                $fecha_proxima_entrga = date("Y-m-d", strtotime("$fecha_entrega + $dias_total days"));
                                $this->UpdateFechas_x_medicamento($formula_id, $fecha_entrega, $fecha_proxima_entrga, $dt1['codigo_producto']);
                            }
                            $update_catidad_pe = $this->UpdateCantidad_Por_Periodo($formula_id, $CantidaEntregar, $dt1['codigo_producto']);
                            $array_periodo_producto[$indice1]['codigo_producto'] = $dt1['codigo_producto'];
                            $array_periodo_producto[$indice1]['cantidad'] = $CantidaEntregar;
                        }
                        $totalcosto = $totalcosto + $costo_producto;
                        $ind++;
                        $indice1++;
                    }
                    $actuInv_Bodegas = $this->UpdateCostos($bodegas_doc_id, $numeracion, $totalcosto);
                    $datos_esm = $this->ConsultarEsm_x_formula($formula_id);
                    $Datopciones = $desp->Menu(1, $totalcosto, $datos_esm['esm_tipo_id_tercero'], $datos_esm['esm_tercero_id']);
                }
                $temporales_2 = $this->Buscar_producto_tmp_no_pend($formula_id);
                if (!empty($temporales_2) || $todo_pendiente == '1') {
                    $pendientes = $this->Medicamentos_Pendientes__($formula_id);
                    if (!empty($array_periodo_producto)) {
                        $pendientes = $this->Medicamentos_Pendientes__H($formula_id);
                        foreach ($pendientes as $k1 => $detalle) {
                            $total_pendiente = $detalle['total'];
                            if ($total_pendiente != 0) {
                                $this->Guardar_Pendientes($formula_id, $detalle['codigo_producto'], $total_pendiente);
                            }
                        }
                    } else {
                        foreach ($pendientes as $k1 => $detalle) {
                            $total_pendiente = $detalle['total'];
                            if ($total_pendiente != 0) {
                                $this->Guardar_Pendientes($formula_id, $detalle['codigo_producto'], $total_pendiente);
                            }
                        }
                    }
                }
                $dat = $this->EliminarTodoTemporal_ESM($formula_id);

                break;
            case '2':

                $desp = AutoCarga::factory('ESM');
                $porcentaje = ModuloGetVar('', '', 'ESM_PorcentajeIntermediacion');

                $bodegas = $this->Consultar_Bodegas_despacho($formula_id);
                $totalcosto = 0;
                foreach ($bodegas as $k1 => $bodegad) {
                    $bodegas_doc_id = ModuloGetVar('app', 'DispensacionESM', 'documento_dispensacion_' . trim($empre) . '_' . trim($bodegad['bodega']));
                    $datos2 = $this->AsignarNumeroDocumentoDespacho_d($bodegas_doc_id);
                    $numeracion = $datos2['numeracion'];
                    $temporales = $this->Buscar_producto_tmp_conc($formula_id, $bodegad['bodega']);
                    $inv_bodegas = $this->IngresarInv_Bodegas_documentos($bodegas_doc_id, $observacion, $numeracion,0);
                    $totalcosto = 0;
                    $pactado = " ";
                    $ind = 0;
                    foreach ($temporales as $k1 => $dt1) {
                        $info_pac = $this->ConsultarListaDetalle($lista_id, $empre, $dt1['codigo_producto']);
                        if (!empty($info_pac)) {
                            $pactado = '1';
                        } else {
                            $pactado = '0';
                        }
                        $datos = $this->precio_producto_plan($plan_id, $dt1['codigo_producto'], $empre, $bodegad['sw_bodegamindefensa'], $dt1['sw_entregado_off']);
                        $costo_producto+=$dt1['cantidad_despachada'] * $datos['pre'];
                        $detalle = $this->Guardar_Inv_bodegas_documento_d($dt1['codigo_producto'], $dt1['cantidad_despachada'], $costo_producto, $bodegas_doc_id, $numeracion, $empre, $dt1['fecha_vencimiento'], $dt1['lote'], $pactado, $dt1['empresa_id'], $dt1['centro_utilidad'], $dt1['bodega']);
                        if (!$detalle) {
                            $array_pendiente[$ind]['codigo_producto'] = $dt1['codigo_producto'];
                            $array_pendiente[$ind]['cantidad'] = $dt1['cantidad_despachada'];
                        }
                        $totalcosto = $totalcosto + $costo_producto;
                    }

                    $actuInv_Bodegas = $this->UpdateCostos($bodegas_doc_id, $numeracion, $totalcosto);
                    $this->UpdatePOR_producto_bodega($formula_id, $bodegas_doc_id, $numeracion);
                    $this->Guardar_Pendientes_Adicional($formula_id, $bodegas_doc_id, $numeracion);
                    $datos_esm = $this->ConsultarEsm_x_formula($formula_id);
                    $Datopciones = $desp->Menu(1, $totalcosto, $datos_esm['esm_tipo_id_tercero'], $datos_esm['esm_tercero_id']);
                }
                $pendientes = $this->Medicamentos_Pendientes_SinDespachar($formula_id);
                foreach ($pendientes as $k1 => $detalle) {
                    $total_pendiente = $detalle['total'];
                    $this->Guardar_Pendientes_($formula_id, $detalle['codigo_producto'], $total_pendiente);
                }

                $dat = $this->EliminarTodoTemporal_ESM($formula_id);
                break;

            default:
                break;
        }
    }

    /*
     * Funcion donde se crea un menu para la dispensacion de la formula externa 
     */

    function MenuOpcion_FormulacionExterna($opcion, $datos_empresa, $formula_id, $observacion, $plan_id, $formula_id_tmp, $medi_form, $todo_pendiente) {
        switch ($opcion) {
            case '1':

                $bodegas = $this->Consultar_Bodegas_despacho_FE($formula_id_tmp);

                foreach ($bodegas as $k1 => $bodegad) {

                    $bodegas_doc_id = ModuloGetVar('app', 'Formulacion_Externa', 'documento_dispensacion_' . trim($bodegad['empresa_id']) . '_' . trim($bodegad['bodega']));
                    $datos = $this->AsignarNumeroDocumentoDespacho_d($bodegas_doc_id);
                    $numeracion = $datos['numeracion'];

                    $sql .= $this->IngresarInv_Bodegas_documentos($bodegas_doc_id, $observacion, $numeracion,0);
                    $sql .=$this->Guardaresm_formulacion_despachos_medicamentos($formula_id, $bodegas_doc_id, $numeracion, '', '', '', $observacion);
                    $temporales = $this->Buscar_productoDipensar_tmp($formula_id_tmp, $bodegad['bodega']);

                    $totalcosto = 0;
                    $pactado = " ";
                    $ind = 0;

                    /* foreach($temporales as $k1 => $dt1)
                      { */
                    /* $datos=$this->precio_producto_plan($plan_id,$dt1['codigo_producto'],$datos_empresa['empresa_id'],'0','0');
                      $costo_producto+=$dt1['cantidad_despachada'] * $datos['pre']; */

                    /* $detalle=$this->Guardar_Inv_bodegas_documento_d($dt1['codigo_producto'],$dt1['cantidad_despachada'],$costo_producto,$bodegas_doc_id,$numeracion,$datos_empresa['empresa_id'],$dt1['fecha_vencimiento'],$dt1['lote'],$pactado=null,$dt1['empresa_id'],$dt1['centro_utilidad'],$dt1['bodega']); */
                    $sql .=$this->Guardar_Inv_bodegas_documento_d($temporales, $bodegas_doc_id, $numeracion, $datos_empresa['empresa_id'], $pactado = null, $plan_id);
                    /* if(!$detalle)
                      {
                      $array_pendiente[$ind]['codigo_producto']=$dt1['codigo_producto'];
                      $array_pendiente[$ind]['cantidad']=$dt1['cantidad_despachada'];

                      } */
                    /* $totalcosto=$totalcosto + $costo_producto;
                      $ind++; */

                    /* } */
                    /* $actuInv_Bodegas=$this->UpdateCostos($bodegas_doc_id,$numeracion,$totalcosto); */
                }
                $temporales_2 = $this->Buscar_Medicamentos_TemporalesCompletos($formula_id_tmp);
                $pendientes = $this->Medicamentos_Pendientes_NDespacho($formula_id, $formula_id_tmp);

                if (!empty($temporales_2) || $todo_pendiente == '1') {
                    foreach ($pendientes as $k1 => $detalle) {
                        $total_pendiente = $detalle['total'];
                        if ($total_pendiente != 0) {
                            $sql .= "INSERT INTO esm_pendientes_por_dispensar
								(
								esm_pendiente_dispensacion_id,
								formula_id,
								codigo_medicamento,
								cantidad,
								usuario_id,
								fecha_registro

								)
								VALUES
								(
								DEFAULT,
								" . $formula_id . ",
								'" . $detalle['codigo_producto'] . "',
								" . $total_pendiente . ",
								" . UserGetUID() . ",
								now()
								); ";
                            /* $this->Guardar_Pendientes($formula_id,$detalle['codigo_producto'],$total_pendiente); */
                        }
                    }
                } else {
                    foreach ($pendientes as $k1 => $detalle) {
                        $total_pendiente = $detalle['total'];
                        if ($total_pendiente != 0) {
                            $sql .= "INSERT INTO esm_pendientes_por_dispensar
								(
								esm_pendiente_dispensacion_id,
								formula_id,
								codigo_medicamento,
								cantidad,
								usuario_id,
								fecha_registro

								)
								VALUES
								(
								DEFAULT,
								" . $formula_id . ",
								'" . $detalle['codigo_producto'] . "',
								" . $total_pendiente . ",
								" . UserGetUID() . ",
								now()
								); ";
                            /* $this->Guardar_Pendientes($formula_id,$detalle['codigo_producto'],$total_pendiente); */
                        }
                    }
                }
                /* Eliminar Temporal y Ejecutar SQL de despacho */

                $dat = $this->EliminarTodoTemporal_FormulasM($formula_id_tmp, $sql);

                break;
            case '2':



                $bodegas = $this->Consultar_Bodegas_despacho($formula_id);
                $totalcosto = 0;

                foreach ($bodegas as $k1 => $bodegad) {
                    $bodegas_doc_id = ModuloGetVar('app', 'Formulacion_Externa', 'documento_dispensacion_' . trim($bodegad['empresa_id']) . '_' . trim($bodegad['bodega']));

                    $datos2 = $this->AsignarNumeroDocumentoDespacho_d($bodegas_doc_id);
                    $numeracion = $datos2['numeracion'];

                    $sql .= $this->IngresarInv_Bodegas_documentos($bodegas_doc_id, $observacion, $numeracion,0);

                    $temporales = $this->Buscar_producto_tmp_conc($formula_id, $bodegad['bodega']);

                    $totalcosto = 0;
                    $pactado = " ";
                    $ind = 0;
                    $sql .=$this->Guardar_Inv_bodegas_documento_d($temporales, $bodegas_doc_id, $numeracion, $datos_empresa['empresa_id'], $pactado = null, $plan_id);

                    /* foreach($temporales as $k1 => $dt1)
                      {

                      $datos=$this->precio_producto_plan($plan_id,$dt1['codigo_producto'],$datos_empresa['empresa_id'],'0','1');
                      $costo_producto+=$dt1['cantidad_despachada'] * $datos['pre'];

                      $detalle=$this->Guardar_Inv_bodegas_documento_d($dt1['codigo_producto'],$dt1['cantidad_despachada'],$costo_producto,$bodegas_doc_id,$numeracion,$datos_empresa['empresa_id'],$dt1['fecha_vencimiento'],$dt1['lote'],$pactado=null,$dt1['empresa_id'],$dt1['centro_utilidad'],$dt1['bodega']);

                      if(!$detalle)
                      {
                      $array_pendiente[$ind]['codigo_producto']=$dt1['codigo_producto'];
                      $array_pendiente[$ind]['cantidad']=$dt1['cantidad_despachada'];
                      }

                      $totalcosto=$totalcosto + $costo_producto;

                      } */
                    /* $actuInv_Bodegas=$this->UpdateCostos($bodegas_doc_id,$numeracion,$totalcosto); */

                    $sql .= $this->UpdatePOR_producto_bodega($formula_id, $bodegas_doc_id, $numeracion);
                    $sql .= $this->Guardar_Pendientes_Adicional($formula_id, $bodegas_doc_id, $numeracion);
                }
                $pendientes = $this->Medicamentos_Pendientes_SinDespachar($formula_id);


                foreach ($pendientes as $k2 => $detalle) {
                    $total_pendiente = $detalle['total'];
                    /* $this->Guardar_Pendientes_($formula_id,$detalle['codigo_producto'],$total_pendiente); */
                    $sql .= " update  esm_pendientes_por_dispensar
						set     sw_estado='1'
						WHERE   formula_id = '" . trim($formula_id) . "'
						AND     codigo_medicamento = '" . trim($detalle['codigo_producto']) . "';  ";

                    if ($total_pendiente != 0) {
                        $sql .= "INSERT INTO esm_pendientes_por_dispensar
							(
							esm_pendiente_dispensacion_id,
							formula_id,
							codigo_medicamento,
							cantidad,
							usuario_id,
							fecha_registro

							)
							VALUES
							(
							DEFAULT,
							" . trim($formula_id) . ",
							'" . trim($detalle['codigo_producto']) . "',
							" . $total_pendiente . ",
							" . UserGetUID() . ",
							now()
							); ";
                    }
                }

                $dat = $this->EliminarTodoTemporal_ESM($formula_id, $sql);
                break;

            default:
                break;
        }
    }

    /*
     * Funcion donde se consulta  el tipo de lista que tiene el paciente
     * @return array $datos vector con la informacion de los productos
     */

    function Lista_Plan_idConsul($plan) {

        $sql = " SELECT lista_precios
                 FROM   planes WHERE plan_id = '" . $plan . "' ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion para buscar el temporal por bodega 
     * @return array $datos vector con la informacion de los productos
     */

    function Buscar_producto_tmp_normal($evolucion) {

        $sql = " SELECT  hc_dispen_tmp_id,
                          evolucion_id,
                          empresa_id,
                          centro_utilidad,
                          bodega,
                          codigo_producto,
                          cantidad_despachada,
                          TO_CHAR(fecha_vencimiento,'DD-MM-YYYY') as fecha_vencimiento,
                          lote,
                          fc_descripcion_producto_alterno(codigo_producto) as descripcion_prod
                  FROM    hc_dispensacion_medicamentos_tmp
                  WHERE   evolucion_id = '" . $evolucion . "' ";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion para Asignar el numero de bodegas doc numeracion 
     * @return array $datos vector con la informacion de los productos
     */

    function AsignarNumeroDocumentoDespacho_d($bodegas_doc_id) {

        $sql = "BEGIN WORK;  LOCK TABLE bodegas_doc_numeraciones IN ROW EXCLUSIVE MODE ;";
        $sql .= "	COMMIT WORK; ";
        $sql.=" UPDATE bodegas_doc_numeraciones set numeracion=numeracion + 1
                          WHERE  bodegas_doc_id= " . trim($bodegas_doc_id) . " RETURNING numeracion;";
     //echo "<pre>".$sql;
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion donde se Ingresa la informacion a  la tabla inv_bodegas_documentos
     * @param string $observacion  cadena con la observacion del despacho
     * @param integer $numero  contiene el numero del documento
     * @return boolean.
     */

    function IngresarInv_Bodegas_documentos($bodegas_doc_id, $observacion, $numero,$estadoPendiente) {
        $sql = "INSERT INTO bodegas_documentos(bodegas_doc_id,numeracion,fecha,
                                                          total_costo,transaccion,observacion,
                                                          usuario_id,todo_pendiente,fecha_registro)
                                                           VALUES(
                                                           " . trim($bodegas_doc_id) . ",
                                                           " . trim($numero) . ",
                                                            now(),
                                                            0,
                                                            null,
                                                            '" . $observacion . "',
                                                            " . UserGetUID() . ",
                                                            '" . $estadoPendiente . "',
															now() ); ";

        return $sql;
    }

    /*
     * Funcion donde se Ingresan los medicamentos a depschar 
     * @return boolean.
     */

    function GuardarHC_formulacion_despachos_medicamentos($evolucion, $bodegas_doc_id, $numeracion, $persona_reclama, $persona_reclama_tipo_id, $persona_reclama_id) {

        /* $this->ConexionTransaccion(); */
        $sql = "INSERT INTO     hc_formulacion_despachos_medicamentos
                  (
                        hc_formulacion_despacho_id,
                        evolucion_id,
                        bodegas_doc_id,
                        numeracion
                         ";
        if ($persona_reclama_tipo_id != '' || $persona_reclama_id != '') {

            $sql .= "   ,persona_reclama,
                                 persona_reclama_tipo_id,
                                 persona_reclama_id ";
        }
        $sql .= "
            )VALUES
            (       DEFAULT,
                    " . $evolucion . ",
                    " . $bodegas_doc_id . ",
                    " . $numeracion . "
                      ";

        if ($persona_reclama_tipo_id != '' || $persona_reclama_id != '') {
            $sql .= " ,'" . $persona_reclama . "',
                        '" . $persona_reclama_tipo_id . "',
                    '" . $persona_reclama_id . "', ";
        }
        $sql .= "  ); ";
        /*
          if(!$rst = $this->ConexionTransaccion($sql))

          return false;

          $this->Commit(); */
        return $sql;
    }

    /*
     * Funcion donde se consulta  el precio por producto
     * @return array $datos vector con la informacion de los productos
     */

    function ConsultarListaDetalle($codigo_lista, $empresa_id, $codigo_producto) {

        $sql = "   SELECT precio
                        FROM   listas_precios_detalle
                        WHERE  empresa_id = '" . $empresa_id . "'
                        AND    codigo_producto = '" . $codigo_producto . "'
                        AND    codigo_lista = '" . $codigo_lista . "' ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion donde se el valor total del producto
     * @return array $datos vector con la informacion de los productos
     */

    function precio_producto_plan($plan_id, $medicamento, $empresa, $sw, $sw_tpend) {

        $sql = "   select fc_precio_producto_plan(" . trim($plan_id) . ",'" . trim($medicamento) . "','" . trim($empresa) . "','" . trim($sw) . "','" . trim($sw_tpend) . "') as pre;";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion donde se garda el detalle de los productos despachados
     * @return array $datos vector con la informacion de los productos
     */

    /* function Guardar_Inv_bodegas_documento_d($medicamentoDespachado,$cantidad,$costo,$bodegas_doc_id,$numeracion,$empresa,$fecha_v,$lote,$pactado,$empresa,$centro_utilidad,$bodega) */

    function Guardar_Inv_bodegas_documento_d($temporales, $bodegas_doc_id, $numeracion, $empresa_id, $pactado = null, $plan_id) {

        foreach ($temporales as $key => $valor) {
            list( $dia, $mes, $ano ) = split('[/.-]', $valor['fecha_vencimiento']);
            $fecha_v = $ano . "-" . $mes . "-" . $dia;



            $sql .="    update  existencias_bodegas_lote_fv
                  set     existencia_actual= existencia_actual - " . trim($valor['cantidad_despachada']) . "
                  WHERE   empresa_id = '" . trim($valor['empresa_id']) . "' AND
                          centro_utilidad = '" . trim($valor['centro_utilidad']) . "'
                  AND     codigo_producto = '" . trim($valor['codigo_producto']) . "'
                  AND     bodega = '" . trim($valor['bodega']) . "'
                  AND     fecha_vencimiento = '" . trim($fecha_v) . "'
                  AND     lote = '" . trim($valor['lote']) . "';   ";

            $sql .= "  update   existencias_bodegas
                   SET      existencia= existencia -" . trim($valor['cantidad_despachada']) . "
                   WHERE    empresa_id = '" . trim($valor['empresa_id']) . "' 
					AND 	centro_utilidad = '" . trim($valor['centro_utilidad']) . "'
                    AND     codigo_producto = '" . trim($valor['codigo_producto']) . "'
                    AND     bodega = '" . trim($valor['bodega']) . "' ;  ";

            $sql .= "INSERT INTO bodegas_documentos_d
                  (
                    consecutivo,
                    codigo_producto,
                    cantidad,
                    total_costo,
                    total_venta,
                    bodegas_doc_id,
                    numeracion,
                    fecha_vencimiento,
                    lote,
                    sw_pactado
                  )
                  VALUES
                  (
                     DEFAULT,
                    '" . trim($valor['codigo_producto']) . "',
                    " . trim($valor['cantidad_despachada']) . ",
                    (COALESCE(fc_precio_producto_plan('0','" . trim($valor['codigo_producto']) . "','" . trim($empresa_id) . "','0','0'),0)),
                    (COALESCE(fc_precio_producto_plan('" . trim($plan_id) . "','" . trim($valor['codigo_producto']) . "','" . trim($empresa_id) . "','0','0'),0)*" . $valor['cantidad_despachada'] . "),
                    " . trim($bodegas_doc_id) . ",
                    " . trim($numeracion) . " ,
                    '" . trim($fecha_v) . "' ,
                   '" . trim($valor['lote']) . "',
                    '1'
                  ); ";
        }
        /* if(!$rst = $this->ConexionTransaccion($sql))
          return false;
          $this->Commit(); */

        return $sql;
    }

    /*
     * Funcion donde se actualiza el costo del movimiento
     * @param string $totalcosto  cadena  con el valor total del costo
     * @param string $numeracion  cadena con el consecutivo del movimiento
     * @param integer $bodegas_doc_id id del documento de bodega
     * @return array con la informacion
     */

    function UpdateCostos($bodegas_doc_id, $numeracion, $totalcosto) {

        $sql = "    update bodegas_documentos
                    set    total_costo=" . $totalcosto . "
                    WHERE  bodegas_doc_id='" . $bodegas_doc_id . "'
                    AND    numeracion='" . $numeracion . "'  ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion donde se consulta los medicamentos que no han quedado pendientes
     * @param integer $evolucion evolucion del paciente
     * @return array con la informacion
     */

    function Buscar_producto_TMP_NoPend($evolucion) {

        $sql = " SELECT    hc_dispen_tmp_id,
                            evolucion_id,
                            empresa_id,
                            centro_utilidad,
                            bodega,
                            codigo_producto,
                            cantidad_despachada,
                            to_char(fecha_vencimiento,'dd-mm-yyyy')as fecha_vencimiento,
                            lote,
                            fc_descripcion_producto_alterno(codigo_producto) as descripcion_prod
                    FROM    hc_dispensacion_medicamentos_tmp
                    WHERE   evolucion_id = '" . $evolucion . "'  ";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion donde se consulta los medicamentos pendientes
     * @param integer $evolucion evolucion del paciente
     * @return array con la informacion
     */

    function Medicamentos_PendientesNormal($evolucion) {

        $sql = "  SELECT  a.codigo_producto,
                          (b.cantidades - a.cantidades) as total
                  from
                  (
                        SELECT codigo_formulado AS codigo_producto,
                              SUM(cantidad_despachada) as cantidades
                        FROM hc_dispensacion_medicamentos_tmp
                        where evolucion_id=" . $evolucion . "
                        group by  codigo_formulado
                  ) as a,
                  (
                  SELECT codigo_medicamento as codigo_producto,
                            SUM(cantidad_entrega) as cantidades
                            FROM hc_formulacion_antecedentes
                            where evolucion_id=" . $evolucion . "
                      group by codigo_medicamento
                  ) as b
                 where
                          a.codigo_producto = b.codigo_producto
                UNION
                    SELECT codigo_medicamento as codigo_producto,
                          cantidad_entrega as cantidades
                          FROM hc_formulacion_antecedentes
                          where evolucion_id=" . $evolucion . " and sw_mostrar='1'
                     and codigo_medicamento NOT IN( select
                                                      codigo_formulado
                                                      FROM hc_dispensacion_medicamentos_tmp
                                                      where evolucion_id=" . $evolucion . ")  ";
        
        /*echo "<pre>";
        print_r($sql);
        echo "</pre>";
        exit();*/
        
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion donde se guardan los medicamentos pendientes
     * @return boolean
     */

    function Guardar_Pendientes_Dispensacion($evolucion, $codigo_medicamento, $cantidad, $todo_pendiente) {
        /* $this->ConexionTransaccion(); */
        $sql = "INSERT INTO hc_pendientes_por_dispensar
                  (
                    hc_pendiente_dispensacion_id,
                    evolucion_id,
                    codigo_medicamento,
                    cantidad,
                    usuario_id,
					todo_pendiente,
                    fecha_registro					
                  )
                  VALUES
                  (
                     DEFAULT,
                    " . $evolucion . ",
                    '" . $codigo_medicamento . "',
                    " . $cantidad . ",
					" . UserGetUID() . ",
					" . $todo_pendiente . ",
					now()
                  ); ";
		
		//echo "sql ". $sql;
        /* if(!$rst = $this->ConexionTransaccion($sql))

          return false;

          $this->Commit();
          return true; */
        return $sql;
    }

    /*
     * Funcion se elimina el temporal de la dispensacion con evolucion
     * @return array con la informacion
     */

    function Eliminar_tmp_Dispensacion($evolucion, $query) {
        $this->ConexionTransaccion();
			//echo "TRANSACCION " . $query;
        $sql .= $query;
        $sql .= "                 DELETE FROM hc_dispensacion_medicamentos_tmp
                                        WHERE  evolucion_id ='" . $evolucion . "'; ";


	
        if (!$rst = $this->ConexionTransaccion($sql))
           return true;
        $this->Commit();
        return true;
    }

    /*
     * Funcion se actualiza el documento el documento relacionado con el pendiente que se ha despachado
     * @return array con la informacion
     */

    function Update_producto_x_bodega($evolucion, $bodegas_doc_id, $numeracion) {

        $sql = "    update  hc_pendientes_por_dispensar
		set     bodegas_doc_id='" . trim($bodegas_doc_id) . "',numeracion='" . trim($numeracion) . "'
		WHERE   evolucion_id = '" . trim($evolucion) . "';  ";
        /* if(!$rst = $this->ConexionBaseDatos($sql))
          return false;

          return true; */
        return $sql;
    }

    /*
     * Funcion se inserta la informacion de los medicamentos pendientes despachados 
     * @return array con la informacion
     */

    function Guardar_Pendientes_Adicional_Dispensacion($evolucion, $bodegas_doc_id, $numeracion, $bodegasDocTodoPendiente) {
        /* $this->ConexionTransaccion(); */
        $sql = "INSERT INTO hc_formulacion_despachos_medicamentos_pendientes
                  (
                              bodegas_doc_id,
                              numeracion,
                              evolucion_id,
							  todo_pendiente
                  )
                  VALUES
                  (
                    " . $bodegas_doc_id . ",
                    " . $numeracion . ", 
                    " . $evolucion . ",
					" . $bodegasDocTodoPendiente . "
                  ); ";

        /* if(!$rst = $this->ConexionTransaccion($sql))
          return false;
          $this->Commit();
          return true; */
        return $sql;
    }

    /*
     * Funcion se consultan los medicamentos pendientes que no se han despachados 
     * @return array con la informacion
     */

    function Medicamentos_Pendientes_sin_dispensar($evolucion) {

        $sql = "   Select  a.codigo_producto,
                      (b.cantidades - a.cantidades) as total from
                      ( 
                        SELECT codigo_formulado AS codigo_producto,
                                SUM(cantidad_despachada) as cantidades
                        FROM hc_dispensacion_medicamentos_tmp
                        where evolucion_id=" . $evolucion . "
                        group by codigo_formulado
              ) as a,
              (   select  dc.codigo_medicamento as codigo_producto,
                          SUM(dc.cantidad) as cantidades
                  FROM  hc_pendientes_por_dispensar as dc
                  WHERE      dc.evolucion_id =" . $evolucion . "
                  and        dc.sw_estado = '0'
                  group by(dc.codigo_medicamento)
          ) as b
          where a.codigo_producto = b.codigo_producto
          UNION
          SELECT codigo_medicamento as codigo_producto,
                  cantidad as cantidades
          FROM hc_pendientes_por_dispensar
          where evolucion_id=" . $evolucion . "
          and sw_estado = '0'
          and codigo_medicamento
          NOT IN( select codigo_formulado
          FROM hc_dispensacion_medicamentos_tmp where evolucion_id=" . $evolucion . ")   ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion donde se registran lo dispensado despues de entregar lo que habia pendiente 
     * @return array con la informacion
     */

    function Guardar_Pendientes_Dispensacion_($evolucion, $codigo_medicamento, $cantidad) {

        /* $this->ConexionTransaccion(); */
        $sql = " update  hc_pendientes_por_dispensar
                  set     sw_estado='1'
                  WHERE   evolucion_id = '" . trim($evolucion) . "'
                  AND     codigo_medicamento = '" . trim($codigo_medicamento) . "';  ";

        if ($cantidad != 0) {
            $sql .= "INSERT INTO hc_pendientes_por_dispensar
                            (
                              hc_pendiente_dispensacion_id,
                              evolucion_id,
                              codigo_medicamento,
                              cantidad,
                              usuario_id,
                              fecha_registro

                            )
                            VALUES
                            (
                               DEFAULT,
                              " . trim($evolucion) . ",
                              '" . trim($codigo_medicamento) . "',
                              " . trim($cantidad) . ",
                              " . UserGetUID() . ",
                              now()
                            );  ";
        }
        /*   if(!$rst = $this->ConexionTransaccion($sql))

          return false;

          $this->Commit();
          return true; */
        return $sql;
    }

    /*
     * Funcion donde se consultan las bodegas despacho  
     * @return array con la informacion
     */

    function Consultar_Bodegas_despacho($formula_id) {

        $sql = "   SELECT   tmp.empresa_id,
                          tmp.centro_utilidad,
                          tmp.bodega,
                          bod.sw_bodegamindefensa
                FROM      esm_dispensacion_medicamentos_tmp as tmp,
                          bodegas as bod
                where   tmp.empresa_id=bod.empresa_id
                and    tmp.centro_utilidad=bod.centro_utilidad
                and    tmp.bodega=bod.bodega
                and   tmp.formula_id=" . $formula_id . "
                group by tmp.empresa_id,
                tmp.centro_utilidad,
                tmp.bodega, bod.sw_bodegamindefensa  ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion donde se consultan el temporal de los medicamentos a despachar por bodega
     * @return array con la informacion
     */

    function Buscar_producto_tmp_conc($formula_id, $bodega) {

        $sql = " SELECT  esm_dispen_tmp_id,
                        formula_id,
                        empresa_id,
                        centro_utilidad,
                        bodega,
                        codigo_producto,
                        cantidad_despachada,
                        to_char(fecha_vencimiento,'dd-mm-yyyy')as fecha_vencimiento,
                        lote,
                        fc_descripcion_producto_alterno(codigo_producto) as descripcion_prod,
                        sw_entregado_off
                FROM    esm_dispensacion_medicamentos_tmp
                WHERE   formula_id = '" . $formula_id . "'
                and     bodega='" . $bodega . "' ";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion donde se guarda el despacho relacionado con el documento de dispensacion
     * @return boolean
     */

    function Guardaresm_formulacion_despachos_medicamentos($formula_id, $bodegas_doc_id, $numeracion, $persona_reclama, $persona_reclama_tipo_id, $persona_reclama_id, $observacion) {
        $this->ConexionTransaccion();

        $sql .= "INSERT INTO     esm_formulacion_despachos_medicamentos
                    (
                                esm_formulacion_despacho_id,
                                formula_id,
                                bodegas_doc_id,
                                numeracion, ";
        $sql .= "   		persona_reclama,
								persona_reclama_tipo_id,
								persona_reclama_id ";
        $sql .= "
            )VALUES
            (       			DEFAULT,
								" . trim($formula_id) . ",
								" . trim($bodegas_doc_id) . ",
								" . trim($numeracion) . ", ";
        $sql .= " 		'" . trim($persona_reclama) . "',
								'" . trim($persona_reclama_tipo_id) . "',
								'" . trim($persona_reclama_id) . "' ";
        $sql .= "            ); ";
        /* if(!$rst = $this->ConexionTransaccion($sql))

          return false;
          $this->Commit(); */
        return $sql;
    }

    /*
     * Funcion donde se consulta la informacion por medicamento dispensado
     * @return array con la informacion
     */

    function Medicamentos_Formulados_R($codigo_producto, $formula_id) {
        $sql = "SELECT  tmp.fe_medicamento_id,
                        tmp.codigo_producto,
                        tmp.cantidad,
                        tmp.observacion,
                        tmp.dosis,
                        tmp.unidad_dosificacion,
                        tmp.tiempo_tratamiento,
                        tmp.unidad_tiempo_tratamiento,
                        tmp.periodicidad_entrega,
                        tmp.unidad_periodicidad_entrega,
                        tmp.via_administracion_id,
                        fc_descripcion_producto_alterno(tmp.codigo_producto) as descripcion_prod,
                        A.descripcion as producto,
                        b.concentracion_forma_farmacologica,
                        b.unidad_medida_medicamento_id,
                        b.factor_conversion,
                        b.factor_equivalente_mg,
                        d.descripcion as forma,
                        c.descripcion as principio_activo,
                        d.cod_forma_farmacologica,
                        tipo_f.sw_ambulatoria,
                        POS.*
           FROM         esm_formula_externa_medicamentos tmp,
                        inventarios_productos A LEFT JOIN medicamentos b ON (A.codigo_producto = b.codigo_medicamento) LEFT JOIN inv_med_cod_principios_activos c on(b.cod_principio_activo = c.cod_principio_activo) LEFT JOIN inv_med_cod_forma_farmacologica  d ON(b.cod_forma_farmacologica = d.cod_forma_farmacologica),
                        esm_formula_externa esm,
                        esm_tipos_formulas tipo_f,
                        esm_formula_externa_posologia POS
            WHERE       tmp.formula_id='" . $formula_id . "'
            AND         tmp.codigo_producto='" . $codigo_producto . "'
            AND         tmp.formula_id=esm.formula_id
            AND         esm.tipo_formula=tipo_f.tipo_formula_id
            AND         tmp.codigo_producto= A.codigo_producto
            AND         tmp.fe_medicamento_id=POS.fe_medicamento_id
            AND    tmp.sw_marcado='0'
       ;  ";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion donde se consulta el factor de conversion por  medicamento
     * @return array con la informacion
     */

    function ConsultarFactorConversion($medicamento) {

        $sql = "  SELECT  HF.codigo_producto,
                              HF.unidad_id,
                              HF.unidad_dosificacion,
                              HF.factor_conversion
                        FROM  hc_formulacion_factor_conversion HF,
                              unidades UN
                        WHERE HF.codigo_producto='" . $medicamento . "'
                        AND   HF.unidad_id = UN.unidad_id;";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion donde se actualiza la fecha por medicamentos para su proxima entega
     * @return array con la informacion
     */

    function UpdateFechas_x_medicamento($formulaid, $fecha_entrega, $fecha_proxima_entrega, $codigo_producto) {

        $sql = "     update  esm_formula_externa_medicamentos
                      set     fecha_entrega='" . $fecha_entrega . "',
                      proxima_fecha_entrega='" . $fecha_proxima_entrega . "'
                      WHERE   formula_id = '" . $formulaid . "'
                      AND     codigo_producto= '" . $codigo_producto . "'
            ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion donde se actualiza la cantidad a entregar por periodo
     * @return array con la informacion
     */

    function UpdateCantidad_Por_Periodo($formula_id, $cantidad, $codigo_producto) {

        $this->ConexionTransaccion();
        $sql = "    update esm_formula_externa_medicamentos
                    set    cantidad_periodo=" . $cantidad . "
                    WHERE   formula_id=" . $formula_id . "
                    AND    codigo_producto='" . $codigo_producto . "'  ";

        if (!$rst = $this->ConexionTransaccion($sql))
            return false;

        $this->Commit();
        return true;
    }

    /*
     * Funcion donde se consulta que ESM atiende al paciente de la formula
     * @return array con la informacion
     */

    function ConsultarEsm_x_formula($formula_id) {

        $sql = "  SELECT   esm_tipo_id_tercero,
                                esm_tercero_id
                        FROM    esm_formula_externa
                        WHERE   formula_id = " . $formula_id . " ";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion donde se busca los temporales
     * @return array con la informacion
     */

    function Buscar_producto_tmp_no_pend($formula_id) {
        $sql = " SELECT  esm_dispen_tmp_id,
                          formula_id,
                          empresa_id,
                          centro_utilidad,
                          bodega,
                          codigo_producto,
                          cantidad_despachada,
                          to_char(fecha_vencimiento,'dd-mm-yyyy')as fecha_vencimiento,
                          lote,
                          fc_descripcion_producto_alterno(codigo_producto) as descripcion_prod
                  FROM    esm_dispensacion_medicamentos_tmp
                  WHERE   formula_id = '" . $formula_id . "'
         ";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion donde se consulta la cantidad total de los medicamentos pendientes
     * @return array con la informacion
     */

    function Medicamentos_Pendientes__($formula_id) {
        $sql = "      Select a.codigo_producto,
                  (b.cantidades - a.cantidades) as total
                  from
                  (

                  SELECT codigo_formulado AS codigo_producto,
                      SUM(cantidad_despachada) as cantidades
                      FROM esm_dispensacion_medicamentos_tmp
                      where formula_id=" . $formula_id . "
                      group by  codigo_formulado
                  ) as a,
                  (

                  SELECT codigo_producto,
                            SUM(cantidad) as cantidades
                            FROM esm_formula_externa_medicamentos
                            where formula_id=" . $formula_id . "
                      and   sw_marcado='0'
                            group by codigo_producto
                  ) as b

                 where
                          a.codigo_producto = b.codigo_producto
                UNION

                    SELECT codigo_producto,
                          cantidad as cantidades
                          FROM esm_formula_externa_medicamentos
                          where formula_id=" . $formula_id . "
                    and  sw_marcado='0'
                          and codigo_producto NOT IN( select
                                                      codigo_formulado
                                                      FROM esm_dispensacion_medicamentos_tmp
                                                      where formula_id=" . $formula_id . ")
                   ";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion donde se consulta la cantidad total de los medicamentos pendientes
     * @return array con la informacion
     */

    function Medicamentos_Pendientes__H($formula_id) {


        $sql = "     Select a.codigo_producto,
                  (b.cantidades - a.cantidades) as total
                  from
                  (

                  SELECT codigo_formulado AS codigo_producto,
                      SUM(cantidad_despachada) as cantidades
                      FROM esm_dispensacion_medicamentos_tmp
                      where formula_id=" . $formula_id . "
                      group by  codigo_formulado
                  ) as a,
                  (

                  SELECT codigo_producto,
                            SUM(cantidad) as cantidadess,
                      SUM(cantidad_periodo) as cantidades
                            FROM esm_formula_externa_medicamentos
                            where formula_id=" . $formula_id . "
                      and   sw_marcado='0'
                            group by codigo_producto
                  ) as b



                 where
                          a.codigo_producto = b.codigo_producto

                UNION

                    SELECT codigo_producto,
                          cantidad as cantidades
                          FROM esm_formula_externa_medicamentos
                          where formula_id=" . $formula_id . "
                    and  sw_marcado='0'
                          and codigo_producto NOT IN( select
                                                      codigo_formulado
                                                      FROM esm_dispensacion_medicamentos_tmp
                                                      where formula_id=" . $formula_id . ")

                   ";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion donde se ingresan los pendietes de una formula 
     * @return boolean
     */

    function Guardar_Pendientes($formula_id, $codigo_medicamento, $cantidad) {
        $this->ConexionTransaccion();
        $sql = "INSERT INTO esm_pendientes_por_dispensar
                    (
                        esm_pendiente_dispensacion_id,
                        formula_id,
                        codigo_medicamento,
                        cantidad,
                        usuario_id,
                        fecha_registro

                  )
                  VALUES
                  (
                     DEFAULT,
                    " . $formula_id . ",
                    '" . $codigo_medicamento . "',
                    " . $cantidad . ",
                    " . UserGetUID() . ",
                 now()
                  ) ";

        if (!$rst = $this->ConexionTransaccion($sql))
            return false;

        $this->Commit();
        return true;
    }

    /*
     * Funcion donde se borra todo el temporal de los medicamentos a despachar
     * @return array con la informacion
     */

    function EliminarTodoTemporal_ESM($formula_id, $sql_todo) {
        $this->ConexionTransaccion();
        $sql .= $sql_todo;
        $sql .= " DELETE FROM esm_dispensacion_medicamentos_tmp
                WHERE  formula_id ='" . trim($formula_id) . "'; ";
        $sql .= "   DELETE    FROM esm_dispensacion_medicamentos_tmp
                                WHERE  formula_id ='" . trim($formula_id) . "'; ";



        if (!$rst = $this->ConexionTransaccion($sql))
            return false;

        $this->Commit();
        return true;

        /*                             if(!$rst = $this->ConexionBaseDatos($sql))
          return false;
          $datos = array();
          while(!$rst->EOF)
          {
          $datos[] = $rst->GetRowAssoc($ToUpper);
          $rst->MoveNext();
          }
          $rst->Close();
          return $datos; */
    }

    /*
     * Funcion donde se actualiza los pendientes despachado , se hace por producto
     * @return array con la informacion
     */

    function UpdatePOR_producto_bodega($formula_id, $bodegas_doc_id, $numeracion) {

        $sql = "    update  esm_pendientes_por_dispensar
                    set     bodegas_doc_id='" . trim($bodegas_doc_id) . "',numeracion='" . trim($numeracion) . "'
            WHERE   formula_id = '" . trim($formula_id) . "'; ";
        /*
          if(!$rst = $this->ConexionBaseDatos($sql))
          return false;
          $datos = array();
          while(!$rst->EOF)
          {
          $datos[] = $rst->GetRowAssoc($ToUpper);
          $rst->MoveNext();
          }
          $rst->Close(); */
        return $sql;
    }

    /*
     * Funcion donde se consultan los pendientes que no se han dispensado
     * @return array con la informacion
     */

    function Medicamentos_Pendientes_SinDespachar($formula_id) {

        $sql = "   Select a.codigo_producto, (b.cantidades - a.cantidades) as total from
                    ( SELECT codigo_formulado AS codigo_producto,
                    SUM(cantidad_despachada) as cantidades
                    FROM esm_dispensacion_medicamentos_tmp
                    where formula_id=" . trim($formula_id) . "
                    group by codigo_formulado
                    ) as a,
                    (   select  dc.codigo_medicamento as codigo_producto,
                            SUM(dc.cantidad) as cantidades
                            FROM  esm_pendientes_por_dispensar as dc
                          WHERE      dc.formula_id =" . trim($formula_id) . "
                          and        dc.sw_estado = '0'
                          group by(dc.codigo_medicamento)
                    ) as b
                    where a.codigo_producto = b.codigo_producto
            UNION
                    SELECT codigo_medicamento as codigo_producto,
                            cantidad as cantidades
                    FROM esm_pendientes_por_dispensar
                    where formula_id=" . trim($formula_id) . "
                    and sw_estado = '0'
                    and codigo_medicamento

                    NOT IN( select codigo_formulado
                    FROM esm_dispensacion_medicamentos_tmp where formula_id=" . trim($formula_id) . ")   ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion donde se ingresan los pendietes de una formula 
     * @return boolean
     */

    function Guardar_Pendientes_($formula_id, $codigo_medicamento, $cantidad) {
        $this->ConexionTransaccion();
        $sql = " update  esm_pendientes_por_dispensar
                    set     sw_estado='1'
            WHERE   formula_id = '" . $formula_id . "'
            AND     codigo_medicamento = '" . $codigo_medicamento . "';  ";

        if ($cantidad != 0) {

            $sql .= "INSERT INTO esm_pendientes_por_dispensar
                    (
                    esm_pendiente_dispensacion_id,
                    formula_id,
                    codigo_medicamento,
                    cantidad,
                    usuario_id,
                  fecha_registro

                  )
                  VALUES
                  (
                     DEFAULT,
                    " . $formula_id . ",
                    '" . $codigo_medicamento . "',
                    " . $cantidad . ",
           " . UserGetUID() . ",
                 now()
                  ) ";
        }
        if (!$rst = $this->ConexionTransaccion($sql))
            return false;
        $this->Commit();
        return true;
    }

    /*
     * Funcion donde se ingresan los pendietes  
     * @return boolean
     */

    function Guardar_Pendientes_Adicional($formula_id, $bodegas_doc_id, $numeracion) {
        /* $this->ConexionTransaccion(); */

        $sql = "INSERT INTO esm_formulacion_despachos_medicamentos_pendientes
                  (
                    bodegas_doc_id,
                    numeracion,
                    formula_id


                  )
                  VALUES
                  (
                    " . $bodegas_doc_id . ",
                    " . $numeracion . ",
                    " . $formula_id . "
                  ); ";

        /* if(!$rst = $this->ConexionTransaccion($sql))

          return false;

          $this->Commit(); */
        return $sql;
    }

    /*
     * Funcion donde se consultan las bodegas despacho sin tener encuenta las de ESM  
     * @return array con la informacion
     */

    function Consultar_Bodegas_despacho_FE($formula_id) {

        $sql = "   SELECT   tmp.empresa_id,
                          tmp.centro_utilidad,
                          tmp.bodega
                FROM      esm_dispensacion_medicamentos_tmp as tmp
                where     tmp.formula_id_tmp=" . trim($formula_id) . "
                group by tmp.empresa_id,
                tmp.centro_utilidad,
                tmp.bodega  ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion donde se consultan el temporal de los medicamentos a despachar por bodega
     * @return array con la informacion
     */

    function Buscar_productoDipensar_tmp($formula_id, $bodega) {

        $sql = " SELECT  esm_dispen_tmp_id,
                        formula_id_tmp,
                        empresa_id,
                        centro_utilidad,
                        bodega,
                        codigo_producto,
                        cantidad_despachada,
                        to_char(fecha_vencimiento,'dd-mm-yyyy')as fecha_vencimiento,
                        lote
                FROM    esm_dispensacion_medicamentos_tmp
                WHERE   formula_id_tmp = '" . $formula_id . "' 
                and     bodega='" . $bodega . "' ";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion donde se busca los temporales de la formula temporal
     * @return array con la informacion
     */

    function Buscar_Medicamentos_TemporalesCompletos($formula_id) {
        $sql = " SELECT  esm_dispen_tmp_id,
                          formula_id_tmp,
                          empresa_id,
                          centro_utilidad,
                          bodega,
                          codigo_producto,
                          cantidad_despachada,
                          to_char(fecha_vencimiento,'dd-mm-yyyy')as fecha_vencimiento,
                          lote,
                          fc_descripcion_producto_alterno(codigo_producto) as descripcion_prod
                  FROM    esm_dispensacion_medicamentos_tmp
                  WHERE   formula_id_tmp = '" . trim($formula_id) . "'
         ";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion donde se consulta la cantidad total de los medicamentos pendientes
     * @return array con la informacion
     */

    function Medicamentos_Pendientes_NDespacho($formula_id, $formula_id_tmp) {

        $sql = "      Select a.codigo_producto,
                  (b.cantidades - a.cantidades) as total
                  from
                  (

                  SELECT codigo_formulado AS codigo_producto,
                      SUM(cantidad_despachada) as cantidades
                      FROM esm_dispensacion_medicamentos_tmp
                      where formula_id_tmp=" . $formula_id_tmp . "
                      group by  codigo_formulado
                  ) as a,
                  (

                  SELECT codigo_producto,
                            SUM(cantidad) as cantidades
                            FROM esm_formula_externa_medicamentos
                            where formula_id=" . $formula_id . "
                      and   sw_marcado='0'
                            group by codigo_producto
                  ) as b

                 where
                          a.codigo_producto = b.codigo_producto
                UNION

                    SELECT codigo_producto,
                          cantidad as cantidades
                          FROM esm_formula_externa_medicamentos
                          where formula_id=" . $formula_id . "
                    and  sw_marcado='0'
                          and codigo_producto NOT IN( select
                                                      codigo_formulado
                                                      FROM esm_dispensacion_medicamentos_tmp
                                                      where formula_id_tmp=" . $formula_id_tmp . ")
                   ";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion donde se borra todo el temporal de los medicamentos a despachar
     * @return array con la informacion
     */

    function EliminarTodoTemporal_FormulasM($formula_id_tmp, $sql_todo) {

        $this->ConexionTransaccion();
        $sql .= $sql_todo;
        $sql .= "      DELETE    FROM esm_dispensacion_medicamentos_tmp
                                WHERE  formula_id_tmp ='" . $formula_id_tmp . "'  ; ";

        $sql .= "      DELETE     FROM  esm_formula_externa_medicamentos_tmp 
                                where  	   usuario_id=" . UserGetUID() . " 
                                and         tmp_formula_id = '" . $formula_id_tmp . "' 
                                ; ";

        $sql .= "  DELETE     FROM  esm_formula_externa_diagnosticos_tmp 
                            WHERE  	  usuario_id=" . UserGetUID() . " 
                            AND         tmp_formula_id = '" . $formula_id_tmp . "' 
                                  ; ";

        $sql .= "  DELETE     FROM  esm_formula_externa_tmp 
                            WHERE      usuario_id=" . UserGetUID() . " 
                            AND        tmp_formula_id = '" . $formula_id_tmp . "'  ; ";
        $sql .= "	COMMIT WORK;";

        if (!$rst = $this->ConexionTransaccion($sql))
            return false;

        $this->Commit();
        return true;
    }
	
	
	
	/*
     * Funcion para buscar el temporal por bodega 
     * @return array $datos vector con la informacion de los productos
     */

    function BuscarProductosTodoPendiente($evolucion) {

        $sql = " SELECT   evolucion_id
                  FROM    hc_pendientes_por_dispensar
                  WHERE   todo_pendiente = 1 
				  AND	  bodegas_doc_id is null  
				  AND     numeracion is null
				  AND	  evolucion_id = '" . $evolucion . "' ";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

}

//fin de la clase
?>