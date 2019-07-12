<?php

/**
 * $Id: app_EE_PanelEnfermeria_userclasses_HTML.php,v 1.4 2011/03/10 15:10:42 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author  Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
 * @package IPSOFT-SIIS
 */

class app_EE_PanelEnfermeria_userclasses_HTML extends app_EE_PanelEnfermeria_user
{
     /**
     * Titulo de la pagina
     *
     * @var string
     * @access private
     */
     var $titulo;
     
     
     /**
     * Constructor
     *
     * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
     * @access public
     */
     function app_EE_PanelEnfermeria_userclasses_HTML()
     {
          $this->SetXajax(array("Activar_IngCue"),"app_modules/EE_PanelEnfermeria/RemoteXajax/PanelEnfermeriaxajax.php");
          $this->app_EE_PanelEnfermeria_user();
          $this->titulo='PACIENTES EN LA ESTACION DE ENFERMERIA';
          $this->salida='';
          return true;
     }
     
     
     /**
     * Metodo default
     *
     * @return boolean
     */
     function main()
     {
          $this->FrmPanelEstacion();
          return true;
     }
     
     
     /**
     * Forma para mostrar el Panel de la Estacion de Enfermeria
     *
     * @return boolean True si se ejecuto correctamente
     * @access public
     */
     function FrmPanelEstacion($subMenu=false)
     {
          //Validar si el usuario esta logueado y si tiene permisos.
          if(!UserGetUID())
          {
               $this->FrmEstacionNoLogin();
               return true;
          }
     	
          unset($_SESSION['Interna']);
          
          $datos_estacion = $this->GetEstacionActiva();
     
          if($datos_estacion===false)
          {
               if(empty($this->error))
               {
                    $this->error = "EE_PanelEnfermeria - FrmPanelEstacion - 01";
                    $this->mensajeDeError = "El metodo GetEstacionActiva() retorno false.";
               }
               return false;
          }
     
          if($datos_estacion===null)
          {
               $this->FrmLogueoEstacion();
               return true;
          }
          
          // Reconocimiento Perfil Profesional.
          $this->GetUserPerfil();
          
          if($datos_estacion['sw_consulta_urgencia'])
          {
               //Verificamos estado de Variable de Retorno.
          if($_SESSION['RETORNO_PANEL'])
               	$this->FrmSeleccionEstacionE($datos_estacion);
               else
               	$this->FrmLogueoEstacionSubMenu($datos_estacion);
               return true;
          }elseif($datos_estacion['sw_estacion_cirugia'] == '1'){
               $this->FrmDatosEstacion(&$datos_estacion);
               $this->FrmListadoPacientesEstacionCirugia();
               $this->FrmListadoPacientesPendientesIngresoCirugia();   
               
               if($this->GetUserPermisos($datosEstacion['estacion_id'],'70'))
               {
                    $this->FrmFuncionalidades_x_Estacion($datos_estacion,'','H');
               }
               
               $this->FrmPieDePaginaPanelEstacion();
          }
          else
          {
               $this->FrmDatosEstacion(&$datos_estacion);
               $this->FrmListadoPacientesEstacion();
               $this->FrmListadoPacientesPendientesIngreso();
               
               if($this->GetUserPermisos($datosEstacion['estacion_id'],'70'))
               {
                    $this->FrmFuncionalidades_x_Estacion($datos_estacion,'','H');
                    $this->IyM_PendientesUsuarios($datos_estacion);
               }
               
               $this->FrmPieDePaginaPanelEstacion();
          }          
          return true;
     }
     
     /**
     * Forma para seleccionar una estacion.
     *
     * @param string $modulo
     * @param string $metodo
     * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
     * @access public
     */
     function FrmLogueoEstacionSubMenu($datos_estacion)
     {
          $this->salida .= ThemeMenuAbrirTabla("ESTACION DE ENFERMERIA ".$datos_estacion[estacion_descripcion]."","50%");
          $Estadisticas = $this->EstadisticasEE();
          
          $this->salida.="<table align='center' border='0' width='95%' cellpadding=\"4\" cellspacing=\"4\">";
          
          if(($Estadisticas['hospitalizados'] == '0') AND ($Estadisticas['p_x_ingresar'] == '0') AND ($Estadisticas['en_consulta'] == '0'))
          {
               $this->salida.="<tr>";
               $this->salida.="<td width='100%' align='center' class='label_mark'>LA ESTACION SE ENCUENTRA SIN PACIENTES!!!</td>";
               $this->salida.="	</tr>";          
          }
          else
          {
               if($Estadisticas['hospitalizados'] OR $Estadisticas['p_x_ingresar'])
               {
                    $this->salida.="	<tr class='label_mark'>";
                    $this->salida.="		<td width='68%' align='left' colspan=\"2\">PACIENTES HOSPITALIZADOS</td>";
                    $this->salida.="	</tr>";
                    
                    $this->salida.="	<tr class='label_error'>";
                    $this->salida.="		<td  width='2%' align='center'>";
                    $this->salida.="			<img src=\"". GetThemePath() ."/images/editar.gif\">&nbsp;&nbsp;";
                    $this->salida.="		</td>";
                    $this->salida.="		<td width='68%' align='left'><a href=\"".ModuloGetURL('app','EE_PanelEnfermeria','user','FrmSeleccionEstacionE',array('datos_estacion'=>$datos_estacion,'urgencias'=>'hospitalizados'))."\"><b>PACIENTES HOSPITALIZADOS</b></a></td>";
                    $this->salida.="	</tr>";
                    $this->salida.="	<tr class='label'>";
                    $this->salida.="		<td width='2%' align='center'>&nbsp;</td>";
                    $this->salida.="		<td width='68%' align='left'>";
                    $this->salida.="			<img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'>&nbsp;Numero de pacientes &nbsp; (".$Estadisticas['hospitalizados'].")   &nbsp;,&nbsp; Por Ingresar &nbsp; (".$Estadisticas['p_x_ingresar'].")";
                    $this->salida.="		</td>";
                    $this->salida.="	</tr>";
               }
               
               if($Estadisticas['en_consulta'])
               {
                    $this->salida.="	<tr class='label_mark'>";
                    $this->salida.="		<td width='68%' align='left' colspan=\"2\">PACIENTES EN CONSULTA DE URGENCIAS</td>";
                    $this->salida.="	</tr>";
                    
                    // Busqueda de la distribucion de pacientes en los Consultorios de Atencion.
                    $pacientes = $this->Distribucion_PacientesConsultorios($datos_estacion['estacion_id']);
     
                    if($pacientes['mi_consultorio'])
                    {
                         $this->salida.="	<tr class='label_error'>";
                         $this->salida.="		<td width='2%' align='center'>";
                         $this->salida.="			<img src=\"". GetThemePath() ."/images/editar.gif\">&nbsp;&nbsp;";
                         $this->salida.="		</td>";
                         $accionMiC = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmSeleccionEstacionE',array('datos_estacion'=>$datos_estacion,'urgencias'=>'mi_consultorio'));
                         $this->salida.="		<td width='68%' align='left'><a href=\"$accionMiC\">PACIENTES ASIGNADOS A MI</a>&nbsp;&nbsp;&nbsp;&nbsp;<label class=\"label\">Pacientes&nbsp; (".$pacientes['mi_consultorio'].")</label></td>";
                         $this->salida.="	</tr>";
                    }
                    
                    if($pacientes['otros_consultorios'])
                    {
                         $this->salida.="	<tr class='label_error'>";
                         $this->salida.="		<td width='2%' align='center'>";
                         $this->salida.="			<img src=\"". GetThemePath() ."/images/editar.gif\">";
                         $this->salida.="		</td>";
                         $accionOtros = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmSeleccionEstacionE',array('datos_estacion'=>$datos_estacion,'urgencias'=>'otros_consultorios'));
                         $this->salida.="		<td width='68%' align='left'><a href=\"$accionOtros\">PACIENTES ASIGNADOS A OTROS PROFESIONALES</a>&nbsp;&nbsp;&nbsp;&nbsp;<label class=\"label\">Pacientes&nbsp; (".$pacientes['otros_consultorios'].")</label></td>";
                         $this->salida.="	</tr>";
                    }
     
                    if($pacientes['sin_consultorios'])
                    {
                         $this->salida.="	<tr class='label_error'>";
                         $this->salida.="		<td width='2%' align='center'>";
                         $this->salida.="			<img src=\"". GetThemePath() ."/images/editar.gif\">&nbsp;&nbsp;";
                         $this->salida.="		</td>";
                         $accionSinC = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmSeleccionEstacionE',array('datos_estacion'=>$datos_estacion,'urgencias'=>'sin_consultorios'));
                         $this->salida.="		<td width='68%' align='left'><a href=\"$accionSinC\">PACIENTES SIN ASIGNAR</a>&nbsp;&nbsp;&nbsp;&nbsp;<label class=\"label\">Pacientes&nbsp; (".$pacientes['sin_consultorios'].")</label></td>";
                         $this->salida.="	</tr>";
                    }
                    
                    $this->salida.="	<tr class='label_error'>";
                    $this->salida.="		<td width='2%' align='center'>";
                    $this->salida.="			<img src=\"". GetThemePath() ."/images/editar.gif\">&nbsp;&nbsp;";
                    $this->salida.="		</td>";
                    $accionSinC = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmSeleccionEstacionE',array('datos_estacion'=>$datos_estacion,'urgencias'=>'todos'));
                    $this->salida.="		<td width='68%' align='left'><a href=\"$accionSinC\">TODOS LOS PACIENTES EN CONSULTA DE URGENCIAS</a>&nbsp;&nbsp;&nbsp;&nbsp;<label class=\"label\">Pacientes&nbsp; (".$Estadisticas['en_consulta'].")</label></td>";
                    $this->salida.="	</tr>";
     
                    $this->salida.="	<tr class='label'>";
                    $this->salida.="		<td width='2%' align='center'>&nbsp;</td>";
                    $this->salida.="		<td width='68%' align='left'>";
                    $this->salida.="			<img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'>&nbsp;Numero total de pacientes en consulta&nbsp; (".$Estadisticas['en_consulta'].")";
                    $this->salida.="		</td>";
                    $this->salida.="	</tr>";
               }
          }
          
          $href    = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmLogueoEstacion');
          
          $this->salida .= "<tr><td colspan=\"2\">";
          $this->salida .= "<center>\n";
          $this->salida .= "  <div class='normal_10' align='center'><br>\n";
          $this->salida .= "    <a href='$href'><b>Seleccionar Estación</b></a>\n";
          $this->salida .= "  </div>\n";
          $this->salida .= "</center>\n";
          $this->salida .= "</td></tr>";          
          $this->salida.="</table><br>";
          
     	$this->salida .= ThemeMenuCerrarTabla();
          return true;
     }
     
          
     /**
     * Forma para seleccionar una estacion.
     *
     * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
     * @access public
     */
     function FrmLogueoEstacion()
     {
          $this->DelEstacionActiva();
     	
          //Borramos Variable de Retorno
          unset($_SESSION['RETORNO_PANEL']);
          
          if(!UserGetUID())
          {
               $this->FrmEstacionNoLogin();
               return true;
          }
     
          $UserEstaciones = $this->GetUserEstaciones();
          if($UserEstaciones===false)
          {
               if(empty($this->error))
               {
                    $this->error = "EE_PanelEnfermeria - FrmLogueoEstacion";
                    $this->mensajeDeError = "El metodo GetUserEstaciones() retorno false.";
               }
               return false;
          }
          elseif(!is_array($UserEstaciones))
          {
               $url= ModuloGetURL('system','Menu','user','main');
               $titulo  = "VALIDACION DE PERMISOS";
               $this->FrmMSG($url, $titulo);
               return true;
          }
     
          $mtz[0]="EMPRESA";
          $mtz[1]="CENTRO UTILIDAD";
          $mtz[2]="UNIDAD FUNCIONAL";
          $mtz[3]="DEPARTAMENTO";
          $mtz[4]="ESTACION";
     
          $url[0]='app';
          $url[1]='EE_PanelEnfermeria';
          $url[2]='user';
          $url[3]='FrmSetEstacion';
          $url[4]='estacion_id';
     
          foreach($UserEstaciones as $k=>$d)
          {
               $v = $this->GetDatosEstacion($k);
               if($v===false)
               {
                    if(empty($this->error))
                    {
                         $this->error = "EE_PanelEnfermeria - FrmEstacionNoLogin";
                         $this->mensajeDeError = "El metodo GetDatosEstacion() retorno false.";
                    }
                    return false;
               }
               elseif(is_array($v))
               {
                    $estaciones[$v['empresa_descripcion']][$v['centro_utilidad_descripcion']][$v['unidad_funcional_descripcion']][$v['departamento_descripcion']][$v['estacion_descripcion']] = $v['estacion_id'];
               }
          }
     
          if(empty($v))
          {
               $url= ModuloGetURL('system','Menu','user','main');
               $titulo  = "VALIDACION DE PERMISOS";
               $mensaje = "NO HAY PERMISOS PARA EL MODULO - NO SE RETORNARON LOS DATOS DE LAS ESTACIONES DEL USUARIO";
               $this->FrmMSG($url, $titulo, $mensaje);
               return true;
          }
     
          $this->salida .= gui_theme_menu_acceso("SELECCION DE ESTACION DE ENFERMERIA",$mtz,$estaciones,$url);
          return true;
     }
     

     function FrmSeleccionEstacionE($datos_estacion)
     {
     	if (!empty($_REQUEST['datos_estacion']))
          	 $datos_estacion = $_REQUEST['datos_estacion'];
               
          //CABECERA - DATOS DE LA ESTACION DE ENFERMERIA
          $this->FrmDatosEstacion(&$datos_estacion);
     	
          //Creamos Variable de Retorno
          if (!empty($_REQUEST['urgencias']))
          	$_SESSION['RETORNO_PANEL'] = $_REQUEST['urgencias'];
               
          // Reconocimiento Perfil Profesional.
          $this->GetUserPerfil();      
          
          switch ($_SESSION['RETORNO_PANEL'])
          {
               case 'mi_consultorio':
                    if($datos_estacion['sw_consulta_urgencia'])
                    {
                         $this->FrmListadoPacientesConsultaUrgencias(1);
                    }
                    
                    if($this->GetUserPermisos($datosEstacion['estacion_id'],'70'))
                    {
                         $this->FrmFuncionalidades_x_Estacion($datos_estacion,1);
                         $this->IyM_PendientesUsuarios($datos_estacion);
                    }
                    
                    $this->FrmPieDePaginaPanelEstacion();
		          return true;
               break;
               case 'otros_consultorios':
                    if($datos_estacion['sw_consulta_urgencia'])
                    {
                         $this->FrmListadoPacientesConsultaUrgencias(2);
                    }
                    
                    if($this->GetUserPermisos($datosEstacion['estacion_id'],'70'))
                    {
                         $this->FrmFuncionalidades_x_Estacion($datos_estacion,1);
                         $this->IyM_PendientesUsuarios($datos_estacion);
                    }
                    
                    $this->FrmPieDePaginaPanelEstacion();
		          return true;
               break;
               case 'sin_consultorios':
                    if($datos_estacion['sw_consulta_urgencia'])
                    {
                         $this->FrmListadoPacientesConsultaUrgencias(3);
                    }
                    
                    if($this->GetUserPermisos($datosEstacion['estacion_id'],'70'))
                    {
                         $this->FrmFuncionalidades_x_Estacion($datos_estacion,1);
                         $this->IyM_PendientesUsuarios($datos_estacion);
                    }
                    $this->FrmPieDePaginaPanelEstacion();
		          return true;
               break;
               case 'hospitalizados':
                    $this->FrmListadoPacientesEstacion();
                    $this->FrmListadoPacientesPendientesIngreso();

                    if($this->GetUserPermisos($datosEstacion['estacion_id'],'70'))
                    {
                         $this->FrmFuncionalidades_x_Estacion($datos_estacion,'','H');
                         $this->IyM_PendientesUsuarios($datos_estacion);
                    }
                    $this->FrmPieDePaginaPanelEstacion();
		          return true;
               break;
               case 'todos':
                    if($datos_estacion['sw_consulta_urgencia'])
                    {
                         $this->FrmListadoPacientesConsultaUrgencias(4);
                    }
                    
                    if($this->GetUserPermisos($datosEstacion['estacion_id'],'70'))
                    {
                         $this->FrmFuncionalidades_x_Estacion($datos_estacion,1);
                         $this->IyM_PendientesUsuarios($datos_estacion);
                    }
                    $this->FrmPieDePaginaPanelEstacion();
		          return true;
               break;
          }          
          return true;
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
     * Forma para mostrar un listado de pacientes sin estar iniciada una sesion.
     *
     * @param array $datos vector con la informacion de la estacion a mostrar
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function FrmEstacionNoLogin()
     {
          if(!($_REQUEST['_system_modulos_default'] && $_REQUEST['estacion_id']))
          {
               $url     = ModuloGetURL('system','log','user','main');
               $titulo  = "VALIDACION DE PERMISOS";
               $mensaje = "Usted debe de iniciar una sesión";
               $url_titulo = "Iniciar Sesión";
     
               $this->FrmMSG($url, $titulo, $mensaje, $url_titulo);
     
               return true;
          }
     
          $datosEstacion = $this->GetDatosEstacion($_REQUEST['estacion_id']);
     
          if($datosEstacion===false)
          {
               if(empty($this->error))
               {
                    $this->error = "EE_PanelEnfermeria - FrmEstacionNoLogin";
                    $this->mensajeDeError = "El metodo GetDatosEstacion() retorno false.";
               }
               return false;
          }
          elseif(!is_array($datosEstacion))
          {
               $url     = ModuloGetURL('system','log','user','main');
               $titulo  = "VALIDACION DE PERMISOS";
               $mensaje = "Usted debe de iniciar una sesión - La estación configurada por defecto [$_REQUEST[estacion_id]] no retorno datos.";
               $url_titulo = "Iniciar Sesión";
     
               $this->FrmMSG($url, $titulo, $mensaje, $url_titulo);
     
               return true;
          }
          $this->FrmDatosEstacion(&$datosEstacion);
          $this->FrmPieDePaginaPanelEstacion();
     }
     
     
     /**
     * Metodo para establecer la estacion activa..
     *
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function FrmSetEstacion()
     {
          if(!$this->SetEstacionActiva())
          {
               $url = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
               $titulo  = "NO SE PUDO ESTABLECER LA ESTACION SELECCIONADA";
               $mensaje = "El argumento [estacion_id] no llego y/o el usuario no tiene permisos en la estacion seleccionada.";
     
               $this->FrmMSG($url, $titulo, $mensaje);
     
               return true;
          }

          $this->FrmPanelEstacion($subMenu=true);
          return true;
     }
     
     
    /**
    * Forma para mostrar el listado de pacientes en la Estacion de Enfermeria
    *
    * @return boolean True si se ejecuto correctamente
    * @access private
    */
    function FrmListadoPacientesEstacion()
    {
          $permisoingresoactivo = 0;
          $permi = $this->GetPermisoIngAct();
          if (!empty($permi)){
            if ($permi[0]['sw_permiso'] == 1){
              $permisoingresoactivo = 1;
            }
          }    
          $listadoPacientes = $this->GetPacientesInternados();
          $_SESSION['EE_PanelEnfermeria']['listadoPacientes'] = $listadoPacientes;
          $datosEstacion = $this->GetEstacionActiva(false);

          if($listadoPacientes===false)
          {
            if(empty($this->error))
            {
              $this->error = "EE_PanelEnfermeria - FrmListadoPacientesEstacion";
              $this->mensajeDeError = "El metodo GetPacientesInternados() retorno false.";
            }
            return false;
          }
          if($listadoPacientes===null)
          {
               return true;
          }
     
          $this->IncludeJS('javascripts/funciones.js', $contenedor='app', $modulo='EE_PanelEnfermeria');
     
          SessionSetVar("RetornopanelEnfermeria",$_SESSION['CENTRALHOSP']['RETORNO']);
          $llamdas = ModuloGetURL('app','ImpresionformatosHC','controller','LlamadasEspecialista',array("datosEstacion"=>$datosEstacion));
          $this->salida .= "<br>\n";
          $this->salida .= "<div style=\"text-align:center\">\n";
          $this->salida .= "  <a href=\"".$llamdas."\" class=\"label_error\">\n";
          $this->salida .= "    REGISTRO DE LLAMADAS A ESPECIALISTA\n";
          $this->salisa .= "  </a>\n";
          $this->salida .= "</div>\n";
          $this->salida .= "<br>\n";
          $this->salida .= "<table align=\"center\" width=\"100%\"  border=\"0\" class=\"modulo_table_list\">\n";
          $this->salida .= "  <tr class=\"formulacion_table_list\">\n";
          $this->salida .= "      <td colspan='17' height='30'>PACIENTES INTERNADOS EN LA ESTACION</td>\n";
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr class=\"formulacion_table_list\">\n";
          $this->salida .= "      <td width=\"15\">&nbsp;</td>\n";
          $this->salida .= "      <td align=\"center\">HAB.</td>\n";
          $this->salida .= "      <td align=\"center\">CAMA</sub></td>\n";
          $this->salida .= "      <td align=\"center\">PACIENTE</td>\n";
          $this->salida .= "      <td width=\"22\">&nbsp;</td>\n";
          $this->salida .= "      <td align=\"center\">TIEMPO<BR>HOSP.</td>\n";
          $this->salida .= "      <td align=\"center\">SIG.<BR>VITALES</td>\n";
          $this->salida .= "      <td align=\"center\">MED.<BR>PACIENTES</td>\n";
          $this->salida .= "      <td align=\"center\">CTRL<BR>PROGRAMADOS</td>\n";
          $this->salida .= "      <td align=\"center\">PROGR.<BR>APOYO</td>\n";
          $this->salida .= "      <td align=\"center\">GLUCO<BR>METRIA</td>\n";
          $this->salida .= "      <td align=\"center\">NEURO<BR>LOGICO</td>\n";
          $this->salida .= "      <td align=\"center\">ORDEN<BR>SERVICIOS</td>\n";
          $this->salida .= "      <td align=\"center\">IMP</td>\n";
          $this->salida .= "      <td align=\"center\">IMÁGENES</td>\n";
          $this->salida .= "      <td align=\"center\">PROFESIONAL</td>\n";
          if ($permisoingresoactivo == 1){
            $this->salida .= "      <td align=\"center\">ACTIVAR/INACTIVAR<BR>INGRESO</td>\n";
          }
          
          $this->salida .= "  </tr>\n";
          
          if ($permisoingresoactivo == 1){
            $refresh = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
            $this->salida .= "      <tr>
                                        <td><input type='hidden' name='rutah' id='rutah' value = '$refresh'></td>
                                        <td><input type='hidden' name='btning' id='btning'></td>
                                        <td><input type='hidden' name='btncue' id='btncue'></td>
                                        <td><input type='hidden' name='btnesting' id='btnesting'></td>
                                        <td><input type='hidden' name='btnestcue' id='btnestcue'></td>
                                        <td><input type='hidden' name='btnpacid' id='btnpacid'></td>
                                    </tr>\n";
          }
               
          $backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
          $imagenHC_cerrada = "<img src=\"".GetThemePath()."/images/hc.png\" border=0 title='Nueva anotación en la Historia Clinica del Paciente' width='22' heigth='22'>";
          $imagenHC_abierta = "<img src=\"".GetThemePath()."/images/hc_abierta.png\" border=0 title='Historia Clinica del Paciente - Sin Cerrar' width='22' heigth='22'>";
          $imagenHC_desabilitada = "<img src=\"".GetThemePath()."/images/hc_desabilitada.png\" border=0 title='Historia Clinica del Paciente - Sin Permiso' width='22' heigth='22'>";

          $_SESSION['HISTORIACLINICA']['RETORNO']['contenedor']   = 'app';
          $_SESSION['HISTORIACLINICA']['RETORNO']['modulo']       = 'EE_PanelEnfermeria';
          $_SESSION['HISTORIACLINICA']['RETORNO']['tipo']         = 'user';
          $_SESSION['HISTORIACLINICA']['RETORNO']['metodo']       = 'FrmPanelEstacion';
     
          foreach($listadoPacientes as $k => $filaPacinte)
          {
               
               if($p++ % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
     
               if($filaPacinte['evolucion_id'])
               {
                    $accion = ModuloHCGetURL($filaPacinte['evolucion_id'], -1, 0, '', false, array('estacion'=>$datosEstacion['estacion_id']));
                    $imagenHC = "<a href='$accion'>$imagenHC_abierta</a>";
               }
               else
               {
                    if($this->GetUserPermisos($datosEstacion['estacion_id'],'55'))
                    {
                         if($this->USERPERFIL == '10' OR $this->USERPERFIL == '11')
                         {
                         	$accion = ModuloHCGetURL(0, -1, $filaPacinte['ingreso'], $datosEstacion['hc_modulo_medico'], false, array('estacion'=>$datosEstacion['estacion_id']));
                         }
                         else
                         {
                         	$accion = ModuloHCGetURL(0, -1, $filaPacinte['ingreso'], $datosEstacion['hc_modulo_enfermera'], false, array('estacion'=>$datosEstacion['estacion_id']));                         
                         }

                         $imagenHC = "<a href='$accion'>$imagenHC_cerrada</a>";
                    }
                    else
                    {
                         $imagenHC = $imagenHC_desabilitada;
                    }
               }
              $estaincu = 0;
              if (($filaPacinte[ingresosestado]  ==  '1') and ($filaPacinte[cuentaestado]  ==  '1' or $filaPacinte[cuentaestado]  ==  '2')){ 
                  $estaincu = 1;
              }else{
                  if (($filaPacinte[ingresosestado]  ==  '0')){ // and ($filaPacinte[cuentaestado]  ==  '0')){ 
                  //						$this->salida .= "      <td align=\"center\" bgcolor=\"$_color\"><input type = 'button' class='input-submit' id = 'a".$filaPacinte['ingreso']."' value = 'Activar'  onclick = \"xajax_Activar_IngCue(".$filaPacinte['ingreso'].", ".$filaPacinte['numerodecuenta'].", this.value, this.id, ".$filaPacinte[ingresosestado].", ".$filaPacinte[cuentaestado].", ".$filaPacinte[paciente_id].")\"></td>\n";
                      $estaincu = 3;
                  }
              }
     
             $url_info_paciente = ModuloGetURL('app','EE_PanelEnfermeria','user','MostrarDatosIngreso',array('ingreso'=>$filaPacinte['ingreso']));
             $nombre_paciente = "<a href='$url_info_paciente'>$filaPacinte[nombre_completo]</a>";
             $this->salida .= "<tr class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>\n";

              $estaincu = 0;
              if (($filaPacinte[ingresosestado]  ==  '1') and ($filaPacinte[cuentaestado]  ==  '1' or $filaPacinte[cuentaestado]  ==  '2')){ 
                  $estaincu = 1;
              }else{
                  if (($filaPacinte[ingresosestado]  ==  '0')){ // and ($filaPacinte[cuentaestado]  ==  '0')){ 
                  //						$this->salida .= "      <td align=\"center\" bgcolor=\"$_color\"><input type = 'button' class='input-submit' id = 'a".$filaPacinte['ingreso']."' value = 'Activar'  onclick = \"xajax_Activar_IngCue(".$filaPacinte['ingreso'].", ".$filaPacinte['numerodecuenta'].", this.value, this.id, ".$filaPacinte[ingresosestado].", ".$filaPacinte[cuentaestado].", ".$filaPacinte[paciente_id].")\"></td>\n";
                      $estaincu = 3;
                  }
              }
             
            if($filaPacinte[paciente_cirugia] == 0 )
            {
                if ($permisoingresoactivo == 1){
              
                    //Busqueda de Programaciones de Cirugia para el Paciente
                    $programacion = $this->ValidarProgramacion_Cirugia($filaPacinte);

                    //Busqueda de las Conductas Pendientes, Para cada paciente.
                    $conducta = $this->BusquedaConducta($filaPacinte[ingreso]);

                    if($conducta[hc_tipo_orden_medica_id] == '01' OR  $conducta[hc_tipo_orden_medica_id] == '02' OR $conducta[hc_tipo_orden_medica_id] == '04')
                    {
                      $imagenPaciente = "<img src=\"".GetThemePath()."/images/trasladodepartamento.png\" border=0 title=\"ORDEN PARA: ".$conducta[descripcion]."\">";
                      if($this->GetUserPermisos($datosEstacion['estacion_id'],'04') OR $this->GetUserPermisos($datosEstacion['estacion_id'],'03'))
                      {
                        $url = ModuloGetURL('app','EE_AsignacionCama','user','FrmIngresoPaciente',array('datosPaciente'=>$filaPacinte,'accionFrmIngresoPaciente'=>'Traslado','SwCambioCama'=>'1','conducta'=>$conducta));
                        $this->salida .= "      <td><a href='$url'>$imagenPaciente</a></td>\n";
                      }
                      else
                      {
                        $this->salida .= "      <td>$imagenPaciente</td>\n";                         
                      }
                    }
                    elseif($conducta[hc_tipo_orden_medica_id] == '06' OR  $conducta[hc_tipo_orden_medica_id] == '07' OR $conducta[hc_tipo_orden_medica_id] == '99')
                    {
                      // Revisamos estado de la Alta del Paciente.
                      $vistos_ok = $this->BusquedaVistos_ok_salida($conducta);
                      // Revisamos las cuentas del paciente
                      $conteo_cuentas = $this->GetInfoCuentasActivas($filaPacinte['ingreso']); //revisa si tiene cuentas abiertas.
                       
                      if($vistos_ok['01']['ingreso'] AND $conteo_cuentas == '1')
                      {
                          $imagenPaciente = "<img src=\"".GetThemePath()."/images/egresocaja.png\" border=0 title=\"ORDEN PARA: ".$conducta[descripcion]."\">";                         
                      }
                      elseif($vistos_ok['01']['ingreso'] AND $conteo_cuentas == '0')
                      {
                        $imagenPaciente = "<img src=\"".GetThemePath()."/images/egresopacienteok.png\" border=0 title=\"ORDEN PARA: ".$conducta[descripcion]."\">";
                      }
                      else
                      {
                        $imagenPaciente = "<img src=\"".GetThemePath()."/images/egreso.png\" border=0 title=\"ORDEN PARA: ".$conducta[descripcion]."\">";
                      }
                       
                      if($this->GetUserPermisos($datosEstacion['estacion_id'],'56') AND $this->GetUserPermisos($datosEstacion['estacion_id'],'06'))
                      {
                        $url = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPacientePendiente_Egreso',array('datosPaciente'=>$filaPacinte,'datos_estacion'=>$datosEstacion,'conducta'=>$conducta));
                        if ($permisoingresoactivo == 1){
                            if ($estaincu == 1){
                              $this->salida .= "      <td><a href='$url'>$imagenPaciente</a></td>\n";
                            }else{
                              $this->salida .= "      <td></td>\n";
                            }
                        }else{
                            $this->salida .= "      <td></td>\n";
                        }
//                        $this->salida .= "      <td><a href='$url'>$imagenPaciente</a></td>\n";
                      }
                      else
                      {
                        $this->salida .= "      <td>$imagenPaciente</td>\n";                         
                      }
                    }
                    elseif($conducta[hc_tipo_orden_medica_id] == '05' OR !empty($programacion) OR $filaPacinte[paciente_cirugia] != 0)
                    {
                      if(empty($conducta[descripcion]))
                      $conducta[descripcion] = "TRASLADO A CIRUGIA.";

                      $imagenPaciente = "<img src=\"".GetThemePath()."/images/cirugia.png\" border=0 title=\"ORDEN PARA: ".$conducta[descripcion]."\">";
                      if($this->GetUserPermisos($datosEstacion['estacion_id'],'04') OR $this->GetUserPermisos($datosEstacion['estacion_id'],'03'))
                      {
                        $url = ModuloGetURL('app','EE_AsignacionCama','user','FrmIngresoPaciente',array('datosPaciente'=>$filaPacinte,'accionFrmIngresoPaciente'=>'Traslado','SwCambioCama'=>'1','conducta'=>$conducta));
                        if ($permisoingresoactivo == 1){
                            if ($estaincu == 1){
                              $this->salida .= "      <td><a href='$url'>$imagenPaciente</a></td>\n";
                            }else{
                              $this->salida .= "      <td></td>\n";
                            }
                        }else{
                            $this->salida .= "      <td></td>\n";
                        }
                      }
                      else
                      {
                        $this->salida .= "      <td>$imagenPaciente</td>\n";                         
                      }                         
                    }
                    else
                    {
                      $imagenPaciente = "<img src=\"".GetThemePath()."/images/hospitalizacion.png\" border=0 title='Paciente Hospitalizado.'>";
                      if($this->GetUserPermisos($datosEstacion['estacion_id'],'01') OR $this->GetUserPermisos($datosEstacion['estacion_id'],'03') OR $this->GetUserPermisos($datosEstacion['estacion_id'],'04'))
                      {
                        $url = ModuloGetURL('app','EE_AsignacionCama','user','FrmIngresoPaciente',array('datosPaciente'=>$filaPacinte,'accionFrmIngresoPaciente'=>'Traslado','SwCambioCama'=>'1'));
                        if ($estaincu == 1){
                          $this->salida .= "      <td><a href='$url'>$imagenPaciente</a></td>\n";
                        }else{
                          $this->salida .= "      <td></td>\n";
                        }
                      }
                      else
                      {
                        $this->salida .= "      <td>$imagenPaciente</td>\n";                         
                      }                         
                    }
                          
                    $this->salida .= "      <td>$filaPacinte[pieza]</td>\n";
                    $this->salida .= "      <td>$filaPacinte[cama]</td>\n";
                    $this->salida .= "      <td>$nombre_paciente</td>\n";
                    
                    
                    if ($estaincu == 1){

                        $this->salida .= "      <td>$imagenHC</td>\n";
                        $this->salida .= "      <td align=\"right\">" . $this->GetDiasHospitalizacion($filaPacinte['fecha_hospitalizacion']) . "</td>\n";
                        
                        //Signos Vitales
                        $SignoVital = "<img src=\"".GetThemePath()."/images/estetoscopio.png\" border=\"0\" title='Toma de Signos Vitales.'>";
                        if($this->GetUserPermisos($datosEstacion['estacion_id'],'61'))
                        {
                          $SignoVital = "<img src=\"".GetThemePath()."/images/estetoscopio.png\" border=\"0\" title='Toma de Signos Vitales.'>";
                          $UrlSignos = ModuloGetURL('app','EE_ControlesPacientes','user','CallFrmsControlesPacientes',array('datosPaciente'=>$filaPacinte,'control'=>'SignoVital'));
                          $this->salida .= "      <td align=\"center\"><a href=\"$UrlSignos\">".$SignoVital." SV</a></td>\n";
                        }
                        else
                        {
                          $this->salida .= "      <td align=\"center\">".$SignoVital." SV</td>\n";
                        }
                        
                        //Administracion Medicamentos
                        $urla = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmMedicamentos',array('datosPaciente'=>$filaPacinte,"control_id"=>2,"control_descripcion"=>"CONTROL MEDICAMENTOS PACIENTE"));
                        $medicamento=$this->GetPacMedicamentosPorSolicitar($filaPacinte[ingreso]);
                        if($medicamento==1)
                        { $this->salida .= "      <td align=\"center\"><a href='$urla'><img src=\"". GetThemePath() ."/images/pparamedin.png\" border='0'>&nbsp;MP</a></td>\n"; }
                        else
                        { $this->salida .= "      <td align=\"center\"><a href='$urla'><img src=\"". GetThemePath() ."/images/pparamed.png\" border='0'>&nbsp;MP</a></td>\n"; }
                    
                        //Controles Programados
                        $conteop=$this->CountControles($filaPacinte[ingreso]);
                        $urlcp = ModuloGetURL('app','EE_ControlesPacientes','user','CallFrmsControlesPacientes',array('datosPaciente'=>$filaPacinte,'control'=>'CProgramados'));
                        if($conteop == 1)
                        { $this->salida .= "      <td align=\"center\"><a href='$urlcp'><img src=\"". GetThemePath() ."/images/resultado.png\" border='0'>&nbsp;CP</a></td>\n"; }
                        else
                        { $this->salida .= "      <td align=\"center\"><img src=\"". GetThemePath() ."/images/prangos.png\" border='0'>&nbsp;CP</td>\n"; }
                              
                        //Controles de Apoyos Diagnosticos
                        $centinela=0;
                        //Traemos las fechas de los apoyos diagnosticos pendientes.
                        $fech_apoyo=$this->GetFechasHcApoyos($filaPacinte[ingreso]);
                        for($max=0;$max < sizeof($fech_apoyo);$max++)
                        {
                          if(strtotime($fech_apoyo[$max][fecha]) <= strtotime(date("y-m-d H:i:s")))
                          { $centinela=1; break;}
                          $centinela=0;
                        }

                        if($centinela==1)
                        {
                          if($this->GetUserPermisos($datosEstacion['estacion_id'],'62'))
                          {
                            $urlAP = ModuloGetURL('app','EE_ControlesPacientes_APD','user','CallControlesPacientes',array("datos_estacion"=>$datosEstacion,"datosPaciente"=>$filaPacinte));
                            $img='alarma.png';
                            $this->salida .= "	<td align=\"center\"><a href='$urlAP'><img src=\"". GetThemePath() ."/images/$img\" border='0'>&nbsp;AD</a></td>\n";
                          }
                          else
                          {
                            $img='alarma.png';
                            $this->salida .= "	<td align=\"center\"><img src=\"". GetThemePath() ."/images/$img\" border='0'>&nbsp;AD</td>\n";
                          }
                        }
                        else
                        {
                          //PROGRAMACION DE APOYOS DIAGNOSTICOS PENDIENTES.....
                          if($this->GetUserPermisos($datosEstacion['estacion_id'],'62'))
                          {
                            $urlAP = ModuloGetURL('app','EE_ControlesPacientes_APD','user','CallControlesPacientes',array("datos_estacion"=>$datosEstacion,"datosPaciente"=>$filaPacinte));
                            $conteo=$this->GetConteo_Hc_control_apoyod($filaPacinte[ingreso]);
                            if(empty($conteo)){$img='fecha_inicio.png';} else {$img='tabla_activa.png';}
                            $this->salida .= "	<td align=\"center\"><a href='$urlAP'><img src=\"". GetThemePath() ."/images/$img\" border='0'>&nbsp;AD</a></td>\n";
                          }
                          else
                          {
                            $conteo=$this->GetConteo_Hc_control_apoyod($filaPacinte[ingreso]);
                            if(empty($conteo)){$img='fecha_inicio.png';} else {$img='tabla_activa.png';}
                            $this->salida .= "	<td align=\"center\"><img src=\"". GetThemePath() ."/images/$img\" border='0'>&nbsp;AD</td>\n";
                          }
                        }
                              
                        //Controles Glucometria (CONTROL = 8)
                        //realizamos un conteo de neurologicos por cada ingreso.
                        $conteo_gluco=$this->GetControles($filaPacinte[ingreso],8);

                        if($conteo_gluco == 1)
                        {
                          if($this->GetUserPermisos($datosEstacion['estacion_id'],'63'))
                          {
                            $enlaceGlucometria = ModuloGetURL('app','EE_ControlesPacientes','user','CallFrmsControlesPacientes',array('datosPaciente'=>$filaPacinte,'control'=>'Glucometria','idControl'=>8,"href_action_hora"=>"FrmIngresarDatosGlucometria","href_action_control"=>array(0=>"FrmResumenGlucometria")));
                            $this->salida .= "	<td align=\"center\"><a href='$enlaceGlucometria'><img src=\"". GetThemePath() ."/images/glucometria.png\" border='0'>&nbsp;GM</a></td>\n";
                          }
                          else
                          {
                            $this->salida .= "	<td align=\"center\"><img src=\"". GetThemePath() ."/images/glucometria.png\" border='0'>&nbsp;GM</td>\n";
                          }
                        }
                        else
                        {
                          $this->salida .= "	<td align=\"center\"><img src=\"". GetThemePath() ."/images/noglucometria.png\" border='0'>&nbsp;GM</td>\n";
                        }
                              
                        //Controles Neurologicos (CONTROL = 10)
                        //realizamos un conteo de neurologicos por cada ingreso.
                        $conteo_neuro=$this->GetControles($filaPacinte[ingreso],10);
                        if($conteo_neuro == 1)
                        {
                          if($this->GetUserPermisos($datosEstacion['estacion_id'],'64'))
                          {
                            $Neuro = "<img src=\"".GetThemePath()."/images/neurologico.png\" border=\"0\" title='Toma de Controles Neurologicos.'>";
                            $UrlNeuro = ModuloGetURL('app','EE_ControlesPacientes','user','CallFrmsControlesPacientes',array('datosPaciente'=>$filaPacinte,'control'=>'Neurologico','idControl'=>10,"href_action_hora"=>"FrmControlesNeurologicos","href_action_control"=>array(0=>"ShowControl_Neurologico")));
                            $this->salida .= "      <td align=\"center\"><a href=\"$UrlNeuro\">".$Neuro." CN</a></td>\n";
                          }
                          else
                          {
                            $Neuro = "<img src=\"".GetThemePath()."/images/neurologico.png\" border=\"0\" title='Toma de Controles Neurologicos.'>";
                            $this->salida .= "      <td align=\"center\">".$Neuro." CN</a></td>\n";
                          }
                        }
                        else
                        {
                          $Neuro = "<img src=\"".GetThemePath()."/images/noneurologico.png\" border=\"0\" title='Toma de Controles Neurologicos.'>";
                          $this->salida .= "      <td align=\"center\">".$Neuro." CN</td>\n";
                        }

                        //Ordenes de Servicio
                        $conteo_os=$this->ConteoOrdenesPaciente($filaPacinte['ingreso'], $filaPacinte['paciente_id'], $filaPacinte['tipo_id_paciente']);
                        
                        //AQUI ES PARA COMUNICARSE CON LA CENTRAL DE IMPRESION DE ORDENES.
                        $_SESSION['CENTRALHOSP']['RETORNO']['modulo']='EE_PanelEnfermeria';
                        $_SESSION['CENTRALHOSP']['RETORNO']['metodo']='FrmPanelEstacion';
                        $_SESSION['CENTRALHOSP']['RETORNO']['tipo']='user';
                        $_SESSION['CENTRALHOSP']['RETORNO']['contenedor']='app';
                        $_SESSION['CENTRALHOSP']['RETORNO']['argumentos']=array('estacion'=>$datosEstacion);

                        if($conteo_os==1)
                        {
                          $href=ModuloGetURL('app','CentralImpresionHospitalizacion','user','BuscarPorEstacion',array("estacion"=>$datosEstacion[estacion_id],
                          "paciente_id"=>$filaPacinte[paciente_id],"tipo_id_paciente"=>$filaPacinte[tipo_id_paciente],"nombre_estacion"=>$datosEstacion[estacion_descripcion],"ingreso"=>$filaPacinte[ingreso],"empresa_id"=>$datosEstacion[empresa_id]));
                          $this->salida .= "	<td align=\"center\"><a href=\"$href\"><img src=\"". GetThemePath() ."/images/pinactivo.png\" border='0'>&nbsp;OS</a></td>\n";
                        }
                        else
                        {
                          $this->salida .= "	<td align=\"center\"><img src=\"". GetThemePath() ."/images/editar.png\" border='0'>&nbsp;OS</td>\n";
                        }
                        //SessionSetVar("RetornopanelEnfermeria",$_SESSION['CENTRALHOSP']['RETORNO']);
                        
                        $link_formatos = ModuloGetURL('app','ImpresionformatosHC','controller','FormatosImpresionEstacion',array("estacion"=>$datosEstacion[estacion_id],
                        "paciente_id"=>$filaPacinte[paciente_id],"tipo_id_paciente"=>$filaPacinte[tipo_id_paciente],"nombre_estacion"=>$datosEstacion[estacion_descripcion],"ingreso"=>$filaPacinte[ingreso],"empresa_id"=>$datosEstacion[empresa_id]));
                        $this->salida .= "	<td align=\"center\">\n";
                        $this->salida .= "    <a href=\"".$link_formatos."\" class=\"label_error\">\n";
                        $this->salida .= "      <img src=\"". GetThemePath() ."/images/imprimir.png\" border='0'>\n";
                        $this->salisa .= "    </a>\n";
                        $this->salida .= "  </td>\n";
//JONIER MURILLO               
                        $this->salida.="  <td align=\"center\"><label class='label_mark'>";
                        $estudios = $this->GetEstudiosImagenologia($filaPacinte['ingreso']);
                        if (!empty($estudios)) {
                          $this->salida.="<a ><img align=\"center\" src=\"". GetThemePath() ."/images/radiology.png\" border='0' title='Estudio Radiologia'></a>";
                          foreach ($estudios AS $estudio) {
                            $ruta   = "http://".$estudio['url'].":".$estudio['web_port']."/oviyam/oviyam?studyUID=".$estudio[estudio_id];
                            $nombre = "ESTUDIOS";
                            $str    = "width=1024,height=768,resizable=no,location=no, status=no,scrollbars=yes";
                            $this->salida.="<a href=\"javascript:void(0);\" onclick=\"window.open('".$ruta."', '".$nombre."', '".$str."');\">ORDEN #".$estudio[admision]."</a>";		
                          }	    
                         
                        } else {
                          $this->salida.="<a ><img align=\"center\" src=\"". GetThemePath() ."/images/fallo.png\" border='0' title='Sin Estudio Radiologia'></a>";
                        }
                        $this->salida.="</label></td>";

                        $infoAtenciones = $this->Profesionales_Atencion($filaPacinte[ingreso]);
                        $this->salida .= "      <td align=\"center\">".$infoAtenciones[nombre]."</td>\n";
   
                    }else{
                    
                        $GetDatPac = $this->GetAtencion($filaPacinte['paciente_id']);
                        if (count($GetDatPac) > 0){
                            $nomdpto = $GetDatPac[0][dpto];
                        }
                        $this->salida .= "      <td colspan = 11 align=\"center\">El Paciente se encuentra en:  ".$nomdpto."</td>\n";
                    
                    }
                    
                    if (($filaPacinte[ingresosestado]  ==  '1') and ($filaPacinte[cuentaestado]  ==  '1' or $filaPacinte[cuentaestado]  ==  '2')){ 
                      $this->salida .= "      <td align=\"center\" bgcolor=\"$_color\"><input type = 'button' id = 'a".$filaPacinte['ingreso']."' value = 'Desactivar'  onclick = \" if(a".$filaPacinte['ingreso'].".value == 'Desactivar'){a".$filaPacinte['ingreso'].".value = 'Actualizar';btning.value = ".$filaPacinte['ingreso']."; btncue.value = ".$filaPacinte['numerodecuenta'].";btnesting.value = ".$filaPacinte['ingresosestado']."; btnestcue.value = ".$filaPacinte['cuentaestado']."; btnpacid.value = ".$filaPacinte['paciente_id']."; Redire(btning.value, btncue.value, btnesting.value, btnestcue.value, btnpacid.value, rutah.value);}else{window.location.href = rutah.value;};\"></td>\n";
                    }else{
                      if (($filaPacinte[ingresosestado]  ==  '0' or $filaPacinte[ingresosestado]  ==  '2') and ($filaPacinte[cuentaestado]  ==  '2')){ 
                        $GetRegPac = $this->GetRegIngresoPaciente($filaPacinte['paciente_id']);
                        $VanIna = 0;
                        if(count($GetRegPac) > 0){
                          if ($filaPacinte['ingreso']<$GetRegPac[0]['ingreso']){
                            $VanIna = 1;
                          }
                        }

                        $this->salida .= $GetRegPac[0]['ingreso'];
                        if ($VanIna == 0){
          //								$this->salida .= "      <td align=\"center\" bgcolor=\"$_color\"><input type = 'button' class='input-submit' id = 'a".$filaPacinte['ingreso']."' value = 'Activar'  onclick = \"Redire(".$filaPacinte['ingreso'].", ".$filaPacinte['numerodecuenta'].", this.value, this.id, ".$filaPacinte[ingresosestado].", ".$filaPacinte[cuentaestado].", ".$filaPacinte[paciente_id].", rutah.value); this.style.display='none'; \"></td>\n";
                          $this->salida .= "      <td align=\"center\" bgcolor=\"$_color\"><input type = 'button' id = 'a".$filaPacinte['ingreso']."' value = 'Activar'  onclick = \" if(a".$filaPacinte['ingreso'].".value == 'Activar'){a".$filaPacinte['ingreso'].".value = 'Actualizar';btning.value = ".$filaPacinte['ingreso']."; btncue.value = ".$filaPacinte['numerodecuenta'].";btnesting.value = ".$filaPacinte['ingresosestado']."; btnestcue.value = ".$filaPacinte['cuentaestado']."; btnpacid.value = ".$filaPacinte['paciente_id']."; Redire(btning.value, btncue.value, btnesting.value, btnestcue.value, btnpacid.value, rutah.value);}else{window.location.href = rutah.value;};\"></td>\n";
                        }else{
                          $this->salida .= "      <td align=\"center\" bgcolor=\"$_color\"><input type = 'button' class='input-submit' id = 'a".$filaPacinte['ingreso']."' value = 'Activar' disabled = 'true'></td>\n";
                        }
                      }else{
                        $this->salida .= "      <td align=\"center\" bgcolor=\"$_color\"><input type = 'button' id = 'a".$filaPacinte['ingreso']."' value = 'Activar' disabled = 'true'></td>\n";
                      }
                    }
                    
//                    $this->salida .= "  </tr>\n";

                //HASTA AQUI
                }else{

                  //Busqueda de Programaciones de Cirugia para el Paciente
                  $programacion = $this->ValidarProgramacion_Cirugia($filaPacinte);

                  //Busqueda de las Conductas Pendientes, Para cada paciente.
                  $conducta = $this->BusquedaConducta($filaPacinte[ingreso]);

                  if($conducta[hc_tipo_orden_medica_id] == '01' OR  $conducta[hc_tipo_orden_medica_id] == '02' OR $conducta[hc_tipo_orden_medica_id] == '04')
                  {
                    $imagenPaciente = "<img src=\"".GetThemePath()."/images/trasladodepartamento.png\" border=0 title=\"ORDEN PARA: ".$conducta[descripcion]."\">";
                    if($this->GetUserPermisos($datosEstacion['estacion_id'],'04') OR $this->GetUserPermisos($datosEstacion['estacion_id'],'03'))
                    {
                      $url = ModuloGetURL('app','EE_AsignacionCama','user','FrmIngresoPaciente',array('datosPaciente'=>$filaPacinte,'accionFrmIngresoPaciente'=>'Traslado','SwCambioCama'=>'1','conducta'=>$conducta));
                      $this->salida .= "      <td><a href='$url'>$imagenPaciente</a></td>\n";
                    }
                    else
                    {
                      $this->salida .= "      <td>$imagenPaciente</td>\n";                         
                    }
                  }
                  elseif($conducta[hc_tipo_orden_medica_id] == '06' OR  $conducta[hc_tipo_orden_medica_id] == '07' OR $conducta[hc_tipo_orden_medica_id] == '99')
                  {
                    // Revisamos estado de la Alta del Paciente.
                    $vistos_ok = $this->BusquedaVistos_ok_salida($conducta);
                    // Revisamos las cuentas del paciente
                    $conteo_cuentas = $this->GetInfoCuentasActivas($filaPacinte['ingreso']); //revisa si tiene cuentas abiertas.
                     
                    if($vistos_ok['01']['ingreso'] AND $conteo_cuentas == '1')
                    {
                        $imagenPaciente = "<img src=\"".GetThemePath()."/images/egresocaja.png\" border=0 title=\"ORDEN PARA: ".$conducta[descripcion]."\">";                         
                    }
                    elseif($vistos_ok['01']['ingreso'] AND $conteo_cuentas == '0')
                    {
                      $imagenPaciente = "<img src=\"".GetThemePath()."/images/egresopacienteok.png\" border=0 title=\"ORDEN PARA: ".$conducta[descripcion]."\">";
                    }
                    else
                    {
                      $imagenPaciente = "<img src=\"".GetThemePath()."/images/egreso.png\" border=0 title=\"ORDEN PARA: ".$conducta[descripcion]."\">";
                    }
                     
                    if($this->GetUserPermisos($datosEstacion['estacion_id'],'56') AND $this->GetUserPermisos($datosEstacion['estacion_id'],'06'))
                    {
                      $url = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPacientePendiente_Egreso',array('datosPaciente'=>$filaPacinte,'datos_estacion'=>$datosEstacion,'conducta'=>$conducta));
                      $this->salida .= "      <td><a href='$url'>$imagenPaciente</a></td>\n";
                    }
                    else
                    {
                      $this->salida .= "      <td>$imagenPaciente</td>\n";                         
                    }
                  }
                  elseif($conducta[hc_tipo_orden_medica_id] == '05' OR !empty($programacion) OR $filaPacinte[paciente_cirugia] != 0)
                  {
                    if(empty($conducta[descripcion]))
                    $conducta[descripcion] = "TRASLADO A CIRUGIA.";

                    $imagenPaciente = "<img src=\"".GetThemePath()."/images/cirugia.png\" border=0 title=\"ORDEN PARA: ".$conducta[descripcion]."\">";
                    if($this->GetUserPermisos($datosEstacion['estacion_id'],'04') OR $this->GetUserPermisos($datosEstacion['estacion_id'],'03'))
                    {
                      $url = ModuloGetURL('app','EE_AsignacionCama','user','FrmIngresoPaciente',array('datosPaciente'=>$filaPacinte,'accionFrmIngresoPaciente'=>'Traslado','SwCambioCama'=>'1','conducta'=>$conducta));
                      $this->salida .= "      <td><a href='$url'>$imagenPaciente</a></td>\n";
                    }
                    else
                    {
                      $this->salida .= "      <td>$imagenPaciente</td>\n";                         
                    }                         
                  }
                  else
                  {
                    $imagenPaciente = "<img src=\"".GetThemePath()."/images/hospitalizacion.png\" border=0 title='Paciente Hospitalizado.'>";
                    if($this->GetUserPermisos($datosEstacion['estacion_id'],'01') OR $this->GetUserPermisos($datosEstacion['estacion_id'],'03') OR $this->GetUserPermisos($datosEstacion['estacion_id'],'04'))
                    {
                      $url = ModuloGetURL('app','EE_AsignacionCama','user','FrmIngresoPaciente',array('datosPaciente'=>$filaPacinte,'accionFrmIngresoPaciente'=>'Traslado','SwCambioCama'=>'1'));
                      $this->salida .= "      <td><a href='$url'>$imagenPaciente</a></td>\n";
                    }
                    else
                    {
                      $this->salida .= "      <td>$imagenPaciente</td>\n";                         
                    }                         
                  }
                        
                  $this->salida .= "      <td>$filaPacinte[pieza]</td>\n";
                  $this->salida .= "      <td>$filaPacinte[cama]</td>\n";
                  $this->salida .= "      <td>$nombre_paciente</td>\n";
                  $this->salida .= "      <td>$imagenHC</td>\n";
                  $this->salida .= "      <td align=\"right\">" . $this->GetDiasHospitalizacion($filaPacinte['fecha_hospitalizacion']) . "</td>\n";
                        
                  //Signos Vitales
                  $SignoVital = "<img src=\"".GetThemePath()."/images/estetoscopio.png\" border=\"0\" title='Toma de Signos Vitales.'>";
                  if($this->GetUserPermisos($datosEstacion['estacion_id'],'61'))
                  {
                    $SignoVital = "<img src=\"".GetThemePath()."/images/estetoscopio.png\" border=\"0\" title='Toma de Signos Vitales.'>";
                    $UrlSignos = ModuloGetURL('app','EE_ControlesPacientes','user','CallFrmsControlesPacientes',array('datosPaciente'=>$filaPacinte,'control'=>'SignoVital'));
                    $this->salida .= "      <td align=\"center\"><a href=\"$UrlSignos\">".$SignoVital." SV</a></td>\n";
                  }
                  else
                  {
                    $this->salida .= "      <td align=\"center\">".$SignoVital." SV</td>\n";
                  }
                  
                  //Administracion Medicamentos
                  $urla = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmMedicamentos',array('datosPaciente'=>$filaPacinte,"control_id"=>2,"control_descripcion"=>"CONTROL MEDICAMENTOS PACIENTE"));
                  $medicamento=$this->GetPacMedicamentosPorSolicitar($filaPacinte[ingreso]);
                  if($medicamento==1)
                  { $this->salida .= "      <td align=\"center\"><a href='$urla'><img src=\"". GetThemePath() ."/images/pparamedin.png\" border='0'>&nbsp;MP</a></td>\n"; }
                  else
                  { $this->salida .= "      <td align=\"center\"><a href='$urla'><img src=\"". GetThemePath() ."/images/pparamed.png\" border='0'>&nbsp;MP</a></td>\n"; }
              
                  //Controles Programados
                  $conteop=$this->CountControles($filaPacinte[ingreso]);
                  $urlcp = ModuloGetURL('app','EE_ControlesPacientes','user','CallFrmsControlesPacientes',array('datosPaciente'=>$filaPacinte,'control'=>'CProgramados'));
                  if($conteop == 1)
                  { $this->salida .= "      <td align=\"center\"><a href='$urlcp'><img src=\"". GetThemePath() ."/images/resultado.png\" border='0'>&nbsp;CP</a></td>\n"; }
                  else
                  { $this->salida .= "      <td align=\"center\"><img src=\"". GetThemePath() ."/images/prangos.png\" border='0'>&nbsp;CP</td>\n"; }
                        
                  //Controles de Apoyos Diagnosticos
                  $centinela=0;
                  //Traemos las fechas de los apoyos diagnosticos pendientes.
                  $fech_apoyo=$this->GetFechasHcApoyos($filaPacinte[ingreso]);
                  for($max=0;$max < sizeof($fech_apoyo);$max++)
                  {
                    if(strtotime($fech_apoyo[$max][fecha]) <= strtotime(date("y-m-d H:i:s")))
                    { $centinela=1; break;}
                    $centinela=0;
                  }

                  if($centinela==1)
                  {
                    if($this->GetUserPermisos($datosEstacion['estacion_id'],'62'))
                    {
                      $urlAP = ModuloGetURL('app','EE_ControlesPacientes_APD','user','CallControlesPacientes',array("datos_estacion"=>$datosEstacion,"datosPaciente"=>$filaPacinte));
                      $img='alarma.png';
                      $this->salida .= "	<td align=\"center\"><a href='$urlAP'><img src=\"". GetThemePath() ."/images/$img\" border='0'>&nbsp;AD</a></td>\n";
                    }
                    else
                    {
                      $img='alarma.png';
                      $this->salida .= "	<td align=\"center\"><img src=\"". GetThemePath() ."/images/$img\" border='0'>&nbsp;AD</td>\n";
                    }
                  }
                  else
                  {
                    //PROGRAMACION DE APOYOS DIAGNOSTICOS PENDIENTES.....
                    if($this->GetUserPermisos($datosEstacion['estacion_id'],'62'))
                    {
                      $urlAP = ModuloGetURL('app','EE_ControlesPacientes_APD','user','CallControlesPacientes',array("datos_estacion"=>$datosEstacion,"datosPaciente"=>$filaPacinte));
                      $conteo=$this->GetConteo_Hc_control_apoyod($filaPacinte[ingreso]);
                      if(empty($conteo)){$img='fecha_inicio.png';} else {$img='tabla_activa.png';}
                      $this->salida .= "	<td align=\"center\"><a href='$urlAP'><img src=\"". GetThemePath() ."/images/$img\" border='0'>&nbsp;AD</a></td>\n";
                    }
                    else
                    {
                      $conteo=$this->GetConteo_Hc_control_apoyod($filaPacinte[ingreso]);
                      if(empty($conteo)){$img='fecha_inicio.png';} else {$img='tabla_activa.png';}
                      $this->salida .= "	<td align=\"center\"><img src=\"". GetThemePath() ."/images/$img\" border='0'>&nbsp;AD</td>\n";
                    }
                  }
                        
                  //Controles Glucometria (CONTROL = 8)
                  //realizamos un conteo de neurologicos por cada ingreso.
                  $conteo_gluco=$this->GetControles($filaPacinte[ingreso],8);

                  if($conteo_gluco == 1)
                  {
                    if($this->GetUserPermisos($datosEstacion['estacion_id'],'63'))
                    {
                      $enlaceGlucometria = ModuloGetURL('app','EE_ControlesPacientes','user','CallFrmsControlesPacientes',array('datosPaciente'=>$filaPacinte,'control'=>'Glucometria','idControl'=>8,"href_action_hora"=>"FrmIngresarDatosGlucometria","href_action_control"=>array(0=>"FrmResumenGlucometria")));
                      $this->salida .= "	<td align=\"center\"><a href='$enlaceGlucometria'><img src=\"". GetThemePath() ."/images/glucometria.png\" border='0'>&nbsp;GM</a></td>\n";
                    }
                    else
                    {
                      $this->salida .= "	<td align=\"center\"><img src=\"". GetThemePath() ."/images/glucometria.png\" border='0'>&nbsp;GM</td>\n";
                    }
                  }
                  else
                  {
                    $this->salida .= "	<td align=\"center\"><img src=\"". GetThemePath() ."/images/noglucometria.png\" border='0'>&nbsp;GM</td>\n";
                  }
                        
                  //Controles Neurologicos (CONTROL = 10)
                  //realizamos un conteo de neurologicos por cada ingreso.
                  $conteo_neuro=$this->GetControles($filaPacinte[ingreso],10);
                  if($conteo_neuro == 1)
                  {
                    if($this->GetUserPermisos($datosEstacion['estacion_id'],'64'))
                    {
                      $Neuro = "<img src=\"".GetThemePath()."/images/neurologico.png\" border=\"0\" title='Toma de Controles Neurologicos.'>";
                      $UrlNeuro = ModuloGetURL('app','EE_ControlesPacientes','user','CallFrmsControlesPacientes',array('datosPaciente'=>$filaPacinte,'control'=>'Neurologico','idControl'=>10,"href_action_hora"=>"FrmControlesNeurologicos","href_action_control"=>array(0=>"ShowControl_Neurologico")));
                      $this->salida .= "      <td align=\"center\"><a href=\"$UrlNeuro\">".$Neuro." CN</a></td>\n";
                    }
                    else
                    {
                      $Neuro = "<img src=\"".GetThemePath()."/images/neurologico.png\" border=\"0\" title='Toma de Controles Neurologicos.'>";
                      $this->salida .= "      <td align=\"center\">".$Neuro." CN</a></td>\n";
                    }
                  }
                  else
                  {
                    $Neuro = "<img src=\"".GetThemePath()."/images/noneurologico.png\" border=\"0\" title='Toma de Controles Neurologicos.'>";
                    $this->salida .= "      <td align=\"center\">".$Neuro." CN</td>\n";
                  }

                  //Ordenes de Servicio
                  $conteo_os=$this->ConteoOrdenesPaciente($filaPacinte['ingreso'], $filaPacinte['paciente_id'], $filaPacinte['tipo_id_paciente']);
                  
                  //AQUI ES PARA COMUNICARSE CON LA CENTRAL DE IMPRESION DE ORDENES.
                  $_SESSION['CENTRALHOSP']['RETORNO']['modulo']='EE_PanelEnfermeria';
                  $_SESSION['CENTRALHOSP']['RETORNO']['metodo']='FrmPanelEstacion';
                  $_SESSION['CENTRALHOSP']['RETORNO']['tipo']='user';
                  $_SESSION['CENTRALHOSP']['RETORNO']['contenedor']='app';
                  $_SESSION['CENTRALHOSP']['RETORNO']['argumentos']=array('estacion'=>$datosEstacion);

                  if($conteo_os==1)
                  {
                    $href=ModuloGetURL('app','CentralImpresionHospitalizacion','user','BuscarPorEstacion',array("estacion"=>$datosEstacion[estacion_id],
                    "paciente_id"=>$filaPacinte[paciente_id],"tipo_id_paciente"=>$filaPacinte[tipo_id_paciente],"nombre_estacion"=>$datosEstacion[estacion_descripcion],"ingreso"=>$filaPacinte[ingreso],"empresa_id"=>$datosEstacion[empresa_id]));
                    $this->salida .= "	<td align=\"center\"><a href=\"$href\"><img src=\"". GetThemePath() ."/images/pinactivo.png\" border='0'>&nbsp;OS</a></td>\n";
                  }
                  else
                  {
                    $this->salida .= "	<td align=\"center\"><img src=\"". GetThemePath() ."/images/editar.png\" border='0'>&nbsp;OS</td>\n";
                  }
                  //SessionSetVar("RetornopanelEnfermeria",$_SESSION['CENTRALHOSP']['RETORNO']);
                  
                  $link_formatos = ModuloGetURL('app','ImpresionformatosHC','controller','FormatosImpresionEstacion',array("estacion"=>$datosEstacion[estacion_id],
                  "paciente_id"=>$filaPacinte[paciente_id],"tipo_id_paciente"=>$filaPacinte[tipo_id_paciente],"nombre_estacion"=>$datosEstacion[estacion_descripcion],"ingreso"=>$filaPacinte[ingreso],"empresa_id"=>$datosEstacion[empresa_id]));
                  $this->salida .= "	<td align=\"center\">\n";
                  $this->salida .= "    <a href=\"".$link_formatos."\" class=\"label_error\">\n";
                  $this->salida .= "      <img src=\"". GetThemePath() ."/images/imprimir.png\" border='0'>\n";
                  $this->salisa .= "    </a>\n";
                  $this->salida .= "  </td>\n";

//                  $this->salida .= "  </tr>\n";
                
                  $this->salida.="  <td align=\"center\"><label class='label_mark'>";
                  $estudios = $this->GetEstudiosImagenologia($filaPacinte['ingreso']);
                  if (!empty($estudios)) {
                    $this->salida.="<a ><img align=\"center\" src=\"". GetThemePath() ."/images/radiology.png\" border='0' title='Estudio Radiologia'></a>";
                    foreach ($estudios AS $estudio) {
                      $ruta   = "http://".$estudio['url'].":".$estudio['web_port']."/oviyam/oviyam?studyUID=".$estudio[estudio_id];
                      $nombre = "ESTUDIOS";
                      $str    = "width=1024,height=768,resizable=no,location=no, status=no,scrollbars=yes";
                      $this->salida.="<a href=\"javascript:void(0);\" onclick=\"window.open('".$ruta."', '".$nombre."', '".$str."');\">ORDEN #".$estudio[admision]."</a>";		
                    }	    
                   
                  } else {
                    $this->salida.="<a ><img align=\"center\" src=\"". GetThemePath() ."/images/fallo.png\" border='0' title='Sin Estudio Radiologia'></a>";
                  }
                  $this->salida.="</label></td>";

                  $infoAtenciones = $this->Profesionales_Atencion($filaPacinte[ingreso]);
                  $this->salida .= "      <td align=\"center\">".$infoAtenciones[nombre]."</td>\n";
                  $this->salida .= "  </tr>\n";

                }


                
            }
            else
            {
              $imagenPaciente = "<img src=\"".GetThemePath()."/images/cirugia.png\" border=0 title='El Paciente se encuentra en Cirugia.'>";
              $this->salida .= "      <td>$imagenPaciente</td>\n";
              $this->salida .= "      <td>$filaPacinte[pieza]</td>\n";
              $this->salida .= "      <td>$filaPacinte[cama]</td>\n";
              $this->salida .= "      <td>$nombre_paciente</td>\n";
              $this->salida .= "      <td>$imagenHC</td>\n";
              $this->salida .= "      <td align=\"right\">" . $this->GetDiasHospitalizacion($filaPacinte['fecha_hospitalizacion']) . "</td>\n";
              $this->salida .= "      <td colspan=\"11\" class=\"label_mark\" align=\"center\">EL PACIENTE SE ENCUENTRA EN CIRUGIA</td>\n";
            }
          }
          $this->salida .= "  </table>\n";
     }
     
     
     /**
     * Forma para mostrar el listado de pacientes en la Estacion de Enfermeria
     *
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function FrmListadoPacientesPendientesIngreso()
     {
          $listadoPacientes = $this->GetPacientesPorIngresar();
     
          if($listadoPacientes===false)
          {
               if(empty($this->error))
               {
                    $this->error = "EE_PanelEnfermeria - FrmListadoPacientesEstacion";
                    $this->mensajeDeError = "El metodo GetPacientesInternados() retorno false.";
               }
               return false;
          }
          if($listadoPacientes===null)
          {
               return true;
          }
     
          $this->IncludeJS('javascripts/funciones.js', $contenedor='app', $modulo='EE_PanelEnfermeria');
     
          $this->salida .= "<br>\n";
          $this->salida .= "<table align=\"center\" width=\"100%\"  border=\"0\" >\n";
          $this->salida .= "  <tr class=\"modulo_table_title\">\n";
          $this->salida .= "      <td colspan='7' height='30'>PACIENTE(S) PENDIENTES POR INGRESAR EN LA ESTACION</td>\n";
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr class=\"modulo_table_title\">\n";
          $this->salida .= "      <td width=\"19\">&nbsp;</td>\n";
          $this->salida .= "      <td align=\"center\">NOMBRE DEL PACIENTE</td>\n";
          $this->salida .= "      <td align=\"center\">PLAN</sub></td>\n";
          $this->salida .= "      <td align=\"center\">MEDICO</td>\n";
          $this->salida .= "      <td align=\"center\">ESTACION DE ORIGEN</td>\n";
          $this->salida .= "      <td align=\"center\">DIAGNOSTICO DE INGRESO</td>\n";
          $this->salida .= "      <td align=\"center\">OBSERVACIONES</td>\n";
          $this->salida .= "  </tr>\n";
     
          $backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
     
          foreach($listadoPacientes as $k => $filaPacinte)
          {
               //Busqueda de Programaciones de Cirugia para el Paciente
               $programacion = $this->ValidarProgramacion_Cirugia($filaPacinte);

               if($filaPacinte[paciente_cirugia] != 0 OR !empty($programacion))       
                    $imagenIngresar = "<img src=\"".GetThemePath()."/images/cirugia.png\" border=0 title='Orden de Traslado a Cirugia.' width='19' heigth='19'>";
			else
                    $imagenIngresar = "<img src=\"".GetThemePath()."/images/ingresar.png\" border=0 title='Ingresar y asignar cama al paciente.' width='19' heigth='19'>";          

               if($p++ % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
               $url = ModuloGetURL('app','EE_AsignacionCama','user','FrmIngresoPaciente',array('numero_registro'=>$filaPacinte['numero_registro'],'accionFrmIngresoPaciente'=>'Ingresar_Paciente'));
     
               $this->salida .= "<tr class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>\n";

               if($this->GetUserPermisos($datosEstacion['estacion_id'],'01') OR $this->GetUserPermisos($datosEstacion['estacion_id'],'04'))
               {
                    $this->salida .= "      <td><a href='$url'>$imagenIngresar</a></td>\n";                    
               }else
               {
                    $this->salida .= "      <td>$imagenIngresar</td>\n";
               }
               
               $url_info_paciente = ModuloGetURL('app','EE_PanelEnfermeria','user','MostrarDatosIngreso',array('ingreso'=>$filaPacinte['ingreso']));
               $this->salida .= "      <td><a href='$url_info_paciente'>$filaPacinte[nombre_completo]</a></td>\n";
               $this->salida .= "      <td>$filaPacinte[plan_descripcion]</td>\n";
               $this->salida .= "      <td>$filaPacinte[profesional]&nbsp;</td>\n";
               $this->salida .= "      <td>$filaPacinte[descripcion_estacion_origen]&nbsp;</td>\n";
               $this->salida .= "      <td>$filaPacinte[diagnostico_nombre]&nbsp;</td>\n";
               $this->salida .= "      <td>$filaPacinte[observaciones]&nbsp;</td>\n";
               $this->salida .= "  </tr>\n";
          }
     
          $this->salida .= "  </table>\n";
     
     }
     
 
     /**
     * Forma para mostrar el listado de pacientes en la Estacion de Enfermeria de Cirugia
     *
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function FrmListadoPacientesEstacionCirugia()
     {
          $datosEstacion = $this->GetEstacionActiva(false);
          
          $listadoPacientes = $this->GetPacientesInternadosCirugia($datosEstacion['departamento']);
          $_SESSION['EE_PanelEnfermeria']['listadoPacientes'] = $listadoPacientes;

          if($listadoPacientes===false)
          {
               if(empty($this->error))
               {
                    $this->error = "EE_PanelEnfermeria - FrmListadoPacientesEstacion";
                    $this->mensajeDeError = "El metodo GetPacientesInternados() retorno false.";
               }
               return false;
          }
          if($listadoPacientes===null)
          {
               return true;
          }
     
          $this->IncludeJS('javascripts/funciones.js', $contenedor='app', $modulo='EE_PanelEnfermeria');

          $this->salida .= "<br>\n";
          $this->salida .= "<table align=\"center\" width=\"100%\"  border=\"0\" >\n";
          $this->salida .= "  <tr class=\"modulo_table_title\">\n";
          $this->salida .= "      <td colspan='13' height='30'>PACIENTES INTERNADOS EN LA ESTACION DE CIRUGIA</td>\n";
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr class=\"modulo_table_title\">\n";
          $this->salida .= "      <td width=\"15\">&nbsp;</td>\n";
          $this->salida .= "      <td align=\"center\">QX.</td>\n";
          $this->salida .= "      <td align=\"center\">TIEMPO<BR>QX</sub></td>\n";
          $this->salida .= "      <td align=\"center\">NOMBRE DEL PACIENTE</td>\n";
          $this->salida .= "      <td width=\"22\">&nbsp;</td>\n";
          $this->salida .= "      <td align=\"center\">CANASTA<BR>CIRUGIA</td>\n";
          $this->salida .= "      <td align=\"center\">MED.<BR>PACIENTES</td>\n";
          $this->salida .= "      <td align=\"center\">SIGNOS<BR>VITALES</td>\n";
          $this->salida .= "      <td align=\"center\">CTRL<BR>PROGRAMADOS</td>\n";
          $this->salida .= "      <td align=\"center\">PROGR.<BR>APOYO</td>\n";
          $this->salida .= "      <td align=\"center\">GLUCO<BR>METRIA</td>\n";
          $this->salida .= "      <td align=\"center\">NEURO<BR>LOGICO</td>\n";
          $this->salida .= "      <td align=\"center\">ORDEN<BR>SERVICIOS</td>\n";
          $this->salida .= "  </tr>\n";
               
          $backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
          $imagenHC_cerrada = "<img src=\"".GetThemePath()."/images/hc.png\" border=0 title='Nueva anotación en la Historia Clinica del Paciente' width='22' heigth='22'>";
          $imagenHC_abierta = "<img src=\"".GetThemePath()."/images/hc_abierta.png\" border=0 title='Historia Clinica del Paciente - Sin Cerrar' width='22' heigth='22'>";
          $imagenHC_desabilitada = "<img src=\"".GetThemePath()."/images/hc_desabilitada.png\" border=0 title='Historia Clinica del Paciente - Sin Permiso' width='22' heigth='22'>";
          
          $_SESSION['HISTORIACLINICA']['RETORNO']['contenedor']   = 'app';
          $_SESSION['HISTORIACLINICA']['RETORNO']['modulo']       = 'EE_PanelEnfermeria';
          $_SESSION['HISTORIACLINICA']['RETORNO']['tipo']         = 'user';
          $_SESSION['HISTORIACLINICA']['RETORNO']['metodo']       = 'FrmPanelEstacion';
     
          foreach($listadoPacientes as $k => $filaPacinte)
          {
               if($p++ % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
     
               if($filaPacinte['evolucion_id'])
               {
                    $accion = ModuloHCGetURL($filaPacinte['evolucion_id'], -1, 0, '', false, array('estacion'=>$datosEstacion['estacion_id']));
                    $imagenHC = "<a href='$accion'>$imagenHC_abierta</a>";
               }
               else
               {
                    if($this->GetUserPermisos($datosEstacion['estacion_id'],'55'))
                    {
                         if($this->USERPERFIL == '10' OR $this->USERPERFIL == '11')
                         {
                         	$accion = ModuloHCGetURL(0, -1, $filaPacinte['ingreso'], $datosEstacion['hc_modulo_cirujano'], false, array('estacion'=>$datosEstacion['estacion_id']));
                         }elseif($this->USERPERFIL == '12')
                         {
                         	$accion = ModuloHCGetURL(0, -1, $filaPacinte['ingreso'], $datosEstacion['hc_modulo_anestesiologo'], false, array('estacion'=>$datosEstacion['estacion_id']));                         
                         }elseif($this->USERPERFIL == '13')
                         {
                         	$accion = ModuloHCGetURL(0, -1, $filaPacinte['ingreso'], $datosEstacion['hc_modulo_circulante'], false, array('estacion'=>$datosEstacion['estacion_id']));                         
                         }elseif($this->USERPERFIL == '14')
                         {
                         	$accion = ModuloHCGetURL(0, -1, $filaPacinte['ingreso'], $datosEstacion['hc_modulo_instrumentador'], false, array('estacion'=>$datosEstacion['estacion_id']));                         
                         }elseif($this->USERPERFIL == '15')
                         {
                         	$accion = ModuloHCGetURL(0, -1, $filaPacinte['ingreso'], $datosEstacion['hc_modulo_enfermeria'], false, array('estacion'=>$datosEstacion['estacion_id']));                         
                         }elseif($this->USERPERFIL == '16')
                         {
                         	$accion = ModuloHCGetURL(0, -1, $filaPacinte['ingreso'], $datosEstacion['hc_modulo_ayudante'], false, array('estacion'=>$datosEstacion['estacion_id']));                         
                         }

                         $imagenHC = "<a href='$accion'>$imagenHC_cerrada</a>";
                    }
                    else
                    {
                         $imagenHC = $imagenHC_desabilitada;
                    }
               }
     
               $url_info_paciente = ModuloGetURL('app','EE_PanelEnfermeria','user','MostrarDatosIngreso',array('ingreso'=>$filaPacinte['ingreso']));
               $nombre_paciente = "<a href='$url_info_paciente'>$filaPacinte[nombre_completo]</a>";
               $this->salida .= "<tr class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>\n";
                    
               //Busqueda de las Conductas Pendientes, Para cada paciente.
               $conducta = $this->BusquedaConducta($filaPacinte[ingreso]);
               $ObservacionQX = $this->PacienteRemitidoOservacionQX($filaPacinte[numerodecuenta],'','1');             
               if($this->USERPERFIL == '10' OR $this->USERPERFIL == '11' OR $this->USERPERFIL == '12' OR $this->USERPERFIL == '13' OR $this->USERPERFIL == '09')
               {
                    if($conducta[hc_tipo_orden_medica_id] == '11' OR $conducta[hc_tipo_orden_medica_id] == '12')
                    {
                         $imagenPaciente = "<img src=\"".GetThemePath()."/images/trasladodepartamento.png\" border=0 title=\"ORDEN PARA: ".$conducta[descripcion]."\">";
                         $url = ModuloGetURL('app','EE_FuncionalidadesQX','user','FrmAccionPacienteQX',array('datosPaciente'=>$filaPacinte,'accionPacienteQX'=>$conducta[hc_tipo_orden_medica_id],'conducta'=>$conducta));                    
                         $this->salida .= "      <td><a href='$url'>$imagenPaciente</a></td>\n";                    
                    }
                    elseif($conducta[hc_tipo_orden_medica_id] == '07' OR $conducta[hc_tipo_orden_medica_id] == '99')
                    {
                         // Revisamos estado de la Alta del Paciente.
                         $vistos_ok = $this->BusquedaVistos_ok_salida($conducta);
                         // Revisamos las cuentas del paciente
                         $conteo_cuentas = $this->GetInfoCuentasActivas($filaPacinte['ingreso']); //revisa si tiene cuentas abiertas.
                         
                         if($vistos_ok['01']['ingreso'] AND $conteo_cuentas == '1')
                         {
						$imagenPaciente = "<img src=\"".GetThemePath()."/images/egresocaja.png\" border=0 title=\"ORDEN PARA: ".$conducta[descripcion]."\">";                         
                         }elseif($vistos_ok['01']['ingreso'] AND $conteo_cuentas == '0')
                         {
						$imagenPaciente = "<img src=\"".GetThemePath()."/images/egresopacienteok.png\" border=0 title=\"ORDEN PARA: ".$conducta[descripcion]."\">";
                         }else
                         {
						$imagenPaciente = "<img src=\"".GetThemePath()."/images/egreso.png\" border=0 title=\"ORDEN PARA: ".$conducta[descripcion]."\">";
                         }

                         $url = ModuloGetURL('app','EE_FuncionalidadesQX','user','FrmAccionPacienteQX',array('datosPaciente'=>$filaPacinte,'accionPacienteQX'=>$conducta[hc_tipo_orden_medica_id],'conducta'=>$conducta));                    
                         $this->salida .= "      <td><a href='$url'>$imagenPaciente</a></td>\n";                    
                    }
					elseif($conducta[hc_tipo_orden_medica_id] == '14')
					{
						if(empty($conducta[descripcion]))
						$conducta[descripcion] = "TRASLADO A OTRA ESTACION DE CIRUGIA.";

						$imagenPaciente = "<img src=\"".GetThemePath()."/images/cirugia.png\" border=0 title=\"ORDEN PARA: ".$conducta[descripcion]."\">";
						if($this->GetUserPermisos($datosEstacion['estacion_id'],'04') OR $this->GetUserPermisos($datosEstacion['estacion_id'],'03'))
						{
						  $url = ModuloGetURL('app','EE_AsignacionCama','user','FrmIngresoPaciente',array('datosPaciente'=>$filaPacinte,'accionFrmIngresoPaciente'=>'Traslado','SwCambioCama'=>'1','conducta'=>$conducta));
						  $this->salida .= "      <td><a href='$url'>$imagenPaciente</a></td>\n";
						}
						else
						{
						  $this->salida .= "      <td>$imagenPaciente</td>\n";                         
						}                         
					}
                    elseif($conducta[hc_tipo_orden_medica_id] == '10' OR $ObservacionQX == '1')
                    {
                         $imagenPaciente = "<img src=\"".GetThemePath()."/images/observacion.png\" border=0 width=\"19\" title=\"Paciente en Recuperacion de Cirugia.\">";
                         $this->salida .= "      <td>$imagenPaciente</a></td>\n";
                         $this->PacienteRemitidoOservacionQX($filaPacinte[numerodecuenta],$conducta,'0');
                    }
                    else
                    {
                         $imagenPaciente = "<img src=\"".GetThemePath()."/images/cirugia.png\" border=\"0\" title=\"Paciente Internado en Sala de Cirugia.\">";
                         $this->salida .= "      <td>$imagenPaciente</a></td>\n";                    
                    }
               }else
               {
                    $imagenPaciente = "<img src=\"".GetThemePath()."/images/cirugia.png\" border=\"0\" title=\"Paciente Internado en Sala de Cirugia.\">";
                    $this->salida .= "      <td>$imagenPaciente</td>\n";
               }
               
               if($ObservacionQX == '0')
               {                    
                    $this->salida .= "      <td align=\"right\">".$this->QuirofanoPaciente($filaPacinte['programacion_id'])."</td>\n";
	               $this->salida .= "      <td align=\"right\">" . $this->GetDiasHospitalizacion($filaPacinte['fecha_ingreso_cirugia']) . "</td>\n";
                    $this->salida .= "      <td>$nombre_paciente</td>\n";
                    $this->salida .= "      <td>$imagenHC</td>\n";
                    
                    //Administracion Canasta de Medicamentos. (Cirugia)
                    $urla = ModuloGetURL('app','EE_AdministracionMedicamentosQX','user','CallFrmMedicamentos',array('datosPaciente'=>$filaPacinte,"control_id"=>2,"control_descripcion"=>"CONTROL MEDICAMENTOS PACIENTE"));
                    $this->salida .= "      <td align=\"center\"><a href='$urla'><img src=\"". GetThemePath() ."/images/medicinqx.png\" border='0'>&nbsp;MP</a></td>\n"; 
                    
                    //Administracion Medicamentos
                    $urla = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmMedicamentos',array('datosPaciente'=>$filaPacinte,"control_id"=>2,"control_descripcion"=>"CONTROL MEDICAMENTOS PACIENTE"));
                    $medicamento=$this->GetPacMedicamentosPorSolicitar($filaPacinte[ingreso]);
                    if($medicamento==1)
                    { $this->salida .= "      <td align=\"center\"><a href='$urla'><img src=\"". GetThemePath() ."/images/pparamedin.png\" border='0'>&nbsp;MP</a></td>\n"; }else
                    { $this->salida .= "      <td align=\"center\"><img src=\"". GetThemePath() ."/images/pparamed.png\" border='0'>&nbsp;MP</td>\n"; }

                    $this->salida .= "      <td colspan=\"5\" class=\"label_mark\" align=\"center\">EL PACIENTE SE ENCUENTRA EN CIRUGIA</td>\n";
                    //Ordenes de Servicio
                    $conteo_os=$this->ConteoOrdenesPaciente($filaPacinte['ingreso'], $filaPacinte['paciente_id'], $filaPacinte['tipo_id_paciente']);
                    if($conteo_os==1)
                    {
                         //AQUI ES PARA COMUNICARSE CON LA CENTRAL DE IMPRESION DE ORDENES.
                         $_SESSION['CENTRALHOSP']['RETORNO']['modulo']='EE_PanelEnfermeria';
                         $_SESSION['CENTRALHOSP']['RETORNO']['metodo']='FrmPanelEstacion';
                         $_SESSION['CENTRALHOSP']['RETORNO']['tipo']='user';
                         $_SESSION['CENTRALHOSP']['RETORNO']['contenedor']='app';
                         $_SESSION['CENTRALHOSP']['RETORNO']['argumentos']=array('estacion'=>$datosEstacion);
                         
                         $href=ModuloGetURL('app','CentralImpresionHospitalizacion','user','BuscarPorEstacion',array("estacion"=>$datosEstacion[estacion_id],
                         "paciente_id"=>$filaPacinte[paciente_id],"tipo_id_paciente"=>$filaPacinte[tipo_id_paciente],"nombre_estacion"=>$datosEstacion[estacion_descripcion],"ingreso"=>$filaPacinte[ingreso],"empresa_id"=>$datosEstacion[empresa_id]));
                         $this->salida .= "	<td align=\"center\"><a href=\"$href\"><img src=\"". GetThemePath() ."/images/pinactivo.png\" border='0'>&nbsp;OS</a></td>\n";
                    }
                    else
                    {
                         $this->salida .= "	<td align=\"center\"><img src=\"". GetThemePath() ."/images/editar.png\" border='0'>&nbsp;OS</td>\n";
                    }
               }
               else
               {
                    $this->salida .= "      <td align=\"right\">".$this->QuirofanoPaciente($filaPacinte['programacion_id'])."</td>\n";
               	$this->salida .= "      <td align=\"right\">" . $this->GetDiasHospitalizacion($filaPacinte['fecha_ingreso_cirugia']) . "</td>\n";
                    $this->salida .= "      <td>$nombre_paciente</td>\n";
                    $this->salida .= "      <td>$imagenHC</td>\n";
                    
                    //Administracion Canasta de Medicamentos. (Cirugia)
                    $urla = ModuloGetURL('app','EE_AdministracionMedicamentosQX','user','CallFrmMedicamentos',array('datosPaciente'=>$filaPacinte,"control_id"=>2,"control_descripcion"=>"CONTROL MEDICAMENTOS PACIENTE"));
                    $this->salida .= "      <td align=\"center\"><a href='$urla'><img src=\"". GetThemePath() ."/images/medicinqx.png\" border='0'>&nbsp;MP</a></td>\n"; 
               
                    //Administracion Medicamentos
                    $urla = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmMedicamentos',array('datosPaciente'=>$filaPacinte,"control_id"=>2,"control_descripcion"=>"CONTROL MEDICAMENTOS PACIENTE"));
                    $medicamento=$this->GetPacMedicamentosPorSolicitar($filaPacinte[ingreso]);
                    if($medicamento==1)
                    { $this->salida .= "      <td align=\"center\"><a href='$urla'><img src=\"". GetThemePath() ."/images/pparamedin.png\" border='0'>&nbsp;MP</a></td>\n"; }else
                    { $this->salida .= "      <td align=\"center\"><img src=\"". GetThemePath() ."/images/pparamed.png\" border='0'>&nbsp;MP</td>\n"; }
                    
                    //Signos Vitales
					if($this->GetUserPermisos($datosEstacion['estacion_id'],'61'))
                    {
						$SignoVital = "<img src=\"".GetThemePath()."/images/estetoscopio.png\" border=\"0\" title='Toma de Signos Vitales.'>";
						$UrlSignos = ModuloGetURL('app','EE_ControlesPacientes','user','CallFrmsControlesPacientes',array('datosPaciente'=>$filaPacinte,'control'=>'SignoVital'));
						$this->salida .= "      <td align=\"center\"><a href=\"$UrlSignos\">".$SignoVital." SV</a></td>\n";
					}
					else
					{
						$SignoVital = "<img src=\"".GetThemePath()."/images/estetoscopio.png\" border=\"0\" title='Toma de Signos Vitales.'>";
						$this->salida .= "      <td align=\"center\">".$SignoVital." SV</td>\n";
					}
                    //Controles Programados
                    $conteop=$this->CountControles($filaPacinte[ingreso]);
                    $urlcp = ModuloGetURL('app','EE_ControlesPacientes','user','CallFrmsControlesPacientes',array('datosPaciente'=>$filaPacinte,'control'=>'CProgramados'));
                    if($conteop == 1)
                    { $this->salida .= "      <td align=\"center\"><a href='$urlcp'><img src=\"". GetThemePath() ."/images/resultado.png\" border='0'>&nbsp;CP</a></td>\n"; }
                    else{ $this->salida .= "      <td align=\"center\"><img src=\"". GetThemePath() ."/images/prangos.png\" border='0'>&nbsp;CP</td>\n"; }
                    
                    //Controles de Apoyos Diagnosticos
                    $centinela=0;
                    //Traemos las fechas de los apoyos diagnosticos pendientes.
                    $fech_apoyo=$this->GetFechasHcApoyos($filaPacinte[ingreso]);
                    for($max=0;$max < sizeof($fech_apoyo);$max++)
                    {
                         if(strtotime($fech_apoyo[$max][fecha]) <= strtotime(date("y-m-d H:i:s")))
                         { $centinela=1; break;}
                         $centinela=0;
                    }

                    if($centinela==1)
                    {
                        if($this->GetUserPermisos($datosEstacion['estacion_id'],'62'))
						{
							$urlAP = ModuloGetURL('app','EE_ControlesPacientes_APD','user','CallControlesPacientes',array("datos_estacion"=>$datosEstacion,"datosPaciente"=>$filaPacinte));
							$img='alarma.png';
							$this->salida .= "	<td align=\"center\"><a href='$urlAP'><img src=\"". GetThemePath() ."/images/$img\" border='0'>&nbsp;AD</a></td>\n";
						}
						else
						{
							$img='alarma.png';
							$this->salida .= "	<td align=\"center\"><img src=\"". GetThemePath() ."/images/$img\" border='0'>&nbsp;AD</td>\n";
						}
					}
                    else
                    {
                        //PROGRAMACION DE APOYOS DIAGNOSTICOS PENDIENTES.....
						if($this->GetUserPermisos($datosEstacion['estacion_id'],'62'))
						{
							$urlAP = ModuloGetURL('app','EE_ControlesPacientes_APD','user','CallControlesPacientes',array("datos_estacion"=>$datosEstacion,"datosPaciente"=>$filaPacinte));
							$conteo=$this->GetConteo_Hc_control_apoyod($filaPacinte[ingreso]);
							if(empty($conteo)){$img='fecha_inicio.png';} else {$img='tabla_activa.png';}
							$this->salida .= "	<td align=\"center\"><a href='$urlAP'><img src=\"". GetThemePath() ."/images/$img\" border='0'>&nbsp;AD</a></td>\n";
						}
						else
						{
							$conteo=$this->GetConteo_Hc_control_apoyod($filaPacinte[ingreso]);
							if(empty($conteo)){$img='fecha_inicio.png';} else {$img='tabla_activa.png';}
							$this->salida .= "	<td align=\"center\"><img src=\"". GetThemePath() ."/images/$img\" border='0'>&nbsp;AD</td>\n";
						}
					}
                    
                    //Controles Glucometria (CONTROL = 8)
                    //realizamos un conteo de neurologicos por cada ingreso.
                    $conteo_gluco=$this->GetControles($filaPacinte[ingreso],8);

                    if($conteo_gluco == 1)
                    {
                        if($this->GetUserPermisos($datosEstacion['estacion_id'],'63'))
						{
							$enlaceGlucometria = ModuloGetURL('app','EE_ControlesPacientes','user','CallFrmsControlesPacientes',array('datosPaciente'=>$filaPacinte,'control'=>'Glucometria','idControl'=>8,"href_action_hora"=>"FrmIngresarDatosGlucometria","href_action_control"=>array(0=>"FrmResumenGlucometria")));
							$this->salida .= "	<td align=\"center\"><a href='$enlaceGlucometria'><img src=\"". GetThemePath() ."/images/glucometria.png\" border='0'>&nbsp;GM</a></td>\n";
						}
						else
						{
							$this->salida .= "	<td align=\"center\"><img src=\"". GetThemePath() ."/images/glucometria.png\" border='0'>&nbsp;GM</td>\n";
						}
					}
                    else
                    {
                         $this->salida .= "	<td align=\"center\"><img src=\"". GetThemePath() ."/images/noglucometria.png\" border='0'>&nbsp;GM</td>\n";
                    }
                    
                    //Controles Neurologicos (CONTROL = 10)
                    //realizamos un conteo de neurologicos por cada ingreso.
                    $conteo_neuro=$this->GetControles($filaPacinte[ingreso],10);
                    if($conteo_neuro == 1)
                    {
                        if($this->GetUserPermisos($datosEstacion['estacion_id'],'64'))
						{
							$Neuro = "<img src=\"".GetThemePath()."/images/neurologico.png\" border=\"0\" title='Toma de Controles Neurologicos.'>";
							$UrlNeuro = ModuloGetURL('app','EE_ControlesPacientes','user','CallFrmsControlesPacientes',array('datosPaciente'=>$filaPacinte,'control'=>'Neurologico','idControl'=>10,"href_action_hora"=>"FrmControlesNeurologicos","href_action_control"=>array(0=>"ShowControl_Neurologico")));
							$this->salida .= "      <td align=\"center\"><a href=\"$UrlNeuro\">".$Neuro." CN</a></td>\n";
						}
						else
						{
							$Neuro = "<img src=\"".GetThemePath()."/images/neurologico.png\" border=\"0\" title='Toma de Controles Neurologicos.'>";
							$this->salida .= "      <td align=\"center\">".$Neuro." CN</td>\n";
						}
					}
					else
                    {
                         $Neuro = "<img src=\"".GetThemePath()."/images/noneurologico.png\" border=\"0\" title='Toma de Controles Neurologicos.'>";
                         $this->salida .= "      <td align=\"center\">".$Neuro." CN</td>\n";
                    }
                    
                    
                    //Ordenes de Servicio
                    $conteo_os=$this->ConteoOrdenesPaciente($filaPacinte['ingreso'], $filaPacinte['paciente_id'], $filaPacinte['tipo_id_paciente']);
                    if($conteo_os==1)
                    {
                         //AQUI ES PARA COMUNICARSE CON LA CENTRAL DE IMPRESION DE ORDENES.
                         $_SESSION['CENTRALHOSP']['RETORNO']['modulo']='EE_PanelEnfermeria';
                         $_SESSION['CENTRALHOSP']['RETORNO']['metodo']='FrmPanelEstacion';
                         $_SESSION['CENTRALHOSP']['RETORNO']['tipo']='user';
                         $_SESSION['CENTRALHOSP']['RETORNO']['contenedor']='app';
                         $_SESSION['CENTRALHOSP']['RETORNO']['argumentos']=array('estacion'=>$datosEstacion);
                         
                         $href=ModuloGetURL('app','CentralImpresionHospitalizacion','user','BuscarPorEstacion',array("estacion"=>$datosEstacion[estacion_id],
                         "paciente_id"=>$filaPacinte[paciente_id],"tipo_id_paciente"=>$filaPacinte[tipo_id_paciente],"nombre_estacion"=>$datosEstacion[estacion_descripcion],"ingreso"=>$filaPacinte[ingreso],"empresa_id"=>$datosEstacion[empresa_id]));
                         $this->salida .= "	<td align=\"center\"><a href=\"$href\"><img src=\"". GetThemePath() ."/images/pinactivo.png\" border='0'>&nbsp;OS</a></td>\n";
                    }
                    else
                    {
                         $this->salida .= "	<td align=\"center\"><img src=\"". GetThemePath() ."/images/editar.png\" border='0'>&nbsp;OS</td>\n";
                    }
               }
               $this->salida .= "  </tr>\n";
          }
          $this->salida .= "  </table>\n";
     }
     
     
     /**
     * Forma para mostrar el listado de pacientes en la Estacion de Enfermeria de Cirugia
     *
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function FrmListadoPacientesPendientesIngresoCirugia()
     {
          $datosEstacion = $this->GetEstacionActiva(false);
          
          $listadoPacientes = $this->GetPacientesPorIngresarCirugia($datosEstacion['departamento']);
     
          if($listadoPacientes===false)
          {
               if(empty($this->error))
               {
                    $this->error = "EE_PanelEnfermeria - FrmListadoPacientesEstacion";
                    $this->mensajeDeError = "El metodo GetPacientesInternados() retorno false.";
               }
               return false;
          }
          if($listadoPacientes===null)
          {
               return true;
          }
     
          $this->IncludeJS('javascripts/funciones.js', $contenedor='app', $modulo='EE_PanelEnfermeria');
     
          $this->salida .= "<br>\n";
          $this->salida .= "<table align=\"center\" width=\"100%\"  border=\"0\" >\n";
          $this->salida .= "  <tr class=\"modulo_table_title\">\n";
          $this->salida .= "      <td colspan='5' height='30'>PACIENTE(S) PENDIENTES POR INGRESAR AL QUIROFANO</td>\n";
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr class=\"modulo_table_title\">\n";
          $this->salida .= "      <td width=\"19\">&nbsp;</td>\n";
          $this->salida .= "      <td align=\"center\" width=\"30%\">NOMBRE DEL PACIENTE</td>\n";
          $this->salida .= "      <td align=\"center\">PLAN</sub></td>\n";
          $this->salida .= "      <td align=\"center\">ESTACION DE ORIGEN</td>\n";
          $this->salida .= "      <td align=\"center\">OBSERVACIONES</td>\n";
          $this->salida .= "  </tr>\n";
     
          $backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
     
          foreach($listadoPacientes as $k => $filaPacinte)
          {
               
               $imagenIngresar = "<img src=\"".GetThemePath()."/images/cargosin.png\" border=0 title='Pendiente por Ingresar al Quirofano.' width='19' heigth='19'>";

               if($p++ % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
     
               $this->salida .= "<tr class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>\n";
               
               $this->salida .= "      <td>$imagenIngresar</td>\n";
               
               $url_info_paciente = ModuloGetURL('app','EE_PanelEnfermeria','user','MostrarDatosIngreso',array('ingreso'=>$filaPacinte['ingreso']));
               $this->salida .= "      <td><a href='$url_info_paciente'>$filaPacinte[nombre_completo]</a></td>\n";
               $this->salida .= "      <td>$filaPacinte[plan_descripcion]</td>\n";
               $this->salida .= "      <td>$filaPacinte[descripcion_estacion_origen]&nbsp;</td>\n";
               $this->salida .= "      <td>$filaPacinte[observaciones]&nbsp;</td>\n";
               $this->salida .= "  </tr>\n";
          }
          $this->salida .= "  </table>\n";
     }

         
     /**
     * Forma para mostrar el listado de pacientes en consulta de urgencias de la Estacion de Enfermeria
     *
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function  FrmListadoPacientesConsultaUrgencias($sw)
     {
          $permisoingresoactivo = 0;
          $permi = $this->GetPermisoIngAct();
          if (!empty($permi)){
            if ($permi[0]['sw_permiso'] == 1){
              $permisoingresoactivo = 1;
              $listadoPacientes = $this->GetPacientesConsultaUrgenciasConPermiso($estacion_id=null,$sw);
            }else{
              $listadoPacientes = $this->GetPacientesConsultaUrgencias($estacion_id=null,$sw);
            }
          }else{
            $listadoPacientes = $this->GetPacientesConsultaUrgencias($estacion_id=null,$sw);
          }
          //
          
          
          static $_colorNivel;
          if(empty($_colorNivel))
          {
          	$_colorNivel = $this->GetColorGround();
          }

          $_SESSION['EE_PanelEnfermeria']['listadoPacientes_Urgencias'] = $listadoPacientes;
          if($listadoPacientes===false)
          {
               if(empty($this->error))
               {
                    $this->error = "EE_PanelEnfermeria - FrmListadoPacientesConsultaUrgencias";
                    $this->mensajeDeError = "El metodo GetPacientesConsultaUrgencias() retorno false.";
               }
               return false;
          }
          if($listadoPacientes===null)
          {
               return true;
          }
          $datosEstacion = $this->GetEstacionActiva(false);
          $this->IncludeJS('javascripts/funciones.js', $contenedor='app', $modulo='EE_PanelEnfermeria');
     
          
          SessionSetVar("RetornopanelEnfermeria",$_SESSION['CENTRALHOSP']['RETORNO']);
          $llamdas = ModuloGetURL('app','ImpresionformatosHC','controller','LlamadasEspecialista',array("datosEstacion"=>$datosEstacion));
          $this->salida .= "<br>\n";
          $this->salida .= "<div style=\"text-align:center\">\n";
          $this->salida .= "  <a href=\"".$llamdas."\" class=\"label_error\">\n";
          $this->salida .= "    REGISTRO DE LLAMADAS A ESPECIALISTA\n";
          $this->salisa .= "  </a>\n";
          $this->salida .= "</div>\n";
          $this->salida .= "<br>\n";

          
          $this->salida.= "<form name='form1' id = 'form1' method='post'>";
          $this->salida .= "<table align=\"center\" width=\"100%\"  border=\"0\" >\n";
          $this->salida .= "  <tr class=\"modulo_table_title\">\n";
          $this->salida .= "      <td colspan='16' height='30'>PACIENTES EN CONSULTA DE URGENCIAS</td>\n";
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr class=\"modulo_table_title\">\n";
          $this->salida .= "      <td width=\"15\">&nbsp;</td>\n";
          $this->salida .= "      <td align=\"center\">NOMBRE DEL PACIENTE</td>\n";
          $this->salida .= "      <td width=\"22\">EDAD</td>\n";
          $this->salida .= "      <td width=\"22\">PRIORI.</td>\n";
          $this->salida .= "      <td width=\"22\">&nbsp;</td>\n";
          $this->salida .= "      <td align=\"center\">TIEMPO<BR>HOSP.</td>\n";
          $this->salida .= "      <td align=\"center\">SIGNOS<BR>VITALES</td>\n";
          $this->salida .= "      <td align=\"center\">MED.<BR>PACIENTES</td>\n";
          $this->salida .= "      <td align=\"center\">ORDEN<BR>SERVICIOS</td>\n";
          $this->salida .= "      <td align=\"center\">IMP</td>\n";
          $this->salida .= "      <td align=\"center\">IMÁGENES </td>\n";
          $this->salida .= "      <td align=\"center\" colspan=\"4\">PROFESIONALES ATENCION</td>\n";
          if ($permisoingresoactivo == 1){
              $this->salida .= "      <td align=\"center\">ACTIVAR/INACTIVAR<BR>INGRESO</td>\n";
          }
          $this->salida .= "  </tr>\n";

          if ($permisoingresoactivo == 1){
            $refresh = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
            $this->salida .= "      <tr>
                                        <td><input type='hidden' name='rutah' id='rutah' value = '$refresh'></td>
                                        <td><input type='hidden' name='btning' id='btning'></td>
                                        <td><input type='hidden' name='btncue' id='btncue'></td>
                                        <td><input type='hidden' name='btnesting' id='btnesting'></td>
                                        <td><input type='hidden' name='btnestcue' id='btnestcue'></td>
                                        <td><input type='hidden' name='btnpacid' id='btnpacid'></td>
                                    </tr>\n";
          }
          
          $backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
          $imagenHC_cerrada = "<img src=\"".GetThemePath()."/images/hc.png\" border=0 title='Nueva anotación en la Historia Clinica del Paciente' width='22' heigth='22'>";
          $imagenHC_abierta = "<img src=\"".GetThemePath()."/images/hc_abierta.png\" border=0 title='Historia Clinica del Paciente - Sin Cerrar' width='22' heigth='22'>";
          $imagenHC_desabilitada = "<img src=\"".GetThemePath()."/images/hc_desabilitada.png\" border=0 title='Historia Clinica del Paciente - Sin Permiso' width='22' heigth='22'>";
          $datosEstacion = $this->GetEstacionActiva(false);
     
          $_SESSION['HISTORIACLINICA']['RETORNO']['contenedor']   = 'app';
          $_SESSION['HISTORIACLINICA']['RETORNO']['modulo']       = 'EE_PanelEnfermeria';
          $_SESSION['HISTORIACLINICA']['RETORNO']['tipo']         = 'user';
          $_SESSION['HISTORIACLINICA']['RETORNO']['metodo']       = 'FrmPanelEstacion';

          foreach($listadoPacientes as $k => $filaPacinte)
          {
               if($p++ % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
     
               if($filaPacinte['evolucion_id'])
               {
                    $accion = ModuloHCGetURL($filaPacinte['evolucion_id'], -1, 0, '', false, array('estacion'=>$datosEstacion['estacion_id']));
                    $imagenHC = "<a href='$accion'>$imagenHC_abierta</a>";
               }
               else
               {
                    if($this->GetUserPermisos($datosEstacion['estacion_id'],'55'))
                    {
                         if($this->USERPERFIL == '10' OR $this->USERPERFIL == '11')
                         {
                         	$accion = ModuloHCGetURL(0, -1, $filaPacinte['ingreso'], $datosEstacion['hc_modulo_consulta_urgencias'], false, array('estacion'=>$datosEstacion['estacion_id']));
                         }
                         else
                         {
                         	$accion = ModuloHCGetURL(0, -1, $filaPacinte['ingreso'], $datosEstacion['hc_modulo_enfermera'], false, array('estacion'=>$datosEstacion['estacion_id']));                         
                         }
                         
                         $imagenHC = "<a href='$accion'>$imagenHC_cerrada</a>";
                    }
                    else
                    {
                         $imagenHC = $imagenHC_desabilitada;
                    }
               }
               
               $url_info_paciente = ModuloGetURL('app','EE_PanelEnfermeria','user','MostrarDatosIngreso',array('ingreso'=>$filaPacinte['ingreso']));
//COMPROBAR      $nombre_paciente = "<a href='$url_info_paciente'>$filaPacinte[nombre_completo] ".$filaPacinte[ingresosestado]." - ".$filaPacinte[cuentaestado]." </a>";
               $nombre_paciente = "<a href='$url_info_paciente'>$filaPacinte[nombre_completo]</a>";
               
               if(!$filaPacinte["nivel_triage_id"])
               	$filaPacinte["nivel_triage_id"] = 0;
                  
               $_color = $_colorNivel[$filaPacinte["nivel_triage_id"]]["bgcolor"];
               
               $this->salida .= "<tr class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'$_color');>\n";
                    
               //Busqueda de Programaciones de Cirugia para el Paciente
               $programacion = $this->ValidarProgramacion_Cirugia($filaPacinte);

               $estaincu = 0;
               if (($filaPacinte[ingresosestado]  ==  '1') and ($filaPacinte[cuentaestado]  ==  '1' or $filaPacinte[cuentaestado]  ==  '2')){ 
                  $estaincu = 1;
               }else{
                  if (($filaPacinte[ingresosestado]  ==  '0')){ // and ($filaPacinte[cuentaestado]  ==  '0')){ 
                  //						$this->salida .= "      <td align=\"center\" bgcolor=\"$_color\"><input type = 'button' class='input-submit' id = 'a".$filaPacinte['ingreso']."' value = 'Activar'  onclick = \"xajax_Activar_IngCue(".$filaPacinte['ingreso'].", ".$filaPacinte['numerodecuenta'].", this.value, this.id, ".$filaPacinte[ingresosestado].", ".$filaPacinte[cuentaestado].", ".$filaPacinte[paciente_id].")\"></td>\n";
                      $estaincu = 3;
                  }
               }               
               //Busqueda de las Conductas Pendientes, Para cada paciente.
               $conducta = $this->BusquedaConducta($filaPacinte[ingreso]);
               if($conducta[hc_tipo_orden_medica_id] == '01' OR  $conducta[hc_tipo_orden_medica_id] == '02' OR $conducta[hc_tipo_orden_medica_id] == '04')
               {
                    $imagenPaciente = "<img src=\"".GetThemePath()."/images/trasladodepartamento.png\" border=0 title=\"ORDEN PARA: ".$conducta[descripcion]."\">";                          
                    if($this->GetUserPermisos($datosEstacion['estacion_id'],'04'))
                    {
	                    $url = ModuloGetURL('app','EE_AsignacionCama','user','FrmIngresoPaciente',array('datosPaciente'=>$filaPacinte,'accionFrmIngresoPaciente'=>'Traslado','SwCambioCama'=>'1','conducta'=>$conducta,'estado'=>'ConsultaURG'));
                         $this->salida .= "      <td><a href='$url'>$imagenPaciente</a></td>\n";
                    }
                    else
                    {
                         $this->salida .= "      <td>$imagenPaciente</td>\n";                         
                    } 
               }
               elseif($conducta[hc_tipo_orden_medica_id] == '06' OR  $conducta[hc_tipo_orden_medica_id] == '07' OR $conducta[hc_tipo_orden_medica_id] == '99')
               {
                    // Revisamos estado de la Alta del Paciente.
                    $vistos_ok = $this->BusquedaVistos_ok_salida($conducta);
                    // Revisamos las cuentas del paciente
                    $conteo_cuentas = $this->GetInfoCuentasActivas($filaPacinte['ingreso']); //revisa si tiene cuentas abiertas.
                    
                    if($vistos_ok['01']['ingreso'] AND $conteo_cuentas == '1')
                    {
                         $imagenPaciente = "<img src=\"".GetThemePath()."/images/egresocaja.png\" border=0 title=\"ORDEN PARA: ".$conducta[descripcion]."\">";                         
                    }elseif($vistos_ok['01']['ingreso'] AND $conteo_cuentas == '0')
                    {
                         $imagenPaciente = "<img src=\"".GetThemePath()."/images/egresopacienteok.png\" border=0 title=\"ORDEN PARA: ".$conducta[descripcion]."\">";
                    }else
                    {
                         $imagenPaciente = "<img src=\"".GetThemePath()."/images/egreso.png\" border=0 title=\"ORDEN PARA: ".$conducta[descripcion]."\">";
                    }
                    
                    if($this->GetUserPermisos($datosEstacion['estacion_id'],'06') AND $this->GetUserPermisos($datosEstacion['estacion_id'],'56'))
                    {
	                    $url = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPacientePendiente_Egreso',array('datosPaciente'=>$filaPacinte,'datos_estacion'=>$datosEstacion,'conducta'=>$conducta,'estado'=>'ConsultaURG'));
                      if ($permisoingresoactivo == 1){
                          if ($estaincu == 1){
                              $this->salida .= "      <td><a href='$url'>$imagenPaciente</a></td>\n";
                          }else{
                              $this->salida .= "      <td></td>\n";
                          }
                      }else{
                                $this->salida .= "      <td><a href='$url'>$imagenPaciente</a></td>\n";
                      }
                    }
                    else
                    {
                         $this->salida .= "      <td>$imagenPaciente</td>\n";                         
                    } 
               }
               elseif($conducta[hc_tipo_orden_medica_id] == '05' OR !empty($programacion) OR $filaPacinte[paciente_cirugia] != 0)
               {
                    if(empty($conducta[descripcion]))
                    	$conducta[descripcion] = "TRASLADO A CIRUGIA.";
                         
                    $imagenPaciente = "<img src=\"".GetThemePath()."/images/cirugia.png\" border=0 title=\"ORDEN PARA: ".$conducta[descripcion]."\">";                    
                    if($this->GetUserPermisos($datosEstacion['estacion_id'],'04') AND $filaPacinte[paciente_cirugia] == 0)
                    {
                         $url = ModuloGetURL('app','EE_AsignacionCama','user','FrmIngresoPaciente',array('datosPaciente'=>$filaPacinte,'accionFrmIngresoPaciente'=>'Traslado','SwCambioCama'=>'1','conducta'=>$conducta,'estado'=>'ConsultaURG'));
                          if ($permisoingresoactivo == 1){
                              if ($estaincu == 1){
                                    $this->salida .= "      <td><a href='$url'>$imagenPaciente</a></td>\n";
                              }else{
                                  $this->salida .= "      <td></td>\n";
                              }
                          }else{
                                    $this->salida .= "      <td><a href='$url'>$imagenPaciente</a></td>\n";
                          }
                    }
                    else
                    {
                         $this->salida .= "      <td>$imagenPaciente</td>\n";                         
                    } 
               }
               else
               {
		          $imagenPaciente = "<img src=\"".GetThemePath()."/images/consulta_ur.png\" border=0 title='Ingresar y asignar cama al paciente.'>";
                    $this->salida .= "      <td>$imagenPaciente</td>\n";                         
               }
               
               $this->salida .= "      <td>$nombre_paciente</td>\n";
               
               //Permite visualizar la edad del paciente
               $edad = CalcularEdad($filaPacinte['fecha_nacimiento'],date("yyyy"-"mm"-"dd"));
               $this->salida .= "      <td align=\"center\">".$edad[anos]." Años</td>\n";
               //Permite visualizar la edad del paciente


              if ($permisoingresoactivo == 1){
                if ($estaincu == 1){
                   $this->salida .= "      <td align=\"center\"><label class=\"label_mark\">".$filaPacinte[marca_prioridad_atencion]."</lable></td>\n";
                   $this->salida .= "      <td>$imagenHC</td>\n";
                   $this->salida .= "      <td align=\"right\" bgcolor=\"$_color\">" . $this->GetDiasHospitalizacion($filaPacinte['fecha_ingreso']) . "</td>\n";
          
                   if($filaPacinte[paciente_cirugia] == 0)
                   {
                        //Signos Vitales
                        if($this->GetUserPermisos($datosEstacion['estacion_id'],'61'))
                        {
                          $SignoVital = "<img src=\"".GetThemePath()."/images/estetoscopio.png\" border=\"0\" title='Toma de Signos Vitales.'>";
                          $UrlSignos = ModuloGetURL('app','EE_ControlesPacientes','user','CallFrmsControlesPacientes',array('datosPaciente'=>$filaPacinte,'control'=>'SignoVital'));
                          $this->salida .= "      <td align=\"center\"><a href=\"$UrlSignos\">".$SignoVital." SV</a></td>\n";
                        }
                        else
                        {
                          $SignoVital = "<img src=\"".GetThemePath()."/images/estetoscopio.png\" border=\"0\" title='Toma de Signos Vitales.'>";
                          $this->salida .= "      <td align=\"center\">".$SignoVital." SV</td>\n";
                        }
                        //Administracion Medicamentos
                        $urla = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmMedicamentos',array('datosPaciente'=>$filaPacinte,"control_id"=>2,"control_descripcion"=>"CONTROL MEDICAMENTOS PACIENTE"));
                        $medicamento=$this->GetPacMedicamentosPorSolicitar($filaPacinte[ingreso]);
                        if($medicamento==1)
                        { $this->salida .= "      <td align=\"center\"><a href='$urla'><img src=\"". GetThemePath() ."/images/pparamedin.png\" border='0'>&nbsp;MP</a></td>\n"; }else
                        { $this->salida .= "      <td align=\"center\"><a href='$urla'><img src=\"". GetThemePath() ."/images/pparamed.png\" border='0'>&nbsp;MP</a></td>\n"; }
         
                        //Ordenes de Servicio
                        $conteo_os=$this->ConteoOrdenesPaciente($filaPacinte['ingreso'], $filaPacinte['paciente_id'], $filaPacinte['tipo_id_paciente']);
                        
                        if($conteo_os==1)
                        {
                             //AQUI ES PARA COMUNICARSE CON LA CENTRAL DE IMPRESION DE ORDENES.
                             $_SESSION['CENTRALHOSP']['RETORNO']['modulo']='EE_PanelEnfermeria';
                             $_SESSION['CENTRALHOSP']['RETORNO']['metodo']='FrmPanelEstacion';
                             $_SESSION['CENTRALHOSP']['RETORNO']['tipo']='user';
                             $_SESSION['CENTRALHOSP']['RETORNO']['contenedor']='app';
                             $_SESSION['CENTRALHOSP']['RETORNO']['argumentos']=array('estacion'=>$datosEstacion);
                             
                             $href=ModuloGetURL('app','CentralImpresionHospitalizacion','user','BuscarPorEstacion',array("estacion"=>$datosEstacion[estacion_id],
                             "paciente_id"=>$filaPacinte[paciente_id],"tipo_id_paciente"=>$filaPacinte[tipo_id_paciente],"nombre_estacion"=>$datosEstacion[estacion_descripcion],"ingreso"=>$filaPacinte[ingreso],"empresa_id"=>$datosEstacion[empresa_id]));
                             $this->salida .= "	<td align=\"center\"><a href=\"$href\"><img src=\"". GetThemePath() ."/images/pinactivo.png\" border='0'>&nbsp;OS</a></td>\n";
                        }
                        else
                        {
                             $this->salida .= "	<td align=\"center\"><img src=\"". GetThemePath() ."/images/editar.png\" border='0'>&nbsp;OS</td>\n";
                        }
                        $link_formatos = ModuloGetURL('app','ImpresionformatosHC','controller','FormatosImpresionEstacion',array("estacion"=>$datosEstacion[estacion_id],
                        "paciente_id"=>$filaPacinte[paciente_id],"tipo_id_paciente"=>$filaPacinte[tipo_id_paciente],"nombre_estacion"=>$datosEstacion[estacion_descripcion],"ingreso"=>$filaPacinte[ingreso],"empresa_id"=>$datosEstacion[empresa_id]));
                        $this->salida .= "	<td align=\"center\">\n";
                        $this->salida .= "    <a href=\"".$link_formatos."\" class=\"label_error\">\n";
                        $this->salida .= "      <img src=\"". GetThemePath() ."/images/imprimir.png\" border='0'>\n";
                        $this->salisa .= "    </a>\n";
                        $this->salida .= "  </td>\n";
                   }else{
                     $this->salida .= "<td colspan=\"4\" class=\"label_mark\" align=\"center\">EL PACIENTE SE ENCUENTRA EN CIRUGIA</td>\n";               
                   }

                    $this->salida.="  <td align=\"center\"><label class='label_mark'>";
                    $estudios = $this->GetEstudiosImagenologia($filaPacinte['ingreso']);
                    if (!empty($estudios)) {
                      $this->salida.="<a ><img align=\"center\" src=\"". GetThemePath() ."/images/radiology.png\" border='0' title='Estudio Radiologia'></a>";
                      foreach ($estudios AS $estudio) {
                        $ruta   = "http://".$estudio['url'].":".$estudio['web_port']."/oviyam/oviyam?studyUID=".$estudio[estudio_id];
                        $nombre = "ESTUDIOS";
                        $str    = "width=1024,height=768,resizable=no,location=no, status=no,scrollbars=yes";
                        $this->salida.="<a href=\"javascript:void(0);\" onclick=\"window.open('".$ruta."', '".$nombre."', '".$str."');\">ORDEN #".$estudio[admision]."</a>";		
                      }	    
                     
                    } else {
                      $this->salida.="<a ><img align=\"center\" src=\"". GetThemePath() ."/images/fallo.png\" border='0' title='Sin Estudio Radiologia'></a>";
                    }
                    $this->salida.="</label></td>";
                    //Profesionales en Atencion               
                    $infoAtenciones = $this->Profesionales_Atencion($filaPacinte[ingreso]);
                    $this->salida .= "       <td colspan=\"4\">".$infoAtenciones[nombre]."</td>\n";
                   
                }else{
                    $GetDatPac = $this->GetAtencion($filaPacinte['paciente_id']);
                    if (count($GetDatPac) > 0){
                        $nomdpto = $GetDatPac[0][dpto];
                    }
                    $this->salida .= "      <td colspan = 12 align=\"center\">El Paciente se encuentra en:  ".$nomdpto."</td>\n";
                }

//                  $this->salida .= "  </tr>\n";
                
                //ONCOLOGIA
                if (($filaPacinte[ingresosestado]  ==  '1') and ($filaPacinte[cuentaestado]  ==  '1' or $filaPacinte[cuentaestado]  ==  '2')){ 
                    $this->salida .= "      <td align=\"center\" bgcolor=\"$_color\"><input type = 'button' id = 'a".$filaPacinte['ingreso']."' value = 'Desactivar'  onclick = \" if(a".$filaPacinte['ingreso'].".value == 'Desactivar'){a".$filaPacinte['ingreso'].".value = 'Actualizar';btning.value = ".$filaPacinte['ingreso']."; btncue.value = ".$filaPacinte['numerodecuenta'].";btnesting.value = ".$filaPacinte['ingresosestado']."; btnestcue.value = ".$filaPacinte['cuentaestado']."; btnpacid.value = ".$filaPacinte['paciente_id']."; Redire(btning.value, btncue.value, btnesting.value, btnestcue.value, btnpacid.value, rutah.value);}else{window.location.href = rutah.value;};\"></td>\n";
                }else{
                  
                  if (($filaPacinte[ingresosestado]  ==  '0' or $filaPacinte[ingresosestado]  ==  '2') and ($filaPacinte[cuentaestado]  ==  '2')){ 
                      //DESHABILITAR BOTON SI EL ULTIMO INGRESO Y CUENTA ESTAN EN ESTADO 1
                      $GetRegPac = $this->GetRegIngresoPaciente($filaPacinte['paciente_id']);
                      $VanIna = 0;
                      if(count($GetRegPac) > 0){
                          if ($filaPacinte['ingreso']<$GetRegPac[0]['ingreso']){
                              $VanIna = 1;
                          }
                      }
                      if ($VanIna == 0){
                          //CHEK                  $this->salida .= "      <td align=\"center\" bgcolor=\"$_color\"><input type = 'checkbox' id = 'a".$filaPacinte['ingreso']."' onclick = \"UCheck(btning.value, 1);btning.value = ".$filaPacinte['ingreso']."; btncue.value = ".$filaPacinte['numerodecuenta'].";btnesting.value = ".$filaPacinte['ingresosestado']."; btnestcue.value = ".$filaPacinte['cuentaestado']."; btnpacid.value = ".$filaPacinte['paciente_id'].";\"></td>\n";
                          $this->salida .= "      <td align=\"center\" bgcolor=\"$_color\"><input type = 'button' id = 'a".$filaPacinte['ingreso']."' value = 'Activar'  onclick = \" if(a".$filaPacinte['ingreso'].".value == 'Activar'){a".$filaPacinte['ingreso'].".value = 'Actualizar';btning.value = ".$filaPacinte['ingreso']."; btncue.value = ".$filaPacinte['numerodecuenta'].";btnesting.value = ".$filaPacinte['ingresosestado']."; btnestcue.value = ".$filaPacinte['cuentaestado']."; btnpacid.value = ".$filaPacinte['paciente_id']."; Redire(btning.value, btncue.value, btnesting.value, btnestcue.value, btnpacid.value, rutah.value);}else{window.location.href = rutah.value;};\"></td>\n";
                      }else{
                          $this->salida .= "      <td align=\"center\" bgcolor=\"$_color\"><input type = 'button' id = 'a".$filaPacinte['ingreso']."' value = 'Activar'  disabled = true onclick = \"this.style.display='none';Redire(".$filaPacinte['ingreso'].", ".$filaPacinte['numerodecuenta'].", ".$filaPacinte[ingresosestado].", ".$filaPacinte[cuentaestado].", ".$filaPacinte[paciente_id].", rutah.value);\" ></td>\n";
                      }
                  }else{
                      $this->salida .= "     <td align=\"center\" bgcolor=\"$_color\"><input type = 'button' id = 'a".$filaPacinte['ingreso']."' value = 'Activar' disabled = true></td>\n";
                  }
                }         
                
              }else{
                 $this->salida .= "      <td align=\"center\"><label class=\"label_mark\">".$filaPacinte[marca_prioridad_atencion]."</lable></td>\n";
                 $this->salida .= "      <td>$imagenHC</td>\n";
                 $this->salida .= "      <td align=\"right\" bgcolor=\"$_color\">" . $this->GetDiasHospitalizacion($filaPacinte['fecha_ingreso']) . "</td>\n";
        
                 if($filaPacinte[paciente_cirugia] == 0)
                 {
                      //Signos Vitales
                      if($this->GetUserPermisos($datosEstacion['estacion_id'],'61'))
                      {
                        $SignoVital = "<img src=\"".GetThemePath()."/images/estetoscopio.png\" border=\"0\" title='Toma de Signos Vitales.'>";
                        $UrlSignos = ModuloGetURL('app','EE_ControlesPacientes','user','CallFrmsControlesPacientes',array('datosPaciente'=>$filaPacinte,'control'=>'SignoVital'));
                        $this->salida .= "      <td align=\"center\"><a href=\"$UrlSignos\">".$SignoVital." SV</a></td>\n";
                      }
                      else
                      {
                        $SignoVital = "<img src=\"".GetThemePath()."/images/estetoscopio.png\" border=\"0\" title='Toma de Signos Vitales.'>";
                        $this->salida .= "      <td align=\"center\">".$SignoVital." SV</td>\n";
                      }
                      //Administracion Medicamentos
                      $urla = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmMedicamentos',array('datosPaciente'=>$filaPacinte,"control_id"=>2,"control_descripcion"=>"CONTROL MEDICAMENTOS PACIENTE"));
                      $medicamento=$this->GetPacMedicamentosPorSolicitar($filaPacinte[ingreso]);
                      if($medicamento==1)
                      { $this->salida .= "      <td align=\"center\"><a href='$urla'><img src=\"". GetThemePath() ."/images/pparamedin.png\" border='0'>&nbsp;MP</a></td>\n"; }else
                      { $this->salida .= "      <td align=\"center\"><a href='$urla'><img src=\"". GetThemePath() ."/images/pparamed.png\" border='0'>&nbsp;MP</a></td>\n"; }
       
                      //Ordenes de Servicio
                      $conteo_os=$this->ConteoOrdenesPaciente($filaPacinte['ingreso'], $filaPacinte['paciente_id'], $filaPacinte['tipo_id_paciente']);
                      
                      if($conteo_os==1)
                      {
                           //AQUI ES PARA COMUNICARSE CON LA CENTRAL DE IMPRESION DE ORDENES.
                           $_SESSION['CENTRALHOSP']['RETORNO']['modulo']='EE_PanelEnfermeria';
                           $_SESSION['CENTRALHOSP']['RETORNO']['metodo']='FrmPanelEstacion';
                           $_SESSION['CENTRALHOSP']['RETORNO']['tipo']='user';
                           $_SESSION['CENTRALHOSP']['RETORNO']['contenedor']='app';
                           $_SESSION['CENTRALHOSP']['RETORNO']['argumentos']=array('estacion'=>$datosEstacion);
                           
                           $href=ModuloGetURL('app','CentralImpresionHospitalizacion','user','BuscarPorEstacion',array("estacion"=>$datosEstacion[estacion_id],
                           "paciente_id"=>$filaPacinte[paciente_id],"tipo_id_paciente"=>$filaPacinte[tipo_id_paciente],"nombre_estacion"=>$datosEstacion[estacion_descripcion],"ingreso"=>$filaPacinte[ingreso],"empresa_id"=>$datosEstacion[empresa_id]));
                           $this->salida .= "	<td align=\"center\"><a href=\"$href\"><img src=\"". GetThemePath() ."/images/pinactivo.png\" border='0'>&nbsp;OS</a></td>\n";
                      }
                      else
                      {
                           $this->salida .= "	<td align=\"center\"><img src=\"". GetThemePath() ."/images/editar.png\" border='0'>&nbsp;OS</td>\n";
                      }
                      $link_formatos = ModuloGetURL('app','ImpresionformatosHC','controller','FormatosImpresionEstacion',array("estacion"=>$datosEstacion[estacion_id],
                      "paciente_id"=>$filaPacinte[paciente_id],"tipo_id_paciente"=>$filaPacinte[tipo_id_paciente],"nombre_estacion"=>$datosEstacion[estacion_descripcion],"ingreso"=>$filaPacinte[ingreso],"empresa_id"=>$datosEstacion[empresa_id]));
                      $this->salida .= "	<td align=\"center\">\n";
                      $this->salida .= "    <a href=\"".$link_formatos."\" class=\"label_error\">\n";
                      $this->salida .= "      <img src=\"". GetThemePath() ."/images/imprimir.png\" border='0'>\n";
                      $this->salisa .= "    </a>\n";
                      $this->salida .= "  </td>\n";
                 }else{
                   $this->salida .= "<td colspan=\"4\" class=\"label_mark\" align=\"center\">EL PACIENTE SE ENCUENTRA EN CIRUGIA</td>\n";               
                 }

                  $this->salida.="  <td align=\"center\"><label class='label_mark'>";
                  $estudios = $this->GetEstudiosImagenologia($filaPacinte['ingreso']);
                  if (!empty($estudios)) {
                    $this->salida.="<a ><img align=\"center\" src=\"". GetThemePath() ."/images/radiology.png\" border='0' title='Estudio Radiologia'></a>";
                    foreach ($estudios AS $estudio) {
                      $ruta   = "http://".$estudio['url'].":".$estudio['web_port']."/oviyam/oviyam?studyUID=".$estudio[estudio_id];
                      $nombre = "ESTUDIOS";
                      $str    = "width=1024,height=768,resizable=no,location=no, status=no,scrollbars=yes";
                      $this->salida.="<a href=\"javascript:void(0);\" onclick=\"window.open('".$ruta."', '".$nombre."', '".$str."');\">ORDEN #".$estudio[admision]."</a>";		
                    }	    
                   
                  } else {
                    $this->salida.="<a ><img align=\"center\" src=\"". GetThemePath() ."/images/fallo.png\" border='0' title='Sin Estudio Radiologia'></a>";
                  }
                  $this->salida.="</label></td>";

                  //Profesionales en Atencion               
                  $infoAtenciones = $this->Profesionales_Atencion($filaPacinte[ingreso]);
                  $this->salida .= "       <td colspan=\"4\">".$infoAtenciones[nombre]."</td>\n";
                  $this->salida .= "  </tr>\n";
              }
              
              
          }
          $this->salida .= "  </table>\n";

          $this->salida .= "  </form>\n";
     }
     
     /*
     * Enlaces para los suministros por estacion.
     */
     function FrmFuncionalidades_x_Estacion($datos_estacion,$ruta,$SW)
     {
	     $datosBodega = $this->GetEstacionBodega($datos_estacion,1);
          
          $this->salida .= "<br><table align=\"center\" width=\"100%\" border=\"0\">\n";
		$this->salida .= "<tr><td width=\"40%\">";          
          
          //Inicio Tabla 1. Referente a las Solicitudes.          
          $this->salida .= "<table align=\"center\" width=\"100%\"  border=\"0\">\n";
          //Solicitudes de suministro por estacion.
          $sol_solicitud = "<a href=\"".ModuloGetURL('app','EE_Suministros_x_Estacion','user','FrmShowBodega',array("datos_estacion"=>$datos,'switche'=>'Solicitar_sol')) ."\" target=\"Contenido\">Realizar Solicitudes de Suministro x Estacion</a>";
          $img1 = "<img src=\"".GetThemePath()."/images/EstacionEnfermeria/info.png\" border=0 width=12 heigth=12>";
          $ConSolicitudes = $this->BusquedaSolicitudes_Estacion($datos_estacion);
          if($ConSolicitudes >= 1){
               $sw=1;
               $con_solicitud = "<a href=\"".ModuloGetURL('app','EE_Suministros_x_Estacion','user','FrmShowBodega',array("datos_estacion"=>$datos,'switche'=>'Confirmar_sol')) ."\" target=\"Contenido\">Confirmar Solicitudes de Suministro x Estacion</a>";
               $img2 = "<img src=\"".GetThemePath()."/images/EstacionEnfermeria/alarma.png\" border=0 width=12 heigth=12>";
          }else
          {
               $con_solicitud = "Confirmar Solicitudes de Suministro x Estacion";          
          }

          //Solicitud y confirmacion de Suministros x Estacion
          $this->salida .= "  <tr>\n";
          $this->salida .= "    <td class=\"modulo_table_title\" height='17'>Solicitud Suministros x Estación</td>\n";
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr>\n";
          $this->salida .= "    <td class=\"modulo_list_claro\" height='17'>$img1&nbsp;$sol_solicitud</td>\n";
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr>\n";
          $this->salida .= "    <td class=\"modulo_list_oscuro\" height='17'>$img2&nbsp;$con_solicitud</td>\n";
          $this->salida .= "  </tr>\n";
          //Fin solicitud y confirmacion de Suministros x Estacion          
          
		$PacientesConOrdenes = $this->GetPacientesConMedicamentosPorDesp();
          
          if($PacientesConOrdenes == "")
          {
               $enlacepend = "Confirmación de Despacho";
               $imgpend='';
               $sw=0;
          }
          elseif($PacientesConOrdenes == 1)
		{
			$enlacepend = "<a href=\"".ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','FrmShowBodega',array("datos_estacion"=>$datos_estacion,'switche'=>'despacho')) ."\" target=\"Contenido\">Confirmacion Despacho: Insumos y Medicamentos Pendientes</a>";
			$imgpend = "<img src=\"".GetThemePath()."/images/EstacionEnfermeria/alarma.png\" border=0 width=12 heigth=12>";
		}

		unset($PacientesConOrdenes);
          
          $PacientesConOrdenes = $this->GetPacientesConMedicamentosPorSolicitar();
          
		if($PacientesConOrdenes == ""){
			$enlace = "Listado Solicitudes Realizadas";
		}
		elseif($PacientesConOrdenes == 1){
			$sw=1;
			$enlace = "<a href=\"".ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','FrmShowBodega',array("datos_estacion"=>$datos_estacion,'switche'=>'recibir')) ."\" target=\"Contenido\">Listado Solicitudes Realizadas: Insumos y Medicamentos</a>";
			$img = "<img src=\"".GetThemePath()."/images/EstacionEnfermeria/alarma.png\" border=0 width=12 heigth=12>";
		}

          //Solicitud y confirmacion de Medicamentos e Insumos Paciente
          $this->salida .= "  <tr>\n";
          $this->salida .= "    <td class=\"modulo_table_title\" height=\"10\">Insumos Y Medicamentos</td>\n";
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr>\n";
          $this->salida .= "    <td class=\"modulo_list_claro\" height=\"10\">$img&nbsp;$enlace</td>\n";
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr>\n";
          $this->salida .= "    <td class=\"modulo_list_oscuro\" height=\"10\">$imgpend&nbsp;$enlacepend</td>\n";
          $this->salida .= "  </tr>\n";
          //Fin solicitud y confirmacion de Medicamentos e Insumos Paciente
           
     	//Devolucion de Medicamentos e Insumos Paciente
          unset($_SESSION['ESTACION']['VECTOR_DEV_INS']['BODEGA_ESTACION']);
          
          $conteoI = $this->GetDevolucion_IM_Pendientes('I');
          if($conteoI == 1)
          {
               $devo_i = "<a href=\"".ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmDevolucionInsumosExterno',array("datos_estacion"=>$datos_estacion,"bodega"=>$datosBodega))."\" target=\"Contenido\">Devolución Insumos</a>";
          }else{ $devo_i = "Devolución Insumos";}
          
          $this->salida .= "<tr>\n";
          $this->salida .= "<td height=\"10\" class=\"modulo_list_claro\">$img&nbsp;$devo_i</td>\n";
          $this->salida .= "</tr>\n";     
          
          $conteoM = $this->GetDevolucion_IM_Pendientes('M');
          if($conteoM == 1)
          {
               $devo_m = "<a href=\"".ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmDevolucionMedicamentosExterno',array("datos_estacion"=>$datos_estacion,"bodega"=>$datosBodega))."\" target=\"Contenido\">Devolución Medicamentos</a>";
          }else{ $devo_m = "Devolución Medicamentos";}
          
          $this->salida .= "  <tr>\n";
          $this->salida .= "		<td height=\"10\" class=\"modulo_list_oscuro\">$img&nbsp;$devo_m</td>\n";
          $this->salida .= "		</tr>\n";
     	//Devolucion de Medicamentos e Insumos Paciente
          $this->salida .= "</table>";
          //Fin Tabla 1. Referente a las Solicitudes.
		
          $this->salida .= "</td>";
          $this->salida .= "<td width=\"25%\">";          

          //Inicio Tabla 2. Referente a los Controles de Pacientes.
          $this->salida .= "<table align=\"center\" width=\"100%\" border=\"0\">\n";
          
          //Controles Pacientes (Dietas).
          $enlaceDietas = "<a href=\"".ModuloGetURL('app','EE_SolicitudDietas','user','FrmPanelEstacion',array("datos_estacion"=>$datos_estacion))."\" target=\"Contenido\">Dietas</a>";
          $imgDietas = "<img src=\"".GetThemePath()."/images/EstacionEnfermeria/recetaDietas.gif\" align='middle' border=0 width=12 heigth=12>";

          //Controles Pacientes (Liquidos).
          $enlaceliquidos = "<a href=\"".ModuloGetURL('app','EE_ControlesPacientes','user','CallFrmsControlesPacientes',array("datos_estacion"=>$datos_estacion,'control'=>'Liquidos','idControl'=>6))."\" target=\"Contenido\">Liquidos</a>";
          $imgliq = "<img src=\"".GetThemePath()."/images/editar.gif\" align='middle' border=0 width=12 heigth=12>";

          //Dietas Y Liquidos
          $this->salida .= "  <tr>\n";
          $this->salida .= "  <td class=\"modulo_table_title\" height='20'>Controles de Pacientes</td>\n";
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr>\n";
          $this->salida .= "    <td class=\"modulo_list_claro\" height='20'>$imgDietas&nbsp;$enlaceDietas</td>\n";
          $this->salida .= "  </tr>\n";
          //Enlace Liquidos
          $this->salida .= "  <tr>\n";
          if($SW == 'H')
          {
          	$this->salida .= "    <td class=\"modulo_list_oscuro\" height='20'>$imgliq&nbsp;$enlaceliquidos</td>\n";
          }
          else
          {
          	$this->salida .= "    <td class=\"modulo_list_oscuro\" height='20'>$imgliq&nbsp;Liquidos</td>\n";          
          }
          $this->salida .= "  </tr>\n";
          //Fin Dietas y Liquidos.

          //Resumen Epicrisis.
          $this->salida .= "  <tr>\n";
          $this->salida .= "    <td class=\"modulo_table_title\" height='20'>Impresion Y Cargue de Insumos</td>\n";
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr>\n";
          $imgepi = "<img src=\"".GetThemePath()."/images/imprimir.png\" align='middle' border=0 width=12 heigth=12>";
          $accionImpresion = ModuloGetURL('app','ResumenEpicrisis','user','FormaMenus',array("datos_estacion"=>$datos_estacion,"ubicacion"=>"estacion",'ruta'=>$ruta));
          $this->salida .= "    <td class=\"modulo_list_claro\" height='20'><a href=\"$accionImpresion\">$imgepi&nbsp;Epicrisis y Autorizaciones</a></td>\n";
          $this->salida .= "  </tr>\n";
          //Resumen Epicrisis.
					
          //Caragar Insumos a la Cuenta.
          $accionInsumos = ModuloGetURL('app','EE_Cargos','user','Call_AgregarInsumos',array("datos_estacion"=>$datos_estacion,"tipoa"=>2));
          $imgcar = "<img src=\"".GetThemePath()."/images/cargos.png\" align='middle' border=0 width=12 heigth=12>";
          $this->salida .= "  <tr>\n";
          $this->salida .= "    <td class=\"modulo_list_oscuro\" height='20'><a href=\"$accionInsumos\">$imgcar&nbsp;Cargar Insumos (Cuenta Paciente)</a></td>\n";
          $this->salida .= "  </tr>\n";
          //Caragar Insumos a la Cuenta.
          
          //Impresion de Ordenes Medicas.
          $accionImpresionOrdenes = ModuloGetURL('app','EE_PanelEnfermeria','user','FormaImpresionSolicitudes',array("datos_estacion"=>$datos_estacion,'ruta'=>$ruta));
          $imgepi = "<img src=\"".GetThemePath()."/images/imprimir.png\" align='middle' border=0 width=12 heigth=12>";
          $this->salida .= "  <tr>\n";
          $this->salida .= "    <td class=\"modulo_list_oscuro\" height='20'><a href=\"$accionImpresionOrdenes\">$imgepi&nbsp;Impresion Ordenes Medicas</a></td>\n";
          $this->salida .= "  </tr>\n";

          $this->salida .= "</table>";
          //Fin Tabla 2. Referente a los Controles de Pacientes.

          $this->salida .= "</td>";
          $this->salida .= "<td width=\"25%\">";          

          //Inicio Tabla 3. Referente a las Estadisticas de la EE.
          $this->salida .= "<table align=\"center\" width=\"100%\" border=\"0\">\n";
          
          $Estadisticas = $this->EstadisticasEE();
          //Estadisticas EE.
          $this->salida .= "  <tr>\n";
          $this->salida .= "    <td class=\"modulo_table_title\" height='20'>Estadisticas EE.</td>\n";
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr>\n";
          $this->salida .= "    <td class=\"modulo_list_claro\" height='20'>Reporte de Pacientes EE.</td>\n";
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr>\n";
          $this->salida .= "    <td class=\"modulo_list_oscuro\" height='20'><label class=\"label_mark\">Pacientes Hospitalizados EE. (".$Estadisticas['hospitalizados'].")</label></td>\n";
          $this->salida .= "  </tr>\n";
          //Division
          $this->salida .= "  <tr>\n";
          $this->salida .= "    <td class=\"modulo_list_claro\" height='20'><label class=\"label_mark\">Pacientes X Ingresar EE. (".$Estadisticas['p_x_ingresar'].")</label></td>\n";
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr>\n";
          $this->salida .= "    <td class=\"modulo_list_oscuro\" height='20'><label class=\"label_mark\">Pacientes en consulta EE. (".$Estadisticas['en_consulta'].")</label></td>\n";
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr>\n";
          $this->salida .= "    <td class=\"modulo_list_claro\" height='20'><label class=\"label_mark\">Pacientes X Egresar EE. (".$Estadisticas['p_x_egresar'].")</label></td>\n";
          $this->salida .= "  </tr>\n";
          //Fin Estadisticas EE.
          $this->salida .= "  <tr>\n";
          $this->salida .= "    <td class=\"modulo_list_oscuro\" height='20'>&nbsp;</td>\n";
          $this->salida .= "  </tr>\n";
          $this->salida .= "</table>";
          //Fin Tabla 3. Referente a las Estadisticas de la EE.

          $this->salida .= "</td></tr>";          
          $this->salida .= "</table>\n";
          return true;
     }

     /*
     * Vistas que pinta los datos de las solicitudes de medicamentos o insumos que fueron 
     * realizadas por los profesionales de enfermeria.
     */
     function IyM_PendientesUsuarios($datos_estacion)
     {
     	$Datos = $this->BuscarDatos_ResponsableIyM($datos_estacion);
          if(!empty($Datos))
          {
          	$_SESSION['SOLICITUD_RESPONSABLE'] = $Datos;
               $this->salida .= "<br><table class=\"modulo_table_list_title\" width=\"100%\">";
               $accionCancel = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmCancelacionProductos',array('datos_estacion'=>$datos_estacion));
               $this->salida .= "<form name=\"CancelPedido\" method=\"post\" action=\"$accionCancel\">";
               $this->salida .= "<tr class=\"modulo_table_title\">";
               $usr_Estacion = $this->TraerUsuario(UserGetUID());
               $this->salida .= "<td colspan=\"8\">SUMINISTROS Y MEDICAMENTOS PENDIENTES POR CARGAR A LOS PACIENTES SOLICITADOS POR EL USUARIO ".$usr_Estacion[nombre]."</td>";
               $this->salida .= "</tr>";
               foreach($Datos as $k => $datos_IyM)
               {
                    $this->salida .= "<tr class=\"modulo_list_claro\">";
                    $this->salida .= "<td>".$k."</td>";
				$this->salida .= "<td><table width=\"100%\">";
                    
                    $this->salida .= "<tr class=\"modulo_table_title\">";
                    $this->salida .= "<td>CODIGO</td>";
                    $this->salida .= "<td>DESCRIPCION</td>";
                    $this->salida .= "<td>CANTIDAD</td>";
                    $this->salida .= "<td>USUARIO BODEGA</td>";
                    $this->salida .= "<td>ESTACION</td>";
                    $this->salida .= "<td>BODEGA</td>";
                    $this->salida .= "<td>&nbsp;</td>";
                    $this->salida .= "</tr>";
                    for($i=0; $i<sizeof($datos_IyM); $i++)
                    {
                         if($i % 2)  $estilo = "class=modulo_list_oscuro";  else  $estilo = "class=modulo_list_oscuro";
                         $this->salida .= "<tr $estilo>";
                         $this->salida .= "<td align=\"center\">".$datos_IyM[$i][codigo_producto]."</td>";
                         $this->salida .= "<td align=\"justify\">".$datos_IyM[$i][descripcion]."</td>";
                         $this->salida .= "<td align=\"center\">".$datos_IyM[$i][cantidad]."</td>";
                         $usr_Bodega = $this->TraerUsuario($datos_IyM[$i][usuario_id]);
                         $this->salida .= "<td align=\"justify\">".$usr_Bodega[nombre]."</td>";
                         $nombre_EE = $this->TraerEstacion($datos_IyM[$i][estacion_id]);
                         $this->salida .= "<td align=\"justify\">".$nombre_EE[descripcion]."</td>";
                         $nombre_Bodega = $this->TraerBodega($datos_IyM[$i][bodega],$datos_estacion);
                         $this->salida .= "<td align=\"justify\">".$nombre_Bodega[descripcion]."</td>";
                         $this->salida .= "<td align=\"center\"><input type=\"checkbox\" name=\"Op[$i]\" value=\"".$datos_IyM[$i][codigo_producto]."\"></td>";
                         $this->salida .= "</tr>";
                    }
                    $this->salida .= "</table>";
                    $this->salida .= "</td>";
                    $this->salida .= "<td colspan=\"6\"><input type=\"submit\" name=\"Cancelar[$k]\" value=\"Devolver\"></td>";
                    $this->salida .= "</tr>";
               }
               $this->salida .= "</form>";
               $this->salida .= "<tr class=\"modulo_table_title\">";
               $AccionCuadrar = ModuloGetURL('app','EstacionEnfermeria_IYM_Usuarios','user','ConsultaMyIDespachosPendientes',array('estacion'=>$datos_estacion));
               $this->salida .= "<td colspan=\"8\"><a href=\"$AccionCuadrar\">Cuadrar Suministros</a></td>";
               $this->salida .= "</tr>";
               $this->salida .= "</table>";
          }
          return true;
     }
     
     /*
     * Vistas que pinta los datos de los productos que seran cancelados o devueltos
     * a las bodegas por parte de los responsables
     */
     function FrmCancelacionProductos()
     {
          $datos_estacion = $_REQUEST['datos_estacion'];
          $Datos = $_SESSION['SOLICITUD_RESPONSABLE'];
          $this->salida .= ThemeAbrirTabla("INSUMOS O MEDICAMENTOS A CANCELAR (DESPACHO A RESPONSABLES) - ".$datos_estacion[estacion_descripcion]."");
          $this->salida .= "<br><table class=\"modulo_table_list_title\" width=\"85%\" align=\"center\">";
          $accionCancelacion = ModuloGetURL('app','EE_PanelEnfermeria','user','CancelacionProductos',array('estacion_id'=>$datos_estacion['estacion_id'],'Op'=>$_REQUEST['Op']));
          $this->salida .= "<form name=\"CancelPedido\" method=\"post\" action=\"$accionCancelacion\">";
	     $this->salida .= "<tr class=\"modulo_table_title\">";
          $this->salida .= "<td colspan=\"3\">PRODUCTOS A CANCELAR</td>";
          $this->salida .= "</tr>";
          foreach($Datos as $k => $datos_IyM)
          {
               if($_REQUEST['Cancelar'][$k] == 'Devolver')
               {
                    $this->salida .= "<tr class=\"modulo_list_claro\">";
                    $this->salida .= "<td>".$k."</td>";
                    $this->salida .= "<td colspan=\"2\"><table width=\"100%\">";
                    
                    $this->salida .= "<tr class=\"modulo_table_title\">";
                    $this->salida .= "<td>CODIGO</td>";
                    $this->salida .= "<td>DESCRIPCION</td>";
                    $this->salida .= "<td>CANTIDAD</td>";
                    $this->salida .= "<td>USUARIO BODEGA</td>";
                    $this->salida .= "<td>ESTACION</td>";
                    $this->salida .= "<td>BODEGA</td>";
                    $this->salida .= "</tr>";
                    
                    if(sizeof($datos_IyM) == sizeof($_REQUEST['Op']))
                    { $tipo = true; }
                    
                    for($i=0; $i<sizeof($datos_IyM); $i++)
                    {
                         if($i % 2)  $estilo = "class=modulo_list_oscuro";  else  $estilo = "class=modulo_list_oscuro";
                         if($_REQUEST['Op'])
                         {
                              if($_REQUEST['Op'][$i] == $datos_IyM[$i][codigo_producto])
                              {
                                   $this->salida .= "<tr $estilo>";
                                   $this->salida .= "<td align=\"center\">".$datos_IyM[$i][codigo_producto]."</td>";
                                   $this->salida .= "<td align=\"justify\">".$datos_IyM[$i][descripcion]."</td>";
                                   $this->salida .= "<td align=\"center\">".$datos_IyM[$i][cantidad]."</td>";
                                   $usr_Bodega = $this->TraerUsuario($datos_IyM[$i][usuario_id]);
                                   $this->salida .= "<td align=\"justify\">".$usr_Bodega[nombre]."</td>";
                                   $nombre_EE = $this->TraerEstacion($datos_IyM[$i][estacion_id]);
                                   $this->salida .= "<td align=\"justify\">".$nombre_EE[descripcion]."</td>";
                                   $nombre_Bodega = $this->TraerBodega($datos_IyM[$i][bodega],$datos_estacion);
                                   $this->salida .= "<td align=\"justify\">".$nombre_Bodega[descripcion]."</td>";
                                   $this->salida .= "</tr>";
                              }
                         }
                         else
                         {
                              $this->salida .= "<tr $estilo>";
                              $this->salida .= "<td align=\"center\">".$datos_IyM[$i][codigo_producto]."</td>";
                              $this->salida .= "<td align=\"justify\">".$datos_IyM[$i][descripcion]."</td>";
                              $this->salida .= "<td align=\"center\">".$datos_IyM[$i][cantidad]."</td>";
                              $usr_Bodega = $this->TraerUsuario($datos_IyM[$i][usuario_id]);
                              $this->salida .= "<td align=\"justify\">".$usr_Bodega[nombre]."</td>";
                              $nombre_EE = $this->TraerEstacion($datos_IyM[$i][estacion_id]);
                              $this->salida .= "<td align=\"justify\">".$nombre_EE[descripcion]."</td>";
                              $nombre_Bodega = $this->TraerBodega($datos_IyM[$i][bodega],$datos_estacion);
                              $this->salida .= "<td align=\"justify\">".$nombre_Bodega[descripcion]."</td>";
                              $this->salida .= "</tr>";
                              $tipo = true;
                         }
                         $this->salida.="<input type=\"hidden\" name=\"Bodega\" value=\"".$datos_IyM[0][bodega]."\">";
                    }
                    $this->salida .= "</table>";
                    $this->salida .= "</td>";
                    $this->salida .= "</tr>";
	               $this->salida.="<input type=\"hidden\" name=\"Solicitud\" value=\"".$k."\">";
               }
          }
          if($tipo == true)
          {
          	$this->salida.="<input type=\"hidden\" name=\"Cancelacion\" value=\"1\">";
          }
          $this->salida.="<tr class=\"modulo_list_claro\">";
          $this->salida .= "<td align=\"center\" width=\"35%\">JUSTIFICACION :</td>";
          $this->salida .= "<td align=\"center\" colspan=\"2\"><TEXTAREA name=\"obs\" cols=\"90\" rows=\"8\">".$_REQUEST['obs']."</TEXTAREA></td>";
          $this->salida.="</tr>";
          $this->salida.="<tr class=\"modulo_list_claro\">";
          $this->salida .= "<td align=\"center\" colspan=\"3\"><input type=\"submit\" name=\"cancelar\" value=\"Devolver\"></td>";
          $this->salida.="</tr>";
          $this->salida.="</form>";
          $this->salida .= "</table>";
          $href = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
          $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>VOLVER</a><br>";
          $this->salida .= themeCerrarTabla();
          return true;
     }
     
     
     function FormaImpresionSolicitudes()
     {
          //Validar si el usuario esta logueado y si tiene permisos.
          if(!$this->GetUserPermisos($datosEstacion['estacion_id'],'58'))
          {
               $url= ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
               $titulo='VALIDACION DE PERMISOS';
               $mensaje='El usuario no tiene permiso para : Impresión de Solicitudes y Ordenes Médicas [58]';
               $this->frmMSG($url, $titulo, $mensaje);
               return true;
          }

          
          $datos_estacion = $_REQUEST['datos_estacion'];
          
          //Variable de Impresion
          unset($_SESSION['EE_ESTACION']);
          
          if($_REQUEST['datos'])
          	$_REQUEST['ruta'] = $_REQUEST['datos'];
          
          if($_REQUEST['ruta'] == '')
               $listadoPacientes2 = $_SESSION['EE_PanelEnfermeria']['listadoPacientes'];
          else
          	$listadoPacientes2 = $_SESSION['EE_PanelEnfermeria']['listadoPacientes_Urgencias'];
          	
          $this->FrmDatosEstacion($datos_estacion, '80%');
          
          if($listadoPacientes2)
          {
               $this->IncludeJS('javascripts/funciones.js', $contenedor='app', $modulo='EE_PanelEnfermeria');
          
               $this->salida .= "<br>\n";
               $this->salida .= "<table align=\"center\" width=\"80%\"  border=\"0\" >\n";
               $this->salida .= "  <tr class=\"modulo_table_title\">\n";
               $this->salida .= "      <td colspan='4' height='30'>PACIENTES EN CONSULTA DE URGENCIAS</td>\n";
               $this->salida .= "  </tr>\n";
               $this->salida .= "  <tr class=\"modulo_table_title\">\n";
               $this->salida .= "      <td width=\"15\">&nbsp;</td>\n";
               $this->salida .= "      <td align=\"center\">NOMBRE DEL PACIENTE</td>\n";
               $this->salida .= "      <td align=\"center\">TIEMPO HOSP.</td>\n";
               $this->salida .= "      <td align=\"center\">IMPRIMIR SOLICITUDES</td>\n";
               $this->salida .= "  </tr>\n";
          
               $backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
               
               foreach($listadoPacientes2 as $k2 => $filaPacinte2)
               {
                    if($p++ % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
                    $url_info_paciente = ModuloGetURL('app','EE_PanelEnfermeria','user','CallMostrarDatosIngreso',array('ingreso'=>$filaPacinte2['ingreso'],'retorno'=>'FormaImpresionSolicitudes','datos_estacion'=>$datos_estacion,'modulito'=>'EE_PanelEnfermeria', 'datos'=>$_REQUEST['ruta']));
                    $nombre_paciente = "<a href='$url_info_paciente'>$filaPacinte2[nombre_completo]</a>";
                    $this->salida .= "<tr class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>\n";
                    
                    if($_REQUEST['ruta'] == '')
					$imagenPaciente = "<img src=\"".GetThemePath()."/images/hospitalizacion.png\" border=0 title='Paciente Hospitalizado.'>";
                    else
                    	$imagenPaciente = "<img src=\"".GetThemePath()."/images/consulta_ur.png\" border=0 title='Ingresar y asignar cama al paciente.'>";
                    
                    $this->salida .= "      <td>$imagenPaciente</td>\n";
                    $this->salida .= "      <td>$nombre_paciente</td>\n";
                    $this->salida .= "      <td align=\"right\">" . $this->GetDiasHospitalizacion($filaPacinte2['fecha_ingreso']) . "</td>\n";
                    
                    $BuscarSolicitudes= ModuloGetURL('app','EE_PanelEnfermeria','user','LlamarImpresionSolicitudes',array('tipoid'=>$filaPacinte2[tipo_id_paciente], 'paciente'=>$filaPacinte2[paciente_id], 'evolucion'=>$filaPacinte2[evolucion_id], 'ingreso'=>$filaPacinte2[ingreso], 'nombre'=>$filaPacinte2[nombre_completo], 'ruta'=>$_REQUEST['ruta'], 'datos_estacion'=>$datos_estacion));
                    $this->salida .= "<td align=\"center\"><a href=\"$BuscarSolicitudes\"><img src=\"". GetThemePath() ."/images/imprimir.png\" border='0'></a></td>\n";
                    $this->salida .= "  </tr>\n";
               }
               $this->salida .= "  </table>\n";
          }
          else
          {
          	$this->salida .= "<br><div align=\"center\" class=\"label_mark\">LA ESTACION NO CUENTA CON PACIENTES EN CONSULTA DE URGENCIAS</div>";
          }
          
          $href = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
          $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>VOLVER</a><br>";
          $this->salida .= themeCerrarTabla();
          
          return true;
     }
     
     /**
     * Forma para mostrar los datos de ingreso del paciente.
     *
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function FrmPacientePendiente_Egreso($datos_estacion,$datosPaciente,$conducta,$estado)
     {
          if(empty($datos_estacion))
          {
               $datos_estacion = $_REQUEST['datos_estacion'];
               $datosPaciente = $_REQUEST['datosPaciente'];
               $conducta = $_REQUEST['conducta'];
               $estado = $_REQUEST['estado'];
          }
          
          // Obtener datos de Egreso del paciente          
		if($datosPaciente === "ShowMensaje")
		{
			$mensaje = "NO HAY PACIENTES PENDIENTES POR EGRESAR DE LA ESTACION '".$datos_estacion[descripcion5]."'";
			$titulo = "MENSAJE";
			$url = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
			$link = "PANEL ENFERMERIA";
			$this->FrmMSG($url,$titulo,$mensaje,$link);
			return true;
		}

		if(is_array($datosPaciente))
		{
			$this->salida .= ThemeAbrirTabla('LISTADO DE PACIENTES POR EGRESAR - [ '.$datos_estacion[estacion_descripcion].' ]')."<BR>";
			$this->salida .= "<table align='center' width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=modulo_table_list>\n";
			$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "		<td colspan=\"6\">PACIENTES POR EGRESAR</td>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "		<td width=\"40%\" colspan=\"2\">PACIENTE</td>\n";
			$this->salida .= "		<td width=\"30%\">TIPO EGRESO</td>\n";
			$this->salida .= "		<td width=\"10%\">INGRESO</td>\n";
			$this->salida .= "		<td width=\"10%\">EVOLUCION</td>\n";
			$this->salida .= "		<td width=\"25%\">RESUMEN HC</td>\n";
			$this->salida .= "	</tr>\n";
			$i=0;
			$reporte= new GetReports();
               if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
               $this->salida .= "<tr class=\"$estilo\">\n";
               $this->salida .= "<td nowrap width=\"5%\">\n";
               $this->salida .= "	<img src='".GetThemePath()."/images/atencion_citas.png' width=18 height=18 align='middle' border=0>\n";
               $this->salida .= "	</td>\n";
               $this->salida .= "	<td nowrap width=\"35%\" align=\"center\">".$datosPaciente['nombre_completo']."<br><label class='label_mark'>".$datosPaciente['tipo_id_paciente']." ".$datosPaciente['paciente_id']."</label></td>\n";
               $this->salida .= "	<td align=\"center\">".$conducta['descripcion']."</td>\n";
               $this->salida .= "	<td align=\"center\">".$datosPaciente['ingreso']."</td>\n";
               $this->salida .= "	<td align=\"center\">".$conducta['evolucion_id']."</td>\n";
               
               /*************************************************************************************
               	FUNCIONES DE RESTRICCION PARA SALIDA DE PACIENTES
               *************************************************************************************/
               $conteo_evolucion = $this->BuscarEvolucion_Pac($datosPaciente['ingreso']); //revisa si tiene evoluciones abiertas.
               $ConteoMedicamentos = $this->GetInformacionMedicamentos_BodegaPaciente($datosPaciente['ingreso'], 'M'); //revisa si tiene Medicamentos por Cuadrar.
               $ConteoSuministros = $this->GetInformacionSuministros_BodegaPaciente($datosPaciente['ingreso'], 'I'); //revisa si tiene Suministros por Cuadrar.
               $conteo_devolucion = $this->GetInformacionDevolucion_BodegaPaciente($datosPaciente['ingreso']);
               /*************************************************************************************
               	FUNCIONES DE RESTRICCION PARA SALIDA DE PACIENTES
               *************************************************************************************/

               $this->salida .= "<td align=\"center\">\n";
               $this->salida.=$reporte->GetJavaReport_HC($datosPaciente['ingreso'],array());
               $funcion=$reporte->GetJavaFunction();
               $this->salida .= "	<a href=\"javascript:$funcion\">RESUMEN HC</a>\n";
               $this->salida .= "	</td>\n";
               $this->salida .= "</tr>\n";
               $i++;

			if(($conteo_evolucion < 1) AND ($conteo_devolucion < 1) AND ($ConteoMedicamentos < 1) AND ($ConteoSuministros < 1))
			{
                    $vistos_ok = $this->BusquedaVistos_ok_salida($conducta);

                    if(empty($vistos_ok['01']['ingreso']))
                    {
	                    $linkvisto = ModuloGetURL('app','EE_PanelEnfermeria','user','Insertar_Vistobueno',array("datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,'conducta'=>$conducta,'estado'=>$estado));
                         $this->salida .= "<form name=forma method=\"POST\" action=$linkvisto>";
                         $this->salida .= "<tr>\n";
                         //$this->salida.="<pre>".print_r($datos_estacion,true)."</pre>";
                         $this->salida .= "<td align=\"center\" class='$estilo' colspan=\"6\"><input  class='input-submit' type=submit name='visto_bueno' value='VISTO BUENO'>\n";
                         $this->salida .= "</td>\n";
                         $this->salida .= "</tr>\n";
                         $this->salida .= "</form>\n";

                    }
                    else
                    {
                         $ok=true;
                         foreach($vistos_ok as $k=>$v)
                         {
                              if(empty($v['ingreso'])) $ok=false;
                         }
                         
                         if($ok)
                         {
						$conteo_cuentas = $this->GetInfoCuentasActivas($datosPaciente['ingreso']); //revisa si tiene cuentas abiertas.                              
                              
                              if($conteo_cuentas == '1')
                              {
                                   $this->salida .= "<tr>\n";
                                   $this->salida .= "<td align=\"center\" class='$estilo' colspan=\"6\"><label class='label_error'>EL PACIENTE TIENE CUENTAS ACTIVAS.<br>FALTA EL VISTO BUENO DE FACTURACION!!!!.</label>\n";
                                   $this->salida .= "</td>\n";
                                   $this->salida .= "</tr>\n";
                                   
                                   $sitienepermiso=$this->BuscarUsuarioParaElimVistoOk($datos_estacion[estacion_id]);
                                   //$this->salida.="<pre>".print_r($sitienepermiso,true)."</pre>";
                                   if($sitienepermiso>0)
                                   {
                                      $Eliminarvisto = ModuloGetURL('app','EE_PanelEnfermeria','user','Eliminar_Vistobueno',array("datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,'conducta'=>$conducta,'estado'=>$estado));
                                      $this->salida .= "<form name=forma method=\"POST\" action=$Eliminarvisto>";
                                      $this->salida .= "<tr>\n";
                                      $this->salida .= "<td align=\"center\" class='$estilo' colspan=\"6\"><input  class='input-submit' type=submit name='eliminar_visto_bueno' value='ELIMINAR VISTO BUENO'>\n";
                                      $this->salida .= "</td>\n";
                                      $this->salida .= "</tr>\n";
                                      $this->salida .= "</form>\n";
                                    }
                              }
                              else
                              {
                                   $linksalida = ModuloGetURL('app','EE_PanelEnfermeria','user','DarSalida',array("cama"=>$datosPaciente['cama'],"ingreso"=>$datosPaciente['ingreso'],"tipo_id"=>$datosPaciente['tipo_id_paciente'],"pac"=>$datosPaciente['paciente_id'],"datos_estacion"=>$datos_estacion,'estado'=>$estado,'conducta'=>$conducta));
                                   $this->salida .= "<form name=forma method=\"POST\" action=$linksalida>";
                                   $this->salida .= "<tr>\n";
                                   $this->salida .= "<td align=\"center\" class='$estilo' colspan=\"6\"><input  class='input-submit' type=submit name='visto_bueno' value='SALIDA DEL PACIENTE'>\n";
                                   $this->salida .= "</td>\n";
                                   $this->salida .= "</tr>\n";
                                   $this->salida .= "</form>\n";
                              }
                         }                   
                    }
                    $linknota = ModuloGetURL('app','EE_PanelEnfermeria','user','Insertar_Nota_Enfermeria',array("datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,'conducta'=>$conducta,'estado'=>$estado));
                    $this->salida .= "<form name=forma method=\"POST\" action=$linknota>	<tr><td class='$estilo' colspan=2 align='center'><input  type=hidden name='ingreso' value=".$value['ingreso']."><input  class='input-submit' type=submit name='enviar' value='Guardar Información'></td><td class='$estilo' colspan='4'><sub><b>NOTA FINAL</b></sub><BR><textarea name='obs'  cols=70 rows=5>".$_REQUEST['obs']."</textarea>&nbsp;&nbsp;&nbsp;&nbsp;\n";
                    $this->salida .= "	</td></tr>";
                    
                    $this->salida .= "</form>\n";
			}
			
               if($conteo_evolucion >= 1)
			{
                    $this->salida .= "<tr align='center'><td class='$estilo' colspan='6'><label class='label_mark'>NO SE PUEDE SACAR EL PACIENTE DEBIDO A QUE TIENE EVOLUCIONES ABIERTAS !</label>\n";
                    $this->salida .= "	</td></tr>\n";
			}
               
               if($conteo_devolucion >= 1)
               {
                    $this->salida .= "<tr align='center'><td class='$estilo' colspan=1 align='center'><img src='".GetThemePath()."/images/pparacarin.png' width=18 height=18 align='middle' border=0></td>";
                    $this->salida .= "<td class='$estilo' colspan='5'><label class='label_mark'>SE DEBE DESPACHAR LAS SOLICITUDES DE DEVOLUCION PENDIENTES EN BODEGA PARA PODER DAR DE ALTA AL PACIENTE !</label>\n";
                    $this->salida .= "</td></tr>\n";
               }

               if($ConteoMedicamentos == '1' OR $ConteoSuministros == '1')
               {
                    if($ConteoSuministros == '1' AND $ConteoMedicamentos == '1')
                    {
                         $MEDICAMENTOS = "EL PACIENTE PRESENTA INSUMOS Y MEDICAMENTOS PENDIENTES POR DESPACHAR, CONFIRMAR O SUMINISTRAR";
                    }elseif($ConteoSuministros == '1')
                    {
                         $MEDICAMENTOS = "EL PACIENTE PRESENTA INSUMOS PENDIENTES POR DESPACHAR O CONFIRMAR";
                    }elseif($ConteoMedicamentos == '1')
                    {
					$MEDICAMENTOS = "EL PACIENTE PRESENTA MEDICAMENTOS PENDIENTES POR DESPACHAR, CONFIRMAR O SUMINISTRAR";                         
                    }
                         
                    $this->salida .= "	<tr align='center'>\n";
                    $this->salida .= "	<td class=\"$estilo\" colspan=\"1\" align=\"center\"><img src='".GetThemePath()."/images/pparacarin.png' width=18 height=18 align='middle' border=0></td>\n";
                    $this->salida .= "	<td colspan=\"5\" class=\"$estilo\"><label class='label_mark'>$MEDICAMENTOS</label></td>\n";
                    $this->salida .= "	</tr>\n";
                    $this->salida .= "	<tr align='center'>\n";
                    $this->salida .= "	<td colspan=\"6\" class=\"$estilo\"><label class=\"label_error\">POR FAVOR, CANCELE, CONFIRME O SUMINISTRE EL PEDIDO !!!.</label></td>\n";
                    $this->salida .= "	</tr>\n";
               }

			$this->salida .= "</table><br>\n";
			
               if($conteo_evolucion >= 1)
			{
				$datos=$this->BuscarEvolucion_Pac($datosPaciente['ingreso'],1);//revisemos si tiene evoluciones abiertas.
				$this->salida.="<table  align=\"center\" border=\"0\" width=\"90%\">";
				$this->salida.="<tr class=\"modulo_table_list_title \">";
				$this->salida.="  <td colspan=\"5\">INFORMACION DE EVOLUCIONES ABIERTAS</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"modulo_table_list_title \">";
				$this->salida.="  <td>No.EVOLUCION</td>";
				$this->salida.="  <td>ESPECIALIDAD</td>";
				$this->salida.="  <td>PROFESIONAL</td>";
				$this->salida.="  <td>FECHA</td>";
                    $this->salida.="  <td>CERRAR</td>";
				$this->salida.="</tr>";
				for($i=0;$i<sizeof($datos);$i++)
				{
                         $rcaja=$datos[$i][recibo_caja];
                         $empresa=$datos[$i][empresa_id];
                         $centro=$datos[$i][centro_utilidad];
                         $fech=$datos[$i][fecha_registro];
								
                         if( $i % 2){ $estilo='modulo_list_claro';}
                         else {$estilo='modulo_list_oscuro';}
                         $this->salida.="<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB'); align=\"center\">";
                         $this->salida.="<td>".$datos[$i][evolucion_id]."</td>";
                         $this->salida.="  <td>".$datos[$i][descripcion]."</td>";
                         $this->salida.="  <td>".$datos[$i][nombre]."</td>";
                         $this->salida.="  <td>".$this->FormateoFechaLocal($datos[$i][fecha])."</td>";
                         $AccionCerrar = ModuloGetURL('app','EE_PanelEnfermeria','user','CerrarEvolucionesAbiertas', array('evolucion'=>$datos[$i][evolucion_id], "datos_estacion"=>$datos_estacion, "datosPaciente"=>$datosPaciente, 'conducta'=>$conducta, 'estado'=>$estado));;
                         $this->salida.="  <td><a href=\"$AccionCerrar\"><b>Cerrar<b></a></td>";
                         $this->salida.="</tr>";
				}
				$this->salida.="</table>";
			}
			$href = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
			$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>VOLVER</a><br>";
			$this->salida .= themeCerrarTabla();
		}//pacientes por egresar
		return true;
     }

     /**
     * Forma para mostrar la cabecera de la Estacion de Enfermeria
     *
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function FrmDatosEstacion($datos, $tamaño)
     {
               $this->salida .= ThemeAbrirTabla("ESTACION DE ENFERMERIA : ".$datos['estacion_descripcion']);
               $this->salida .= "<center>\n";
               
               if(!$tamaño)
               	$tamaño = '100%';
                    
               $this->salida .= "    <table class='modulo_table_title' border='0' width=\"$tamaño\">\n";
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
     function FrmPieDePaginaPanelEstacion()
     {
          $refresh = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
          $href    = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmLogueoEstacion');
     
          $this->salida .= "<table align=\"center\">";
          $this->salida .= "<tr><td>";
          $this->salida .= "<center>\n";
          $this->salida .= "  <div class='normal_10' align='center'><br>\n";
          $this->salida .= "    <a href='$href'>Seleccionar Estación</a>\n";
          $this->salida .= "    &nbsp;&nbsp;-&nbsp;&nbsp;\n";
          $this->salida .= "    <a href='$refresh'>Refrescar</a><br>\n";
          $this->salida .= "  </div>\n";
          $this->salida .= "</center>\n";
          $this->salida .= "</td></tr>";          
          $this->salida .= "</table>";
          $this->salida .= themeCerrarTabla();
          return true;
     }
     
     /**
     * Forma para mostrar los datos de ingreso del paciente.
     *
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function MostrarDatosIngreso($ingresoID,$retorno,$datos_estacion,$modulo='',$datos)
     {
     
     	if (empty($ingresoID))
          	$ingresoID = $_REQUEST['ingreso'];
               
          if(!$ingresoID)
          {
               $url     = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
               $titulo  = "DATOS DEL PACIENTE";
               $mensaje = "Error : El metodo ";
     
               $this->FrmMSG($url, $titulo, $mensaje, $url_titulo);
     
               return true;
          }
     
          if(!$datosPaciente = $this->GetDatosPaciente($ingresoID))
          {
     
               $url     = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
               $titulo  = "DATOS DEL PACIENTE";
               $mensaje = "Error : El metodo ";
     
               $this->FrmMSG($url, $titulo, $mensaje, $url_titulo);
     
               return true;
          }
     
          $ContactosPaciente = $this->GetContactosPaciente($ingresoID);
     
          $this->salida .= ThemeAbrirTabla('INFORMACION DEL PACIENTE','60%');
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
          $this->salida .= "  <tr class=\"modulo_list_oscuro\">\n";
          $this->salida .= "      <td class=\"label\" nowrap=\"yes\">OBSERVACIONES</td><td class=\"modulo_list_claro\" nowrap=\"yes\">".$datosPaciente['observaciones_pacien']."</td>\n";
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
                    $this->salida .= "          <br> DIRECCION: ".$ContactosPaciente[$i][direccion]."\n";
               }
               if($i>0){
                    $this->salida .= "      <br>";
               }
               $this->salida .= "      </td><td>&nbsp;</td>\n";
               $this->salida .= "  </tr>\n";
          }
     
          $this->salida .= "<tr><td align=\"center\" colspan=\"2\">&nbsp;</td></tr>\n";
     	
          if($modulo)
          {
               $link = ModuloGetURL('app',$modulo,'user',$retorno,array("datos_estacion"=>$datos_estacion,"estacion"=>$datos_estacion,"datos"=>$datos));
               $this->salida .= "<tr><td align=\"center\" colspan=\"2\"><a href=\"$link\"><b><sub>REGRESAR</sub></b></a></td></tr>\n";
          }else
          {
               $link = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
               $this->salida .= "<tr><td align=\"center\" colspan=\"2\"><a href=\"$link\"><b><sub>REGRESAR</sub></b></a></td></tr>\n";
          }
          
          $this->salida .= "</table><br>\n";
          $this->salida .= themeCerrarTabla();
          return true;
     }// fin MostrarDatosIngreso
     
}//fin de la clase

?>