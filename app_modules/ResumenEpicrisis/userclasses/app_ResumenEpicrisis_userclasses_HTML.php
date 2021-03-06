<?php

/**
 * $Id: app_ResumenEpicrisis_userclasses_HTML.php,v 1.6 2006/12/27 18:49:28 luis Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: Manejo visual de los resumenes de las Epicrisis.
 */

/**
*Contiene los metodos visuales para realizar las autorizaciones.
*/

class app_ResumenEpicrisis_userclasses_HTML extends app_ResumenEpicrisis_user
{
	/**
	*Constructor de la clase app_CentroAutorizacion_user_HTML
	*El constructor de la clase app_CentroAutorizacion_user_HTML se encarga de llamar
	*a la clase app_CentroAutorizacion_user quien se encarga de el tratamiento
	* de la base de datos.
	*/

    function app_ResumenEpicrisis_user_HTML()
	{
		$this->salida='';
		$this->app_ResumenEpicrisis_user();
		return true;
	}
  	
     function SetStyle($campo)
	{
          if ($this->frmError[$campo] || $campo=="MensajeError"){
               if ($campo=="MensajeError"){
                    return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
               }
               return ("label_error");
          }
          return ("label");
	}
     
     /**
     * Forma para mostrar mensaje
     *
     * @param string $url opcional url de retorno
     * @param string $titulo opcional titulo de la ventana
     * @param string $mensaje opcional mensaje a mostrar
     * @param string $url_titulo opcional
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function FrmMSG($url='', $titulo='', $mensaje='', $url_titulo='VOLVER')
     {
          if(empty($titulo))  $titulo  = $this->titulo;
          if(empty($mensaje)) $mensaje = "EL USUARIO NO TIENE PERMISOS EN ESTE MODULO.";
     
          $this->salida  = themeAbrirTabla($titulo);
          $this->salida .= "<div class='titulo3' align='center'><br><br><b>$mensaje</b>";
     
          if($url)
          {
               $this->salida.="  <table width='100%' border='0' cellspacing=\"0\" cellpadding=\"8\">\n";
               $this->salida.="    <tr>\n";
               $this->salida.="        <td align='center' class=\"label_error\">\n";
               $this->salida.="            <a href='$url'>$url_titulo</a>\n";
               $this->salida.="        </td>\n";
               $this->salida.="    </tr>\n";
               $this->salida.="  </table>\n";
     
          }
     
          $this->salida .= "<br><br></div>";
          $this->salida .= themeCerrarTabla();
          return true;
     }
     
     /**
	*
	*/
	function FormaMenus()
	{
          if(empty($_SESSION['EPICRISIS']['EMPRESA_ID']))
          {
               $_SESSION['EPICRISIS']['EMPRESA_ID'] = $_REQUEST['DatosEpicrisis']['empresa_id'];
               $_SESSION['EPICRISIS']['EMPRESA'] = $_REQUEST['DatosEpicrisis']['razon_social'];
               $_SESSION['EPICRISIS']['ESTACION_ID'] = $_REQUEST['DatosEpicrisis']['estacion_id'];
               $_SESSION['EPICRISIS']['ESTACION'] = $_REQUEST['DatosEpicrisis']['descripcion'];
               $_SESSION['EPICRISIS']['DPTO'] = $_REQUEST['DatosEpicrisis']['nombre_dpto'];
          }
          
          if($_REQUEST['ubicacion'] == 'estacion')
          {
          	$estacion = $_REQUEST['datos_estacion'];
               $_SESSION['EPICRISIS']['EMPRESA_ID'] = $_REQUEST['datos_estacion']['empresa_id'];
               $_SESSION['EPICRISIS']['EMPRESA'] = $_REQUEST['datos_estacion']['empresa_descripcion'];
               $_SESSION['EPICRISIS']['ESTACION_ID'] = $_REQUEST['datos_estacion']['estacion_id'];
               $_SESSION['EPICRISIS']['ESTACION'] = $_REQUEST['datos_estacion']['estacion_descripcion'];
               $_SESSION['EPICRISIS']['DPTO'] = $_REQUEST['datos_estacion']['departamento_descripcion'];
          }
          
	     $this->salida  = ThemeAbrirTabla('PACIENTES EN LA ESTACION','800');
          if($_REQUEST['ruta'] == '')
          {
               $this->FrmListadoPacientesEstacion($_REQUEST['ubicacion'], $_REQUEST['datos_estacion'], $_REQUEST['ruta']);
          }
          else
          {
               $this->FrmListadoPacientesConsultaUrgencias($_REQUEST['ubicacion'], $_REQUEST['datos_estacion'], $_REQUEST['ruta']);
          }
		
          if($_REQUEST['ubicacion'] == 'estacion')
          {
	          $action = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
          }
          else
          {
          	$action = ModuloGetURL('app','ResumenEpicrisis','user','main',array());
          }
          $this->salida .= "<br><div align=\"center\"><a href=\"$action\">VOLVER</a></div>";
          $this->salida.=ThemeCerrarTabla();
          return true;
          
     }
     
     /**
     * Forma para mostrar el listado de pacientes en la Estacion de Enfermeria
     *
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function FrmListadoPacientesEstacion($location, $datos_estacion, $ruta)
     {
          $reporte= new GetReports();
          	
          $listadoPacientes = $this->GetPacientesInternados();
		
          if($listadoPacientes)
          {
               $this->IncludeJS('javascripts/funciones.js', $contenedor='app', $modulo='EE_PanelEnfermeria');
          
               $this->salida .= "<br>\n";
               $this->salida .= "<table align=\"center\" width=\"80%\"  border=\"0\" >\n";
               $this->salida .= "  <tr class=\"modulo_table_title\">\n";
               $this->salida .= "      <td colspan='7' height='30'>PACIENTES INTERNADOS EN LA ESTACION</td>\n";
               $this->salida .= "  </tr>\n";
               $this->salida .= "  <tr class=\"modulo_table_title\">\n";
               $this->salida .= "      <td width=\"15\">&nbsp;</td>\n";
               $this->salida .= "      <td align=\"center\">HAB.</td>\n";
               $this->salida .= "      <td align=\"center\">CAMA</td>\n";
               $this->salida .= "      <td align=\"center\">NOMBRE DEL PACIENTE</td>\n";
               $this->salida .= "      <td align=\"center\">TIEMPO HOSP.</td>\n";
               $this->salida .= "      <td align=\"center\">IMPRIMIR</td>\n";
               $this->salida .= "      <td align=\"center\">AUTORIZAR</td>\n";
               $this->salida .= "  </tr>\n";
                    
               $backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
                              
               if($location == 'estacion')
               {	$rutaVolver = ModuloGetURL('app','ResumenEpicrisis','user','FormaMenus',array("datos_estacion"=>$datos_estacion,"ubicacion"=>"estacion",'ruta'=>$ruta)); }
               else {	$rutaVolver = ModuloGetURL('app','ResumenEpicrisis','user','main',array()); }
               
               foreach($listadoPacientes as $k => $filaPacinte)
               {
                    if($p++ % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
                    $url_info_paciente = ModuloGetURL('app','ResumenEpicrisis','user','MostrarDatosIngreso',array('ingreso'=>$filaPacinte['ingreso'],'ubicacion'=>$location));
                    $nombre_paciente = "<a href='$url_info_paciente'>$filaPacinte[nombre_completo]</a>";
                    $this->salida .= "<tr class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>\n";
                    
                    $imagenPaciente = "<img src=\"".GetThemePath()."/images/hospitalizacion.png\" border=0 title='Paciente Hospitalizado.'>";
                    $this->salida .= "      <td>$imagenPaciente</td>\n";
                    $this->salida .= "      <td>$filaPacinte[pieza]</td>\n";
                    $this->salida .= "      <td>$filaPacinte[cama]</td>\n";
                    $this->salida .= "      <td>$nombre_paciente</td>\n";
                    $this->salida .= "      <td align=\"right\">" . $this->GetDiasHospitalizacion($filaPacinte['fecha_ingreso']) . "</td>\n";
                    $mostrar3=$reporte->GetJavaReport_Epicrisis($filaPacinte[ingreso],array());
                    $funcion2=$reporte->GetJavaFunction();
                    $this->salida.=$mostrar3;
                    echo $mostrar;
                    $this->salida .= "<td align=\"center\"><a href=\"javascript:$funcion2\"><img src=\"". GetThemePath() ."/images/imprimir.png\" border='0'></a></td>\n";
                    $AccionAutorizacion = ModuloGetURL('app','NCAutorizaciones','user','FormaConsultarAutorizaciones',array('ingreso'=>$filaPacinte['ingreso']));
                    $Autorizacion = $this->ReturnModuloExterno('app','NCAutorizaciones','user');
                    $Autorizacion->SetActionVolver($rutaVolver);
                    $Autorizacion->SetBuscador(false);
                    $this->salida .= "<td align=\"center\"><a href=\"$AccionAutorizacion\"><img src=\"". GetThemePath() ."/images/autorizadores.png\" border='0'></a></td>\n";
                    $this->salida .= "</tr>\n";
               }
               $this->salida .= "  </table>\n";
          }
          else
          {
          	$this->salida .= "<br><div align=\"center\" class=\"label_mark\">LA ESTACION NO CUENTA CON PACIENTES HOSPITALIZADOS</div>";
          }
     }

     /**
     * Forma para mostrar el listado de pacientes en consulta de urgencias de la Estacion de Enfermeria
     *
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function FrmListadoPacientesConsultaUrgencias($location, $datos_estacion, $ruta)
     {
          $reporte2 = new GetReports();
          
          $listadoPacientes2 = $this->GetPacientesConsultaUrgencias();
					
          
          if($listadoPacientes2)
          {
               $this->IncludeJS('javascripts/funciones.js', $contenedor='app', $modulo='EE_PanelEnfermeria');
          
               $this->salida .= "<br>\n";
               $this->salida .= "<table align=\"center\" width=\"80%\"  border=\"0\" >\n";
               $this->salida .= "  <tr class=\"modulo_table_title\">\n";
               $this->salida .= "      <td colspan='6' height='30'>PACIENTES EN CONSULTA DE URGENCIAS</td>\n";
               $this->salida .= "  </tr>\n";
               $this->salida .= "  <tr class=\"modulo_table_title\">\n";
               $this->salida .= "      <td width=\"15\">&nbsp;</td>\n";
               $this->salida .= "      <td align=\"center\">NOMBRE DEL PACIENTE</td>\n";
               $this->salida .= "      <td align=\"center\">TIEMPO HOSP.</td>\n";
               $this->salida .= "      <td align=\"center\">HC</td>\n";
							 $this->salida .= "      <td align=\"center\">EPICRISIS</td>\n";
               $this->salida .= "      <td align=\"center\">AUTORIZAR</td>\n";
               $this->salida .= "  </tr>\n";
          
               $backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');

               if($location == 'estacion')
               {	$rutaVolver = ModuloGetURL('app','ResumenEpicrisis','user','FormaMenus',array("datos_estacion"=>$datos_estacion,"ubicacion"=>"estacion",'ruta'=>$ruta)); }
               else {	$rutaVolver = ModuloGetURL('app','ResumenEpicrisis','user','main',array()); }
               
               foreach($listadoPacientes2 as $k2 => $filaPacinte2)
               {
                    if($p++ % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
                    $url_info_paciente = ModuloGetURL('app','ResumenEpicrisis','user','MostrarDatosIngreso',array('ingreso'=>$filaPacinte2['ingreso'],'ubicacion'=>$location));
                    $nombre_paciente = "<a href='$url_info_paciente'>$filaPacinte2[nombre_completo]</a>";
                    $this->salida .= "<tr class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>\n";
                    $imagenPaciente = "<img src=\"".GetThemePath()."/images/consulta_ur.png\" border=0 title='Ingresar y asignar cama al paciente.'>";
                    $this->salida .= "      <td>$imagenPaciente</td>\n";
                    $this->salida .= "      <td>$nombre_paciente</td>\n";
                    $this->salida .= "      <td align=\"right\">" . $this->GetDiasHospitalizacion($filaPacinte2['fecha_ingreso']) . "</td>\n";
                    $mostrar4=$reporte2->GetJavaReport_Epicrisis($filaPacinte2[ingreso],array());
                    $funcion3=$reporte2->GetJavaFunction();
                    $this->salida.=$mostrar4;
                    echo $mostrar4;
                    $this->salida .= "<td align=\"center\"><a href=\"javascript:$funcion3\"><img src=\"". GetThemePath() ."/images/imprimir.png\" border='0'></a></td>\n";
                    
										
										$epi = $this->GetDatosEpicrisis($filaPacinte2['ingreso']);
										if($epi)
										{

											$mostrarT=$reporte2->GetJavaReport('hc','Epicrisis','ReporteEpicrisis',array('ingreso'=>$filaPacinte2['ingreso'],'evolucion'=>$filaPacinte2['evolucion_id']),array('rpt_name'=>'Epicrisis'.$filaPacinte2['ingreso'],'rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
											$funcionT=$reporte2->GetJavaFunction();
											$this->salida .= "<td align=\"center\"><a href=\"javascript:$funcionT\"><img src=\"". GetThemePath() ."/images/imprimir.png\" border='0'></a></td>\n";
											$this->salida .= "$mostrarT";
										}
										else
											$this->salida .= "<td align=\"center\">&nbsp;</td>\n";
                    
										
										$AccionAutorizacion = ModuloGetURL('app','NCAutorizaciones','user','FormaConsultarAutorizaciones',array('ingreso'=>$filaPacinte['ingreso']));
                    $Autorizacion = $this->ReturnModuloExterno('app','NCAutorizaciones','user');
                    $Autorizacion->SetActionVolver($rutaVolver);
                    $Autorizacion->SetBuscador(false);
                    $this->salida .= "<td align=\"center\"><a href=\"$AccionAutorizacion\"><img src=\"". GetThemePath() ."/images/autorizadores.png\" border='0'></a></td>\n";
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
     * Forma para mostrar los datos de ingreso del paciente.
     *
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function MostrarDatosIngreso()
     {
          if(!$_REQUEST['ingreso'])
          {
               $url     = ModuloGetURL('app','ResumenEpicrisis','user','FormaMenus');
               $titulo  = "DATOS DEL PACIENTE";
               $mensaje = "Error : El metodo ";
     
               $this->FrmMSG($url, $titulo, $mensaje, $url_titulo);
     
               return true;
          }
     
          if(!$datosPaciente = $this->GetDatosPaciente($_REQUEST['ingreso']))
          {
     
               $url     = ModuloGetURL('app','ResumenEpicrisis','user','FormaMenus');
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
     
          if($_REQUEST['ubicacion'] == 'estacion')
          {
	          $link = ModuloGetURL('app','ResumenEpicrisis','user','FormaMenus',array('ubicacion'=>$_REQUEST['ubicacion']));
          }
          else
          {
          	$link=ModuloGetURL('app','ResumenEpicrisis','user','main',array());
          }
          $this->salida .= "<tr><td align=\"center\" colspan=\"2\"><a href=\"$link\"><b><sub>REGRESAR</sub></b></a></td></tr>\n";
          $this->salida .= "</table><br>\n";
          $this->salida .= themeCerrarTabla();
          return true;
     }// fin MostrarDatosIngreso


}//fin clase
?>

