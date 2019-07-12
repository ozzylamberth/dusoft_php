<?php

/**
 * $Id: app_Central_de_Autorizaciones_userclasses_HTML.php,v 1.1.1.1 2009/09/11 20:36:19 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo para el manejo de autorizaciones
 */
class app_Central_de_Autorizaciones_userclasses_HTML extends app_Central_de_Autorizaciones_user {

    //Constructor de la clase app_Os_ListaTrabajo_userclasses_HTML
    function app_Central_de_Autorizaciones_userclasses_HTML() {
        $this->salida = '';
        $this->app_Central_de_Autorizaciones_user();
        return true;
    }

    //aoltu
    function SetStyle($campo) {
        if ($this->frmError[$campo] || $campo == "MensajeError") {
            if ($campo == "MensajeError") {
                $arreglo = array('numero' => $numero, 'prefijo' => $prefijo);
                return ("<tr><td class='label_error' colspan='3' align='center'>" . $this->frmError["MensajeError"] . "</td></tr>");
            }
            return ("label_error");
        }
        return ("label");
    }

    /*
     * Funcion donde se visualiza el encabezado de la empresa.
     * @return boolean
     */

    function Encabezado($mirar='', $reporte) {
        $this->salida .= "<br><table  class=\"modulo_table_list_title\" border=\"0\"  width=\"80%\" align=\"center\" >";
        $this->salida .= " <tr class=\"modulo_table_list_title\">";
        $this->salida .= " <td>EMPRESA</td>";
        $this->salida .= " <td>CENTRO UTILIDAD</td>";
        $this->salida .= " <td>DEPARTAMENTO</td>";
        $this->salida .= " </tr>";
        $this->salida .= " <tr align=\"center\">";
        $this->salida .= " <td class=\"modulo_list_claro\" >" . $_SESSION['CENTRO']['NOM_EMP'] . "</td>";
        $this->salida .= " <td class=\"modulo_list_claro\">" . $_SESSION['CENTRO']['NOM_CENTRO'] . "</td>";
        $this->salida .= " <td class=\"modulo_list_claro\" >" . $_SESSION['CENTRO']['NOM_DPTO'] . "</td>";
        $this->salida .= " </tr>";
        if ($mirar == '1') {
            //$reporte= new GetReports();
            $this->salida.=$reporte->GetJavaReport_HistoriaClinica($_SESSION['CENTRAL']['PACIENTE']['evolucion_id'], array());
            $funcion = $reporte->GetJavaFunction();

            $this->salida .= " <tr align=\"center\">";
            $this->salida .= " <td class=\"modulo_list_claro\" colspan=\"3\"><input type=\"button\" class=\"input-submit\" name=\"EVOLUCION\" value=\"EVOLUCION\" onclick=\"javascript:$funcion\"></td>";
            $this->salida .= " </tr>";
            //unset($reporte);

            /* if (!IncludeFile("classes/ResumenHC/ResumenHC.class.php"))
              {
              $this->error = "Error";
              $this->mensajeDeError = "No se pudo incluir : classes/ResumenHC/ResumenHC.class.php";
              }
              global $VISTA;
              if (!IncludeFile("classes/ResumenHC/$VISTA/ResumenHC.$VISTA.php"))
              {
              $this->error = "Error";
              $this->mensajeDeError = "No se pudo incluir : classes/ResumenHC/$VISTA/ResumenHC.$VISTA.php";
              }
              $temp="ResumenHC_$VISTA";
              $resumenhc = new $temp($_SESSION['CENTRAL']['PACIENTE']['evolucion_id']);
              if (!$resumenhc->IniciarImprimir())
              {
              $this->error = $resumenhc->Error();
              $this->mensajeDeError = $resumenhc->ErrorMsg();
              return false;
              }
              $resumenhc->GetImpresion();
              unset($resumenhc);
             */
            /* $this->salida .= " <tr align=\"center\">";
              $this->salida .= " <td class=\"modulo_list_claro\" colspan=\"3\"><input type=\"button\" name=\"EVOLUCION\" value=\"EVOLUCION\" onclick=\"window.open('cache/historia".$_SESSION['CENTRAL']['PACIENTE']['evolucion_id'].".pdf')\" class=\"input-submit\"></td>";
              $this->salida .= " </tr>"; */
        }
        $this->salida .= " </table>";
        return true;
    }

    function FormaServicioAmb($dats) {
        if ($dats) {
            $this->salida .= ThemeMenuAbrirTabla("SERVICIOS", "50%");
            for ($i = 0; $i < sizeof($dats); $i++) {

                $desc = strtoupper($dats[$i][descripcion]);
                //$centroU=$dats[$i][centro_utilidad];

                $this->salida.="<table border='0' width='100%'>";
                $this->salida.="    <tr>";
                $this->salida.="        <td align='left' class='normal_10N'>";
                $this->salida.="            <img src=\"" . GetThemePath() . "/images/editar.gif\">&nbsp;&nbsp;<a href=\"" . ModuloGetURL('app', 'Central_de_Autorizaciones', 'user', 'FormaMetodoBuscar', array("tipo_consulta" => $dats[$i][tipo_consulta_id])) . "\">$desc</a>";
                $this->salida.="    <tr>";
                $this->salida.="        <td align='left'>";
                $this->salida.="            <div class='normal_10_menu' valign='middle'><img src=\"" . GetThemePath() . "/images/flecha_der.gif\" width='10' height='10'>&nbsp;" . strtolower($desc) . "</div>";
                $this->salida.="        </td>";
                $this->salida.="    </tr>";
                $this->salida.="</table>";
                $this->salida .="<br>";
            }

            $this->salida.="<table border='0' align='center'>";
            $action3 = ModuloGetURL('app', 'Central_de_Autorizaciones', 'user', 'main');
            $this->salida .= "           <form name=\"forma\" action=\"$action3\" method=\"post\">";
            $this->salida.="    <tr>";
            $this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"VOLVER\"></td></tr>";
            $this->salida.="</table>";
            $this->salida .= ThemeMenuCerrarTabla();
        } else {
            $this->salida .= ThemeMenuAbrirTabla("SERVICIOS", "50%");
            $this->salida.="<table  align=\"center\" border=\"0\"  width=\"85%\">\n";
            $this->salida.="    <tr>\n";
            $this->salida.="        <td align=\"center\" class=\"label_error\">NO EXISTEN SERVICIOS AMBULATORIOS.</td>\n";
            $this->salida.="    </tr>\n";
            $this->salida.="</table>\n";
            $this->salida.="<table border='0' align='center'>";
            $action3 = ModuloGetURL('app', 'Central_de_Autorizaciones', 'user', 'main');
            $this->salida .= "           <form name=\"forma\" action=\"$action3\" method=\"post\">";
            $this->salida.="    <tr>";
            $this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"VOLVER\"></td></tr>";
            $this->salida.="</table>";

            $this->salida .= ThemeMenuCerrarTabla();
        }
        //$this->salida.="</center>\n";
        return true;
    }

    /**
     * Se utilizada listar en el combo los diferentes tipo de identifiacion de los pacientes
     * @access private
     * @return void
     */
    function BuscarIdPaciente($tipo_id, $TipoId='') {
        foreach ($tipo_id as $value => $titulo) {
            if ($value == $TipoId) {
                $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
            } else {
                $this->salida .=" <option value=\"$value\">$titulo</option>";
            }
        }
    }

    /*
     * Esta funcion realiza la busqueda de las ordenes de servicio según filtros como numero de orden
     * documento y plan
     * @return boolean
     */

    function FormaMetodoBuscar($arr, $sw) {
        unset($_SESSION['CENTRAL']['ARR_SOLICITUDES']);
        unset($_SESSION['SEGURIDAD']);
        unset($_SESSION['CENTRAL']['PROFESIONAL']);
        unset($_SESSION['CENTRAL']['RUTA']);
        unset($_SESSION['CENTRAL']['PACIENTE']);
        unset($_SESSION['CENTRAL']['DATOS']);
        if (empty($_SESSION['CENTRAL']['TIPO_CONSULTA'])) {
            $_SESSION['CENTRAL']['TIPO_CONSULTA'] = $_REQUEST['tipo_consulta'];
        }
        $dateDEA = date("Y-m-d");
        
        $this->salida.= ThemeAbrirTabla('ORDEN DE SERVICIOS MEDICOS');
        $this->Encabezado();
        $accion = ModuloGetURL('app', 'Central_de_Autorizaciones', 'user', 'BuscarOrdenes');
        $this->salida .= "<br><table class=\"modulo_table_list\" border=\"0\" width=\"80%\" align=\"center\">";
        $this->salida .= "<tr class=\"modulo_table_list_title\">";
        $this->salida .= "<td align = CENTER >CRITERIOS DE BUSQUEDA: </td>";
        $this->salida .= "<td align = CENTER >SELECCIONE LA FECHA:</td>";
        $this->salida .= "</tr>";
        $this->salida .= "<tr class=\"modulo_list_claro\" >";
        $this->salida .= "<td width=\"40%\" >";
        $this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\">";
        $this->salida .= "<tr><td>";
        $this->salida .= "<table width=\"80%\" align=\"center\" border=\"0\">";
        $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "<SCRIPT>";
        $this->salida .= "function Revisar(frm,x){";
        $this->salida .= "  if(x==true){";
        $this->salida .= "frm.Fecha.value='TODAS LAS FECHAS'";
        $this->salida .= "  }";
        $this->salida .= "else{";
        $this->salida .= "frm.Fecha.value=''";
        $this->salida .= "}";
        $this->salida .= "}";
        $this->salida .= "</SCRIPT>";
        $this->salida .= "<tr><td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"TipoDocumento\" class=\"select\">";
        $tipo_id = $this->tipo_id_paciente();
        $this->BuscarIdPaciente($tipo_id, '');
        $this->salida .= "</select></td></tr>";
        $this->salida .= "<tr><td class=\"label\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\"  value = " . $_REQUEST['Documento'] . "></td></tr>";
        $this->salida .= "<tr><td class=\"label\">NOMBRES</td><td><input type=\"text\" class=\"input-text\" name=\"Nombres\" maxlength=\"64\" value = " . $_REQUEST["Nombres"] . "></td></tr>";
        if(empty($_REQUEST['DiaEspe'])){
            $this->salida .= "<tr><td class=\"label\">FECHA</td><td><input type=\"text\" readonly class=\"input-text\" name=\"Fecha\" value = " . $dateDEA . "></td></tr>";
        }else{
            $this->salida .= "<tr><td class=\"label\">FECHA</td><td><input type=\"text\" readonly class=\"input-text\" name=\"Fecha\" value = " . $_REQUEST['DiaEspe'] . "></td></tr>";
        }
        $this->salida .= "<tr class=\"label\">";
        $this->salida .= "<td colspan = 2  align = left >TODAS LAS FECHAS";
        if ($_REQUEST['allfecha'] == 'on') {
            $check = 'checked';
        } else {
            $check = '';
        }
        $this->salida.="  &nbsp;<input type = checkbox name= 'allfecha' $check onclick=Revisar(this.form,this.checked) ></td>";
        $this->salida .= "</tr>";

        $this->salida .= "<tr class=\"label\">";
        if ($_REQUEST['allmedicos'] == 'on') {
            $checke = 'checked';
        } else {
            $checke = '';
        }
        $this->salida .= "<td colspan=2 align = left >SOLO MEDICOS";
        $this->salida.=" &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;<input type = checkbox name= 'allmedicos' $checke></td>";
        $this->salida .= "</tr>";

        $this->salida .= "<tr><td colspan = 2 align=\"center\" ><table>";
        $this->salida .= "<tr><td align=\"right\" ><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSQUEDA\"></td>";
        $this->salida .= "</form>";
        //es consulta
        if (!empty($_SESSION['CENTRAL']['TIPO_CONSULTA'])) {
            $actionM = ModuloGetURL('app', 'Central_de_Autorizaciones', 'user', 'BuscarServiciosAmb');
        } else {
            $actionM = ModuloGetURL('app', 'Central_de_Autorizaciones', 'user', 'main');
        }

        $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
        $this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"><br></td></form>";
        $this->salida .= "</tr>";
        $this->salida .= "</table></td></tr>";
        $this->salida .= "</td></tr></table>";
        $this->salida .= "</table>";
        $this->salida .= "</td>";
        $this->salida .= "<td>";
        $this->salida .= "<BR><table border=\"0\" width=\"80%\" align=\"center\">";
        $this->salida .= "<tr><td>";
        $this->salida.="\n" . '<script>' . "\n";
        $this->salida.='function year1(t)' . "\n";
        $this->salida.='{' . "\n";
        $this->salida.='window.location.href="Contenido.php?year="+t.elements[0].value+"&meses="+t.elements[1].value+"';
        foreach ($_REQUEST as $v => $v1) {
            if ($v != 'year' and $v != 'meses' and $v != 'DiaEspe') {
                if (is_array($v1)) {
                    foreach ($v1 as $k2 => $v2) {
                        if (is_array($v2)) {
                            foreach ($v2 as $k3 => $v3) {
                                if (is_array($v3)) {
                                    foreach ($v3 as $k4 => $v4) {
                                        $this->salida .= "&$v" . "[$k2][$k3][$k4]=$v4";
                                    }
                                } else {
                                    $this->salida .= "&$v" . "[$k2][$k3]=$v3";
                                }
                            }
                        } else {
                            $this->salida .= "&$v" . "[$k2]=$v2";
                        }
                    }
                } else {
                    $this->salida .= "&$v=$v1";
                }
            }
        }
        $this->salida.='";' . "\n";
        $this->salida.='}' . "\n";
        $this->salida.='</script>';
        $this->salida .='<form name="cosa">';
        $this->salida .="<table border=\"0\" width=\"80%\" align=\"center\">";
        $this->salida .='<tr align="center">';
        $this->salida .="<td class=\"label\">AÑO</td><td><select name=\"year\" onchange=\"year1(this.form)\" class=\"select\">";
        if (empty($_REQUEST['year'])) {
            $year = $_REQUEST['year'] = date("Y");
            $this->AnosAgenda(True, $_REQUEST['year']);
        } else {
            $this->AnosAgenda(true, $_REQUEST['year']);
            $year = $_REQUEST['year'];
        }
        $this->salida .= "</select></td>";
        $this->salida .="<td class=\"label\">MES</td><td><select name=\"mes\" onchange=\"year1(this.form)\" class=\"select\">";
        if (empty($_REQUEST['meses'])) {
            $mes = $_REQUEST['meses'] = date("m");
            $this->MesesAgenda(True, $year, $mes);
        } else {
            $this->MesesAgenda(True, $year, $_REQUEST['meses']);
            $mes = $_REQUEST['meses'];
        }
        $this->salida .= "</select>";
        $this->salida .= "</td>";
        $this->salida .= "</tr>";
        $this->salida .= "</table>";
        $this->salida .='</form>';
        $this->ReturnMetodoExterno('app', 'Agenda', 'user', 'CalendarioEstandard');
        $this->salida .= "   </td></tr>";
        $this->salida .= "<tr class=\"modulo_table_list_title\">";
        $this->salida .= "<td>";
        $this->salida .= "</td>";
        $this->salida .= "</tr>";
        $this->salida .= "</table>";
        $this->salida .= "           </td>";
        $this->salida .= "        </tr>";
        $this->salida .= "    </table>";
        //mensaje
        $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "    </table>";

        if (!empty($arr) AND $sw == 1) {
            $this->salida .= "         <br><table width=\"80%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\">";

            $this->salida .= "            <tr align=\"center\" class=\"modulo_table_list_title\">";
            $this->salida .= "                <td width=\"10%\">IDENTIFICACION</td>";
            $this->salida .= "                <td width=\"10%\">FECHA</td>";
            $this->salida .= "                <td width=\"30%\">PACIENTE</td>";
            //si es de urgencias no muestra esta columna porque sonv arios
            if (!empty($_SESSION['CENTRAL']['TIPO_CONSULTA'])) {
                $this->salida .= "                <td width=\"25%\">PROFESIONAL</td>";
            }
            $this->salida .= "                <td width=\"12%\"></td>";
            $this->salida .= "            </tr>";
            for ($i = 0; $i < sizeof($arr); $i++) {

                //si el estado es-> 0 es por q la evolucion esta cerrada
                //de lo contrario es q esta abierta.
                if ($arr[$i][estado] == 0) {
                    //realizamos un conteo de las solicitudes ya sea por formula
                    //medica,solicitud de apoyos,incapacidad,ordenes de servicio....
                    $total = $this->Revisarsolicitudes($arr[$i][evolucion_id]);
                }

                if (!empty($arr[$i][ingreso])) {
                    $total = $this->RevisarsolicitudesAmb($arr[$i][ingreso]);
                }

                if ($i % 2) {
                    $estilo = 'modulo_list_claro';
                } else {
                    $estilo = 'modulo_list_oscuro';
                }
                $this->salida .= "            <tr class=\"$estilo\">";
                $this->salida .= "                <td align=\"center\">" . $arr[$i][tipo_id_paciente] . " " . $arr[$i][paciente_id] . "</td>";
                $this->salida .= "                <td>" . $arr[$i][fechacom] . "</td>";
                $this->salida .= "                <td>" . $arr[$i][nombre_paciente] . "</td>";
                if (!empty($_SESSION['CENTRAL']['TIPO_CONSULTA'])) {
                    $this->salida .= "                <td>" . $arr[$i][nombre] . "</td>";
                }
                //$accion=ModuloGetURL('app','CentroAutorizacion','user','DetalleOS',array('tipoid'=>$arr[$i][tipo_id_paciente],'pacienteid'=>$arr[$i][paciente_id]));
                $accion = ModuloGetURL('app', 'Central_de_Autorizaciones', 'user', 'ListadoPacientesEvolucionCerrada', array('hora' => $arr[$i][fechacom], 'ruta' => 'ruta', 'nom' => $arr[$i][nombre_paciente], 'evolucion' => $arr[$i][evolucion_id], 'tipoid' => $arr[$i][tipo_id_paciente], 'paciente_id' => $arr[$i][paciente_id], 'plan' => $arr[$i][plan_id], 'ingreso' => $arr[$i][ingreso]));

                if ($total > 0) {
                    $img = 'revision1.png';
                } else {
                    $img = 'pplan.png';
                }
                if ($arr[$i][estado] == 0) {
                    //$this->salida .= "                <td align=\"center\"><a href=\"$accion\"><img src=\"".GetThemePath()."/images/$img\"  border=\"0\">&nbsp;&nbsp;VER</a></td>";
                    $this->salida .= "                <td align=\"center\"><a href=\"$accion\">VER</a></td>";
                } else {
                    $this->salida .= "                <td align=\"center\"><label class='label_mark'>Evolucion Abierta</label></td>";
                }

                $this->salida .= "            </tr>";
            }
            $this->salida .= "    </table>";
            $this->conteo = $_SESSION['SPY2'];
            $this->salida .=$this->RetornarBarra();
        } else {
            $datos = $arr;
            if (!empty($datos) AND $sw == 2) {
                $this->salida .= "         <br><table width=\"80%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\">";
                $this->salida .= "            <tr align=\"center\" class=\"modulo_table_list_title\">";
                $this->salida .= "                <td width=\"35%\">MEDICO</td>";
                $this->salida .= "                <td width=\"25%\">CONSULTORIO</td>";
                $this->salida .= "                <td width=\"25%\">UBICACION</td>";
                $this->salida .= "            </tr>";
                for ($i = 0; $i < sizeof($datos); $i++) {
                    if ($i % 2) {
                        $estilo = 'modulo_list_claro';
                    } else {
                        $estilo = 'modulo_list_oscuro';
                    }
                    $this->salida .= "            <tr class=\"$estilo\">";
                    $accion = ModuloGetURL('app', 'Central_de_Autorizaciones', 'user', 'Listado', array('tipoid_profesional' => $datos[$i][tipo_id_profesional], 'profesional_id' => $datos[$i][profesional_id]));
                    $this->salida .= "                <td align=\"center\"> <a href=$accion>" . $datos[$i][nombre] . "</a></td>";
                    $this->salida .= "                <td>" . $datos[$i][descripcion] . "</td>";
                    $this->salida .= "                <td>" . $datos[$i][tipo_consultorio] . "</td>";
                    //    $this->salida .= "                <td align=\"center\"><a href=\"$accion\"><img src=\"".GetThemePath()."/images/flecha.png\" border=\"0\">&nbsp;&nbsp;VER</a></td>";
                    $this->salida .= "            </tr>";
                }

                $this->salida .= "    </table>";
                //$this->conteo=$_SESSION['SPY2'];
                //$this->salida .=$this->RetornarBarra();
            }
        }
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    function Listado() {
        if (!$_SESSION['CENTRAL']['PROFESIONAL']) {
            $_SESSION['CENTRAL']['PROFESIONAL']['tipoid'] = $_REQUEST['tipoid_profesional'];
            $_SESSION['CENTRAL']['PROFESIONAL']['profesional_id'] = $_REQUEST['profesional_id'];
        }
        unset($_SESSION['CENTRAL']['PACIENTE']);
        unset($_SESSION['CENTRAL']['ARR_SOLICITUDES']);
        $this->salida.= ThemeAbrirTabla('LISTADO ATENCION DE PACIENTES');
        $this->Encabezado();
        $datos = $this->ListadoCitasAtender();
        $accion = ModuloGetURL('app', 'Central_de_Autorizaciones', 'user', 'BuscarOrdenes');
        $this->SetJavaScripts('DatosPaciente');
        if (!empty($datos)) {
            $this->salida .= "         <br><table width=\"80%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\">";
            $this->salida .= "            <tr align=\"center\" class=\"modulo_table_list_title\">";
            $this->salida .= "                <td width=\"10%\">IDENTIFICACION</td>";
            $this->salida .= "                <td width=\"15%\">FECHA</td>";
            $this->salida .= "                <td width=\"50%\">PACIENTE</td>";
            $this->salida .= "                <td width=\"15%\"></td>";
            $this->salida .= "            </tr>";
            for ($i = 0; $i < sizeof($datos); $i++) {
                if ($i % 2) {
                    $estilo = 'modulo_list_claro';
                } else {
                    $estilo = 'modulo_list_oscuro';
                }
                $this->salida .= "            <tr class=\"$estilo\">";
                $this->salida .= "                <td>" . $datos[$i][tipo_id_paciente] . "&nbsp;" . $datos[$i][paciente_id] . "</td>";
                $this->salida .= "                <td>" . $datos[$i][fechacom] . "</td>";
                $dato = RetornarWinOpenDatosPaciente($datos[$i][tipo_id_paciente], $datos[$i][paciente_id], $datos[$i][nombre_completo]);
                $this->salida .= "                <td align=\"center\">" . $dato . "</td>";
                $accion = ModuloGetURL('app', 'Central_de_Autorizaciones', 'user', 'ListadoPacientesEvolucionCerrada', array('hora' => $datos[$i][hora], 'nom' => $datos[$i][nombre_completo], 'evolucion' => $datos[$i][evolucion_id], 'tipoid' => $datos[$i][tipo_id_paciente], 'paciente_id' => $datos[$i][paciente_id], 'plan' => $datos[$i][plan_id]));
                if ($datos[$i][estado] == '0') {
                    $this->salida .= "                <td align=\"center\"><a href=\"$accion\"><img src=\"" . GetThemePath() . "/images/flecha.png\" border=\"0\">&nbsp;&nbsp;VER1</a></td>";
                } else {
                    $this->salida .= "                <td align=\"center\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class='label_mark'>Evolucion Abierta</label></td>";
                }
                $this->salida .= "            </tr>";
            }

            $this->salida .= "    </table>";
        }

        $this->salida.="<br><table align=\"center\" width='40%' border=\"0\">";
        $action2 = ModuloGetURL('app', 'Central_de_Autorizaciones', 'user', 'FormaMetodoBuscar');
        $this->salida .= "           <form name=\"formados\" action=\"$action2\" method=\"post\">";
        $this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></form></td>";
        $this->salida .= "</tr>";
        $this->salida.="</table><br>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /*
     * Funcion donde se visualiza el encabezado de la empresa.
     * @return boolean
     */

    function EncabezadoPac() {
        $this->salida .= "<br><table  class=\"modulo_table_list_title\" border=\"0\"  width=\"80%\" align=\"center\" >";
        $this->salida .= " <tr class=\"modulo_table_list_title\">";
        $this->salida .= " <td  width=\"18%\">IDENTIFICACION</td>";
        $this->salida .= " <td  width=\"25%\">HORA</td>";
        $this->salida .= " <td colspan=2>PACIENTE</td>";
        $this->salida .= " </tr>";
        $this->salida .= " <tr align=\"center\">";
        $this->salida .= " <td class=\"modulo_list_claro\" >" . $_SESSION['CENTRAL']['PACIENTE']['tipo_id'] . "&nbsp;" . $_SESSION['CENTRAL']['PACIENTE']['paciente_id'] . "</td>";
        $this->salida .= " <td class=\"modulo_list_claro\">" . $_SESSION['CENTRAL']['PACIENTE']['hora'] . "</td>";
        $this->salida .= " <td class=\"modulo_list_claro\" colspan=2>" . $_SESSION['CENTRAL']['PACIENTE']['nom'] . "</td>";
        $this->salida .= " </tr>";
        $var = $this->DatosPlan($_SESSION['CENTRAL']['PACIENTE']['plan']);

        $this->salida .= " <tr align=\"center\">";
        $this->salida .= " <td class=\"modulo_table_list_title\">RESPONSABLE: </td>";
        $this->salida .= " <td class=\"modulo_list_claro\">" . $var[nombre_tercero] . "</td>";
        $this->salida .= " <td class=\"modulo_table_list_title\"  width=\"8%\">PLAN: </td>";
        $this->salida .= " <td class=\"modulo_list_claro\" >" . $var[plan_descripcion] . "</td>";
        $this->salida .= " </tr>";

        $this->salida .= " </table>";
        return true;
    }

    function ListadoPacientesEvolucionCerrada($datos='', $control='') {
        IncludeLib("funciones_central_impresion");
        if (!empty($datos)) {
            if ($control == 1) {
                $RUTA = $_ROOT . "cache/incapacidad_medica" . UserGetUID() . ".pdf";
            } else if ($control == 2) {
                $RUTA = $_ROOT . "cache/solicitudes" . UserGetUID() . ".pdf";
            } else if ($control == 3) {
                $RUTA = $_ROOT . "cache/ordenservicio" . $datos['orden'] . ".pdf";
            } else {
                $RUTA = $_ROOT . "cache/formula_medica_amb" . UserGetUID() . ".pdf";
            }
            $DIR = "printer.php?ruta=$RUTA";
            $RUTA1 = GetBaseURL() . $DIR;
            $mostrar = "\n<script language='javascript'>\n";
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

        $reporte = new GetReports();
        if (!$_SESSION['CENTRAL']['PACIENTE']) {
            $_SESSION['CENTRAL']['PACIENTE']['tipo_id'] = $_REQUEST['tipoid'];
            $_SESSION['CENTRAL']['PACIENTE']['paciente_id'] = $_REQUEST['paciente_id'];
            $_SESSION['CENTRAL']['PACIENTE']['evolucion_id'] = $_REQUEST['evolucion'];
            $_SESSION['CENTRAL']['PACIENTE']['ingreso'] = $_REQUEST['ingreso'];
            $_SESSION['CENTRAL']['PACIENTE']['nom'] = $_REQUEST['nom'];
            $_SESSION['CENTRAL']['PACIENTE']['hora'] = $_REQUEST['hora'];
            $_SESSION['CENTRAL']['PACIENTE']['plan'] = $_REQUEST['plan'];
        }
        if($_REQUEST['ruta'] == 'ruta'){
            $_SESSION['CENTRAL']['RUTA'] = 1;
        }
        
        $this->salida.= ThemeAbrirTabla('LISTADO ATENCION DE PACIENTES');
        $this->Encabezado('1', &$reporte);
        $this->EncabezadoPac();
        $accion = ModuloGetURL('app', 'Central_de_Autorizaciones', 'user', 'BuscarOrdenes');
        $this->SetJavaScripts('DatosPaciente');
        unset($_SESSION['CENTRAL']['DATOS']);
        if (empty($_SESSION['CENTRAL']['DATOS'])) {
            $_SESSION['CENTRAL']['DATOS'] = $this->EncabezadoReporte();
        }

        //claudia

        $plt = AutoCarga::factory("PlanTerapeutico", "classes", "app", "ImpresionHC");
        $planTerapeutico = $plt->ObtenerPlanTerapeutico($_SESSION['CENTRAL']['PACIENTE']['ingreso']);

        if (!empty($planTerapeutico)) {
            $this->FormaPlanTerapeutico($_SESSION['CENTRAL']['PACIENTE']['ingreso'], array('tipo_id_paciente' => $_SESSION['CENTRAL']['PACIENTE']['tipo_id'], 'paciente_id' => $_SESSION['CENTRAL']['PACIENTE']['paciente_id']), $reporte);
        }

        $frm = AutoCarga::factory('FormulaOptometriaSQL', 'classes', 'hc1', 'Optometria');
        $formula_optometria = $frm->ObtenerDatosFormulaOptometriaActual($_SESSION['CENTRAL']['PACIENTE']['evolucion_id']);

        if (!empty($formula_optometria)) {
            $this->FormaFormulaOptometria($_SESSION['CENTRAL']['PACIENTE']['evolucion_id'], $reporte);
        }

        if (!empty($_SESSION['CENTRAL']['TIPO_CONSULTA'])) {  //medicamentos de coanulta
            $vector1 = GetMedicamentosAmb($_SESSION['CENTRAL']['PACIENTE']['evolucion_id'], $_SESSION['CENTRO']['SW_FARMACIA']);
        } else {  //medicamentos urgencias
            $vector1 = GetMedicamentosHospitalariosAmbulatorios($_SESSION['CENTRAL']['PACIENTE']['ingreso'], $_SESSION['CENTRO']['SW_FARMACIA']);
        }
        if ($vector1) {
            $this->FrmMedicamentos($vector1, &$reporte);
        }
        //fin claudia

        if ($_SESSION['CENTRO']['SW_FARMACIA'] == '0') {
            if (!empty($_SESSION['CENTRAL']['TIPO_CONSULTA'])) {  //solicitudes de consulta
                $arr = BuscarSolicitudesEvolucion($_SESSION['CENTRAL']['PACIENTE']['evolucion_id']);
            } else {  //solicitudes de la urgencia	
                //$arr=BuscarSolicitudesIngreso($_SESSION['CENTRAL']['PACIENTE']['ingreso']);
                $arr = BuscarSolicitudesHospitalariasAmbulatorias($_SESSION['CENTRAL']['PACIENTE']['ingreso']);
            }
            if (!empty($arr)) {
                $this->FormaSolicitudes($arr, &$reporte);
            }

            $var = '';
            if (!empty($_SESSION['CENTRAL']['TIPO_CONSULTA'])) {  //os de consulta
                $var = BuscarOrdenesSEvolucion($_SESSION['CENTRAL']['PACIENTE']['evolucion_id']);
            } else {  //os de la urgencia	
                //$var=BuscarOrdenesIngreso($_SESSION['CENTRAL']['PACIENTE']['ingreso']);
                $var = BuscarOrdenesHospitalariasAmbulatorias($_SESSION['CENTRAL']['PACIENTE']['ingreso']);
            }
            if (!empty($var)) {
                $this->FormaOrdenes($var, &$reporte);
            }

            //claudia
            if (!empty($_SESSION['CENTRAL']['TIPO_CONSULTA'])) {  //incapacidad de consulta
                $vec = Consulta_Incapacidades_GeneradasEvolucion($_SESSION['CENTRAL']['PACIENTE']['evolucion_id']);
            } else {  //incapacidad urgencias
                $vec = Consulta_Incapacidades_GeneradasIngreso($_SESSION['CENTRAL']['PACIENTE']['ingreso']);
            }
            //$vec=Consulta_Incapacidades_GeneradasEvolucion($_SESSION['CENTRAL']['PACIENTE']['evolucion_id']);

            if (!empty($vec)) {
                $this->FrmIncapacidad($vec, &$reporte);
            }
            //fin claudia
        }
        unset($reporte);
        if (!$vector1 AND !$arr AND !$var AND !$vec) {
            $this->salida.="<br><br><table align=\"center\" width='80%' border=\"0\">";
            $this->salida.="  <TR><td align=\"center\" width=\"9%\"><label class='label_mark'>EL PACIENTE NO TIENE NINGUNA SOLICITUD</label></td><TR>";
            $this->salida.="</table>";
        }
        $this->salida .= "</form>";

        $this->salida.="<br><table align=\"center\" width='40%' border=\"0\">";
        if ($_SESSION['CENTRAL']['RUTA']) {
            $action2 = ModuloGetURL('app', 'Central_de_Autorizaciones', 'user', 'FormaMetodoBuscar');
        } else {
            $action2 = ModuloGetURL('app', 'Central_de_Autorizaciones', 'user', 'Listado');
        }
        $this->salida .= "           <form name=\"formados\" action=\"$action2\" method=\"post\">";
        $this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></form></td>";
        $this->salida .= "</tr>";
        $this->salida.="</table><br>";

        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //dar
    function FormaSolicitudes($arr, $reporte) {
        unset($_SESSION['CENTRAL']['ARR_SOLICITUDES']);
        IncludeLib("malla_validadora");
        $this->salida .= "         <br><table width=\"80%\" border=\"0\" align=\"center\">";
        $this->salida .= "            <tr class=\"modulo_table_title\"><td colspan=\"5\" align=\"center\">SOLICITUDES</td></tr>";
        for ($i = 0; $i < sizeof($arr);) {
            $d = $i;

            $tipsol = "";
            $obser = $this->ObtenerTipoSolicitud($arr[$d][hc_os_solicitud_id]);
            if (count($obser) > 0) {
                $tipsol = $obser[0][os_tipo_solicitud_id];
            }



            if ($arr[$i][plan_id] == $arr[$d][plan_id] AND $arr[$i][servicio] == $arr[$d][servicio]) {
                $this->salida .= "            <tr><td colspan=\"5\" class=\"modulo_table_title\">PLAN:" . $arr[$i][plan_descripcion] . "</td></tr>";
                $this->salida .= "            <tr>";
                $this->salida .= "                <td class=\"modulo_table_title\" width=\"12%\">SERVICIO: </td>";
                $this->salida .= "                <td class=\"modulo_list_claro\" width=\"13%\">" . $arr[$i][desserv] . "</td>";
                $this->salida .= "                <td class=\"modulo_table_title\" width=\"11%\">DEPARTAMENTO: </td>";
                $this->salida .= "                <td class=\"modulo_list_claro\" align=\"left\" colspan=\"2\">" . $arr[$i][despto] . "</td>";
                $this->salida .= "            </tr>";
                $this->salida .= "            <tr class=\"modulo_table_title\">";
                $this->salida .= "                <td>FECHA</td>";
                $this->salida .= "                <td>CARGO</td>";
                $this->salida .= "                <td colspan=\"2\" width=\"50%\">DESCRIPCION</td>";
                $this->salida .= "                <td width=\"10%\">TIPO</td>";
                //$this->salida .= "                <td width=\"11%\">JUSTIF.</td>";
                $this->salida .= "            </tr>";
            }

            while ($arr[$i][plan_id] == $arr[$d][plan_id] AND $arr[$i][servicio] == $arr[$d][servicio]) {

                $tabla = "";
                if ($tipsol == 'APD') {
                    $tabla = "hc_os_solicitudes_apoyod";
                } elseif ($tipsol == 'QX') {
                    $tabla = "hc_os_solicitudes_acto_qx";
                } elseif ($tipsol == 'PNQ') {
                    $tabla = "hc_os_solicitudes_no_quirurgicos";
                } elseif ($tipsol == 'INT') {
                    $tabla = "hc_os_solicitudes_interconsultas";
                }
                $cadobse = "";
                if (!empty($tabla)) {
                    $obser = $this->ObtenerObservacionSolicitud($arr[$d][hc_os_solicitud_id], $tabla);
                    if (count($obser) > 0)
                        $cadobse = $obser[0][observacion];
                }

                if ($d % 2) {
                    $estilo = "modulo_list_claro";
                } else {
                    $estilo = "modulo_list_oscuro";
                }
                $this->salida .= "            <tr class=\"$estilo\">";
                $this->salida .= "                <td>" . $this->FechaStamp($arr[$i][fecha]) . " " . $this->HoraStamp($arr[$i][fecha]) . "</td>";
                $this->salida .= "                <td align=\"center\">" . $arr[$d][cargos] . "</td>";
                $this->salida .= "                <td colspan=\"2\">" . $arr[$d][descar] . "</td>";
                $this->salida .= "                <td align=\"center\">" . $arr[$d][desos] . "</td>";
                $this->salida .= "            </tr>";

                $this->salida .= "            <tr>";
                $this->salida .= "                <td width=\"5%\" class=\"modulo_table_title\">OBSERVACIONES: </td>";
                $this->salida .= "                <td width=\"5%\" colspan=\"7\" class=\"hc_table_submodulo_list_title\" align=\"left\">" . $cadobse . "</td>";
                $this->salida .= "            </tr>";

                $this->salida .= "            <tr class=\"$estilo\">";
                $this->salida .= "                <td width=\"11%\" class=\"modulo_table_title\" >JUSTIFICACION:</td>";
                $x = MallaValidadoraValidarCargo($arr[$d][cargos], $arr[$d][plan_id], $arr[$d][servicio], $arr[$d][hc_os_solicitud_id], $arr[$d][cantidad]);
                if (is_array($x)) {
                    $this->salida .= "                <td align=\"center\" colspan=\"4\">CARGO VALIDADO POR LA MALLA</td>";
                } else {
                    $this->salida .= "                <td align=\"center\" colspan=\"4\">$x</td>";
                }
                $this->salida .= "            </tr>";
                $d++;
            }
            $i = $d;
        }

        //Variable de session que contiene el arreglo de las solicitudes para cuando se vayan a imprimir
        $_SESSION['CENTRAL']['ARR_SOLICITUDES'] = $arr;
        $go_to_url = ModuloGetURL('app', 'Central_de_Autorizaciones', 'user', 'Reportesolicitudes', array('pos' => 1));
        $this->salida .= "                <tr><td class=$estilo colspan=\"3\" align=\"center\" width=\"7%\"><img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0' title=\"IMPRIMIR POS\">&nbsp;<a href=\"$go_to_url\"> IMPRIMIR POS</a></td>";
        $go_to_url = ModuloGetURL('app', 'Central_de_Autorizaciones', 'user', 'Reportesolicitudes', array('pos' => 0, 'TipoDocumento' => $_SESSION['CENTRAL']['PACIENTE']['tipo_id'], 'Documento' => $_SESSION['CENTRAL']['PACIENTE']['paciente_id'], 'Nombres' => $_SESSION['CENTRAL']['PACIENTE']['nom'], 'evolucion' => $_SESSION['CENTRAL']['PACIENTE']['evolucion_id']));
        $this->salida .= "                <td class=$estilo colspan=\"1\" align=\"center\" width=\"25%\"><img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0' title=\"IMPRIMIR MEDIA CARTA\">&nbsp;<a href=\"$go_to_url\"> IMPRIMIR MEDIA CARTA</a></td>";

        //$reporte= new GetReports();
        $mostrar = $reporte->GetJavaReport('app', 'CentralImpresionHospitalizacion', 'solicitudesHTM', array('TipoDocumento' => $_SESSION['CENTRAL']['PACIENTE']['tipo_id'], 'Documento' => $_SESSION['CENTRAL']['PACIENTE']['paciente_id'], 'Nombres' => $_SESSION['CENTRAL']['PACIENTE']['nom'], 'evolucion' => $_SESSION['CENTRAL']['PACIENTE']['evolucion_id']), array('rpt_name' => 'salida', 'rpt_dir' => 'cache', 'rpt_rewrite' => TRUE));
        $funcion = $reporte->GetJavaFunction();
        $this->salida .=$mostrar;
        $this->salida .= "                <td class=$estilo colspan=\"1\" align=\"center\" width=\"7%\"><img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0' title=\"IMPRIMIR\">&nbsp;<a href=\"javascript:$funcion\"> IMPRIMIR</a></td></tr>";
        $this->salida .= " </table>";
    }

    //dar
    function FormaOrdenes($var, $reporte) {
        $this->salida .= "    <br><table width=\"80%\" border=\"0\" align=\"center\" >";
        $this->salida .= "            <tr class=\"modulo_table_title\">";
        $this->salida .= "                <td colspan=\"5\" align=\"CENTER\">ORDENES</td>";
        $this->salida .= "            </tr>";
        $this->salida .= "             </table>";

        for ($i = 0; $i < sizeof($var);) {
            $d = $i;
            $tipsol = "";
            $obser = $this->ObtenerTipoSolicitud($var[$d][hc_os_solicitud_id]);
            if (count($obser) > 0) {
                $tipsol = $obser[0][os_tipo_solicitud_id];
            }

            $tabla = "";
            if ($tipsol == 'APD') {
                $tabla = "hc_os_solicitudes_apoyod";
            } elseif ($tipsol == 'QX') {
                $tabla = "hc_os_solicitudes_acto_qx";
            } elseif ($tipsol == 'PNQ') {
                $tabla = "hc_os_solicitudes_no_quirurgicos";
            } elseif ($tipsol == 'INT') {
                $tabla = "hc_os_solicitudes_interconsultas";
            }
            $cadobse = "";
            if (!empty($tabla)) {
                $obser = $this->ObtenerObservacionSolicitud($var[$d][hc_os_solicitud_id], $tabla);
                if (count($obser) > 0)
                    $cadobse = $obser[0][observacion];
            }

            $this->salida .= "    <table width=\"80%\" border=\"1\" align=\"center\" >";
            $this->salida .= "            <tr class=\"modulo_table_title\">";
            $this->salida .= "                <td colspan=\"5\" align=\"left\">NUMERO DE ORDEN DE SERVICIO: " . $var[$i][orden_servicio_id] . "</td>";
            $this->salida .= "            </tr>";
            $this->salida .= "            <tr>";
            $this->salida .= "                <td colspan=\"5\" class=\"modulo_list_claro\">";
            $this->salida .= "                        <table width=\"100%\" border=\"1\" align=\"center\" class=\"\">";
            $this->salida .= "                                <tr>";
            $this->salida .= "                                        <td width=\"5%\" class=\"modulo_table_title\">TIPO AFILIADO: </td>";
            $this->salida .= "                                        <td width=\"5%\" class=\"hc_table_submodulo_list_title\">" . $var[$d][tipo_afiliado_nombre] . "</td>";
            $this->salida .= "                                        <td width=\"5%\" class=\"modulo_table_title\">RANGO: </td>";
            $this->salida .= "                                        <td width=\"5%\" class=\"hc_table_submodulo_list_title\">" . $var[$d][rango] . "</td>";
            $this->salida .= "                                        <td width=\"5%\" class=\"modulo_table_title\">SEMANAS COT.: </td>";
            $this->salida .= "                                        <td width=\"5%\" class=\"hc_table_submodulo_list_title\">" . $var[$d][semanas_cotizadas] . "</td>";
            $this->salida .= "                                        <td width=\"5%\" class=\"modulo_table_title\">SERVICIO: </td>";
            $this->salida .= "                                        <td width=\"5%\" class=\"hc_table_submodulo_list_title\">" . $var[$d][desserv] . "</td>";
            $this->salida .= "                                </tr>";
            $this->salida .= "                                <tr>";
            $this->salida .= "                                        <td width=\"5%\" class=\"modulo_table_title\">AUT. INT.: </td>";
            $this->salida .= "                                        <td width=\"5%\" class=\"hc_table_submodulo_list_title\">" . $var[$d][autorizacion_int] . "</td>";
            $this->salida .= "                                        <td width=\"5%\" class=\"modulo_table_title\">AUT. EXT: </td>";
            $this->salida .= "                                        <td width=\"5%\" class=\"hc_table_submodulo_list_title\">" . $var[$d][autorizacion_ext] . "</td>";
            $this->salida .= "                                        <td width=\"5%\" class=\"modulo_table_title\">AUTORIZADOR: </td>";
            $dat = $this->BuscarAutorizador($var[$d][autorizacion_int], $var[$d][autorizacion_ext]);
            $this->salida .= "                                        <td width=\"5%\" colspan=\"3\" class=\"hc_table_submodulo_list_title\">" . $dat . "</td>";
            $this->salida .= "                                </tr>";
            $this->salida .= "                                <tr>";
            $this->salida .= "                                        <td width=\"5%\" class=\"modulo_table_title\">PLAN: </td>";
            $this->salida .= "                                        <td width=\"5%\" class=\"hc_table_submodulo_list_title\" colspan=\"7\" align=\"left\">" . $var[$d][plan_descripcion] . "</td>";
            $this->salida .= "                                </tr>";
            $this->salida .= "                                <tr>";
            $this->salida .= "                                        <td width=\"5%\" class=\"modulo_table_title\">OBSERVACIONES: </td>";
            $this->salida .= "                                        <td width=\"5%\" colspan=\"7\" class=\"hc_table_submodulo_list_title\" align=\"left\">" . $cadobse . "</td>";
            $this->salida .= "                                </tr>";
            $this->salida .= "                         </table>";
            $this->salida .= "                </td>";
            $this->salida .= "            </tr>";
            
            while ($var[$i][orden_servicio_id] == $var[$d][orden_servicio_id]) {
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
                if ($d % 2) {
                    $estilo = "modulo_list_claro";
                } else {
                    $estilo = "modulo_list_oscuro";
                }
                $this->salida .= "            <tr class=\"$estilo\">";
                $this->salida .= "                <td align=\"center\">" . $var[$d][numero_orden_id] . "</td>";
                $this->salida .= "                <td align=\"center\">" . $var[$d][cantidad] . "</td>";
                if (!empty($var[$d][cargo])) {
                    $cargo = $var[$d][cargo];
                } else {
                    $cargo = $var[$d][cargoext];
                }
                $this->salida .= "                <td align=\"center\">" . $cargo . "</td>";
                $this->salida .= "                <td>" . $var[$d][descripcion] . "</td>";
                $p = '';
                if (!empty($var[$d][departamento])) {
                    $p = 'DPTO. ' . $var[$d][desdpto];
                    $id = $var[$d][departamento];
                    $tipo = 'i';
                } else {
                    $p = $var[$d][planpro];
                    $id = $var[$d][plan_proveedor_id];
                    $tipo = 'e';
                }
                $this->salida .= "                <td align=\"center\">" . $p . "</td>";
                $this->salida .= "            </tr>";
                $this->salida .= "            <tr class=\"modulo_list_oscuro\">";
                $this->salida .= "                <td colspan=\"5\">";
                $this->salida .= "                        <table width=\"100%\" border=\"0\" align=\"center\">";
                $this->salida .= "                                <tr class=\"modulo_list_claro\">";
                $this->salida .= "                                        <td width=\"5%\" class=\"modulo_table_title\">ACTIVACION: </td>";
                $this->salida .= "                                        <td width=\"5%\" colspan=\"2\">" . $this->FechaStamp($var[$d][fecha_activacion]) . "</td>";
                $this->salida .= "                                        <td width=\"5%\" class=\"modulo_table_title\">VENC.: </td>";
                $x = '';

                $vecimiento = $var[$d][fecha_vencimiento];
                $arr_fecha = explode(" ", $vecimiento);

                if (strtotime(date("Y-m-d")) > strtotime($arr_fecha[0]))
                    $x = 'VENCIDA';
                $this->salida .= "                                        <td width=\"5%\" >" . $this->FechaStamp($var[$d][fecha_vencimiento]) . "</td>";
                $this->salida .= "                                        <td width=\"5%\" class=\"label_error\" align=\"center\">" . $x . "</td>";
                $this->salida .= "                                        <td width=\"5%\" class=\"modulo_table_title\">REFRENDAR HASTA: </td>";
                $this->salida .= "                                        <td width=\"5%\">" . $this->FechaStamp($var[$d][fecha_refrendar]) . "</td>";
                $this->salida .= "                                </tr>";
                $this->salida .= "                         </table>";
                $this->salida .= "        <table width=\"100%\" border=\"0\" align=\"center\">";
                $this->salida .= "            <tr class=\"modulo_list_claro\" align=\"center\">";
                $this->salida .= "                                        <td width=\"7%\" class=\"modulo_table_title\">ESTADO: </td>";
                $this->salida .= "                                        <td width=\"7%\" class=\"hc_table_submodulo_list_title\" colspan=\"2\">" . $var[$d][estado] . "</td>";
//                $this->salida .= "                <td width=\"15%\"></td>";
//                $accionP = ModuloGetURL('app', 'Central_de_Autorizaciones', 'user', 'FormaCambiarProveedor', array('tipoid' => $var[0][tipo_id_paciente], 'pacienteid' => $var[0][paciente_id], 'orden' => $var[$d][orden_servicio_id], 'numor' => $var[$d][numero_orden_id], 'proveedor' => $id, 'cargo' => $cargo, 'plan' => $var[$d][plan_id], 'tipop' => $tipo));
//                $this->salida .= "                <td width=\"7%\"><a href=\"$accionP\"><img src=\"" . GetThemePath() . "/images/proveedor.png\" border='0'>CAMBIAR PROVEEDOR</a></td>";
                $this->salida .= "            </tr>";
                $this->salida .= "             </table>";
                $this->salida .= "                </td>";
                $this->salida .= "            </tr>";
                $this->salida .= "             </table>";
                $this->salida .= "                </td>";
                $this->salida .= "            </tr>";
                $d++;
            }
            
            $this->salida .= "              <tr class=\"$estilo\">";
            $accion = ModuloGetURL('app', 'Central_de_Autorizaciones', 'user', 'ReporteOrdenServicio', array('orden' => $var[$i][orden_servicio_id], 'evolucion' => $var[$i][evolucion_id], 'plan' => $var[$i][plan_id], 'tipoid' => $var[$i][tipo_id_paciente], 'paciente' => $var[$i][paciente_id], 'afiliado' => $var[$i][tipo_afiliado_id], 'pos' => 1));
            
            $this->salida .= "                  <td align=\"center\" ><a href=\"$accion\"><img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0' title=\"IMPRIMIR POS\"> IMPRIMIR POS</a></td>";
            
/*            
            $this->salida .= "                  <td width=\"15%\"></td>";
            $accionP = ModuloGetURL('app', 'Central_de_Autorizaciones', 'user', 'FormaCambiarProveedor', array('tipoid' => $var[0][tipo_id_paciente], 'pacienteid' => $var[0][paciente_id], 'orden' => $var[$i][orden_servicio_id], 'numor' => $var[$i][numero_orden_id], 'proveedor' => $id, 'cargo' => $cargo, 'plan' => $var[$f][plan_id], 'tipop' => $tipo));
            $this->salida .= "                  <td width=\"7%\"><a href=\"$accionP\">CAMBIAR PROVEEDOR</a></td>";
*/            
            
            $accionP = ModuloGetURL('app', 'Central_de_Autorizaciones', 'user', 'FormaCambiarProveedor', array('tipoid' => $var[0][tipo_id_paciente], 'pacienteid' => $var[0][paciente_id], 'orden' => $var[0][orden_servicio_id], 'numor' => $var[0][numero_orden_id], 'proveedor' => $id, 'cargo' => $cargo, 'plan' => $var[0][plan_id], 'tipop' => $tipo));
            $this->salida .= "                <td width=\"7%\"><a href=\"$accionP\"><img src=\"" . GetThemePath() . "/images/proveedor.png\" border='0'>CAMBIAR PROVEEDOR</a></td>";
            
            $mostrar = $reporte->GetJavaReport('app', 'CentralImpresionHospitalizacion', 'ordenservicioHTM', array('orden' => $var[$i][orden_servicio_id]), array('rpt_name' => '', 'rpt_dir' => 'cache', 'rpt_rewrite' => TRUE));
            $funcion = $reporte->GetJavaFunction();
            $this->salida .=$mostrar;
            $this->salida.="                    <td align=\"center\" width=\"25%\"><a href=\"javascript:$funcion\"><img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0' title=\"IMPRIMIR\"> IMPRIMIR</a></td>";
            $accion = ModuloGetURL('app', 'Central_de_Autorizaciones', 'user', 'ReporteOrdenServicio', array('orden' => $var[$i][orden_servicio_id], 'evolucion' => $var[$i][evolucion_id], 'plan' => $var[$i][plan_id], 'tipoid' => $var[$i][tipo_id_paciente], 'paciente' => $var[$i][paciente_id], 'afiliado' => $var[$i][tipo_afiliado_id], 'pos' => 0));
            $this->salida .= "                  <td class=$estilo align=\"center\" width=\"25%\"><img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0' title=\"IMPRIMIR MEDIA CARTA\">&nbsp;<a href=\"$accion\"> IMPRIMIR MEDIA CARTA</a></td></tr>";
            
//JONIER                        
            $this->salida .= "            </tr>";
            $i = $d;
            $this->salida .= "             </table>";
        }//fin for
    }

    /**
     *
     */
    function FormaCambiarProveedor($tipo, $paciente, $orden, $num, $cargo, $proveedor, $tipop) {
        
        if (empty($tipo)) {
            $tipo = $_REQUEST['tipoid'];
            $paciente = $_REQUEST['pacienteid'];
            $orden = $_REQUEST['orden'];
            $num = $_REQUEST['numor'];
            $cargo = $_REQUEST['cargo'];
            $proveedor = $_REQUEST['proveedor'];
            $tipop = $_REQUEST['tipop'];
        }
        $Solicitud = $this->BuscarSolicitud($num);
        $this->salida .= ThemeAbrirTabla('CAMBIAR PROVEEDOR ORDEN DE SERVICIO');
        $this->Encabezado();
        $this->EncabezadoPac();
        $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "    </table>";
        $actionM = ModuloGetURL('app', 'Central_de_Autorizaciones', 'user', 'CambiarProveedor', array('act' => $proveedor, 'tipop' => $tipop));
        $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
        $this->salida .= "<br><table width=\"40%\" border=\"0\" align=\"center\">";
        $this->salida .= "       <input type=\"hidden\" name=\"cargo\" value=\"" . $cargo . "\"></td>";
        $this->salida .= "       <input type=\"hidden\" name=\"proveedor\" value=\"" . $proveedor . "\"></td>";
        $this->salida .= "       <input type=\"hidden\" name=\"tipoid\" value=\"" . $tipo . "\"></td>";
        $this->salida .= "       <input type=\"hidden\" name=\"pacienteid\" value=\"" . $paciente . "\"></td>";
        $this->salida .= "       <input type=\"hidden\" name=\"orden\" value=\"" . $orden . "\"></td>";
        $this->salida .= "       <input type=\"hidden\" name=\"numor\" value=\"" . $num . "\"></td>";
        $this->salida .= "<tr class=\"modulo_list_claro\">";
        $this->salida .= "                <td align=\"center\">PROVEEDOR:    </td>";
        $dpto = $this->ComboDepartamento($cargo, $Solicitud['hc_os_solicitud_id']);
        $pro = $this->ComboProveedor($cargo);
        $suma = 0;
        $suma = sizeof($pro) + sizeof($dpto);
        if (!empty($dpto) OR !empty($pro)) {
            $this->salida .= "                <td align=\"center\"><select name=\"Combo\" class=\"select\">";
            $this->salida .=" <option value=\"-1\">------SELECCIONE------</option>";

            //departamentos
            for ($j = 0; $j < sizeof($dpto); $j++) {
                if ($dpto[$j][departamento] == $proveedor) {
                    $this->salida .=" <option value=\"" . $dpto[$j][departamento] . ",dpto\" selected>" . $dpto[$j][descripcion] . "</option>";
                } else {
                    $this->salida .=" <option value=\"" . $dpto[$j][departamento] . ",dpto\">" . $dpto[$j][descripcion] . "</option>";
                }
            }
            //proveedores
            for ($j = 0; $j < sizeof($pro); $j++) {
                if ($proveedor == $pro[$j][plan_proveedor_id]) {
                    $this->salida .=" <option value=\"" . $pro[$j][tercero_id] . "," . $pro[$j][tipo_id_tercero] . "," . $pro[$j][plan_proveedor_id] . "," . $pro[$j][empresa_id] . "\" selected>" . $pro[$j][plan_descripcion] . "</option>";
                } else {
                    $this->salida .=" <option value=\"" . $pro[$j][tercero_id] . "," . $pro[$j][tipo_id_tercero] . "," . $pro[$j][plan_proveedor_id] . "," . $pro[$j][empresa_id] . "\">" . $pro[$j][plan_descripcion] . "</option>";
                }
            }

            $this->salida .= "              </select></td>";
        } else {
            $this->salida .= "                <td class=\"label_error\" align=\"center\">El Cargo No lo Presta Nigun Departamento o Proveedor</td>";
        }
        $this->salida .= "              </select></td>";
        $this->salida .= "</tr>";
        $this->salida .= "</table>";
        $this->salida .= "<table width=\"40%\" border=\"0\" align=\"center\">";
        $this->salida .= "<tr>";
        if ($suma > 1) {
            $this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CAMBIAR\"><br></td>";
        } else {
            $this->salida .= "<td align=\"center\" class=\"label_mark\"><br>SOLO HAY UN PROVEEDOR<br></td>";
        }
        $this->salida .= "</form>";
        $actionM = ModuloGetURL('app', 'Central_de_Autorizaciones', 'user', 'ListadoPacientesEvolucionCerrada');
        $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
        $this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CANCELAR\"><br></td>";
        $this->salida .= "</form>";
        $this->salida .= "</tr>";
        $this->salida .= "</table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
     * Separa la fecha del formato timestamp
     * @access private
     * @return string
     * @param date fecha
     */
    function FechaStamp($fecha) {
        if ($fecha) {
            $fech = strtok($fecha, "-");
            for ($l = 0; $l < 3; $l++) {
                $date[$l] = $fech;
                $fech = strtok("-");
            }
            return ceil($date[2]) . "/" . str_pad(ceil($date[1]), 2, 0, STR_PAD_LEFT) . "/" . str_pad(ceil($date[0]), 2, 0, STR_PAD_LEFT);
            //                    return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
        }
    }

    /**
     * Separa la hora del formato timestamp
     * @access private
     * @return string
     * @param date hora
     */
    function HoraStamp($hora) {
        $hor = strtok($hora, " ");
        for ($l = 0; $l < 4; $l++) {
            $time[$l] = $hor;
            $hor = strtok(":");
        }

        $x = explode(".", $time[3]);
        return $time[1] . ":" . $time[2] . ":" . $x[0];
    }

    /**
     *
     */
    function CalcularNumeroPasos($conteo) {
        $numpaso = ceil($conteo / $this->limit);
        return $numpaso;
    }

    function CalcularBarra($paso) {
        $barra = floor($paso / 10) * 10;
        if (($paso % 10) == 0) {
            $barra = $barra - 10;
        }
        return $barra;
    }

    function CalcularOffset($paso) {
        $offset = ($paso * $this->limit) - $this->limit;
        return $offset;
    }

    function RetornarBarra() {
        $this->conteo;
        $this->limit;

        if ($this->limit >= $this->conteo) {
            return '';
        }
        $paso = $_REQUEST['paso'];
        if (is_null($paso)) {
            $paso = 1;
        }
        $vec = '';
        foreach ($_REQUEST as $v => $v1) {
            if ($v != 'modulo' and $v != 'metodo' and $v != 'SIIS_SID' and $v != 'Of') {
                $vec[$v] = $v1;
            }
        }
        $accion = ModuloGetURL('app', 'Central_de_Autorizaciones', 'user', 'BuscarOrdenes', $vec);
        $barra = $this->CalcularBarra($paso);
        $numpasos = $this->CalcularNumeroPasos($this->conteo);
        $colspan = 1;

        $this->salida .= "<br><table border='1' align='center'  cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
        if ($paso > 1) {
            $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=" . $this->CalcularOffset(1) . "&paso=1'>&lt;</a></td>";
            $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=" . $this->CalcularOffset($paso - 1) . "&paso=" . ($paso - 1) . "'>&lt;&lt;</a></td>";
            $colspan+=1;
        } else {
            // $this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
            //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
        }
        $barra++;
        if (($barra + 10) <= $numpasos) {
            for ($i = ($barra); $i < ($barra + 10); $i++) {
                if ($paso == $i) {
                    $this->salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
                } else {
                    $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=" . $this->CalcularOffset($i) . "&paso=$i' >$i</a></td>";
                }
                $colspan++;
            }
            $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=" . $this->CalcularOffset($paso + 1) . "&paso=" . ($paso + 1) . "' >&gt;&gt;</a></td>";
            $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=" . $this->CalcularOffset($numpasos) . "&paso=$numpasos'>&gt;</a></td>";
            $colspan+=2;
        } else {
            $diferencia = $numpasos - 9;
            if ($diferencia <= 0) {
                $diferencia = 1;
            }//cambiar en todas las barra
            for ($i = ($diferencia); $i <= $numpasos; $i++) {
                if ($paso == $i) {
                    $this->salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
                } else {
                    $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=" . $this->CalcularOffset($i) . "&paso=$i' >$i</a></td>";
                }
                $colspan++;
            }
            if ($paso != $numpasos) {
                $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=" . $this->CalcularOffset($paso + 1) . "&paso=" . ($paso + 1) . "' >&gt;&gt;</a></td>";
                $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=" . $this->CalcularOffset($numpasos) . "&paso=$numpasos'>&gt;</a></td>";
                $colspan++;
            } else {
                // $this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
                //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
            }
        }
        if (($_REQUEST['Of']) == 0 OR ($paso == $numpasos)) {
            if ($numpasos > 10) {
                $valor = 10 + 3;
            } else {
                $valor = $numpasos + 3;
            }
            $this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=" . $valor . " align='center'>Página $paso de $numpasos</td><tr></table>";
        } else {
            if ($numpasos > 10) {
                $valor = 10 + 5;
            } else {
                $valor = $numpasos + 5;
            }
            $this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=" . $valor . " align='center'>Página $paso de $numpasos</td><tr></table>";
        }
    }

    /**
     * Funcion que Saca los años para el calendario a partir del año actual
     * @return array
     */
    function AnosAgenda($Seleccionado='False', $ano) {

        $anoActual = date("Y");
        $anoActual1 = $anoActual - 10;
        for ($i = 0; $i <= 20; $i++) {
            $vars[$i] = $anoActual1;
            $anoActual1 = $anoActual1 + 1;
        }
        switch ($Seleccionado) {
            case 'False': {
                    foreach ($vars as $value => $titulo) {
                        if ($titulo == $ano) {
                            $this->salida .=" <option value=\"$titulo\" selected>$titulo</option>";
                        } else {
                            $this->salida .=" <option value=\"$titulo\">$titulo</option>";
                        }
                    }
                    break;
                }case 'True': {
                    foreach ($vars as $value => $titulo) {
                        if ($titulo == $ano) {
                            $this->salida .=" <option value=\"$titulo\" selected>$titulo</option>";
                        } else {
                            $this->salida .=" <option value=\"$titulo\">$titulo</option>";
                        }
                    }
                    break;
                }
        }
    }

    function MesesAgenda($Seleccionado='False', $Año, $Defecto) {
        $anoActual = date("Y");
        $vars[1] = 'ENERO';
        $vars[2] = 'FEBRERO';
        $vars[3] = 'MARZO';
        $vars[4] = 'ABRIL';
        $vars[5] = 'MAYO';
        $vars[6] = 'JUNIO';
        $vars[7] = 'JULIO';
        $vars[8] = 'AGOSTO';
        $vars[9] = 'SEPTIEMBRE';
        $vars[10] = 'OCTUBRE';
        $vars[11] = 'NOVIEMBRE';
        $vars[12] = 'DICIEMBRE';
        //$mesActual=date("m");
        switch ($Seleccionado) {
            case 'False': {
                    if ($anoActual == $Año) {
                        foreach ($vars as $value => $titulo) {
                            if ($value >= $mesActual) {
                                if ($value == $Defecto) {
                                    $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
                                } else {
                                    $this->salida .=" <option value=\"$value\">$titulo</option>";
                                }
                            }
                        }
                    } else {
                        foreach ($vars as $value => $titulo) {
                            if ($value == $Defecto) {
                                $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
                            } else {
                                $this->salida .=" <option value=\"$value\">$titulo</option>";
                            }
                        }
                    }
                    break;
                }
            case 'True': {
                    if ($anoActual == $Año) {
                        foreach ($vars as $value => $titulo) {
                            if ($value >= $mesActual) {

                                if ($value == $Defecto) {
                                    $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
                                } else {
                                    $this->salida .=" <option value=\"$value\">$titulo</option>";
                                }
                            }
                        }
                    } else {
                        foreach ($vars as $value => $titulo) {
                            if ($value == $Defecto) {
                                $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
                            } else {
                                $this->salida .=" <option value=\"$value\">$titulo</option>";
                            }
                        }
                    }
                    break;
                }
        }
    }

//***********************FUNCIONES CLAUDIA

    function FrmIncapacidad($vector1, $rep) {
        $this->salida .= "<form name=\"formades\" action=\"$accion\" method=\"post\">";
        if ($vector1) {
            $this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"80%\">";
            $this->salida.="<tr class=\"modulo_table_title\">";
            $this->salida.="  <td align=\"center\" colspan=\"5\">INCAPACIDADES MEDICAS GENERADAS</td>";
            $this->salida.="</tr>";
            $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
            $this->salida.="  <td width=\"5%\">No. EVOLUCION</td>";
            $this->salida.="  <td width=\"45%\">OBSERVACION DE LA INCAPACIDAD</td>";
            $this->salida.="  <td width=\"10%\">TIPO DE INCAPACIDAD</td>";
            $this->salida.="  <td width=\"10%\">DIAS DE INCAPACIDAD</td>";
            $this->salida.="  <td width=\"10%\">FECHA DE EMISION</td>";
            $this->salida.="</tr>";
            for ($i = 0; $i < sizeof($vector1); $i++) {
                if ($i % 2) {
                    $estilo = 'modulo_list_claro';
                } else {
                    $estilo = 'modulo_list_oscuro';
                }
                $this->salida.="<tr class=\"$estilo\">";
                $this->salida.="  <td align=\"center\" width=\"5%\">" . $vector1[$i][evolucion_id] . "</td>";
                $this->salida.="  <td align=\"left\" width=\"45%\">" . $vector1[$i][observacion_incapacidad] . "</td>";
                $this->salida.="  <td align=\"center\" width=\"10%\">" . $vector1[$i][tipo_incapacidad_descripcion] . "</td>";
                $this->salida.="  <td align=\"center\" width=\"10%\">" . $vector1[$i][dias_de_incapacidad] . "</td>";
                $a = $this->FechaStamp($vector1[$i][fecha]);
                $b = $this->HoraStamp($vector1[$i][fecha]);
                $fecha = $a . ' - ' . $b;
                $this->salida.="  <td align=\"left\" width=\"10%\">" . $fecha . "</td>";
                $this->salida.="</tr>";
            }
            $this->salida.="<tr class=\"$estilo\">";

            //reporte en impresora pos
            $accion1 = ModuloGetURL('app', 'Central_de_Autorizaciones', 'user', 'ReporteIncapacidadMedica', array('impresion_pos' => '1', 'evolucion_id' => $vector1[0][evolucion_id]));
            $this->salida.="  <td colspan = 2 align=\"center\" width=\"35%\"><a href='$accion1'><img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0' title=\"IMPRIMIR\"> IMPRIMIR</a></td>";

            //reporte en pdf
            //alex
            $mostrar = $rep->GetJavaReport('app', 'CentralImpresionHospitalizacion', 'incapacidad_html', array('evolucion_id' => $_SESSION['CENTRAL']['PACIENTE']['evolucion_id']), array('rpt_name' => 'incapacidad_html', 'rpt_dir' => 'cache', 'rpt_rewrite' => TRUE));
            $nombre_funcion = $rep->GetJavaFunction();
            $this->salida .=$mostrar;
            $this->salida.="<td colspan = 2 align=\"center\" width=\"35%\"><a href=\"javascript:$nombre_funcion\"><img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0' title=\"IMPRIMIR PDF\"> IMPRIMIR PDF</a></td>";
            //fin de alex
            //reporte en impresora pdf caso sos
            $accion2 = ModuloGetURL('app', 'Central_de_Autorizaciones', 'user', 'ReporteIncapacidadMedica');
            $this->salida.="  <td align=\"center\" width=\"30%\"><a href='$accion2'><img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0' title=\"IMPRIMIR MEDIA CARTA\"> IMPRIMIR MEDIA CARTA3</a></td>";



            $this->salida.="</tr>";
            $this->salida.="</table><br>";
        }
        $this->salida .= "</form>";
        return true;
    }

    function FrmMedicamentos($vector1, $reporte) {
        $this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"80%\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida.="</table>";
        $espia = 1;
        $total_medicamentos_uso_controlado = 0;
        for ($i = 0; $i < sizeof($vector1); $i++) {
			$_SESSION['CENTRAL']['PACIENTE']['evolucion_id'] = $vector1[$i][evolucion_id];
            if ($vector1[$i][sw_uso_controlado] == 1) {
                $total_medicamentos_uso_controlado = $total_medicamentos_uso_controlado + 1;
            }
            if ($espia == 1) {
                $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
                $this->salida.="<tr class=\"modulo_table_title\">";

                if ($vector1[$i][item] == 'NO POS' AND $vector1[$i][sw_paciente_no_pos] == '0') {
                    $this->salida.="  <td align=\"center\" colspan=\"5\">MEDICAMENTOS NO POS JUSTIFICADOS</td>";
                } else {
                    if ($vector1[$i][item] == 'NO POS' AND $vector1[$i][sw_paciente_no_pos] == '1') {
                        $this->salida.="  <td align=\"center\" colspan=\"5\">MEDICAMENTOS NO POS SOLICITADOS A PETICION DEL PACIENTE</td>";
                    } else {
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
            if ($vector1[$i][item] == $vector1[$i + 1][item] AND $vector1[$i][sw_paciente_no_pos] == $vector1[$i + 1][sw_paciente_no_pos]) {
                $espia = 0;
            } else {
                $espia = 1;
            }

            if ($i % 2) {
                $estilo = 'modulo_list_claro';
            } else {
                $estilo = 'modulo_list_oscuro';
            }

            $this->salida.="<tr class=\"$estilo\">";
            if ($vector1[$i][item] == 'NO POS') {
                $this->salida.="  <td ROWSPAN = 3 align=\"center\" width=\"7%\">" . $vector1[$i][codigo_producto] . "<BR>NO_POS</td>";
            } else {
                $this->salida.="  <td ROWSPAN = 3 align=\"center\" width=\"7%\">" . $vector1[$i][codigo_producto] . "</td>";
            }
            $this->salida.="  <td align=\"center\" width=\"30%\">" . $vector1[$i][producto] . "</td>";
            $this->salida.="  <td colspan=\"3\" align=\"center\" width=\"43%\">" . $vector1[$i][principio_activo] . "</td>";
            $this->salida.="</tr>";


            $this->salida.="<tr class=\"$estilo\">";
            $this->salida.="<td colspan = 4>";
            $this->salida.="<table>";

            $this->salida.="<tr class=\"$estilo\">";
            $this->salida.="  <td colspan = 3 align=\"left\" width=\"9%\">Via de Administracion: " . $vector1[$i][via] . "</td>";
            $this->salida.="</tr>";

            $this->salida.="<tr class=\"$estilo\">";
            $this->salida.="  <td align=\"left\" width=\"9%\">Dosis:</td>";
            $e = $vector1[$i][dosis] / floor($vector1[$i][dosis]);
            if ($e == 1) {
                $this->salida.="  <td align=\"left\" width=\"14%\">" . floor($vector1[$i][dosis]) . "  " . $vector1[$i][unidad_dosificacion] . "</td>";
            } else {
                $this->salida.="  <td align=\"left\" width=\"14%\">" . $vector1[$i][dosis] . "  " . $vector1[$i][unidad_dosificacion] . "</td>";
            }

            //es consulta
            if (!empty($_SESSION['CENTRAL']['TIPO_CONSULTA'])) {
                $vector_posologia = Consulta_Solicitud_Medicamentos_Posologia($vector1[$i][codigo_producto], $vector1[$i][tipo_opcion_posologia_id], $vector1[$i][evolucion_id]);
            } else {
                $vector_posologia = Consulta_Solicitud_Medicamentos_Posologia_Hosp($vector1[$i][codigo_producto], $vector1[$i][tipo_opcion_posologia_id], $vector1[$i][evolucion_id]);
            }


//pintar formula para opcion 1
            if ($vector1[$i][tipo_opcion_posologia_id] == 1) {
                $this->salida.="  <td align=\"left\" width=\"50%\">cada " . $vector_posologia[0][periocidad_id] . " " . $vector_posologia[0][tiempo] . "</td>";
            }

//pintar formula para opcion 2
            if ($vector1[$i][tipo_opcion_posologia_id] == 2) {
                $this->salida.="  <td align=\"left\" width=\"50%\">" . $vector_posologia[0][descripcion] . "</td>";
            }

//pintar formula para opcion 3
            if ($vector1[$i][tipo_opcion_posologia_id] == 3) {
                $momento = '';
                if ($vector_posologia[0][sw_estado_momento] == '1') {
                    $momento = 'antes de ';
                } else {
                    if ($vector_posologia[0][sw_estado_momento] == '2') {
                        $momento = 'durante ';
                    } else {
                        if ($vector_posologia[0][sw_estado_momento] == '3') {
                            $momento = 'despues de ';
                        }
                    }
                }
                $Cen = $Alm = $Des = '';
                $cont = 0;
                $conector = '  ';
                $conector1 = '  ';
                if ($vector_posologia[0][sw_estado_desayuno] == '1') {
                    $Des = $momento . 'el Desayuno';
                    $cont++;
                }
                if ($vector_posologia[0][sw_estado_almuerzo] == '1') {
                    $Alm = $momento . 'el Almuerzo';
                    $cont++;
                }
                if ($vector_posologia[0][sw_estado_cena] == '1') {
                    $Cen = $momento . 'la Cena';
                    $cont++;
                }
                if ($cont == 2) {
                    $conector = ' y ';
                    $conector1 = '  ';
                }
                if ($cont == 1) {
                    $conector = '  ';
                    $conector1 = '  ';
                }
                if ($cont == 3) {
                    $conector = ' , ';
                    $conector1 = ' y ';
                }
                $this->salida.="  <td align=\"left\" width=\"50%\">" . $Des . "" . $conector . "" . $Alm . "" . $conector1 . "" . $Cen . "</td>";
            }

//pintar formula para opcion 4
            if ($vector1[$i][tipo_opcion_posologia_id] == 4) {
                $conector = '  ';
                $frecuencia = '';
                $j = 0;
                foreach ($vector_posologia as $k => $v) {
                    if ($j + 1 == sizeof($vector_posologia)) {
                        $conector = '  ';
                    } else {
                        if ($j + 2 == sizeof($vector_posologia)) {
                            $conector = ' y ';
                        } else {
                            $conector = ' - ';
                        }
                    }
                    $frecuencia = $frecuencia . $k . $conector;
                    $j++;
                }
                $this->salida.="  <td align=\"left\" width=\"50%\">a la(s): $frecuencia</td>";
            }

//pintar formula para opcion 5
            if ($vector1[$i][tipo_opcion_posologia_id] == 5) {
                $this->salida.="  <td align=\"left\" width=\"50%\">" . $vector_posologia[0][frecuencia_suministro] . "</td>";
            }
            $this->salida.="</tr>";

            $this->salida.="<tr class=\"$estilo\">";
            $this->salida.="  <td align=\"left\" width=\"9%\">Cantidad:</td>";
            $e = $vector1[$i][cantidad] / floor($vector1[$i][cantidad]);
            if ($vector1[$i][contenido_unidad_venta]) {
                if ($e == 1) {
                    $this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">" . floor($vector1[$i][cantidad]) . " " . $vector1[$i][descripcion] . " por " . $vector1[$i][contenido_unidad_venta] . "</td>";
                } else {
                    $this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">" . $vector1[$i][cantidad] . " " . $vector1[$i][descripcion] . " por " . $vector1[$i][contenido_unidad_venta] . "</td>";
                }
            } else {
                if ($e == 1) {
                    $this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">" . floor($vector1[$i][cantidad]) . " " . $vector1[$i][descripcion] . "</td>";
                } else {
                    $this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">" . $vector1[$i][cantidad] . " " . $vector1[$i][descripcion] . "</td>";
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
            $this->salida.="  <td align=\"left\" width=\"55%\">" . $vector1[$i][observacion] . "</td>";

            if ($vector1[$i][item] == 'NO POS' AND $vector1[$i][sw_paciente_no_pos] == '0') {
                //reporte en pdf de la justificacion
                $mostrar = $reporte->GetJavaReport('system', 'reportes', 'justificacion_nopos_med_html', array('codigo_producto' => $vector1[$i][codigo_producto], 'evolucion' => $_SESSION['CENTRAL']['PACIENTE']['evolucion_id'], 'invocado' => 1), array('rpt_name' => 'justificacion_nopos_med_html', 'rpt_dir' => 'cache', 'rpt_rewrite' => TRUE));
                $nombre_funcion = $reporte->GetJavaFunction();
                $this->salida .=$mostrar;
                $this->salida.="<td align=\"left\" width=\"14%\"><a href=\"javascript:$nombre_funcion\"><img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0' title=\"IMPRIMIR JUSTIFICACION\">JUSTIFICACION</a></td>";
            }
            $this->salida.="<tr class=\"$estilo\">";


            if ($vector1[$i][sw_uso_controlado] == 1) {
                $this->salida.="<tr class=\"$estilo\">";
                $this->salida.="  <td align=\"left\" colspan = 2 width=\"73%\">MEDICAMENTO DE USO CONTROLADO</td>";
                $this->salida.="<tr class=\"$estilo\">";
            }
            $this->salida.="</table>";
            $this->salida.="</td>";
            $this->salida.="</tr>";

            if ($espia == 1) {
                //la impresion de claudia
                $this->salida.="<tr class=\"$estilo\">";
                if ($vector1[$i][item] == 'NO POS' AND $vector1[$i][sw_paciente_no_pos] == '0') {
                    //reporte en impresora pos
                    //es consulta
                    if (!empty($_SESSION['CENTRAL']['TIPO_CONSULTA'])) {
                        $accion1 = ModuloGetURL('app', 'Central_de_Autorizaciones', 'user', 'ReporteFormulaMedica', array('sw_pos' => '0', 'sw_paciente_no_pos' => '0', 'impresion_pos' => '1'));
                    } else {
                        $accion1 = ModuloGetURL('app', 'Central_de_Autorizaciones', 'user', 'ReporteFormulaMedicaHosp', array('sw_pos' => '0', 'sw_paciente_no_pos' => '0', 'impresion_pos' => '1'));
                    }
                    $this->salida.="  <td colspan = 2 align=\"center\" width=\"37%\"><a href='$accion1'><img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0' title=\"IMPRIMIR POS\"> IMPRIMIR POS</a></td>";
                    //reporte en pdf
                    $mostrar = $reporte->GetJavaReport('app', 'Central_de_Autorizaciones', 'formula_medica_html', array('sw_pos' => '0', 'sw_paciente_no_pos' => '0', 'tipo_id_paciente' => $_SESSION['CENTRAL']['PACIENTE']['tipo_id'], 'paciente_id' => $_SESSION['CENTRAL']['PACIENTE']['paciente_id'], 'evolucion_id' => $_SESSION['CENTRAL']['PACIENTE']['evolucion_id']), array('rpt_name' => 'formula_medica_html', 'rpt_dir' => 'cache', 'rpt_rewrite' => TRUE));
                    $nombre_funcion = $reporte->GetJavaFunction();
                    $this->salida .=$mostrar;
                    $this->salida.="<td colspan = 2 align=\"center\" width=\"20%\"><a href=\"javascript:$nombre_funcion\"><img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0' title=\"IMPRIMIR PDF\"> IMPRIMIR PDF</a></td>";
                    //reporte en pdf antiguo caso sos media carta
                    $accion2 = ModuloGetURL('app', 'Central_de_Autorizaciones', 'user', 'ReporteFormulaMedica', array('sw_pos' => '0', 'sw_paciente_no_pos' => '0'));
                    $this->salida.="  <td colspan = 1 align=\"center\" width=\"23%\"><a href='$accion2'><img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0' title=\"IMPRIMIR MEDIA CARTA\">IMPRIMIR MEDIA CARTA4</a></td>";
                } else {
                    if ($vector1[$i][item] == 'NO POS' AND $vector1[$i][sw_paciente_no_pos] == '1') {
                        //reporte en impresora pos
                        //es consulta
                        if (!empty($_SESSION['CENTRAL']['TIPO_CONSULTA'])) {
                            $accion1 = ModuloGetURL('app', 'Central_de_Autorizaciones', 'user', 'ReporteFormulaMedica', array('sw_pos' => '0', 'sw_paciente_no_pos' => '1', 'impresion_pos' => '1'));
                        } else {
                            $accion1 = ModuloGetURL('app', 'Central_de_Autorizaciones', 'user', 'ReporteFormulaMedicaHosp', array('sw_pos' => '0', 'sw_paciente_no_pos' => '1', 'impresion_pos' => '1'));
                        }

                        $this->salida.="  <td colspan = 2 align=\"center\" width=\"37%\"><a href='$accion1'><img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0' title=\"IMPRIMIR POS\"> IMPRIMIR POS</a></td>";
                        //reporte en pdf
                        $mostrar = $reporte->GetJavaReport('app', 'Central_de_Autorizaciones', 'formula_medica_html', array('sw_pos' => '0', 'sw_paciente_no_pos' => '1', 'tipo_id_paciente' => $_SESSION['CENTRAL']['PACIENTE']['tipo_id'], 'paciente_id' => $_SESSION['CENTRAL']['PACIENTE']['paciente_id'], 'evolucion_id' => $_SESSION['CENTRAL']['PACIENTE']['evolucion_id']), array('rpt_name' => 'formula_medica_html', 'rpt_dir' => 'cache', 'rpt_rewrite' => TRUE));
                        $nombre_funcion = $reporte->GetJavaFunction();
                        $this->salida .=$mostrar;
                        $this->salida.="<td colspan = 2 align=\"center\" width=\"20%\"><a href=\"javascript:$nombre_funcion\"><img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0' title=\"IMPRIMIR PDF\"> IMPRIMIR PDF</a></td>";
                        //reporte en pdf antiguo caso sos media carta
                        $accion2 = ModuloGetURL('app', 'Central_de_Autorizaciones', 'user', 'ReporteFormulaMedica', array('sw_pos' => '0', 'sw_paciente_no_pos' => '1'));
                        $this->salida.="  <td colspan = 1 align=\"center\" width=\"23%\"><a href='$accion2'><img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0' title=\"IMPRIMIR MEDIA CARTA\">IMPRIMIR MEDIA CARTA5</a></td>";
                    } else {
                        //reporte en impresora pos
                        //es consulta
                        if (!empty($_SESSION['CENTRAL']['TIPO_CONSULTA'])) {
                            $accion1 = ModuloGetURL('app', 'Central_de_Autorizaciones', 'user', 'ReporteFormulaMedica', array('sw_pos' => '1', 'impresion_pos' => '1'));
                        } else {
                            $accion1 = ModuloGetURL('app', 'Central_de_Autorizaciones', 'user', 'ReporteFormulaMedicaHosp', array('sw_pos' => '1', 'impresion_pos' => '1'));
                        }

                        $this->salida.="  <td colspan = 2 align=\"center\" width=\"37%\"><a href='$accion1'><img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0' title=\"IMPRIMIR POS\"> IMPRIMIR POS</a></td>";
                        //reporte en pdf
                        $mostrar = $reporte->GetJavaReport('app', 'Central_de_Autorizaciones', 'formula_medica_html', array('sw_pos' => '1', 'tipo_id_paciente' => $_SESSION['CENTRAL']['PACIENTE']['tipo_id'], 'paciente_id' => $_SESSION['CENTRAL']['PACIENTE']['paciente_id'], 'evolucion_id' => $_SESSION['CENTRAL']['PACIENTE']['evolucion_id']), array('rpt_name' => 'formula_medica_html', 'rpt_dir' => 'cache', 'rpt_rewrite' => TRUE));
                        $nombre_funcion = $reporte->GetJavaFunction();
                        $this->salida .=$mostrar;
                        $this->salida.="<td colspan = 2 align=\"center\" width=\"20%\"><a href=\"javascript:$nombre_funcion\"><img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0' title=\"IMPRIMIR PDF\"> IMPRIMIR PDF</a></td>";
                        //reporte en pdf antiguo caso sos media carta
                        $accion2 = ModuloGetURL('app', 'Central_de_Autorizaciones', 'user', 'ReporteFormulaMedica', array('sw_pos' => '1'));
                        $this->salida.="  <td colspan = 1 align=\"center\" width=\"23%\"><a href='$accion2'><img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0' title=\"IMPRIMIR MEDIA CARTA\">IMPRIMIR MEDIA CARTA6</a></td>";
                    }
                }
                $this->salida.="</tr>";
                $this->salida.="</table><br>";
            }
        }
//opcion para imprimir medicamentos de uso controlado
        if ($total_medicamentos_uso_controlado > 0) {
            $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
            $this->salida.="<tr class=\"modulo_table_title\">";
            $this->salida.="<td COLSPAN = 3 align=\"center\" >MEDICAMENTOS DE USO CONTROLADO</td>";
            $this->salida.="</tr>";

            $this->salida.="<tr class=\"modulo_list_claro\">";
            //reporte en impresora pos
            $accion1 = ModuloGetURL('app', 'Central_de_Autorizaciones', 'user', 'ReporteFormulaMedica', array('sw_uso_controlado' => '1', 'impresion_pos' => '1'));
            $this->salida.="<td align=\"center\" width=\"30%\"><a href='$accion1'><img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0' title=\"IMPRIMIR POS\"> IMPRIMIR POS</a></td>";

            //reporte en pdf
            //alex
            $mostrar = $reporte->GetJavaReport('app', 'Central_de_Autorizaciones', 'formula_medica_html', array('sw_uso_controlado' => '1', 'tipo_id_paciente' => $_SESSION['CENTRAL']['PACIENTE']['tipo_id'], 'paciente_id' => $_SESSION['CENTRAL']['PACIENTE']['paciente_id'], 'evolucion_id' => $_SESSION['CENTRAL']['PACIENTE']['evolucion_id']), array('rpt_name' => 'formula_medica_html', 'rpt_dir' => 'cache', 'rpt_rewrite' => TRUE));
            $nombre_funcion = $reporte->GetJavaFunction();
            $this->salida .=$mostrar;
            $this->salida.="<td align=\"center\" width=\"30%\"><a href=\"javascript:$nombre_funcion\"><img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0' title=\"IMPRIMIR PDF\"> IMPRIMIR PDF</a></td>";
            //fin de alex
            //reporte en pdf antiguo caso sos media carta
            $accion2 = ModuloGetURL('app', 'Central_de_Autorizaciones', 'user', 'ReporteFormulaMedica', array('sw_uso_controlado' => '1'));
            $this->salida.="<td align=\"center\" width=\"20%\"><a href='$accion2'><img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0' title=\"IMPRIMIR MEDIA CARTA\">IMPRIMIR MEDIA CARTA7</a></td>";


            $this->salida.="</tr>";
            $this->salida.="</table><br>";
        }
    }

    function FormaPlanTerapeutico($ingreso, $paciente, &$rep) {
        $paciente['ingreso'] = $ingreso;

        $this->salida .= "  <br>\n";
        $this->salida .= "  <table align=\"center\" border=\"0\" class=\"modulo_table_list\" width=\"80%\" class=\"modulo_list_claro\">\n";
        $this->salida .= "    <tr align=\"center\" class=\"formulacion_table_list\">\n";
        $this->salida .= "      <td width=\"50%\">IMPRIMIR PLAN TERAPEUTICO</td>\n";

        $mostrar = $rep->GetJavaReport('app', 'ImpresionHC', 'PlanTerapeutico', $paciente, array('rpt_name' => 'planTerapeutico', 'rpt_dir' => '', 'rpt_rewrite' => TRUE));
        $funcion = $rep->GetJavaFunction();
        $this->salida .= "      <td class=\"modulo_list_claro\">\n";
        $this->salida .= $mostrar;
        $this->salida .= "        <a href=\"javascript:" . $funcion . "\" class =\"label_error\">\n";
        $this->salida .= "          <img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0'>INGRESO: " . $ingreso . "\n";
        $this->salida .= "        </a>\n";
        $this->salida .= "      </td>\n";
        $this->salida .= "    </tr>\n";
        $this->salida .= "  </table>\n";
        return true;
    }

    /**
     * Funcion donde se crea un link para el reporte de la formula de optometria
     *
     * @param integer $evolucion 
     * @param object $rep Objeto de la clase GetReports
     *
     * @return boolean
     */
    function FormaFormulaOptometria($evolucion, &$rep) {
        $paciente['evolucion'] = $evolucion;
        $xml = Autocarga::factory("ReportesCsv");

        $this->salida .= "  <br>\n";
        $this->salida .= "  <table align=\"center\" border=\"0\" class=\"modulo_table_list\" width=\"80%\" class=\"modulo_list_claro\">\n";
        $this->salida .= "    <tr align=\"center\" class=\"formulacion_table_list\">\n";
        $this->salida .= "      <td width=\"50%\">IMPRIMIR FORMULA DE OPTOMETRIA</td>\n";

        $mostrar = $rep->GetJavaReport('app', 'ImpresionHC', 'FormulaOptometria', $paciente, array('rpt_name' => '', 'rpt_dir' => '', 'rpt_rewrite' => TRUE));
        $funcion = $rep->GetJavaFunction();
        $this->salida .= "      <td class=\"modulo_list_claro\">\n";
        $this->salida .= $mostrar;
        $this->salida .= "        <a href=\"javascript:" . $funcion . "\" class =\"label_error\">\n";
        $this->salida .= "          <img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0'>HTML\n";
        $this->salida .= "        </a>\n";
        $this->salida .= "      </td>\n";

        $this->salida .= "      <td class=\"modulo_list_claro\">\n";
        $this->salida .= $xml->GetJavacriptReporteFPDF('app', 'ImpresionHC', 'FormulaOptometria', $paciente, array("interface" => 5));
        $fnc1 = $xml->GetJavaFunction();
        $this->salida .= "        <a href=\"javascript:" . $fnc1 . "\" class =\"label_error\">\n";
        $this->salida .= "          <img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0'>MEDIA CARTA\n";
        $this->salida .= "        </a>\n";
        $this->salida .= "      </td>\n";

        $this->salida .= "    </tr>\n";
        $this->salida .= "  </table>\n";
        return true;
    }

//***********************FIN FUNCIONES CLAUDIA
}

//fin clase
?>
