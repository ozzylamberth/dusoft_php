<?php

/**
 * $Id: app_EE_ControlesPacientes_APD_userclasses_HTML.php,v 1.2 2006/01/04 19:17:31 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author  Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
 * @package IPSOFT-SIIS
 */

class app_EE_ControlesPacientes_APD_userclasses_HTML extends app_EE_ControlesPacientes_APD_user
{
     /**
     * Constructor
     *
     * @return boolean
     */
     function app_EE_ControlesPacientes_APD_user_HTML()
     {
          $this->app_EE_ControlesPacientes_APD_user();
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
     function FrmDatosEstacion($datos,$descripcion)
     {
     	if(empty($descripcion))
          	$descripcion = "ESTACI&Oacute;N DE ENFERMERIA";
               
          $this->salida .= ThemeAbrirTabla($descripcion." : ".$datos['estacion_descripcion']);
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
     function CallControlesPacientes($datosPaciente,$datos_estacion)
     {
          //Vector que contiene los datos del paciente internado.
          if(empty($datosPaciente))
          	$datosPaciente = $_REQUEST['datosPaciente'];

                         //PRINT_R($datosPaciente);
          if($datosPaciente===false)
          {
               if(empty($this->error))
               {
                    $this->error = "EE_ControlesPacientes";
                    $this->mensajeDeError = "Imposible acceder al metodo.";
               }
               return false;
          }
          elseif(!is_array($datosPaciente))
          {
               $url= ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
               $titulo='VALIDACION DE PACIENTE';
               if(empty($datosPaciente))
               {
                    $mensaje = "No se pudo obtener los datos del paciente.";
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
     	
          $descripcion = "CONTROLES DE APOYOS DIAGNOSTICOS PENDIENTES";
          $this->ControlesPacientes($datos_estacion,$datosPaciente,$descripcion);
          return true;
     }
     
     
     function ControlesPacientes($datos_estacion,$datosPaciente,$descripcion)
     {
          $fecha=date("Y-m-d H:i:s");
          $this->FrmDatosEstacion($datos_estacion,$descripcion);
          if(!empty($datosPaciente))
          {
               $get_examen=$this->GetExamenes($datosPaciente['ingreso']);
               $fech_pro=$this->GetFechaProgramacion($datosPaciente['ingreso']);
               $url= ModuloGetURL('app','EE_ControlesPacientes_APD','user',"CallFrmSolicitudE",array("datosPaciente"=>$datosPaciente,"datos_estacion"=>$datos_estacion,"obs"=>$get_examen[$l]['descripcion']."Actividad:"));
               
               $this->salida .= "<br><table align=\"center\" width=\"88%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_title\">\n";
               $this->salida .= "	<tr class=\"modulo_table_title\">\n";
               if(!empty($datosPaciente[cama]))
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

               $img = "<img src=\"".GetThemePath()."/images/EstacionEnfermeria/alarma.png\" border=0 width=12 heigth=12>";
               $this->salida .= "<table align='center' width=\"100%\"  border=\"0\" class=\"modulo_table_list\">\n";

               if(UserGetUID()==0)
               {
                    $this->salida .= "					<tr class=\"modulo_list_claro\" align=\"center\"><td>$img PROGRAMAR ACTIVIDAD\n";
               }
               else
               {
                    $this->salida .= "					<tr class=\"modulo_list_claro\" align=\"center\"><td>$img <a href='$url'>PROGRAMAR ACTIVIDAD</a>\n";
               }
               $this->salida .= "					</td></tr>\n";
               $this->salida .= "</table>\n";

               $this->salida .= "<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list\">\n";
               $this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
               $this->salida .= "	</tr>\n";
                                        
               $this->salida .= "<tr ".$this->Lista($i).">\n";
                  
               $url= ModuloGetURL('app','EE_ControlesPacientes_APD','user',"CallFrmSolicitudE",array("datosPaciente"=>$datosPaciente,"datos_estacion"=>$datos_estacion,"obs"=>$get_examen[$l]['descripcion']."Actividad:"));
               $i_label='<label class=label_mark>';
               $f_label='</label>';

               if(!empty($get_examen))
               {
                    $var_add="";
                    $var_add1="";
                    for($l=0; $l<sizeof($get_examen); $l++)
                    {
                         $var_add .= "<tr ".$this->Lista($l)." >";
                         $var_add .= "<td>";
                         $var_adde="<td>";
                         $var_ex="<td>";
                         $var_aut="<td>";
                         $arr=explode(".",$get_examen[$l]['fecha']);
                         $fecha_hora=explode(" ",$arr[0]);
                         $var_add.=$fecha_hora[0]."&nbsp;".$fecha_hora[1];
                         $var_adde.=$get_examen[$l]['descripcion'];
                         $var_ex.=ucwords(strtolower($get_examen[$l]['des']));
                         if($get_examen[$l]['sw_estado']=='0')
                         {
                              $var_aut.=$i_label .'Autorizado'.$f_label;
                         }
                         else
                         {
                              $var_aut.='No Autorizado';
                         }
                         $var_add .= "</td>";
                         $var_adde.= "</td>";
                         $var_ex.= "</td>";
                         $var_aut.="</td>";
                         $var_add.=$var_adde;
                         $var_add.=$var_ex;
                         $var_add.=$var_aut;
                         $var_add.="</tr>";
                    }
                    $var_add1 .= "<table border=\"1\" width='100%'>";
                    $var_add1.="<tr class='modulo_table_title'>";
                    $var_add1.="<td>";
                    $var_add1.="Fecha";
                    $var_add1.="</td>";
                    $var_add1.="<td>";
                    $var_add1.="Solicitud";
                    $var_add1.="</td>";
                    $var_add1.="<td align='center'>";
                    $var_add1.="Examen";
                    $var_add1.="</td>";
                    $var_add1.="<td>";
                    $var_add1.="Autorizacion";
                    $var_add1.="</td>";
                    $var_add1.="</tr>";
                    $var_add1.=$var_add;
                    $var_add1 .= "</table>";
                    $this->salida.="<td colspan='4'>".$this->GetFrmFechaProgramacion($datosPaciente['ingreso'],$datos_estacion,$datosPaciente)."</td></tr><tr ".$this->Lista($i).">";
                    $this->salida .= "<td align=\"left\" colspan='4'>$var_add1</td>\n";
                    unset($var_add);
                    unset($var_adde);
                    unset($var_aut);
                    unset($f_label);
                    unset($i_label);
                    unset($var_ex);
               }
               else
               {	
                    //aqui mostramos solamente si hay una programacion...
                    $this->salida.="<td colspan='4'>".$this->GetFrmFechaProgramacion($datosPaciente['ingreso'],$datos_estacion,$datosPaciente)."</td></tr><tr ".$this->Lista($i).">";
               }

               /***************************/
               $this->salida .= "</td></tr>\n";
               //End for
               $this->salida .= "</table><br>\n";
               $this->FrmPieDePagina();
               return true;
          }
	     else
          {
               $mensaje = "LA ESTACI&Oacute;N [ ".$datos_estacion['estacion_descripcion']." ] NO CUENTA CON PACIENTES.";
               $titulo = "MENSAJE";
               $link = "PANEL ENFERMERIA";
               $url= ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
               $this->frmMSG($url,$titulo,$mensaje,$link);
               return true;
          }
     }
     
     /*
     *	GetFrmFechaProgramacion
     */
     function GetFrmFechaProgramacion($ingreso,$estacion,$datoscenso)
     {
          $_SESSION['CONTROLA']['ESTACIONX']=$estacion;
          $dats=$this->GetFechaProgramacion($ingreso);
		if($dats)
          {
               $salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";
               $salida.="<tr class=\"modulo_table_list_title\">";
               $salida.="  <td></td>";
               $salida.="  <td>FECHA</td>";
               $salida.="  <td>HORA</td>";
               $salida.="  <td><SUB>AYUNO</SUB></td>";
               $salida.="  <td>ACTIVIDAD</td>";
               $salida.="  <td></td>";
               $salida.="</tr>";
               $img="<img src=\"".GetThemePath()."/images/siguiente.png\" width=10 heigth=10>&nbsp;";

               for($i=0;$i<sizeof($dats);$i++)
               {
                    $desc=$dats[$i][observacion];
                    $fecha1=$dats[$i][fecha];
                    if( $i % 2){ $estilo='modulo_list_claro';}
                    else {$estilo='modulo_list_oscuro';}
                    $salida.="<tr class=\"$estilo\" align=\"center\">";
                    $fecha=explode(":",$fecha1);
                    $fecha_completa=explode(" ",$fecha1);

                    if(strtotime($fecha[0]) < strtotime(date("Y-m-d H")))
                    {
                         $salida.="  <td width='5%'><img src=\"".GetThemePath()."/images/alarma.png\"></td>";
                         if($fecha_completa[0] == date("Y-m-d")) {
                              $fecha2=explode(" ",$fecha1);
                              $fecha2[0] = "HOY";
                         }
                         elseif($fecha_completa[0] == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y")))){
                              $fecha2=explode(" ",$fecha1);
                              $fecha2[0] = "AYER ";
                         }
                         elseif($fecha_completa[0] == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")+1), date("Y")))){
                              $fecha2=explode(" ",$fecha1);
                              $fecha2[0] = "MAÑANA ";
                         }
                         else
                         {
                              $fecha2=explode(" ",$fecha1);
                         }
                         $salida.="  <td width='7%'><font color='#C04237'>$fecha2[0]</font></td>";
                         $salida.="  <td width='7%'><font color='#C04237'>$fecha2[1]</font></td>";
                    }
                    if(strtotime($fecha[0]) == strtotime(date("Y-m-d H")))
                    {
                         if($fecha_completa[0] == date("Y-m-d")) {
                              $fecha2=explode(" ",$fecha1);
                              $fecha2[0] = "HOY";
                         }
                         elseif($fecha_completa[0] == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y")))){
                              $fecha2=explode(" ",$fecha1);
                              $fecha2[0] = "AYER ";
                         }
                         elseif($fecha_completa[0] == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")+1), date("Y")))){
                              $fecha2=explode(" ",$fecha1);
                              $fecha2[0] = "MAÑANA ";
                         }
                         else
                         {
                              $fecha2=explode(" ",$fecha1);
                         }
                         $salida.="  <td width='5%'><img src=\"".GetThemePath()."/images/alarma.png\"></td>";
                         $salida.="  <td width='7%'><font color='#36C014'>$fecha2[0]</font></td>";
                         $salida.="  <td width='7%'><font color='#36C014'>$fecha2[1]</font></td>";
                    }
                    if(strtotime($fecha[0]) > strtotime(date("Y-m-d H")))
                    {
                         if($fecha_completa[0] == date("Y-m-d")) {
                              $fecha2=explode(" ",$fecha1);
                              $fecha2[0] = "HOY";
                         }
                         elseif($fecha_completa[0] == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y")))){
                              $fecha2=explode(" ",$fecha1);
                              $fecha2[0] = "AYER ";
                         }
                         elseif($fecha_completa[0] == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")+1), date("Y")))){
                              $fecha2=explode(" ",$fecha1);
                              $fecha2[0] = "MAÑANA ";
                         }
                         else
                         {
                              $fecha2=explode(" ",$fecha1);
                         }
                         $salida.="  <td width='5%'><img src=\"".GetThemePath()."/images/fecha_fin.png\"></td>";
                         $salida.="  <td width='7%'><font color='#002575'>$fecha2[0]</font></td>";
                         $salida.="  <td width='7%'><font color='#002575'>$fecha2[1]</font></td>";
                    }
                    
                    $count=$this->RevisarAyunoProgramacion($ingreso,$dats[$i][fecha]);
                    if($count >0 AND $dats[$i][sw_ayuno]==1){$ayuno='checkS.gif';}else{$ayuno='checkN.gif';}
                    $salida.="  <td width='2%' align=\"center\" ><img src=\"".GetThemePath()."/images/$ayuno\"></td>";

				$desc=str_replace("Observacion:","&nbsp;--&nbsp;Observacion :",$desc);
                    $desc=str_replace("Actividad:","&nbsp;--&nbsp;Actividad:",$desc);
                    $desc=str_replace("Actividad Glucometria:","$img<label class=label_mark>Actividad Glucometria :</label>",$desc);
                    $desc=str_replace("Actividad Neurologico:","$img<label class=label_mark>Actividad Neurologico :</label>",$desc);

                    $salida.="  <td width='90%' align=\"left\" >$desc</td>";
                    if(UserGetUID()==0)
                    {
                         $salida.="  <td>Cumplir</td>";
                    }
                    else
                    {
                         $salida.="  <td><a href=".ModuloGetURL('app','EE_ControlesPacientes_APD','user','CumplirProgramacion',array("ingreso"=>$ingreso,"id"=>$dats[$i][hc_control_pend_id],'datos_estacion'=>$estacion,'datosPaciente'=>$datoscenso)).">Cumplir</a></td>";
                    }

                    $salida.="</tr>";
               }
               $salida.="</table>";
          }
          return $salida;
     }
   
     /*
     * Funcion que carga la forma para las solicitudes de APD.
     */
     function FrmSolicitudE($datosPaciente,$datos_estacion)
     {
          $mostrar ="\n<script language='javascript'>\n";
          $mostrar.="  function borrado(nom){\n";
          $mostrar.="  document.formabuscar.obs.value=''\n";
	     $mostrar.="  };\n";
          $mostrar .="\n</script>\n";
          $this->salida.= ThemeAbrirTabla('SOLICITUD DE EXAMENES');
          $accionT = ModuloGetURL('app','EE_ControlesPacientes_APD','user','InsertarControlE',array("control_descripcion"=>"CONTROLES DE APOYOS DIAGNOSTICOS PENDIENTES","datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente));
          $this->salida.=$mostrar;
          $this->salida .= "<form name=\"formabuscar\" action=\"$accionT\" method=\"post\">";
          $this->salida .= "<br><table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_title\">\n";
          $this->salida .= "	<tr class=\"modulo_table_title\">\n";
          if(!empty($datosPaciente[cama]))
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

          if(empty($_REQUEST['fech'])){$_REQUEST['fech']=date("d-m-Y");}
          $this->salida .= "<br><table  border=\"1\" class=\"modulo_table_list\" width=\"80%\" align=\"center\" >";
          $this->salida .= "				       <tr  class=\"modulo_list_claro\"><td align=\"left\" class=\"".$this->SetStyle("fechac")."\">FECHA: </td><td align=\"left\"><input type=\"text\" class=\"input-text\" name=\"fech\" size='11' maxlength=\"10\" value=\"".$_REQUEST['fech']."\">".ReturnOpenCalendario('formabuscar','fech','-')."</td>";
          $this->salida .= " <td>HORA/MINUTO</td>";
          $this->salida .= " <td><select name=\"hora\" class=\"select\">";
          for($i=0;$i<24;$i++)
          {
		     if($i<10){$a=0;}else{$a='';}
               if($a.$i==$_REQUEST['hora'])
               {
                    $this->salida .=" <option value=$a$i selected>$a$i</option>";
               }
               else
               {
                    $this->salida .=" <option value=$a$i>$a$i</option>";
               }
          }
          $this->salida .= "  </select>";
          $this->salida .= " &nbsp;&nbsp;<select name=\"min\" class=\"select\">";
          for($i=0;$i<60;$i++)
          {
               if($i<10){$a=0;}else{$a='';}
               if($a.$i==$_REQUEST['min'])
               {
                    $this->salida .=" <option value=$a$i selected>$a$i</option>";
               }
               else
               {
                    $this->salida .=" <option value=$a$i>$a$i</option>";
               }
          }
          $this->salida .= "  </select></td>";

          if($_REQUEST['ayuno']==on)
          {	$check='checked';	}else{$check='';	}

          $this->salida .= " <td align='center'><label class='label_mark'>AYUNO</label>&nbsp;<input type='checkbox' name='ayuno' $check></td>";

          $this->salida .= " </tr>";
          $this->salida .= " <tr>";
          $this->salida .= " <td colspan='4'>ACTIVIDAD:&nbsp;<textarea style=width:100% name=obs cols='30' rows='20'>".$_REQUEST['obs']."</textarea></td>";
          $this->salida .= " <td align='center'><input type='submit'class=\"input-submit\" name='guarda' value='GUARDAR' ></form>&nbsp;&nbsp;<input type='button'class=\"input-submit\" name='borrar' value='BORRAR' onclick=borrado(this.name)></td>";
          $this->salida .= " </tr>";
          $this->salida .= " </table><br>";

          $href = ModuloGetURL('app','EE_ControlesPacientes_APD','user','CallControlesPacientes',array("datos_estacion"=>$datos_estacion,'datosPaciente'=>$datosPaciente));
          $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>VOLVER</a><br>\n";
          $this->salida.= ThemeCerrarTabla();
          return true;
     }
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     /*
     *	FrmSignosVitales
     *	Formulario que permite ingresar los signos vitales al paciente seleccioado
     *
     *	@Author Tizziano Perea O.
     *	@access Private
     *	@param array datos del paciente
     *	@param array datos de la estacion
     *	@return boolean
     */
     //FrmSignosVitales(datos_estacion,$datosPaciente,$cantidad,$referer_name,$referer_parameters)
     function FrmSignosVitales($datos_estacion,$datosPaciente,$control)
     {
          $href = ModuloGetURL('app','EE_ControlesPacientes','user','InsertarSignosVitales',array("datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,"control"=>$control,"referer_name"=>$referer_name,"referer_parameters"=>$referer_parameters));
          $this->salida .= "<form name='signos_vitales' action='".$href."' method='POST'><br>\n";
          $this->salida .= "	<table align=\"center\" width=\"88%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list_title\">\n";
          $this->salida .= "		<tr class=\"modulo_table_title\">\n";
          $this->salida .= "			<td>PACIENTE</td>\n";
          $this->salida .= "			<td>HABITACION</td>\n";
          $this->salida .= "			<td>CAMA</td>\n";
          $this->salida .= "			<td>FECHA CONTROL</td>\n";
          $this->salida .= "		</tr>\n";
          $this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
          $this->salida .= "			<td>".$datosPaciente[nombre_completo]."</td>\n";
          $this->salida .= "			<td>".$datosPaciente[pieza]."</td>\n";
          $this->salida .= "			<td>".$datosPaciente[cama]."</td>\n";
          $this->salida .= "			<td>\n";
          //Seleccion de la Hora de la toma del Signo Vital.
          $hora_inicio_turno = ModuloGetVar('app','EstacionEnfermeria','HoraInicioTurnoControles');
          $rango_turno = ModuloGetVar('app','EstacionEnfermeria','RangoVerTurnosControles');
          
          $hora_inicio_turno = "00:00:00";			
          if(date("H:i:s") <= $hora_inicio_turno)
          {
               list($fecha_control,$hora_control)=explode(" ",date("Y-m-d H:i:s"));
               list($h,$m,$s)=explode(":",$hora_control);
          }
          else
          {//si la hora actual es menor que la de inicio turno, se debe poner la fecha anterior
               list($fecha_control,$hora_control)=explode(" ",date("Y-m-d H:i:s",mktime(date("h"),date("i"),date("s"),date("m"),(date("d")-1),date("Y"))));
               list($h,$m,$s)=explode(":",$hora_control);
          }

          $i=0;
          $this->salida.= "<select name='selectHora' class='select'>\n";
          for($j=0; $j<$rango_turno; $j++)
          {
               list($anno, $mes, $dia)=explode("-",$fecha_control);
               if ($i==23)
               {
                    list($h,$m,$s)=explode(":",$hora_inicio_turno);
                    $i=date("H",mktime($h+$j,$m,$s,$mes,$dia,$anno));
                    $fecha2=date("Y-m-d H:i:s",mktime(24,0,0,$mes,$dia,$anno));
                    $fecha_control=date("Y-m-d",mktime(24,0,0,$mes,$dia,$anno));
               }
               else
               {
                    list($h,$m,$s)=explode(":",$hora_inicio_turno);
                    $i=date("H",mktime($h+$j,$m,$s,$mes,$dia,$anno));
                    $fecha2=date("Y-m-d H:i:s",mktime($i,0,0,$mes,$dia,$anno));
                    $fecha_control=date("Y-m-d",mktime($i,0,0,$mes,$dia,$anno));
               }
               if(empty($selectHora)){
                    if($i == date("H")){ $selected = "selected='true'";} else { $selected = "";}
               }
               else
               {//viene de insertar pero no lo hizo, asi que pongo por defecto los valores enviados
                    list($A,$B) = explode(" ",$selectHora);
                    if($i == $B){ $selected = "selected='true'";} else { $selected = "";}
               }
               #################################################
               list($yy,$mm,$dd)=explode(" ",$fecha_control);//(date("m"),(date("d")),date("Y")));
               if($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")),date("Y"))))){
                    $show = "Hoy a las";
               }
               elseif($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")+1),date("Y"))))){
                    $show = "Mañana a las";
               }
               elseif($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")-1),date("Y"))))){
                    $show = "Ayer a las";
               }
               else{
                    $show = $fecha_control;
               }
               ###########################
               $this->salida .= "<option value='".date("Y-m-d")." ".$i."' $selected>".$i."</option>\n";
         }//fin for
          
          if(!empty($_REQUEST['selectHora']))
          {
               $horas_R = explode(" ", $_REQUEST['selectHora']);
               $this->salida .= "<option value='".date("Y-m-d")." ".$horas_R[1]."' selected='true'>".$horas_R[1]."</option>\n";
          }
          $this->salida.= "</select>:&nbsp;\n";
          $this->salida.= "<select name='selectMinutos' class='select'>\n";
          for($j=0; $j<=59; $j++)
          {
               if(empty($selectMinutos)){
                    if($j == date("i")){ $selected = "selected='true'";} else { $selected = "";}
               }
               else
               {//viene de insertar pero no lo hizo, asi que pongo por defecto los valores enviados
                    list($A,$B) = explode(" ",$selectMinutos);
                    if($j == $A){ $selected = "selected='true'";} else { $selected = "";}
               }
               if ($j<10){
                    $this->salida .= "			<option value='0$j:00' $selected>0$j</option>\n";
               }
               else{
                    $this->salida .= "			<option value='$j:00' $selected>$j</option>\n";
               }
          }
          $this->salida .= "</select>\n";
          $this->salida .= "</td>\n";
          $this->salida .= "</tr>\n";
          $this->salida .= "</table><br><br>\n";
     	/*-------------------------------------------
               Segemento que imprime en pantalla
               los Signos Vitales que se tomaran al paciente.
          -------------------------------------------
          */
          $this->salida .= "<table align=\"center\">";
          $this->salida .= $this->SetStyle("MensajeError",11);
          $this->salida .= "</table>";
          $this->salida .= "<table align=\"center\" width=\"88%\" border=\"0\" class=\"modulo_table_list\">\n";
          $this->salida .= "<tr class=\"modulo_table_list_title\">\n";
          $this->salida .= "<td align=\"center\" >FREC. CARD.</td>\n";
          $this->salida .= "<td align=\"center\" >FREC. RESP.</td>\n";
          $this->salida .= "<td align=\"center\" >PVC</td>\n";
          $this->salida .= "<td align=\"center\" >PIC</td>\n";
          $this->salida .= "<td align=\"center\" >PESO</td>\n";
          $this->salida .= "<td align=\"center\">TEMP.</td>\n";
          $this->salida .= "<td align=\"center\">MANUAL</td>\n";
          $this->salida .= "<td  align=\"center\">T.INCUB</td>\n";
          $this->salida .= "<td  align=\"center\">SAT O<sub>2</sub></td>\n";
          $this->salida .= "</tr>\n";
          $this->salida .= "<tr ".$this->Lista(1).">\n";
          $this->salida .= "<td align=\"center\"><input type='text' class='input-text' name='fc' value='".$_REQUEST['fc']."' size='6' maxlength='5'> X min.</td>\n";
          $this->salida .= "<td align=\"center\"><input type='text' class='input-text' name='fr' value='".$_REQUEST['fr']."' size='6' maxlength='5'> X min.</td>\n";
          $this->salida .= "<td align=\"center\"><input type='text' class='input-text' name='pvc' value='".$_REQUEST['pvc']."' size='6' maxlength='6'> cmH<sub>2</sub>O</td>\n";
          $this->salida .= "<td align=\"center\"><input type='text' class='input-text' name='pic' value='".$_REQUEST['pic']."' size='6' maxlength='6'> cmH<sub>2</sub>O</td>\n";
          $this->salida .= "<td align=\"center\"><input type='text' class='input-text' name='peso' value='".$_REQUEST['peso']."' size='6' maxlength='6'> Kg.</td>\n";
          $this->salida .= "<td align='center'><input type='text' class='input-text' name='tpiel' value='".$_REQUEST['tpiel']."' size='6' maxlength='5'> ºC</td>\n";
          $this->salida .= "<td align='center'><input type='text' class='input-text' name='manual' value='".$_REQUEST['manual']."' size='6' maxlength='6'> ºC</td>\n";
          $this->salida .= "<td align='center'><input type='text' class='input-text' name='servo' value='".$_REQUEST['servo']."' size='6' maxlength='6'> ºC</td>\n";
          $this->salida .= "<td align='center'><input type='text' class='input-text' name='sato' value='".$_REQUEST['sato']."' size='6' maxlength='3'> %</td>\n";
          $this->salida .= "</tr>\n";
          $this->salida .= "</table>\n\n";
          /*-------------------------------------------
               Segemento que imprime en pantalla
               los Signos Vitales que se tomaran al paciente.
          -------------------------------------------
          */
		$sitios=$this->GetSignosVitalesSitios();
          $this->salida .= "<table colspan=\"2\" align=\"center\" width=\"88%\" border=\"0\" class=\"modulo_table_list\">\n";
          $this->salida .= "<tr align='center' class='modulo_table_list_title'>\n";
          $this->salida .= "<td width=\"50%\">TENSION ARTERIAL</td>\n";
          $this->salida .= "<td width=\"50%\">OBSERVACION</td>\n";
          $this->salida .= "</tr>\n";
          $this->salida .= "<tr class=\"modulo_list_claro\">\n";
          $this->salida .= "<td width=\"50%\">";
          $this->salida .= "<label class=\"label\">&nbsp;T.A</label>&nbsp;&nbsp;<input type=\"text\" class='input-text' name=\"taa\" value='".$_REQUEST['taa']."' size='6' maxlength='5'>&nbsp;<b>/</b>&nbsp;
          <input type=\"text\" class='input-text' name=\"tab\" value='".$_REQUEST['tab']."' size='6' maxlength='5'>";
          $this->salida .= "<label class=\"label\">&nbsp;&nbsp;&nbsp;SITIO</label>";

          if (!empty($sitios)) {
               $this->salida .="&nbsp;<select name=\"sitio\" class='select'>";//rowspan='3'
               $this->salida .="<option value=-1>- - - -</option>";
               $this->SetOptionsSignosVitalesSitios($sitios,$_REQUEST['sitio']);
               $this->salida .="</select>\n";
          }
          $this->salida .= "<table colspan=\"2\" align=\"center\" width=\"100%\" border=\"1\" class=\"modulo_table_list\">\n";
          $this->salida .="<tr align=\"center\"><td colspan=\"12\" class='modulo_table_list_title'>ESCALA VISUAL ANALOGA - EVA</td></tr>";
          $this->salida .="<tr align=\"center\">";
          $this->salida .="<td rowspan=\"2\">Menor Dolor</td>";
          $fecha_nac=$this->GetFechaNacPaciente($datosPaciente[ingreso]);
          $FechaFin = date("Y-m-d");
          $edad_paciente = CalcularEdad($fecha_nac,$FechaFin);
          if ($edad_paciente[anos] < ModuloGetVar('','','max_edad_pediatrica'))
          {
               $this->salida .="<td><img src=\"".GetThemePath()."/images/signovital/no_dolor.png\" border=0></td>";
               $this->salida .="<td><img src=\"".GetThemePath()."/images/signovital/leve.png\" border=0></td>";
               $this->salida .="<td><img src=\"".GetThemePath()."/images/signovital/moderado.png\" border=0></td>";
               $this->salida .="<td><img src=\"".GetThemePath()."/images/signovital/severopain.png\" border=0></td>";
               $this->salida .="<td><img src=\"".GetThemePath()."/images/signovital/muyseveropain.png\" border=0></td>";
               $this->salida .="<td rowspan=\"2\">Mayor Dolor</td>";
               $this->salida .="</tr>";
               $this->salida .="<tr>";

               if ($_REQUEST['eva'] != 0 )
               {
                    $this->salida .="<td align=\"center\"><input type=\"radio\" name=\"eva\" value=\"0\"></td>";
               }
               else
               {
                    $this->salida .="<td align=\"center\"><input type=\"radio\" checked name=\"eva\" value=\"0\"></td>";
               }
               if ($_REQUEST['eva'] != 1 )
               {
                    $this->salida .="<td align=\"center\"><input type=\"radio\" name=\"eva\" value=\"1\"></td>";
               }
               else
               {
                    $this->salida .="<td align=\"center\"><input type=\"radio\" checked name=\"eva\" value=\"1\"></td>";
               }
               if ($_REQUEST['eva'] != 2 )
               {
                    $this->salida .="<td align=\"center\"><input type=\"radio\" name=\"eva\" value=\"2\"></td>";
               }
               else
               {
                    $this->salida .="<td align=\"center\"><input type=\"radio\" checked name=\"eva\" value=\"2\"></td>";
               }

               if ($_REQUEST['eva'] != 3 )
               {
                    $this->salida .="<td align=\"center\"><input type=\"radio\" name=\"eva\" value=\"3\"></td>";
               }
               else
               {
                    $this->salida .="<td align=\"center\"><input type=\"radio\" checked name=\"eva\" value=\"3\"></td>";
               }

               if ($_REQUEST['eva'] != 4 )
               {
                    $this->salida .="<td align=\"center\"><input type=\"radio\" name=\"eva\" value=\"4\"></td>";
               }
               else
               {
                    $this->salida .="<td align=\"center\"><input type=\"radio\" checked name=\"eva\" value=\"4\"></td>";
               }
          }
          else
          {
               $this->salida .="<td>1</td>";
               $this->salida .="<td>2</td>";
               $this->salida .="<td>3</td>";
               $this->salida .="<td>4</td>";
               $this->salida .="<td>5</td>";
               $this->salida .="<td>6</td>";
               $this->salida .="<td>7</td>";
               $this->salida .="<td>8</td>";
               $this->salida .="<td>9</td>";
               $this->salida .="<td>10</td>";
               $this->salida .="<td rowspan=\"2\">Mayor Dolor</td>";

               $this->salida .="</tr>";
               $this->salida .="<tr>";
               if ($_REQUEST['eva'] != 1 )
               {
                    $this->salida .="<td><input type=\"radio\" name=\"eva\" value=\"1\"></td>";
               }
               else
               {
                    $this->salida .="<td><input type=\"radio\" checked name=\"eva\" value=\"1\"></td>";
               }
               if ($_REQUEST['eva'] != 2 )
               {
                    $this->salida .="<td><input type=\"radio\" name=\"eva\" value=\"2\"></td>";
               }
               else
               {
                    $this->salida .="<td><input type=\"radio\" checked name=\"eva\" value=\"2\"></td>";
               }
               if ($_REQUEST['eva'] != 3 )
               {
                    $this->salida .="<td><input type=\"radio\" name=\"eva\" value=\"3\"></td>";
               }
               else
               {
                    $this->salida .="<td><input type=\"radio\" checked name=\"eva\" value=\"3\"></td>";
               }

               if ($_REQUEST['eva'] != 4)
               {
                    $this->salida .="<td><input type=\"radio\" name=\"eva\" value=\"4\"></td>";
               }
               else
               {
                    $this->salida .="<td><input type=\"radio\" checked name=\"eva\" value=\"4\"></td>";
               }

               if ($_REQUEST['eva'] != 5 )
               {
                    $this->salida .="<td><input type=\"radio\" name=\"eva\" value=\"5\"></td>";
               }
               else
               {
                    $this->salida .="<td><input type=\"radio\" checked name=\"eva\" value=\"5\"></td>";
               }

               if ($_REQUEST['eva'] != 6 )
               {
                    $this->salida .="<td><input type=\"radio\" name=\"eva\" value=\"6\"></td>";
               }
               else
               {
                    $this->salida .="<td><input type=\"radio\" checked name=\"eva\" value=\"6\"></td>";
               }

               if ($_REQUEST['eva'] != 7 )
               {
                    $this->salida .="<td><input type=\"radio\" name=\"eva\" value=\"7\"></td>";
               }
               else
               {
                    $this->salida .="<td><input type=\"radio\" checked name=\"eva\" value=\"7\"></td>";
               }

               if ($_REQUEST['eva'] != 8 )
               {
                    $this->salida .="<td><input type=\"radio\" name=\"eva\" value=\"8\"></td>";
               }
               else
               {
                    $this->salida .="<td><input type=\"radio\" checked name=\"eva\" value=\"8\"></td>";
               }

               if ($_REQUEST['eva'] != 9 )
               {
                    $this->salida .="<td><input type=\"radio\" name=\"eva\" value=\"9\"></td>";
               }
               else
               {
                    $this->salida .="<td><input type=\"radio\" checked name=\"eva\" value=\"9\"></td>";
               }

               if ($_REQUEST['eva'] != 10 )
               {
                    $this->salida .="<td><input type=\"radio\" name=\"eva\" value=\"10\"></td>";
               }
               else
               {
                    $this->salida .="<td><input type=\"radio\" checked name=\"eva\" value=\"10\"></td>";
               }
          }
          $this->salida .="</tr></table>";
          $this->salida .= "</td>\n";

          $this->salida .= "<td width=\"50%\" align='center'>\n";
          $this->salida .= "<textarea name=\"observacion\" cols=\"50\" rows=\"4\" class=\"textarea\">".$_REQUEST['observacion']."</textarea>";
          $this->salida .= "<br><br><input type='submit' class='input-submit' name='Save' value='Insertar'>";
          $this->salida .= "</td>\n";
          $this->salida .= "</tr>\n";
          $this->salida .= "</table><br><br>\n";
          $this->salida .= "<input type='hidden' name='ingreso' value='".$datosPaciente['ingreso']."'>\n";
          $this->salida .= "</form>\n";
          $this->ShowSignosVitales($datos_estacion,$datosPaciente,0,$control);
          $this->FrmPieDePagina();
          return true;
     }

	/*
     *	SetOptionsSignosVitalesSitios
     *
     *	@Author Tizziano Perea.
     *	@access Private
     */
     function SetOptionsSignosVitalesSitios($sitio,$valor)
     {
          for($i=0; $i<sizeof($sitio); $i++)
          {
               if ($sitio[$i]['sitio_id']==$valor)
                    $this->salida .= "<option value='".$sitio[$i]['sitio_id']."' selected>".$sitio[$i]['descripcion']."</option>\n";
               else
                    $this->salida .= "<option value='".$sitio[$i]['sitio_id']."'>".$sitio[$i]['descripcion']."</option>\n";
          }
          return true;
     }
          
     /*
     *	ShowSignosVitales
     *	Muestra los signos vitales registrados del paciente seleccionado
     *	@Author Rosa Maria Angel
     *	@access Private
     *	@param array datos del paciente
     *	@param array datos de la estacion
     *	@param integer numero de filas a mostrar
     *	@return boolean
     */
     function ShowSignosVitales($datos_estacion,$datosPaciente,$contador,$control)
     {
          $vectorSignos = $this->GetSignosVitales($datosPaciente['ingreso']);  
          if(!$vectorSignos){
               return false;
          }
          elseif($vectorSignos != "ShowMensaje")
          {
               if (empty($contador)){
                    $contador=sizeof($vectorSignos);
               }
               $this->salida .= "<br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
               $this->salida .= "<tr class=\"modulo_table_list_title\">\n";
               $this->salida .= "<td>FECHA</td>\n";
               $this->salida .= "<td>HORA</td>\n";
               $this->salida .= "<td>F.C.</td>\n";
               $this->salida .= "<td>F.R.</td>\n";
               $this->salida .= "<td>PVC</td>\n";
               $this->salida .= "<td>PIC</td>\n";
               $this->salida .= "<td>PESO (Kg)</td>\n";
               $this->salida .= "<td>T.A.</td>\n";
               $this->salida .= "<td>MEDIA</td>\n";
               $this->salida .= "<td>SITIO TOMA T.A</td>\n";
               $this->salida .= "<td>TEMP.</td>\n";
               $this->salida .= "<td>T. INC</td>\n";
               $this->salida .= "<td>MANUAL</td>\n";
               $this->salida .= "<td>EVA</td>\n";
               $this->salida .= "<td>SAT O2</td>\n";
               $this->salida .= "<td>USUARIO</td>\n";
               $this->salida .= "</tr>\n";
               $cont=1;
               
               while ($cont <= sizeof($vectorSignos) && $cont <= $contador)
               {
                    list($fecha,$hora) = explode(" ",$vectorSignos[$cont-1][fecha]);
                    $this->salida .= "<tr ".$this->Lista($cont)."' align='center'>\n";
                    if($fecha == date("Y-m-d")) {
                         $fecha = "HOY $hora";
                    }
                    elseif($fecha == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y")))){
                         $fecha = "AYER $hora";
                    }
                    else {
                         $fecha = $fecha;
                    }
                    //---------------Alerta de temperatura
                    if (!IncludeLib('datospaciente')){
                         $this->error = "Error al cargar la libreria [datospaciente].";
                         $this->mensajeDeError = "datospaciente";
                         return false;
                    }
                    $x = GetDatosPaciente("","",$datosPaciente['ingreso']);//funcion del api realizada por jaime
                    $Edad = CalcularEdad($x[fecha_nacimiento],'');
                    list($Edad,$k) = explode(" ",$Edad[edad_aprox]);
                    //temperatura es 20;
                    $k = $this->GetAlarmaRangoControl(20,$x[sexo_id],$Edad,$vectorSignos[$cont-1][temp_piel]);
                    if($k === "Alarma"){$estilo = "class='alerta'";} else {$estilo = "";}
                    //---------------fin Alerta de temperatura
                    
                    //------- valido si estan en ceros que pongan "--";
                    if($vectorSignos[$cont-1][fc] == 0) $fc = "--"; else $fc = $vectorSignos[$cont-1][fc];
                    if($vectorSignos[$cont-1][fr] == 0) $fr = "--"; else $fr = $vectorSignos[$cont-1][fr];
                    $fecha_nac=$this->GetFechaNacPaciente($datosPaciente[ingreso]);
                    $FechaFin = date("Y-m-d");
                    $edad_paciente = CalcularEdad($fecha_nac,$FechaFin);
                    if ($edad_paciente[anos] < ModuloGetVar('','','max_edad_pediatrica'))
                    {
                         if($vectorSignos[$cont-1][evaluacion_dolor] == 0) $eva = "0"; else $eva = $vectorSignos[$cont-1][evaluacion_dolor];
                    }
                    else
                    {
                         if($vectorSignos[$cont-1][evaluacion_dolor] == 0) $eva = "--"; else $eva = $vectorSignos[$cont-1][evaluacion_dolor];
                    }
                    if($vectorSignos[$cont-1][pvc] == 0.00) $pvc = "--"; else $pvc = $vectorSignos[$cont-1][pvc];

                    if($vectorSignos[$cont-1][ta_alta] == 0.00)
                    {$taa = "--";}
                    else {$ta_alta = $vectorSignos[$cont-1][ta_alta];}

                    if($vectorSignos[$cont-1][ta_baja] == 0.00)
                    {$taa = "--";}
                    else {$ta_baja = $vectorSignos[$cont-1][ta_baja];}

                    if($ta_alta AND $ta_baja)
                    {$taa=$ta_alta."/".$ta_baja;}

                    if($vectorSignos[$cont-1][media] == 0) $media = "--"; else $media = $vectorSignos[$cont-1][media];
                    if($vectorSignos[$cont-1][sato2] == 0) $sato = "--"; else $sato = $vectorSignos[$cont-1][sato2];
                    if(empty($vectorSignos[$cont-1][descripcion])) $descripcion = "--"; else $descripcion = $vectorSignos[$cont-1][descripcion];
                    if($vectorSignos[$cont-1][temp_piel] == 0) $temp = "--"; else $temp = $vectorSignos[$cont-1][temp_piel];
                    if($vectorSignos[$cont-1][servo] == 0.00) $servo = "--"; else $servo = $vectorSignos[$cont-1][servo];
                    if($vectorSignos[$cont-1][manual] == 0.00) $manual = "--"; else $manual = $vectorSignos[$cont-1][manual];
                    if($vectorSignos[$cont-1][presion_intracraneana] == 0) $presion = "--"; else $presion = $vectorSignos[$cont-1][presion_intracraneana];
                    if($vectorSignos[$cont-1][peso] == 0.000) $peso = "--"; else $peso = number_format($vectorSignos[$cont-1][peso],2,',','.');
                    if($vectorSignos[$cont-1][sitio_id]=='' OR is_null($vectorSignos[$cont-1][sitio_id])){$sit='--';}else{$sit=$vectorSignos[$cont-1][sitio_id];}
                    //-------fin valido si estan en ceros que pongan "--";
                    if($sit <> '' and $sit <> '--')
                    {
                         $sitio=$this->GetSignosVitalesSitios($sit);
                    }
                    unset($sit);
                    //preguntamos si es invasiva=1 o no invasiva=0
                    $this->salida .= "<td>".$fecha."</td>\n";
                    $this->salida .= "<td>".$hora."</td>\n";
                    $this->salida .= "<td>".$fc."</td>\n";
                    $this->salida .= "<td>".$fr."</td>\n";
                    $this->salida .= "<td>".$pvc."</td>\n";
                    $this->salida .= "<td>".$presion."</td>\n";
                    $this->salida .= "<td>".$peso."</td>\n";
                    $this->salida .= "<td>".$taa."</td>\n";
                    $this->salida .= "<td>".$media."</td>\n";
                    $this->salida .= "<td>".$sitio[0][descripcion]."</td>\n";
                    $this->salida .= "<td $estilo>".$temp."</td>\n";
                    $this->salida .= "<td>".$servo."</td>\n";
                    $this->salida .= "<td>".$manual."</td>\n";
                    $this->salida .= "<td>".$eva."</td>\n";
                    $this->salida .= "<td>".$sato."</td>\n";
                    $min='15';
                    $DatosUser = $this->GetDatosUsuarioSistema($vectorSignos[$cont-1][usuario_id]);
                    $fechita=explode(" ",$vectorSignos[$cont-1][fecha]);
                    if($vectorSignos[$cont-1][usuario_id]==UserGetUID()
                    AND $fechita[0]==date("Y-m-d") AND $datosPaciente[ingreso]==$vectorSignos[$cont-1][ingreso])
                    {
                         list($fechaReh,$horaReg) = explode(" ",$vectorSignos[$cont-1][fecha_registro]);
                         $new_hora=date("H:i:s",strtotime("+".$min." min",strtotime($horaReg)));

                         if(strtotime($new_hora) > strtotime(date("H:i:s")))
                         {
                              $href = ModuloGetURL('app','EE_ControlesPacientes','user','BorradoSignosVitales',
                              array("fecha"=>$vectorSignos[$cont-1][fecha],"datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,"contador"=>$contador,"control"=>$control));
                              $nombre=$link_eliminar="&nbsp;<a href='$href'>[Eliminar]</a>";
                         }
                         else{$nombre=$DatosUser[0][usuario];}
                    }
                    else{$nombre=$DatosUser[0][usuario];}
                    $this->salida .= "			<td>$nombre</td>\n";
                    $this->salida .= "		</tr>\n";
		          if($vectorSignos[$cont-1][observacion]!='' AND $vectorSignos[$cont-1][observacion] !='NULL')
                    {
                         $observacion = $vectorSignos[$cont-1][observacion];
                         $this->salida .= "<tr ".$this->Lista($cont)."'>\n";
                         $this->salida .= "<td class=\"modulo_table_title\">OBSERVACION</td>\n";
                         $this->salida .= "<td colspan=\"15\">".$observacion."</td>\n";
                         $this->salida .= "</tr>\n";
                    }
                    $cont++;
               }
               $this->salida .= "		</tr>\n";
               $this->salida .= "	</table>\n\n";
               return true;
          }
     }//ShowSignosVitales

     /*
     *	FrmFrecuenciaControlesP
     *	Aqui se manejan aquellos controles que son programados.
     *	@Author Jairo Duvan Diaz Martinez.
     *	@access Private
     */
     function FrmFrecuenciaControlesP($datos_estacion,$datosPaciente,$control,$descripcion,$idControl,$href_action_hora,$href_action_control)
     {
          $hora_inicio_turno=ModuloGetVar('app','EstacionEnfermeria','HoraInicioTurnoControles');
          $rango_turno=ModuloGetVar('app','EstacionEnfermeria','RangoVerTurnosControles');

          unset($_SESSION['ESTACION']['DIRECCION']['URL']);
          $_SESSION['ESTACION']['DIRECCION']['URL']=$href_action_hora;//url a donde se va a dirigir al tocar la fecha.

          if($ingreso_id OR $_SESSION['ESTACION_CONTROL']['INGRESO'])
          {
               $_SESSION['ESTACION_CONTROL']['INGRESO']=$ingreso_id;//colocamos el id para q filtre el pac con las fechas.
          }
          if(empty($href_action_hora))
          {
                    $href_action_hora=$_SESSION['ESTACION_CONTROL']['URL_EXAMEN'];
          }
          else
          {
                    $_SESSION['ESTACION_CONTROL']['URL_EXAMEN']=$href_action_hora;
          }
          if(!empty($datosPaciente))
          {
               $this->salida .= "<br><table align=\"center\" width=\"88%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
               $this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
               $this->salida .= "		<td>HABITACION</td>\n";
               $this->salida .= "		<td>CAMA</td>\n";
               $this->salida .= "		<td>PACIENTE</td>\n";
               $this->salida .= "		<td>HORARIO</td>\n";
               $this->salida .= "		<td>ACCI&Oacute;N</td>\n";
               $this->salida .= "	</tr>\n";

               $INGRESAR = $datosPaciente[ingreso];
               $vect_control=array();
               $controles=$this->GetControles($datosPaciente[ingreso]);
               $vect_control=$this->FindControles($controles,$idControl,$datosPaciente[ingreso]);
                              
               if(is_array($controles) && $vect_control['ingreso']==$datosPaciente[ingreso])
               {
                    $next_turno=array();
                    $horas_no_cumplidas=array();
                    $turno_prgdo=array();
                    $turno_fecha_rango=array();
                    $rango15=$rango30=0;
                    $rango=1;
                    $turno_hora="";

                    $horas_no_cumplidas = $this->GetControlesProgramadosNoCumplidos($datos_estacion['estacion_id'],$datosPaciente[ingreso],$idControl);
                    if ($horas_no_cumplidas==="ShowMensaje") {
                         return false;
                    }

                    $next_turno=$this->GetControlesProgramadosSiguientesTurnos($datos_estacion['estacion_id'],$datosPaciente[ingreso],$idControl);
                    if ($next_turno==="ShowMensaje") {
                         return false;
                    }

                    $this->salida .= "<tr ".$this->Lista($i).">\n";
                    if(empty($datosPaciente[cama]))
                    {
                         $this->salida .= "	<td align=\"center\"><label class='label_mark'>No Ingresado</label></td>\n";
                         $this->salida .= "	<td align=\"center\"><label class='label_mark'>No Ingresado</label></td>\n";
                    }
                    else
                    {
                         $this->salida .= "	<td align=\"center\">".$datosPaciente[pieza]."</td>\n";
                         $this->salida .= "	<td align=\"center\">".$datosPaciente[cama]."</td>\n";
                    }	
                    $this->salida .= "	<td>".$datosPaciente[nombre_completo]."</td>\n";
                    $this->salida .= "	<td align=\"center\">";

                    if (empty($horas_no_cumplidas) && empty($next_turno)) {
                              $turno_hora.="--<br>";
                    }

                    for ($j=0;$j<sizeof($horas_no_cumplidas);$j++){
                         $href = ModuloGetURL('app','EE_ControlesPacientes','user',$href_action_hora,array('datosPaciente'=>$datosPaciente,'datos_estacion'=>$datos_estacion,'control'=>$control,'href_action_hora'=>$href_action_hora,'href_action_control'=>$href_action_control,'turno_hora'=>$horas_no_cumplidas[$j]['fecha']));
                         $turno_hora.=	"		<a class='TurnoInactivo' href=\"".$href."\">".$horas_no_cumplidas[$j]['fecha']."</a><br>";
                    }//fin for

                    for ($j=0;$j<sizeof($next_turno);$j++){
                         if (!$j){
                              $href = ModuloGetURL('app','EE_ControlesPacientes','user',$href_action_hora,array('datosPaciente'=>$datosPaciente,'datos_estacion'=>$datos_estacion,'control'=>$control,'href_action_hora'=>$href_action_hora,'href_action_control'=>$href_action_control,'turno_hora'=>$next_turno[$j]['fecha']));
                              $turno_hora.=	"		<a class='TurnoActivo' href=\"".$href."\">".$next_turno[$j]['fecha']."</a><br>";
                         }
                         else{
                              list($fecha,$hora)=explode(" ",$next_turno[$j]['fecha']);
                              $turno_prgdo[]=$hora;
                              list($h,$m,$s)=explode(":",$hora);
                              if ($m==15 || $m==45){
                                   $rango15=1;
                              }
                              elseif ($m==30) {
                                   $rango30=1;
                              }
                              $turno_hora.=$next_turno[$j]['fecha']."<br>";
                              if ($j==1 || $j==(sizeof($next_turno))-1){
                                   $turno_fecha_rango[]=$next_turno[$j]['fecha'];
                              }
                         }
                    }//fin for
                    if ($rango15 && $rango30){
                         $rango=15;
                    }
                    elseif ($rango30){
                         $rango=30;
                    }
                    elseif ($rango15){
                         $rango=15;
                    }
                    else{
                         $rango=1;
                    }
                    $this->salida .= $turno_hora."</td>\n";
                    if (count($turno_fecha_rango)==1){
                         $turno_fecha_rango[]=$turno_fecha_rango[0];
                    }
                    elseif (empty($turno_fecha_rango) || (empty($next_turno) && empty($horas_no_cumplidas) && empty($turno_fecha_rango))){
                         $turno_prgdo[]=date("H").":00:00";
                         $turno_fecha_rango[]=$turno_fecha_rango[0]=date("Y-m-d H").":00:00";
                    }
                    $href = ModuloGetURL('app','EE_ControlesPacientes','user','CallFrmProgramarTurnos',array("href_action_hora"=>$href_action_hora,"href_action_control"=>$href_action_control,"rango"=>$rango,"turno_fecha_rango"=>$turno_fecha_rango,"turnos_prgmar"=>$turno_prgdo,"datos_estacion"=>$datos_estacion,"datosPaciente"=>array("numerodecuenta"=>$datosPaciente['numerodecuenta'],"pieza"=>$datosPaciente['pieza'],"cama"=>$datosPaciente['cama'],"nombre_completo"=>$datosPaciente['nombre_completo'],"paciente_id"=>$datosPaciente['paciente_id'],"tipo_id_paciente"=>$datosPaciente['tipo_id_paciente'],"ingreso"=>$datosPaciente['ingreso'],"control_id"=>$idControl,"control_descripcion"=>$descripcion),'control'=>$control));
                    $this->salida .= "	<td align=\"center\"><a href=\"".$href."\">PROGRAMAR</a>\n";

                    switch ($control)
                    {
                    	case 'Glucometria'://GLUCOMETRIA
                                   $liquidos_diario=array();
                                   $liquidos_diario=$this->GetControlProgramadoGlucometria($datosPaciente[ingreso]);
                                   if ($liquidos_diario==="ShowMensaje"){
                                        return false;
                                   }

                                   if (!empty($liquidos_diario)){
                                        $hrefResumen = ModuloGetURL('app','EE_ControlesPacientes','user',$href_action_control[0],array('datosPaciente'=>$datosPaciente,'datos_estacion'=>$datos_estacion,'control'=>$control,'href_action_hora'=>$href_action_hora,'href_action_control'=>$href_action_control));
                                        $this->salida .= "	<br><a href=\"".$hrefResumen."\">RESUMEN</a></td>\n";
                                   }
                                   else{
                                        $this->salida .= "	<br>RESUMEN</td>\n";
                                   }
                         break;
                         case 'Neurologico'://NEUROLOGICO
                              $liquidos_diario=array();
                              $liquidos_diario=$this->GetControlProgramadoHojaNeurologica($datosPaciente[ingreso]);
                              if ($liquidos_diario==="ShowMensaje"){
                                   return false;
                              }

                              if (!empty($liquidos_diario)){
                                   $hrefResumen = ModuloGetURL('app','EE_ControlesPacientes','user',$href_action_control[0],array('datosPaciente'=>$datosPaciente,'datos_estacion'=>$datos_estacion,'control'=>$control,'href_action_hora'=>$href_action_hora,'href_action_control'=>$href_action_control));
                                   $this->salida .= "	<br><a href=\"".$hrefResumen."\">RESUMEN</a></td>\n";
                              }
                              else{
                                   $this->salida .= "	<br>RESUMEN</td>\n";
                              }
                         break;
                    }
                    $this->salida .= "</tr>\n";
               }			
               $this->salida .= "</table><br>\n";
               $this->FrmPieDePagina();
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               return true;
          }
          else {
               $mensaje = "LA ESTACIÓN [ ".$datos_estacion['estacion_descripcion']." ] NO CUENTA CON PACIENTES.";
               $titulo = "MENSAJE";
     		$link = "PANEL ENFERMERIA";
               $url = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
               $this->frmMSG($url,$titulo,$mensaje,$link);
               return true;
          }
     }

     /*
     *	FrmProgramarTurnos: Pantalla de la programacion de Controles.
     */
     function FrmProgramarTurnos($rango,$datos_estacion,$datosPaciente,$turnos_prgmar,$turno_fecha_rango,$href_action_hora,$href_action_control,$ingreso_id,$control)
     {
         if (!ModuloIncludeLib("app","EstacionEnfermeria","funciones")){
               $this->error = "Error al cargar la libreria de Modulos.";
               $this->mensajeDeError = "funciones";
               return false;
          }

          if(empty($_SESSION['ESTACION']['DIRECCION']['CONTROL']))
          {
               $_SESSION['ESTACION']['DIRECCION']['CONTROL']=$href_action_control;
          }
          $controles=array();
          $turnos="";
          unset($_SESSION['ESTACION']['NOMBRE_CONTROL']);
          //en esta variable de session tenemos $_SESSION['ESTACION']['NOMBRE_CONTROL']
          //tenemos el nombre del control ya que cuando insertamos en hc_agenda_controles
          //tambien insertamos en hc_control_apoyosd_pendientes
          //para que quede registrado como una actividad.
          $_SESSION['ESTACION']['NOMBRE_CONTROL']=$datosPaciente['control_descripcion'];
          $this->salida .= ThemeAbrirTabla($datosPaciente['control_descripcion']." - [ ".$datos_estacion['estacion_descripcion']." ]");
          $href = ModuloGetURL('app','EE_ControlesPacientes','user','CallInsertarAgendaTurnos',array("datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,"turnos_prgmar"=>$turnos_prgmar,"turno_fecha_rango"=>$turno_fecha_rango,"href_action_hora"=>$href_action_hora,"href_action_control"=>$href_action_control,"ingreso"=>$ingreso_id,'control'=>$control));
          $this->salida .= "<form name=\"formaCreaTurnos\" action=\"$href\" method=\"post\"><BR>";
          $this->salida .= "<table align=\"center\" width=\"88%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\"class=\"modulo_table_title\" >\n";
          $this->salida .= "	<tr class=\"modulo_table_title\">\n";
          $this->salida .= "		<td>HABITACION</td>\n";
          $this->salida .= "		<td>CAMA</td>\n";
          $this->salida .= "		<td>PACIENTE</td>\n";
          $this->salida .= "		<td>FECHA PROGRAMACI&Oacute;N</td>\n";
          $this->salida .= "	</tr>\n";
          $this->salida .= "	<tr class='modulo_list_oscuro' ".$this->Lista($i)." align='center'>\n";
          
          if(empty($datosPaciente['cama']))
          {
               $this->salida .= "		<td>No Ingresado</td>\n";
               $this->salida .= "		<td>No Ingresado</td>\n";
          }
          else
          {
               $this->salida .= "		<td>".$datosPaciente['pieza']."</td>\n";
               $this->salida .= "		<td>".$datosPaciente['cama']."</td>\n";
          }	
          $this->salida .= "		<td>".$datosPaciente['nombre_completo']."</td>\n";
          list($Fechita,$Horita) = explode(" ",$turno_fecha_rango[0]);
          $this->salida .= "		<td>".$Fechita."</td>\n";
          $this->salida .= "	</tr>\n";
          $this->salida .= "</table>\n";

          $horas=$this->GetTurnosEstacion($datos_estacion['estacion_id']);
          if ($horas===false){
               return false;
          }

          $hora_inicio_turno=ModuloGetVar('app','EstacionEnfermeria','HoraInicioTurnoControles');
          $rango_turno=ModuloGetVar('app','EstacionEnfermeria','RangoVerTurnosControles');
          $href = ModuloGetURL('app','EE_ControlesPacientes','user','CallFrmProgramarTurnos',array("ingreso"=>$datos_estacion['ingreso'],"rango"=>$rango,"estacion"=>$estacion,"datos_estacion"=>$datos_estacion,"turnos_prgmar"=>$turnos_prgmar,"turno_fecha_rango"=>$turno_fecha_rango,'control'=>$control));

          $turnos=CrearTurnos($href,date("Y-m-d"),true,true,$turnos_prgmar[0],$rango,$rango_turno,$horas,true,$turnos_prgmar,$hora_inicio_turno,$datosPaciente['ingreso'],$datosPaciente['control_id']);

          $this->salida .= "<br><table align=\"center\" width=\"88%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list\">\n";
          $this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
          $this->salida .= "		<td width='48%'>OBSERVACION</td>\n";
          $this->salida .= "		<td width='40%'>TURNOS</td>\n";
          $this->salida .= "	</tr>\n";
          $this->salida .= "	<tr ".$this->Lista(1).">\n";
          $this->salida .= "		<td valign='top'>\n";
          $this->FrmControles(array("ingreso"=>$datosPaciente['ingreso'],"control_id"=>$datosPaciente['control_id']));
          $this->salida .= "		</td>\n";
          $this->salida .= "		<td>$turnos</td>\n";
          $this->salida .= "	</tr>\n";
          $this->salida .= "</table><br>\n";
          $this->salida .= "<input type='hidden' name='estacion_id' value='".$datos_estacion['estacion_id']."'>";
          $this->salida .= "<input type='hidden' name='ingreso_id' value='".$datosPaciente['ingreso']."'>";
          $this->salida .= "<input type='hidden' name='control_id' value='".$datosPaciente['control_id']."'>";
          $this->salida .= "<input type='hidden' name='fecha' value='".date("Y-m-d")."'>";
          $this->salida .= "<div class='normal_10' align='center'><br><br><input type='submit' class='input-submit' name='SaveTurnos' value='GUARDAR TURNOS'>";
          $this->salida .= "</form>\n";

          $href = ModuloGetURL('app','EE_ControlesPacientes','user','CallFrmsControlesPacientes',array('datosPaciente'=>$datosPaciente,"href_action_hora"=>$href_action_hora,"href_action_control"=>$href_action_control,'control'=>$control));
          $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>PROGRAMACION DE CONTROLES</a><br>";

          $this->FrmPieDePagina();
          return true;
     }
          
     /*
     *	FrmControlesNeurologicos
     *	Formulario que permite ingresar los Controles Neurologicos del paciente
     *
     *	@Author Tizziano Perea O.
     *	@access Private
     *	@param array datos del paciente
     *	@param array datos de la estacion
     *	@return boolean
     */
     function FrmControlesNeurologicos()
     {
          $datosPaciente = $_REQUEST['datosPaciente'];
          $datos_estacion = $_REQUEST['datos_estacion'];
          $control = $_REQUEST['control'];
          $href_action_hora = $_REQUEST['href_action_hora'];
          $href_action_control = $_REQUEST['href_action_control'];

          $this->salida = ThemeAbrirTabla('CONTROL DE ESTADO NEUROLOGICO');
          $Tallas = $this->GetTallasPupilas($datosPaciente,$datos_estacion,$control);
          $Reaccion = $this->GetReaccionPupilas($datosPaciente,$datos_estacion,$control);
          $Nivel_Conciencia = $this->GetNivelesConciencia($datosPaciente,$datos_estacion,$control);
          $TiposFuerza = $this->GetTiposFuerza($datosPaciente,$datos_estacion,$control);
          $TipoAperturaOcular = $this->GetTipoAperturaOcular($datosPaciente,$datos_estacion,$control);
          $RespuestaVerbal = $this->GetRespuestaVerbal($datosPaciente,$datos_estacion,$control);
          $RespuestaMotora = $this->GetRespuestaMotora($datosPaciente,$datos_estacion,$control);

          $href = ModuloGetURL('app','EE_ControlesPacientes','user','Insertar_ControlesNeurologicos',array("control"=>$control,"datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,"href_action_hora"=>$href_action_hora,"href_action_control"=>$href_action_control,'turno_hora'=>$_REQUEST['turno_hora']));
          $this->salida .= "<form name=\"Neurologico\"' action='".$href."' method='POST'>";
          
          $this->salida .= "<table align=\"center\" width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_title\">\n";
          $this->salida .= "	<tr class=\"modulo_table_title\">\n";
          if(!empty($datosPaciente[cama]))
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

          $this->salida .= "<br><table colspan=\"2\" align=\"center\" width=\"90%\" border=\"0\" class=\"modulo_table_list\">\n";
          $this->salida .= $this->SetStyle("MensajeError",11);
          $this->salida .= "<tr class='modulo_table_title'>\n";
          $this->salida .= "<td align='center' width=\"50%\">TOMA DE CONTROLES NEUROLOGICOS\n";
          $this->salida .= "</td>\n";
          $this->salida .= "<td align='center' width=\"50%\">\n";

          $hora_inicio_turno = ModuloGetVar('app','EstacionEnfermeria','HoraInicioTurnoControles');
          $rango_turno = ModuloGetVar('app','EstacionEnfermeria','RangoVerTurnosControles');

          $hora_inicio_turno = "00:00:00";			
          if(date("H:i:s") <= $hora_inicio_turno)
          {
               list($fecha_control,$hora_control)=explode(" ",date("Y-m-d H:i:s"));
               list($h,$m,$s)=explode(":",$hora_control);
          }
          else
          {//si la hora actual es menor que la de inicio turno, se debe poner la fecha anterior
               list($fecha_control,$hora_control)=explode(" ",date("Y-m-d H:i:s",mktime(date("h"),date("i"),date("s"),date("m"),(date("d")-1),date("Y"))));
               list($h,$m,$s)=explode(":",$hora_control);
          }

          $i=0;
          $this->salida.= "<select name='selectHora' class='select'>\n";
          for($j=0; $j<$rango_turno; $j++)
          {
               list($anno, $mes, $dia)=explode("-",$fecha_control);
               if ($i==23)
               {
                    list($h,$m,$s)=explode(":",$hora_inicio_turno);
                    $i=date("H",mktime($h+$j,$m,$s,$mes,$dia,$anno));
                    $fecha2=date("Y-m-d H:i:s",mktime(24,0,0,$mes,$dia,$anno));
                    $fecha_control=date("Y-m-d",mktime(24,0,0,$mes,$dia,$anno));
               }
               else
               {
                    list($h,$m,$s)=explode(":",$hora_inicio_turno);
                    $i=date("H",mktime($h+$j,$m,$s,$mes,$dia,$anno));
                    $fecha2=date("Y-m-d H:i:s",mktime($i,0,0,$mes,$dia,$anno));
                    $fecha_control=date("Y-m-d",mktime($i,0,0,$mes,$dia,$anno));
               }
               if(empty($selectHora)){
                    if($i == date("H")){ $selected = "selected='true'";} else { $selected = "";}
               }
               else
               {//viene de insertar pero no lo hizo, asi que pongo por defecto los valores enviados
                    list($A,$B) = explode(" ",$selectHora);
                    if($i == $B){ $selected = "selected='true'";} else { $selected = "";}
               }
               #################################################
               list($yy,$mm,$dd)=explode(" ",$fecha_control);//(date("m"),(date("d")),date("Y")));
               if($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")),date("Y"))))){
                    $show = "Hoy a las";
               }
               elseif($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")+1),date("Y"))))){
                    $show = "Mañana a las";
               }
               elseif($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")-1),date("Y"))))){
                    $show = "Ayer a las";
               }
               else{
                    $show = $fecha_control;
               }
               ###########################
               $this->salida .= "<option value='".date("Y-m-d")." ".$i."' $selected>".$i."</option>\n";
         }//fin for
          
          if(!empty($_REQUEST['selectHora']))
          {
               $horas_R = explode(" ", $_REQUEST['selectHora']);
               $this->salida .= "<option value='".date("Y-m-d")." ".$horas_R[1]."' selected='true'>".$horas_R[1]."</option>\n";
          }
          $this->salida.= "</select>:&nbsp;\n";
          
          $this->salida .= "<select name='selectMinutos' class='select'>\n";

          for($j=0; $j<=59; $j++)
          {
               if(empty($selectMinutos)){
                    if($j == date("i")){ $selected = "selected='true'";} else { $selected = "";}
               }
               else
               {//viene de insertar pero no lo hizo, asi que pongo por defecto los valores enviados
                    list($A,$B) = explode(" ",$selectMinutos);
                    if($j == $A){ $selected = "selected='true'";} else { $selected = "";}
               }
               if ($j<10){
                    $this->salida .= "<option value='0$j:00' $selected>0$j</option>\n";
               }
                    else{
                    $this->salida .= "<option value='$j:00' $selected>$j</option>\n";
               }
          }
          $this->salida .= "</select>\n";
          $this->salida .= "</td>\n";
          $this->salida .= "</tr>\n";
          $this->salida .= "</table>\n";

          $this->salida.="<table border='0' align='center' valign='top' width='90%' class=\"modulo_table_list\">";
          $this->salida.="<tr>";
          $this->salida.="<td>";

          /*---------------------------------------------------------------------
          *	ESTRUCTURA EN HTML DE LOS SISTEMAS NEUROLOGICOS A EVALUAR
          *	TIZZIANO PEREA O.
          ---------------------------------------------------------------------*/

          $this->salida.="<table border='1' cellspacing='3' cellpadding='6' width='100%' class=\"modulo_table_list\">";

          $this->salida.="<tr class=\"modulo_table_list_title\">";
          $this->salida.="<td colspan='2' align='center'> PUPILAS</td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"modulo_list_claro\">";
          $this->salida.="<td align='center'>";
          $this->salida.="<table border='1' class=\"modulo_table_list\"><div align='center'>TALLA PUPILA IZQUIERDA</div>";
          $this->salida.="<tr class=\"modulo_list_claro\">";
          $this->salida.="<td align='center'><img src=\"".GetThemePath()."/images/Pupilas/circulo_4.png\" border=0></td>";
          $this->salida.="<td align='center'><img src=\"".GetThemePath()."/images/Pupilas/circulo_3.png\" border=0></td>";
          $this->salida.="<td align='center'><img src=\"".GetThemePath()."/images/Pupilas/circulo_2.png\" border=0></td>";
          $this->salida.="<td align='center'><img src=\"".GetThemePath()."/images/Pupilas/circulo_grande.png\" border=0></td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"modulo_list_oscuro\">";
          $this->salida.="<td align='center'><input type='radio' name='pupilaI' value='".$Tallas[0]['talla_pupila_id']."'></td>";
          $this->salida.="<td align='center'><input type='radio' name='pupilaI' value='".$Tallas[1]['talla_pupila_id']."'></td>";
          $this->salida.="<td align='center'><input type='radio' name='pupilaI' value='".$Tallas[2]['talla_pupila_id']."'></td>";
          $this->salida.="<td align='center'><input type='radio' name='pupilaI' value='".$Tallas[3]['talla_pupila_id']."'></td>";
          $this->salida.="</tr>";

          $this->salida.="<tr>";
          $this->salida.="<td colspan='4' align='center'><label>REACCION</label><br>";

          $this->salida.="<select name='reaccionI' class='select'>";
          foreach ($Reaccion as $k => $v)
          {
               $this->salida .= "<option value='".$v['reaccion_pupila_id']."' >".$v['descripcion']."</option>\n";
          }
          $this->salida.="</select></td>";
          $this->salida.="</tr>";

          $this->salida.="</table>";
          $this->salida.="</td>";

          $this->salida.="<td align='center'>";
          $this->salida.="<table border='1' class=\"modulo_table_list\"><div align='center'>TALLA PUPILA DERECHA</div>";
          $this->salida.="<tr class=\"modulo_list_claro\">";
          $this->salida.="<td align='center'><img src=\"".GetThemePath()."/images/Pupilas/circulo_4.png\" border=0></td>";
          $this->salida.="<td align='center'><img src=\"".GetThemePath()."/images/Pupilas/circulo_3.png\" border=0></td>";
          $this->salida.="<td align='center'><img src=\"".GetThemePath()."/images/Pupilas/circulo_2.png\" border=0></td>";
          $this->salida.="<td align='center'><img src=\"".GetThemePath()."/images/Pupilas/circulo_grande.png\" border=0></td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"modulo_list_oscuro\">";
          $this->salida.="<td align='center'><input type='radio' name='pupilaD' value='".$Tallas[0]['talla_pupila_id']."'></td>";
          $this->salida.="<td align='center'><input type='radio' name='pupilaD' value='".$Tallas[1]['talla_pupila_id']."'></td>";
          $this->salida.="<td align='center'><input type='radio' name='pupilaD' value='".$Tallas[2]['talla_pupila_id']."'></td>";
          $this->salida.="<td align='center'><input type='radio' name='pupilaD' value='".$Tallas[3]['talla_pupila_id']."'></td>";
          $this->salida.="</tr>";

          $this->salida.="<tr>";
          $this->salida.="<td colspan='4' align='center'><label>REACCION</label><br>";

          $this->salida.="<select name='reaccionD' class='select'>";
          foreach ($Reaccion as $k => $v)
          {
               $this->salida .= "<option value='".$v['reaccion_pupila_id']."' >".$v['descripcion']."</option>\n";
          }
          $this->salida.="</select></td>";

          $this->salida.="</tr>";
          $this->salida.="</table>";
          $this->salida.="</td>";
          $this->salida.="</tr>";
          $this->salida.="</table>";
          $this->salida.="</td>";

          $this->salida.="<td>";
          $this->salida.="<table border='1' cellspacing='1' cellpadding='3' width='100%' class=\"modulo_table_list\">";

          $this->salida.="<tr class=\"modulo_table_list_title\">";
	     $this->salida.="<td colspan='2'> NIVELES DE CONCIENCIA</td>";
          $this->salida.="</tr>";

          $spy=0;
          foreach ($Nivel_Conciencia as $k => $c)
          {
               if($spy==0)
               {
                    $this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
                    $spy=1;
               }
               else
               {
                    $this->salida.="<tr class=\"hc_submodulo_list_claro\">";
                    $spy=0;
               }

          	$this->salida.="<td><b>$c[descripcion]</b></td>";
               $this->salida.="<td align='center'><input type='radio' name='orientado' value='".$c['nivel_consciencia_id']."'></td>";
               $this->salida.="</tr>";
          }

          $this->salida.="</table>";
          $this->salida.="</td>";


          $this->salida.="<td>";
          $this->salida.="<table border='1' width='100%' class=\"modulo_table_list\">";//width='100%'

          $this->salida.="<tr class=\"modulo_table_list_title\">";
          $this->salida.="<td colspan='2'> FUERZA</td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"modulo_list_claro\">";
     	$this->salida.="<td> BRAZO DERECHO </td>";
          $this->salida.="<td align='center'>";
          $this->salida.="<select name='brader' class='select'>";

          foreach ($TiposFuerza as $k => $f)
          {
               $this->salida.="<option value='".$f['fuerza_id']."' >".$f['descripcion']."</option>\n";
          }
          $this->salida.="</select></td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"modulo_list_oscuro\">";
          $this->salida.="<td> BRAZO IZQUIERDO </td>";
          $this->salida.="<td align='center'>";
          $this->salida.="<select name='braizq' class='select'>";
          foreach ($TiposFuerza as $k => $f)
          {
               $this->salida.="<option value='".$f['fuerza_id']."' >".$f['descripcion']."</option>\n";
          }
          $this->salida.="</select></td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"modulo_list_claro\">";
	     $this->salida.="<td> PIERNA DERECHA </td>";
          $this->salida.="<td align='center'>";
          $this->salida.="<select name='pierder' class='select'>";
          foreach ($TiposFuerza as $k => $f)
          {
               $this->salida.="<option value='".$f['fuerza_id']."' >".$f['descripcion']."</option>\n";
          }
          $this->salida.="</select></td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"modulo_list_oscuro\">";
	     $this->salida.="<td> PIERNA IZQUIERDA</td>";
          $this->salida.="<td align='center'>";
          $this->salida.="<select name='pierizq' class='select'>";
          foreach ($TiposFuerza as $k => $f)
          {
               $this->salida.="<option value='".$f['fuerza_id']."' >".$f['descripcion']."</option>\n";
          }
          $this->salida.="</select> </td>";
	     $this->salida.="</tr>";
          $this->salida.="</table>";
          $this->salida.="</td></td>";
          $this->salida.="</tr>";
          $this->salida.="</table><br>";

          /*---------------------------------------------------------------------
          *	ESTRUCTURA EN HTML DE LA ESCALA DE GLASGOW
          *	TIZZIANO PEREA O.
          ---------------------------------------------------------------------*/

          $this->salida.="<table border='0' align='center' valign='top' width='90%' class=\"modulo_table_list\">";
          $this->salida.="<tr>";
          $this->salida.="<td>";
          $this->salida.="<table width='100%' valign='top' border='0' align='center' class=\"modulo_table_list\">";

          $this->salida.="<tr class=\"modulo_table_list_title\">";
          $this->salida.="<td colspan='3' align='center'> ESCALA DE GLASGOW</td>";
          $this->salida.="</tr>";

          $this->salida.="<tr><td>";
          $this->salida.="<table border='1' cellspacing='4' cellpadding='5' width='100%' class=\"modulo_table_list\">";
          $this->salida.="<div align='center' class='modulo_table_title'>APERTURA OCULAR</div>";

          foreach ($TipoAperturaOcular as $k => $AO)
          {
               $this->salida.="<tr>";
               $this->salida.="<td class=\"modulo_list_claro\">".$AO[apertura_ocular_id].' -   '.$AO[descripcion]."</td>";
               $this->salida.="<td align='center' class=\"modulo_list_oscuro\"> <input type='radio' name='ao' value='".$AO['apertura_ocular_id']."'> </td>";
               $this->salida.="</tr>";
          }

          $this->salida.="</td></tr>";
          $this->salida.="</table>";

          $this->salida.="<td>";
          $this->salida.="<table border='1'  cellspacing='2' cellpadding='3' width='100%' class=\"modulo_table_list\">";
          $this->salida.="<div align='center' class='modulo_table_title'>RESPUESTA VERBAL</div>";
          foreach ($RespuestaVerbal as $k => $RV)
          {
               $FechaInicio = $this->GetFechaNacPaciente($datosPaciente[ingreso]);
               $FechaFin = date("Y-m-d");
               $edad_paciente = CalcularEdad($FechaInicio,$FechaFin);
               if ($edad_paciente[anos] < ModuloGetVar('','','max_edad_lactante'))
               {
                    $this->salida.="<tr>";
                    $this->salida.="<td class=\"modulo_list_claro\">".$RV[respuesta_verbal_id].' -   '.$RV[descripcion_lactante]."</td>";
                    $this->salida.="<td align='center' class=\"modulo_list_oscuro\"> <input type='radio' name='rv' value='".$RV['respuesta_verbal_id']."'> </td>";
                    $this->salida.="</tr>";
               }
               else
               {
                    $this->salida.="<tr>";
                    $this->salida.="<td class=\"modulo_list_claro\">".$RV[respuesta_verbal_id].' -   '.$RV[descripcion]."</td>";
                    $this->salida.="<td align='center' class=\"modulo_list_oscuro\"> <input type='radio' name='rv' value='".$RV['respuesta_verbal_id']."'> </td>";
                    $this->salida.="</tr>";
               }
          }
          $this->salida.="</table></td>";


          $this->salida.="<td>";
          $this->salida.="<table border='1' width='100%' class=\"modulo_table_list\">";
          $this->salida.="<div align='center' class='modulo_table_title'> RESPUESTA MOTORA</div>";
          foreach ($RespuestaMotora as $k => $RM)
          {
               $FechaInicio = $this->GetFechaNacPaciente($datosPaciente[ingreso]);
               $FechaFin = date("Y-m-d");
               $edad_paciente = CalcularEdad($FechaInicio,$FechaFin);
               if ($edad_paciente[anos] < ModuloGetVar('','','max_edad_lactante'))
               {
                    $this->salida.="<tr>";
                    $this->salida.="<td class=\"modulo_list_claro\">".$RM[respuesta_motora_id].' -   '.$RM[descripcion_lactante]."</td>";
                    $this->salida.="<td align='center' class=\"modulo_list_oscuro\"> <input type='radio' name='rm' value='".$RM['respuesta_motora_id']."'> </td>";
                    $this->salida.="</tr>";
               }
               else
               {
                    $this->salida.="<tr>";
                    $this->salida.="<td class=\"modulo_list_claro\">".$RM[respuesta_motora_id].' -   '.$RM[descripcion]."</td>";
                    $this->salida.="<td align='center' class=\"modulo_list_oscuro\"> <input type='radio' name='rm' value='".$RM['respuesta_motora_id']."'> </td>";
                    $this->salida.="</tr>";
               }
          }
          $this->salida.="</table></td>";

          $this->salida.="</td></tr>";
          $this->salida.="</table>";
          $this->salida.="</td></tr>";
          $this->salida.="</table>";

          $this->salida .= "<div class='normal_10' align='center'><br><input type='submit' class='input-submit' name='Save' value='INSERTAR'>";
          $this->salida.="</form>";
          
          $href = ModuloGetURL('app','EE_ControlesPacientes','user','CallFrmsControlesPacientes',array('datosPaciente'=>$datosPaciente,"href_action_hora"=>$href_action_hora,"href_action_control"=>$href_action_control,'control'=>$control));
          $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>PROGRAMACION DE CONTROLES</a><br>";
          $this->salida .= themeCerrarTabla();
          return true;
     }

     /*
     *	ShowControl_Neurologico
     *	Muestra los Controles neurologicos registrados del paciente seleccionado
     *	@Author Rosa Maria Angel
     *	@access Private
     *	@param array datos del paciente
     *	@param array datos de la estacion
     *	@param integer numero de filas a mostrar
     *	@return boolean
     */
     function ShowControl_Neurologico($datosPaciente,$datos_estacion,$control,$href_action_hora,$href_action_control)
     {
          $datosPaciente = $_REQUEST['datosPaciente'];
          $datos_estacion = $_REQUEST['datos_estacion'];
          $control = $_REQUEST['control'];
          $href_action_hora = $_REQUEST['href_action_hora'];
          $href_action_control = $_REQUEST['href_action_control'];

          $this->salida = ThemeAbrirTabla('LISTA DE CONTROL DE ESTADO NEUROLOGICO');

          $VectorControl = $this->Listar_ControlesNeurologicos($datosPaciente['ingreso']);
          
          if($VectorControl != "ShowMensaje")
          {
               if (empty($contador)){
                    $contador=sizeof($VectorControl);
               }
               
               $this->salida .= "<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_title\">\n";
               $this->salida .= "	<tr class=\"modulo_table_title\">\n";
               if(!empty($datosPaciente[cama]))
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

               $this->salida .="<table align=\"center\" width=\"100%\" border='0'>";
               $this->salida .="<tr class=\"modulo_table_list_title\">";
               $this->salida .="<td rowspan='2'>FECHA</td>";
               $this->salida .="<td rowspan='2'>HORA</td>";
               $this->salida .="<td colspan='2'>PUPILA DERECHA</td>";
               $this->salida .="<td colspan='2'>PUPILA IZQUIDA.</td>";
               $this->salida .="<td rowspan='2'>CONCIENCIA</td>";
               $this->salida .="<td colspan='4'> FUERZA </td>";
               $this->salida .="<td colspan='4'> ESCALA DE GLASGOW </td>";
               $this->salida .="<td rowspan='2'>USUARIO</td>";
               $this->salida .="</tr>";
               $this->salida .="<tr class='hc_table_submodulo_list_title'>";
               $this->salida .="<td align=\"center\"> TALLA </td>";
               $this->salida .="<td align=\"center\"> REACCION</td>";
               $this->salida .="<td align=\"center\"> TALLA </td>";
               $this->salida .="<td align=\"center\"> REACCION </td>";
               $this->salida .="<td align=\"center\"> B. DER. </td>";
               $this->salida .="<td align=\"center\"> B. IZQ. </td>";
               $this->salida .="<td align=\"center\"> P. DER. </td>";
               $this->salida .="<td align=\"center\"> P. IZQ. </td>";
               $this->salida .="<td align=\"center\"> A. OCULAR </td>";
               $this->salida .="<td align=\"center\"> R. VERBAL </td>";
               $this->salida .="<td align=\"center\"> R. MOTORA </td>";
               $this->salida .="<td align=\"center\"> E.G. </td>";
               $this->salida .="</tr>";
               $cont=1;
               $spy=0;
               while ($cont <= sizeof($VectorControl) && $cont <= $contador)
               {
                    list($fecha,$hora) = explode(" ",$VectorControl[$cont-1][fecha]);
                    list($ano,$mes,$dia) = explode("-",$fecha);
                    list($hora,$min) = explode(":",$hora);
                    $hora=$hora.":".$min;
                    if($fecha == date("Y-m-d"))
                    {
                         $fecha = "HOY";
                    }
                    elseif($fecha == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y"))))
                    {
                         $fecha = "AYER";
                    }
                    else
                    {
                         $fecha = $fecha;
                    }

                    if($spy==0)
                    {
                         $this->salida.="<tr class=\"modulo_list_oscuro\">";
                         $spy=1;
                    }
                    else
                    {
                         $this->salida.="<tr class=\"modulo_list_claro\">";
                         $spy=0;
                    }

                    if($VectorControl[$cont-1][pupila_talla_d] == 0) $ptallad = "--"; else $ptallad = $VectorControl[$cont-1][pupila_talla_d];
                    if($VectorControl[$cont-1][pupila_reaccion_d] == ' ') $preacciond = "--"; else $preacciond = $VectorControl[$cont-1][pupila_reaccion_d];
                    if($VectorControl[$cont-1][pupila_talla_i] == 0) $ptallai = "--"; else $ptallai = $VectorControl[$cont-1][pupila_talla_i];
                    if($VectorControl[$cont-1][pupila_reaccion_i] == ' ') $preaccioni = "--"; else $preaccioni = $VectorControl[$cont-1][pupila_reaccion_i];
                    if($VectorControl[$cont-1][descripcion] == ' ') $conciencia = "--"; else $conciencia = $VectorControl[$cont-1][descripcion];
                    if($VectorControl[$cont-1][fuerza_brazo_d] == ' ') $brazod = "--"; else $brazod = $VectorControl[$cont-1][fuerza_brazo_d];
                    if($VectorControl[$cont-1][fuerza_brazo_i] == ' ') $brazoi = "--"; else $brazoi = $VectorControl[$cont-1][fuerza_brazo_i];
                    if($VectorControl[$cont-1][fuerza_pierna_d] == ' ') $piernad = "--"; else $piernad = $VectorControl[$cont-1][fuerza_pierna_d];
                    if($VectorControl[$cont-1][fuerza_pierna_i] == ' ') $piernai = "--"; else $piernai = $VectorControl[$cont-1][fuerza_pierna_i];
                    if($VectorControl[$cont-1][tipo_apertura_ocular_id] == 0 ) $AO = "--"; else $AO = $VectorControl[$cont-1][tipo_apertura_ocular_id];
                    if($VectorControl[$cont-1][tipo_respuesta_verbal_id] == 0 ) $RV = "--"; else $RV = $VectorControl[$cont-1][tipo_respuesta_verbal_id];
                    if($VectorControl[$cont-1][tipo_respuesta_motora_id] == 0 ) $RM = "--"; else $RM = $VectorControl[$cont-1][tipo_respuesta_motora_id];
                    if($VectorControl[$cont-1][usuario] == ' ') $user = "--"; else $user = $VectorControl[$cont-1][usuario];
                    $EG = $AO + $RV + $RM;
                    if($EG == 0) $EG = "--"; else $EG = $EG;

                    $this->salida .="<td align=\"center\">" .$fecha. "</td>";
                    $this->salida .="<td align=\"center\">" .$hora. "</td>";
                    $this->salida .="<td align=\"center\">" .$ptallad. "</td>";
                    $this->salida .="<td align=\"center\">" .$preacciond. "</td>";
                    $this->salida .="<td align=\"center\">" .$ptallai. "</td>";
                    $this->salida .="<td align=\"center\">" .$preaccioni. "</td>";
                    $this->salida .="<td align=\"center\">" .$conciencia. "</td>";
                    $this->salida .="<td align=\"center\">" .$brazod. "</td>";
                    $this->salida .="<td align=\"center\">" .$brazoi. "</td>";
                    $this->salida .="<td align=\"center\">" .$piernad. "</td>";
                    $this->salida .="<td align=\"center\">" .$piernai. "</td>";
                    $this->salida .="<td align=\"center\">" .$AO. "</td>";
                    $this->salida .="<td align=\"center\">" .$RV. "</td>";
                    $this->salida .="<td align=\"center\">" .$RM. "</td>";
                    if ($EG < 8)
                    {
                         $this->salida .="<td align=\"center\" class ='GlasgowBajo'>" .$EG. "</td>";
                    }

                    if ($EG >= 8 && $EG < 12)
                    {
                         $this->salida .="<td align=\"center\" class ='GlasgowIntermedio'>" .$EG. "</td>";
                    }

                    if ($EG >= 12)
                    {
                         $this->salida .="<td align=\"center\" class ='GlasgowAlto'>" .$EG. "</td>";
                    }

                    $fechareg =$VectorControl[$cont-1][fecha_registro];
                    $fechareg = explode(" ",$fechareg);
                    $user=$this->GetDatosUsuarioSistema($VectorControl[$cont-1][usuario_id]);
                    if ($VectorControl[$cont-1][usuario_id] == UserGetUID() AND $fechareg[0]==date("Y-m-d") AND $datosPaciente[ingreso]==$VectorControl[$cont-1][ingreso])
                    {
                         $action = ModuloGetURL('app','EE_ControlesPacientes','user','Borrar_ControlNeuro',
                         array("fechar"=>$VectorControl[$cont-1][fecha_registro],'datosPaciente'=>$datosPaciente,'datos_estacion'=>$datos_estacion,'control'=>$control,'href_action_hora'=>$href_action_hora,'href_action_control'=>$href_action_control));
                         $this->salida .= "<td><a href='".$action."'>ELIMINAR</a></td>\n";
                    }
                    else
                    {
                         $this->salida .="<td align=\"center\">" .$user[0][usuario]. "</td>";
                    }
                    $this->salida .="</tr>";
                    $cont++;
               }
               $this->salida .="</table>";
          }else
          {
               $this->salida .= "<div class='lable_mark' align='center'><br>AUN NO HAY REGISTRO DE CONTROLES NEUROLOGICOS</div>";
          }
          
          $href = ModuloGetURL('app','EE_ControlesPacientes','user','CallFrmsControlesPacientes',array('datosPaciente'=>$datosPaciente,"href_action_hora"=>$href_action_hora,"href_action_control"=>$href_action_control,'control'=>$control));
          $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>PROGRAMACION DE CONTROLES</a><br>";
          $this->salida .= themeCerrarTabla();
          return true;
     }
     
     /*
     *	FrmIngresarDatosGlucometr&iacute;a
     *	Permite ingresar los datos de la glucometria a un paciente x
     */
     function FrmIngresarDatosGlucometria($datosPaciente,$datos_estacion,$control,$href_action_hora,$href_action_control,$turno_hora)
     {
          if(empty($datos_estacion))
          {
               $datosPaciente = $_REQUEST['datosPaciente'];
               $datos_estacion = $_REQUEST['datos_estacion'];
               $control = $_REQUEST['control'];
               $href_action_hora = $_REQUEST['href_action_hora'];
               $href_action_control = $_REQUEST['href_action_control'];
          }
          
          if(empty($_REQUEST['turno_hora']))
			$_REQUEST['turno_hora'] = $turno_hora;
          
          $ViasInsulina = $this->GetViasInsulina();
          $TiposInsulina = $this->GetTiposInsulina();
          if(!$ViasInsulina || !$TiposInsulina){
               return false;
          }
          elseif($ViasInsulina === "ShowMensaje" || $TiposInsulina === "ShowMensaje" ){
               $mensaje = "NO SE ENCONTRARON LOS TIPOS DE INSULINA O LAS V&Iacute;AS DE ADMINISTRACION";
               $titulo = "MENSAJE";
               $url = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
               $link = "PANEL ENFERMERIA";
               $this->frmMSG($url, $titulo, $mensaje, $link);
               return true;
          }
          else
          {
               $fecha=$_REQUEST['turno_hora'];
               $this->salida .= ThemeAbrirTabla("CONTROL DE PACIENTE DIABETICO");
               $action = ModuloGetURL('app','EE_ControlesPacientes','user','InsertarDatosGlucometria',array("control"=>$control,"datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,"href_action_hora"=>$href_action_hora,"href_action_control"=>$href_action_control,'turno_hora'=>$_REQUEST['turno_hora']));
               $this->salida .= "<form name='IniciarGlucometriasPacientes' method=\"POST\" action=\"$action\"><br>\n";
               
               $this->salida .= "<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_title\">\n";
               $this->salida .= "	<tr class=\"modulo_table_title\">\n";
               if(!empty($datosPaciente[cama]))
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
               
               $this->salida .= "	<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
               $this->salida .= $this->SetStyle("MensajeError",1);
               $this->salida .= "	<tr>\n";
               $this->salida .= "		<td>\n";
               $this->salida .= "			<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list\">\n";
               $this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
               $this->salida .= "					<td rowspan='2' width='25%' class=\"".$this->SetStyle("Glucometria")."\">GLUCOMETRIA</td>\n";
               $this->salida .= "					<td colspan='3' class=\"".$this->SetStyle("Insulina")."\">INSULINA</td>\n";
               $this->salida .= "				</tr>\n";
               $this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
               $this->salida .= "					<td width='25%' class=\"".$this->SetStyle("SelectInsulina")."\">TIPO</td>\n";
               $this->salida .= "					<td width='25%' class=\"".$this->SetStyle("TextInsulina")."\">CANTIDAD</td>\n";
               $this->salida .= "					<td width='25%' class=\"".$this->SetStyle("ViaInsulina")."\">VIA</td>\n";
               $this->salida .= "				</tr>\n";
               $this->salida .= "				<tr class=\"modulo_list_claro\" align='center'>\n";
               $this->salida .= "					<td rowspan='2' width='25%'><input type='text' name='Glucometria' value='$Glucometria' size='6' maxlength='5' class='input-text'></td>\n";
               $this->salida .= "					<td width='25%' align='left'><br>&nbsp;&nbsp;&nbsp;&nbsp;\n";
               if(in_array("cristalina",$_REQUEST['checkInsulina'])){
                    $checked = "checked='yes'";
               }
               else{
                    $checked = "";
               }
               $this->salida .= "						<input type='checkbox' name='checkInsulina[]' value='cristalina' $checked>&nbsp;&nbsp;CRISTALINA&nbsp;&nbsp;&nbsp;\n";
               $this->salida .= "					</td>\n";
               $this->salida .= "					<td>\n";
               $this->salida .= "						<input type='text' name='textInsulina[cristalina]' value='".$_REQUEST['textInsulina']['cristalina']."' size='6' maxlength='5' class='input-text'><label class='label_mark'>&nbsp;Unidades</label>\n";
               $this->salida .= "					</td>\n";
               $this->salida .= "					<td>\n";
               $this->salida .= "						<select name='ViaInsulina[cristalina]' class='select'>\n";
               $this->salida .= "							<option value='-1' $selected>--</option>\n";
               foreach($ViasInsulina as $clave => $val)
               {
                    if($val['tipo_via_insulina_id'] == $_REQUEST['ViaInsulina']['cristalina']) { $selected="selected='true'";} else {$selected = ""; }
                    $this->salida .= "						<option value='".$val['tipo_via_insulina_id']."' $selected>".$val['descripcion']."</option>\n";
               }
               $this->salida .= "						</select>\n";
               $this->salida .= "					</td>\n";
               $this->salida .= "				</tr>\n";
               $this->salida .= "				<tr class=\"modulo_list_claro\" align='center'>\n";
               $this->salida .= "					<td width='25%' align='left'><br>&nbsp;&nbsp;&nbsp;&nbsp;\n";
               if(in_array("nph",$_REQUEST['checkInsulina'])){
                    $checked = "checked='yes'";
               }
               else{
                    $checked = "";
               }
               $this->salida .= "						<input type='checkbox' name='checkInsulina[]' value='nph' $checked>&nbsp;&nbsp;NPH&nbsp;&nbsp;&nbsp;\n";
               $this->salida .= "					</td>\n";
               $this->salida .= "					<td>\n";
               $this->salida .= "						<input type='text' name='textInsulina[nph]' value='".$_REQUEST['textInsulina']['nph']."' size='6' maxlength='5' class='input-text'><label class='label_mark'>&nbsp;Unidades</label>\n";
               $this->salida .= "					</td>\n";
               $this->salida .= "					<td>\n";
               $this->salida .= "						<select name='ViaInsulina[nph]' class='select'>\n";
               $this->salida .= "							<option value='-1' $selected>--</option>\n";
               foreach($ViasInsulina as $clave => $val)
               {
                    if($val['tipo_via_insulina_id'] == $_REQUEST['ViaInsulina']['nph']) { $selected="selected='true'";} else {$selected = ""; }
                    $this->salida .= "						<option value='".$val['tipo_via_insulina_id']."' $selected>".$val['descripcion']."</option>\n";
               }
               $this->salida .= "						</select>\n";
               $this->salida .= "					</td>\n";
               $this->salida .= "				</tr>\n";
               $this->salida .= "			</table><br>\n";
               $this->salida .= "			<br><br><div class='normal_10' align='center'><input type='submit' name='Submit' value='INGRESAR DATOS' class='input-submit'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='reset' name='Reset' value='REESTABLECER' class='input-submit'>\n";
               $this->salida .= "</table>\n";
               $this->salida .= "</form>\n";
               
               $href = ModuloGetURL('app','EE_ControlesPacientes','user','CallFrmsControlesPacientes',array('datosPaciente'=>$datosPaciente,"href_action_hora"=>$href_action_hora,"href_action_control"=>$href_action_control,'control'=>$control));
               $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>PROGRAMACION DE CONTROLES</a><br>";
               $this->salida .= themeCerrarTabla();
          }
          return true;
     }//fin FrmIngresarDatosGlucometr&iacute;a

     /*
     *	FrmResumenGlucometria
     *	Muestra los registros que tiene el paciente del control de Glucometria
     */
     function FrmResumenGlucometria()
     {
          $datosPaciente = $_REQUEST['datosPaciente'];
          $datos_estacion = $_REQUEST['datos_estacion'];
          $control = $_REQUEST['control'];
          $href_action_hora = $_REQUEST['href_action_hora'];
          $href_action_control = $_REQUEST['href_action_control'];
          
          $Resumen = $this->GetResumenGlucometria($datosPaciente[ingreso]);
          if(!$Resumen){
               return false;
          }

          elseif($Resumen === "ShowMensaje")
          {
               $mensaje = "NO SE ENCONTRARON REGISTROS DE CONTROLES PARA PACIENTES DIABETICOS";
               $titulo = "MENSAJE";
               $url = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
               $link = "PANEL ENFERMERIA";
               $this->frmMSG($url, $titulo, $mensaje, $link);
               return true;
          }
          else
          {

               $this->salida .= ThemeAbrirTabla("RESUMEN CONTROL DE GLUCOMETRIA");
               $this->salida .= "<table align=\"center\" width=\"95%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class='normal_10'>\n";
               $this->salida .= "	<tr>\n";
               $this->salida .= "<td><br>\n";
               $this->salida .= "<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_title\">\n";
               $this->salida .= "	<tr class=\"modulo_table_title\">\n";
               if(!empty($datosPaciente[cama]))
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
               $this->salida .= "		</td>\n";
               $this->salida .= "	</tr>\n";
               $this->salida .= "	<tr><td>&nbsp;</td></tr>\n";
               $this->salida .= "	<tr>\n";
               $this->salida .= "		<td>\n";
               $this->salida .= "			<table width='100%' border='0'  align='center'>\n";
               $this->salida .= "				<tr class=\"modulo_table_list_title\" align='center'>\n";
               $this->salida .= "					<td rowspan='2'>FECHA</td>\n";
               $this->salida .= "					<td rowspan='2'>GLUCOMETRIA</td>\n";
               $this->salida .= "					<td colspan='2'>INSULINA CRISTALINA</td>\n";
               $this->salida .= "					<td colspan='2'>INSULINA NHP</td>\n";
               $this->salida .= "				</tr>\n";
               $this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
               $this->salida .= "					<td width='13%'>CANTIDAD</td>\n";
               $this->salida .= "					<td width='13%' >VIA</td>\n";
               $this->salida .= "					<td width='13%'>CANTIDAD</td>\n";
               $this->salida .= "					<td width='13%'>VIA</td>\n";
               $this->salida .= "				</tr>\n";
               /*$Rangos = $this->GetRangoControl(8);*/


               if (!IncludeLib('datospaciente')){
                    $this->error = "Error al cargar la libreria [datospaciente].";
                    $this->mensajeDeError = "datospaciente";
                    return false;
               }


               $datos_hc=GetDatosPaciente("","",$datosPaciente[ingreso],"","");
               $paciente=array("edad"=>CalcularEdad($datos_hc["fecha_nacimiento"],date("Y-m-d")),"sexo"=>$datos_hc["sexo_id"]);

               $Rangos = $this->GetRangoControl(8,$paciente);
               if ($Rangos === false){
                    return false;
               }
               
               foreach($Resumen as $key => $value)
               {
                    if(!empty($value[0][glucometria]))			{ $gluco = number_format($value[0][glucometria], 0, ',', '.');} else { $gluco = "--"; }
                    if(!empty($value[0][valor_cristalina]))	{ $valCristalina = number_format($value[0][valor_cristalina], 0, ',', '');} else { $valCristalina = "--"; }
                    if(!empty($value[0][valor_nph]))				{ $valNPH = number_format($value[0][valor_nph], 0, ',', '');} else { $valNPH = "--"; }
                    if(!empty($value[0][via_cristalina]))		{ $via_cristalina = $value[0][viacristalina];} else { $via_cristalina = "--"; }
                    if(!empty($value[0][via_nph]))					{ $via_nph = $value[0][vianph];} else { $via_nph = "--"; }

                    $this->salida .= "				<tr ".$this->Lista($cont)." align='center'>\n";
                    list($date,$time) = explode (" ",$key);
                    if($date == date("Y-m-d")) {
                         $fecha = "HOY ".$time;
                    }
                    elseif($date == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y")))){
                         $fecha = "AYER ".$time;
                    }
                    else{
                         $fecha = $key;
                    }
                    $this->salida .= "					<td>".$fecha."</td>\n";
                    if($gluco >= $Rangos[rango_max] || $gluco<= $Rangos[rango_min]){
                         $estilo = "alerta";
                    }
                    else{
                         $estilo = "";
                    }
                    $this->salida .= "					<td class='$estilo' >".$gluco."</td>\n";
                    $this->salida .= "					<td>".$valCristalina."</td>\n";
                    $this->salida .= "					<td>".$via_cristalina."</td>\n";
                    $this->salida .= "					<td>".$valNPH."</td>\n";
                    $this->salida .= "					<td>".$via_nph."</td>\n";
                    $this->salida .= "				</tr>\n";
                    $cont++;
               }
               $this->salida .= "		</td>\n";
               $this->salida .= "	</tr>\n";
               $this->salida .= "</table>\n";
               $href = ModuloGetURL('app','EE_ControlesPacientes','user','CallFrmsControlesPacientes',array('datosPaciente'=>$datosPaciente,"href_action_hora"=>$href_action_hora,"href_action_control"=>$href_action_control,'control'=>$control));
               $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>PROGRAMACION DE CONTROLES</a><br>";
               $this->salida .= "</table>\n";
               $this->salida .= themeCerrarTabla();
          }
          return true;
     }//FrmResumenGlucometria
    
    /*
     * Funcion que me muestra en pantalla la informacion de los controles
     * que tienen pendiente los pacientes
     */          
	function FrmControlesProgramados($datos_estacion,$datosPaciente,$control)
     {
		$controles=$this->GetControles($datosPaciente['ingreso']);
		if(!empty($controles))
          {
               $this->salida .= "<br><table align=\"center\" width=\"88%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_title\">\n";
               $this->salida .= "	<tr class=\"modulo_table_title\">\n";
               if(!empty($datosPaciente[cama]))
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

               for ($j=0;$j<sizeof($controles);$j++){
                    $this->FrmControles(array("ingreso"=>$datosPaciente[ingreso],"control_id"=>$controles[$j]['control_id']));
                    $this->salida .= "<br>\n";
               }
          }
          else
          {
               $mensaje = "EL PACIENTE NO TIENE CONTROLES PROGRAMADOS";
               $titulo = "MENSAJE";
               $link = "PANEL ENFERMERIA";
               $url = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
               $this->frmMSG($url,$titulo,$mensaje,$link);
               return true;
          }
          
          if($_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO'])
          {
               $href = ModuloGetURL($_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['contenedor'],
               $_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['modulo'],
               $_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['tipo'],
               $_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['metodo'],
               $_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['argumentos']);
          }
          else
          {
	          $this->FrmPieDePagina();
          }
          return true;     
     }
     
     /*
     *	FrmControles: Vista que pinta los controles asignados a los pacientes.
     */
     function FrmControles($datos)
     {
          $ctrlPosicion = array();
          $controles = $this->GetControles($datos['ingreso']);
          switch($datos['control_id'])
          {
               case 1:
                    $ctrlPosicion=$this->FindControles($controles,$datos['control_id'],$datos['ingreso']);
                    if (!$this->ControlPosicion($ctrlPosicion))
                    {
                         return false;
                    }
               break;
               case 2:
                    $ctrlOxig=$this->FindControles($controles,$datos['control_id'],$datos['ingreso']);
                    if (!$this->ControlOxig($ctrlOxig))
                    	return false;
               break;
               case 3:
                    $ctrlReposo=$this->FindControles($controles,$datos['control_id'],$datos['ingreso']);
                    if (!$this->ControlReposo($ctrlReposo))
                    	return false;
               break;
               case 4:
                    $ctrlTerResp=$this->FindControles($controles,$datos['control_id'],$datos['ingreso']);
                    if (!$this->ControlTerResp($ctrlTerResp))
                    	return false;
               break;
               case 5:
                    $ctrlCurTerm=$this->FindControles($controles,$datos['control_id'],$datos['ingreso']);
                    if (!$this->ControlCurTerm($ctrlCurTerm))
                    	return false;
               break;
               case 6:
                    $ctrlLiquidos=$this->FindControles($controles,$datos['control_id'],$datos['ingreso']);
                    if (!$this->ControlLiquidos($ctrlLiquidos))
                    	return false;
               break;
               case 7:
                    $ctrlTA=$this->FindControles($controles,$datos['control_id'],$datos['ingreso']);
                    if (!$this->ControlTA($ctrlTA))
                    	return false;
               break;
               case 8:
                    $ctrlGlucometria=$this->FindControles($controles,$datos['control_id'],$datos['ingreso']);
                    if (!$this->ControlGlucometria($ctrlGlucometria))
                    	return false;
               break;
               case 9:
                    $ctrlCuraciones=$this->FindControles($controles,$datos['control_id'],$datos['ingreso']);
                    if (!$this->ControlCuraciones($ctrlCuraciones))
                         return false;
               break;
               case 10:
                    $ctrlNeurologico=$this->FindControles($controles,$datos['control_id'],$datos['ingreso']);
                    if (!$this->ControlNeurologico($ctrlNeurologico))
                         return false;
               break;
               case 11:
                    if (!IncludeLib('datospaciente'))
                    {
                         $this->error = "Error al cargar la libreria [datospaciente].";
                         $this->mensajeDeError = "datospaciente";
                         return false;
                    }
                    $datos_hc = GetDatosPaciente("","",$datos['ingreso'],"","");
                    $query="SELECT * FROM gestacion WHERE tipo_id_paciente='".$datos_hc['tipoidpaciente']."' AND paciente_id='".$datos_hc['paciente_id']."' ";
                    $resultado=$dbconn->Execute($query);
                    if (!$resultado) {
                         $this->error = "Error al ejecutar el query <br>".$query;
                         $this->mensajeDeError = $query;
                         return false;
                    }
                    if ($data->estado)
                    {
                         $ctrlParto=$this->FindControles($controles,$datos['control_id'],$datos['ingreso']);
                         if (!$this->ControlParto($ctrlParto))
                              return false;
                    }
               break;
               case 12:
                    $ctrlPerAbdominal=$this->FindControles($controles,$datos['control_id'],$datos['ingreso']);
                    if (!$this->ControlPerAbdominal($ctrlPerAbdominal))
                         return false;
               break;
               case 13:
                    $ctrlPerCefalico=$this->FindControles($controles,$datos['control_id'],$datos['ingreso']);
                    if (!$this->ControlPerCefalico($ctrlPerCefalico))
                         return false;
               break;
               case 14:
                    $ctrlPerExtremidades=$this->FindControles($controles,$datos['control_id'],$datos['ingreso']);
                    if (!$this->ControlPerExtremidades($ctrlPerExtremidades))
                         return false;
               break;
               case 25:
                    $ctrlPresDieta=$this->FindControles($controles,$datos['control_id'],$datos['ingreso']);
                    if (!$this->ControlPrescripcionDietas($ctrlPresDieta))
                         return false;
               break;
          }
          return true;
     }
     
     /*
     *	FindControles
     */
     function FindControles($control,$valor,$ingreso)
     {
          foreach($control as $key =>$value)
          {
               if ($value['control_id']==$valor && $value['ingreso']==$ingreso)
               {return $value;}
          }
          return false;
     }
     
     /*
     *	ControlPosicion
     */
     function ControlPosicion($control)
     {
          if (!empty($control))
          {
               $data = $this->VerificaPosicionesPaciente($control['evolucion_id']);
               if(!$data){
                    return false;
               }
               if(!is_array($data))
               {
                    $this->error = "Error, el paciente no cuenta con registros en \"hc_posicion_paciente\" con evolucion_id=".$control['evolucion_id'];
                    $this->mensajeDeError = $query;
                    return false;
               }

               $controles=$this->GetControlPosicion($data[posicion_id],0);
               $this->salida .= "	<table width='88%' align='center' border='0' class='modulo_table_list'>";
               $this->salida .= "		<tr>\n";
               $this->salida .= "			<td width='88%' align='left' colspan='2' class='modulo_table_title'>POSICION DEL PACIENTE</td>\n";
               $this->salida .= "		</tr>\n";
               if (!empty($data[posicion_id]))
               {
                    $this->salida .= "						<tr ".$this->Lista(1)."'>\n";
                    $this->salida .= "							<td width='20%'>Posici&oacute;n</td>\n";
                    $this->salida .= "							<td width='80%'>".$controles[0]['descripcion']."</td>\n";
                    $this->salida .= "						</tr>\n";
                    if (!empty($data[observaciones]))
                    {
                         $this->salida .= "						<tr ".$this->Lista(2)."'>\n";
                         $this->salida .= "							<td width='20%'>Observaci&oacute;n</td>\n";
                         $this->salida .= "							<td width='80%' align='justify'>".$data[observaciones]."</td>\n";
                         $this->salida .= "						</tr>\n";
                    }
               }
               $this->salida .= "	</table>\n";
          }
          return true;
     }
     
     /*
     *	ControlOxig
     */
     function ControlOxig($control)
     {
          list($dbconn) = GetDBconn();
          if (!empty($control))
          {
               $data = $this->VerificaOxigenoterapiaPaciente($control['evolucion_id']);
               if(!$data){
                    return false;
               }
               if(!is_array($data))
               {
                    $this->error = "Error, el paciente no cuenta con registros en \"hc_oxigenoterapia\" con evolucion_id=".$control['evolucion_id'];
                    $this->mensajeDeError = $query;
                    return false;
               }

               $metodo=$this->GetControlOxiMetodo($data[metodo_id],0);
               $concentracion=$this->GetControlOxiConcentraciones($data[concentracion_id],0);
               $flujo=$this->GetControlOxiFlujo($data[flujo_id],0);
               $contador=1;

               $this->salida .= "	<table width='100%' align='center' border='0' class='modulo_table_list'>";
               $this->salida .= "		<tr>\n";
               $this->salida .= "			<td width='88%' align='left' class='modulo_table_title' colspan='2'>OXIGENOTERAPIA</td>\n";
               $this->salida .= "		</tr>\n";
               if (!empty($data[metodo_id]))
               {
                    $this->salida .= "						<tr ".$this->Lista($contador)."'>\n";
                    $this->salida .= "							<td width='20%'>M&eacute;todo</td>\n";
                    $this->salida .= "							<td width='80%'>".$metodo[0]['descripcion']."</td>\n";
                    $this->salida .= "						</tr>\n";
                    $contador++;
               }
               if (!empty($data[concentracion_id]))
               {
                    $this->salida .= "						<tr ".$this->Lista($contador)."'>\n";
                    $this->salida .= "							<td width='20%'>Concentraci&oacute;n</td>\n";
                    $this->salida .= "							<td width='80%'>".$concentracion[0]['descripcion']."</td>\n";
                    $this->salida .= "						</tr>\n";
                    $contador++;
               }
               if (!empty($data[flujo_id]))
               {
                    $this->salida .= "						<tr ".$this->Lista($contador)."'>\n";
                    $this->salida .= "							<td width='20%'>Flujo</td>\n";
                    $this->salida .= "							<td width='80%'>".$flujo[0]['descripcion']."</td>\n";
                    $this->salida .= "						</tr>\n";
                    $contador++;
               }
               if (!empty($data[observaciones]))
               {
                    $this->salida .= "						<tr ".$this->Lista($contador)."'>\n";
                    $this->salida .= "							<td width='20%'>Observaci&oacute;n</td>\n";
                    $this->salida .= "							<td width='80%' align='justify'>".$data->observaciones."</td>\n";
                    $this->salida .= "						</tr>\n";
               }
               $this->salida .= "		</tr>\n";
               $this->salida .= "	</table>\n";
          }
          return true;
     }

     /*
     *	ControlReposo
     */
     function ControlReposo($control)
     {
          list($dbconn) = GetDBconn();
          if (!empty($control))
          {
               $query="SELECT * FROM hc_reposo_paciente_detalle WHERE evolucion_id=".$control['evolucion_id'];
               $query2="SELECT * FROM hc_reposo_paciente WHERE evolucion_id=".$control['evolucion_id'];
               $resultado2=$dbconn->Execute($query2);
               $resultado=$dbconn->Execute($query);
               if (!$resultado2)
               {
                    $this->error = "Error al consultar la tabla \"hc_reposo_paciente_detalle\" con evolucion_id=".$control['evolucion_id'];
                    $this->mensajeDeError = $query;
                    return false;
               }
               if (!$resultado)
               {
                    $this->error = "Error al consultar la tabla \"hc_reposo_paciente_detalle\" con evolucion_id=".$control['evolucion_id'];
                    $this->mensajeDeError = $query;
                    return false;
               }
               if (!$resultado->RecordCount())
               {
                    $this->error = "Error, el paciente no cuenta con registros en \"hc_reposo_paciente_detalle\" con evolucion_id=".$control['evolucion_id'];
                    $this->mensajeDeError = $query;
                    return false;
               }

               $this->salida .= "	<table width='88%' align='center' border='0' class='modulo_table_list'>";
               $this->salida .= "		<tr>\n";
               $this->salida .= "			<td width='100%' align='left' class='modulo_table_title' colspan='2'>REPOSO DEL PACIENTE</td>\n";
               $this->salida .= "		</tr>\n";
               $this->salida .= "		<tr>\n";
               while ($data=$resultado->FetchNextObject($toUpper=false))
               {
                    $controles=$this->GetControlReposo($data->tipo_reposo_id,0);
                    if (!empty($data->tipo_reposo_id))
                    {
                         $this->salida .= "						<tr ".$this->Lista(1)."'>\n";
                         $this->salida .= "							<td width='20%'>Tipo de Reposo</td>\n";
                         $this->salida .= "							<td width='80%'>".$controles[0]['descripcion']."</td>\n";
                         $this->salida .= "						</tr>\n";
                    }
               }
               $data=$resultado2->FetchNextObject($toUpper=false);
               if (!empty($data->observaciones)) {
                    $this->salida .= "						<tr ".$this->Lista(2)."'>\n";
                    $this->salida .= "							<td width='20%'>Observaci&oacute;n</td>\n";
                    $this->salida .= "							<td width='80%' align='justify'>".$data->observaciones."</td>\n";
                    $this->salida .= "						</tr>\n";
               }
               $this->salida .= "	</table>\n";
          }
          return true;
     }

     /*
     *	ControlTerResp
     */
     function ControlTerResp($control)
     {
          list($dbconn) = GetDBconn();

          if (!empty($control))
          {
               $data = $this->VerificaTerapiasRespiratoriasPacientes($control['evolucion_id']);
               if(!$data){
                    return false;
               }
               if(!is_array($data))
               {
                    $this->error = "Error, el paciente no cuenta con registros en \"hc_terapias_respiratorias\" con evolucion_id=".$control['evolucion_id'];
                    $this->mensajeDeError = $query;
                    return false;
               }

               $controles=$this->GetControlTerResp($data[frecuencia_id],0);
               $this->salida .= "	<table width='88%' align='center' border='0' class='modulo_table_list'>";
               $this->salida .= "		<tr>\n";
               $this->salida .= "			<td width='100%' align='left' class='modulo_table_title' colspan='2'>TERAPIA RESPIRATORIA</td>\n";
               $this->salida .= "		</tr>\n";
               if (!empty($data[frecuencia_id]))
               {
                    $this->salida .= "						<tr ".$this->Lista(1)."'>\n";
                    $this->salida .= "							<td width='20%'>Frecuencia</td>\n";
                    $this->salida .= "							<td width='80%'>".$controles[0]['descripcion']."</td>\n";
                    $this->salida .= "						</tr>\n";
               }
               if (!empty($data[observaciones]))
               {
                    $this->salida .= "						<tr ".$this->Lista(2)."'>\n";
                    $this->salida .= "							<td width='20%'>Observaci&oacute;n</td>\n";
                    $this->salida .= "							<td width='80%' align='justify'>".$data[observaciones]."</td>\n";
                    $this->salida .= "						</tr>\n";
               }
               $this->salida .= "	</table>\n";
          }
          return true;
     }

	/**
     *	ControlCurTerm
     */
     function ControlCurTerm($control)
     {
          list($dbconn) = GetDBconn();
          if (!empty($control))
          {
               $data = $this->VerificaCurvasTermicasPaciente($control['evolucion_id']);
               if(!$data){
                    return false;
               }
               if(!is_array($data))
               {
                    $this->error = "Error, el paciente no cuenta con registros en \"hc_posicion_paciente\" con evolucion_id=".$control['evolucion_id'];
                    $this->mensajeDeError = $query;
                    return false;
               }

               $controles=$this->GetControlCurTerm($data[frecuencia_id],0);

               $this->salida .= "	<table width='88%' align='center' border='0' class='modulo_table_list'>";
               $this->salida .= "		<tr>\n";
               $this->salida .= "			<td width='100%' align='left' class='modulo_table_title' colspan='2'>CURVA TERMICA</td>\n";
               $this->salida .= "		</tr>\n";
               if (!empty($data[frecuencia_id]))
               {
                    $this->salida .= "						<tr ".$this->Lista(1)."'>\n";
                    $this->salida .= "							<td width='20%'>Frecuencia</td>\n";
                    $this->salida .= "							<td width='80%'>".$controles[0]['descripcion']."</td>\n";
                    $this->salida .= "						</tr>\n";
               }
               if (!empty($data[observaciones])) {
                    $this->salida .= "						<tr ".$this->Lista(2)."'>\n";
                    $this->salida .= "							<td width='20%'>Observaci&oacute;n</td>\n";
                    $this->salida .= "							<td width='80%' align='justify'>".$data[observaciones]."</td>\n";
                    $this->salida .= "						</tr>\n";
               }
               $this->salida .= "	</table>\n";
          }
          return true;
     }


     /**
     *	ControlLiquidos
     */
     function ControlLiquidos($control)
     {
          list($dbconn) = GetDBconn();
          if (!empty($control))
          {
               $data = $this->VerificaControlLiquidosPaciente($control['evolucion_id']);
               if(!$data){
                    return false;
               }
               if(!is_array($data))
               {
                    $this->error = "Error, el paciente no cuenta con registros en \"hc_control_liquidos\" con evolucion_id=".$control['evolucion_id'];
                    $this->mensajeDeError = $query;
                    return false;
               }

               $controles=$this->GetControlLiquidos($control['evolucion_id'],0);

               $this->salida .= "	<table width='88%' align='center' border='0' class='modulo_table_list'>";
               $this->salida .= "		<tr>\n";
               $this->salida .= "			<td width='100%' align='left' class='modulo_table_title' colspan='2'>CONTROL DE LIQUIDOS INGERIDOS Y ELIMINADOS</td>\n";
               $this->salida .= "		</tr>\n";
               if (!empty($controles[0]['observaciones']))
               {
                    $this->salida .= "						<tr ".$this->Lista(1)."'>\n";
                    $this->salida .= "							<td width='20%'>Descripci&oacute;n</td>\n";
                    $this->salida .= "							<td width='80%'>".$controles[0]['observaciones']."</td>\n";
                    $this->salida .= "						</tr>\n";
               }
               $this->salida .= "	</table>\n";
          }
          return true;
     }
     
     /*
     *	ControlTA
     */
     function ControlTA($control)
     {
          list($dbconn) = GetDBconn();
          if (!empty($control))
          {
               $data = $this->verificaTensionArterialPaciente($control['evolucion_id']);
               if(!$data){
                    return false;
               }
               if(!is_array($data))
               {
                    $this->error = "Error, el paciente no cuenta con registros en \"hc_control_tension_arterial\" con evolucion_id=".$control['evolucion_id'];
                    $this->mensajeDeError = $query;
                    return false;
               }

               $controles=$this->GetControlTA($data[frecuencia_id],0);

               $this->salida .= "	<table width='88%' align='center' border='0' class='modulo_table_list'>";
               $this->salida .= "		<tr>\n";
               $this->salida .= "			<td width='100%' align='left' class='modulo_table_title' colspan='2'>TENSION ARTERIAL</td>\n";
               $this->salida .= "		</tr>\n";
               if (!empty($data[frecuencia_id]))
               {
                    $this->salida .= "						<tr ".$this->Lista(1)."'>\n";
                    $this->salida .= "							<td width='20%'>Frecuencia</td>\n";
                    $this->salida .= "							<td width='80%'>".$controles[0]['descripcion']."</td>\n";
                    $this->salida .= "						</tr>\n";
               }
               if (!empty($data[observaciones])) {
                    $this->salida .= "						<tr ".$this->Lista(2)."'>\n";
                    $this->salida .= "							<td width='20%'>Observaci&oacute;n</td>\n";
                    $this->salida .= "							<td width='80%' align='justify'>".$data[observaciones]."</td>\n";
                    $this->salida .= "						</tr>\n";
               }
               $this->salida .= "	</table>\n";
          }
          return true;
     }

     /*
     *	ControlGlucometria
     */
     function ControlGlucometria($control)
     {
          list($dbconn) = GetDBconn();
          if (!empty($control))
          {
               $data = $this->verificaGlucometriaPaciente($control['evolucion_id']);
               if(!$data){
                    return false;
               }
               if(!is_array($data))
               {
                    $this->error = "Error, el paciente no cuenta con registros en \"hc_control_glucometria\" con evolucion_id=".$control['evolucion_id'];
                    $this->mensajeDeError = $query;
                    return false;
               }

               $controles=$this->GetControlGlucometria($data[frecuencia_id],0);

               $this->salida .= "	<table width='88%' align='center' border='0' class='modulo_table_list'>";
               $this->salida .= "		<tr>\n";
               $this->salida .= "			<td width='100%' align='left' class='modulo_table_title' colspan='2'>GLUCOMETRIA</td>\n";
               $this->salida .= "		</tr>\n";
               if (!empty($data[frecuencia_id]))
               {
                    $this->salida .= "						<tr ".$this->Lista(1)."'>\n";
                    $this->salida .= "							<td width='20%'>Frecuencia</td>\n";
                    $this->salida .= "							<td width='80%'>".$controles[0]['descripcion']."</td>\n";
                    $this->salida .= "						</tr>\n";
               }
               if (!empty($data[observaciones])) {
                    $this->salida .= "						<tr ".$this->Lista(2)."'>\n";
                    $this->salida .= "							<td width='20%'>Observaci&oacute;n</td>\n";
                    $this->salida .= "							<td width='80%' align='justify'>".$data[observaciones]."</td>\n";
                    $this->salida .= "						</tr>\n";
               }
               $this->salida .= "	</table>\n";
          }
          return true;
     }

     /*
     *	ControlCUraciones
     */
     function ControlCuraciones($control)
     {
          list($dbconn) = GetDBconn();
          if (!empty($control))
          {
               $data = $this->verificaControlCuracionesPaciente($control['evolucion_id']);
               if(!$data){
                    return false;
               }
               if(!is_array($data))
               {
                    $this->error = "Error, el paciente no cuenta con registros en \"hc_control_curaciones\" con evolucion_id=".$control['evolucion_id'];
                    $this->mensajeDeError = $query;
                    return false;
               }

               $controles=$this->GetControlCuraciones($data[frecuencia_id],0);

               $this->salida .= "	<table width='88%' align='center' border='0' class='modulo_table_list'>";
               $this->salida .= "		<tr>\n";
               $this->salida .= "			<td width='100%' align='left' class='modulo_table_title' colspan='2'>CURACIONES</td>\n";
               $this->salida .= "		</tr>\n";
               if (!empty($data[frecuencia_id]))
               {
                    $this->salida .= "						<tr ".$this->Lista(1)."'>\n";
                    $this->salida .= "							<td width='20%'>Frecuencia</td>\n";
                    $this->salida .= "							<td width='80%'>".$controles[0]['descripcion']."</td>\n";
                    $this->salida .= "						</tr>\n";
               }
               if (!empty($data[observaciones])) {
                    $this->salida .= "						<tr ".$this->Lista(2)."'>\n";
                    $this->salida .= "							<td width='20%'>Observaci&oacute;n</td>\n";
                    $this->salida .= "							<td width='80%' align='justify'>".$data[observaciones]."</td>\n";
                    $this->salida .= "						</tr>\n";
               }
               $this->salida .= "	</table>\n";
          }
          return true;
     }
		
     /*
     *	ControlNeurologico
     */
     function ControlNeurologico($control)
     {
          $pfj=$this->frmPrefijo;
          list($dbconn) = GetDBconn();

          if (!empty($control))
          {
               $data = $this->verificaControlNeurologicoPaciente($control['evolucion_id']);
               if(!$data){
                    return false;
               }
               if(!is_array($data))
               {
                    $this->error = "Error, el paciente no cuenta con registros en \"hc_control_curaciones\" con evolucion_id=".$control['evolucion_id'];
                    $this->mensajeDeError = $query;
                    return false;
               }

               $controles=$this->GetControlNeurologico($data[frecuencia_id],0);

               $this->salida .= "	<table width='88%' align='center' border='0' class='modulo_table_list'>";
               $this->salida .= "		<tr>\n";
               $this->salida .= "			<td width='100%' align='left' class='modulo_table_title' colspan='2'>CONTROL NEUROLOGICO</td>\n";
               $this->salida .= "		</tr>\n";
               if (!empty($data[frecuencia_id]))
               {
                    $this->salida .= "						<tr ".$this->Lista(1)."'>\n";
                    $this->salida .= "							<td width='20%'>Frecuencia</td>\n";
                    $this->salida .= "							<td width='80%'>".$controles[0]['descripcion']."</td>\n";
                    $this->salida .= "						</tr>\n";
               }
               if (!empty($data[observaciones])) {
                    $this->salida .= "						<tr ".$this->Lista(2)."'>\n";
                    $this->salida .= "							<td width='20%'>Observaci&oacute;n</td>\n";
                    $this->salida .= "							<td width='80%' align='justify'>".$data[observaciones]."</td>\n";
                    $this->salida .= "						</tr>\n";
               }
               $this->salida .= "	</table>\n";
          }
          return true;
     }

     /*
     *	ControlParto
     */
     function ControlParto($control)
     {
          list($dbconn) = GetDBconn();
          if (!empty($control))
          {
               $controles=$this->GetControlParto($control['evolucion_id']);

               $this->salida .= "	<table width='88%' align='center' border='0' class='modulo_table_list'>";
               $this->salida .= "		<tr>\n";
               $this->salida .= "			<td width='100%' align='left' class='modulo_table_title' colspan='2'>CONTROL DE TRABAJO DE PARTO</td>\n";
               $this->salida .= "		</tr>\n";
               if (!empty($controles[0]['observaciones']))
               {
                    $this->salida .= "						<tr ".$this->Lista(1)."'>\n";
                    $this->salida .= "							<td width='20%'>Descripci&oacute;n</td>\n";
                    $this->salida .= "							<td width='80%'>".$controles[0]['observaciones']."</td>\n";
                    $this->salida .= "						</tr>\n";
               }
               $this->salida .= "	</table>\n";
          }
          return true;
     }
		
     /*
     *	ControlPerAbdominal
     */
     function ControlPerAbdominal($control)
     {
          list($dbconn) = GetDBconn();
          if (!empty($control))
          {
               $data = $this->verificaPerimetroAbdominalPaciente($control['evolucion_id']);
               if(!$data){
                    return false;
               }
               if(!is_array($data))
               {
                    $this->error = "Error, el paciente no cuenta con registros en \"hc_control_perimetro_abdominal\" con evolucion_id=".$control['evolucion_id'];
                    $this->mensajeDeError = $query;
                    return false;
               }

               $controles=$this->GetControlPerAbdominal($control['evolucion_id'],0);

               $this->salida .= "	<table width='88%' align='center' border='0' class='modulo_table_list'>";
               $this->salida .= "		<tr>\n";
               $this->salida .= "			<td width='100%' align='left' class='modulo_table_title' colspan='2'>PERIMETRO ABDOMINAL</td>\n";
               $this->salida .= "		</tr>\n";
               if (!empty($controles[0]['observaciones']))
               {
                    $this->salida .= "						<tr ".$this->Lista(1)."'>\n";
                    $this->salida .= "							<td width='20%'>Descripci&oacute;n</td>\n";
                    $this->salida .= "							<td width='80%'>".$controles[0]['observaciones']."</td>\n";
                    $this->salida .= "						</tr>\n";
               }
               $this->salida .= "	</table>\n";
          }
          return true;
     }

     /*
     *	ControlPerCefalico
     */
     function ControlPerCefalico($control)
     {
          list($dbconn) = GetDBconn();
          if (!empty($control))
          {
               $data = $this->verificaPerimetroCefalicoPaciente($control['evolucion_id']);
               if(!$data){
                    return false;
               }
               if(!is_array($data))
               {
                    $this->error = "Error, el paciente no cuenta con registros en \"hc_control_perimetro_cefalico\" con evolucion_id=".$control['evolucion_id'];
                    $this->mensajeDeError = $query;
                    return false;
               }

               $controles=$this->GetControlPerCefalico($control['evolucion_id'],0);

               $this->salida .= "	<table width='100%' align='center' border='0' class='modulo_table_list'>";
               $this->salida .= "		<tr>\n";
               $this->salida .= "			<td width='100%' align='left' class='modulo_table_title' colspan='2'>PERIMETRO CEFALICO</td>\n";
               $this->salida .= "		</tr>\n";
               if (!empty($controles[0]['observaciones']))
               {
                    $this->salida .= "						<tr ".$this->Lista(1)."'>\n";
                    $this->salida .= "							<td width='20%'>Descripci&oacute;n</td>\n";
                    $this->salida .= "							<td width='80%'>".$controles[0]['observaciones']."</td>\n";
                    $this->salida .= "						</tr>\n";
               }
               $this->salida .= "	</table>\n";
          }
          return true;
     }

     /*
     *	ControlPerExtremidades
     */
     function ControlPerExtremidades($control)
     {
          list($dbconn) = GetDBconn();
          if (!empty($control))
          {
               $query="SELECT * FROM hc_control_perimetro_extremidades_detalle WHERE evolucion_id=".$control['evolucion_id'];
               $query2="SELECT * FROM hc_control_perimetro_extremidades WHERE evolucion_id=".$control['evolucion_id'];
               $resultado2=$dbconn->Execute($query2);
               $resultado=$dbconn->Execute($query);
               if (!$resultado2)
               {
                    $this->error = "Error al consultar la tabla \"hc_control_perimetro_extremidades\" con evolucion_id=".$control['evolucion_id'];
                    $this->mensajeDeError = $query;
                    return false;
               }
               if (!$resultado)
               {
                    $this->error = "Error al consultar la tabla \"hc_control_perimetro_extremidades_detalle\" con evolucion_id=".$control['evolucion_id'];
                    $this->mensajeDeError = $query;
                    return false;
               }
               if (!$resultado->RecordCount())
               {
                    $this->error = "Error, el paciente no cuenta con registros en \"hc_control_perimetro_extremidades_detalle\" con evolucion_id=".$control['evolucion_id'];
                    $this->mensajeDeError = $query;
                    return false;
               }

               $this->salida .= "	<table width='88%' align='center' border='0' class='modulo_table_list'>";
               $this->salida .= "		<tr>\n";
               $this->salida .= "			<td width='100%' align='left' class='modulo_table_title' colspan='2'>PERIMETRO DE EXTREMIDADES</td>\n";
               $this->salida .= "		</tr>\n";
               while ($data=$resultado->FetchNextObject($toUpper=false))
               {
                    $controles=$this->GetControlPerExtremidades($data->tipo_extremidad_id,0);
                    if (!empty($data->tipo_extremidad_id))
                    {
                         $this->salida .= "						<tr ".$this->Lista($i++)."'>\n";
                         $this->salida .= "							<td width='20%'>Tipo de Perimetro de extremidad</td>\n";
                         $this->salida .= "							<td width='80%'>".$controles[0]['descripcion']."</td>\n";
                         $this->salida .= "						</tr>\n";
                    }
               }
               $data=$resultado2->FetchNextObject($toUpper=false);
               if (!empty($data->observaciones)) {
                    $this->salida .= "						<tr ".$this->Lista($i++)."'>\n";
                    $this->salida .= "							<td width='20%'>Observaci&oacute;n</td>\n";
                    $this->salida .= "							<td width='80%' align='justify'>".$data->observaciones."</td>\n";
                    $this->salida .= "						</tr>\n";
               }
               $this->salida .= "	</table>\n";
          }
          return true;
     }
     
     /*
     *	ControlPrescripcionDietas
     */
     function ControlPrescripcionDietas($control)
     {
          $dietas_d=$this->GetCControlDietasDetalle($control);
          if ($dietas_d===false || !is_array($dietas_d))
               return false;
          $this->salida .= "					<table width='88%' align=\"center\" border='0' class='modulo_table_list'>\n";
          $this->salida .= "		<tr>\n";
          $this->salida .= "			<td width='100%' align='left' colspan='2' class='modulo_table_title'>PRESCRIPCION DE DIETAS</td>\n";
          $this->salida .= "		</tr>\n";

          if(sizeof($dietas_d)>1)
          {
               foreach ($dietas_d as $key => $value)
               {
                    $datos.=$value['descripcion'].",";
               }

               $this->salida .= "						<tr ".$this->Lista($key)."'>\n";
               $this->salida .= "							<td width='20%'>Tipo de Dieta</td>\n";
               $this->salida .= "							<td width='80%'>$datos</td>\n";
               $this->salida .= "						</tr>\n";unset($datos);
          }
          else{
               foreach ($dietas_d as $key => $value)
               {
                    $this->salida .= "						<tr ".$this->Lista($key)."'>\n";
                    $this->salida .= "							<td width='20%'>Tipo de Dieta</td>\n";
                    $this->salida .= "							<td width='80%'>".$value['descripcion']."</td>\n";
                    $this->salida .= "						</tr>\n";
               }
          }
          $data=$this->GetCControlDietas($control);
          if ($data===false || !is_array($data))
               return false;
          if (!empty($data['observaciones'])) {
               $this->salida .= "						<tr ".$this->Lista(2)."'>\n";
               $this->salida .= "							<td width='20%'>Observación</td>\n";
               $this->salida .= "							<td width='80%' align='justify'>".$data['observaciones']."</td>\n";
               $this->salida .= "						</tr>\n";
          }
          $this->salida .= "					</table>\n";
          return true;
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

