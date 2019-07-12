<?php

/**
* $Id: app_EE_Admin_userclasses_HTML.php,v 1.3 2006/01/30 16:08:05 tizziano Exp $
* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
* @author  Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
* @package IPSOFT-SIIS
*/

class app_EE_Admin_userclasses_HTML extends app_EE_Admin_user
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
     function app_EE_Admin_userclasses_HTML()
     {
          $this->app_EE_Admin_user();
          $this->titulo='ADMINISTRACION DE ESTACIONES DE ENFERMERIA';
          $this->salida='';
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
     function FrmLogueoEstacion()
     {
          $UserPermisos = $this->GetUserPermisos();
          if (empty($UserPermisos)){
               $url= ModuloGetURL('app','EE_Admin','user','main');
               $this->frmUsuarioNoTienePermiso($url);
               return true;
          }
     
          $mtz[0]="EMPRESA";
          $mtz[1]="CENTRO UTILIDAD";
          $mtz[2]="UNIDAD FUNCIONAL";
          $mtz[3]="DEPARTAMENTO";
          $mtz[4]="ESTACION";
     
          $url[0]='app';
          $url[1]='EE_Admin';
          $url[2]='user';
          $url[3]='InicioSeleccionEstacion';
          $url[4]='estacion_id';
     
          foreach($UserPermisos as $k=>$v)
          {
               $estaciones[$v['empresa_descripcion']][$v['centro_utilidad_descripcion']][$v['unidad_funcional_descripcion']][$v['departamento_descripcion']][$v['estacion_descripcion']] = $v['estacion_id'];
          }
     
          $this->salida .= gui_theme_menu_acceso("SELECCION DE ESTACION DE ENFERMERIA",$mtz,$estaciones,$url);
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
     function frmUsuarioNoTienePermiso($url='',$titulo='', $mensaje='')
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
               $this->salida.="            <a href='$url'>VOLVER</a>\n";
               $this->salida.="        </td>\n";
               $this->salida.="    </tr>\n";
               $this->salida.="  </table>\n";
     
          }
          $this->salida .= "<br><br></div>";
          $this->salida .= themeCerrarTabla();
          return true;
     }
     
     function FrmUsuariosEstacion()
     {
          //VALIDACION DE PERMISOS
          if(empty($_SESSION['EE_Admin'][UserGetUID()]))
          {
               $url= ModuloGetURL('app','EE_Admin','user','main');
               $titulo = "VALIDACION DE PERMISOS";
               $this->frmUsuarioNoTienePermiso($url,$titulo);
               return true;
          }
     
          $this->salida  = themeAbrirTabla('ESTACION DE ENFERMERIA : '.$_SESSION['EE_Admin'][UserGetUID()]['estacion_descripcion']);
     
          if(!IncludeClass('ClaseHTML'))
          {
               $this->error = "EE_Admin - User HTML 01";
               $this->mensajeDeError = "No se pudo incluir la clase - ClaseHTML";
               return false;
          }
     
          if(empty($_REQUEST['offset']))
          {
               $paso = 1;
          }
          else
          {
               $paso = $_REQUEST['offset'];
          }
          
          if($_REQUEST['accion_BUSCAR'] == true)
          {
               $TRUE = true;
          }

          $accion=ModuloGetURL('app','EE_Admin','user','FrmUsuariosEstacion',array('accion_BUSCAR'=>$TRUE));
          $this->salida.="<form name=\"formalistarr\" action=\"$accion\" method=\"post\">";
          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
          $this->salida.="<tr class=\"modulo_table_list_title\">";
          $this->salida.="  <td align=\"center\" colspan=\"5\">BUSCADOR AVANZADO USUARIOS </td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
          $this->salida.="<td width=\"5%\">TIPO</td>";

          $this->salida.="<td width=\"10%\" align = left >";
          $this->salida.="<select size = 1 name = 'criterio'  class =\"select\">";
          $this->salida.="<option value = '1'>Id</option>";
          $this->salida.="<option value = '2' selected>Login</option>";
          $this->salida.="<option value = '3'>Nombre Usuario</option>";
          $this->salida.="</select>";
          $this->salida.="</td>";
          $this->salida.="<td width=\"10%\">DESCRIPCIÓN:</td>";
          $this->salida .="<td width=\"25%\" align='center'><input type='text' class='input-text' 	name = 'busqueda'  size=\"40\" maxlength=\"40\"  value =\"$buscar\"></td>" ;

          $this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name= 'buscar' type=\"submit\" value=\"BUSCAR\"></td>";
          $this->salida.="</tr>";
          $this->salida.="</form>";
          $this->salida.="<tr class=\"modulo_table_list_title\">";
          if($_REQUEST['busqueda'])
          {
               $cadena="El Buscador Avanzado: realizó la  busqueda &nbsp;'".$_REQUEST['busqueda']."'&nbsp;";
          }
          else
          {
               $cadena="Buscador Avanzado: Busqueda de todos los usuarios";
          }
          $this->salida.="  <td align=\"left\" colspan=\"5\">$cadena</td>";
          $this->salida.="</tr>";
          $this->salida.="</table><br>";

          if($_REQUEST['buscar'])
          {
               $filtro=$this->GetFiltroUsuarios($_REQUEST['criterio'],$_REQUEST['busqueda']);
          }
          $this->salida.="</form>";
          
          if($_REQUEST['accion_BUSCAR'] == true)
          {
	          $filas = $this->GetUsuariosSistema($_SESSION['EE_Admin'][UserGetUID()]['estacion_id'],$paso,$limit=null,$numReg=null,$filtro);          
          }
          else
          {
	          $filas = $this->GetUsuariosEstacion($_SESSION['EE_Admin'][UserGetUID()]['estacion_id'],$paso,$limit=null,$numReg=null,$filtro);          
          }

     
          if($filas===0)
          {
               $this->salida .= "<table width=\"100%\" border=\"0\" align=\"center\">\n";
               $this->salida .= "  <tr><td class=\"label_error\" align=\"center\">NO HAY USUARIOS ASIGNADOS A LA ESTACION</td></tr>\n";
               $this->salida .= "</table>\n";
          }
          elseif($filas===false)
          {
               if(empty($this->error))
               {
                    $this->error = "EE_Admin - User HTML 02";
                    $this->mensajeDeError = "El metodo GetUsuariosEstacion() retorno false.";
               }
               return false;
          }
          else
          {
               //LISTADO DE USUARIOS ACTIVOS EN LA ESTACION
               $this->salida .= "<table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"90%\" align=\"center\" >";
               $this->salida .= "  <tr class=\"modulo_table_list_title\" align=\"center\">";
               $this->salida .= "      <td>USUARIO</td>\n";
               $this->salida .= "      <td>NOMBRE DEL USUARIO</td>\n";
               $this->salida .= "      <td>UID</td>\n";
               $this->salida .= "      <td>ESTADO</td>\n";
               if($_REQUEST['accion_BUSCAR'] == true)
               {
                    $this->salida .= "      <td>TIPO PROFESIONAL</td>\n";
                    $this->salida .= "      <td>ACCION</td>\n";
               }else
               {
                    $this->salida .= "      <td>PERFIL</td>\n";
                    $this->salida .= "      <td>PERMISOS</td>\n";
               }
               $this->salida .= "  </tr> \n";
               foreach($filas as $k=>$v)
               {
                    foreach($v as $k2 =>$v)
                    {
                         if($i++ % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
                         $this->salida .= "  <tr class=\"$estilo\" align=\"center\">";
                         $this->salida .= "      <td align=\"left\">".$v[usuario]."</td>\n";
                         $this->salida .= "      <td align=\"left\">".$v[nombre]."</td>\n";
                         $this->salida .= "      <td align=\"right\">".$v[usuario_id]."</td>\n";
                         if($v[activo] == '1')
                         { 
                         	$estado="<img src=\"". GetThemePath() ."/images/activo.gif\" width=\"13\" border=\"0\">";
                              $Accion = ModuloGetUrl('app','EE_Admin','user','DesactivaProfesional',array('accion_bloqueo'=>$v[activo],'usuario'=>$v[usuario_id]));
                              $this->salida .= "      <td><a href=\"$Accion\">".$estado."</a></td>\n";
                              if($_REQUEST['accion_BUSCAR'] == true)
                              {
                                   $this->salida .= "<td align=\"left\"><b>".strtoupper($v[tipo_profesional])."</b></td>\n";
                                   $AdicionarEstacion = ModuloGetUrl('app','EE_Admin','user','InsertarUSenEE',array('usuario'=>$v[usuario_id]));
                              	$img = "<img src=\"".GetThemePath()."/images/atencion_citas.png\" width=\"13\" border=1 title=\"Usuario pendiente por ingresar al la Estación\">";
                                   $this->salida .= "      <td><a href=\"$AdicionarEstacion\">$img&nbsp;ADICIONAR A ESTACION</a></td>\n";
                              }else
                              {
                                   $this->salida .= "      <td align=\"left\"><b>".strtoupper($v[descripcion])."</b></td>\n";
                                   $AccionPermisos = ModuloGetUrl('app','EE_Admin','user','FrmFormaPermisos',array('perfil'=>$v[estacion_perfil_id],'usuario'=>$v[usuario_id],'estacion_id'=>$v[estacion_id]));
                                   $this->salida .= "      <td><a href=\"$AccionPermisos\">PERMISOS</a></td>\n";
                              }
                         }
	                    else
                         { 
                              $estado="<img src=\"". GetThemePath() ."/images/inactivo.gif\" width=\"13\" border='0'>";
                              $Accion = ModuloGetUrl('app','EE_Admin','user','DesactivaProfesional',array('accion_bloqueo'=>$v[activo],'usuario'=>$v[usuario_id]));
                              $this->salida .= "      <td><a href=\"$Accion\">".$estado."</a></td>\n";
                              if($_REQUEST['accion_BUSCAR'] == true)
                              {
                                   $this->salida .= "      <td align=\"left\">".strtoupper($v[tipo_profesional])."</td>\n";
                                   $img = "<img src=\"".GetThemePath()."/images/atencion_citas.png\" width=\"13\" border=1 title=\"Usuario pendiente por ingresar al la Estación\">";
                                   $this->salida .= "      <td>$img&nbsp;ADICIONAR A ESTACION</td>\n";
                              }else
                              {
                                   $this->salida .= "      <td align=\"left\">".strtoupper($v[descripcion])."</td>\n";
                                   $this->salida .= "      <td>PERMISOS</td>\n";
                              }
                         }
                         $this->salida .= "  </tr> \n";
                    }
               }
               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $AccionInsertar = ModuloGetUrl('app','EE_Admin','user','FrmUsuariosEstacion',array('accion_BUSCAR'=>true));
               $this->salida.="<td colspan=\"6\" align=\"right\"><a href=\"$AccionInsertar\">INSERTAR PROFESIONAL EN LA ESTACION</a></td>";
               $this->salida.="</tr>\n";               
               $this->salida.="</table>\n";
     
               //BARRA PAGINADORA
               $barra = new ClaseHTML;
     		
               if($_REQUEST['accion_BUSCAR'] == true)
               {
	               $TRUE = true;
               }

               $url= ModuloGetURL('app','EE_Admin','user','FrmUsuariosEstacion',array('accion_BUSCAR'=>$TRUE));
               $this->salida .= $barra->ObtenerPaginado($filas[1],$paso,$url,$limit=null);
          }//Fin del listado de usuarios en la estacion
     
          //BUSCADOR PARA ADICIONAR USUARIOS
     
          //PIE DE LA PAGINA
          $url= ModuloGetURL('app','EE_Admin','user','main');
          $this->salida .= "  <table align='center' width='100%' border='0' cellspacing=\"0\" cellpadding=\"8\">\n";
          $this->salida .= "    <tr>\n";
          $this->salida .= "        <td align='center' class=\"label_error\">\n";
          $this->salida .= "            <a href='$url'>VOLVER</a>\n";
          $this->salida .= "        </td>\n";
          $this->salida .= "    </tr>\n";
          $this->salida .= "  </table>\n";
          $this->salida .= themeCerrarTabla();
     
          return true;
     }
     
     
     /**
     Funcion que despliega en pantalla Todos y cada uno de los
     perfiles con los q cuentan los usuario de cada estacion y la forma 
     para seleccionar un perfil.
     **/
     function FrmFormaPermisos()
     {
          $perfil = $_REQUEST['perfil'];
          $usuario = $_REQUEST['usuario'];
          $estacion_id = $_REQUEST['estacion_id'];

/*************/
          $perfiles = $this->GetPerfiles();
          $perfil_US = $this->GetPerfiles_Usuarios($usuario,$estacion_id);
          $this->salida  = themeAbrirTabla('ESTACION DE ENFERMERIA : '.$_SESSION['EE_Admin'][UserGetUID()]['estacion_descripcion']);
          //LISTADO DE USUARIOS ACTIVOS EN LA ESTACION
          $this->salida .= "<table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_list_claro\">";
          $this->salida .= "  <tr class=\"modulo_table_list_title\" align=\"center\">";
          $this->salida .= "      <td colspan=\"2\">PERFILES DE USUARIOS DE LA ESTACION</td>\n";
          $this->salida .= "  </tr> \n";
          $this->salida .= "  <tr class=\"hc_table_submodulo_list_title\" align=\"center\">";
          $this->salida .= "      <td>DESCRIPCION DEL PERFILES</td>\n";
          $this->salida .= "      <td>ACCION</td>\n";
          $this->salida .= "  </tr> \n";
          
          foreach($perfiles as $k => $v)
          {
               if($i++ % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
               $this->salida .= "  <tr class=\"$estilo\" align=\"center\">";
               $this->salida .= "      <td align=\"left\">".strtoupper($v[descripcion])."</td>\n";
               if($perfil_US[estacion_perfil_id] == $v[estacion_perfil_id])
               {
               	$this->salida .= "      <td align=\"center\" width=\"5%\"><img src=\"". GetThemePath() ."/images/checkS.gif\" border='0'></td>\n";
                    $descripcion_perfil = strtoupper($v[descripcion]);
               }else
               {
                    $UpdatePerfil = ModuloGetUrl('app','EE_Admin','user','ActualizarPerfil',array('estacion_id'=>$estacion_id,'usuario'=>$usuario,'perfil_id'=>$v[estacion_perfil_id]));
               	$this->salida .= "   <td align=\"center\" width=\"5%\"><a href=\"$UpdatePerfil\"><img src=\"". GetThemePath() ."/images/checkN.gif\" border='0'></a></td>\n";
               }
               $this->salida .= "  </tr> \n";
          }
          $this->salida .= "</table><br><br>";
/*************/
                    
          if(empty($perfil))
          {
               $perfil = $perfil_US[estacion_perfil_id];
          }
          $Componentes = $this->GetPerfil_ComponenteEstacion($perfil,$usuario,$estacion_id);
          
          if(!empty($Componentes))
          {
               //$this->salida  = themeAbrirTabla('ESTACION DE ENFERMERIA : '.$_SESSION['EE_Admin'][UserGetUID()]['estacion_descripcion']);
               //LISTADO DE USUARIOS ACTIVOS EN LA ESTACION
               $this->salida .= "<table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_list_claro\">";
               $this->salida .= "  <tr class=\"modulo_table_list_title\" align=\"center\">";
               $this->salida .= "      <td>GRUPO COMPONENTE</td>\n";
               $this->salida .= "      <td>COMPONENTES DEL PERFIL ".$descripcion_perfil."</td>\n";
               $this->salida .= "  </tr> \n";
               foreach($Componentes as $k => $v)
               {
                    if($i++ % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
                    $this->salida .= "  <tr class=\"$estilo\" align=\"center\">";
                    $this->salida .= "      <td align=\"center\"><b>".strtoupper($k)."</b></td>\n";
                    $this->salida .= "      <td><table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\">";
                    foreach($v as $k2 => $v)
                    {
                         if($j++ % 2)  $estilo2 = "modulo_list_claro";  else  $estilo2 = "modulo_list_oscuro";
                         $this->salida .= "  <tr class=\"$estilo2\" align=\"center\">";
                         $this->salida .= "      <td align=\"left\">".strtoupper($v[grupo_componente])."</td>\n";
                         if($v[restar] == '1')
                         { 
                              $EliminarPerfil = ModuloGetUrl('app','EE_Admin','user','Eliminar_ComponenteUsuario',array('estacion_id'=>$estacion_id,'usuario'=>$usuario,'componente'=>$v[componente],'perfil'=>$perfil));                         
                              $this->salida .= "      <td align=\"center\" width=\"5%\"><a href=\"$EliminarPerfil\"><img src=\"". GetThemePath() ."/images/checkS.gif\" border='0' title=\"Eliminar Componente\"></a></td>\n";
                         }
                         else
                         {
                              $AdicionarPerfil = ModuloGetUrl('app','EE_Admin','user','Adicionar_ComponenteUsuario',array('estacion_id'=>$estacion_id,'usuario'=>$usuario,'componente'=>$v[componente],'perfil'=>$perfil));                         
                              $this->salida .= "      <td align=\"center\" width=\"5%\"><a href=\"$AdicionarPerfil\"><img src=\"". GetThemePath() ."/images/checkN.gif\" border='0' title=\"Adicionar Componente\"></a></td>\n";
                         }
                         $this->salida .= "  </tr> \n";
                    }
                    $this->salida .= "      </table></td>\n";
                    $this->salida .= "  </tr> \n";
               }
               $this->salida .= "</table>";
          }
          //PIE DE LA PAGINA
          $url= ModuloGetURL('app','EE_Admin','user','FrmUsuariosEstacion');
          $this->salida .= "  <table align='center' width='100%' border='0' cellspacing=\"0\" cellpadding=\"8\">\n";
          $this->salida .= "    <tr>\n";
          $this->salida .= "        <td align='center' class=\"label_error\">\n";
          $this->salida .= "            <a href='$url'>VOLVER</a>\n";
          $this->salida .= "        </td>\n";
          $this->salida .= "    </tr>\n";
          $this->salida .= "  </table>\n";
          $this->salida .= themeCerrarTabla();
          return true;
     }
     
}//fin de la clase

?>

