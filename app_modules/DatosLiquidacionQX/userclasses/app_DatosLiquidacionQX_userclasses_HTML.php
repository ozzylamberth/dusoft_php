<?php

/**
 * $Id: app_DatosLiquidacionQX_userclasses_HTML.php,v 1.52 2007/10/01 21:02:55 carlos Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * MODULO para el Manejo de Inventario del Sistema
 */

/**
*Contiene los metodos visuales para realizar la administracion de los Inventario de la clinica
*/
IncludeClass("ClaseHTML");
class app_DatosLiquidacionQX_userclasses_HTML extends app_DatosLiquidacionQX_user
{
    /**
    *Constructor de la clase app_Inventarios_user_HTML
    *El constructor de la clase app_Inventarios_user_HTML se encarga de llamar
    *a la clase app_Inventarios_user que se encarga del tratamiento
    * de la base de datos.
    */

  function app_DatosLiquidacionQX_user_HTML()
    {
        $this->salida='';
        $this->app_DatosLiquidacionQX_user();
        return true;
    }

    /**
    * Function que muestra al usuario la diferentes bodegas, la empresa y el centro de utilidad

    * al que pertenecen y en las que el usuario tiene permiso de trabajar
    * @return boolean
    */

    function FrmLogueoDepartamento(){

    $Empresas=$this->LogueoDepartamento();
        if(sizeof($Empresas)>0){
            $url[0]='app';
            $url[1]='DatosLiquidacionQX';
            $url[2]='user';
            $url[3]='LlamaFormaMenu';
            $url[4]='datos_query';
            $this->salida .= gui_theme_menu_acceso("SELECCION DEL DEPARTAMENTO PARA LIQUIDACION DE LA CIRUGIA",$Empresas[0],$Empresas[1],$url,ModuloGetURL('system','Menu'));
        }else{
      $mensaje = "EL USUARIO NO TIENE PERMISOS PARA ACCESAR A NINGUN DEPARTAMENTO.";
            $titulo = "LIQUIDACION QX";
            $boton = "";//REGRESAR
            $accion="";
            $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
            return true;
        }
        return true;
    }

    function Encabezado(){
    $this->salida .= "    <table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" align=\"center\"><b>EMPRESA</b></td>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" align=\"center\"><b>DEPARTAMENTO</b></td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td class=\"modulo_list_claro\" align=\"center\"><b>".$_SESSION['LIQUIDACION_QX']['NombreEmp']."</b></td>";
    $this->salida .= "      <td class=\"modulo_list_claro\" align=\"center\"><b>".$_SESSION['LIQUIDACION_QX']['NombreDpto']."</b></td>";
    $this->salida .= "      </table><BR>";
        return true;
    }


  function FormaMenu(){

        $this->salida .= ThemeAbrirTabla('MENU LIQUIDACION QX');
        $actionMenu=ModuloGetURL('app','DatosLiquidacionQX','user','FrmLogueoDepartamento');
        $this->salida .= "    <form name=\"forma\" action=\"$actionMenu\" method=\"post\">";
        $this->Encabezado();
        $this->salida .= "    <table border=\"0\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
    $action=ModuloGetURL('app','DatosLiquidacionQX','user','LlamaSolicitudIdPaciente');
        $this->salida .= "    <tr><td class=\"modulo_table_list_title\" align=\"center\">MENU</td></tr>";
    $this->salida .= "    <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$action\" ><b>LIQUIDACION</b></a></td></tr>";
        $this->salida .= "      </table><BR>";
    $this->salida .= "    <table border=\"0\" width=\"40%\" align=\"center\">";
    $this->salida .= "    <tr><td align=\"center\"><input type=\"submit\" class=\"input-submit\" value=\"VOLVER\"></td></tr>";
    $this->salida .= "    </table>";
    $this->salida .= "      </form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

  function DatosPacientes($TipoDocumento,$Documento,$NoIngreso,$NoCuenta,$Estado,$FechaCirugia){
    $this->paginaActual = 1;
    $this->offset = 0;
    if($_REQUEST['offset']){
      $this->paginaActual = intval($_REQUEST['offset']);
      if($this->paginaActual > 1){
        $this->offset = ($this->paginaActual - 1) * ($this->limit);
      }
    }

      unset($_SESSION['PACIENTES']);

        $this->salida .= ThemeAbrirTabla('LIQUIDACIONES DE CIRUGIA');
    $action=ModuloGetURL('app','DatosLiquidacionQX','user','LlamaDatosRequeridosLiquidacion');
        $this->salida .= "<form name=\"forma\" action=\"$action\" method=\"post\">";
        $this->salida .= "<table width=\"90%\" align=\"center\" border=\"0\">\n";
        $this->salida .= "<tr><td colspan=\"2\" align=\"center\">";
        $this->salida .=      $this->SetStyle("MensajeError");
        $this->salida .= "</td></tr>";
        $this->salida .= "<tr>";
    $this->salida .= "<td width=\"50%\" valign=\"top\" class=\"modulo_list_oscuro\">";
        $this->salida .= "     <BR><table width=\"98%\" border=\"0\" align=\"center\" class=\"normal_10\" >";
    $this->salida .= "        <tr class=\"modulo_table_list_title\"><td colspan=\"2\" class=\"modulo_table_list_title\">FILTRO DE BUSQUEDA</td></tr>";
    $this->salida .= "        <tr class=\"modulo_list_claro\">";
    $this->salida .= "        <td class=\"label\">FECHA CIRUGIA</td>";
    $this->salida .= "        <td><input size=\"10\" type=\"text\" name=\"FechaCirugia\" value=\"$FechaCirugia\" class=\"input-text\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\">";
        $this->salida .= "      &nbsp&nbsp&nbsp;".ReturnOpenCalendario('forma','FechaCirugia','/')."</td>";
    $this->salida .= "        </tr>";
    $this->salida .= "        <tr class=\"modulo_list_claro\">";
    $this->salida .= "        <td class=\"label\">ESTADO PROCEDIMIENTOS</td>";
    $this->salida .= "        <td><select name=\"Estado\" class=\"select\">";
    if($Estado=='1'){$sel1='selected';}elseif($Estado=='2' OR empty($Estado)){$sel2='selected';}elseif($Estado=='3'){$sel3='selected';}elseif($Estado=='4'){$sel4='selected';}elseif($Estado=='5'){$sel5='selected';}    
    $this->salida .="     <option value=\"2\" $sel2>PENDIENTES POR LIQUIDAR</option>";
    $this->salida .="     <option value=\"3\" $sel3>LIQUIDADOS PENDIENTES POR CARGAR A LA CTA.</option>";
    $this->salida .="     <option value=\"4\" $sel4>CARGADOS A LA CTA. (ACTIVA)</option>";
    $this->salida .="     <option value=\"5\" $sel5>ELIMINADAS</option>";
    $this->salida .="     <option value=\"1\" $sel1>TODOS</option>";
    $this->salida .= "      </select></td>";
    $this->salida .= "        </tr>";
    $this->salida .= "    </table>";
    $this->salida .= "</td>";
    $this->salida .= "<td width=\"50%\" class=\"modulo_list_oscuro\">";
    $this->salida .= "     <BR><table width=\"98%\" border=\"0\" align=\"center\" class=\"normal_10\" >";
    $this->salida .= "        <tr class=\"modulo_table_list_title\"><td colspan=\"2\" class=\"modulo_table_list_title\">DATOS PACIENTE</td></tr>";
    $this->salida .= "        <tr class=\"modulo_list_claro\">";
    $this->salida .= "        <td class=\"label\">No. INGRESO</td>";
    $this->salida .= "        <td><input type=\"text\" size=\"8\" name=\"NoIngreso\" value=\"$NoIngreso\" class=\"input-submit\"></td>";
    $this->salida .= "        </tr>";
    $this->salida .= "        <tr class=\"modulo_list_claro\">";
    $this->salida .= "        <td class=\"label\">No. CUENTA</td>";
    $this->salida .= "        <td><input type=\"text\" size=\"8\" name=\"NoCuenta\" value=\"$NoCuenta\" class=\"input-submit\"></td>";
    $this->salida .= "        </tr>";
        $this->salida .= "        <tr class=\"modulo_list_claro\"><td class=\"label\">TIPO DOCUMENTO </td><td><select name=\"TipoDocumento\" class=\"select\">";
        $tipos=$this->tipo_id_paciente();
        foreach($tipos as $value=>$titulo){
            if($value==$TipoDocumento){
                $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
            }else{
                $this->salida .=" <option value=\"$value\">$titulo</option>";
            }
        }
        $this->salida .= "      </select></td>";
        $this->salida .= "        </tr>";
        $this->salida .= "        <tr class=\"modulo_list_claro\">";
        $this->salida .= "        <td class=\"".$this->SetStyle("Documento")."\">DOCUMENTO </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" size=\"32\" maxlength=\"32\" value=\"$Documento\"></td>";
        $this->salida .= "        </tr>";
    $this->salida .= "     </table><BR>";
    $this->salida .= "</td>";
    $this->salida .= "</tr>";
    $this->salida .= "<tr class=\"modulo_list_oscuro\"><td align=\"center\" colspan=\"2\">";
    $this->salida .= "  <table width=\"98%\" border=\"0\" align=\"center\" class=\"normal_10\" >";
    $this->salida .= "  <tr class=\"modulo_list_oscuro\">";
    $this->salida .= "  <td align=\"right\"><input type=\"submit\" class=\"input-submit\" name=\"Buscar\" value=\"BUSCAR\"></td>";
    $this->salida .= "</form>";
    $action1=ModuloGetURL('app','DatosLiquidacionQX','user','FormaMenu');
        $this->salida .= "  <form name=\"forma\" action=\"$action1\" method=\"post\">";
    $this->salida .= "  <td align=\"left\"><input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\"></td>";
    $this->salida .= "  </form>";
    $this->salida .= "  </table>";
    $this->salida .= "</td></tr>";
    $this->salida .= "</table>";
        if($TipoDocumento!=-1 && !empty($TipoDocumento) && !empty($Documento)){

            $programaciones=$this->ProgramacionesQXPendientes($TipoDocumento,$Documento);
            if($programaciones){
                foreach($programaciones['vector'] as $numero_programacion=>$vector){
                    $this->salida .= "    <BR><table cellspacing=\"2\"  cellpadding=\"3\"border=\"1\" width=\"100%\" align=\"center\">";
                    $this->salida .= "    <tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida .= "          <td width=\"25%\" nowrap valign=\"center\">";
                    $this->salida .= "                      <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\" class=\"normal_10\">";
                    $actionCancel=ModuloGetURL('app','DatosLiquidacionQX','user','ProcesoCancelarLaProgramacion',array("programacion"=>$numero_programacion));
                    $this->salida .= "                      <tr><td colspan=\"2\" align=\"center\">No. $numero_programacion&nbsp;&nbsp;&nbsp;<a href=\"$actionCancel\"><img title=\"Cancelar Programacion\" border = 0 src=\"".GetThemePath()."/images/checksi.png\"></a></td></tr>";
                    $this->salida .= "                      <tr><td colspan=\"2\" align=\"center\">".$programaciones['datos_programacion'][$numero_programacion][tipo_id_paciente]." ".$programaciones['datos_programacion'][$numero_programacion][paciente_id]."</td></tr>";
                    $this->salida .= "                      <tr><td colspan=\"2\" align=\"center\">".$programaciones['datos_programacion'][$numero_programacion][nombre]."</td></tr>";
                    $this->salida .= "                      <tr><td width=\"40%\">SALA:</td><td>".$programaciones['datos_programacion'][$numero_programacion][quirofano]."</td></tr>";
                    (list($fechaIn,$horaIn)=explode(' ',$programaciones['datos_programacion'][$numero_programacion][hora_inicio]));
                    (list($anoIn,$mesIn,$diaIn)=explode('-',$fechaIn));
                    (list($hhIn,$mmIn)=explode(':',$horaIn));
                    (list($fechaFn,$horaFn)=explode(' ',$programaciones['datos_programacion'][$numero_programacion][hora_fin]));
                    (list($anoFn,$mesFn,$diaFn)=explode('-',$fechaFn));
                    (list($hhFn,$mmFn)=explode(':',$horaFn));
                    $segundos=(mktime($hhFn,$mmFn+1,0,$mesFn,$diaFn,$anoFn)-mktime($hhIn,$mmIn,0,$mesIn,$diaIn,$anoIn))/60;
                    $Horas=(int)($segundos/60);
                    $Minutos=($segundos%60);
                    $this->salida .= "                      <tr><td width=\"40%\">HORA INICIO:</td><td>".$fechaIn." ".$hhIn.":".$mmIn."</td></tr>";
                    $this->salida .= "                      <tr><td width=\"40%\">DURACION:</td><td>".str_pad($Horas,2,0,STR_PAD_LEFT).":".str_pad($Minutos,2,0,STR_PAD_LEFT)."&nbsp;&nbsp;&nbsp;(HH:mm)</td></tr>";
                    $this->salida .= "                      </table>";
                    $this->salida .= "          </td>";
                    $this->salida .= "          <td valign=\"top\">";
                    foreach($vector as $plan_id=>$vector1){
                        $this->salida .= "              <table cellspacing=\"2\"  cellpadding=\"3\"border=\"1\" width=\"100%\" align=\"center\" class=\"normal_10\">";
                        $this->salida .= "              <tr>";
                        $this->salida .= "                      <td width=\"70%\">";
                        $this->salida .= "                          <table cellspacing=\"2\"  cellpadding=\"3\"border=\"1\" width=\"100%\" align=\"center\" class=\"normal_10\">";
                        $this->salida .= "                          <tr><td class=\"modulo_table_list_title\" align=\"left\" colspan=\"2\">".$programaciones['datos_planes'][$numero_programacion][$plan_id][plan_descripcion]."</td></tr>";
                        foreach($vector1 as $cirujano=>$vector2){
                            $this->salida .= "                          <tr>";
                            $this->salida .= "                          <td width=\"20%\" class=\"modulo_table_title\">ESPECIALISTA</td><td>".$programaciones['datos_cirujanos'][$numero_programacion][$plan_id][$cirujano][cirujano]."</td>";
                            $this->salida .= "                          </tr>";
                            $this->salida .= "                          <tr>";
                            $this->salida .= "                          <td width=\"20%\" class=\"modulo_table_title\">PROCEDIMIENTOS</td>";
                            $this->salida .= "                          <td>";
                            $this->salida .= "                          <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\" class=\"normal_10\">";
                            foreach($vector2 as $procedimiento=>$datosTotal){
                                $this->salida .= "                          <tr><td>$procedimiento</td><td>".$datosTotal['descripcion']."</td></tr>";
                            }
                            $this->salida .= "                              </table>";
                            $this->salida .= "                          </td>";
                            $this->salida .= "                          </tr>";
                        }

                        $this->salida .= "                              </table>";
                        $this->salida .= "                      </td>";
                        $this->salida .= "                      <td width=\"30%\" align=\"center\">";
                        $this->salida .= "                          <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\" class=\"normal_10\">";
                        if(empty($programaciones['datos_planes'][$numero_programacion][$plan_id][cuenta_liquidacion_qx_id])){
                            if(!empty($programaciones['datos_planes'][$numero_programacion][$plan_id][numerodecuenta])){
                                $this->salida .= "                          <tr><td align=\"center\">No. Cuenta ".$programaciones['datos_planes'][$numero_programacion][$plan_id][numerodecuenta]."</td></tr>";
                                $this->salida .= "                          <tr><td align=\"center\">";
                                $action=ModuloGetURL('app','DatosLiquidacionQX','user','VariablesLiquidacionCirugia',array("programacion_id"=>$numero_programacion,"plan_id"=>$plan_id,"numerodecuenta"=>$programaciones['datos_planes'][$numero_programacion][$plan_id][numerodecuenta],"TipoDocumentoBus"=>$TipoDocumento,"DocumentoBus"=>$Documento,"NoIngresoBus"=>$NoIngreso,"NoCuentaBus"=>$NoCuenta,"EstadoBus"=>$Estado,"FechaCirugiaBus"=>$FechaCirugia));
                                $this->salida .= "                          <a href=\"$action\"><img title=\"Guardar Datos para la Liquidacion\" border = 0 src=\"".GetThemePath()."/images/cargar.png\"></a>";
                                $canastaInsumos=$this->InsumosPendientesCanasta($numero_programacion);
                                if($canastaInsumos){
                                    $actionIn=ModuloGetURL('app','DatosLiquidacionQX','user','CargaInsumosMedicamentosCuenta',array("TipoDocumento"=>$programaciones['datos_programacion'][$numero_programacion][tipo_id_paciente],"Documento"=>$programaciones['datos_programacion'][$numero_programacion][paciente_id],"nombrePaciente"=>$programaciones['datos_programacion'][$numero_programacion][nombre],"cuenta"=>$programaciones['datos_planes'][$numero_programacion][$plan_id][numerodecuenta],"ingreso"=>$programaciones['datos_programacion'][$numero_programacion][ingreso],"programacionId"=>$numero_programacion));
                                    $this->salida .= "                          &nbsp;&nbsp;&nbsp;&nbsp;<a href=\"$actionIn\"><img title=\"Cargar Insumos no QX a la Cuenta\" border = 0 src=\"".GetThemePath()."/images/pparamed.png\"></a>";
                                }
                                $this->salida .= "                          </td></tr>";
                            }else{
                                $this->salida .= "                          <tr><td align=\"center\">Plan sin cuenta Activa</td></tr>";
                            }
                        }else{
                            $this->salida .= "                          <tr><td align=\"center\" class=\"label_mark\">No. Liquidacion ".$programaciones['datos_planes'][$numero_programacion][$plan_id][cuenta_liquidacion_qx_id]."</td></tr>";
                        }
                        $this->salida .= "                              </table>";
                        $this->salida .= "              </tr>";
                        $this->salida .= "                  </table>";
                    }
                    $this->salida .= "          </td>";
                    $this->salida .= "    </tr>";
                    $RegistroNotas=$this->RegistroNotasPaciente($numero_programacion,$programaciones['datos_programacion'][$numero_programacion][tipo_id_paciente],$programaciones['datos_programacion'][$numero_programacion][paciente_id]);
                    if($RegistroNotas){
                        //imprimir nota operatoria
                        $this->salida .= "              <tr class=\"hc_table_submodulo_list_title\">";
                        $rep= new GetReports();
                        $mostrar=$rep->GetJavaReport('app','DatosLiquidacionQX','reporteNotaOperatoria_html',array('programacion'=>$numero_programacion,'tipoidpaciente'=>$programaciones['datos_programacion'][$numero_programacion][tipo_id_paciente],'paciente'=>$programaciones['datos_programacion'][$numero_programacion][paciente_id],"ingreso"=>$programaciones['datos_programacion'][$numero_programacion][ingreso]),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
                        $nombre_funcion=$rep->GetJavaFunction();
                        $this->salida .=$mostrar;
                        $this->salida .= "                            <td colspan=\"2\" width=\"20%\"><a class=\"Menu\" href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/traslado.png\" border='0'>&nbsp&nbsp&nbsp;IMPRIMIR NOTA OPERATORIA</a></td>";
                        $this->salida .= "              </tr>";
                        //fin imprimir
                    }

                    $this->salida .= "   </table>";
                }
                /*$this->salida .= "      <BR><table cellspacing=\"2\"  cellpadding=\"3\"border=\"1\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
                $this->salida .= "    <tr>";
                $this->salida .= "          <td rowspan=\"4\" valign=\"center\" class=\"hc_table_submodulo_list_title\" align=\"center\" width=\"30%\">- ".$programaciones[0]['programacion_id']." -</BR></BR>".$programaciones[0]['nombre']."</td>\n";
                if($programaciones[0]['nombre_tercero']){
                $this->salida .= "          <td class=\"modulo_list_claro\">".$programaciones[0]['nombre_tercero']."</td>\n";
                }else{
                $this->salida .= "          <td class=\"modulo_table_title\" width=\"15%\">CIRUJANO</td><td class=\"modulo_list_claro\">SIN ASIGNAR</td>\n";
                }
                $action=ModuloGetURL('app','DatosLiquidacionQX','user','VariablesLiquidacionCirugia',array("programacion_id"=>$programaciones[0]['programacion_id'],"TipoDocumentoBus"=>$TipoDocumento,"DocumentoBus"=>$Documento,"NoIngresoBus"=>$NoIngreso,"NoCuentaBus"=>$NoCuenta,"EstadoBus"=>$Estado,"FechaCirugiaBus"=>$FechaCirugia));
                $this->salida .= "          <td rowspan=\"4\" valign=\"center\" class=\"modulo_list_claro\" align=\"center\" width=\"5%\"><a href=\"$action\"><img title=\"\" border = 0 src=\"".GetThemePath()."/images/cargar.png\"></a></td>\n";
                $this->salida .= "       </tr>\n";
                $this->salida .= "    <tr>";
                $this->salida .= "          <td class=\"modulo_table_title\" width=\"15%\">QUIROFANO</td><td class=\"modulo_list_claro\">".$programaciones[0]['quirofano']."</td>\n";
                $this->salida .= "       </tr>\n";
                $this->salida .= "    <tr>";
                (list($fechaIn,$horaIn)=explode(' ',$programaciones[0]['hora_inicio']));
                (list($anoIn,$mesIn,$diaIn)=explode('-',$fechaIn));
                (list($hhIn,$mmIn)=explode(':',$horaIn));
                (list($fechaFn,$horaFn)=explode(' ',$programaciones[0]['hora_fin']));
                (list($anoFn,$mesFn,$diaFn)=explode('-',$fechaFn));
                (list($hhFn,$mmFn)=explode(':',$horaFn));
                $segundos=(mktime($hhFn,$mmFn,0,$mesFn,$diaFn,$anoFn)-mktime($hhIn,$mmIn,0,$mesIn,$diaIn,$anoIn))/60;
                $Horas=(int)($segundos/60);
                $Minutos=($segundos%60);
                $this->salida .= "          <td class=\"modulo_table_title\" width=\"15%\">HORA</td><td class=\"modulo_list_claro\">".$programaciones[0]['hora_inicio']."&nbsp&nbsp&nbsp;<label class=\"label\">DURACION (HH:mm): &nbsp&nbsp&nbsp;</label>".str_pad($Horas,2,0,STR_PAD_LEFT).":".str_pad($Minutos,2,0,STR_PAD_LEFT)."</td>\n";
                $this->salida .= "       </tr>\n";

                $this->salida .= "    <tr>";
                $this->salida .= "          <td colspan=\"2\" class=\"modulo_list_claro\">";
                $this->salida .= "              <table cellspacing=\"1\"  cellpadding=\"1\"border=\"0\" width=\"100%\" align=\"center\" class=\"normal_10\">";
                $this->salida .= "              <tr>";
                $this->salida .= "                  <td class=\"modulo_table_title\">PROCEDIMIENTOS</td>\n";
                $this->salida .= "                  </tr>\n";
                for($i=0;$i<sizeof($programaciones[1]);$i++){
                    $this->salida .= "              <tr><td>";
                    $this->salida .= "              ".$programaciones[1][$i]['procedimiento_qx']." ".$programaciones[1][$i]['descripcion']."";
                    $this->salida .= "              </td></tr>";
                }
                $this->salida .= "              </table>";
                $this->salida .= "          </td>\n";
                $this->salida .= "       </tr>\n";
                $this->salida .= "   </table>";*/
            }

    }
    $datos=$this->BuscarDatosLiquidaciones($TipoDocumento,$Documento,$NoIngreso,$NoCuenta,$Estado,$FechaCirugia);
    if($datos){
      $this->salida .= "      <BR><table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
      $this->salida .= "    <tr>";
      $this->salida .= "            <td width=\"5%\" class=\"modulo_table_list_title\">No.</td>\n";
      $this->salida .= "            <td class=\"modulo_table_list_title\">PACIENTE</td>\n";
      $this->salida .= "          <td width=\"15%\" class=\"modulo_table_list_title\">PLAN</td>\n";
      $this->salida .= "            <td width=\"10%\" class=\"modulo_table_list_title\">FECHA CIRUGIA</td>\n";
      $this->salida .= "            <td width=\"10%\" class=\"modulo_table_list_title\">DURACION (H:m)</td>\n";
      //$this->salida .= "            <td width=\"15%\" class=\"modulo_table_list_title\">VIA ACCESO</td>\n";
      $this->salida .= "            <td width=\"10%\" class=\"modulo_table_list_title\">ESTADO</td>\n";
      $this->salida .= "            <td width=\"3%\" class=\"modulo_table_list_title\">&nbsp;</td>\n";
      $this->salida .= "            <td width=\"3%\" class=\"modulo_table_list_title\">&nbsp;</td>\n";
      //      if($_SESSION['LIQUIDACION_QX']['CargueIyM']=='1'){
      //  $this->salida .= "          <td width=\"3%\" class=\"modulo_table_list_title\">&nbsp;</td>\n";
      //      }
      $this->salida .= "            <td width=\"3%\" class=\"modulo_table_list_title\">&nbsp;</td>\n";
      $this->salida .= "            <td width=\"3%\" class=\"modulo_table_list_title\">&nbsp;</td>\n";
            $this->salida .= "          <td width=\"3%\" class=\"modulo_table_list_title\">&nbsp;</td>\n";
      $this->salida .= "         </tr>\n";
      $y=0;
	if(!is_object($rep))
      	$rep= new GetReports();  
      for($i=0;$i<sizeof($datos);$i++){
        
        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
        $this->salida .= "   <tr class=\"$estilo\">\n";
        $this->salida .= "   <td>".$datos[$i]['cuenta_liquidacion_qx_id']."</td>";
        $this->salida .= "   <td>".$datos[$i]['tipo_id_paciente']." ".$datos[$i]['paciente_id']." - ".$datos[$i]['nombre']."</td>";
        $this->salida .= "   <td>".$datos[$i]['plan_descripcion']."</td>";
        (list($fecha,$HoraTot)=explode(' ',$datos[$i]['fecha_cirugia']));
        (list($ano,$mes,$dia)=explode('-',$fecha));
        (list($Hora,$Min)=explode(':',$HoraTot));
        //".strftime("%b %d de %Y %H:%M",mktime($Hora,$Min,0,$mes,$dia,$ano))."
        $this->salida .= "   <td>".$ano."-".$mes."-".$dia." ".$Hora.":".$Min."</td>";
        (list($Hora,$Min)=explode(':',$datos[$i]['duracion_cirugia']));
        $this->salida .= "   <td>".$Hora.":".$Min."</td>";
        //$this->salida .= "   <td>".$datos[$i]['via']."</td>";
        if($datos[$i]['estado']=='0'){
          $this->salida .= "     <td>NO LIQUIDADA</td>";
          $actionCancel=ModuloGetURL('app','DatosLiquidacionQX','user','CancelarLiquidacionQX',array("NoLiquidacion"=>$datos[$i]['cuenta_liquidacion_qx_id'],"TipoDocumento"=>$datos[$i]['tipo_id_paciente'],"Documento"=>$datos[$i]['paciente_id'],"nombrePaciente"=>$datos[$i]['nombre'],"cuenta"=>$datos[$i]['numerodecuenta'],"ingreso"=>$datos[$i]['ingreso']));
          $this->salida .= "     <td><a href=\"$actionCancel\" title=\"Cancelar\"><img border = 0 src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
        }elseif($datos[$i]['estado']=='1'){
          $this->salida .= "     <td>LIQUIDADA</td>";
          $this->salida .= "     <td>&nbsp;</td>";
        }elseif($datos[$i]['estado']=='2'){
          $this->salida .= "     <td>CARGADA A LA CUENTA</td>";
          $this->salida .= "     <td>&nbsp;</td>";
        }elseif($datos[$i]['estado']=='3'){
          $this->salida .= "     <td>ELIMINADA</td>";
          $this->salida .= "     <td>&nbsp;</td>";
        }
        if($datos[$i]['programacion_id']){               
          $RegistroNotas=$this->RegistroNotasPaciente($datos[$i]['programacion_id'],$datos[$i]['tipo_id_paciente'],$datos[$i]['paciente_id']);
          if($RegistroNotas){
              //imprimir nota operatoria                         
              $mostrar=$rep->GetJavaReport('app','DatosLiquidacionQX','reporteNotaOperatoria_html',array('programacion'=>$datos[$i]['programacion_id'],'tipoidpaciente'=>$datos[$i]['tipo_id_paciente'],'paciente'=>$datos[$i]['paciente_id'],'ingreso'=>$datos[$i]['ingreso']),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
              $nombre_funcion=$rep->GetJavaFunction();
              $this->salida .=$mostrar;
              $this->salida .= "  <td><a title=\"Imprimir Nota Operatoria\" href=\"javascript:$nombre_funcion\"><img border = 0 src=\"".GetThemePath()."/images/traslado.png\"></a></td>";
              //fin imprimir
          }else{
              $this->salida .= "  <td>&nbsp;</td>";
          }
        }else{
          $this->salida .= "  <td>&nbsp;</td>";
        }
        $action=ModuloGetURL('app','DatosLiquidacionQX','user','LiquidacionEquiposQX',array("liquidacionId"=>$datos[$i]['cuenta_liquidacion_qx_id'],"TipoDocumento"=>$datos[$i]['tipo_id_paciente'],"Documento"=>$datos[$i]['paciente_id'],"nombrePaciente"=>$datos[$i]['nombre'],"cuenta"=>$datos[$i]['numerodecuenta'],"ingreso"=>$datos[$i]['ingreso']));
        $this->salida .= "   <td align=\"center\"><a title=\"Liquidacion Equipos\" href=\"$action\"><img border = 0 src=\"".GetThemePath()."/images/pc.png\"></a></td>";
        //if($_SESSION['LIQUIDACION_QX']['CargueIyM']=='1'){                    
            //$Insumos=$this->ValidarMedicamentosCuentaPaciente($datos[$i]['cuenta_liquidacion_qx_id'],$datos[$i]['numerodecuenta']);                    
            //if($datos[$i]['estado']=='2' || !empty($Insumos)){
            //$actionInv=ModuloGetURL('app','DatosLiquidacionQX','user','CargaInsumosMedicamentosCuenta',array("liquidacionId"=>$datos[$i]['cuenta_liquidacion_qx_id'],"TipoDocumento"=>$datos[$i]['tipo_id_paciente'],"Documento"=>$datos[$i]['paciente_id'],"nombrePaciente"=>$datos[$i]['nombre'],"cuenta"=>$datos[$i]['numerodecuenta'],"ingreso"=>$datos[$i]['ingreso']));
            //$this->salida .= "   <td align=\"center\"><a title=\"Cargar Insumos y Medicamentos\" href=\"$actionInv\"><img border = 0 src=\"".GetThemePath()."/images/pparamedin.png\"></a></td>";
            //}else{
            //$this->salida .= "   <td align=\"center\"><img title=\"La Cirugia no esta Cargada a la Cuenta\" border = 0 src=\"".GetThemePath()."/images/pparamed.png\"></td>";
            //}
            /*if($this->VerificarCuentaActiva($datos[$i]['tipo_id_paciente'],$datos[$i]['paciente_id'])==1){
                $actionInv=ModuloGetURL('app','DatosLiquidacionQX','user','CargaInsumosMedicamentosCuenta',array("liquidacionId"=>$datos[$i]['cuenta_liquidacion_qx_id'],"TipoDocumento"=>$datos[$i]['tipo_id_paciente'],"Documento"=>$datos[$i]['paciente_id'],"nombrePaciente"=>$datos[$i]['nombre'],"cuenta"=>$datos[$i]['numerodecuenta'],"ingreso"=>$datos[$i]['ingreso']));
                $this->salida .= "   <td align=\"center\"><a title=\"Cargar Insumos y Medicamentos\" href=\"$actionInv\"><img border = 0 src=\"".GetThemePath()."/images/pparamedin.png\"></a></td>";
            }else{
                $actionInv=ModuloGetURL('app','DatosLiquidacionQX','user','CargaInsumosMedicamentosCuenta',array("liquidacionId"=>$datos[$i]['cuenta_liquidacion_qx_id'],"TipoDocumento"=>$datos[$i]['tipo_id_paciente'],"Documento"=>$datos[$i]['paciente_id'],"nombrePaciente"=>$datos[$i]['nombre'],"cuenta"=>$datos[$i]['numerodecuenta'],"ingreso"=>$datos[$i]['ingreso']));
                $this->salida .= "   <td align=\"center\"><a title=\"Cargar Insumos y Medicamentos\" href=\"$actionInv\"><img border = 0 src=\"".GetThemePath()."/images/pparamed.png\"></a></td>";
            }*/
        //}
        /*$actionInv=ModuloGetURL('app','DatosLiquidacionQX','user','SeleccionBodegaCargaInsumos',array("liquidacionId"=>$datos[$i]['cuenta_liquidacion_qx_id'],"TipoDocumento"=>$datos[$i]['tipo_id_paciente'],"Documento"=>$datos[$i]['paciente_id'],"nombrePaciente"=>$datos[$i]['nombre'],"cuenta"=>$datos[$i]['numerodecuenta'],"ingreso"=>$datos[$i]['ingreso']));
        if($datos[$i]['estado']=='2' && $datos[$i]['documentos_in']==1){
        $this->salida .= "   <td align=\"center\"><a title=\"Cargar Insumos y Medicamentos\" href=\"$actionInv\"><img border = 0 src=\"".GetThemePath()."/images/pparamedin.png\"></a></td>";
        }else{
        $this->salida .= "   <td align=\"center\"><a title=\"Cargar Insumos y Medicamentos\" href=\"$actionInv\"><img border = 0 src=\"".GetThemePath()."/images/pparamed.png\"></a></td>";
        }*/
        $action=ModuloGetURL('app','DatosLiquidacionQX','user','LlamaModificarLiquidacion',array("liquidacionId"=>$datos[$i]['cuenta_liquidacion_qx_id']));
        $this->salida .= "   <td align=\"center\"><a title=\"Modificar Datos de la Cirugia\" href=\"$action\"><img border = 0 src=\"".GetThemePath()."/images/pmodificar.png\"></a></td>";
        if($datos[$i]['estado']=='0'){
          $action=ModuloGetURL('app','DatosLiquidacionQX','user','LlamaFormaEquivalentesLiquidacion',array("liquidacionId"=>$datos[$i]['cuenta_liquidacion_qx_id'],"TipoDocumento"=>$datos[$i]['tipo_id_paciente'],"Documento"=>$datos[$i]['paciente_id'],"nombrePaciente"=>$datos[$i]['nombre'],"cuenta"=>$datos[$i]['numerodecuenta'],"ingreso"=>$datos[$i]['ingreso'],
          "TipoDocumentoFil"=>$TipoDocumento,"DocumentoFil"=>$Documento,"NoIngresoFil"=>$NoIngreso,"NoCuentaFil"=>$NoCuenta,"EstadoFil"=>$Estado,"FechaCirugiaFil"=>$FechaCirugia));
          $this->salida .= "     <td align=\"center\"><a title=\"Liquidacion Cirugia\" href=\"$action\"><img border = 0 src=\"".GetThemePath()."/images/cargosin.png\"></a></td>";
        }elseif($datos[$i]['estado']=='1'){
          $action=ModuloGetURL('app','DatosLiquidacionQX','user','LlamaFormaModificarLiquidacion',array("liquidacionId"=>$datos[$i]['cuenta_liquidacion_qx_id'],"TipoDocumento"=>$datos[$i]['tipo_id_paciente'],"Documento"=>$datos[$i]['paciente_id'],"nombrePaciente"=>$datos[$i]['nombre'],"cuenta"=>$datos[$i]['numerodecuenta'],"ingreso"=>$datos[$i]['ingreso'],"estado"=>$datos[$i]['estado'],
          "TipoDocumentoFil"=>$TipoDocumento,"DocumentoFil"=>$Documento,"NoIngresoFil"=>$NoIngreso,"NoCuentaFil"=>$NoCuenta,"EstadoFil"=>$Estado,"FechaCirugiaFil"=>$FechaCirugia));
          $this->salida .= "     <td align=\"center\"><a title=\"Liquidacion Cirugia\" href=\"$action\"><img border = 0 src=\"".GetThemePath()."/images/cargosin.png\"></a></td>";
        }else{
          $action=ModuloGetURL('app','DatosLiquidacionQX','user','LlamaFormaModificarLiquidacion',array("liquidacionId"=>$datos[$i]['cuenta_liquidacion_qx_id'],"TipoDocumento"=>$datos[$i]['tipo_id_paciente'],"Documento"=>$datos[$i]['paciente_id'],"nombrePaciente"=>$datos[$i]['nombre'],"cuenta"=>$datos[$i]['numerodecuenta'],"ingreso"=>$datos[$i]['ingreso'],"estado"=>$datos[$i]['estado'],
          "TipoDocumentoFil"=>$TipoDocumento,"DocumentoFil"=>$Documento,"NoIngresoFil"=>$NoIngreso,"NoCuentaFil"=>$NoCuenta,"EstadoFil"=>$Estado,"FechaCirugiaFil"=>$FechaCirugia));
          $this->salida .= "     <td align=\"center\"><a title=\"Consulta Cargos Cirugia\" href=\"$action\"><img border = 0 src=\"".GetThemePath()."/images/cargos.png\"></a></td>";
        }
        $this->salida .= "   </tr>\n";
      }
      $this->salida .= "      </table>";
      $Paginador = new ClaseHTML();
      $this->actionPaginador=ModuloGetURL('app','DatosLiquidacionQX','user','LlamaDatosRequeridosLiquidacion',array("TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"Buscar"=>1,"NoIngreso"=>$NoIngreso,"NoCuenta"=>$NoCuenta,"Estado"=>$Estado,"FechaCirugia"=>$FechaCirugia));
      $this->salida .= "      ".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->actionPaginador);
    }else{
      $this->salida .= "      </BR><table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
      $this->salida .= "      <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON REGISTROS DE LIQUIDACIONES REALIZADAS</td></tr>";
      $this->salida .= "      </table>";
    }
    if(($TipoDocumento && $Documento) || $NoIngreso || $NoCuenta){
      $this->salida .= "      <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\">";
      $actionNuevo=ModuloGetURL('app','DatosLiquidacionQX','user','LlamaDatosRequeridosLiquidacion',array("TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"NoIngreso"=>$NoIngreso,"NoCuenta"=>$NoCuenta,"Estado"=>$Estado,"FechaCirugia"=>$FechaCirugia));
      $this->salida .= "      <tr><td align=\"right\" class=\"label\"><a href=\"$actionNuevo\">CREAR NUEVA LIQUIDACION</td></tr>";
      $this->salida .= "      </table>";
    }
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    function DatosCancelacionProgramacion($programacion){

    $this->salida.= ThemeAbrirTabla('CANCELACION PROGRAMACION No. '.$programacion);
        $this->Encabezado();
        $accion=ModuloGetURL('app','DatosLiquidacionQX','user','CancelacionProgramacionQX',array("programacion"=>$programacion));
        $this->salida .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"65%\" align=\"center\" class=\"normal_10\">";
    $this->salida .= "  <tr><td width=\"100%\">";
        $this->salida .= "  <fieldset><legend class=\"field\">MOTIVO CANCELACION PROGRAMACION</legend>";
        $this->salida .= "  <br><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= "  <tr><td colspan=\"4\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "  </td></tr>";
        $this->salida .= "   <tr><td class=\"".$this->SetStyle("motivoCancel")."\">MOTIVO CANCELACION: </td><td><select name=\"motivoCancel\" class=\"select\">";
        $Motivos=$this->MotivosCancelacionProgramacion();
        foreach($Motivos as $value=>$titulo){
            if($value==$_REQUEST['motivoCancel']){
                $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
            }else{
                $this->salida .=" <option value=\"$value\">$titulo</option>";
            }
        }
        $this->salida .= "   </select></td></tr>";
        $this->salida .= "   <tr><td class=\"label\" colspan=\"2\">OBSERVACIONES</td></tr><tr><td colspan=\"2\"><textarea name=\"observacion\" cols=\"65\" rows=\"3\" class=\"textarea\">".$_REQUEST['observacion']."</textarea></td></tr>";
    $this->salida .= "  </table><br>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
    $this->salida .= "  <tr><td align=\"center\">";
    $this->salida .= "  <BR><input type=\"submit\" class=\"input-submit\" value=\"VOLVER\" name=\"regresar\">&nbsp&nbsp&nbsp;<input type=\"submit\" class=\"input-submit\" value=\"ACEPTAR\" name=\"aceptar\">";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table>";
    $this->salida .= "<br>";
        $this->salida .= "</form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    function DatosRequeridosLiquidacion($TipoDocumento,$Documento,$nombrePaciente,$cuenta,$ingreso){
        SessionSetVar("RutaImagen",GetThemePath());
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserDrag");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS("RemoteScripting");
        $this->IncludeJS("ScriptRemoting/gases.js",'app','DatosLiquidacionQX');
        $this->salida .= ThemeAbrirTabla('DATOS REQUERIDOS LIQUIDACION QX');        
        $this->salida.="<script language='javascript'>\n";
        $this->salida.="  function desabilita(frm,valor){";
        $this->salida.="    cadena=valor.split('/');";
        $this->salida.="    if(cadena[1]==0 || valor==-1){";
        $this->salida.="        frm.gasAnestesico.disabled=true;\n";
        $this->salida.="        frm.gasAnestesicoMe.disabled=true;\n";
        $this->salida.="        frm.DuracionGas.disabled=true;\n";
        $this->salida.="        frm.nogas.value='0';\n";
        $this->salida.="    }else{\n";
        $this->salida.="        frm.gasAnestesico.disabled=false;\n";
        $this->salida.="        frm.gasAnestesicoMe.disabled=false;\n";
        $this->salida.="        frm.DuracionGas.disabled=false;\n";
        $this->salida.="        frm.nogas.value='1';\n";
        $this->salida.="    }\n";
        $this->salida.="  }\n";
        $this->salida.="  function desabilitaQuirofano(frm,valor){";
        $this->salida.="    cadena=valor.split('/');";
        $this->salida.="    if(cadena[1]==0 || valor==-1){";
        $this->salida.="        frm.quirofano.disabled=true;\n";
        $this->salida.="        frm.noquiro.value='0';\n";
        $this->salida.="    }else{\n";
        $this->salida.="        frm.quirofano.disabled=false;\n";
        $this->salida.="        frm.noquiro.value='1';\n";
        $this->salida.="    }\n";
        $this->salida.="  }\n";
        $this->salida.="  function desabilitaPolitrauma(frm,valor){";
        $this->salida.="    if(valor==true){";
        $this->salida.="        frm.TipoPolitrauma.disabled=false;\n";
        $this->salida.="    }else{\n";
        $this->salida.="        frm.TipoPolitrauma.disabled=true;\n";
        $this->salida.="    }\n";
        $this->salida.="  }\n";
        $this->salida .= "  function Iniciar(capita,envios)\n";
        $this->salida .= "  {\n";        
        $this->salida .= "    document.getElementById('titulo').innerHTML = '<center>GASES ANESTESICOS</center>';\n";
        $this->salida .= "    document.getElementById('error').innerHTML = '';\n";                
        $this->salida .= "    contenedor = 'd2Container';\n";
        $this->salida .= "    titulo = 'titulo';\n";
        $this->salida .= "    ele = xGetElementById('d2Container');\n";
        $this->salida .= "    xMoveTo(ele, xClientWidth()/3, xScrollTop()+24);\n";
        $this->salida .= "    ele = xGetElementById('titulo');\n";
        $this->salida .= "    xResizeTo(ele,280, 20);\n";
        $this->salida .= "    xMoveTo(ele, 0, 0);\n";
        $this->salida .= "    xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
        $this->salida .= "    ele = xGetElementById('cerrar');\n";
        $this->salida .= "    xResizeTo(ele,20, 20);\n";
        $this->salida .= "    xMoveTo(ele, 280, 0);\n";
        $this->salida .= "  }\n";
        $this->salida .= "  function myOnDragStart(ele, mx, my)\n";
        $this->salida .= "  {\n";
        $this->salida .= "    window.status = '';\n";
        $this->salida .= "    if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
        $this->salida .= "    else xZIndex(ele, hiZ++);\n";
        $this->salida .= "    ele.myTotalMX = 0;\n";
        $this->salida .= "    ele.myTotalMY = 0;\n";
        $this->salida .= "  }\n";
        $this->salida .= "  function myOnDrag(ele, mdx, mdy)\n";
        $this->salida .= "  {\n";
        $this->salida .= "    if (ele.id == titulo) {\n";
        $this->salida .= "      xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
        $this->salida .= "    }\n";
        $this->salida .= "    else {\n";
        $this->salida .= "      xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
        $this->salida .= "    }  \n";
        $this->salida .= "    ele.myTotalMX += mdx;\n";
        $this->salida .= "    ele.myTotalMY += mdy;\n";
        $this->salida .= "  }\n";
        $this->salida .= "  function myOnDragEnd(ele, mx, my)\n";
        $this->salida .= "  {\n";
        $this->salida .= "  }\n";
        $this->salida .= "  function MostrarSpan(Seccion)\n";
        $this->salida .= "  { \n";
        $this->salida .= "    e = xGetElementById(Seccion);\n";
        $this->salida .= "    e.style.display = \"\";\n";
        $this->salida .= "  }\n";
        $this->salida .= "  function Cerrar(Seccion)\n";
        $this->salida .= "  { \n";
        $this->salida .= "    e = xGetElementById(Seccion);\n";
        $this->salida .= "    e.style.display = \"none\";\n";
        $this->salida .= "  }\n";
        $this->salida .= "  function MostrarVentana(Seccion)\n";
        $this->salida .= "  { \n";
        $this->salida .= "    e = xGetElementById(Seccion);\n";
        $this->salida .= "    e.style.display = \"block\";\n";
        $this->salida .= "  }\n";
        $this->salida .= "  function InsertarDatosFrecuencia(frm)\n";
        $this->salida .= "  { \n";
        $this->salida .= "    if(frm.gasAnestesico.value==-1){;\n";        
        $this->salida .= "      alert('Todos los Datos son Obligatorios');\n";        
        $this->salida .= "      return false;\n";        
        $this->salida .= "    };\n";        
        $this->salida .= "    if(frm.SuministroGas.value==-1){;\n";        
        $this->salida .= "      alert('Todos los Datos son Obligatorios');\n";        
        $this->salida .= "      return false;\n";        
        $this->salida .= "    };\n";        
        $this->salida .= "    if(frm.FrecuenciaSuministroGas.value==-1){;\n";        
        $this->salida .= "      alert('Todos los Datos son Obligatorios');\n";        
        $this->salida .= "      return false;\n";        
        $this->salida .= "    };\n";        
        $this->salida .= "    if(frm.MinutosSuministroGas.value==0){;\n";        
        $this->salida .= "      alert('Todos los Datos son Obligatorios');\n";        
        $this->salida .= "      return false;\n";        
        $this->salida .= "    };\n";        
        $this->salida .= "    var cadena=new Array();\n";        
        $this->salida .= "    cadena[0]=frm.gasAnestesico.value;\n";                
        $this->salida .= "    var indice=frm.gasAnestesico.selectedIndex;\n";                
        $this->salida .= "    cadena[1]=frm.gasAnestesico.options[indice].text;\n";                
        $this->salida .= "    cadena[2]=frm.SuministroGas.value;\n";                
        $this->salida .= "    var indice1=frm.SuministroGas.selectedIndex;\n";                
        $this->salida .= "    cadena[3]=frm.SuministroGas.options[indice1].text;\n";         
        $this->salida .= "    cadena[4]=frm.FrecuenciaSuministroGas.value;\n";        
        $this->salida .= "    var indice2=frm.FrecuenciaSuministroGas.selectedIndex;\n";     
        $this->salida .= "    cadena[5]=frm.FrecuenciaSuministroGas.options[indice2].text;\n";                           
        $this->salida .= "    cadena[6]=frm.MinutosSuministroGas.value;\n";                                                          
        $this->salida .= "    jsrsExecute(\"app_modules/DatosLiquidacionQX/ScriptRemoting/gases.php\", valores_resultado_insercion, \"InsertarDatosGasesSuministrados\",cadena);";        
        $this->salida .= "  }\n";        
        $this->salida.="</script>\n";
        
        //ELABORACION DE LA VENTANA DE GASES ANESTESICOS        
        
        $ventana.= "  <div id='d2Container' class='d2Container' style=\"display:none\">\n";
        $ventana.= "  <div id='titulo' class='draggable' style=\" text-transform: uppercase;\"></div>\n";
        $ventana.= "  <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('d2Container')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
        $ventana.= "  <div id='error' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
        $ventana.= "  <div id='d2Contents'>\n";
        $ventana.= "  <form name=\"formaGas\" action=\"$action\" method=\"post\">";        
        $ventana.="<table align=\"center\">";
        $ventana.="<tr class=\"modulo_list_claro\" width=\"100%\">";
        $ventana.="<td class=\"".$this->SetStyle("gasAnestesico")."\">TIPO GAS</td>";
        $ventana.="<td><select name=\"gasAnestesico\" class=\"select\">";
        $ventana.="    <option value=\"-1\" selected>---seleccione---</option>";
        $TipoGases=$this->TiposGasesAnestesicos();
        foreach($TipoGases as $value=>$titulo){          
          $ventana.="  <option value=\"$value\">$titulo</option>";          
        }
        $ventana.= "   </select></td>";
        $ventana.= "</td></tr>";
        $ventana.="<tr class=\"modulo_list_claro\">";
        $ventana.="<td class=\"".$this->SetStyle("SuministroGas")."\">METODO SUMINISTRO</td>";
        $ventana.="<td><select name=\"SuministroGas\" class=\"select\" onchange=\"CambioSuministro(this.value)\">";
        $ventana.="    <option value=\"-1\" selected>---seleccione---</option>";
        $TipoSuministros=$this->TiposMetodosSuministrosGases();
        foreach($TipoSuministros as $value=>$titulo){          
          $ventana.="  <option value=\"$value\">$titulo</option>";          
        }
        $ventana.= "   </select></td>";
        $ventana.= "</td></tr>";
        $ventana.="<tr class=\"modulo_list_claro\">";
        $ventana.="<td class=\"".$this->SetStyle("FrecuenciaSuministroGas")."\">FRECUENCIA SUMINISTRO</td>";
        $ventana.="<td id=\"frecuencia\"><select name=\"FrecuenciaSuministroGas\" class=\"select\">";
        $ventana.="    <option value=\"-1\" selected>---seleccione---</option>";        
        $ventana.= "   </select></td>";
        $ventana.= "</td></tr>";
        $ventana.="<tr class=\"modulo_list_claro\">";
        $ventana.="<td class=\"".$this->SetStyle("MinutosSuministroGas")."\">MINUTOS</td>";
        $ventana.="<td><input class=\"input-text\" type=\"text\" size=\"4\" name=\"MinutosSuministroGas\" value=\"0\"></td>";
        $ventana.="</tr>";
        $ventana.="<tr class=\"modulo_list_claro\">";          
        $ventana.="<td align=\"center\" colspan=\"2\"><input class=\"input-submit\" type=\"button\" onclick=\"InsertarDatosFrecuencia(document.formaGas)\" name=\"INSERTAR\" value=\"INSERTAR\"></td>";
        $ventana.="</tr>";
        $ventana.="</table>"; 
        $ventana.="</form>";
        $ventana.="</div>";
        $ventana.="</div>";        
        $this->salida.=$ventana;
        
        $action=ModuloGetURL('app','DatosLiquidacionQX','user','InsertarDatosReqLiquidacion',array("TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso));
        $this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
        $this->Encabezado();        
        $this->salida .= "  <table width=\"40%\" border=\"0\" align=\"center\">";
        $this->salida .= "  <tr><td align=\"center\">";
        $this->salida .=    $this->SetStyle("MensajeError");
        $this->salida .= "  </td></tr>";
    $this->salida .= "   </table>";
        $this->salida .= "  <table width=\"60%\" border=\"0\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">DATOS PACIENTE</legend>";
        $this->salida .= "    <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"98%\" align=\"center\">";
        if(!empty($_SESSION['Liquidacion_QX']['LIQUIDACION_ID'])){
            $this->salida .= "    <tr class=\"modulo_list_claro\">";
            $this->salida .= "      <td width=\"20%\" class=\"label\">No. LIQUIDACION</td>";
            $this->salida .= "      <td colspan=\"3\">".$_SESSION['Liquidacion_QX']['LIQUIDACION_ID']."</td>";
            $this->salida .= "    </tr>";
        }
    $this->salida .= "    <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"20%\" class=\"label\">PACIENTE</td>";
        $this->salida .= "      <td colspan=\"2\">".$TipoDocumento." ".$Documento." - ".$nombrePaciente."</td>";
        $actionPaciente=ModuloGetURL('app','DatosLiquidacionQX','user','PedirDatosPaciente',array("TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso));
        $this->salida .= "      <td width=\"25%\" align=\"center\"><a href=\"$actionPaciente\"><b>DATOS PACIENTE</b></a></td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"20%\" class=\"label\">No. CUENTA</td>";
        $this->salida .= "      <td>".$cuenta."</td>";
        $this->salida .= "      <td width=\"20%\" class=\"label\">No. INGRESO</td>";
        $this->salida .= "      <td width=\"25%\">".$ingreso."</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    </table>";
        $this->salida .= "   </fieldset>";
        $this->salida .= "   </td></tr>";
        $this->salida .= "   </table><br>";
    if($_SESSION['Liquidacion_QX']['CIRUJANOS']){
          foreach($_SESSION['Liquidacion_QX']['CIRUJANOS'] as $contadorProc=>$cirujanoArray){
              if($y % 2){$estilo='modulo_list_claro';$estilo1='modulo_list_oscuro';}else{$estilo='modulo_list_oscuro';$estilo1='modulo_list_claro';}
                $this->salida .= "    <table border=\"0\" width=\"85%\" align=\"center\">";
                $this->salida .= "    <tr>";
                $this->salida .= "    <td class=\"modulo_table_list_title\">ESPECIALISTA No. ".$contadorProc."</td>";
                $actionElimina=ModuloGetURL('app','DatosLiquidacionQX','user','EliminarCirDatosReqLiquidacion',array("TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso,"contadorProc"=>$contadorProc));
                $this->salida .= "    <td class=\"$estilo\" width=\"5%\"><a href=\"$actionElimina\"><img title=\"Eliminar Cirujano\" border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
                $this->salida .= "    </tr>";
                (list($tipoIdCir,$IdCir,$NomCir)=explode('||//',$cirujanoArray));
                $this->salida .= "    <tr class=\"$estilo\">";
                $this->salida .= "    <td colspan=\"2\" align=\"center\">".$NomCir."</td>";
                $this->salida .= "    </tr>";
                if($_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'][$cirujanoArray]){
                    $this->salida .= "    <tr class=\"$estilo\">";
                    $this->salida .= "    <td colspan=\"2\" align=\"center\">";
                    $this->salida .= "    <table border=\"0\" width=\"98%\" align=\"center\">";
                    $this->salida .= "    <tr><td colspan=\"4\" class=\"modulo_table_title\">PROCEDIMIENTOS</td></tr>";
          //jab
	  //echo '_SESSION: <pre>';print_r($_SESSION);
	  //echo '<br><br>PROC: <pre>';print_r($_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'][$cirujanoArray]);
	  foreach($_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'][$cirujanoArray] as $indice=>$procedimiento){
            $this->salida .= "  <tr class=\"$estilo1\"><td colspan=\"4\">";
            $this->salida .= "    <table border=\"0\" width=\"98%\" align=\"center\">";
                      (list($cargo,$descripcion,$sw_bilateral)=explode('||//',$procedimiento));
            $this->salida .= "    <tr class=\"$estilo1\">";
            $this->salida .= "    <td width=\"15%\">".$cargo."</td>";
                        $this->salida .= "    <td>".$descripcion."</td>";
                        if($sw_bilateral==1){
              $che='';
              if($_SESSION['Liquidacion_QX']['PROCEDIMIENTOS_BILATERAL'][$cargo]==1){
                $che='checked';
              }
              $this->salida .= "    <td width=\"5%\"><input $che type=\"checkbox\" name=\"bilateral[".$cargo."]\" value=\"1\"></td>";
                        }else{
            $this->salida .= "    <td width=\"5%\">&nbsp;</td>";
                        }
                        $actionEliminaPro=ModuloGetURL('app','DatosLiquidacionQX','user','EliminarProDatosReqLiquidacion',array("TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso,"cirujanoArray"=>$cirujanoArray,"indice"=>$indice));
                    $this->salida .= "    <td width=\"5%\"><a href=\"$actionEliminaPro\"><img title=\"Eliminar Procedimiento\" border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
                        $this->salida .= "    </tr>";
            $this->salida .= "    <tr class=\"$estilo1\">";
            $this->salida .= "    <td width=\"15%\" class=\"label\">DIAGNOSTICO UNO</td>";
            if($_SESSION['Liquidacion_QX']['PROCEDIMIENTOS_DIAGNOSTICOS'][$_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'][$cirujanoArray][$indice]][1]){
              (list($codigo,$procedimiento)=explode('||//',$_SESSION['Liquidacion_QX']['PROCEDIMIENTOS_DIAGNOSTICOS'][$_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'][$cirujanoArray][$indice]][1]));
              $this->salida .= "    <td colspan=\"3\" align=\"left\">$codigo  $procedimiento</td>";
            }else{
              $action1=ModuloGetURL('app','DatosLiquidacionQX','user','LlamaBuscadorDiagnosticos',array("TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso,"procedimiento"=>$_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'][$cirujanoArray][$indice],"numDiagnostico"=>1));
              $this->salida .= "    <td colspan=\"3\" align=\"center\"><a href=\"$action1\"><b>INSERTAR DIAGNOSTICO</b></a></td>";
            }
            $this->salida .= "    </tr>";
            $this->salida .= "    <tr class=\"$estilo1\" class=\"label\">";
            $this->salida .= "    <td width=\"15%\" class=\"label\">DIAGNOSTICO DOS</td>";
            if($_SESSION['Liquidacion_QX']['PROCEDIMIENTOS_DIAGNOSTICOS'][$_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'][$cirujanoArray][$indice]][2]){
              (list($codigo,$procedimiento)=explode('||//',$_SESSION['Liquidacion_QX']['PROCEDIMIENTOS_DIAGNOSTICOS'][$_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'][$cirujanoArray][$indice]][2]));
              $this->salida .= "    <td colspan=\"3\" align=\"left\">$codigo  $procedimiento</td>";
            }else{
            $action1=ModuloGetURL('app','DatosLiquidacionQX','user','LlamaBuscadorDiagnosticos',array("TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso,"procedimiento"=>$_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'][$cirujanoArray][$indice],"numDiagnostico"=>2));
            $this->salida .= "    <td colspan=\"3\" align=\"center\"><a href=\"$action1\"><b>INSERTAR DIAGNOSTICO</b></a></td>";
            }
            $this->salida .= "    </tr>";
            $this->salida .= "    <tr class=\"$estilo1\" class=\"label\">";
            $this->salida .= "    <td width=\"15%\" class=\"label\">COMPLICACION</td>";
            if($_SESSION['Liquidacion_QX']['PROCEDIMIENTOS_DIAGNOSTICOS'][$_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'][$cirujanoArray][$indice]][3]){
              (list($codigo,$procedimiento)=explode('||//',$_SESSION['Liquidacion_QX']['PROCEDIMIENTOS_DIAGNOSTICOS'][$_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'][$cirujanoArray][$indice]][3]));
              $this->salida .= "    <td colspan=\"3\" align=\"left\">$codigo  $procedimiento</td>";
            }else{
                            $action1=ModuloGetURL('app','DatosLiquidacionQX','user','LlamaBuscadorDiagnosticos',array("TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso,"procedimiento"=>$_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'][$cirujanoArray][$indice],"numDiagnostico"=>3));
                            $this->salida .= "    <td colspan=\"3\" align=\"center\"><a href=\"$action1\"><b>INSERTAR DIAGNOSTICO</b></a></td>";
            }
            $this->salida .= "    </tr>";
            $this->salida .= "    </table>";
            $this->salida .= "  </td></tr>";
                    }
                    $this->salida .= "    </table>";
                    $this->salida .= "    </td>";
          $this->salida .= "    </tr>";
                }
                $this->salida .= "    <tr class=\"$estilo\">";
                $action=ModuloGetURL('app','DatosLiquidacionQX','user','LlamaInsertarProcedReqLiquidacion',array("TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso,"contadorProc"=>$contadorProc));
                $this->salida .= "    <td colspan=\"2\" align=\"center\"><a href=\"$action\"><b>INGRESAR PROCEDIMIENTOS</b></a></td>";
                $this->salida .= "    </tr>";
                $this->salida .= "    </table><BR>";
                $y++;
            }
        }
        $this->salida .= "    <table border=\"0\" width=\"85%\" align=\"center\">";
    $profesionales=$this->profesionalesEspecialista();
    $this->salida .= "    <tr class=\"modulo_list_oscuro\"><td>";
        if($profesionales){
        //funcion que Muestra en la forma el buscador de profesionales en caso de que la cantidad de profesionales sea mayor a x
        $this->CampoProfesionalDeterminaCantidad($profesionales);
        $this->salida .= "    </td></tr>";
        }
        $this->salida .= "    <tr class=\"modulo_list_claro\"><td>";
    $this->salida .= "        <table border=\"0\" width=\"95%\" align=\"center\">";
        $this->salida .= "        <tr><td colspan=\"2\" class=\"modulo_table_list_title\">VIA ACCESO</td></tr>";
        $this->salida .= "              <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "              <td class=\"".$this->SetStyle("viaAcceso")."\">VIA ACCESO</td>";
    $this->salida .= "              <td><select name=\"viaAcceso\" class=\"select\">";
        $this->salida .="         <option value=\"-1\" selected>---seleccione---</option>";
      $vias=$this->viaAccesoSegunProcedimientos();
        for($i=0;$i<sizeof($vias);$i++){
      $value=$vias[$i]['via_acceso'];
            $titulo=$vias[$i]['descripcion'];
            if($value==$_SESSION['Liquidacion_QX']['VIA_ACCESO']){
                $this->salida .="     <option value=\"$value\" selected>$titulo</option>";
            }else{
                $this->salida .="     <option value=\"$value\">$titulo</option>";
            }
        }
      $this->salida .= "        </select></td>";
        $this->salida .= "              </tr>";
        $cantidadProcedimientos=0;
        foreach($_SESSION['Liquidacion_QX']['CIRUJANOS'] as $indice=>$cirujano){
      $cantidadProcedimientos+=sizeof($_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'][$cirujano]);
        }
        if($cantidadProcedimientos>1){
            $this->salida .= "              <tr class=\"modulo_list_oscuro\">";
            $this->salida .= "              <td class=\"".$this->SetStyle("politraumatismo")."\">POLITRAUMATISMO</td>";
      $che='';
            if(empty($_SESSION['Liquidacion_QX']['POLITRAUMATISMO'])){
        $desabilitar1='disabled';
            }else{
        $che='checked';
            }
            $this->salida .= "              <td>";
            $this->salida .= "              <input type=\"checkbox\" name=\"politraumatismo\" value=\"1\" onclick=\"desabilitaPolitrauma(this.form,this.checked)\" $che>&nbsp&nbsp&nbsp;";
            $this->salida .= "              <select name=\"TipoPolitrauma\" class=\"select\" $desabilitar1>";
            $this->salida .="         <option value=\"-1\" selected>---Tipo Politrauma---</option>";
            $TipoPolitraumas=$this->TiposPolitraumasBD();
            foreach($TipoPolitraumas as $value=>$titulo){
                if($value==$_SESSION['Liquidacion_QX']['TIPO_POLITRAUMA']){
                    $this->salida .="     <option value=\"$value\" selected>$titulo</option>";
                }else{
                    $this->salida .="     <option value=\"$value\">$titulo</option>";
                }
            }
            $this->salida .= "              </select>";
            $this->salida .= "              </td>";
            $this->salida .= "              </tr>";
        }
    $this->salida .= "        </table>";

    $this->salida .= "        <table border=\"0\" width=\"95%\" align=\"center\">";
        $this->salida .= "        <tr><td colspan=\"2\" class=\"modulo_table_list_title\">OTROS PROFESIONALES</td></tr>";
        $this->salida .= "              <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "              <td class=\"".$this->SetStyle("ayudante")."\">AYUDANTE</td>";
        $this->salida .= "              <td><select name=\"ayudante\" class=\"select\">";
        $this->salida .="         <option value=\"-1\" selected>---seleccione---</option>";
      $profesionalesAy=$this->profesionalesAyudantes();
        for($i=0;$i<sizeof($profesionalesAy);$i++){
      $value=$profesionalesAy[$i]['tipo_id_tercero'].'||//'.$profesionalesAy[$i]['tercero_id'];
            $titulo=$profesionalesAy[$i]['nombre'];
            if($value==$_SESSION['Liquidacion_QX']['AYUDANTE']){
                $this->salida .="     <option value=\"$value\" selected>$titulo</option>";
            }else{
                $this->salida .="     <option value=\"$value\">$titulo</option>";
            }
        }
      $this->salida .= "        </select>";
        $cheAyu='';
        if($_SESSION['Liquidacion_QX']['AYUDANTE_IGUAL_ESP']=='1'){
            $cheAyu='checked';
        }
        $this->salida .= "          &nbsp;&nbsp;&nbsp;<label class=\"label\">IGUAL ESPECIALIDAD</label>&nbsp;&nbsp;<input type=\"checkbox\" name=\"AyudanteIgualEsp\" value=\"1\" $cheAyu>";
        $this->salida .= "          </td>";
        $this->salida .= "              </tr>";
        $this->salida .= "              <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "              <td class=\"".$this->SetStyle("anestesiologo")."\">ANESTESIOLOGO</td>";
        $this->salida .= "              <td><select name=\"anestesiologo\" class=\"select\">";
        $this->salida .="         <option value=\"-1\" selected>---seleccione---</option>";
      $anestesiologos=$this->profesionalesEspecialistaAnestecistas();
        for($i=0;$i<sizeof($anestesiologos);$i++){
      $value=$anestesiologos[$i]['tipo_id_tercero'].'||//'.$anestesiologos[$i]['tercero_id'];
            $titulo=$anestesiologos[$i]['nombre'];
            if($value==$_SESSION['Liquidacion_QX']['ANESTESIOLOGO']){
                $this->salida .="     <option value=\"$value\" selected>$titulo</option>";
            }else{
                $this->salida .="     <option value=\"$value\">$titulo</option>";
            }
        }
      $this->salida .= "        </select></td>";
        $this->salida .= "              </tr>";
    $this->salida .= "        </table><BR>";

    $this->salida .= "        <table border=\"0\" width=\"95%\" align=\"center\">";
        $this->salida .= "        <tr><td colspan=\"4\" class=\"modulo_table_list_title\">GASES ANESTESICOS</td></tr>";
        $this->salida .= "            <tr class=\"modulo_list_claro\">";
        $this->salida .= "            <td width=\"10%\" nowrap class=\"".$this->SetStyle("TipoAnestesia")."\">TIPO ANESTESIA</td>";
        $this->salida .= "            <td width=\"20%\" nowrap><select onchange=\"desabilita(this.form,this.value)\" name=\"TipoAnestesia\" onchange=\"desabilita(this.form,this.value)\" class=\"select\">";
        $this->salida .="         <option value=\"-1\" selected>---seleccione---</option>";
      $TiposAnestesias=$this->TiposDeAnestesias();
    for($i=0;$i<sizeof($TiposAnestesias);$i++){
      $value=$TiposAnestesias[$i]['qx_tipo_anestesia_id'].'/'.$TiposAnestesias[$i]['sw_uso_gases'];
            $titulo=$TiposAnestesias[$i]['descripcion'];
            if($value==$_SESSION['Liquidacion_QX']['TIPO_ANESTESIA']){
                $this->salida .="     <option value=\"$value\" selected>$titulo</option>";
            }else{
                $this->salida .="     <option value=\"$value\">$titulo</option>";
            }
        }
      $this->salida .= "       </select></td>";
    $this->salida .= "           <td>";
        if(empty($_SESSION['Liquidacion_QX']['TIPO_ANESTESIA']) || $_SESSION['Liquidacion_QX']['NO_GAS']!='1'){
      $desabilitar='disabled';
        }
    //$this->salida .= "                  <input type=\"hidden\" name=\"nogas\" value=\"".$_SESSION['Liquidacion_QX']['NO_GAS']."\">";
        $this->salida .= "                  <BR><table width=\"100%\" align=\"center\" border=\"0\">\n";
        $this->salida .= "                  <tr class=\"modulo_list_oscuro\"><td>";
        $this->salida .= "                  <a href=\"javascript:Iniciar();MostrarVentana(d2Container)\" class=\"label\">INSERTAR GAS ANESTESICO</a>\n";
        $this->salida .= "                  </td></tr>";        
//         $this->salida .= "                  <td class=\"".$this->SetStyle("gasAnestesico")."\">GAS ANESTESICO</td>";
//         $this->salida .= "                  <td><select name=\"gasAnestesico\" class=\"select\" $desabilitar>";
//         $this->salida .="           <option value=\"-1\" selected>---seleccione---</option>";
//       $TipoGases=$this->TiposGasesAnestesicos('A');
//         foreach($TipoGases as $value=>$titulo){
//             if($value==$_SESSION['Liquidacion_QX']['GAS_ANESTESICO']){
//                 $this->salida .="     <option value=\"$value\" selected>$titulo</option>";
//             }else{
//                 $this->salida .="     <option value=\"$value\">$titulo</option>";
//             }
//         }
//       $this->salida .= "            </select></td>";
//         $this->salida .= "                  </td></tr>";
//         $this->salida .= "                  <tr class=\"modulo_list_oscuro\">";
//         $this->salida .= "                  <td class=\"".$this->SetStyle("gasAnestesicoMe")."\">GAS MEDICINAL</td>";
//         $this->salida .= "                  <td><select name=\"gasAnestesicoMe\" class=\"select\" $desabilitar>";
//         $this->salida .="           <option value=\"-1\" selected>---seleccione---</option>";
//       $TipoGases=$this->TiposGasesAnestesicos('M');
//         foreach($TipoGases as $value=>$titulo){
//             if($value==$_SESSION['Liquidacion_QX']['GAS_ANESTESICO_ME']){
//                 $this->salida .="     <option value=\"$value\" selected>$titulo</option>";
//             }else{
//                 $this->salida .="     <option value=\"$value\">$titulo</option>";
//             }
//         }
//       $this->salida .= "            </select></td>";
//         $this->salida .= "              </tr>";
//         $this->salida .= "                  <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("DuracionGas")."\">MINUTOS SUMISTRO GAS</td>";
//         $this->salida .= "                  <td><input size=\"4\" type=\"text\" class=\"input-text\" name=\"DuracionGas\" value=\"".$_SESSION['Liquidacion_QX']['DURACION_GAS']."\" $desabilitar></td>";
        
        $this->salida .= "                  </table>";
        $this->salida .= "           </td>";
        $this->salida .= "           </tr>";
        $this->salida .= "           <tr><td colspan=\"3\" id=\"MostrarDatosGases\">";                
        $this->salida .= "<table width=\"100%\" align=\"center\" border=\"0\">\n";
        $this->salida .= "<tr class=\"modulo_list_oscuro\">";
        $this->salida .= "<td align=\"center\" width=\"30%\" class=\"label\">TIPO GAS</td>";
        $this->salida .= "<td align=\"center\" width=\"30%\" class=\"label\">METODO SUMINISTRO</td>";
        $this->salida .= "<td align=\"center\" width=\"20%\" class=\"label\">FRECUENCIA SUMINISTRO(L/m)</td>";
        $this->salida .= "<td align=\"center\" width=\"15%\" class=\"label\">MINUTOS</td>";
        $this->salida .= "<td align=\"center\" width=\"5%\" class=\"label\">&nbsp;</td>";
        $this->salida .= "</tr>";
	//echo '<br>GASES: <pre>'; print_r($_SESSION['Liquidacion_QX']['GASES']);
        foreach($_SESSION['Liquidacion_QX']['GASES'] as $i=>$vector){
          $this->salida .= "<tr class=\"modulo_list_oscuro\">";
          $this->salida .= "<td width=\"30%\">".$vector[TipoGasDes]."</td>";
          $this->salida .= "<td width=\"30%\">".$vector[MetodoGasDes]."</td>";
          $this->salida .= "<td width=\"20%\">".$vector[FrecuenciaGasDes]."</td>";
          $this->salida .= "<td width=\"20%\">".$vector[MinutosGas]."</td>";          
          $this->salida .= "<td width=\"5%\"><a href=\"javascript:EliminarGasAnestesico(new Array('$i'))\"><img title=\"Eliminar Gas\" border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
          $this->salida .= "</tr>";
        }
        $this->salida .= "</table>";
        $this->salida .= "           </td></tr>";
        $this->salida .= "       </table><BR>";
        
        $this->salida .= "        <table border=\"0\" width=\"95%\" align=\"center\">";
        $this->salida .= "        <tr><td colspan=\"5\" class=\"modulo_table_list_title\">TIPO SALA</td></tr>";
        $this->salida .= "            <tr class=\"modulo_list_claro\">";
        $this->salida .= "            <td width=\"10%\" nowrap class=\"".$this->SetStyle("TipoSala")."\">TIPO SALA</td>";
        $this->salida .= "            <td width=\"20%\" nowrap ><select name=\"TipoSala\" onchange=\"desabilitaQuirofano(this.form,this.value)\" class=\"select\">";
        $this->salida .="         <option value=\"-1\" selected>---seleccione---</option>";
      $TiposSala=$this->TiposDeSalas();
    for($i=0;$i<sizeof($TiposSala);$i++){
      $value=$TiposSala[$i]['tipo_sala_id'].'/'.$TiposSala[$i]['sw_quirofano'];
            $titulo=$TiposSala[$i]['descripcion'];
            if($value==$_SESSION['Liquidacion_QX']['TIPO_SALA']){
                $this->salida .="     <option value=\"$value\" selected>$titulo</option>";
            }else{
                $this->salida .="     <option value=\"$value\">$titulo</option>";
            }
        }
      $this->salida .= "       </select></td>";
    $this->salida .= "           <td colspan=\"3\">";
    if(empty($_SESSION['Liquidacion_QX']['TIPO_SALA']) || $_SESSION['Liquidacion_QX']['NO_QUIRO']!='1'){
      $desabilitar2='disabled';
        }
    $this->salida .= "                  <input type=\"hidden\" name=\"noquiro\" value=\"".$_SESSION['Liquidacion_QX']['NO_QUIRO']."\">";
        $this->salida .= "                  <BR><table width=\"100%\" align=\"center\" border=\"0\">\n";
        $this->salida .= "                  <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "                  <td class=\"".$this->SetStyle("quirofano")."\">QUIROFANO</td>";
        $this->salida .= "                  <td><select name=\"quirofano\" class=\"select\" $desabilitar2>";
        $this->salida .="           <option value=\"-1\" selected>---seleccione---</option>";
      $Quirofanos=$this->TiposQuirofanosTotal();
        for($j=0;$j<sizeof($Quirofanos);$j++){
            if($Quirofanos[$j]['quirofano']==$_SESSION['Liquidacion_QX']['QUIROFANO']){
                $this->salida .="     <option value=\"".$Quirofanos[$j]['quirofano']."\" selected>".$Quirofanos[$j]['descripcion']."</option>";
            }else{
                $this->salida .="     <option value=\"".$Quirofanos[$j]['quirofano']."\">".$Quirofanos[$j]['descripcion']."</option>";
            }
        }
      $this->salida .= "            </select></td>";
        $this->salida .= "              </tr>";
        $this->salida .= "                  </table><BR>";
        $this->salida .= "           </td>";
        $this->salida .= "           </tr>";
    $this->salida .= "            <tr class=\"modulo_list_claro\">";
        $this->salida .= "            <td width=\"10%\" nowrap class=\"".$this->SetStyle("FechaCirugia")."\">FECHA CIRUGIA</td>";
        $this->salida .= "        <td><input size=\"10\" type=\"text\" name=\"FechaCirugia\" value=\"".$_SESSION['Liquidacion_QX']['FECHA_CIRUGIA']."\" class=\"input-text\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\">";
        $this->salida .= "         &nbsp&nbsp&nbsp;".ReturnOpenCalendario('forma','FechaCirugia','/')."</td>";
    $this->salida .= "            <td class=\"".$this->SetStyle("HoraInicio")."\">HORA INICIO</td>";
    $this->salida .= "            <td>";
        $this->salida .= "            <select size=\"1\" name=\"HoraInicio\" class=\"select\">";
        $this->salida.="          <option value = -1>Horas</option>";
      for($j=0;$j<=23; $j++){
            $j=str_pad($j,2,'0',STR_PAD_LEFT);
            if($_SESSION['Liquidacion_QX']['HORA_INICIO']==$j){
                $this->salida.="      <option selected value = \"$j\">$j</option>";
            }else{
                $this->salida.="      <option value = \"$j\">$j</option>";
            }
    }
    $this->salida.="            </select>&nbsp;";
        $this->salida.="            <select size=\"1\"  name=\"minutosInicio\" class=\"select\">";
      $this->salida.="            <option value = -1>Minutos</option>";
        for($j=0;$j<=59; $j++){
            $j=str_pad($j,2,'0',STR_PAD_LEFT);
            if($_SESSION['Liquidacion_QX']['MIN_INICIO']==$j){
                $this->salida.="    <option selected value = \"$j\" >$j</option>";
            }else{
                $this->salida.="    <option value=\"$j\">$j</option>";
            }
    }
    $this->salida.="          </select></td>";
    $this->salida .= "           </tr>";

    $this->salida .= "            <tr class=\"modulo_list_claro\">";
    $this->salida .= "            <td class=\"".$this->SetStyle("duracion")."\">DURACION</td>";
    $this->salida .= "            <td>";
    $this->salida .= "            <select size=\"1\" name=\"hora\" class=\"select\">";
    $this->salida.="          <option value = -1>Horas</option>";
    for($j=0;$j<=23; $j++){
      $j=str_pad($j,2,'0',STR_PAD_LEFT);
      if($_SESSION['Liquidacion_QX']['HORA_DURACION']==$j){
          $this->salida.="      <option selected value = \"$j\">$j</option>";
      }else{
          $this->salida.="      <option value = \"$j\">$j</option>";
      }
    }
    $this->salida.="            </select>&nbsp;";
    $this->salida.="            <select size=\"1\"  name=\"minutos\" class=\"select\">";
    $this->salida.="            <option value = -1>Minutos</option>";
    for($j=0;$j<=59; $j++){
      $j=str_pad($j,2,'0',STR_PAD_LEFT);
      if($_SESSION['Liquidacion_QX']['MIN_DURACION']==$j){
        $this->salida.="    <option selected value = \"$j\" >$j</option>";
      }else{
        $this->salida.="    <option value=\"$j\">$j</option>";
      }
    }
    $this->salida.="          </select></td>";    
    $this->salida .= "        <td class=\"".$this->SetStyle("recuperacion")."\">MINUTOS RECUPERACION</td>";
    $this->salida .= "        <td><input size=\"4\" type=\"text\" class=\"input-text\" name=\"recuperacion\" value=\"".$_SESSION['Liquidacion_QX']['RECUPERACION']."\"></td>";        
    $this->salida .= "       </table><BR>";
    
        $this->salida .= "        <table border=\"0\" width=\"95%\" align=\"center\">";
        $this->salida .= "        <tr><td colspan=\"4\" class=\"modulo_table_list_title\">DATOS CIRUGIA</td></tr>";
        $this->salida .= "        <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "        <td width=\"15%\" class=\"".$this->SetStyle("ambitoCirugia")."\">AMBITO CIRUGIA</td>";
        $this->salida .= "          <td><select name=\"ambitoCirugia\" class=\"select\">";
        $this->salida .="         <option value=\"-1\" selected>---seleccione---</option>";
      $tiposAmbitos=$this->TiposdeAmbitosdeCirugia();
        for($i=0;$i<sizeof($tiposAmbitos);$i++){
      $value=$tiposAmbitos[$i]['ambito_cirugia_id'];
            $titulo=$tiposAmbitos[$i]['descripcion'];
            if($value==$_SESSION['Liquidacion_QX']['AMBITO_CIRUGIA']){
                $this->salida .="     <option value=\"$value\" selected>$titulo</option>";
            }else{
                $this->salida .="     <option value=\"$value\">$titulo</option>";
            }
        }
      $this->salida .= "        </select></td>";
        $this->salida .= "        <td width=\"15%\" class=\"".$this->SetStyle("tipoCirugia")."\">TIPO CIRUGIA</td>";
        $this->salida .= "          <td><select name=\"tipoCirugia\" class=\"select\">";
        $this->salida .="         <option value=\"-1\" selected>---seleccione---</option>";
      $tiposCirugia=$this->tipocirugia();
        for($i=0;$i<sizeof($tiposCirugia);$i++){
      $value=$tiposCirugia[$i]['tipo_cirugia_id'];
            $titulo=$tiposCirugia[$i]['descripcion'];
            if($value==$_SESSION['Liquidacion_QX']['TIPO_CIRUGIA']){
                $this->salida .="     <option value=\"$value\" selected>$titulo</option>";
            }else{
                $this->salida .="     <option value=\"$value\">$titulo</option>";
            }
        }
      $this->salida .= "        </select></td>";
        $this->salida .= "        </tr>";
    $this->salida .= "        <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "        <td width=\"15%\" class=\"".$this->SetStyle("finalidadCirugia")."\">FINALIDAD CIRUGIA</td>";
        $this->salida .= "          <td colspan=\"3\"><select name=\"finalidadCirugia\" class=\"select\">";
        $this->salida .="         <option value=\"-1\" selected>---seleccione---</option>";
      $finalidadesCirugia=$this->tipofinalidad();
        for($i=0;$i<sizeof($finalidadesCirugia);$i++){
      $value=$finalidadesCirugia[$i]['finalidad_procedimiento_id'];
            $titulo=$finalidadesCirugia[$i]['descripcion'];
            if($value==$_SESSION['Liquidacion_QX']['FINALIDAD_CIRUGIA']){
                $this->salida .="     <option value=\"$value\" selected>$titulo</option>";
            }else{
                $this->salida .="     <option value=\"$value\">$titulo</option>";
            }
        }
    $this->salida .= "        </tr>";
        $this->salida .= "        </table>";
        $this->salida .= "        <table border=\"0\" width=\"50%\" align=\"center\">";
    $this->salida .= "        <tr class=\"modulo_list_claro\">";
    $this->salida .= "        <td align=\"center\"><input type=\"submit\" name=\"GuardaDatos\" value=\"GUARDAR DATOS\" class=\"input-submit\"></td>";
        $this->salida .= "        </tr>";
    $this->salida .= "        </table>";
    $this->salida .= "    </td></tr>";
    $this->salida .= "    </table>";
    $this->salida .= "    <table border=\"0\" width=\"50%\" align=\"center\">";
    $this->salida .= "    <tr><td align=\"center\">";
        $this->salida .= "    <input class=\"input-submit\" type=\"submit\" value=\"VOLVER\" name=\"volverMenu\">";
        $this->salida .= "    </td></tr>";
        $this->salida .= "    </table>";
    $this->salida .= "      </form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

  function FormaLiquidarCargosCuenta($NoLiquidacion,$TipoDocumento,$Documento,$nombrePaciente,$cuenta,$ingreso){
    $this->salida .= ThemeAbrirTabla('CARGOS PRINCIPALES DE LA CIRUGIA');
        $action=ModuloGetURL('app','DatosLiquidacionQX','user','LlamaCargarCargosCirugiaTemporal',array("NoLiquidacion"=>$NoLiquidacion,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso));
        $this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
    $this->Encabezado();
        $this->salida .= "  <table width=\"40%\" border=\"0\" align=\"center\">";
        $this->salida .= "  <tr><td align=\"center\">";
        $this->salida .=    $this->SetStyle("MensajeError");
        $this->salida .= "  </td></tr>";
    $this->salida .= "   </table>";
        $this->salida .= "  <table width=\"70%\" border=\"0\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">DATOS DEL PROCEDIMIENTO</legend>";
        $this->salida .= "    <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"98%\" align=\"center\">";
    $this->salida .= "    <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"25%\" class=\"label\">NUMERO LIQUIDACION</td>";
        $this->salida .= "      <td colspan=\"3\">".$NoLiquidacion."</td>";
        $this->salida .= "    </tr>";
    $this->salida .= "    <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"25%\" class=\"label\">PACIENTE</td>";
        $this->salida .= "      <td colspan=\"3\">".$TipoDocumento." ".$Documento." - ".$nombrePaciente."</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"25%\" class=\"label\">No. CUENTA</td>";
        $this->salida .= "      <td>".$cuenta."</td>";
        $this->salida .= "      <td width=\"25%\" class=\"label\">No. INGRESO</td>";
        $this->salida .= "      <td>".$ingreso."</td>";
        $this->salida .= "    </tr>";
    $datosCirugia=$this->TraeDatosCirugia($NoLiquidacion);
    $estado=$datosCirugia['estado'];
    $reliquidada=$datosCirugia['reliquidada'];
    $this->salida .= "    <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"25%\" class=\"label\">VIA ACCESO</td>";
        $this->salida .= "      <td>".$datosCirugia['via']."</td>";
        $this->salida .= "      <td width=\"25%\" class=\"label\">AMBITO</td>";
        $this->salida .= "      <td>".$datosCirugia['ambito']."</td>";
        $this->salida .= "    </tr>";
    $this->salida .= "    <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"25%\" class=\"label\">FINALIDAD</td>";
        $this->salida .= "      <td>".$datosCirugia['finalidad']."</td>";
        $this->salida .= "      <td width=\"25%\" class=\"label\">TIPO</td>";
        $this->salida .= "      <td>".$datosCirugia['tipo']."</td>";
        $this->salida .= "    </tr>";
    $this->salida .= "    <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"25%\" class=\"label\">FECHA</td>";
    (list($fecha,$hora)=explode(' ',$datosCirugia['fecha_cirugia']));
    (list($ano,$mes,$dia)=explode('-',$fecha));
    (list($hora,$minutos)=explode(':',$hora));
        $this->salida .= "      <td>".ucwords(strftime("%b %d de %Y %H:%M",mktime($hora,$minutos,0,$mes,$dia,$ano)))."</td>";
        $this->salida .= "      <td width=\"25%\" class=\"label\">DURACION (HH:mm)</td>";
        $this->salida .= "      <td>".$datosCirugia['duracion_cirugia']."</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    </table>";
        $this->salida .= "   </fieldset>";
        $this->salida .= "   </td></tr>";
        $this->salida .= "   </table><br>";
    $procedimientos=$this->TraeProcedimientosCirugia($NoLiquidacion);
    if($procedimientos){
      $this->salida .= "    <table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
      $this->salida .= "    <tr class=\"modulo_table_title\"><td colspan=\"3\">PROCEDIMIENTOS DEL ACTO QUIRURGICO</td></tr>";
      $this->salida .= "    <tr class=\"modulo_table_title\">";
      $this->salida .= "    <td width=\"30%\">CIRUJANO</td>";
      $this->salida .= "    <td>PROCEDIMIENTO</td>";
            $this->salida .= "    <td width=\"5%\">BILATERAL</td>";
      $this->salida .= "    </tr>";
      $cirujanoAnt=-1;
      for($i=0;$i<sizeof($procedimientos);$i++){
        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
        $this->salida .= "  <tr class=\"$estilo\">";
        if($cirujanoAnt!=$procedimientos[$i]['tipo_id_cirujano'].'-'.$procedimientos[$i]['cirujano_id']){
        $this->salida .= "    <td rowspan=\"".$procedimientos[$i]['contador']."\">".$procedimientos[$i]['nombre_tercero']."</td>";
        $this->salida .= "    <td>".$procedimientos[$i]['descripcion']."</td>";
        if($procedimientos[$i]['sw_bilateral']==1){
        $this->salida .= "    <td>".$procedimientos[$i]['sw_bilateral']."</td>";
        }else{
        $this->salida .= "    <td>&nbsp;</td>";
        }
        $cirujanoAnt=$procedimientos[$i]['tipo_id_cirujano'].'-'.$procedimientos[$i]['cirujano_id'];
        }else{
        $this->salida .= "    <td>".$procedimientos[$i]['descripcion']."</td>";
        if($procedimientos[$i]['sw_bilateral']==1){
        $this->salida .= "    <td>".$procedimientos[$i]['sw_bilateral']."</td>";
        }else{
        $this->salida .= "    <td>&nbsp;</td>";
        }
        }

        $this->salida .= "    </tr>";
        $y++;
      }
      $this->salida .= "      </table><br>";
    }

    $datosCargos=$this->DatosCargoCirugia($NoLiquidacion,$estado);
    if($datosCargos){
      $this->salida .= "    <table border=\"0\" width=\"98%\" align=\"center\" class=\"normal_10\">";
      $this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"6\">CARGOS LIQUIDADOS DEL ACTO QUIRURGICO</td></tr>";
      $this->salida .= "    <tr class=\"modulo_table_list_title\">";
      $this->salida .= "    <td width=\"15%\">CONCEPTO</td>";
      $this->salida .= "    <td>CARGO</td>";
            $this->salida .= "    <td width=\"9%\">VALOR CARGO</td>";
      $this->salida .= "    <td width=\"20%\">PROFESIONAL</td>";
      $this->salida .= "    <td width=\"9%\">% HONORARIO</td>";
      $this->salida .= "    <td width=\"9%\">VALOR</td>";
            $this->salida .= "    </tr>";
            for($i=0;$i<sizeof($datosCargos);$i++){
        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
        $this->salida .= "  <tr class=\"$estilo\">";
        $this->salida .= "    <td>".$datosCargos[$i]['concepto']."</td>";
        $this->salida .= "    <td>".$datosCargos[$i]['cargo_cups']." ".$datosCargos[$i]['descripcion_cargo']."</td>";
              $this->salida .= "    <td>".$datosCargos[$i]['valor_cargo']."</td>";
        $this->salida .= "    <td>".$datosCargos[$i]['nombre_tercero']."</td>";
        $this->salida .= "    <td>".$datosCargos[$i]['porcentaje_honorario']."</td>";
        $this->salida .= "    <td>".$datosCargos[$i]['valor']."</td>";
        $this->salida .= "  </tr>";
        $y++;
      }
      $this->salida .= "    </table>";
      $this->salida .= "    <table border=\"0\" width=\"98%\" align=\"center\" class=\"normal_10\">";
      $this->salida .= "    <tr>";
      if($estado=='1'){
        $action=ModuloGetURL('app','DatosLiquidacionQX','user','CargarALaCuentaPaciente',array("NoLiquidacion"=>$NoLiquidacion,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso));
        $this->salida .= "   <td align=\"right\"><a href=\"$action\"><img border = 0 src=\"".GetThemePath()."/images/cargar.png\"><b>&nbsp&nbsp;CARGAR A LA CUENTA</b></a></td>";
      }elseif($estado=='2'){
        $this->salida .= "   <td align=\"right\"><img border = 0 src=\"".GetThemePath()."/images/pcopagos.png\"><b>&nbsp&nbsp;CARGADO A LA CUENTA</b></td>";
      }
      $this->salida .= "    </tr>";
      $this->salida .= "    </table>";
    }else{
      $this->salida .= "    <table width=\"98%\" border=\"0\" align=\"center\">";
      $this->salida .= "    <tr>";
      $this->salida .= "    <td align=\"center\" class=\"label_error\">LA CIRUGIA NO HA SIDO LIQUIDADA AUN, PARA HACERLO HAGA CLICK SOBRE LA OPCION LIQUIDAR</td>";
      $this->salida .= "    </tr>";
    }
    $this->salida .= "    <table width=\"98%\" border=\"0\" align=\"center\">";
    $this->salida .= "    <tr>";
    $this->salida .= "    <td width=\"50%\" align=\"right\">";
    if($estado!='0'){
    $this->salida .= "    <input type=\"submit\" name=\"Liquidar\" value=\"RELIQUIDAR\" class=\"input-submit\">";
    }else{
    $this->salida .= "    <input type=\"submit\" name=\"Liquidar\" value=\"LIQUIDAR\" class=\"input-submit\">";
    }
    $this->salida .= "    </td>";
    $this->salida .= "      </form>";
    $actionDos=ModuloGetURL('app','DatosLiquidacionQX','user','DatosPacientes');
        $this->salida .= "    <form name=\"forma\" action=\"$actionDos\" method=\"post\">";
    $this->salida .= "    <td width=\"50%\" align=\"left\">";
    $this->salida .= "    <input type=\"submit\" name=\"Volver\" value=\"VOLVER\" class=\"input-submit\">";
    $this->salida .= "    </td>";
    $this->salida .= "      </form>";
    $this->salida .= "      </tr>";
    $this->salida .= "    </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
  }

    function CampoProfesionalDeterminaCantidad($profesionales){
    if(sizeof($_SESSION['Liquidacion_QX']['CIRUJANOS'])>0){
            $cont=sizeof($_SESSION['Liquidacion_QX']['CIRUJANOS'])+1;
        }else{
            $cont=1;
        }
        //15 Cantidad de registro existentes de preofesionales
        if(sizeof($profesionales)<=15){
            $this->salida .= "    <table border=\"0\" width=\"98%\" align=\"center\">";
            $this->salida .= "    <tr><td colspan=\"3\" class=\"modulo_table_list_title\">INGRESO ESPECIALISTA No. ".$cont."</td></tr>";
            $this->salida .= "    <tr class=\"modulo_list_claro\">";
            $this->salida .= "    <td width=\"10%\" nowrap class=\"".$this->SetStyle("cirujano")."\">ESPECIALISTA</td>";
            $this->salida .= "    <td align=\"center\"><select name=\"cirujano\" class=\"select\">\n";
            $this->salida .=" <option value=\"-1\" selected>---seleccione---</option>";
            for($i=0;$i<sizeof($profesionales);$i++){
                $value=$profesionales[$i]['tipo_id_tercero'].'||//'.$profesionales[$i]['tercero_id'].'||//'.$profesionales[$i]['nombre'];
                if(!in_array($value,$_SESSION['Liquidacion_QX']['CIRUJANOS'])){
                    $titulo=$profesionales[$i]['nombre'];
                    if($value==$cirujano){
                        $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
                    }else{
                        $this->salida .=" <option value=\"$value\">$titulo</option>";
                    }
                }
            }
            $this->salida .= "    </select></td>";
            $this->salida .= "    <td align=\"center\"><input type=\"submit\" name=\"InsertarCirujano\" value=\"INSERTAR\" class=\"input-submit\"></td>";
            $this->salida .= "    </tr>";
            $this->salida .= "    </table>";
        }else{
      $this->salida .= "      <table width=\"95%\" border=\"0\" class=\"normal_10\" align=\"center\">";
            $this->salida .= "    <tr><td colspan=\"1\" class=\"modulo_table_list_title\">INGRESO ESPECIALISTA No. ".$cont."</td></tr>";
            $this->salida .= "    <tr class=\"modulo_list_claro\">";
            /*$this->salida .= "    <td class=\"label\">ESPECIALISTA</td>";
            $this->salida .= "    <td align=\"center\"><input size=\"50\" type=\"text\" name=\"cirujanoBus\" value=\"".$cirujanoBus."\" class=\"input-submit\"></td>";
      $this->salida .= "    <input type=\"hidden\" name=\"cirujano\" value=\"$cirujano\">";
            */
            $this->salida .= "    <td align=\"center\"><input type=\"submit\" name=\"buscarProfesional\" value=\"BUSCAR CIRUJANO\" class=\"input-submit\"></td>";
            $this->salida .= "    </tr>";
            $this->salida .= "    </table><BR>";
        }
    }

    //clzc -spqx
    function SetStyle($campo){
        if($this->frmError[$campo] || $campo=="MensajeError"){
                if ($campo=="MensajeError"){
                        return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
                }
                return ("label_error");
        }
        return ("label");
    }

    function InsertarProcedReqLiquidacion($TipoDocumento,$Documento,$nombrePaciente,$cuenta,$ingreso,$contadorProc,$procedimientoBus,$codigoBus,$tipoProcedimiento){
    $this->salida .= ThemeAbrirTabla('BUSCADOR PROCEDIMIENTOS QX');
        $action=ModuloGetURL('app','DatosLiquidacionQX','user','SeleccionProcedimientoQX',array("TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"contadorProc"=>$contadorProc,"ingreso"=>$ingreso));
        $this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
        $this->Encabezado();
    $this->salida .= "  <table width=\"50%\" border=\"0\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">DATOS PACIENTE</legend>";
        $this->salida .= "    <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"98%\" align=\"center\">";
    $this->salida .= "     <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"20%\" class=\"label\">PACIENTE</td>";
        $this->salida .= "      <td colspan=\"3\">".$TipoDocumento." ".$Documento." - ".$nombrePaciente."</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"20%\" class=\"label\">No. CUENTA</td>";
        $this->salida .= "      <td>".$cuenta."</td>";
        $this->salida .= "      <td width=\"20%\" class=\"label\">No. INGRESO</td>";
        $this->salida .= "      <td>".$ingreso."</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    </table>";
        $this->salida .= "   </fieldset>";
        $this->salida .= "   </td></tr>";
        $this->salida .= "   </table><br>";
        if($_SESSION['Liquidacion_QX']['ULTIMO_PROCEDIMIENTO']){
            $grupoUltimoProcedimiento=$this->SeleccionGrupoUltimoProcedimiento();
            if(empty($tipoProcedimiento)){
                if($grupoUltimoProcedimiento){
                    $tipoProcedimiento=$grupoUltimoProcedimiento;
                }
            }
        }
        $this->salida .= "    <table width=\"70%\" border=\"0\" class=\"normal_10\" align=\"center\">";
        $this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"4\">PARAMENTROS DE BUSQUEDA</td></tr>";
        $this->salida .= "        <tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("tipoProcedimiento")."\">TIPOS PROCEDIMIENTOS:</td>";
        $this->salida .= "        <td colspan=\"3\"><select name=\"tipoProcedimiento\" class=\"select\">";
      $tiposProcedimientos=$this->tiposdeProcedimientos();
        $this->salida .="       <option value=\"-1\">----seleccionar---</option>";
        for($i=0;$i<sizeof($tiposProcedimientos);$i++){
            $value=$tiposProcedimientos[$i]['tipo_cargo'].'/'.$tiposProcedimientos[$i]['grupo_tipo_cargo'];
            $titulo=$tiposProcedimientos[$i]['descripcion'];
            if($value==$tipoProcedimiento){
            $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
            }else{
                $this->salida .=" <option value=\"$value\">$titulo</option>";
            }
        }
      $this->salida .= "      </select></td>";
        $this->salida.= "       </tr>";
        $this->salida .= "    <tr class=\"modulo_list_claro\">";
    $this->salida .= "    <td class=\"label\">PROCEDIMIENTO</td>";
    $this->salida .= "    <td><input size=\"60\" type=\"text\" name=\"procedimientoBus\" value=\"".$procedimientoBus."\" class=\"input-submit\"></td>";
    $this->salida .= "    <td class=\"label\">CODIGO</td>";
        $this->salida .= "    <td><input type=\"text\" size=\"10\" name=\"codigoBus\" value=\"".$codigoBus."\" class=\"input-submit\"></td>";
    $this->salida .= "    </tr>";
        $this->salida .= "    <tr class=\"modulo_list_claro\"><td colspan=\"4\" align=\"center\">";
    $this->salida .= "    <input type=\"submit\" name=\"filtrar\" value=\"FILTRAR\" class=\"input-submit\">";
        $this->salida .= "    <input type=\"submit\" name=\"volver\" value=\"VOLVER\" class=\"input-submit\">";
    $this->salida .= "    </td></tr>";
        $this->salida .= "    </table><BR>";
    $procedimientos=$this->BusquedaProcedimientosQX($tipoProcedimiento,$codigoBus,$procedimientoBus);
    if($procedimientos){
      $this->salida .= "    <table border=\"0\" width=\"85%\" align=\"center\" class=\"normal_10\">";
      $this->salida .= "    <tr class=\"modulo_table_list_title\">";
      $this->salida .= "    <td>TIPO PROCEDIMIENTO</td>";
            $this->salida .= "    <td>PROCEDIMIENTO</td>";
            $this->salida .= "    <td>DESCRIPCION</td>";
            $this->salida .= "    <td>&nbsp;</td>";
            $this->salida .= "    </tr>";
            for($i=0;$i<sizeof($procedimientos);$i++){
        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
        $this->salida .= "  <tr class=\"$estilo\">";
                $this->salida .= "  <td width=\"20%\">".$procedimientos[$i]['grupo_tipo_cargo']."</td>";
                $this->salida .= "  <td width=\"15%\">".$procedimientos[$i]['cargo']."</td>";
                $this->salida .= "  <td width=\"60%\">".$procedimientos[$i]['descripcion']."</td>";
                if($procedimientos[$i]['sw_bilateral']=='1'){
          $sw_bilateral='1';
                }else{
          $sw_bilateral='0';
                }
                $actionSelect=ModuloGetURL('app','DatosLiquidacionQX','user','SeleccionProcedimientoQX',array("TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso,"contadorProc"=>$contadorProc,"procedimientoSelect"=>$procedimientos[$i]['cargo']."||//".$procedimientos[$i]['descripcion']."||//".$sw_bilateral));
                $this->salida .= "  <td align=\"center\" width=\"5%\"><a href=\"$actionSelect\"><img border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"><a></td>";
                $this->salida .= "  </tr>";
                $y++;
            }
            $this->salida .= "    </table>";
            $this->salida .=$this->RetornarBarra(1);
        }else{
      $this->salida .= "    <table width=\"60%\" border=\"0\" align=\"center\">";
      $this->salida .= "    <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON REGISTROS CON ESTOS PARAMETROS</td></tr>";
      $this->salida .= "      </table><BR>";
        }
        $this->salida .= "      </form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    function BuscadorProfesional($TipoDocumento,$Documento,$nombrePaciente,$cuenta,$ingreso,$TipoDocumentoBus,$DocumentoBus,$NomcirujanoBus){

        $this->salida .= ThemeAbrirTabla('BUSCADOR ESPECIALISTA');
        $action=ModuloGetURL('app','DatosLiquidacionQX','user','SeleccionProfesionalBuscador',array("TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso));
        $this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
        $this->Encabezado();
    $this->salida .= "    <table width=\"60%\" border=\"0\" align=\"center\">";
        $this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"3\">PARAMENTROS DE BUSQUEDA</td></tr>";
        $this->salida .= "    <tr class=\"modulo_list_claro\">";
        $this->salida .= "    <td class=\"label\">IDENTIFICACION</td>";
    $this->salida .= "    <td><select name=\"TipoDocumentoBus\" class=\"select\">";
        $tipos=$this->tipo_id_paciente();
        foreach($tipos as $value=>$titulo){
            if($value==$TipoDocumentoBus){
                $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
            }else{
                $this->salida .=" <option value=\"$value\">$titulo</option>";
            }
        }
        $this->salida .= "     </select></td>";
        $this->salida .= "       <td><input type=\"text\" class=\"input-text\" name=\"DocumentoBus\" size=\"32\" maxlength=\"32\" value=\"$DocumentoBus\"></td>";
    $this->salida .= "     </tr>";
        $this->salida .= "     <tr class=\"modulo_list_claro\">";
        $this->salida .= "     <td class=\"label\">NOMBRES</td>";
        $this->salida .= "     <td colspan=\"2\">";
        $this->salida .= "     <input size=\"50\" type=\"text\" name=\"NomcirujanoBus\" value=\"".$NomcirujanoBus."\" class=\"input-submit\">&nbsp&nbsp&nbsp;";
        $this->salida .= "     <input class=\"input-submit\" type=\"submit\" name=\"Filtrar\" value=\"FILTRAR\" class=\"input-submit\">";
        $this->salida .= "     <input class=\"input-submit\" type=\"submit\" name=\"Volver\" value=\"VOLVER\" class=\"input-submit\">";
        $this->salida .= "     </td>";
        $this->salida .= "    </table><BR>";
        $profesionales=$this->profesionalesEspecialista($TipoDocumentoBus,$DocumentoBus,$NomcirujanoBus,$barra=1);
        if($profesionales){
          $this->salida .= "    <table width=\"60%\" border=\"0\" align=\"center\">";
      $this->salida .= "    <tr class=\"modulo_table_list_title\">";
            $this->salida .= "    <td>IDENTIFICACION</td>";
            $this->salida .= "    <td>NOMBRE</td>";
            $this->salida .= "    <td>&nbsp;</td>";
            $this->salida .= "    </tr>";
            for($i=0;$i<sizeof($profesionales);$i++){
        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
        $this->salida .= "    <tr class=\"$estilo\">";
        $this->salida .= "    <td width=\"30%\">".$profesionales[$i]['tipo_id_tercero']." ".$profesionales[$i]['tercero_id']."</td>";
                $this->salida .= "    <td>".$profesionales[$i]['nombre']."</td>";
                $actionSelect=ModuloGetURL('app','DatosLiquidacionQX','user','SeleccionProfesionalBuscador',array("TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso,"cirSeleccionado"=>$profesionales[$i]['tipo_id_tercero']."||//".$profesionales[$i]['tercero_id']."||//".$profesionales[$i]['nombre']));
                $this->salida .= "    <td align=\"center\" width=\"5%\"><a href=\"$actionSelect\"><img title=\"Seleccionar Cirujano\" border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"><a></td>";
                $this->salida .= "    </tr>";
                $y++;
            }
            $this->salida .= "    </table><BR>";
            $this->salida .=$this->RetornarBarra();
        }else{
      $this->salida .= "    <table width=\"60%\" border=\"0\" align=\"center\">";
      $this->salida .= "    <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON REGISTROS CON ESTOS PARAMETROS</td></tr>";
      $this->salida .= "      </table><BR>";
        }
    $this->salida .= "      </form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

  function BuscadorDiagnosticos($TipoDocumento,$Documento,$nombrePaciente,$cuenta,$ingreso,$procedimiento,$numDiagnostico,$codigoBus,$descripcionBus){

    $this->salida .= ThemeAbrirTabla('BUSCADOR DIAGNOSTICO');
        $action=ModuloGetURL('app','DatosLiquidacionQX','user','SeleccionDiagnosticoBuscador',array("TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso,"procedimiento"=>$procedimiento,"numDiagnostico"=>$numDiagnostico));
        $this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
        $this->Encabezado();
    $this->salida .= "    <table width=\"70%\" border=\"0\" align=\"center\">";
        $this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"4\">PARAMENTROS DE BUSQUEDA</td></tr>";
        $this->salida .= "    <tr class=\"modulo_list_claro\">";
    $this->salida .= "     <td class=\"label\">CODIGO</td>";
        $this->salida .= "        <td><input type=\"text\" class=\"input-text\" name=\"codigoBus\" size=\"10\" value=\"$codigoBus\"></td>";
        $this->salida .= "     <td class=\"label\">DESCRIPCION</td>";
    $this->salida .= "     <td><input size=\"50\" type=\"text\" name=\"descripcionBus\" value=\"".$descripcionBus."\" class=\"input-submit\"></td>";
    $this->salida .= "    </tr>";
    $this->salida .= "    <tr class=\"modulo_list_claro\"><td align=\"center\" colspan=\"4\">";
        $this->salida .= "     <input class=\"input-submit\" type=\"submit\" name=\"Filtrar\" value=\"FILTRAR\" class=\"input-submit\">";
        $this->salida .= "     <input class=\"input-submit\" type=\"submit\" name=\"Volver\" value=\"VOLVER\" class=\"input-submit\">";
    $this->salida .= "    </td></tr>";
        $this->salida .= "    </table><BR>";
        $diagnosticos=$this->DiagnosticosBD($codigoBus,$descripcionBus);
        if($diagnosticos){
          $this->salida .= "    <table width=\"70%\" border=\"0\" align=\"center\">";
      $this->salida .= "    <tr class=\"modulo_table_list_title\">";
            $this->salida .= "    <td width=\"20%\">CODIGO</td>";
            $this->salida .= "    <td>DESCRIPCION</td>";
            $this->salida .= "    <td width=\"5%\">&nbsp;</td>";
            $this->salida .= "    </tr>";
            for($i=0;$i<sizeof($diagnosticos);$i++){
        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
        $this->salida .= "    <tr class=\"$estilo\">";
        $this->salida .= "    <td>".$diagnosticos[$i]['diagnostico_id']."</td>";
                $this->salida .= "    <td>".$diagnosticos[$i]['diagnostico_nombre']."</td>";
                $actionSelect=ModuloGetURL('app','DatosLiquidacionQX','user','SeleccionDiagnosticoBuscador',array("cetinela"=>1,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso,"procedimiento"=>$procedimiento,"numDiagnostico"=>$numDiagnostico,"codigoDiagnostico"=>$diagnosticos[$i]['diagnostico_id'],"diagnostico_nombre"=>$diagnosticos[$i]['diagnostico_nombre']));
                $this->salida .= "    <td align=\"center\" width=\"5%\"><a href=\"$actionSelect\"><img title=\"Seleccionar Cirujano\" border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"><a></td>";
                $this->salida .= "    </tr>";
                $y++;
            }
            $this->salida .= "    </table><BR>";
            $this->salida .=$this->RetornarBarra(2);
        }else{
      $this->salida .= "    <table width=\"60%\" border=\"0\" align=\"center\">";
      $this->salida .= "    <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON REGISTROS CON ESTOS PARAMETROS</td></tr>";
      $this->salida .= "      </table><BR>";
        }
    $this->salida .= "      </form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
  }

    function CalcularNumeroPasos($conteo){
        $numpaso=ceil($conteo/$this->limit);
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

     function RetornarBarra($origen){

        if($this->limit>=$this->conteo){
            return '';
        }
        $paso=$_REQUEST['paso'];
        if(empty($paso)){
            $paso=1;
        }
        if($origen==1){
            $accion=ModuloGetURL('app','DatosLiquidacionQX','user','SeleccionProcedimientoQX',array('conteo'=>$this->conteo,"paso"=>$_REQUEST['paso'],"Of"=>$_REQUEST['Of'],
            "filtrar"=>1,"TipoDocumento"=>$_REQUEST['TipoDocumento'],"Documento"=>$_REQUEST['Documento'],"nombrePaciente"=>$_REQUEST['nombrePaciente'],"cuenta"=>$_REQUEST['cuenta'],
            "ingreso"=>$_REQUEST['ingreso'],"contadorProc"=>$_REQUEST['contadorProc'],"procedimientoBus"=>$_REQUEST['procedimientoBus'],"codigoBus"=>$_REQUEST['codigoBus'],
            "tipoProcedimiento"=>$_REQUEST['tipoProcedimiento']));
        }elseif($origen==2){
      $accion=ModuloGetURL('app','DatosLiquidacionQX','user','SeleccionDiagnosticoBuscador',array('conteo'=>$this->conteo,"paso"=>$_REQUEST['paso'],"Of"=>$_REQUEST['Of'],
            "Filtrar"=>1,"TipoDocumento"=>$_REQUEST['TipoDocumento'],"Documento"=>$_REQUEST['Documento'],"nombrePaciente"=>$_REQUEST['nombrePaciente'],"cuenta"=>$_REQUEST['cuenta'],
            "ingreso"=>$_REQUEST['ingreso'],"procedimiento"=>$_REQUEST['procedimiento'],"codigoBus"=>$_REQUEST['codigoBus'],"descripcionBus"=>$_REQUEST['descripcionBus'],"numDiagnostico"=>$_REQUEST['numDiagnostico']));
    }else{
      $accion=ModuloGetURL('app','DatosLiquidacionQX','user','SeleccionProfesionalBuscador',array('conteo'=>$this->conteo,"paso"=>$_REQUEST['paso'],"Of"=>$_REQUEST['Of'],
            "Filtrar"=>1,"TipoDocumento"=>$_REQUEST['TipoDocumento'],"Documento"=>$_REQUEST['Documento'],"nombrePaciente"=>$_REQUEST['nombrePaciente'],"cuenta"=>$_REQUEST['cuenta'],
            "ingreso"=>$_REQUEST['ingreso'],"TipoDocumentoBus"=>$_REQUEST['TipoDocumentoBus'],"DocumentoBus"=>$_REQUEST['DocumentoBus'],"NomcirujanoBus"=>$_REQUEST['NomcirujanoBus']));
        }
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
        $this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan='15' align='center'>P�gina $paso de $numpasos</td><tr></table>";
    }

  //FUNCIONES QUE ACOMPA�AN AL CALENDARIO
/**
* Funcion que Saca los anos para el calendario a partir del a�o actual
* @return array
*/
  function AnosAgenda($Seleccionado='False',$ano){

        $anoActual=date("Y");
        //$ano = $anoActual;
        $anoActual1=$anoActual-10;
        for($i=0;$i<=20;$i++){
            $vars[$i]=$anoActual1;
            $anoActual1=$anoActual1+1;
        }
    switch($Seleccionado){
      case 'False':{
        foreach($vars as $value=>$titulo){
          if($titulo==$ano){
            $this->salida .=" <option value=\"$titulo\" selected>$titulo</option>";
          }else{
            $this->salida .=" <option value=\"$titulo\">$titulo</option>";
          }
        }
        break;
      }case 'True':{
        foreach($vars as $value=>$titulo){
          if($titulo==$ano){
            $this->salida .=" <option value=\"$titulo\" selected>$titulo</option>";
          }else{
            $this->salida .=" <option value=\"$titulo\">$titulo</option>";
          }
        }
        break;
      }
    }
  }

  function MesesAgenda($Seleccionado='False',$A�o,$Defecto){
    $anoActual=date("Y");
    $vars[1]='ENERO';
    $vars[2]='FEBRERO';
    $vars[3]='MARZO';
    $vars[4]='ABRIL';
    $vars[5]='MAYO';
    $vars[6]='JUNIO';
    $vars[7]='JULIO';
    $vars[8]='AGOSTO';
    $vars[9]='SEPTIEMBRE';
    $vars[10]='OCTUBRE';
    $vars[11]='NOVIEMBRE';
    $vars[12]='DICIEMBRE';
    //$mesActual=date("m");
    switch($Seleccionado){
      case 'False':{
        if($anoActual==$A�o){
          foreach($vars as $value=>$titulo){
            if($value>=$mesActual){
              if($value==$Defecto){
                $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
              }else{
                $this->salida .=" <option value=\"$value\">$titulo</option>";
              }
           }
         }
       }else{
        foreach($vars as $value=>$titulo){
          if($value==$Defecto){
            $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
          }else{
            $this->salida .=" <option value=\"$value\">$titulo</option>";
          }
        }
       }
      break;
      }
      case 'True':{
        if($anoActual==$A�o){
          foreach($vars as $value=>$titulo){
            if($value>=$mesActual){
              if($value==$Defecto){
                $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
              }else{
                $this->salida .=" <option value=\"$value\">$titulo</option>";
              }
            }
          }
        }else{
          foreach($vars as $value=>$titulo){
            if($value==$Defecto){
              $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
            }else{
              $this->salida .=" <option value=\"$value\">$titulo</option>";
            }
                }
            }
            break;
        }
      }
  }

  /**
* La funcion FormaMensaje se encarga de retornar un mensaje para el usuario
* @return boolean
* @param string mensaje a retornar para el usuario
* @param string titulo de la ventana a mostrar
* @param string lugar a donde debe retornar la ventana
* @param boolean tipo boton de la ventana
*/

    function FormaMensaje($mensaje,$titulo,$accion,$boton,$origen){
			
        $this->salida .= ThemeAbrirTabla($titulo);
        $this->salida .= "                <table class=\"normal_10\" width=\"60%\" align=\"center\">";
        $this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "                     <tr><td colspan=\"2\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>";
        if($boton){
        		$this->salida .= "                     <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"AceptarMen\" value=\"$boton\"></td></tr>";
        }else{
					$this->salida .= "                     <tr><td colspan=\"2\" align=\"center\">";						
					if($origen==1){						
						$this->salida .= "                   <input class=\"input-submit\" type=\"submit\" name=\"CancelarProceso\" value=\"Cancelar\">";
					}
					$this->salida .= "                     <input class=\"input-submit\" type=\"submit\" name=\"AceptarMen\" value=\"Aceptar\">";
					$this->salida .= "                     </td></tr>";
      	}
        $this->salida .= "               </form>";
        $this->salida .= "               </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

  function EliminarRelacionCuentasDetalle($NoLiquidacion,$TipoDocumento,$Documento,$nombrePaciente,$cuenta,$ingreso,$bandera){

    $this->salida .= ThemeAbrirTabla("ELIMINACION DE CARGOS DE CIRUGIA");
    if($bandera==1){
    $accion=ModuloGetURL('app','DatosLiquidacionQX','user','EliminaCuentaReliquidacion',array("NoLiquidacion"=>$NoLiquidacion,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso));
    }else{
    $accion=ModuloGetURL('app','DatosLiquidacionQX','user','ElimacionCargosCirugiaCuantasDet',array("NoLiquidacion"=>$NoLiquidacion,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso));
    }
    $this->salida .= " <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
    $this->Encabezado();
    $this->salida .= "  <table width=\"50%\" border=\"0\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">DATOS PACIENTE</legend>";
        $this->salida .= "    <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"98%\" align=\"center\">";
    $this->salida .= "     <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"20%\" class=\"label\">PACIENTE</td>";
        $this->salida .= "      <td colspan=\"3\">".$TipoDocumento." ".$Documento." - ".$nombrePaciente."</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"20%\" class=\"label\">No. CUENTA</td>";
        $this->salida .= "      <td>".$cuenta."</td>";
        $this->salida .= "      <td width=\"20%\" class=\"label\">No. INGRESO</td>";
        $this->salida .= "      <td>".$ingreso."</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    </table>";
        $this->salida .= "   </fieldset>";
        $this->salida .= "   </td></tr>";
        $this->salida .= "   </table><br>";
        $this->salida .= " <table class=\"normal_10\" width=\"60%\" align=\"center\">";
        $this->salida .= " <tr><td class=\"label_error\" align=\"center\">ESTA LIQUIDACION YA SE ENCUENTRA CARGADA EN LA CUENTA, PARA RELIQUIDARLA EL SISTEMA DEBE ELIMINAR LOS CARGOS EXISTENTES, SI DESEA CONTINUAR DE CLICK EN LA OPCION ACEPTAR</td></tr>";
        $this->salida .= "  <tr><td align=\"center\">";
    $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"CANCELAR\">";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table>";
    $this->salida .= "  </form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
  }

  function EliminarRelacionLiquidacion($TipoDocumento,$Documento,$nombrePaciente,$cuenta,$ingreso){

    $this->salida .= ThemeAbrirTabla("ELIMINACION DE LA LIQUIDACION CIRUGIA");
    $accion=ModuloGetURL('app','DatosLiquidacionQX','user','EliminacionCargosLiquidacionCirugia',array("TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso));
    $this->salida .= " <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
    $this->Encabezado();
    $this->salida .= "  <table width=\"50%\" border=\"0\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">DATOS PACIENTE</legend>";
        $this->salida .= "    <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"98%\" align=\"center\">";
    $this->salida .= "     <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"20%\" class=\"label\">PACIENTE</td>";
        $this->salida .= "      <td colspan=\"3\">".$TipoDocumento." ".$Documento." - ".$nombrePaciente."</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"20%\" class=\"label\">No. CUENTA</td>";
        $this->salida .= "      <td>".$cuenta."</td>";
        $this->salida .= "      <td width=\"20%\" class=\"label\">No. INGRESO</td>";
        $this->salida .= "      <td>".$ingreso."</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    </table>";
        $this->salida .= "   </fieldset>";
        $this->salida .= "   </td></tr>";
        $this->salida .= "   </table><br>";
        $this->salida .= " <table class=\"normal_10\" width=\"60%\" align=\"center\">";
        $this->salida .= " <tr><td class=\"label_error\" align=\"center\">AL REALIZAR CAMBIOS EN LOS DATOS DE LA CIRUGIA SE ELIMINA LA LIQUIDACION EXISTENTE</td></tr>";
        $this->salida .= "  <tr><td align=\"center\">";
    $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"CANCELAR\">";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table>";
    $this->salida .= "  </form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
  }

  /*function FormaSeleccionBodegaCargaInsumos($liquidacionId,$TipoDocumento,$Documento,$nombrePaciente,$cuenta,$ingreso){
    $this->salida .= ThemeAbrirTabla("SELECCION BODEGA DESCARGO DE INSUMOS Y MEDICAMENTOS");
    $accion=ModuloGetURL('app','DatosLiquidacionQX','user','SeleccionDocumentoParaIYM',array("liquidacionId"=>$liquidacionId,
    "TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso));
    $this->salida .= " <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
    $this->Encabezado();
    $this->salida .= "  <table width=\"50%\" border=\"0\" align=\"center\">";
    $this->salida .= "  <tr><td align=\"center\">";
        $this->salida .=    $this->SetStyle("MensajeError");
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">DATOS PACIENTE</legend>";
        $this->salida .= "    <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"98%\" align=\"center\">";
    $this->salida .= "     <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"20%\" class=\"label\">No. LIQUIDACION</td>";
        $this->salida .= "      <td colspan=\"3\">".$liquidacionId."</td>";
        $this->salida .= "    </tr>";
    $this->salida .= "     <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"20%\" class=\"label\">PACIENTE</td>";
        $this->salida .= "      <td colspan=\"3\">".$TipoDocumento." ".$Documento." - ".$nombrePaciente."</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"20%\" class=\"label\">No. CUENTA</td>";
        $this->salida .= "      <td>".$cuenta."</td>";
        $this->salida .= "      <td width=\"20%\" class=\"label\">No. INGRESO</td>";
        $this->salida .= "      <td>".$ingreso."</td>";
        $this->salida .= "    </tr>";
    $bodegas=$this->BodegasPermisosDescargoIyM();
    if($bodegas){
      $_SESSION['LIQUIDACION_QX']['CENTRO_UTILIDAD']=$bodegas['centro_utilidad'];
      $_SESSION['LIQUIDACION_QX']['BODEGA']=$bodegas['bodega'];
      $_SESSION['LIQUIDACION_QX']['NOM_BODEGA']=$bodegas['descripcion'];
      $this->salida .= "    <tr class=\"modulo_list_claro\">";
      $this->salida .= "    <td width=\"15%\" class=\"".$this->SetStyle("bodega")."\">BODEGA</td>";
      $this->salida .= "        <td colspan=\"3\">".$_SESSION['LIQUIDACION_QX']['BODEGA']." ".$_SESSION['LIQUIDACION_QX']['NOM_BODEGA']."</td>";
      $this->salida .= "    <tr class=\"modulo_list_claro\">";
      $this->salida .= "    <td width=\"15%\" class=\"".$this->SetStyle("movimiento")."\">TIPO MOVIMIENTO</td>";
      $this->salida .= "        <td colspan=\"3\"><select name=\"movimiento\" class=\"select\">";
      if(empty($_REQUEST['movimiento']) || $_REQUEST['movimiento']=='I'){
        $this->salida .="   <option value=\"I\" selected>INGRESO</option>";
        $this->salida .="   <option value=\"E\">EGRESO</option>";
      }elseif($_REQUEST['movimiento']=='E'){
        $this->salida .="   <option value=\"E\" selected>EGRESO</option>";
        $this->salida .="   <option value=\"I\">INGRESO</option>";
      }
      $this->salida .= "   </select></td>";
      $this->salida .= "    </tr>";
    }else{
      $this->salida .= "    <tr class=\"modulo_list_claro\">";
      $this->salida .= "    <td colspan=\"4\" align=\"center\" class=\"label_error\">EL USUARIO NO TIENE PERMISOS PARA TRABAJAR SOBRE ALGUNA BODEGA</td>";
      $this->salida .= "    </tr>";
    }
        $this->salida .= "    </table>";
        $this->salida .= "   </fieldset>";
        $this->salida .= "   </td></tr>";
        $this->salida .= "   </table><br>";

    $this->salida .= " <table class=\"normal_10\" width=\"60%\" align=\"center\">";
        $this->salida .= "  <tr>";
    $this->salida .= "  <td width=\"50%\" align=\"right\">";
    $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"CREAR NUEVO DOCUMENTO\">";
    $this->salida .= "  </td>";
    $this->salida .= "  </form>";
    $accion=ModuloGetURL('app','DatosLiquidacionQX','user','DatosPacientes');
    $this->salida .= " <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
    $this->salida .= "  <td width=\"50%\" align=\"left\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"VOLVER\">";
        $this->salida .= "  </td>";
    $this->salida .= "  </form>";
    $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
    $DocumentosBodega=$this->DocumentosBodegaCreados($liquidacionId);
    if($DocumentosBodega){
          $this->salida .= "    <BR><table width=\"100%\" border=\"0\" align=\"center\">";
      $this->salida .= "    <tr class=\"modulo_table_list_title\">";
            $this->salida .= "    <td width=\"10%\">DOCUMENTO</td>";
            $this->salida .= "    <td width=\"20%\">TIPO</td>";
      $this->salida .= "    <td width=\"10%\">BODEGA</td>";
            $this->salida .= "    <td width=\"10%\">FECHA</td>";
      $this->salida .= "    <td>PRODUCTO</td>";
      $this->salida .= "    <td width=\"5%\">CANTIDAD</td>";
      $this->salida .= "    <td width=\"5%\">COSTO</td>";
            $this->salida .= "    </tr>";
      $documentoAnt=-1;
            for($i=0;$i<sizeof($DocumentosBodega);$i++){
        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
        $this->salida .= "    <tr class=\"$estilo\">";
        if($DocumentosBodega[$i]['bodegas_doc_id'].'-'.$DocumentosBodega[$i]['numeracion'] != $documentoAnt){
          $this->salida .= "    <td rowspan=\"".$DocumentosBodega[$i]['contador']."\">".$DocumentosBodega[$i]['bodegas_doc_id']."<br><label class=\"label\">No.:</label>".$DocumentosBodega[$i]['numeracion']."</td>";
          $this->salida .= "    <td rowspan=\"".$DocumentosBodega[$i]['contador']."\">".$DocumentosBodega[$i]['nomdocumento']." <br><label align=\"center\">- ".$DocumentosBodega[$i]['tipomov']." -</label></td>";
          $this->salida .= "    <td rowspan=\"".$DocumentosBodega[$i]['contador']."\">".$_SESSION['LIQUIDACION_QX']['BODEGA']." ".$_SESSION['LIQUIDACION_QX']['NOM_BODEGA']."</td>";
          (list($ano,$mes,$dia)=explode('-',$DocumentosBodega[$i]['fecha']));
          $this->salida .= "    <td rowspan=\"".$DocumentosBodega[$i]['contador']."\">".strftime('%d %b de %Y',mktime(0,0,0,$mes,$dia,$ano))."</td>";
          $this->salida .= "    <td>".$DocumentosBodega[$i]['codigo_producto']."  ".$DocumentosBodega[$i]['descripcion']."</td>";
          $this->salida .= "    <td>".$DocumentosBodega[$i]['cantidad']."</td>";
          $this->salida .= "    <td>".$DocumentosBodega[$i]['total']."</td>";
          $documentoAnt=$DocumentosBodega[$i]['bodegas_doc_id'].'-'.$DocumentosBodega[$i]['numeracion'];
        }else{
          $this->salida .= "    <td>".$DocumentosBodega[$i]['codigo_producto']."  ".$DocumentosBodega[$i]['descripcion']."</td>";
          $this->salida .= "    <td>".$DocumentosBodega[$i]['cantidad']."</td>";
          $this->salida .= "    <td>".$DocumentosBodega[$i]['total']."</td>";
        }
                $this->salida .= "    </tr>";
                $y++;
            }
            $this->salida .= "    </table><BR>";
    }
    $liquidar=$this->ConfirmarLiquidacionCuenta($liquidacionId);
    if($liquidar==1){
      $this->salida .= " <table class=\"normal_10\" width=\"90%\" align=\"center\">";
      $this->salida .= "    <tr><td width=\"100%\" align=\"center\">";
      $this->salida .= "    <img border = 0 src=\"".GetThemePath()."/images/cargar.png\">&nbsp&nbsp&nbsp;<b>INSUMOS CARGADOS A LA CUENTA</b>";
      $this->salida .= "    </td></tr>";
      $this->salida .= "    </table>";
    }elseif($liquidar==2){
      $this->salida .= " <table class=\"normal_10\" width=\"90%\" align=\"center\">";
      $this->salida .= "    <tr><td width=\"100%\" align=\"center\">";
      $action=ModuloGetURL('app','DatosLiquidacionQX','user','CargarInsumosMedicamentosCuenta',array("liquidacionId"=>$liquidacionId,
      "TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso));
      $this->salida .= "    <a href=\"$action\"><img border = 0 src=\"".GetThemePath()."/images/cargar.png\">&nbsp&nbsp&nbsp;<b>CARGAR A LA CUENTA</b></a>";
      $this->salida .= "    </td></tr>";
      $this->salida .= "    </table>";
    }else{
      $this->salida .= " <table class=\"normal_10\" width=\"90%\" align=\"center\">";
      $this->salida .= "    <tr><td width=\"100%\" align=\"center\">";
      $this->salida .= "    <img border = 0 src=\"".GetThemePath()."/images/cargar.png\">&nbsp&nbsp&nbsp;<b>LA LIQUIDACION NO HA SIDO CARGADA A LA CUENTA</b>";
      $this->salida .= "    </td></tr>";
      $this->salida .= "    </table>";
    }
        $this->salida .= ThemeCerrarTabla();
        return true;
  }*/

  /*function CreacionDocumentosBodegas($liquidacionId,$TipoDocumento,$Documento,$nombrePaciente,$cuenta,$ingreso,
    $movimiento,$bodegasDocId,$nomdocumento,$ProductoFechaVence,$NomProductoFechaVence){

    $this->salida .= ThemeAbrirTabla("CREACION DOCUMENTO DE BODEGA");
    $this->salida .="<script>\n\n";
    $this->salida .= "  function ValidaSolicitud(frm){\n";
    $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
    $this->salida .= "      if(frm.elements[i].type=='text'){";
    $this->salida .= "        if(frm.elements[i+1].value=='' || frm.elements[i+1].value<=0 || parseInt(frm.elements[i].value,10) < parseInt(frm.elements[i+1].value,10)){";
    $this->salida .= "                  alert('Las Cantidad Solicitada no puede ser igual a 0 o mayor a las Existencias de la bodega');";
    $this->salida .= "                  return false; \n";
    $this->salida .= "        }";
    $this->salida .= "        i++;";
    $this->salida .= "      }";
    $this->salida .= "    }";
    $this->salida .= "    frm.submit();";
    $this->salida .= "  }";
    $this->salida .= "  function InsertarFechasVencimiento(x,y){\n";
    $this->salida .= "    document.formabuscar.productoFV.value=x;";
    $this->salida .= "    document.formabuscar.NomproductoFV.value=y;";
    $this->salida .= "    document.formabuscar.submit();";
    $this->salida .= "  }";
    $this->salida .= "  function EliminarProductoDocBodega(y){\n";
    $this->salida .= "    document.formabuscar.producto.value=y;";
    $this->salida .= "    document.formabuscar.submit();";
    $this->salida .= "  }";
    $this->salida .="</script>\n\n";
    $this->Encabezado();
    $accion=ModuloGetURL('app','DatosLiquidacionQX','user','InsertarDocumentosBodegasCirugia',array("liquidacionId"=>$liquidacionId,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso,
    "movimiento"=>$movimiento,"bodegasDocId"=>$bodegasDocId,"nomdocumento"=>$nomdocumento));
    $this->salida .= " <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
    $this->salida .= "  <table width=\"60%\" border=\"0\" align=\"center\">";
    $this->salida .= "  <tr><td align=\"center\">";
        $this->salida .=    $this->SetStyle("MensajeError");
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">DATOS PACIENTE</legend>";
        $this->salida .= "    <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"98%\" align=\"center\">";
    $this->salida .= "     <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"20%\" class=\"label\">No. LIQUIDACION</td>";
        $this->salida .= "      <td colspan=\"3\">".$liquidacionId."</td>";
        $this->salida .= "    </tr>";
    $this->salida .= "     <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"20%\" class=\"label\">PACIENTE</td>";
        $this->salida .= "      <td colspan=\"3\">".$TipoDocumento." ".$Documento." - ".$nombrePaciente."</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"20%\" class=\"label\">No. CUENTA</td>";
        $this->salida .= "      <td>".$cuenta."</td>";
        $this->salida .= "      <td width=\"20%\" class=\"label\">No. INGRESO</td>";
        $this->salida .= "      <td>".$ingreso."</td>";
        $this->salida .= "    </tr>";
    $this->salida .= "     <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"20%\" class=\"label\">TIPO DOCUMENTO</td>";
    if($movimiento=='I'){
      $mov='INGRESO';
    }elseif($movimiento=='E'){
      $mov='EGRESO';
    }
        $this->salida .= "      <td colspan=\"3\"><b>".$nomdocumento." - ".$mov."</b></td>";
        $this->salida .= "    </tr>";
    $this->salida .= "     <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"20%\" class=\"label\">BODEGA</td>";
        $this->salida .= "      <td colspan=\"3\">".$_SESSION['LIQUIDACION_QX']['BODEGA']." ".$_SESSION['LIQUIDACION_QX']['NOM_BODEGA']."</td>";
        $this->salida .= "    </tr>";
    $this->salida .= "    </table>";
        $this->salida .= "   </fieldset>";
        $this->salida .= "   </td></tr>";
        $this->salida .= "   </table><BR>";
    if($movimiento=='E'){
    $this->salida .= " <table class=\"normal_10\" width=\"90%\" align=\"center\">";
        $this->salida .= "  <tr>";
    $this->salida .= "  <td width=\"100%\" align=\"center\">";
    $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"SeleccionProducto\" value=\"SELECCIONAR PRODUCTO\">";
    $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"SeleccionPaquete\" value=\"SELECCIONAR PAQUETE\">";
    $this->salida .= "  </td>";
    $this->salida .= "  </tr>";
    $this->salida .= "  </table><BR>";
    }
    if(sizeof($_SESSION['IYM_CIRUGIA'])>0){
    $this->salida.="   <table  align=\"center\" border=\"0\"  width=\"98%\">";
    $this->salida.="   <tr class=\"modulo_table_list_title\"><td colspan=\"7\">PRODUCTOS SELECCIONADOS</td></tr>";
        $this->salida.="   <tr class=\"modulo_table_list_title\">";
        $this->salida.="   <td align=\"center\" nowrap width=\"15%\">CODIGO</td>";
    $this->salida.="   <td align=\"center\">DESCRIPCION</td>";
    $this->salida.="   <td align=\"center\" nowrap width=\"10%\">EXIST. BODEGAS</td>";
    $this->salida.="   <td align=\"center\" nowrap width=\"5%\">&nbsp;</td>";
    $this->salida.="   <td align=\"center\" nowrap width=\"20%\">FECHAS VENCIMIENTO</td>";
    $this->salida.="   <td align=\"center\" nowrap width=\"10%\">CANTIDAD</td>";
    $this->salida.="   <td align=\"center\" nowrap width=\"5%\">&nbsp;</td>";
    $this->salida.="   </tr>";
    $y=0;
    foreach($_SESSION['IYM_CIRUGIA'] as $codigoPro=>$descripcionPro){
      if($y % 2){$estilo='modulo_list_claro';$estilo1='modulo_list_oscuro';}else {$estilo='modulo_list_oscuro';$estilo1='modulo_list_claro';}
      $this->salida.="   <tr class=\"$estilo\">";
      $this->salida.="   <td align=\"center\">$codigoPro</td>";
      $this->salida.="   <td align=\"center\">$descripcionPro</td>";
      $this->salida.="   <td align=\"center\"><input size=\"10\" type=\"text\" readonly class=\"text-input\" name=\"Existencias[".$codigoPro."]\" value=\"".$_SESSION['IYM_EXISTENCIAS'][$codigoPro]."\"></td>";
      if($movimiento=='I' && $_SESSION['IYM_CIRUGIA_FV'][$codigoPro]==1){
        $this->salida.="   <td align=\"center\"><a href=\"javascript:InsertarFechasVencimiento('$codigoPro','$descripcionPro')\"><img title=\"Insertar Fechas de Vencimiento\" border=\"0\" src=\"".GetThemePath()."/images/pparamed.png\"></td>";
        $this->salida.="   <td align=\"center\">";
        if(sizeof($_SESSION['IYM_CIRUGIA_FV_PRODUCTOS'])>0){
          $this->salida .= "         <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\">";
          $this->salida .= "        <tr class=\"modulo_table_title\" align=\"center\">";
          $this->salida .= "               <td>FECHA</td>";
          $this->salida .= "         <td>LOTE</td>";
          $this->salida .= "               <td>CANT.</td>";
          $this->salida .= "               <td>&nbsp;</td>";
          $this->salida .= "        </tr>";
          foreach($_SESSION['IYM_CIRUGIA_FV_PRODUCTOS'][$codigoPro] as $lote=>$arreglo){
            (list($cantidades,$fecha)=explode('||//',$arreglo));
            $this->salida .= "        <tr class=\"$estilo1\" align=\"center\">";
            $this->salida .= "             <td>$fecha</td>";
            $this->salida .= "         <td>$lote</td>";
            $this->salida .= "             <td>$cantidades</td>";
            $actionEliminaFV=ModuloGetURL('app','DatosLiquidacionQX','user','EliminarLoteProducto',array("liquidacionId"=>$liquidacionId,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso,
            "movimiento"=>$movimiento,"bodegasDocId"=>$bodegasDocId,"nomdocumento"=>$nomdocumento,"producto"=>$codigoPro,"LoteProducto"=>$lote));
            $this->salida .= "             <td><a href=\"$actionEliminaFV\"><img title=\"Eliminar Lote\" border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></td>";
            $this->salida .= "        </tr>";
          }
          $this->salida .= "         </table>";
        }else{
          $this->salida .= "  &nbsp;";
        }
        $this->salida .= "  </td>";
      }else{
        $this->salida.="   <td align=\"center\">&nbsp;</td>";
        $this->salida.="   <td align=\"center\">&nbsp;</td>";
      }
      $this->salida.="   <td align=\"center\"><input size=\"10\" type=\"text\" class=\"text-input\" name=\"Cantidad[".$codigoPro."]\" value=\"".$_SESSION['IYM_CIRUGIA_CANTIDADES'][$codigoPro]."\"></td>";
      $this->salida.="   <td align=\"center\"><a href=\"javascript:EliminarProductoDocBodega('$codigoPro')\"><img title=\"Eliminar Producto\" border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></td>";
      $this->salida.="   </tr>";
      $y++;
    }
    $this->salida.="      </table>";
    $this->salida .= " <table class=\"normal_10\" width=\"98%\" align=\"center\">";
    $this->salida .= "  <tr><td width=\"100%\" align=\"right\">";
    if($movimiento=='I'){
    $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"Guardar\" value=\"GUARDAR DOCUMENTO\">";
    }else{
        $this->salida .= "  <input class=\"input-submit\" type=\"button\" name=\"Guardar\" onclick=\"return ValidaSolicitud(this.form);\" value=\"GUARDAR DOCUMENTO\">";
    }
        $this->salida .= "  </td></tr>";
        $this->salida .= "  </table><BR>";
    //Continene el producto a Insertar las Fechas de Vencimiento
    $this->salida.="   <input type=\"hidden\" name=\"productoFV\">";
    $this->salida.="   <input type=\"hidden\" name=\"NomproductoFV\">";
    $this->salida.="   <input type=\"hidden\" name=\"producto\">";
    //Fin
    if(!empty($ProductoFechaVence)){

      $this->salida .= "<input type=\"hidden\" name=\"ProductoFechaVence\" value=\"$ProductoFechaVence\">";
      $this->salida .= "<input type=\"hidden\" name=\"NomProductoFechaVence\" value=\"$NomProductoFechaVence\">";

      $this->salida .= "<table class=\"normal_10\" border=\"0\" width=\"98%\" align=\"center\">";
      $sumaCantLotes=0;
      if($_SESSION['IYM_CIRUGIA_FV_PRODUCTOS'][$ProductoFechaVence]){
      foreach($_SESSION['IYM_CIRUGIA_FV_PRODUCTOS'][$ProductoFechaVence] as  $lote=>$arreglo){
        (list($cantidades,$fecha)=explode('||//',$arreglo));
        $sumaCantLotes+=$cantidades;
      }
      $cantidadRest=$_SESSION['IYM_CIRUGIA_CANTIDADES'][$ProductoFechaVence]-$sumaCantLotes;
      }else{
      $cantidadRest=$_SESSION['IYM_CIRUGIA_CANTIDADES'][$ProductoFechaVence];
      }
      $this->salida .= "<tr class=\"modulo_table_title\">";
      $this->salida .= "<td width=\"80%\" nowrap align=\"center\">$ProductoFechaVence - $NomProductoFechaVence</td>";
      $this->salida .= "<td width=\"20%\" align=\"center\">CANTIDAD QUE FALTA POR INSERTAR ".$cantidadRest."</td></tr>";
      $this->salida .= "<tr><td colspan=\"2\" class=\"modulo_list_claro\">";
      $this->salida .= "  <table class=\"normal_10\" cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
      $this->salida .= "   <tr class=\"modulo_list_oscuro\">";
      $this->salida .= "   <td class=\"".$this->SetStyle("cantidadLote")."\">CANTIDAD</td><td><input type=\"text\" name=\"cantidadLote\" value=\"".$_REQUEST['cantidadLote']."\"></td>";
      $this->salida .= "   <td class=\"".$this->SetStyle("NoLote")."\">NUMERO LOTE</td><td><input type=\"text\" name=\"NoLote\" value=\"".$_REQUEST['NoLote']."\"></td>";
      $this->salida .= "     <td class=\"".$this->SetStyle("FechaVmto")."\">FECHA VENCIMIENTO </td>";
      $this->salida .= "     <td><input type=\"text\" name=\"FechaVmto\" size=\"10\" readonly value=\"".$_REQUEST['FechaVmto']."\" class=\"input-text\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\">";
      $this->salida .= "     ".ReturnOpenCalendario('formabuscar','FechaVmto','/')."</td>";
      $this->salida.= "   </tr>";
      $this->salida.= "   <tr>";
      $this->salida .= "     <td align=\"center\" colspan=\"6\"><input class=\"input-submit\" type=\"submit\" name=\"insertarFV\" value=\"INSERTAR\"></td>";
      $this->salida.= "   </tr>";
      $this->salida.= "   </table>";
      $this->salida .= "</td></tr>";
      $this->salida.= "</table>";
    }
    }else{
      $this->salida.="   <table  align=\"center\" border=\"0\"  width=\"98%\">";
      $this->salida.="   <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON DATOS</td></tr>";
      $this->salida.="   </table>";
    }
    $this->salida .= "  </form>";
    $accion=ModuloGetURL('app','DatosLiquidacionQX','user','SeleccionBodegaCargaInsumos',array("liquidacionId"=>$liquidacionId,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso));
    $this->salida .= " <form name=\"forma\" action=\"$accion\" method=\"post\">";
    $this->salida .= " <table class=\"normal_10\" width=\"90%\" align=\"center\">";
    $this->salida .= "  <tr>";
    $this->salida .= "  <td width=\"100%\" align=\"center\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"VOLVER\">";
        $this->salida .= "  </td>";
    $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
    $this->salida .= "  </form>";
    $this->salida .= ThemeCerrarTabla();
        return true;
  }*/

  /*function BuscadorProductoInv($liquidacionId,$TipoDocumento,$Documento,$nombrePaciente,$cuenta,$ingreso,
    $movimiento,$bodegasDocId,$nomdocumento,$codigoBus,$DescripcionBus){

    $this->salida .= ThemeAbrirTabla('BUSCADOR PRODUCTOS INVENTARIOS');
        $action=ModuloGetURL('app','DatosLiquidacionQX','user','SeleccionProductoInventariosQx',array("liquidacionId"=>$liquidacionId,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso,
    "movimiento"=>$movimiento,"bodegasDocId"=>$bodegasDocId,"nomdocumento"=>$nomdocumento));
        $this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
        $this->Encabezado();
    $this->salida .= "    <table width=\"80%\" border=\"0\" align=\"center\">";
        $this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"4\">PARAMENTROS DE BUSQUEDA</td></tr>";
        $this->salida .= "    <tr class=\"modulo_list_claro\">";
    $this->salida .= "     <td class=\"label\">CODIGO</td>";
        $this->salida .= "        <td><input type=\"text\" class=\"input-text\" name=\"codigoBus\" size=\"10\" value=\"$codigoBus\"></td>";
        $this->salida .= "     <td class=\"label\">DESCRIPCION</td>";
    $this->salida .= "     <td><input size=\"70\" type=\"text\" name=\"DescripcionBus\" value=\"".$DescripcionBus."\" class=\"input-submit\"></td>";
    $this->salida .= "    </tr>";
    $this->salida .= "    <tr class=\"modulo_list_claro\"><td align=\"center\" colspan=\"4\">";
        $this->salida .= "     <input class=\"input-submit\" type=\"submit\" name=\"Filtrar\" value=\"FILTRAR\" class=\"input-submit\">";
        $this->salida .= "     <input class=\"input-submit\" type=\"submit\" name=\"Volver\" value=\"VOLVER\" class=\"input-submit\">";
    $this->salida .= "    </td></tr>";
        $this->salida .= "    </table><BR>";
        $ProductosBodega=$this->ProductosInventariosBodega($codigoBus,$DescripcionBus);
    if($ProductosBodega){
          $this->salida .= "    <table width=\"80%\" border=\"0\" align=\"center\">";
      $this->salida .= "    <tr class=\"modulo_table_list_title\">";
            $this->salida .= "    <td width=\"20%\">CODIGO</td>";
            $this->salida .= "    <td>DESCRIPCION</td>";
      $this->salida .= "    <td width=\"15%\">EXISTENCIAS</td>";
            $this->salida .= "    <td width=\"5%\">&nbsp;</td>";
            $this->salida .= "    </tr>";
            for($i=0;$i<sizeof($ProductosBodega);$i++){
        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
        $this->salida .= "    <tr class=\"$estilo\">";
        $this->salida .= "    <td>".$ProductosBodega[$i]['codigo_producto']."</td>";
                $this->salida .= "    <td>".$ProductosBodega[$i]['descripcion']."</td>";
        $this->salida .= "    <td>".$ProductosBodega[$i]['existencia']."</td>";
                $actionSelect=ModuloGetURL('app','DatosLiquidacionQX','user','SeleccionProductoInventariosQx',array("liquidacionId"=>$liquidacionId,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso,
        "movimiento"=>$movimiento,"bodegasDocId"=>$bodegasDocId,"nomdocumento"=>$nomdocumento,"producto"=>$ProductosBodega[$i]['codigo_producto'],"descripcion"=>$ProductosBodega[$i]['descripcion'],"existencia"=>$ProductosBodega[$i]['existencia']));
                $this->salida .= "    <td align=\"center\" width=\"5%\"><a href=\"$actionSelect\"><img title=\"Seleccionar Producto\" border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"><a></td>";
                $this->salida .= "    </tr>";
                $y++;
            }
            $this->salida .= "    </table><BR>";
            $Paginador = new ClaseHTML();
      $this->actionPaginador=ModuloGetURL('app','DatosLiquidacionQX','user','SeleccionProductoInventariosQx',array("liquidacionId"=>$liquidacionId,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso,
      "movimiento"=>$movimiento,"bodegasDocId"=>$bodegasDocId,"nomdocumento"=>$nomdocumento,"Filtrar"=>1,"codigoBus"=>$codigoBus,"DescripcionBus"=>$DescripcionBus));
            $this->salida .= "      ".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->actionPaginador);
        }else{
      $this->salida .= "    <table width=\"60%\" border=\"0\" align=\"center\">";
      $this->salida .= "    <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON REGISTROS CON ESTOS PARAMETROS</td></tr>";
      $this->salida .= "      </table><BR>";
        }
    $this->salida .= "      </form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
  }*/

  /*function BuscadorPaquetesInv($liquidacionId,$TipoDocumento,$Documento,$nombrePaciente,$cuenta,$ingreso,
    $movimiento,$bodegasDocId,$nomdocumento,$codigoBus,$DescripcionBus){
    $this->paginaActual = 1;
    $this->offset = 0;
    if($_REQUEST['offset']){
      $this->paginaActual = intval($_REQUEST['offset']);
      if($this->paginaActual > 1){
        $this->offset = ($this->paginaActual - 1) * ($this->limit);
      }
    }
    $this->salida .= ThemeAbrirTabla('BUSCADOR PAQUETES INVENTARIOS');
        $action=ModuloGetURL('app','DatosLiquidacionQX','user','SeleccionPaquetesInventariosQx',array("liquidacionId"=>$liquidacionId,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso,
    "movimiento"=>$movimiento,"bodegasDocId"=>$bodegasDocId,"nomdocumento"=>$nomdocumento,"offset"=>$this->paginaActual));
        $this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
        $this->Encabezado();
    $this->salida .= "    <table width=\"80%\" border=\"0\" align=\"center\">";
        $this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"4\">PARAMENTROS DE BUSQUEDA</td></tr>";
        $this->salida .= "    <tr class=\"modulo_list_claro\">";
    $this->salida .= "     <td class=\"label\">CODIGO PAQUETE</td>";
        $this->salida .= "        <td><input type=\"text\" class=\"input-text\" name=\"codigoBus\" size=\"10\" value=\"$codigoBus\"></td>";
        $this->salida .= "     <td class=\"label\">DESCRIPCION</td>";
    $this->salida .= "     <td><input size=\"70\" type=\"text\" name=\"DescripcionBus\" value=\"".$DescripcionBus."\" class=\"input-submit\"></td>";
    $this->salida .= "    </tr>";
    $this->salida .= "    <tr class=\"modulo_list_claro\"><td align=\"center\" colspan=\"4\">";
        $this->salida .= "     <input class=\"input-submit\" type=\"submit\" name=\"Filtrar\" value=\"FILTRAR\" class=\"input-submit\">";
        $this->salida .= "     <input class=\"input-submit\" type=\"submit\" name=\"Volver\" value=\"VOLVER\" class=\"input-submit\">";
    $this->salida .= "    </td></tr>";
        $this->salida .= "    </table><BR>";
        $PaquetesBodega=$this->PaquetesInventariosBodega($liquidacionId,$codigoBus,$DescripcionBus);
    if($PaquetesBodega){
          $this->salida .= "    <table width=\"80%\" border=\"0\" align=\"center\">";
      $this->salida .= "    <tr class=\"modulo_table_list_title\">";
            $this->salida .= "    <td width=\"20%\">CODIGO</td>";
            $this->salida .= "    <td>DESCRIPCION</td>";
      $this->salida .= "    <td width=\"5%\">&nbsp;</td>";
            $this->salida .= "    <td width=\"5%\">&nbsp;</td>";
            $this->salida .= "    </tr>";
            for($i=0;$i<sizeof($PaquetesBodega);$i++){
        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
        $this->salida .= "    <tr class=\"$estilo\">";
        $this->salida .= "    <td>".$PaquetesBodega[$i]['paquete_insumos_id']."</td>";
                $this->salida .= "    <td>".$PaquetesBodega[$i]['descripcion']."</td>";
        $actionSelect=ModuloGetURL('app','DatosLiquidacionQX','user','ConsultaPaquetesInventariosQx',array("liquidacionId"=>$liquidacionId,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso,
        "movimiento"=>$movimiento,"bodegasDocId"=>$bodegasDocId,"nomdocumento"=>$nomdocumento,"codigoBus"=>$codigoBus,"DescripcionBus"=>$DescripcionBus,
        "paqueteId"=>$PaquetesBodega[$i]['paquete_insumos_id'],"nomPaquete"=>$PaquetesBodega[$i]['descripcion']));
                $this->salida .= "    <td align=\"center\" width=\"5%\"><a href=\"$actionSelect\"><img title=\"Consultar Productos Paquete\" border=\"0\" src=\"".GetThemePath()."/images/pconsultar.png\"><a></td>";

        $chequeado='';
        if($_SESSION['PAQUETES_CIRUGIA'][$PaquetesBodega[$i]['paquete_insumos_id']]==1){
          $chequeado='checked';
        }
                $this->salida .= "    <td align=\"center\" width=\"5%\"><input type=\"checkbox\" name=\"Seleccion[".$PaquetesBodega[$i]['paquete_insumos_id']."]\" value=\"1\" $chequeado></td>";
        if($chequeado){
          $this->salida .= "        <input type=\"hidden\" name=\"SeleccionActual[".$PaquetesBodega[$i]['paquete_insumos_id']."]\" value=\"1\"></td>";
        }
                $this->salida .= "    </tr>";
                $y++;
            }
            $this->salida .= "    </table>";
      $this->salida .= "    <table width=\"80%\" border=\"0\" align=\"center\">";
      $this->salida .= "      <tr><td align=\"right\"><input type=\"submit\" name=\"SeleccionPaquete\" class=\"input-submit\" value=\"SELECCIONAR\"></td></tr>";
      $this->salida .= "      </table><BR>";
            $Paginador = new ClaseHTML();
      $this->actionPaginador=ModuloGetURL('app','DatosLiquidacionQX','user','SeleccionPaquetesInventariosQx',array("liquidacionId"=>$liquidacionId,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso,
      "movimiento"=>$movimiento,"bodegasDocId"=>$bodegasDocId,"nomdocumento"=>$nomdocumento,"Filtrar"=>1,"codigoBus"=>$codigoBus,"DescripcionBus"=>$DescripcionBus,"offset"=>$this->paginaActual));
            $this->salida .= "      ".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->actionPaginador);
        }else{
      $this->salida .= "    <table width=\"60%\" border=\"0\" align=\"center\">";
      $this->salida .= "    <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON REGISTROS CON ESTOS PARAMETROS</td></tr>";
      $this->salida .= "      </table><BR>";
        }
    $this->salida .= "      </form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
  }*/

  /*function LlamaConsultaPaquetesInventariosQx($liquidacionId,$TipoDocumento,$Documento,$nombrePaciente,$cuenta,$ingreso,
    $movimiento,$bodegasDocId,$nomdocumento,$paqueteId,$nomPaquete,
    $codigoBus,$DescripcionBus){

    $this->salida .= ThemeAbrirTabla('PRODUCTOS QUE CONTIENEN LOS PAQUETES');
        $action=ModuloGetURL('app','DatosLiquidacionQX','user','SeleccionPaquetesInventariosQx',array("liquidacionId"=>$liquidacionId,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso,
    "movimiento"=>$movimiento,"bodegasDocId"=>$bodegasDocId,"nomdocumento"=>$nomdocumento,"Filtrar"=>1,"codigoBus"=>$codigoBus,"DescripcionBus"=>$DescripcionBus));
        $this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
        $this->Encabezado();
    $ProductosPaquetes=$this->ProductosPaquetesInventariosBodega($paqueteId);
    if($ProductosPaquetes){
          $this->salida .= "    <table width=\"80%\" border=\"0\" align=\"center\">";
      $this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"3\" align=\"3\">$nomPaquete</td></tr>";
      $this->salida .= "    <tr class=\"modulo_table_list_title\">";
            $this->salida .= "    <td width=\"20%\">CODIGO</td>";
            $this->salida .= "    <td>DESCRIPCION</td>";
      $this->salida .= "    <td width=\"10%\">CANTIDAD</td>";
            $this->salida .= "    </tr>";
            for($i=0;$i<sizeof($ProductosPaquetes);$i++){
        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
        $this->salida .= "    <tr class=\"$estilo\">";
        $this->salida .= "    <td>".$ProductosPaquetes[$i]['codigo_producto']."</td>";
                $this->salida .= "    <td>".$ProductosPaquetes[$i]['descripcion']."</td>";
        $this->salida .= "    <td>".$ProductosPaquetes[$i]['cantidad']."</td>";
                $this->salida .= "    </tr>";
                $y++;
            }
            $this->salida .= "    </table>";
        }else{
      $this->salida .= "    <table width=\"60%\" border=\"0\" align=\"center\">";
      $this->salida .= "    <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON REGISTROS CON ESTOS PARAMETROS</td></tr>";
      $this->salida .= "      </table>";
        }
    $this->salida .= "    <table width=\"60%\" border=\"0\" align=\"center\">";
    $this->salida .= "    <tr><td align=\"center\"><input type=\"submit\" value=\"VOLVER\" class=\"input-submit\" name=\"volveer\"></td></tr>";
    $this->salida .= "    </table>";
    $this->salida .= "      </form>";
    $this->salida .= ThemeCerrarTabla();
        return true;
  }*/

 /**
* Funcion que halla el costo de un producto
* @return array
* @param string empresa a la que pertenece la bodega donde se va a crear el documento
* @param string codigo unico que identifica la producto
*/



  function FormaEquivalentesLiquidacion($NoLiquidacion,$TipoDocumento,$Documento,$nombrePaciente,$cuenta,$ingreso,
    $TipoDocumentoFil,$DocumentoFil,$NoIngresoFil,$NoCuentaFil,$EstadoFil,$FechaCirugiaFil){

    $this->salida .= ThemeAbrirTabla('TARIFARIOS EQUIVALENTES DE LOS PROCEDIMIENTOS');
        $action=ModuloGetURL('app','DatosLiquidacionQX','user','IntertarEquivalentesLiquidacion',array("NoLiquidacion"=>$NoLiquidacion,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso,
    "TipoDocumentoFil"=>$TipoDocumentoFil,"DocumentoFil"=>$DocumentoFil,"NoIngresoFil"=>$NoIngresoFil,"NoCuentaFil"=>$NoCuentaFil,"EstadoFil"=>$EstadoFil,"FechaCirugiaFil"=>$FechaCirugiaFil));
    $this->salida .="<script>\n\n";
    $this->salida .=" function VerificacionEquivalentes(frm){";
    $dat=$this->TraeProcedimientosCirugia($NoLiquidacion);
    for($i=0;$i<sizeof($dat);$i++){
      $this->salida .="   if(frm.SeleccionId".$dat[$i]['consecutivo_procedimiento'].".value==''){";
      $this->salida .="     alert('Por cada Procedimiento debe Realizar la Seleccion del Tarifario con el que desea Liquidar');";
      $this->salida .="     return false;";
      $this->salida .="   }";
    }
    $this->salida .=" }";
    $this->salida .="</script>\n\n";
        $this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
    $this->Encabezado();
    //$this->salida .= "  <input type=\"hidden\" name=\"lorena\" value=\"lorena1\">";
        $this->salida .= "  <table width=\"40%\" border=\"0\" align=\"center\">";
        $this->salida .= "  <tr><td align=\"center\">";
        $this->salida .=    $this->SetStyle("MensajeError");
        $this->salida .= "  </td></tr>";
    $this->salida .= "   </table>";
        $this->salida .= "  <table width=\"70%\" border=\"0\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">DATOS DEL PROCEDIMIENTO</legend>";
        $this->salida .= "    <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"98%\" align=\"center\">";
    $this->salida .= "    <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"25%\" class=\"label\">No. LIQUIDACION</td>";
        $this->salida .= "      <td colspan=\"3\">".$NoLiquidacion."</td>";
        $this->salida .= "    </tr>";
    $this->salida .= "    <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"25%\" class=\"label\">PACIENTE</td>";
        $this->salida .= "      <td colspan=\"3\">".$TipoDocumento." ".$Documento." - ".$nombrePaciente."</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"25%\" class=\"label\">No. CUENTA</td>";
        $this->salida .= "      <td>".$cuenta."</td>";
        $this->salida .= "      <td width=\"25%\" class=\"label\">No. INGRESO</td>";
        $this->salida .= "      <td>".$ingreso."</td>";
        $this->salida .= "    </tr>";
    $datosCirugia=$this->TraeDatosCirugia($NoLiquidacion);
    $estado=$datosCirugia['estado'];
    $reliquidada=$datosCirugia['reliquidada'];
    $this->salida .= "    <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"25%\" class=\"label\">VIA ACCESO</td>";
        $this->salida .= "      <td>".$datosCirugia['via']."</td>";
        $this->salida .= "      <td width=\"25%\" class=\"label\">AMBITO</td>";
        $this->salida .= "      <td>".$datosCirugia['ambito']."</td>";
        $this->salida .= "    </tr>";
    $this->salida .= "    <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"25%\" class=\"label\">FINALIDAD</td>";
        $this->salida .= "      <td>".$datosCirugia['finalidad']."</td>";
        $this->salida .= "      <td width=\"25%\" class=\"label\">TIPO</td>";
        $this->salida .= "      <td>".$datosCirugia['tipo']."</td>";
        $this->salida .= "    </tr>";
    $this->salida .= "    <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"25%\" class=\"label\">FECHA</td>";
    (list($fecha,$hora)=explode(' ',$datosCirugia['fecha_cirugia']));
    (list($ano,$mes,$dia)=explode('-',$fecha));
    (list($hora,$minutos)=explode(':',$hora));
        $this->salida .= "      <td>".ucwords(strftime("%b %d de %Y %H:%M",mktime($hora,$minutos,0,$mes,$dia,$ano)))."</td>";
        $this->salida .= "      <td width=\"25%\" class=\"label\">DURACION (HH:mm)</td>";
        $this->salida .= "      <td>".$datosCirugia['duracion_cirugia']."</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    </table>";
        $this->salida .= "   </fieldset>";
        $this->salida .= "   </td></tr>";
        $this->salida .= "   </table><br><BR>";

    $this->salida .= "    <table border=\"0\" width=\"25%\" align=\"center\" class=\"normal_10\">";
    $this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"2\">SELECCION DE DERECHOS PARA LIQUIDAR</td></tr>";
    $chequeado='';
    if($_REQUEST['der_cirujano']){$chequeado='checked';}
    $this->salida .= "    <tr class=\"modulo_list_claro\"><td class=\"label\">CIRUJANO</td><td width=\"5%\"><input $chequeado type=\"checkbox\" value=\"1\" name=\"der_cirujano\"></td></tr>";
    $chequeado='';
    if($_REQUEST['der_anestesiologo']){$chequeado='checked';}
    $this->salida .= "    <tr class=\"modulo_list_claro\"><td class=\"label\">ANESTESIOLOGO</td><td width=\"5%\"><input $chequeado type=\"checkbox\" value=\"1\" name=\"der_anestesiologo\"></td></tr>";
    $chequeado='';
    if($_REQUEST['der_ayudante']){$chequeado='checked';}
    $this->salida .= "    <tr class=\"modulo_list_claro\"><td class=\"label\">AYUDANTE</td><td width=\"5%\"><input $chequeado type=\"checkbox\" value=\"1\" name=\"der_ayudante\"></td></tr>";
    $chequeado='';
    if($_REQUEST['der_sala']){$chequeado='checked';}
    $this->salida .= "    <tr class=\"modulo_list_claro\"><td class=\"label\">SALA</td><td width=\"5%\"><input $chequeado type=\"checkbox\" value=\"1\" name=\"der_sala\"></td></tr>";
    /*$chequeado='';
    if($_REQUEST['der_materiales']){$chequeado='checked';}
    $this->salida .= "    <tr class=\"modulo_list_claro\"><td class=\"label\">MATERIALES</td><td width=\"5%\"><input $chequeado type=\"checkbox\" value=\"1\" name=\"der_materiales\"></td></tr>";
    */
        $chequeado='';
    if($_REQUEST['der_equipos']){$chequeado='checked';}
    $this->salida .= "    <tr class=\"modulo_list_claro\"><td class=\"label\">EQUIPOS MEDICOS</td><td width=\"5%\"><input $chequeado type=\"checkbox\" value=\"1\" name=\"der_equipos\"></td></tr>";
        //$chequeado='';
    //if($_REQUEST['der_insumos_consumo']){$chequeado='checked';}
    //$this->salida .= "    <tr class=\"modulo_list_claro\"><td class=\"label\">INSUMOS AL CONSUMO</td><td width=\"5%\"><input $chequeado type=\"checkbox\" value=\"1\" name=\"der_insumos_consumo\"></td></tr>";
    $this->salida .= "   </table><BR>";

    $procedimientos=$this->GetEquivalenciasCargosLiquidacion($NoLiquidacion);
    if($procedimientos){
      $this->salida .= "    <table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
      $this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"2\">PROCEDIMIENTOS Y SELECCION DE EQUIVALENCIAS&nbsp&nbsp&nbsp&nbsp; - &nbsp&nbsp&nbsp&nbsp PLAN: ".$procedimientos[0]['plan_descripcion']."</td></tr>";
      $cirujanoAnt=-1;
      $procedimientoAnt=-1;
      for($i=0;$i<sizeof($procedimientos);$i++){
        $consec=$procedimientos[$i]['consecutivo_procedimiento_prin'];
        if($cirujanoAnt!=$procedimientos[$i]['cirujano_prin']){
           $this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"2\">".$procedimientos[$i]['nombre_tercero_prin']."</td></tr>";
           if($procedimientoAnt!=$procedimientos[$i]['cargo_cups_prin']){
              $this->salida .= "    <tr class=\"modulo_list_oscuro\">";
              $this->salida .= "    <td class=\"label\">".$procedimientos[$i]['cargo_cups_prin']." - ".$procedimientos[$i]['descripcion_prin']."</td>";
              if($procedimientos[$i]['sw_bilateral']==1){
              $this->salida .= "    <td width=\"11%\" nowrap class=\"label\">BILATERAL</td>";
              }else{
              $this->salida .= "    <td class=\"label\">&nbsp;</td>";
              }
              $this->salida .= "    </tr>";
              if(empty($procedimientos[$i]['tarifario_id']) && empty($procedimientos[$i]['cargo'])){
                $this->salida .= "    <tr class=\"modulo_list_oscuro\"><td colspan=\"2\">";
                $this->salida .= "        <table border=\"0\" width=\"98%\" align=\"center\" class=\"normal_10\">";
                $this->salida .= "        <tr class=\"modulo_list_claro\">";
                $this->salida .= "        <td class=\"label_error\" align=\"center\">NO SE ENCONTRARON EQUIVALENCIAS</td>";
                $this->salida .= "        </tr>";
                $this->salida .= "        </table>";
                $this->salida .= "    </td></tr>";
              }else{
                $this->salida .= "    <tr class=\"modulo_list_oscuro\"><td colspan=\"2\">";
                $this->salida .= "        <table border=\"0\" width=\"98%\" align=\"center\" class=\"normal_10\">";
                $this->salida .= "        <tr class=\"modulo_list_claro\">";
                $this->salida .= "        <td width=\"10%\">".$procedimientos[$i]['nomtarifario']."</td>";
                $this->salida .= "        <td width=\"10%\">".$procedimientos[$i]['cargo']."</td>";
                $this->salida .= "        <td>".$procedimientos[$i]['descripcion']."</td>";
                $this->salida .= "        <td align=\"center\" width=\"12%\"><label class=\"label\">BILATERAL</label>&nbsp;&nbsp;<input title=\"Bilateral\" type=\"checkbox\" name=\"Bilateral[".$procedimientos[$i]['consecutivo_procedimiento_prin']."]\" value=\"1\"></td>";
                $this->salida .= "        <td align=\"center\" width=\"5%\"><input title=\"Seleccion\" checked type=\"checkbox\" name=\"Seleccion[$i][".$procedimientos[$i]['consecutivo_procedimiento_prin']."]\" value=\"".$procedimientos[$i]['tarifario_id']."||//".$procedimientos[$i]['cargo']."\" id=\"SeleccionId$consec\"></td>";
                $this->salida .= "        </tr>";
                $this->salida .= "        </table>";
                $this->salida .= "    </td></tr>";
              }
              $procedimientoAnt=$procedimientos[$i]['cargo_cups_prin'];
           }else{
              if(empty($procedimientos[$i]['tarifario_id']) && empty($procedimientos[$i]['cargo'])){
                $this->salida .= "    <tr class=\"modulo_list_oscuro\"><td colspan=\"2\">";
                $this->salida .= "        <table border=\"0\" width=\"98%\" align=\"center\" class=\"normal_10\">";
                $this->salida .= "        <tr class=\"modulo_list_claro\">";
                $this->salida .= "        <td class=\"label_error\" align=\"center\">NO SE ENCONTRARON EQUIVALENCIAS</td>";
                $this->salida .= "        </tr>";
                $this->salida .= "        </table>";
                $this->salida .= "    </td></tr>";
              }else{
                $this->salida .= "    <tr class=\"modulo_list_oscuro\"><td colspan=\"2\">";
                $this->salida .= "        <table border=\"0\" width=\"98%\" align=\"center\" class=\"normal_10\">";
                $this->salida .= "        <tr class=\"modulo_list_claro\">";
                $this->salida .= "        <td width=\"10%\">".$procedimientos[$i]['nomtarifario']."</td>";
                $this->salida .= "        <td width=\"10%\">".$procedimientos[$i]['cargo']."</td>";
                $this->salida .= "        <td>".$procedimientos[$i]['descripcion']."</td>";
                $this->salida .= "        <td align=\"center\" width=\"12%\"><label class=\"label\">BILATERAL</label>&nbsp;&nbsp;<input title=\"Bilateral\" type=\"checkbox\" name=\"Bilateral[".$procedimientos[$i]['consecutivo_procedimiento_prin']."]\" value=\"1\"></td>";
                $this->salida .= "        <td align=\"center\" width=\"5%\"><input title=\"Seleccion\" type=\"checkbox\" name=\"Seleccion[$i][".$procedimientos[$i]['consecutivo_procedimiento_prin']."]\" value=\"".$procedimientos[$i]['tarifario_id']."||//".$procedimientos[$i]['cargo']."\" id=\"SeleccionId$consec\"></td>";
                $this->salida .= "        </tr>";
                $this->salida .= "        </table>";
                $this->salida .= "    </td></tr>";
              }
           }
           $cirujanoAnt=$procedimientos[$i]['cirujano_prin'];
        }else{
          if($procedimientoAnt!=$procedimientos[$i]['cargo_cups_prin']){
              $this->salida .= "    <tr class=\"modulo_list_oscuro\">";
              $this->salida .= "    <td class=\"label\">".$procedimientos[$i]['cargo_cups_prin']." - ".$procedimientos[$i]['descripcion_prin']."</td>";
              if($procedimientos[$i]['sw_bilateral']==1){
              $this->salida .= "    <td width=\"15%\" nowrap class=\"label\">BILATERAL</td>";
              }else{
              $this->salida .= "    <td class=\"label\">&nbsp;</td>";
              }
              $this->salida .= "    </tr>";
              if(empty($procedimientos[$i]['tarifario_id']) && empty($procedimientos[$i]['cargo'])){
                $this->salida .= "    <tr class=\"modulo_list_oscuro\"><td colspan=\"2\">";
                $this->salida .= "        <table border=\"0\" width=\"98%\" align=\"center\" class=\"normal_10\">";
                $this->salida .= "        <tr class=\"modulo_list_claro\">";
                $this->salida .= "        <td class=\"label_error\" align=\"center\">NO SE ENCONTRARON EQUIVALENCIAS</td>";
                $this->salida .= "        </tr>";
                $this->salida .= "        </table>";
                $this->salida .= "    </td></tr>";
              }else{
                $this->salida .= "    <tr class=\"modulo_list_oscuro\"><td colspan=\"2\">";
                $this->salida .= "        <table border=\"0\" width=\"98%\" align=\"center\" class=\"normal_10\">";
                $this->salida .= "        <tr class=\"modulo_list_claro\">";
                $this->salida .= "        <td width=\"10%\">".$procedimientos[$i]['nomtarifario']."</td>";
                $this->salida .= "        <td width=\"10%\">".$procedimientos[$i]['cargo']."</td>";
                $this->salida .= "        <td>".$procedimientos[$i]['descripcion']."</td>";
                $this->salida .= "        <td align=\"center\" width=\"12%\"><label class=\"label\">BILATERAL</label>&nbsp;&nbsp;<input title=\"Bilateral\" type=\"checkbox\" name=\"Bilateral[".$procedimientos[$i]['consecutivo_procedimiento_prin']."]\" value=\"1\"></td>";
                $this->salida .= "        <td align=\"center\" width=\"5%\"><input title=\"Seleccion\" checked type=\"checkbox\" name=\"Seleccion[$i][".$procedimientos[$i]['consecutivo_procedimiento_prin']."]\" value=\"".$procedimientos[$i]['tarifario_id']."||//".$procedimientos[$i]['cargo']."\" id=\"SeleccionId$consec\"></td>";
                $this->salida .= "        </tr>";
                $this->salida .= "        </table>";
                $this->salida .= "    </td></tr>";
              }
              $procedimientoAnt=$procedimientos[$i]['cargo_cups_prin'];
          }else{
              if(empty($procedimientos[$i]['tarifario_id']) && empty($procedimientos[$i]['cargo'])){
                $this->salida .= "    <tr class=\"modulo_list_oscuro\"><td colspan=\"2\">";
                $this->salida .= "        <table border=\"0\" width=\"98%\" align=\"center\" class=\"normal_10\">";
                $this->salida .= "        <tr class=\"modulo_list_claro\">";
                $this->salida .= "        <td class=\"label_error\" align=\"center\">NO SE ENCONTRARON EQUIVALENCIAS</td>";
                $this->salida .= "        </tr>";
                $this->salida .= "        </table>";
                $this->salida .= "    </td></tr>";
              }else{
                $this->salida .= "    <tr class=\"modulo_list_oscuro\"><td colspan=\"2\">";
                $this->salida .= "        <table border=\"0\" width=\"98%\" align=\"center\" class=\"normal_10\">";
                $this->salida .= "        <tr class=\"modulo_list_claro\">";
                $this->salida .= "        <td width=\"10%\">".$procedimientos[$i]['nomtarifario']."</td>";
                $this->salida .= "        <td width=\"10%\">".$procedimientos[$i]['cargo']."</td>";
                $this->salida .= "        <td>".$procedimientos[$i]['descripcion']."</td>";
                $this->salida .= "        <td align=\"center\" width=\"12%\"><label class=\"label\">BILATERAL</label>&nbsp;&nbsp;<input title=\"Bilateral\" type=\"checkbox\" name=\"Bilateral[".$procedimientos[$i]['consecutivo_procedimiento_prin']."]\" value=\"1\"></td>";
                $this->salida .= "        <td align=\"center\" width=\"5%\"><input title=\"Seleccion\" type=\"checkbox\" name=\"Seleccion[$i][".$procedimientos[$i]['consecutivo_procedimiento_prin']."]\" value=\"".$procedimientos[$i]['tarifario_id']."||//".$procedimientos[$i]['cargo']."\" id=\"SeleccionId$consec\"></td>";
                $this->salida .= "        </tr>";
                $this->salida .= "        </table>";
                $this->salida .= "    </td></tr>";
             }
          }
        }
      }
      $this->salida .= "      </table>";
      $this->salida .= "    <table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
      $this->salida .= "    <tr><td align=\"right\"><input type=\"submit\" name=\"Liquidar\" value=\"LIQUIDAR\" class=\"input-submit\"></td></tr>";
      $this->salida .= "      </table><BR>";
    }
    if($_SESSION['LIQUIDACION_QX']['CargueIyM']=='1'){                          
      $Insumos=$this->ValidarMedicamentosCuentaPaciente($NoLiquidacion,$cuenta);                    
      if(!empty($Insumos)){
        $this->salida .= "    <table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
        $actionInv=ModuloGetURL('app','DatosLiquidacionQX','user','CargaInsumosMedicamentosCuenta',array("liquidacionId"=>$NoLiquidacion,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso));
        $this->salida .= "   <td><a title=\"Cargar Insumos y Medicamentos\" href=\"$actionInv\"><b>CARGAR INSUMOS Y MEDICAMENTOS&nbsp;&nbsp;<img border = 0 src=\"".GetThemePath()."/images/pparamedin.png\"></a></td>";
        $this->salida .= "   </table>";
      }
    }        
    $this->salida .= "      </form>";
    $action=ModuloGetURL('app','DatosLiquidacionQX','user','LlamaSolicitudIdPaciente');
        $this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
    $this->salida .= "    <table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
    $this->salida .= "    <tr><td align=\"center\"><input type=\"submit\" name=\"Volver\" value=\"VOLVER\" class=\"input-submit\"></td></tr>";
    $this->salida .= "    </table>";
    $this->salida .= "      </form>";
    $this->salida .= ThemeCerrarTabla();
        return true;
  }

  function FormaMostrarDatosLiquidacion($NoLiquidacion,$TipoDocumento,$Documento,$nombrePaciente,$cuenta,$ingreso,$valoresManual){
    $this->salida .= ThemeAbrirTabla('VALORES DE LOS CARGOS LIQUIDADOS');

        //$valoresManual indica que la liquidacion tuvo correcciones manualmente
        $action=ModuloGetURL('app','DatosLiquidacionQX','user','GuardarDatosRetornadosLiquidacion',array("NoLiquidacion"=>$NoLiquidacion,"TipoDocumento"=>$TipoDocumento,
    "Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso,"valoresManual"=>$valoresManual));
        $this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
    $this->Encabezado();
        $this->salida .= "  <table width=\"40%\" border=\"0\" align=\"center\">";
        $this->salida .= "  <tr><td align=\"center\">";
        $this->salida .=    $this->SetStyle("MensajeError");
        $this->salida .= "  </td></tr>";
    $this->salida .= "   </table>";
    $this->salida .= "  <table width=\"70%\" border=\"0\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">DATOS DEL PROCEDIMIENTO</legend>";
        $this->salida .= "    <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"98%\" align=\"center\">";
    $this->salida .= "    <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"25%\" class=\"label\">NUMERO LIQUIDACION</td>";
        $this->salida .= "      <td colspan=\"3\">".$NoLiquidacion."</td>";
        $this->salida .= "    </tr>";
    $this->salida .= "    <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"25%\" class=\"label\">PACIENTE</td>";
        $this->salida .= "      <td colspan=\"3\">".$TipoDocumento." ".$Documento." - ".$nombrePaciente."</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"25%\" class=\"label\">No. CUENTA</td>";
        $this->salida .= "      <td>".$cuenta."</td>";
        $this->salida .= "      <td width=\"25%\" class=\"label\">No. INGRESO</td>";
        $this->salida .= "      <td>".$ingreso."</td>";
        $this->salida .= "    </tr>";
    $datosCirugia=$this->TraeDatosCirugia($NoLiquidacion);
    $estado=$datosCirugia['estado'];
    $this->salida .= "    <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"25%\" class=\"label\">VIA ACCESO</td>";
        $this->salida .= "      <td>".$datosCirugia['via']."</td>";
        $this->salida .= "      <td width=\"25%\" class=\"label\">AMBITO</td>";
        $this->salida .= "      <td>".$datosCirugia['ambito']."</td>";
        $this->salida .= "    </tr>";
    $this->salida .= "    <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"25%\" class=\"label\">FINALIDAD</td>";
        $this->salida .= "      <td>".$datosCirugia['finalidad']."</td>";
        $this->salida .= "      <td width=\"25%\" class=\"label\">TIPO</td>";
        $this->salida .= "      <td>".$datosCirugia['tipo']."</td>";
        $this->salida .= "    </tr>";
    $this->salida .= "    <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"25%\" class=\"label\">FECHA</td>";
    (list($fecha,$hora)=explode(' ',$datosCirugia['fecha_cirugia']));
    (list($ano,$mes,$dia)=explode('-',$fecha));
    (list($hora,$minutos)=explode(':',$hora));
        $this->salida .= "      <td>".ucwords(strftime("%b %d de %Y %H:%M",mktime($hora,$minutos,0,$mes,$dia,$ano)))."</td>";
        $this->salida .= "      <td width=\"25%\" class=\"label\">DURACION (HH:mm)</td>";
        $this->salida .= "      <td>".$datosCirugia['duracion_cirugia']."</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    </table>";
        $this->salida .= "   </fieldset>";
        $this->salida .= "   </td></tr>";
        $this->salida .= "   </table><br><BR>";				
    if($_SESSION['ARREGLO_LIQUIDACIONQX_CARGOS']){
      $this->salida .= "    <table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
      $this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"4\">CARGOS DEL ACTO QUIRURGICO No. ".$NoLiquidacion."</td></tr>";
      $this->salida .= "    <tr class=\"modulo_list_oscuro\">";
      $this->salida .= "    <td width=\"10%\" class=\"label\">ANESTESIOLOGO</td>";
      $nombreTercero=$this->NombreTercero($_SESSION['ARREGLO_LIQUIDACIONQX_CARGOS'][1][1]['liquidacion']['DA']['tipo_id_tercero'],$_SESSION['ARREGLO_LIQUIDACIONQX_CARGOS'][1][1]['liquidacion']['DA']['tercero_id']);
      $this->salida .= "    <td width=\"40%\">".$nombreTercero['nombre_tercero']."</td>";
      $this->salida .= "    <td width=\"10%\" class=\"label\">AYUDANTE</td>";
      $nombreTercero=$this->NombreTercero($_SESSION['ARREGLO_LIQUIDACIONQX_CARGOS'][1][1]['liquidacion']['DY']['tipo_id_tercero'],$_SESSION['ARREGLO_LIQUIDACIONQX_CARGOS'][1][1]['liquidacion']['DY']['tercero_id']);
      $this->salida .= "    <td width=\"40%\">".$nombreTercero['nombre_tercero']."</td>";
      $this->salida .= "    </tr>";
      foreach($_SESSION['ARREGLO_LIQUIDACIONQX_CARGOS'] as $indiceCirujano=>$Vector){
        $this->salida .= "        <tr class=\"modulo_table_title\">";
        $this->salida .= "         <td width=\"10%\">CIRUJANO ".$indiceCirujano."</td>";
        $nombreTercero=$this->NombreTercero($Vector[1]['tipo_id_cirujano'],$Vector[1]['cirujano_id']);
        $this->salida .= "         <td colspan=\"3\">".$nombreTercero['nombre_tercero']."</td>";
        $this->salida .= "       </tr>";
        foreach($Vector as $indiceProcedimiento=>$DatosQX){
          $this->salida .= "    <tr class=\"modulo_list_oscuro\">";
          $this->salida .= "      <td colspan=\"4\">";
          $this->salida .= "       <table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
          $descripciones=$this->DescripcionCargosCups($DatosQX['cargo_cups']);
          $this->salida .= "       <tr class=\"modulo_list_claro\">";
          $this->salida .= "        <td  width=\"10%\" class=\"label\">CARGO CUPS</td>";
          $this->salida .= "        <td colspan=\"4\">".$DatosQX['cargo_cups']." - ".$descripciones['descripcion']."</td>";
          $this->salida .= "       </tr>";
                    if($DatosQX['uvrs']){
                        $this->salida .= "       <tr class=\"modulo_list_claro\">";
                        $this->salida .= "        <td  width=\"10%\" class=\"label\">UVRS/G.QX</td>";
                        $this->salida .= "        <td colspan=\"4\">".$DatosQX['uvrs']."</td>";
                        $this->salida .= "       </tr>";
                    }
                    elseif($DatosQX['grupo_qx'])
                    {
                        $this->salida .= "       <tr class=\"modulo_list_claro\">";
                        $this->salida .= "        <td  width=\"10%\" class=\"label\">Grupo QX</td>";
                        $this->salida .= "        <td colspan=\"4\">".$DatosQX['grupo_qx']."</td>";
                        $this->salida .= "       </tr>";
                    }
          $descripciones=$this->DescripcionCargosTarifario($DatosQX['tarifario_id']);
          $this->salida .= "       <tr class=\"modulo_list_claro\">";
          $this->salida .= "        <td  width=\"10%\" class=\"label\">EQUIVALENCIA</td>";
          $this->salida .= "        <td colspan=\"4\">".$descripciones['tarifario']." ".$DatosQX['cargo']." - ".$DatosQX['descripcion']."</td>";
          $this->salida .= "       </tr>";
          $this->salida .= "          <tr class=\"modulo_table_list_title\">";
          $this->salida .= "          <td width=\"10%\">".$indiceProcedimiento."</td>";
          $this->salida .= "          <td width=\"20%\">CARGO</td>";
          $this->salida .= "          <td width=\"10%\">%</td>";
          $this->salida .= "          <td width=\"30%\">VALOR CUBIERTO</td>";
          $this->salida .= "          <td>VALOR NO CUBIERTO</td>";
          $this->salida .= "          </tr>";
          foreach($DatosQX['liquidacion'] as $derecho=>$DatosDerecho){
						if($DatosDerecho['facturado']==2){
							$this->salida .= "        <tr class=\"hc_table_submodulo_list_title\">";
						}else{
							$this->salida .= "        <tr class=\"modulo_list_claro\">";
						}
            
            $this->salida .= "        <td class=\"label\" align=\"left\">$derecho</td>";
            $descripciones=$this->DescripcionCargosTarifario($DatosDerecho['tarifario_id']);
            $this->salida .= "        <td align=\"left\">".$descripciones['tarifario']." - ".$DatosDerecho['cargo']."</td>";
                        if($valoresManual==1){
                            $this->salida .= "        <td align=\"center\"><input align=\"right\" type=\"text\" class=\"input-text\" size=\"8\" name=\"Porcentajes[$indiceCirujano][$indiceProcedimiento][liquidacion][$derecho]\" value=\"".$DatosDerecho['PORCENTAJE']."\"></td>";
                        }else{
                $this->salida .= "        <td align=\"right\">".$DatosDerecho['PORCENTAJE']."</td>";
                        }
                        if($valoresManual==1){
                            $this->salida .= "        <td align=\"center\"><input align=\"right\" type=\"text\" class=\"input-text\" size=\"8\" name=\"valoresCubiertos[$indiceCirujano][$indiceProcedimiento][liquidacion][$derecho]\" value=\"".FormatoValor($DatosDerecho['valor_cubierto'])."\"></td>";
                        }else{
                $this->salida .= "        <td align=\"right\">".FormatoValor($DatosDerecho['valor_cubierto'])."</td>";
                        }
                        if($valoresManual==1){
                $this->salida .= "        <td align=\"center\"><input align=\"right\" type=\"text\" class=\"input-text\" size=\"8\" name=\"valoresNoCubiertos[$indiceCirujano][$indiceProcedimiento][liquidacion][$derecho]\" value=\"".FormatoValor($DatosDerecho['valor_no_cubierto'])."\"></td>";
                        }else{
                            $this->salida .= "        <td align=\"right\">".FormatoValor($DatosDerecho['valor_no_cubierto'])."</td>";
                        }
            $this->salida .= "        </tr>";
          }
          $this->salida .= "       </table>";
          $this->salida .= "      </td>";
          $this->salida .= "    </tr>";
        }
      }
      $this->salida .= "    </table>";
    }
    
    if($DatosQXEquipos=$_SESSION['ARREGLO_LIQUIDACIONQX_EQUIPOS']){
      $this->salida .= "    <BR><table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
      $this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"4\">CARGOS DE EQUIPOS DEL ACTO QUIRURGICO No. ".$NoLiquidacion."</td></tr>";
      for($i=0;$i<sizeof($DatosQXEquipos);$i++){
        $this->salida .= "    <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "      <td colspan=\"4\">";
        $this->salida .= "       <table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= "       <tr class=\"modulo_list_claro\">";
        $this->salida .= "        <td  width=\"10%\" class=\"label\">EQUIPO</td>";
        $this->salida .= "        <td colspan=\"4\">".$DatosQXEquipos[$i]['descripcion_equipo']."&nbsp&nbsp&nbsp;<label class=\"label\">DURACION:&nbsp&nbsp&nbsp;</label>".$DatosQXEquipos[$i]['duracion']."</td>";
        $this->salida .= "       </tr>";
        $descripciones=$this->DescripcionCargosTarifario($DatosQXEquipos[$i]['tarifario_id']);
        $this->salida .= "       <tr class=\"modulo_list_claro\">";
        $this->salida .= "        <td  width=\"10%\" class=\"label\">EQUIVALENCIA</td>";
        $this->salida .= "        <td colspan=\"4\">".$descripciones['tarifario']." ".$DatosQXEquipos[$i]['cargo']." - ".$DatosQXEquipos[$i]['descripcion']."</td>";
        $this->salida .= "       </tr>";
        $this->salida .= "          <tr class=\"modulo_table_list_title\">";
        $this->salida .= "          <td width=\"10%\">TIPO EQUIPO</td>";
        $this->salida .= "          <td width=\"10%\">CANTIDAD</td>";
        $this->salida .= "          <td width=\"30%\">VALOR CUBIERTO</td>";
        $this->salida .= "          <td width=\"30%\">VALOR NO CUBIERTO</td>";
        $this->salida .= "          <td width=\"10%\">FACTURADO</td>";
        $this->salida .= "          </tr>";
        $this->salida .= "        <tr class=\"modulo_list_claro\">";
        if($DatosQXEquipos[$i]['tipo_equipo']=='fijo'){
          $this->salida .= "        <td align=\"center\">FIJO</td>";
        }else{
          $this->salida .= "        <td align=\"center\">MOVIL</td>";
        }
        $this->salida .= "        <td>".$DatosQXEquipos[$i]['cantidad']."</td>";
        if($valoresManual==1){
          $this->salida .= "        <td align=\"center\"><input align=\"right\" type=\"text\" class=\"input-text\" size=\"8\" name=\"valoresCubiertosEquipos[$i]\" value=\"".FormatoValor($DatosQXEquipos[$i]['valor_cubierto'])."\"></td>";
        }else{
          $this->salida .= "        <td align=\"right\">".FormatoValor($DatosQXEquipos[$i]['valor_cubierto'])."</td>";
        }
        if($valoresManual==1){
          $this->salida .= "        <td align=\"center\"><input align=\"right\" type=\"text\" class=\"input-text\" size=\"8\" name=\"valoresNoCubiertosEquipos[$i]\" value=\"".FormatoValor($DatosQXEquipos[$i]['valor_no_cubierto'])."\"></td>";
        }else{
          $this->salida .= "        <td align=\"right\">".FormatoValor($DatosQXEquipos[$i]['valor_no_cubierto'])."</td>";
        }

        if($DatosQXEquipos[$i]['facturado']=='1'){
          $this->salida .= "        <td align=\"center\">SI</td>";
        }else{
          $this->salida .= "        <td align=\"center\">NO</td>";
        }
        $this->salida .= "      </table>";
        $this->salida .= "      </td>";
        $this->salida .= "    </tr>";
      }
      $this->salida .= "    </table>";
    }
        if($estado==0){
            if($valoresManual==1){
                $this->salida .= "    <table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
                $actionManual=ModuloGetURL('app','DatosLiquidacionQX','user','LlamaFormaMostrarDatosLiquidacion',array("NoLiquidacion"=>$NoLiquidacion,"TipoDocumento"=>$TipoDocumento,
                "Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso));
                $this->salida .= "    <tr><td align=\"right\"><a href=\"$actionManual\" class=\"label\">VISTA NORMAL</a></td></tr>";
                $this->salida .= "    </table>";
            }else{
                $this->salida .= "    <table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
                $actionManual=ModuloGetURL('app','DatosLiquidacionQX','user','LlamaFormaMostrarDatosLiquidacion',array("NoLiquidacion"=>$NoLiquidacion,"TipoDocumento"=>$TipoDocumento,
                "Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso,"valoresManual"=>1));
                $this->salida .= "    <tr><td align=\"right\"><a href=\"$actionManual\" class=\"label\">CORREGIR VALORES MANUALMENTE</a></td></tr>";
                $this->salida .= "    </table>";
            }
        }
    $this->salida .= "    <table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
    $this->salida .= "    <tr>";
    if($estado==0){
    $this->salida .= "    <td align=\"right\" width=\"50%\"><input type=\"submit\" name=\"Guardar\" value=\"GUARDAR LIQUIDACION\" class=\"input-submit\"></td>";
    }else{
    $this->salida .= "    <td align=\"right\" width=\"50%\"><input type=\"submit\" name=\"GuardarReliquidar\" value=\"RELIQUIDAR\" class=\"input-submit\"></td>";
    }
    $this->salida .= "      </form>";
    if($estado==0){
    $action=ModuloGetURL('app','DatosLiquidacionQX','user','LlamaCargarCargosCirugiaTemporal',array("NoLiquidacion"=>$NoLiquidacion,"TipoDocumento"=>$TipoDocumento,
    "Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso));
    $this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
    $this->salida .= "    <td align=\"left\" width=\"50%\"><input type=\"submit\" name=\"Volver\" value=\"VOLVER\" class=\"input-submit\"></td>";
    $this->salida .= "      </form>";
    }else{
    $action=ModuloGetURL('app','DatosLiquidacionQX','user','LlamaSolicitudIdPaciente');
    $this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
    $this->salida .= "    <td align=\"left\" width=\"50%\"><input type=\"submit\" name=\"Volver\" value=\"VOLVER\" class=\"input-submit\"></td>";
    $this->salida .= "      </form>";
    }
    $this->salida .= "    </tr>";
    $this->salida .= "    </table>";
    if($estado==1 || $estado==2){
    $this->salida .= "    <table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
    if($estado==1){
      $action=ModuloGetURL('app','DatosLiquidacionQX','user','CargarALaCuentaPaciente',array("NoLiquidacion"=>$NoLiquidacion,"TipoDocumento"=>$TipoDocumento,
      "Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso));
      $this->salida .= "    <tr><td align=\"left\"><a href=\"$action\"><img border = 0 src=\"".GetThemePath()."/images/cargar.png\"><b>&nbsp&nbsp;CARGAR A LA CUENTA</b></a></td></tr>";      
    }elseif($estado==2){
      $this->salida .= "     <tr><td align=\"left\"><img border = 0 src=\"".GetThemePath()."/images/pcopagos.png\"><b>&nbsp&nbsp;CARGADO A LA CUENTA</b></td></tr>";      
    }
    $rep= new GetReports();
    $mostrar=$rep->GetJavaReport('app','DatosLiquidacionQX','reporteLiquidacionQX_html',array("NoLiquidacion"=>$NoLiquidacion),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
    $nombre_funcion=$rep->GetJavaFunction();
    $this->salida .=$mostrar;
    $this->salida .= "     <tr><td align=\"left\"><a class=\"Menu\" href=\"javascript:$nombre_funcion\"><img border = 0 src=\"".GetThemePath()."/images/imprimir.png\"><b>&nbsp&nbsp;IMPRIMIR</b></a></td></tr>";      
    $this->salida .= "    </table>";
    }

   if($_SESSION['LIQUIDACION_QX']['CargueIyM']=='1'){                          
      $Insumos=$this->ValidarMedicamentosCuentaPaciente($NoLiquidacion,$cuenta);                    
      if($estado=='2' || !empty($Insumos)){
        $this->salida .= "    <table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
        $actionInv=ModuloGetURL('app','DatosLiquidacionQX','user','CargaInsumosMedicamentosCuenta',array("liquidacionId"=>$NoLiquidacion,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso));
        $this->salida .= "   <td><a title=\"Cargar Insumos y Medicamentos\" href=\"$actionInv\"><b>CARGAR INSUMOS Y MEDICAMENTOS&nbsp;&nbsp;<img border = 0 src=\"".GetThemePath()."/images/pparamedin.png\"></a></td>";
        $this->salida .= "   </table>";
      }
    }

    //consulta de los medicamentos en la cuenta del paciente
    $cargos=$this->CargosMedicamentosCuentaPaciente($NoLiquidacion);
    $cargosDev=$this->CargosMedicamentosCuentaPacienteDevol($NoLiquidacion);
    if(is_array($cargos) || is_array($cargosDev)){
      $this->salida .= "    <table width=\"80%\" border=\"0\" align=\"center\" class=\"modulo_table_list_title\">";
      $this->salida .= "    <tr class=\"modulo_table_title\"><td colspan=\"6\">INSUMOS Y MEDICAMENTOS CARGADOS EN LA CUENTA</td></tr>";
      $this->salida .= "    <tr class=\"modulo_table_title\">";
      $this->salida .= "    <td width=\"15%\">CODIGO</td>";
      $this->salida .= "    <td width=\"15%\">CANTIDAD</td>";
      $this->salida .= "    <td>PRODUCTO</td>";
      $this->salida .= "    <td width=\"15%\">VALOR NO CUBIERTO</td>";
      $this->salida .= "    <td width=\"15%\">VALOR CUBIERTO</td>";
      $this->salida .= "    <td width=\"15%\">FACTURADO</td>";
      $this->salida .= "    </tr>";
      if(is_array($cargos)){
        $this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"6\">DESPACHOS</td></tr>";
        for($i=0;$i<sizeof($cargos);$i++){
		  
			if(empty($cargos[$i]['forma_farmacologica']))
			{
				$cargos[$i]['forma_farmacologica'] = '';
			}
			if(empty($cargos[$i]['concentracion_forma_farmacologica']))
			{
				$cargos[$i]['concentracion_forma_farmacologica'] = '';
			}
		  
			if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
			$this->salida .= "    <tr class=\"$estilo\">";
			$this->salida .= "    <td align=\"left\">".$cargos[$i]['codigo_producto']."</td>";
			$divisor=(int)($cargos[$i]['cantidad']);
			if($cargos[$i]['cantidad']%$divisor){
				$this->salida .= "    <td align=\"left\">".$cargos[$i]['cantidad']."</td>";
			}
			else{
				$this->salida .= "    <td align=\"left\">".$divisor."</td>";
			}
			$this->salida .= "    <td align=\"left\">".$cargos[$i]['descripcion']." ".$cargos[$i]['forma_farmacologica']." ".$cargos[$i]['concentracion_forma_farmacologica']."</td>";
			$this->salida .= "    <td align=\"left\">".$cargos[$i]['valor_nocubierto']."</td>";
			$this->salida .= "    <td align=\"left\">".$cargos[$i]['valor_cubierto']."</td>";
			if($cargos[$i]['facturado']==1){
				$this->salida .= "    <td align=\"center\"><img title=\"Cargo Facturado\" border = 0 src=\"".GetThemePath()."/images/checksi.png\"></td>";
			}
			else{
				$this->salida .= "    <td align=\"center\"><img title=\"Cargo No Facturado\" border = 0 src=\"".GetThemePath()."/images/checkno.png\"></td>";
			}
			$y++;
        }
      }
      if(is_array($cargosDev)){
        $this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"6\">DEVOLUCIONES</td></tr>";
        for($i=0;$i<sizeof($cargosDev);$i++){
		  if(empty($cargosDev[$i]['forma_farmacologica']))
		  {
				$cargosDev[$i]['forma_farmacologica'] = '';
		  }
		  if(empty($cargosDev[$i]['concentracion_forma_farmacologica']))
		  {
				$cargosDev[$i]['concentracion_forma_farmacologica'] = '';
		  }
          if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
          $this->salida .= "    <tr class=\"$estilo\">";
          $this->salida .= "    <td align=\"left\">".$cargosDev[$i]['codigo_producto']."</td>";
          $divisor=(int)($cargosDev[$i]['cantidad']);
          if($cargosDev[$i]['cantidad']%$divisor){
            $this->salida .= "    <td align=\"left\">".$cargosDev[$i]['cantidad']."</td>";
          }else{
          $this->salida .= "    <td align=\"left\">".$divisor."</td>";
          }
          $this->salida .= "    <td align=\"left\">".$cargosDev[$i]['descripcion']." ".$cargosDev[$i]['forma_farmacologica']." ".$cargosDev[$i]['concentracion_forma_farmacologica']."</td>";
          $this->salida .= "    <td align=\"left\">".$cargosDev[$i]['valor_nocubierto']."</td>";
          $this->salida .= "    <td align=\"left\">".$cargosDev[$i]['valor_cubierto']."</td>";
          if($cargosDev[$i]['facturado']==1){
            $this->salida .= "    <td align=\"center\"><img title=\"Cargo Facturado\" border = 0 src=\"".GetThemePath()."/images/checksi.png\"></td>";
          }else{
             $this->salida .= "    <td align=\"center\"><img title=\"Cargo No Facturado\" border = 0 src=\"".GetThemePath()."/images/checkno.png\"></td>";
          }
          $y++;
        }
      }
      $this->salida .= "    </table>";
    }
    //fin

    $this->salida .= ThemeCerrarTabla();
        return true;
  }

  function Encabezado1(){
    $this->salida .= "    <table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" align=\"center\"><b>EMPRESA</b></td>";
        $this->salida .= "      <td class=\"modulo_table_list_title\" align=\"center\"><b>DEPARTAMENTO</b></td>";
    $this->salida .= "      <td class=\"modulo_table_list_title\" align=\"center\"><b>BODEGA</b></td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td class=\"modulo_list_claro\" align=\"center\"><b>".$_SESSION['LIQUIDACION_QX']['NombreEmp']."</b></td>";
    $this->salida .= "      <td class=\"modulo_list_claro\" align=\"center\"><b>".$_SESSION['LIQUIDACION_QX']['NombreDpto']."</b></td>";
    $this->salida .= "      <td class=\"modulo_list_claro\" align=\"center\"><b>".$_SESSION['LIQUIDACION_QX']['Bodega']." - ".$_SESSION['LIQUIDACION_QX']['NombreBodega']."</b></td>";
    $this->salida .= "      </table><BR>";
        return true;
    }

  function frmCargaInsumosMedicamentosCuenta($NoLiquidacion,$TipoDocumento,$Documento,$nombrePaciente,$cuenta,$ingreso){
    $this->salida .= ThemeAbrirTabla('CARGO DE INSUMOS Y MEDICAMENTOS A LA CUENTA DEL PACIENTE');
        $action=ModuloGetURL('app','DatosLiquidacionQX','user','CargarIyMCuentaPaciente',array("NoLiquidacion"=>$NoLiquidacion,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso));
    $this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
    $this->Encabezado1();
        $this->salida .= "  <table width=\"40%\" border=\"0\" align=\"center\">";
        $this->salida .= "  <tr><td align=\"center\">";
        $this->salida .=    $this->SetStyle("MensajeError");
        $this->salida .= "  </td></tr>";
    $this->salida .= "   </table>";
    $this->salida .= "  <table width=\"70%\" border=\"0\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">DATOS DEL PROCEDIMIENTO</legend>";
        $this->salida .= "    <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"98%\" align=\"center\">";
    $this->salida .= "    <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"25%\" class=\"label\">NUMERO LIQUIDACION</td>";
        $this->salida .= "      <td colspan=\"3\">".$NoLiquidacion."</td>";
        $this->salida .= "    </tr>";
    $this->salida .= "    <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"25%\" class=\"label\">PACIENTE</td>";
        $this->salida .= "      <td colspan=\"3\">".$TipoDocumento." ".$Documento." - ".$nombrePaciente."</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"25%\" class=\"label\">No. CUENTA</td>";
        $this->salida .= "      <td>".$cuenta."</td>";
        $this->salida .= "      <td width=\"25%\" class=\"label\">No. INGRESO</td>";
        $this->salida .= "      <td>".$ingreso."</td>";
        $this->salida .= "    </tr>";
    $datosCirugia=$this->TraeDatosCirugia($NoLiquidacion);
    if($datosCirugia){
      $estado=$datosCirugia['estado'];
      $this->salida .= "    <tr class=\"modulo_list_claro\">";
      $this->salida .= "        <td width=\"25%\" class=\"label\">VIA ACCESO</td>";
      $this->salida .= "        <td>".$datosCirugia['via']."</td>";
      $this->salida .= "        <td width=\"25%\" class=\"label\">AMBITO</td>";
      $this->salida .= "        <td>".$datosCirugia['ambito']."</td>";
      $this->salida .= "    </tr>";
      $this->salida .= "    <tr class=\"modulo_list_claro\">";
      $this->salida .= "        <td width=\"25%\" class=\"label\">FINALIDAD</td>";
      $this->salida .= "        <td>".$datosCirugia['finalidad']."</td>";
      $this->salida .= "        <td width=\"25%\" class=\"label\">TIPO</td>";
      $this->salida .= "        <td>".$datosCirugia['tipo']."</td>";
      $this->salida .= "    </tr>";
      $this->salida .= "    <tr class=\"modulo_list_claro\">";
      $this->salida .= "        <td width=\"25%\" class=\"label\">FECHA</td>";
      (list($fecha,$hora)=explode(' ',$datosCirugia['fecha_cirugia']));
      (list($ano,$mes,$dia)=explode('-',$fecha));
      (list($hora,$minutos)=explode(':',$hora));
      $this->salida .= "        <td>".ucwords(strftime("%b %d de %Y %H:%M",mktime($hora,$minutos,0,$mes,$dia,$ano)))."</td>";
      $this->salida .= "        <td width=\"25%\" class=\"label\">DURACION (HH:mm)</td>";
      $this->salida .= "        <td>".$datosCirugia['duracion_cirugia']."</td>";
      $this->salida .= "    </tr>";
    }
        $this->salida .= "    </table>";
        $this->salida .= "   </fieldset>";
        $this->salida .= "   </td></tr>";
        $this->salida .= "   </table><br><BR>";
    $HojaInsumos=$this->ProductosHojaInsumos($NoLiquidacion,$_SESSION['LIQUIDACION_QX']['PROGRAMACION_INSUMOS']);
    if($HojaInsumos){
      $this->salida .= "    <table width=\"95%\" border=\"0\" align=\"center\">";
      $this->salida .= "    <tr class=\"modulo_table_title\"><td colspan=\"7\">HOJA DE CONSUMO DE INSUMOS Y MEDICAMENTOS</td></tr>";
      $this->salida .= "    <tr class=\"modulo_table_title\">";
            $this->salida .= "    <td width=\"15%\">CODIGO</td>";
            $this->salida .= "    <td>DESCRIPCION</td>";
			$this->salida .= "    <td width=\"15%\">LOTE</td>";
	  $this->salida .= "    <td width=\"15%\">FEC VENCIMIENTO</td>";
      $this->salida .= "    <td width=\"15%\">EXISTENCIAS</td>";
      $this->salida .= "    <td width=\"15%\">CANTIDAD</BR>DESPACHADA</td>";
            $this->salida .= "    <td width=\"15%\">CANTIDAD A</BR>CARGAR</td>";
            $this->salida .= "    </tr>";
            for($i=0;$i<sizeof($HojaInsumos);$i++){
        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
        $this->salida .= "    <tr class=\"$estilo\">";
        $this->salida .= "    <td align=\"left\">".$HojaInsumos[$i]['codigo_producto']."</td>";
        $this->salida .= "    <td align=\"left\">".$HojaInsumos[$i]['descripcion']."</td>";
		$this->salida .= "    <td align=\"left\">".$HojaInsumos[$i]['lote']."</td>";
		$this->salida .= "    <td align=\"left\">".$HojaInsumos[$i]['fecha_vencimiento']."</td>";
        $this->salida .= "    <td align=\"left\">".$HojaInsumos[$i]['existencia']."</td>";
        $divisor=(int)($HojaInsumos[$i]['total']);
        if($HojaInsumos[$i]['total']%$divisor){
          $tot=$HojaInsumos[$i]['total'];
        }else{
          $tot=(int)($HojaInsumos[$i]['total']);
        }
        $this->salida .= "    <td align=\"right\">".$tot."</td>";
                $this->salida .= "    <input type=\"hidden\" name=\"CantidadesSol[".$HojaInsumos[$i]['codigo_producto']."][".$HojaInsumos[$i]['lote']."][".$HojaInsumos[$i]['fecha_vencimiento']."]\" value=\"".$tot."\">";
                $this->salida .= "    <td align=\"center\"><input type=\"text\" class=\"input-text\" name=\"Cantidades[".$HojaInsumos[$i]['codigo_producto']."][".$HojaInsumos[$i]['lote']."][".$HojaInsumos[$i]['fecha_vencimiento']."]\" value=\"".$tot."\" size=\"3\"></td>";
        $this->salida .= "    </tr>";
        $y++;
      }
      $estado=$this->ConfirmarLiquidacionCuenta($NoLiquidacion);
      if($this->VerificarCuentaActiva($TipoDocumento,$Documento)==1){
        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
        $this->salida .= "    <tr class=\"$estilo\">";
        $this->salida .= "   <td colspan=\"7\" align=\"right\" class=\"link\"><input type=\"submit\" class=\"input-submit\" name=\"CargarCuenta\" value=\"CARGAR A LA CUENTA\"></td>";
        $this->salida .= "    </tr>";
      }
      $this->salida .= "     </table><BR>";
    }
    
    $GasesAnestesicos=$this->BuscarGasesAnestesicosRegistrados($NoLiquidacion);
    if($GasesAnestesicos){
      $this->salida .= "    <table width=\"95%\" border=\"0\" align=\"center\">";
      $this->salida .= "    <tr class=\"modulo_table_title\"><td colspan=\"8\">GASES ANESTESICOS REGISTRADOS</td></tr>";
      $this->salida .= "    <tr class=\"modulo_table_title\">";
      $this->salida .= "    <td width=\"20%\">CODIGO PRODUCTO</td>";
      $this->salida .= "    <td width=\"20%\">TIPO GAS</td>";
      $this->salida .= "    <td width=\"20%\">METODO SUMINISTRO</td>";
      $this->salida .= "    <td width=\"10%\">FRECUENCIA SUMINISTRO</td>";
      $this->salida .= "    <td width=\"10%\">MINUTOS</td>";
	  $this->salida .= "    <td width=\"10%\">FECHA VENCIMIENTO</td>";
	  $this->salida .= "    <td width=\"10%\">LOTE</td>";      
      $this->salida .= "    <td width=\"5%\">&nbsp;</td>";      
      $this->salida .= "    </tr>";
      for($i=0;$i<sizeof($GasesAnestesicos);$i++){
        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
        $this->salida .= "    <tr class=\"$estilo\">";
        $this->salida .= "    <td align=\"left\">".$GasesAnestesicos[$i]['codigo_producto']."</td>";
        $this->salida .= "    <td align=\"left\">".$GasesAnestesicos[$i]['nom_tipo_gas_id']."</td>";
        $this->salida .= "    <td align=\"left\">".$GasesAnestesicos[$i]['nom_tipo_suministro_id']."</td>";
        $this->salida .= "    <td align=\"left\">".$GasesAnestesicos[$i]['frecuencia_id']." ".$GasesAnestesicos[$i]['unidad']."</td>";
        $this->salida .= "    <td align=\"left\">".$GasesAnestesicos[$i]['tiempo_suministro']."</td>";
		$this->salida .= "    <td align=\"left\">".$GasesAnestesicos[$i]['fecha_vencimiento']."</td>";
		$this->salida .= "    <td align=\"left\">".$GasesAnestesicos[$i]['lote']."</td>";
        $info=$GasesAnestesicos[$i]['codigo_producto'];
        $info.='||//'.$GasesAnestesicos[$i]['tipo_gas_id'];
        $info.='||//'.$GasesAnestesicos[$i]['tipo_suministro_id'];
        $info.='||//'.$GasesAnestesicos[$i]['frecuencia_id'];
        $info.='||//'.$GasesAnestesicos[$i]['tiempo_suministro'];
        $info.='||//'.$GasesAnestesicos[$i]['factor_conversion'];
		$info.='||//'.$GasesAnestesicos[$i]['fecha_vencimiento'];
		$info.='||//'.$GasesAnestesicos[$i]['lote'];
		
        $this->salida .= "    <td align=\"center\"><input type=\"checkbox\" name=\"GasesAnestesicos[".$GasesAnestesicos[$i]['suministro_gas_id']."-".$GasesAnestesicos[$i]['fecha_vencimiento']."-".$GasesAnestesicos[$i]['lote']."]\" value=\"$info\"></td>";        
        $this->salida .= "    </tr>";
        $y++;
      }
      $estado=$this->ConfirmarLiquidacionCuenta($NoLiquidacion);
      if($this->VerificarCuentaActiva($TipoDocumento,$Documento)==1){
        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
        $this->salida .= "    <tr class=\"$estilo\">";
        $this->salida .= "   <td colspan=\"8\" align=\"right\" class=\"link\"><input type=\"submit\" class=\"input-submit\" name=\"CargarCuentaGases\" value=\"CARGAR A LA CUENTA\"></td>";
        $this->salida .= "    </tr>";
      }
      $this->salida .= "     </table><BR>";
    }

        if($NoLiquidacion){
            $this->salida .= "    <table width=\"95%\" border=\"0\" align=\"center\" class=\"modulo_table_list_title\">";
            $this->salida .= "    <tr class=\"modulo_table_title\"><td colspan=\"6\">INSUMOS Y MEDICAMENTOS QUIRURGICOS CARGADOS EN LA CUENTA</td></tr>";
            $this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"6\">DESPACHOS</td></tr>";
            $cargos=$this->CargosMedicamentosCuentaPaciente($NoLiquidacion);
            if($cargos){
                $this->salida .= "    <tr class=\"modulo_table_title\">";
                $this->salida .= "    <td width=\"15%\">CODIGO</td>";
                $this->salida .= "    <td width=\"15%\">CANTIDAD</td>";
                $this->salida .= "    <td>PRODUCTO</td>";
                $this->salida .= "          <td width=\"15%\">VALOR NO CUBIERTO</td>";
                $this->salida .= "          <td width=\"15%\">VALOR CUBIERTO</td>";
                $this->salida .= "          <td width=\"15%\">FACTURADO</td>";
                $this->salida .= "    </tr>";
                for($i=0;$i<sizeof($cargos);$i++){
				
					if(empty($cargos[$i]['forma_farmacologica']))
					{
						$cargos[$i]['forma_farmacologica'] = '';
					}
					if(empty($cargos[$i]['concentracion_forma_farmacologica']))
					{
						$cargos[$i]['concentracion_forma_farmacologica'] = '';
					}
                    if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
                    $this->salida .= "    <tr class=\"$estilo\">";
                    $this->salida .= "    <td align=\"left\">".$cargos[$i]['codigo_producto']."</td>";
                    $divisor=(int)($cargos[$i]['cantidad']);
                    if($cargos[$i]['cantidad']%$divisor){
                        $this->salida .= "    <td align=\"left\">".$cargos[$i]['cantidad']."</td>";
                    }else{
                    $this->salida .= "    <td align=\"left\">".$divisor."</td>";
                    }
                    $this->salida .= "    <td align=\"left\">".$cargos[$i]['descripcion']." ".$cargos[$i]['forma_farmacologica']." ".$cargos[$i]['concentracion_forma_farmacologica']."</td>";
                    $this->salida .= "    <td align=\"left\">".$cargos[$i]['valor_nocubierto']."</td>";
                    $this->salida .= "    <td align=\"left\">".$cargos[$i]['valor_cubierto']."</td>";
                    if($cargos[$i]['facturado']==1){
                      $this->salida .= "    <td align=\"center\"><img title=\"Cargo Facturado\" border = 0 src=\"".GetThemePath()."/images/checksi.png\"></td>";
                    }else{
                      $this->salida .= "    <td align=\"center\"><img title=\"Cargo No Facturado\" border = 0 src=\"".GetThemePath()."/images/checkno.png\"></td>";
                    }
                    $y++;
                }
            }else{
                $this->salida .= "    <tr class=\"modulo_list_claro\">";
                $this->salida .= "    <td colspan=\"6\">NO SE HAN CARGADO DESPACHOS A LA CUENTA</td>";
                $this->salida .= "    </tr>";
            }
            $this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"6\">DEVOLUCIONES</td></tr>";
            $cargos=$this->CargosMedicamentosCuentaPacienteDevol($NoLiquidacion);
            if($cargos){
                $this->salida .= "    <tr class=\"modulo_table_title\">";
                $this->salida .= "    <td width=\"15%\">CODIGO</td>";
                $this->salida .= "    <td width=\"15%\">CANTIDAD</td>";
                $this->salida .= "    <td>PRODUCTO</td>";
                $this->salida .= "    <td width=\"15%\">VALOR NO CUBIERTO</td>";
                $this->salida .= "    <td width=\"15%\">VALOR CUBIERTO</td>";
                $this->salida .= "    <td width=\"15%\">FACTURADO</td>";
                $this->salida .= "    </tr>";
                for($i=0;$i<sizeof($cargos);$i++){
				
					if(empty($cargos[$i]['forma_farmacologica']))
					{
						$cargos[$i]['forma_farmacologica'] = '';
					}
					if(empty($cargos[$i]['concentracion_forma_farmacologica']))
					{
						$cargos[$i]['concentracion_forma_farmacologica'] = '';
					}
                    if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
                    $this->salida .= "    <tr class=\"$estilo\">";
                    $this->salida .= "    <td align=\"left\">".$cargos[$i]['codigo_producto']."</td>";
                    $divisor=(int)($cargos[$i]['cantidad']);
                    if($cargos[$i]['cantidad']%$divisor){
                        $this->salida .= "    <td align=\"left\">".$cargos[$i]['cantidad']."</td>";
                    }else{
                    $this->salida .= "    <td align=\"left\">".$divisor."</td>";
                    }
                    $this->salida .= "    <td align=\"left\">".$cargos[$i]['descripcion']." ".$cargos[$i]['forma_farmacologica']." ".$cargos[$i]['concentracion_forma_farmacologica']."</td>";
                    $this->salida .= "    <td align=\"left\">".$cargos[$i]['valor_nocubierto']."</td>";
                    $this->salida .= "    <td align=\"left\">".$cargos[$i]['valor_cubierto']."</td>";
                    if($cargos[$i]['facturado']==1){
                      $this->salida .= "    <td align=\"center\"><img title=\"Cargo Facturado\" border = 0 src=\"".GetThemePath()."/images/checksi.png\"></td>";
                    }else{
                      $this->salida .= "    <td align=\"center\"><img title=\"Cargo No Facturado\" border = 0 src=\"".GetThemePath()."/images/checkno.png\"></td>";
                    }
                    $y++;
                }
            }else{
                $this->salida .= "    <tr class=\"modulo_list_claro\">";
                $this->salida .= "    <td colspan=\"6\">NO SE HAN CARGADO DEVOLUCIONES A LA CUENTA</td>";
                $this->salida .= "    </tr>";
            }
            
            if($this->VerificarCuentaActiva($TipoDocumento,$Documento)==1){
								if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
                $this->salida .= "    <tr class=\"$estilo\">";
                $action=ModuloGetURL('app','DatosLiquidacionQX','user','LlamaReliquidarCargosIyMCuenta',array("NoLiquidacion"=>$NoLiquidacion,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso));
                $this->salida .= "     <td colspan=\"6\" align=\"left\"><a href=\"$action\"><img border = 0 src=\"".GetThemePath()."/images/producto_precio.png\"><b>&nbsp&nbsp;RELIQUIDAR INSUMOS Y MEDICAMENTOS</a></td>";
                $this->salida .= "    </tr>";
                $y++;
                if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
                $this->salida .= "    <tr class=\"$estilo\">";
                $action=ModuloGetURL('app','DatosLiquidacionQX','user','LlamaSeleccionarCargosCuenta',array("NoLiquidacion"=>$NoLiquidacion,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso));
                $this->salida .= "     <td colspan=\"6\" align=\"left\"><a href=\"$action\"><img border = 0 src=\"".GetThemePath()."/images/producto_precio.png\"><b>&nbsp&nbsp;ADICIONAR CARGOS A LA CUENTA</a></td>";
                $this->salida .= "    </tr>";
                $y++;
                if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
                $this->salida .= "    <tr class=\"$estilo\">";
                $action=ModuloGetURL('app','DatosLiquidacionQX','user','LlamaDevolucionCargosCuenta',array("NoLiquidacion"=>$NoLiquidacion,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso));
                $this->salida .= "     <td colspan=\"6\" align=\"left\"><a href=\"$action\"><img border = 0 src=\"".GetThemePath()."/images/producto_precio.png\"><b>&nbsp&nbsp;REALIZAR DEVOLUCION EN LA CUENTA</a></td>";
                $this->salida .= "    </tr>";
            }else{
                $this->salida .= "    <tr class=\"modulo_list_claro\">";
                $this->salida .= "     <td class=\"label_error\" colspan=\"6\" align=\"left\"><img border = 0 src=\"".GetThemePath()."/images/producto_precio.png\"><b>&nbsp&nbsp;NO EXISTE UN CUENTA ACTIVA</td>";
                $this->salida .= "    </tr>";
            }
            $this->salida .= "    </table>";
        }else{
            //insumos en la cuenta liquidados sin la liquidacion
            $this->salida .= "    <table width=\"95%\" border=\"0\" align=\"center\" class=\"modulo_table_list_title\">";
            $this->salida .= "    <tr class=\"modulo_table_title\"><td colspan=\"6\">INSUMOS Y MEDICAMENTOS CARGADOS EN LA CUENTA</td></tr>";
            $this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"6\">DESPACHOS</td></tr>";
            $cargos=$this->CargosMedicamentosCuentaPacienteSinLiquidacion($cuenta);
            if($cargos){
                $this->salida .= "    <tr class=\"modulo_table_title\">";
                $this->salida .= "    <td width=\"15%\">CODIGO</td>";
                $this->salida .= "    <td width=\"15%\">CANTIDAD</td>";
                $this->salida .= "    <td>PRODUCTO</td>";
                $this->salida .= "    <td width=\"15%\">VALOR NO CUBIERTO</td>";
                $this->salida .= "    <td width=\"15%\">VALOR CUBIERTO</td>";
                $this->salida .= "    <td width=\"15%\">FACTURADO</td>";
                $this->salida .= "    </tr>";
                for($i=0;$i<sizeof($cargos);$i++){
                    if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
                    $this->salida .= "    <tr class=\"$estilo\">";
                    $this->salida .= "    <td align=\"left\">".$cargos[$i]['codigo_producto']."</td>";
                    $divisor=(int)($cargos[$i]['cantidad']);
                    if($cargos[$i]['cantidad']%$divisor){
                        $this->salida .= "    <td align=\"left\">".$cargos[$i]['cantidad']."</td>";
                    }else{
                    $this->salida .= "    <td align=\"left\">".$divisor."</td>";
                    }
                    $this->salida .= "    <td align=\"left\">".$cargos[$i]['descripcion']."</td>";
                    $this->salida .= "    <td align=\"left\">".$cargos[$i]['valor_nocubierto']."</td>";
                    $this->salida .= "    <td align=\"left\">".$cargos[$i]['valor_cubierto']."</td>";
                    if($cargos[$i]['facturado']==1){
                      $this->salida .= "    <td align=\"center\"><img title=\"Cargo Facturado\" border = 0 src=\"".GetThemePath()."/images/checksi.png\"></td>";
                    }else{
                      $this->salida .= "    <td align=\"center\"><img title=\"Cargo No Facturado\" border = 0 src=\"".GetThemePath()."/images/checkno.png\"></td>";
                    }
                    $y++;
                }
            }else{
                $this->salida .= "    <tr class=\"modulo_list_claro\">";
                $this->salida .= "    <td colspan=\"6\">NO SE HAN CARGADO DESPACHOS A LA CUENTA</td>";
                $this->salida .= "    </tr>";
            }
            $this->salida .= "    </table>";
            //fin
        }
    $this->salida .= "    </form>";
    $action=ModuloGetURL('app','DatosLiquidacionQX','user','LlamaSolicitudIdPaciente');
    $this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
    $this->salida .= "    <BR><table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "    <tr><td align=\"center\"><input type=\"submit\" name=\"Volver\" value=\"VOLVER\" class=\"input-submit\"></td></td>";
    $this->salida .= "    </table>";
    $this->salida .= "    </form>";
    $this->salida .= ThemeCerrarTabla();
        return true;
  }	
	

  function SeleccionarCargosCuenta($NoLiquidacion,$TipoDocumento,$Documento,$nombrePaciente,$cuenta,$ingreso,$lote,$fecha_vencimiento){
    $this->salida .= ThemeAbrirTabla('SELECCION DE INSUMOS Y MEDICAMENTOS PARA CARGAR A LA CUENTA DEL PACIENTE');
    $action=ModuloGetURL('app','DatosLiquidacionQX','user','GuardarItemsCuentaPaciente',array("NoLiquidacion"=>$NoLiquidacion,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso));
    $this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
    $this->Encabezado1();
        $this->salida .= "  <table width=\"40%\" border=\"0\" align=\"center\">";
        $this->salida .= "  <tr><td align=\"center\">";
        $this->salida .=    $this->SetStyle("MensajeError");
        $this->salida .= "  </td></tr>";
    $this->salida .= "   </table>";
    $this->salida .= "  <table width=\"70%\" border=\"0\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">DATOS DEL PROCEDIMIENTO</legend>";
        $this->salida .= "    <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"98%\" align=\"center\">";
    $this->salida .= "    <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"25%\" class=\"label\">NUMERO LIQUIDACION</td>";
        $this->salida .= "      <td colspan=\"3\">".$NoLiquidacion."</td>";
        $this->salida .= "    </tr>";
    $this->salida .= "    <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"25%\" class=\"label\">PACIENTE</td>";
        $this->salida .= "      <td colspan=\"3\">".$TipoDocumento." ".$Documento." - ".$nombrePaciente."</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"25%\" class=\"label\">No. CUENTA</td>";
        $this->salida .= "      <td>".$cuenta."</td>";
        $this->salida .= "      <td width=\"25%\" class=\"label\">No. INGRESO</td>";
        $this->salida .= "      <td>".$ingreso."</td>";
        $this->salida .= "    </tr>";
    $datosCirugia=$this->TraeDatosCirugia($NoLiquidacion);
    if($datosCirugia){
      $estado=$datosCirugia['estado'];
      $this->salida .= "    <tr class=\"modulo_list_claro\">";
      $this->salida .= "        <td width=\"25%\" class=\"label\">VIA ACCESO</td>";
      $this->salida .= "        <td>".$datosCirugia['via']."</td>";
      $this->salida .= "        <td width=\"25%\" class=\"label\">AMBITO</td>";
      $this->salida .= "        <td>".$datosCirugia['ambito']."</td>";
      $this->salida .= "    </tr>";
      $this->salida .= "    <tr class=\"modulo_list_claro\">";
      $this->salida .= "        <td width=\"25%\" class=\"label\">FINALIDAD</td>";
      $this->salida .= "        <td>".$datosCirugia['finalidad']."</td>";
      $this->salida .= "        <td width=\"25%\" class=\"label\">TIPO</td>";
      $this->salida .= "        <td>".$datosCirugia['tipo']."</td>";
      $this->salida .= "    </tr>";
      $this->salida .= "    <tr class=\"modulo_list_claro\">";
      $this->salida .= "        <td width=\"25%\" class=\"label\">FECHA</td>";
      (list($fecha,$hora)=explode(' ',$datosCirugia['fecha_cirugia']));
      (list($ano,$mes,$dia)=explode('-',$fecha));
      (list($hora,$minutos)=explode(':',$hora));
      $this->salida .= "        <td>".ucwords(strftime("%b %d de %Y %H:%M",mktime($hora,$minutos,0,$mes,$dia,$ano)))."</td>";
      $this->salida .= "        <td width=\"25%\" class=\"label\">DURACION (HH:mm)</td>";
      $this->salida .= "        <td>".$datosCirugia['duracion_cirugia']."</td>";
      $this->salida .= "    </tr>";
    }
        $this->salida .= "    </table>";
        $this->salida .= "   </fieldset>";
        $this->salida .= "   </td></tr>";
        $this->salida .= "   </table><br><BR>";

    if($_SESSION['IYM_CUENTAS_QX']['PRODUCTOS_IYM']){
      $this->salida .= "       <BR><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
      $this->salida .= "        <tr class=\"modulo_table_list_title\">";
      $this->salida .= "          <td width=\"10%\" nowrap>CODIGO</td>";
      $this->salida .= "          <td>PRODUCTO</td>";
	  $this->salida .= "          <td width=\"10%\" nowrap>LOTE</td>";
	  $this->salida .= "          <td width=\"10%\" nowrap>FEC VENCIMIENTO</td>";
      $this->salida .= "          <td width=\"10%\" nowrap>EXISTENCIAS</td>";
      $this->salida .= "          <td width=\"15%\" nowrap>CANTIDAD A FACTURAR</td>";
      $this->salida .= "          <td width=\"5%\" nowrap>&nbsp;</td>";
      $this->salida .= "        </tr>";
      foreach($_SESSION['IYM_CUENTAS_QX']['PRODUCTOS_IYM'] as $codigoProducto=>$vector2){
        foreach($vector2 as $lote=>$vector1){
			foreach($vector1 as $fecha_vencimiento=>$vector){
				foreach($vector as $descripcion=>$existencias){
				  if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
				  $this->salida .= "    <tr class=\"$estilo\">";
				  $this->salida .= "      <td>$codigoProducto</td>";
				  $this->salida .= "      <td>$descripcion</td>";
				  $this->salida .= "      <td>$lote</td>";
				  $this->salida .= "      <td>$fecha_vencimiento</td>";
				  $this->salida .= "      <td>$existencias</td>";
				  $actionElim=ModuloGetURL('app','DatosLiquidacionQX','user','EliminarItemsCuentaPaciente',array("NoLiquidacion"=>$NoLiquidacion,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso,"codigoProducto"=>$codigoProducto,"lote"=>$lote,"fecha_vencimiento"=>$fecha_vencimiento));
				  $this->salida .= "      <td align=\"center\"><input type=\"text\" class=\"input-submit\" name=\"CantFacturar[".$codigoProducto."][".$lote."][".$fecha_vencimiento."]\" size=\"4\" value=\"".$_SESSION['IYM_CUENTAS_QX']['PRODUCTOS_IYM_CANTIDADES_DES'][$codigoProducto][$lote][$fecha_vencimiento]."\"></td>";
				  $this->salida .= "      <td align=\"center\"><a title=\"Eliminar del Listado\" href=\"$actionElim\"><img border = 0 src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
				  $this->salida .= "    </tr>";
				  $y++;
				}
			}	
		}
      }
      if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
      $this->salida .= "      <tr class=\"$estilo\">";
      $this->salida .= "      <td colspan=\"7\" align=\"right\"><input type=\"submit\" class=\"input-submit\" name=\"Facturar\" value=\"CARGA A LA CUENTA\"></td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      </table>";
    }else{
      $this->salida .= "      <BR><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
      $this->salida .= "      <tr><td align=\"center\" class=\"label_error\">";
      $this->salida .= "        NO SE HAN SELECCIONADO PRODUCTOS PARA CARGAR A LA CUENTA DEL PACIENTE";
      $this->salida .= "      </td></tr>";
      $this->salida .= "      </table>";
    }
    $this->salida .= "      <BR><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
    $this->salida .= "      <tr><td align=\"center\">";
    $this->salida .= "      <input type=\"submit\" name=\"SeleccionPaquete\" value=\"SELECCION PAQUETE\" class=\"input-submit\">";
    $this->salida .= "      <input type=\"submit\" name=\"SeleccionProducto\" value=\"SELECCION PRODUCTO\" class=\"input-submit\">";
    $this->salida .= "      </td></tr>";
    $this->salida .= "      </table>";

    $this->salida .= "    </form>";
    $action=ModuloGetURL('app','DatosLiquidacionQX','user','CargaInsumosMedicamentosCuenta',array("liquidacionId"=>$NoLiquidacion,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso));
    $this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
    $this->salida .= "    <BR><table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "    <tr><td align=\"center\"><input type=\"submit\" name=\"Volver\" value=\"VOLVER\" class=\"input-submit\"></td></td>";
    $this->salida .= "    </table>";
    $this->salida .= "    </form>";
    $this->salida .= ThemeCerrarTabla();
        return true;
  }

  /**
    *       BuscadorProductoInv
    *
  *   Funcion que muestra la consulta de los productos en el inventario
    *       @Author Lorena Arag�n G.
    *       @access Private
    *       @return boolean
    */

  function BuscadorProductoInv($NoLiquidacion,$TipoDocumento,$Documento,$nombrePaciente,$cuenta,$ingreso,$codigoBus,$DescripcionBus){

    $this->salida .= ThemeAbrirTabla('BUSCADOR PRODUCTOS INVENTARIOS');
        $action=ModuloGetURL('app','DatosLiquidacionQX','user','LlamaBuscadorProductoInv',array("NoLiquidacion"=>$NoLiquidacion,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso));
        $this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
        $this->Encabezado();
    $this->salida .= "    <BR><table width=\"80%\" border=\"0\" align=\"center\">";
        $this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"4\">PARAMENTROS DE BUSQUEDA</td></tr>";
        $this->salida .= "    <tr class=\"modulo_list_claro\">";
    $this->salida .= "     <td class=\"label\">CODIGO</td>";
        $this->salida .= "        <td><input type=\"text\" class=\"input-text\" name=\"codigoBus\" size=\"10\" value=\"$codigoBus\"></td>";
        $this->salida .= "     <td class=\"label\">DESCRIPCION</td>";
    $this->salida .= "     <td><input size=\"70\" type=\"text\" name=\"DescripcionBus\" value=\"".$DescripcionBus."\" class=\"input-submit\"></td>";
    $this->salida .= "    </tr>";
    $this->salida .= "    <tr class=\"modulo_list_claro\"><td align=\"center\" colspan=\"4\">";
        $this->salida .= "     <input class=\"input-submit\" type=\"submit\" name=\"Filtrar\" value=\"FILTRAR\" class=\"input-submit\">";
        $this->salida .= "     <input class=\"input-submit\" type=\"submit\" name=\"Volver\" value=\"VOLVER\" class=\"input-submit\">";
    $this->salida .= "    </td></tr>";
        $this->salida .= "    </table><BR>";
        $ProductosBodega=$this->ProductosInventariosBodega($codigoBus,$DescripcionBus);
    if($ProductosBodega){
          $this->salida .= "    <table width=\"80%\" border=\"0\" align=\"center\">";
      $this->salida .= "    <tr class=\"modulo_table_list_title\">";
            $this->salida .= "    <td width=\"20%\">CODIGO</td>";
            $this->salida .= "    <td>DESCRIPCION</td>";
			$this->salida .= "    <td width=\"15%\">LOTE</td>";
			$this->salida .= "    <td width=\"15%\">FEC VENCIMIENTO</td>";
			$this->salida .= "    <td width=\"15%\">EXISTENCIAS</td>";
            $this->salida .= "    <td width=\"5%\">&nbsp;</td>";
            $this->salida .= "    </tr>";
            for($i=0;$i<sizeof($ProductosBodega);$i++){
        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
        $this->salida .= "    <tr class=\"$estilo\">";
        $this->salida .= "    <td>".$ProductosBodega[$i]['codigo_producto']."</td>";
        $this->salida .= "    <td>".$ProductosBodega[$i]['descripcion']."</td>";
		$this->salida .= "    <td>".$ProductosBodega[$i]['lote']."</td>";
		$this->salida .= "    <td>".$ProductosBodega[$i]['fecha_vencimiento']."</td>";
        $this->salida .= "    <td>".$ProductosBodega[$i]['existencia']."</td>";
                $actionSelect=ModuloGetURL('app','DatosLiquidacionQX','user','SeleccionProductoInventariosQx',array("producto"=>$ProductosBodega[$i]['codigo_producto'],"descripcion"=>$ProductosBodega[$i]['descripcion'], "lote"=>$ProductosBodega[$i]['lote'],"fecha_vencimiento"=>$ProductosBodega[$i]['fecha_vencimiento'],"existencia"=>$ProductosBodega[$i]['existencia'],
        "NoLiquidacion"=>$NoLiquidacion,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso));
                $this->salida .= "    <td align=\"center\" width=\"5%\"><a href=\"$actionSelect\"><img title=\"Seleccionar Producto\" border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"></a></td>";
                $this->salida .= "    </tr>";
                $y++;
            }
            $this->salida .= "    </table><BR>";
            $Paginador = new ClaseHTML();
      $this->actionPaginador=ModuloGetURL('app','DatosLiquidacionQX','user','LlamaBuscadorProductoInv',array("NoLiquidacion"=>$NoLiquidacion,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso,"codigoBus"=>$codigoBus,"DescripcionBus"=>$DescripcionBus));
            $this->salida .= "      ".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->actionPaginador);
        }else{
      $this->salida .= "    <table width=\"60%\" border=\"0\" align=\"center\">";
      $this->salida .= "    <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON REGISTROS CON ESTOS PARAMETROS</td></tr>";
      $this->salida .= "      </table><BR>";
        }
    $this->salida .= "      </form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
  }

  /**
    *       FrmConsultarRegistrosDespachos
    *
  *   Funcion que muestra la consulta de los productos en el inventario
    *       @Author Lorena Arag�n G.
    *       @access Private
    *       @return boolean
    */


  function BuscadorPaquetesInv($NoLiquidacion,$TipoDocumento,$Documento,$nombrePaciente,$cuenta,$ingreso,$codigoBus,$DescripcionBus,$codigoBus,$DescripcionBus){
    $this->paginaActual = 1;
    $this->offset = 0;
    if($_REQUEST['offset']){
      $this->paginaActual = intval($_REQUEST['offset']);
      if($this->paginaActual > 1){
        $this->offset = ($this->paginaActual - 1) * ($this->limit);
      }
    }
    $this->salida .= ThemeAbrirTabla('BUSCADOR PAQUETES INVENTARIOS');
        $action=ModuloGetURL('app','DatosLiquidacionQX','user','SeleccionPaquetesInventariosQx',array("offset"=>$this->paginaActual,"NoLiquidacion"=>$NoLiquidacion,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso));
        $this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
        $this->Encabezado();
    $this->salida .= "    <BR><table width=\"80%\" border=\"0\" align=\"center\">";
        $this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"4\">PARAMENTROS DE BUSQUEDA</td></tr>";
        $this->salida .= "    <tr class=\"modulo_list_claro\">";
    $this->salida .= "     <td class=\"label\">CODIGO PAQUETE</td>";
        $this->salida .= "        <td><input type=\"text\" class=\"input-text\" name=\"codigoBus\" size=\"10\" value=\"$codigoBus\"></td>";
        $this->salida .= "     <td class=\"label\">DESCRIPCION</td>";
    $this->salida .= "     <td><input size=\"70\" type=\"text\" name=\"DescripcionBus\" value=\"".$DescripcionBus."\" class=\"input-submit\"></td>";
    $this->salida .= "    </tr>";
    $this->salida .= "    <tr class=\"modulo_list_claro\"><td align=\"center\" colspan=\"4\">";
        $this->salida .= "     <input class=\"input-submit\" type=\"submit\" name=\"Filtrar\" value=\"FILTRAR\" class=\"input-submit\">";
        $this->salida .= "     <input class=\"input-submit\" type=\"submit\" name=\"Volver\" value=\"VOLVER\" class=\"input-submit\">";
    $this->salida .= "    </td></tr>";
        $this->salida .= "    </table><BR>";
        $PaquetesBodega=$this->PaquetesInventariosBodega($codigoBus,$DescripcionBus);
    if($PaquetesBodega){
          $this->salida .= "    <table width=\"80%\" border=\"0\" align=\"center\">";
      $this->salida .= "    <tr class=\"modulo_table_list_title\">";
            $this->salida .= "    <td width=\"20%\">CODIGO</td>";
            $this->salida .= "    <td>DESCRIPCION</td>";
      $this->salida .= "    <td width=\"5%\">&nbsp;</td>";
            $this->salida .= "    <td width=\"5%\">&nbsp;</td>";
            $this->salida .= "    </tr>";
            for($i=0;$i<sizeof($PaquetesBodega);$i++){
        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
        $this->salida .= "    <tr class=\"$estilo\">";
        $this->salida .= "    <td>".$PaquetesBodega[$i]['paquete_insumos_id']."</td>";
                $this->salida .= "    <td>".$PaquetesBodega[$i]['descripcion']."</td>";
        $actionSelect=ModuloGetURL('app','DatosLiquidacionQX','user','ConsultaPaquetesInventariosQx',array("paqueteId"=>$PaquetesBodega[$i]['paquete_insumos_id'],"nomPaquete"=>$PaquetesBodega[$i]['descripcion'],"codigoBus"=>$codigoBus,"DescripcionBus"=>$DescripcionBus,
        "NoLiquidacion"=>$NoLiquidacion,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso));
                $this->salida .= "    <td align=\"center\" width=\"5%\"><a href=\"$actionSelect\"><img title=\"Consultar Productos Paquete\" border=\"0\" src=\"".GetThemePath()."/images/pconsultar.png\"><a></td>";
        $actionPaq=ModuloGetURL('app','DatosLiquidacionQX','user','SeleccionPtosPaqueteInv',array("paqueteId"=>$PaquetesBodega[$i]['paquete_insumos_id'],
        "NoLiquidacion"=>$NoLiquidacion,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso));
                $this->salida .= "    <td align=\"center\" width=\"5%\"><a href=\"$actionPaq\"><img title=\"Seleccionar Paquete\" border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"></a></td>";
                $this->salida .= "    </tr>";
                $y++;
            }
            $this->salida .= "    </table>";
            $Paginador = new ClaseHTML();
      $this->actionPaginador=ModuloGetURL('app','DatosLiquidacionQX','user','SeleccionPaquetesInventariosQx',array("NoLiquidacion"=>$NoLiquidacion,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso,"codigoBus"=>$codigoBus,"DescripcionBus"=>$DescripcionBus));
            $this->salida .= "      ".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->actionPaginador);
        }else{
      $this->salida .= "    <table width=\"60%\" border=\"0\" align=\"center\">";
      $this->salida .= "    <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON REGISTROS CON ESTOS PARAMETROS</td></tr>";
      $this->salida .= "      </table><BR>";
        }
    $this->salida .= "      </form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
  }

  /**
    *       LlamaConsultaPaquetesInventariosQx
    *
  *   Funcion que muestra la consulta de los productos en el inventario
    *       @Author Lorena Arag�n G.
    *       @access Private
    *       @return boolean
    */

  function LlamaConsultaPaquetesInventariosQx($NoLiquidacion,$TipoDocumento,$Documento,$nombrePaciente,$cuenta,$ingreso,$paqueteId,$nomPaquete,$codigoBus,$DescripcionBus){

    $this->salida .= ThemeAbrirTabla('PRODUCTOS QUE CONTIENEN LOS PAQUETES');
        $action=ModuloGetURL('app','DatosLiquidacionQX','user','SeleccionPaquetesInventariosQx',array("NoLiquidacion"=>$NoLiquidacion,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso,"codigoBus"=>$codigoBus,"DescripcionBus"=>$DescripcionBus));
        $this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
        $this->Encabezado();
    $ProductosPaquetes=$this->ProductosPaquetesInventariosBodega($paqueteId);
    if($ProductosPaquetes){
          $this->salida .= "    <BR><table width=\"80%\" border=\"0\" align=\"center\">";
      $this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"3\" align=\"3\">$nomPaquete</td></tr>";
      $this->salida .= "    <tr class=\"modulo_table_list_title\">";
            $this->salida .= "    <td width=\"20%\">CODIGO</td>";
            $this->salida .= "    <td>DESCRIPCION</td>";
      $this->salida .= "    <td width=\"10%\">CANTIDAD</td>";
            $this->salida .= "    </tr>";
            for($i=0;$i<sizeof($ProductosPaquetes);$i++){
        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
        $this->salida .= "    <tr class=\"$estilo\">";
        $this->salida .= "    <td>".$ProductosPaquetes[$i]['codigo_producto']."</td>";
                $this->salida .= "    <td>".$ProductosPaquetes[$i]['descripcion']."</td>";
        $this->salida .= "    <td>".$ProductosPaquetes[$i]['cantidad']."</td>";
                $this->salida .= "    </tr>";
                $y++;
            }
            $this->salida .= "    </table>";
        }else{
      $this->salida .= "    <BR><table width=\"60%\" border=\"0\" align=\"center\">";
      $this->salida .= "    <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON REGISTROS CON ESTOS PARAMETROS</td></tr>";
      $this->salida .= "      </table>";
        }
    $this->salida .= "    <table width=\"60%\" border=\"0\" align=\"center\">";
    $this->salida .= "    <tr><td align=\"center\"><input type=\"submit\" value=\"VOLVER\" class=\"input-submit\" name=\"volveer\"></td></tr>";
    $this->salida .= "    </table>";
    $this->salida .= "      </form>";
    $this->salida .= ThemeCerrarTabla();
        return true;
  }

  function DevolucionCargosCuenta($NoLiquidacion,$TipoDocumento,$Documento,$nombrePaciente,$cuenta,$ingreso,$ProductoFechaVence,$NomProductoFechaVence){
    $this->salida .= ThemeAbrirTabla('DEVOLUCION DE INSUMOS Y MEDICAMENTOS DE LA CUENTA DEL PACIENTE');
    $action=ModuloGetURL('app','DatosLiquidacionQX','user','GuardarDevolucioIyMCuenta',array("NoLiquidacion"=>$NoLiquidacion,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso));
    $this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
    $this->salida .= "  <script>";
    $this->salida .= "  function CargarFechaVence(frm,codigo,descripcion){";
    $this->salida .= "    frm.ProductoFechaVence.value=codigo;";
    $this->salida .= "    frm.NomProductoFechaVence.value=descripcion;";
    $this->salida .= "    frm.submit();";
    $this->salida .= "  }";
    $this->salida .= "  </script>";
    $this->Encabezado1();
        $this->salida .= "  <table width=\"40%\" border=\"0\" align=\"center\">";
        $this->salida .= "  <tr><td align=\"center\">";
        $this->salida .=    $this->SetStyle("MensajeError");
        $this->salida .= "  </td></tr>";
    $this->salida .= "   </table>";
    $this->salida .= "  <table width=\"70%\" border=\"0\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">DATOS DEL PROCEDIMIENTO</legend>";
        $this->salida .= "    <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"98%\" align=\"center\">";
    $this->salida .= "    <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"25%\" class=\"label\">NUMERO LIQUIDACION</td>";
        $this->salida .= "      <td colspan=\"3\">".$NoLiquidacion."</td>";
        $this->salida .= "    </tr>";
    $this->salida .= "    <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"25%\" class=\"label\">PACIENTE</td>";
        $this->salida .= "      <td colspan=\"3\">".$TipoDocumento." ".$Documento." - ".$nombrePaciente."</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"25%\" class=\"label\">No. CUENTA</td>";
        $this->salida .= "      <td>".$cuenta."</td>";
        $this->salida .= "      <td width=\"25%\" class=\"label\">No. INGRESO</td>";
        $this->salida .= "      <td>".$ingreso."</td>";
        $this->salida .= "    </tr>";
    $datosCirugia=$this->TraeDatosCirugia($NoLiquidacion);
    if($datosCirugia){
      $estado=$datosCirugia['estado'];
      $this->salida .= "    <tr class=\"modulo_list_claro\">";
      $this->salida .= "        <td width=\"25%\" class=\"label\">VIA ACCESO</td>";
      $this->salida .= "        <td>".$datosCirugia['via']."</td>";
      $this->salida .= "        <td width=\"25%\" class=\"label\">AMBITO</td>";
      $this->salida .= "        <td>".$datosCirugia['ambito']."</td>";
      $this->salida .= "    </tr>";
      $this->salida .= "    <tr class=\"modulo_list_claro\">";
      $this->salida .= "        <td width=\"25%\" class=\"label\">FINALIDAD</td>";
      $this->salida .= "        <td>".$datosCirugia['finalidad']."</td>";
      $this->salida .= "        <td width=\"25%\" class=\"label\">TIPO</td>";
      $this->salida .= "        <td>".$datosCirugia['tipo']."</td>";
      $this->salida .= "    </tr>";
      $this->salida .= "    <tr class=\"modulo_list_claro\">";
      $this->salida .= "        <td width=\"25%\" class=\"label\">FECHA</td>";
      (list($fecha,$hora)=explode(' ',$datosCirugia['fecha_cirugia']));
      (list($ano,$mes,$dia)=explode('-',$fecha));
      (list($hora,$minutos)=explode(':',$hora));
      $this->salida .= "        <td>".ucwords(strftime("%b %d de %Y %H:%M",mktime($hora,$minutos,0,$mes,$dia,$ano)))."</td>";
      $this->salida .= "        <td width=\"25%\" class=\"label\">DURACION (HH:mm)</td>";
      $this->salida .= "        <td>".$datosCirugia['duracion_cirugia']."</td>";
      $this->salida .= "    </tr>";
    }
        $this->salida .= "    </table>";
        $this->salida .= "   </fieldset>";
        $this->salida .= "   </td></tr>";
        $this->salida .= "   </table><br><BR>";
    $this->salida .= "    <table width=\"98%\" border=\"0\" align=\"center\" class=\"modulo_table_list_title\">";
    $this->salida .= "    <tr class=\"modulo_table_title\"><td colspan=\"7\">INSUMOS Y MEDICAMENTOS CARGADOS EN LA CUENTA</td></tr>";
    $cargos=$this->CargosIyMCuentaPacienteTotal($NoLiquidacion);
    if($cargos){
      $this->salida .= "    <tr class=\"modulo_table_title\">";
            $this->salida .= "    <td width=\"10%\">CODIGO</td>";
      $this->salida .= "    <td width=\"13%\">CANT. FACTURADA</td>";
      $this->salida .= "    <td>PRODUCTO</td>";
	  $this->salida .= "    <td width=\"15%\" align=\"center\">LOTE</td>";
      $this->salida .= "    <td width=\"10%\">FECHAS VENCIMIENTO</td>";
      $this->salida .= "    <td width=\"15%\">CANT. A DEVOLVER</td>";
            $this->salida .= "    </tr>";
      for($i=0;$i<sizeof($cargos);$i++){

        if($y % 2){$estilo='modulo_list_claro';$estilo1='modulo_list_oscuro';}else{$estilo='modulo_list_oscuro';$estilo1='modulo_list_claro';}
        $this->salida .= "    <tr class=\"$estilo\">";
        $this->salida .= "    <td align=\"left\">".$cargos[$i]['codigo_producto']."</td>";
        $divisor=(int)($cargos[$i]['total']);
        if($cargos[$i]['total']%$divisor){
          $this->salida .= "    <td align=\"left\">".$cargos[$i]['total']."</td>";
          $divisor=$cargos[$i]['total'];
        }else{
         $this->salida .= "    <td align=\"left\">".$divisor."</td>";
        }
        $this->salida .= "    <input type=\"hidden\" name=\"VectorTotal[".$cargos[$i]['codigo_producto']."][".$cargos[$i]['lote']."][".$cargos[$i]['fecha_vencimiento']."]\" value=\"".$divisor."\">";
        
		if(empty($cargos[$i]['forma_farmacologica']))
		{
			$cargos[$i]['forma_farmacologica'] = '';
		}
		if(empty($cargos[$i]['concentracion_forma_farmacologica']))
		{
			$cargos[$i]['concentracion_forma_farmacologica'] = '';
		}
		
		$this->salida .= "    <td align=\"left\">".$cargos[$i]['descripcion']." ".$cargos[$i]['forma_farmacologica']." ".$cargos[$i]['concentracion_forma_farmacologica']."</td>";
		$this->salida .= "    <td align=\"center\">".$cargos[$i]['lote']."</td>";
        $sw_control_fecha_vencimiento=$this->HallarRequerimientoFechasVence($cargos[$i]['codigo_producto']);
        if($sw_control_fecha_vencimiento==1){
        $this->salida .= "  <input type=\"hidden\" name=\"VectorFechas[]\" value=\"".$cargos[$i]['codigo_producto']."\">";
        $this->salida .= "<td>";
        $this->salida .= "  <table width=\"100%\" border=\"0\" align=\"center\">";
        $this->salida .= "  <tr class=\"$estilo1\">";
        $this->salida .= "      <td>";
        $this->salida .= "      <a href=\"javascript:CargarFechaVence(document.forma,'".$cargos[$i]['codigo_producto']."','".urlencode($cargos[$i]['descripcion'])."')\"><img title=\"Insertar Fechas Vencimiento\" border=\"0\" src=\"".GetThemePath()."/images/pparamed.png\"></a>";
        $this->salida .= "      </td>";
        if($_SESSION['IYM_CUENTAS_QX_DEVOL']['FECHAS_VENCE'][$cargos[$i]['codigo_producto']]){
          $this->salida .= "    <td>";
          $this->salida .= "        <table width=\"100%\" border=\"0\" align=\"center\">";
          $this->salida .= "        <tr class=\"modulo_table_title\">";
          $this->salida .= "        <td>LOTE</td>";
          $this->salida .= "        <td>CANT.</td>";
          $this->salida .= "        <td>FECHA</td>";
          $this->salida .= "        <td>&nbsp;</td>";
          $this->salida .= "        </tr>";
          foreach($_SESSION['IYM_CUENTAS_QX_DEVOL']['FECHAS_VENCE'][$cargos[$i]['codigo_producto']] as $lote=>$valor){
            (list($cantidades,$fecha)=explode('||//',$valor));
            $this->salida .= "        <tr class=\"$estilo1\">";
            $this->salida .= "        <td>$lote</td>";
            $this->salida .= "        <td>$cantidades</td>";
            $this->salida .= "        <td>$fecha</td>";
            $actionEliminaFV=ModuloGetURL('app','DatosLiquidacionQX','user','EliminarFechaVencimientos',array("codigoProducto"=>$cargos[$i]['codigo_producto'],"lote"=>$lote,"NoLiquidacion"=>$NoLiquidacion,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso));
            $this->salida .= "        <td><a href=\"$actionEliminaFV\"><img title=\"Eliminar Lote\" border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
            $this->salida .= "       </tr>";
          }
          $this->salida .= "       </table>";
          $this->salida .= "   </td>";
        }
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "    </td>";
        }else{
        $this->salida .= "    <td>";
        $this->salida .= "    ".$cargos[$i]['fecha_vencimiento']."";
        $this->salida .= "    </td>";
        }
        $this->salida .= "    <td align=\"center\"><input type=\"text\" name=\"CantidadDevol[".$cargos[$i]['codigo_producto']."][".$cargos[$i]['lote']."][".$cargos[$i]['fecha_vencimiento']."]\" value=\"".$_SESSION['IYM_CUENTAS_QX_DEVOL']['PRODUCTOS_IYM_CANTIDADES_DEV'][$cargos[$i]['codigo_producto']][$cargos[$i]['lote'][$cargos[$i]['fecha_vencimiento']]]."\" class=\"input-text\" size=\"4\"></td>";
        $y++;
      }
      if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
      $this->salida .= "    <tr class=\"$estilo\">";
      $this->salida .= "    <td align=\"right\" colspan=\"7\"><input type=\"submit\" class=\"input-submit\" name=\"Devolver\" value=\"CARGAR DEVOLUCION\"></td>";
      $this->salida .= "    </tr>";
    }
    $this->salida .= "    </table>";
    $this->salida .= "<input type=\"hidden\" name=\"ProductoFechaVence\" value=\"$ProductoFechaVence\">";
    $this->salida .= "<input type=\"hidden\" name=\"NomProductoFechaVence\" value=\"$NomProductoFechaVence\">";
    if(!empty($ProductoFechaVence)){

      $this->salida .= "<BR><table class=\"normal_10\" border=\"0\" width=\"98%\" align=\"center\">";
      $sumaCantLotes=0;
      if($_SESSION['IYM_CUENTAS_QX_DEVOL']['FECHAS_VENCE'][$ProductoFechaVence]){
      foreach($_SESSION['IYM_CUENTAS_QX_DEVOL']['FECHAS_VENCE'][$ProductoFechaVence] as  $lote=>$arreglo){
        (list($cantidades,$fecha)=explode('||//',$arreglo));
        $sumaCantLotes+=$cantidades;
      }
      $cantidadRest=$_SESSION['IYM_CUENTAS_QX_DEVOL']['PRODUCTOS_IYM_CANTIDADES_DEV'][$ProductoFechaVence]-$sumaCantLotes;
      }else{
      $cantidadRest=$_SESSION['IYM_CUENTAS_QX_DEVOL']['PRODUCTOS_IYM_CANTIDADES_DEV'][$ProductoFechaVence];
      }
      $this->salida .= "<tr class=\"modulo_table_title\">";
      $this->salida .= "<td width=\"80%\" nowrap align=\"center\">$ProductoFechaVence - $NomProductoFechaVence</td>";
      $this->salida .= "<td width=\"20%\" align=\"center\">CANTIDAD QUE FALTA POR INSERTAR ".$cantidadRest."</td></tr>";
      $this->salida .= "<tr><td colspan=\"2\" class=\"modulo_list_claro\">";
      $this->salida .= "  <table class=\"normal_10\" cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
      $this->salida .= "   <tr class=\"modulo_list_oscuro\">";
      $this->salida .= "   <td class=\"".$this->SetStyle("cantidadLote")."\">CANTIDAD</td><td><input type=\"text\" name=\"cantidadLote\" value=\"".$_REQUEST['cantidadLote']."\"></td>";
      $this->salida .= "   <td class=\"".$this->SetStyle("NoLote")."\">NUMERO LOTE</td><td><input type=\"text\" name=\"NoLote\" value=\"".$_REQUEST['NoLote']."\"></td>";
      $this->salida .= "     <td class=\"".$this->SetStyle("FechaVmto")."\">FECHA VENCIMIENTO </td>";
      $this->salida .= "     <td><input type=\"text\" name=\"FechaVmto\" size=\"10\" readonly value=\"".$_REQUEST['FechaVmto']."\" class=\"input-text\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\">";
      $this->salida .= "     ".ReturnOpenCalendario('forma','FechaVmto','/')."</td>";
      $this->salida.= "   </tr>";
      $this->salida.= "   <tr>";
      $this->salida .= "     <td align=\"center\" colspan=\"6\"><input class=\"input-submit\" type=\"submit\" name=\"insertarFV\" value=\"INSERTAR\"></td>";
      $this->salida.= "   </tr>";
      $this->salida.= "   </table>";
      $this->salida .= "</td></tr>";
      $this->salida.= "</table>";
    }
    $this->salida .= "    </form>";
    $action=ModuloGetURL('app','DatosLiquidacionQX','user','CargaInsumosMedicamentosCuenta',array("liquidacionId"=>$NoLiquidacion,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso));
    $this->salida .= "    <form name=\"forma1\" action=\"$action\" method=\"post\">";
    $this->salida .= "    <BR><table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "    <tr><td align=\"center\"><input type=\"submit\" name=\"Volver\" value=\"VOLVER\" class=\"input-submit\"></td></td>";
    $this->salida .= "    </table>";
    $this->salida .= "    </form>";
    $this->salida .= ThemeCerrarTabla();
        return true;
  }

    function FrmLiquidacionEquiposQX($liquidacionId,$TipoDocumento,$Documento,$nombrePaciente,$cuenta,$ingreso){
        $this->salida .= ThemeAbrirTabla('EQUIPOS QUIRURGICOS UTILIZADOS EN LA CIRUGIA');
    $action=ModuloGetURL('app','DatosLiquidacionQX','user','GuardarSeleccionEquipos',array("liquidacionId"=>$liquidacionId,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"cuenta"=>$cuenta,"ingreso"=>$ingreso,"nombrePaciente"=>$nombrePaciente));
        $this->Encabezado();

        $this->salida .= "  <table width=\"40%\" border=\"0\" align=\"center\">";
        $this->salida .= "  <tr><td align=\"center\">";
        $this->salida .=    $this->SetStyle("MensajeError");
        $this->salida .= "  </td></tr>";
    $this->salida .= "   </table>";
        $this->salida .= "  <table width=\"60%\" border=\"0\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">DATOS PACIENTE</legend>";
        $this->salida .= "    <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"98%\" align=\"center\">";
        $this->salida .= "    <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"20%\" class=\"label\">No. LIQUIDACION</td>";
        $this->salida .= "      <td colspan=\"3\">".$liquidacionId."</td>";
        $this->salida .= "    </tr>";
    $this->salida .= "    <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"20%\" class=\"label\">PACIENTE</td>";
        $this->salida .= "      <td colspan=\"3\">".$TipoDocumento." ".$Documento." - ".$nombrePaciente."</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"20%\" class=\"label\">No. CUENTA</td>";
        $this->salida .= "      <td>".$cuenta."</td>";
        $this->salida .= "      <td width=\"20%\" class=\"label\">No. INGRESO</td>";
        $this->salida .= "      <td width=\"25%\">".$ingreso."</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    </table>";
        $this->salida .= "   </fieldset>";
        $this->salida .= "   </td></tr>";
        $this->salida .= "   </table><br>";
        $duracionesFijas=$_REQUEST['duracionFijo'];
        $duracionesMoviles=$_REQUEST['duracionMovil'];
    $this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";

        if($_SESSION['EQUIPOS_LIQUIDACION_QX_FIJO_PROGRAMADO'][$liquidacionId]){
            $this->salida .= "    <table width=\"98%\" border=\"0\" align=\"center\" class=\"modulo_table_list_title\">";
            $this->salida .= "    <tr class=\"modulo_table_title\"><td colspan=\"6\">EQUIPOS FIJOS</td></tr>";
            $this->salida .= "    <tr class=\"modulo_table_title\">";
            $this->salida .= "    <td width=\"15%\" nowrap>DEPARTAMENTO</td>";
            $this->salida .= "    <td width=\"20%\" nowrap>QUIROFANO</td>";
            $this->salida .= "    <td width=\"20%\" nowrap>TIPO EQUIPO</td>";
            $this->salida .= "    <td width=\"15%\" nowrap>EQUIPO</td>";
            $this->salida .= "    <td width=\"20%\" nowrap>DURACION (minutos)</td>";
            $this->salida .= "    <td width=\"8%\" nowrap>&nbsp;</td>";
            $this->salida .= "    <tr>";
            foreach($_SESSION['EQUIPOS_LIQUIDACION_QX_FIJO_PROGRAMADO'][$liquidacionId] as $dpto=>$datos){
                foreach($datos as $quirofano=>$datos1){
                    foreach($datos1 as $tipoEquipo=>$datos2){
                        foreach($datos2 as $equipo=>$nomequipo){
                            $this->salida .= "    <tr class=\"modulo_list_oscuro\">";
                            $this->salida .= "    <td>".$dpto."</td>";
                            $this->salida .= "    <td>".$quirofano."</td>";
                            $this->salida .= "    <td>".$tipoEquipo."</td>";
                            $this->salida .= "    <td>".$nomequipo."</td>";
                            if($duracionesFijas[$equipo]){
                                $valor=$duracionesFijas[$equipo];
                            }elseif($_SESSION['EQUIPOS_LIQUIDACION_QX_FIJO_PROGRAMADO_DURACION'][$liquidacionId][$dpto][$quirofano][$tipoEquipo][$equipo]){
                                $valor=$_SESSION['EQUIPOS_LIQUIDACION_QX_FIJO_PROGRAMADO_DURACION'][$liquidacionId][$dpto][$quirofano][$tipoEquipo][$equipo];
                            }
                            $this->salida .= "    <td align=\"center\"><input size=\"3\" type=\"text\" name=\"duracionFijo[".$equipo."]\" value=\"".$valor."\" class=\"input-text\"></td>";
                            $actionElim=ModuloGetURL('app','DatosLiquidacionQX','user','EliminarEquipoLiquidacionQX',array("fijo"=>1,"liquidacionId"=>$liquidacionId,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso,"dpto"=>$dpto,
                            "quirofano"=>$quirofano,"tipoEquipo"=>$tipoEquipo,"equipo"=>$equipo));
                            $this->salida .= "    <td><a href=\"$actionElim\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
                            $this->salida .= "    </tr>";
                            $y++;
                        }
                    }
                }
            }
            $this->salida .= "    </table></BR>";
        }
        if($_SESSION['EQUIPOS_LIQUIDACION_QX_MOVILES_PROGRAMADO'][$liquidacionId]){
            $this->salida .= "    <table width=\"98%\" border=\"0\" align=\"center\" class=\"modulo_table_list_title\">";
            $this->salida .= "    <tr class=\"modulo_table_title\"><td colspan=\"5\">EQUIPOS MOVILES</td></tr>";
            $this->salida .= "    <tr class=\"modulo_table_title\">";
            $this->salida .= "    <td width=\"15%\" nowrap>DEPARTAMENTO</td>";
            $this->salida .= "    <td width=\"20%\" nowrap>TIPO EQUIPO</td>";
            $this->salida .= "    <td width=\"15%\" nowrap>EQUIPO</td>";
            $this->salida .= "    <td width=\"20%\" nowrap>DURACION (minutos)</td>";
            $this->salida .= "    <td width=\"8%\" nowrap>&nbsp;</td>";
            $this->salida .= "    <tr>";
            foreach($_SESSION['EQUIPOS_LIQUIDACION_QX_MOVILES_PROGRAMADO'][$liquidacionId] as $dpto=>$datos){
                foreach($datos as $tipoEquipo=>$datos1){
                    foreach($datos1 as $equipo=>$nomequipo){
                        $this->salida .= "    <tr class=\"modulo_list_oscuro\">";
                        $this->salida .= "    <td>".$dpto."</td>";
                        $this->salida .= "    <td>".$tipoEquipo."</td>";
                        $this->salida .= "    <td>".$nomequipo."</td>";
                        $valor='';
                        if($duracionesMoviles[$equipo]){
                            $valor=$duracionesMoviles[$equipo];
                        }elseif($_SESSION['EQUIPOS_LIQUIDACION_QX_MOVILES_PROGRAMADO_DURACION'][$liquidacionId][$dpto][$tipoEquipo][$equipo]){
                            $valor=$_SESSION['EQUIPOS_LIQUIDACION_QX_MOVILES_PROGRAMADO_DURACION'][$liquidacionId][$dpto][$tipoEquipo][$equipo];
                        }
                        $this->salida .= "    <td align=\"center\"><input size=\"3\" type=\"text\" name=\"duracionMovil[".$equipo."]\" value=\"".$valor."\" class=\"input-text\"></td>";
                        $actionElim=ModuloGetURL('app','DatosLiquidacionQX','user','EliminarEquipoLiquidacionQX',array("liquidacionId"=>$liquidacionId,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso,"dpto"=>$dpto,
                        "quirofano"=>$quirofano,"tipoEquipo"=>$tipoEquipo,"equipo"=>$equipo));
                        $this->salida .= "    <td><a href=\"$actionElim\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
                        $this->salida .= "    </tr>";
                        $y++;
                    }
                }
            }
            $this->salida .= "    </table>";
        }
        if(!$_SESSION['EQUIPOS_LIQUIDACION_QX_FIJO_PROGRAMADO'][$liquidacionId] && !$_SESSION['EQUIPOS_LIQUIDACION_QX_MOVILES_PROGRAMADO'][$liquidacionId]){
            $this->salida .= "    <table width=\"98%\" border=\"0\" align=\"center\" class=\"normal_10\">";
            $this->salida .= "    <tr><td align=\"center\" class=\"label_error\">NO SE SELECCIONARON EQUIPOS EN LA PROGRAMACION</td></tr>";
            $this->salida .= "    </table>";
        }
        if($_SESSION['EQUIPOS_LIQUIDACION_QX_FIJO_PROGRAMADO'][$liquidacionId] || $_SESSION['EQUIPOS_LIQUIDACION_QX_MOVILES_PROGRAMADO'][$liquidacionId]){
            $this->salida .= "    <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
            $this->salida .= "    <tr><td align=\"center\"><input type=\"submit\" name=\"GuardarEquipos\" value=\"GUARDAR EQUIPOS\" class=\"input-submit\"></td></tr>";
            $this->salida .= "    </table>";
        }
        $this->salida .= "    <table width=\"98%\" border=\"0\" align=\"center\" class=\"normal_10\">";
        $action=ModuloGetURL('app','DatosLiquidacionQX','user','BuscadorEquipoQX',array("liquidacionId"=>$liquidacionId,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso));
        $this->salida .= "    <tr><td align=\"right\" class=\"label\"><a href=\"$action\">Seleccionar Equipo Quirugico</a></td></tr>";
        $this->salida .= "    </table>";
        $this->salida .= "    </BR><table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
        $this->salida .= "    <tr><td align=\"center\"><input type=\"submit\" name=\"Salir\" value=\"VOLVER\" class=\"input-submit\"></td></tr>";
        $this->salida .= "    </table>";
    $this->salida .= "    </form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }


  function Forma_Seleccion_EquiposQX($liquidacionId,$TipoDocumento,$Documento,$nombrePaciente,$cuenta,$ingreso,$tipoEquipo,$Quirofano,$Departamento,$descripcionEquipo){

        $this->paginaActual = 1;
    $this->offset = 0;
        $this->salida .= ThemeAbrirTabla('EQUIPOS QUIRURGICOS');
        $this->salida .= "<script>";
    $this->salida .= "function SeleccionQuiro(frm,valor){";
    $this->salida .= "  if(valor=='F'){";
    $this->salida .= "    frm.Quirofano.disabled=false;";
        $this->salida .= "  }else{";
    $this->salida .= "    frm.Quirofano.disabled=true;";
        $this->salida .="   }\n";
        $this->salida .=" }\n";
        $this->salida .= "</script>";
        $accion=ModuloGetURL('app','DatosLiquidacionQX','user','BuscadorEquipoQX',array("liquidacionId"=>$liquidacionId,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso));
        $this->salida .= "<form name=\"formades\" action=\"$accion\" method=\"post\">";
        $this->salida.="<table  align=\"center\" border=\"0\"  width=\"85%\">";
        $this->salida.="<tr class=\"modulo_table_title\">";
        $this->salida.="  <td align=\"center\" colspan=\"6\">BUSQUEDA AVANZADA </td>";
        $this->salida.="</tr>";
        $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
        $this->salida.="<td width=\"5%\">TIPO EQUIPO</td>";
        $this->salida.="<td width=\"10%\" align = left >";
        $this->salida.="<select size = 1 name = 'tipoEquipo'  class =\"select\" onchange=\"SeleccionQuiro(this.form,this.value)\">";
        if($tipoEquipo==-1){
      $selected='selected';
        }elseif(($tipoEquipo=='M')){
      $selected1='selected';
        }elseif(($tipoEquipo=='F')){
      $selected2='selected';
        }
    $this->salida.="<option value = '-1' $selected>Todos</option>";
        $this->salida.="<option value = 'M' $selected1>Movil</option>";
        $this->salida.="<option value = 'F' $selected2>Fijo</option>";
        $this->salida.="</select>";
        $this->salida.="</td>";
    if($tipoEquipo!='F'){
      $disable='disabled';
        }
        $this->salida.="<td width=\"5%\">QUIROFANO</td>";
        $this->salida.="<td width=\"10%\" align = left >";
        $this->salida.="<select size = 1 name = 'Quirofano'  class =\"select\" $disable>";
        $this->salida.="<option value = '-1' >Todos</option>";
        $quiros = $this->TiposQuirofanosTotal();
        for($i=0;$i<sizeof($quiros);$i++){
            if($Quirofano!= $quiros[$i]['quirofano']){
                $this->salida.="<option value = ".$quiros[$i]['quirofano'].">".$quiros[$i]['descripcion']."</option>";
            }else{
                $this->salida.="<option value = ".$quiros[$i]['quirofano']." selected >".$quiros[$i]['descripcion']."</option>";
            }
        }
        $this->salida.="</select>";
        $this->salida.="</td>";
        $this->salida.="<td width=\"5%\">DEPARTAMENTO</td>";
        $this->salida.="<td width=\"10%\" align = left >";
        $this->salida.="<select size = 1 name = 'Departamento'  class =\"select\">";
        $this->salida.="<option value = '-1' >Todos</option>";
        $Dptos = $this->TotalDepartamentos();
        for($i=0;$i<sizeof($Dptos);$i++){
            if($Departamento!= $Dptos[$i]['departamento']){
                $this->salida.="<option value = ".$Dptos[$i]['departamento'].">".$Dptos[$i]['descripcion']."</option>";
            }else{
                $this->salida.="<option value = ".$Dptos[$i]['departamento']." selected >".$Dptos[$i]['descripcion']."</option>";
            }
        }
        $this->salida.="</select>";
        $this->salida.="</td>";
        $this->salida.="</tr>";
        $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
        $this->salida.="<td width=\"10%\">DESCRIPCION</td>";
        $this->salida .="<td width=\"25%\" colspan=\"2\" align='center'><input type='text' class='input-text' name = 'descripcionEquipo'   value =\"$descripcionEquipo\"></td>" ;
        $this->salida .= "<td  colspan=\"3\" width=\"6%\" align=\"center\">";
        $this->salida .= "  <input class=\"input-submit\" name=\"Filtrar\" type=\"submit\" value=\"FILTRAR\">";
        $this->salida .= "  <input class=\"input-submit\" name=\"Volver\" type=\"submit\" value=\"VOLVER\">";
        $this->salida .= "</td>";
        $this->salida.="</tr>";
        $this->salida.="</table><br>";
        $vector=$this->BusquedaEquiposQX($tipoEquipo,$Quirofano,$Departamento,$descripcionEquipo);
        if($vector){
            $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
            $this->salida.="<tr class=\"modulo_table_title\">";
            $this->salida.="  <td align=\"center\" colspan=\"4\">RESULTADO DE LA BUSQUEDA</td>";
            $this->salida.="</tr>";
            $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
      $this->salida.="  <td >TIPO</td>";
            $this->salida.="  <td>DEPARTAMENTO</td>";
            $this->salida.="  <td>DESCRIPCION</td>";
            $this->salida.="  <td width=\"5%\">&nbsp;</td>";
            $this->salida.="</tr>";
            for($i=0;$i<sizeof($vector);$i++){
                if($i % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
                $this->salida.="<tr class=\"$estilo\">";
                if($vector[$i]['fijo']=='1'){
                $Fijo='F';
                $this->salida.="  <td>FIJO</td>";
                }else{
                $Fijo='M';
                $this->salida.="  <td>MOVIL</td>";
                }
                $this->salida.="  <td>".$vector[$i]['nom_departamento']."</td>";
                $this->salida.="  <td>".$vector[$i]['nom_equipo']."</td>";
                $action=ModuloGetURL('app','DatosLiquidacionQX','user','SeleccionarEquipoProgramacion',array("liquidacionId"=>$liquidacionId,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso,
                "dpto"=>$vector[$i]['nom_departamento'],"nom_equipo"=>$vector[$i]['nom_equipo'],"equipo"=>$vector[$i]['equipo_id'],"fijo"=>$Fijo,"quirofano"=>$vector[$i]['quirofano'],"tipoEquipoVec"=>$vector[$i]['tipo_equipo']));
                $this->salida.="  <td align=\"center\"><a href=\"$action\"><img title=\"Seleccion Equipo\" border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"></a></td>";
                $this->salida.="</tr>";
            }
            $this->salida.="</table>";
            $Paginador = new ClaseHTML();
      $this->actionPaginador=ModuloGetURL('app','DatosLiquidacionQX','user','BuscadorEquipoQX',array("liquidacionId"=>$liquidacionId,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso,
            "tipoEquipo"=>$tipoEquipo,"Quirofano"=>$Quirofano,"Departamento"=>$Departamento,"descripcionEquipo"=>$descripcionEquipo));
            $this->salida .= "      ".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->actionPaginador);
        }
        $this->salida .= "</form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
 }
 
  function FrmCancelarLiquidacionQX($NoLiquidacion,$TipoDocumento,$Documento,$nombrePaciente,$cuenta,$ingreso){

    $this->salida .= ThemeAbrirTabla('CANCELACION DE LA LIQUIDACION QX');
    $action=ModuloGetURL('app','DatosLiquidacionQX','user','InsertarCancelacionLiquidacionQX',array("NoLiquidacion"=>$NoLiquidacion,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso));
    $this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";    
    $this->Encabezado();
    $this->salida .= "  <table width=\"40%\" border=\"0\" align=\"center\">";
    $this->salida .= "  <tr><td align=\"center\">";
    $this->salida .=    $this->SetStyle("MensajeError");
    $this->salida .= "  </td></tr>";
    $this->salida .= "   </table>";
    $this->salida .= "  <table width=\"60%\" border=\"0\" align=\"center\">";
    $this->salida .= "  <tr><td>";
    $this->salida .= "  <fieldset><legend class=\"field\">DATOS PACIENTE</legend>";
    $this->salida .= "    <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"98%\" align=\"center\">";    
    $this->salida .= "    <tr class=\"modulo_list_claro\">";
    $this->salida .= "      <td width=\"20%\" class=\"label\">No. LIQUIDACION</td>";
    $this->salida .= "      <td colspan=\"3\">".$NoLiquidacion."</td>";
    $this->salida .= "    </tr>";    
    $this->salida .= "    <tr class=\"modulo_list_claro\">";
    $this->salida .= "      <td width=\"20%\" class=\"label\">PACIENTE</td>";
    $this->salida .= "      <td colspan=\"3\">".$TipoDocumento." ".$Documento." - ".$nombrePaciente."</td>";    
    $this->salida .= "    </tr>";
    $this->salida .= "    <tr class=\"modulo_list_claro\">";
    $this->salida .= "      <td width=\"20%\" class=\"label\">No. CUENTA</td>";
    $this->salida .= "      <td>".$cuenta."</td>";
    $this->salida .= "      <td width=\"20%\" class=\"label\">No. INGRESO</td>";
    $this->salida .= "      <td width=\"25%\">".$ingreso."</td>";
    $this->salida .= "    </tr>";
    $this->salida .= "    </table>";
    $this->salida .= "   </fieldset>";
    $this->salida .= "   </td></tr>";
    $this->salida .= "   </table><br>";
    
    $this->salida .= "    <table border=\"0\" width=\"60%\" align=\"center\" class=\"normal_10\">";
    $this->salida .= "       <tr><td><fieldset><legend class=\"field\">DATOS DE LA CANCELACION</legend>";
    $this->salida .= "          <table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
    $this->salida .= "          <tr class=\"modulo_list_claro\"><td width=\"30%\" class=\"".$this->SetStyle("motivoCancel")."\">MOTIVO CANCELACION: </td>";
    $this->salida .= "          <td><select name=\"motivoCancel\" class=\"select\">";
    $Motivos=$this->MotivosCancelacionLiquidacion();
    $this->salida .= "          <option value=\"-1\" selected>---Seleccione---</option>";
    for($i=0;$i<sizeof($Motivos);$i++){
      $var='';
      if($_REQUEST['motivoCancel']==$Motivos[$i]['motivo_id']){
        $var='selected';
      }
      $this->salida .= "          <option value=\"".$Motivos[$i]['motivo_id']."\" $var>".$Motivos[$i]['descripcion']."</option>";
    }
    $this->salida .= "          </select></td></tr>";
    $this->salida .= "          <tr class=\"modulo_list_claro\"><td width=\"30%\" class=\"".$this->SetStyle("observacion")."\">OBSERVACION</td><td><textarea name=\"observacion\" cols=\"50\" rows=\"3\" class=\"textarea\">".$_REQUEST['observacion']."</textarea></td></tr>";
    $this->salida .= "          </td></tr>";
    $this->salida .= "          </table>";
    $this->salida .= "       </fieldset></td></tr>";
    $this->salida .= "    </table>";
    $this->salida .= "    <table border=\"0\" width=\"60%\" align=\"center\" class=\"normal_10\">";
    $this->salida .= "    <tr><td align=\"center\">";
    $this->salida .= "    <input type=\"submit\" name=\"Volver\" value=\"VOLVER\" class=\"input-submit\">";
    $this->salida .= "    <input type=\"submit\" name=\"Guardar\" value=\"GUARDAR\" class=\"input-submit\">"; 
    $this->salida .= "    </td></tr>";
    $this->salida .= "    </td>";
    $this->salida .= "    </table>";
    $this->salida .= "</form>";
    $this->salida .= ThemeCerrarTabla();
    return true;
  }        








}//fin clase user
?>

