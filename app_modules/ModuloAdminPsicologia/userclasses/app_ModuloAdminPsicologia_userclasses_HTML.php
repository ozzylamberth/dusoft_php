<?php

/**
 * $Id: app_Notas_y_Monitoreo_userclasses_HTML.php,v 1.11 2005/11/30 23:07:54 darling Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Contiene los metodos visuales para realizar las consultas de pacientes psicologicos.
 */


class app_ModuloAdminPsicologia_userclasses_HTML extends app_ModuloAdminPsicologia_user
{

     function app_ModuloAdminPsicologia_user_HTML()
	{
          $this->salida='';
          $this->app_ModuloAdminPsicologia_user();
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
          $this->salida .= ThemeAbrirTabla('MODULO ADMINISTRATIVO DE PSICOLOGIA');
          $this->Encabezado();
          unset($_SESSION['CONTALLER']);
          $this->salida .= "<br>";
          $this->salida .= "<table width=\"60%\" border=\"0\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
          $this->salida .= "<tr>";
          $this->salida .= "      <td align=\"center\" class=\"modulo_table_list_title\" colspan=\"2\">MENU DE SELECCION</td>";
          $this->salida .= "</tr>";
          
          $this->salida .= "<tr>";
          $_SESSION['MOD_PSICOLOGIA'] = true;
          $accionF=ModuloGetURL('app','CentroAutorizacion','user','FormaBuscarTodos');
          $this->salida .= "      <td align=\"left\" class=\"modulo_list_claro\"><a title='Permite realizar la autorizacion de las interconsultas programadas' href=\"$accionF\">AUTORIZAR INTERCONSULTAS PROGRAMADAS</a></td>";
          $accionF=ModuloGetURL('app','ModuloAdminPsicologia','user','FormaBuscarTodos',array('motivo_id'=>$listadoMotivos[$i][motivo_id], 'sw'=>"0"));
          $this->salida .= "      <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accionF\"><img src=\"".GetThemePath()."/images/copiar.png\" border=0 title='Permite realizar la autorizacion de las interconsultas programadas.'></a></td>\n";
          $this->salida .= "</tr>";
          
          $this->salida .= "<tr>";
          $accionH=ModuloGetURL('app','ModuloAdminPsicologia','user','FrmEdicionMotivos');
          $this->salida .= "      <td align=\"left\" class=\"modulo_list_claro\"><a title='Permite la edicion de los tipos de motivos de consulta' href=\"$accionH\">CREACION Y EDICION DE MOTIVOS DE CONSULTA</a></td>";
          $accionH=ModuloGetURL('app','ModuloAdminPsicologia','user','FrmEdicionMotivos',array('motivo_id'=>$listadoMotivos[$i][motivo_id], 'sw'=>"0"));
          $this->salida .= "      <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accionH\"><img src=\"".GetThemePath()."/images/edita.png\" border=0 title='Permite la edicion de los tipos de motivos de consulta.'></a></td>\n";
          $this->salida .= "</tr>";
          
          $this->salida .= "<tr>";
          $accionHi=ModuloGetURL('app','ModuloAdminPsicologia','user','FrmEdicionMotivosDetalle');
          $this->salida .= "      <td align=\"left\" class=\"modulo_list_claro\"><a title='Permite la edicion de los tipos de motivos detalle de consulta' href=\"$accionHi\">CREACION Y EDICION DETALLES MOTIVOS DE CONSULTA</a></td>";
          $accionHi=ModuloGetURL('app','ModuloAdminPsicologia','user','FrmEdicionMotivosDetalle',array('motivo_id'=>$listadoMotivos[$i][motivo_id], 'sw'=>"0"));
          $this->salida .= "      <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accionHi\"><img src=\"".GetThemePath()."/images/editar.png\" border=0 title='Permite la edicion de los tipos de motivos detalle de consulta.'></a></td>\n";
          $this->salida .= "</tr>";
          
          $this->salida .= "<tr>";
          $accionT=ModuloGetURL('app','ModuloAdminPsicologia','user','FrmEdicionTrabajos');
          $this->salida .= "      <td align=\"left\" class=\"modulo_list_claro\"><a title='Permite la edicion de los tipos de trabajos realizados' href=\"$accionT\">CREACION Y EDICION DE TRABAJOS REALIZADOS</a></td>";
          $accionT=ModuloGetURL('app','ModuloAdminPsicologia','user','FrmEdicionTrabajos',array('motivo_id'=>$listadoMotivos[$i][motivo_id], 'sw'=>"0"));
          $this->salida .= "      <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accionT\"><img src=\"".GetThemePath()."/images/edita.png\" border=0 title='Permite la edicion de los tipos de trabajos realizados.'></a></td>\n";
          $this->salida .= "</tr>";
          
          $this->salida .= "<tr>";
          $accionTi=ModuloGetURL('app','ModuloAdminPsicologia','user','FrmEdicionTrabajosDetalle');
          $this->salida .= "      <td align=\"left\" class=\"modulo_list_claro\"><a title='Permite la edicion de los tipos de trabajos realizados detalle' href=\"$accionTi\">CREACION Y EDICION DE  DETALLES DE TRABAJOS REALIZADOS</a></td>";
          $accionTi=ModuloGetURL('app','ModuloAdminPsicologia','user','FrmEdicionTrabajosDetalle',array('motivo_id'=>$listadoMotivos[$i][motivo_id], 'sw'=>"0"));
          $this->salida .= "      <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accionTi\"><img src=\"".GetThemePath()."/images/editar.png\" border=0 title='Permite la edicion de los tipos de trabajos realizados detalle.'></a></td>\n";
          $this->salida .= "</tr>";
          
          $this->salida .= "<tr>";
          $accionTR=ModuloGetURL('app','ModuloAdminPsicologia','user','FrmAdmon_TalleresPsicologicos');
          $this->salida .= "      <td align=\"left\" class=\"modulo_list_claro\"><a title='Permite la administracion de los talleres psicologicos' href=\"$accionTR\">ADMINISTRACION DE TALLERES PSICOLOGICOS</a></td>";
          $accionTR=ModuloGetURL('app','ModuloAdminPsicologia','user','FrmAdmon_TalleresPsicologicos');
          $this->salida .= "      <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accionTR\"><img src=\"".GetThemePath()."/images/historial.png\" border=0 title='Permite la administracion de los talleres psicologicos.'></a></td>\n";
          $this->salida .= "</tr>";
          
          $this->salida .= "<tr>";
          $accionTR=ModuloGetURL('app','ModuloRepPsicologia','user','main');
          $this->salida .= "      <td align=\"left\" class=\"modulo_list_claro\"><a title='Permite la consulta de los respectivos reportes de psicologia' href=\"$accionTR\">MODULO REPORTES PSICOLOGIA</a></td>";
          $this->salida .= "      <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accionTR\"><img src=\"".GetThemePath()."/images/resumen.gif\" border=0 title='Permite la consulta de los respectivos reportes de psicologia.'></a></td>\n";
          $this->salida .= "</tr>";
          $this->salida .= "</table>";
          
          $accion=ModuloGetURL('app','ModuloAdminPsicologia','user','main');
          $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
          $this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"></p>";
          $this->salida .= "</form>";
          $this->salida .= ThemeCerrarTabla();
          return true;
	}
     
     
     /**
     * Forma para mostrar el listado tipos de motivos de consulta.
     *
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function FrmAdmon_TalleresPsicologicos($nombre_taller, $intro, $objetivos, $contenido, $metodologia, $intensidad, $areas_apoyo)
     {
          include_once 'app_modules/ModuloAdminPsicologia/RemoteXajax/AdmonTallerXajax.php';
          $this->SetXajax(array("ConsultaTaller", "ActivarTaller", "ProgramacionesTalleres", "CrearProgramacionT", "CancelarTalleres", "CancelProgramacionT"));

          $this->IncludeJS("CrossBrowser");
          $this->IncludeJS("CrossBrowserDrag");
          $this->IncludeJS("CrossBrowserEvent");
          $this->IncludeJS("RemoteScripting");
     	$this->IncludeJS("ScriptsRemotos/misfunciones.js",'app','ModuloAdminPsicologia');

          $this->salida .= ThemeAbrirTabla('ADMINISTRACION DE TALLERES PSICOLOGICOS');
          $this->Encabezado("TALLERES PSICOLOGICOS");
          
		$this->salida .= "<br>\n";                    
          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
          $this->salida .= $this->SetStyle("MensajeError");
          $this->salida.="</table>";
          
		$this->salida .= "<br>\n";
          $accion = ModuloGetURL('app','ModuloAdminPsicologia','user','CrearTallerPsicologico');
          $this->salida .= "<form name=\"formacrear\" action=\"$accion\" method=\"post\">";          
          $this->salida .= "<table align=\"center\" width=\"80%\"  border=\"0\" >\n";
          $this->salida .= "  <tr class=\"modulo_table_title\">\n";
          $this->salida .= "      <td colspan='2' height='30'>CREAR TALLER PSICOLOGICO</td>\n";
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "      <td align=\"left\" width=\"50%\">NOMBRE DEL TALLER</td>\n";
          $this->salida .= "      <td align=\"left\" width=\"50%\" class=\"modulo_list_claro\"><input type=\"text\" name='nombre_taller' id='nombre_taller' size=\"34\" maxlength=\"256\" value=\"$nombre_taller\"></td>\n";                              
		$this->salida .= "  </tr>\n";          
          $this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "      <td align=\"left\">INTRODUCCION DEL TALLER</td>\n";
          $this->salida .= "      <td align=\"left\" class=\"modulo_list_claro\"><TEXTAREA name='intro' id='intro' rows='2' cols='30'>".$intro."</TEXTAREA></td>\n";
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "      <td align=\"left\">OBJETIVOS DEL TALLER</td>\n";
          $this->salida .= "      <td align=\"left\" class=\"modulo_list_claro\"><TEXTAREA name='objetivos' id='objetivos' rows='2' cols='30'>".$objetivos."</TEXTAREA></td>\n";                              
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "      <td align=\"left\">CONTENIDOS GENERALES</td>\n";
          $this->salida .= "      <td align=\"left\" class=\"modulo_list_claro\"><TEXTAREA name='contenido' id='contenido' rows='2' cols='30'>".$contenido."</TEXTAREA></td>\n";                              
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "      <td align=\"left\">METODOLOGIA</td>\n";
          $this->salida .= "      <td align=\"left\" class=\"modulo_list_claro\"><TEXTAREA name='metodologia' id='metodologia' rows='2' cols='30'>".$metodologia."</TEXTAREA></td>\n";                              
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "      <td align=\"left\">RESPONSABLE</td>\n";
		$this->salida .= "	<td colspan=\"3\" class=\"hc_submodulo_list_claro\" align=\"left\">";
		$this->salida .= "	<select name=\"responsable\" class=\"select\">";
          $psiologos=$this->profesionalesPsicologos();
          $this->salida .=" 	<option value=\"-1\">---Seleccione---</option>";
          for($i=0;$i<sizeof($psiologos);$i++)
          {
               $value = $psiologos[$i]['usuario_id'];
               $titulo=$psiologos[$i]['nombre'];
               if($value==$_REQUEST['responsable'])
               {
                    $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
               }else{
                    $this->salida .=" <option value=\"$value\">$titulo</option>";
               }
          }	  
          $this->salida .= "	</select>";
          $this->salida .= "	</td>";
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "      <td align=\"left\">PORCENTAJE DE SESIONES MINIMAS</td>\n";
          $this->salida .= "      <td align=\"left\" class=\"modulo_list_claro\"><select name=\"sesiones_minimas\" class=\"select\">";
          $this->salida .= "      <option value=\"-1\" selected>-- SELECCIONAR --</option>";
          for ($i = 0; $i<=100; $i++)
          {
          	$this->salida .= "      <option value=\"$i\">".$i." %</option>";
               $i = $i + 9;
          }
          $this->salida .= "      </select></td>\n";                              
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "      <td align=\"left\">INTENSIDAD</td>\n";
          $this->salida .= "      <td align=\"left\" class=\"modulo_list_claro\"><TEXTAREA name='intensidad' id='intensidad' rows='2' cols='30'>".$intensidad."</TEXTAREA></td>\n";
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "      <td align=\"left\">AUTONOMIA DEL TALLER: ";
          $this->salida .= "      <select name=\"autonomia\" class=\"select\">";
          $this->salida .= "      <option value=\"1\" selected>Propio</option>";
          $this->salida .= "      <option value=\"2\">Apoyo de Otras Areas</option>";
          $this->salida .= "      </select>\n";                              
          $this->salida .= "      </td>\n";
          $this->salida .= "      <td align=\"left\" class=\"modulo_list_claro\"><b>AREAS DE APOYO:</b><BR>";
          $this->salida .= "      <TEXTAREA name='areas_apoyo' id='areas_apoyo' rows='1' cols='30'>".$areas_apoyo."</TEXTAREA>";
          $this->salida .= "      </td>\n";
          $this->salida .= "  </tr>\n";
 		$this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "      <td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"crear_taller\" value=\"CREAR TALLER\"></td>\n";
          $this->salida .= "  </tr>\n";
          $this->salida .= "</form>";
          $this->salida .= "</table>\n";

          $this->salida .= "<br>\n";          
          $this->salida .= "<table align=\"center\" width=\"80%\"  border=\"0\" >\n";
          $this->salida .= "  <tr class=\"modulo_table_title\">\n";
          $this->salida .= "      <td colspan='2' height='30'>MENU DE GESTION DE TALLERES PSICOLOGICOS</td>\n";
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "      <td align=\"left\" width=\"50%\">Consultar Taller Psicologico\n";
		$this->salida .= "	&nbsp;&nbsp;<select name=\"consultarT\" id=\"consultarT\" onchange=\"CargarTaller();\" class=\"select\">";
          $psiologos=$this->ConsultaTalleres();
          $this->salida .=" 	<option value=\"-1\">---Seleccione---</option>";
          for($i=0;$i<sizeof($psiologos);$i++)
          {
               $value = $psiologos[$i]['taller_id'];
               $titulo=$psiologos[$i]['nombre_taller'];
               if($value==$_REQUEST['consultarT'])
               {
                    $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
               }else{
                    $this->salida .=" <option value=\"$value\">$titulo</option>";
               }
          }	  
          $this->salida .= "	</select>";
          $this->salida .= "	<input class=\"input-submit\" type=\"button\" onclick=\"MostrarCapa('ContenedorConTaller');IniciarTaller('CONSULTA DE TALLERES PSICOLOGICOS', 'ContenedorConTaller');CargarContenedor('ContenedorConTaller');\" name=\"ir_taller\" value=\"IR\"></td>";
          $this->salida .= "      <td align=\"left\" width=\"50%\">Programar Taller&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
		$this->salida .= "	&nbsp;&nbsp;<select name=\"ProgramarT\" id=\"ProgramarT\" onchange=\"CargarTaller();\" class=\"select\">";
          $psiologos=$this->ConsultaTalleres(1);
          $this->salida .=" 	<option value=\"-1\" selected>---Seleccione---</option>";
          for($i=0;$i<sizeof($psiologos);$i++)
          {
               if($psiologos[$i]['estado'] == null OR $psiologos[$i]['estado'] == 0 OR $psiologos[$i]['estado'] == 2)
               {
                    $value = $psiologos[$i]['taller_id'];
                    $titulo=$psiologos[$i]['nombre_taller'];
                    if($value==$_REQUEST['ProgramarT'])
                    {
                         $this->salida .=" <option value=\"$value\">$titulo</option>";
                    }else{
                         $this->salida .=" <option value=\"$value\">$titulo</option>";
                    }
               }
          }	  
          $this->salida .= "	</select>";
          $this->salida .= "	<input class=\"input-submit\" type=\"button\" onclick=\"MostrarCapa('ContenedorProgTaller');IniciarProgTaller('PROGRAMACION DE TALLERES PSICOLOGICOS', 'ContenedorProgTaller');CargarContenedor('ContenedorProgTaller');\" name=\"P_taller\" value=\"IR\"></td>";
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "      <td align=\"left\" width=\"50%\">Cancelar Programacion&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
		$this->salida .= "	&nbsp;&nbsp;<select name=\"CancelarP\" id=\"CancelarP\" onchange=\"CargarTaller();\" class=\"select\">";
          $psiologos=$this->ConsultaTalleres(2);
          $this->salida .=" 	<option value=\"-1\" selected>---Seleccione---</option>";
          for($i=0;$i<sizeof($psiologos);$i++)
          {
               $value = $psiologos[$i]['taller_id'];
               $titulo=$psiologos[$i]['nombre_taller'];
               if($psiologos[$i]['estado'] == 1)
               {
                    if($value==$_REQUEST['CancelarP'])
                    {
                         $this->salida .=" <option value=\"$value\">$titulo</option>";
                    }else{
                         $this->salida .=" <option value=\"$value\">$titulo</option>";
                    }
               }
          }	  
          $this->salida .= "	</select>";
          $this->salida .= "	<input class=\"input-submit\" type=\"button\" onclick=\"MostrarCapa('ContenedorCancelTaller');CancelarProgTaller('CANCELAR PROGRAMACION DE TALLERES', 'ContenedorCancelTaller');CargarContenedor('ContenedorCancelTaller');\" name=\"Cancel_taller\" value=\"IR\"></td>";
          $accion = ModuloGetURL('app','ModuloAdminPsicologia','user','CallFrmInscripciones');          
          $this->salida .= "<form name=\"formains\" action=\"$accion\" method=\"post\">";          
          $this->salida .= "      <td align=\"left\" width=\"50%\">Inscripcion Participantes\n";
          $this->salida .= "	&nbsp;&nbsp;<select name=\"IncribirP\" id=\"IncribirP\" class=\"select\">";
          $psiologos=$this->ConsultaTalleres(3);
          $this->salida .=" 	<option value=\"-1\" selected>---Seleccione---</option>";
          for($i=0;$i<sizeof($psiologos);$i++)
          {
               $value = $psiologos[$i]['taller_id'];
               $titulo=$psiologos[$i]['nombre_taller'];
               if($psiologos[$i]['estado'] == 1)
               {
                    if($value==$_REQUEST['IncribirP'])
                    {
                         $this->salida .=" <option value=\"$value\">$titulo</option>";
                    }else{
                         $this->salida .=" <option value=\"$value\">$titulo</option>";
                    }
               }
          }	  
          $this->salida .= "	</select>";
          $this->salida .= "	<input class=\"input-submit\" type=\"submit\" name=\"inscribir\" value=\"IR\"></td>";
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "      <td align=\"center\" colspan=\"2\"><img src=\"".GetThemePath()."/images/estadistica.gif\"  width='14' height='14' border=0> <a href=\"$EstadisticasT\">Estadisticas de Gestion</a></td>\n";                    
          $this->salida .= "  </tr>\n";
          $this->salida .= "</form>\n";
          $this->salida .= "</table>\n";
                
          $this->salida.="<div id='ContenedorConTaller' class='d2Container' style=\"display:none\">";
          $this->salida .= "    <div id='titulo' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
          $this->salida .= "    <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorConTaller');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
          $this->salida .= "    <div id='error' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
          $this->salida .= "    <div id='ContenidoConTaller'>\n";
          $this->salida .= "    </div>\n";     
          $this->salida.="</div>";
          
          $this->salida.="<div id='ContenedorProgTaller' class='d2Container' style=\"display:none\">";
          $this->salida .= "    <div id='tituloP' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
          $this->salida .= "    <div id='cerrarP' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorProgTaller');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
          $this->salida .= "    <div id='errorP' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
          $this->salida .= "    <div id='ContenidoProgramacion'>\n";
          $this->salida .= "    </div>\n";     
          $this->salida.="</div>";
          
          $this->salida.="<div id='ContenedorCancelTaller' class='d2Container' style=\"display:none\">";
          $this->salida .= "    <div id='tituloC' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
          $this->salida .= "    <div id='cerrarC' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorCancelTaller');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
          $this->salida .= "    <div id='errorC' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
          $this->salida .= "    <div id='ContenidoCancelacion'>\n";
          $this->salida .= "    </div>\n";     
          $this->salida.="</div>";
          
          $accionI=ModuloGetURL('app','ModuloAdminPsicologia','user','FormaInicial');
          $this->salida .= "<form name=\"formabuscar\" action=\"$accionI\" method=\"post\">";
          $this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"></p>";
          $this->salida .= "</form>";
          

          $javaC = "<script>\n";
          $javaC .= "   var contenedor\n";
          
          $javaC .= "   function CargarContenedor(Elemento)\n";
          $javaC .= "   {\n";
          $javaC .= "        contenedor = Elemento;\n";
          $javaC .= "   }\n";

          $javaC .= "   var titulo = 'titulo';\n";
          $javaC .= "   var hiZ = 2;\n";
          $javaC .= "   var DatosTaller;\n";
          
          $javaC .= "   function CargarTaller()\n";
          $javaC .= "   {\n";
          $javaC .= "        DatosTaller = document.getElementById('consultarT').value;\n";
          $javaC .= "        if (DatosTaller == -1)\n";
          $javaC .= "        {\n"; 
          $javaC .= "            DatosTaller = document.getElementById('ProgramarT').value;\n"; 
          $javaC .= "        }\n";
          $javaC .= "        if (DatosTaller == -1)\n";
          $javaC .= "        {\n"; 
          $javaC .= "            DatosTaller = document.getElementById('CancelarP').value;\n"; 
          $javaC .= "        }\n";
          $javaC .= "   }\n";
          
          $javaC .= "   function IniciarTaller(tit, Elemento)\n";
          $javaC .= "   {\n";
          $javaC .= "	   xajax_ConsultaTaller(DatosTaller);\n";
          $javaC .= "	   Capa = xGetElementById(Elemento);\n";
          $javaC .= "	   xResizeTo(Capa, 620, 'auto');\n";
          $javaC .= "       xMoveTo(Capa, xClientWidth()/4, xScrollTop()+60);\n";

          $javaC .= "       document.getElementById('titulo').innerHTML = '<center>'+tit+'</center>';\n";
          $javaC .= "       document.getElementById('error').innerHTML = '';\n";
          
          $javaC .= "       ele = xGetElementById('titulo');\n";
          $javaC .= "       xResizeTo(ele, 600, 20);\n";
          $javaC .= "       xMoveTo(ele, 0, 0);\n";
          
          $javaC .= "       ele = xGetElementById('cerrar');\n";
          $javaC .= "       xResizeTo(ele,20, 20);\n";
          $javaC .= "       xMoveTo(ele, 600, 0);\n";
          $javaC .= "   }\n";       
          
          $javaC .= "   function IniciarProgTaller(tit, Elemento)\n";
          $javaC .= "   {\n";
          $javaC .= "	   xajax_ProgramacionesTalleres(DatosTaller);\n";
          $javaC .= "	   Capa = xGetElementById(Elemento);\n";
          $javaC .= "	   xResizeTo(Capa, 620, 'auto');\n";
          $javaC .= "       xMoveTo(Capa, xClientWidth()/4, xScrollTop()+60);\n";

          $javaC .= "       document.getElementById('tituloP').innerHTML = '<center>'+tit+'</center>';\n";
          $javaC .= "       document.getElementById('errorP').innerHTML = '';\n";
          
          $javaC .= "       ele = xGetElementById('tituloP');\n";
          $javaC .= "       xResizeTo(ele, 600, 20);\n";
          $javaC .= "       xMoveTo(ele, 0, 0);\n";
          
          $javaC .= "       ele = xGetElementById('cerrarP');\n";
          $javaC .= "       xResizeTo(ele,20, 20);\n";
          $javaC .= "       xMoveTo(ele, 600, 0);\n";
          $javaC .= "   }\n";       
          
          $javaC .= "   function CancelarProgTaller(tit, Elemento)\n";
          $javaC .= "   {\n";
          $javaC .= "	   xajax_CancelarTalleres(DatosTaller);\n";
          $javaC .= "	   Capa = xGetElementById(Elemento);\n";
          $javaC .= "	   xResizeTo(Capa, 620, 'auto');\n";
          $javaC .= "       xMoveTo(Capa, xClientWidth()/4, xScrollTop()+60);\n";

          $javaC .= "       document.getElementById('tituloC').innerHTML = '<center>'+tit+'</center>';\n";
          $javaC .= "       document.getElementById('errorC').innerHTML = '';\n";
          
          $javaC .= "       ele = xGetElementById('tituloC');\n";
          $javaC .= "       xResizeTo(ele, 600, 20);\n";
          $javaC .= "       xMoveTo(ele, 0, 0);\n";
          
          $javaC .= "       ele = xGetElementById('cerrarC');\n";
          $javaC .= "       xResizeTo(ele,20, 20);\n";
          $javaC .= "       xMoveTo(ele, 600, 0);\n";
          $javaC .= "   }\n";       
          
          $javaC .= "   function ProgramarTallerSave(Taller)\n";
          $javaC .= "   {\n";
          $javaC .= "     fecha = xGetElementById('fechaini').value;\n";
          $javaC .= "     intervalos = xGetElementById('periodicidad').value;\n";
          $javaC .= "     HoraInicio = xGetElementById('Horaini').value;\n";
          $javaC .= "     HoraFin = xGetElementById('Horafin').value;\n";
          $javaC .= "     Nsesiones = xGetElementById('sesiones').value;\n";
          $javaC .= "     locationes = xGetElementById('ubicacion').value;\n";
          $javaC .= "     xajax_CrearProgramacionT(fecha, intervalos, HoraInicio, HoraFin, Nsesiones, Taller, locationes);\n";
          $javaC .= "   }\n";
          
          $javaC .= "   function CancelTallerP(Programacion, Taller)\n";
          $javaC .= "   {\n";
          $javaC .= "     xajax_CancelProgramacionT(Programacion, Taller);\n";
          $javaC .= "   }\n";
          
          $javaC .= "   function myOnDragStart(ele, mx, my)\n";
          $javaC .= "   {\n";
          $javaC .= "     window.status = '';\n";
          $javaC .= "     if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
          $javaC .= "     else xZIndex(ele, hiZ++);\n";
          $javaC .= "     ele.myTotalMX = 0;\n";
          $javaC .= "     ele.myTotalMY = 0;\n";
          $javaC .= "   }\n";
          
          $javaC .= "   function myOnDrag(ele, mdx, mdy)\n";
          $javaC .= "   {\n";
          $javaC .= "     if (ele.id == titulo) {\n";
          $javaC .= "       xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
          $javaC .= "     }\n";
          $javaC .= "     else {\n";
          $javaC .= "       xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
          $javaC .= "     }  \n";
          $javaC .= "     ele.myTotalMX += mdx;\n";
          $javaC .= "     ele.myTotalMY += mdy;\n";
          $javaC .= "   }\n";
               
          $javaC .= "   function myOnDragEnd(ele, mx, my)\n";
          $javaC .= "   {\n";
          $javaC .= "   }\n";
          
          $javaC.= "function MostrarCapa(Elemento)\n";
          $javaC.= "{\n;";
          $javaC.= "    capita = xGetElementById(Elemento);\n";
          $javaC.= "    capita.style.display = \"\";\n";
          $javaC.= "}\n";
          
          $javaC.= "function Cerrar(Elemento)\n";
          $javaC.= "{\n";
          $javaC.= "    capita = xGetElementById(Elemento);\n";          
          $javaC.= "    capita.style.display = \"none\";\n";
          $javaC.= "}\n";                    
          
          $javaC.= "function CerrarCapaConsulta()\n";
          $javaC.= "{\n";
          $javaC.= "    capita = xGetElementById(ContenedorConTaller);\n";          
          $javaC.= "    capita.style.display = \"none\";\n";
          $javaC.= "}\n";                    
          
          $javaC.= "function CerrarCapaProg()\n";
          $javaC.= "{\n";
          $javaC.= "    capita = xGetElementById(ContenedorProgTaller);\n";          
          $javaC.= "    capita.style.display = \"none\";\n";
          $javaC.= "}\n";                    
          
          $javaC.= "function CerrarCapaCancel()\n";
          $javaC.= "{\n";
          $javaC.= "    capita = xGetElementById(ContenedorCancelTaller);\n";          
          $javaC.= "    capita.style.display = \"none\";\n";
          $javaC.= "}\n";                    
          
          $javaC.= "function ActivarDesactivar(Taller, Estado)\n";
          $javaC.= "{\n";
          $javaC.= "    xajax_ActivarTaller(Taller, Estado);\n";          
          $javaC.= "}\n";                    
          
          $javaC.= "function load_page()\n";
          $javaC.= "{\n";
          $javaC.= "    location.reload();\n";
          $javaC.= "}\n";
          
          $javaC.= "</script>\n";
          
          $this->salida.= $javaC;
          $this->salida .= ThemeCerrarTabla();
          return true;
     }

     /**
     * Forma para inscribir participantes al taller psicologico.
     *
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function FrmInscripciones($Taller, $vectorPac)
     {
          $this->salida .= ThemeAbrirTabla('INSCRIPCIONES DE PARTICIPANTES AL TALLER');
          $this->Encabezado("INSCRIPCIONES");
          
          $VectPacTalleres = $this->PacientesEnTaller($Taller);
                    
		$this->salida .= "<br>\n";                    
          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
          $this->salida .= $this->SetStyle("MensajeError");
          $this->salida.="</table>";
          
		$this->salida .= "<br>\n";
          $accion = ModuloGetURL('app','ModuloAdminPsicologia','user','ConsultaPacienteInscripcion', array('Taller'=>$Taller));
          $this->salida .= "<form name=\"formafind\" action=\"$accion\" method=\"post\">";          
          $this->salida .= "<table align=\"center\" width=\"65%\"  border=\"1\">\n";
          $this->salida .= "  <tr class=\"modulo_table_title\">\n";
          $this->salida .= "      <td height='30'>PREINSCRIBIR PARTICIPANTE</td>\n";
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "      <td align=\"left\" width=\"50%\">No. IDENTIFICACION:&nbsp;&nbsp;\n";
          $this->salida .= "      <input type=\"text\" name='identificacion' id='identificacion' size=\"10\">\n";
          $this->salida .= "	&nbsp;&nbsp;<select name=\"tipo_id\" id=\"tipo_id\" class=\"select\">";
          $psiologos=$this->ConsultaTipos_ID();
          $this->salida .=" 	<option value=\"-1\" selected>---Seleccione---</option>";
          for($i=0;$i<sizeof($psiologos);$i++)
          {
               $value = $psiologos[$i]['tipo_id_paciente'];
               $titulo=$psiologos[$i]['descripcion'];
               if($value==$_REQUEST['tipo_id'])
               {
                    $this->salida .=" <option value=\"$value\">$titulo</option>";
               }else{
                    $this->salida .=" <option value=\"$value\">$titulo</option>";
               }
          }
          $this->salida .= "	</select>";
		$this->salida .= "  &nbsp;&nbsp;<input type=\"submit\" class=\"input-submit\" name='preinscribir' id='preinscribir' value=\"PRE INSCRIBIR\">\n";
          $this->salida .= "	</td>\n";
		$this->salida .= "  </tr>\n";
          if(is_array($VectPacTalleres))
	     {
               $this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
               $AccinonCla = ModuloGetURL('app','ModuloAdminPsicologia','user','ClausurarTallerPs', array('Taller'=>$Taller));
               $this->salida .= "      <td height='30' class=\"labe_error\"><a href=\"$AccinonCla\">CLAUSURAR TALLER</a></td>\n";
               $this->salida .= "  </tr>\n";
          }
          $this->salida.="</table>";
          $this->salida .= "</form>";
          
          if($vectorPac)
          {
			$accion = ModuloGetURL('app','ModuloAdminPsicologia','user','InscribirPaciente', array('Taller'=>$Taller, 'Paciente_Id'=>$vectorPac[0]['paciente_id'], 'Tipo_Paciente'=>$vectorPac[0]['tipo_id_paciente']));
               $this->salida .= "<form name=\"formafind\" action=\"$accion\" method=\"post\">";          
               $this->salida .= "<br>\n";
               $this->salida .= "<table align=\"center\" width=\"65%\"  border=\"1\" >\n";
               $this->salida .= "  <tr class=\"modulo_table_title\">\n";
               $this->salida .= "      <td colspan=\"4\">PARTICIPANTE</td>\n";
               $this->salida .= "  </tr>\n";
               $this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
               $this->salida .= "      <td align=\"left\" colspan=\"2\">NOMBRE DE PACIENTE:</td>\n";
               $this->salida .= "      <td align=\"left\" colspan=\"2\">".$vectorPac[0]['primer_nombre']." ".$vectorPac[0]['segundo_nombre']." ".$vectorPac[0]['primer_apellido']." ".$vectorPac[0]['segundo_apellido']."</td>\n";
               $this->salida .= "  </tr>\n";
               $this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
               $this->salida .= "      <td align=\"left\" width=\"20%\" class=\"modulo_list_oscuro\">IDENTIFICACION:</td>\n";
               $this->salida .= "      <td align=\"left\" width=\"30%\" class=\"modulo_list_oscuro\">".$vectorPac[0]['tipo_id_paciente']." - ".$vectorPac[0]['paciente_id']."</td>\n";
               $this->salida .= "      <td align=\"left\" width=\"20%\" class=\"modulo_list_oscuro\">SEXO:</td>\n";
               $this->salida .= "      <td align=\"left\" width=\"30%\" class=\"modulo_list_oscuro\">".$vectorPac[0]['sexo_id']."</td>\n";
               $this->salida .= "  </tr>\n";
          	$this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
               $this->salida .= "      <td align=\"center\" colspan=\"4\"><input type=\"submit\" class=\"input-submit\" name='inscribir' id='inscribir' value=\"INSCRIBIR\"></td>\n";
               $this->salida .= "  </tr>\n";
               $this->salida.="</table>";
               $this->salida .= "</form>";
          }
          
          if(is_array($VectPacTalleres))
          {
			$accion = ModuloGetURL('app','ModuloAdminPsicologia','user','InscribirPaciente', array('Taller'=>$Taller, 'Paciente_Id'=>$vectorPac[0]['paciente_id'], 'Tipo_Paciente'=>$vectorPac[0]['tipo_id_paciente']));
               $this->salida .= "<form name=\"formafind\" action=\"$accion\" method=\"post\">";          
               $this->salida .= "<br>\n";
               $this->salida .= "<table align=\"center\" width=\"65%\"  border=\"1\" >\n";
               $this->salida .= "  <tr class=\"modulo_table_title\">\n";
               $this->salida .= "      <td colspan=\"6\">PARTICIPANTES INSCRITOS EN EL TALLER ".$VectPacTalleres[0]['nombre_taller']."</td>\n";
               $this->salida .= "  </tr>\n";
               $this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
               $this->salida .= "      <td>Cod.</td>\n";
               $this->salida .= "      <td>NOMBRE PACIENTE</td>\n";
               $this->salida .= "      <td>IDENTIFICACION</td>\n";
               $this->salida .= "      <td>ELIMINAR</td>\n";
               $this->salida .= "      <td>APROBO</td>\n";
               $this->salida .= "      <td>REPROBO</td>\n";
               $this->salida .= "  </tr>\n";
               for($i=0; $i<sizeof($VectPacTalleres); $i++)
               {
                    if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
                    $a = $i + 1;
                    $this->salida .= "  <tr class=\"$estilo\">\n";
                    $this->salida .= "      <td align=\"center\">".$a."</td>\n";
                    $this->salida .= "      <td align=\"left\">".$VectPacTalleres[$i]['nombre']."</td>\n";
                    $this->salida .= "      <td align=\"left\">".$VectPacTalleres[$i]['identificacion']."</td>\n";
                    if($VectPacTalleres[$i]['sw_estado'] == 0)
                    {
                         $Eliminar=ModuloGetURL('app','ModuloAdminPsicologia','user','EliminarInscripcionPaciente', array('participacion'=>$VectPacTalleres[$i]['id_participacion'], 'Taller'=>$Taller));
                         $this->salida .= "      <td align=\"center\"><a href=\"$Eliminar\"><img src=\"".GetThemePath()."/images/delete2.gif\"  width='20' height='20' border=0></a></td>\n";
                         $Aprobar=ModuloGetURL('app','ModuloAdminPsicologia','user','AprobarPaciente', array('sw'=>'1', 'participacion'=>$VectPacTalleres[$i]['id_participacion'],'Taller'=>$Taller ));
                         $this->salida .= "      <td align=\"center\"><a href=\"$Aprobar\"><img src=\"".GetThemePath()."/images/ok.png\"  width='20' height='20' border=0></a></td>\n";
                         $Reprobar=ModuloGetURL('app','ModuloAdminPsicologia','user','AprobarPaciente', array('sw'=>'2', 'participacion'=>$VectPacTalleres[$i]['id_participacion'], 'Taller'=>$Taller));
                         $this->salida .= "      <td align=\"center\"><a href=\"$Reprobar\"><img src=\"".GetThemePath()."/images/delete.gif\"  width='15' height='15' border=0></a></td>\n";
                    }elseif($VectPacTalleres[$i]['sw_estado'] == 1)
                    {
                         $this->salida .= "      <td align=\"center\">&nbsp;</td>\n";
                         $this->salida .= "      <td align=\"center\"><img src=\"".GetThemePath()."/images/ok.png\"  width='20' height='20' border=0 title=\"Aprobo el taller.\"></td>\n";
                         $this->salida .= "      <td align=\"center\">&nbsp;</td>\n";
                    }
                    elseif($VectPacTalleres[$i]['sw_estado'] == 2)
                    {
                         $this->salida .= "      <td align=\"center\">&nbsp;</td>\n";
                         $this->salida .= "      <td align=\"center\">&nbsp;</td>\n";
                         $this->salida .= "      <td align=\"center\"><img src=\"".GetThemePath()."/images/delete.gif\"  width='15' height='15' border=0 title=\"Reprobo el taller.\"></td>\n";
                    }
                    $this->salida .= "  </tr>\n";
               }
               $this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
               $this->salida .= "      <td align=\"center\" colspan=\"6\">&nbsp;</td>\n";
               $this->salida .= "  </tr>\n";
               $this->salida.="</table>";
               $this->salida .= "</form>";
          }
          
          $accionI=ModuloGetURL('app','ModuloAdminPsicologia','user','FrmAdmon_TalleresPsicologicos');
          $this->salida .= "<form name=\"formabuscar\" action=\"$accionI\" method=\"post\">";
          $this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"></p>";
          $this->salida .= "</form>";
          $this->salida .= ThemeCerrarTabla();
          return true;
     }
     
     
     /**
     * Forma para mostrar el listado tipos de motivos de consulta.
     *
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function FrmEdicionMotivos()
     {
          $this->IncludeJS("CrossBrowser");
          $this->IncludeJS("CrossBrowserDrag");
          $this->IncludeJS("CrossBrowserEvent");
          $this->IncludeJS("RemoteScripting");
     	$this->IncludeJS("ScriptsRemotos/misfunciones.js",'app','ModuloAdminPsicologia');

          $this->salida .= ThemeAbrirTabla('TIPOS MOTIVOS DE CONSULTA');
          $this->Encabezado("MOTIVOS CONSULTA");
          $listadoMotivos = $this->GetTiposMotivosConsulta();
          if(is_array($listadoMotivos))
          {
               $this->salida .= "<br>\n";
               $this->salida .= "<table align=\"center\" width=\"80%\"  border=\"0\" >\n";
               $this->salida .= "  <tr class=\"modulo_table_title\">\n";
               $this->salida .= "      <td colspan='4' height='30'>MOTIVOS DE CONSULTA</td>\n";
               $this->salida .= "  </tr>\n";
               $this->salida .= "  <tr class=\"modulo_table_title\">\n";
               $this->salida .= "      <td align=\"center\">ID MOTIVO</td>\n";
               $this->salida .= "      <td align=\"center\">NOMBRE MOTIVO</td>\n";                              
               $this->salida .= "      <td align=\"center\">EDITAR</td>\n";               
               $this->salida .= "      <td align=\"center\">DESACTIVAR</td>\n";
               $this->salida .= "  </tr>\n";
                    
               for($i=0; $i<sizeof($listadoMotivos); $i++)
               {
                    if($i % 2){ $estilo='modulo_list_claro';}
                    else{$estilo='modulo_list_claro';}

                    $this->salida .= "<tr align=\"center\" class='$estilo'>\n";
                    $this->salida .= "      <td class=\"$estilo\">".$listadoMotivos[$i][motivo_id]."</td>\n";
                    $this->salida .= "      <td align=\"left\" class=\"$estilo\">".$listadoMotivos[$i][descripcion]."</td>\n";
                    $javaEditar = "javascript:MostrarCapa('ContenedorEdMotivo');IniciarEdMotivo('EDITAR TIPO MOTIVO CONSULTA', 'ContenedorEdMotivo', '".$listadoMotivos[$i][motivo_id]."');CargarContenedor('ContenedorEdMotivo');";
                    $this->salida .= "      <td align=\"center\" class=\"$estilo\"><a href=\"$javaEditar\"><img src=\"".GetThemePath()."/images/edita.png\" border=0 title='Editar Motivo.'></a></td>\n";
                    if($listadoMotivos[$i][sw_activacion] == 0)
                    {
                         $hrefEl=ModuloGetURL('app','ModuloAdminPsicologia','user','EdicionActivacionMotivo',array('motivo_id'=>$listadoMotivos[$i][motivo_id], 'sw'=>"0"));
                         $this->salida .= "      <td align=\"center\" class=\"$estilo\"><a href=\"$hrefEl\"><img src=\"".GetThemePath()."/images/activo.gif\" border=0 title='Desactivar Motivo.'></a></td>\n";
                    }
                    else
                    {
                         $hrefEl=ModuloGetURL('app','ModuloAdminPsicologia','user','EdicionActivacionMotivo',array('motivo_id'=>$listadoMotivos[$i][motivo_id], 'sw'=>"1"));
                         $this->salida .= "      <td align=\"center\" class=\"$estilo\"><a href=\"$hrefEl\"><img src=\"".GetThemePath()."/images/inactivoip.gif\" border=0 title='Desactivar Motivo.'></a></td>\n";
                    }
                    $this->salida .= "</tr>\n";
               }
               $this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
               $javaCrear = "javascript:MostrarCapa('ContenedorCrearMotivo');IniciarCrearMotivo('CREAR TIPO MOTIVO CONSULTA', 'ContenedorCrearMotivo');CargarContenedor('ContenedorCrearMotivo');";
               $this->salida .= "      <td colspan='4' height='30'><a href=\"$javaCrear\">Crear Motivo</a></td>\n";
               $this->salida .= "  </tr>\n";
               $this->salida .= "  </table>\n";
          }
          else
          {
          	$this->salida .= "<br><div align=\"center\" class=\"label_mark\">NO HAY MOTIVOS DE CONSULTA CREADOS</div>";
          }
          
          $this->salida.="<div id='ContenedorEdMotivo' class='d2Container' style=\"display:none\">";
          $Cambio = ModuloGetURL('app','ModuloAdminPsicologia','user','EdicionTipoMotivoConsulta');
          $this->salida .= "    <form name=\"CambioMot\" action=\"$Cambio\" method=\"post\">\n";
          $this->salida .= "    <div id='tituloEd' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
          $this->salida .= "    <div id='cerrarEd' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorEdMotivo');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
          $this->salida .= "    <div id='errorEd' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
          $this->salida .= "    <div id='ContenidoEdMotivo'>\n";
          $this->salida .= "            <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
          $this->salida .= "                <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "                    <td colspan=\"2\">EDICION MOTIVOS CONSULTA</td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "                <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "                    <td align=\"left\" width=\"50%\">DESCRIPCION MOTIVO\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                    <td align=\"center\" width=\"50%\">\n";
          $this->salida .= "                        <input type=\"text\" class=\"input-text\" size=\"20\" id=\"descMot\" name=\"descMot\" value=\"\">\n";
          $this->salida .= "                        <input type=\"hidden\" id=\"IdMotivo\" name=\"IdMotivo\" value=\"\">\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "                <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"center\">\n";
          $this->salida .= "                        <input type=\"submit\" class=\"input-submit\" name=\"guardarMot\" value=\"Editar\">\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "            </table>\n";
          $this->salida .= "        </form>\n";
          $this->salida .= "    </div>\n";     
          $this->salida.="</div>";

          $this->salida.="<div id='ContenedorCrearMotivo' class='d2Container' style=\"display:none\">";
          $Crear = ModuloGetURL('app','ModuloAdminPsicologia','user','CreacionTipoMotivoConsulta');
          $this->salida .= "    <form name=\"CrearMot\" action=\"$Crear\" method=\"post\">\n";
          $this->salida .= "    <div id='tituloCr' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
          $this->salida .= "    <div id='cerrarCr' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorCrearMotivo');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
          $this->salida .= "    <div id='errorCr' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
          $this->salida .= "    <div id='ContenidoCrearMotivo'>\n";
          $this->salida .= "            <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
          $this->salida .= "                <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "                    <td colspan=\"2\">CREACION MOTIVOS CONSULTA</td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "                <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "                    <td align=\"left\" width=\"50%\">DESCRIPCION MOTIVO\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                    <td align=\"center\" width=\"50%\">\n";
          $this->salida .= "                        <input type=\"text\" class=\"input-text\" size=\"20\" id=\"Motivo\" name=\"Motivo\" value=\"\">\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "                <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"center\">\n";
          $this->salida .= "                        <input type=\"submit\" class=\"input-submit\" name=\"SaveMot\" value=\"Crear\">\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "            </table>\n";
          $this->salida .= "        </form>\n";
          $this->salida .= "    </div>\n";     
          $this->salida.="</div>";
          
          $accion=ModuloGetURL('app','ModuloAdminPsicologia','user','FormaInicial');
          $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
          $this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"></p>";
          $this->salida .= "</form>";
          
          $javaC = "<script>\n";
          $javaC .= "   var contenedor\n";
          
          $javaC .= "   function CargarContenedor(Elemento)\n";
          $javaC .= "   {\n";
          $javaC .= "        contenedor = Elemento;\n";
          $javaC .= "   }\n";

          $javaC .= "   var titulo = 'titulo';\n";
          $javaC .= "   var hiZ = 2;\n";
          $javaC .= "   var DatosFactor = new Array();\n";
          $javaC .= "   var EnvioFactor = new Array();\n";
          
          $javaC .= "   function IniciarEdMotivo(tit, Elemento, Motivo)\n";
          $javaC .= "   {\n";
          $javaC .= "	   Capa = xGetElementById(Elemento);\n";
          $javaC .= "       xMoveTo(Capa, xClientWidth()/2, xScrollTop()+60);\n";

          $javaC .= "       document.CambioMot.IdMotivo.value = Motivo;\n";
          $javaC .= "       document.getElementById('tituloEd').innerHTML = '<center>'+tit+'</center>';\n";
          $javaC .= "       document.getElementById('errorEd').innerHTML = '';\n";
          
          $javaC .= "       ele = xGetElementById('tituloEd');\n";
          $javaC .= "       xResizeTo(ele, 280, 20);\n";
          $javaC .= "       xMoveTo(ele, 0, 0);\n";
          
          $javaC .= "       ele = xGetElementById('cerrarEd');\n";
          $javaC .= "       xResizeTo(ele,20, 20);\n";
          $javaC .= "       xMoveTo(ele, 280, 0);\n";
          $javaC .= "   }\n";       
          
          $javaC .= "   function IniciarCrearMotivo(tit, Elemento)\n";
          $javaC .= "   {\n";
          $javaC .= "	   Capa = xGetElementById(Elemento);\n";
          $javaC .= "       xMoveTo(Capa, xClientWidth()/2, xScrollTop()+60);\n";

          $javaC .= "       document.getElementById('tituloCr').innerHTML = '<center>'+tit+'</center>';\n";
          $javaC .= "       document.getElementById('errorCr').innerHTML = '';\n";
          
          $javaC .= "       ele = xGetElementById('tituloCr');\n";
          $javaC .= "       xResizeTo(ele, 280, 20);\n";
          $javaC .= "       xMoveTo(ele, 0, 0);\n";
          
          $javaC .= "       ele = xGetElementById('cerrarCr');\n";
          $javaC .= "       xResizeTo(ele,20, 20);\n";
          $javaC .= "       xMoveTo(ele, 280, 0);\n";
          $javaC .= "   }\n";       
          
          $javaC .= "   function myOnDragStart(ele, mx, my)\n";
          $javaC .= "   {\n";
          $javaC .= "     window.status = '';\n";
          $javaC .= "     if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
          $javaC .= "     else xZIndex(ele, hiZ++);\n";
          $javaC .= "     ele.myTotalMX = 0;\n";
          $javaC .= "     ele.myTotalMY = 0;\n";
          $javaC .= "   }\n";
          
          $javaC .= "   function myOnDrag(ele, mdx, mdy)\n";
          $javaC .= "   {\n";
          $javaC .= "     if (ele.id == titulo) {\n";
          $javaC .= "       xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
          $javaC .= "     }\n";
          $javaC .= "     else {\n";
          $javaC .= "       xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
          $javaC .= "     }  \n";
          $javaC .= "     ele.myTotalMX += mdx;\n";
          $javaC .= "     ele.myTotalMY += mdy;\n";
          $javaC .= "   }\n";
               
          $javaC .= "   function myOnDragEnd(ele, mx, my)\n";
          $javaC .= "   {\n";
          $javaC .= "   }\n";
          
          $javaC.= "function MostrarCapa(Elemento)\n";
          $javaC.= "{\n;";
          $javaC.= "    capita = xGetElementById(Elemento);\n";
          $javaC.= "    capita.style.display = \"\";\n";
          $javaC.= "}\n";
          
          $javaC.= "function Cerrar(Elemento)\n";
          $javaC.= "{\n";
          $javaC.= "    capita = xGetElementById(Elemento);\n";          
          $javaC.= "    capita.style.display = \"none\";\n";          
          $javaC.= "}\n";                    
          
          $javaC.= "function load_page()\n";
          $javaC.= "{\n";
          $javaC.= "    location.reload();\n";
          $javaC.= "}\n";
          
          $javaC.= "</script>\n";
          $this->salida.= $javaC;

          $this->salida .= ThemeCerrarTabla();
          return true;
     }
     
          
     /**
     * Forma para mostrar el listado tipos de motivos detalle de consulta.
     *
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function FrmEdicionMotivosDetalle()
     {
          $this->IncludeJS("CrossBrowser");
          $this->IncludeJS("CrossBrowserDrag");
          $this->IncludeJS("CrossBrowserEvent");
          $this->IncludeJS("RemoteScripting");
     	$this->IncludeJS("ScriptsRemotos/misfunciones.js",'app','ModuloAdminPsicologia');

          $this->salida .= ThemeAbrirTabla('MOTIVOS DETALLES DE CONSULTA');
          $this->Encabezado("MOTIVOS DETALLE CONSULTA");
          $listadoMotivos = $this->GetTiposMotivosConsulta();
          if(is_array($listadoMotivos))
          {
               $this->salida .= "<br>\n";
               $this->salida .= "<table align=\"center\" width=\"80%\"  border=\"0\" >\n";
               $this->salida .= "  <tr class=\"modulo_table_title\">\n";
               $this->salida .= "      <td colspan='3' height='30'>MOTIVOS DETALLES DE CONSULTA</td>\n";
               $this->salida .= "  </tr>\n";
               $this->salida .= "  <tr class=\"modulo_table_title\">\n";
               $this->salida .= "      <td align=\"center\">NOMBRE MOTIVO</td>\n";
               $this->salida .= "      <td align=\"center\">INFO MOTIVO DETALLE</td>\n";
               $this->salida .= "      <td align=\"center\">CREACION</td>\n";                             
               $this->salida .= "  </tr>\n";
                    
               for($i=0; $i<sizeof($listadoMotivos); $i++)
               {
                    if($i % 2){ $estilo='modulo_list_claro';}
                    else{$estilo='modulo_list_claro';}

                    $MotivosDet = array();
                    $MotivosDet = $this->GetMotivosDetalleConsulta($listadoMotivos[$i][motivo_id]);
                    
                    $this->salida .= "<tr align=\"center\" class='$estilo'>\n";
                    $this->salida .= "      <td align=\"left\" class=\"$estilo\">".$listadoMotivos[$i][descripcion]."</td>\n";
                    $this->salida .= "      <td align=\"center\">";
                    $this->salida .= "      <table width=\"100%\">";
                    for($j=0; $j<sizeof($MotivosDet); $j++)
                    {
                         if($i % 2){ $estilo1='modulo_list_oscuro';}
                    		else{$estilo1='modulo_list_oscuro';}
                              
					$this->salida .= "      <tr class='$estilo1'>";
                         $this->salida .= "      <td align=\"left\" width=\"70%\">".strtoupper($MotivosDet[$j][descripcion])."</td>";
                         $EditarDet = "javascript:MostrarCapa('ContenedorEdMotivoDet');IniciarEdetMotivo('EDITAR TIPO MOTIVO DETALLE', 'ContenedorEdMotivoDet', '".$MotivosDet[$j][motivo_detalle_id]."');CargarContenedor('ContenedorEdMotivoDet');";
                         $this->salida .= "      <td align=\"center\" class=\"$estilo1\"><a href=\"$EditarDet\"><img src=\"".GetThemePath()."/images/edita.png\" border=0 title='Editar Motivo Detalle.'></a></td>\n";
                         if($MotivosDet[$j][sw_activacion] == 0)
                         {
                              $hrefEl=ModuloGetURL('app','ModuloAdminPsicologia','user','EdicionActivacionMotivoDet',array('motivo_id_det'=>$MotivosDet[$j][motivo_detalle_id], 'sw'=>"0"));
                              $this->salida .= "      <td align=\"center\" class=\"$estilo1\" width=\"15%\"><a href=\"$hrefEl\"><img src=\"".GetThemePath()."/images/activo.gif\" border=0 title='Desactivar Motivo.'></a></td>\n";
                         }
                         else
                         {
                              $hrefEl=ModuloGetURL('app','ModuloAdminPsicologia','user','EdicionActivacionMotivoDet',array('motivo_id_det'=>$MotivosDet[$j][motivo_detalle_id], 'sw'=>"1"));
                              $this->salida .= "      <td align=\"center\" class=\"$estilo1\" width=\"15%\"><a href=\"$hrefEl\"><img src=\"".GetThemePath()."/images/inactivoip.gif\" border=0 title='Desactivar Motivo.'></a></td>\n";
                         }
                         $this->salida .= "      </tr>";
                    }
                    $this->salida .= "      </table>";
                    $this->salida .= "      </td>\n";
                    $javaCrearDet = "javascript:MostrarCapa('ContenedorCrearMotivoDet');IniciarCrearMotivoDet('CREAR TIPO MOTIVO DETALLE', 'ContenedorCrearMotivoDet', '".$listadoMotivos[$i][motivo_id]."');CargarContenedor('ContenedorCrearMotivoDet');";
                    $this->salida .= "      <td align=\"center\" class=\"$estilo\"><a href=\"$javaCrearDet\">Crear Motivo Detalle</a></td>\n";
                    $this->salida .= "</tr>\n";
                    unset($MotivosDet);
               }
               $this->salida .= "  </tr>\n";
               $this->salida .= "  </table>\n";
          }
          else
          {
          	$this->salida .= "<br><div align=\"center\" class=\"label_mark\">NO HAY MOTIVOS DE CONSULTA CREADOS</div>";
          }
          
          $this->salida.="<div id='ContenedorEdMotivoDet' class='d2Container' style=\"display:none\">";
          $CambioDet = ModuloGetURL('app','ModuloAdminPsicologia','user','EdicionTipoMotivoDetConsulta');
          $this->salida .= "    <form name=\"CambioMotDet\" action=\"$CambioDet\" method=\"post\">\n";
          $this->salida .= "    <div id='tituloEdet' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
          $this->salida .= "    <div id='cerrarEdet' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorEdMotivoDet');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
          $this->salida .= "    <div id='errorEdet' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
          $this->salida .= "    <div id='ContenidoEdMotivoDet'>\n";
          $this->salida .= "            <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
          $this->salida .= "                <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "                    <td colspan=\"2\">EDICION MOTIVOS DETALLE</td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "                <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "                    <td align=\"left\" width=\"50%\">DESCRIPCION MOTIVO\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                    <td align=\"center\" width=\"50%\">\n";
          $this->salida .= "                        <input type=\"text\" class=\"input-text\" size=\"20\" id=\"descMotDet\" name=\"descMotDet\" value=\"\">\n";
          $this->salida .= "                        <input type=\"hidden\" id=\"IdMotivoDet\" name=\"IdMotivoDet\" value=\"\">\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "                <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"center\">\n";
          $this->salida .= "                        <input type=\"submit\" class=\"input-submit\" name=\"guardarMotDet\" value=\"Editar\">\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "            </table>\n";
          $this->salida .= "        </form>\n";
          $this->salida .= "    </div>\n";     
          $this->salida.="</div>";

          $this->salida.="<div id='ContenedorCrearMotivoDet' class='d2Container' style=\"display:none\">";
          $Crear = ModuloGetURL('app','ModuloAdminPsicologia','user','CreacionTipoMotivoConsultaDet');
          $this->salida .= "    <form name=\"CrearMotDet\" action=\"$Crear\" method=\"post\">\n";
          $this->salida .= "    <div id='tituloCrDet' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
          $this->salida .= "    <div id='cerrarCrDet' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorCrearMotivoDet');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
          $this->salida .= "    <div id='errorCrDet' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
          $this->salida .= "    <div id='ContenidoCrearMotivoDet'>\n";
          $this->salida .= "            <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
          $this->salida .= "                <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "                    <td colspan=\"2\">CREACION MOTIVOS CONSULTA DETALLE</td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "                <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "                    <td align=\"left\" width=\"50%\">DESCRIPCION MOTIVO\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                    <td align=\"center\" width=\"50%\">\n";
          $this->salida .= "                        <input type=\"text\" class=\"input-text\" size=\"20\" id=\"MotivoDet\" name=\"MotivoDet\" value=\"\">\n";
          $this->salida .= "                        <input type=\"hidden\" id=\"IdMotivoCrDet\" name=\"IdMotivoCrDet\" value=\"\">\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "                <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"center\">\n";
          $this->salida .= "                        <input type=\"submit\" class=\"input-submit\" name=\"SaveMotDet\" value=\"Crear\">\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "            </table>\n";
          $this->salida .= "        </form>\n";
          $this->salida .= "    </div>\n";     
          $this->salida.="</div>";
          
          $accion=ModuloGetURL('app','ModuloAdminPsicologia','user','FormaInicial');
          $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
          $this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"></p>";
          $this->salida .= "</form>";
          
          $javaC = "<script>\n";
          $javaC .= "   var contenedor\n";
          
          $javaC .= "   function CargarContenedor(Elemento)\n";
          $javaC .= "   {\n";
          $javaC .= "        contenedor = Elemento;\n";
          $javaC .= "   }\n";

          $javaC .= "   var titulo = 'titulo';\n";
          $javaC .= "   var hiZ = 2;\n";
          $javaC .= "   var DatosFactor = new Array();\n";
          $javaC .= "   var EnvioFactor = new Array();\n";
          
          $javaC .= "   function IniciarEdetMotivo(tit, Elemento, Motivo)\n";
          $javaC .= "   {\n";
          $javaC .= "	   Capa = xGetElementById(Elemento);\n";
          $javaC .= "       xMoveTo(Capa, xClientWidth()/2, xScrollTop()+60);\n";

          $javaC .= "       document.CambioMotDet.IdMotivoDet.value = Motivo;\n";
          $javaC .= "       document.getElementById('tituloEdet').innerHTML = '<center>'+tit+'</center>';\n";
          $javaC .= "       document.getElementById('errorEdet').innerHTML = '';\n";
          
          $javaC .= "       ele = xGetElementById('tituloEdet');\n";
          $javaC .= "       xResizeTo(ele, 280, 20);\n";
          $javaC .= "       xMoveTo(ele, 0, 0);\n";
          
          $javaC .= "       ele = xGetElementById('cerrarEdet');\n";
          $javaC .= "       xResizeTo(ele,20, 20);\n";
          $javaC .= "       xMoveTo(ele, 280, 0);\n";
          $javaC .= "   }\n";       
          
          $javaC .= "   function IniciarCrearMotivoDet(tit, Elemento, Motivo)\n";
          $javaC .= "   {\n";
          $javaC .= "	   Capa = xGetElementById(Elemento);\n";
          $javaC .= "       xMoveTo(Capa, xClientWidth()/2, xScrollTop()+60);\n";
          
          $javaC .= "       document.CrearMotDet.IdMotivoCrDet.value = Motivo;\n";
		$javaC .= "       document.getElementById('tituloCrDet').innerHTML = '<center>'+tit+'</center>';\n";
          $javaC .= "       document.getElementById('errorCrDet').innerHTML = '';\n";
          
          $javaC .= "       ele = xGetElementById('tituloCrDet');\n";
          $javaC .= "       xResizeTo(ele, 280, 20);\n";
          $javaC .= "       xMoveTo(ele, 0, 0);\n";
          
          $javaC .= "       ele = xGetElementById('cerrarCrDet');\n";
          $javaC .= "       xResizeTo(ele,20, 20);\n";
          $javaC .= "       xMoveTo(ele, 280, 0);\n";
          $javaC .= "   }\n";       
          
          $javaC .= "   function myOnDragStart(ele, mx, my)\n";
          $javaC .= "   {\n";
          $javaC .= "     window.status = '';\n";
          $javaC .= "     if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
          $javaC .= "     else xZIndex(ele, hiZ++);\n";
          $javaC .= "     ele.myTotalMX = 0;\n";
          $javaC .= "     ele.myTotalMY = 0;\n";
          $javaC .= "   }\n";
          
          $javaC .= "   function myOnDrag(ele, mdx, mdy)\n";
          $javaC .= "   {\n";
          $javaC .= "     if (ele.id == titulo) {\n";
          $javaC .= "       xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
          $javaC .= "     }\n";
          $javaC .= "     else {\n";
          $javaC .= "       xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
          $javaC .= "     }  \n";
          $javaC .= "     ele.myTotalMX += mdx;\n";
          $javaC .= "     ele.myTotalMY += mdy;\n";
          $javaC .= "   }\n";
               
          $javaC .= "   function myOnDragEnd(ele, mx, my)\n";
          $javaC .= "   {\n";
          $javaC .= "   }\n";
          
          $javaC.= "function MostrarCapa(Elemento)\n";
          $javaC.= "{\n;";
          $javaC.= "    capita = xGetElementById(Elemento);\n";
          $javaC.= "    capita.style.display = \"\";\n";
          $javaC.= "}\n";
          
          $javaC.= "function Cerrar(Elemento)\n";
          $javaC.= "{\n";
          $javaC.= "    capita = xGetElementById(Elemento);\n";          
          $javaC.= "    capita.style.display = \"none\";\n";          
          $javaC.= "}\n";                    
          
          $javaC.= "function load_page()\n";
          $javaC.= "{\n";
          $javaC.= "    location.reload();\n";
          $javaC.= "}\n";
          
          $javaC.= "</script>\n";
          $this->salida.= $javaC;

          $this->salida .= ThemeCerrarTabla();
          return true;
     }

          
     /**
     * Forma para mostrar el listado tipos de motivos de consulta.
     *
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function FrmEdicionTrabajos()
     {
          $this->IncludeJS("CrossBrowser");
          $this->IncludeJS("CrossBrowserDrag");
          $this->IncludeJS("CrossBrowserEvent");
          $this->IncludeJS("RemoteScripting");
     	$this->IncludeJS("ScriptsRemotos/misfunciones.js",'app','ModuloAdminPsicologia');

          $this->salida .= ThemeAbrirTabla('TIPOS TRABAJOS REALIZADOS');
          $this->Encabezado("TIPOS TRABAJOS");
          $listadoTrabajos = $this->GetTiposTrabajos();
          if(is_array($listadoTrabajos))
          {
               $this->salida .= "<br>\n";
               $this->salida .= "<table align=\"center\" width=\"80%\"  border=\"0\" >\n";
               $this->salida .= "  <tr class=\"modulo_table_title\">\n";
               $this->salida .= "      <td colspan='4' height='30'>TRABAJOS REALIZADOS</td>\n";
               $this->salida .= "  </tr>\n";
               $this->salida .= "  <tr class=\"modulo_table_title\">\n";
               $this->salida .= "      <td align=\"center\">ID TRABAJO</td>\n";
               $this->salida .= "      <td align=\"center\">NOMBRE TRABAJO</td>\n";                              
               $this->salida .= "      <td align=\"center\">EDITAR</td>\n";               
               $this->salida .= "      <td align=\"center\">DESACTIVAR</td>\n";
               $this->salida .= "  </tr>\n";
                    
               for($i=0; $i<sizeof($listadoTrabajos); $i++)
               {
                    if($i % 2){ $estilo='modulo_list_claro';}
                    else{$estilo='modulo_list_claro';}

                    $this->salida .= "<tr align=\"center\" class='$estilo'>\n";
                    $this->salida .= "      <td class=\"$estilo\">".$listadoTrabajos[$i][trabajo_id]."</td>\n";
                    $this->salida .= "      <td align=\"left\" class=\"$estilo\">".$listadoTrabajos[$i][descripcion]."</td>\n";
                    $javaEditar = "javascript:MostrarCapa('ContenedorEdMotivo');IniciarEdMotivo('EDITAR TIPO TRABAJO REALIZADO', 'ContenedorEdMotivo', '".$listadoTrabajos[$i][trabajo_id]."');CargarContenedor('ContenedorEdMotivo');";
                    $this->salida .= "      <td align=\"center\" class=\"$estilo\"><a href=\"$javaEditar\"><img src=\"".GetThemePath()."/images/edita.png\" border=0 title='Editar Trabajo.'></a></td>\n";
                    if($listadoTrabajos[$i][sw_activacion] == 0)
                    {
                         $hrefEl=ModuloGetURL('app','ModuloAdminPsicologia','user','EdicionActivacionTrabajo',array('motivo_id'=>$listadoTrabajos[$i][trabajo_id], 'sw'=>"0"));
                         $this->salida .= "      <td align=\"center\" class=\"$estilo\"><a href=\"$hrefEl\"><img src=\"".GetThemePath()."/images/activo.gif\" border=0 title='Activar Trabajo.'></a></td>\n";
                    }
                    else
                    {
                         $hrefEl=ModuloGetURL('app','ModuloAdminPsicologia','user','EdicionActivacionTrabajo',array('motivo_id'=>$listadoTrabajos[$i][trabajo_id], 'sw'=>"1"));
                         $this->salida .= "      <td align=\"center\" class=\"$estilo\"><a href=\"$hrefEl\"><img src=\"".GetThemePath()."/images/inactivoip.gif\" border=0 title='Desactivar Trabajo.'></a></td>\n";
                    }
                    $this->salida .= "</tr>\n";
               }
               $this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
               $javaCrear = "javascript:MostrarCapa('ContenedorCrearTrabajo');IniciarCrearTrabajo('CREAR TIPO TRABAJO REALIZADO', 'ContenedorCrearTrabajo');CargarContenedor('ContenedorCrearTrabajo');";
               $this->salida .= "      <td colspan='4' height='30'><a href=\"$javaCrear\">Crear Trabajo</a></td>\n";
               $this->salida .= "  </tr>\n";
               $this->salida .= "  </table>\n";
          }
          else
          {
          	$this->salida .= "<br><div align=\"center\" class=\"label_mark\">NO HAY TRABAJOS REALIZADOS CREADOS</div>";
          }
          
          $this->salida.="<div id='ContenedorEdMotivo' class='d2Container' style=\"display:none\">";
          $Cambio = ModuloGetURL('app','ModuloAdminPsicologia','user','EdicionTipoTrabajoRealizado');
          $this->salida .= "    <form name=\"CambioMot\" action=\"$Cambio\" method=\"post\">\n";
          $this->salida .= "    <div id='tituloEd' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
          $this->salida .= "    <div id='cerrarEd' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorEdMotivo');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
          $this->salida .= "    <div id='errorEd' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
          $this->salida .= "    <div id='ContenidoEdMotivo'>\n";
          $this->salida .= "            <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
          $this->salida .= "                <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "                    <td colspan=\"2\">EDICION TRABAJOS REALIZADOS</td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "                <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "                    <td align=\"left\" width=\"50%\">DESCRIPCION TRABAJO\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                    <td align=\"center\" width=\"50%\">\n";
          $this->salida .= "                        <input type=\"text\" class=\"input-text\" size=\"20\" id=\"descMot\" name=\"descMot\" value=\"\">\n";
          $this->salida .= "                        <input type=\"hidden\" id=\"IdMotivo\" name=\"IdMotivo\" value=\"\">\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "                <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"center\">\n";
          $this->salida .= "                        <input type=\"submit\" class=\"input-submit\" name=\"guardarMot\" value=\"Editar\">\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "            </table>\n";
          $this->salida .= "        </form>\n";
          $this->salida .= "    </div>\n";     
          $this->salida.="</div>";

          $this->salida.="<div id='ContenedorCrearTrabajo' class='d2Container' style=\"display:none\">";
          $Crear = ModuloGetURL('app','ModuloAdminPsicologia','user','CreacionTipoTrabajoRealizado');
          $this->salida .= "    <form name=\"CrearMot\" action=\"$Crear\" method=\"post\">\n";
          $this->salida .= "    <div id='tituloCr' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
          $this->salida .= "    <div id='cerrarCr' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorCrearMotivo');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
          $this->salida .= "    <div id='errorCr' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
          $this->salida .= "    <div id='ContenidoCrearMotivo'>\n";
          $this->salida .= "            <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
          $this->salida .= "                <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "                    <td colspan=\"2\">CREACION TIPOS TRABAJOS REALIZADOS</td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "                <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "                    <td align=\"left\" width=\"50%\">DESCRIPCION TRABAJO\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                    <td align=\"center\" width=\"50%\">\n";
          $this->salida .= "                        <input type=\"text\" class=\"input-text\" size=\"20\" id=\"Trabajo\" name=\"Trabajo\" value=\"\">\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "                <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"center\">\n";
          $this->salida .= "                        <input type=\"submit\" class=\"input-submit\" name=\"SaveTra\" value=\"Crear\">\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "            </table>\n";
          $this->salida .= "        </form>\n";
          $this->salida .= "    </div>\n";     
          $this->salida.="</div>";

          $accion=ModuloGetURL('app','ModuloAdminPsicologia','user','FormaInicial');
          $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
          $this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"></p>";
          $this->salida .= "</form>";
          
          $javaC = "<script>\n";
          $javaC .= "   var contenedor\n";
          
          $javaC .= "   function CargarContenedor(Elemento)\n";
          $javaC .= "   {\n";
          $javaC .= "        contenedor = Elemento;\n";
          $javaC .= "   }\n";

          $javaC .= "   var titulo = 'titulo';\n";
          $javaC .= "   var hiZ = 2;\n";
          $javaC .= "   var DatosFactor = new Array();\n";
          $javaC .= "   var EnvioFactor = new Array();\n";
          
          $javaC .= "   function IniciarEdMotivo(tit, Elemento, Motivo)\n";
          $javaC .= "   {\n";
          $javaC .= "	   Capa = xGetElementById(Elemento);\n";
          $javaC .= "       xMoveTo(Capa, xClientWidth()/2, xScrollTop()+60);\n";

          $javaC .= "       document.CambioMot.IdMotivo.value = Motivo;\n";
          $javaC .= "       document.getElementById('tituloEd').innerHTML = '<center>'+tit+'</center>';\n";
          $javaC .= "       document.getElementById('errorEd').innerHTML = '';\n";
          
          $javaC .= "       ele = xGetElementById('tituloEd');\n";
          $javaC .= "       xResizeTo(ele, 280, 20);\n";
          $javaC .= "       xMoveTo(ele, 0, 0);\n";
          
          $javaC .= "       ele = xGetElementById('cerrarEd');\n";
          $javaC .= "       xResizeTo(ele,20, 20);\n";
          $javaC .= "       xMoveTo(ele, 280, 0);\n";
          $javaC .= "   }\n";       
          
          $javaC .= "   function IniciarCrearTrabajo(tit, Elemento)\n";
          $javaC .= "   {\n";
          $javaC .= "	   Capa = xGetElementById(Elemento);\n";
          $javaC .= "       xMoveTo(Capa, xClientWidth()/2, xScrollTop()+60);\n";

          $javaC .= "       document.getElementById('tituloCr').innerHTML = '<center>'+tit+'</center>';\n";
          $javaC .= "       document.getElementById('errorCr').innerHTML = '';\n";
          
          $javaC .= "       ele = xGetElementById('tituloCr');\n";
          $javaC .= "       xResizeTo(ele, 280, 20);\n";
          $javaC .= "       xMoveTo(ele, 0, 0);\n";
          
          $javaC .= "       ele = xGetElementById('cerrarCr');\n";
          $javaC .= "       xResizeTo(ele,20, 20);\n";
          $javaC .= "       xMoveTo(ele, 280, 0);\n";
          $javaC .= "   }\n";       
          
          $javaC .= "   function myOnDragStart(ele, mx, my)\n";
          $javaC .= "   {\n";
          $javaC .= "     window.status = '';\n";
          $javaC .= "     if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
          $javaC .= "     else xZIndex(ele, hiZ++);\n";
          $javaC .= "     ele.myTotalMX = 0;\n";
          $javaC .= "     ele.myTotalMY = 0;\n";
          $javaC .= "   }\n";
          
          $javaC .= "   function myOnDrag(ele, mdx, mdy)\n";
          $javaC .= "   {\n";
          $javaC .= "     if (ele.id == titulo) {\n";
          $javaC .= "       xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
          $javaC .= "     }\n";
          $javaC .= "     else {\n";
          $javaC .= "       xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
          $javaC .= "     }  \n";
          $javaC .= "     ele.myTotalMX += mdx;\n";
          $javaC .= "     ele.myTotalMY += mdy;\n";
          $javaC .= "   }\n";
               
          $javaC .= "   function myOnDragEnd(ele, mx, my)\n";
          $javaC .= "   {\n";
          $javaC .= "   }\n";
          
          $javaC.= "function MostrarCapa(Elemento)\n";
          $javaC.= "{\n;";
          $javaC.= "    capita = xGetElementById(Elemento);\n";
          $javaC.= "    capita.style.display = \"\";\n";
          $javaC.= "}\n";
          
          $javaC.= "function Cerrar(Elemento)\n";
          $javaC.= "{\n";
          $javaC.= "    capita = xGetElementById(Elemento);\n";          
          $javaC.= "    capita.style.display = \"none\";\n";          
          $javaC.= "}\n";                    
          
          $javaC.= "function load_page()\n";
          $javaC.= "{\n";
          $javaC.= "    location.reload();\n";
          $javaC.= "}\n";
          
          $javaC.= "</script>\n";
          $this->salida.= $javaC;

          $this->salida .= ThemeCerrarTabla();
          return true;
     }
     
     
     /**
     * Forma para mostrar el listado tipos de trabajos realizados detalle.
     *
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function FrmEdicionTrabajosDetalle()
     {
          $this->IncludeJS("CrossBrowser");
          $this->IncludeJS("CrossBrowserDrag");
          $this->IncludeJS("CrossBrowserEvent");
          $this->IncludeJS("RemoteScripting");
     	$this->IncludeJS("ScriptsRemotos/misfunciones.js",'app','ModuloAdminPsicologia');

          $this->salida .= ThemeAbrirTabla('TRABAJOS REALIZADOS DETALLE');
          $this->Encabezado("TRABAJOS REALIZADOS DETALLE");
          $listadoMotivos = $this->GetTiposTrabajos();
          if(is_array($listadoMotivos))
          {
               $this->salida .= "<br>\n";
               $this->salida .= "<table align=\"center\" width=\"80%\"  border=\"0\" >\n";
               $this->salida .= "  <tr class=\"modulo_table_title\">\n";
               $this->salida .= "      <td colspan='3' height='30'>TRABAJOS REALIZADOS DETALLE</td>\n";
               $this->salida .= "  </tr>\n";
               $this->salida .= "  <tr class=\"modulo_table_title\">\n";
               $this->salida .= "      <td align=\"center\">NOMBRE TRABAJO</td>\n";
               $this->salida .= "      <td align=\"center\">INFO TRABAJO DETALLE</td>\n";
               $this->salida .= "      <td align=\"center\">CREACION</td>\n";                             
               $this->salida .= "  </tr>\n";
                    
               for($i=0; $i<sizeof($listadoMotivos); $i++)
               {
                    if($i % 2){ $estilo='modulo_list_claro';}
                    else{$estilo='modulo_list_claro';}

                    $MotivosDet = array();
                    $MotivosDet = $this->GetTrabajosRealizadosDetalle($listadoMotivos[$i][trabajo_id]);
                    
                    $this->salida .= "<tr align=\"center\" class='$estilo'>\n";
                    $this->salida .= "      <td align=\"left\" class=\"$estilo\">".$listadoMotivos[$i][descripcion]."</td>\n";
                    $this->salida .= "      <td align=\"center\">";
                    $this->salida .= "      <table width=\"100%\">";
                    for($j=0; $j<sizeof($MotivosDet); $j++)
                    {
                         if($i % 2){ $estilo1='modulo_list_oscuro';}
                    		else{$estilo1='modulo_list_oscuro';}
                              
					$this->salida .= "      <tr class='$estilo1'>";
                         $this->salida .= "      <td align=\"left\" width=\"70%\">".strtoupper($MotivosDet[$j][descripcion])."</td>";
                         $EditarDet = "javascript:MostrarCapa('ContenedorEdTrabajoDet');IniciarEdetTrabajo('EDITAR TIPO TRABAJO DETALLE', 'ContenedorEdTrabajoDet', '".$MotivosDet[$j][trabajo_detalle_id]."');CargarContenedor('ContenedorEdTrabajoDet');";
                         $this->salida .= "      <td align=\"center\" class=\"$estilo1\"><a href=\"$EditarDet\"><img src=\"".GetThemePath()."/images/edita.png\" border=0 title='Editar Trabajo Detalle.'></a></td>\n";
                         if($MotivosDet[$j][sw_activacion] == 0)
                         {
                              $hrefEl=ModuloGetURL('app','ModuloAdminPsicologia','user','EdicionActivacionTrabajoDet',array('trabajo_id_det'=>$MotivosDet[$j][trabajo_detalle_id], 'sw'=>"0"));
                              $this->salida .= "      <td align=\"center\" class=\"$estilo1\" width=\"15%\"><a href=\"$hrefEl\"><img src=\"".GetThemePath()."/images/activo.gif\" border=0 title='Desactivar Trabajo.'></a></td>\n";
                         }
                         else
                         {
                              $hrefEl=ModuloGetURL('app','ModuloAdminPsicologia','user','EdicionActivacionTrabajoDet',array('trabajo_id_det'=>$MotivosDet[$j][trabajo_detalle_id], 'sw'=>"1"));
                              $this->salida .= "      <td align=\"center\" class=\"$estilo1\" width=\"15%\"><a href=\"$hrefEl\"><img src=\"".GetThemePath()."/images/inactivoip.gif\" border=0 title='Desactivar Trabajo.'></a></td>\n";
                         }
                         $this->salida .= "      </tr>";
                    }
                    $this->salida .= "      </table>";
                    $this->salida .= "      </td>\n";
                    $javaCrearDet = "javascript:MostrarCapa('ContenedorCrearTrabajoDet');IniciarCrearTrabajoDet('CREAR TIPO TRABAJO DETALLE', 'ContenedorCrearTrabajoDet', '".$listadoMotivos[$i][trabajo_id]."');CargarContenedor('ContenedorCrearTrabajoDet');";
                    $this->salida .= "      <td align=\"center\" class=\"$estilo\"><a href=\"$javaCrearDet\">Crear Trabajo Detalle</a></td>\n";
                    $this->salida .= "</tr>\n";
                    unset($MotivosDet);
               }
               $this->salida .= "  </tr>\n";
               $this->salida .= "  </table>\n";
          }
          else
          {
          	$this->salida .= "<br><div align=\"center\" class=\"label_mark\">NO HAY MOTIVOS DE CONSULTA CREADOS</div>";
          }
          
          $this->salida.="<div id='ContenedorEdTrabajoDet' class='d2Container' style=\"display:none\">";
          $CambioDet = ModuloGetURL('app','ModuloAdminPsicologia','user','EdicionTipoTrabajoDet');
          $this->salida .= "    <form name=\"CambioMotDet\" action=\"$CambioDet\" method=\"post\">\n";
          $this->salida .= "    <div id='tituloEdet' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
          $this->salida .= "    <div id='cerrarEdet' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorEdMotivoDet');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
          $this->salida .= "    <div id='errorEdet' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
          $this->salida .= "    <div id='ContenidoEdTrabajoDet'>\n";
          $this->salida .= "            <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
          $this->salida .= "                <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "                    <td colspan=\"2\">EDICION TRABAJOS DETALLE</td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "                <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "                    <td align=\"left\" width=\"50%\">DESCRIPCION TRABAJO\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                    <td align=\"center\" width=\"50%\">\n";
          $this->salida .= "                        <input type=\"text\" class=\"input-text\" size=\"20\" id=\"descMotDet\" name=\"descMotDet\" value=\"\">\n";
          $this->salida .= "                        <input type=\"hidden\" id=\"IdMotivoDet\" name=\"IdMotivoDet\" value=\"\">\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "                <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"center\">\n";
          $this->salida .= "                        <input type=\"submit\" class=\"input-submit\" name=\"guardarMotDet\" value=\"Editar\">\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "            </table>\n";
          $this->salida .= "        </form>\n";
          $this->salida .= "    </div>\n";     
          $this->salida.="</div>";

          $this->salida.="<div id='ContenedorCrearTrabajoDet' class='d2Container' style=\"display:none\">";
          $Crear = ModuloGetURL('app','ModuloAdminPsicologia','user','CreacionTipoTrabajoDet');
          $this->salida .= "    <form name=\"CrearMotDet\" action=\"$Crear\" method=\"post\">\n";
          $this->salida .= "    <div id='tituloCrDet' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
          $this->salida .= "    <div id='cerrarCrDet' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorCrearMotivoDet');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
          $this->salida .= "    <div id='errorCrDet' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
          $this->salida .= "    <div id='ContenidoCrearTrabajoDet'>\n";
          $this->salida .= "            <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
          $this->salida .= "                <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "                    <td colspan=\"2\">CREACION TRABAJOS DETALLE</td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "                <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "                    <td align=\"left\" width=\"50%\">DESCRIPCION TRABAJO\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                    <td align=\"center\" width=\"50%\">\n";
          $this->salida .= "                        <input type=\"text\" class=\"input-text\" size=\"20\" id=\"MotivoDet\" name=\"MotivoDet\" value=\"\">\n";
          $this->salida .= "                        <input type=\"hidden\" id=\"IdMotivoCrDet\" name=\"IdMotivoCrDet\" value=\"\">\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "                <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"center\">\n";
          $this->salida .= "                        <input type=\"submit\" class=\"input-submit\" name=\"SaveMotDet\" value=\"Crear\">\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "            </table>\n";
          $this->salida .= "        </form>\n";
          $this->salida .= "    </div>\n";     
          $this->salida.="</div>";
          
          $accion=ModuloGetURL('app','ModuloAdminPsicologia','user','FormaInicial');
          $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
          $this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"></p>";
          $this->salida .= "</form>";
          
          $javaC = "<script>\n";
          $javaC .= "   var contenedor\n";
          
          $javaC .= "   function CargarContenedor(Elemento)\n";
          $javaC .= "   {\n";
          $javaC .= "        contenedor = Elemento;\n";
          $javaC .= "   }\n";

          $javaC .= "   var titulo = 'titulo';\n";
          $javaC .= "   var hiZ = 2;\n";
          $javaC .= "   var DatosFactor = new Array();\n";
          $javaC .= "   var EnvioFactor = new Array();\n";
          
          $javaC .= "   function IniciarEdetTrabajo(tit, Elemento, Motivo)\n";
          $javaC .= "   {\n";
          $javaC .= "	   Capa = xGetElementById(Elemento);\n";
          $javaC .= "       xMoveTo(Capa, xClientWidth()/2, xScrollTop()+60);\n";

          $javaC .= "       document.CambioMotDet.IdMotivoDet.value = Motivo;\n";
          $javaC .= "       document.getElementById('tituloEdet').innerHTML = '<center>'+tit+'</center>';\n";
          $javaC .= "       document.getElementById('errorEdet').innerHTML = '';\n";
          
          $javaC .= "       ele = xGetElementById('tituloEdet');\n";
          $javaC .= "       xResizeTo(ele, 280, 20);\n";
          $javaC .= "       xMoveTo(ele, 0, 0);\n";
          
          $javaC .= "       ele = xGetElementById('cerrarEdet');\n";
          $javaC .= "       xResizeTo(ele,20, 20);\n";
          $javaC .= "       xMoveTo(ele, 280, 0);\n";
          $javaC .= "   }\n";       
          
          $javaC .= "   function IniciarCrearTrabajoDet(tit, Elemento, Motivo)\n";
          $javaC .= "   {\n";
          $javaC .= "	   Capa = xGetElementById(Elemento);\n";
          $javaC .= "       xMoveTo(Capa, xClientWidth()/2, xScrollTop()+60);\n";
          
          $javaC .= "       document.CrearMotDet.IdMotivoCrDet.value = Motivo;\n";
		$javaC .= "       document.getElementById('tituloCrDet').innerHTML = '<center>'+tit+'</center>';\n";
          $javaC .= "       document.getElementById('errorCrDet').innerHTML = '';\n";
          
          $javaC .= "       ele = xGetElementById('tituloCrDet');\n";
          $javaC .= "       xResizeTo(ele, 280, 20);\n";
          $javaC .= "       xMoveTo(ele, 0, 0);\n";
          
          $javaC .= "       ele = xGetElementById('cerrarCrDet');\n";
          $javaC .= "       xResizeTo(ele,20, 20);\n";
          $javaC .= "       xMoveTo(ele, 280, 0);\n";
          $javaC .= "   }\n";       
          
          $javaC .= "   function myOnDragStart(ele, mx, my)\n";
          $javaC .= "   {\n";
          $javaC .= "     window.status = '';\n";
          $javaC .= "     if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
          $javaC .= "     else xZIndex(ele, hiZ++);\n";
          $javaC .= "     ele.myTotalMX = 0;\n";
          $javaC .= "     ele.myTotalMY = 0;\n";
          $javaC .= "   }\n";
          
          $javaC .= "   function myOnDrag(ele, mdx, mdy)\n";
          $javaC .= "   {\n";
          $javaC .= "     if (ele.id == titulo) {\n";
          $javaC .= "       xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
          $javaC .= "     }\n";
          $javaC .= "     else {\n";
          $javaC .= "       xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
          $javaC .= "     }  \n";
          $javaC .= "     ele.myTotalMX += mdx;\n";
          $javaC .= "     ele.myTotalMY += mdy;\n";
          $javaC .= "   }\n";
               
          $javaC .= "   function myOnDragEnd(ele, mx, my)\n";
          $javaC .= "   {\n";
          $javaC .= "   }\n";
          
          $javaC.= "function MostrarCapa(Elemento)\n";
          $javaC.= "{\n;";
          $javaC.= "    capita = xGetElementById(Elemento);\n";
          $javaC.= "    capita.style.display = \"\";\n";
          $javaC.= "}\n";
          
          $javaC.= "function Cerrar(Elemento)\n";
          $javaC.= "{\n";
          $javaC.= "    capita = xGetElementById(Elemento);\n";          
          $javaC.= "    capita.style.display = \"none\";\n";          
          $javaC.= "}\n";                    
          
          $javaC.= "function load_page()\n";
          $javaC.= "{\n";
          $javaC.= "    location.reload();\n";
          $javaC.= "}\n";
          
          $javaC.= "</script>\n";
          $this->salida.= $javaC;

          $this->salida .= ThemeCerrarTabla();
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

     /*
	* Funcion donde se visualiza el encabezado de la empresa.
	* @return boolean
	*/
     function Encabezado($titulo)
	{
          $empresa = $_SESSION['AdminPsico']['EMPRESA'];
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