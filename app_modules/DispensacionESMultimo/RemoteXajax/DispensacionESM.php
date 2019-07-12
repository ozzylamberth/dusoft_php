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
		if($FormularioBuscador['sw_ambulatoria']=='1')
		{
		    $busqueda=$obje->Consultar_Medicamentos_Detalle_AMBU($FormularioBuscador,$formula_id);
		  
			if(!empty($busqueda))
			{
		        foreach($busqueda as $k => $valor)
				{  
						$html .= "                 <div id=\"erro\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:11px;\">\n";
						$html .= "                 </div>\n";
						$html .= "                                    <div id=\"error\" class='label_error'></div>";
				       $cantidad = $obje->Cantidad_ProductoTemporal($formula_id,$valor['cod_principio_activo']);
				       $CantidaEntregar=round($valor['cantidad']);
						$html .= "                 <form id=\"forma".$formula_id."@".$valor['codigo_producto']."\" name=\"".$formula_id."@".$valor['codigo_producto']."\" action=\"\" method=\"post\">\n";
						$html .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
						$html .= "                    <tr class=\"modulo_table_list_title\">\n";
						$html .= "                     <td width=\"50%\">PRODUCTO: ".$valor['codigo_producto']." &nbsp; ".$valor['descripcion_prod'].". </td>
						<td>CANTIDAD SOLICITADA <input readonly=\"true\" type=\"input-text\" name=\"cantidad_solicitada\" id=\"cantidad_solicitada\" value=\"".$CantidaEntregar."\" class=\"input-text\"></td><td>CANTIDAD PENDIENTE <input readonly=\"true\" type=\"input-text\" name=\"cantidad_pendiente\" id=\"cantidad_pendiente\" value=\"".($CantidaEntregar-$cantidad['total'])."\" class=\"input-text\"></td>\n";
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
									  	
							foreach($Existencias as $key=>$v)
							{
								$ProductoLote=$obje->Buscar_ProductoLote($formula_id,$valor['codigo_producto'],$v['lote']);
								
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
									$Bodegas_otras=$obje->Buscar_producto_EN_OTRA_FRM($farmacia,$centrou,$FormularioBuscador['bodega'],$valor['cod_principio_activo']);
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
			}
		}else
		{
			$busqueda=$obje->Consultar_Medicamentos_Detalle($FormularioBuscador,$formula_id);
		
			foreach($busqueda as $k => $valor)
			{
			
				if($valor['sw_durante_tratamiento']=='1')
				{
				   
					$Conversion=$obje->ConsultarFactorConversion($valor['codigo_producto']);
					$unidad_dosif=$Conversion['0']['unidad_dosificacion'];	
					$factor_conversion=$Conversion['0']['factor_conversion'];	
					
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


					$Entregar=($cantidad_e/$factor_conversion);
				    
					$CantidaEntregar=intval($Entregar);
					
			       
				
 /*


					$fecha_formulacion=$busqueda[0]['fecha_formula'];

					list($year, $month, $day) =explode("-", $fecha_formulacion);
					$fecha_formulacion= mktime(0, 0, 0, $month, $day, $year); 

					$tiempo_tratamiento=$busqueda[0]['tiempo_tratamiento'];

					$unidad_tiempo_tratamiento=$busqueda[0]['unidad_tiempo_tratamiento'];



					if($unidad_tiempo_tratamiento=='1')
					{

					$dias_s=$tiempo_tratamiento * 365;
					}
					if($unidad_tiempo_tratamiento=='2')
					{

					$dias_s=$tiempo_tratamiento *  30;
					}
					if($unidad_tiempo_tratamiento=='3')
					{

					$dias_s=$tiempo_tratamiento *  7;
					}
					if($unidad_tiempo_tratamiento=='4')
					{

					$dias_s=$tiempo_tratamiento *  1;
					}





					
					$Conversion=$obje->ConsultarFactorConversion($busqueda[0]['codigo_producto']);
					$unidad_dosif=$Conversion['0']['unidad_dosificacion'];	
					$factor_conversion=$Conversion['0']['factor_conversion'];	








					$CantidaEntregar=($cantidad_entrega/$factor_conversion);
*/

				}
				
				$html .= "                 <div id=\"erro\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:11px;\">\n";
				$html .= "                 </div>\n";
				$html .= "                                    <div id=\"error\" class='label_error'></div>";
				$cantidad = $obje->Cantidad_ProductoTemporal($formula_id,$valor['cod_principio_activo']);
			   	  
				$html .= "                 <form id=\"forma".$formula_id."@".$valor['codigo_producto']."\" name=\"".$formula_id."@".$valor['codigo_producto']."\" action=\"\" method=\"post\">\n";
				$html .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$html .= "                    <tr class=\"modulo_table_list_title\">\n";
				$html .= "                     <td width=\"50%\">PRODUCTO: ".$valor['codigo_producto']." &nbsp; ".$valor['descripcion_prod'].". </td>
				<td>CANTIDAD SOLICITADA <input readonly=\"true\" type=\"input-text\" name=\"cantidad_solicitada\" id=\"cantidad_solicitada\" value=\"".$CantidaEntregar."\" class=\"input-text\"></td><td>CANTIDAD PENDIENTE <input readonly=\"true\" type=\"input-text\" name=\"cantidad_pendiente\" id=\"cantidad_pendiente\" value=\"".($CantidaEntregar-$cantidad['total'])."\" class=\"input-text\"></td>\n";
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
					  	
						foreach($Existencias as $key=>$v)
						{
							   $ProductoLote=$obje->Buscar_ProductoLote($formula_id,$valor['codigo_producto'],$v['lote']);
						    
						
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
							$Bodegas_otras=$obje->Buscar_producto_EN_OTRA_FRM($farmacia,$centrou,$FormularioBuscador['bodega'],$valor['cod_principio_activo']);
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
		}	
			
		/*	$today = date("Y-m-d"); 
			$hoy=explode("-", $today);
			$hoy_fecha= $hoy[2]."/".$hoy[1]."/".$hoy[0];
    		$dias_dipensados= ModuloGetVar('','','dispensacion_dias_ultima_entrega');
					
			list($a,$m,$d) = split("-",$today);
			$fecha_condias = date("Y-m-d",(mktime(0,0,0, $m,($d - $dias_dipensados),$a)));
		
			$fecha_condias_d=explode("-", $fecha_condias);
		  				
		    $tiempo_tratamiento_=$busqueda[0]['tiempo_tratamiento'];
			$unidad_tiempo_tratamiento_=$busqueda[0]['unidad_tiempo_tratamiento'];
				 
			
				 
				 if($unidad_tiempo_tratamiento_=='1')
				{
				 
				    $dias_se=$tiempo_tratamiento_ * 365;
				}
				 if($unidad_tiempo_tratamiento_=='2')
				{
				 
				    $dias_se=$tiempo_tratamiento_ *  30;
				}
				 if($unidad_tiempo_tratamiento_=='3')
				{
				 
				    $dias_se=$tiempo_tratamiento_ *  7;
				}
				 if($unidad_tiempo_tratamiento_=='4')
				{
				 
				    $dias_se=$tiempo_tratamiento_ *  1;
				}
				
				
				$fecha_formulacion_de=$busqueda[0]['fecha_formula'];
			    
				
				
				list($a,$m,$d) = split("-",$fecha_formulacion_de);
		        $fecha_condias__ = date("Y-m-d",(mktime(0,0,0, $m,($d + $dias_se),$a)));
			
				
	
		$hoy_d=date('Y-m-d');
		if($hoy_d <=$fecha_condias__)
		{
		 $dispensar='SI';
		 
		 //$objResponse->alert("se puede dispensar");
		
		}else
		{
		// $objResponse->alert("no se puede dispensar");
		   $dispensar='NO';
		}
		if($dispensar=='SI')	
		{
		
		
			$datos_ex=$obje->ConsultarUltimoResg_Dispens_($formula_id,$today,$fecha_condias,$busqueda[0]['cod_principio_activo'],$busqueda[0]['paciente_id'],$busqueda[0]['tipo_id_paciente']);
		
			if(empty($datos_ex))
			{
			  
			  
				$cantidad_veces=round($busqueda[0]['cantidad_veces']);
				$dosisA=$busqueda[0]['dosis'];
			
			   
				$CantidadMedi=$cantidad_veces * $dosisA; 
				
			
			
				$fecha_formulacion=$busqueda[0]['fecha_formula'];
			
				list($year, $month, $day) =explode("-", $fecha_formulacion);
				$fecha_formulacion= mktime(0, 0, 0, $month, $day, $year); 
				 
				$tiempo_tratamiento=$busqueda[0]['tiempo_tratamiento'];
				 
				$unidad_tiempo_tratamiento=$busqueda[0]['unidad_tiempo_tratamiento'];
				 
			
				 
				 if($unidad_tiempo_tratamiento=='1')
				{
				 
				    $dias_s=$tiempo_tratamiento * 365;
				}
				 if($unidad_tiempo_tratamiento=='2')
				{
				 
				    $dias_s=$tiempo_tratamiento *  30;
				}
				 if($unidad_tiempo_tratamiento=='3')
				{
				 
				    $dias_s=$tiempo_tratamiento *  7;
				}
				 if($unidad_tiempo_tratamiento=='4')
				{
				 
				    $dias_s=$tiempo_tratamiento *  1;
				}
				
				
				
				
				
				$fecha_formulacion_d=$busqueda[0]['fecha_formula'];
			    
				
				
				list($a,$m,$d) = split("-",$fecha_formulacion_d);
		        $fecha_condias = date("Y-m-d",(mktime(0,0,0, $m,($d + $dias_s),$a)));
				$fecha_condias_d=explode("-", $fecha_condias);
				$fecha_condias_t= $fecha_condias_d[2]."/".$fecha_condias_d[1]."/".$fecha_condias_d[0];
						
				list($year, $month, $day) =explode("/", $fecha_condias_t);
				$fecha_finalizacion= mktime(0, 0, 0, $month, $day, $year); 

				$totalDays = ($fecha_finalizacion - $fecha_formulacion)/(60 * 60 * 24) ;  
		    
			    $cantidad_Tota=$busqueda[0]['cantidad'];
			  
			 
			  
			  	$TotalCantMe=$totalDays * $CantidadMedi;
				 
				
				$periodicidad_entrega=$busqueda[0]['periodicidad_entrega'];
				 $unidad_periodicidad_entrega=$busqueda[0]['unidad_periodicidad_entrega'];
				
				
					if($unidad_periodicidad_entrega=='1')
					{

					//$dias_e= $periodicidad_entrega * 365;
					 $dias_e=$periodicidad_entrega * 365;
					}
					if($unidad_periodicidad_entrega=='2')
					{

					 $dias_e=$periodicidad_entrega * 30;
					}
					if($unidad_periodicidad_entrega=='3')
					{

					 $dias_e=$periodicidad_entrega  * 7;
					}
					if($unidad_periodicidad_entrega=='4')
					{

					 $dias_e=$periodicidad_entrega * 1;
					}
				
				
				$cantidad_entrega_=$busqueda[0]['cantidad'];
				
				//$objResponse->alert("cantidad a entregar cantidad medicamento");
			
			
				$cantidad_entrega=($cantidad_entrega_/$tiempo_tratamiento);
			
					
			
	  	//$float=is_float($CantidadMedi);
		
		//	$objResponse->alert($float);
		
	//	$cantidad_entrega=($cantidad_entrega_/$dias_e);
		
		
		
			
				
			
			
		        //$TotalCantMe2=$CantidadMedi/$totalDays;
			
				//	list($dia,$mes,$año)=split("-",$fecha_formulacion);

					$Conversion=$obje->ConsultarFactorConversion($busqueda[0]['codigo_producto']);
					$unidad_dosif=$Conversion['0']['unidad_dosificacion'];	
					$factor_conversion=$Conversion['0']['factor_conversion'];	
					
					
					

				
					
					
					
					$CantidaEntregar=($cantidad_entrega/$factor_conversion);
					

					$valors=intval($CantidaEntregar);
					$real=$CantidaEntregar-$valor;
					if($real!=0)	
					$valors++;
		            
					
					
		            $farmacia=$empresa['empresa_id'];
					$centrou=$empresa['centro_utilidad'];
				
					
				*/
			/*	$html .= "                 <div id=\"erro\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:11px;\">\n";
				$html .= "                 </div>\n";
				$html .= "                                    <div id=\"error\" class='label_error'></div>";
		       
				//$objResponse->alert(print_r($busqueda),true);
					foreach($busqueda as $k => $valor)
					{
					
					$cantidad = $obje->Cantidad_ProductoTemporal($formula_id,$valor['cod_principio_activo']);
			   
			      //print_r($cantidad);
				  
				  
				  
						$html .= "                 <form id=\"forma".$formula_id."@".$valor['codigo_producto']."\" name=\"".$formula_id."@".$valor['codigo_producto']."\" action=\"\" method=\"post\">\n";
						$html .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
						$html .= "                    <tr class=\"modulo_table_list_title\">\n";
						$html .= "                     <td width=\"50%\">PRODUCTO: ".$valor['codigo_producto']." &nbsp; ".$valor['descripcion_prod'].". </td>
						<td>CANTIDAD SOLICITADA <input readonly=\"true\" type=\"input-text\" name=\"cantidad_solicitada\" id=\"cantidad_solicitada\" value=\"".$CantidaEntregar."\" class=\"input-text\"></td><td>CANTIDAD PENDIENTE <input readonly=\"true\" type=\"input-text\" name=\"cantidad_pendiente\" id=\"cantidad_pendiente\" value=\"".($CantidaEntregar-$cantidad['total'])."\" class=\"input-text\"></td>\n";
						$html .= "                        <input type=\"hidden\" name=\"principio_activo\" id=\"principio_activo\" value=\"".$valor['cod_principio_activo']."\">";
                         $html .= "                        <input type=\"hidden\" name=\"medicamento_formulado\" id=\"medicamento_formulado\" value=\"".$valor['codigo_producto']."\">";

						
						$html .= "                        <input type=\"hidden\" name=\"formula_id\" id=\"formula_id\" value=\"".$formula_id."\">";
						$html .= "                        <input type=\"hidden\" name=\"codigo_producto\" id=\"codigo_producto\" value=\"".$valor['codigo_producto']."\">";
						$html .= "                        <input type=\"hidden\" name=\"bodega\" id=\"bodega\" value=\"".$FormularioBuscador['bodega']."\">";
						
						//$html .= "                        <input type=\"hidden\" name=\"valor\" id=\"valor\" value=\"".$valor['valor']."\">";
						//$html .= "                        <input type=\"hidden\" name=\"porc_iva\" id=\"porc_iva\" value=\"".$valor['porc_iva']."\">";
						$html .= "                     </td>";
						$html .= "                    </tr>\n";

						$html .= "                   <tr class=\"modulo_list_claro\">\n";
						$html .= "                      <td colspan=\"3\" align=\"center\">";
						$Existencias=$obje->Consultar_ExistenciasBodegas($valor['cod_principio_activo'],$FormularioBuscador,$farmacia,$centrou,$bodega);      
						//$objResponse->alert(print_r($Existencias),true);
						
						// $objResponse->alert($Existencias[0]['existencia_actual']);
	// $objResponse->alert(print_r($Existencias,true));
						
						if(!empty($Existencias))
						{	
						//$objResponse->alert(print_r($Existencias),true);
			      
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
					  	
				       foreach($Existencias as $key=>$v)
				        {
						   $ProductoLote=$obje->Buscar_ProductoLote($formula_id,$valor['codigo_producto'],$v['lote']);
					    
					
						if(!empty($ProductoLote))
						{
							$habilitar = " checked=\"true\" disabled ";
						}
						else
							$habilitar = "  ";
							
					 /*Para Nomenclatura de Productos a Vencer y Proximos a Vencer*/
					 
				/**     $fech_vencmodulo = ModuloGetVar('app','AdminFarmacia','dias_vencimiento_product_bodega_farmacia_'.$farmacia);
					// $objResponse->alert($fech_vencmodulo);

				         $fecha =$v['fecha_vencimiento'];  //esta es la que viene de la DB
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
				          $color =" style=\"width:100%\" ";
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
					  
					  //  if($todo=='1')
						//{
						//	$cantidad_lote="";
							//break;
						//}
						/*$solitada=$CantidaEntregar;
						
														
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
						
						
						
					/***		$html .= "                                        <tr class=\"modulo_list_claro\">";
							
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
							
							$solitada=$CantidaEntregar - $v['existencia_actual'];
							
					
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
					
					
					
							$Bodegas_otras=$obje->Buscar_producto_EN_OTRA_FRM($farmacia,$centrou,$FormularioBuscador['bodega'],$valor['cod_principio_activo']);
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
			}else
			{
					$html .= "                                   <table width=\"70%\" align=\"center\" rules=\"all\" class=\"modulo_table_list\">";
					$html .= "                                       <tr class=\"label_error\">\n";
					$html .= "                                       <td align=\"center\" >";
					$html .= "                                           ESTE MEDICAMENTO YA  FUE FORMULADO HACE MENOS DE  $dias_dipensados DIA(S) <br>";
    				$html .= "                                          CANTIDAD DESPACHADA  ".round($datos_ex[0]['total'])." ";
					$html .= "          <img border=\"0\"  title=\"MEDICAMENTO DISPENSADO\" src=\"".GetThemePath()."/images/alarma.gif\">\n";

					
					//$html .= "          <img border=\"0\"  title=\"MEDICAMENTO DISPENSADO\" src=\"".GetThemePath()."/images/alarma.gif\">\n";

					$html .= "                                        </td>";
					
					
					$html .= "                                        </tr>\n";
			        $html .= "                                   </table >";
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
				
				 $objResponse->alert($fecha_formulacion_des);
				  $objResponse->alert("----");
				 
				$objResponse->alert($fecha_condias__d);
				 $objResponse->alert("----");
				 
				$objResponse->alert($hoy_ds);
				
				if($hoy_ds >$fecha_condias__d)
				{
				  $cont=$cont+1;
					
				}
			}	
			if($cont==$coun)
			{
				$INACTIVAR=$obje->UpdateEstad_Form_d($formula_id);
			
			}

		}*/
			 
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
			if($Formulario[$i]!="")
			{
				$cantidad = $obje->Cantidad_ProductoTemporal($Formulario['formula_id'],$Formulario['principio_activo']);
			
			
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
						$html .= "                        ".$detalle['codigo_producto'];
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
	
	 function PacienteReclama($observacion,$formula_id,$pendiente)
    {
      
			$objResponse = new xajaxResponse();
			
			$url=ModuloGetURL("app", "DispensacionESM", "controller", "GenerarEntregaMedicamentos",array("observacion"=>$observacion,"formula_id"=>$formula_id,"pendiente"=>$pendiente));
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
			$cantidad_entrega=$busqueda[0]['cantidad'];
		   	
			$empresa = SessionGetVar("DatosEmpresaAF");
			$farmacia=$empresa['empresa_id'];
			$centrou=$empresa['centro_utilidad'];
		
			$html .= "                 <div id=\"erro\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:11px;\">\n";
			$html .= "                 </div>\n";
			$html .= "                                    <div id=\"error\" class='label_error'></div>";
       
			foreach($busqueda as $k => $valor)
			{
	            $cantidad = $obje->Cantidad_ProductoTemporal($formula_id,$valor['cod_principio_activo']);
				$html .= "                 <form id=\"forma".$formula_id."@".$valor['codigo_producto']."\" name=\"".$formula_id."@".$valor['codigo_producto']."\" action=\"\" method=\"post\">\n";
				$html .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$html .= "                    <tr class=\"modulo_table_list_title\">\n";
				$html .= "                     <td width=\"50%\">PRODUCTO: ".$valor['codigo_producto']." &nbsp; ".$valor['descripcion_prod'].". </td>
				<td>CANTIDAD SOLICITADA <input readonly=\"true\" type=\"input-text\" name=\"cantidad_solicitada\" id=\"cantidad_solicitada\" value=\"".$cantidad_entrega."\" class=\"input-text\"></td><td>CANTIDAD PENDIENTE <input readonly=\"true\" type=\"input-text\" name=\"cantidad_pendiente\" id=\"cantidad_pendiente\" value=\"".($cantidad_entrega-$cantidad['total'])."\" class=\"input-text\"></td>\n";
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
				$ProductoLote=$obje->Buscar_ProductoLote($formula_id,$valor['codigo_producto'],$v['lote']);
			
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
			
					$Bodegas_otras=$obje->Buscar_producto_EN_OTRA_FRM($farmacia,$centrou,$FormularioBuscador['bodega'],$valor['principio_activo']);
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
			if($Formulario[$i]!="")
			{
				
					$cantidad = $obje->Cantidad_ProductoTemporal($Formulario['formula_id'],$Formulario['principio_activo']);
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
						$html .= "                        ".$detalle['codigo_producto'];
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
?>