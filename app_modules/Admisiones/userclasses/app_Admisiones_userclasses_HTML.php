<?php

/**
 * $Id: app_Admisiones_userclasses_HTML.php,v 1.33 2006/09/14 23:32:46 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: Manejo logico de las autorizaciones.
 */

/**
*Contiene los metodos visuales para realizar las autorizaciones.
*/

class app_Admisiones_userclasses_HTML extends app_Admisiones_user
{
    /**
    *Constructor de la clase app_Autorizacion_user_HTML
    *El constructor de la clase app_Autorizacion_user_HTML se encarga de llamar
    *a la clase app_Autorizacion_user quien se encarga de el tratamiento
    * de la base de datos.
    */

  function app_Admisiones_user_HTML()
    {
                $this->salida='';
                $this->app_Admisiones_user();
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
    *
    */
    function FechaStamp($fecha)
    {
        if($fecha){
                $fech = strtok ($fecha,"-");
                for($l=0;$l<3;$l++)
                {
                    $date[$l]=$fech;
                    $fech = strtok ("-");
                }

                return  ceil($date[2])."/".str_pad(ceil($date[1]),2,0,STR_PAD_LEFT)."/".str_pad(ceil($date[0]),2,0,STR_PAD_LEFT);
        }
 }

    /**
    * Separa la hora del formato timestamp
    * @access private
    * @return string
    * @param date hora
    */
    function HoraStamp($hora)
  {
    $hor = strtok ($hora," ");
    for($l=0;$l<4;$l++)
    {
      $time[$l]=$hor;
      $hor = strtok (":");
    }
        $x=explode('.',$time[3]);
    return  $time[1].":".$time[2].":".$x[0];
  }


    function FormaBuscar($arr,$tipoPac)
    {
                IncludeLib("datospaciente");
                IncludeLib("funciones_admision");
                if(empty($_SESSION['ADMISIONES']['TIPOSALIDA']))
                {  $action=ModuloGetURL('app','Admisiones','user','BuscarPaciente');  }
                else
                {  $action=ModuloGetURL('app','Admisiones','user','BuscarPacienteSalida');  }
        $this->salida .= ThemeAbrirTabla('ADMISIONES - BUSCAR PACIENTE');
                $this->salida .= "                <table width=\"50%\" align=\"center\" border=\"0\">";
                $this->salida .= "             <form name=\"formabuscar\" action=\"$action\" method=\"post\">";
                $this->salida .= $this->SetStyle("MensajeError");
                $this->salida .= "                     <tr><td  class=\"".$this->SetStyle("Tipo")."\">TIPO DOCUMENTO: </td><td><select name=\"TipoDocumento\" class=\"select\">";
                $this->salida .=" <option value=\"\">-------SELECCIONE-------</option>";
                $tipos=TiposIdPacientes();
                for($i=0; $i<sizeof($tipos); $i++)
                {
                        if($tipos[$i][tipo_id_paciente]==$_SESSION['ADMISIONES']['BUSQUEDA']['tipo_id_paciente'])
                        {  $this->salida .=" <option value=\"".$tipos[$i][tipo_id_paciente]."\" selected>".$tipos[$i][descripcion]."</option>";  }
                        else
                        {  $this->salida .=" <option value=\"".$tipos[$i][tipo_id_paciente]."\">".$tipos[$i][descripcion]."</option>";  }
                }
                $this->salida .= "              </select></td></tr>";
                $this->salida .= "                     <tr><td class=\"".$this->SetStyle("Documento")."\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" value=\"".$_SESSION['ADMISIONES']['BUSQUEDA']['paciente_id']."\"></td></tr>";
                $this->salida .= "                     <tr><td class=\"".$this->SetStyle("nombre")."\">NOMBRES: </td><td><input type=\"text\" class=\"input-text\" name=\"nombre\" value=\"".$_SESSION['ADMISIONES']['BUSQUEDA']['Nombres']."\"></td></tr>";
                $campo=BuscarCamposObligatoriosPacientes();
                if($campo[historia_prefijo][sw_mostrar]==1)
                {
                        $this->salida .= "    <tr height=\"20\">";
                        $this->salida .= "      <td class=\"".$this->SetStyle("prefijo")."\">PREFIJO: </td>";
                        $this->salida .= "      <td><input type=\"text\" maxlength=\"4\" name=\"prefijo\" value=\"".$_SESSION['ADMISIONES']['BUSQUEDA']['prefijo']."\" class=\"input-text\"></td>";
                        $this->salida .= "      <td></td>";
                        $this->salida .= "    </tr>";
                }
                if($campo[historia_numero][sw_mostrar]==1)
                {
                        $this->salida .= "      <td class=\"".$this->SetStyle("historia")."\">No. HISTORIA: </td>";
                        $this->salida .= "      <td  height=\"25\"><input type=\"text\" maxlength=\"50\" name=\"historia\" value=\"".$_SESSION['ADMISIONES']['BUSQUEDA']['historia']."\" class=\"input-text\"></td>";
                        $this->salida .= "      <td></td>";
                        $this->salida .= "    </tr>";
                }
                $this->salida .= "                     <tr><td align=\"right\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\"><br></td></form>";
                $contenedor=$_SESSION['ADMISIONES']['RETORNO']['contenedor'];
                $modulo=$_SESSION['ADMISIONES']['RETORNO']['modulo'];
                $tipo=$_SESSION['ADMISIONES']['RETORNO']['tipo'];
                $metodo=$_SESSION['ADMISIONES']['RETORNO']['metodo'];
                $argumentos=$_SESSION['ADMISIONES']['RETORNO']['argumentos'];
                $actionM=ModuloGetURL($contenedor,$modulo,$tipo,$metodo,$argumentos);
                $this->salida .= "<td align=\"center\"><form name=\"enter\" action=\"$actionM\" method=\"post\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"><br></td></form></tr>";
                $this->salida .= "               </table>";

                //-------------SI SON SALIDAS---------------
                //salida de urgencias
                if($_SESSION['ADMISIONES']['TIPOSALIDA']==1 AND empty($arr))
                {
                        $arr=PacienteSalidaUrgencias($_SESSION['ADMISIONES']['EMPRESA'],$tipo_id_paciente='',$paciente_id='',$prefijo='',$historia='',$nombres='',$_SESSION['ADMISIONES']['CENTROUTILIDAD'],$_SESSION['ADMISIONES']['CU']);
                        $tipoPac=10;
                }
                elseif($_SESSION['ADMISIONES']['TIPOSALIDA']==2 AND empty($arr))
                {       //salida de hospitalizacion
                        $arr=PacienteSalidaEstacion($_SESSION['ADMISIONES']['EMPRESA'],$tipo_id_paciente='',$paciente_id='',$prefijo='',$historia='',$nombres='',$_SESSION['ADMISIONES']['CENTROUTILIDAD'],$_SESSION['ADMISIONES']['CU']);
                        $tipoPac=9;
                }

                //-------------------------------------------

                if(!empty($arr) AND empty($_SESSION['ADMISIONES']['TIPOSALIDA']))
                {
                        $this->salida .= "              <br><table width=\"90%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
                        $this->salida .= "                     <tr align=\"center\" class=\"modulo_table_list_title\">";
                        $this->salida .= "                       <td width=\"15%\">IDENTIFICACION</td>";
                        $this->salida .= "                       <td width=\"20%\">PACIENTE</td>";
                        $this->salida .= "                       <td width=\"65%\"></td>";
                        $this->salida .= "                     </tr>";
                        for($i=0; $i<sizeof($arr); $i++)
                        {
                                if( $i % 2){ $estilo='modulo_list_claro';}
                                else {$estilo='modulo_list_oscuro';}
                                $this->salida .= "                     <tr align=\"center\" class=\"$estilo\">";
                                $this->salida .= "                       <td>".$arr[$i][tipo_id_paciente]." ".$arr[$i][paciente_id]."</td>";
                                $this->salida .= "                       <td>".$arr[$i][nombre]."</td>";
                                $this->salida .= "                       <td>";
                                //esta pendiente de ser clasificado
                                if($tipoPac==1)
                                {
                                        $this->salida .= "                         <table width=\"100%\" border=\"0\" align=\"left\"  class=\"modulo_table_list_title\" cellspacing=\"3\" cellpadding=\"3\">";
                                        $this->salida .= "                             <tr class=\"$estilo\" align=\"center\">";
                                        $this->salida .= "                                       <td colspan=\"2\" class=label_mark>EL PACIENTE ESTA PENDIENTE DE SER CLASIFICADO EN EL PUNTO ".$arr[$i][descripcion]."</td>";
                                        $this->salida .= "                                   </tr>";
                                        $this->salida .= "                             <tr class=\"$estilo\" align=\"left\">";
                                        $accion=ModuloGetURL('app','Admisiones','user','AdmitirTriage',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre],'plan'=>$arr[$i][plan_id],'ptoadmon'=>$arr[$i][punto_admision_id]));
                                        $this->salida .= "                                       <td width=\"50%\"><a href=\"$accion\"><img src=\"".GetThemePath()."/images/activo.gif\" border='0'>&nbsp; ADMITIRLO</a></td>";
                                        $accion2=ModuloGetURL('app','Admisiones','user','CambiarPtoTriage',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'nompto'=>$arr[$i][descripcion]));
                                        $this->salida .= "                                       <td width=\"50%\"><a href=\"$accion2\"><img src=\"".GetThemePath()."/images/editar.png\" border='0'>&nbsp; CAMBIAR PUNTO</a></td>";
                                        $this->salida .= "                                   </tr>";
                                        $this->salida .= "                             <tr class=\"$estilo\" align=\"left\">";
                                        $accion=ModuloGetURL('app','Admisiones','user','SacarPacienteLista',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'ingreso'=>$arr[$i][ingreso],'nombre'=>$arr[$i][nombre]));
                                        $this->salida .= "                                       <td width=\"50%\"><a href=\"$accion\"><img src=\"".GetThemePath()."/images/inactivo.gif\" border='0'>&nbsp;  SACAR LISTADO TRIAGE</a></td>";
                                        $accion1=ModuloGetURL('app','Admisiones','user','ModificarDatosPaciente',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id]));
                                        $this->salida .= "                                       <td width=\"50%\"><a href=\"$accion1\"><img src=\"".GetThemePath()."/images/modificar.png\" border='0'>&nbsp; MODIFICAR DATOS PACIENTE</a></td>";
                                        $this->salida .= "                                   </tr>";
                                        $this->salida .= "                           </table>";
                                }
                                //esta pendiente de ser admitido
                                if($tipoPac==2)
                                {
                                        $this->salida .= "                         <table width=\"100%\" border=\"0\" align=\"left\"  class=\"modulo_table_list_title\" cellspacing=\"3\" cellpadding=\"3\">";
                                        $this->salida .= "                             <tr class=\"$estilo\" align=\"center\">";
                                        $this->salida .= "                                       <td colspan=\"2\" class=label_mark>EL PACIENTE ESTA PENDIENTE DE SER ADMITIDO EN EL PUNTO ".$arr[$i][descripcion]."</td>";
                                        $this->salida .= "                                   </tr>";
                                        $this->salida .= "                             <tr class=\"$estilo\" align=\"left\">";
                                        $accion=ModuloGetURL('app','Admisiones','user','AdmitirTriage',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre],'plan'=>$arr[$i][plan_id],'ptoadmon'=>$arr[$i][punto_admision_id]));
                                        $this->salida .= "                                       <td width=\"50%\"><a href=\"$accion\"><img src=\"".GetThemePath()."/images/activo.gif\" border='0'>&nbsp; ADMITIRLO</a></td>";
                                        $this->salida .= "                                       <td width=\"50%\"><a href=\"\"><img src=\"".GetThemePath()."/images/no_usuarios.png\" border='0'>&nbsp; REMITIRLO</a></td>";
                                        $this->salida .= "                                   </tr>";
                                        $this->salida .= "                             <tr class=\"$estilo\" align=\"left\">";
                                        $accion=ModuloGetURL('app','Admisiones','user','SacarPacienteLista',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'ingreso'=>$arr[$i][ingreso],'nombre'=>$arr[$i][nombre]));
                                        $this->salida .= "                                       <td width=\"50%\"><a href=\"$accion\"><img src=\"".GetThemePath()."/images/inactivo.gif\" border='0'>&nbsp;  SACAR LISTADO ADMISION</a></td>";
                                        $accion1=ModuloGetURL('app','Admisiones','user','ModificarDatosPaciente',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id]));
                                        $this->salida .= "                                       <td width=\"50%\"><a href=\"$accion1\"><img src=\"".GetThemePath()."/images/modificar.png\" border='0'>&nbsp; MODIFICAR DATOS PACIENTE</a></td>";
                                        $this->salida .= "                                   </tr>";
                                        $this->salida .= "                           </table>";
                                }
                                //esta pacientes_urgencias pte medico atienda
                                if($tipoPac==3)
                                {
                                        $this->salida .= "                         <table width=\"100%\" border=\"0\" align=\"left\"  class=\"modulo_table_list_title\" cellspacing=\"3\" cellpadding=\"3\">";
                                        $this->salida .= "                             <tr class=\"$estilo\" align=\"center\">";
                                        $this->salida .= "                                       <td colspan=\"2\" class=label_mark>PACIENTE PENDIENTE DE SER ATENDIDO POR EL PROFESIONAL EN ".$arr[$i][descripcion]."</td>";
                                        $this->salida .= "                                   </tr>";
                                        $this->salida .= "                             <tr class=\"$estilo\" align=\"left\">";
                                        $accion=ModuloGetURL('app','Admisiones','user','CambiarEstacion',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'ingreso'=>$arr[$i][ingreso],'estacion'=>$arr[$i][estacion_id],'nomestacion'=>$arr[$i][descripcion]));
                                        $this->salida .= "                                       <td width=\"50%\"><a href=\"$accion\"><img src=\"".GetThemePath()."/images/traslado.png\" border='0'>&nbsp; CAMBIAR ESTACION</a></td>";
                                        $accion=ModuloGetURL('app','Admisiones','user','SacarPacienteLista',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'ingreso'=>$arr[$i][ingreso],'nombre'=>$arr[$i][nombre],'lista'=>1));
                                        $this->salida .= "                                       <td width=\"50%\"><a href=\"$accion\"><img src=\"".GetThemePath()."/images/inactivo.gif\" border='0'>&nbsp;  SACAR LISTADO PROFESIONAL</a></td>";
                                        $this->salida .= "                                   </tr>";
                                        $this->salida .= "                             <tr class=\"$estilo\" align=\"left\">";
                                        $col=2;
                                        if(!empty($arr[$i][triage_id]))
                                        {
                                                $nivel=$this->NivelTriage($arr[$i][triage_id]);
                                                if(!empty($nivel))
                                                {
                                                        $accion2=ModuloGetURL('app','Admisiones','user','ConsultaTriage',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre]));
                                                        $this->salida .= "                                       <td><a href=\"$accion2\"><img src=\"".GetThemePath()."/images/especialidad.png\" border='0'>&nbsp; CONSULTAR TRIAGE</a></td>";
                                                        $col=1;
                                                }
                                        }
                                        $accion1=ModuloGetURL('app','Admisiones','user','ModificarDatosPaciente',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'ingreso'=>$arr[$i][ingreso]));
                                        $this->salida .= "                                       <td colspan=\"$col\"><a href=\"$accion1\"><img src=\"".GetThemePath()."/images/modificar.png\" border='0'>&nbsp; MODIFICAR DATOS</a></td>";
                                        $this->salida .= "                                   </tr>";
                                        $this->salida .= "                           </table>";
                                }
                                //paciente pte por ingresar en una estacion
                                if($tipoPac==4)
                                {
                                        $this->salida .= "                         <table width=\"100%\" border=\"0\" align=\"left\"  class=\"modulo_table_list_title\" cellspacing=\"3\" cellpadding=\"3\">";
                                        $this->salida .= "                             <tr class=\"$estilo\" align=\"center\">";
                                        $this->salida .= "                                       <td colspan=\"2\" class=label_mark>EL PACIENTE ESTA PENDIENTE POR INGRESAR EN LA ESTACION ".$arr[$i][descripcion]."</td>";
                                        $this->salida .= "                                   </tr>";
                                        $this->salida .= "                             <tr class=\"$estilo\" align=\"left\">";
                                        $col=2;
                                        if(!empty($arr[$i][triage_id]))
                                        {
                                                $nivel=$this->NivelTriage($arr[$i][triage_id]);
                                                if(!empty($nivel))
                                                {
                                                        $accion2=ModuloGetURL('app','Admisiones','user','ConsultaTriage',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre]));
                                                        $this->salida .= "                                       <td width=\"50%\"><a href=\"$accion2\"><img src=\"".GetThemePath()."/images/especialidad.png\" border='0'>&nbsp; CONSULTAR TRIAGE</a></td>";
                                                        $col=1;
                                                }
                                        }
                                        $accion1=ModuloGetURL('app','Admisiones','user','ModificarDatosPaciente',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'ingreso'=>$arr[$i][ingreso]));
                                        $this->salida .= "                                       <td colspan=\"$col\"><a href=\"$accion1\"><img src=\"".GetThemePath()."/images/modificar.png\" border='0'>&nbsp; MODIFICAR DATOS</a></td>";
                                        $this->salida .= "                                   </tr>";
                                        $this->salida .= "                           </table>";
                                }
                                //el paciente fue remitido
                                if($tipoPac==5)
                                {
                                        $this->salida .= "                         <table width=\"100%\" border=\"0\" align=\"left\"  class=\"modulo_table_list_title\" cellspacing=\"3\" cellpadding=\"3\">";
                                        $this->salida .= "                             <tr class=\"$estilo\" align=\"center\">";
                                        $this->salida .= "                                       <td colspan=\"2\" class=label_mark>EL PACIENTE FUE REMITIDO ".$arr[$i][descripcion]."</td>";
                                        $this->salida .= "                                   </tr>";
                                        $this->salida .= "                             <tr class=\"$estilo\" align=\"left\">";
                                        $accion2=ModuloGetURL('app','Admisiones','user','PacienteRemitido',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre]));
                                        $this->salida .= "                                       <td colspan=\"2\"><a href=\"$accion2\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp; IMPRIMIR REMISION</a></td>";
                                        $this->salida .= "                                   </tr>";
                                        $this->salida .= "                           </table>";
                                }
                                //paciente  que esta en una estacion
                                if($tipoPac==6)
                                {
                                        $this->salida .= "                         <table width=\"100%\" border=\"0\" align=\"left\"  class=\"modulo_table_list_title\" cellspacing=\"3\" cellpadding=\"3\">";
                                        $this->salida .= "                             <tr class=\"$estilo\" align=\"center\">";
                                        $msg='';
                                        if(!empty($arr[$i][egreso_dpto_id])){  $msg='- PENDIENTE DE SALIDA'; }
                                        $this->salida .= "                                       <td colspan=\"2\" class=label_mark>EL PACIENTE ESTA EN LA ESTACION ".$arr[$i][descripcion]." ".$msg."</td>";
                                        $this->salida .= "                                   </tr>";
                                        $this->salida .= "                             <tr class=\"$estilo\" align=\"left\">";
                                        $accion=ModuloGetURL('app','Admisiones','user','UbicacionPacienteEstacion',array('tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre],'nombre_estacion'=>$arr[$i][descripcion],'cama'=>$arr[$i][cama],'pieza'=>$arr[$i][pieza],'ubicacion'=>$arr[$i][ubicacion]));
                                        $this->salida .= "                                       <td width=\"50%\"><a href=\"$accion\"><img src=\"".GetThemePath()."/images/servicios.png\" border='0'>&nbsp;  UBICACION DEL PACIENTE</a></td>";
                                        $accion1=ModuloGetURL('app','Admisiones','user','ModificarDatosPaciente',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'ingreso'=>$arr[$i][ingreso]));
                                        $this->salida .= "                                       <td width=\"50%\"><a href=\"$accion1\"><img src=\"".GetThemePath()."/images/modificar.png\" border='0'>&nbsp; MODIFICAR DATOS</a></td>";
                                        $this->salida .= "                                   </tr>";
                                        if(!empty($arr[$i][triage_id]))
                                        {
                                                $nivel=$this->NivelTriage($arr[$i][triage_id]);
                                                if(!empty($nivel))
                                                {
                                                        $this->salida .= "                             <tr class=\"$estilo\" align=\"left\">";
                                                        $accion2=ModuloGetURL('app','Admisiones','user','ConsultaTriage',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre]));
                                                        $this->salida .= "                                       <td><a href=\"$accion2\"><img src=\"".GetThemePath()."/images/especialidad.png\" border='0'>&nbsp; CONSULTAR TRIAGE</a></td>";
                                                        $col=1;
                                                        $this->salida .= "                                   </tr>";
                                                }
                                        }

                                        if(!empty($arr[$i][egreso_dpto_id]))
                                        {
                                                $this->salida .= "                             <tr class=\"$estilo\" align=\"left\">";
                                                $accion2=ModuloGetURL('app','Admisiones','user','ImpresionSolicitudes',array('tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'ingreso'=>$arr[$i][ingreso],'evolucion'=>$arr[$i][evolucion_id]));
                                                $this->salida .= "                                       <td width=\"50%\"><a href=\"$accion2\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp;  IMPRIMIR SOLICITUDES MEDICA</a></td>";
                                                $this->salida .= "                                       <td width=\"50%\"><a href=\"$accion3\"><img src=\"".GetThemePath()."/images/modificar.png\" border='0'>&nbsp; ALGO</a></td>";
                                                $this->salida .= "                                   </tr>";
                                        }
                                        $this->salida .= "                           </table>";
                                }
                                //el paciente que el asistencial pidio remision
                                if($tipoPac==7)
                                {
                                        $this->salida .= "                         <table width=\"100%\" border=\"0\" align=\"left\"  class=\"modulo_table_list_title\" cellspacing=\"3\" cellpadding=\"3\">";
                                        $this->salida .= "                             <tr class=\"$estilo\" align=\"center\">";
                                        $this->salida .= "                                       <td colspan=\"2\" class=label_mark>PACIENTE PENDIENTE DE SER ATENDIDO POR EL PROFESIONAL (SOLICITUD REMISION)</td>";
                                        $this->salida .= "                                   </tr>";
                                        $this->salida .= "                             <tr class=\"$estilo\" align=\"left\">";
                                        $accion=ModuloGetURL('app','Admisiones','user','AdmitirTriage',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre],'plan'=>$arr[$i][plan_id],'ptoadmon'=>$arr[$i][punto_admision_id]));
                                        $this->salida .= "                                       <td width=\"50%\"><a href=\"$accion\"><img src=\"".GetThemePath()."/images/activo.gif\" border='0'>&nbsp; ADMITIRLO</a></td>";
                                        $this->salida .= "                                       <td width=\"50%\"><a href=\"\"><img src=\"".GetThemePath()."/images/no_usuarios.png\" border='0'>&nbsp; REMITIRLO</a></td>";
                                        $this->salida .= "                                   </tr>";
                                        $this->salida .= "                             <tr class=\"$estilo\" align=\"left\">";
                                        $accion=ModuloGetURL('app','Admisiones','user','SacarPacienteLista',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'ingreso'=>$arr[$i][ingreso],'nombre'=>$arr[$i][nombre]));
                                        $this->salida .= "                                       <td width=\"50%\"><a href=\"$accion\"><img src=\"".GetThemePath()."/images/inactivo.gif\" border='0'>&nbsp;  SACAR LISTADO</a></td>";
                                        $accion1=ModuloGetURL('app','Admisiones','user','ModificarDatosPaciente',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id]));
                                        $this->salida .= "                                       <td width=\"50%\"><a href=\"$accion1\"><img src=\"".GetThemePath()."/images/modificar.png\" border='0'>&nbsp; MODIFICAR DATOS PACIENTE</a></td>";
                                        $this->salida .= "                                   </tr>";
                                        if(!empty($arr[$i][triage_id]))
                                        {
                                                $nivel=$this->NivelTriage($arr[$i][triage_id]);
                                                if(!empty($nivel))
                                                {
                                                        $this->salida .= "                             <tr class=\"$estilo\" align=\"left\">";
                                                        $accion2=ModuloGetURL('app','Admisiones','user','ConsultaTriage',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre]));
                                                        $this->salida .= "                                       <td colspan=\"2\"><a href=\"$accion2\"><img src=\"".GetThemePath()."/images/especialidad.png\" border='0'>&nbsp; CONSULTAR TRIAGE</a></td>";
                                                        $this->salida .= "                                   </tr>";
                                                }
                                        }
                                        $this->salida .= "                           </table>";
                                }
                                //el paciente que el asistencial dio un nivel y lo mando a un pto admision
                                if($tipoPac==8)
                                {
                                        $this->salida .= "                         <table width=\"100%\" border=\"0\" align=\"left\"  class=\"modulo_table_list_title\" cellspacing=\"3\" cellpadding=\"3\">";
                                        $this->salida .= "                             <tr class=\"$estilo\" align=\"center\">";
                                        $this->salida .= "                                       <td colspan=\"2\" class=label_mark>PACIENTE PENDIENTE DE SER CLASIFICADO POR EL MEDICO EN LA ESTACION ".$arr[$i][estacion]."</td>";
                                        $this->salida .= "                                   </tr>";
                                        $this->salida .= "                             <tr class=\"$estilo\" align=\"left\">";
                                        $accion=ModuloGetURL('app','Admisiones','user','AdmisionLista',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre],'plan'=>$arr[$i][plan_id],'ptoadmon'=>$arr[$i][punto_admision_id]));
                                        $this->salida .= "                                       <td width=\"50%\"><a href=\"$accion\"><img src=\"".GetThemePath()."/images/activo.gif\" border='0'>&nbsp; ADMITIRLO</a></td>";
                                        $this->salida .= "                                       <td width=\"50%\"><a href=\"\"><img src=\"".GetThemePath()."/images/no_usuarios.png\" border='0'>&nbsp; REMITIRLO</a></td>";
                                        $this->salida .= "                                   </tr>";
                                        $this->salida .= "                             <tr class=\"$estilo\" align=\"left\">";
                                        $accion=ModuloGetURL('app','Admisiones','user','SacarPacienteLista',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'ingreso'=>$arr[$i][ingreso],'nombre'=>$arr[$i][nombre],'lista'=>2));
                                        $this->salida .= "                                       <td width=\"50%\"><a href=\"$accion\"><img src=\"".GetThemePath()."/images/inactivo.gif\" border='0'>&nbsp;  SACAR LISTADO</a></td>";
                                        $accion1=ModuloGetURL('app','Admisiones','user','ModificarDatosPaciente',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id]));
                                        $this->salida .= "                                       <td width=\"50%\"><a href=\"$accion1\"><img src=\"".GetThemePath()."/images/modificar.png\" border='0'>&nbsp; MODIFICAR DATOS PACIENTE</a></td>";
                                        $this->salida .= "                                   </tr>";
                                        if(!empty($arr[$i][triage_id]))
                                        {
                                                $nivel=$this->NivelTriage($arr[$i][triage_id]);
                                                if(!empty($nivel))
                                                {
                                                        $this->salida .= "                             <tr class=\"$estilo\" align=\"left\">";
                                                        $accion2=ModuloGetURL('app','Admisiones','user','ConsultaTriage',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre]));
                                                        $this->salida .= "                                       <td colspan=\"2\"><a href=\"$accion2\"><img src=\"".GetThemePath()."/images/especialidad.png\" border='0'>&nbsp; CONSULTAR TRIAGE</a></td>";
                                                        $this->salida .= "                                   </tr>";
                                                }
                                        }
                                        $this->salida .= "                           </table>";
                                }
                                $this->salida .= "                       </td>";
                                $this->salida .= "                     </tr>";
                        }

                        $this->salida .= "                    </table>";
                }
                elseif(!empty($arr) AND !empty($_SESSION['ADMISIONES']['TIPOSALIDA']))
                {
                        //salida de pacientes
                        $this->FormaSalidas($arr,$tipoPac);
                }
       $this->salida .= ThemeCerrarTabla();
                return true;
    }

    /**
    *
    */
    function FormaSalidas($arr,$tipoPac)
    {
            IncludeLib("funciones_facturacion");
            $reporte= new GetReports();
            $this->salida .= "              <br><table width=\"90%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
            $this->salida .= "                     <tr align=\"center\" class=\"modulo_table_list_title\">";
            $this->salida .= "                       <td width=\"15%\">IDENTIFICACION</td>";
            $this->salida .= "                       <td width=\"20%\">PACIENTE</td>";
            $this->salida .= "                       <td width=\"65%\"></td>";
            $this->salida .= "                     </tr>";
            for($i=0; $i<sizeof($arr); $i++)
            {
                    if( $i % 2){ $estilo='modulo_list_claro';}
                    else {$estilo='modulo_list_oscuro';}
                    $this->salida .= "                     <tr align=\"center\" class=\"$estilo\">";
                    $this->salida .= "                       <td>".$arr[$i][tipo_id_paciente]." ".$arr[$i][paciente_id]."</td>";
                    $this->salida .= "                       <td>".$arr[$i][nombre]."</td>";
                    $this->salida .= "                       <td>";
                    //esta pendiente de ser clasificado
                    if($tipoPac==1)
                    {
                            $this->salida .= "                         <table width=\"100%\" border=\"0\" align=\"left\"  class=\"modulo_table_list_title\" cellspacing=\"3\" cellpadding=\"3\">";
                            $this->salida .= "                             <tr class=\"$estilo\" align=\"center\">";
                            $this->salida .= "                                       <td colspan=\"2\" class=label_mark>EL PACIENTE ESTA PENDIENTE DE SER CLASIFICADO EN EL PUNTO ".$arr[$i][descripcion]."</td>";
                            $this->salida .= "                                   </tr>";
                            $this->salida .= "                           </table>";
                    }
                    //esta pendiente de ser admitido
                    if($tipoPac==2)
                    {
                            $this->salida .= "                         <table width=\"100%\" border=\"0\" align=\"left\"  class=\"modulo_table_list_title\" cellspacing=\"3\" cellpadding=\"3\">";
                            $this->salida .= "                             <tr class=\"$estilo\" align=\"center\">";
                            $this->salida .= "                                       <td colspan=\"2\" class=label_mark>EL PACIENTE ESTA PENDIENTE DE SER ADMITIDO EN EL PUNTO ".$arr[$i][descripcion]."</td>";
                            $this->salida .= "                                   </tr>";
                            $this->salida .= "                           </table>";
                    }
                    //esta pacientes_urgencias pte medico atienda
                    if($tipoPac==3)
                    {
                            $this->salida .= "                         <table width=\"100%\" border=\"0\" align=\"left\"  class=\"modulo_table_list_title\" cellspacing=\"3\" cellpadding=\"3\">";
                            $this->salida .= "                             <tr class=\"$estilo\" align=\"center\">";
                            $this->salida .= "                                       <td colspan=\"2\" class=label_mark>PACIENTE PENDIENTE DE SER ATENDIDO POR EL PROFESIONAL EN ".$arr[$i][descripcion]."</td>";
                            $this->salida .= "                                   </tr>";
                            $this->salida .= "                           </table>";
                    }
                    //paciente pte por ingresar en una estacion
                    if($tipoPac==4)
                    {
                            $this->salida .= "                         <table width=\"100%\" border=\"0\" align=\"left\"  class=\"modulo_table_list_title\" cellspacing=\"3\" cellpadding=\"3\">";
                            $this->salida .= "                             <tr class=\"$estilo\" align=\"center\">";
                            $this->salida .= "                                       <td colspan=\"2\" class=label_mark>EL PACIENTE ESTA PENDIENTE POR INGRESAR EN LA ESTACION ".$arr[$i][descripcion]."</td>";
                            $this->salida .= "                                   </tr>";
                            $this->salida .= "                           </table>";
                    }
                    //el paciente fue remitido
                    if($tipoPac==5)
                    {
                            $this->salida .= "                         <table width=\"100%\" border=\"0\" align=\"left\"  class=\"modulo_table_list_title\" cellspacing=\"3\" cellpadding=\"3\">";
                            $this->salida .= "                             <tr class=\"$estilo\" align=\"center\">";
                            $this->salida .= "                                       <td colspan=\"2\" class=label_mark>EL PACIENTE FUE REMITIDO ".$arr[$i][descripcion]."</td>";
                            $this->salida .= "                                   </tr>";
                            $this->salida .= "                           </table>";
                    }
                    //paciente  que esta en una estacion
                    if($tipoPac==6)
                    {
                            $this->salida .= "                         <table width=\"100%\" border=\"0\" align=\"left\"  class=\"modulo_table_list_title\" cellspacing=\"3\" cellpadding=\"3\">";
                            $this->salida .= "                             <tr class=\"$estilo\" align=\"center\">";
                            if(!empty($arr[$i][egreso_dpto_id])){  $msg='- PENDIENTE DE SALIDA'; }
                            $this->salida .= "                                       <td colspan=\"2\" class=label_mark>EL PACIENTE ESTA EN LA ESTACION ".$arr[$i][descripcion]." ".$msg."</td>";
                            $this->salida .= "                                   </tr>";
                            $this->salida .= "                           </table>";
                    }
                    //el paciente que el asistencial pidio remision
                    if($tipoPac==7)
                    {
                            $this->salida .= "                         <table width=\"100%\" border=\"0\" align=\"left\"  class=\"modulo_table_list_title\" cellspacing=\"3\" cellpadding=\"3\">";
                            $this->salida .= "                             <tr class=\"$estilo\" align=\"center\">";
                            $this->salida .= "                                       <td colspan=\"2\" class=label_mark>PACIENTE PENDIENTE DE SER ATENDIDO POR EL PROFESIONAL (SOLICITUD REMISION)</td>";
                            $this->salida .= "                                   </tr>";
                            $this->salida .= "                           </table>";
                    }
                    //el paciente que el asistencial dio un nivel y lo mando a un pto admision
                    if($tipoPac==8)
                    {
                            $this->salida .= "                         <table width=\"100%\" border=\"0\" align=\"left\"  class=\"modulo_table_list_title\" cellspacing=\"3\" cellpadding=\"3\">";
                            $this->salida .= "                             <tr class=\"$estilo\" align=\"center\">";
                            $this->salida .= "                                       <td colspan=\"2\" class=label_mark>PACIENTE PENDIENTE DE SER CLASIFICADO POR EL MEDICO EN LA ESTACION ".$arr[$i][estacion]."</td>";
                            $this->salida .= "                                   </tr>";
                            $this->salida .= "                           </table>";
                    }
                    //paciente  que esta en una estacion
                    if($tipoPac==9)
                    {
                            $this->salida .= "                         <table width=\"100%\" border=\"0\" align=\"left\"  class=\"modulo_table_list_title\" cellspacing=\"3\" cellpadding=\"3\">";
                            $this->salida .= "                             <tr class=\"$estilo\" align=\"center\">";
                            $msg='- PENDIENTE DE SALIDA';
                            $this->salida .= "                                       <td colspan=\"2\" class=label_mark>EL PACIENTE ESTA EN LA ESTACION ".$arr[$i][descripcion]." ".$msg."</td>";
                            $this->salida .= "                                   </tr>";
                            $this->salida .= "                             <tr class=\"$estilo\" align=\"left\">";
                            $accion=ModuloGetURL('app','Admisiones','user','UbicacionPacienteEstacion',array('tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre],'nombre_estacion'=>$arr[$i][descripcion],'cama'=>$arr[$i][cama],'pieza'=>$arr[$i][pieza],'ubicacion'=>$arr[$i][ubicacion]));
                            $this->salida .= "                                       <td><a href=\"$accion\"><img src=\"".GetThemePath()."/images/servicios.png\" border='0'>&nbsp;  UBICACION DEL PACIENTE</a></td>";
                            $accion1=ModuloGetURL('app','Admisiones','user','ModificarDatosPaciente',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'ingreso'=>$arr[$i][ingreso]));
                            $this->salida .= "                                       <td><a href=\"$accion1\"><img src=\"".GetThemePath()."/images/modificar.png\" border='0'>&nbsp; MODIFICAR DATOS</a></td>";
                            $this->salida .= "                                   </tr>";
                            if(!empty($arr[$i][triage_id]))
                            {
                                    $nivel=$this->NivelTriage($arr[$i][triage_id]);
                                    if(!empty($nivel))
                                    {
                                            $this->salida .= "                             <tr class=\"$estilo\" align=\"left\">";
                                            $accion2=ModuloGetURL('app','Admisiones','user','ConsultaTriage',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre]));
                                            $this->salida .= "                                       <td><a href=\"$accion2\"><img src=\"".GetThemePath()."/images/especialidad.png\" border='0'>&nbsp; CONSULTAR TRIAGE</a></td>";
                                            $col=1;
                                            $this->salida .= "                                   </tr>";
                                    }
                            }

                            if(!empty($arr[$i][egreso_dpto_id]))
                            {
                                    $this->salida .= "                             <tr class=\"$estilo\" align=\"left\">";
                                    $accion2=ModuloGetURL('app','Admisiones','user','ImpresionSolicitudes',array('tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'evolucion'=>$arr[$i][evolucion_id],'ingreso'=>$arr[$i][ingreso]));
                                    $this->salida .= "                                       <td width=\"50%\"><a href=\"$accion2\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp;  IMPRIMIR SOLICITUDES MEDICA</a></td>";
                                    $saldo=0;
                                    $saldo=SaldoCuentaPaciente($arr[$i][numerodecuenta]);
                                    if($saldo[saldo] > 0)
                                    {
                                            $this->salida .= "                                       <td width=\"50%\"><a href=\"$accion3\"><img src=\"".GetThemePath()."/images/modificar.png\" border='0'>&nbsp; TIENE UN SALDO DE $ $saldo[saldo]</a></td>";
                                    }
                                    elseif($saldo[saldo] < 0)
                                    {
                                            $this->salida .= "                                       <td width=\"50%\"><a href=\"$accion3\"><img src=\"".GetThemePath()."/images/modificar.png\" border='0'>&nbsp; TIENE UN SALDO DE $ $saldo[saldo]</a></td>";
                                    }
                                    else
                                    {
                                            $this->salida .= "                                       <td width=\"50%\"><a href=\"$accion3\"><img src=\"".GetThemePath()."/images/modificar.png\" border='0'>&nbsp; CERRAR CUENTA</a></td>";
                                    }
                                    $this->salida .= "                                   </tr>";
                            }
                            $this->salida .= "                           </table>";
                    }
                    //paciente de alta de urgencias
                    if($tipoPac==10)
                    {
                            $msg='';
                            if($arr[$i][historia_clinica_tipo_cierre_id]==9)
                            {  $msg=' (REMISION MEDICA) ';  }
                            $this->salida .= "                         <table width=\"100%\" border=\"0\" align=\"left\"  class=\"modulo_table_list_title\" cellspacing=\"3\" cellpadding=\"3\">";
                            $this->salida .= "                             <tr class=\"$estilo\" align=\"center\">";
                            $this->salida .= "                                       <td colspan=\"2\" class=label_mark>EL PACIENTE DADO DE ALTA DE OBSERVACION DE URGENCIAS $msg</td>";
                            $this->salida .= "                                   </tr>";
                            //-----------------------------------------------------------------
                            $this->salida .= "                             <tr class=\"$estilo\" align=\"left\">";
                            $accion=ModuloGetURL('app','Admisiones','user','UbicacionPacienteEstacion',array('tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre],'nombre_estacion'=>$arr[$i][descripcion],'cama'=>$arr[$i][cama],'pieza'=>$arr[$i][pieza],'ubicacion'=>$arr[$i][ubicacion]));
                            $this->salida .= "                                       <td width=\"50%\"><a href=\"$accion\"><img src=\"".GetThemePath()."/images/servicios.png\" border='0'>&nbsp;  UBICACION DEL PACIENTE</a></td>";
                            $accion1=ModuloGetURL('app','Admisiones','user','ModificarDatosPaciente',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'ingreso'=>$arr[$i][ingreso]));
                            $this->salida .= "                                       <td width=\"50%\"><a href=\"$accion1\"><img src=\"".GetThemePath()."/images/modificar.png\" border='0'>&nbsp; MODIFICAR DATOS</a></td>";
                            $this->salida .= "                                   </tr>";
                            //-----------------------------------------------------------------
                            $this->salida .= "                             <tr class=\"$estilo\" align=\"left\">";
                            $saldo=0;
                            $saldo=SaldoCuentaPaciente($arr[$i][numerodecuenta]);
                            if($saldo > 0)
                            {
                                    $this->salida .= "                                       <td width=\"50%\"><a href=\"$accion3\"><img src=\"".GetThemePath()."/images/plata.png\" border='0'>&nbsp; TIENE UN SALDO DE $ $saldo</a></td>";
                            }
                            elseif($saldo < 0)
                            {
                                    $this->salida .= "                                       <td width=\"50%\"><a href=\"$accion3\"><img src=\"".GetThemePath()."/images/plata.png\" border='0'>&nbsp; TIENE UN SALDO DE $ $saldo</a></td>";
                            }
                            else
                            {
                                    $accion2=ModuloGetURL('app','Admisiones','user','ImpresionSolicitudes',array('tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'evolucion'=>$arr[$i][evolucion_id],'ingreso'=>$arr[$i][ingreso]));
                                    $this->salida .= "                                       <td width=\"50%\"><a href=\"$accion2\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp;  IMPRIMIR SOLICITUDES MEDICA</a></td>";
                            }
                            $accion=ModuloGetURL('app','Admisiones','user','VerCuenta',array('cuenta'=>$arr[$i][numerodecuenta],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre],'plan'=>$arr[$i][plan_id],'ingreso'=>$arr[$i][ingreso],'rango'=>$arr[$i][rango],'estado'=>$arr[$i][estado],'fecha'=>$arr[$i][fecha_registro]));
                            $this->salida .= "                                       <td colspan=\"$col\"><a href=\"$accion\"><img src=\"".GetThemePath()."/images/pcopagos.png\" border='0'>&nbsp; VER CUENTA</a></td>";
                            $this->salida .= "                                   </tr>";
                            //-----------------------------------------------------------------
                            //remision
                            $this->salida .= "                             <tr class=\"$estilo\" align=\"left\">";
                            $cols=2;
                            if($arr[$i][historia_clinica_tipo_cierre_id]==9)
                            {
                                        $accion2=ModuloGetURL('app','Admisiones','user','RemisionMedica',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre],'ingreso'=>$arr[$i][ingreso]));
                                        $this->salida .= "                                       <td><a href=\"$accion2\"><img src=\"".GetThemePath()."/images/pparacar.png\" border='0'>&nbsp; REMISION</a></td>";
                                        $cols=1;
                            }
                            $mostrar=$reporte->GetJavaReport('app','SalidaPacientes','salida',array('tipo_id_paciente'=>$arr[$i][tipo_id_paciente],'paciente_id'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre]),array('rpt_name'=>'salida','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
                            $funcion=$reporte->GetJavaFunction();
                            $this->salida .=$mostrar;
                            $this->salida.="                 <td colspan=\"$cols\"><a href=\"javascript:$funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp; IMPRIMIR SALIDA PACIENTE</a></td>";
                            //$this->salida .= "                                         <td colspan=\"$cols\"><a href=\"javascript:$funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp; IMPRIMIR SALIDA PACIENTE</a></td>";
                            //$accion=ModuloGetURL('app','Admisiones','user','SalidaPaciente',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre],'ingreso'=>$arr[$i][ingreso]));
                            //$this->salida .= "                                         <td colspan=\"$cols\"><a href=\"$accion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp; IMPRIMIR SALIDA PACIENTE</a></td>";
                            $this->salida .= "                                   </tr>";
                            //-----------------------------------------------------------------
                            $this->salida .= "                             <tr class=\"$estilo\" align=\"left\">";
                            $col=2;
                            if(!empty($arr[$i][triage_id]))
                            {
                                    $nivel=$this->NivelTriage($arr[$i][triage_id]);
                                    if(!empty($nivel))
                                    {
                                            $accion2=ModuloGetURL('app','Admisiones','user','ConsultaTriage',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre]));
                                            $this->salida .= "                                       <td><a href=\"$accion2\"><img src=\"".GetThemePath()."/images/especialidad.png\" border='0'>&nbsp; CONSULTAR TRIAGE</a></td>";
                                            $col=1;
                                    }
                            }

                            $mostrar=$reporte->GetJavaReport_HC($arr[$i][ingreso],array());
                            $funcion=$reporte->GetJavaFunction();
                            $this->salida .=$mostrar;
                            //$this->salida.="<A href=\"javascript:$funcion\">X</A><br>";
                            $this->salida .= "                                       <td colspan=\"$col\"><a href=\"javascript:$funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp; IMPRIMIR HISTORIA CLINICA</a></td>";
                            $this->salida .= "                                   </tr>";
                            //-----------------------------------------------------------------
                            $this->salida .= "                           </table>";
                    }
                    $this->salida .= "                       </td>";
                    $this->salida .= "                     </tr>";
            }
            $this->salida .= "                    </table>";
            unset($reporte);
    }


    /**
    * Forma para mensajes.
    * @access private
    * @return boolean
    * @param string mensaje
    * @param string nombre de la ventana
    * @param string accion de la forma
    * @param string nombre del boton
    */
    function FormaMensaje($mensaje,$titulo,$accion,$boton)
    {
                $this->salida .= ThemeAbrirTabla($titulo);
                $this->salida .= "                <table width=\"60%\" align=\"center\">";
                $this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
                $this->salida .= "                     <tr><td colspan=\"2\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>";
                if($boton){
                   $this->salida .= "                      <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"$boton\"></td></tr>";
                }
       else{
                   $this->salida .= "                      <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Aceptar\"></td></tr>";
       }
                $this->salida .= "               </form>";
                $this->salida .= "               </table>";
                $this->salida .= ThemeCerrarTabla();
                return true;
    }

    /**
    *
    */
    function FormaElegirEstacion()
    {
                IncludeLib("funciones_admision");
                $this->salida .= ThemeAbrirTabla('ADMISIONES - ELEGIR ESTACION DE ENFERMERIA');
                $accion=ModuloGetURL('app','Admisiones','user','LlamarIngreso');
                $this->salida .= "                <br><table width=\"50%\" align=\"center\" border=0>";
                $this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
                $this->salida .= $this->SetStyle("MensajeError");
                $this->salida .= "                     <tr><td width=\"30%\" class=\"".$this->SetStyle("Estacion")."\">ELIJA LA ESTACION: </td>";
                $this->salida .= "                     <td colspan=\"2\"><select name=\"Estacion\" class=\"select\">";
                $Est=BuscarEstacionesPuntosAdmisiones($_SESSION['ADMISIONES']['PACIENTE']['punto_admision_id']);
                $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
                for($i=0; $i<sizeof($Est); $i++){
                        $this->salida .=" <option value=\"".$Est[$i][estacion_id].",".$Est[$i][departamento].",".$Est[$i][descripcion]."\">".$Est[$i][descripcion]."</option>";
                }
                $this->salida .= "              </select></td></tr>";
                $this->salida .= "                     <tr>";
                $this->salida .= "                     <tr>";
                $this->salida .= "                     <td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"></td>";
                $this->salida .= "               </form>";
                if(!empty($_SESSION['ADMISIONES']['RETORNO']))
                {
                        $_SESSION['ADMISIONES']['RETORNO']['CANCELAR']=true;
                        $Contenedor=$_SESSION['ADMISIONES']['RETORNO']['contenedor'];
                        $Modulo=$_SESSION['ADMISIONES']['RETORNO']['modulo'];
                        $Tipo=$_SESSION['ADMISIONES']['RETORNO']['tipo'];
                        $Metodo=$_SESSION['ADMISIONES']['RETORNO']['metodo'];
                        $arg=$_SESSION['ADMISIONES']['RETORNO']['argumentos'];
                        $accion=ModuloGetURL($Contenedor,$Modulo,$Tipo,$Metodo,$arg);
                }
                else
                {   $accion=ModuloGetURL('app','Admisiones','user','BuscarPaciente',array('TipoDocumento'=>$_SESSION['ADMISIONES']['BUSQUEDA']['tipo_id_paciente'],'Documento'=>$_SESSION['ADMISIONES']['BUSQUEDA']['paciente_id'],'Nombres'=>$_SESSION['ADMISIONES']['BUSQUEDA']['Nombres'],'prefijo'=>$_SESSION['ADMISIONES']['BUSQUEDA']['prefijo'],'historia'=>$_SESSION['ADMISIONES']['BUSQUEDA']['historia']));   }
                $this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
                $this->salida .= "                     <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"CANCELAR\"></td>";
                $this->salida .= "              </tr>";
                $this->salida .= "               </form>";
                $this->salida .= "               </table>";
                $this->salida .= ThemeCerrarTabla();
                return true;
    }

    /**
    *
    */
    function FormaModificarDatosPaciente()
    {
                $this->salida .= ThemeAbrirTabla('ADMISIONES - MODIFICAR DATOS PACIENTE');
                /*$contenedor=$_SESSION['ADMISIONES']['MODIFICAR']['RETORNO']['contenedor'];
                $modulo=$_SESSION['ADMISIONES']['MODIFICAR']['RETORNO']['modulo'];
                $tipo=$_SESSION['ADMISIONES']['MODIFICAR']['RETORNO']['tipo'];
                $metodo=$_SESSION['ADMISIONES']['MODIFICAR']['RETORNO']['metodo'];
                $argumentos=$_SESSION['ADMISIONES']['MODIFICAR']['RETORNO']['argumentos'];
                */
                $argumentos=array('tipoid'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'],'paciente'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id']);
                //mensaje
                $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
                $this->salida .= $this->SetStyle("MensajeError");
                $this->salida .= "  </table>";
                $this->FormaModificarDatos('app','Admisiones','user','FormaModificarDatosPaciente',$argumentos);
                if(!empty($_SESSION['ADMISIONES']['PACIENTE']['ingreso']))
                {  $this->FormaDatosIngreso();  }
                $this->salida .= "        <table border=\"0\" width=\"50%\" align=\"center\">";
                $this->salida .= "          <tr align=\"center\">";
                if(!empty($_SESSION['ADMISIONES']['PACIENTE']['ingreso']))
                {  $this->salida .= "            <td><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"MODIFICAR ADMISION\"></form></td>";  }
                $accionCambio=ModuloGetURL('app','Admisiones','user','CambioIdentificacion');
                $this->salida .= "            <td><form name=\"formai\" action=\"$accionCambio\" method=\"post\"><input class=\"input-submit\" type=\"submit\" name=\"Cambiar\" value=\"CAMBIAR IDENTIFICACION\"></form></td>";
                $accionUni=ModuloGetURL('app','Admisiones','user','UnificarHistorias');
                $this->salida .= "            <form name=\"formai\" action=\"$accionUni\" method=\"post\">";
                $this->salida .= "            <td><input class=\"input-submit\" type=\"submit\" name=\"Cambiar\" value=\"UNIFICACION HISTORIAS\"></form></td>";
                if(empty($_SESSION['ADMISIONES']['MODIFICAR']['RETORNO']))
                {  $accion=ModuloGetURL('app','Admisiones','user','BuscarPaciente',array('TipoDocumento'=>$_SESSION['ADMISIONES']['BUSQUEDA']['tipo_id_paciente'],'Documento'=>$_SESSION['ADMISIONES']['BUSQUEDA']['paciente_id'],'Nombres'=>$_SESSION['ADMISIONES']['BUSQUEDA']['Nombres'],'prefijo'=>$_SESSION['ADMISIONES']['BUSQUEDA']['prefijo'],'historia'=>$_SESSION['ADMISIONES']['BUSQUEDA']['historia']));  }
                else
                {
                        $contenedor=$_SESSION['ADMISIONES']['MODIFICAR']['RETORNO']['contenedor'];
                        $modulo=$_SESSION['ADMISIONES']['MODIFICAR']['RETORNO']['modulo'];
                        $tipo=$_SESSION['ADMISIONES']['MODIFICAR']['RETORNO']['tipo'];
                        $metodo=$_SESSION['ADMISIONES']['MODIFICAR']['RETORNO']['metodo'];
                        $argumentos=$_SESSION['ADMISIONES']['MODIFICAR']['RETORNO']['argumentos'];
                        $accion=ModuloGetURL($contenedor,$modulo,$tipo,$metodo,$argumentos);
                }
                //$accion=MoDuloGetURL('app','Admisiones','user','BuscarPaciente',array('TipoDocumento'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'],'Documento'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id']));
                $this->salida .= "            ";
                $this->salida .= "                     <td align=\"center\"><form name=\"formabuscar\" action=\"$accion\" method=\"post\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"VOLVER\"></form></td>";
                $this->salida .= "           </tr>";
                $this->salida .= "           </table>";
                $this->salida .= ThemeCerrarTabla();
                return true;
    }

    /**
    *
    */
    function FormaDatosIngreso()
    {
                IncludeLib("funciones_admision");
                $dat=$this->DatosIngreso($_SESSION['ADMISIONES']['PACIENTE']['ingreso']);
                $PlanId=$dat[plan_id];
                if(empty($_REQUEST['ViaIngreso']))
                {  $ViaIngreso=$dat[via_ingreso_id];  }
                else
                {  $ViaIngreso=$_REQUEST['ViaIngreso'];  }
                if(empty($_REQUEST['TipoAfiliado']))
                {  $TipoAfiliado=$dat[tipo_afiliado_id];  }
                else
                {  $TipoAfiliado=$_REQUEST['TipoAfiliado'];  }
                if(empty($_REQUEST['Nivel']))
                {  $Nivel=$dat[rango];  }
                else
                {  $Nivel=$_REQUEST['Nivel'];  }
                if(empty($_REQUEST['Semanas']))
                {  $sem=$dat[semanas_cotizadas];  }
                else
                {  $sem=0;  }
                if(empty($_REQUEST['Comentario']))
                {  $Comentarios=$dat[comentario];  }
                else
                {  $Comentarios=$_REQUEST['Comentario'];  }

                $sw=$this->BuscarSW($PlanId);

                $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\" class=\"normal_10\">";
                $accion=ModuloGetURL('app','Admisiones','user','ModificarDatosIngreso',array('sw'=>$sw));
                $this->salida .= "     <form name=\"formai\" action=\"$accion\" method=\"post\">";

                $this->salida .= "      <input type=\"hidden\" name=\"PolizaAnt\" value=\"$dat[poliza]\">";
              $this->salida .= "        <tr height=\"20\"><td class=\"".$this->SetStyle("Responsable")."\">RESPONSABLE: </td><td>";
                $this->salida .= "    ".$dat[plan_descripcion]."</td></tr>";
                $this->salida .= "            <tr>";
                $this->salida .= "                <td class=\"".$this->SetStyle("FechaIngreso")."\">FECHA INGRESO: </td>";
                if(empty($_REQUEST['FechaIngreso']))
                {  $fechaingreso=$this->FechaStamp($dat[fecha_ingreso]);  }
                else
                {  $fechaingreso=$_REQUEST['FechaIngreso'];  }
                $this->salida .= "            <td><input type=\"text\"  class=\"input-text\" name=\"FechaIngreso\" value=\"$fechaingreso\"></td>";
                $this->salida .= "            <td></td>";
                $this->salida .= "            </tr>";
                if($sw=='1'){
                        $this->salida .= "      <input type=\"hidden\" name=\"TipoAfiliado\" value=\"$dat[tipo_afiliado_id]\">";
                        $this->salida .= "      <input type=\"hidden\" name=\"Nivel\" value=\"$dat[rango]\">";
                        $this->salida .= "            <tr>";
                        $this->salida .= "                <td class=\"".$this->SetStyle("poliza")."\">POLIZA: </td>";
                        $this->salida .= "            <td><input type=\"text\" class=\"input-text\" name=\"Poliza\" value=\"$dat[poliza]\"></td>";
                        $this->salida .= "            <td></td>";
                        $this->salida .= "            </tr>";
                }
                /*$this->salida .= "                       <tr><td class=\"".$this->SetStyle("CausaExterna")."\">CAUSA EXTERNA: </td><td><select name=\"CausaExterna\" class=\"select\">";
                $causa_externa=$this->Causa_Externa();
                $this->BuscarIdCausaExterna($causa_externa,'False',$dat[causa_externa_id],$TipoForma);
                $this->salida .= "              </select></td></tr>";*/
                $this->salida .= "                     <tr><td class=\"".$this->SetStyle("ViaIngreso")."\">VIA INGRESO: </td><td><select name=\"ViaIngreso\" class=\"select\">";
                $via_ingreso=ViasIngreso();
                $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
                for($i=0; $i<sizeof($via_ingreso); $i++)
                {
                        if($via_ingreso[$i][via_ingreso_id]==$ViaIngreso){
                            $this->salida .=" <option value=\"".$via_ingreso[$i][via_ingreso_id]."\" selected>".$via_ingreso[$i][via_ingreso_nombre]."</option>";
                        }
                        else{
                            $this->salida .=" <option value=\"".$via_ingreso[$i][via_ingreso_id]."\">".$via_ingreso[$i][via_ingreso_nombre]."</option>";
                        }
                }
                $this->salida .= "              </select></td></tr>";
                if($sw!='1' && $sw!='2')
                {
                        $this->salida .= "      <input type=\"hidden\" name=\"Poliza\" value=\"$poliza\">";
                        $tipo_afiliado=TiposAfiliado($PlanId);
                        $this->salida .= "            <tr>";
                        if(sizeof($tipo_afiliado)>1)
                        {
                                $this->salida .= "                     <td class=\"".$this->SetStyle("TipoAfiliado")."\">TIPO AFILIADO: </td><td><select name=\"TipoAfiliado\" class=\"select\">";
                                $this->BuscarIdTipoAfiliado($tipo_afiliado,$TipoAfiliado);
                                $this->salida .= "              </select></td>";
                        }
                        else
                        {
                                $this->salida .= "                  <td class=\"".$this->SetStyle("TipoAfiliado")."\">TIPO AFILIADO: </td>";
                                $this->salida .= "            <td><input type=\"hidden\"  class=\"input-text\" name=\"TipoAfiliado\" value=\"".$tipo_afiliado[0][tipo_afiliado_id]."\">".$tipo_afiliado[0][tipo_afiliado_nombre]."</td>";
                                $this->salida .= "            <td></td>";
                        }
                        $niveles=Niveles($PlanId);
                        if(sizeof($niveles)>1)
                        {
                            $this->salida .= "                     <tr><td class=\"".$this->SetStyle("Nivel")."\">RANGO: </td><td><select name=\"Nivel\" class=\"select\">";
                            $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
                            for($i=0; $i<sizeof($niveles); $i++)
                            {
                                    if($niveles[$i][rango]==$Nivel){
                                        $this->salida .=" <option value=\"".$niveles[$i][rango]."\" selected>".$niveles[$i][rango]."</option>";
                                    }
                                    else{
                                            $this->salida .=" <option value=\"".$niveles[$i][rango]."\">".$niveles[$i][rango]."</option>";
                                    }
                            }
                        }
                        else
                        {
                                $this->salida .= "                   <td class=\"".$this->SetStyle("Nivel")."\">RANGO: </td>";
                                $this->salida .= "            <td><input type=\"hidden\"  class=\"input-text\" name=\"Nivel\" value=\"".$niveles[0][rango]."\">".$niveles[0][rango]."</td>";
                                $this->salida .= "            <td></td>";
                        }
                        $this->salida .= "            </tr>";
                }
                $this->salida .= "            <tr>";
                $this->salida .= "                   <td class=\"".$this->SetStyle("Semanas")."\">SEMANAS: </td>";
                $this->salida .= "            <td><input type=\"text\"  class=\"input-text\" name=\"Semanas\" value=\"".$sem."\" size=6></td>";
                $this->salida .= "            <td></td>";
                $this->salida .= "            </tr>";
                $datos=BuscarProtocolo($var[plan_id]);
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
                                $this->salida .= "                <tr><td class=\"label\" width=\"24%\">PROTOCOLO: </td><td><a href=\"$accion\">$Protocolo</a></td>";
                                $this->salida .= "            <td></td></tr>";
                        }
                }
                $this->salida .= "    </table>";
                $this->salida .= "    <br><table border=\"0\" width=\"60%\" align=\"center\">";
                $this->salida .= "      <tr><td><fieldset><legend class=\"field\">COMENTARIOS</legend>";
                $this->salida .= "        <table border=\"0\" width=\"100%\" align=\"center\">";
                $this->salida .= "          <tr  align=\"center\"><td width=\"30%\"><textarea name=\"Comentario\" cols=\"65\" rows=\"3\" class=\"textarea\">$Comentarios</textarea></td></tr>";
                $this->salida .= "           </table>";
                $this->salida .= "        </fieldset></td></tr></table><br>";
    }

    /**
    * Crear el combo de tipos de afiliados
    * @access private
    * @return string
    * @param array arreglo con los tipos de afiliados
    * @param int tipo de afiliado
    */
    function BuscarIdTipoAfiliado($tipo_afiliado,$TipoAfiliado='')
    {
                $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
                for($i=0; $i<sizeof($tipo_afiliado); $i++)
                {
                    if($tipo_afiliado[$i][tipo_afiliado_id]==$TipoAfiliado){
                     $this->salida .=" <option value=\"".$tipo_afiliado[$i][tipo_afiliado_id]."\" selected>".$tipo_afiliado[$i][tipo_afiliado_nombre]."</option>";
                    }
                    else{
                     $this->salida .=" <option value=\"".$tipo_afiliado[$i][tipo_afiliado_id]."\">".$tipo_afiliado[$i][tipo_afiliado_nombre]."</option>";
                    }
                }
    }


    /**
    *
    */
    function FormaModificarDatos($contenedor,$modulo,$tipo,$metodo,$argumentos)
    {
            $Paciente=$this->ReturnModuloExterno('app','Pacientes','user');
            $_SESSION['PACIENTES']['RETORNO']['contenedor']=$contenedor;
            $_SESSION['PACIENTES']['RETORNO']['modulo']=$modulo;
            $_SESSION['PACIENTES']['RETORNO']['tipo']=$tipo;
            $_SESSION['PACIENTES']['RETORNO']['metodo']=$metodo;
            $_SESSION['PACIENTES']['RETORNO']['argumentos']=$argumentos;

            if(!is_object($Paciente))
            {
                    $this->error = "La clase Pacientes no se pudo instanciar";
                    $this->mensajeDeError = "";
                    return false;
            }
            if(!$Paciente->LlamarFormaDatosPacienteCreado($_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'],$_SESSION['ADMISIONES']['PACIENTE']['paciente_id'],$_SESSION['ADMISIONES']['PACIENTE']['plan_id']))
            {
                    $this->error = $Paciente->error ;
                    $this->mensajeDeError = $Paciente->mensajeDeError;
                    unset($Paciente);
                    return false;
            }
            else
            {
                    if(!$Paciente->TipoRetorno)
                    {
                                $this->salida .= $Paciente->GetSalida();
                                unset($Paciente);
                    }
            }

    }


    /**
    *
    */
    /*function FormaIngreso()
    {
            IncludeLib("funciones_admision");
            $this->salida .= ThemeAbrirTabla('ADMISIONES - DATOS DEL INGRESO DEL PACIENTE');

            $this->FormaModificarDatos('app','Admisiones','user','LlamarIngreso',array());

            $sw=$this->BuscarSW($_SESSION['ADMISIONES']['PACIENTE']['plan_id']);
            if($sw==1 && $_SESSION['ADMISIONES']['SWSOAT']==0)
            {
                    $this->salida .= "      <table border=\"1\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
                    $this->salida .= "            <tr class=\"modulo_table_list_title\">";
                    $this->salida .= "            <td>DEBE REMITIR EL PACIENTE AL MODULO DE SOAT.</td>";
                    $this->salida .= "            </tr>";
                    $accionA=ModuloGetURL('app','Admisiones','user','FormaBuscar');
                    $this->salida .= "            <tr class=\"modulo_table_list_title\">";
                    $this->salida .= "         <form name=\"formaingreso\" action=\"$accionA\" method=\"post\">";
                    $this->salida .= "                  <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"></td>";
                    $this->salida .= "  </form>";
                    $this->salida .= "            </tr>";
                    $this->salida .= "    </table><br>";
            }
            if($sw==1 && $_SESSION['ADMISIONES']['SWSOAT']==1)
            {
                    $this->salida .= "      <table border=\"1\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
                    $accionA=ModuloGetURL('app','Admisiones','user','LlamarModuloSoat');
                    $this->salida .= "            <tr class=\"modulo_table_list_title\">";
                    $this->salida .= "         <form name=\"formaingreso\" action=\"$accionA\" method=\"post\">";
                    $this->salida .= "                  <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"SELECCIONAR EVENTO SOAT\"></td>";
                    $this->salida .= "  </form>";
                    $this->salida .= "            </tr>";
                    $this->salida .= "    </table><br>";
            }
            if($sw!=1)
            {
                    $accion=ModuloGetURL('app','Admisiones','user','InsertarDatosIngreso');
                    $this->salida .= "         <form name=\"formaingreso\" action=\"$accion\" method=\"post\">";
                    $this->salida.= "            <input type=\"hidden\" name=\"TipoId\" value=\"$TipoId\">";
                    $this->salida.= "            <input type=\"hidden\" name=\"TipoForma\" value=\"$TipoForma\">";
                    $this->salida.= "            <input type=\"hidden\" name=\"Nivel\" value=\"$Nivel\">";
                    $this->salida.= "            <input type=\"hidden\" name=\"PacienteId\" value=\"$PacienteId\">";
                    $this->salida.= "            <input type=\"hidden\" name=\"Responsable\" value=\"$Responsable\">";
                    $this->salida .= "      <table border=\"0\" width=\"60%\" align=\"center\" class=\"normal_10\">";
                    $this->salida .= $this->SetStyle("MensajeError");
                    $this->salida .= "            <tr>";
                    $this->salida .= "                <td class=\"".$this->SetStyle("fechaIngreso")."\">FECHA INGRESO: </td>";
                    $fechaSistema=date("d/m/Y");
                    $this->salida .= "            <td><input type=\"text\"  class=\"input-text\" name=\"fechaIngreso\" value=\"$fechaSistema\"></td>";
                    $this->salida .= "            <td></td>";
                    $this->salida .= "            </tr>";
                    $this->salida .= "                     <tr><td class=\"".$this->SetStyle("ViaIngreso")."\">VIA INGRESO: </td><td><select name=\"ViaIngreso\" class=\"select\">";
                    $via_ingreso=ViasIngreso();
                    $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
                    for($i=0; $i<sizeof($via_ingreso); $i++)
                    {
                            if($value==$ViaIngreso){
                                $this->salida .=" <option value=\"".$via_ingreso[$i][via_ingreso_id]."\" selected>".$via_ingreso[$i][via_ingreso_nombre]."</option>";
                            }
                            else{
                                $this->salida .=" <option value=\"".$via_ingreso[$i][via_ingreso_id]."\">".$via_ingreso[$i][via_ingreso_nombre]."</option>";
                            }
                    }
                    $this->salida .= "              </select></td></tr>";
                    if($sw!=1 && $sw!=2)
                    {
                            $tipo_afiliado=TiposAfiliado($_SESSION['ADMISIONES']['PACIENTE']['plan_id']);
                            $this->salida .= "            <tr>";
                            if($TipoAfiliado==-1 || empty($TipoAfiliado))
                            { $TipoAfiliado=$_SESSION['ADMISIONES']['PACIENTE']['tipo_afiliado_id']; }
                            if(!empty($_SESSION['ADMISIONES']['PACIENTE']['tipo_afiliado_id']))
                            {
                                            $TipoAfiliado=$this->NombreTipoAfiliado($_SESSION['ADMISIONES']['PACIENTE']['tipo_afiliado_id']);
                                            $this->salida .= "                  <td class=\"".$this->SetStyle("TipoAfiliado")."\">TIPO AFILIADO: </td>";
                                            $this->salida .= "            <td><input type=\"hidden\"  class=\"input-text\" name=\"TipoAfiliado\" value=\"".$_SESSION['ADMISIONES']['PACIENTE']['tipo_afiliado_id']."\">".$TipoAfiliado."</td>";
                                            $this->salida .= "            <td></td>";
                            }
                            else
                            {
                                    if(sizeof($tipo_afiliado)>1)
                                    {
                                            $this->salida .= "                     <td class=\"".$this->SetStyle("TipoAfiliado")."\">TIPO AFILIADO: </td><td><select name=\"TipoAfiliado\" class=\"select\">";
                                            $this->BuscarIdTipoAfiliado($tipo_afiliado,$TipoAfiliado);
                                            $this->salida .= "              </select></td>";
                                    }
                                    else
                                    {
                                            $this->salida .= "                  <td class=\"".$this->SetStyle("TipoAfiliado")."\">TIPO AFILIADO: </td>";
                                            $this->salida .= "            <td><input type=\"hidden\"  class=\"input-text\" name=\"TipoAfiliado\" value=\"".$tipo_afiliado[0][tipo_afiliado_id]."\">".$tipo_afiliado[0][tipo_afiliado_nombre]."</td>";
                                            $this->salida .= "            <td></td>";
                                    }
                            }
                            $this->salida .= "            </tr>";
                            $this->salida .= "            <tr>";
                            if($Nivel==-1 || empty($Nivel))
                            {  $Nivel=$_SESSION['ADMISIONES']['PACIENTE']['rango'];  }
                            if(!empty($_SESSION['ADMISIONES']['PACIENTE']['rango']))
                            {
                                        $this->salida .= "                   <td class=\"".$this->SetStyle("Nivel")."\">RANGO: </td>";
                                        $this->salida .= "            <td><input type=\"hidden\"  class=\"input-text\" name=\"Nivel\" value=\"".$_SESSION['ADMISIONES']['PACIENTE']['rango']."\">&nbsp;&nbsp;".$_SESSION['ADMISIONES']['PACIENTE']['rango']."</td>";
                                        $this->salida .= "            <td></td>";
                            }
                            else
                            {
                                    $niveles=Niveles();
                                    if(sizeof($niveles)>1)
                                    {
                                        $this->salida .= "                     <tr><td class=\"".$this->SetStyle("Nivel")."\">RANGO: </td><td><select name=\"Nivel\" class=\"select\">";
                                        $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
                                        for($i=0; $i<sizeof($niveles); $i++)
                                        {
                                                if($niveles[$i][rango]==$Nivel){
                                                    $this->salida .=" <option value=\"".$niveles[$i][rango]."\" selected>".$niveles[$i][rango]."</option>";
                                                }
                                                else{
                                                        $this->salida .=" <option value=\"".$niveles[$i][rango]."\">".$niveles[$i][rango]."</option>";
                                                }
                                        }
                                    }
                                    else
                                    {
                                            $this->salida .= "                   <td class=\"".$this->SetStyle("Nivel")."\">RANGO: </td>";
                                            $this->salida .= "            <td><input type=\"hidden\"  class=\"input-text\" name=\"Nivel\" value=\"".$niveles[0][rango]."\">&nbsp;&nbsp;".$niveles[0][rango]."</td>";
                                            $this->salida .= "            <td></td>";
                                    }
                            }
                            $this->salida .= "            </tr>";
                            $this->salida .= "            <tr>";
                            if(!empty($_SESSION['ADMISIONES']['PACIENTE']['semanas']))
                            {
                                    $this->salida .= "                   <td class=\"".$this->SetStyle("Semanas")."\">SEMANAS: </td>";
                                    $this->salida .= "            <td><input type=\"hidden\"  class=\"input-text\" name=\"Semanas\" value=\"".$_SESSION['TRIAGE']['PACIENTE']['semanas']."\">&nbsp;&nbsp;".$_SESSION['TRIAGE']['PACIENTE']['semanas']."</td>";
                                    $this->salida .= "            <td></td>";
                            }
                            else
                            {
                                    $this->salida .= "                   <td class=\"".$this->SetStyle("Semanas")."\">SEMANAS: </td>";
                                    $this->salida .= "            <td><input type=\"text\"  class=\"input-text\" name=\"Semanas\"></td>";
                                    $this->salida .= "            <td></td>";
                            }
                            $this->salida .= "            </tr>";
                    }
                    $datos=BuscarProtocolo($_SESSION['ADMISIONES']['PACIENTE']['plan_id']);
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
                                    $this->salida .= "                <tr><td class=\"label\" width=\"24%\">PROTOCOLO: </td><td><a href=\"$accion\">$Protocolo</a></td>";
                                    $this->salida .= "            <td></td></tr>";
                            }
                    }
                    if(empty($Comentarios))
                    {  $Comentarios=$_SESSION['TRIAGE']['PACIENTE']['observacion_ingreso'];  }
                    $this->salida .= "          <tr>";
                    $this->salida .= "  <td class=\"".$this->SetStyle("Nivel")."\">COMENTARIOS: </td>";
                    $this->salida .= "          <td width=\"50%\" colspan=\"2\"><textarea name=\"Comentarios\" cols=\"65\" rows=\"3\" class=\"textarea\">$Comentarios</textarea></td></tr>";
                    $this->salida .= "                     </tr>";
                    $this->salida .= "           </table>";
                    $this->salida .= "      <table border=\"0\" width=\"40%\" align=\"center\" class=\"normal_10\">";
                    $this->salida .= "                <tr><td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"><br></td>";
                    $this->salida .= "  </form>";
                    //$actionCancelar=ModuloGetURL('app','Admisiones','user','BuscarPaciente',array('TipoDocumento'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'],'Documento'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id']));
                    $actionCancelar=ModuloGetURL('app','Admisiones','user','BuscarPaciente',array('TipoDocumento'=>$_SESSION['ADMISIONES']['BUSQUEDA']['tipo_id_paciente'],'Documento'=>$_SESSION['ADMISIONES']['BUSQUEDA']['paciente_id'],'Nombres'=>$_SESSION['ADMISIONES']['BUSQUEDA']['Nombres'],'prefijo'=>$_SESSION['ADMISIONES']['BUSQUEDA']['prefijo'],'historia'=>$_SESSION['ADMISIONES']['BUSQUEDA']['historia']));
                    $this->salida .= "  <form name=\"formacancelar\" action=\"$actionCancelar\" method=\"post\">";
                    $this->salida .= "                     <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"CANCELAR\"><br></td>";
                    $this->salida .= "  </form>";
                    $this->salida .= "                     </tr>";
                    $this->salida .= "           </table>";
            }
            $this->salida .= ThemeCerrarTabla();
            return true;
        }*/


    /**
    *
    */
    function FormaCambiarPtoTriage()
    {
                $action=ModuloGetURL('app','Admisiones','user','ActualizarPtoTriage');
        $this->salida .= ThemeAbrirTabla('ADMISIONES - ELEGIR PUNTO TRIAGE');
                $this->salida .= "                <table width=\"50%\" align=\"center\" border=\"0\">";
                $this->salida .= "             <form name=\"formabuscar\" action=\"$action\" method=\"post\">";
                $this->salida .= "                     <tr><td class=\"label\" colspan=\"2\" align=\"center\">Elija el Punto de Triage al Que va a Remitir el Paciente.</td>";
                $this->salida .= $this->SetStyle("MensajeError");
                $this->salida .= "                     <tr><td class=\"".$this->SetStyle("Punto")."\">PUNTO TRIAGE: </td><td><select name=\"Punto\" class=\"select\">";
                $var=$this->TodosPuntosTriage();
                $this->salida .= "                   <option value=\"-1\">------SELECCIONE------</option>";
                for($i=0; $i<sizeof($var); $i++)
                {
                        $this->salida .= "                   <option value=\"".$var[$i][punto_triage_id]."\">".$var[$i][descripcion]."</option>";
                }
                $this->salida .= "              </select></td></tr>";
                $this->salida .= "               </table>";
                $this->salida .= " <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"45%\" align=\"center\"  >";
                $this->salida .= "    <tr align=\"center\">";
                $this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"ACEPTAR\"></td>";
                $this->salida .= "    </form>";
                //$accion=ModuloGetURL('app','Admisiones','user','BuscarPaciente',array('TipoDocumento'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'],'Documento'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id']));
                $accion=ModuloGetURL('app','Admisiones','user','BuscarPaciente',array('TipoDocumento'=>$_SESSION['ADMISIONES']['BUSQUEDA']['tipo_id_paciente'],'Documento'=>$_SESSION['ADMISIONES']['BUSQUEDA']['paciente_id'],'Nombres'=>$_SESSION['ADMISIONES']['BUSQUEDA']['Nombres'],'prefijo'=>$_SESSION['ADMISIONES']['BUSQUEDA']['prefijo'],'historia'=>$_SESSION['ADMISIONES']['BUSQUEDA']['historia']));
                $this->salida .= "    <form name=\"formaguardar\" action=\"$accion\" method=\"post\">";
                $this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"CANCELAR\"></td>";
                $this->salida .= "    </form>";
                $this->salida .= "    </table>";
                $this->salida .= ThemeCerrarTabla();
                return true;
    }

        /**
        *
        */
        /*function FormaGarantesAdmon()
        {
                IncludeLib("funciones_admision");
                $this->salida .= ThemeAbrirTabla('ADMISIONES - DATOS GARANTES');
                $accion=ModuloGetURL('app','Admisiones','user','InsertarGarantesAdmon');
                $this->salida .= "<br><table width=\"70%\" cellspacing=\"0\" border=\"0\" cellpadding=\"0\" align=\"center\" >";
                $this->salida .= $this->SetStyle("MensajeError");
                $this->salida .= "  <form name=\"formapedir\" action=\"$accion\" method=\"post\">";
                $this->salida .= "      <tr><td class=\"".$this->SetStyle("TipoId")."\">TIPO DOCUMENTO: </td><td><select name=\"TipoId\" class=\"select\">";
                $var=TipoIdTerceros();
                $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
                for($i=0; $i<sizeof($var); $i++)
                {
                        $this->salida .= "                   <option value=\"".$var[$i][tipo_id_tercero]."\">".$var[$i][descripcion]."</option>";
                }
                $this->salida .= "              </select></td></tr>";
                $this->salida .= "      <tr height=\"20\">";
                $this->salida .= "      <td class=\"".$this->SetStyle("GaranteId")."\">DOCUMENTO: </td>";
                $this->salida .= "      <td><input type=\"text\" name=\"GaranteId\" maxlength=\"32\" class=\"input-text\" value=\"".$_REQUEST['GaranteId']."\"></td>";
                $this->salida .= "          <td>  </td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      <tr height=\"20\">";
                $this->salida .= "      <td class=\"".$this->SetStyle("PrimerNombre")."\">PRIMER NOMBRE: </td>";
                $this->salida .= "      <td><input type=\"text\" maxlength=\"20\" name=\"PrimerNombre\"  value=\"".$_REQUEST['PrimerNombre']."\" class=\"input-text\"></td>";
                $this->salida .= "      <td></td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      <tr height=\"20\">";
                $this->salida .= "      <td class=\"label\">SEGUNDO NOMBRE: </td>";
                $this->salida .= "      <td><input type=\"text\" maxlength=\"20\" name=\"SegundoNombre\" value=\"".$_REQUEST['SegundoNombre']."\" class=\"input-text\"></td>";
                $this->salida .= "          <td></td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      <tr height=\"20\">";
                $this->salida .= "      <td class=\"".$this->SetStyle("PrimerApellido")."\">PRIMER APELLIDO: </td>";
                $this->salida .= "      <td><input type=\"text\" maxlength=\"30\" name=\"PrimerApellido\" value=\"".$_REQUEST['PrimerApellido']."\" class=\"input-text\"></td>";
                $this->salida .= "      <td></td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      <tr height=\"20\">";
                $this->salida .= "      <td class=\"label\">SEGUNDO APELLIDO: </td>";
                $this->salida .= "      <td><input type=\"text\" maxlength=\"30\" name=\"SegundoApellido\" value=\"".$_REQUEST['SegundoApellido']."\" class=\"input-text\"></td>";
                $this->salida .= "      <td></td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      <tr>";
                $this->salida .= "      <tr height=\"20\">";
                $this->salida .= "      <td class=\"".$this->SetStyle("Direccion")."\">DIRECCION: </td>";
                $this->salida .= "      <td><input type=\"text\" maxlength=\"60\" name=\"Direccion\" value=\"".$_REQUEST['Direccion']."\" class=\"input-text\"></td>";
                $this->salida .= "      <td></td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      <tr height=\"20\">";
                $this->salida .= "      <td class=\"".$this->SetStyle("Telefono")."\">TELEFONOS: </td>";
                $this->salida .= "      <td ><input type=\"text\" maxlength=\"30\" name=\"Telefono\" value=\"".$_REQUEST['Telefono']."\" class=\"input-text\"></td>";
                $this->salida .= "      <td></td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      <tr>";
                $this->salida .= "      <td  colspan=\"3\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"ACEPTAR\"><br></form></td>";
                $this->salida .= "      </tr>";
                $this->salida .= "  </table>";
                //$this->salida .= "          </fieldset></td></tr></table><br>";
                $this->salida .= ThemeCerrarTabla();
                return true;
        }*/

    /**
    *
    */
    function FormaTriage()
    {
                IncludeLib("funciones_admision");
                $this->salida .= ThemeAbrirTabla('HOJA TRIAGE');
                $dat=DatosTriage($_SESSION['ADMISIONES']['PACIENTE']['triage_id']);
                $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
                $this->salida .= $this->SetStyle("MensajeError");
                $this->salida .= "            </table><br>";
                $this->salida .= "                <table width=\"70%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
                $this->salida .= "                     <tr>";
                $this->salida .= "                        <td class=\"modulo_list_claro\" colspan=\"4\" align=\"center\"><b>DEPARTAMENTO DE SERVICIOS DE ".$dat['descripcion']."</b></td>";
                $this->salida .= "                     </tr>";
                $this->salida .= "                     <tr>";
                $this->salida .= "                        <td align=\"center\" class=\"modulo_table_list_title\">INSTITUCION QUE REMITE: </td>";
                $this->salida .= "                        <td class=\"modulo_list_claro\" colspan=\"3\">".$this->NombreEmpresa($dat['empresa_id'])."</td>";
                $this->salida .= "                     </tr>";
                $this->salida .= "                     <tr>";
                $this->salida .= "                        <td align=\"center\" class=\"modulo_table_list_title\">PROFESIONAL: </td>";
                $this->salida .= "                        <td class=\"modulo_list_claro\" colspan=\"3\">".$dat['nombre']."</td>";
                $this->salida .= "                     </tr>";              
                $this->salida .= "                     <tr>";
                $this->salida .= "                        <td align=\"center\" width=\"25%\" class=\"modulo_table_list_title\">IDENTIFICACION: </td>";
                $this->salida .= "                        <td class=\"modulo_list_claro\" width=\"17%\">".$dat['tipo_id_paciente']." ".$dat['paciente_id']."</td>";
                $this->salida .= "                        <td align=\"center\" class=\"modulo_table_list_title\" width=\"10%\">PACIENTE: </td>";
                $this->salida .= "                        <td class=\"modulo_list_claro\" width=\"30%\">".$_SESSION['ADMISIONES']['PACIENTE']['nombre']."</td>";
                $this->salida .= "                     </tr>";
                $this->salida .= "                     <tr>";
                $this->salida .= "                        <td align=\"center\" class=\"modulo_table_list_title\">CLASIFICACION: </td>";
                //$estilo=ColorTriage($dat[nivel_triage_id]);
				$ColorTriage = $this->BuscarColorTriage($dat[nivel_triage_id]);
                $this->salida .= "                        <td bgcolor=\"".$ColorTriage['color_oscuro']."\">NIVEL ".$dat[nivel_triage_id]."</td>";
                $this->salida .= "                        <td align=\"center\" class=\"modulo_table_list_title\">FECHA: </td>";
                $this->salida .= "                        <td class=\"modulo_list_claro\">".FechaStamp($dat[hora_llegada])." ".HoraStamp($dat[hora_llegada])."</td>";
                $this->salida .= "                     </tr>";
                $this->salida .= "                     <tr >";
                $this->salida .= "                        <td align=\"center\" class=\"modulo_table_list_title\">CAUSAS PROBABLES: </td>";
                $this->salida .= "                        <td class=\"modulo_list_claro\" colspan=\"3\">";
                $causas=BuscarCausas($_SESSION['ADMISIONES']['PACIENTE']['triage_id']);
                if(!empty($causas))
                {
                    $this->salida .= "                   <table width=\"100%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
                    $this->salida .= "                       <tr class=\"modulo_table_list_title\">";
                    $this->salida .= "                   <td>NIVEL</td>";
                    $this->salida .= "                   <td>CAUSA PROBABLE</td>";
                    $this->salida .= "                       </tr>";
                    for($i=0; $i<sizeof($causas);)
                    {
                            $this->salida .= "                       <tr class=\"modulo_list_oscuro\">";
                            //$estilo=ColorTriage($causas[$i][nivel_triage_id]);
                            $ColorTriage = $this->BuscarColorTriage($dat[nivel_triage_id]);
							$this->salida .= "                   <td bgcolor=\"".$ColorTriage['color_oscuro']."\" width=\"15%\" align=\"center\">NIVEL ".$causas[$i][nivel_triage_id]."</td>";
                            $this->salida .= "                   <td width=\"75%\">";
                            $this->salida .= "                               <table width=\"100%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
                            $d=$i;
                            while($causas[$i][nivel_triage_id]==$causas[$d][nivel_triage_id])
                            {
                                    $estiloClaro=ColorTriageClaro($causas[$i][nivel_triage_id]);
                                    $this->salida .= "                                   <tr class=\"modulo_list_claro\">";
                                    $this->salida .= "                               <td class=\"$estiloClaro\">".$causas[$d][descripcion]."</td>";
                                    $this->salida .= "                                   </tr>";
                                    $d++;
                            }
                            $i=$d;
                            $this->salida .= "                             </table>";
                            $this->salida .= "                   </td>";
                            $this->salida .= "                       </tr>";
                    }
                    $this->salida .= "                       </table>";                 
                }
                $this->salida .= "              </td>";
                $this->salida .= "                     </tr>";
                $this->salida .= "                     <tr >";
                $this->salida .= "                        <td align=\"center\" class=\"modulo_table_list_title\">MOTIVO CONSULTA: </td>";
                if(!empty($_REQUEST['MotivoConsulta']))
                {  $dat[motivo_consulta]=$_REQUEST['MotivoConsulta'];  }
                $this->salida .= "                        <td class=\"modulo_list_claro\" colspan=\"3\"><textarea name=\"MotivoConsulta\" cols=\"90\" rows=\"2\" class=\"textarea\" readonly>".$dat[motivo_consulta]."</textarea></td>";
                $this->salida .= "                     </tr>";
                $this->salida .= "                     <tr >";
                $this->salida .= "                        <td align=\"center\" class=\"modulo_table_list_title\">SIGNOS VITALES: </td>";
                $this->salida .= "                        <td class=\"modulo_list_claro\" colspan=\"3\">";
                $sig=BuscarSignosVitalesTriage($_SESSION['ADMISIONES']['PACIENTE']['triage_id']);
                $glas=$sig[respuesta_motora_id] + $sig[respuesta_verbal_id]+ $sig[apertura_ocular_id];
                if(empty($glas)){   $glas='--';  }
                $this->salida .= "                   <table width=\"70%\" border=\"0\" align=\"center\" class=\"modulo_table_list_title\" cellspacing=\"3\" cellpadding=\"3\">";
                $this->salida .= "                       <tr align=\"center\" class=\"modulo_table_list_title\">";
                $this->salida .= "                       <td>F.C.</td>";
                $this->salida .= "                       <td>F.R.</td>";
                $this->salida .= "                       <td>PESO(Kg)</td>";
                $this->salida .= "                       <td>T.A.</td>";
                $this->salida .= "                       <td>TEMP.</td>";
                $this->salida .= "                       <td>EVA.</td>";
                $this->salida .= "                       <td>GLASGOW</td>";
                $this->salida .= "                       </tr>";
                $this->salida .= "                       <tr>";
                $this->salida .= "                         <td class=\"modulo_list_claro\" width=\"10%\">".$sig[signos_vitales_fc]."</td>";
                $this->salida .= "                         <td class=\"modulo_list_claro\" width=\"10%\">".$sig[signos_vitales_fr]."</td>";
                $this->salida .= "                         <td class=\"modulo_list_claro\" width=\"15%\">".$sig[signos_vitales_peso]."</td>";
                $this->salida .= "                         <td class=\"modulo_list_claro\" width=\"15%\">".$sig[signos_vitales_taalta]." / ".$sig[signos_vitales_tabaja]."</td>";
                $this->salida .= "                         <td class=\"modulo_list_claro\" width=\"10%\">".$sig[signos_vitales_temperatura]."</td>";
                $this->salida .= "                         <td class=\"modulo_list_claro\" width=\"10%\">".$sig[evaluacion_dolor]."</td>";
                $this->salida .= "                         <td class=\"modulo_list_claro\" width=\"10%\">".$glas."</td>";
                $this->salida .= "                       </tr>";
                $this->salida .= "                       </table>";
                $this->salida .= "                                  </td>";
                $this->salida .= "                     </tr>";
                $this->salida .= "                     <tr >";
                $this->salida .= "                        <td align=\"center\" class=\"modulo_table_list_title\">OBSERVACION: </td>";
                if(!empty($_REQUEST['observacion']))
                {  $dat[observacion_medico]=$_REQUEST['observacion'];  }
                $this->salida .= "                        <td class=\"modulo_list_claro\" colspan=\"3\"><textarea name=\"observacion\" cols=\"90\" rows=\"2\" class=\"textarea\" readonly>".$dat[observacion_medico]."</textarea></td>";
                $this->salida .= "                     </tr>";
                $this->salida .= "                     <tr >";
                $this->salida .= "                        <td align=\"center\" class=\"modulo_table_list_title\">DIAGNOSTICO: </td>";
                $this->salida .= "                        <td class=\"modulo_list_oscuro\" colspan=\"3\">";
                $diaTriage=BuscarDiagnosticoTriage($_SESSION['REMISIONES']['DATOS']['triage_id']);
                if(!empty($diaTriage))
                {
                        $this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"100%\">";
                        $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                        $this->salida.="  <td width=\"9%\">CODIGO</td>";
                        $this->salida.="  <td width=\"88%\">DESCRIPCION</td>";
                        $this->salida.="  <td width=\"3%\"></td>";
                        $this->salida.="</tr>";
                        for($k=0; $k<sizeof($diaTriage); $k++)
                        {
                                $this->salida.="<tr class=\"modulo_list_claro\">";
                                $this->salida.="  <td align=\"center\">".$diaTriage[$k][diagnostico_id]."</td>";
                                $this->salida.="  <td>".$diaTriage[$k][diagnostico_nombre]."</td>";
                                $this->salida.="  <input type = hidden name=codigodi".$diaTriage[$k][diagnostico_id]." value = ".$diaTriage[$k][descripcion]."></td>";
                                $accion2=ModuloGetURL('app','Remisiones','user','EliminarDiagnostico',array('codigoED'=>$diaTriage[$k][diagnostico_id],'dat'=>$_REQUEST));
                                $this->salida.="  <td></td>";
                                $this->salida.="</tr>";
                        }
                        $this->salida.="</table><br>";
                }
                $this->salida .= "                     </td>";
                $this->salida .= "                     </tr>";
                $this->salida .= "               </table><br>";
                $this->salida .= "<table width=\"40%\" border=\"0\" align=\"center\">";
                $this->salida .= "<tr>";

                $reporte= new GetReports();
                $mostrar=$reporte->GetJavaReport('app','Admisiones','triage',array('triage_id'=>$_SESSION['ADMISIONES']['PACIENTE']['triage_id'],'empresa'=>$_SESSION['ADMISIONES']['NOMEMPRESA'],'nombre'=>$_SESSION['ADMISIONES']['PACIENTE']['nombre']),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
                $funcion=$reporte->GetJavaFunction();
                $this->salida .=$mostrar;
                $this->salida .= "                     <td align=\"center\"><input class=\"input-submit\" type=\"button\" name=\"Imprimir\" value=\"IMPRIMIR\" onclick=\"javascript:$funcion\"></form></td>";
                unset($reporte);

                if(empty($_SESSION['ADMISIONES']['TRIAGE']['RETORNO']))
                {  $accion=ModuloGetURL('app','Admisiones','user','BuscarPaciente',array('TipoDocumento'=>$_SESSION['ADMISIONES']['BUSQUEDA']['tipo_id_paciente'],'Documento'=>$_SESSION['ADMISIONES']['BUSQUEDA']['paciente_id'],'Nombres'=>$_SESSION['ADMISIONES']['BUSQUEDA']['Nombres'],'prefijo'=>$_SESSION['ADMISIONES']['BUSQUEDA']['prefijo'],'historia'=>$_SESSION['ADMISIONES']['BUSQUEDA']['historia']));  }
                else
                {
                        $contenedor=$_SESSION['ADMISIONES']['TRIAGE']['RETORNO']['contenedor'];
                        $modulo=$_SESSION['ADMISIONES']['TRIAGE']['RETORNO']['modulo'];
                        $tipo=$_SESSION['ADMISIONES']['TRIAGE']['RETORNO']['tipo'];
                        $metodo=$_SESSION['ADMISIONES']['TRIAGE']['RETORNO']['metodo'];
                        $argumentos=$_SESSION['ADMISIONES']['TRIAGE']['RETORNO']['argumentos'];
                        $accion=ModuloGetURL($contenedor,$modulo,$tipo,$metodo,$argumentos);
                }
                $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
                $this->salida .= "<td  align=\"center\"><input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"VOLVER\"></td>";
                $this->salida .= "</form>";
                $this->salida .= "</tr>";
                $this->salida .= " </table>";
                $this->salida .= ThemeCerrarTabla();
                return true;
    }


    /**
    *
    */
    function FormaUbicacionEstacion()
    {
                $this->salida .= ThemeAbrirTabla('ADMISIONES - UBICACION PACIENTE ESTACION');
                $this->salida .= "<table width=\"40%\" border=\"1\" align=\"center\" class=\"modulo_table_list_title\">";
                $this->salida .= "<tr class=\"modulo_table_list_title\">";
                $this->salida .= "<td  colspan=\"2\"><img src=\"".GetThemePath()."/images/servicios.png\" border='0'>&nbsp;&nbsp; UBICACION EN LA ESTACION</td>";
                $this->salida .= "</tr>";
                $this->salida .= "<tr class=\"modulo_list_claro\">";
                $this->salida .= "<td class=\"label\" width=\"33%\">IDENTIFICACION: </td>";
                $this->salida .= "<td class=\"label_mark\" align=\"left\">".$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente']." ".$_SESSION['ADMISIONES']['PACIENTE']['paciente_id']."</td>";
                $this->salida .= "</tr>";
                $this->salida .= "<tr class=\"modulo_list_claro\">";
                $this->salida .= "<td class=\"label\">PACIENTE: </td>";
                $this->salida .= "<td class=\"label_mark\" align=\"left\">".$_SESSION['ADMISIONES']['PACIENTE']['nombre']."</td>";
                $this->salida .= "</tr>";
                $this->salida .= "<tr class=\"modulo_list_claro\">";
                $this->salida .= "<td class=\"label\">ESTACION ENFERMERIA: </td>";
                $this->salida .= "<td class=\"label_mark\" align=\"left\">".$_SESSION['ADMISIONES']['PACIENTE']['nombre_estacion']."</td>";
                $this->salida .= "</tr>";
                $this->salida .= "<tr class=\"modulo_list_claro\">";
                $this->salida .= "<td class=\"label\">PIEZA: </td>";
                $this->salida .= "<td class=\"label_mark\" align=\"left\">".$_SESSION['ADMISIONES']['PACIENTE']['pieza']."</td>";
                $this->salida .= "</tr>";
                $this->salida .= "<tr class=\"modulo_list_claro\">";
                $this->salida .= "<td class=\"label\">CAMA: </td>";
                $this->salida .= "<td class=\"label_mark\" align=\"left\">".$_SESSION['ADMISIONES']['PACIENTE']['cama']."</td>";
                $this->salida .= "</tr>";
                $this->salida .= "<tr class=\"modulo_list_claro\">";
                $this->salida .= "<td class=\"label\">UBICACION: </td>";
                $this->salida .= "<td class=\"label_mark\" align=\"left\">".$_SESSION['ADMISIONES']['PACIENTE']['ubicacion']."</td>";
                $this->salida .= "</tr>";
                $this->salida.="</table><br>";
                $this->salida .= "<table width=\"40%\" border=\"0\" align=\"center\">";
                $this->salida .= "<tr>";
                //$accion=ModuloGetURL('app','Admisiones','user','BuscarPaciente',array('TipoDocumento'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'],'Documento'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id']));
                $accion=ModuloGetURL('app','Admisiones','user','BuscarPaciente',array('TipoDocumento'=>$_SESSION['ADMISIONES']['BUSQUEDA']['tipo_id_paciente'],'Documento'=>$_SESSION['ADMISIONES']['BUSQUEDA']['paciente_id'],'Nombres'=>$_SESSION['ADMISIONES']['BUSQUEDA']['Nombres'],'prefijo'=>$_SESSION['ADMISIONES']['BUSQUEDA']['prefijo'],'historia'=>$_SESSION['ADMISIONES']['BUSQUEDA']['historia']));
                $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
                $this->salida .= "<td  align=\"center\"><input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"VOLVER\"></td>";
                $this->salida .= "</form>";
                $this->salida .= "</tr>";
                $this->salida .= " </table>";
                $this->salida .= ThemeCerrarTabla();
                return true;
    }


    /**
    *
    */
    function FormaCambiarEstacion()
    {
                IncludeLib("funciones_admision");
                $this->salida .= ThemeAbrirTabla('ADMISIONES - ELEGIR ESTACION DE ENFERMERIA');
                $accion=ModuloGetURL('app','Admisiones','user','CambiarEstacionAtencion');
                $this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
                $this->salida .= "                <table width=\"50%\" align=\"center\" >";
                $this->salida .= $this->SetStyle("MensajeError");
                $this->salida .= "                </table><BR>";
                $this->salida .= "<p class=\"label_mark\" align=\"center\">ESTACION ACTUAL ASIGNADA: ".$_SESSION['ADMISIONES']['PACIENTE']['nombre_estacion']."<BR> ELIJA LA NUEVA ESTACION A LA QUE VA A ASIGNAR EL PACIENTE.</p>";
                $this->salida .= "                <table width=\"50%\" align=\"center\" >";
                $this->salida .= "                     <tr><td  width=\"30%\" class=\"".$this->SetStyle("Estacion")."\">ELIJA LA ESTACION: </td>";
                $this->salida .= "                     <td colspan=\"2\"><select name=\"Estacion\" class=\"select\">";
                $Est=BuscarTodasEstaciones();
                $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
                for($i=0; $i<sizeof($Est); $i++){
                        $this->salida .=" <option value=\"".$Est[$i][estacion_id]."||".$Est[$i][departamento]."||".$Est[$i][descripcion]."\">".$Est[$i][descripcion]."</option>";
                }
                $this->salida .= "              </select></td></tr>";
                $this->salida .= "                     <tr>";
                $this->salida .= "                     <tr>";
                $this->salida .= "                     <td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"></td>";
                $this->salida .= "               </form>";
                //$accion=ModuloGetURL('app','Admisiones','user','BuscarPaciente',array('TipoDocumento'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'],'Documento'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id']));
                $accion=ModuloGetURL('app','Admisiones','user','BuscarPaciente',array('TipoDocumento'=>$_SESSION['ADMISIONES']['BUSQUEDA']['tipo_id_paciente'],'Documento'=>$_SESSION['ADMISIONES']['BUSQUEDA']['paciente_id'],'Nombres'=>$_SESSION['ADMISIONES']['BUSQUEDA']['Nombres'],'prefijo'=>$_SESSION['ADMISIONES']['BUSQUEDA']['prefijo'],'historia'=>$_SESSION['ADMISIONES']['BUSQUEDA']['historia']));
                $this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
                $this->salida .= "                     <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"CANCELAR\"></td>";
                $this->salida .= "              </tr>";
                $this->salida .= "               </form>";
                $this->salida .= "               </table>";
                $this->salida .= ThemeCerrarTabla();
                return true;
    }

    /**
    *
    */
    function FormaSacarLista()
    {
                $this->salida .= ThemeAbrirTabla('ADMISIONES - SACAR PACIENTE LISTADO');
                //mensaje
                $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
                $this->salida .= $this->SetStyle("MensajeError");
                $this->salida .= "  </table><BR>";
                $accion=ModuloGetURL('app','Admisiones','user','SacarPaciente');
                $this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
                $this->salida .= "                <table width=\"60%\" align=\"center\" >";
                $this->salida .= "                     <tr>";
                $this->salida .= "                     <td align=\"center\" class=\"label_MARK\" colspan=\"2\">IDENTIFICACION: ".$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente']." ".$_SESSION['ADMISIONES']['PACIENTE']['paciente_id']."<BR>PACIENTE: ".$_SESSION['ADMISIONES']['PACIENTE']['nombre']."<BR>
                EL PACIENTE SERA SACADO DEL LISTADO Y SE CANCELARA SU PROCESO DE ATENCION EN LA INSTITUCION <BR>
                POR FAVOR ESPECIFIQUE EL MOTIVO</td>";
                $this->salida .= "              </tr>";
                $this->salida .= "                     <tr>";
                $this->salida .= "                     <td align=\"center\" class=\"label\">OBSERVACION: </td>";
                $this->salida .= "                     <td align=\"center\"><textarea cols=\"70\" rows=\"3\" class=\"textarea\"name=\"observacion\"></textarea>";
                $this->salida .= "              </tr>";
                $this->salida .= "               </table>";
                $this->salida .= "                <table width=\"50%\" align=\"center\" >";
                $this->salida .= "                     <tr>";
                $this->salida .= "                     <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"></td>";
                $this->salida .= "               </form>";
                //$accion=ModuloGetURL('app','Admisiones','user','BuscarPaciente',array('TipoDocumento'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'],'Documento'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id']));
                $accion=ModuloGetURL('app','Admisiones','user','BuscarPaciente',array('TipoDocumento'=>$_SESSION['ADMISIONES']['BUSQUEDA']['tipo_id_paciente'],'Documento'=>$_SESSION['ADMISIONES']['BUSQUEDA']['paciente_id'],'Nombres'=>$_SESSION['ADMISIONES']['BUSQUEDA']['Nombres'],'prefijo'=>$_SESSION['ADMISIONES']['BUSQUEDA']['prefijo'],'historia'=>$_SESSION['ADMISIONES']['BUSQUEDA']['historia']));
                $this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
                $this->salida .= "                     <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"CANCELAR\"></td>";
                $this->salida .= "              </tr>";
                $this->salida .= "               </form>";
                $this->salida .= "               </table>";
                $this->salida .= ThemeCerrarTabla();
                return true;
    }


    /**
    *
    */
    function FormaRemision()
    {
            IncludeLib("funciones_admision");
            $arr=$_SESSION['ADMISIONES']['PACIENTE']['DATOSREMISION'];
            $this->salida .= ThemeAbrirTabla('HOJA TRIAGE');
            $this->salida .= "                <table width=\"75%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
            $this->salida .= "                     <tr>";
            $this->salida .= "                        <td class=\"modulo_list_claro\" colspan=\"4\" align=\"center\"><b>HOJA TRIAGE</b></td>";
            $this->salida .= "                     </tr>";
            $this->salida .= "                     <tr>";
            $this->salida .= "                        <td class=\"modulo_list_claro\" colspan=\"4\" align=\"center\"><b>DEPARTAMENTO DE SERVICIOS DE ".$arr[0]['descripcion']."</b></td>";
            $this->salida .= "                     </tr>";
            $this->salida .= "                     <tr>";
            $this->salida .= "                        <td align=\"center\" class=\"modulo_table_list_title\">INSTITUCION QUE REMITE: </td>";
            $this->salida .= "                        <td class=\"modulo_list_claro\" colspan=\"3\">".$arr[0][razon_social]."</td>";
            $this->salida .= "                     </tr>";
            $this->salida .= "                     <tr>";
            $this->salida .= "                        <td align=\"center\" width=\"20%\" class=\"modulo_table_list_title\">IDENTIFICACION: </td>";
            $this->salida .= "                        <td class=\"modulo_list_claro\" width=\"17%\">".$arr[0]['tipo_id_paciente']." ".$arr[0]['paciente_id']."</td>";
            $this->salida .= "                        <td align=\"center\" class=\"modulo_table_list_title\" width=\"10%\">PACIENTE: </td>";
            $this->salida .= "                        <td class=\"modulo_list_claro\" width=\"30%\">".$arr[0]['nombre']."</td>";
            $this->salida .= "                     </tr>";
            $this->salida .= "                     <tr>";
            $this->salida .= "                        <td align=\"center\" width=\"20%\" class=\"modulo_table_list_title\">CLASIFICACION: </td>";
            $this->salida .= "                        <td class=\"modulo_list_claro\" width=\"17%\">NIVEL ".$arr[0]['nivel_triage_id']."</td>";
            $this->salida .= "                        <td align=\"center\" class=\"modulo_table_list_title\" width=\"10%\">FECHA: </td>";
            $this->salida .= "                        <td class=\"modulo_list_claro\" width=\"30%\">".$this->FechaStamp($arr[0]['fecha_registro'])." ".$this->HoraStamp($arr[0]['fecha_registro'])."</td>";
            $this->salida .= "                     </tr>";
            $this->salida .= "                     <tr>";
            $this->salida .= "                        <td align=\"center\" class=\"modulo_table_list_title\">MEDICO QUE REMITE: </td>";
            $this->salida .= "                        <td class=\"modulo_list_claro\" colspan=\"3\">".$arr[0][medico]."</td>";
            $this->salida .= "                     </tr>";
            if(is_array($arr[3]))
            {
                    $this->salida .= "                     <tr >";
                    $this->salida .= "                        <td align=\"center\" class=\"modulo_table_list_title\">CAUSAS PROBABLES: </td>";
                    $this->salida .= "                        <td class=\"modulo_list_claro\" colspan=\"3\">";
                    $this->salida .= "                   <table width=\"100%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
                    $this->salida .= "                       <tr class=\"modulo_table_list_title\">";
                    $this->salida .= "                   <td>NIVEL</td>";
                    $this->salida .= "                   <td>CAUSA PROBABLE</td>";
                    $this->salida .= "                       </tr>";
                    for($i=0; $i<sizeof($arr[3]);)
                    {
                            $this->salida .= "                       <tr class=\"modulo_list_oscuro\">";
                            $estilo=ColorTriage($arr[3][$i][nivel_triage_id]);
                            $this->salida .= "                   <td class=\"$estilo\" width=\"15%\" align=\"center\">NIVEL ".$arr[3][$i][nivel_triage_id]."</td>";
                            $this->salida .= "                   <td width=\"75%\">";
                            $this->salida .= "                               <table width=\"100%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
                            $d=$i;
                            while($arr[3][$i][nivel_triage_id]==$arr[3][$d][nivel_triage_id])
                            {
                                    $estiloClaro=ColorTriageClaro($arr[3][$d][nivel_triage_id]);
                                    $this->salida .= "                                   <tr class=\"modulo_list_claro\">";
                                    $this->salida .= "                               <td class=\"$estiloClaro\">".$arr[3][$d][descripcion]."</td>";
                                    $this->salida .= "                                   </tr>";
                                    $d++;
                            }
                            $i=$d;
                            $this->salida .= "                             </table>";
                            $this->salida .= "                   </td>";
                            $this->salida .= "                       </tr>";
                    }
                    $this->salida .= "                       </table>";
                    $this->salida .= "              </td>";
                    $this->salida .= "                     </tr>";
            }
            $this->salida .= "                     <tr >";
            $this->salida .= "                        <td align=\"center\" class=\"modulo_table_list_title\">MOTIVO CONSULTA: </td>";
            $this->salida .= "                        <td class=\"modulo_list_claro\" colspan=\"3\"><textarea name=\"MotivoConsulta\" cols=\"90\" rows=\"2\" class=\"textarea\" readonly>".$arr[0][motivo_consulta]."</textarea></td>";
            $this->salida .= "                     </tr>";

            $this->salida .= "                     <tr >";
            $this->salida .= "                        <td align=\"center\" class=\"modulo_table_list_title\">SIGNOS VITALES: </td>";
            $this->salida .= "                        <td class=\"modulo_list_claro\" colspan=\"3\">";
            $glas=$arr[0][respuesta_motora_id] + $arr[0][respuesta_verbal_id]+ $arr[0][apertura_ocular_id];
            if(empty($glas)){   $glas='--';  }
            $this->salida .= "                   <table width=\"70%\" border=\"0\" align=\"center\" class=\"modulo_table_list_title\" cellspacing=\"3\" cellpadding=\"3\">";
            $this->salida .= "                       <tr align=\"center\" class=\"modulo_table_list_title\">";
            $this->salida .= "                       <td>F.C.</td>";
            $this->salida .= "                       <td>F.R.</td>";
            $this->salida .= "                       <td>PESO(Kg)</td>";
            $this->salida .= "                       <td>T.A.</td>";
            $this->salida .= "                       <td>TEMP.</td>";
            $this->salida .= "                       <td>EVA.</td>";
            $this->salida .= "                       <td>GLASGOW</td>";
            $this->salida .= "                       </tr>";
            $this->salida .= "                       <tr>";
            $this->salida .= "                         <td class=\"modulo_list_claro\" width=\"10%\">".$arr[0][signos_vitales_fc]." /m</td>";
            $this->salida .= "                         <td class=\"modulo_list_claro\" width=\"10%\">".$arr[0][signos_vitales_fr]." /m</td>";
            $this->salida .= "                         <td class=\"modulo_list_claro\" width=\"15%\">".$arr[0][signos_vitales_peso]."</td>";
            $this->salida .= "                         <td class=\"modulo_list_claro\" width=\"15%\">".$arr[0][signos_vitales_tabaja]." / ".$arr[0][signos_vitales_taalta]."</td>";
            $this->salida .= "                         <td class=\"modulo_list_claro\" width=\"10%\">".$arr[0][signos_vitales_temperatura]." C</td>";
            $this->salida .= "                         <td class=\"modulo_list_claro\" width=\"10%\">".$arr[0][evaluacion_dolor]."</td>";
            $this->salida .= "                         <td class=\"modulo_list_claro\" width=\"10%\">".$glas."</td>";
            $this->salida .= "                       </tr>";
            $this->salida .= "                       </table>";
            $this->salida .= "                                  </td>";
            $this->salida .= "                     </tr>";
            if(is_array($arr[1]))
            {
                    $this->salida .= "                     <tr >";
                    $this->salida .= "                        <td align=\"center\" class=\"modulo_table_list_title\">DIAGNOSTICO: </td>";
                    $this->salida .= "                        <td class=\"modulo_list_oscuro\" colspan=\"3\">";
                    $this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"100%\">";
                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida.="  <td width=\"9%\">CODIGO</td>";
                    $this->salida.="  <td width=\"88%\">DESCRIPCION</td>";
                    $this->salida.="</tr>";
                    for($k=0; $k<sizeof($arr[1]); $k++)
                    {
                            $this->salida.="<tr class=\"modulo_list_claro\">";
                            $this->salida.="  <td align=\"center\">".$arr[1][$k][diagnostico_id]."</td>";
                            $this->salida.="  <td>".$arr[1][$k][diagnostico_nombre]."</td>";
                            $this->salida.="</tr>";
                    }
                    $this->salida.="</table><br>";
                    $this->salida .= "                     </td>";
                    $this->salida .= "                     </tr>";
            }
            if(!empty($arr[0][observacion_remision]))
        {  $this->salida .= "                      <tr><td align=\"center\" width=\"150\" class=\"modulo_table_list_title\">OBSERVACION REMISION: </td><td width=\"610\" class=\"modulo_list_claro\" colspan=\"3\">".$arr[0][observacion_remision]."</td></tr>";  }
            else
        {  $this->salida .= "                      <tr><td align=\"center\" width=\"150\" class=\"modulo_table_list_title\">OBSERVACION REMISION: </td><td width=\"610\" class=\"modulo_list_claro\" colspan=\"3\">&nbsp;</td></tr>";  }

            $this->salida .= "                     <tr >";
            $this->salida .= "                        <td align=\"center\" class=\"modulo_table_list_title\">INSTITUCIONES A LAS QUE SE REMITE: </td>";
            $this->salida .= "                        <td class=\"modulo_list_oscuro\" colspan=\"3\">";
            if(is_array($arr[2]))
            {
                    $this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"100%\">";
                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida.="  <td width=\"15%\">CODIGO</td>";
                    $this->salida.="  <td width=\"75%\">INSTITUCION</td>";
                    $this->salida.="  <td width=\"10%\">NIVEL</td>";
                    $this->salida.="</tr>";
                    for($i=0; $i<sizeof($arr[2]); $i++)
                    {
                                $this->salida.="<tr class=\"modulo_list_claro\">";
                                $this->salida.="  <td  align=\"center\">".$arr[2][$i][centro_remision]."</td>";
                                $this->salida.="  <td>".$arr[2][$i][descripcion]."      ".$arr[2][$i][direccion]."   ".$arr[2][$i][telefono]."</td>";
                                $this->salida.="  <td align=\"center\">NIVEL ".$arr[2][$i][nivel]."</td>";
                                $this->salida.="</tr>";
                    }
                    $this->salida.="</table><br>";
            }
            $this->salida .= "                     </td>";
            $this->salida .= "                     </tr>";
            $this->salida.="</table><br>";

            $this->salida.="<table  align=\"center\" border=\"0\"  width=\"40%\">";
            $this->salida .= "                     <tr>";

            $reporte= new GetReports();
            $mostrar=$reporte->GetJavaReport('app','Remisiones','contrareferenciaHTM',array('triage'=>$arr[0][triage_id]),array('rpt_name'=>'contrareferencia','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
            $funcion=$reporte->GetJavaFunction();
            $this->salida .=$mostrar;
            $this->salida .= "                     <td align=\"center\"><input class=\"input-submit\" type=\"button\" name=\"Imprimir\" value=\"IMPRIMIR\" onclick=\"javascript:$funcion\"></form></td>";
            unset($reporte);
            //$accion=ModuloGetURL('app','Admisiones','user','BuscarPaciente',array('TipoDocumento'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'],'Documento'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id']));
            $accion=ModuloGetURL('app','Admisiones','user','BuscarPaciente',array('TipoDocumento'=>$_SESSION['ADMISIONES']['BUSQUEDA']['tipo_id_paciente'],'Documento'=>$_SESSION['ADMISIONES']['BUSQUEDA']['paciente_id'],'Nombres'=>$_SESSION['ADMISIONES']['BUSQUEDA']['Nombres'],'prefijo'=>$_SESSION['ADMISIONES']['BUSQUEDA']['prefijo'],'historia'=>$_SESSION['ADMISIONES']['BUSQUEDA']['historia']));
            $this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
            $this->salida .= "                     <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"VOLVER\"></td>";
            $this->salida .= "              </tr>";
            $this->salida .= "               </form>";
            $this->salida .= "               </table>";
            $this->salida .= ThemeCerrarTabla();
            return true;
    }

    /**
    *
    */
    function FormaImpresionSolicitudes($datos='',$control='')
    {
            $this->IncludeJS("CrossBrowser");
            $this->IncludeJS("CrossBrowserDrag");
            $this->IncludeJS("CrossBrowserEvent");
            IncludeLib("funciones_central_impresion");

            if(!empty($datos))
            {
                if($control==1)
                {
                    $RUTA = $_ROOT ."cache/incapacidad_medica".UserGetUID().".pdf";
                }
                else if($control==2)
                {
                    $RUTA = $_ROOT ."cache/solicitudes".UserGetUID().".pdf";
                }
                else if($control==3)
                {
                    $RUTA = $_ROOT ."cache/ordenservicio".$datos['orden'].".pdf";
                }
                else
                {
                    $RUTA = $_ROOT ."cache/formula_medica_hos.pdf";
                }
                $DIR="printer.php?ruta=$RUTA";
                $RUTA1= GetBaseURL() . $DIR;
                $mostrar ="\n<script language='javascript'>\n";
                $mostrar.="var rem=\"\";\n";
                $mostrar.="  function abreVentana(){\n";
                $mostrar.="    var url2=\"\"\n";
                $mostrar.="    var width=\"400\"\n";
                $mostrar.="    var height=\"300\"\n";
                $mostrar.="    var winX=Math.round(screen.width/2)-(width/2);\n";
                $mostrar.="    var winY=Math.round(screen.height/2)-(height/2);\n";
                $mostrar.="    var nombre=\"Printer_Mananger\";\n";
                $mostrar.="    var str =\"width=\"+width+\",height=\"+height+\",left=\"+winX+\",top=\"+winY+\",resizable=no,status=no,scrollbars=yes,location=no\";\n";
                $mostrar.="    var url2 ='$RUTA1';\n";
                $mostrar.="    rem = window.open(url2, nombre, str)};\n";
                $mostrar.="</script>\n";
                $this->salida.="$mostrar";
                $this->salida.="<BODY onload=abreVentana();>";
            }

            $this->salida .= ThemeAbrirTabla('IMPRESION SOLICITUDES MEDICAS');
            $reporte= new GetReports();

	       if(empty($_SESSION['ADMISIONES']['DATOS']))
            {  $_SESSION['ADMISIONES']['DATOS']=$this->EncabezadoReporte();  }
            
               //reportes de claudia
               //la variable $tipo_formulacion se carga con los dos tipos de formulacion que existen en SIIS
               //que son amb y hosp, de tal manera que a la funcion FrmMedicamentos se le envie cada tipo
               //para que de acuerdo a ello invoque las funciones respectivas.
          
               $vectorOriginal = array();
	          // Variable de impresion de medicamentos especiales.
               unset($_SESSION['MED_NOPOS']);
               unset($_SESSION['MED_CONTROL']);

               
               //tipo 1 de formulacion
               $tipo_formulacion = 'amb';
               if(!empty($_SESSION['ADMISIONES']['PACIENTE']['ingreso']))
               {  
	               $vector1 = GetMedicamentosHospitalariosAmbulatorios($_SESSION['ADMISIONES']['PACIENTE']['ingreso'],'');
               }
          
               if($vector1)
               {
                    array_push($vectorOriginal, $vector1);
                    $this->FrmMedicamentos($vectorOriginal, &$reporte, $_SESSION['ADMISIONES']['PACIENTE']['ingreso'], $_SESSION['ADMISIONES']['PACIENTE']['evolucion_id'], $tipo_formulacion);
               }

               // Variable de impresion de medicamentos especiales.
               unset($_SESSION['MED_NOPOS']);
               unset($_SESSION['MED_CONTROL']);

               $vectorOriginal = "";
               $vectorOriginal = array();
               
               //tipo 2 de formulacion
               $tipo_formulacion = 'hosp';
               if(!empty($_SESSION['ADMISIONES']['PACIENTE']['evolucion_id']))
               {  
                    $vector2 = GetMedicamentos($_SESSION['ADMISIONES']['PACIENTE']['evolucion_id']);
               }
               
               if($vector2)
               {
                    array_push($vectorOriginal, $vector2);
               }
               
               // Vector de consulta de soluciones
               if(!empty($_SESSION['ADMISIONES']['PACIENTE']['evolucion_id']))
               {  
                    $vector3 = GetSoluciones($_SESSION['ADMISIONES']['PACIENTE']['evolucion_id']);
               }
               if($vector3)
               {
                    array_push($vectorOriginal, $vector3);
               }
               
               if($vectorOriginal)
               {
                    $this->FrmMedicamentos($vectorOriginal, &$reporte, $_SESSION['ADMISIONES']['PACIENTE']['ingreso'], $_SESSION['ADMISIONES']['PACIENTE']['evolucion_id'], $tipo_formulacion);          
               }
               //fin claudia.

            /*if(!empty($_SESSION['ADMISIONES']['PACIENTE']['evolucion_id']))
            {  
                $vector1=GetMedicamentos($_SESSION['ADMISIONES']['PACIENTE']['evolucion_id']);
                $amb = "0";
            }
            else
            {  
                $vector1=GetMedicamentosHospitalariosAmbulatorios($_SESSION['ADMISIONES']['PACIENTE']['ingreso'],'');
                $amb = "1";
            }*/

            $accion=ModuloGetURL('app','Admisiones','user','BuscarOrdenes');
            $this->SetJavaScripts('DatosPaciente');
            /*if($vector1)
            {
                  $this->FrmMedicamentos($vector1,&$reporte,$amb);
            }*/

            if(!empty($_SESSION['ADMISIONES']['PACIENTE']['evolucion_id']))
            {  $arr=BuscarSolicitudesEvolucion($_SESSION['ADMISIONES']['PACIENTE']['evolucion_id']);  }
            else
            {   $arr=BuscarSolicitudesHospitalariasAmbulatorias($_SESSION['ADMISIONES']['PACIENTE']['ingreso']);}
            //{  $arr=BuscarSolicitudesIngreso($_SESSION['ADMISIONES']['PACIENTE']['ingreso']);  }
            if(!empty($arr))
            {
                            $this->FormaSolicitudes($arr,&$reporte);
            }
            $var='';
            if(!empty($_SESSION['ADMISIONES']['PACIENTE']['evolucion_id']))
            {  $var=BuscarOrdenesSEvolucion($_SESSION['ADMISIONES']['PACIENTE']['evolucion_id']);  }
            else
            {   $var=BuscarOrdenesHospitalariasAmbulatorias($_SESSION['ADMISIONES']['PACIENTE']['ingreso']);   }
            //{  $var=BuscarOrdenesIngreso($_SESSION['ADMISIONES']['PACIENTE']['ingreso']);  }
            if(!empty($var))
            {
                            $this->FormaOrdenes($var,&$reporte);
            }

            if(!empty($_SESSION['ADMISIONES']['PACIENTE']['evolucion_id']))
            {  $vec=Consulta_Incapacidades_GeneradasEvolucion($_SESSION['ADMISIONES']['PACIENTE']['evolucion_id']);  }
            else
            {  $vec=Consulta_Incapacidades_GeneradasIngreso($_SESSION['ADMISIONES']['PACIENTE']['ingreso']);  }
            if(!empty($vec))
            {
                            $this->FrmIncapacidad($vec,&$reporte);
            }
            unset($reporte);
            if(!$vector1 AND !$arr AND !$var AND !$vec)
            {
                            $this->salida.="<br><br><table align=\"center\" width='80%' border=\"0\">";
                            $this->salida.="  <TR><td align=\"center\" width=\"9%\"><label class='label_mark'>EL PACIENTE NO TIENE NINGUNA SOLICITUD</label></td><TR>";
                            $this->salida.="</table>";
            }
            $this->salida .= "</form>";

            $this->salida.="<table  align=\"center\" border=\"0\"  width=\"40%\">";
            $this->salida .= "                     <tr>";
            if(empty($_SESSION['ADMISIONES']['IMPRESION']['RETORNO']))
            {  $accion=ModuloGetURL('app','Admisiones','user','BuscarPaciente',array('TipoDocumento'=>$_SESSION['ADMISIONES']['BUSQUEDA']['tipo_id_paciente'],'Documento'=>$_SESSION['ADMISIONES']['BUSQUEDA']['paciente_id'],'Nombres'=>$_SESSION['ADMISIONES']['BUSQUEDA']['Nombres'],'prefijo'=>$_SESSION['ADMISIONES']['BUSQUEDA']['prefijo'],'historia'=>$_SESSION['ADMISIONES']['BUSQUEDA']['historia']));  }
            else
            {
                    $contenedor=$_SESSION['ADMISIONES']['IMPRESION']['RETORNO']['contenedor'];
                    $modulo=$_SESSION['ADMISIONES']['IMPRESION']['RETORNO']['modulo'];
                    $tipo=$_SESSION['ADMISIONES']['IMPRESION']['RETORNO']['tipo'];
                    $metodo=$_SESSION['ADMISIONES']['IMPRESION']['RETORNO']['metodo'];
                    $argumentos=$_SESSION['ADMISIONES']['IMPRESION']['RETORNO']['argumentos'];
                    $accion=ModuloGetURL($contenedor,$modulo,$tipo,$metodo,$argumentos);
            }
            $this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
            $this->salida .= "                     <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"VOLVER\"></td>";
            $this->salida .= "              </tr>";
            $this->salida .= "               </form>";
            $this->salida .= "               </table>";
            $this->salida .= ThemeCerrarTabla();
            return true;
    }


    /**
    *
    */
    function FormaSolicitudes($arr,$reporte)
    {
          unset($_SESSION['ADMISIONES']['ARR_SOLICITUDES']);
          IncludeLib("malla_validadora");
          $this->salida .= "         <br><table width=\"80%\" border=\"0\" align=\"center\">";
          $this->salida .= "            <tr class=\"modulo_table_title\"><td colspan=\"5\" align=\"center\">SOLICITUDES</td></tr>";
          for($i=0; $i<sizeof($arr);)
          {
               $d=$i;
               if($arr[$i][plan_id]==$arr[$d][plan_id]
               AND $arr[$i][servicio]==$arr[$d][servicio])
               {
                    $this->salida .= "            <tr><td colspan=\"5\" class=\"modulo_table_title\">PLAN:".$arr[$i][plan_descripcion]."</td></tr>";
                    $this->salida .= "            <tr>";
                    $this->salida .= "                <td class=\"modulo_table_title\" width=\"12%\">SERVICIO: </td>";
                    $this->salida .= "                <td class=\"modulo_list_claro\" width=\"13%\">".$arr[$i][desserv]."</td>";
                    $this->salida .= "                <td class=\"modulo_table_title\" width=\"11%\">DEPARTAMENTO: </td>";
                    $this->salida .= "                <td class=\"modulo_list_claro\" align=\"left\" colspan=\"2\">".$arr[$i][despto]."</td>";
                    $this->salida .= "            </tr>";
                    $this->salida .= "            <tr class=\"modulo_table_title\">";
                    $this->salida .= "                <td>FECHA</td>";
                    $this->salida .= "                <td>CARGO</td>";
                    $this->salida .= "                <td colspan=\"2\" width=\"50%\">DESCRIPCION</td>";
                    $this->salida .= "                <td width=\"10%\">TIPO</td>";
                    //$this->salida .= "                <td width=\"11%\">JUSTIF.</td>";
                    $this->salida .= "            </tr>";
               }
               while($arr[$i][plan_id]==$arr[$d][plan_id]
               AND $arr[$i][servicio]==$arr[$d][servicio])
               {
                    if($d % 2) {  $estilo="modulo_list_claro";  }
                    else {  $estilo="modulo_list_oscuro";   }
                    $this->salida .= "            <tr class=\"$estilo\">";
                    $this->salida .= "                <td>".$this->FechaStamp($arr[$i][fecha])." ".$this->HoraStamp($arr[$i][fecha])."</td>";
                    $this->salida .= "                <td align=\"center\">".$arr[$d][cargos]."</td>";
                    $this->salida .= "                <td colspan=\"2\">".$arr[$d][descar]."</td>";
                    $this->salida .= "                <td align=\"center\">".$arr[$d][desos]."</td>";
                    $this->salida .= "            </tr>";

                    $this->salida .= "            <tr class=\"$estilo\">";
                    $this->salida .= "                <td width=\"11%\" class=\"modulo_table_title\" >JUSTIFICACION:</td>";            
                    $x=MallaValidadoraValidarCargo($arr[$d][cargos],$arr[$d][plan_id],$arr[$d][servicio],$arr[$d][hc_os_solicitud_id],$arr[$d][cantidad]);
                    if(is_array($x))
                    {  $this->salida .= "                <td align=\"center\" colspan=\"4\">CARGO VALIDADO POR LA MALLA</td>";  }
                    else
                    {  $this->salida .= "                <td align=\"center\" colspan=\"4\">$x</td>";  }
                    $this->salida .= "            </tr>";
                    $d++;
               }
               $i=$d;
          }
          //Variable de session que contiene el arreglo de las solicitudes para cuando se vayan a imprimir
          $_SESSION['ADMISIONES']['ARR_SOLICITUDES']=$arr;
          $go_to_url=ModuloGetURL('app','Admisiones','user','Reportesolicitudes',array('pos'=>1));
          $this->salida .= "                <tr><td class=$estilo colspan=\"3\" align=\"center\" width=\"7%\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp;<a href=\"$go_to_url\"> IMPRIMIR POS</a></td>";
          
          $go_to_url=ModuloGetURL('app','Admisiones','user','Reportesolicitudes',array('pos'=>0));

          $go_to_url=ModuloGetURL('app','Admisiones','user','Reportesolicitudes',array('TipoDocumento'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'],'Documento'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id'],'Nombres'=>$_SESSION['ADMISIONES']['PACIENTE']['nombre'],'evolucion'=>$_SESSION['ADMISIONES']['PACIENTE']['evolucion_id'],'ingreso'=>$_SESSION['ADMISIONES']['PACIENTE']['ingreso'],'pos'=>0));
          
          $this->salida .= "                <td class=$estilo colspan=\"1\" align=\"center\" width=\"7%\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp;<a href=\"$go_to_url\"> IMPRIMIR MEDIA CARTA</a></td>";

          // Cambio Realizado por Tizziano Perea.
          if($_SESSION['SALIDAPACIENTES'] == true OR $_SESSION['EE_ESTACION'] == true)
          {
               $mostrar=$reporte->GetJavaReport('app','CentralImpresionHospitalizacion','solicitudesHTM',array('TipoDocumento'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'],'Documento'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id'],'Nombres'=>$_SESSION['ADMISIONES']['PACIENTE']['nombre'],'evolucion'=>$_SESSION['ADMISIONES']['PACIENTE']['evolucion_id'],'ingreso'=>$_SESSION['ADMISIONES']['PACIENTE']['ingreso'],'peticion'=>'SalidaPacientes'),array('rpt_name'=>'solicitudesHTM','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));                         
          }
          else
          {
               $mostrar=$reporte->GetJavaReport('app','CentralImpresionHospitalizacion','solicitudesHTM',array('TipoDocumento'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'],'Documento'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id'],'Nombres'=>$_SESSION['ADMISIONES']['PACIENTE']['nombre'],'evolucion'=>$_SESSION['ADMISIONES']['PACIENTE']['evolucion_id'],'ingreso'=>$_SESSION['ADMISIONES']['PACIENTE']['ingreso']),array('rpt_name'=>'solicitudesHTM','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));                         
          }
          
          $funcion=$reporte->GetJavaFunction();
          $this->salida .=$mostrar;
          $this->salida .= "                <td class=$estilo colspan=\"1\" align=\"center\" width=\"7%\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp;<a href=\"javascript:$funcion\"> IMPRIMIR</a></td></tr>";
          $this->salida .= " </table>";
    }

    /**
    *
    */
    function FormaOrdenes($var,$reporte)
    {
            $this->salida .= "    <br><table width=\"80%\" border=\"0\" align=\"center\" >";
            $this->salida .= "            <tr class=\"modulo_table_title\">";
            $this->salida .= "                <td colspan=\"5\" align=\"CENTER\">ORDENES</td>";
            $this->salida .= "            </tr>";
            $this->salida .= "             </table>";
            for($i=0; $i<sizeof($var);)
            {
                    $d=$i;
                    $this->salida .= "    <table width=\"80%\" border=\"1\" align=\"center\" >";
                    $this->salida .= "            <tr class=\"modulo_table_title\">";
                    $this->salida .= "                <td colspan=\"5\" align=\"left\">NUMERO DE ORDEN ".$var[$i][orden_servicio_id]."</td>";
                    $this->salida .= "            </tr>";
                    $this->salida .= "            <tr>";
                    $this->salida .= "                <td colspan=\"5\" class=\"modulo_list_claro\">";
                    $this->salida .= "                        <table width=\"100%\" border=\"1\" align=\"center\" class=\"\">";
                    $this->salida .= "                                <tr>";
                    $this->salida .= "                                        <td width=\"5%\" class=\"modulo_table_title\">TIPO AFILIADO: </td>";
                    $this->salida .= "                                        <td width=\"5%\" class=\"hc_table_submodulo_list_title\">".$var[$d][tipo_afiliado_nombre]."</td>";
                    $this->salida .= "                                        <td width=\"5%\" class=\"modulo_table_title\">RANGO: </td>";
                    $this->salida .= "                                        <td width=\"5%\" class=\"hc_table_submodulo_list_title\">".$var[$d][rango]."</td>";
                    $this->salida .= "                                        <td width=\"5%\" class=\"modulo_table_title\">SEMANAS COT.: </td>";
                    $this->salida .= "                                        <td width=\"5%\" class=\"hc_table_submodulo_list_title\">".$var[$d][semanas_cotizadas]."</td>";
                    $this->salida .= "                                        <td width=\"5%\" class=\"modulo_table_title\">SERVICIO: </td>";
                    $this->salida .= "                                        <td width=\"5%\" class=\"hc_table_submodulo_list_title\">".$var[$d][desserv]."</td>";
                    $this->salida .= "                                </tr>";
                    $this->salida .= "                                <tr>";
                    $this->salida .= "                                        <td width=\"5%\" class=\"modulo_table_title\">AUT. INT.: </td>";
                    $this->salida .= "                                        <td width=\"5%\" class=\"hc_table_submodulo_list_title\">".$var[$d][autorizacion_int]."</td>";
                    $this->salida .= "                                        <td width=\"5%\" class=\"modulo_table_title\">AUT. EXT: </td>";
                    $this->salida .= "                                        <td width=\"5%\" class=\"hc_table_submodulo_list_title\">".$var[$d][autorizacion_ext]."</td>";
                    $this->salida .= "                                        <td width=\"5%\" class=\"modulo_table_title\">AUTORIZADOR: </td>";
                    $dat=$this->BuscarAutorizador($var[$d][autorizacion_int],$var[$d][autorizacion_ext]);
                    $this->salida .= "                                        <td width=\"5%\" colspan=\"3\" class=\"hc_table_submodulo_list_title\">".$dat."</td>";
                    $this->salida .= "                                </tr>";
                    $this->salida .= "                                <tr>";
                    $this->salida .= "                                        <td width=\"5%\" class=\"modulo_table_title\">PLAN: </td>";
                    $this->salida .= "                                        <td width=\"5%\" class=\"hc_table_submodulo_list_title\" colspan=\"7\" align=\"left\">".$var[$d][plan_descripcion]."</td>";
                    $this->salida .= "                                </tr>";
                    $this->salida .= "                                <tr>";
                    $this->salida .= "                                        <td width=\"5%\" class=\"modulo_table_title\">OBSERVACIONES: </td>";
                    $this->salida .= "                                        <td width=\"5%\" colspan=\"7\" class=\"hc_table_submodulo_list_title\" align=\"left\">".$var[$d][observacion]."</td>";
                    $this->salida .= "                                </tr>";
                    $this->salida .= "                         </table>";
                    $this->salida .= "                </td>";
                    $this->salida .= "            </tr>";
                    while($var[$i][orden_servicio_id]==$var[$d][orden_servicio_id])
                    {
                            $this->salida .= "            <tr>";
                            $this->salida .= "                <td colspan=\"5\">";
                            $this->salida .= "                <table width=\"99%\" border=\"0\" align=\"center\">";
                            $this->salida .= "            <tr class=\"modulo_table_title\">";
                            $this->salida .= "                <td width=\"6%\">ITEM</td>";
                            $this->salida .= "                <td width=\"6%\">CANT.</td>";
                            $this->salida .= "                <td width=\"10%\">CARGO</td>";
                            $this->salida .= "                <td width=\"45%\">DESCRICPION</td>";
                            $this->salida .= "                <td width=\"20%\">PROVEEDOR</td>";
                            $this->salida .= "            </tr>";
                            if($d % 2) {  $estilo="modulo_list_claro";  }
                            else {  $estilo="modulo_list_oscuro";   }
                            $this->salida .= "            <tr class=\"$estilo\">";
                            $this->salida .= "                <td align=\"center\">".$var[$d][numero_orden_id]."</td>";
                            $this->salida .= "                <td align=\"center\">".$var[$d][cantidad]."</td>";
                            if(!empty($var[$d][cargo])){  $cargo=$var[$d][cargo];  }
                            else {  $cargo=$var[$d][cargoext];   }
                            $this->salida .= "                <td align=\"center\">".$cargo."</td>";
                            $this->salida .= "                <td>".$var[$d][descripcion]."</td>";
                            $p='';
                            if(!empty($var[$d][departamento]))
                            {  $p='DPTO. '.$var[$d][desdpto];  $id=$var[$d][departamento]; $tipo='i'; }
                            else
                            {  $p=$var[$d][planpro];  $id=$var[$d][plan_proveedor_id]; $tipo='e'; }
                            $this->salida .= "                <td align=\"center\">".$p."</td>";
                            $this->salida .= "            </tr>";
                            $this->salida .= "            <tr class=\"modulo_list_oscuro\">";
                            $this->salida .= "                <td colspan=\"5\">";
                            $this->salida .= "                        <table width=\"100%\" border=\"0\" align=\"center\">";
                            $this->salida .= "                                <tr class=\"modulo_list_claro\">";
                            $this->salida .= "                                        <td width=\"5%\" class=\"modulo_table_title\">ACTIVACION: </td>";
                            $this->salida .= "                                        <td width=\"5%\" colspan=\"2\">".$this->FechaStamp($var[$d][fecha_activacion])."</td>";
                            $this->salida .= "                                        <td width=\"5%\" class=\"modulo_table_title\">VENC.: </td>";
                            $x='';
                            if(date("Y-m-d") > $var[$d][fecha_vencimiento]) $x='VENCIDA';
                            $this->salida .= "                                        <td width=\"5%\" >".$this->FechaStamp($var[$d][fecha_vencimiento])."</td>";
                            $this->salida .= "                                        <td width=\"5%\" class=\"label_error\" align=\"center\">".$x."</td>";
                            $this->salida .= "                                        <td width=\"5%\" class=\"modulo_table_title\">REFRENDAR HASTA: </td>";
                            $this->salida .= "                                        <td width=\"5%\">".$this->FechaStamp($var[$d][fecha_refrendar])."</td>";
                            $this->salida .= "                                </tr>";
                            $this->salida .= "                         </table>";
                            $this->salida .= "        <table width=\"100%\" border=\"0\" align=\"center\">";
                            $this->salida .= "            <tr class=\"modulo_list_claro\" align=\"center\">";
                            $this->salida .= "                                        <td width=\"7%\" class=\"modulo_table_title\">ESTADO: </td>";
                            $this->salida .= "                                        <td width=\"7%\" class=\"hc_table_submodulo_list_title\" colspan=\"2\">".$var[$d][estado]."</td>";
                            $this->salida .= "                <td width=\"15%\"></td>";
                            $accionP=ModuloGetURL('app','Admisiones','user','FormaCambiarProveedor',array('tipoid'=>$var[0][tipo_id_paciente],'pacienteid'=>$var[0][paciente_id],'orden'=>$var[$d][orden_servicio_id],'numor'=>$var[$d][numero_orden_id],'proveedor'=>$id,'cargo'=>$cargo,'plan'=>$var[$f][plan_id],'tipop'=>$tipo));
                            $this->salida .= "                <td width=\"7%\"><a href=\"$accionP\">CAMBIAR PROVEEDOR</a></td>";
                            $this->salida .= "            </tr>";
                            $this->salida .= "             </table>";
                            $this->salida .= "                </td>";
                            $this->salida .= "            </tr>";
                            $this->salida .= "             </table>";
                            $this->salida .= "                </td>";
                            $this->salida .= "            </tr>";
                            $d++;
                    }
                    $this->salida .= "            <tr class=\"$estilo\">";
                    $accion=ModuloGetURL('app','Admisiones','user','ReporteOrdenServicio',array('orden'=>$var[$i][orden_servicio_id],'evolucion'=>$var[$i][evolucion_id],'plan'=>$var[$i][plan_id],'tipoid'=>$var[$i][tipo_id_paciente],'paciente'=>$var[$i][paciente_id],'afiliado'=>$var[$i][tipo_afiliado_id],'pos'=>1));
                    $this->salida .= "                <td align=\"center\" ><a href=\"$accion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR POS</a></td>";
                    //$reporte= new GetReports();

                    $accion=ModuloGetURL('app','Admisiones','user','ReporteOrdenServicio',array('orden'=>$var[$i][orden_servicio_id],'evolucion'=>$var[$i][evolucion_id],'plan'=>$var[$i][plan_id],'tipoid'=>$var[$i][tipo_id_paciente],'paciente'=>$var[$i][paciente_id],'afiliado'=>$var[$i][tipo_afiliado_id],'pos'=>0));
                    $this->salida .= "                <td class=$estilo align=\"center\" width=\"7%\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp;<a href=\"$accion\"> IMPRIMIR MEDIA CARTA</a></td>";

                    $mostrar=$reporte->GetJavaReport('app','CentralImpresionHospitalizacion','ordenservicioHTM',array('orden'=>$var[$i][orden_servicio_id]),array('rpt_name'=>'ordenservicioHTM','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
                    $funcion=$reporte->GetJavaFunction();
                    $this->salida .=$mostrar;
                    $this->salida.="                 <td align=\"center\" width=\"43%\"><a href=\"javascript:$funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR</a></td></tr>";
                    //unset($reporte);
                    //$accion1=ModuloGetURL('app','Admisiones','user','ReporteOrdenServicio',array('orden'=>$var[$i][orden_servicio_id],'evolucion'=>$var[$i][evolucion_id],'plan'=>$var[$i][plan_id],'tipoid'=>$var[$i][tipo_id_paciente],'paciente'=>$var[$i][paciente_id],'afiliado'=>$var[$i][tipo_afiliado_id],'pos'=>0));
                    //$this->salida.="                   <td align=\"center\" width=\"43%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR PDF</a></td>";
                    $this->salida .= "            </tr>";
                    $i=$d;
                    $this->salida .= "             </table>";
            }//fin for
    }



    /**
    *
    */
    function FormaRemisionMedica($vector)
    {
            IncludeLib('funciones_admision');
            $this->salida .= ThemeAbrirTabla('REMISION MEDICA');
            $this->Encabezado();
            $arr=DatosRemision($_SESSION['ADMISIONES']['PACIENTE']['ingreso']);
            //mensaje
            $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "  </table>";
            $this->salida .= "       <table border=\"0\" width=\"65%\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
            $this->salida .= "          <tr>";
            $this->salida .= "          <td class=\"modulo_table_list_title\">INSTITUCION QUE REMITE: </td><td class=\"modulo_list_claro\" colspan=\"3\">".$_SESSION['ADMISIONES']['NOMEMPRESA']."</td>";
            $this->salida .= "           </tr>";
            if(!empty($arr[0][traslado_ambulancia]))
            {  $msg='<B> - SE SOLICITA AMBULANCIA</B>';  }
            $this->salida .= "          <tr>";
            $this->salida .= "          <td width=\"20%\" class=\"modulo_table_list_title\">TIPO REMISION: </td><td class=\"modulo_list_claro\" width=\"85%\">".$arr[0][descripcion]." $msg</td>";
            $this->salida .= "          <td width=\"10%\" class=\"modulo_table_list_title\">NIVEL REMISION: </td><td class=\"modulo_list_claro\" width=\"10%\">".$arr[0][nivel_centro_remision]."</td>";
            $this->salida .= "           </tr>";
            $this->salida .= "          <tr>";
            $this->salida .= "          <td class=\"modulo_table_list_title\">DESCRIPCION MOTIVO: </td><td class=\"modulo_list_claro\" colspan=\"3\">".$arr[0][descripcion_otro_motivo]."</td>";
            $this->salida .= "           </tr>";
            $this->salida .= "          <tr>";
            $this->salida .= "          <td class=\"modulo_table_list_title\">OBSERVACIONES: </td><td class=\"modulo_list_claro\" colspan=\"3\">".$arr[0][observaciones]."</td>";
            $this->salida .= "           </tr>";
            $this->salida .= "          <tr>";
            $this->salida .= "          <td class=\"modulo_table_list_title\">MOTIVOS REMISION: </td>";
            $this->salida .= "          <td class=\"modulo_list_claro\" colspan=\"3\">";
            $this->salida .= "       <table border=\"0\" width=\"100%\"align=\"left\" class=\"normal_10\">";
            for($i=0; $i<sizeof($arr); $i++)
            {
                    if(!empty($arr[$i][motivo]))
                    {  $this->salida .= "          <tr class=\"modulo_list_oscuro\"><td>".$arr[$i][motivo]."</td></tr>";  }
            }
            $this->salida .= "           </table>";
            $this->salida .= "          </td>";
            $this->salida .= "           </tr>";
            $this->salida .= "          <tr>";
            $this->salida .= "          <td class=\"modulo_table_list_title\">CENTROS REMISION: </td>";
            $this->salida .= "          <td class=\"modulo_list_claro\" colspan=\"3\">";
            for($i=0; $i<sizeof($arr); $i++)
            {
                    if(!empty($arr[$i][centro]))
                    {
                            $centros[$arr[$i][centro]]=array('centro'=>$arr[$i][centro],'nivel'=>$arr[$i][nivel],'direccion'=>$arr[$i][direccion],'telefono'=>$arr[$i][telefono]);
                    }
            }
            if(!empty($centros))
            {
                    $this->salida .= "       <table border=\"0\" width=\"100%\"align=\"left\" class=\"normal_10\">";
                    $this->salida .= "           <tr class=\"modulo_table_list_title\">";
                    $this->salida .= "          <td width=\"90%\">CENTRO</td>";
                    $this->salida .= "          <td width=\"10%\">NIVEL</td>";
                    $this->salida .= "           </tr>";
                    foreach($centros as $k => $v)
                    {
                                    $this->salida .= "          <tr class=\"modulo_list_oscuro\">";
                                    $this->salida .= "          <td>".$v[centro]." ".$v[direccion]." ".$v[telefono]."</td>";
                                    $this->salida .= "          <td align=\"center\">".$v[nivel]."</td>";
                                    $this->salida .= "          </tr>";
                    }
                    $this->salida .= "           </table>";
            }
            $this->salida .= "          </td>";
            $this->salida .= "           </tr>";
            $accion=MoDuloGetURL('app','Admisiones','user','AccionesRemision',array('evolucion'=>$arr[0][evolucion_id],'ingreso'=>$arr[0][ingreso]));
            $this->salida .= "            <form name=\"forma\" action=\"$accion\" method=\"post\">";
            $this->salida .= "          <tr>";
            $this->salida .= "          <td class=\"modulo_table_list_title\">OBSERVACION REMISION: </td>";
            if($arr[0][sw_remision]==0)
            {  $this->salida .= "          <td class=\"modulo_list_claro\" colspan=\"3\"><textarea cols=\"85\" rows=\"3\" class=\"textarea\"name=\"observacion\">".$_REQUEST['observacion']."</textarea></td>";  }
            else
            {  $this->salida .= "          <td class=\"modulo_list_claro\" colspan=\"3\">".$arr[0][observacion_remision]."</td>";  }
            $this->salida .= "           </tr>";
            $this->salida .= "           </table><br>";
            if($arr[0][sw_remision]==0)
            {
                    $this->salida .= "                <table width=\"70%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
                    $this->salida .= "                     <tr>";
                    $this->salida .= "                        <td align=\"center\" class=\"modulo_table_list_title\" colspan=\"4\">INSTITUCIONES A REMITIR: </td>";
                    $this->salida .= "                     </tr>";
                    $this->salida .= "                     <tr>";
                    $this->salida .= "                        <td align=\"center\" colspan=\"4\" class=\"modulo_list_oscuro\">";
                    if(!empty($_SESSION['ADMISIONES']['CENTROS']))
                    {
                            $this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"65%\">";
                            $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                            $this->salida.="  <td width=\"90%\">INSTITUCION</td>";
                            $this->salida.="  <td width=\"6%\">NIVEL</td>";
                            $this->salida.="  <td width=\"6%\"></td>";
                            $this->salida.="</tr>";
                            foreach($_SESSION['ADMISIONES']['CENTROS'] as $k => $v)
                            {
                                foreach($v as $k1 => $v1)
                                {
                                        foreach($v1 as $k2 => $v2)
                                        {
                                                $this->salida.="<tr class=\"modulo_list_claro\">";
                                                $this->salida.="  <td>".$k1."</td>";
                                                $this->salida.="  <td align=\"center\">".$k2."</td>";
                                                $this->salida.="  <input type = hidden name=Rem".$k." value = ".$k."></td>";
                                                $accion=ModuloGetURL('app','Admisiones','user','EliminarCentro',array('codigoEC'=>$k,'observacion'=>$_REQUEST['observacion']));
                                                $this->salida.="  <td><a href='$accion'><img src=\"".GetThemePath()."/images/elimina.png\"  border='0'></a></td>";
                                                $this->salida.="</tr>";
                                        }
                                }
                            }
                            $this->salida.="</table><br>";
                    }
                    $this->salida.="<table  align=\"center\" border=\"0\"  width=\"75%\">";
                    $this->salida.="<tr class=\"modulo_table_title\">";
                    $this->salida.="  <td align=\"center\" colspan=\"7\">CENTROS DE REMISION</td>";
                    $this->salida.="</tr>";
                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida.="<td width=\"5%\">TIPO</td>";
                    $this->salida.="<td width=\"10%\" align = left >";
                    $this->salida.="<select  name = 'criterio'  class =\"select\">";
                    $this->salida .= " <option value=\"Todas\">TODOS LOS NIVELES</option>";
                    $nivel=$this->Niveles();
                    for($i=0; $i<sizeof($nivel); $i++)
                    {
                            if($nivel[$i][nivel]==$_REQUEST['criterio'])
                            {  $this->salida .=" <option value=\"".$nivel[$i][nivel]."\" selected>INSTITUCION ".$nivel[$i][descripcion]."</option>";  }
                            else
                            {  $this->salida .=" <option value=\"".$nivel[$i][nivel]."\">INSTITUCION ".$nivel[$i][descripcion]."</option>";  }
                    }
                    $this->salida.="</select>";
                    $this->salida.="</td>";
                    $this->salida.="<td width=\"6%\">CODIGO:</td>";
                    $this->salida .="<td width=\"11%\" align='center'><input type='text' size =10 class='input-text' size = 10 maxlength = 10   name = 'codigo' value=\"".$_REQUEST[codigo]."\"></td>" ;
                    $this->salida.="<td width=\"10%\">DESCRIPCION:</td>";
                    $this->salida .="<td width=\"25%\" align='center'><input type='text' class='input-text'     name = 'descripcion' value=\"".$_REQUEST[descripcion]."\"></td>" ;
                    $this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name=\"Buscar\" type=\"submit\" value=\"BUSCAR\"></td>";
                    $this->salida.="</tr>";
                    $this->salida.="</table><br>";
                    if(!empty($vector))
                    {
                        $this->FormaResultados($vector);
                    }
                    $this->salida .= "                     </td>";
                    $this->salida .= "                     </tr>";
                    $this->salida .= "               </table><br>";
            }
            $this->salida .= "       <br><table border=\"0\" width=\"40%\" align=\"center\">";
            $this->salida .= "          <tr align=\"center\">";
            if($arr[0][sw_remision]==0)
            {  $this->salida .= "                      <td align=\"center\"><form name=\"formabuscar\" action=\"$accion\" method=\"post\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"GUARDAR\"></form></td>";  }
            else
            {
                    $reporte= new GetReports();
                    $mostrar=$reporte->GetJavaReport('app','Admisiones','remision',array('ingreso'=>$_SESSION['ADMISIONES']['PACIENTE']['ingreso'],'empresa'=>$_SESSION['ADMISIONES']['NOMEMPRESA']),array('rpt_name'=>'remision','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
                    $funcion=$reporte->GetJavaFunction();
                    $this->salida .=$mostrar;
                    $this->salida .= "                     <td align=\"center\"><input class=\"input-submit\" type=\"button\" name=\"Imprimir\" value=\"IMPRIMIR\" onclick=\"javascript:$funcion\"></form></td>";
                    unset($reporte);
            }
            $this->salida .= "</form>";
            if(empty($_SESSION['ADMISIONES']['REMISION']['RETORNO']))
            {  $accion=ModuloGetURL('app','Admisiones','user','BuscarPaciente',array('TipoDocumento'=>$_SESSION['ADMISIONES']['BUSQUEDA']['tipo_id_paciente'],'Documento'=>$_SESSION['ADMISIONES']['BUSQUEDA']['paciente_id'],'Nombres'=>$_SESSION['ADMISIONES']['BUSQUEDA']['Nombres'],'prefijo'=>$_SESSION['ADMISIONES']['BUSQUEDA']['prefijo'],'historia'=>$_SESSION['ADMISIONES']['BUSQUEDA']['historia']));  }
            else
            {
                    $contenedor=$_SESSION['ADMISIONES']['REMISION']['RETORNO']['contenedor'];
                    $modulo=$_SESSION['ADMISIONES']['REMISION']['RETORNO']['modulo'];
                    $tipo=$_SESSION['ADMISIONES']['REMISION']['RETORNO']['tipo'];
                    $metodo=$_SESSION['ADMISIONES']['REMISION']['RETORNO']['metodo'];
                    $argumentos=$_SESSION['ADMISIONES']['REMISION']['RETORNO']['argumentos'];
                    $accion=ModuloGetURL($contenedor,$modulo,$tipo,$metodo,$argumentos);
            }
            $this->salida .= "                     <td align=\"center\"><form name=\"formabuscar\" action=\"$accion\" method=\"post\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"VOLVER\"></form></td>";
            $this->salida .= "           </tr>";
            $this->salida .= "           </table>";
            $this->salida .= ThemeCerrarTabla();
            return true;
    }

    /**
    *
    */
    function FormaResultados($arr)
    {
            if ($arr)
            {
                    $this->salida.="<table  align=\"center\" border=\"0\"  width=\"70%\">";
                    $this->salida.="<tr class=\"modulo_table_title\">";
                    $this->salida.="  <td align=\"center\" colspan=\"3\">RESULTADO DE LA BUSQUEDA</td>";
                    $this->salida.="</tr>";
                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida.="  <td width=\"30%\">INSTITUCION</td>";
                    $this->salida.="  <td width=\"10%\">NIVEL</td>";
                    $this->salida.="  <td width=\"5%\"></td>";
                    $this->salida.="</tr>";
                    for($i=0;$i<sizeof($arr);$i++)
                    {
                            $this->salida.="<tr class=\"modulo_list_claro\">";
                            $this->salida.="  <td>".$arr[$i][descripcion]."</td>";
                            $this->salida.="  <td align=\"center\">".$arr[$i][nivel]."</td>";
                            $this->salida.="  <td align=\"center\"><input type = checkbox name=centro".$arr[$i][centro_remision]." value =\"".$arr[$i][centro_remision]."||".$arr[$i][descripcion]."||".$arr[$i][nivel]."||".$arr[$i][telefono]."||".$arr[$i][direccion]."\"></td>";
                            $this->salida.="</tr>";
                    }
                    $this->salida.="<tr class=\"modulo_list_claro\">";
                    $this->salida .= "<td align=\"right\" colspan=\"3\"><input class=\"input-submit\" name=\"Guardar\" type=\"submit\" value=\"GUARDAR\"></td>";
                    $this->salida.="</tr>";
                    $this->salida.="</table><br>";
                    $this->salida .=$this->RetornarBarra();
            }
    }


/**
    *
    */
    function RetornarBarra()
    {
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

      $accion=ModuloGetURL('app','Admisiones','user','Busqueda',$vec);
    $barra=$this->CalcularBarra($paso);
    $numpasos=$this->CalcularNumeroPasos($this->conteo);
    $colspan=1;

    $this->salida .= "<br><table border='1' align='center'  cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
    if($paso > 1){
      $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
      $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
      $colspan+=1;
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
       // $this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
        //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
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
      $this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>P�ina $paso de $numpasos</td><tr></table><br>";
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
    $this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>P�ina $paso de $numpasos</td><tr></table><br>";
    }
    }

    /**
    *
    */
    function CalcularNumeroPasos($conteo)
    {
        $numpaso=ceil($conteo/$this->limit);
        return $numpaso;
    }

    /**
    *
    */
    function CalcularBarra($paso)
    {
        $barra=floor($paso/10)*10;
        if(($paso%10)==0)
        {
            $barra=$barra-10;
        }
        return $barra;
    }

    /**
    *
    */
    function CalcularOffset($paso)
    {
        $offset=($paso*$this->limit)-$this->limit;
        return $offset;
    }



    /**
    *
    */
    function FormaSalidaPaciente()
    {
            $this->salida .= ThemeAbrirTabla('IMPRESION SALIDA PACIENTE');

            $this->salida .= "       <table border=\"0\" width=\"50%\" align=\"center\">";
            $this->salida .= "          <tr align=\"center\" class=\"modulo_table_list_title\"><td colspan=\"4\">SALIDA DE PACIENTE</td></tr>";
            $this->salida .= "          <tr align=\"center\">";
            $this->salida .= "              <td class=\"modulo_table_list_title\">IDENTIFICACION: </td><td class=\"modulo_list_claro\">".$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente']." ".$_SESSION['ADMISIONES']['PACIENTE']['paciente_id']."</td>";
            $this->salida .= "              <td class=\"modulo_table_list_title\">PACIENTE: </td><td class=\"modulo_list_claro\">".$_SESSION['ADMISIONES']['PACIENTE']['nombre']."</td>";
            $this->salida .= "           </tr>";
            $this->salida .= "          <tr align=\"left\"><br><td colspan=\"4\">______________________</td></tr>";

            $this->salida .= "           </table>";

            $this->salida .= "       <br><table border=\"0\" width=\"50%\" align=\"center\">";
            $this->salida .= "          <tr align=\"center\">";
            $accion=ModuloGetURL('app','Admisiones','user','',array('TipoDocumento'=>$_SESSION['ADMISIONES']['BUSQUEDA']['tipo_id_paciente'],'Documento'=>$_SESSION['ADMISIONES']['BUSQUEDA']['paciente_id'],'Nombres'=>$_SESSION['ADMISIONES']['BUSQUEDA']['Nombres'],'prefijo'=>$_SESSION['ADMISIONES']['BUSQUEDA']['prefijo'],'historia'=>$_SESSION['ADMISIONES']['BUSQUEDA']['historia']));
            $reporte= new GetReports();
            $mostrar=$reporte->GetJavaReport('app','SalidaPacientes','salida',array('tipo_id_paciente'=>$_SESSION['ADMISIONES']['BUSQUEDA']['tipo_id_paciente'],'paciente_id'=>$_SESSION['ADMISIONES']['BUSQUEDA']['paciente_id'],'nombre'=>$_SESSION['ADMISIONES']['BUSQUEDA']['Nombres']),array('rpt_name'=>'salida','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
            $funcion=$reporte->GetJavaFunction();
            $this->salida .=$mostrar;
            $this->salida .= "                     <td align=\"center\"><input class=\"input-submit\" type=\"button\" name=\"Imprimir\" value=\"IMPRIMIR\" onclick=\"javascript:$funcion\"></form></td>";
            unset($reporte);
            $accion=ModuloGetURL('app','Admisiones','user','BuscarPaciente',array('TipoDocumento'=>$_SESSION['ADMISIONES']['BUSQUEDA']['tipo_id_paciente'],'Documento'=>$_SESSION['ADMISIONES']['BUSQUEDA']['paciente_id'],'Nombres'=>$_SESSION['ADMISIONES']['BUSQUEDA']['Nombres'],'prefijo'=>$_SESSION['ADMISIONES']['BUSQUEDA']['prefijo'],'historia'=>$_SESSION['ADMISIONES']['BUSQUEDA']['historia']));
            $this->salida .= "                     <td align=\"center\"><form name=\"formabuscar\" action=\"$accion\" method=\"post\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"VOLVER\"></form></td>";
            $this->salida .= "           </tr>";
            $this->salida .= "           </table>";
            $this->salida .= ThemeCerrarTabla();
            return true;
    }

    /**
    *
    */
    function Encabezado()
    {
                $this->salida.="<table  align=\"center\" border=\"0\"  width=\"60%\" class=\"modulo_table_list_title\">";
                $this->salida.="<tr>";
                $this->salida.="<td width=\"10%\">IDENTIFICACION: </td><td align=\"left\" class=\"modulo_list_claro\">".$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente']." ".$_SESSION['ADMISIONES']['PACIENTE']['paciente_id']."</td>";
                $this->salida.="<td width=\"10%\">PACIENTE: </td><td align=\"left\" class=\"modulo_list_claro\">".$_SESSION['ADMISIONES']['PACIENTE']['nombre']."</td>";
                $this->salida.="</tr>";
                $this->salida.="</table><br>";
    }


    //***********************FUNCIONES CLAUDIA

    function FrmIncapacidad($vector1,$rep)
    {
            $this->salida .= "<form name=\"formades\" action=\"$accion\" method=\"post\">";
            if($vector1)
        {
            $this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"80%\">";
            $this->salida.="<tr class=\"modulo_table_title\">";
            $this->salida.="  <td align=\"center\" colspan=\"5\">INCAPACIDADES MEDICAS GENERADAS</td>";

            for($i=0;$i<sizeof($vector1);$i++)
            {
                if( $i % 2){ $estilo='modulo_list_claro';}
                else {$estilo='modulo_list_oscuro';}
                $this->salida.="</tr>";
                $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                $this->salida.="  <td width=\"5%\">No. EVOLUCION</td>";
                $this->salida.="  <td width=\"45%\">OBSERVACION DE LA INCAPACIDAD</td>";
                $this->salida.="  <td width=\"10%\">TIPO DE INCAPACIDAD</td>";
                $this->salida.="  <td width=\"10%\">DIAS DE INCAPACIDAD</td>";
                $this->salida.="  <td width=\"10%\">FECHA DE EMISION</td>";
                $this->salida.="</tr>";
                $this->salida.="<tr class=\"$estilo\">";
                $this->salida.="  <td align=\"center\" width=\"5%\">".$vector1[$i][evolucion_id]."</td>";
                $this->salida.="  <td align=\"left\" width=\"45%\">".$vector1[$i][observacion_incapacidad]."</td>";
                $this->salida.="  <td align=\"center\" width=\"10%\">".$vector1[$i][tipo_incapacidad_descripcion]."</td>";
                $this->salida.="  <td align=\"center\" width=\"10%\">".$vector1[$i][dias_de_incapacidad]."</td>";
                $a = $this->FechaStamp($vector1[$i][fecha]);
                $b = $this->HoraStamp($vector1[$i][fecha]);
                $fecha = $a.' - '.$b;
                $this->salida.="  <td align=\"left\" width=\"10%\">".$fecha."</td>";
                $this->salida.="</tr>";
                $this->salida.="<tr class=\"$estilo\">";

        //impresion pos
                $accion1=ModuloGetURL('app','Central_de_Autorizaciones','user','ReporteIncapacidadMedica',array('evolucion'=>$vector1[$i][evolucion_id], 'modulo_invoca'=>'salida_pacientes', 'parametro_retorno'=>'1', 'impresion_pos'=>'1'));
        $this->salida.="  <td colspan = 2 align=\"center\" width=\"63%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR POS</a></td>";

                //impresion html y pdf
                $mostrar=$rep->GetJavaReport('app','CentralImpresionHospitalizacion','incapacidad_html',array('evolucion_id'=>$vector1[$i][evolucion_id]),array('rpt_name'=>'incapacidad_html','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
                $nombre_funcion=$rep->GetJavaFunction();
                $this->salida .=$mostrar;
                $this->salida.="<td colspan = 2 align=\"center\" width=\"43%\"><a href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR PDF</a></td>";
                //fin de alex

                //impresion en media carta
                $accion2=ModuloGetURL('app','Central_de_Autorizaciones','user','ReporteIncapacidadMedica',array('evolucion'=>$vector1[$i][evolucion_id], 'modulo_invoca'=>'salida_pacientes', 'parametro_retorno'=>'1'));
        $this->salida.="  <td colspan = 1 align=\"center\" width=\"20%\"><a href='$accion2'><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR MEDIA CARTA</a></td>";

              $this->salida.="</tr>";
            }
            $this->salida.="</table><br>";
        }
        $this->salida .= "</form>";
        return true;
    }

     function FrmMedicamentos($vector1, $reporte, $ingreso, $evolucion, $tipo_formulacion)
     {
          $this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";

          if ($tipo_formulacion == "amb")
          { $tipo_medicamentos = "MEDICAMENTOS AMBULATORIOS"; }
          else
          { $tipo_medicamentos = "MEDICAMENTOS Y/O SOLUCIONES HOSPITALARIAS"; }
          
          $this->salida.="<table align=\"center\" border=\"0\" class=\"hc_table_submodulo_list_title\" width=\"80%\">";
          $this->salida.="<tr class=\"modulo_table_title\">";
          $this->salida.="  <td>".$tipo_medicamentos."</td>";
          $this->salida.="</tr>";

          //Variables que me indican si en la formulacion hubo med. No Pos o Controlados.
          $_SESSION['MED_NOPOS'] = 0;
          $_SESSION['MED_CONTROL'] = 0;
          
          $this->salida.="<tr class=\"modulo_list_claro\">";
          $this->salida.="  <td width=\"40%\">";
          
          $this->salida.="    <table  align=\"center\" border=\"0\"  width=\"100%\">";
          
          $this->salida.= $this->Pintar_FormulacionConsultada($vector1, $tipo_formulacion);
          
          $this->salida.="    </table>";
         
          $this->salida.="  </td>";
          $this->salida.="</tr>";
          
          $this->salida.="</table>";
          
          // Enlaces de Impresion de medicamentos en Papel.
          $this->salida.="<table align=\"center\" border=\"0\" class=\"hc_table_submodulo_list_title\" width=\"80%\">";
          $this->salida.="<tr class=\"modulo_list_claro\">";
          
          if ($tipo_formulacion == 'amb')
          {
               $modulo = 'Central_de_Autorizaciones';
               $nombre_reporte = 'formula_medica_html';
          }
          elseif ($tipo_formulacion == 'hosp')
          {
               $modulo = 'ImpresionHC';
               $nombre_reporte = 'formula_medica_hosp_html';
          }

          if(!$evolucion)
          { $evolucion = $vector1[0][0]['evolucion_id']; }
          
          //reporte pos
     	$accion1=ModuloGetURL('app',$modulo,'user','ReporteFormulaMedica',array('tipo_id_paciente'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'], 'paciente_id'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id'], 'ingreso'=>$ingreso, 'evolucion_id'=>$evolucion, 'modulo_invoca'=>'Admisiones', 'parametro_retorno'=>'1','impresion_pos'=>'1'));
          $this->salida.="  <td align=\"center\" width=\"33%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR POS</a></td>";

          //reporte pdf y html
          $mostrar=$reporte->GetJavaReport('app',$modulo,$nombre_reporte,array('tipo_id_paciente'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'],'paciente_id'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id'], 'ingreso'=>$ingreso, 'evolucion_id'=>$evolucion, 'modulo_invoca'=>'Admisiones'),array('rpt_name'=>'formulamedica','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
          $nombre_funcion=$reporte->GetJavaFunction();
          $this->salida .=$mostrar;
          $this->salida.="<td align=\"center\" width=\"33%\"><a href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR PDF</a></td>";

          //reporte media carta
          $accion2=ModuloGetURL('app',$modulo,'user','ReporteFormulaMedica',array('tipo_id_paciente'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'], 'paciente_id'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id'], 'ingreso'=>$ingreso, 'evolucion_id'=>$evolucion, 'modulo_invoca'=>'Admisiones', 'parametro_retorno'=>'1'));
          $this->salida.="  <td align=\"center\" width=\"33%\"><a href='$accion2'><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>IMPRIMIR MEDIA CARTA</a></td>";

          $this->salida.="</tr>";
          
          if($_SESSION['MED_NOPOS'] == 1 OR $_SESSION['MED_CONTROL'] == 1)
          {
          	$this->salida.="<tr class=\"modulo_list_oscuro\">";
               $accionJava = "javascript:MostrarCapa('MedicamentosEspeciales');IniciarCapaMed('IMPRESION DE MEDICAMENTOS ESPECIALES','MedicamentosEspeciales');CargarContenedor('MedicamentosEspeciales');";
			$this->salida.="<td align=\"center\" colspan=\"3\"><a href=\"$accionJava\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>IMPRIMIR MEDICAMENTOS ESPECIALES</a></td>";          
               $this->salida.="</tr>";
		}
                    
          $this->salida.="</table>";    
     	
          //Capa de Impresion               
          $this->salida.="<div id='MedicamentosEspeciales' class='d2Container' style=\"display:none\"><br>";
          $this->salida .= "    <div id='titulo' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
          $this->salida .= "    <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('MedicamentosEspeciales');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
          $this->salida .= "    <div id='error' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
          $this->salida .= "    <div id='ContenidoMedicamentosEspeciales' class='d2Content' style=\"height:250\">\n";
          
          $this->salida.="		<table align=\"center\" border=\"0\" class=\"hc_table_submodulo_list_title\" width=\"100%\">";
          $this->salida .= "            <tr class=\"modulo_table_list_title\">\n";
          $this->salida .= "                 <td colspan=\"3\">MEDICAMENTOS DE USO CONTROLADO</td>\n";
          $this->salida .= "            </tr>\n";
          foreach($vector1 as $k => $vectorM)
          {
               for($i=0;$i<sizeof($vectorM);$i++)
               {
                    if($vectorM[$i]['sw_uso_controlado'] == '1')
                    {
                         $this->salida.="		<tr class=\"modulo_list_claro\">";
                         $this->salida.="			<td colspan=\"3\" align=\"left\"><B>".$vectorM[$i]['producto']."</B> - ( ".$vectorM[$i]['codigo_producto']." - ";
                         if(empty($vectorM[$i]['codigo_pos']))
                         {
                              $this->salida.="".$vectorM[$i]['item']." )";
                         }else{
                              $this->salida.="".$vectorM[$i]['codigo_pos']." )";
                         }
                         if($vectorM[$i]['sw_uso_controlado'] == 1)
                         {
                              $this->salida.="&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/endturn.png\" border='0' width=\"15\" title=\"Medicamento de Uso Controlado\">";
                         }
                         $this->salida.="				</td>";
                         $this->salida.="		</tr>";

                         $this->salida.="		<tr class=\"modulo_list_claro\">";
                         if ($tipo_formulacion == 'amb')
                         {
                              $modulo = 'Central_de_Autorizaciones';
                              $nombre_reporte = 'formula_medica_html';
                         }
                         elseif ($tipo_formulacion == 'hosp')
                         {
                              $modulo = 'ImpresionHC';
                              $nombre_reporte = 'formula_medica_hosp_html';
                         }
                         
                         //reporte pos
                         $accion1=ModuloGetURL('app',$modulo,'user','ReporteFormulaMedica',array('tipo_id_paciente'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'], 'paciente_id'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id'], 'ingreso'=>$ingreso, 'evolucion_id'=>$evolucion, 'rango'=>'uso_controlado', 'codigo_producto'=>$vectorM[$i]['codigo_producto'], 'soluciones'=>'1', 'modulo_invoca'=>'Admisiones', 'parametro_retorno'=>'1','impresion_pos'=>'1'));
                         $this->salida.="  				<td align=\"center\" width=\"33%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR POS</a></td>";
               
                         //reporte pdf y html
                         $mostrar=$reporte->GetJavaReport('app',$modulo,$nombre_reporte,array('tipo_id_paciente'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'], 'paciente_id'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id'], 'ingreso'=>$ingreso, 'evolucion_id'=>$evolucion, 'rango'=>'uso_controlado', 'codigo_producto'=>$vectorM[$i]['codigo_producto'], 'modulo_invoca'=>'Admisiones'),array('rpt_name'=>'formulamedica','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
                         $nombre_funcion=$reporte->GetJavaFunction();
                         $this->salida .=$mostrar;
                         $this->salida.="				<td align=\"center\" width=\"33%\"><a href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR PDF</a></td>";
               
                         //reporte media carta
                         $accion2=ModuloGetURL('app',$modulo,'user','ReporteFormulaMedica',array('tipo_id_paciente'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'], 'paciente_id'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id'], 'ingreso'=>$ingreso, 'evolucion_id'=>$evolucion, 'rango'=>'uso_controlado', 'codigo_producto'=>$vectorM[$i]['codigo_producto'], 'soluciones'=>'1', 'modulo_invoca'=>'Admisiones', 'parametro_retorno'=>'1'));
                         $this->salida.="  				<td align=\"center\" width=\"33%\"><a href='$accion2'><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>IMPRIMIR MEDIA CARTA</a></td>";
                         $this->salida.="		</tr>";
                    }
               }
          }
          $this->salida.="		</table><br><br>";
          
          $this->salida.="		<table align=\"center\" border=\"0\" class=\"hc_table_submodulo_list_title\" width=\"100%\">";
          $this->salida .= "            <tr class=\"modulo_table_list_title\">\n";
          $this->salida .= "                 <td colspan=\"3\">MEDICAMENTOS NO POS</td>\n";
          $this->salida .= "            </tr>\n";

          foreach($vector1 as $k => $vectorM)
          {
          
               for($i=0;$i<sizeof($vectorM);$i++)
               {
                    if($vectorM[$i]['item'] == 'NO POS')
                    {
                         $this->salida.="		<tr class=\"modulo_list_oscuro\">";
                         $this->salida.="			<td colspan=\"3\" align=\"left\"><B>".$vectorM[$i]['producto']."</B> - ( ".$vectorM[$i]['codigo_producto']." - ";
                         if(empty($vectorM[$i]['codigo_pos']))
                         {
                              $this->salida.="".$vectorM[$i]['item']." )";
                         }else{
                              $this->salida.="".$vectorM[$i]['codigo_pos']." )";
                         }
                         if($vectorM[$i]['sw_uso_controlado'] == 1)
                         {
                              $this->salida.="&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/endturn.png\" border='0' width=\"15\" title=\"Medicamento de Uso Controlado\">";
                         }
                         $this->salida.="				</td>";
                         $this->salida.="		</tr>";

                         $this->salida.="			<tr class=\"modulo_list_oscuro\">";
                         if ($tipo_formulacion == 'amb')
                         {
                              $modulo = 'Central_de_Autorizaciones';
                              $nombre_reporte = 'formula_medica_html';
                         }
                         elseif ($tipo_formulacion == 'hosp')
                         {
                              $modulo = 'ImpresionHC';
                              $nombre_reporte = 'formula_medica_hosp_html';
                         }
                         
                         //reporte pos
                         $accion1=ModuloGetURL('app',$modulo,'user','ReporteFormulaMedica',array('tipo_id_paciente'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'], 'paciente_id'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id'], 'ingreso'=>$ingreso, 'evolucion_id'=>$evolucion, 'rango'=>'no_pos', 'codigo_producto'=>$vectorM[$i]['codigo_producto'], 'soluciones'=>'1', 'modulo_invoca'=>'Admisiones', 'parametro_retorno'=>'1','impresion_pos'=>'1'));
                         $this->salida.="  				<td align=\"center\" width=\"33%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR POS</a></td>";
               
                         //reporte pdf y html
                         $mostrar=$reporte->GetJavaReport('app',$modulo,$nombre_reporte,array('tipo_id_paciente'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'], 'paciente_id'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id'], 'ingreso'=>$ingreso, 'evolucion_id'=>$evolucion, 'rango'=>'no_pos', 'codigo_producto'=>$vectorM[$i]['codigo_producto'], 'modulo_invoca'=>'Admisiones'),array('rpt_name'=>'formulamedica','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
                         $nombre_funcion=$reporte->GetJavaFunction();
                         $this->salida .=$mostrar;
                         $this->salida.="				<td align=\"center\" width=\"33%\"><a href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR PDF</a></td>";
               
                         //reporte media carta
                         $accion2=ModuloGetURL('app',$modulo,'user','ReporteFormulaMedica',array('tipo_id_paciente'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'], 'paciente_id'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id'], 'ingreso'=>$ingreso, 'evolucion_id'=>$evolucion, 'rango'=>'no_pos', 'codigo_producto'=>$vectorM[$i]['codigo_producto'], 'soluciones'=>'1', 'modulo_invoca'=>'Admisiones', 'parametro_retorno'=>'1'));
                         $this->salida.="  				<td align=\"center\" width=\"33%\"><a href='$accion2'><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>IMPRIMIR MEDIA CARTA</a></td>";
                         $this->salida.="			</tr>";
                    }
               }
          }
          $this->salida.="		</table>";

          $this->salida.="	</div>\n";     
          $this->salida.="</div>";

          
          $javaC = "<script>\n";
          $javaC .= "   var contenedor\n";
          
          $javaC .= "   function CargarContenedor(Elemento)\n";
          $javaC .= "   {\n";
          $javaC .= "        contenedor = Elemento;\n";
          $javaC .= "   }\n";

          $javaC .= "   var titulo = 'titulo';\n";
          $javaC .= "   var hiZ = 2;\n";
          
          $javaC .= "   function IniciarCapaMed(tit, Elemento)\n";
          $javaC .= "   {\n";
          $javaC .= "	   Capa = xGetElementById(Elemento);\n";
          $javaC .= "	   xResizeTo(Capa, 500, 'auto');\n";
          $javaC .= "       document.getElementById('titulo').innerHTML = '<center>'+tit+'</center>';\n";
          $javaC .= "       document.getElementById('error').innerHTML = '';\n";
          $javaC .= "       Capa = xGetElementById(Elemento);\n";
          $javaC .= "       xMoveTo(Capa, xClientWidth()/4, xScrollTop()+24);\n";
          $javaC .= "       ele = xGetElementById('titulo');\n";
          $javaC .= "       xResizeTo(ele, 480, 20);\n";
          $javaC .= "       xMoveTo(ele, 0, 0);\n";
          $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
          $javaC .= "       ele = xGetElementById('cerrar');\n";
          $javaC .= "       xResizeTo(ele,20, 20);\n";
          $javaC .= "       xMoveTo(ele, 480, 0);\n";
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
          
          $javaC.= "</script>\n";
          $this->salida.= $javaC;
          
          return true;
     }// Fin FrmMedicamentos

     
     /*
     * Forma que permite dibujar la consulta de los medicamentos.
     *
     * @autor Tizziano Perea
     */
	function Pintar_FormulacionConsultada($vectorOriginal, $tipo_formulacion)
     {
          foreach($vectorOriginal as $k => $vector1)
          {
               for($i=0;$i<sizeof($vector1);$i++)
               {
               	// Activo variable de uso controlado.
                    if($vector1[$i]['sw_uso_controlado'] == '1')
                    { $_SESSION['MED_CONTROL'] = 1; }
                    
                    // Activo variable de Med. No Pos.
                    if($vector1[$i]['item'] == 'NO POS' OR $vector1[$i]['codigo_pos'] == 'NO POS')
                    { $_SESSION['MED_NOPOS'] = 1; }
                    
                    if($vector1[$i]['tipo_solicitud'] == "M")
                    { $estilo = 'modulo_list_oscuro'; }else
                    { $estilo = 'modulo_list_claro'; }
                    
                    $salida.="<tr>";
                    
                    if($vector1[$i]['tipo_solicitud'] == "M")
                    { 
                    	$salida.="<td width=\"40%\" colspan=\"3\" class=\"modulo_list_claro\"><B>".$vector1[$i]['producto']."</B> - ( ".$vector1[$i]['codigo_producto']." - ";
                         if(empty($vector1[$i]['codigo_pos']))
                         {
                         	$salida.="".$vector1[$i]['item']." )";
                         }else{
                         	$salida.="".$vector1[$i]['codigo_pos']." )";
                         }
                         if($vector1[$i]['sw_uso_controlado'] == 1)
                         {
                         	$salida.="&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/endturn.png\" border='0' width=\"15\" title=\"Medicamento de Uso Controlado\">";
                         }
                         $salida.="</td>";
                    }
                    else
                    {
                         if($vector1[$i]['num_mezcla'] != $vector1[$i-1]['num_mezcla'])
                         {
                              $salida.="<td width=\"40%\" colspan=\"3\" class=\"modulo_list_oscuro\">";
                              for($j=0; $j<sizeof($vector1); $j++)
                              {
                                   if($vector1[$i]['num_mezcla'] == $vector1[$j]['num_mezcla'])
                                   {
                                        $salida.="<B>".$vector1[$j]['producto']."</B> - ( ".$vector1[$j]['codigo_producto']." - <label class=\"label_mark\">".floor($vector1[$j]['cantidad_producto'])." ".$vector1[$j]['unidad_suministro']."</label>)<br>";
                                   }
                              }
                              $salida.="</td>";
                         }
                    }
    
                    if($vector1[$i]['tipo_solicitud'] == "M")
                    {
                         $salida.="<tr class=\"$estilo\">";
                         $salida.="<td colspan=\"6\">";
                         $salida.="<table>";
                         
                         $salida.="<tr class=\"$estilo\">";
                         $salida.="<td colspan = 3 align=\"left\" width=\"9%\"><b>Via de Administracion:</b> ".$vector1[$i][via]."</td>";
                         $salida.="</tr>";
     
                         $salida.="<tr class=\"$estilo\">";
                         $salida.="<td align=\"left\" width=\"9%\"><b>Dosis:</b></td>";
                         $e=$vector1[$i][dosis]/floor($vector1[$i][dosis]);
                         if($e==1)
                         {
                              $salida.="  <td align=\"left\" width=\"10%\">".floor($vector1[$i][dosis])."  ".$vector1[$i][unidad_dosificacion]."</td>";
                         }
                         else
                         {
                              $salida.="  <td align=\"left\" width=\"10%\">".$vector1[$i][dosis]."  ".$vector1[$i][unidad_dosificacion]."</td>";
                         }
                         
                         if($tipo_formulacion == "amb")
                         {
						$vector_posologia = Consulta_Solicitud_Medicamentos_Posologia($vector1[$i][codigo_producto], $vector1[$i][tipo_opcion_posologia_id], $vector1[$i][evolucion_id]);             
                              
                              //pintar formula para opcion 1
                              if($vector1[$i][tipo_opcion_posologia_id]== 1)
                              {
                                   $salida.="  <td align=\"left\" width=\"20%\">cada ".$vector_posologia[0][periocidad_id]." ".$vector_posologia[0][tiempo]."</td>";
                              }
                              //pintar formula para opcion 2
                              if($vector1[$i][tipo_opcion_posologia_id]== 2)
                              {
                                   $salida.="  <td align=\"left\" width=\"20%\">".$vector_posologia[0][descripcion]."</td>";
                              }
                              //pintar formula para opcion 3
                              if($vector1[$i][tipo_opcion_posologia_id]== 3)
                              {
                                   $momento = '';
                                   if($vector_posologia[0][sw_estado_momento]== '1')
                                   {
                                        $momento = 'antes de ';
                                   }
                                   else
                                   {
                                        if($vector_posologia[0][sw_estado_momento]== '2')
                                        {
                                             $momento = 'durante ';
                                        }
                                        else
                                        {
                                             if($vector_posologia[0][sw_estado_momento]== '3')
                                             {
                                                       $momento = 'despues de ';
                                             }
                                        }
                                   }
                                   $Cen = $Alm = $Des= '';
                                   $cont= 0;
                                   $conector = '  ';
                                   $conector1 = '  ';
                                   if($vector_posologia[0][sw_estado_desayuno]== '1')
                                   {
                                        $Des = $momento.'el Desayuno';
                                        $cont++;
                                   }
                                   if($vector_posologia[0][sw_estado_almuerzo]== '1')
                                   {
                                        $Alm = $momento.'el Almuerzo';
                                        $cont++;
                                   }
                                   if($vector_posologia[0][sw_estado_cena]== '1')
                                   {
                                        $Cen = $momento.'la Cena';
                                        $cont++;
                                   }
                                   if ($cont== 2)
                                   {
                                        $conector = ' y ';
                                        $conector1 = '  ';
                                   }
                                   if ($cont== 1)
                                   {
                                        $conector = '  ';
                                        $conector1 = '  ';
                                   }
                                   if ($cont== 3)
                                   {
                                        $conector = ' , ';
                                        $conector1 = ' y ';
                                   }
                                   $salida.="  <td align=\"left\" width=\"20%\">".$Des."".$conector."".$Alm."".$conector1."".$Cen."</td>";
                              }
                              //pintar formula para opcion 4
                              if($vector1[$i][tipo_opcion_posologia_id]== 4)
                              {
                                   $conector = '  ';
                                   $frecuencia='';
                                   $j=0;
                                   foreach ($vector_posologia as $k => $v)
                                   {
                                        if ($j+1 ==sizeof($vector_posologia))
                                        {
                                             $conector = '  ';
                                        }
                                        else
                                        {
                                             if ($j+2 ==sizeof($vector_posologia))
                                             {
                                                  $conector = ' y ';
                                             }
                                             else
                                             {
                                                  $conector = ' - ';
                                             }
                                        }
                                        $frecuencia = $frecuencia.$k.$conector;
                                        $j++;
                                   }
                                   $salida.="  <td align=\"left\" width=\"20%\">a la(s): $frecuencia</td>";
                              }
                              //pintar formula para opcion 5
                              if($vector1[$i][tipo_opcion_posologia_id]== 5)
                              {
                                   $salida.="  <td align=\"left\" width=\"20%\">".$vector_posologia[0][frecuencia_suministro]."</td>";
                              }

                         }else
                         {
                         	$salida.="<td align=\"left\" width=\"20%\">".$vector1[$i][frecuencia]."</td>";                         
                         }

                         $salida.="</tr>";
          
                         $salida.="<tr class=\"$estilo\">";
                         $salida.="  <td align=\"left\" width=\"9%\"><b>Cantidad:</b></td>";
                         $e=$vector1[$i][cantidad]/floor($vector1[$i][cantidad]);
                         if($vector1[$i][contenido_unidad_venta])
                         {
                              if($e==1)
                              {
                                   $salida.="  <td colspan=\"2\" align=\"left\">".floor($vector1[$i][cantidad])." ".$vector1[$i][unidad]." por ".$vector1[$i][contenido_unidad_venta]."</td>";
                              }
                              else
                              {
                                   $salida.="  <td colspan=\"2\" align=\"left\">".$vector1[$i][cantidad]." ".$vector1[$i][unidad]." por ".$vector1[$i][contenido_unidad_venta]."</td>";
                              }
                         }
                         else
                         {
                              if($e==1)
                              {
                                   $salida.="  <td colspan=\"2\" align=\"left\">".floor($vector1[$i][cantidad])." ".$vector1[$i][unidad]."</td>";
                              }
                              else
                              {
                                   $salida.="  <td colspan=\"2\" align=\"left\">".$vector1[$i][cantidad]." ".$vector1[$i][unidad]."</td>";
                              }
                         }
                         $salida.="</tr>";
                         if($vector1[$i][observacion] != "")
                         {
                              $salida.="<tr class=\"$estilo\">";
                              $salida.="  <td align=\"left\" width=\"9%\"><b>Observaci�:</b></td>";
                              $salida.="  <td align=\"left\" colspan=\"2\">".$vector1[$i][observacion]."</td>";
                              $salida.="</tr>";
                         }
          
                         $Profesional = $this->ProfesionalFormulacion_Medicamento($vector1[$i][usuario_id]);
                         $salida.="<tr class=\"$estilo\">";
                         $salida.="<td align=\"left\" width=\"9%\"><b>Formul�</b></td>";
                         $salida.="<td align=\"left\" colspan=\"2\">".$Profesional."</td>";
                         $salida.="</tr>";
                         $salida.="</table>";
                         $salida.="</td>";
                         $salida.="</tr>";
     
                    }else
                    {
                         if($vector1[$i]['num_mezcla'] != $vector1[$i-1]['num_mezcla'])
                         {
                              $salida.="<tr class=\"$estilo\">";
                              $salida.="<td colspan=\"6\">";
                              $salida.="<table>";

                              $salida.="<tr class=\"$estilo\">";
                              $salida.="  <td align=\"left\" width=\"42%\"><b>Cantidad Total:</b></td>";
                              $salida.="  <td align=\"left\" colspan=\"2\">".floor($vector1[$i][cantidad])." SOLUCION(ES)</td>";
                              $salida.="</tr>";
                              
                              $salida.="<tr class=\"$estilo\">";
                              $salida.="  <td align=\"left\" width=\"42%\"><b>Volumen de Infusi�:</b></td>";
                              $salida.="  <td align=\"left\" colspan=\"2\">".floor($vector1[$i][volumen_infusion])." ".strtoupper($vector1[$i][unidad_volumen])."</td>";
                              $salida.="</tr>";
                         
                              if($vector1[$i][observacion] != "")
                              {
                                   $salida.="<tr class=\"$estilo\">";
                                   $salida.="  <td align=\"left\" width=\"9%\"><b>Observaci�:</b></td>";
                                   $salida.="  <td align=\"left\" colspan=\"2\">".$vector1[$i][observacion]."</td>";
                                   $salida.="</tr>";
                              }
               
                              $Profesional = $this->ProfesionalFormulacion_Medicamento($vector1[$i][usuario_id]);
                              $salida.="<tr class=\"$estilo\">";
                              $salida.="<td align=\"left\" width=\"9%\"><b>Formul�</b></td>";
                              $salida.="<td align=\"left\" colspan=\"2\">".$Profesional."</td>";
                              $salida.="</tr>";
                              $salida.="</table>";
		                    $salida.="</td>";
                              $salida.="</tr>";
                         }
                    }
               } //fin del for muy importante
          }
     	return $salida;
     }

    
/*    function FrmMedicamentos($vector1, $reporte, $amb)
    {
        $this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"80%\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida.="</table>";
        $espia=1;
        $total_medicamentos_uso_controlado = 0;

        for($i=0;$i<sizeof($vector1);$i++)
        {
                if($vector1[$i][sw_uso_controlado]== 1)
                {
                        $total_medicamentos_uso_controlado= $total_medicamentos_uso_controlado + 1;
                }
                if ($espia==1)
                {
                        $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
                        $this->salida.="<tr class=\"modulo_table_title\">";

                        if ($vector1[$i][item]== 'NO POS' AND $vector1[$i][sw_paciente_no_pos]== '0')
                        {
                                $this->salida.="  <td align=\"center\" colspan=\"5\">MEDICAMENTOS NO POS JUSTIFICADOS</td>";
                        }
                        else
                        {
                            if($vector1[$i][item]== 'NO POS' AND $vector1[$i][sw_paciente_no_pos]== '1')
                            {
                                    $this->salida.="  <td align=\"center\" colspan=\"5\">MEDICAMENTOS NO POS SOLICITADOS A PETICION DEL PACIENTE</td>";
                            }
                            else
                            {
                                    $this->salida.="  <td align=\"center\" colspan=\"5\">MEDICAMENTOS POS FORMULADOS</td>";
                            }
                        }
                        $this->salida.="</tr>";

                        $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                        $this->salida.="  <td width=\"7%\">CODIGO</td>";
                        $this->salida.="  <td width=\"30%\">PRODUCTO</td>";
                        $this->salida.="  <td colspan=\"3\" width=\"43%\">PRINCIPIO ACTIVO</td>";
                        $this->salida.="</tr>";
                }
                if ($vector1[$i][item]== $vector1[$i+1][item] AND $vector1[$i][sw_paciente_no_pos]== $vector1[$i+1][sw_paciente_no_pos])
                {
                        $espia=0;
                }
                else
                {
                        $espia=1;
                }

                if( $i % 2){ $estilo='modulo_list_claro';}
                else {$estilo='modulo_list_oscuro';}
                $this->salida.="<tr class=\"$estilo\">";
                if($vector1[$i][item] == 'NO POS')
                {
                        $this->salida.="  <td ROWSPAN = 3 align=\"center\" width=\"7%\">".$vector1[$i][codigo_producto]."<BR>NO_POS</td>";
                } 
                else
                {
                        $this->salida.="  <td ROWSPAN = 3 align=\"center\" width=\"7%\">".$vector1[$i][codigo_producto]."</td>";
                }
                $this->salida.="  <td align=\"center\" width=\"30%\">".$vector1[$i][producto]."</td>";
                $this->salida.="  <td colspan=\"3\" align=\"center\" width=\"43%\">".$vector1[$i][principio_activo]."</td>";
                $this->salida.="</tr>";

                $this->salida.="<tr class=\"$estilo\">";
                $this->salida.="<td colspan = 4>";
                $this->salida.="<table>";

                $this->salida.="<tr class=\"$estilo\">";
                $this->salida.="  <td colspan = 3 align=\"left\" width=\"9%\">Via de Administracion: ".$vector1[$i][via]."</td>";
                $this->salida.="</tr>";

                $this->salida.="<tr class=\"$estilo\">";
                $this->salida.="  <td align=\"left\" width=\"9%\">Dosis:</td>";
                $e=$vector1[$i][dosis]/floor($vector1[$i][dosis]);
                if($e==1)
                {
                        $this->salida.="  <td align=\"left\" width=\"14%\">".floor($vector1[$i][dosis])."  ".$vector1[$i][unidad_dosificacion]."</td>";
                }
                else
                {
                        $this->salida.="  <td align=\"left\" width=\"14%\">".$vector1[$i][dosis]."  ".$vector1[$i][unidad_dosificacion]."</td>";
                }

                $vector_posologia= Consulta_Solicitud_Medicamentos_Posologia_Hosp($vector1[$i][codigo_producto], $vector1[$i][tipo_opcion_posologia_id], $vector1[$i][evolucion_id]);

                //pintar formula para opcion 1
                if($vector1[$i][tipo_opcion_posologia_id]== 1)
                {
                        $this->salida.="  <td align=\"left\" width=\"50%\">cada ".$vector_posologia[0][periocidad_id]." ".$vector_posologia[0][tiempo]."</td>";
                }
                //pintar formula para opcion 2
                if($vector1[$i][tipo_opcion_posologia_id]== 2)
                {
                        $this->salida.="  <td align=\"left\" width=\"50%\">".$vector_posologia[0][descripcion]."</td>";
                }
                //pintar formula para opcion 3
                if($vector1[$i][tipo_opcion_posologia_id]== 3)
                {
                        $momento = '';
                        if($vector_posologia[0][sw_estado_momento]== '1')
                        {
                                $momento = 'antes de ';
                        }
                        else
                        {
                                if($vector_posologia[0][sw_estado_momento]== '2')
                                {
                                        $momento = 'durante ';
                                }
                                else
                                {
                                    if($vector_posologia[0][sw_estado_momento]== '3')
                                    {
                                            $momento = 'despues de ';
                                    }
                                }
                        }
                        $Cen = $Alm = $Des= '';
                        $cont= 0;
                        $conector = '  ';
                        $conector1 = '  ';
                        if($vector_posologia[0][sw_estado_desayuno]== '1')
                        {
                                $Des = $momento.'el Desayuno';
                                $cont++;
                        }
                        if($vector_posologia[0][sw_estado_almuerzo]== '1')
                        {
                                $Alm = $momento.'el Almuerzo';
                                $cont++;
                        }
                        if($vector_posologia[0][sw_estado_cena]== '1')
                        {
                                $Cen = $momento.'la Cena';
                                $cont++;
                        }
                        if ($cont== 2)
                        {
                                $conector = ' y ';
                                $conector1 = '  ';
                        }
                        if ($cont== 1)
                        {
                                $conector = '  ';
                                $conector1 = '  ';
                        }
                        if ($cont== 3)
                        {
                                $conector = ' , ';
                                $conector1 = ' y ';
                        }
                        $this->salida.="  <td align=\"left\" width=\"50%\">".$Des."".$conector."".$Alm."".$conector1."".$Cen."</td>";
                }
                //pintar formula para opcion 4
                if($vector1[$i][tipo_opcion_posologia_id]== 4)
                {
                        $conector = '  ';
                        $frecuencia='';
                        $j=0;
                        foreach ($vector_posologia as $k => $v)
                        {
                                if ($j+1 ==sizeof($vector_posologia))
                                {
                                        $conector = '  ';
                                }
                                else
                                {
                                        if ($j+2 ==sizeof($vector_posologia))
                                        {
                                                $conector = ' y ';
                                        }
                                        else
                                        {
                                                $conector = ' - ';
                                        }
                                }
                                $frecuencia = $frecuencia.$k.$conector;
                                $j++;
                        }
                        $this->salida.="  <td align=\"left\" width=\"50%\">a la(s): $frecuencia</td>";
                }
                //pintar formula para opcion 5
                if($vector1[$i][tipo_opcion_posologia_id]== 5)
                {
                        $this->salida.="  <td align=\"left\" width=\"50%\">".$vector_posologia[0][frecuencia_suministro]."</td>";
                }
                $this->salida.="</tr>";

                $this->salida.="<tr class=\"$estilo\">";
                $this->salida.="  <td align=\"left\" width=\"9%\">Cantidad:</td>";
                $e=$vector1[$i][cantidad]/floor($vector1[$i][cantidad]);
                if ($vector1[$i][contenido_unidad_venta])
                {
                        if($e==1)
                        {
                                $this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".floor($vector1[$i][cantidad])." ".$vector1[$i][descripcion]." por ".$vector1[$i][contenido_unidad_venta]."</td>";
                        }
                        else
                        {
                                $this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".$vector1[$i][cantidad]." ".$vector1[$i][descripcion]." por ".$vector1[$i][contenido_unidad_venta]."</td>";
                        }
               }
                else
                {
                        if($e==1)
                        {
                                $this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".floor($vector1[$i][cantidad])." ".$vector1[$i][descripcion]."</td>";
                        }
                        else
                        {
                                $this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".$vector1[$i][cantidad]." ".$vector1[$i][descripcion]."</td>";
                        }
                }
                $this->salida.="</tr>";
                $this->salida.="</table>";
                $this->salida.="</td>";
                $this->salida.="</tr>";
                $this->salida.="<tr class=\"$estilo\">";
                $this->salida.="<td colspan = 4 class=\"$estilo\">";
                $this->salida.="<table>";
                $this->salida.="<tr class=\"$estilo\">";
                $this->salida.="  <td align=\"left\" width=\"4%\">Observacion:</td>";
                $this->salida.="  <td align=\"left\" width=\"69%\">".$vector1[$i][observacion]."</td>";


                //IMPRESION DE LA JUSTIFICACION
                if ($vector1[$i][item]== 'NO POS' AND $vector1[$i][sw_paciente_no_pos]== '0')
                {
                    //reporte en pdf de la justificacion
                    $mostrar=$reporte->GetJavaReport('system','reportes','justificacion_nopos_med_html',array('codigo_producto'=>$vector1[$i][codigo_producto], 'evolucion'=>$vector1[$i][evolucion_id], 'invocado'=>2),array('rpt_name'=>'justificacion_nopos_med_html','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
                    $nombre_funcion=$reporte->GetJavaFunction();
                    $this->salida .=$mostrar;
                    $this->salida.="<td align=\"left\" width=\"14%\"><a href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>JUSTIFICACION</a></td>";
                }
                //FIN DE IMPRESION JUSTIFICACION

                $this->salida.="<tr class=\"$estilo\">";
                if($vector1[$i][sw_uso_controlado]==1)
                {
                        $this->salida.="<tr class=\"$estilo\">";
                        $this->salida.="  <td align=\"left\" colspan = 2 width=\"73%\">MEDICAMENTO DE USO CONTROLADO</td>";
                        $this->salida.="<tr class=\"$estilo\">";
                }
                $this->salida.="</table>";
                $this->salida.="</td>";
                $this->salida.="</tr>";
                
               if ($amb == '1')
               {
                    $modulo = 'Central_de_Autorizaciones';
                    $nombre_reporte = 'formula_medica_html';
               }
               elseif($amb == '0')
               {
                    $modulo = 'CentralImpresionHospitalizacion';
                    $nombre_reporte = 'formula_medica_hosp_html';
               }

                if($espia==1)
                {
                    //la impresion de claudia
                    $this->salida.="<tr class=\"$estilo\">";
                    if ($vector1[$i][item]== 'NO POS' AND $vector1[$i][sw_paciente_no_pos]== '0')
                    {
                         //reporte en impresora pos
                         //$accion1=ModuloGetURL('app','CentralImpresionHospitalizacion','user','ReporteFormulaMedica',array('sw_pos'=>'0','sw_paciente_no_pos'=>'0', 'tipo_id_paciente'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'],'paciente_id'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id'], 'ingreso'=>$_SESSION['ADMISIONES']['PACIENTE']['ingreso'], 'modulo_invoca'=>'salida_pacientes', 'parametro_retorno'=>'1', 'impresion_pos'=>'1'));
                         $accion1=ModuloGetURL('app',$modulo,'user','ReporteFormulaMedica',array('tipo_formulacion'=>$amb, 'sw_pos'=>'0','sw_paciente_no_pos'=>'0', 'tipo_id_paciente'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'],'paciente_id'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id'], 'ingreso'=>$_SESSION['ADMISIONES']['PACIENTE']['ingreso'], 'modulo_invoca'=>'salida_pacientes', 'parametro_retorno'=>'1', 'impresion_pos'=>'1'));
                         $this->salida.="<td colspan = 2 align=\"center\" width=\"37%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR POS</a></td>";

                         //reportes en html
                         $mostrar=$reporte->GetJavaReport('app',$modulo,'formula_medica_hosp_html',array('tipo_formulacion'=>$amb, 'sw_pos'=>'0','sw_paciente_no_pos'=>'0', 'tipo_id_paciente'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'],'paciente_id'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id'], 'ingreso'=>$_SESSION['ADMISIONES']['PACIENTE']['ingreso']),array('rpt_name'=>'formulamedica','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
                         $nombre_funcion=$reporte->GetJavaFunction();
                         $this->salida .=$mostrar;
                         $this->salida.="<td colspan = 2 align=\"center\" width=\"43%\"><a href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR PDF</a></td>";

                         //reporte en pdf antiguo media carta
                         $accion2=ModuloGetURL('app',$modulo,'user','ReporteFormulaMedica',array('tipo_formulacion'=>$amb, 'sw_pos'=>'0','sw_paciente_no_pos'=>'0', 'tipo_id_paciente'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'],'paciente_id'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id'], 'ingreso'=>$_SESSION['ADMISIONES']['PACIENTE']['ingreso'], 'modulo_invoca'=>'salida_pacientes', 'parametro_retorno'=>'1'));
                         $this->salida.="<td colspan = 1 align=\"center\" width=\"37%\"><a href='$accion2'><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR MEDIA CARTA</a></td>";
                    }
                    else
                    {
                         if($vector1[$i][item]== 'NO POS' AND $vector1[$i][sw_paciente_no_pos]== '1')
                         {
                              //reporte en impresora pos
                              //$accion1=ModuloGetURL('app','CentralImpresionHospitalizacion','user','ReporteFormulaMedica',array('sw_pos'=>'0','sw_paciente_no_pos'=>'1', 'tipo_id_paciente'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'],'paciente_id'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id'], 'ingreso'=>$_SESSION['ADMISIONES']['PACIENTE']['ingreso'], 'modulo_invoca'=>'salida_pacientes', 'parametro_retorno'=>'1', 'impresion_pos'=>'1'));
                              $accion1=ModuloGetURL('app','Central_de_Autorizaciones','user','ReporteFormulaMedicaHosp',array('tipo_formulacion'=>$amb, 'sw_pos'=>'0','sw_paciente_no_pos'=>'1', 'tipo_id_paciente'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'],'paciente_id'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id'], 'ingreso'=>$_SESSION['ADMISIONES']['PACIENTE']['ingreso'], 'modulo_invoca'=>'salida_pacientes', 'parametro_retorno'=>'1', 'impresion_pos'=>'1'));
                              $this->salida.="  <td colspan = 2 align=\"center\" width=\"37%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR POS</a></td>";

                              //reportes en html
                                   $mostrar=$reporte->GetJavaReport('app','CentralImpresionHospitalizacion','formula_medica_hosp_html',array('tipo_formulacion'=>$amb, 'sw_pos'=>'0','sw_paciente_no_pos'=>'1', 'tipo_id_paciente'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'],'paciente_id'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id'], 'ingreso'=>$_SESSION['ADMISIONES']['PACIENTE']['ingreso']),array('rpt_name'=>'formulamedica','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
                                   $nombre_funcion=$reporte->GetJavaFunction();
                                   $this->salida .=$mostrar;
                                   $this->salida.="<td colspan = 2 align=\"center\" width=\"43%\"><a href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR PDF</a></td>";

                                   //reporte en pdf antiguo media carta
                                   $accion1=ModuloGetURL('app','CentralImpresionHospitalizacion','user','ReporteFormulaMedica',array('tipo_formulacion'=>$amb, 'sw_pos'=>'0','sw_paciente_no_pos'=>'1', 'tipo_id_paciente'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'],'paciente_id'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id'], 'ingreso'=>$_SESSION['ADMISIONES']['PACIENTE']['ingreso'], 'modulo_invoca'=>'salida_pacientes', 'parametro_retorno'=>'1'));
                                   $this->salida.="  <td colspan = 1 align=\"center\" width=\"37%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR MEDIA CARTA</a></td>";
                              }
                              else
                              {
                                   //reporte en impresora pos
                                   //$accion1=ModuloGetURL('app','CentralImpresionHospitalizacion','user','ReporteFormulaMedica',array('sw_pos'=>'1', 'tipo_id_paciente'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'],'paciente_id'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id'], 'ingreso'=>$_SESSION['ADMISIONES']['PACIENTE']['ingreso'], 'modulo_invoca'=>'salida_pacientes', 'parametro_retorno'=>'1', 'impresion_pos'=>'1'));
                                   $accion1=ModuloGetURL('app','Central_de_Autorizaciones','user','ReporteFormulaMedicaHosp',array('tipo_formulacion'=>$amb, 'sw_pos'=>'1', 'tipo_id_paciente'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'],'paciente_id'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id'], 'ingreso'=>$_SESSION['ADMISIONES']['PACIENTE']['ingreso'], 'modulo_invoca'=>'salida_pacientes', 'parametro_retorno'=>'1', 'impresion_pos'=>'1'));

                                   $this->salida.="  <td colspan = 2 align=\"center\" width=\"37%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR POS</a></td>";

                                //reportes en html
                                   $mostrar=$reporte->GetJavaReport('app','CentralImpresionHospitalizacion','formula_medica_hosp_html',array('tipo_formulacion'=>$amb, 'sw_pos'=>'1', 'tipo_id_paciente'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'],'paciente_id'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id'], 'ingreso'=>$_SESSION['ADMISIONES']['PACIENTE']['ingreso']),array('rpt_name'=>'formulamedica','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
                                   $nombre_funcion=$reporte->GetJavaFunction();
                                   $this->salida .=$mostrar;
                                   $this->salida.="<td colspan = 2 align=\"center\" width=\"43%\"><a href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR PDF</a></td>";
                                   //fin de alex

                                   //reporte en pdf antiguo media carta
                                   $accion1=ModuloGetURL('app','CentralImpresionHospitalizacion','user','ReporteFormulaMedica',array('tipo_formulacion'=>$amb, 'sw_pos'=>'1', 'tipo_id_paciente'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'],'paciente_id'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id'], 'ingreso'=>$_SESSION['ADMISIONES']['PACIENTE']['ingreso'], 'modulo_invoca'=>'salida_pacientes', 'parametro_retorno'=>'1'));
                                   $this->salida.="  <td colspan = 1 align=\"center\" width=\"37%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR MEDIA CARTA</a></td>";
                              }
                    }
                    $this->salida.="</tr>";
                    $this->salida.="</table><br>";
                }
                
            }
               //opcion para imprimir medicamentos de uso controlado
               if($total_medicamentos_uso_controlado>0)
               {
                    $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
                    $this->salida.="<tr class=\"modulo_table_title\">";
                    $this->salida.="<td align=\"center\" colspan=\"3\">MEDICAMENTOS DE USO CONTROLADO</td>";
                    $this->salida.="</tr>";
                    $this->salida.="<tr class=\"modulo_list_claro\">";
                 //reporte en impresora pos
                    $accion1=ModuloGetURL('app','Admisiones','user','ReporteFormulaMedica',array('tipo_formulacion'=>$amb, 'sw_uso_controlado'=>'1', 'tipo_id_paciente'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'],'paciente_id'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id'], 'ingreso'=>$_SESSION['ADMISIONES']['PACIENTE']['ingreso'], 'modulo_invoca'=>'salida_pacientes', 'parametro_retorno'=>'1', 'impresion_pos'=>'1'));
                    $this->salida.="<td align=\"center\" width=\"25%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR POS</a></td>";

                    //reporte pdf y htmlformula_medica_hosp_html
                    $usoControlado=$reporte->GetJavaReport('app','CentralImpresionHospitalizacion','formula_medica_hosp_html',array('tipo_formulacion'=>$amb, 'sw_uso_controlado'=>'1', 'tipo_id_paciente'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'],'paciente_id'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id'], 'ingreso'=>$_SESSION['ADMISIONES']['PACIENTE']['ingreso']),array('rpt_name'=>'formulamedica','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
                    $nombrefuncion=$reporte->GetJavaFunction();
                    $this->salida.=$usoControlado;
                    $this->salida.="<td align=\"center\" width=\"25%\"><a href=\"javascript:$nombrefuncion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR PDF</a></td>";
                    //fin de alex

                    //reporte en pdf antiguo media carta
                    $accion2=ModuloGetURL('app','Admisiones','user','ReporteFormulaMedica',array('tipo_formulacion'=>$amb, 'sw_uso_controlado'=>'1', 'tipo_id_paciente'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'],'paciente_id'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id'], 'ingreso'=>$_SESSION['ADMISIONES']['PACIENTE']['ingreso'], 'modulo_invoca'=>'salida_pacientes', 'parametro_retorno'=>'1'));
                    $this->salida.="<td align=\"center\" width=\"30%\"><a href='$accion2'><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR MEDIA CARTA</a></td>";

                    $this->salida.="</tr>";
                    $this->salida.="</table><br>";
               }
          }
*/

//-------------------------------INGRESO DINAMICO-------------------------------------------
    /**
    *
    */
    function FormaCapturaIngreso($sw_apertura)
    {
            IncludeLib("funciones_admision");
			$this->salida = ThemeAbrirTabla('ADMISIONES - DATOS DEL INGRESO DEL PACIENTE..');
            $this->FormaModificarDatos('app','Admisiones','user','FormaCapturaIngreso',array());
            if(!empty($_SESSION['ADMISIONES']['INGRESO']['ingreso']) AND empty($_SESSION['ADMISIONES']['PACIENTE']['INGRESO']))
            {   $this->InsertarIngresoInicial($sw_apertura);   }

            //INSERTAR EN PENDIENTES
            if(!empty($_SESSION['ADMISIONES']['INGRESO']['ingresotmp']) AND empty($_SESSION['ADMISIONES']['PACIENTE']['IDPENDIENTE']))
            {    $this->InsertarPendientesAdmitir();   }

            $sw=$this->BuscarSW($_SESSION['ADMISIONES']['PACIENTE']['plan_id']);
            if($sw==1 && $_SESSION['ADMISIONES']['SWSOAT']==0)
            {
                    $this->salida .= "      <table border=\"1\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
                    $this->salida .= "            <tr class=\"modulo_table_list_title\">";
                    $this->salida .= "            <td>DEBE REMITIR EL PACIENTE AL MODULO DE SOAT.</td>";
                    $this->salida .= "            </tr>";
                    $_SESSION['ADMISIONES']['RETORNO']['CANCELAR']=true;
                    $Contenedor=$_SESSION['ADMISIONES']['RETORNO']['contenedor'];
                    $Modulo=$_SESSION['ADMISIONES']['RETORNO']['modulo'];
                    $Tipo=$_SESSION['ADMISIONES']['RETORNO']['tipo'];
                    $Metodo=$_SESSION['ADMISIONES']['RETORNO']['metodo'];
                    $accionA=ModuloGetURL($Contenedor,$Modulo,$Tipo,$Metodo,$_SESSION['ADMISIONES']['RETORNO']['argumentos']);
                    $this->salida .= "            <tr class=\"modulo_table_list_title\">";
                    $this->salida .= "         <form name=\"formaingreso\" action=\"$accionA\" method=\"post\">";
                    $this->salida .= "                  <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"></td>";
                    $this->salida .= "  </form>";
                    $this->salida .= "            </tr>";
                    $this->salida .= "    </table><br>";
            }
            if($sw==1 && $_SESSION['ADMISIONES']['SWSOAT']==1)
            {
                    $this->salida .= "      <table border=\"1\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
                    $accionA=ModuloGetURL('app','Admisiones','user','LlamarModuloSoat');
                    $this->salida .= "            <tr class=\"modulo_table_list_title\">";
                    $this->salida .= "         <form name=\"formaingreso\" action=\"$accionA\" method=\"post\">";
                    $this->salida .= "                  <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"SELECCIONAR EVENTO\"></td>";
                    $this->salida .= "  </form>";
                    $this->salida .= "            </tr>";
                    $this->salida .= "    </table><br>";
            }
            if($sw!=1)
            {
                    if(!empty($_SESSION['ADMISIONES']['INGRESO']['ingreso']))
                    {  $accion=ModuloGetURL('app','Admisiones','user','InsertarIngreso');   }
                    if(!empty($_SESSION['ADMISIONES']['INGRESO']['ingresotmp']))
                    {       $accion=ModuloGetURL('app','Admisiones','user','InsertarIngresoTMP');   }
					
                    $this->salida .= "         <form name=\"formaingreso\" action=\"$accion\" method=\"post\">";
                    $this->salida .= "      <table border=\"0\" width=\"60%\" align=\"center\" class=\"normal_10\">";
                    $this->salida .= $this->SetStyle("MensajeError");
                    $this->salida .= "            <tr>";
                    $this->salida .= "                <td class=\"".$this->SetStyle("fechaIngreso")."\">FECHA INGRESO: </td>";
                    $fechaSistema=date("d/m/Y");
                    $this->salida .= "            <td><input type=\"text\"  class=\"input-text\" name=\"fechaIngreso\" value=\"$fechaSistema\"></td>";
                    $this->salida .= "            <td></td>";
                    $this->salida .= "            </tr>";
                    $this->salida .= "                     <tr><td class=\"".$this->SetStyle("ViaIngreso")."\">VIA INGRESO: </td><td><select name=\"ViaIngreso\" class=\"select\">";
                    $via_ingreso=ViasIngreso();
                    $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
                    for($i=0; $i<sizeof($via_ingreso); $i++)
                    {
                            if(empty($_REQUEST['ViaIngreso']))
                            {  $_REQUEST['ViaIngreso']=1;  }
                            if($via_ingreso[$i][via_ingreso_id]==$_REQUEST['ViaIngreso']){
                                $this->salida .=" <option value=\"".$via_ingreso[$i][via_ingreso_id]."\" selected>".$via_ingreso[$i][via_ingreso_nombre]."</option>";
                            }
                            else{
                                $this->salida .=" <option value=\"".$via_ingreso[$i][via_ingreso_id]."\">".$via_ingreso[$i][via_ingreso_nombre]."</option>";
                            }
                    }
                    $this->salida .= "              </select></td></tr>";
                    if($sw!=1)
                    {
                            $tipo_afiliado=TiposAfiliado($_SESSION['ADMISIONES']['PACIENTE']['plan_id']);
                            $this->salida .= "            <tr>";
                            if(!empty($_REQUEST['TipoAfiliado']))
                            { $TipoAfiliado=$_REQUEST['TipoAfiliado']; }
                            if(!empty($_SESSION['ADMISIONES']['PACIENTE']['tipo_afiliado_id']))
                            {
                                            $TipoAfiliado=$this->NombreTipoAfiliado($_SESSION['ADMISIONES']['PACIENTE']['tipo_afiliado_id']);
                                            $this->salida .= "                  <td class=\"".$this->SetStyle("TipoAfiliado")."\">TIPO AFILIADO: </td>";
                                            $this->salida .= "            <td><input type=\"hidden\"  class=\"input-text\" name=\"TipoAfiliado\" value=\"".$_SESSION['ADMISIONES']['PACIENTE']['tipo_afiliado_id']."\">".$TipoAfiliado."</td>";
                                            $this->salida .= "            <td></td>";
                            }
                            else
                            {
                                    if(sizeof($tipo_afiliado)>1)
                                    {
                                            $this->salida .= "                     <td class=\"".$this->SetStyle("TipoAfiliado")."\">TIPO AFILIADO: </td><td><select name=\"TipoAfiliado\" class=\"select\">";
                                            $this->BuscarIdTipoAfiliado($tipo_afiliado,$TipoAfiliado);
                                            $this->salida .= "              </select></td>";
                                    }
                                    else
                                    {
                                            $this->salida .= "                  <td class=\"".$this->SetStyle("TipoAfiliado")."\">TIPO AFILIADO: </td>";
                                            $this->salida .= "            <td><input type=\"hidden\"  class=\"input-text\" name=\"TipoAfiliado\" value=\"".$tipo_afiliado[0][tipo_afiliado_id]."\">".$tipo_afiliado[0][tipo_afiliado_nombre]."</td>";
                                            $this->salida .= "            <td></td>";
                                    }
                            }
                            $this->salida .= "            </tr>";
                            $this->salida .= "            <tr>";
                            if(!empty($_REQUEST['Nivel']))
                            { $Nivel=$_REQUEST['Nivel']; }
                            if(!empty($_SESSION['ADMISIONES']['PACIENTE']['rango']))
                            {
                                        $this->salida .= "                   <td class=\"".$this->SetStyle("Nivel")."\">RANGO: </td>";
                                        $this->salida .= "            <td><input type=\"hidden\"  class=\"input-text\" name=\"Nivel\" value=\"".$_SESSION['ADMISIONES']['PACIENTE']['rango']."\">&nbsp;&nbsp;".$_SESSION['ADMISIONES']['PACIENTE']['rango']."</td>";
                                        $this->salida .= "            <td></td>";
                            }
                            else
                            {
                                    $niveles=Niveles($_SESSION['ADMISIONES']['PACIENTE']['plan_id']);
                                    if(sizeof($niveles)>1)
                                    {
                                        $this->salida .= "                     <tr><td class=\"".$this->SetStyle("Nivel")."\">RANGO: </td><td><select name=\"Nivel\" class=\"select\">";
                                        $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
                                        for($i=0; $i<sizeof($niveles); $i++)
                                        {
                                                if($niveles[$i][rango]==$Nivel){
                                                    $this->salida .=" <option value=\"".$niveles[$i][rango]."\" selected>".$niveles[$i][rango]."</option>";
                                                }
                                                else{
                                                        $this->salida .=" <option value=\"".$niveles[$i][rango]."\">".$niveles[$i][rango]."</option>";
                                                }
                                        }
                                    }
                                    else
                                    {
                                            $this->salida .= "                   <td class=\"".$this->SetStyle("Nivel")."\">RANGO: </td>";
                                            $this->salida .= "            <td><input type=\"hidden\"  class=\"input-text\" name=\"Nivel\" value=\"".$niveles[0][rango]."\">&nbsp;&nbsp;".$niveles[0][rango]."</td>";
                                            $this->salida .= "            <td></td>";
                                    }
                            }
                            $this->salida .= "            </tr>";
                            $this->salida .= "            <tr>";
                            if(!empty($_SESSION['ADMISIONES']['PACIENTE']['semanas']))
                            {
                                    $this->salida .= "                   <td class=\"".$this->SetStyle("Semanas")."\">SEMANAS: </td>";
                                    $this->salida .= "            <td><input type=\"hidden\"  class=\"input-text\" name=\"Semanas\" value=\"".$_SESSION['ADMISIONES']['PACIENTE']['semanas']."\">&nbsp;&nbsp;".$_SESSION['ADMISIONES']['PACIENTE']['semanas']."</td>";
                                    $this->salida .= "            <td></td>";
                            }
                            else
                            {
                                    $this->salida .= "                   <td class=\"".$this->SetStyle("Semanas")."\">SEMANAS: </td>";
                                    $this->salida .= "            <td><input type=\"text\"  class=\"input-text\" name=\"Semanas\" value=\"".$_REQUEST['Semanas']."\"></td>";
                                    $this->salida .= "            <td></td>";
                            }
                            $this->salida .= "            </tr>";
                    }
                    $datos=BuscarProtocolo($_SESSION['ADMISIONES']['PACIENTE']['plan_id']);
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
                                    $this->salida .= "                <tr><td class=\"label\" width=\"24%\">PROTOCOLO: </td><td><a href=\"$accion\">$Protocolo</a></td>";
                                    $this->salida .= "            <td></td></tr>";
                            }
                    }
                    $this->salida .= "          <tr>";
                    $this->salida .= "  <td class=\"".$this->SetStyle("Nivel")."\">COMENTARIOS: </td>";
                    $this->salida .= "          <td width=\"50%\" colspan=\"2\"><textarea name=\"Comentarios\" cols=\"65\" rows=\"3\" class=\"textarea\">".$_REQUEST['Comentarios']."</textarea></td></tr>";
                    $this->salida .= "                     </tr>";
                    $this->salida .= "           </table>";
                    $this->FormaDatosAdicionales();
//--------------------------------------------
                    //botones datos adicionales
                    $this->salida .= "      <input type=\"hidden\" name=\"metod\" value=\"FormaCapturaIngreso\">";
                    $this->salida .= "      <br><table border=\"0\" width=\"40%\" align=\"center\" class=\"normal_10\">";
                    $this->salida .= "               <tr>";
                    $this->salida .= "                  <td class=\"label\"><input class=\"input-submit\" type=\"submit\" name=\"Empleador\" value=\"EMPLEADOR\"></td>";
                    $this->salida .= "                  <td class=\"label\"><input class=\"input-submit\" type=\"submit\" name=\"Acudiente\" value=\"ACUDIENTE\"></td>";
                    $this->salida .= "                  <td class=\"label\"><input class=\"input-submit\" type=\"submit\" name=\"Garante\" value=\"GARANTE\"></td>";
                    $this->salida .= "               </tr>";
                    $this->salida .= "         </table>";
                    //fin botones datos adicionales
//--------------------------------------------
                    //links datos adicionales
                    /*$this->salida .= "      <br><table border=\"0\" width=\"40%\" align=\"center\" class=\"normal_10\">";
                    $this->salida .= "               <tr>";
                    $accionEmp=ModuloGetURL('app','Admisiones','user','LlamarEmpleador',array('metod'=>'FormaCapturaIngreso'));
                    $this->salida .= "                  <td class=\"label\"><a href=\"$accionEmp\">EMPLEADOR</a></td>";
                    $accionAcud=ModuloGetURL('app','Admisiones','user','LlamarAcudientes',array('metod'=>'FormaCapturaIngreso'));
                    $this->salida .= "                  <td class=\"label\"><a href=\"$accionAcud\">ACUDIENTE</a></td>";
                    $accionGara=ModuloGetURL('app','Admisiones','user','LlamarGarantesIngreso',array('metod'=>'FormaCapturaIngreso'));
                    $this->salida .= "                  <td class=\"label\"><a href=\"$accionGara\">GARANTE</a></td>";
                    $this->salida .= "               </tr>";
                    $this->salida .= "         </table>";*/
                    //fin links datos adicionales
                    $this->salida .= "      <table border=\"0\" width=\"40%\" align=\"center\" class=\"normal_10\">";
                    $rips=$this->VerificarDatosObligatoriosRips($_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'],$_SESSION['ADMISIONES']['PACIENTE']['paciente_id']);
                    if($rips >0)
                    {  $this->salida .= "                 <tr><td align=\"center\" class=\"label_mark\"><br>FALTAN DATOS DE RIPS<br></td>";  }
                    else
                    {  $this->salida .= "                 <tr><td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"><br></td>";  }
                    $this->salida .= "  </form>";
                    $actionCancelar=ModuloGetURL('app','Admisiones','user','CancelarIngreso');
                    $this->salida .= "  <form name=\"formacancelar\" action=\"$actionCancelar\" method=\"post\">";
                    $this->salida .= "                     <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"CANCELAR\"><br></td>";
                    $this->salida .= "  </form>";
                    $this->salida .= "                     </tr>";
                    $this->salida .= "           </table>";
            }
            $this->salida .= ThemeCerrarTabla();
            return true;
        }

    function FormaIngresoEventos()
    {
            IncludeLib("funciones_admision");
            $this->salida .= ThemeAbrirTabla('ADMISIONES - DATOS DEL INGRESO SOAT');
            $this->ReturnMetodoExterno('app','Soat','user','SoatAdmisionMenu',array('Evento'=>$_SESSION['ADMISIONES']['SOAT']['evento'],'TipoId'=>$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'],'PacienteId'=>$_SESSION['ADMISIONES']['PACIENTE']['paciente_id']));
            $this->FormaModificarDatos('app','Admisiones','user','FormaIngresoEventos',array());

            //se hace admision real el medico lo clasifico sino en la tabla ficti
            if(!empty($_SESSION['ADMISIONES']['INGRESO']['ingreso']))
            {  $accion=ModuloGetURL('app','Admisiones','user','InsertarIngreso');   }
            if(!empty($_SESSION['ADMISIONES']['INGRESO']['ingresotmp']))
            {       $accion=ModuloGetURL('app','Admisiones','user','InsertarIngresoTMP');   }
            $this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
            $this->salida .= "    <table border=\"0\" width=\"50%\" align=\"center\" >";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "            <tr>";
            $this->salida .= "                <td class=\"".$this->SetStyle("fechaIngreso")."\">FECHA INGRESO: </td>";
            if(!$_REQUEST['fechaIngreso']) {   $FechaIngreso=date("d/m/Y"); }
            $this->salida .= "            <td><input type=\"text\"  class=\"input-text\" name=\"fechaIngreso\" value=\"$FechaIngreso\"></td>";
            $this->salida .= "            <td></td>";
            $this->salida .= "            </tr>";
            $this->salida .= "                     <tr><td class=\"".$this->SetStyle("ViaIngreso")."\">VIA INGRESO: </td><td><select name=\"ViaIngreso\" class=\"select\">";
            $via_ingreso=ViasIngreso();
            $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
            for($i=0; $i<sizeof($via_ingreso); $i++)
            {
                    if(empty($_REQUEST['ViaIngreso']))
                    {  $_REQUEST['ViaIngreso']=1;  }
                    if($via_ingreso[$i][via_ingreso_id]==$_REQUEST['ViaIngreso']){
                        $this->salida .=" <option value=\"".$via_ingreso[$i][via_ingreso_id]."\" selected>".$via_ingreso[$i][via_ingreso_nombre]."</option>";
                    }
                    else{
                        $this->salida .=" <option value=\"".$via_ingreso[$i][via_ingreso_id]."\">".$via_ingreso[$i][via_ingreso_nombre]."</option>";
                    }
            }
            $this->salida .= "              </select></td></tr>";
            $this->salida .= "           </table>";
            $this->FormaDatosAdicionales();
            //links datos adicionales
            $this->salida .= "      <br><table border=\"0\" width=\"40%\" align=\"center\" class=\"normal_10\">";
            $this->salida .= "               <tr>";
            $accionEmp=ModuloGetURL('app','Admisiones','user','LlamarEmpleador',array('metod'=>'FormaIngresoEventos'));
            $this->salida .= "                  <td class=\"label\"><a href=\"$accionEmp\">EMPLEADOR</a></td>";
            $accionAcud=ModuloGetURL('app','Admisiones','user','LlamarAcudientes',array('metod'=>'FormaIngresoEventos'));
            $this->salida .= "                  <td class=\"label\"><a href=\"$accionAcud\">ACUDIENTE</a></td>";
            $accionGara=ModuloGetURL('app','Admisiones','user','LlamarGarantesIngreso',array('metod'=>'FormaIngresoEventos'));
            $this->salida .= "                  <td class=\"label\"><a href=\"$accionGara\">GARANTE</a></td>";
            $this->salida .= "               </tr>";
            $this->salida .= "         </table>";
            //fin links datos adicionales
            $this->salida .= "    <table border=\"0\" width=\"50%\" align=\"center\" >";
            $rips=$this->VerificarDatosObligatoriosRips($_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'],$_SESSION['ADMISIONES']['PACIENTE']['paciente_id']);
            if($rips >0)
            {   $this->salida .= "                     <tr><td align=\"center\" class=\"label_mark\"><br>FALTAN DATOS RIPS<br></td>";   }
            else
            {   $this->salida .= "                     <tr><td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"><br></td>";   }
            $this->salida .= "  </form>";
            if($_SESSION['ADMISIONES']['SOAT']['sw']==2)
            {
                    $accionA=ModuloGetURL('app','Admisiones','user','LlamarModuloSoat');
                    $this->salida .= "         <form name=\"formaingreso\" action=\"$accionA\" method=\"post\">";
                    $this->salida .= "               <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"CAMBIAR EVENTO\"><br></td>";
                    $this->salida .= "  </form>";
                    $actionCancelar=ModuloGetURL('app','Admisiones','user','CancelarIngreso');
                    $this->salida .= "  <form name=\"formacancelar\" action=\"$actionCancelar\" method=\"post\">";
                    $this->salida .= "                     <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"CANCELAR\"><br></td>";
                    $this->salida .= "  </form>";
                    $this->salida .= "                     </tr>";
            }
            $this->salida .= "           </table>";
            $this->salida .= ThemeCerrarTabla();
            return true;
    }

        /**
        *
        */
        function FormaCrearGarantes()
        {
                IncludeLib('funciones_admision');
                $this->salida .= ThemeAbrirTabla('ADMISIONES - DATOS GARANTES');
                $Garantes=$this->BuscarGarantes();
                if($Garantes)
                {
                        $this->salida .= "         <br>";
                        $this->salida .= "      <table width=\"60%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
                        $this->salida .= "          <tr align=\"center\" class=\"modulo_table_list_title\">";
                        $this->salida .= "              <td>GARANTE</td>";
                        $this->salida .= "              <td>IDENTIFICACION</td>";
                        $this->salida .= "              <td>DIRECCION</td>";
                        $this->salida .= "              <td>TELEFONO</td>";
                        $this->salida .= "              <td width=\"3%\"></td>";
                        $this->salida .= "              <td width=\"3%\"></td>";
                        $this->salida .= "          </tr>";
                        for($i=0; $i<sizeof($Garantes); $i++)
                        {
                                        if( $i % 2) $estilo='modulo_list_claro';
                                        else $estilo='modulo_list_oscuro';
                                        $this->salida .= "          <tr class=\"$estilo\">";
                                        $this->salida .= "              <td>".$Garantes[$i][primer_nombre_garante]." ". $Garantes[$i][primer_apellido_garante]."</td>";
                                        $this->salida .= "              <td>".$Garantes[$i][tipo_id_tercero]." ".$Garantes[$i][garante_id]."</td>";
                                        $this->salida .= "              <td align=\"center\">".$Garantes[$i][direccion_garante]."</td>";
                                        $this->salida .= "              <td align=\"center\">".$Garantes[$i][telefono_garante]."</td>";
                                        $accion2=ModuloGetURL('app','Admisiones','user','EliminarGarante',array('tipoGarante'=>$Garantes[$i][tipo_id_tercero],'idGarante'=>$Garantes[$i][garante_id]));
                                        $this->salida .= "              <td align=\"center\"><a href='$accion2'><img src=\"".GetThemePath()."/images/elimina.png\"  border='0' title='ELIMINAR'></a></td>";
                                        $accion1=ModuloGetURL('app','Admisiones','user','LlamarFormaActualizarGarantes',array('arr'=>$Garantes[$i]));
                                        $this->salida .= "              <td align=\"center\"><a href='$accion1'><img src=\"".GetThemePath()."/images/editar.png\"  border='0' title='ACTUALIZAR'></a></td>";

                                        $this->salida .= "          </tr>";
                        }
                        $this->salida .= "  </table>";
                }
                $accion=ModuloGetURL('app','Admisiones','user','InsertarDatosGarantes',array('TipoId'=>$TipoId,'GaranteId'=>$PacienteId,'Ingreso'=>$Ingreso));
                $this->salida .= "  <BR><table border=\"0\" width=\"60%\" align=\"center\" >";
                $this->salida .= "    <tr><td><fieldset><legend class=\"field\">DATOS GARANTE</legend>";
                $this->salida .= "<br><table width=\"70%\" cellspacing=\"0\" border=\"0\" cellpadding=\"0\" align=\"center\" >";
                $this->salida .= $this->SetStyle("MensajeError");
                $this->salida .= "  <form name=\"formapedir\" action=\"$accion\" method=\"post\">";
                $this->salida .= "<input type=\"hidden\" name=\"Ingreso\" value=\"$Ingreso\">";
                $this->salida .= "<input type=\"hidden\" name=\"accion\" value=\"$accionAcep\">";
                $this->salida .= "      <tr><td class=\"".$this->SetStyle("TipoId")."\">TIPO DOCUMENTO: </td><td><select name=\"TipoId\" class=\"select\">";
                $var=TipoIdTerceros();
                $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
                for($i=0; $i<sizeof($var); $i++)
                {
                        if($var[$i][tipo_id_tercero]==$_REQUEST[TipoId])
                        {   $this->salida .=" <option value=\"".$var[$i][tipo_id_tercero]."\" selected>".$var[$i][descripcion]."</option>";  }
                        else
                        {   $this->salida .=" <option value=\"".$var[$i][tipo_id_tercero]."\">".$var[$i][descripcion]."</option>";   }
                }
                $this->salida .= "              </select></td></tr>";
                $this->salida .= "      <tr height=\"20\">";
                $this->salida .= "      <td class=\"".$this->SetStyle("GaranteId")."\">DOCUMENTO: </td>";
                $this->salida .= "      <td><input type=\"text\" name=\"GaranteId\" maxlength=\"32\" class=\"input-text\" value=\"$_REQUEST[GaranteId]\"></td>";
                $this->salida .= "          <td>  </td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      <tr height=\"20\">";
                $this->salida .= "      <td class=\"".$this->SetStyle("PrimerNombre")."\">PRIMER NOMBRE: </td>";
                $this->salida .= "      <td><input type=\"text\" maxlength=\"20\" name=\"PrimerNombre\"  value=\"$_REQUEST[PrimerNombre]\" class=\"input-text\"></td>";
                $this->salida .= "      <td></td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      <tr height=\"20\">";
                $this->salida .= "      <td class=\"label\">SEGUNDO NOMBRE: </td>";
                $this->salida .= "      <td><input type=\"text\" maxlength=\"20\" name=\"SegundoNombre\" value=\"$_REQUEST[SegundoNombre]\" class=\"input-text\"></td>";
                $this->salida .= "          <td></td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      <tr height=\"20\">";
                $this->salida .= "      <td class=\"".$this->SetStyle("PrimerApellido")."\">PRIMER APELLIDO: </td>";
                $this->salida .= "      <td><input type=\"text\" maxlength=\"30\" name=\"PrimerApellido\" value=\"$_REQUEST[PrimerApellido]\" class=\"input-text\"></td>";
                $this->salida .= "      <td></td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      <tr height=\"20\">";
                $this->salida .= "      <td class=\"label\">SEGUNDO APELLIDO: </td>";
                $this->salida .= "      <td><input type=\"text\" maxlength=\"30\" name=\"SegundoApellido\" value=\"$_REQUEST[SegundoApellido]\" class=\"input-text\"></td>";
                $this->salida .= "      <td></td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      <tr>";
                $this->salida .= "      <tr height=\"20\">";
                $this->salida .= "      <td class=\"".$this->SetStyle("Direccion")."\">DIRECCION: </td>";
                $this->salida .= "      <td><input type=\"text\" maxlength=\"60\" name=\"Direccion\" value=\"$_REQUEST[Direccion]\" class=\"input-text\"></td>";
                $this->salida .= "      <td></td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      <tr height=\"20\">";
                $this->salida .= "      <td class=\"".$this->SetStyle("Telefono")."\">TELEFONOS: </td>";
                $this->salida .= "      <td ><input type=\"text\" maxlength=\"30\" name=\"Telefono\" value=\"$_REQUEST[Telefono]\" class=\"input-text\"></td>";
                $this->salida .= "      <td></td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      <tr>";
                $this->salida .= "           <td  align=\"center\" ><br><input class=\"input-submit\" type=\"submit\" name=\"Ingresar\" value=\"ACEPTAR\"><br></form></td>";
                $contenedor=$_SESSION['GARANTE']['RETORNO']['contenedor'];
                $modulo=$_SESSION['GARANTE']['RETORNO']['modulo'];
                $tipo=$_SESSION['GARANTE']['RETORNO']['tipo'];
                $metodo=$_SESSION['GARANTE']['RETORNO']['metodo'];
                $argumentos=$_SESSION['GARANTE']['RETORNO']['argumentos'];
                $accionCancelar=ModuloGetURL($contenedor,$modulo,$tipo,$metodo,$argumentos);
                $this->salida .= "  <form name=\"formagarantes\" action=\"$accionCancelar\" method=\"post\">";
                $this->salida .= "      <td  colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"CANCELAR\"><br></form></td>";
                $this->salida .= "      </tr>";
                $this->salida .= "  </table>";
                $this->salida .= "        </fieldset></td></tr></table><br>";
                $this->salida .= ThemeCerrarTabla();
                return true;
        }

        /**
        *
        */
        function FormaActualizarGarantes()
        {
                IncludeLib('funciones_admision');
                $this->salida .= ThemeAbrirTabla('ADMISIONES - ACTUALIZAR DATOS GARANTES');
                $accion=ModuloGetURL('app','Admisiones','user','ActualizarDatosGarantes',array('TipoIdAnt'=>$_REQUEST['TipoIdAnt'],'GaranteIdAnt'=>$_REQUEST['GaranteIdAnt'],'ingreso'=>$_REQUEST['ingreso'],'triage_pendiente'=>$_REQUEST['triage_pendiente']));
                $this->salida .= "  <BR><table border=\"0\" width=\"60%\" align=\"center\" >";
                $this->salida .= "    <tr><td><fieldset><legend class=\"field\">DATOS GARANTE</legend>";
                $this->salida .= "<br><table width=\"70%\" cellspacing=\"0\" border=\"0\" cellpadding=\"0\" align=\"center\" >";
                $this->salida .= $this->SetStyle("MensajeError");
                $this->salida .= "  <form name=\"formapedir\" action=\"$accion\" method=\"post\">";
                $this->salida .= "<input type=\"hidden\" name=\"Ingreso\" value=\"$Ingreso\">";
                $this->salida .= "<input type=\"hidden\" name=\"accion\" value=\"$accionAcep\">";
                $this->salida .= "      <tr><td class=\"".$this->SetStyle("TipoId")."\">TIPO DOCUMENTO: </td><td><select name=\"TipoId\" class=\"select\">";
                $var=TipoIdTerceros();
                $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
                for($i=0; $i<sizeof($var); $i++)
                {
                        if($var[$i][tipo_id_tercero]==$_REQUEST['TipoId'])
                        {   $this->salida .=" <option value=\"".$var[$i][tipo_id_tercero]."\" selected>".$var[$i][descripcion]."</option>";  }
                        else
                        {   $this->salida .=" <option value=\"".$var[$i][tipo_id_tercero]."\">".$var[$i][descripcion]."</option>";   }
                }
                $this->salida .= "              </select></td></tr>";
                $this->salida .= "      <tr height=\"20\">";
                $this->salida .= "      <td class=\"".$this->SetStyle("GaranteId")."\">DOCUMENTO: </td>";
                $this->salida .= "      <td><input type=\"text\" name=\"GaranteId\" maxlength=\"32\" class=\"input-text\" value=\"$_REQUEST[GaranteId]\"></td>";
                $this->salida .= "          <td>  </td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      <tr height=\"20\">";
                $this->salida .= "      <td class=\"".$this->SetStyle("PrimerNombre")."\">PRIMER NOMBRE: </td>";
                $this->salida .= "      <td><input type=\"text\" maxlength=\"20\" name=\"PrimerNombre\"  value=\"$_REQUEST[PrimerNombre]\" class=\"input-text\"></td>";
                $this->salida .= "      <td></td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      <tr height=\"20\">";
                $this->salida .= "      <td class=\"label\">SEGUNDO NOMBRE: </td>";
                $this->salida .= "      <td><input type=\"text\" maxlength=\"20\" name=\"SegundoNombre\" value=\"$_REQUEST[SegundoNombre]\" class=\"input-text\"></td>";
                $this->salida .= "          <td></td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      <tr height=\"20\">";
                $this->salida .= "      <td class=\"".$this->SetStyle("PrimerApellido")."\">PRIMER APELLIDO: </td>";
                $this->salida .= "      <td><input type=\"text\" maxlength=\"30\" name=\"PrimerApellido\" value=\"$_REQUEST[PrimerApellido]\" class=\"input-text\"></td>";
                $this->salida .= "      <td></td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      <tr height=\"20\">";
                $this->salida .= "      <td class=\"label\">SEGUNDO APELLIDO: </td>";
                $this->salida .= "      <td><input type=\"text\" maxlength=\"30\" name=\"SegundoApellido\" value=\"$_REQUEST[SegundoApellido]\" class=\"input-text\"></td>";
                $this->salida .= "      <td></td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      <tr>";
                $this->salida .= "      <tr height=\"20\">";
                $this->salida .= "      <td class=\"".$this->SetStyle("Direccion")."\">DIRECCION: </td>";
                $this->salida .= "      <td><input type=\"text\" maxlength=\"60\" name=\"Direccion\" value=\"$_REQUEST[Direccion]\" class=\"input-text\"></td>";
                $this->salida .= "      <td></td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      <tr height=\"20\">";
                $this->salida .= "      <td class=\"".$this->SetStyle("Telefono")."\">TELEFONOS: </td>";
                $this->salida .= "      <td ><input type=\"text\" maxlength=\"30\" name=\"Telefono\" value=\"$_REQUEST[Telefono]\" class=\"input-text\"></td>";
                $this->salida .= "      <td></td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      <tr>";
                $this->salida .= "           <td  align=\"center\" ><br><input class=\"input-submit\" type=\"submit\" name=\"Ingresar\" value=\"ACTUALIZAR\"><br></form></td>";
                $accionCancelar=ModuloGetURL('app','Admisiones','user','FormaCrearGarantes');
                $this->salida .= "  <form name=\"formagarantes\" action=\"$accionCancelar\" method=\"post\">";
                $this->salida .= "      <td  colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"CANCELAR\"><br></form></td>";
                $this->salida .= "      </tr>";
                $this->salida .= "  </table>";
                $this->salida .= "        </fieldset></td></tr></table><br>";
                $this->salida .= ThemeCerrarTabla();
                return true;
        }

        function FormaDatosAdicionales()
        {
                    $acu=$this->BuscarAcudientes();
                    $vars=$this->BuscarEmpleadores();
                    $Garantes=$this->BuscarGarantes();
                    if(!empty($acu) OR !empty($vars) OR !empty($Garantes))
                    {
                            $this->salida .= "      <table width=\"60%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"normal_10\">";
                            $this->salida .= "    <tr><td class=\"label_mark\" colspan=\"2\">DATOS ADICIONALES</td></tr>";
                            if(!empty($vars))
                            {
                                    $this->salida .= "      <tr>";
                                    $this->salida .= "      <td class=\"label\" width=\"20%\">EMPLEADOR: </td>";
                                    $this->salida .= "      <td>";
                                    $this->salida .= "      <table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"normal_10\">";
                                    for($i=0; $i<sizeof($vars); $i++)
                                    {
                                                    $this->salida .= "          <tr class=\"label\">";
                                                    $this->salida .= "              <td>".$vars[$i][nombre]."</td>";
                                                    $this->salida .= "          </tr>";
                                    }
                                    $this->salida .= "  </table>";
                                    $this->salida .= "      </td>";
                                    $this->salida .= "      </tr>";
                            }
                            if(!empty($Garantes))
                            {
                                    $this->salida .= "      <tr>";
                                    $this->salida .= "      <td class=\"label\" width=\"20%\">GARANTE: </td>";
                                    $this->salida .= "      <td>";
                                    $this->salida .= "      <table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"normal_10\">";
                                    for($i=0; $i<sizeof($Garantes); $i++)
                                    {
                                                    $this->salida .= "          <tr class=\"label\">";
                                                    $this->salida .= "              <td><LI>".$Garantes[$i][primer_nombre_garante]." ". $Garantes[$i][primer_apellido_garante]."</LI></td>";
                                                    $this->salida .= "          </tr>";
                                    }
                                    $this->salida .= "  </table>";
                                    $this->salida .= "      </td>";
                                    $this->salida .= "      </tr>";
                            }
                            if($acu)
                            {
                                    $this->salida .= "      <tr>";
                                    $this->salida .= "      <td class=\"label\" width=\"20%\">ACUDIENTE: </td>";
                                    $this->salida .= "      <td>";
                                    $this->salida .= "      <table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"normal_10\">";
                                    for($i=0; $i<sizeof($acu); $i++)
                                    {
                                                    $this->salida .= "          <tr class=\"label\">";
                                                    $this->salida .= "              <td><LI>".$acu[$i][nombre_completo]."</LI></td>";
                                                    $this->salida .= "          </tr>";
                                    }
                                    $this->salida .= "  </table>";
                                    $this->salida .= "      </td>";
                                    $this->salida .= "      </tr>";
                            }
                            $this->salida .= "  </table>";
                    }
        }

//--------------------------------EMPLEADOR------------------------------

    function FormaEmpleador()
    {
            IncludeLib('funciones_admision');
            $ru='classes/BuscadorDestino/selectorCiudad.js';
            $rus='classes/BuscadorDestino/selector.php';
            $this->salida .= "<script languaje='javascript' src=\"$ru\"></script>";
            $this->salida .= ThemeAbrirTabla('ADMISIONES - DATOS EMPLEADOR');
            //mensaje
            $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "  </table>";
            $accion=ModuloGetURL('app','Admisiones','user','GuardarEmpleador');
      $this->salida .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";
            $this->salida .= "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"50%\" align=\"center\">";
            $vars=$this->BuscarEmpleadores();
            if(!empty($vars))
            {
                    $this->salida .= "         <br>";
                    $this->salida .= "      <table width=\"50%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
                    $this->salida .= "          <tr align=\"center\" class=\"modulo_table_list_title\">";
                    $this->salida .= "              <td>EMPLEADOR ASIGNADO AL PACIENTE</td>";
                    $this->salida .= "              <td width=\"3%\"></td>";
                    $this->salida .= "          </tr>";
                    for($i=0; $i<sizeof($vars); $i++)
                    {
                                    if( $i % 2) $estilo='modulo_list_claro';
                                    else $estilo='modulo_list_oscuro';
                                    $this->salida .= "          <tr class=\"$estilo\">";
                                    $this->salida .= "              <td>".$vars[$i][nombre]."</td>";
                                    $accion2=ModuloGetURL('app','Admisiones','user','EliminarEmpleador');
                                    $this->salida .= "              <td align=\"center\"><a href='$accion2'><img src=\"".GetThemePath()."/images/elimina.png\"  border='0' alt='ELIMINAR'></a></td>";
                                    $this->salida .= "          </tr>";
                    }
                    $this->salida .= "  </table>";
            }
            else
            {
                    $emp=$this->Empleadores();
                    if(!empty($emp))
                    {
                            $this->salida .= " <tr><td colspan=\"2\" class=\"label_mark\" align=\"center\">EMPLEADORES EXISTENTES</td></tr>";
                            $this->salida .= " <tr>";
                            $this->salida .= "  <td class=\"".$this->SetStyle("empleador")."\" width=\"30%\">EMPLEADORES :</td>";
                            $this->salida .= "  <td><select name=\"empleador\" class=\"select\">";
                            $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
                            for($i=0; $i<sizeof($emp); $i++)
                            {
                                    if($emp[$i][tipo_id_empleador]."||".$emp[$i][empleador_id]==$_REQUEST[empelador])
                                    {   $this->salida .=" <option value=\"".$emp[$i][tipo_id_empleador]."||".$emp[$i][empleador_id]."\" selected>".$emp[$i][nombre]."</option>";  }
                                    else
                                    {   $this->salida .=" <option value=\"".$emp[$i][tipo_id_empleador]."||".$emp[$i][empleador_id]."\">".$emp[$i][nombre]."</option>";   }
                            }
                            $this->salida .= "  </select></td>";
                            $this->salida .= " </tr>";
                    }
                    $this->salida .= " <tr><td colspan=\"2\" class=\"label_mark\" align=\"center\"><br>DATOS PARA NUEVO EMPLEADOR</td></tr>";
                    $this->salida .= " <tr>";
                    $this->salida .= "  <td class=\"".$this->SetStyle("tipoID")."\" width=\"30%\">TIPO IDENTIFICACION :</td>";
                    $this->salida .= "  <td><select name=\"tipoID\" class=\"select\">";
                    $var=TipoIdTerceros();
                    $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
                    for($i=0; $i<sizeof($var); $i++)
                    {
                            if($var[$i][tipo_id_tercero]==$_REQUEST['tipoID'])
                            {   $this->salida .=" <option value=\"".$var[$i][tipo_id_tercero]."\" selected>".$var[$i][descripcion]."</option>";  }
                            else
                            {   $this->salida .=" <option value=\"".$var[$i][tipo_id_tercero]."\">".$var[$i][descripcion]."</option>";   }
                    }
                    $this->salida .= "  </select></td>";
                    $this->salida .= " </tr>";
                    $this->salida .= " <tr>";
                    $this->salida .= "  <td class=\"".$this->SetStyle("numero")."\">NUMERO :</td>";
                    $this->salida .= "  <td><input type=\"text\" name=\"numero\" value=\"".$_REQUEST['numero']."\" class=\"input-text\"></td>";
                    $this->salida .= " </tr>";
                    $this->salida .= " <tr>";
                    $this->salida .= "  <td class=\"".$this->SetStyle("nombre")."\">NOMBRE :</td>";
                    $this->salida .= "  <td><input type=\"text\" name=\"nombre\" value=\"".$_REQUEST['nombre']."\" class=\"input-text\"></td>";
                    $this->salida .= " </tr>";
                    $this->salida .= " <tr>";
                    $this->salida .= "  <td class=\"".$this->SetStyle("direccion")."\">DIRECCION :</td>";
                    $this->salida .= "  <td><input type=\"text\" name=\"direccion\" value=\"".$_REQUEST['direccion']."\" class=\"input-text\"></td>";
                    $this->salida .= " </tr>";
                    $this->salida .= " <tr>";
                    $this->salida .= "  <td class=\"".$this->SetStyle("telefono")."\">TELEFONO :</td>";
                    $this->salida .= "  <td><input type=\"text\" name=\"telefono\" value=\"".$_REQUEST['telefono']."\" class=\"input-text\"></td>";
                    $this->salida .= " </tr>";
                    $this->salida .= "    <tr>";
                    $this->salida .= "      <td class=\"".$this->SetStyle("pais")."\">* PAIS: </td>";
                    $this->salida .= "      <td><input type=\"text\" name=\"npais\" value=\"".$_REQUEST['npais']."\" class=\"input-text\" readonly>";
                    $this->salida .= "      <input type=\"hidden\" name=\"pais\" value=\"".$_REQUEST['pais']."\" class=\"input-text\"></td>";
                    $this->salida .= "    </tr>";
                    $this->salida .= "    <tr>";
                    $this->salida .= "      <td class=\"".$this->SetStyle("dpto")."\">* DEPARTAMENTO: </td>";
                    $this->salida .= "      <td><input type=\"text\" name=\"ndpto\" value=\"".$_REQUEST['ndpto']."\" class=\"input-text\" readonly>";
                    $this->salida .= "      <input type=\"hidden\" name=\"dpto\" value=\"".$_REQUEST['dpto']."\" class=\"input-text\"></td>";
                    $this->salida .= "    </tr>";
                    $this->salida .= "    <tr>";
                    $this->salida .= "      <td class=\"".$this->SetStyle("mpio")."\">* CIUDAD: </td>";
                    $this->salida .= "      <td><input type=\"text\" name=\"nmpio\"  value=\"".$_REQUEST['nmpio']."\" class=\"input-text\" readonly>";
                    $this->salida .= "       <input type=\"hidden\" name=\"mpio\" value=\"".$_REQUEST['mpio']."\" class=\"input-text\" >";
                    $this->salida .= "      <input type=\"hidden\" name=\"comuna\" value=\"\">";
                    $this->salida .= "      <input type=\"hidden\" name=\"barrio\" value=\"\">";
                    $this->salida .= "       <input class=\"input-submit\" type=\"button\" name=\"Cambiar\" value=\"Cambiar\" onclick=\"abrirVentana('Buscador_Destino','$rus',450,200,0,this.form,1)\"></td>";
                    $this->salida .= "    </tr>";
                    $this->salida .= "</table><br>";
            }
            $this->salida .= "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"30%\" align=\"center\">";
            $this->salida .= "    <tr>";
            if(empty($vars))
            {
                    $this->salida .= "       <td><input class=\"input-submit\" type=\"submit\" name=\"Cambiar\" value=\"ACEPTAR\"></td>";
            }
            $this->salida .= "      </form>";
            $contenedor=$_SESSION['EMPLEADOR']['RETORNO']['contenedor'];
            $modulo=$_SESSION['EMPLEADOR']['RETORNO']['modulo'];
            $tipo=$_SESSION['EMPLEADOR']['RETORNO']['tipo'];
            $metodo=$_SESSION['EMPLEADOR']['RETORNO']['metodo'];
            $argumentos=$_SESSION['EMPLEADOR']['RETORNO']['argumentos'];
            $accionCancelar=ModuloGetURL($contenedor,$modulo,$tipo,$metodo,$argumentos);
            $this->salida .= "  <form name=\"formagarantes\" action=\"$accionCancelar\" method=\"post\">";
            $this->salida .= "      <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"CANCELAR\"></td>";
            $this->salida .= "      </form>";
            $this->salida .= "      </tr>";
            $this->salida .= "</table>";
            $this->salida .= ThemeCerrarTabla();
            return true;
    }


    function FormaConsultorios()
    {
                IncludeLib("funciones_admision");
                $this->salida .= ThemeAbrirTabla('ADMISIONES - ELEGIR CONSULTORIO');
                $accion=ModuloGetURL('app','Admisiones','user','LlamarIngresoCons');
                $this->salida .= "                <br><table width=\"50%\" align=\"center\" border=0>";
                $this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
                $this->salida .= $this->SetStyle("MensajeError");
                $this->salida .= "                     <tr><td width=\"30%\" class=\"".$this->SetStyle("consultorio")."\">ELIJA EL CONSULTORIO: </td>";
                $this->salida .= "                     <td colspan=\"2\"><select name=\"consultorio\" class=\"select\">";
                $cons = BuscarConsultoriosEstacion($_SESSION['ADMISIONES']['PACIENTE']['estacion_id']);
                $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
                for($i=0; $i<sizeof($cons); $i++)
                {
                        $prof='';
                        if(!empty($cons[$i][descripcion2]))
                        {   $prof=' - '.$cons[$i][descripcion2];  }
                        $this->salida .=" <option value=\"".$cons[$i][paciente_urgencia_consultorio_id]."\">".$cons[$i][descripcion]." ".$prof."</option>";
                }
                $this->salida .= "              </select></td></tr>";
                $this->salida .= "                     <tr>";
                $this->salida .= "                     <tr>";
                $this->salida .= "                     <td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"></td>";
                $this->salida .= "               </form>";
                if(!empty($_SESSION['ADMISIONES']['RETORNO']))
                {
                        $_SESSION['ADMISIONES']['RETORNO']['CANCELAR']=true;
                        $Contenedor=$_SESSION['ADMISIONES']['RETORNO']['contenedor'];
                        $Modulo=$_SESSION['ADMISIONES']['RETORNO']['modulo'];
                        $Tipo=$_SESSION['ADMISIONES']['RETORNO']['tipo'];
                        $Metodo=$_SESSION['ADMISIONES']['RETORNO']['metodo'];
                        $arg=$_SESSION['ADMISIONES']['RETORNO']['argumentos'];
                        $accion=ModuloGetURL($Contenedor,$Modulo,$Tipo,$Metodo,$arg);
                }
                else
                {   $accion=ModuloGetURL('app','Admisiones','user','BuscarPaciente',array('TipoDocumento'=>$_SESSION['ADMISIONES']['BUSQUEDA']['tipo_id_paciente'],'Documento'=>$_SESSION['ADMISIONES']['BUSQUEDA']['paciente_id'],'Nombres'=>$_SESSION['ADMISIONES']['BUSQUEDA']['Nombres'],'prefijo'=>$_SESSION['ADMISIONES']['BUSQUEDA']['prefijo'],'historia'=>$_SESSION['ADMISIONES']['BUSQUEDA']['historia']));  }
                $this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
                $this->salida .= "                     <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"CANCELAR\"></td>";
                $this->salida .= "              </tr>";
                $this->salida .= "               </form>";
                $this->salida .= "               </table>";
                $this->salida .= ThemeCerrarTabla();
                return true;
    }
//----------------------------------------------------------------------------------------------------

}//fin clase

?>

