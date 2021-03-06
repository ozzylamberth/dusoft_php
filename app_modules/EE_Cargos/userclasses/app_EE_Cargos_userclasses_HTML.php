<?php

/**
 * $Id: app_EE_Cargos_userclasses_HTML.php,v 1.9 2007/11/28 15:58:31 jgomez Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author  Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
 * @package IPSOFT-SIIS
 */

class app_EE_Cargos_userclasses_HTML extends app_EE_Cargos_user
{
     /**
     * Constructor
     *
     * @return boolean
     */
     function app_EE_Cargos_user_HTML()
     {
          $this->app_EE_Cargos_user();
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
     function frmMSG($url='', $titulo='', $mensaje='', $link='', $VectorArgumentos)
     {
          if(empty($titulo))  $titulo  = $this->titulo;
          if(empty($mensaje)) $mensaje = "EL USUARIO NO TIENE PERMISOS EN ESTE MODULO.";
          if(empty($link)) $link = "VOLVER";
          $this->salida  = themeAbrirTabla($titulo);
          $this->salida .= "<div class='titulo3' align='center'><br><br><b>$mensaje</b>";
          if($VectorArgumentos['verificacion'] == true)
          {
               $bodega = $_SESSION['ESTANCIA'];
               unset($_SESSION['ESTANCIA']);
               
               $Documento = $_SESSION['RETORNO']['numeracion'];
               unset($_SESSION['RETORNO']['numeracion']);
               
               $reporte = new GetReports();
               $mostrar=$reporte->GetJavaReport('app','EE_Cargos','solicitud_insumos_cuentapaciente_html',array('Documento'=>$Documento,'tipo_id_paciente'=>$VectorArgumentos['argumentos']['TipoId'], 'paciente_id'=>$VectorArgumentos['argumentos']['PacienteId'], 'ingreso'=>$VectorArgumentos['argumentos']['Ingreso'], 'nivel'=>$VectorArgumentos['argumentos']['Nivel'], 'cuenta'=>$VectorArgumentos['argumentos']['Cuenta'], 'plan'=>$VectorArgumentos['argumentos']['PlanId'], 'bodega'=>$bodega),array('rpt_name'=>"actualdocumento_bodega".$VectorArgumentos['argumentos']['Cuenta'],'rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
               $nombre_funcion=$reporte->GetJavaFunction();
               $this->salida .=$mostrar;

               $this->salida .= "<div class='titulo3' align='center'><a href=\"javascript:$nombre_funcion\">Impresi??? Documento de Descargo</a><br><br>";
          }
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
          $this->salida .= "    <table class='modulo_table_title' border='0' width='88%'>\n";
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
     * Forma para el ingreso de un paciente a la estacion.
     *
     * @return boolean True si se ejecuto correctamente
     * @access public
     */
     function Call_AgregarInsumos($datosPaciente,$datos_estacion,$control)
     {
          if(empty($_REQUEST['datos_estacion']))
          	$datos_estacion = &$this->GetdatosEstacion();
          else
               $datos_estacion = $_REQUEST['datos_estacion'];

          //VALIDACION DE PERMISOS
          if(!is_array($datos_estacion))
          {
               $url= ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
               $titulo = "VALIDACION DE PERMISOS";
               $this->frmMSG($url,$titulo);
               return true;
          }

          UNSET($_SESSION['CUENTAS']['E']);
          UNSET($_SESSION['DATOS_INSUMOSTMP']);
          UNSET($_SESSION['DATOS_DOCUMENTO']);
          
          $_SESSION['CUENTAS']['E']['DATOS']=$datos_estacion;
          
          $tipo=$_REQUEST['tipoa'];
          
          $this->FrmDatosEstacion(&$datos_estacion);
          
          include_once 'app_modules/EE_Cargos/RemoteXajax/EECargoXajax.php';
         
          $this->SetXajax(array("ActivarCapa"));

          $this->IncludeJS("CrossBrowser");
          $this->IncludeJS("CrossBrowserDrag");
          $this->IncludeJS("CrossBrowserEvent");
          $this->IncludeJS("RemoteScripting");

          $this->FrmListadoPacientesEstacion($datos_estacion);               
          $this->FrmListadoPacientesConsultaUrgencias($datos_estacion);
          $this->FrmListadoPacientesEstacionCirugia($datos_estacion);
          $this->FrmListadoPacientesEstacionPreparacionQX($datos_estacion);
          
          $javaC = "<script>\n";
          $javaC .= "   function ImprimirDocumento(Documento, tipo_id_paciente, paciente_id, numerodecuenta, rango, plan_id, ingreso)\n";
          $javaC .= "   {\n";
          $javaC .= "	   Datos = new Array();\n";
          $javaC .= "	   arrayofstring = new Array();\n"; 
          $javaC .= "	   arrayofstring = Documento.split(',');\n";
          $javaC .= "	   Datos[0] = arrayofstring[0];\n";
          $javaC .= "	   Datos[1] = tipo_id_paciente;\n";
          $javaC .= "	   Datos[2] = paciente_id;\n";
          $javaC .= "	   Datos[3] = numerodecuenta;\n";
          $javaC .= "	   Datos[4] = rango;\n";
          $javaC .= "	   Datos[5] = plan_id;\n";
          $javaC .= "	   Datos[6] = ingreso;\n";
          $javaC .= "	   Datos[7] = arrayofstring[1];\n";
          $javaC .= "	   Datos[8] = arrayofstring[2];\n";
          $javaC .= "	   xajax_ActivarCapa(Datos);\n";
          $javaC .= "   }\n";  
          $javaC.= "</script>\n";
          $this->salida.= $javaC;
          
          $this->FrmPieDePagina();
          return true;
     }
     
     /**
     * Forma para mostrar el listado de pacientes en la Estacion de Enfermeria
     *
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function FrmListadoPacientesEstacion($datos_estacion)
     {
     	$reporte = new GetReports();
          $listadoPacientes = $this->GetPacientesInternados($datos_estacion['estacion_id']);
          if($listadoPacientes)
          {
               $this->IncludeJS('javascripts/funciones.js', $contenedor='app', $modulo='EE_PanelEnfermeria');
          
               $this->salida .= "<br>\n";
               $this->salida .= "<table align=\"center\" width=\"88%\"  border=\"0\" >\n";
               $this->salida .= "  <tr class=\"modulo_table_title\">\n";
               $this->salida .= "      <td colspan='9' height='30'>PACIENTES INTERNADOS EN LA ESTACION</td>\n";
               $this->salida .= "  </tr>\n";
               $this->salida .= "  <tr class=\"modulo_table_title\">\n";
               $this->salida .= "      <td width=\"15\">&nbsp;</td>\n";
               $this->salida .= "      <td align=\"center\">HAB.</td>\n";
               $this->salida .= "      <td align=\"center\">CAMA</td>\n";
               $this->salida .= "      <td align=\"center\">NOMBRE DEL PACIENTE</td>\n";
               $this->salida .= "      <td align=\"center\">IDENTIFICACION</td>\n";                              
               $this->salida .= "      <td align=\"center\">TIEMPO HOSP.</td>\n";
               $this->salida .= "      <td align=\"center\">CUENTA</td>\n";               
               $this->salida .= "      <td align=\"center\">ACCION</td>\n";
               $this->salida .= "      <td align=\"center\">DOCUMENTOS</td>\n";
               $this->salida .= "  </tr>\n";
                    
               $backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
               
               foreach($listadoPacientes as $k => $filaPacinte)
               {
                    if($p++ % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
                    $url_info_paciente = ModuloGetURL('app','EE_Cargos','user','MostrarDatosIngreso',array('ingreso'=>$filaPacinte['ingreso']));
                    $nombre_paciente = "<a href='$url_info_paciente'>$filaPacinte[nombre_completo]</a>";
                    $this->salida .= "<tr align=\"center\" class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>\n";
                    
                    $imagenPaciente = "<img src=\"".GetThemePath()."/images/hospitalizacion.png\" border=0 title='Paciente Hospitalizado.'>";
                    $this->salida .= "      <td>$imagenPaciente</td>\n";
                    $this->salida .= "      <td>$filaPacinte[pieza]</td>\n";
                    $this->salida .= "      <td>$filaPacinte[cama]</td>\n";
                    $this->salida .= "      <td align=\"left\">$nombre_paciente</td>\n";
                    $this->salida .= "      <td align=\"left\">".$filaPacinte[tipo_id_paciente]." - ".$filaPacinte[paciente_id]."</td>\n";
                    $this->salida .= "      <td align=\"left\">" . $this->GetDiasHospitalizacion($filaPacinte['fecha_ingreso']) . "</td>\n";
                    $this->salida .= "      <td align=\"center\">$filaPacinte[numerodecuenta]</td>\n";										
                    $href=ModuloGetURL('app','EE_Cargos','user','LlamarFormaBodegas',array("estacion"=>$datos_estacion,"Cuenta"=>$filaPacinte[numerodecuenta],'TipoId'=>$filaPacinte[tipo_id_paciente],'PacienteId'=>$filaPacinte[paciente_id],'Nivel'=>$filaPacinte[rango],'PlanId'=>$filaPacinte[plan_id],'ingreso'=>$filaPacinte[ingreso]));										
                    $this->salida .= "<td align=\"center\"><a href=\"$href\">Agregar Insumos</a></td>\n";
				//Documentos
                    $this->salida .= "<td align=\"center\">";
                    $mostrar = $reporte->GetJavaReport('app','EE_Cargos','solicitud_insumos_documentopaciente_html',array(),array('rpt_name'=>'detalledocumentobodega'.$filaPacinte[numerodecuenta],'rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
                    $funcion = $reporte->GetJavaFunction();
				$this->salida.=$mostrar;
                    $this->salida.="<select name=\"documento$k\" id=\"docuemto$k\" onchange=\"ImprimirDocumento(this.value, '".$filaPacinte[tipo_id_paciente]."', '".$filaPacinte[paciente_id]."', '".$filaPacinte[numerodecuenta]."', '".$filaPacinte[rango]."', '".$filaPacinte[plan_id]."', '".$filaPacinte[ingreso]."');$funcion;\" class=\"select\">";
          		$this->salida.="<option selected>- SELECCIONE -</option>";
                    $vector = $this->Get_DocumentosBodega($filaPacinte[numerodecuenta]);
                    $this->GetHtmlDocumentos($vector,$_REQUEST["documento".$k]);
          		$this->salida .="</select>";
                    $this->salida .="</td>\n";
                    //Documentos
                    $this->salida .= "</tr>\n";
               }
               $this->salida .= "  </table>\n";
          }
          else
          {
          	$this->salida .= "<br><div align=\"center\" class=\"label_mark\">LA ESTACION NO CUENTA CON PACIENTES HOSPITALIZADOS</div>";
          }
     }

     
     function GetHtmlDocumentos($vect,$TipoId)
     {
          foreach($vect as $value => $titulo)
          {
               $this->salida .=" <option value=\"".$titulo[numeracion].",".$titulo[bodegas_doc_id].",".$titulo[departamento_al_cargar]."\">$titulo[numeracion]</option>";
          }
     }

     /**
     * Forma para mostrar el listado de pacientes en consulta de urgencias de la Estacion de Enfermeria
     *
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function FrmListadoPacientesConsultaUrgencias($datos_estacion)
     {
          $reporte = new GetReports();
          $listadoPacientes2 = $this->GetPacientesConsultaUrgencias($datos_estacion['estacion_id']);
          if($listadoPacientes2)
          {
               $this->IncludeJS('javascripts/funciones.js', $contenedor='app', $modulo='EE_PanelEnfermeria');
          
               $this->salida .= "<br>\n";
               $this->salida .= "<table align=\"center\" width=\"88%\"  border=\"0\" >\n";
               $this->salida .= "  <tr class=\"modulo_table_title\">\n";
               $this->salida .= "      <td colspan='7' height='30'>PACIENTES EN CONSULTA DE URGENCIAS</td>\n";
               $this->salida .= "  </tr>\n";
               $this->salida .= "  <tr class=\"modulo_table_title\">\n";
               $this->salida .= "      <td width=\"15\">&nbsp;</td>\n";
               $this->salida .= "      <td align=\"center\">NOMBRE DEL PACIENTE</td>\n";
               $this->salida .= "      <td align=\"center\">IDENTIFICACION</td>\n";                              
               $this->salida .= "      <td align=\"center\">TIEMPO HOSP.</td>\n";
               $this->salida .= "      <td align=\"center\">CUENTA</td>\n";               
               $this->salida .= "      <td align=\"center\">ACCION</td>\n";
               $this->salida .= "      <td align=\"center\">DOCUMENTOS</td>\n";
               $this->salida .= "  </tr>\n";
          
               $backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
               
               foreach($listadoPacientes2 as $k2 => $filaPacinte2)
               {
                    if($p++ % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
                    $url_info_paciente = ModuloGetURL('app','EE_Cargos','user','MostrarDatosIngreso',array('ingreso'=>$filaPacinte2['ingreso']));
                    $nombre_paciente = "<a href='$url_info_paciente'>$filaPacinte2[nombre_completo]</a>";
                    $this->salida .= "<tr class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>\n";
                    $imagenPaciente = "<img src=\"".GetThemePath()."/images/consulta_ur.png\" border=0 title='Ingresar y asignar cama al paciente.'>";
                    $this->salida .= "      <td>$imagenPaciente</td>\n";
                    $this->salida .= "      <td align=\"left\">$nombre_paciente</td>\n";
                    $this->salida .= "      <td align=\"left\">".$filaPacinte2[tipo_id_paciente]." - ".$filaPacinte2[paciente_id]."</td>\n";
                    $this->salida .= "      <td align=\"left\">" . $this->GetDiasHospitalizacion($filaPacinte2['fecha_ingreso']) . "</td>\n";
                    $this->salida .= "      <td align=\"center\">$filaPacinte2[numerodecuenta]</td>\n";										
                    $href=ModuloGetURL('app','EE_Cargos','user','LlamarFormaBodegas',array("estacion"=>$datos_estacion,"Cuenta"=>$filaPacinte2[numerodecuenta],'TipoId'=>$filaPacinte2[tipo_id_paciente],'PacienteId'=>$filaPacinte2[paciente_id],'Nivel'=>$filaPacinte2[rango],'PlanId'=>$filaPacinte2[plan_id],'ingreso'=>$filaPacinte2[ingreso]));
                    $this->salida .= "<td align=\"center\"><a href=\"$href\">Agregar Insumos</a></td>\n";
                    //Documentos
                    $this->salida .= "<td align=\"center\">";
                    $mostrar = $reporte->GetJavaReport('app','EE_Cargos','solicitud_insumos_documentopaciente_html',array(),array('rpt_name'=>'detalledocumentobodega'.$filaPacinte2[numerodecuenta],'rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
                    $funcionURG = $reporte->GetJavaFunction();
				$this->salida.=$mostrar;
                    $this->salida.="<select name=\"documento$k\" id=\"docuemto$k\" onchange=\"ImprimirDocumento(this.value, '".$filaPacinte2[tipo_id_paciente]."', '".$filaPacinte2[paciente_id]."', '".$filaPacinte2[numerodecuenta]."', '".$filaPacinte2[rango]."', '".$filaPacinte2[plan_id]."', '".$filaPacinte2[ingreso]."');$funcionURG;\" class=\"select\">";
          		$this->salida.="<option value=-1 selected>-- SELECCIONE --</option>";
                    $vector=$this->Get_DocumentosBodega($filaPacinte2[numerodecuenta]);
                    $this->GetHtmlDocumentos($vector,$_REQUEST["documento".$k]);
          		$this->salida .="</select>";
                    $this->salida .="</td>\n";
                    //Documentos
                    $this->salida .= "  </tr>\n";
               }
               $this->salida .= "  </table>\n";
          }
          else
          {
          	$this->salida .= "<br><div align=\"center\" class=\"label_mark\">LA ESTACION NO CUENTA CON PACIENTES EN CONSULTA DE URGENCIAS</div>";
          }
     }
     
     /**
     * Forma para mostrar el listado de pacientes en la Estacion de Cirugia
     *
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function FrmListadoPacientesEstacionCirugia($datos_estacion)
     {
     	$reporte = new GetReports();
          $listadoPacientes = $this->GetPacientesInternadosCirugia($datos_estacion['departamento']);
          if($listadoPacientes)
          {
               $this->IncludeJS('javascripts/funciones.js', $contenedor='app', $modulo='EE_PanelEnfermeria');
          
               $this->salida .= "<br>\n";
               $this->salida .= "<table align=\"center\" width=\"88%\"  border=\"0\" >\n";
               $this->salida .= "  <tr class=\"modulo_table_title\">\n";
               $this->salida .= "      <td colspan='9' height='30'>PACIENTES INTERNADOS EN LA ESTACION</td>\n";
               $this->salida .= "  </tr>\n";
               $this->salida .= "  <tr class=\"modulo_table_title\">\n";
               $this->salida .= "      <td width=\"15\">&nbsp;</td>\n";
               $this->salida .= "      <td align=\"center\">QX.</td>\n";
			$this->salida .= "      <td align=\"center\">TIEMPO<BR>QX</sub></td>\n";
               $this->salida .= "      <td align=\"center\">NOMBRE DEL PACIENTE</td>\n";
               $this->salida .= "      <td align=\"center\">IDENTIFICACION</td>\n";                              
               $this->salida .= "      <td align=\"center\">TIEMPO HOSP.</td>\n";
               $this->salida .= "      <td align=\"center\">CUENTA</td>\n";               
               $this->salida .= "      <td align=\"center\">ACCION</td>\n";
               $this->salida .= "      <td align=\"center\">DOCUMENTOS</td>\n";
               $this->salida .= "  </tr>\n";
                    
               $backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
               
               foreach($listadoPacientes as $k => $filaPacinte)
               {
                    if($p++ % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
                    $url_info_paciente = ModuloGetURL('app','EE_Cargos','user','MostrarDatosIngreso',array('ingreso'=>$filaPacinte['ingreso']));
                    $nombre_paciente = "<a href='$url_info_paciente'>$filaPacinte[nombre_completo]</a>";
                    $this->salida .= "<tr align=\"center\" class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>\n";
                    
                    $imagenPaciente = "<img src=\"".GetThemePath()."/images/cirugia.png\" border=0 title='Paciente Hospitalizado.'>";
                    $this->salida .= "      <td>$imagenPaciente</td>\n";
                    $this->salida .= "      <td align=\"right\">".$this->QuirofanoPaciente($filaPacinte['programacion_id'])."</td>\n";
                    $Dia = explode("dias",$this->GetDiasHospitalizacion($filaPacinte['fecha_ingreso_cirugia']));
               	$this->salida .= "      <td align=\"right\">".$Dia[0]." dias</td>\n";
                    $this->salida .= "      <td align=\"left\">$nombre_paciente</td>\n";
                    $this->salida .= "      <td align=\"left\">".$filaPacinte[tipo_id_paciente]." - ".$filaPacinte[paciente_id]."</td>\n";
                    $this->salida .= "      <td align=\"left\">" . $this->GetDiasHospitalizacion($filaPacinte['fecha_ingreso']) . "</td>\n";
                    $this->salida .= "      <td align=\"center\">$filaPacinte[numerodecuenta]</td>\n";										
                    $href=ModuloGetURL('app','EE_Cargos','user','LlamarFormaBodegas',array("estacion"=>$datos_estacion,"Cuenta"=>$filaPacinte[numerodecuenta],'TipoId'=>$filaPacinte[tipo_id_paciente],'PacienteId'=>$filaPacinte[paciente_id],'Nivel'=>$filaPacinte[rango],'PlanId'=>$filaPacinte[plan_id],'ingreso'=>$filaPacinte[ingreso]));
                    $this->salida .= "<td align=\"center\"><a href=\"$href\">Agregar Insumos</a></td>\n";
				//Documentos
                    $this->salida .= "<td align=\"center\">";
                    $mostrar = $reporte->GetJavaReport('app','EE_Cargos','solicitud_insumos_documentopaciente_html',array(),array('rpt_name'=>'detalledocumentobodega'.$filaPacinte[numerodecuenta],'rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
                    $funcion = $reporte->GetJavaFunction();
				$this->salida.=$mostrar;
                    $this->salida.="<select name=\"documento$k\" id=\"docuemto$k\" onchange=\"ImprimirDocumento(this.value, '".$filaPacinte[tipo_id_paciente]."', '".$filaPacinte[paciente_id]."', '".$filaPacinte[numerodecuenta]."', '".$filaPacinte[rango]."', '".$filaPacinte[plan_id]."', '".$filaPacinte[ingreso]."');$funcion;\" class=\"select\">";
          		$this->salida.="<option selected>- SELECCIONE -</option>";
                    $vector = $this->Get_DocumentosBodega($filaPacinte[numerodecuenta]);
                    $this->GetHtmlDocumentos($vector,$_REQUEST["documento".$k]);
          		$this->salida .="</select>";
                    $this->salida .="</td>\n";
                    //Documentos
                    $this->salida .= "</tr>\n";
               }
               $this->salida .= "  </table>\n";
          }
          else
          {
          	$this->salida .= "<br><div align=\"center\" class=\"label_mark\">LA ESTACION DE CIRUGIA NO CUENTA CON PACIENTES</div>";
          }
     }
     
     /**
     * Forma para mostrar el listado de pacientes en la Estacion de Preparacion de Cirugia
     *
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function FrmListadoPacientesEstacionPreparacionQX($datos_estacion)
     {
     	$reporte = new GetReports();
          $listadoPacientes = $this->GetPacientesInternadosPreparacionQX($datos_estacion['departamento']);
          if($listadoPacientes)
          {
               $this->IncludeJS('javascripts/funciones.js', $contenedor='app', $modulo='EE_PanelEnfermeria');
          
               $this->salida .= "<br>\n";
               $this->salida .= "<table align=\"center\" width=\"88%\"  border=\"0\" >\n";
               $this->salida .= "  <tr class=\"modulo_table_title\">\n";
               $this->salida .= "      <td colspan='8' height='30'>PACIENTES INTERNADOS EN LA ESTACION DE PREPARACION DE CIRUGIA</td>\n";
               $this->salida .= "  </tr>\n";
               $this->salida .= "  <tr class=\"modulo_table_title\">\n";
               $this->salida .= "      <td width=\"15\">&nbsp;</td>\n";
			   $this->salida .= "      <td align=\"center\">TIEMPO EN<BR>PREPARACION</sub></td>\n";
               $this->salida .= "      <td align=\"center\">NOMBRE DEL PACIENTE</td>\n";
               $this->salida .= "      <td align=\"center\">IDENTIFICACION</td>\n";                              
               $this->salida .= "      <td align=\"center\">TIEMPO HOSP.</td>\n";
               $this->salida .= "      <td align=\"center\">CUENTA</td>\n";               
               $this->salida .= "      <td align=\"center\">ACCION</td>\n";
               $this->salida .= "      <td align=\"center\">DOCUMENTOS</td>\n";
               $this->salida .= "  </tr>\n";
                    
               $backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
               
               foreach($listadoPacientes as $k => $filaPacinte)
               {
                    if($p++ % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
                    $url_info_paciente = ModuloGetURL('app','EE_Cargos','user','MostrarDatosIngreso',array('ingreso'=>$filaPacinte['ingreso']));
                    $nombre_paciente = "<a href='$url_info_paciente'>$filaPacinte[nombre_completo]</a>";
                    $this->salida .= "<tr align=\"center\" class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>\n";
                    
                    $imagenPaciente = "<img src=\"".GetThemePath()."/images/cama.png\" border=0 title='Paciente Hospitalizado.'>";
                    $this->salida .= "      <td>$imagenPaciente</td>\n";
                    $Dia = explode("dias",$this->GetDiasHospitalizacion($filaPacinte['fecha_ingreso_cirugia']));
               	$this->salida .= "      <td align=\"right\">".$Dia[0]." dias</td>\n";
                    $this->salida .= "      <td align=\"left\">$nombre_paciente</td>\n";
                    $this->salida .= "      <td align=\"left\">".$filaPacinte[tipo_id_paciente]." - ".$filaPacinte[paciente_id]."</td>\n";
                    $this->salida .= "      <td align=\"left\">" . $this->GetDiasHospitalizacion($filaPacinte['fecha_ingreso']) . "</td>\n";
                    $this->salida .= "      <td align=\"center\">$filaPacinte[numerodecuenta]</td>\n";										
                    $href=ModuloGetURL('app','EE_Cargos','user','LlamarFormaBodegas',array("estacion"=>$datos_estacion,"Cuenta"=>$filaPacinte[numerodecuenta],'TipoId'=>$filaPacinte[tipo_id_paciente],'PacienteId'=>$filaPacinte[paciente_id],'Nivel'=>$filaPacinte[rango],'PlanId'=>$filaPacinte[plan_id],'ingreso'=>$filaPacinte[ingreso]));
                    $this->salida .= "<td align=\"center\"><a href=\"$href\">Agregar Insumos</a></td>\n";
				//Documentos
                    $this->salida .= "<td align=\"center\">";
                    $mostrar = $reporte->GetJavaReport('app','EE_Cargos','solicitud_insumos_documentopaciente_html',array(),array('rpt_name'=>'detalledocumentobodega'.$filaPacinte[numerodecuenta],'rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
                    $funcion = $reporte->GetJavaFunction();
				$this->salida.=$mostrar;
                    $this->salida.="<select name=\"documento$k\" id=\"docuemto$k\" onchange=\"ImprimirDocumento(this.value, '".$filaPacinte[tipo_id_paciente]."', '".$filaPacinte[paciente_id]."', '".$filaPacinte[numerodecuenta]."', '".$filaPacinte[rango]."', '".$filaPacinte[plan_id]."', '".$filaPacinte[ingreso]."');$funcion;\" class=\"select\">";
          		$this->salida.="<option selected>- SELECCIONE -</option>";
                    $vector = $this->Get_DocumentosBodega($filaPacinte[numerodecuenta]);
                    $this->GetHtmlDocumentos($vector,$_REQUEST["documento".$k]);
          		$this->salida .="</select>";
                    $this->salida .="</td>\n";
                    //Documentos
                    $this->salida .= "</tr>\n";
               }
               $this->salida .= "  </table>\n";
          }
          else
          {
          	$this->salida .= "<br><div align=\"center\" class=\"label_mark\">LA ESTACION DE CIRUGIA NO CUENTA CON PACIENTES</div>";
          }
     }

          
     /**
     * Forma para mostrar los datos de ingreso del paciente.
     *
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function MostrarDatosIngreso()
     {
          if(!$_REQUEST['ingreso'])
          {
               $url     = ModuloGetURL('app','EE_Cargos','user','Call_AgregarInsumos',array("datos_estacion"=>$_SESSION['CUENTAS']['E']['DATOS'],'tipoa'=>2));
               $titulo  = "DATOS DEL PACIENTE";
               $mensaje = "Error : El metodo ";
     
               $this->FrmMSG($url, $titulo, $mensaje, $url_titulo);
     
               return true;
          }
     
          if(!$datosPaciente = $this->GetDatosPaciente($_REQUEST['ingreso']))
          {
     
               $url     = ModuloGetURL('app','EE_Cargos','user','Call_AgregarInsumos',array("datos_estacion"=>$_SESSION['CUENTAS']['E']['DATOS'],'tipoa'=>2));
               $titulo  = "DATOS DEL PACIENTE";
               $mensaje = "Error : El metodo ";
     
               $this->FrmMSG($url, $titulo, $mensaje, $url_titulo);
     
               return true;
          }
     
          $ContactosPaciente = $this->GetContactosPaciente($_REQUEST['ingreso']);
     
          $this->salida .= ThemeAbrirTabla('INFORMACI&Oacute;N DEL PACIENTE','60%');//[ '.$datos_estacion[descripcion5].' ] -
          $this->salida .= "<br><table align=\"center\"  width=70% cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class=\"modulo_table\">\n";
     
          $this->salida .= "  <tr class=\"modulo_table\">\n";
          $this->salida .= "      <td class=\"label\">RESPONSABLE</td><td class=\"modulo_list_claro\">".$datosPaciente['nombre_tercero']."</td >\n";
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr class=\"modulo_table\">\n";
          $this->salida .= "      <td class=\"label\">PLAN</td><td class=\"modulo_list_claro\">".$datosPaciente['plan_descripcion']."</td>\n";
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr class=\"modulo_table\">\n";
          $this->salida .= "      <td class=\"label\">TIPO AFILIADO</td><td class=\"modulo_list_claro\">".$datosPaciente['tipo_afiliado_nombre']."</td>\n";
          $this->salida .= "  </tr>\n";
          $this->salida .= "</table>\n";
     
          $this->salida .= "<br><table width=70% align=\"center\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
          $this->salida .= "  <tr class=\"modulo_list_oscuro\">\n";
          $this->salida .= "      <td class=\"label\">PACIENTE</td><td class=\"modulo_list_claro\"><b>".strtoupper($datosPaciente['primer_nombre'])." ".strtoupper($datosPaciente['segundo_nombre'])." ".strtoupper($datosPaciente['primer_apellido'])." ".strtoupper($datosPaciente['segundo_apellido'])."</b></td>\n";
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr class=\"modulo_list_oscuro\">\n";
          $this->salida .= "      <td class=\"label\">IDENTIFICACION</td><td class=\"modulo_list_claro\"><b>".$datosPaciente['tipo_id_paciente']." ".$datosPaciente['paciente_id']."</b></td>\n";
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr class=\"modulo_list_oscuro\" >\n";
          $this->salida .= "      <td class=\"label\">HISTORIA CLINICA</td><td class=\"modulo_list_claro\">".$datosPaciente['historia_prefijo']." ".$datosPaciente['historia_numero']."</td>\n";
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr class=\"modulo_list_oscuro\">\n";
          $this->salida .= "      <td class=\"label\">SEXO</td><td class=\"modulo_list_claro\">".$datosPaciente['sexo_id']."</td>\n";
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr class=\"modulo_list_oscuro\">\n";
          $this->salida .= "      <td class=\"label\">FECHA NACIMIENTO</td><td class=\"modulo_list_claro\">".$datosPaciente['fecha_nacimiento']."</td>\n";
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr class=\"modulo_list_oscuro\">\n";
          $this->salida .= "      <td class=\"label\" nowrap=\"yes\">DIRECCION RESIDENCIA</td><td class=\"modulo_list_claro\" nowrap=\"yes\">".$datosPaciente['residencia_direccion'].". ".$datosPaciente['municipio'].", ".$datosPaciente['departamento'].", ".$datosPaciente['pais']."</td>\n";
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr class=\"modulo_list_oscuro\">\n";
          $this->salida .= "      <td class=\"label\" nowrap=\"yes\">TELEFONO RESIDENCIA</td><td class=\"modulo_list_claro\" nowrap=\"yes\">".$datosPaciente['residencia_telefono']."</td>\n";
          $this->salida .= "  </tr>\n";
          if($ContactosPaciente && $ContactosPaciente != "ShowMensaje")
          for($i=0; $i<sizeof($ContactosPaciente); $i++)
          {
               $this->salida .= "<tr valign=\"top\">\n";
               $this->salida .= "  <td class=\"label\">ACUDIENTE ".($i+1)."</td>\n";
               $this->salida .= "  <td>".strtoupper($ContactosPaciente[$i][nombre_completo])."\n";
               if($ContactosPaciente[$i][parentesco]){
                    $this->salida .= "          <br> PARENTESCO: ".$ContactosPaciente[$i][parentesco]."\n";
               }
               if($ContactosPaciente[$i][telefono]){
                    $this->salida .= "          <br> TELEFONO: ".$ContactosPaciente[$i][telefono]."\n";
               }
               if($ContactosPaciente[$i][direccion]){
                    $this->salida .= "          <br> DIRECCI&Oacute;N: ".$ContactosPaciente[$i][direccion]."\n";
               }
               if($i>0){
                    $this->salida .= "      <br>";
               }
               $this->salida .= "      </td><td>&nbsp;</td>\n";
               $this->salida .= "  </tr>\n";
          }
     
          $this->salida .= "<tr><td align=\"center\" colspan=\"2\">&nbsp;</td></tr>\n";
					$link=ModuloGetURL('app','EE_Cargos','user','Call_AgregarInsumos',array("datos_estacion"=>$_SESSION['CUENTAS']['E']['DATOS'],'tipoa'=>2));
          $this->salida .= "<tr><td align=\"center\" colspan=\"2\"><a href=\"$link\"><b><sub>REGRESAR</sub></b></a></td></tr>\n";
          $this->salida .= "</table><br>\n";
          $this->salida .= themeCerrarTabla();
          return true;
     }// fin MostrarDatosIngreso
     
     /**
     * Metodo q pinta la forma para la seleccion de Bodegas asociadas a la EE.
     */
     function FormaBodegas($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId)
     { 
          $this->salida .= ThemeAbrirTabla('ELEGIR BODEGAS DE INSUMOS O MEDICAMENTOS');
          $this->salida .= "			      <table width=\"40%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
          $this->salida .= $this->SetStyle("MensajeError");
          $this->salida .= "				       <tr>";
          $tipo=$this->Bodegas();
          if(empty($tipo))
          {	$this->salida .= "       <td class=\"label_error\" colspan=\"2\" align=\"center\">LA ESTACION NO TIENE BODEGAS ASOCIADAS</td>";  }
          else
          {
               $accion=ModuloGetURL('app','EE_Cargos','user','BodegaInsumos',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId));
               $this->salida .= "    <form name=\"formaborrar\" action=\"$accion\" method=\"post\">";
               $this->salida .= "       <td class=\"label\">BODEGAS: </td>";
               $this->salida .= "             		<td colspan=\"2\"><select name=\"Bodegas\" class=\"select\">";
               $this->salida .= " 										<option value=\"-1\">----------BODEGAS----------</option>";
               for($i=0; $i<sizeof($tipo); $i++)
               {
                         $this->salida .= " 										<option value=\"".$tipo[$i][bodega].",".$tipo[$i][empresa_id].",".$tipo[$i][centro_utilidad]."\">".$tipo[$i][descripcion]."</option>";
               }
               $this->salida .= "             		</select></td>";
          }
          $this->salida .= "				       </tr>";
          $this->salida .= "			     </table>";
          $this->salida .= " <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"45%\" align=\"center\"  >";
          $this->salida .= "	  <tr align=\"center\">";
          if(!empty($tipo))
          {
               $this->salida .= "	  	<td><input class=\"input-submit\" type=\"submit\" name=\"Cambiar\" value=\"ACEPTAR\"></td>";
               $this->salida .= "    </form>";
          }
          $accionCancelar = ModuloGetURL('app','EE_Cargos','user','Call_AgregarInsumos',array("datos_estacion"=>$_SESSION['CUENTAS']['E']['DATOS'],'tipoa'=>2));
          $this->salida .= "    <form name=\"formaborrar\" action=\"$accionCancelar\" method=\"post\">";
          $this->salida .= "	  	<td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"CANCELAR\"></td>";
          $this->salida .= "    </form>";
          $this->salida .= "	  </tr>";
          $this->salida .= " </table>";
          $this->salida .= ThemeCerrarTabla();
          return true;
     }
		 
	/**
     * Muestra los cargos que inserto con sus totales y la opcion de insertar un nuevo cargo.
     * @access private
     * @return boolean
     * @param int numero de la cuenta
     * @param string tipo documento
     * @param int numero documento
     * @param string nivel
     * @param string plan_id
     * @param int ingreso
     * @param date fecha de la cuenta
     */
     function	FormaInsumos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$D)
     {

        $file ='app_modules/EE_Cargos/RemoteXajax/EECargoXajax.php';
        $this->SetXajax(array("BuscarProducto","Agregar_Tmp_Insumos","ListarTmpInsumos","EliminarInsumo"),$file);
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserDrag");
        $this->IncludeJS("CrossBrowserEvent");
        $path = SessionGetVar("rutaImagenes");
        $this->IncludeJS('RemoteXajax/EECargo.js', $contenedor='app', $modulo='EE_Cargos');
        
        $javaC = "<script>\n";
        $javaC .= "   var contenedor1=''\n";
        $javaC .= "   var titulo1=''\n";
        $javaC .= "   var hiZ = 2;\n";
        $javaC .= "   var DatosFactor = new Array();\n";
        $javaC .= "   var EnvioFactor = new Array();\n";
        $javaC .= "   function Iniciar2(tit)\n";
        $javaC .= "   {\n";
        $javaC .= "       contenedor1 = 'ContenedorProductos';\n";
        $javaC .= "       titulo1 = 'tituloProductos';\n";
        $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
        $javaC.= "        Capa = xGetElementById(contenedor1);\n";
        $javaC .= "       xResizeTo(Capa, 750, 'auto');\n";
        $javaC.= "        Capx = xGetElementById('ContenedorProductos');\n";
        $javaC .= "       xResizeTo(Capx, 750, 400);\n";
        $javaC .= "       xMoveTo(Capa, xClientWidth()/10, xScrollTop()+10);\n";
        $javaC .= "       ele = xGetElementById(titulo1);\n";
        $javaC .= "       xResizeTo(ele, 730, 20);\n";
        $javaC .= "       xMoveTo(ele, 0, 0);\n";
        $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
        $javaC .= "       ele = xGetElementById('cerrarProductos');\n";
        $javaC .= "       xResizeTo(ele, 20, 20);\n";
        $javaC .= "       xMoveTo(ele, 730, 0);\n";
        $javaC .= "   }\n";
        $javaC.= "</script>\n";
        $this->salida.= $javaC;
        $javaC1.= "<script>\n";
        $javaC1 .= "   function myOnDragStart(ele, mx, my)\n";
        $javaC1 .= "   {\n";
        $javaC1 .= "     window.status = '';\n";
        $javaC1 .= "     if (ele.id == titulo1) xZIndex(contenedor1, hiZ++);\n";
        $javaC1 .= "     else xZIndex(ele, hiZ++);\n";
        $javaC1 .= "     ele.myTotalMX = 0;\n";
        $javaC1 .= "     ele.myTotalMY = 0;\n";
        $javaC1 .= "   }\n";
        $javaC1 .= "   function myOnDrag(ele, mdx, mdy)\n";//
        $javaC1 .= "   {\n";
        $javaC1 .= "     if (ele.id == titulo1) {\n";
        $javaC1 .= "       xMoveTo(contenedor1, xLeft(contenedor1) + mdx, xTop(contenedor1) + mdy);\n";
        $javaC1 .= "     }\n";
        $javaC1 .= "     else {\n";
        $javaC1 .= "       xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
        $javaC1 .= "     }  \n";
        $javaC1 .= "     ele.myTotalMX += mdx;\n";
        $javaC1 .= "     ele.myTotalMY += mdy;\n";
        $javaC1 .= "   }\n";
        $javaC1 .= "   function myOnDragEnd(ele, mx, my)\n";
        $javaC1 .= "   {\n";
        $javaC1 .= "   }\n";
        $javaC1.= "function MostrarCapa(Elemento)\n";
        $javaC1.= "{\n";
        $javaC1.= "    capita = xGetElementById(Elemento);\n";
        $javaC1.= "    capita.style.display = \"\";\n";
        $javaC1.= "}\n";
        $javaC1.= "function Cerrar(Elemento)\n";
        $javaC1.= "{\n";
        $javaC1.= "    capita = xGetElementById(Elemento);\n";
        $javaC1.= "    capita.style.display = \"none\";\n";
        $javaC1.= "}\n";
        $javaC1.= "</script>\n";
        $this->salida.= $javaC1;
/*******************************************************************************
* Ventana emergente 3 para la busqueda de productos.
**********************************************************************************/
        $this->salida.="<div id='ContenedorProductos' class='d2Container' style=\"display:none;\">";
        $this->salida .= "    <div id='tituloProductos' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
        $this->salida .= "    <div id='cerrarProductos' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorProductos');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
        $this->salida .= "    <div id='errorProductos' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
        $this->salida .= "    <div id='ContenidoProductos' class='d2Content'>\n";
        $this->salida .= "                          <input type=\"hidden\" id=\"empresa_idz\" value=\"".$datos['empresa_id']."\">\n";
        $this->salida .= "                          <input type=\"hidden\" id=\"centro_utilidadz\" value=\"".$datos['centro_utilidad']."\">\n";
        $this->salida .= "                          <input type=\"hidden\" id=\"bodegaz\" value=\"".$datos['bodega']."\">\n";
        
        $this->salida .= "               <form name=\"jukilo\" id=\"jukilo\" action=\"".$accion1."\" method=\"post\">\n";
        $this->salida .= "                 <table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
        $this->salida .= "                    <tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "                       <td COLSPAN='2' align=\"center\">\n";
        $this->salida .= "                          BUSCADOR DE PRODUCTOS";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                    <tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "                       <td width=\"35%\" align=\"center\">\n";
        $this->salida .= "                          TIPO DE BUSQUEDA";
        $this->salida .= "                       <select id=\"tip_bus\" name=\"tip_bus\" class=\"select\" onchange=\"Aplicar(this.value)\">";
        $this->salida .= "                           <option value=\"1\" SELECTED>DESCRIPCION</option> \n";
        $this->salida .= "                           <option value=\"2\"># CODIGO</option> \n";
        $this->salida .= "                       </select>\n";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td width=\"55%\" align=\"left\" id=\"ventanatabla\">\n";
        $this->salida .= "                          DESCRIPCION";
        $this->salida .= "                         <input type=\"text\" class=\"input-text\" id=\"criterio\" name=\"criterio\" size=\"40\" onkeypress=\"return acceptm(event);\" onkeydown=\"recogerTeclaBus(event,'500','450')\" value=\"\">\n";//
        $this->salida .= "                         <input type=\"hidden\" name=\"SuperCuenta\" value=\"$Cuenta\">";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                </table>\n";
        $this->salida .= "              </form>\n";
        $this->salida .= "                 <br>\n";
        $this->salida .="              <div id=\"tabelos\">";
        $this->salida .="              </div>\n";
        $this->salida .= "    </div>\n";
        $this->salida.="</div>"; 
/*******************************************************************************
* Ventana emergente 3 aqui es cuando se modifica una cuenta. 
**********************************************************************************/
    
          IncludeLib("tarifario");
          $dpto=$_SESSION['CUENTAS']['E']['DEPTO'];
          $Ingreso=$_SESSION['CUENTAS']['E']['INGRESO'];
          $TipoId=$_SESSION['CUENTAS']['E']['tipo_id_paciente'];
          $PacienteId=$_SESSION['CUENTAS']['E']['paciente_id'];
          $Nombres=$this->BuscarNombresPaciente($TipoId,$PacienteId);
          $Apellidos=$this->BuscarApellidosPaciente($TipoId,$PacienteId);
          
          $this->salida .= ThemeAbrirTabla('INSUMOS - AGREGAR CARGO A LA CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos);
          $argu=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso);
          $this->Encabezado1($PlanId,$TipoId,$PacienteId,$_SESSION['CUENTAS']['E']['INGRESO'],$Nivel,$Fecha,$argu,$Cuenta);
          $datos=$this->DatosTmpInsumos($Cuenta);
          $this->salida .= "			      <table width=\"50%\" align=\"center\" border=\"0\">";
          $this->salida .= $this->SetStyle("MensajeError");
          $this->salida .= "			     </table>";
          $this->salida .= "                 <br>";  
          $this->salida .="              <div id='error_insumo' class='label_error' style=\"text-transform: uppercase; text-align:center;\">";
          $this->salida .="              ";    
          $this->salida .="              </div>\n";  
          $this->salida .="              <div id=\"lista_insumos_seleccionados\">";
          $this->salida .="              </div>\n";
          
//           if(!empty($datos) AND empty($D))
//           {
//                $this->salida .= " <table border=\"0\" cellspacing=\"3\" cellpadding=\"3\" width=\"90%\" align=\"center\" >";
//                $this->salida .= "	  <tr align=\"center\" class=\"modulo_table_list_title\">";
//                $this->salida .= "	      <td>DEPARTAMENTO</td>";
//                $this->salida .= "	      <td>COD. PRODUCTO</td>";
//                $this->salida .= "	      <td>DESCRIPCION</td>";
//                $this->salida .= "	      <td>BODEGA</td>";
//                $this->salida .= "	      <td>PRECIO</td>";
//                $this->salida .= "	      <td>CANT.</td>";
//                $this->salida .= "	      <td></td>";
//                $this->salida .= "	      <td></td>";
//                $this->salida .= "	  </tr>";
//                for($i=0; $i<sizeof($datos);$i++)
//                {
//                     if( $i % 2) $estilo='modulo_list_claro';
//                     else $estilo='modulo_list_oscuro';
// 
//                     $this->salida .= "	  <tr class=\"$estilo\" align=\"center\">";
//                     $this->salida .= "	      <td>".$datos[$i][desdpto]."</td>";
//                     $this->salida .= "	      <td>".$datos[$i][codigo_producto]."</td>";
//                     $this->salida .= "	      <td>".$datos[$i][descripcion]."</td>";
//                     $this->salida .= "	      <td>".$datos[$i][desbodega]."</td>";
//                     $this->salida .= "	      <td>".$datos[$i][precio]."</td>";
//                     $this->salida .= "	      <td>".FormatoValor($datos[$i][cantidad])."</td>";
//                     $accionModificar=ModuloGetURL('app','EE_Cargos','user','LlamaFormaModificarCargoTmpIyM',array('ID'=>$Datos[$i][tmp_cuenta_insumos_id],'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Datos'=>$datos[$i]));
//                     $this->salida .= "	      <td><a href=\"$accionModificar\" alt=\"Modificar los cargos del paciente\">MODI</a></td>";
//                     $accionEliminar=ModuloGetURL('app','EE_Cargos','user','EliminarCargoTmpIyM',array('ID'=>$datos[$i][tmp_cuenta_insumos_id],'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
//                     $this->salida .= "	      <td><a href=\"$accionEliminar\">ELIM</a></td>";
//                     $this->salida .= "	  </tr>";
//                }
//                $this->salida .= " </table>";
//           }
          if(!empty($D))
          {
                    $bod[0]=$d[bodega];
          }
          $bodega_ss = $_SESSION['CUENTA']['E']['BODEGA'];
          SessionDelVar("bodega");
          SessionSetVar("bodega",$bodega_ss);
          $bod=explode(',',$bodega_ss);
            //var_dump($bod);
          global $_ROOT;
          $sw=ModuloGetVar('app','Facturacion','sw_gravar_cuota_paciente');
          $this->salida .= "\n<script>\n";
          $this->salida .= "var rem=\"\";\n";
          $this->salida .= "  function abrirVentana(){\n";
          $this->salida .= "    var dpto='';\n";
          $this->salida .= "    var bodega='';\n";
          $this->salida .= "    bodega=document.newcargo.Bodegas.value;\n";
          $this->salida .= "    if(bodega==-1){\n";
          $this->salida .= "      alert('Debe elegir la Bodega.');\n";
          $this->salida .= "    }\n";
          $this->salida .= "    else{\n";
          $this->salida .= "    	var nombre='';\n";
          $this->salida .= "      var url2='';\n";
          $this->salida .= "      var str='';\n";
          $this->salida .= "      var ALTO=screen.height;\n";
          $this->salida .= "      var ANCHO=screen.width;\n";
          $this->salida .= "      nombre=\"buscador_General\";\n";
          $this->salida .= "      str =\"ANCHO,ALTO,resizable=no,status=no,scrollbars=yes\";\n";
          $this->salida .= "      url2 ='".$_ROOT."classes/classbuscador/buscador.php?tipo=InsertarInsumos&forma=newcargo&plan='+'$PlanId'+'&Empresa='+'$bod[1]'+'&CU='+'$bod[2]'+'&Bodega='+bodega;\n";
          $this->salida .= "      rem = window.open(url2, nombre, str);\n";
          $this->salida .= "    }\n";
          $this->salida .= "  }\n";
          $this->salida .= "</script>\n";
          if($D){
               $accion=ModuloGetURL('app','EE_Cargos','user','ModificarCargoTmpIyM',array('id'=>$D[tmp_cuenta_insumos_id],'Datos'=>$D));
               $Boton='MODIFICAR CARGO';
               $Modi=true;
          }
          else {
               $accion=ModuloGetURL('app','EE_Cargos','user','InsertarInsumos');
               $Boton='AGREGAR CARGO';
          }
          $this->salida .= " <form name=\"newcargo\" action=\"$accion\" method=\"post\">";
          $FechaCargo=date("d/m/Y");
          $Dpto=$this->Departamento;
          $Descripcion='';
          $Cant=1;
          //$this->salida .= " <table border=\"0\" width=\"90%\" align=\"center\"  class=\"normal_10\">";
          //$this->salida .= "   <tr>";
          //$this->salida .= "    <td>";
          //$this->salida .= "     <fieldset><legend class=\"field\">AGREGAR CARGO</legend>";
          //$this->salida .= "       <table height=\"74\" border=\"0\" width=\"98%\" align=\"center\" class=\"normal_10\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"PacienteId\" value=\"$PacienteId\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"TipoId\" value=\"$TipoId\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"PlanId\" value=\"$PlanId\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"Nivel\" value=\"$Nivel\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"Ingreso\" value=\"$Ingreso\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"Fecha\" value=\"$Fecha\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"Cuenta\" value=\"$Cuenta\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"Cobertura\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"EmpresaId\" value=\"$bod[1]\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"CU\" value=\"$bod[2]\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"Bodegas\" value=\"$bod[0]\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"CantMax\">";
          //$this->salida .= "       <tr>";
          //$this->salida .= "         <td class=\"label\" width=\"18%\" >DEPARTAMENTO: </td>";
          $x=$this->BuscarNombreDpto($dpto);
          //$this->salida .= "	       <td>$x</td>";
          //$this->salida .= "       <td>&nbsp;</td>";
          //$this->salida .= "       <td class=\"".$this->SetStyle("Codigo")."\">COD. PROD: </td>";
          //$this->salida .= "   <td><input type=\"text\" class=\"input-text\" name=\"Codigo\" size=\"12\" value=\"".$D[codigo_producto]."\" ></td>";
          //$this->salida .= "       <td>&nbsp;</td>";
          $bode=$this->NombreBodega($bod[0]);
          $_SESSION['ESTANCIA']['BODEGA'] = $bode;
          $_SESSION['ESTANCIA']['DPTO'] = $x;
          //$this->salida .= "               <td colspan=\"2\" class=\"label\">BODEGA:  ".$bode[descripcion]."</td>";
          $this->salida .= "              <table height=\"74\" border=\"0\" width=\"98%\" align=\"center\" class=\"normal_10\">";  
          $this->salida .= "                <tr>";
          $this->salida .= "                  <td colspan=\"2\" align=\"center\">";//abrirVentana()
          $this->salida .= "                      <a class=\"label_error\" href=\"javascript:MostrarCapa('ContenedorProductos'); xajax_BuscarProducto((xajax.getFormValues('jukilo')),1,'".$Cuenta."','".$PlanId."');Iniciar2('BUSCAR PRODUCTOS');\" title=\"AGREGAR INSUMO A LA CUENTA\">AGREGAR INSUMO(S) A LA CUENTA</a>\n";
          $this->salida .= "                </td>";
          $this->salida .= "                </tr>";
          $this->salida .= "              </table>";
          //$this->salida .= "              <tr>";
//           $this->salida .= "                <td class=\"label\">DESCRIPCION: </td>";
//           $this->salida .= "                <td><textarea cols=\"35\" rows=\"3\" class=\"textarea\"name=\"Descripcion\" readonly>".$D[descripcion]."</textarea></td>";
//           $this->salida .= "                <td>&nbsp;</td>";
//           $this->salida .= "                <td class=\"label\">PRECIO: </td>";
//           $this->salida .= "                <td><input type=\"text\" class=\"input-text\" name=\"Precio\" size=\"10\" value=\"".$D[precio]."\" readonly></td>";
//           $this->salida .= "                <td>&nbsp;</td>";
          //$this->salida .= "                <td class=\"".$this->SetStyle("Cantidad")."\">CANTIDAD: </td>";
          //$this->salida .= "                <td><input type=\"text\" class=\"input-text\" name=\"Cantidad\" size=\"5\" value=\"$Cant\"></td>";
          //$this->salida .= "              </tr>";
          //$this->salida .= "              <tr>";
//           $this->salida .= "                <td class=\"label\">GRAVAMEN %: </td>";
//           $this->salida .= "                <td><input type=\"text\" class=\"input-text\" name=\"Gravamen\" size=\"10\" value=\"".FormatoValor($Gravamen)."\" readonly></td>";
//           $this->salida .= "                <td>&nbsp;</td>";
//           $this->salida .= "                <td class=\"".$this->SetStyle("FechaCargo")."\">FECHA CARGO: </td>";
//           $this->salida .= "	  	          <td colspan=\"4\"><input type=\"text\" name=\"FechaCargo\" value=\"$FechaCargo\" size=\"10\" class=\"input-text\" onFocus=\"this.select();\"  onBlur=\"IsValidDate(this,'DD/MM/YYYY')\" onKeyUp=\"setDate(this,'DD/MM/YYYY','es')\">&nbsp;&nbsp;";
//           $this->salida .= 	ReturnOpenCalendario('newcargo','FechaCargo','/')."</td>";
//           $this->salida .= "		          </tr>";
//           $this->salida .= "			       </table>";
//           $this->salida .= "		      </fieldset></td></tr></table>";
          $this->salida .= "               <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"80%\" align=\"center\"  >";
          $this->salida .= "	            <tr align=\"center\">";
          //$this->salida .= "	             <td><input class=\"input-submit\" type=\"submit\" name=\"Cambiar\" value=\"$Boton\"></td>";
          $this->salida .= "                   </form>";
          $accionEliminarTodos=ModuloGetURL('app','EE_Cargos','user','EliminarTodosCargosIyM',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Pieza'=>$Pieza,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
          $this->salida .= "                    <form name=\"formaborrar\" action=\"$accionEliminarTodos\" method=\"post\">";
          $this->salida .= "	  	             <td width=\"25%\"><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"ELIMINAR TODOS LOS CARGOS\"></td>";
          $this->salida .= "                     </form>";
          $accionCancelar=ModuloGetURL('app','EE_Cargos','user','EliminarTodosCargosIyM',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Pieza'=>$Pieza,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
          $this->salida .= "                    <form name=\"formaguardar\" action=\"$accionCancelar\" method=\"post\">";
          $this->salida .= "	   	              <td width=\"25%\"><input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"CANCELAR\"></td>";
          $this->salida .= "                    </form>";
          $this->salida .= "                      <td width=\"30%\">&nbsp;</td>";
          $accionGuardarTodos=ModuloGetURL('app','EE_Cargos','user','GuardarTodosCargosIyM',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Pieza'=>$Pieza,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
          $this->salida .= "                    <form name=\"formaguardar\" action=\"$accionGuardarTodos\" method=\"post\">";
          $this->salida .= "                     <td width=\"20%\"><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"GUARDAR TODOS LOS CARGOS\"></td>";
          $this->salida .= "                    </form>";  
          $this->salida .= "	            </tr>";
          $this->salida .= "	          </table><br>";
          $this->salida.=" <script language=\"javaScript\">
                             function mOvr(src,clrOver) 
                              {
                                 src.style.background = clrOver;
                              }

                             function mOut(src,clrIn) 
                             {
                               src.style.background = clrIn;
                             }
                             ";
          
        $this->salida.="function recogerTeclaBus(evt)
                        {                   
                            var keyCode = document.layers ? evt.which : document.all ? evt.keyCode : evt.keyCode;
                            var keyChar = String.fromCharCode(keyCode);

                            if(keyCode==13)  //Si se pulsa enter da directamente el resultado
                            {
                                //alert('good job');
                                xajax_BuscarProducto((xajax.getFormValues('jukilo')),1,'".$Cuenta."','".$PlanId."');
                            }

                        }


                       function MostrarTmpInsumos()
                       {
                            xajax_ListarTmpInsumos('".$Cuenta."');
                       }
                      MostrarTmpInsumos();  
                        </script>";
        $this->salida .= ThemeCerrarTabla();
        return true;
     }

     function Encabezado($PlanId,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$argu,$Cuenta)
     {
          $datos=$this->CuentaParticular($Cuenta,$PlanId); 
          if(!$datos)
          {
               $datos=$this->BuscarPlanes($PlanId,$Ingreso);
               $Responsable=$datos[nombre_tercero];
               $ident=$datos[tipo_id_tercero].' '.$datos[tercero_id];
          }
          $Nombres=$this->BuscarNombresPaciente($TipoId,$PacienteId);
          $Apellidos=$this->BuscarApellidosPaciente($TipoId,$PacienteId);
          $Fecha1=$this->FechaStamp($Fecha);
          $Hora=$this->HoraStamp($Fecha);
          $this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\" >";
          $this->salida .= "		<tr>";
          $this->salida .= "		   <td width=\"45%\">";
          $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"normal_10\" >";
          $this->salida .= "            <tr><td><fieldset><legend class=\"field\">RESPONSABLE</legend>";
          $this->salida .= "              <table border=\"0\" width=\"98%\" align=\"center\" class=\"normal_10\">";
          $this->salida .= "                <tr><td class=\"label\" width=\"24%\">RESPONSABLE: </td><td>$Responsable</td></tr>";
          $this->salida .= "                <tr><td class=\"label\" width=\"24%\">IDENTIFICACION: </td><td>".$ident."</td></tr>";
          $this->salida .= "                <tr><td class=\"label\" width=\"24%\">PLAN: </td><td>".$datos[plan_descripcion]."</td></tr>";
          $this->salida .= "                <tr><td class=\"label\" width=\"24%\">NIVEL: </td><td>$Nivel</td></tr>";
          if(!empty($datos[protocolos]))
          {
               if(file_exists("protocolos/".$datos[protocolos].""))
               {
                    $Protocolo=$datos[protocolos];
                    $this->salida .= "<script>";
                    $this->salida .= "function Protocolo(valor){";
                    $this->salida .= "window.open('protocolos/'+valor,'PROTOCOLO','');";
                    $this->salida .= "}";
                    $this->salida .= "</script>";
                    $accion="javascript:Protocolo('$datos[protocolos]')";
                    $this->salida .= "                <tr><td class=\"label\" width=\"24%\">PROTOCOLO: </td><td><a href=\"$accion\">$Protocolo</a></td></tr>";
               }
          }
          $this->salida .= "			       </table>";
          $this->salida .= "		      </fieldset></td></tr></table>";
          $this->salida .= "		   </td>";
          $this->salida .= "		   <td>";
          $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
          $this->salida .= "            <tr><td><fieldset><legend class=\"field\">DATOS DEL PACIENTE</legend>";
          $this->salida .= "              <table border=\"0\" width=\"97%\" align=\"center\" class=\"normal_10\">";
          $this->salida .= "                <tr><td class=\"label\" width=\"35%\">PACIENTE: </td><td>$Nombres $Apellidos</td></tr>";
          $this->salida .= "                <tr><td class=\"label\">IDENTIFICACION: </td><td>$TipoId  $PacienteId</td></tr>";
          $this->salida .= "                <tr><td class=\"label\">No. INGRESO: </td><td>$Ingreso</td></tr>";
          //$this->salida .= "                <tr><td class=\"label\">FECHA APERTURA: </td><td>$Fecha1</td></tr>";
          //$this->salida .= "                <tr><td class=\"label\">HORA APERTURA: </td><td>$Hora</td></tr>";
          $this->salida .= "			        </table>";
          $this->salida .= "		      </fieldset></td></tr></table>";
          $this->salida .= "		   </td>";
          $this->salida .= "		</tr>";
          $this->salida .= "	</table>";
     }


     function Encabezado1($PlanId,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$argu,$Cuenta)
     {
          $datos=$this->CuentaParticular($Cuenta,$PlanId); 
          if(!$datos)
          {
               $datos=$this->BuscarPlanes($PlanId,$Ingreso);
               $Responsable=$datos[nombre_tercero];
               $ident=$datos[tipo_id_tercero].' '.$datos[tercero_id];
          }
          $Nombres=$this->BuscarNombresPaciente($TipoId,$PacienteId);
          $Apellidos=$this->BuscarApellidosPaciente($TipoId,$PacienteId);
          $Fecha1=$this->FechaStamp($Fecha);
          $Hora=$this->HoraStamp($Fecha);
          $this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\" >";
          $this->salida .= "        <tr>";
          $this->salida .= "           <td width=\"50%\">";
          $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\" >";
          $this->salida .= "            <tr class=\"formulacion_table_list\"><td colspan='2'>DATOS RESPONSABLE</td></tr>";
          $this->salida .= "                <tr><td class=\"formulacion_table_list\" width=\"24%\">NOMBRE: </td><td class=\"modulo_list_claro\">$Responsable</td></tr>";
          $this->salida .= "                <tr><td class=\"formulacion_table_list\" width=\"24%\">IDENTIFICACION: </td><td class=\"modulo_list_claro\">".$ident."</td></tr>";
          $this->salida .= "                <tr><td class=\"formulacion_table_list\" width=\"24%\">PLAN: </td><td class=\"modulo_list_claro\">".$datos[plan_descripcion]."</td></tr>";
          $this->salida .= "                <tr><td class=\"formulacion_table_list\" width=\"24%\">NIVEL: </td><td class=\"modulo_list_claro\">$Nivel</td></tr>";
          if(!empty($datos[protocolos]))
          {
               if(file_exists("protocolos/".$datos[protocolos].""))
               {
                    $Protocolo=$datos[protocolos];
                    $this->salida .= "<script>";
                    $this->salida .= "function Protocolo(valor){";
                    $this->salida .= "window.open('protocolos/'+valor,'PROTOCOLO','');";
                    $this->salida .= "}";
                    $this->salida .= "</script>";
                    $accion="javascript:Protocolo('$datos[protocolos]')";
                    $this->salida .= "                <tr><td class=\"label\" width=\"24%\">PROTOCOLO: </td><td><a href=\"$accion\">$Protocolo</a></td></tr>";
               }
          }
          $this->salida .= "                 </table>";
          //$this->salida .= "              </td></tr></table>";
          $this->salida .= "    </td>";
          $this->salida .= "    <td>";
          $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
          $this->salida .= "            <tr class=\"formulacion_table_list\"><td colspan='2'>INFORMACION DEL PACIENTE</td></tr>";
          $this->salida .= "                <tr><td class=\"formulacion_table_list\" width=\"35%\">PACIENTE: </td><td class=\"modulo_list_claro\">$Nombres $Apellidos</td></tr>";
          $this->salida .= "                <tr><td class=\"formulacion_table_list\">IDENTIFICACION: </td><td class=\"modulo_list_claro\">$TipoId  $PacienteId</td></tr>";
          $this->salida .= "                <tr><td class=\"formulacion_table_list\">No. INGRESO: </td><td class=\"modulo_list_claro\">$Ingreso</td></tr>";
          $this->salida .= "                <tr><td class=\"formulacion_table_list\">No. CUENTA:</td><td class=\"modulo_list_claro\">$Cuenta</td></tr>";
          //$this->salida .= "                <tr><td class=\"label\">FECHA APERTURA: </td><td>$Fecha1</td></tr>";
          //$this->salida .= "                <tr><td class=\"label\">HORA APERTURA: </td><td>$Hora</td></tr>";
          $this->salida .= "      </table>";
          $this->salida .= "     </td>";
          $this->salida .= "    </tr>";
          $this->salida .= "    </table>";
     } 
     /*
     * Funcion que me permite intercalar las clases de las vistas
     */          
     function Lista($numero)
     {
          if ($numero%2)
               return ("class='modulo_list_oscuro'");
          return ("class='modulo_list_claro'");
     }//End lISTA

}//fin de la clase

?>