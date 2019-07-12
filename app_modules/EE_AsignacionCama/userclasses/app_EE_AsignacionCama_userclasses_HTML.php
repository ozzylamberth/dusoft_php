<?php

/**
 * $Id: app_EE_AsignacionCama_userclasses_HTML.php,v 1.18 2007/05/29 21:05:20 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author  Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
 * @package IPSOFT-SIIS
 */

class app_EE_AsignacionCama_userclasses_HTML extends app_EE_AsignacionCama_user
{
     /**
     * Constructor
     *
     * @return boolean
     */
     function app_EE_AsignacionCama_user_HTML()
     {
          $this->app_EE_AsignacionCama_user();
          $this->salida='';
          return true;
     }
     
     
     /**
     * Metodo Default
     *
     * @return boolean
     */
     function main()
     {
          $url= ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
          $titulo='FALTA METODO EN EL LLAMADO';
          $mensaje='Este modulo requiere un METODO especifico y debe ser llamado desde la Estacion De Enfermeria.';
          $this->frmMSG($url, $titulo, $mensaje);
          return true;
     }
     
     
     /**
     * Forma para mostrar mensaje
     *
     * @param string $url opcional url de retorno
     * @param string $titulo opcional titulo de la ventana
     * @param string $mensaje opcional mensaje a mostrar
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function frmMSG($url='', $titulo='', $mensaje='', $link='')
     {
          if(empty($titulo))  $titulo  = $this->titulo;
          if(empty($mensaje)) $mensaje = "EL USUARIO NO TIENE PERMISOS EN ESTE MODULO.";
          if(empty($link)) $link = "VOLVER";
          $this->salida  = themeAbrirTabla($titulo);
          $this->salida .= "<div class='titulo3' align='center'><br><br><b>$mensaje</b>";
          if($url)
          {
               $this->salida.="  <table width='100%' border='0' cellspacing=\"0\" cellpadding=\"8\">\n";
               $this->salida.="    <tr>\n";
               $this->salida.="        <td align='center' class=\"label_error\">\n";
               $this->salida.="            <a href='$url'>$link</a>\n";
               $this->salida.="        </td>\n";
               $this->salida.="    </tr>\n";
               $this->salida.="  </table>\n";
     
          }
          $this->salida .= "<br><br></div>";
          $this->salida .= themeCerrarTabla();
          return true;
     }
     
     
     /**
     * Forma para el ingreso de un paciente a la estacion.
     *
     * @return boolean True si se ejecuto correctamente
     * @access public
     */
     function FrmIngresoPaciente()
     {
          //Obtener los datos del paciente a Ingresar.
          if(empty($_REQUEST['datosPaciente']))
          	$datosPaciente = $this->GetDatosPacientePorIngresar();
          else
          	$datosPaciente = $_REQUEST['datosPaciente'];
          
          if($datosPaciente===false)
          {
               if(empty($this->error))
               {
                    $this->error = "EE_AsignacionCama - FrmIngresoPaciente - 01";
                    $this->mensajeDeError = "El metodo GetDatosPacientePorIngresar() retorno false.";
               }
               return false;
          }
          elseif(!is_array($datosPaciente))
          {
               $url= ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
               $titulo='VALIDACION DE PACIENTE';
               if(empty($datosPaciente))
               {
                    $mensaje = "No se pudo obtener los datos del paciente a INGRESAR.";
               }
               else
               {
                    $mensaje = $datosPaciente;
               }
               $this->frmMSG($url, $titulo, $mensaje);
               return true;
          }

          $datos_estacion = &$this->GetdatosEstacion();
     
          //VALIDACION DE PERMISOS
          if(!is_array($datos_estacion))
          {
               $url= ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
               $titulo = "VALIDACION DE PERMISOS";
               $this->frmMSG($url,$titulo);
               return true;
          }
          
          if($_REQUEST['conducta'])
          	$conducta = $_REQUEST['conducta'];
     
          //CABECERA - DATOS DE LA ESTACION DE ENFERMERIA
          $this->FrmDatosEstacion(&$datos_estacion);
          switch($_REQUEST['accionFrmIngresoPaciente'])
          {
               case 'Ingresar_Paciente':
               $swCambioCama = $_REQUEST['SwCambioCama'];
               $this->FrmListPacientesPorIngresar($datosPaciente,$datos_estacion,$swCambioCama,$conducta,'');        
               $this->FrmPieDePagina();
			return true;                         
               break;
               
               case 'Traslado':
               $swCambioCama = $_REQUEST['SwCambioCama'];
               $estado = $_REQUEST['estado'];
 			$this->FrmListPacientesPorIngresar($datosPaciente,$datos_estacion,$swCambioCama,$conducta,$estado);
               $this->FrmPieDePagina();
			return true;                         
               break;
     
               default:
                    $this->FrmListPacientesPorIngresar($datosPaciente,$datos_estacion,$swCambioCama,$conducta,'');
          }
          //DATOS DEL PIE DE PAGINA
          $this->FrmPieDePagina();
          return true;
     }
     
     /**
     * Forma para mostrar la cabecera de la Estacion de Enfermeria
     *
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function FrmDatosEstacion($datos)
     {
               $this->salida .= ThemeAbrirTabla("ESTACI&Oacute;N DE ENFERMERIA : ".$datos['estacion_descripcion']);
               $this->salida .= "<center>\n";
               $this->salida .= "    <table class='modulo_table_title' border='0' width='100%'>\n";
               $this->salida .= "        <tr class='modulo_table_title'>\n";
               $this->salida .= "            <td>Empresa</td>\n";
               $this->salida .= "            <td>Centro Utilidad</td>\n";
               $this->salida .= "            <td>Unidad Funcional</td>\n";
               $this->salida .= "            <td>Departamento</td>\n";
               $this->salida .= "        </tr>\n";
               $this->salida .= "        <tr class='modulo_list_oscuro'>\n";
               $this->salida .= "            <td>".$datos['empresa_descripcion']."</td>\n";
               $this->salida .= "            <td>".$datos['centro_utilidad_descripcion']."</td>\n";
               $this->salida .= "            <td>".$datos['unidad_funcional_descripcion']."</td>\n";
               $this->salida .= "            <td>".$datos['departamento_descripcion']."</td>\n";
               $this->salida .= "        </tr>\n";
               $this->salida .= "    </table>\n";
               $this->salida .= "</center>\n";
               return true;
     }
     
     
     /**
     * Forma para mostrar el pie de pagina de la Estacion de Enfermeria
     *
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function FrmPieDePagina()
     {
          $url= ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
     
          $this->salida .= "<center>\n";
          $this->salida.="  <table width='100%' border='0' cellspacing=\"0\" cellpadding=\"8\">\n";
          $this->salida.="    <tr>\n";
          $this->salida.="        <td align='center' class=\"label_error\">\n";
          $this->salida.="            <a href='$url'>VOLVER</a>\n";
          $this->salida.="        </td>\n";
          $this->salida.="    </tr>\n";
          $this->salida.="  </table>\n";
          $this->salida .= "</center>\n";
          $this->salida .= themeCerrarTabla();
          return true;
     }
     
     
     /**
     * Forma para mostrar la informacion del paciente, justo antes de realizar la
     * asignacion de la cama
     *
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function FrmListPacientesPorIngresar($datosPaciente,$datos_estacion,$swCambioCama,$conducta,$estado)
	{
		if(is_array($datosPaciente))
		{
          	$this->SetXajax(array("ActualizarTraslado"),"app_modules/EE_AsignacionCama/RemoteXajax/TrasladoMedicamentos.php");
               
			$this->salida .= "<script>\n";
               
               $this->salida .= "	function ActualizarTM(Cuenta)\n";
               $this->salida .= "	{\n";
               $this->salida .= "		xajax_ActualizarTraslado(Cuenta);\n";
               $this->salida .= "	}\n";
               
               $this->salida .= "	function load_page()\n";
               $this->salida .= "	{\n";
               $this->salida .= "  	location.reload();\n";
               $this->salida .= "	}\n";
			
               $this->salida .= "</script>\n";

               $this->salida .= "<br><br><table width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
			if(!empty($conducta))
               {	
               	$descripcion = $conducta[descripcion];
                    $this->salida .= "	<tr>\n";
                    $this->salida .= "	<td colspan=\"8\"><div class='label_error' align='center'>EL PACIENTE PRESENTA UNA ORDEN DE TRASLADO A : $conducta[descripcion].<br></td>\n";
                    $this->salida .= "	</tr>\n";
                    $this->salida .= "	<tr>\n";
                    $this->salida .= "	<td colspan=\"8\">&nbsp;</td>\n";
                    $this->salida .= "	</tr>\n";
               }

               /*************************************************************************************
               	FUNCIONES DE RESTRICCION PARA SALIDA DE PACIENTES
               *************************************************************************************/
               $ConteoMedicamentos_S = $this->GetInformacionMedicamentos_BodegaPaciente($datosPaciente['ingreso'], 'M', 0); //revisa si tiene Medicamentos por Cuadrar.
               $ConteoMedicamentos_R = $this->GetInformacionMedicamentos_BodegaPaciente($datosPaciente['ingreso'], 'M', 2); //revisa si tiene Medicamentos por Cuadrar.               
               $ConteoSuministros_S = $this->GetInformacionSuministros_BodegaPaciente($datosPaciente['ingreso'], 'I', 0); //revisa si tiene Suministros por Cuadrar.
               $ConteoSuministros_R = $this->GetInformacionSuministros_BodegaPaciente($datosPaciente['ingreso'], 'I', 2); //revisa si tiene Suministros por Cuadrar.
               $conteo_devolucion = $this->GetInformacionDevolucion_BodegaPaciente($datosPaciente['ingreso']);
               
               // Funcion para restriccion de traslado de medicamentos
               $swTrasladoMed = $this->RestriccionAsignacionCama($datosPaciente['numerodecuenta']);
               /*************************************************************************************
               	FUNCIONES DE RESTRICCION PARA SALIDA DE PACIENTES
               *************************************************************************************/

               $this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "		<td colspan=\"8\">PACIENTE PENDIENTE POR INGRESAR A LA ESTACION: </td>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "		<td>PACIENTE</td>\n";
			$this->salida .= "		<td>IDENTIFICACION</td>\n";
			$this->salida .= "		<td>CUENTA</td>\n";
			$this->salida .= "		<td>VIA INGRESO</td>\n";
               if($swCambioCama == '1')
               { $estacion_act = 'ESTACION ACTUAL';}else{$estacion_act = 'ESTACION ORIGEN';}
			$this->salida .= "		<td>".$estacion_act."</td>\n";
			$this->salida .= "		<td colspan=\"3\">ACCIONES</td>\n";
			$this->salida .= "	</tr>\n";

               $viaIngreso = $this->GetViaIngresoPaciente($datosPaciente[ingreso]);
               if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
               $this->salida .= "<tr class=\"$estilo\">\n";
               $this->salida .= "	<td nowrap>".$datosPaciente[nombre_completo]."</td>\n";
               $this->salida .= "	<td align=\"center\">".$datosPaciente[tipo_id_paciente]." ".$datosPaciente[paciente_id]."</td>\n";
               $this->salida .= "	<td align=\"center\">".$datosPaciente[numerodecuenta]."</td>\n";
               $this->salida .= "	<td align=\"center\">".$viaIngreso[via_ingreso_nombre]."&nbsp;</td>\n";
               
               if($swCambioCama == '1')
               { $nombre_estacion = $datos_estacion[estacion_descripcion];}else{ $nombre_estacion = $datosPaciente[descripcion_estacion_origen];}

               $this->salida .= "	<td align=\"center\">".$nombre_estacion."&nbsp;</td>\n";
                              
               if($swCambioCama == '1')
               { $Asignacion = "CAMBIAR CAMA";}else{ $Asignacion = "ASIGNAR CAMA";}

               $linkIngresar = ModuloGetURL('app','EE_AsignacionCama','user','CallListadoCamas',array("datosPaciente"=>$datosPaciente,"datos_estacion"=>$datos_estacion,'SwCambioCama'=>$swCambioCama,'conducta'=>$conducta));
               
               if($swCambioCama == '1')
               { $linkRemitir = ModuloGetURL('app','EE_AsignacionCama','user','CallListadoEstaciones',array("datosPaciente"=>$datosPaciente,"datos_estacion"=>$datos_estacion,'SwTrasladoEE'=>'1','conducta'=>$conducta,'estado'=>$estado,'SwCambioCama'=>$swCambioCama)); }
               else
               { $linkRemitir = ModuloGetURL('app','EE_AsignacionCama','user','CallListadoEstaciones',array("datosPaciente"=>$datosPaciente,"datos_estacion"=>$datos_estacion,'conducta'=>$conducta,'estado'=>$estado)); }
               
               $linkCancelar = ModuloGetURL('app','EE_AsignacionCama','user','CallFrmCancelarPendientePorHospitalizar',array("datos"=>$pacientes[$i],"datos_estacion"=>$datos_estacion));
               $retorno = ModuloGetURL('app','EE_AsignacionCama','user','CallListPacientesPorIngresar');
                
               if($estado == 'ConsultaURG')
               {
               	$this->salida .= "	<td align=\"center\">".$Asignacion."</td>\n";
               }
               else
               {
               	if($swTrasladoMed == '1')
                    {
						$this->salida .= "	<td align=\"center\">".$Asignacion."</td>\n"; 
                    }
					else
                    {
						if($swCambioCama == '1')
						{
							if($this->GetUserPermisos('03'))
							{
								$this->salida .= "	<td align=\"center\"><a href=\"$linkIngresar\">".$Asignacion."</a></td>\n";
							}
							else
							{
								$this->salida .= "	<td align=\"center\">".$Asignacion."</td>\n";
							}
							
						}
						else
						{
							if($this->GetUserPermisos('01') OR $this->GetUserPermisos('02'))
							{
								$this->salida .= "	<td align=\"center\"><a href=\"$linkIngresar\">".$Asignacion."</a></td>\n";
							}
							else
							{
								$this->salida .= "	<td align=\"center\">".$Asignacion."</td>\n";
							}	
						}
					 
                    }
               }
               
               if(($ConteoMedicamentos_S == '1' OR $ConteoSuministros_S == '1' OR $ConteoMedicamentos_R == '1' OR $ConteoSuministros_R == '1') OR ($conteo_devolucion == '1'))
               {
                    $programacion = $this->ValidarProgramacion_Cirugia($datosPaciente);
                    $solicitudesQX = $this->ValidarSolicitudes_Cirugia($datosPaciente);
          
                    if((!empty($programacion) AND $datosPaciente[paciente_cirugia] != 1) OR (!empty($solicitudesQX) AND $datosPaciente[paciente_cirugia] != 1))
                    {
                        if($estado != 'ConsultaURG')
                        {
                            if($this->GetUserPermisos('04'))
							{
								$this->salida .= "<td align=\"center\"><a href=\"$linkRemitir\">REMITIR A EE</a></td>\n";
							}
							else
							{
								$this->salida .= "   <td align=\"center\">REMITIR A EE</td>\n";
							}
							
                        }
                    }
                    else
                    {
                        $this->salida .= "   <td align=\"center\">REMITIR A EE</td>\n";                                     
                    }
               }
               else
               {
                    if($this->GetUserPermisos('04'))
					{
						$this->salida .= "<td align=\"center\"><a href=\"$linkRemitir\">REMITIR A EE</a></td>\n";
					}
					else
					{
						$this->salida .= "   <td align=\"center\">REMITIR A EE</td>\n";
					}  
               }

               $this->salida .= "	<td align=\"center\">RESERVAR CAMA</td>\n";

               $this->salida .= "</tr>\n";

               if(empty($programacion))
               {
                    if($ConteoMedicamentos_S == '1' OR $ConteoSuministros_S == '1' OR $ConteoMedicamentos_R == '1' OR $ConteoSuministros_R == '1')
                    {
                         if($ConteoSuministros_S == '1' AND $ConteoMedicamentos_S == '1')
                         {
                              $MEDICAMENTOS = "EL PACIENTE PRESENTA INSUMOS Y MEDICAMENTOS PENDIENTES POR DESPACHAR..";
                         }elseif($ConteoSuministros_R == '1' AND $ConteoMedicamentos_R == '1')
                         {
						$MEDICAMENTOS = "EL PACIENTE PRESENTA INSUMOS Y MEDICAMENTOS PENDIENTES POR CONFIRMAR..";
                         }elseif($ConteoSuministros_R == '1' AND $ConteoSuministros_S == '1')
                         {
                              $MEDICAMENTOS = "EL PACIENTE PRESENTA INSUMOS PENDIENTES POR DESPACHAR Y CONFIRMAR..";
					}elseif($ConteoMedicamentos_S == '1' AND $ConteoMedicamentos_R == '1' )
                         {
                              $MEDICAMENTOS = "EL PACIENTE PRESENTA MEDICAMENTOS PENDIENTES POR DESPACHAR Y CONFIRMAR..";
                         }elseif($ConteoSuministros_S == '1')
                         {
                              $MEDICAMENTOS = "EL PACIENTE PRESENTA INSUMOS PENDIENTES POR DESPACHAR..";
                         }elseif($ConteoSuministros_R == '1')
                         {
                              $MEDICAMENTOS = "EL PACIENTE PRESENTA INSUMOS PENDIENTES POR CONFIRMAR..";
					}elseif($ConteoMedicamentos_S == '1')
                         {
                              $MEDICAMENTOS = "EL PACIENTE PRESENTA MEDICAMENTOS PENDIENTES POR DESPACHAR..";
                         }elseif($ConteoMedicamentos_R == '1')
                         {
                              $MEDICAMENTOS = "EL PACIENTE PRESENTA MEDICAMENTOS PENDIENTES POR CONFIRMAR..";
                         }
                         
                         $this->salida .= "	<tr class=\"$estilo\">\n";
                         $this->salida .= "	<td colspan=\"8\">&nbsp;</td>\n";
                         $this->salida .= "	</tr>\n";
                         $this->salida .= "	<tr class=\"$estilo\">\n";
                         $this->salida .= "	<td colspan=\"8\" class=\"label_mark\" align=\"center\">$MEDICAMENTOS &nbsp;&nbsp;&nbsp;<img src='".GetThemePath()."/images/pparacarin.png' width=18 height=18 align='middle' border=0></td>\n";
                         $this->salida .= "	</tr>\n";
                         $this->salida .= "	<tr class=\"$estilo\">\n";
                         $this->salida .= "	<td colspan=\"8\" class=\"label_error\" align=\"center\">POR FAVOR, CANCELE, CONFIRME O SUMINISTRE EL PEDIDO !!!.</td>\n";
                         $this->salida .= "	</tr>\n";
                    }
                    
                    if($conteo_devolucion == '1')
                    {
                         $this->salida .= "	<tr class=\"$estilo\">\n";
                         $this->salida .= "	<td colspan=\"8\">&nbsp;</td>\n";
                         $this->salida .= "	</tr>\n";
                         $this->salida .= "	<tr class=\"$estilo\">\n";
                         $this->salida .= "	<td colspan=\"8\" class=\"label_mark\" align=\"center\">SE DEBE DESPACHAR LAS SOLICITUDES DE DEVOLUCION PENDIENTES EN BODEGA PARA PODER TRASLADAR AL PACIENTE &nbsp;&nbsp;&nbsp;<img src='".GetThemePath()."/images/pparacarin.png' width=\"19\" height=\"19\" align=\"middle\" border=\"0\"></td>\n";
                         $this->salida .= "	</tr>\n";
                    }
               }
			$this->salida .= "</table><br>\n";
               
               if($swTrasladoMed == '1')
               {
				$VectorMedicamentos = $this->ProductosPendientes_X_Traspaso($datosPaciente['ingreso']);
                    $this->salida .= "<table width=\"85%\" align=\"center\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
                    $this->salida .= "<tr>\n";
                    $this->salida .= "<td class=\"modulo_table_list_title\" colspan=\"5\">MEDICAMENTOS PENDIENTES POR CONFIRMACION DE TRASLADO</td>\n";
                    $this->salida .= "</tr>\n";
                    $this->salida .= "<tr class=\"modulo_table_list_title\">\n";
                    $this->salida .= "<td>CODIGO</td>\n";
                    $this->salida .= "<td>DESCRIPCION MEDICAMENTO</td>\n";
                    $this->salida .= "<td>PRINCIPIO ACTIVO</td>\n";
                    $this->salida .= "<td>FORMA FARMACOLOGICA</td>\n";
                    $this->salida .= "<td>CANTIDAD TRASLADADA</td>\n";                    
                    $this->salida .= "</tr>\n";
                    for($i=0; $i<sizeof($VectorMedicamentos); $i++)
                    {
                         $this->salida .= "<tr class=\"$estilo\">\n";
                         $this->salida .= "<td>".$VectorMedicamentos[$i]['codigo_producto']."</td>\n";
                         $this->salida .= "<td>".$VectorMedicamentos[$i]['descripcion']."</td>\n";
                         $this->salida .= "<td>".$VectorMedicamentos[$i]['principio']."</td>\n";
                         $this->salida .= "<td>".$VectorMedicamentos[$i]['forma_farma']."</td>\n";
                         $this->salida .= "<td>".$VectorMedicamentos[$i]['stock']."</td>\n";                    
                         $this->salida .= "</tr>\n";
                    }
                    $this->salida .= "<tr>\n";
                    $this->salida .= "<td class=\"$estilo\" colspan=\"5\" align=\"center\"><input type=\"submit\" class=\"input-submit\" name=\"aceptar_med\" value=\"ACEPTAR MEDICAMENTOS\" onclick=\"javascript:ActualizarTM('".$datosPaciente['numerodecuenta']."');\"></td>\n";
                    $this->salida .= "</tr>\n";
                    $this->salida .= "</table><br>\n";
               }
		}
		return true;
	}//fin FrmListPacientesPorIngresar
     
	
     /**
	*	FrmListadoCamas => muestra un listado de las camas disponibles de la EE
	*
	*	@param array => matriz con los datos del paciente
	*	@param boolean => defgine si va a realizar un cambio de cama o asignaci&oacute;n de cama
	*	@return boolean
	*/
	function FrmListadoCamas($datosPaciente,$swCambioCama,$datos_estacion,$conducta)
	{
          $mostrar ="\n<script language='javascript'>\n";
          $mostrar.="function mOvr(src,clrOver,i) {;\n";
          $mostrar.="src.style.background = clrOver;\n";
          $mostrar.= "document.getElementById(i).style.background = clrOver;\n";
          $mostrar.="}\n";

          $mostrar.="function mOut(src,clrIn,i) {\n";
          $mostrar.="src.style.background = clrIn;\n";
          $mostrar.= "document.getElementById(i).style.background = clrIn;\n";
          $mostrar.="}\n";
          $mostrar.="</script>\n";
          $this->salida .="$mostrar";

          $vc = $vp = array();
          $datosCamas = $this->GetCamasDisponibles($datos_estacion[estacion_id],$datosPaciente[plan_id]); 

          if(empty($datosCamas)){
               $mensaje = "NO SE ENCONTRARON CAMAS DISPONIBLES";
               $titulo = "MENSAJE";
               $url = ModuloGetURL('app','EE_AsignacionCama','user','FrmIngresoPaciente',array("datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,'accionFrmIngresoPaciente'=>'Ingresar_Paciente','conducta'=>$conducta));
               $link = "ASIGNACION CAMAS";

               if(!$swCambioCama)
               {
                    $linkVirtual = ModuloGetURL('app','EE_AsignacionCama','user','CallCrear_Asignar_Cama_Virtual',array("swCambioCama"=>$swCambioCama,"datosPaciente"=>$datosPaciente,"datos_estacion"=>$datos_estacion));
                    $impresion = "	<div class='normal_10' align='center'><img src=\"". GetThemePath() ."/images/flecha_der.gif\" border='0' width=10 heigth=10 >&nbsp;<a href=\"$linkVirtual\">ASIGNACION CAMA VIRTUAL</a></div>\n";
               }
               $this->frmMSG($url, $titulo, $mensaje, $link);
               return true;
          } 

          $impresion='';
          $this->salida .= ThemeAbrirTabla('LISTADO DE CAMAS DISPONIBLES EN '.$datos_estacion[estacion_descripcion])."<br>";
          $this->salida .= "<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_title\">\n";
          $this->salida .= "	<tr class=\"modulo_table_title\">\n";
          if($swCambioCama == '1')
          {
               $this->salida .= "		<td>HABITACIÓN</td>\n";
               $this->salida .= "		<td>CAMA</td>\n";
          }
          $this->salida .= "			<td>PACIENTE</td>\n";
          $this->salida .= "			<td>IDENTIFICACIÓN</td>\n";
          $this->salida .= "			<td>CUENTA</td>\n";
          $this->salida .= "			<td>INGRESO</td>\n";
          $this->salida .= "		</tr>\n";
          $this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
          if($swCambioCama == '1')
          {
               $this->salida .= "			<td>".$datosPaciente[pieza]."</td>\n";
               $this->salida .= "			<td>".$datosPaciente[cama]."</td>\n";
          }
          $this->salida .= "			<td>".$datosPaciente[nombre_completo]."</td>\n";
          $this->salida .= "			<td>".$datosPaciente[tipo_id_paciente]." ".$datosPaciente[paciente_id]."</td>\n";
          $this->salida .= "			<td>".$datosPaciente[numerodecuenta]."</td>\n";
          $this->salida .= "			<td>".$datosPaciente[ingreso]."</td>\n";
          $this->salida .= "		</tr>\n";
          $this->salida .= "	</table><br>\n";

          $this->salida .= "<table width=\"100%\" cellpadding=\"2\" border=\"0\" >\n";
          $this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
          $this->salida .= "		<td>HAB.</td>\n";
          $this->salida .= "		<td>CARGO</td>\n";
          $this->salida .= "		<td>TIPO DE CAMA</td>\n";
          $this->salida .= "		<td>CAMA</td>\n";
          $this->salida .= "		<td>VALOR ($)</td>\n";
          $this->salida .= "		<td>COB (%)</td>\n";
          $this->salida .= "		<td>VAL. PACIENTE</td>\n";
          $this->salida .= "		<td colspan=\"1\">ACCION</td>\n";
          $this->salida .= "	</tr>\n";

          $i=0;
          $backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');

          foreach($datosCamas[$datos_estacion[estacion_id]] as $pieza => $datospieza)
          {
               $i++;
               $num_camas=sizeof($datospieza);
		     if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
			$this->salida .= "	<tr  class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]','$i'); onmouseover=mOvr(this,'#7A99BB','$i');>\n";
               $this->salida .= "		<td id=$i align=\"center\" rowspan=\"".$num_camas."\">".$pieza."</td>\n";
               $j=0;

               foreach($datospieza as $k =>$dato_cama)//datos de las camas
               {
                    if ($j!=0)//para que haga la primera fila completa, las demas son del rowspan
                    { $this->salida.="<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]','$i'); onmouseover=mOvr(this,'#7A99BB','$i');>\n"; }
                    $j++;
     
                    //$vc[$j][7] este es el cargo de tarifarios_detalle.
                    $this->salida .= "	<td align=\"center\">".$dato_cama[cargo]."</td>\n";

                    //este tipo servicio de cama si es normal,virtual,ambulatoria
                    $this->salida .= "	<td align=\"left\">".$dato_cama[desc_cargo]."</td>\n";
                    $this->salida .= "	<td align=\"center\">".$dato_cama[cama]."</td>\n";
                    $this->salida .= "	<td align=\"center\">".FormatoValor(0)."</td>\n";
                    $this->salida .= "	<td align=\"center\">".FormatoValor(0)."</td>\n";
                    $this->salida .= "	<td align=\"center\">".FormatoValor(0)."</td>\n";

                    if($swCambioCama == '1')//[12]=>1 => es cambio de cama P.1.3, no asignacion de cama P.1.1
                    {	
                         //ojo esto es por si voy a hacer el subproceso 3 "cambio de cama" en lugar de "asignar cama" del subproceso 1
                         //vector que guarda la informacion de la cama, descripcion,cama donde vamos a guardar.
                         $data_cama[0]=$dato_cama[cama];
                         $data_cama[1]=$dato_cama[desc_cargo];
                         $data_cama[2]=$dato_cama[cargo];
                         $data_cama[3]=$dato_cama[tipo_cama_id];
                         $data_cama[4]=$dato_cama[tipo_clase_cama_id];
                         $linkAsignaCama = ModuloGetURL('app','EE_AsignacionCama','user','UpdateCamaPaciente',array("datosPaciente"=>$datosPaciente,"cama"=>$dato_cama[cama],"pieza"=>$pieza,"datosCamaPaciente"=>$data_cama,"datos_estacion"=>$datos_estacion));
                         $LINK_TITLE='CAMBIO DE CAMA';
                         unset($data_cama);
                    }
                    else //asignacion de cama P.1.1
                    {
                         //vector que guarda la informacion de la cama, descripcion,cama donde vamos a guardar.
                         $data_cama[0]=$dato_cama[cama];
                         $data_cama[1]=$dato_cama[desc_cargo];
                         $data_cama[2]=$dato_cama[cargo];
                         $data_cama[3]=$dato_cama[tipo_cama_id];
                         $data_cama[4]=$dato_cama[tipo_clase_cama_id];
                         $linkAsignaCama = ModuloGetURL('app','EE_AsignacionCama','user','CallIngresarPaciente',array("datosPaciente"=>$datosPaciente,"pieza"=>$pieza,"datosCamaPaciente"=>$data_cama,"datos_estacion"=>$datos_estacion,'conducta'=>$conducta));
                         $LINK_TITLE='ASIGNACION DE CAMA';
                         unset($data_cama);
                    }
                    $this->salida .= "	<td align=\"center\"><a href=\"$linkAsignaCama\">$LINK_TITLE</a></td>\n";
               }
          }
          $this->salida .= "</table><br>\n";

          if(empty($datosPaciente[cama]))
          {
               $linkVirtual = ModuloGetURL('app','EE_AsignacionCama','user','CallCrear_Asignar_Cama_Virtual',array("swCambioCama"=>$swCambioCama,"datosPaciente"=>$datosPaciente,"datos_estacion"=>$datos_estacion,'conducta'=>$conducta));
               $this->salida .= "	<div class='normal_10' align='center'><img src=\"". GetThemePath() ."/images/flecha_der.gif\" border='0' width=10 heigth=10 >&nbsp;<a href=\"$linkVirtual\">ASIGNACION CAMA VIRTUAL</a></div>\n";
          }

          $href = ModuloGetURL('app','EE_AsignacionCama','user','FrmIngresoPaciente',array('accionFrmIngresoPaciente'=>'Ingresar_Paciente','datosPaciente'=>$datosPaciente,'conducta'=>$conducta,'SwCambioCama'=>$swCambioCama));
          $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>VOLVER</a><br>";
          
          $href = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmLogueoEstacion');
          $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>SELECCIONAR ESTACION</a><br>";
		$this->salida .= themeCerrarTabla();
          return true;
     }//ListadoCamas
     
	
     /**
	*		IngresarPaciente => vista en la que se pide a la enfermera comentarios del ingreso
	*
	*		llamado desde el subproceso1->"Asignar cama" del proceso "ingreso de pacientes a la estaci&oacute;n de enfermer&iacute;a"
	*		vista 3 => 1.1.3.H => IngresarPaciente pide observaciones y llama a la funcion que inserta en la bd
	*
	*		@access Private
	*		@param array => matriz con los datos del paciente a ingresar
	*		@param array datos de la estacion
	*		@return boolean
	*/
	function IngresarPaciente($datosPaciente,$datos_estacion,$conducta)
     {	
          $this->salida .= ThemeAbrirTabla('INGRESAR PACIENTE A LA ESTACION - [ '.$datos_estacion[estacion_descripcion].' ]');
		$this->salida .= "	<table class='modulo_table_title' align=\"center\" width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\">\n";
		$this->salida .= "		<tr class=\"modulo_table_title\">\n";
		if(!empty($datosPaciente[pieza]))
		{
			$this->salida .= "		<td>HABITACIÓN</td>\n";
			$this->salida .= "		<td>CAMA</td>\n";
		}
		$this->salida .= "			<td>PACIENTE</td>\n";
		$this->salida .= "			<td>IDENTIFICACIÓN</td>\n";
		$this->salida .= "			<td>CUENTA</td>\n";
		$this->salida .= "			<td>INGRESO</td>\n";
		$this->salida .= "		</tr>\n";
		$this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
          if(!empty($datosPaciente[pieza]))
          {
               $this->salida .= "			<td>".$datosPaciente[pieza]."</td>\n";
               $this->salida .= "			<td>".$datosPaciente[cama]."</td>\n";
          }
          $this->salida .= "			<td>".$datosPaciente[nombre_completo]."</td>\n";
          $this->salida .= "			<td>".$datosPaciente[tipo_id_paciente]." ".$datosPaciente[paciente_id]."</td>\n";
          $this->salida .= "			<td>".$datosPaciente[numerodecuenta]."</td>\n";
          $this->salida .= "			<td>".$datosPaciente[ingreso]."</td>\n";
		$this->salida .= "		</tr>\n";
		$linkIngresar = ModuloGetURL('app','EE_AsignacionCama','user','InsertarPaciente',array("datosPaciente"=>$datosPaciente,"datos_estacion"=>$datos_estacion,'conducta'=>$conducta));
		$this->salida .= "			<form name=\"ingresars\" method=\"POST\" action=\"$linkIngresar\">\n";
		$this->salida .= "	</table>\n";


		$this->salida .= "			<table width=\"90%\" align=\"center\" cellpadding=\"2\" border=\"1\" >\n";
		$this->salida .= "				<tr class=\"modulo_table_title\">\n";
		$this->salida .= "					<td>HAB.</td>\n";
		$this->salida .= "					<td>CAMA</td>\n";
		$this->salida .= "					<td>DESCRIPCION</td>\n";
		$this->salida .= "					<td>OBSERVACIONES</td>\n";
		$this->salida .= "				</tr>\n";

		$this->salida .= "				<tr class=\"modulo_list_claro\">\n";
		$this->salida .= "					<td align=\"center\">".$datosPaciente[pieza]."</td>\n";
		$this->salida .= "					<td align=\"center\">".$datosPaciente[cama]."</td>\n";
		$this->salida .= "					<td align=\"center\">".urldecode($datosPaciente[desc_cargo])."</td>\n";
		$this->salida .= "					<td align=\"center\"><textarea name=\"observaciones\" cols='80' rows='6'  class=\"textarea\"></textarea></td>\n";
		$this->salida .= "				</tr>\n";
		$this->salida .= "			</table><br>\n";

		$this->salida.="<table align=\"center\" width='40%' border=\"0\">";
		$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"guardar\" type=\"submit\" value=\"ASIGNAR CAMA\"></form></td>";


		$link = ModuloGetURL('app','EE_AsignacionCama','user','DecisionCambioCargo',array("datosPaciente"=>$datosPaciente,"datos_estacion"=>$datos_estacion,'conducta'=>$conducta));
		$this->salida .= "           <form name=\"forma\" action=\"$link\" method=\"post\">";
		$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"CAMBIAR CARGO\"></form></td>";
		$this->salida .= "</tr>";
		$this->salida.="</table>";

		$href = ModuloGetURL('app','EE_AsignacionCama','user','CallListadoCamas',array("datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,'conducta'=>$conducta));
          $this->salida .= "			<div class='normal_10' align='center'><br><a href='".$href."'>Volver</a>";

          $href = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmLogueoEstacion');
          $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Seleccionar Estación</a><br>";
		$this->salida .= themeCerrarTabla();
		return true;
	}//IngresarPaciente

     
     //esta funcion me muestra las piezas y las camas virtuales q hay para asignar la cama virtual.
     function Crear_Asignar_Cama_Virtual($datosPaciente,$datos_estacion,$swCambioCama,$conducta)
     {
          $this->salida .= ThemeAbrirTabla('ASIGNACIÓN DE CAMA VIRTUAL DE LA ESTACION - [ '.$datos_estacion[estacion_descripcion].' ]');
          $this->salida .= "	<table align=\"center\" width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_title\">\n";
          $this->salida .= "		<tr class=\"modulo_table_title\">\n";
          if($swCambioCama == '1')
          {
               $this->salida .= "		<td>HABITACIÓN</td>\n";
               $this->salida .= "		<td>CAMA</td>\n";
          }
          $this->salida .= "			<td>PACIENTE</td>\n";
          $this->salida .= "			<td>IDENTIFICACIÓN</td>\n";
          $this->salida .= "			<td>CUENTA</td>\n";
          $this->salida .= "			<td>INGRESO</td>\n";
          $this->salida .= "		</tr>\n";
          $this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
          if($swCambioCama == '1')
          {
               $this->salida .= "			<td>".$datosPaciente[pieza]."</td>\n";
               $this->salida .= "			<td>".$datosPaciente[cama]."</td>\n";
          }
          $this->salida .= "			<td>".$datosPaciente[nombre_completo]."</td>\n";
          $this->salida .= "			<td>".$datosPaciente[tipo_id_paciente]." ".$datosPaciente[paciente_id]."</td>\n";
          $this->salida .= "			<td>".$datosPaciente[numerodecuenta]."</td>\n";
          $this->salida .= "			<td>".$datosPaciente[ingreso]."</td>\n";
          $this->salida .= "		</tr>\n";
          $this->salida .= "	</table><br>\n";
     
          $arr_habit=$this->Revisar_Habitaciones_Existentes($datos_estacion['estacion_id']);
          if(is_array($arr_habit))
          {
               $this->salida .= "<SCRIPT language='javascript'>\n";
               $this->salida .= "function Pintartd(clrIn,i,x){\n";
               $this->salida .= "  if(x==true){\n";
               $sw=0;
               for($i=0;$i<sizeof($arr_habit);$i++)
               {
                    if( $i % 2){ $color='#DDDDDD';}
                    else {$color='#CCCCCC';}
                    $this->salida .= "document.getElementById($i).style.background = '$color';\n";
                    if(!empty($arr_habit[$i]['sw_virtual']))
                    {$sw=$sw +1;}
                    if($sw==0)
                    {
                         $s=$i+1;
                         $this->salida .= "document.getElementById($s).style.background = clrIn;\n";
                    }
               }
               $this->salida .= "document.getElementById(i).style.background = '#7A99BB';\n";
               $this->salida .= "    }\n";
               $this->salida .= "  else{\n";
               $this->salida .= "document.getElementById(i).style.background = clrIn;\n";

               $this->salida .= "  }\n";
               $this->salida .= "}\n";

               $this->salida .= "function pasar(obj,x){\n";
               $this->salida .= "  if(x==1){\n";
               $this->salida .= "  document.formin.text_tipo.value='PIEZA VIRTUAL';\n";
               $this->salida .= "}else{\n";
               $this->salida .= "  document.formin.text_tipo.value='PIEZA AMBULATORIA';\n";
               $this->salida .= "}\n";
               $this->salida .= "}\n";
               $this->salida .= "</SCRIPT>\n";

               $this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
               $this->salida .= $this->SetStyle("MensajeError");
               $this->salida.="</able>";
               
               $this->salida.="<table  align=\"center\" border=\"2\"  width=\"90%\">";
               $this->salida .= "<tr class=\"modulo_list_claro\"><td>";

               $href = ModuloGetURL('app','EE_AsignacionCama','user','GenerarCamaVirtual',array("swCambioCama"=>$swCambioCama,"datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,'conducta'=>$conducta));
               $this->salida .= "<form name='formin' action='".$href."' method='POST'><br>\n";
               $this->salida.="<table  align=\"center\" border=\"1\"  width=\"40%\">";

			$this->salida.="<tr class=\"modulo_table_title\">";
               $this->salida.="  <td align=\"left\"  colspan=\"2\">TIPO DE SERVICIO DE CAMA</td>";
               $this->salida.="</tr>";
               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="  <td>DESCRIPCION</td>";
               $this->salida.="  <td></td>";
               $this->salida.="</tr>";

               $this->salida.="<tr  class=modulo_list_claro align=\"center\">";
               $this->salida.="  <td><label class='label_mark'><b>CAMA VIRTUAL</b></label></td>";
               $this->salida.="  <td><input type=\"radio\" name=\"tipoc\" onclick=pasar(this,1) value='2'></td>";
               $this->salida.="</tr>";

               $this->salida.="<tr  class=modulo_list_oscuro align=\"center\">";
               $this->salida.="  <td><label class='label_mark'><b>CAMA AMBULATORIA</b></label></td>";
               $this->salida.="  <td><input type=\"radio\" name=\"tipoc\" onclick=pasar(this,2) value='3'></td>";
               $this->salida.="</tr>";
               $this->salida.="</table><br>";

               $this->salida.="<table  align=\"center\" border=\"1\"  width=\"70%\">";
          	$this->salida.="<tr class=\"modulo_table_title\">";
               $this->salida.="  <td align=\"left\" colspan=\"5\">SELECCIONE LA PIEZA DONDE VA A CREAR LA CAMA</td>";
               $this->salida.="</tr>";
               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="  <td>PIEZA</td>";
               $this->salida.="  <td>DESCRIPCION</td>";
               $this->salida.="  <td>UBICACION</td>";
               $this->salida.="  <td><sub>Camas<br>Especiales</sub></td>";
               $this->salida.="  <td></td>";
               $this->salida.="</tr>";
               $sw=0;

	          for($i=0;$i<sizeof($arr_habit);$i++)
               {
                    if( $i % 2){ $estilo='modulo_list_claro';$color='#DDDDDD';}
                    else {$estilo='modulo_list_oscuro';$color='#CCCCCC';}

          		if(!empty($arr_habit[$i]['sw_virtual']))
                    {$sw=$sw +1; $clase="<label class='label_mark'><b>";$_clase="</b></label>";}else{$clase='';$_clase='';}
                                   
                    $this->salida.="<tr id=$i class=\"$estilo\" align=\"center\">";
                    $this->salida.="  <td>$clase".$arr_habit[$i]['pieza']."$_clase</td>";
                    $this->salida.="  <td>$clase".$arr_habit[$i]['descripcion']."$_clase</td>";
                    $this->salida.="  <td>$clase".$arr_habit[$i]['ubicacion']."$_clase</td>";
                    $contador=$this->Conteo_Camas_Especiales($arr_habit[$i]['pieza']);
                    $this->salida.="  <td>(".FormatoValor($contador).")</td>";
                    $this->salida.="  <td><input type=\"radio\" name=\"opcion\" onclick=Pintartd('$color','$i',this.checked) value=".$arr_habit[$i]['pieza']."></td>";
	               $this->salida.="</tr>";
               }
               if($sw==0)
               {
                    $this->salida.="<tr id=$i class=\"$estilo\" align=\"center\">";
                    $this->salida.="  <td><input type=\"text\" class='input-text' name=\"text_tipo\" READONLY></td>";
                    $this->salida.="  <td><textarea name=desc class='input-text' cols=30 rows=3>PIEZA ESPECIAL</textarea></td>";
                    $this->salida.="  <td><textarea name=ubic class='input-text' cols=30 rows=3>ESTACION &nbsp; ".$datos_estacion[estacion_descripcion]."</textarea></td>";
                    $this->salida.="  <td>&nbsp;</td>";
                    $this->salida.="  <td><input type=\"radio\" name=\"opcion\" onclick=Pintartd('$color','$i',this.checked) value='[-@@@-]'></td>";
                    $this->salida.="</tr>";
               }

               $this->salida.="</table><br>";
               
               $this->salida.="<table   align=\"center\" border=\"1\" width=\"70%\">";
               $this->salida.="<tr class=\"modulo_table_title\"><td>SELECCIONE EL TIPO DE CAMA</td><td align='center'>COLOQUE LA UBICACIÓN DE LA CAMA</td></tr>";
               $this->salida.="<tr>";

               $VectorTiposCamas=$this->Traer_Tipos_Cama_excepciones($datos_estacion['estacion_id'],$datosPaciente[plan_id]);

               if(!is_array($VectorTiposCamas))
               {
                    $VectorTiposCamas=$this->Traer_Tipos_Cama($datos_estacion['estacion_id']);
               }
               if(sizeof($VectorTiposCamas))
               {
                    //TIPO CAMAS
                    $this->salida .= "<td align='center' class=\"modulo_list_claro\"><select name=\"tipo_cama\" class=\"select\">\n";

                    for($j=0; $j<sizeof($VectorTiposCamas); $j++)
                    {
                         if($VectorTiposCamas[$j][tipo_cama_id]==$tipo_cama_act) //verificar si hay un request..
                         {
                              $this->salida .= "	<option value=\"".$VectorTiposCamas[$j][tipo_cama_id]."*".$VectorTiposCamas[$j][cargo]."*".$VectorTiposCamas[$j][tipo_clase_cama_id]."\"selected>".$VectorTiposCamas[$j][descripcion]."</option>\n";
                         }
                         else
                         {
                              $this->salida .= "	<option value=\"".$VectorTiposCamas[$j][tipo_cama_id]."*".$VectorTiposCamas[$j][cargo]."*".$VectorTiposCamas[$j][tipo_clase_cama_id]."\">".$VectorTiposCamas[$j][descripcion]."</option>\n";
                         }
                    }
                    $this->salida .= "	</select>\n";
               }
               else
          	{ $this->salida .= "<td class=\"modulo_list_claro\"><label class='label_error'>POR FAVOR LLENAR LAS TABLAS tipos_camas_excepcion_plan,tipos_camas,estaciones_tipos_camas_permitidos </label></td>";}

               $this->salida.="</td><td align=\"center\" class=\"modulo_list_claro\"><input type=\"text\" class='input-text' maxlength=\"20\"  name=\"ubic_cama\"></td>";
               $this->salida.="</tr>";
               $this->salida.="</table><br>";

               $this->salida.="<table   align=\"center\" border=\"0\" width=\"20%\">";
               $this->salida.="<tr class=\"modulo_list_claro\"><td class=\"modulo_list_claro\"><input class=\"input-submit\" type=submit name=mandar value='ASIGNAR CAMA VIRTUAL'></td></tr>";
               $this->salida.="</form>";
               $this->salida.="</table>";
               $this->salida.="</td></tr></table>";
          }
     
		$href = ModuloGetURL('app','EE_AsignacionCama','user','CallListadoCamas',array("datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,'conducta'=>$conducta));
		$this->salida .= "			<div class='normal_10' align='center'><br><a href='".$href."'>Volver</a>";

          $href = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmLogueoEstacion');
          $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Seleccionar Estación</a><br>";
          $this->salida .= themeCerrarTabla();
          return true;
     }
     

     //funcion en la cual revisa si tiene autorizacion para cambiar el cargo
	//$SW_USER_LEGAL esta variable si esta activa significa q puede cambiar el cargo
	//sin problemas.

	function DecisionCambioCargo($datosPaciente,$datos_estacion,$SW_USER_LEGAL,$conducta)
	{
          if(empty($datos_estacion))
          {
               $datosPaciente=$_REQUEST['datosPaciente'];
               $datos_estacion=$_REQUEST['datos_estacion'];
          }

          $this->salida .= ThemeAbrirTabla('CAMBIO DE CARGO DE CAMA');

          $this->salida .= "	<table class='modulo_table_title' align=\"center\" width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\">\n";
          $this->salida .= "		<tr class=\"modulo_table_title\">\n";
          if(!empty($datosPaciente[cama]))
          {
               $this->salida .= "		<td>HABITACION</td>\n";
               $this->salida .= "		<td>CAMA</td>\n";
          }
          $this->salida .= "			<td>PACIENTE</td>\n";
          $this->salida .= "			<td>IDENTIFICACIÓN</td>\n";
          $this->salida .= "			<td>CUENTA</td>\n";
          $this->salida .= "			<td>INGRESO</td>\n";
          $this->salida .= "		</tr>\n";
          $this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
          if(!empty($datosPaciente[cama]))
          {
               $this->salida .= "			<td>".$datosPaciente[pieza]."</td>\n";
               $this->salida .= "			<td>".$datosPaciente[cama]."</td>\n";
          }
          $this->salida .= "			<td>".$datosPaciente[nombre_completo]."</td>\n";
          $this->salida .= "			<td>".$datosPaciente[tipo_id_paciente]." ".$datosPaciente[paciente_id]."</td>\n";
          $this->salida .= "			<td>".$datosPaciente[numerodecuenta]."</td>\n";
          $this->salida .= "			<td>".$datosPaciente[ingreso]."</td>\n";
          $this->salida .= "		</tr>\n";
          $this->salida .= "	</table><br>\n";
          
          $this->salida.="<table  width=\"100%\">";
          $this->salida .="".$this->SetStyle("MensajeError")."";
          $this->salida.="</table>";

          //debemos darle prioridad a esta tabla, logicamente si esta el plan de este paciente
          $dats=$this->Traer_Tipos_Cama_excepciones($datos_estacion[estacion_id],$datosPaciente[plan_id]);
          if(!is_array($dats))
          {
               $dats=$this->Traer_Tipos_Cama($datos_estacion[estacion_id]);
          }
          
          if(is_array($dats))
          {
               $href = ModuloGetURL('app','EE_AsignacionCama','user','CallCambioCargoIngresarPaciente',array("datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,"spya"=>$SW_USER_LEGAL,'conducta'=>$conducta));
               $this->salida .= "<form name=\"forma\" action=\"$href\" method=\"post\">";
               $this->salida.="<table  align=\"center\" border=\"1\" class=\"hc_table_list\" width=\"85%\">";
               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="  <td>Cargo</td>";
               $this->salida.="  <td>Descripcion</td>";
               $this->salida.="  <td>Precio Lista</td>";
               $this->salida.="  <td></td>";
               $this->salida.="</tr>";

               for($i=0;$i<sizeof($dats);$i++)
               {
                    if($datosPaciente[cargo] != $dats[$i][cargo])
                    {//este es el cargo.
                         if( $i % 2){ $estilo='modulo_list_claro';}
                         else {$estilo='modulo_list_oscuro';}
                         $this->salida.="<tr class=\"$estilo\" align=\"center\">";
                         $this->salida.="  <td>".$dats[$i][cargo]."</td>";
                         $desc=$this->GetDescripcionCama($dats[$i][cargo],'');
                         $this->salida.="  <td>$desc</td>";
                         $desc=urlencode($desc);
                         $this->salida.="  <td><input type='text' name='precio' value=".FormatoValor($dats[$i][precio_lista])." READONLY></td>";
                         $this->salida.="  <td><input type=radio name=opcion value=".$desc."$".$dats[$i][cargo]."$".$dats[$i][tipo_cama_id]."$".$dats[$i][tipo_clase_cama_id]."></td>";
                         $this->salida.="</tr>";
                    }
               }
          }
          else
          {
               $this->salida.="<table  align=\"center\" border=\"0\"  width=\"85%\">";
               $this->salida .= "    <td  align=\"center\"><label class='label_error'>NO HAY CONFIGURACION DE TIPOS DE CAMAS PARA ESTA ESTACIÓN</label>";
               $this->salida .= "    </td>";
          }
          $this->salida.="</table>";
          $this->salida.="<table align=\"center\" width='40%' border=\"0\">";
          $this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"guardar\" type=\"submit\" value=\"Cambiar\"></form></td>";

		$href = ModuloGetURL('app','EE_AsignacionCama','user','CallRetornoIngresarPaciente',array("datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,'conducta'=>$conducta));
          $this->salida .= "<form name=\"forma\" action=\"$href\" method=\"post\">";
          $this->salida .= "<td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></form></td>";
          $this->salida .= "</tr>";
          $this->salida.="</table><br>";
		//}
          $this->salida .= themeCerrarTabla();
          return true;
	}
    
     
     /**
	*		ListadoEstaciones => vista de un listado de las estaciones del departamento
	*
	*		llamado desde vista 1, link Remitir EE => subproceso 2->"Cambio estacion de enfermeria antes del ingreso al dpto" del proceso "ingreso de pacientes a la estaci&oacute;n de enfermer&iacute;a"
	*		vista 4 => 1.2.1.H => ListadoEstaciones lista las estaciones del departamento
	*
	*		@access Private
	*		@param array => matriz con los datos del paciente a ingresar
	*		@param boolean => Sw para identificar si es traslado de EE a un paciente ya ingresado &oacute; cambio de EE antes del ingreso
	*		@param array datos de la estacion
	*		@return boolean
	*/
	function ListadoEstaciones($datosPaciente,$SwTrasladoEE,$datos_estacion,$conducta,$estado,$swCambioCama)
	{
		$estaciones = $this->GetEstacionesDpto($datos_estacion);
		if(!$estaciones){
			return false;
		}
		if($estaciones === "ShowMensaje")
		{
			$mensaje = "NO SE ENCONTRARON ESTACIONES EN EL DEPARTAMENTO";
			$titulo = "TRASLADO DE ESTACION";
			$url = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
			$link = "Panel Enfermeria";
			$this->frmMSG($url, $titulo, $mensaje, $link);
			return true;
		}

		$this->salida .= ThemeAbrirTabla('TRASLADO DE ESTACION')."<br>";
          
          $RUTA = "app_modules/EE_AsignacionCama/descriptivo.php?sign=";
          $mostrar ="\n<script language='javascript'>\n";
          $mostrar.="var rem=\"\";\n";
          $mostrar.=" function Descriptivo(valor_ee,desc_ee){\n";
          $mostrar.="    var nombre=\"\"\n";
          $mostrar.="    var url2=\"\"\n";
          $mostrar.="    var str=\"\"\n";
          $mostrar.="    var nombre=\"REPORTE\";\n";
          $mostrar.="    var str =\"width=350,height=250,resizable=no,location=no, status=no,scrollbars=yes\";\n";
          $mostrar.="    var url2 ='$RUTA'+valor_ee+','+desc_ee;\n";
          $mostrar.="    rem = window.open(url2, nombre, str)};\n";
          
          $mostrar.="</script>\n";
          $this->salida .="$mostrar";

		$this->salida .= "	<table align=\"center\" width=\"70%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list\">\n";
		$this->salida .= "		<tr class=\"modulo_table_title\">\n";
		$this->salida .= "			<td>PACIENTE</td>\n";
		$this->salida .= "			<td>IDENTIFICACION</td>\n";
		$this->salida .= "			<td>CUENTA</td>\n";
		$this->salida .= "		</tr>\n";
		$this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
		$this->salida .= "			<td><b>".$datosPaciente[nombre_completo]."</b></td>\n";
		$this->salida .= "			<td><b>".$datosPaciente[tipo_id_paciente]." ".$datosPaciente[paciente_id]."</b></td>\n";
		$this->salida .= "			<td><b>".$datosPaciente[numerodecuenta]."</b></td>\n";
		$this->salida .= "		</tr>\n";
		$this->salida .= "	</table><br>\n";

		$this->salida .= "<table width=\"70%\" cellpadding=\"2\" border=\"0\" class=\"modulo_table_list\" align=\"center\">\n";
		$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
		$this->salida .= "		<td width=\"50%\">ESTACION</td>\n";
		$this->salida .= "		<td width=\"30%\">ACCIONES</td>\n";
          $this->salida .= "		<td width=\"20%\">PROTOCOLO</td>\n";
		$this->salida .= "	</tr>\n";

          for ($i=0; $i<sizeof($estaciones); $i++)
		{
          	if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
               $this->salida .= "	<tr class=\"$estilo\">\n";
               $this->salida .= "		<td><img src=\"".GetThemePath()."/images/flecha_der.gif\"  width='10' height='10'>&nbsp;<label class='label_mark'>".$estaciones[$i][1]."</label></td>\n";
               
               if($SwTrasladoEE == '1'){//ojo esto es por si voy a hacer el subproceso 4 "traslado EE dentro del dpto" en lugar del subproceso 2 "cambio de estacion antes del ingreso"
                    $linkRemitir = ModuloGetURL('app','EE_AsignacionCama','user','UpdateTrasladoEstacion',array("datosPaciente"=>$datosPaciente,"estacionDestino"=>$estaciones[$i][0],"datos_estacion"=>$datos_estacion,'conducta'=>$conducta,'estado'=>$estado,'Prox_Dpto'=>$estaciones[$i][2]));
               }
               else{//cambio de estacion antes del ingreso
                    $linkRemitir = ModuloGetURL('app','EE_AsignacionCama','user','UpdateCambioEstacion',array("datosPaciente"=>$datosPaciente,"estacionDestino"=>$estaciones[$i][0],"datos_estacion"=>$datos_estacion,'conducta'=>$conducta,'estado'=>$estado,'Prox_Dpto'=>$estaciones[$i][2]));
               }
               $this->salida .= "		<td align=\"center\"><img src=\"".GetThemePath()."/images/uf.png\"  width='14' height='14'>&nbsp;<a href=\"$linkRemitir\">REMITIR</a></td>\n";
               $estaciones_id = $estaciones[$i][0];
               $descripcion_ee = $estaciones[$i][1];
               $this->salida .= "		<td align=\"center\"><a href=\"javascript:Descriptivo($estaciones_id,'$descripcion_ee')\">Descripción EE.</a></td>\n";
               $this->salida .= "	</tr>\n";
		}
		$this->salida .= "</table><br>\n";
          
          $programacion = $this->ValidarProgramacion_Cirugia($datosPaciente);
          
          if(!empty($programacion) AND $datosPaciente[paciente_cirugia] != 1)
          {
               $estacionesCirugia = $this->GetEstacionesCirugia($datos_estacion);
               $this->salida .= "<table width=\"70%\" cellpadding=\"2\" border=\"0\" class=\"modulo_table_list\" align=\"center\">\n";
               $this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
               $this->salida .= "		<td width=\"70%\">ESTACIONES DE CIRUGIA</td>\n";
               $this->salida .= "		<td width=\"30%\">ACCIONES</td>\n";
               $this->salida .= "	</tr>\n";

               for ($j=0; $j<sizeof($estacionesCirugia); $j++)
               {
                    if($j % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
                    $this->salida .= "	<tr class=\"$estilo\">\n";
                    $this->salida .= "		<td><img src=\"".GetThemePath()."/images/alarma.png\"  width='10' height='10'>&nbsp;<label class='label'>".$estacionesCirugia[$j][descripcion]."</label></td>\n";
                    
                    $linkCirugia = ModuloGetURL('app','EE_AsignacionCama','user','IngresarPaciente_EstacionCirugia',array("datosPaciente"=>$datosPaciente,"DptoCirugia"=>$estacionesCirugia[$j][departamento],"datos_estacion"=>$datos_estacion,"programacion"=>$programacion));
                    $this->salida .= "		<td align=\"center\"><img src=\"".GetThemePath()."/images/traslado.png\"  width='14' height='14'>&nbsp;<a href=\"$linkCirugia\">INGRESAR</a></td>\n";
                    $this->salida .= "	</tr>\n";
               }
               $this->salida .= "</table><br>\n";
          }
          //adicionado para comprobar si tiene oerdenes de cirugia pendientes
          $solicitudesQX = $this->ValidarSolicitudes_Cirugia($datosPaciente);
          
          if(!empty($solicitudesQX) AND $datosPaciente[paciente_cirugia] != 1)
          {
               $estacionesCirugia = $this->GetEstacionesCirugia($datos_estacion);
               $this->salida .= "<table width=\"70%\" cellpadding=\"2\" border=\"0\" class=\"modulo_table_list\" align=\"center\">\n";
               $this->salida .= " <tr class=\"modulo_table_list_title\">\n";
               $this->salida .= "   <td width=\"70%\">ESTACIONES DE CIRUGIA</td>\n";
               $this->salida .= "   <td width=\"30%\">ACCIONES</td>\n";
               $this->salida .= " </tr>\n";

               for ($j=0; $j<sizeof($estacionesCirugia); $j++)
               {
                    if($j % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
                    $this->salida .= "  <tr class=\"$estilo\">\n";
                    $this->salida .= "    <td><img src=\"".GetThemePath()."/images/alarma.png\"  width='10' height='10'>&nbsp;<label class='label'>".$estacionesCirugia[$j][descripcion]."</label></td>\n";
                    
                    $linkCirugia = ModuloGetURL('app','EE_AsignacionCama','user','IngresarPaciente_EstacionCirugia',array("datosPaciente"=>$datosPaciente,"DptoCirugia"=>$estacionesCirugia[$j][departamento],"datos_estacion"=>$datos_estacion));
                    $this->salida .= "    <td align=\"center\"><img src=\"".GetThemePath()."/images/traslado.png\"  width='14' height='14'>&nbsp;<a href=\"$linkCirugia\">INGRESAR</a></td>\n";
                    $this->salida .= "  </tr>\n";
               }
               $this->salida .= "</table><br>\n";
          }
          
          $href = ModuloGetURL('app','EE_AsignacionCama','user','FrmIngresoPaciente',array('accionFrmIngresoPaciente'=>'Traslado','datosPaciente'=>$datosPaciente,'conducta'=>$conducta,'estado'=>$estado,'SwCambioCama'=>$swCambioCama));
          $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>VOLVER</a><br>";
          
          $href = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
		$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>PANEL ENFERMERIA</a><br>";
		$this->salida .= themeCerrarTabla();
		return true;
	}//ListadoEstaciones

     
     function SetStyle($campo)
     {
          if ($this->frmError[$campo] || $campo=="MensajeError"){
               if ($campo=="MensajeError"){
                    $arreglo=array('numero'=>$numero,'prefijo'=>$prefijo);
                    return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
               }
     	     return ("label_error");
     	}
     	return ("label");
	}

}//fin de la clase

?>