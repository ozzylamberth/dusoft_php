<?php

/**
 * $Id: app_EE_Insumos_y_Medicamentos_userclasses_HTML.php,v 1.3 2010/03/15 18:59:15 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author  Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
 * @package IPSOFT-SIIS
 */

class app_EE_Insumos_y_Medicamentos_userclasses_HTML extends app_EE_Insumos_y_Medicamentos_user
{
     /**
     * Constructor
     *
     * @return boolean
     */
     function app_EE_Insumos_y_Medicamentos_user_HTML()
     {
          $this->app_EE_Insumos_y_Medicamentos_user();
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
     * Forma para mostrar la cabecera de la Estacion de Enfermeria
     *
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function FrmDatosEstacion($datos)
     {
          $this->salida .= ThemeAbrirTabla("ESTACI&Oacute;N DE ENFERMERIA : ".$datos['estacion_descripcion']);
          $this->salida .= "<center>\n";
          $this->salida .= "    <table class='modulo_table_title' border='0' width='80%'>\n";
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
     function FrmPieDePagina($datosPaciente,$datos_estacion)
     {
          if ($_SESSION['Interna'] == true)
          	$url= ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmMedicamentos',array('datosPaciente'=>$datosPaciente,'datos_estacion'=>$datos_estacion));
          else
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
     
     
	/**
	*		FrmShowBodega: Bodegas de Solicitud de la EE.
	*
	*		@Tizziano Perea O.
	*		@access Private
	*		@param array datos de la estacion
	*		@return boolean
	*/
	function FrmShowBodega($datos_estacion,$SWITCHE)
	{
          //Validar si el usuario esta logueado y si tiene permisos.
          if(!$this->GetUserPermisos('53'))
          {
               $url= ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
               $titulo='VALIDACION DE PERMISOS';
               $mensaje='El usuario no tiene permiso para : Recibir Despachos de Insumos y Medicamentos (Pacientes) [53]';
               $this->frmMSG($url, $titulo, $mensaje);
               return true;
          }
          
          if(empty($datos_estacion))
          {
               $datos_estacion = $_REQUEST['datos_estacion'];
               $SWITCHE = $_REQUEST['switche'];
               //esta variable de session la usamos para trabajar esta forma indiferente de
               //q sea medicamentos o insumos,para llamar frmshowbodega
               if(empty($_SESSION['ESTACION_MEDICAMENTOS']['ACTION']))
               {$_SESSION['ESTACION_MEDICAMENTOS']['ACTION']=$_REQUEST['accion'];}
          }
		
          if(empty($datos_estacion))
          $datos_estacion = &$this->GetdatosEstacion();
     
          //VALIDACION DE PERMISOS
          if(!is_array($datos_estacion))
          {
               $url= ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
               $titulo = "VALIDACION DE PERMISOS";
               $this->frmMSG($url,$titulo);
               return true;
          }
          
          $datos=$this->GetEstacionBodega($datos_estacion,1);

          if(is_array($datos))
          {
               $this->salida .= ThemeAbrirTabla("SELECCIONAR BODEGAS DE LA ESTACION &nbsp;".$datos_estacion[descripcion_estacion]."");
               
               if($SWITCHE=='despacho') 
               {
               	$datosPaciente[] = $_REQUEST['datosPaciente'];
                    $f = ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','CallInsumosMed_X_Despachar',array("datos_estacion"=>$datos_estacion,'switche'=>'despacho',"datosPaciente"=>$datosPaciente));
               }
               elseif($SWITCHE=='recibir')
               {
                    $f = ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','CallMedicamentosIns_X_Recibir',array("datos_estacion"=>$datos_estacion,'switche'=>'recibir'));
               }

               $this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";

               $this->salida .= "	<br><table align=\"center\" width=\"50%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class='modulo_list_table'\n>";
               $this->salida .= "		<tr class='modulo_table_list_title'>\n";
               $this->salida .= "			<td width=\"2%\" >BODEGAS</td>\n";
               $this->salida .= "		</tr>\n";
               $this->salida .= "		<tr class='modulo_list_claro'>\n";
               $this->salida .= "			<td width=\"2%\"  align=\"center\" >\n";

               $this->salida.="<select name='bodega' class='select'>";
               
               if(empty($empresa))
               {
                    for($i=0;$i<sizeof($datos);$i++)
                    {
                         $this->salida.="<option value=".$datos[$i][bodega].">".$datos[$i][descripcion]."</option>";
                    }
	               $this->salida.="</select>";
               }
               $this->salida .= "			</td>\n";
               $this->salida .= "		</tr>\n";
               $this->salida.=" <tr class='modulo_list_oscuro'>";
               $this->salida.=" <td align=\"center\">";
               $this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"BUSCAR\"></form>";
               $this->salida.=" </td>";
               $this->salida .= "		</tr>\n";
               $this->salida.="</table><br>";
          }
          //DATOS DEL PIE DE PAGINA
          if ($_SESSION['Interna'] == true)
          	$this->FrmPieDePagina($_REQUEST['datosPaciente'],$datos_estacion);
          else
          	$this->FrmPieDePagina();
          return true;
	}
     
     
     /*
     *
     *
     *		@Author Tizziano Perea O.
     *		@access Private
     *		@return bool
     *		Proposito: Unificar la solicitud de medicamentos e insumos.
     */

     function InsumosMed_X_Despachar($datos_estacion,$bodega,$SWITCHE,$datosPaciente)
     {
          if(empty($datos_estacion))
          {
               $datos_estacion=$_REQUEST['datos_estacion'];
               $bodega=$_REQUEST['bodega'];
               $SWITCHE=$_REQUEST['switche'];
          }
          
          if(empty($datosPaciente[0]) OR empty($datosPaciente))
          {
               $a = 0;
               foreach($_SESSION['EE_PanelEnfermeria']['listadoPacientes'] as $k => $Pacientes)
               {
                    $datosPaciente[$a] = $Pacientes;
                    $a++;
                    $i = $a;						
               }

               unset($Pacientes);
               foreach($_SESSION['EE_PanelEnfermeria']['listadoPacientes_Urgencias'] as $k => $Pacientes)
               {
                    $datosPaciente[$i] = $Pacientes;
                    $i++;
               }
          }

          $nom_bodega=$this->TraerNombreBodega($datos_estacion,$bodega);
		
          // Cargamos la Variable de SESSION para manejo Interno.
          unset($_SESSION['EE_I_y_M']['Pacientes']);
          $_SESSION['EE_I_y_M']['Pacientes'] = $datosPaciente;
          $_SESSION['EE_I_y_M']['Estacion'] = $datos_estacion;
          // Cargamos la Variable de SESSION para manejo Interno.
          
          $this->salida .= ThemeAbrirTabla("LISTADO DE DESPACHO DE INSUMOS Y MEDICAMENTOS DESDE LA BODEGA  &nbsp;".strtoupper($nom_bodega)."");
          $this->salida .= "<div style=\"display:none\" id=\"nota\">\n";
          $this->salida .= "  <center>\n";
		  $this->salida .= "    <b class=\"label_error\">LOS MEDICAMENTOS Y/O INSUMOS EN ROJO NO POSEEN EXISTENCIAS EN LA BODEGA</b>\n";
          $this->salida .= "  </center>\n";
          $this->salida .= "</div>\n";
          $flag = false;
          foreach($datosPaciente as $A => $B)
          {
              for($tpo=0; $tpo<2; $tpo++)
              {
                if($p++ % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";

                if($tpo==0)
                {
                  //consulta de medicamentos solicitados
                  if($B['ingreso'])
                  {
	                  $medic=$this->GetInsumosPendDesp($B['ingreso'],$datos_estacion,$bodega);
                  }
                }
                elseif($tpo==1)
                {
                  //consulta de medicamentos solicitados
                  if($B['ingreso'])
                  {
                   	$medic=$this->GetMedicamentosPendDesp($B['ingreso'],$datos_estacion,$bodega);
                  }
                }

                if(!empty($medic))
                {
                         $contador=4;
                         if($tpo==0)
                         {
                              $_SESSION['ESTACION']['VECTOR_DESP_INS'][$B[ingreso]]=$medic;
                              $f = ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','ConfirmarDespSolicitudIns',array("ingreso"=>$B[ingreso],"plan"=>$B[plan_id],"cuenta"=>$B[numerodecuenta],"bodega"=>$bodega,"switche"=>$SWITCHE));
                              $this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";
                         }elseif($tpo==1)
                         {
                              $_SESSION['ESTACION']['VECTOR_DESP'][$B[ingreso]]=$medic;
                              $f = ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','ConfirmarDespSolicitudMed',array("ingreso"=>$B[ingreso],"plan"=>$B[plan_id],"cuenta"=>$B[numerodecuenta],"bodega"=>$bodega,"switche"=>$SWITCHE));
                              $this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";
                         }

                         $this->salida .= "<table align=\"center\" width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
                         if($tpo==0)
                         {
                              $this->salida .= "	<tr class=hc_table_submodulo_list_title>\n";
                              $this->salida .= "	<td colspan=\"4\">DESPACHO DE INSUMOS</td>\n";
                              $this->salida .= "	</tr>\n";
                         }elseif($tpo==1)
                         {
                              $this->salida .= "	<tr class=hc_table_submodulo_list_title>\n";
                              $this->salida .= "	<td colspan=\"4\">DESPACHO DE MEDICAMENTOS</td>\n";
                              $this->salida .= "	</tr>\n";
                         }
               
                         $this->salida .= "	<tr class='modulo_table_title'>\n";
                         $this->salida .= "		<td>HABITACION</td>\n";
                         $this->salida .= "		<td>CAMA</td>\n";
                         $this->salida .= "		<td>TIEMPO HOSPITALIZACION</td>\n";
                         $this->salida .= "		<td>PACIENTE</td>\n";
                         $this->salida .= "	</tr>\n";

						 
						 
						 
                         $this->salida .= "	<tr class=hc_table_submodulo_list_title>\n";
                         if(empty($B[pieza]))
                         {
                              $this->salida .= "	<td align=\"center\">No Ingresado</td>\n";
                              $this->salida .= "	<td align=\"center\">No Ingresado</td>\n";
                    
                         }
                         else
                         {
                              $this->salida .= "	<td align=\"center\">".$B[pieza]."</td>\n";
                              $this->salida .= "	<td align=\"center\">".$B[cama]."</td>\n";
                         }
                         $diasHospitalizacion = $this->GetDiasHospitalizacion($B[fecha_ingreso]);
                         $this->salida .= "	<td align=\"center\">".$diasHospitalizacion."</td>\n";
                         $this->salida .= "	<td>".$B[nombre_completo]."</td>\n";
                         $this->salida .= "	</tr>\n";

                         $this->salida .= "	<tr class=hc_table_submodulo_list_title><td colspan='4'>\n";
                         $this->salida .= "	<br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class='modulo_list_table'\n>";

                         $this->salida .= "		<tr  class='modulo_table_list_title'>\n";
                         $this->salida .= "			<td align=\"center\" width=\"5%\"  >SOLICITUD</td>\n";
                         $this->salida .= "			<td align=\"center\" width=\"10%\" >CODIGO</td>\n";
                         $this->salida .= "			<td align=\"center\" width=\"20%\" >PRODUCTO</td>\n";
                         $this->salida .= "			<td align=\"center\" width=\"5%\"  >CANT</td>\n";
                         $this->salida .= "			<td align=\"center\" width=\"10%\" >CODIGO DESP</td>\n";
                         $this->salida .= "			<td align=\"center\" width=\"20%\" >PRODUCTO DESP</td>\n";
						 $this->salida .= "			<td align=\"center\" width=\"10%\"  >FEC<BR>VENCIMIENTO</td>\n";
						 $this->salida .= "			<td align=\"center\" width=\"10%\"  >LOTE</td>\n";
                         $this->salida .= "			<td align=\"center\" width=\"5%\"  >CANT DESP</td>\n";
                         $this->salida .= "			<td align=\"center\" width=\"5%\"  >&nbsp;</td>\n";
                         $this->salida .= "		</tr>\n";

                        for($i=0;$i<sizeof($medic);$i++)
                        {
                          if($i % 2)  $estilo = "class=modulo_list_claro";  else  $estilo = "class=modulo_list_oscuro";
                          
                          
                          
                          if($medic[$i][solicitud_id]!= $medic[$i-1][solicitud_id])
                          {
                            $this->salida .= "		<tr $estilo>\n";
                            $this->salida .= "		  <td colspan = 1  align=\"center\" class=modulo_list_claro width=\"5%\">";
                            $this->salida .= "		  ".$medic[$i][solicitud_id]."";
                            //opcion agragada para cancelar los despachos sin confirmar se coloca en un estado 6==>Despachado y luego cancelado
                            $accion=ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','CancelarDespSolicitud',array("solicitud_id"=>$medic[$i][solicitud_id],"ingreso"=>$B[ingreso],"plan"=>$B[plan_id],"cuenta"=>$B[numerodecuenta],"bodega"=>$bodega,"switche"=>$SWITCHE));
                            $this->salida .= "<a href=\"$accion\"><img title=\"Cancelar la Solicitud\" src=\"".GetThemePath()."/images/fallo.png\" border=\"0\" width=\"15\" height=\"15\"></a>";
                            //fin opcion
                            $this->salida .= "</td>\n";
                            $solicitud=$medic[$i][solicitud_id];
                            $this->salida .= "		<td colspan = 10 width=\"95%\">";
                            $this->salida .= "		  <table align=\"center\" width=\"100%\" border=\"0\" class='modulo_list_table'\n>";
                          }

                          if($tpo==1)
                            $despacho=$this->GetDatosDespacho($medic[$i][doc],$medic[$i][consecutivo_d],$medic[$i][solicitud_id]);
                          elseif($tpo==0)
                            $despacho=$this->GetDatosDespachoIns($medic[$i][doc],$medic[$i][consecutivo_d],$medic[$i][solicitud_id]);
                          
							
						  
						  if(empty($despacho)) $despacho[0]['codigo'] = "";
                          $cls = sizeof($despacho);
                          $flag = false;
                          foreach($despacho as $k1 => $dtl)
                          {
                            $clase = "";
							if ($dtl['existencia_actual'] < floor($dtl[cantidad])) 
							{
								$flag = true;
								$clase = "class =\"label_error\" ";
							}
							
							$this->salida .= "<tr $estilo>\n";
                            if(!$flag)
                            {
                              $this->salida .= "<td ".$clase." rowspan=\"".$cls."\" width=\"10%\">".$medic[$i][codigo_producto]."</td>\n";
                              $this->salida .= "<td ".$clase." rowspan=\"".$cls."\" width=\"20%\">".$medic[$i][producto]."</td>\n";
                              $this->salida .= "<td ".$clase." rowspan=\"".$cls."\" align=\"center\" width=\"5%\">".floor($medic[$i][cant_solicitada])."</td>\n";
                            }
                            if(empty($dtl['codigo']) AND empty($dtl['descripcion']))
                              $this->salida .= "<td ".$clase." colspan='4' width=\"20%\"><label class='label_mark'>No Despachado</label></td>\n";
                            else
                            {
                              $this->salida .= "<td ".$clase." width=\"10%\">".$dtl[codigo_producto]."</td>\n";
                              $this->salida .= "<td ".$clase." width=\"20%\">".$dtl[descripcion]."</td>\n";
							  $this->salida .= "<td ".$clase." width=\"10%\">".$dtl[fecha_vencimiento]."</td>\n";
							  $this->salida .= "<td ".$clase." width=\"10%\">".$dtl[lote]."</td>\n";
                            }
                            
                            
							$cant_desp=floor($dtl[cantidad]);
                            if($cant_desp <=0){$cant_desp='';}
                            $this->salida .= "<td ".$clase." width=\"5%\" align=\"center\">$cant_desp</td>\n";

                            if(!$flag)
                            {
                              $this->salida .= "  <td  ".$clase." rowspan=\"".$cls."\" width=\"5%\" align=\"center\">\n";
                              if($medic[$i][sw]==5)//este estado es que se despacho incompleta.
                              {
                                if($dtl[cantidad]>0)
                                  $this->salida .= "    <img src=\"". GetThemePath() ."/images/checkS.gif\" width='17' height='17' border='0'>\n";
                                else
                                  $this->salida .= "    <label class='label_mark'>--</label>\n";
                              }
                              if($medic[$i][sw]==1)
                              {
                                if($dtl>0)
                                  $this->salida .= "  <input type=checkbox name=opcion[] value=".$medic[$i][solicitud_id].",".$medic[$i][consecutivo_d].">\n";
                                else
                                  $this->salida .= "  <label class='label_mark'>--</label>\n";
                              }
                              $this->salida .= "  </td>\n";
                              $flag = true;
                            }
                            $this->salida.=" </tr>";
                          }
                          if($medic[$i][solicitud_id] != $medic[$i+1][solicitud_id])
                          {
                            $this->salida .= "</table>";
                            $this->salida .= "</td>";
                            $this->salida .= "</tr>";
                          }
                        }
                         
                         $this->salida.=" <tr align='right' class=\"modulo_table_button\"><td colspan='10'>";                         
                         $this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"CONFIRMAR\">";
                         $this->salida.=" </td>";
                         $this->salida .= "</tr>";
                         $this->salida.="</table>";

                         $this->salida .= "</td></tr>\n";
                         $this->salida.="</table></form>";
                }
                if($contador !=4)
                {$contador=1;}
               }//fin for
          }//fin foreach

          if($contador==1)
          {
               $this->salida .= "	<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" \n>";
               $this->salida .= "<tr><td align=\"center\" ><label class='label_mark'>NO HAY SOLICITUDES PENDIENTES POR DEPACHAR</label>";
               $this->salida .= "</td></tr>";
               $this->salida.="</table><br>";

          }
          
          if ($_SESSION['Interna'] == true)
		{
               $hr = ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','FrmShowBodega',array("datos_estacion"=>$datos_estacion,'datosPaciente'=>$B,"switche"=>$SWITCHE));
               $this->salida .= "<div class='normal_10' align='center'><br><a href='".$hr."'>SELECCION DE BODEGA</a><br>";
          }
          else
          {
               $hr = ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','FrmShowBodega',array("datos_estacion"=>$datos_estacion,"switche"=>$SWITCHE));
               $this->salida .= "<div class='normal_10' align='center'><br><a href='".$hr."'>SELECCION DE BODEGA</a><br>";
          }
          
          if($flag)
          {
            $this->salida .= "<script>\n";
            $this->salida .= "  document.getElementById('nota').style.display=\"block\";\n";
            $this->salida .= "</script>\n";
          }
          
          //DATOS DEL PIE DE PAGINA
          if ($_SESSION['Interna'] == true)
          	$this->FrmPieDePagina($B,$datos_estacion);
          else
          	$this->FrmPieDePagina();
          unset($ItemBusqueda);
          return true;
     }
     
	//funcion que confirma si se va a cancelar la solicitud
     function ConfirmarDespSolicitudIns()
     {
          $bodega = $_REQUEST['bodega'];
          $SWITCHE = $_REQUEST['switche'];
          // Cambiamos las asignacion de Request a Variable de Session
          $datos_estacion = $_SESSION['EE_I_y_M']['Estacion'];
	     // Cambiamos las asignacion de Request a Variable de Session
          $datosPaciente = $_SESSION['EE_I_y_M']['Pacientes'];
          $op = $_REQUEST['opcion'];
          $plan = $_REQUEST['plan'];
          $cuenta = $_REQUEST['cuenta'];
          $medic = $_SESSION['ESTACION']['VECTOR_DESP_INS'][$_REQUEST['ingreso']];
     
          if(sizeof($medic) AND sizeof($op))
          {
               unset($matriz);
               for($h=0;$h<sizeof($op);$h++)
               {
                    $dat_op=explode(",",$op[$h]);
                    $matriz[$h]=$dat_op[0];
               }
     
               if(!empty($medic))
               {
                    $this->salida .= ThemeAbrirTabla('CONFIRMACION DESPACHO DE INSUMOS');
                    $f = ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','InsertDespSolicitudMed',array("plan"=>$plan,"cuenta"=>$cuenta,"datos_estacion"=>$datos_estacion,"bodega"=>$bodega,"matriz"=>$matriz,"op"=>$op,"switche"=>$SWITCHE));
                    $this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";

                    $this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class='modulo_list_table'\n>";
                    $this->salida .= "		<tr  class='modulo_table_title'>\n";
                    $this->salida .= "			<td align=\"center\" width=\"5%\" >SOLICITUD</td>\n";
                    $this->salida .= "			<td align=\"center\" width=\"20%\" >CODIGO</td>\n";
                    $this->salida .= "			<td align=\"center\" width=\"20%\" >PRODUCTO</td>\n";
                    $this->salida .= "			<td align=\"center\" width=\"5%\" >CANT</td>\n";
                    $this->salida .= "			<td align=\"center\" width=\"20%\"  >CODIGO DESP</td>\n";
                    $this->salida .= "			<td align=\"center\" width=\"20%\" >PRODUCTO DESP</td>\n";
                    $this->salida .= "			<td align=\"center\" width=\"5%\" >CANT DESP</td>\n";
                    $this->salida .= "			<td align=\"center\" width=\"2%\" ></td>\n";
                    $this->salida .= "		</tr>\n";

                    for($i=0;$i<sizeof($medic);$i++)
                    {
                         if($i % 2)  $estilo = "class=modulo_list_claro";  else  $estilo = "class=modulo_list_oscuro";
 
                         if(in_array($medic[$i][solicitud_id],$matriz)==TRUE)
                         {

                              if($medic[$i][solicitud_id]!= $medic[$i-1][solicitud_id])
                              {
                                   $this->salida .= "<tr $estilo>\n";
                                   $this->salida .= "<td colspan = 1  align=\"center\" class=modulo_list_claro width=\"10%\">".$medic[$i][solicitud_id]."</td>\n";
                                   $solicitud=$medic[$i][solicitud_id];
                                   $this->salida .= "<td colspan = 6 width=\"65%\">";
                                   $this->salida .= "	<table align=\"center\" width=\"100%\" border=\"0\" class='modulo_list_table'\n>";
                              }


                              $this->salida .= "<tr $estilo>\n";
                              $this->salida .= "<td $estilo width=\"20%\">".$medic[$i][codigo_producto]."</td>\n";
                              $this->salida .= "<td $estilo width=\"20%\">".$medic[$i][producto]."</td>\n";
                              $this->salida .= "<td $estilo align=\"center\" width=\"5%\">".floor($medic[$i][cantidad])."</td>\n";
                              $despacho=$this->GetDatosDespachoIns($medic[$i][doc],$medic[$i][consecutivo_d],$medic[$i][solicitud_id]);

                              if(empty($despacho[0][codigo_producto]) AND empty($despacho[0][descripcion]))
                              {
                                   $this->salida .= "<td $estilo colspan='2' width=\"20%\"><label class='label_mark'>No Despachado</label></td>\n";
                              }
                              else
                              {
                                   $this->salida .= "<td $estilo width=\"20%\">".$despacho[0][codigo_producto]."</td>\n";
                                   $this->salida .= "<td $estilo width=\"20%\">".$despacho[0][descripcion]."</td>\n";
                              }
                              $cant_desp=floor($despacho[0][cantidad]);
                              if($cant_desp <=0){$cant_desp='';}
                              $this->salida .= "<td $estilo width=\"5%\">$cant_desp</td>\n";

                              $this->salida.=" </tr>";
                              if($medic[$i][solicitud_id] != $medic[$i+1][solicitud_id])
                              {

                                   $this->salida .= "</table>";
                                   $this->salida .= "</td>";
                                   $this->salida.="  <td colspan = 1 $estilo width=\"2%\" align=\"center\"><img src=\"". GetThemePath() ."/images/checkS.gif\" border='0'></label></td>";
                                   $this->salida .= "</tr>";
                              }
                         }
                    }
                    $this->salida.="</table><br>";
               }

               $this->salida.="<br><table border=\"0\" align=\"center\" width=\"40%\">";
               $this->salida.=" <tr>";
               $this->salida.=" <td align=\"center\">";
               $this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"CONFIRMAR\"></form>";
               $this->salida.=" </td>";


               $href = ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','CallInsumosMed_X_Despachar',array("bodega"=>$bodega,"switche"=>$SWITCHE));
               $this->salida .="<form name=forma action=".$href." method=post>";
               $this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"CANCELAR\" class=\"input-submit\"></form></td>";
               $this->salida.=" </tr>";
               $this->salida.=" </table>";
               $this->salida .= ThemeCerrarTabla();
          }
          else
          {
               $this->salida .= ThemeAbrirTabla('CONFIRMACION DE SOLICITUDES DESPACHADAS',"50%");
               $this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
               $this->salida .= "		<tr >\n";
               $this->salida .= "			<td align=\"center\"><label class='label_mark'>NO SE SELECCIONO NINGUNA CASILLA !</label></td>\n";
               $this->salida.="</tr></table>";
               $this->salida.="<br><table border=\"0\" align=\"center\" width=\"10%\">";
               $this->salida.=" <tr>";
               $href = ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','CallInsumosMed_X_Despachar',array("bodega"=>$bodega,"switche"=>$SWITCHE));
               $this->salida .="<form name=forma action=".$href." method=post>";
               $this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"VOLVER\" class=\"input-submit\"></form></td>";
               $this->salida.=" </tr>";
               $this->salida.=" </table>";
               $this->salida .= ThemeCerrarTabla();
          }
          return true;
     }

     
     
     //funcion que confirma si se va a cancelar la solicitud
    function ConfirmarDespSolicitudMed()
    {
      $bodega=$_REQUEST['bodega'];
      $SWITCHE=$_REQUEST['switche'];
      // Cambiamos las asignacion de Request a Variable de Session          
      $datos_estacion = $_SESSION['EE_I_y_M']['Estacion'];
      // Cambiamos las asignacion de Request a Variable de Session
      $datosPaciente = $_SESSION['EE_I_y_M']['Pacientes'];
      $op=$_REQUEST['opcion'];
      $plan=$_REQUEST['plan'];
      $cuenta=$_REQUEST['cuenta'];
      $medic=$_SESSION['ESTACION']['VECTOR_DESP'][$_REQUEST['ingreso']];
     
      if(sizeof($medic) AND sizeof($op))
      {
        unset($matriz);
        for($h=0;$h<sizeof($op);$h++)
        {
          $dat_op=explode(",",$op[$h]);
          $matriz[$h]=$dat_op[0];
        }

        if(!empty($medic))
        {
          $this->salida .= ThemeAbrirTabla('CONFIRMACION DESPACHO DE MEDICAMENTOS');
          $f = ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','InsertDespSolicitudMed',array("plan"=>$plan,"cuenta"=>$cuenta,"datos_estacion"=>$datos_estacion,"bodega"=>$bodega,"matriz"=>$matriz,"op"=>$op,"switche"=>$SWITCHE));
          $this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";
          $this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class='modulo_list_table'\n>";
          $this->salida .= "		<tr  class='modulo_table_title'>\n";
          $this->salida .= "			<td align=\"center\" width=\"5%\" >SOLICITUD</td>\n";
          $this->salida .= "			<td align=\"center\" width=\"20%\" >CODIGO</td>\n";
          $this->salida .= "			<td align=\"center\" width=\"20%\" >PRODUCTO</td>\n";
          $this->salida .= "			<td align=\"center\" width=\"5%\" >CANT</td>\n";
          $this->salida .= "			<td align=\"center\" width=\"20%\"  >CODIGO DESP</td>\n";
          $this->salida .= "			<td align=\"center\" width=\"20%\" >PRODUCTO DESP</td>\n";
          $this->salida .= "			<td align=\"center\" width=\"5%\" >CANT DESP</td>\n";
          $this->salida .= "			<td align=\"center\" width=\"2%\" ></td>\n";
          $this->salida .= "		</tr>\n";

          for($i=0;$i<sizeof($medic);$i++)
          {
            if($i % 2)  $estilo = "class=modulo_list_claro";  else  $estilo = "class=modulo_list_oscuro";
            if(in_array($medic[$i][solicitud_id],$matriz)==TRUE)
            {
              if($medic[$i][solicitud_id]!= $medic[$i-1][solicitud_id])
              {
                $this->salida .= "<tr $estilo>\n";
                $this->salida .= "<td colspan = 1  align=\"center\" class=modulo_list_claro width=\"10%\">".$medic[$i][solicitud_id]."</td>\n";
                $solicitud=$medic[$i][solicitud_id];
                $this->salida .= "<td colspan = 6 width=\"65%\">";
                $this->salida .= "	<table align=\"center\" width=\"100%\" border=\"0\" class='modulo_list_table'\n>";
              }
              
              $flag = false;
              $despacho = $this->GetDatosDespacho($medic[$i][doc],$medic[$i][consecutivo_d],$medic[$i][solicitud_id]);
              if(empty($despacho)) $despacho[0]['codigo'] = "";
              $cls = sizeof($despacho);
              foreach($despacho as $key => $dtl)
              {
                $this->salida .= "<tr $estilo>\n";
                if(!$flag)
                {
                  $this->salida .= "  <td rowspan=\"".$cls."\" width=\"20%\">".$medic[$i][codigo_producto]."</td>\n";
                  $this->salida .= "  <td rowspan=\"".$cls."\" width=\"20%\">".$medic[$i][producto]."</td>\n";
                  $this->salida .= "  <td rowspan=\"".$cls."\" align=\"center\" width=\"5%\">".floor($medic[$i][cant_solicitada])."</td>\n";
                  $flag = true;
                }
                
                if(empty($dtl['codigo_producto']) AND empty($dtl['descripcion']))
                {
                  $this->salida .= "  <td colspan='2' width=\"20%\"><label class='label_mark'>No Despachado</label></td>\n";
                }
                else
                {
                  $this->salida .= "  <td width=\"20%\">".$dtl[codigo_producto]."</td>\n";
                  $this->salida .= "  <td width=\"20%\">".$dtl[descripcion]."</td>\n";
                }
                $cant_desp=floor($dtl[cantidad]);
                if($cant_desp <=0){$cant_desp='';}
                $this->salida .= "  <td width=\"5%\">$cant_desp</td>\n";

                $this->salida.=" </tr>\n";
              }
              if($medic[$i][solicitud_id] != $medic[$i+1][solicitud_id])
              {
                   $this->salida .= "</table>";
                   $this->salida .= "</td>";
                   $this->salida.="  <td colspan = 1 $estilo width=\"2%\" align=\"center\"><img src=\"". GetThemePath() ."/images/checkS.gif\" border='0'></label></td>";
                   $this->salida .= "</tr>";
              }
            }
          }
          $this->salida.="</table><br>";
        }

        $this->salida.="<br><table border=\"0\" align=\"center\" width=\"40%\">";
        $this->salida.=" <tr>";
        $this->salida.=" <td align=\"center\">";
        $this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"CONFIRMAR\"></form>";
        $this->salida.=" </td>";
        $href = ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','CallInsumosMed_X_Despachar',array("bodega"=>$bodega,"switche"=>$SWITCHE));
        $this->salida .="<form name=forma action=".$href." method=post>";
        $this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"CANCELAR\" class=\"input-submit\"></form></td>";
        $this->salida.=" </tr>";
        $this->salida.=" </table>";
        $this->salida .= ThemeCerrarTabla();
      }
      else
      {
        $this->salida .= ThemeAbrirTabla('CONFIRMACION DE SOLICITUDES DESPACHADAS',"50%");
        $this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
        $this->salida .= "		<tr >\n";
        $this->salida .= "			<td align=\"center\"><label class='label_mark'>NO SE SELECCIONO NINGUNA CASILLA !</label></td>\n";
        $this->salida.="</tr></table>";
        $this->salida.="<br><table border=\"0\" align=\"center\" width=\"10%\">";
        $this->salida.=" <tr>";
        $href = ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','CallInsumosMed_X_Despachar',array("estacion"=>$estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
        $this->salida .="<form name=forma action=".$href." method=post>";
        $this->salida .="<td><input type=\"submit\" align=\"center\" name=\"CANCELAR\" value=\"Volver\" class=\"input-submit\"></form></td>";
        $this->salida.=" </tr>";
        $this->salida.=" </table>";
        $this->salida .= ThemeCerrarTabla();
      }
      return true;
    }

     /*
     *		@Author Tizziano Perea Ocoro.
     *		@access Private
     *		@return bool
     *		Proposito: Unificacion de funciones para recibir insumos y medicamentos
     */
     function MedicamentosIns_X_Recibir($datos_estacion,$bodega,$SWITCHE)
     {
          if(empty($datos_estacion))
          {
               $datos_estacion = $_REQUEST['estacion'];
               $SWITCHE = $_REQUEST['switche'];
               $bodega = $_REQUEST['bodega'];
          }
          $_SESSION['ESTA_MEDIC']['ESTADO']=1;
          
          $a = 0;
          foreach($_SESSION['EE_PanelEnfermeria']['listadoPacientes'] as $k => $Pacientes)
          {
               $datosPaciente[$a] = $Pacientes;
               $a++;
               $i = $a;						
          }

          unset($Pacientes);
          foreach($_SESSION['EE_PanelEnfermeria']['listadoPacientes_Urgencias'] as $k => $Pacientes)
          {
               $datosPaciente[$i] = $Pacientes;
               $i++;
          }

          $nom_bodega=$this->TraerNombreBodega($datos_estacion,$bodega);

          $this->salida .= ThemeAbrirTabla("SOLICITUDES REALIZADAS DE INSUMOS Y MEDICAMENTOS (Pendiente Despacho) &nbsp; -- &nbsp; BODEGA  ".strtoupper($nom_bodega)."");
          $this->salida .= " <table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
          $this->salida .= $this->SetStyle("MensajeError");
          $this->salida .= " </table>";
          foreach($datosPaciente as $A => $B)
          {
               for($tpo=0; $tpo<2; $tpo++)
               {
                    if($p++ % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";

                    if($tpo==0)
                    {
                         //consulta de medicamentos solicitados
                         if($B['ingreso'])
                         {
		                    $medic=$this->GetInsumosSolicitados($B['ingreso'],$datos_estacion,$bodega);
                         }
                    }elseif($tpo==1)
                    {
                         //consulta de medicamentos solicitados
                         if($B['ingreso'])
                         {
                         	$medic=$this->GetMedicamentosSolicitados($B['ingreso'],$datos_estacion,$bodega);
                         }
                    }

                    if(!empty($medic))
                    {
                         $contador=4;
                         if($tpo==0)
                         {
                              $_SESSION['ESTACION']['VECTOR_SOL_INS'][$B[ingreso]]=$medic;
                              //mandamos spia en 1 para usar la misma funcion de cancelar solicitud
                              //individual...y con esta variable sabemos q retornara aqui.
                              $f = ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','ConfirmarCancelSolicitudIns',array('ingreso'=>$B[ingreso],'spia'=>1,"datos_estacion"=>$datos_estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
                              $this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";
                         }elseif($tpo==1)
                         {
                              $_SESSION['ESTACION']['VECTOR_SOL'][$B[ingreso]]=$medic;
                              //mandamos spia en 1 para usar la misma funcion de cancelar solicitud
                              //individual...y con esta variable sabemos q retornara aqui.
                              $f = ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','ConfirmarCancelSolicitudMed',array('ingreso'=>$B[ingreso],'spia'=>1,"datos_estacion"=>$datos_estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
                              $this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";
                         }

                         $this->salida .= "<table align=\"center\" width=\"85%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
                         
                         if($tpo==0)
                         {
                              $this->salida .= "	<tr class=hc_table_submodulo_list_title>\n";
                              $this->salida .= "	<td colspan=\"4\">INSUMOS POR RECIBIR</td>\n";
                              $this->salida .= "	</tr>\n";
                         }elseif($tpo==1)
                         {
                              $this->salida .= "	<tr class=hc_table_submodulo_list_title>\n";
                              $this->salida .= "	<td colspan=\"4\">MEDICAMENTOS POR RECIBIR</td>\n";
                              $this->salida .= "	</tr>\n";
                         }

                         $this->salida .= "	<tr class='modulo_table_title'>\n";
                         $this->salida .= "		<td>HAB.</td>\n";
                         $this->salida .= "		<td>CAMA</td>\n";
                         $this->salida .= "		<td>TIEMPO HOSP.</td>\n";
                         $this->salida .= "		<td>PACIENTE</td>\n";
                         $this->salida .= "	</tr>\n";

                         $this->salida .= "	<tr class=hc_table_submodulo_list_title>\n";
                         if(empty($B[pieza]))
                         {
                              $this->salida .= "	<td align=\"center\">No Ingresado</td>\n";
                              $this->salida .= "	<td align=\"center\">No Ingresado</td>\n";
                         }
                         else
                         {
                              $this->salida .= "	<td align=\"center\">".$B[pieza]."</td>\n";
                              $this->salida .= "	<td align=\"center\">".$B[cama]."</td>\n";
                         }
                         $diasHospitalizacion = $this->GetDiasHospitalizacion($B[fecha_ingreso]);
                         $this->salida .= "	<td align=\"center\">".$diasHospitalizacion."</td>\n";
                         //$linkVerDatos = ModuloGetURL('app','EstacionE_Pacientes','user','CallMostrarDatosIngreso',array("ingresoID"=>$B['ingreso'],"retorno"=>"CallListRevisionPorSistemas","datos_estacion"=>$estacion,"modulito"=>'EstacionE_ControlPacientes'));
                         $this->salida .= "	<td>".$B[nombre_completo]."</td>\n";
                         $this->salida .= "	</tr>\n";


                         $this->salida .= "	<tr class='hc_table_submodulo_list_title'><td colspan='4'>\n";
                         $this->salida .= "	<br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class='modulo_list_table'\n>";

                         $this->salida .= "		<tr class='modulo_table_title'>\n";
                         $this->salida .= "			<td width=\"10%\" >SOLICITUD</td>\n";
                         $this->salida .= "			<td width=\"10%\" >CODIGO</td>\n";
                         $this->salida .= "			<td width=\"25%\" >PRODUCTO</td>\n";
                         $this->salida .= "			<td width=\"25%\"  >PRINCIPIO ACTIVO</td>\n";
                         $this->salida .= "			<td width=\"5%\" >CANT</td>\n";
                         $this->salida .= "			<td width=\"2%\" ></td>\n";
                         $this->salida .= "		</tr>\n";


                         for($i=0;$i<sizeof($medic);$i++)
                         {
                              if($i % 2)  $estilo = "class=modulo_list_claro";  else  $estilo = "class=modulo_list_oscuro";
                              //if($medic[$i][solicitud_id]!=$solicitud)
                              if($medic[$i][solicitud_id]!= $medic[$i-1][solicitud_id])
                              {
                                   $this->salida .= "<tr $estilo>\n";
                                   $this->salida .= "<td colspan = 1  align=\"center\" class=modulo_list_claro width=\"10%\">".$medic[$i][solicitud_id]."</td>\n";
                                   $solicitud=$medic[$i][solicitud_id];
                                   $this->salida .= "<td colspan = 4 width=\"65%\">";
                                   $this->salida .= "	<table align=\"center\" width=\"100%\" border=\"0\" class='modulo_list_table'\n>";
                              }

                              $this->salida .= "<tr $estilo>\n";
                              $this->salida .= "<td $estilo width=\"12%\">".$medic[$i][codigo_producto]."</td>\n";
                              $this->salida .= "<td $estilo width=\"30%\">".$medic[$i][producto]."</td>\n";
                              $this->salida .= "<td $estilo width=\"30%\">".$medic[$i][principio_activo]."</td>\n";
                              if($medic[$i][tipo_solicitud] == "M")
                              { $cantidad_Sol = $medic[$i][cant_solicitada]; } 
                              else
                              { $cantidad_Sol = $medic[$i][cantidad]; } 
                              
                              $this->salida .= "<td $estilo align=\"center\" width=\"7%\">".floor($cantidad_Sol)."</td>\n";
                              $this->salida.=" </tr>";
                              if($medic[$i][solicitud_id] != $medic[$i+1][solicitud_id])
                              {

                                   $this->salida .= "</table>";
                                   $this->salida .= "</td>";
                                   $this->salida.="  <td colspan = 1 $estilo width=\"2%\" align=\"center\"><input type=checkbox name=opcion[] value=".$medic[$i][solicitud_id].",".$medic[$i][consecutivo_d]."></td>";
                                   $this->salida .= "</tr>";

                              }
                         }
                         $this->salida.=" <tr align='right' class=\"modulo_table_button\"><td colspan='6'>";
                         $this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"CANCELAR\">";
                         $this->salida.=" </td>";
                         $this->salida .= "</tr>";
                         $this->salida.="</table></form><br>";

                         $this->salida .= "</td></tr>\n";
                         $this->salida.="</table>";
                    }
                    if($contador !=4)
                    {$contador=1;}
               }//fin for
          }//fin foreach

          if($contador==1)
          {
               $this->salida .= "	<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" \n>";
               $this->salida .= "<tr><td align=\"center\" ><label class='label_mark'>NO HAY SOLICITUDES DE MEDICAMENTOS PARA ESTA BODEGA</label>";
               $this->salida .= "</td></tr>";
               $this->salida.="</table><br>";
          }
          
          $hr = ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','FrmShowBodega',array("datos_estacion"=>$datos_estacion,"switche"=>$SWITCHE));
          $this->salida .= "<div class='normal_10' align='center'><br><a href='".$hr."'>SELECCION DE BODEGA</a><br>";
          //DATOS DEL PIE DE PAGINA
          $this->FrmPieDePagina();
          unset($ItemBusqueda);
          return true;
	}
     
     
     //funcion que confirma si se va a cancelar la solicitud
     //esta pantalla muestra para confirmar la cancelación de los insumos 
     function ConfirmarCancelSolicitudIns()
     {
          $bodega=$_REQUEST['bodega'];
          $SWITCHE=$_REQUEST['switche'];
          $datos_estacion=$_REQUEST['datos_estacion'];
          $op=$_REQUEST['opcion'];
          $spy=$_REQUEST['spia']; //variable q determina a donde me dirigo cuando se cancele una solicitud.
          $ingreso=$_REQUEST['ingreso'];
          $medic=$_SESSION['ESTACION']['VECTOR_SOL_INS'][$ingreso];
          if(sizeof($medic) AND sizeof($op))
          {
               unset($matriz);
               for($h=0;$h<sizeof($op);$h++)
               {
                    $dat_op=explode(",",$op[$h]);
                    $matriz[$h]=$dat_op[0];
               }
               $this->salida .= ThemeAbrirTabla('CANCELAR SOLICITUD DE INSUMOS');
               $f = ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','CancelSolicitudInsumos',array("spia"=>$spy,"datos_estacion"=>$datos_estacion,"bodega"=>$bodega,"matriz"=>$matriz,"switche"=>$SWITCHE));
               $this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";
     
               $this->salida .= "	<table align=\"center\" width=\"85%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class='modulo_list_table'\n>";
     
               $this->salida .= "		<tr class=\"modulo_table_title\">\n";
               $this->salida .= "			<td colspan='7'  align=\"center\">MEDICAMENTOS SOLICITADOS</td>\n";
               $this->salida .= "		</tr>\n";
     
               $this->salida .= "		<tr class=\"hc_table_submodulo_list_title\">\n";
               $this->salida .= "			<td width=\"5%\" >SOLICITUD</td>\n";
               $this->salida .= "			<td width=\"17%\" >BODEGA</td>\n";
               $this->salida .= "			<td width=\"10%\" >CODIGO</td>\n";
               $this->salida .= "			<td width=\"25%\" >PRODUCTO</td>\n";
               $this->salida .= "			<td width=\"25%\"  >PRINCIPIO ACTIVO</td>\n";
               $this->salida .= "			<td width=\"5%\" >CANTIDAD</td>\n";
               $this->salida .= "			<td width=\"5%\" ></td>\n";
               $this->salida .= "		</tr>\n";
     
               for($i=0;$i<sizeof($medic);$i++)
               {
                    if($i % 2)  $estilo = "class=modulo_list_claro";  else  $estilo = "class=modulo_list_oscuro";
                    if(in_array($medic[$i][solicitud_id],$matriz)==TRUE)
                    {
                         if($medic[$i][solicitud_id]!= $medic[$i-1][solicitud_id])
                         {
                              $this->salida .= "<tr $estilo>\n";
                              $this->salida .= "<td colspan = 1 width=\"5%\" align=\"center\" class=modulo_list_claro width=\"10%\">".$medic[$i][solicitud_id]."</td>\n";
                              $solicitud=$medic[$i][solicitud_id];
                              $this->salida .= "<td colspan = 5 width=\"65%\">";
                              $this->salida .= "	<table align=\"center\" width=\"100%\" border=\"0\" class='modulo_list_table'\n>";
                         }
     
                         $nom_bodega=$this->TraerNombreBodega($estacion,$medic[$i][bodega]);
                         $this->salida .= "<tr $estilo>\n";
                         $this->salida .= "<td $estilo width=\"20%\"><label class='label_mark'>$nom_bodega</label></td>\n";
                         $this->salida .= "<td $estilo width=\"12%\">".$medic[$i][codigo_producto]."</td>\n";
                         $this->salida .= "<td $estilo width=\"30%\">".$medic[$i][producto]."</td>\n";
                         $this->salida .= "<td $estilo width=\"30%\">".$medic[$i][principio_activo]."</td>\n";
                         $this->salida .= "<td $estilo align=\"center\" width=\"7%\">".floor($medic[$i][cantidad])."</td>\n";
                         $this->salida.=" </tr>";
                         if($medic[$i][solicitud_id] != $medic[$i+1][solicitud_id])
                         {
                              $this->salida .= "</table>";
                              $this->salida .= "</td>";
                              $this->salida.="  <td colspan = 1 $estilo width=\"5%\" align=\"center\"><img src=\"". GetThemePath() ."/images/checkS.gif\" border='0'></td>";
                              $this->salida .= "</tr>";
                         }
                    }
               }
               $this->salida.="</tr>";
               $this->salida.="<tr class=\"modulo_list_claro\">";
               $this->salida .= "<td  colspan='2' align='right' width=\"35%\"  >JUSTIFICACION :</td>";
               $this->salida .= "<td colspan='5'  align=\"left\"><TEXTAREA name=obs cols=100 rows=8>".$_REQUEST['obs']."</TEXTAREA></td>";

               $this->salida.="</tr></table><br>";
               $this->salida.="<br><table border=\"0\" align=\"center\" width=\"40%\">";
               $this->salida.=" <tr>";
               $this->salida.=" <td align=\"center\">";
               $this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"CONFIRMAR\"></form>";
               $this->salida.=" </td>";

               $href = ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','CallMedicamentosIns_X_Recibir',array("datos_estacion"=>$datos_estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
               $this->salida .="<form name=forma action=".$href." method=post>";
               $this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"CANCELAR\" class=\"input-submit\"></form></td>";
               $this->salida.=" </tr>";
               $this->salida.=" </table>";
               $this->salida .= ThemeCerrarTabla();
          }
          else
          {
               $this->salida .= ThemeAbrirTabla("CONTROL INSUMOS PACIENTE","50%");
               $this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
               $this->salida .= "		<tr >\n";
               $this->salida .= "			<td align=\"center\"><label class='label_mark'>NO SE SELECCIONO NINGUNA CASILLA !</label></td>\n";
               $this->salida.="</tr></table>";
	          $this->salida.="<br><table border=\"0\" align=\"center\" width=\"10%\">";
               $this->salida.=" <tr>";
               
               $href = ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','CallMedicamentosIns_X_Recibir',array("datos_estacion"=>$datos_estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
     
               $this->salida .="<form name=forma action=".$href." method=post>";
               $this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"VOLVER\" class=\"input-submit\"></form></td>";
               $this->salida.=" </tr>";
               $this->salida.=" </table>";
               $this->salida .= ThemeCerrarTabla();
          }
          return true;
     }
     
     //funcion que confirma si se va a cancelar la solicitud
     function ConfirmarCancelSolicitudMed()
     {
          $bodega=$_REQUEST['bodega'];
          $SWITCHE=$_REQUEST['switche'];
          $datos_estacion=$_REQUEST['datos_estacion'];
          $op=$_REQUEST['opcion'];
          $spy=$_REQUEST['spia']; //variable q determina a donde me dirigo cuando se cancele una solicitud.
          $ingreso=$_REQUEST['ingreso'];
          $medic=$_SESSION['ESTACION']['VECTOR_SOL'][$ingreso];
          if(sizeof($medic) AND sizeof($op))
          {
               unset($matriz);
               for($h=0;$h<sizeof($op);$h++)
               {
                    $dat_op=explode(",",$op[$h]);
                    $matriz[$h]=$dat_op[0];
               }
               $this->salida .= ThemeAbrirTabla('CANCELAR SOLICITUD DE MEDICAMENTOS');
               $f = ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','CancelSolicitudMedicametos',array("spia"=>$spy,"datos_estacion"=>$datos_estacion,"bodega"=>$bodega,"matriz"=>$matriz,"switche"=>$SWITCHE));
               $this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";
     
               $this->salida .= "	<table align=\"center\" width=\"85%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class='modulo_list_table'\n>";
     
               $this->salida .= "		<tr class=\"modulo_table_title\">\n";
               $this->salida .= "			<td colspan='7'  align=\"center\">MEDICAMENTOS SOLICITADOS</td>\n";
               $this->salida .= "		</tr>\n";
     
               $this->salida .= "		<tr class=\"hc_table_submodulo_list_title\">\n";
               $this->salida .= "			<td width=\"5%\" >SOLICITUD</td>\n";
               $this->salida .= "			<td width=\"17%\" >BODEGA</td>\n";
               $this->salida .= "			<td width=\"10%\" >CODIGO</td>\n";
               $this->salida .= "			<td width=\"25%\" >PRODUCTO</td>\n";
               $this->salida .= "			<td width=\"25%\"  >PRINCIPIO ACTIVO</td>\n";
               $this->salida .= "			<td width=\"5%\" >CANT</td>\n";
               $this->salida .= "			<td width=\"5%\" ></td>\n";
               $this->salida .= "		</tr>\n";
     
               for($i=0;$i<sizeof($medic);$i++)
               {
                    if($i % 2)  $estilo = "class=modulo_list_claro";  else  $estilo = "class=modulo_list_oscuro";
                    if(in_array($medic[$i][solicitud_id],$matriz)==TRUE)
                    {
                         if($medic[$i][solicitud_id]!= $medic[$i-1][solicitud_id])
                         {
                              $this->salida .= "<tr $estilo>\n";
                              $this->salida .= "<td colspan = 1 width=\"5%\" align=\"center\" class=modulo_list_claro width=\"10%\">".$medic[$i][solicitud_id]."</td>\n";
                              $solicitud=$medic[$i][solicitud_id];
                              $this->salida .= "<td colspan = 5 width=\"65%\">";
                              $this->salida .= "	<table align=\"center\" width=\"100%\" border=\"0\" class='modulo_list_table'\n>";
                         }
                         $nom_bodega=$this->TraerNombreBodega($estacion,$medic[$i][bodega]);
                         $this->salida .= "<tr $estilo>\n";
                         $this->salida .= "<td $estilo width=\"20%\"><label class='label_mark'>$nom_bodega</label></td>\n";
                         $this->salida .= "<td $estilo width=\"12%\">".$medic[$i][codigo_producto]."</td>\n";
                         $this->salida .= "<td $estilo width=\"30%\">".$medic[$i][producto]."</td>\n";
                         $this->salida .= "<td $estilo width=\"30%\">".$medic[$i][principio_activo]."</td>\n";
                         $this->salida .= "<td $estilo align=\"center\" width=\"7%\">".floor($medic[$i][cant_solicitada])."</td>\n";
                         $this->salida.=" </tr>";
                         if($medic[$i][solicitud_id] != $medic[$i+1][solicitud_id])
                         {
                              $this->salida .= "</table>";
                              $this->salida .= "</td>";
                              $this->salida.="  <td colspan = 1 $estilo width=\"5%\" align=\"center\"><img src=\"". GetThemePath() ."/images/checkS.gif\" border='0'></td>";
                              $this->salida .= "</tr>";
                         }
                    }
               }
               $this->salida.="</tr>";
               $this->salida.="<tr class=\"modulo_list_claro\">";
               $this->salida .= "<td  colspan='2' align='right' width=\"35%\"  >JUSTIFICACION :</td>";
               $this->salida .= "<td colspan='5'  align=\"left\"><TEXTAREA name=obs cols=100 rows=8>".$_REQUEST['obs']."</TEXTAREA></td>";

               $this->salida.="</tr></table><br>";
               $this->salida.="<br><table border=\"0\" align=\"center\" width=\"40%\">";
               $this->salida.=" <tr>";
               $this->salida.=" <td align=\"center\">";
               $this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"CONFIRMAR\"></form>";
               $this->salida.=" </td>";

               $href = ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','CallMedicamentosIns_X_Recibir',array("datos_estacion"=>$datos_estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
               
               $this->salida .="<form name=forma action=".$href." method=post>";
               $this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"CANCELAR\" class=\"input-submit\"></form></td>";
               $this->salida.=" </tr>";
               $this->salida.=" </table>";
               $this->salida .= ThemeCerrarTabla();
          }
          else
          {
               $this->salida .= ThemeAbrirTabla('CONTROL MEDICAMENTOS PACIENTE',"50%");
               $this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
               $this->salida .= "		<tr >\n";
               $this->salida .= "			<td align=\"center\"><label class='label_mark'>NO SE SELECCIONO NINGUNA CASILLA !</label></td>\n";
               $this->salida.="</tr></table>";
	          $this->salida.="<br><table border=\"0\" align=\"center\" width=\"10%\">";
               $this->salida.=" <tr>";
	          $href = ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','CallMedicamentosIns_X_Recibir',array("datos_estacion"=>$datos_estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
               $this->salida .="<form name=forma action=".$href." method=post>";
               $this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"VOLVER\" class=\"input-submit\"></form></td>";
               $this->salida.=" </tr>";
               $this->salida.=" </table>";
               $this->salida .= ThemeCerrarTabla();
          }
          return true;
     }
     
  	function FrmCancelarDespSolicitud($solicitud_id,$ingreso,$plan,$cuenta,$datos_estacion,$datosPaciente,$bodega,$switche)
     {
          //Validar si el usuario esta logueado y si tiene permisos.
          if(!$this->GetUserPermisos('57'))
          {
               $url= ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
               $titulo='VALIDACION DE PERMISOS';
               $mensaje='El usuario no tiene permiso para : Cancelar Solicitudes de Insumos y Medicamentos Previamente Despachadas [57]';
               $this->frmMSG($url, $titulo, $mensaje);
               return true;
          }
          
          $nom_bodega=$this->TraerNombreBodega($datos_estacion,$bodega);
          $this->salida .= ThemeAbrirTabla("CANCELACION DE DESPACHO DE INSUMOS Y MEDICAMENTOS");
          
          foreach($datosPaciente as $A => $B){
          for($tpo=0; $tpo<2; $tpo++){
               if($p++ % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
               if($tpo==0){
               //consulta de medicamentos solicitados
               $medic=$this->GetInsumosPendDesp($B['ingreso'],$datos_estacion,$bodega,$solicitud_id);
               }elseif($tpo==1){
               //consulta de medicamentos solicitados
               $medic=$this->GetMedicamentosPendDesp($B['ingreso'],$datos_estacion,$bodega,$solicitud_id);
               }
               if(!empty($medic)){
               $contador=4;            
               $f = ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','GuardarCancelarDespSolicitud',array("solicitud_id"=>$solicitud_id,"ingreso"=>$B[ingreso],"plan"=>$B[plan_id],"cuenta"=>$B[numerodecuenta],"bodega"=>$bodega,"switche"=>$SWITCHE));
               $this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";            
               $this->salida .= "<table align=\"center\" width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
               $this->salida .= "<tr><td colspan=\"4\" width=\"100%\">".$this->SetStyle("MensajeError")."</td></tr>";
               if($tpo==0){
                    $this->salida .= "  <tr class=hc_table_submodulo_list_title>\n";
                    $this->salida .= "  <td colspan=\"4\">CANCELACION DE INSUMOS</td>\n";
                    $this->salida .= "  </tr>\n";
               }elseif($tpo==1){
                    $this->salida .= "  <tr class=hc_table_submodulo_list_title>\n";
                    $this->salida .= "  <td colspan=\"4\">CANCELACION DE MEDICAMENTOS</td>\n";
                    $this->salida .= "  </tr>\n";
               }
                    
               $this->salida .= " <tr class='modulo_table_title'>\n";
               $this->salida .= "   <td>HABITACION</td>\n";
               $this->salida .= "   <td>CAMA</td>\n";
               $this->salida .= "   <td>TIEMPO HOSPITALIZACION</td>\n";
               $this->salida .= "   <td>PACIENTE</td>\n";
               $this->salida .= " </tr>\n";
     
               $this->salida .= " <tr class=hc_table_submodulo_list_title>\n";
               if(empty($B[pieza])){
               $this->salida .= "  <td align=\"center\">No Ingresado</td>\n";
               $this->salida .= "  <td align=\"center\">No Ingresado</td>\n";
               }else{
               $this->salida .= "  <td align=\"center\">".$B[pieza]."</td>\n";
               $this->salida .= "  <td align=\"center\">".$B[cama]."</td>\n";
               }
               $diasHospitalizacion = $this->GetDiasHospitalizacion($B[fecha_ingreso]);
               $this->salida .= " <td align=\"center\">".$diasHospitalizacion."</td>\n";
               $this->salida .= " <td>".$B[nombre_completo]."</td>\n";
               $this->salida .= " </tr>\n";
     
               $this->salida .= " <tr class=hc_table_submodulo_list_title><td colspan='4' width=\"100%\">\n";
               $this->salida .= " <br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class='modulo_list_table'\n>";
     
               $this->salida .= "   <tr  class='modulo_table_title'>\n";
               $this->salida .= "     <td align=\"center\" width=\"5%\" >SOLICITUD</td>\n";
               $this->salida .= "     <td align=\"center\" width=\"20%\" >CODIGO</td>\n";
               $this->salida .= "     <td align=\"center\" width=\"20%\" >PRODUCTO</td>\n";
               $this->salida .= "     <td align=\"center\" width=\"5%\" >CANT</td>\n";
               $this->salida .= "     <td align=\"center\" width=\"20%\"  >CODIGO DESP</td>\n";
               $this->salida .= "     <td align=\"center\" width=\"20%\" >PRODUCTO DESP</td>\n";
               $this->salida .= "     <td align=\"center\" width=\"5%\" >CANT DESP</td>\n";            
               $this->salida .= "   </tr>\n";
     
               for($i=0;$i<sizeof($medic);$i++){              
               if($i % 2)  $estilo = "class=modulo_list_claro";  else  $estilo = "class=modulo_list_oscuro";
               if($medic[$i][solicitud_id]!= $medic[$i-1][solicitud_id]){
                    $this->salida .= "<tr $estilo>\n";
                    $this->salida .= "<td colspan = 1  align=\"center\" class=modulo_list_claro width=\"10%\">";
                    $this->salida .= "".$medic[$i][solicitud_id]."";                
                    $this->salida .= "</td>\n";
                    $solicitud=$medic[$i][solicitud_id];
                    $this->salida .= "<td colspan =7 width=\"70%\">";
                    $this->salida .= " <table align=\"center\" width=\"100%\" border=\"0\" class='modulo_list_table'\n>";
               }
     
               $this->salida .= "<tr $estilo>\n";
               $this->salida .= "<td $estilo width=\"20%\">".$medic[$i][codigo_producto]."</td>\n";
               $this->salida .= "<td $estilo width=\"20%\">".$medic[$i][producto]."</td>\n";
               $this->salida .= "<td $estilo align=\"center\" width=\"5%\">".floor($medic[$i][cantidad])."</td>\n";
                                   
               if($tpo==1){          
                         $despacho=$this->GetDatosDespacho($medic[$i][doc],$medic[$i][consecutivo_d],$medic[$i][solicitud_id]);
               }elseif($tpo==0)
               {
                         $despacho=$this->GetDatosDespachoIns($medic[$i][doc],$medic[$i][consecutivo_d],$medic[$i][solicitud_id]);
               }
     
               if(empty($despacho[0][codigo_producto]) AND empty($despacho[0][descripcion]))
               {
                         $this->salida .= "<td $estilo colspan='2' width=\"20%\"><label class='label_mark'>No Despachado</label></td>\n";
               }
               else
               {
                         $this->salida .= "<td $estilo width=\"20%\">".$despacho[0][codigo_producto]."</td>\n";
                         $this->salida .= "<td $estilo width=\"20%\">".$despacho[0][descripcion]."</td>\n";
               }
               $cant_desp=floor($despacho[0][cantidad]);
               if($cant_desp <=0){$cant_desp='';}
               $this->salida .= "<td $estilo width=\"6%\">$cant_desp</td>\n";
               $this->salida.=" </tr>";
               if($medic[$i][solicitud_id] != $medic[$i+1][solicitud_id])
               {
                         $this->salida .= "</table>";
                         $this->salida .= "</td>";
                         $this->salida .= "</tr>";
               }              
               }                          
               
               $this->salida.="</table>";
     
               $this->salida .= "</td></tr>\n";            
               $this->salida.="<tr class=\"modulo_list_claro\">";
               $this->salida .= "<td  colspan='2' align='center' width=\"35%\">JUSTIFICACION :</td>";
               $this->salida .= "<td colspan='5' align=\"left\"><TEXTAREA name=obs cols=60 rows=6>".$_REQUEST['obs']."</TEXTAREA></td>";
               $this->salida.="</tr>";
               $this->salida.=" <tr class=\"modulo_table_button\"><td colspan='7' align='center'>";                         
               $this->salida.=" <input name=\"Guardar\" type=\"submit\" class=\"input-submit\"  value=\"GUARDAR\">";
               $this->salida.=" </td>";
               $this->salida .= "</tr>";
               $this->salida.="</table>";
               $this->salida.="</form>";
               }
               if($contador !=4)
               {$contador=1;}
          }//fin for
          }//fin foreach
          $this->salida.="  <table width='100%' border='0' cellspacing=\"0\" cellpadding=\"8\">\n";
          $this->salida.="    <tr>\n";
          $this->salida.="        <td align='center' class=\"label_error\">\n";
          $url = ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','CallInsumosMed_X_Despachar',array("bodega"=>$bodega,"switche"=>$switche));
          $this->salida.="            <a href='$url'>VOLVER</a>\n";
          $this->salida.="        </td>\n";
          $this->salida.="    </tr>\n";
          $this->salida.="  </table>\n";      
          $this->salida .= ThemeCerrarTabla();
     }
  
  	/**
	*	Frm_SelectOption Selecciona el tipo de producto que desea consultar
	*
	*	@Tizziano Perea O.
	*	@access Private
	*	@param array datos de la estacion
	*	@return boolean
	*/
	function Frm_SelectOption()
	{
          //Validar si el usuario esta logueado y si tiene permisos.
          if(!$this->GetUserPermisos('12'))
          {
               $url= ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
               $titulo='VALIDACION DE PERMISOS';
               $mensaje='El usuario no tiene permiso para : Bodegas Estacion - Recibir Despachos [12]';
               $this->frmMSG($url, $titulo, $mensaje);
               return true;
          }
          
          if(empty($datos_estacion))
          {
               $datos_estacion = $_REQUEST['datos_estacion'];
          }
          
          if(empty($datosPaciente))
          {
               $datosPaciente = $_REQUEST['datosPaciente'];
          }
		
          if(empty($datos_estacion))
          	$datos_estacion = &$this->GetdatosEstacion();
     
          //VALIDACION DE PERMISOS
          if(!is_array($datos_estacion))
          {
               $url= ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
               $titulo = "VALIDACION DE PERMISOS";
               $this->frmMSG($url,$titulo);
               return true;
          }
          
          $this->salida .= ThemeAbrirTabla("SELECCIONAR BODEGAS DE LA ESTACION &nbsp;".$datos_estacion[descripcion_estacion]."");
          
          $f = ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','Call_RutaProductos',array("datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente));
          $this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";

          $this->salida .= "	<br><table align=\"center\" width=\"50%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class='modulo_list_table'\n>";
          $this->salida .= "		<tr class='modulo_table_list_title'>\n";
          $this->salida .= "			<td width=\"2%\" >TIPO DE CONSULTA</td>\n";
          $this->salida .= "		</tr>\n";
          $this->salida .= "		<tr class='modulo_list_claro'>\n";
          $this->salida .= "			<td width=\"2%\"  align=\"center\" >\n";

          $this->salida.="<select name='Seleccion' class='select'>";
          $this->salida.="	<option value='Medicamentos'>MEDICAMENTOS</option>";
          $this->salida.="	<option value='Insumos'>INSUMOS</option>";
          $this->salida.="	<option value='Devoluciones'>SOLICITUDES DEVOLUCION</option>";
          $this->salida.="</select>";
          
          $this->salida .= "			</td>\n";
          $this->salida .= "		</tr>\n";
          
          $this->salida.=" <tr class='modulo_list_oscuro'>";
          $this->salida.=" <td align=\"center\">";
          $this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"BUSCAR\"></form>";
          $this->salida.=" </td>";
          $this->salida.=" </tr>\n";
          $this->salida.="</table><br>";
          
          //DATOS DEL PIE DE PAGINA
          if ($_SESSION['Interna'] == true)
          	$this->FrmPieDePagina($datosPaciente,$datos_estacion);
          else
          	$this->FrmPieDePagina();
          return true;
	}

     
     /**
     *	Pantalla de modo consulta de los medicamentos que estan pendientes
     *	por aceptacion de devolucion o despacho
     *
	*	@Tizziano Perea O.
	*	@access Private
	*	@param array datos de la estacion
	*/
     function Frm_ConsultaEstadoMedicamentos($datos_estacion,$datosPaciente)
     {
          $this->salida .= ThemeAbrirTabla("MEDICAMENTOS");
          $this->salida .= "<br><table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_title\">\n";
          $this->salida .= "    <tr class=\"modulo_table_title\">\n";
          $this->salida .= "        <td>HABITACIÓN</td>\n";
          $this->salida .= "        <td>CAMA</td>\n";
          $this->salida .= "        <td>PACIENTE</td>\n";
          $this->salida .= "        <td>IDENTIFICACIÓN</td>\n";
          $this->salida .= "        <td>CUENTA</td>\n";
          $this->salida .= "        <td>INGRESO</td>\n";
          $this->salida .= "        </tr>\n";
          $this->salida .= "        <tr align='center' class='modulo_list_oscuro'>\n";
          $this->salida .= "            <td>".$datosPaciente[pieza]."</td>\n";
          $this->salida .= "            <td>".$datosPaciente[cama]."</td>\n";
          $this->salida .= "            <td>".$datosPaciente[nombre_completo]."</td>\n";
          $this->salida .= "            <td>".$datosPaciente[tipo_id_paciente]." ".$datosPaciente[paciente_id]."</td>\n";
          $this->salida .= "            <td>".$datosPaciente[numerodecuenta]."</td>\n";
          $this->salida .= "            <td>".$datosPaciente[ingreso]."</td>\n";
          $this->salida .= "        </tr>\n";
          $this->salida .= "    </table>\n";
          
          $this->salida .= " <br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
          $this->salida .= $this->SetStyle("MensajeError");
          $this->salida .= " </table>";
          
          $solicitudes = $this->SolicitudesMedicamentos($datosPaciente[ingreso], $datos_estacion);
		
          $despachos = $this->DespachoMedicamentosForaneos($datosPaciente[ingreso], $datos_estacion);
          if($solicitudes OR $despachos)
          {
               if($solicitudes)
               {
                    $this->salida .= "       <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
                    $this->salida .= "        <tr class=\"modulo_table_list_title\">";
                    $this->salida .= "          <td colspan=\"5\" nowrap align=\"center\"><b>SOLICITUDES PENDIENTES POR DESPACHO</b></td>";
                    $this->salida .= "        </tr>";
                    
                    $this->salida .= "        <tr class=\"modulo_table_list_title\">";
                    $this->salida .= "          <td width=\"30%\" nowrap align=\"center\"><b>DEPARTAMENTO</b></td>";
                    $this->salida .= "          <td width=\"30%\" nowrap align=\"center\"><b>ESTACION SOLICITANTE</b></td>";
                    $this->salida .= "          <td width=\"20%\" nowrap align=\"center\"><b>FECHA</b></td>";
                    $this->salida .= "          <td width=\"14%\" nowrap align=\"center\"><b>CODIGO</b></td>";
                    $this->salida .= "          <td width=\"11%\" nowrap align=\"center\"></td>";
                    
                    $y=0;
               
                    foreach($solicitudes as $departamento => $vector)
                    {
                         if($y % 2){$estilo='modulo_list_claro'; $estilo1='modulo_list_oscuro';}else{$estilo='modulo_list_oscuro';$estilo1='modulo_list_claro';}
                    
                         $this->salida .= "	 <tr class=\"$estilo\">\n";
                         (list($dpto,$descripcionDpto)=explode('-',$departamento));
                         $this->salida .= "	 <td>";
                         $this->salida .= "	 <table width=\"100%\" border=\"0\">";
                         $this->salida .= "	  <tr class=\"$estilo\"><td>$descripcionDpto</td></tr>";
                         $this->salida .= "	 </table>";
                         $this->salida .= "	 </td>";
                         $this->salida .= "	 <td colspan=\"4\">";
                         $this->salida .= "       <table border=\"0\" width=\"100%\" align=\"center\" class=\"normal_10\">";
                         foreach($vector as $solicitudId => $Datos)
                         {
                              $NombreEstacion=$this->NombreEstacion($Datos['estacion_id']);
                              $this->salida .= "	 <tr class=\"$estilo1\">\n";
                              $this->salida .= "	 <td width=\"43%\">".$NombreEstacion['descripcion']."</td>";
                              (list($fecha,$HoraTot)=explode(' ',$Datos['fecha_solicitud']));
                              (list($ano,$mes,$dia)=explode('-',$fecha));
                              (list($hora,$min)=explode(':',$HoraTot));
                              $this->salida .= "	 <td width=\"30%\">".strftime('%b %d de %Y  %H:%M',mktime($hora,$min,0,$mes,$dia,$ano))."</td>";
                              $this->salida .= "	 <td width=\"20%\">".$Datos['solicitud_id']."</td>";
                              $action=ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','DetalleSolicitudMedicamento',array("EstacionId"=>$Datos['estacion_id'],"NombreEstacion"=>$NombreEstacion['descripcion'],"SolicitudId"=>$Datos['solicitud_id'],"Fecha"=>$Datos['fecha_solicitud'],"Ingreso"=>$Datos['ingreso'],"usuarioestacion"=>$Datos['usuario_id'].' '.$Datos['usuarioestacion'],"nombrepac"=>$Datos['nombrepac'],"tipo_id_paciente"=>$Datos['tipo_id_paciente'],"paciente_id"=>$Datos['paciente_id'],'datos_estacion'=>$datos_estacion,'datosPaciente'=>$datosPaciente));
                              $this->salida .= "	 <td align=\"center\" width=\"15%\"><a href=\"$action\" class=\"link\"><img title=\"Ver Detalle\" border=\"0\" src=\"".GetThemePath()."/images/pconsultar.png\"></td>";
                              $this->salida .= "	 </tr>";
                         }
                         $this->salida .= "       </table>";
                         $this->salida .= "	 </td>";
                         $this->salida .= "	 </tr>";
                         $y++;
                    }
                    $this->salida .= "   </table><BR>";
               }
               
               if($despachos)
               {
                    $this->salida .= "       <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
                    $this->salida .= "        <tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida .= "          <td colspan=\"5\" nowrap align=\"center\"><b>SOLICITUDES DESPACHADAS</b></td>";
                    $this->salida .= "        </tr>";
                    
                    $this->salida .= "        <tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida .= "          <td width=\"30%\" nowrap align=\"center\"><b>DEPARTAMENTO</b></td>";
                    $this->salida .= "          <td width=\"30%\" nowrap align=\"center\"><b>ESTACION SOLICITANTE</b></td>";
                    $this->salida .= "          <td width=\"20%\" nowrap align=\"center\"><b>FECHA</b></td>";
                    $this->salida .= "          <td width=\"14%\" nowrap align=\"center\"><b>CODIGO</b></td>";
                    $this->salida .= "          <td width=\"11%\" nowrap align=\"center\"></td>";
                    
                    $y=0;
               
                    foreach($despachos as $departamento => $vector)
                    {
                         if($y % 2){$estilo='modulo_list_claro'; $estilo1='modulo_list_oscuro';}else{$estilo='modulo_list_oscuro';$estilo1='modulo_list_claro';}
                    
                         $this->salida .= "	 <tr class=\"$estilo\">\n";
                         (list($dpto,$descripcionDpto)=explode('-',$departamento));
                         $this->salida .= "	 <td>";
                         $this->salida .= "	 <table width=\"100%\" border=\"0\">";
                         $this->salida .= "	  <tr class=\"$estilo\"><td>$descripcionDpto</td></tr>";
                         $this->salida .= "	 </table>";
                         $this->salida .= "	 </td>";
                         $this->salida .= "	 <td colspan=\"4\">";
                         $this->salida .= "       <table border=\"0\" width=\"100%\" align=\"center\" class=\"normal_10\">";
                         foreach($vector as $solicitudId => $Datos)
                         {
                              $NombreEstacion=$this->NombreEstacion($Datos['estacion_id']);
                              $this->salida .= "	 <tr class=\"$estilo1\">\n";
                              $this->salida .= "	 <td width=\"43%\">".$NombreEstacion['descripcion']."</td>";
                              (list($fecha,$HoraTot)=explode(' ',$Datos['fecha_solicitud']));
                              (list($ano,$mes,$dia)=explode('-',$fecha));
                              (list($hora,$min)=explode(':',$HoraTot));
                              $this->salida .= "	 <td width=\"30%\">".strftime('%b %d de %Y  %H:%M',mktime($hora,$min,0,$mes,$dia,$ano))."</td>";
                              $this->salida .= "	 <td width=\"20%\">".$Datos['solicitud_id']."</td>";
                              $action=ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','DetalleSolicitudMedicamento',array("EstacionId"=>$Datos['estacion_id'],"NombreEstacion"=>$NombreEstacion['descripcion'],"SolicitudId"=>$Datos['solicitud_id'],"Fecha"=>$Datos['fecha_solicitud'],"Ingreso"=>$Datos['ingreso'],"usuarioestacion"=>$Datos['usuario_id'].' '.$Datos['usuarioestacion'],"nombrepac"=>$Datos['nombrepac'],"tipo_id_paciente"=>$Datos['tipo_id_paciente'],"paciente_id"=>$Datos['paciente_id'],'datos_estacion'=>$datos_estacion,'datosPaciente'=>$datosPaciente,'estado'=>'despachado'));
                              $this->salida .= "	 <td align=\"center\" width=\"15%\"><a href=\"$action\" class=\"link\"><img title=\"Ver Detalle\" border=\"0\" src=\"".GetThemePath()."/images/pconsultar.png\"></td>";
                              $this->salida .= "	 </tr>";
                         }
                         $this->salida .= "       </table>";
                         $this->salida .= "	 </td>";
                         $this->salida .= "	 </tr>";
                         $y++;
                    }
                    $this->salida .= "   </table><BR>";      
          	}
          }
          else
          {
               $this->salida .= "<div class='label_mark' align='center'><br>NO HAY DESPACHOS PENDIENTES DE MEDICAMENTOS<br>";
          }
          
          $href2 = ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','Frm_SelectOption',array("datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente));
          $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href2."'>PANTALLA DE SELECCION</a><br>";
                    
          //DATOS DEL PIE DE PAGINA
          if ($_SESSION['Interna'] == true)
          	$this->FrmPieDePagina($datosPaciente,$datos_estacion);
          else
          	$this->FrmPieDePagina();     	
          return true;     
     }
     
     
     /**
     *	Pantalla de modo consulta de los insumos que estan pendientes
     *	por aceptacion de devolucion o despacho
     *
	*	@Tizziano Perea O.
	*	@access Private
	*	@param array datos de la estacion
	*/
     function Frm_ConsultaEstadoInsumos($datos_estacion,$datosPaciente)
     {
          $this->salida .= ThemeAbrirTabla("INSUMOS");
          
          $this->salida .= "<br><table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_title\">\n";
          $this->salida .= "    <tr class=\"modulo_table_title\">\n";
          $this->salida .= "        <td>HABITACIÓN</td>\n";
          $this->salida .= "        <td>CAMA</td>\n";
          $this->salida .= "        <td>PACIENTE</td>\n";
          $this->salida .= "        <td>IDENTIFICACIÓN</td>\n";
          $this->salida .= "        <td>CUENTA</td>\n";
          $this->salida .= "        <td>INGRESO</td>\n";
          $this->salida .= "        </tr>\n";
          $this->salida .= "        <tr align='center' class='modulo_list_oscuro'>\n";
          $this->salida .= "            <td>".$datosPaciente[pieza]."</td>\n";
          $this->salida .= "            <td>".$datosPaciente[cama]."</td>\n";
          $this->salida .= "            <td>".$datosPaciente[nombre_completo]."</td>\n";
          $this->salida .= "            <td>".$datosPaciente[tipo_id_paciente]." ".$datosPaciente[paciente_id]."</td>\n";
          $this->salida .= "            <td>".$datosPaciente[numerodecuenta]."</td>\n";
          $this->salida .= "            <td>".$datosPaciente[ingreso]."</td>\n";
          $this->salida .= "        </tr>\n";
          $this->salida .= "    </table>\n";
          
          
          $this->salida .= " <br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
          $this->salida .= $this->SetStyle("MensajeError");
          $this->salida .= " </table>";
         
          $solicitudes = $this->SolicitudesInsumos($datosPaciente[ingreso], $datos_estacion);
          
          $despachos = $this->DespachoInsumosForaneos($datosPaciente[ingreso], $datos_estacion);
		
          if($solicitudes OR $despachos)
          {
               if($solicitudes)
               {
                    $this->salida .= "       <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
                    $this->salida .= "        <tr class=\"modulo_table_list_title\">";
                    $this->salida .= "          <td colspan=\"5\" nowrap align=\"center\"><b>SOLICITUDES PENDIENTES POR DESPACHO</b></td>";
                    $this->salida .= "        </tr>";
                    
                    $this->salida .= "        <tr class=\"modulo_table_list_title\">";
                    $this->salida .= "          <td width=\"30%\" nowrap align=\"center\"><b>DEPARTAMENTO</b></td>";
                    $this->salida .= "          <td width=\"30%\" nowrap align=\"center\"><b>ESTACION SOLICITANTE</b></td>";
                    $this->salida .= "          <td width=\"20%\" nowrap align=\"center\"><b>FECHA</b></td>";
                    $this->salida .= "          <td width=\"14%\" nowrap align=\"center\"><b>CODIGO</b></td>";
                    $this->salida .= "          <td width=\"11%\" nowrap align=\"center\"></td>";
                    
                    $y=0;
                    foreach($solicitudes as $departamento => $vector)
                    {
                         if($y % 2){$estilo='modulo_list_claro'; $estilo1='modulo_list_oscuro';}else{$estilo='modulo_list_oscuro';$estilo1='modulo_list_claro';}
                    
                         $this->salida .= "	 <tr class=\"$estilo\">\n";
                         (list($dpto,$descripcionDpto)=explode('-',$departamento));
                         $this->salida .= "	 <td>";
                         $this->salida .= "	 <table width=\"100%\" border=\"0\">";
                         $this->salida .= "	  <tr class=\"$estilo\"><td>$descripcionDpto</td></tr>";
                         $this->salida .= "	 </table>";
                         $this->salida .= "	 </td>";
                         $this->salida .= "	 <td colspan=\"4\">";
                         $this->salida .= "       <table border=\"0\" width=\"100%\" align=\"center\" class=\"normal_10\">";
                         foreach($vector as $solicitudId => $Datos)
                         {
                              $NombreEstacion=$this->NombreEstacion($Datos['estacion_id']);
                              $this->salida .= "	 <tr class=\"$estilo1\">\n";
                              $this->salida .= "	 <td width=\"43%\">".$NombreEstacion['descripcion']."</td>";
                              (list($fecha,$HoraTot)=explode(' ',$Datos['fecha_solicitud']));
                              (list($ano,$mes,$dia)=explode('-',$fecha));
                              (list($hora,$min)=explode(':',$HoraTot));
                              $this->salida .= "	 <td width=\"30%\">".strftime('%b %d de %Y  %H:%M',mktime($hora,$min,0,$mes,$dia,$ano))."</td>";
                              $this->salida .= "	 <td width=\"20%\">".$Datos['solicitud_id']."</td>";
                              $action=ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','DetalleSolicitudMedicamento',array("EstacionId"=>$Datos['estacion_id'],"NombreEstacion"=>$NombreEstacion['descripcion'],"SolicitudId"=>$Datos['solicitud_id'],"Fecha"=>$Datos['fecha_solicitud'],"Ingreso"=>$Datos['ingreso'],"usuarioestacion"=>$Datos['usuario_id'].' '.$Datos['usuarioestacion'],"nombrepac"=>$Datos['nombrepac'],"tipo_id_paciente"=>$Datos['tipo_id_paciente'],"paciente_id"=>$Datos['paciente_id'],'datos_estacion'=>$datos_estacion,'datosPaciente'=>$datosPaciente));
                              $this->salida .= "	 <td align=\"center\" width=\"15%\"><a href=\"$action\" class=\"link\"><img title=\"Ver Detalle\" border=\"0\" src=\"".GetThemePath()."/images/pconsultar.png\"></td>";
                              $this->salida .= "	 </tr>";
                         }
                         $this->salida .= "       </table>";
                         $this->salida .= "	 </td>";
                         $this->salida .= "	 </tr>";
                         $y++;
                    }
                    $this->salida .= "   </table><BR>";
               }
               
               if($despachos)
               {
                    $this->salida .= "       <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
                    $this->salida .= "        <tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida .= "          <td colspan=\"5\" nowrap align=\"center\"><b>SOLICITUDES DESPACHADAS</b></td>";
                    $this->salida .= "        </tr>";
                    
                    $this->salida .= "        <tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida .= "          <td width=\"30%\" nowrap align=\"center\"><b>DEPARTAMENTO</b></td>";
                    $this->salida .= "          <td width=\"30%\" nowrap align=\"center\"><b>ESTACION SOLICITANTE</b></td>";
                    $this->salida .= "          <td width=\"20%\" nowrap align=\"center\"><b>FECHA</b></td>";
                    $this->salida .= "          <td width=\"14%\" nowrap align=\"center\"><b>CODIGO</b></td>";
                    $this->salida .= "          <td width=\"11%\" nowrap align=\"center\"></td>";
                    
                    $y=0;
                    foreach($despachos as $departamento => $vector)
                    {
                         if($y % 2){$estilo='modulo_list_claro'; $estilo1='modulo_list_oscuro';}else{$estilo='modulo_list_oscuro';$estilo1='modulo_list_claro';}
                    
                         $this->salida .= "	 <tr class=\"$estilo\">\n";
                         (list($dpto,$descripcionDpto)=explode('-',$departamento));
                         $this->salida .= "	 <td>";
                         $this->salida .= "	 <table width=\"100%\" border=\"0\">";
                         $this->salida .= "	  <tr class=\"$estilo\"><td>$descripcionDpto</td></tr>";
                         $this->salida .= "	 </table>";
                         $this->salida .= "	 </td>";
                         $this->salida .= "	 <td colspan=\"4\">";
                         $this->salida .= "       <table border=\"0\" width=\"100%\" align=\"center\" class=\"normal_10\">";
                         foreach($vector as $solicitudId => $Datos)
                         {
                              $NombreEstacion=$this->NombreEstacion($Datos['estacion_id']);
                              $this->salida .= "	 <tr class=\"$estilo1\">\n";
                              $this->salida .= "	 <td width=\"43%\">".$NombreEstacion['descripcion']."</td>";
                              (list($fecha,$HoraTot)=explode(' ',$Datos['fecha_solicitud']));
                              (list($ano,$mes,$dia)=explode('-',$fecha));
                              (list($hora,$min)=explode(':',$HoraTot));
                              $this->salida .= "	 <td width=\"30%\">".strftime('%b %d de %Y  %H:%M',mktime($hora,$min,0,$mes,$dia,$ano))."</td>";
                              $this->salida .= "	 <td width=\"20%\">".$Datos['solicitud_id']."</td>";
                              $action=ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','DetalleSolicitudMedicamento',array("EstacionId"=>$Datos['estacion_id'],"NombreEstacion"=>$NombreEstacion['descripcion'],"SolicitudId"=>$Datos['solicitud_id'],"Fecha"=>$Datos['fecha_solicitud'],"Ingreso"=>$Datos['ingreso'],"usuarioestacion"=>$Datos['usuario_id'].' '.$Datos['usuarioestacion'],"nombrepac"=>$Datos['nombrepac'],"tipo_id_paciente"=>$Datos['tipo_id_paciente'],"paciente_id"=>$Datos['paciente_id'],'datos_estacion'=>$datos_estacion,'datosPaciente'=>$datosPaciente,'estado'=>'despachado'));
                              $this->salida .= "	 <td align=\"center\" width=\"15%\"><a href=\"$action\" class=\"link\"><img title=\"Ver Detalle\" border=\"0\" src=\"".GetThemePath()."/images/pconsultar.png\"></td>";
                              $this->salida .= "	 </tr>";
                         }
                         $this->salida .= "       </table>";
                         $this->salida .= "	 </td>";
                         $this->salida .= "	 </tr>";
                         $y++;
                    }
                    $this->salida .= "   </table><BR>";
               }
          }
          else
          {
               $this->salida .= "<div class='label_mark' align='center'><br>NO HAY DESPACHOS PENDIENTES DE INSUMOS<br>";
          }

           
          $href2 = ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','Frm_SelectOption',array("datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente));
          $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href2."'>PANTALLA DE SELECCION</a><br>";
                    
          //DATOS DEL PIE DE PAGINA
          if ($_SESSION['Interna'] == true)
          	$this->FrmPieDePagina($datosPaciente,$datos_estacion);
          else
          	$this->FrmPieDePagina();     	
          return true;     
     }
     
     function Frm_ConsultaSolicitudesDevolucion($datos_estacion,$datosPaciente)
     {
          $this->salida .= ThemeAbrirTabla("SOLICITUDES DE DEVOLUCION");
          
          $this->salida .= "<br><table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_title\">\n";
          $this->salida .= "    <tr class=\"modulo_table_title\">\n";
          $this->salida .= "        <td>HABITACIÓN</td>\n";
          $this->salida .= "        <td>CAMA</td>\n";
          $this->salida .= "        <td>PACIENTE</td>\n";
          $this->salida .= "        <td>IDENTIFICACIÓN</td>\n";
          $this->salida .= "        <td>CUENTA</td>\n";
          $this->salida .= "        <td>INGRESO</td>\n";
          $this->salida .= "        </tr>\n";
          $this->salida .= "        <tr align='center' class='modulo_list_oscuro'>\n";
          $this->salida .= "            <td>".$datosPaciente[pieza]."</td>\n";
          $this->salida .= "            <td>".$datosPaciente[cama]."</td>\n";
          $this->salida .= "            <td>".$datosPaciente[nombre_completo]."</td>\n";
          $this->salida .= "            <td>".$datosPaciente[tipo_id_paciente]." ".$datosPaciente[paciente_id]."</td>\n";
          $this->salida .= "            <td>".$datosPaciente[numerodecuenta]."</td>\n";
          $this->salida .= "            <td>".$datosPaciente[ingreso]."</td>\n";
          $this->salida .= "        </tr>\n";
          $this->salida .= "    </table>\n";
          
          
          $this->salida .= " <br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
          $this->salida .= $this->SetStyle("MensajeError");
          $this->salida .= " </table>";
          
          $devoluciones=$this->DevolucionesMedicamentos($datosPaciente[ingreso], $datos_estacion);
     	if($devoluciones)
          {
      		$this->salida .= "       <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "        <tr class=\"modulo_table_list_title\">";
			$this->salida .= "          <td colspan=\"5\" nowrap align=\"center\"><b>SOLICITUDES PENDIENTES POR DEVOLUCION</b></td>";
			$this->salida .= "        </tr>";
			
               $this->salida .= "        <tr class=\"modulo_table_list_title\">";
			$this->salida .= "          <td width=\"30%\" nowrap align=\"center\"><b>DEPARTAMENTO</b></td>";
			$this->salida .= "          <td width=\"30%\" nowrap align=\"center\"><b>ESTACION SOLICITANTE</b></td>";
			$this->salida .= "          <td width=\"20%\" nowrap align=\"center\"><b>FECHA</b></td>";
			$this->salida .= "          <td width=\"14%\" nowrap align=\"center\"><b>CODIGO</b></td>";
			$this->salida .= "          <td width=\"11%\" nowrap align=\"center\"></td>";
			
               $y=0;
               foreach($devoluciones as $departamento => $vector)
               {
        			if($y % 2){$estilo='modulo_list_claro'; $estilo1='modulo_list_oscuro';}else{$estilo='modulo_list_oscuro';$estilo1='modulo_list_claro';}
               
                    $this->salida .= "	 <tr class=\"$estilo\">\n";
				(list($dpto,$descripcionDpto)=explode('-',$departamento));
				$this->salida .= "	 <td>";
				$this->salida .= "	 <table width=\"100%\" border=\"0\">";
				$this->salida .= "	  <tr class=\"$estilo\"><td>$descripcionDpto</td></tr>";
				$this->salida .= "	 </table>";
				$this->salida .= "	 </td>";
				$this->salida .= "	 <td colspan=\"4\">";
				$this->salida .= "       <table border=\"0\" width=\"100%\" align=\"center\" class=\"normal_10\">";
                    foreach($vector as $solicitudId => $Datos)
                    {
                         $NombreEstacion=$this->NombreEstacion($Datos['estacion_id']);
					$this->salida .= "	 <tr class=\"$estilo1\">\n";
					$this->salida .= "	 <td width=\"43%\">".$NombreEstacion['descripcion']."</td>";
					(list($fecha,$HoraTot)=explode(' ',$Datos['fecha_solicitud']));
					(list($ano,$mes,$dia)=explode('-',$fecha));
					(list($hora,$min)=explode(':',$HoraTot));
					$this->salida .= "	 <td width=\"30%\">".strftime('%b %d de %Y  %H:%M',mktime($hora,$min,0,$mes,$dia,$ano))."</td>";
					$this->salida .= "	 <td width=\"20%\">".$Datos['documento']."</td>";
					$action=ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','DetalleDevolucionMedicamentos',array("EstacionId"=>$Datos['estacion_id'],"NombreEstacion"=>$NombreEstacion['descripcion'],"Documento"=>$Datos['documento'],"Fecha"=>$Datos['fecha'],"Ingreso"=>$Datos['ingreso'],"observacion"=>$Datos['observacion'],"identificacion"=>$identificacion,"nombrepac"=>$Datos['nombrepac'],'datos_estacion'=>$datos_estacion,'datosPaciente'=>$datosPaciente,"observaciones"=>$Datos['observacion'],"parametro"=>$Datos['parametro'],'bodega_s'=>$Datos['bodega']));
					$this->salida .= "	 <td align=\"center\" width=\"15%\"><a href=\"$action\" class=\"link\"><img title=\"Ver Detalle\" border=\"0\" src=\"".GetThemePath()."/images/pconsultar.png\"></td>";
					$this->salida .= "	 </tr>";
				}
				$this->salida .= "       </table>";
     			$this->salida .= "	 </td>";
				$this->salida .= "	 </tr>";
				$y++;
			}
			$this->salida .= "   </table><BR>";
		}
          else
          {
               $this->salida .= "<div class='label_mark' align='center'><br>NO HAY SOLICITUDES DE DEVOLUCION<br>";
          }
          
          $href2 = ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','Frm_SelectOption',array("datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente));
          $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href2."'>PANTALLA DE SELECCION</a><br>";
                    
          //DATOS DEL PIE DE PAGINA
          if ($_SESSION['Interna'] == true)
          	$this->FrmPieDePagina($datosPaciente,$datos_estacion);
          else
          	$this->FrmPieDePagina();     	
          return true;     
     }

     
     /**
     * La funcion que visualiza el detalle de la solicitud de un paciente
     * @return boolean
     * @param array datos de al solicitud de despacho de medicamentos o insumos
     * @param array datos de ubicacion de la bodega a la que se hizo la solicitud
     * @param string nombre de la empresa en la que se esta trabajando
     * @param string nombre del centro de utilidad en el que se esta trabajando
     * @param string nombre de la bodega en la que se esta trabajando
     * @param string nombre de la estacion que hace la solicitud
     * @param adte fecha de realizacion de la solicitud
     */

	function FrmAtenderSolicitudPaciente($SolicitudId, $Ingreso, $EstacionId, $NombreEstacion, $Fecha, $usuarioestacion, $nombrepac, $tipo_id_paciente, $paciente_id, $datos_estacion, $datosPaciente, $estado)
     {
          $tipoSolicitud=$this->GetTipoSolicitudBodega($SolicitudId,$datos_estacion['empresa_id']);
          $nom_bodega=$this->TraerNombreBodega($datos_estacion,$tipoSolicitud['bodega']);
          
          // Cargamos variable con valor de la solicitud.
          $matriz = array();
          $matriz['0'] = $SolicitudId;
          
          if($tipoSolicitud['tipo_solicitud']=='M')
          {
			$Vector  = $this->GetMedicamentosSolicitud($SolicitudId,$datos_estacion['empresa_id']);
		  	$palabra = 'MEDICAMENTOS';
               $retorno = 1;
		}elseif($tipoSolicitud['tipo_solicitud']=='Z')
          {
			$Vector = $this->GetMezclasSolicitud($SolicitudId,$datos_estacion['empresa_id']);
		  	$palabra='MEZCLAS';
		}elseif($tipoSolicitud['tipo_solicitud']=='I')
          {
			$Vector = $this->GetInsumosSolicitud($SolicitudId,$datos_estacion['empresa_id']);
			$palabra='INSUMOS';
               $retorno = 2;
		}

          if(!$Vector)
          {
			$mensaje = "NO SE ENCONTRARON MEDICAMENTOS EN LA SOLICITUD SELECCIONADA";
			$titulo = "DETALLE DOCUMENTO BODEGA";
			if($retorno == 1)
               {
               	$accion = ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','Call_RutaProductos',array('Seleccion'=>'Medicamentos',"datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente));
               }elseif($retorno == 2)
               {
               	$accion = ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','Call_RutaProductos',array('Seleccion'=>'Insumos',"datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente));
               }
			$boton = "REGRESAR";
			$this->frmMSG($accion,$titulo,$mensaje,$boton);
			return true;
		}else
          {
			//ordenar por solicitud
			foreach($Vector as $key=>$value)
               {
				$datosOrdenados[$value[solicitud_id]][$key] = $value;
          	}
               
               $this->salida .= themeAbrirTabla('SOLICITUDES DE '.' '.$palabra);
               
               if($tipoSolicitud['tipo_solicitud']=='M')
          		$f = ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','InsertDespSolicitudMed',array("plan"=>$datosPaciente['plan_id'], "cuenta"=>$datosPaciente['numerodecuenta'], "datos_estacion"=>$datos_estacion, "bodega"=>$tipoSolicitud['bodega'], "matriz"=>$matriz, "retorno"=>$tipoSolicitud['tipo_solicitud'], "datosPaciente"=>$datosPaciente));
               else
                    $f = ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','InsertDespSolicitudMed',array("plan"=>$datosPaciente['plan_id'], "cuenta"=>$datosPaciente['numerodecuenta'], "datos_estacion"=>$datos_estacion, "bodega"=>$tipoSolicitud['bodega'], "matriz"=>$matriz, "retorno"=>$tipoSolicitud['tipo_solicitud'], "datosPaciente"=>$datosPaciente));
               
               $this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";
               $this->salida .= "       <table class=\"normal_10\" border=\"0\" width=\"80%\" align=\"center\">";
			$this->salida .= "        <tr><td width=\"100%\">";
			$this->salida .= "        <fieldset><legend class=\"field\">DOCUMENTO SOLICITUD MEDICAMENTO</legend>";
			$this->salida .= "          <table class=\"normal_10\"cellspacing=\"2\" cellpadding=\"3\"border=\"0\"  width=\"95%\" align=\"center\">";
			$this->salida .= "	          <tr><td></td></tr>";
			$this->salida .= "	          <tr class=\"modulo_list_claro\">";
			$this->salida .= "	          <td width=\"20%\"><label class=\"label\">SOLICITANTE</td>";
			$this->salida .= "	          <td colspan=\"3\">$NombreEstacion</td>";
			$this->salida .= "	          </tr>";
			$this->salida .= "	          <tr class=\"modulo_list_oscuro\">";
			$this->salida .= "	          <td width=\"20%\"><label class=\"label\">BODEGA</td>";
     		$this->salida .= "            <td width=\"45%\">".$nom_bodega."</td>";
               $this->salida .= "	          <td width=\"20%\"><label class=\"label\">CODIGO SOLICITUD</td>";
			$this->salida .= "	          <td width=\"15%\">$SolicitudId</td>";
			$this->salida .= "	          </tr>";
			$this->salida .= "	          <tr class=\"modulo_list_oscuro\">";
			$this->salida .= "	          <td width=\"20%\"><label class=\"label\">PACIENTE</td>";
			$this->salida .= "	          <td width=\"45%\">$tipo_id_paciente - $paciente_id  $nombrepac</td>";
			$this->salida .= "	          <td width=\"20%\"><label class=\"label\">FECHA SOLICITUD</td>";
               
               (list($fecha,$HoraTot)=explode(' ',$Fecha));
               (list($ano,$mes,$dia)=explode('-',$fecha));
               (list($hora,$min)=explode(':',$HoraTot));

               $this->salida .= "	          <td width=\"15%\" align=\"center\">".strftime('%b %d de %Y  %H:%M',mktime($hora,$min,0,$mes,$dia,$ano))."</td>";
			$this->salida .= "	          </tr>";
			$this->salida .= "	          <tr><td></td></tr>";
			$this->salida .= "			    </table>";
			$this->salida .= "		     </fieldset></td><BR>";
			$this->salida .= "       </table><BR><BR>";
			$this->salida .= "			<table width=\"85%\" class=\"modulo_table_list\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
			$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
			if($tipoSolicitud['tipo_solicitud']=='Z')
               {
                    if($ValueMed[mezcla_recetada_id])
                    {
                         $this->salida .= "<td>MEZCLA</td>\n";
                    }
			}
               // Falta la funcionalidad para las mezclas.
			$this->salida .= "<td>MEDICAMENTO</td>\n";
			if(!$estado)
               { $this->salida .= "<td>CANT SOLICITADA</td>\n"; }
               else
               { $this->salida .= "<td>CANT DESPACHADA</td>\n";
			     $this->salida .= "<td>FECHA VENCIEMIENTO</td>\n";
				 $this->salida .= "<td>LOTE</td>\n";
				 $this->salida .= "<td>EXISTENCIA</td>\n";		
			   }
			$this->salida .= "				</tr>\n";
			$l = $i = 0;
			foreach($datosOrdenados as $key=>$value)
               {
				$contadorRowSpan = sizeof($value);
				foreach($value as $keyMed => $ValueMed)
                    {
                    	if(($l++) % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
					$this->salida .= "				<tr align=\"center\" class=\"$estilo\">\n";
                         $this->salida .= "					<td>".$ValueMed[medicamento_id]." => ".$ValueMed[nommedicamento]." ".$ValueMed[ff]."</td>\n";
                         if(!$estado)
                         {                         
						$this->salida .= "					<td>".$ValueMed[cant_solicitada]."</td>\n";
                         }else
                         {
                              if($tipoSolicitud['tipo_solicitud'] == 'M')
                              { $despacho = $this->GetDatosDespacho($ValueMed[documento_despacho],$ValueMed[consecutivo_d]); }
                              else
                              { $despacho = $this->GetDatosDespachoIns($ValueMed[documento_despacho],$ValueMed[consecutivo_d]); }
                              
                              $cant_desp = floor($despacho[0][cantidad]);
							  $existencia = floor($despacho[0][existencia_actual]);
                              if($cant_desp <= 0){ $cant_desp = "<label class=\"label_mark\">No Despachado</label>"; }
                              
                              $this->salida .= "					<td>".$cant_desp."</td>\n";
								
							  if($cant_desp > $existencia)
							  {
								$this->salida .= "					<td class=\"label_error\">".$despacho[0][fecha_vencimiento]."</td>\n";
								$this->salida .= "					<td class=\"label_error\">".$despacho[0][lote]."</td>\n";
								$this->salida .= "					<td class=\"label_error\">".$existencia."</td>\n";
							  }
							  else
							  {
								$this->salida .= "					<td>".$despacho[0][fecha_vencimiento]."</td>\n";
								$this->salida .= "					<td>".$despacho[0][lote]."</td>\n";
								$this->salida .= "					<td>".$existencia."</td>\n";
							  }
							  
                         }
					$this->salida .= "				</tr>\n";
				}
			}
               if($estado)
               {
                    $this->salida .= "<tr align=\"center\" class=\"$estilo\">\n";
                    $this->salida .= "<td align=\"center\">&nbsp;</td>";
					$this->salida .= "<td align=\"center\">&nbsp;</td>";
					$this->salida .= "<td align=\"center\">&nbsp;</td>";
					$this->salida .= "<td align=\"center\">&nbsp;</td>";
                    $this->salida .= "<td align=\"center\"><input type=\"submit\" class=\"input-submit\" name=\"confirmar\" value=\"Confirmar\"></td>";
                    $this->salida .= "</tr>\n";
               }
			$this->salida .= "	</table><BR>\n";
               $this->salida .= "</form>\n";

               
               if($retorno == 1)
               { $href2 = ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','Call_RutaProductos',array('Seleccion'=>'Medicamentos',"datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente));
               }elseif($retorno == 2)
               { $href2 = ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','Call_RutaProductos',array('Seleccion'=>'Insumos',"datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente)); }

               $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href2."'>VOLVER</a><br>";
               
			$this->salida .= themeCerrarTabla();
			return true;
		}
	}
     
     /**
     * La funcion que visualiza el detalle de una devolucion a la bodega
     * @return boolean
     * @param string empresa en la que se esta trabajando
     * @param string nombre de la empresa en la que se esta trabajando
     * @param string centro de utilidad en el que se esta trabajando
     * @param string nombre del centro de utilidad en el que se esta trabajando
     * @param string bodega en la que se esta trabajando
     * @param string nombre de la bodega en la que se esta trabajando
     * @param string codigo de la estacion que realizo la solicitud de la devolucion
     * @param string nombre de la estacion que realizo la solicitud de la devolucion
     * @param date fecha de realizacion de la devolucion
     * @param string codigo unico que identifica la solicitud de devolucion
     * @param string codigo del ingreso del paciente
     * @param string obeservaciones de la solicitud de devolucion
     */

	function FormaDetalleDevolucionMedicamentos($EstacionId,$NombreEstacion,$Fecha,$Documento,$Ingreso,$observaciones,$bandera,$codigoProducto,$descripcion,$Cantidad,$consecutivo,$identificacion,$nombrepac,$datos_estacion,$datosPaciente,$parametro,$bodega)
     {
          $this->salida .= themeAbrirTabla('PRODUCTOS DE LA DEVOLUCIONES DE MEDICAMENTOS E INSUMOS');
          $action = ModuloGetURL('app','InvBodegas','user','RealizarDevolucionMedicamentos');
          $this->salida .= "       <form name='Solicitud' action='$action' method='POST' onsubmit=\"return ValidaSolicitud(this);\">\n";
		$this->salida .= "       <table class=\"normal_10\" border=\"0\" width=\"70%\" align=\"center\">";
		$this->salida .= "        <tr><td width=\"100%\">";
		$this->salida .= "        <fieldset><legend class=\"field\">DOCUMENTO DEVOLUCION MEDICAMENTO</legend>";
		$this->salida .= "          <table class=\"normal_10\" cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
		$this->salida .= "	          <tr><td></td></tr>";
		$this->salida .= "	          <tr class=\"modulo_list_claro\">";
		$this->salida .= "	          <td width=\"15%\" ><label class=\"label\">PACIENTE</td>";
		$this->salida .= "	          <td>".$identificacion." ".$nombrepac."</td>";
		$this->salida .= "	          </tr>";
		$this->salida .= "	          <tr class=\"modulo_list_claro\">";
          $nom_bodega = $this->TraerNombreBodega($datos_estacion,$bodega);
		$this->salida .= "	          <td width=\"20%\" ><label class=\"label\">BODEGA</td>";
		$this->salida .= "	          <td>".$nom_bodega."</td>";
		$this->salida .= "	          </tr>";
		$this->salida .= "	          <tr class=\"modulo_list_claro\">";
		$this->salida .= "	          <td width=\"20%\" ><label class=\"label\">SOLICITANTE</td>";
		$this->salida .= "	          <td>$NombreEstacion</td>";
		$this->salida .= "	          </tr>";
		$this->salida .= "	          <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	          <td width=\"20%\"><label class=\"label\">FECHA SOLICITUD</td>";
          (list($fecha,$HoraTot)=explode(' ',$Fecha));
          (list($ano,$mes,$dia)=explode('-',$fecha));
          (list($hora,$min)=explode(':',$HoraTot));
          $this->salida .= "            <td>".strftime('%b %d de %Y  %H:%M',mktime($hora,$min,0,$mes,$dia,$ano))."</td>";		
		$this->salida .= "	          </tr>";
		$this->salida .= "	          <tr class=\"modulo_list_claro\">";
		$this->salida .= "	          <td width=\"20%\"><label class=\"label\">CODIGO</td>";
		$this->salida .= "	          <td>$Documento</td>";
		$this->salida .= "	          </tr>";
		$this->salida .= "	          <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	          <td width=\"20%\"><label class=\"label\">OBSERVACIONES</td>";
		$this->salida .= "	          <td>$observaciones</td>\n";
		$this->salida .= "	          </tr>";
		$this->salida .= "	          <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	          <td width=\"20%\"><label class=\"label\">PARAMETRO DE DEVOLUCION</td>";
		$this->salida .= "	          <td>$parametro</td>\n";
		$this->salida .= "	          </tr>";
		$this->salida .= "	          <tr><td></td></tr>";
		$this->salida .= "			    </table>";
		$this->salida .= "		     </fieldset></td><BR>";
		$this->salida .= "       </table><BR>";
		$this->salida .= "<table width=\"95%\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
		$this->salida .= "<tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "</td><tr>";
     	$this->salida .= "</table>";
		$ProductosDevolucion=$this->ProductosDevolucion($Documento, $datos_estacion);
		if($ProductosDevolucion)
          {
               $this->salida .= "	<table width=\"95%\" class=\"modulo_table_list\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
               $this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
               $this->salida .= "			<td width=\"10%\">CODIGO PRODUCTO</td>\n";
               $this->salida .= "			<td>NOMBRE</td>\n";
               $this->salida .= "			<td width=\"10%\">CANTIDAD DEVOLUCION</td>\n";
               $this->salida .= "		</tr>\n";
               $y=0;
               $z=0;
               for($i=0;$i<sizeof($ProductosDevolucion);$i++)
               {
               	if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
                    if($z % 2){$estilo1='modulo_list_oscuro';}else{$estilo1='modulo_list_claro';}
               	$this->salida .= "	 <tr class=\"$estilo\">\n";
                    $this->salida .= "	 <td>".$ProductosDevolucion[$i]['codigo_producto']."</td>";
                    $this->salida .= "	 <td>".$ProductosDevolucion[$i]['descripcion']."</td>";
                    $this->salida .= "	 <td>".$ProductosDevolucion[$i]['cantidad']."</td>";
               	$this->salida .= "	 </tr>\n";
                    $y++;
                    $z++;
               }
               $this->salida .= "			</table>";
		}
          
          $href2 = ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','Call_RutaProductos',array('Seleccion'=>'Devoluciones',"datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente));
          $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href2."'>VOLVER</a><br>";

		$this->salida .= ThemeCerrarTabla();
		return true;
	}
}//fin de la clase
?>