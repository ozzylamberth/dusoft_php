<?php

/**
 * $Id: app_AtencionInterconsulta_userclasses_HTML.php,v 1.5 2006/05/16 22:25:42 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_AtencionInterconsulta_userclasses_HTML extends app_AtencionInterconsulta_user
{

	function app_AtencionInterconsulta_user_HTML()
	{
	  $this->app_AtencionInterconsulta_user(); //Constructor del padre 'modulo'
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
          
          $datos_estacion = $this->GetEstacionActiva();
     
          if($datos_estacion===false)
          {
               if(empty($this->error))
               {
                    $this->error = "AtencionInterconsulta - FrmPanelEstacion";
                    $this->mensajeDeError = "El metodo GetEstacionActiva() retorno false.";
               }
               return false;
          }
     
          if($datos_estacion===null)
          {
               $this->FrmLogueoEstacion();
               return true;
          }
          
          $this->GetUserPerfil();
          
          if($datos_estacion['sw_consulta_urgencia'])
          {
               //Verificamos estado de Variable de Retorno.
			if($_SESSION['RETORNO_PANEL'])
               	$this->FrmSeleccionEstacionE($datos_estacion);
               else
               	$this->FrmLogueoEstacionSubMenu($datos_estacion);
               return true;
          }
          else
          {
	          $this->FrmDatosEstacion(&$datos_estacion);
               $this->FrmListadoPacientesEstacion_Interconsultas($datos_estacion);
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
          $this->salida .= ThemeMenuAbrirTabla("ESTACION DE ENFERMERIA ".$datos_estacion[estacion_descripcion]." - (INTERCONSULTAS)","50%");
          $Estadisticas = $this->EstadisticasEE();
          
          $this->salida.="<table align='center' border='0' width='95%' cellpadding=\"4\" cellspacing=\"4\">";
          if($Estadisticas['hospitalizados'])
          {
               $this->salida.="	<tr class='label_mark'>";
               $this->salida.="		<td width='68%' align='left' colspan=\"2\">PACIENTES HOSPITALIZADOS</td>";
               $this->salida.="	</tr>";
               
               $this->salida.="	<tr class='label_error'>";
               $this->salida.="		<td  width='2%' align='center'>";
               $this->salida.="			<img src=\"". GetThemePath() ."/images/editar.gif\">&nbsp;&nbsp;";
               $this->salida.="		</td>";
               $this->salida.="		<td width='68%' align='left'><a href=\"".ModuloGetURL('app','AtencionInterconsulta','user','FrmSeleccionEstacionE',array('datos_estacion'=>$datos_estacion,'urgencias'=>'hospitalizados'))."\"><b>PACIENTES HOSPITALIZADOS</b></a></td>";
               $this->salida.="	</tr>";
               $this->salida.="	<tr class='label'>";
               $this->salida.="		<td width='2%' align='center'>&nbsp;</td>";
               $this->salida.="		<td width='68%' align='left'>";
               $this->salida.="			<img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'>&nbsp;Numero de pacientes &nbsp; (".$Estadisticas['hospitalizados'].")";
               $this->salida.="		</td>";
               $this->salida.="	</tr>";
		}
          
          if($Estadisticas['en_consulta'])
          {
               $this->salida.="	<tr class='label_mark'>";
               $this->salida.="		<td width='68%' align='left' colspan=\"2\">PACIENTES EN CONSULTA DE URGENCIAS</td>";
               $this->salida.="	</tr>";
               
               $this->salida.="	<tr class='label_error'>";
               $this->salida.="		<td width='2%' align='center'>";
               $this->salida.="			<img src=\"". GetThemePath() ."/images/editar.gif\">&nbsp;&nbsp;";
               $this->salida.="		</td>";
               $accionSinC = ModuloGetURL('app','AtencionInterconsulta','user','FrmSeleccionEstacionE',array('datos_estacion'=>$datos_estacion,'urgencias'=>'todos'));
               $this->salida.="		<td width='68%' align='left'><a href=\"$accionSinC\">PACIENTES EN CONSULTA DE URGENCIAS</a>&nbsp;&nbsp;&nbsp;&nbsp;<label class=\"label\">Pacientes&nbsp; (".$Estadisticas['en_consulta'].")</label></td>";
               $this->salida.="	</tr>";
		}
          
          $href    = ModuloGetURL('app','AtencionInterconsulta','user','FrmLogueoEstacion');
          
          $this->salida .= "<tr><td colspan=\"2\">";
          $this->salida .= "<center>\n";
          $this->salida .= "  <div class='normal_10' align='center'><br>\n";
          $this->salida .= "    <a href='$href'><b>Seleccionar Estaci&oacute;n</b></a>\n";
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
                    $this->error = "AtencionInterconsulta - FrmLogueoEstacion";
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
          $url[1]='AtencionInterconsulta';
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
                         $this->error = "AtencionInterconsulta - FrmEstacionNoLogin";
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
     
          $this->salida .= gui_theme_menu_acceso("SELECCION DE ESTACION DE ENFERMERIA - (INTERCONSULTAS)",$mtz,$estaciones,$url);
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
               
               
          switch ($_SESSION['RETORNO_PANEL'])
          {
               case 'hospitalizados':
                    $this->FrmListadoPacientesEstacion_Interconsultas($datos_estacion);
                    $this->FrmPieDePaginaPanelEstacion();
		          return true;
               break;
               case 'todos':
                    if($datos_estacion['sw_consulta_urgencia'])
                    {
                         $this->FrmListadoPacientesConsultaUrgencias_Interconsultas($datos_estacion);
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
               $url = ModuloGetURL('app','AtencionInterconsulta','user','FrmPanelEstacion');
               $titulo  = "NO SE PUDO ESTABLECER LA ESTACION SELECCIONADA";
               $mensaje = "El argumento [estacion_id] no llego y/o el usuario no tiene permisos en la estacion seleccionada.";
     
               $this->FrmMSG($url, $titulo, $mensaje);
     
               return true;
          }

          $this->FrmPanelEstacion($subMenu=true);
          return true;
     }

     
     /**
     * Metodo que muestra en pantalla todos los pacientes pendientes X Interconsulta.
     *
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function FrmListadoPacientesEstacion_Interconsultas($datos_estacion)
	{
		$prueba=$this->ReconocerProfesional();
		$hospitaesta1=$this->BuscarPacienteHosptalizados($datos_estacion['estacion_id']);
		$hospitaesta=$hospitaesta1[0];
		$DatosHospitalizacion=$hospitaesta1[1];
		if($DatosHospitalizacion)
		{
			$this->SetJavaScripts('DatosPaciente');
			$this->salida .= "<BR>";
			$this->salida .= "<BR>";
			$this->salida .= '<table width="90%" align="center" border="0" class="modulo_table">';
			$this->salida .= '<tr align="center" class="modulo_table_title">';
			$this->salida .= '<td>';
			$this->salida .= "PACIENTES HOSPITALIZADOS";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= '<tr align="center">';
			$this->salida .= '<td align="center">';
			$this->salida .= '<table width="100%" align="center" class="modulo_table_list" border="1">';
			$this->salida .= '<tr align="center" class="modulo_table_list_title">';
			$this->salida .= '<td align="center" width="35%">';
			$this->salida .= "Pacientes";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center" width="10">';
			$this->salida .= "Pieza - Cama";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= "Fecha Evolución";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= "Nombre";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= "Especialidad";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$i=$spy=0;
               
	          $_SESSION['HISTORIACLINICA']['RETORNO']['contenedor']   = 'app';
               $_SESSION['HISTORIACLINICA']['RETORNO']['modulo']       = 'AtencionInterconsulta';
               $_SESSION['HISTORIACLINICA']['RETORNO']['tipo']         = 'user';
               $_SESSION['HISTORIACLINICA']['RETORNO']['metodo']       = 'FrmPanelEstacion';

			foreach($DatosHospitalizacion as $k=>$v)
			{
				foreach($v as $t=>$r)
				{
					foreach($r as $p=>$q)
					{
						if($spy==0)
						{
							$this->salida.='<tr align="center" class="modulo_list_claro">';
							$dato='<tr align="center" class="modulo_list_claro">';
							$spy=1;
						}
						else
						{
							$this->salida.='<tr align="center" class="modulo_list_oscuro">';
							$dato='<tr align="center" class="modulo_list_oscuro">';
							$spy=0;
						}
						$this->salida .= '<td align="left">';
						$open=RetornarWinOpenDatosPaciente($t,$k,$p);
						$this->salida .=$open;
						$this->salida .= "</td>";
						$t=0;
						$prof=0;
						foreach($q as $h=>$j)
						{
							if($t==0)
							{
								$this->salida .= '<td align="center">';
								$this->salida .=$j['cama'];
								$this->salida .= "</td>";
								$t=1;
							}
							$salida1 .= '<table width="100%" align="center" border="0">';
							$salida1.=$dato;
							$salida1 .= "<td>";
							$salida1 .=$j['fecha'];
							$salida1 .= "</td>";
							$salida1 .= "</tr>";
							$salida1 .= '</table>';
							$salida2 .= '<table width="100%" align="center" border="0">';
							$salida2.=$dato;
							$salida2 .= "<td>";
							$salida2 .=$j['nombre'];
							$salida2 .= "</td>";
							$salida2 .= "</tr>";
							$salida2 .= '</table>';
							$salida .= '<table width="100%" align="center" border="0">';
							$salida.=$dato;
							$especialidad='';
							$arr=$this->RevisarInterConsultas($j['ingreso']);

              					if(!empty($arr))
							{
                                        for($x=0;$x<sizeof($arr);$x++)
                                        {
                                             if(empty ($arr[$x][hc_modulo]))
                                             	$arr[$x][hc_modulo] = 'AtencionInterconsulta';
                                             
                                             if(empty($j['evolucion_id']))
                                             {
                                                  $accion=ModuloHCGetURL(0, -1, $j['ingreso'], $arr[$x][hc_modulo], '', array('estacion'=>$datos_estacion['estacion_id']));
                                                  $prof=1;
                                             }
                                             else
                                             {
                                                  if($j['usuario_id']==UserGetUID())
                                                  $accion=ModuloHCGetURL($j['evolucion_id'], -1, 0, $arr[$x][hc_modulo], '', array('estacion'=>$datos_estacion['estacion_id']));
                                             }
                                             if($prof==0)
                                             {
                                                  $accion=ModuloHCGetURL(0, -1, $j['ingreso'], $arr[$x][hc_modulo], '', array('estacion'=>$datos_estacion['estacion_id']));
                                             }
                                             $especialidad .="<a href='$accion'>".$arr[$x][descripcion]."</a><br>";
                                        }
                                        $salida .= "<td>$especialidad";
                                        $especialidad='';
								$salida .= "</td>";
							}else
							{
                                   	$salida .= "<td><label class='label_mark'>No tiene Interconsulta</label></td>";
							}
							$salida .= "</tr>";
							$salida .= '</table>';
						}
						$this->salida .= '<td align="center" valign="top">';
						$this->salida .=$salida1;
						$this->salida .= "</td>";
						$this->salida .= '<td align="center" valign="top">';
						$this->salida .=$salida2;
						$this->salida .= "</td>";
						$this->salida .= '<td align="center" valign="top">';
						$this->salida .=$salida;
						$this->salida .= "</td>";
						$this->salida .= '</tr>';
						$salida='';
						$salida1='';
						$salida2='';
					}
				}
			}
			$this->salida .= '</table>';
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= '</table>';
		}
		else
		{
			$this->salida .= '<table width="80%" align="center">';
			$this->salida .= '<tr align="center">';
			$this->salida .= '<td align="center">';
			$this->salida .= '<label class="label_error">NO HAY PACIENTES PARA ATENDER EN HOSPITALIZACIÓN</label>';
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= "</table>";
		}
		return true;
	}

     /**
     * Metodo que muestra en pantalla todos los pacientes de Urgencias pendientes X Interconsulta.
     *
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function FrmListadoPacientesConsultaUrgencias_Interconsultas($datos_estacion)
	{
		$prueba=$this->ReconocerProfesional();
		$hospitaesta1=$this->BuscarPacienteConsultaURG($datos_estacion['estacion_id']);
		$hospitaesta=$hospitaesta1[0];
		$DatosHospitalizacion=$hospitaesta1[1];
		if($DatosHospitalizacion)
		{
			$this->SetJavaScripts('DatosPaciente');
			$this->salida .= "<BR>";
			$this->salida .= "<BR>";
			$this->salida .= '<table width="90%" align="center" border="0" class="modulo_table">';
			$this->salida .= '<tr align="center" class="modulo_table_title">';
			$this->salida .= '<td>';
			$this->salida .= "PACIENTES EN CONSULTA DE URGENCIAS";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= '<tr align="center">';
			$this->salida .= '<td align="center">';
			$this->salida .= '<table width="100%" align="center" class="modulo_table_list" border="1">';
			$this->salida .= '<tr align="center" class="modulo_table_list_title">';
			$this->salida .= '<td align="center" width="35%">';
			$this->salida .= "Pacientes";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= "Fecha Evolución";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= "Nombre";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= "Especialidad";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$i=$spy=0;

               $_SESSION['HISTORIACLINICA']['RETORNO']['contenedor']   = 'app';
               $_SESSION['HISTORIACLINICA']['RETORNO']['modulo']       = 'AtencionInterconsulta';
               $_SESSION['HISTORIACLINICA']['RETORNO']['tipo']         = 'user';
               $_SESSION['HISTORIACLINICA']['RETORNO']['metodo']       = 'FrmPanelEstacion';

			foreach($DatosHospitalizacion as $k=>$v)
			{
				foreach($v as $t=>$r)
				{
					foreach($r as $p=>$q)
					{
						if($spy==0)
						{
							$this->salida.='<tr align="center" class="modulo_list_claro">';
							$dato='<tr align="center" class="modulo_list_claro">';
							$spy=1;
						}
						else
						{
							$this->salida.='<tr align="center" class="modulo_list_oscuro">';
							$dato='<tr align="center" class="modulo_list_oscuro">';
							$spy=0;
						}
						$this->salida .= '<td align="left">';
						$open=RetornarWinOpenDatosPaciente($t,$k,$p);
						$this->salida .=$open;
						$this->salida .= "</td>";
						$t=0;
						$prof=0;
						foreach($q as $h=>$j)
						{
							$salida1 .= '<table width="100%" align="center" border="0">';
							$salida1.=$dato;
							$salida1 .= "<td>";
							$salida1 .=$j['fecha'];
							$salida1 .= "</td>";
							$salida1 .= "</tr>";
							$salida1 .= '</table>';
							$salida2 .= '<table width="100%" align="center" border="0">';
							$salida2.=$dato;
							$salida2 .= "<td>";
							$salida2 .=$j['nombre'];
							$salida2 .= "</td>";
							$salida2 .= "</tr>";
							$salida2 .= '</table>';
							$salida .= '<table width="100%" align="center" border="0">';
							$salida.=$dato;
							$especialidad='';
							$arr=$this->RevisarInterConsultas($j['ingreso']);

              					if(!empty($arr))
							{
                                        for($x=0;$x<sizeof($arr);$x++)
                                        {
                                        	if(empty ($arr[$x][hc_modulo]))
                                             	$arr[$x][hc_modulo] = 'AtencionInterconsulta';
                                                  
                                             if(empty($j['evolucion_id']))
                                             {
                                                  $accion=ModuloHCGetURL(0, -1, $j['ingreso'], $arr[$x][hc_modulo], '', array('estacion'=>$datos_estacion['estacion_id']));
                                                  $prof=1;
                                             }
                                             else
                                             {
                                                  if($j['usuario_id']==UserGetUID())
                                                  $accion=ModuloHCGetURL($j['evolucion_id'], -1, 0, $arr[$x][hc_modulo], '', array('estacion'=>$datos_estacion['estacion_id']));
                                             }
                                             if($prof==0)
                                             {
                                                  $accion=ModuloHCGetURL(0, -1, $j['ingreso'], $arr[$x][hc_modulo], '', array('estacion'=>$datos_estacion['estacion_id']));
                                             }
                                             $especialidad .="<a href='$accion'>".$arr[$x][descripcion]."</a><br>";
                                        }
                                        $salida .= "<td>$especialidad";
                                        $especialidad='';
								$salida .= "</td>";
							}else
							{
                                   	$salida .= "<td><label class='label_mark'>No tiene Interconsulta</label></td>";
							}
							$salida .= "</tr>";
							$salida .= '</table>';
						}
						$this->salida .= '<td align="center" valign="top">';
						$this->salida .=$salida1;
						$this->salida .= "</td>";
						$this->salida .= '<td align="center" valign="top">';
						$this->salida .=$salida2;
						$this->salida .= "</td>";
						$this->salida .= '<td align="center" valign="top">';
						$this->salida .=$salida;
						$this->salida .= "</td>";
						$this->salida .= '</tr>';
						$salida='';
						$salida1='';
						$salida2='';
					}
				}
			}
			$this->salida .= '</table>';
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= '</table>';
		}
		else
		{
			$this->salida .= '<table width="80%" align="center">';
			$this->salida .= '<tr align="center">';
			$this->salida .= '<td align="center">';
			$this->salida .= '<label class="label_error">NO HAY PACIENTES PARA ATENDER EN HOSPITALIZACIÓN</label>';
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= "</table>";
		}
		return true;
	}

          
	function ListadoPacienteUrgencias()
	{
		$modulo=$this->TipoModulo();
		if($modulo==false)
		{
			return false;
		}
		$DatosEstacion=$this->BuscarPacientesEstacion();
		$prueba=$this->ReconocerProfesional();
		if($prueba==1 or $prueba==2)
		{
			if($DatosEstacion)
			{
				$this->SetJavaScripts('DatosPaciente');
				$this->salida .= "<BR>";
				$this->salida .= '<table width="80%" align="center" border="0" class="modulo_table">';
				$this->salida .= '<tr align="center" class="modulo_table_title">';
				$this->salida .= '<td>';
				$this->salida .= "Paciente en Urgencias";
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
				$this->salida .= '<tr align="center">';
				$this->salida .= '<td align="center">';
				$this->salida .= '<table width="100%" align="center" class="modulo_table_list" border="1">';
				$this->salida .= '<tr align="center" class="modulo_table_list_title">';
				$this->salida .= '<td align="center" width="35%">';
				$this->salida .= "Pacientes";
				$this->salida .= "</td>";
				$this->salida .= '<td align="center">';
				$this->salida .= "Tiempo en Espera";
				$this->salida .= "</td>";
				$this->salida .= '<td align="center">';
				$this->salida .= "Fecha Evolucion";
				$this->salida .= "</td>";
				$this->salida .= '<td align="center">';
				$this->salida .= "Profesional";
				$this->salida .= "</td>";
				$this->salida .= '<td align="center">';
				$this->salida .= "Acción";
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
				$spy=0;
				foreach($DatosEstacion as $k=>$v)
				{
					foreach($v as $t=>$r)
					{
						foreach($r as $p=>$h)
						{
							$s=0;
							$prof=0;
							foreach($h as $i=>$j)
							{
								if($s==0)
								{
									if(!empty($j[0]))
									{
										$this->salida.='<tr align="center" class="'.$j[2].'">';
										$dato='<tr align="center" class="'.$j[2].'">';
									}
									else
									{
										if(empty($j[0]) or $j[0]==1)
										{
											if($spy==0)
											{
												$this->salida.='<tr align="center" class="modulo_list_claro">';
												$dato='<tr align="center" class="modulo_list_claro">';
												$spy=1;
											}
											else
											{
												$this->salida.='<tr align="center" class="modulo_list_oscuro">';
												$dato='<tr align="center" class="modulo_list_oscuro">';
												$spy=0;
											}
										}
									}
									$this->salida .= "<td>";
									$open=RetornarWinOpenDatosPaciente($t,$k,$p);
									$this->salida .=$open;
									$this->salida .= "</td>";
									$this->salida .= "<td>";
									if($j[0]==1)
									{
										$this->salida .="<label class=\"label_error\">";
									}
									$this->salida .=$j[1];
									if($j[0]==1)
									{
										$this->salida .="</label>";
									}
									$this->salida .= "</td>";
									$s=1;
								}
								$salida1 .= '<table width="100%" align="center" border="0">';
								$salida1.=$dato;
								$salida1 .= "<td>";
								$salida1 .=$j[5];
								$salida1 .= "</td>";
								$salida1 .= "</tr>";
								$salida1 .= '</table>';
								$salida2 .= '<table width="100%" align="center" border="0">';
								$salida2.=$dato;
								$salida2 .= "<td>";
								$salida2 .=$j[7];
								$salida2 .= "</td>";
								$salida2 .= "</tr>";
								$salida2 .= '</table>';
								$salida3 .= '<table width="100%" align="center" border="0">';
								$salida3.=$dato;
								$salida3 .= "<td>";
								if(empty($j[6]))
								{
									if($j[9]==='0')
									{
										$accion=ModuloGetURL('app','AtencionUrgenciasHospitalizacion','user','ClasificarTriage', array('tipo_id_paciente'=>$t, 'paciente_id'=>$k, 'plan_id'=>$j[10], 'triage_id'=>$j[11], 'punto_triage_id'=>$j[12], 'punto_admision_id'=>$j[13], 'sw_no_atender'=>$j[14], 'ingreso'=>$j[3], 'moduloh'=>$modulo, 'estacion_id'=>$j[8]));
									}
									else
									{
										$accion=ModuloHCGetURL(0,'',$j[15],$modulo,$modulo,array('estacion'=>$j[8]));
									}
									$salida3 .="<a href='$accion'>Atender</a>";
									$prof=1;
								}
								else
								{
									if($j[6]==$_SESSION['SYSTEM_USUARIO_ID'])
									{
										$accion=ModuloHCGetURL($j[4],'',0,$modulo,$modulo,array('estacion'=>$j[8]));
										$salida3 .="<a href='$accion'>Continuar Atencion</a>";
										$prof=1;
									}
									else
									{
										$salida3 .="Otro Profesional";
									}
								}
								$salida3 .= "</td>";
								$salida3 .= "</tr>";
								$salida3 .= '</table>';
							}
							$this->salida .= "<td valign='top'>";
							$this->salida.=$salida1;
							$this->salida .= "</td>";
							$this->salida .= "<td valign='top'>";
							$this->salida.=$salida2;
							$this->salida .= "</td>";
							$this->salida .= "<td valign='top'>";
							if($prof==0)
							{
								$salida3 .='<table width="100%" align="center" border="0">';
								$salida3.=$dato;
								$salida3 .= "<td>";
								$accion=ModuloHCGetURL(0,'',$j[15],$modulo,$modulo,array('estacion'=>$j[8]));
								$salida3.='<a href="'.$accion.'">Nueva Atencion</a>';
								$salida3 .= "</td>";
								$salida3 .= "</tr>";
								$salida3 .= '</table>';
							}
							$this->salida.=$salida3;
							$this->salida .= "</td>";
							$this->salida .= "</tr>";
							$salida='';
							$salida1='';
							$salida2='';
							$salida3='';
						}
					}
				}
				$this->salida .= '</table>';
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
				$this->salida .= '</table>';
			}
			else
			{
				$this->salida .= '<table width="80%" align="center">';
				$this->salida .= '<tr align="center">';
				$this->salida .= '<td align="center">';
				$this->salida .= '<label class="label_error">NO HAY PACIENTES PARA ATENDER EN URGENCIAS</label>';
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
				$this->salida .= "</table>";
			}
			$this->salida .= "<BR>";
		}
		return true;
	}

	function ContinuarHistoria()
	{
		$this->salida.="<script>\n";
		$this->salida.="location.href=\"".ModuloHCGetURL(0,'',$_SESSION['Atencion']['ingreso'],$_SESSION['Atencion']['modulo'],$_SESSION['Atencion']['modulo'],array('estacion'=>$_SESSION['Atencion']['estacion_id']))."\";\n";
		$this->salida.="</script>\n";
		return true;
	}

	function ListadoPacientesClasificar()
	{
		$pacientestriage=$this->PacientesClasificacionTriage();
		if($pacientestriage)
		{
			$this->SetJavaScripts('DatosPaciente');
			$this->salida .= '<table width="80%" align="center" border="0" class="modulo_table">';
			$this->salida .= '<tr align="center" class="modulo_table_title">';
			$this->salida .= '<td>';
			$this->salida .= "Paciente Para Clasificación Triage";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= '<tr align="center">';
			$this->salida .= '<td align="center">';
			$this->salida .= '<table width="100%" align="center" class="modulo_table_list" border="1">';
			$this->salida .= '<tr align="center" class="modulo_table_list_title">';
			$this->salida .= '<td align="center" width="70%">';
			$this->salida .= "Pacientes";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= "Acción";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$spy=0;
			foreach($pacientestriage as $k=>$v)
			{
				foreach($v as $t=>$s)
				{
					if($spy==0)
					{
						$this->salida.='<tr align="center" class="modulo_list_claro">';
						$spy=1;
					}
					else
					{
						$this->salida.='<tr align="center" class="modulo_list_oscuro">';
						$spy=0;
					}
					$this->salida .= '<td align="center">';
					$this->salida.=RetornarWinOpenDatosPaciente($s['tipo_id_paciente'],$s['paciente_id'],$s['nombre']);
					$this->salida .= "</td>";
					$this->salida .= '<td align="center">';
					$accion=ModuloGetURL('app','AtencionUrgenciasHospitalizacion','user','ClasificarTriage',array('paciente_id'=>$s['paciente_id'], 'tipo_id_paciente'=>$s['tipo_id_paciente'], 'plan_id'=>$s['plan_id'], 'triage_id'=>$s['triage_id'], 'punto_triage_id'=>$s['punto_triage_id'], 'punto_admision_id'=>$s['punto_admision_id'], 'sw_no_atender'=>$s['sw_no_atender']));
					$this->salida.='<a href="'.$accion.'">Clasificar</a>';
					$this->salida .= "</td>";
					$this->salida .= "</tr>";
				}
			}
			$this->salida .= "</table>";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= '</table>';
		}
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
               $this->salida .= ThemeAbrirTabla("ESTACI&Oacute;N DE ENFERMERIA : ".$datos['estacion_descripcion']." - (INTERCONSULTAS)");
               $this->salida .= "<center>\n";
               $this->salida .= "    <table class='modulo_table_title' border='0' width='90%'>\n";
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
          $refresh = ModuloGetURL('app','AtencionInterconsulta','user','FrmPanelEstacion');
          $href    = ModuloGetURL('app','AtencionInterconsulta','user','FrmLogueoEstacion');
     
          $this->salida .= "<table align=\"center\">";
          $this->salida .= "<tr><td>";
          $this->salida .= "<center>\n";
          $this->salida .= "  <div class='normal_10' align='center'><br>\n";
          $this->salida .= "    <a href='$href'>Seleccionar Estaci&oacute;n</a>\n";
          $this->salida .= "    &nbsp;&nbsp;-&nbsp;&nbsp;\n";
          $this->salida .= "    <a href='$refresh'>Refrescar</a><br>\n";
          $this->salida .= "  </div>\n";
          $this->salida .= "</center>\n";
          $this->salida .= "</td></tr>";          
          $this->salida .= "</table>";
          $this->salida .= themeCerrarTabla();
          return true;
     }

}
?>
