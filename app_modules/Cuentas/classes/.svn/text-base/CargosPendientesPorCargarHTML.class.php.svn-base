<?php
  /******************************************************************************
  * $Id: CargosPendientesPorCargarHTML.class.php,v 1.7 2010/11/29 14:05:53 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
  * $Revision: 1.7 $ 
  * 
  ********************************************************************************/
  IncludeClass('CargosPendientesPorCargar','','app','Cuentas');
 
  class CargosPendientesPorCargarHTML
  {
    function CargosPendientesPorCargarHTML(){}
    /**********************************************************************************
    * Funcion donde se solicitan los datos para realizar la modificacion de un cargo
    * 
    * @return array 
    ***********************************************************************************/
		/**
		*
		*/
		function FormaPendientesCargar($arr,$Plan,$cuenta,$TipoId,$PacienteId,$Nivel,$Cama,$Fecha,$Ingreso)
		{
// 				unset($_SESSION['Liquidacion_QX']);
// 				unset($_SESSION['LIQUIDACION_QX']);
// 				unset($_SESSION['ARREGLO_LIQUIDACIONQX_CARGOS']);

				IncludeLib('funciones_admision');
				//IncludeLib('funciones_facturacion');
				IncludeLib('malla_validadora');
				$html = "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"90%\" align=\"center\"  class=\"modulo_table_list\">";
				$html .= "    <tr align=\"center\" class=\"modulo_table_title\">";
				$html .= "        <td class=\"label_error\"><img src=\"".GetThemePath()."/images/cargar.png\" border=\"0\">&nbsp;&nbsp;PENDIENTES POR CARGAR</td>";
				$html .= "    </tr>";
				$html .= "    <tr align=\"center\">";
				$html .= "        <td>";
				$_SESSION['DATOS_ARREGLO']['CARGOS_PENDIENTES_CARGAR_CUENTA']=$arr;

				for($i=0; $i<sizeof($arr); $i++)
				{
								$accion=ModuloGetURL('app','Cuentas','user','LlamaInsertarPendientesCargar',array('Cuenta'=>$cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$Plan,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'departamento'=>$arr[$i][departamento],'servicio'=>$arr[$i][servicio],'empresa'=>$arr[$i][empresa_id],'cu'=>$arr[$i][centro_utilidad],'ID'=>$arr[$i][procedimiento_pendiente_cargar_id]));
								$html .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
								$html .= "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"99%\" align=\"center\"  class=\"modulo_table_list\">";
								$html .= "    <tr align=\"center\" class=\"modulo_table_list_title\">";
								$html .= "        <td width=\"8%\">CUPS</td>";
								$html .= "        <td width=\"43%\">CARGO</td>";
								$html .= "        <td width=\"8%\">DEPARTAMENTO</td>";
								$html .= "        <td>USUARIO</td>";
								$html .= "        <td width=\"8%\">TIPO PROFESIONAL</td>";
								$html .= "        <td width=\"8%\">FECHA</td>";
								$html .= "        <td width=\"8%\">TIPO SALA</td>";
								$html .= "    </tr>";
								if( $i % 2) $estilo='modulo_list_claro';
								else $estilo='modulo_list_oscuro';
								$html .= "      <tr class=\"$estilo\" align=\"center\">";
								$html .= "       <td>".$arr[$i][cargo_cups]."</td>";
								$html .= "       <td>".$arr[$i][descups]."</td>";
								$html .= "       <td>".$arr[$i][desdpto]."</td>";
								$html .= "       <td>".$arr[$i][nombre]."</td>";
								$html .= "       <td>".$arr[$i][tipo]."</td>";
								$html .= "       <td>".FechaStamp($arr[$i][fecha])."</td>";
								$disabled='';
								if($arr[$i][sw_tipo_cargo]!='QX'){$disabled='disabled';}
								$html .= "            <td width=\"8%\" nowrap ><select $disabled name=\"TipoSala\" class=\"select\">";
								$html .="         <option value=\"-1\" selected>---seleccione---</option>";
								$TiposSala=$this->LlamaTiposDeSalas();
								for($x=0;$x<sizeof($TiposSala);$x++){
										$value=$TiposSala[$x]['tipo_sala_id'].'/'.$TiposSala[$x]['sw_quirofano'];
										$titulo=$TiposSala[$x]['descripcion'];
										if($value==$arr[$x][tipo_sala_id]){
												$html .="     <option value=\"$value\" selected>$titulo</option>";
										}else{
												$html .="     <option value=\"$value\">$titulo</option>";
										}
								}
								$html .= "       </select></td>";
								$html .= "    </tr>";
								$malla='';
								$malla=MallaValidadoraCargoCups($arr[$i][cargo_cups],$Plan,$arr[$i][servicio]);
								//echo "<br><br>malla==>"; print_r($malla);
								$html .= "      <tr align=\"center\">";
								$html .= "       <td colspan=\"6\">".$malla['mensaje']."</td>";
								$html .= "    </tr>";

								if(!empty($malla['validacion']))
								{
												$equi=ValdiarEquivalencias($Plan,$arr[$i][cargo_cups]);
												if(!empty($equi))
												{
																$html .= "      <tr align=\"center\">";
																$html .= "       <td colspan=\"7\">";
																$html .= "     <br><table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
																$html .= "      <tr class=\"modulo_table_list_title\">";
																$html .= "        <td>TARIFARIO</td>";
																$html .= "        <td>CARGO</td>";
																$html .= "        <td>DESCRIPCION</td>";
																$html .= "        <td>PRECIO</td>";
																$html .= "        <td></td>";
																$html .= "      </tr>";
																for($j=0; $j<sizeof($equi); $j++)
																{
																				if( $j % 2) $estilo='modulo_list_oscuro';
																				else $estilo='modulo_list_claro';
																				$html .= "     <tr class=\"$estilo\">";
																				$html .= "        <td align=\"center\">".$equi[$j][tarifario_id]."</td>";
																				$html .= "        <td align=\"center\">".$equi[$j][cargo]."</td>";
																				$html .= "        <td>".$equi[$j][descripcion]."</td>";
																				$html .= "        <td align=\"center\">".FormatoValor($equi[$j][precio])."</td>";
																				//hay varias
																				if(sizeof($equi) >= 1)
																				{
																								$x=PendientesCargarEquivalencias($arr[$i][procedimiento_pendiente_cargar_id],$equi[$j][cargo],$equi[$j][tarifario_id]);
																								if($x==1)
																								{  $html .= "        <td align=\"center\"><input type = checkbox name= cargo".$equi[$j][tarifario_id]."".$equi[$j][cargo]." value=\"".$equi[$j][tarifario_id]."||".$equi[$j][cargo]."||".$arr[$i][cargo_cups]."||".$arr[$i][autorizacion_int]."||".$arr[$i][autorizacion_ext]."||".$arr[$i][tipo_id_tercero]."||".$arr[$i][tercero_id]."\" checked></td>";  }
																								else
																								{  $html .= "        <td align=\"center\"><input type = checkbox name= cargo".$equi[$j][tarifario_id]."".$equi[$j][cargo]." value=\"".$equi[$j][tarifario_id]."||".$equi[$j][cargo]."||".$arr[$i][cargo_cups]."||".$arr[$i][autorizacion_int]."||".$arr[$i][autorizacion_ext]."||".$arr[$i][tipo_id_tercero]."||".$arr[$i][tercero_id]."\"></td>";  }
																				}
																				else
																				{       //solo hay una equivalencia
																								$html .= "        <td align=\"center\"><input type = checkbox name= cargo".$equi[$j][tarifario_id]."".$equi[$j][cargo]." value=\"".$equi[$j][tarifario_id]."||".$equi[$j][cargo]."||".$arr[$i][cargo_cups]."||".$arr[$i][autorizacion_int]."||".$arr[$i][autorizacion_ext]."||".$arr[$i][tipo_id_tercero]."||".$arr[$i][tercero_id]."\" checked></td>";
																				}
																				$html .= "      </tr>";
																}
																$html .= "     </table>";
																$html .= "       </td>";
																$html .= "    </tr>";
												}
								}
								$html .= "      <tr align=\"center\">";
								$html .= "       <td colspan=\"3\">";
								$html .= "         <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"LIQUIDAR\">";
								$html .= "       </td>";
								$html .= "</form>";
								$acc = ModuloGetUrl("app","Cuentas","user","LlamaFrmEliminarPendientesXCargar",array('EmpresaId'=>$arr[$i][empresa_id], 'CentroUtilidad'=> $arr[$i][centro_utilidad],'Ingreso'=>$ingreso,'cargo_cups'=>$arr[$i][cargo_cups],'Cuenta'=>$cuenta,'procedimiento_pendiente_cargar_id'=>$arr[$i][procedimiento_pendiente_cargar_id]));
								$html .= "       <td colspan=\"3\">";
								$html .= "         <a href=\"$acc\">Eliminar</a>";
								$html .= "       </td>";
								$html .= "    </tr>";
								$html .= "  </table><BR>";
								//$html .= "</form>";
				}

				$html .= "        </td>";
				$html .= "    </tr>";
				$html .= "  </table><br>";
				return $html;
		}
    
		function LlamaTiposDeSalas()
		{
			$dat = new CargosPendientesPorCargar();
			$salas = $dat->TiposDeSalas();
			return $salas;
		}

		/**
		* Muestra el detalle de la cuenta.
		* @access private
		* @return boolean
		* @param int numero de la cuenta
		* @param string tipo documento
		* @param int numero documento
		* @param string nivel
		* @param string plan_id
		* @param int numero de cama
		* @param date fecha de la cuenta
		* @param int ingreso
		* @param array arreglo con los datos de la cuenta
		* @param int numero de transaccion
		*/
		function FormaCuentaCargosLiquidadosQX($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Cama,$Fecha,$Ingreso,$vars,$Transaccion,$mensaje,$Dev,$Estado,$arre,$id)
		{
				//IncludeLib("tarifario");
				//IncludeLib("funciones_facturacion");
				unset($_SESSION['CUENTA']['DIVISION']);
				unset($_SESSION['DIVISION']['CUENTA']);
				unset($_SESSION['DIVISION']['DIVISION']['ABONOS']);
				IncludeLib("funciones_admision");
				global $VISTA;
				//factura detalleda
				$RUTA = $_ROOT ."cache/factura.pdf";
				$mostrar ="\n<script>\n";
				$mostrar.="var rem=\"\";\n";
				$mostrar.="  function abreVentana(){\n";
				$mostrar.="    var nombre=\"\"\n";
				$mostrar.="    var url2=\"\"\n";
				$mostrar.="    var str=\"\"\n";
				$mostrar.="    var ALTO=screen.height\n";
				$mostrar.="    var ANCHO=screen.width\n";
				$mostrar.="    var nombre=\"REPORTE\";\n";
				$mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
				$mostrar.="    var url2 ='$RUTA';\n";
				$mostrar.="    rem = window.open(url2, nombre, str)};\n";
				//$mostrar.="</script>\n";
				//factura conceptos
				$RUTA = $_ROOT ."cache/facturaconceptos.pdf";
				//$mostrar ="\n<script language='javascript'>\n";
				$mostrar.="var rem=\"\";\n";
				$mostrar.="  function abreVentana2(){\n";
				$mostrar.="    var nombre=\"\"\n";
				$mostrar.="    var url2=\"\"\n";
				$mostrar.="    var str=\"\"\n";
				$mostrar.="    var ALTO=screen.height\n";
				$mostrar.="    var ANCHO=screen.width\n";
				$mostrar.="    var nombre=\"REPORTE\";\n";
				$mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
				$mostrar.="    var url2 ='$RUTA';\n";
				$mostrar.="    rem = window.open(url2, nombre, str)};\n";
				$RUTA = $_ROOT ."cache/hojacargos".$Cuenta.".pdf";
				$mostrar.="var rem=\"\";\n";
				$mostrar.="  function abreVentanaHC(){\n";
				$mostrar.="    var nombre=\"\"\n";
				$mostrar.="    var url2=\"\"\n";
				$mostrar.="    var str=\"\"\n";
				$mostrar.="    var ALTO=screen.height\n";
				$mostrar.="    var ANCHO=screen.width\n";
				$mostrar.="    var nombre=\"REPORTE\";\n";
				$mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
				$mostrar.="    var url2 ='$RUTA';\n";
				$mostrar.="    rem = window.open(url2, nombre, str)};\n";
				$RUTA = $_ROOT ."cache/hojacargos2".$Cuenta.".pdf";
				$mostrar.="var rem=\"\";\n";
				$mostrar.="  function abreVentanaHT(){\n";
				$mostrar.="    var nombre=\"\"\n";
				$mostrar.="    var url2=\"\"\n";
				$mostrar.="    var str=\"\"\n";
				$mostrar.="    var ALTO=screen.height\n";
				$mostrar.="    var ANCHO=screen.width\n";
				$mostrar.="    var nombre=\"REPORTE\";\n";
				$mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
				$mostrar.="    var url2 ='$RUTA';\n";
				$mostrar.="    rem = window.open(url2, nombre, str)};\n";
				$RUTA1 = $_ROOT ."cache/hojacargos3".$Cuenta.".pdf";
				$mostrar.="  function abreVentanaHC3(){\n";
				$mostrar.="    var nombre=\"\"\n";
				$mostrar.="    var url2=\"\"\n";
				$mostrar.="    var str=\"\"\n";
				$mostrar.="    var ALTO=screen.height\n";
				$mostrar.="    var ANCHO=screen.width\n";
				$mostrar.="    var nombre=\"REPORTE\";\n";
				$mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
				$mostrar.="    var url2 ='$RUTA1';\n";
				$mostrar.="    rem = window.open(url2, nombre, str)};\n";
				$mostrar.= "function ConsultaAutorizacion(nombre, url, ancho, altura,Tarifario,Cargo,Cuenta,Autorizacion,Ayudas,tipo){";
				$mostrar.= " var str = 'width='+ancho+',height='+altura+',X=300,Y=800,resizable=no,status=no,scrollbars=yes';";
				$mostrar.= " var url2 = url+'?Tarifario='+Tarifario+'&Cargo='+Cargo+'&Cuenta='+Cuenta+'&Autorizacion='+Autorizacion+'&Ayudas='+Ayudas+'&Tipo='+tipo;";
				$mostrar.= " rem = window.open(url2, nombre, str);";
				$mostrar.= "  if (rem != null) {";
				$mostrar.= "     if (rem.opener == null) {";
				$mostrar.= "       rem.opener = self;";
				$mostrar.= "     }";
				$mostrar.= "  }";
				$mostrar.= "};";
				$mostrar.="</script>\n";
				$html ="$mostrar";
		
				$Nombres=$this->LlamaBuscarNombresPaciente($TipoId,$PacienteId);
				$Apellidos=$this->LlamaBuscarApellidosPaciente($TipoId,$PacienteId);
				$TipoCuenta=$_SESSION['CUENTAS']['TIPOCUENTA'];
				if($vars)
				{
						$html .= ThemeAbrirTabla('DETALLES DE LA CUENTA No. '.$Cuenta.' (Insumos y Medicamentos) '.$Nombres.' '.$Apellidos);
				}
				if($Dev)
				{
						$html .= ThemeAbrirTabla('DETALLES DE LA CUENTA No. '.$Cuenta.' (Devolución Insumos y Medicamentos) '.$Nombres.' '.$Apellidos);
				}
				if(!$Dev && !$vars)
				{
						$html .= ThemeAbrirTabla('DETALLES DE CARGOS CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos);
				}
				//$this->ConsultaAutorizacion();
				$argu=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso);
				//$this->Encabezado($PlanId,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$argu,$Cuenta);
				//$this->TotalesCuenta($Cuenta);
				$html .= "  </fieldset></td></tr></table><BR>";
				$accionT=ModuloGetURL('app','Cuentas','user','GetLlamaFormaCuantaPendientesCargar',array("Cuenta"=>$Cuenta,"TipoId"=>$TipoId,"PacienteId"=>$PacienteId,"Nivel"=>$Nivel,"PlanId"=>$PlanId,"Cama"=>$Cama,"Fecha"=>$Fecha,"Ingreso"=>$Ingreso,"vars"=>$vars,"Transaccion"=>$Transaccion,"mensaje"=>$mensaje,"Dev"=>$Dev,"Estado"=>$Estado,"arre"=>$arre));
				$html .= "           <form name=\"forma\" action=\"$accionT\" method=\"post\">";

				if($_SESSION['ARREGLO_LIQUIDACIONQX_CARGOS'])
				{
						$html .= "    <table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
						$html .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"4\">CARGOS DEL ACTO QUIRURGICO No. ".$_SESSION['Liquidacion_QX']['LIQUIDACION_ID']."</td></tr>";
						$html .= "    <tr class=\"modulo_list_oscuro\">";
						$html .= "    <td width=\"10%\" class=\"label\">ANESTESIOLOGO</td>";
						$nombreTercero=$this->LlamaNombreTercero($_SESSION['ARREGLO_LIQUIDACIONQX_CARGOS'][1][1]['liquidacion']['DA']['tipo_id_tercero'],$_SESSION['ARREGLO_LIQUIDACIONQX_CARGOS'][1][1]['liquidacion']['DA']['tercero_id']);
						$html .= "    <td width=\"40%\">".$nombreTercero['nombre_tercero']."</td>";
						$html .= "    <td width=\"10%\" class=\"label\">AYUDANTE</td>";
						$nombreTercero=$this->LlamaNombreTercero($_SESSION['ARREGLO_LIQUIDACIONQX_CARGOS'][1][1]['liquidacion']['DY']['tipo_id_tercero'],$_SESSION['ARREGLO_LIQUIDACIONQX_CARGOS'][1][1]['liquidacion']['DY']['tercero_id']);
						$html .= "    <td width=\"40%\">".$nombreTercero['nombre_tercero']."</td>";
						$html .= "    </tr>";
						foreach($_SESSION['ARREGLO_LIQUIDACIONQX_CARGOS'] as $indiceCirujano=>$Vector){
								$html .= "        <tr class=\"modulo_table_title\">";
								$html .= "         <td width=\"10%\">CIRUJANO ".$indiceCirujano."</td>";
								$nombreTercero=$this->LlamaNombreTercero($Vector[1]['tipo_id_cirujano'],$Vector[1]['cirujano_id']);
								$html .= "         <td colspan=\"3\">".$nombreTercero['nombre_tercero']."</td>";
								$html .= "       </tr>";
								foreach($Vector as $indiceProcedimiento=>$DatosQX){
										$html .= "    <tr class=\"modulo_list_oscuro\">";
										$html .= "      <td colspan=\"4\">";
										$html .= "       <table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
										$descripciones=$this->LlamaDescripcionCargosCups($DatosQX['cargo_cups']);
										$html .= "       <tr class=\"modulo_list_claro\">";
										$html .= "        <td  width=\"10%\" class=\"label\">CARGO CUPS</td>";
										$html .= "        <td colspan=\"4\">".$DatosQX['cargo_cups']." - ".$descripciones['descripcion']."</td>";
										$html .= "       </tr>";
										if($DatosQX['uvrs']){
												$html .= "       <tr class=\"modulo_list_claro\">";
												$html .= "        <td  width=\"10%\" class=\"label\">UVRS</td>";
												$html .= "        <td colspan=\"4\">".$DatosQX['uvrs']."</td>";
												$html .= "       </tr>";
										}
										$descripciones=$this->LlamaDescripcionCargosTarifario($DatosQX['tarifario_id']);
										$html .= "       <tr class=\"modulo_list_claro\">";
										$html .= "        <td  width=\"10%\" class=\"label\">EQUIVALENCIA</td>";
										$html .= "        <td colspan=\"4\">".$descripciones['tarifario']." ".$DatosQX['cargo']." - ".$DatosQX['descripcion']."</td>";
										$html .= "       </tr>";
										$html .= "          <tr class=\"modulo_table_list_title\">";
										$html .= "          <td width=\"10%\">".$indiceProcedimiento."</td>";
										$html .= "          <td width=\"20%\">CARGO</td>";
										$html .= "          <td width=\"10%\">%</td>";
										$html .= "          <td width=\"30%\">VALOR CUBIERTO</td>";
										$html .= "          <td>VALOR NO CUBIERTO</td>";
										$html .= "          </tr>";
										foreach($DatosQX['liquidacion'] as $derecho=>$DatosDerecho){

												$html .= "        <tr class=\"modulo_list_claro\">";
												$html .= "        <td class=\"label\">$derecho</td>";
												$descripciones=$this->LlamaDescripcionCargosTarifario($DatosDerecho['tarifario_id']);
												$html .= "        <td>".$descripciones['tarifario']." - ".$DatosDerecho['cargo']."</td>";
												if($valoresManual==1){
														$html .= "        <td align=\"center\"><input align=\"right\" type=\"text\" class=\"input-text\" size=\"8\" name=\"Porcentajes[$indiceCirujano][$indiceProcedimiento][liquidacion][$derecho]\" value=\"".$DatosDerecho['PORCENTAJE']."\"></td>";
												}else{
														$html .= "        <td align=\"right\">".$DatosDerecho['PORCENTAJE']."</td>";
												}
												if($valoresManual==1){
														$html .= "        <td align=\"center\"><input align=\"right\" type=\"text\" class=\"input-text\" size=\"8\" name=\"valoresCubiertos[$indiceCirujano][$indiceProcedimiento][liquidacion][$derecho]\" value=\"".FormatoValor($DatosDerecho['valor_cubierto'])."\"></td>";
												}else{
														$html .= "        <td align=\"right\">".FormatoValor($DatosDerecho['valor_cubierto'])."</td>";
												}
												if($valoresManual==1){
														$html .= "        <td align=\"center\"><input align=\"right\" type=\"text\" class=\"input-text\" size=\"8\" name=\"valoresNoCubiertos[$indiceCirujano][$indiceProcedimiento][liquidacion][$derecho]\" value=\"".FormatoValor($DatosDerecho['valor_no_cubierto'])."\"></td>";
												}else{
														$html .= "        <td align=\"right\">".FormatoValor($DatosDerecho['valor_no_cubierto'])."</td>";
												}
												$html .= "        </tr>";
										}
										$html .= "       </table>";
										$html .= "      </td>";
										$html .= "    </tr>";
								}
						}
						$html .= "    </table>";
						$html .= "    <table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
						$action=ModuloGetURL('app','Cuentas','user','GetCargarALaCuentaPaciente',array("NoLiquidacion"=>$_SESSION['Liquidacion_QX']['LIQUIDACION_ID'],"TipoDocumento"=>$TipoId,"Documento"=>$PacienteId,"externo"=>1,"Cuenta"=>$Cuenta,"TipoId"=>$TipoId,"PacienteId"=>$PacienteId,"Nivel"=>$Nivel,"PlanId"=>$PlanId,
						"Cama"=>$Cama,"Fecha"=>$Fecha,"Ingreso"=>$Ingreso,"vars"=>$vars,"Transaccion"=>$Transaccion,"mensaje"=>$mensaje,"Dev"=>$Dev,"Estado"=>$Estado,"arre"=>$arre,"id"=>$id));
						$html .= "    <tr><td align=\"left\"><a href=\"$action\"><img border = 0 src=\"".GetThemePath()."/images/cargar.png\"><b>&nbsp&nbsp;CARGAR A LA CUENTA</b></a></td></tr>";
						$html .= "    <tr><td align=\"center\"><input type=\"submit\" class=\"input-submit\" value=\"VOLVER\" name=\"Volver\"></td></tr>";
						$html .= "    </table>";
				}
				$html .= "           </form>";
				$html .= ThemeCerrarTabla();
				return $html;
		}

	/**********************************************************************************
	* Funcion donde se solicitan los datos para eliminar un cargo
	* 
	* @return array 
	***********************************************************************************/
	function FrmEliminarPendientesXCargar($Cuenta,$accionE,$accionC,$mensaje)
	{  
																	
		$html = "<script>\n";
		$html .= "	function EvaluarJustificacion(frm) \n";
		$html .= "	{\n";
		$html .= "		ele = document.getElementById('error');\n";
		$html .= "		if(frm.observacion.value == '')\n";
		$html .= "		{\n";
		$html .= "			ele.innerHTML = 'SE DEBE INDICAR UNA JUSTIFICACIÓN POR LA CUAL SE ESTA ELIMINANDO<br>EL CARGO PENDIENTE POR CARGAR.'\n";
		$html .= "			return;\n";
		$html .= "		}\n";
		$html .= "		frm.action = \"".$accionE."\";\n";
		$html .= "		frm.submit();\n";
		$html .= "	}\n";
		$html .= "</script>\n";
		$html .= ThemeAbrirTabla('ELIMINAR CARGO PENDIENTE POR CARGAR A LA CUENTA '.$Cuenta);           
		$html .= "<div id=\"error\" style=\"text-align:center\" class=\"label_error\"></div>\n";
		$html .= "<form name=\"formajustificar\" action=\"javascript:EvaluarJustificacion(document.formajustificar);\" method=\"post\">";
		$html .= "<table width=\"50%\" align=\"center\" border=0>";
		//$html .= "<p class=\"label_error\" align=\"center\">$mensaje</p>";
		$html .= "  <tr>";
		$html .= "    <td colspan=\"2\" class=\"label_mark\" align=\"center\">VA A ELIMINAR UN CARGO PENDIENTE A LA CUENTA No. $Cuenta<BR></td>";
		$html .= "  </tr>";
		$html .= "  <tr>";
		$html .= "    <td class=\"label\">JUSTIFICACIÓN: </td>";
		$html .= "    <td align=\"left\"><textarea cols=\"45\" rows=\"3\" class=\"textarea\" name=\"observacion\"></textarea></td>";
		$html .= "  </tr>";
		$html .= "</table>";      
		$html .= "<BR><table width=\"50%\" align=\"center\" border=0>";
		$html .= "<tr>";
		$html .= "<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Anular\" value=\"ELIMINAR\"></td>";
		$html .= "</form>";      
		$html .= "<form name=\"formabuscar\" action=\"$accionC\" method=\"post\">";
		$html .= "<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"VOLVER\"></td></tr>";
		$html .= "</form>";
		$html .= "</table>";
		$html .= ThemeCerrarTabla();                  
		return $html;   
	}
      
		function LlamaBuscarNombresPaciente($TipoId,$PacienteId)
		{
			$obj = new CargosPendientesPorCargar();
			$dat = $obj->BuscarNombresPaciente($TipoId,$PacienteId);
			return $dat;
		}

		function LlamaBuscarApellidosPaciente($TipoId,$PacienteId)
		{
			$obj = new CargosPendientesPorCargar();
			$dat = $obj->BuscarApellidosPaciente($TipoId,$PacienteId);
			return $dat;
		}
	
		function LlamaNombreTercero($tipo_id_tercero,$tercero_id)
		{
			$obj = new CargosPendientesPorCargar();
			$dat = $obj->NombreTercero($tipo_id_tercero,$tercero_id);
			return $dat;
		}
	
		function LlamaDescripcionCargosCups($cargo_cups)
		{
			$obj = new CargosPendientesPorCargar();
			$dat = $obj->DescripcionCargosCups($cargo_cups);
			return $dat;
		}
	
		function LlamaDescripcionCargosTarifario($tarifario_id)
		{
			$obj = new CargosPendientesPorCargar();
			$dat = $obj->DescripcionCargosTarifario($tarifario_id);
			return $dat;
		}


		function SetStyle($campo){
				if ($this->frmError[$campo] || $campo=="MensajeError"){
					if ($campo=="MensajeError"){
						return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
					}
					return ("label_error");
				}
				return ("label");
		} 
  }
?>