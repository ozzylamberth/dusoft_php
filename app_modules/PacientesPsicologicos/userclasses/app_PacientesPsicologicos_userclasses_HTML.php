<?php

/**
 * $Id: app_Notas_y_Monitoreo_userclasses_HTML.php,v 1.11 2005/11/30 23:07:54 darling Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Contiene los metodos visuales para realizar las consultas de pacientes psicologicos.
 */


IncludeClass("EncuestaInicial", null, "hc", "EncuestaPaciente");
IncludeClass("EncuestaInicial_HTML", "html", "hc", "EncuestaPaciente");
class app_PacientesPsicologicos_userclasses_HTML extends app_PacientesPsicologicos_user
{

     function app_PacientesPsicologicos_user_HTML()
	{
          $this->salida='';
          $this->app_PacientesPsicologicos_user();
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
	* Menu de selecion.
     * FormaInicial()
	*/
	function FormaInicial()
	{
          $this->salida .= ThemeAbrirTabla('MENU DE CONSULTA DE PACIENTES');
          $this->Encabezado();
          $this->salida .= "            <br>";
          $this->salida .= "            <table width=\"40%\" border=\"0\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
          $this->salida .= "               <tr>";
          $this->salida .= "                  <td align=\"center\" class=\"modulo_table_list_title\">MENU DE SELECCION</td>";
          $this->salida .= "               </tr>";
          
          $this->salida .= "               <tr>";
          $accionF=ModuloGetURL('app','PacientesPsicologicos','user','FrmListadoPacientesPsicologicos');
          $this->salida .= "                  <td align=\"center\" class=\"modulo_list_claro\"><a title='Permite realizar las busquedas de las pruebas aplicadas' href=\"$accionF\">DILIGENCIAR ENCUESTA PACIENTE</a></td>";
          $this->salida .= "               </tr>";
          
          $this->salida .= "               <tr>";
          $accionH=ModuloGetURL('app','PacientesPsicologicos','user','FrmListadoPacientesProcesos');
          $this->salida .= "                  <td align=\"center\" class=\"modulo_list_claro\"><a title='Permite realizar las busquedas de las pruebas aplicadas' href=\"$accionH\">SEGUIMIENTO PSICOLOGICO</a></td>";
          $this->salida .= "               </tr>";
          
          $this->salida .= "           </table>";
          
          $accion=ModuloGetURL('app','PacientesPsicologicos','user','main');
          $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
          $this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"></p>";
          $this->salida .= "</form>";
          $this->salida .= ThemeCerrarTabla();
          return true;
	}
     
     
     /**
     * Forma para mostrar el listado de pacientes psicologia.
     *
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function FrmListadoPacientesPsicologicos()
     {
     	$this->salida .= ThemeAbrirTabla('PACIENTES PENDIENTES POR DILIGENCIAR ENCUESTA PSICOLOGICA');
          $this->Encabezado("ENCUESTA PACIENTE");
          $listadoPacientes = $this->GetPacientesPendienteEncuesta();
          if(!is_array($listadoPacientes))
          {
               $this->IncludeJS('javascripts/funciones.js', $contenedor='app', $modulo='EE_PanelEnfermeria');
          
               $this->salida .= "<br>\n";
               $this->salida .= "<table align=\"center\" width=\"80%\"  border=\"0\" >\n";
               $this->salida .= "  <tr class=\"modulo_table_title\">\n";
               $this->salida .= "      <td colspan='9' height='30'>PACIENTES PENDIENTES ENCUESTA INICIAL</td>\n";
               $this->salida .= "  </tr>\n";
               $this->salida .= "  <tr class=\"modulo_table_title\">\n";
               $this->salida .= "      <td width=\"15\">&nbsp;</td>\n";
               $this->salida .= "      <td align=\"center\">NOMBRE DEL PACIENTE</td>\n";
               $this->salida .= "      <td align=\"center\">IDENTIFICACION</td>\n";                              
               $this->salida .= "      <td align=\"center\">INGRESO</td>\n";               
               $this->salida .= "      <td align=\"center\">EVOLUCION</td>\n";
               $this->salida .= "      <td align=\"center\">DILIGENCIAR</td>\n";
               $this->salida .= "  </tr>\n";
                    
               $backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
               
               foreach($listadoPacientes as $k => $filaPacinte)
               {
                    if(!$filaPacinte['encuesta_id'])
                    {
                         if($p++ % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
                         $url_info_paciente = ModuloGetURL('app','PacientesPsicologicos','user','MostrarDatosIngreso',array('ingreso'=>$filaPacinte['ingreso']));
                         $nombre_paciente = "<a href='$url_info_paciente'>$filaPacinte[nombre]</a>";
                         $this->salida .= "<tr align=\"center\" class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>\n";
                         
                         $imagenPaciente = "<img src=\"".GetThemePath()."/images/proveedor.png\" border=0 title='Paciente Psicologico.'>";
                         $this->salida .= "      <td>$imagenPaciente</td>\n";
                         $this->salida .= "      <td align=\"left\">$nombre_paciente</td>\n";
                         $this->salida .= "      <td align=\"left\">".$filaPacinte[tipo_id_paciente]." - ".$filaPacinte[paciente_id]."</td>\n";
                         $this->salida .= "      <td align=\"center\">".$filaPacinte[ingreso]."</td>\n";
                         $this->salida .= "      <td align=\"center\">".$filaPacinte[evolucion_id]."</td>\n";
                         $href=ModuloGetURL('app','PacientesPsicologicos','user','LlamarFormaUpdateEncuesta',array('TipoId'=>$filaPacinte[tipo_id_paciente],'PacienteId'=>$filaPacinte[paciente_id],'ingreso'=>$filaPacinte[ingreso],'evolucion'=>$filaPacinte[evolucion_id]));
                         $this->salida .= "<td align=\"center\"><a href=\"$href\">Actualizar</a></td>\n";
                         $this->salida .= "</tr>\n";
                    }
               }
               $this->salida .= "  </table>\n";
          }
          else
          {
          	$this->salida .= "<br><div align=\"center\" class=\"label_mark\">NO HAY ENCUESTAS DE PACIENTES PARA DILIGENCIAR</div>";
          }
          $accion=ModuloGetURL('app','PacientesPsicologicos','user','FormaInicial');
          $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
          $this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"></p>";
          $this->salida .= "</form>";
          $this->salida .= ThemeCerrarTabla();
          return true;
     }
     
          
     function frmFormaUpdateEncuesta($ingreso, $evolucion, $tipo_pac, $paciente_id)
     {
     	$this->salida .= ThemeAbrirTabla('DILIGENCIAR ENCUESTA');
          $this->Encabezado("ENCUESTA PACIENTE");
          
          $accion=ModuloGetURL('app','PacientesPsicologicos','user','InsertarDatos_Encuesta', array('TipoId'=>$tipo_pac,'PacienteId'=>$paciente_id,'Ingreso'=>$ingreso,'Evolucion'=>$evolucion));
          $this->salida.= "	<form name=\"Encuesta\" id=\"Encuesta\" method=\"post\" action=\"$accion\">";          
          $this->salida.= "	<div id=\"EncuIni\">";          
          $this->salida.= "	<br><br><table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td class=\"modulo_table_list_title\">MOTIVO DE CONSULTA:</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td class=\"modulo_list_oscuro\" align=\"center\"><textarea name=\"motivo\" id=\"motivo\" class=\"input-text\" cols=\"100%\" rows=\"2\"></textarea></td>";          
          $this->salida.= "	</tr>";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td class=\"modulo_table_list_title\">OBJETIVOS INICIALES:</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td class=\"modulo_list_oscuro\" align=\"center\"><textarea name=\"objetivo\" id=\"objetivo\" class=\"input-text\" cols=\"100%\" rows=\"2\"></textarea></td>";          
          $this->salida.= "	</tr>";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td class=\"modulo_table_list_title\">COMPROMISOS</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td class=\"modulo_list_oscuro\" align=\"left\">";
          $this->salida.= "	<label class=\"label\">Asistir puntualmente a las sesiones:</label>&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"asistencia\" id=\"asistencia\" value=\"1\"><br>";
          $this->salida.= "	<label class=\"label\">Avisar previamente la cancelación de la cita si no puede asistir:</label>&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"avisos\" id=\"avisos\" value=\"1\"><br>";
          $this->salida.= "	<label class=\"label\">Despues de 3 citas consecutivas de no asistencia puedo quedar suspendido del proceso:</label>&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"suspension\" id=\"suspension\" value=\"1\"><br>";
          $this->salida.= "	<label class=\"label\">Me compromento a cumplir con las tareas y ejercicios propuestos durante el proceso:</label>&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"compromiso\" id=\"compromiso\" value=\"1\"><br>";
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td class=\"modulo_table_list_title\">¿ COMO SE SINTIO DURANTE LA ENTREVISTA ?</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td class=\"modulo_list_oscuro\" align=\"center\"><textarea name=\"concepto\" id=\"concepto\" class=\"input-text\" cols=\"100%\" rows=\"2\"></textarea></td>";          
          $this->salida.= "	</tr>";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td class=\"modulo_table_list_title\">OTROS:</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td class=\"modulo_list_oscuro\" align=\"center\"><textarea name=\"otros\" id=\"otros\" class=\"input-text\" cols=\"100%\" rows=\"2\"></textarea></td>";          
          $this->salida.= "	</tr>";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td align=\"center\" class=\"modulo_list_oscuro\"><input type=\"submit\" name=\"save_encuesta\" id=\"save_encuesta\" class=\"input-submit\" value=\"INSERTAR\"></td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	</table><BR>";
          $this->salida.= "</div>";
          $this->salida.= "</form>";
          $this->salida .= ThemeCerrarTabla();
     	
          return true;
     }

     
     function FrmViewEncuestaPaciente()
     {
     	print_r($_REQUEST);
          $this->salida .= ThemeAbrirTabla('DATOS DE ENCUESTA DILIGENCIADA');
          $this->Encabezado("ENCUESTA PACIENTE");
	     
          $this->salida.= "	<br><br><table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td class=\"modulo_table_title\">MOTIVO DE CONSULTA:</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td class=\"modulo_list_oscuro\" align=\"left\">".$_REQUEST['motivo']."</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td class=\"modulo_table_title\">OBJETIVOS INICIALES:</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td class=\"modulo_list_oscuro\" align=\"left\">".$_REQUEST['objetivo']."</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	<tr>";
          
          if($_REQUEST['asistencia'] == '1')
          { $asistencia = 'SI'; }else{ $asistencia = 'NO'; }

          if($_REQUEST['avisos'] == '1')
          { $avisos = 'SI'; }else{ $avisos = 'NO'; }

          if($_REQUEST['suspension'] == '1')
          { $suspension = 'SI'; }else{ $suspension = 'NO'; }

          if($_REQUEST['compromiso'] == '1')
          { $compromiso = 'SI'; }else{ $compromiso = 'NO'; }

          $this->salida.= "	<td class=\"modulo_table_title\">COMPROMISOS</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td class=\"modulo_list_oscuro\" align=\"left\">";
          $this->salida.= "	<label class=\"label\">Asistir puntualmente a las sesiones:</label>&nbsp;&nbsp;&nbsp;<label class=\"label_error\">".$asistencia."</label><br>";
          $this->salida.= "	<label class=\"label\">Avisar previamente la cancelación de la cita si no puede asistir:</label>&nbsp;&nbsp;&nbsp;<label class=\"label_error\">".$avisos."</label><br>";
          $this->salida.= "	<label class=\"label\">Despues de 3 citas consecutivas de no asistencia puedo quedar suspendido del proceso:</label>&nbsp;&nbsp;&nbsp;<label class=\"label_error\">".$suspension."</label><br>";
          $this->salida.= "	<label class=\"label\">Me compromento a cumplir con las tareas y ejercicios propuestos durante el proceso:</label>&nbsp;&nbsp;&nbsp;<label class=\"label_error\">".$compromiso."</label><br>";
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td class=\"modulo_table_title\">¿ COMO SE SINTIO DURANTE LA ENTREVISTA ?</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td class=\"modulo_list_oscuro\" align=\"left\">".$_REQUEST['concepto']."</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td class=\"modulo_table_title\">OTROS:</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td class=\"modulo_list_oscuro\" align=\"left\">".$_REQUEST['otros']."</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	</table><BR>";

          $accion=ModuloGetURL('app','PacientesPsicologicos','user','FrmListadoPacientesPsicologicos');
          $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
          $this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"></p>";
          $this->salida .= "</form>";
          $this->salida .= ThemeCerrarTabla();
               	
          return true;
     }

     /**
     * Forma para mostrar el listado de pacientes psicologia.
     *
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function FrmListadoPacientesProcesos()
     {
     	$this->salida .= ThemeAbrirTabla('PACIENTES EN PROCESOS DE SEGUIMIENTO PSICOLOGICO');
          $this->Encabezado("SEGUIMIENTO");
          $listadoPacientes = $this->GetPacientesProcesoSeguimiento();
          if($listadoPacientes)
          {
               $this->IncludeJS('javascripts/funciones.js', $contenedor='app', $modulo='EE_PanelEnfermeria');
          
               $this->salida .= "<br>\n";
               $this->salida .= "<table align=\"center\" width=\"80%\"  border=\"0\" >\n";
               $this->salida .= "  <tr class=\"modulo_table_title\">\n";
               $this->salida .= "      <td colspan='9' height='30'>LISTADO PACIENTES</td>\n";
               $this->salida .= "  </tr>\n";
               $this->salida .= "  <tr class=\"modulo_table_title\">\n";
               $this->salida .= "      <td width=\"15\">&nbsp;</td>\n";
               $this->salida .= "      <td align=\"center\">NOMBRE DEL PACIENTE</td>\n";
               $this->salida .= "      <td align=\"center\">IDENTIFICACION</td>\n";                              
               $this->salida .= "      <td align=\"center\">INGRESO</td>\n";               
               $this->salida .= "      <td align=\"center\">EVOLUCION</td>\n";
               $this->salida .= "      <td align=\"center\">Proceso Actual</td>\n";
               $this->salida .= "  </tr>\n";
                    
               $backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
               
               foreach($listadoPacientes as $k => $filaPacinte)
               {
                    if($p++ % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
                    $url_info_paciente = ModuloGetURL('app','PacientesPsicologicos','user','MostrarDatosIngreso',array('ingreso'=>$filaPacinte['ingreso']));
                    $nombre_paciente = "<a href='$url_info_paciente'>$filaPacinte[nombre]</a>";
                    $this->salida .= "<tr align=\"center\" class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>\n";
                    
                    $imagenPaciente = "<img src=\"".GetThemePath()."/images/proveedor.png\" border=0 title='Paciente Psicologico.'>";
                    $this->salida .= "      <td>$imagenPaciente</td>\n";
                    $this->salida .= "      <td align=\"left\">$nombre_paciente</td>\n";
                    $this->salida .= "      <td align=\"left\">".$filaPacinte[tipo_id_paciente]." - ".$filaPacinte[paciente_id]."</td>\n";
                    $this->salida .= "      <td align=\"center\">".$filaPacinte[ingreso]."</td>\n";
                    $this->salida .= "      <td align=\"center\">".$filaPacinte[evolucion_id]."</td>\n";
                    $href=ModuloGetURL('app','PacientesPsicologicos','user','FrmConsultasSeguimiento',array('TipoId'=>$filaPacinte[tipo_id_paciente],'PacienteId'=>$filaPacinte[paciente_id],'ingreso'=>$filaPacinte[ingreso],'evolucion'=>$filaPacinte[evolucion_id], 'sesion_id'=>$filaPacinte[sesion_id]));
                    $this->salida .= "<td align=\"center\"><a href=\"$href\">Ver</a></td>\n";
                    $this->salida .= "</tr>\n";
               }
               $this->salida .= "  </table>\n";
          }
          else
          {
          	$this->salida .= "<br><div align=\"center\" class=\"label_mark\">NO HAY PACIENTES CON PROCESOS DE SEGUIMIENTO</div>";
          }
          $accion=ModuloGetURL('app','PacientesPsicologicos','user','FormaInicial');
          $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
          $this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"></p>";
          $this->salida .= "</form>";
          $this->salida .= ThemeCerrarTabla();
          return true;
     }
     
     function FrmConsultasSeguimiento()
     {
     	if($_REQUEST['evolucion'])
          { $titulo = "PROCESO DE SEGUIMIENTO ACTUAL"; }else
          { $titulo = "PROCESOS DE SEGUIMIENTO"; }

          if($_REQUEST['evolucion'])
          { $Evolucion_Para_Modulo = $_REQUEST['evolucion']; }else
          { $Evolucion_Para_Modulo = $_REQUEST['evolucion_rec']; }
                    
          $this->salida = ThemeAbrirTabla($titulo);

          $this->Encabezado($titulo);
          
          $sesion_id = $_REQUEST['sesion_id'];
          $listado = $this->GetEvolucionesProceso($sesion_id);
          
          $this->salida .= "<br>\n";
          $this->salida .= "<table align=\"center\" width=\"80%\"  border=\"0\" >\n";
          $this->salida .= "  <tr class=\"modulo_table_title\">\n";
          $this->salida .= "      <td colspan='9' height='30'>LISTADO EVOLUCIONES SEGUMIENTO</td>\n";
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr class=\"modulo_table_title\">\n";
          $this->salida .= "      <td align=\"center\">INGRESO</td>\n";               
          $this->salida .= "      <td align=\"center\">EVOLUCION</td>\n";
          $this->salida .= "  </tr>\n";
               
          $backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
          
          foreach($listado as $k => $filaPacinte)
          {
               if($p++ % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
               $this->salida .= "<tr align=\"center\" class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>\n";
               $href=ModuloGetURL('app','PacientesPsicologicos','user','FrmConsultasSeguimiento',array('TipoId'=>$_REQUEST['TipoId'],'PacienteId'=>$_REQUEST['PacienteId'],
               																	   'ingreso'=>$filaPacinte[ingreso],'evolucion_rec'=>$filaPacinte[evolucion_id],'sesion_id'=>$sesion_id));
               $this->salida .= "      <td align=\"center\">".$filaPacinte[ingreso]."</td>\n";
               $this->salida .= "      <td align=\"center\"><a href=\"$href\">".$filaPacinte[evolucion_id]."</a></td>\n";
               $this->salida .= "</tr>\n";
          }
          $this->salida .= "  </table>\n";

          $accionHIS=ModuloHCGetURL($Evolucion_Para_Modulo,'','','','');
          $this->salida .= "<br><center><div class='label_mark'><font size=\"3\">INFORMACION $titulo</font></div></center>";
          $this->salida .= "<br><center><div><IFRAME border=\"0\" width=\"79%\" align=\"center\" height=\"600\" SRC='$accionHIS'>";
          $this->salida .= "</IFRAME></div></center>";
          
          // Retorno
          $actionM=ModuloGetURL('app','PacientesPsicologicos','user','FrmListadoPacientesProcesos');
          $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
          $this->salida .= "<br><table align=\"center\"><tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Volver\" value=\"VOLVER\">";
          $this->salida .= "</td></tr></table></form>";

          $this->salida.=ThemeCerrarTabla();
          return true;
     }

          
     /**
     * Forma para mostrar los datos de ingreso del paciente.
     *
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function MostrarDatosIngreso()
     {
     	$datosPaciente = $this->GetDatosPaciente($_REQUEST['ingreso']);
     
          $ContactosPaciente = $this->GetContactosPaciente($_REQUEST['ingreso']);
     
          $this->salida .= ThemeAbrirTabla('INFORMACION DEL PACIENTE','60%');//[ '.$datos_estacion[descripcion5].' ] -
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
          $link=ModuloGetURL('app','PacientesPsicologicos','user','FrmListadoPacientesPsicologicos');
          $this->salida .= "<tr><td align=\"center\" colspan=\"2\"><a href=\"$link\"><b><sub>REGRESAR</sub></b></a></td></tr>\n";
          $this->salida .= "</table><br>\n";
          $this->salida .= ThemeCerrarTabla();
          return true;
     }// fin MostrarDatosIngreso

     
	/**
	*
	*/
//	function FormaInicial()
	//{	
  //   	$this->FrmListadoPacientesProcesos();
// /*          if(empty($_SESSION['NYM']['EMPRESA_ID']))
//           {
//                $_SESSION['NYM']['EMPRESA_ID']=$_REQUEST['Monitoreo']['empresa_id'];
//                $_SESSION['NYM']['EMPRESA']=$_REQUEST['Monitoreo']['razon_social'];
//           }
// 
//           $this->salida .= ThemeAbrirTabla('ESTADISTICAS DE HISTORIAS CLINICAS');
//           $titulo = 'ESTADISTICAS DE HISTORIAS CLINICAS';
//           $this->Encabezado($titulo);
//           $this->salida .= "<br>";
//           $this->salida .= "<table width=\"100%\" border=\"0\" align=\"center\">";
//           $this->salida .= "<tr>";
//           $this->salida .= "<td width=\"50%\"  colspan=\"2\">";
//           
//           /**************************  NOTAS DE AUDITORIA *************************/
//           $this->salida .= "<fieldset><legend class=\"field\">NOTAS DE AUDITORIA</legend>\n";
//           $this->salida .= "<table width=\"100%\" border=\"0\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
//           $this->salida .= "<tr>";
//           $this->salida .= "<td   align=\"center\" class=\"modulo_table_title\">NOTAS</td>";
//           $this->salida .= "</tr>";
//           $this->salida .= "</table>";
//           
//           $accionNA=ModuloGetURL('app','Notas_y_Monitoreo','user','MisNotas_Auditoria');
//           $this->salida .= "<IFRAME border=\"0\" width=\"100%\" height=\"210\" SRC='$accionNA'>";
//           $this->salida .= "</IFRAME>";
//           
//           $this->salida .= "</fieldset><br>";
//           /**************************  NOTAS DE AUDITORIA *************************/
//           
//           $this->salida .= "</td>";
// 					$this->salida .= "</tr>"; 
//           $this->salida .= "<tr>";					
//           $this->salida .= "<td width=\"50%\" colspan=\"2\">";
// 		
//           /************************  HISTORIAS MONITORIZADAS ***********************/
//           $this->salida .= "<fieldset><legend class=\"field\">MIS HISTORIAS MONITORIZADAS</legend>\n";
//           $this->salida .= "<table width=\"100%\" border=\"0\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
//           $this->salida .= "<tr>";
//           $this->salida .= "<td align=\"center\" class=\"modulo_table_title\">HISTORIAS</td>";
//           $this->salida .= "</tr>";
//           $this->salida .= "</table>";
//           
//           $accionMIS=ModuloGetURL('app','Notas_y_Monitoreo','user','MisHistorias_Monitoreadas');
//           $this->salida .= "<IFRAME border=\"0\" width=\"100%\" height=\"210\" SRC='$accionMIS'>";
//           $this->salida .= "</IFRAME>";
// 
//           $this->salida .= "</fieldset><br>";
//           /************************  HISTORIAS MONITORIZADAS ***********************/
//           
//           $this->salida .= "</td>"; 
//           $this->salida .= "</tr>";        
//           $this->salida .= "<tr>";
//           $this->salida .= "<td width=\"50%\">";
// 
//           /************************** ULTIMAS ATENCIONES ***********************/
//           //$atenciones = $this->Get_UltimasAtenciones_X_Servicios();
//           $this->salida .= "<fieldset><legend class=\"field\">ULTIMAS ATENCIONES</legend>\n";
//           $this->salida .= "<table width=\"100%\" border=\"0\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
//           $this->salida .= "<tr>";
//           $this->salida .= "<td align=\"center\" width=\"100%\" colspan=\"4\" class=\"modulo_table_title\">ATENCIONES POR SERVICIO</td>";
//           $this->salida .= "</tr>";
//           $this->salida .= "</table>";
// 
//           $accionI=ModuloGetURL('app','Notas_y_Monitoreo','user','Atenciones_X_Servicio');
//           $this->salida .= "<IFRAME border=\"0\" width=\"100%\" height=\"295\" SRC='$accionI'>";
//           $this->salida .= "</IFRAME>";
//           
//           $this->salida .= "<center><div class=\"label\">&nbsp;</div></center>";
//           $this->salida .= "</fieldset><br>";
//  		/************************** ULTIMAS ATENCIONES ***********************/
//          
//           $this->salida .= "</td>";
//           $this->salida .= "<td width=\"50%\" >";
// 
//           /****************************** BUSCADOR ****************************/
//           $this->salida .= "<fieldset alignv=\"top\"><legend class=\"field\">BUSCADOR</legend>\n";
//           $this->salida .= "<table width=\"100%\" alignv=\"top\" border=\"0\" cellspacing=\"3\" cellpadding=\"3\">";
//           $this->salida .= "<tr>";
//           $this->salida .= "<td align=\"center\" colspan=\"3\" class=\"modulo_table_title\">OPCIONES</td>";
//           $this->salida .= "</tr>";
//                     
//           $mostrar ="\n<script language='javascript'>\n";
//           $mostrar.="  function limpiar(){\n";
//           $mostrar.="  document.data.nombres.value='';\n";
//           $mostrar.="  document.data.Documento.value='';\n";
//        
//           $mostrar.="  document.data.evolucion.value='';\n";
//           $mostrar.="  document.data.ingreso.value='';\n";
//           $mostrar.="  document.data.cuenta.value='';\n";
//           $mostrar.="  document.data.prefijo.value='';\n";
//           $mostrar.="  document.data.factura.value='';\n";
//           
//           $mostrar.="  };\n";
//           $mostrar.="</script>\n";
//           $this->salida .="$mostrar";
//  
//           $accion=ModuloGetURL('app','Notas_y_Monitoreo','user','BuscarOrden');
//           $this->salida .= "<form name=\"data\" action=\"$accion\" method=\"post\">";
//           //
//           $this->salida .= "<tr><td class=\"label\">TIPO DOCUMENTO: </td><td><br><select name=\"TipoDocumento\" class=\"select\">";
//           $this->salida .= "<option value=-1 selected>--  SELECCIONE --</option>";
//           
//           $tipo_id=$this->tipo_id_paciente();
//           $this->BuscarIdPaciente($tipo_id,$_REQUEST['TipoDocumento']);
//           $this->salida .= "</select></td></tr>";
// 
//           $this->salida .= "<tr><td class=\"label\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" value=".$_REQUEST['Documento']."></td></tr>";
//           $this->salida .= "<tr><td class=\"label\">NOMBRES</td><td><input type=\"text\" class=\"input-text\" name=\"nombres\" maxlength=\"32\" value=".$_REQUEST['nombres']."></td></tr>";
// 
//           //BUSQUEDA POR SERVICIO          
//           $this->salida .= "<tr><td class=\"label\">SERVICIO: </td><td><select name=\"servicio\" class=\"select\">";
//           $this->salida .= "<option value=-1 selected>--  SELECCIONE --</option>";
//           $vector=$this->Get_Servicios();
//           $this->GetHtmlServicio($vector,$_REQUEST['servicio']);
//           $this->salida .= "                  </select></td></tr>";
// 		//BUSQUEDA POR SERVICIO
//           
//           //BUSQUEDA POR FECHA
//           $this->salida .= "<tr><td class=\"label\">FECHA</td>";
//           $this->salida .= "<td align=\"left\" class=\"label\">DESDE&nbsp;<input type=\"text\" class=\"input-text\" name=\"fechaini\" size='11' maxlength=\"10\" READONLY value=\"".$_REQUEST['fechaini']."\"><sub>".ReturnOpenCalendario('data','fechaini','-')."</sub>";
//           $this->salida .= "</tr>";
//           $this->salida .= "<tr>";
//           $this->salida .= "<td align=\"left\">&nbsp;</td>";
//           $this->salida .= "<td align=\"left\" class=\"label\">HASTA&nbsp;<input type=\"text\" class=\"input-text\" name=\"fechafin\" size='11' maxlength=\"10\" READONLY value=\"".$_REQUEST['fechafin']."\"><sub>".ReturnOpenCalendario('data','fechafin','-')."</sub></label></td></tr>";
//           //BUSQUEDA POR FECHA
//           
//           $mostrar2 ="\n<script language='javascript'>\n";
//           $mostrar2.="  function cambioHTML(obj){\n";
//           $mostrar2.="  if(obj.selectedIndex==1)\n";
//           $mostrar2.="  {document.getElementById('cambio').innerHTML=\"<input type='text' class='input-text' OnChange='document.data.evo_oculto.value=this.value' name='evolucion' value=''>\";}\n";
//           
//           $mostrar2.="  else if(obj.selectedIndex==2)\n";
//           $mostrar2.="  {document.getElementById('cambio').innerHTML=\"<input type='text' class='input-text' OnChange='document.data.ing_oculto.value=this.value' name='ingreso' value=''>\"}\n";
// 
//           $mostrar2.="  else if(obj.selectedIndex==3)\n";
//           $mostrar2.="  {document.getElementById('cambio').innerHTML=\"<input type='text' class='input-text' OnChange='document.data.cuenta_oculto.value=this.value' name='cuenta' value=''>\"}\n";
//          
//           $mostrar2.="  else if(obj.selectedIndex==4)\n";
//           $mostrar2.="  {document.getElementById('cambio').innerHTML=\"<input type='text' class='input-text' OnChange='document.data.pre_oculto.value=this.value' size='4' name=prefijo' value=''> -- <input type='text' class='input-text' OnChange='document.data.fac_oculto.value=this.value' name='factura' value=''>\"}\n";
//           $mostrar2.="  };\n";
// 
//           $mostrar2.="</script>\n";
//           $this->salida .="$mostrar2";
//           
//           $this->salida .= "<input type=\"hidden\" name=\"evo_oculto\" value=\"\" class=\"input-text\">";
//           $this->salida .= "<input type=\"hidden\" name=\"ing_oculto\" value=\"\" class=\"input-text\">";
//           $this->salida .= "<input type=\"hidden\" name=\"cuenta_oculto\" value=\"\" class=\"input-text\">";
//           $this->salida .= "<input type=\"hidden\" name=\"pre_oculto\" value=\"\" class=\"input-text\">";
// 		$this->salida .= "<input type=\"hidden\" name=\"fac_oculto\" value=\"\" class=\"input-text\">";
//           
//           $this->salida .= "<tr><td class=\"label\">OPCION BUSQUEDA: </td><td><select name=\"parametros\" class=\"select\" OnChange=\"cambioHTML(this);\">";
//           $this->salida .= "<option value=-1 selected>--  SELECCIONE --</option>";
//           $this->salida .= "<option value=\"1\"> EVOLUCION</option>";
//           $this->salida .= "<option value=\"2\"> INGRESO</option>";
//           $this->salida .= "<option value=\"3\"> CUENTA</option>";
//           $this->salida .= "<option value=\"4\"> FACTURA</option>";
//           $this->salida .= "</select></td></tr>";
//           $this->salida .= "<tr><td>&nbsp;</td><td colspan=\"2\" class=\"label\"><div id=\"cambio\" name=\"valor\"></div></td>";
//           $this->salida .= "</tr>";
//           $this->salida .= "<tr><td align='center' colspan=\"$col\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSQUEDA\"></td>";
//           $this->salida .= "</form>";
//           
//           $this->salida .= "<td align=\"left\"><input class=\"input-submit\" type=\"button\" name=\"Buscar\" value=\"BORRAR CASILLAS\" onclick='limpiar();'></td>";
//           $this->salida .= "</tr>";
//           //
//           $this->salida .= "</table>";
//           $this->salida .= "</fieldset><br>";
//      	/****************************** BUSCADOR ****************************/
//                     
//           $this->salida .= "</td>";
//           $this->salida .= "</tr>";
//           $this->salida .= "</table>";
//           
//           $accion=ModuloGetURL('app','Notas_y_Monitoreo','user','main');
//           $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
//           $this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"></p>";
//           $this->salida .= "</form>";
//           $this->salida .= ThemeCerrarTabla();
          // return true;
 //	}
//      
//      
//      function MisNotas_Auditoria()
//      {
//           $Mis_notas = $this->Get_NotasAuditoria_Asignadas();
//                     
//           $mostrar ="\n<script language='javascript'>\n";
//           $mostrar.="function mOvr(src,clrOver) {;\n";
//           $mostrar.="src.style.background = clrOver;\n";
//           $mostrar.="}\n";
// 
//           $mostrar.="function mOut(src,clrIn) {\n";
//           $mostrar.="src.style.background = clrIn;\n";
//           $mostrar.="}\n";
//           $mostrar.="</script>\n";
// 
//           $backgrounds=array('modulo_list_claro','modulo_list_oscuro');
// 
//           $this->salida .="$mostrar";
//      	
//           if(!empty($Mis_notas))
//           {
//                $this->salida .= "<table width=\"100%\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\">";
//                $this->salida .= "<tr>";
//                $this->salida .= "<td align=\"center\" width=\"7%\" class=\"modulo_table_title\">Fecha.</td>";
//                $this->salida .= "<td align=\"center\" width=\"7%\" class=\"modulo_table_title\">Prioridad.</td>";
//                $this->salida .= "<td align=\"center\" width=\"70%\" class=\"modulo_table_title\">Nota de Auditoria.</td>";
//                $this->salida .= "<td align=\"center\" width=\"7%\" class=\"modulo_table_title\">Ver.</td>";
//                $this->salida .= "</tr>";
//                for ($i=0; $i<sizeof($Mis_notas);$i++)
//                {
//                     if($Mis_notas[$i][nota_auditoria_id] != $Mis_notas[$i-1][nota_auditoria_id])
//                     {
//                          if( $i % 2){ $estilo='modulo_list_claro';}
//                               else {$estilo='modulo_list_claro';}
//           
//                          $this->salida .= "<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#FFFFFF');>";
//                          
//                          $accionVer = "<a href=\"".ModuloGetURL('app','Notas_y_Monitoreo','user','Informacion_NotaAuditoria',array('ingreso'=>$Mis_notas[$i][ingreso],'evolucion'=>$Mis_notas[$i][evolucion_id],'hc_evolucion'=>$Mis_notas[$i][hc_evolucion],'nota_auditoria_id'=>$Mis_notas[$i][nota_auditoria_id],'nombre'=>$Mis_notas[$i][nombre],'paciente_id'=>$Mis_notas[$i][paciente_id],'tipo_id_paciente'=>$Mis_notas[$i][tipo_id_paciente])) ."\" target=\"Contenido\"><img src=\"". GetThemePath() ."/images/Listado.png\" border='0' width='17' height='17' title=\"Ver información de la nota de auditoria.\"></a>";
//                          
//                          $fecha = explode(' ', $Mis_notas[$i][fecha_registro]);
//                          $this->salida .= "<td align=\"center\" width=\"10%\">".$fecha[0]."</td>";
//      
//                          if($Mis_notas[$i][sw_prioridad] == '0')
//                          { $prioridad = "<img src=\"". GetThemePath() ."/images/baja.png\" border=\"0\" title=\"Prioridad Baja\">"; }
//                          elseif($Mis_notas[$i][sw_prioridad] == '1')
//                          { $prioridad = "<img src=\"". GetThemePath() ."/images/media.png\" border=\"0\" title=\"Prioridad Media\">"; }
//                          elseif($Mis_notas[$i][sw_prioridad] == '2')
//                          { $prioridad = "<img src=\"". GetThemePath() ."/images/alta.png\" border=\"0\" title=\"Prioridad Alta\">"; }
//      
//                          $this->salida .= "<td align=\"center\" width=\"10%\">".$prioridad."</td>";
// 												 if($Mis_notas[$i][sw_responder] == 1)
// 												 {  $estilo='label_mark';  }
//                          $this->salida .= "<td align=\"left\" width=\"70%\" class=\"$estilo\">".$Mis_notas[$i][nota]."</td>";
//                          $this->salida .= "<td align=\"center\" width=\"10%\">".$accionVer."</td>";
//                          $this->salida .= "</tr>";
//                     }
//                }
//                $this->salida .= "</table>";
//           }
//           else
//           {
//                $this->salida.="<table width=\"100%\" border=\"0\" align=\"center\">";
// 			$this->salida.="<tr align=\"center\"><br><td><label class='label_mark'>EN EL MOMENTO USTED NO TIENE NOTAS DE AUDITORIA</label>";
// 			$this->salida.="</td></tr>";
// 			$this->salida.="</table>";
// 			return true;
//           }*/
//          return true;
 //    }
     
          
     function Informacion_NotaAuditoria()
     {
					$nota_auditoria_id = $_REQUEST['nota_auditoria_id'];
					$nombre = $_REQUEST['nombre'];
					$paciente_id = $_REQUEST['paciente_id'];
					$tipo_id_paciente = $_REQUEST['tipo_id_paciente'];
					$ingreso = $_REQUEST['ingreso'];
					$evolucion = $_REQUEST['evolucion'];
					$hc_evolucion = $_REQUEST['hc_evolucion'];
					
					$this->salida = ThemeAbrirTabla('INFORMACION');
					$titulo = "NOTA DE AUDITORIA";
					$this->Encabezado($titulo);
		
					$info_nota = $this->GetInformacion_NotaAuditoria($nota_auditoria_id);
					$info_respuesta = $this->GetRespuesta_NotaAuditoria($nota_auditoria_id);
										
					$actionM=ModuloGetURL('app','Notas_y_Monitoreo','user','InsertarRespuesta_NotaAuditoria',array('nota_auditoria_id'=>$nota_auditoria_id,
					'nombre'=>$nombre, 'paciente_id'=>$paciente_id, 'tipo_id_paciente'=>$tipo_id_paciente,
					'ingreso'=>$ingreso, 'evolucion'=>$evolucion, 'hc_evolucion'=>$hc_evolucion));
					
					$this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
					
					$this->salida.="<br><br><table border=\"0\" align=\"center\"  width=\"100%\">";
					$this->salida .= $this->SetStyle("MensajeError");
					$this->salida.="</table>";

					/*DIALOGO DE NOTAS Y RESPUESTAS*/
					$this->salida .= "<br><table width=\"80%\" border=\"0\" class=\"modulo_list_oscuro\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\">";
					$this->salida .= "<tr>";
					$this->salida .= "<td colspan=\"4\" align=\"center\" width=\"100%\" class=\"modulo_table_title\">";
					$this->salida .= "PACIENTE";
					$this->salida .= "</td>";
					$this->salida .= "</tr>";
					$this->salida .= "<tr>";
					$this->salida .= "<td colspan=\"2\" width=\"50%\" class=\"hc_table_submodulo_list_title\">DATOS PACIENTE:</td>";
					$this->salida .= "<td colspan=\"2\" align=\"justify\" width=\"50%\" class=\"modulo_list_claro\"><b>".$tipo_id_paciente." ".$paciente_id."  -  ".$nombre."</b></td>";
					$this->salida .= "</tr>";													
					$this->salida .= "<tr>";
					$this->salida .= "<td colspan=\"4\" align=\"center\" width=\"100%\" class=\"modulo_table_title\">NOTA DE LA AUDITORIA</td>";
					$this->salida .= "</tr>";						
					$this->salida .= "</tr>";
					$this->salida .= "<td colspan=\"4\" align=\"justify\" width=\"100%\" class=\"modulo_list_claro\"><b>NOTA: </b><br>".$info_nota[0][nota]."</td>";
					$this->salida .= "</tr>";						
					$this->salida .= "</tr>";
					$this->salida .= "<td colspan=\"4\" align=\"justify\" width=\"100%\" class=\"modulo_list_claro\">&nbsp;</td>";
					$this->salida .= "</tr>";
					$this->salida .= "<tr>";
					$this->salida .= "<td colspan=\"4\" width=\"100%\" class=\"modulo_table_title\">DATOS DE LA NOTA DE AUDITORIA</td>";
					$this->salida .= "</tr>";
					$this->salida .= "<tr>";
					$this->salida .= "<td colspan=\"2\" width=\"50%\" class=\"hc_table_submodulo_list_title\">FECHA Y HORA DE REGISTRO: </td>";
					$this->salida .= "<td colspan=\"2\" width=\"50%\" class=\"hc_table_submodulo_list_title\">HISTORIA CLINICA: </td>";
					$this->salida .= "</tr>";						
					$this->salida .= "<tr>";
					$fecha = explode(' ', $info_nota[0][fecha_registro]);
					$hora = explode(':', $fecha[1]);
					$this->salida .= "<td colspan=\"2\" align=\"center\" width=\"50%\" class=\"modulo_list_claro\"><b>".$fecha[0]." a las,  ".$hora[0]." : ".$hora[1]."</b></td>";
					if(!empty($v[evolucion_id]))
					{
					$dato_evolucion = "y la Evolución: <b>".$info_nota[$i][evolucion_id]."</b>";
					}else
					{
					$dato_evolucion = "&nbsp;";               
					}
					$this->salida .= "<td colspan=\"2\" align=\"center\" width=\"50%\" class=\"modulo_list_claro\">Del Ingreso: <b>".$info_nota[0][ingreso]."</b>&nbsp;&nbsp;&nbsp;&nbsp;".$dato_evolucion."</td>";
					$this->salida .= "</tr>";
					
					$this->salida .= "<tr>";
					$this->salida .= "<td width=\"25%\" class=\"hc_table_submodulo_list_title\">PRIVACIDAD</td>";
					if($info_nota[0][sw_privada] == '0')
					{ $this->salida .= "<td width=\"25%\" class=\"modulo_list_claro\">AUDITOR</td>"; }
					elseif($info_nota[0][sw_privada] == '1')
					{ $this->salida .= "<td width=\"25%\" class=\"modulo_list_claro\">AUDITORES Y MEDICOS</td>"; }
					elseif($info_nota[0][sw_privada] == '2')
					{ $this->salida .= "<td width=\"25%\" class=\"modulo_list_claro\">AUDITOR EXTERNO</td>"; }
					elseif($info_nota[0][sw_privada] == '3')
					{ $this->salida .= "<td width=\"25%\" class=\"modulo_list_claro\">AUDITOR INTERNO</td>"; }
					
					$this->salida .= "<td width=\"25%\" class=\"hc_table_submodulo_list_title\">PRIORIDAD</td>";
					if($info_nota[0][sw_prioridad] == '0')
					{ $this->salida .= "<td width=\"25%\" align=\"center\" class=\"modulo_list_claro\"><b>BAJA</b></td>"; }
					elseif($info_nota[0][sw_prioridad] == '1')
					{ $this->salida .= "<td width=\"25%\" align=\"center\" class=\"modulo_list_claro\"><b>MEDIA</b></td>"; }
					elseif($info_nota[0][sw_prioridad] == '2')
					{ $this->salida .= "<td width=\"25%\" align=\"center\" class=\"modulo_list_claro\"><b>ALTA</b></td>"; }
					$this->salida .= "</tr>";						
					if($info_nota[0][sw_tipo_auditor]=='1')
					{ $auditor = 'AUDITOR INTERNO A CARGO: ';
					}else
					{ $auditor = 'AUDITOR EXTERNO A CARGO: '; }						
					$USR = $this->TraerUsuario($info_nota[0][usuario_id]);
					$this->salida .= "<tr>";
					$this->salida .= "<td colspan=\"4\" width=\"100%\" class=\"modulo_table_title\">AUDITOR</td>";
					$this->salida .= "</tr>";						
					$this->salida .= "<tr>";
					$this->salida .= "<td colspan=\"2\" width=\"50%\" class=\"hc_table_submodulo_list_title\">".$auditor."</td>";
					$this->salida .= "<td colspan=\"2\" width=\"50%\" class=\"modulo_list_claro\"><b>".strtoupper($USR[nombre])."</b></td>";
					$this->salida .= "</tr>";						
					$this->salida .= "<tr>";
					$this->salida .= "<td colspan=\"4\" width=\"100%\" class=\"modulo_table_title\">CAUSA</td>";
					$this->salida .= "</tr>";						
					$this->salida .= "<tr>";
					$this->salida .= "<td colspan=\"2\" width=\"50%\" class=\"hc_table_submodulo_list_title\">DESCRIPCION DEL TIPO DE AUDITORIA: </td>";
					$this->salida .= "<td colspan=\"2\" align=\"justify\" width=\"50%\" class=\"modulo_list_claro\">";
					for($i=0; $i<sizeof($info_nota); $i++)
					{
							$this->salida .= "<li><div>".$info_nota[$i][descripcion_tipo_nota]."</div></li>";
					}
					$this->salida .= "</td>";
					$this->salida .= "</tr>";	                
					$this->salida .= "<tr>";
					$this->salida .= "<td colspan=\"4\" align=\"center\" width=\"100%\" class=\"modulo_table_title\">RESPONDER</td>";
					$this->salida .= "</tr>";
					$this->salida .= "<tr>";
					$this->salida .= "<td colspan=\"4\" align=\"center\" width=\"100%\" class=\"hc_table_submodulo_list_title\">";
					$this->salida .= "<textarea name=\"respuesta\" rows=\"7\" style=\"width:100%\" class=\"textarea\"></textarea>";
					$this->salida .= "</td>";
					$this->salida .= "</tr>";
					$this->salida .= "<tr>";
					$this->salida .= "<td colspan=\"4\" align=\"center\" width=\"100%\" class=\"modulo_list_claro\">";
					$this->salida .= "<input type=\"submit\" class=\"input-submit\" name=\"responder\" value=\"RESPONDER\">";
					$this->salida .= "</td>";
					$this->salida .= "</tr>";     
					$this->salida .= "</tr>";
					$this->salida .= "<td colspan=\"4\" align=\"justify\" width=\"100%\" class=\"modulo_list_claro\">&nbsp;</td>";
					$this->salida .= "</tr>";
					$this->salida .= "<tr>";
					$this->salida .= "<td colspan=\"4\" width=\"100%\" class=\"modulo_table_title\">RESPUESTAS</td>";
					$this->salida .= "</tr>";
					foreach ($info_respuesta as $k2 => $v1)
					{
								$USR = $this->TraerUsuario($v1[usuario_id]);
								$this->salida .= "<tr>";
								if($v1[sw_tipo_usuario] == '1')
								{
									$this->salida .= "<td colspan=\"2\" width=\"50%\" class=\"hc_table_submodulo_list_title\">PROFESIONAL</td>";               
								}else
								{
									$this->salida .= "<td colspan=\"2\" width=\"50%\" align=\"center\" class=\"modulo_table\"><b>AUDITOR</b></td>";                              
								}
								$this->salida .= "<td colspan=\"2\" width=\"50%\" class=\"modulo_list_claro\"><b>".strtoupper($USR[nombre])."</b></td>";
								$this->salida .= "</tr>";
								
								$this->salida .= "<tr>";
								$fecha1 = explode(' ', $v1[fecha_registro]);
								$hora1 = explode(':', $fecha1[1]);
								if($v1[sw_tipo_usuario] == '1')
								{	
								$estilo='modulo_list_claro';
									$this->salida .= "<td colspan=\"2\" width=\"50%\" class=\"hc_table_submodulo_list_title\">FECHA Y HORA DE REGISTRO: </td>";
								}else
								{
								$estilo='modulo_list_oscuro';
										$this->salida .= "<td colspan=\"2\" width=\"50%\" align=\"center\" class=\"modulo_table\"><b>FECHA Y HORA DE REGISTRO: </b></td>";
								}
								$this->salida .= "<td colspan=\"2\" align=\"center\" width=\"50%\" class=\"modulo_list_claro\">".$fecha1[0]." a las,  ".$hora1[0]." : ".$hora1[1]."</td>";
								$this->salida .= "</tr>";
								
								$this->salida .= "</tr>";
								$this->salida .= "<td colspan=\"4\" align=\"justify\" width=\"100%\" class=\"$estilo\"><b>NOTA: </b><br>".$v1[respuesta]."</td>";
								$this->salida .= "</tr>";
					}
	
					$this->salida .= "</table>";
					$this->salida .= "</form>";
 					/*DIALOGO DE NOTAS Y RESPUESTAS*/          
          $actionM=ModuloGetURL('app','Notas_y_Monitoreo','user','FormaInicial');
          $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
          $this->salida .= "<br><table align=\"center\"><tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Volver\" value=\"VOLVER\">";
          $this->salida .= "</td></tr></table></form>";  
          /*INSERTAR NOTA MEDICA*/
          if(!empty($evolucion))
          {
          	$hc_evolucion = $evolucion;
          	$accionHIS=ModuloHCGetURL($evolucion,'','','','');
          }elseif(!empty($hc_evolucion))
          {
          	$accionHIS=ModuloHCGetURL($hc_evolucion,'','','','');          
          }else
          {
          	$hc_evolucion = $_REQUEST['Evolucion_Para_Modulo'];
          	$accionHIS=ModuloHCGetURL($hc_evolucion,'','','','');          
          }
          
          $this->salida .= "<br><center><div class='label_mark'><font size=\"3\">HISTORIA CLINICA</font></div></center>";
          $this->salida .= "<br><center><div><IFRAME border=\"0\" width=\"79%\" align=\"center\" height=\"600\" SRC='$accionHIS'>";
          $this->salida .= "</IFRAME></div></center>";

          $actionM=ModuloGetURL('app','Notas_y_Monitoreo','user','InsertNotaMedica',array('ingreso'=>$ingreso,'evolucion_id'=>$evolucion,
          'Evolucion_Para_Modulo'=>$hc_evolucion,'forma_volver'=>'Ok','nota_auditoria_id'=>$nota_auditoria_id,'nombre'=>$nombre,
          'paciente_id'=>$paciente_id,'tipo_id_paciente'=>$tipo_id_paciente,'evolucion'=>$evolucion));
           
          $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";

          $this->salida .= "<br><table width=\"80%\" border=\"1\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\">";
          $this->salida .= "<tr>";
          $this->salida .= "<td colspan=\"2\" align=\"center\" width=\"100%\" class=\"modulo_table_title\">";
          $this->salida .= "NOTA MEDICA";
          $this->salida .= "</td>";
          $this->salida .= "</tr>";
          
          $this->salida .= "<tr>";
          $this->salida .= "<td colspan=\"2\" align=\"center\" width=\"100%\" class=\"modulo_list_claro\">";
          $this->salida .= "<textarea name=\"nota_medica\" rows=\"7\" style=\"width:100%\" class=\"textarea\">".$_REQUEST['nota_medica']."</textarea>";
          $this->salida .= "</td>";
          $this->salida .= "</tr>";
          
          $this->salida .= "<tr>";
          $this->salida .= "<td align=\"center\" width=\"50%\" class=\"modulo_list_claro\">";
          $this->salida .= "<input class=\"input-submit\" type=\"submit\" name=\"nota_medica_boton\" value=\"INSERTAR\">";
          $this->salida .= "</td>";
          
          $this->salida .= "<td align=\"center\" width=\"50%\" class=\"modulo_list_claro\">";
          $this->salida .= "<input type=\"radio\" name=\"monitorizar\" value=\"1\"><b>Monitorizar Ingreso<b>&nbsp;&nbsp;&nbsp;";
          $this->salida .= "<input type=\"radio\" name=\"monitorizar\" value=\"2\"><b>Monitorizar Evolucion<b>";
          $this->salida .= "</td>";

          $this->salida .= "</tr>";
          $this->salida .= "</table>";
          /*INSERTAR NOTA MEDICA*/
          $this->salida .= "</form>";
                              
          $actionM=ModuloGetURL('app','Notas_y_Monitoreo','user','FormaInicial');
          $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
          $this->salida .= "<br><table align=\"center\"><tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Volver\" value=\"VOLVER\">";
          $this->salida .= "</td></tr></table></form>";

          $this->salida.=ThemeCerrarTabla();
          return true;
     }

     
     function Atenciones_X_Servicio()
     {
     	$atenciones = $this->Get_UltimasAtenciones_X_Servicios();
                    
          $mostrar ="\n<script language='javascript'>\n";
          $mostrar.="function mOvr(src,clrOver) {;\n";
          $mostrar.="src.style.background = clrOver;\n";
          $mostrar.="}\n";

          $mostrar.="function mOut(src,clrIn) {\n";
          $mostrar.="src.style.background = clrIn;\n";
          $mostrar.="}\n";
          $mostrar.="</script>\n";

          $backgrounds=array('modulo_list_claro','modulo_list_oscuro');

          $this->salida .="$mostrar";
     	
          if(!empty($atenciones))
          {
               $this->salida .= "<table width=\"100%\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\">";
               $this->salida .= "<tr>";
               $this->salida .= "<td align=\"center\" width=\"7%\" class=\"modulo_table_title\">Ingreso.</td>";
               $this->salida .= "<td align=\"center\" width=\"7%\" class=\"modulo_table_title\">Evol.</td>";
               $this->salida .= "<td align=\"center\" width=\"70%\" class=\"modulo_table_title\">Paciente.</td>";
               $this->salida .= "<td align=\"center\" width=\"7%\" class=\"modulo_table_title\">Fecha.</td>";
               $this->salida .= "</tr>";
               for ($i=0; $i<sizeof($atenciones);$i++)
               {
                    if( $i % 2){ $estilo='modulo_list_claro';}
                         else {$estilo='modulo_list_claro';}
     
                    $fecha = $this->FechaStamp($atenciones[$i][fecha]);
                    if($atenciones[$i][servicio] != $atenciones[$i-1][servicio])
                    {
                         $this->salida .= "<tr class=\"modulo_list_oscuro\">";
                         
                         $servicio = strtoupper($atenciones[$i][descripcion]);
                         $this->salida .= "<td colspan=\"3\" align=\"center\"><b>".$servicio."</b></td>";
                         
                         $accionDesp = "<a href=\"".ModuloGetURL('app','Notas_y_Monitoreo','user','CallDesplegarInfo',array('ingreso'=>$atenciones[$i][ingreso],'servicio_id'=>$atenciones[$i][servicio],'servicio'=>$servicio)) ."\" target=\"Contenido\"><img src=\"". GetThemePath() ."/images/pconsultar.png\" border='0' width='17' height='17' title='Busqueda completa por Servicio de:".$atenciones[$i][descripcion]."'></a>";
                         $this->salida .= "<td align=\"center\">".$accionDesp."</td>";
                         $this->salida .= "</tr>";
                    }
                    $this->salida .= "<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#FFFFFF');>";
                    
                    $accionNotaI = "<a href=\"".ModuloGetURL('app','Notas_y_Monitoreo','user','CallIngresarNota',array('ingreso'=>$atenciones[$i][ingreso],'evolucion'=>$atenciones[$i][evolucion_id],'nombre'=>'I')) ."\" target=\"Contenido\" title=\"Adición de Nota observacion al Ingreso: ".$atenciones[$i][ingreso]."\">".$atenciones[$i][ingreso]."</a>";
                    $accionNotaE = "<a href=\"".ModuloGetURL('app','Notas_y_Monitoreo','user','CallIngresarNota',array('ingreso'=>$atenciones[$i][ingreso],'evolucion'=>$atenciones[$i][evolucion_id],'nombre'=>'E')) ."\" target=\"Contenido\" title=\"Adición de Nota observacion a la Evolucion: ".$atenciones[$i][evolucion_id]."\">".$atenciones[$i][evolucion_id]."</a>";
                    
                    $this->salida .= "<td align=\"center\" width=\"10%\">".$accionNotaI."</td>";
                    $this->salida .= "<td align=\"center\" width=\"10%\">".$accionNotaE."</td>";
                    $this->salida .= "<td align=\"left\" width=\"70%\">".$atenciones[$i][nombre]."</td>";
                    $this->salida .= "<td align=\"center\" width=\"10%\">".$fecha."</td>";
                    $this->salida .= "</tr>";
               }
     
               $this->salida .= "</table>";
          }
          else
          {
               $this->salida.="<table width=\"100%\" border=\"0\" align=\"center\">";
			$this->salida.="<tr align=\"center\"><br><td><label class='label_mark'>NO HAY LISTADO DE ULTIMAS ATENCIONES</label>";
			$this->salida.="</td></tr>";
			$this->salida.="</table>";
			return true;
          }
          return true;
     }
     
     
     function IngresarNota($ingreso,$evolucion,$nombre,$Evolucion_Para_Modulo)
     {
     	$this->salida = ThemeAbrirTabla('INFORMACION Y NOTA MEDICA');
          $titulo = "INFORMACION Y NOTA MEDICA";
          $this->Encabezado($titulo);
          
          if(empty($Evolucion_Para_Modulo))
          {
          	$Evolucion_Para_Modulo = $evolucion;
          }
          
          $accionHIS=ModuloHCGetURL($Evolucion_Para_Modulo,'','','','');
          $this->salida .= "<br><center><div class='label_mark'><font size=\"3\">HISTORIA CLINICA</font></div></center>";
          $this->salida .= "<br><center><div><IFRAME border=\"0\" width=\"79%\" align=\"center\" height=\"600\" SRC='$accionHIS'>";
          $this->salida .= "</IFRAME></div></center>";
          
          if($nombre == 'E')
          {
          	$evolucion = $evolucion;
          }
          else
          {
          	$evolucion = '';
          }

          $actionM=ModuloGetURL('app','Notas_y_Monitoreo','user','InsertNotaMedica',array('ingreso'=>$ingreso,'evolucion_id'=>$evolucion,'nombre'=>$nombre, 'Evolucion_Para_Modulo'=>$Evolucion_Para_Modulo));
          $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";

          $this->salida .= "<br><table width=\"80%\" border=\"1\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\">";
          $this->salida .= "<tr>";
          $this->salida .= "<td colspan=\"2\" align=\"center\" width=\"100%\" class=\"modulo_table_title\">";
          $this->salida .= "NOTA MEDICA";
          $this->salida .= "</td>";
          $this->salida .= "</tr>";
          
          $this->salida .= "<tr>";
          $this->salida .= "<td colspan=\"2\" align=\"center\" width=\"100%\" class=\"modulo_table\">";
          $this->salida .= "<textarea name=\"nota_medica\" rows=\"7\" style=\"width:100%\" class=\"textarea\">".$_REQUEST['nota_medica']."</textarea>";
          $this->salida .= "</td>";
          $this->salida .= "</tr>";
          
          $this->salida .= "<tr>";
          $this->salida .= "<td align=\"center\" width=\"50%\" class=\"modulo_table\">";
          $this->salida .= "<input class=\"input-submit\" type=\"submit\" name=\"nota_medica_boton\" value=\"INSERTAR\">";
          $this->salida .= "</td>";
          
          $this->salida .= "<td align=\"center\" width=\"50%\" class=\"modulo_table\">";
          $this->salida .= "<input type=\"radio\" name=\"monitorizar\" value=\"1\"><b>Monitorizar Ingreso<b>&nbsp;&nbsp;&nbsp;";
          $this->salida .= "<input type=\"radio\" name=\"monitorizar\" value=\"2\"><b>Monitorizar Evolucion<b>";
          $this->salida .= "</td>";

          $this->salida .= "</tr>";
          $this->salida .= "</table>";
          $this->salida .= "</form>";
          
          if ($_SESSION['INSERTAR']['AGENDAMEDICA']['ACCION'] == 'AgendaMedica')
          {
               $actionM=ModuloGetURL('app','AgendaMedica','user','AgendaDia');
          }
          else
          {
	          $actionM=ModuloGetURL('app','Notas_y_Monitoreo','user','FormaInicial');
          }
          $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
          $this->salida .= "<br><table align=\"center\"><tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Volver\" value=\"VOLVER\">";
          $this->salida .= "</td></tr></table></form>";

          $this->salida.=ThemeCerrarTabla();
          return true;
     }
     
 
     function DesplegarInfo($ingreso,$servicio_id,$servicio)
     {
          $this->salida = ThemeAbrirTabla("INFORMACION TOTAL SERVICIO: ".$servicio."");
     	          
          $titulo = "INFORMACION TOTAL SERVICIO: ".$servicio."";
          $this->Encabezado($titulo);

          $atencion_total = $this->Atenciones_X_Servicio_Totales($servicio_id);
          $mostrar ="\n<script language='javascript'>\n";
          $mostrar.="function mOvr(src,clrOver) {;\n";
          $mostrar.="src.style.background = clrOver;\n";
          $mostrar.="}\n";

          $mostrar.="function mOut(src,clrIn) {\n";
          $mostrar.="src.style.background = clrIn;\n";
          $mostrar.="}\n";
          $mostrar.="</script>\n";

          $backgrounds=array('modulo_list_claro','modulo_list_oscuro');

          $this->salida .="$mostrar";
     	
          if(!empty($atencion_total))
          {
               $accionI=ModuloGetURL('app','Notas_y_Monitoreo','user','CallDesplegarInfo',array('ingreso'=>$ingreso,'servicio_id'=>$servicio_id,'servicio'=>$servicio));
               $this->salida.= "<form name=\"ServiTotal\" action=\"$accionI\" method=\"post\">";
          
               $this->salida .= "<br><table width=\"80%\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\">";
               $this->salida .= "<tr>";
               $this->salida .= "<td align=\"center\" width=\"7%\" class=\"modulo_table_title\">INGRESO</td>";
               $this->salida .= "<td align=\"center\" width=\"7%\" class=\"modulo_table_title\">EVOLUCION</td>";
               $this->salida .= "<td align=\"center\" width=\"70%\" class=\"modulo_table_title\">PACIENTE</td>";
               $this->salida .= "<td align=\"center\" width=\"7%\" class=\"modulo_table_title\">FECHA</td>";
               $this->salida .= "</tr>";
               for ($i=0; $i<sizeof($atencion_total);$i++)
               {
                    if( $i % 2){ $estilo='modulo_list_claro';}
                         else {$estilo='modulo_list_claro';}
     
                    $fecha = $this->FechaStamp($atencion_total[$i][fecha]);
                    if($atencion_total[$i][servicio] != $atencion_total[$i-1][servicio])
                    {
                         $this->salida .= "<tr class=\"modulo_list_oscuro\">";
                         $servicio = strtoupper($atencion_total[$i][descripcion]);
                         $this->salida .= "<td colspan=\"4\" align=\"center\"><b>".$servicio."</b></td>";
                         $this->salida .= "</tr>";
                    }
                    $this->salida .= "<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#FFFFFF');>";
                    
                    $accionNotaI = "<a href=\"".ModuloGetURL('app','Notas_y_Monitoreo','user','CallIngresarNota',array('ingreso'=>$atencion_total[$i][ingreso],'evolucion'=>$atencion_total[$i][evolucion_id],'nombre'=>'I')) ."\" title=\"Adición de Nota observacion al Ingreso: ".$atencion_total[$i][ingreso]."\">".$atencion_total[$i][ingreso]."</a>";
                    $this->salida .= "<td align=\"center\" width=\"10%\">".$accionNotaI."</td>";
                    
                    $accionNotaE = "<a href=\"".ModuloGetURL('app','Notas_y_Monitoreo','user','CallIngresarNota',array('ingreso'=>$atencion_total[$i][ingreso],'evolucion'=>$atencion_total[$i][evolucion_id],'nombre'=>'E')) ."\" title=\"Adición de Nota observacion a la Evolucion: ".$atencion_total[$i][evolucion_id]."\">".$atencion_total[$i][evolucion_id]."</a>";
                    $this->salida .= "<td align=\"center\" width=\"10%\">".$accionNotaE."</td>";
                    
                    $this->salida .= "<td align=\"left\" width=\"70%\">".$atencion_total[$i][nombre]."</td>";
                    $this->salida .= "<td align=\"center\" width=\"10%\">".$fecha."</td>";
                    $this->salida .= "</tr>";
               }
     
               $this->salida .= "</table>";
               //Mostrar Barra de Navegacion
               $this->RetornarBarraInfo();
               $this->salida.= "</form>";
          }
          
          
          $actionM=ModuloGetURL('app','Notas_y_Monitoreo','user','FormaInicial');
          $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
          $this->salida .= "<br><table align=\"center\"><tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Volver\" value=\"VOLVER\">";
          $this->salida .= "</td></tr></table></form>";

          $this->salida.=ThemeCerrarTabla();
     	return true;
     }
     
    
     function MisHistorias_Monitoreadas()
     {
     	$monitores = $this->Get_MisHistorias_Monitoreadas();
          
          $mostrar ="\n<script language='javascript'>\n";
          $mostrar.="function mOvr(src,clrOver) {;\n";
          $mostrar.="src.style.background = clrOver;\n";
          $mostrar.="}\n";

          $mostrar.="function mOut(src,clrIn) {\n";
          $mostrar.="src.style.background = clrIn;\n";
          $mostrar.="}\n";
          $mostrar.="</script>\n";

          $backgrounds=array('modulo_list_claro','modulo_list_oscuro');

          $this->salida .="$mostrar";
          if(!empty($monitores))
          {
               $this->salida .= "<table width=\"100%\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\">";
               $this->salida .= "<tr>";
               $this->salida .= "<td align=\"center\" width=\"7%\" class=\"modulo_table_title\">Ingreso.</td>";
               $this->salida .= "<td align=\"center\" width=\"7%\" class=\"modulo_table_title\">Evol.</td>";
               $this->salida .= "<td align=\"center\" width=\"70%\" class=\"modulo_table_title\">Paciente.</td>";
               $this->salida .= "<td align=\"center\" width=\"7%\" class=\"modulo_table_title\">Opc.</td>";
               $this->salida .= "</tr>";
               for ($i=0; $i<sizeof($monitores);$i++)
               {
                    if( $i % 2){ $estilo='modulo_list_claro';}
                         else {$estilo='modulo_list_claro';}
     
                    $this->salida .= "<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#FFFFFF');>";
                    $this->salida .= "<td align=\"center\" width=\"10%\"><font color=\"#990000\">".$monitores[$i][ingreso]."</font></td>";
                    $this->salida .= "<td align=\"center\" width=\"10%\"><font color=\"#990000\">".$monitores[$i][evolucion_id]."</font></td>";
                    $this->salida .= "<td align=\"left\" width=\"70%\">".$monitores[$i][nombre]."</td>";
                    
                    $accionMon = "<a href=\"".ModuloGetURL('app','Notas_y_Monitoreo','user','forma_DesmonitorizarHC',array('ingreso'=>$monitores[$i][ingreso],'evolucion'=>$monitores[$i][evolucion_id],'hc_monitoreo_id'=>$monitores[$i][hc_monitoreo_id]))."\" target=\"Contenido\"><img src=\"". GetThemePath() ."/images/desmonitorizado.png\" border='0' width='17' height='17' title='Eliminar de mis historias monitoreadas'></a>";
                    $this->salida .= "<td align=\"center\" width=\"10%\">".$accionMon."</td>";
                    $this->salida .= "</tr>";
               }
               $this->salida .= "</table>";
          }
          else
          {
               $this->salida.="<table width=\"100%\" border=\"0\" align=\"center\">";
			$this->salida.="<tr  align=\"center\"><br><td><label class='label_mark'>USTED NO TIENE HISTORIAS MONITORIZADAS</label>";
			$this->salida.="</td></tr>";
			$this->salida.="</table>";
			return true;
          }
          return true;
     }
	
	
     function forma_DesmonitorizarHC()
     {
          $this->salida = ThemeAbrirTabla("MONITOREO DE HISTORIAS CLINICAS");
     	
          $ingreso = $_REQUEST['ingreso'];
          $evolucion_HC = $_REQUEST['evolucion'];
          $hc_monitoreo_id = $_REQUEST['hc_monitoreo_id'];
          
          if (empty($evolucion_HC))
          {	
          	$evolucion = $this->TraerEvolucion($ingreso);
          	foreach($evolucion as $k => $v)
               {
               	$evolucion_HC = $v[evolucion_id];
               }
          }
          $titulo = "DESMONITORIZAR HC";
          $this->Encabezado($titulo);
          
          $accionHIS=ModuloHCGetURL($evolucion_HC,'','','','');
          $this->salida .= "<br><center><div class='label_mark'><font size=\"3\">HISTORIA CLINICA</font></div></center>";
          $this->salida .= "<br><center><div><IFRAME border=\"0\" width=\"79%\" align=\"center\" height=\"600\" SRC='$accionHIS'>";
          $this->salida .= "</IFRAME></div></center>";

          $actionM=ModuloGetURL('app','Notas_y_Monitoreo','user','Desmonitorizar_Monitorizados',array('hc_monitoreo_id'=>$hc_monitoreo_id));
          $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
          $this->salida .= "<br><table align=\"center\"><tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Volver\" value=\"DESMONITORIZAR\">";
          $this->salida .= "</td></tr></table></form>";
          
          $actionM=ModuloGetURL('app','Notas_y_Monitoreo','user','FormaInicial');
          $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
          $this->salida .= "<br><table align=\"center\"><tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Volver\" value=\"VOLVER\">";
          $this->salida .= "</td></tr></table></form>";
          
          $this->salida.=ThemeCerrarTabla();
          return true;
     }
     
     
     /*
	* Funcion donde se visualiza el encabezado de la empresa.
	* @return boolean
	*/
     function Encabezado($titulo)
	{
          $empresa = $_SESSION['PacientePsico']['EMPRESA'];
          if(!$titulo)
          { $titulo = "MENU INICIAL"; }
		$this->salida .= "<br><table  class=\"modulo_table_title\" border=\"0\" width=\"80%\" align=\"center\" >";
		$this->salida .= " <tr class=\"modulo_table_title\">";
		$this->salida .= " <td>EMPRESA</td>";
		$this->salida .= " <td>MODULO</td>";
		$this->salida .= " <td>FECHA</td>";
		$this->salida .= " </tr>";
		$this->salida .= " <tr align=\"center\">";
		$this->salida .= " <td class=\"modulo_list_claro\" >".$empresa."</td>";
		$this->salida .= " <td class=\"modulo_list_claro\">$titulo</td>";
		$this->salida .= " <td class=\"modulo_list_claro\" >".$this->FormateoFechaLocal(date("Y-m-d"))."</td>";
		$this->salida .= " </tr>";
		$this->salida .= " </table>";
		return true;
	}

     	
	/**
	* Se utilizada listar en el combo los diferentes tipo de identifiacion de los pacientes
	* @access private
	* @return void
	*/
	function BuscarIdPaciente($tipo_id,$TipoId)
	{
          foreach($tipo_id as $value=>$titulo)
          {
               if($value==$TipoId){
                    $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
               }else{
                    $this->salida .=" <option value=\"$value\">$titulo</option>";
               }
          }
	}
	
	
	/*
	* Esta funcion realiza la busqueda de las ordenes de servicio según filtros como numero de orden
	* documento y plan
	* @return boolean
	*/
	function FormaMetodoBuscar($Busqueda,$arr,$f)
	{
          $this->salida.= ThemeAbrirTabla('INFORMACIÓN DEL PACIENTE');
          
          $titulo = 'BUSQUEDA DE HISTORIAS CLINICAS';
          $this->Encabezado($titulo);
			
          if (empty($this->dos)){
			$this->salida.="<br><br><table border=\"0\" align=\"center\"  width=\"100%\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida.="</table>";
	      }
			
               if(!empty($arr) AND !empty($f))
               {
                    $mostrar ="\n<script language='javascript'>\n";
                    $mostrar.="function mOvr(src,clrOver) {;\n";
                    $mostrar.="src.style.background = clrOver;\n";
                    $mostrar.="}\n";
     
                    $mostrar.="function mOut(src,clrIn) {\n";
                    $mostrar.="src.style.background = clrIn;\n";
                    $mostrar.="}\n";
                    $mostrar.="</script>\n";
                    $this->salida .="$mostrar";
     
                    $this->salida .= "<table class=\"modulo_table_title\" width=\"80%\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\" align=\"center\" >";
                    $vector=array();//reiniciamos el vector q va a comparar.
     
                    $backgrounds=array('modulo_list_claro'=>'#F4F4F4','modulo_list_oscuro'=>'#F4F4F4');
                    $reporte= new GetReports();
                    for($i=0;$i<sizeof($arr);$i++)
                    {
                         if( $i % 2){ $estilo='modulo_list_claro';}
                         else {$estilo='modulo_list_claro';}
     
                    if($arr[$i][tipo_id_paciente].$arr[$i][paciente_id]<> $_var)
                    {
                         $this->salida .= "			<tr align=\"center\" class=\"modulo_table_title\">";
                         $this->salida .= "				<td width=\"10%\">Identificacion</td>";
                         $this->salida .= "				<td width=\"50%\">Datos Paciente</td>";
                         $this->salida .= "				<td width=\"10%\">Historia C</td>";
                         $this->salida .= "			</tr>";
     
                         $this->salida.="<tr  bgcolor='#F4F4F4' align='center'  onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#F4F4F4');>";
                         $this->salida.="  <td><label class='label_mark'>".$arr[$i][tipo_id_paciente]."&nbsp; - &nbsp;".$arr[$i][paciente_id]."</label></td>";
                         $this->salida.="  <td><label class='label_mark'>".$arr[$i][nombre]."</label></td>";
     
                         $mostrar3=$reporte->GetJavaReport_HC($arr[$i][ingreso],array());
                         $funcion2=$reporte->GetJavaFunction();
                         $this->salida.=$mostrar3;
                         $this->salida.="  <td width=\"10%\" ><a href=\"javascript:$funcion2\"><img src=\"". GetThemePath() ."/images/historial.png\" border='0' title='HISTORIA CLINICA'></a></td>";
                         $this->salida.="</tr>";
                         $_var=$arr[$i][tipo_id_paciente].$arr[$i][paciente_id];
                         
                         $this->salida.="<tr class='modulo_list_oscuro'>";
                         $this->salida .= "<td  colspan='3'>";
                         
                         if( $i % 2){ $estilo1='modulo_list_claro';}
                         else {$estilo1='modulo_list_claro';}
     
                         
                         $this->salida .= "		<table class=\"hc_table_list\" width=\"100%\" border=\"1\" align=\"center\" >";
                         $this->salida .= "			<tr class=\"hc_table_submodulo_list_title\" align=\"center\" >";
                         $this->salida .= "				<td width=\"9%\">Ingreso</td>";
                         $this->salida .= "				<td width=\"10%\">Fecha Ingreso</td>";                         
                         $this->salida .= "				<td width=\"9%\">Evolucion</td>";
                         $this->salida .= "				<td width=\"9%\">Fecha Evol.</td>";                                                  
                         $this->salida .= "				<td width=\"20%\">Departamento</td>";
                         $this->salida .= "				<td width=\"20%\">Servicio</td>";
                         $this->salida .= "				<td width=\"5%\"></td>";
                         $this->salida .= "			</tr>";
     
                         $this->salida.="<tr  class='$estilo1' align='center'>";
                         
                         $USR = $this->TraerUsuario($arr[$i][usuario_id]);
               
                         //Cambio: Antes estaba el mecanismo de Impresion.
                         $ActionIngreso = ModuloGetURL('app','Notas_y_Monitoreo','user','CallIngresarNota',array('ingreso'=>$arr[$i][ingreso],'evolucion'=>$arr[$i][evolucion_id],'nombre'=>'I'));
                         $this->salida.="  <td><a href=\"$ActionIngreso\">".$arr[$i][ingreso]."</a></td>";

                         $fecha=explode(" ",$arr[$i][fecha_ingreso]);
                         $this->salida.="  <td>".$fecha[0]."</td>";
                         
                         //Cambio: Antes estaba el mecanismo de Impresion.
                         $ActionEvo = ModuloGetURL('app','Notas_y_Monitoreo','user','CallIngresarNota',array('ingreso'=>$arr[$i][ingreso],'evolucion'=>$arr[$i][evolucion_id],'nombre'=>'E'));

                         $this->salida.="  <td><a href=\"$ActionEvo\">".$arr[$i][evolucion_id]."</a></td>";                                                  

                         $fechaevo=explode(" ",$arr[$i][fecha]);
                         $this->salida.="  <td>".$fechaevo[0]."</td>";
                         
                         $this->salida.="  <td>".$arr[$i][desc]."</td>";
                         $this->salida.="  <td>".$arr[$i][descripcion]."</td>";
                         
                         $a=$e="";
                         if($arr[$i][estado]==1)
                         {$a='activo.gif';$e='ingreso activo';}else{$a='inactivo.gif';$e='ingreso inactivo';}
                         $this->salida.="  <td><img src=\"". GetThemePath() ."/images/$a\" border='0' width='12' height='12'  title='$e'></td></tr>";
                         
                         $this->salida .= "<tr class='$estilo1'>";
                         $this->salida .= "<td class=\"hc_table_submodulo_list_title\">Profesional:</td>";
                         $this->salida .= "<td colspan=\"6\" class='$estilo1'>".$USR[nombre]."&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;".strtoupper($USR[usuario])."</td>";
                         $this->salida .= "</tr>";

                         $this->salida .= "			</table>";//fin tabla de ingresos
                         $this->salida.="</td></tr>";
                    }
                    else
                    {
                         $USR = $this->TraerUsuario($arr[$i][usuario_id]);
                         
                         $this->salida.="<tr class='modulo_list_oscuro'>";
                         $this->salida .= "<td  colspan='3'>";
                         
                         if( $i % 2){ $estilo1='modulo_list_claro';}
                         else {$estilo1='modulo_list_claro';}
     
                         $this->salida .= "		<table  width=\"100%\"  border=\"1\" class=\"hc_table_list\" align=\"center\" >";
                         $this->salida .= "			<tr class=\"hc_table_submodulo_list_title\" align=\"center\" >";
                         $this->salida .= "				<td width=\"9%\">Ingreso</td>";
                         $this->salida .= "				<td width=\"10%\">Fecha Ingreso</td>";                         
                         $this->salida .= "				<td width=\"9%\">Evolucion</td>";
                         $this->salida .= "				<td width=\"9%\">Fecha Evol.</td>";                                                  
                         $this->salida .= "				<td width=\"20%\">Departamento</td>";
                         $this->salida .= "				<td width=\"20%\">Servicio</td>";
                         $this->salida .= "				<td width=\"5%\"></td>";
                         $this->salida .= "			</tr>";
     
                         $this->salida.="<tr  class='$estilo1' align='center'>";
                         
                         //Cambio: Antes estaba el mecanismo de Impresion.
                         $ActionIngreso = ModuloGetURL('app','Notas_y_Monitoreo','user','CallIngresarNota',array('ingreso'=>$arr[$i][ingreso],'evolucion'=>$arr[$i][evolucion_id],'nombre'=>'I'));
                         $this->salida.="  <td><a href=\"$ActionIngreso\">".$arr[$i][ingreso]."</a></td>";
                         
                         $fecha=explode(" ",$arr[$i][fecha_ingreso]);
                         $this->salida.="  <td>".$fecha[0]."</td>";

                         //Cambio: Antes estaba el mecanismo de Impresion.
                         $ActionEvo = ModuloGetURL('app','Notas_y_Monitoreo','user','CallIngresarNota',array('ingreso'=>$arr[$i][ingreso],'evolucion'=>$arr[$i][evolucion_id],'nombre'=>'E'));
                         $this->salida.="  <td><a href=\"$ActionEvo\">".$arr[$i][evolucion_id]."</a></td>";                                                  

                         $fechaevo=explode(" ",$arr[$i][fecha]);
                         $this->salida.="  <td>".$fechaevo[0]."</td>";
                                                  
                         $this->salida.="  <td>".$arr[$i][desc]."</td>";
                         $this->salida.="  <td>".$arr[$i][descripcion]."</td>";
                         $a=$e="";
                         if($arr[$i][estado]==1)
                         {$a='activo.gif';$e='ingreso activo';}else{$a='inactivo.gif';$e='ingreso inactivo';}
                         $this->salida.="  <td><img src=\"". GetThemePath() ."/images/$a\" border='0' width='12' height='12' title='$e'></td></tr>";
                         
                         $this->salida .= "<tr class='$estilo1'>";
                         $this->salida .= "<td class=\"hc_table_submodulo_list_title\">Profesional:</td>";
                         $this->salida .= "<td colspan=\"6\" class='$estilo1'>".$USR[nombre]."&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;".strtoupper($USR[usuario])."</td>";
                         $this->salida .= "</tr>";
                         
                         $this->salida .= "			</table>";//fin tabla de ingresos
                         $this->salida.="</td></tr>";
                    }
               }
               $this->salida.="</table>";
               $this->conteo=$_SESSION['SPY'];
               $this->salida .=$this->RetornarBarra1();
          }
          
          $this->salida.="<br><table width=\"100%\">";
          $actionM=ModuloGetURL('app','Notas_y_Monitoreo','user','FormaInicial');
          $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
          $this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"><br></td></form>";
          $this->salida .= "</tr>";
          $this->salida.="</table>";
     
          $this->salida .= ThemeCerrarTabla();
          return true;
	}

	
     function RetornarBarraInfo(){

		if($this->limit>=$this->conteo){
			return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso)){
			$paso=1;
		}

      	$accion=ModuloGetURL('app','Notas_y_Monitoreo','user','CallDesplegarInfo',array('conteo'=>$this->conteo,'paso1'=>$_REQUEST['paso1'],
          'ingreso'=>$_REQUEST['ingreso'],'servicio_id'=>$_REQUEST['servicio_id'],'servicio'=>$_REQUEST['servicio'],'evolucion'=>$_REQUEST['evolucion'],
          'nombre'=>$_REQUEST['nombre']));
          
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;

		$this->salida .= "<br><table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1){
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}else{
               // $this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
               //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
     	}
		
          $barra ++;
		if(($barra+10)<=$numpasos){
			for($i=($barra);$i<($barra+10);$i++){
				if($paso==$i){
						$this->salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}else{
						$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}else{
      		$diferencia=$numpasos-9;
			if($diferencia<=0){$diferencia=1;}
			for($i=($diferencia);$i<=$numpasos;$i++){
				if($paso==$i){
					$this->salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}else{
					$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos){
  			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}else{
                    //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
                    //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
               }
		}
		$this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan='15' align='center'>Página $paso de $numpasos</td><tr></table>";
	}
     
     
     function RetornarBarra1(){
          if($this->limit>=$this->conteo){
				return '';
		}
          
          $paso=$_REQUEST['paso'];
		if(empty($paso)){
			$paso=1;
		}
		$vec='';
		foreach($_REQUEST as $v=>$v1)
		{
			if($v!='modulo' and $v!='metodo' and $v!='SIIS_SID' and  $v!='Of')
			{   $vec[$v]=$v1;   }
		}
		
		$accion=ModuloGetURL('app','Notas_y_Monitoreo','user','BuscarOrden',$vec);
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;

		$this->salida .= "<br><table width='22%' border='0'  align='center' cellspacing=\"5\"  cellpadding=\"1\"><tr><td width='20%' class='label' bgcolor=\"#D3DCE3\">Páginas</td>";
		if($paso > 1){
			$this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'><img src=\"".GetThemePath()."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
			$this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'><img src=\"".GetThemePath()."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
			$colspan+=2;
		}
		$barra ++;
		if(($barra+10)<=$numpasos){
			for($i=($barra);$i<($barra+10);$i++){
				if($paso==$i){
						$this->salida .= "<td width='7%' bgcolor=\"#D3DCE3\">$i</td>";
				}else{
						$this->salida .= "<td width='7%'  bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$this->salida .= "<td width='7%'  bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' ><img src=\"".GetThemePath()."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
			$this->salida .= "<td width='7%'  bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'><img src=\"".GetThemePath()."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
			$colspan+=2;
		}else{
      		$diferencia=$numpasos-9;
			if($diferencia<0){$diferencia=1;}
			for($i=($diferencia);$i<=$numpasos;$i++){
				if($paso==$i){
					$this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\" >$i</td>";
				}else{
					$this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos){
  			$this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' ><img src=\"".GetThemePath()."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
				$this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'><img src=\"".GetThemePath()."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
				$colspan++;
			}
		}
      	if(($_REQUEST['Of'])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
			$this->salida .= "</tr><tr><td  class=\"label\"  colspan=".$valor." align='center'>Página&nbsp; $paso de $numpasos</td><tr></table>";
			//$this->salida.="</table>";
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
			$this->salida .= "</tr><tr><td   class=\"label\"  colspan=".$valor." align='center'>Página&nbsp; $paso de $numpasos</td><tr></table>";
		}
	}
	//fin de las funciones para la barra de segnentacion

     /**
     *
     */
     function CalcularNumeroPasos($conteo){
          $numpaso=ceil($conteo/$this->limit);
          $numpaso;
          return $numpaso;
     }
     
     function CalcularBarra($paso){
          $barra=floor($paso/10)*10;
          if(($paso%10)==0){
               $barra=$barra-10;
     	}
     	return $barra;
     }
     
     function CalcularOffset($paso){
          $offset=($paso*$this->limit)-$this->limit;
          return $offset;
     }
     
     function GetHtmlServicio($vect,$TipoId)
     {
          foreach($vect as $value=>$titulo)
          {
               if($value==$TipoId){
                    $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
               }else{
                    $this->salida .=" <option value=\"$value\">$titulo</option>";
               }
          }
     }


//----------------------------------------------------------------------------------------------------

}//fin clase

?>

