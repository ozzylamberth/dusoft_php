<?php
	/**
	* Archivo Xajax
	* Tiene como responsabilidad hacer el manejo de las funciones
	* que son invocadas por medio de xajax
	*
	* @package IPSOFT-SIIS
	* @version $Revision: 1.3 $
	* @copyright (C) 2010 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres 
	*/
	
	
	
	function BuscarProducto1($FormularioBuscador,$formula_id,$bodega_otra)
    {
	    $objResponse = new xajaxResponse();
	
		$obje = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
		$empresa = SessionGetVar("DatosEmpresaAF");
		$farmacia=$empresa['empresa_id'];
		$centrou=$empresa['centro_utilidad'];
		
		// $objResponse->alert(print_r($FormularioBuscador,true));
		$bodegas_doc_id= ModuloGetVar('app','DispensacionESM','documento_dispensacion_'.trim($farmacia).'_'.trim($FormularioBuscador['bodega']));
				
		 //$objResponse->alert(print_r(ModuloGetVar('app','DispensacionESM','documento_dispensacion_'.trim($farmacia).'_'.trim($FormularioBuscador['bodega'])),true));
		 

		$privilegios=$obje->Usuario_Privilegios_($FormularioBuscador);
	
		if($FormularioBuscador['sw_ambulatoria']=='1')
		{
		  
		    $busqueda=$obje->Consultar_Medicamentos_Detalle_AMBU($FormularioBuscador,$formula_id);
		  
			if(!empty($busqueda))
			{
		        foreach($busqueda as $k => $valor)
				{  
		            $today = date("Y-m-d"); 
					$fecha_formula=$valor['fecha_formula'];
						
					$tiempo_tratamiento_=$valor['tiempo_tratamiento'];
					$unidad_tiempo_tratamiento_=$valor['unidad_tiempo_tratamiento'];
						
					if($unidad_tiempo_tratamiento_=='1')
					{
						$dias_c=$tiempo_tratamiento_ * 365;
										
					}
					if($unidad_tiempo_tratamiento_=='2')
					{
						$dias_c=$tiempo_tratamiento_ * 30;
										
					}
					if($unidad_tiempo_tratamiento_=='3')
					{
						$dias_c=$tiempo_tratamiento_ * 7;
										
					}
					if($unidad_tiempo_tratamiento_=='4')
					{
						$dias_c=$tiempo_tratamiento_ * 1;
					}
					
					list($a,$m,$d) = split("-",$fecha_formula);
					$fecha_fin_formula = date("Y-m-d",(mktime(0,0,0, $m,($d + $dias_c),$a)));
				
				  
				    if($fecha_fin_formula >$today)
					{
						    $datos_ex=$obje->ConsultarUltimoResg_Dispens_($formula_id,$today,$fecha_condias,$valor['cod_principio_activo'],$valor['paciente_id'],$valor['tipo_id_paciente'],$valor['codigo_producto']);
							$array_can=$obje->ConsultarUltimoResg_Dispens_CANTIDAD($formula_id,$today,$fecha_condias,$valor['cod_principio_activo'],$valor['paciente_id'],$valor['tipo_id_paciente'],$valor['codigo_producto']);
						
							
						
						if(!empty($datos_ex))
						{
						   if($datos_ex['resultado']=='1')
							{
							   $fecha_despacho=$datos_ex['fecha_registro'];
							}else
							{
								 $fecha_despacho=$datos_ex['fecha_registro'];

							}	
						 $dias_dipensados= ModuloGetVar('','','dispensacion_dias_ultima_entrega');
						 list($a,$m,$d) = split("-",$fecha_despacho);
						 $fecha_fin_despacho = date("Y-m-d",(mktime(0,0,0, $m,($d + $dias_dipensados),$a)));
						
						}
							
								if($today > $fecha_fin_despacho || $valor['sw_autorizado']=='1')
								{
								
										$html .= "                 <div id=\"erro\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:11px;\">\n";
										$html .= "                 </div>\n";
										$html .= "                                    <div id=\"error\" class='label_error'></div>";
										$cantidad = $obje->Cantidad_ProductoTemporal($formula_id,$valor['cod_principio_activo'],$valor['codigo_producto']);
									
										
										$CantidaEntregar=round($valor['cantidad']);
										$cantidad_=0;
										   
										       if($cantidad['codigo_formulado']==$valor['codigo_producto'])
												{	
										
										    
													$cantidad_=$cantidad['total'];					
											
												}
										
										$html .= "                 <form id=\"forma".$formula_id."@".$valor['codigo_producto']."\" name=\"".$formula_id."@".$valor['codigo_producto']."\" action=\"\" method=\"post\">\n";
										$html .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
										$html .= "                    <tr class=\"modulo_table_list_title\">\n";
										$html .= "                     <td width=\"50%\">PRODUCTO: ".$valor['codigo_producto_mini']." &nbsp; ".$valor['descripcion_prod'].". </td>
										<td>CANTIDAD SOLICITADA <input readonly=\"true\" type=\"input-text\" name=\"cantidad_solicitada\" id=\"cantidad_solicitada\" value=\"".$CantidaEntregar."\" class=\"input-text\"></td><td>CANTIDAD PENDIENTE <input readonly=\"true\" type=\"input-text\" name=\"cantidad_pendiente\" id=\"cantidad_pendiente\" value=\"".($CantidaEntregar-$cantidad_)."\" class=\"input-text\"></td>\n";
										$html .= "                        <input type=\"hidden\" name=\"principio_activo\" id=\"principio_activo\" value=\"".$valor['cod_principio_activo']."\">";
										$html .= "                        <input type=\"hidden\" name=\"medicamento_formulado\" id=\"medicamento_formulado\" value=\"".$valor['codigo_producto']."\">";
										$html .= "                        <input type=\"hidden\" name=\"formula_id\" id=\"formula_id\" value=\"".$formula_id."\">";
										$html .= "                        <input type=\"hidden\" name=\"codigo_producto\" id=\"codigo_producto\" value=\"".$valor['codigo_producto']."\">";
										$html .= "                        <input type=\"hidden\" name=\"bodega\" id=\"bodega\" value=\"".$FormularioBuscador['bodega']."\">";
										$html .= "                     </td>";
										$html .= "                    </tr>\n";
				                
										$html .= "                   <tr class=\"modulo_list_claro\">\n";
										$html .= "                      <td colspan=\"3\" align=\"center\">";
										$Existencias=$obje->Consultar_ExistenciasBodegas($valor['cod_principio_activo'],$FormularioBuscador,$farmacia,$centrou,$bodega,$valor['codigo_producto']);      
								   
										if(!empty($Existencias))
										{	
										
										
											$html .= "                                   <table width=\"85%\" align=\"center\" rules=\"all\" class=\"modulo_table_list\">";
											$html .= "                                       <tr class=\"modulo_table_list_title\">\n";
											$html .= "                                       <td width=\"25%\">";
											$html .= "                                            CODIGO  ";
											$html .= "                                        </td>";
											$html .= "                                       <td width=\"20%\">";
											$html .= "                                            PRODUCTO  ";
											$html .= "                                        </td>";
											$html .= "                                       <td width=\"20%\">";
											$html .= "                                            LOTE";
											$html .= "                                        </td>";
											$html .= "                                        <td width=\"20%\">";
											$html .= "                                              FECHA VENCIMIENTO";
											$html .= "                                        </td>";
											$html .= "                                       <td width=\"5%\">";
											$html .= "                                             EXISTENCIA";
											$html .= "                                      </td>";
											$html .= "                                        <td width=\"5%\">";
											$html .= "                                              CANTIDAD";
											$html .= "                                        </td>";
											$html .= "                                        <td width=\"5%\">";
											$html .= "                                              SEL";
											$html .= "                                        </td>";
											$html .= "                                        </tr>\n";
														
											$i=0;
										if(!empty($bodegas_doc_id))
										{
        			  	
											foreach($Existencias as $key=>$v)
											{
												
												$ProductoLote=$obje->Buscar_ProductoLote($formula_id,$valor['codigo_producto'],$v['lote'],$v['codigo_producto']);
												//$objResponse->alert(($ProductoLote,true));
										
												if(!empty($ProductoLote))
												{   
												$habilitar = " checked=\"true\" disabled ";
												}
												else
												$habilitar = "  ";
															
												
												$fech_vencmodulo = ModuloGetVar('app','AdminFarmacia','dias_vencimiento_product_bodega_farmacia_'.$farmacia);
												
												$fecha =$v['fecha_vencimiento'];  //esta es la que viene de la DB
												list($ano,$mes,$dia) = split( '[/.-]', $fecha );
												$fecha = $mes."/".$dia."/".$ano;
												          
												$fecha_actual=date("m/d/Y");
												$fecha_compara_actual=date("Y-m-d");
												$int_nodias = floor(abs(strtotime($fecha) - strtotime($fecha_actual))/86400);
												$colores['PV'] = ModuloGetVar('app','ReportesInventariosGral','color_proximo_vencer');
												$colores['VN'] = ModuloGetVar('app','ReportesInventariosGral','color_vencido');
												            
												$fecha_uno_act= mktime(0,0,0,date('m'),date('d'),date('Y'));
												$fecha_dos= mktime(0,0,0,$mes,$dia,$ano);
												$color =" style=\"width:100%\" ";
												$vencido=0;
												if($int_nodias<$fech_vencmodulo)
												{
												   $color = "style=\"width:100%;background:".$colores['PV'].";\"";
													$vencido=0;
												}
												          
												if($fecha_dos<=$fecha_uno_act)
												{
													$color = "style=\"width:100%;background:".$colores['VN'].";\"";
													$vencido=1;
												}
												if($vencido==0)
												{  
													  
													$html .= "                                        <tr class=\"modulo_list_claro\">";
													$html .= "                                           <td>";
													$html .= "                                             <input style=\"width:100%\" type=\"text\" readonly=\"text\" class=\"input-text\" value=\"".$v['codigo_producto']."\" name=\"codigo_producto".$i."\" id=\"codigo_producto".$i."\" >";
													$html .= "                                            </td>";
													$html .= "                                           <td>".$v['producto']." ";
													$html .= "                                            </td>";
															
													$html .= "                                           <td>";
													$html .= "                                             <input style=\"width:100%\" type=\"text\" readonly=\"text\" class=\"input-text\" value=\"".$v['lote']."\" name=\"lote".$i."\" id=\"lote".$i."\" >";
													$html .= "                                            </td>";
													$html .= "                                           <td>";
													$fecha_vencimiento=explode("-",$v['fecha_vencimiento']);
													$fechavencimiento=$fecha_vencimiento[2]."-".$fecha_vencimiento[1]."-".$fecha_vencimiento[0];
													$html .= "                                               <input ".$color."  type=\"text\" readonly=\"text\" class=\"input-text\" value=\"".$fechavencimiento."\" name=\"fecha_vencimiento".$i."\" id=\"fecha_vencimiento".$i."\" >";
													$html .= "                                              </td>";
													$html .= "                                             <td>";
													$html .= "                                              <input style=\"width:100%\" type=\"text\" readonly=\"text\" class=\"input-text\" value=\"".$v['existencia_actual']."\" name=\"existencia_actual".$i."\" id=\"existencia_actual".$i."\" >";
													$html .= "                                           </td>";
													$html .= "                                              <td>";
													$html .= "                                                <input style=\"width:100%\" type=\"text\" class=\"input-text\" name=\"cantidad".$i."\" id=\"cantidad".$valor['orden_requisicion_id']."@".$valor['codigo_producto']."".$i."\"  value=\"$cantidad_lote\" onkeypress=\"return acceptNum(event);\" onkeyup=\"ValidarCantidad('cantidad".$valor['orden_requisicion_id']."@".$valor['codigo_producto']."".$i."',xGetElementById('cantidad".$valor['orden_requisicion_id']."@".$valor['codigo_producto']."".$i."').value,'".$v['existencia_actual']."','hell$i');\">";
													$html .= "                                             </td>";
													$html .= "                                           <td>";
													if($vencido!=1)
													$html .= "                                                <input ".$habilitar." style=\"width:100%\" type=\"checkbox\" class=\"input-text\" name=\"".$i."\" id=\"".$i."\" value=\"".$i."\" >";
													$html .= "                                               <input type=\"hidden\" name=\"registros\" id=\"registros\" value=\"".$i."\" >";
													$html .= "                                             </td>";
													$html .= "                                       </tr>";
													
																							
													$i++;
												}
											}
										}	
													$html .= "                                       <tr>";
													$html .= "                                              <td colspan=\"4\" align=\"center\">";
													$html .= "  													<div class=\"label_error\" id=\"".$valor['orden_requisicion_id']."@".$valor['codigo_producto']."\"></div>";
													$html .= "                                              </td>";
													$html .= "                                          </tr>";
													$html .= "                                     </table>\n";
													$html .= "                         </td>";
													$html .= "                      </tr>\n";
													$html .= "                                          <tr class=\"modulo_table_list_title\">\n";
													$html .= "                                         <td width=\"20%\" colspan=\"3\" align=\"center\">";
													$html .= "  												<input type=\"hidden\" name=\"formula_id\" id=\"formula_id\" value=\"".$formula_id."\">";
													$html .= "                                                <input class=\"input-submit\" type=\"button\" value=\"GUARDAR TEMPORAL\" onclick=\"xajax_GuardarPT(xajax.getFormValues('forma".$valor['formula_id']."@".$valor['codigo_producto']."','".$formula_id."'));\">";
													$html .= "                                          </td>";
													$html .= "                                        </tr>\n";
									
										}else
										{
													
													$html .= "                                   <table width=\"70%\" align=\"center\" rules=\"all\" class=\"modulo_table_list\">";
													$html .= "                                       <tr class=\"modulo_table_list_title\">\n";
													$html .= "                                       <td width=\"20%\">";
													$html .= "                                            BODEGA EXISTENCIA PRODUCTO";
													$html .= "                                        </td>";
													$html .= "                                        <td width=\"20%\">";
													$html .= "				  <select name=\"bodega_otra\" id=\"bodega_otra\" class=\"select\">\n";
													$html .= "            <option value = '-1'>--  SELECCIONE --</option>\n";
													$csk = "";
													
												
													$Bodegas_otras=$obje->Buscar_producto_EN_OTRA_FRM($farmacia,$centrou,$FormularioBuscador['bodega'],$valor['cod_principio_activo'],$valor['codigo_producto']);
													
													foreach($Bodegas_otras as $indice => $det)
													{
														
														$html .= "  <option value=\"".$det['bodega']."\" ".$sel.">".$det['bodega_des']."</option>\n";
													}
													$html .= "				  </select>\n";
													$html .= "				</td>\n";
													$html .= "                                         <td width=\"20%\" colspan=\"3\" align=\"center\">";
													$html .= "  												<input type=\"hidden\" name=\"formula_id\" id=\"formula_id\" value=\"".$formula_id."\">";
													$html .= "                                                <input class=\"input-submit\" type=\"button\" value=\"BUSCAR EN BODEGA\" onclick=\"Recargar_informacion(document.getElementById('bodega_otra').value);\">";
													$html .= "                                          </td>";
													$html .= "                                     </table>\n";
										}
										$html .= "                 </table>\n";
										$html .= "              </form>";
										$html .= "                <br>\n";
								}else
								{
								
										$html .= "                                   <table width=\"70%\" align=\"center\" rules=\"all\" class=\"modulo_table_list\">";
										$html .= "                                       <tr class=\"label_error\">\n";
										$html .= "                                       <td align=\"center\" >";
										$html .= "                                         <I> ESTE MEDICAMENTO YA  FUE DESPACHADO HACE MENOS DE  $dias_dipensados DIA(S)</I> <br>";
										$html .= "                                          <I>FORMULA NO : </I>".$datos_ex['formula_papel']."\n <BR> ";
										$html .= "                                          <I>CANTIDAD DESPACHADA : </I> ".round($datos_ex['unidades'])."\n<BR> ";
										$html .= "                                          <I>USUARIO DESPACHO  :</I>".$datos_ex['nombre']."  \n <BR> ";
											$html .= "                                      <I>LUGAR DE DESPACHO : </I> ".$datos_ex['razon_social']."  \n ";
									
										$html .= "          <img border=\"0\"  title=\"MEDICAMENTO DISPENSADO\" src=\"".GetThemePath()."/images/alarma.gif\">\n";
										$html .= "                                        </td>";
										$html .= "                                        </tr>\n";
										$html .= "<embed src=\"".GetBaseURL()."/1.mid\" hidden=\"true\" type=\"midi\" loop=\"true\"></embed > ";
				
										$html .= "                                   </table >";
										
										if($privilegios['sw_privilegios']=='1')
										{
										
												$html .= "	<table align=\"center\" border=\"0\" width=\"30%\" >\n";
                                                $autorizacion='1';
												$html .= "  <tr class=\"formulacion_table_list\">\n";
												$html .= "      <td   colspan=\"15\" align=\"CENTER\">OBSERVACIONES:</td>\n";
												$html .= "  </tr >\n";
												$html .= "  <tr class=\"modulo_table_list_title\">\n";
												$html .= "      <td   colspan=\"13\"  align=\"left\" class=\"modulo_list_claro\"> <textarea  onkeypress=\"return max(event)\"  name=\"observaciones\"  id=\"observaciones\"   rows=\"2\"  style=\"width:100%\"></textarea>\n";
												$html .= "       </td>\n";
												$html .= "  </tr >\n";
												
												
												$html .= "		<tr  align=\"center\">";
												$html .= "      <td  >";
												$html .= "      <input type=\"button\" class=\"input-submit\" value=\"AUTORIZAR DESPACHO DEL MEDICAMENTO\" style=\"width:100%\" onclick=\"xajax_Autorizacion_despacho(xajax.getFormValues('buscador'),'".$formula_id."','".$bodega_otra."',document.getElementById('observaciones').value,'".$valor['codigo_producto']."');\" >";
												$html .= "      </td>";
												$html .= "		</tr>\n";
												$html .= "    </table>";
										
										
										}


														
								}
					}else
					{
							$html .= "                                   <table width=\"70%\" align=\"center\" rules=\"all\" class=\"modulo_table_list\">";
							$html .= "                                       <tr class=\"label_error\">\n";
							$html .= "                                       <td align=\"center\" >";
							$html .= "                                           EL PACIENTE YA FINALIZO EL TRATAMIENTO CON ESTE PRODUCTO ";
							$html .= "          <img border=\"0\"  title=\"MEDICAMENTO DISPENSADO\" src=\"".GetThemePath()."/images/alarma.gif\">\n";
							
							$html .= "                                        </td>";
							$html .= "                                        </tr>\n";
							 $html .= "<embed src=\"".GetBaseURL()."/1.mid\" hidden=\"true\" type=\"midi\" loop=\"true\"></embed > ";
				
							$html .= "                                   </table >";
					
					
					
					
					}
				}
			}
		}else
		{
			$busqueda=$obje->Consultar_Medicamentos_Detalle($FormularioBuscador,$formula_id);
			//	 $objResponse->alert(print_r($busqueda,true));
		     $today = date("Y-m-d"); 
			 $privilegios=$obje->Usuario_Privilegios_($FormularioBuscador);
			foreach($busqueda as $k => $valor)
			{
		         $datos_ex=$obje->ConsultarUltimoResg_Dispens_($formula_id,$today,$fecha_condias,$valor['cod_principio_activo'],$valor['paciente_id'],$valor['tipo_id_paciente'],$valor['codigo_producto']);
				 $array_can=$obje->ConsultarUltimoResg_Dispens_CANTIDAD($formula_id,$today,$fecha_condias,$valor['cod_principio_activo'],$valor['paciente_id'],$valor['tipo_id_paciente'],$valor['codigo_producto']);
				// $informacion_hospi=$obje->ConsultarUltimoResg_Dispens_Hospitalizacion($formula_id,$valor['paciente_id'],$valor['tipo_id_paciente']);
                    $fecha_formula=$valor['fecha_formula'];
					$tiempo_tratamiento=$valor['tiempo_tratamiento'];
					$unidad_tiempo_tratamiento=$valor['unidad_tiempo_tratamiento'];

					if($unidad_tiempo_tratamiento=='1')
					{

						$dias_tt=$tiempo_tratamiento * 365;
					}
					if($unidad_tiempo_tratamiento=='2')
					{

						$dias_tt=$tiempo_tratamiento *  30;
					}
					if($unidad_tiempo_tratamiento=='3')
					{

						$dias_tt=$tiempo_tratamiento *  7;
					}
					if($unidad_tiempo_tratamiento=='4')
					{

						$dias_tt=$tiempo_tratamiento *  1;
					}
														
							
				
				
				     /*if($informacion_hospi['resultado']=='1')
						{
							   $fecha_dispensacion=$informacion_hospi['fecha_registro'];
						}else
						{
								 $fecha_dispensacion=$informacion_hospi['fecha_registro'];

						}	*/
						
				list($a,$m,$d) = split("-",$fecha_formula);
				$fecha_fin_formula_dispensada = date("Y-m-d",(mktime(0,0,0, $m,($d + $dias_tt),$a)));
				
                 if($today < $fecha_fin_formula_dispensada)		
				{
				
								
			
				    if(!empty($datos_ex))
					{      
					
							if($datos_ex['resultado']=='1')
							{
								   $fecha_despacho_=$datos_ex['fecha_registro'];
							}else
							{
									 $fecha_despacho_=$datos_ex['fecha_registro'];

							}	
								$periodicidad_entrega=$valor['periodicidad_entrega'];
								$unidad_periodicidad_entrega=$valor['unidad_periodicidad_entrega'];

								if($unidad_periodicidad_entrega=='1')
								{
								$dias_p=$periodicidad_entrega * 365;

								}
								if($unidad_periodicidad_entrega=='2')
								{
								$dias_p=$periodicidad_entrega * 30;

								}
								if($unidad_periodicidad_entrega=='3')
								{
								$dias_p=$periodicidad_entrega * 7;

								}
								if($unidad_periodicidad_entrega=='4')
								{
								$dias_p=$periodicidad_entrega * 1;
								}

							
							 list($a,$m,$d) = split("-",$fecha_despacho_);
							 $fecha_fin_despacho_ = date("Y-m-d",(mktime(0,0,0, $m,($d + $dias_p),$a)));
							 
							 
							
					}
				    if($today > $fecha_fin_despacho_ || $valor['sw_autorizado']=='1')
				    {
				
				
							if($valor['sw_durante_tratamiento']=='1')
							{
							 
								$Conversion=$obje->ConsultarFactorConversion($valor['codigo_producto']);
							    $factor_conversion=$Conversion['0']['factor_conversion'];
								if(empty($factor_conversion))
								{
									$factor_conversion='1';
								
								}
										
								
								$cantidad_e=$valor['cantidad'];
								 
								$Entregar=($cantidad_e/$factor_conversion);

								$CantidaEntregar=intval($Entregar);
							
						     
						    }
							else
							{
							    
								$cantidad_veces=round($valor['cantidad_veces']);
								$dosisA=$valor['dosis'];
								
								$entrega_diaria=$cantidad_veces * $dosisA; 
							
								$periodicidad_entrega=$valor['periodicidad_entrega'];
								$unidad_periodicidad_entrega=$valor['unidad_periodicidad_entrega'];

								if($unidad_periodicidad_entrega=='1')
								{

								$dias_s=$periodicidad_entrega * 365;
								}
								if($unidad_periodicidad_entrega=='2')
								{

								$dias_s=$periodicidad_entrega *  30;
								}
								if($unidad_periodicidad_entrega=='3')
								{

								$dias_s=$periodicidad_entrega *  7;
								}
								if($unidad_periodicidad_entrega=='4')
								{

								$dias_s=$periodicidad_entrega *  1;
								}
								
								$cantidad_e=$entrega_diaria * $dias_s;
						
								
								
								$Conversion=$obje->ConsultarFactorConversion($valor['codigo_producto']);
								$unidad_dosif=$Conversion['0']['unidad_dosificacion'];	
								$factor_conversion=$Conversion['0']['factor_conversion'];	


								//$Entregar=($cantidad_e/$factor_conversion);
							    
								//$CantidaEntregar=intval($Entregar);
								
								
															
								$fintensidad=$valor['tiempo'];
								$fnumero=$valor['periocidad_id'];
								if($fintensidad=='Hora(s)')
								{
								       $cantidad = ($dosisA*1) * 24/($fnumero*1);
								
								}
								else if($fintensidad.value == 'Min')
					               $cantidad = ($dosisA*1) * 24/(($fnumero*1)/60);
								   
							  else if($fintensidad.value == 'Dia(s)')
								$cantidad = 1/($fnumero*1);
							  else if($fintensidad.value == 'Semana(s)')
					           $cantidad = (1/($fnumero*7))*($dosiscantidad*1);
							   
							   
							   $cantidad1 = $cantidad*$dias_s;
							   $Entregar=($cantidad1/$factor_conversion);
							  $CantidaEntregar=intval($Entregar);
							
					
					        }
					
							$html .= "                 <div id=\"erro\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:11px;\">\n";
							$html .= "                 </div>\n";
							$html .= "                                    <div id=\"error\" class='label_error'></div>";
							$cantidad = $obje->Cantidad_ProductoTemporal($formula_id,$valor['cod_principio_activo'],$valor['codigo_producto']);
 						
													$cantidad_=0;
										   
										       if($cantidad['codigo_formulado']==$valor['codigo_producto'])
												{	
										
										    
													$cantidad_=$cantidad['total'];					
											
												}		
									
									
									
									
									
									
								// $objResponse->alert(print_r($cantidad_,true));		
									
							$html .= "                 <form id=\"forma".$formula_id."@".$valor['codigo_producto']."\" name=\"".$formula_id."@".$valor['codigo_producto']."\" action=\"\" method=\"post\">\n";
							$html .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
							$html .= "                    <tr class=\"modulo_table_list_title\">\n";
							$html .= "                     <td width=\"50%\">PRODUCTO: ".$valor['codigo_producto_mini']." &nbsp; ".$valor['descripcion_prod'].". </td>
							<td>CANTIDAD SOLICITADA <input readonly=\"true\" type=\"input-text\" name=\"cantidad_solicitada\" id=\"cantidad_solicitada\" value=\"".$CantidaEntregar."\" class=\"input-text\"></td><td>CANTIDAD PENDIENTE <input readonly=\"true\" type=\"input-text\" name=\"cantidad_pendiente\" id=\"cantidad_pendiente\" value=\"".($CantidaEntregar-$cantidad_)."\" class=\"input-text\"></td>\n";
							$html .= "                        <input type=\"hidden\" name=\"principio_activo\" id=\"principio_activo\" value=\"".$valor['cod_principio_activo']."\">";
							$html .= "                        <input type=\"hidden\" name=\"medicamento_formulado\" id=\"medicamento_formulado\" value=\"".$valor['codigo_producto']."\">";

							
							$html .= "                        <input type=\"hidden\" name=\"formula_id\" id=\"formula_id\" value=\"".$formula_id."\">";
							$html .= "                        <input type=\"hidden\" name=\"codigo_producto\" id=\"codigo_producto\" value=\"".$valor['codigo_producto']."\">";
							$html .= "                        <input type=\"hidden\" name=\"bodega\" id=\"bodega\" value=\"".$FormularioBuscador['bodega']."\">";
							$html .= "                     </td>";
							$html .= "                    </tr>\n";
							$html .= "                   <tr class=\"modulo_list_claro\">\n";
							$html .= "                      <td colspan=\"3\" align=\"center\">";
							
							$Existencias=$obje->Consultar_ExistenciasBodegas($valor['cod_principio_activo'],$FormularioBuscador,$farmacia,$centrou,$bodega,$valor['codigo_producto']);      
							
								if(!empty($Existencias))
								{	
								
								
									$html .= "                                   <table width=\"85%\" align=\"center\" rules=\"all\" class=\"modulo_table_list\">";
									$html .= "                                       <tr class=\"modulo_table_list_title\">\n";
									$html .= "                                       <td width=\"25%\">";
									$html .= "                                            CODIGO  ";
									$html .= "                                        </td>";
									$html .= "                                       <td width=\"20%\">";
									$html .= "                                            PRODUCTO  ";
									$html .= "                                        </td>";
									$html .= "                                       <td width=\"20%\">";
									$html .= "                                            LOTE";
									$html .= "                                        </td>";
									$html .= "                                        <td width=\"20%\">";
									$html .= "                                              FECHA VENCIMIENTO";
									$html .= "                                        </td>";
									$html .= "                                       <td width=\"5%\">";
									$html .= "                                             EXISTENCIA";
									$html .= "                                      </td>";
									$html .= "                                        <td width=\"5%\">";
									$html .= "                                              CANTIDAD";
									$html .= "                                        </td>";
									$html .= "                                        <td width=\"5%\">";
									$html .= "                                              SEL";
									$html .= "                                        </td>";
									$html .= "                                        </tr>\n";
								
									$i=0;
								if(!empty($bodegas_doc_id))
								{ 	
									foreach($Existencias as $key=>$v)
									{
										 //  $ProductoLote=$obje->Buscar_ProductoLote($formula_id,$valor['codigo_producto'],$v['lote']);
									    $ProductoLote=$obje->Buscar_ProductoLote($formula_id,$valor['codigo_producto'],$v['lote'],$v['codigo_producto']);
													
									
										if(!empty($ProductoLote))
										{
											$habilitar = " checked=\"true\" disabled ";
										}
										else
											$habilitar = "  ";
											
									    $fech_vencmodulo = ModuloGetVar('app','AdminFarmacia','dias_vencimiento_product_bodega_farmacia_'.$farmacia);
								        $fecha =$v['fecha_vencimiento'];  //esta es la que viene de la DB
								        list($ano,$mes,$dia) = split( '[/.-]', $fecha );
								        $fecha = $mes."/".$dia."/".$ano;
								          
								        $fecha_actual=date("m/d/Y");
								        $fecha_compara_actual=date("Y-m-d");
								        
										$int_nodias = floor(abs(strtotime($fecha) - strtotime($fecha_actual))/86400);
										$colores['PV'] = ModuloGetVar('app','ReportesInventariosGral','color_proximo_vencer');
										$colores['VN'] = ModuloGetVar('app','ReportesInventariosGral','color_vencido');

										$fecha_uno_act= mktime(0,0,0,date('m'),date('d'),date('Y'));
										$fecha_dos= mktime(0,0,0,$mes,$dia,$ano);
										$color =" style=\"width:100%\" ";
										$vencido=0;
								        if($int_nodias<$fech_vencmodulo)
								        {
								            $color = "style=\"width:100%;background:".$colores['PV'].";\"";
											 $vencido=0;
								        }
								        if($fecha_dos<=$fecha_uno_act)
								        {
								                $color = "style=\"width:100%;background:".$colores['VN'].";\"";
								                $vencido=1;
										}
										if($vencido==0)
										{  
									    	$html .= "                                        <tr class=\"modulo_list_claro\">";
											$html .= "                                           <td>";
											$html .= "                                             <input style=\"width:100%\" type=\"text\" readonly=\"text\" class=\"input-text\" value=\"".$v['codigo_producto']."\" name=\"codigo_producto".$i."\" id=\"codigo_producto".$i."\" >";
											$html .= "                                            </td>";
											$html .= "                                           <td>".$v['producto']." ";
											$html .= "                                            </td>";
											$html .= "                                           <td>";
											$html .= "                                             <input style=\"width:100%\" type=\"text\" readonly=\"text\" class=\"input-text\" value=\"".$v['lote']."\" name=\"lote".$i."\" id=\"lote".$i."\" >";
											$html .= "                                            </td>";
											$html .= "                                           <td>";
											$fecha_vencimiento=explode("-",$v['fecha_vencimiento']);
											$fechavencimiento=$fecha_vencimiento[2]."-".$fecha_vencimiento[1]."-".$fecha_vencimiento[0];
											$html .= "                                               <input ".$color."  type=\"text\" readonly=\"text\" class=\"input-text\" value=\"".$fechavencimiento."\" name=\"fecha_vencimiento".$i."\" id=\"fecha_vencimiento".$i."\" >";
											$html .= "                                              </td>";
											$html .= "                                             <td>";
											$html .= "                                              <input style=\"width:100%\" type=\"text\" readonly=\"text\" class=\"input-text\" value=\"".$v['existencia_actual']."\" name=\"existencia_actual".$i."\" id=\"existencia_actual".$i."\" >";
											$html .= "                                           </td>";
											$html .= "                                              <td>";
											$html .= "                                                <input style=\"width:100%\" type=\"text\" class=\"input-text\" name=\"cantidad".$i."\" id=\"cantidad".$valor['orden_requisicion_id']."@".$valor['codigo_producto']."".$i."\"  value=\"$cantidad_lote\" onkeypress=\"return acceptNum(event);\" onkeyup=\"ValidarCantidad('cantidad".$valor['orden_requisicion_id']."@".$valor['codigo_producto']."".$i."',xGetElementById('cantidad".$valor['orden_requisicion_id']."@".$valor['codigo_producto']."".$i."').value,'".$v['existencia_actual']."','hell$i');\">";
											$html .= "                                             </td>";
											$html .= "                                           <td>";
											if($vencido!=1)
											$html .= "                                                <input ".$habilitar." style=\"width:100%\" type=\"checkbox\" class=\"input-text\" name=\"".$i."\" id=\"".$i."\" value=\"".$i."\" >";
											$html .= "                                               <input type=\"hidden\" name=\"registros\" id=\"registros\" value=\"".$i."\" >";
											$html .= "                                             </td>";
											$html .= "                                       </tr>";
											$i++;
											
											//$solitada=$CantidaEntregar - $v['existencia_actual'];
										}
							        }
								}
								
									$html .= "                                       <tr>";
									$html .= "                                              <td colspan=\"4\" align=\"center\">";
									$html .= "  													<div class=\"label_error\" id=\"".$valor['orden_requisicion_id']."@".$valor['codigo_producto']."\"></div>";
									$html .= "                                              </td>";
									$html .= "                                          </tr>";
									$html .= "                                     </table>\n";
									$html .= "                         </td>";
									$html .= "                      </tr>\n";
									$html .= "                                          <tr class=\"modulo_table_list_title\">\n";
									$html .= "                                         <td width=\"20%\" colspan=\"3\" align=\"center\">";
									$html .= "  												<input type=\"hidden\" name=\"formula_id\" id=\"formula_id\" value=\"".$formula_id."\">";
									$html .= "                                                <input class=\"input-submit\" type=\"button\" value=\"GUARDAR TEMPORAL\" onclick=\"xajax_GuardarPT(xajax.getFormValues('forma".$valor['formula_id']."@".$valor['codigo_producto']."','".$formula_id."'));\">";
									$html .= "                                          </td>";
									$html .= "                                        </tr>\n";
								}else
								{	
								
								        $html .= "                                   <table width=\"70%\" align=\"center\" rules=\"all\" class=\"modulo_table_list\">";
										$html .= "                                       <tr class=\"modulo_table_list_title\">\n";
										$html .= "                                       <td width=\"20%\">";
										$html .= "                                            BODEGA EXISTENCIA PRODUCTO";
										$html .= "                                        </td>";
										$html .= "                                        <td width=\"20%\">";
										
										$html .= "				  <select name=\"bodega_otra\" id=\"bodega_otra\" class=\"select\">\n";
										$html .= "            <option value = '-1'>--  SELECCIONE --</option>\n";
										$csk = "";
									$Bodegas_otras=$obje->Buscar_producto_EN_OTRA_FRM($farmacia,$centrou,$FormularioBuscador['bodega'],$valor['cod_principio_activo'],$valor['codigo_producto']);
														foreach($Bodegas_otras as $indice => $det)
										{
									
										$html .= "  <option value=\"".$det['bodega']."\" ".$sel.">".$det['bodega_des']."</option>\n";
										}
										$html .= "				  </select>\n";
										$html .= "				</td>\n";
										$html .= "                                         <td width=\"20%\" colspan=\"3\" align=\"center\">";
										$html .= "  												<input type=\"hidden\" name=\"formula_id\" id=\"formula_id\" value=\"".$formula_id."\">";
										$html .= "                                                <input class=\"input-submit\" type=\"button\" value=\"BUSCAR EN BODEGA\" onclick=\"Recargar_informacion(document.getElementById('bodega_otra').value);\">";
										$html .= "                                          </td>";
										$html .= "                                     </table>\n";
								}
								$html .= "                 </table>\n";
								$html .= "              </form>";
								$html .= "                <br>\n";
			        }
				   else
					{
						$html .= "                                   <table width=\"70%\" align=\"center\" rules=\"all\" class=\"modulo_table_list\">";
						$html .= "                                       <tr class=\"label_error\">\n";
						$html .= "                                       <td align=\"center\" >";
						$html .= "                                           ESTE MEDICAMENTO YA  FUE DESPACHADO HACE MENOS DE  $dias_tt DIA(S) <br>";
						$html .= "                                          <I>FORMULA NO : </I>".$datos_ex['formula_papel']."\n <BR> ";
						$html .= "                                          <I>CANTIDAD DESPACHADA : </I> ".round($array_can['total'])."\n<BR> ";
						$html .= "                                          <I>USUARIO QUE DESPACHO  :</I>".$datos_ex['nombre']."  \n <BR> ";
						$html .= "                                      <I>LUGAR DE DESPACHO : </I> ".$datos_ex['razon_social']."  \n ";
					                            
						
						$html .= "          <img border=\"0\"  title=\"MEDICAMENTO DISPENSADO\" src=\"".GetThemePath()."/images/alarma.gif\">\n";
						 $html .= "<embed src=\"".GetBaseURL()."/1.mid\" hidden=\"true\" type=\"midi\" loop=\"true\"></embed > ";
				
						$html .= "                                        </td>";
						$html .= "                                        </tr>\n";
						$html .= "                                   </table >";

						if($privilegios['sw_privilegios']=='1')
						{

							$html .= "	<table align=\"center\" border=\"0\" width=\"30%\" >\n";
							$autorizacion='1';
							$html .= "  <tr class=\"formulacion_table_list\">\n";
							$html .= "      <td   colspan=\"15\" align=\"CENTER\">OBSERVACIONES:</td>\n";
							$html .= "  </tr >\n";
							$html .= "  <tr class=\"modulo_table_list_title\">\n";
							$html .= "      <td   colspan=\"13\"  align=\"left\" class=\"modulo_list_claro\"> <textarea  onkeypress=\"return max(event)\"  name=\"observaciones\"  id=\"observaciones\"   rows=\"2\"  style=\"width:100%\"></textarea>\n";
							$html .= "       </td>\n";
							$html .= "  </tr >\n";


							$html .= "		<tr  align=\"center\">";
							$html .= "      <td  >";
							$html .= "      <input type=\"button\" class=\"input-submit\" value=\"AUTORIZAR DESPACHO DEL MEDICAMENTO\" style=\"width:100%\" onclick=\"xajax_Autorizacion_despacho(xajax.getFormValues('buscador'),'".$formula_id."','".$bodega_otra."',document.getElementById('observaciones').value,'".$valor['codigo_producto']."');\" >";
							$html .= "      </td>";
							$html .= "		</tr>\n";
							$html .= "    </table>";
					
					    }
			       }	
				 }else
				 {
				            $html .= "                                   <table width=\"70%\" align=\"center\" rules=\"all\" class=\"modulo_table_list\">";
							$html .= "                                       <tr class=\"label_error\">\n";
							$html .= "                                       <td align=\"center\" >";
							$html .= "                                           EL PACIENTE YA FINALIZO EL TRATAMIENTO CON ESTE PRODUCTO ";
							$html .= "          <img border=\"0\"  title=\"MEDICAMENTO DISPENSADO\" src=\"".GetThemePath()."/images/alarma.gif\">\n";
							$html .= "                                        </td>";
							  $html .= "<embed src=\"".GetBaseURL()."/1.mid\" hidden=\"true\" type=\"midi\" loop=\"true\"></embed > ";
				
							$html .= "                                        </tr>\n";
							$html .= "                                   </table >";
				    
					
							$inform_=$obje->Consultar_Formula_Verificar_pro($formula_id);
                          	
							$coun=count($inform_);
							
							$cont=0;
							foreach($inform_ as $k => $vll)
							{
	                          
								$tiempo_tratamiento_e=$vll['tiempo_tratamiento'];
								$unidad_tiempo_tratamiento_e=$vll['unidad_tiempo_tratamiento'];


								if($unidad_tiempo_tratamiento_e=='1')
								{

								$dias_ser=$tiempo_tratamiento_e * 365;
								}
								if($unidad_tiempo_tratamiento_e=='2')
								{

								$dias_ser=$tiempo_tratamiento_e *  30;
								}
								if($unidad_tiempo_tratamiento_e=='3')
								{

								$dias_ser=$tiempo_tratamiento_e *  7;
								}
								if($unidad_tiempo_tratamiento_e=='4')
								{

								$dias_ser=$tiempo_tratamiento_e *  1;
								}


								$fecha_formulacion_des=$vll['fecha_formula'];



								list($a,$m,$d) = split("-",$fecha_formulacion_des);
								$fecha_condias__d = date("Y-m-d",(mktime(0,0,0, $m,($d + $dias_ser),$a)));

								$hoy_ds=date('Y-m-d');
							
							
								if($hoy_ds >$fecha_condias__d)
								{
									$cont=$cont+1;

									
								}
							}	
							
							
							if($cont==$coun)
							{
								$INACTIVAR=$obje->UpdateEstad_Form_d($formula_id);

							}
						
						
				 
				 }
			    }
		
		}	 
    $objResponse->assign("BuscadorProductos","innerHTML",$html);
    return $objResponse;
  }

/* GUARDAR TMP */
	 function GuardarPT($Formulario,$formula_id)
	  {
	    $objResponse = new xajaxResponse();
		
	
	
		$obje = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
       	$empresa = SessionGetVar("DatosEmpresaAF");
         
	    $k=0;
	    for($i=0;$i<=$Formulario['registros'];$i++)
		{
			if($Formulario[$i]!="" && $Formulario['cantidad'.$i]!="")
			{
				$cantidad = $obje->Cantidad_ProductoTemporal($Formulario['formula_id'],$Formulario['principio_activo'],$Formulario['medicamento_formulado']);
			  
			
				if(($cantidad['total']+$Formulario['cantidad'.$i])<=$Formulario['cantidad_solicitada'])
				{
				
				    
						if($Formulario['cantidad'.$i] == "")
					    {
					      $objResponse->assign('error_doc',"innerHTML","NO HA DILIGENCIADO UNA CANTIDAD A INGRESAR");
					    }
					$Retorno = $obje->GuardarTemporal($Formulario['formula_id'],$Formulario['codigo_producto'.$i],$Formulario['cantidad'.$i],$Formulario['fecha_vencimiento'.$i],$Formulario['lote'.$i],$empresa,$Formulario['bodega'],$Formulario['medicamento_formulado']);
					$objResponse->assign("".$Formulario['formula_id']."@".$Formulario['codigo_producto']."","innerHTML",$consulta->mensajeDeError);
					
					
					if($Retorno)
						$k++;
				}
			}else
			{
				$objResponse->assign('error_doc',"innerHTML","NO HA DILIGENCIADO UNA CANTIDAD A INGRESAR");
			
			
			}
	    }
	    
		if($k>0)
		{
			$objResponse->script(" Recargar_informacion('".$empresa['bodega']."');");

			$objResponse->script("xajax_BuscarProducto1(xajax.getFormValues('buscador'),'".$Formulario['formula_id']."',1);");
			$objResponse->script("xajax_MostrarProductox('".$Formulario['formula_id']."');");
		}
	    if($Retorno === false)
	    {
	      $objResponse->assign('error_doc','innerHTML',$obje->mensajeDeError);
	    }

    /*$objResponse->assign("tablaoide","innerHTML",$salida);
    $objResponse->script("Clear();");*/
    return $objResponse;
  }
  
   /* MOSTRAR PRODUCTOS TEMPORALES */
 
		function MostrarProductox($formula_id)
		{
			$objResponse = new xajaxResponse();
			
		    $obje = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
         	$empresa = SessionGetVar("DatosEmpresaAF");
			 $farmacia=$empresa['empresa_id'];
		
			$vector=$obje->Buscar_producto_tmp_c($formula_id);
				
		    if(!empty($vector))
			{
			
			
					$html .= "                 <table width=\"75%\" align=\"center\" class=\"modulo_table_list\">\n";
					$html .= "                    <tr  class=\"formulacion_table_list\" >\n";
					$html .= "                       <td align=\"center\" width=\"12%\">\n";
					$html .= "                        <a title='CODIGO DEL PRODUCTO'>CODIGO<a> ";
					$html .= "                      </td>\n";
					$html .= "                       <td align=\"center\" width=\"30%\">\n";
					$html .= "                        <a title='DESCRIPCION DEL PRODUCTO'>DESCRIPCION<a>";
					$html .= "                      </td>\n";
					$html .= "                       <td width=\"10%\">LOTE</td>\n";    
					$html .= "                      <td width=\"15%\">FECHA VENCIMIENTO</td>\n";    
					$html .= "                       <td align=\"center\" width=\"12%\">\n";
					$html .= "                        CANTIDAD";
					$html .= "                      </td>\n";
					
					$html .= "                      <td align=\"center\" width=\"5%\">\n";
					$html .= "                         <a title='ELIMINAR REGISTRO'>X<a>";
					$html .= "                       </td>\n";
					$html .= "                    </tr>\n";
			
					foreach($vector as $key=>$detalle)
					{
							$fech_vencmodulo = ModuloGetVar('app','AdminFarmacia','dias_vencimiento_product_bodega_farmacia_'.$farmacia);

							$fecha =$detalle['fecha_vencimiento'];  //esta es la que viene de la DB
							list($ano,$mes,$dia) = split( '[/.-]', $fecha );
							$fecha = $mes."/".$dia."/".$ano;

							$fecha_actual=date("m/d/Y");
							$fecha_compara_actual=date("Y-m-d");
							//Mes/Dia/Año  "02/02/2010
							$int_nodias = floor(abs(strtotime($fecha) - strtotime($fecha_actual))/86400);
							$colores['PV'] = ModuloGetVar('app','ReportesInventariosGral','color_proximo_vencer');
							$colores['VN'] = ModuloGetVar('app','ReportesInventariosGral','color_vencido');

							$fecha_uno_act= mktime(0,0,0,date('m'),date('d'),date('Y'));
							$fecha_dos= mktime(0,0,0,$mes,$dia,$ano);
							$color ="";
							if($int_nodias<$fech_vencmodulo)
							{
							$color = "style=\"background:".$colores['PV']."\"";
							}

							if($fecha_dos<=$fecha_uno_act)
							{
							$color = "style=\"background:".$colores['VN']."\"";
							}
									
						$html .= "                     <tr class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
						$html .= "                       <td align=\"left\" class=\"label_mark\">\n";
						$html .= "                        ".$detalle['codigo_producto_mini'];
						$html .= "                       </td>\n";
						$html .= "                       <td align=\"left\" class=\"label_mark\">\n";
						$html .= "                         ".$detalle['descripcion_prod'];
						$html .= "                      </td>\n";
						$html .= "                       <td class=\"label_mark\">".$detalle['lote']."</td>\n";
						$html .= "                      <td align=\"center\" class=\"label_mark\" ".$color.">".$detalle['fecha_vencimiento']."</td>\n";
						$html .= "                      <td align=\"right\" class=\"label_mark\">\n";
						$html .= "                        ".$detalle['cantidad_despachada'];
						$html .= "                      </td>\n";
					
					
					$html .= "				<td  width=\"5%\" align=\"center\"  >\n";
					$html .= "					<a href=\"#\" onclick=\"xajax_Eliminar_codigo_prodcto_d('".$formula_id."','".$detalle['codigo_producto']."','".$detalle['esm_dispen_tmp_id']."')\" class=\"label_error\"  ><img src=\"".GetThemePath()."/images/delete2.gif\" border='0' >\n";
					$html .= "					</a></center>\n";
					$html .= "			</td>\n";		
				
					$html .= "                   </tr>\n";
					}

					$html .= "                    </table><BR>\n";
					
					$html .= "                 <table width=\"75%\" align=\"center\" >\n";
					$html .= "                                         <td width=\"20%\" colspan=\"3\" align=\"center\">";
					$html .= "  												<input type=\"hidden\" name=\"formula_id\" id=\"formula_id\" value=\"".$formula_id."\">";
					$html .= "                                                <input class=\"input-submit\" type=\"button\" value=\"REALIZAR ENTREGA\" onclick=\"xajax_Cambiarvetana('".$formula_id."');\">";
					$html .= "                                          </td>";
					$html .= "                    </table>\n";
					
					//$objResponse->call("super");
				}
					else
					{
					$html .= "                  <table width=\"80%\" align=\"center\">\n";
					$html .= "                   <tr>\n";
					$html .= "                   <td align=\"center\">\n";
					$html .= "                      <label class='label_error'> ESTE DOCUMENTO NO TIENE PRODUCTOS ASIGNADOS</label>";
					$html .= "                   </td>\n";
					$html .= "                  </tr>\n";
					$html .= "                  </table>\n";
					}
					
					$objResponse->assign("productostmp","innerHTML",$html);

					return $objResponse;

		}
	/* ELIMINAR PRODUCTO */
	
	function Eliminar_codigo_prodcto_d($formula_id,$codigo_producto,$esm_dispen_tmp_id)
		{
			$objResponse = new xajaxResponse();
		    $obje = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
         	$empresa = SessionGetVar("DatosEmpresaAF");
			 $farmacia=$empresa['empresa_id'];
		
			$vector=$obje->EliminarDatosTMP_DISPENSACION($formula_id,$codigo_producto,$esm_dispen_tmp_id);
				
		    if($vector)
			{
			  // 	$objResponse->script("xajax_BuscarProducto1(xajax.getFormValues('buscador'),'".$Formulario['formula_id']."',1);");
			    $objResponse->script("xajax_MostrarProductox('".$formula_id."');");
	        }
			
			
					return $objResponse;

		}
	/* CAMBIAR DE VENTANA */
	
		function Cambiarvetana($formula_id)
		{
			$objResponse = new xajaxResponse();
      	
			$url=ModuloGetURL("app", "DispensacionESM", "controller", "Preparar_Documento_Dispensacion", array("formula_id"=>$formula_id));
			$objResponse->script('
						 window.location="'.$url.'";
							');
		    return $objResponse;
		}
		
		
		/* CAMBIAR DE VENTANA PENDIENTES */
	
		function Cambiarvetana2($formula_id)
		{
			$objResponse = new xajaxResponse();
      	
			$url=ModuloGetURL("app", "DispensacionESM", "controller", "Preparar_Documento_Dispensacion_Pendientes", array("formula_id"=>$formula_id));
			$objResponse->script('
						 window.location="'.$url.'";
							');
		    return $objResponse;
		}
	
	 function PacienteReclama($observacion,$formula_id,$pendiente,$observacion2)
    {
      
			$objResponse = new xajaxResponse();
			
			$url=ModuloGetURL("app", "DispensacionESM", "controller", "GenerarEntregaMedicamentos",array("observacion"=>$observacion."-".$observacion2,"formula_id"=>$formula_id,"pendiente"=>$pendiente));
			$objResponse->script('
					 window.location="'.$url.'";
					');
		    return $objResponse;
      
	  }
	 /**
	*Funcion que sirve de enlace con otra funcion cuando no es el paciente quien reclama los medicamentos
	* @param string $observacion  cadena con la observacion ingresada al  realizar el despacho
	* @return Object $objResponse objeto de respuesta al formulario  	
	*/
      
		function PersonaRclama($observacion,$formula_id)
		{
		  $objResponse = new xajaxResponse();
			$url=ModuloGetURL("app", "DispensacionESM", "controller", "DatosPersonaReclama",array("observacion"=>$observacion,"formula_id"=>$formula_id));
			$objResponse->script('
						 window.location="'.$url.'";
							');
			return $objResponse;
			
		}
	
	/* pendiente */
	
	function BuscarProducto2($FormularioBuscador,$formula_id,$bodega_otra)
    {
			$objResponse = new xajaxResponse();
			$obje = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
			$busqueda=$obje->Consultar_Medicamentos_Detalle_P($FormularioBuscador,$formula_id);
			//$objResponse->alert(print_r($busqueda),true);
			$cantidad_entrega=$busqueda[0]['cantidad'];
		   	
			$empresa = SessionGetVar("DatosEmpresaAF");
			$farmacia=$empresa['empresa_id'];
			$centrou=$empresa['centro_utilidad'];
		
			$html .= "                 <div id=\"erro\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:11px;\">\n";
			$html .= "                 </div>\n";
			$html .= "                                    <div id=\"error\" class='label_error'></div>";
       
			foreach($busqueda as $k => $valor)
			{
				$cantidad = $obje->Cantidad_ProductoTemporal($formula_id,$valor['cod_principio_activo'],$valor['codigo_producto']);
						//$objResponse->alert(print_r($cantidad),true);
						
						$cantidad_entrega=round($valor['cantidad']);
						$cantidad_=0;
						
						if($cantidad['codigo_formulado']==$valor['codigo_producto'])
						{	
								
								    
											$cantidad_=$cantidad['total'];					
									
						}

				
				$html .= "                 <form id=\"forma".$formula_id."@".$valor['codigo_producto']."\" name=\"".$formula_id."@".$valor['codigo_producto']."\" action=\"\" method=\"post\">\n";
				$html .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$html .= "                    <tr class=\"modulo_table_list_title\">\n";
				$html .= "                     <td width=\"50%\">PRODUCTO: ".$valor['codigo_producto_mini']." &nbsp; ".$valor['descripcion_prod'].". </td>
				<td>CANTIDAD SOLICITADA <input readonly=\"true\" type=\"input-text\" name=\"cantidad_solicitada\" id=\"cantidad_solicitada\" value=\"".$cantidad_entrega."\" class=\"input-text\"></td><td>CANTIDAD PENDIENTE <input readonly=\"true\" type=\"input-text\" name=\"cantidad_pendiente\" id=\"cantidad_pendiente\" value=\"".($cantidad_entrega-$cantidad_)."\" class=\"input-text\"></td>\n";
				$html .= "                        <input type=\"hidden\" name=\"formula_id\" id=\"formula_id\" value=\"".$formula_id."\">";
				$html .= "                        <input type=\"hidden\" name=\"codigo_producto\" id=\"codigo_producto\" value=\"".$valor['codigo_producto']."\">";
				$html .= "                        <input type=\"hidden\" name=\"bodega\" id=\"bodega\" value=\"".$FormularioBuscador['bodega']."\">";
				$html .= "                        <input type=\"hidden\" name=\"principio_activo\" id=\"principio_activo\" value=\"".$valor['cod_principio_activo']."\">";
                $html .= "                        <input type=\"hidden\" name=\"medicamento_formulado\" id=\"medicamento_formulado\" value=\"".$valor['codigo_producto']."\">";
				$html .= "                     </td>";
				$html .= "                    </tr>\n";

				$html .= "                   <tr class=\"modulo_list_claro\">\n";
				$html .= "                      <td colspan=\"3\" align=\"center\">";
				$Existencias=$obje->Consultar_ExistenciasBodegas($valor['cod_principio_activo'],$FormularioBuscador,$farmacia,$centrou,$bodega,$valor['codigo_producto']);      
				 
				if(!empty($Existencias))
				{
	      				$html .= "                                   <table width=\"70%\" align=\"center\" rules=\"all\" class=\"modulo_table_list\">";
						$html .= "                                       <tr class=\"modulo_table_list_title\">\n";
						$html .= "                                       <td width=\"25%\">";
						$html .= "                                            CODIGO  ";
						$html .= "                                        </td>";
						$html .= "                                       <td width=\"20%\">";
						$html .= "                                            PRODUCTO  ";
						$html .= "                                        </td>";
						$html .= "                                       <td width=\"20%\">";
						$html .= "                                            LOTE";
						$html .= "                                        </td>";
						$html .= "                                        <td width=\"20%\">";
						$html .= "                                              FECHA VENCIMIENTO";
						$html .= "                                        </td>";
						$html .= "                                       <td width=\"5%\">";
						$html .= "                                             EXISTENCIA";
						$html .= "                                      </td>";
						$html .= "                                        <td width=\"5%\">";
						$html .= "                                              CANTIDAD";
						$html .= "                                        </td>";
						$html .= "                                        <td width=\"5%\">";
						$html .= "                                              SEL";
						$html .= "                                        </td>";
						$html .= "                                        </tr>\n";

		      $i=0;
			  	
		       foreach($Existencias as $key=>$v)
		        {
				//$ProductoLote=$obje->Buscar_ProductoLote($formula_id,$valor['codigo_producto'],$v['lote']);
		      	$ProductoLote=$obje->Buscar_ProductoLote($formula_id,$valor['codigo_producto'],$v['lote'],$v['codigo_producto']);
											
				if(!empty($ProductoLote))
				{
					$habilitar = " checked=\"true\" disabled ";
				}
				else
					$habilitar = "  ";
					
			
			    $fech_vencmodulo = ModuloGetVar('app','AdminFarmacia','dias_vencimiento_product_bodega_farmacia_'.$farmacia);
			    $fecha =$v['fecha_vencimiento'];  //esta es la que viene de la DB
		        list($ano,$mes,$dia) = split( '[/.-]', $fecha );
		        $fecha = $mes."/".$dia."/".$ano;
		          
		        $fecha_actual=date("m/d/Y");
		        $fecha_compara_actual=date("Y-m-d");
		        
		        $int_nodias = floor(abs(strtotime($fecha) - strtotime($fecha_actual))/86400);
		        $colores['PV'] = ModuloGetVar('app','ReportesInventariosGral','color_proximo_vencer');
		        $colores['VN'] = ModuloGetVar('app','ReportesInventariosGral','color_vencido');
		            
		        $fecha_uno_act= mktime(0,0,0,date('m'),date('d'),date('Y'));
		        $fecha_dos= mktime(0,0,0,$mes,$dia,$ano);
		        $color =" style=\"width:100%\" ";
				$vencido=0;
		        if($int_nodias<$fech_vencmodulo)
		        {
		            $color = "style=\"width:100%;background:".$colores['PV'].";\"";
					 $vencido=0;
		        }
		          
		        if($fecha_dos<=$fecha_uno_act)
		        {
		            $color = "style=\"width:100%;background:".$colores['VN'].";\"";
		            $vencido=1;
				}
				if($vencido==0)
				{  					
						/*/if($todo=='1')
						{
							$cantidad_lote="";
							break;
						}
						$solitada=$cantidad_entrega;
						
														
						if($solitada >$v['existencia_actual'] && $todo!='1')
						{
							$cantidad_lote = $v['existencia_actual'];
							$solitada=$solitada - $v['existencia_actual'];
						}
						
						if($solitada <$v['existencia_actual'])
						{
							$cantidad_lote = $solitada;
							$solitada = 0; 
							$todo='1';
							
						}*/
				$html .= "                                        <tr class=\"modulo_list_claro\">";
				$html .= "                                           <td>";
				$html .= "                                             <input style=\"width:100%\" type=\"text\" readonly=\"text\" class=\"input-text\" value=\"".$v['codigo_producto']."\" name=\"codigo_producto".$i."\" id=\"codigo_producto".$i."\" >";
				$html .= "                                            </td>";
				$html .= "                                           <td>".$v['producto']." ";
				$html .= "                                            </td>";
				$html .= "                                           <td>";
				$html .= "                                             <input style=\"width:100%\" type=\"text\" readonly=\"text\" class=\"input-text\" value=\"".$v['lote']."\" name=\"lote".$i."\" id=\"lote".$i."\" >";
				$html .= "                                            </td>";
				$html .= "                                           <td>";
				$fecha_vencimiento=explode("-",$v['fecha_vencimiento']);
				$fechavencimiento=$fecha_vencimiento[2]."-".$fecha_vencimiento[1]."-".$fecha_vencimiento[0];
				$html .= "                                               <input ".$color."  type=\"text\" readonly=\"text\" class=\"input-text\" value=\"".$fechavencimiento."\" name=\"fecha_vencimiento".$i."\" id=\"fecha_vencimiento".$i."\" >";
				$html .= "                                              </td>";
				$html .= "                                             <td>";
				$html .= "                                              <input style=\"width:100%\" type=\"text\" readonly=\"text\" class=\"input-text\" value=\"".$v['existencia_actual']."\" name=\"existencia_actual".$i."\" id=\"existencia_actual".$i."\" >";
				$html .= "                                           </td>";
				$html .= "                                              <td>";
				$html .= "                                                <input style=\"width:100%\" type=\"text\" class=\"input-text\" name=\"cantidad".$i."\" id=\"cantidad".$valor['orden_requisicion_id']."@".$valor['codigo_producto']."".$i."\"   value=\"$cantidad_lote\"  onkeypress=\"return acceptNum(event);\" onkeyup=\"ValidarCantidad('cantidad".$valor['orden_requisicion_id']."@".$valor['codigo_producto']."".$i."',xGetElementById('cantidad".$valor['orden_requisicion_id']."@".$valor['codigo_producto']."".$i."').value,'".$v['existencia_actual']."','hell$i');\">";
				$html .= "                                             </td>";
				$html .= "                                           <td>";
				if($vencido!=1)
				$html .= "                                                <input ".$habilitar." style=\"width:100%\" type=\"checkbox\" class=\"input-text\" name=\"".$i."\" id=\"".$i."\" value=\"".$i."\" >";
				$html .= "                                               <input type=\"hidden\" name=\"registros\" id=\"registros\" value=\"".$i."\" >";
				$html .= "                                             </td>";
				$html .= "                                       </tr>";
		      $i++;
	          }
			}
			$html .= "                                       <tr>";
			$html .= "                                              <td colspan=\"4\" align=\"center\">";
			$html .= "  													<div class=\"label_error\" id=\"".$valor['orden_requisicion_id']."@".$valor['codigo_producto']."\"></div>";
			$html .= "                                              </td>";
			$html .= "                                          </tr>";
			$html .= "                                     </table>\n";
			$html .= "                         </td>";
			$html .= "                      </tr>\n";
			$html .= "                                          <tr class=\"modulo_table_list_title\">\n";
			$html .= "                                         <td width=\"20%\" colspan=\"3\" align=\"center\">";
			$html .= "  												<input type=\"hidden\" name=\"formula_id\" id=\"formula_id\" value=\"".$formula_id."\">";
		//	$html .= "  												<input type=\"hidden\" name=\"bodegas_doc_id\" id=\"bodegas_doc_id\" value=\"".$FormularioBuscador['bodegas_doc_id']."\">";
			$html .= "                                                <input class=\"input-submit\" type=\"button\" value=\"GUARDAR TEMPORAL\" onclick=\"xajax_GuardarPTP(xajax.getFormValues('forma".$formula_id."@".$valor['codigo_producto']."'),'".$formula_id."');\">";
			$html .= "                                          </td>";
			$html .= "                                        </tr>\n";
	      }else
			{
			
			        $html .= "                                   <table width=\"70%\" align=\"center\" rules=\"all\" class=\"modulo_table_list\">";
					$html .= "                                       <tr class=\"modulo_table_list_title\">\n";
					$html .= "                                       <td width=\"20%\">";
					$html .= "                                            BODEGA EXISTENCIA PRODUCTO";
					$html .= "                                        </td>";
					$html .= "                                        <td width=\"20%\">";
					$html .= "				  <select name=\"bodega_otra\" id=\"bodega_otra\" class=\"select\">\n";
					$html .= "            <option value = '-1'>--  SELECCIONE --</option>\n";
					$csk = "";
			
					$Bodegas_otras=$obje->Buscar_producto_EN_OTRA_FRM($farmacia,$centrou,$FormularioBuscador['bodega'],$valor['principio_activo'],$valor['codigo_producto']);
					foreach($Bodegas_otras as $indice => $det)
					{
				
					$html .= "  <option value=\"".$det['bodega']."\" ".$sel.">".$det['bodega_des']."</option>\n";
					}
					$html .= "				  </select>\n";
					$html .= "				</td>\n";
					$html .= "                                         <td width=\"20%\" colspan=\"3\" align=\"center\">";
					$html .= "  												<input type=\"hidden\" name=\"formula_id\" id=\"formula_id\" value=\"".$formula_id."\">";
					//	$html .= "  												<input type=\"hidden\" name=\"bodegas_doc_id\" id=\"bodegas_doc_id\" value=\"".$FormularioBuscador['bodegas_doc_id']."\">"; BuscarProducto1($FormularioBuscador,$formula_id)
					$html .= "                                                <input class=\"input-submit\" type=\"button\" value=\"BUSCAR EN BODEGA\" onclick=\"Recargar_informacion(document.getElementById('bodega_otra').value);\">";
					$html .= "                                          </td>";
					$html .= "                                     </table>\n";
			
			
			
			}
			$html .= "                 </table>\n";
			$html .= "              </form>";
			$html .= "                <br>\n";
      }
	 
    $objResponse->assign("BuscadorProductos","innerHTML",$html);
    return $objResponse;
  }
  /* GUARDAR TMP */
	 function GuardarPTP($Formulario,$formula_id)
	  {
	    $objResponse = new xajaxResponse();
	
	
		$obje = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
       	$empresa = SessionGetVar("DatosEmpresaAF");
       
		 
	    $k=0;
	    for($i=0;$i<=$Formulario['registros'];$i++)
		{
			if($Formulario[$i]!="" && $Formulario['cantidad'.$i]!="")
			{
				
                 $cantidad = $obje->Cantidad_ProductoTemporal($Formulario['formula_id'],$Formulario['principio_activo'],$Formulario['medicamento_formulado']);
			   				if(($cantidad['total']+$Formulario['cantidad'.$i])<=$Formulario['cantidad_solicitada'])
				{
				
				    
						if($Formulario['cantidad'.$i] == "")
					    {
					      $objResponse->assign('error_doc',"innerHTML","NO HA DILIGENCIADO UNA CANTIDAD A INGRESAR");
					    }
					$Retorno = $obje->GuardarTemporal($Formulario['formula_id'],$Formulario['codigo_producto'.$i],$Formulario['cantidad'.$i],$Formulario['fecha_vencimiento'.$i],$Formulario['lote'.$i],$empresa,$Formulario['bodega'],$Formulario['medicamento_formulado']);
					
					$objResponse->assign("".$Formulario['formula_id']."@".$Formulario['codigo_producto']."","innerHTML",$consulta->mensajeDeError);
					
					
					if($Retorno)
						$k++;
				}
			}else
			{
			
				$objResponse->assign('error_doc',"innerHTML","NO HA DILIGENCIADO UNA CANTIDAD A INGRESAR");
			}		  
	    }
	    
		if($k>0)
		{
			$objResponse->script(" Recargar_informacion('".$empresa['bodega']."');");

			$objResponse->script("xajax_BuscarProducto2(xajax.getFormValues('buscador'),'".$Formulario['formula_id']."',1);");
			$objResponse->script("xajax_MostrarProductox2('".$Formulario['formula_id']."');");
		}
	    if($Retorno === false)
	    {
	      $objResponse->assign('error_doc','innerHTML',$obje->mensajeDeError);
	    }

    /*$objResponse->assign("tablaoide","innerHTML",$salida);
    $objResponse->script("Clear();");*/
    return $objResponse;
  }
   /* MOSTRAR PRODUCTOS TEMPORALES */
 
		function MostrarProductox2($formula_id)
		{
			$objResponse = new xajaxResponse();
		    $obje = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
         	$empresa = SessionGetVar("DatosEmpresaAF");
			 $farmacia=$empresa['empresa_id'];
		
			$vector=$obje->Buscar_producto_tmp_c($formula_id);
				
		    if(!empty($vector))
			{
			
			
					$html .= "                 <table width=\"75%\" align=\"center\" class=\"modulo_table_list\">\n";
					$html .= "                    <tr  class=\"formulacion_table_list\" >\n";
					$html .= "                       <td align=\"center\" width=\"12%\">\n";
					$html .= "                        <a title='CODIGO DEL PRODUCTO'>CODIGO<a> ";
					$html .= "                      </td>\n";
					$html .= "                       <td align=\"center\" width=\"30%\">\n";
					$html .= "                        <a title='DESCRIPCION DEL PRODUCTO'>DESCRIPCION<a>";
					$html .= "                      </td>\n";
					$html .= "                       <td width=\"10%\">LOTE</td>\n";    
					$html .= "                      <td width=\"15%\">FECHA VENCIMIENTO</td>\n";    
					$html .= "                       <td align=\"center\" width=\"12%\">\n";
					$html .= "                        CANTIDAD";
					$html .= "                      </td>\n";
					
					$html .= "                      <td align=\"center\" width=\"5%\">\n";
					$html .= "                         <a title='ELIMINAR REGISTRO'>X<a>";
					$html .= "                       </td>\n";
					$html .= "                    </tr>\n";
			
					foreach($vector as $key=>$detalle)
					{
							$fech_vencmodulo = ModuloGetVar('app','AdminFarmacia','dias_vencimiento_product_bodega_farmacia_'.$farmacia);

							$fecha =$detalle['fecha_vencimiento'];  //esta es la que viene de la DB
							list($ano,$mes,$dia) = split( '[/.-]', $fecha );
							$fecha = $mes."/".$dia."/".$ano;

							$fecha_actual=date("m/d/Y");
							$fecha_compara_actual=date("Y-m-d");
							//Mes/Dia/Año  "02/02/2010
							$int_nodias = floor(abs(strtotime($fecha) - strtotime($fecha_actual))/86400);
							$colores['PV'] = ModuloGetVar('app','ReportesInventariosGral','color_proximo_vencer');
							$colores['VN'] = ModuloGetVar('app','ReportesInventariosGral','color_vencido');

							$fecha_uno_act= mktime(0,0,0,date('m'),date('d'),date('Y'));
							$fecha_dos= mktime(0,0,0,$mes,$dia,$ano);
							$color ="";
							if($int_nodias<$fech_vencmodulo)
							{
							$color = "style=\"background:".$colores['PV']."\"";
							}

							if($fecha_dos<=$fecha_uno_act)
							{
							$color = "style=\"background:".$colores['VN']."\"";
							}
									
						$html .= "                     <tr class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
						$html .= "                       <td align=\"left\" class=\"label_mark\">\n";
						$html .= "                        ".$detalle['codigo_producto_mini'];
						$html .= "                       </td>\n";
						$html .= "                       <td align=\"left\" class=\"label_mark\">\n";
						$html .= "                         ".$detalle['descripcion_prod'];
						$html .= "                      </td>\n";
						$html .= "                       <td class=\"label_mark\">".$detalle['lote']."</td>\n";
						$html .= "                      <td align=\"center\" class=\"label_mark\" ".$color.">".$detalle['fecha_vencimiento']."</td>\n";
						$html .= "                      <td align=\"right\" class=\"label_mark\">\n";
						$html .= "                        ".$detalle['cantidad_despachada'];
						$html .= "                      </td>\n";
					
					
					$html .= "				<td  width=\"5%\" align=\"center\"  >\n";
					$html .= "					<a href=\"#\" onclick=\"xajax_Eliminar_codigo_prodcto_d2('".$formula_id."','".$detalle['codigo_producto']."','".$detalle['esm_dispen_tmp_id']."')\" class=\"label_error\"  ><img src=\"".GetThemePath()."/images/delete2.gif\" border='0' >\n";
					$html .= "					</a></center>\n";
					$html .= "			</td>\n";		
				
					$html .= "                   </tr>\n";
					}

					$html .= "                    </table><BR>\n";
					
					$html .= "                 <table width=\"75%\" align=\"center\" >\n";
					$html .= "                                         <td width=\"20%\" colspan=\"3\" align=\"center\">";
					$html .= "  												<input type=\"hidden\" name=\"formula_id\" id=\"formula_id\" value=\"".$formula_id."\">";
					$html .= "                                                <input class=\"input-submit\" type=\"button\" value=\"REALIZAR ENTREGA\" onclick=\"xajax_Cambiarvetana2('".$formula_id."');\">";
					$html .= "                                          </td>";
					$html .= "                    </table>\n";
					
					//$objResponse->call("super");
				}
					else
					{
					$html .= "                  <table width=\"80%\" align=\"center\">\n";
					$html .= "                   <tr>\n";
					$html .= "                   <td align=\"center\">\n";
					$html .= "                      <label class='label_error'> ESTE DOCUMENTO NO TIENE PRODUCTOS ASIGNADOS</label>";
					$html .= "                   </td>\n";
					$html .= "                  </tr>\n";
					$html .= "                  </table>\n";
					}
					
					$objResponse->assign("productostmp","innerHTML",$html);

					return $objResponse;

		}
  /* ELIMINAR PRODUCTO */
	
	function Eliminar_codigo_prodcto_d2($formula_id,$codigo_producto,$esm_dispen_tmp_id)
		{
			$objResponse = new xajaxResponse();
		    $obje = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
         	$empresa = SessionGetVar("DatosEmpresaAF");
			 $farmacia=$empresa['empresa_id'];
		
			$vector=$obje->EliminarDatosTMP_DISPENSACION($formula_id,$codigo_producto,$esm_dispen_tmp_id);
				
		    if($vector)
			{
			  // 	$objResponse->script("xajax_BuscarProducto1(xajax.getFormValues('buscador'),'".$Formulario['formula_id']."',1);");
			    $objResponse->script("xajax_MostrarProductox2('".$formula_id."');");
	        }
			
			
					return $objResponse;

		}
	/*AUTORIZACION DEL DESPACHO */
	
	function Autorizacion_despacho($Formulario,$formula_id,$bodega_otra,$observacion,$producto)
		{
			$objResponse = new xajaxResponse();
		    $obje = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
         	
		
			$vector=$obje->UpdateAutorizacion_por_medicamento($formula_id,$observacion,$producto);
				
		    if($vector==true)
			{
			  
			    $objResponse->script("xajax_BuscarProducto1(xajax.getFormValues('buscador'),'".$formula_id."','".$bodega_otra."');");
	        }
			
			
			return $objResponse;

		}
	
	
	
?>